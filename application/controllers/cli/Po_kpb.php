<?php

class Po_kpb extends Honda_Controller
{

    public function __construct()
    {
        parent::__construct();
        ini_set('max_execution_time', 0);
    }

    public function index()
    {
        $this->po_kpb_detail();
    }

    public function po_kpb_detail()
    {
        $this->db
            ->select('pkd.id_detail')
            ->select('p.id_part_int')
            ->from('tr_po_kpb_detail as pkd')
            ->join('ms_part as p', 'p.id_part = pkd.id_part_h3')
            ->group_start()
            ->where('pkd.id_part_h3 IS NOT NULL', null, false)
            ->where('pkd.id_part_h3_int IS NULL', null, false)
            ->group_end();

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
                ->set('id_part_h3_int', $row['id_part_int'])
                ->where('id_detail', $row['id_detail'])
                ->update('tr_po_kpb_detail');
        }
    }
}
