<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('can_access'))
{
	function can_access($menu, $level)
	{
		$ci = &get_instance();
		$user = $ci->db
		->select('u.id_user')
		->select('ug.code as kode_group')
		->select('ug.user_group as nama_group')
		->select('ug.id_user_group')
		->from('ms_user as u')
		->join('ms_user_group as ug', 'ug.id_user_group = u.id_user_group', 'left')
		->where('u.id_user',$ci->session->userdata('id_user'))
		->limit(1)
		->get()->row();

		$menu = $ci->db
		->from('ms_menu as m')
		->where('m.menu_link', $menu)
		->limit(1)
		->get()->row();

		$access_level = $ci->db
		->from('ms_user_access_level ual')
		->where('ual.id_user_group', $user->id_user_group)
		->where('ual.id_menu', $menu->id_menu)
		->limit(1)
		->get()->row();

		return $access_level->$level == 1;

	}
}