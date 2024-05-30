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

<?php 

if(isset($_GET['id'])){

  if($jenis == "RFS"){

    echo "<body onload='kirim_data_rfs()'>";

  }else{ 

    echo "<body onload='kirim_data_nrfs()'>";

  }

}else{ 

  echo "<body onload='auto()'>";

}

?>

<div class="content-wrapper">

<!-- Content Header (Page header) -->

<section class="content-header">

  <h1>

    <?php echo $title; ?>    

  </h1>

  <ol class="breadcrumb">

    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    

    <li class="">H1</li>

    <li class="">Penerimaan</li>

    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>

  </ol>

  </section>

  <section class="content">

    



    <?php 

    if($set == 'add_nrfs_rfs'){

    ?>



    <div class="box">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="dealer/nrfs_rfs">

            <button class="btn bg-blue btn-flat margin"><i class="fa fa-chevron-left"></i> Back</button>

          </a>

          <a href="dealer/nrfs_rfs/save_back">

            <button class="btn btn-success btn-flat margin"><i class="fa fa-save"></i> Save & Back</button>

          </a>

        </h3>

        <div class="box-tools pull-right">

          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

        </div>

      </div><!-- /.box-header -->

      <!--div class="box-body">

          <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>

          <div class="col-sm-2">

            <input type="text" class="form-control" disabled value="<?php echo date("Y-m-d") ?>" id="tanggal" placeholder="Tanggal penerimaan" id="tgl_penerimaan">                    

          </div>                                                                  

      </div-->

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

        <div class="row" id="nrfs_div">

          <div class="col-md-12">

            <div class="box-body">    

              <form class="form-horizontal" action="dealer/penerimaan_unit/save" method="post" enctype="multipart/form-data">              

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">ID Ubah Status</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" readonly id="id_ubah" placeholder="Scan Nomor Mesin" name="id">                    

                  </div>                                                          

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Ubah Status</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" readonly  value="<?php echo date("Y-m-d") ?>" id="tgl" placeholder="Tgl Nomor Mesin" name="tgl">                    

                  </div>                                                          

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Scan Nomor Mesin</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" autofocus id="rfs_text" placeholder="Scan Nomor Mesin" name="no_barcode">                    

                  </div>                                                          

                  <div class="col-sm-2">

                  </div>                                                          

                </div>                

              </div>

            </form>          

          </div>

        </div>



        <div id="tampil_data"></div>

      </div><!-- /.box-body -->

    </div><!-- /.box -->



    <?php 

    }elseif($set == 'detail'){

    ?>



    <div class="box">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="dealer/nrfs_rfs">

            <button class="btn bg-blue btn-flat margin"><i class="fa fa-chevron-left"></i> Back</button>

          </a>

        </h3>

        <div class="box-tools pull-right">

          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

        </div>

      </div><!-- /.box-header -->

      <!--div class="box-body">

          <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>

          <div class="col-sm-2">

            <input type="text" class="form-control" disabled value="<?php echo date("Y-m-d") ?>" id="tanggal" placeholder="Tanggal penerimaan" id="tgl_penerimaan">                    

          </div>                                                                  

      </div-->

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

        

        $row = $dt_scan_ubah->row();

        ?>

        <div class="row" id="nrfs_div">

          <div class="col-md-12">

            <div class="box-body">    

              <form class="form-horizontal" action="dealer/penerimaan_unit/save" method="post" enctype="multipart/form-data">              

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">ID Ubah Status</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" readonly id="id_ubah" value="<?php echo $row->id_scan_ubah ?>" placeholder="Scan Nomor Mesin" name="id">                    

                  </div>                                                          

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Ubah Status</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" readonly  value="<?php echo date("Y-m-d") ?>" id="tgl" placeholder="Tgl Nomor Mesin" name="tgl">                    

                  </div>                                                          

                </div>                               

              </div>

            </form>          

          </div>

        </div>



        <table id="example" class="table table-bordered table-hover">

          <thead>

            <tr>              

              <th width="5%">No</th>

              <th>No Mesin</th>              

              <th>Status</th>

              <th>Lokasi Awal</th>

              <th>Lokasi Tujuan</th>            

            </tr>

          </thead>

          <tbody>            

          <?php 

          $no = 1;

          foreach ($dt_rfs->result() as $row) {

            echo "

            <tr>

              <td>$no</td>

              <td>$row->no_mesin</td>

              <td>$row->status_ubah</td>

              <td>$row->lokasi_awal</td>

              <td>$row->lokasi_tujuan</td>              

            </tr>";            

            $no++;

          }

          ?>

          </tbody>

        </table>

      </div><!-- /.box-body -->

    </div><!-- /.box -->



    <?php

    }elseif($set=="view"){

    ?>



    <div class="box">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="dealer/nrfs_rfs/add">

            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>

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

              <th>ID Ubah</th>              

              <th>Tgl Ubah</th>              

              <th width="15%">Approval Status</th>

              <th width="18%">Action</th>              

            </tr>

          </thead>

          <tbody>            

          <?php 

          $no = 1;

          foreach ($dt_scan_ubah->result() as $row) {

            $rt = $this->m_admin->getByID("tr_scan_ubah","id_scan_ubah",$row->id_scan_ubah)->row();

            if($rt->status_ubah == 'Waiting Approval'){

              $r = '';

              $isi = "<span class='label label-warning'>Waiting Approval</span>";

            }elseif($rt->status_ubah == 'Approved'){

              $r = 'disabled';

              $isi = "<span class='label label-success'>Approved</span>";

            }elseif($rt->status_ubah == 'Rejected'){

              $r = 'disabled';

              $isi = "<span class='label label-danger'>Rejected</span>";

            }

            echo "

            <tr>

              <td>$no</td>

              <td>$rt->id_scan_ubah</td>

              <td>$rt->tgl_ubah</td>

              <td>$isi</td>

              <td>"; ?>                

                <a href='<?php echo "dealer/nrfs_rfs/approve?id=$rt->id_scan_ubah" ?>'>

                  <button <?php echo $r ?> onclick="return confirm('Are you sure to approve this?')" class='btn btn-flat btn-xs btn-success'><i class='fa fa-check'></i> Approve</button>

                </a>

                <a href='<?php echo "dealer/nrfs_rfs/reject?id=$rt->id_scan_ubah" ?>'>

                  <button <?php echo $r ?> onclick="return confirm('Are you sure to reject this?')"  class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Reject</button>              

                </a>

                <a href='<?php echo "dealer/nrfs_rfs/detail?id=$rt->id_scan_ubah" ?>'>

                  <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-eye'></i> Detail</button>

                </a>

                <a href='<?php echo "dealer/nrfs_rfs/cetak?id=$rt->id_scan_ubah" ?>'>

                  <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"print"); ?> <?php echo $r ?> class='btn btn-flat btn-xs btn-primary'><i class='fa fa-print'></i>  Cetak Striker</button>                

                </a>                

              </td>

            </tr>

            <?php

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

  var tgl_js=document.getElementById("tgl").value; 

  $.ajax({

      url : "<?php echo site_url('dealer/nrfs_rfs/cari_id')?>",

      type:"POST",

      data:"tgl="+tgl_js,   

      cache:false,   

      success: function(msg){ 

        data=msg.split("|");

        $("#id_ubah").val(data[0]);  

        kirim_data_rfs();              

      }        

  })

}

