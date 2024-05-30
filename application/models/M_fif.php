<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_fif extends CI_Model {

	var $baseUrl = "https://restapi.fifgroup.co.id/fifport/";

	public function order_new($no_spk)
	{
		$this->db->select('spk.*,pros.id_prospek,pros.jenis_kelamin');
		$this->db->from('tr_spk spk');
		$this->db->join('tr_order_survey os', 'os.no_spk = spk.no_spk', 'inner');
		$this->db->join('tr_prospek pros', 'pros.id_customer = spk.id_customer', 'inner');
		$this->db->where('spk.no_spk', $no_spk);
		return $this->db->get();
	}

	public function get_spk($search, $limit, $start, $order_field, $order_ascdesc, $id_dealer)
	{
		$cari = "";
		// $this->db->select('spk.no_spk,spk.nama_konsumen,spk.created_at');
		// $this->db->from('tr_spk spk');
		// $this->db->join('tr_prospek pr', 'spk.id_customer = pr.id_customer', 'inner');
		// $this->db->join('tr_skema_kredit sk', 'sk.id_prospek = pr.id_prospek', 'inner');
		// $this->db->where('spk.id_dealer', $id_dealer);
		// $this->db->where('sk.id_finco', 'FC00000003');
		// $this->db->where('spk.status_spk', 'approved');
		// $this->db->where('spk.created_at >=', '2021-07-01');
		// $this->db->where("spk.no_spk NOT IN (SELECT no_spk FROM tr_fif_order WHERE is_cancel='t')");
		// if ($search !='') {
		// 	$this->db->like('spk.no_spk', $search, 'BOTH');
		// 	$this->db->or_like('spk.nama_konsumen', $search, 'BOTH');
		// }
		// $this->db->order_by($order_field, $order_ascdesc);
		// $this->db->limit($limit,$start);

		if ($search != '') {
			$cari = "AND ( `spk`.`no_spk` LIKE '%$search%' ESCAPE '!'
			OR  `spk`.`nama_konsumen` LIKE '%$search%' ESCAPE '!')";
		}
		
		// testing ernesto
		$set_where ='';
		// $set_where .="NOT IN (SELECT no_spk FROM tr_fif_order WHERE is_cancel='t')";

		$query = "
		SELECT `spk`.`no_spk`, `spk`.`nama_konsumen`, `spk`.`created_at`, `spk`.`status_spk`
		FROM `tr_spk` `spk`
		INNER JOIN `tr_prospek` `pr` ON `spk`.`id_customer` = `pr`.`id_customer`
		INNER JOIN `tr_skema_kredit` `sk` ON `sk`.`id_prospek` = `pr`.`id_prospek`
		WHERE `spk`.`id_dealer` = '$id_dealer'
		AND `sk`.`id_finco` = 'FC00000003'
		AND `spk`.`status_spk` = 'approved'
		AND `spk`.`created_at` >= '2021-07-01'
		$set_where 
		$cari
		ORDER BY $order_field $order_ascdesc
		LIMIT $start, $limit
		";
		return $this->db->query($query);
	}

	public function get_order_fif($search, $limit, $start, $order_field, $order_ascdesc, $id_dealer)
	{
		// $this->db->select('tf.no_spk,spk.nama_konsumen,tf.order_uuid,tf.order_status, tf.kirim_dokumen, tf.delivery, tf.kirim_invoice, tf.kirim_dokumen_invoice, tf.inv_uuid, tf.created_at');
		// $this->db->from('tr_fif_order tf');
		// $this->db->join('tr_spk spk', 'spk.no_spk = tf.no_spk', 'inner');
		// $this->db->where('spk.id_dealer', $id_dealer);
		// if ($search !='') {
		// 	$this->db->like('tf.no_spk', $search, 'BOTH');
		// 	$this->db->or_like('spk.nama_konsumen', $search, 'BOTH');
		// 	$this->db->or_like('tf.order_uuid', $search, 'BOTH');
		// }
		// $this->db->order_by($order_field, $order_ascdesc);
		// $this->db->limit($limit,$start);

		$cari = "";
		if ($search != '') {
			$cari = "AND (`tf`.`no_spk` LIKE '%$search%' ESCAPE '!' 
				OR `spk`.`nama_konsumen` LIKE '%$search%' ESCAPE '!' 
				OR `tf`.`order_uuid` LIKE '%$search%' ESCAPE '!' )";
		}

		$query = "
			SELECT
				tf.id,
				`tf`.`no_spk`,
				`spk`.`nama_konsumen`,
				`tf`.`order_uuid`,
				`tf`.`order_status`,
				`tf`.`kirim_dokumen`,
				`tf`.`delivery`,
				`tf`.`kirim_invoice`,
				`tf`.`kirim_dokumen_invoice`,
				`tf`.`inv_uuid`,
				`tf`.`created_at` 
			FROM
				`tr_fif_order` `tf`
				INNER JOIN `tr_spk` `spk` ON `spk`.`no_spk` = `tf`.`no_spk` 
			WHERE
				`spk`.`id_dealer` = '$id_dealer' 
				$cari
			ORDER BY $order_field $order_ascdesc
			LIMIT $start, $limit
		";
		return $this->db->query($query);
	}

	public function count_filter_order_fif($search,$id_dealer)
	{
		// $this->db->select('tf.order_uuid');
		// $this->db->from('tr_fif_order tf');
		// $this->db->join('tr_spk spk', 'spk.no_spk = tf.no_spk', 'inner');
		// $this->db->where('spk.id_dealer', $id_dealer);
		// if ($search !='') {
		// 	$this->db->like('tf.no_spk', $search, 'BOTH');
		// 	$this->db->or_like('spk.nama_konsumen', $search, 'BOTH');
		// 	$this->db->or_like('tf.order_uuid', $search, 'BOTH');
		// }
		$cari = "";
		if ($search != '') {
			$cari = "AND  (`tf`.`no_spk` LIKE '%$search%' ESCAPE '!'
						OR  `spk`.`nama_konsumen` LIKE '%$search%' ESCAPE '!'
						OR  `tf`.`order_uuid` LIKE '%$search%' ESCAPE '!')";
		}
		$query = "
		SELECT `tf`.`order_uuid`
		FROM `tr_fif_order` `tf`
		INNER JOIN `tr_spk` `spk` ON `spk`.`no_spk` = `tf`.`no_spk`
		WHERE `spk`.`id_dealer` = '$id_dealer'
		$cari
		";

		return $this->db->query($query)->num_rows();
	}

	public function get_order_fif_kasir($search, $limit, $start, $order_field, $order_ascdesc, $id_dealer)
	{
		$cari = "";
		if ($search != '') {
			$cari = "AND (`tf`.`no_spk` LIKE '%$search%' ESCAPE '!' 
				OR `spk`.`nama_konsumen` LIKE '%$search%' ESCAPE '!' )";
		}

		$query = "
			SELECT
				`tf`.`no_spk`,
				`spk`.`nama_konsumen`,
				`tf`.`order_uuid`,
				`tf`.`order_status`,
				`tf`.`kirim_dokumen`,
				`tf`.`delivery`,
				`tf`.`kirim_invoice`,
				`tf`.`kirim_dokumen_invoice`,
				`tf`.`inv_uuid`,
				`tf`.`created_at` 
			FROM
				`tr_fif_order` `tf`
				INNER JOIN `tr_spk` `spk` ON `spk`.`no_spk` = `tf`.`no_spk` 
			WHERE
				`spk`.`id_dealer` = '$id_dealer' 
				and `tf`.`delivery` = 'y' 
				$cari
			ORDER BY $order_field $order_ascdesc
			LIMIT $start, $limit

		";

		return $this->db->query($query);
	}

	public function count_filter_order_fif_kasir($search,$id_dealer)
	{
		$cari = "";
		if ($search != '') {
			$cari = "AND  (`tf`.`no_spk` LIKE '%$search%' ESCAPE '!'
						OR  `spk`.`nama_konsumen` LIKE '%$search%' ESCAPE '!')";
		}
		$query = "
		SELECT `tf`.`order_uuid`
		FROM `tr_fif_order` `tf`
		INNER JOIN `tr_spk` `spk` ON `spk`.`no_spk` = `tf`.`no_spk`
		WHERE `spk`.`id_dealer` = '$id_dealer'
		and `tf`.`delivery` = 'y'
		$cari
		";

		return $this->db->query($query)->num_rows();
	}

	public function count_filter_spk($search,$id_dealer)
	{
		// $this->db->select('spk.no_spk');
		// $this->db->from('tr_spk spk');
		// $this->db->join('tr_prospek pr', 'spk.id_customer = pr.id_customer', 'inner');
		// $this->db->join('tr_skema_kredit sk', 'sk.id_prospek = pr.id_prospek', 'inner');
		// $this->db->where('spk.id_dealer', $id_dealer);
		// $this->db->where('sk.id_finco', 'FC00000003');
		// $this->db->where('spk.status_spk', 'approved');
		// if ($search !='') {
		// 	$this->db->like('spk.no_spk', $search, 'BOTH');
		// 	$this->db->or_like('spk.nama_konsumen', $search, 'BOTH');
		// }

		$cari = "";
		if ($search != '') {
			$cari = "AND  (`spk`.`no_spk` LIKE '%$search%' ESCAPE '!'
			OR  `spk`.`nama_konsumen` LIKE '%$search%' ESCAPE '!')";
		}

		$query = "

		SELECT `spk`.`no_spk`
		FROM `tr_spk` `spk`
		INNER JOIN `tr_prospek` `pr` ON `spk`.`id_customer` = `pr`.`id_customer`
		INNER JOIN `tr_skema_kredit` `sk` ON `sk`.`id_prospek` = `pr`.`id_prospek`
		WHERE `spk`.`id_dealer` = '$id_dealer'
		AND `sk`.`id_finco` = 'FC00000003'
		AND `spk`.`status_spk` = 'approved'
		$cari

		 ";

		return $this->db->query($query)->num_rows();
	}

	function get_sales_order($no_spk)
	{
		$this->db->where('no_spk', $no_spk);
		return $this->db->get('tr_sales_order');
	}

	function get_warna($nosin)
	{
		$sql = "
			SELECT b.warna_samsat FROM
			tr_scan_barcode as a
			inner join ms_warna as b on a.warna=b.id_warna
			where a.no_mesin = '$nosin'

		";
		return $this->db->query($sql);
	}

	function get_detail_order($orderUuid)
	{

		$token =  get_token_fif();
		// Status Order EndPoint
		
		$url = $this->baseUrl."order/status/order/".$orderUuid;

		$headers = [
			'Content-Type:application/json',
			'Accept:application/json',
			'Authorization: Bearer '.$token,
		];

		//initialize curl 
		$curl = curl_init(); 
		//set parameters 
		curl_setopt_array($curl, 
			array( 
				CURLOPT_HTTPHEADER => $headers, # HTTP Headers
				//expects a response 
				CURLOPT_RETURNTRANSFER => 1, 
				//get url 
				CURLOPT_URL => $url
			)
		); 
		// Send the request & save response to $resp 
		$resp = curl_exec($curl); 
		// Close request to clear up some resources 
		return $resp;
	}

	function get_detail_order_by_nospk($no_spk)
	{
		$this->db->where('no_spk', $no_spk);
		$this->db->order_by('id', 'desc');
		$cek = $this->db->get('tr_fif_order');

		if ($cek->num_rows() > 0) {
			$orderUuid = $cek->row()->order_uuid;
			$token =  get_token_fif();
			// Status Order EndPoint
			
			$url = $this->baseUrl."order/status/order/".$orderUuid;

			$headers = [
				'Content-Type:application/json',
				'Accept:application/json',
				'Authorization: Bearer '.$token,
			];

			//initialize curl 
			$curl = curl_init(); 
			//set parameters 
			curl_setopt_array($curl, 
				array( 
					CURLOPT_HTTPHEADER => $headers, # HTTP Headers
					//expects a response 
					CURLOPT_RETURNTRANSFER => 1, 
					//get url 
					CURLOPT_URL => $url
				)
			); 
			// Send the request & save response to $resp 
			$resp = curl_exec($curl); 
			// Close request to clear up some resources 

			return $resp;
		} else {
			$resp = '';
			return $resp;
		}

		
	}

	function get_detail_order_fromdb_by_nospk($no_spk)
	{
		$this->db->where('no_spk', $no_spk);
		$this->db->order_by('id', 'desc');
		$cek = $this->db->get('tr_fif_order');

		if ($cek->num_rows() > 0) {
			$orderUuid = $cek->row()->order_uuid;
			$co = $this->cek_order($orderUuid);
			return $co;
		} else{
			return false;
		}
	}

	function upload_dokument($nama_file, $name_form)
	{
		$return = array();
        $this->load->library('upload'); // Load librari upload

        $config['upload_path'] = './uploads/invoice_fif/';
        $config['allowed_types'] = 'jpeg|jpg|png|pdf';
        $config['max_size'] = '2048';
        $config['overwrite'] = true;
        $config['file_name'] = $nama_file;

        $this->upload->initialize($config); // Load konfigurasi uploadnya
        if($this->upload->do_upload($name_form)){ // Lakukan upload dan Cek jika proses upload berhasil
            // Jika berhasil :
            $return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
        }else{
            // Jika gagal :
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
        }
        return $return;
	}

	function cek_order($uuid)
	{
		$sql = "SELECT * FROM tr_fif_order_json INNER JOIN tr_fif_order_json_detail ON tr_fif_order_json_detail.id_order_json = tr_fif_order_json.id  WHERE tr_fif_order_json.order_uuid = '$uuid' ORDER BY tr_fif_order_json.id DESC LIMIT 1";
		return $this->db->query($sql);
	}


}

/* End of file M_fif.php */
/* Location: ./application/models/M_fif.php */