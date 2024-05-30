<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealer_sim_part extends CI_Controller {

    public $table = 'ms_dealer';

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $fetch_data = $this->make_datatables();
        $data = array();
        foreach ($fetch_data as $each) {
            $sub_array = (array) $each;
            $sub_array['action'] = $this->load->view('additional/md/h3/action_dealer_sim_part', [
                'data' => json_encode($each),      
                'id_dealer' => $each->id_dealer,      
            ], true);
            $data[] = $sub_array;
        }
        $output = array("draw" => intval($_POST["draw"]), "recordsFiltered" => $this->get_filtered_data(), "data" => $data);
        echo json_encode($output);
    }
    
    public function make_query() {
        $this->db
        ->select('d.id_dealer')
        ->select('d.kode_dealer_md')
        ->select('d.nama_dealer')
        ->select('d.alamat')
        ->select('"" as tipe_diskon')
        ->select('0 as diskon_fixed')
        ->select('0 as diskon_reguler')
        ->select('0 as diskon_hotline')
        ->select('0 as diskon_urgent')
        ->select('0 as diskon_other')
        ->select('kab.kabupaten')
		->select('kab.id_kabupaten')
        ->from('ms_dealer as d')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan')
		->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
		->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
		->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi')
        ;

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('d.kode_dealer_md', $search);
            $this->db->or_like('d.nama_dealer', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('d.nama_dealer', 'asc');
        }
    }

    public function make_datatables() {
        $this->make_query();
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data() {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
}
