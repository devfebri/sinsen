<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Referensi_po_hotline extends CI_Controller
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
        foreach ($this->db->get()->result_array() as $record) {
            $record['action'] = $this->load->view('additional/md/h3/action_referensi_po_hotline', [
                'data' => json_encode($record),
                'referensi' => $record['referensi']
            ], true);
            $data[] = $record;
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
        $purchase_sudah_setting_pemenuhan_urgent = $this->db
        ->select('distinct(ppdd.po_id)')
        ->from('tr_h3_md_pemenuhan_po_dari_dealer as ppdd')
        ->where('ppdd.qty_urgent >', 0)
        ->get_compiled_select();
        ;

        $purchase_sudah_setting_pemenuhan_hotline = $this->db
        ->select('distinct(ppdd.po_id)')
        ->from('tr_h3_md_pemenuhan_po_dari_dealer as ppdd')
        ->where('ppdd.qty_hotline >', 0)
        ->get_compiled_select();
        ;

        $referensi_sudah_terpakai = $this->db
        ->select('DISTINCT(pop_sq.referensi)')
        ->from('tr_h3_md_purchase_order as po_sq')
        ->join('tr_h3_md_purchase_order_parts as pop_sq', 'pop_sq.id_purchase_order = po_sq.id_purchase_order')
        ->where('po_sq.status', 'Waiting Approval')
        ->where('po_sq.jenis_po', $this->input->post('jenis_po'))
        ->get_compiled_select();

		$this->db
		->select('po.po_id as referensi')
        ->select('po.po_type')
		->select('d.id_dealer')
		->select('d.nama_dealer')
		->select('po.created_at')
		->from('tr_h3_dealer_purchase_order as po')
        ->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
        ->group_start()
        ->where('po.po_type', 'HLO')
        ->or_where('po.po_type', 'URG')
        ->group_end()
        ->where('po.status', 'Processed By MD')
        ->where('po.created_by_md', 0)
        ->where("po.po_id NOT IN ({$referensi_sudah_terpakai})", null, false)
        ;

        if($this->input->post('jenis_po') == 'URG'){
            $this->db->group_start();
            $this->db->where("po.po_id in ({$purchase_sudah_setting_pemenuhan_urgent})", null, false);
            $this->db->or_where("po.po_id in ({$purchase_sudah_setting_pemenuhan_hotline})", null, false);
            $this->db->group_end();
        }elseif($this->input->post('jenis_po') == 'HTL'){
            $this->db->where("po.po_id in ({$purchase_sudah_setting_pemenuhan_hotline})", null, false);
        }
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
            $this->db->order_by('po.proses_at', 'desc');
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