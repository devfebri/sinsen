<?php

class Tracking_transaksi_part extends CI_Controller{
    var $tables = "tr_entry_po_leasing";
	var $folder = "dealer";
	var $page   = "tracking_transaksi_part";
	var $title  = "History Transaksi Parts";
	
	  public function __construct()
  {
    parent::__construct();
    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    }

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
  
  }
	
   	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		} else {
			$this->load->view('template/header', $data);
			$this->load->view('template/aside');
			$this->load->view($this->folder . "/" . $this->page);
			$this->load->view('template/footer');
		}
	}

	public function index()
	{
		$data['isi']    = "History Transaksi Parts";
		$data['title']	= $this->title;
		$data['set']	= "index";
		$this->template($data);
	}
	
	public function fetch_data(){
	  date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d');
      $tahun = date('Y');
      $month = date("m",strtotime($date));
      $start_date=$this->input->post("started") == null ? "$tahun-$month-01" : $this->input->post("started");
      $end = $this->input->post("ended")== null ? date('Y-m-d') : $this->input->post("ended");
      $id_dealer     = $this->m_admin->cari_dealer();
      
      $start        = $this->input->post('start');
      $length       = $this->input->post('length');
      $limit        = "LIMIT $start, $length ";
      $draw         = $this->input->post("draw");
      $search       = $this->input->post("search")["value"];
      $orders       = isset($_POST["order"]) ? $_POST["order"] : '';
      $id_part      = $this->input->post('kode_part');
      
      $where = " WHERE 1=1 AND LEFT(a.created_at,10) BETWEEN '$start_date' AND '$end' AND a.id_dealer='$id_dealer' ";
      $where2 = " WHERE 1=1 AND LEFT(a.created_at,10) BETWEEN '$start_date' AND '$end' AND a.id_dealer='$id_dealer' ";
      
          if($id_part !=""){
              $where .=" AND (a.id_part LIKE'%$id_part%' )";
              $where2 .=" AND (a.id_part LIKE'%$id_part%' )";
          }
      
        if (isset($search)) {
          if ($search != '') {
                $where .= " AND (a.id_part LIKE '%$search%' OR a.referensi LIKE '%$search%') ";
                $where2 .= " AND (a.id_part LIKE '%$search%' OR a.referensi LIKE '%$search%') ";
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
              $where .= " ORDER BY a.id ASC ";
              $where2 .= " ORDER BY a.id ASC ";
            }
          } else {
            $where .= " ORDER BY a.id ASC ";
            $where2 .= " ORDER BY a.id ASC ";
          }
          if (isset($limit)) {
            if ($limit != '') {
              $where .= ' ' . $limit;
            }
          }

          // $tanggal = date("Y-m-d");
          // if($tanggal <='2023-08-06' || $tanggal >='2023-08-12'){
          // // if(1){
          //   $no_fed = '';
          // }else{
          //   $no_fed = "join ms_part b on a.id_part = b.id_part and b.kelompok_part !='FED OIL'";
          // }
          
          $no_fed = '';
          if($this->config->item('ahm_d_only')){
            $no_fed = "join ms_part b on a.id_part = b.id_part and b.kelompok_part !='FED OIL'";
          }

          $query = $this->db->query("SELECT a.id_part,a.id_gudang,a.id_rak,a.tipe_transaksi,a.sumber_transaksi,a.referensi,a.stok_awal,a.stok_value,a.stok_akhir,a.created_at 
          from ms_h3_dealer_transaksi_stok a $no_fed $where");
          // $query2 = $this->db->query("SELECT a.id_part,a.id_gudang,a.id_rak,a.tipe_transaksi,a.sumber_transaksi,a.referensi,a.stok_awal,a.stok_value,a.stok_akhir,a.created_at
          // from ms_h3_dealer_transaksi_stok a $where2")->num_rows();
	      $index=1;
	      $result=array();
	      foreach($query->result() as $rows){
	           $sub_array=array();
	           $sub_array[] = $index++;
	           $sub_array[] = $rows->id_part;
	           $sub_array[] = $rows->id_gudang;
	           $sub_array[] = $rows->id_rak;
	           $sub_array[] = $rows->tipe_transaksi == "+" ? "Penerimaan"  : "Penjualan";
	           $sub_array[] = formatTanggal(substr($rows->created_at,0,10))." ".substr($rows->created_at,11,8);
	           $sub_array[] = $rows->sumber_transaksi;
	           $sub_array[] = $rows->referensi;
	           $sub_array[] = $rows->stok_awal;
	           $sub_array[] = $rows->stok_value;
	           $sub_array[] = $rows->stok_akhir;
	           $result[]=$sub_array;
	      }
	      $output = array(
            "draw"            =>  intval($draw),
            "recordsFiltered" =>  $query->num_rows(),
            "data"          =>  $result,
     
            );
        echo json_encode($output);
	}
}

?>