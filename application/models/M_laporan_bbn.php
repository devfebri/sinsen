<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class M_laporan_bbn extends CI_Model {

    public function __construct(){

        parent::__construct();

        $this->load->database();

    }



    public function get_data(){

        $query = $this->db->query("

            select z.tgl_cetak_invoice, c.nama_dealer, z.no_mesin, d.no_rangka, d.tipe_motor, z.nama_bpkb, 

                e.tgl_bastd as tgl_pengajuan_dealer, e.no_bastd, f.tgl_entry as tgl_pembayaran, e.tgl_approval,

                e.status_faktur as status_approve_finance, e.tgl_mohon_samsat as tgl_pengajuan_samsat, e.status_bbn,
		g.no_pol, g.no_stnk

                from (

                	select b.id_dealer, a.no_mesin, tgl_cetak_invoice, b.nama_bpkb

                	from tr_sales_order a

                	join tr_spk b on a.no_spk = b.no_spk

                	where  tgl_cetak_invoice > '2019-11-30' and a.created_at > '2019-11-30'

                	union

                	select a.id_dealer, b.no_mesin, a.tgl_cetak_invoice, c.nama_npwp

                	from tr_sales_order_gc a join tr_sales_order_gc_nosin b on a.id_sales_order_gc = b.id_sales_order_gc

                	join tr_spk_gc c on a.no_spk_gc = c.no_spk_gc

                	where tgl_cetak_invoice > '2019-11-30' and a.created_at > '2019-11-30'

                ) z 

                join ms_dealer c on z.id_dealer = c.id_dealer

                join tr_scan_barcode d on z.no_mesin = d.no_mesin

                left join (

                	select a.no_bastd, a.tgl_bastd, a.tgl_approval, a.status_faktur, b.no_mesin, c.tgl_mohon_samsat, c.status_bbn, a.updated_at, a.status_bayar

                	from tr_faktur_stnk a

                	join tr_faktur_stnk_detail b on a.no_bastd = b.no_bastd

                	left join tr_pengajuan_bbn_detail c on c.no_mesin = b.no_mesin

                ) e on e.no_mesin = z.no_mesin

                left join (

                	select DISTINCT referensi, b.tgl_entry from tr_penerimaan_bank_detail a

                	join tr_penerimaan_bank b on a.id_penerimaan_bank = b.id_penerimaan_bank

                	group by a.referensi

                ) f on e.no_bastd = f.referensi
		
		left join tr_entry_stnk g on g.no_mesin = z.no_mesin
        ");

        

        if($query->num_rows() > 0) {

            return $query->result();

        }else{

            return false;

        }

    }

}