<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Master Data</li>    
    <li class="">Dealer</li>    
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
          <a href="master/toko">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/toko/save" class="form-horizontal form-groups-bordered">
              <div class="box-body">                                                                                                                                                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Toko/Customer *</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="id_toko" placeholder="ID Toko/Customer" name="id_toko" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Toko *</label>            
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Nama Toko" name="nama_toko" required>
                  </div>
                 </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">No Telp/No HP *</label>           
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Telp/No HP" name="no_telp" required>                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Alamat *</label>           
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Alamat" name="alamat" required>                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                  <div class="col-sm-4">
                    <input type="hidden" readonly name="id_kelurahan" id="id_kelurahan">                      
                    <input type="text" readonly required name="kelurahan" data-toggle="modal" placeholder="Kelurahan" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">                               
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                    <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan"  name="kecamatan">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                    <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten" id="kabupaten" name="kabupaten">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_provinsi" id="id_provinsi">
                    <input type="text" class="form-control" readonly placeholder="Provinsi" id="provinsi" name="provinsi">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">TOP Part</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="TOP Part" name="top_part">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">NPWP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="NPWP" name="npwp">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">TOP Oli</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="TOP Oli" name="top_oli">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pemilik</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Pemilik" name="nama_pemilik">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Diskon</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="tipe_diskon">
                      <option value="">- choose -</option>
                      <option>Rupiah</option>
                      <option>Persen</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Foto Pemilik</label>
                  <div class="col-sm-4">
                    <input type="file" class="form-control" name="foto_pemilik">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Fix Order</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Diskon Fix Order" name="diskon_fix">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Toko</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="status_toko">
                      <option value="">- choose -</option>
                      <option>Milik Sendiri</option>
                      <option>Sewa</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Reguler</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Diskon Reguler" name="diskon_reguler">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Hotline</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Diskon Hotline" name="diskon_hotline">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Urgent</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Diskon Urgent" name="diskon_urgent">                                        
                  </div>                  
                </div>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode AHM</label>
                  <div class="col-sm-4">                    
                    <select class="form-control select2" name="kode_ahm">
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Ruko</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" onkeypress="return number_only(event)" placeholder="Jumlah Ruko"  name="jumlah_ruko">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Foto Ruko</label>
                  <div class="col-sm-4">                    
                    <input type="file" class="form-control" name="foto_ruko">                                        
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="form-control flat-red" name="status" value="1" checked>
                      Active
                    </div>
                  </div>                  
                </div>

              </div>
              

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
      $row = $dt_toko->row(); 

    ?>
    <body onload="take_kec()">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/toko">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <form method="POST" role="form" enctype="multipart/form-data" action="master/toko/update" class="form-horizontal form-groups-bordered">
              <div class="box-body">                                                                                                                                                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Toko/Customer *</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="id_toko" value="<?php echo $row->id_toko ?>" readonly placeholder="ID Toko/Customer" name="id_toko" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Toko *</label>            
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $row->nama_toko ?>" placeholder="Nama Toko" name="nama_toko" required>
                  </div>
                 </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">No Telp/No HP *</label>           
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Telp/No HP" value="<?php echo $row->no_telp ?>" name="no_telp" required>                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Alamat *</label>           
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->alamat ?>" class="form-control" placeholder="Alamat" name="alamat" required>                    
                  </div>
                </div>
                <div class="form-group">
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
                    <input type="text" value="<?php echo $kel ?>" required type="text" onpaste="return false" onkeypress="return nihil(event)" autocomplete="off" name="kelurahan" data-toggle="modal" placeholder="Kelurahan" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()" >
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                    <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan"  name="kecamatan">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                    <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten" id="kabupaten" name="kabupaten">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_provinsi" id="id_provinsi">
                    <input type="text" class="form-control" readonly placeholder="Provinsi" id="provinsi" name="provinsi">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">TOP Part</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" value="<?php echo $row->top_part ?>" placeholder="TOP Part" name="top_part">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">NPWP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="NPWP" name="npwp" value="<?php echo $row->npwp ?>">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">TOP Oli</label>
                  <div class="col-sm-4">                      
                    <input type="text" class="form-control" placeholder="TOP Oli" name="top_oli" value="<?php echo $row->top_oli ?>">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pemilik</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Pemilik" name="nama_pemilik" value="<?php echo $row->nama_pemilik ?>">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Diskon</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="tipe_diskon">
                      <option <?php if($row->tipe_diskon == '') echo "selected" ?> value="">- choose -</option>
                      <option <?php if($row->tipe_diskon == 'Rupiah') echo "selected" ?>>Rupiah</option>
                      <option <?php if($row->tipe_diskon == 'Persen') echo "selected" ?>>Persen</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Foto Pemilik</label>
                  <div class="col-sm-4">
                    <input type="file" class="form-control"  name="foto_pemilik">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Fix Order</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" value="<?php echo $row->diskon_fix ?>" placeholder="Diskon Fix Order" name="diskon_fix">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Toko</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="status_toko">
                      <option <?php if($row->status_toko == '') echo "selected" ?> value="">- choose -</option>
                      <option <?php if($row->status_toko == 'Milik Sendiri') echo "selected" ?>>Milik Sendiri</option>
                      <option <?php if($row->status_toko == 'Sewa') echo "selected" ?>>Sewa</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Reguler</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Diskon Reguler" value="<?php echo $row->diskon_reguler ?>" name="diskon_reguler">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Hotline</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Diskon Hotline" value="<?php echo $row->diskon_hotline ?>" name="diskon_hotline">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon Urgent</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Diskon Urgent" value="<?php echo $row->diskon_urgent ?>" name="diskon_urgent">                                        
                  </div>                  
                </div>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode AHM</label>
                  <div class="col-sm-4">                    
                    <select class="form-control select2" name="kode_ahm">
                      <option value="">- choose -</option>
                      <?php 
                      $hasil=0;
                      foreach($dt_dealer->result() as $val) {
                        $hasil = ($row->id_dealer == $isi->id_dealer) ? "selected" : "" ;                        
                        echo "
                        <option $hasil value='$val->id_dealer'>$val->nama_dealer</option>;
                        ";
                      }
                      ?>                 
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Ruko</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->jumlah_ruko ?>" onkeypress="return number_only(event)" placeholder="Jumlah Ruko"  name="jumlah_ruko">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Foto Ruko</label>
                  <div class="col-sm-4">                    
                    <input type="file" class="form-control" name="foto_ruko">                                        
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->status=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="status" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="status" value="1">                      
                      <?php } ?>
                      Active
                    </div>
                  </div>                  
                </div>

              </div>
              

              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update</button>
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
          <a href="master/toko/add">
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
              <th>Kode Toko/Customer</th>
              <th>Nama Customer</th>             
              <th>Tipe Diskon</th>
              <th>Fix</th>              
              <th>Reg</th>
              <th>Hotline</th>
              <th>Urgent</th>            
              <th width="13%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no=1;
            foreach ($dt_toko->result() as $val) {                
              echo"
              <tr>
                <td>$no</td>
                <td>$val->id_toko</td>
                <td>$val->nama_toko</td>
                <td>$val->tipe_diskon</td>
                <td>$val->diskon_fix</td>               
                <td>$val->diskon_reguler</td>               
                <td>$val->diskon_hotline</td>
                <td>$val->diskon_urgent</td>                                                                          
                <td>"; ?>
                  <a href="master/toko/delete?id=<?php echo $val->id_toko ?>"><button type="button" class="btn btn-danger btn-sm btn-flat" title="Delete" onclick="return confirm('Are you sure want to delete this data?')"><i class="fa fa-trash"></i></button></a>
                  <a href="master/toko/edit?id=<?php echo $val->id_toko ?>"><button type='button' class="btn btn-primary btn-sm btn-flat" title="Edit"><i class="fa fa-edit"></i></button></a>                  
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
function take_kec(){
  var id_kelurahan = $("#id_kelurahan").val();                       
  $.ajax({
      url: "<?php echo site_url('h1/bantuan_bbn/take_kec')?>",
      type:"POST",
      data:"id_kelurahan="+id_kelurahan,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");                    
          $("#id_kecamatan").val(data[0]);                                                    
          $("#kecamatan").val(data[1]);                                                    
          $("#id_kabupaten").val(data[2]);                                                    
          $("#kabupaten").val(data[3]);                                                    
          $("#id_provinsi").val(data[4]);                                                    
          $("#provinsi").val(data[5]);                                                    
          $("#kelurahan").val(data[6]);                                                    
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
            "url": "<?php echo site_url('h1/bantuan_bbn/ajax_list')?>",
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