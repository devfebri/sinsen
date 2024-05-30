<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Uang_jaminan_new extends CI_Controller {


     var $page   = "uang_jaminan";
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
        $orders       = isset($_POST["order"]) ? $_POST["order"] : ''; 
        
        $where ="WHERE 1=1 AND uj.id_dealer ='{$this->m_admin->cari_dealer()}' AND uj.sisa_bayar >= 0 ";
        $where2 ="WHERE 1=1 AND uj.id_dealer ='{$this->m_admin->cari_dealer()}' AND uj.sisa_bayar >= 0 ";
        $searchingColumn="";
        $result=array();
        
         
        if (isset($search)) {
          if ($search != '') {
             $searchingColumn = $search;
                $where .= " AND (uj.no_inv_uang_jaminan LIKE '%$search%' OR uj.id_booking LIKE '%$search%' OR cus.nama_customer LIKE '%$search%') ";
                $where2 .= " AND (uj.no_inv_uang_jaminan LIKE '%$search%' OR uj.id_booking LIKE '%$search%' OR cus.nama_customer LIKE '%$search%') ";
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
              $where .= " ORDER BY uj.tgl_invoice DESC ";
              $where2 .= " ORDER BY uj.tgl_invoice DESC ";
            }
          } else {
            $where .= " ORDER BY uj.tgl_invoice DESC ";
            $where2 .= " ORDER BY uj.tgl_invoice DESC ";
          }
          if (isset($LIMIT)) {
            if ($LIMIT != '') {
              $where .= ' ' . $LIMIT;
            }
          }
        $index=1;
        $fetch = $this->db->query("SELECT uj.no_inv_uang_jaminan,uj.cetak_ke,uj.tgl_invoice,uj.id_booking,doc.created_at as tgl_request,doc.id_customer,uj.total_bayar,uj.sisa_bayar,cus.nama_customer,doc.status from tr_h2_uang_jaminan uj join tr_h3_dealer_request_document doc on doc.id_booking=uj.id_booking join ms_customer_h23 cus on doc.id_customer=cus.id_customer $where");
        $fetch2 = $this->db->query("SELECT uj.no_inv_uang_jaminan,uj.cetak_ke,uj.tgl_invoice,uj.id_booking,doc.created_at as tgl_request,doc.id_customer,uj.total_bayar,uj.sisa_bayar,cus.nama_customer, doc.status from tr_h2_uang_jaminan uj join tr_h3_dealer_request_document doc on doc.id_booking=uj.id_booking join ms_customer_h23 cus on doc.id_customer=cus.id_customer $where2");
        
        $data = array();
        foreach($fetch->result() as $rs){
            //   $customer = $this->db->get_where('ms_customer_h23',['id_customer'=>$rs->id_customer]);
              $sub_array = array();
              $button = '';
              $btn_print = '<a href="dealer/uang_jaminan/cetak?id=' . $rs->no_inv_uang_jaminan . '" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i></a>';
              $btn_print_uj_revisi = '<a href="dealer/uang_jaminan/cetak_revisi?id=' . $rs->no_inv_uang_jaminan . '" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-print"></i></a>';
              $btn_edit = '<a href="dealer/uang_jaminan/edit?id=' . $rs->no_inv_uang_jaminan . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-pencil"></i></a>';
              if (can_access($this->page, 'can_update')) {
                if ($rs->cetak_ke == 0 && $rs->sisa_bayar > 0) {
                  $button .= $btn_edit;
                }
              }
              // if (can_access($this->page, 'can_print')) $button .= $btn_print;

              if ($rs->status == 'Revisi Dealer') {
                if (can_access($this->page, 'can_print')) $button .= $btn_print_uj_revisi;
              }else{
                if (can_access($this->page, 'can_print')) $button .= $btn_print;
              }
        
              $sub_array[] = '<a href="dealer/uang_jaminan/detail?id=' . $rs->no_inv_uang_jaminan . '">' . $rs->no_inv_uang_jaminan . '</a>';;
              $sub_array[] =  date_dmy($rs->tgl_invoice);
              $sub_array[] = $rs->id_booking;
              $sub_array[] = $rs->tgl_request;
              $sub_array[] = $rs->id_customer;
              $sub_array[] = $rs->nama_customer;
              $sub_array[] = mata_uang_rp($rs->total_bayar);
              $sub_array[] = mata_uang_rp($rs->sisa_bayar);
              $sub_array[] = $button;
              $data[]      = $sub_array;
        }
        $output = array(
          "draw"            =>    intval($this->input->post("draw")),
          "recordsFiltered" =>    $fetch2->num_rows(),
          "data"            =>    $data,
          
         
        );
        echo json_encode($output);
    
    }
}
?>