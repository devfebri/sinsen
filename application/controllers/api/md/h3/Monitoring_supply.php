<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_supply extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('h3_md_sales_order_model', 'sales_order');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['amount_supply'] = $this->sales_order->get_sub_total_do($row['id_sales_order']);
            $row['service_rate'] = round(
                ($row['amount_supply'] / $row['total_amount']) * 100,
                1
            ) . "%";
            $row['list_do'] = $this->load->view('additional/action_list_do_monitoring_supply', [
                'id_sales_order' => $row['id_sales_order'],
            ], true);

            $row['id_sales_order'] = $this->load->view('additional/action_open_so_monitoring_supply', [
                'id_sales_order' => $row['id_sales_order'],
            ], true);

            $row['index'] = $this->input->post('start') + $index;

            $data[] = $row;
            $index++;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
        ]);
    }

    public function make_query()
    {
        $this->db->reset_query();

        $this->db
        ->select('so.id_sales_order')
        ->select('d.nama_dealer')
        ->select('d.kode_dealer_md')
        ->select('so.total_amount')
        ->select('so.status')
        ->select('date_format(so.tanggal_order, "%d/%m/%Y") as tanggal_order')
        ->select('kab.kabupaten')
        ->from('tr_h3_md_sales_order as so')
        ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
        ->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan', 'left')
        ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
        ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
        ;

        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->group_start();
                $this->db->where('left(so.created_at,10) <=', '2023-09-30');
                // $this->db->or_where('left(dso.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }else{
            $this->db->group_start();
                $this->db->where('left(so.created_at,10) >', '2023-10-01');
                    // $this->db->where('dso.status =', 'On Process');
                    // $this->db->or_where('left(so.created_at,10) <=', '2023-09-08');
            $this->db->group_end();
        }

        if ($this->input->post('id_customer_filter')) {
            $this->db->where('so.id_dealer', $this->input->post('id_customer_filter'));
        }

        if ($this->input->post('id_salesman_filter')) {
            $this->db->where('so.id_salesman', $this->input->post('id_salesman_filter'));
        }

        if ($this->input->post('no_so_filter')) {
            $this->db->like('so.id_sales_order', $this->input->post('no_so_filter'));
        }

        if ($this->input->post('alamat_customer_filter')) {
            $this->db->like('d.alamat', $this->input->post('alamat_customer_filter'));
        }

        $periode_po_filter_start = $this->input->post('periode_po_filter_start');
        $periode_po_filter_end = $this->input->post('periode_po_filter_end');
        if($periode_po_filter_start != null and $periode_po_filter_end != null){            
            $this->db->group_start();
            $this->db->where(sprintf('so.tanggal_order between "%s" and "%s"', $periode_po_filter_start, $periode_po_filter_end), null, false);
            $this->db->group_end();
        }

        
    }

    public function make_datatables()
    {
        $this->make_query();

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('so.created_at', 'desc');
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
