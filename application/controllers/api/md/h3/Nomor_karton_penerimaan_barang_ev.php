<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Nomor_karton_penerimaan_barang_ev extends CI_Controller
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

            $row['action'] = $this->load->view('additional/md/h3/action_nomor_karton_penerimaan_barang_ev', [
                'data' => json_encode($row),
                'nomor_karton' => $row['carton_id'],
                'packing_sheet_number' => $row['packing_id'],
                'box_id' => $row['box_id'],
                'serial_number' => $row['serial_number'],
                'status' => $row['status'],
                'nomor_karton_int' => $row['no_doos_int'],
                'packing_sheet_number_int' => $row['packing_sheet_number_int'],
                'surat_jalan_ahm_int' => $row['surat_jalan_ahm_int'],
                'surat_jalan_ahm' => $row['surat_jalan_ahm'],
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
        if ($withSelect) {
            $this->select();
        }

        $this->db->from('tr_shipping_list_ev_accrem as sl');
        $this->join_table();
        $filter_surat_jalan_ahm = $this->input->post('filter_surat_jalan_ahm');
        $filter_packing_sheet_number = $this->input->post('filter_packing_sheet_number');
        $filter_nomor_karton = $this->input->post('filter_nomor_karton');

        if($filter_surat_jalan_ahm != null OR $filter_packing_sheet_number != null OR $filter_nomor_karton != null){
            $this->db->group_start();
            if($filter_surat_jalan_ahm != null) $this->db->like('sl.no_shipping_list', $filter_surat_jalan_ahm);
            if($filter_packing_sheet_number != null) $this->db->like('sl.packing_id', $filter_packing_sheet_number);
            if($filter_nomor_karton != null) $this->db->like('sl.carton_id', $filter_nomor_karton);
            $this->db->group_end();
        }else{
            $this->db->where('1=1', null, false);
        }

        $this->db
            ->where('sl.is_penerimaan', 0)
            ->or_where('sl.is_penerimaan', null);
    }

    public function select()
    {
        $this->db
            ->select('sl.box_id')
            ->select('sl.packing_id')
            ->select('sl.carton_id')
            ->select('sl.acc_tipe')
            ->select('sl.part_id')
            ->select('sl.serial_number')
            ->select('0 as status')
            ->select('psli.surat_jalan_ahm_int')
            ->select('psli.surat_jalan_ahm')
            ->select('psli.packing_sheet_number_int')
            ->select('psli.packing_sheet_number')
            ->select('psp.no_doos')
            ->select('psp.no_doos_int')
            ->select('ps.packing_sheet_date');
    }

    public function join_table()
    {
        $this->db
            ->join('tr_h3_md_nomor_karton as nk', 'nk.nomor_karton = sl.carton_id')
            ->join('tr_h3_md_ps_parts as psp', 'psp.no_doos_int = nk.id and sl.packing_id = psp.packing_sheet_number and sl.id_part_int = psp.id_part_int')
            ->join('tr_h3_md_ps as ps', '(ps.id = psp.packing_sheet_number_int)')
            ->join('tr_h3_md_psl_items as psli', 'psli.packing_sheet_number_int = ps.id');
    }


    public function make_datatables($withSelect = true)
    {
        $this->make_query($withSelect);

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('sl.packing_id', $search);
            $this->db->or_like('sl.carton_id', $search);
            $this->db->or_like('sl.part_id', $search);
            $this->db->or_like('sl.serial_number', $search);
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
            $this->db->order_by('sl.carton_id', 'asc');
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
