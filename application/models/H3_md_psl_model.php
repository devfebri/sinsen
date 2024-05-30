<?php

class h3_md_psl_model extends Honda_Model{
    protected $table = 'tr_h3_md_psl';

    public function upload($filename){
        $surat_jalan_ahm = $this->get_surat_jalan_ahm($filename);
        $lines = $this->read_uploaded_file($filename);
        $packing_sheets = $this->parsing_lines($lines, $surat_jalan_ahm);
        $this->check_surat_jalan(array_column($packing_sheets, 'packing_sheet_number'));

        return $packing_sheets;
    }

    private function get_surat_jalan_ahm($filename){
        $exploded = explode('.', $filename);
		
		return substr($exploded[0], 3, 10);
    }

    private function read_uploaded_file($filename){
        $myfile = fopen("./uploads/AHM/{$filename}", "r");

        $lines = [];
        while ($line = fgets($myfile)) {
            $lines[] = $line;
        }
        return $lines;
    }

    private function parsing_lines($lines, $surat_jalan_ahm)
	{
		$registeredPackingSheet = [];
		$packing_sheets = [];
		foreach ($lines as $line) {
			$column = $this->parsing_line($line);
			if(!in_array($column['packing_sheet_number'], $registeredPackingSheet)){
				$packing_sheet = [];
				$packing_sheet['surat_jalan_ahm'] = $surat_jalan_ahm;
				$packing_sheet['packing_sheet_number'] = $column['packing_sheet_number'];

				$packing_sheets[] = $packing_sheet;
				$registeredPackingSheet[] = $column['packing_sheet_number'];
			}
		}
		return $packing_sheets;
	}

    private function parsing_line($line)
	{
		$fieldLength = [
			'kode_produk' => 1,
			'kode_md' => 5,
			'packing_sheet_date' => 8,
			'packing_sheet_number' => 15,
			'no_po' => 30,
			'jenis_po' => 3,
			'tanggal_po' => 8,
			'no_urut' => 5,
			'no_doos' => 15,
			'id_part' => 25,
			'part_deskripsi' => 38,
			'packing_sheet_quantity' => 8,
			'qty_order' => 10,
			'qty_back_order' => 6,
			'customer_code' => 3,
		];

		$column = [];
		$startIndex = 0;
		foreach ($fieldLength as $key => $value) {
			$data = trim(substr($line, $startIndex, $value));
			if (in_array($key, ['packing_sheet_date', 'tanggal_po'])) {
				$date = DateTime::createFromFormat('dmY', $data);
				$column[$key] = $date->format('Y-m-d');
			} else {
				$column[$key] = $data;
			}
			$startIndex += $value;
		}
		return $column;
	}

    private function check_surat_jalan($packing_sheets){
		$packing_sheet_not_found = [];
		foreach ($packing_sheets as $packing_sheet) {
			$this->db
			->from('tr_h3_md_ps as ps')
			->where('ps.packing_sheet_number', $packing_sheet);

			if($this->db->get()->row_array() == null){
				$packing_sheet_not_found[] = $packing_sheet;
			}
		}

		if (count($packing_sheet_not_found) > 0) {
			send_json([
				'error_type' => 'packing_sheet_not_complete',
				'payload' => $packing_sheet_not_found,
				'message' => 'Terdapat PSL dengan nomor packing sheet tidak terdaftar di sistem. Mohon lakukan pengecekan kembali.'
			], 422);
		}
	}
}
