<?php

use PhpOffice\PhpSpreadsheet\Shared\Date;

class H3_md_etd_upload_model extends Honda_Model
{
    protected $table = 'ms_h3_md_estimated_time_delivery';

    protected $folder_upload = './uploads/upload_etd_revisi/';

    public function upload_excel($file_key)
    {
        ini_set('memory_limit', '-1');

        if (!file_exists($this->folder_upload)) {
            mkdir($this->folder_upload, 0777, true);
        }

        $config['upload_path'] = $this->folder_upload;
        $config['allowed_types'] = 'xls|xlsx';
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($file_key)) {
            send_json([
                'error_type' => 'validation_error',
                'message' => 'Data tidak valid',
                'errors' => [
                    'file' => $this->upload->display_errors('', '')
                ]
            ], 422);
        }

        $data = $this->upload->data();
        $filename = $data['file_name'];

        $data = $this->read_excel($filename);
        $this->update_etd($data);
    }

    private function read_excel($filename)
    {
        $filepath = sprintf('%s%s', $this->folder_upload, $filename);

        try {
            $excel = \PhpOffice\PhpSpreadsheet\IOFactory::load($filepath);
        } catch (\Exception $exception) {
            send_json([
                'message' => $exception->getMessage()
            ], 422);
        }

        //  Get worksheet dimensions
        $sheet = $excel->getSheet(0);
        $highestRow = $sheet->getHighestRow();

        $data = [];
        for ($startRow = 1; $startRow <= $highestRow; $startRow++) {

            if ($startRow == 1) {
                $kolom_header_table = $sheet->getCell(sprintf('B%s', $startRow))->getValue();

                if (strtolower($kolom_header_table) != 'part number') {
                    send_json([
                        'message' => 'Format excel salah, pastikan baris pertama pada excel merupakan header tabel'
                    ], 422);
                }
            }

            $row = [];
            $row['id_part'] = $sheet->getCell(sprintf('B%s', $startRow))->getValue();
            if ($row['id_part'] == null) break;

            $row['nama_part'] = $sheet->getCell(sprintf('C%s', $startRow))->getValue();

            $row['cut_off'] = $this->get_excel_to_php_time(sprintf('D%s', $startRow), $sheet);
            $row['etd_revisi'] = $this->get_excel_to_php_time(sprintf('E%s', $startRow), $sheet);
            $row['keterangan'] = $sheet->getCell(sprintf('F%s', $startRow))->getValue();
            $data[] = $row;
        }
        return $data;
    }

    private function get_excel_to_php_time($column, $sheet)
    {
        $cell = $sheet->getCell($column);
        $cell_value = $cell->getValue();

        if (Date::isDateTime($cell) && $cell_value != null) {
            $unix_timestamp = Date::excelToTimestamp($cell_value);
            return Mcarbon::createFromTimestamp($unix_timestamp)->toDateString();
        } else {
            return null;
        }
    }

    private function update_etd($data)
    {
        $this->load->model('h3_md_purchase_order_parts_model', 'purchase_order_parts');
        $this->load->model('H3_md_history_estimasi_waktu_hotline_model', 'history_estimasi_waktu_hotline');

        foreach ($data as $row) {
            $purchase_order_parts = $this->db
                ->select('pop.id_purchase_order')
                ->select('po.referensi_po_hotline')
                ->select('DATEDIFF(pop.eta, pop.etd) as diff')
                ->from('tr_h3_md_purchase_order_parts as pop')
                ->join('tr_h3_md_purchase_order as po', 'po.id_purchase_order = pop.id_purchase_order')
                ->where('pop.id_part', $row['id_part'])
                ->where('po.status !=', 'Canceled')
                ->where('po.status !=', 'Closed')
                ->where('po.jenis_po', 'HTL')
                ->get()->result_array();

            foreach ($purchase_order_parts as $purchase_order_part) {
                $etd = Mcarbon::parse($row['etd_revisi'])->toDateString();
                $eta = Mcarbon::parse($row['etd_revisi'])->addDays($purchase_order_part['diff'])->toDateString();
                $update_data = [
                    'etd' => $etd,
                    'eta' => $eta
                ];
                $condition = [
                    'id_part' => $row['id_part'],
                    'id_purchase_order' => $purchase_order_part['id_purchase_order']
                ];
                $this->purchase_order_parts->update($update_data, $condition);

                $history = array_merge($update_data, [
                    'id_part' => $row['id_part'],
                    'source' => 'upload_revisi',
                    'po_id' => $purchase_order_part['referensi_po_hotline'],
                    'id_purchase_order' => $purchase_order_part['id_purchase_order']
                ]);

                $check_history = $this->history_estimasi_waktu_hotline->get([
                    'etd' => $etd,
                    'eta' => $eta,
                    'id_part' => $row['id_part'],
                    'id_purchase_order' => $purchase_order_part['id_purchase_order'],
                    'source' => 'upload_revisi',
                    'po_id' => $purchase_order_part['referensi_po_hotline'],
                ], true);

                if ($check_history == null) {
                    $this->history_estimasi_waktu_hotline->insert($history);
                }
            }
        }
    }

    public function get_folder_upload()
    {
        return $this->folder_upload;
    }
}
