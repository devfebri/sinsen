<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_sales_program extends CI_Controller
{

  var $tables =   "tr_do_dealer";
  var $folder =   "h1";
  var $page   =   "report_sales_program";
  var $pk     =   "no_do";
  var $title  =   "Report Sales Program";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('M_h1_md_sales_program', 'm_sp');
    $this->load->model('M_business_control_h1', 'mbc');
    $this->load->helper('tgl_indo');

    //===== Load Library =====
    $this->load->library('upload');

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
      $data['group']  = $this->session->userdata("group");
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $this->load->view($this->folder . "/" . $this->page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $data['set']    = "view";
    $this->template($data);
  }
  public function view()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $data['set']  = "detail";
    $id       = $this->input->get('id');
    $get_program    = $this->db->query("SELECT * FROM tr_sales_program WHERE id_program_md='$id'");
    if ($get_program->num_rows() > 0) {
      $data['program'] = $get_program->row();
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/report_sales_program'>";
    }
  }
  public function report_view()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $data['set']    = "report_view";
    $this->template($data);
  }
  public function download_report_monitoring_claim()
  {

    if (isset($_POST['id_program_md'])) {
      $filter = ['id_program_md' => $this->input->post('id_program_md')];
      $this->_download_rep_mon_claim($filter);
    } else {
      $data['isi']    = $this->page;
      $data['title']  = $this->title;
      $data['set']    = "download_report_monitoring_claim";
      $this->template($data);
    }
  }

  function _download_rep_mon_claim($filter)
  {
    $this->load->model('m_dgi_api');

    include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
    $excel = new PHPExcel();

    $sp = $this->m_sp->getSalesProgram($filter);
    if ($sp->num_rows() > 0) {
      $sp = $sp->row();
    } else {
      $_SESSION['pesan']  = "ID Progam MD tidak ditemukan !";
      $_SESSION['tipe']   = "danger";
      echo "<script>history.go(-1)</script>";
    }
    $row = 1;
    $excel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Laporan Monitoring Klaim');
    $row = 3;
    $excel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Sales ID Program MD');
    $excel->setActiveSheetIndex(0)->setCellValue('B' . $row, ' :' . $sp->id_program_md);
    $row = 4;
    $excel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Nama Program');
    $excel->setActiveSheetIndex(0)->setCellValue('B' . $row, ' :' . $sp->judul_kegiatan);
    $row = 6;
    $excel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Periode Awal Program');
    $excel->setActiveSheetIndex(0)->setCellValue('B' . $row, ' :' . shortdate_indo($sp->periode_awal));
    $row = 7;
    $excel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Periode Akhir Program');
    $excel->setActiveSheetIndex(0)->setCellValue('B' . $row, ' :' . shortdate_indo($sp->periode_akhir));

    $row = 9;
    $header = [
      'A' . $row => 'Kode Main Dealer',
      'B' . $row => 'Kode Dealer',
      'C' . $row => 'Nama Dealer',
      'D' . $row => 'Sales Program ID AHM',
      'E' . $row => 'Sales Program ID MD',
      'F' . $row => 'No. Faktur Penjualan Dealer',
      'G' . $row => 'Tanggal Faktur',
      'H' . $row => 'No. PO Finance Company',
      'I' . $row => 'Tanggal PO Finance Company',
      'J' . $row => 'No. Rangka',
      'K' . $row => 'No. Mesin',
      'L' . $row => 'Kode Tipe Motor',
      'M' . $row => 'Nama Tipe Motor',
      'N' . $row => 'Kode Warna',
      'O' . $row => 'Deskripsi Warna',
      'P' . $row => 'Tanggal Faktur STNK',
      'Q' . $row => 'Cash / Credit',
      'R' . $row => 'ID FinCoy',
      'S' . $row => 'Finance Company',
      'T' . $row => 'Nama Dealer TA',
      'U' . $row => 'Tanggal BAST Unit',
      'V' . $row => 'Nama Customer',
      'W' . $row => 'Alamat',
      'X' . $row => 'Kota',
      'Y' . $row => 'Tanggal Entry Claim',
      'Z' . $row => 'Status MD',
      'AA' . $row => 'Tgl Verifikasi MD',
      'AB' . $row => 'Alasan Reject',
      'AC' . $row => 'Status BC',
      'AD' . $row => 'Tgl Verifikasi BC',
      'AE' . $row => 'Alasan BC',
    ];
    // $excel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Catatan :');

    foreach ($header as $key => $hd) {
      $excel->getActiveSheet()->getStyle($key)->applyFromArray([
        'borders' => array(
          'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
          )
        ),
      ]);
      $excel->setActiveSheetIndex(0)->setCellValue($key, $hd);
    }
    $excel->getActiveSheet()->getStyle("A9:AE9")->getFont()->setBold(true);

    $data = $this->m_sp->getClaimSalesProgram($filter)->result();
    $row = $row + 1;
    $kode_md = 'E20';
    $no = 1;
    $row_first = $row;
    foreach ($data as $dt) {
      $dlr = $this->db->query("SELECT kode_dealer_md, nama_dealer FROM ms_dealer WHERE id_dealer='$dt->id_dealer'")->row();

      $get_kab = $this->db->query("SELECT kabupaten FROM ms_kabupaten kab
      JOIN ms_kecamatan kec ON kec.id_kabupaten=kab.id_kabupaten
      JOIN ms_kelurahan kel ON kel.id_kecamatan=kec.id_kecamatan
      where id_kelurahan='$dt->id_kelurahan'
      ");
      $kabupaten = '';
      if ($get_kab->num_rows() > 0) {
        $kabupaten = $get_kab->row()->kabupaten;
      }

      $f = [
        'id_dealer' => $dt->id_dealer,
        'id_sales_order' => $dt->id_sales_order
      ];
      $stnk = $this->m_dgi_api->getFakturSTNK($f)->row();
      $excel->setActiveSheetIndex(0)->setCellValue('A' . $row, $kode_md);
      $excel->setActiveSheetIndex(0)->setCellValue('B' . $row, $dlr->kode_dealer_md);
      $excel->setActiveSheetIndex(0)->setCellValue('C' . $row, $dlr->nama_dealer);
      $excel->setActiveSheetIndex(0)->setCellValue('D' . $row, $dt->id_program_ahm);
      $excel->setActiveSheetIndex(0)->setCellValue('E' . $row, $dt->id_program_md);
      $excel->setActiveSheetIndex(0)->setCellValue('F' . $row, $dt->no_invoice);
      $excel->setActiveSheetIndex(0)->setCellValue('G' . $row, $dt->tgl_invoice);
      $excel->setActiveSheetIndex(0)->setCellValue('H' . $row, $dt->no_po_leasing);
      $excel->setActiveSheetIndex(0)->setCellValue('I' . $row, $dt->tgl_po_leasing);
      $excel->setActiveSheetIndex(0)->setCellValue('J' . $row, $dt->no_rangka);
      $excel->setActiveSheetIndex(0)->setCellValue('K' . $row, $dt->no_mesin);
      $excel->setActiveSheetIndex(0)->setCellValue('L' . $row, $dt->id_tipe_kendaraan);
      $excel->setActiveSheetIndex(0)->setCellValue('M' . $row, $dt->deskripsi_ahm);
      $excel->setActiveSheetIndex(0)->setCellValue('N' . $row, $dt->id_warna);
      $excel->setActiveSheetIndex(0)->setCellValue('O' . $row, $dt->warna);
      $excel->setActiveSheetIndex(0)->setCellValue('P' . $row, $stnk->tgl_pengajuan);
      $excel->setActiveSheetIndex(0)->setCellValue('Q' . $row, $dt->jenis_beli);
      $excel->setActiveSheetIndex(0)->setCellValue('R' . $row, $dt->id_finance_company);
      $excel->setActiveSheetIndex(0)->setCellValue('S' . $row, $dt->finance_company);
      $excel->setActiveSheetIndex(0)->setCellValue('T' . $row, ''); //Nama Dealer TA
      $excel->setActiveSheetIndex(0)->setCellValue('U' . $row, $dt->tgl_bastk); //Tanggal BAST Unit - belum
      $excel->setActiveSheetIndex(0)->setCellValue('V' . $row, $dt->nama_konsumen);
      $excel->setActiveSheetIndex(0)->setCellValue('W' . $row, $dt->alamat);
      $excel->setActiveSheetIndex(0)->setCellValue('X' . $row, $kabupaten);
      $excel->setActiveSheetIndex(0)->setCellValue('Y' . $row, $dt->tgl_ajukan_claim);
      $excel->setActiveSheetIndex(0)->setCellValue('Z' . $row, $dt->status);
      $excel->setActiveSheetIndex(0)->setCellValue('AA' . $row, $dt->tgl_approve_reject_md);
      $excel->setActiveSheetIndex(0)->setCellValue('AB' . $row, $dt->alasan_reject);
      $excel->setActiveSheetIndex(0)->setCellValue('AC' . $row, ''); //Status BC
      $excel->setActiveSheetIndex(0)->setCellValue('AD' . $row, ''); //Tgl Verifikasi BC
      $excel->setActiveSheetIndex(0)->setCellValue('AE' . $row, ''); //Alasan BC
      $no++;
      $row++;
    }
    $row_last = $row - 1;
    $excel->getActiveSheet()->getStyle('A' . $row_first . ':AE' . $row_last)->applyFromArray([
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
        )
      ),
    ]);
    $excel->getActiveSheet()->getColumnDimension('A')->setWidth(24);
    $excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
    $excel->getActiveSheet()->getColumnDimension('C')->setWidth(36);
    $excel->getActiveSheet()->getColumnDimension('D')->setWidth(24);
    $excel->getActiveSheet()->getColumnDimension('E')->setWidth(24);
    $excel->getActiveSheet()->getColumnDimension('F')->setWidth(28);
    $excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $excel->getActiveSheet()->getColumnDimension('H')->setWidth(28);
    $excel->getActiveSheet()->getColumnDimension('I')->setWidth(28);
    $excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
    $excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
    $excel->getActiveSheet()->getColumnDimension('L')->setWidth(23);
    $excel->getActiveSheet()->getColumnDimension('M')->setWidth(23);
    $excel->getActiveSheet()->getColumnDimension('N')->setWidth(14);
    $excel->getActiveSheet()->getColumnDimension('O')->setWidth(23);
    $excel->getActiveSheet()->getColumnDimension('P')->setWidth(25);
    $excel->getActiveSheet()->getColumnDimension('Q')->setWidth(13);
    $excel->getActiveSheet()->getColumnDimension('R')->setWidth(13);
    $excel->getActiveSheet()->getColumnDimension('S')->setWidth(25);
    $excel->getActiveSheet()->getColumnDimension('T')->setWidth(25);
    $excel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
    $excel->getActiveSheet()->getColumnDimension('V')->setWidth(24);
    $excel->getActiveSheet()->getColumnDimension('W')->setWidth(34);
    $excel->getActiveSheet()->getColumnDimension('X')->setWidth(24);
    $excel->getActiveSheet()->getColumnDimension('Y')->setWidth(24);
    $excel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);
    $excel->getActiveSheet()->getColumnDimension('AA')->setWidth(20);
    $excel->getActiveSheet()->getColumnDimension('AB')->setWidth(28);
    $excel->getActiveSheet()->getColumnDimension('AC')->setWidth(20);
    $excel->getActiveSheet()->getColumnDimension('AD')->setWidth(18);
    $excel->getActiveSheet()->getColumnDimension('AE')->setWidth(28);

    $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    $excel->getActiveSheet(0)->setTitle("REPORT MONITORING CLAIM");
    $excel->setActiveSheetIndex(0);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $nama_file = 'Report_Monitoring_claim-' . strtotime(get_ymd());
    header('Content-Disposition: attachment; filename="' . $nama_file . '.xlsx"'); // Set nama file excel nya
    header('Cache-Control: max-age=0');
    $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $write->save('php://output');
  }
}
