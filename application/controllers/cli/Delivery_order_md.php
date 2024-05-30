<?php

class Delivery_order_md extends Honda_Controller
{
    public function set_int_relation()
    {
        $this->db
            ->select('do.id')
            ->select('so.id as id_sales_order_int')
            ->from('tr_h3_md_do_sales_order as do')
            ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
            ->where('do.id_sales_order_int IS NULL', null, false)
            ->limit(2000);

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
                ->set('id_sales_order_int', $row['id_sales_order_int'])
                ->where('id', $row['id'])
                ->update('tr_h3_md_do_sales_order');
        }
    }
}
