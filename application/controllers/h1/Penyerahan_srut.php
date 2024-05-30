<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penyerahan_srut extends CI_Controller {

    var $tables =   "tr_penyerahan_srut";	
		var $folder =   "h1";
		var $page		=		"penyerahan_srut";
    var $pk     =   "no_serah_terima";
    var $title  =   "Penyerahan SRUT";

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

		//---- cek session -------//		
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
		$data['set']		= "view";				
		$data['dt_penyerahan_srut'] = $this->db->query("SELECT * FROM tr_penyerahan_srut INNER JOIN ms_dealer ON tr_penyerahan_srut.id_dealer = ms_dealer.id_dealer");
		$this->template($data);			
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}		
	public function t_penyerahan_srut(){
		// $data['tgl_faktur'] = $this->input->post('tgl_faktur');
		$data['id_dealer'] 	= $this->input->post('id_dealer');		
		$this->load->view('h1/t_penyerahan_srut',$data);
	}
	public function cari_id(){				
		$th 						= date("Y");		
		$bln						= date("m");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_penyerahan_srut WHERE MID(no_serah_terima,7,2) = '$bln' ORDER BY no_serah_terima DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_serah_terima)-11;
			$id 	= substr($row->no_serah_terima,$pan,5)+1;					
			$isi 	= sprintf("%'.05d",$id);		
			$kode = $isi."/".$bln."/".$th;
		}else{		 	
		 	$kode = "00001/".$bln."/".$th;
		} 			
		return $kode;
	}
	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		
		$tabel                   = $this->tables;		
		$no_serah_terima         = $this->cari_id();		
		$data['no_serah_terima'] = $no_serah_terima;
		$data['tgl_faktur']      = date('Y-m-d');		
		// $data['tgl_faktur']      = $this->input->post('tgl_faktur');		
		$data['id_dealer']       = $this->input->post('id_dealer');				
		$data['status']          = "input";
		$data['created_at']      = $waktu;		
		$data['created_by']      = $login_id;
		$check = $this->input->post('chk');
		foreach ($check as $ms) {
			$data2['no_serah_terima'] = $no_serah_terima;
			$data2['no_mesin'] 				= $ms;					
			$this->m_admin->insert("tr_penyerahan_srut_detail",$data2);	
		}
		// $jum1 = $this->input->post("jum1");
		// for ($i=1; $i <= $jum1 ; $i++) { 
		// 	$data2['no_serah_terima'] = $no_serah_terima;
		// 	$data2['no_mesin'] 				= $this->input->post("no_mesin_".$i);					
		// 	$this->m_admin->insert("tr_penyerahan_srut_detail",$data2);	
		// }

		// $jum2 = $this->input->post("jum2");
		// for ($j=1; $j <= $jum2 ; $j++) { 
		// 	$data3['no_serah_terima'] 	= $no_serah_terima;
		// 	$data3['no_mesin'] 				= $this->input->post("no_mesin2_".$j);					
		// 	$this->m_admin->insert("tr_penyerahan_srut_detail",$data3);	
		// }
		$this->m_admin->insert("tr_penyerahan_srut",$data);	
		$_SESSION['pesan'] 		= "Data has been saved successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penyerahan_srut/add'>";		
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";				
		$id = $this->input->get('id');		
		$data['dt_penyerahan_srut'] = $this->m_admin->getByID("tr_penyerahan_srut",$this->pk,$id);
		$this->template($data);			
	}
	public function edit()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "edit_srut";				
		$id = $this->input->get('id');		
		$data['row'] = $this->m_admin->getByID("tr_penyerahan_srut",$this->pk,$id)->row();
		$this->template($data);			
	}
	public function save_edit()
	{		
		$waktu 		= gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		
		$tabel                   = $this->tables;		
		$no_serah_terima         = $this->input->post('no_serah_terima');		
		$data[0]['no_serah_terima'] = $no_serah_terima;
		$data[0]['updated_at']      = $waktu;		
		$data[0]['updated_by']      = $login_id;

		$check = $this->input->post('chk');
		foreach ($check as $key=>$ms) {
			$data2[$key]['no_serah_terima'] = $no_serah_terima;
			$data2[$key]['no_mesin']        = $ms;					
		}
		// $jum1 = $this->input->post("jum1");
		// for ($i=1; $i <= $jum1 ; $i++) { 
		// 	$data2['no_serah_terima'] = $no_serah_terima;
		// 	$data2['no_mesin'] 				= $this->input->post("no_mesin_".$i);					
		// 	$this->m_admin->insert("tr_penyerahan_srut_detail",$data2);	
		// }

		// $jum2 = $this->input->post("jum2");
		// for ($j=1; $j <= $jum2 ; $j++) { 
		// 	$data3['no_serah_terima'] 	= $no_serah_terima;
		// 	$data3['no_mesin'] 				= $this->input->post("no_mesin2_".$j);					
		// 	$this->m_admin->insert("tr_penyerahan_srut_detail",$data3);	
		// }

		$this->db->trans_begin();
			$this->db->update_batch('tr_penyerahan_srut',$data,'no_serah_terima');
			$this->m_admin->delete('tr_penyerahan_srut_detail','no_serah_terima',$no_serah_terima);
			$this->db->insert_batch("tr_penyerahan_srut_detail",$data2);	
		if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
                $_SESSION['pesan'] 	= "Something went wrong";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
        }
        else
        {
            $this->db->trans_commit();
            $_SESSION['pesan'] 		= "Data has been saved successfully";
			$_SESSION['tipe'] 		= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penyerahan_srut'>";
        }		
	}
	public function close()
	{		
		$waktu 		= gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		
		$tabel                      = $this->tables;		
		$no_serah_terima            = $this->input->get('id');		
		$data[0]['no_serah_terima'] = $no_serah_terima;
		$data[0]['status']          = 'close';
		$data[0]['updated_at']      = $waktu;		
		$data[0]['updated_by']      = $login_id;

		$this->db->trans_begin();
			$this->db->update_batch('tr_penyerahan_srut',$data,'no_serah_terima');
		if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
                $_SESSION['pesan'] 	= "Something went wrong";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
        }
        else
        {
            $this->db->trans_commit();
            $_SESSION['pesan'] 		= "Data has been closed successfully";
			$_SESSION['tipe'] 		= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penyerahan_srut'>";
        }		
	}

	public function cetak()
	{
		$this->load->library('mpdf_l');
	    $data['id'] = $this->input->get('id');        
	    $mpdf = $this->mpdf_l->load();    
		$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
	    $mpdf->charset_in='UTF-8';
	    $mpdf->autoLangToFont = true;

	    $data['cetak'] = 'print';
	    $html = $this->load->view('h1/penyerahan_srut_cetak', $data, true);
	    // render the view into HTML
	    $mpdf->WriteHTML($html);
	    // write the HTML into the mpdf
	    $output = 'penyerahan_srut_cetak.pdf';
	    $mpdf->Output("$output", 'I');
       
	}
}