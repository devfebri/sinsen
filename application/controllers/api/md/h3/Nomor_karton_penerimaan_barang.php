<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Nomor_karton_penerimaan_barang extends CI_Controller
{
    public function index()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $this->benchmark->mark('datatable_start');
        $this->make_datatables();
        $this->limit();
        // $this->order();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';

            $row['action'] = $this->load->view('additional/md/h3/action_nomor_karton_penerimaan_barang', [
                'data' => json_encode($row),
                'nomor_karton' => $row['no_doos'],
                'nomor_karton_int' => $row['no_doos_int'],
                'packing_sheet_number_int' => $row['packing_sheet_number_int'],
                'packing_sheet_number' => $row['packing_sheet_number'],
                'surat_jalan_ahm_int' => $row['surat_jalan_ahm_int'],
                'surat_jalan_ahm' => $row['surat_jalan_ahm'],
                'status' => $row['status'],
            ], true);
            $data[] = $row;
            $index++;
        }
        $this->benchmark->mark('datatable_end');

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'records_filtered_time' => (float) $this->benchmark->elapsed_time('records_filtered_start', 'records_filtered_end'),
            'recordsTotal' => $this->recordsTotal(),
            'records_total_time' => (float) $this->benchmark->elapsed_time('records_total_start', 'records_total_end'),
            'data' => $data,
            'datatable_time' => (float) $this->benchmark->elapsed_time('datatable_start', 'datatable_end'),
        ]);
    }

    public function make_query($withSelect = true)
    {
        $nomor_karton_diterima = $this->db
            ->select('COUNT(pbi_sq.nomor_karton)')
            ->from('tr_h3_md_penerimaan_barang_items as pbi_sq')
            ->where('pbi_sq.surat_jalan_ahm_int = psli.surat_jalan_ahm_int', null, false)
            ->where('pbi_sq.nomor_karton_int = psp.no_doos_int', null, false)
            ->where('pbi_sq.tersimpan', 1)
            ->get_compiled_select();

        if ($withSelect) {
            $this->select();
        }

        $this->db->from('tr_h3_md_nomor_karton as nk');

        $this->join_table();

        $filter_surat_jalan_ahm = $this->input->post('filter_surat_jalan_ahm');
        $filter_packing_sheet_number = $this->input->post('filter_packing_sheet_number');
        $filter_nomor_karton = $this->input->post('filter_nomor_karton');

        if($filter_surat_jalan_ahm != null OR $filter_packing_sheet_number != null OR $filter_nomor_karton != null){
            $this->db->group_start();
            if($filter_surat_jalan_ahm != null) $this->db->like('psli.surat_jalan_ahm', $filter_surat_jalan_ahm);
            if($filter_packing_sheet_number != null) $this->db->like('psp.packing_sheet_number', $filter_packing_sheet_number);
            if($filter_nomor_karton != null) $this->db->like('psp.no_doos', $filter_nomor_karton);
            $this->db->group_end();
        }else{
            $this->db->where('1=0', null, false);
        }

        $this->db
            ->where('nk.jumlah_item >', 0)
            ->where('nk.jumlah_item != nk.jumlah_item_diterima', null, false)
            ->group_start()
                ->where('psp.is_ev', '')
                ->or_where('psp.is_ev', 0)
                ->or_where('psp.is_ev', null)
            ->group_end();

        $this->db->group_by('psli.surat_jalan_ahm');
        $this->db->group_by('psp.packing_sheet_number');
        $this->db->group_by('psp.no_doos');
    }

    public function join_table()
    {
        $this->db
            ->join('tr_h3_md_ps_parts as psp', 'psp.no_doos = nk.nomor_karton')
            ->join('tr_h3_md_ps as ps', '(ps.packing_sheet_number = psp.packing_sheet_number AND ps.invoice_number_int IS NOT NULL)')
            ->join('tr_h3_md_psl_items as psli', 'psli.packing_sheet_number = ps.packing_sheet_number');
    }

    public function select()
    {
        $nomor_karton_diterima = $this->db
            ->select('COUNT(pbi_sq.nomor_karton)')
            ->from('tr_h3_md_penerimaan_barang_items as pbi_sq')
            ->where('pbi_sq.surat_jalan_ahm_int = psli.surat_jalan_ahm_int', null, false)
            ->where('pbi_sq.nomor_karton = psp.no_doos', null, false)
            ->where('pbi_sq.tersimpan', 1)
            ->get_compiled_select();

        $this->db
            ->select('psli.surat_jalan_ahm_int')
            ->select('psli.surat_jalan_ahm')
            ->select('psli.packing_sheet_number_int')
            ->select('psli.packing_sheet_number')
            ->select('psp.no_doos')
            ->select('psp.no_doos_int')
            ->select('ps.packing_sheet_date')
            ->select("IFNULL(({$nomor_karton_diterima}), 0) as nomor_karton_diterima", false)
            ->select('nk.jumlah_item as jumlah_item_dikarton')
            ->select('0 as status');
    }

    public function make_datatables($withSelect = true)
    {
        $this->make_query($withSelect);

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('psli.surat_jalan_ahm', $search);
            $this->db->or_like('psp.no_doos', $search);
            $this->db->or_like('psp.packing_sheet_number', $search);
            $this->db->or_like('psli.surat_jalan_ahm', $search);
            $this->db->group_end();
        }
    }

    public function order()
    {
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('psp.no_doos', 'asc');
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
