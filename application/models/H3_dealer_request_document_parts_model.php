<?php

class h3_dealer_request_document_parts_model extends Honda_Model
{
    protected $table = 'tr_h3_dealer_request_document_parts';

    public function insert($data)
    {
        parent::insert($data);
        $id = $this->db->insert_id();

        $this->set_int_relation($id);

        return $this->db
            ->from($this->table)
            ->where('id', $id)
            ->limit(1)
            ->get()->row_array();
    }

    public function set_int_relation($id)
    {
        $data = $this->db
            ->from($this->table)
            ->where('id', $id)
            ->limit(1)
            ->get()->row_array();
        if ($data == null) throw new Exception(sprintf('Request document parts tidak ditemukan [%s]', $id));

        $request_document = $this->db
            ->select('id')
            ->from('tr_h3_dealer_request_document')
            ->where('id_booking', $data['id_booking'])
            ->limit(1)
            ->get()->row_array();
        if ($request_document == null) throw new Exception(sprintf('Request document %s tidak ditemukan', $data['id_booking']));

        $part = $this->db
            ->select('id_part_int as id')
            ->from('ms_part')
            ->where('id_part', $data['id_part'])
            ->limit(1)
            ->get()->row_array();
        if ($part == null) throw new Exception(sprintf('Kode part %s tidak ditemukan', $data['id_part']));

        $updated = $this->db
            ->set('id_booking_int', $request_document['id'])
            ->set('id_part_int', $part['id'])
            ->where('id', $id)
            ->update($this->table);

        if ($updated) log_message('info', sprintf('Set relation untuk request document part %s', $data['id_booking']));

        return $updated;
    }
}
