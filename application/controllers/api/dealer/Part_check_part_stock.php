<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Part_check_part_stock extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_part_check_part_stock', [
                'data' => json_encode($row)
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
        $part_filter_kendaraan = $this->db
        ->select('pvtm.no_part')
        ->from('ms_tipe_kendaraan as tk')
        ->join('ms_ptm as ptm', 'ptm.tipe_marketing = tk.id_tipe_kendaraan')
        ->join('ms_pvtm as pvtm', 'pvtm.tipe_marketing = ptm.tipe_produksi')
        ->where('tk.id_tipe_kendaraan', $this->input->post('id_tipe_kendaraan'))
        ->get()->result_array();
        $part_filter_kendaraan = array_map(function($data){
            return $data['no_part'];
        }, $part_filter_kendaraan);

        if(!$this->config->item('ahm_d_only')){
            $this->db
                ->select('p.*')
                ->select('
                    case 
                        when p.status = "D" then "Discontinue"
                        when p.status = "A" then "Active"
                        else "-"
                    end as status
                ', false)
                ->select('1 as kuantitas')
                ->from('ms_part as p');
                ->where("p.kelompok_part !='FED OIL'");
        }else{
            $this->db
                ->select('p.*')
                ->select('
                    case 
                        when p.status = "D" then "Discontinue"
                        when p.status = "A" then "Active"
                        else "-"
                    end as status
                ', false)
                ->select('1 as kuantitas')
                ->from('ms_part as p');
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        if($this->input->post('id_tipe_kendaraan') != null){
            if(count($part_filter_kendaraan) > 0){
                $this->db->where_in('p.id_part', $part_filter_kendaraan);
            }
        }

        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('p.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'asc');
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
