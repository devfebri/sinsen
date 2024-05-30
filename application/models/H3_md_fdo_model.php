<?php

class h3_md_fdo_model extends Honda_Model{

    protected $table = 'tr_h3_md_fdo';

    public function __construct(){
        parent::__construct();
        $this->load->model('H3_md_ap_part_model', 'ap_part');
    }

    public function insert($data){
        $data['created_by'] = $this->session->userdata('id_user');
        $data['status'] = 'Waiting Approval';
        parent::insert($data);
    }

    public function update($data, $condition){
        $data['updated_at'] = date('Y-m-d H:i:s', time());
        $data['updated_by'] = $this->session->userdata('id_user');
        parent::update($data, $condition);
    }

    public function create_ap($invoice_number){
        $fdo = $this->db
        ->select('fdo.dpp_due_date')
        ->select('fdo.ppn_due_date')
        ->from('tr_h3_md_fdo as fdo')
        ->limit(1)
        ->where('fdo.invoice_number', $invoice_number)
        ->get()->row_array();

        if($fdo != null AND $fdo['dpp_due_date'] != $fdo['ppn_due_date']){
            $this->create_split_ap($invoice_number);
        }else if($fdo != null AND $fdo['dpp_due_date'] == $fdo['ppn_due_date']){
            $this->create_combine_ap($invoice_number);
        }
    }

    public function create_combine_ap($invoice_number){
        $invoice = $this->db
        ->select('"AHM" as id_referensi_table')
        ->select('"ms_vendor" as referensi_table')
        ->select('"2.03.21012.02" as nomor_account')
        ->select('fdo.invoice_number as referensi')
        ->select('"invoice_ahm" as jenis_transaksi')
        ->select('fdo.invoice_date as tanggal_transaksi')
        ->select('fdo.ppn_due_date as tanggal_jatuh_tempo')
        ->select('"AHM" as nama_vendor')
        ->select('(fdo.total_ppn + fdo.total_dpp) as total_bayar', false)
        ->from("{$this->table} as fdo")
        ->where('fdo.invoice_number', $invoice_number)
        ->get()->row_array();

        if($invoice != null){
            $ap_part = $this->ap_part->get([
                'referensi' => $invoice['referensi'],
                'tanggal_jatuh_tempo' => $invoice['tanggal_jatuh_tempo'],
            ], true);
            if($ap_part == null){
                $this->ap_part->insert($invoice);
            }
        }
    }

    public function create_split_ap($invoice_number){
        $invoice_ppn = $this->db
        ->select('"AHM" as id_referensi_table')
        ->select('"ms_vendor" as referensi_table')
        ->select('"2.03.21012.02" as nomor_account')
        ->select('fdo.invoice_number as referensi')
        ->select('"invoice_ahm" as jenis_transaksi')
        ->select('fdo.invoice_date as tanggal_transaksi')
        ->select('fdo.ppn_due_date as tanggal_jatuh_tempo')
        ->select('"AHM" as nama_vendor')
        ->select('fdo.total_ppn as total_bayar')
        ->from("{$this->table} as fdo")
        ->where('fdo.invoice_number', $invoice_number)
        ->get()->row_array();

        if($invoice_ppn != null){
            $ap_part = $this->ap_part->get([
                'referensi' => $invoice_ppn['referensi'],
                'tanggal_jatuh_tempo' => $invoice_ppn['tanggal_jatuh_tempo'],
            ], true);
            if($ap_part == null){
                $this->ap_part->insert($invoice_ppn);
            }
        }

        $invoice_dpp = $this->db
        ->select('"AHM" as id_referensi_table')
        ->select('"ms_vendor" as referensi_table')
        ->select('"2.03.21012.01" as nomor_account')
        ->select('fdo.invoice_number as referensi')
        ->select('"invoice_ahm" as jenis_transaksi')
        ->select('fdo.invoice_date as tanggal_transaksi')
        ->select('fdo.dpp_due_date as tanggal_jatuh_tempo')
        ->select('"AHM" as nama_vendor')
        ->select('fdo.total_dpp as total_bayar')
        ->from("{$this->table} as fdo")
        ->where('fdo.invoice_number', $invoice_number)
        ->get()->row_array();

        if($invoice_dpp != null){
            $ap_part = $this->ap_part->get([
                'referensi' => $invoice_dpp['referensi'],
                'tanggal_jatuh_tempo' => $invoice_dpp['tanggal_jatuh_tempo'],
            ], true);

            if($ap_part == null){
                $this->ap_part->insert($invoice_dpp);
            }
        }
    }

