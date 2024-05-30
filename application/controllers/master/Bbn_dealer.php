<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bbn_dealer extends CI_Controller {

    var $tables =   "ms_bbn_dealer";	
		var $folder =   "master";
		var $page		=		"bbn_dealer";
    var $pk     =   "id_bbn_dealer";
    var $title  =   "Master Data BBN dari Dealer ke MD";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
		
$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}

	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{						
			$data['id_menu'] = $this->m_admin->getMenu($this->page);
			$data['group'] 	= $this->session->userdata("group");
			$this->load->view('template/header',$data);
			$this->load->view('template/aside');			
			$this->load->view($this->folder."/".$this->page);		
			$this->load->view('template/footer');
		}
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "view";
		$data['dt_bbn_dealer'] = $this->db->query("SELECT ms_bbn_dealer.*, ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.deskripsi_ahm FROM ms_bbn_dealer LEFT JOIN ms_tipe_kendaraan 
																			ON ms_bbn_dealer.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan");							
		$this->template($data);	
	}

	public function proses_import()
	{
		$filenya = 'uploads/import_bbn_dealer.xlsx';
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        // Fungsi untuk melakukan proses upload file
        $return = array();
        $this->load->library('upload'); // Load librari upload

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'xlsx';
        $config['max_size'] = '2048';
        $config['overwrite'] = true;
        $config['file_name'] = 'import_bbn_dealer';

        $this->upload->initialize($config); // Load konfigurasi uploadnya
        if($this->upload->do_upload('import_file')){ // Lakukan upload dan Cek jika proses upload berhasil
            // Jika berhasil :
            $return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
            // return $return;
        }else{
            // Jika gagal :
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            // return $return;
        }
        // print_r($return);exit();

        $excelreader = new PHPExcel_Reader_Excel2007();
        $loadexcel = $excelreader->load($filenya); // Load file yang telah diupload ke folder excel
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
        // Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
        $data = array();
        $error = '';

       	$tgl_efektif = $sheet[1]['B'];
       	if ($tgl_efektif == '') {
       		$_SESSION['pesan'] 	= "Data yang diimport tidak memiliki tanggal efektif ! <br>";
			$_SESSION['tipe'] 	= "error";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/bbn_dealer'>";
			exit();
       	}

       	$this->db->trans_begin();
        $numrow = 1;
        foreach($sheet as $rw_excel){
            // Cek $numrow apakah lebih dari 1
            // Artinya karena baris pertama adalah nama-nama kolom
            // Jadi dilewat saja, tidak usah diimport

            if($numrow > 3){
                // Kita push (add) array data ke variabel data

                if (!empty($rw_excel['D'])) {
                	$_SESSION['pesan'] 	= "Sepertinya file yang anda upload ada kesalahan ! <br>";
					$_SESSION['tipe'] 	= "error";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/bbn_dealer'>";
					exit();
                }

            	if (!empty($rw_excel['A']) OR !empty($rw_excel['B']) OR !empty($rw_excel['C']) ) {

            		$this->db->where('id_tipe_kendaraan', $rw_excel['A']);
            		$cek_tipe = $this->db->get('ms_bbn_dealer');
            		if ($cek_tipe->num_rows() > 0) {
            			if ($rw_excel['B'] > 0 or $rw_excel['C'] > 0) {
            				$this->db->where('id_tipe_kendaraan', $rw_excel['A']);
	            			$this->db->update('ms_bbn_dealer', array(
	            				'biaya_bbn'=> $rw_excel['B'],
	            				'biaya_instansi' => $rw_excel['C'],
	            				'tgl_efektif' => $tgl_efektif,
	            				'updated_at' => get_waktu(),
            					'updated_by' => $this->session->userdata('id_user'),
	            			));
            			}
            			
            		} else {

            			//validasi insert data yang tipe kendaraan nya sdh ada
            			$this->db->where('id_tipe_kendaraan', $rw_excel['A']);
            			$this->db->where('active', 1);
            			$cek_valid = $this->db->get('ms_bbn_dealer');
            			if ($cek_valid->num_rows() > 0) {
            				$_SESSION['pesan'] 	= "Tipe Kendaraan ini ".$rw_excel['A']." sudah ada ! <br>";
							$_SESSION['tipe'] 	= "error";
							echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/bbn_dealer'>";
							exit();
            			}

            			$tgl						= date('Y-m-d');
						$th 						= date("y");
						$bln 						= date("m");		
						$tgl 						= date("d");		
						$pr_num 				= $this->db->query("SELECT * FROM ms_bbn_dealer ORDER BY id_bbn_dealer DESC LIMIT 0,1");						
						if($pr_num->num_rows()>0){
							$row 	= $pr_num->row();				
							$pan  = strlen($row->id_bbn_dealer)-5;
							$id 	= substr($row->id_bbn_dealer,$pan,5)+1;	
							if($id < 10){
									$kode1 = $th.$bln.$tgl."/BBN-D-MD/0000".$id;          
				      }elseif($id>9 && $id<=99){
									$kode1 = $th.$bln.$tgl."/BBN-D-MD/000".$id;                    
				      }elseif($id>99 && $id<=999){
									$kode1 = $th.$bln.$tgl."/BBN-D-MD/00".$id;          					          
				      }elseif($id>999){
									$kode1 = $th.$bln.$tgl."/BBN-D-MD/0".$id;                    
				      }
							$kode = $kode1;
						}else{
							$kode = $th.$bln.$tgl."/BBN-D-MD/"."00001";
						} 	
						// echo $kode;

            			$this->db->insert('ms_bbn_dealer', array(
            				'id_bbn_dealer' => $kode,
            				'id_tipe_kendaraan' => $rw_excel['A'],
            				'biaya_bbn' => $rw_excel['B'],
            				'biaya_instansi' => $rw_excel['C'],
            				'tgl_efektif' => $tgl_efektif,
            				'created_at' => get_waktu(),
            				'created_by' => $this->session->userdata('id_user'),
            				'active' => 1
            			));
            		}
            		
            	} else {
            		$_SESSION['pesan'] 	= "Data yang diimport tidak boleh kosong <br>";
					$_SESSION['tipe'] 	= "error";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/bbn_dealer'>";
					exit();
            	}
            }

            $numrow++; // Tambah 1 setiap kali looping
        }
        if ($this->db->trans_status() === FALSE)
		{
	        $this->db->trans_rollback();
	        $_SESSION['pesan'] 	= "Data gagal di import";
			$_SESSION['tipe'] 	= "error";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/bbn_dealer'>";

	    } else {
	    	$this->db->trans_commit();
	    	unlink($filenya);
	        $_SESSION['pesan'] 	= "Data has been import successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/bbn_dealer'>";
		}
        

	}

	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "insert";	
		$data['dt_tipe_kendaraan'] = $this->m_admin->getSort("ms_tipe_kendaraan","tipe_ahm","ASC");								
		$this->template($data);	
	}
	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel			= $this->tables;		
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$data['id_bbn_dealer'] 					= $this->input->post('id_bbn_dealer');				
			$data['biaya_bbn']					= $this->m_admin->ubah_rupiah($this->input->post('biaya_bbn'));				
			$data['biaya_instansi'] 		= $this->m_admin->ubah_rupiah($this->input->post('biaya_instansi'));				
			$data['id_tipe_kendaraan'] 	= $this->input->post('id_tipe_kendaraan');
			if($this->input->post('active') == '1'){
				$data['active'] 					= $this->input->post('active');		
			}else{
				$data['active'] 					= "";					
			}				
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;

			//validasi insert data yang tipe kendaraan nya sdh ada
			$this->db->where('id_tipe_kendaraan', $this->input->post('id_tipe_kendaraan'));
			$this->db->where('active', 1);
			$cek_valid = $this->db->get('ms_bbn_dealer');
			if ($cek_valid->num_rows() > 0) {
				$_SESSION['pesan'] 	= "Tipe Kendaraan ini ".$this->input->post('id_tipe_kendaraan')." sudah ada ! <br>";
				$_SESSION['tipe'] 	= "error";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/bbn_dealer'>";
				exit();
			}

			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/bbn_dealer/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function cari_id(){
		$tgl						= $this->input->post('tgl');	
		$th 						= date("y");
		$bln 						= date("m");
		$thn 						= date("Y");		
		$tgl 						= date("d");		
		$pr_num 				= $this->db->query("SELECT * FROM ms_bbn_dealer where left(created_at,4) = '$thn' ORDER BY id_bbn_dealer DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_bbn_dealer)-5;
			$id 	= substr($row->id_bbn_dealer,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th.$bln.$tgl."/BBN-D-MD/0000".$id;          
      			}elseif($id>9 && $id<=99){
					$kode1 = $th.$bln.$tgl."/BBN-D-MD/000".$id;                    
      			}elseif($id>99 && $id<=999){
					$kode1 = $th.$bln.$tgl."/BBN-D-MD/00".$id;          					          
      			}elseif($id>999){
					$kode1 = $th.$bln.$tgl."/BBN-D-MD/0".$id;                    
      		}
			$kode = $kode1;
		}else{
			$kode = $th.$bln.$tgl."/BBN-D-MD/"."00001";
		} 	
		echo $kode;
	}
	public function delete()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');	
		$cek_approval  = $this->m_admin->cek_approval($tabel,$pk,$id);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{	
			$this->db->trans_begin();			
			$this->db->delete($tabel,array($pk=>$id));
			$this->db->trans_commit();			
			$result = 'Success';									

			if($this->db->trans_status() === FALSE){
				$result = 'You can not delete this data because it already used by the other tables';										
				$_SESSION['tipe'] 	= "danger";			
			}else{
				$result = 'Data has been deleted succesfully';										
				$_SESSION['tipe'] 	= "success";			
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/bbn_dealer'>";
		}
	}
	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel,$pk,$id);
		}
		echo json_encode(array("status" => TRUE));
	}
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_bbn_dealer'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_tipe_kendaraan'] = $this->m_admin->getSort("ms_tipe_kendaraan","tipe_ahm","ASC");								
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		

		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['id_bbn_dealer'] 					= $this->input->post('id_bbn_dealer');				
			$data['biaya_bbn']					= $this->m_admin->ubah_rupiah($this->input->post('biaya_bbn'));				
			$data['biaya_instansi']			= $this->m_admin->ubah_rupiah($this->input->post('biaya_instansi'));								
			$data['id_tipe_kendaraan'] 	= $this->input->post('id_tipe_kendaraan');
			if($this->input->post('active') == '1'){
				$data['active'] 					= $this->input->post('active');		
			}else{
				$data['active'] 					= "";					
			}
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/bbn_dealer'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}