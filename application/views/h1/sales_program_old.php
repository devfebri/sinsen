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
.hide{
	display: none;
}
</style>
<base href="<?php echo base_url(); ?>" />
<?php if(isset($_GET['id'])){ ?>
  <body onload="getGabungan();getSyarat();getJenisProgram_edit();kirim_data_dealer();">
<?php }else{ ?>
  <body onload="auto();getGabungan();getSyarat();">
<?php } ?>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Finance</li>
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
          <a href="h1/sales_program">
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
            <form class="form-horizontal" id='form_sp' action="h1/sales_program/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <button type="reset" class="btn btn-primary btn-flat btn-block" disabled>Sales Program</button>                                             
                <br>
                <div class="col-md-6">
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-4 control-label">ID Program AHM</label>
                    <div class="col-sm-7">
                      <input type="hidden" id="mode" value="new">
                      <input type="text" name="id_program_ahm" placeholder="ID Program AHM" class="form-control">
                    </div>
                  </div> 
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-4 control-label">ID Program MD</label>
                    <div class="col-sm-7">
                      <input type="text" name="id_program_md" id="id_program_md" placeholder="ID Program MD" class="form-control" readonly>
                    </div>
                  </div> 
                  <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-4 control-label">Jenis Program</label>
                  <div class="col-sm-5">
                      <select class="form-control select2 " name="id_jenis_sales_program" id="id_jenis_sales_program" onchange="auto()">                     
                        <?php 
                        $program = $this->m_admin->getSortCond("ms_jenis_sales_program","id_jenis_sales_program","ASC");
                        foreach ($program->result() as $isi) {
                          echo "<option value='$isi->id_jenis_sales_program'>$isi->jenis_sales_program</option>";
                        }
                        ?>
                      </select>
                      <input type="hidden" name="id_jenis_sales_program" id="id_jenis_sales_program_inp">
                    </div>
                  </div>
                  <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-4 control-label">Sumber Program </label>
                  <div class="col-sm-7">
                      <select class="form-control select2 " name="jenis" id="jenis">
                        <option value="ahm">AHM</option>
                        <option value="md">MD</option>
                        <option value="dealer">Dealer</option>
                      </select>
                    </div>
                  </div><br>
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-4 control-label">Periode Awal</label>
                    <div class="col-sm-4">
                      <input type="text" name="periode_awal" placeholder="" class="form-control periode_awal" id="tanggal" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-4 control-label">Tanggal Maksimal BASTK</label>
                    <div class="col-sm-4">
                      <input type="text" name="tanggal_maks_bastk" placeholder="" class="form-control" id="tanggal3" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-4 control-label">Judul Kegiatan</label>
                    <div class="col-sm-7">
                      <input type="text" name="judul_kegiatan" placeholder="" class="form-control" autocomplete="off">
                    </div>
                  </div> 

                </div>
                <div class="col-md-6">
                  <div class="form-group" style="min-height: 190px">                  
                    <label for="inputEmail3" class="col-sm-3 control-label" style="text-align: left">Juklak MD</label>
                  <div class="col-sm-8">
                    <input type="file" name="draft_jutlak" class="form-control">
                  </div>  
                  </div> 
               <!--    <div class="form-group">
                  <label for="inputEmail3" class="col-sm-12 control-label" style="text-align: left">Syarat dan Ketentuan</label>                  
                    <div class="col-sm-11">
                    <textarea class="form-control" id="textarea-full" name="syarat_ketentuan" rows="2">
                    </textarea>
                  </div>
                  </div> -->
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-3 control-label">Periode Akhir</label>
                    <div class="col-sm-4">
                      <input type="text" name="periode_akhir" placeholder="" class="form-control periode_akhir" id="tanggal2" autocomplete="off">
                    </div>
                  </div> 
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-3 control-label">Tanggal Maksimal PO</label>
                    <div class="col-sm-4">
                      <input type="text" name="tanggal_maks_po" placeholder="" class="form-control" id="tanggal4" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-3 control-label">Kuota Program</label>
                    <div class="col-sm-6">
                      <input type="text" name="kuota_program" placeholder="" class="form-control" id="kuota_program" autocomplete="off">
                    </div>
                    <div class="col-md-2">
                      <button onclick="getJenisProgram()" class="btn btn-primary btn-sm" type="button">Generate</button>
                    </div>
                  </div> 
                </div>
                
                <button type="reset" class="btn btn-success btn-flat btn-block" disabled>Detail Kendaraan dan Kontribusi</button>                                             
                <br>
                <div class="" id="ShowDetailKendaraan"></div>
                <button type="reset" class="btn btn-primary btn-flat btn-block" disabled>Dealer</button>                                             
                <br>
                <span id="tampil_dealer"></span>
                <button type="reset" class="btn btn-success btn-flat btn-block" disabled>Program Yang Bisa Digabungkan</button>                         
                <br>
                <div class="" id="ShowGabungan"></div>
                <button type="reset" class="btn btn-primary btn-flat btn-block" disabled>Syarat dan Ketentuan</button>                         
                <br>
                <div class="" id="ShowSyarat"></div>
                
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="button" onclick="submitForm()" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<script>
  function submitForm()
  {
    var cek_gabungan = parseInt($('#cek_gabungan').val());
    if (cek_gabungan>0) {
      alert=confirm('Apakah Anda yakin ingin menyimpan Sales Program ini, yang digabung dengan Sales Program yang lain ?');
    }else{
      alert=confirm('Apakah Anda yakin ingin menyimpan Sales Program ini ?');
    }
    if (alert==true) {
      $("#form_sp").submit();
      return true;
    }else{
      return false;
    }
  }
