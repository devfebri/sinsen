<?php

defined('BASEPATH') or exit('No direct script access allowed');


class h3_dealer_sales_order_return extends Honda_Controller
{
    public $folder = "dealer";
    public $page   = "h3_dealer_sales_order_return";
    public $title  = "Sales Order Return";

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
        $this->load->model('h3_dealer_sales_order_model', 'sales_order');
        $this->load->model('h3_dealer_sales_order_return_model', 'sales_order_return');
        $this->load->model('h3_dealer_sales_order_return_parts_model', 'sales_order_return_parts');
        $this->load->model('h3_dealer_sales_order_parts_model', 'sales_order_parts');
        // $this->load->model('dealer_model', 'dealer');
    }

    public function index()
    {
        $data['set']	= "index";
        $data['sales_order_return'] = $this->sales_order_return->get([
            'id_dealer' => $this->m_admin->cari_dealer()
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
        $salesOrderReturnData = $this->input->post(['nomor_so']);
        $salesOrderReturnData = array_merge($salesOrderReturnData, [
            'id_sales_order_return' => $this->sales_order_return->generateID(),
            'created_by' => $this->session->userdata('id_user'),
            'id_dealer' => $this->m_admin->cari_dealer()
        ]);

        $salesOrderReturnPartsData = $this->groupArray($this->input->post([
            'id_part', 'kuantitas', 'kuantitas_return'
        ]), [
            'id_sales_order_return' => $salesOrderReturnData['id_sales_order_return']
        ]);

        $this->db->trans_start();
        $this->sales_order_return->insert($salesOrderReturnData);
        $this->sales_order_return_parts->insert_batch($salesOrderReturnPartsData);
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $_SESSION['pesan'] 	= "Data berhasil diperbarui.";
            $_SESSION['tipe'] 	= "info";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?k={$salesOrderReturnData['id_sales_order_return']}'>";
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

        $sales_order_return = $this->sales_order_return->find($this->input->get('k'), 'id_sales_order_return');
        if (is_object($sales_order_return)) {
            $data['sales_order_return'] = $sales_order_return;
            $data['sales_order'] = $this->sales_order->find($sales_order_return->nomor_so, 'nomor_so');
            $data['parts'] = $this->db->select('sop.*, mp.nama_part')
            ->from('tr_h3_dealer_sales_order_return_parts as sop')
            ->join('ms_part as mp', 'sop.id_part=mp.id_part')
            ->where('sop.id_sales_order_return', $sales_order_return->id_sales_order_return)
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

    public function ambil_sales_order_data(){
        $query = $this->db->select('sop.id_part, mp.nama_part, sop.kuantitas')
            ->from('tr_h3_dealer_sales_order_parts as sop')
            ->join('ms_part as mp', 'sop.id_part = mp.id_part')
            ->where('sop.nomor_so', $this->input->get('nomor_so'))
            ->get()->result();

        send_json($query);
    }
}
