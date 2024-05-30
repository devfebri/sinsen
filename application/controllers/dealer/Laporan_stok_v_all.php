<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_stok_v_all extends CI_Controller
{

  var $folder =   "dealer/laporan";
  var $page    =    "laporan_stok_v_all";
  var $title  =   "Laporan Stok Versi ALL";

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
      $data['title'] = "Laporan Stock Versi ALL";
      $data['params'] = $params;
      // send_json($data);
      $filter = [
        // 'start_date' => $params->start_date,
        // 'end_date' => $params->end_date,
        // 'id_dealer'=>$this->db->query("select a.id_dealer,b.id_karyawan_dealer,c.id_user from ms_dealer a join ms_karyawan_dealer b on a.id_dealer=b.id_dealer join ms_user c on c.id_karyawan_dealer=b.id_karyawan_dealer where c.id_user='{$_SESSION['id_user']}'")->row()->id_dealer,
        'id_dealer' => $this->m_admin->cari_dealer()
      ];               
      
      // $tanggal = date("Y-m-d");
      // // $tanggal = '2023-07-13';
      // if($tanggal <='2023-08-06' || $tanggal >='2023-08-11'){
      // // if(1){
      //   $where = '';
      // }else{
      //   $where = "and mp.kelompok_part !='FED OIL'";
      // }

      $where = '';
      if($this->config->item('ahm_d_only')){
        $where = "and mp.kelompok_part !='FED OIL'";
      }
      
            $data['details'] = $this->db->query("select a.id_part_int,md.id_dealer,a.id_part,mp.nama_part,mp.kelompok_vendor,a.stock,a.id_rak,a.id_gudang,mp.harga_md_dealer,(a.stock * harga_md_dealer) as jumlah_beli, 
				mp.harga_dealer_user,(a.stock * harga_dealer_user) as jumlah_jual,mp.kelompok_part,mp.rank,mp.status,md.diskon_reguler, md.tipe_diskon
				from ms_part mp 
        join ms_h3_dealer_stock a on a.id_part_int=mp.id_part_int
        join ms_dealer md on md.id_dealer = '{$filter['id_dealer']}'
         where a.id_dealer ='{$filter['id_dealer']}' and mp.kelompok_part !='TL' $where order by a.id_rak ASC")->result();
			
    //         $data['details'] = $this->db->query("select a.id_part,mp.nama_part,a.stock ,mp.harga_md_dealer,(a.stock * harga_md_dealer) as jumlah_beli, 
				// mp.harga_dealer_user,(a.stock * harga_dealer_user) as jumlah_jual,mp.kelompok_part,mp.rank,mp.status 
				// from ms_part mp join ms_h3_dealer_stock a on a.id_part=mp.id_part where a.stock > 0 and a.id_dealer ='{$filter['id_dealer']}'
    //     or a.id_part in(select d.id_part from tr_h3_dealer_sales_order_parts d join tr_h3_dealer_sales_order e on 
    //     d.nomor_so=e.nomor_so where e.id_dealer ='{$filter['id_dealer']}' and left(e.created_at,10) between '{$filter['start_date']}' and '{$filter['end_date']}') 
    //     or a.id_part 
    //     in(select f.id_part from tr_h3_dealer_good_receipt_parts f 
    //     join tr_h3_dealer_good_receipt g on g.id_good_receipt=f.id_good_receipt where g.id_dealer='{$filter['id_dealer']}' and left(g.created_at,10) between '{$filter['start_date']}' and '{$filter['end_date']}')
    //      or a.id_part in(select h.id_part from tr_h3_dealer_shipping_list_parts h 
    //      join tr_h3_dealer_shipping_list i on h.id_shipping_list=i.id_shipping_list where i.id_dealer='{$filter['id_dealer']}' and left(i.created_at,10) between '{$filter['start_date']}' and '{$filter['end_date']}')
    //     group by a.id_part")->result();
      
         
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
      $data['title']  = "Laporan Stock Versi ALL";
      $data['set']    = "view";
      $this->template($data);
    }
  }
}
