<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Perolehan_gimmick_global extends CI_Controller
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
                $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_perolehan_sales_campaign_global', [
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

        $query_perolehan = [];
        foreach ($sales_campaign_details as $sales_campaign_detail) {
            $key = $sales_campaign_detail['id'] . '_detail';

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

            $query = $this->db->get_compiled_select();

            $query_perolehan[$key] = $query;
        }

        $sales_campaign_globals = $this->db
        ->select('sc_global.*')
		->select('CONCAT(sc_global.id, "_global") as label_key', false)
        ->from('ms_h3_md_sales_campaign_detail_gimmick_global as sc_global')
        ->where('sc_global.id_campaign', $this->input->post('id_campaign'))
        ->get()->result_array();

        $query_perolehan_hadiah = [];
        foreach ($sales_campaign_globals as $sales_campaign_global) {
            $query = $this->db
            ->select('perolehan_global.count_gimmick')
            ->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_global as perolehan_global')
            ->where('perolehan_global.id_campaign', $this->input->post('id_campaign'))
            ->where('perolehan_global.id_gimmick_global', $sales_campaign_global['id'])
            ->where('perolehan_global.id_perolehan = perolehan.id', null, false)
            ->get_compiled_select();

            $query_perolehan_hadiah[
                $sales_campaign_global['label_key']
            ] = $query;
        }


        $total_perolehan_hadiah = $this->db
        ->select('SUM(perolehan_global.count_gimmick) as count_gimmick')
        ->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_global as perolehan_global')
        ->where('perolehan_global.id_campaign', $this->input->post('id_campaign'))
        ->where('perolehan_global.id_perolehan = perolehan.id', null, false)
        ->get_compiled_select();

        foreach ($query_perolehan as $key => $row_query) {
            $this->db->select("IFNULL(({$row_query}), 0) as {$key}", false);
        }

        foreach ($query_perolehan_hadiah as $key => $row_query) {
            $this->db->select("IFNULL(({$row_query}), 0) as {$key}", false);
        }

        $this->db->select("IFNULL(({$total_perolehan_hadiah}), 0) as total_perolehan_hadiah", false);

        $this->db
        ->select('perolehan.id')
        ->select('d.nama_dealer')
        ->select('d.kode_dealer_md')
        ->select('perolehan.total_pembelian')
        ->select('perolehan.total_pembelian_dus')
        ->select('perolehan.total_pembelian_dus_sisa as total_pembelian_sisa')
        ->select('perolehan.sudah_create_so')
        ->select('ps.no_faktur')
        ->select('ps.tgl_faktur')
        ->select('
            case
                when sc.end_date_gimmick IS NOT NULL then sc.end_date_gimmick
                else sc.end_date
            end as akhir_periode
        ', false)
        ->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_dealers as perolehan')
        ->join('ms_dealer as d', 'd.id_dealer = perolehan.id_dealer')
        ->join('ms_h3_md_sales_campaign as sc', 'sc.id = perolehan.id_campaign')
        ->join('tr_h3_md_sales_order as so', '(so.id_perolehan = perolehan.id AND so.status != "Canceled")', 'left')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order_int = so.id', 'left')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_ref_int = do.id', 'left')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list_int = pl.id', 'left')
        ->where('perolehan.id_campaign', $this->input->post('id_campaign'))
        ->where('d.h1',0)
        ->where('d.h2',0)
        ->where('d.h3',1)
        ;

        if(count($dealer_tidak_terdiskualifikasi) > 0){
            $this->db->where_in('perolehan.id_dealer', $dealer_tidak_terdiskualifikasi);
        }
        
        $this->db->having("{$key} > " ,0);
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
