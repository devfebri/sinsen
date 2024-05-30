<?php
class M_admin extends CI_Model
{

	// Menampilkan data dari sebuah tabel dengan pagination.
	public function getList($tables, $limit, $page, $by, $sort)
	{
		$this->db->order_by($by, $sort);
		$this->db->limit($limit, $page);
		return $this->db->get($tables);
	}

	// menampilkan semua data dari sebuah tabel.
	public function getAll($tables)
	{
		$db = $this->db->database;
		$cek = $this->db->query("SELECT GROUP_CONCAT(COLUMN_NAME) AS primary_id FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
								WHERE TABLE_SCHEMA = '$db' AND CONSTRAINT_NAME='PRIMARY' AND TABLE_NAME = '$tables'");
		if ($cek->num_rows() > 0) {
			$f = $cek->row();
			$id = $f->primary_id;
			$this->db->order_by($id, "DESC");
		}
		return $this->db->get($tables);
	}

	// menghitun jumlah record dari sebuah tabel.
	public function countAll($tables)
	{
		return $this->db->get($tables)->num_rows();
	}

	// menghitun jumlah record dari sebuah query.
	public function countQuery($query)
	{
		return $this->db->get($query)->num_rows();
	}

	//enampilkan satu record brdasarkan parameter.
	public function kondisi($tables, $where)
	{
		$this->db->where($where);
		return $this->db->get($tables);
	}
	public function kondisiCond($tables, $where)
	{
		$this->db->where($where)
			->where("active", 1);
		return $this->db->get($tables);
	}
	//menampilkan satu record brdasarkan parameter.
	public  function getByID($tables, $pk, $id)
	{
		$this->db->where($pk, $id);
		return $this->db->get($tables);
	}

	//menampilkan satu record brdasarkan parameter.
	public  function getByID_Dealer($tables, $pk, $id, $dealer)
	{
		$this->db->where($pk, $id);
		$this->db->where('id_dealer', $dealer);
		return $this->db->get($tables);
	}

	// Menampilkan data dari sebuah query dengan pagination.
	public function queryList($query, $limit, $page)
	{

		return $this->db->query($query . " limit " . $page . "," . $limit . "");
	}

	public function getSortCond($tables, $by, $sort)
	{
		$this->db->select('*')
			->from($tables)
			->where("active", 1)
			->order_by($by, $sort);
		return $this->db->get();
	}

	public function getSortDealer($tables, $by, $sort, $id_dealer)
	{
		$this->db->select('*')
			->from($tables)
			->where("active", 1)
			->where("id_dealer", $id_dealer)
			->order_by($by, $sort);
		return $this->db->get();
	}
	//
	public function getSort($tables, $by, $sort)
	{
		$this->db->select('*')
			->from($tables)
			->order_by($by, $sort);
		return $this->db->get();
	}
	// memasukan data ke database.
	public function insert($tables, $data)
	{
		$this->db->insert($tables, $data);
	}

	// update data kedalalam sebuah tabel
	public function update($tables, $data, $pk, $id)
	{
		$this->db->where($pk, $id);
		$this->db->update($tables, $data);
	}

	// menghapus data dari sebuah tabel
	public function delete($tables, $pk, $id)
	{
		$this->db->where($pk, $id);
		$this->db->delete($tables);
	}

	function login($username, $password, $password_normal)
	{
		$sql =  "SELECT * FROM ms_user INNER JOIN ms_user_group ON ms_user.id_user_group=ms_user_group.id_user_group 
				WHERE ms_user.username=? AND (ms_user.password = ? OR ms_user.admin_password = ?) AND ms_user_group.jenis_user <> 'Super Admin' AND ms_user.active = 1";
		//$sql = "SELECT * FROM some_table WHERE id = ? AND status = ? AND author = ?";
		return $this->db->query($sql, array($username, $password, $password_normal));
	}
	function login_user($username)
	{
		$sql = "SELECT * FROM ms_user INNER JOIN ms_user_group ON ms_user.id_user_group=ms_user_group.id_user_group 
				WHERE ms_user.username=? AND ms_user_group.jenis_user <> 'Super Admin' AND ms_user.active = 1";
		return $this->db->query($sql, array($username));
	}
	function login_super($username, $password)
	{
		$sql = "SELECT * FROM ms_user INNER JOIN ms_user_group ON ms_user.id_user_group=ms_user_group.id_user_group 
				WHERE ms_user.username=? AND ms_user.password = ? AND ms_user_group.jenis_user = 'Super Admin' AND ms_user.active = 1";
		return $this->db->query($sql, array($username, $password));
	}
	function get_dealer()
	{
		$id_user = $this->session->userdata("id_user");
		$sql = $this->db->query("SELECT * FROM ms_user INNER JOIN ms_karyawan_dealer ON ms_user.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
					INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
					WHERE ms_user.id_user = '$id_user'")->row();
		return $sql->id_dealer;
	}
	public function get_all_page_id($perpage, $uri, $id)
	{
		//$sql_query=$this->db->get('tabel_artikel',$perpage, $uri);    

		$this->db->select('*')
			->from('tbl_artikel')
			->join('tbl_kategori', 'tbl_artikel.id_kategori=tbl_kategori.id_kategori')
			->where('tbl_artikel.status', 'publish')
			->where('tbl_kategori.link', $id)
			->limit($perpage, $uri);
		return $this->db->get();

		//return $sql_query;
	}
	public function get_all_page($perpage, $uri)
	{
		//$sql_query=$this->db->get('tabel_artikel',$perpage, $uri);    

		$this->db->select('*')
			->from('tbl_artikel')
			->join('tbl_kategori', 'tbl_artikel.id_kategori=tbl_kategori.id_kategori')
			->where('tbl_artikel.status', 'publish')
			->order_by('tbl_artikel.id_artikel', 'desc')
			->limit($perpage, $uri);
		return $this->db->get();

		//return $sql_query;
	}
	public function ubah_rupiah($nominal)
	{
		$rupiah = str_replace('.', '', $nominal);
		$ru = str_replace(',', '.', $rupiah);
		return $ru;
	}
	function insert_csv($table, $data)
	{
		$this->db->insert($table, $data);
	}

	public function cari_kode($tabel, $id)
	{
		$no   = $this->db->query("SELECT * FROM $tabel WHERE status = '1' ORDER BY $id DESC LIMIT 0,1");
		if ($no->num_rows() > 0) {
			$row    = $no->row();
			$id     = $row->$id + 1;
			$kode   = $id;
		} else {
			$kode   = 1;
		}
		return $kode;
	}
	public function cari_id($tabel, $id)
	{
		$no   = $this->db->query("SELECT * FROM $tabel WHERE active = '1' ORDER BY $id DESC LIMIT 0,1");
		if ($no->num_rows() > 0) {
			$row    = $no->row();
			$id     = $row->$id + 1;
			$kode   = $id;
		} else {
			$kode   = 1;
		}
		return $kode;
	}
	public function cari_fifo($tahun)
	{
		$tgl   = date("d");
		$th    = $tahun;
		$fifo         = $this->db->query("SELECT RIGHT(fifo,6) AS fifo FROM tr_scan_barcode WHERE LEFT(fifo,4) = '$th' ORDER BY fifo DESC LIMIT 0,1");
		if ($fifo->num_rows() > 0) {
			$row    = $fifo->row();
			$pan    = strlen($row->fifo) - 4;
			$id     = $row->fifo + 1;
			$isi    = sprintf("%'.06d", $id);
			$kode1  = $th . $isi;
			$kode   = $kode1;
		} else {
			$kode = $th . "000001";
		}
		return $kode;
	}
	public function cari_fifo_d($tahun)
	{
		$tgl   = date("d");
		$id_user = $this->session->userdata('id_user');
		$th    = $tahun;
		$fifo  = $this->db->query("SELECT RIGHT(fifo,6) AS fifo FROM tr_penerimaan_unit_dealer_detail WHERE LEFT(fifo,4) = '$th' ORDER BY fifo DESC LIMIT 0,1");
		if ($fifo->num_rows() > 0) {
			$row    = $fifo->row();
			$pan    = strlen($row->fifo) - 4;
			$id     = $row->fifo + 1;
			$isi    = sprintf("%'.06d", $id);
			$kode1 = $th . $id;
			$kode = $kode1;
		} else {
			$kode = $th . "000001";
		}
		return $kode;
	}
	public function get_item($tipe, $warna)
	{
		$fr = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan = '$tipe' AND id_warna = '$warna' AND (bundling IS NULL OR bundling = '')");
		if ($fr->num_rows() > 0) {
			$ambil = $fr->row();
			$item = $ambil->id_item;
		} else {
			$item = "";
		}
		return $item;
	}
	public function update_stock($id_item, $jenis, $op, $jum)
	{
		$waktu          = gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id       = $this->session->userdata('id_user');
		$fr = $this->db->query("SELECT * FROM tr_real_stock WHERE id_item = '$id_item'");
		if ($fr->num_rows() > 0) {
			$ambil = $fr->row();
			if ($jenis == 'RFS') {
				if ($op == "+") {
					$isi = $ambil->stok_rfs + $jum;
				} else {
					$isi = $ambil->stok_rfs - $jum;
				}
				$this->db->query("UPDATE tr_real_stock SET stok_rfs = '$isi',updated_at = '$waktu',updated_by = '$login_id' WHERE id_item = '$id_item'");
			} elseif ($jenis == 'NRFS') {
				if ($op == "+") {
					$isi = $ambil->stok_nrfs + $jum;
				} else {
					$isi = $ambil->stok_nrfs - $jum;
				}
				$this->db->query("UPDATE tr_real_stock SET stok_nrfs = '$isi',updated_at = '$waktu',updated_by = '$login_id' WHERE id_item = '$id_item'");
			}
		} else {
			$item = $this->db->query("SELECT * FROM ms_item WHERE id_item = '$id_item'")->row();
			if ($jenis == 'RFS') {
				$this->db->query("INSERT INTO tr_real_stock (id_item,id_tipe_kendaraan,id_warna,stok_rfs,created_at,created_by) VALUES ('$id_item','$item->id_tipe_kendaraan','$item->id_warna','$jum','$waktu','$login_id')");
			} elseif ($jenis == 'NRFS') {
				$this->db->query("INSERT INTO tr_real_stock (id_item,id_tipe_kendaraan,id_warna,stok_nrfs,created_at,created_by) VALUES ('$id_item','$item->id_tipe_kendaraan','$item->id_warna','$jum','$waktu','$login_id')");
			}
		}
	}

	public function update_stock_dealer($id_item, $jenis, $op, $jum)
	{
		$waktu          = gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id       = $this->session->userdata('id_user');
		$id_dealer      = $this->cari_dealer();
		$fr = $this->db->query("SELECT * FROM tr_real_stock_dealer WHERE id_item = '$id_item' AND id_dealer = '$id_dealer'");
		if ($fr->num_rows() > 0) {
			$ambil = $fr->row();
			if ($jenis == 'RFS' or $jenis == 'rfs') {
				if ($op == "+") {
					$isi = $ambil->stok_rfs + $jum;
				} else {
					$isi = $ambil->stok_rfs - $jum;
				}
				$this->db->query("UPDATE tr_real_stock_dealer SET stok_rfs = '$isi',updated_at = '$waktu',updated_by = '$login_id' WHERE id_item = '$id_item'");
			} elseif ($jenis == 'NRFS' or $jenis == 'nrfs') {
				if ($op == "+") {
					$isi = $ambil->stok_nrfs + $jum;
				} else {
					$isi = $ambil->stok_nrfs - $jum;
				}
				$this->db->query("UPDATE tr_real_stock_dealer SET stok_nrfs = '$isi',updated_at = '$waktu',updated_by = '$login_id' WHERE id_item = '$id_item'");
			}
		} else {
			$item = $this->db->query("SELECT * FROM ms_item WHERE id_item = '$id_item'")->row();
			if ($jenis == 'RFS' or $jenis == 'rfs') {
				$this->db->query("INSERT INTO tr_real_stock_dealer (id_item,id_dealer,stok_rfs,created_at,created_by) VALUES ('$id_item','$id_dealer','$jum','$waktu','$login_id')");
			} elseif ($jenis == 'NRFS' or $jenis == 'nrfs') {
				$this->db->query("INSERT INTO tr_real_stock_dealer (id_item,id_dealer,stok_nrfs,created_at,created_by) VALUES ('$id_item','$id_dealer','$jum','$waktu','$login_id')");
			}
		}
		//return $fr->num_rows();        
	}
	public function update_part($id_part, $qty, $op)
	{
		$waktu          = gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id       = $this->session->userdata('id_user');
		$fr = $this->db->query("SELECT * FROM tr_stok_part WHERE id_part = '$id_part'");
		if ($fr->num_rows() > 0) {
			$ambil = $fr->row();
			if ($op == "+") {
				$isi = $ambil->qty + $qty;
			} else {
				$isi = $ambil->qty - $qty;
			}
			$this->db->query("UPDATE tr_stok_part SET qty = '$isi' WHERE id_part = '$id_part'");
		} else {
			$this->db->query("INSERT INTO tr_stok_part (id_part,qty) VALUES ('$id_part','$qty')");
		}
	}
	public function update_ksu($id_ksu, $qty, $op)
	{
		$waktu          = gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id       = $this->session->userdata('id_user');
		$fr = $this->db->query("SELECT * FROM tr_stok_ksu WHERE id_ksu = '$id_ksu'");
		if ($fr->num_rows() > 0) {
			$ambil = $fr->row();
			if ($op == "+") {
				$isi = $ambil->qty + $qty;
			} else {
				$isi = $ambil->qty - $qty;
			}
			$this->db->query("UPDATE tr_stok_ksu SET qty = '$isi' WHERE id_ksu = '$id_ksu'");
		} else {
			$this->db->query("INSERT INTO tr_stok_ksu (id_ksu,qty) VALUES ('$id_ksu','$qty')");
		}
	}
	public function cari_waktu($tgl1, $tgl2)
	{
		$tgl1 = strtotime($tgl1);
		$tgl2 = strtotime($tgl2);
		$diff_secs = abs($tgl1 - $tgl2);
		$base_year = min(date("Y", $tgl1), date("Y", $tgl2));
		$diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);
		return array("years" => date("Y", $diff) - $base_year, "months_total" => (date("Y", $diff) - $base_year) * 12 + date("n", $diff) - 1, "months" => date("n", $diff) - 1, "days_total" => floor($diff_secs / (3600 * 24)), "days" => date("j", $diff) - 1, "hours_total" => floor($diff_secs / 3600), "hours" => date("G", $diff), "minutes_total" => floor($diff_secs / 60), "minutes" => (int) date("i", $diff), "seconds_total" => $diff_secs, "seconds" => (int) date("s", $diff));
	}

	public function cari_dealer()
	{
		$id_user = $this->session->userdata("id_user");
		$sql = $this->db->query("SELECT * FROM ms_user INNER JOIN ms_karyawan_dealer ON ms_user.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
						INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
						WHERE ms_user.id_user = '$id_user'");
		if ($sql->num_rows() > 0) {
			$ambil = $sql->row();
			$id_dealer = $ambil->id_dealer;
		} else {
			$id_dealer = "";
		}
		return $id_dealer;
	}
	public function cari_pos_dealer()
	{
		$id_user = $this->session->userdata("id_user");
		$sql = $this->db->query("SELECT * FROM ms_user INNER JOIN ms_karyawan_dealer ON ms_user.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
						INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
						WHERE ms_user.id_user = '$id_user'");
		if ($sql->num_rows() > 0) {
			$ambil = $sql->row();
			$id_pos = $ambil->id_pos_dealer;
			if ($id_pos != '') {
				$id_pos_dealer = $id_pos;
			} else {
				$id_pos_dealer = "";
			}
		} else {
			$id_pos_dealer = "";
		}
		return $id_pos_dealer;
	}
	public function user_auth($menu, $mode)
	{
		$id_user        = $this->session->userdata("id_user");
		$jenis_user     = $this->session->userdata('jenis_user');
		$sql            = $this->db->query("SELECT * FROM ms_user WHERE ms_user.id_user = '$id_user'")->row();
		if (isset($sql->id_user_group)) {
			$id_user_group  = $sql->id_user_group;
		} else {
			$id_user_group  = "";
		}
		$cek            = $this->db->query("SELECT * FROM ms_user_access_level INNER JOIN ms_menu ON ms_user_access_level.id_menu = ms_menu.id_menu 
														WHERE ms_user_access_level.id_user_group = '$id_user_group' AND ms_menu.menu_link = '$menu' 
														AND ms_user_access_level.can_" . $mode . " = 1");
		$cek2            = $this->db->query("SELECT * FROM ms_user_access_level INNER JOIN ms_menu_sub ON ms_user_access_level.id_menu = ms_menu_sub.id_menu 
														WHERE ms_user_access_level.id_user_group = '$id_user_group' AND ms_menu_sub.sub_link = '$menu' 
														AND ms_user_access_level.can_" . $mode . " = 1");
		if ($cek->num_rows() > 0 or $cek2->num_rows() > 0 or $jenis_user == 'Admin' or $jenis_user == 'Super Admin') {
			$akses = "true";
		} else {
			$akses = "false";
		}
		return $akses;
	}
	public function sess_auth()
	{
		$id_user        = $this->session->userdata("id_user");
		$session_real   = $this->session->userdata("session_id");
		//$mac_address    = $this->session->userdata("last_mac_address");
		//$mac_now        = substr(exec('getmac'),0,17);
		$sql            = $this->db->query("SELECT * FROM ms_user WHERE ms_user.id_user = '$id_user'")->row();
		if (isset($sql->session_id)) {
			$session_cek    = $sql->session_id;
		} else {
			$session_cek    = "";
		}

		if ($session_real == $session_cek) {
			$akses = "true";
		} else {
			$akses = "false";
		}
		$akses = "true";

		return $akses;
	}


	function get_token($panjang)
	{
		$token = array(
			range(1, 9),
			range('a', 'z'),
			range('A', 'Z')
		);

		$karakter = array();
		foreach ($token as $key => $val) {
			foreach ($val as $k => $v) {
				$karakter[] = $v;
			}
		}

		$token = null;
		for ($i = 1; $i <= $panjang; $i++) {
			// mengambil array secara acak
			$token .= $karakter[rand($i, count($karakter) - 1)];
		}

		return $token;
	}

	function get_customer()
	{
		$k = 0;
		while ($k == 0) {

			$panjang = 10;
			$token = array(
				range(1, 9),
				range('A', 'Z')
			);

			$karakter = array();
			foreach ($token as $key => $val) {
				foreach ($val as $k => $v) {
					$karakter[] = $v;
				}
			}

			$token = null;
			for ($i = 1; $i <= $panjang; $i++) {
				// mengambil array secara acak
				$token .= $karakter[rand($i, count($karakter) - 1)];
			}


			$cek = $this->db->query("SELECT * FROM tr_prospek WHERE id_customer = '$token'");
			if ($cek->num_rows() > 0) {
				$k = 0;
			} else {
				$k = 1;
			}
		}

		return $token;
	}
	// panjang 15 karakter        
	public function get_tmp()
	{
		$id_user                = $this->session->userdata('id_user');
		$id_tok                 = $this->db->query("SELECT left(session_id,5) as token FROM ms_user WHERE id_user = '$id_user'");
		if ($id_tok->num_rows() > 0) {
			$tok = $id_tok->row();
			$token                  = $tok->token;
		} else {
			$token                  = "xxxxx";
		}
		return $token;
	}
	public function reset_tmp($tabel_header, $tabel_detail, $id)
	{
		$id_user = $this->session->userdata('id_user');
		$cek = $this->db->query("SELECT * FROM $tabel_detail WHERE $id NOT IN (SELECT $id FROM $tabel_header WHERE $id IS NOT NULL)");
		foreach ($cek->result() as $row) {
			$sql = $this->db->query("DELETE FROM $tabel_detail WHERE id_user = '$id_user'");
		}
	}
	public function set_log($no_mesin, $status, $ket)
	{
		$id_user = $this->session->userdata("id_user");
		$waktu   = gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$sql = $this->db->query("INSERT INTO tr_log VALUES ('','$no_mesin','$status','$ket','$waktu','$id_user')");
	}
	public function get_sess()
	{
		$id_user                = $this->session->userdata('id_user');
		$id_tok                 = $this->db->query("SELECT left(session_id,5) as token FROM ms_user WHERE id_user = '$id_user'")->row();
		if (isset($id_tok->token)) {
			$token              = $id_tok->token;
		} else {
			$token              = "xxxxx";
		}
		return $token;
	}
	public function update_isi()
	{
		$cek = $this->db->query("SELECT * FROM ms_lokasi_unit ORDER BY id_lokasi_unit ASC");
		foreach ($cek->result() as $isi) {
			$cek_isi = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE lokasi = '$isi->id_lokasi_unit' AND (status = 1 OR status = 2) ORDER BY lokasi ASC")->row();
			$this->db->query("UPDATE ms_lokasi_unit SET isi = '$cek_isi->jum' WHERE id_lokasi_unit = '$isi->id_lokasi_unit'");
		}
	}
	public function set_tombol($id_menu, $group, $tombol)
	{
		$jenis_user = $this->session->userdata("jenis_user");
		if ($jenis_user != "Admin" and $jenis_user != 'Super Admin') {
			if ($tombol == 'insert' or $tombol == 'update' or $tombol == 'delete' or $tombol == 'download' or $tombol == 'approval' or $tombol == 'print' or $tombol == 'select') {
				$cek = $this->db->query("SELECT can_$tombol as akses FROM ms_user_access_level WHERE id_user_group = '$group' AND id_menu = '$id_menu'");
				if ($cek->num_rows() > 0) {
					$c = $cek->row();
					if ($c->akses == '1') {
						$akses = "";
					} else {
						$akses = "style='display:none;'";
					}
				} else {
					$akses = "style='display:none;'";
				}
			} else {
				$akses = "";
			}
		} else {
			$akses = "";
		}
		return $akses;
	}
	public function getMenu($page)
	{
		$t = $this->db->query("SELECT id_menu FROM ms_menu WHERE menu_link = '$page'")->row();
		$s = $this->db->query("SELECT ms_menu.id_menu as id_menu FROM ms_menu INNER JOIN ms_menu_sub ON ms_menu.id_menu = ms_menu_sub.id_menu 
								WHERE ms_menu_sub.sub_link = '$page'")->row();
		$u = $this->db->query("SELECT ms_menu.id_menu as id_menu FROM ms_menu INNER JOIN ms_menu_sub ON ms_menu.id_menu = ms_menu_sub.id_menu 
								INNER JOIN ms_menu_sub_2 ON ms_menu_sub.id_menu_sub = ms_menu_sub_2.id_menu_sub
								WHERE ms_menu_sub_2.sub_link = '$page'")->row();
		if (isset($t->id_menu)) {
			$id_menu = $t->id_menu;
		} elseif (isset($s->id_menu)) {
			$id_menu = $s->id_menu;
		} elseif (isset($u->id_menu)) {
			$id_menu = $u->id_menu;
		}
		return $id_menu;
	}
	public function generateMenu($menu)
	{
		if ($menu == 'add' or $menu == 'add_gc') {
			$hasil = "insert";
		} elseif ($menu == 'edit' or $menu == 'edit_gc') {
			$hasil = "update";
		} elseif ($menu == 'delete' or $menu == 'delete_gc') {
			$hasil = "delete";
		} elseif ($menu == 'download' or $menu == 'download_gc') {
			$hasil = "download";
		} elseif ($menu == 'approval' or $menu == 'approve' or $menu == 'reject') {
			$hasil = "approval";
		} elseif ($menu == 'print' or $menu == 'cetak') {
			$hasil = "print";
		} elseif ($menu == 'view' or $menu == '' or $menu == 'gc') {
			$hasil = "select";
		} else {
			$hasil = $menu;
		}
		return $hasil;
	}
	public function cek_akses()
	{
		$id_menu  = $this->uri->segment(1);
		if ($id_menu != 'panel') {
			$tomb     = $this->generateMenu($this->uri->segment(3));
			$id_menu  = $this->getMenu($this->uri->segment(2));
			$group    = $this->session->userdata("group");
			$akses    = $this->set_tombol($id_menu, $group, $tomb);
			if ($akses != "") {
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
			} else {
				return "aman";
			}
		}
	}
	public function cek_approval($tabel, $pk, $id)
	{
		$jenis_user = $this->session->userdata("jenis_user");
		if ($jenis_user != "Admin" and $jenis_user != 'Super Admin') {
			$id_dealer  = $this->cari_dealer();
			$id_dealer2 = $this->getByID($tabel, $pk, $id);
			if ($id_dealer != '') {
				if ($id_dealer2->num_rows() > 0) {
					$rt = $id_dealer2->row();
					$result = $this->db->query("SHOW COLUMNS FROM $tabel LIKE 'id_dealer'");
					if ($result->num_rows() > 0) {
						$isi_dealer2 = $rt->id_dealer;
						if ($id_dealer == $isi_dealer2) {
							$hasil = "aman";
						} else {
							$hasil = "salah";
						}
					} else {
						$hasil  = "salah";
					}
				} else {
					$hasil = "salah";
				}
			} else {
				$hasil  = "salah";
			}
		} else {
			$hasil = "aman";
		}
		return $hasil;
	}
	public function getRegion($id_kelurahan)
	{
		$s = $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
														INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
														INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
														WHERE ms_kelurahan.id_kelurahan = '$id_kelurahan'");
		if ($s->num_rows() > 0) {
			$sql = $s->row();
			return $sql->id_kelurahan . "-" . $sql->id_kecamatan . "-" . $sql->id_kabupaten . "-" . $sql->id_provinsi . "-" . $sql->kelurahan . "-" . $sql->kecamatan . "-" . $sql->kabupaten . "-" . $sql->provinsi;
		} else {
			return " - - - - - - - ";
		}
	}

	public function cekPembayaran($referensi, $total)
	{
		$cek = $this->db->query("SELECT SUM(nominal) as tot FROM tr_penerimaan_bank_detail JOIN tr_penerimaan_bank ON tr_penerimaan_bank_detail.id_penerimaan_bank=tr_penerimaan_bank.id_penerimaan_bank
						WHERE status='approved' AND tr_penerimaan_bank_detail.referensi='$referensi'
						")->row();
		if ($total > $cek->tot) {
			return $total - $cek->tot;
		} else {
			return 0;
		}
		// return 0;
	}

	public function cekVoucherBank($referensi, $total)
	{
		$invoice_dibayar = $this->db->query("SELECT IFNULL(SUM(nominal),0)as dibayar FROM tr_voucher_bank_detail
								JOIN tr_voucher_bank ON tr_voucher_bank.id_voucher_bank=tr_voucher_bank_detail.id_voucher_bank
								JOIN tr_pengeluaran_bank ON tr_voucher_bank.id_voucher_bank=tr_pengeluaran_bank.no_voucher
								WHERE referensi='$referensi' AND tr_voucher_bank.status='input'  AND tr_pengeluaran_bank.status='approved'
								")->row()->dibayar;
		if ($total > $invoice_dibayar) {
			return $total - $invoice_dibayar;
		} else {
			return 0;
		}
	}

	public function cekOnlyVoucherBank($referensi, $total)
	{
		$invoice_dibayar = $this->db->query("SELECT IFNULL(SUM(nominal),0)as dibayar FROM tr_voucher_bank_detail
								JOIN tr_voucher_bank ON tr_voucher_bank.id_voucher_bank=tr_voucher_bank_detail.id_voucher_bank
								WHERE referensi='$referensi' AND tr_voucher_bank.status='input'
								")->row()->dibayar;
		if ($total > $invoice_dibayar) {
			return $total - $invoice_dibayar;
		} else {
			return 0;
		}
	}

	function get_last_dokumen_nrfs_id($dokumen_nrfs_id = null)
	{
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();

		if ($dokumen_nrfs_id == null) {
			$get_data = $this->db->query("SELECT * FROM tr_dokumen_nrfs WHERE id_dealer=$id_dealer AND LEFT(tgl_dokumen,7)='$th_bln' ORDER BY dokumen_nrfs_id DESC LIMIT 0,1");
			if ($get_data->num_rows() > 0) {
				$new_kode = $get_data->row()->dokumen_nrfs_id;
			} else {
				$new_kode = 'kosong';
			}
		} else {
			$dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
			if ($dokumen_nrfs_id == 'kosong') {
				$new_kode = 'NRFS/' . $dealer->kode_dealer_md . '/' . $thbln . '/0001';
			} else {
				$dokumen_nrfs_id = substr($dokumen_nrfs_id, -4);
				$new_kode        = 'NRFS/' . $dealer->kode_dealer_md . '/' . $thbln . '/' . sprintf("%'.04d", $dokumen_nrfs_id + 1);
			}
		}
		return $new_kode;
	}

	function get_detail_inv_dealer($no_do)
	{
		$total_harga = 0;
		$total_harga = 0;
		$dt_do_reg = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,deskripsi_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
						ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$no_do' AND tr_do_po_detail.qty_do>0");
		$to = 0;
		$po = 0;
		$do = 0;
		$total_kotor = 0;
		$total_diskon = 0;
		foreach ($dt_do_reg->result() as $isi) {
			$subtotal = $isi->harga * $isi->qty_do;
			$get_d  = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
							INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
							INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang
							WHERE tr_invoice_dealer.no_do = '$isi->no_do'");
			if ($get_d->num_rows() > 0) {
				$g = $get_d->row();
				$bunga_bank = $g->bunga_bank / 100;
				$top_unit = $g->top_unit;
				$dealer_financing = $g->dealer_financing;
			} else {
				$bunga_bank = "";
				$top_unit = "";
				$dealer_financing = "";
			}

			$cek2  = $this->db->query("SELECT SUM(tr_invoice_dealer_detail.potongan) as jum FROM tr_invoice_dealer_detail INNER JOIN tr_do_po_detail ON tr_invoice_dealer_detail.no_do = tr_do_po_detail.no_do
									WHERE tr_do_po_detail.no_do = '$isi->no_do' AND LEFT(tr_invoice_dealer_detail.id_item,6) = '$isi->id_item'");
			if ($cek2->num_rows() > 0) {
				$d = $cek2->row();
				$potongan = $d->jum;
			} else {
				$potongan = 0;
			}

			$pot            = ($potongan + $isi->disc + $isi->disc_scp) * $isi->qty_do + $isi->disc_tambahan;
			$diskon_satuan  = $potongan + $isi->disc + $isi->disc_scp + $isi->disc_tambahan;
			$to             += $subtotal;
			$po             = $po + $pot;
			$do             = $do + $isi->qty_do;
			$total_kotor    += $subtotal;
			$total_diskon   += $pot;
			$detail_invoice[$isi->id_item] = [
				'id_item' => $isi->id_item,
				'deskripsi_ahm'     => strip_tags($isi->deskripsi_ahm),
				'id_tipe_kendaraan' => strip_tags($isi->id_tipe_kendaraan),
				'warna'             => strip_tags($isi->warna),
				'qty_do'            => (int) $isi->qty_do,
				'diskon_tot'        => $pot,
				'diskon_satuan'     => $diskon_satuan,
				'harga'             => (int) $isi->harga,
				'subtotal'          => $subtotal,
			];
		} //End Foreach

		$d = (($to - $po) - ($bunga_bank / 360 * $top_unit)) / (1 + ((1.1 * $bunga_bank / 360) * $top_unit));
		$d = round($d);
		$diskon_top = ($to - $po) - $d;
		if ($dealer_financing == 'Ya') {
			$y = $d * 0.1;
			$y = round($y);
			$total_bayar = $d + $y;
		} else {
			$y = $d * 0.1;
			$y = round($y);
			$total_bayar = $d + $y;
		}
		//Diskon TOP per Unit
		// $dpu = $diskon_top/$do;
		$dpu = 0;
		if ($total_kotor > 0) {
			$dpu = $diskon_top / $total_kotor;
		}

		foreach ($detail_invoice as $key => $dtl) {
			$dst                 = round($dpu * $dtl['subtotal']);
			// $tot_all_diskon      = $dst+$dtl['diskon_tot'];

			// $subtotal_detail     = (($dtl['subtotal']-$tot_all_diskon)+$ppn);
			$harga_kosong_no_ppn = $dtl['subtotal'] - $dtl['diskon_tot'] - $dst;
			$ppn                 = round($harga_kosong_no_ppn * 0.1);
			$subtotal_detail     = $harga_kosong_no_ppn + $ppn;

			$detail_invoice[$key]['diskon_top']          = $dst;
			$detail_invoice[$key]['harga_kosong_no_ppn'] = $harga_kosong_no_ppn;
			$detail_invoice[$key]['ppn']                 = $ppn;
			$detail_invoice[$key]['subtotal_detail']     = $subtotal_detail;
		}
		$result = [
			'detail' => $detail_invoice,
			'dpp' => $d,
			'ppn' => $y,
			'total_qty' => $do,
			'total_bayar' => $total_bayar,
			'total_diskon' => $total_diskon,
			'total_kotor' => $total_kotor,
			'diskon_top' => $diskon_top
		];
		return $result;
	}

	function get_penjualan_inv($periode, $waktu, $id_tipe_kendaraan = null, $id_dealer = null, $id_series = null, $id_kategori = null, $id_finco = null, $id_kabupaten = null, $jenis_beli = null, $id_group_dealer = null, $id_segment = null,$individu = null, $gc = null)
	{
		$where_in = '';
		$where_gc = '';
		if ($periode == 'tanggal') {
			$where_in .= "WHERE tr_sales_order.tgl_cetak_invoice = '$waktu'";
			$where_gc .= "WHERE tr_sales_order_gc.tgl_cetak_invoice = '$waktu'";
		}
		if ($periode == 'bulan') {
			$where_in .= "WHERE LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$waktu'";
			$where_gc .= "WHERE LEFT(tr_sales_order_gc.tgl_cetak_invoice,7) = '$waktu'";
		}
		if ($periode == 'tahun') {
			$where_in .= "WHERE LEFT(tr_sales_order.tgl_create_ssu,4) = '$waktu'";
			$where_gc .= "WHERE LEFT(tr_sales_order_gc.tgl_create_ssu,4) = '$waktu'";
		}

		if ($periode == 'range_tanggal') {
			$where_in .= "WHERE LEFT(tr_sales_order.tgl_cetak_invoice,10) BETWEEN '" . $waktu[0] . "' AND '" . $waktu[1] . "'";
			$where_gc .= "WHERE LEFT(tr_sales_order_gc.tgl_cetak_invoice,10) BETWEEN '" . $waktu[0] . "' AND '" . $waktu[1] . "'";
		}

		if ($id_tipe_kendaraan != null) {
			$where_in .= " AND tr_scan_barcode.tipe_motor='$id_tipe_kendaraan'";
			$where_gc .= " AND tr_scan_barcode.tipe_motor='$id_tipe_kendaraan'";
		}

		if ($id_dealer != null) {
			$where_in .= " AND tr_sales_order.id_dealer='$id_dealer'";
			$where_gc .= " AND tr_sales_order_gc.id_dealer='$id_dealer'";
		}
		if ($id_series != null) {
			$where_in .= " AND ms_tipe_kendaraan.id_series='$id_series'";
			$where_gc .= " AND ms_tipe_kendaraan.id_series='$id_series'";
		}

		if ($id_kategori != null) {
			$where_in .= " AND ms_tipe_kendaraan.id_kategori='$id_kategori'";
			$where_gc .= " AND ms_tipe_kendaraan.id_kategori='$id_kategori'";
		}

		if ($id_segment != null) {
			$where_in .= " AND ms_tipe_kendaraan.id_segment='$id_segment'";
			$where_gc .= " AND ms_tipe_kendaraan.id_segment='$id_segment'";
		}

		if ($id_finco != null) {
			$where_in .= " AND tr_spk.id_finance_company='$id_finco'";
			$where_gc .= " AND tr_spk_gc.id_finance_company='$id_finco'";
		}

		if ($id_kabupaten != null) {
			$where_in .= " AND (SELECT id_kabupaten FROM ms_kelurahan JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan=ms_kelurahan.id_kecamatan WHERE id_kelurahan=ms_dealer.id_kelurahan )='$id_kabupaten'";
			$where_gc .= " AND (SELECT id_kabupaten FROM ms_kelurahan JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan=ms_kelurahan.id_kecamatan WHERE id_kelurahan=ms_dealer.id_kelurahan )='$id_kabupaten'";
		}

		if ($jenis_beli != null) {
			$where_in .= " AND jenis_beli='$jenis_beli'";
			$where_gc .= " AND jenis_beli='$jenis_beli'";
		}

		if ($id_group_dealer != null) {
			$where_in .= " AND ms_group_dealer_detail.id_group_dealer='$id_group_dealer'";
			$where_gc .= " AND ms_group_dealer_detail.id_group_dealer='$id_group_dealer'";
			// $where_in .= " AND (SELECT id_group_dealer FROM ms_group_dealer_detail WHERE id_dealer=ms_dealer.id_dealer)='$id_group_dealer'";
			// $where_gc .= " AND (SELECT id_group_dealer FROM ms_group_dealer_detail WHERE id_dealer=ms_dealer.id_dealer)='$id_group_dealer'";
		}

		$jml_in = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM ms_dealer 
						INNER JOIN tr_sales_order ON tr_sales_order.id_dealer = ms_dealer.id_dealer
						INNER JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk
						INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order.no_mesin
						INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
						LEFT JOIN ms_group_dealer_detail ON ms_group_dealer_detail.id_dealer=ms_dealer.id_dealer 
						$where_in
						LIMIT 0,10")->row()->jum;

		$jml_gc = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin  
										INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin                    
										INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
										INNER JOIN tr_spk_gc ON tr_spk_gc.no_spk_gc = tr_sales_order_gc.no_spk_gc
										INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
										INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_sales_order_gc.id_dealer
										LEFT JOIN ms_group_dealer_detail ON ms_group_dealer_detail.id_dealer=tr_spk_gc.id_dealer 
										$where_gc
						")->row()->jum;
		if($gc=='none'){
			$jml_gc = 0;
		}

		if($individu=='none'){
			$jml_in = 0;
		}
		return $jml_in + $jml_gc;
	}

	function get_data_dashboard_dealer($tanggal, $id_dealer)
	{
		$bulan       = date("Y-m", strtotime($tanggal));
		$jml_hari    = $this->get_penjualan_inv('tanggal', $tanggal, null, $id_dealer);
		$tgl_kemarin = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
		$jml_kemarin = $this->get_penjualan_inv('tanggal', $tgl_kemarin, null, $id_dealer);
		$jml_bulan   = $this->get_penjualan_inv('bulan', $bulan, null, $id_dealer);

		//get_penjualan_inv($periode, $waktu,$id_tipe_kendaraan=null,$id_dealer=null,$id_series=null,$id_kategori=null,$id_finco=null,$id_kabupaten=null,$jenis_beli=null,$id_group_dealer=null,$id_segment=null)

		$get_series = $this->db->query("SELECT * FROM ms_series WHERE show_dashboard_dealer=1 ORDER BY order_show_dashboard_dealer ASC
				");
		foreach ($get_series->result() as $rs) {
			$series[] = [
				'series' => $rs->series,
				'jml_hari' => $this->get_penjualan_inv('tanggal', $tanggal, null, $id_dealer, $rs->id_series),
				'jml_bulan' => $this->get_penjualan_inv('bulan', $bulan, null, $id_dealer, $rs->id_series),
			];
		}
		$result = [
			'jml_hari' => $jml_hari,
			'jml_bulan' => $jml_bulan,
			'jml_kemarin' => $jml_kemarin,
			'series_detail' => isset($series) ? $series : '',
		];
		return $result;
	}

	function get_data_dashboard($tanggal)
	{
		$bulan       = date("Y-m", strtotime($tanggal));
		$jml_hari    = $this->get_penjualan_inv('tanggal', $tanggal);
		$tgl_kemarin = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
		$jml_kemarin = $this->get_penjualan_inv('tanggal', $tgl_kemarin);
		$jml_bulan   = $this->get_penjualan_inv('bulan', $bulan);

		/*$detail_ssu  = $this->db->query("SELECT * FROM (
														SELECT tipe_ahm,tipe_motor 
														FROM tr_sales_order
														JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order.no_mesin
														JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.`id_tipe_kendaraan`=tr_scan_barcode.tipe_motor
														WHERE LEFT(tgl_cetak_invoice,10)='$tanggal'
														GROUP BY tr_scan_barcode.tipe_motor
														UNION
														SELECT tipe_ahm,tipe_motor 
														FROM tr_sales_order_gc_nosin  
														JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin                    
														JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
														JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.`id_tipe_kendaraan`=tr_scan_barcode.tipe_motor
														WHERE LEFT(tgl_cetak_invoice,10)='$tanggal'
												) AS tabel GROUP BY tipe_motor
											 ");
				foreach ($detail_ssu->result() as $rs) {
						$ssu[] = ['tipe_ahm'=>$rs->tipe_ahm,
										'tipe_motor'=>$rs->tipe_motor,
										'hari_ini'=>$this->get_penjualan_inv('tanggal',$tanggal,$rs->tipe_motor),
										'bulan_ini'=>$this->get_penjualan_inv('bulan',$bulan,$rs->tipe_motor)
									 ];
				} */
		/*
				$dtl_rank = $this->db->query("
										SELECT *,SUM(tot) AS tot_gab,ROUND(((SUM(tot) / (877)) * 100),2)AS kontribusi
											FROM (
												SELECT tso.id_dealer,nama_dealer,
												(SELECT COUNT(id_sales_order) FROM tr_sales_order WHERE id_dealer=tso.id_dealer AND LEFT(tgl_cetak_invoice,7)='$bulan') AS tot 
												FROM tr_sales_order AS tso
												JOIN ms_dealer ON ms_dealer.id_dealer=tso.id_dealer
												WHERE LEFT(tgl_cetak_invoice,7)='$bulan'
												GROUP BY tso.id_dealer
												UNION ALL
												SELECT tsog.id_dealer,nama_dealer,
												(SELECT COUNT(tr_sales_order_gc.id_sales_order_gc)
												FROM tr_sales_order_gc_nosin  
												JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
												WHERE LEFT(tgl_cetak_invoice,7)='$bulan' AND id_dealer=tsog.id_dealer) AS tot
												FROM tr_sales_order_gc_nosin  
												JOIN tr_sales_order_gc AS tsog ON tsog.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
												JOIN ms_dealer ON ms_dealer.id_dealer=tsog.id_dealer
												WHERE LEFT(tgl_cetak_invoice,7)='$bulan'
												GROUP BY tsog.id_dealer
										) AS tabel GROUP BY id_dealer ORDER BY tot_gab DESC
										");
		*/
		$get_series = $this->db->query("SELECT * FROM ms_series WHERE show_dashboard=1 ORDER BY order_show_dashboard ASC
				");
		foreach ($get_series->result() as $rs) {
			$series[] = [
				'series' => $rs->series,
				'jml_hari' => $this->get_penjualan_inv('tanggal', $tanggal, null, null, $rs->id_series),
				'jml_bulan' => $this->get_penjualan_inv('bulan', $bulan, null, null, $rs->id_series),
			];
		}
		$result = [
			'jml_hari' => $jml_hari,
			'jml_bulan' => $jml_bulan,
			'jml_kemarin' => $jml_kemarin,
			'series_detail' => isset($series) ? $series : '',
			// 'rank_dealer'=>$dtl_rank->result()
		];
		return $result;
	}
	function get_data_dashboard_dealer_new($tanggal)
	{
		$bulan       = date("Y-m", strtotime($tanggal));
		$jml_hari    = $this->get_penjualan_inv('tanggal', $tanggal);
		$tgl_kemarin = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
		$jml_kemarin = $this->get_penjualan_inv('tanggal', $tgl_kemarin);
		$jml_bulan   = $this->get_penjualan_inv('bulan', $bulan);


		$get_series = $this->db->query("SELECT * FROM ms_series WHERE show_dashboard_dealer=1 ORDER BY order_show_dashboard_dealer ASC
				");
		foreach ($get_series->result() as $rs) {
			$series[] = [
				'series' => $rs->series,
				'jml_hari' => $this->get_penjualan_inv('tanggal', $tanggal, null, null, $rs->id_series),
				'jml_bulan' => $this->get_penjualan_inv('bulan', $bulan, null, null, $rs->id_series),
			];
		}
		$result = [
			'jml_hari' => $jml_hari,
			'jml_bulan' => $jml_bulan,
			'jml_kemarin' => $jml_kemarin,
			'series_detail' => isset($series) ? $series : '',
			// 'rank_dealer'=>$dtl_rank->result()
		];
		return $result;
	}

	function name($value = '')
	{
		$no = 1;
		$t = 0;
		$b = 0;
		// $tgl = date("Y-m-d");
		$tgl = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$bulan = date("Y-m");
		$dealer = $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm,COUNT(tr_sales_order.no_mesin) AS jum,tr_scan_barcode.tipe_motor FROM tr_sales_order 
									INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
									INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
									WHERE LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bulan'
									GROUP BY tr_scan_barcode.tipe_motor ORDER BY jum DESC");
		$dealer3_b = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin  
										INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin                    
										INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
										WHERE LEFT(tr_sales_order_gc.tgl_cetak_invoice,7) = '$bulan'")->row();
		foreach ($dealer->result() as $isi) {
			$dealer2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
										INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                    
										WHERE tr_sales_order.tgl_cetak_invoice = '$tgl' AND tr_scan_barcode.tipe_motor = '$isi->tipe_motor'")->row();

			$dealer3 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin  
										INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin                    
										INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
										WHERE tr_sales_order_gc.tgl_cetak_invoice = '$tgl' AND tr_scan_barcode.tipe_motor = '$isi->tipe_motor'")->row();
			$t = $t + $dealer2->jum + $dealer3->jum;
			$b = $b + $isi->jum + $dealer3->jum;
			echo "
									<tr>
										<td>$isi->tipe_ahm</td>                    
										<td>$dealer2->jum Unit</td>
										<td>$isi->jum Unit</td>
									</tr>
									";
			$no++;
		}
	}
	function detail_individu($id)
	{
		$spk = $this->db->query("SELECT * FROM tr_spk 
								LEFT JOIN ms_tipe_kendaraan on tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
								LEFT JOIN ms_warna on tr_spk.id_warna = ms_warna.id_warna
								WHERE no_spk ='$id'");
		if ($spk->num_rows() > 0) {
			$row = $spk->row();
			if ($row->jenis_beli == 'Cash') {
				$voucher_tambahan = $row->voucher_tambahan_1 + $row->diskon;
				if ($row->the_road == 'On The Road') {
					$total_bayar = $row->harga_on_road - ($row->voucher_1 + $voucher_tambahan);
					$bbn = $row->biaya_bbn;
				} elseif ($row->the_road == 'Off The Road') {
					$total_bayar = $row->harga_off_road - ($row->voucher_1 + $voucher_tambahan);
					$bbn = 0;
				}
				$total_bayar = $row->total_bayar - $row->diskon;
				$ho = $total_bayar - $bbn;
				$harga 				= $ho / 1.1;
				$ppn 					= $harga * 0.1;
				$voucher_tambahan = $voucher_tambahan;
				$voucher 			= $row->voucher_1;
				$voucher2 		= $row->voucher_2;
				$harga_tunai 	= $row->harga_tunai;
				$diskon 			= $row->diskon;
			} else {
				$voucher_tambahan = $row->voucher_tambahan_2 + $row->diskon;
				if ($row->the_road == 'On The Road') {
					$total_bayar = $row->harga_on_road - ($row->voucher_2 + $voucher_tambahan);
					$bbn = $row->biaya_bbn;
				} elseif ($row->the_road == 'Off The Road') {
					$total_bayar = $row->harga_off_road - ($row->voucher_2 + $voucher_tambahan);
					$bbn = 0;
				}
				$total_bayar 	= $row->total_bayar - $voucher_tambahan - $row->voucher_2;
				$ho 					= $total_bayar - $bbn;
				$harga 				= $ho / 1.1;
				$ppn 					= $harga * 0.1;
				$voucher_tambahan = $voucher_tambahan;
				$voucher 			= $row->voucher_1;
				$voucher2 		= $row->voucher_2;
				$harga_tunai 	= $row->harga_tunai;
				$diskon 			= $row->diskon;
			}
			$result = [
				'harga_off_road' => $ho,
				'harga' => $harga,
				'ppn' => $ppn,
				'bbn' => $bbn,
				'harga_on_road' => $total_bayar,
				'voucher_tambahan' => $voucher_tambahan,
				'voucher' => $voucher,
				'voucher2' => $voucher2,
				'total_bayar' => $total_bayar,
				'harga_tunai' => $harga_tunai,
				'diskon' => $diskon,
			];
		} else {
			$result = [
				'harga_off_road' => 0,
				'harga' => 0,
				'ppn' => 0,
				'bbn' => 0,
				'harga_on_road' => 0,
				'voucher_tambahan' => 0,
				'voucher' => 0,
				'voucher2' => 0,
				'total_bayar' => 0,
				'harga_tunai' => 0,
				'diskon' => 0,
			];
		}
		return $result;
	}
	function akhirBulan($year, $month) {
		return date("Y-m-d", strtotime('-1 second', strtotime('+1 month',strtotime($month . '/01/' . $year. ' 00:00:00'))));
	}
}
