<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_po_logistik extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_h3_md_rekap_po_logitstik', [
                'id_checker' => $row['id_checker'],
            ], true);

            // $row['dokumen_nrfs_id'] = $this->load->view('additional/action_open_po_logistik_rekap_po_logistik', [
            //     'dokumen_nrfs_id' => $row['dokumen_nrfs_id'],
            // ], true);

            $row['index'] = $this->input->post('start') + $index . '.';

            $data[] = $row;
            $index++;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
        ]);
    }

    private function make_query()
    {
        $this->db
        ->select('c.id_checker')
        ->select('scan_barcode.no_shipping_list')
        ->select('c.tgl_checker')
        ->select('c.no_mesin')
        ->select('c.keterangan')
        ->select('scan_barcode.no_rangka')
        ->select('scan_barcode.tipe_motor')
        ->select('c.status_checker')
        ->from('tr_checker as c')
        ->join('tr_scan_barcode as scan_barcode', 'scan_barcode.no_mesin = c.no_mesin', 'left')
        ->where('c.status_checker', 'H3-Approved')
        ;
    }

    private function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('c.id_checker', $search);
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('c.created_at', 'desc');
        }
    }

    private function limit(){
        if ($this->input->post('length') != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    private function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    private function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }

    public function add_session_rekap_po_logistik(){
        $id_checker_selected = $this->session->userdata('id_checker_selected');
        $id_checker = $this->input->post('id_checker');
        if($id_checker_selected == null || count($id_checker_selected) < 1){
            $id_checker_selected[] = $id_checker;
        }else{
            if(!in_array($id_checker, $id_checker_selected)){
                $id_checker_selected[] = $id_checker;
            }
        }
        $this->session->set_userdata('id_checker_selected', $id_checker_selected);

        send_json($this->session->userdata('id_checker_selected'));
    }

    public function remove_session_rekap_po_logistik(){
        $id_checker_selected = $this->session->userdata('id_checker_selected');
        $id_checker = $this->input->post('id_checker');
        for ($i=0; $i < count($id_checker_selected); $i++) { 
            if($id_checker == $id_checker_selected[$i]){
                unset($id_checker_selected[$i]);
                $id_checker_selected = array_values($id_checker_selected);
            }
        }
        $this->session->set_userdata('id_checker_selected', $id_checker_selected);
        
        send_json($this->session->userdata('id_checker_selected'));
    }

    public function reset_session(){
        $this->session->unset_userdata('id_checker_selected');

        send_json($this->session->userdata('id_checker_selected'));
    }
}
