<?php

class H3_md_ps_model extends Honda_Model{

    protected $table = 'tr_h3_md_ps';

    public function insert($data){
        $data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
    }

    public function set_jumlah_karton($id){
        $this->db
        ->from('tr_h3_md_ps_parts as psp')
        ->where('psp.packing_sheet_number_int', $id);

        $karton = array_column($this->db->get()->result_array(), 'no_doos');
        $karton_tanpa_duplikat = array_unique($karton);
        $jumlah_karton = count($karton_tanpa_duplikat);

        if($jumlah_karton > 0){
            $this->db
            ->set('ps.jumlah_karton', $jumlah_karton)
            ->where('ps.id', $id)
            ->update(sprintf('%s as ps', $this->table));

            log_message('debug', sprintf('Set jumlah karton sebanyak %s untuk packing sheet number [%s]', $jumlah_karton, $id));
        }
    }

}
