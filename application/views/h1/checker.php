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
<?php if ($set=='insert'): ?>
  <body onload="auto()">
<?php endif ?>
<?php if ($set=='edit'): ?>
  <body onload="kirim_data_checker()">
  
<?php endif ?>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Repair</li>
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
          <a href="h1/checker">
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
            <form class="form-horizontal" action="h1/checker/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <input type="hidden" name="id_checker" id="id_checker">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Checker</label>
                  <div class="col-sm-4">
                    <input type='hidden' id='set' value='new'>
                    <input type="text" name="tgl_checker" id="tanggal" value="<?php echo date("Y-m-d"); ?>" placeholder="Tanggal Checker" class="form-control">
                  </div>                  
                <label for="inputEmail3" class="col-sm-2 control-label">Estimasi Tanggal Selesai</label>
                  <div class="col-sm-4">
                    <input type="text" name="estimasi_tgl_selesai" id="tanggal2" placeholder="Estimasi tanggal selesai" class="form-control" required="">
                  </div>      
                </div>  

                <div class="form-group">
                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pemeriksa</label>
                  <div class="col-sm-4">
                    <input type="text" name="nama_pemeriksa" id="nama_pemeriksa" placeholder="Nama Pemeriksa" class="form-control" required="">
                  </div>

                  
                </div> 

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-3">
                    <input type="text" id="no_mesin" placeholder="No Mesin" name="no_mesin" readonly class="form-control">
                  </div>
                  <div class="col-sm-1">
                    <a class="btn btn-primary btn-flat btn-sm"  data-toggle="modal" data-target="#Nosinmodal" type="button"><i class="fa fa-search"></i> Browse</a>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                  <div class="col-sm-4">
                    <input type="text" name="id_item" id="id_item" placeholder="Kode Item" readonly class="form-control">
                  </div>
                </div>                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sumber Kerusakan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="sumber_kerusakan">
                      <option value="">- choose -</option>
                      <option>AHM</option>
                      <option>Ekspedisi</option>
                      <option>Warehouse</option>
                      <option>Pinjaman</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                  <div class="col-sm-4">
                    <input type="text" name="tipe_ahm" id="tipe_ahm" readonly placeholder="Tipe Kendaraan" class="form-control">
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-4">
                    <input type="text" name="keterangan" placeholder="Keterangan" class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" name="warna" id="warna" readonly placeholder="Warna" class="form-control">
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Ekspedisi</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="ekspedisi" name="ekspedisi" placeholder="Ekspedisi" class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Polisi</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_polisi" id="no_polisi" readonly placeholder="No Polisi" class="form-control">
                  </div>
                </div>             

                <button type="reset" class="btn btn-warning btn-flat btn-block" disabled>Detail Part</button>                                             
                <!-- <span id="tampil_checker"></span>   -->
                <table id="myTable" class="table myTable1 order-list" border="0">
                <thead>
                  <tr>
                    <th width="10%">Part</th>
                    <th width="15%">Deskripsi</th>
                    <th width="15%">PO Urgent</th>
                    <th width="15%">Gejala</th>
                    <th width="15%">Penyebab</th>
                    <th width="15%">Pengatasan</th>
                    <th width="5%">QTY Order</th>
                    <th width="10%">Ongkos Kerja</th>
                    <th width="15%">Keterangan</th>
                    <th width="15%">#</th>
                  </tr>
                </thead> 
                <?php $no_baris=1; ?>
                <tbody id="list">
                  <br>
                  <button class="btn btn-success" id="tambah">Tambah</button>
                </tbody>
              </table>
              
                <!-- <span id="list"></span> -->
                

                <br>
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
   } elseif($set=="edit"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/checker">
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
            <form class="form-horizontal" action="h1/checker/save_edit" method="post" enctype="multipart/form-data">              
              <div class="box-body">  
                <div class="form-group"> 
                <label for="inputEmail3" class="col-sm-2 control-label">ID Checker</label>
                  <div class="col-sm-4">
                    <input type='hidden' id='set' value='edit'>
                    <input type="text" name="id_checker" id="id_checker" value="<?php echo $checker->id_checker ?>" placeholder="Tanggal Checker" class="form-control" readonly>
                  </div>                    
                </div>     
                <div class="form-group">                  
            
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Checker</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_checker" id="tanggal" value="<?php echo $checker->tgl_checker ?>" placeholder="Tanggal Checker" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Estimasi Tanggal Selesai</label>
                  <div class="col-sm-4">
                    <input type="text" name="estimasi_tgl_selesai" placeholder="Estimasi tanggal selesai" value="<?php echo $checker->estimasi_tgl_selesai ?>" class="form-control" readonly>
                  </div>      
                </div>  

                <div class="form-group">
                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pemeriksa</label>
                  <div class="col-sm-4">
                    <input type="text" name="nama_pemeriksa" id="nama_pemeriksa" placeholder="Nama Pemeriksa" value="<?php echo $checker->nama_pemeriksa ?>" class="form-control">
                  </div>


                </div> 

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-3">
                    <input type="text" id="no_mesin" placeholder="No Mesin" name="no_mesin" readonly class="form-control" value="<?php echo $checker->no_mesin ?>">
                  </div>
                  <div class="col-sm-1">
                    <a class="btn btn-primary btn-flat btn-sm"  data-toggle="modal" data-target="#Nosinmodal" type="button"><i class="fa fa-search"></i> Browse</a>
                  </div>
                  
                 <?php $cek_nosin = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
        INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna 
        WHERE tr_scan_barcode.no_mesin = '$checker->no_mesin'")->row(); ?>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                  <div class="col-sm-4">
                    <input type="text" name="id_item" id="id_item" placeholder="Kode Item" readonly class="form-control" value="<?php echo $cek_nosin->id_item ?>">
                  </div>
                </div>                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sumber Kerusakan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="sumber_kerusakan">

                      <option <?php if($checker->sumber_kerusakan=='AHM') echo 'selected' ?>>AHM</option>
                      <option <?php if($checker->sumber_kerusakan=='Ekspedisi') echo 'selected' ?>>Ekspedisi</option>
                      <option <?php if($checker->sumber_kerusakan=='Warehouse') echo 'selected' ?>>Warehouse</option>
                      <option <?php if($checker->sumber_kerusakan=='Pinjaman') echo 'selected' ?>>Pinjaman</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                  <div class="col-sm-4">

                    <input type="text" name="tipe_ahm" id="tipe_ahm" readonly placeholder="Tipe Kendaraan" class="form-control" value="<?php echo $cek_nosin->tipe_ahm ?>">
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-4">
                    <input type="text" name="keterangan" placeholder="Keterangan" class="form-control" value="<?php echo $checker->keterangan ?>">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" name="warna" id="warna" readonly placeholder="Warna" class="form-control" readonly value="<?php echo $cek_nosin->warna ?>">
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Ekspedisi</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="ekspedisi" name="ekspedisi" placeholder="Ekspedisi" class="form-control" value="<?php echo $checker->ekspedisi ?>">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Polisi</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_polisi" id="no_polisi" readonly placeholder="No Polisi" class="form-control" value="<?php echo $checker->no_polisi ?>">
                  </div>
                </div>             


                <button type="reset" class="btn btn-warning btn-flat btn-block" disabled>Detail Part</button>                                             
                <!-- <span id="tampil_checker"></span>   -->
                <table id="myTable" class="table myTable1 order-list" border="0">
                <thead>
                  <tr>
                    <th width="10%">Part</th>
                    <th width="15%">Deskripsi</th>
                    <th width="15%">PO Urgent</th>
                    <th width="15%">Gejala</th>
                    <th width="15%">Penyebab</th>
                    <th width="15%">Pengatasan</th>
                    <th width="5%">QTY Order</th>
                    <th width="10%">Ongkos Kerja</th>
                    <th width="15%">Keterangan</th>
                    <th width="15%">#</th>
                  </tr>
                </thead> 
                <tbody id="list">
                <br>
                  <button class="btn btn-success" id="tambah">Tambah</button>
                <?php   
                error_reporting(0);
                $no_baris = 1;
                  foreach($dt_checker->result() as $row) {
                    $gejala = $this->db->get_where('ms_gejala', array('id_gejala'=>$row->gejala))->row()->gejala;
                    $penyebab = $this->db->get_where('ms_penyebab', array('id_penyebab'=>$row->penyebab))->row()->penyebab;
                    $pengatasan = $this->db->get_where('ms_pengatasan', array('id_pengatasan'=>$row->pengatasan))->row()->nama_pengatasan; 
                    ?>

                  <tr id="rm_<?php echo $no_baris; ?>">
                     <td width="10%"> <input id="id_part_<?php echo $no_baris; ?>" readonly type="text" onclick="modal_part(<?php echo $no_baris; ?>)" name="id_part[<?php echo $no_baris; ?>]" class="form-control isi_combo" placeholder="ID Part" value="<?php echo $row->id_part ?>"> </td>
                     <td width="10%"> <input type="text" id="deskripsi_<?php echo $no_baris; ?>" placeholder="Deskripsi" class="form-control isi_combo" value="<?php echo $row->deskripsi ?>" name="deskripsi[<?php echo $no_baris; ?>]"> </td>
                     <td width="10%"> <input type="text" name="no_po_urgent[<?php echo $no_baris; ?>]" id="no_po_urgent" placeholder="Masukkan No PO Urgent" value="<?php echo $row->no_po_urgent ?>" class="form-control"> </td>
                     <td width="15%">
                        <select class="form-control select2 isi_combo" name="gejala[<?php echo $no_baris; ?>]" id="gejala">
                           <option value="<?php echo $row->gejala ?>"><?php echo $gejala ?></option>
                           <?php 
                           $this->db->where('active', '1');
                           $gej=$this->db->get('ms_gejala'); foreach ($gej->result() as $isi){echo "<option value='$isi->id_gejala'>$isi->gejala</option>";}?> 
                        </select>
                     </td>
                     <td width="15%">
                        <select class="form-control select2 isi_combo" name="penyebab[<?php echo $no_baris; ?>]" id="penyebab">
                           <option value="<?php echo $row->penyebab ?>"><?php echo $penyebab ?></option>
                           <?php
                           $this->db->where('active', '1');
                            $peny=$this->db->get('ms_penyebab'); foreach ($peny->result() as $isi){echo "<option value='$isi->id_penyebab'>$isi->penyebab</option>";}?> 
                        </select>
                     </td>
                     <td width="15%">
                        <select class="form-control select2 isi_combo" name="pengatasan[<?php echo $no_baris; ?>]" id="pengatasan">
                           <option value="<?php echo $row->pengatasan ?>"><?php echo $pengatasan
                            ?></option>
                           <?php 
                           $this->db->where('active', '1');
                           $peny=$this->db->get('ms_pengatasan'); foreach ($peny->result() as $isi){echo "<option value='$isi->id_pengatasan'>$isi->nama_pengatasan</option>";}?> 
                        </select>
                     </td>
                     <td width="5%"> <input type="text" id="qty_order" placeholder="QTY Order" class="form-control isi_combo" name="qty_order[<?php echo $no_baris; ?>]" value="<?php echo $row->qty_order ?>"> </td>
                     <td width="10%"> <input type="text" id="ongkos_kerja_<?php echo $no_baris; ?>" placeholder="Ongkos Kerja" class="form-control isi_combo" value="<?php echo $row->ongkos_kerja ?>" name="ongkos_kerja[<?php echo $no_baris; ?>]"> </td>
                     <td width="15%"> <input type="text" id="ket" class="form-control isi_combo" placeholder="Keterangan" value="<?php echo $row->ket ?>" name="ket[<?php echo $no_baris; ?>]"> </td>
                     <td><span class="btn btn-sm btn-danger" onclick="rm_row(<?php echo $no_baris; ?>)"><i class="fa fa-trash"></i></span></td>
                  </tr>

                  <?php $no_baris++; } ?> 
               </tbody>
              </table>
                

                <br>
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
          <a href="h1/checker/add">            
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
              <th>No Checker</th>             
              <th>Tgl Checker</th> 
              <th>No Mesin</th>
              <th>Tipe Kendaraan</th>              
              <th width="5%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_checker->result() as $row) {                                         
            $rt = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan 
                ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan WHERE tr_scan_barcode.no_mesin = '$row->no_mesin'");
            if($rt->num_rows()>0){
              $t = $rt->row();
              $tipe_ahm = $t->tipe_ahm;
            }else{
              $tipe_ahm = "";
            }
          echo "          
            <tr>
              <td>$no</td>                           
              <td><a href='h1/checker/detail?id=$row->id_checker'>$row->id_checker</a></td>              
              <td>$row->tgl_checker</td>
              <td>$row->no_mesin</td>
              <td>$tipe_ahm</td>
              <td>";?>
                <a <?php echo $this->m_admin->set_tombol($id_menu,$group,"edit"); ?> href='h1/checker/edit?id=<?php echo $row->id_checker ?>' class='btn btn-flat btn-warning btn-xs' ><i class='fa fa-pencil'></i> Edit</a>
                <!-- <a <?php echo $this->m_admin->set_tombol($id_menu,$group,"delete"); ?> href='h1/checker/delete?id=<?php echo $row->id_checker ?>' class='btn btn-flat btn-danger btn-xs' onclick="return confirm('Are you sure to delete this data?')"><i class='fa fa-trash'></i> Delete</a> -->
                <?php
              echo "</td>";                                      
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }
    elseif($set=="detail"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/checker">
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
              <div class="box-body">  
              <div class="form-horizontal">
              <div class="form-group">
                 <label for="inputEmail3" class="col-sm-2 control-label">ID Checker</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_checker" id="tanggal" value="<?php echo $checker->id_checker ?>" class="form-control" readonly>
                  </div> 
              </div>     
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Checker</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_checker" id="tanggal" value="<?php echo $checker->tgl_checker ?>" placeholder="Tanggal Checker" class="form-control" readonly>
                  </div>                  
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">Harga Jasa</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_checker" id="tanggal" value="<?php echo $checker->harga_jasa ?>" placeholder="Tanggal Checker" class="form-control" readonly>
                  </div>                   -->
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" id="no_mesin" placeholder="No Mesin" name="no_mesin" readonly class="form-control" value="<?php echo $checker->no_mesin ?>">
                  </div>
                  <?php $cek_nosin = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
        INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna 
        WHERE tr_scan_barcode.no_mesin = '$checker->no_mesin'")->row(); ?>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                  <div class="col-sm-4">
                    <input type="text" name="id_item" id="id_item" placeholder="Kode Item" readonly class="form-control" value="<?php echo $cek_nosin->id_item ?>">
                  </div>
                </div>                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sumber Kerusakan</label>
                  <div class="col-sm-4">
                    <input type="text" name="id_item" id="id_item" placeholder="Kode Item" readonly class="form-control" value="<?php echo $checker->sumber_kerusakan ?>">
                   
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                  <div class="col-sm-4">

                    <input type="text" name="tipe_ahm" id="tipe_ahm" readonly placeholder="Tipe Kendaraan" class="form-control" value="<?php echo $cek_nosin->tipe_ahm ?>">
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-4">
                    <input type="text" name="keterangan" placeholder="Keterangan" class="form-control" readonly value="<?php echo $checker->keterangan ?>">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" name="warna" id="warna" readonly placeholder="Warna" class="form-control" readonly value="<?php echo $cek_nosin->warna ?>">
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Ekspedisi</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="ekspedisi" name="ekspedisi" placeholder="Ekspedisi" class="form-control" value="<?php echo $checker->ekspedisi ?>">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Polisi</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_polisi" id="no_polisi" readonly placeholder="No Polisi" class="form-control" value="<?php echo $checker->no_polisi ?>">
                  </div>
                </div>             

                <button type="reset" class="btn btn-warning btn-flat btn-block" disabled>Detail Part</button>                             
                   <table id="myTable" class="table myTable1 order-list" border="0">
                      <thead>
                        <tr>
                          <th width="12%">Part</th>
                          <th width="15%">Deskripsi</th>
                          <th width="15%">Gejala</th>
                          <th width="15%">Penyebab</th>
                          <th width="15%">Pengatasan</th>
                          <th width="5">QTY Order</th>
                          <th width="5%">Ongkos Kerja</th>
                          <th width="15%">Keterangan</th>
                          <th width="10%">No PO Urgent</th>
                        </tr>
                      </thead> 
                    </table>

                    <table id="example2" class="table myTable1 table-bordered table-hover">
                      <?php   
                      foreach($checker_detail->result() as $row) {           
                        echo "   
                        <tr>                    
                          <td width='10%'>$row->id_part</td>
                          <td width='15%'>$row->deskripsi</td>
                          <td width='15%''>$row->gejala</td>
                          <td width='15%'>$row->penyebab</td>
                          <td width='15%'>$row->pengatasan</td>
                          <td width='5%'>$row->qty_order</td>
                          <td align='right' width='5%'>$row->ongkos_kerja</td>
                          <td width='15%'>$row->ket</td>
                          <td width='10%'>$row->no_po_urgent</td>
                        </tr>";?>
                      <?php    
                        }
                      ?>  
                    </table>  

                <br>

                </div>
                
              </div><!-- /.box-body -->
             
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
  <?php } ?>
  </section>
