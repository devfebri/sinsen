<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order_non_md extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin');
    }
    
    public function index(){
        $starts       = $this->input->post("start");
        $length       = $this->input->post("length");
        $LIMIT        = "LIMIT $starts, $length ";
        $draw         = $this->input->post("draw");
        $search       = $this->input->post("search")["value"];
        $filter_purchase_date = $this->input->post('filter_purchase_date');
        $orders               = isset($_POST["order"]) ? $_POST["order"] : ''; 
        
        $where ="WHERE 1=1 AND po.po_nmd = 1 AND po.id_dealer ='{$this->m_admin->cari_dealer()}'";
        $searchingColumn="";
        $result=array();
        
         
        if($this->input->post('filter_purchase_date') != null){
             $where .= " AND (po.tanggal_order >= '{$this->input->post('start_date')}' AND po.tanggal_order <='{{$this->input->post('end_date')}}') ";
          
        }

        $filter_search_po_id = $this->input->post('filter_search_po_id');

        if($filter_search_po_id != ''){
            $where .= " AND (po.po_id LIKE '%$filter_search_po_id%') ";
        }
    
        if (isset($orders)) {
            if ($orders != '') {
              $order = $orders;
              $order_column = ['jenis'];
              $order_clm  = $order_column[$order[0]['column']];
              $order_by   = $order[0]['dir'];
              $where .= " ORDER BY $order_clm $order_by ";
            } else {
              $where .= " ORDER BY po.created_at DESC ";
            }
          } else {
            $where .= " ORDER BY po.created_at DESC ";
          }
          if (isset($LIMIT)) {
            if ($LIMIT != '') {
              $where .= ' ' . $LIMIT;
            }
          }
        $index=1;
        $button="";
        $fetch = $this->db->query("select po.created_at,po.id,po.po_id,po.po_type,po.id_booking,po.tanggal_order,po.tanggal_selesai,po.status,po.pesan_untuk_bulan 
        from tr_h3_dealer_purchase_order po 
         $where");        

        
        foreach($fetch->result() as $rows){
            array_push($result,array(
               "index"=>$index,
               "aksi"=> $this->load->view('additional/action_purchase_order_non_md', [
                'po_id' => $rows->po_id,
                ], true),
               "periode"=>$rows->po_type == "FIX" ? $rows->pesan_untuk_bulan : "-",
               
                
               ));
               
               $index++;
        }
        $output = array(
          "draw"            =>     intval($this->input->post("draw")),
          "recordsFiltered" =>     $fetch->num_rows(),
          "data"            =>     $result,
          
         
        );
        echo json_encode($output);
    
    }
}
?>