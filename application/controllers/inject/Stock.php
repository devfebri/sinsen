<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends CI_Controller {

    protected $folder = 'inject';
    protected $page = 'stock';

    public function __construct(){
        parent::__construct();
        $config = [
            'allowed_types' => 'xls|xlsx',
            'upload_path' => './uploads/inject',
            'encrypt_name' => true
        ];
        $this->load->library('upload', $config);

        $this->load->model('part_model', 'part');
        $this->load->model('dealer_model', 'dealer');
        $this->load->model('h3_dealer_stock_model', 'stock');
        $this->load->model('h3_dealer_gudang_h23_model', 'gudang');
        $this->load->model('h3_dealer_lokasi_rak_bin_model', 'lokasi_rak_bin');
        $this->load->model('h3_dealer_transaksi_stok_model', 'transaksi_stok');
    }

    public function index(){
        $this->load->view('inject/stock');
    }

    public function upload_excel(){
        if ($this->upload->do_upload('file')){
            $data = $this->read_excel($this->upload->data()['file_name']);
            $this->proses_data($data);
        }
        else{
            echo 'Upload gagal! <br>';
            echo $this->upload->display_errors();
        }
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
        $keyName = ['id_part', 'nama_part', 'gudang', 'rak', 'stock', 'kode_dealer'];

        $result = [];
        for ($row = 1; $row <= $highestRow; $row++){ 
            $rowData = $sheet->rangeToArray('B' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];

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

            $row['rak'] = str_replace(" ","-", $row['rak']);
            $row['gudang'] = str_replace(" ","-", $row['gudang']);

            $this->form_validation->set_data($row);
            $this->validate();
                
            if (!$this->form_validation->run()){
                $all_valid = false;
                $validation_error[] = [
                    'message' => "Terdapat data tidak lengkap pada kode part {$row['id_part']} [Baris: {$baris}]",
                    'errors' => $this->form_validation->error_array(),
                ];
            }else{
                $part = $this->part->find($row['id_part'], 'id_part');
                
                if($part == null){
                    continue;
                }

                $dealer = $this->dealer->find($row['kode_dealer'], 'kode_dealer_md');

                $kode_gudang = "{$row['kode_dealer']}/WHS-{$row['gudang']}";
                $gudang = $this->gudang->get([
                    'id_gudang' => $kode_gudang,
                    'id_dealer' => $dealer->id_dealer
                ], true);

                if ($gudang == null) {
                    $this->gudang->insert([
                        'id_gudang' => $kode_gudang,
                        'tipe_gudang' => 'Good',
                        'deskripsi_gudang' => '...',
                        'alamat' => '...',
                        'id_dealer' => $dealer->id_dealer,
                        'luas_gudang' => 0,
                        'kategori' => 'Permanent'
                    ]);
                }

                $lokasi_rak_bin = $this->lokasi_rak_bin->get([
                    'id_rak' => $row['rak'],
                    'id_gudang' => $kode_gudang,
                    'id_dealer' => $dealer->id_dealer
                ]);

                if($lokasi_rak_bin == null){
                    $this->lokasi_rak_bin->insert([
                        'id_rak' => $row['rak'],
                        'deskripsi_rak' => '...',
                        'unit' => 1,
                        'id_gudang' => $kode_gudang,
                        'id_dealer' => $dealer->id_dealer
                    ]);
                }

                $transaksi_stock = [
                    'id_part' => $row['id_part'],
                    'id_rak' => $row['rak'],
                    'id_gudang' => $kode_gudang,
                    'tipe_transaksi' => '+',
                    'sumber_transaksi' => 'inject_stock',
                    'referensi' => '-',
                    'stok_value' => $row['stock'],
                    'id_dealer' => $dealer->id_dealer
                ];
                $this->transaksi_stok->insert($transaksi_stock);

                $stock_didalam_gudang = $this->stock->get([
                    'id_part' => $row['id_part'],
                    'id_rak' => $row['rak'],
                    'id_gudang' => $kode_gudang,
                    'id_dealer' => $dealer->id_dealer,
                ], true);
        
                // Cek apakah digudang ada record yang sama.
                if($stock_didalam_gudang != null){
                    // Jika ada stock digudang tersebut ditambah.
                    $this->stock->update([
                        'stock' => $stock_didalam_gudang->stock + $row['stock'],
                    ],[
                        'id_part' => $part->id_part,
                        'id_rak' => $row['rak'],
                        'id_gudang' => $kode_gudang,
                        'id_dealer' => $dealer->id_dealer,
                    ]);
                }else{
                    // Jika tidak, maka buat record stock di warehouse.
                    $this->stock->insert([
                        'id_part' => $part->id_part,
                        'id_rak' => $row['rak'],
                        'id_gudang' => $kode_gudang,
                        'id_dealer' => $dealer->id_dealer,
                        'stock' => $row['stock']
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
            $this->output->set_status_header(400);
            send_json([
                'error_type' => 'validation_error',
                'payloads' => $validation_error
            ]);
        }
    }

    public function validate(){
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('id_part', 'Kode Part', [
            'required',
            // 'alpha_dash',
            // [
            //     'exist_by_id_part_callable',
            //     [$this->part, 'exist_by_id_part']
            // ]
        ]);
        $this->form_validation->set_rules('kode_dealer', 'Dealer', [
            'required',
            [
                'exist_by_kode_dealer_callable',
                [$this->dealer, 'exist_by_kode_dealer']
            ]
        ]);

        $this->form_validation->set_rules('rak', 'Rak', [
            'required',
        ]);

        $this->form_validation->set_rules('rak', 'Rak', [
            'required',
        ]);

        $this->form_validation->set_rules('gudang', 'Gudang', [
            'required',
        ]);
    }
}