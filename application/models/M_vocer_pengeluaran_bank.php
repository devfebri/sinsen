<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_vocer_pengeluaran_bank extends CI_Model
{
    var $table = "tr_voucher_bank";
    var $column_order = array('','id_voucher_bank','account','tgl_entry','dibayar','jenis_bayar','a.pph','total_pembayaran','status'); //field yang ada di table user
    var $column_search = array('id_voucher_bank','account','tgl_entry','dibayar','vendor_name','jenis_bayar','a.pph','total_pembayaran','status'); //field yang diizin untuk pencarian 
    var $order = array('id_voucher_bank' => 'desc'); 

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query(){
        $this->db->select("id_voucher_bank, account, tgl_entry, dibayar, vendor_name, jenis_bayar, a.pph, total_pembayaran,status,tipe_customer");
        $this->db->from('tr_voucher_bank a');
        $this->db->join('ms_vendor b', 'a.dibayar  = b.id_vendor','left');
        
        // join dengan vendor dan dealer

        $i = 0;
        // loop column
        foreach ($this->column_search as $item) {
            if($_POST['search']['value']) // if datatable send POST for search
            {

                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $this->db->where("a.status <> 'batal'");
	
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->select("id_voucher_bank");
        $this->db->from('tr_voucher_bank');	
        $this->db->where("status <> 'batal'");
        return $this->db->count_all_results();
    }
}
