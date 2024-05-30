<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealer_retur_penjualan extends CI_Controller
{
    public function index()
    {
        $this->benchmark->mark('data_start');
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_dealer_retur_penjualan', [
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
            'data_time' => floatval($this->benchmark->elapsed_time('data_start', 'data_end'))
        ]);
    }

    public function make_query()
    {
        $no_faktur_sudah_retur = $this->db
            ->select('rp.no_faktur')
            ->from('tr_h3_md_retur_penjualan as rp')
            ->where('rp.status !=', 'Canceled')
            ->get_compiled_select();

        $dealer_yang_terdapat_faktur = $this->db
            ->select('pl.id_dealer')
            ->from('tr_h3_md_packing_sheet as ps')
            ->join('tr_h3_md_picking_list as pl', 'pl.id = ps.id_picking_list_int')
            ->where("ps.no_faktur not in ({$no_faktur_sudah_retur})")
            ->get_compiled_select();

        $this->db
            ->select('d.id_dealer')
            ->select('d.nama_dealer')
            ->select('d.kode_dealer_md')
            ->select('d.alamat')
            ->from('ms_dealer as d')
            ->where("d.id_dealer in ({$dealer_yang_terdapat_faktur})", null, false);
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('d.kode_dealer_md', $search);
            $this->db->or_like('d.nama_dealer', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('d.nama_dealer', 'asc');
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
