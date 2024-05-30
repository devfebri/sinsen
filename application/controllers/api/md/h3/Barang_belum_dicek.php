<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Barang_belum_dicek extends CI_Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', '0');

        parent::__construct();
    }

    public function index()
    {
        $this->benchmark->mark('datatable_start');
        $this->make_datatables();
        $this->limit();
        $data = $this->db->get()->result_array();
        $this->benchmark->mark('datatable_end');

        send_json([
            'draw' => intval($this->input->post('draw')),
            'data' => $data,
            'recordsFiltered' => $this->recordsFiltered(),
            'records_filtered_time' => (float) $this->benchmark->elapsed_time('records_filtered_start', 'records_filtered_end'),
            'recordsTotal' => $this->recordsTotal(),
            'records_total_time' => (float) $this->benchmark->elapsed_time('records_total_start', 'records_total_end'),
            'datatable_time' => (float) $this->benchmark->elapsed_time('datatable_start', 'datatable_end'),
        ]);
    }

    public function make_query()
    {
        $surat_jalan_ahm = $this->db
            ->select('DISTINCT(pbi.surat_jalan_ahm_int) as surat_jalan_ahm_int', false)
            ->from('tr_h3_md_penerimaan_barang_items as pbi')
            ->where('pbi.no_surat_jalan_ekspedisi', $this->input->post('no_surat_jalan_ekspedisi'))
            ->get_compiled_select();

        $this->db
            ->select('psli.surat_jalan_ahm')
            ->select('date_format(ps.packing_sheet_date, "%d/%m/%Y") as packing_sheet_date')
            ->select('ps.packing_sheet_number')
            ->select('psp.no_doos as nomor_karton')
            ->select('psp.id_part')
            ->select('p.nama_part')
            ->select('psp.packing_sheet_quantity')
            ->select('fdo.invoice_number')
            ->select('fdo.invoice_date')
            ->from('tr_h3_md_ps as ps')
            ->join('tr_h3_md_fdo as fdo', 'fdo.id = ps.invoice_number_int', 'left')
            ->join('tr_h3_md_ps_parts as psp', 'psp.packing_sheet_number_int = ps.id')
            ->join('tr_h3_md_psl_items as psli', 'psli.packing_sheet_number_int = ps.id')
            ->join('tr_h3_md_penerimaan_barang_items as pbi', '(pbi.id_part_int = psp.id_part_int and pbi.packing_sheet_number_int = psp.packing_sheet_number_int and pbi.nomor_karton = psp.no_doos)', 'left')
            ->join('ms_part as p', 'p.id_part_int = psp.id_part_int')
            ->where("psli.surat_jalan_ahm_int in ({$surat_jalan_ahm})")
            ->group_start()
            ->where('pbi.id', null)
            ->or_where('pbi.tersimpan', 0)
            ->group_end();
    }

    public function make_datatables()
    {
        $this->make_query();

        $filter_surat_jalan_ahm = $this->input->post('filter_surat_jalan_ahm');
        $filter_packing_sheet_number = $this->input->post('filter_packing_sheet_number');
        $filter_nomor_karton = $this->input->post('filter_nomor_karton');
        $filter_id_part = $this->input->post('filter_id_part');

        if ($filter_surat_jalan_ahm != null or $filter_packing_sheet_number != null or $filter_nomor_karton != null or $filter_id_part != null) {
            $this->db->group_start();
            if ($filter_surat_jalan_ahm != null) $this->db->like('psli.surat_jalan_ahm', $filter_surat_jalan_ahm);
            if ($filter_packing_sheet_number != null) $this->db->like('psp.packing_sheet_number', $filter_packing_sheet_number);
            if ($filter_nomor_karton != null) $this->db->like('psp.no_doos', $filter_nomor_karton);
            if ($filter_id_part != null) $this->db->like('psp.id_part', $filter_id_part);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.packing_sheet_number', 'asc');
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
        $this->benchmark->mark('records_filtered_start');
        $this->make_datatables(false);
        $rows = count($this->db->get()->result_array());
        $this->benchmark->mark('records_filtered_end');

        return $rows;
    }

    public function recordsTotal()
    {
        $this->benchmark->mark('records_total_start');
        $this->make_query(false);
        $rows = count($this->db->get()->result_array());
        $this->benchmark->mark('records_total_end');

        return $rows;
    }
}
