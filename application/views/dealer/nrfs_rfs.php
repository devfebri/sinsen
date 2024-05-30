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

  echo "<body onload='kirim_data_rfs()'>";  

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

    <li class="">Kontrol Unit</li>

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

            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

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

              <form class="form-horizontal" action="dealer/nrfs_rfs/save" method="post" enctype="multipart/form-data">              
                <?php if (isset($mode)): ?>
                  <input type="hidden" name="mode" value="<?= $mode ?>">
                <?php endif ?>
                <div class="form-group">

                  <!--label for="inputEmail3" class="col-sm-2 control-label">ID Ubah Status</label-->

                  <div class="col-sm-3">

                    <input type="hidden" class="form-control" readonly id="id_ubah" placeholder="Scan Nomor Mesin" name="id_scan_ubah">                    

                  </div>                

                  <div class="col-sm-1">

                  </div>                                          

                  <!--label for="inputEmail3" class="col-sm-2 control-label">Tgl Ubah Status</label-->

                  <div class="col-sm-4">

                    <input type="hidden" class="form-control" readonly  value="<?php echo date("Y-m-d") ?>" id="tgl" placeholder="Tgl Nomor Mesin" name="tgl">                    

                  </div>                                                          

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Scan Nomor Mesin</label>

                  <div class="col-sm-3">

                    <input type="text" class="form-control" autofocus id="rfs_text" placeholder="Scan Nomor Mesin" name="rfs_text">                    

                  </div>                                                          

                  <div class="col-sm-4">

                    <!-- <button type="submit" class="btn btn-primary btn-flat btn-md"><i class="fa fa-save"></i> Save</button> -->

                    <button data-toggle="modal" type="button" data-target="#Scanmodal" class="btn btn-primary btn-flat btn-md"><i class="fa fa-check"></i> Browse</button>

                  </div>                                                          

                </div>     
                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>

                  <div class="col-sm-5">
                    <input type="text" class="form-control" autofocus id="keterangan" placeholder="Keterangan" name="keterangan">                    

                  </div>
                </div>                

              </div>



              <?php
              $id_dealer   = $this->m_admin->cari_dealer();
              $cek_dealer = cek_dealer($id_dealer);
              $nosin_ins=null;
              if ($cart = $this->cart->contents())

                {

             ?>

              <table id="example" class="table table-bordered table-hover">

                <thead>

                  <tr>              

                    <th width="5%">No</th>

                    <th>No Mesin</th>                                  
                    <th>No Rangka</th>                                  
                    <th>Tipe</th>
                    <th>Warna</th>                                  
                    <th>Mekanik (Honda ID)</th>
                    <th></th>      

                  </tr>

                </thead>

                <tbody>            

                <?php 

                $no = 1;

                foreach ($cart as $row) {
                  $nosin[$no] = "'$row[no_mesin]'"; 
                  $sc= $this->db->get_where('tr_scan_barcode',['no_mesin'=>$row['no_mesin']])->row();
                  echo "

                  <tr>

                    <td>$no</td>

                    <td>$row[name]</td>                    
                    <td>$sc->no_rangka</td>                    
                    <td>$row[tipe]</td>                    
                    <td>$row[warna]</td>                 
                    <td>"?>
                    <select class="form-control" onchange="setMekanik(this,'<?= $row['rowid'] ?>')">
                      <?php $kry = $this->db->query("SELECT ms_karyawan_dealer.*,ms_jabatan.jabatan FROM ms_karyawan_dealer 
                        JOIN ms_jabatan ON ms_karyawan_dealer.id_jabatan=ms_jabatan.id_jabatan
                        WHERE id_dealer='$id_dealer'"); 
                      if ($kry->num_rows()>0) { ?>
                        <option value="">-choose-</option>
                      <?php 
                        foreach ($kry->result() as $rs) { 
                          $select = $rs->id_karyawan_dealer==$row['id_karyawan_dealer']?'selected':'';
                          ?>
                          <option value="<?= $rs->id_karyawan_dealer ?>" <?= $select ?>><?= $rs->jabatan .' | '.$rs->nama_lengkap ?></option>
                       <?php }
                        }else{
                          echo '<option value="">Data Kosong</option>';
                        }
                      ?>
                    </select>
                    </td>
                    <td width='5%'>

                    <button title="Hapus Data"

                      class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button"       

                      onclick="hapus_data('<?php echo $row['rowid']; ?>')"></button>

                    </td>

                  </tr>

                  <?php

                  $no++;

                }
               $nosin_ins = implode(',', $nosin)
                ?>

                </tbody>

              </table>

              <?php } ?>



                <div class="form-group">

                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" class="btn btn-info btn-flat margin"><i class="fa fa-save"></i> Save All </button>              

                </div>

            </form>          

          </div>

        </div>



        

      </div><!-- /.box-body -->

    </div><!-- /.box -->


 <?php 

    }elseif($set == 'rfs_nrfs'){

    ?>



    <div class="box">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="dealer/nrfs_rfs">

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
          <div class="col-md-12">

            <div class="box-body">    

              <form class="form-horizontal" action="dealer/nrfs_rfs/save_nrfs_db" method="post" enctype="multipart/form-data">              
                <div class="form-group">

                  <!--label for="inputEmail3" class="col-sm-2 control-label">ID Ubah Status</label-->

                  <div class="col-sm-3">

                    <input type="hidden" class="form-control" readonly id="id_ubah" placeholder="Scan Nomor Mesin" name="id_scan_ubah">                    

                  </div>                

                  <div class="col-sm-1">

                  </div>                                          

                  <!--label for="inputEmail3" class="col-sm-2 control-label">Tgl Ubah Status</label-->

                  <div class="col-sm-4">

                    <input type="hidden" class="form-control" readonly  value="<?php echo date("Y-m-d") ?>" id="tgl" placeholder="Tgl Nomor Mesin" name="tgl">                    

                  </div>                                                          

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Scan Nomor Mesin</label>

                  <div class="col-sm-3">

                    <input type="text" class="form-control" autofocus id="rfs_text" placeholder="Scan Nomor Mesin" name="rfs_text">                    

                  </div>                                                          

                  <div class="col-sm-4">
                    <button data-toggle="modal" type="button" data-target="#modalNosin" class="btn btn-primary btn-flat btn-md"><i class="fa fa-check"></i> Browse</button>

                  </div>                                                          

                </div>     
                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>

                  <div class="col-sm-4">
                    <input type="text" class="form-control" autofocus id="keterangan" placeholder="Keterangan" name="keterangan">                    
                  </div>                                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Sumber Kerusakan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" autofocus id="keterangan" value="Dealer" readonly>
                  </div>                        

                </div>      
                <button class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
                <br><br>          
              <div id="tampil_detail"></div>
                <div class="form-group">

                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" class="btn btn-info btn-flat margin"><i class="fa fa-save"></i> Save All </button>              

                </div>

            </form>          

          </div>

        </div>    
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<div class="modal fade" id="modalNosin">      

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        Search Item

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        

      </div>

      <div class="modal-body">

        <table id="example3" class="table table-bordered table-hover">

          <thead>

            <tr>

              <th width="5%">No</th>

              <th>No Mesin</th>            

              <th>No Rangka</th>            

              <th>Tipe</th>

              <th>Warna</th>

              <th width="1%"></th>

            </tr>

          </thead>

          <tbody>

          <?php

          $no = 1;                         

          $id_dealer = $this->m_admin->cari_dealer();
          $data_nosin = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_penerimaan_unit_dealer 

              ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer


              WHERE tr_penerimaan_unit_dealer_detail.jenis_pu = 'rfs' 
              AND tr_penerimaan_unit_dealer_detail.status_dealer = 'input' 
              AND tr_penerimaan_unit_dealer_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_sales_order WHERE no_mesin IS NOT NULL)
              AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'");
           foreach ($data_nosin->result() as $xx) {            

            $nos = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$xx->no_mesin)->row();
            $no_rangka = isset($nos->no_rangka)?$nos->no_rangka:'';
            $tipe_motor = isset($nos->tipe_motor)?$nos->tipe_motor:'';
            $warna = isset($nos->warna)?$nos->warna:'';
            echo "

            <tr>

              <td>$no</td>

              <td>$xx->no_mesin</td>

              <td>$no_rangka</td>

              <td>$tipe_motor</td>

              <td>$warna</td>";

              ?>

              <td class="center">

                <button title="Choose" data-dismiss="modal" onclick="pilihNosin('<?php echo $xx->no_mesin; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 

              </td>           

            </tr>

            <?php

            $no++;

          }

          ?>

          </tbody>

          </tbody>

        </table>

      </div>      

    </div>

  </div>

