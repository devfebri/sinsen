<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Part_kelompok_part extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('Mcarbon');
        $this->load->model('H3_md_stock_int_model', 'stock');
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['stock_avs'] = $this->stock->qty_avs($row['id_part_int']);

            $row['action'] = $this->load->view('additional/md/h3/action_part_kelompok_part', [
                'data' => json_encode($row),
                'id_part' => $row['id_part']
            ], true);

            for ($i=1; $i <= 6; $i++) { 
                $start_date = Mcarbon::now()->startOfMonth()->subMonth(1)->toDateString();
                $end_date = Mcarbon::now()->startOfMonth()->subMonth(1)->endOfMonth()->toDateString();

                $parts_terjual = $this->db
                ->select('IFNULL(SUM(sop.qty_order), 0) AS qty')
                ->from('tr_h3_md_sales_order_parts as sop')
                ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = sop.id_sales_order')
                ->where('sop.id_part', $row['id_part'])
                ->group_start()
                ->where("so.tanggal_order between '{$start_date}' AND '{$end_date}'", null, false)
                ->group_end()
                ->get()->row_array();

                $row['m_' . $i] = $parts_terjual['qty'];
            }

            $index++;
            $data[] = $row;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_total_data(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('p.id_part_int')
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('p.status')
        ->select('p.harga_dealer_user as het')
        ->select('0 as qty_keep_stock')
        ->from('ms_part as p')
        ->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
        ->where('kp.id_kelompok_part', $this->input->post('id_kelompok_part'))
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('p.nama_part', $search);
            $this->db->or_like('p.id_part', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.nama_part', 'ASC');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function get_filtered_data() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function get_total_data() {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
