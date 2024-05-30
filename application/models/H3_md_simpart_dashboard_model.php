<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class H3_md_simpart_dashboard_model extends CI_Model{
		public function __construct()
        {
            parent::__construct();
        }

		public function getDataDealer()
		{
			$query=$this->db->query("SELECT id_dealer,kode_dealer_ahm,nama_dealer from ms_dealer where id_dealer in('1','2','4','5','6','8','10','13','18','19','22','23','25','28','29','30','38','39','40','41','43','44','46','47','51','54','56','58','64','65','66','69','70','71','74','76','77','78','80','81','82','83','84','85','86','88','90','91','94','96','97','98','101','102','103','104','105','106','107','128','714','715','716')");
			return $query->result();
		}

        public function master_ahass()
        {
            $master_ahass=$this->db->query("SELECT kode_dealer_ahm, kode_dealer_md, nama_dealer, grouping_dealer, jenis_dealer, (case when h1=1 and h2=1 and h3=1 then 'H123' when h2=1 and h3=1 then 'H23' when h3=1 then 'H3' else '-' end) as channel  
            from ms_dealer md 
            where active=1 and id_dealer in('1','2','4','5','6','8','10','13','18','19','22','23','25','28','29','30','38','39','40','41','43','44','46','47','51','54','56','58','64','65','66','69','70','71','74','76','77','78','80','81','82','83','84','85','86','88','90','91','94','96','97','98','101','102','103','104','105','106','107','128','714','715','716')");
			return $master_ahass->result();
        }

        public function parts_number($id_dealer)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = " AND spd.id_dealer='$id_dealer'";
            }

            $parts_number=$this->db->query("SELECT md.kode_dealer_ahm, spi.id_part, mp.nama_part, mp.kelompok_part, spi.qty_sim_part, sim_part.batas_atas_jumlah_ue , 
            sim_part.batas_bawah_jumlah_ue, (case when sim_part.batas_atas_jumlah_ue = 500 
            then 'Target UE < 500' when sim_part.batas_bawah_jumlah_ue = 500 then 'Target UE 500-1000' when sim_part.batas_atas_jumlah_ue = 1000 then 'Target UE >1000' 
            else '-' end ) as unit_entry, (case when sim_part.batas_atas_jumlah_ue = 500 
            then 'Kat 3' when sim_part.batas_bawah_jumlah_ue = 500 then 'Kat 2' when sim_part.batas_atas_jumlah_ue = 1000 then 'Kat 1' 
            else '-' end ) as kategori_ahass, IFNULL(SUM(ds.stock),0) as stock_on_hand
            FROM ms_h3_md_sim_part sim_part
            JOIN ms_h3_md_sim_part_item spi on spi.id_sim_part_int=sim_part.id
            JOIN ms_h3_md_sim_part_dealer spd on spd.id_sim_part_int=sim_part.id
            JOIN ms_dealer md on md.id_dealer=spd.id_dealer 
            JOIN ms_part mp on mp.id_part_int=spi.id_part_int 
            left JOIN ms_h3_dealer_stock ds on ds.id_part_int=spi.id_part_int and ds.id_dealer=spd.id_dealer 
            WHERE sim_part.active=1 $filter_dealer
            GROUP by spd.id_dealer, spi.id_part");
			return $parts_number->result();
        }

        // public function master_sim_part($id_dealer)
        // {
        //     $filter_dealer = '';
        //     if ($id_dealer!='all') {
        //          $filter_dealer = " AND spd.id_dealer='$id_dealer'";
        //     }

        //     $master_sim_part=$this->db->query("SELECT md.kode_dealer_ahm, spi.id_part, mp.nama_part, mp.kelompok_part, spi.qty_sim_part, sim_part.batas_atas_jumlah_ue , sim_part.batas_bawah_jumlah_ue, (case when sim_part.batas_atas_jumlah_ue = 500 
        //     then 'Target UE < 500' when sim_part.batas_bawah_jumlah_ue = 500 then 'Target UE 500-1000' when sim_part.batas_atas_jumlah_ue = 1000 then 'Target UE >1000' 
        //     else '-' end ) as unit_entry, (case when sim_part.batas_atas_jumlah_ue = 500 
        //     then 'Kat 3' when sim_part.batas_bawah_jumlah_ue = 500 then 'Kat 2' when sim_part.batas_atas_jumlah_ue = 1000 then 'Kat 1' 
        //     else '-' end ) as kategori_ahass, IFNULL(SUM(ds.stock),0) as stock_on_hand
        //     FROM ms_h3_md_sim_part sim_part
        //     JOIN ms_h3_md_sim_part_item spi on spi.id_sim_part_int=sim_part.id
        //     JOIN ms_h3_md_sim_part_dealer spd on spd.id_sim_part_int=sim_part.id
        //     JOIN ms_dealer md on md.id_dealer=spd.id_dealer 
        //     JOIN ms_part mp on mp.id_part_int=spi.id_part_int 
	    //     LEFT JOIN ms_h3_dealer_stock ds on ds.id_part_int=spi.id_part_int and ds.id_dealer=spd.id_dealer 
        //     where sim_part.active=1 $filter_dealer
	    //     GROUP by spd.id_dealer, spi.id_part");
		// 	return $master_sim_part->result();
        // }

        public function master_sim_part($id_dealer)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = " AND spd.id_dealer='$id_dealer'";
            }

            $master_sim_part=$this->db->query("SELECT md.kode_dealer_ahm, spi.id_part, mp.nama_part, IFNULL(SUM(ds.stock),0) as stock_on_hand, 
            MONTH(CURRENT_DATE()) as bulan,(case when h1=1 and h2=1 and h3=1 then 'H123' when h2=1 and h3=1 then 'H23' when h3=1 then 'H3' else '-' end) as channel,
            spi.qty_sim_part as qty_sim_part,  (CASE WHEN SUM(ds.stock) = 0 THEN 'Tidak Tersedia' ELSE 'Tersedia' END) as AvailItem, 
            (CASE WHEN mp.kelompok_part = 'OIL' THEN 'Oil' ELSE 'Non Oil' END) as tipe_oli, mp.kelompok_part, 
            'True' as validasiGrouping, (CASE WHEN mp.kelompok_part in ('OIL', 'SPLUR', 'SPLUG', 'GMO') then 'QUICK REPAIR' ELSE 'LIGHT REPAIRE' END) as RepairMode, 
            (CASE WHEN SUM(ds.stock) = 0  THEN '0' ELSE '1' END ) as countAvailItem, '0,95' as target, 
            mp.harga_md_dealer as harga_jual, mp.harga_dealer_user as het, spd.jumlah_ue, md.nama_dealer, 
            (case when sim_part.batas_atas_jumlah_ue = 500 
            then 'Kat 3' when sim_part.batas_bawah_jumlah_ue = 500 then 'Kat 2' when sim_part.batas_atas_jumlah_ue = 1000 then 'Kat 1'
            else '-' end ) as kategori_ahass, 
            (case when sim_part.batas_atas_jumlah_ue = 500 
            then 'Target UE < 500' when sim_part.batas_bawah_jumlah_ue = 500 then 'Target UE 500-1000' when sim_part.batas_atas_jumlah_ue = 1000 then 'Target UE >1000' 
            else '-' end ) as unit_entry,
            DATE_FORMAT(CURDATE(),'%d-%m-%Y') as tanggal
            FROM ms_h3_md_sim_part sim_part
            JOIN ms_h3_md_sim_part_item spi on spi.id_sim_part_int=sim_part.id
            JOIN ms_h3_md_sim_part_dealer spd on spd.id_sim_part_int=sim_part.id
            JOIN ms_dealer md on md.id_dealer=spd.id_dealer 
            JOIN ms_part mp on mp.id_part_int=spi.id_part_int 
            LEFT JOIN ms_h3_dealer_stock ds on ds.id_part_int=spi.id_part_int and ds.id_dealer=spd.id_dealer 
            where sim_part.active=1 $filter_dealer
            GROUP by spd.id_dealer, spi.id_part
            ORDER by md.nama_dealer ASC");
			return $master_sim_part->result();
        }

        public function master_by_qty($id_dealer)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = " AND spd.id_dealer='$id_dealer'";
            }

            $master_by_qty=$this->db->query("SELECT md.kode_dealer_ahm, spi.qty_sim_part as minimum_qty,  MONTH(CURRENT_DATE()) as bulan, IFNULL(SUM(ds.stock),0) as qty, 
            (CASE WHEN SUM(ds.stock) = 0 THEN 'Tidak Tersedia' ELSE 'Tersedia' END) as AvailQTY
            , (CASE WHEN SUM(ds.stock) < spi.qty_sim_part  THEN '0' ELSE '1' END ) as grouping, mp.id_part, mp.nama_part,
            (CASE WHEN mp.kelompok_part = 'OIL' THEN 'Oil' ELSE 'Non Oil' END) as tipe_oli, mp.kelompok_part, md.nama_dealer 
            FROM ms_h3_md_sim_part sim_part
            JOIN ms_h3_md_sim_part_item spi on spi.id_sim_part_int=sim_part.id
            JOIN ms_h3_md_sim_part_dealer spd on spd.id_sim_part_int=sim_part.id
            JOIN ms_dealer md on md.id_dealer=spd.id_dealer 
            JOIN ms_part mp on mp.id_part_int=spi.id_part_int 
            JOIN ms_h3_dealer_stock ds on ds.id_part_int=spi.id_part_int and ds.id_dealer=spd.id_dealer 
            where sim_part.active=1 $filter_dealer
            GROUP by spd.id_dealer, spi.id_part
            ORDER by md.nama_dealer ASC");
			return $master_by_qty->result();
        }

        public function master_by_item($id_dealer)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = " AND spd.id_dealer='$id_dealer'";
            }

            $master_by_item=$this->db->query("SELECT md.kode_dealer_ahm,  MONTH(CURRENT_DATE()) as bulan
            , (CASE WHEN SUM(ds.stock) = 0  THEN '0' ELSE '1' END ) as grouping, mp.id_part, mp.nama_part,
             mp.kelompok_part, md.nama_dealer, '0,95' as target, DATE_FORMAT(CURDATE(),'%d-%m-%Y') as tanggal
            FROM ms_h3_md_sim_part sim_part
            JOIN ms_h3_md_sim_part_item spi on spi.id_sim_part_int=sim_part.id
            JOIN ms_h3_md_sim_part_dealer spd on spd.id_sim_part_int=sim_part.id
            JOIN ms_dealer md on md.id_dealer=spd.id_dealer 
            JOIN ms_part mp on mp.id_part_int=spi.id_part_int 
            JOIN ms_h3_dealer_stock ds on ds.id_part_int=spi.id_part_int and ds.id_dealer=spd.id_dealer 
            where sim_part.active=1 $filter_dealer 
            GROUP by spd.id_dealer, spi.id_part
            ORDER by md.nama_dealer ASC");
			return $master_by_item;
        }

        public function master_kelompok_part($id_dealer)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = " AND spd.id_dealer='$id_dealer'";
            }

            $master_kelompok_part=$this->db->query("SELECT mp.nama_part, mp.id_part, mp.kelompok_part, (case when mp.superseed = '' or mp.superseed is null then mp.id_part else mp.superseed end) as supersede, 
            MAX(CASE WHEN sim_part.batas_bawah_jumlah_ue = 0 and sim_part.batas_atas_jumlah_ue = 500 then spi.qty_sim_part else 0 end) as ue_500,  
            MAX(CASE WHEN sim_part.batas_bawah_jumlah_ue = 500 and sim_part.batas_atas_jumlah_ue = 1000 then spi.qty_sim_part else 0 end) as ue_500_1000,  
            MAX(CASE WHEN sim_part.batas_bawah_jumlah_ue = 1000 then spi.qty_sim_part else 0 end) as ue_1000
            FROM ms_h3_md_sim_part sim_part
            JOIN ms_h3_md_sim_part_item spi on spi.id_sim_part_int=sim_part.id
            JOIN ms_h3_md_sim_part_dealer spd on spd.id_sim_part_int=sim_part.id
            JOIN ms_part mp on mp.id_part_int=spi.id_part_int 
            where sim_part.active=1 $filter_dealer
            GROUP by spi.id_part");
			return $master_kelompok_part->result();
        }



        // public function master_status_by_qty($id_dealer)
        // {
        //     $filter_dealer = '';
        //     if ($id_dealer!='all') {
        //          $filter_dealer = " AND spd.id_dealer='$id_dealer'";
        //     }

        //     $master_by_qty=$this->db->query("");
		// 	return $master_by_qty->result();
        // }
	}	
?>