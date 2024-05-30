<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Record_demand extends CI_Controller {

    public $total_lost = 0;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index() {
        $this->make_datatables(); $this->limit();

        $data = [];
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['note_field'] = htmlspecialchars($row['note_field'], ENT_QUOTES, 'UTF-8');
            $data[] = $row;
            $index++;
        }

        send_json(
            array(
                'draw' => intval($this->input->post('draw')), 
                'recordsFiltered' => $this->recordsFiltered(), 
                'recordsTotal' => $this->recordsTotal(),
                'total_lost' => $this->total_lost,
                'data' => $data
            )
        );
    }


    public function make_query() {
        $this->db
        ->select('rd.*')
        ->select('date_format(rd.created_at, "%d/%m/%Y") as created_at')
        ->select('(rd.qty * rd.harga_satuan) as lost_of_sales')
        ->select('p.nama_part')
        ->from('tr_h3_dealer_record_reasons_and_parts_demand as rd')
        ->join('ms_part as p', 'p.id_part = rd.id_part')
        ->where('rd.id_dealer', $this->m_admin->cari_dealer())
        ;
    }

    public function make_datatables(){
        $this->make_query();

        if($this->input->post('filter_part_record_demand') != null){
            $this->db->where('rd.id_part', $this->input->post('filter_part_record_demand'));
        }

        if($this->input->post('filter_date_record_demand') != null){
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');

            $this->db->where("DATE_FORMAT(rd.created_at, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'");
        }

        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('rd.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('rd.id_part', 'DESC');
        }
    }

    public function limit() {
        if ($this->input->post('length') != - 1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
    }

    public function recordsFiltered() {
        $this->make_datatables();

        $count = 0;
        foreach ($this->db->get()->result_array() as $row) {
            $this->total_lost += (double) $row['lost_of_sales'];
            $count++;
        }
        return $count;
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}