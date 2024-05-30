<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_retur_pembelian_claim extends CI_Controller
{
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
            ->select('rpc.no_retur')
            ->select('rpc.tanggal')
            ->select('rpc.id_claim')
            ->select('cmda.created_at as tanggal_claim')
            ->select('rpci.id_part')
            ->select('p.nama_part')
            ->select('rpci.qty')
			->select('(rpci.nominal / rpci.qty) as price', false)
            ->select('rpci.nominal')
            ->select('ps.packing_sheet_date')
            ->select('ps.packing_sheet_number')
            ->select('fdo.invoice_date')
            ->select('fdo.invoice_number')
            ->from('tr_h3_md_retur_pembelian_claim as rpc')
            ->join('tr_h3_md_retur_pembelian_claim_items as rpci', 'rpci.no_retur = rpc.no_retur')
            ->join('tr_h3_md_claim_main_dealer_ke_ahm as cmda', 'cmda.id_claim = rpc.id_claim')
            ->join('ms_kategori_claim_c3 as kc', 'kc.id = rpci.id_kode_claim')
            ->join('ms_part as p', 'p.id_part = rpci.id_part')
            ->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = cmda.packing_sheet_number')
            ->join('tr_h3_md_fdo as fdo', 'fdo.invoice_number = cmda.invoice_number', 'left')
			->join('tr_h3_md_fdo_parts as fdo_parts', '(fdo_parts.id_part = rpci.id_part and fdo_parts.nomor_packing_sheet = ps.packing_sheet_number and fdo_parts.invoice_number = fdo.invoice_number)', 'left')

            ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('rpc.no_retur', $search);
            $this->db->group_end();
        }

        if ($this->input->post('periode_laporan_retur_pembelian_filter_start') != null and $this->input->post('periode_laporan_retur_pembelian_filter_end') != null) {
            $this->db->group_start();
            $this->db->where('rpc.tanggal >=', $this->input->post('periode_laporan_retur_pembelian_filter_start'));
            $this->db->where('rpc.tanggal <=', $this->input->post('periode_laporan_retur_pembelian_filter_end'));
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('rpc.created_at', 'desc');
        }
    }

    public function limit()
    {
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->count_all_results();
    }

    public function recordsTotal()
    {
        $this->make_query();
        return $this->db->count_all_results();
    }
}
