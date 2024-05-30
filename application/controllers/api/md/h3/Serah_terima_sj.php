<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Serah_terima_sj extends CI_Controller
{
    private $nama_customer_filter;
    private $id_packing_sheet_filter;
    private $no_faktur_filter;

    public function index()
    {
        $this->db
            ->select('sti.id_serah_terima_sj_int')
            ->from('tr_h3_md_serah_terima_sj_item as sti')
            ->join('tr_h3_md_packing_sheet as ps', 'ps.id = sti.id_packing_sheet_int')
            ->join('tr_h3_md_picking_list as pl', 'pl.id = ps.id_picking_list_int')
            ->join('tr_h3_md_do_sales_order as do', 'do.id = pl.id_ref_int')
            ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
            ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer');
        $nama_customer_filter = $this->input->post('nama_customer_filter');
        if ($nama_customer_filter != null) {
            $this->db->like('d.nama_dealer', trim($nama_customer_filter));
        } else {
            $this->db->where('1=0', null, false);
        }
        $this->nama_customer_filter = array_column($this->db->get()->result_array(), 'id_serah_terima_sj_int');

        $this->db
            ->select('sti.id_serah_terima_sj_int')
            ->from('tr_h3_md_serah_terima_sj_item as sti')
            ->join('tr_h3_md_packing_sheet as ps', 'ps.id = sti.id_packing_sheet_int')
            ->join('tr_h3_md_picking_list as pl', 'pl.id = ps.id_picking_list_int')
            ->join('tr_h3_md_do_sales_order as do', 'do.id = pl.id_ref_int')
            ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
            ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer');
        $id_packing_sheet_filter = $this->input->post('id_packing_sheet_filter');
        if ($id_packing_sheet_filter != null) {
            $this->db->like('ps.id_packing_sheet', trim($id_packing_sheet_filter));
        } else {
            $this->db->where('1=0', null, false);
        }
        $this->id_packing_sheet_filter = array_column($this->db->get()->result_array(), 'id_serah_terima_sj_int');

        $this->db
            ->select('sti.id_serah_terima_sj_int')
            ->from('tr_h3_md_serah_terima_sj_item as sti')
            ->join('tr_h3_md_packing_sheet as ps', 'ps.id = sti.id_packing_sheet_int')
            ->join('tr_h3_md_picking_list as pl', 'pl.id = ps.id_picking_list_int')
            ->join('tr_h3_md_do_sales_order as do', 'do.id = pl.id_ref_int')
            ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
            ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer');
        $no_faktur_filter = $this->input->post('no_faktur_filter');
        if ($no_faktur_filter != null) {
            $this->db->like('ps.no_faktur', trim($no_faktur_filter));
        } else {
            $this->db->where('1=0', null, false);
        }
        $this->no_faktur_filter = array_column($this->db->get()->result_array(), 'id_serah_terima_sj_int');

        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_serah_terima_sj', [
                'id' => $row['id']
            ], true);
            $row['index'] = $this->input->post('start') + $index . '.';
            $index++;
            $data[] = $row;
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
        $jumlah_sj = $this->db
            ->select('count(sti.id_packing_sheet)')
            ->from('tr_h3_md_serah_terima_sj_item as sti')
            ->where('sti.id_serah_terima_sj = st.id_serah_terima_sj')
            ->get_compiled_select();

        $this->db
            ->select('st.id')
            ->select('st.id_serah_terima_sj')
            ->select('st.created_at')
            ->select('st.proses_at')
            ->select('st.rejected_at')
            ->select("({$jumlah_sj}) as jumlah_sj", false)
            ->select('st.status')
            ->from('tr_h3_md_serah_terima_sj as st');

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->where('st.status !=', 'Open');
        } else {
            $this->db->where('st.status', 'Open');
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        if (count($this->nama_customer_filter) > 0 or count($this->id_packing_sheet_filter) > 0 or count($this->no_faktur_filter) > 0) {
            $this->db->group_start();
            if (count($this->nama_customer_filter) > 0) {
                $this->db->where_in('st.id', $this->nama_customer_filter);
            }

            if (count($this->id_packing_sheet_filter) > 0) {
                $this->db->where_in('st.id', $this->id_packing_sheet_filter);
            }

            if (count($this->no_faktur_filter) > 0) {
                $this->db->where_in('st.id', $this->no_faktur_filter);
            }
            $this->db->group_end();
        }

        if ($this->input->post('id_serah_terima_sj_filter') != null) {
            $this->db->like('st.id_serah_terima_sj', trim($this->input->post('id_serah_terima_sj_filter')));
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('st.created_at', 'desc');
        }
    }

    public function limit()
    {
        if ($this->input->post('length') != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal()
    {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
