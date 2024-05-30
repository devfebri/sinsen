<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_claim_program_ireg extends CI_Model
{
    var $table = "tr_claim_dealer";
    var $table2 = "tr_claim_sales_program_detail";
    var $column_order = array(); //field yang ada di table user
    var $column_search = array(); //field yang diizin untuk pencarian 

    var $order = array('idpm' => 'desc'); // default order 

    function __construct()
    {

        parent::__construct();
        $this->load->database();
    }

    private function get_sibp()
    {
        $this->db->select('descJuklak, statusVerifikasi1,statusVerifikasi2,errorMessage,rejectMessage,kode_dealer_md,judul_kegiatan,tipe_ahm,tipe_motor,nama_dealer,memo,judul_kegiatan,tr_scan_barcode.no_rangka,kode_dealer_md,tr_claim_dealer.id_program_md as idpm, month(tr_sales_order.tgl_cetak_invoice) as prd, year(tr_sales_order.tgl_cetak_invoice) as yr, tr_scan_barcode.no_mesin as nms, tr_sales_program.id_program_ahm as asm, tr_claim_sales_program_detail.id_claim_dealer as idclaim');
        $this->db->from('tr_claim_dealer');
        $this->db->join('ms_dealer', 'ms_dealer.id_dealer = tr_claim_dealer.id_dealer');
        $this->db->join('tr_sales_program', 'tr_sales_program.id_program_md = tr_claim_dealer.id_program_md');
        $this->db->join('tr_claim_sales_program', 'tr_claim_sales_program.id_program_md = tr_claim_dealer.id_program_md and tr_claim_sales_program.id_dealer = tr_claim_dealer.id_dealer', 'left');
        $this->db->join('tr_claim_sales_program_detail', 'tr_claim_sales_program_detail.id_claim_dealer = tr_claim_dealer.id_claim');
        $this->db->join('tr_sales_order', 'tr_sales_order.id_sales_order=tr_claim_dealer.id_sales_order');
        $this->db->join('tr_scan_barcode', 'tr_scan_barcode.no_mesin=tr_sales_order.no_mesin');
        $this->db->join('ms_tipe_kendaraan', 'tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan');
        $this->db->join('ms_jenis_sales_program', 'ms_jenis_sales_program.id_jenis_sales_program = tr_sales_program.id_jenis_sales_program');
        $this->db->join('ms_program_subcategory', 'ms_program_subcategory.id_subcategory = ms_jenis_sales_program.id_sub_category');
        $this->db->join('tr_pengajuan_claim_to_ahm', 'tr_claim_dealer.id_claim = tr_pengajuan_claim_to_ahm.id_claim_dealer', 'left');
        $this->db->join('ms_juklak_ahm', 'ms_juklak_ahm.juklakNo = tr_sales_program.id_program_ahm');
        $this->db->where('tr_claim_dealer.status', 'approved');
        $this->db->where('ms_program_subcategory.claim_to_ahm', '1');
        $this->db->where('tr_claim_sales_program_detail.is_irregular_case', '1');
        $this->db->order_by('tr_claim_sales_program_detail.memo', 'DESC');
    }


    function get_datatables($nojuk, $idsales, $year, $mon, $dcode, $owner, $nosin)
    {
        $bulan = (int)$mon;
        if ($bulan == 0) {
            $blm = '';
        }
        $le = $this->input->post('length');
        $st = $this->input->post('start');
        $this->get_sibp();
        if ($le != -1)

            $this->db->like('tr_sales_program.id_program_ahm', $nojuk);
        $this->db->like('tr_claim_dealer.id_program_md', $idsales);
        $this->db->like('year(tr_sales_program.periode_awal)', $year);
        $this->db->like('month(tr_sales_program.periode_awal)', $mon);
        $this->db->like('ms_dealer.kode_dealer_md', $dcode);
        $this->db->like('tr_sales_program.jenis', $owner);
        $this->db->like('tr_scan_barcode.no_mesin', $nosin);
        // if ($reason != '') {
        //     $this->db->like('tr_claim_sales_program_detail.alasan', $reason);
        // }
        // if ($memo != '') {
        //     $this->db->like('tr_claim_sales_program_detail.memo', $memo);
        // }
        $this->db->order_by('tr_claim_dealer.tgl_approve_reject_md', 'DESC');
        $this->db->limit($le, $st);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->get_sibp();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_datamemo()
    {

        $le = $this->input->post('length');
        $st = $this->input->post('start');
        $this->datamemo();
        if ($le != -1)


            $this->db->limit($le, $st);
        $query = $this->db->get();
        return $query->result();
    }

    public function datamemo()
    {

        $this->db->select('judul_kegiatan,tr_claim_dealer.id_program_md as idpm, month(tr_sales_program.periode_awal) as prd, year(tr_sales_program.periode_awal) as yr, tr_scan_barcode.no_mesin as nms, tr_sales_program.id_program_ahm as asm, tr_claim_sales_program_detail.id_claim_dealer as idclaim');
        $this->db->from('tr_claim_dealer');
        $this->db->join('ms_dealer', 'ms_dealer.id_dealer = tr_claim_dealer.id_dealer');
        $this->db->join('tr_sales_program', 'tr_sales_program.id_program_md = tr_claim_dealer.id_program_md');
        $this->db->join('tr_claim_sales_program', 'tr_claim_sales_program.id_program_md = tr_claim_dealer.id_program_md and tr_claim_sales_program.id_dealer = tr_claim_dealer.id_dealer', 'left');
        $this->db->join('tr_claim_sales_program_detail', 'tr_claim_sales_program_detail.id_claim_dealer = tr_claim_dealer.id_claim');
        $this->db->join('tr_sales_order', 'tr_sales_order.id_sales_order=tr_claim_dealer.id_sales_order');
        $this->db->join('tr_scan_barcode', 'tr_scan_barcode.no_mesin=tr_sales_order.no_mesin');
        $this->db->join('ms_tipe_kendaraan', 'tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan');
        $this->db->join('ms_jenis_sales_program', 'ms_jenis_sales_program.id_jenis_sales_program = tr_sales_program.id_jenis_sales_program');
        $this->db->join('ms_program_subcategory', 'ms_program_subcategory.id_subcategory = ms_jenis_sales_program.id_sub_category');


        $this->db->where('tr_claim_sales_program_detail.is_irregular_case', 1);
        $this->db->where('tr_claim_sales_program_detail.memo', '');
        $this->db->or_where('tr_claim_sales_program_detail.memo', null);
    }
    public function get_datamemo2()
    {
        $this->db->select('nama_dealer,tipe_motor,tipe_ahm,judul_kegiatan,tr_scan_barcode.no_rangka,ms_dealer.kode_dealer_md,tr_claim_dealer.id_program_md as idpm, month(tr_sales_program.periode_awal) as prd, year(tr_sales_program.periode_awal) as yr, tr_scan_barcode.no_mesin as nms, tr_sales_program.id_program_ahm as asm, tr_claim_sales_program_detail.id_claim_dealer as idclaim');
        $this->db->from('tr_claim_dealer');
        $this->db->join('ms_dealer', 'ms_dealer.id_dealer = tr_claim_dealer.id_dealer');
        $this->db->join('tr_sales_program', 'tr_sales_program.id_program_md = tr_claim_dealer.id_program_md');
        $this->db->join('tr_claim_sales_program', 'tr_claim_sales_program.id_program_md = tr_claim_dealer.id_program_md and tr_claim_sales_program.id_dealer = tr_claim_dealer.id_dealer', 'left');
        $this->db->join('tr_claim_sales_program_detail', 'tr_claim_sales_program_detail.id_claim_dealer = tr_claim_dealer.id_claim');
        $this->db->join('tr_sales_order', 'tr_sales_order.id_sales_order=tr_claim_dealer.id_sales_order');
        $this->db->join('tr_scan_barcode', 'tr_scan_barcode.no_mesin=tr_sales_order.no_mesin');
        $this->db->join('ms_tipe_kendaraan', 'tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan');
        $this->db->join('ms_jenis_sales_program', 'ms_jenis_sales_program.id_jenis_sales_program = tr_sales_program.id_jenis_sales_program');
        $this->db->join('ms_program_subcategory', 'ms_program_subcategory.id_subcategory = ms_jenis_sales_program.id_sub_category');


        $this->db->where('tr_claim_sales_program_detail.is_irregular_case', 1);
        $this->db->where("tr_claim_sales_program_detail.memo is null or tr_claim_sales_program_detail.memo =''");
        // $this->db->or_where('tr_claim_sales_program_detail.memo', null);
        $this->db->limit(10);

        return $this->db->get()->result_array();
    }

    function count_filtered2()
    {
        $this->datamemo();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all2()
    {
        $this->db->from($this->table2);
        return $this->db->count_all_results();
    }

    public function get_update_memo($centang, $jmldata, $reason, $memo2)
    {

        return true;
    }

    public function data_export()
    {

        $this->db->select("tr_sales_order.tgl_po_leasing as tglpo,no_ktp,ms_finance_company.id_finance_company,finance_company,tgl_bastk,nama_konsumen,tr_spk.alamat,kabupaten,alasan_reject,tgl_approve_reject_md,jenis_beli,ms_warna.id_warna,tipe_ahm,tipe_motor,tr_scan_barcode.no_rangka,no_po_leasing,tgl_cetak_invoice,no_invoice,kode_dealer_md,nama_dealer,id_program_ahm,tr_claim_dealer.id_program_md,tr_claim_dealer.id_program_md as idpm, month(tr_sales_program.periode_awal) as prd, year(tr_sales_program.periode_awal) as yr, tr_scan_barcode.no_mesin as nms, tr_sales_program.id_program_ahm as asm, tr_claim_sales_program_detail.id_claim_dealer as idclaim, ms_warna.warna as wr, tr_claim_dealer.created_at as ctr, tr_claim_dealer.status as stss, voucher_2,month(tr_sales_program.periode_awal) as prd, year(tr_sales_program.periode_awal) as yr,
        (case when tr_spk.jenis_beli = 'Kredit' then tr_spk.voucher_2 else tr_spk.voucher_1 end) as total_diskon");
        $this->db->from('tr_claim_dealer');
        $this->db->join('ms_dealer', 'ms_dealer.id_dealer = tr_claim_dealer.id_dealer');
        $this->db->join('tr_sales_program', 'tr_sales_program.id_program_md = tr_claim_dealer.id_program_md');
        $this->db->join('tr_claim_sales_program', 'tr_claim_sales_program.id_program_md = tr_claim_dealer.id_program_md and tr_claim_sales_program.id_dealer = tr_claim_dealer.id_dealer', 'left');
        $this->db->join('tr_claim_sales_program_detail', 'tr_claim_sales_program_detail.id_claim_dealer = tr_claim_dealer.id_claim');
        $this->db->join('tr_sales_order', 'tr_sales_order.id_sales_order=tr_claim_dealer.id_sales_order');
        $this->db->join('tr_scan_barcode', 'tr_scan_barcode.no_mesin=tr_sales_order.no_mesin');
        $this->db->join('ms_tipe_kendaraan', 'tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan');
        $this->db->join('ms_jenis_sales_program', 'ms_jenis_sales_program.id_jenis_sales_program = tr_sales_program.id_jenis_sales_program');
        $this->db->join('ms_program_subcategory', 'ms_program_subcategory.id_subcategory = ms_jenis_sales_program.id_sub_category');
        $this->db->join('ms_warna', 'ms_warna.id_warna=tr_scan_barcode.warna');
        $this->db->join('tr_spk', 'tr_spk.no_spk = tr_sales_order.no_spk');
        $this->db->join('ms_finance_company', 'tr_spk.id_finance_company=ms_finance_company.id_finance_company', 'left');
        $this->db->join('ms_kabupaten', 'tr_spk.id_kabupaten = ms_kabupaten.id_kabupaten', 'left');

        $this->db->where('tr_claim_sales_program_detail.is_irregular_case', 1);
    }

    public function get_export_row($id, $mesin, $claim)
    {


        $this->data_export();
        $this->db->where('tr_claim_dealer.id_program_md', $id);
        $this->db->where('tr_scan_barcode.no_mesin', $mesin);
        $this->db->where('tr_claim_sales_program_detail.id_claim_dealer', $claim);
        return $this->db->get()->row_array();
    }
    public function get_export_array($memo)
    {
        $this->data_export();

        $this->db->where('tr_claim_sales_program_detail.memo', $memo);
        return $this->db->get()->result_array();
    }

    public function get_group_memo()
    {
        $this->db->select('*, count(memo) as mm');
        $this->db->from('tr_claim_sales_program_detail');
        $this->db->group_by('memo');
        $this->db->group_by('alasan');
        $this->db->where_not_in('memo', null);
        $this->db->where_not_in('memo', "");

        return $this->db->get()->result_array();
    }
}
