<?php

defined('BASEPATH') or exit('No direct script access allowed');

class h3_dealer_promo extends Honda_Controller
{
    public $folder = "dealer";
    public $page   = "h3_dealer_promo";
    public $title  = "Promo";

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
        $this->load->library('form_validation');
        $this->load->model('m_admin');
        $this->load->model('promo_model', 'promo');
        $this->load->model('promo_items_model', 'promo_items');
        $this->load->model('promo_hadiah_model', 'promo_hadiah');
    }

    public function index()
    {
        $data['set']	= "index";
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
        $this->db->trans_start();
        $master = $this->input->post([
            'nama', 'mekanisme_promo',
            'start_date', 'end_date', 'minimal_pembelian',
            'tipe_promo','hadiah_per_item',
            'promo_untuk_kelompok_part', 'tipe_diskon_master',
            'diskon_value_master'
        ]);

        $master = array_merge([
            'id_promo' => $this->promo->generateID()
        ], $master);
        $this->promo->insert($master);

        if(count($this->input->post('gifts')) > 0){
            $master_gifts = $this->getOnly(true, $this->input->post('gifts'), [
                'id_promo' => $master['id_promo']
            ]);
            $this->promo_hadiah->insert_batch($master_gifts);
        }

        foreach ($this->input->post('parts_promo') as $each) {
            $items_gifts = isset($each['gifts']) ? $each['gifts'] : [];
            unset($each['gifts']);
            $each['id_promo'] = $master['id_promo'];
            $this->promo_items->insert($each);
            $id_items = $this->db->insert_id();

            if(count($items_gifts) > 0){
                foreach ($items_gifts as $items_gift) {
                    $items_gift['id_promo'] = $master['id_promo'];
                    $items_gift['id_items'] = $id_items;
                    $this->promo_hadiah->insert($items_gift);
                }
            }
        }

        $this->db->trans_complete();

        if($this->db->trans_status()){
            $result = $this->promo->find($master['id_promo'], 'id_promo');
            send_json($result);
        }else{
            $this->output->set_status_header(500);
        }
    }

    public function detail()
    {
        $data['mode']  = 'detail';
        $data['set']   = "form";

        $data['master'] = $this->db
        ->from('ms_h3_promo_dealer as pd')
        ->limit(1)
        ->where('pd.id_promo', $this->input->get('id_promo'))
        ->get()->row_array();

        $gifts = $this->db
        ->from('ms_h3_promo_dealer_hadiah as pdh')
        ->where('pdh.id_promo', $this->input->get('id_promo'))
        ->where('pdh.id_items', null)
        ->get()->result_array();

        $data['master']['gifts'] = count($gifts) > 0 ? $gifts : [];

        $items = $this->db
        ->select('pdi.*')
        ->select('p.nama_part')
        ->from('ms_h3_promo_dealer_items as pdi')
        ->join('ms_part as p', 'p.id_part = pdi.id_part', 'left')
        ->where('pdi.id_promo', $this->input->get('id_promo'))
        ->get()->result_array();

        foreach ($items as $item) {
            $gifts = $this->db
            ->from('ms_h3_promo_dealer_hadiah as pdh')
            ->where('pdh.id_promo', $this->input->get('id_promo'))
            ->where('pdh.id_items', $item['id'])
            ->get()->result_array();
            $item['gifts'] = count($gifts) > 0 ? $gifts : [];

            $data['parts_promo'][] = $item;
        }

        $this->template($data);
    }

    public function edit()
    {
        $data['set']	= "form";
        $data['mode']  = 'edit';
        $data['master'] = $this->db
        ->from('ms_h3_promo_dealer as pd')
        ->limit(1)
        ->where('pd.id_promo', $this->input->get('id_promo'))
        ->get()->row_array();

        $gifts = $this->db
        ->from('ms_h3_promo_dealer_hadiah as pdh')
        ->where('pdh.id_promo', $this->input->get('id_promo'))
        ->where('pdh.id_items', null)
        ->get()->result_array();

        $data['master']['gifts'] = count($gifts) > 0 ? $gifts : [];

        $items = $this->db
        ->select('pdi.*')
        ->select('p.nama_part')
        ->from('ms_h3_promo_dealer_items as pdi')
        ->join('ms_part as p', 'p.id_part = pdi.id_part', 'left')
        ->where('pdi.id_promo', $this->input->get('id_promo'))
        ->get()->result_array();

        foreach ($items as $item) {
            $gifts = $this->db
            ->from('ms_h3_promo_dealer_hadiah as pdh')
            ->where('pdh.id_promo', $this->input->get('id_promo'))
            ->where('pdh.id_items', $item['id'])
            ->get()->result_array();
            $item['gifts'] = count($gifts) > 0 ? $gifts : [];

            $data['parts_promo'][] = $item;
        }

        $this->template($data);
    }

    public function update()
    {
        $this->db->trans_start();
        $master = $this->input->post([
            'nama', 'mekanisme_promo',
            'start_date', 'end_date', 'minimal_pembelian',
            'tipe_promo', 'hadiah_per_item',
            'promo_untuk_kelompok_part', 'tipe_diskon_master',
            'diskon_value_master'
        ]);

        $this->promo->update($master, $this->input->post(['id_promo']));

        if(count($this->input->post('gifts')) > 0){
            $master_gifts = $this->getOnly(true, $this->input->post('gifts'), $this->input->post(['id_promo']));
            $this->promo_hadiah->update_batch($master_gifts, $this->input->post(['id_promo']));
        }

        $this->promo_items->delete($this->input->post('id_promo'), 'id_promo');
        foreach ($this->input->post('parts_promo') as $each) {
            $items_gifts = isset($each['gifts']) ? $each['gifts'] : [];
            unset($each['gifts']);
            $each['id_promo'] = $this->input->post('id_promo');
            $this->promo_items->insert($each);
            $id_items = $this->db->insert_id();

            if(count($items_gifts) > 0){
                foreach ($items_gifts as $items_gift) {
                    $items_gift['id_promo'] = $this->input->post('id_promo');
                    $items_gift['id_items'] = $id_items;
                    unset($items_gift['id']);
                    $this->promo_hadiah->insert($items_gift);
                }
            }
        }

        $this->db->trans_complete();

        if($this->db->trans_status()){
            $result = $this->promo->get($this->input->post(['id_promo']), true);
            send_json($result);
        }else{
            $this->output->set_status_header(500);
        }
    }

    public function delete()
    {
        $delete = $this->stock->delete($this->input->get('k'));
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
}
