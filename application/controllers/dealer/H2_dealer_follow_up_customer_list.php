<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_dealer_follow_up_customer_list extends CI_Controller
{

  var $folder     = "dealer";
  var $page       = "h2_dealer_follow_up_customer_list";
  var $title      = "Follow Up Reminder";
  var $jenis_user = '';
  var $is_md      = false;

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    $this->db_crm       = $this->load->database('db_crm', true);
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('H2_dealer_customer_list_model');
    $this->load->model('H2_dealer_history_fu_datatables');
    //===== Load Library =====		
    $this->load->library('form_validation');
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

    $this->jenis_user   = $this->db->get_where('ms_user',['jenis_user'=>$_SESSION['group']])->row()->code;
		if ($this->jenis_user=='Main Dealer') {
			$this->is_md = true;
		}
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
        $data['isi']       = $this->page;
        $data['title']     = $this->title;
        $data['set']       = "view";
        $data['pekerjaan'] = $this->H2_dealer_customer_list_model->getDataPekerjaan();
        $data['listFU']    = $this->H2_dealer_customer_list_model->getDataHasilFU();
        $data['kendaraan'] = $this->H2_dealer_customer_list_model->getDataKendaraan();
        $data['dt_dealer'] = $this->H2_dealer_customer_list_model->getDataDealer();
        $data['jasaType']  = $jasaType =$this->H2_dealer_customer_list_model->getDataJasaType();   
        $data['notification'] = $this->H2_dealer_customer_list_model->getNotification();   
        $this->template($data);
  }

  function getDataFUReminder()
  {
    
		$id_dealer = $this->m_admin->cari_dealer();

    $list = $this->H2_dealer_customer_list_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $field) {
        $folup_ke =$this->db->query("SELECT SUM(CASE WHEN tgl_fol_up != '' THEN 1 ELSE 0 END) as folup_ke FROM tr_h2_fol_up_detail WHERE id_follow_up='$field->id_follow_up'")->row(); 
        $data_update = $this->db->query("SELECT max(tgl_fol_up) as tgl_fol_up ,max(tgl_next_fol_up) as tgl_next_fol_up, max(id_kategori_status_komunikasi) as id_kategori_status_komunikasi, max(id_media_kontak_fol_up) as id_media_kontak_fol_up FROM tr_h2_fol_up_detail WHERE id_follow_up='$field->id_follow_up' GROUP BY id_follow_up")->row();
 
        if($data_update->id_kategori_status_komunikasi=='1'){
          $status_komunikasi = "Unreacheable";
        }elseif($data_update->id_kategori_status_komunikasi=='2'){
          $status_komunikasi = "Failed";
        }elseif($data_update->id_kategori_status_komunikasi=='3'){
          $status_komunikasi = "Rejected";
        }elseif($data_update->id_kategori_status_komunikasi=='4'){
          $status_komunikasi = "Contacted";
        }else{
          $status_komunikasi = "-";
        }

        if($data_update->id_media_kontak_fol_up=='1'){
          $media_kontak = "Telepon";
        }elseif($data_update->id_media_kontak_fol_up=='2'){
          $media_kontak = "Telepon/WA Call";
        }elseif($data_update->id_media_kontak_fol_up=='3'){
          $media_kontak = "WA";
        }elseif($data_update->id_media_kontak_fol_up=='4'){
          $media_kontak = "SMS";
        }elseif($data_update->id_media_kontak_fol_up=='5'){
          $media_kontak = "Visit";
        }elseif($data_update->id_media_kontak_fol_up=='6'){
          $media_kontak = "Facebook";
        }elseif($data_update->id_media_kontak_fol_up=='7'){
          $media_kontak = "Instagram";
        }elseif($data_update->id_media_kontak_fol_up=='8'){
          $media_kontak = "Telegram";
        }elseif($data_update->id_media_kontak_fol_up=='9'){
          $media_kontak = "Twitter";
        }elseif($data_update->id_media_kontak_fol_up=='10'){
          $media_kontak = "Email";
        }else{
          $media_kontak = "-";
        }
        $no++;
        $row = array();
        $row[] = $no;
        $row[] = $field->id_follow_up;
        $row[] = $field->id_customer;
        $row[] = strtoupper($field->nama_customer);
        $row[] = $field->tgl_assigned;
        $row[] = $folup_ke->folup_ke;
        $row[] =  $media_kontak;
        // $row[] = $field->tgl_fol_up;
        $row[] = $data_update->tgl_fol_up;
        $row[] = $status_komunikasi;
        $row[] = $data_update->tgl_next_fol_up;
        $row[] = '<a id="'.$field->id_follow_up.'" href="dealer/h2_dealer_follow_up_customer_list/fu_customer?id_follow_up='.$field->id_follow_up.'" type="button" class="btn btn-primary btn-xs">Follow Up</a>';
 
        $data[] = $row;
    }
 
    $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->H2_dealer_customer_list_model->count_all(),
        "recordsFiltered" => $this->H2_dealer_customer_list_model->count_filtered(),
        "data" => $data,
    );
    //output dalam format JSON
     echo json_encode($output);
  }

  public function fu_customer()
  {
    $data['isi']            = $this->page;
    $data['title']          = $this->title;
    $data['set']	          = "detail";
    $data['id_dealer']      = $id_dealer = $this->m_admin->cari_dealer();
    $data['id_customer']    = $id_customer = $this->input->get('id_customer');
    $data['pic']            = $this->H2_dealer_customer_list_model->getDataKaryawan();
    $data['mediaKomunikasi']= $this->H2_dealer_customer_list_model->getMediaKomunikasi();
    $data['listFU']         = $this->H2_dealer_customer_list_model->getDataHasilFU();
    $data['getFUData']      = $this->H2_dealer_customer_list_model->getFUData();
    $data['historyFollowUp']      = $this->H2_dealer_customer_list_model->historyFollowUp();
    $data['template_pesan'] = $this->H2_dealer_customer_list_model->getDataTemplate();
    $data['template_pesan_global'] = $this->H2_dealer_customer_list_model->getDataTemplateGlobal();
    
	  $this->template($data);	
  }

  public function save_fu()
  {
    $id_follow_up = $this->input->post('id_follow_up');
    $id_customer = $this->input->post('id_customer');
    $id_karyawan_dealer = $this->input->post('id_karyawan_dealer');
    $id_media_kontak_fol_up = $this->input->post('id_media_kontak_fol_up');
    $tgl_fol_up = $this->input->post('tgl_fol_up');
    $jam_fol_up = $this->input->post('jam_fol_up');
    
    $id_kategori_status_komunikasi = $this->input->post('id_kategori_status_komunikasi');
    $tgl_next_fol_up = $this->input->post('tgl_next_fol_up');
    $keterangan = $this->input->post('keterangan');
    $is_booking = $this->input->post('is_booking');
    $tgl_booking_service = $this->input->post('tgl_booking_service');
		$id_dealer = $this->m_admin->cari_dealer();

    $this->form_validation->set_rules('id_karyawan_dealer','PIC Follow Up','required');
    $this->form_validation->set_rules('id_media_kontak_fol_up','Media Follow Up','required');
    $this->form_validation->set_rules('tgl_fol_up','Tanggal Follow Up','required');
    $this->form_validation->set_rules('id_kategori_status_komunikasi','Hasil Follow Up','required');
    $this->form_validation->set_rules('tgl_next_fol_up','Tanggal Next Follow Up','required');
    
    $FUData = $this->db->query("SELECT * FROM tr_h2_fol_up_detail where id_follow_up='$id_follow_up'")->row();

   
    if($this->form_validation->run()==true){
      if($is_booking==1){
        $saveData = array(
          'id_karyawan_dealer' => $id_karyawan_dealer,
          'id_media_kontak_fol_up' => $id_media_kontak_fol_up,
          'tgl_fol_up' => $tgl_fol_up.' '.$jam_fol_up,
          'id_kategori_status_komunikasi' => $id_kategori_status_komunikasi,
          'tgl_next_fol_up' => $tgl_next_fol_up,
          'keterangan' => $keterangan,
          'is_booking' => $is_booking,
          'tgl_booking_service' => $tgl_booking_service,
          'id_follow_up' => $id_follow_up,
          'created_at'    => date('Y-m-d H:i:s'),
          'created_by' =>$this->session->userdata('id_user'),
          'id_dealer' => $id_dealer,
          'id_customer' => $id_customer
          );  
      }else{
        $saveData = array(
          'id_karyawan_dealer' => $id_karyawan_dealer,
          'id_media_kontak_fol_up' => $id_media_kontak_fol_up,
          'tgl_fol_up' => $tgl_fol_up.' '.$jam_fol_up,
          'id_kategori_status_komunikasi' => $id_kategori_status_komunikasi,
          'tgl_next_fol_up' => $tgl_next_fol_up,
          'keterangan' => $keterangan,
          'is_booking' => $is_booking,
          'tgl_booking_service' => NULL,
          'id_follow_up' => $id_follow_up,
          'created_at'    => date('Y-m-d H:i:s'),
          'created_by' =>$this->session->userdata('id_user'),
          'id_dealer' => $id_dealer,
          'id_customer' => $id_customer
          );  
      }

      if(is_null($FUData->tgl_fol_up)&&is_null($FUData->id_media_kontak_fol_up)&&is_null($FUData->id_kategori_status_komunikasi)){
       
        $where= array('id_follow_up'=>$id_follow_up);
        $this->H2_dealer_customer_list_model->saveFU($where, $saveData);
      }else{
        $this->db->insert('tr_h2_fol_up_detail',$saveData);
      }

      $save_id_fol_up = array('id_follow_up' =>$id_follow_up);
      $where2= array('id_customer'=>$id_customer);
      $this->H2_dealer_customer_list_model->saveFUCustomer($where2, $save_id_fol_up);
      $_SESSION['pesan'] = "Data has been saved successfully";
      $_SESSION['tipe']  = "success";
      redirect('dealer/h2_dealer_follow_up_customer_list');
    }elseif($id_karyawan_dealer==''){
      $_SESSION['pesan'] 	= "Mohon diisi PIC Follow Up";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
    }elseif($id_media_kontak_fol_up==''){
      $_SESSION['pesan'] 	= "Mohon diisi Media Follow Up";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
    }elseif($tgl_fol_up==''){
      $_SESSION['pesan'] 	= "Mohon diisi Tanggal Follow Up";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
    }elseif($id_kategori_status_komunikasi==''){
      $_SESSION['pesan'] 	= "Mohon diisi Hasil Status Follow Up";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
    }elseif($tgl_next_fol_up==''){
      $_SESSION['pesan'] 	= "Mohon diisi Tanggal Next Follow Up";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
    }elseif($is_booking==''){
      $_SESSION['pesan'] 	= "Mohon diisi Apakah Booking Service atau Tidak";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
    }
  }

  public function save_template(){
    $pesan = $this->input->post('pesan');
    $kategori = $this->input->post('kategori');
		$id_dealer = $this->m_admin->cari_dealer();
 
		$saveData = array(
			'pesan' => $pesan,
			'id_dealer' => $id_dealer,
      'created_at' =>date('Y-m-d'),
      'created_by' =>$this->session->userdata('id_user'),
      'kategori' => $kategori
			);
		$this->H2_dealer_customer_list_model->input_template($saveData);
    $_SESSION['pesan'] = "Template Pesan Berhasil Disimpan!";
    $_SESSION['tipe']  = "success";
    echo "<script>history.go(-1)</script>";
  }

  public function edit_template()
  {
    $id_template = $this->input->post('id_template');
    
    $pesan = $this->input->post('pesan2');
		$id_dealer = $this->m_admin->cari_dealer();
    
    $this->form_validation->set_rules('pesan','Pesan Template','required');
 
      $saveData = array(
        'pesan' => $pesan,
        'id_dealer' => $id_dealer,
        'updated_at' =>date('Y-m-d'),
        'updated_by' =>$this->session->userdata('id_user')
        );

      $where= array('id_template'=>$id_template);
      $this->H2_dealer_customer_list_model->update_template($where, $saveData);
      $_SESSION['pesan'] = "Template Pesan Berhasil Diupdate!";
      $_SESSION['tipe']  = "success";
      echo "<script>history.go(-1)</script>";
  }

  public function history()
  {
    $data['isi']            = $this->page;
    $data['title']          = $this->title;
    $data['set']	          = "history";
    $this->template($data);	
  }

  public function getDataHistory()
  {
    $list = $this->H2_dealer_history_fu_datatables->getDataTableHistory();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $field) {
      $status = '';
      $btn_print = '';
      if ($field->status_fol_up == 'Konsumen Tidak Datang') {
				$status = '<label class="label label-warning">Konsumen Tidak Datang</label>';
			} elseif ($field->status_fol_up == 'Batal Service') {
				$status = '<label class="label label-danger">Batal Service</label>';
			}elseif ($field->status_fol_up == 'Sedang dikerjakan') {
				$status = '<label class="label label-primary">Sedang Dikerjakan</label>';
			}elseif ($field->status_fol_up == 'Selesai') {
				$status = '<label class="label label-success">Selesai</label>';
			}

      // $btn_print = '<button type="button" class="btn btn-success btn-xs" data-toggle="tooltip" title="Detail"><i class="fa fa-eye"></i></button>';
      $btn_print ='<a id="'.$field->id_follow_up.'" href="dealer/h2_dealer_follow_up_customer_list/detail_history_customer?id_follow_up='.$field->id_follow_up.'" type="button" class="btn btn-primary btn-xs  data-toggle="tooltip" title="Detail""><i class="fa fa-eye"></i></a>';
      
       $no++;
       $row = array();
       $row[] = $no;
       $row[] = $field->id_follow_up;
       $row[] = $field->id_customer;
       $row[] = $field->nama_pengguna; 
       $row[] = $field->media_kontak;
       $row[] = $field->tgl_fol_up; 
       $row[] = $field->tgl_booking_service; 
       $row[] = $field->closed_at; 
       $row[] = $field->total_jasa; 
       $row[] = $status; 
       $row[] = $btn_print; 
       $data[] = $row;
    }
 
    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->H2_dealer_history_fu_datatables->count_all_history(),
      "recordsFiltered" => $this->H2_dealer_history_fu_datatables->count_filtered_history(),
      "data" => $data,
    );
        
    echo json_encode($output);
  }

  public function detail_history_customer()
  {
    $data['isi']            = $this->page;
    $data['title']          = $this->title;
    $data['set']	          = "detail_history";
    $data['id_dealer']      = $id_dealer = $this->m_admin->cari_dealer();
    $data['id_customer']    = $id_customer = $this->input->get('id_customer');
    $data['pic']            = $this->H2_dealer_customer_list_model->getDataKaryawan();
    $data['mediaKomunikasi']= $this->H2_dealer_customer_list_model->getMediaKomunikasi();
    $data['listFU']         = $this->H2_dealer_customer_list_model->getDataHasilFU();
    $data['getFUData']      = $this->H2_dealer_customer_list_model->getFUData();
    $data['historyFollowUp']      = $this->H2_dealer_customer_list_model->historyFollowUp();
    $data['template_pesan'] = $this->H2_dealer_customer_list_model->getDataTemplate();
    $data['template_pesan_global'] = $this->H2_dealer_customer_list_model->getDataTemplateGlobal();
    
	  $this->template($data);	
  }

  public function update_data()
  {
    $id_customer = $this->input->post('id_customer');
    $tujuan_penggunaan_motor = $this->input->post('tujuan_penggunaan_motor');
    $tgl_lahir = $this->input->post('tgl_lahir');
    $no_hp = $this->input->post('no_hp');
    $email = $this->input->post('email');
 
    $this->form_validation->set_rules('no_hp','No.Hp','required|min_length[9]|max_length[13]|numeric');
    $this->form_validation->set_rules('email','Email','required');
    
    if ($this->form_validation->run()==true){
            $this->db->trans_begin();
            $this->db->set('tujuan_penggunaan_motor', $tujuan_penggunaan_motor);
            $this->db->set('tgl_lahir', $tgl_lahir);
            $this->db->set('no_hp', $no_hp);
            $this->db->set('email', $email);
            $this->db->where('id_customer', $id_customer);
            $this->db->update('ms_customer_h23');

            if ($this->db->trans_status() == true) {
                $this->db->trans_commit();
                $result = [
                    'status' => true,
                    'message' => 'success',
                    'data' => []
                ];
            } else {
                $this->db->trans_rollback();
                $result = [
                    'status' => false,
                    'message' => 'failed',
                    'data' => []
                ];
            }
        }else
        {
            $this->db->trans_rollback();
                $result = [
                    'status' => false,
                    'message' => 'failed',
                    'data' => []
                ];
        }
        echo json_encode($result);
  }
}
