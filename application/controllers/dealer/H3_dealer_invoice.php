<?php
defined('BASEPATH') or exit('No direct script access allowed');

class h3_dealer_invoice extends Honda_Controller
{
    public $folder = "dealer";
    public $page   = "h3_dealer_invoice";
    public $title  = "Invoice";

    public function __construct()
    {
        parent::__construct();
        //---- cek session -------//
        if ($this->session->userdata('nama') == '') {
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
        }

        //===== Load Database =====
        $this->load->database();
        $this->load->helper('url');
        //===== Load Model =====
        $this->load->model('m_admin');
        $this->load->library('form_validation');
        $this->load->model('customer_model', 'customer');
        $this->load->model('h3_dealer_invoice_model', 'invoice');
        $this->load->model('h3_dealer_invoice_parts_model', 'invoice_parts');
        // $this->load->model('dealer_model', 'dealer');
    }
    
    public function index()
    {
        $data['set']	= "index";
        $data['invoice'] = $this->invoice->get([
            'id_dealer' => $this->m_admin->cari_dealer(),
        ]);
        $this->template($data);
    }

    public function add()
    {
        $data['mode']    = 'insert';
        $data['set']     = "form";

        $this->template($data);
    }

    public function save()
    {
        $invoiceData = $this->input->post(['ref']);
        $invoiceData = array_merge($invoiceData, [
            'id_invoice' => $this->invoice->generateID(),
            'id_dealer' => $this->m_admin->cari_dealer(),
            'tipe_ref' => 'booking',
            'created_by' => $this->session->userdata('id_user')
        ]);

        $invoicePartsData = $this->groupArray($this->input->post([
            'id_part', 'kuantitas', 'harga_saat_dibeli'
        ]), [
            'id_invoice' => $invoiceData['id_invoice']
        ]);


        $this->db->trans_start();
        $this->invoice->insert($invoiceData);
        $this->invoice_parts->insert_batch($invoicePartsData);
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $_SESSION['pesan'] 	= "Data berhasil diperbarui.";
            $_SESSION['tipe'] 	= "info";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?k={$invoiceData['id_invoice']}'>";
        } else {
            $_SESSION['pesan'] 	= "Data not found !";
            $_SESSION['tipe'] 	= "danger";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";
        }
    }

    public function detail()
    {
        $data['mode']  = 'detail';
        $data['set']   = "form";

        $invoice = $this->invoice->find($this->input->get('k'), 'id_invoice');
        if (is_object($invoice)) {
            $data['invoice'] = $invoice;
            $data['parts'] = $this->db->select('tip.*, mp.nama_part')
                    ->from('tr_h3_dealer_invoice_parts as tip')
                    ->join('ms_part as mp', 'tip.id_part=mp.id_part')
                    ->where('tip.id_invoice', $invoice->id_invoice)
                    ->get()->result();
            $this->template($data);
        } else {
            $_SESSION['pesan'] 	= "Data not found !";
            $_SESSION['tipe'] 	= "danger";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/sa_form'>";
        }
    }

    public function edit()
    {
        $data['set']	= "form";
        $data['mode']  = 'edit';
        $request_document = $this->request_document->find($this->input->get('k'), 'id_booking');
        $data['request_document'] = $request_document;
        $data['customer'] = $this->customer->find($request_document->id_customer, 'id_customer');
        $data['parts'] = $this->request_document_parts->get([
            'id_booking' => $request_document->id_booking
        ]);

        $this->template($data);
    }

    public function update()
    {
        $id_booking = $this->input->post('id_booking');

        $requestDocumentData = $this->input->post(['id_customer']);

        $requestDocumentPartsData = $this->groupArray($this->input->post([
            'id_part', 'kuantitas', 'harga_saat_dibeli'
        ]), $this->input->post(['id_booking']));

        $this->db->trans_start();
        $this->request_document->update($requestDocumentData, $this->input->post(['id_booking']));
        $this->request_document_parts->update_batch($requestDocumentPartsData, $this->input->post(['id_booking']));
        $this->db->trans_complete();
        
        if ($this->db->trans_status()) {
            $_SESSION['pesan'] 	= "Data berhasil diperbarui.";
            $_SESSION['tipe'] 	= "info";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?k={$this->input->post('id_booking')}'>";
        } else {
            $_SESSION['pesan'] 	= "Data not found !";
            $_SESSION['tipe'] 	= "danger";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";
        }
    }

    public function delete()
    {
        $delete = $this->gudang_h23->delete($this->input->get('k'));
        if ($delete) {
            $_SESSION['pesan'] 	= "Data berhasil dihapus.";
            $_SESSION['tipe'] 	= "info";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";
        } else {
            $_SESSION['pesan'] 	= "Data not found !";
            $_SESSION['tipe'] 	= "danger";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";
        }
    }
    
    protected function template($data)
    {
        $name = $this->session->userdata('nama');
        $data['isi']    = $this->page;
        $data['title']	= $this->title;

        if ($name=="") {
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
        } else {
            $this->load->view('template/header', $data);
            $this->load->view('template/aside');
            $this->load->view($this->folder."/".$this->page);
            $this->load->view('template/footer');
        }
    }

    public function getRequestDocumentParts(){
        send_json($this->request_document_parts->get([
            'id_booking' => $this->input->get('id_booking')
        ]));
    }
}