</script>    
    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/sales_program/add">            
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
              <th>ID Program AHM</th>             
              <th>ID Program MD</th> 
              <th>Jenis Kegiatan</th>              
              <th>Periode Awal</th>
              <th>Periode Akhir</th>            
              <th>Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_sales->result() as $row) {                                         
          echo "          
            <tr>
              <td>$no</td>                           
              <td>$row->id_program_ahm</td>              
              <td>
                <a href='h1/sales_program/detail?id=$row->id_program_md'>
                  $row->id_program_md
                </a>
              </td>              
              <td>$row->judul_kegiatan</td>
              <td>$row->periode_awal</td>
              <td>$row->periode_akhir</td> 
              <td>";
              ?>
                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="h1/sales_program/delete?id=<?php echo $row->id_program_md ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='h1/sales_program/edit?id=<?php echo $row->id_program_md ?>'><i class='fa fa-edit'></i></a>
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
    }elseif($set=="detail"){

    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/sales_program">
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
            <form class="form-horizontal">              
              <div class="box-body">       
                <button type="reset" class="btn btn-primary btn-flat btn-block" disabled>Sales Program</button>                                             
                <br>
                <div class="col-md-6">
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-4 control-label">ID Program AHM</label>
                    <div class="col-sm-7">
                      <input type="text" placeholder="ID Program AHM" class="form-control" readonly value="<?php echo  $row->id_program_ahm ?>">
                    </div>
                  </div> 
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-4 control-label">ID Program MD</label>
                    <div class="col-sm-7">
                      <input type="text" value="<?php echo  $row->id_program_md ?>" class="form-control" readonly>
                    </div>
                  </div> 
                  <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-4 control-label">Jenis Program</label>
                  <div class="col-sm-6">
                    <?php $jp = $this->db->query("SELECT jenis_sales_program FROM ms_jenis_sales_program WHERE id_jenis_sales_program ='$row->id_jenis_sales_program' ");
                    if ($jp->num_rows() > 0) {
                      $jenis_sales_program = $jp->row()->jenis_sales_program;
                    }else{
                      $jenis_sales_program='';
                    }
                       ?>
                      <input type="text" value="<?php echo  $jenis_sales_program ?>" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-4 control-label">Sumber Program </label>
                  <div class="col-sm-7">
                      <input type="text" value="<?php echo  $row->jenis ?>" class="form-control" readonly>
                    </div>
                  </div><br>
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-4 control-label">Periode Awal</label>
                    <div class="col-sm-4">
                      <input type="text" value="<?php echo  $row->periode_awal ?>" class="form-control" readonly>
                    </div>
                  </div>
                      <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-4 control-label">Tanggal Maksimal BASTK</label>
                    <div class="col-sm-4">
                      <input type="text" value="<?php echo  $row->tanggal_maks_bastk ?>" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-4 control-label">Judul Kegiatan</label>
                    <div class="col-sm-7">
                      <input type="text" value="<?php echo  $row->judul_kegiatan ?>" class="form-control" readonly>
                  
                    </div>
                  </div> 

                </div>
                <div class="col-md-6">
                  <div class="form-group" style="min-height: 200px">                 
                    <label for="inputEmail3" class="col-sm-3 control-label" style="text-align: left">Juklak MD</label>
                    <div class="col-sm-6">
                      <input type="text" value="<?php echo  $row->draft_jutlak ?>" class="form-control" readonly>
                    </div>  
                    <?php if(isset($row->draft_jutlak) AND $row->draft_jutlak != ""){ ?>
                    <div class="col-sm-2">
                      <a target="_blank" href="assets/panel/files/<?php echo $row->draft_jutlak ?>" class="btn btn-flat btn-primary">Lihat File</a>
                    </div>
                    <?php } ?>
                  </div> 
                <!--   <div class="form-group">
                  <label for="inputEmail3" class="col-sm-12 control-label" style="text-align: left">Syarat dan Ketentuan</label>                  
                    <div class="col-sm-11">
                    <div class="well"><?php echo  $row->syarat_ketentuan ?></div>
                  </div>
                  </div> -->
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-3 control-label">Periode Akhir</label>
                    <div class="col-sm-4">
                      <input type="text" value="<?php echo  $row->periode_akhir ?>" class="form-control" readonly>
                     
                    </div>
                  </div>
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-3 control-label">Tanggal Maksimum PO</label>
                    <div class="col-sm-4">
                      <input type="text" value="<?php echo  $row->tanggal_maks_po ?>" class="form-control" readonly>
                     
                    </div>
                  </div> 
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-3 control-label">Kuota Program</label>
                    <div class="col-sm-8">
                      <input type="text" value="<?php echo  $row->kuota_program ?>" class="form-control" readonly>
                      
                    </div>
                  </div> 
                </div>
                
                <button type="reset" class="btn btn-success btn-flat btn-block" disabled>Detail Kendaraan dan Kontribusi</button>                                             
                <br>
               
                     <style type="text/css">  .hide{
                    display: none;
                  }</style>
                  <?php if ($jenis_sales_program=='SCP') {
                      $hide = ' hide';
                  }else{
                    $hide='';
                  } 
                  if($jenis_sales_program=='Group Customer'){
                    $hide2 = '';
                  }else{
                    $hide2 = 'hide';
                  }
                  ?>
                  <table id="" class="table table-bordered table-hover">
                    <thead>
                      <tr>              
                        <th style="width: 15%">Kode Type</th>                    
                        <th>Nama Type</th>
                        <th style="width: 8%">Warna</th>
                        <th style="width: 8%">Tahun Produksi</th>
                        <th>Kontribusi</th>
                        <th>Cash</th>
                        <th>Kredit</th>
                        <th>Metode Pembayaran</th>
                        <th class="jenis_barang <?php echo  $hide ?>">Jenis Barang</th>
                        <th class="qty_minimum <?php echo  $hide2 ?>">Qty Minimum</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $login_id = $this->session->userdata('id_user');    
                      $sales_program = $this->db->query("SELECT *,ms_tipe_kendaraan.tipe_ahm FROM tr_sales_program_tipe 
                          left join ms_tipe_kendaraan on tr_sales_program_tipe.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                        WHERE tr_sales_program_tipe.id_program_md ='$row->id_program_md' ");
                      if ($sales_program->num_rows() > 0) {
                        foreach ($sales_program->result() as $rs) { ?>
                          <tr>
                            <td rowspan="4"><?php echo $rs->id_tipe_kendaraan ?></td>
                            <td rowspan="4"><?php echo $rs->tipe_ahm ?></td>
                            <td rowspan="4"><?php echo $rs->id_warna ?></td>
                            <td rowspan="4"><?php echo $rs->tahun_produksi ?></td>
                            <td><b>AHM</b></td>
                            <td align='right'><?php echo mata_uang($rs->ahm_cash) ?></td>
                            <td align='right'><?php echo mata_uang($rs->ahm_kredit )?></td>
                            <td rowspan="4"><?php echo $rs->metode_pembayaran ?></td>
                            <td rowspan="4" class="jenis_barang <?php echo  $hide ?>"><?php echo $rs->jenis_barang ?></td>
                            <td rowspan="4" class="qty_minimum <?php echo  $hide2 ?>"><?php echo $rs->qty_minimum ?></td>
                           
                          </tr>
                          <tr>
                            <td><b>MD</b></td>
                            <td align='right'><?php echo mata_uang($rs->md_cash) ?></td>
                            <td align='right'><?php echo mata_uang( $rs->md_kredit) ?></td>
                          </tr>
                          <tr>
                            <td><b>Dealer</b></td>
                            <td align='right'><?php echo mata_uang($rs->dealer_cash) ?></td>
                            <td align='right'><?php echo mata_uang($rs->dealer_kredit )?></td>
                          </tr>
                          <tr>
                            <td><b>Other</b></td>
                            <td align='right'><?php echo mata_uang($rs->other_cash) ?></td>
                            <td align='right'><?php echo mata_uang($rs->other_kredit) ?></td>
                          </tr>
                      <?php  }
                      }
                       ?>
                    </tbody>
                  </table><br><br>
                <button type="reset" class="btn btn-primary btn-flat btn-block" disabled>Dealer</button>                                             
                <br>
                <table id="example4" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                    <th>Dealer yang Ikut Program</th>
                    <th>Kuota</th>
                  </tr>
                  </thead>
                  <?php $dealer = $this->db->query("SELECT * FROM tr_sales_program_dealer 
                    left join ms_dealer on tr_sales_program_dealer.id_dealer = ms_dealer.id_dealer
                  WHERE id_program_md='$row->id_program_md'")->result(); ?>
                  <tbody>
                    <?php foreach ($dealer as $dealer): ?>
                      <tr>
                      <td><?php echo $dealer->nama_dealer ?></td>
                      <td><?php echo $dealer->kuota ?></td>
                    </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
                <br><br>
                <button type="reset" class="btn btn-success btn-flat btn-block" disabled>Program Yang Bisa Digabungkan</button>                         
                <br>
                <table id="example2" class="table table-bordered table-condensed"> 
                  <thead>
                    <tr align="center">
                      <th style="width: 90%">Program</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $sp_gab= $this->db->query("SELECT * FROM tr_sales_program_gabungan 
            left join tr_sales_program on tr_sales_program_gabungan.id_program_md_gabungan = tr_sales_program.id_program_md
      WHERE tr_sales_program_gabungan.id_program_md='$row->id_program_md' "); 


                    foreach ($sp_gab->result() as $rs): ?>
                      <tr>
                        <td><?php echo $rs->id_program_md ?> | <?php echo $rs->judul_kegiatan ?></td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>  
                <br><br>
                <button type="reset" class="btn btn-primary btn-flat btn-block" disabled>Syarat dan Ketentuan</button>                         
                <br> 
                <table style="margin-left: 20px">
                  <?php $syarat=$this->db->query("SELECT tr_sales_program_syarat.syarat_ketentuan FROM tr_sales_program_syarat left join tr_sales_program on tr_sales_program_syarat.id_program_md = tr_sales_program.id_program_md
      WHERE tr_sales_program_syarat.id_program_md='$row->id_program_md' ") ?>
                    <?php if ($syarat->num_rows()>0): ?>
                      <?php $no=1; foreach ($syarat->result() as $rs): ?>
                          <tr>
                            <td><?php echo $no?>.&nbsp;</td>
                            <td><?php echo $rs->syarat_ketentuan?></td>
                          </tr>
                      <?php $no++;endforeach ?>
                    <?php endif ?>
                </table>
                
              </div><!-- /.box-body --><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="edit"){
      $row = $dt_sales->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/sales_program">
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
            <form class="form-horizontal" action="h1/sales_program/update" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <button type="reset" class="btn btn-primary btn-flat btn-block" disabled>Sales Program</button>                                             
                <br>
                <div class="col-md-6">
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-4 control-label">ID Program AHM</label>
                    <div class="col-sm-7">
                      <input type="hidden" id="mode" value="edit">
                      <input type="text" placeholder="ID Program AHM"  name="id_program_ahm" id="id_program_ahm" class="form-control" value="<?php echo  $row->id_program_ahm ?>">
                    </div>
                  </div> 
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-4 control-label">ID Program MD</label>
                    <div class="col-sm-7">
                      <input type="text" name="id_program_md" id="id_program_md" value="<?php echo  $row->id_program_md ?>" class="form-control" readonly>
                    </div>
                  </div> 
                  <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-4 control-label">Jenis Program</label>
                  <div class="col-sm-6">
                    <?php $jp = $this->db->query("SELECT jenis_sales_program FROM ms_jenis_sales_program WHERE id_jenis_sales_program ='$row->id_jenis_sales_program' ");
                    if ($jp->num_rows() > 0) {
                      $jenis_sales_program = $jp->row()->jenis_sales_program;
                    }else{
                      $jenis_sales_program='';
                    }
                       ?>
                      
                      <input type="hidden" value="<?php echo  $row->id_jenis_sales_program ?>" id="id_jenis_sales_program" name="id_jenis_sales_program" class="form-control">
                      <input type="text" value="<?php echo  $jenis_sales_program ?>" id="jenis_sales_program" name="jenis_sales_program" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-4 control-label">Sumber Program </label>
                  <div class="col-sm-7">
                      <input type="text" value="<?php echo  $row->jenis ?>" class="form-control" name="jenis" readonly>
                    </div>
                  </div><br>
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-4 control-label">Periode Awal</label>
                    <div class="col-sm-4">
                      <input type="text" name="periode_awal" id="tanggal" value="<?php echo  $row->periode_awal ?>" class="form-control periode_awal" >
                    </div>
                  </div>
                      <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-4 control-label">Tanggal Maksimal BASTK</label>
                    <div class="col-sm-4">
                      <input id="tanggal2" type="text" name="tanggal_maks_bastk" value="<?php echo  $row->tanggal_maks_bastk ?>" class="form-control" >
                    </div>
                  </div>
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-4 control-label">Judul Kegiatan</label>
                    <div class="col-sm-7">
                      <input type="text" name="judul_kegiatan" value="<?php echo  $row->judul_kegiatan ?>" class="form-control" >
                  
                    </div>
                  </div> 

                </div>
                <div class="col-md-6">
                  <div class="form-group" style="min-height: 200px">                 
                    <label for="inputEmail3" class="col-sm-3 control-label" style="text-align: left">Juklak MD</label>
                    <div class="col-sm-6 ">
                      <input type="file" class="form-control" name="draft_jutlak">
                    </div>  
                    <?php if(isset($row->draft_jutlak) AND $row->draft_jutlak != ""){ ?>
                    <div class="col-sm-2">
                      <a target="_blank" href="assets/panel/files/<?php echo $row->draft_jutlak ?>" class="btn btn-flat btn-primary">Lihat File</a>
                    </div>
                    <?php } ?>
                  </div> 
                  
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-3 control-label">Periode Akhir</label>
                    <div class="col-sm-4">
                      <input type="text" id="tanggal3" value="<?php echo  $row->periode_akhir ?>" name="periode_akhir" class="form-control periode_akhir" >
                     
                    </div>
                  </div>
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-3 control-label">Tanggal Maksimum PO</label>
                    <div class="col-sm-4">
                      <input type="text" id="tanggal4" value="<?php echo  $row->tanggal_maks_po ?>" name="tanggal_maks_po" class="form-control" >                     
                    </div>
                  </div> 
                  <div class="form-group">                  
                    <label for="inputEmail3" class="col-sm-3 control-label">Kuota Program</label>
                    <div class="col-sm-8">
                      <input type="text" value="<?php echo  $row->kuota_program ?>" name="kuota_program" class="form-control" >
                      
                    </div>
                  </div> 
                </div>
                
                <button type="reset" class="btn btn-success btn-flat btn-block" disabled>Detail Kendaraan dan Kontribusi</button>                                             
                <br>
                <div class="" id="ShowDetailKendaraan"></div>

                <button type="reset" class="btn btn-primary btn-flat btn-block" disabled>Dealer</button>                                             
                <br>

                <span id="tampil_dealer"></span>
                <button type="reset" class="btn btn-success btn-flat btn-block" disabled>Program Yang Bisa Digabungkan</button>                         
                <br>
                <div class="" id="ShowGabungan"></div>
                <button type="reset" class="btn btn-primary btn-flat btn-block" disabled>Syarat dan Ketentuan</button>                         
                <br>
                <div class="" id="ShowSyarat"></div>
                
                
              </div><!-- /.box-body --><!-- /.box-footer -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>                  
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


<div class="modal fade modal_edit_detailkendaraan" id="Modal_edit">
  <div class="modal-dialog" style="width: 90%;">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title">Edit Detail Kendaraan dan Kontribusi</h4>
      </div>
      <div class="modal-body" id="showEditDetail">
      </div>
      <div class="modal-footer">
            <p align="center">
              <button type="submit" class="btn btn-primary pull-right" onclick="saveEditDetail()">Simpan</button>
            </p>
      </div>
        </form> 
    </div>
  </div>
</div>

<script type="text/javascript">
function cek_c(){ 
  if (document.getElementById('md_c').checked == true){
    document.getElementById('md').removeAttribute('disabled');    
  }else{
    document.getElementById('md').setAttribute('disabled','disabled');    
  }

  if (document.getElementById('dealer_c').checked == true){
    document.getElementById('dealer').removeAttribute('disabled');    
  }else{
    document.getElementById('dealer').setAttribute('disabled','disabled');    
  }

  if (document.getElementById('ahm_c').checked == true){
    document.getElementById('ahm').removeAttribute('disabled');    
  }else{
    document.getElementById('ahm').setAttribute('disabled','disabled');    
  }

  if (document.getElementById('other_c').checked == true){
    document.getElementById('other').removeAttribute('disabled');    
  }else{
    document.getElementById('other').setAttribute('disabled','disabled');    
  }
}
function auto(){
  var id_jenis_sales_program = document.getElementById("id_jenis_sales_program").value;
  var id_jenis_sales_program_text = $("#id_jenis_sales_program :selected").text();
  $("#id_jenis_sales_program_inp").val(id_jenis_sales_program);
  $.ajax({
      url : "<?php echo site_url('h1/sales_program/cari_id')?>",
      type:"POST",
      data:"id_jenis_sales_program="+id_jenis_sales_program,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_program_md").val(data[0]);        
       // kirim_data_tipe();
        // kirim_data_dealer();
 //       document.getElementById('md').setAttribute('disabled','disabled');    
   //     document.getElementById('ahm').setAttribute('disabled','disabled');
     //   document.getElementById('dealer').setAttribute('disabled','disabled');
       // document.getElementById('other').setAttribute('disabled','disabled');
      }        
  })
  if (id_jenis_sales_program_text=='SCP') {
  	$('.choose_jenis_scp').removeClass('hide');
  }else{
  	$('.choose_jenis_scp').addClass('hide');
  }
}
function kirim_data_tipe(){    
  $("#tampil_tipe").show();
  var id_program_md = document.getElementById("id_program_md").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_program_md="+id_program_md;                           
     xhr.open("POST", "h1/sales_program/t_tipe", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_tipe").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function simpan_tipe(){  
  var id_program_md       = document.getElementById("id_program_md").value;   
  var id_tipe_kendaraan   = document.getElementById("id_tipe_kendaraan").value;   
  var tahun_kendaraan     = document.getElementById("tahun_kendaraan").value;   
  var id_warna            = document.getElementById("id_warna").value;   
  var metode_bayar        = document.getElementById("metode_bayar").value;     
  var jenis_bayar_dibelakang = document.getElementById("jenis_bayar_dibelakang").value;     
  //alert(id_po);
  if (id_warna == "" || id_tipe_kendaraan == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/sales_program/save_tipe')?>",
          type:"POST",
          data:"id_tipe_kendaraan="+id_tipe_kendaraan+"&tahun_kendaraan="+tahun_kendaraan+"&id_warna="+id_warna+"&metode_bayar="+metode_bayar+"&id_program_md="+id_program_md+"&jenis_bayar_dibelakang="+jenis_bayar_dibelakang,          
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                kirim_data_tipe();
                kosong();                
              }else{
                alert(data[0]);
                kosong();                      
              }                
          }
      })    
  }
}
function hapus_tipe(a,b){ 
    var id_sales_program_tipe   = a;   
    var id_tipe_kendaraan       = b;       
    $.ajax({
        url : "<?php echo site_url('h1/sales_program/delete_tipe')?>",
        type:"POST",
        data:"id_sales_program_tipe="+id_sales_program_tipe,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_tipe();
            }
        }
    })
}
function kosong(args){
  $("#id_tipe_kendaraan").val("");
  $("#id_warna").val("");   
  $("#tahun_kendaraan").val("");   
  $("#metode_bayar").val("");     
}
function kirim_data_dealer(){    
  $("#tampil_dealer").show();
  var id_program_md = document.getElementById("id_program_md").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_program_md="+id_program_md;                           
     xhr.open("POST", "h1/sales_program/t_dealer", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_dealer").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function simpan_dealer(){  
  var id_program_md       = document.getElementById("id_program_md").value;   
  var id_dealer           = document.getElementById("id_dealer").value;   
  var kuota               = document.getElementById("kuota").value;     
  //alert(id_po);
  if (id_dealer == "" || kuota == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/sales_program/save_dealer')?>",
          type:"POST",
          data:"id_dealer="+id_dealer+"&kuota="+kuota+"&id_program_md="+id_program_md,          
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                kirim_data_dealer();
                kosong2();                
              }else{
                alert(data[0]);
                kosong2();                      
              }                
          }
      })    
  }
}
function hapus_dealer(a,b){ 
    var id_sales_program_dealer   = a;   
    var id_dealer       = b;       
    $.ajax({
        url : "<?php echo site_url('h1/sales_program/delete_dealer')?>",
        type:"POST",
        data:"id_sales_program_dealer="+id_sales_program_dealer,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_dealer();
            }
        }
    })
}
function kosong2(args){
  $("#id_dealer").val("");
  $("#kuota").val("");     
}

