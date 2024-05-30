<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Scan_picking_list_parts extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_scan_picking_list_parts', [
                'data' => $row
            ], true);
            $data[] = $row;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('sum(splp_sq.qty_scan)')
        ->from('tr_h3_md_scan_picking_list_parts as splp_sq')
        ->where('splp_sq.id_part = plp.id_part')
        ->where('splp_sq.id_lokasi_rak = plp.id_lokasi_rak')
        // ->where('splp_sq.serial_number = plp.serial_number')
        ->where('splp_sq.id_picking_list = plp.id_picking_list');
        if($this->input->post('is_ev') == 'EV'){
            $this->db->where('splp_sq.serial_number = plp.serial_number');
        }
        if($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB'){
            $this->db->where('splp_sq.id_tipe_kendaraan = plp.id_tipe_kendaraan');
        }
        
        $total_part_telah_discan = $this->db->get_compiled_select();

        $this->db
        ->select('dop.qty_supply')
        ->from('tr_h3_md_do_sales_order_parts as dop')
        ->where('dop.id_do_sales_order = pl.id_ref')
        ->where('dop.id_part = plp.id_part');

        if($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB'){
            $this->db->where('dop.id_tipe_kendaraan = plp.id_tipe_kendaraan');
        }

        $qty_do = $this->db->get_compiled_select();

        $this->db
        ->select('p.id_part_int')
        ->select('plp.id_part')
        ->select('plp.id_lokasi_rak')
        ->select('plp.serial_number')
        ->select('p.nama_part')
        ->select("IFNULL(({$qty_do}), 0) as qty_do", false)
        ->select('ifnull(plp.qty_disiapkan, 0) as qty_picking')
        ->select("ifnull( ({$total_part_telah_discan}), 0) as qty_sudah_scan")
        ->select("( ifnull(plp.qty_disiapkan, 0) - ifnull( ({$total_part_telah_discan}), 0) ) as qty_belum_scan")
        ->select("( ifnull(plp.qty_disiapkan, 0) - ifnull( ({$total_part_telah_discan}), 0) ) as qty_scan")
        ->from('tr_h3_md_picking_list_parts as plp')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = plp.id_picking_list')
        ->join('ms_part as p', 'p.id_part_int = plp.id_part_int')
        ->where('plp.id_picking_list', $this->input->post('id_picking_list'))
        ->where("IFNULL(({$qty_do}), 0) > 0", null, false)
        ->where('plp.qty_disiapkan >', 0)
        ;

        if($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB'){
            $this->db->select('plp.id_tipe_kendaraan');
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('plp.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('plp.id_part', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}