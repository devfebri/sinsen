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
          <a href="dealer/retur_bastd">
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
            <form class="form-horizontal" action="dealer/retur_bastd" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Retur</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="No Retur" name="nama_konsumen">                                        
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Retur</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Tanggal Retur" name="nama_konsumen">                    
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
                      <th>Nama Konsumen</th>              
                      <th>Alamat</th>              
                      <th>No Mesin</th>              
                      <th>No Rangka</th>              
                      <th>Tanggal Pembelian</th>              
                      <th>Tanggal Terima Unit</th>
                      <th>Tanggal Terima STNK di Dealer</th>
                      <th>Tanggal Terima BPKB di Dealer</th>
                      <th>Tanggal Terima STNK di Konsumen</th>
                      <th>Tanggal Terima BPKB di Konsumen</th>
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
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="dealer/retur_bastd/add">
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
         <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>                      
              <th>Nama Konsumen</th>              
              <th>Alamat</th>              
              <th>No Mesin</th>              
              <th>No Rangka</th>              
              <th>Tanggal Pembelian</th>              
              <th>Tanggal Terima Unit</th>
              <th>Tanggal Terima STNK di Dealer</th>
              <th>Tanggal Terima BPKB di Dealer</th>
              <th>Tanggal Terima STNK di Konsumen</th>
              <th>Tanggal Terima BPKB di Konsumen</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_mot->result() as $row) {     
            $cek1 = $this->db->query("SELECT tr_penerimaan_unit_dealer.tgl_penerimaan FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_penerimaan_unit_dealer
              ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
              WHERE tr_penerimaan_unit_dealer_detail.no_mesin = '$row->no_mesin'");          
            if($cek1->num_rows() > 0){
              $d = $cek1->row();
              $tgl_penerimaan = $d->tgl_penerimaan;
            }else{
              $tgl_penerimaan = "";
            }

            $cek2 = $this->db->query("SELECT tr_konfirmasi_dokumen.tgl_terima FROM tr_konfirmasi_dokumen INNER JOIN tr_konfirmasi_dokumen_detail
              ON tr_konfirmasi_dokumen.no_serah_terima = tr_konfirmasi_dokumen_detail.no_serah_terima 
              WHERE tr_konfirmasi_dokumen_detail.no_mesin = '$row->no_mesin'");          
            if($cek2->num_rows() > 0){
              $d = $cek2->row();
              $tgl_stnk = $d->tgl_terima;
              $tgl_bpkb = $d->tgl_terima;
            }else{
              $tgl_stnk = "";
              $tgl_bpkb = "";
            }
            echo "
            <tr>
              <td>$row->nama_konsumen</td>
              <td>$row->alamat</td>
              <td>$row->no_mesin</td>
              <td>$row->no_rangka</td>
              <td>$row->tgl_cetak_invoice</td>                                          
              <td>$tgl_penerimaan</td>                                          
              <td>$tgl_stnk</td>                                          
              <td>$tgl_bpkb</td>                                          
              <td></td>                                          
              <td></td>                                                         
            </tr>
            ";
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
      url : "<?php echo site_url('dealer/retur_bastd/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_retur_bastd").val(data[0]);                
        $("#id_customer").val(data[1]);                
      }        
  })
}
function take_sales(){
  var id_karyawan_dealer = $("#id_karyawan_dealer").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/retur_bastd/take_sales')?>",
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