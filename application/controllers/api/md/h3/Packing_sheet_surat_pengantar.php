<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Packing_sheet_surat_pengantar extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $fetch_data = $this->make_datatables();
        $data = array();
        foreach ($fetch_data as $rs) {
            $sub_array = (array)$rs;
            $row = json_encode($rs);
            $link = '<button data-dismiss=\'modal\' onClick=\'return pilih_packing_sheet_surat_pengantar(' . $row . ')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
            $sub_array['aksi'] = $link;
            $data[] = $sub_array;
        }
        $output = array("draw" => intval($_POST["draw"]), "recordsFiltered" => $this->get_filtered_data(), "data" => $data);
        echo json_encode($output);
    }
    
    public function make_query() {
        $jumlah_koli = $this->db->distinct()->select('count(no_dus)')->from('tr_h3_md_scan_picking_list_parts as splp')->where('splp.id_picking_list = ps.id_picking_list')->get_compiled_select();

        $packing_sheet_yang_dikirim = $this->db
        ->select('spi.id_packing_sheet')
        ->from('tr_h3_md_surat_pengantar_items as spi')->get_compiled_select();

        $this->db->select('ps.*')
        ->select('date_format(ps.tgl_packing_sheet, "%d-%m-%Y") as tgl_packing_sheet')
        ->select('md.nama_dealer as nama_customer')
        ->select("($jumlah_koli) as jumlah_koli")
        ->select('pl.id_ref')
        ->from("tr_h3_md_packing_sheet as ps")
        ->join("tr_h3_md_picking_list as pl", 'ps.id_picking_list = pl.id_picking_list')
        ->join('ms_dealer as md', 'md.id_dealer = pl.id_dealer')
        ->where("ps.id_packing_sheet not in (({$packing_sheet_yang_dikirim}))")
        ;

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ps.id_packing_sheet', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.id_packing_sheet', 'ASC');
        }
    }

    public function make_datatables() {
        $this->make_query();
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        // $query = $this->db->get_compiled_select();
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data() {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
}
