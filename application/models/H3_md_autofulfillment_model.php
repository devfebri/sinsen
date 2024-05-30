<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class H3_md_autofulfillment_model extends Honda_Model{

    protected $table = 'tr_h3_md_autofulfillment';

    public function __construct()
    {
      parent::__construct();
      $this->load->database();
    }

    public function getDataDealer()
    {
        $query=$this->db->query("SELECT id_dealer,kode_dealer_ahm,nama_dealer from ms_dealer where active=1 and autofulfillment_md = 1");
        return $query->result();
    }

    public function getDataKelompokPartHGP()
    {
      $query=$this->db->query("SELECT id,kelompok_umum from ms_kelompok_part_hgp where active=1");
      return $query->result();
    }
  }
?>