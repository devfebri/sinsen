<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak_faktur extends CI_Controller {

    var $tables =   "tr_pengajuan_bbn";	
		var $folder =   "h1";
		var $page		=		"cetak_faktur";
    var $pk     =   "id_pengajuan_bbn";
    var $title  =   "Cetak Faktur";


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
		$this->load->library('cfpdf');
		$this->load->library('mpdf_l');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');

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
		$tgl_mohon_samsat = $data['tgl_mohon_samsat'] = $this->input->post('tgl_mohon_samsat');
		$no_faktur_ahm = $data['no_faktur_ahm']    = $this->input->post('no_faktur_ahm');
		$no_mesin = $data['no_mesin']         = $this->input->post('no_mesin');
		$id_dealer = $data['id_dealer']        = $this->input->post('id_dealer');
		$where_cari='';
		if ($tgl_mohon_samsat!='') {
			$where_cari .= "AND tgl_mohon_samsat='$tgl_mohon_samsat'";
		}
		if ($no_faktur_ahm!='') {
			$where_cari .= "AND tr_fkb.nomor_faktur LIKE '%$no_faktur_ahm%'";
		}if ($no_mesin!='') {
			$where_cari .= "AND tr_faktur_stnk_detail.no_mesin LIKE '%$no_mesin%'";
		}if ($id_dealer!='') {
			$where_cari .= "AND tr_faktur_stnk.id_dealer='$id_dealer'";
		}

		if($where_cari == ''){
			// $where_cari .='AND 1=0';
		}

		// if ($tgl_mohon_samsat!='') {
		// 	$where_cari = "AND (tgl_mohon_samsat='$tgl_mohon_samsat' OR tr_fkb.nomor_faktur='$no_faktur_ahm' OR tr_faktur_stnk_detail.no_mesin='$no_mesin' OR tr_faktur_stnk.id_dealer='$id_dealer') ";
		// }
		$data['dt_bbn'] = $this->db->query("SELECT 
			tr_faktur_stnk_detail.id_faktur_stnk_detail,
			tr_faktur_stnk_detail.no_bastd,
			tr_faktur_stnk_detail.id_sales_order,
			tr_faktur_stnk_detail.no_spk,
			tr_faktur_stnk_detail.no_mesin,
			tr_faktur_stnk_detail.no_rangka,
			tr_faktur_stnk_detail.alamat,
			tr_faktur_stnk_detail.ktp,
			tr_faktur_stnk_detail.fisik,
			tr_faktur_stnk_detail.stnk,
			tr_faktur_stnk_detail.bpkb,
			tr_faktur_stnk_detail.kuasa,
			tr_faktur_stnk_detail.ckd,
			tr_faktur_stnk_detail.permohonan,
			tr_faktur_stnk_detail.biaya_bbn,
			tr_faktur_stnk_detail.biaya_bbn_md,
			tr_faktur_stnk_detail.harga_unit,
			ms_warna.warna_samsat as warna,ms_tipe_kendaraan.deskripsi_ahm,ms_tipe_kendaraan.deskripsi_samsat,ms_dealer.nama_dealer,tr_fkb.nomor_faktur,tr_pengajuan_bbn_detail.tgl_mohon_samsat,
			CASE WHEN tr_pengajuan_bbn_detail.nama_konsumen IS NULL
			THEN tr_faktur_stnk_detail.nama_konsumen
			ELSE tr_pengajuan_bbn_detail.nama_konsumen
			END AS nama_konsumen
			FROM tr_faktur_stnk_detail 
			JOIN tr_faktur_stnk ON tr_faktur_stnk_detail.no_bastd=tr_faktur_stnk.no_bastd
			LEFT JOIN tr_scan_barcode ON tr_faktur_stnk_detail.no_mesin=tr_scan_barcode.no_mesin
			LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan
			LEFT JOIN tr_fkb ON tr_faktur_stnk_detail.no_mesin=tr_fkb.no_mesin_spasi
			LEFT JOIN ms_warna ON tr_scan_barcode.warna=ms_warna.id_warna
			LEFT JOIN ms_dealer ON tr_faktur_stnk.id_dealer=ms_dealer.id_dealer
			LEFT JOIN tr_pengajuan_bbn_detail ON tr_faktur_stnk_detail.no_mesin=tr_pengajuan_bbn_detail.no_mesin
			WHERE tr_faktur_stnk.status_faktur='approved'
			AND ((SELECT count(no_mesin) FROM tr_pengajuan_bbn_detail WHERE no_mesin=tr_faktur_stnk_detail.no_mesin AND status_bbn='generated')!=1 )
			AND
			((SELECT count(no_mesin) FROM tr_pengajuan_bbn_detail WHERE no_mesin=tr_faktur_stnk_detail.no_mesin AND reject='')=1 
			OR (SELECT count(no_mesin) FROM tr_pengajuan_bbn_detail WHERE no_mesin=tr_faktur_stnk_detail.no_mesin)=0  )
			$where_cari
			ORDER BY id_faktur_stnk_detail DESC");
			
		$this->db->order_by('nama_dealer','ASC');
		$data['dealer'] = $this->db->get('ms_dealer');
		$this->template($data);			
	}
	public function server_faktur()
	{		
		echo '
				<thead>
          <tr>                          
            <td>Nama Konsumen</td>
            <td>No Mesin</td>              
            <td>No Rangka</td>              
            <td>Dealer</td>                          
            <td>No Faktur AHM</td>              
            <td>Tipe</td>              
            <td>Warna</td>              
            <td>Tgl Mohon Samsat</td>
            <td>Action</td>              
          </tr>                     
        </thead>
       <tbody>
		';
		$sql = "SELECT tr_faktur_stnk_detail.id_faktur_stnk_detail, tr_faktur_stnk_detail.no_bastd, tr_faktur_stnk_detail.id_sales_order, tr_faktur_stnk_detail.no_spk, tr_faktur_stnk_detail.no_mesin, tr_faktur_stnk_detail.no_rangka, tr_faktur_stnk_detail.alamat, tr_faktur_stnk_detail.ktp, tr_faktur_stnk_detail.fisik, tr_faktur_stnk_detail.stnk, tr_faktur_stnk_detail.bpkb, tr_faktur_stnk_detail.kuasa, tr_faktur_stnk_detail.ckd, tr_faktur_stnk_detail.permohonan, tr_faktur_stnk_detail.biaya_bbn, tr_faktur_stnk_detail.biaya_bbn_md, tr_faktur_stnk_detail.harga_unit, ms_warna.warna,ms_tipe_kendaraan.deskripsi_ahm,ms_dealer.nama_dealer,tr_fkb.nomor_faktur,tr_pengajuan_bbn_detail.tgl_mohon_samsat, CASE WHEN tr_pengajuan_bbn_detail.nama_konsumen IS NULL THEN tr_faktur_stnk_detail.nama_konsumen ELSE tr_pengajuan_bbn_detail.nama_konsumen ";
		$sql .= " FROM tr_faktur_stnk_detail JOIN tr_faktur_stnk ON tr_faktur_stnk_detail.no_bastd=tr_faktur_stnk.no_bastd";
		$sql .= " LEFT JOIN tr_scan_barcode ON tr_faktur_stnk_detail.no_mesin=tr_scan_barcode.no_mesin";
		$sql .= " LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan";
		$sql .= " LEFT JOIN tr_fkb ON tr_faktur_stnk_detail.no_mesin=tr_fkb.no_mesin_spasi";
		$sql .= " LEFT JOIN ms_warna ON tr_scan_barcode.warna=ms_warna.id_warna";
		$sql .= " LEFT JOIN ms_dealer ON tr_faktur_stnk.id_dealer=ms_dealer.id_dealer";
		$sql .= " LEFT JOIN tr_pengajuan_bbn_detail ON tr_faktur_stnk_detail.no_mesin=tr_pengajuan_bbn_detail.no_mesin";
		$sql .= " WHERE tr_faktur_stnk.status_faktur='approved'";
		$sql .= " AND ((SELECT count(no_mesin) FROM tr_pengajuan_bbn_detail WHERE no_mesin=tr_faktur_stnk_detail.no_mesin AND status_bbn='generated')!=1 )";
		$sql .= " AND ((SELECT count(no_mesin) FROM tr_pengajuan_bbn_detail WHERE no_mesin=tr_faktur_stnk_detail.no_mesin AND reject='')=1 OR (SELECT count(no_mesin) FROM tr_pengajuan_bbn_detail WHERE no_mesin=tr_faktur_stnk_detail.no_mesin)=0  )";
		$sql .= " ORDER BY tr_faktur_stnk_detail.id_faktur_stnk_detail DESC LIMIT 0,50";

		$query = $this->db->query("$sql2");
		$data = array();
		$no = 1;
		foreach ($query->result() as $isi) {			
			echo "
				<tr>          
          <td>$isi->nama_konsumen</td>
          <td>$isi->no_mesin</td>	                    
          <td>$isi->no_rangka</td>	                    
          <td>$isi->nama_dealer</td>	                    
          <td>$isi->nomor_faktur</td>	                    
          <td>$isi->deskripsi_ahm</td>	                    
          <td>$isi->warna</td>	                    
          <td>$isi->tgl_mohon_samsat</td>	                    
          <td></td>	                    
        </tr>
			";			
		}		
	}

	public function history()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "history";
		$tgl_mohon_samsat = $data['tgl_mohon_samsat'] = $this->input->post('tgl_mohon_samsat');
		$no_faktur_ahm = $data['no_faktur_ahm']    = $this->input->post('no_faktur_ahm');
		$no_mesin = $data['no_mesin']         = $this->input->post('no_mesin');
		$id_dealer = $data['id_dealer']        = $this->input->post('id_dealer');
		$where_cari='';
		if ($tgl_mohon_samsat!='') {
			$where_cari .= "AND tgl_mohon_samsat='$tgl_mohon_samsat'";
		}
		if ($no_faktur_ahm!='') {
			$where_cari .= "AND tr_fkb.nomor_faktur LIKE '%$no_faktur_ahm%'";
		}if ($no_mesin!='') {
			$where_cari .= "AND tr_faktur_stnk_detail.no_mesin='$no_mesin'";
		}if ($id_dealer!='') {
			$where_cari .= "AND tr_faktur_stnk.id_dealer='$id_dealer'";
		}
		// if ($tgl_mohon_samsat!='') {
		// 	$where_cari = "AND (tgl_mohon_samsat='$tgl_mohon_samsat' OR tr_fkb.nomor_faktur='$no_faktur_ahm' OR tr_faktur_stnk_detail.no_mesin='$no_mesin' OR tr_faktur_stnk.id_dealer='$id_dealer') ";
		// }
		$data['dt_bbn'] = $this->db->query("SELECT tr_faktur_stnk_detail.*,ms_warna.warna,ms_tipe_kendaraan.deskripsi_ahm,ms_dealer.nama_dealer,tr_fkb.nomor_faktur,tr_pengajuan_bbn_detail.tgl_mohon_samsat FROM tr_faktur_stnk_detail 
			JOIN tr_faktur_stnk ON tr_faktur_stnk_detail.no_bastd=tr_faktur_stnk.no_bastd
			LEFT JOIN tr_scan_barcode ON tr_faktur_stnk_detail.no_mesin=tr_scan_barcode.no_mesin
			LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan
			LEFT JOIN tr_fkb ON tr_faktur_stnk_detail.no_mesin=tr_fkb.no_mesin_spasi
			LEFT JOIN ms_warna ON tr_scan_barcode.warna=ms_warna.id_warna
			LEFT JOIN ms_dealer ON tr_faktur_stnk.id_dealer=ms_dealer.id_dealer
			LEFT JOIN tr_pengajuan_bbn_detail ON tr_faktur_stnk_detail.no_mesin=tr_pengajuan_bbn_detail.no_mesin
			WHERE tr_faktur_stnk.status_faktur='approved'
			AND ((SELECT count(no_mesin) FROM tr_pengajuan_bbn_detail WHERE no_mesin=tr_faktur_stnk_detail.no_mesin AND status_bbn='generated')=1 )
			AND
			((SELECT count(no_mesin) FROM tr_pengajuan_bbn_detail WHERE no_mesin=tr_faktur_stnk_detail.no_mesin AND reject='')=1 
			OR (SELECT count(no_mesin) FROM tr_pengajuan_bbn_detail WHERE no_mesin=tr_faktur_stnk_detail.no_mesin)=0 )
			$where_cari
			ORDER BY id_faktur_stnk_detail DESC");
		$this->db->order_by('nama_dealer','ASC');
		$this->db->limit(100);
		$data['dealer'] = $this->db->get('ms_dealer');
		$this->template($data);			
	}	

	public function generate_ulang()
	{				
		$data['isi']   = $this->page;
		$data['title'] = 'Generate Ulang File TXT Samsat';			
		$data['set']   = "generate_ulang";				
		$this->template($data);			
	}

	function get_data_generate_ulang($tgl_mohon_samsat)
	{
		return $get_data = $this->db->query("SELECT * FROM(
					SELECT tgl_mohon_samsat, no_faktur,tr_pengajuan_bbn_detail.no_mesin,no_rangka,id_tipe_kendaraan,id_warna,id_kelurahan,kelurahan,kecamatan,kabupaten,(SELECT tahun_produksi FROM tr_fkb WHERE no_mesin_spasi=tr_pengajuan_bbn_detail.no_mesin) AS tahun_produksi,nama_konsumen,no_ktp,alamat,nama_ibu,tgl_ibu,pekerjaan,tgl_jual,biaya_bbn,no_bastd 
					FROM tr_pengajuan_bbn_detail
					WHERE tgl_mohon_samsat='$tgl_mohon_samsat' AND status_bbn='generated'
					UNION
					SELECT tgl_samsat AS tgl_mohon_samsat, no_faktur, tr_bantuan_bbn.no_mesin,no_rangka,id_tipe_kendaraan,id_warna,id_kelurahan,kelurahan,kecamatan,kabupaten,tahun_produksi,nama_konsumen,no_ktp,alamat,nama_ibu,tgl_ibu,pekerjaan, tgl_samsat AS tgl_jual,biaya_bbn,no_faktur FROM tr_bantuan_bbn
					WHERE tr_bantuan_bbn.tgl_samsat='$tgl_mohon_samsat' AND 
					(tr_bantuan_bbn.status='generated' OR tr_bantuan_bbn.status='approved')
				) AS tabel ORDER BY nama_konsumen ASC
				");
	}

	function cekGenerateUlang()
	{
		$tgl_mohon_samsat = $this->input->post('tgl_mohon_samsat');
		$get_data = $this->get_data_generate_ulang($tgl_mohon_samsat);
		$no=1;
		foreach ($get_data->result() as $rs) {
			$wil = $this->db->query("SELECT ms_kabupaten.*,kelurahan,kecamatan,ms_kelurahan.kode_samsat FROM ms_kabupaten INNER JOIN ms_kecamatan ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
				INNER JOIN ms_kelurahan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan 
				WHERE ms_kelurahan.id_kelurahan = '$rs->id_kelurahan'")->row();
			$tp = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan='$rs->id_tipe_kendaraan'")->row();
			$wr = $this->db->query("SELECT * FROM ms_warna WHERE id_warna='$rs->id_warna'")->row();
			$fkb = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin_spasi='$rs->no_mesin'");
			if ($fkb->num_rows()>0) {
				$fkb = $fkb->row();
				$rs_silinder = $fkb->isi_silinder;
			}else{
				$rs_silinder = '';
			}
			
			if($rs->tahun_produksi==''){
				$dt_bantuan_bbn = $this->db->query("SELECT tahun_produksi FROM tr_bantuan_bbn_luar WHERE no_mesin ='$rs->no_mesin'");
				if ($dt_bantuan_bbn->num_rows()>0) {
					$rs->tahun_produksi= $dt_bantuan_bbn->row()->tahun_produksi;
				}
			}

			$no_faktur = str_replace(' ','','FH/'.$rs->no_faktur);
			echo '<tr>';
			echo "<td>$no</td>";
			echo "<td>$rs->nama_konsumen</td>";
			echo "<td>$rs->alamat</td>";
			echo "<td>$rs->no_rangka</td>";
			echo "<td>$rs->no_mesin</td>";
			echo "<td>$no_faktur</td>";
			echo "<td>$tp->deskripsi_ahm</td>";
			echo "<td>$wr->warna_samsat</td>";
			echo "<td>$rs->tahun_produksi</td>";
			echo "<td>".mata_uang_rp($rs->biaya_bbn)."</td>";
			echo "<td>$rs->tgl_jual</td>";
			echo "<td>$rs->tgl_mohon_samsat</td>";
			echo '</tr>';
			$no++;
		}
	}
	public function download_file_samsat_history()
	{	
		$tgl_mohon_samsat = $this->input->post('tgl_mohon_samsat');

		/*
      ini_set('memory_limit', '-1');
      ini_set('max_execution_time', 900);
      $mpdf                           = $this->pdf->load();
      $mpdf->allow_charset_conversion =true;  // Set by default to TRUE
      $mpdf->charset_in               ='UTF-8';
      $mpdf->autoLangToFont           = true;
      $data['set']                   	= 'cetak';                  
      $html = $this->load->view('h1/laporan/monitor_kekurangan_bbn', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'print.pdf';
      $mpdf->Output("$output", 'I');
		*/

		if ($this->input->post('save_excel')!='') {
			$data['nama_file'] = "GENERATE_-".$tgl_mohon_samsat;
			$data['tgl_mohon_samsat'] = $tgl_mohon_samsat;
			$data['get_data']  = $this->get_data_generate_excel($tgl_mohon_samsat);
			$this->load->view("h1/generate_samsat_biro",$data);

		}else{
			$data['nama_file'] = "GENERATE FILE SAMSAT-".$tgl_mohon_samsat;
			$data['get_data']  = $this->get_data_generate_ulang($tgl_mohon_samsat);
			$this->load->view("h1/file_samsat_ulang",$data);
		}
	}

	function get_data_generate_excel($tgl_mohon_samsat)
	{
		return $get_data = $this->db->query("
			SELECT md.nama_dealer , data.* FROM(
				SELECT tgl_mohon_samsat,a.no_mesin,no_rangka, nama_konsumen, c.id_dealer 
				FROM tr_pengajuan_bbn_detail a 
				join tr_faktur_stnk c on c.no_bastd  = a.no_bastd 
				WHERE tgl_mohon_samsat='$tgl_mohon_samsat' AND status_bbn='generated'
				UNION
				SELECT tgl_samsat AS tgl_mohon_samsat, tr_bantuan_bbn.no_mesin,no_rangka,nama_konsumen, id_dealer 
				FROM tr_bantuan_bbn
				WHERE tr_bantuan_bbn.tgl_samsat='$tgl_mohon_samsat' AND 
				(tr_bantuan_bbn.status='generated' OR tr_bantuan_bbn.status='approved')
			) AS data 
			join ms_dealer md on data.id_dealer = md.id_dealer 
			ORDER BY nama_dealer asc, nama_konsumen ASC
		");
	}

	function get_data_faktur(){
		$tahun = date('Y');
		$query = $this->db->query("select tf.kode_tipe, mtk.no_mesin as digit, mtk.deskripsi_samsat , nomor_faktur , tf.no_mesin_spasi as no_mesin , mtk.cc_motor 
			from tr_fkb tf 
			join ms_tipe_kendaraan mtk on tf.kode_tipe = mtk.id_tipe_kendaraan 
			where tahun_produksi ='$tahun'
			group by mtk.id_tipe_kendaraan
			order by tf.kode_tipe ASC");

		if($query->num_rows() >0 ) {
			$content = '<table class="table table-bordered">
			<thead>
				<tr>
					<td>No</td>
					<td>Kode Tipe</td>
					<td>5 Digit No Mesin</td>
					<td>Faktur Samsat</td>
					<td>No Faktur AHM</td>
					<td>Contoh No Mesin </td>
					<td>CC Motor</td>
				</tr></thead><tbody>';
			$i=0;
			foreach($query->result() as $row){
				$i++;
				$content .='<tr>
					<td>'.$i.'</td>
					<td>'.$row->kode_tipe.'</td>
					<td>'.$row->digit.'</td>
					<td>'.$row->deskripsi_samsat.'</td>
					<td>'.$row->nomor_faktur.'</td>
					<td>'.$row->no_mesin.'</td>
					<td>'.$row->cc_motor.'</td>
					</tr>';
			}
		$content .= '
			</tbody>
		</table>';

		}else{
		
			$content = "Under Maintenance.";
		}
		echo $content;
	}


}