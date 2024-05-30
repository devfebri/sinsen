<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_md_csl_master extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }
  function getDealer($filter)
  {
    $where = "WHERE 1=1 ";
    if (isset($filter['id_dealer'])) {
      $where .= " AND id_dealer='{$filter['id_dealer']}'";
    }
    if (isset($filter['kode_dealer_md'])) {
      $where .= " AND kode_dealer_md='{$filter['kode_dealer_md']}'";
    }
    return $this->db->query("SELECT * FROm ms_dealer $where");
  }
}
