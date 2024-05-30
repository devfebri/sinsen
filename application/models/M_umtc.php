<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class M_umtc extends CI_Model {

    public function __construct(){

        parent::__construct();

        $this->load->database();

    }

    public function get_data(){
        $query = $this->db->query("
		select b.id_tipe_kendaraan as id_tipe_kendaraan, b.deskripsi_ahm, a.id_warna, c.warna, UCASE(b.tipe_ahm) as tipe_ahm, b.cc_motor, a.id_item,
		UCASE(concat(concat(b.tipe_ahm,' ') ,c.warna)) as item , b.id_kategori, 
		concat(concat(substring(b.tgl_awal,9,2),substring(b.tgl_awal,6,2)),SUBSTRING(b.tgl_awal,1,4)) as tgl_awal,
		concat(concat(substring(b.tgl_akhir,9,2),substring(b.tgl_akhir,6,2)),SUBSTRING(b.tgl_akhir,1,4)) as tgl_akhir,
		(case when b.active = 1 then case when a.active then 'Y' else 'N' end else 'N' end) status
		from ms_item a
		join ms_tipe_kendaraan b on a.id_tipe_kendaraan = b.id_tipe_kendaraan
		join ms_warna c on a.id_warna = c.id_warna
		order by id_tipe_kendaraan ASC
       ");

        if($query->num_rows() > 0) {

            return $query->result();

        }else{

            return false;

        }
    }

}