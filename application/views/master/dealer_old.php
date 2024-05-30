<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Dealer</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="insert"){
    ?>
    <body onload="auto()">

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/dealer">
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
            <form class="form-horizontal" action="master/dealer/save" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                                                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" required  id="inputEmail3" placeholder="Nama Dealer" name="nama_dealer">
                  </div>
                </div>                 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer MD</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Kode Dealer MD" name="kode_dealer_md">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer AHM</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Kode Dealer AHM" name="kode_dealer_ahm">
                  </div>
                  <!--label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer Link AHM</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Kode Dealer Link AHM" name="kode_dealer_ahm_link">
                  </div-->
                </div>    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Jaringan</label>            
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="h1" value="1" checked>
                      H1
                    </div>
                  </div>                  
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="h2" value="1" checked>
                      H2
                    </div>
                  </div>                  
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="h3" value="1" checked>
                      H3
                    </div>
                  </div>                  
                </div>                                                                                                                           
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" id="inputEmail3" placeholder="Alamat" name="alamat">
                  </div>
                </div>                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Telp</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control"  required id="inputEmail3" placeholder="No.Telp" name="no_telp">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                  <div class="col-sm-4">
                    <input type="hidden" readonly name="id_kelurahan" id="id_kelurahan">                      
                    <input type="text" readonly name="kelurahan" data-toggle="modal" placeholder="Kelurahan" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">                               
                  </div>                  
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">NPWP</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="inputEmail3" placeholder="NPWP" name="npwp">
                  </div>                            
                  <label for="inputEmail3" class="col-sm-2 control-label">PKP</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="pkp">
                      <option value="">- choose - </option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-4">
                    <input type="email" required class="form-control" id="inputEmail3" placeholder="Email" name="email">
                  </div>            
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Harga</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="id_kelompok_harga">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_kelompok_harga->result() as $val) {
                        echo "
                        <option value='$val->id_kelompok_harga'>$val->kelompok_harga</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>                  
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Top Unit</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="inputEmail3" placeholder="Top Unit" name="top_unit">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Top Part</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="inputEmail3" placeholder="Top Part" name="top_part">
                  </div>            
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pemilik</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="inputEmail3" placeholder="Pemilik" name="pemilik">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">PIC</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="inputEmail3" placeholder="PIC" name="pic">
                  </div>            
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Diskon</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="tipe_diskon">
                      <option value="">- choose -</option>
                      <option>Rupiah</option>
                      <option>Persen</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Fixed Order</label>
                  <div class="col-sm-4">
                    <input type="text" required onkeypress="return number_only(event)" class="form-control" id="inputEmail3" placeholder="Diskon Fixed Order" name="diskon_fixed_order">
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Reguler</label>
                  <div class="col-sm-4">
                    <input type="text" required onkeypress="return number_only(event)" class="form-control" id="inputEmail3" placeholder="Tipe Reguler" name="diskon_reguler">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Hotline</label>
                  <div class="col-sm-4">
                    <input type="text" required onkeypress="return number_only(event)" class="form-control" id="inputEmail3" placeholder="Diskon Hotline" name="diskon_hotline">
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Urgent</label>
                  <div class="col-sm-4">
                    <input type="text" required onkeypress="return number_only(event)" class="form-control" id="inputEmail3" placeholder="Tipe Urgent" name="diskon_urgent">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer Cabang Sinar Sentosa</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="dealer_cb_ssp">
                      <option value="">- choose - </option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>            
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer Group Sinar Sentosa</label>
                  <div class="col-sm-4">
                    <select class="form-control"  required name="dealer_group_ssp">
                      <option value="">- choose - </option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Bisa Pilih Unit DO</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="bisa_pilih_unit_do">
                      <option value="">- choose - </option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Maks Penitipan Unit</label>
                  <div class="col-sm-4">
                    <input type="text" required onkeypress="return number_only(event)" class="form-control" id="inputEmail3" placeholder="Maks Penitipan Unit" name="maks_penitipan_unit">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Maks Hari Penitipan</label>
                  <div class="col-sm-4">
                    <input type="text" required onkeypress="return number_only(event)" class="form-control" id="inputEmail3" placeholder="Maks Hari Penitipan" name="maks_hari_penitipan">
                  </div>            
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Harus PDI</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="hrs_pdi">
                      <option value="">- choose -</option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya PDI</label>
                  <div class="col-sm-4">
                    <input type="text" required onkeypress="return number_only(event)" class="form-control" id="inputEmail3" placeholder="Biaya PDI" name="biaya_pdi">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kirim Samsat</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="kirim_samsat">
                      <option value="">- choose -</option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>                            
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer Financing</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="dealer_financing">
                      <option value="">- choose -</option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang Unit</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="gudang_unit">
                      <option value="">- choose -</option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang Part</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="gudang_part">
                      <option value="">- choose -</option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang Sendiri</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="gudang_sendiri">
                      <option value="">- choose -</option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>     
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Limit PO</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="inputEmail3" placeholder="Limit PO" name="limit_po">
                  </div>
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pimpinan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="inputEmail3" placeholder="Nama Pimpinan" name="pimpinan">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Singkat Perusahaan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="inputEmail3" placeholder="Nama Singkat Perusahaan" name="nama_kecil">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer POS?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pos" onchange="cari_pos()" id="pos">
                      <option value="Tidak">Tidak</option>                      
                      <option value="Ya">Ya</option>
                    </select>
                  </div>
                  <span id="dealer_induk">                    
                    <label for="inputEmail3" class="col-sm-2 control-label">Dealer Induk</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" name="id_dealer_induk" id="id_dealer_induk">
                        <option value="">- choose -</option>   
                        <?php 
                        foreach($dt_dealer->result() as $val) {
                          echo "
                          <option value='$val->id_dealer'>$val->nama_dealer</option>;
                          ";
                        }
                        ?>                 
                      </select>
                    </div>
                  </span>
                </div> 
                <div class="form-group">                              
                  <label for="field-1" class="col-sm-2 control-label">Logo</label>            
                  <div class="col-sm-4">
                    <input type="file" class="form-control" name="logo">
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Favicon</label>            
                  <div class="col-sm-4">
                    <input type="file" class="form-control" name="favicon">
                  </div>
                  </div>
                </div>   
               
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>                  
                </div>                                                                                                                           
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
      $row = $dt_dealer->row();       
    ?>
    <body onload="cari_pos()">

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/dealer">
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
            <form class="form-horizontal" action="master/dealer/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_dealer ?>" />
              <div class="box-body">                                                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $row->nama_dealer ?>" required  id="inputEmail3" placeholder="Nama Dealer" name="nama_dealer">
                  </div>
                </div>                 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer MD</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control" value="<?php echo $row->kode_dealer_md ?>" id="inputEmail3" placeholder="Kode Dealer MD" name="kode_dealer_md">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer AHM</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control" value="<?php echo $row->kode_dealer_ahm ?>" id="inputEmail3" placeholder="Kode Dealer AHM" name="kode_dealer_ahm">
                  </div>
                  <!--label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer Link AHM</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control" value="<?php echo $row->kode_dealer_ahm_link ?>" id="inputEmail3" placeholder="Kode Dealer Link AHM" name="kode_dealer_ahm_link">
                  </div-->
                </div>    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Jaringan</label>            
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->h1=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="h1" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="h1" value="1">                      
                      <?php } ?>
                      H1
                    </div>
                  </div>                  
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->h2=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="h2" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="h2" value="1">                      
                      <?php } ?>
                      H2
                    </div>
                  </div>                  
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->h3=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="h3" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="h3" value="1">                      
                      <?php } ?>
                      H3
                    </div>
                  </div>                  
                </div>                                                                                                                           
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $row->alamat ?>" id="inputEmail3" placeholder="Alamat" name="alamat">
                  </div>
                </div>                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Telp</label>
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" class="form-control" value="<?php echo $row->no_telp ?>" id="inputEmail3" placeholder="No.Telp" name="no_telp">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                  <div class="col-sm-4">
                    <?php 
                    $dt_cust    = $this->m_admin->getByID("ms_kelurahan","id_kelurahan",$row->id_kelurahan)->row();                                 
                    if(isset($dt_cust)){
                      $kel = $dt_cust->kelurahan;
                    }else{
                      $kel = "";
                    }
                    ?>
                    <input type="hidden" value="<?php echo $row->id_kelurahan ?>" readonly name="id_kelurahan" id="id_kelurahan">                      
                    <input type="text" value="<?php echo $kel ?>" readonly name="kelurahan" data-toggle="modal" placeholder="Kelurahan" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">                               
                  </div>                  
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">NPWP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputEmail3" value="<?php echo $row->npwp ?>" placeholder="NPWP" name="npwp">
                  </div>                            
                  <label for="inputEmail3" class="col-sm-2 control-label">PKP</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pkp">
                      <option value="<?php echo $row->pkp ?>"><?php echo $row->pkp ?></option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-4">
                    <input type="email" class="form-control" value="<?php echo $row->email ?>" id="inputEmail3" placeholder="Email" name="email">
                  </div>                            
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Harga</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="id_kelompok_harga">
                      <option value="<?php echo $row->id_kelompok_harga ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_kelompok_harga","id_kelompok_harga",$row->id_kelompok_harga)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->kelompok_harga;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_kelompok_harga = $this->m_admin->kondisiCond("ms_kelompok_harga","id_kelompok_harga != '$row->id_kelompok_harga'");                                                
                      foreach($dt_kelompok_harga->result() as $val) {
                        echo "
                        <option value='$val->id_kelompok_harga'>$val->kelompok_harga</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>                  
                </div>                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Top Unit</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $row->top_unit ?>" id="inputEmail3" placeholder="Top Unit" name="top_unit">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Top Part</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $row->top_part ?>" id="inputEmail3" placeholder="Top Part" name="top_part">
                  </div>            
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pemilik</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $row->pemilik ?>" id="inputEmail3" placeholder="Pemilik" name="pemilik">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">PIC</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $row->pic ?>" id="inputEmail3" placeholder="PIC" name="pic">
                  </div>            
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Diskon</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="tipe_diskon">
                      <option value="<?php echo $row->tipe_diskon ?>"><?php echo $row->tipe_diskon ?></option>
                      <option>Rupiah</option>
                      <option>Persen</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Fixed Order</label>
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" class="form-control" value="<?php echo $row->diskon_fixed_order ?>" id="inputEmail3" placeholder="Diskon Fixed Order" name="diskon_fixed_order">
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Reguler</label>
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" class="form-control" value="<?php echo $row->diskon_reguler ?>" id="inputEmail3" placeholder="Tipe Reguler" name="diskon_reguler">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Hotline</label>
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" class="form-control" value="<?php echo $row->diskon_hotline ?>" id="inputEmail3" placeholder="Diskon Hotline" name="diskon_hotline">
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Urgent</label>
                  <div class="col-sm-4">
                    <input type="text" required onkeypress="return number_only(event)" value="<?php echo $row->diskon_urgent ?>" class="form-control" id="inputEmail3" placeholder="Tipe Urgent" name="diskon_urgent">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer Cabang Sinar Sentosa</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="dealer_cb_ssp">
                      <option value="<?php echo $row->dealer_cb_ssp ?>"><?php echo $row->dealer_cb_ssp ?></option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>            
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer Group Sinar Sentosa</label>
                  <div class="col-sm-4">
                    <select class="form-control"  required name="dealer_group_ssp">
                      <option value="<?php echo $row->dealer_group_ssp ?>"><?php echo $row->dealer_group_ssp ?></option>                      
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Bisa Pilih Unit DO</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="bisa_pilih_unit_do">
                      <option value="<?php echo $row->bisa_pilih_unit_do ?>"><?php echo $row->bisa_pilih_unit_do ?></option>                      
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Maks Penitipan Unit</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->maks_penitipan_unit ?>" required onkeypress="return number_only(event)" class="form-control" id="inputEmail3" placeholder="Maks Penitipan Unit" name="maks_penitipan_unit">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Maks Hari Penitipan</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->maks_hari_penitipan ?>" required onkeypress="return number_only(event)" class="form-control" id="inputEmail3" placeholder="Maks Hari Penitipan" name="maks_hari_penitipan">
                  </div>            
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Harus PDI</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="hrs_pdi">
                      <option value="<?php echo $row->hrs_pdi ?>"><?php echo $row->hrs_pdi ?></option>                                            
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya PDI</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->biaya_pdi ?>" required onkeypress="return number_only(event)" class="form-control" id="inputEmail3" placeholder="Biaya PDI" name="biaya_pdi">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kirim Samsat</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="kirim_samsat">
                      <option value="<?php echo $row->kirim_samsat ?>"><?php echo $row->kirim_samsat ?></option>                                                                  
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>                            
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer Financing</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="dealer_financing">
                      <option value="<?php echo $row->dealer_financing ?>"><?php echo $row->dealer_financing ?></option>                                                                                        
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang Unit</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="gudang_unit">
                      <option value="<?php echo $row->gudang_unit ?>"><?php echo $row->gudang_unit ?></option>                                                                                                              
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang Part</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="gudang_part">
                      <option value="<?php echo $row->gudang_part ?>"><?php echo $row->gudang_part ?></option>                                                                                                                                    
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang Sendiri</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="gudang_sendiri">
                      <option value="<?php echo $row->gudang_sendiri ?>"><?php echo $row->gudang_sendiri ?></option>                                                                                                                                                          
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>     
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Limit PO</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->limit_po ?>" required class="form-control" id="inputEmail3" placeholder="Limit PO" name="limit_po">
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pimpinan</label>
                  <div class="col-sm-4">
                    <input type="text" required value="<?php echo $row->pimpinan ?>" class="form-control" id="inputEmail3" placeholder="Nama Pimpinan" name="pimpinan">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Singkat Perusahaan</label>
                  <div class="col-sm-4">
                    <input type="text" required value="<?php echo $row->nama_kecil ?>" class="form-control" id="inputEmail3" placeholder="Nama Singkat Perusahaan" name="nama_kecil">
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer POS?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pos" onchange="cari_pos()" id="pos">
                      <?php 
                      $cek = $row->pos;
                      if($cek == 'Ya'){
                      ?>
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>                      
                      <?php }else{ ?>
                        <option value="Tidak">Tidak</option>                      
                        <option value="Ya">Ya</option>
                      <?php } ?>
                    </select>
                  </div>
                  <span id="dealer_induk">                    
                    <label for="inputEmail3" class="col-sm-2 control-label">Dealer Induk</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" name="id_dealer_induk" id="id_dealer_induk">
                        <option value="">- choose -</option>   
                        <?php 
                        foreach($dt_dealer_ms->result() as $val) {                                                  
                          $en = ($val->id_dealer == $row->id_dealer_induk) ? "selected" : "" ;
                          echo "
                          <option $en value='$val->id_dealer'>$val->nama_dealer</option>;
                          ";
                        }
                        ?>                 
                      </select>
                    </div>
                  </span>
                </div>    
                <div class="form-group">                              
                  <label for="field-1" class="col-sm-2 control-label">Logo</label>            
                  <div class="col-sm-4">
                    <input type="file" class="form-control" name="logo">
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Favicon</label>            
                  <div class="col-sm-4">
                    <input type="file" class="form-control" name="favicon">
                  </div>
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
    }elseif($set=="detail"){
      $row = $dt_dealer->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/dealer">
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
            <form class="form-horizontal" action="master/dealer/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_dealer ?>" />
              <div class="box-body">                                                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-10">
                    <input disabled type="text" class="form-control" value="<?php echo $row->nama_dealer ?>" required autofocus id="inputEmail3" placeholder="Nama Dealer" name="nama_dealer">
                  </div>
                </div>                 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer MD</label>
                  <div class="col-sm-2">
                    <input disabled type="text" onkeypress="return number_only(event)" class="form-control" value="<?php echo $row->kode_dealer_md ?>" id="inputEmail3" placeholder="Kode Dealer MD" name="kode_dealer_md">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer AHM</label>
                  <div class="col-sm-2">
                    <input disabled type="text" onkeypress="return number_only(event)" class="form-control" value="<?php echo $row->kode_dealer_ahm ?>" id="inputEmail3" placeholder="Kode Dealer AHM" name="kode_dealer_ahm">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer Link AHM </label>
                  <div class="col-sm-2">
                    <input disabled type="text" onkeypress="return number_only(event)" class="form-control" value="<?php echo $row->kode_dealer_ahm_link ?>" id="inputEmail3" placeholder="Kode Dealer Link AHM" name="kode_dealer_ahm_link">
                  </div>
                </div>    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Jaringan</label>            
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->h1=='1'){
                      ?>
                      <input readonly type="checkbox" class="flat-red" name="h1" value="1" checked>
                      <?php }else{ ?>
                      <input readonly type="checkbox" class="flat-red" name="h1" value="1">                      
                      <?php } ?>
                      H1
                    </div>
                  </div>                  
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->h2=='1'){
                      ?>
                      <input readonly type="checkbox" class="flat-red" name="h2" value="1" checked>
                      <?php }else{ ?>
                      <input readonly type="checkbox" class="flat-red" name="h2" value="1">                      
                      <?php } ?>
                      H2
                    </div>
                  </div>                  
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->h3=='1'){
                      ?>
                      <input readonly type="checkbox" class="flat-red" name="h3" value="1" checked>
                      <?php }else{ ?>
                      <input readonly type="checkbox" class="flat-red" name="h3" value="1">                      
                      <?php } ?>
                      H3
                    </div>
                  </div>                  
                </div>                                                                                                                           
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-10">
                    <input disabled type="text" class="form-control" value="<?php echo $row->alamat ?>" id="inputEmail3" placeholder="Alamat" name="alamat">
                  </div>
                </div>                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Telp</label>
                  <div class="col-sm-4">
                    <input disabled type="text" onkeypress="return number_only(event)" class="form-control" value="<?php echo $row->no_telp ?>" id="inputEmail3" placeholder="No.Telp" name="no_telp">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                  <div class="col-sm-4">
                    <?php 
                    $dt_cust    = $this->m_admin->getByID("ms_kelurahan","id_kelurahan",$row->id_kelurahan)->row();                                 
                    if(isset($dt_cust)){
                      $kel = $dt_cust->kelurahan;
                    }else{
                      $kel = "";
                    }
                    ?>
                    <input type="hidden" value="<?php echo $row->id_kelurahan ?>" readonly name="id_kelurahan" id="id_kelurahan">                      
                    <input type="text" value="<?php echo $kel ?>" readonly name="kelurahan" data-toggle="modal" placeholder="Kelurahan" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">                               
                  </div>                  
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">NPWP</label>
                  <div class="col-sm-4">
                    <input disabled type="text" class="form-control" id="inputEmail3" value="<?php echo $row->npwp ?>" placeholder="NPWP" name="npwp">
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">PKP</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pkp">
                      <option disabled value="<?php echo $row->pkp ?>"><?php echo $row->pkp ?></option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-4">
                    <input disabled type="email" class="form-control" value="<?php echo $row->email ?>" id="inputEmail3" placeholder="Email" name="email">
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Harga</label>
                  <div class="col-sm-4">
                    <select disabled class="form-control" name="id_kelompok_harga">
                      <option value="<?php echo $row->id_kelompok_harga ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_kelompok_harga","id_kelompok_harga",$row->id_kelompok_harga)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->kelompok_harga;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_kelompok_harga = $this->m_admin->kondisiCond("ms_kelompok_harga","id_kelompok_harga != ".$row->id_kelompok_harga);                                                
                      foreach($dt_kelompok_harga->result() as $val) {
                        echo "
                        <option value='$val->id_kelompok_harga'>$val->kelompok_harga</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>                  
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Plaforn Unit</label>
                  <div class="col-sm-4">
                    <input disabled type="text" class="form-control" value="<?php echo $row->plaforn_unit ?>" id="inputEmail3" placeholder="Plaforn Unit" name="plaforn_unit">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Plaforn Part</label>
                  <div class="col-sm-4">
                    <input disabled type="text" class="form-control" value="<?php echo $row->plaforn_part ?>" id="inputEmail3" placeholder="Plaforn Part" name="plaforn_part">
                  </div>            
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Top Unit</label>
                  <div class="col-sm-4">
                    <input disabled type="text" class="form-control" value="<?php echo $row->top_unit ?>" id="inputEmail3" placeholder="Top Unit" name="top_unit">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Top Part</label>
                  <div class="col-sm-4">
                    <input disabled type="text" class="form-control" value="<?php echo $row->top_part ?>" id="inputEmail3" placeholder="Top Part" name="top_part">
                  </div>            
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Diskon</label>
                  <div class="col-sm-4">
                    <input disabled type="text" onkeypress="return number_only(event)" class="form-control" value="<?php echo $row->tipe_diskon ?>" id="inputEmail3" placeholder="Tipe Diskon" name="tipe_diskon">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Fixed Order</label>
                  <div class="col-sm-4">
                    <input disabled type="text" onkeypress="return number_only(event)" class="form-control" value="<?php echo $row->diskon_fixed_order ?>" id="inputEmail3" placeholder="Diskon Fixed Order" name="diskon_fixed_order">
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Reguler</label>
                  <div class="col-sm-4">
                    <input disabled type="text" onkeypress="return number_only(event)" class="form-control" value="<?php echo $row->diskon_reguler ?>" id="inputEmail3" placeholder="Tipe Reguler" name="diskon_reguler">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Hotline</label>
                  <div class="col-sm-4">
                    <input disabled type="text" onkeypress="return number_only(event)" class="form-control" value="<?php echo $row->diskon_hotline ?>" id="inputEmail3" placeholder="Diskon Hotline" name="diskon_hotline">
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Urgent</label>
                  <div class="col-sm-4">
                    <input disabled type="text" onkeypress="return number_only(event)" class="form-control" value="<?php echo $row->diskon_urgent ?>" id="inputEmail3" placeholder="Tipe Urgent" name="diskon_urgent">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer Sinar Sentosa</label>
                  <div class="col-sm-4">
                    <input disabled type="text" class="form-control" value="<?php echo $row->dealer_sinar_sentosa ?>" id="inputEmail3" placeholder="Dealer Sinar Sentosa" name="dealer_sinar_sentosa">
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Harus PDI</label>
                  <div class="col-sm-4">
                    <select disabled class="form-control" name="hrs_pdi">
                      <option value="<?php echo $row->hrs_pdi ?>"><?php echo $row->hrs_pdi ?></option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kirim Samsat</label>
                  <div class="col-sm-4">
                    <select disabled class="form-control" name="kirim_samsat">
                      <option value="<?php echo $row->kirim_samsat ?>"><?php echo $row->kirim_samsat ?></option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer Financing</label>
                  <div class="col-sm-4">
                    <select disabled class="form-control" name="dealer_financing">
                      <option value="<?php echo $row->dealer_financing ?>"><?php echo $row->dealer_financing ?></option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang Unit</label>
                  <div class="col-sm-4">
                    <select disabled class="form-control" name="gudang_unit">
                      <option value="<?php echo $row->gudang_unit ?>"><?php echo $row->gudang_unit ?></option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang Part</label>
                  <div class="col-sm-4">
                    <select disabled class="form-control" name="gudang_part">
                      <option value="<?php echo $row->gudang_part ?>"><?php echo $row->gudang_part ?></option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Bank</label>
                  <div class="col-sm-4">
                    <input disabled type="text" class="form-control" value="<?php echo $row->kode_bank ?>" id="inputEmail3" placeholder="Kode Bank" name="kode_bank">
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Rekening</label>
                  <div class="col-sm-4">
                    <input disabled type="text" class="form-control" value="<?php echo $row->no_rekening ?>" id="inputEmail3" placeholder="No.Rekening" name="no_rekening">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Badan Usaha</label>
                  <div class="col-sm-4">
                    <input disabled type="text" class="form-control" value="<?php echo $row->badan_usaha ?>" id="inputEmail3" placeholder="Badan Usaha" name="badan_usaha">
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pemilik</label>
                  <div class="col-sm-4">
                    <input disabled type="text" class="form-control" value="<?php echo $row->pemilik ?>" id="inputEmail3" placeholder="Pemilik" name="pemilik">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Diwakili</label>
                  <div class="col-sm-4">
                    <input disabled type="text" class="form-control" value="<?php echo $row->diwakili ?>" id="inputEmail3" placeholder="Diwakili" name="diwakili">
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang Sendiri</label>
                  <div class="col-sm-4">
                    <select disabled class="form-control" name="gudang_sendiri">
                      <option value="<?php echo $row->gudang_sendiri ?>"><?php echo $row->gudang_sendiri ?></option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>            
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Limit PO</label>
                  <div class="col-sm-4">
                    <input type="text" disabled value="<?php echo $row->limit_po ?>" required class="form-control" id="inputEmail3" placeholder="Limit PO" name="limit_po">
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pimpinan</label>
                  <div class="col-sm-4">
                    <input type="text" disabled required value="<?php echo $row->pimpinan ?>" class="form-control" id="inputEmail3" placeholder="Nama Pimpinan" name="pimpinan">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Singkat Perusahaan</label>
                  <div class="col-sm-4">
                    <input type="text" disabled required value="<?php echo $row->nama_kecil ?>" class="form-control" id="inputEmail3" placeholder="Nama Singkat Perusahaan" name="nama_kecil">
                  </div>
                </div>     
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer POS?</label>
                  <div class="col-sm-4">
                    <select class="form-control" disabled name="pos" onchange="cari_pos()" id="pos">
                      <?php 
                      $cek = $row->pos;
                      if($cek == 'Ya'){
                      ?>
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>                      
                      <?php }else{ ?>
                        <option value="Tidak">Tidak</option>                      
                        <option value="Ya">Ya</option>
                      <?php } ?>
                    </select>
                  </div>
                  <span id="dealer_induk">                    
                    <label for="inputEmail3" class="col-sm-2 control-label">Dealer Induk</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" disabled name="id_dealer_induk" id="id_dealer_induk">
                        <option value="">- choose -</option>   
                        <?php 
                        foreach($dt_dealer->result() as $val) {
                          $en = "";
                          if($val->id_dealer == $row->id_dealer_induk) $en = "selected";
                          echo "
                          <option $en value='$val->id_dealer'>$val->nama_dealer</option>;
                          ";
                        }
                        ?>                 
                      </select>
                    </div>
                  </span>
                </div>            
                <div class="form-group">                              
                  <label for="field-1" class="col-sm-2 control-label">Logo</label>            
                  <div class="col-sm-4">
                    <input type="file" class="form-control" name="logo">
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Favicon</label>            
                  <div class="col-sm-4">
                    <input type="file" class="form-control" name="favicon">
                  </div>
                  </div>
                </div>   
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->active=='1'){
                      ?>
                      <input readonly type="checkbox" class="flat-red" name="active" value="1" checked>
                      <?php }else{ ?>
                      <input readonly type="checkbox" class="flat-red" name="active" value="1">                      
                      <?php } ?>
                      Active
                    </div>
                  </div>                  
                </div>                                                                                                                           
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <a href="master/dealer/edit?id=<?php echo $row->id_dealer ?>">
                    <button type="button" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Edit Data</button>                
                  </a>
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
          <a href="master/dealer/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
              <th>Nama Dealer</th>
              <th>Dealer MD</th>
              <th>Dealer AHM</th>
              <th>H1</th>
              <th>H2</th>
              <th>H3</th>
              <th>Alamat</th>
              <th>No.Telp</th>
              <th>Maks Plafon</th>
              <th>Sisa Plafon</th>
              <th>Active</th>           
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_dealer->result() as $row) {       
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";
            if($row->h1=='1') $h1 = "<i class='glyphicon glyphicon-ok'></i>";
                else $h1 = "";
            if($row->h2=='1') $h2 = "<i class='glyphicon glyphicon-ok'></i>";
                else $h2 = "";
            if($row->h3=='1') $h3 = "<i class='glyphicon glyphicon-ok'></i>";
                else $h3 = "";
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->nama_dealer</td>              
              <td>$row->kode_dealer_md</td>
              <td>$row->kode_dealer_ahm</td>              
              <td>$h1</td>
              <td>$h2</td>
              <td>$h3</td>
              <td>$row->alamat</td>
              <td>$row->no_telp</td>
              <td>".mata_uang2($row->plafon_maks)."</td>
              <td>".mata_uang2($row->plafon)."</td>
              <td>$active</td>
              <td>";
              ?>
                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/dealer/delete?id=<?php echo $row->id_dealer ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/dealer/edit?id=<?php echo $row->id_dealer ?>'><i class='fa fa-edit'></i></a>
                <!--a data-toggle='tooltip' title="View Data" class='btn btn-info btn-sm btn-flat' href='master/dealer/view?id=<?php echo $row->id_dealer ?>'><i class='fa fa-eye'></i></a-->
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
<div class="modal fade" id="Kelurahanmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Kelurahan
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
       <table id="table" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>              
              <th>Kelurahan</th>
              <th>Kecamatan</th>
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>                     
          </tbody>
        </table>        
      </div>      
    </div>
  </div>
</div>
<script type="text/javascript">
function auto(){
  $("#dealer_induk").hide();
}
function cari_pos(){
  var pos = $("#pos").val();                       
  if(pos == 'Ya'){
    $("#dealer_induk").show();  
  }else{
    $("#dealer_induk").hide();  
  }
}
function take_kec(){
  var id_kelurahan = $("#id_kelurahan").val();                       
  $.ajax({
      url: "<?php echo site_url('master/dealer/take_kec')?>",
      type:"POST",
      data:"id_kelurahan="+id_kelurahan,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");                    
          $("#id_kelurahan").val(data[0]);                                                    
          $("#kelurahan").val(data[1]);                                                              
      } 
  })
}
function chooseitem(id_kelurahan){
  document.getElementById("id_kelurahan").value = id_kelurahan; 
  take_kec();
  $("#Kelurahanmodal").modal("hide");
}
</script>
<script type="text/javascript">

var table;

$(document).ready(function() {
    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master/dealer/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });
});

</script>