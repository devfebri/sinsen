<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Packing_sheet extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $this->benchmark->mark('data_start');
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/action_packing_sheet_datatable', [
                'data' => json_encode($row)
            ], true);

            $data[] = $row;
            $index++;
        }
        $this->benchmark->mark('data_end');

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsFiltered_time' => floatval($this->benchmark->elapsed_time('recordsFiltered_start', 'recordsFiltered_end')),
            'recordsTotal' => $this->recordsTotal(),
            'recordsTotal_time' => floatval($this->benchmark->elapsed_time('recordsTotal_start', 'recordsTotal_end')),
            'data' => $data,
            'data_time' => floatval($this->benchmark->elapsed_time('data_start', 'data_end'))
        ]);
    }

    public function make_query()
    {
        $packing_sudah_proses = $this->db
            ->select('pb.id_packing_sheet')
            ->from('tr_h3_dealer_penerimaan_barang as pb')
            ->get_compiled_select();

        $this->db
            ->select('sp.id_surat_pengantar')
            ->select('sp.tanggal as tanggal_surat_pengantar')
            ->select('ps.*')
            ->select('ps.tgl_packing_sheet as tanggal_packing_sheet')
            ->select('po.po_id as nomor_po')
            ->select('po.tanggal_order as tanggal_po')
            ->select('ps.tgl_faktur as tanggal_faktur')
            ->select('ps.no_faktur as nomor_faktur')
            ->from('tr_h3_md_surat_pengantar_items as spi')
            ->join('tr_h3_md_surat_pengantar as sp', 'sp.id = spi.id_surat_pengantar_int')
            ->join('tr_h3_md_packing_sheet as ps', 'ps.id = spi.id_packing_sheet_int')
            ->join('tr_h3_md_picking_list as pl', 'ps.id_picking_list_int = pl.id')
            ->join('tr_h3_md_do_sales_order as dso', 'dso.id = pl.id_ref_int')
            ->join('tr_h3_md_sales_order as so', 'dso.id_sales_order_int = so.id')
            ->join('tr_h3_dealer_purchase_order as po', 'so.id_ref = po.po_id')
            ->where("ps.id_packing_sheet not in ({$packing_sudah_proses})")
            ->where('so.id_dealer', $this->m_admin->cari_dealer());

        if ($this->input->post('id_surat_pengantar') != null) {
            $this->db->where('spi.id_surat_pengantar', $this->input->post('id_surat_pengantar'));
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ps.id_packing_sheet', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.created_at', 'DESC');
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
        $this->benchmark->mark('recordsFiltered_start');
        $this->make_datatables();
        $record = $this->db->count_all_results();
        $this->benchmark->mark('recordsFiltered_end');

        return $record;
    }

    public function recordsTotal()
    {
        $this->benchmark->mark('recordsTotal_start');
        $this->make_query();
        $record = $this->db->count_all_results();
        $this->benchmark->mark('recordsTotal_end');

        return $record;
    }
}
