<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Do_filter_do_revisi_index extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_do_filter_do_revisi_index', [
                'data' => json_encode($row),
                'id_do_sales_order' => $row['id_do_sales_order']
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
        $do_yang_direvisi = $this->db
            ->select('DISTINCT(dr.id_do_sales_order) as id_do_sales_order')
            ->from('tr_h3_md_do_revisi as dr')
            ->get_compiled_select();

        $this->db
            ->select('do.id_do_sales_order')
            ->select('date_format(do.tanggal, "%d-%m-%Y") as tanggal')
            ->from('tr_h3_md_do_sales_order as do')
            ->where("do.id_do_sales_order IN ({$do_yang_direvisi})", null, false);
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('do.id_do_sales_order', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('do.tanggal', 'desc');
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
        return $this->db->count_all_results();
    }
}
