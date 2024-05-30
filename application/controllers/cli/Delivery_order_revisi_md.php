<?php

class Delivery_order_revisi_md extends Honda_Controller
{
    public function set_int_relation()
    {
        $this->db
            ->select('dr.id')
            ->select('do.id as id_do_sales_order_int')
            ->from('tr_h3_md_do_revisi as dr')
            ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dr.id_do_sales_order')
            ->where('dr.id_do_sales_order_int is null', null, false)
            ->limit(1000);

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
                ->set('id_do_sales_order_int', $row['id_do_sales_order_int'])
                ->where('id', $row['id'])
                ->update('tr_h3_md_do_revisi');
        }
    }
}
