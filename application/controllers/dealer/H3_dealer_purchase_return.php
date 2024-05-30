<?php
defined('BASEPATH') or exit('No direct script access allowed');

class h3_dealer_purchase_return extends Honda_Controller
{
    public $folder = "dealer";
    public $page   = "h3_dealer_purchase_return";
    public $title  = "Purchase Order Return";

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
        $this->load->model('h3_dealer_purchase_return_model', 'purchase_return');
        $this->load->model('h3_dealer_purchase_return_parts_model', 'purchase_return_parts');
        $this->load->model('h3_dealer_purchase_order_model', 'purchase');
        $this->load->model('h3_dealer_shipping_list_model', 'shipping_list');
        $this->load->model('h3_dealer_shipping_list_parts_model', 'shipping_list_parts');
    }
    
    public function index()
    {
        $data['set']	= "index";
        $data['purchase_return'] = $this->purchase_return->get([
            'id_dealer' => $this->m_admin->cari_dealer()
        ]);
        $this->template($data);
    }

    public function detail()
    {
        $data['mode']  = 'detail';
        $data['set']   = "form";


        $purchase_return = $this->db
        ->select('pr.*, sl.id_ref')
        ->from('tr_h3_dealer_purchase_return as pr')
        ->join('tr_h3_dealer_shipping_list as sl', 'sl.id_ref = pr.id_purchase_return', 'left')
        ->where('pr.id_purchase_return', $this->input->get('k'))
        ->get()->row();
        if (is_object($purchase_return)) {
            $data['purchase_return'] = $purchase_return;
            $data['parts'] = $this->db->select('sop.*, mp.nama_part')
                    ->from('tr_h3_dealer_purchase_return_parts as sop')
                    ->join('ms_part as mp', 'sop.id_part=mp.id_part')
                    ->where('sop.id_purchase_return', $purchase_return->id_purchase_return)
                    ->get()->result();
            $this->template($data);
        } else {
            $_SESSION['pesan'] 	= "Data not found !";
            $_SESSION['tipe'] 	= "danger";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/sa_form'>";
        }
    }

    public function submitShippingList(){
        // DONE: Buat shipping list dari purchase return.
        $purchase_return = $this->purchase_return->find($this->input->get('k'), 'id_purchase_return');
        $purchase_return_parts = $this->purchase_return_parts->get([
            'id_purchase_return' => $this->input->get('k')
        ]);
        

        $shippingListCreated = $this->createShippingListFromPurchaseReturn($purchase_return, $purchase_return_parts);

        if ($shippingListCreated) {
            $_SESSION['pesan'] 	= "Data berhasil diperbarui.";
            $_SESSION['tipe'] 	= "info";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?k={$this->input->get('k')}'>";
        } else {
            $_SESSION['pesan'] 	= "Data not found !";
            $_SESSION['tipe'] 	= "danger";
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";
        }
    }

    private function createShippingListFromPurchaseReturn($purchase_return, $purchase_return_parts){
        $this->db->trans_start();
        $shippingListBelumDibuat = $this->shipping_list->find($purchase_return->id_purchase_return, 'id_ref') == null;
        if($shippingListBelumDibuat){
            $shippingListData = [
                'id_shipping_list' => $this->shipping_list->generateShippingListNumber(),
                'id_ref' => $purchase_return->id_purchase_return,
                'id_dealer' => $this->m_admin->cari_dealer(),
                'ref_type' => 'pr'
            ];
            $this->shipping_list->insert($shippingListData);

            foreach ($purchase_return_parts as $each) {
                $shippingListPart = [];
                $shippingListPart['id_shipping_list'] = $shippingListData['id_shipping_list'];
                $shippingListPart['id_part'] = $each->id_part;
                $shippingListPart['kuantitas'] = $each->kuantitas;
                $shippingListParts[] = $shippingListPart;
            }
            $this->shipping_list_parts->insert_batch($shippingListParts);
        }
        $this->db->trans_complete();

        return $this->db->trans_status();
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

    public function ambil_purchase_data(){
        $query = $this->db->select('sop.id_part, mp.nama_part, sop.kuantitas')
                        ->from('tr_h3_dealer_purchase_parts as sop')
                        ->join('ms_part as mp', 'sop.id_part = mp.id_part')
                        ->where('sop.nomor_so', $this->input->get('nomor_so'))
                        ->get()->result();

        send_json($query);
    }
}
