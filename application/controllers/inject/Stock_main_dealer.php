<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_main_dealer extends CI_Controller {

    protected $folder = 'inject';
    protected $page = 'stock_main_dealer';

    public function __construct(){
        parent::__construct();
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $config = [
            'allowed_types' => 'xls|xlsx',
            'upload_path' => './uploads/inject',
            'encrypt_name' => true
        ];
        $this->load->library('upload', $config);

        $this->load->model('part_model', 'part');
        $this->load->model('H3_md_lokasi_rak_model', 'lokasi_rak');
        $this->load->model('h3_md_kartu_stock_model', 'kartu_stock');
        $this->load->model('H3_md_stock_model', 'stock');
    }

    public function index(){
        $this->load->view('inject/stock_main_dealer');
    }

    public function upload_excel(){
        if (!$this->upload->do_upload('file')){
            $this->output->set_status_header(422);
            echo 'Upload gagal! <br>';
            echo $this->upload->display_errors();
        }

        $data = $this->read_excel($this->upload->data()['file_name']);
        $this->proses_data($data);
    }

    public function read_excel($filename){
        //  Include PHPExcel_IOFactory
        include APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';

        $filepath = "./uploads/inject/{$filename}";

        //  Read your Excel workbook
        try {
            $inputFileType = PHPExcel_IOFactory::identify($filepath);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($filepath);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($filepath,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
        $keyName = ['id_part', 'nama_part', 'het', 'harga_beli', 'kel_barang', 'status', 'stock', 'rak'];

        $result = [];
        for ($row = 1; $row <= $highestRow; $row++){ 
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];

            if($row == 1 or $rowData[0] == '') continue;

            $data = [];
            for ($i=0; $i < count($keyName); $i++) { 
                $data[$keyName[$i]] = trim($rowData[$i]);
            }
            $result[] = $data;
        }

        return $result;
    }

    public function proses_data($data){
        $all_valid = true;
        $validation_error = [];
        $baris = 2;

        $this->db->trans_begin();
        foreach ($data as $row) {
            if($row['stock'] < 0){
                $row['stock'] = 0;
            }

            $this->form_validation->set_data($row);
            $this->validate();
                
            if (!$this->form_validation->run()){
                $all_valid = false;
                $validation_error[] = [
                    'message' => "Terdapat data tidak lengkap pada kode part {$row['id_part']} [Baris: {$baris}]",
                    'errors' => $this->form_validation->error_array(),
                ];
            }else{
                $part = (array) $this->part->find($row['id_part'], 'id_part');
                
                // if($part == null){
                //     continue;
                // }

                $lokasi_rak = $this->db
                ->from('ms_h3_md_lokasi_rak as lr')
                ->where("lr.kode_lokasi_rak = '{$row['rak']}'", null, false)
                ->get()->row();

                if($lokasi_rak == null){
                    $this->lokasi_rak->insert([
                        'kode_lokasi_rak' => $row['rak'],
                        'deskripsi' => '...',
                        'kapasitas' => 1,
                        'id_gudang' => 1,
                        'lokasi_retur' => 0,
                        'active' => 1
                    ]);
                    $id_lokasi_rak = $this->db->insert_id();
                }else{
                    $id_lokasi_rak = $lokasi_rak->id;
                }

                $transaksi_stock = [
                    'id_part_int' => $part['id_part_int'],
                    'id_part' => $row['id_part'],
                    'id_lokasi_rak' => $id_lokasi_rak,
                    'tipe_transaksi' => '+',
                    'sumber_transaksi' => 'inject',
                    'referensi' => 'inject',
                    'stock_value' => $row['stock'],
                ];
        
                $this->kartu_stock->insert($transaksi_stock);

                $stock_didalam_gudang = $this->stock->get([
                    'id_part' => $row['id_part'],
                    'id_lokasi_rak' => $id_lokasi_rak
                ], true);
        
                // Cek apakah digudang ada record yang sama.
                if($stock_didalam_gudang != null){
                    // Jika ada stock digudang tersebut ditambah.
                    $this->stock->update([
                        'qty' => $stock_didalam_gudang->qty + $row['stock'],
                    ],[
                        'id_part' => $row['id_part'],
                        'id_lokasi_rak' => $id_lokasi_rak,
                    ]);
                }else{
                    // Jika tidak, maka buat record stock di warehouse.
                    $this->stock->insert([
                        'id_part_int' => $part['id_part_int'],
                        'id_part' => $row['id_part'],
                        'id_lokasi_rak' => $id_lokasi_rak,
                        'qty' => $row['stock']
                    ]);
                }
            }
            $this->form_validation->reset_validation();
            $baris++;
        }

        if ($this->db->trans_status() and $all_valid){
            $this->db->trans_commit();
        }else{
            $this->db->trans_rollback();
            send_json([
                'error_type' => 'validation_error',
                'payloads' => $validation_error
            ], 422);
        }
    }

    public function validate(){
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('id_part', 'Kode Part', [
            'required',
            [
                'exist_by_id_part_callable',
                [$this->part, 'exist_by_id_part']
            ]
        ]);

        $this->form_validation->set_rules('rak', 'Rak', [
            'required',
        ]);

        $this->form_validation->set_rules('stock', 'Stock', [
            'required',
        ]);
    }
}