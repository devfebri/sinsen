<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH."/third_party/PHPExcel/PHPExcel.php";

class H2_dealer_upload_follow_up extends CI_Controller
{

  var $folder =   "dealer";
  var $page   =   "h2_dealer_upload_follow_up";
  var $title  =   "Upload Data Follow Up (EXCEL)";


  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('H2_dealer_customer_list_model');
    //===== Load Library =====		
    // $this->load->library('PHPExcel');
    $this->load->library('upload');
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
      $this->template($data);
  }

  public function downloadReport(){
    $data['id_dealer'] = $id_dealer = $this->m_admin->cari_dealer();
    $data['start_date']= $start_date= $this->input->post('tgl1');
	  $data['end_date']  = $end_date	= $this->input->post('tgl2');
    // $data['report']= $report = $this->H2_dealer_customer_list_model->reportFollowUp($id_dealer, $start_date,$end_date);

    if($_POST['process']=='excel'){
        // $this->load->view("dealer/laporan/temp_h2_dealer_reporting_follow_up",$data);
    }
  }

  public function import_excel(){
    $dname = explode(".", $_FILES['fileExcel']['name']);
    $ext = end($dname); 
    if(isset($_FILES["fileExcel"]["name"])&&($ext=='xlsx' ||$ext=='xls')){
      $path = $_FILES["fileExcel"]["tmp_name"];
      $object = PHPExcel_IOFactory::load($path);
      foreach($object->getWorksheetIterator() as $worksheet){
        $highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
        for($row=2; $row<=$highestRow; $row++)
        {
          $tgl_fol_up = $worksheet->getCellByColumnAndRow(1,$row)->getValue();
          $tgl_fol_up = \PHPExcel_Style_NumberFormat::toFormattedString($tgl_fol_up, 'YYYY-MM-DD');
          $id_follow_up = $worksheet->getCellByColumnAndRow(0,$row)->getValue();
          $hasil_fol_up = $worksheet->getCellByColumnAndRow(2,$row)->getValue();
          // $id_follow_up = $this->get_id_folup(); 
          $temp_data=array(
            // 'id_follow_up' => $id_follow_up,
            'tgl_fol_up' => $tgl_fol_up,
            'hasil_fol_up' => $hasil_fol_up
          );
          $this->db->where('id_follow_up', $id_follow_up);
          $update = $this->db->update('tr_h2_fol_up_detail',$temp_data);
        }
        if($update){
          $_SESSION['pesan'] 	= "Data Berhasil di Import ke Database";
          $_SESSION['tipe'] 	= "success";
          redirect($_SERVER['HTTP_REFERER']);
        }else{
          $_SESSION['pesan'] 	= "Terjadi Kesalahan";
          $_SESSION['tipe'] 	= "danger";
          redirect($_SERVER['HTTP_REFERER']);
        }
      }
      // if($update)
      // {
      //   // $this->session->set_flashdata('status', '<span class="glyphicon glyphicon-ok"></span> Data Berhasil di Import ke Database');
      //   $_SESSION['pesan'] 	= "Data Berhasil di Import ke Database";
      //   $_SESSION['tipe'] 	= "success";
			// 	redirect($_SERVER['HTTP_REFERER']);
      // }else{
      //   $_SESSION['pesan'] 	= "Terjadi Kesalahan";
      //   $_SESSION['tipe'] 	= "danger";
      //   redirect($_SERVER['HTTP_REFERER']);
      // }
    }else{
      // echo "Tidak Ada File yang masuk";
      $_SESSION['pesan'] 	= "Tidak Ada File Yang Masuk/Format File Tidak Sesuai";
      $_SESSION['tipe'] 	= "danger";
      redirect($_SERVER['HTTP_REFERER']);
    }
  }
}
