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
<body onload="cek()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Pengajuan Tagihan Keluar</li>
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
          <a href="h1/pengajuan_tagihan_keluar">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form class="form-horizontal" action="h1/pengajuan_tagihan_keluar/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tagihan Ke?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="tagihan_ke" id="tagihan_ke" onchange="cek_tagihan()">
                      <option value="">- choose -</option>
                      <option>Leasing</option>
                      <option>Dealer</option>
                    </select>
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Pengajuan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Tgl Pengajuan" id="tanggal" name="tgl_pengajuan">
                  </div>                  
                </div>
                <div class="form-group">                                
                  <span id="dealer_span">
                    <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" name="id_dealer">
                        <option value="">- choose -</option>
                        <?php 
                        $dealer = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");
                        foreach ($dealer->result() as $isi) {
                          echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md | $isi->nama_dealer</option>";
                        }
                        ?>                      
                      </select>
                    </div>              
                  </span>
                  <span id="leasing_span">
                    <label for="inputEmail3" class="col-sm-2 control-label">Leasing</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" id="id_finance_company" name="id_finance_company">
                        <option value="">- choose -</option>
                        <?php 
                        $finance = $this->m_admin->getSortCond("ms_finance_company","finance_company","ASC");
                        foreach ($finance->result() as $isi) {
                          echo "<option value='$isi->id_finance_company'>$isi->finance_company</option>";
                        }
                        ?>
                      </select>
                    </div>                                
                  </span>

                  <label for="inputEmail3" class="col-sm-2 control-label">Create By</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $this->session->userdata('username');  ?>" placeholder="Create By" readonly name="">
                    <input type="hidden" class="form-control" value="<?php echo $this->session->userdata('id_user');  ?>" placeholder="Create By" readonly name="">
                  </div>                  
                </div>                
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Tagihan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Total Tagihan"  name="total_tagihan">
                  </div>              
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Keterangan"  name="keterangan">
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
    }elseif($set=="detail"){
      $row = $dt_tagihan->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/pengajuan_tagihan_keluar">
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
            <form class="form-horizontal" action="h1/pengajuan_tagihan_keluar/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tagihan Ke?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="tagihan_ke">
                      <option value="<?php echo $row->tagihan_ke ?>"><?php echo $row->tagihan_ke ?></option>                      
                    </select>
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Pengajuan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->tgl_pengajuan ?>" placeholder="Tgl Pengajuan" id="tanggal" name="tgl_pengajuan">
                  </div>                  
                </div>
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer">
                      <option value="<?php echo $row->id_dealer ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->kode_dealer_md | $dt_cust->nama_dealer";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dealer = $this->m_admin->kondisiCond("ms_dealer","id_dealer != ".$row->id_dealer);                                                
                      foreach ($dealer->result() as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md | $isi->nama_dealer</option>";
                      }
                      ?>                      
                    </select>
                  </div>              
                  <label for="inputEmail3" class="col-sm-2 control-label">Create By</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $this->session->userdata('username');  ?>" placeholder="Create By" readonly name="">
                    <input type="hidden" class="form-control" value="<?php echo $this->session->userdata('id_user');  ?>" placeholder="Create By" readonly name="">
                  </div>                  
                </div>
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Leasing</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="id_finance_company">
                      <?php $fin    = $this->db->get("ms_finance_company");
                        if ($fin->num_rows()>0) {
                          echo '<option value="">- choose -</option>';
                          foreach ($fin->result() as $fn) { 
                            $select = $fn->id_finance_company==$row->id_finance_company?'selected':'';
                          ?>
                            <option value="<?= $fn->id_finance_company ?>" <?= $selected ?>><?= $id_finance_company ?></option>
                          <?php }
                        }
                      ?>
                    </select>
                  </div>                                
                </div> 
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Tagihan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->total_tagihan ?>" placeholder="Total Tagihan"  name="total_tagihan">
                  </div>              
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->keterangan ?>" placeholder="Keterangan"  name="keterangan">
                  </div>                  
                </div>               

                
              </div><!-- /.box-body -->
              
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
          <a href="h1/pengajuan_tagihan_keluar/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
              <th>Tagihan Ke</th>              
              <th>Nama</th>            
              <th>Tgl Penjualan</th>
              <th>Total Tagihan</th>
              <th>Create By</th>
              <th width="5%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_tagihan->result() as $row) {                                         
            $user = $this->m_admin->getByID("ms_user","id_user",$row->created_by);
            if($user->num_rows() > 0) $nama_user = $user->row()->username;
              else $nama_user = "";
            if($row->id_dealer != ''){
              $cek = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row()->nama_dealer;
            }elseif($row->id_finance_company != ''){
              $cek = $this->m_admin->getByID("ms_finance_company","id_finance_company",$row->id_finance_company)->row()->finance_company;
            }else{
              $cek = "";
            }
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->tagihan_ke</td>
              <td>$cek</td>                                         
              <td>$row->tgl_pengajuan</td>
              <td>$row->total_tagihan</td>                            
              <td>$nama_user</td>                            
              <td>                                
                <a href='h1/pengajuan_tagihan_keluar/view?id=$row->id_pengajuan_tagihan_keluar' class='btn btn-warning btn-flat btn-xs'>View</a>                                                
              </td>
            </tr>";                                      
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
function cek(){
  $("#dealer_span").hide();
  $("#leasing_span").hide();
}
function cek_tagihan(){
  var tagihan_ke = $("#tagihan_ke").val();
  if(tagihan_ke == 'Dealer'){
    $("#dealer_span").show();
    $("#leasing_span").hide();
  }else if(tagihan_ke == 'Leasing'){
    $("#dealer_span").hide();
    $("#leasing_span").show();
  }
}
</script>