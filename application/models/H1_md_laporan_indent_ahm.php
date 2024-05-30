<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class H1_md_laporan_indent_ahm extends CI_Model{
		public function getDataDealer()
		{
			$query=$this->db->query("SELECT * FROM ms_dealer WHERE active = 1 ORDER BY ms_dealer.id_dealer ASC");
			return $query->result();
		}

		public function downloadSPK($id_dealer,$start_date,$end_date){
			$data['id_dealer'] = $id_dealer	= $this->input->post('id_dealer');
			$data['start_date']= $start_date= $this->input->post('tgl1');
			$data['end_date']  = $end_date	= $this->input->post('tgl2');

			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = "AND b.id_dealer='$id_dealer'";
         		}
			
			$end_date = date_format(date_add(date_create($end_date),date_interval_create_from_date_string("1 days")),"Y-m-d");

			$spk = $this->db->query("select a.id_indent , b.kode_dealer_md , b.nama_dealer, a.id_spk , a.nama_konsumen , a.no_ktp , a.no_telp , a.id_tipe_kendaraan , a.id_warna ,  a.date_konfirmasi , a.date_prospek , a.date_deal , a.date_sales , a.status , a.date_cancel,
							(case when a.date_prospek is not null and a.date_cancel is null
								then 
									case when a.date_deal is not null and a.date_cancel is  null
										then 
											case when a.date_sales is not null and a.date_cancel is null
												then 'Sales'	
												else 'Deal'end 
												else 'Prospek' end
										else 'Cancel' end) as status_indent 
						from tr_po_dealer_indent a
						join ms_dealer b on a.id_dealer = b.id_dealer 
						where date_konfirmasi >='$start_date' and date_konfirmasi <='$end_date' and send_ahm =1 $filter_dealer
						order by date_konfirmasi asc, a.id_spk asc");
			return $spk;
		}
	}	
?>