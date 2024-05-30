<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_memo_h1 extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function get_kelurahan()
  {
    $query=$this->db->query("SELECT * from ms_kelurahan limit 10");
    return $query;
  }

  

  public function get_kecamatan($kecamatan)
  {
    $query=$this->db->query("SELECT * from ms_kecamatan limit 10");
    return $query;
  }
  
  public function get_data_master()
  {
    $query=$this->db->query("SELECT * from ms_kecamatan limit 10");
    return $query;
  }

  



}