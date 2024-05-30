<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelompok_md extends CI_Controller {

    var $tables =   "ms_kelompok_md";	
		var $folder =   "master";
		var $page		=		"kelompok_md";
    var $pk     =   "id_kelompok_md";
    var $title  =   "Master Data Kelompok Harga Jual MD";

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
		if($name=="" OR $auth=='false' OR $sess=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel?Anda_Tidak_Punya_Akses_untuk_Menu_ini!'>";
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
		// $data['dt_kelompok_md'] = $this->db->query("SELECT ms_kelompok_md.*,ms_kelompok_harga.kelompok_harga FROM ms_kelompok_md LEFT JOIN ms_kelompok_harga 
					// ON ms_kelompok_md.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga");							
		$data['data']=$this->db->query("SELECT ms_kelompok_harga.*,ms_kelompok_md_harga.*,id_kel as kel, (SELECT count(id_kel) FROM ms_kelompok_md_harga_detail WHERE id_kel=kel)as jum FROM ms_kelompok_md_harga
			left join ms_kelompok_harga on ms_kelompok_md_harga.id_kelompok_harga=ms_kelompok_harga.id_kelompok_harga
			ORDER BY ms_kelompok_md_harga.id_kel DESC
			");
		$this->template($data);		
	}

	public function all()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "view_all";
		$where = "";
		$data['s'] = "";
		if(isset($_GET['filter'])){
		    $start = $this->input->get("s");
		    if($start!=""){
		        $data['s'] = $start;
		        $where .= " WHERE km.start_date = '$start'";
		    }
		}
		// $data['dt_kelompok_md_old'] = $this->db->query("SELECT ms_kelompok_md.*,ms_kelompok_harga.kelompok_harga FROM ms_kelompok_md LEFT JOIN ms_kelompok_harga 
		// 			 ON ms_kelompok_md.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga");	
		// $data['dt_kelompok_md'] = $this->db->query("SELECT * FROM ms_kelompok_md GROUP BY id_kelompok_harga,id_item ORDER BY start_date DESC, id_item");
// 		$data['dt_kelompok_md'] = $this->db->query("SELECT id_kelompok_harga, id_item,created_at,harga_jual,active,harga_bbn,start_date,end_date
// FROM (
//     SELECT id_kelompok_harga, MAX(start_date), id_item,created_at,harga_jual,active,harga_bbn,start_date,end_date
//     FROM ms_kelompok_md
//     GROUP BY id_item DESC) as ids  
// ORDER BY `ids`.`created_at` DESC");
$data['dt_kelompok_md'] = $this->db->query("SELECT km.id_kelompok_md, km.id_kelompok_harga,km.id_item,c.deskripsi_ahm, d.warna, c.tipe_ahm,
(SELECT harga_bbn FROM ms_kelompok_md WHERE id_item=km.id_item AND id_kelompok_harga=km.id_kelompok_harga ORDER BY start_date DESC LIMIT 1) AS harga_bbn,
(SELECT harga_jual FROM ms_kelompok_md WHERE id_item=km.id_item AND id_kelompok_harga=km.id_kelompok_harga ORDER BY start_date DESC LIMIT 1) AS harga_jual,
(SELECT start_date FROM ms_kelompok_md WHERE id_item=km.id_item AND id_kelompok_harga=km.id_kelompok_harga ORDER BY start_date DESC LIMIT 1) AS start_date,
(SELECT end_date FROM ms_kelompok_md WHERE id_item=km.id_item AND id_kelompok_harga=km.id_kelompok_harga ORDER BY start_date DESC LIMIT 1) AS end_date,
(SELECT active FROM ms_kelompok_md WHERE id_item=km.id_item AND id_kelompok_harga=km.id_kelompok_harga ORDER BY start_date DESC LIMIT 1) AS active
FROM ms_kelompok_md AS km 
join ms_item b on b.id_item = km.id_item
join ms_tipe_kendaraan c on b.id_tipe_kendaraan =c.id_tipe_kendaraan
join ms_warna d on b.id_warna = d.id_warna 
group BY km.id_item,km.id_kelompok_harga
			");
		$this->template($data);		
	}

	public function history()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title." (History)";															
		$data['set']		= "history";
		$data['dt_kelompok_md_old'] = $this->db->query("SELECT ms_kelompok_md.*,ms_kelompok_harga.kelompok_harga FROM ms_kelompok_md LEFT JOIN ms_kelompok_harga 
					 ON ms_kelompok_md.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga");	
		$data['dt_kelompok_md'] = $this->db->query("SELECT * FROM ms_kelompok_md WHERE id_kelompok_md NOT IN 
						(SELECT id_kelompok_md FROM (SELECT * FROM ms_kelompok_md ORDER BY start_date DESC, id_item) X
						GROUP BY id_kelompok_harga,id_item)");
		$this->template($data);		
	}
	public function send()
	{
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');	
		$id_kel		= $this->input->get('id');
		$dt 			= $this->db->query("SELECT * FROM ms_kelompok_md_harga_detail 
								LEFT JOIN ms_kelompok_md_harga on ms_kelompok_md_harga.id_kel=ms_kelompok_md_harga_detail.id_kel
								WHERE ms_kelompok_md_harga_detail.id_kel='$id_kel' ");
		if ($dt->num_rows() >0) {
			
			$data['status'] = 'Waiting Approval';
			$data['updated_at'] = $waktu;
			$data['updated_by'] = $login_id;

			$this->db->trans_begin();
			$this->m_admin->update('ms_kelompok_md_harga',$data,'id_kel',$id_kel);
			$this->m_admin->update('ms_kelompok_md_harga_detail',$data,'id_kel',$id_kel);
			if ($this->db->trans_status() === FALSE){
	     	$this->db->trans_rollback();
	      $_SESSION['pesan'] 		= "Something Wen't Wrong";
				$_SESSION['tipe'] 		= "danger";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kelompok_md'>";	
	    }else{
	      $this->db->trans_commit();
	      $_SESSION['pesan'] 		= "Data has been saved successfully";
				$_SESSION['tipe'] 		= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kelompok_md'>";	
	    }
		}else{
			$_SESSION['pesan'] 		= "Something Wen't Wrong";
			$_SESSION['tipe'] 		= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kelompok_md'>";	
		}
	}

	public function approve()
	{
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "approve";
		$id_kel 		= $this->input->get('id');					
		$data['data']=$this->db->query("SELECT ms_kelompok_harga.*,ms_kelompok_md_harga.*,id_kel as kel,ms_kelompok_md_harga.status, (SELECT count(id_kel) FROM ms_kelompok_md_harga_detail WHERE id_kel=kel)as jum FROM ms_kelompok_md_harga
			left join ms_kelompok_harga on ms_kelompok_md_harga.id_kelompok_harga=ms_kelompok_harga.id_kelompok_harga
			WHERE (ms_kelompok_md_harga.status='input' OR ms_kelompok_md_harga.status='rejected' OR ms_kelompok_md_harga.status='Waiting Approval') AND ms_kelompok_md_harga.id_kel='$id_kel'
			");
		if ($data['data']->num_rows()>0) {
			$data['detail']=$this->db->query("SELECT *,ms_kelompok_md_harga_detail.keterangan FROM ms_kelompok_md_harga_detail 
						LEFT JOIN ms_item on ms_kelompok_md_harga_detail.id_item = ms_item.id_item
						LEFT JOIN ms_tipe_kendaraan on ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
						LEFT JOIN ms_warna on ms_item.id_warna = ms_warna.id_warna 
			WHERE ms_kelompok_md_harga_detail.id_kel='$id_kel' ");
		$data['tipe']=$this->db->query("SELECT * FROM ms_kelompok_md_harga_detail 
			LEFT JOIN ms_item on ms_kelompok_md_harga_detail.id_item = ms_item.id_item
						LEFT JOIN ms_tipe_kendaraan on ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
						LEFT JOIN ms_warna on ms_item.id_warna = ms_warna.id_warna 
			WHERE ms_kelompok_md_harga_detail.id_kel='$id_kel' GROUP BY LEFT(ms_kelompok_md_harga_detail.id_item,3)");
			$this->template($data);		
		}else{
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kelompok_md'>";	

		}
		
	}

	public function save_approve_reject()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');	
		$submit		= $this->input->post('save');
		if ($submit=='approve') {
			$id_kel 				= $this->input->post('id_kel');		
			$data['status'] 		= "approved";
			$data['tgl_approve']	= $waktu;


			$dt=$this->db->query("SELECT * FROM ms_kelompok_md_harga_detail 
						JOIN ms_kelompok_md_harga on ms_kelompok_md_harga.id_kel=ms_kelompok_md_harga_detail.id_kel
				WHERE ms_kelompok_md_harga_detail.id_kel='$id_kel' ");
			if ($dt->num_rows() > 0) {
				foreach ($dt->result() as $key => $val) {
					$dt_md[$key]['id_kelompok_harga'] = $val->id_kelompok_harga;
					$dt_md[$key]['id_item'] = $val->id_item;
					$dt_md[$key]['harga_jual'] = $val->harga_jual;
					$dt_md[$key]['start_date'] = $val->start_date;
					$dt_md[$key]['created_at'] = $waktu;
					$dt_md[$key]['created_by'] = $login_id;
					$dt_md[$key]['active'] = 1;
				}
			}
			for ($i=0; $i < $dt->num_rows(); $i++) { 
				$dt_ket[$i]['keterangan'] 	= $this->input->post('keterangan_'.$i);
				$dt_ket[$i]['id'] 			= $this->input->post('id_'.$i);
				$dt_ket[$i]['status'] 		= "approved";
			}

			$this->db->trans_begin();
				$this->m_admin->update('ms_kelompok_md_harga',$data,'id_kel',$id_kel);
        		$this->db->insert_batch('ms_kelompok_md',$dt_md);
        		$this->db->update_batch('ms_kelompok_md_harga_detail', $dt_ket, 'id');
			if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                     $_SESSION['pesan'] 		= "Something Wen't Wrong";
					$_SESSION['tipe'] 		= "danger";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kelompok_md/approve?id=".$id_kel."'>";	
            }
            else
            {
                $this->db->trans_commit();
                $_SESSION['pesan'] 		= "Data has been saved successfully";
				$_SESSION['tipe'] 		= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kelompok_md/approve?id=".$id_kel."'>";	
            }
		}
		elseif ($submit=='reject') {
			$id_kel 				= $this->input->post('id_kel');		
			$data['status'] 		= "rejected";
			$data['updated_at'] 		= $waktu;
			$data['tgl_reject'] 		= $waktu;
			$data['updated_by'] 		= $login_id;


			$dt=$this->db->query("SELECT * FROM ms_kelompok_md_harga_detail WHERE ms_kelompok_md_harga_detail.id_kel='$id_kel' ");
			for ($i=0; $i < $dt->num_rows(); $i++) { 
				$dt_ket[$i]['keterangan'] 	= $this->input->post('keterangan_'.$i);
				$dt_ket[$i]['id'] 			= $this->input->post('id_'.$i);
				$dt_ket[$i]['status'] 		= "rejected";
				$data['updated_at'] 		= $waktu;
				$data['updated_by'] 		= $login_id;
			}

			$this->db->trans_begin();
				$this->m_admin->update('ms_kelompok_md_harga',$data,'id_kel',$id_kel);
        		$this->db->update_batch('ms_kelompok_md_harga_detail', $dt_ket, 'id');
			if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                     $_SESSION['pesan'] 		= "Something Wen't Wrong";
					$_SESSION['tipe'] 		= "danger";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kelompok_md'>";	
            }
            else
            {
                $this->db->trans_commit();
                $_SESSION['pesan'] 		= "Data has been saved successfully";
				$_SESSION['tipe'] 		= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kelompok_md'>";	
            }
		}					
	}



	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['dt_kel'] = $this->m_admin->getSortCond("ms_kelompok_harga","kelompok_harga","ASC");	
		$data['dt_item'] = $this->m_admin->getSortCond("ms_item","id_item","ASC");	
		$data['set']	= "insert";									
		$this->template($data);	
	}

	public function delData(){
		$login_id	= $this->session->userdata('id_user');
		
		$this->db->query("DELETE FROM ms_kelompok_md_harga_detail WHERE created_by='$login_id' AND status='new'");
		echo "ok";
	}
	public function generate()
	{				
		$data['id_kelompok_harga']=$this->input->post('id_kelompok_harga');
		$login_id	= $this->session->userdata('id_user');
		$data['detail']=$this->db->query("SELECT * FROM ms_kelompok_md_harga_detail 
						LEFT JOIN ms_item on ms_kelompok_md_harga_detail.id_item = ms_item.id_item
						LEFT JOIN ms_tipe_kendaraan on ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
						LEFT JOIN ms_warna on ms_item.id_warna = ms_warna.id_warna 
			WHERE ms_kelompok_md_harga_detail.created_by='$login_id' AND ms_kelompok_md_harga_detail.status='new'");
		$data['tipe']=$this->db->query("SELECT * FROM ms_kelompok_md_harga_detail 
			LEFT JOIN ms_item on ms_kelompok_md_harga_detail.id_item = ms_item.id_item
						LEFT JOIN ms_tipe_kendaraan on ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
						LEFT JOIN ms_warna on ms_item.id_warna = ms_warna.id_warna 
			WHERE ms_kelompok_md_harga_detail.created_by='$login_id' AND ms_kelompok_md_harga_detail.status='new' GROUP BY LEFT(ms_kelompok_md_harga_detail.id_item,3)");
		$this->load->view('master/t_kelompok_md',$data);							
	}
	public function t_edit()
	{				
		$data['id_kel'] = $id_kel = $this->input->get('id');		
		$data['detail']=$this->db->query("SELECT * FROM ms_kelompok_md_harga_detail 
						LEFT JOIN ms_item on ms_kelompok_md_harga_detail.id_item = ms_item.id_item
						LEFT JOIN ms_tipe_kendaraan on ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
						LEFT JOIN ms_warna on ms_item.id_warna = ms_warna.id_warna 
						WHERE ms_kelompok_md_harga_detail.id_kel='$id_kel'");		
		$this->load->view('master/t_kelompok_md_edit',$data);							
	}

	public function getInput()
	{				
		//$data=$this->db->query("SELECT * FROM ms_kelompok_md ORDER BY id_item");
		//$id_kelompok_harga = $this->input->post('id_kelompok_harga');
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		//$dq = "SELECT *,LEFT(ms_kelompok_md.id_item,3) as id_tipe_kendaraan FROM ms_kelompok_md LEFT JOIN ms_item ON ms_kelompok_md.id_item=ms_item.id_item LEFT join ms_warna ON ms_item.id_warna=ms_warna.id_warna WHERE LEFT(ms_kelompok_md.id_item,3)='$id_tipe_kendaraan'  GROUP BY ms_kelompok_md.id_item ORDER BY ms_kelompok_md.start_date DESC";
		$id_kelompok_harga = $this->input->post('id_kelompok_harga');
		$dq="SELECT *,ms_item.id_item as item, (SELECT harga_jual FROM ms_kelompok_md WHERE id_item=item AND id_kelompok_harga='$id_kelompok_harga' ORDER BY start_date DESC LIMIT 0,1) as harga_jual FROM ms_item
			left JOIN ms_tipe_kendaraan on ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			INNER join ms_warna on ms_item.id_warna = ms_warna.id_warna
		 WHERE ms_item.id_tipe_kendaraan='$id_tipe_kendaraan' AND ms_item.active=1  ORDER BY ms_item.bundling DESC";
		$data['detail'] = $this->db->query($dq);		
		$data['tipe'] = $this->db->query("SELECT * FROM ms_tipe_kendaraan ORDER BY id_tipe_kendaraan");

		$id_tipe_kendaraan==null?$data['id_tipe_kendaraan']=null:$data['id_tipe_kendaraan']=$id_tipe_kendaraan;
		$this->load->view('master/t_kelompok_md_input',$data);								
	}
	public function addDetail(){
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');
		$mode = 0;

		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		//$dq = "SELECT *,LEFT(ms_kelompok_md.id_item,3) as id_tipe_kendaraan FROM ms_kelompok_md LEFT JOIN ms_item ON ms_kelompok_md.id_item=ms_item.id_item LEFT join ms_warna ON ms_item.id_warna=ms_warna.id_warna WHERE LEFT(ms_kelompok_md.id_item,3)='$id_tipe_kendaraan'  GROUP BY ms_kelompok_md.id_item ORDER BY ms_kelompok_md.start_date DESC";
		$dq="SELECT *,ms_item.id_item as item, (SELECT harga_jual FROM ms_kelompok_md WHERE id_item=item ORDER BY start_date DESC LIMIT 0,1) as harga_jual FROM ms_item
			left JOIN ms_tipe_kendaraan on ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			INNER join ms_warna on ms_item.id_warna = ms_warna.id_warna
		 	WHERE ms_item.id_tipe_kendaraan='$id_tipe_kendaraan' AND ms_item.active=1";
		$dt = $this->db->query($dq);		
		for ($i=0; $i < $dt->num_rows(); $i++) { 
			$checked	= $this->input->post('chk_'.$i);
			if ($checked=='ya') {							
				$data['id_item'] 	= $id_item	= $this->input->post('id_item_'.$i);
				$data['checked']	= $this->input->post('chk_'.$i);
				$data['id_kel']		= $this->input->post('id_kel');
				$data['harga_jual']	= str_replace('.', '', $this->input->post('harga_baru_'.$i));
				$data['status']			= 'new';
				$data['created_at']	= $waktu;
				$data['created_by']	= $login_id;		

				$cek_sql = $this->db->query("SELECT * FROM ms_kelompok_md_harga_detail WHERE id_item = '$id_item' 
						AND status = 'new' AND created_by = '$login_id'");
				if($cek_sql->num_rows() > 0){
					$mode++;
				}else{
					$this->m_admin->insert("ms_kelompok_md_harga_detail",$data);		
				}
			}
		}		
		if($mode == 0){
			echo "nihil";
		}else{
			echo "Tipe Kendaraan ini telah diinput sebelumnya";
		}
	}

	public function delDetail(){
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');


		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		$this->db->query("DELETE FROM ms_kelompok_md_harga_detail WHERE status='new' AND created_by='$login_id' AND LEFT(id_item,3)='$id_tipe_kendaraan' ");
		echo 'nihil';
	}

	public function getEditDetail()
	{
		$id_kel = $this->input->post('id_kel');	
		$data['edit']   = $this->input->post('edit');
		$getHeader = $this->db->query("SELECT * FROM ms_kelompok_md_harga WHERE id_kel='$id_kel'");
		if ($getHeader->num_rows()>0) {
			$data['header'] = $getHeader->row();		
		}	
		$data['detail']=$this->db->query("SELECT *,ms_kelompok_md_harga_detail.keterangan FROM ms_kelompok_md_harga_detail 
						LEFT JOIN ms_item on ms_kelompok_md_harga_detail.id_item = ms_item.id_item
						LEFT JOIN ms_tipe_kendaraan on ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
						LEFT JOIN ms_warna on ms_item.id_warna = ms_warna.id_warna 
			WHERE ms_kelompok_md_harga_detail.id_kel='$id_kel' ");
		$data['tipe']=$this->db->query("SELECT * FROM ms_kelompok_md_harga_detail 
			LEFT JOIN ms_item on ms_kelompok_md_harga_detail.id_item = ms_item.id_item
						LEFT JOIN ms_tipe_kendaraan on ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
						LEFT JOIN ms_warna on ms_item.id_warna = ms_warna.id_warna 
			WHERE ms_kelompok_md_harga_detail.id_kel='$id_kel' GROUP BY LEFT(ms_kelompok_md_harga_detail.id_item,3)");
		$this->load->view('master/t_kelompok_md',$data);
	}

	public function saveEditDetailOne()
	{
		$waktu                 = gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id              = $this->session->userdata('id_user');
		$tabel                 = 'ms_kelompok_md_harga_detail';
		$pk                    = 'id';
		$id_kel                = $this->input->post('id_kel');	
		$id                    = $this->input->post('id');	
		$dt_edit['harga_jual'] = $this->m_admin->ubah_rupiah($this->input->post('harga_jual'));	
		$dt_edit['updated_at'] = $waktu;		
		$dt_edit['updated_by'] = $login_id;		
		$this->m_admin->update($tabel,$dt_edit,$pk,$id);

		$data['edit']   = 'y';
		$getHeader = $this->db->query("SELECT * FROM ms_kelompok_md_harga WHERE id_kel='$id_kel'");
		if ($getHeader->num_rows()>0) {
			$data['header'] = $getHeader->row();		
		}	
		$data['detail']=$this->db->query("SELECT *,ms_kelompok_md_harga_detail.keterangan FROM ms_kelompok_md_harga_detail 
						LEFT JOIN ms_item on ms_kelompok_md_harga_detail.id_item = ms_item.id_item
						LEFT JOIN ms_tipe_kendaraan on ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
						LEFT JOIN ms_warna on ms_item.id_warna = ms_warna.id_warna 
			WHERE ms_kelompok_md_harga_detail.id_kel='$id_kel' ");
		$data['tipe']=$this->db->query("SELECT * FROM ms_kelompok_md_harga_detail 
			LEFT JOIN ms_item on ms_kelompok_md_harga_detail.id_item = ms_item.id_item
						LEFT JOIN ms_tipe_kendaraan on ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
						LEFT JOIN ms_warna on ms_item.id_warna = ms_warna.id_warna 
			WHERE ms_kelompok_md_harga_detail.id_kel='$id_kel' GROUP BY LEFT(ms_kelompok_md_harga_detail.id_item,3)");
		$this->load->view('master/t_kelompok_md',$data);
	}

	

	// public function save()
	// {		
	// 	$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
	// 	$login_id		= $this->session->userdata('id_user');
	// 	$tabel			= $this->tables;
	// 	$pk					= $this->pk;
	// 	$id  				= $this->input->post($pk);
	// 	$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
	// 	if($cek == 0){
	// 		$data['id_kelompok_harga'] 	= $this->input->post('id_kelompok_harga');		
	// 		$data['harga_bbn'] 					= $this->input->post('harga_bbn');		
	// 		$data['harga_jual'] 				= $this->input->post('harga_jual');		
	// 		$data['id_item'] 						= $this->input->post('id_item');		
	// 		$data['start_date']					= $this->input->post('start_date');		
	// 		$data['end_date']						= $this->input->post('end_date');		
	// 		if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
	// 			else $data['active'] 		= "";					
	// 		$data['created_at']				= $waktu;		
	// 		$data['created_by']				= $login_id;
	// 		$this->m_admin->insert($tabel,$data);
	// 		$_SESSION['pesan'] 	= "Data has been saved successfully";
	// 		$_SESSION['tipe'] 	= "success";
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kelompok_md/add'>";
	// 	}else{
	// 		$_SESSION['pesan'] 	= "Duplicate entry for primary key";
	// 		$_SESSION['tipe'] 	= "danger";
	// 		echo "<script>history.go(-1)</script>";
	// 	}
	// }

	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');	

		$id_kelompok_harga 			= $this->input->post('id_kelompok_harga');		
		$data['id_kelompok_harga'] 			= $this->input->post('id_kelompok_harga');		
		$data['start_date'] 			= $this->input->post('start_date');	
		$data['status'] 						= "input";
		$data['created_at']					= $waktu;		
		$data['created_by']					= $login_id;


		$this->db->trans_begin();
				$this->m_admin->insert('ms_kelompok_md_harga',$data);
				$lastHeader=$this->db->query("SELECT id_kel From ms_kelompok_md_harga WHERE created_by='$login_id' AND status='input' AND id_kelompok_harga='$id_kelompok_harga' ORDER BY id_kel DESC LIMIT 0,1")->row()->id_kel;

				$this->db->query("UPDATE ms_kelompok_md_harga_detail set status='input', id_kel = '$lastHeader', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id'");
			if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                     $_SESSION['pesan'] 		= "Something Wen't Wrong";
					$_SESSION['tipe'] 		= "danger";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kelompok_md'>";	
            }
            else
            {
                    $this->db->trans_commit();
                   $_SESSION['pesan'] 		= "Data has been saved successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kelompok_md'>";	
            }
			
	}



	public function delete()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');		
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
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kelompok_md'>";
	}
	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel,$pk,$id);
		}
		echo json_encode(array("status" => TRUE));
	}
	// public function edit()
	// {		
	// 	$tabel			= $this->tables;
	// 	$pk 			= $this->pk;		
	// 	$id 			= $this->input->get('id');		
	// 	$data['dt_kelompok_md'] = $this->m_admin->getByID("ms_kelompok_md_harga","id_kel",$id);
	// 	$data['dt_kel'] = $this->m_admin->getSortCond("ms_kelompok_harga","kelompok_harga","ASC");	
	// 	$data['dt_item'] = $this->m_admin->getSortCond("ms_item","id_item","ASC");	
	// 	$data['isi']    = $this->page;		
	// 	$data['title']	= "Edit ".$this->title;		
	// 	$data['set']	= "edit";									
	// 	$this->template($data);	
	// }
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');		
		$kel = $data['dt_kelompok_md'] = $this->m_admin->getByID("ms_kelompok_md_harga","id_kel",$id);
		$kel = $kel->row();
		$data['dt_kel'] = $this->m_admin->getSortCond("ms_kelompok_harga","kelompok_harga","ASC");	
		$data['dt_item'] = $this->m_admin->getSortCond("ms_item","id_item","ASC");	
		$data['details'] = $this->db->query("SELECT mhd.harga_jual AS harga_baru,mhd.id_item,ms_item.id_tipe_kendaraan,ms_item.id_item,tipe_ahm,deskripsi_ahm,(SELECT harga_jual FROM ms_kelompok_md WHERE id_item=mhd.id_item AND start_date<='$kel->start_date' AND id_kelompok_harga='$kel->id_kelompok_harga' ORDER BY created_at DESC LIMIT 1) AS harga_akhir,warna,mhd.keterangan,mhd.status 
								FROM ms_kelompok_md_harga_detail AS mhd
								JOIN ms_item ON mhd.id_item=ms_item.id_item
								JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan 
								JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna 
								WHERE id_kel='$id'")->result();
		$data['isi']    = $this->page;		
		$data['title']	= "Edit ".$this->title;		
		$data['set']	= "edit";									
		$data['form']	= "save_edit";									
		$data['status']	= $kel->status;									
		$this->template($data);	
	}
	function getItem()
	{
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		$start_date        = $this->input->post('start_date');
		$id_kelompok_harga = $this->input->post('id_kelompok_harga');
		$item = $this->db->query("SELECT item.id_item,item.id_tipe_kendaraan,item.id_warna warna,tipe_ahm,deskripsi_ahm,(SELECT harga_jual FROM ms_kelompok_md WHERE id_item=item.id_item AND start_date<='$start_date' AND id_kelompok_harga='$id_kelompok_harga' ORDER BY created_at DESC LIMIT 1) AS harga_akhir,'' AS harga_baru,'' AS keterangan,'' AS status
			FROM ms_item AS item
			JOIN ms_tipe_kendaraan ON item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan 
			JOIN ms_warna ON item.id_warna=ms_warna.id_warna 
			WHERE item.id_tipe_kendaraan='$id_tipe_kendaraan' AND item.active=1 ORDER BY id_item ASC")->result();
		echo json_encode($item);
		// foreach ($collection as $value) {
			
		// }
	}
	public function save_edit()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		
		$id   = $this->input->post('id');
		$cek = $this->db->query("SELECT * FROM ms_kelompok_md_harga WHERE id_kel='$id'");
		if ($cek->num_rows()>0) {
			$kel = $cek->row();
		}else{
			$rsp = ['status'=> 'error',
					'pesan'=> 'Data pada master tidak ditemukan !'
			];
			echo json_encode($rsp);
			exit;
		}

		$data['id_kel'] = $id;
		$data['updated_at']           = $waktu;		
		$data['updated_by']           = $login_id;

		$this->db->trans_begin();
			$this->db->update('ms_kelompok_md_harga',$data,['id_kel'=>$id]);
			$this->db->delete('ms_kelompok_md_harga_detail',['id_kel'=>$id]);
			$details          = $this->input->post('details');
			foreach ($details as $key => $val) {
				$dt_detail[] = ['id_kel'=> $id,
								'id_item'   => $val['id_item'],
								'keterangan'   => $val['keterangan'],
								'harga_jual'     => $val['harga_baru'],
								'updated_at' => $waktu,
								'updated_by' => $login_id,
						 	 ];	
			}
			$this->db->insert_batch('ms_kelompok_md_harga_detail',$dt_detail);
		if ($this->db->trans_status() === FALSE)
      	{
			$this->db->trans_rollback();
			$rsp = ['status'=> 'error',
					'pesan'=> ' Something went wrong'
				   ];
      	}
      	else
      	{
        	$this->db->trans_commit();
        	$rsp = ['status'=> 'sukses',
					'link'=>base_url('master/kelompok_md')
				   ];
        	$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
      	}
      	echo json_encode($rsp);
	}
	// public function update()
	// {		
	// 	$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
	// 	$login_id		= $this->session->userdata('id_user');
	// 	$tabel			= $this->tables;
	// 	$pk 				= $this->pk;		
	// 	$_SESSION['pesan'] 	= "Data has been updated successfully";
	// 	$_SESSION['tipe'] 	= "success";
	// 	echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kelompok_md'>";		
	// }
}