</div>

<div class="modal fade" id="Nosinmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Cari No Mesin
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="10%"></th>              
              <th>No Mesin</th>
              <th>No Rangka</th>                                    
              <th>Tipe Motor</th>                                               
              <th>Warna</th>              
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          $dt_nosin = $this->db->query("SELECT tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka,ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,ms_warna.id_warna,ms_warna.warna FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan 
            INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna WHERE (tr_scan_barcode.status = 1 OR tr_scan_barcode.status = 7) AND tr_scan_barcode.tipe = 'NRFS'
            ORDER BY tr_scan_barcode.no_mesin ASC");
          foreach ($dt_nosin->result() as $ve2) {
            echo "
            <tr>"; ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="chooseitem('<?php echo $ve2->no_mesin; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
              </td>
              <?php echo "
              <td>$ve2->no_mesin</td>
              <td>$ve2->no_rangka</td>            
              <td>$ve2->id_tipe_kendaraan | $ve2->tipe_ahm</td>
              <td>$ve2->id_warna | $ve2->warna</td>";
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

<div class="modal fade" id="Partmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Cari Part
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="table" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="10%"></th>              
              <th>ID Part</th>
              <th>Nama Part</th>                                    
              <th>Satuan</th>
              <th>Harga</th>                                               
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
function chooseitem(no_mesin){
  document.getElementById("no_mesin").value = no_mesin; 
  cek_nosin();
  $("#Nosinmodal").modal("hide");
}
function cek_nosin(){
  var no_mesin = $("#no_mesin").val();                       
  $.ajax({
      url: "<?php echo site_url('h1/checker/cek_nosin')?>",
      type:"POST",
      data:"no_mesin="+no_mesin,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){                      
            $("#id_item").val(data[1]);
            $("#tipe_ahm").val(data[2]);
            $("#warna").val(data[3]);
            $("#ekspedisi").val(data[4]);
            $("#no_polisi").val(data[5]);
          }else{
            alert(data[0]);
          }
      } 
  })
}
function modal_part(id_row) {
  localStorage.setItem("id_row_checker", id_row);
  $("#Partmodal").modal("show");
}
function choosepart(id_part){
  var id_row_checker = localStorage.getItem("id_row_checker");
  document.getElementById("id_part_"+id_row_checker).value = id_part;  
  $("#Partmodal").modal("hide");
  cek_part();  
}
function cek_part(){
  id_row_checker = localStorage.getItem("id_row_checker");
  var id_part = $("#id_part_"+id_row_checker).val();
  $.ajax({
      url : "<?php echo site_url('h1/checker/cek_part')?>",
      type:"POST",
      data:"id_part="+id_part,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#deskripsi_"+id_row_checker).val(data[0]);                        
        $("#ongkos_kerja_"+id_row_checker).val(data[1]);                        
      }        
  })
}
function auto(){
  var tgl_js = "1"; 
  $.ajax({
      url : "<?php echo site_url('h1/checker/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_checker").val(data[0]);                
        kirim_data_checker();         
      }        
  })
}
function kirim_data_checker(){    
  $("#tampil_checker").show();
  var id_checker = document.getElementById("id_checker").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_checker="+id_checker;                           
     xhr.open("POST", "h1/checker/t_checker", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_checker").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
function simpan_checker(){  
  var set               = document.getElementById("set").value;   
  var id_checker        = '';
  if(set=="edit"){
    var id_checker      = document.getElementById("id_checker").value;   
  }
  var id_part           = document.getElementById("id_part").value;   
  var deskripsi         = document.getElementById("deskripsi").value;   
  var no_po_urgent         = document.getElementById("no_po_urgent").value;   
  var gejala            = document.getElementById("gejala").value;   
  var penyebab          = document.getElementById("penyebab").value;     
  var ongkos_kerja      = document.getElementById("ongkos_kerja").value;     
  var ket               = document.getElementById("ket").value;     
  var no_mesin          = document.getElementById("no_mesin").value;     
  var qty_order         = document.getElementById("qty_order").value;     
  var pengatasan        = document.getElementById("pengatasan").value;     
  //alert(id_po);
  if (id_part == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/checker/save_checker')?>",
          type:"POST",
          data:"set="+set+"&id_checker="+id_checker+"&id_part="+id_part+"&deskripsi="+deskripsi+"&no_po_urgent="+no_po_urgent+"&gejala="+gejala+"&penyebab="+penyebab+"&ongkos_kerja="+ongkos_kerja+"&ket="+ket+"&no_mesin="+no_mesin+"&qty_order="+qty_order+"&pengatasan="+pengatasan,          
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                kirim_data_checker();
                kosong();                
              }else{
                alert(data[0]);
                kosong();                      
              }                
          }
      })    
  }
}
function hapus_checker(a,b){ 
    var id_checker_detail   = a;   
    var id_checker          = b;       
    $.ajax({
        url : "<?php echo site_url('h1/checker/delete_checker')?>",
        type:"POST",
        data:"id_checker_detail="+id_checker_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_checker();
            }
        }
    })
}
function kosong(args){
  $("#id_part").val("");
  $("#deskripsi").val("");   
  $("#ongkos_kerja").val("");   
  $("#gejala").val("");     
  $("#penyebab").val("");     
  $("#ket").val("");     
}

