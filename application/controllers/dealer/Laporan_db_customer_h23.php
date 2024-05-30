<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_db_customer_h23 extends CI_Controller
{

  var $folder =   "dealer/laporan";
  var $page    =    "laporan_db_customer_h23";
  var $title  =   "Laporan Database Customer AHASS";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_dealer_laporan', 'm_lap');
    //===== Load Library =====		

    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    $auth = $this->m_admin->user_auth($this->page, "select");
    $sess = $this->m_admin->sess_auth();
    if ($name == "" or $auth == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
    } elseif ($sess == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
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
    if (isset($_GET['cetak'])) {
      ini_set('memory_limit', '-1');
      ini_set('max_execution_time', 900);

      $params = json_decode($_GET['params']);
      
      $data['set']   = 'cetak';
      $data['title'] = "Database Customer AHASS";
      $data['params'] = $params;
      // send_json($data);
      $filter = [
        'start_date' => $params->start_date,
        'end_date' => $params->end_date,
        'id_dealer'=>$this->db->query("select a.id_dealer,b.id_karyawan_dealer,c.id_user from ms_dealer a join ms_karyawan_dealer b on a.id_dealer=b.id_dealer join ms_user c on c.id_karyawan_dealer=b.id_karyawan_dealer where c.id_user='{$_SESSION['id_user']}'")->row()->id_dealer,
      ];
        if($filter['start_date']=="" && $filter['end_date']==""){
            
            $data['details'] = $this->db->query("select a.no_mesin,a.no_rangka,a.tahun_produksi,left(c.created_at,10) as tanggal_service,right(c.created_at,8) as jam_servis,
            a.tgl_pembelian,a.no_polisi,b.id_type,a.nama_customer,a.alamat,
            d.deskripsi_ahm,d.tipe_ahm,a.no_hp, CASE WHEN so.id_dealer=c.id_dealer THEN 'Own Dealer' 
		 WHEN so.id_dealer<>c.id_dealer THEN 'Other Dealer'
		 ELSE 'Other Dealer' END as detail_pembelian, pendidikan.pendidikan, pekerjaan.pekerjaan,  CASE WHEN a.id_kelurahan is not null THEN kel.kelurahan ELSE '' END as nama_kelurahan,
	    kec.kecamatan as nama_kecamatan, kab.kabupaten as nama_kabupaten, prov.provinsi as nama_provinsi       
            from ms_customer_h23 
            a join tr_h2_sa_form b on b.id_customer =a.id_customer 
            join tr_h2_wo_dealer c on c.id_sa_form =b.id_sa_form 
            join ms_tipe_kendaraan d on a.id_tipe_kendaraan =d.id_tipe_kendaraan 
	    join tr_sales_order so on a.no_mesin=so.no_mesin
	    left join ms_kelurahan kel on a.id_kelurahan=kel.id_kelurahan
	    left join ms_kecamatan kec on kel.id_kecamatan=kec.id_kecamatan
	    left join ms_kabupaten kab on kec.id_kabupaten=kab.id_kabupaten
	    left join ms_provinsi prov on kab.id_provinsi=prov.id_provinsi 
	    left join tr_spk spk on so.no_spk=spk.no_spk
	    left join ms_pekerjaan pekerjaan on spk.pekerjaan=pekerjaan.id_pekerjaan
	    left join ms_pendidikan pendidikan on spk.pendidikan=pendidikan.id_pendidikan

            where b.id_dealer ='{$filter['id_dealer']}' and c.status ='closed'")->result();
        }else{
             $data['details'] = $this->db->query("select a.no_mesin,a.no_rangka,a.tahun_produksi,left(c.created_at,10) as tanggal_service,right(c.created_at,8) as jam_servis,
            a.tgl_pembelian,a.no_polisi,jasa.id_type,a.nama_customer,a.alamat,
            d.deskripsi_ahm,d.tipe_ahm,a.no_hp,kerja.harga,kerja.id_work_order,kerja.id_jasa, CASE WHEN so.id_dealer=c.id_dealer THEN 'Own Dealer' 
		 WHEN so.id_dealer<>c.id_dealer THEN 'Other Dealer' 
		 ELSE 'Other Dealer' END as detail_pembelian,
             pendidikan.pendidikan, pekerjaan.pekerjaan,
	    CASE WHEN a.id_kelurahan is not null THEN kel.kelurahan ELSE '' END as nama_kelurahan,
	    kec.kecamatan as nama_kecamatan, kab.kabupaten as nama_kabupaten, prov.provinsi as nama_provinsi
	    from ms_customer_h23 
            a join tr_h2_sa_form b on b.id_customer =a.id_customer 
            join tr_h2_wo_dealer c on c.id_sa_form =b.id_sa_form 
            join ms_tipe_kendaraan d on a.id_tipe_kendaraan =d.id_tipe_kendaraan 
            join tr_h2_wo_dealer_pekerjaan kerja on c.id_work_order =kerja.id_work_order 
            join ms_h2_jasa jasa on kerja.id_jasa =jasa.id_jasa
	    left join tr_sales_order so on a.no_mesin=so.no_mesin
	    left join tr_spk spk on so.no_spk=spk.no_spk
	    left join ms_kelurahan kel on a.id_kelurahan=kel.id_kelurahan
	    left join ms_kecamatan kec on kel.id_kecamatan=kec.id_kecamatan
	    left join ms_kabupaten kab on kec.id_kabupaten=kab.id_kabupaten
	    left join ms_provinsi prov on kab.id_provinsi=prov.id_provinsi 
	    left join ms_pekerjaan pekerjaan on spk.pekerjaan=pekerjaan.id_pekerjaan
	    left join ms_pendidikan pendidikan on spk.pendidikan=pendidikan.id_pendidikan
            where b.id_dealer ='{$filter['id_dealer']}' and LEFT(c.created_at ,10) 
            between '{$filter['start_date']}' and '{$filter['end_date']}' and c.status ='closed'")->result();
        }
         
      if ($params->tipe == 'preview') {
          
        $this->load->library('pdf');
        $mpdf                           = $this->pdf->load();
        $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
        $mpdf->charset_in               = 'UTF-8';
        $mpdf->autoLangToFont           = true;

        $html = $this->load->view($this->folder . '/' . $this->page, $data, true);
        $mpdf->WriteHTML($html);
        $output = $this->page . '.pdf';
        $mpdf->Output("$output", 'I');
      } else {
        $this->load->view($this->folder . '/' . $this->page, $data);
      }
    } else {
      $data['isi']    = $this->page;
      $data['title']  = "Laporan Database Customer AHASS";
      $data['set']    = "view";
      $this->template($data);
    }
  }
}
