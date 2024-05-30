<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Parts extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('h3_dealer_gudang_h23_model', 'gudang');
        $this->load->model('dealer_model', 'dealer');
    }

    public function all()
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
        $this->db->select('mp.harga_md_dealer as harga_saat_dibeli');
        $this->db->from('ms_part as mp');

        $search = $this->input->post('search')['value'];
        if ($search!='') {
            $this->db->like('mp.id_part', $search);
            $this->db->or_like('mp.nama_part', $search);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('mp.id_part', 'ASC');
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
