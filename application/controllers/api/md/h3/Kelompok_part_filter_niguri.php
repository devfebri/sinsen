<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelompok_part_filter_niguri extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        set_time_limit(0);
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();
        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row = (array) $row;
            $row['action'] = $this->load->view('additional/md/h3/action_kelompok_part_filter_niguri', [
                'data' => json_encode($row),
                'id_kelompok_part' => $row['id_kelompok_part']
            ], true);
            $data[] = $row;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_record_total(),
            'data' => $data
        ]);
    }

    public function make_query()
    {
        $kelompok_part_di_niguri = [];
        $this->db
            ->select('DISTINCT(p.kelompok_part_int) as kelompok_part_int', false)
            ->from('tr_h3_md_niguri as n')
            ->join('ms_part as p', 'p.id_part_int = n.id_part')
            ->where('n.id_niguri_header', $this->input->post('id_niguri'));

        $kelompok_part_di_niguri = array_map(function ($row) {
            return $row['kelompok_part_int'];
        }, $this->db->get()->result_array());

        $this->db
            ->select('kp.id_kelompok_part')
            ->from('ms_kelompok_part as kp')
            ->where('kp.active', 1);

        if (count($kelompok_part_di_niguri) > 0) {
            $this->db->where_in('kp.id', $kelompok_part_di_niguri);
        } else {
            $this->db->where('1=0', null, false);
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('kp.id_kelompok_part', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('kp.id_kelompok_part', 'ASC');
        }
    }

    public function limit()
    {
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function get_filtered_data()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function get_record_total()
    {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