</div>
<div class="modal fade modalPart" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">AHASS</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="no_mesin">
        <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_part" style="width: 100%">
                  <thead>
                  <tr>
                      <th>ID Part</th>
                      <th>Nama Part</th>
                      <th>Kel. Vendor</th>
                      <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
              <script>
                  $(document).ready(function(){
                      $('#tbl_part').DataTable({
                          processing: true,
                          serverSide: true,
                          "language": {                
                                  "infoFiltered": ""
                              },
                          order: [],
                          ajax: {
                              url: "<?= base_url('master/kpb/fetch_part') ?>",
                              dataSrc: "data",
                              data: function ( d ) {
                                    // d.kode_item     = $('#kode_item').val();
                                    return d;
                                },
                              type: "POST"
                          },
                          "columnDefs":[  
                      // { "targets":[4],"orderable":false},
                      { "targets":[2],"className":'text-center'}, 
                      // { "targets":[4], "searchable": false } 
                 ]
                      });
                  });
              </script>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready( function () {
    getDetail();
  });
function pilihNosin(no_mesin){
  values ={no_mesin:no_mesin}
  $.ajax({
      url:"<?php echo site_url('dealer/nrfs_rfs/save_nrfs');?>",
      type:"POST",
      data:values,
      cache:false,
      success:function(data){
        getDetail();
      }
    });
  $("#modalNosin").modal("hide");

}
  function getDetail() {
    $.ajax({
      url:"<?php echo site_url('dealer/nrfs_rfs/getDetail');?>",
      type:"POST",
      // data:values,
      cache:false,
      success:function(html){
        $('#tampil_detail').html(html);
      }
    });
  }
