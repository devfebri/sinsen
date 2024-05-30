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

<?php if(isset($_GET['id'])){ ?>

  <body onload="kirim_data()">

<?php }else{ ?>

  <body onload="auto()">

<?php } ?>

<div class="content-wrapper">

<!-- Content Header (Page header) -->

<section class="content-header">

  <h1>

    <?php echo $title; ?>    

  </h1>

  <ol class="breadcrumb">

    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    

    <li class="">H1</li>

    <li class="">Faktur STNK</li>

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

          <a href="h1/penggantian_fkb">

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

            <form class="form-horizontal" action="h1/penggantian_fkb/save" method="post" enctype="multipart/form-data">              

              <div class="box-body">       

                <br>

                <input type="hidden" id="mode" value="new">

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Entry/Surat</label>

                  <div class="col-sm-4">

                    <input type="text" name="tgl_entry" placeholder="Tgl Entry/Surat" value="<?php echo date("Y-m-d") ?>" readonly class="form-control">

                  </div>                  

                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat</label>

                  <div class="col-sm-4">

                    <input type="text" name="no_surat" id="no_surat" placeholder="No Surat" readonly class="form-control">

                  </div>                                

                </div>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Ditujukan Ke</label>

                  <div class="col-sm-6">

                    <select class="form-control select2" name="ditujukan_ke">

                      <option value="">- choose -</option>

                      <?php 

                      $dt_1 = $this->m_admin->getSortCond("ms_proses_ganti_faktur","ditujukan_ke","ASC");

                      foreach ($dt_1->result() as $isi) {

                        echo "<option value='$isi->ditujukan_ke'>$isi->ditujukan_ke</option>";

                      }

                      ?>

                    </select>

                  </div>                  

                </div>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pengirim</label>

                  <div class="col-sm-6">

                    <select class="form-control select2" name="nama_pengirim">

                      <option value="">- choose -</option>

                      <?php 

                      $dt_2 = $this->m_admin->getSortCond("ms_proses_ganti_faktur","nama_pengirim","ASC");

                      foreach ($dt_2->result() as $isi) {

                        echo "<option value='$isi->nama_pengirim'>$isi->nama_pengirim</option>";

                      }

                      ?>

                    </select>

                  </div>                                

                </div>  

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Jabatan Pengirim</label>

                  <div class="col-sm-6">

                    <select class="form-control select2" name="jabatan">

                      <option value="">- choose -</option>

                      <?php 

                      $dt_3 = $this->db->query("SELECT DISTINCT(ms_jabatan.jabatan) FROM ms_proses_ganti_faktur INNER JOIN ms_jabatan ON ms_proses_ganti_faktur.id_jabatan=ms_jabatan.id_jabatan 

                              ORDER BY nama_pengirim ASC");

                      foreach ($dt_3->result() as $isi) {

                        echo "<option value='$isi->jabatan'>$isi->jabatan</option>";

                      }

                      ?>

                    </select>

                  </div>                                

                </div>  



                <span id="tampil_data"></span>

                

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

    }elseif($set=="detail"){

      $row = $dt_alasan->row();

    ?>



    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="h1/penggantian_fkb">

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

        <div class="row">

          <div class="col-md-12">            

            <form class="form-horizontal" action="h1/penggantian_fkb/save" method="post" enctype="multipart/form-data">              

              <div class="box-body">       

                <br>

                <input type="hidden" id="mode" value="detail">

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Entry/Surat</label>

                  <div class="col-sm-4">

                    <input type="text" name="tgl_entry" placeholder="Tgl Entry/Surat" value="<?php echo $row->tgl_entry ?>" readonly class="form-control">

                  </div>                  

                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat</label>

                  <div class="col-sm-4">

                    <input type="text" name="no_surat" id="no_surat" value="<?php echo $row->no_surat ?>"  placeholder="No Surat" readonly class="form-control">

                  </div>                                

                </div>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Ditujukan Ke</label>

                  <div class="col-sm-6">

                    <input type="text" name="no_surat" id="no_surat" value="<?php echo $row->ditujukan_ke ?>"  placeholder="Ditujukan" readonly class="form-control">

                  </div>                  

                </div>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pengirim</label>

                  <div class="col-sm-6">

                    <input type="text" name="no_surat" id="no_surat" value="<?php echo $row->nama_pengirim ?>"  placeholder="Nama Pengirim" readonly class="form-control">

                  </div>                                

                </div>  

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Jabatan Pengirim</label>

                  <div class="col-sm-6">

                    <input type="text" name="no_surat" id="no_surat" value="<?php echo $row->jabatan ?>"  placeholder="Jabatan" readonly class="form-control">

                  </div>                                

                </div>  



                <span id="tampil_data"></span>

                

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

    }elseif($set=="cetak"){

      $row = $dt_alasan->row();

    ?>



    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="h1/penggantian_fkb">

            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

          </a>

          <a href="h1/penggantian_fkb/cetak_fix?id=<?php echo $row->no_surat ?>" target="_blank">

            <button class="btn btn-success btn-flat margin"><i class="fa fa-print"></i> Cetak</button>

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

            <table class="myTable1" width="90%" align="center" border="0">

              <tr>

                <td width="60%">

                  <?php echo $row->no_surat ?>

                </td>

                <td width="40%">

                  <?php

                  $tanggal = date("d F Y", strtotime($row->tgl_entry)); 

                  echo $tanggal 

                  ?>

                </td>

              </tr>

              <tr>

                <td valign="top">

                  Penggantian / Koreksi Faktur Polisi

                </td>

                <td>

                  Kepada Yth, <br>

                  PT.Astra Honda Motor <br>

                  AR/AP Dept head <br>

                  Jl.Laksada Yos Sudarso. Sunter I <br>

                  Jakarta 14350 <br>

                  UP.Bpk Priyo Lambang

                </td>

              </tr>            

              <tr>

                <td colspan="2">

                  Dengan hormat, <br>

                  <p>

                    Dengan ini kami mohon untuk diterbitkan kembali Faktur Polisi / Sertifikat di bawah ini:

                  </p>

                  <table class="table table-bordered table-hover" width="100%">

                    <tr>

                      <td width="5%">No</td>

                      <td width="45%">No aktur</td>

                      <td width="50%">Alasan Penggantian</td>

                    </tr>

                    <?php 

                    $no=1;

                    $tr = $this->db->query("SELECT * FROM tr_penggantian_fkb_detail INNER JOIN tr_fkb ON tr_penggantian_fkb_detail.no_mesin=tr_fkb.no_mesin_spasi

                       WHERE tr_penggantian_fkb_detail.no_surat = '$row->no_surat'");

                    foreach ($tr->result() as $isi) {

                      echo "

                      <tr>

                        <td>$no</td>

                        <td>$isi->nomor_faktur</td>

                        <td>$isi->alasan_penggantian</td>

                      </tr>

                      ";

                    $no++;

                    }

                    ?>                    

                  </table>

                </td>

              </tr>  

              <tr>

                <td colspan="2">

                  Atas bantuan dan kerjasamanya kami ucapkan terima kasih.

                </td>                

              </tr>

              <tr>

                <td width="60%"></td>

                <td width="40%">

                  Hormat Kami, <br><br><br>

                  <u>Tony Attas</u> <br>

                  <i>Direktur</i>

                </td>

              </tr>

            </table>            

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

          <a href="h1/penggantian_fkb/add">            

            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>

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

        <table id="example2" class="table table-bordered table-hover">

          <thead>

            <tr>              

              <th width="5%">No</th>                          

              <th>No Surat</th>

              <th>Tgl Surat</th>

              <th>Ditujukan Ke</th>              

              <th width="5%">Action</th>              

            </tr>

          </thead>

          <tbody>            

          <?php 

          $no=1; 

          foreach($dt_alasan->result() as $row) {                                         
          $print = $this->m_admin->set_tombol($id_menu,$group,'print');

          echo "          

            <tr>

              <td>$no</td>

              <td>

                <a href='h1/penggantian_fkb/detail?id=$row->no_surat'>

                  $row->no_surat

                </a>

              </td>                           

              <td>$row->tgl_entry</td>              

              <td>$row->ditujukan_ke</td>                            

              <td>

                <a href='h1/penggantian_fkb/cetak?id=$row->no_surat' $print class='btn btn-primary btn-flat btn-xs'><i class='fa fa-print'> Cetak Surat</i></a>

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

    ?>

  </section>

</div>

<script type="text/javascript">

function auto(){    

  var id = "1";

  $.ajax({

      url : "<?php echo site_url('h1/penggantian_fkb/cari_id')?>",

      type:"POST",

      data:"id="+id,   

      cache:false,   

      success: function(msg){ 

        data=msg.split("|");

        $("#no_surat").val(data[0]);              
        getSelect2();
        kirim_data();

      }        

  })

}

function kirim_data(){    

  getSelect2();
  $("#tampil_data").show();

  var no_surat  = document.getElementById("no_surat").value;   

  var mode      = document.getElementById("mode").value;   

  var xhr;

  if (window.XMLHttpRequest) { // Mozilla, Safari, ...

    xhr = new XMLHttpRequest();

  }else if (window.ActiveXObject) { // IE 8 and older

    xhr = new ActiveXObject("Microsoft.XMLHTTP");

  } 

   //var data = "birthday1="+birthday1_js;          

    var data = "no_surat="+no_surat+"&mode="+mode;                           

     xhr.open("POST", "h1/penggantian_fkb/t_data", true); 

     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  

     xhr.send(data);

     xhr.onreadystatechange = display_data;

     function display_data() {

        if (xhr.readyState == 4) {

            if (xhr.status == 200) {       
                getSelect2();
                document.getElementById("tampil_data").innerHTML = xhr.responseText;

            }else{

                alert('There was a problem with the request.');

            }

        }

    }   

}

function ambil_nosin(){

  var no_mesin = $("#no_mesin").val();                       

  $.ajax({

      url: "<?php echo site_url('h1/penggantian_fkb/ambil_nosin')?>",

      type:"POST",

      data:"no_mesin="+no_mesin,            

      cache:false,

      success:function(msg){                

          data=msg.split("|");          

          $("#tipe").val(data[0]);                                                    

          $("#warna").val(data[1]);                                                    

          $("#tahun_produksi").val(data[2]);                                                    

          $("#no_faktur").val(data[3]);                                                    

      } 

  })

}

function simpan_data(){

  var no_surat  = document.getElementById("no_surat").value;  

  var no_mesin    = document.getElementById("no_mesin").value;     

  var alasan_penggantian    = document.getElementById("alasan_penggantian").value;     

  //alert(id_po);

  if (no_mesin == "" || alasan_penggantian == "") {    

      alert("Isikan data dengan lengkap...!");

      return false;

  }else{

      $.ajax({

          url : "<?php echo site_url('h1/penggantian_fkb/save_nosin')?>",

          type:"POST",

          data:"no_surat="+no_surat+"&no_mesin="+no_mesin+"&alasan_penggantian="+alasan_penggantian,

          cache:false,

          success:function(msg){            

              data=msg.split("|");

              if(data[0]=="ok"){

                  kirim_data();

                  kosong();                

              }else{

                  alert("Gagal Simpan, No Mesin List ini sudah dipilih");

                  kosong();                  

              }                

          }

      })    

  }

}

function kosong(args){

  $("#no_mesin").val("");

  $("#tipe").val("");     

  $("#warna").val("");     

  $("#tahun_produksi").val("");     

  $("#no_faktur").val("");     

  $("#alasan_penggantian").val("- choose -");     

}



function hapus_nosin(a,b){ 

    var no_surat  = b;       

    var id_penggantian_fkb_detail  = a;       

    $.ajax({

        url : "<?php echo site_url('h1/penggantian_fkb/delete_nosin')?>",

        type:"POST",

        data:"id_penggantian_fkb_detail="+id_penggantian_fkb_detail,

        cache:false,

        success:function(msg){            

            data=msg.split("|");

            if(data[0]=="nihil"){

              kirim_data();

            }

        }

    })

}

function getSelect2()
 {
    $(".select2").select2({
            placeholder: "-- Pilih --",
            allowClear: false
        });
  }

</script>