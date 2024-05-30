<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H2_dealer_last_toj_table_datatable extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}

        $this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
    }

    public function getDataToj() {
        $this->make_datatables(); 
        $this->limit();
        $data = array();
        $no=1;
        foreach ($this->db->get()->result_array() as $record) {

            $record['action'] = $this->load->view('additional/action_pilih_last_toj', [
                'data' => json_encode($record),
                'id_type' => $record['id_type'],
                'deskripsi' => $record['deskripsi']
              ], true);
            $data[] = $record;
        }

        $output = [
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_record_total(),
            'data' => $data
        ];
        send_json($output);
    }

    public function make_query() {
        $this->db->select('id_type')
                 ->select('deskripsi')
                 ->from('ms_h2_jasa_type')
                 ->where('active',1);
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('deskripsi', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $id_type = $_POST['columns'][$indexColumn]['id_type'];
            // $tipe_ahm = $_POST['columns'][$indexColumn]['tipe_ahm'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $id_type != '' ? $id_type : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('id_type', 'asc');
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

    public function get_record_total(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
