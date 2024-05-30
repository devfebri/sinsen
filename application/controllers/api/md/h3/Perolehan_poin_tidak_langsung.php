<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Perolehan_poin_tidak_langsung extends CI_Controller
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

        $dealer_tidak_terdiskualifikasi = array_column($this->db->get()->result_array(), 'id_dealer');

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

            $query = $this->db
            ->select('SUM(perolehan_perbulan.total_poin_penjualan_per_bulan) as total_poin_penjualan_per_bulan', false)
            ->from('tr_h3_md_perolehan_sales_campaign_poin_tl_perbulan as perolehan_perbulan')
            ->where('perolehan_perbulan.bulan', $month_iteration->format('m'))
            ->where('perolehan_perbulan.tahun', $month_iteration->format('Y'))
            ->where('perolehan_perbulan.id_perolehan = perolehan.id', null, false)
            ->get_compiled_select();

            $perolehan_perbulan[
                'month_' . $month_iteration->format('mY')
            ] = $query;
        }

        $sales_campaign_hadiah = $this->db
		->from('ms_h3_md_sales_campaign_detail_hadiah as scdh')
		->where('scdh.id_campaign', $this->input->post('id_campaign'));

        $perolehan_hadiah = [];
        foreach ($this->db->get()->result_array() as $row) {
            $query = $this->db
            ->select('SUM(perolehan_hadiah.count_hadiah) as count_hadiah', false)
            ->from('tr_h3_md_perolehan_sales_campaign_poin_tl_hadiah as perolehan_hadiah')
            ->where('perolehan_hadiah.id_hadiah', $row['id'])
            ->where('perolehan_hadiah.id_perolehan = perolehan.id', null, false)
            ->get_compiled_select();

            $perolehan_hadiah[
                'hadiah_' . $row['id']
            ] = $query;
        }

        $perolehan_perbulan = [];
        for ($add_month = 0; $add_month < $perbedaan_bulan; $add_month++) { 
            $month_iteration = $start_date->copy()->addMonths($add_month);

            $query = $this->db
            ->select('SUM(perolehan_perbulan.total_poin_penjualan_per_bulan) as total_poin_penjualan_per_bulan', false)
            ->from('tr_h3_md_perolehan_sales_campaign_poin_tl_perbulan as perolehan_perbulan')
            ->where('perolehan_perbulan.bulan', $month_iteration->format('m'))
            ->where('perolehan_perbulan.tahun', $month_iteration->format('Y'))
            ->where('perolehan_perbulan.id_perolehan = perolehan.id', null, false)
            ->get_compiled_select();

            $perolehan_perbulan[
                'month_' . $month_iteration->format('mY')
            ] = $query;
        }

        foreach ($perolehan_perbulan as $key => $query) {
            $this->db->select("IFNULL( ({$query}) , 0) AS {$key}", false);
        }

        foreach ($perolehan_hadiah as $key => $query) {
            $this->db->select("IFNULL( ({$query}) , 0) AS {$key}", false);
        }

        $this->db
        ->select('perolehan.id')
        ->select('d.nama_dealer')
        ->select('d.kode_dealer_md')
        ->select('perolehan.total_poin_penjualan_per_dealer')
        ->select('perolehan.sisa_poin')
        ->select('perolehan.total_insentif')
        ->select('perolehan.ppn')
        ->select('perolehan.nilai_kw')
        ->select('perolehan.pph_23')
        ->select('perolehan.pph_21')
        ->select('perolehan.total_bayar')
        ->select('d.nama_bank_h3 as nama_bank')
        ->select('d.atas_nama_bank_h3 as atas_nama')
        ->select('d.no_rekening_h3 as no_rekening')
        ->from('tr_h3_md_perolehan_sales_campaign_poin_tidak_langsung as perolehan')
        ->join('ms_dealer as d', 'd.id_dealer = perolehan.id_dealer')
        ->where('perolehan.id_campaign', $this->input->post('id_campaign'))
        ;

        if(count($dealer_tidak_terdiskualifikasi) > 0){
            $this->db->where_in('perolehan.id_dealer', $dealer_tidak_terdiskualifikasi);
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
