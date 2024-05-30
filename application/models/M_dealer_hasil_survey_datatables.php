<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_dealer_hasil_survey_datatables extends CI_Model
{
    var $table          = "tr_spk";
    var $column_order   = array('','tr_spk.no_spk','tr_spk.nama_konsumen','tr_spk.alamat','ms_finance_company.finance_company','tr_spk.harga_tunai','tr_spk.tanda_jadi','tr_spk.uang_muka','tr_spk.tenor','tr_hasil_survey.tgl_approval','tr_hasil_survey.status_approval','ms_finance_company.id_finance_company','tr_spk.id_customer'); //field yang ada di table user
    var $column_search  = array('tr_spk.no_spk','tr_spk.nama_konsumen','tr_spk.id_finance_company','tr_spk.harga_tunai','tr_spk.tanda_jadi','tr_spk.uang_muka','tr_spk.tenor','ms_finance_company.finance_company'); //field yang diizin untuk pencarian 
    var $order          = array('tr_spk.updated_at' => 'desc'); 
   
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query($id_dealer)
    {   
       
        $num = array($id_dealer);
 
        $this->db->select('tr_spk.no_spk ,tr_spk.nama_konsumen,tr_spk.alamat,tr_spk.id_finance_company,tr_spk.harga_tunai,tr_spk.tanda_jadi,tr_spk.uang_muka,tr_spk.tenor,tr_spk.id_customer,ms_finance_company.finance_company');
        $this->db->from('tr_spk');
        $this->db->join('ms_finance_company', 'tr_spk.id_finance_company = ms_finance_company.id_finance_company');
        $this->db->where_in('tr_spk.id_dealer', $num);
        $this->db->where("tr_spk.jenis_beli = 'Kredit' AND tr_spk.status_survey='baru' AND tr_spk.status_spk = 'approved'");


        $i = 0;

        foreach ($this->column_search as $item) {
            if($_POST['search']['value']) 
            {
                if($i===0)
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->column_search) - 1 == $i) 
                    $this->db->group_end(); 
            }
            $i++;
        }


        if(isset($_POST['order'])) 
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }

        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }


    function get_datatables($id_dealer)
    {
        $this->_get_datatables_query($id_dealer);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($id_dealer)
    {
        $this->_get_datatables_query($id_dealer);
        $query = $this->db->get();
        return $query->num_rows();

    }

    public function count_all($id_dealer)
    {
        // $this->_get_datatables_query($id_dealer);
     
        $num = array($id_dealer);
        $this->db->select('tr_spk.no_spk','tr_spk.nama_konsumen','tr_spk.id_finance_company','tr_spk.harga_tunai','tr_spk.tanda_jadi','tr_spk.uang_muka','tr_spk.tenor','tr_spk.id_customer');
        $this->db->from('tr_spk');
        $this->db->where_in('tr_spk.id_dealer', $num);
        $this->db->where("tr_spk.jenis_beli = 'Kredit' AND tr_spk.status_survey='baru' AND tr_spk.status_spk ='approved'");
        // $this->db->where_in('tr_spk.status_spk' ,$spk_status);
        return $this->db->count_all_results();

    }

    function get($filter=NULL)
    {
  
      $where = 'WHERE 1=1';
      $where .= "  AND tr_hasil_survey.status_spk = 'lama'";
  
      $select = '';
  
      if (isset($filter['id_dealer'])) {
        if ($filter['id_dealer'] != '') {
          $where .= " AND tr_order_survey.id_dealer = ({$filter['id_dealer']})";
        }
      }
  
  
      if ($filter != null) {
        if (isset($filter['search'])) {
          if ($filter['search'] != '') {
            $filter['search'] = $this->db->escape_str($filter['search']);
            $where .= " AND ( tr_penerimaan_unit_dealer.id_goods_receipt LIKE'%{$filter['search']}%'
                              OR tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer LIKE'%{$filter['search']}%'
                              OR tr_penerimaan_unit_dealer.no_surat_jalan LIKE'%{$filter['search']}%'
                              OR tr_surat_jalan.no_surat_jalan LIKE'%{$filter['search']}%'
                              OR tr_penerimaan_unit_dealer.tgl_penerimaan LIKE'%{$filter['search']}%'
            )";
          }
        }


        if (isset($filter['select'])) {
            $select = "COUNT(tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer) count";
        }
  
      }
  
      $order_data = '';
      if (isset($filter['order'])) {
        $order_column = [null, null];
        $order = $filter['order'];
        if ($order != '') {
          $order_clm  = $order_column[$order['0']['column']];
          $order_by   = $order['0']['dir'];
          $order_data = " ORDER BY $order_clm $order_by ";
        } else {
          $order_data = "ORDER BY tr_hasil_survey.updated_at ASC";
        }
      }
  
      $limit = '';
      if (isset($filter['limit'])) {
        $limit = $filter['limit'];
      }
  
      $group_by = '';
      if (isset($filter['group_by'])) {
        $group_by = "GROUP BY " . $filter['group_by'];
      }
  
     return $this->db->query("SELECT $select
                 FROM tr_hasil_survey INNER JOIN tr_order_survey ON tr_hasil_survey.no_order_survey = tr_order_survey.no_order_survey
      $where
      $group_by
      $order_data
      $limit
      ");
     
    }

    








}

