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
<body onload="auto()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Business Control</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    <?php 
    if($set=="approve"){
      $row = $dt_proposal->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/proposal_dealer">
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
            <form class="form-horizontal" action="h1/proposal_dealer/save_approval" method="post" enctype="multipart/form-data">
              <div class="box-body">                    
                <div class="form-group">
                  <input type="hidden" name="id_proposal" value="<?php echo $row->id_proposal ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Program</label>
                  <div class="col-sm-10">
                    <input type="text" readonly value="<?php echo $row->nama_program ?>" class="form-control" placeholder="Nama Program" name="nama_program">                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tema Program</label>
                  <div class="col-sm-10">
                    <input type="text" readonly value="<?php echo $row->tema_program ?>" class="form-control" placeholder="Tema Program" name="tema_program">                                        
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Mulai</label>
                  <div class="col-sm-4">
                    <input type="text" readonly value="<?php echo $row->tgl_mulai ?>" class="form-control" id="tanggal" placeholder="Tanggal Mulai" name="tgl_mulai">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Selesai</label>
                  <div class="col-sm-4">
                    <input type="text" readonly value="<?php echo $row->tgl_selesai ?>" class="form-control" placeholder="Tanggal Selesai" name="tgl_selesai">
                  </div>                  
                </div>                                                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sumber Dana</label>
                  <div class="col-sm-4">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <input type="checkbox" <?php if($row->ahm=='on') echo 'checked' ?> disabled name="ahm"> AHM
                      </div>
                      <input type="text" class="form-control pull-right" <?php if($row->ahm!='on') echo 'disabled' ?> name="ahm_text">
                    </div>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <input type="checkbox" <?php if($row->md=='on') echo 'checked' ?> disabled name="md"> MD
                      </div>
                      <input type="text" class="form-control pull-right" <?php if($row->md!='on') echo 'disabled' ?> name="md_text">
                    </div>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <input type="checkbox" <?php if($row->dealer=='on') echo 'checked' ?> disabled name="dealer"> Dealer
                      </div>
                      <input type="text" class="form-control pull-right" <?php if($row->dealer!='on') echo 'disabled' ?> name="dealer_text">
                    </div>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <input type="checkbox" <?php if($row->lainnya=='on') echo 'checked' ?> disabled name="lainnya"> Lainnya
                      </div>
                      <input type="text" <?php if($row->lainnya!='on') echo 'disabled' ?> class="form-control pull-right" name="lainnya_text">
                    </div>
                    <!-- <input type="text" class="form-control" placeholder="Sumber Dana" name="sumber_dana">                     -->
                    
                    
                    
                    
                  </div>                                
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Terakhir LPJ</label>
                  <div class="col-sm-2">
                    <input type="text" id="tanggal2" class="form-control" placeholder="Tgl Terakhir LPJ" name="tgl_lpj">                    
                  </div>                                
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Juklak</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="No Juklak" name="no_juklak">                    
                 </div>                  
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Leasing Pendukung</label>
                  <div class="col-sm-10">
                    <?php 
                    $isi = explode(',', $row->id_leasing_pendukung);
                    $hasil='';
                    foreach ($isi as $amb) {      
                      $cek = $this->m_admin->getByID("ms_finance_company","id_finance_company",$amb)->row();
                      if($hasil==''){
                        $hasil = $cek->finance_company;       
                      }else{
                        $hasil = $hasil.", ".$cek->finance_company;       
                      }
                      
                    }                                                                                     
                    ?>
                    <input type="text" readonly value="<?php echo $hasil ?>" class="form-control" placeholder="Leasing Pendukung" name="leasing">                    
                  </div>                  
                </div>
                <button disabled class="btn btn-block btn-warning btn-flat">Rincian Biaya</button>
                <br>
                <table class="table table-bordered table-hover">
                  <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>PPN</th>
                    <th>Keterangan</th>
                  </tr>
                  <?php  
                  $sql = $this->db->query("SELECT * FROM tr_proposal_dealer_rincian WHERE id_proposal = '$row->id_proposal'");
                  foreach ($sql->result() as $isi) {
                    echo "
                      <tr>
                        <td>$isi->item</td>
                        <td>$isi->qty</td>
                        <td>$isi->harga</td>
                        <td>$isi->ppn</td>
                        <td>$isi->keterangan</td>
                      </tr>
                    ";
                  }
                  ?>                  
                </table>                    
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to approve all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Approve</button>                  
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="reject"){
      $row = $dt_proposal->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/proposal_dealer">
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
            <form class="form-horizontal" action="h1/proposal_dealer/save_reject" method="post" enctype="multipart/form-data">
              <div class="box-body">                    
                <div class="form-group">
                  <input type="hidden" name="id_proposal" value="<?php echo $row->id_proposal ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Program</label>
                  <div class="col-sm-10">
                    <input type="text" readonly value="<?php echo $row->nama_program ?>" class="form-control" placeholder="Nama Program" name="nama_program">                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tema Program</label>
                  <div class="col-sm-10">
                    <input type="text" readonly value="<?php echo $row->tema_program ?>" class="form-control" placeholder="Tema Program" name="tema_program">                                        
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Mulai</label>
                  <div class="col-sm-4">
                    <input type="text" readonly value="<?php echo $row->tgl_mulai ?>" class="form-control" id="tanggal" placeholder="Tanggal Mulai" name="tgl_mulai">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Selesai</label>
                  <div class="col-sm-4">
                    <input type="text" readonly value="<?php echo $row->tgl_selesai ?>" class="form-control" placeholder="Tanggal Selesai" name="tgl_selesai">
                  </div>                  
                </div>                                                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sumber Dana</label>
                  <div class="col-sm-4">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <input type="checkbox" <?php if($row->ahm=='on') echo 'checked' ?> disabled name="ahm"> AHM
                      </div>                      
                    </div>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <input type="checkbox" <?php if($row->md=='on') echo 'checked' ?> disabled name="md"> MD
                      </div>                      
                    </div>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <input type="checkbox" <?php if($row->dealer=='on') echo 'checked' ?> disabled name="dealer"> Dealer
                      </div>                      
                    </div>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <input type="checkbox" <?php if($row->lainnya=='on') echo 'checked' ?> disabled name="lainnya"> Lainnya
                      </div>                      
                    </div>
                    <!-- <input type="text" class="form-control" placeholder="Sumber Dana" name="sumber_dana">                     -->
                    
                    
                    
                    
                  </div>                                
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Leasing Pendukung</label>
                  <div class="col-sm-10">
                    <?php 
                    $isi = explode(',', $row->id_leasing_pendukung);
                    $hasil='';
                    foreach ($isi as $amb) {      
                      $cek = $this->m_admin->getByID("ms_finance_company","id_finance_company",$amb)->row();
                      if($hasil==''){
                        $hasil = $cek->finance_company;       
                      }else{
                        $hasil = $hasil.", ".$cek->finance_company;       
                      }
                      
                    }                                                                                     
                    ?>
                    <input type="text" readonly value="<?php echo $hasil ?>" class="form-control" placeholder="Leasing Pendukung" name="leasing">                    
                  </div>                  
                </div>
                <button disabled class="btn btn-block btn-warning btn-flat">Rincian Biaya</button>
                <br>
                <table class="table table-bordered table-hover">
                  <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>PPN</th>
                    <th>Keterangan</th>
                  </tr>
                  <?php  
                  $sql = $this->db->query("SELECT * FROM tr_proposal_dealer_rincian WHERE id_proposal = '$row->id_proposal'");
                  foreach ($sql->result() as $isi) {
                    echo "
                      <tr>
                        <td>$isi->item</td>
                        <td>$isi->qty</td>
                        <td>$isi->harga</td>
                        <td>$isi->ppn</td>
                        <td>$isi->keterangan</td>
                      </tr>
                    ";
                  }
                  ?>                  
                </table>
                <div class="box-body">                                                      
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alasan Reject</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" placeholder="Alasan Reject" name="alasan_reject">                    
                    </div>                                
                  </div>
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to reject all data?')" name="save" value="save" class="btn btn-danger btn-flat"><i class="fa fa-save"></i> Reject</button>                  
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
              <th width="5%">No</th>                          
              <th>Nama Program</th>
              <th>Tema</th>
              <th>Tgl Mulai</th>              
              <th>Tgl Selesai</th>            
              <th>Dealer</th>              
              <th width='18%'>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_proposal->result() as $row) {                                         
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->nama_program</td>                           
              <td>$row->tema_program</td>                           
              <td>$row->tgl_mulai</td>                           
              <td>$row->tgl_selesai</td>                            
              <td>$row->nama_dealer</td>                                          
              <td>
                  <a href='h1/proposal_dealer/approve?id=$row->id_proposal' class='btn btn-primary btn-flat btn-xs'>Approve</a>
                  <a href='h1/proposal_dealer/reject?id=$row->id_proposal' class='btn btn-danger btn-flat btn-xs'>Reject</a>
                  <a onclick=\"return confirm('Are you sure to update data?')\" href=\"h1/proposal_dealer/revisi?id=$row->id_proposal\" class=\"btn btn-warning btn-flat btn-xs\">Revisi</a>
              </td>                                         
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
  var id = "1";
  $.ajax({
      url : "<?php echo site_url('h1/report_promosi/cari_id')?>",
      type:"POST",
      data:"id="+id,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_report_promosi").val(data[0]);
        //kirim_data();             
      }        
  })
}
function ambil_noreg(){
  var no_reg = document.getElementById("no_reg").value; 
  $.ajax({
      url : "<?php echo site_url('h1/report_promosi/ambil_noreg')?>",
      type:"POST",
      data:"no_reg="+no_reg,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#lokasi").val(data[0]);
        $("#tgl_mulai").val(data[1]);
        $("#tgl_selesai").val(data[2]);
        //kirim_data();             
      }        
  })
}
</script>

<script type="text/javascript" id="lancar">
$(document).ready(function(){
  $('.add').click(function(){
    var start=$('#hide').val();
    var sumall=Number(start)+1;
    $('#hide').val(sumall);
    var tbody=$('#append');
    $('<tr><td><input type="file" name="nama_file[]" class="form-control"></td><td><input type="text" name="ket[]" placeholder="Ket" class="form-control"></td><td><button type="button" class="btn btn-danger remove">-</button type="button"></td></tr>').appendTo(tbody);
    $('.remove').click(function(){     
      $(this).parents('tr').remove();      
    });
  });  
});
</script>