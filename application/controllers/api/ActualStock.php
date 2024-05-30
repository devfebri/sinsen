<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ActualStock extends CI_Controller
{
    public $table = "ms_h3_dealer_stock";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('h3_dealer_gudang_h23_model', 'gudang');
        $this->load->model('dealer_model', 'dealer');
        $this->load->model('h3_dealer_dealer_terdekat_model', 'dealer_terdekat');
        $this->load->model('h3_dealer_sales_order_parts_model', 'sales_order_parts');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $book_by_sales = $this->sales_order_parts->book_by_sales_order($this->m_admin->cari_dealer(), $row['id_part'], $row['id_gudang'], $row['id_rak']);

            $row['book_by_sales'] = $book_by_sales;
            $row['stock'] -= $book_by_sales;
            $row['aksi'] = $this->load->view('additional/action_actual_stok_dealer_sales_order', [
                'data' => json_encode($row),
                'id_part' => $row['id_part'],
                'id_rak' => $row['id_rak'],
                'stock' => $row['stock'],
            ], true);

            $row['index'] = $this->input->post('start') + $index . '.';
            $data[] = $row;
            $index++;
        }

        $output = array(
          'draw' => intval($_POST['draw']),
          'recordsFiltered' => $this->get_filtered_data(),
          'recordsTotal' => $this->get_total_data(),
          'data' => $data
        );

        send_json($output);
    }

    public function make_query()
    {
        $this->db
        ->select('p.id_part_int')
        ->select('ds.id_part')
        ->select('p.nama_part')
        ->select('p.nama_part_bahasa')
        ->select('p.harga_dealer_user as harga_saat_dibeli')
        ->select('concat("Rp", format(p.harga_dealer_user, 0, "ID_id")) as het')
        ->select('s.satuan')
        ->select('p.kelompok_part')
        ->select('ds.id_gudang')
        ->select('ds.id_rak')
        ->select('ds.stock')
        ->select('"" as tipe_diskon')
        ->select('"" as diskon_value')
        ->from("ms_h3_dealer_stock as ds")
        ->join('ms_part as p', 'p.id_part_int = ds.id_part_int')
        ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
        ->where('ds.id_dealer', $this->m_admin->cari_dealer())
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        // $search = trim($this->input->post('search')['value']);

        // if ($search!='') {
        //     $this->db->group_start();
        //     $this->db->like('ds.id_part', $search);
        //     $this->db->or_like('p.nama_part', $search);
        //     $this->db->group_end();
        // }

        
        $search_kode = $this->input->post('search_kode');
        $search_nama_part = $this->input->post('search_nama_part');
        $search_nama_part_bahasa = $this->input->post('search_nama_part_bahasa');

        if($search_kode != ''){
            $this->db->group_start();
            $this->db->like('ds.id_part', $search_kode);
            $this->db->group_end();
        }

        if ($search_nama_part != '') {
            $this->db->group_start();
            $this->db->like('p.nama_part', $search_nama_part);
            $this->db->group_end();
        }

        if ($search_nama_part_bahasa != '') {
            $this->db->group_start();
            $this->db->like('p.nama_part_bahasa', $search_nama_part_bahasa);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ds.id_part', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function get_filtered_data()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function get_total_data(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}