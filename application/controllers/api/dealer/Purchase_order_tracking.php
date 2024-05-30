<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_order_tracking extends CI_Controller
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
            $row['action'] = $this->load->view('additional/action_purchase_order_tracking', [
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
        $this->db
        ->select('po.*')
        ->select("
            case
                when po.id_booking is not null then c.nama_customer
                else '---'
            end as nama_customer
        ")
        ->select("
            case
                when po.id_booking is not null then c.no_hp
                else '---'
            end as no_hp
        ")
        ->select("
            case
                when po.id_booking is not null then c.id_tipe_kendaraan
                else '---'
            end as id_tipe_kendaraan
        ")
        ->select("
            case
                when po.id_booking is not null then c.no_mesin
                else '---'
            end as no_mesin
        ")
        ->select('date_format(po.tanggal_order, "%d-%m-%Y") as tanggal_order')
        ->from('tr_h3_dealer_purchase_order as po')
		->join('tr_h3_dealer_request_document as rd', 'po.id_booking = rd.id_booking', 'left')
		->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer', 'left')
        ->where('po.id_dealer', $this->m_admin->cari_dealer())
        ->where('po.status !=', 'Canceled')
        ->group_start()
		->where('po.order_to', null)
		->or_where('po.order_to', 0)
        ->group_end()
        ->where_in('po.status', ['Submitted', 'Processed by MD', 'Closed'])
        ;

        if($this->input->post('date_range') != null){
            $this->db->where("po.tanggal_order between '{$this->input->post('tanggal_po_start')}' and '{$this->input->post('tanggal_po_end')}'");
        }

        if($this->input->post('filter_tipe') != null){
            $this->db->where('po.po_type', $this->input->post('tipe_po'));
        }

        if ($this->input->post('kategori_po') != null) {
            if($this->input->post('kategori_po') == 'KPB'){
                $this->db->where('po.kategori_po', 'KPB');
            }
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('po.po_id', $search);
            $this->db->or_like('c.nama_customer', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('po.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered(){
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}