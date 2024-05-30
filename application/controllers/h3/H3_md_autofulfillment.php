<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class H3_md_autofulfillment extends Honda_Controller {
	var $tables = "tr_h3_md_autofulfillment";	
	var $folder = "h3";
	var $page   = "h3_md_autofulfillment";
	var $title  = "Autofulfillment";

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
        $this->load->model('H3_md_autofulfillment_model', 'autofulfillment_model');
        $this->load->model('h3_dealer_purchase_order_model', 'purchase_order');		
		$this->load->model('h3_dealer_purchase_order_parts_model', 'purchase_order_parts');	
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
        $data['dt_dealer'] = $this->autofulfillment_model->getDataDealer();	
        // $data['kelompok_part_hgp'] = $this->autofulfillment_model->getDataKelompokPartHGP();													
		$data['set']	= "index"; 
		$this->template($data);	
    }

	public function save_data(){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $periode_start = $this->input->post('tanggal_start_periode');
        $tipe_waktu =$this->input->post('tipe_waktu');
        $pilih_ahass = $this->input->post('pilih_ahass');
        $kel_part_filter =$this->input->post('kel_part_filter');
        $kelompok_part_besar =$this->input->post('kelompok_part_besar');
        $kategori_sim_part= $this->input->post('kategori_sim_part');
        $jenis_po= $this->input->post('jenis_po');
        $thelist = $kelompok_part_besar;

        $this->db->trans_begin();
        if($tipe_waktu=='week'){
            $start_point = date('Y-m-d', strtotime($this->input->post('tanggal_start_periode')));
            $tujuhHari = 604800;
            $weekSalesQuery = [];
            for ($i=0; $i < 6; $i++) { 
                $sub_start = $i * 7;
                $sub_end = ($i + 1) * 7;

                $weekSalesQuery[] = $this->db
                ->select('SUM(dsop.kuantitas)', false)
                ->from('tr_h3_dealer_sales_order as dso')
                ->join('tr_h3_dealer_sales_order_parts as dsop', 'dso.id = dsop.nomor_so_int')
                // ->join('ms_h3_md_setting_kelompok_produk as kp','kp.id_kelompok_part=p.kelompok_part')
                // ->where("dso.tanggal_so between '{$date_start_point}' and '{$date_end_point}'", null, false)
                ->group_start()
                ->where("dso.tanggal_so <= subdate('{$start_point}', {$sub_start})")
                ->where("dso.tanggal_so > subdate('{$start_point}', {$sub_end})")
                ->group_end()
                // ->where('p.id_part_int = 20109', null, false)
                ->where('kp.produk ', $thelist)
                ->where('dsop.id_part_int = p.id_part_int', null, false)
                ->where('dso.id_dealer', $pilih_ahass)
                ->where('dso.status', 'Closed')
                ->get_compiled_select();        
            }
    
            // $date_start_point = date('Y-m-d', strtotime("- {$sub_start} day", strtotime($start_point)));
            // $date_end_point = date('Y-m-d', strtotime("- {$sub_end} day", strtotime($start_point)));
    
            $total_six_weeks = $this->db
            ->select('SUM(dsop.kuantitas)', false)
            ->from('tr_h3_dealer_sales_order as dso')
            ->join('tr_h3_dealer_sales_order_parts as dsop', 'dso.id = dsop.nomor_so_int')
            // ->join('ms_h3_md_setting_kelompok_produk as kp','kp.id_kelompok_part=p.kelompok_part')
            // ->where("dso.tanggal_so between '{$date_start_point}' and '{$date_end_point}'", null, false)
            ->group_start()
            ->where("tanggal_so <= subdate('{$start_point}', 0)")
            ->where("tanggal_so > subdate('{$start_point}', 6 * 7)")
            ->group_end()
            ->where('kp.produk', $thelist)
            ->where('p.id_part_int = dsop.id_part_int', null, false)
            ->where('dso.id_dealer', $pilih_ahass)
            ->where('dso.status', 'Closed')
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
    
            // $order_md = $this->purchase_parts_md->qty_on_order_md($pilih_ahass, 'p.id_part', true);
            // $order_md = $this->purchase_parts_md->qty_on_order_md2($pilih_ahass, 'p.id_part_int', true);
            $order_md = $this->purchase_parts_md->qty_on_order_md_v3($pilih_ahass, 'p.id_part_int', true);
    
            // $stock = $this->db->select('sum(ds.stock)')
            // ->from('ms_h3_dealer_stock as ds')
            // ->where('ds.id_part = p.id_part')
            // ->where('ds.id_dealer', $this->m_admin->cari_dealer())
            // ->group_by('ds.id_part')
            // ->get_compiled_select();
    
            // $stock = $this->dealer_stock->qty_on_hand($pilih_ahass, 'p.id_part', null, null, true);
            $stock = $this->dealer_stock->qty_on_hand2($pilih_ahass, 'p.id_part_int', null, null, true);
    
    
            $sim_part = $this->sim_part->qty_sim_part2($pilih_ahass, 'p.id_part_int', true);
    
            $index = 1;
            foreach ($weekSalesQuery as $query) {
                $this->db->select("IFNULL(({$query}), 0) as t$index");
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
            ->join('ms_h3_md_setting_kelompok_produk as kp','kp.id_kelompok_part=p.kelompok_part')
            ->group_start()
            ->where('kp.produk', $thelist)
            ->where('p.status', 'A')
            // ->where('p.sim_part', $kategori_sim_part)
            ;
            if($jenis_po=='FIX'){
                $this->db->where('p.fix', 1);
            }
            if($kategori_sim_part!=''){
                $this->db->where('p.sim_part', $kategori_sim_part);
            }
            // if($kategori_sim_part==1){
            //     $this->db->where('p.sim_part', 1);
            // }elseif($kategori_sim_part==0){
            //     $this->db->where('p.sim_part', 0);
            // }
            // if(count($kode_part_yang_terjual) > 0){
            //     $this->db->or_where_in('p.id_part_int', $kode_part_yang_terjual);
            // }
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
            // $id_dealer = $this->m_admin->cari_dealer();
            foreach ($result as $rs) {
                $sub_array = (array)$rs;
                $sub_array['id_dealer'] = $pilih_ahass;
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
                // $sub_array['suggested_order'] = $rs->avg_six_weeks - $rs->stock - $rs->order_md;
    
                // if($sub_array['suggested_order'] <= 0){
                //     $sub_array['suggested_order'] = 0;
                // }else if($sub_array['suggested_order'] < $rs->sim_part){
                //     $sub_array['suggested_order'] = $rs->sim_part;
                // }

                // if($sub_array['suggested_order'] <= 0){
                //     $sub_array['suggested_order'] = 0;
                // }else{
                //     $sub_array['suggested_order'] = $sub_array['suggested_order'];
                // }

                if($sub_array['suggested_order'] <= 0){
                    if($rs->sim_part > 0){
                        if($rs->sim_part > $rs->stock){
                            $sub_array['suggested_order'] = ($rs->sim_part-$rs->stock);
                        }else{
                            $sub_array['suggested_order'] = 0;
                        }
                    }else{
                        $sub_array['suggested_order'] = 0;
                    }
                }else{
                    $sub_array['suggested_order'] = $sub_array['suggested_order'];
                }
                
                // $sub_array['suggested_order'] = $rs->sim_part;
    
                // if($rs->total_six_weeks > 0){
                //     $six_weeks = $rs->total_six_weeks / 42;
                //     if($six_weeks == 0){
                //         $stock_days = $rs->stock;
                //     }else{
                //         $stock_days = $rs->stock / $six_weeks;
                //     }
                //     $sub_array['stock_days'] =  ( $stock_days );
                // }else{
                //     $sub_array['stock_days'] = 0;
                // }

                if($rs->total_six_weeks > 0){
                    $six_weeks = $rs->total_six_weeks / 42;
                    if($six_weeks == 0){
                        $stock_days = 0;
                    }else{
                        $stock_days = $rs->stock / $six_weeks;
                    }
                    $sub_array['stock_days'] =  ( $stock_days );
                }else{
                    $sub_array['stock_days'] = 0;
                }
    
                $sub_array['stock_days'] = round($sub_array['stock_days']);
    
                $sub_array['adjusted_order'] = $sub_array['suggested_order'];
                $sub_array['type_waktu'] =  $tipe_waktu;
                $sub_array['periode_start'] =  $periode_start;
                $sub_array['created_at'] = date('Y-m-d H:i:s', time());
                $sub_array['created_by'] = $this->session->userdata('id_user');
                $sub_array['jenis_po'] = $jenis_po;
                $sub_array['order_md'] = $rs->order_md;
                $sub_array['sim_part'] = $rs->sim_part;
    
                unset($sub_array['total_six_weeks']);
                // unset($sub_array['sim_part']);
                unset($sub_array['stock']);
                // unset($sub_array['order_md']);
                $data[] = $sub_array;
                $index++;
                
                $this->db->where('id_dealer',$pilih_ahass);
                $this->db->where('jenis_po',$jenis_po);
                $this->db->where('id_part_int',$rs->id_part_int);
                $this->db->delete('tr_h3_md_autofulfillment');
            }
            // echo $this->db->last_query();
            // die();
            $this->autofulfillment_model->insert_batch($data);
            // $this->db->trans_complete();
        }else{
            $start_point = date('Y-m-d', strtotime($this->input->post('tanggal_start_periode')));
            $tujuhHari = 2678400;
            $weekSalesQuery = [];
            for ($i=0; $i < 6; $i++) { 
                $sub_start = $i * 31;
                $sub_end = ($i + 1) * 31;
    
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
                ->where('kp.produk', $thelist)
                ->where('dsop.id_part_int = p.id_part_int', null, false)
                ->where('dso.id_dealer', $pilih_ahass)
                ->where('dso.status', 'Closed')
                ->get_compiled_select();
            }
    
            $date_start_point = date('Y-m-d', strtotime("- {$sub_start} day", strtotime($start_point)));
            $date_end_point = date('Y-m-d', strtotime("- {$sub_end} day", strtotime($start_point)));
    
            $total_six_weeks = $this->db
            ->select('SUM(dsop.kuantitas)', false)
            ->from('tr_h3_dealer_sales_order as dso')
            ->join('tr_h3_dealer_sales_order_parts as dsop', 'dso.id = dsop.nomor_so_int')
            // ->where("dso.tanggal_so between '{$date_start_point}' and '{$date_end_point}'", null, false)
            ->group_start()
            ->where("tanggal_so <= subdate('{$start_point}', 0)")
            ->where("tanggal_so > subdate('{$start_point}', 6 * 31)")
            ->group_end()
            ->where('kp.produk', $thelist)
            ->where('p.id_part_int = dsop.id_part_int', null, false)
            ->where('dso.id_dealer', $pilih_ahass)
            ->where('dso.status', 'Closed')
            ->get_compiled_select();

    
            // $order_md = $this->purchase_parts_md->qty_on_order_md($pilih_ahass, 'p.id_part', true);
            // $order_md = $this->purchase_parts_md->qty_on_order_md2($pilih_ahass, 'p.id_part_int', true);
            $order_md = $this->purchase_parts_md->qty_on_order_md_v3($pilih_ahass, 'p.id_part_int', true);
    
            // $stock = $this->db->select('sum(ds.stock)')
            // ->from('ms_h3_dealer_stock as ds')
            // ->where('ds.id_part = p.id_part')
            // ->where('ds.id_dealer', $this->m_admin->cari_dealer())
            // ->group_by('ds.id_part')
            // ->get_compiled_select();
    
            // $stock = $this->dealer_stock->qty_on_hand($pilih_ahass, 'p.id_part', null, null, true);
            $stock = $this->dealer_stock->qty_on_hand2($pilih_ahass, 'p.id_part_int', null, null, true);
    
            $sim_part = $this->sim_part->qty_sim_part2($pilih_ahass, 'p.id_part_int', true);
    
            $index = 1;
            foreach ($weekSalesQuery as $query) {
                $this->db->select("IFNULL(({$query}), 0) as t$index");
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
            ->join('ms_h3_md_setting_kelompok_produk as kp','kp.id_kelompok_part=p.kelompok_part')
            ->group_start()
            ->where('kp.produk', $thelist)
            ->where('p.status', 'A')
            // ->where('p.sim_part', $kategori_sim_part)
            ;
            if($jenis_po=='FIX'){
                $this->db->where('p.fix', 1);
            }
            if($kategori_sim_part!=''){
                $this->db->where('p.sim_part', $kategori_sim_part);
            }
            // if($kategori_sim_part==1){
            //     $this->db->where('p.sim_part', 1);
            // }elseif($kategori_sim_part==0){
            //     $this->db->where('p.sim_part', 0);
            // }
            // if(count($kode_part_yang_terjual) > 0){
            //     $this->db->or_where_in('p.id_part_int', $kode_part_yang_terjual);
            // }
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
            // $id_dealer = $this->m_admin->cari_dealer();
            foreach ($result as $rs) {
                $sub_array = (array)$rs;
                $sub_array['id_dealer'] = $pilih_ahass;
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
    
                // if($sub_array['suggested_order'] <= 0){
                //     $sub_array['suggested_order'] = 0;
                // }else if($sub_array['suggested_order'] < $rs->sim_part){
                //     $sub_array['suggested_order'] = $rs->sim_part;
                // }


                if($sub_array['suggested_order'] <= 0){
                    if($rs->sim_part > 0){
                        if($rs->sim_part > $rs->stock){
                            $sub_array['suggested_order'] = ($rs->sim_part-$rs->stock);
                        }else{
                            $sub_array['suggested_order'] = 0;
                        }
                    }else{
                        $sub_array['suggested_order'] = 0;
                    }
                }else{
                    $sub_array['suggested_order'] = $sub_array['suggested_order'];
                }
    
                if($rs->total_six_weeks > 0){
                    $six_weeks = $rs->total_six_weeks / 186;
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
                $sub_array['type_waktu'] =  $tipe_waktu;
                $sub_array['periode_start'] =  $periode_start;
                $sub_array['created_at'] = date('Y-m-d H:i:s', time());
                $sub_array['created_by'] = $this->session->userdata('id_user');
                $sub_array['jenis_po'] = $jenis_po;
                $sub_array['order_md'] = $rs->order_md;
                $sub_array['sim_part'] = $rs->sim_part;
    
                unset($sub_array['total_six_weeks']);
                // unset($sub_array['sim_part']);
                unset($sub_array['stock']);
                // unset($sub_array['order_md']);
                $data[] = $sub_array;
                $index++;

                $this->db->where('id_dealer',$pilih_ahass);
                $this->db->where('jenis_po',$jenis_po);
                $this->db->where('id_part_int',$rs['id_part_int']);
                $this->db->delete('tr_h3_md_autofulfillment');
            }
            $this->autofulfillment_model->insert_batch($data);
            // $this->db->trans_complete();
        }
        

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

		// return $this->db->trans_status();
	}

    public function autofulfillment_table()
    {
        $this->benchmark->mark('data_start');
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            // $on_order = $this->db
            //     ->select('SUM( pop.kuantitas - opt.qty_bill ) as kuantitas', false)
            //     ->from('tr_h3_dealer_purchase_order_parts as pop')
            //     ->join('tr_h3_dealer_purchase_order as po', 'po.id = pop.po_id_int')
            //     ->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id_int = po.id and opt.id_part_int = pop.id_part_int)')
            //     ->where('pop.id_part_int', $row['id_part_int'])
            //     ->where('po.status', 'Processed by MD')
            //     ->where('po.kategori_po !=', 'KPB')
            //     ->where('po.id_dealer', $row['id_dealer'])
            //     ->limit(1)
            //     ->get()->row_array();
            //     // $order_md = $this->purchase_parts_md->qty_on_order_md2($row['id_dealer'], $row['id_part_int'], true);
                

            // $row['on_order'] = $order_md;

            $stock = $this->db
                ->select('IFNULL(sum(ds.stock), 0) AS kuantitas')
                ->from('ms_h3_dealer_stock as ds')
                ->where('ds.id_part_int', $row['id_part_int'])
                ->where('ds.id_dealer', $row['id_dealer'])
                ->limit(1)
                ->get()->row_array();

            $row['stock'] = $stock['kuantitas'];

            $in_transit = $this->db
                ->select('IFNULL(SUM( opt.qty_bill ), 0) as kuantitas', false)
                ->from('tr_h3_dealer_purchase_order_parts as pop')
                ->join('tr_h3_dealer_purchase_order as po', 'po.id = pop.po_id_int')
                ->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id_int = po.id and opt.id_part_int = pop.id_part_int)')
                ->where('pop.id_part_int', $row['id_part_int'])
                ->where('po.status', 'Processed by MD')
                ->where('po.kategori_po !=', 'KPB')
                ->where('po.id_dealer', $row['id_dealer'])
                ->limit(1)
                ->get()->row_array();

            $row['in_transit'] = $in_transit['kuantitas'];

            $row['adjust_order'] = $this->load->view('additional/md/h3/autofulfillment_adjust_order_view', [
                "loop" => $index,
                'id_part' => $row['id_part'],
                'id_dealer' => $row['id_dealer'],
                'adjusted_order' => $row['adjusted_order'],
            ], true);

            $row['index'] = $this->input->post('start') + $index;

            $data[] = $row;
            $index++;
        }
        $this->benchmark->mark('data_end');

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsFiltered_time' => floatval($this->benchmark->elapsed_time('recordsFiltered_start', 'recordsFiltered_end')),
            'recordsTotal' => $this->recordsTotal(),
            'recordsTotal_time' => floatval($this->benchmark->elapsed_time('recordsTotal_start', 'recordsTotal_end')),
            'data' => $data,
            'data_time' => floatval($this->benchmark->elapsed_time('data_start', 'data_end'))
        ]);
    }

    public function make_query()
    {
        $id_dealer = $this->input->post('pilih_ahass');
        $tanggal_start_periode = $this->input->post('tanggal_start_periode');
        $tipe_waktu = $this->input->post('tipe_waktu');
        $kelompok_part_besar = $this->input->post('kelompok_part_besar');
        $jenis_po = $this->input->post('jenis_po');
        $sim_part = $this->sim_part->qty_sim_part($id_dealer, 'ar.id_part_int', true);
        $kategori_sim_part = $this->input->post('kategori_sim_part');


        $this->db
            ->select('ar.id_dealer')
            ->select('ar.periode_start')
            ->select('md.nama_dealer')
            ->select('ar.id_part')
            ->select('IFNULL(ar.avg_six_weeks, 0) as avg_six_weeks')
            ->select('IFNULL(ar.akumulasi_qty, 0) as akumulasi_qty')
            ->select('IFNULL(ar.akumulasi_persen, 0) as akumulasi_persen')
            // ->select('IFNULL(ar.rank, "-") as rank')
            ->select('ar.rank')
            ->select('(CASE WHEN type_waktu="week" then "W" else "M" end) as type_waktu')
            ->select('IFNULL(ar.t1, 0) as w1')
            ->select('IFNULL(ar.t2, 0) as w2')
            ->select('IFNULL(ar.t3, 0) as w3')
            ->select('IFNULL(ar.t4, 0) as w4')
            ->select('IFNULL(ar.t5, 0) as w5')
            ->select('IFNULL(ar.t6, 0) as w6')
            // ->select("IFNULL(({$sim_part}), 0) as min_stok")
            ->select("IFNULL(ar.sim_part, 0) as sim_part")
            ->select('IFNULL(ar.stock_days, 0) as stock_days')
            ->select('IFNULL(ar.suggested_order, 0) as suggested_order')
            ->select('IFNULL(ar.adjusted_order, 0) as adjusted_order')
            ->select('IFNULL(ar.order_md, 0) as on_order')
            ->select('ar.id_part_int')
            ->select('mp.nama_part')
            ->from('tr_h3_md_autofulfillment as ar')
            ->join('ms_part as mp', 'mp.id_part_int = ar.id_part_int')
            ->join('ms_dealer as md', 'md.id_dealer = ar.id_dealer')
            ->join('ms_h3_md_setting_kelompok_produk as kp','kp.id_kelompok_part=mp.kelompok_part')
            ->where('ar.id_dealer', $id_dealer)
            ->where('ar.type_waktu', $tipe_waktu)
            ->where('kp.produk', $kelompok_part_besar)
            ->where('ar.jenis_po', $jenis_po)
            ->where('mp.sim_part', $kategori_sim_part)
            // ->where('ar.periode_start', $tanggal_start_periode)
            ->limit(1);
    }

    public function make_datatables()
    {
        $this->make_query();
        $id_part = $this->input->post('id_part');
        $deskripsi_part = $this->input->post('deskripsi_part');
        $kategori_part = $this->input->post('kategori_part');

        if ($this->input->post('kelompok_part') != null) {
            $this->db->where_in('mp.kelompok_part', $this->input->post('kelompok_part'));
        }

        // $search = trim($this->input->post('search')['value']);

        // if ($search != '') {
        //     $this->db->group_start();
        //     $this->db->like('mp.nama_part', $search);
        //     $this->db->or_like('mp.id_part', $search);
        //     $this->db->group_end();
        // }
        if ($kategori_part == 'sim_part') {
            $this->db->where('mp.sim_part', '1');
        }elseif($kategori_part == 'non_sim_part'){
            $this->db->where('mp.sim_part', '0');
        }

        if ($this->input->post('filter_rank') != '') {
            $this->db->where('ar.rank', $this->input->post('filter_rank'));
        }

        if ($this->input->post('filter_kelompok_hgp') == 'tire') {
            $this->db->where_in('mp.kelompok_part',array('TIRE','TIRE1'));
        }elseif($this->input->post('filter_kelompok_hgp') == 'ahm'){
            $this->db->where('mp.kelompok_part','AHM');
        }elseif($this->input->post('filter_kelompok_hgp') == 'electrical_parts'){
            $this->db->where_in('mp.kelompok_part',array('ELECT','EP','EPHVL','EPMTI'));
        }elseif($this->input->post('filter_kelompok_hgp') == 'drive_belt'){
            $this->db->where('mp.kelompok_part','BLDRV');
        }elseif($this->input->post('filter_kelompok_hgp') == 'brake_system'){
            $this->db->where_in('mp.kelompok_part',array('BRAKE','BS','PS'));
        }elseif($this->input->post('filter_kelompok_hgp') == 'shock_absorber'){
            $this->db->where_in('mp.kelompok_part',array('SHOCK','SA'));
        }elseif($this->input->post('filter_kelompok_hgp') == 'piston_kit'){
            $this->db->where('mp.kelompok_part','PSKIT');
        }elseif($this->input->post('filter_kelompok_hgp') == 'battery'){
            $this->db->where_in('mp.kelompok_part',array('BATT','BT'));
        }elseif($this->input->post('filter_kelompok_hgp') == 'disk_clutch'){
            $this->db->where_in('mp.kelompok_part',array('DISK','DIHVL'));
        }elseif($this->input->post('filter_kelompok_hgp') == 'drive_chain_kit'){
            $this->db->where_in('mp.kelompok_part',array('CDKGP','CDKIT'));
        }elseif($this->input->post('filter_kelompok_hgp') == 'plastik_part'){
            $this->db->where_in('mp.kelompok_part',array('PLAST','PT'));
        }elseif($this->input->post('filter_kelompok_hgp') == 'element_cleaner'){
            $this->db->where('mp.kelompok_part','EC');
        }elseif($this->input->post('filter_kelompok_hgp') == 'sparkplug'){
            $this->db->where_in('mp.kelompok_part',array('SPLUG','SPLUR'));
        }elseif($this->input->post('filter_kelompok_hgp') == 'bearing'){
            $this->db->where_in('mp.kelompok_part',array('BRNG','BRNG3'));
        }elseif($this->input->post('filter_kelompok_hgp') == 'oil_seal'){
            $this->db->where('mp.kelompok_part','OSEAL');
        }elseif($this->input->post('filter_kelompok_hgp') == 'others'){
            $this->db->where_in('mp.kelompok_part',array('AH','BB','BBIST','BM1','BR','CABLE','CB','CCKIT','CD','CH','CHAIN','COMP','COOL','CRKIT','ET','FKT','GS','GSA','GSB','GST','HAH','HM','HNMTI','HRPLAS','HRW','HSD','IMPOR','INS','ISTC','LGS','LSIST','MF','MTI','MUF','N','NF','OAHM1','OAHM2','OC','OFCC','OINS','OISTC','OKGD','OMTI','ORPL','OTHER','OTHR','PAINT','PSTN','RBR','RIMWH','RPHVL','RPIST','RSKIT','RW','RW2','RW3','RWHVL','SAOIL','SD','SDN','SDN2','SDT','SE','SP','SP2','SPGUI','SPOKE','STR','TAS','TAS','TB','TB1','TBHGP','TBVL','TDI','TR','VALVE','VV'));
        }

        // else{
        //     $this->db->where('mp.sim_part', '1');
        //     $this->db->or_where('mp.sim_part', '0');
        // }

        if ($id_part != '') {
            $this->db->like('mp.id_part', $id_part);
        }
        
        if ($deskripsi_part != '') {
            $this->db->like('mp.nama_part', $deskripsi_part);
        }

        

        if ($this->input->post('filter_order') != null) {
            if ($this->input->post('filter_order') == 'sim_part') $this->db->order_by('min_stok', 'desc');
            if ($this->input->post('filter_order') == 'avg_six_weeks') $this->db->order_by('ar.avg_six_weeks', 'desc');
        } else {
            $this->db->order_by('min_stok', 'desc');
        }

        $this->db->order_by('min_stok', 'desc');
        $this->db->order_by('ar.avg_six_weeks', 'DESC');
    }

    public function limit()
    {
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->benchmark->mark('recordsFiltered_start');
        $this->make_datatables();
        $record = $this->db->count_all_results();
        $this->benchmark->mark('recordsFiltered_end');

        return $record;
    }

    public function recordsTotal()
    {
        $this->benchmark->mark('recordsTotal_start');
        $this->make_query();
        $record = $this->db->count_all_results();
        $this->benchmark->mark('recordsTotal_end');

        return $record;
    }

    public function adjust_order(){
		$this->db->trans_start();
		$this->autofulfillment_model->update($this->input->post(['adjusted_order']), [
			'id_dealer' => $this->input->post('id_dealer'),
			'id_part' => $this->input->post('id_part')
		]);
		$this->db->trans_complete();

        // var_dump("TEST" . $this->input->get('id_dealer'). $this->input->post('id_dealer'));
        // die();

		if($this->db->trans_status()){
			$this->output->set_status_header(200);
			$adjusted_order = $this->autofulfillment_model->get([
				'id_dealer' => $this->input->post('id_dealer'),
				'id_part' => $this->input->post('id_part')
			], true);
			send_json($adjusted_order);
		}else{
			$this->output->set_status_header(500);
		}

	}

    public function generate_part_auto(){
        $periode_start = $this->input->post('tanggal_start_periode');
        $tipe_waktu =$this->input->post('tipe_waktu');
        $pilih_ahass = $this->input->post('pilih_ahass');
        // $kel_part_filter =$this->input->post('kel_part_filter');
        $kelompok_part_besar =$this->input->post('kelompok_part_besar');
        $jenis_po= $this->input->post('jenis_po');

        $this->db->trans_begin();
        //Cari id part yang telah di adjust qty
        $parts = $this->db->select('mda.id_part')
                          ->select('mda.id_part_int')
                          ->select('mda.adjusted_order')
                          ->select('mp.harga_dealer_user')
                          ->from('tr_h3_md_autofulfillment mda')
                          ->join('ms_part mp','mp.id_part_int=mda.id_part_int')
                          ->where('mda.id_dealer',$pilih_ahass)
                          ->where('mda.type_waktu',$tipe_waktu)
                          ->where('mda.jenis_po',$jenis_po)
                          ->where('mda.adjusted_order >',0)
                          ->get()->result_array();
        
        $total_amount = 0;
        foreach($parts as $part){
            $total_amount += $part['harga_dealer_user']*$part['adjusted_order'];
        }

        //Insert data PO Header
        $purchase_order = array(
            'po_id' => $this->purchase_order->generatePONumber($jenis_po, $pilih_ahass),
            'tanggal_order' => date('Y-m-d'),
            'status' => 'Draft',
            'created_by_md' => 1,
            'autofulfillment_md' => 1,
            'po_type' => $jenis_po,
            'kategori_po' => 'Non SIM Part',
            'produk' => $kelompok_part_besar,
            'id_dealer' => $pilih_ahass,
            'total_amount' =>  $total_amount
        );

        $this->purchase_order->insert($purchase_order);

        $id_po_int = $this->db->select('id')
                              ->from('tr_h3_dealer_purchase_order po')
                              ->where('po.po_id',$purchase_order['po_id'])
                              ->get()->row_array();

        //Insert data part ke PO Dealer Parts 
        foreach($parts as $part){
        $purchase_order_parts = array(  
            'po_id' => $purchase_order['po_id'],
            'po_id_int' => $id_po_int['id'],
            'id_part' => $part['id_part'],
            'id_part_int' => $part['id_part_int'],
            'kuantitas' => $part['adjusted_order'],
            'harga_saat_dibeli' => $part['harga_dealer_user'],
            'tot_harga_part' => $part['harga_dealer_user']*$part['adjusted_order']);

            
            $this->db->insert('tr_h3_dealer_purchase_order_parts', $purchase_order_parts);
        }
        
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

    public function create_so(){
        $periode_start = $this->input->post('tanggal_start_periode');
        $tipe_waktu =$this->input->post('tipe_waktu');
        $pilih_ahass = $this->input->post('pilih_ahass');
        $kel_part_filter =$this->input->post('kel_part_filter');
        $kelompok_part_besar =$this->input->post('kelompok_part_besar');
        $jenis_po= $this->input->post('jenis_po');

        $this->db->trans_begin();
        //Cari id part yang telah di adjust qty
        $parts = $this->db->select('mda.id_part')
                          ->select('mda.id_part_int')
                          ->select('mda.adjusted_order')
                          ->select('mp.harga_dealer_user')
                          ->from('tr_h3_md_autofulfillment mda')
                          ->join('ms_part mp','mp.id_part_int=mda.id_part_int')
                          ->where('mda.id_dealer',$pilih_ahass)
                          ->where('mda.type_waktu',$tipe_waktu)
                          ->where('mda.jenis_po',$jenis_po)
                          ->where('mda.adjusted_order >',0)
                          ->get()->result_array();
        
        $total_amount = 0;
        foreach($parts as $part){
            $total_amount += $part['harga_dealer_user']*$part['adjusted_order'];
        }

        //Insert data PO Header
        $purchase_order = array(
            'po_id' => $this->purchase_order->generatePONumber($jenis_po, $pilih_ahass),
            'tanggal_order' => date('Y-m-d'),
            'status' => 'Submitted',
            'created_by_md' => 1,
            'autofulfillment_md' => 1,
            'po_type' => $jenis_po,
            'kategori_po' => 'Non SIM Part',
            'produk' => $kelompok_part_besar,
            'id_dealer' => $pilih_ahass,
            'total_amount' =>  $total_amount
        );

        // var_dump($purchase_order);
        // die();

        $this->purchase_order->insert($purchase_order);

        $id_po_int = $this->db->select('id')
                              ->from('tr_h3_dealer_purchase_order po')
                              ->where('po.po_id',$purchase_order['po_id'])
                              ->get()->row_array();

        foreach($parts as $part){
        $purchase_order_parts = array(  
            'po_id' => $purchase_order['po_id'],
            'po_id_int' => $id_po_int['id'],
            'id_part' => $part['id_part'],
            'id_part_int' => $part['id_part_int'],
            'kuantitas' => $part['adjusted_order'],
            'harga_saat_dibeli' => $part['harga_dealer_user'],
            'tot_harga_part' => $part['harga_dealer_user']*$part['adjusted_order']);

            
            $this->db->insert('tr_h3_dealer_purchase_order_parts', $purchase_order_parts);
        }
        
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