    public function set_invoice_number_ke_packing_sheet($invoice_number){
        $this->db
        ->select('fdo.id')
        ->select('fdo_ps.invoice_number')
        ->select('fdo_ps.packing_sheet_number')
        ->from('tr_h3_md_fdo_ps as fdo_ps')
        ->join('tr_h3_md_fdo as fdo', 'fdo.invoice_number = fdo_ps.invoice_number')
        ->where('fdo_ps.invoice_number', $invoice_number)
        ;

        foreach($this->db->get()->result_array() as $row){
            $this->db
            ->set('invoice_number_int', $row['id'])
            ->set('invoice_number', $row['invoice_number'])
            ->where('packing_sheet_number', $row['packing_sheet_number'])
            ->update('tr_h3_md_ps');
        }
    }

    public function upload($path){
        $lines = $this->get_lines($path);
        $fdo = $this->parse_lines($lines);

        $this->check_packing_sheet_for_fdo($fdo);

        $this->proses_data($fdo);
    }

    private function get_lines($path){
        $file = fopen($path, "r");

        $lines = [];
        while ($line = fgets($file)) {
            $lines[] = $line;
        }
        return $lines;
    }

    public function parse_lines($lines)
	{
		$registedInvoiceNumber = [];
		$finalData = [];

		$invoiceKeys = [
			'invoice_number', 'invoice_date', 'kode_dealer', 'customer_code', 'dpp_due_date', 'ppn_due_date'
		];
		$invoicePartsKeys = [
			'invoice_number', 'nomor_packing_sheet', 'id_part', 'quantity', 'price', 'disc_campaign',
			'disc_insentif', 'cash_disc', 'dpp', 'ppn','other_disc_1',
			'other_disc_2', 'invoice_sequence',
		];
		foreach ($lines as $line) {
			// Lakukan pemecahan berdasarkan panjang karakter yang telah ditentukan.
			$column = $this->parsing_line($line);

			if (!in_array($column['invoice_number'], $registedInvoiceNumber)) {
				// Hapus key pada array untuk invoice sesuai dengan yang diperlukan saja.
				$invoice = [];
				$invoice['total_dpp'] = $invoice['total_ppn'] = 0;
				foreach ($invoiceKeys as $value) {
					$invoice[$value] = $column[$value];
				}

				// Lakukan perulangan untuk mendapatkan parts yang sesuai dengan nomor invoice.
				$parts = [];
				$cast_to_double = ['price', 'disc_campaign', 'disc_insentif', 'cash_disc', 'dpp', 'ppn', 'other_disc_1', 'other_disc_2'];
				foreach ($lines as $lineForPart) {
					$columnForPart = $this->parsing_line($lineForPart);

					if ($invoice['invoice_number'] == $columnForPart['invoice_number']) {
						$part = [];
						foreach ($invoicePartsKeys as $value) {
							if($value == 'dpp'){
								$invoice['total_dpp'] += $columnForPart[$value];
							}else if($value == 'ppn'){
								$invoice['total_ppn'] += $columnForPart[$value];
							}

							if(in_array($value, $cast_to_double)){
								$part[$value] = (double) $columnForPart[$value];
							}else{
								$part[$value] = $columnForPart[$value];
							}

							$data_part = $this->db->select('p.id_part_int')->from('ms_part as p')->where('p.id_part', $columnForPart['id_part'])->get()->row_array();
							$part['id_part_int'] = $data_part != null ? $data_part['id_part_int'] : null;

							$packing_sheet_data = $this->db->select('ps.id')->from('tr_h3_md_ps as ps')->where('ps.packing_sheet_number', $columnForPart['nomor_packing_sheet'])->get()->row_array();
							$part['nomor_packing_sheet_int'] = $packing_sheet_data != null ? $packing_sheet_data['id'] : null;
						}
						$parts[] = $part;
					}
				}

				$registedInvoiceNumber[] = $column['invoice_number'];

				$finalData[] = [
					'invoice' => $invoice,
					'parts' => $parts,
				];
			}
		}

		return $finalData;
	}