function hapusNosin(a){ 

    var rowid  = a;           

    $.ajax({

        url : "<?php echo site_url('dealer/nrfs_rfs/hapus')?>",

        type:"POST",

        data:"rowid="+rowid,

        cache:false,

        success:function(msg){            
          getDetail()
        }

    })

}
function showModalPart(no_mesin) {
  $('.modalPart').modal('show');
  $('.modalPart #no_mesin').val(no_mesin)
}
function pilihPart(part)
{
  var no_mesin = $('.modal #no_mesin').val()
  $('#id_part_'+no_mesin).val(part.id_part);
}
function addPart(no_mesin) {
  var id_part  = $('#id_part_'+no_mesin).val();
  var qty_part = $('#qty_part_'+no_mesin).val();
  if (id_part=='' || qty_part=='') {
    alert('Silahkan isi data dengan lengkap !');
    return false;
  }
  var values ={no_mesin:no_mesin,id_part:id_part,qty_part:qty_part}
  $.ajax({
      beforeSend: function() { $('#loading-status').show(); },
       url:"dealer/nrfs_rfs/addPart",
       type:"POST",
       data:values,
       cache:false,
       success:function(data){
        $('#loading-status').hide();
        if (data=='sukses') {
          getDetail()
        }
       },
       statusCode: {
    500: function() { 
      $('#loading-status').hide();
      alert("Something Wen't Wrong");
    }
  }
  });
}
function delPart(rowid) {
    var values = {rowid:rowid};
    $.ajax({
           beforeSend: function() { $('#loading-status').show(); },
           url:"dealer/nrfs_rfs/delPart",
           type:"POST",
           data:values,
           cache:false,
           success:function(data){
              $('#loading-status').hide();
              if (data=='sukses') {
                getDetail();
              }
           },
           statusCode: {
        500: function() {
          $('#loading-status').hide();
          alert("Something Wen't Wrong");
        }
      }
      });
  }
