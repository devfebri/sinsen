<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Report_pembayaran_ke_vendor extends CI_Controller
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
            'data' => $data,
            'post' => $_POST
        ]);
    }

    public function get_total()
    {
        $this->make_datatables();
        $result = $this->db->get()->result_array();

        $data = [
            'total_jumlah_terutang' => 0,
            'total_nominal' => 0,
        ];

        $data['total_jumlah_terutang'] = array_map(function ($row) {
            return floatval($row['nominal']);
        }, $result);
        $data['total_jumlah_terutang'] = array_sum($data['total_jumlah_terutang']);

        $data['total_nominal'] = array_map(function ($row) {
            return floatval($row['total_amount']);
        }, $result);
        $data['total_nominal'] = array_sum($data['total_nominal']);

        send_json($data);
    }

    public function make_query()
    {
        $this->db
            ->select('vp.id_voucher_pengeluaran')
            ->select('epb.tgl_cair')
            ->select('vp.nama_penerima_dibayarkan_kepada')
            ->select('vp.tanggal_transaksi')
            ->select('vpi.jumlah_terutang')
            ->select('vpi.nominal')
            ->select('vp.total_amount')
            ->select('vp.deskripsi')
            ->from('tr_h3_md_voucher_pengeluaran as vp')
            ->join('tr_h3_md_voucher_pengeluaran_items as vpi', 'vpi.id_voucher_pengeluaran = vp.id_voucher_pengeluaran')
            ->join('tr_h3_md_entry_pengeluaran_bank as epb', 'epb.id_voucher_pengeluaran_int = vp.id', 'left')
            ->join('ms_dealer as d', 'd.id_dealer = vp.id_account', 'left')
            ->join('ms_vendor as v', 'v.id_vendor = vp.id_account', 'left')
            ->where('vp.status != ', 'Canceled');
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('d.nama_dealer', $search);
            $this->db->or_like('v.vendor_name', $search);
            $this->db->group_end();
        }

        if ($this->input->post('tanggal_entry_start') != null and $this->input->post('tanggal_entry_end') != null) {
            $this->db->group_start();
            $this->db->where("vp.created_at between '{$this->input->post('tanggal_entry_start')}' AND '{$this->input->post('tanggal_entry_end')}'", null, false);
            $this->db->group_end();
        }

        if ($this->input->post('tanggal_transaksi_start') != null and $this->input->post('tanggal_transaksi_end') != null) {
            $this->db->group_start();
            $this->db->where("vp.tanggal_transaksi between '{$this->input->post('tanggal_transaksi_start')}' AND '{$this->input->post('tanggal_transaksi_end')}'", null, false);
            $this->db->group_end();
        }

        if ($this->input->post('tanggal_pembayaran_start') != null and $this->input->post('tanggal_pembayaran_end') != null) {
            $this->db->group_start();
            $this->db->where("epb.approved_at between '{$this->input->post('tanggal_pembayaran_start')}  00:00:01' AND '{$this->input->post('tanggal_pembayaran_end')} 23:59:59'", null, false);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('vp.id_voucher_pengeluaran', 'asc');
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
