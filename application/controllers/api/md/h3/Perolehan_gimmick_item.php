<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Perolehan_gimmick_item extends CI_Controller
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
            if($row['no_faktur'] == null){
                $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_perolehan_sales_campaign_peritem', [
                    'id' => $row['id'],
                    'total_perolehan_hadiah' => $row['total_perolehan_hadiah'],
                    'akhir_periode' => $row['akhir_periode'],
                    'sudah_create_so' => $row['sudah_create_so'],
                ], true);
            }else{
                $row['action'] = sprintf('%s <br> %s', $row['no_faktur'], Mcarbon::parse($row['tgl_faktur'])->format('d/m/Y H:i'));
            }
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

        $sales_campaign_details = $this->db
        ->select('sc_detail.*')
        ->select('sc.jenis_item_gimmick')
        ->select('sc.satuan_rekapan_gimmick')
        ->from('ms_h3_md_sales_campaign_detail_gimmick as sc_detail')
		->join('ms_h3_md_sales_campaign as sc', 'sc.id = sc_detail.id_campaign')
        ->where('sc_detail.id_campaign', $this->input->post('id_campaign'))
        ->get()->result_array();

        $list_query_perolehan = [];
        $list_query_sisa = [];
        foreach ($sales_campaign_details as $sales_campaign_detail) {
            $this->db
            ->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_details as perolehan_detail')
            ->where('perolehan_detail.id_campaign', $this->input->post('id_campaign'))
            ->where('perolehan_detail.id_detail', $sales_campaign_detail['id'])
            ->where('perolehan_detail.id_perolehan = perolehan.id', null, false);

            if($sales_campaign_detail['satuan_rekapan_gimmick'] == 'Satuan'){
                $this->db->select('perolehan_detail.jumlah_kuantitas_yang_tercapai');
            }else if($sales_campaign_detail['satuan_rekapan_gimmick'] == 'Dus'){
                $this->db->select('perolehan_detail.jumlah_dus_yang_tercapai');
            }

            $query_perolehan = $this->db->get_compiled_select();

            $list_query_perolehan[
                "{$sales_campaign_detail['id']}_detail"
            ] = $query_perolehan;

            $this->db
            ->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_details as perolehan_detail')
            ->where('perolehan_detail.id_campaign', $this->input->post('id_campaign'))
            ->where('perolehan_detail.id_detail', $sales_campaign_detail['id'])
            ->where('perolehan_detail.id_perolehan = perolehan.id', null, false);

            if($sales_campaign_detail['satuan_rekapan_gimmick'] == 'Satuan'){
                $this->db->select('perolehan_detail.jumlah_kuantitas_yang_tercapai_sisa');
            }else if($sales_campaign_detail['satuan_rekapan_gimmick'] == 'Dus'){
                $this->db->select('perolehan_detail.jumlah_dus_yang_tercapai_sisa');
            }

            $query_sisa = $this->db->get_compiled_select();

            $list_query_sisa[
                "{$sales_campaign_detail['id']}_sisa"
            ] = $query_sisa;
        }

        $sales_campaign_gimmick_items = $this->db
        ->select('sc_gimmick_item.id')
		->from('ms_h3_md_sales_campaign_detail_gimmick_item as sc_gimmick_item')
		->join('ms_h3_md_sales_campaign_detail_gimmick as sc_detail', 'sc_detail.id = sc_gimmick_item.id_detail_gimmick')
		->where('sc_detail.id_campaign', $this->input->post('id_campaign'))
		->order_by('sc_gimmick_item.id_detail_gimmick', 'asc')
		->get()->result_array();
        $list_query_item = [];
        foreach ($sales_campaign_gimmick_items as $sales_campaign_gimmick_item) {
            $query_item = $this->db
            ->select('SUM(perolehan_item.count_gimmick) as count_gimmick', false)
            ->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_item as perolehan_item')
            ->where('perolehan_item.id_campaign', $this->input->post('id_campaign'))
            ->where('perolehan_item.id_gimmick_item', $sales_campaign_gimmick_item['id'])
            ->where('perolehan_item.id_perolehan = perolehan.id', null, false)
            ->get_compiled_select();

            $list_query_item[
                "{$sales_campaign_gimmick_item['id']}_item"
            ] = $query_item;
        }

        $total_perolehan_hadiah = $this->db
        ->select('SUM(perolehan_item.count_gimmick) as count_gimmick', false)
        ->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_item as perolehan_item')
        ->where('perolehan_item.id_campaign', $this->input->post('id_campaign'))
        ->where('perolehan_item.id_perolehan = perolehan.id', null, false)
        ->get_compiled_select();

        foreach ($list_query_perolehan as $key => $row_query) {
            $this->db->select("IFNULL(({$row_query}), 0) as `{$key}`", false);
        }

        foreach ($list_query_item as $key => $row_query) {
            $this->db->select("IFNULL(({$row_query}), 0) as `{$key}`", false);
        }

        foreach ($list_query_sisa as $key => $row_query) {
            $this->db->select("IFNULL(({$row_query}), 0) as `{$key}`", false);
        }

        $this->db->select("IFNULL(({$total_perolehan_hadiah}), 0) as total_perolehan_hadiah", false);

        $this->db
        ->select('perolehan.id')
        ->select('d.nama_dealer')
        ->select('d.kode_dealer_md')
        ->select('perolehan.sudah_create_so')
        ->select('
            case
                when sc.end_date_gimmick IS NOT NULL then sc.end_date_gimmick
                else sc.end_date
            end as akhir_periode
        ', false)
        ->select('ps.no_faktur')
        ->select('ps.tgl_faktur')
        ->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_dealers as perolehan')
        ->join('ms_dealer as d', 'd.id_dealer = perolehan.id_dealer')
        ->join('ms_h3_md_sales_campaign as sc', 'sc.id = perolehan.id_campaign')
        ->join('tr_h3_md_sales_order as so', '(so.id_perolehan = perolehan.id AND so.status != "Canceled")', 'left')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order', 'left')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order', 'left')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list', 'left')
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
