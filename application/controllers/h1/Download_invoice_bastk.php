<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Download_invoice_bastk extends CI_Controller {

	

	var $folder =   "h1/laporan";

	var $page		="download_invoice_bastk";	

	var $isi		="laporan_1";	

	var $title  =   "Download Invoice & BASTK Dealer";



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

		$auth = $this->m_admin->user_auth($this->page,"select");		

		$sess = $this->m_admin->sess_auth();						

		if($name=="" OR $auth=='false')

		{

			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";

		}elseif($sess=='false'){

			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";

		}





	}

	protected function template($data)

	{

		$name = $this->session->userdata('nama');

		if($name=="")

		{

			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";

		}else{

			$data['id_menu'] = $this->m_admin->getMenu($this->page);

			$data['group'] 	= $this->session->userdata("group");

			$this->load->view('template/header',$data);

			$this->load->view('template/aside');			

			$this->load->view($this->folder."/".$this->page);		

			$this->load->view('template/footer');

		}

	}



	public function index()

	{						

		$data['isi']    = $this->isi;		

		$data['title']	= $this->title;															

		$data['set']		= "view";

		$this->template($data);		    	    

	}
	
	public function fetch(){
	       $starts       = $this->input->post("start");
           $length       = $this->input->post("length");
           $LIMIT        = "LIMIT  $starts, $length ";
           $draw         = $this->input->post("draw");
           $search       = $this->input->post('search')['value'];
           $jenis_penjualan =$this->input->post('filter');
           $no_mesin = $this->input->post('no_mesin');
           $where="WHERE 1=1";
           $where_gc="WHERE 1=1";
          if($jenis_penjualan == "GC"){
                $where_gc .="  AND d.no_mesin LIKE '%$no_mesin%' GROUP BY b.no_spk_gc";
                $where .=" AND a.no_mesin LIKE '%$no_mesin%'";
           }elseif($jenis_penjualan == "REG" ){
                $where_gc .=" AND d.no_mesin LIKE '%$no_mesin%' GROUP BY b.no_spk_gc";
                $where .=" AND a.no_mesin LIKE '%$no_mesin%'";
           }
           
          
           $result=array();
            if($jenis_penjualan=="GC"){
                $query =$this->db->query("select b.id_sales_order_gc as sales_order,b.no_spk_gc as spk,GROUP_CONCAT(d.no_mesin SEPARATOR ';') as nomesin,c.id_dealer,c.kode_dealer_md,c.nama_dealer,CASE WHEN left(b.id_sales_order_gc,2)='GC' then 'GC' else 'REG' end as jenis 
				from tr_sales_order_gc b join ms_dealer c on b.id_dealer =c.id_dealer left join tr_sales_order_gc_nosin d on b.no_spk_gc=d.no_spk_gc $where_gc $LIMIT");
				$query2 =$this->db->query("select b.id_sales_order_gc as sales_order,b.no_spk_gc as spk,GROUP_CONCAT(d.no_mesin SEPARATOR ';') as nomesin,c.id_dealer,c.kode_dealer_md,c.nama_dealer,CASE WHEN left(b.id_sales_order_gc,2)='GC' then 'GC' else 'REG' end as jenis 
				from tr_sales_order_gc b join ms_dealer c on b.id_dealer =c.id_dealer left join tr_sales_order_gc_nosin d on b.no_spk_gc=d.no_spk_gc GROUP BY b.no_spk_gc");
            }else{
                $query =$this->db->query("select a.id_sales_order as sales_order,a.no_spk as spk,a.no_mesin as nomesin,c.id_dealer,c.kode_dealer_md,c.nama_dealer,CASE WHEN left(a.id_sales_order,2)='GC' then 'GC' else 'REG' end as jenis 
				from tr_sales_order a join ms_dealer c on a.id_dealer =c.id_dealer $where $LIMIT");
				 $query2 =$this->db->query("select a.id_sales_order as sales_order,a.no_spk as spk,a.no_mesin as nomesin,c.id_dealer,c.kode_dealer_md,c.nama_dealer,CASE WHEN left(a.id_sales_order,2)='GC' then 'GC' else 'REG' end as jenis 
				from tr_sales_order a join ms_dealer c on a.id_dealer =c.id_dealer");
            }
            $data=array();
            $button_invoice ="";
            $button_bastk   ="";
            $button_spk   ="";
            $index =1;
            foreach($query->result() as $rows){
                if($rows->jenis =="GC"){
                 $button_invoice= "<a href=".base_url('dealer/sales_order/cetak_invoice_gc?id='.$rows->sales_order)." class='btn btn-sm btn-primary' target='_blank'><i class='fa fa-print'></i> Invoice</a>";
                 $button_bastk   ="<a href=".base_url('dealer/sppu/cetak_sppu_gc?id='.$rows->sales_order)." class='btn btn-sm btn-danger' target='_blank'><i class='fa fa-print'></i> BASTK</a>";
                }else{
                     $button_invoice= "<a href=".base_url('dealer/sales_order/cetak_invoice2?id='.$rows->sales_order.'&dealer='.$rows->id_dealer)." class='btn btn-sm btn-primary' target='_blank'><i class='fa fa-eye'></i> Invoice</a>";
                     $button_bastk   ="<a href=".base_url('dealer/sppu/cetak_sppu?id='.$rows->sales_order)." class='btn btn-sm btn-danger' target='_blank'><i class='fa fa-print'></i> BASTK</a>";
                     $button_spk   ="<a href=".base_url('dealer/spk/cetak?id='.$rows->spk)." class='btn btn-sm btn-success' target='_blank'><i class='fa fa-print'></i> SPK</a>";
                }
                
                $sub_array=array();
                $sub_array[]=$index++;
                $sub_array[]=$rows->kode_dealer_md;
                $sub_array[]=$rows->nama_dealer;
                $sub_array[]=$rows->sales_order;
                $sub_array[]=$rows->spk;
                $sub_array[]=$rows->nomesin;
                $sub_array[]=$button_invoice." ". $button_bastk." ".$button_spk;
                $result[]=$sub_array;
            }
            
             $output = array(
                 "draw"            =>     intval($this->input->post("draw")),
                 "recordsFiltered" =>     $query2->num_rows(),
                 "data"            =>     $result,
     
            );
                echo json_encode($output);
	    }


}