<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi_voucher_pengeluaran extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_transaksi_voucher_pengeluaran', [
                'json' => json_encode($row),
                'data' => $row,
            ], true);

            $row['index'] = $this->input->post('start') + $index;

            $data[] = $row;
            $index++;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_total_data(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $nominal_ap_dibuatkan_voucher = $this->db
        ->select('SUM(vpi.nominal) as nominal', false)
        ->from('tr_h3_md_voucher_pengeluaran_items as vpi')
        ->join('tr_h3_md_voucher_pengeluaran as vp', 'vp.id_voucher_pengeluaran = vpi.id_voucher_pengeluaran')
        ->where('vpi.id_referensi = ap.id', null, false)
        ->where('vp.status', 'Open')
        ->get_compiled_select();

        $this->db
        ->select('ap.nomor_account')
        ->select('coa.coa as nama_coa')
        ->select('ap.id as id_referensi')
        ->select('ap.referensi')
        ->select('ap.jenis_transaksi')
        ->select('date_format(ap.tanggal_transaksi, "%d/%m/%Y") as tanggal_transaksi')
        ->select('date_format(ap.tanggal_jatuh_tempo, "%d/%m/%Y") as tanggal_jatuh_tempo')
        ->select('ap.nama_vendor')
        ->select("( (ap.total_bayar - ap.total_sudah_dibayar) - IFNULL(({$nominal_ap_dibuatkan_voucher}), 0) ) as jumlah_terutang", false)
        ->select('0 as nominal')
        ->select('"" as keterangan')
        ->from('tr_h3_md_ap_part as ap')
        ->join('ms_coa as coa', 'coa.kode_coa = ap.nomor_account', 'left')
        ->where('
            case
                when ap.jenis_transaksi = "invoice_ahm" then ap.id_rekap_invoice_ahm is null
                else true
            end
        ', null, false)
        ->having('jumlah_terutang >', 0)
        ;

        $tipe_penerima = $this->input->post('tipe_penerima');
        if($tipe_penerima == 'Lain-lain'){
            $this->db->group_start();
            $this->db->where('ap.jenis_transaksi', 'rekap_perolehan_insentif_poin_sales_campaign');
            $this->db->or_where('ap.jenis_transaksi', 'perolehan_insentif_poin_sales_campaign');
            $this->db->or_where('ap.jenis_transaksi', 'rekap_perolehan_insentif_cashback_sales_campaign');
            $this->db->group_end();
        }else{
            $this->db->where('ap.id_referensi_table', $this->input->post('id_dibayarkan_kepada'));
        }
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ap.referensi', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ap.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function get_filtered_data() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function get_total_data() {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
