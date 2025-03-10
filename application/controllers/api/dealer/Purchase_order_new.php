<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order_new extends CI_Controller {

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
        $filter_status        = $this->input->post('filter_status');
        $filter_tipe_po       = $this->input->post('filter_tipe_po');
        $filter_purchase_date = $this->input->post('filter_purchase_date');
        $orders               = isset($_POST["order"]) ? $_POST["order"] : ''; 
        
        $where ="WHERE 1=1 AND po.po_nmd=0 AND po.id_dealer ='{$this->m_admin->cari_dealer()}'";
        $where2 ="WHERE 1=1 AND po.po_nmd=0 AND po.id_dealer ='{$this->m_admin->cari_dealer()}'";
        $searchingColumn="";
        $result=array();
        
         if($this->input->post('filter_status') != null){
           $where .= " AND po.status='$filter_status' ";
           $where2 .= " AND po.status='$filter_status' ";
         }
         
         if($this->input->post('filter_tipe_po') != null){
           $where .= " AND po.po_type='$filter_tipe_po' ";
           $where2 .= " AND po.po_type='$filter_tipe_po' ";
         }


        if($this->input->post('filter_purchase_date') != null){
             $where .= " AND (po.tanggal_order >= '{$this->input->post('start_date')}' AND po.tanggal_order <='{{$this->input->post('end_date')}}') ";
             $where2 .= " AND (po.tanggal_order >= '{$this->input->post('start_date')}' AND po.tanggal_order <='{{$this->input->post('end_date')}}') ";
          
        }
        // if (isset($search)) {
        //   if ($search != '') {
        //      $searchingColumn = $search;
        //         $where .= " AND (po.po_id LIKE '%$search%' OR po.id_booking LIKE '%$search%') ";
        //         $where2 .= " AND (po.po_id LIKE '%$search%' OR po.id_booking LIKE '%$search%') ";
        //       }
        //   }

        $filter_search_po_id = $this->input->post('filter_search_po_id');
        // $filter_search_nama_cust = $this->input->post('filter_search_nama_cust');
        $filter_no_booking = $this->input->post('filter_no_booking');

        if($filter_search_po_id != ''){
            $where .= " AND (po.po_id LIKE '%$filter_search_po_id%') ";
            $where2 .= " AND (po.po_id LIKE '%$filter_search_po_id%') ";
        }

        if($filter_no_booking != ''){
          $where .= " AND (po.id_booking LIKE '%$filter_no_booking%') ";
          $where2 .= " AND (po.id_booking LIKE '%$filter_no_booking%') ";
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
              $where .= " ORDER BY po.created_at DESC ";
              $where2 .= " ORDER BY po.created_at DESC ";
            }
          } else {
            $where .= " ORDER BY po.created_at DESC ";
            $where2 .= " ORDER BY po.created_at DESC ";
          }
          if (isset($LIMIT)) {
            if ($LIMIT != '') {
              $where .= ' ' . $LIMIT;
            }
          }
        $index=1;
        $button="";
        $fetch = $this->db->query("select po.created_at,po.id,po.po_id,po.po_type,po.id_booking,po.tanggal_order,po.tanggal_selesai,po.status,po.pesan_untuk_bulan,doc.id_customer,(CASE WHEN po.autofulfillment_md != '' then 'Ya' else '-' end) as autofulfillment_md, (CASE WHEN po.created_by_md = 1 then 'Ya' else '-' end) as created_by_md from tr_h3_dealer_purchase_order po left join tr_h3_dealer_request_document doc on po.id_booking = doc.id_booking $where");
        $fetch2 = $this->db->query("select po.created_at,po.id,po.po_id,po.po_type,po.id_booking,po.tanggal_order,po.tanggal_selesai,po.status,po.pesan_untuk_bulan,doc.id_customer,(CASE WHEN po.autofulfillment_md != '' then 'Ya' else '-' end) as autofulfillment_md, (CASE WHEN po.created_by_md = 1 then 'Ya' else '-' end) as created_by_md from tr_h3_dealer_purchase_order po left join tr_h3_dealer_request_document doc on po.id_booking = doc.id_booking $where2 ");
        

        
        foreach($fetch->result() as $rows){
       
            $customer = $this->db->query("SELECT nama_customer from ms_customer_h23 where id_customer='$rows->id_customer'");
            $qty_unit = $this->db->query("SELECT IFNULL(SUM(kuantitas),0) as qty_unit from tr_h3_dealer_purchase_order_parts where po_id='$rows->po_id' group by po_id");
            $qty_item = $this->db->query("SELECT IFNULL(COUNT(kuantitas),0) as qty_item from tr_h3_dealer_purchase_order_parts where po_id='$rows->po_id'");
            
            // $penerimaan = $this->db->query("SELECT IFNULL(SUM(gr_item.qty_good),0) as qty_order_fulfillment from tr_h3_dealer_penerimaan_barang gr join tr_h3_dealer_penerimaan_barang_items gr_item on gr.id_penerimaan_barang = gr_item.id_penerimaan_barang join tr_h3_md_packing_sheet ps on gr.id_packing_sheet=ps.id_packing_sheet join tr_h3_md_picking_list pl on ps.id_picking_list=pl.id_picking_list join tr_h3_md_do_sales_order dso on dso.id_do_sales_order=pl.id_ref join tr_h3_md_sales_order so on so.id_sales_order=dso.id_sales_order join tr_h3_dealer_purchase_order po on po.po_id=so.id_ref where po.po_id='$rows->po_id'");
            $penerimaan = $this->db->query("SELECT IFNULL(SUM(qty_fulfillment),0) as qty_order_fulfillment from tr_h3_dealer_order_fulfillment where po_id='$rows->po_id'");
            $fullfill   = round(@(($penerimaan->num_rows() > 0 ? $penerimaan->row()->qty_order_fulfillment : 0 ) /  ($qty_unit->num_rows() > 0 ? $qty_unit->row()->qty_unit : 0)) * 100);
            if($fullfill==100){
                $this->db->query("UPDATE tr_h3_dealer_purchase_order SET status='Closed' WHERE po_id ='$rows->po_id' and status='Submitted'");
            }
            array_push($result,array(
               "index"=>$index,
               "aksi"=> $this->load->view('additional/action_purchase_order', [
                'po_id' => $rows->po_id,
                ], true),
               "periode"=>$rows->po_type == "FIX" ? $rows->pesan_untuk_bulan : "-",
               "po_type"=>strtoupper($rows->po_type),
               "nama_customer"=> $customer->num_rows() > 0 ? $customer->row()->nama_customer : "-",
               "id_booking"=>$rows->id_booking,
               "created_by_md"=>$rows->created_by_md,
               "autofulfillment_md"=>$rows->autofulfillment_md,
               "unit_qty"=>$qty_unit->num_rows() > 0 ? $qty_unit->row()->qty_unit : 0, 
               "item_qty"=>$qty_item->num_rows() > 0 ? $qty_item->row()->qty_item : 0, 
               "tanggal_order"=>formatTanggal($rows->tanggal_order),
               "tanggal_selesai"=>$rows->tanggal_selesai,
               "fulfillment_qty"=>$penerimaan->num_rows() > 0 ? $penerimaan->row()->qty_order_fulfillment : 0,
               "fulfillment_rate"=>$fullfill."%",
               "status"=> $this->load->view('additional/status_purchase_order', [
                'status' => $rows->status,
                ], true),
               
                
               ));
               
               $index++;
        }
        $output = array(
          "draw"            =>     intval($this->input->post("draw")),
          "recordsFiltered" =>     $fetch2->num_rows(),
          "data"            =>     $result,
          
         
        );
        echo json_encode($output);
    
    }
}
?>