    private function parsing_line($line)
	{
		$fieldLength = [
			'invoice_number' => 25,
			'invoice_date' => 8,
			'kode_dealer' => 5,
			'nomor_packing_sheet' => 15,
			'id_part' => 25,
			'part_description' => 30,
			'quantity' => 10,
			'price' => 20,
			'disc_campaign' => 20,
			'disc_insentif' => 20,
			'cash_disc' => 20,
			'dpp' => 20,
			'dpp_due_date' => 8,
			'ppn' => 20,
			'ppn_due_date' => 8,
			'other_disc_1' => 20,
			'other_disc_2' => 20,
			'customer_code' => 5,
			'invoice_sequence' => 10
		];

		$column = [];
		$startIndex = 0;
		foreach ($fieldLength as $key => $value) {
			$data = trim(substr($line, $startIndex, $value));
			if (in_array($key, ['invoice_date', 'dpp_due_date', 'ppn_due_date'])) {
				$date = DateTime::createFromFormat('dmY', $data);
				$column[$key] = $date->format('Y-m-d');
			} else {
				$column[$key] = $data;
			}
			$startIndex += $value;
		}
		return $column;
	}

    private function check_packing_sheet_for_fdo($data){
		$fdo_packing_sheet_tidak_ada = [];
		foreach ($data as $each) {
			$invoice = $each['invoice'];
			$parts = $each['parts'];

			$packing_sheet_not_found = [];
			$part_not_found = [];
			$nomor_packing_sheets = array_map(function($data){
				return $data['nomor_packing_sheet'];
			}, $parts);
			$nomor_packing_sheets = array_unique($nomor_packing_sheets);

			$packing_sheet_numbers = $this->db
			->select('ps.packing_sheet_number')
			->from('tr_h3_md_ps as ps')
			->get()->result_array();

			$packing_sheet_numbers = array_map(function($data){
				return $data['packing_sheet_number'];
			}, $packing_sheet_numbers);

			foreach ($nomor_packing_sheets as $nomor_packing_sheet) {
				if(!in_array($nomor_packing_sheet, $packing_sheet_numbers)){
					$packing_sheet_not_found[] = $nomor_packing_sheet;
				}
			}

			foreach ($parts as $part) {
				$check_part = $this->db
				->from('ms_part as p')
				->where('p.id_part', $part['id_part'])
				->get()->row_array();

				if($check_part == null){
					$part_not_found[] = $part['id_part'];
				}

			}

			$fdo_packing_sheet_tidak_ada[] = [
				'invoice' => $invoice,
				'packing_sheet_not_found' => $packing_sheet_not_found,
				'part_not_found' => array_unique($part_not_found),
			];
		}

		if (count($packing_sheet_not_found) > 0 OR count($part_not_found) > 0) {
			send_json([
				'error_type' => 'packing_sheet_not_complete',
				'payload' => $fdo_packing_sheet_tidak_ada,
				'message' => 'Terdapat FDO dengan nomor packing sheet tidak terdaftar di sistem. Mohon lakukan pengecekan kembali.'
			], 422);
		}
	}

