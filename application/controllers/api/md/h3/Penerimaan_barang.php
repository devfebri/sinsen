<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan_barang extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $index = 1;
        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_penerimaan_barang', [
                'id' => $row['no_penerimaan_barang']
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
        ->select('pb.tanggal_penerimaan,pb.no_penerimaan_barang, pb.tgl_surat_jalan_ekspedisi, pb.no_surat_jalan_ekspedisi, pb.no_plat,pb.status')
        ->select('date_format(pb.tanggal_penerimaan, "%d-%m-%Y") as tanggal_penerimaan')
        ->select('date_format(pb.tgl_surat_jalan_ekspedisi, "%d-%m-%Y") as tgl_surat_jalan_ekspedisi')
        ->select('e.nama_ekspedisi as ekspedisi')
        ->from('tr_h3_md_penerimaan_barang as pb')
		->join('ms_h3_md_ekspedisi as e', 'e.id = pb.id_vendor')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pb.no_penerimaan_barang', $search);
            $this->db->or_like('pb.no_surat_jalan_ekspedisi', $search);
            $this->db->group_end();
        }

        if($this->input->post('periode_tanggal_penerimaan_filter_start') != null and $this->input->post('periode_tanggal_penerimaan_filter_end') != null){            
            $this->db->group_start();
            $this->db->where('pb.tanggal_penerimaan >=', $this->input->post('periode_tanggal_penerimaan_filter_start'));
            $this->db->where('pb.tanggal_penerimaan <=', $this->input->post('periode_tanggal_penerimaan_filter_end'));
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else { 
            //
            $this->db->order_by('pb.created_at', 'desc');
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
        return $this->db->get()->num_rows();
    }
}
