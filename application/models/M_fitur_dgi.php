<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_fitur_dgi extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

    public function get_data(){
        $query = $this->db->query("
		select  d.kode_dealer_md , d.nama_dealer, DATE_FORMAT(FROM_UNIXTIME(request_time),\"%d %M %Y\") as tgl, 
			(case when endpoint like '%pkb%' or 
			endpoint like '%inv2%' or
			endpoint like '%unpaidhlo%' or
			endpoint like '%dphlo%' or
			endpoint like '%pinb%' or
			endpoint like '%prsl%' 
			then 'H23' else 'H1' end) as kategori,  	
			(case 
				when endpoint like '%pkb%' then 'PKB' 
				when endpoint like '%inv2%' then 'INV2' 
				when endpoint like '%unpaidhlo%' then 'UNPAIDHLO' 
				when endpoint like '%dphlo%' then 'DPHLO' 
				when endpoint like '%pinb%' then 'PINB' 
				when endpoint like '%prsl%' then 'PRSL' 
				when endpoint like '%prsp%' then 'PRSP' 
				when endpoint like '%spk%' then 'SPK' 
				when endpoint like '%inv1%' then 'INV1' 
				when endpoint like '%lsng%' then 'LSNG' 
				when endpoint like '%doch%' then 'DOCH' 
				when endpoint like '%bast%' then 'BAST' 
				when endpoint like '%uinb%' then 'UINB' 
				else '-' 
			end) as endpoint,
			count(1) as hit , sum(a.data_count) as jumlah
		from dgi_activity_log a 
		join ms_dgi_api_key b on a.api_key =b.api_key 
		join ms_dealer c on b.id_dealer = c.kode_dealer_md 
		join ms_dealer d on c.kode_dealer_ahm = d.kode_dealer_md 
		where DATE_FORMAT(FROM_UNIXTIME(request_time), \"%Y-%m-%d\") >= DATE_FORMAT( now(),\"%Y-%m-01\") and  DATE_FORMAT(FROM_UNIXTIME(request_time), \"%Y-%m-%d\") < DATE_FORMAT( now(),\"%Y-%m-02\") and d.id_dealer !=103 and status = 1
		group by a.status, d.nama_dealer, tgl, endpoint , d.id_dealer , kategori 
		order by d.nama_dealer , tgl , kategori asc
 	");

        if($query->num_rows() > 0) {
            return $query->result();
        }else{
            return false;
        }
    }

    public function get_data_all(){
        $query = $this->db->query("
	select kode_dealer_md, nama_dealer, 
	sum(case when kategori ='H1' then hit end) as h1_hit,
	sum(case when kategori ='H1' then jumlah end) as h1_data,
	sum(case when kategori ='H23' then hit end) as h23_hit,
	sum(case when kategori ='H23' then jumlah end) as h23_data
	from (
		select d.kode_dealer_md , d.nama_dealer,
			(case when endpoint like '%pkb%' or 
			endpoint like '%inv2%' or
			endpoint like '%unpaidhlo%' or
			endpoint like '%dphlo%' or
			endpoint like '%pinb%' or
			endpoint like '%prsl%' 
			then 'H23' else 'H1' end) as kategori,  
			count(1) as hit , sum(a.data_count) as jumlah
		from dgi_activity_log a 
		join ms_dgi_api_key b on a.api_key =b.api_key 
		join ms_dealer c on b.id_dealer = c.kode_dealer_md 
		join ms_dealer d on c.kode_dealer_ahm = d.kode_dealer_md 
		where DATE_FORMAT(FROM_UNIXTIME(request_time), \"%Y-%m-%d\") >= DATE_FORMAT( now(),\"%Y-%m-01\") and  DATE_FORMAT(FROM_UNIXTIME(request_time), \"%Y-%m-%d\") <= DATE_FORMAT( now(),\"%Y-%m-%d\") and d.id_dealer !=103 and status = 1
		group by a.status, d.nama_dealer, d.id_dealer , kategori 
		
	)as tbl
	group by kode_dealer_md , nama_dealer 
	order by nama_dealer asc
 	");

        if($query->num_rows() > 0) {
            return $query->result();
        }else{
            return false;
        }
    }
}
