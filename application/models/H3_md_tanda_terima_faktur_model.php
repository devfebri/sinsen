<?php

class H3_md_tanda_terima_faktur_model extends Honda_Model
{
    protected $table = 'tr_h3_md_tanda_terima_faktur';

    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
    }

    public function generate_id()
    {
        $th        = date('Y');
        $bln       = date('m');

        $query = $this->db
            ->select('no_tanda_terima_faktur')
            ->from($this->table)
            ->where("LEFT(created_at, 4)='{$th}'")
            ->where('created_at > ', '2022-04-24 23:21:00')
            ->order_by('id', 'DESC')
            ->order_by('created_at', 'DESC')
            ->order_by('no_tanda_terima_faktur', 'DESC')
            ->limit(1)
            ->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            $no_tanda_terima_faktur = substr($row->no_tanda_terima_faktur, 0, 4);
            $no_tanda_terima_faktur = sprintf("%'.04d", $no_tanda_terima_faktur + 1);
            $id = "{$no_tanda_terima_faktur}/{$bln}/TT/Part/{$th}";
        } else {
            $id = "0001/{$bln}/TT/Part/{$th}";
        }

        return strtoupper($id);
    }
}
