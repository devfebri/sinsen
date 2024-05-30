<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Terima_bpkb extends CI_Controller {

    var $tables =   "tr_kirim_bpkb";	
		var $folder =   "h1";
		var $page		=		"terima_bpkb";
    var $pk     =   "no_kirim_bpkb";
    var $title  =   "Terima bpkb dari Biro Jasa";

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
		$this->db_crm = $this->load->database('db_crm', true);
		$this->load->model('mokita_model');
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
		$data['set']		= "view_new";				
		//$data['dt_bpkb']	= $this->db->query("SELECT * FROM tr_kirim_bpkb ORDER BY no_kirim_bpkb DESC limit 10");
		//$this->sync();
		$this->template($data);			
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");
		$this->template($data);			
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";		
		$id = $this->input->get('id');
		$data['sql'] = $this->db->query("SELECT * FROM tr_kirim_bpkb_detail INNER JOIN tr_entry_stnk ON tr_kirim_bpkb_detail.no_mesin = tr_entry_stnk.no_mesin
			inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
        inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
        inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
		WHERE tr_kirim_bpkb_detail.no_kirim_bpkb = '$id'");
		$this->template($data);			
	}
	public function konfirm()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "konfirm";		
		$id = $this->input->get('id');
		$data['no_kirim_bpkb'] = $id;
		$data['sql'] = $this->db->query("SELECT * FROM tr_kirim_bpkb_detail INNER JOIN tr_entry_stnk ON tr_kirim_bpkb_detail.no_mesin = tr_entry_stnk.no_mesin
			inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
        inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
        inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
		WHERE tr_kirim_bpkb_detail.no_kirim_bpkb = '$id'");
		$this->template($data);			
	}		

	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');								
		$no_kirim_bpkb 					= $this->input->post('no_kirim_bpkb');		
		$da['updated_at'] 			= $waktu;		
		$da['updated_by'] 			= $login_id;		
	
		$jum 										= $this->input->post("jum");		
		for ($i=1; $i <= $jum; $i++) { 
			if(isset($_POST["cek_bpkb_".$i])){
				$nosin 								= trim($_POST["no_mesin_".$i]);
				$this->db->query("UPDATE tr_kirim_bpkb_detail SET konfirm = 'ya' WHERE no_mesin = '$nosin'");										
				$amb = $this->m_admin->getByID('tr_entry_stnk','no_mesin',$nosin)->row();
				$amc = $this->m_admin->getByID('tr_pengajuan_bbn_detail','no_mesin',$nosin)->row();
				$cek = $this->db->query("SELECT no_mesin FROM tr_terima_bj WHERE no_mesin = '$nosin'");
				if($cek->num_rows() == 1){
					$ds['no_kirim_bpkb'] 	= $no_kirim_bpkb;
					$ds['no_bpkb'] 				= $amb->no_bpkb;
					$ds['tgl_bpkb'] 			= $amb->tgl_bpkb;
					$ds['tgl_terima_bpkb']= $tgl;
					$ds['status_bj'] 			= "input";
					$ds['updated_by']			= $login_id;
					$ds['updated_at']			= $waktu;
					$this->m_admin->update('tr_terima_bj',$ds,'no_mesin',$nosin);
				}elseif($cek->num_rows() == 0){
					$ds['no_bastd'] 			= $amc->no_bastd;
					$ds['no_kirim_bpkb'] 	= $no_kirim_bpkb;
					$ds['no_bpkb'] 				= $amb->no_bpkb;
					$ds['no_mesin']				= $amb->no_mesin;
					$ds['no_rangka']			= $amb->no_rangka;
					$ds['nama_konsumen'] 	= $amb->nama_konsumen;
					$ds['id_tipe_kendaraan'] 	= $amb->id_tipe_kendaraan;
					$ds['id_warna'] 			= $amb->id_warna;
					$ds['notice_pajak'] 	= $amb->notice_pajak;
					$ds['status_bj'] 			= "input";
					$ds['tgl_bpkb'] 			= $amb->tgl_bpkb;
					$ds['tgl_terima_bpkb']= $tgl;
					$ds['created_by']			= $login_id;
					$ds['created_at']			= $waktu;
					$this->m_admin->insert('tr_terima_bj',$ds);
				}
				$get_leads = $this->mokita_model->cek_sales_order(['no_mesin' => $nosin]);
				if ($get_leads) {
					$get_leads->no_polisi = $amb->no_pol;
					$get_leads->no_mesin = $nosin;
					$kirim_ce_apps[]=$get_leads;
				}
			}			
		}

		if (isset($kirim_ce_apps)) {
			foreach ($kirim_ce_apps as $key => $spk) {
				$last_status_ce_apps = $this->mokita_model->last_tracking($spk->no_spk);
				$array_post = [
					'AppsOrderNumber'   => '',
					'DmsOrderNumber'    => '',
					'CustomerPhoneNumber' => $spk->no_hp,
					'CreditStatus' => $last_status_ce_apps ? $last_status_ce_apps->CreditStatus : '',
					'IndentStatus' => $last_status_ce_apps ? $last_status_ce_apps->IndentStatus : '',
					'DeliveryStatus' => $last_status_ce_apps ? $last_status_ce_apps->DeliveryStatus : '',
					'EstimatedDeliveryDate' => $spk->tgl_pengiriman,
					'EngineNumber' => $spk->no_mesin,
					'StnkStatus' => $last_status_ce_apps ? $last_status_ce_apps->StnkStatus : '',
					'BpkbStatus' =>'BPKB sudah selesai',
					'VehicleNumber' => $spk->no_polisi,
				];
				if ($spk->input_from=='sinsengo') {
					$get_leads = $this->db_crm->get_where("leads", ['leads_id' => $spk->leads_id])->row();
					if ($get_leads!=null) {
						$array_post['AppsOrderNumber']   = $get_leads->sourceRefID;
						$array_post['DmsOrderNumber']    = $get_leads->batchID;
						$this->load->library("mokita");
						$result = $this->mokita->h1_credit_approval_indent_delivery_stnk_bpkb($array_post);
					}
				}
				$this->mokita_model->set_tracking($spk->no_spk, $array_post);
			}
		}
			
		$this->m_admin->update("tr_kirim_bpkb",$da,"no_kirim_bpkb",$no_kirim_bpkb);								
		
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' con\tent='0; url=".base_url()."h1/terima_bpkb'>";
	}	
	public function sync(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');								
		$cek = $this->db->query("SELECT no_mesin,no_kirim_bpkb FROM tr_kirim_bpkb_detail WHERE no_mesin NOT IN (SELECT no_mesin FROM tr_terima_bj) and konfirm ='ya'");
		foreach ($cek->result() as $isi) {
			$nosin = $isi->no_mesin;
			$amb = $this->m_admin->getByID('tr_entry_stnk','no_mesin',$nosin)->row();
			$amc = $this->m_admin->getByID('tr_pengajuan_bbn_detail','no_mesin',$nosin)->row();
			$cek = $this->db->query("SELECT no_mesin FROM tr_terima_bj WHERE no_mesin = '$nosin'");
			if($cek->num_rows() > 0){
				$ds['no_kirim_bpkb'] 	= $isi->no_kirim_bpkb;
				$ds['no_bpkb'] 				= $amb->no_bpkb;
				$ds['tgl_bpkb'] 			= $amb->tgl_bpkb;
				$ds['tgl_terima_bpkb']= $tgl;
				$ds['status_bj'] 			= "input";
				$ds['updated_by']			= $login_id;
				$ds['updated_at']			= $waktu;
				$this->m_admin->update('tr_terima_bj',$ds,'no_mesin',$nosin);
			}else{
				$ds['no_bastd'] 			= $amc->no_bastd;
				$ds['no_kirim_bpkb'] 	= $isi->no_kirim_bpkb;
				$ds['no_bpkb'] 				= $amb->no_bpkb;
				$ds['no_mesin']				= $amb->no_mesin;
				$ds['no_rangka']			= $amb->no_rangka;
				$ds['nama_konsumen'] 	= $amb->nama_konsumen;
				$ds['id_tipe_kendaraan'] 	= $amb->id_tipe_kendaraan;
				$ds['id_warna'] 			= $amb->id_warna;
				$ds['notice_pajak'] 	= $amb->notice_pajak;
				$ds['status_bj'] 			= "input";
				$ds['tgl_bpkb'] 			= $amb->tgl_bpkb;
				$ds['tgl_terima_bpkb']= $tgl;
				$ds['created_by']			= $login_id;
				$ds['created_at']			= $waktu;
				$this->m_admin->insert('tr_terima_bj',$ds);
			}
		}
	}

    public function getAllData()
    {
        $search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
		$limit = $_POST['length']; // Ambil data limit per page
		$start = $_POST['start']; // Ambil data start
		/*
		$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
		$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
		$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"
		*/
			$id_menu = $this->m_admin->getMenu($this->page);

			$cari = '';
		if ($search != '') {
			$cari = " 
				and (a.no_kirim_bpkb LIKE '%$search%' 
					OR a.status_bpkb LIKE '%$search%'
					OR a.tgl_kirim_bpkb LIKE '%$search%')
			";
		}

        $list_data = $this->db->query("			
			select a.no_kirim_bpkb, a.tgl_kirim_bpkb, a.status_bpkb, a.created_at, a.updated_at, (
				select count(b.no_bpkb) as jumlah from tr_kirim_bpkb_detail b where b.no_kirim_bpkb = a.no_kirim_bpkb
			) jumlah
			from tr_kirim_bpkb a
			where 1=1 $cari
			order by no_kirim_bpkb DESC 
			LIMIT $start,$limit
        ");

        $data = array();

        foreach($list_data->result() as $row)
        {
			$no_kirim_bpkb = "<a href='h1/terima_bpkb/detail?id=$row->no_kirim_bpkb'>$row->no_kirim_bpkb</a>";
			$cek = $this->db->query("
				select a.no_kirim_bpkb, a.no_kirim_bpkb, a.no_mesin, a.konfirm, b.tgl_mohon_samsat
				from tr_kirim_bpkb_detail a 
				join tr_pengajuan_bbn_detail b on a.no_mesin = b.no_mesin 
				where a.no_kirim_bpkb ='$row->no_kirim_bpkb'
				group by a.konfirm, b.tgl_mohon_samsat 
				order by tgl_mohon_samsat ASC 
            ");
		
            $x=0;
            $tomb=''; $current =''; $end ='';
            foreach ($cek->result() as $isi) {
				if($isi->konfirm != 'ya'){
					$x++;
				}		
				if($current ==''){
					$current = $isi->tgl_mohon_samsat;
					$end = $isi->tgl_mohon_samsat;
				}else{
					if($current != $isi->tgl_mohon_samsat and $current!=''){
						$end = $isi->tgl_mohon_samsat;
					}
				}
            }

            $tgl_awal = $current;
            $tgl_akhir = $end;
            if ($x>0) {
              $tomb = "<a href='h1/terima_bpkb/konfirm?id=$row->no_kirim_bpkb' class='btn btn-primary btn-flat btn-xs'>Konfirmasi</a>";
            }else{
				if($row->jumlah>0 && $row->status_bpkb == 'input'){
					$tomb='';
					$update_status = $this->db->query("update tr_kirim_bpkb set status_bpkb = 'close' where no_kirim_bpkb = '$row->no_kirim_bpkb'");
				}else if($row->jumlah == 0 && $row->status_bpkb == 'input'){
					$update_status = $this->db->query("update tr_kirim_bpkb set status_bpkb = 'cancel' where no_kirim_bpkb = '$row->no_kirim_bpkb'");
				}
            }
			
			if($row->status_bpkb =='input'){
				$status='<span class="label label-default">'.$row->status_bpkb.'</span>';
			}else if($row->status_bpkb =='close'){
				$status='<span class="label label-success">'.$row->status_bpkb.'</span>';
			}else if($row->status_bpkb =='cancel'){
				$status='<span class="label label-warning">'.$row->status_bpkb.'</span>';
			}

            $data[]= array(
            	'',
                $no_kirim_bpkb,
                $row->tgl_kirim_bpkb,
                $tgl_awal .' s/d '. $tgl_akhir,
                $row->jumlah,
				$status,
				$tomb
            );     
        }
        $get_total = $this->db->query("
			select a.no_kirim_bpkb, a.tgl_kirim_bpkb, a.created_at, a.updated_at
			from tr_kirim_bpkb a
			where 1=1 $cari
			order by no_kirim_bpkb DESC 
		");

        $total_data = $get_total->num_rows();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_data,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
}