<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Berita_acara_penyerahan_faktur extends CI_Controller

{
    public function index()
    {
        $this->make_datatables();
        $this->limit(1);

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_berita_acara_penyerahan_faktur', [
                'no_bap' => $row['no_bap']
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
        $this->db
        ->select('bapf.no_bap')
        ->select('date_format(bapf.end_date, "%d-%m-%Y") as end_date')
        ->select('debt_collector.nama_lengkap')
        ->select('
            concat(
                "Rp ",
                format(bapf.total, 0, "ID_id")
            ) as total
        ')
        ->from('tr_h3_md_berita_acara_penyerahan_faktur as bapf')
        ->join('ms_karyawan as debt_collector', 'debt_collector.id_karyawan = bapf.id_debt_collector')
        ;
        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->where('left(bapf.created_at,10) <=', '2023-09-30');
            $this->db->group_end();
        } else {
            $this->db->group_start();
            $this->db->where('left(bapf.created_at,10) >', '2023-10-01');
            $this->db->group_end();
        }

        if ($this->input->post('no_bap_filter')) {
            $this->db->like('bapf.no_bap', trim($this->input->post('no_bap_filter')));
        }

        if($this->input->post('tanggal_jatuh_tempo_filter_start') != null and $this->input->post('tanggal_jatuh_tempo_filter_end') != null){            
            $this->db->group_start();
            $this->db->where("bapf.end_date between '{$this->input->post('tanggal_jatuh_tempo_filter_start')}' AND '{$this->input->post('tanggal_jatuh_tempo_filter_end')}'", null, false);
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('bapf.created_at', 'desc');
        }
    }

    public function limit(){
        if ($this->input->post('length') != - 1) {
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
        return $this->db->count_all_results();
    }
}
