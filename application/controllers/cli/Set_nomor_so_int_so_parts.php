<?php

class Set_nomor_so_int_so_parts extends Honda_Controller
{

    public function index()
    {
        $this->db
            ->select('sop.id')
            ->select('sop.nomor_so')
            ->select('so.id as nomor_so_int')
            ->from('tr_h3_dealer_sales_order_parts as sop')
            ->join('tr_h3_dealer_sales_order as so', '(so.nomor_so = sop.nomor_so)')
            ->limit(50000)
            ->where('sop.nomor_so_int_baru is null', null, false)
            ;

        foreach ($this->db->get()->result_array() as $row) {
            $this->db->trans_start();
            $this->db
                ->set('nomor_so_int', $row['nomor_so_int'])
                ->where('id', $row['id'])
                ->update('tr_h3_dealer_sales_order_parts');
            $this->db->trans_complete();
        }


        if ($this->db->trans_status()) {
            echo 'Berhasil';
        } else {
            echo 'Gagal';
        }
    }
}
