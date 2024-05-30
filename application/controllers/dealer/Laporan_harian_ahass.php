<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_harian_ahass extends CI_Controller
{

  var $folder =   "dealer/laporan";
  var $page    =    "laporan_harian_ahass";
  var $title  =   "Laporan Performance AHASS";

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
      $data['title'] = "PERFORMANCE AHASS";
      $data['params'] = $params;
     
      $filter = [
        'start_date' => $params->start_date,
        'end_date' => $params->end_date,
        'id_dealer'=>$this->db->query("select a.id_dealer,b.id_karyawan_dealer,c.id_user from ms_dealer a join ms_karyawan_dealer b on a.id_dealer=b.id_dealer join ms_user c on c.id_karyawan_dealer=b.id_karyawan_dealer where c.id_user='{$_SESSION['id_user']}'")->row()->id_dealer,
      ];
        $data['details'] = $this->db->query("select c.id_tipe_kendaraan as tipe_kendaraan,f.deskripsi, 
					SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='ASS1' then 1 ELSE 0 end )as total_ass1, 
					SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='ASS2' then 1 ELSE 0 end )as total_ass2,
					SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='ASS3' then 1 ELSE 0 end )as total_ass3,
					SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='ASS4' then 1 ELSE 0 end )as total_ass4,
					SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='CS' then 1 ELSE 0 end )as total_cs,
					SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='LS' then 1 ELSE 0 end )as total_ls,
					SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='OR+' then 1 ELSE 0 end )as total_or,
					SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='LR' then 1 ELSE 0 end )as total_lr,
					SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='HR' and d.pekerjaan_luar =0 then 1 ELSE 0 end )as total_hr,
					SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='JR' then 1 ELSE 0 end )as total_jr,
					SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type in('C2','C1','PUD') then 1 ELSE 0 end )as total_claim,
					SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type in('OTHER') then 1 ELSE 0 end )as total_other,
					SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and d.pekerjaan_luar =0 THEN 1 else 0 end) as total_job,
					SUM(CASE WHEN a.status='Closed' and d.pekerjaan_luar =0 then 1 else 0 end) as total_entri
				from tr_h2_wo_dealer a left join tr_h2_sa_form b on a.id_sa_form=b.id_sa_form
				left join ms_customer_h23 c on b.id_customer=c.id_customer join tr_h2_wo_dealer_pekerjaan d 
				on a.id_work_order=d.id_work_order left join ms_h2_jasa e on e.id_jasa=d.id_jasa left join ms_ptm f on c.id_tipe_kendaraan=f.tipe_marketing where a.id_dealer='{$filter['id_dealer']}' 
				and a.status='Closed' and left(a.created_at,10) between '{$filter['start_date']}' and '{$filter['end_date']}' GROUP BY c.id_tipe_kendaraan")->result();
         $data['jasa']= $this->db->query("SELECT 
					SUM(CASE WHEN A.id_type in ('HR','LR','OR+','CS','JR','LS' ) THEN B.harga ELSE 0 END) AS ongkos_kerja, 
					SUM(CASE WHEN A.id_type in ('ASS1','ASS2','ASS3','ASS4') THEN B.harga ELSE 0 END) AS ass, 
					SUM(CASE WHEN A.id_type in ('C2','C1','PUD') THEN B.harga ELSE 0 END) AS claim,
					SUM(CASE WHEN A.id_type in ('OTHER') THEN B.harga ELSE 0 END) AS other
				FROM tr_h2_wo_dealer_pekerjaan B 
				JOIN ms_h2_jasa A ON A.id_jasa=B.id_jasa JOIN tr_h2_wo_dealer C 
				ON B.id_work_order=C.id_work_order WHERE C.id_dealer='{$filter['id_dealer']}' AND C.status='Closed' AND B.pekerjaan_batal='0'
				AND LEFT(C.created_at,10) BETWEEN '{$filter['start_date']}' AND '{$filter['end_date']}'")->row();
	    $data['part']=$this->db->query("select 
					SUM(CASE WHEN A.kelompok_part in('AH','AHM','BB','BBIST','CABLE','CB','CCKIT','CDKGP',
					'CH','CHAIN','COMP','COOL','CRKIT','DISK','EC','ELECT','EP','EPMTI','ET','GS','GSA','GSB','GST','HM','HNMTI',
					'IMPOR','INS','ISTC','LGS','LSIST','MF','MTI','MUF','N','NF','OAHM1','OAHM2','OC','OFCC','OINS','OKGD',
					'OMTI','ORPL','OSEAL','OTHER','PLAST','PSKIT','PSTN','RBR','RIMWH','RPIST','RSKIT','SD','SDN','SDN2','SDT','SE',
					'SPGUI','SPOKE','STR','TB','TBHGP','TDI','VALVE','VV','BM1','BR','BRNG','BRNG2','BRNG3','BS','CD','CDKIT','DIHVL',
					'EPHVL','HAH','HPLAS','HRW','HSD','OISTC','OTHR','PAINT','PT','RPHVL','RW','RW2','RW3','RWHVL','SA','SAOIL','SHOCK',
					'TAS','TB1','TBVL') then B.harga_beli*B.qty ELSE 0 END) AS spart,
					SUM(CASE WHEN A.kelompok_part in('GMO','ACB','ACG','FLUID','OIL') then B.harga_beli*B.qty ELSE 0 END) AS oil,
					SUM(CASE WHEN A.kelompok_part in('SPLUR','SP','SPLUG','SP2') then B.harga_beli*B.qty ELSE 0 END) AS busi,
					SUM(CASE WHEN A.kelompok_part in('TIRE','TR','TIRE1') then B.harga_beli*B.qty ELSE 0 END) AS tire,
					SUM(CASE WHEN A.kelompok_part in('BLDRV') then B.harga_beli*B.qty ELSE 0 END) AS belt,
					SUM(CASE WHEN A.kelompok_part in('BATT','BT') then B.harga_beli*B.qty ELSE 0 END) AS battery,
					SUM(CASE WHEN A.kelompok_part in('BRAKE','PS') then B.harga_beli*B.qty ELSE 0 END) AS brake,
					SUM(CASE WHEN A.kelompok_part in('ACCEC','FKT','TL','FED OIL','ACC','HELM','PA','PACC') then B.harga_beli*B.qty ELSE 0 END) AS other
				 from tr_h23_nsc_parts B JOIN ms_part A ON A.id_part=B.id_part join tr_h23_nsc C ON C.no_nsc=B.no_nsc where C.id_dealer='{$filter['id_dealer']}' and C.referensi='sales' 
				 and C.tgl_nsc BETWEEN '{$filter['start_date']}' and '{$filter['end_date']}'")->row();
	$data['part_wo']=$this->db->query("select 
					SUM(CASE WHEN A.kelompok_part in('AH','AHM','BB','BBIST','CABLE','CB','CCKIT','CDKGP',
					'CH','CHAIN','COMP','COOL','CRKIT','DISK','EC','ELECT','EP','EPMTI','ET','GS','GSA','GSB','GST','HM','HNMTI',
					'IMPOR','INS','ISTC','LGS','LSIST','MF','MTI','MUF','N','NF','OAHM1','OAHM2','OC','OFCC','OINS','OKGD',
					'OMTI','ORPL','OSEAL','OTHER','PLAST','PSKIT','PSTN','RBR','RIMWH','RPIST','RSKIT','SD','SDN','SDN2','SDT','SE',
					'SPGUI','SPOKE','STR','TB','TBHGP','TDI','VALVE','VV','BM1','BR','BRNG','BRNG2','BRNG3','BS','CD','CDKIT','DIHVL',
					'EPHVL','HAH','HPLAS','HRW','HSD','OISTC','OTHR','PAINT','PT','RPHVL','RW','RW2','RW3','RWHVL','SA','SAOIL','SHOCK',
					'TAS','TB1','TBVL') then B.harga_beli*B.qty ELSE 0 END) AS spart,
					SUM(CASE WHEN (A.kelompok_part in('GMO','ACB','ACG','FLUID','OIL') and sa.id_type not in ('ASS1','ASS2','ASS3','ASS4')) then B.harga_beli*B.qty ELSE 0 END) AS oil,
					SUM(CASE WHEN (A.kelompok_part in('GMO','ACB','ACG','FLUID','OIL') and sa.id_type in ('ASS1','ASS2','ASS3','ASS4')) then B.harga_beli*B.qty ELSE 0 END) AS oil_kpb,
					SUM(CASE WHEN A.kelompok_part in('SPLUR','SP','SPLUG','SP2') then B.harga_beli*B.qty ELSE 0 END) AS busi,
					SUM(CASE WHEN A.kelompok_part in('TIRE','TR','TIRE1') then B.harga_beli*B.qty ELSE 0 END) AS tire,
					SUM(CASE WHEN A.kelompok_part in('BLDRV') then B.harga_beli*B.qty ELSE 0 END) AS belt,
					SUM(CASE WHEN A.kelompok_part in('BATT','BT') then B.harga_beli*B.qty ELSE 0 END) AS battery,
					SUM(CASE WHEN A.kelompok_part in('BRAKE','PS') then B.harga_beli*B.qty ELSE 0 END) AS brake,
					SUM(CASE WHEN A.kelompok_part in('ACCEC','FKT','TL','FED OIL','ACC','HELM','PA','PACC') then B.harga_beli*B.qty ELSE 0 END) AS other
				 from tr_h23_nsc_parts B JOIN ms_part A ON A.id_part=B.id_part join tr_h23_nsc C ON C.no_nsc=B.no_nsc join tr_h2_wo_dealer wo on C.id_referensi=wo.id_work_order join tr_h2_sa_form sa on wo.id_sa_form=sa.id_sa_form where C.id_dealer='{$filter['id_dealer']}' and C.referensi='work_order' 
				 and C.tgl_nsc BETWEEN '{$filter['start_date']}' and '{$filter['end_date']}'")->row();

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
      $data['title']  = "PERFORMANCE AHASS";
      $data['set']    = "view";
      $this->template($data);
    }
  }
}
