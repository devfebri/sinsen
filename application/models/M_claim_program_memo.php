<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_claim_program_memo extends CI_Model
{
    var $table = "tr_claim_dealer";

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


        $this->db->select('*, tr_claim_dealer.id_program_md as idpm, month(tr_sales_order.tgl_cetak_invoice) as prd, year(tr_sales_order.tgl_cetak_invoice) as yr, tr_scan_barcode.no_mesin as nms, tr_sales_program.id_program_ahm as asm, tr_claim_sales_program_detail.id_claim_dealer as idclaim');
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
        $this->db->where('tr_claim_dealer.status', 'approved');
        $this->db->where('ms_program_subcategory.claim_to_ahm', '1');
        $this->db->where('tr_claim_sales_program_detail.is_irregular_case', '1');
    }


    function get_datatables()
    {

        $le = $this->input->post('length');
        $st = $this->input->post('start');
        $this->get_sibp();
        if ($le != -1)


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
}
