<?php

class H3_md_target_salesman_model extends Honda_Model{
	
	protected $table = 'ms_h3_md_target_salesman';

	public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');

		parent::insert($data);
	}

	public function update($data, $condition){
		$data['updated_at'] = date('Y-m-d H:i:s', time());
		$data['updated_by'] = $this->session->userdata('id_user');

		parent::update($data, $condition);
	}

	public function get_target_sales_query($tanggal, $id_dealer = 0, $produk = null){
		$produk = $produk != null ? $produk : $this->input->get('produk');

		$this->db
		->select('
			case
				when ts.jenis_target_salesman = "Parts" then tsp.target_part
				when ts.jenis_target_salesman = "Apparel" then tsapp.target_apparel
				when ts.jenis_target_salesman = "Oil" then tso.amount_engine_oil + tso.amount_gear_oil
				when ts.jenis_target_salesman = "Acc" then tsa.target_acc
			end as target_customer
		', false)
		->from('ms_h3_md_target_salesman as ts')
		->join('ms_h3_md_target_salesman_parts as tsp', "tsp.id_target_salesman = ts.id", 'left')
		->join('ms_h3_md_target_salesman_oil as tso', "tso.id_target_salesman = ts.id", 'left')
		->join('ms_h3_md_target_salesman_acc as tsa', "tsa.id_target_salesman = ts.id", 'left')
		->join('ms_h3_md_target_salesman_apparel as tsapp', "tsapp.id_target_salesman = ts.id", 'left')
		->where("
			case
				when ts.jenis_target_salesman = 'Parts' then tsp.id_dealer = '{$id_dealer}'
				when ts.jenis_target_salesman = 'Apparel' then tsapp.id_dealer = '{$id_dealer}'
				when ts.jenis_target_salesman = 'Oil' then tso.id_dealer = '{$id_dealer}'
				when ts.jenis_target_salesman = 'Acc' then tsa.id_dealer = '{$id_dealer}'
			end
		", null, false)
		->where('ts.jenis_target_salesman', $produk)
		->group_start()
		->where(sprintf('"%s" between ts.start_date AND ts.end_date', $tanggal), null, false)
		->group_end()
		->limit(1)
		->order_by('ts.created_at', 'desc');
	}
}
