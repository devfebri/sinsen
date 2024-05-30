<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lap_pembayaran_bbn_biro extends CI_Controller {
	var $folder 	=   "h1/laporan";
	var $page		=	"laporan_pembayaran_bbn_biro";
	var $title  	=   "Laporan Pembayaran BBN Biro Jasa";

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
	}

	protected function template($data)
	{
		$name = $this->session->userdata('nama');

		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{
			$data['id_menu'] = $this->m_admin->getMenu('lap_pembayaran_bbn_biro');
			$data['group'] 	= $this->session->userdata("group");
			$this->load->view('template/header',$data);
			$this->load->view('template/aside');			
			$this->load->view($this->folder."/".$this->page);		
			$this->load->view('template/footer');
		}
	}

	public function index()
	{
		$tgl1 = $this->input->get('tanggal1');

		if (isset($_GET['cetak'])) {
			if ($_GET['cetak'] == 'cetak') {
				
				ini_set('memory_limit', '-1');
				ini_set('max_execution_time', 900);

				$mpdf                           = $this->pdf->load();
				$mpdf->allow_charset_conversion =true;  // Set by default to TRUE
				$mpdf->charset_in               ='UTF-8';
				$mpdf->autoLangToFont           = true;

				$data['set']                   	= 'cetak';            
				$data['tanggal1']              	= $this->input->get('tanggal1');  
				$data['tanggal2']              	= $this->input->get('tanggal2');    
				$data['date_create']			= get_waktu();

				$where ='';
				if($this->input->get('tanggal2')!=''){
					$tgl2 =$this->input->get('tanggal2');
					$where.="and c.tgl_bastd = '$tgl2'";
				}

				$data['query'] = $this->db->query("
					select 'lap_pembayarran_bbn' as menu, a.tgl_mohon_samsat , d.nama_dealer, a.nama_konsumen , a.id_tipe_kendaraan , a.id_warna , a.no_mesin , a.no_rangka , c.tgl_bastd , c.no_bastd , x.tgl_transfer , a.biaya_bbn , a.biaya_bbn_md_bj , 667500 as biaya_adm, (a.biaya_bbn_md_bj+667500) as total_bbn_md_birojasa, z.tgl_bayar, z.no_entry, y.tgl_bayar as tgl_bayar_adm, y.no_entry as no_entry_adm 
					from tr_pengajuan_bbn_detail a
					join tr_faktur_stnk_detail b on a.no_mesin = b.no_mesin 
					join tr_faktur_stnk c on b.no_bastd  = c.no_bastd and c.status_faktur !='rejected'
					join ms_dealer d on c.id_dealer = d.id_dealer 
					left join (
						select a.referensi , c.tgl_mohon_samsat , c.id_adm_bbn , sum(a.nominal) , GROUP_CONCAT(b.id_voucher_bank) as no_entry, 
						GROUP_CONCAT( (case when d.tgl_transfer <> '' then d.tgl_transfer else e.tgl_bg end) ) as tgl_bayar
						from tr_voucher_bank_detail a
						join tr_voucher_bank b on a.id_voucher_bank = b.id_voucher_bank 
						join tr_adm_bbn c on a.referensi  = c.id_adm_bbn 
						left join tr_voucher_bank_transfer d on d.id_voucher_bank = b.id_voucher_bank
						left join tr_voucher_bank_bg e on e.id_voucher_bank = b.id_voucher_bank
						where referensi in (
							select id_adm_bbn from tr_adm_bbn tab where tgl_mohon_samsat ='$tgl1'
						) and b.status !='batal'
						group by a.referensi , c.tgl_mohon_samsat , c.id_adm_bbn 
					)z on a.tgl_mohon_samsat  = z.tgl_mohon_samsat
					left join (
						select f.id_penerimaan_bank , g.tgl_transfer , c.no_bastd , d.tgl_mohon_samsat , d.no_mesin
						from tr_penerimaan_bank_detail e
						join tr_penerimaan_bank f on e.id_penerimaan_bank = f.id_penerimaan_bank
						join tr_penerimaan_bank_transfer g on g.id_penerimaan_bank = f.id_penerimaan_bank 
						join tr_faktur_stnk c on e.referensi  = c.no_bastd 
						join tr_pengajuan_bbn_detail d on d.no_bastd = c.no_bastd 
						where d.tgl_mohon_samsat ='$tgl1'
					)x on x.no_bastd = c.no_bastd and x.no_mesin = a.no_mesin
					left join (
						select a.referensi , c.tgl_mohon_samsat , c.id_adm_stnk , sum(a.nominal) , GROUP_CONCAT(b.id_voucher_bank) as no_entry, 
						GROUP_CONCAT( (case when d.tgl_transfer <> '' then d.tgl_transfer else e.tgl_bg end) ) as tgl_bayar
						from tr_voucher_bank_detail a
						join tr_voucher_bank b on a.id_voucher_bank = b.id_voucher_bank 
						join tr_adm_stnk c on a.referensi  = c.id_adm_stnk 
						left join tr_voucher_bank_transfer d on d.id_voucher_bank = b.id_voucher_bank
						left join tr_voucher_bank_bg e on e.id_voucher_bank = b.id_voucher_bank
						where referensi in (
							select id_adm_stnk from tr_adm_stnk tab where tgl_mohon_samsat ='$tgl1'
						) and b.status !='batal'
						group by a.referensi , c.tgl_mohon_samsat , c.id_adm_stnk 
					)y on a.tgl_mohon_samsat  = z.tgl_mohon_samsat
					where a.tgl_mohon_samsat ='$tgl1' $where
				");			

				$html = $this->load->view('h1/laporan/laporan_pembayaran_bbn_biro', $data, true);
                
				// render the view into HTML
                $mpdf->AddPage("L","","","","","5","5","15","5","","","","","","","","","","","","A1");
				$mpdf->WriteHTML($html);

				// write the HTML into the mpdf
				$date_buat = date("dmY-hi", strtotime(get_waktu()));
				$output = "rekap_pembayaran_bbn-$date_buat.pdf";
				$mpdf->Output("$output", 'I');
				
			} elseif ($_GET['cetak'] == 'export_excel') {
				ini_set('memory_limit', '-1');
				ini_set('max_execution_time', 900);

				$data['set']                   	= 'export_excel';            
				$data['tanggal1']              	= $this->input->get('tanggal1');      
				$data['tanggal2']              	= $this->input->get('tanggal2');
				$data['date_create']			= get_waktu();

				$where ='';
				if($this->input->get('tanggal2')!=''){
					$tgl2 =$this->input->get('tanggal2');
					$where.="and c.tgl_bastd = '$tgl2'";
				}

				$data['query'] = $this->db->query("
					select 'lap_pembayarran_bbn' as menu, a.tgl_mohon_samsat , d.nama_dealer, a.nama_konsumen , a.id_tipe_kendaraan , a.id_warna , a.no_mesin , a.no_rangka , c.tgl_bastd , c.no_bastd , x.tgl_transfer , a.biaya_bbn , a.biaya_bbn_md_bj , 667500 as biaya_adm, (a.biaya_bbn_md_bj+667500) as total_bbn_md_birojasa, z.tgl_bayar, z.no_entry, y.tgl_bayar as tgl_bayar_adm, y.no_entry as no_entry_adm 
					from tr_pengajuan_bbn_detail a
					join tr_faktur_stnk_detail b on a.no_mesin = b.no_mesin 
					join tr_faktur_stnk c on b.no_bastd  = c.no_bastd and c.status_faktur !='rejected'
					join ms_dealer d on c.id_dealer = d.id_dealer 
					left join (
						select a.referensi , c.tgl_mohon_samsat , c.id_adm_bbn , sum(a.nominal) , GROUP_CONCAT(b.id_voucher_bank) as no_entry, 
						GROUP_CONCAT( (case when d.tgl_transfer <> '' then d.tgl_transfer else e.tgl_bg end) ) as tgl_bayar
						from tr_voucher_bank_detail a
						join tr_voucher_bank b on a.id_voucher_bank = b.id_voucher_bank 
						join tr_adm_bbn c on a.referensi  = c.id_adm_bbn 
						left join tr_voucher_bank_transfer d on d.id_voucher_bank = b.id_voucher_bank
						left join tr_voucher_bank_bg e on e.id_voucher_bank = b.id_voucher_bank
						where referensi in (
							select id_adm_bbn from tr_adm_bbn tab where tgl_mohon_samsat ='$tgl1'
						) and b.status !='batal'
						group by a.referensi , c.tgl_mohon_samsat , c.id_adm_bbn 
					)z on a.tgl_mohon_samsat  = z.tgl_mohon_samsat
					left join (
						select f.id_penerimaan_bank , g.tgl_transfer , c.no_bastd , d.tgl_mohon_samsat , d.no_mesin
						from tr_penerimaan_bank_detail e
						join tr_penerimaan_bank f on e.id_penerimaan_bank = f.id_penerimaan_bank
						join tr_penerimaan_bank_transfer g on g.id_penerimaan_bank = f.id_penerimaan_bank 
						join tr_faktur_stnk c on e.referensi  = c.no_bastd 
						join tr_pengajuan_bbn_detail d on d.no_bastd = c.no_bastd 
						where d.tgl_mohon_samsat ='$tgl1'
					)x on x.no_bastd = c.no_bastd and x.no_mesin = a.no_mesin
					left join (
						select a.referensi , c.tgl_mohon_samsat , c.id_adm_stnk , sum(a.nominal) , GROUP_CONCAT(b.id_voucher_bank) as no_entry, 
						GROUP_CONCAT( (case when d.tgl_transfer <> '' then d.tgl_transfer else e.tgl_bg end) ) as tgl_bayar
						from tr_voucher_bank_detail a
						join tr_voucher_bank b on a.id_voucher_bank = b.id_voucher_bank 
						join tr_adm_stnk c on a.referensi  = c.id_adm_stnk 
						left join tr_voucher_bank_transfer d on d.id_voucher_bank = b.id_voucher_bank
						left join tr_voucher_bank_bg e on e.id_voucher_bank = b.id_voucher_bank
						where referensi in (
							select id_adm_stnk from tr_adm_stnk tab where tgl_mohon_samsat ='$tgl1'
						) and b.status !='batal'
						group by a.referensi , c.tgl_mohon_samsat , c.id_adm_stnk 
					)y on a.tgl_mohon_samsat  = z.tgl_mohon_samsat
					where a.tgl_mohon_samsat ='$tgl1' $where
				");

				$this->load->view('h1/laporan/laporan_pembayaran_bbn_biro', $data);
		    }

		} else {
			$data['isi']    = $this->page;		
			$data['title']	= $this->title;															
			$data['set']		= "view";			
			$this->template($data);	
		}	
	}	
}