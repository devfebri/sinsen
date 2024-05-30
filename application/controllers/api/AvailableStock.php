<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AvailableStock extends CI_Controller
{
    public $table = "ms_h3_dealer_stock";
    public $pk = "id";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('h3_dealer_gudang_h23_model', 'gudang');
        $this->load->model('dealer_model', 'dealer');
        $this->load->model('h3_dealer_dealer_terdekat_model', 'dealer_terdekat');
    }

    public function index()
    {
        $fetch_data = $this->make_datatables();
        $data = array();
        foreach ($fetch_data as $rs) {
            $sub_array   = (array) $rs;
            $row         = json_encode($rs);
            $link        ='<button data-dismiss=\'modal\' onClick=\'return pilihPart('.$row.')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
            $sub_array['aksi'] = $link;
            $data[] = $sub_array;
        }

        $output = array(
          "draw"            =>     intval($_POST["draw"]),
          "recordsFiltered" =>     $this->get_filtered_data(),
          "data"            =>     $data
        );
        echo json_encode($output);
    }

    public function make_query()
    {
        $select_query = $this->input->post('select_query');
        if($select_query != null){
            $this->db->select($select_query);
        }else{
            $this->db->select('*');
        }

        $this->db->from("ms_part as mp");
        
        $this->db->join('ms_h3_dealer_stock as ds', 'ds.id_part_int = mp.id_part_int'); // pakai left join - 10-04-2023
        $this->db->join('ms_gudang_h23 as mg', 'mg.id_gudang = ds.id_gudang', 'left');
        $this->db->join('ms_lokasi_rak_bin as lrb', 'lrb.id_rak = ds.id_rak', 'left');

        $warehouse_asal = $this->input->post('warehouse_asal');
        if($warehouse_asal != null){
            $this->db->where('ds.id_gudang', $warehouse_asal);
        }

        $rak_warehouse_asal = $this->input->post('rak_warehouse_asal');
        if($rak_warehouse_asal != null){
            $this->db->where('ds.id_rak', $rak_warehouse_asal);
        }
        

        $search = $this->input->post('search')['value'];
        if ($search!='') {
            $this->db->group_start();
            $this->db->like('ds.id_part', $search);
            $this->db->or_like('mp.nama_part', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by($this->pk, 'ASC');
        }

        // echo $this->db->get_compiled_select();
        // die();
    }

    public function make_query_dealer_terdekat()
    {
        $so_type = $this->input->post('so_type');
        $id_dealer = $this->m_admin->cari_dealer();
        $this->db->flush_cache();
        foreach ($this->dealer_terdekat->get(['id_dealer' => $id_dealer]) as $dealer) {
            $dealer_terdekat[] = $dealer->id_dealer_terdekat;
        }

        $select_query = $this->input->post('select_query');
        if($select_query != null){
            $this->db->select($select_query);
        }else{
            $this->db->select('*');
        }

        $this->db->from("ms_part as mp");
        $this->db->join('ms_h3_dealer_stock as ds', 'ds.id_part_int = mp.id_part_int');
        $this->db->join('ms_gudang_h23 as mg', 'mg.id_gudang = ds.id_gudang');
        $this->db->join('ms_lokasi_rak_bin as lrb', 'lrb.id_rak = ds.id_rak');

        $this->db->where_in('ds.id_dealer', $dealer_terdekat);

        $warehouse_asal = $this->input->post('warehouse_asal');
        if($warehouse_asal != null){
            $this->db->where('mg.id_gudang', $warehouse_asal);
        }

        $search = $this->input->post('search')['value'];
        if ($search!='') {
            $this->db->like('ds.id_part', $search);
            $this->db->or_like('mp.nama_part', $search);
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by($this->pk, 'ASC');
        }

        // echo $this->db->get_compiled_select();
        // die();
    }

    public function make_query_main_dealer()
    {
        $select_query = $this->input->post('select_query');
        if($select_query != null AND FALSE){
            $this->db->select($select_query);
        }else{
            $this->db->select('*');
        }

        $this->db->from("tr_stok_part as tsp");
        $this->db->join('ms_part as mp', 'tsp.id_part=mp.id_part');

        $warehouse_asal = $this->input->post('warehouse_asal');
        if($warehouse_asal != null){
            $this->db->where('mg.id_gudang', $warehouse_asal);
        }

        $search = $this->input->post('search')['value'];
        if ($search!='') {
            $this->db->like('tsp.id_part', $search);
            $this->db->or_like('mp.nama_part', $search);
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('tsp.id_stok_part', 'ASC');
        }

        // echo $this->db->get_compiled_select();
        // die();
    }

    public function make_datatables()
    {
        $so_type = $this->input->post('so_type');
        if($so_type == 'dealer_lain'){
            $this->make_query_dealer_terdekat();
        }elseif($so_type == 'main_dealer'){
            $this->make_query_main_dealer();
        }else{
            $this->make_query();
        }
        
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data()
    {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
}
