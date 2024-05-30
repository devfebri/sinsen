<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Dealer_performance extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_md_csl_master', 'm_csl_m');
    $this->load->model('m_md_csl', 'm_csl');

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function index()
  {
    $f_csl = [
      'id_dealer' => $this->login->id_dealer,
      'tahun' => $this->input->get('year'),
      'bulan' => $this->input->get('month'),
      'kategori' => $this->input->get('categories'),
    ];

    $re_claim = $this->m_csl->getDetailActualUpladCSL($f_csl);
    $data = [];
    $actuals = [];
    $targets = [];
    foreach ($re_claim->result() as $rs) {

      $f_csl['id_atribut'] = $rs->id_atribut;
      $f_csl['select'] = 'target_atribut';
      $target_csl = $this->m_csl->getDetailTargetListUpladCSL($f_csl)->row()->target;
      $targets[] = $target_csl;

      $data[] = [
        'id' => (int)$rs->id,
        'type' => $rs->tipe,
        'category' => $rs->kategori,
        'actual' => (int)$rs->actual,
        'target' => (int)$target_csl,
      ];
      $actuals[] = $rs->actual;
    }

    $actual = @ROUND(array_sum($actuals) / count($actuals));
    $target = @ROUND(array_sum($targets) / count($targets));
    $result = [
      'actual' => $actual,
      'target' => $target,
      'data' => $data,
    ];
    send_json(msg_sc_success($result));
  }

  function download()
  {
    include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
    $title    = "CSL Report";
    $tahun    = $this->input->get('year');
    $bulan    = $this->input->get('month');
    $kategori    = $this->input->get('categories');
    $id_dealer = $this->login->id_dealer;
    $user = sc_user(['id_user' => $this->login->id_user])->row();

    $dl = $this->db->query("SELECT dl.*,kelurahan,kecamatan,kabupaten,provinsi FROM ms_dealer dl
      LEFT JOIN ms_kelurahan kel ON kel.id_kelurahan=dl.id_kelurahan
      LEFT JOIN ms_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
      LEFT JOIN ms_kabupaten kab ON kab.id_kabupaten=kec.id_kabupaten
      LEFT JOIN ms_provinsi prov ON prov.id_provinsi=kab.id_provinsi
      WHERE dl.id_dealer='$id_dealer'
    ")->row();
    $excel = new PHPExcel();
    $excel->getProperties()->setCreator('SSP')
      ->setLastModifiedBy('SSP')
      ->setTitle($title);

    $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    $excel->getActiveSheet(0)->setTitle($title);
    $excel->setActiveSheetIndex(0);

    $excel->setActiveSheetIndex(0)->setCellValue('B2', $dl->nama_dealer);
    $excel->setActiveSheetIndex(0)->setCellValue('B3', $dl->alamat);
    $excel->setActiveSheetIndex(0)->setCellValue('B4', $dl->kecamatan . ' - ' . $dl->kabupaten);
    $excel->setActiveSheetIndex(0)->setCellValue('B5', $dl->provinsi);
    $excel->setActiveSheetIndex(0)->setCellValue('B6', $dl->no_telp);
    $excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

    $excel->setActiveSheetIndex(0)->setCellValue('B8', 'Kategori');
    $excel->setActiveSheetIndex(0)->setCellValue('D8', strtoupper($kategori));
    $excel->setActiveSheetIndex(0)->setCellValue('B9', 'Periode');
    $excel->setActiveSheetIndex(0)->setCellValue('D9', bulan_pjg($bulan) . ' ' . $tahun);
    $excel->setActiveSheetIndex(0)->getStyle('B8:B9')->getFont()->setBold(true);

    $excel->setActiveSheetIndex(0)->setCellValue('E8', 'Pencapaian');
    $excel->setActiveSheetIndex(0)->setCellValue('E9', 'Target');
    $excel->setActiveSheetIndex(0)->getStyle('E8:E9')->getFont()->setBold(true);

    $excel->setActiveSheetIndex(0)->setCellValue('B11', 'No');
    $excel->setActiveSheetIndex(0)->setCellValue('C11', 'Tipe');
    $excel->setActiveSheetIndex(0)->setCellValue('D11', 'Kategori');
    $excel->setActiveSheetIndex(0)->setCellValue('E11', 'Target(%)');
    $excel->setActiveSheetIndex(0)->setCellValue('F11', 'Aktual(%)');
    $excel->getActiveSheet()->getStyle("B11:F11")->applyFromArray([
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      ),
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
        )
      ),
    ]);
    $excel->setActiveSheetIndex(0)->getStyle('B11:F11')->getFont()->setBold(true);
    $excel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
    $excel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
    $excel->getActiveSheet()->getColumnDimension('D')->setWidth(27);
    $excel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
    $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);

    $f_csl = [
      'tahun' => $tahun,
      'bulan' => $bulan,
      'kategori' => $kategori,
      'id_dealer' => $id_dealer,
    ];
    $get_data = $this->m_csl->getDetailActualUpladCSL($f_csl)->result();
    $row = 12;
    $no = 1;
    $actuals = [];
    $targets = [];
    foreach ($get_data as $dt) {
      $f_csl['id_atribut'] = $dt->id_atribut;
      $f_csl['select'] = 'target_atribut';
      $target_csl = $this->m_csl->getDetailTargetListUpladCSL($f_csl)->row()->target;
      $targets[] = ROUND($target_csl);

      $excel->setActiveSheetIndex(0)->setCellValue("B$row", $no);
      $excel->setActiveSheetIndex(0)->setCellValue("C$row", $dt->tipe);
      $excel->setActiveSheetIndex(0)->setCellValue("D$row", $dt->nama_atribut);
      $excel->setActiveSheetIndex(0)->setCellValue("E$row", ROUND($target_csl));
      $excel->setActiveSheetIndex(0)->setCellValue("F$row", ROUND($dt->actual));
      $actuals[] = ROUND($dt->actual);

      $row++;
      $no++;
    }

    $actual = @ROUND(array_sum($actuals) / count($actuals));
    $target = @ROUND(array_sum($targets) / count($targets));
    $excel->setActiveSheetIndex(0)->setCellValue('F8', $actual);
    $excel->setActiveSheetIndex(0)->setCellValue('F9', $target);


    $excel->getActiveSheet()->mergeCells("B$row:D$row");
    $excel->setActiveSheetIndex(0)->setCellValue("B$row", 'Total');
    $excel->getActiveSheet()
      ->getStyle("B$row")
      ->getAlignment()
      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $excel->setActiveSheetIndex(0)->setCellValue("E$row", array_sum($targets));
    $excel->setActiveSheetIndex(0)->setCellValue("F$row", array_sum($actuals));
    $excel->setActiveSheetIndex(0)->getStyle("B$row:F$row")->getFont()->setBold(true);
    $excel->getActiveSheet()->getStyle("B11:F$row")->applyFromArray([
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
        )
      ),
    ]);

    $row += 2;
    $excel->setActiveSheetIndex(0)->setCellValue("B$row", 'Tanggal Generate');
    $excel->setActiveSheetIndex(0)->getStyle("B$row")->getFont()->setBold(true);
    $excel->setActiveSheetIndex(0)->setCellValue("D$row", waktu_full());
    $row++;
    $excel->setActiveSheetIndex(0)->setCellValue("B$row", 'Oleh');
    $excel->setActiveSheetIndex(0)->getStyle("B$row")->getFont()->setBold(true);
    $excel->setActiveSheetIndex(0)->setCellValue("D$row", $user->nama_lengkap);
    $nama_file = 'CSL-' . get_ymd();
    $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

    $path = 'uploads/document_csl/' . get_y() . '/' . $dl->kode_dealer_md . '/' . get_m() . '/' . get_d();
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }
    $path_file_name = $path . '/' . $nama_file . '.xlsx';
    if (file_exists(FCPATH . $path_file_name)) {
      unlink($path_file_name); //Hapus File
    }
    $write->save($path_file_name);
    $data = base_url($path_file_name);
    send_json(msg_sc_success($data));
  }
}
