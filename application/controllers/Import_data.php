<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import_data extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
		$this->load->library('form_validation');
// 		$this->load->helper("telegram");
//         $this->load->model('Number_model');
    }


    public function index()
    {
        // $this->load->view('header');
        $this->load->view('import_view');
        // $this->load->view('footer');s
        
    }

    public function import_diskon_all(){
		$this->load->view('import_data_diskon');
	}


    public function load_temp()
    {
        date_default_timezone_set('Asia/Jakarta');
		$filename = $_GET['filename'];
		// log_r($filename);
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
					
		$excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load('excel/'.$filename.''); // Load file yang tadi diupload ke folder excel
		$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
		//skip untuk header
		unset($sheet[1]);
		// $this->db->truncate('makul_matakuliah');
        echo " <table class='table table-bordered table-striped table-hover'>
                       <thead>
                       <tr>
                       <th>No</th>
                       <th>ID PART</th>
                       <th>ID GUDANG</th>
                       <th>ID RAK</th>
                       <th>QTY</th>
                       </tr>
                   </thead>";
                    $no=1;
                    foreach ($sheet as $rw) {
                        $id=$rw['G'];
                    }
                    $data = $this->db->query("SELECT * FROM ms_h3_dealer_stock where id_dealer='$id'")->result();
                    foreach ($data as $d) { 
                        echo "<tbody><tr id='dataku$d->id'>
                                <td>$no</td>
                                <td>$d->id_part</td>
                                <td>$d->id_gudang</td>
                                <td>$d->id_rak</td>
                                <td>$d->stock</td>
                             </tr>
                           </tbody>  ";
                        $no++;   
                    }
                    echo "</table>";                 
    }

    public function aksi()
	{
        date_default_timezone_set('Asia/Jakarta');
        $filename = $_FILES['filename']['name'];
        

            $this->load->library('upload');
            $nmfile = "home".time();
            $config['upload_path']   = './excel/';
            $config['overwrite']     = true;
            $config['allowed_types'] = 'xlsx';
            $config['file_name'] = $_FILES['filename']['name'];

            $this->upload->initialize($config);

            if($_FILES['filename']['name'])
            {
                if($this->upload->do_upload('filename'))
                {
                $gbr = $this->upload->data();
                include APPPATH.'third_party/PHPExcel/PHPExcel.php';
					
                $excelreader = new PHPExcel_Reader_Excel2007();
                $loadexcel = $excelreader->load('excel/'.$filename.''); 
                $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
                unset($sheet[1]);
        
                $no1=0;
                $no2=0;
                $number1=0;
                $number2=0;
                $numbers1=0;
                $numbers2=0;
                
                foreach ($sheet as $rw) {
                 
                    $nomor_gudang = $rw['F']."/"."WHS-".$rw['C'];
                    $cek_gudang = $this->db->get_where('ms_gudang_h23', array('id_gudang'=>$nomor_gudang,'id_dealer'=>$rw['G']));
                    if ($cek_gudang->num_rows() == 0) {
                        $no1++;
                        $data_insert = array(
                            'id_gudang'=>$nomor_gudang,
                            'tipe_gudang'=>"Good",
                            'deskripsi_gudang'=>'...',
                            'alamat'=>'...',
                            'id_dealer'=>$rw['G'],
                            'luas_gudang'=>0,
                            'kategori'=>"Permanent",
                            'created_at'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('ms_gudang_h23', $data_insert);
                    }

                    $nomor_gudang = $rw['F']."/"."WHS-".$rw['C'];
                    $cek_rak = $this->db->get_where('ms_lokasi_rak_bin', array('id_rak'=>$rw['D'],'id_dealer'=>$rw['G']));
                    if ($cek_rak->num_rows() == 0) {
                        $number1++;
                        $rak_insert = array(
                            'id_rak'=>$rw['D'],
                            'deskripsi_rak'=>'...',
                            'unit'=>'1',
                            'id_gudang'=>$nomor_gudang,
                            'id_dealer'=>$rw['G'],
                        );
                        $this->db->insert('ms_lokasi_rak_bin', $rak_insert);
                       
                    } 
                    
                        $nomor_gudang = $rw['F']."/"."WHS-".$rw['C'];
                        $cek_stok = $this->db->get_where('ms_h3_dealer_stock', array('id_part'=>$rw['A'],'id_dealer'=>$rw['G'],'id_gudang'=>$nomor_gudang,'id_rak'=>$rw['D']));
                        
                            $numbers1++;
                            $stok_insert = array(
                                'id_part'=>strtoupper($rw['A']),
                                'id_dealer'=>$rw['G'],
                                'id_gudang'=>$nomor_gudang,
                                'id_rak'=>$rw['D'],
                                'stock'=>$rw['E'],
                                'freeze'=>0
                            );
                            $this->db->insert('ms_h3_dealer_stock', $stok_insert);
                            $insert_log = array(
                                'id_part'=>$rw['A'],
                                'id_gudang'=>$nomor_gudang,
                                'id_rak'=>$rw['D'],
                                'id_dealer'=>$rw['G'],
                                'tipe_transaksi'=>"+",
                                'sumber_transaksi'=>"inject_stock",
                                'referensi'=>"-",
                                'stok_awal'=>0,
                                'stok_value'=>$rw['E'],
                                'stok_akhir'=>$rw['E'],
                                'created_at'=>date('Y-m-d H:i:s')
                            );
                            $this->db->insert('ms_h3_dealer_transaksi_stok',$insert_log);
                   
                }
                
                echo $no1.'DATA GUDANG BERHASIL INSERT<br>';
                
                echo $number1.'DATA RAK BERHASIL INSERT<br>';
                
                echo $numbers1.'DATA STOK BERHASIL INSERT<br>';
                
                echo $numbers2.'DATA STOK BERHASIL UPDATE<br>';

            }

        }
		
    }
    

    public function import_jasa(){
        $this->load->view('import_jasa_view');
    }
	
	 public function import_status_part(){
        $this->load->view('import_status_part');
    }

    public function kode(){
       
    }


    

    public function jasa_aksi(){
        date_default_timezone_set('Asia/Jakarta');
        $filename = $_FILES['filename']['name'];
        

            $this->load->library('upload');
            $nmfile = "home".time();
            $config['upload_path']   = './excel/';
            $config['overwrite']     = true;
            $config['allowed_types'] = 'xlsx';
            $config['file_name'] = $_FILES['filename']['name'];

            $this->upload->initialize($config);

            if($_FILES['filename']['name'])
            {
                if($this->upload->do_upload('filename'))
                {
                $gbr = $this->upload->data();
                include APPPATH.'third_party/PHPExcel/PHPExcel.php';
					
                $excelreader = new PHPExcel_Reader_Excel2007();
                $loadexcel = $excelreader->load('excel/'.$filename.''); 
                $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
                unset($sheet[1]);
        
                $no1=0;
                $urutan=0;  
                $activity_capacity = "";
                $activity_promotion = "";               
                foreach ($sheet as $rows) {
                    if($rows['U'] == '1'){
                        $activity_capacity = "BS";
                    }elseif($rows['U'] == '2'){
                        $activity_capacity = "HH";
                    }else{
                        $activity_capacity = "LL";
                    }
                    
                    if($rows['V'] =='1'){
                        $activity_promotion ="SVPS";
                    }elseif($rows['V'] =='2'){
                        $activity_promotion ="SVJD";
                    }elseif($rows['V'] =='3'){
                        $activity_promotion ="SVGC";
                    }elseif($rows['V'] =='4'){
                        $activity_promotion ="SVPA";
                    }elseif($rows['V'] =='5'){
                        $activity_promotion ="SVER";
                    }elseif($rows['V'] =='6'){
                        $activity_promotion ="PE";
                    }elseif($rows['V'] =='7'){
                        $activity_promotion ="RM";
                    }elseif($rows['V'] =='8'){
                        $activity_promotion ="AE01";
                    }elseif($rows['V'] =='9'){
                        $activity_promotion ="AE02";
                    }elseif($rows['V'] =='10'){
                        $activity_promotion ="AE03";
                    }elseif($rows['V'] =='11'){
                        $activity_promotion ="NP";
                    }
                    $kode_jasa_ahm = $rows['H'].$rows['W'].$rows['X'].$activity_capacity.$activity_promotion;
                    
                    $no1++;
                    $kode = $rows['C'];
                    if($kode==""){
                        $idJasa = $this->Number_model->generateKodeJasa();
                    }else{
                        $idJasa = $rows['C'];
                    }
                    $data = array(
                        "id_jasa"=>$idJasa,
                        "id_jasa2"=>$rows['D'],
                        "deskripsi"=>strtoupper($rows['E']),
                        "id_type"=>$rows['F'],
                        "kategori"=>$rows['G'],
                        "tipe_motor"=>$rows['H'],
                        "harga"=>$rows['I'],
                        "batas_atas"=>$rows['J'],
                        "batas_bawah"=>$rows['K'],
                        "waktu"=>$rows['L'],
                        "active"=>$rows['M'],
                        "created_at"=>date('Y-m-d H:i:s'),
                        "created_by"=>1,
                        "updated_at"=>NULL,
                        "updated_by"=>NULL,
                        "deleted_at"=>NULL,
                        "deleted_by"=>NULL,
                        "is_favorite"=>NULL,
                        "activity_capacity"=>$activity_capacity,
                        "activity_promotion"=>$activity_promotion,
                        "kode_jenis_pekerjaan"=>$rows['W'],
                        "kode_kategori_pekerjaan"=>$rows['X'],
                        "kode_jasa_ahm"=>$kode_jasa_ahm
                    );
                    $cek = $this->db->query("SELECT id_jasa from ms_h2_jasa where id_jasa='$idJasa'")->num_rows();
                    if($cek <= 0){
                        $this->db->insert('ms_h2_jasa',$data);
                    }else{
                        $this->db->where("id_jasa",$kode);
                        $this->db->update('ms_h2_jasa',$data);
                    }
                   
                }

                echo $no1." Data master jasa berhasil di input";
            }

        }
    }



    public function aksi_diskon_all(){
        date_default_timezone_set('Asia/Jakarta');
        $filename = $_FILES['filename']['name'];
        

            $this->load->library('upload');
            $nmfile = "home".time();
            $config['upload_path']   = './excel/';
            $config['overwrite']     = true;
            $config['allowed_types'] = 'xlsx';
            $config['file_name'] = $_FILES['filename']['name'];

            $this->upload->initialize($config);

            if($_FILES['filename']['name'])
            {
                if($this->upload->do_upload('filename'))
                {
                $gbr = $this->upload->data();
                include APPPATH.'third_party/PHPExcel/PHPExcel.php';
					
                $excelreader = new PHPExcel_Reader_Excel2007();
                $loadexcel = $excelreader->load('excel/'.$filename.''); 
                $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
                unset($sheet[1]);
        
                $no1=0;
                            
                foreach ($sheet as $rows) {
                    $data = array(
                        "id_part"=>$rows['B'],
                        "tipe_diskon"=>"Persen",
                        "diskon_fixed"=>$rows['D'],
                        "diskon_urgent"=>0,
                        "diskon_hotline"=>0,
                        "diskon_reguler"=>$rows['D'],
                        "diskon_other"=>0,
                        "active"=>1,
                        "created_at"=>date('Y-m-d H:i:s'),
                        "created_by"=>1,
                    );
                    $data2= array(
                        "status"=>$rows['C']
                    );
                    $part = $rows['B'];
                    $cek = $this->db->query("SELECT id_part from ms_h3_md_diskon_part_tertentu where id_part='$part'")->num_rows();
                    if($cek <= 0){
                        $this->db->insert('ms_h3_md_diskon_part_tertentu',$data);
                    }else{
                        $this->db->where("id_part",$part);
                        $this->db->update('ms_h3_md_diskon_part_tertentu',$data);
                    }
                    $cek_part = $this->db->query("SELECT id_part from ms_part where id_part='$part'")->num_rows();
                    if($cek > 0){
                        $this->db->where("id_part",$part);
                        $this->db->update('ms_part',$data2);
                    }
                }

                echo $no1." Data master Diskon berhasil di perbarui";
            }

        }
    }
	
	
	public function aksi_status_all(){
        date_default_timezone_set('Asia/Jakarta');
        $filename = $_FILES['filename']['name'];
        

            $this->load->library('upload');
            $nmfile = "home".time();
            $config['upload_path']   = './excel/';
            $config['overwrite']     = true;
            $config['allowed_types'] = 'xlsx';
            $config['file_name'] = $_FILES['filename']['name'];

            $this->upload->initialize($config);

            if($_FILES['filename']['name'])
            {
                if($this->upload->do_upload('filename'))
                {
                $gbr = $this->upload->data();
                include APPPATH.'third_party/PHPExcel/PHPExcel.php';
					
                $excelreader = new PHPExcel_Reader_Excel2007();
                $loadexcel = $excelreader->load('excel/'.$filename.''); 
                $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
                unset($sheet[1]);
        
                $no1=0;
                            
                foreach ($sheet as $rows) {
                    $data = array(
                        "status"=>$rows['B'],  
                    );
                    
                    $part = $rows['A'];
                    
                    $cek_part = $this->db->query("SELECT id_part from ms_part where id_part='$part'")->num_rows();
                    if($cek_part > 0){
                        $this->db->where("id_part",$part);
                        $this->db->update('ms_part',$data);
                    }
                }

                echo $no1." Data master Diskon berhasil di perbarui";
            }

        }
    }
	
	public function import_master_diskon_tertentu(){
		$this->load->view('import_diskon_tertentu');
	}
	
	public function aksi_import_diskon_tertentu(){
		date_default_timezone_set('Asia/Jakarta');
        $filename = $_FILES['filename']['name'];
        

            $this->load->library('upload');
            $nmfile = "home".time();
            $config['upload_path']   = './excel/';
            $config['overwrite']     = true;
            $config['allowed_types'] = 'xlsx';
            $config['file_name'] = $_FILES['filename']['name'];

            $this->upload->initialize($config);

            if($_FILES['filename']['name'])
            {
                if($this->upload->do_upload('filename'))
                {
                $gbr = $this->upload->data();
                include APPPATH.'third_party/PHPExcel/PHPExcel.php';
					
                $excelreader = new PHPExcel_Reader_Excel2007();
                $loadexcel = $excelreader->load('excel/'.$filename.''); 
                $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
                unset($sheet[1]);
				
                $no1=0;
                            
                foreach ($sheet as $rows) {
					$kode = $rows['A'];
					$dealer = $rows['B'];
					$part  = $this->db->query("SELECT id from ms_h3_md_diskon_part_tertentu where id_part='$kode'")->row()->id;
					$id_dealer = $this->db->query("SELECT 
					case 
						when kode_dealer_md ='JBI-E197' THEN '51'  	 
						when kode_dealer_md='MWP' THEN '18'  
						when kode_dealer_md='MS-R001' THEN '8' else id_dealer
					END
					 AS id_dealer from ms_dealer where kode_dealer_md='$dealer'")->row();
					$dealers= $id_dealer->id_dealer == NULL ? "0" : $id_dealer->id_dealer;
                    $data = array(
                        "id_diskon_part_tertentu"=>$part,
						"id_dealer"=>$dealers,
						"tipe_diskon"=>"Persen",
						"diskon_fixed"=>$rows['C'],
						"diskon_reguler"=>$rows['C']
                    );
					$cek_data = $this->db->query("SELECT * FROM ms_h3_md_diskon_part_tertentu_items where id_diskon_part_tertentu
					='$part' and id_dealer='$dealers'")->num_rows();
					if($cek_data > 0){
						$this->db->where('id_diskon_part_tertentu',$part);
						$this->db->where('id_dealer',$dealers);
						$update = $this->db->update('ms_h3_md_diskon_part_tertentu_items',$data);
					}else{
						if($part != NULL && $dealers != NULL){
							$insert = $this->db->insert('ms_h3_md_diskon_part_tertentu_items',$data);
						}else{
							return "Success";
						}
					}
                }

                echo $no1." Data master Diskon berhasil di perbarui";
            }

        }
	}


}
?>