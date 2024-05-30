<?php

class H3_dealer_order_fulfillment_model extends Honda_Model
{
    protected $table = 'tr_h3_dealer_order_fulfillment';

    public function insert($data)
    {
        $data['created_at'] = Mcarbon::now()->toDateTimeString();
        $data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
        $id = $this->db->insert_id();

        $this->set_int($id);
    }

    public function update($data, $condition)
    {
        $data['updated_at'] = Mcarbon::now()->toDateTimeString();
        $data['updated_by'] = $this->session->userdata('id_user');
        parent::update($data, $condition);
    }

    public function set_int($id)
    {
        $data = $this->db
            ->from($this->table)
            ->where('id', $id)
            ->get()->row_array();

        if ($data == null) log_message('info', 'Order fullfillment tidak ditemukan');

        $purchase_order = $this->db
            ->from('tr_h3_dealer_purchase_order')
            ->where('po_id', $data['po_id'])
            ->limit(1)
            ->get()->row_array();

        if ($purchase_order == null) log_message('info', 'Purchase order dealer tidak ditemukan');

        $part = $this->db
            ->from('ms_part')
            ->where('id_part', $data['id_part'])
            ->limit(1)
            ->get()->row_array();

        if ($part == null) log_message('info', 'Part tidak ditemukan');

        $this->db
            ->set('id_part_int', $part['id_part_int'])
            ->set('po_id_int', $purchase_order['id'])
            ->where('id', $id)
            ->update($this->table);

        log_message('info', sprintf('Integer relation untuk order fulfillment telah berhasil diisi [%s]', $id));
    }
}
