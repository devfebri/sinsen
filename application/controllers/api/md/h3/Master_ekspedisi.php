<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_ekspedisi extends CI_Controller

{
    public function index()
    {
        $records = $this->make_datatables();
        $data = array();
        foreach ($records as $each) {
            $sub_arr = (array) $each;
            $sub_arr['action'] = $this->load->view('additional/md/h3/action_index_h3_md_master_ekspedisi', [
                'id' => $each->id
            ], true);

            $produk_angkutan = $this->db
            ->from('ms_h3_md_ekspedisi_item as ei')
            ->where('ei.id_ekspedisi', $each->id)
            ->group_by('ei.produk_angkatan')
            ->order_by('ei.produk_angkatan', 'asc')
            ->get()->result();

            $produk_angkutan_string = '';
            foreach ($produk_angkutan as $each_produk_angkutan) {
                $produk_angkutan_string .= "/{$each_produk_angkutan->produk_angkatan}";
            }
            $sub_arr['produk_angkutan'] = substr($produk_angkutan_string, 1);
            $data[] = $sub_arr;
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
        $produk_angkatan = $this->db
        ->select('count(ei.id)')
        ->from('ms_h3_md_ekspedisi_item as ei')
        ->where('ei.id_ekspedisi = e.id')
        ->get_compiled_select();

        $this->db
        ->select('e.id')
        ->select('e.nama_ekspedisi')
        ->select('e.nama_pemilik')
        ->select('e.alamat')
        ->select('e.no_telp')
        ->select("({$produk_angkatan}) as produk_angkatan")
        ->from('ms_h3_md_ekspedisi as e')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->like('e.nama_ekspedisi', $search);
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('e.created_at', 'desc');
        }

        if ($this->input->post('length') != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function recordsFiltered()
    {
        $this->make_query();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
