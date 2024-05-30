<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Create_faktur extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index;
            $row['action'] = $this->load->view('additional/md/h3/action_index_create_faktur_datatable', [
                'id' => $row['id_do_sales_order']
            ], true);

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
        $do_revisi_open = $this->db
            ->select('count(dr.id)')
            ->from('tr_h3_md_do_revisi as dr')
            ->where('dr.id_do_sales_order = dso.id_do_sales_order')
            ->where('dr.status', 'Open')
            ->where('dr.source', 'scan_picking_list')
            ->get_compiled_select();

        // $this->db
        //     ->select('ps.tgl_faktur')
        //     ->select('ifnull(ps.no_faktur, "-") as no_faktur')
        //     ->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
        //     ->select('date_format(dso.tanggal, "%d-%m-%Y") as tanggal_do')
        //     ->select('dso.id_do_sales_order')
        //     ->select('so.id_sales_order')
        //     ->select('d.nama_dealer')
        //     ->select('d.kode_dealer_md as kode_dealer')
        //     ->select('d.alamat')
        //     ->select('
        //     concat(
        //         "Rp ",
        //         format(dso.total, 0, "ID_id")
        //     )
        // as amount')
        //     ->select('so.produk')
        //     ->select('dso.sudah_revisi')
        //     ->from('tr_h3_md_do_sales_order as dso')
        //     ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
        //     // ->join('tr_h3_dealer_purchase_order as po', 'so.id_ref = po.po_id')
        //     ->join('tr_h3_md_picking_list as pl', 'pl.id_ref = dso.id_do_sales_order')
        //     ->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
        //     ->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list', 'left')
        //     ->where('pl.selesai_scan', 1)
        //     ->where("IFNULL(({$do_revisi_open}), 0) < 1", null, false);

        $this->db
        ->select('ps.tgl_faktur')
        ->select('ifnull(ps.no_faktur, "-") as no_faktur')
        ->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
        ->select('date_format(dso.tanggal, "%d-%m-%Y") as tanggal_do')
        ->select('dso.id_do_sales_order')
        ->select('dso.id_sales_order')
        ->select('d.nama_dealer')
        ->select('d.kode_dealer_md as kode_dealer')
        ->select('d.alamat')
        ->select('
            concat(
                "Rp ",
                format(dso.total, 0, "ID_id")
            )
        as amount')
        ->select('so.produk')
        ->select('dso.sudah_revisi')
        ->from('tr_h3_md_do_sales_order as dso')
        ->join('tr_h3_md_sales_order as so', 'dso.id_sales_order_int=so.id')
        // ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
        // ->join('tr_h3_dealer_purchase_order as po', 'so.id_ref = po.po_id')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_ref_int= dso.id')
        ->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list_int = pl.id', 'left')
        ->where('pl.selesai_scan', 1)
        ->where("IFNULL(({$do_revisi_open}), 0) < 1", null, false)
        ;   

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->where('ps.faktur_printed', 1);
            $this->db->or_where('left(pl.created_at,10) <=', '2023-09-30');
            $this->db->group_end();
        } else {
            $this->db->where('left(pl.created_at,10) >', '2023-10-01');
            $this->db->where('pl.status !=', 'Canceled');
            $this->db->group_start();
            $this->db->where('ps.id', null);
            $this->db->or_where('ps.faktur_printed', 0);
            $this->db->group_end();
            // $this->db->group_start();
            // $this->db->where('pl.status !=', 'Cancel');
            // $this->db->or_where('pl.status !=', 'Canceled');
            // $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('dso.id_sales_order', $search);
            $this->db->or_like('dso.id_do_sales_order', $search);
            $this->db->or_like('ps.no_faktur', $search);
            $this->db->or_like('d.kode_dealer_md', $search);
            $this->db->or_like('d.nama_dealer', $search);
            $this->db->group_end();
        }

        $id_customer_filter = $this->input->post('id_customer_filter');
        if ($id_customer_filter != null) $this->db->where('so.id_dealer', $id_customer_filter);

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('d.nama_dealer', 'asc');
            $this->db->order_by('ps.no_faktur', 'asc');
            $this->db->order_by('dso.created_at', 'desc');
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
