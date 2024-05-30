<?php

use GO\Scheduler;

class Set_id_int_picking_slip_dealer extends Honda_Controller
{

    public function index()
    {
        $this->picking_slip();
    }

    public function picking_slip()
    {
        $this->load->model('h3_dealer_picking_slip_model', 'picking_slip');

        $this->db
            ->select('ps.id')
            ->from('tr_h3_dealer_picking_slip as ps')
            ->where('ps.nomor_ps', '00888-PS/11/21/00484')
            // ->where('ps.nomor_so_int is null', null, false)
            ;

        $this->db->trans_start();
        foreach ($this->db->get()->result_array() as $row) {
            $this->picking_slip->int_relation($row['id']);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            echo 'Berhasil';
        } else {
            echo 'Gagal';
        }
    }
}
