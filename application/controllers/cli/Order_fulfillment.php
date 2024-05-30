<?php

class Order_fulfillment extends Honda_Controller
{
    public function set_int_relation()
    {
        $this->load->model('H3_dealer_order_fulfillment_model', 'order_fulfillment');
        $data = $this->db
            ->select('of.id')
            ->from('tr_h3_dealer_order_fulfillment as of')
            ->group_start()
            ->or_where('of.po_id_int', null)
            ->or_where('of.id_part_int', null)
            ->group_end()
            ->get()->result_array();;

        $this->db->trans_start();
        foreach ($data as $row) {
            $this->order_fulfillment->set_int($row['id']);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            echo 'Berhasil';
        } else {
            echo 'Gagal';
        }
    }
}
