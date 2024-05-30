<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Open_keterangan_monitor_plafon extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = [];
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';

            $index++;
            $data[] = $row;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'data' => $data,
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_total_data(),
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('pb.nomor_bg')
        ->select('pb.nama_bank_bg')
        ->select('pb.tanggal_jatuh_tempo_bg')
        ->select('pbi.jumlah_pembayaran')
        ->from('tr_h3_md_penerimaan_pembayaran_item as pbi')
        ->join('tr_h3_md_penerimaan_pembayaran as pb', 'pb.id_penerimaan_pembayaran = pbi.id_penerimaan_pembayaran')
        ->where('pbi.referensi', $this->input->post('no_faktur'))
        ->where('pb.jenis_pembayaran', 'BG')
        ->group_start()
        ->where('pb.status_bg is null', null, false)
        ->or_where('pb.status_bg', 'Cair')
        ->group_end()
        ->order_by('pb.created_at', 'desc');
    }

    public function make_datatables() {
        $this->make_query();

        // $search = $this->input->post('search')['value'];
        // if ($search != '') {
        //     $this->db->group_start();
        //     $this->db->like('d.nama_dealer', $search);
        //     $this->db->or_like('d.kode_dealer_md', $search);
        //     $this->db->group_end();
        // }
        // if (isset($_POST["order"])) {
        //     $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        // } else {
        //     $this->db->order_by('d.nama_dealer', 'ASC');
        // }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function get_filtered_data() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function get_total_data(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
