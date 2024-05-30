<?php

class h3_dealer_sales_order_model extends Honda_Model
{
    protected $table = 'tr_h3_dealer_sales_order';

    public function __construct()
    {
        parent::__construct();

        $this->load->library('Mcarbon');

        $this->load->model('m_admin');
        $this->load->model('dealer_model', 'dealer');
        $this->load->model('h3_dealer_request_document_model', 'request_document');
    }

    public function insert($data)
    {
        $data['created_at'] = Mcarbon::now()->toDateTimeString();
        $data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
        $id = $this->db->insert_id();

        try {
            if (isset($data['status']) and $data['status'] == 'Processing') {
                $this->create_picking_slip($id);
            }

            if (isset($data['booking_id_reference'])) {
                $this->set_booking_id_reference_int($data['nomor_so']);
            }
        } catch (Exception $e) {
            log_message('error', $e);
        }

        return $id;
    }

    public function update($data, $condition)
    {
        $data['updated_at'] = Mcarbon::now()->toDateTimeString();
        $data['updated_by'] = $this->session->userdata('id_user');
        parent::update($data, $condition);

        try {
            if (isset($data['status']) and $data['status'] == 'Processing') {
                $this->create_picking_slip($condition['id']);
            }
        } catch (Exception $e) {
            log_message('error', $e);
        }
    }

    public function create_picking_slip($id)
    {
        $this->load->model('h3_dealer_picking_slip_model', ' picking_slip');

        try {
            $picking_slip = (array) $this->picking_slip->find($id, 'nomor_so_int');

            if ($picking_slip != null) {
                log_message('debug', sprintf('Picking sudah pernah dibuat dengan nomor picking slip %s', $picking_slip['nomor_ps']));
                return;
            }

            $sales_order = (array) $this->find($id);

            $this->picking_slip->insert([
                'nomor_ps' => $this->picking_slip->generateNomorPickingSlip($sales_order['id_dealer']),
                'nomor_so' => $sales_order['nomor_so'],
                'nomor_so_int' => $id,
                'tanggal_ps' => Mcarbon::now()->toDateString(),
                'id_dealer' => $sales_order['id_dealer'],
            ]);
        } catch (Exception $e) {
            log_message('debug', $e);
        }
    }

    public function set_booking_id_reference_int($nomor_so)
    {
        $sales_order = (array) $this->find($nomor_so, 'nomor_so');
        $request_document = (array) $this->request_document->find($sales_order['booking_id_reference'], 'id_booking');

        $this->db->set('booking_id_reference_int', $request_document['id'])->where('id', $sales_order['id'])->update($this->table);
    }

    public function update_status_po_untuk_sales_order($nomor_so)
    {
        $data = $this->db
            ->select('so.pembelian_dari_dealer_lain')
            ->select('so.booking_id_reference')
            ->select('so.id_dealer as dealer_so')
            ->select('rd.id_dealer as dealer_request_document')
            ->select('po.po_id')
            ->from('tr_h3_dealer_sales_order as so')
            ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = so.booking_id_reference')
            ->join('tr_h3_dealer_purchase_order as po', '(po.id_booking = rd.id_booking and po.status != "Rejected")')
            ->where('so.nomor_so', $nomor_so)
            ->get()->row_array();

        if ($data != null) {
            if (
                // $data['pembelian_dari_dealer_lain'] == 1 
                // AND
                intval($data['dealer_so']) == intval($data['dealer_request_document'])
            ) {
                $this->db
                    ->set('po.penyerahan_customer', 1)
                    // ->set('po.tanggal_selesai', date('Y-m-d', time()))
                    // ->set('po.status', 'Closed')
                    ->where('po.po_id', $data['po_id'])
                    ->update('tr_h3_dealer_purchase_order as po');
            }
        }
    }

    public function generateNomorSO($date = null)
    {
        $th        = date('Y');
        $bln       = date('m');
        if ($date != null) {
            $th_bln    = $date->format('Y-m');
            $thbln     = $date->format('ym');
        } else {
            $th_bln    = date('Y-m');
            $thbln     = date('ym');
        }
        $dealer    = $this->dealer->getCurrentUserDealer();

        $get_data  = $this->db->from($this->table)
            ->where("LEFT(tanggal_so,7)='{$th_bln}'")
            ->where('id_dealer', $this->m_admin->cari_dealer())
            ->order_by('id', 'DESC')
            // ->order_by('created_at', 'DESC')
            // ->order_by('nomor_so', 'DESC')
            ->limit(1)->get();

        if ($get_data->num_rows() > 0) {

            $row        = $get_data->row();

            $nomor_so = substr($row->nomor_so, -5);

            $new_kode   = "SO/{$dealer->kode_dealer_md}/{$thbln}/" . sprintf("%'.05d", $nomor_so + 1);
        } else {

            $new_kode = "SO/{$dealer->kode_dealer_md}/{$thbln}/00001";
        }

        return strtoupper($new_kode);
    }

    public function update_harga($nomor_so)
    {
        $sales_order = $this->db
            ->from(sprintf('%s as so', $this->table))
            ->where('nomor_so', $nomor_so)
            ->limit(1)
            ->get()->row_array();

        if ($sales_order == null) throw new Exception(sprintf('Sales order dealer %s tidak ditemukan', $sales_order['nomor_so']));

        if ($sales_order['status'] == 'Canceled') {
            throw new Exception(sprintf('Harga sales order dealer %s tidak bisa diperbarui karena sudah berstatus canceled', $sales_order['nomor_so']));
        }

        if ($sales_order['status'] == 'Closed') {
            throw new Exception(sprintf('Harga sales order dealer %s tidak bisa diperbarui karena sudah berstatus closed', $sales_order['nomor_so']));
        }

        $this->load->model('h3_dealer_sales_order_parts_model', 'sales_order_parts');
        $this->sales_order_parts->update_harga($sales_order['nomor_so']);
        $this->hitung_total($sales_order['nomor_so']);
    }

    public function hitung_total($nomor_so)
    {
        $sales_order = $this->db
            ->from(sprintf('%s as so', $this->table))
            ->where('nomor_so', $nomor_so)
            ->limit(1)
            ->get()->row_array();

        if ($sales_order == null) throw new Exception(sprintf('Sales order dealer %s tidak ditemukan', $sales_order['nomor_so']));

        if ($sales_order['status'] == 'Canceled') {
            throw new Exception(sprintf('Harga sales order dealer %s tidak bisa diperbarui karena sudah berstatus canceled', $sales_order['nomor_so']));
        }

        if ($sales_order['status'] == 'Closed') {
            throw new Exception(sprintf('Harga sales order dealer %s tidak bisa diperbarui karena sudah berstatus closed', $sales_order['nomor_so']));
        }

        $this->load->model('h3_dealer_sales_order_parts_model', 'sales_order_parts');
        $total = $this->sales_order_parts->total_amount_parts($nomor_so);

        $updated = $this->db
            ->set('total_tanpa_ppn', $total)
            ->where('nomor_so', $nomor_so)
            ->update($this->table);

        log_message('info', sprintf('Total sales order %s berhasil diperbarui dari %s menjadi %s', $nomor_so, $sales_order['total_tanpa_ppn'], $total));

        return $updated;
    }
}
