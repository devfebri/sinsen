<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ActualStock extends CI_Controller
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
        $this->db->select('ds.id_part, mp.nama_part, mp.harga_dealer_user as harga_saat_dibeli, ds.id_gudang, ds.id_rak, ds.stock');

        $this->db->from("ms_h3_dealer_stock as ds");
        
        $this->db->join('ms_part as mp', 'ds.id_part_int = mp.id_part_int');
        $this->db->join('ms_gudang_h23 as mg', 'mg.id_gudang = ds.id_gudang');
        $this->db->join('ms_lokasi_rak_bin as lrb', 'lrb.id_rak = ds.id_rak');

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
    }

    

    public function make_datatables()
    {
        $this->make_query();
        
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
