<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nrfs_rfs extends CI_Controller {

	var $tables =   "tr_scan_ubah_dealer";	
	var $folder =   "dealer";
	var $page   =		"nrfs_rfs";
	var $pk     =   "id_scan_ubah_dealer";
	var $title  =   "Ubah Status Unit";

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
		$this->load->library('cart');
		$this->load->library('cart');
		$this->load->library("udp_cart");//load library 
		$this->part_add    = new Udp_cart("part_add");

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
		$data['isi']          = "nrfs_rfs";		
		$data['title']        = $this->title;															
		$data['set']          = "view";		
		$id_dealer            = $this->m_admin->cari_dealer();
		$data['dt_scan_ubah'] = $this->db->query("SELECT * FROM tr_scan_ubah_dealer 
					WHERE id_dealer = '$id_dealer' ORDER BY id_scan_ubah_dealer DESC");						
		$this->cart->destroy();
		$this->template($data);	
	}

	public function add()
	{				
			$data['isi']   = "nrfs_rfs";		
			if (isset($_GET['mode'])) {
				$data['mode']  = $this->input->get('mode');														
				// $this->cart->destroy();		
				$title = 'RFS ke NRFS';
			}else{
				$title = 'NRFS ke RFS';	

				
			}									

			$data['title'] = $this->title.' '.$title;			
			$data['set']   = "add_nrfs_rfs";		
		$this->template($data);	
	}
	public function add_rfs_nrfs()
	{
		$data['set']   = "rfs_nrfs";	
		$data['isi']   = "nrfs_rfs";		
		$data['mode']  = 'insert';
		$title         = 'RFS ke NRFS';
		$data['title'] = $this->title.' '.$title;		
		$this->template($data);	
	}
	public function cari_id(){
		$id = $this->m_admin->cari_kode("tr_scan_ubah","id_scan_ubah");
		echo $id;
	}
	public function cari_id_real(){
		$id = $this->m_admin->cari_id("tr_scan_ubah_dealer","id_scan_ubah_dealer");
		return $id;
	}
	public function t_rfs(){
		$id = $this->input->post('id_ubah');		
		$data['dt_rfs'] = $this->db->query("SELECT * FROM tr_scan_ubah_detail WHERE id_scan_ubah = '$id'");
		$data['jenis']  = 'RFS';		
		$this->load->view('h1/t_rfs_ubah',$data);
	}
	public function save_nosin(){
		$no_mesin = $this->input->post('rfs_text');
		$so = $this->db->query("SELECT *,tr_scan_barcode.no_mesin FROM tr_scan_barcode 
							inner join ms_tipe_kendaraan on ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
							inner join ms_warna on ms_warna.id_warna = tr_scan_barcode.warna
							WHERE tr_scan_barcode.no_mesin = '$no_mesin'");
		if ($so->num_rows() >0) {
			$so = $so->row();
			// $data_produk= array('id' => $this->cari_id_real(),
			$data_produk= array('id' => rand(1,999),
								'name'          => $no_mesin,
								'no_rangka'     => $so->no_rangka,
								'no_mesin'      => $so->no_mesin,
								'tipe'          => $so->tipe_ahm,
								'warna'         => $so->warna,
								'id_karyawan'   => '',
								'nama_karyawan' => '',
								'price'         => "122",							 
								'qty'           => "2333"
							);
		$this->cart->insert($data_produk);		
		echo "ok";
		}else{
			echo "No Mesin Tidak Ditemukan";
		}
		
	}				
	function hapus() 
	{
		$rowid = $this->input->post('rowid');
		if ($rowid=="all")
			{
				$this->cart->destroy();
			}
		else
			{
				$data = array('rowid' => $rowid,
			  				  'qty' =>0);
				$this->cart->update($data);
			}
		echo "ok";
	}
	public function save(){	
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		$tabel    = $this->tables;
		$pk       = $this->pk;		
		$id_ubah  = $this->cari_id_real();
		$cart = $this->cart->contents();
		if (isset($_POST['mode'])) {
			if ($_POST['mode']=='rfs_nrfs') {
			$status_ubah = 'RFS ke NRFS';
			}
		}else{
			$status_ubah = 'NRFS ke RFS';

		}
		if (count($cart)==0) {
			$_SESSION['pesan'] 	= "Detail Perubahan Status Unit Dari $status_ubah Belum Ditentukan !";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
			exit;
		}
		
		$data_nosin = array('tgl_ubah' => $tgl,
							'id_dealer'           => $this->m_admin->cari_dealer(),
							'id_scan_ubah_dealer' => $id_ubah,
							'status_ubah'         => $status_ubah,
							'created_by'          => $login_id,
							'keterangan'          => $this->input->post("keterangan"),
							'created_at'          => $waktu);
		$this->m_admin->insert($tabel,$data_nosin);
				
		
		if ($cart = $this->cart->contents())
			{
				if (isset($_POST['mode'])) {
					if ($_POST['mode']=='rfs_nrfs') {
						foreach ($cart as $item)
						{
							$no_mesin         = $item['name'];
							$data['no_mesin'] = $item['name'];
							$data['jenis_pu'] = "nrfs";
							$data_detail = array('id_scan_ubah_dealer' =>$id_ubah,
											'no_mesin' => $item['name']);			
							$this->m_admin->insert("tr_scan_ubah_dealer_detail",$data_detail);

							$r = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$no_mesin)->row();
							$this->m_admin->update_stock_dealer($r->id_item,"rfs","-",1);
							$this->m_admin->update_stock_dealer($r->id_item,"nrfs","+",1);
							$this->m_admin->update("tr_penerimaan_unit_dealer_detail",$data,"no_mesin",$no_mesin);
						}
					}
				}else{
					foreach ($cart as $item)
					{
						$no_mesin         = $item['name'];
						$data['no_mesin'] = $item['name'];
						$cek_dokumen = $this->db->query("SELECT * FROM tr_dokumen_nrfs WHERE no_mesin='$no_mesin' AND status='ready_to_repair' ORDER BY created_at DESC LIMIT 1");
						if ($cek_dokumen->num_rows()>0) {
							$dok = $cek_dokumen->row();
							$upd_dokumen[] = ['dokumen_nrfs_id'=>$dok->dokumen_nrfs_id,
											  'id_mekanik'=>$item['id_karyawan_dealer'],
											  'status'=>'resolved'
											 ];
						}
						$data['jenis_pu'] = "rfs";
						$data_detail      = array('id_scan_ubah_dealer' =>$id_ubah,
												  'no_mesin'        => $item['name'],
												  'id_mekanik'	=> $item['id_karyawan_dealer']
												);			
						$this->m_admin->insert("tr_scan_ubah_dealer_detail",$data_detail);
						if (isset($upd_dokumen)) {
							$this->db->update_batch('tr_dokumen_nrfs',$upd_dokumen,'dokumen_nrfs_id');
						}
						$r = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$no_mesin)->row();
						$this->m_admin->update_stock_dealer($r->id_item,"rfs","+",1);
						$this->m_admin->update_stock_dealer($r->id_item,"nrfs","-",1);
						$this->m_admin->update("tr_penerimaan_unit_dealer_detail",$data,"no_mesin",$no_mesin);
					}
				}
			}		
		$this->cart->destroy();		
		$_SESSION['pesan'] 	= "Status has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/nrfs_rfs/'>";
	}
	public function detail()
	{				
		$data['isi'] = "rfs_nrfs";		
		$data['set'] = "detail";				
		$id          = $this->input->get('id');	
		$id_dealer   = $this->m_admin->cari_dealer();				
		$data['dt_scan_ubah'] = $this->db->query("SELECT *,tr_scan_ubah_dealer_detail.no_mesin as nomesin FROM tr_scan_ubah_dealer_detail 
			left join tr_scan_ubah_dealer on tr_scan_ubah_dealer.id_scan_ubah_dealer = tr_scan_ubah_dealer_detail.id_scan_ubah_dealer
join tr_scan_barcode on tr_scan_ubah_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
join ms_warna on tr_scan_barcode.warna = ms_warna.id_warna
join ms_tipe_kendaraan on tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
WHERE tr_scan_ubah_dealer_detail.id_scan_ubah_dealer = '$id' ORDER BY tr_scan_ubah_dealer_detail.id_scan_ubah_dealer DESC");	
		$row = $this->db->query("SELECT * FROM tr_scan_ubah_dealer WHERE id_scan_ubah_dealer='$id'");
		if ($row->num_rows()==0)redirect('dealer/nrfs_rfs','refresh');
		$data['title']	= $this->title.' '.$row->row()->status_ubah;
		$data['row'] = $row->row();
		$this->template($data);	
	}

	function getDetail()
	{
		$this->load->view('dealer/t_rfs_nrfs');
	}
	public function save_nrfs(){
		$no_mesin = $this->input->post('no_mesin');
		$so = $this->db->query("SELECT *,tr_scan_barcode.no_mesin FROM tr_scan_barcode 
							inner join ms_tipe_kendaraan on ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
							inner join ms_warna on ms_warna.id_warna = tr_scan_barcode.warna
							WHERE tr_scan_barcode.no_mesin = '$no_mesin'");
		if ($so->num_rows() >0) {
			$so = $so->row();
			// $data_produk= array('id' => $this->cari_id_real(),
			$data_produk= array('id' => rand(1,999),
							 'name' => $no_mesin,
							  'no_rangka' => $so->no_rangka,
							  'no_mesin' => $so->no_mesin,
							  'tipe' => $so->tipe_ahm,
							  'warna' => $so->warna,
							 'price' => "1",							 
							 'qty' => "1"
							);
		$this->cart->insert($data_produk);		
		echo "ok";
		}else{
			echo "No Mesin Tidak Ditemukan";
		}
		
	}
	function addPart()
	{
		$data['no_mesin'] = $this->input->post('no_mesin');
		$data['id_part']  = $this->input->post('id_part');
		$data['qty']      = $this->input->post('qty_part');
		$data['price']    = 1;
		$data['id']       = rand(1,9999);
		if ($this->part_add->insert($data)) {
			echo 'sukses';
		}else{
			echo 'gagal';
		}
	}
	function delPart()
	{
		$rowid=$this->input->post('rowid');
		if($this->part_add->remove_item($rowid)){
			echo 'sukses';
		}else{
			echo 'gagal';
		}
	}
	
	public function save_nrfs_db(){	
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		$tabel    = $this->tables;
		$pk       = $this->pk;		
		$id_ubah  = $this->cari_id_real();
        $id_dealer = $this->m_admin->cari_dealer();
		$data_nosin = array('tgl_ubah' => $tgl,
							'id_dealer'           => $this->m_admin->cari_dealer(),
							'id_scan_ubah_dealer' => $id_ubah,
							'status_ubah'         => 'RFS ke NRFS',
							'created_by'          => $login_id,
							'keterangan'          => $this->input->post("keterangan"),
							'created_at'          => $waktu);
		$this->m_admin->insert($tabel,$data_nosin);
		$cart = $this->cart->contents();
		if (count($cart)==0) {
			$_SESSION['pesan'] 	= "Detail Perubahan Status Unit Dari RFS ke NRFS Belum Ditentukan !";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
			exit;
		}		
		if ($cart = $this->cart->contents())
			{
				$last_dokumen_nrfs_id = $this->m_admin->get_last_dokumen_nrfs_id();
				$ktg_notif = $this->db->get_where('ms_notifikasi_kategori',['kode_notif'=>'ntf_parts_nrfs'])->row();
				foreach ($cart as $item)
				{
					$no_mesin         = $item['name'];
					$data['no_mesin'] = $item['name'];
					$data['jenis_pu'] = "nrfs";
					$data_detail = array('id_scan_ubah_dealer' =>$id_ubah,
									'no_mesin' => $item['name']);			
					$this->m_admin->insert("tr_scan_ubah_dealer_detail",$data_detail);

					$r = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$no_mesin)->row();
					$this->m_admin->update_stock_dealer($r->id_item,"rfs","-",1);
					$this->m_admin->update_stock_dealer($r->id_item,"nrfs","+",1);
					$this->m_admin->update("tr_penerimaan_unit_dealer_detail",$data,"no_mesin",$no_mesin);
					$tipe = $this->db->get_where('ms_tipe_kendaraan',['id_tipe_kendaraan'=>$r->tipe_motor])->row();
							$wrn  = $this->db->get_where('ms_warna',['id_warna'=>$r->warna])->row();
					$dok_add_part ='';
					$last_dokumen_nrfs_id = $this->m_admin->get_last_dokumen_nrfs_id($last_dokumen_nrfs_id);
					if($part = $this->part_add->get_content()){
							$dok_add_part ='';
							$scan_ubah_part = '';
							$notif_parts = array();
							foreach($part as $key => $val){
								$dt_part = $this->db->get_where('ms_part',['id_part'=>$val['id_part']])->row();
								if ($val['no_mesin']==$no_mesin) {
									$dok_add_part[]=['dokumen_nrfs_id'=>$last_dokumen_nrfs_id,
											'id_part'  =>$val['id_part'],
											'nama_part'=>$dt_part->nama_part,
											'qty_part' =>$val['qty'],
											];
									$scan_ubah_part[]=['id_scan_ubah_dealer'=>$id_ubah,
											'no_mesin' =>$no_mesin,
											'id_part'  =>$val['id_part'],
											'qty_part' =>$val['qty']
											];
								}
								$notif_parts[] = $val['id_part'].'('.$val['qty'].')';
							}

							if ($dok_add_part!='') {
								$this->db->insert_batch('tr_dokumen_nrfs_part',$dok_add_part);
							}
							if ($scan_ubah_part!='') {
								$this->db->insert_batch('tr_scan_ubah_dealer_part',$scan_ubah_part);
							}
						}
					$sj = $this->db->get_where('tr_surat_jalan_detail',['no_mesin'=>$no_mesin]);
					$need_parts = $this->input->post('need_parts_'.$no_mesin);
					if (strtolower($need_parts)=='yes') {
						$status_dok='open';
					}else{
						$status_dok='ready_to_repair';
						$ready_repair_at = $waktu;
						$ready_repair_by = $login_id;
					}
					$dokumen=['dokumen_nrfs_id'=> $last_dokumen_nrfs_id,
							'tgl_dokumen'     => date('Y-m-d'),
							'id_dealer'       => $id_dealer,
							'no_shiping_list' => $sj->num_rows()>0?$sj->row()->no_surat_jalan:'',
							'type_code'       => $r->tipe_motor,
							'deskripsi_unit'  => $tipe->tipe_ahm,
							'color_code'      => $wrn->id_warna,
							'deskripsi_warna' => $wrn->warna,
							'no_mesin'        => $no_mesin,
							'no_rangka'       => $r->no_rangka,
							// 'need_parts'      => $dok_add_part!=''?'Yes':'No',
							'need_parts'      => $this->input->post('need_parts_'.$no_mesin),
							'sumber_rfs_nrfs' => 'Dealer',
							'status_nrfs'     => 'NRFS',
							'dokumen_nrfs_from'=>'ubah_status',
							'created_at'      => $waktu,
							'created_by'      => $login_id,
							'ready_repair_at' => isset($ready_repair_at)?$ready_repair_at:null,
							'ready_repair_by' => isset($ready_repair_by)?$ready_repair_by:null,
							'status'		  => $status_dok
						   ];
					$this->db->insert('tr_dokumen_nrfs',$dokumen);	
					if (count($notif_parts)>0) {
						$parts = implode(', ', $notif_parts);
						$pesan = "Telah terjadi perubahan unit RFS ke NRFS dengan detail: <br> 
								  Kode Tipe Unit = $r->tipe_motor <br>
								  Kode Warna = $wrn->id_warna <br>
								  No Mesin = $no_mesin <br>
								  No Rangka = $r->no_rangka <br>
								  Parts = $parts <br>
								  Mohon untuk melakukan pengecekan ketersediaan Parts didalam sistem
								";
						$notifikasi_parts[] = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
								'id_referensi' => $last_dokumen_nrfs_id,
								'judul'        => "Notifikasi Kebutuhan Parts",
								'pesan'        => $pesan,
								'link'         => $ktg_notif->link.'?id='.$last_dokumen_nrfs_id,
								'status'       =>'baru',
								// 'id_dealer'    => $id_dealer,
								'created_at'   => $waktu,
								'created_by'   => $login_id
							 ];
					}
					$this->session->unset_userdata($no_mesin);
				}
			}		
		$this->cart->destroy();		
		$this->part_add->destroy();
		
		$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
		$ktg_notif = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>6])->row();
		$notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
				'id_referensi' => $id_ubah,
				'judul'        => "Perubahan unit RFS ke NRFS",
				'pesan'        => "Telah terjadi perubahan unit RFS ke NRFS, Silahkan Click pada pesan ini untuk melihat detail transaksi.",
				'link'         => $ktg_notif->link.'?id='.$id_ubah,
				'status'       =>'baru',
				'id_dealer'    => $id_dealer,
				'created_at'   => $waktu,
				'created_by'   => $login_id
			 ];
		$this->db->insert('tr_notifikasi',$notif);
		if (isset($notifikasi_parts)) {
			$this->db->insert_batch('tr_notifikasi',$notifikasi_parts);
		}

		$_SESSION['pesan'] 	= "Status has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/nrfs_rfs/'>";
	}

	function setNeedParts()
	{
		$this->session->set_userdata($this->input->post('no_mesin'), $this->input->post('need_parts'));
		echo json_encode('sukses');
	}

	function setMekanik()
	{
		$rowid = $this->input->post('rowid');
		$id_karyawan = $this->input->post('id_karyawan');
		
		$kry = $this->db->query("SELECT ms_karyawan_dealer.* FROM ms_karyawan_dealer WHERE id_karyawan_dealer='$id_karyawan'");
		if ($kry->num_rows()>0){
			$nama_karyawan = $kry->row()->nama_lengkap;
		}else{
			$nama_karyawan = null;
			$id_karyawan = null;
		}
		$data = array('rowid' => $rowid,
			  		  'id_karyawan_dealer' =>$id_karyawan,
			  		  'nama_karyawan' =>$nama_karyawan,
			  		);
		$this->cart->update($data);
		$resp = ['status'=>'sukses'];
		echo json_encode($resp);
	}
}