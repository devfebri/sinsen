<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Faktur_retur_penjualan extends CI_Controller
{

    public function index()
    {
        $this->benchmark->mark('data_start');
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_faktur_retur_penjualan', [
                'data' => json_encode($row)
            ], true);
            $data[] = $row;
        }
        $this->benchmark->mark('data_end');

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
            'recordsFiltered_time' => floatval($this->benchmark->elapsed_time('recordsFiltered_start', 'recordsFiltered_end')),
            'recordsTotal_time' => floatval($this->benchmark->elapsed_time('recordsTotal_start', 'recordsTotal_end')),
            'data_time' => floatval($this->benchmark->elapsed_time('data_start', 'data_end')),
        ]);
    }

    public function make_query()
    {
        $no_faktur_sudah_retur = $this->db
            ->select('rp.no_faktur')
            ->from('tr_h3_md_retur_penjualan as rp')
            ->where('rp.status !=', 'Canceled')
            ->get_compiled_select();

        $this->db
            ->select('ps.no_faktur')
            ->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tgl_faktur')
            ->select('ps.id_packing_sheet')
            ->select('date_format(ps.tgl_packing_sheet, "%d-%m-%Y") as tgl_packing_sheet')
            ->select('sp.id_dealer')
            ->select('d.nama_dealer')
            ->select('d.alamat')
            ->select('k.nama_lengkap as nama_salesman')
            ->select('do.diskon_cashback')
            ->select('do.diskon_cashback_otomatis')
            ->select('do.diskon_insentif')
            ->select('do.total as total_nilai_faktur')
            ->from('tr_h3_md_packing_sheet as ps')
            ->join('tr_h3_md_surat_pengantar_items as spi', 'spi.id_packing_sheet_int = ps.id')
            ->join('tr_h3_md_surat_pengantar as sp', 'sp.id = spi.id_surat_pengantar_int')
            ->join('tr_h3_md_picking_list as pl', 'pl.id = ps.id_picking_list_int')
            ->join('tr_h3_md_do_sales_order as do', 'do.id = pl.id_ref_int')
            ->join('tr_h3_md_sales_order as so', 'so.id = do.id_sales_order_int')
            ->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
            ->join('ms_dealer as d', 'd.id_dealer = sp.id_dealer')
            ->where('sp.shipping_list_printed', 1)
            ->where("ps.no_faktur not in ({$no_faktur_sudah_retur})", null, false);

        if ($this->input->post('id_dealer') != null) {
            $this->db->where('sp.id_dealer', $this->input->post('id_dealer'));
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ps.no_faktur', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.no_faktur', 'asc');
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
        $record =  $this->db->count_all_results();
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
