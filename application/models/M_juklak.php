<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_juklak extends CI_Model {

    var $column_order = array(null,'juklakNo','descJuklak'); //set column field database for datatable orderable
    var $column_search = array('juklakNo','descJuklak'); //set column field database for datatable searchable
    var $order = array('created_at' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query(){
        $this->db->select(" (CASE when a.target_penjualan !='' then a.target_penjualan else 0 end) target_penjualan , a.id_program_ahm , a.draft_jutlak, b.programCategory, a.judul_kegiatan, b.juklakNo, a.segment, b.descJuklak , a.id_program_md, b.uniqueCustomer, a.judul_kegiatan , b.subProgram , a.periode_awal as startPeriod , a.periode_akhir as endPeriod, a.jenis, (case when a.kuota_program = 0 then '~' else a.kuota_program end) as kuota_program, a.unique_customer, a.tanggal_maks_po, a.tanggal_maks_bastk, a.kategori_program , c.jenis_sales_program as sub_kategori_program, a.kk_validation, b.statusJuklak , a.created_at , a.updated_at");
        $this->db->from("tr_sales_program a");
        $this->db->join("ms_jenis_sales_program c","a.id_jenis_sales_program = c.id_jenis_sales_program");
        $this->db->join("ms_juklak_ahm b", "a.id_program_ahm = b.juklakNo","left");
        // join dengan tr_sales_program_dealer
        $this->db->where("a.send_dealer=1"); 

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

	    // $this->db->where('send_dealer','1');		

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
        $this->db->select("a.id_program_ahm");
        $this->db->from("tr_sales_program a");
        $this->db->join("ms_juklak_ahm b", "a.id_program_ahm = b.juklakNo","left");
        $this->db->where("a.send_dealer = 1");
        return $this->db->count_all_results();
    }

}
