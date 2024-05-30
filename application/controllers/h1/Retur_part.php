<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur_part extends CI_Controller {

    var $tables_hader =   "tr_retur_part";	
    var $tables_detail =   "tr_retur_part_detail";	
		var $folder =   "h1";
		var $page		=		"retur_part";
    var $pk     =   "no_do";
    var $title  =   "Retur Part";

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
		$this->template($data);			
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";
		$this->template($data);			
	}

	public function detail($no)
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";
		$data['retur'] = $this->db->query("SELECT * from tr_retur_part where no_retur_part ='$no'")->row();
		$data['detail_retur_part']=$this->db->query("select * from tr_retur_part_detail 
													left join ms_part on tr_retur_part_detail.id_part = ms_part.id_part
													left join ms_alasan_retur_part on tr_retur_part_detail.id_alasan_retur_part = ms_alasan_retur_part.id_alasan_retur_part
													where tr_retur_part_detail.id_retur_part='$no'
	    ");
		$this->template($data);			
	}

	public function approve($no)
	{				
		/*$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "approve";

		$data['retur'] = $this->db->query("SELECT * from tr_retur_part where no_retur_part ='$no'")->row();
		$data['detail_retur_part']=$this->db->query("select * from tr_retur_part_detail 
													left join ms_part on tr_retur_part_detail.id_part = ms_part.id_part
													left join ms_alasan_retur_part on tr_retur_part_detail.id_alasan_retur_part = ms_alasan_retur_part.id_alasan_retur_part
													where tr_retur_part_detail.id_retur_part='$no'
	    ");
		$this->template($data);		*/
		$this->db->query("UPDATE tr_retur_part set status='approved'where no_retur_part='$no'");
		$_SESSION['pesan'] 	= "Data has been approved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/retur_part'>";
	}

	public function getDetail()
	{
		$login_id		= $this->session->userdata('id_user');
		$data['detail_retur_part']=$this->db->query("select * from tr_retur_part_detail 
													left join ms_part on tr_retur_part_detail.id_part = ms_part.id_part
													left join ms_alasan_retur_part on tr_retur_part_detail.id_alasan_retur_part = ms_alasan_retur_part.id_alasan_retur_part
													where tr_retur_part_detail.created_by='$login_id' and tr_retur_part_detail.status='new'
	    ");
		$this->load->view("h1/t_detail_retur_part",$data);
	}

	public function addDetail(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		
		$id_part = $this->input->post('id_part');
		$qty_retur = $this->input->post('qty_retur');
		$id_alasan_retur_part = $this->input->post('id_alasan_retur_part');
		$cek_detail=$this->db->query("SELECT * FROM tr_retur_part_detail WHERE created_by='$login_id' AND id_part='$id_part' and status='new' ");



		if ($cek_detail->num_rows() ==0 ) {
			$data_insert = array('id_part' => $id_part,
						'created_by' => $login_id,
						'qty_retur' => $qty_retur,
						'id_alasan_retur_part' => $id_alasan_retur_part,
						'created_at' => $waktu,
						'status' => 'new',
				);
		$this->m_admin->insert($this->tables_detail, $data_insert);
		}
		else{
			$row = $cek_detail->row();
			$data_upd = array(
				'id_part' => $id_part,
						'created_by' => $login_id,
						'qty_retur' => $qty_retur,
						'id_alasan_retur_part' => $id_alasan_retur_part,
						'status' => 'new',
				);
		$this->m_admin->update($this->tables_detail, $data_upd,"id_retur_part_detail", $row->id_retur_part_detail);
		}
		$data['detail_retur_part']=$this->db->query("select * from tr_retur_part_detail 
													left join ms_part on tr_retur_part_detail.id_part = ms_part.id_part
													left join ms_alasan_retur_part on tr_retur_part_detail.id_alasan_retur_part = ms_alasan_retur_part.id_alasan_retur_part
													where tr_retur_part_detail.created_by='$login_id' and tr_retur_part_detail.status='new'

	    ");
		$this->load->view("h1/t_detail_retur_part",$data);
	}	


	public function deleteDetail()
	{
		$login_id		= $this->session->userdata('id_user');		
		$id_retur_part_detail = $this->input->post('id_retur_part_detail');

		$this->m_admin->delete($this->tables_detail, "id_retur_part_detail", $id_retur_part_detail);
		$data['detail_retur_part']=$this->db->query("select * from tr_retur_part_detail 
													left join ms_part on tr_retur_part_detail.id_part = ms_part.id_part
													left join ms_alasan_retur_part on tr_retur_part_detail.id_alasan_retur_part = ms_alasan_retur_part.id_alasan_retur_part
													where tr_retur_part_detail.created_by='$login_id' and tr_retur_part_detail.status='new'
	    ");
		$this->load->view("h1/t_detail_retur_part",$data);
	}	

	public function jenisRetur()
	{
		$jenis_retur =$this->input->post('jenis_retur');
		if ($jenis_retur=="po_checker") {
			$no_sj = $this->db->query("SELECT * from tr_po_checker where status='approved'");
			if ($no_sj->num_rows() >0) {
						echo "<option>-- Choose --</option>";
				foreach ($no_sj->result() as $sj) {
					if (!!$sj->no_sj) {
						echo "<option value='$sj->no_sj'>$sj->no_sj</option>";
					}
				}
			}
			echo "|";
			$no_po = $this->db->query("SELECT GROUP_CONCAT(no_po) as no_po from tr_po_checker where status='approved'")->row()->no_po;
			$sj = $this->input->post('no_sj');
			$part = $this->db->query("SELECT tr_po_checker_detail.*, ms_part.nama_part from tr_po_checker_detail
				left join tr_po_checker on tr_po_checker_detail.no_po=tr_po_checker.no_po
			 left join ms_part on tr_po_checker_detail.id_part = ms_part.id_part where tr_po_checker_detail.no_po in($no_po) AND no_sj='$sj' group by id_part");
			echo "<option>-- Choose --</option>";
			foreach ($part->result() as $pt) {
				echo"<option value='$pt->id_part' nama_part='$pt->nama_part'>$pt->id_part</option>";
			}
		

		}elseif ($jenis_retur=="po_aksesoris") {
			$no_sj = $this->db->query("SELECT * from tr_po_checker where status='approved'");
			if ($no_sj->num_rows() >0) {
						echo "<option>-- Choose --</option>";
				foreach ($no_sj->result() as $sj) {
					if (!!$sj->no_sj) {
						echo "<option value='$sj->no_sj'>$sj->no_sj</option>";
					}
				}
			}
			echo "|";
			// $part = $this->db->query("SELECT * from tr_po_aksesoris_detail 
			// 							left join ms_part on tr_po_aksesoris_detail.id_part=ms_part.id_part
			// 							left join tr_po_aksesoris on tr_po_aksesoris_detail.no_po_aksesoris = tr_po_aksesoris.no_po_aksesoris
			// 							where tr_po_aksesoris.status_po = 'terpenuhi' GROUP BY tr_po_aksesoris_detail.id_part
			// 						");
			$part = $this->db->query("SELECT * FROM tr_pemenuhan_po_detail
								left join ms_part ON tr_pemenuhan_po_detail.id_part = ms_part.id_part
								left join tr_pemenuhan_po ON tr_pemenuhan_po_detail.no_pemenuhan_po = tr_pemenuhan_po.no_pemenuhan_po
								group by tr_pemenuhan_po_detail.id_part");
			echo "<option>-- Choose --</option>";
			foreach ($part->result() as $pt) {
				echo"<option value='$pt->id_part' nama_part='$pt->nama_part'>$pt->id_part</option>";
			}
		}
	}

	public function save()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		
		$jenis_retur = $this->input->post('jenis_retur');
		$cek_header  =$this->db->query("SELECT * from tr_retur_part order by no_retur_part desc limit 0,1");
		if ($cek_header->num_rows()==0) {
			$no_retur_part="0001";
		}else{
			$row = $cek_header->row()->no_retur_part;
			$no_retur_part = sprintf("%04d", $row+1);
		}
		$data_insert = array('no_retur_part' => $no_retur_part,
							 'jenis_retur' =>$jenis_retur,
							 'no_po' => '',
							 'no_sj' => $this->input->post('no_sj')	,
							 'status' => 'input',
							 'created_at' => $waktu,
							 'created_by' => $login_id,
						);
		

		$id=$this->db->query("select GROUP_CONCAT(id_retur_part_detail) as id from tr_retur_part_detail left join ms_part on tr_retur_part_detail.id_part = ms_part.id_part left join ms_alasan_retur_part on tr_retur_part_detail.id_alasan_retur_part = ms_alasan_retur_part.id_alasan_retur_part where tr_retur_part_detail.created_by='$login_id' and tr_retur_part_detail.status='new'")->row()->id;

			$this->m_admin->insert($this->tables_hader, $data_insert);
			$this->db->query("UPDATE tr_retur_part_detail set id_retur_part='$no_retur_part',status='input' where id_retur_part_detail in ($id)");

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/retur_part'>";
	}
}