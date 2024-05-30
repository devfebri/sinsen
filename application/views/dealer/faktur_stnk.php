<?php 

function bln(){

  $bulan=$bl=$month=date("m");

  switch($bulan)

  {

    case"1":$bulan="Januari"; break;

    case"2":$bulan="Februari"; break;

    case"3":$bulan="Maret"; break;

    case"4":$bulan="April"; break;

    case"5":$bulan="Mei"; break;

    case"6":$bulan="Juni"; break;

    case"7":$bulan="Juli"; break;

    case"8":$bulan="Agustus"; break;

    case"9":$bulan="September"; break;

    case"10":$bulan="Oktober"; break;

    case"11":$bulan="November"; break;

    case"12":$bulan="Desember"; break;

  }

  $bln = $bulan;

  return $bln;

}

?>

<style type="text/css">

.myTable1{

  margin-bottom: 0px;

}

.myt{

  margin-top: 0px;

}

.isi{

  height: 25px;

  padding-left: 4px;

  padding-right: 4px;  

}

</style>

<base href="<?php echo base_url(); ?>" />

<body onload="auto()">

<div class="content-wrapper">

<!-- Content Header (Page header) -->

<section class="content-header">

  <h1>

    <?php echo $title; ?>    

  </h1>

  <ol class="breadcrumb">

    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    

    <li class="">Customer</li>

    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>

  </ol>

  </section>

  <section class="content">

    <?php 

    if($set=="insert"){

    ?>



    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="dealer/faktur_stnk">

            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

          </a>

        </h3>

        <div class="box-tools pull-right">

          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

        </div>

      </div><!-- /.box-header -->

      <div class="box-body">

        <?php                       

        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    

        ?>                  

        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">

            <strong><?php echo $_SESSION['pesan'] ?></strong>

            <button class="close" data-dismiss="alert">

                <span aria-hidden="true">&times;</span>

                <span class="sr-only">Close</span>  

            </button>

        </div>

        <?php

        }

            $_SESSION['pesan'] = '';                        

                

        ?>

        <div id="row">

          <div class="col-md-12">

            <form class="form-horizontal" action="dealer/faktur_stnk/save" method="post" enctype="multipart/form-data">

              <div class="box-body">                              

                <div class="form-group">

                  <input type="hidden" name="mode" value="input">

                  <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>

                  <div class="col-sm-3">

                    <input type="text" required class="form-control"  id="tanggal"  placeholder="Start Date" name="start_date" autocomplete="off">

                  </div>


                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>

                  <div class="col-sm-3">

                    <input type="text" required class="form-control" id="tanggal2"  placeholder="End Date" name="end_date" autocomplete="off">                    

                  </div>
                  <div class="col-sm-3">
                    <button class="btn btn-primary btn-flat" type="button" onclick="kirim_data_stnk()"><i class="fa fa-refresh"></i> Generate</button>
		    <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="right" title="Jika data tidak muncul, maka dibuatkan terlebih dahulu data generate list unit deliverynya sampai proses diterima konsumen di menu Konfirmasi BASTK"><i class="fa fa-info-circle" aria-hidden="true"></i></button>        
                  </div>
                </div>                

                <div class="form-group">

                  <button class="btn btn-primary btn-block btn-flat" disabled>Kelengkapan Dokumen</button>                      

                  <span id="tampil_stnk"></span>   

                </div>  

              </div>

          </div>

        </div>        

      </div><!-- /.box-body -->

      <div class="box-footer">

        <div class="col-sm-2">

        </div>

        <div class="col-sm-10">

          <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
          <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
	</div>

      </div><!-- /.box-footer -->

    </div><!-- /.box -->

  </form>



    <?php 

    }elseif($set=='detail'){

      $row = $dt_faktur->row();

    ?>

    

    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="dealer/faktur_stnk">

            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

          </a>

        </h3>

        <div class="box-tools pull-right">

          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

        </div>

      </div><!-- /.box-header -->

      <div class="box-body">

        <?php                       

        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    

        ?>                  

        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">

            <strong><?php echo $_SESSION['pesan'] ?></strong>

            <button class="close" data-dismiss="alert">

                <span aria-hidden="true">&times;</span>

                <span class="sr-only">Close</span>  

            </button>

        </div>

        <?php

        }

            $_SESSION['pesan'] = '';                        

                

        ?>

        <div id="row">

          <div class="col-md-12">            

            <form class="form-horizontal" enctype="multipart/form-data">

              <div class="box-body">                              

                <div class="form-group">

                  <input type="hidden" name="no_bastd" id="no_bastd" value="<?php echo $row->no_bastd ?>">

                  <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>

                  <div class="col-sm-3">

                    <input type="text" readonly class="form-control" value="<?php echo $row->start_date ?>"  placeholder="Start Date" name="start_date">

                  </div>                                  

                  <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>

                  <div class="col-sm-3">

                    <input type="text" readonly class="form-control" value="<?php echo $row->end_date ?>" placeholder="End Date" name="end_date">                    

                  </div>

                </div>  

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Cetak</label>

                  <div class="col-sm-3">

                    <input type="text" readonly class="form-control" value="<?php echo $row->tgl_cetak ?>"  placeholder="Start Date" name="start_date">

                  </div>                                

                  </div>

                </div>                                

                <div class="form-group">                  

                  <table id="example4" class="table myTable1 table-bordered table-hover">

                    <thead>

                      <tr>      

                        <th>No Mesin</th>              

                        <th>No Rangka</th>              

                        <th>Nama Konsumen</th>

                        <th>Alamat</th>

                        <th>Biaya BBN</th>

                      <?php /* ?>  <th>Harga Unit</th> <?php */ ?>

                        <th>Fotocopy KTP (5)</th>

                        <th>Cek Fisik Kendaraan (2)</th>

                        <th>Hasil Cek Fisik STNK (1)</th>

                        <th>Formulir Data BPKB (1)</th>

                        <th>Surat Kuasa (2)</th>

                        <th>CKD STNK & BPKB (2)</th>

                        <th>Form Permohonan STNK (1)</th>                          

                      </tr>

                    </thead>

                   

                    <tbody>                    

                      <?php   

                      $no = 1;

                      foreach($dt_stnk->result() as $row) {                                                   

                        if($row->ktp == 'ya') $ktp = "checked";      

                          else $ktp = "";        

                        if($row->fisik == 'ya') $fisik = "checked";      

                          else $fisik = "";        

                        if($row->stnk == 'ya') $stnk = "checked";      

                          else $stnk = "";        

                        if($row->bpkb == 'ya') $bpkb = "checked";      

                          else $bpkb = "";        

                        if($row->kuasa == 'ya') $kuasa = "checked";      

                          else $kuasa = "";        

                        if($row->ckd == 'ya') $ckd = "checked";      

                          else $ckd = "";        

                        if($row->permohonan == 'ya') $pem = "checked";      

                          else $pem = "";             

                                    



                        $er = $this->db->query("SELECT * FROM tr_spk INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer

                            WHERE tr_spk.no_spk = '$row->no_spk'");

                        if($er->num_rows() > 0){

                          $ts = $er->row();

                          $nama_konsumen = $ts->nama_bpkb;

                          $alamat = $ts->alamat;

                        }else{

                          $nama_konsumen = "";

                          $alamat = "";

                        }

                        $re = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$row->no_mesin)->row();

                        $ra = $this->m_admin->getByID("tr_sales_order","no_spk",$row->no_spk)->row();

                        





                        echo "

                        <tr>             

                          <td>$row->no_mesin</td> 

                          <td>$row->no_rangka</td> 

                          <td>$row->nama_konsumen</td> 

                          <td>$row->alamat</td> 

                          <td>".number_format($row->biaya_bbn, 0, ',', '.')."</td> ";



                          //<td>".number_format($ra->harga_unit, 0, ',', '.')."</td> 

                          

                          echo "<td align='center'>                            

                            <input type='checkbox' name='check_ktp_$no' $ktp disabled>

                          </td>      

                          <td align='center'>

                            <input type='checkbox' name='check_fisik_$no' $fisik disabled>

                          </td>      

                          <td align='center'>

                            <input type='checkbox' name='check_stnk_$no' $stnk disabled>

                          </td>      

                          <td align='center'>

                            <input type='checkbox' name='check_bpkb_$no' $bpkb disabled>

                          </td>      

                          <td align='center'>

                            <input type='checkbox' name='check_kuasa_$no' $kuasa disabled>

                          </td>      

                          <td align='center'>

                            <input type='checkbox' name='check_ckd_$no' $ckd disabled>

                          </td>      

                          <td align='center'>

                            <input type='checkbox' name='check_permohonan_$no' $pem disabled>

                          </td>      

                        </tr>";

                        $no++;

                        }

                      ?>

                    </tbody>

                  </table>  

                </div>  

              </div>

          </div>

        </div>        

      </div><!-- /.box-body -->

      

   

    <?php

    }elseif($set=="view"){

    ?>



    <div class="box">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="dealer/faktur_stnk/add">

            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>

          </a> 
          <a href="dealer/faktur_stnk/status_nosin">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"view"); ?> class="btn bg-green btn-flat margin" disabled>Cek Status No Mesin</button>
          </a>          

          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  

        </h3>

        <div class="box-tools pull-right">

          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

        </div>

      </div><!-- /.box-header -->

      <div class="box-body">

        <?php                       

        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    

        ?>                  

        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">

            <strong><?php echo $_SESSION['pesan'] ?></strong>

            <button class="close" data-dismiss="alert">

                <span aria-hidden="true">&times;</span>

                <span class="sr-only">Close</span>  

            </button>

        </div>

        <?php

        }

            $_SESSION['pesan'] = '';                        

                

        ?>

        <table id="example2" class="table table-bordered table-hover">

          <thead>

            <tr>

              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              

              <th width="5%">No</th>              

              <th>No BASTD</th>              

              <th>Tanggal BASTD</th>              

              <th>Start Date</th>              

              <th>End Date</th>

              <th>Qty Pengajuan</th>    
	      <th>Status Pengajuan</th>              

              <th>Action</th>              

            </tr>

          </thead>

          <tbody>            

          <?php 

          $no=1; 

          foreach($dt_faktur->result() as $row) {     
            $edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
            $delete = $this->m_admin->set_tombol($id_menu,$group,'delete');            
            $approval = $this->m_admin->set_tombol($id_menu,$group,'approval');            
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');            

            $s = $this->db->query("SELECT COUNT(no_mesin) AS qty FROM tr_faktur_stnk_detail WHERE no_bastd = '$row->no_bastd'")->row();          

            if(isset($s->qty)){
              $jum = $s->qty;
            }else{
              $jum = "";
            }

            if($row->status_faktur !== 'rejected'){

              $tombol = "<a href='dealer/faktur_stnk/cetak?id=$row->no_bastd'>

                        <button $print class='btn btn-flat btn-xs btn-primary'><i class='fa fa-print'></i> Print BASTD</button>

                      </a>";

            }else{

              $tombol = "";
            }

	    if($row->status_faktur =='rejected'){
		$st="<span class='label label-danger'>$row->status_faktur</span>";
	    }else if($row->status_faktur =='approved'){
		$st="<span class='label label-success'>$row->status_faktur</span>";
	    }else {
		$st="<span class='label label-info'>$row->status_faktur</span>";
	    }

	
            echo "

            <tr>

              <td>$no</td>

              <td>

                <a href='dealer/faktur_stnk/detail?id=$row->no_bastd'>

                  $row->no_bastd

                </a>

              </td>              

              <td>$row->tgl_bastd</td>

              <td>$row->start_date</td>

              <td>$row->end_date</td>                            

              <td>$jum unit</td>    
              <td>$st</td>                                                             

              <td>$tombol</td>

            </tr>

            ";

          $no++;

          }

          ?>

                <!-- <a href='dealer/faktur_stnk/konfirmasi?id=$row->no_bastd'>

                  <button class='btn btn-flat btn-xs btn-success'><i class='fa fa-check'></i> Konfirmasi Penerimaan</button>

                </a> -->

          </tbody>

        </table>

      </div><!-- /.box-body -->

    </div><!-- /.box -->
    <?php

    }elseif($set=="status_nosin"){

    ?>
    <div class="box">

      <div class="box-header with-border">

        <h3 class="box-title">
          <a href="dealer/faktur_stnk">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"view"); ?> class="btn bg-maroon btn-flat margin"> View Data</button></button>
          </a>                          

        </h3>

        <div class="box-tools pull-right">

          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

        </div>

      </div><!-- /.box-header -->

      <div class="box-body">
        <table id="example2" class="table table-bordered table-hover">

          <thead>

            <tr>
              <th>No Mesin</th>
              <th>No Rangka</th>
              <th>Nama Customer</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>STNK</th>
              <th>Plat</th>
              <th>BPKB</th>
              <th>SRUT</th>
              <th>Status</th>
            </tr>

          </thead>

          <tbody>            
            <?php 
              $id_dealer     = $this->m_admin->cari_dealer();
            foreach ($nosin as $ns): 
              // $stnk = $this->db->query("SELECT no_stnk FROM tr_terima_bj WHERE no_mesin='$ns->no_mesin' AND no_stnk IS NOT NULL ORDER BY id_terima_bj DESC");
              // $stnk = $stnk->num_rows()>0?$stnk->row()->no_stnk:'';
              // $plat = $this->db->query("SELECT no_plat FROM tr_terima_bj WHERE no_mesin='$ns->no_mesin' AND no_plat IS NOT NULL ORDER BY id_terima_bj DESC");
              // $plat = $plat->num_rows()>0?$plat->row()->no_plat:'';
              // $bpkb = $this->db->query("SELECT no_bpkb FROM tr_terima_bj WHERE no_mesin='$ns->no_mesin' AND no_bpkb IS NOT NULL ORDER BY id_terima_bj DESC");
              // $bpkb = $bpkb->num_rows()>0?$bpkb->row()->no_bpkb:'';

              $stnk = $this->db->query("SELECT IFNULL(no_stnk,'') AS no_stnk FROM tr_penyerahan_stnk_detail AS tpsd
                 JOIN tr_penyerahan_stnk ON tpsd.no_serah_stnk=tr_penyerahan_stnk.no_serah_stnk
                 JOIN tr_terima_bj ON tpsd.no_mesin=tr_terima_bj.no_mesin AND no_stnk IS NOT NULL 
                 WHERE tpsd.no_mesin='$ns->no_mesin' AND tpsd.status_nosin='terima' ORDER BY id_terima_bj DESC");
              $stnk = $stnk->num_rows()>0?$stnk->row()->no_stnk:'';

              $bpkb = $this->db->query("SELECT IFNULL(no_bpkb,'') AS no_bpkb FROM tr_penyerahan_bpkb_detail AS tpsd
                 JOIN tr_penyerahan_bpkb ON tpsd.no_serah_bpkb=tr_penyerahan_bpkb.no_serah_bpkb
                 JOIN tr_terima_bj ON tpsd.no_mesin=tr_terima_bj.no_mesin AND no_bpkb IS NOT NULL 
                 WHERE tpsd.no_mesin='$ns->no_mesin' AND tpsd.status_nosin='terima' ORDER BY id_terima_bj DESC");
              $bpkb = $bpkb->num_rows()>0?$bpkb->row()->no_bpkb:'';
              
              $plat = $this->db->query("SELECT IFNULL(no_plat,'') AS no_plat FROM tr_penyerahan_plat_detail AS tpsd
                 JOIN tr_penyerahan_plat ON tpsd.no_serah_plat=tr_penyerahan_plat.no_serah_plat
                 JOIN tr_terima_bj ON tpsd.no_mesin=tr_terima_bj.no_mesin AND no_plat IS NOT NULL 
                 WHERE tpsd.no_mesin='$ns->no_mesin' AND tpsd.status_nosin='terima' ORDER BY id_terima_bj DESC");
              $plat = $plat->num_rows()>0?$plat->row()->no_plat:'';

              $stnk_terima = $this->db->query("SELECT * FROM tr_tandaterima_stnk_konsumen_detail AS tskd
                JOIN tr_tandaterima_stnk_konsumen ON tskd.kd_stnk_konsumen=tr_tandaterima_stnk_konsumen.kd_stnk_konsumen
                WHERE no_mesin='$ns->no_mesin' AND jenis_cetak='stnk' AND no_stnk IS NOT NULL");
              $stnk_terima = $stnk_terima->num_rows()>0?$stnk_terima->row()->no_stnk:'';

              $plat_terima = $this->db->query("SELECT * FROM tr_tandaterima_stnk_konsumen_detail AS tskd
                JOIN tr_tandaterima_stnk_konsumen ON tskd.kd_stnk_konsumen=tr_tandaterima_stnk_konsumen.kd_stnk_konsumen
                WHERE no_mesin='$ns->no_mesin' AND jenis_cetak='plat' AND no_plat IS NOT NULL");
              $plat_terima = $plat_terima->num_rows()>0?$plat_terima->row()->no_plat:'';

              $bpkb_terima = $this->db->query("SELECT * FROM tr_tandaterima_stnk_konsumen_detail AS tskd
                JOIN tr_tandaterima_stnk_konsumen ON tskd.kd_stnk_konsumen=tr_tandaterima_stnk_konsumen.kd_stnk_konsumen
                WHERE no_mesin='$ns->no_mesin' AND jenis_cetak='bpkb' AND no_bpkb IS NOT NULL");
              $bpkb_terima = $bpkb_terima->num_rows()>0?$bpkb_terima->row()->no_bpkb:'';

              $srut_terima = $this->db->query("SELECT * FROM tr_tandaterima_stnk_konsumen_detail AS tskd
                JOIN tr_tandaterima_stnk_konsumen ON tskd.kd_stnk_konsumen=tr_tandaterima_stnk_konsumen.kd_stnk_konsumen
                WHERE no_mesin='$ns->no_mesin' AND jenis_cetak='srut' AND no_srut IS NOT NULL");
              $srut_terima = $srut_terima->num_rows()>0?$srut_terima->row()->no_srut:'';

              $srut = $this->db->query("SELECT * FROM tr_terima_srut_detail AS psd
                JOIN tr_srut ON psd.no_mesin=tr_srut.no_mesin
                WHERE psd.no_mesin='$ns->no_mesin'");
              $srut = $srut->num_rows()>0?$srut->row()->no_srut:'';
              $status = "<span class='label label-warning'>Open</span>";
              $cek_faktur = $this->db->query("SELECT count(no_mesin) AS c FROM tr_faktur_stnk_detail WHERE no_mesin='$ns->no_mesin'")->row()->c;
              // $cek_selesai = $this->db->query("SELECT COUNT(no_mesin) AS c FROM tr_tandaterima_stnk_konsumen_detail WHERE no_mesin='$ns->no_mesin' AND no_stnk IS NOT NULL AND no_plat IS NOT NULL AND no_bpkb IS NOT NULL AND no_srut IS NOT NULL")->row()->c;
              if ($cek_faktur==0) {
                $status = "<span class='label label-warning'>Open</span>";
              }elseif ($stnk=='' || $plat=='' || $bpkb=='' || $srut=='') {
                $status = "<span class='label label-info'>Process</span>";
              }
              elseif ($stnk!='' && $plat!='' && $bpkb!='' && $srut!='') {
                // if ($cek_selesai>0) {
                if($plat_terima!='' && $stnk_terima!='' && $bpkb_terima!='' && $srut_terima!=''){
                  $status = "<span class='label label-success'>Complete</span>";
                }else{
                  $status = "<span class='label label-primary'>Accepted</span>";
                }
              }else{
                $status='';
              }
            ?>
              <tr>
                <td><?= $ns->no_mesin ?></td>
                <td><?= $ns->no_rangka ?></td>
                <td><?= $ns->nama_konsumen ?></td>
                <td><?= $ns->id_tipe_kendaraan.' | '.$ns->tipe_ahm ?></td>
                <td><?= $ns->id_warna.' | '.$ns->warna ?></td>
                <td><?= $stnk ?></td>
                <td><?= $plat ?></td>
                <td><?= $bpkb ?></td>
                <td><?= $srut ?></td>
                <td><?= $status ?></td>
              </tr>
            <?php endforeach ?>
          </tbody>

        </table>

      </div><!-- /.box-body -->

    </div><!-- /.box -->

    <?php

    }

    ?>

  </section>

</div>







<script type="text/javascript">



function kirim_data_stnk(){    

  $("#tampil_stnk").show();

  var start_date  = document.getElementById("tanggal").value;   

  var end_date    = document.getElementById("tanggal2").value;   

  var xhr;

  if (window.XMLHttpRequest) { // Mozilla, Safari, ...

    xhr = new XMLHttpRequest();

  }else if (window.ActiveXObject) { // IE 8 and older

    xhr = new ActiveXObject("Microsoft.XMLHTTP");

  } 

   //var data = "birthday1="+birthday1_js;          

    var data = "start_date="+start_date+"&end_date="+end_date;                           

     xhr.open("POST", "dealer/faktur_stnk/t_stnk", true); 

     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  

     xhr.send(data);

     xhr.onreadystatechange = display_data;

     function display_data() {

        if (xhr.readyState == 4) {

            if (xhr.status == 200) {       

                document.getElementById("tampil_stnk").innerHTML = xhr.responseText;

            }else{

                alert('There was a problem with the request.');

            }

        }

    }   

}



</script>