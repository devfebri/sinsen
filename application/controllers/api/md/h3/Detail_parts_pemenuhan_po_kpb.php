<?php

use PhpOffice\PhpSpreadsheet\Worksheet\Row;

defined('BASEPATH') or exit('No direct script access allowed');

class Detail_parts_pemenuhan_po_kpb extends CI_Controller
{
    public function index()
    {
		$this->load->model('H3_md_stock_int_model', 'stock_int');
        $this->load->model('h3_md_ms_diskon_oli_kpb_model', 'diskon_oli_kpb');

        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        $this->benchmark->mark('data_start');
        foreach ($this->db->get()->result_array() as $row) {
            if ($row['id_part_h3'] != null and $row['id_part_h3'] != '') {
                $row['qty_avs'] = $this->stock_int->qty_avs($row['id_part_h3_int']);
            } else {
                $row['qty_avs'] = $this->stock_int->qty_avs($row['id_part_int']);
            }

            $diskon_oli_kpb = $this->diskon_oli_kpb->get_diskon_oli_kpb($row['id_part'], $row['id_tipe_kendaraan']);
            if ($diskon_oli_kpb != null) {
                $row['tipe_diskon'] = $diskon_oli_kpb['tipe_diskon'];
                $row['diskon_value'] = $diskon_oli_kpb['diskon_value'];
            } else {
                $row['tipe_diskon'] = '';
                $row['diskon_value'] = 0;
            }
            $row['index'] = $this->input->post('start') + $index . '.';

            $row['id_part_h3_input'] = $this->load->view('additional/md/h3/action_part_h3_detail_parts_pemenuhan_po_kpb', [
                'id_detail' => $row['id_detail'],
                'tipe_produksi' => $row['tipe_produksi'],
                'id_part_h3' => $row['id_part_h3'],
            ], true);

            $data[] = $row;
            $index++;
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
        $this->db
            ->select('pod.id_detail')
            ->select('pod.id_part')
            ->select('pod.id_part_int')
            ->select('pod.id_part_h3')
            ->select('pod.id_part_h3_int')
            ->select('pt.tipe_produksi')
            ->select('pod.id_tipe_kendaraan')
            ->select('p.nama_part')
            ->select('pod.qty as qty_order')
            ->select('pod.qty as qty_pemenuhan')
            ->select('p.harga_dealer_user as harga')
            ->select('(pod.harga_material - pod.diskon) as harga_kpb', false)
            ->select('kp.keep_stock_toko')
            ->select('kp.keep_stock_dealer')
            ->select('"" as tipe_diskon')
            ->select('0 as diskon_value')
            ->from('tr_po_kpb_detail as pod')
            ->join('ms_part as p', 'p.id_part_int = pod.id_part_int')
            ->join('ms_kelompok_part as kp', 'kp.id = p.kelompok_part_int')
            ->join('ms_ptm as pt', 'pt.tipe_marketing = pod.id_tipe_kendaraan')
            ->join('ms_kpb as kpb', 'kpb.id_tipe_kendaraan = pod.id_tipe_kendaraan')
            ->where('pod.id_po_kpb', $this->input->post('id_po_kpb'));
    }

    public function make_datatables()
    {
        $this->make_query();
    }

    public function limit()
    {
        if ($this->input->post('length') != -1) {
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
