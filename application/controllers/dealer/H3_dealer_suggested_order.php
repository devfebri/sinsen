<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class H3_dealer_suggested_order extends Honda_Controller {
	var $tables = "tr_h3_dealer_suggested_order";	
	var $folder = "dealer";
	var $page   = "h3_dealer_suggested_order";
	var $title  = "Create Suggested Order";

	public function __construct()
	{		
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
        $this->load->model('h3_analisis_ranking_model', 'analisis_ranking');
        $this->load->model('H3_md_ms_sim_part_model', 'sim_part');
        $this->load->model('h3_dealer_stock_model', 'dealer_stock');
        $this->load->model('h3_md_purchase_order_parts_model', 'purchase_parts_md');
        
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "index";
        $data['cek_dealer_auto']	= $this->db->select('autofulfillment_md')
                                               ->from('ms_dealer')
                                               ->where('id_dealer',$this->m_admin->cari_dealer())
                                               ->get()->row();

		$this->template($data);	
    }

    public function export(){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        $order_md = $this->db->select('sum(pop.kuantitas)')
        ->from('tr_h3_dealer_purchase_order_parts as pop')
        ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = pop.po_id')
        ->where('pop.id_part = mp.id_part')
        ->where('po.status', 'Processed by MD')
        ->group_by('pop.id_part')
        ->get_compiled_select();

        $stock = $this->db->select('sum(ds.stock)')
        ->from('ms_h3_dealer_stock as ds')
        ->where('ds.id_part = mp.id_part')
        ->group_by('ds.id_part')
        ->get_compiled_select();

        $this->db
        ->select('ar.*')
        ->select('mp.harga_md_dealer as harga_saat_dibeli')
        ->select('mp.nama_part')
        ->select('ar.adjusted_order as kuantitas')
        ->select('IFNULL(dmp.min_stok, 0) as min_stok')
        ->select('IFNULL(dmp.maks_stok, 0) as maks_stok')
        ->select("IFNULL(({$stock}), 0) as stock")
        ->select("IFNULL(({$order_md}), 0) as order_md")
        ->from('ms_h3_analisis_ranking as ar')
        ->join('ms_part as mp', 'mp.id_part = ar.id_part')
        ->join('ms_h3_dealer_master_part as dmp', 'dmp.id_part = ar.id_part', 'left');

        $data = $this->db->get()->result();

        // Load plugin PHPExcel nya
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        // Panggil class PHPExcel nya

        $excel = new PHPExcel();
        // Settingan awal fil excel
        $excel->getProperties()->setCreator('SSP')
                    ->setLastModifiedBy('SSP')
                    ->setTitle("Suggested ORder");

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
            'font' => array(
                'bold' => true
            ), // Set font nya jadi bold
            'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
        'alignment' => array(

          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)

        ),

        'borders' => array(

          'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis

          'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis

          'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis

          'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis

        )

      );

  





        $excel->setActiveSheetIndex(0)->setCellValue('A1', 'ID Part');

        $excel->setActiveSheetIndex(0)->setCellValue('B1', 'Nama Part');

        $excel->setActiveSheetIndex(0)->setCellValue('C1', 'Rata - rata 6 minggu');

        $excel->setActiveSheetIndex(0)->setCellValue('D1', 'Akumulasi Qty');

        $excel->setActiveSheetIndex(0)->setCellValue('E1', 'Akumulasi %');

        $excel->setActiveSheetIndex(0)->setCellValue('F1', 'Rank');

        $excel->setActiveSheetIndex(0)->setCellValue('G1', 'W1');

        $excel->setActiveSheetIndex(0)->setCellValue('H1', 'W2');

        $excel->setActiveSheetIndex(0)->setCellValue('I1', 'W3');

        $excel->setActiveSheetIndex(0)->setCellValue('J1', 'W4');

        $excel->setActiveSheetIndex(0)->setCellValue('K1', 'W5');

        $excel->setActiveSheetIndex(0)->setCellValue('L1', 'W6');

        $excel->setActiveSheetIndex(0)->setCellValue('M1', 'Stock On Hand');

        $excel->setActiveSheetIndex(0)->setCellValue('N1', 'SIM Part');

        $excel->setActiveSheetIndex(0)->setCellValue('O1', 'Stock In Transit');

        $excel->setActiveSheetIndex(0)->setCellValue('P1', 'Suggested Order');



        $excel->getActiveSheet()->getStyle('A1')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('B1')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('C1')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('D1')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('E1')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('F1')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('G1')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('H1')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('I1')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('J1')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('K1')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('L1')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('M1')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('N1')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('O1')->applyFromArray($style_col);

        $excel->getActiveSheet()->getStyle('P1')->applyFromArray($style_col);



        $excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

        $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

        $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

        $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

        $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

        $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

        $excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

        $excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

        $excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

        $excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

        $excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

        $excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);

        $excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);

        $excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);

        $excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);

        $excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);





        $kolom = 2;

        $nomor = 1;

        foreach($data as $each) {



            $excel->setActiveSheetIndex(0)->setCellValue('A' . $kolom, $each->id_part);

            $excel->setActiveSheetIndex(0)->setCellValue('B' . $kolom, $each->nama_part);

            $excel->setActiveSheetIndex(0)->setCellValue('C' . $kolom, $each->avg_six_weeks);

            $excel->setActiveSheetIndex(0)->setCellValue('D' . $kolom, $each->akumulasi_qty);

            $excel->setActiveSheetIndex(0)->setCellValue('E' . $kolom, $each->akumulasi_persen);

            $excel->setActiveSheetIndex(0)->setCellValue('F' . $kolom, $each->rank);

            $excel->setActiveSheetIndex(0)->setCellValue('G' . $kolom, $each->w1);

            $excel->setActiveSheetIndex(0)->setCellValue('H' . $kolom, $each->w2);

            $excel->setActiveSheetIndex(0)->setCellValue('I' . $kolom, $each->w3);

            $excel->setActiveSheetIndex(0)->setCellValue('J' . $kolom, $each->w4);

            $excel->setActiveSheetIndex(0)->setCellValue('K' . $kolom, $each->w5);

            $excel->setActiveSheetIndex(0)->setCellValue('L' . $kolom, $each->w6);

            $excel->setActiveSheetIndex(0)->setCellValue('M' . $kolom, $each->min_stok);

            $excel->setActiveSheetIndex(0)->setCellValue('N' . $kolom, $each->stock);

            $excel->setActiveSheetIndex(0)->setCellValue('O' . $kolom, $each->order_md);

            $excel->setActiveSheetIndex(0)->setCellValue('P' . $kolom, $each->suggested_order);



            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)

      $excel->getActiveSheet()->getStyle('A'.$kolom)->applyFromArray($style_row);

      $excel->getActiveSheet()->getStyle('B'.$kolom)->applyFromArray($style_row);

      $excel->getActiveSheet()->getStyle('C'.$kolom)->applyFromArray($style_row);

      $excel->getActiveSheet()->getStyle('D'.$kolom)->applyFromArray($style_row);

      $excel->getActiveSheet()->getStyle('E'.$kolom)->applyFromArray($style_row);

      $excel->getActiveSheet()->getStyle('F'.$kolom)->applyFromArray($style_row);

      $excel->getActiveSheet()->getStyle('G'.$kolom)->applyFromArray($style_row);

      $excel->getActiveSheet()->getStyle('H'.$kolom)->applyFromArray($style_row);

      $excel->getActiveSheet()->getStyle('I'.$kolom)->applyFromArray($style_row);

      $excel->getActiveSheet()->getStyle('J'.$kolom)->applyFromArray($style_row);

      $excel->getActiveSheet()->getStyle('K'.$kolom)->applyFromArray($style_row);

      $excel->getActiveSheet()->getStyle('L'.$kolom)->applyFromArray($style_row);

      $excel->getActiveSheet()->getStyle('M'.$kolom)->applyFromArray($style_row);

      $excel->getActiveSheet()->getStyle('N'.$kolom)->applyFromArray($style_row);

      $excel->getActiveSheet()->getStyle('O'.$kolom)->applyFromArray($style_row);

      $excel->getActiveSheet()->getStyle('P'.$kolom)->applyFromArray($style_row);



            $kolom++;

            $nomor++;



        }



        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)

        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE

        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya

        $excel->getActiveSheet(0)->setTitle("Suggested Order");

        $excel->setActiveSheetIndex(0);



        // Proses file excel

        ob_end_clean();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment; filename="suggested_order.xlsx"'); // Set nama file excel nya

        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $write->save('php://output');

        ob_end_clean();

    }

	public function adjust_order(){
		$this->db->trans_start();
		$this->analisis_ranking->update($this->input->post(['adjusted_order']), [
			'id_dealer' => $this->m_admin->cari_dealer(),
			'id_part' => $this->input->post('id_part')
		]);
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$this->output->set_status_header(200);
			$adjusted_order = $this->analisis_ranking->get([
				'id_dealer' => $this->m_admin->cari_dealer(),
				'id_part' => $this->input->post('id_part')
			], true);
			send_json($adjusted_order);
		}else{
			$this->output->set_status_header(500);
		}

	}

	public function generate_suggested_order(){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        
        $start_point = date('Y-m-d', strtotime($this->input->get('tanggal_start_periode')));
		$tujuhHari = 604800;
        $weekSalesQuery = [];
        for ($i=0; $i < 6; $i++) { 
            $sub_start = $i * 7;
            $sub_end = ($i + 1) * 7;

            $date_start_point = date('Y-m-d', strtotime("- {$sub_start} day", strtotime($start_point)));
            $date_end_point = date('Y-m-d', strtotime("- {$sub_end} day", strtotime($start_point)));

            $weekSalesQuery[] = $this->db
            ->select('SUM(dsop.kuantitas)', false)
            ->from('tr_h3_dealer_sales_order as dso')
            ->join('tr_h3_dealer_sales_order_parts as dsop', 'dso.id = dsop.nomor_so_int')
            // ->where("dso.tanggal_so between '{$date_start_point}' and '{$date_end_point}'", null, false)
            ->group_start()
            ->where("dso.tanggal_so <= subdate('{$start_point}', {$sub_start})")
            ->where("dso.tanggal_so > subdate('{$start_point}', {$sub_end})")
            ->group_end()
            // ->where('p.id_part_int = 20109', null, false)
            ->where('dsop.id_part_int = p.id_part_int', null, false)
            ->where('dso.id_dealer', $this->m_admin->cari_dealer())
            ->get_compiled_select();
        }

        $kode_part_yang_terjual = $this->db
        ->select('DISTINCT(dsop.id_part_int) as id_part_int')
        ->from('tr_h3_dealer_sales_order as dso')
        ->join('tr_h3_dealer_sales_order_parts as dsop', 'dso.id = dsop.nomor_so_int')
        ->join('ms_part as p', 'p.id_part_int = dsop.id_part_int')
        ->group_start()
        ->where("tanggal_so <= subdate('{$start_point}', 0)")
        ->where("tanggal_so > subdate('{$start_point}', 6 * 7)")
        ->group_end()
        ->where('dso.id_dealer', $this->m_admin->cari_dealer())
        ->where('p.fix', 1)
        ->get()->result_array();
        $kode_part_yang_terjual = array_map(function($data){
            return $data['id_part_int'];
        }, $kode_part_yang_terjual);

        $date_start_point = date('Y-m-d', strtotime("- {$sub_start} day", strtotime($start_point)));
        $date_end_point = date('Y-m-d', strtotime("- {$sub_end} day", strtotime($start_point)));

        $total_six_weeks = $this->db
        ->select('SUM(dsop.kuantitas)', false)
        ->from('tr_h3_dealer_sales_order as dso')
        ->join('tr_h3_dealer_sales_order_parts as dsop', 'dso.id = dsop.nomor_so_int')
        // ->where("dso.tanggal_so between '{$date_start_point}' and '{$date_end_point}'", null, false)
        ->group_start()
        ->where("tanggal_so <= subdate('{$start_point}', 0)")
        ->where("tanggal_so > subdate('{$start_point}', 6 * 7)")
        ->group_end()
        ->where('p.id_part_int = dsop.id_part_int', null, false)
        ->where('dso.id_dealer', $this->m_admin->cari_dealer())
        ->get_compiled_select();

        // $order_md = $this->db->select('sum(plp.qty_disiapkan)')
        // ->from('tr_h3_dealer_purchase_order_parts as pop')
        // ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = pop.po_id')
        // ->join('tr_h3_md_sales_order as so', 'so.id_ref = po.po_id')
        // ->join('tr_h3_md_do_sales_order as dso', 'dso.id_sales_order = so.id_sales_order')
        // ->join('tr_h3_md_picking_list as pl', 'pl.id_ref = dso.id_do_sales_order')
        // ->join('tr_h3_md_picking_list_parts as plp', 'pl.id_picking_list = plp.id_picking_list')
        // ->join('tr_h3_md_packing_sheet as ps', 'pl.id_picking_list = ps.id_picking_list')
        // ->where('ps.no_faktur', null)
        // ->where('pop.id_part = p.id_part')
        // ->where('po.status', 'Processed by MD')
        // ->where('po.id_dealer', $this->m_admin->cari_dealer())
        // ->group_by('pop.id_part')
        // ->get_compiled_select();

        $order_md = $this->purchase_parts_md->qty_on_order_md($this->m_admin->cari_dealer(), 'p.id_part', true);

        // $stock = $this->db->select('sum(ds.stock)')
        // ->from('ms_h3_dealer_stock as ds')
        // ->where('ds.id_part = p.id_part')
        // ->where('ds.id_dealer', $this->m_admin->cari_dealer())
        // ->group_by('ds.id_part')
        // ->get_compiled_select();

        $stock = $this->dealer_stock->qty_on_hand($this->m_admin->cari_dealer(), 'p.id_part', null, null, true);

        $adjust_order = $this->db
        ->select('msp.adjusted_order')
        ->from('ms_h3_dealer_master_part as msp')
        ->where('msp.id_part = p.id_part')
        ->where('msp.id_dealer', $this->m_admin->cari_dealer())
        ->get_compiled_select();

        $sim_part = $this->db->select('jpp.qty_sim_part')
        ->from('ms_jumlah_pit_dealers as jpd')
        ->join('ms_jumlah_pit_parts as jpp', 'jpp.id_jumlah_pit = jpd.id_jumlah_pit')
        ->join('ms_jumlah_pit as jp', '(jp.id = jpd.id_jumlah_pit and jp.active = 1)')
        ->where('jpd.id_dealer', $this->m_admin->cari_dealer())
        ->where('jpp.id_part = p.id_part')
        ->get_compiled_select();

        $sim_part = $this->sim_part->qty_sim_part($this->m_admin->cari_dealer(), 'p.id_part_int', true);

        $index = 1;
        foreach ($weekSalesQuery as $query) {
            $this->db->select("IFNULL(({$query}), 0) as w$index");
            $index++;
        }

        $this->db
        ->select("ifnull( ({$total_six_weeks}) ,0) as total_six_weeks")
        ->select("ifnull( ( ({$total_six_weeks}) / 6 ) ,0) as avg_six_weeks")
        ->select("IFNULL(({$stock}), 0) as stock")
        ->select("IFNULL(({$order_md}), 0) as order_md")
        ->select("IFNULL(({$sim_part}), 0) as sim_part")
        ->select('p.id_part_int')
        ->select('p.id_part')
        ->from('ms_part as p')
        ->group_start()
        ->where('p.fix', 1)
        ;
        if(count($kode_part_yang_terjual) > 0){
            $this->db->or_where_in('p.id_part_int', $kode_part_yang_terjual);
        }
        $this->db
        ->group_end()
        ->order_by('avg_six_weeks', 'DESC');
        $result = $this->db->get()->result();

        $total = 0;
        foreach ($result as $each) {
            $total += (int) $each->avg_six_weeks;
        }

        $data = array();
        $akumulasi = 0;
		$index = 1;
        $id_dealer = $this->m_admin->cari_dealer();
        foreach ($result as $rs) {
			$sub_array = (array)$rs;
			$sub_array['id_dealer'] = $id_dealer;
            $akumulasi += (int) $rs->avg_six_weeks;
            $sub_array['akumulasi_qty'] = $akumulasi;
            if(((int) $rs->total_six_weeks) == 0){
                $persen = 0;
            }else{
                $persen = ($sub_array['akumulasi_qty'] / $total) * 100;
            }
            
            $persen = round($persen);

			if($rs->avg_six_weeks == 0){
                $sub_array['rank'] = 'E';
            }else if($persen >= 0 AND $persen <= 80){
                $sub_array['rank'] = 'A';
            }else if($persen >= 80 AND $persen <= 90){
                $sub_array['rank'] = 'B';
            }else if($persen >= 90 AND $persen <= 95){
                $sub_array['rank'] = 'C';
            }else if($persen >= 95 AND $persen <= 100){
                $sub_array['rank'] = 'D';
			}

			if($sub_array['rank'] == 'A'){
				$sub_array['status'] = 'Very Fast Moving';
			}else if($sub_array['rank'] == 'B'){
				$sub_array['status'] = 'Fast Moving';
			}else if($sub_array['rank'] == 'C'){
				$sub_array['status'] = 'Slow Moving';
			}else if($sub_array['rank'] == 'D'){
				$sub_array['status'] = 'Very Slow Moving';
			}else if($sub_array['rank'] == 'E'){
				$sub_array['status'] = 'Dead Stock';
			}

            $sub_array['akumulasi_persen'] = (float) number_format($persen, 2);
            $sub_array['suggested_order'] = (($rs->avg_six_weeks - $rs->stock - $rs->order_md) + $rs->avg_six_weeks);

            if($sub_array['suggested_order'] <= 0){
                $sub_array['suggested_order'] = 0;
            }else if($sub_array['suggested_order'] < $rs->sim_part){
                $sub_array['suggested_order'] = $rs->sim_part;
            }

            if($rs->total_six_weeks > 0){
                $six_weeks = $rs->avg_six_weeks / 42;
                if($six_weeks == 0){
                    $stock_days = $rs->stock;
                }else{
                    $stock_days = $rs->stock / $six_weeks;
                }
                $sub_array['stock_days'] =  ( $stock_days );
            }else{
                $sub_array['stock_days'] = 0;
            }

            $sub_array['stock_days'] = round($sub_array['stock_days']);

            $sub_array['adjusted_order'] = $sub_array['suggested_order'];

			unset($sub_array['total_six_weeks']);
			unset($sub_array['sim_part']);
			unset($sub_array['stock']);
			unset($sub_array['order_md']);
            $data[] = $sub_array;
			$index++;
        }

		$this->db->trans_start();
		$this->analisis_ranking->delete($id_dealer, 'id_dealer');
		$this->analisis_ranking->insert_batch($data);
		$this->db->trans_complete();

		return $this->db->trans_status();
	}

    public function active_autofulfillment(){
        $id_dealer = $this->m_admin->cari_dealer();

        $this->db->trans_begin();
        $this->db->set('autofulfillment_md',1)
                 ->where('id_dealer',$id_dealer)
                 ->update('ms_dealer');

        if ($this->db->trans_status() == true) {
           $this->db->trans_commit();
           $result = [
               'status' => true,
               'message' => 'success',
               'data' => []
           ];
        } else {
            $this->db->trans_rollback();
            $result = [
                'status' => false,
                'message' => 'failed',
                'data' => []
            ];
        }
    
        echo json_encode($result);
    }

    public function non_active_autofulfillment(){
        $id_dealer = $this->m_admin->cari_dealer();

        $this->db->trans_begin();
        $this->db->set('autofulfillment_md',0)
                 ->where('id_dealer',$id_dealer)
                 ->update('ms_dealer');

        if ($this->db->trans_status() == true) {
           $this->db->trans_commit();
           $result = [
               'status' => true,
               'message' => 'success',
               'data' => []
           ];
        } else {
            $this->db->trans_rollback();
            $result = [
                'status' => false,
                'message' => 'failed',
                'data' => []
            ];
        }
    
        echo json_encode($result);
    }
}