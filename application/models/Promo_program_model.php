<?php

class promo_program_model extends Honda_Model {

    protected $table = 'ms_h3_promo_program';

    public function __construct(){
        $this->load->model('m_admin');
        $this->load->model('dealer_model', 'dealer');
	}
	
	public function insert($data){
		$data['created_by'] = $this->session->userdata('id_user');

		parent::insert($data);
	}

    public function generateID(){
        $jenis_po = $this->input->post('jenis_po');
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');
		
		if($this->m_admin->cari_dealer() == ''){
            $kode_dealer = 'E20';
        }else{
            $dealer = $this->dealer->getCurrentUserDealer();
            $kode_dealer = $dealer->$kode_dealer_md;
        }

		$query = $this->db->select('*')
						->from($this->table)
						->where("LEFT(created_at, 7)='{$th_bln}'")
						->order_by('id', 'DESC')
						->limit(1)
						->get();

		if ($query->num_rows()>0) {
			$row        = $query->row();
			$id_program = substr($row->id_program, 15, 5);
			$id_program = sprintf("%'.05d",$id_program+1);
			$id   = "PR/{$kode_dealer}/{$th}/{$bln}/{$id_program}";
		}else{
			$id   = "PR/{$kode_dealer}/{$th}/{$bln}/00001";
		}

   		return strtoupper($id);
    }
}

?>