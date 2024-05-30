<?php
defined('BASEPATH') or exit('No direct script access allowed');


class H1_model_nrfs extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	


	public function filter($search, $limit, $start, $order_field, $order_ascdesc, $tgl1, $tgl2){

		$this->db->select("
			tc.created_at as date_at,
			tc.nama_pemeriksa as nama_pemeriksa ,
			tcd.id_part as id_part,
			tcd.gejala as gejala,
			tcd.penyebab as penyebab,
			tc.no_mesin as no_mesin,
			tsl.no_rangka as no_rangka,
			tpu.tgl_penerimaan as tanggal_penerimaan,
			tcd.pengatasan as perbaikan_gudang,
			v.kode_vendor_ahm as id_ekspedisi,
			tc.no_polisi as no_polisi,
			tf.nama_kapal as nama_kapal,
			CASE WHEN tcd.no_po_urgent != '' THEN 'Y' ELSE 'N' END AS butuh_po,
			tcd.no_po_urgent as no_po_urgent,
			tc.estimasi_tgl_selesai as estimasi_tgl_selesai,
			tw.updated_at as actual_tgl_selesai
			");
        $this->db->from('tr_checker tc');
		$this->db->join('tr_checker_detail tcd','tc.id_checker = tcd.id_checker');
		$this->db->join('tr_shipping_list tsl','tc.no_mesin = tsl.no_mesin','left');
		$this->db->join('tr_penerimaan_unit_detail tpud','tsl.no_shipping_list = tpud.no_shipping_list','left');
		$this->db->join('tr_penerimaan_unit tpu', 'tpud.id_penerimaan_unit = tpu.id_penerimaan_unit', 'left');
		$this->db->join('tr_fkb tf','tc.no_mesin = tf.no_mesin_spasi');
		$this->db->join('tr_wo tw','tc.id_checker = tw.id_checker');
		$this->db->join('ms_vendor v','tc.ekspedisi = v.id_vendor');
		if ($tgl1 != '') {
			$this->db->where('tw.status_wo!=','closed');
			$this->db->where('tc.sumber_kerusakan','EKSPEDISI');
			
			
			if($tgl1!==date('Y-m-d')){
				$this->db->or_where("tw.created_at BETWEEN '$tgl1' AND '$tgl2'");
				$this->db->or_where("tw.updated_at BETWEEN '$tgl1' AND '$tgl2'");
				$this->db->where("tw.status_wo",'closed');
			}else{
				$this->db->where("tw.created_at BETWEEN '$tgl1' AND '$tgl2'");
			}
		}


		if ($search != '') {
        	$this->db->like('tc.no_mesin', $search, 'BOTH');
        	$this->db->or_like('tc.created_at', $search, 'BOTH');
        }

		$this->db->order_by($order_field, $order_ascdesc); // Untuk menambahkan query ORDER BY
		$this->db->limit($limit, $start); // Untuk menambahkan query LIMIT

		//$this->db->get();
		//echo $this->db->last_query();die;

		return $this->db->get(); // Eksekusi query sql sesuai kondisi diatas
	}

	public function generate_file($tgl1, $tgl2){

		$this->db->select("
			'E20' as md_code,
			DATE_FORMAT(tc.created_at, '%Y%m%d') as date_at,
			tc.nama_pemeriksa as nama_pemeriksa ,
			tcd.id_part as id_part,
			tcd.gejala as gejala,
			tcd.penyebab as penyebab,
			tc.no_mesin as no_mesin,
			tsl.no_rangka as no_rangka,
			DATE_FORMAT(tpu.tgl_penerimaan, '%Y%m%d') as tanggal_penerimaan,
			tcd.pengatasan as perbaikan_gudang,
			v.kode_vendor_ahm as id_ekspedisi,
			tc.no_polisi as no_polisi,
			tf.nama_kapal as nama_kapal,
			CASE WHEN tcd.no_po_urgent != '' THEN 'Y' ELSE 'N' END AS butuh_po,
			tcd.no_po_urgent as no_po_urgent,
			DATE_FORMAT(tc.estimasi_tgl_selesai, '%Y%m%d') as estimasi_tgl_selesai,
			DATE_FORMAT(tw.updated_at, '%Y%m%d') as actual_tgl_selesai, tcd.qty_order,tcd.ket
			");
        $this->db->from('tr_checker tc');
		$this->db->join('tr_checker_detail tcd','tc.id_checker = tcd.id_checker');
		$this->db->join('tr_shipping_list tsl','tc.no_mesin = tsl.no_mesin','left');
		$this->db->join('tr_penerimaan_unit_detail tpud','tsl.no_shipping_list = tpud.no_shipping_list','left');
		$this->db->join('tr_penerimaan_unit tpu', 'tpud.id_penerimaan_unit = tpu.id_penerimaan_unit', 'left');
		$this->db->join('tr_fkb tf','tc.no_mesin = tf.no_mesin_spasi');
		$this->db->join('tr_wo tw','tc.id_checker = tw.id_checker');
		$this->db->join('ms_vendor v','tc.ekspedisi = v.id_vendor');
		$this->db->where('tcd.qty_order > 0');
		$this->db->where("tcd.ket != 'REPAINTING'");
		if ($tgl1 != '') {
			$this->db->where('tw.status_wo!=','closed');
			$this->db->or_where("tw.updated_at BETWEEN '$tgl1' AND '$tgl2'");
		}

		return $this->db->get(); // Eksekusi query sql sesuai kondisi diatas
	}

	public function count_all(){
		// return $this->db->count_all('siswa'); // Untuk menghitung semua data siswa
	}

	public function count_filter($search,$tgl1, $tgl2){
		$this->db->select("
			tc.created_at as date_at,
			tc.nama_pemeriksa as nama_pemeriksa ,
			tcd.id_part as id_part,
			tcd.gejala as gejala,
			tcd.penyebab as penyebab,
			tc.no_mesin as no_mesin,
			tsl.no_rangka as no_rangka,
			tpu.tgl_penerimaan as tanggal_penerimaan,
			tcd.pengatasan as perbaikan_gudang,
			tc.ekspedisi as id_ekspedisi,
			tc.no_polisi as no_polisi,
			tf.nama_kapal as nama_kapal,
			CASE WHEN tcd.no_po_urgent != '' THEN 'Ya' ELSE 'Tidak' END AS butuh_po,
			tcd.no_po_urgent as no_po_urgent,
			tc.estimasi_tgl_selesai as estimasi_tgl_selesai,
			tw.updated_at as actual_tgl_selesai
			");
        $this->db->from('tr_checker tc');
		$this->db->join('tr_checker_detail tcd','tc.id_checker = tcd.id_checker');
		$this->db->join('tr_shipping_list tsl','tc.no_mesin = tsl.no_mesin','left');
		$this->db->join('tr_penerimaan_unit_detail tpud','tsl.no_shipping_list = tpud.no_shipping_list','left');
		$this->db->join('tr_penerimaan_unit tpu', 'tpud.id_penerimaan_unit = tpu.id_penerimaan_unit', 'left');
		$this->db->join('tr_fkb tf','tc.no_mesin = tf.no_mesin_spasi');
		$this->db->join('tr_wo tw','tc.id_checker = tw.id_checker');
		if ($tgl1 != '') {
			$this->db->where("tw.updated_at BETWEEN '$tgl1' AND '$tgl2'");
			$this->db->or_where('tw.status_wo!=','closed');
		}

		if ($search != '') {
        	$this->db->like('tc.no_mesin', $search, 'BOTH');
        	$this->db->or_like('tc.created_at', $search, 'BOTH');
        }

		return $this->db->get()->num_rows(); 
	}

	public function d_check()
	{
		$cek = $this->db->get('tr_checker_detail');
		$num = 0;

		foreach ($cek->result() as $rw) {
			$id_checker = $this->db->get_where('tr_checker', array('id_checker'=>$rw->id_checker));
			if ($id_checker->num_rows() == 0) {
				$this->db->where('id_checker_detail', $rw->id_checker_detail);
				$this->db->delete('tr_checker_detail');
				$num++;
			}
		}
		return $num;
	}


}