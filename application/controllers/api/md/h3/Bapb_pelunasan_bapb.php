<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bapb_pelunasan_bapb extends CI_Controller
{

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_bapb_pelunasan_bapb', [
                'data' => json_encode($row),
                'sudah_dibuatkan_pelunasan' => $row['sudah_dibuatkan_pelunasan']
            ], true);
            $data[] = $row;
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
        $terdapat_dipelunasan = $this->db
        ->select('pb.no_pelunasan')
        ->from('tr_h3_md_pelunasan_bapb as pb')
        ->where('pb.no_bapb = ba.no_bapb', null, false)
        ->get_compiled_select();

        $this->db
        ->select('ba.no_bapb')
        ->select('ba.no_surat_jalan_ekspedisi')
        ->select("EXISTS(({$terdapat_dipelunasan})) as sudah_dibuatkan_pelunasan", false)
        ->from('tr_h3_md_berita_acara_penerimaan_barang as ba')
        ->where("NOT EXISTS(({$terdapat_dipelunasan}))", null, false)
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
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
            $this->db->order_by('ba.no_bapb', 'desc');
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