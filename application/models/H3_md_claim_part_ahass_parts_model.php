<?php

class h3_md_claim_part_ahass_parts_model extends Honda_Model
{

    protected $table = 'tr_h3_md_claim_part_ahass_parts';

    public function insert_batch($data)
    {
        $data = array_map(function ($row) {
            $row['status'] = 'Open';
            return $row;
        }, $data);
        parent::insert_batch($data);
    }

    public function insert($data)
    {
        parent::insert($data);
        $id = $this->db->insert_id();

        $this->set_int_relation($id);
    }

    public function set_int_relation($id)
    {
        $data = $this->db
            ->select('p.id_part_int')
            ->select('cpa.id as id_claim_part_ahass_int')
            ->select('cd.id as id_claim_dealer_int')
            ->from(sprintf('%s as cpai', $this->table))
            ->join('tr_h3_md_claim_part_ahass as cpa', 'cpa.id_claim_part_ahass = cpai.id_claim_part_ahass')
            ->join('ms_part as p', 'p.id_part = cpai.id_part')
            ->join('tr_h3_md_claim_dealer as cd', 'cd.id_claim_dealer = cpai.id_claim_dealer')
            ->where('cpai.id', $id)
            ->limit(1)
            ->get()->row_array();

        if ($data != null) {
            $this->db
                ->set('id_claim_part_ahass_int', $data['id_claim_part_ahass_int'])
                ->set('id_claim_dealer_int', $data['id_claim_dealer_int'])
                ->set('id_part_int', $data['id_part_int'])
                ->where('id', $id)
                ->update($this->table);

            log_message('debug', sprintf('Update int relation claim part ahass parts id [%s]', $id));
        }
    }
}
