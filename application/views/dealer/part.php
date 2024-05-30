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
<body onload="cek_oli()">
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Master Data</li>
    <li class="">Part</li>
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
          <a href="master/part">
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
            <form class="form-horizontal" action="master/part/save" method="post" enctype="multipart/form-data">                 
              <div class="box-body">
                <div class="form-group">
                  <input type="hidden" id="mode" value="new">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Part</label>
                  <div class="col-sm-3">
                    <input type="text" required class="form-control" id="id_part" placehOtherser="ID Part" name="id_part">
                  </div>                
                  <div class="col-sm-1">
                    <button type="button" class="btn btn-flat btn-primary" onclick="kirim_data_ptm()"><i class="fa fa-refresh"></i> Generate</button>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Part</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="inputEmail3" placehOtherser="Nama Part" name="nama_part">
                  </div>
                </div>            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Vendor</label>
                  <div class="col-sm-4">
                    <select name="kelompok_vendor" class="form-control">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_vendor->result() as $isi) {
                        echo "<option value='$isi->kelompok_vendor'>$isi->kelompok_vendor</option>";
                      }
                      ?>
                    </select>
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Satuan</label>
                  <div class="col-sm-4">
                    <select name="id_satuan" class="form-control">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_satuan->result() as $isi) {
                        echo "<option value='$isi->id_satuan'>$isi->satuan</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Min Stok</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputEmail3" placehOtherser="Min Stok" name="min_stok">
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Maks Stok</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputEmail3" placehOtherser="Maks Stok" name="maks_stok">
                  </div>
                </div>            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Safety Stok</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputEmail3" placehOtherser="Safety Stok" name="safety_stok">
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Min Sales</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputEmail3" placehOtherser="Min Sales" name="min_sales">
                  </div>
                </div>            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Part</label>
                  <div class="col-sm-4">
                    <select name="kelompok_part" class="form-control">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_part->result() as $isi) {
                        echo "<option value='$isi->kelompok_part'>$isi->kelompok_part</option>";
                      }
                      ?>
                    </select>
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis</label>
                  <div class="col-sm-4">
                    <input type="checkbox" class="flat-red" name="jenis[]" value="sim_part"> SIM Part <br>
                    <input type="checkbox" class="flat-red" name="jenis[]" value="fix"> Fix <br>
                    <input type="checkbox" class="flat-red" name="jenis[]" value="reguler"> Reguler
                  </div>
                </div>            
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Harga MD-Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="inputEmail3" placehOtherser="Harga MD-Dealer" name="harga_md_dealer">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Harga Dealer-End User</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="inputEmail3" placehOtherser="Harga Dealer-End User" name="harga_dealer_user">
                  </div>
                </div>            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">PNT</label>
                  <div class="col-sm-4">
                    <select name="pnt" class="form-control">
                      <option value="">- choose -</option>
                      <option>A</option>
                      <option>B</option>
                      <option>C</option>
                    </select>
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Fast/Slow</label>
                  <div class="col-sm-4">
                    <select name="fast_slow" class="form-control">
                      <option value="">- choose -</option>
                      <option value="F">Fast Moving</option>
                      <option value="S">Slow Moving</option>                      
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Import Lokal</label>
                  <div class="col-sm-4">
                    <select name="import_lokal" class="form-control">
                      <option value="">- choose -</option>
                      <option value="Y">Import</option>
                      <option value="N">Lokal</option>                      
                    </select>
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Rank</label>
                  <div class="col-sm-4">
                    <select name="rank" class="form-control">
                      <option value="">- choose -</option>
                      <option>A</option>
                      <option>B</option>                      
                      <option>C</option>
                      <option>D</option>
                      <option>E</option>
                      <option>F</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Current/Non Current</label>
                  <div class="col-sm-4">
                    <select name="current" class="form-control">
                      <option value="">- choose -</option>
                      <option value="C">Current</option>
                      <option value="N">Non Current</option>                      
                      <option value="O">Others</option>
                    </select>
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Important/Safety/Additional</label>
                  <div class="col-sm-4">
                    <select name="important" class="form-control">
                      <option value="">- choose -</option>
                      <option value="I">Important</option>
                      <option value="S">Safety</option>                      
                      <option value="A">Additional</option>                      
                      <option value="O">Others</option>                      
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Long/Short/Others</label>
                  <div class="col-sm-4">
                    <select name="long" class="form-control">
                      <option value="">- choose -</option>
                      <option value="L">Long</option>
                      <option value="S">Short</option>                      
                      <option value="O">Others</option>
                    </select>
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Engine/Frame/Electrical</label>
                  <div class="col-sm-4">
                    <select name="engine" class="form-control">
                      <option value="">- choose -</option>
                      <option value="E">Engine</option>
                      <option value="F">Frame</option>                      
                      <option value="L">Electrical</option>                      
                      <option value="O">Others</option>                      
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Recommend Part</label>
                  <div class="col-sm-4">
                    <select name="recommend_part" class="form-control">
                      <option value="">- choose -</option>
                      <option>Ya</option>
                      <option>Tidak</option>                                            
                    </select>
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">
                    <select name="status" class="form-control">
                      <option value="">- choose -</option>
                      <option>Active</option>
                      <option>Discountinued</option>                                            
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Part/Oli</label>            
                  <div class="col-sm-4">
                    <select name="part_oli" id="part_oli" onchange="cek_oli()" class="form-control">                      
                      <option>Part</option>
                      <option>Oli</option>                                            
                    </select>
                  </div>
                  <label id="qty_dus_lbl" for="field-1" class="col-sm-2 control-label">Qty Per Dus</label>            
                  <div class="col-sm-4">
                    <input type="text" name="qty_dus" id="qty_dus" class="form-control">
                  </div>                  
                </div> 
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Gambar (Maks 100  Kb)</label>            
                  <div class="col-sm-4">
                    <input type="file" name="gambar" class="form-control">
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Link Superseed</label>            
                  <div class="col-sm-4">
                    <input type="text" placehOtherser="Click to Browse" name="superseed" data-toggle="modal" data-target="#Seedmodal" id="superseed" readonly class="form-control">                    
                  </div>                  
                </div>                                                
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>                  
                </div>
                <button class="btn btn-flat btn-info btn-block" disabled>Detail PTM</button>
                <br>
                <span id="tampil_ptm"></span>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="edit"){
      $row = $dt_part->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/part">
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
            <form class="form-horizontal" action="master/part/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_part ?>">
              <input type="hidden" id="mode" value="edit">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Part</label>
                  <div class="col-sm-3">
                    <input type="text" value="<?php echo $row->id_part ?>" required class="form-control" readonly id="id_part" placehOtherser="ID Part" name="id_part">
                  </div>                
                  <div class="col-sm-1">
                    <button type="button" class="btn btn-flat btn-primary" onclick="kirim_data_ptm()"><i class="fa fa-refresh"></i> Generate</button>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Part</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $row->nama_part ?>" id="inputEmail3" placehOtherser="Nama Part" name="nama_part">
                  </div>
                </div>            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Vendor</label>
                  <div class="col-sm-4">
                    <select name="kelompok_vendor" class="form-control">
                      <option value="<?php echo $row->kelompok_vendor ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_kelompok_vendor","kelompok_vendor",$row->kelompok_vendor)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->kelompok_vendor;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_vendor = $this->m_admin->kondisi("ms_kelompok_vendor","kelompok_vendor != '$row->kelompok_vendor'");                                                
                      foreach ($dt_vendor->result() as $isi) {
                        echo "<option value='$isi->kelompok_vendor'>$isi->kelompok_vendor</option>";
                      }
                      ?>
                    </select>
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Satuan</label>
                  <div class="col-sm-4">
                    <select name="id_satuan" class="form-control">
                      <option value="<?php echo $row->id_satuan ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_satuan","id_satuan",$row->id_satuan)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->satuan;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_satuan = $this->m_admin->kondisi("ms_satuan","id_satuan != ".$row->id_satuan);                                                
                      foreach ($dt_satuan->result() as $isi) {
                        echo "<option value='$isi->id_satuan'>$isi->satuan</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Min Stok</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->min_stok ?>" id="inputEmail3" placehOtherser="Min Stok" name="min_stok">
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Maks Stok</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->maks_stok ?>" id="inputEmail3" placehOtherser="Maks Stok" name="maks_stok">
                  </div>
                </div>            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Safety Stok</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->safety_stok ?>" id="inputEmail3" placehOtherser="Safety Stok" name="safety_stok">
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Min Sales</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->min_sales ?>" class="form-control" id="inputEmail3" placehOtherser="Min Sales" name="min_sales">
                  </div>
                </div>            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Part</label>
                  <div class="col-sm-4">
                    <select name="kelompok_part" class="form-control">
                      <option value="<?php echo $row->kelompok_part ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_kelompok_part","kelompok_part",$row->kelompok_part)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->kelompok_part;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_k_part = $this->m_admin->kondisi("ms_kelompok_part","kelompok_part != '$row->kelompok_part'");                                                
                      foreach ($dt_k_part->result() as $isi) {
                        echo "<option value='$isi->kelompok_part'>$isi->kelompok_part</option>";
                      }
                      ?>
                    </select>
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis</label>
                  <div class="col-sm-4">
                    <input type="checkbox" class="flat-red" name="jenis[]" value="sim_part"> SIM Part <br>
                    <input type="checkbox" class="flat-red" name="jenis[]" value="fix"> Fix <br>
                    <input type="checkbox" class="flat-red" name="jenis[]" value="reguler"> Reguler
                  </div>
                </div>            
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Harga MD-Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" required value="<?php echo $row->harga_md_dealer ?>" class="form-control" id="inputEmail3" placehOtherser="Harga MD-Dealer" name="harga_md_dealer">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Harga Dealer-End User</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $row->harga_dealer_user ?>" id="inputEmail3" placehOtherser="Harga Dealer-End User" name="harga_dealer_user">
                  </div>
                </div>            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">PNT</label>
                  <div class="col-sm-4">
                    <select name="pnt" class="form-control">
                      <option value="<?php echo $row->pnt ?>"><?php echo $row->pnt ?></option>
                      <option>A</option>
                      <option>B</option>
                      <option>C</option>
                    </select>
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Fast/Slow</label>
                  <div class="col-sm-4">
                    <select name="fast_slow" class="form-control">
                      <option value="<?php echo $row->fast_slow ?>">
                        <?php 
                        if($row->fast_slow == "F"){
                          echo "Fast </option>                            
                            <option value='S'>Slow</option>";                      
                        }elseif($row->fast_slow == "S"){
                          echo "Slow </option>                            
                            <option value='F'>Fast</option>";                      
                        }
                        ?>
                      </option>
                      <option>Fast</option>
                      <option>Slow</option>                      
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Import Lokal</label>
                  <div class="col-sm-4">
                    <select name="import_lokal" class="form-control">
                      <option value="<?php echo $row->import_lokal ?>">
                        <?php 
                        if($row->import_lokal == "Y"){
                          echo "Import </option>                            
                            <option value='N'>Lokal</option>";                      
                        }elseif($row->import_lokal == "N"){
                          echo "Lokal </option>                            
                            <option value='Y'>Import</option>";                      
                        }
                        ?>                          
                      </option>                      
                    </select>
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Rank</label>
                  <div class="col-sm-4">
                    <select name="rank" class="form-control">
                      <option value="<?php echo $row->rank ?>"><?php echo $row->rank ?></option>
                      <option>A</option>
                      <option>B</option>                      
                      <option>C</option>
                      <option>D</option>
                      <option>E</option>
                      <option>F</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Current/Non Current/Others</label>
                  <div class="col-sm-4">
                    <select name="current" class="form-control">
                      <option value="<?php echo $row->current ?>">
                        <?php 
                        if($row->current == "C"){
                          echo "Current </option>                            
                            <option value='N'>Non Current</option>                      
                            <option value='O'>Others</option>";                      
                        }elseif($row->current == "N"){
                          echo "Non Current </option>                            
                            <option value='C'>Current</option>                      
                            <option value='O'>Others</option>";                      
                        }elseif($row->important == "O"){
                          echo "Other </option>                            
                            <option value='C'>Current</option>                      
                            <option value='N'>Non Current</option>";                      
                        }
                        ?>                          
                      </option>                      
                    </select>
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Important/Safety/Additional</label>
                  <div class="col-sm-4">
                    <select name="important" class="form-control">
                      <option value="<?php echo $row->important ?>">
                        <?php 
                        if($row->important == "I"){
                          echo "Important </option>                            
                            <option value='S'>Safety</option>                      
                            <option value='A'>Additional</option>";                      
                        }elseif($row->important == "S"){
                          echo "Safety </option>                            
                            <option value='I'>Important</option>                      
                            <option value='A'>Additional</option>";                      
                        }elseif($row->important == "A"){
                          echo "Additional </option>                            
                            <option value='I'>Important</option>                      
                            <option value='S'>Safety</option>";                      
                        }
                        ?>                      
                      </option>                                      
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Long/Short/Others</label>
                  <div class="col-sm-4">
                    <select name="long" class="form-control">
                      <option value="<?php echo $row->long ?>">
                        <?php 
                        if($row->long == "L"){
                          echo "Long </option>                            
                            <option value='S'>Short</option>                      
                            <option value='O'>Others</option>";                      
                        }elseif($row->long == "S"){
                          echo "Short </option>                            
                            <option value='L'>Long</option>                      
                            <option value='O'>Others</option>";                      
                        }elseif($row->long == "O"){
                          echo "Others </option>                            
                            <option value='L'>Long</option>                      
                            <option value='S'>Short</option>";                      
                        }
                        ?>                      
                      </option>
                      <option>Long</option>
                      <option>Short</option>                      
                      <option>Others</option>
                    </select>
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Engine/Frame/Electrical</label>
                  <div class="col-sm-4">
                    <select name="engine" class="form-control">
                      <option value="<?php echo $row->engine ?>">
                        <?php 
                        if($row->engine == "E"){
                          echo "Engine </option>                            
                            <option value='F'>Frame</option>                      
                            <option value='L'>Electrical</option>";                      
                        }elseif($row->engine == "F"){
                          echo "Frame </option>                            
                            <option value='E'>Engine</option>                      
                            <option value='L'>Electrical</option>";                      
                        }elseif($row->engine == "L"){
                          echo "Electrical </option>                            
                            <option value='E'>Engine</option>                      
                            <option value='F'>Frame</option>";                      
                        }
                        ?>                      
                      </option>                      
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Recommend Part</label>
                  <div class="col-sm-4">
                    <select name="recommend_part" class="form-control">
                      <option value="<?php echo $row->recommend_part ?>"><?php echo $row->recommend_part ?></option>
                      <option>Ya</option>
                      <option>Tidak</option>                                            
                    </select>
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">
                    <select name="status" class="form-control">
                      <option value="<?php echo $row->status ?>"><?php echo $row->status ?></option>
                      <option>Active</option>
                      <option>Discountinued</option>                                            
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Part/Oli</label>            
                  <div class="col-sm-4">
                    <select name="part_oli" id="part_oli" onchange="cek_oli()" class="form-control">                      
                      <option value="<?php echo $row->part_oli ?>"><?php echo $row->part_oli ?></option>
                      <option>Part</option>                                            
                      <option>Oli</option>                                            
                    </select>
                  </div>
                  <label id="qty_dus_lbl" for="field-1" class="col-sm-2 control-label">Qty Per Dus</label>            
                  <div class="col-sm-4">
                    <input type="text" name="qty_dus" id="qty_dus" value="<?php echo $row->qty_dus ?>" class="form-control" >
                  </div>                  
                </div> 
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Gambar (Maks 100  Kb)</label>            
                  <div class="col-sm-4">
                    <input type="file" name="gambar" class="form-control">
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Link Superseed</label>            
                  <div class="col-sm-4">
                    <input type="text" placehOtherser="Click to Browse" name="superseed" value="<?php echo $row->superseed ?>" data-toggle="modal" data-target="#Seedmodal" id="superseed" readonly class="form-control">                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->active=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="active" value="1">                      
                      <?php } ?>
                      Active
                    </div>
                  </div>                  
                </div>                   
                <button class="btn btn-flat btn-info btn-block" disabled>Detail PTM</button>
                <br>
                <span id="tampil_ptm"></span>                             
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Update</button>                
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="upload"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/part">
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
            <form class="form-horizontal" action="master/part/import_db" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Choose File</label>
                  <div class="col-sm-10">
                    <input type="file" accept=".PMP" required class="form-control" autofocus name="userfile">                    
                  </div>                  
                </div>                                                                                                      
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to import this data?')" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Start Upload</button>                                  
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
          <a href="master/part/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          <a class="btn bg-maroon btn-flat margin" href="master/part/upload"><i class="fa fa-upload"></i> Upload PMP</a>                  
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
              <th>ID Part</th>
              <th>Nama Part</th>              
              <th>Kel. Vendor</th>
              <th>Satuan</th>
              <th>Min Stok</th>
              <th>Maks Stok</th>
              <th>Safety Stok</th>
              <th>Kel. Part</th>
              <th width="5%">Active</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_part->result() as $row) {       
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->id_part</td>              
              <td>$row->nama_part</td>              
              <td>$row->kelompok_vendor</td>              
              <td>$row->satuan</td>              
              <td>$row->min_stok</td>              
              <td>$row->maks_stok</td>              
              <td>$row->safety_stok</td>              
              <td>$row->kelompok_part</td>              
              <td>$active</td>              
              <td>";
              ?>
                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/part/delete?id=<?php echo $row->id_part ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/part/edit?id=<?php echo $row->id_part ?>'><i class='fa fa-edit'></i></a>
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
<div class="modal fade" id="Seedmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Cari Data Part
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example3" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="10%"></th>              
              <th>Kode Part</th>
              <th>Nama Part</th>                                                  
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          $dt_part = $this->m_admin->getSortCond("ms_part","nama_part","ASC");
          foreach ($dt_part->result() as $ve2) {
            echo "
            <tr>"; ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="chooseitem('<?php echo $ve2->id_part; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
              </td>
              <?php echo "
              <td>$ve2->id_part</td>
              <td>$ve2->nama_part</td>";
              ?>                         
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

<div class="modal fade" id="Ptmmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search PTM
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>Tipe Motor</th>
              <th>Deskripsi</th>                                                                                                
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          foreach ($dt_ptm->result() as $ve2) {
            $sql = $this->db->query("SELECT * FROM ms_ptm WHERE tipe_motor = '$ve2->tipe_marketing'");
            if($sql->num_rows() > 0){
              $ve = $sql->row();
              $tipe = $ve->tipe_motor;
              $desk = $ve->deskripsi;
            }else{
              $tipe = "";
              $desk = "";
            }
            echo "
            <tr>
              <td>$no</td>
              <td>$tipe</td>
              <td>$desk</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="chooseptm('<?php echo $ve2->id_pvtm; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
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
function cek_oli(){
  var cek = $("#part_oli").val();
  if(cek == ""){
    $("#qty_dus").hide();
    $("#qty_dus_lbl").hide();
  }else if(cek == 'Oli'){
    $("#qty_dus").show();
    $("#qty_dus_lbl").show();
  }else if(cek == 'Part'){
    $("#qty_dus").hide();
    $("#qty_dus_lbl").hide();
  }
  kirim_data_ptm();
}
function chooseptm(id_pvtm){
  document.getElementById("id_pvtm").value = id_pvtm; 
  cek_item();
  $("#Ptmmodal").modal("hide");
}
function cek_item(){  
  var id_pvtm      = $("#id_pvtm").val();                       
  $.ajax({
      url: "<?php echo site_url('master/part/cek_item')?>",
      type:"POST",
      data:"id_pvtm="+id_pvtm,
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            $("#tipe_motor").val(data[1]);                
            $("#deskripsi").val(data[2]);                            
          }else{
            alert(data[0]);
          }
      } 
  })
}
function kirim_data_ptm(){    
  $("#tampil_ptm").show();
  var id_part = document.getElementById("id_part").value;   
  var mode = document.getElementById("mode").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and Otherser
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_part="+id_part+"&mode="+mode;                           
     xhr.open("POST", "master/part/t_part", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_ptm").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }
}
function simpan_ptm(){
  var id_part   = document.getElementById("id_part").value;  
  var id_pvtm    = document.getElementById("id_pvtm").value;     
  //alert(id_po);
  if (id_part == "" || id_pvtm == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('master/part/save_ptm')?>",
          type:"POST",
          data:"id_part="+id_part+"&id_pvtm="+id_pvtm,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="ok"){
                  kirim_data_ptm();
                  kosong();                
              }else{
                  alert("Gagal Simpan, PTM ini sudah dipilih");
                  kosong();                  
              }                
          }
      })    
  }
}
function hapus_ptm(a){ 
    var id_part_detail  = a;       
    $.ajax({
        url : "<?php echo site_url('master/part/delete_ptm')?>",
        type:"POST",
        data:"id_part_detail="+id_part_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_ptm();
            }
        }
    })
}
function kosong(args){
  $("#id_pvtm").val("");
  $("#tipe_motor").val("");     
  $("#deskripsi").val("");     
}
function chooseitem(id_part){
  document.getElementById("superseed").value = id_part;   
  $("#Seedmodal").modal("hide");
}
</script>