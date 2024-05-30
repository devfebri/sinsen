<?php

class h3_md_diskon_oli_reguler_model extends Honda_Model{

    protected $table = 'ms_h3_md_diskon_oli_reguler';

    public function insert($data){
        $data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
    }

    public function get_diskon($part, $dealer, $jumlah_dus){
        $diskon_oli_reguler = $this->db
        ->select('dor.id')
        ->from('ms_h3_md_diskon_oli_reguler as dor')
        ->join('ms_part as p', 'p.id_part = dor.id_part')
        ->where('dor.id_part', $part)
        ->where('dor.active', 1)
        ->get()->row_array();

        $general_range = $this->db
        ->select('dorgr.tipe_diskon')
        ->select('dorgr.diskon_value')
        ->select('rdo.kode_range')
        ->select('rdo.range_start')
        ->select('rdo.range_end')
        ->select("$jumlah_dus as jumlah_dus")
        ->from('ms_h3_md_diskon_oli_reguler_general_ranges as dorgr')
        ->join('ms_h3_md_range_dus_oli as rdo', 'rdo.id = dorgr.id_range_dus_oli')
        ->where('dorgr.id_diskon_oli_reguler', $diskon_oli_reguler['id'])
        // ->where('rdo.range_start <=', $jumlah_dus)
        // ->where('rdo.range_end >=', $jumlah_dus)
        ->limit(1)
        ->where('rdo.range_start <=', $jumlah_dus)
        ->order_by('rdo.range_end', 'desc')
        ->get()->row_array();

        $diskon_oli_reguler_dealer = $this->db
        ->select('dori.id')
        ->select('d.kode_dealer_md')
        ->select('d.nama_dealer')
        ->from('ms_h3_md_diskon_oli_reguler_item as dori')
        ->join('ms_dealer as d', 'd.id_dealer = dori.id_dealer')
        ->where('dori.id_dealer', $dealer)
        ->where('dori.id_diskon_oli_reguler', $diskon_oli_reguler['id'])
        ->get()->row_array()
        ;

        $diskon_oli_reguler_range_dealer = $this->db
        ->select('dorr.id')
        ->select('dorr.tipe_diskon')
        ->select('dorr.diskon_value')
        ->select('rdo.kode_range')
        ->select('rdo.range_start')
        ->select('rdo.range_end')
        ->from('ms_h3_md_diskon_oli_reguler_ranges as dorr')
        ->join('ms_h3_md_range_dus_oli as rdo', 'rdo.id = dorr.id_range_dus_oli')
        ->where('dorr.id_diskon_oli_reguler_item', $diskon_oli_reguler_dealer['id'])
        // ->where('rdo.range_start <=', $jumlah_dus)
        // ->where('rdo.range_end >=', $jumlah_dus)
        ->limit(1)
        ->where('rdo.range_start <=', $jumlah_dus)
        ->order_by('rdo.range_start', 'desc')
        ->get()->row_array();

        $diskon = [
            'id_part' => $part,
            'tipe_diskon' => '',
            'diskon_value' => 0
        ];

        if($diskon_oli_reguler != null){
            if($diskon_oli_reguler_dealer != null){
                if($diskon_oli_reguler_range_dealer != null){
                    $diskon['tipe_diskon'] = $diskon_oli_reguler_range_dealer['tipe_diskon'];
                    $diskon['diskon_value'] = $diskon_oli_reguler_range_dealer['diskon_value'];
                }else{
                    if($general_range != null){
                        $diskon['tipe_diskon'] = $general_range['tipe_diskon'];
                        $diskon['diskon_value'] = $general_range['diskon_value'];
                    }
                }
            }else{
                if($general_range != null){
                    $diskon['tipe_diskon'] = $general_range['tipe_diskon'];
                    $diskon['diskon_value'] = $general_range['diskon_value'];
                }
            }
        }

        log_message('debug', sprintf('Pencarian diskon oli reguler kode part %s untuk dealer [%s] dengan jumlah dus %s [payload] %s', $part, $dealer, $jumlah_dus, print_r($diskon, true)));

        return $diskon;
    }

    public function get_jumlah_dus($parts, $key = 'qty_supply'){
		$total_dus = 0;
		foreach ($parts as $part) {
			$total_dus += $part[$key] / $part['qty_dus'];
		}

		return floor($total_dus);
	}
    
    public function generate_id($kelompok_part){
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		
        $query = $this->db
        ->select('id_diskon_oli_reguler')
        ->from($this->table)
        ->where('kelompok_part', $kelompok_part)
        ->where("LEFT(created_at, 7)='{$th_bln}'")
        ->order_by('id', 'DESC')
        ->order_by('created_at', 'DESC')
        ->limit(1)
        ->get();

		if ($query->num_rows()>0) {
			$row = $query->row();
			$id_diskon_oli_reguler = substr($row->id_diskon_oli_reguler, 0, 3);
			$id_diskon_oli_reguler = sprintf("%'.03d",$id_diskon_oli_reguler+1);
			$id = "{$id_diskon_oli_reguler}/DISC-REGULER/{$kelompok_part}/{$bln}/{$th}";
		}else{
			$id = "001/DISC-REGULER/{$kelompok_part}/{$bln}/{$th}";
		}

   		return strtoupper($id);
    }
}
