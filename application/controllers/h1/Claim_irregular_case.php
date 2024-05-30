<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Claim_irregular_case extends CI_Controller
{
    var $tables =   "tr_do_dealer";

    var $folder =   "h1";

    var $page   =   "claim_irregular_case";

    var $pk     =   "no_do";

    var $title  =   "Claim Irregular Case";


    public function __construct()
    {

        parent::__construct();

        //===== Load Database =====

        $this->load->database();

        $this->load->helper('url');

        //===== Load Model =====

        $this->load->model('m_admin');
        $this->load->model('M_claim_program_ahm', 'cma');
        $this->load->model('M_claim_program_ireg', 'cmc');


        //===== Load Library =====

        $this->load->library('upload');



        //---- cek session -------//    

        $name = $this->session->userdata('nama');

        $auth = $this->m_admin->user_auth($this->page, "select");

        $sess = $this->m_admin->sess_auth();

        if ($name == "" or $auth == 'false' or $sess == 'false') {

            echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
        }
    }
    protected function template($data)
    {

        $name = $this->session->userdata('nama');

        if ($name == "") {

            echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
        } else {

            $data['id_menu'] = $this->m_admin->getMenu($this->page);

            $data['group']  = $this->session->userdata("group");

            $this->load->view('template/header', $data);

            $this->load->view('template/aside');

            $this->load->view($this->folder . "/" . $this->page);

            $this->load->view('template/footer');
        }
    }

    public function index()
    {
        $data['isi']    = $this->page;
        $data['title']  = $this->title;
        $data['set']    = "view";
        $data['query'] = $this->cmc->get_group_memo();


        $this->template($data);
    }

    public function addmemo()
    {
        $claim = $this->input->post('claim');
        $reason = $this->input->post('reasonmemo');
        $memo2 = $this->input->post('memo2');

        $jmldata = count($claim);
        //var_dump($jmldata . $centang . $reason . $memo2);
        //$query = $this->cmc->get_update_memo($claim, $jmldata, $reason, $memo2);
        for ($i = 0; $i < $jmldata; $i++) {
            $this->db->where('id_claim_dealer', $claim[$i]);
            $this->db->set('alasan', $reason);
            $this->db->set('memo', $memo2);
            $this->db->update('tr_claim_sales_program_detail');
            redirect(base_url('h1/claim_irregular_case'));
        }
    }
}
