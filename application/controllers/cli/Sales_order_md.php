<?php

class Sales_order_md extends Honda_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('h3_md_sales_order_parts_model', 'h3_md_sales_order_parts');
    }

    public function set_int_relation()
    {
        $this->db
            ->select('sop.id')
            ->from('tr_h3_md_sales_order_parts as sop')
            ->group_start()
            ->where('sop.id_part_int IS NULL', null, false)
            ->or_where('sop.id_sales_order_int IS NULL', null, false)
            ->group_end()
            ->limit(2000);

        foreach ($this->db->get()->result_array() as $row) {
            $this->h3_md_sales_order_parts->set_int_relation($row['id']);
        }
    }
}
