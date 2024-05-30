<?php

class H3_md_history_estimasi_waktu_hotline_model extends Honda_Model
{

    protected $table = 'tr_h3_md_history_estimasi_waktu_hotline';

    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['created_by'] = $this->session->userdata('id_user');

        log_message('info', sprintf('Menambahkan history estimasi waktu hotline %s', print_r($data, true)));

        parent::insert($data);
    }

    public function insert_batch($data)
    {
        $data = array_map(function ($row) {
            $row['created_at'] = date('Y-m-d H:i:s', time());
            $row['created_by'] = $this->session->userdata('id_user');
            return $row;
        }, $data);

        parent::insert_batch($data);
    }
}
