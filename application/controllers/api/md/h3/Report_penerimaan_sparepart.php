<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Report_penerimaan_sparepart extends CI_Controller
{
    public function index()
    {
        ini_set('max_execution_time', 0);

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
            return floatval($row['jumlah_terutang']);
        }, $result);
        $data['total_jumlah_terutang'] = array_sum($data['total_jumlah_terutang']);

        $data['total_nominal'] = array_map(function ($row) {
            return floatval($row['nominal']);
        }, $result);
        $data['total_nominal'] = array_sum($data['total_nominal']);

        send_json($data);
    }

    public function make_query()
    {
        $rumus_diskon = 'IFNULL( ((fdo_parts.disc_campaign/ IFNULL(SUM(pbi.qty_packing_sheet), 0) ) + (fdo_parts.disc_insentif/ IFNULL(SUM(pbi.qty_packing_sheet), 0))), 0 )';
        $rumus_harga_setelah_diskon = sprintf('( fdo_parts.price -  %s )', $rumus_diskon);
        $rumus_dpp = sprintf('IFNULL( ( IFNULL(SUM(pbi.qty_packing_sheet), 0) *  %s ), 0 )', $rumus_harga_setelah_diskon);
        $rumus_ppn = sprintf('IFNULL( ( %s * 0.1 ), 0 )', $rumus_dpp);

        $this->db
        ->select('pb.id')
        ->from('tr_h3_md_penerimaan_barang as pb')
        ->where('pb.status', 'Closed');
        $id_penerimaan_barang_yang_closed = array_column($this->db->get()->result_array(), 'id');
        $id_penerimaan_barang_yang_closed = implode(',', $id_penerimaan_barang_yang_closed);

        $this->db
            ->select('fdo.invoice_number')
            ->select('fdo.invoice_date')
            ->select('fdo.dpp_due_date as tanggal_jatuh_tempo')
            ->select('fdo_parts.id_part')
            ->select('p.nama_part')
            ->select('IFNULL(SUM(pbi.qty_packing_sheet), 0) as quantity')
            ->select('fdo_parts.price')
            ->select(sprintf('%s as diskon', $rumus_diskon))
            ->select(sprintf('%s as dpp', $rumus_dpp))
            ->select(sprintf('%s as ppn', $rumus_ppn))
            ->select(sprintf('(%s + %s) as total_harga', $rumus_dpp, $rumus_ppn))
            ->select('IFNULL(SUM(pbi.qty_diterima), 0) as qty_scan')
            ->select('pb.no_penerimaan_barang') 
            ->select('pb.tanggal_penerimaan')
            ->select('pb.no_plat')
            ->select('e.nama_ekspedisi')
            ->select('pb.no_surat_jalan_ekspedisi')
            ->select('pb.tgl_surat_jalan_ekspedisi')
            ->from('tr_h3_md_fdo_parts as fdo_parts')
            ->join('tr_h3_md_fdo as fdo', 'fdo.id = fdo_parts.invoice_number_int')
            ->join('ms_part as p', 'p.id_part_int = fdo_parts.id_part_int')
            ->join('tr_h3_md_penerimaan_barang_items as pbi', sprintf('(pbi.id_part_int = fdo_parts.id_part_int AND pbi.packing_sheet_number_int = fdo_parts.nomor_packing_sheet_int AND pbi.tersimpan = 1 AND pbi.no_penerimaan_barang_int IN (%s))', $id_penerimaan_barang_yang_closed), 'left')
            ->join('tr_h3_md_penerimaan_barang as pb', 'pb.id = pbi.no_penerimaan_barang_int', 'left')
            ->join('ms_h3_md_ekspedisi as e', 'e.id = pb.id_vendor', 'left')
            ->group_by('fdo.id')
            ->group_by('fdo_parts.id_part_int')
            ->group_by('fdo_parts.nomor_packing_sheet_int')
            ->group_by('pbi.no_penerimaan_barang_int')
            ->order_by('fdo_parts.invoice_number', 'asc')
            ->order_by('pbi.id', 'desc');
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('fdo.invoice_number', $search);
            $this->db->group_end();
        }

        if ($this->input->post('tanggal_faktur_start') != null and $this->input->post('tanggal_faktur_end') != null) {
            $this->db->group_start();
            $this->db->where("fdo.invoice_date between '{$this->input->post('tanggal_faktur_start')}' AND '{$this->input->post('tanggal_faktur_end')}'", null, false);
            $this->db->group_end();
        }

        // if($this->input->post('tanggal_jatuh_tempo_start') != null AND $this->input->post('tanggal_jatuh_tempo_end') != null){
        //     $this->db->group_start();
        //         $this->db->group_start();
        //         $this->db->where("fdo.dpp_due_date between '{$this->input->post('tanggal_jatuh_tempo_start')}' AND '{$this->input->post('tanggal_jatuh_tempo_end')}'", null, false);
        //         $this->db->group_end();
        //         $this->db->or_group_start();
        //         $this->db->where("fdo.ppn_due_date between '{$this->input->post('tanggal_jatuh_tempo_start')}' AND '{$this->input->post('tanggal_jatuh_tempo_end')}'", null, false);
        //         $this->db->group_end();
        //     $this->db->group_end();
        // }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('fdo.created_at', 'desc');
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
        return $this->db->get()->num_rows();
    }

    public function recordsTotal()
    {
        $this->make_query();
        return $this->db->count_all_results();
    }
}
