<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class List_part_booking extends CI_Controller {
    
	public $folder = "dealer";
	public $page   = "list_part_booking";
	public $title  = "List Part Booking";

	public function __construct(){		
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->db->reconnect();

		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
	
	}

	public function index(){				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "index";

		$this->template($data);	
	}
    
     public function fetch(){
        $starts       = $this->input->post("start");
        $length       = $this->input->post("length");
        $LIMIT        = "LIMIT $starts, $length ";
        $draw         = $this->input->post("draw");
        $search       = $this->input->post("search")["value"];
        $orders               = isset($_POST["order"]) ? $_POST["order"] : ''; 
        
        $where ="WHERE 1=1 AND a.id_dealer ='{$this->m_admin->cari_dealer()}' and a.status not in('closed','cancel') and wo_parts.pekerjaan_batal='0' GROUP by wo_parts.id_jasa ";
        $whereSO = "WHERE 1=1 AND so.status not in('Closed','Canceled') and (so.id_work_order is null or so.id_work_order ='') ";
        $where2 ="WHERE 1=1 AND a.id_dealer ='{$this->m_admin->cari_dealer()}' and a.status not in('closed','cancel') and wo_parts.pekerjaan_batal='0' GROUP by wo_parts.id_jasa ";
        $whereSO2 = "WHERE 1=1 AND so.status not in('Closed','Canceled') and (so.id_work_order is null or so.id_work_order ='') ";
        $searchingColumn="";
        $result=array();
        
        if (isset($search)) {
          if ($search != '') {
             $searchingColumn = $search;
                $where .= " AND (wo_parts.id_part LIKE '%$search%' ) ";
                $whereSO .= " AND (sop.id_part LIKE '%$search%' ) ";
                $where2 .= " AND (wo_parts.id_part LIKE '%$search%' ) ";
                $whereSO2 .= " AND (sop.id_part LIKE '%$search%' ) ";
              }
          }
    
        // if (isset($orders)) {
        //     if ($orders != '') {
        //       $order = $orders;
        //       $order_column = [];
        //       $order_clm  = $order_column[$order[0]['column']];
        //       $order_by   = $order[0]['dir'];
        //       $where .= " ORDER BY $order_clm $order_by ";
        //       $where2 .= " ORDER BY $order_clm $order_by ";
        //     } else {
        //       $where .= " ORDER BY a.created_at DESC ";
        //       $where2 .= " ORDER BY a.created_at DESC ";
        //       $whereSO .= " ORDER BY so.created_at DESC ";
        //       $whereSO2 .= " ORDER BY so.created_at DESC ";
        //     }
        //   } else {
        //     $where .= " ORDER BY a.created_at DESC ";
        //     $whereSO .= " ORDER BY so.created_at DESC ";
        //     $where2 .= " ORDER BY a.created_at DESC ";
        //     $whereSO2 .= " ORDER BY so.created_at DESC ";
        //   }
          if (isset($LIMIT)) {
            if ($LIMIT != '') {
              $where .= ' ' . $LIMIT;
            }
          }
        $index=1;
        $button="";
        $fetch = $this->db->query(" select a.id_work_order,left(a.created_at,10) as tanggal_dibuat,a.status,'Work Order' as referensi ,wo_parts.id_jasa,js.deskripsi,wo_parts.id_part,wo_parts.qty,wo_parts.nomor_so
         from tr_h2_wo_dealer a join tr_h2_wo_dealer_parts wo_parts on a.id_work_order=wo_parts.id_work_order
         join ms_h2_jasa js on wo_parts.id_jasa = js.id_jasa 
         $where
         	union ALL 
         select so.nomor_so,left(so.created_at,10) as tanggal_dibuat,so.status,'Direct Sales' as referensi,'-' as id_jasa,'-' as deskripsi ,sop.id_part,sop.kuantitas,sop.nomor_so from tr_h3_dealer_sales_order so 
         join tr_h3_dealer_sales_order_parts sop on so.nomor_so =sop.nomor_so order by tanggal_dibuat asc $whereSO");
        $fetch2 = $this->db->query(" select a.id_work_order,left(a.created_at,10) as tanggal_dibuat,a.status,'Work Order' as referensi ,wo_parts.id_jasa,js.deskripsi,wo_parts.id_part,wo_parts.qty,wo_parts.nomor_so
         from tr_h2_wo_dealer a join tr_h2_wo_dealer_parts wo_parts on a.id_work_order=wo_parts.id_work_order
         join ms_h2_jasa js on wo_parts.id_jasa = js.id_jasa 
         $where2
         	union ALL 
         select so.nomor_so,left(so.created_at,10) as tanggal_dibuat,so.status,'Direct Sales' as referensi,'-' as id_jasa,'-' as deskripsi ,sop.id_part,sop.kuantitas,sop.nomor_so from tr_h3_dealer_sales_order so 
         join tr_h3_dealer_sales_order_parts sop on so.nomor_so =sop.nomor_so order by tanggal_dibuat asc $whereSO2");

        
        $id_dealer = $this->m_admin->cari_dealer();
        
        foreach($fetch->result() as $rows){
            $customer = $this->db->query("SELECT b.id_customer,b.nama_customer from ms_customer_h23 b where id_customer='$rows->id_customer'")->row();
            array_push($result,array(
              "index"=>$index,
                "id_work_order"=>$rows->id_work_order,
                "tanggal"=>$rows->tanggal_dibuat,
                "status"=>$rows->status,
                "referensi"=>$rows->referensi,
                "id_jasa"=>$rows->id_jasa,
                "deskripsi"=>$rows->deskripsi,
                "id_part"=>$rows->id_part,
                "qty"=>$rows->qty,
                "nomor_so"=>$rows->nomor_so
               ));
               $index++;
        }
        $output = array(
          "draw"            =>     intval($this->input->post("draw")),
          "recordsFiltered" =>     $fetch2->num_rows(),
          'recordsTotal' => $fetch2->num_rows(),
          "data"            =>     $result,
         
        );
        echo json_encode($output);
    
    }
}

?>