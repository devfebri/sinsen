<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Serial_number_sales_order extends CI_Controller
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
            // $row['action'] = $this->load->view('additional/action_rak_parts_sales_order_datatable', [
            $row['action'] = $this->load->view('additional/action_serial_number_sales_order_datatable', [
                'data' => json_encode($row),
                'serial_number' => $row['serial_number']
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
        ->select('et.serial_number')
        ->select('et.id_gudang_dealer')
        ->select('et.id_lokasi_rak_dealer')
        ->from('tr_h3_serial_ev_tracking as et')
        ->where('et.id_dealer', $this->m_admin->cari_dealer())
        ->where('et.accStatus', 4)
        ->where('et.id_part_int', $this->input->post('id_part_int'))
        // ->where('et.id_lokasi_rak_dealer', $this->input->post('id_rak'))
        ->group_start()
        ->where('et.no_so_wo_booking', null)
        ->or_where('et.no_so_wo_booking', '')
        ->or_where('et.no_so_wo_booking', $this->input->post('nomor_so'))
        ->group_end()
        ->group_start()
        ->where('et.id_penerimaan_dealer !=',null)
        ->or_where('et.id_penerimaan_dealer !=','')
        ->group_end()
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('et.serial_number', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('et.fifo', 'ASC');
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