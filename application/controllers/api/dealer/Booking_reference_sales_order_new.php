<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_reference_sales_order_new extends CI_Controller {

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
        $orders               = isset($_POST["order"]) ? $_POST["order"] : ''; 
        
        $where ="WHERE 1=1 AND a.id_dealer ='{$this->m_admin->cari_dealer()}'";
        $where2 ="WHERE 1=1 AND a.id_dealer ='{$this->m_admin->cari_dealer()}'";
        $searchingColumn="";
        $result=array();
        
        if (isset($search)) {
          if ($search != '') {
             $searchingColumn = $search;
                $where .= " AND (a.id_booking LIKE '%$search%' ) ";
                $where2 .= " AND (a.id_booking LIKE '%$search%' ) ";
              }
          }
    
        if (isset($orders)) {
            if ($orders != '') {
              $order = $orders;
              $order_column = ['jenis'];
              $order_clm  = $order_column[$order[0]['column']];
              $order_by   = $order[0]['dir'];
              $where .= " ORDER BY $order_clm $order_by ";
              $where2 .= " ORDER BY $order_clm $order_by ";
            } else {
              $where .= " ORDER BY a.id_booking DESC ";
              $where2 .= " ORDER BY a.id_booking DESC ";
            }
          } else {
            $where .= " ORDER BY a.id_booking DESC ";
            $where2 .= " ORDER BY a.id_booking DESC ";
          }
          if (isset($LIMIT)) {
            if ($LIMIT != '') {
              $where .= ' ' . $LIMIT;
            }
          }
        $index=1;
        $button="";
        $fetch = $this->db->query("SELECT a.id,a.id_booking,a.id_customer from tr_h3_dealer_request_document a $where");
        $fetch2 = $this->db->query("SELECT a.id,a.id_booking,a.id_customer from tr_h3_dealer_request_document a $where2 ");
        
        $id_dealer = $this->m_admin->cari_dealer();
        
        foreach($fetch->result() as $rows){
            $customer = $this->db->query("SELECT * from ms_customer_h23 b where id_customer='$rows->id_customer'")->row();
            array_push($result,array(
              "index"=>$index,
                "action"=>$this->load->view('additional/action_booking_reference_sales_order', [
                    'data' => json_encode($rows)
                ], true),
                "id_booking"=>$rows->id_booking,
                "id_customer"=>$customer->id_customer,
                "nama_customer"=>$customer->nama_customer,
                "id_customer_int"=>$customer->id_customer_int,
                "alamat"=>$customer->alamat,
                "no_hp"=> $customer->no_hp,
                "no_mesin"=>$customer->no_mesin,
                "no_rangka"=>$customer->no_rangka,
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