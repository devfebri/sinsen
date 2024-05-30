<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_claim_program_ahm extends CI_Model
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

    public function get_sibp()
    {
        $this->db->select('descJuklak, statusVerifikasi1,statusVerifikasi2,errorMessage,rejectMessage,tr_claim_sales_program_detail.send_ahm,id_claim,judul_kegiatan,tr_scan_barcode.no_rangka,tgl_approve_reject_md,kode_dealer_md,nama_dealer,tipe_motor,tipe_ahm,tr_claim_dealer.id_program_md as idpm, month(tr_sales_order.tgl_cetak_invoice) as prd, year(tr_sales_order.tgl_cetak_invoice) as yr, tr_scan_barcode.no_mesin as nos, tr_sales_program.id_program_ahm as asm, tr_claim_sales_program_detail.id_claim_dealer as idclaim');
        $this->db->from('tr_claim_dealer');
        $this->db->join('ms_dealer', 'ms_dealer.id_dealer = tr_claim_dealer.id_dealer');
        $this->db->join('tr_sales_program', 'tr_sales_program.id_program_md = tr_claim_dealer.id_program_md');
        $this->db->join('tr_claim_sales_program', 'tr_claim_sales_program.id_program_md = tr_claim_dealer.id_program_md and tr_claim_sales_program.id_dealer = tr_claim_dealer.id_dealer', 'left');
        $this->db->join('tr_sales_order', 'tr_sales_order.id_sales_order=tr_claim_dealer.id_sales_order');
        $this->db->join('tr_scan_barcode', 'tr_scan_barcode.no_mesin=tr_sales_order.no_mesin');
        $this->db->join('ms_tipe_kendaraan', 'tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan');
        $this->db->join('ms_jenis_sales_program', 'ms_jenis_sales_program.id_jenis_sales_program = tr_sales_program.id_jenis_sales_program');
        $this->db->join('ms_program_subcategory', 'ms_program_subcategory.id_subcategory = ms_jenis_sales_program.id_sub_category');
        $this->db->join('tr_claim_sales_program_detail', 'tr_claim_sales_program_detail.id_claim_dealer = tr_claim_dealer.id_claim');
        $this->db->join('tr_pengajuan_claim_to_ahm', 'tr_claim_dealer.id_claim = tr_pengajuan_claim_to_ahm.id_claim_dealer', 'left');
        $this->db->join('ms_juklak_ahm', 'ms_juklak_ahm.juklakNo = tr_sales_program.id_program_ahm');
        $this->db->where('tr_claim_dealer.status', 'approved');
        $this->db->where("tr_sales_program.id_program_ahm !=''");
        $this->db->where('ms_program_subcategory.claim_to_ahm', '1');
        $this->db->where('tr_claim_sales_program_detail.is_irregular_case', null);
    }
    function get_datatables($noj, $idsales, $year, $mon, $dcode, $owner, $nosin, $send)
    {
        $blm = (int)$mon;
        if ($blm == 0) {
            $blm = '';
        }
        //$thn = (int)$year;
        $le = $this->input->post('length');
        $st = $this->input->post('start');
        $this->get_sibp();

        if ($le != -1)

            $this->db->like('tr_sales_program.id_program_ahm', $noj);
            $this->db->like('tr_claim_dealer.id_program_md', $idsales);
            $this->db->like('year(tr_sales_program.periode_awal)', $year);
            $this->db->like('month(tr_sales_program.periode_awal)', "$blm");
            $this->db->like('ms_dealer.kode_dealer_md', $dcode);
            $this->db->like('tr_sales_program.jenis', $owner);
            $this->db->like('tr_scan_barcode.no_mesin', $nosin);
        if ($send == 1) {
            $this->db->where('tr_claim_sales_program_detail.send_ahm', 1);
        } else if ($send == 2) {
            $this->db->where('tr_claim_sales_program_detail.send_ahm', null);
        } 

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
}
