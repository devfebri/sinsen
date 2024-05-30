<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_tanda_terima_kpb extends CI_Controller
{

  var $folder = "h2/laporan";
  var $page   = "h2_tanda_terima_kpb";
  var $title  = "Tanda Terima KPB";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_md_laporan', 'm_lap');
    $this->load->helper('romawi');
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
      $data['title'] = $this->title;
      $data['params'] = $params;
      // send_json($data);
      $filter = [
        'tgl_awal' => $params->tgl_awal,
        'tgl_akhir' => $params->tgl_akhir,
        'id_dealer' => $params->id_dealer,
      ];

      $data['dealer'] = $this->m_lap->getDealer(['id_dealer' => $params->id_dealer])->row();
      $data['details'] = $this->m_lap->getLaporanTandaTerimaKPB($filter);
      if ($params->tipe == 'preview') {
        $this->load->library('pdf');
        $mpdf                           = $this->pdf->load();
        $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
        $mpdf->charset_in               = 'UTF-8';
        $mpdf->autoLangToFont           = true;

        // $mpdf->AddPage('L');
        $html = $this->load->view($this->folder . '/' . $this->page, $data, true);
        $mpdf->WriteHTML($html);
        $output = $this->page . '.pdf';
        $mpdf->Output("$output", 'I');
      } else {
        $this->_excell($data);
      }
    } else {
      $data['isi']    = $this->page;
      $data['title']  = $this->title;
      $data['set']    = "view";
      $this->template($data);
    }
  }

  function _excell($data)
  {
    $this->load->helper('tgl_indo');
    $details = $data['details'];
    $title = "TANDA TERIMA KPB";
    include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
    $excel = new PHPExcel();
    $excel->getProperties()->setCreator('SSP')
      ->setLastModifiedBy('SSP')
      ->setTitle($title);

    $excel->setActiveSheetIndex(0)->setCellValue('A1', $title);
    $excel->getActiveSheet()->mergeCells("A1:K1");
    $excel->getActiveSheet()->getStyle("A1")->applyFromArray([
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
      ),
    ]);

    $excel->setActiveSheetIndex(0)->setCellValue('A2', 'Periode : ' . $data['params']->tgl_awal . ' - ' . $data['params']->tgl_akhir);
    $excel->getActiveSheet()->mergeCells("A2:K2");
    $excel->getActiveSheet()->getStyle("A2")->applyFromArray([
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
      ),
    ]);

    $excel->setActiveSheetIndex(0)->setCellValue('A3', 'NAMA AHASS : ' . $data['dealer']->nama_dealer);
    $excel->getActiveSheet()->mergeCells("A3:K3");
    $excel->getActiveSheet()->getStyle("A3")->applyFromArray([
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
      ),
    ]);

    $excel->setActiveSheetIndex(0)->setCellValue('A4', 'NOMOR AHASS : ' . $data['dealer']->kode_dealer_md);
    $excel->getActiveSheet()->mergeCells("A4:K4");
    $excel->getActiveSheet()->getStyle("A4")->applyFromArray([
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
      ),
    ]);

    $row = 6;
    foreach ($details['details'] as $dtl5) {
      $row_awal = $row;
      $excel->setActiveSheetIndex(0)->setCellValue("A$row", $dtl5['no_mesin_5']);
      $excel->getActiveSheet()->mergeCells("A$row:K$row");
      $excel->getActiveSheet()->getStyle("A$row")->applyFromArray([
        'alignment' => array(
          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ),
      ]);
      $excel->getActiveSheet()->getStyle("A$row:K$row")->applyFromArray([
        'alignment' => array(
          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        ),
      ]);
      $row++; //6
      $row_header_detail = $row;
      $excel->setActiveSheetIndex(0)->setCellValue("A$row", "KPB");
      $excel->setActiveSheetIndex(0)->setCellValue("C$row", "Jasa");
      $excel->setActiveSheetIndex(0)->setCellValue("D$row", "Keunt. Oli");
      $excel->setActiveSheetIndex(0)->setCellValue("E$row", "Oli");
      $excel->setActiveSheetIndex(0)->setCellValue("F$row", "AHASS");
      $excel->getActiveSheet()->mergeCells("F$row:K$row");

      $row++; // 7
      $excel->getActiveSheet()->mergeCells("A$row_header_detail:B$row");
      $excel->getActiveSheet()->mergeCells("C$row_header_detail:C" . $row);
      $excel->getActiveSheet()->mergeCells("D$row_header_detail:D" . $row);
      $excel->getActiveSheet()->mergeCells("E$row_header_detail:E" . $row);
      $excel->setActiveSheetIndex(0)->setCellValue("F$row", "Jasa");
      $excel->setActiveSheetIndex(0)->setCellValue("G$row", "Insentif Oli");
      $excel->setActiveSheetIndex(0)->setCellValue("H$row", "Oli");
      $excel->setActiveSheetIndex(0)->setCellValue("I$row", "PPN");
      $excel->setActiveSheetIndex(0)->setCellValue("J$row", "PPH");
      $excel->setActiveSheetIndex(0)->setCellValue("K$row", "Sub Total");

      $excel->getActiveSheet()->getStyle("A$row_header_detail:K$row")->applyFromArray([
        'alignment' => array(
          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ),
      ]);
      $row++; //8
      $tot_jasa = 0;
      $tot_insentif_oli = 0;
      $tot_oli = 0;
      $tot_ppn = 0;
      $tot_pph = 0;
      $tot_sub_total = 0;
      foreach ($dtl5['kpb'] as $kpb) {
        $tot_jasa           += $kpb['tot_jasa'];
        $tot_insentif_oli   += $kpb['tot_insentif_oli'];
        $tot_oli            += $kpb['tot_oli'];
        $tot_ppn            += $kpb['ppn'];
        $tot_pph            += $kpb['pph'];
        $tot_sub_total      += $kpb['sub_total'];

        $excel->setActiveSheetIndex(0)->setCellValue("A$row", 'KPB ' . dec_romawi($kpb['kpb']));
        $excel->setActiveSheetIndex(0)->setCellValue("B$row", $kpb['qty']);
        $excel->setActiveSheetIndex(0)->setCellValue("C$row", $kpb['harga_jasa']);
        $excel->setActiveSheetIndex(0)->setCellValue("D$row", $kpb['insentif_oli']);
        $excel->setActiveSheetIndex(0)->setCellValue("E$row", $kpb['harga_material']);
        $excel->setActiveSheetIndex(0)->setCellValue("F$row", $kpb['tot_jasa']);
        $excel->setActiveSheetIndex(0)->setCellValue("G$row", $kpb['tot_insentif_oli']);
        $excel->setActiveSheetIndex(0)->setCellValue("H$row", $kpb['tot_oli']);
        $excel->setActiveSheetIndex(0)->setCellValue("I$row", $kpb['ppn']);
        $excel->setActiveSheetIndex(0)->setCellValue("J$row", $kpb['pph']);
        $excel->setActiveSheetIndex(0)->setCellValue("K$row", $kpb['sub_total']);

        $excel->getActiveSheet()->getStyle("C$row:K$row")->getNumberFormat()->setFormatCode('#,##0');
        $excel->getActiveSheet()->getStyle("C$row:K$row")->applyFromArray([
          'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
          ),
        ]);
        $row++;
      }

      $excel->setActiveSheetIndex(0)->setCellValue("A$row", 'Total');
      $excel->getActiveSheet()->mergeCells("A$row:E$row");
      $excel->setActiveSheetIndex(0)->setCellValue("F$row", $tot_jasa);
      $excel->setActiveSheetIndex(0)->setCellValue("G$row", $tot_insentif_oli);
      $excel->setActiveSheetIndex(0)->setCellValue("H$row", $tot_oli);
      $excel->setActiveSheetIndex(0)->setCellValue("I$row", $tot_ppn);
      $excel->setActiveSheetIndex(0)->setCellValue("J$row", $tot_pph);
      $excel->setActiveSheetIndex(0)->setCellValue("K$row", $tot_sub_total);

      $excel->getActiveSheet()->getStyle("F$row:K$row")->getNumberFormat()->setFormatCode('#,##0');
      $excel->getActiveSheet()->getStyle("F$row:K$row")->applyFromArray([
        'alignment' => array(
          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        ),
      ]);

      // Atur garis
      $excel->getActiveSheet()->getStyle("A$row_awal:K$row")->applyFromArray([
        'borders' => array(
          'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
          )
        ),
      ]);
      $row++;
    }

    // Penentuan Total All
    $row++;
    $row_awal = $row;
    $excel->setActiveSheetIndex(0)->setCellValue("A$row", 'TOTAL ALL');
    $excel->getActiveSheet()->mergeCells("A$row:K$row");
    $excel->getActiveSheet()->getStyle("A$row")->applyFromArray([
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
      ),
    ]);
    $excel->getActiveSheet()->getStyle("A$row:K$row")->applyFromArray([
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      ),
    ]);
    $row++; //6
    $row_header_detail = $row;
    $excel->setActiveSheetIndex(0)->setCellValue("A$row", "KPB");
    $excel->setActiveSheetIndex(0)->setCellValue("C$row", "Jasa");
    $excel->setActiveSheetIndex(0)->setCellValue("D$row", "Keunt. Oli");
    $excel->setActiveSheetIndex(0)->setCellValue("E$row", "Oli");
    $excel->setActiveSheetIndex(0)->setCellValue("F$row", "AHASS");
    $excel->getActiveSheet()->mergeCells("F$row:K$row");

    $row++; // 7
    $excel->getActiveSheet()->mergeCells("A$row_header_detail:B$row");
    $excel->getActiveSheet()->mergeCells("C$row_header_detail:C" . $row);
    $excel->getActiveSheet()->mergeCells("D$row_header_detail:D" . $row);
    $excel->getActiveSheet()->mergeCells("E$row_header_detail:E" . $row);
    $excel->setActiveSheetIndex(0)->setCellValue("F$row", "Jasa");
    $excel->setActiveSheetIndex(0)->setCellValue("G$row", "Insentif Oli");
    $excel->setActiveSheetIndex(0)->setCellValue("H$row", "Oli");
    $excel->setActiveSheetIndex(0)->setCellValue("I$row", "PPN");
    $excel->setActiveSheetIndex(0)->setCellValue("J$row", "PPH");
    $excel->setActiveSheetIndex(0)->setCellValue("K$row", "Sub Total");

    $excel->getActiveSheet()->getStyle("A$row_header_detail:K$row")->applyFromArray([
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
      ),
    ]);
    $row++; //8
    $tot_jasa = 0;
    $tot_insentif_oli = 0;
    $tot_oli = 0;
    $tot_ppn = 0;
    $tot_pph = 0;
    $tot_sub_total = 0;
    foreach ($details['total_all'] as $kpb) {
      $tot_jasa           += $kpb['tot_jasa'];
      $tot_insentif_oli   += $kpb['tot_insentif_oli'];
      $tot_oli            += $kpb['tot_oli'];
      $tot_ppn            += $kpb['ppn'];
      $tot_pph            += $kpb['pph'];
      $tot_sub_total      += $kpb['sub_total'];
      $excel->setActiveSheetIndex(0)->setCellValue("A$row", 'KPB ' . dec_romawi($kpb['kpb']));
      $excel->setActiveSheetIndex(0)->setCellValue("B$row", $kpb['qty']);
      $excel->setActiveSheetIndex(0)->setCellValue("C$row", '-');
      $excel->setActiveSheetIndex(0)->setCellValue("D$row", '-');
      $excel->setActiveSheetIndex(0)->setCellValue("E$row", '-');
      $excel->setActiveSheetIndex(0)->setCellValue("F$row", $kpb['tot_jasa']);
      $excel->setActiveSheetIndex(0)->setCellValue("G$row", $kpb['tot_insentif_oli']);
      $excel->setActiveSheetIndex(0)->setCellValue("H$row", $kpb['tot_oli']);
      $excel->setActiveSheetIndex(0)->setCellValue("I$row", $kpb['ppn']);
      $excel->setActiveSheetIndex(0)->setCellValue("J$row", $kpb['pph']);
      $excel->setActiveSheetIndex(0)->setCellValue("K$row", $kpb['sub_total']);

      $excel->getActiveSheet()->getStyle("C$row:K$row")->getNumberFormat()->setFormatCode('#,##0');
      $excel->getActiveSheet()->getStyle("C$row:K$row")->applyFromArray([
        'alignment' => array(
          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        ),
      ]);
      $row++;
    }
    $excel->setActiveSheetIndex(0)->setCellValue("A$row", 'Sub Total');
    $excel->getActiveSheet()->mergeCells("A$row:E$row");
    $excel->setActiveSheetIndex(0)->setCellValue("F$row", $tot_jasa);
    $excel->setActiveSheetIndex(0)->setCellValue("G$row", $tot_insentif_oli);
    $excel->setActiveSheetIndex(0)->setCellValue("H$row", $tot_oli);
    $excel->setActiveSheetIndex(0)->setCellValue("I$row", $tot_ppn);
    $excel->setActiveSheetIndex(0)->setCellValue("J$row", $tot_pph);
    $excel->setActiveSheetIndex(0)->setCellValue("K$row", $tot_sub_total);

    $excel->getActiveSheet()->getStyle("C$row:K$row")->getNumberFormat()->setFormatCode('#,##0');
    $excel->getActiveSheet()->getStyle("C$row:K$row")->applyFromArray([
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
      ),
    ]);

    // Atur garis
    $excel->getActiveSheet()->getStyle("A$row_awal:K$row")->applyFromArray([
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
        )
      ),
    ]);
    $row++;
    $row++;

    // Penetuan Pencairan KPB Dalam Bentuk Oli
    $excel->setActiveSheetIndex(0)->setCellValue("A$row", "Pencairan KPB Dalam Bentuk Oli :");
    $row++;
    // send_json($details['tipe_5']);
    foreach ($details['tipe_5'] as $tp5) {
      $excel->setActiveSheetIndex(0)->setCellValue("A$row", $tp5['nama_tipe']);
      $excel->setActiveSheetIndex(0)->setCellValue("E$row", "{$tp5['tot_qty']} Botol = ");
      $excel->setActiveSheetIndex(0)->setCellValue("F$row", "{$tp5['dus']} Dus, ");
      $excel->setActiveSheetIndex(0)->setCellValue("G$row", "{$tp5['botol']} Dus, ");
      $excel->getActiveSheet()->mergeCells("A$row:D$row");
      $row++;
    }

    // Penetuan Pencairan KPB Dalam Bentuk Uang
    $excel->setActiveSheetIndex(0)->setCellValue("A$row", "Pencairan KPB Dalam Bentuk Uang :");
    $excel->getActiveSheet()->mergeCells("A$row:D$row");
    $excel->setActiveSheetIndex(0)->setCellValue("E$row", $tot_sub_total);
    $excel->getActiveSheet()->getStyle("E$row")->getNumberFormat()->setFormatCode('#,##0');

    $row++;
    $excel->setActiveSheetIndex(0)->setCellValue("G$row", 'JAMBI, ' . tgl_indo(get_ymd()));
    $row++;
    $excel->setActiveSheetIndex(0)->setCellValue("A$row", 'MENGETAHUI');
    $excel->setActiveSheetIndex(0)->setCellValue("D$row", 'YANG MENYERAHKAN');
    $excel->setActiveSheetIndex(0)->setCellValue("G$row", 'YANG MEMBUAT');
    $row += 4;
    $excel->setActiveSheetIndex(0)->setCellValue("A$row", 'NOVITA SARI');
    $excel->setActiveSheetIndex(0)->setCellValue("G$row", 'EVI CHUSTINA');
    $row++;
    $excel->setActiveSheetIndex(0)->setCellValue("A$row", 'FINANCE');
    $excel->setActiveSheetIndex(0)->setCellValue("G$row", 'ADM. PKB');


    $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    $excel->getActiveSheet(0)->setTitle($title);
    $excel->setActiveSheetIndex(0);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $nama_file = 'tanda_terima_kpb' . '-' . strtotime(get_ymd());
    header('Content-Disposition: attachment; filename="' . $nama_file . '.xlsx"'); // Set nama file excel nya
    header('Cache-Control: max-age=0');
    $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $write->save('php://output');
  }

  function download_kpb(){
		$start_date = $this->input->get('started');
		$end_date = $this->input->get('ended');
		$id_dealer = $this->input->get('id_dealer');

		if($start_date =='' and $end_date==''){
			$start_date = date('Y-m-01');
			$end_date = date('Y-m-t');  
		}

    if($id_dealer !=''){
      $dealer = "and a.id_dealer=$id_dealer"; 
    }else{
      $dealer = '';
    }

    $data['start_date'] = $start_date;
    $data['end_date'] = $end_date;

    $data['list_dealer'] = $this->db->query("
      select a.created_at as tgl_input, 'E20' as kode_md, b.kode_dealer_ahm , a.no_mesin , c.no_mesin as digit, no_kpb , tgl_beli_smh , kpb_ke , km_service , tgl_service 
      from tr_claim_kpb a 
      join ms_dealer b on a.id_dealer = b.id_dealer 
      join ms_tipe_kendaraan c on a.id_tipe_kendaraan = c.id_tipe_kendaraan 
      where tgl_service BETWEEN '$start_date' and '$end_date' $dealer order by a.created_at asc
    ")->result();
    
    $this->load->view('h2/laporan/laporan_inputan_claim_kpb',$data);
  }
}
