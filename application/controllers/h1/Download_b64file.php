<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Download_b64file extends CI_Controller
{
  var $folder =   "h1";
  var $page   =   "Download File Base64";
  var $title  =   "Download File Base64";

  public function __construct()
  {

    parent::__construct();
    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    //===== Load Library =====
    $this->load->library('upload');
    //---- cek session -------//    

    $name = $this->session->userdata('nama');
    $auth = $this->m_admin->user_auth($this->page, "select");
    $sess = $this->m_admin->sess_auth();
    if ($name == "" or $auth == 'false' or $sess == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
    }
  }

  protected function template($data)
  {
    $name = $this->session->userdata('nama');

    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $data['id_menu'] = $this->m_admin->getMenu($this->page);
      $data['group']  = $this->session->userdata("group");
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $this->load->view($this->folder . "/" . $this->page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
    // $data['isi']    = $this->page;
    // $data['title']  = $this->title;
    // $data['set']    = "view";
    // $this->template($data);

    echo 'ok';die;
  }

  function list_data(){
    // tarik ada penjualan per bulan (pengaruh sama session?)
    // set default download folder di komputer
    // jalankan script download file pakai schedular
    
    $data = $this->db->query("select a.id_sales_order , a.no_mesin, a.tgl_cetak_invoice
    from tr_sales_order a
    join tr_spk b on a.no_spk = b.no_spk
    where b.program_umum !='' and tgl_cetak_invoice between '2022-11-01' and '2022-11-01' order by a.tgl_create_ssu asc limit 1")->result();

    foreach ($data as $row){
      $id_claim = $this->db->query("
        select id_claim from tr_claim_dealer where id_sales_order = '$row->id_sales_order' and status ='approved'
      ")->row()->id_claim;

      $get_id_syarat = $this->db->query("
        select id, filename from tr_claim_dealer_syarat where id_claim ='$id_claim' and filename !=''
      ")->result();

      foreach ($get_id_syarat as $row_syarat){ 
        $tgl_ssu = str_replace("-","", $row->tgl_cetak_invoice);
        $tahun = substr($tgl_ssu,0,4);
        $bln = substr($tgl_ssu,4,2);
        $day = substr($tgl_ssu,6,2);

        // as is : downloads/syarat_claim/2023/10/01/23/no_mesin
        // to be : downloads/syarat_claim/tahun/bulan/dealer/no_mesin

        $folder 	= "downloads/syarat_claim/".$tahun;
        if (!file_exists($folder)) {
          echo $folder.'<br>';
          mkdir($folder, 0777);	
        }  
      
        $folder 	= "downloads/syarat_claim/".$tahun.'/'.$bln;
        if (!file_exists($folder)) {
          echo $folder.'<br>';
          mkdir($folder, 0777);	  
        }
        
        $folder 	= "downloads/syarat_claim/".$tahun.'/'.$bln.'/'.$day;
        if (!file_exists($folder)) {
          echo $folder.'<br>';
          mkdir($folder, 0777);	  
        }
        
        // create folder no mesin
        $file_path = $folder.'/'.$row->no_mesin;
        if (!file_exists($file_path)) {
          mkdir($file_path, 0777);	
        }	 

        // create file to server
        // $this->create_file($row_syarat->id,$file_path); 
      }
    }
  }

  function create_file($id,$path){
    $get_data = $this->db->query("select file, filename from tr_claim_dealer_syarat where id='$id' and filename !=''")->row();
    
    $filename='no_file';
    if(count($get_data) > 0){
      $b64 = $get_data->file;
      $ext = explode('.',$get_data->filename)[1];
      $filename = $get_data->filename;
      
      $file = $path.'/'.$filename;
      echo $filename.'<br>';
      
      $bin = base64_decode($b64, true);
      
      // Write the contents back to the file
      file_put_contents($file, $bin);
    }
  }

  function download_image_x($id){
    // $id ='710686';
    // $id = $this->input->post('id');
    $get_data = $this->db->query("select file, filename from tr_claim_dealer_syarat where id='$id' and filename !=''")->row();
    
    $filename='no_file';
    if(count($get_data) > 0){
      $b64 = $get_data->file;
      $ext = explode('.',$get_data->filename)[1];
      $filename = $get_data->filename;
      
      $data['b64'] = $b64;
      $data['ext'] = $ext;
      $data['filename'] = $filename;
      // $this->load->view('h1/t_download_image', $data);

    //   function download(filename){
    //     window.location="http://whateveryoursiteis.com/download.php?file="+filename;
    // }

    /*
    $.ajax({
        url : "<?php echo site_url('h1/Download_b64file/list_data')?>",
        type:"POST",
        data:"",
        cache:false,
        success:function(msg){            
            window.location="<?php echo site_url('h1/Download_b64file/download_image?id=')?>"+msg.id;
        }
    })
    
    */

      ob_start();
      $bin = base64_decode($b64, true);

      # Perform a basic validation to make sure that the result is a valid PDF file
      # Be aware! The magic number (file signature) is not 100% reliable solution to validate PDF files
      # Moreover, if you get Base64 from an untrusted source, you must sanitize the PDF contents
      if (strpos($bin, '%PDF') !== 0) {
        // throw new Exception('Missing the PDF file signature');
      }

      # Write the PDF contents to a local file
      //file_put_contents('file.pdf', $bin);

      # Base64 to pdf (create file pdf from database, no file in server)
      /**/

      ob_clean(); // jika mau file download aktifkan line ini, jika utk preview dinonaktifkan
      // content pdf yang rumit (fpdf) tidak bisa generate file pdf dengan sempurna / corrupt

      // header('Content-Description: File Transfer');
      header('Expires: 0');
      header('Pragma: public');

      if($ext == 'xls' || $ext =='xlsx'){
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
        header("Content-Type: application/force-download");
        header("Content-Type: application/download");
        header("Content-type: application/vnd-ms-excel");
        header('Content-disposition: attachment; filename='.$filename); // komen line ini utk bisa preview pdf atau aktifkan line ini utk bisa download file pdf
        header('Content-Length: '.strlen($bin));
        header("Cache-Control: max-age=0");
      }else{
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
        header("Content-Type: application/force-download");
        header("Content-Type: application/download");
        header('Content-Type: application/'.$ext);
        header('Content-disposition: attachment; filename='.$filename); // komen line ini utk bisa preview pdf atau aktifkan line ini utk bisa download file pdf
        header('Content-Length: '.strlen($bin));
      }
      
      echo $bin;
      exit;

      # atau bisa dengan akses link from path file (perlu save file di server). 
      # eg: http://localhost/base64/aplikasi.pdf
      ob_end_flush();
    }

    // die;
    // $this->load->view('dealer/t_download_juklak', $data);
  }

  
}
