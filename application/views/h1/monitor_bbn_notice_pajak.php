<style type="text/css">

.myTable1{

  margin-bottom: 0px;

}

.myt{

  margin-top: 0px;

}

.isi{

  height: 30px;  

  padding-left: 5px;

  padding-right: 5px;  

  margin-right: 0px; 

}

.isi_combo{   

  height: 30px;

  border:1px solid #ccc;

  padding-left:1.5px;

}

</style>

<base href="<?php echo base_url(); ?>" />

<body onload="mulai()">

<div class="content-wrapper">

<!-- Content Header (Page header) -->

<section class="content-header">

  <h1>

    <?php echo $title; ?>    

  </h1>

   <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Finance</li>
    <li class="">Invoice Terima</li>
    <li class="">Tagihan Samsat</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$page)); ?></li>
  </ol>
  </section>

  <section class="content">

    

    <?php 

    if($set=="insert"){      

    ?>



    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="h1/monitor_bbn_notice_pajak">

            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>

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

        <div class="row">

          <div class="col-md-12">            

            <form class="form-horizontal" action="h1/monitor_bbn_notice_pajak/save" method="post" enctype="multipart/form-data">              

              <div class="box-body">       

                <br>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Faktur (Awal)</label>                  

                  <div class="col-sm-4">

                    <input autocomplete='off' type="text" id="tanggal" name="tgl_awal" placeholder="Tgl Faktur (Awal)" class="form-control">

                  </div>                                                

                </div>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Faktur (Akhir)</label>                  

                  <div class="col-sm-4">

                    <input autocomplete='off' type="text" id="tanggal1" name="tgl_akhir" placeholder="Tgl Faktur (Akhir)" class="form-control">

                  </div>                              

                  <div class="col-sm-2">

                    <button type="button" onclick="generate()" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Generate</button>

                  </div>  

                </div>

                <div class="form-group">                  

                  <span id="tampil_data"></span>

                </div>                

              </div><!-- /.box-body -->

              <div class="box-footer">

                <div class="col-sm-2">

                </div>

                <div class="col-sm-10">                  

                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>

                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  

                </div>

              </div><!-- /.box-footer -->

            </form>

          </div>

        </div>

      </div>

    </div><!-- /.box -->





    <?php

    }elseif($set=="view"){

    ?>



    <div class="box">

      <div class="box-header with-border">

        <h3 class="box-title">

<?php /* ?>
          <a href="h1/monitor_bbn_notice_pajak/add">            

            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>

          </a>          

                    <?php */ ?>


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

              <th width="5%">No</th>                          

              <th width="12%">No Invoice</th>

              <th>Tgl Invoice</th>

              <th>Jumlah Unit</th>

              <th>Amount Notice Pajak</th>
              <th>Amount BBN MD - BJ</th>   
              <th>Selisih</th>              

              <th width="10%">Action</th>              

            </tr>

          </thead>

          <tbody>            

          <?php 

          $no=1; 

          foreach($dt_bbn->result() as $row){                                         

            // $item = $this->db->query("SELECT COUNT(no_mesin) as jum FROM tr_pengajuan_bbn_detail WHERE id_generate = '$row->id_generate'")->row();

            // if(isset($row->no_tanda_terima)){
            $detail = $this->db->query("
            SELECT sum(ms_bbn_biro.biaya_bbn) as biaya_bbn_biro FROM tr_proses_bbn_detail
INNER JOIN tr_pengajuan_bbn_detail ON tr_proses_bbn_detail.no_mesin= tr_pengajuan_bbn_detail.no_mesin 
INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
inner join ms_bbn_biro on ms_tipe_kendaraan.id_tipe_kendaraan = ms_bbn_biro.id_tipe_kendaraan 
AND ms_bbn_biro.tahun_produksi=tr_pengajuan_bbn_detail.tahun
INNER JOIN ms_warna ON tr_pengajuan_bbn_detail.id_warna = ms_warna.id_warna WHERE tr_proses_bbn_detail.no_invoice_bbn = '$row->no_invoice_bbn'")->row()->biaya_bbn_biro;    

            // $detail = $this->db->query("

            //   SELECT sum(ms_bbn_biro.biaya_bbn) as biaya_bbn_biro FROM tr_proses_bbn_detail INNER JOIN tr_pengajuan_bbn_detail ON tr_proses_bbn_detail.no_mesin= tr_pengajuan_bbn_detail.no_mesin INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan inner join ms_bbn_biro on ms_tipe_kendaraan.id_tipe_kendaraan = ms_bbn_biro.id_tipe_kendaraan INNER JOIN ms_warna ON tr_pengajuan_bbn_detail.id_warna = ms_warna.id_warna WHERE tr_proses_bbn_detail.no_invoice_bbn = '$row->no_invoice_bbn'  AND ms_bbn_biro.tahun_produksi = (SELECT tahun_produksi FROM tr_fkb WHERE no_mesin_spasi = tr_proses_bbn_detail.no_mesin) ")->row()->biaya_bbn_biro;    

            // }else{

            //   $tom = "";

            // } 
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');
            if ($row->amount != $detail) {
              $warna = "#ea8f8f";
              $tom = "<a $print href='h1/monitor_bbn_notice_pajak/cetak?id=$row->no_invoice_bbn' class='btn btn-primary btn-flat btn-sm'><i class='fa fa-print'></i></a>";
            }else
            {
              $tom='';
              $warna='';
            }
            $selisih = $detail-$row->amount;
            echo "          

            <tr style='background: $warna;'>

              <td>$no</td>

              <td><a href='h1/monitor_bbn_notice_pajak/detail?id=$row->no_invoice_bbn'>$row->no_invoice_bbn</a></td>                           

              <td>$row->tgl_invoice</td>                           

              <td>$row->jumlah_unit</td>                           

              <td>".mata_uang2($row->amount)."</td> 
              <td>".mata_uang2($detail)."</td>                                        
              <td>".mata_uang2($selisih)."</td>                                        

              <td align='center'>";

              echo $tom;

              echo "

              </td>";                                      

          $no++;

          }

          ?>

          </tbody>

        </table>

      </div><!-- /.box-body -->

    </div><!-- /.box -->

 <?php 
}
    if($set=="detail"){      

    ?>



    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="h1/monitor_bbn_notice_pajak">

            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>

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


           <?php /* ?>     <br>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Faktur (Awal)</label>                  

                  <div class="col-sm-4">

                    <input autocomplete='off' type="text" id="tanggal" name="tgl_awal" placeholder="Tgl Faktur (Awal)" class="form-control">

                  </div>                                                

                </div>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Faktur (Akhir)</label>                  

                  <div class="col-sm-4">

                    <input autocomplete='off' type="text" id="tanggal1" name="tgl_akhir" placeholder="Tgl Faktur (Akhir)" class="form-control">

                  </div>                              

                  <div class="col-sm-2">

                    <button type="button" onclick="generate()" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Generate</button>

                  </div>  

                </div>

                <div class="form-group">    <?php */ ?>              

                    <table id="example2" class="table myTable1 table-bordered table-hover">
                      <thead>
                        <tr>      
                          <th>Tgl Faktur</th>              
                          <th>No Mesin</th>              
                          <th>No Rangka</th>              
                          <th>Nama Konsumen</th>
                          <th>Tipe</th>                        
                          <th>Warna</th>                        
                          <th>Tahun Produksi</th>                        
                          <th>Biaya BBN MD - BJ</th>                        
                          <th>Notice Pajak</th>                           
                          <th>Selisih</th>
                        </tr>
                      </thead>
                     
                      <tbody>                    
                        <?php   
                        $no = 1;
                        foreach($dt_detail->result() as $isi) {
                          $selisih = $isi->biaya_bbn_biro-$isi->notice_pajak;
                            echo "
                            <tr>
                              <td>$isi->tgl_jual</td>
                              <td>$isi->no_mesin</td>
                              <td>$isi->no_rangka</td>
                              <td>$isi->nama_konsumen</td>
                              <td>$isi->deskripsi_ahm</td>
                              <td>$isi->warna</td>
                              <td>$isi->tahun</td>
                              <td>".mata_uang2($isi->biaya_bbn_biro)."</td>
                              <td>".mata_uang2($isi->notice_pajak)."</td>
                              <td>".mata_uang2($selisih)."</td>
                            </tr>
                            ";
                          $no++;
                          }
                        ?>
                      </tbody>
                    </table>     
              </div><!-- /.box-body -->

           </form>

          </div>

        </div>

      </div>

    </div><!-- /.box -->






    <?php

    }

    ?>

  </section>

</div>

<script type="text/javascript">

function mulai(){

  for (var i = 1; i <= 1000; i++) {   

    $("#notice_pajak_"+i+"").hide();    

  }

}

function cek_form(){ 

  for (var i = 1; i <= 1000; i++) {

    if (document.getElementById("cek_form_"+i+"").checked == true){

      $("#notice_pajak__"+i+"").show();

      $("#notice_pajak__"+i+"").val('');

      $("#notice_pajak__"+i+"").focus();

    }else{

      $("#notice_pajak__"+i+"").hide();

    }    

  }  

}

function generate(){    

  $("#tampil_data").show();

  var start_date  = document.getElementById("tanggal").value;   

  var end_date    = document.getElementById("tanggal1").value;   

  var xhr;

  if (window.XMLHttpRequest) { // Mozilla, Safari, ...

    xhr = new XMLHttpRequest();

  }else if (window.ActiveXObject) { // IE 8 and older

    xhr = new ActiveXObject("Microsoft.XMLHTTP");

  } 

   //var data = "birthday1="+birthday1_js;          

    var data = "start_date="+start_date+"&end_date="+end_date;                           

     xhr.open("POST", "h1/monitor_bbn_notice_pajak/t_bbn", true); 

     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  

     xhr.send(data);

     xhr.onreadystatechange = display_data;

     function display_data() {

        if (xhr.readyState == 4) {

            if (xhr.status == 200) {       

                document.getElementById("tampil_data").innerHTML = xhr.responseText;

            }else{

                alert('There was a problem with the request.');

            }

        }

    }   

}

</script>