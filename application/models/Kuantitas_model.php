<?php

class Kuantitas_model extends Honda_Model
{
    public function qty_used_for_sales_order($id_part, $sql_reference = true, $compiled_query = true){
        $this->db
        ->select('ifnull( sum(sop.kuantitas), 0) as qty_sales_order')
        ->from('tr_h3_dealer_sales_order as so')
        ->join('tr_h3_dealer_sales_order_parts as sop', 'so.nomor_so = sop.nomor_so')
        ->where('so.id_dealer', $this->m_admin->cari_dealer())
        ->where('so.status !=', 'Closed');

        if($sql_reference){
            $this->db->where("sop.id_part = {$id_part}");
        }else{
            $this->db->where("sop.id_part = '{$id_part}'");
        }

        if($compiled_query){
            return $this->db->get_compiled_select();
        }else{
            return $this->db->get()->row()->qty_sales_order;
        }
    }

    public function qty_used_for_outbound_fulfillment($id_part, $sql_reference = true, $compiled_query = true){

        $this->db
        ->select('ifnull( sum(offp.kuantitas), 0) as qty_outbound_fulfillment')
        ->from('tr_h3_dealer_outbound_form_for_fulfillment as off')
        ->join('tr_h3_dealer_outbound_form_for_fulfillment_parts as offp', 'off.id_outbound_form_for_fulfillment = offp.id_outbound_form_for_fulfillment')
        ->where('off.id_dealer', $this->m_admin->cari_dealer())
        ->where('off.status !=', 'Open');

        if($sql_reference){
            $this->db->where("offp.id_part = {$id_part}");
        }else{
            $this->db->where("offp.id_part = '{$id_part}'");
        }

        if($compiled_query){
            return $this->db->get_compiled_select();
        }else{
            return $this->db->get()->row()->qty_outbound_fulfillment;
        }

        // $qty_book = "({$qty_used_for_sales_order}) - ({$qty_used_for_outbound_fulfillment})";
    }

    public function qty_book($id_part, $sql_reference = true, $compiled_query = true){
        if($compiled_query){
            $query_qty_used_for_sales_order = $this->qty_used_for_sales_order($id_part, $sql_reference, $compiled_query);
            $query_qty_used_for_outbound_fulfillment = $this->qty_used_for_outbound_fulfillment($id_part, $sql_reference, $compiled_query);

            return $qty_book = "({$query_qty_used_for_sales_order}) + ({$query_qty_used_for_outbound_fulfillment})";
        }else{
            $qty_used_for_sales_order = $this->qty_used_for_sales_order($id_part, $sql_reference, false);
            $qty_used_for_outbound_fulfillment = $this->qty_used_for_outbound_fulfillment($id_part, $sql_reference, false);

            return $qty_used_for_sales_order + $qty_used_for_outbound_fulfillment;
        }
    }
}
