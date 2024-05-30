<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Monitor_orderin extends CI_Controller {

	

	var $folder =   "h1/report";

	var $page		="monitor_orderin";	

	var $isi		="laporan_1";	

	var $title  =   "Monitoring Order In";



	public function __construct()

	{		

		parent::__construct();

		

		//===== Load Database =====

		$this->load->database();

		$this->load->helper('url');

		//===== Load Model =====

		$this->load->model('m_admin');		

		//===== Load Library =====		

		$this->load->library('pdf');		



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

		$data['isi']    = $this->isi;		

		$data['title']	= $this->title;															

		$data['set']		= "view";

		$this->template($data);		    	    

	}		

	public function	download(){				
		$data['tgl1'] = $tgl1		= $this->input->post('tgl1');
		$data['tgl2'] = $tgl2		= $this->input->post('tgl2');	
		$data['id_dealer'] = $id_dealer		= $this->input->post('id_dealer');		
		


		$selectedOption = $this->input->post('radioGroup');

		$where = '';
		$join = '';
		$group = '';

		if ($selectedOption==='order_in'){

			$select ="";
			$join = "left join tr_order_survey b on a.no_spk = b.no_spk and b.status_survey !='cancel'
					left join ms_finance_company i on i.id_finance_company = b.id_finance_company
					left join tr_hasil_survey m on b.no_order_survey = m.no_order_survey ";

		}else if($selectedOption==='all_spk'){

			$select ="";
			$join = "left join tr_order_survey b on a.no_spk = b.no_spk and b.status_survey !='cancel'
			left join ms_finance_company i on i.id_finance_company = b.id_finance_company
			left join tr_hasil_survey m on b.no_order_survey = m.no_order_survey ";
			$group ='group by a.no_spk';
		}
		
		if($id_dealer != 'all'){
			$where.= " and a.id_dealer = '$id_dealer'";
		}
		
		$tgl2 = date_format(date_add(date_create($tgl2),date_interval_create_from_date_string("1 days")),"Y-m-d");
	
		$sql = $this->db->query("
			select a.no_spk , n.kode_dealer_md, n.nama_dealer, a.created_at as tgl_spk , 
			b.no_order_survey ,
			b.created_at as tgl_order, (case when b.created_at > m.created_at then ADDTIME(m.created_at, \"12:00:00\") else m.created_at end) as tgl_hasil,
			a.no_ktp , a.nama_konsumen , f.pekerjaan , a.alamat , c.kelurahan , d.kecamatan , e.kabupaten , 
			a.id_tipe_kendaraan, a.id_warna , h.tipe_ahm , g.warna ,
			a.harga_on_road , a.jenis_beli , (case when i.finance_company is null then '-' else i.finance_company end) as finance_company , a.uang_muka , ifnull((a.voucher_1 + a.voucher_2),0) as voucer , a.voucher_tambahan_1 ,
			a.voucher_tambahan_2 , diskon , (a.uang_muka - ifnull((a.voucher_1 + a.voucher_2),0)) as dp_stor , 
			k.id_flp_md , l.jabatan , (case when m.status_approval is null then a.status_spk else m.status_approval end) as status, 
			(case when m.status_approval = 'rejected' then m.keterangan else '-' end) alasan,
			SEC_TO_TIME ( TIMESTAMPDIFF(MINUTE,  b.created_at, (case when a.created_at > m.created_at then ADDTIME(m.created_at, \"12:00:00\") else m.created_at end)) )as selisih,
			k.nama_lengkap, a.angsuran, a.tenor,
			(case when SEC_TO_TIME ( TIMESTAMPDIFF(SECOND ,  a.created_at, (case when a.created_at > m.created_at then ADDTIME(m.created_at, \"12:00:00\") else m.created_at end)) ) is null then '00:00:00' else 
			TIME_FORMAT(SEC_TO_TIME ( TIMESTAMPDIFF(SECOND ,  a.created_at, (case when a.created_at > m.created_at then ADDTIME(m.created_at, \"12:00:00\") else m.created_at end)) ),\"%H:%i:%s\") end) as times
			from tr_spk a 
			join ms_kelurahan c on a.id_kelurahan  = c.id_kelurahan 
			join ms_kecamatan d on c.id_kecamatan = d.id_kecamatan 
			join ms_kabupaten e on d.id_kabupaten = e.id_kabupaten 
			join ms_pekerjaan f on f.id_pekerjaan = a.pekerjaan 
			join ms_warna g on g.id_warna = a.id_warna 
			join ms_tipe_kendaraan h on h.id_tipe_kendaraan = a.id_tipe_kendaraan 
			join tr_prospek j on j.id_customer = a.id_customer 
			join ms_karyawan_dealer k on k.id_karyawan_dealer = j.id_karyawan_dealer 
			join ms_jabatan l on l.id_jabatan = k.id_jabatan 
			join ms_dealer n on n.id_dealer = a.id_dealer
			$join
			where date(a.created_at) = '$tgl1' $where
			and (m.status_approval IN ('rejected','approved') or a.jenis_beli ='cash' or a.status_spk in ('canceled','booking','rejected','close')) 
			order by a.created_at asc
			");
			
		// $sql = $this->db->query("
		// 	select a.no_spk , n.kode_dealer_md, n.nama_dealer, a.created_at as tgl_spk , b.no_order_survey ,b.created_at as tgl_order, (case when b.created_at > m.created_at then ADDTIME(m.created_at, \"12:00:00\") else m.created_at end) as tgl_hasil,
		// 	a.no_ktp , a.nama_konsumen , f.pekerjaan , a.alamat , c.kelurahan , d.kecamatan , e.kabupaten , 
		// 	a.id_tipe_kendaraan, a.id_warna , h.tipe_ahm , g.warna ,
		// 	a.harga_on_road , a.jenis_beli , (case when i.finance_company is null then '-' else i.finance_company end) as finance_company , a.uang_muka , ifnull((a.voucher_1 + a.voucher_2),0) as voucer , a.voucher_tambahan_1 ,
		// 	a.voucher_tambahan_2 , diskon , (a.uang_muka - ifnull((a.voucher_1 + a.voucher_2),0)) as dp_stor , 
		// 	k.id_flp_md , l.jabatan , (case when m.status_approval is null then a.status_spk else m.status_approval end) as status, 
		// 	(case when m.status_approval = 'rejected' then m.keterangan else '-' end) alasan,
		// 	SEC_TO_TIME ( TIMESTAMPDIFF(MINUTE,  b.created_at, (case when a.created_at > m.created_at then ADDTIME(m.created_at, \"12:00:00\") else m.created_at end)) )as selisih , k.nama_lengkap, a.angsuran, a.tenor,
		// 	(case when SEC_TO_TIME ( TIMESTAMPDIFF(SECOND ,  a.created_at, (case when a.created_at > m.created_at then ADDTIME(m.created_at, \"12:00:00\") else m.created_at end)) ) is null then '00:00:00' else TIME_FORMAT(SEC_TO_TIME ( TIMESTAMPDIFF(SECOND ,  a.created_at, (case when a.created_at > m.created_at then ADDTIME(m.created_at, \"12:00:00\") else m.created_at end)) ),\"%H:%i:%s\") end) as times
		// 	from tr_spk a 
		// 	join ms_kelurahan c on a.id_kelurahan  = c.id_kelurahan 
		// 	join ms_kecamatan d on c.id_kecamatan = d.id_kecamatan 
		// 	join ms_kabupaten e on d.id_kabupaten = e.id_kabupaten 
		// 	join ms_pekerjaan f on f.id_pekerjaan = a.pekerjaan 
		// 	join ms_warna g on g.id_warna = a.id_warna 
		// 	join ms_tipe_kendaraan h on h.id_tipe_kendaraan = a.id_tipe_kendaraan 
		// 	join tr_prospek j on j.id_customer = a.id_customer 
		// 	join ms_karyawan_dealer k on k.id_karyawan_dealer = j.id_karyawan_dealer 
		// 	join ms_jabatan l on l.id_jabatan = k.id_jabatan 
		// 	join ms_dealer n on n.id_dealer = a.id_dealer
			
		// 	left join tr_order_survey b on a.no_spk = b.no_spk and b.status_survey !='cancel'
		// 	left join ms_finance_company i on i.id_finance_company = b.id_finance_company
		// 	left join tr_hasil_survey m on b.no_order_survey = m.no_order_survey 
			
		// 	where date(a.created_at) = '$tgl1' $where
		// 	and (m.status_approval IN ('rejected','approved') or a.jenis_beli ='cash' or a.status_spk in ('canceled','booking','rejected','close')) 
		// 	order by a.created_at asc
		// 	, b.created_at asc
		// ");


			if ($selectedOption==='order_in'){
				
				$data['sql'] = $sql;	
				if($_POST['process']=='excel'){
					$this->load->view("h1/report/template/temp_monitor_orderin",$data);
				}else if($_POST['process']=='csv'){
					$this->load->view("h1/report/template/temp_monitor_orderin_csv",$data);
				}	

			}else if($selectedOption==='all_spk'){
				$data['sql'] = $sql;	
				if($_POST['process']=='excel'){
					$this->load->view("h1/report/template/temp_monitor_orderin_csv",$data);
				}else if($_POST['process']=='csv'){
					$this->load->view("h1/report/template/temp_monitor_orderin_all_spk_csv",$data);
				}	
			}


	}	

}