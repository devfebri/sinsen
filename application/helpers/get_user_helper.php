<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_user'))
{
	function get_user($menu, $jenis_user = 'Dealer')
	{
		$ci = &get_instance();
		$id_user = $ci->session->userdata('id_user');

		$ci->db
		->select('u.id_user')
		->select('karyawan.nama_lengkap')
		->select('ug.code as kode_group')
		->select('ug.user_group as nama_group')
		->select('ual.*')
		->from('ms_user as u')
		->join('ms_user_group as ug', 'ug.id_user_group = u.id_user_group')
		
		->join('ms_menu as m', "(m.menu_link = '{$menu}')")
		->join('ms_user_access_level as ual', '(ual.id_user_group = ug.id_user_group and m.id_menu = ual.id_menu)')
		->where('u.id_user', $id_user)
		->where('u.jenis_user', $jenis_user)
		->limit(1);

		if($jenis_user == 'Dealer'){
			$ci->db->join('ms_karyawan_dealer as karyawan', 'karyawan.id_karyawan_dealer = u.id_karyawan_dealer');
		}else{
			$ci->db->join('ms_karyawan as karyawan', 'karyawan.id_karyawan = u.id_karyawan_dealer');
		}

		$access = $ci->db->get()->row_array();

		$data = [];
		$keys = [
			'can_insert', 'can_update',
			'can_delete', 'can_download',
			'can_approval', 'can_reject',
			'can_submit', 'can_cancel',
			'can_reopen', 'can_print',
			'can_transit', 'can_close'
		];
		foreach ($access as $key => $value) {
			$data[$key] = $value;
			if(in_array($key, $keys)){
				$data[$key] = $value == 1 ? true : false;
			}
		}

		return $data;
	}
}