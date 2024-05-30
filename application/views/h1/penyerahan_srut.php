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
    <li class="">SRUT</li>
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
          <a href="h1/penyerahan_srut">
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
            <form class="form-horizontal" autocomplete="off" action="h1/penyerahan_srut/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                 <!--  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Faktur</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="no_serah_terima" id="no_serah_terima">
                    <input type="text" name="tgl_faktur" id="tanggal2" placeholder="Tanggal Faktur" class="form-control">
                  </div>   -->                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" id="id_dealer">
                      <option value="">- choose -</option>
                      <?php 
                      $dealer = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");
                      foreach($dealer->result() as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md | $isi->nama_dealer</option>";
                      }
                      ?>
                    </select>
                  </div>                                                  
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <div class="col-sm-4">
                    <button type="button" onclick="generate()" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Generate</button>
                  </div>  
                </div>
                <span id="tampil_penyerahan_srut"></span>                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="edit_srut"){
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penyerahan_srut">
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
            <form class="form-horizontal" autocomplete="off" action="h1/penyerahan_srut/save_edit" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                 <!--  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Faktur</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="no_serah_terima" id="no_serah_terima">
                    <input type="text" name="tgl_faktur" id="tanggal2" placeholder="Tanggal Faktur" class="form-control">
                  </div>   -->                 
                  <input type="hidden" value="<?= $row->no_serah_terima ?>" name="no_serah_terima">               
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" id="id_dealer" disabled>
                      <option value="">- choose -</option>
                      <?php 
                      $dealer = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");
                      foreach($dealer->result() as $isi) {
                        $selected = $isi->id_dealer==$row->id_dealer?'selected':'';
                        echo "<option value='$isi->id_dealer' $selected>$isi->kode_dealer_md | $isi->nama_dealer</option>";
                      }
                      ?>
                    </select>
                  </div>                                                  
                </div>                
                <table id="example2" class="table myTable1 table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>No SRUT</th>
                      <th>No SRUT dr Pemohon</th>
                      <th>Tahun Pembuatan</th>   
                      <th>Aksi</th>         
                    </tr>
                  </thead>
                 
                  <tbody>                    
                    <?php   
                    $dt_srut = $this->db->query("
                      SELECT tr_srut.*, (SELECT no_mesin FROM tr_penyerahan_srut_detail WHERE no_mesin=tr_picking_list_view.no_mesin)as ada_nosin
                      FROM tr_picking_list_view
                      INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list=tr_picking_list.no_picking_list
                      INNER JOIN tr_do_po ON tr_picking_list.no_do=tr_do_po.no_do
                      INNER JOIN tr_scan_barcode ON tr_picking_list_view.no_mesin = tr_scan_barcode.no_mesin
                      INNER JOIN tr_srut ON tr_scan_barcode.no_mesin = tr_srut.no_mesin
                      WHERE tr_do_po.id_dealer = '$row->id_dealer' 
                      AND tr_scan_barcode.no_mesin NOT IN (SELECT no_mesin FROM tr_penyerahan_srut_detail 
                        JOIN tr_penyerahan_srut ON tr_penyerahan_srut_detail.no_serah_terima=tr_penyerahan_srut.no_serah_terima
                        WHERE no_mesin IS NOT NULL 
                        AND tr_penyerahan_srut.status='close'
                        AND tr_penyerahan_srut.no_serah_terima!='$row->no_serah_terima'

                      )");                    
                    
                    foreach($dt_srut->result() as $isi) {  
                      $checked = $isi->ada_nosin!=NULL?'checked':'';
                      echo "
                      <tr>                     
                        <td>$isi->no_mesin</td> 
                        <td>$isi->no_rangka</td> 
                        <td>$isi->no_srut</td> 
                        <td>$isi->no_srut_pemohon</td> 
                        <td>$isi->tahun_pembuatan</td> 
                        <td><input type=\"checkbox\" name='chk[]' value='$isi->no_mesin' $checked></td>                       
                      </tr>";                      
                      }
                    ?>
                  </tbody>
                </table>              
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="detail"){
      $row = $dt_penyerahan_srut->row();      
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penyerahan_srut">
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
            <form class="form-horizontal" autocomplete="off" action="h1/penyerahan_srut/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Tanda Terima</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly name="tgl_faktur" value="<?php echo $row->tgl_faktur ?>" placeholder="Tanggal Tanda Terima" class="form-control">
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <?php $t = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row() ?>
                    <input type="text" readonly name="id_dealer" value="<?php echo "$t->kode_dealer_md - $t->nama_dealer"; ?>" placeholder="ID Dealer" class="form-control">
                  </div>                                                  
                </div>                
                <table id="example2" class="table myTable1 table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>No SRUT</th>
                      <th>No SRUT dr Pemohon</th>
                      <th>Tahun Pembuatan</th>            
                    </tr>
                  </thead>
                 
                  <tbody>                    
                    <?php   
                    $dt_srut = $this->db->query("SELECT tr_srut.* FROM tr_srut INNER JOIN tr_penyerahan_srut_detail 
                        ON tr_srut.no_mesin = tr_penyerahan_srut_detail.no_mesin                        
                        WHERE tr_penyerahan_srut_detail.no_serah_terima = '$row->no_serah_terima'");                    
                    foreach($dt_srut->result() as $isi) {                                         
                      echo "
                      <tr>                     
                        <td>$isi->no_mesin</td> 
                        <td>$isi->no_rangka</td> 
                        <td>$isi->no_srut</td> 
                        <td>$isi->no_srut_pemohon</td> 
                        <td>$isi->tahun_pembuatan</td>                       
                      </tr>";                      
                      }
                    ?>
                  </tbody>
                </table>     
              </div><!-- /.box-body -->              
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="edit"){
      $row = $dt_penyerahan_srut->row();
      $row2 = $dt_penyerahan_srut2->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penyerahan_srut">
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
            <form class="form-horizontal" action="h1/penyerahan_srut/update" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <input type="hidden" name="id" value="<?php echo $row->id_penyerahan_srut ?>">
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan - Tahun</label>
                  <div class="col-sm-4">
                    <input type="text" name="bulan" id="format_bulan" placeholder="Bulan - Tahun" value="<?php echo $row->bulan ?>" id="tanggal" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Segment</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_segment">
                      <option value="<?php echo $row->id_segment ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_segment","id_segment",$row->id_segment)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->id_segment | $dt_cust->segment";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $segment = $this->m_admin->kondisiCond("ms_segment","id_segment != '$row->id_segment'");                                                
                      foreach ($segment->result() as $isi) {
                        echo "<option value='$isi->id_segment'>$isi->id_segment | $isi->segment</option>";
                      }
                      ?>
                    </select>
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kendaraan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_kategori">
                      <option value="<?php echo $row->id_kategori ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_kategori","id_kategori",$row->id_kategori)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->id_kategori | $dt_cust->kategori";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $kategori = $this->m_admin->kondisiCond("ms_kategori","id_kategori != '$row->id_kategori'");                                                
                      foreach ($kategori->result() as $isi) {
                        echo "<option value='$isi->id_kategori'>$isi->id_kategori | $isi->kategori</option>";
                      }
                      ?>
                    </select>
                  </div>              
                </div>                

                <table class='table table-bordered table-hover' id="example4">
                  <thead>
                    <tr>
                      <th>Kode Tipe Kendaraan Honda</th>
                      <th>Nama Tipe Kendaraan Honda</th>
                      <th>Qty Penjualan Honda</th>
                      <th>Tipe Kendaraan Yamaha</th>
                      <th>Qty Penjualan Yamaha</th>
                      <th>Tipe Kendaraan Suzuki</th>                    
                      <th>Qty Penjualan Suzuki</th>                    
                      <th>Tipe Kendaraan Kawasaki</th>                    
                      <th>Qty Penjualan Kawasaki</th>                    
                    </tr>                  
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <select class="form-control select2 isi_combo" id="id_tipe_kendaraan" name="id_tipe_kendaraan" onchange="cek_tipe()">
                          <option value="<?php echo $row2->id_tipe_kendaraan ?>">
                          <?php 
                          $dt_cust    = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$row2->id_tipe_kendaraan)->row();                                 
                          if(isset($dt_cust)){
                            echo "$dt_cust->id_tipe_kendaraan | $dt_cust->tipe_ahm";
                          }else{
                            echo "- choose -";
                          }
                          ?>
                        </option>
                        <?php 
                        $kategori = $this->m_admin->kondisiCond("ms_tipe_kendaraan","id_tipe_kendaraan != '$row2->id_tipe_kendaraan'");                                                
                        foreach ($kategori->result() as $isi) {
                          echo "<option value='$isi->id_tipe_kendaraan'>$isi->id_tipe_kendaraan | $isi->tipe_ahm</option>";
                        }
                        ?>
                        </select> 
                      </td>
                      <td>
                        <?php   
                        $ahm = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$row2->id_tipe_kendaraan)->row();
                        ?>
                        <input type="text" readonly placeholder="Nama Tipe" id="tipe_ahm" name="tipe_ahm" value="<?php  echo $ahm->tipe_ahm  ?>" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Qty Honda" value="<?php  echo $row2->qty_honda ?>" name="qty_honda" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Tipe Yamaha" value="<?php  echo $row2->tipe_yamaha   ?>" name="tipe_yamaha" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Qty Yamaha" name="qty_yamaha" value="<?php  echo $row2->qty_yamaha   ?>" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Tipe Suzuki" name="tipe_suzuki" value="<?php  echo $row2->tipe_suzuki  ?>" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Qty Suzuki" name="qty_suzuki" value="<?php  echo $row2->qty_suzuki   ?>" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Tipe Kawasaki" name="tipe_kawasaki" value="<?php  echo $row2->tipe_kawasaki  ?>" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Qty Kawasaki" name="qty_kawasaki" value="<?php  echo $row2->qty_kawasaki   ?>" class="form-control isi">
                      </td>
                    </tr>
                  </tbody>
                </table>
                
              </div><!-- /.box-body -->              
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
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
          <a href="h1/penyerahan_srut/add">            
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
              <th>No Serah Terima</th>              
              <th>Tgl Serah Terima</th>            
              <th>Nama Dealer</th>
              <th>Jumlah SRUT</th>
              <th width="10%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_penyerahan_srut->result() as $row) {                 
            $cek = $this->m_admin->getByID("tr_penyerahan_srut_detail","no_serah_terima",$row->no_serah_terima);
            if($cek->num_rows() > 0){
              $jum = $cek->num_rows();
            }else{
              $jum = 0;
            }
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');
            $edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
            $close = $this->m_admin->set_tombol($id_menu,$group,'close');
            $button ="<a $print href='h1/penyerahan_srut/cetak?id=$row->no_serah_terima' class='btn btn-warning btn-flat btn-xs'>Print</a>&nbsp;";
            if ($row->status!='close') {
              $button .="<a $edit href='h1/penyerahan_srut/edit?id=$row->no_serah_terima' class='btn btn-primary btn-flat btn-xs'>Edit</a>&nbsp;";
            $button .="<a $close href='h1/penyerahan_srut/close?id=$row->no_serah_terima' onclick=\"return confirm('Are you sure want to close this data ?');\" class='btn btn-danger btn-flat btn-xs'>Close</a>&nbsp;";
            }
          echo "          
            <tr>
              <td>$no</td>
              <td>
                <a href='h1/penyerahan_srut/detail?id=$row->no_serah_terima'>
                  $row->no_serah_terima
                </a>
              </td>
              <td>$row->tgl_faktur</td>                                         
              <td>$row->nama_dealer</td>
              <td>$jum Unit</td>                            
              <td>$button</td>";                                      
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
  var id = 1;
  $.ajax({
      url : "<?php echo site_url('h1/penyerahan_srut/cari_id')?>",
      type:"POST",
      data:"id="+id,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_serah_terima").val(data[0]);                
      }        
  })
}
function generate(){    
  $("#tampil_penyerahan_srut").show();
  // var tgl_faktur  = document.getElementById("tanggal2").value;   
  var tgl_faktur  = '';   
  var id_dealer   = document.getElementById("id_dealer").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "tgl_faktur="+tgl_faktur+"&id_dealer="+id_dealer;                           
     xhr.open("POST", "h1/penyerahan_srut/t_penyerahan_srut", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_penyerahan_srut").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
</script>