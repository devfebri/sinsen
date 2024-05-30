<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Open_view_surat_jalan_ahm_berita_acara_penerimaan_barang extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_ekspedisi_berita_acara_penerimaan_barang', [
                'data' => json_encode($row)
            ], true);

            $row['index'] = $this->input->post('start') + $index;
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
        $this->db
        ->select('DISTINCT(pbi.surat_jalan_ahm) as surat_jalan_ahm')
        ->from('tr_h3_md_berita_acara_penerimaan_barang as bapb')
        ->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_surat_jalan_ekspedisi = bapb.no_surat_jalan_ekspedisi')
        ->join('tr_h3_md_penerimaan_barang_items as pbi', 'pbi.no_penerimaan_barang = pb.no_penerimaan_barang')
        ->where('bapb.no_bapb', $this->input->post('no_bapb'))
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pbi.surat_jalan_ahm', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pbi.surat_jalan_ahm', 'asc');
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