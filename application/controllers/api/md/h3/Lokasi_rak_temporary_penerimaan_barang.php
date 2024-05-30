<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Lokasi_rak_temporary_penerimaan_barang extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        ini_set('max_execution_time', '0');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_lokasi_rak_temporary_penerimaan_barang', [
                'data' => json_encode($row)
            ], true);

            $row['view_stock'] = $this->load->view('additional/md/h3/action_view_stock_lokasi_temporary_penerimaan_barang', [
                'id' => $row['id']
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
        $kapasitas_yang_diperlukan = $this->input->post('kapasitas_yang_diperlukan') != null ? $this->input->post('kapasitas_yang_diperlukan') : 0;

        $this->db 
        ->select('lr.id')
        ->select('lr.kode_lokasi_rak')
        ->select('lr.deskripsi')
        ->select('lr.kapasitas')
        ->select('lr.kapasitas_terpakai')
        ->select('(lr.kapasitas - lr.kapasitas_terpakai) as kapasitas_tersedia')
        ->select('g.kode_gudang')
        ->from('ms_h3_md_lokasi_rak as lr')
        ->join('ms_h3_md_gudang as g', 'g.id = lr.id_gudang')
        ->where('lr.id !=', $this->input->post('selain_id_lokasi_rak'))
        ->where("( lr.kapasitas - lr.kapasitas_terpakai ) > {$kapasitas_yang_diperlukan}", null, false)
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('lr.kode_lokasi_rak', $search);
            $this->db->or_like('g.kode_gudang', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('lr.kode_lokasi_rak', 'asc');
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