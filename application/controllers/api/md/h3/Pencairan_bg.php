<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pencairan_bg extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_pencairan_bg', [
                'id_penerimaan_pembayaran' => $row['id_penerimaan_pembayaran']
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
        $this->db
        ->select('ifnull(d.nama_dealer, "-") as nama_dealer')
        ->select('"Part" as jenis_pembayaran')
        ->select('pb.id_penerimaan_pembayaran')
        ->select('pb.nama_bank_bg')
        ->select('pb.nomor_bg')
        ->select('date_format(pb.tanggal_jatuh_tempo_bg, "%d/%m/%Y") as tanggal_jatuh_tempo_bg')
        ->select('
            concat(
                "Rp ",
                format(pb.nominal_bg, 0, "ID_id")
            ) as nominal_bg
        ', false)
        ->select('rek.bank as nama_bank_tujuan')
        ->select('rek.no_rekening as no_rekening_tujuan')
        ->select('pb.status_bg')
        ->from('tr_h3_md_penerimaan_pembayaran as pb')
        ->join('ms_dealer as d', 'd.id_dealer = pb.id_dealer', 'left')
        ->join('ms_rek_md as rek', 'rek.id_rek_md = pb.id_rekening_md_bg')
        ->where('pb.jenis_pembayaran', 'BG')
        ;

        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->where('pb.status_bg', 'Cair');
        }else{
            $this->db->group_start();
            $this->db->where('pb.status_bg', 'Tolak');
            $this->db->or_where('pb.status_bg IS NULL', null, false);
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        // $search = trim($this->input->post('search')['value']);
        // if ($search != '') {
        //     $this->db->like('ttf.no_tanda_terima_faktur', $search);
        //     $this->db->or_like('d.nama_dealer', $search);
        //     $this->db->or_like('wp.nama', $search);
        // }

        if (count($this->input->post('filter_customer')) > 0) {
            $this->db->where_in('pb.id_dealer', $this->input->post('filter_customer'));
        }

        if (count($this->input->post('filter_bank_tujuan')) > 0) {
            $this->db->where_in('pb.id_rekening_md_bg', $this->input->post('filter_bank_tujuan'));
        }

        if($this->input->post('jatuh_tempo_filter') != null){
            $this->db->where('pb.tanggal_jatuh_tempo_bg', $this->input->post('jatuh_tempo_filter'));
        }

        if ($this->input->post('nama_bank_bg_filter')) {
            $this->db->like('pb.nama_bank_bg', trim($this->input->post('nama_bank_bg_filter')));
        }

        if ($this->input->post('no_giro_filter')) {
            $this->db->like('pb.nomor_bg', trim($this->input->post('no_giro_filter')));
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pb.created_at', 'desc');
        }
    }

    public function limit(){
        if ($this->input->post('length') != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered(){
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
