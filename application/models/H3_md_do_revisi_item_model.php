<?php

class H3_md_do_revisi_item_model extends Honda_Model
{
    protected $table = 'tr_h3_md_do_revisi_item';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('H3_md_do_revisi_model', 'do_revisi');
    }

    public function get_parts($id_revisi)
    {
        $do_revisi = $this->do_revisi->get_data($id_revisi);

        $this->db
            ->select('dri.id')
            ->select('dsop.id_part')
            ->select('p.nama_part')
            ->select('p.qty_dus')
            ->select('so.produk')
            ->select('so.id_dealer')
            ->select('so.po_type')
            ->select('dsop.harga_jual')
            ->select('dri.tipe_diskon_campaign')
            ->select('dri.diskon_campaign')
            ->select('dri.tipe_diskon_satuan_dealer')
            ->select('dri.diskon_satuan_dealer')
            ->select('dri.qty_do')
            ->select("dri.qty_revisi")
            ->select("dri.qty_revisi as kuantitas")
            ->select('(sc.jenis_diskon_campaign = "Additional") as additional_discount')
            ->from('tr_h3_md_do_revisi_item as dri')
            ->join('tr_h3_md_do_revisi as dr', 'dr.id = dri.id_revisi')
            ->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = dr.id_do_sales_order')
            ->join('tr_h3_md_picking_list as pl', 'pl.id_ref = dso.id_do_sales_order')
            ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
            ->join('tr_h3_dealer_purchase_order as po', 'so.id_ref = po.po_id')
            ->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
            ->join('ms_h3_md_sales_campaign as sc', '(sc.id = dri.id_campaign_diskon AND sc.jenis_reward_diskon = 1)', 'left')
            ->where('dri.id_revisi', $id_revisi)
            ->where('dri.qty_do >', 0)
            ->order_by('dri.id_part', 'asc');

        if ($do_revisi['kategori_po'] == 'KPB') {
            $this->db->select('dri.id_tipe_kendaraan');
            $this->db->join('tr_h3_md_do_sales_order_parts as dsop', '(dsop.id_part = dri.id_part and dsop.id_do_sales_order = dr.id_do_sales_order and dsop.id_tipe_kendaraan = dri.id_tipe_kendaraan)');
            $this->db->join('tr_h3_md_sales_order_parts as sop', '(sop.id_sales_order = dso.id_sales_order AND sop.id_part = dsop.id_part and sop.id_tipe_kendaraan = dsop.id_tipe_kendaraan)');
        } else {
            $this->db->join('tr_h3_md_do_sales_order_parts as dsop', '(dsop.id_part = dri.id_part and dsop.id_do_sales_order = dr.id_do_sales_order)');
            $this->db->join('tr_h3_md_sales_order_parts as sop', '(sop.id_sales_order = dso.id_sales_order AND sop.id_part = dsop.id_part)');
        }

        $this->db
            ->join('ms_part as p', 'p.id_part = dsop.id_part')
            ->join('ms_kelompok_part as kp', 'p.kelompok_part = kp.id_kelompok_part', 'left');

        $parts = $this->db->get()->result_array();
        $parts = array_map(function ($data) {
            $data['qty_selisih'] = abs($data['qty_do'] - $data['qty_revisi']);
            $data['harga_setelah_diskon'] = harga_setelah_diskon($data['tipe_diskon_satuan_dealer'], $data['diskon_satuan_dealer'], $data['harga_jual'], $data['additional_discount'] == 1, $data['tipe_diskon_campaign'], $data['diskon_campaign']);
            $data['amount'] = $data['harga_setelah_diskon'] * $data['qty_revisi'];
            unset($data['qty_dus']);

            return $data;
        }, $parts);

        return $parts;
    }

    public function hitung_harga_setelah_diskon($id){
        $this->load->helper('harga_setelah_diskon');
        $this->load->helper('rupiah_format');

        $data = $this->db
        ->select('dri.id_part')
        ->select('dop.harga_jual')
        ->select('dri.tipe_diskon_satuan_dealer')
        ->select('dri.diskon_satuan_dealer')
        ->select('dri.tipe_diskon_campaign')
        ->select('dri.diskon_campaign')
        ->select('dri.id_campaign_diskon')
        ->select('(sc.jenis_diskon_campaign = "Additional") as additional_discount')
        ->from('tr_h3_md_do_revisi_item as dri')
        ->join('tr_h3_md_do_revisi as dr', 'dr.id = dri.id_revisi')
        ->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = dr.id_do_sales_order and dop.id_part = dri.id_part)')
        ->join('ms_h3_md_sales_campaign as sc', '(sc.id = dri.id_campaign_diskon AND sc.jenis_reward_diskon = 1)', 'left')
        ->where('dri.id', $id)
        ->get()->row_array();

        if($data == null) throw new Exception(sprintf('Tidak menemukan data delivery order item [%s]', $id));

        $sales_campaign = $this->db
        ->select('sc.jenis_diskon_campaign')
        ->from('ms_h3_md_sales_campaign as sc')
        ->where('sc.jenis_reward_diskon', 1)
        ->where('sc.id', $id)
        ->get()->row_array();

        $harga_setelah_diskon = harga_setelah_diskon($data['tipe_diskon_satuan_dealer'], $data['diskon_satuan_dealer'], $data['harga_jual'], $data['additional_discount'] == 1, $data['tipe_diskon_campaign'], $data['diskon_campaign']);

        $this->db
        ->set('dri.harga_setelah_diskon', $harga_setelah_diskon)
        ->where('dri.id', $id)
        ->update('tr_h3_md_do_revisi_item as dri');

        log_message('debug', sprintf('Set harga setelah diskon delivery order item [%s] dengan nominal %s', $id, rupiah_format($harga_setelah_diskon)));
    }
}
