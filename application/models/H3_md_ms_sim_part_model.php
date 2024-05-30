<?php

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class H3_md_ms_sim_part_model extends Honda_Model{

    protected $table = 'ms_h3_md_sim_part';

    public function __constrcut(){
        parent::__construct();

        $this->load->library('Mcarbon');
    }

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

    public function qty_sim_part($id_dealer, $id_part, $sql = false){
        $dealer = $this->db
        ->from('ms_dealer')
        ->where('id_dealer', $id_dealer)
        ->limit(1)
        ->get()->row_array();
        if($dealer == null) return 0;
        
        $jumlah_pit = $this->db
        ->from('ms_h3_md_jumlah_pit')
        ->where('id_dealer', $id_dealer)
        ->limit(1)
        ->get()->row_array();

        if($jumlah_pit == null) return 0;

        $this->db
        ->select('spi.qty_sim_part')
        ->from('ms_h3_md_sim_part as sp')
        ->join('ms_h3_md_sim_part_item as spi', 'spi.id_sim_part_int = sp.id')
        ->where("sp.batas_bawah_jumlah_pit <=", $jumlah_pit['jumlah_pit'])
        ->where("sp.batas_atas_jumlah_pit >=", $jumlah_pit['jumlah_pit'])
        ->where("spi.id_part", $id_part, !$sql)
        ->order_by('spi.qty_sim_part', 'desc')
        ->limit(1);

        if($sql){
            return $this->db->get_compiled_select();
        }else{
            $data = $this->db->get()->row_array();
            return $data != null ? $data['qty_sim_part'] : 0;
        }
    }

    public function qty_sim_part2($id_dealer, $id_part_int, $sql = false){
        // $dealer = $this->db
        // ->from('ms_dealer')
        // ->where('id_dealer', $id_dealer)
        // ->limit(1)
        // ->get()->row_array();
        // if($dealer == null) return 0;
        
        $jumlah_pit = $this->db
        ->from('ms_h3_md_jumlah_pit')
        ->where('id_dealer', $id_dealer)
        ->limit(1)
        ->get()->row_array();

        if($jumlah_pit == null) return 0;

        $this->db
        ->select('spi.qty_sim_part')
        ->from('ms_h3_md_sim_part as sp')
        ->join('ms_h3_md_sim_part_item as spi', 'spi.id_sim_part_int = sp.id')
        ->where("sp.batas_bawah_jumlah_pit <=", $jumlah_pit['jumlah_pit'])
        ->where("sp.batas_atas_jumlah_pit >=", $jumlah_pit['jumlah_pit'])
        ->where("spi.id_part_int", $id_part_int, !$sql)
        ->order_by('spi.qty_sim_part', 'desc')
        ->limit(1);

        if($sql){
            return $this->db->get_compiled_select();
        }else{
            $data = $this->db->get()->row_array();
            return $data != null ? $data['qty_sim_part'] : 0;
        }
    }

    public function generate_id($batas_bawah_jumlah_pit, $batas_atas_jumlah_pit){
		$bulan_short = date('m');
		$tahun_short = date('y');
		$tahun = date('Y');
        $tahun_dan_bulan = date('Y-m');
        
        $data = $this->db
        ->from($this->table)
        ->where("LEFT(created_at,7) = '{$tahun_dan_bulan}'")
        ->order_by('created_at', 'desc')
        ->order_by('id', 'desc')
        ->limit(1)
        ->get();

        $romawi_batas_bawah_jumlah_pit = $this->numberToRomanRepresentation($batas_bawah_jumlah_pit);
        $romawi_batas_atas_jumlah_pit = $this->numberToRomanRepresentation($batas_atas_jumlah_pit);

        if ($data->num_rows()>0) {
            $row = $data->row();
            $id_sim_part = substr($row->id_sim_part, 0, 3);
			$new_kode = sprintf("%'.03d", $id_sim_part + 1);
            $new_kode = "$new_kode/SIM-PARTS/$romawi_batas_bawah_jumlah_pit-$romawi_batas_atas_jumlah_pit/$bulan_short/$tahun";
        } else {
            $new_kode = "001/SIM-PARTS/$romawi_batas_bawah_jumlah_pit-$romawi_batas_atas_jumlah_pit/$bulan_short/$tahun";
        }
        return strtoupper($new_kode);
    }

    public function generate_id_new(){
		$bulan_short = date('m');
		$tahun_short = date('y');
		$tahun = date('Y');
        $tahun_dan_bulan = date('Y-m');
        
        $data = $this->db
        ->from($this->table)
        ->where("LEFT(created_at,7) = '{$tahun_dan_bulan}'")
        ->order_by('created_at', 'desc')
        ->order_by('id', 'desc')
        ->limit(1)
        ->get();

        if ($data->num_rows()>0) {
            $row = $data->row();
            $id_sim_part = substr($row->id_sim_part, 0, 3);
			$new_kode = sprintf("%'.03d", $id_sim_part + 1);
            $new_kode = "$new_kode/SIM-PARTS/$bulan_short/$tahun";
        } else {
            $new_kode = "001/SIM-PARTS/$bulan_short/$tahun";
        }
        return strtoupper($new_kode);
    }
    
    private function numberToRomanRepresentation($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    public function upload_excel($filename){
        $data = $this->baca_excel($filename);
        $this->simpan_excel($data);
    }
    
    private function baca_excel($filename){
        $filepath = "./uploads/master_part_simpart/{$filename}";

        $spreadsheet = IOFactory::load($filepath)->getSheet(0);
        $highestRow = $spreadsheet->getHighestRow(); 

        $data = [];
        $range_sim_part = 3;
        for($range = 0; $range < $range_sim_part; $range++){
            $column_range = Coordinate::stringFromColumnIndex(4 + $range);
            $range_data = $spreadsheet->getCell(sprintf('%s2', $column_range))->getValue();
            $batas_bawah_jumlah_pit = explode('-', $range_data)[0];
            $batas_atas_jumlah_pit = explode('-', $range_data)[1];

            $parts = [];
            for ($row = 3; $row <= $highestRow ; $row++) { 
                $id_part = $spreadsheet->getCell(sprintf('B%s', $row))->getValue();
                $kuantitas_sim_part = $spreadsheet->getCell(sprintf('%s%s', $column_range, $row))->getValue();

                $part = $this->db
                ->select('p.id_part_int')
                ->from('ms_part as p')
                ->where('p.id_part', $id_part)
                ->get()->row_array();

                if($kuantitas_sim_part != null){
                    $parts[] = [
                        'id_part_int' => $part['id_part_int'],
                        'id_part' => $id_part,
                        'kuantitas_sim_part' => $kuantitas_sim_part,
                    ];
                }
            }

            $sim_part = [
                'batas_bawah_jumlah_pit' => $batas_bawah_jumlah_pit,
                'batas_atas_jumlah_pit' => $batas_atas_jumlah_pit,
                'parts' => $parts
            ];

            $data[] = $sim_part;
        }

        return $data;
    }

    public function simpan_excel($data){
        $this->load->model('H3_md_ms_sim_part_item_model', 'sim_part_item');
        
		$this->db->set('sim_part', 0)->update('ms_part');
        foreach($data as $row){
            $sim_part = (array) $this->get([
                'batas_bawah_jumlah_pit' => $row['batas_bawah_jumlah_pit'],
                'batas_atas_jumlah_pit' => $row['batas_atas_jumlah_pit'],
            ], true);

            $id_sim_part = null;
            if($sim_part != null){
                $id_sim_part = $sim_part['id_sim_part'];
                $this->db->where('id_sim_part', $id_sim_part)->delete('ms_h3_md_sim_part_item');
            }else{
                $id_sim_part = $this->sim_part->generate_id(
                    $row['batas_bawah_jumlah_pit'], 
                    $row['batas_atas_jumlah_pit']
                );
                
                $this->insert([
                    'id_sim_part' => $id_sim_part,
                    'tanggal_mulai_berlaku' => Mcarbon::now()->toDateString(),
                    'batas_bawah_jumlah_pit' => $row['batas_bawah_jumlah_pit'],
                    'batas_atas_jumlah_pit' => $row['batas_atas_jumlah_pit'],
                    'active' => 1
                ]);
            }

            foreach($row['parts'] as $part){
		        $this->db->set('sim_part', 1)->where('id_part_int', $part['id_part_int'])->update('ms_part');
                $this->sim_part_item->insert([
                    'id_sim_part' => $id_sim_part,
                    'id_part' => $part['id_part'],
                    'qty_sim_part' => $part['kuantitas_sim_part']
                ]);
            }
        }
    }
}
