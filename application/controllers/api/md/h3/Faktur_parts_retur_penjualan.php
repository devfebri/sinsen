<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Faktur_parts_retur_penjualan extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('H3_md_lokasi_rak_model', 'lokasi_rak');
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $lokasi = $this->lokasi_rak->suggest_lokasi($row['id_part'], $row['qty_faktur'], true);

            if($lokasi != null){
                $row['id_lokasi_rak'] = $lokasi['id_lokasi_rak'];
                $row['kode_lokasi_rak'] = $lokasi['kode_lokasi_rak'];
            }

            $row['action'] = $this->load->view('additional/md/h3/action_faktur_parts_retur_penjualan', [
                'data' => json_encode($row),
                'id_part' => $row['id_part']
            ], true);

            $data[] = $row;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_total_data(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $qty_faktur = $this->db
        ->select('SUM(plp.qty_disiapkan) as qty_disiapkan')
        ->from('tr_h3_md_picking_list_parts as plp')
        ->where('plp.id_picking_list = pl.id_picking_list')
        ->where('plp.id_part = dop.id_part')
        ->get_compiled_select();
        
        $this->db
        ->select('dop.id_part')
        ->select('p.nama_part')
        ->select('p.harga_dealer_user')
        ->select("IFNULL( ({$qty_faktur}), 0) as qty_faktur", false)
        ->select('dop.tipe_diskon_campaign')
        ->select('dop.diskon_campaign')
        ->select('dop.tipe_diskon_satuan_dealer')
        ->select('dop.diskon_satuan_dealer')
        ->select('1 as qty_retur')
        ->select('"" as id_lokasi_rak')
        ->select('"" as kode_lokasi_rak')
        ->select('"" as alasan')
        ->from('tr_h3_md_packing_sheet as ps')
        // ->join('tr_h3_md_picking_list_parts as plp', 'plp.id_picking_list = ps.id_picking_list')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = pl.id_ref)')
        ->join('ms_part as p', 'p.id_part = dop.id_part')
        ->where('ps.no_faktur', $this->input->post('no_faktur'))
        ->where("IFNULL( ({$qty_faktur}), 0) > 0", null, false);
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('dop.id_part', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('dop.id_part', 'asc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function get_filtered_data() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function get_total_data() {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
