<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H2_dealer_fu_list_datatable extends CI_Controller
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

    public function getDataMCType() {
        $this->make_datatables(); $this->limit();
        // $id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
        $data = array();
        $no=1;
        foreach ($this->db->get()->result_array() as $record) {
            // $pilihKendaraan ='';
            // $link2 = '';

            // if(count($this->input->post('filters')) > 0){
            //   if(in_array($id_tipe_kendaraan, $this->input->post('filters'))){
            //       $pilihKendaraan = 'checked';
            //   }
            // }

            // $link2 = '<input $pilihKendaraan type="checkbox" data-id_tipe_kendaraan="'.$no++.'">';
            
            // $link2 = "<input $pilihKendaraan type='checkbox' data-id_tipe_kendaraan=".$no++. ">";
            

            // 'data' => json_encode($record),
            // 'id_tipe_kendaraan' => $record['id_tipe_kendaraan'],
            // 'tipe_ahm' => $record['tipe_ahm']

            // $records   = array();
            // // $records[] = 2;
            // $records[] = $record->id_tipe_kendaraan;
            // $records[] = $record->tipe_ahm;
            // $records[] = $link2;

            
            // $record[] = $record->id_tipe_kendaraan;
            // $record[] = $record->tipe_ahm;
            // $record['action']= '';

            $record['action'] = $this->load->view('additional/action_pilih_kendaraan', [
                'data' => json_encode($record),
                'id_tipe_kendaraan' => $record['id_tipe_kendaraan'],
                'tipe_ahm' => $record['tipe_ahm']
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
        $this->db->select('id_tipe_kendaraan')
        ->select('tipe_ahm')
        ->from('ms_tipe_kendaraan');
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('id_tipe_kendaraan', $search);
            $this->db->or_like('tipe_ahm', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $id_tipe_kendaraan = $_POST['columns'][$indexColumn]['id_tipe_kendaraan'];
            // $tipe_ahm = $_POST['columns'][$indexColumn]['tipe_ahm'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $id_tipe_kendaraan != '' ? $id_tipe_kendaraan : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('id_tipe_kendaraan', 'asc');
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
