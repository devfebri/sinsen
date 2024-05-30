<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Open_view_qty_po extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = [];
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $data[] = $row;
            $index++;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'data' => $data,
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_record_total(),
        ]);
    }
    
    public function make_query() {
        // PO Hotline untuk qty PO
        $hotline_sudah_dipenuhi = $this->db
        ->select('SUM(dop.qty_supply) as kuantitas', false)
        ->from('tr_h3_md_sales_order as so')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order')
        ->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_sales_order')
        ->where('so.id_ref = po.referensi_po_hotline', null, false)
        ->where('dop.id_part = pop.id_part', null, false)
        ->where('do.sudah_create_faktur', 1)
        ->where('so.created_at > pb.created_at', null, false)
        ->get_compiled_select();

        $qty_po_hotline = $this->db
        ->select('"HLO" as jenis_po')
        ->select("IFNULL( (SUM(pbi.qty_diterima - IFNULL(({$hotline_sudah_dipenuhi}), 0)) ), 0 ) as sisa_belum_terpenuhi", false)
        // ->select('pbi.no_penerimaan_barang')
        // ->select('pbi.qty_diterima')
        // ->select('pbi.no_po')
        ->from('tr_h3_md_penerimaan_barang_items as pbi')
        ->join('tr_h3_md_penerimaan_barang as pb', '(pb.no_penerimaan_barang = pbi.no_penerimaan_barang)')
        ->join('tr_h3_md_purchase_order_parts as pop', '(pop.id_purchase_order = pbi.no_po AND pop.id_part = pbi.id_part)')
        ->join('tr_h3_md_purchase_order as po', '(po.id_purchase_order = pop.id_purchase_order)')
        ->where('po.jenis_po', 'HTL')
        ->where('pbi.id_part', $this->input->post('id_part_open_view_qty_po'))
        ->where('pbi.tersimpan', 1)
        ->get_compiled_select();

        // PO Urgent untuk qty PO
        $urgent_sudah_dipenuhi = $this->db
        ->select('SUM(dop.qty_supply) as kuantitas', false)
        ->from('tr_h3_md_sales_order as so')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order')
        ->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
        ->where('so.id_ref = pop.referensi', null, false)
        ->where('dop.id_part = pop.id_part', null, false)
        ->where('do.sudah_create_faktur', 1)
        ->where('so.created_at > pb.created_at', null, false)
        ->get_compiled_select();

        $qty_po_urgent = $this->db
        ->select('"URG" as jenis_po')
        ->select("IFNULL( (SUM(pbi.qty_diterima - IFNULL(({$urgent_sudah_dipenuhi}), 0))), 0 ) as sisa_belum_terpenuhi", false)
        // ->select('pbi.no_penerimaan_barang')
        // ->select('pb.created_at')
        // ->select('pbi.qty_diterima')
        // ->select('pbi.no_po')
        // ->select('pop.referensi')
        // ->select("IFNULL(({$urgent_sudah_dipenuhi}), 0) as urgent_sudah_dipenuhi", false)
        ->from('tr_h3_md_penerimaan_barang_items as pbi')
        ->join('tr_h3_md_penerimaan_barang as pb', '(pb.no_penerimaan_barang = pbi.no_penerimaan_barang)')
        ->join('tr_h3_md_purchase_order_parts as pop', '(pop.id_purchase_order = pbi.no_po AND pop.id_part = pbi.id_part)')
        ->join('tr_h3_md_purchase_order as po', '(po.id_purchase_order = pop.id_purchase_order)')
        ->where('po.jenis_po', 'URG')
        ->where('pbi.id_part', $this->input->post('id_part_open_view_qty_po'))
        ->where('pbi.tersimpan', 1)
        ->get_compiled_select();

        $this->db
        ->select('qty_po.jenis_po')
        ->select('qty_po.sisa_belum_terpenuhi')
        ->from("
            (
                ({$qty_po_hotline})
                UNION
                ({$qty_po_urgent})
            ) as qty_po
        ");
    }

    public function make_datatables() {
        $this->make_query();

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('jenis_po', 'desc');
        }
    }

    public function limit(){
        if ($this->input->post('length') != - 1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
    }

    public function get_filtered_data() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function get_record_total(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
