<?php



defined('BASEPATH') or exit('No direct script access allowed');



class Claim_program_ahm extends CI_Controller

{



  var $tables =   "tr_do_dealer";



  var $folder =   "h1";



  var $page   =   "claim_program_ahm";



  var $pk     =   "no_do";



  var $title  =   "Claim Program AHM";



  public function __construct()

  {



    parent::__construct();



    //===== Load Database =====

    $this->load->database();



    $this->load->helper('url');

		$this->load->helper('astra_helper');	



    //===== Load Model =====

    $this->load->model('m_admin');

    $this->load->model('M_claim_program_ahm', 'cma');

    $this->load->model('M_claim_program_ireg', 'cmc');



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



  function count_filtered()

  {

    $this->get_sibp();

    $query = $this->db->get();

    return $query->num_rows();

  }



  public function count_all()

  {

    $this->db->from($this->table);

    return $this->db->count_all_results();

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

    $data['isi']    = $this->page;

    $data['title']  = $this->title;

    $data['set']    = "view";

    $this->template($data);

  }



  public function show_tb()

  {

    if (isset($_POST['nojuk'])) {

      $nojuk = $_POST['nojuk'];

    }



    if (isset($_POST['idsales'])) {

      $idsales = $_POST['idsales'];

    }

    if (isset($_POST['year'])) {

      $year = $_POST['year'];

    }

    if (isset($_POST['mon'])) {

      $mon = $_POST['mon'];

    }

    if (isset($_POST['dcode'])) {

      $dcode = $_POST['dcode'];

    }

    if (isset($_POST['owner'])) {

      $owner = $_POST['owner'];

    }

    if (isset($_POST['nosin'])) {

      $nosin = $_POST['nosin'];

    }

    if (isset($_POST['send'])) {

      $send = $_POST['send'];

    }

    if (isset($_SESSION['tipe'])) {

      unset($_SESSION['tipe'] );

    }

    if (isset($_SESSION['pesan'])) {

      unset($_SESSION['pesan'] );

    }



    $m = "return confirm('yakin merubah data?')";



    // testing

    $list = $this->cma->get_datatables($nojuk, $idsales, $year, $mon, $dcode, $owner, $nosin, $send);

    $data = array();

    $no = $this->input->post('start');





    foreach ($list as $field) {

      $no++;

      $row = array();

      $row[] = $no;

      $row[] = $field->yr;

      $row[] = $field->prd;

      $row[] = $field->asm;

      $row[] = $field->descJuklak;

      $row[] = $field->idpm;

      $row[] = $field->judul_kegiatan;

      $row[] = $field->no_rangka;

      $row[] = $field->nos;

      $row[] = $field->tgl_approve_reject_md;

      $row[] = $field->kode_dealer_md;

      $row[] = $field->nama_dealer;

      $row[] = $field->tipe_motor;

      $row[] = $field->tipe_ahm;

      

      $statusVerifikasi1= '';

      $statusVerifikasi2= '';



      if($field->statusVerifikasi1 == 1 && $field->send_ahm==1){

        $statusVerifikasi1= 'Y';

      }else if($field->statusVerifikasi1 == 0){

        $statusVerifikasi1= 'N';

      }



      if($field->statusVerifikasi2 == 1 && $field->send_ahm==1){

        $statusVerifikasi2= 'Y';

      }else if($field->statusVerifikasi2 == 0){

        $statusVerifikasi2= 'N';

      }



      $row[] = $statusVerifikasi1;

      $row[] = $statusVerifikasi2;

      $row[] = $field->errorMessage;

      $row[] = $field->rejectMessage;

      $row[] = $field->send_ahm;



      $btn_view = '<a  href="'  . base_url('h1/claim_program_ahm/views/') . $field->idpm . '/' . $field->nos . '" class="btn btn-primary btn-flat bta">View</a>';



      if ($field->send_ahm == 1) {

        $but = 'disabled';

        $aria = 'aria-disabled="true"';

        $te = 'Claimed';

        $btn_claim = ' <a href="' . base_url('h1/claim_program_ahm/api_claim/') . $field->idclaim . '" class="btn btn-warning btn-flat bta ' . $but . '" ' . $aria . '> ' . $te . '</a>';



        $btn_irregular = '';

        if ($field->statusVerifikasi2 == 0 || $field->statusVerifikasi2 == '') {

          $btn_irregular = '<a href="' . base_url('h1/claim_program_ahm/irregular/') .  $field->id_claim . '" class="btn btn-success btn-flat bta" onclick="' . $m . '">Ireguler</a>';

        }

        $btn = $btn_view . '' . $btn_claim . '' . $btn_irregular;

      } else {

        $but = "";

        $aria = "";

        $te = 'Claim';

        $btn_claim = ' <a href="' . base_url('h1/claim_program_ahm/api_claim/') . $field->idclaim . '" class="btn btn-warning btn-flat bta ' . $but . '" ' . $aria . '> ' . $te . '</a>';



        $btn = $btn_view . '' . $btn_claim;

      }



      $row[] = $btn;

      $data[] = $row;

    }

    

    $output = array(

      "draw" => $this->input->post('draw'),

      //"recordsTotal" => $this->cma->count_all($nojuk, $idsales, $year, $mon, $dcode, $owner, $nosin, $send),

      "recordsFiltered" => $this->cma->count_filtered($nojuk, $idsales, $year, $mon, $dcode, $owner, $nosin, $send),

      "data" => $data,

    );

    echo json_encode($output);

  }







  // testing

  public function api_claim($idclaim){

    $token =  get_token_astra();



    // url testing    

		// $url = 'https://portaldev.ahm.co.id/jx06/ahmfascp000-pst/rest/fa/scp002/md-post-claim';

    // $idclaim = '202204130001'; // jika sudah live dikomen / dihapus baris ini

    

    // url production

    $url = 'https://portal2.ahm.co.id/jx06/ahmfascp000-pst/rest/fa/scp002/md-post-claim/s';



    // perlukah dibuat utk foreach data jika claim lebih dari 1 data

    $get_info_claim = $this->db->query("

      select a.id_claim_sp, a.id_claim_dealer, a.send_ahm , a.date_send_to_ahm ,b.id_dealer , b.id_sales_order , b.tgl_ajukan_claim , b.tgl_approve_reject_md, 

      c.no_mesin , concat(h.no_frame,c.no_rangka) as no_rangka , c.tgl_cetak_invoice , c.no_invoice , c.tgl_bastk , c.no_bastk , 

      (case when d.jenis_beli = 'Kredit' then d.voucher_2 else d.voucher_1 end) as diskon_program, d.no_ktp , d.id_tipe_kendaraan, e.kode_dealer_ahm ,

      g.juklakNo , MONTH (c.tgl_cetak_invoice) as progMonth, year(c.tgl_cetak_invoice) as progYear

      from tr_claim_sales_program_detail a

      join tr_claim_dealer b on a.id_claim_dealer =b.id_claim 

      join tr_sales_order c on b.id_sales_order = c.id_sales_order 

      join tr_spk d on c.no_spk = d.no_spk 

      join ms_dealer e on b.id_dealer = e.id_dealer 

      join tr_sales_program f on f.id_program_md = b.id_program_md 

      join ms_juklak_ahm g on f.id_program_ahm = g.juklakNo 

      join tr_shipping_list h on c.no_mesin = h.no_mesin

      where a.id_claim_dealer ='$idclaim' and f.id_program_ahm !='' and g.juklakNo !=''"

    );

    

    if($get_info_claim->num_rows() > 0 ){

      $claim = $get_info_claim->row();



      $object = new stdclass();

      $object->progYear = $claim->progYear;

      $object->progMonth = $claim->progMonth; 

      $object->juklakNo = $claim->juklakNo;

      $object->frameNo = $claim->no_rangka;

      $object->engineNo = $claim->no_mesin;

      $object->dealerCode = $claim->kode_dealer_ahm;

      $object->mdApproveDate = $claim->tgl_approve_reject_md;

      $object->motorType = $claim->id_tipe_kendaraan;

      $object->discountValue = $claim->diskon_program;

      $object->invoiceNo = $claim->no_invoice;

      $object->invoiceDate = $claim->tgl_cetak_invoice;

      $object->bastDate = date_format(date_create($claim->tgl_bastk),"Y-m-d");

      $object->nik = $claim->no_ktp;

      $object->dealerSubmitDate = date_format(date_create($claim->tgl_ajukan_claim),"Y-m-d H:i:s");

      // $object->dealerSubmitDate = $claim->tgl_ajukan_claim;



      // $object->progYear = 2022;

      // $object->progMonth = 3;

      // $object->juklakNo = "UAT/ATSC/0314/004";

      // $object->frameNo = "MH1JF22109K021513";

      // $object->engineNo = "JF22E1022758";

      // $object->dealerCode = "07628";

      // $object->mdApproveDate = "2022-02-10 09:40:10";

      // $object->motorType = "CM1";

      // $object->discountValue = 2300000;

      // $object->invoiceNo = "inv-no-xxx";

      // $object->invoiceDate = "2022-03-02";

      // $object->bastDate = "2022-02-02";

      // $object->nik = "9876568271123456";

      // $object->dealerSubmitDate = "2022-02-12 09:40:10";



      $data[] = $object; 



      $hasil = api_auto_claim($token['jxid'], $token['txid'], $url, $data); // api 2 : pengajuan claim md to ahm



      $hasil = json_decode($hasil,true);

      $get_data_claim = $this->db->query("select id, engineNo, juklakNo from tr_pengajuan_claim_to_ahm where engineNo='$object->engineNo' and juklakNo='$object->juklakNo'");



      if($object->juklakNo!=''){

        if($hasil['status'] == '1'){

          if($get_data_claim->num_rows()> 0 ){

            // sudah pernah ajukan klaim

            $id = $get_data_claim->row()->id;

            $this->db->update('tr_pengajuan_claim_to_ahm', array('updatedDate'=>  date('Y-m-d H:i:s') ), "id='$id'");

            

            $_SESSION['pesan'] 	= 'Gagal! Data sudah pernah claim ke AHM';

            $_SESSION['tipe'] 	= "warning";

            echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/claim_program_ahm'>";

            exit();

          }else{

            $this->db->insert('tr_pengajuan_claim_to_ahm', array(

              'id_claim_dealer' => $idclaim,

              'juklakNo' => $object->juklakNo,

              'engineNo' => $object->engineNo,

              'frameNo' => $object->frameNo,

              'dealerCode' => $object->dealerCode,

              'nik'=>$object->nik,

              'motorType'=>$object->motorType,

              'discountValue'=>$object->discountValue,

              'invoiceNo'=>$object->invoiceNo,

              'invoiceDate' =>$object->invoiceDate, 

              'statusVerifikasi1' => 1,

              'bastDate'=>$object->bastDate,

              'dealerSubmitDate' => $object->dealerSubmitDate,

              'mdApproveDate' => $object->mdApproveDate,

              'createdDate' => date('Y-m-d H:i:s')

            ));

            

            // update status claim send to ahm = 1

            $this->db->where('id_claim_dealer', $idclaim);

            $this->db->set('send_ahm', 1);

            $this->db->set('date_send_to_ahm', date('Y-m-d H:i:s'));

            $this->db->update('tr_claim_sales_program_detail');

          }

          $_SESSION['pesan'] 	= $hasil['message']['notes'];

          $_SESSION['tipe'] 	= "success";

          

          echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/claim_program_ahm'>";

          exit();

        }else{

          if(count($hasil['data'])>0){

            foreach($hasil['data'] as $row){

              // $msg = implode("; ",$hasil['data'][0]['errorMessage']);

              $msg = implode("; ",$row['errorMessage']);

              $juklak_no = $row['juklakNo'];

              $noMesin = $row['engineNo'];

              $status_verifikasi1 = '0';

              

              // penambahan 28 nov 2022

              $get_data_claim = $this->db->query("select id, engineNo, juklakNo from tr_pengajuan_claim_to_ahm where engineNo='$object->engineNo' and juklakNo='$object->juklakNo' and statusVerifikasi1=0");

              

              if($get_data_claim->num_rows()> 0 ){

                // sudah pernah ajukan klaim

                $id = $get_data_claim->row()->id;

                $this->db->update('tr_pengajuan_claim_to_ahm', array('updatedDate'=>  date('Y-m-d H:i:s'), 'statusVerifikasi1'=>$status_verifikasi1, 'errorMessage' => $msg ), "engineNo = '$noMesin' and juklakNo = '$juklak_no'");

              

                // $_SESSION['pesan'] 	= $hasil['message']['notes'];

                $_SESSION['pesan'] 	= $hasil['message']['notes']. '<br><br>' . implode("<br> ",$row['errorMessage']);

                $_SESSION['tipe'] 	= "success";

              }else{

                // echo 'Hubungi IT!';die;

                $this->db->insert('tr_pengajuan_claim_to_ahm', array(

                  'id_claim_dealer' => $idclaim,

                  'juklakNo' => $object->juklakNo,

                  'engineNo' => $object->engineNo,

                  'frameNo' => $object->frameNo,

                  'dealerCode' => $object->dealerCode,

                  'nik'=>$object->nik,

                  'motorType'=>$object->motorType,

                  'discountValue'=>$object->discountValue,

                  'invoiceNo'=>$object->invoiceNo,

                  'invoiceDate' =>$object->invoiceDate, 

                  'statusVerifikasi1'=>$status_verifikasi1, 

                  'bastDate'=>$object->bastDate,

                  'dealerSubmitDate' => $object->dealerSubmitDate,

                  'mdApproveDate' => $object->mdApproveDate,

                  'createdDate' => date('Y-m-d H:i:s'),

                  'updatedDate'=>  date('Y-m-d H:i:s'),

                  'errorMessage' => $msg

                ));

                    

                // update status claim send to ahm = 1

                $this->db->where('id_claim_dealer', $idclaim);

                $this->db->set('send_ahm', 1);

                $this->db->set('date_send_to_ahm', date('Y-m-d H:i:s'));

                $this->db->update('tr_claim_sales_program_detail');

                  

                $_SESSION['pesan'] 	= $hasil['message']['notes']. '<br><br>' . implode("<br> ",$row['errorMessage']) . '<br><br>Silahkan Hubungi IT (setelah melakukan pengecekan status approval claim di AHM) untuk dilakukan Perbaikan Status Claim di MDMS, dengan No Mesin: '.$object->engineNo;

                $_SESSION['tipe'] 	= "danger";

              }

            }

          }else{

            $msg = implode("; ",$hasil['data'][0]['errorMessage']);

            $_SESSION['pesan'] 	= $msg;

            $_SESSION['tipe'] 	= "warning";

          }  

          

          // print_r($hasil['data'][0]['errorMessage']); die;

          echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/claim_program_ahm'>";

          exit();

        }

      }else{

        $_SESSION['pesan'] 	= 'Gagal! Juklak AHM tidak ditemukan.';

        $_SESSION['tipe'] 	= "danger";

        echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/claim_program_ahm'>";

      }

    }else{

      $_SESSION['pesan'] 	= 'Gagal! Data claim tidak mengikuti program AHM.';

      $_SESSION['tipe'] 	= "danger";

      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/claim_program_ahm'>";

    }

	}



  public function show_irregular()

  {

    if (isset($_POST['nojuk1'])) {

      $nojuk1 = $_POST['nojuk1'];

    }



    if (isset($_POST['idsales1'])) {

      $idsales = $_POST['idsales1'];

    }

    if (isset($_POST['year1'])) {

      $year = $_POST['year1'];

    }

    if (isset($_POST['mon1'])) {

      $mon = $_POST['mon1'];

    }

    if (isset($_POST['dcode1'])) {

      $dcode = $_POST['dcode1'];

    }

    if (isset($_POST['owner1'])) {

      $owner = $_POST['owner1'];

    }

    if (isset($_POST['nosin1'])) {

      $nosin = $_POST['nosin1'];

    }

    // if (isset($_POST['memo1'])) {

    //   $memo = $_POST['memo1'];

    // }





    $list = $this->cmc->get_datatables($nojuk1, $idsales, $year, $mon, $dcode, $owner, $nosin);

    $data = array();

    $no = $this->input->post('start');

    foreach ($list as $field) {

      $no++;

      $row = array();



      if ($field->memo == null) {

        $but = 'disabled';

        $aria = 'aria-disabled="true"';

        $status = '0';

      } else if ($field->memo == "") {

        $but = 'disabled';

        $aria = 'aria-disabled="true"';

        $status = '0';

      } else {

        $but = "";

        $aria = '';

        $status = '1';

      }



      $row[] = $no;

      $row[] = $field->yr;

      $row[] = $field->prd;

      $row[] = $field->asm;

      $row[] = $field->descJuklak;

      $row[] = $field->idpm;

      $row[] = $field->judul_kegiatan;

      $row[] = $field->no_rangka;

      $row[] = $field->nms;

      $row[] = $field->kode_dealer_md;

      $row[] = $field->nama_dealer;

      $row[] = $field->tipe_motor;

      $row[] = $status;

      $row[] = $field->tipe_ahm;

      $row[] = $field->statusVerifikasi2;

      $row[] = $field->rejectMessage;

      $row[] =

        '<a href="' . base_url('h1/claim_program_ahm/view_irregular/') . $field->idpm . '/' . $field->nms .  '"  class="btn btn-primary btn-flat bta">View</a>';

      $data[] = $row;

    }



    $output = array(

      "draw" => $this->input->post('draw'),

      "recordsTotal" => $this->cmc->count_all(),

      "recordsFiltered" => $this->cmc->count_filtered(),

      "data" => $data,

    );

    //output dalam format JSON

    echo json_encode($output);

  }







  public function irregular($kd)

  {

    $this->db->set('is_irregular_case', 1);

    $this->db->where('id_claim_dealer', $kd);

    $this->db->update('tr_claim_sales_program_detail');

    redirect('h1/claim_program_ahm');

  }



  public function views($id, $mesin)

  {



    $data['isi']    = $this->page;

    $data['title']  = $this->title;

    $data['set']    = "views";



    $this->db->select('judul_kegiatan,tr_scan_barcode.no_rangka,tgl_approve_reject_md,kode_dealer_md,nama_dealer,

    tr_claim_dealer.id_program_md as idpm, month(tr_sales_order.tgl_cetak_invoice) as prd, year(tr_sales_order.tgl_cetak_invoice) as yr,

    tr_scan_barcode.no_mesin as nos, tr_sales_program.id_program_ahm as asm, tipe_motor,tipe_ahm');

    $this->db->from('tr_claim_dealer');

    $this->db->join('ms_dealer', 'ms_dealer.id_dealer = tr_claim_dealer.id_dealer');

    $this->db->join('tr_sales_program', 'tr_sales_program.id_program_md = tr_claim_dealer.id_program_md');

    $this->db->join('tr_claim_sales_program', 'tr_claim_sales_program.id_program_md = tr_claim_dealer.id_program_md and tr_claim_sales_program.id_dealer = tr_claim_dealer.id_dealer', 'left');

    $this->db->join('tr_sales_order', 'tr_sales_order.id_sales_order=tr_claim_dealer.id_sales_order');

    $this->db->join('tr_scan_barcode', 'tr_scan_barcode.no_mesin=tr_sales_order.no_mesin');

    $this->db->join('ms_tipe_kendaraan', 'tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan');

    $this->db->join('ms_jenis_sales_program', 'ms_jenis_sales_program.id_jenis_sales_program = tr_sales_program.id_jenis_sales_program');

    $this->db->join('ms_program_subcategory', 'ms_program_subcategory.id_subcategory = ms_jenis_sales_program.id_sub_category');

    $this->db->where('tr_claim_dealer.status', 'approved');

    $this->db->where('ms_program_subcategory.claim_to_ahm', '1');

    $this->db->where('tr_claim_dealer.id_program_md', $id);

    $this->db->where('tr_scan_barcode.no_mesin', $mesin);

    $data['vc'] = $this->db->get()->row_array();



    $this->db->select('jenis_beli,voucher_2,jenis,tipe_motor,tipe_ahm,ms_warna.id_warna,ms_warna.warna as msw,no_invoice,tgl_cetak_invoice,tgl_bastk, tr_claim_sales_program_detail.is_irregular_case as ir, ms_finance_company.id_finance_company, ms_finance_company.finance_company,no_ktp, tgl_ajukan_claim');

    $this->db->from('tr_sales_order');

    $this->db->join('tr_spk', 'tr_spk.no_spk = tr_sales_order.no_spk');

    $this->db->join('tr_claim_dealer', 'tr_claim_dealer.id_sales_order = tr_sales_order.id_sales_order');

    $this->db->join('tr_scan_barcode', 'tr_scan_barcode.no_mesin = tr_sales_order.no_mesin');

    $this->db->join('ms_tipe_kendaraan', 'tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan');

    $this->db->join('ms_warna', 'ms_warna.id_warna=tr_scan_barcode.warna');

    $this->db->join('tr_sales_program', 'tr_sales_program.id_program_md = tr_claim_dealer.id_program_md');

    $this->db->join('ms_jenis_sales_program', 'ms_jenis_sales_program.id_jenis_sales_program = tr_sales_program.id_jenis_sales_program');

    $this->db->join('ms_program_subcategory', 'ms_program_subcategory.id_subcategory = ms_jenis_sales_program.id_sub_category');

    $this->db->join('ms_finance_company', 'tr_spk.id_finance_company=ms_finance_company.id_finance_company', 'left');

    $this->db->join('tr_claim_sales_program', 'tr_claim_sales_program.id_program_md = tr_claim_dealer.id_program_md');

    $this->db->join('tr_claim_sales_program_detail', 'tr_claim_sales_program_detail.id_claim_sp = tr_claim_sales_program.id_claim_sp');

    $this->db->where('tr_claim_dealer.id_program_md', $id);

    $this->db->where('tr_scan_barcode.no_mesin', $mesin);

    $this->db->where('ms_program_subcategory.claim_to_ahm', 1);

    $data['tb2'] = $this->db->get()->row_array();







    $this->template($data);









    //$this->load->view('h1/view_claim_proposal_ahm', $data);

  }



  public function view_irregular($id, $mesin)

  {

    $data['isi']    = $this->page;

    $data['title']  = $this->title;

    $data['set']    = "view_irregular";



    $this->db->select('tipe_ahm,kode_dealer_md, nama_dealer,tipe_motor,tgl_approve_reject_md,judul_kegiatan,tr_scan_barcode.no_rangka,tr_claim_dealer.id_program_md as idpm, month(tr_sales_order.tgl_cetak_invoice) as prd, year(tr_sales_order.tgl_cetak_invoice) as yr, tr_scan_barcode.no_mesin as nos');

    $this->db->from('tr_claim_dealer');

    $this->db->join('ms_dealer', 'ms_dealer.id_dealer = tr_claim_dealer.id_dealer');

    $this->db->join('tr_sales_program', 'tr_sales_program.id_program_md = tr_claim_dealer.id_program_md');

    $this->db->join('tr_claim_sales_program', 'tr_claim_sales_program.id_program_md = tr_claim_dealer.id_program_md and tr_claim_sales_program.id_dealer = tr_claim_dealer.id_dealer', 'left');

    $this->db->join('tr_sales_order', 'tr_sales_order.id_sales_order=tr_claim_dealer.id_sales_order');

    $this->db->join('tr_scan_barcode', 'tr_scan_barcode.no_mesin=tr_sales_order.no_mesin');

    $this->db->join('ms_tipe_kendaraan', 'tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan');

    $this->db->join('ms_jenis_sales_program', 'ms_jenis_sales_program.id_jenis_sales_program = tr_sales_program.id_jenis_sales_program');

    $this->db->join('ms_program_subcategory', 'ms_program_subcategory.id_subcategory = ms_jenis_sales_program.id_sub_category');

    $this->db->where('tr_claim_dealer.status', 'approved');

    $this->db->where('ms_program_subcategory.claim_to_ahm', '1');

    $this->db->where('tr_claim_dealer.id_program_md', $id);

    $this->db->where('tr_scan_barcode.no_mesin', $mesin);

    $data['vc'] = $this->db->get()->row_array();



    $this->db->select('no_ktp,tgl_ajukan_claim,ms_finance_company.id_finance_company,ms_finance_company.finance_company,jenis_beli,voucher_2,jenis,tipe_motor,tipe_ahm,ms_warna.id_warna,no_invoice,tgl_cetak_invoice,tgl_bastk,ms_warna.warna as msw, tr_claim_sales_program_detail.is_irregular_case as ir');

    $this->db->from('tr_sales_order');

    $this->db->join('tr_spk', 'tr_spk.no_spk = tr_sales_order.no_spk');

    $this->db->join('tr_claim_dealer', 'tr_claim_dealer.id_sales_order = tr_sales_order.id_sales_order');

    $this->db->join('tr_scan_barcode', 'tr_scan_barcode.no_mesin = tr_sales_order.no_mesin');

    $this->db->join('ms_tipe_kendaraan', 'tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan');

    $this->db->join('ms_warna', 'ms_warna.id_warna=tr_scan_barcode.warna');

    $this->db->join('tr_sales_program', 'tr_sales_program.id_program_md = tr_claim_dealer.id_program_md');

    $this->db->join('ms_jenis_sales_program', 'ms_jenis_sales_program.id_jenis_sales_program = tr_sales_program.id_jenis_sales_program');

    $this->db->join('ms_program_subcategory', 'ms_program_subcategory.id_subcategory = ms_jenis_sales_program.id_sub_category');

    $this->db->join('ms_finance_company', 'tr_spk.id_finance_company=ms_finance_company.id_finance_company', 'left');

    $this->db->join('tr_claim_sales_program', 'tr_claim_sales_program.id_program_md = tr_claim_dealer.id_program_md');

    $this->db->join('tr_claim_sales_program_detail', 'tr_claim_sales_program_detail.id_claim_sp = tr_claim_sales_program.id_claim_sp');

    $this->db->where('tr_claim_dealer.id_program_md', $id);

    $this->db->where('tr_scan_barcode.no_mesin', $mesin);

    $data['tb2'] = $this->db->get()->row_array();





    $this->template($data);

  }







  public function addfield()

  {



    $data['isi']    = $this->page;

    $data['title']  = $this->title;

    $data['set']    = "addfield";

    $data['query'] = $this->cmc->get_datamemo2();

    $this->template($data);

  }



  public function show_memo()

  {

    $list = $this->cmc->get_datamemo();

    $data = array();

    $no = $this->input->post('start');

    foreach ($list as $field) {

      $no++;

      $row = array();







      $row[] = "<input type=\"checkbox\" class=\"centang\" value=\"$field->idclaim\" name\"centang[]\">";

      $row[] = $no;

      $row[] = $field->yr;

      $row[] = $field->prd;

      $row[] = $field->asm;

      $row[] = $field->idpm;

      $row[] = $field->judul_kegiatan;
      $row[] = $field->no_rangka;

      $row[] = $field->nms;

      $row[] = $field->kode_dealer_md;

      $row[] = $field->nama_dealer;

      $row[] = $field->tipe_motor;

      $row[] = $field->tipe_ahm;

      $row[] = $field->memo;

      $row[] = $field->alasan;





      $data[] = $row;

    }

    $output = array(

      "draw" => $this->input->post('draw'),

      "recordsTotal" => $this->cmc->count_all2(),

      "recordsFiltered" => $this->cmc->count_filtered2(),

      "data" => $data,

    );



    echo json_encode($output);

  }

  public function updmemo1()

  {

    //$this->load->model('m_dgi_api');

    $claim = $this->input->get('claim');

    $reason = $this->input->get('reasonmemo');

    $memo2 = $this->input->get('memo2');



    $jmldata = count($claim);

    //var_dump($jmldata . $centang . $reason . $memo2);

    //$query = $this->cmc->get_update_memo($claim, $jmldata, $reason, $memo2);

    for ($i = 0; $i < $jmldata; $i++) {

      $this->db->where('id_claim_dealer', $claim[$i]);

      $this->db->set('alasan', $reason);

      $this->db->set('memo', $memo2);

      $this->db->update('tr_claim_sales_program_detail');

    }

    $this->session->set_flashdata('messege', 'Data Berhasil Di Ubah');

    redirect(base_url('h1/claim_program_ahm/addfield'));

  }



  public function export($memo, $alasan)

  {





    // $alasan1 = preg_replace("/[^a-zA-Z0-9]/", "", $alasan;

    $alasan1 = rawurldecode($alasan);

    $memo1 = rawurldecode($memo);

    include APPPATH . 'third_party/PHPExcel/PHPExcel.php';



    $excel = new PHPExcel();

    $row = 1;

    $excel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'No Memo');

    $excel->setActiveSheetIndex(0)->setCellValue('B' . $row, $memo1);

    $row = 2;

    $excel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Alasan');

    $excel->setActiveSheetIndex(0)->setCellValue('B' . $row, $alasan1);

    $row = 4;

    $header = [

      'A' . $row => 'Kode Main Dealer',

      'B' . $row => 'Kode Dealer',

      'C' . $row => 'Nama Dealer',

      'D' . $row => 'Sales Program ID AHM',

      'E' . $row => 'Sales Program ID MD',

      'F' . $row => 'Tahun Program',

      'G' . $row => 'Bulan Progran',

      'H' . $row => 'Total Diskon',

      'I' . $row => 'No. Faktur Penjualan Dealer',

      'J' . $row => 'Tanggal Faktur',

      'K' . $row => 'No. PO Finance Company',

      'L' . $row => 'Tanggal PO Finance Company',

      'M' . $row => 'No. Rangka',

      'N' . $row => 'No. Mesin',

      'O' . $row => 'Kode Tipe Motor',

      'P' . $row => 'Nama Tipe Motor',

      'Q' . $row => 'Kode Warna',

      'R' . $row => 'Deskripsi Warna',

      'S' . $row => 'Tanggal Faktur STNK',

      'T' . $row => 'Cash / Credit',

      'U' . $row => 'ID FinCoy',

      'V' . $row => 'Finance Company',

      'W' . $row => 'Nama Dealer TA',

      'X' . $row => 'Tanggal BAST Unit',

      'Y' . $row => 'No. KTP',

      'Z' . $row => 'Nama Customer',

      'AA' . $row => 'Alamat',

      'AB' . $row => 'Kota',

      'AC' . $row => 'Tanggal Entry Claim',

      'AD' . $row => 'Status MD',

      'AE' . $row => 'Tgl Verifikasi MD',

      'AF' . $row => 'Alasan Reject',

      'AG' . $row => 'Tgl MD Submit AHM',

      'AH' . $row => 'status Verifikasi AHM',

      'AI' . $row => 'Tgl Verifikasi AHM',

      'AJ' . $row => 'Alasan Verifikasi AHM',

    ];



    foreach ($header as $key => $hd) {

      $excel->getActiveSheet()->getStyle($key)->applyFromArray([

        'borders' => array(

          'allborders' => array(

            'style' => PHPExcel_Style_Border::BORDER_THIN,

          )

        ),

      ]);

      $excel->setActiveSheetIndex(0)->setCellValue($key, $hd);

    }

    $excel->getActiveSheet()->getStyle("A4:AJ4")->getFont()->setBold(true);



    $datas = $this->cmc->get_export_array($memo1, $alasan1);



    $row = $row + 1;

    $kode_md = 'E20';

    $no = 1;

    $row_first = $row;

    foreach ($datas as $pet) {

      $excel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'E20');

      $excel->setActiveSheetIndex(0)->setCellValue('B' . $row, $pet['kode_dealer_md']);

      $excel->setActiveSheetIndex(0)->setCellValue('C' . $row, $pet['nama_dealer']);

      $excel->setActiveSheetIndex(0)->setCellValue('D' . $row, $pet['id_program_ahm']);

      $excel->setActiveSheetIndex(0)->setCellValue('E' . $row, $pet['id_program_md']);

      $excel->setActiveSheetIndex(0)->setCellValue('F' . $row, $pet['yr']);

      $excel->setActiveSheetIndex(0)->setCellValue('G' . $row, $pet['prd']);

      $excel->setActiveSheetIndex(0)->setCellValue('H' . $row, $pet['total_diskon']);

      $excel->setActiveSheetIndex(0)->setCellValue('I' . $row, $pet['no_invoice']);

      $excel->setActiveSheetIndex(0)->setCellValue('J' . $row, $pet['tgl_cetak_invoice']);

      $excel->setActiveSheetIndex(0)->setCellValue('K' . $row, $pet['no_po_leasing']);

      $excel->setActiveSheetIndex(0)->setCellValue('L' . $row, $pet['tglpo']);

      $excel->setActiveSheetIndex(0)->setCellValue('M' . $row, "MH1" . $pet['no_rangka']);

      $excel->setActiveSheetIndex(0)->setCellValue('N' . $row, $pet['nms']);

      $excel->setActiveSheetIndex(0)->setCellValue('O' . $row, $pet['tipe_motor']);

      $excel->setActiveSheetIndex(0)->setCellValue('P' . $row, $pet['tipe_ahm']);

      $excel->setActiveSheetIndex(0)->setCellValue('Q' . $row, $pet['id_warna']);

      $excel->setActiveSheetIndex(0)->setCellValue('R' . $row, $pet['wr']);

      $excel->setActiveSheetIndex(0)->setCellValue('S' . $row, '');

      $excel->setActiveSheetIndex(0)->setCellValue('T' . $row, $pet['jenis_beli']);

      $excel->setActiveSheetIndex(0)->setCellValue('U' . $row, $pet['id_finance_company']);

      $excel->setActiveSheetIndex(0)->setCellValue('V' . $row, $pet['finance_company']);

      $excel->setActiveSheetIndex(0)->setCellValue('W' . $row, '');

      $excel->setActiveSheetIndex(0)->setCellValue('X' . $row, $pet['tgl_bastk']);

      $excel->setActiveSheetIndex(0)->setCellValue('Y' . $row, "'" . $pet['no_ktp']);

      $excel->setActiveSheetIndex(0)->setCellValue('Z' . $row, $pet['nama_konsumen']);

      $excel->setActiveSheetIndex(0)->setCellValue('AA' . $row, $pet['alamat']);

      $excel->setActiveSheetIndex(0)->setCellValue('AB' . $row, $pet['kabupaten']);

      $excel->setActiveSheetIndex(0)->setCellValue('AC' . $row, $pet['ctr']);

      $excel->setActiveSheetIndex(0)->setCellValue('AD' . $row, $pet['stss']);

      $excel->setActiveSheetIndex(0)->setCellValue('AF' . $row, $pet['tgl_approve_reject_md']);

      $excel->setActiveSheetIndex(0)->setCellValue('AG' . $row, $pet['alasan_reject']);

      $excel->setActiveSheetIndex(0)->setCellValue('AH' . $row, '-');

      $excel->setActiveSheetIndex(0)->setCellValue('AI' . $row, '-');

      $excel->setActiveSheetIndex(0)->setCellValue('AJ' . $row, '-');





      $no++;

      $row++;

    }

    $row_last = $row - 1;

    $excel->getActiveSheet()->getStyle('A' . $row_first . ':AJ' . $row_last)->applyFromArray([

      'borders' => array(

        'allborders' => array(

          'style' => PHPExcel_Style_Border::BORDER_THIN,

        )

      ),

    ]);

    $excel->getActiveSheet()->getColumnDimension('A')->setWidth(24);

    $excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);

