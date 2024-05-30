<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Berita_acara extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_berita_acara_penerimaan_barang', [
                'id' => $row['no_bapb']
            ], true);
            $row['index'] = $this->input->post('start') + $index . '.';

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
        $qty_rusak = $this->db
        ->select('SUM(bai.qty_rusak) as qty_rusak')
        ->from('tr_h3_md_berita_acara_penerimaan_barang_items as bai')
        ->where('bai.no_bapb = ba.no_bapb', null, false)
        ->get_compiled_select();

        $jumlah_surat_jalan = $this->db
        ->select('COUNT(DISTINCT(bai.surat_jalan_ahm)) as surat_jalan_ahm')
        ->from('tr_h3_md_berita_acara_penerimaan_barang_items as bai')
        ->where('bai.no_bapb = ba.no_bapb', null, false)
        ->get_compiled_select();

        $jumlah_packing_sheet = $this->db
        ->select('COUNT(DISTINCT(bai.packing_sheet_number)) as packing_sheet_number')
        ->from('tr_h3_md_berita_acara_penerimaan_barang_items as bai')
        ->where('bai.no_bapb = ba.no_bapb', null, false)
        ->get_compiled_select();

        $this->db
        ->select('ba.no_bapb')
        ->select('ba.no_surat_jalan_ekspedisi')
        ->select("IFNULL(({$qty_rusak}), 0) as qty_rusak", false)
        ->select("IFNULL(({$jumlah_packing_sheet}), 0) as jumlah_packing_sheet", false)
        ->select("IFNULL(({$jumlah_surat_jalan}), 0) as jumlah_surat_jalan", false)
        ->from('tr_h3_md_berita_acara_penerimaan_barang as ba')
        ->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_surat_jalan_ekspedisi = ba.no_surat_jalan_ekspedisi')
		->join('tr_h3_md_sales_order as so', '(so.no_bapb = ba.no_bapb and so.status != "Canceled")', 'left')
        ;

        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->where('so.id_sales_order IS NOT NULL', null, false);
        }else{
            $this->db->where('so.id_sales_order IS NULL', null, false);
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ba.no_bapb', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ba.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}