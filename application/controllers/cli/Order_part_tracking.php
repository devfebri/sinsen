<?php

use GO\Scheduler;

class Order_part_tracking extends Honda_Controller
{
    public function set_int_relation()
    {
        $this->db->trans_start();
        $this->db
            ->select('opt.id')
            ->select('p.id_part_int')
            ->select('po.id as po_id_int')
            ->from('tr_h3_dealer_order_parts_tracking as opt')
            ->join('ms_part as p', 'p.id_part = opt.id_part')
            ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = opt.po_id')
            ->group_start()
            ->where('opt.id_part_int', null)
            ->or_where('opt.po_id_int', null)
            ->group_end()
            ->limit(1000);

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
                ->set('id_part_int', $row['id_part_int'])
                ->set('po_id_int', $row['po_id_int'])
                ->where('id', $row['id'])
                ->update('tr_h3_dealer_order_parts_tracking');
        }
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            echo 'Berhasil';
        } else {
            echo 'Gagal';
        }
    }
}
