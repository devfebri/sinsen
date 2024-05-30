<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed 
header('Content-Type: application/x-www-form-urlencoded');

class H23_api extends CI_Controller {

    function __construct(){
        parent::__construct();
        require_once APPPATH.'third_party/dompdf/dompdf_config.inc.php';
        $this->load->model("h23_api_model");
    }
    
    public function index(){
        echo "Hello cuk";
    }
    
    public function cetak_po_reg()
    {
       
      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = false;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = false;

    
    $title         = 'cetak_po_reguler';
    $id = $_GET['id'];
    $data['data']=$this->db->query("SELECT a.po_id,a.id_dealer,a.tanggal_order,a.batas_waktu,a.po_type,a.id_salesman,b.kode_dealer_ahm,b.nama_dealer,b.alamat, a.po_nmd from tr_h3_dealer_purchase_order a join ms_dealer b on a.id_dealer=b.id_dealer where a.po_id='$id'")->row_array();
    $data['sparepart']=$this->db->query("select a.*,b.nama_part,b.harga_dealer_user,a.harga_saat_dibeli as harga_saat_beli,a.harga_setelah_diskon,a.diskon_value from tr_h3_dealer_purchase_order_parts a join ms_part b on a.id_part=b.id_part where a.po_id='$id'")->result();
      $html = $this->load->view('po_reg_cetak', $data, true);
      $mpdf->WriteHTML($html);
      $output = $title . '.pdf';
      $mpdf->Output("$output", 'I');
      
     
      echo json_encode([
          "status"=>200,
          "message"=>"success"
          ]);
      
    }
    public function cetak_po_fix()
    {
       
      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;
      $mpdf->format            ='A4-L';

    
    $title         = 'cetak_po_fix';
    $id = $_GET['id'];
    $data['data']=$this->db->query("SELECT a.po_id,a.id_dealer,a.produk,a.tanggal_order,a.batas_waktu,a.po_type,a.id_salesman,b.kode_dealer_ahm,b.nama_dealer,b.alamat from tr_h3_dealer_purchase_order a join ms_dealer b on a.id_dealer=b.id_dealer where a.po_id='$id'")->row_array();
    $data['sparepart']=$this->db->query("select a.*,b.nama_part,b.harga_dealer_user,if(b.sim_part='1','Simpart','Non Simpart') as simpart,b.kelompok_part,b.minimal_order from tr_h3_dealer_purchase_order_parts a join ms_part b on a.id_part=b.id_part where a.po_id='$id'")->result();
          $mpdf->AddPage('L');
      $html = $this->load->view('po_fix_cetak', $data, true);
      $mpdf->WriteHTML($html);
      $output = $title . '.pdf';
      $mpdf->Output("$output", 'D');
       echo json_encode([
          "status"=>200,
          "message"=>"success"
          ]);
      
    }
    
