<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Perolehan_cashback_tidak_langsung_global extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->library('Mcarbon');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $data[] = $row;
            $index++;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('scd.id_dealer')
        ->from('ms_h3_md_sales_campaign_dealers as scd')
        ->where('scd.diskualifikasi', 0)
        ->where('scd.id_campaign', $this->input->post('id_campaign'));

        $dealers = array_map(function($row){
            return $row['id_dealer'];
        }, $this->db->get()->result_array());

        $sales_campaign = $this->db
		->select('
			CASE
				WHEN sc.start_date_gimmick IS NOT NULL THEN sc.start_date_gimmick
				ELSE sc.start_date
			END AS start_date
		', false)
		->select('
			CASE
				WHEN sc.end_date_gimmick IS NOT NULL THEN sc.end_date_gimmick
				ELSE sc.end_date
			END AS end_date
		', false)
        ->select('sc.satuan_rekapan_cashback')
		->from('ms_h3_md_sales_campaign as sc')
		->where('sc.id', $this->input->post('id_campaign'))
		->get()->row_array();

        $start_date = Mcarbon::parse($sales_campaign['start_date']);
        $start_date_month = $start_date->copy()->startOfMonth();
        $end_date = Mcarbon::parse($sales_campaign['end_date']);
        $end_date_month = $end_date->copy()->startOfMonth();

        $perbedaan_bulan = $start_date_month->diffInMonths($end_date_month) + 1;

        $perolehan_perbulan = [];
        for ($add_month = 0; $add_month < $perbedaan_bulan; $add_month++) { 
            $month_iteration = $start_date->copy()->addMonths($add_month);

            $this->db
            ->from('tr_h3_perolehan_sales_campaign_cashback_tl_perbulan as perolehan_perbulan')
            ->where('perolehan_perbulan.bulan', $month_iteration->format('m'))
            ->where('perolehan_perbulan.tahun', $month_iteration->format('Y'))
            ->where('perolehan_perbulan.id_perolehan = perolehan.id', null, false);

            if($sales_campaign['satuan_rekapan_cashback'] == 'Dus'){
                $this->db->select('SUM(perolehan_perbulan.total_dus_penjualan_per_bulan) as total_poin_penjualan_per_bulan', false);
            }else{
                $this->db->select('SUM(perolehan_perbulan.total_penjualan_per_bulan) as total_poin_penjualan_per_bulan', false);
            }

            $query = $this->db->get_compiled_select();

            $perolehan_perbulan[
                'month_' . $month_iteration->format('mY')
            ] = $query;
        }

        $sales_campaign_global = $this->db
		->from('ms_h3_md_sales_campaign_detail_cashback_global as scdg')
		->where('scdg.id_campaign', $this->input->post('id_campaign'));

        $perolehan_global = [];
        foreach ($this->db->get()->result_array() as $row) {
            $query = $this->db
            ->select('SUM(perolehan_global.count_cashback) as count_cashback', false)
            ->from('tr_h3_perolehan_sales_campaign_cashback_tl_global as perolehan_global')
            ->where('perolehan_global.id_global', $row['id'])
            ->where('perolehan_global.id_perolehan = perolehan.id', null, false)
            ->get_compiled_select();

            $perolehan_global[
                'global_' . $row['id']
            ] = $query;
        }

        $perolehan_perbulan = [];
        for ($add_month = 0; $add_month < $perbedaan_bulan; $add_month++) { 
            $month_iteration = $start_date->copy()->addMonths($add_month);

            $this->db
            ->from('tr_h3_perolehan_sales_campaign_cashback_tl_perbulan as perolehan_perbulan')
            ->where('perolehan_perbulan.bulan', $month_iteration->format('m'))
            ->where('perolehan_perbulan.tahun', $month_iteration->format('Y'))
            ->where('perolehan_perbulan.id_perolehan = perolehan.id', null, false);

            if($sales_campaign['satuan_rekapan_cashback'] == 'Dus'){
                $this->db->select('SUM(perolehan_perbulan.total_dus_penjualan_per_bulan) as total_poin_penjualan_per_bulan', false);
            }else{
                $this->db->select('SUM(perolehan_perbulan.total_penjualan_per_bulan) as total_poin_penjualan_per_bulan', false);
            }

            $query = $this->db->get_compiled_select();

            $perolehan_perbulan[
                'month_' . $month_iteration->format('mY')
            ] = $query;
        }

        foreach ($perolehan_perbulan as $key => $query) {
            $this->db->select("IFNULL( ({$query}) , 0) AS {$key}", false);
        }

        foreach ($perolehan_global as $key => $query) {
            $this->db->select("IFNULL( ({$query}) , 0) AS {$key}", false);
        }

        $this->db
        ->select('perolehan.id')
        ->select('d.nama_dealer')
        ->select('d.kode_dealer_md')
        ->select('perolehan.total_insentif')
        ->select('perolehan.ppn')
        ->select('perolehan.nilai_kw')
        ->select('perolehan.pph_23')
        ->select('perolehan.pph_21')
        ->select('perolehan.total_bayar')
        ->select('d.nama_bank_h3 as nama_bank')
        ->select('d.atas_nama_bank_h3 as atas_nama')
        ->select('d.no_rekening_h3 as no_rekening')
        ->from('tr_h3_perolehan_sales_campaign_cashback_tidak_langsung as perolehan')
        ->join('ms_dealer as d', 'd.id_dealer = perolehan.id_dealer')
        ->where('perolehan.id_campaign', $this->input->post('id_campaign'))
        ;

        if(count($dealers) > 0){
            $this->db->where_in('perolehan.id_dealer', $dealers);
        }

        if($sales_campaign['satuan_rekapan_cashback'] == 'Dus'){
            $this->db->select('perolehan.total_dus_penjualan_per_dealer as total_penjualan');
            $this->db->select('perolehan.sisa_total_dus_penjualan_per_dealer as sisa_poin');
        }else{
            $this->db->select('perolehan.total_penjualan_per_dealer as total_penjualan');
            $this->db->select('perolehan.sisa_total_penjualan_per_dealer as sisa_poin');
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('d.nama_dealer', $search);
            $this->db->or_like('d.kode_dealer_md', $search);
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('d.nama_dealer', 'asc');
        }

    }

    public function limit(){
        if ($this->input->post('length') != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
