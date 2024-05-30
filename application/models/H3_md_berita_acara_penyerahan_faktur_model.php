<?php

class H3_md_berita_acara_penyerahan_faktur_model extends Honda_Model
{
    protected $table = 'tr_h3_md_berita_acara_penyerahan_faktur';

    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
    }

    public function generate_id()
    {
        $tahun = date('Y');
        $bulan = date('m');

        $query = $this->db
            ->select('no_bap')
            ->from($this->table)
            ->where("LEFT(created_at, 4)='{$tahun}'", null, false)
            ->order_by('id', 'DESC')
            ->order_by('created_at', 'DESC')
            ->limit(1)
            ->get();

        if ($query->num_rows() > 0) {
            $data = $query->row_array();
            $exploded_nomor_bap = explode('/', $data['no_bap']);
            $urutan_nomor_bap = intval($exploded_nomor_bap[0]);

            $no_bap = sprintf("%'.04d", $urutan_nomor_bap + 1);
            return "{$no_bap}/{$bulan}/PART/{$tahun}";
        } else {
            return "0001/{$bulan}/PART/{$tahun}";
        }
    }
}
