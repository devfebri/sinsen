<?php

class H3_dealer_order_parts_tracking_model extends Honda_Model
{

    protected $table = 'tr_h3_dealer_order_parts_tracking';

    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
        $id = $this->db->insert_id();

        $this->set_int_relation($id);
    }

    public function update($data, $condition)
    {
        $data['updated_at'] = date('Y-m-d H:i:s', time());
        $data['updated_by'] = $this->session->userdata('id_user');
        parent::update($data, $condition);
    }

    public function tambah_qty_book($id_purchase, $id_part, $value, $id_tipe_kendaraan = null)
    {
        $this->db->set('opt.qty_book', "opt.qty_book + {$value}", FALSE)
            ->where('opt.id_part', $id_part)
            ->where('opt.po_id', $id_purchase);

        if ($id_tipe_kendaraan != null) {
            $this->db->where('opt.id_tipe_kendaraan', $id_tipe_kendaraan);
        }

        return $this->db->update("{$this->table} as opt");
    }

    public function kurang_qty_book($id_purchase, $id_part, $value, $id_tipe_kendaraan = null)
    {
        $this->db->set('opt.qty_book', "opt.qty_book - {$value}", FALSE)
            ->where('opt.id_part', $id_part)
            ->where('opt.po_id', $id_purchase);

        if ($id_tipe_kendaraan != null) {
            $this->db->where('opt.id_tipe_kendaraan', $id_tipe_kendaraan);
        }

        return $this->db->update("{$this->table} as opt");
    }

    public function tambah_qty_pick($id_purchase, $id_part, $value, $id_tipe_kendaraan = null)
    {
        $this->db->set('opt.qty_pick', "opt.qty_pick + {$value}", FALSE)
            ->where('opt.id_part', $id_part)
            ->where('opt.po_id', $id_purchase);

        if ($id_tipe_kendaraan != null) {
            $this->db->where('opt.id_tipe_kendaraan', $id_tipe_kendaraan);
        }

        return $this->db->update("{$this->table} as opt");
    }

    public function kurang_qty_pick($id_purchase, $id_part, $value, $id_tipe_kendaraan = null)
    {
        $this->db->set('opt.qty_pick', "opt.qty_pick - {$value}", FALSE)
            ->where('opt.id_part', $id_part)
            ->where('opt.po_id', $id_purchase);

        if ($id_tipe_kendaraan != null) {
            $this->db->where('opt.id_tipe_kendaraan', $id_tipe_kendaraan);
        }

        return $this->db->update("{$this->table} as opt");
    }

    public function tambah_qty_pack($id_purchase, $id_part, $value, $id_tipe_kendaraan = null)
    {
        $this->db->set('opt.qty_pack', "opt.qty_pack + {$value}", FALSE)
            ->where('opt.id_part', $id_part)
            ->where('opt.po_id', $id_purchase);

        if ($id_tipe_kendaraan != null) {
            $this->db->where('opt.id_tipe_kendaraan', $id_tipe_kendaraan);
        }

        return $this->db->update("{$this->table} as opt");
    }

    public function kurang_qty_pack($id_purchase, $id_part, $value, $id_tipe_kendaraan = null)
    {
        $this->db->set('opt.qty_pack', "opt.qty_pack - {$value}", FALSE)
            ->where('opt.id_part', $id_part)
            ->where('opt.po_id', $id_purchase);

        if ($id_tipe_kendaraan != null) {
            $this->db->where('opt.id_tipe_kendaraan', $id_tipe_kendaraan);
        }

        return $this->db->update("{$this->table} as opt");
    }

    public function tambah_qty_bill($id_purchase, $id_part, $value, $id_tipe_kendaraan = null)
    {
        $this->db->set('opt.qty_bill', "opt.qty_bill + {$value}", FALSE)
            ->where('opt.id_part', $id_part)
            ->where('opt.po_id', $id_purchase);

        if ($id_tipe_kendaraan != null) {
            $this->db->where('opt.id_tipe_kendaraan', $id_tipe_kendaraan);
        }

        return $this->db->update("{$this->table} as opt");
    }

    public function kurang_qty_bill($id_purchase, $id_part, $value, $id_tipe_kendaraan = null)
    {
        $this->db->set('opt.qty_bill', "opt.qty_bill - {$value}", FALSE)
            ->where('opt.id_part', $id_part)
            ->where('opt.po_id', $id_purchase);

        if ($id_tipe_kendaraan != null) {
            $this->db->where('opt.id_tipe_kendaraan', $id_tipe_kendaraan);
        }

        return $this->db->update("{$this->table} as opt");
    }

    public function tambah_qty_ship($id_purchase, $id_part, $value, $id_tipe_kendaraan = null)
    {
        $this->db->set('opt.qty_ship', "opt.qty_ship + {$value}", FALSE)
            ->where('opt.id_part', $id_part)
            ->where('opt.po_id', $id_purchase);

        if ($id_tipe_kendaraan != null) $this->db->where('opt.id_tipe_kendaraan', $id_tipe_kendaraan);

        $result = $this->db->update(sprintf('%s as opt', $this->table));

        if ($result) log_message('info', sprintf('Order part tracking kuantitas shipping untuk purchase order dealer %s dengan kode part %s ditambahkan sebesar %s', $id_purchase, $id_part, $value));

        return $result;
    }

    public function kurang_qty_ship($id_purchase, $id_part, $value, $id_tipe_kendaraan = null)
    {
        $this->db->set('opt.qty_ship', "opt.qty_ship - {$value}", FALSE)
            ->where('opt.id_part', $id_part)
            ->where('opt.po_id', $id_purchase);

        if ($id_tipe_kendaraan != null) {
            $this->db->where('opt.id_tipe_kendaraan', $id_tipe_kendaraan);
        }

        return $this->db->update("{$this->table} as opt");
    }

    public function set_int_relation($id)
    {
        $order_part_tracking = $this->db
            ->from($this->table)
            ->where('id', $id)
            ->limit(1)
            ->get()->row_array();

        if ($order_part_tracking == null) return;

        $part = $this->db
            ->select('id_part_int')
            ->from('ms_part')
            ->where('id_part', $order_part_tracking['id_part'])
            ->limit(1)
            ->get()->row_array();

        if ($part == null) return;

        $purchase_order_dealer = $this->db
            ->select('id')
            ->from('tr_h3_dealer_purchase_order')
            ->where('po_id', $order_part_tracking['po_id'])
            ->limit(1)
            ->get()->row_array();

        if ($purchase_order_dealer == null) return;

        $this->db
            ->set('id_part_int', $part['id_part_int'])
            ->set('po_id_int', $purchase_order_dealer['id'])
            ->where('id', $id)
            ->update($this->table);
    }
}
