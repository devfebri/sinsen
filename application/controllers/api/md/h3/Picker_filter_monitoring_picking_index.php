<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Picker_filter_monitoring_picking_index extends CI_Controller
{

    public function index()
    {
        $this->benchmark->mark('data_start');
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_picker_filter_monitoring_picking_index', [
                'data' => json_encode($row),
                'id_karyawan' => $row['id_karyawan']
            ], true);

            $data[] = $row;
        }
        $this->benchmark->mark('data_end');

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsFiltered_time' => floatval($this->benchmark->elapsed_time('recordsFiltered_start', 'recordsFiltered_end')),
            'recordsTotal' => $this->recordsTotal(),
            'recordsTotal_time' => floatval($this->benchmark->elapsed_time('recordsTotal_start', 'recordsTotal_end')),
            'data' => $data,
            'data_time' => floatval($this->benchmark->elapsed_time('data_start', 'data_end'))
        ]);
    }

    public function make_query()
    {
        $this->db
            ->select('k.id_karyawan')
            ->select('k.nama_lengkap')
            ->from('ms_karyawan as k')
            ->where('k.id_jabatan', 'JBT-054');
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('k.nama_lengkap', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('k.nama_lengkap', 'asc');
        }
    }

    public function limit()
    {
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->benchmark->mark('recordsFiltered_start');
        $this->make_datatables();
        $record = $this->db->count_all_results();
        $this->benchmark->mark('recordsFiltered_end');

        return $record;
    }

    public function recordsTotal()
    {
        $this->benchmark->mark('recordsTotal_start');
        $this->make_query();
        $record = $this->db->count_all_results();
        $this->benchmark->mark('recordsTotal_end');

        return $record;
    }
}
