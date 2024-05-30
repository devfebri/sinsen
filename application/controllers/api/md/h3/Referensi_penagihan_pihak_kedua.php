<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Referensi_penagihan_pihak_kedua extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/action_referensi_penagihan_pihak_kedua', [
                'row' => $row
            ], true);

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
    
    public function make_query() {
        $this->db
        ->select('ppi.referensi')
        ->select('ppi.tipe_transaksi as tipe_referensi')
        ->select('d.nama_dealer as nama_vendor')
        ->select('ppi.jumlah_pembayaran as nominal_pembayaran')
        ->select('pp.nomor_bg')
        ->select('pp.nominal_bg')
        ->select('"-" as divisi')
        ->from('tr_h3_md_penerimaan_pembayaran_item ppi')
        ->join('tr_h3_md_penerimaan_pembayaran as pp', 'pp.id_penerimaan_pembayaran = ppi.id_penerimaan_pembayaran')
        ->join('ms_dealer as d', 'd.id_dealer = pp.id_dealer', 'left')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pp.referensi', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pp.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsTotal() {
        $this->make_query();

        return $this->db->get()->num_rows();
    }

    public function recordsFiltered() {
        $this->make_datatables();

        return $this->db->get()->num_rows();
    }
}