function rm_row(id) {
  $("#rm_"+id).remove();
}
</script>
<script type="text/javascript">
var table;
$(document).ready(function() {
    var no = <?php echo $no_baris ?>;
    $("#tambah").click(function(e) {
      e.preventDefault();
      // alert("Asd");
      $("#list").append('<tr id="rm_'+no+'"> <td width="10%"> <input id="id_part_'+no+'" readonly type="text" onclick="modal_part('+no+')" name="id_part['+no+']" class="form-control isi_combo" placeholder="ID Part" required=""> </td><td width="10%"> <input type="text" id="deskripsi_'+no+'" placeholder="Deskripsi" class="form-control isi_combo" name="deskripsi['+no+']" required=""> </td><td width="10%"> <input type="text" name="no_po_urgent['+no+']" id="no_po_urgent" placeholder="Masukkan No PO Urgent" class="form-control"> </td><td width="15%"> <select class="form-control select2 isi_combo" name="gejala['+no+']" id="gejala" required=""> <option value="">- choose -</option> <?php $this->db->where("active", "1"); $gej=$this->db->get("ms_gejala"); foreach ($gej->result() as $isi){echo "<option value=\'$isi->id_gejala\'>$isi->gejala</option>";}?> </select> </td><td width="15%"> <select class="form-control select2 isi_combo" name="penyebab['+no+']" id="penyebab" required=""> <option value="">- choose -</option> <?php $this->db->where("active", "1"); $peny=$this->db->get("ms_penyebab"); foreach ($peny->result() as $isi){echo "<option value=\'$isi->id_penyebab\'>$isi->penyebab</option>";}?> </select> </td><td width="15%"> <select class="form-control select2 isi_combo" name="pengatasan['+no+']" id="pengatasan" required=""> <option value="">- choose -</option> <?php $this->db->where("active", "1"); $peny=$this->db->get("ms_pengatasan"); foreach ($peny->result() as $isi){echo "<option value=\'$isi->id_pengatasan\'>$isi->nama_pengatasan</option>";}?> </select> </td><td width="5%"> <input type="text" id="qty_order" placeholder="QTY Order" class="form-control isi_combo" name="qty_order['+no+']" value=1 required=""> </td><td width="10%"> <input type="text" id="ongkos_kerja_'+no+'" placeholder="Ongkos Kerja" class="form-control isi_combo" name="ongkos_kerja['+no+']" required=""> </td><td width="15%"> <input type="text" id="ket" class="form-control isi_combo" placeholder="Keterangan" name="ket['+no+']"> </td><td><span class="btn btn-sm btn-danger" onclick="rm_row('+no+')"><i class="fa fa-trash"><i/></span></td></tr>');
      no++;
    });

    // checked butuh po
    $("input[type='checkbox']").change(function() {
        if(this.checked) {
          $("#no_po_urgent").show();
        } else {
          $("#no_po_urgent").hide();
        }
    });

    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('h1/checker/ajax_list')?>",
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