<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan_unit_sl_datatable extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

		$no = $_POST['start'];
        $data = array();
        foreach ($this->db->get()->result_array() as $rows) {
            $jum = $this->db->query("SELECT COUNT(no_rangka) AS jum FROM tr_shipping_list WHERE no_shipping_list = '$rows[no_shipping_list]'")->row_array();
            $rows['action'] = $this->load->view('additional/md/h1/action_sl_penerimaan_unit', [
                'data' => json_encode($rows),
                'no_shipping_list' => $rows['no_shipping_list'],
                'jum' => $jum['jum'],
            ], true);
            // $data[] = $row;
            $no++;
            $row = array();
			$row[] = $no;
			$row[] = $rows['no_shipping_list'];
            $row[] = $jum['jum'];
            $row[] = $rows['action'];
			// $row[] = $no;
			// $row[] = "A";
            // $row[] = "A";
            // $row[] = "A";
			$data[] = $row;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_total_data(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $this->db
        // ->distinct()
        ->select('DISTINCT(no_shipping_list) as no_shipping_list')
        // ->select('DISTINCT(no_shipping_list) as no_shipping_list')
        ->from('tr_shipping_list')
        ->join('tr_invoice','tr_shipping_list.no_shipping_list = tr_invoice.no_sl','left')
        ->where('tr_invoice.status','approve')
        ->where('tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL)')
        ->order_by('tgl_sl','DESC')
        // ->where('tr_shipping_list.no_shipping_list', '1100/2022/13277')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->like('tr_shipping_list.no_shipping_list', $search);
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('tgl_sl', 'desc');
        }
    }

    public function limit(){
        if ($this->input->post('length') != - 1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
    }

    public function get_filtered_data() {
        $this->make_query();
        return $this->db->get()->num_rows();
    }

    public function get_total_data(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