function check_metodeBayar(){
    var metode_bayar        = document.getElementById("metode_bayar").value;

    if (metode_bayar=='Bayar Di Belakang') {
      $('.input_jenis_bayar_dibelakang').removeClass('hide');
    }else{
      $('.input_jenis_bayar_dibelakang').addClass('hide');
    }
  }

function getJenisProgram()
{
  var id_jenis_sales_program = $("#id_jenis_sales_program").val();
  var jenis_sales_program =  $("#id_jenis_sales_program").select2('data')[0]['text'];
  var id_program_md =  $("#id_program_md").val();  
  var kuota_program = parseInt($("#kuota_program").val());  
  $('#id_jenis_sales_program').select2({ disabled: true });
  $('#kuota_program').attr('readonly',true);
  $('.periode_awal').attr('readonly',true);
  $('.periode_akhir').attr('readonly',true);
  $.ajax({
       beforeSend: function() { $('#loading-status').show(); },
       url:"<?php echo site_url('h1/sales_program/getJenisProgram')?>",
       type:"POST",
       data:"id_jenis_sales_program="+id_jenis_sales_program
             +"&jenis_sales_program="+jenis_sales_program
             +"&id_program_md="+id_program_md,
       cache:false,
       success:function(html){
          $('#loading-status').hide();
          $('#ShowDetailKendaraan').html(html);
          getGabungan(); 
          getJS();
          // if (kuota_program>0) {
            kirim_data_dealer();
          // }
       },
       statusCode: {
    500: function() {
      $('#loading-status').hide();
      alert("Something Wen't Wrong");
    }
  }
  });
}
function getJenisProgram_edit()
{
  var id_jenis_sales_program = $("#id_jenis_sales_program").val();
  var jenis_sales_program = $("#jenis_sales_program").val();
  var id_program_md =  $("#id_program_md").val();  
  $.ajax({
       beforeSend: function() { $('#loading-status').show(); },
       url:"<?php echo site_url('h1/sales_program/getJenisProgram_edit')?>",
       type:"POST",
       data:"id_jenis_sales_program="+id_jenis_sales_program
             +"&id_program_md="+id_program_md
             +"&jenis_sales_program="+jenis_sales_program,
       cache:false,
       success:function(html){
          $('#loading-status').hide();
          getGabungan(); 
          $('#ShowDetailKendaraan').html(html);
          getJS();
       },
       statusCode: {
    500: function() {
      $('#loading-status').hide();
      alert("Something Wen't Wrong");
    }
  }
  });
}

