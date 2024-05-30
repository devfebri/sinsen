<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_dealer_manage_stock_out extends Honda_Controller
{
    protected $folder = "dealer";
    protected $page   = "h3_dealer_manage_stock_out";
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
        $this->load->model('h3_dealer_manage_stock_out_model', 'manage_stock_out');
        $this->load->model('h3_dealer_manage_stock_out_parts_model', 'manage_stock_out_parts');
        $this->load->model('H3_dealer_gudang_h23_model', 'gudang');
        $this->load->model('H3_dealer_lokasi_rak_bin_model', 'rak');
        $this->load->model('h3_dealer_stock_model', 'stock');
        $this->load->model('Ms_part_model', 'part');
        $this->load->model('satuan_model', 'satuan');
        $this->load->model('dealer_model', 'dealer');
    }

    public function index()
    {
        $data['set']	= "index";
        $this->template($data);
    }

    public function add()
    {
        $data['kode_md'] = 'E22';
        $data['mode']    = 'insert';
        $data['set']     = "form";
        $data['satuan'] = $this->satuan->all();

        $this->template($data);
    }

    public function save()
    {
        $this->db->trans_start();
        $data = [
            'id_manage_stock_out' => $this->manage_stock_out->generateIdManageStockOut(),
            'id_dealer' => $this->m_admin->cari_dealer(),
            'created_by' => $this->session->userdata('id_user'),
        ];

        $parts = $this->getOnly(true, $this->input->post('parts'), [
            'id_manage_stock_out' => $data['id_manage_stock_out']
        ]);
            
        $this->manage_stock_out->insert($data);
        $this->manage_stock_out_parts->insert_batch($parts);

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_userdata('pesan', 'Data berhasil diperbarui.');
            $this->session->set_userdata('tipe', 'info');
            
            $result = $this->manage_stock_out->find($data['id_manage_stock_out'], 'id_manage_stock_out');
            send_json($result);
        } else {
            $this->session->set_userdata('pesan', 'Data not found!');
            $this->session->set_userdata('tipe', 'danger');
            
            $this->output->set_status_header(500);
        }
    }

    public function detail()
    {
        $data['mode']  = 'detail';
        $data['set']   = "form";
        $manage_stock_out = $this->manage_stock_out->find($this->input->get('id_manage_stock_out'), 'id_manage_stock_out');
        if (is_object($manage_stock_out)) {
            $data['manage_stock_out'] = $manage_stock_out;
            $data['parts'] = $this->db
            ->select('sp.*')
            ->select('p.nama_part')
            ->select('s.satuan')
            ->from('tr_h3_dealer_manage_stock_out_parts sp')
            ->join('ms_part as p', 'p.id_part = sp.id_part')
            ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
            ->where('sp.id_manage_stock_out', $manage_stock_out->id_manage_stock_out)
            ->get()->result();

            $this->template($data);
        } else {
            $_SESSION['pesan'] 	= "Data not found !";
            $_SESSION['tipe'] 	= "danger";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";
        }
    }

    public function edit()
    {
        $data['mode']  = 'edit';
        $data['set']   = "form";
        $manage_stock_out = $this->manage_stock_out->find($this->input->get('id_manage_stock_out'), 'id_manage_stock_out');
        if (is_object($manage_stock_out)) {
            $data['manage_stock_out'] = $manage_stock_out;
            $data['parts'] = $this->db
            ->select('sp.*')
            ->select('p.nama_part')
            ->select('s.satuan')
            ->from('tr_h3_dealer_manage_stock_out_parts sp')
            ->join('ms_part as p', 'p.id_part = sp.id_part')
            ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
            ->where('sp.id_manage_stock_out', $manage_stock_out->id_manage_stock_out)
            ->get()->result();

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

        $parts = $this->getOnly(true, $this->input->post('parts'), $this->input->post(['id_manage_stock_out']));
            
        $this->manage_stock_out_parts->update_batch($parts, $this->input->post(['id_manage_stock_out']));

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_userdata('pesan', 'Data berhasil diperbarui.');
            $this->session->set_userdata('tipe', 'info');
            
            $result = $this->manage_stock_out->get($this->input->post(['id_manage_stock_out']), true);
            send_json($result);
        } else {
            $this->session->set_userdata('pesan', 'Data not found!');
            $this->session->set_userdata('tipe', 'danger');
            
            $this->output->set_status_header(500);
        }
    }

    public function reject(){
        $this->db->trans_start();
        $this->manage_stock_out->update(array_merge([
            'status' => 'Rejected',
        ], $this->input->post(['alasan_reject'])), $this->input->post(['id_manage_stock_out']));
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_userdata('pesan', 'Data berhasil diperbarui.');
            $this->session->set_userdata('tipe', 'info');
            
            $result = $this->manage_stock_out->get($this->input->post(['id_manage_stock_out']), true);
            send_json($result);
        } else {
            $this->session->set_userdata('pesan', 'Data not found!');
            $this->session->set_userdata('tipe', 'danger');
            
            $this->output->set_status_header(500);
        }
    }

    public function cancel(){
        $this->db->trans_start();
        $this->manage_stock_out->update([
            'status' => 'Canceled',
        ], $this->input->get(['id_manage_stock_out']));
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_userdata('pesan', 'Data berhasil diperbarui.');
            $this->session->set_userdata('tipe', 'info');
            
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?id_manage_stock_out={$this->input->get('id_manage_stock_out')}'>";
        } else {
            $this->session->set_userdata('pesan', 'Data not found!');
            $this->session->set_userdata('tipe', 'danger');
            
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?id_manage_stock_out={$this->input->get('id_manage_stock_out')}'>";
        }
    }

    public function approve(){
        $this->db->trans_start();
        $this->manage_stock_out->update([
            'status' => 'Approved',
        ], $this->input->get(['id_manage_stock_out']));
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_userdata('pesan', 'Data berhasil diperbarui.');
            $this->session->set_userdata('tipe', 'info');
            
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?id_manage_stock_out={$this->input->get('id_manage_stock_out')}'>";
        } else {
            $this->session->set_userdata('pesan', 'Data not found!');
            $this->session->set_userdata('tipe', 'danger');
            
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?id_manage_stock_out={$this->input->get('id_manage_stock_out')}'>";
        }
    }

    public function close(){
        $this->db->trans_start();
        $this->manage_stock_out->update([
            'status' => 'Closed',
        ], $this->input->get(['id_manage_stock_out']));
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_userdata('pesan', 'Data berhasil diperbarui.');
            $this->session->set_userdata('tipe', 'info');
            
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?id_manage_stock_out={$this->input->get('id_manage_stock_out')}'>";
        } else {
            $this->session->set_userdata('pesan', 'Data not found!');
            $this->session->set_userdata('tipe', 'danger');
            
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?id_manage_stock_out={$this->input->get('id_manage_stock_out')}'>";
        }
    }

    public function cetak(){

        $data = [];

        $data['manage_stock_out'] = $this->manage_stock_out->find($this->input->get('id_manage_stock_out'), 'id_manage_stock_out');

        $data['manage_stock_out_parts'] = $this->manage_stock_out_parts->get($this->input->get(['id_manage_stock_out']));

        $data['dealer'] = $this->dealer->getCurrentUserDealer();

        // $this->load->library('mpdf_l');

        require_once APPPATH .'third_party/mpdf/mpdf.php';

        // Require composer autoload

        $mpdf = new Mpdf();

        // Write some HTML code:

        $html = $this->load->view('dealer/h3_cetak_manage_stock_out', $data, true);

        $mpdf->WriteHTML($html);



        $date = date('d_m_Y', time());

        $filename = $date . "_{$data['manage_stock_out']->id_manage_stock_out}";

        // Output a PDF file directly to the browser

        $mpdf->Output($filename, "I");

    }

}
