<?php

class ms_part_model extends Honda_Model
{
    protected $table = 'ms_part';

    public function insert($data)
    {
        parent::insert($data);

        if (!isset($data['kelompok_part_int']) and isset($data['id_part'])) {
            $this->set_kelompok_part_int($data['id_part']);
        }

        if (isset($data['id_part'])) {
            $this->list_update_harga($data['id_part'], $data);
        }
    }

    public function update($data, $condition)
    {
        parent::update($data, $condition);

        if (!isset($data['kelompok_part_int']) and isset($condition['id_part'])) {
            $this->set_kelompok_part_int($condition['id_part']);
        }

        if (isset($condition['id_part'])) {
            $this->list_update_harga($condition['id_part'], $data);
        }
    }

    public function list_update_harga($id_part, $data)
    {
        $this->load->model('h3_md_list_update_harga_model', 'list_update_harga');

        $part = (array) $this->find($id_part, 'id_part');
        if ($part != null and isset($data['harga_md_dealer']) and isset($data['harga_dealer_user'])) {
            log_message('debug', 'List kode part yang akan di update harga');
            $harga_hpp_beda = $part['harga_md_dealer'] != $data['harga_md_dealer'];
            $harga_het_beda = $part['harga_dealer_user'] != $data['harga_dealer_user'];
            if ($harga_hpp_beda or $harga_het_beda or true) {
                $part_update = [
                    'id_part_int' => $part['id_part_int'],
                    'id_part' => $part['id_part'],
                    'update_het' => $harga_het_beda ? 1 : 0,
                    'update_hpp' => $harga_hpp_beda ? 1 : 0,
                ];

                log_message('debug', sprintf('Kode part %s masuk kedalam list update harga', $part['id_part']));
                log_message('debug', print_r($part_update, true));

                $this->list_update_harga->insert($part_update);
                $this->create_status_update_harga();
            }
        }
    }

    public function create_status_update_harga()
    {
        $this->db
            ->from('tr_h3_md_status_update_harga as suh');

        if ($this->db->get()->num_rows() == 0) {
            $this->db->insert('tr_h3_md_status_update_harga', ['update_po_dealer' => 0]);
            log_message('debug', 'Membuat status update harga');
        }
    }

    public function set_kelompok_part_int($id_part)
    {
        $part = $this->db
            ->select('p.id_part_int')
            ->select('kp.id as kelompok_part_int')
            ->from('ms_part as p')
            ->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
            ->where('p.id_part', $id_part)
            ->get()->row_array();

        if ($part != null) {
            $this->db
                ->set('kelompok_part_int', $part['kelompok_part_int'])
                ->where('id_part_int', $part['id_part_int'])
                ->update('ms_part');
            log_message('debug', sprintf('Set kelompok part int untuk kode part %s', $id_part));
        }
    }

    public function part_exist($id_part)
    {
        $part = $this->find($id_part, 'id_part');

        if ($part == null) {
            $this->form_validation->set_message('part_exist_callable', 'Part tidak ditemukan.');
            return false;
        }
        return true;
    }

