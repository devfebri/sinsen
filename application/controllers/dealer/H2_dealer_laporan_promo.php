<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_dealer_laporan_promo extends CI_Controller
{

  var $folder =   "dealer/laporan";
  var $page    =    "h2_dealer_laporan_promo";
  var $title  =   "Laporan Promo Servis dan Part";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('H2_dealer_laporan_promo_model','promo_model');
    //===== Load Library =====		

    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    $auth = $this->m_admin->user_auth($this->page, "select");
    $sess = $this->m_admin->sess_auth();
    if ($name == "" or $auth == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
    } elseif ($sess == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
    }
    ini_set('display_errors', 0);
  }
  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $data['id_menu'] = $this->m_admin->getMenu($this->page);
      $data['group']   = $this->session->userdata("group");
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
      $data['id_dealer'] = $id_dealer = $this->m_admin->cari_dealer();
      $data['promo_servis'] = $promo_servis = $this->promo_model->program_promo_servis($id_dealer);
      $data['promo_part'] = $promo_part = $this->promo_model->program_promo_part($id_dealer);
      $this->template($data);
  }

  public function downloadReport(){
    $data['id_dealer'] = $id_dealer = $this->m_admin->cari_dealer();
    $data['nama_dealer'] = $this->db->query("SELECT nama_dealer from ms_dealer where id_dealer = $id_dealer");
    $data['start_date']= $start_date= $this->input->post('tgl1');
	  $data['end_date']  = $end_date	= $this->input->post('tgl2');
	  $data['type_jasa']      = $type_jasa	    = $this->input->post('type_jasa');
	  $data['type_part']      = $type_part	    = $this->input->post('type_part');
	  $data['promoOption'] = $promoOption	    = $this->input->post('promoOption');

   
    if($_POST['process']=='excel'){
      if($promoOption == 'promo_servis'){
        $data['query_promo_servis'] = $query_promo_servis = $this->promo_model->promo_servis($id_dealer,$start_date,$end_date, $type_jasa);
        $this->load->view("dealer/laporan/temp_h2_dealer_laporan_promo_servis",$data);
      }elseif($promoOption == 'promo_part' && $type_part != 'PRM/01/24/00004'){
        $data['query_promo_part'] = $query_promo_part = $this->promo_model->promo_part($id_dealer,$start_date,$end_date, $type_part);
        $this->load->view("dealer/laporan/temp_h2_dealer_laporan_promo_part",$data);
      }elseif($promoOption == 'promo_part' && $type_part == 'PRM/01/24/00004'){
        $data['query_promo_goliath'] = $query_promo_goliath = $this->promo_model->promo_part_goliath($id_dealer,$start_date,$end_date, $type_part);
        
			  $data_dealer_pembelian = array();
        foreach($data['query_promo_goliath']->result() as $row){
          $dealer_pembelian = $this->db->query("SELECT so.id_dealer, md.nama_dealer
              from tr_sales_order so 
              join ms_dealer md on md.id_dealer=so.id_dealer 
              where so.no_mesin='$row->no_mesin'
              ")->row();
					if($dealer_pembelian->id_dealer == '' or $dealer_pembelian->id_dealer == NULL){
						$dealer_pembelian = $this->db->query("SELECT so.id_dealer, d.nama_dealer 
              FROM tr_sales_order_gc_nosin sog
              JOIN tr_sales_order_gc so ON sog.id_sales_order_gc=so.id_sales_order_gc
              JOIN tr_spk_gc spk ON spk.no_spk_gc=so.no_spk_gc
              join ms_dealer d on d.id_dealer=so.id_dealer
              where sog.no_mesin='$row->no_mesin'
						")->row();
					}
					$data_dealer_pembelian[] = $dealer_pembelian;
        }
        $data['data_dealer_pembelian'] = $data_dealer_pembelian;
        $this->load->view("dealer/laporan/temp_h2_dealer_laporan_promo_part_goliath",$data);
      }
    }

  }
}
