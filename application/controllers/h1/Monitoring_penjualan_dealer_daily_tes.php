<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_penjualan_dealer_daily_tes extends CI_Controller
{


  var $folder = "h1/laporan";
  var $page   = "monitoring_penjualan_dealer_daily_tes";
  var $title  = "Monitoring Penjualan Dealer Daily";

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
      // set_time_limit(500);
      set_time_limit(0);
      ini_set('memory_limit', '5000M');
      ini_set('max_execution_time', 1000000000000);
      $delimiter = ";";
      $filename = "monitoring_penjualan_dealer_daily_" . date('Y-m-d') . ".csv";
      header("Content-type: text/x-csv");
      header('Content-Disposition: attachment; filename=' . $filename);

      $f = fopen("php://output", "w");

      $fields = array('TGL PENJUALAN', 'KODE DEALER', 'NAMA DEALER', 'NO MESIN', 'NO RANGKA', 'KODE TIPE', 'KODE WARNA', 'DESKRIPSI MOTOR CUSTOMER', 'DESKRIPSI WARNA', 'HARGA OTR', 'DP GROSS', 'DP SETOR', 'JENIS CUSTOMER', 'JENIS KELAMIN', 'TGL LAHIR', 'NAMA CUSTOMER', 'NO KTP', 'ALAMAT', 'NAMA KELURAHAN', 'NAMA KECAMATAN', 'NAMA KOTA', 'KODE POS', 'AGAMA', 'PENGELUARAN', 'PEKERJAAN', 'PENDIDIKAN', 'PENANGGUNG JAWAB', 'NO HP', 'NO TELP', 'BERSEDIA DIHUBUNGI', 'MERK MOTOR SEKARANG', 'DIGUNAKAN UNTUK', 'YG MENGGUNAKAN MOTOR', 'HOBI', 'ID FLP', 'NAMA FLP DEALER', 'JABATAN', 'NAMA FINCOY', 'KETERANGAN', 'EMAIL');
      fputcsv($f, $fields, $delimiter);

      //output each row of the data, format line as csv and write to file pointer
      $data = $this->db->query("SELECT so.no_mesin,so.no_rangka,LEFT(so.tgl_cetak_invoice,10) AS tgl_cetak_invoice,
      nama_dealer,kode_dealer_md,
      spk.no_spk,spk.nama_konsumen,spk.harga_on_road,uang_muka,dp_stor,spk.alamat,spk.tgl_lahir,spk.no_ktp,kel.kode_pos,spk.no_hp,spk.no_telp,
      tipe_ahm,warna,spk.id_tipe_kendaraan,spk.id_warna,
      (SELECT CASE WHEN jenis_kelamin='Pria' THEN 'L' ELSE 'P' END AS jk FROM tr_prospek WHERE id_customer=spk.id_customer ORDER BY created_at DESC LIMIT 1) AS jk,
      kelurahan,kecamatan,kabupaten,
      ag.agama,pd.pendidikan,pb.pengeluaran,pk.pekerjaan,
      cdb.sedia_hub,ms.merk_sebelumnya,dg.digunakan,hb.hobi,
      (SELECT CONCAT(id_flp_md,'|', nama_lengkap,'|',jabatan) FROM ms_karyawan_dealer mkd
        JOIN ms_jabatan AS jb ON jb.id_jabatan=mkd.id_jabatan
        WHERE mkd.id_karyawan_dealer=(SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=spk.id_customer ORDER BY created_at DESC LIMIT 1)
      ) AS sales,fc.finance_company,spk.email,cdb.menggunakan
    FROM tr_sales_order AS so 
    JOIN ms_dealer ON so.id_dealer=ms_dealer.id_dealer
    JOIN tr_spk spk ON spk.no_spk=so.no_spk
    JOIN ms_tipe_kendaraan ON spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
    JOIN ms_warna ON spk.id_warna=ms_warna.id_warna 
    JOIN tr_cdb cdb ON cdb.no_spk=spk.no_spk
    LEFT JOIN ms_agama ag ON ag.id_agama=cdb.agama
    LEFT JOIN ms_pendidikan pd ON pd.id_pendidikan=cdb.pendidikan
    LEFT JOIN ms_pengeluaran_bulan pb ON pb.id_pengeluaran_bulan=spk.pengeluaran_bulan
    LEFT JOIN ms_merk_sebelumnya ms ON ms.id_merk_sebelumnya=cdb.merk_sebelumnya
    LEFT JOIN ms_hobi hb ON hb.id_hobi=cdb.hobi
    LEFT JOIN ms_digunakan dg ON dg.id_digunakan=cdb.digunakan
    LEFT JOIN ms_pekerjaan pk ON pk.id_pekerjaan=spk.pekerjaan
    LEFT JOIN ms_kelurahan kel ON kel.id_kelurahan = spk.id_kelurahan
    LEFT JOIN ms_kecamatan kec ON kec.id_kecamatan = kel.id_kecamatan
    LEFT JOIN ms_kabupaten kab ON kab.id_kabupaten = kec.id_kabupaten
    LEFT JOIN ms_finance_company fc ON fc.id_finance_company=spk.id_finance_company
    LIMIT 100
    -- LEFT JOIN ms_provinsi prov ON prov.id_provinsi = kab.id_provinsi
    ");
      foreach ($data->result() as $dt) {
        $sales = explode('|', $dt->sales);
        $lineData = array(
          $dt->tgl_cetak_invoice,
          $dt->kode_dealer_md,
          $dt->nama_dealer,
          $dt->no_mesin,
          $dt->no_rangka,
          $dt->id_tipe_kendaraan,
          $dt->id_warna,
          $dt->tipe_ahm,
          $dt->warna,
          mata_uang_rp($dt->harga_on_road),
          $dt->uang_muka,
          $dt->dp_stor,
          'Individu',
          $dt->jk,
          $dt->tgl_lahir,
          $dt->nama_konsumen,
          $dt->no_ktp,
          $dt->alamat,
          $dt->kelurahan,
          $dt->kecamatan,
          $dt->kabupaten,
          $dt->kode_pos,
          $dt->agama,
          $dt->pengeluaran,
          $dt->pekerjaan,
          $dt->pendidikan,
          '',
          $dt->no_hp,
          $dt->no_telp,
          $dt->sedia_hub,
          $dt->merk_sebelumnya,
          $dt->digunakan,
          $dt->menggunakan,
          $dt->hobi,
          $sales[0],
          $sales[1],
          $sales[2],
          $dt->finance_company,
          '',
          $dt->email,
        );
        fputcsv($f, $lineData, $delimiter);
      }
      fclose($f);
    } else {
      $data['isi']    = $this->page;
      $data['title']  = $this->title;
      $data['set']    = "view";
      $this->template($data);
    }
  }
}