    public function upload($folder, $filename)
    {
        $this->load->helper('clean_data');

        $lines = $this->get_lines($folder, $filename);
        $pmp = $this->parsing_pmp($lines);

        $total_penambahan = 0;
        $total_update = 0;

        $all_valid = true;
        $validation_error = [];
        $baris = 1;
        $this->db->trans_begin();
        foreach ($pmp as $data) {
            $data = clean_data($data);
            $this->form_validation->reset_validation();
            $this->form_validation->set_data($data);
            $this->validate_data_pmp();

            if (!$this->form_validation->run()) {
                $all_valid = false;
                $validation_error[] = [
                    'message' => "Terdapat data tidak lengkap Pada Part {$data['id_part']} [Baris: {$baris}]",
                    'errors' => $this->form_validation->error_array(),
                ];
                continue;
            }

            $kelompok_part = $this->kelompok_part->find($data['kelompok_part'], 'id_kelompok_part');
            if ($kelompok_part == null) {
                $this->kelompok_part->insert([
                    'id_kelompok_part' => $data['kelompok_part'],
                    'kelompok_part' => $data['kelompok_part'],
                    'active' => 0,
                    'include_ppn' => 1,
                    'created_manually' => 0,
                    'proses_barang_bagi' => 'Tidak',
                ]);
            }

            $part = $this->part->find($data['id_part'], 'id_part');
            if ($part == null) {
                $this->part->insert($data);
                $total_penambahan += 1;
            } else {
                $condition = [
                    'id_part' => $data['id_part']
                ];
                unset($data['id_part']);
                $this->part->update($data, $condition);
                $total_update += 1;
            }

            $baris++;
        }

        if (!$all_valid) {
            $this->db->trans_rollback();
            send_json([
                'message' => 'Format data tidak sesuai',
                'error_type' => 'validation_error',
                'payload' => $validation_error
            ], 422);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->db->trans_commit();
            $message = "File PMP berhasil diupload. terdapat {$total_update} perubahan dan {$total_penambahan} penambahan data.";
            $this->session->set_userdata('pesan', $message);
            $this->session->set_userdata('tipe', 'success');

            send_json([
                'message' => $message,
                'redirect_url' => base_url('h3/part')
            ]);
        } else {
            $this->db->trans_rollback();
            $message = 'File PMP tidak berhasil di upload. Harap cek file PMP yang digunakan.';
            $this->session->set_userdata('pesan', $message);
            $this->session->set_userdata('tipe', 'danger');

            send_json([
                'message' => $message
            ], 422);
        }
    }

    private function get_lines($folder, $filename)
    {
        $file = fopen("$folder/{$filename}", "r");

        $lines = [];
        while ($line = fgets($file)) {
            $lines[] = $line;
        }
        return $lines;
    }

    private function parsing_pmp($lines)
    {
        $final_data = [];
        $line_number = 1;
        foreach ($lines as $line) {
            $line = trim($line);
            $exploded_line = explode(';', $line);

            $columns = [
                'id_part', 'nama_part', 'harga_dealer_user', 'harga_md_dealer', 'kelompok_vendor',
                'kelompok_part', 'part_reference', 'status', 'superseed', 'min_order_dealer_kecil',
                'min_order_dealer_menengah', 'min_order_dealer_besar', 'pnt', 'fast_slow', 'import_lokal',
                'rank', 'current', 'important', 'long', 'engine',
            ];

            if (count($columns) > count($exploded_line)) {
                send_json([
                    'error_type' => 'format_error',
                    'message' => "File .PMP tidak sesuai format pada baris {$line_number}.",
                ], 422);
            }

            $data = [];
            for ($i = 0; $i < count($columns); $i++) {
                $value = trim($exploded_line[$i]);
                if ($columns[$i] == 'status') {
                    if (is_numeric($value)) {
                        if ($value == 1) {
                            $value = 'A';
                        } elseif ($value == 0) {
                            $value = 'D';
                        }
                    }
                }
                $data[$columns[$i]] = $value;
            }

            $final_data[] = $data;
            $line_number++;
        }

        return $final_data;
    }

    private function validate_data_pmp()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('id_part', 'Kode Part', 'required');
        $this->form_validation->set_rules('nama_part', 'Nama Part', 'required');
        $this->form_validation->set_rules('kelompok_vendor', 'Kelompok Vendor', 'required');
        $this->form_validation->set_rules('harga_dealer_user', 'HET', 'required|numeric');
        $this->form_validation->set_rules('harga_md_dealer', 'Harga Pokok', 'required|numeric');
        $this->form_validation->set_rules('kelompok_part', 'Kelompok Part', 'required');
        $this->form_validation->set_rules('kelompok_part', 'Kelompok Part', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
    }
}