function getGabungan()
{
  var mode = $("#mode").val();
  var id_program_md = $("#id_program_md").val();
  var periode_awal = $(".periode_awal").val();
  var periode_akhir = $(".periode_akhir").val();
  $.ajax({
       beforeSend: function() { $('#loading-status').show(); },
       url:"<?php echo site_url('h1/sales_program/getGabungan')?>",
       type:"POST",
       data:"id_program_md="+id_program_md+"&mode="+mode+"&periode_awal="+periode_awal+"&periode_akhir="+periode_akhir,
       cache:false,
       success:function(html){
          $('#loading-status').hide();
          $('#ShowGabungan').html(html);
          getJS();
          datatables();
       },
       statusCode: {
    500: function() {
      $('#loading-status').hide();
      alert("Something Wen't Wrong");
    }
  }
  });
}

function getSyarat()
{
  var mode = $("#mode").val();
  var id_program_md = $("#id_program_md").val();
  $.ajax({
       beforeSend: function() { $('#loading-status').show(); },
       url:"<?php echo site_url('h1/sales_program/getSyarat')?>",
       type:"POST",
       data:"id_program_md="+id_program_md+"&mode="+mode,
       cache:false,
       success:function(html){
          $('#loading-status').hide();
          $('#ShowSyarat').html(html);
          getJS();
          datatables();
       },
       statusCode: {
    500: function() {
      $('#loading-status').hide();
      alert("Something Wen't Wrong");
    }
  }
  });
}