    $excel->getActiveSheet()->getColumnDimension('C')->setWidth(36);

    $excel->getActiveSheet()->getColumnDimension('D')->setWidth(24);

    $excel->getActiveSheet()->getColumnDimension('E')->setWidth(28);

    $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);

    $excel->getActiveSheet()->getColumnDimension('G')->setWidth(28);

    $excel->getActiveSheet()->getColumnDimension('H')->setWidth(24);

    $excel->getActiveSheet()->getColumnDimension('I')->setWidth(28);

    $excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

    $excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);

    $excel->getActiveSheet()->getColumnDimension('L')->setWidth(23);

    $excel->getActiveSheet()->getColumnDimension('M')->setWidth(23);

    $excel->getActiveSheet()->getColumnDimension('N')->setWidth(14);

    $excel->getActiveSheet()->getColumnDimension('O')->setWidth(23);

    $excel->getActiveSheet()->getColumnDimension('P')->setWidth(25);

    $excel->getActiveSheet()->getColumnDimension('Q')->setWidth(13);

    $excel->getActiveSheet()->getColumnDimension('R')->setWidth(13);

    $excel->getActiveSheet()->getColumnDimension('S')->setWidth(25);

    $excel->getActiveSheet()->getColumnDimension('T')->setWidth(25);

    $excel->getActiveSheet()->getColumnDimension('U')->setWidth(20);

    $excel->getActiveSheet()->getColumnDimension('V')->setWidth(24);

    $excel->getActiveSheet()->getColumnDimension('W')->setWidth(34);

    $excel->getActiveSheet()->getColumnDimension('X')->setWidth(24);

    $excel->getActiveSheet()->getColumnDimension('Y')->setWidth(24);

    $excel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);

    $excel->getActiveSheet()->getColumnDimension('AA')->setWidth(20);

    $excel->getActiveSheet()->getColumnDimension('AB')->setWidth(28);

    $excel->getActiveSheet()->getColumnDimension('AC')->setWidth(20);

    $excel->getActiveSheet()->getColumnDimension('AD')->setWidth(18);

    $excel->getActiveSheet()->getColumnDimension('AE')->setWidth(28);

    $excel->getActiveSheet()->getColumnDimension('AF')->setWidth(28);

    $excel->getActiveSheet()->getColumnDimension('AG')->setWidth(28);

    $excel->getActiveSheet()->getColumnDimension('Ah')->setWidth(28);

    $excel->getActiveSheet()->getColumnDimension('AI')->setWidth(28);

    $excel->getActiveSheet()->getColumnDimension('AJ')->setWidth(28);



    $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

    $excel->getActiveSheet(0)->setTitle("REPORT CLAIM AHM");

    $excel->setActiveSheetIndex(0);



    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    $nama_file = 'Report_Monitoring_claim-AHM-' . strtotime(get_ymd());

    header('Content-Disposition: attachment; filename="' . $nama_file . '.xlsx"'); // Set nama file excel nya

    header('Cache-Control: max-age=0');

    $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

    $write->save('php://output');

  }

  

  // public function api_claim2($idclaim)

  // {

  //   $this->order_new($idclaim); // ganti ke function ini



    // $this->db->where('id_claim_dealer', $idclaim);

    // $this->db->set('send_ahm', 1);

    // $this->db->set('date_send_to_ahm', date('Y-m-d H:i:s'));

    // $this->db->update('tr_claim_sales_program_detail');

    // $this->session->set_flashdata('messege', 'Data berhasil di claim'); // beda format session (perlu diganti)

    // redirect('h1/claim_program_ahm');

  // }



  // public function order_new()

	// {

    

  //   $token =  get_token_astra();

	// 	$url = 'https://portaldev.ahm.co.id/jx06/ahmfascp000-pst/rest/fa/scp002/md-post-claim';



  //   /*

  //   [

  //     {

  //         "progYear": 2022,

  //         "progMonth": 3,

  //         "juklakNo": "UAT/ATSC/0314/004",

  //         "frameNo": "MH1JF1314AK160959",

  //         "engineNo": "JF13E0157719",

  //         "mdApproveDate": "2022-02-10 09:40:10",

  //         "dealerCode": "06111",

  //         "motorType": "CC2",

  //         "discountValue": 2300000,

  //         "invoiceNo": "inv-no-xxx",

  //         "invoiceDate": "2022-03-02",

  //         "bastDate": "2022-02-02",

  //         "nik": "9876568271123456",

  //         "dealerSubmitDate": "2022-02-12 09:40:10"

  //     }

  //   ]

  //   */

    

  //   // perlu utk foreach data jika claim lebih dari 1

  //   $object = new stdclass();

  //   $object->progYear = 2022;

  //   $object->progMonth = 3;

  //   $object->juklakNo = "UAT/ATSC/0314/004";

  //   $object->frameNo = "MH1JF22109K021513";

  //   $object->engineNo = "JF22E1022758";

  //   $object->dealerCode = "07628";

  //   $object->mdApproveDate = "2022-02-10 09:40:10";

  //   $object->motorType = "CM1";

  //   $object->discountValue = 2300000;

  //   $object->invoiceNo = "inv-no-xxx";

  //   $object->invoiceDate = "2022-03-02";

  //   $object->bastDate = "2022-02-02";

  //   $object->nik = "9876568271123456";

  //   $object->dealerSubmitDate = "2022-02-12 09:40:10";



  //   $data[] = $object; 

    

  //   $hasil = api_auto_claim($token['jxid'], $token['txid'], $url, $data); // api 2 : pengajuan claim md to ahm

	// 	$hasil = json_decode($hasil,true);



  //   if($hasil['status'] == '1'){

  //     $get_data_claim = $this->db->query("select id, engineNo, juklakNo from tr_pengajuan_claim_to_ahm where engineNo='$object->engineNo' and juklakNo='$object->juklakNo'");



  //     if($get_data_claim->num_rows()> 0 ){

  //       // sudah pernah ajukan klaim

  //       $id = $get_data_claim->row()->id;

  //       $this->db->update('tr_pengajuan_claim_to_ahm', array('updatedDate'=>  date('Y-m-d H:i:s') ), "id='$id'");

  //     }else{

  //       $this->db->insert('tr_pengajuan_claim_to_ahm', array(

  //         'juklakNo' => $object->juklakNo,

  //         'engineNo' => $object->engineNo,

  //         'frameNo' => $object->frameNo,

  //         'dealerCode' => $object->dealerCode,

  //         'nik'=>$object->nik,

  //         'motorType'=>$object->motorType,

  //         'discountValue'=>$object->discountValue,

  //         'invoiceNo'=>$object->invoiceNo,

  //         'invoiceDate' =>$object->invoiceDate, 

  //         'statusVerifikasi1' => 1,

  //         'bastDate'=>$object->bastDate,

  //         'dealerSubmitDate' => $object->dealerSubmitDate,

  //         'mdApproveDate' => $object->mdApproveDate,

  //         'createdDate' => date('Y-m-d H:i:s')

  //       ));

  //     }

  //     $_SESSION['pesan'] 	= $hasil['message']['notes'];

  //     $_SESSION['tipe'] 	= "success";

  //     echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/claim_sales_program'>";

  //     exit();

  //   }else{

  //     if(count($hasil['data'])>0){

  //       foreach($hasil['data'] as $row){

  //         $msg = implode("; ",$row['errorMessage']);

  //         $juklak_no = $row['juklakNo'];

  //         $noMesin = $row['engineNo'];

  //         $status_verifikasi1 = '0';

  //         // cara utk update batch?

  //         $this->db->update('tr_pengajuan_claim_to_ahm', array('updatedDate'=>  date('Y-m-d H:i:s'), 'statusVerifikasi1'=>$status_verifikasi1, 'errorMessage' => $msg ), "engineNo = '$noMesin' and juklakNo = '$juklak_no'");

  //       }

  //       $_SESSION['pesan'] 	= $hasil['message']['notes'];

  //       $_SESSION['tipe'] 	= "warning";

  //     }  



  //     echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/claim_sales_program'>";

  //     exit();

  //   }

	// }



}

