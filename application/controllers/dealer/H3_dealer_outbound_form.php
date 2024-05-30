<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_dealer_outbound_form extends Honda_Controller
{
    protected $folder = "dealer";
    protected $page   = "h3_dealer_outbound_form";
    protected $title  = "Manage Stock Out";

    public function __construct()
    {
        parent::__construct();
        //---- cek session -------//
        $name = $this->session->userdata('nama');
        if ($name=="") {
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
        }

        //===== Load Database =====
        $this->load->database();
        $this->load->helper('url');
        //===== Load Model =====
        $this->load->model('m_admin');
        $this->load->library('form_validation');
        $this->load->model('h3_dealer_outbound_form_model', 'outbound_form');
        $this->load->model('h3_dealer_outbound_form_parts_model', 'outbound_form_parts');
        $this->load->model('H3_dealer_gudang_h23_model', 'gudang');
        $this->load->model('H3_dealer_lokasi_rak_bin_model', 'rak');
        $this->load->model('Ms_part_model', 'part');
    }
    
    public function index()
    {
        $data['set']	= "index";
        $data['outbound_form'] = $this->outbound_form->all();
        $this->template($data);
    }

    public function add()
    {
        $data['kode_md'] = 'E22';
        $data['mode']    = 'insert';
        $data['set']     = "form";

        $this->template($data);
    }

    public function save()
    {
        $this->db->trans_start();

        $this->outbound_form->insert($this->input->post(['tipe', 'id_warehouse_asal', 'id_warehouse_tujuan']));
        $id_outbound_form = $this->db->insert_id();
        $parts = $this->groupArray($this->input->post(['id_part', 'kuantitas', 'id_rak']), [
            'id_outbound_form' => $id_outbound_form
        ]);
        $this->outbound_form_parts->insert_batch($parts);

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_userdata('pesan', 'Data berhasil diperbarui.');
            $this->session->set_userdata('tipe', 'info');
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?k=$id_outbound_form'>";
        } else {
            $this->session->set_userdata('pesan', 'Data not found !');
            $this->session->set_userdata('tipe', 'danger');
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";
        }
    }

    public function detail()
    {
        $data['mode']  = 'detail';
        $data['set']   = "form";

        $outbound_form = $this->outbound_form->find($this->input->get('k'), 'id_outbound_form');
        if (is_object($outbound_form)) {
            $data['outbound_form'] = $outbound_form;
            $parts = $this->outbound_form_parts->get([
                'id_outbound_form' => $outbound_form->id_outbound_form
            ]);
            $finalParts = [];
            foreach ($parts as $each) {
                $subArr = (array) $each;
                $part = (array) $this->part->find($each->id_part, 'id_part');
                $subArr['selectedRak'] = $this->rak->find($each->id_rak, 'id_rak');
                $finalParts[] = array_merge($subArr, $part);
            }
            $data['parts'] = $finalParts;
            $this->template($data);
        } else {
            $_SESSION['pesan'] 	= "Data not found !";
            $_SESSION['tipe'] 	= "danger";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";
        }
    }

    public function edit()
    {
        $data['set']	= "form";
        $data['mode']  = 'edit';

        $outbound_form = $this->outbound_form->find($this->input->get('k'), 'id_outbound_form');
        if (is_object($outbound_form)) {
            $data['outbound_form'] = $outbound_form;
            $parts = $this->outbound_form_parts->get([
                'id_outbound_form' => $outbound_form->id_outbound_form
            ]);
            $finalParts = [];
            foreach ($parts as $each) {
                $subArr = (array) $each;
                $part = (array) $this->part->find($each->id_part, 'id_part');
                $subArr['selectedRak'] = $this->rak->find($each->id_rak, 'id_rak');
                $finalParts[] = array_merge($subArr, $part);
            }
            $data['parts'] = $finalParts;
            $this->template($data);
        } else {
            $_SESSION['pesan'] 	= "Data not found !";
            $_SESSION['tipe'] 	= "danger";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";
        }
    }

    public function update()
    {
        $this->db->trans_start();
        $this->outbound_form->update(
            $this->input->post(['tipe', 'id_warehouse_asal', 'id_warehouse_tujuan']), 
            $this->input->post(['id_outbound_form']));
        $parts = $this->groupArray($this->input->post(['id_part', 'kuantitas', 'id_rak']), $this->input->post(['id_outbound_form']));
        $this->outbound_form_parts->update_batch($parts, $this->input->post(['id_outbound_form']));

        $this->db->trans_complete();
        
        if ($this->db->trans_status()) {
            $_SESSION['pesan'] 	= "Data berhasil diperbarui.";
            $_SESSION['tipe'] 	= "info";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?k={$this->input->post('id_outbound_form')}'>";
        } else {
            $_SESSION['pesan'] 	= "Data not found !";
            $_SESSION['tipe'] 	= "danger";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";
        }
    }
}