</script>






    <?php 

    }elseif($set == 'edit'){

    

    $row = $dt_scan_ubah->row();



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

                

        ?>

        <div class="row" id="nrfs_div">

          <div class="col-md-12">

            <div class="box-body">    

              <form class="form-horizontal" action="dealer/nrfs_rfs/update" method="post" enctype="multipart/form-data">              

                <div class="form-group">

                  <!--label for="inputEmail3" class="col-sm-2 control-label">ID Ubah Status</label-->

                  <div class="col-sm-3">

                    <input type="hidden" class="form-control" readonly id="id_ubah" value="<?php echo $row->id_scan_ubah ?>" placeholder="Scan Nomor Mesin" name="id_scan_ubah">                    

                  </div>                

                  <div class="col-sm-1">

                  </div>                                          

                  <!--label for="inputEmail3" class="col-sm-2 control-label">Tgl Ubah Status</label-->

                  <div class="col-sm-4">

                    <input type="hidden" class="form-control" readonly  value="<?php echo $row->tgl_ubah ?>" id="tgl" placeholder="Tgl Nomor Mesin" name="tgl">                    

                  </div>                                                          

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Scan Nomor Mesin</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" autofocus id="rfs_text" placeholder="Scan Nomor Mesin" name="no_barcode">                    

                  </div>                                                          

                  <div class="col-sm-2">

                    <button data-toggle="modal" type="button" data-target="#Scanmodal" class="btn btn-primary btn-flat btn-md"><i class="fa fa-check"></i> Browse</button>

                  </div>                                                          

                </div>                

              </div>



              <div id="tampil_data"></div>



                <div class="form-group">

                  <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="update" class="btn btn-info btn-flat margin"><i class="fa fa-save"></i> Update All </button>              

                  <button type="submit" onclick="return confirm('Are you sure to approve all data?')" name="save" value="approve" class="btn btn-success btn-flat margin"><i class="fa fa-check"></i> Approve All </button>              

                  <button type="submit" onclick="return confirm('Are you sure to reject all data?')" name="save" value="reject" class="btn btn-danger btn-flat margin"><i class="fa fa-close"></i> Reject All </button>              

                </div>

            </form>          

          </div>

        </div>



        

      </div><!-- /.box-body -->

    </div><!-- /.box -->







    <?php 

    }elseif($set == 'detail'){

    ?>



    <div class="box">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="dealer/nrfs_rfs">

            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

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

        

        // $row = $dt_scan_ubah->row();

        ?>

        <div class="row" id="nrfs_div">

          <div class="col-md-12">

            <div class="box-body">    

              <form class="form-horizontal" action="dealer/penerimaan_unit/save" method="post" enctype="multipart/form-data">              

                <div class="form-group">

                  <!--label for="inputEmail3" class="col-sm-2 control-label">ID Ubah Status</label>

                  <div class="col-sm-4">

                  </div-->                                                          

                  <input type="hidden" class="form-control" readonly id="id_ubah" value="<?php echo $row->id_scan_ubah_dealer ?>" placeholder="Scan Nomor Mesin" name="id">                    

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Ubah Status</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" readonly  value="<?php echo date("Y-m-d") ?>" id="tgl" placeholder="Tgl Nomor Mesin" name="tgl">                    

                  </div>                                                          

                </div>   
                 <div class="form-group">

                  <!--label for="inputEmail3" class="col-sm-2 control-label">ID Ubah Status</label>

                  <div class="col-sm-4">

                  </div-->                                                          
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>

                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly  value="<?php echo $row->keterangan ?>" id="tgl" placeholder="Keterangan" name="tgl">                    

                  </div>                                                          

                </div>   
                <div class="form-group">

                  <!--label for="inputEmail3" class="col-sm-2 control-label">ID Ubah Status</label>

                  <div class="col-sm-4">

                  </div-->                                                          
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Perubahan</label>

                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly  value="<?php echo $row->status_ubah ?>">
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
              <th>No Rangka</th>              
              <th>Tipe</th>
              <th>Warna</th>
              <th>Mekanik (Honda ID)</th>
            </tr>

          </thead>

          <tbody>            

          <?php 

          $no = 1;

          if ($dt_scan_ubah->num_rows()>0) {
            foreach ($dt_scan_ubah->result() as $row) {
              $kry = $this->db->query("SELECT ms_karyawan_dealer.*,ms_jabatan.jabatan FROM ms_karyawan_dealer 
                        JOIN ms_jabatan ON ms_karyawan_dealer.id_jabatan=ms_jabatan.id_jabatan
                        WHERE id_karyawan_dealer='$row->id_mekanik'");
              $mekanik = '';
              if ($kry->num_rows()>0) {
                $kry = $kry->row();
                $mekanik = $kry->jabatan.' | '.$kry->nama_lengkap;
              }
              echo "

              <tr>

                <td>$no</td>

                <td>$row->nomesin</td>              
                <td>$row->no_rangka</td>              
                <td>$row->tipe_ahm</td>              
                <td>$row->warna</td>              
                <td>$mekanik</td>
              </tr>";            

              $no++;

            }
          }else{
            echo '<tr><td colspan="5" align="center">Data Detail Tidak Ada</td></tr>';
          }

          ?>

          </tbody>

        </table><br>
        <?php if ($row->status_ubah=='RFS ke NRFS'): ?>
          <div class="form-group">
            <button type="reset" class="btn btn-primary btn-flat btn-block" disabled="">Dokumen NRFS</button>
          </div>
          <div class="form-group">
            <table class="table table-bordered">
              <thead>
                <tr style="text-align: center;">
                  <th>Dokumen NRFS ID</th>
                  <th>ID Dealer</th>
                  <th>No. Shipping List</th>
                  <th>Deskripsi Unit</th>
                  <th>Deskripsi Warna</th>
                  <th>No Rangka</th>
                  <th>No Mesin</th>
                  <th>Need Parts</th>
                  <th>Parts</th>
                  <th>Sumber NRFS</th>
                  <th>Status Dokumen</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($dt_scan_ubah->result() as $row) { 
                  $dok = $this->db->query("SELECT tr_dokumen_nrfs.*,CONCAT(kode_dealer_md,' | ',nama_dealer) AS dealer,tr_dokumen_nrfs.status FROM tr_dokumen_nrfs 
                    JOIN ms_dealer ON tr_dokumen_nrfs.id_dealer=ms_dealer.id_dealer
                    WHERE no_mesin='$row->nomesin' ORDER BY created_at DESC LIMIT 1");
                ?>
                  <?php if ($dok->num_rows()>0): 
                    $dok = $dok->row();
                    $part = $this->db->query("SELECT * FROM tr_dokumen_nrfs_part WHERE dokumen_nrfs_id='$dok->dokumen_nrfs_id'");
                  ?>
                    <tr>
                    <td><?= $dok->dokumen_nrfs_id ?></td>
                    <td><?= $dok->dealer ?></td>
                    <td><?= $dok->no_shiping_list ?></td>
                    <td><?= $dok->deskripsi_unit ?></td>
                    <td><?= $dok->deskripsi_warna ?></td>
                    <td><?= $dok->no_mesin ?></td>
                    <td><?= $dok->no_rangka ?></td>
                    <td><?= $dok->need_parts ?></td>
                    <td>
                      <?php if ($part->num_rows()>0): ?>
                        <table class="table table-bordered">
                          <tr>
                            <td><b>No. Part</b></td>
                            <td><b>Qty Part</b></td>
                          </tr>
                          <?php foreach ($part->result() as $prt): ?>
                            <tr>
                              <td><?= $prt->id_part ?></td>
                              <td><?= $prt->qty_part ?></td>
                            </tr>
                          <?php endforeach ?>
                        </table>
                      <?php endif ?>
                    </td>
                    <td><?= $dok->sumber_rfs_nrfs ?></td>
                    <td><?= $dok->status ?></td>
                  </tr>
                  <?php endif ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
        <?php endif ?>

      </div><!-- /.box-body -->

    </div><!-- /.box -->



    <?php 

    }elseif($set == 'cetak'){

    ?>



    <div class="box">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="dealer/nrfs_rfs">

            <button class="btn bg-blue btn-flat margin"><i class="fa fa-chevron-left"></i> Back</button>

          </a>          

          <!--button class="btn bg-maroon btn-flat margin" ><i class="fa fa-print"></i> Print All</button-->                  

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

              <th>No Mesin</th>              

              <th>Tipe Kendaraan</th>              

              <th>Warna</th>

              <th>Status</th>

              <th width="5%">Qty Cetak</th>              

            </tr>

          </thead>

          <tbody>            

          <?php 

          $no=1; 

          foreach($dt_cetak->result() as $row) {                 

            echo "

            <tr>

              <td>$no</td>

              <td>$row->no_mesin</td>

              <td>$row->tipe_ahm</td>

              <td>$row->warna</td>              

              <td>$row->tipe</td>              

              <td>"; ?>

                <!-- <a href="dealer/penerimaan_unit/cetak_s?id=<?php echo $row->no_mesin ?>" target="_blank">

                  <button name="cetak" type="button" class="btn bg-maroon btn-flat btn-sm"><i class="fa fa-print"></i></button>

                </a> -->

                <button name="cetak" type="button" class="btn bg-maroon btn-flat btn-sm" 

                  onclick="javascript:wincal=window.open('dealer/nrfs_rfs/cetak_s?id=<?php echo $row->no_mesin; ?>',

                  'Set Bayar','width=600,height=400');">

                <i class="fa fa-print"></i></button>

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

    }elseif($set=="view"){

    ?>



    <div class="box">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="dealer/nrfs_rfs/add">

            <button class="btn bg-blue btn-flat margin"> NRFS ke RFS</button>

          </a> 
          <a href="dealer/nrfs_rfs/add_rfs_nrfs">

            <button class="btn bg-green btn-flat margin"> RFS ke NRFS</button>

          </a> 
          <!-- <a href="#">

            <button class="btn bg-red btn-flat margin"> RFS ke NRFS</button>

          </a>           -->

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

              <th>Tgl Ubah</th>            

              <th>Jumlah Unit</th>  
              <th>Jenis Perubahan</th>  

              <th>Action</th>

            </tr>

          </thead>

          <tbody>            

          <?php 

          $no = 1;

          foreach ($dt_scan_ubah->result() as $row) {

            $cek = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_ubah_dealer_detail WHERE id_scan_ubah_dealer = '$row->id_scan_ubah_dealer'");

            if($cek->num_rows() > 0){

              $fr = $cek->row();

              $jum = $fr->jum;

            }else{

              $jum = 0;

            }

            echo "

            <tr>

              <td>$no</td>

              <td>$row->tgl_ubah</td>

              <td>$jum</td>
              <td>$row->status_ubah</td>
              <td align='center'>"; ?>            

              <a href='<?php echo "dealer/nrfs_rfs/detail?id=$row->id_scan_ubah_dealer" ?>'>

                  <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-eye'></i> Detail</button>

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





<div class="modal fade" id="Scanmodal">      

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        Search Item

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        

      </div>

      <div class="modal-body">

        <table id="example3" class="table table-bordered table-hover">

          <thead>

            <tr>

              <th width="5%">No</th>

              <th>No Mesin</th>            

              <th>No Rangka</th>            

              <th>Tipe</th>

              <th>Warna</th>

              <th width="1%"></th>

            </tr>

          </thead>

          <tbody>

          <?php

          $no = 1;                         

          $id_dealer = $this->m_admin->cari_dealer();

          if (isset($mode)) {
            if ($mode=='rfs_nrfs') {
              $stat = 'rfs';
            }
          }else{
            $stat='nrfs';
            $id_dealer            = $this->m_admin->cari_dealer();
            $dok_nrfs = "AND no_mesin IN(SELECT no_mesin FROm tr_dokumen_nrfs WHERE status='ready_to_repair' AND id_dealer='$id_dealer')";
          }
          if (isset($nosin_ins)) {
            $nosin_ins = "AND tr_penerimaan_unit_dealer_detail.no_mesin NOT IN($nosin_ins)";
          }else{
            $nosin_ins = '';
          }
          $dt_scan = $this->db->query("SELECT *,no_mesin FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_penerimaan_unit_dealer 

              ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 

              WHERE tr_penerimaan_unit_dealer_detail.jenis_pu = '$stat' 

              AND tr_penerimaan_unit_dealer_detail.status_dealer = 'input' AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' $nosin_ins
              $dok_nrfs 
              ");

          foreach ($dt_scan->result() as $ve2) {            

            $nos = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$ve2->no_mesin)->row();

            echo "

            <tr>

              <td>$no</td>

              <td>$ve2->no_mesin</td>

              <td>$nos->no_rangka</td>

              <td>$nos->tipe_motor</td>

              <td>$nos->warna</td>";

              ?>

              <td class="center">

                <button title="Choose" data-dismiss="modal" onclick="choose_rangka('<?php echo $ve2->no_mesin; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 

              </td>           

            </tr>

            <?php

            $no++;

          }

          ?>

          </tbody>

        </table>

      </div>      

    </div>

  </div>

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

function choose_rangka(no_mesin){

  document.getElementById("rfs_text").value = no_mesin;   

  simpan_rfs();

  $("#Scanmodal").modal("hide");

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

  var rfs_text    = document.getElementById("rfs_text").value;     

  //alert(id_po);

  if (rfs_text == "") {    

      alert("Isikan data dengan lengkap...!");

      return false;

  }else{

      $.ajax({

          url : "<?php echo site_url('dealer/nrfs_rfs/save_nosin')?>",

          type:"POST",

          data:"rfs_text="+rfs_text,

          cache:false,

          success:function(msg){            

              data=msg.split("|");

              if(data[0]=="ok"){

                window.location.reload();

              }else{
                alert(msg);
                // $('#rfs_text').val('');
              }     

          }

      })    

  }

}

function hapus_data(a){ 

    var rowid  = a;           

    $.ajax({

        url : "<?php echo site_url('dealer/nrfs_rfs/hapus')?>",

        type:"POST",

        data:"rowid="+rowid,

        cache:false,

        success:function(msg){            

            data=msg.split("|");

            if(data[0]=="ok"){

              window.location.reload();

            }

        }

    })

}

function setNeedParts(no_mesin) {
  var need_parts = $('#need_parts_'+no_mesin).val();
  values = {need_parts:need_parts, no_mesin:no_mesin}
 $.ajax({
  // beforeSend: function() {
  //   $('#gnrtBtn').attr('disabled',true);
  // },
  url:'<?= base_url('dealer/nrfs_rfs/setNeedParts') ?>',
  type:"POST",
  data: values,
  cache:false,
  dataType:'JSON',
  success:function(response){
    getDetail()
  },
  error:function(){
    alert("Error");
    // $('#gnrtBtn').attr('disabled',false);
  },
  statusCode: {
    500: function() { 
      alert('Error Code 500');
      // $('#gnrtBtn').attr('disabled',false);
    }
  }
});
}

function setMekanik(el, rowid) {
  let id_karyawan = $(el).val();
  console.log(id_karyawan);
  let values = {rowid:rowid,id_karyawan:id_karyawan}
 $.ajax({
  // beforeSend: function() {
  //   $('#gnrtBtn').attr('disabled',true);
  // },
  url:'<?= base_url('dealer/nrfs_rfs/setMekanik') ?>',
  type:"POST",
  data: values,
  cache:false,
  dataType:'JSON',
  success:function(response){
    if (response.status=='sukses') {
      // window.location.reload();
    }
  },
  error:function(){
    alert("Error");
    // $('#gnrtBtn').attr('disabled',false);
  },
  statusCode: {
    500: function() { 
      alert('Error Code 500');
      // $('#gnrtBtn').attr('disabled',false);
    }
  }
});
}

</script>

<!-- <script type="text/javascript">

var rfs_text = document.getElementById("rfs_text");

rfs_text.addEventListener("keydown", function (e) {

    if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"

        simpan_rfs();

    }

});

</script> -->