<?php
defined('BASEPATH') or exit('No direct script access allowed');

class List_do_monitoring_supply extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            if($row['tanggal'] != null){
                $row['tanggal'] = $this->load->view('additional/action_rincian_proses_monitoring_supply', [
                    'tanggal' => $row['tanggal'],
                    'id_do_sales_order' => $row['id_do_sales_order'],
                ], true);
            }
            
            if($row['tanggal_picking'] != null){
                $row['tanggal_picking'] = $this->load->view('additional/action_rincian_picking_monitoring_supply', [
                    'tanggal_picking' => $row['tanggal_picking'],
                    'id_do_sales_order' => $row['id_do_sales_order'],
                ], true);
            }

            if($row['tanggal_scan'] != null){
                $row['tanggal_scan'] = $this->load->view('additional/action_rincian_scan_monitoring_supply', [
                    'tanggal_scan' => $row['tanggal_scan'],
                    'id_do_sales_order' => $row['id_do_sales_order'],
                ], true);
            }

            if($row['tanggal_faktur'] != null){
                $row['tanggal_faktur'] = $this->load->view('additional/action_rincian_faktur_monitoring_supply', [
                    'tanggal_faktur' => $row['tanggal_faktur'],
                    'id_do_sales_order' => $row['id_do_sales_order'],
                ], true);
            }

            if($row['tanggal_packing'] != null){
                $row['tanggal_packing'] = $this->load->view('additional/action_rincian_packing_monitoring_supply', [
                    'tanggal_packing' => $row['tanggal_packing'],
                    'id_do_sales_order' => $row['id_do_sales_order'],
                ], true);
            }

            if($row['tanggal_shipping'] != null){
                $row['tanggal_shipping'] = $this->load->view('additional/action_rincian_shipping_monitoring_supply', [
                    'tanggal_shipping' => $row['tanggal_shipping'],
                    'id_do_sales_order' => $row['id_do_sales_order'],
                ], true);
            }

            $row['index'] = $this->input->post('start') + $index;

            $data[] = $row;
            $index++;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_total_data(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('do.id_do_sales_order')
        ->select('date_format(do.tanggal, "%d/%m/%Y") as tanggal')
        ->select('
        case
            when pl.start_pick is null then pl.start_pick
            else date_format(pl.start_pick, "%d/%m/%Y")
        end as tanggal_picking', false)
        ->select('
        case
            when pl.tanggal_mulai_scan is null then pl.tanggal_mulai_scan
            else date_format(pl.tanggal_mulai_scan, "%d/%m/%Y")
        end as tanggal_scan', false)
        ->select('
        case
            when ps.tgl_faktur is null then ps.tgl_faktur
            else date_format(ps.tgl_faktur, "%d/%m/%Y")
        end as tanggal_faktur', false)
        ->select('
        case
            when ps.tgl_packing_sheet is null then ps.tgl_packing_sheet
            else date_format(ps.tgl_packing_sheet, "%d/%m/%Y")
        end as tanggal_packing', false)
        ->select('
        case
            when sp.tanggal is null then sp.tanggal
            else date_format(sp.tanggal, "%d/%m/%Y")
        end as tanggal_shipping', false)
        ->select('
            case
                when sp.tanggal is not null then TIMESTAMPDIFF(second, so.created_at, sp.created_at) * 1000
                when ps.tgl_packing_sheet is not null then TIMESTAMPDIFF(second, so.created_at, ps.tgl_packing_sheet) * 1000
                when ps.tgl_faktur is not null then TIMESTAMPDIFF(second, so.created_at, ps.tgl_faktur) * 1000
                when pl.tanggal_mulai_scan is not null then TIMESTAMPDIFF(second, so.created_at, pl.tanggal_mulai_scan) * 1000
                when pl.created_at is not null then TIMESTAMPDIFF(second, so.created_at, pl.created_at) * 1000
                when do.created_at is not null then TIMESTAMPDIFF(second, so.created_at, do.created_at) * 1000
                else 0
            end as lead_time_so
        ', false)
        ->select('
            case
                when sp.tanggal is not null then TIMESTAMPDIFF(second, do.created_at, sp.created_at) * 1000
                when ps.tgl_packing_sheet is not null then TIMESTAMPDIFF(second, do.created_at, ps.tgl_packing_sheet) * 1000
                when ps.tgl_faktur is not null then TIMESTAMPDIFF(second, do.created_at, ps.tgl_faktur) * 1000
                when pl.tanggal_mulai_scan is not null then TIMESTAMPDIFF(second, do.created_at, pl.tanggal_mulai_scan) * 1000
                when pl.created_at is not null then TIMESTAMPDIFF(second, do.created_at, pl.created_at) * 1000
                else 0
            end as lead_time_do
        ', false)
        ->select('do.status')
        ->from('tr_h3_md_do_sales_order as do')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order', 'left')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list', 'left')
        ->join('tr_h3_md_surat_pengantar_items as spi', 'spi.id_packing_sheet = ps.id_packing_sheet', 'left')
        ->join('tr_h3_md_surat_pengantar as sp', 'sp.id_surat_pengantar = spi.id_surat_pengantar', 'left')
        ->where('do.id_sales_order', $this->input->post('id_sales_order'))
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('do.id_do_sales_order', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('do.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function get_filtered_data() {
        $this->make_datatables();
        return $this->db->count_all_results();
    }

    public function get_total_data() {
        $this->make_query();
        return $this->db->count_all_results();
    }
}
