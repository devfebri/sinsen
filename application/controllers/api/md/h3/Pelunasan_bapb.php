<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pelunasan_bapb extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_pelunasan_bapb', [
                'id' => $row['no_pelunasan']
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
        ->select('sum(pli.qty_rusak)')
        ->from('tr_h3_md_pelunasan_bapb_items pli')
        ->where('pli.no_pelunasan = pl.no_pelunasan')
        ->get_compiled_select();

        $count_surat_jalan_ahm = $this->db
        ->select('count(pbsj.surat_jalan_ahm)')
        ->from('tr_h3_md_penerimaan_barang_surat_jalan_ahm as pbsj')
        ->where('pbsj.no_surat_jalan_ekspedisi = ba.no_surat_jalan_ekspedisi', null, false)
        ->get_compiled_select();

        $count_packing_sheet_number = $this->db
        ->select('COUNT(
            DISTINCT(pli.packing_sheet_number)
        )')
        ->from('tr_h3_md_pelunasan_bapb_items as pli')
        ->where('pli.no_pelunasan = pl.no_pelunasan', null, false)
        ->get_compiled_select();

        $this->db
        ->select('pl.no_pelunasan')
        ->select('date_format(pl.tanggal_pelunasan, "%d/%m/%Y") as tanggal_pelunasan')
        ->select('ba.no_bapb')
        ->select('ba.no_surat_jalan_ekspedisi')
        ->select("IFNULL(({$qty_rusak}), 0) as qty_rusak", false)
        ->select("IFNULL(({$count_surat_jalan_ahm}), 0) as count_surat_jalan_ahm", false)
        ->select("IFNULL(({$count_packing_sheet_number}), 0) as count_packing_sheet_number", false)
        ->from('tr_h3_md_pelunasan_bapb as pl')
        ->join('tr_h3_md_berita_acara_penerimaan_barang as ba', 'ba.no_bapb = pl.no_bapb')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pl.no_pelunasan', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pl.no_pelunasan', 'ASC');
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