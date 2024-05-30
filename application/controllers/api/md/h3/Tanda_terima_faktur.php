<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tanda_terima_faktur extends CI_Controller

{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_tanda_terima_faktur', [
                'id' => $row['id']
            ], true);
            $index++;
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
        $terdapat_faktur = $this->db
        ->select('ttfi.id_tanda_terima_faktur')
        ->from('tr_h3_md_tanda_terima_faktur_item as ttfi')
        ->like('ttfi.no_faktur', $this->input->post('no_faktur_filter'))
        ->get_compiled_select();

        $jumlah_faktur = $this->db
        ->select('COUNT(ttfi.no_faktur) as no_faktur', false)
        ->from('tr_h3_md_tanda_terima_faktur_item as ttfi')
        ->where('ttfi.id_tanda_terima_faktur = ttf.id', null, false)
        ->get_compiled_select();

        $jumlah_faktur_lunas = $this->db
        ->select('COUNT(ttfi.no_faktur) as no_faktur', false)
        ->from('tr_h3_md_tanda_terima_faktur_item as ttfi')
        ->join('tr_h3_md_ar_part as ar', 'ar.referensi = ttfi.no_faktur')
        ->where('ttfi.id_tanda_terima_faktur = ttf.id', null, false)
        ->where('ar.lunas', 1)
        ->get_compiled_select();

        $this->db
        ->select('ttf.id')
        ->select('ttf.no_tanda_terima_faktur')
        ->select('d.nama_dealer')
        ->select('date_format(ttf.start_date, "%d-%m-%Y") as start_date')
        ->select('date_format(ttf.end_date, "%d-%m-%Y") as end_date')
        ->select('
            concat(
                "Rp ",
                format(ttf.total, 0, "ID_id")
            ) as total
        ')
        ->select('wp.nama as nama_wilayah_penagihan')
        // ->select("IFNULL(({$jumlah_faktur}), 0) as jumlah_faktur", false)
        // ->select("IFNULL(({$jumlah_faktur_lunas}), 0) as jumlah_faktur_lunas", false)
        ->from('tr_h3_md_tanda_terima_faktur as ttf')
        ->join('ms_dealer as d', 'd.id_dealer = ttf.id_dealer')
        ->join('ms_h3_md_wilayah_penagihan as wp', 'wp.id = ttf.id_wilayah_penagihan');

        if($this->input->post('no_faktur_filter') != null){
            $this->db->where("ttf.id IN ({$terdapat_faktur})", null, false);
        }

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->where("IFNULL(({$jumlah_faktur}), 0) = IFNULL(({$jumlah_faktur_lunas}), 0)", null, false);
        }else{
            $this->db->where("IFNULL(({$jumlah_faktur}), 0) != IFNULL(({$jumlah_faktur_lunas}), 0)", null, false);
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ttf.no_tanda_terima_faktur', $search);
            $this->db->or_like('d.nama_dealer', $search);
            $this->db->or_like('wp.nama', $search);
            $this->db->group_end();
        }

        if ($this->input->post('id_customer_filter')) {
            $this->db->where('ttf.id_dealer', $this->input->post('id_customer_filter'));
        }

        if ($this->input->post('no_tanda_terima_faktur_filter')) {
            $this->db->like('ttf.no_tanda_terima_faktur', trim($this->input->post('no_tanda_terima_faktur_filter')));
        }

        if($this->input->post('tanggal_jatuh_tempo_filter_start') != null and $this->input->post('tanggal_jatuh_tempo_filter_end') != null){            
            $this->db->group_start();
                $this->db->where("ttf.start_date BETWEEN '{$this->input->post('tanggal_jatuh_tempo_filter_start')}' AND '{$this->input->post('tanggal_jatuh_tempo_filter_end')}'", null, false);
                $this->db->or_where("ttf.end_date BETWEEN '{$this->input->post('tanggal_jatuh_tempo_filter_start')}' AND '{$this->input->post('tanggal_jatuh_tempo_filter_end')}'", null, false);
            $this->db->group_end();
        }

        if ($this->input->post('id_wilayah_penagihan_filter')) {
            $this->db->like('ttf.id_wilayah_penagihan', $this->input->post('id_wilayah_penagihan_filter'));
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ttf.created_at', 'desc');
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