    private function proses_data($fdo){
        $this->db->trans_start();
		$invoice_updated = [];
		$invoice_inserted = [];
		$invoice_approved = [];
		foreach ($fdo as $each) {
			$condition = [
				'invoice_number' => $each['invoice']['invoice_number']
			];

			$packing_sheet_numbers = array_unique(
				array_map(function($data){
					return $data['nomor_packing_sheet'];
				}, $each['parts'])
			);

			$fdo_ps = array_map(function($data) use ($each) {
				return [
					'packing_sheet_number' => $data,
					'invoice_number' => $each['invoice']['invoice_number']
				];
			}, $packing_sheet_numbers);

			$fdo = (array) $this->fdo->get($condition, true);
			$data = $each['invoice'];
			if($fdo != null){
				$check_invoice_approved = $this->db
				->select('fdo.id')
				->from('tr_h3_md_fdo as fdo')
				->where('fdo.invoice_number', $data['invoice_number'])
				->where('fdo.status', 'Approved')
				->get()->row_array();

				if($check_invoice_approved != null){
					$invoice_approved[] = $data['invoice_number'];
					$this->set_invoice_number_ke_packing_sheet($data['invoice_number']);
					continue;
				}else{
					$invoice_updated[] = $data['invoice_number'];
				}

				unset($data['invoice_number']);

				$this->fdo->update($data, $condition);
				$parts = array_map(function($part) use ($fdo) {
					$part['invoice_number_int'] = $fdo['id'];
					return $part;
				}, $each['parts']);
				$this->fdo_parts->insert_or_update_batch($parts, $condition);
				$this->fdo_ps->insert_or_update_batch($fdo_ps, $condition);

				$this->set_invoice_number_ke_packing_sheet($condition['invoice_number']);
			}else{
				$invoice_inserted[] = $data['invoice_number'];
				$this->fdo->insert($each['invoice'], $condition);
				$invoice_number_int = $this->db->insert_id();
				$parts = array_map(function($part) use ($invoice_number_int) {
					$part['invoice_number_int'] = $invoice_number_int;
					return $part;
				}, $each['parts']);
				$this->fdo_parts->insert_or_update_batch($parts, $condition);
				$this->fdo_ps->insert_or_update_batch($fdo_ps, $condition);
				$this->set_invoice_number_ke_packing_sheet($condition['invoice_number']);
			}
		}
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$count_invoice_inserted = count($invoice_inserted);
			$count_invoice_updated = count($invoice_updated);
			$count_invoice_approved = count($invoice_approved);

			$success_message = "Data FDO berhasil di upload";

			if($count_invoice_inserted > 0){
				$join_invoice_inserted = '';
				foreach ($invoice_inserted as $each) {
					$join_invoice_inserted .= ", " . $each;
				}
				$join_invoice_inserted = substr($join_invoice_inserted, 2);
				$success_message .= ". Invoice Baru: {$join_invoice_inserted}";
			}

			if($count_invoice_updated > 0){
				$join_invoice_updated = '';
				foreach ($invoice_updated as $each) {
					$join_invoice_updated .= ", " . $each;
				}
				$join_invoice_updated = substr($join_invoice_updated, 2);
				$success_message .= ". Invoice diperbarui: {$join_invoice_updated}";
			}

			$success_message .= ".";

			if($count_invoice_approved > 0){
				$join_invoice_approved = '';
				foreach ($invoice_approved as $each) {
					$join_invoice_approved .= " ," . $each;
				}
				$join_invoice_approved = substr($join_invoice_approved, 2);
				$success_message .= " Terdapat Invoice yang sudah di approved, antara lain: {$join_invoice_approved}.";
			}
			$this->session->set_userdata('pesan', $success_message);
			$this->session->set_userdata('tipe', 'info');

			return true;
		} else {
			$this->session->set_userdata('pesan', 'Data FDO tidak berhasil di upload.');
			$this->session->set_userdata('tipe', 'danger');

			return false;
		}
    }
}
