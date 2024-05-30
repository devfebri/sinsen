<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Po_hotline_purchase extends CI_Controller
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
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/md/h3/action_referensi_po_hotline_purchase_order', [
                'data' => json_encode($row),
            ], true);
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
        $purchase_sudah_setting_pemenuhan_hotline = $this->db
        ->select('distinct(ppdd.po_id)')
        ->from('tr_h3_md_pemenuhan_po_dari_dealer as ppdd')
        ->where('ppdd.qty_hotline >', 0)
        ->get_compiled_select();
        ;

        $referensi_sudah_terpakai = $this->db
        ->select('po_sq.referensi_po_hotline')
        ->from('tr_h3_md_purchase_order as po_sq')
        ->where('po_sq.status', 'Waiting Approval')
        ->where('po_sq.jenis_po', 'HTL')
        ->get_compiled_select();

		$this->db
		->select('po.po_id as referensi')
        ->select('po.po_type')
		->select('d.id_dealer')
		->select('d.nama_dealer')
		->select('po.created_at')
		->select('c.nama_customer')
		->from('tr_h3_dealer_purchase_order as po')
        ->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
        ->where('po.po_type', 'HLO')
        ->where('po.status', 'Processed By MD')
        ->where('po.created_by_md', 0)
        ->where("po.po_id NOT IN ({$referensi_sudah_terpakai})", null, false)
        ->where("po.po_id in ({$purchase_sudah_setting_pemenuhan_hotline})", null, false);
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('po.po_id', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('po.po_id', 'asc');
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