      public function cetak_penerimaan()
    {
       
      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = false;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = false;

    
    $title         = 'PENERIMAAN-PARTS';
    $id = $_GET['id'];
    $data['data']=$this->db->query("SELECT a.kode_dealer_ahm,a.nama_dealer,a.alamat,a.no_telp,b.id_good_receipt,b.nomor_po,left(b.tanggal_receipt,10)as tanggal_penerimaan,b.id_reference,c.kelurahan,d.kecamatan,e.kabupaten from ms_dealer a join tr_h3_dealer_good_receipt b on b.id_dealer=a.id_dealer join ms_kelurahan c on a.id_kelurahan=c.id_kelurahan join ms_kecamatan d on c.id_kecamatan=d.id_kecamatan join ms_kabupaten e on d.id_kabupaten=e.id_kabupaten where b.id_good_receipt='$id'")->row_array();
    $data['sparepart']=$this->db->query("select a.*,b.nama_part,b.harga_dealer_user from tr_h3_dealer_good_receipt_parts a join ms_part b on a.id_part=b.id_part where a.id_good_receipt='$id'")->result();
      $html = $this->load->view('cetak_good_receipt', $data, true);
      $mpdf->WriteHTML($html);
      $output = $title . '.pdf';
      $mpdf->Output("$output", 'I');
      
     
      echo json_encode([
          "status"=>200,
          "message"=>"success"
          ]);
      
    }
    
    
    public function grafik_ue_promotion(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $tgl2 = date('m', strtotime('-1 month', strtotime($date)));
      $result=array();
      $resultM=array();
      $dataPoints=array();
      $dataPoints1=array();
      
    //   bulan ini
      $reminder = $this->db->query("select count(*) as unit_reminder,'Reminder' as rm from tr_h2_sa_form where id_sa_form in
      (SELECT b.id_sa_form from tr_h2_wo_dealer b where b.status='closed' and month(created_at)='$month' and year(created_at)='$tahun') and asal_unit_entry ='Reminder'")->row();
      $pitExpress = $this->db->query("select count(*) as pit_express,'Pit Express' as pe from tr_h2_sa_form where id_sa_form in
      (SELECT b.id_sa_form from tr_h2_wo_dealer b where b.status='closed' and month(created_at)='$month' and year(created_at)='$tahun') and asal_unit_entry ='Walk In Pit Express'")->row();
       $visit = $this->db->query("select count(*) as visit,'Service Visit' as sv from tr_h2_sa_form where id_sa_form in
      (SELECT b.id_sa_form from tr_h2_wo_dealer b where b.status='closed' and month(created_at)='$month' and year(created_at)='$tahun') and asal_unit_entry ='Service Visit'")->row();
      $event = $this->db->query("select count(*) as event,'AHASS Event' as ae from tr_h2_sa_form where id_sa_form in
      (SELECT b.id_sa_form from tr_h2_wo_dealer b where b.status='closed' and month(created_at)='$month' and year(created_at)='$tahun') and asal_unit_entry ='AHASS Event'")->row();
      
    //   bulan lalu
      $reminderM = $this->db->query("select count(*) as unit_reminder,'Reminder' as rm from tr_h2_sa_form where id_sa_form in
      (SELECT b.id_sa_form from tr_h2_wo_dealer b where b.status='closed' and month(created_at)='$tgl2' and year(created_at)='$tahun') and asal_unit_entry ='Reminder'")->row();
      $pitExpressM = $this->db->query("select count(*) as pit_express,'Pit Express' as pe from tr_h2_sa_form where id_sa_form in
      (SELECT b.id_sa_form from tr_h2_wo_dealer b where b.status='closed' and month(created_at)='$tgl2' and year(created_at)='$tahun') and asal_unit_entry ='Walk In Pit Express'")->row();
        $visitM = $this->db->query("select count(*) as visit,'Service Visit' as sv from tr_h2_sa_form where id_sa_form in
      (SELECT b.id_sa_form from tr_h2_wo_dealer b where b.status='closed' and month(created_at)='$tgl2' and year(created_at)='$tahun') and asal_unit_entry ='Service Visit'")->row();
      $eventM = $this->db->query("select count(*) as event,'AHASS Event' as ae from tr_h2_sa_form where id_sa_form in
      (SELECT b.id_sa_form from tr_h2_wo_dealer b where b.status='closed' and month(created_at)='$tgl2' and year(created_at)='$tahun') and asal_unit_entry ='AHASS Event'")->row();
	
	
	   $sub_array=array();
	   $sub_array['reminder']=$reminder->rm;
	   $sub_array['total_reminder']=$reminder->unit_reminder;
	   $sub_array['pit_express']= $pitExpress->pe;
	   $sub_array['total_pit_express']= $pitExpress->pit_express;
	   $sub_array['visit']=$visit->sv;
	   $sub_array['total_visit']=$visit->visit;
	   $sub_array['event']= $event->ae;
	   $sub_array['total_event']= $event->event;
	   $result[]=$sub_array;
	   
	   $sub_arrayM=array();
	   $sub_arrayM['reminder']=$reminderM->rm;
	   $sub_arrayM['total_reminder']=$reminderM->unit_reminder;
	   $sub_arrayM['pit_expresso']= $pitExpressM->pe;
	   $sub_arrayM['total_pit_expresso']= $pitExpressM->pit_express;
	   $sub_arrayM['visit']=$visitM->sv;
	   $sub_arrayM['total_visit']=$visitM->visit;
	   $sub_arrayM['event']= $eventM->ae;
	   $sub_arrayM['total_event']= $eventM->event;
	   $resultM[]=$sub_arrayM;
	   
            
        $dataPoints=array(
            ["label"=> $sub_array['reminder'],"y"=> (int)$sub_array['total_reminder']],
            ["label"=> $sub_array['pit_express'],"y"=> (int)$sub_array['total_pit_express']],
            ["label"=> $sub_array['visit'],"y"=> (int)$sub_array['total_visit']],
            ["label"=> $sub_array['event'],"y"=> (int)$sub_array['total_event']],
        );
        
         $dataPoints1=array(
            ["label"=> $sub_arrayM['reminder'],"y"=> (int)$sub_arrayM['total_reminder']],
            ["label"=> $sub_arrayM['pit_expresso'],"y"=> (int)$sub_arrayM['total_pit_expresso']],
            ["label"=> $sub_arrayM['visit'],"y"=> (int)$sub_arrayM['total_visit']],
            ["label"=> $sub_arrayM['event'],"y"=> (int)$sub_arrayM['total_event']],
        );
        
	    echo json_encode(array(
            "dataPoints"=>$dataPoints,
            "dataPoints1"=>$dataPoints1
            ));
            
            
    }
    
    public function grafik_revenue(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $dataPoints=array();
      $result=array();
      $revenueJasa = $this->db->query("select sum(a.harga) as jasa,'Jasa' as js from tr_h2_wo_dealer_pekerjaan a join tr_h2_wo_dealer b on a.id_work_order=b.id_work_order
				where month(b.created_at)='$month' and year(b.created_at)='$tahun' and b.status='Closed'")->row();
	  $revenueParts = $this->db->query("select sum(a.harga) as parts, 'Parts' as ps from tr_h2_wo_dealer_parts a join tr_h2_wo_dealer b on a.id_work_order=b.id_work_order 
				where month(b.created_at)='$month' and year(b.created_at)='$tahun' and b.status='Closed' and a.id_part in(select c.id_part from ms_part c where c.kelompok_part !='OIL')")->row();
	  $revenueAHMOIL = $this->db->query("select sum(a.harga) as oil,'AHM Oil' as oi from tr_h2_wo_dealer_parts a join tr_h2_wo_dealer b on a.id_work_order=b.id_work_order
				where month(b.created_at)='$month' and year(b.created_at)='$tahun' and b.status='Closed' and a.id_part in(select c.id_part from ms_part c where c.kelompok_part ='OIL')")->row();
	
	   $sub_array = array();
	   $sub_array['jasa']= $revenueJasa->jasa;
	   $sub_array['parts']= $revenueParts->parts;
	   $sub_array['oil']= $revenueAHMOIL->oil;
	   $sub_array['js']= $revenueJasa->js;
	   $sub_array['ps']= $revenueParts->ps;
	   $sub_array['oi']= $revenueAHMOIL->oi;
	   $result[]= $sub_array;
	   
	   $dataPoints=array(
            ["label"=> $sub_array['js'],"y"=> (int)$sub_array['jasa'],"exploded"=>true],
            ["label"=> $sub_array['ps'],"y"=> (int)$sub_array['parts'],"exploded"=>true],
            ["label"=> $sub_array['oi'],"y"=> (int)$sub_array['oil'],"exploded"=>true],
        );
        
	   echo json_encode(array(
	       "dataPoints"=>$dataPoints,
	       ));
      
    }
    
    
    public function grafik_kpb(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $tgl2 = date('m', strtotime('-1 month', strtotime($date)));
      $result=array();
      $resultM=array();
      $dataPoints=array();
      $dataPoints1=array();
      
      
      //   bulan ini
      $kpb1 = $this->db->query("select count(a.id_work_order) as ass1,'ASS 1' as a1 from tr_h2_wo_dealer a join tr_h2_wo_dealer_pekerjaan c on a.id_work_order=c.id_work_order 
      join ms_h2_jasa b on c.id_jasa=b.id_jasa where b.id_type='ASS1' and a.status='Closed' and  month(a.created_at)='$month' and year(a.created_at)='$tahun'")->row();
      $kpb2 = $this->db->query("select count(a.id_work_order) as ass2,'ASS 2' as a2 from tr_h2_wo_dealer a join tr_h2_wo_dealer_pekerjaan c on a.id_work_order=c.id_work_order 
      join ms_h2_jasa b on c.id_jasa=b.id_jasa where b.id_type='ASS2' and a.status='Closed' and  month(a.created_at)='$month' and year(a.created_at)='$tahun'")->row();
      $kpb3 = $this->db->query("select count(a.id_work_order) as ass3,'ASS 3' as a3 from tr_h2_wo_dealer a join tr_h2_wo_dealer_pekerjaan c on a.id_work_order=c.id_work_order 
      join ms_h2_jasa b on c.id_jasa=b.id_jasa where b.id_type='ASS3' and a.status='Closed' and  month(a.created_at)='$month' and year(a.created_at)='$tahun'")->row();
      $kpb4 = $this->db->query("select count(a.id_work_order) as ass4,'ASS 4' as a4 from tr_h2_wo_dealer a join tr_h2_wo_dealer_pekerjaan c on a.id_work_order=c.id_work_order 
      join ms_h2_jasa b on c.id_jasa=b.id_jasa where b.id_type='ASS4' and a.status='Closed' and  month(a.created_at)='$month' and year(a.created_at)='$tahun'")->row();
      
    //   bulan lalu
      $kpb1M = $this->db->query("select count(a.id_work_order) as ass1,'ASS 1' as a1 from tr_h2_wo_dealer a join tr_h2_wo_dealer_pekerjaan c on a.id_work_order=c.id_work_order 
      join ms_h2_jasa b on c.id_jasa=b.id_jasa where b.id_type='ASS1' and a.status='Closed' and  month(a.created_at)='$tgl2' and year(a.created_at)='$tahun'")->row();
      $kpb2M = $this->db->query("select count(a.id_work_order) as ass2,'ASS 2' as a2 from tr_h2_wo_dealer a join tr_h2_wo_dealer_pekerjaan c on a.id_work_order=c.id_work_order 
      join ms_h2_jasa b on c.id_jasa=b.id_jasa where b.id_type='ASS2' and a.status='Closed' and  month(a.created_at)='$tgl2' and year(a.created_at)='$tahun'")->row();
      $kpb3M = $this->db->query("select count(a.id_work_order) as ass3,'ASS 3' as a3 from tr_h2_wo_dealer a join tr_h2_wo_dealer_pekerjaan c on a.id_work_order=c.id_work_order 
      join ms_h2_jasa b on c.id_jasa=b.id_jasa where b.id_type='ASS3' and a.status='Closed' and  month(a.created_at)='$tgl2' and year(a.created_at)='$tahun'")->row();
      $kpb4M = $this->db->query("select count(a.id_work_order) as ass4,'ASS 4' as a4 from tr_h2_wo_dealer a join tr_h2_wo_dealer_pekerjaan c on a.id_work_order=c.id_work_order 
      join ms_h2_jasa b on c.id_jasa=b.id_jasa where b.id_type='ASS4' and a.status='Closed' and  month(a.created_at)='$tgl2' and year(a.created_at)='$tahun'")->row();
      
      
      $sub_array=array();
	   $sub_array['kpb1']=$kpb1->a1;
	   $sub_array['total_kpb1']=$kpb1->ass1;
	   $sub_array['kpb2']= $kpb2->a2;
	   $sub_array['total_kpb2']= $kpb2->ass2;
	   $sub_array['kpb3']=$kpb3->a3;
	   $sub_array['total_kpb3']=$kpb3->ass3;
	   $sub_array['kpb4']=$kpb4->a4;
	   $sub_array['total_kpb4']=$kpb4->ass4;
	   $result[]=$sub_array;
	   
	   $sub_arrayM=array();
	   $sub_arrayM['kpb1M']=$kpb1M->a1;
	   $sub_arrayM['total_kpb1M']=$kpb1M->ass1;
	   $sub_arrayM['kpb2M']= $kpb2M->a2;
	   $sub_arrayM['total_kpb2M']= $kpb2M->ass2;
	   $sub_arrayM['kpb3M']=$kpb3M->a3;
	   $sub_arrayM['total_kpb3M']=$kpb3M->ass3;
	   $sub_arrayM['kpb4M']=$kpb4M->a4;
	   $sub_arrayM['total_kpb4M']=$kpb4M->ass4;
	   $resultM[]=$sub_arrayM;
	   
            
        $dataPoints=array(
            ["label"=> $sub_array['kpb1'],"y"=> (int)$sub_array['total_kpb1']],
            ["label"=> $sub_array['kpb2'],"y"=> (int) $sub_array['total_kpb2']],
            ["label"=> $sub_array['kpb3'],"y"=> (int)$sub_array['total_kpb3']],
            ["label"=> $sub_array['kpb4'],"y"=> (int) $sub_array['total_kpb4']],
        );
        
         $dataPoints1=array(
            ["label"=> $sub_arrayM['kpb1M'],"y"=> (int)$sub_arrayM['total_kpb1M']],
            ["label"=> $sub_arrayM['kpb2M'],"y"=> (int) $sub_arrayM['total_kpb2M']],
            ["label"=> $sub_arrayM['kpb3M'],"y"=> (int)$sub_arrayM['total_kpb3M']],
            ["label"=> $sub_arrayM['kpb4M'],"y"=> (int) $sub_arrayM['total_kpb4M']],
        );
        
	    echo json_encode(array(
            "dataPoints"=>$dataPoints,
            "dataPoints1"=>$dataPoints1
            ));
      
      
    }
    
    
    public function total_ue_hari_ini(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $result=array();
      $query = $this->db->query("SELECT COUNT(*) as total_ue_hari_ini from tr_h2_wo_dealer where status='Closed' and left(created_at,10)='$date'")->row();
      $result=number_format($query->total_ue_hari_ini,0,',','.');
      
      echo json_encode(array(
          "data"=>$result
          ));
          
    }
    public function total_ue_bulan_ini(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $tgl2 = date('m', strtotime('-1 month', strtotime($date)));
      $result=array();
      $resultOnPersent = array();
      $resultM1=array();
      $query = $this->db->query("SELECT COUNT(*) as total_ue_bulan_ini from tr_h2_wo_dealer where status='Closed' and month(created_at)='$month' and year(created_at)='$tahun'")->row();
      $queryM = $this->db->query("SELECT COUNT(*) as total_ue_bulan_ini from tr_h2_wo_dealer where status='Closed' and month(created_at)='$tgl2' and year(created_at)='$tahun'")->row();
      $resultOnPersent=($query->total_ue_bulan_ini - $queryM->total_ue_bulan_ini) / $queryM->total_ue_bulan_ini * 100 ;
      $result = number_format($query->total_ue_bulan_ini,0,',','.');
      $resultM1= number_format($queryM->total_ue_bulan_ini,0,',','.');
      echo json_encode(array(
          "persent"=>$resultOnPersent,
          "data"=>$result,
          "M1"=>$resultM1
          ));
    }
    
    public function total_ue_kemarin(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tgl2 = date('Y-m-d', strtotime('-1 day', strtotime($date)));
      $result=array();
      $query = $this->db->query("SELECT COUNT(*) as total_ue_hari_ini from tr_h2_wo_dealer where status='Closed' and left(created_at,10)='$tgl2'")->row();
      $result=number_format($query->total_ue_hari_ini,0,',','.');
      
      echo json_encode(array(
          "data"=>$result,
          ));
    }
    
     public function total_ue_ytd(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y');
      $result=array();
      $query = $this->db->query("SELECT COUNT(*) as total_ue_hari_ini from tr_h2_wo_dealer where status='Closed' and year(created_at)='$date'")->row();
      $result=number_format($query->total_ue_hari_ini,0,',','.');
      
      echo json_encode(array(
          "data"=>$result,
          ));
    }
    
    
    public function test_home(){
        $data['title']	= "Dashboard";
		$data['isi']		= "home";
		$data['judul']	= "Statistik Web";
        	$this->load->view('template/header', $data);
			$this->load->view('template/aside');
			$this->load->view("index_h23_md");
			$this->load->view('template/footer');
    }
    
    public function grafik($id){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $result=array();
      $WO = $this->db->query("SELECT COUNT(*) as 'Work_Order','Work Order' as wo from tr_h2_wo_dealer where id_dealer ='$id' and status ='closed' and year(created_at)='$tahun' and month(created_at)='$month'")->row();
      $Bil = $this->db->query("SELECT COUNT(*) as 'Billing_Process','Billing Process' as bp from tr_h2_wo_dealer where id_dealer ='$id' and status ='closed' and no_njb is not NULL and year(created_at)='$tahun' and month(created_at)='$month'")->row();
      $Part = $this->db->query("SELECT COUNT(*) as 'Parts_Sales','Parts Sales' as ps from tr_h3_dealer_sales_order where id_dealer ='$id' and status ='Closed' and year(created_at)='$tahun' and month(created_at)='$month' and id_work_order is NULL")->row();
      $Inbound = $this->db->query("SELECT COUNT(*) as 'Parts_Inbound','Parts Inbound' as pi from tr_h3_dealer_good_receipt where id_dealer ='$id' and year(created_at)='$tahun' and month(created_at)='$month'")->row();
      
        $sub_array=array();
        $sub_array['wo'] =$WO->wo;
        $sub_array['total_wo'] =$WO->Work_Order;
        $sub_array['bp'] =$Bil->bp;
        $sub_array['total_billing'] =$Bil->Billing_Process;
        $sub_array['ps'] =$Part->ps;
        $sub_array['total_ps'] =$Part->Parts_Sales;
        $sub_array['inbound'] = $Inbound->pi;
        $sub_array['total_inbound'] =$Inbound->Parts_Inbound;
        $result[]= $sub_array;
        
        echo json_encode(array(
            "status"=>200,
            "values"=>$result
            ));
    }
    
     public function grafik_all_h1(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $result=array();
      $start=$this->input->post("started") == null ? date('Y-m-d') : $this->input->post("started");
      $end = $this->input->post("ended")== null ? date('Y-m-d') : $this->input->post("ended");
        
          $starts       = $this->input->post("start");
          $length       = $this->input->post("length");
          $LIMIT        = "LIMIT  $starts, $length ";
          $draw         = $this->input->post("draw");
          $search       = $this->input->post('search')['value'];
          // $where ="where id_dealer in('44', '84', '103',  '105',  '82', '97', '37', '106',  '39', '104',  '102',  '131',  '13', '2',  '51', '45', '66', '22', '128',  '3',  '43', '25', '40', '100',  '101',  '129',  '46', '85', '18', '93', '94', '95', '78', '65', '80', '125',  '126',  '47', '127',  '90', '130',  '96', '77', '113',  '116',  '1',  '4',  '117',  '8',  '123',  '132',  '41', '110',  '119',  '120',  '121',  '122',  '124',  '70', '112',  '98', '74', '71', '115',  '81', '83', '86', '107')";
          $where ="where id_dealer in(select id_dealer from ms_dealer where active=1 and h1=1)";
          $searchingColumn;
          
          if (isset($search)) {
            if ($search != '') {
               $searchingColumn = $search;
              $where .= " AND (nama_dealer LIKE '%$search%') 
                ";
                }
            }
        // $masterDealer = $this->db->query("SELECT * from ms_dealer $where group by kode_dealer_ahm $LIMIT");
        $masterDealer = $this->db->query("SELECT id_dealer, kode_dealer_md, kode_dealer_ahm, nama_dealer from ms_dealer $where $LIMIT");
         // $masterDealer2 = $this->db->query("SELECT * from ms_dealer where id_dealer in('44',  '84', '103',  '105',  '82', '97', '37', '106',  '39', '104',  '102',  '131',  '13', '2',  '51', '45', '66', '22', '128',  '3',  '43', '25', '40', '100',  '101',  '129',  '46', '85', '18', '93', '94', '95', '78', '65', '80', '125',  '126',  '47', '127',  '90', '130',  '96', '77', '113',  '116',  '1',  '4',  '117',  '8',  '123',  '132',  '41', '110',  '119',  '120',  '121',  '122',  '124',  '70', '112',  '98', '74', '71', '115',  '81', '83', '86', '107') group by kode_dealer_ahm");
         $masterDealer2 = $this->db->query("SELECT id_dealer, kode_dealer_md, kode_dealer_ahm, nama_dealer from ms_dealer where id_dealer in(select id_dealer from ms_dealer where active=1 and h1=1)");
        $index=1;
        $data=array();
        foreach($masterDealer->result() as $rows){
            $unitInbound = $this->db->query("select a.id_penerimaan_unit_dealer, a.tgl_penerimaan, a.id_goods_receipt,a.created_at, a.updated_at, b.no_po, b.no_do,
				(case when c.kode_dealer_ahm = 'TA' then c.head_office else c.kode_dealer_ahm end ) as kode_dealer_ahm				
				from tr_penerimaan_unit_dealer a 
				join tr_do_po b on a.no_do =b.no_do
				join ms_dealer c on a.id_dealer = c.id_dealer
				where date(a.created_at) BETWEEN '$start' and '$end' and a.id_goods_receipt is not null and id_goods_receipt !='' and a.status = 'close' and a.id_dealer ='$rows->id_dealer'
				order by a.created_at asc, a.updated_at asc");
				
		// $guest_book = $this->db->query("select count(1) as jmlh from tr_guest_book_new tgbn where date(created_at) BETWEEN '$start' and '$end' and id_dealer='$rows->id_dealer'");
	
		$prospect_apps = $this->db->query("select
				                            count(1) as jmlh
                            			from
                            				tr_prospek a
                            			join ms_dealer b on
                            				a.id_dealer = b.id_dealer
                            			where
                            				date(a.created_at) BETWEEN '$start' and '$end'
                            				and b.id_dealer='$rows->id_dealer' and input_from = 'sc'
                                		");
	
				$prospect = $this->db->query("select
				                            count(a.id_dealer) as dealer
                            			from
                            				tr_prospek a
                            			join ms_dealer b on
                            				a.id_dealer = b.id_dealer
                            			where
                            				date(a.created_at) BETWEEN '$start' and '$end'
                            				and a.id_list_appointment is not null
                            				and a.id_list_appointment != ''
                            				and b.id_dealer='$rows->id_dealer'
                                			union 
                                			select
                                				count(gc.id_dealer) as dealer
                                			from
                                				tr_prospek_gc gc
                                			join ms_dealer b on
                                				gc.id_dealer = b.id_dealer
                                			where
                                				date(gc.created_at) BETWEEN '$start' and '$end'
                                				and b.id_dealer='$rows->id_dealer'");
                $dealingProcess = $this->db->query("select count(a.id_dealer) as dealer
			from
				tr_spk a
			join tr_prospek b on
				a.id_customer = b.id_customer
			join ms_dealer c on
				a.id_dealer = c.id_dealer
			where
				date(a.created_at)  BETWEEN '$start' and '$end'
				and a.no_spk is not null
				and a.no_spk != ''
				and a.status_spk != 'canceled' and a.status_spk !='rejected'
				and a.id_dealer='$rows->id_dealer'
			
			union 
			select
				count(c.id_dealer) as dealer
			from
				tr_spk_gc gca
			join tr_prospek_gc gcb on
				gca.id_prospek_gc = gcb.id_prospek_gc
			join ms_dealer c on
				gca.id_dealer = c.id_dealer
			where
				date(gca.created_at) BETWEEN '$start' and '$end'
				and gca.no_spk_gc is not null
				and gca.no_spk_gc != ''
				and c.id_dealer='$rows->id_dealer'
				and gca.status = 'approved'
			");
			
				$billingProcess = $this->db->query("select
			    count(a.id_dealer) as dealer
			from
				tr_sales_order a
			join ms_dealer b on
				a.id_dealer = b.id_dealer
			where
				date(a.created_at) BETWEEN '$start' and '$end'
				and a.no_invoice is not null
				and a.no_invoice != ''
				and a.id_dealer='$rows->id_dealer'
			union 
			select
			 count(b.id_dealer) as dealer
			from
				tr_sales_order_gc gca
			join ms_dealer b on
				gca.id_dealer = b.id_dealer
			where
				date(gca.created_at) BETWEEN '$start' and '$end'
				and gca.no_invoice is not null
				and gca.no_invoice != '' 
				and b.id_dealer='$rows->id_dealer'
				");
				$handleLeasing = $this->db->query("select count(a.id_dealer) as dealer
			from
				tr_order_survey a
			join ms_dealer b on
				a.id_dealer = b.id_dealer
			join tr_spk c on
				a.no_spk = c.no_spk
			where
				date(a.created_at) BETWEEN '$start' and '$end'
				and a.no_order_survey is not null
				and a.no_order_survey != ''
				and c.status_survey = 'approved'
				and a.id_dealer='$rows->id_dealer'
			
			UNION select
				count(b.id_dealer) as dealer
			from
				tr_order_survey_gc gca
			join ms_dealer b on
				gca.id_dealer = b.id_dealer
			join tr_spk_gc gcc on
				gca.no_spk_gc = gcc.no_spk_gc
			where
				date(gca.created_at) BETWEEN '$start' and '$end'
				and gca.no_order_survey_gc is not null
				and gca.no_order_survey_gc != ''
					and b.id_dealer='$rows->id_dealer'
				and gcc.status_survey = 'approved' ");
				
				$delivery = $this->db->query("select count(a.id_dealer) as dealer 
				from tr_sales_order a
				join ms_dealer b on a.id_dealer = b.id_dealer
				where date(a.created_at) BETWEEN '$start' and '$end' and a.delivery_document_id is not null and a.delivery_document_id !='' and a.id_dealer ='$rows->id_dealer'
				order by a.created_at asc, a.updated_at asc");
				
				
				$documentHandling = $this->db->query("select
			    count(distinct(c.id_sales_order)) as dealer
			from
				tr_faktur_stnk_detail c
			left join tr_tandaterima_stnk_konsumen_detail b on
				c.no_mesin = b.no_mesin
			left join tr_tandaterima_stnk_konsumen d on
				b.kd_stnk_konsumen = d.kd_stnk_konsumen
			join ms_dealer a on
				a.id_dealer = d.id_dealer
			where
				date(d.tgl_terima_stnk) BETWEEN '$start' and '$end' and d.jenis_cetak = 'stnk'
				and c.id_sales_order is not null
				and c.id_sales_order != '' and d.tgl_terima_stnk is not NULL and d.tgl_terima_stnk !=''
				and a.id_dealer ='$rows->id_dealer'
		");
				
        $sub_array=array();
        $sub_array[] = $index++;
        $sub_array[] = $rows->kode_dealer_ahm;
        $sub_array[] = $rows->nama_dealer;
        $sub_array[] = $unitInbound->num_rows();
        $sub_array[] = $prospect->row()->dealer;
	$sub_array[] = $prospect_apps->row()->jmlh;
        $sub_array[] = $dealingProcess->row()->dealer;
        $sub_array[] = $billingProcess->row()->dealer;
        $sub_array[] = $handleLeasing->row()->dealer;
        $sub_array[] = $delivery->row()->dealer;
        $sub_array[] = $documentHandling->row()->dealer;
        $result[]=$sub_array;
        }
         
      $output = array(
      "draw"            =>     intval($this->input->post("draw")),
      "recordsFiltered" =>     $masterDealer2->num_rows(),
      "data"            =>     $result,
     
    );
    echo json_encode($output);
   
       
    }
    
    public function grafik_monitoring_h1(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $result=array();
      $start=$this->input->post("started") == null ? date('Y-m-01') : $this->input->post("started");
      $end = $this->input->post("ended")== null ? date('Y-m-d') : $this->input->post("ended");
      
        $unitIn = $this->db->query("select COUNT(DISTINCT(c.kode_dealer_ahm)) as total, a.id_penerimaan_unit_dealer, a.tgl_penerimaan, a.id_goods_receipt,a.created_at, a.updated_at, b.no_po, b.no_do,
				(case when c.kode_dealer_ahm = 'TA' then c.head_office else c.kode_dealer_ahm end ) as kode_dealer_ahm				
				from tr_penerimaan_unit_dealer a 
				join tr_do_po b on a.no_do =b.no_do
				join ms_dealer c on a.id_dealer = c.id_dealer
				where date(a.created_at) BETWEEN '$start' and '$end' and a.id_goods_receipt is not null and id_goods_receipt !='' and a.status = 'close' 
				order by a.created_at asc, a.updated_at asc");
		$prospecting = $this->db->query("select COUNT(DISTINCT(a.kode_dealer_ahm)) as total from tr_prospek b join ms_dealer a on a.id_dealer = b.id_dealer where date(b.created_at) 
                  BETWEEN '$start' and '$end' and a.active = 1");

		$prospecting_apps = $this->db->query("select COUNT(DISTINCT(a.kode_dealer_ahm)) as total from tr_prospek b join ms_dealer a on a.id_dealer = b.id_dealer where date(b.created_at) 
                  BETWEEN '$start' and '$end' and input_from ='sc'");

        $dealing = $this->db->query("           
         SELECT COUNT(DISTINCT(a.kode_dealer_ahm)) as total from ms_dealer a join tr_spk b on a.id_dealer =b.id_dealer 
         where date(b.created_at)  BETWEEN '$start' and '$end' and b.no_spk is not null 
         and b.status_spk !=''
         and b.status_spk != 'canceled' and b.status_spk !='rejected'");
         
         $billing = $this->db->query("SELECT COUNT(DISTINCT(a.kode_dealer_ahm)) as total from ms_dealer a join tr_sales_order b on a.id_dealer =b.id_dealer 
			where
				date(b.created_at) BETWEEN '$start' and '$end'
				and b.no_invoice is not null
				and b.no_invoice != ''");
				
		$handleLeasing = $this->db->query(" select COUNT(DISTINCT(b.kode_dealer_ahm)) as total
			from
				tr_order_survey a
			join ms_dealer b on
				a.id_dealer = b.id_dealer
			join tr_spk c on
				a.no_spk = c.no_spk
			where
				date(a.created_at) BETWEEN '$start' and '$end'
				and a.no_order_survey is not null
				and a.no_order_survey != ''
				and c.status_survey = 'approved'");
		$delivery = $this->db->query("	select COUNT(DISTINCT(b.kode_dealer_ahm)) as total
				from tr_sales_order a
				join ms_dealer b on a.id_dealer = b.id_dealer
				where date(a.created_at) BETWEEN '$start' and '$end' and a.delivery_document_id is not null and a.delivery_document_id !=''");
				
		$documentHandling = $this->db->query("		select
			    count(distinct(a.kode_dealer_ahm)) as total
			from
				tr_faktur_stnk_detail c
			left join tr_tandaterima_stnk_konsumen_detail b on
				c.no_mesin = b.no_mesin
			left join tr_tandaterima_stnk_konsumen d on
				b.kd_stnk_konsumen = d.kd_stnk_konsumen
			join ms_dealer a on
				a.id_dealer = d.id_dealer
			where
				date(d.tgl_terima_stnk) BETWEEN '$start' and '$end' and d.jenis_cetak = 'stnk'
				and c.id_sales_order is not null
				and c.id_sales_order != '' and d.tgl_terima_stnk is not NULL and d.tgl_terima_stnk !=''");
			
			$sub_array=array();	
			$sub_array['unit_inbound_label']="Unit Inbound";
			$sub_array['unit_inbound']=round(($unitIn->row()->total / 48) * 100);
			$sub_array['prospecting_label']="Prospecting";
			$sub_array['prospecting']=round(($prospecting->row()->total / 48) * 100);
			$sub_array['prospecting_apps_label']="Prospecting Apps";
			$sub_array['prospecting_apps']=round(($prospecting_apps->row()->total / 48) * 100);
			$sub_array['dealing_label']="Dealing Process";
			$sub_array['dealing']=round(($dealing->row()->total / 48) * 100);
			$sub_array['billing_label']="Billing Process";
			$sub_array['billing']=round(($billing->row()->total / 48) * 100);
			$sub_array['handle_label']="Handle Leasing";
			$sub_array['handle_leasing']=round(($handleLeasing->row()->total / 48) * 100);
			$sub_array['delivery_label']="Delivery Process";
			$sub_array['delivery_process']=round(($delivery->row()->total / 48) * 100);
			$sub_array['document_label']="Document Handling";
			$sub_array['document_handling']=round(($documentHandling->row()->total / 48) * 100);
			$result[]= $sub_array;
			 
        
         $dataPoints=array(
            ["label"=> $sub_array['unit_inbound_label'],"y"=> (int)$sub_array['unit_inbound'],"exploded"=>true],
            ["label"=> $sub_array['prospecting_label'],"y"=> (int)$sub_array['prospecting'],"exploded"=>true],
            ["label"=> $sub_array['prospecting_apps_label'],"y"=> (int)$sub_array['prospecting_apps'],"exploded"=>true],
            ["label"=> $sub_array['dealing_label'],"y"=> (int)$sub_array['dealing'],"exploded"=>true],
            ["label"=> $sub_array['billing_label'],"y"=> (int)$sub_array['billing'],"exploded"=>true],
            ["label"=> $sub_array['handle_label'],"y"=> (int)$sub_array['handle_leasing'],"exploded"=>true],
            ["label"=> $sub_array['delivery_label'],"y"=> (int)$sub_array['delivery_process'],"exploded"=>true],
            ["label"=> $sub_array['document_label'],"y"=> (int)$sub_array['document_handling'],"exploded"=>true],
        );
        
	   echo json_encode(array(
	       "dataPoints"=>$dataPoints,
	       ));
		
    }
    
    
    
      public function grafik_all(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $result=array();
      $WO = $this->db->query("SELECT DISTINCT(id_dealer) as 'Work_Order','Work Order' as wo from tr_h2_wo_dealer where status ='closed' and year(created_at)='$tahun' and month(created_at)='$month' and id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714')");
      $Bil = $this->db->query("SELECT DISTINCT(id_dealer) as 'Billing_Process','Billing Process' as bp from tr_h2_wo_dealer where status ='closed' and no_njb is not NULL and year(created_at)='$tahun' and month(created_at)='$month' and id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714')");
      $Part = $this->db->query("SELECT DISTINCT(id_dealer) as 'Parts_Sales','Parts Sales' as ps from tr_h3_dealer_sales_order where status ='Closed' and year(created_at)='$tahun' and month(created_at)='$month' and id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714')");
      $Inbound = $this->db->query("SELECT DISTINCT(id_dealer) as 'Parts_Inbound','Parts Inbound' as pi from tr_h3_dealer_good_receipt where year(created_at)='$tahun' and month(created_at)='$month' and id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714')");
      $rec = $this->db->query("SELECT DISTINCT(id_dealer) as 'Print_Receipt','Print Receipt' as pr from tr_h2_receipt_customer where year(created_at)='$tahun' and month(created_at)='$month'");
      
        $sub_array=array();
        $sub_array['wos'] =$WO->row()->wo;
        $sub_array['total_wos'] =round(($WO->num_rows() / 55) * 100);
        $sub_array['bps'] =$Bil->row()->bp;
        $sub_array['total_billings'] =round(($Bil->num_rows() / 55) *100);
        $sub_array['pss'] =$Part->row()->ps;
        $sub_array['total_pss'] =round(($Part->num_rows() / 55)*100);
        $sub_array['inbounds'] = $Inbound->row()->pi;
        $sub_array['total_inbounds'] =round(($Inbound->num_rows()/55)*100);
        $sub_array['rec'] =$rec->row()->pr;
        $sub_array['receipt'] =round(($rec->num_rows()/60)*100);
        $sub_array['w1'] = $WO->num_rows();
        $sub_array['b1']=$Bil->num_rows();
        $sub_array['ps1']=$Part->num_rows();
        $sub_array['pi1'] =$Inbound->num_rows();
        $sub_array['rec1'] =$rec->num_rows();
        $result[]= $sub_array;
        
        echo json_encode(array(
            "status"=>200,
            "values"=>$result
            ));
    }
    
    
    public function grafik_all_new(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $start_date=$this->input->post("started") == null ? "$tahun-$month-01" : $this->input->post("started");
      $end = $this->input->post("ended")== null ? date('Y-m-d') : $this->input->post("ended");
      $result=array();
      $WOSC = $this->db->query("SELECT DISTINCT(id_dealer) as 'Work_Order','Work Order' as wo from tr_h2_wo_dealer where status ='closed' and input_from ='sc' and left(created_at,10) between '$start_date' and '$end' and id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714', '5','10','28','56','69','715')");
      $WO = $this->db->query("SELECT DISTINCT(id_dealer) as 'Work_Order','Work Order' as wo from tr_h2_wo_dealer where status ='closed' and left(created_at,10) between '$start_date' and '$end' and id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714', '5','10','28','56','69','715')");
      $Bil = $this->db->query("SELECT DISTINCT(id_dealer) as 'Billing_Process','Billing Process' as bp from tr_h2_wo_dealer where status ='closed' and no_njb is not NULL and left(created_at,10) between '$start_date' and '$end' and id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714', '5','10','28','56','69','715')");
      $Part = $this->db->query("SELECT DISTINCT(id_dealer) as 'Parts_Sales','Parts Sales' as ps from tr_h3_dealer_sales_order where status ='Closed' and left(created_at,10) between '$start_date' and '$end' and id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714', '5','10','28','56','69','715')");
      $Inbound = $this->db->query("SELECT DISTINCT(id_dealer) as 'Parts_Inbound','Parts Inbound' as pi from tr_h3_dealer_good_receipt where left(created_at,10) between '$start_date' and '$end' and id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714', '5','10','28','56','69','715')");
      $rec = $this->db->query("SELECT DISTINCT(id_dealer) as 'Print_Receipt','Print Receipt' as pr from tr_h2_receipt_customer where left(created_at,10) between '$start_date' and '$end'");
      
        $total_dealer = 61;

        $sub_array=array();
        $sub_array['wos'] =$WO->row()->wo;
        $sub_array['total_wos_sc'] =round(($WOSC->num_rows() / $total_dealer) * 100);
        $sub_array['total_wos'] =round(($WO->num_rows() / $total_dealer) * 100);
        $sub_array['bps'] =$Bil->row()->bp;
        $sub_array['total_billings'] =round(($Bil->num_rows() / $total_dealer) *100);
        $sub_array['pss'] =$Part->row()->ps;
        $sub_array['total_pss'] =round(($Part->num_rows() / $total_dealer)*100);
        $sub_array['inbounds'] = $Inbound->row()->pi;
        $sub_array['total_inbounds'] =round(($Inbound->num_rows()/$total_dealer)*100);
        $sub_array['rec'] =$rec->row()->pr;
        $sub_array['receipt'] =round(($rec->num_rows()/60)*100);
        $sub_array['w1'] = $WO->num_rows();
        $sub_array['wsc1'] = $WOSC->num_rows();
        $sub_array['b1']=$Bil->num_rows();
        $sub_array['ps1']=$Part->num_rows();
        $sub_array['pi1'] =$Inbound->num_rows();
        $sub_array['rec1'] =$rec->num_rows();
        $result[]= $sub_array;
        
        echo json_encode(array(
            "status"=>200,
            "values"=>$result
            ));
    }
    
    public function grafik_allM1(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $tgl2 = date('m', strtotime('-1 month', strtotime($date)));
      $result=array();
      $WO = $this->db->query("SELECT DISTINCT(id_dealer) as 'Work_Order','Work Order' as wo from tr_h2_wo_dealer where status ='closed' and year(created_at)='$tahun' and month(created_at)='$tgl2' and id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714')");
      $Bil = $this->db->query("SELECT DISTINCT(id_dealer) as 'Billing_Process','Billing Process' as bp from tr_h2_wo_dealer where status ='closed' and no_njb is not NULL and year(created_at)='$tahun' and month(created_at)='$tgl2' and id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714')");
      $Part = $this->db->query("SELECT DISTINCT(id_dealer) as 'Parts_Sales','Parts Sales' as ps from tr_h3_dealer_sales_order where status ='Closed' and year(created_at)='$tahun' and month(created_at)='$tgl2'  and id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714')");
      $Inbound = $this->db->query("SELECT DISTINCT(id_dealer) as 'Parts_Inbound','Parts Inbound' as pi from tr_h3_dealer_good_receipt where year(created_at)='$tahun' and month(created_at)='$tgl2' and id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714')");
       $rec = $this->db->query("SELECT DISTINCT(id_dealer) as 'rec','Print Receipt' as pr from tr_h2_receipt_customer where year(created_at)='$tahun' and month(created_at)='$tgl2'");
        $sub_array=array();
        $sub_array['wos'] =$WO->row()->wo;
        $sub_array['total_wos'] =round(($WO->num_rows() / 55) * 100);
        $sub_array['bps'] =$Bil->row()->bp;
        $sub_array['total_billings'] =round(($Bil->num_rows() / 55) *100);
        $sub_array['pss'] =$Part->row()->ps;
        $sub_array['total_pss'] =round(($Part->num_rows() / 55)*100);
        $sub_array['inbounds'] = $Inbound->row()->pi;
        $sub_array['total_inbounds'] =round(($Inbound->num_rows()/55)*100);
        $sub_array['rec'] =$rec->row()->pr;
        $sub_array['receipt'] =round(($rec->num_rows()/60)*100);
        
        $sub_array['w1'] = $WO->num_rows();
        $sub_array['b1']=$Bil->num_rows();
        $sub_array['ps1']=$Part->num_rows();
        $sub_array['pi1'] =$Inbound->num_rows();
        $sub_array['rec1'] =$rec->num_rows();
        
        $result[]= $sub_array;
        
        echo json_encode(array(
            "status"=>200,
            "values"=>$result,
            ));
    }
    
    public function grafik_wo(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $result=array();
      
      $dealer = $this->db->query("select id_dealer,nama_dealer as dealer from ms_dealer where id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714') and active ='1' order by nama_dealer")->result();
      
      foreach($dealer as $rows){
          $woDealer = $this->db->query("SELECT COUNT(*) AS totalWo from tr_h2_wo_dealer where id_dealer ='$rows->id_dealer' and status='closed' and year(created_at)='$tahun' and month(created_at)='$month'")->row();
          $sub_array = array();
          $sub_array['dealer'] =$rows->dealer;
          $sub_array['totalWO'] = $woDealer->totalWo;
          $result[] = $sub_array;
      }
      
      echo json_encode(array(
          "status"=>200,
          "values"=>$result
          ));
    }
    
    public function grafik_bp(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $result=array();
      
      $dealer = $this->db->query("select id_dealer,nama_dealer as dealer from ms_dealer where id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714') and active ='1' order by nama_dealer")->result();
      
      foreach($dealer as $rows){
          $woDealer = $this->db->query("SELECT COUNT(*) AS totalBp from tr_h2_wo_dealer where id_dealer ='$rows->id_dealer' and status='closed' and no_njb is not NULL and year(created_at)='$tahun' and month(created_at)='$month'")->row();
          $sub_array = array();
          $sub_array['dealer'] =$rows->dealer;
          $sub_array['totalBP'] = $woDealer->totalBp;
          $result[] = $sub_array;
      }
      
      echo json_encode(array(
          "status"=>200,
          "values"=>$result
          ));
    }
    
      public function grafik_receipt(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $result=array();
      
      $dealer = $this->db->query("select id_dealer,nama_dealer as dealer from ms_dealer where id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714') and active ='1' order by nama_dealer")->result();
      
      foreach($dealer as $rows){
          $woDealer = $this->db->query("SELECT COUNT(*) AS total_rec from tr_h2_receipt_customer where id_dealer ='$rows->id_dealer' and year(created_at)='$tahun' and month(created_at)='$month'")->row();
          $sub_array = array();
          $sub_array['dealer'] =$rows->dealer;
          $sub_array['total_rec'] = $woDealer->total_rec;
          $result[] = $sub_array;
      }
      
      echo json_encode(array(
          "status"=>200,
          "values"=>$result
          ));
    }
    
     public function grafik_ps(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $result=array();
      
      $dealer = $this->db->query("select id_dealer,nama_dealer as dealer from ms_dealer where id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714') and active ='1' order by nama_dealer")->result();
      
      foreach($dealer as $rows){
          $woDealer = $this->db->query("SELECT COUNT(*) AS totalPs from tr_h3_dealer_sales_order where id_dealer ='$rows->id_dealer' and status='closed' and id_work_order is NULL and year(created_at)='$tahun' and month(created_at)='$month'")->row();
          $sub_array = array();
          $sub_array['dealer'] =$rows->dealer;
          $sub_array['totalPs'] = $woDealer->totalPs;
          $result[] = $sub_array;
      }
      
      echo json_encode(array(
          "status"=>200,
          "values"=>$result
          ));
    }
    
     public function grafik_pi(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $result=array();
      
      $dealer = $this->db->query("select id_dealer,nama_dealer,kode_dealer_ahm as dealer from ms_dealer where id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714') and active ='1' ORDER BY nama_dealer")->result();
      
      foreach($dealer as $rows){
          $woDealer = $this->db->query("SELECT COUNT(*) AS totalPi from tr_h3_dealer_good_receipt where id_dealer in('$rows->id_dealer') and year(created_at)='$tahun' and month(created_at)='$month' ")->row();
         
          $sub_array = array();
          $sub_array['dealer'] =$rows->nama_dealer;
          $sub_array['totalPi'] = $woDealer->totalPi;
          $result[] = $sub_array;
      }
      
      echo json_encode(array(
          "status"=>200,
          "values"=>$result
          ));
    }
    
    public function kelompok_part(){
        $query = $this->db->get('ms_kelompok_part');
        $result = array();
		foreach($query->result() as $rows){
			array_push($result,array(
				'kelompok_part'=>$rows->kelompok_part,
			
			));
		}
		echo json_encode(array(
			'status'=>200,
			'values'=>$result
		));
    }

    public function report_stock_amount($id){
        $query = $this->db->query("SELECT a.id_part,b.nama_part,a.stock from ms_h3_dealer_stock a join ms_part b on a.id_part=b.id_part where a.stock > 0 and a.id_dealer ='$id'
        or a.id_part in(select d.id_part from tr_h3_dealer_sales_order_parts d join tr_h3_dealer_sales_order e on d.nomor_so=e.nomor_so where e.id_dealer ='$id')
       group by a.id_part");
        
        $result=array();
        $index=1;
        foreach($query->result() as $rows){
            array_push($result,array(
                'index'=>$index++,
                'id_part'=>$rows->id_part,
                'nama_part'=>$rows->nama_part,
                'stock'=>$rows->stock
                ));
                
           
        }
         echo json_encode(array(
			    'status'=>200,
			    'values'=>$result
	        ));
    }
    
     public function fetch_penerimaan(){
     
     $fetchData = $this->make_query_penerimaan();
     $data = array();
     $index=1;
    foreach ($fetchData as $rs) {
      $sub_array = array();
      $status = '';
      $button = '';
      $sub_array[] = $index++;
      $sub_array[] = $rs->id_good_receipt;
      $sub_array[] = $rs->nomor_po;
      $sub_array[] = substr(date_dmy($rs->tanggal_receipt),0,10);
      $sub_array[] = $rs->id_reference;
      $sub_array[] = "<a href=\"https://www.sinarsentosaprimatama.com/h23_api/cetak_penerimaan?id=$rs->id_good_receipt\" className={classes.btnPdf} type=\"button\" target=\"_blank\" style=\"text-decoration:none;\"><i class=\"fa fa-download\"></i> Cetak</a>";
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($this->input->post("draw")),
      "recordsFiltered" =>     $this->make_query_penerimaan(true),
      "data"            =>     $data,
     
    );
    echo json_encode($output);
      
    }
    
     public function make_query_penerimaan($recordsFiltered = null){
      $start        = $this->input->post('start');
      $length       = $this->input->post('length');
      $limit        = "LIMIT $start, $length";
      $id_dealer    = $this->input->get('id');

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST['order'] : '',
      'order_column' => ['id_good_receipt', 'nomor_po', 'id_reference','tanggal_receipt'],
      'search' => $this->input->post('search')['value'],
      'id_dealer' =>  $id_dealer,
    ];

        if ($recordsFiltered == true) {
          return $this->h23_api_model->getDataPenerimaan($filter)->num_rows();
        } else {
          return $this->h23_api_model->getDataPenerimaan($filter)->result();
        }
    }
    
    public function fetch_stock_amount(){
      $fetchData = $this->make_query();
      
     $data = array();
     $index=1;
    foreach ($fetchData as $rs) {
      $sub_array = array();
      $status = '';
      $button = '';
      $sub_array[] = $index++;
      $sub_array[] = $rs->id_part;
      $sub_array[] = $rs->nama_part;
      $sub_array[] = $rs->stock;
      $sub_array[] = number_format($rs->harga,0,',','.');
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($this->input->post("draw")),
      "recordsFiltered" =>     $this->make_query(true),
      "data"            =>     $data,
     
    );
    echo json_encode($output);
      
    }
    
    public function make_query($recordsFiltered = null){
      $start        = $this->input->post('start');
      $length       = $this->input->post('length');
      $limit        = "LIMIT $start, $length";
      $id_dealer    = $this->input->get('id');

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST['order'] : '',
      'order_column' => ['id_part', 'nama_part', 'stock'],
      'search' => $this->input->post('search')['value'],
      'id_dealer' =>  $id_dealer,
    ];

        if ($recordsFiltered == true) {
          return $this->h23_api_model->getDataAmount($filter)->num_rows();
        } else {
          return $this->h23_api_model->getDataAmount($filter)->result();
        }
    }


    public function fetch_all_module_new(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $start_date=$this->input->post("started") == null ? "$tahun-$month-01" : $this->input->post("started");
      $end = $this->input->post("ended")== null ? date('Y-m-d') : $this->input->post("ended");
      
      $start        = $this->input->post('start');
      $length       = $this->input->post('length');
      $limit        = "LIMIT $start, $length ";
      $draw         = $this->input->post("draw");
      $search       = $this->input->post("search")["value"];
      $orders       = isset($_POST["order"]) ? $_POST["order"] : ''; 
      
        $where ="WHERE 1=1 AND id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714' ,'5','10','28','56','69','715') and active ='1' ";
        $where2 ="WHERE 1=1 AND id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714' ,'5','10','28','56','69','715') and active ='1' ";
       
        if (isset($search)) {
          if ($search != '') {
                $where .= " AND (nama_dealer LIKE '%$search%') ";
                $where2 .= " AND (nama_dealer LIKE '%$search%') ";
              }
          }
      
          if (isset($orders)) {
            if ($orders != '') {
              $order = $orders;
              $order_column = [];
              $order_clm  = $order_column[$order[0]['column']];
              $order_by   = $order[0]['dir'];
              $where .= " ORDER BY $order_clm $order_by ";
              $where2 .= " ORDER BY $order_clm $order_by ";
            } else {
              $where .= " ORDER BY nama_dealer ASC ";
              $where2 .= " ORDER BY nama_dealer ASC ";
            }
          } else {
            $where .= " ORDER BY nama_dealer ASC ";
          }
          if (isset($limit)) {
            if ($limit != '') {
              $where .= ' ' . $limit;
            }
          }
      
      
      
      $fetchData = $this->db->query("select id_dealer,nama_dealer,kode_dealer_ahm as dealer from ms_dealer $where")->result();
      $fetchData2 = $this->db->query("select id_dealer,nama_dealer,kode_dealer_ahm as dealer from ms_dealer $where2")->num_rows();
      $data = array();
      
      $index=1;
    foreach ($fetchData as $rs) {
      $WOSC = $this->db->query("SELECT COUNT(*) as 'Work_Order','Work Order' as wo from tr_h2_wo_dealer where id_dealer ='$rs->id_dealer' and status ='closed' and input_from ='sc' and left(created_at,10) >= '$start_date' and left(created_at,10) <='$end'")->row();
      $WO = $this->db->query("SELECT COUNT(*) as 'Work_Order','Work Order' as wo from tr_h2_wo_dealer where id_dealer ='$rs->id_dealer' and status ='closed' and (input_from !='sc' or input_from = '' or input_from is NULL) and left(created_at,10) >= '$start_date' and left(created_at,10) <='$end'")->row();
      $Bil = $this->db->query("SELECT COUNT(*) as 'Billing_Process','Billing Process' as bp from tr_h2_wo_dealer where id_dealer ='$rs->id_dealer' and status ='closed' and no_njb is not NULL and left(created_at,10) >= '$start_date' and left(created_at,10) <='$end'")->row();
      $Part = $this->db->query("SELECT COUNT(*) as 'Parts_Sales','Parts Sales' as ps from tr_h3_dealer_sales_order where id_dealer ='$rs->id_dealer' and status ='Closed' and left(created_at,10) >= '$start_date' and left(created_at,10) <='$end'")->row();
      $Inbound = $this->db->query("SELECT COUNT(*) as 'Parts_Inbound','Parts Inbound' as pi from tr_h3_dealer_good_receipt where id_dealer ='$rs->id_dealer' and left(created_at,10) >= '$start_date' and left(created_at,10) <='$end'")->row();
      $sub_array = array();
      $status = '';
      $button = '';
      $sub_array[] = $index++;
      $sub_array[] = $rs->dealer;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $WOSC->Work_Order;
      $sub_array[] = $WO->Work_Order;
      $sub_array[] = $Bil->Billing_Process;
      $sub_array[] = $Part->Parts_Sales;
      $sub_array[] = $Inbound->Parts_Inbound;
    //   $sub_array[] = $WOSC->Work_Order + $WO->Work_Order + $Bil->Billing_Process +  $Part->Parts_Sales + $Inbound->Parts_Inbound;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>  intval($draw),
      "recordsFiltered" =>  $fetchData2,
      "data"          =>  $data,
     
    );
    echo json_encode($output);
    }
    
        public function fetch_all_module(){
      $start        = $this->input->post('start');
      $length       = $this->input->post('length');
      $limit        = "LIMIT $start, $length";
      $draw         = $this->input->post("draw");
      
      $fetchData = $this->db->query("select id_dealer,nama_dealer,kode_dealer_ahm as dealer from ms_dealer where id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714') and active ='1' ORDER BY nama_dealer ASC $limit")->result();
      $fetchData2 = $this->db->query("select id_dealer,nama_dealer,kode_dealer_ahm as dealer from ms_dealer where id_dealer in('1',	'2',	'4',	'6',	'8',	'13',	'18',	'19',	'22',	'23',	'25',	'29',	'30',	'38',	'39',	'40',	'41',	'43',	'44',	'46',	'47',	'51',	'54',	'58',	'64',	'65',	'66',	'70',	'71',	'74',	'76',	'77',	'78',	'80',	'81',	'82',	'83',	'84',	'85',	'86',	'88',	'90',	'91',	'94',	'96',	'97',	'98',	'101',	'102',	'103',	'104',	'105',	'106',	'107','714') and active ='1' ORDER BY nama_dealer ASC")->num_rows();
      $data = array();
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $index=1;
    foreach ($fetchData as $rs) {
      $WO = $this->db->query("SELECT COUNT(*) as 'Work_Order','Work Order' as wo from tr_h2_wo_dealer where id_dealer ='$rs->id_dealer' and status ='closed' and year(created_at)='$tahun' and month(created_at)='$month'")->row();
      $Bil = $this->db->query("SELECT COUNT(*) as 'Billing_Process','Billing Process' as bp from tr_h2_wo_dealer where id_dealer ='$rs->id_dealer' and status ='closed' and no_njb is not NULL and year(created_at)='$tahun' and month(created_at)='$month'")->row();
      $Part = $this->db->query("SELECT COUNT(*) as 'Parts_Sales','Parts Sales' as ps from tr_h3_dealer_sales_order where id_dealer ='$rs->id_dealer' and status ='Closed' and year(created_at)='$tahun' and month(created_at)='$month'")->row();
      $Inbound = $this->db->query("SELECT COUNT(*) as 'Parts_Inbound','Parts Inbound' as pi from tr_h3_dealer_good_receipt where id_dealer ='$rs->id_dealer' and year(created_at)='$tahun' and month(created_at)='$month'")->row();
      $sub_array = array();
      $status = '';
      $button = '';
      $sub_array[] = $index++;
      $sub_array[] = $rs->dealer;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $WO->Work_Order;
      $sub_array[] = $Bil->Billing_Process;
      $sub_array[] = $Part->Parts_Sales;
      $sub_array[] = $Inbound->Parts_Inbound;
      $sub_array[] = $WO->Work_Order + $Bil->Billing_Process +  $Part->Parts_Sales + $Inbound->Parts_Inbound;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>  intval($draw),
      "recordsFiltered" =>  $fetchData2,
      "values"          =>  $data,
     
    );
    echo json_encode($output);
    }
    
    
    public function download_po_reg(){
        $id = $_GET['id'];
        $data['data']=$this->db->query("SELECT a.po_id,a.id_dealer,a.tanggal_order,a.batas_waktu,a.po_type,a.id_salesman,b.kode_dealer_ahm,b.nama_dealer,b.alamat from tr_h3_dealer_purchase_order a join ms_dealer b on a.id_dealer=b.id_dealer where a.po_id='$id'")->row_array();
        $data['sparepart']=$this->db->query("select a.*,b.nama_part,b.harga_dealer_user from tr_h3_dealer_purchase_order_parts a join ms_part b on a.id_part=b.id_part where a.po_id='$id'")->result();

        $this->load->view('po_reg_excel',$data);
    }
    
      public function download_po_fix(){
        $id = $_GET['id'];
        $data['data']=$this->db->query("SELECT a.po_id,a.id_dealer,a.produk,a.tanggal_order,a.batas_waktu,a.po_type,a.id_salesman,b.kode_dealer_ahm,b.nama_dealer,b.alamat from tr_h3_dealer_purchase_order a join ms_dealer b on a.id_dealer=b.id_dealer where a.po_id='$id'")->row_array();
        $data['sparepart']=$this->db->query("select a.*,b.nama_part,b.harga_dealer_user,if(b.sim_part='1','Simpart','Non Simpart') as simpart,b.kelompok_part,b.minimal_order from tr_h3_dealer_purchase_order_parts a join ms_part b on a.id_part=b.id_part where a.po_id='$id'")->result();

        $this->load->view('po_fix_excel',$data);
    }

    public function login(){
        if(!empty($_POST['username']) && !empty($_POST['password'])){
            $username = $_POST['username'];
            $password = $_POST['password'];
            $result = array();
            $query = $this->db->query("SELECT a.id_dealer,a.nama_lengkap,b.username,b.id_user,b.jenis_user from ms_karyawan_dealer a join ms_user b on a.id_karyawan_dealer=b.id_karyawan_dealer where b.username='$username' and b.admin_password='$password' union select c.id_dealer,c.nama_lengkap,b.username,b.id_user,b.jenis_user from ms_karyawan c join ms_user b on c.id_karyawan=b.id_karyawan_dealer where b.username='$username' and b.admin_password='$password'");
            if($query->num_rows() == 1){
               $data = [
                "success"=>true,
                "id_user"=>$query->row()->id_user,
                "user"=>$query->row()->username,
                "id_dealer"=>$query->row()->id_dealer,
                "nama"=>$query->row()->nama_lengkap ,
                "jenis_user"=>$query->row()->jenis_user =="Main Dealer" ? "1" : "0",
               ];
               echo json_encode([
                   "status"=>200,
                   "values"=>$data,
               ]);
            }else{
                echo json_encode(
                    [
                        "error"=>true,
                        "message"=>"Username atau Password Salah"
                    ]
                );
            }
        }else{
            echo json_encode(
                [
                    "error"=>true,
                    "message"=>"Username dan Password tidak valid"
                ]
            );
        }
    }

    public function get_allUser(){
        $data = $this->db->get_where('ms_user',['active'=>'1']);

		$result = array();
		foreach($data->result() as $rows){
			array_push($result,array(
				'id_user'=>$rows->id_user,
				// 'username'=>$rows->username,
				// 'nama'=>$rows->nama,
    //             'no_hp'=>$rows->no_hp,
    //             'status'=>$rows->status,
    //             'role'=>$rows->role
			));
		}
		echo json_encode(array(
			'status'=>200,
			'values'=>$result
		));
    }

    public function po_number($id){
        $query = $this->db->get_where('tr_h3_dealer_purchase_order',['id_dealer'=>$id,'status'=>'Submitted','po_type'=>'REG']);
        $result = array();
		foreach($query->result() as $rows){
			array_push($result,array(
				'nomor_po'=>$rows->po_id,			
			));
		}
		echo json_encode(array(
			'status'=>200,
			'values'=>$result
		));
    }
    
     public function po_number_fix($id){
        $query = $this->db->get_where('tr_h3_dealer_purchase_order',['id_dealer'=>$id,'status'=>'Submitted','po_type'=>'FIX']);
        $result = array();
		foreach($query->result() as $rows){
			array_push($result,array(
				'nomor_po'=>$rows->po_id,			
			));
		}
		echo json_encode(array(
			'status'=>200,
			'values'=>$result
		));
    }

    public function get_byID($id){
        $query = $this->db->get_where('user',['id'=>$id]);
        if($query->num_rows() == 1){
            $data = [
             "nama"=>$query->row()->nama,
             "user"=>$query->row()->username
            ];
            echo json_encode([
                "status"=>200,
                "values"=>$data,
            ]);
         }else{
             echo json_encode(
                 [
                     "error"=>true,
                     "message"=>"User tidak ditemukan"
                 ]
             );
         }
    }

    public function get_allBarang(){

        $query_barang = $this->db->get('barang');
        $result=array();
        foreach($query_barang->result() as $rows){
                    $x['id_barang']=$rows->id;
                    $x['nama_barang']=$rows->nama_barang;
                    $x['kategori']=$rows->kategori;
                    $x['jenis']=$rows->jenis;
                    $x['kondisi']=$rows->kondisi;
                    $x['harga']=$rows->harga;
                    $x['sampul']=$rows->foto;
                    $x['deskripsi']=$rows->deskripsi;
                    $x['foto']=array();
                    $id = $rows->id;
                    $query = $this->db->get_where('detail_barang',['id_barang'=>$id])->result();
                    foreach($query as $row){
                        $y['foto']=$row->foto;
                        array_push($x['foto'], $y);
                    }
                    array_push($result, $x);
            }
            
            echo json_encode(array(
			    'status'=>200,
			    'values'=>$result
		    ));
    }

    public function get_barangByID($id){
        $query= $this->db->get_where('barang',['id'=>$id]);
        $result=array();
        if($query->num_rows() == 1){
            $data = [
                $x['id_barang']=$query->row()->id,
                $x['nama_barang']=$query->row()->nama_barang,
                $x['kategori']=$query->row()->kategori,
                $x['jenis']=$query->row()->jenis,
                $x['kondisi']=$query->row()->kondisi,
                $x['harga']=$query->row()->harga,
                $x['sampul']=$query->row()->foto,
                $x['deskripsi']=$query->row()->deskripsi,
                $x['foto']=array(),
            ];
            $query = $this->db->get_where('detail_barang',['id_barang'=>$id])->result();
            foreach($query as $row){
                $y['foto']=$row->foto;
                array_push($x['foto'], $y);
            }
            array_push($result, $x);
            echo json_encode([
                "status"=>200,
                "values"=>$result,
            ]);
         }else{
             echo json_encode(
                 [
                     "error"=>true,
                     "message"=>"Data tidak ditemukan"
                 ]
             );
         }
    }

    public function register(){
        if(!empty($_POST['username']) AND !empty($_POST['password']) AND !empty($_POST['nama']) AND !empty($_POST['no_hp'])){
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $nama = $this->input->post('nama');
            $noHp = $this->input->post('no_hp');
            $foto = $this->input->post('foto');
            $status = 1;
            $role = 1;

            $query = $this->db->get_where('user',['username'=>$username,'nama'=>$nama,'no_hp'=>$noHp]);
            if($query->num_rows() >=1){
                $result= [
                    "error"=>true,
                    "message"=>"User sudah pernah didaftarkan"
                ];
                echo json_encode($result);
            } else{   
                $save= $this->db->insert('user',array(
                    "username"=>$username,
                    "password"=>$password,
                    "nama"=>$nama,
                    "no_hp"=>$noHp,
                    "foto"=>$foto,
                    "status"=>$status,
                    "role"=>$role
                ));
    
                if($save){
                    $result= [
                        "status"=>200,
                        "message"=>"Berhasil melakukan registrasi, silahkan login ke akun anda"
                    ];
                    echo json_encode($result);
                }else{
                    $result= [
                        "error"=>true,
                        "message"=>"Gagal melakukan registrasi"
                    ];
                    echo json_encode($result);
                }
            }

            
        }else{
            $result= [
                "error"=>true,
                "message"=>"Gagal melakukan registrasi, mohon isi data dengan lengkap"
            ];
            echo json_encode($result);
        }
    }

    public function get_jenis(){
        
        $data = $this->db->get('jenis');
        $result = array();
		foreach($data->result() as $rows){
			array_push($result,array(
				'id'=>$rows->id,
				'kategori'=>$rows->jenis,
			));
		}
		echo json_encode(array(
			'status'=>200,
			'values'=>$result
		));

    }

    public function post_tambahJenis(){
        if($_POST){
            $jenis = $this->input->post('jenis');

            //cek apakah data sudah ada didatabase ?
            $query = $this->db->get_where('jenis',['jenis'=>$jenis]);
            if($query->num_rows() > 0){
                $result= [
                    "error"=>true,
                    "message"=>"Data sudah ada !"
                ];
                echo json_encode($result);
            }else{

                $save = $this->db->insert('jenis',array(
                    "jenis"=>$jenis
                )); 
            
                if($save){
                    $result= [
                        "status"=>200,
                        "message"=>"Berhasil menambahkan data jenis"
                    ];
                    echo json_encode($result);
                } else {
                    $result= [
                        "error"=>true,
                        "message"=>"Gagal menyimpan data"
                    ];
                } 

            }
        }
    }


    public function put_updateJenis(){
        if(!empty($_POST['id_jenis'])){
            $id_jenis = $this->input->post('id_jenis');
            $jenis    = $this->input->post('jenis');
            $data     = array();
            $data = array(
                "jenis"=>$jenis
            );

            $cek = $this->db->get_where('jenis',['id'=>$id_jenis]);
            if($cek->num_rows() <= 0 ){
                echo json_encode(array(
                    "error"=>true,
                    "message"=>"Terjadi kesalahan, data tidak ditemukan"
                ));
            }else{  
                $this->db->where('id',$id_jenis);
                $update = $this->db->update('jenis',$data);
    
                if($update){
                    echo json_encode(array(
                        "status"=>200,
                        "message"=>"Data berhasil diupdate"
                    ));    
                }else{
                    echo json_encode(array(
                        "error"=>true,
                        "message"=>"Terjadi kesalahan, data gagal diupdate"
                    ));
                }
            }     
        }else{
            echo json_encode(array(
                "error"=>true,
                "message"=>"Terjadi kesalahan, data gagal diupdate"
            ));
        }
    }

    public function delete_hapusJenis(){
        if(!empty($_POST['id_jenis'])){
            $id_jenis = $this->input->post('id_jenis');
            
            $cek = $this->db->get_where('jenis',array("id"=>$id_jenis));
            
            if($cek->num_rows() > 0){
                
                $this->db->where('id',$id_jenis);
                $delete = $this->db->delete('jenis');

            if($delete){
                echo json_encode(array(
                    "status"=>200,
                    "message"=>"Data berhasil dihapus"
                ));
                }else{
                echo json_encode(array(
                    "error"=>true,
                    "message"=>"Terjadi kesalahan, data gagal dihapus"
                ));
                }
            }else{
                echo json_encode(array(
                    "error"=>true,
                    "message"=>"Terjadi kesalahan, data tidak ditemukan"
                )); 
            }
        }else{
            echo json_encode(array(
                "error"=>true,
                "message"=>"Terjadi kesalahan, data tidak ditemukan"
            )); 
        }
    }

    public function get_kategori(){
        
        $data = $this->db->get('kategori');
        $result = array();
		foreach($data->result() as $rows){
			array_push($result,array(
				'id'=>$rows->id,
				'kategori'=>$rows->kategori,
			));
		}
		echo json_encode(array(
			'status'=>200,
			'values'=>$result
		));

    }

    public function post_tambahKategori(){
        if($_POST){
            $kategori = $this->input->post("kategori");

            $data = array(
                "kategori"=>$kategori
            );

            $insert = $this->db->insert("kategori",$data);
            
            if($insert){
                echo json_encode(array(
                    "status"=>200,
                    "message"=>"Data berhasil ditambahkan"
                ));
            }else{
                echo json_encode(array(
                    "error"=>true,
                    "message"=>"Gagal menambahkan data"
                )); 
            }
        }
    }

    public function put_updateKategori(){
        if($_POST){
            $id       = $this->input->post("id_kategori");
            $kategori = $this->input->post("kategori");

            $data = array(
                "kategori"=>$kategori,
            );
            
            $cek = $this->db->get_where('kategori',['id'=>$id]);
            
            if($cek->num_rows() > 0){
                
                $this->db->where("id",$id);
                $update = $this->db->update('kategori',$data);

                if($update){
                    echo json_encode(array(
                        "status"=>200,
                        "message"=>"Data berhasil diupdate"
                    ));
                }else{
                    echo json_encode(array(
                        "error"=>true,
                        "message"=>"Gagal mengupdate data"
                    ));
                }
            }else{
                echo json_encode(array(
                    "error"=>true,
                    "message"=>"Data tidak ditemukan, gagal mengupdate data"
                ));
            }
        }
    }


    public function upload(){

        $folderPath = "./upload/";

        $file_tmp1 = $_FILES['file1']['tmp_name'];
        $file_ext1 = strtolower(end(explode('.',$_FILES['file1']['name'])));
        $file1 = $folderPath . uniqid() . '.'.$file_ext1;
        $a =  uniqid() . '.'.$file_ext1;
        move_uploaded_file($file_tmp1, $file1);

        $file_tmp2 = $_FILES['file2']['tmp_name'];
        $file_ext2 = strtolower(end(explode('.',$_FILES['file2']['name'])));
        $file2 = $folderPath . uniqid() . '.'.$file_ext2;
        $b = uniqid() . '.'.$file_ext2;
    
        move_uploaded_file($file_tmp2, $file2);

        $data = array(
            "foto1"=> $a,
            "foto2"=> $b,
        );
        $this->db->insert('upload',$data);
        echo json_encode(array(
            "values"=>$data
        ));
    
    }
    
    
    public function customer(){
        $this->load->view("upload_customer");
    }
    
    

    public function tes_insert()
    { 
        $data = json_decode(file_get_contents("php://input"),true);

        echo json_encode(print_r($data[0]['foto']));
       
        move_uploaded_file($_FILES["foto"]["tmp_name"], "./upload/" . $_FILES["foto"]["name"]);
      
        // for($i=0; $i < count($data); $i++)
        //     {
        //         $result = array(
        //             "nama"=>$data[$i]['nama'],
        //             "alamat"=>$data[$i]['alamat'],
        //             "noHp"=>$data[$i]['noHp'],
        //         );

        //         $save = $this->db->insert('insert_data',$result);
        //     }
        //     if($save){
        //         echo json_encode(array(
        //             "status"=>200,
        //             "message"=>"Data berhasil ditambahkan"
        //         ));
        //     }else {
        //         echo json_encode(array(
        //             "error"=>true,
        //             "message"=>"Terjadi kesalahan, gagal menambahkan data !"
        //         ));
        //     }
    }

   public function grafik_monitoring_konsistensi_h1(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $result=array();
      $start=$this->input->post("started") == null ? date('Y-m-01') : $this->input->post("started");
      $end = $this->input->post("ended")== null ? date('Y-m-d') : $this->input->post("ended");
	
      
        $unitIn = $this->db->query("
			select count(kode_dealer_ahm) as total
			from (		
				select count(tgl) as total, kode_dealer_ahm from(
					select date(a.created_at) as tgl , kode_dealer_ahm , count(a.id_penerimaan_unit_dealer)
					from tr_penerimaan_unit_dealer a 
					join tr_do_po b on a.no_do =b.no_do
					join ms_dealer c on a.id_dealer = c.id_dealer
					where date(a.created_at) BETWEEN '$start' and '$end' and c.active = 1 and a.id_goods_receipt is not null and id_goods_receipt !='' and a.status = 'close' 
					group by kode_dealer_ahm, date(a.created_at)
				)x
			group by kode_dealer_ahm
			having total >= 10
			) y
		");

		$prospecting = $this->db->query("			
			select count(kode_dealer_ahm) as total
			from (
				select count(tgl) as total, kode_dealer_ahm from(
					select date(b.created_at) as tgl , kode_dealer_ahm , count(b.id_prospek)
					from tr_prospek b join ms_dealer a on a.id_dealer = b.id_dealer where date(b.created_at) 
					BETWEEN '$start' and '$end' and a.active = 1 
					group by kode_dealer_ahm, date(b.created_at)
				)x
			group by kode_dealer_ahm
			having total >= 22
			) y
		");

		$prospecting_apps = $this->db->query("
			select count(kode_dealer_ahm) as total
			from (
				select count(tgl) as total, kode_dealer_ahm from(
					select date(b.created_at) as tgl , kode_dealer_ahm , count(b.id_prospek)
					from tr_prospek b join ms_dealer a on a.id_dealer = b.id_dealer where date(b.created_at) 
					BETWEEN '$start' and '$end' and a.active = 1 and b.input_from ='sc' and a.active = 1
					group by kode_dealer_ahm, date(b.created_at)
				)x
			group by kode_dealer_ahm
			having total >= 22
			) y
		");

        $dealing = $this->db->query("           
        		select count(kode_dealer_ahm) as total
			from (		
				select count(tgl) as total, kode_dealer_ahm from(		
					select date(b.created_at) as tgl , kode_dealer_ahm , count(b.no_spk)
					from ms_dealer a join tr_spk b on a.id_dealer =b.id_dealer 
        	 		where date(b.created_at)  BETWEEN '$start' and '$end' and b.no_spk is not null 
         			and b.status_spk !=''
         			and b.status_spk != 'canceled' and b.status_spk !='rejected' and a.active = 1
					group by kode_dealer_ahm, date(b.created_at)
				)x
			group by kode_dealer_ahm
			having total >= 22
			) y 
	");
         
         $billing = $this->db->query("
			select count(kode_dealer_ahm) as total
			from (		
				select count(tgl) as total, kode_dealer_ahm from(		
					select date(b.created_at) as tgl , kode_dealer_ahm , count(b.id_sales_order)
					from ms_dealer a join tr_sales_order b on a.id_dealer =b.id_dealer 
					where date(b.created_at) BETWEEN '$start' and '$end'
					and b.no_invoice is not null
					and b.no_invoice != '' and a.active = 1
					group by kode_dealer_ahm, date(b.created_at)
				)x
			group by kode_dealer_ahm
			having total >= 22
			) y
	");
				
		$handleLeasing = $this->db->query(" 
			select count(kode_dealer_ahm) as total
			from (		
				select count(tgl) as total, kode_dealer_ahm from(		
					select date(a.created_at) as tgl , kode_dealer_ahm , count(c.no_spk)
					from
						tr_order_survey a
					join ms_dealer b on
						a.id_dealer = b.id_dealer
					join tr_spk c on
						a.no_spk = c.no_spk
					where
						date(a.created_at) BETWEEN '$start' and '$end'
						and a.no_order_survey is not null
						and a.no_order_survey != '' and b.active = 1
						and c.status_survey = 'approved' and c.status_spk = 'close'
					group by kode_dealer_ahm, date(a.created_at)
				)x
			group by kode_dealer_ahm
			having total >= 22
			) y
		");

		$delivery = $this->db->query("	
			select count(kode_dealer_ahm) as total
			from (		
				select count(tgl) as total, kode_dealer_ahm from(		
					select date(a.created_at) as tgl , kode_dealer_ahm , count(a.delivery_document_id)
					from tr_sales_order a
					join ms_dealer b on a.id_dealer = b.id_dealer
					where date(a.created_at) BETWEEN '$start' and '$end' and a.delivery_document_id is not null and a.delivery_document_id !='' and b.active = 1
					group by kode_dealer_ahm, date(a.created_at)
				)x
			group by kode_dealer_ahm
			having total >= 22
			) y
		");
				
		$documentHandling = $this->db->query("		
			select count(kode_dealer_ahm) as total
			from (		
				select count(tgl) as total, kode_dealer_ahm from(
					select date(d.tgl_terima_stnk) as tgl , kode_dealer_ahm , count(b.no_mesin)
					from
						tr_faktur_stnk_detail c
					left join tr_tandaterima_stnk_konsumen_detail b on
						c.no_mesin = b.no_mesin
					left join tr_tandaterima_stnk_konsumen d on
						b.kd_stnk_konsumen = d.kd_stnk_konsumen
					join ms_dealer a on
						a.id_dealer = d.id_dealer
					where
						date(d.tgl_terima_stnk) BETWEEN '$start' and '$end' and d.jenis_cetak = 'stnk'
						and c.id_sales_order is not null and a.active = 1
						and c.id_sales_order != '' and d.tgl_terima_stnk is not NULL and d.tgl_terima_stnk !=''
					group by kode_dealer_ahm, date(d.tgl_terima_stnk)
				)x
			group by kode_dealer_ahm
			having total >= 15
			) y		
		");
			
			$sub_array=array();	
			$sub_array['unit_inbound_label']="Unit Inbound";
			$sub_array['unit_inbound']=round(($unitIn->row()->total / 48) * 100);
			$sub_array['prospecting_label']="Prospecting";
			$sub_array['prospecting']=round(($prospecting->row()->total / 48) * 100);
			$sub_array['prospecting_apps_label']="Prospecting Apps";
			$sub_array['prospecting_apps']=round(($prospecting_apps->row()->total / 48) * 100);
			$sub_array['dealing_label']="Dealing Process";
			$sub_array['dealing']=round(($dealing->row()->total / 48) * 100);
			$sub_array['billing_label']="Billing Process";
			$sub_array['billing']=round(($billing->row()->total / 48) * 100);
			$sub_array['handle_label']="Handle Leasing";
			$sub_array['handle_leasing']=round(($handleLeasing->row()->total / 48) * 100);
			$sub_array['delivery_label']="Delivery Process";
			$sub_array['delivery_process']=round(($delivery->row()->total / 48) * 100);
			$sub_array['document_label']="Document Handling";
			$sub_array['document_handling']=round(($documentHandling->row()->total / 48) * 100);
			$result[]= $sub_array;
			 
        
         $dataPoints=array(
            ["label"=> $sub_array['unit_inbound_label'],"y"=> (int)$sub_array['unit_inbound'],"exploded"=>true],
            ["label"=> $sub_array['prospecting_label'],"y"=> (int)$sub_array['prospecting'],"exploded"=>true],
            ["label"=> $sub_array['prospecting_apps_label'],"y"=> (int)$sub_array['prospecting_apps'],"exploded"=>true],
            ["label"=> $sub_array['dealing_label'],"y"=> (int)$sub_array['dealing'],"exploded"=>true],
            ["label"=> $sub_array['billing_label'],"y"=> (int)$sub_array['billing'],"exploded"=>true],
            ["label"=> $sub_array['handle_label'],"y"=> (int)$sub_array['handle_leasing'],"exploded"=>true],
            ["label"=> $sub_array['delivery_label'],"y"=> (int)$sub_array['delivery_process'],"exploded"=>true],
            ["label"=> $sub_array['document_label'],"y"=> (int)$sub_array['document_handling'],"exploded"=>true],
        );
        
	   echo json_encode(array(
	       "dataPoints"=>$dataPoints,
	       ));
		
    }

     public function grafik_konsistensi_h23(){
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $start_date=$this->input->post("started") == null ? "$tahun-$month-01" : $this->input->post("started");
      $end = $this->input->post("ended")== null ? date('Y-m-d') : $this->input->post("ended");
      $list_dealer = "'1','2','4','6','8','13','18','19','22','23','25','29','30','38','39','40','41','43','44','46','47','51','54','58','64','65','66','70','71','74','76','77','78','80','81','82','83','84','85','86','88','90','91','94','96','97','98','101','102','103','104','105','106','107', '5','10','28','56','69','715'";

      $result=array();
      $WOSC = $this->db->query("
		select count(id_dealer) as total
		from (
			select count(tgl) as total, id_dealer from(
				select date(created_at) as tgl , id_dealer 
				from tr_h2_wo_dealer where status ='closed' and input_from ='sc' 
				and left(created_at,10) between '$start_date' and '$end' and id_dealer in ($list_dealer)		
				group by id_dealer, date(created_at)
			)x
		group by id_dealer
		having total >= 22
		) y
	");

      $WO = $this->db->query("
		select count(id_dealer) as total
		from (
			select count(tgl) as total, id_dealer from(
				select date(created_at) as tgl , id_dealer 
				from tr_h2_wo_dealer where status ='closed' 
				and left(created_at,10) between '$start_date' and '$end' and id_dealer in ($list_dealer)		
				group by id_dealer, date(created_at)
			)x
		group by id_dealer
		having total >= 22
		) y
	");
      
	$Bil = $this->db->query("
		select count(id_dealer) as total
		from (
			select count(tgl) as total, id_dealer from(
				select date(created_at) as tgl , id_dealer 
				from tr_h2_wo_dealer where status ='closed' and no_njb is not NULL and left(created_at,10) between '$start_date' and '$end' and id_dealer in ($list_dealer)		
				group by id_dealer, date(created_at)
			)x
		group by id_dealer
		having total >= 22
		) y
	");
      
	$Part = $this->db->query("
		select count(id_dealer) as total
		from (
			select count(tgl) as total, id_dealer from(
				select date(created_at) as tgl , id_dealer 
				from tr_h3_dealer_sales_order where status ='Closed' and left(created_at,10) between '$start_date' and '$end' and id_dealer in ($list_dealer)		
				group by id_dealer, date(created_at)
			)x
		group by id_dealer
		having total >= 22
		) y
	");
      	
	$Inbound = $this->db->query("
		select count(id_dealer) as total
		from (
			select count(tgl) as total, id_dealer from(
				select date(created_at) as tgl , id_dealer 
				from tr_h3_dealer_good_receipt where left(created_at,10) between '$start_date' and '$end' and id_dealer in ($list_dealer)		
				group by id_dealer, date(created_at)
			)x
		group by id_dealer
		having total >= 5
		) y
	");
       
        $total_dealer= 61;            

        $sub_array=array();
        $sub_array['total_wos_sc'] =round(($WOSC->row()->total / $total_dealer) * 100);
        $sub_array['total_wos'] =round(($WO->row()->total / $total_dealer) * 100);
        $sub_array['total_billings'] =round(($Bil->row()->total / $total_dealer) *100);
        $sub_array['total_pss'] =round(($Part->row()->total / $total_dealer)*100);
        $sub_array['total_inbounds'] =round(($Inbound->row()->total/$total_dealer)*100);

        $sub_array['w1'] = $WO->row()->total;
        $sub_array['wsc1'] = $WOSC->row()->total;
        $sub_array['b1']=$Bil->row()->total;
        $sub_array['ps1']=$Part->row()->total;
        $sub_array['pi1'] =$Inbound->row()->total;

        $result[]= $sub_array;
        
        echo json_encode(array(
            "status"=>200,
            "values"=>$result
            ));
    }

}

/* End of file Api.php */


?>