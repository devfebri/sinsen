<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 40px;
  padding-left: 5px;
  padding-right: 5px;  
}
.isi_combo{   
  height: 30px;
  border:1px solid #ccc;
  padding-left:1.5px;
}
</style>
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Bussiness Control</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="insert"){
    ?>
    <body onload="getDetail(null)">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/pengeluaran_gift">
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
            <form class="form-horizontal" action="h1/pengeluaran_gift/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No DO" name="no_do">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Invoice</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Invoce" name="no_invoice">
                  </div>                  
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal DO</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal" placeholder="Tanggal DO" name="tanggal_do">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Invoice</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal2" placeholder="Tanggal Invoice" name="tanggal_invoice">
                  </div>                  
                </div>                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Surat Jalan" name="no_surat_jalan">
                  </div>                                    
                </div>                                                         
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal1" placeholder="Tanggal Surat Jalan" name="tanggal_surat_jalan">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer">
                        <?php $dt_dealer=$this->db->query("SELECT * FROM ms_dealer order by nama_dealer") ?>
                        <?php 
                            if ($dt_dealer->num_rows() >0) {
                              echo"<option value=''>- choose -</option>";
                              foreach ($dt_dealer->result() as $rs) {
                                echo "<option value='$rs->id_dealer'>$rs->nama_dealer</option>";
                              }
                            }
                         ?>
                    </select>
                  </div>                  
                </div>                    

                <button class="btn btn-info btn-flat btn-sm btn-block" disabled>Detail Item</button>
                <div id="showDetail"></div>
                <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pembuat (Marketing)</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Pembuat" name="nama_pembuat_marketing">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Penyerah</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Penyerah" name="nama_penyerah">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Penerima</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Penerima" name="nama_penerima">
                  </div>                                    
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
          <a href="h1/pengeluaran_gift/add">            
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
              <th>No DO</th>             
              <th>Tgl DO</th>               
              <th>No Invoice</th>              
              <th>Tgl Invoice</th>
              <th>NO SJ</th>                            
              <th>Dealer</th>                            
              <th width="12%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
           $no=1; 
           foreach($data->result() as $row) {                                         
            $edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
          echo "          
            <tr>
              <td>$no</td>                           
              <td>$row->no_do
              </td>                            
              <td>$row->tanggal_do</td>              
              <td>$row->no_invoice</td>
              <td>$row->tanggal_invoice</td>
              <td>$row->no_surat_jalan</td>
              <td>$row->nama_dealer</td>
              <td align='center'>
                <a href='h1/pengeluaran_gift/edit?id=$row->id_pengeluaran' $edit type='button' class='btn btn-flat btn-warning btn-xs'><i class='fa fa-edit'></i> Edit</a>                
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
    elseif($set=="edit"){

    ?>
    <body onload="getDetail(<?=$row->id_pengeluaran?>)">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/pengeluaran_gift">
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
            <form class="form-horizontal" action="h1/pengeluaran_gift/save_edit" method="post" enctype="multipart/form-data">   
            <input type="hidden" name="id_pengeluaran" value="<?= $row->id_pengeluaran?>">           
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No DO" name="no_do" value="<?= $row->no_do?>">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Invoice</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Invoce" name="no_invoice" value="<?= $row->no_invoice?>">
                  </div>                  
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal DO</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal" placeholder="Tanggal DO" name="tanggal_do" value="<?= $row->tanggal_do?>">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Invoice</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal2" placeholder="Tanggal Invoice" name="tanggal_invoice" value="<?= $row->tanggal_invoice?>">
                  </div>                  
                </div>                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Surat Jalan" name="no_surat_jalan" value="<?= $row->no_surat_jalan?>"> 
                  </div>                                    
                </div>                                                         
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal1" placeholder="Tanggal Surat Jalan" name="tanggal_surat_jalan" value="<?= $row->tanggal_surat_jalan?>">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer">
                        <?php $dt_dealer=$this->db->query("SELECT * FROM ms_dealer order by nama_dealer") ?>
                        <?php 
                            if ($dt_dealer->num_rows() >0) {
                              foreach ($dt_dealer->result() as $rs) {
                                if ($rs->id_dealer==$row->id_dealer) {
                                  $selected='selected';
                                }else{
                                  $selected='';
                                }
                                echo "<option value='$rs->id_dealer' $selected>$rs->nama_dealer</option>";
                              }
                            }
                         ?>
                    </select>
                  </div>                  
                </div>                    

                <button class="btn btn-info btn-flat btn-sm btn-block" disabled>Detail Item</button>
                <div id="showDetail"></div>
                <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pembuat (Marketing)</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Pembuat" name="nama_pembuat_marketing" value="<?= $row->nama_pembuat_marketing?>">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Penyerah</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Penyerah" name="nama_penyerah" value="<?= $row->nama_penyerah?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Penerima</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Penerima" name="nama_penerima" value="<?= $row->nama_penerima?>">
                  </div>                                    
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
    <?php } ?>
  </section>
</div>



<script type="text/javascript">
  function getDetail(a)
{
  var value={id:a}
  $.ajax({
       beforeSend: function() { $('#loading-status').show(); },
       url:"<?php echo site_url('h1/pengeluaran_gift/getDetail')?>",
       type:"POST",
       data:value,
       cache:false,
       success:function(html){
          $('#loading-status').hide();
          $('#showDetail').html(html);
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