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
          <a href="dealer/konfirmasi_bast">
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
            <form class="form-horizontal" action="dealer/konfirmasi_bast" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Retur</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="No Retur" name="nama_konsumen">                                        
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Retur</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" name="nama_konsumen">                    
                  </div>
                </div>                
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">No BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="No BASTD" name="nama_konsumen">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Nama Dealer" name="nama_konsumen">                    
                  </div>                  
                </div>                                  
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" placeholder="Alamat Dealer" name="nama_konsumen">                    
                  </div>                                                    
                </div>
                <table id="" class="table table-bordered table-hover">
                  <thead>
                    <tr>                      
                      <th>No Mesin</th>              
                      <th>No Rangka</th>              
                      <th>Nama Konsumen</th>              
                      <th>Tipe</th>              
                      <th>Warna</th>              
                      <th>Tahun</th>
                      <th>Alasan Retur</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody> 
                  </tbody>
                </table>

              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    

    <?php 
    }elseif($set=='detail'){
      $row = $dt_sales_order->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/konfirmasi_bast">
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
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">No. Sales Order</label>
                <div class="col-sm-4">
                  <input type="text" value="<?php echo $row->id_sales_order ?>" required class="form-control" readonly id="no_sales_order" name="id_sales_order">                                        
                </div>                  
                <label for="inputEmail3" class="col-sm-2 control-label">No. Mesin</label>
                <div class="col-sm-4">
                  <input type="text" required value="<?php echo $row->no_mesin ?>" class="form-control" readonly id="no_mesin">                    
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Nama</label>
                <div class="col-sm-4">
                  <input type="text" required value="<?php echo $row->nama_konsumen ?>" class="form-control" readonly id="nama_konsumen">                                        
                </div>                  
                <label for="inputEmail3" class="col-sm-2 control-label">No. Rangka</label>
                <div class="col-sm-4">
                  <input type="text" required value="<?php echo $row->no_rangka ?>" class="form-control" readonly id="no_rangka">                    
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                <div class="col-sm-4">
                  <input type="text" required value="<?php echo $row->alamat ?>" class="form-control" readonly id="alamat">                                        
                </div>                  
                <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                <div class="col-sm-4">
                  <input type="text" required value="<?php echo $row->tipe_ahm ?>" class="form-control" readonly id="tipe_ahm">                    
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">No. KTP</label>
                <div class="col-sm-4">
                  <input type="text" required value="<?php echo $row->no_ktp ?>" class="form-control" readonly id="no_ktp">                                        
                </div>                  
                <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                <div class="col-sm-4">
                  <input type="text" required value="<?php echo $row->warna ?>" class="form-control" readonly id="warna">                    
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Unit Diterima Konsumen</label>
                <div class="col-sm-4">
                  <input type="text" readonly class="form-control" value="<?php echo $row->tgl_terima_unit_ke_konsumen ?>" name="tgl_terima_unit_ke_konsumen" autocomplete="off">                                        
                </div>  
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Nama Penerima</label>
                <div class="col-sm-4">
                  <input type="text" value="<?php echo $row->nama_penerima ?>" readonly class="form-control" name="nama_penerima">
                </div>  
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">No HP Penerima</label>
                <div class="col-sm-4">
                  <input type="text" value="<?php echo $row->no_hp_penerima ?>" readonly class="form-control" name="no_hp_penerima">
                </div>  
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Foto KTP</label>
                <div class="col-sm-4">
                  <input type="file" name="foto_ktp_penerima" class="form-control" accept="image/x-png,image/gif,image/jpeg,image/jpg,image/bmp">                                       
                </div>  
                <div class="col-sm-1">                                        
                  <a class="btn bg-maroon btn-flat btn-sm" data-toggle="modal" data-target="#Ktpmodal" type="button"><i class="fa fa-image"></i> Lihat</a>
                </div>  
                </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Foto Serah Terima</label>
                <div class="col-sm-4">
                  <input type="file" name="foto_serah_terima" class="form-control" accept="image/x-png,image/gif,image/jpeg,image/jpg,image/bmp">                                       
                </div>  
                <div class="col-sm-1">                                        
                  <a class="btn bg-maroon btn-flat btn-sm" data-toggle="modal" data-target="#Serahmodal" type="button"><i class="fa fa-image"></i> Lihat</a>
                </div>  
              </div>   
              
              <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-5">
                  <label for="explanation_bast"> Penjelasan BAST dari driver ke konsumen <br> Seperti: Unit, Buku, Jaringan Dealer, After Sales dll : </label><br>
                  <?php if($row->explanation_bast==1){echo 'Ya, Sudah dilakukan';}else{ echo 'Belum dilakukan'; } ?>
                </div> 
              </div>
            </form>
          </div>      
        </div>      
      </div>
    </div><!-- /.box -->

    <div class="modal fade" id="Ktpmodal">      
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            View Image
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
          </div>
          <div class="modal-body">
           <img src="assets/panel/images/konfirmasi_bast/<?php echo isset($row->foto_ktp_penerima)?$row->foto_ktp_penerima:''; ?>" width="80%">
          </div>      
        </div>
      </div>
    </div>

    <div class="modal fade" id="Serahmodal">      
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            View Image
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
          </div>
          <div class="modal-body">
           <img src="assets/panel/images/konfirmasi_bast/<?php echo isset($row->foto_serah_terima)?$row->foto_serah_terima:''; ?>" width="80%">
          </div>      
        </div>
      </div>
    </div>

    <?php
    }elseif($set=="view"){
    ?>
    

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="dealer/konfirmasi_bast/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a-->          
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No So</th>                            

              <th>Nama Konsumen</th>              
              <th>Alamat</th>              

              <th>No Mesin</th>
              <th>No Rangka</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_sales_order->result() as $row) {     
 
            echo "
            <tr>
              <td>$no</td>              
              <td>
                <a href='dealer/konfirmasi_bast/detail?id=$row->id_sales_order'>
                  $row->id_sales_order
                </a>
              </td>
              <td>$row->nama_konsumen</td>
              <td>$row->alamat</td>
              <td>$row->no_mesin</td>
              <td>$row->no_rangka</td>
              <td>$row->tipe_ahm</td>
              <td>$row->warna</td>
              <td> ";    
              if ($row->status_cetak != 'konsumen' AND ($row->tgl_terima_unit_ke_konsumen==null OR $row->tgl_terima_unit_ke_konsumen=='' OR $row->tgl_terima_unit_ke_konsumen=='0000-00-00')) { ?>
                <button type="button" class="btn btn-primary btn-flat btn-xs" data-toggle="modal" data-target=".modal_detail" id_sales_order="<?php echo $row->id_sales_order ?>" onclick="detail_popup('<?php echo $row->id_sales_order ?>')">Diterima Konsumen</button>
             <?php }else{
              if($row->tgl_terima_unit_ke_konsumen!=null && $row->tgl_terima_unit_ke_konsumen!='0000-00-00' ){
                echo '<label class="label label-success">Sudah dikonfirm terima oleh konsumen</label>';
              }
             }

              echo "</td>
            </tr>
            ";
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<!-- Modal Detail -->
<div class="modal fade modal_detail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title">Konfirmasi BAST</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" action="dealer/konfirmasi_bast/confirm" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">No. Sales Order</label>
              <div class="col-sm-4">
                <input type="text" required class="form-control" readonly id="no_sales_order" name="id_sales_order">                                        
              </div>                  
              <label for="inputEmail3" class="col-sm-2 control-label">No. Mesin</label>
              <div class="col-sm-4">
                <input type="text" required class="form-control" readonly id="no_mesin">                    
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Nama</label>
              <div class="col-sm-4">
                <input type="text" required class="form-control" readonly id="nama_konsumen">                                        
              </div>                  
              <label for="inputEmail3" class="col-sm-2 control-label">No. Rangka</label>
              <div class="col-sm-4">
                <input type="text" required class="form-control" readonly id="no_rangka">                    
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
              <div class="col-sm-4">
                <input type="text" required class="form-control" readonly id="alamat">                                        
              </div>                  
              <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
              <div class="col-sm-4">
                <input type="text" required class="form-control" readonly id="tipe_ahm">                    
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">No. KTP</label>
              <div class="col-sm-4">
                <input type="text" required class="form-control" readonly id="no_ktp">                                        
              </div>                  
              <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
              <div class="col-sm-4">
                <input type="text" required class="form-control" readonly id="warna">                    
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Unit Diterima Konsumen*</label>
              <div class="col-sm-4">
                <!-- <input type="text" required class="form-control" id="tanggal" readonly name="tgl_terima_unit_ke_konsumen" autocomplete="off"> -->
                <input type="text" required class="form-control disabled_input_date" id="tanggal_bast" name="tgl_terima_unit_ke_konsumen" autocomplete="off">                                        
              </div>  
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Longitude</label>
              <div class="col-sm-4">
                <input type="text" required class="form-control" name="longitude" id="longitude">
              </div>  
              <label for="inputEmail3" class="col-sm-2 control-label">Latitude</label>
              <div class="col-sm-4">
                <input type="text" required class="form-control" name="latitude" id="latitude">
              </div>  
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">CSL*</label>
              <div class="col-sm-4">
		<?php if(date('Y-m-d')>='2021-12-22'){ ?>
		<select required class="form-control "name = "csl" placeholder='Silahkan Pilih'>
			<option></option>
			<option value ="Sangat Puas">5. Sangat Puas</option>
			<option value ="Puas">4. Puas</option>
			<option value ="Cukup Puas">3. Cukup Puas</option>
			<option value ="Tidak Puas">2. Tidak Puas</option>
			<option value ="Sangat Tidak Puas">1. Sangat Tidak Puas</option>
		</select>
		<?php }else{ ?>
                <input type="text" required class="form-control" name="csl">
		<?php } ?>
              </div>  
            </div>
            <!-- <div class="form-group"> -->
            <!--   <label for="inputEmail3" class="col-sm-2 control-label">Nama Penerima</label>
              <div class="col-sm-4">
                <input type="text" required class="form-control" name="nama_penerima">
              </div>  
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">No HP Penerima</label>
              <div class="col-sm-4">
                <input type="text" required class="form-control" name="no_hp_penerima">
              </div>  
            </div> -->
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Foto KTP</label>
              <div class="col-sm-4">
                <input type="file" name="foto_ktp_penerima" class="form-control" accept="image/x-png,image/gif,image/jpeg,image/jpg,image/bmp">                                       
              </div>  
              
              <div class="col-sm-1"></div>
              <div class="col-sm-5">
                <label for="explanation_bast"> Penjelasan BAST dari driver ke konsumen <br> Seperti: Unit, Buku, Jaringan Dealer, After Sales dll :</label><br>
                <input type="checkbox" id="explanation_bast" checked name="explanation_bast" value="1"> Ya, Sudah dilakukan
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Foto Serah Terima</label>
              <div class="col-sm-4">
                <input type="file" name="foto_serah_terima" class="form-control" accept="image/x-png,image/gif,image/jpeg,image/jpg,image/bmp">                                       
              </div>  
            </div>
        
      </div>
      <div class="modal-footer">
         <p align="center">
          <button class='btn btn-flat bg-blue' onclick="return confirm('Are you sure confirm this data ?')" type="submit">Simpan</button></p>
          </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $(".disabled_input_date").on('keydown paste focus', function(e){
        if(e.keyCode != 9) // ignore tab
            e.preventDefault();
    });

  function detail_popup(id_sales_order)
  {

    $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('dealer/konfirmasi_bast/detail_konfirmasi');?>",
               type:"POST",
              data:"id_sales_order="+id_sales_order,
               cache:false,
               success:function(msg){
                  $('#loading-status').hide();
                  data=msg.split("|");
                $("#no_sales_order").val(data[0]);
                $("#no_mesin").val(data[1]);
                $("#nama_konsumen").val(data[2]);
                $("#no_rangka").val(data[3]);
                $("#alamat").val(data[4]);
                $("#tipe_ahm").val(data[5]);
                $("#no_ktp").val(data[6]);
                $("#warna").val(data[7]);
                $("#longitude").val(data[8]);
                $("#latitude").val(data[9]);
               },
               statusCode: {
            500: function() {
              $('#loading-status').hide();
              alert('Terjadi Kesalahan Saat Menambahkan Data');
            }
          }
          });
  }
</script>
<!-- End Of Modal Detail -->
    <?php
    }
    ?>
  </section>
</div>



<script type="text/javascript">
function auto(){
  var tgl_js=document.getElementById("tgl").value; 
  $.ajax({
      url : "<?php echo site_url('dealer/konfirmasi_bast/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_konfirmasi_bast").val(data[0]);                
        $("#id_customer").val(data[1]);                
      }        
  })
}
function take_sales(){
  var id_karyawan_dealer = $("#id_karyawan_dealer").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/konfirmasi_bast/take_sales')?>",
      type:"POST",
      data:"id_karyawan_dealer="+id_karyawan_dealer,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");          
          //$("#no_polisi").html(msg);                                                    
          $("#kode_sales").val(data[0]);                                                    
          $("#nama_sales").val(data[1]);                                                    
      } 
  })
}


</script>