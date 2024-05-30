<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H2_dealer_history_fu_datatables extends CI_Model
{
    var $table = "tr_log_generate_customer_list_fol_up as a";
    var $column_order = array(null,'a.id_follow_up','a.id_customer','nama_pengguna','media_kontak', 'd.tgl_fol_up','d.tgl_booking_service','closed_at','status_fol_up', null );
    var $column_search = array('a.id_follow_up','a.id_customer','d.nama_customer','e.nama','a.tgl_fol_up','a.tgl_booking_service');
    var $order = array('a.tgl_fol_up' => 'desc');
   
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_history_query()
    {
        $date = new DateTime("now");

        $curr_date = $date->format('Y-m-d ');
        $id_dealer = $this->m_admin->cari_dealer();
        $this->db->select('a.id_follow_up,a.id_customer, a.tgl_fol_up,a.tgl_booking_service,(case when a.hasil_fol_up ="closed" then a.tgl_actual_service else "-" END) as closed_at,(case when a.hasil_fol_up ="closed" then a.biaya_actual_service else 0 END) as total_jasa,(CASE WHEN b.tipe_coming like "%milik%" then d.nama_customer else e.nama END) as nama_pengguna,
        (case when a.id_media_kontak_fol_up="1" THEN "Telepon"
         when a.id_media_kontak_fol_up="2" THEN "Telepon/WA Call"
         when a.id_media_kontak_fol_up="3" THEN "WA"
         when a.id_media_kontak_fol_up="4" THEN "SMS"
         when a.id_media_kontak_fol_up="5" THEN "Visit"
         when a.id_media_kontak_fol_up="6" THEN "Facebook"
         when a.id_media_kontak_fol_up="7" THEN "Instagram"
         when a.id_media_kontak_fol_up="8" THEN "Telegram"
         when a.id_media_kontak_fol_up="9" THEN "Twitter"
         when a.id_media_kontak_fol_up="10" THEN "Email" END) as media_kontak,
         (case when a.hasil_fol_up="closed" then "Selesai" 
         when a.hasil_fol_up in ("cancel","canceled") then "Batal Service"
         when a.hasil_fol_up in ("open","pending","pause") then "Sedang dikerjakan"
         else "Konsumen Tidak Datang" END) as status_fol_up');
         $this->db->from('tr_h2_fol_up_header f');
         $this->db->join('tr_h2_fol_up_detail a','f.id_follow_up=a.id_follow_up');
         $this->db->join('tr_h2_sa_form b','a.id_customer=b.id_customer');
          // $this->db->join('tr_h2_wo_dealer c','c.id_sa_form=b.id_sa_form');
          $this->db->join('ms_customer_h23 d','d.id_customer=a.id_customer');
          $this->db->join('ms_h2_pembawa e','e.id_pembawa=b.id_pembawa','left');
          $this->db->where('a.is_done=','1');
          $this->db->where('a.id_dealer =',$id_dealer);
          $this->db->group_by('a.id_follow_up');

        $i = 0;
        foreach ($this->column_search as $item) // looping awal
        {
          if($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
          {
            if($i===0) // looping awal
            {
              $this->db->group_start(); 
              $this->db->like($item, $_POST['search']['value']);
            }
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
        if(isset($this->order))
        {
          $order = $this->order;
          $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function getDataTableHistory()
    {
      $this->_get_datatables_history_query();
      if($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
      $query = $this->db->get();
      return $query->result();
    }
 
    function count_filtered_history()
    {
      $this->_get_datatables_history_query();
      $query = $this->db->get();
      return $query->num_rows();
    }
 
    public function count_all_history()
    {
      $id_dealer = $this->m_admin->cari_dealer();
      $this->db->select('a.id_follow_up,a.id_customer, a.tgl_fol_up,a.tgl_booking_service,(case when a.hasil_fol_up ="closed" then a.tgl_actual_service else "-" END) as closed_at,(case when a.hasil_fol_up ="closed" then a.biaya_actual_service else 0 END) as total_jasa,(CASE WHEN b.tipe_coming like "%milik%" then d.nama_customer else e.nama END) as nama_pengguna,
        (case when a.id_media_kontak_fol_up="1" THEN "Telepon"
         when a.id_media_kontak_fol_up="2" THEN "Telepon/WA Call"
         when a.id_media_kontak_fol_up="3" THEN "WA"
         when a.id_media_kontak_fol_up="4" THEN "SMS"
         when a.id_media_kontak_fol_up="5" THEN "Visit"
         when a.id_media_kontak_fol_up="6" THEN "Facebook"
         when a.id_media_kontak_fol_up="7" THEN "Instagram"
         when a.id_media_kontak_fol_up="8" THEN "Telegram"
         when a.id_media_kontak_fol_up="9" THEN "Twitter"
         when a.id_media_kontak_fol_up="10" THEN "Email" END) as media_kontak,
         (case when a.hasil_fol_up="closed" then "Selesai" 
         when a.hasil_fol_up in ("cancel","canceled") then "Batal Service"
         when a.hasil_fol_up in ("open","pending","pause") then "Sedang dikerjakan"
         else "Konsumen Tidak Datang" END) as status_fol_up');
         $this->db->from('tr_h2_fol_up_header f');
         $this->db->join('tr_h2_fol_up_detail a','f.id_follow_up=a.id_follow_up');
         $this->db->join('tr_h2_sa_form b','a.id_customer=b.id_customer');
          // $this->db->join('tr_h2_wo_dealer c','c.id_sa_form=b.id_sa_form');
          $this->db->join('ms_customer_h23 d','d.id_customer=a.id_customer');
          $this->db->join('ms_h2_pembawa e','e.id_pembawa=b.id_pembawa','left');
          $this->db->where('a.is_done=','1');
          $this->db->where('a.id_dealer =',$id_dealer);
          $this->db->group_by('a.id_follow_up');
      return $this->db->count_all_results();
    }

}

