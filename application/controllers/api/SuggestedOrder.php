<?php


defined('BASEPATH') or exit('No direct script access allowed');

class SuggestedOrder extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('ms_part_model', 'part');
        $this->load->model('h3_dealer_sales_order_model', 'sales_order');
        $this->load->model('h3_dealer_sales_order_parts_model', 'sales_order_parts');
    }

    public function index(){
        $this->load->model('H3_md_ms_sim_part_model', 'sim_part');
        $this->load->model('h3_dealer_stock_model', 'stock');

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        $order_md = $this->db
        ->select('sum(pop.kuantitas)')
        ->from('tr_h3_dealer_purchase_order_parts as pop')
        ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = pop.po_id')
        ->where('pop.id_part = mp.id_part')
        ->where('po.status', 'Processed by MD')
        ->where('po.kategori_po !=', 'KPB')
        ->get_compiled_select();

        $stock = $this->stock->qty_on_hand($this->m_admin->cari_dealer(), 'mp.id_part', null, null, true);
        $sim_part = $this->sim_part->qty_sim_part($this->m_admin->cari_dealer(), 'mp.id_part_int', true);

        $this->db
        ->select('ar.*')
        ->select('mp.harga_dealer_user as harga_saat_dibeli')
        ->select('mp.nama_part')
        ->select('mp.kelompok_part')
        ->select('ar.suggested_order as kuantitas')
        ->select('IFNULL(dmp.maks_stok, 0) as maks_stok')
        ->select("IFNULL(({$sim_part}), 0) as sim_part")
        ->select("IFNULL(({$stock}), 0) as stock")
        ->select("IFNULL(({$order_md}), 0) as order_md")
        ->select("0 as diskon_value")
        ->select("'' as tipe_diskon")
        ->select("0 as diskon_value_campaign")
        ->select("'' as tipe_diskon_campaign")
        ->select('ar.adjusted_order as kuantitas')
        ->from('ms_h3_analisis_ranking as ar')
        ->join('ms_part as mp', 'mp.id_part = ar.id_part')
        ->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part = mp.kelompok_part')
        ->join('ms_h3_dealer_master_part as dmp', 'dmp.id_part = ar.id_part and dmp.id_dealer = ar.id_dealer', 'left')
        ->where('ar.id_dealer', $this->m_admin->cari_dealer())
        ->where('mp.fix', 1)
        ->group_start()
        ->where('ar.suggested_order >', 0)
        ->or_where('ar.adjusted_order >', 0)
        ->group_end()
        ->order_by('ar.suggested_order', 'desc')
        ;

        if($this->input->get('kategori_po') != null and $this->input->get('kategori_po') == 'SIM Part'){
            $this->db->where('mp.sim_part', 1);
        }else if($this->input->get('kategori_po') != null and $this->input->get('kategori_po') == 'Non SIM Part'){
            $this->db->where('mp.sim_part', 0);
        }

        $this->db->where('skp.produk', $this->input->get('produk'));

        send_json(
            $this->db->get()->result_array()
        );
    }

}
