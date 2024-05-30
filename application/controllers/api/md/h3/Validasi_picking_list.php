<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Validasi_picking_list extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_validasi_picking_list', [
                'id' => $row['id_picking_list'],
                'status' => $row['status'],
                'cetakan_ke' => $row['cetak_ke']
            ], true);
            $row['id_picking_list'] = $this->load->view('additional/action_index_picking_list_validasi_picking_list_datatable', [
                'id' => $row['id_picking_list'],
            ], true);

            $row['index'] = $this->input->post('start') + $index . '.';
            $index++;
            $data[] = $row;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
        ]);
    }

    public function make_query()
    {
        $this->db
            ->select('k.nama_lengkap as nama_picker')
            ->select('po.po_type')
            ->select('date_format(pl.created_at, "%d-%m-%Y") as tanggal_picking')
            ->select('pl.id_picking_list')
            ->select('d.nama_dealer')
            ->select('d.alamat')
            ->select('so.kategori_po')
            ->select('pl.cetak_ke')
            ->select('pl.start_pick')
            ->select('pl.end_pick')
            ->select('
            case
                when (pl.start_pick is null or pl.end_pick is null) then null
                else timestampdiff(second, pl.start_pick, pl.end_pick) * 1000
            end as duration
        ')
            ->select('pl.status')
            ->select('
            case
                when k.nama_lengkap is not null then k.nama_lengkap
                else "-"
            end as nama_picker
        ', false)
            ->from('tr_h3_md_picking_list as pl')
            ->join('ms_karyawan as k', 'k.id_karyawan = pl.id_picker')
            ->join('tr_h3_md_do_sales_order as do', 'do.id = pl.id_ref_int')
            ->join('tr_h3_md_sales_order as so', 'so.id = do.id_sales_order_int')
            ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = so.id_ref')
            ->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
            ->where('pl.selesai_scan', 0);
    }

    public function make_datatables()
    {
        $this->make_query();

        if ($this->input->post('dealers') and count($this->input->post('dealers')) > 0) {
            $this->db->where_in('pl.id_dealer', $this->input->post('dealers'));
        }

        $tanggal_picking_list_filter_start = $this->input->post('tanggal_picking_list_filter_start');
        $tanggal_picking_list_filter_end = $this->input->post('tanggal_picking_list_filter_end');
        if ($tanggal_picking_list_filter_start != null and $tanggal_picking_list_filter_end != null) {
            $this->db
                ->group_start()
                ->where(sprintf('pl.tanggal between "%s" AND "%s"', $tanggal_picking_list_filter_start, $tanggal_picking_list_filter_end))
                ->group_end();
        }

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pl.id_picking_list', $search);
            $this->db->or_like('k.nama_lengkap', $search);
            $this->db->or_like('d.nama_dealer', $search);
            $this->db->or_like('pl.status', $search);
            $this->db->or_like('so.kategori_po', $search);
            $this->db->or_like('so.po_type', $search);
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pl.created_at', 'desc');
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
        return $this->db->count_all_results();
    }

    public function recordsTotal()
    {
        $this->make_query();
        return $this->db->count_all_results();
    }
}