function kirim_data_rfs(){    

  $("#tampil_data").show();

  var id_ubah = document.getElementById("id_ubah").value;   

  var xhr;

  if (window.XMLHttpRequest) { // Mozilla, Safari, ...

    xhr = new XMLHttpRequest();

  }else if (window.ActiveXObject) { // IE 8 and older

    xhr = new ActiveXObject("Microsoft.XMLHTTP");

  } 

   //var data = "birthday1="+birthday1_js;          

    var data = "id_ubah="+id_ubah;                           

     xhr.open("POST", "dealer/nrfs_rfs/t_rfs", true); 

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

function kosong_rfs(args){

  $("#rfs_text").val("");  

}

function simpan_rfs(){

  var id_ubah     = document.getElementById("id_ubah").value;  

  var rfs_text    = document.getElementById("rfs_text").value;     

  //alert(id_po);

  if (id_ubah == "" || rfs_text == "") {    

      alert("Isikan data dengan lengkap...!");

      return false;

  }else{

      $.ajax({

          url : "<?php echo site_url('dealer/nrfs_rfs/save_rfs')?>",

          type:"POST",

          data:"rfs_text="+rfs_text+"&id_ubah="+id_ubah,

          cache:false,

          success:function(msg){            

              data=msg.split("|");

              if(data[0]=="ok"){

                  kirim_data_rfs();

                  kosong_rfs();                

              }else if(data[0]=="no"){

                  alert("Gagal Simpan, No Mesin ini sudah diubah scan sebelumnya");

                  kosong_rfs();                  

              }else if(data[0]=="none"){

                  //alert("Gagal Simpan, No Mesin ini belum pernah di-scan sebelumnya");

                  alert(data[0]);

                  kosong_rfs();                  

              }else{

                alert("Periksa kembali No Mesin yang anda masukkan");

                kosong_rfs();

              }                

          }

      })    

  }

}

function hapus_scan(a,b,c){ 

    var id_scan_ubah  = a;       

    var jenis         = b;       

    var no_mesin      = c;       

    $.ajax({

        url : "<?php echo site_url('dealer/nrfs_rfs/delete_scan')?>",

        type:"POST",

        data:"id_scan_ubah="+id_scan_ubah+"&jenis="+jenis+"&no_mesin="+no_mesin,

        cache:false,

        success:function(msg){            

            data=msg.split("|");

            if(data[0]=="nihil"){

              if(jenis == 'NRFS'){

                kirim_data_rfs();

              }else if(jenis == 'RFS'){

                kirim_data_nrfs();                

              }

            }

        }

    })

}

</script>

<script type="text/javascript">

var rfs_text = document.getElementById("rfs_text");

rfs_text.addEventListener("keydown", function (e) {

    if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"

        simpan_rfs();

    }

});

</script>