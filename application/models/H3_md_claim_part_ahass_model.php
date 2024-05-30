<?php

class H3_md_claim_part_ahass_model extends Honda_Model
{

	protected $table = 'tr_h3_md_claim_part_ahass';

	public function __construct()
	{
		parent::__construct();
	}

	public function insert($data)
	{
		$data['status'] = 'Open';
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');

		parent::insert($data);
	}

	public function generateID()
	{
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');

		$bulan_romawi = $this->numberToRomanRepresentation($bln);

		$query = $this->db
			->select('id_claim_part_ahass')
			->from($this->table)
			->where("LEFT(created_at, 4)='{$th}'")
			->where('created_at > ', '2021-05-06 16:34:00')
			->order_by('created_at', 'DESC')
			->limit(1)
			->get();

		if ($query->num_rows() > 0) {
			$row        = $query->row();
			$id_claim_part_ahass = substr($row->id_claim_part_ahass, 0, 3);
			$id_claim_part_ahass = sprintf("%'.03d", $id_claim_part_ahass + 1);
			$id   = "{$id_claim_part_ahass}/E20/C3/{$bulan_romawi}/{$th}";
		} else {
			$id   = "001/E20/C3/{$bulan_romawi}/{$th}";
		}

		return strtoupper($id);
	}

	private function numberToRomanRepresentation($number)
	{
		$map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
		$returnValue = '';
		while ($number > 0) {
			foreach ($map as $roman => $int) {
				if ($number >= $int) {
					$number -= $int;
					$returnValue .= $roman;
					break;
				}
			}
		}
		return $returnValue;
	}

	public function set_id_int($id)
	{
		$claim_part_ahass = $this->db
			->select('ps.id as packing_sheet_number_int')
			->from(sprintf('%s as cpa', $this->table))
			->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = cpa.packing_sheet_number')
			->where('cpa.id', $id)
			->limit(1)
			->get()->row_array();

		if ($claim_part_ahass == null) return;

		log_message('debug', sprintf('Set id int untuk claim part ahass [%s] [payload] %s', $id, print_r($claim_part_ahass, true)));

		$this->db
			->set('packing_sheet_number_int', $claim_part_ahass['packing_sheet_number_int'])
			->where('id', $id)
			->update($this->table);
	}
}
