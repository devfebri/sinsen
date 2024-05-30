<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
{
	public $tables = "ms_customer";
	public $folder = "master";

	public $page = "customer";
	public $pk = "id_customer";

	public $title = "Master Data Customer";

	public function fetch_all()
	{
		$this->make_datatables();
		$this->limit();

		$data = array();
		$index = 1;
		foreach ($this->db->get()->result_array() as $row) {
			$row = html_escape($row);
			$link = '<button data-dismiss=\'modal\' onClick=\'return pilihCustomer(' . json_encode($row) . ')\' class="btn btn-success btn-flat btn-xs"><i class="fa fa-check"></i></button>';
			$row['aksi'] = $link;

			$row['index'] = $this->input->post('start') + $index . ".";
			$data[] = $row;
			$index++;
		}

		$output = array(
			"draw" => intval($_POST["draw"]),
			"recordsFiltered" => $this->get_filtered_data(),
			"recordsTotal" => $this->get_total_data(),
			"data" => $data
		);

		echo json_encode($output);
	}

	public function make_query()
	{
		$this->db
			->select('c.id_customer_int')
			->select('c.id_customer')
			->select('c.nama_customer')
			->select('c.no_identitas')
			->select('c.no_hp')
			->select('c.alamat')
			->select('c.id_kelurahan')
			->select('c.id_kecamatan')
			->select('c.id_kabupaten')
			->select('c.id_provinsi')
			->select('c.id_tipe_kendaraan')
			->select('c.no_mesin')
			->select('c.no_rangka')
			->select('c.tahun_produksi')
			->select('c.no_polisi')
			->select('c.id_dealer')
			->select('c.is_ev')
			->select('kel.kelurahan, kec.kecamatan, kab.kabupaten, prov.provinsi, tk.tipe_ahm as tipe_kendaraan, w.warna')
			->select('tk.deskripsi_ahm as deskripsi_unit')
			->from('ms_customer_h23 as c')
			->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
			->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
			->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
			->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
			->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = c.id_tipe_kendaraan', 'left')
			->join('ms_warna as w', 'w.id_warna = c.id_warna', 'left');
	}

	public function make_datatables()
	{
		$this->make_query();

		// $search = trim($this->input->post('search')['value']);
		// if ($search != null) {
		// 	$this->db->group_start();
		// 	$this->db->like('c.id_customer', $search);
		// 	$this->db->or_like('c.nama_customer', $search);
		// 	$this->db->or_like('c.no_hp', $search);
		// 	$this->db->or_like('c.no_mesin', $search);
		// 	$this->db->or_like('c.no_rangka', $search);
		// 	$this->db->or_like('c.no_polisi', $search);
		// 	$this->db->or_like('c.no_identitas', $search);
		// 	$this->db->group_end();
		// }

		$search_cust = $this->input->post('search_cust');
        $search_nosin = $this->input->post('search_nosin');
        $search_norang = $this->input->post('search_norang');

        if($search_cust != ''){
            $this->db->group_start();
            $this->db->like('c.nama_customer', $search_cust);
            $this->db->group_end();
        }

        if ($search_nosin != '') {
            $this->db->group_start();
            $this->db->like('c.no_mesin', $search_nosin);
            $this->db->group_end();
        }

		if ($search_norang != '') {
            $this->db->group_start();
            $this->db->like('c.no_rangka', $search_norang);
            $this->db->group_end();
        }

		if (isset($_POST["order"])) {
			$indexColumn = $_POST['order']['0']['column'];
			$name = $_POST['columns'][$indexColumn]['name'];
			$data = $_POST['columns'][$indexColumn]['data'];
			$this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
		} else {
			$this->db->order_by('c.id_customer', 'asc');
		}
	}

	public function limit()
	{
		$this->load->model('m_admin');
		// if($this->m_admin->cari_dealer()==103){
		if(0){
			$this->db->limit(10,1);
		}else{
			if ($_POST["length"] != -1) {
				$this->db->limit($_POST['length'], $_POST['start']);
			}
		}
	}

	public function get_filtered_data()
	{
		$this->make_datatables();
		return $this->db->get()->num_rows();
	}

	public function get_total_data()
	{
		$this->make_query();
		return $this->db->get()->num_rows();
	}
}