function getJS()
  {
    $(".select2").select2({
            placeholder: "-- Pilih --",
            allowClear: false
        });
  }

  function  datatables()
  {
    $('#examdple4').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
         // "scrollX":true,
          fixedHeader:true,
          "lengthMenu": [[10, 25, 50,75,100, -1], [10, 25, 50,75,100, "All"]],
         // "autoWidth": true
        });
  }
  function addDetailKendaraan_edit()
  {
      var id_program_md = $("#id_program_md").val();
      var kode_type = $("#kode_type").val();
      var id_warna = $("#id_warna").val();
      var tahun_produksi = $("#tahun_produksi").val();
      var ahm_cash = $("#ahm_cash").val();
      var ahm_kredit = $("#ahm_kredit").val();
      var md_cash = $("#md_cash").val();
      var md_kredit = $("#md_kredit").val();
      var dealer_cash = $("#dealer_cash").val();
      var dealer_kredit = $("#dealer_kredit").val();
      var other_cash = $("#other_cash").val();
      var other_kredit = $("#other_kredit").val();
      var metode_pembayaran = $("#metode_pembayaran").val();
      var jenis_barang = $("#jenis_barang").val();
      var qty_minimum = $("#qty_minimum").val();
      var jenis_bayar_dibelakang = $("#jenis_bayar_dibelakang").val();

      if (kode_type=='') {
        alert('Silahkan Pilih Tipe Kendaraan')
      }else{
        $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/sales_program/save_tipe');?>",
               type:"POST",
               data:"kode_type="+kode_type
                  +"&id_program_md="+id_program_md
                  +"&id_warna="+id_warna
                  +"&tahun_produksi="+tahun_produksi
                  +"&ahm_cash="+ahm_cash
                  +"&ahm_kredit="+ahm_kredit
                  +"&md_cash="+md_cash
                  +"&md_kredit="+md_kredit
                  +"&dealer_cash="+dealer_cash
                  +"&dealer_kredit="+dealer_kredit
                  +"&other_cash="+other_cash
                  +"&other_kredit="+other_kredit
                  +"&metode_pembayaran="+metode_pembayaran
                  +"&jenis_bayar_dibelakang="+jenis_bayar_dibelakang
                  +"&jenis_barang="+jenis_barang
                  +"&qty_minimum="+qty_minimum,
               cache:false,
               success:function(data){
                  $('#loading-status').hide();
                    getGabungan();                       
                  if(data=="nihil"){
                    getJenisProgram_edit(); 
                  }else{
                    alert(data);          
                  }    
               },
               statusCode: {
            500: function() {
              $('#loading-status').hide();
              alert("Something Wen't Wrong");
            }
          }
          });
      }
  }
  function addDetailKendaraan()
  {
      var id_program_md = $("#id_program_md").val();
      var kode_type = $("#kode_type").val();
      var id_warna = $("#id_warna").val();
      var tahun_produksi = $("#tahun_produksi").val();
      var ahm_cash = $("#ahm_cash").val();
      var ahm_kredit = $("#ahm_kredit").val();
      var md_cash = $("#md_cash").val();
      var md_kredit = $("#md_kredit").val();
      var dealer_cash = $("#dealer_cash").val();
      var dealer_kredit = $("#dealer_kredit").val();
      var other_cash = $("#other_cash").val();
      var other_kredit = $("#other_kredit").val();
      var metode_pembayaran = $("#metode_pembayaran").val();
      var jenis_barang = $("#jenis_barang").val();
      var qty_minimum = $("#qty_minimum").val();
      var jenis_bayar_dibelakang = $("#jenis_bayar_dibelakang").val();

      if (kode_type=='') {
        alert('Silahkan Pilih Tipe Kendaraan')
      }else{
        $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/sales_program/save_tipe');?>",
               type:"POST",
               data:"kode_type="+kode_type
                  +"&id_program_md="+id_program_md
                  +"&id_warna="+id_warna
                  +"&tahun_produksi="+tahun_produksi
                  +"&ahm_cash="+ahm_cash
                  +"&ahm_kredit="+ahm_kredit
                  +"&md_cash="+md_cash
                  +"&md_kredit="+md_kredit
                  +"&dealer_cash="+dealer_cash
                  +"&dealer_kredit="+dealer_kredit
                  +"&other_cash="+other_cash
                  +"&other_kredit="+other_kredit
                  +"&metode_pembayaran="+metode_pembayaran
                  +"&jenis_bayar_dibelakang="+jenis_bayar_dibelakang
                  +"&jenis_barang="+jenis_barang
                  +"&qty_minimum="+qty_minimum,
               cache:false,
               success:function(data){
                  $('#loading-status').hide();
                  getGabungan(); 
                  if(data=="nihil"){
                    getJenisProgram();                        
                  }else{
                    alert(data);          
                  }    
               },
               statusCode: {
            500: function() {
              $('#loading-status').hide();
              alert("Something Wen't Wrong");
            }
          }
          });
      }
  }

  function saveEditDetail()
  {
      var id_program_md = $("#id_program_md").val();
      var kode_type = $(".modal_edit_detailkendaraan #kode_type").val();
      var id_warna = $(".modal_edit_detailkendaraan #id_warna").val();
      var tahun_produksi = $(".modal_edit_detailkendaraan #tahun_produksi").val();
      var ahm_cash = $(".modal_edit_detailkendaraan #ahm_cash").val();
      var ahm_kredit = $(".modal_edit_detailkendaraan #ahm_kredit").val();
      var md_cash = $(".modal_edit_detailkendaraan #md_cash").val();
      var md_kredit = $(".modal_edit_detailkendaraan #md_kredit").val();
      var dealer_cash = $(".modal_edit_detailkendaraan #dealer_cash").val();
      var dealer_kredit = $(".modal_edit_detailkendaraan #dealer_kredit").val();
      var other_cash = $(".modal_edit_detailkendaraan #other_cash").val();
      var other_kredit = $(".modal_edit_detailkendaraan #other_kredit").val();
      var metode_pembayaran = $(".modal_edit_detailkendaraan #metode_pembayaran_edit").val();
      var jenis_barang = $(".modal_edit_detailkendaraan #jenis_barang").val();
      var qty_minimum = $(".modal_edit_detailkendaraan #qty_minimum").val();
      var jenis_bayar_dibelakang = $(".modal_edit_detailkendaraan #jenis_bayar_dibelakang_edit").val();
      var id_sales_program_tipe = $(".modal_edit_detailkendaraan #id_sales_program_tipe").val();

      if (kode_type=='') {
        alert('Silahkan Pilih Tipe Kendaraan')
      }else{
        $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/sales_program/saveEditDetail');?>",
               type:"POST",
               data:"kode_type="+kode_type
                  +"&id_program_md="+id_program_md
                  +"&id_warna="+id_warna
                  +"&tahun_produksi="+tahun_produksi
                  +"&ahm_cash="+ahm_cash
                  +"&ahm_kredit="+ahm_kredit
                  +"&md_cash="+md_cash
                  +"&md_kredit="+md_kredit
                  +"&dealer_cash="+dealer_cash
                  +"&dealer_kredit="+dealer_kredit
                  +"&other_cash="+other_cash
                  +"&other_kredit="+other_kredit
                  +"&metode_pembayaran="+metode_pembayaran
                  +"&jenis_bayar_dibelakang="+jenis_bayar_dibelakang
                  +"&id_sales_program_tipe="+id_sales_program_tipe
                  +"&jenis_barang="+jenis_barang
                  +"&qty_minimum="+qty_minimum,
               cache:false,
               success:function(msg){
                  $('#loading-status').hide();
                  data=msg.split("|");                  
                  window.location.href = "<?php echo site_url('h1/sales_program/edit?id=');?>"+data[1];                    
               },
               statusCode: {
            500: function() {
              $('#loading-status').hide();
              alert("Something Wen't Wrong");
            }
          }
          });
      }
  }

  function editDetailKendaraan(id)
  {
      $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/sales_program/editDetailKendaraan');?>",
               type:"POST",
               data:"id_sales_program_tipe="+id,
               cache:false,
               success:function(data){
                  $('#loading-status').hide();
                  getGabungan(); 
                   $('#showEditDetail').html(data);
                   getJS();   
               },
               statusCode: {
            500: function() {
              $('#loading-status').hide();
              alert("Something Wen't Wrong");
            }
          }
          });
  }

  
  function delDetailKendaraan(id_sales_program_tipe)
  {
      var mode = $("#mode").val();
      $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/sales_program/delete_tipe');?>",
               type:"POST",
               data:"id_sales_program_tipe="+id_sales_program_tipe,
               cache:false,
               success:function(data){
                  $('#loading-status').hide();
                  getGabungan(); 
                  if(data=="nihil"){
                    if(mode == 'edit'){
                      getJenisProgram_edit();
                    }else{
                      getJenisProgram();    
                    }
                  }else{
                    alert(data);          
                  }    
               },
               statusCode: {
            500: function() {
              $('#loading-status').hide();
              alert("Something Wen't Wrong");
            }
          }
          });
  }

  function addGabungan()
  {
      var id_program_md = $("#id_program_md").val();
      var id_program_md_gabungan = $("#id_program_md_gabungan").val();
      if (id_program_md_gabungan == '') {
        alert('Silahkan Pilih Program')
      }else{

      var id_program_md_gabungan = $("#id_program_md_gabungan").val();
      $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/sales_program/save_gabungan');?>",
               type:"POST",
               data:"id_program_md_gabungan="+id_program_md_gabungan
                  +"&id_program_md="+id_program_md,
               cache:false,
               success:function(data){
                  $('#loading-status').hide();
                  
                  if(data=="nihil"){
                    getGabungan();    
                  }else{
                    alert(data);          
                  }    
               },
               statusCode: {
            500: function() {
              $('#loading-status').hide();
              alert("Something Wen't Wrong");
            }
          }
          });
      }
  }

    function delGabungan(id)
  {
      $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/sales_program/delete_gabungan');?>",
               type:"POST",
               data:"id="+id,
               cache:false,
               success:function(data){
                  $('#loading-status').hide();
                  
                  if(data=="nihil"){
                    getGabungan();    
                  }else{
                    alert(data);          
                  }    
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