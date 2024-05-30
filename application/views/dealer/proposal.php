<?php 
	function format_curr($a){
	    return number_format($a, 0, '.', '.');
	} ?>
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
          <a href="dealer/proposal">
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
            <form class="form-horizontal" action="dealer/proposal/save" method="post" enctype="multipart/form-data">
              <div class="box-body">    
                <button disabled class="btn btn-block btn-primary btn-flat">Proposal</button>
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Program</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="ID Program" name="id_program">                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Program</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" placeholder="Nama Program" name="nama_program">                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tema Program</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" placeholder="Tema Program" name="tema_program">                                        
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Mulai</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="tanggal" placeholder="Tanggal Mulai" name="tgl_mulai">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Selesai</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Tanggal Selesai" name="tgl_selesai" id="tanggal2" >                    
                  </div>                  
                </div>                                                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sumber Dana</label>
                  <div class="col-sm-4">
                    <!-- <input type="text" class="form-control" placeholder="Sumber Dana" name="sumber_dana">                     -->
                    <input type="checkbox" name="ahm"> AHM                    
                    <input type="checkbox" name="md"> MD                    
                    <input type="checkbox" name="dealer"> Dealer                    
                    <input type="checkbox" name="lainnya"> Lainnya                    

                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Target Penjualan</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control" placeholder="Target Penjualan" name="target_penjualan">                    
                  </div>              
                  <label for="inputEmail3" class="col-sm-2 control-label" style="text-align: left">Unit</label>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Event</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="Lokasi Event" name="lokasi_event">                    
                 </div>                  
                </div>
                <button disabled class="btn btn-block btn-success btn-flat">Leasing Pendukung</button>
                <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Leasing</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" multiple="multiple" name="leasing[]" style="color: white">
                      <option>- choose more -</option>
                      <?php $dt_fin = $this->db->query("SELECT * from ms_finance_company where active=1") ?>
                      <?php foreach ($dt_fin->result() as $fin): ?>
                      		<option value="<?php echo $fin->id_finance_company ?>"><?php echo $fin->finance_company ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>                  
                </div>
                <button disabled class="btn btn-block btn-warning btn-flat">Rincian Biaya</button>
                <br>
                
                  	<div id="tampil_rincian"></div>
                    <input type="hidden" id="mode" value="new">
           
                <button disabled class="btn btn-block btn-danger btn-flat">Deskripsi Program</button>
                <br>
                <?php /* ?><div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Judul</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" name="judul"></textarea>
                  </div>                
                </div>   <?php */ ?>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Latar Belakang</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" name="latar_belakang"></textarea>
                  </div>                  
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Detail Pelaksanaan</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" name="isi"></textarea>
                  </div>                
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Penutup</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" name="penutup"></textarea>
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Proposal</label>
                  <div class="col-sm-4">
                    <!--<input type="text" class="form-control" placeholder="Jenis Proposal" name="jenis_proposal">                    -->
                    <select class="form-control" name="jenis_proposal">
                      <option value="Proposal Internal">Proposal Internal</option>
                      <option value="Proposal Eksternal">Proposal Eksternal</option>
                    </select>
                  </div>                                    
                </div>                
                
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
   } elseif($set=="detail"){
      $row = $dt_proposal->row();               
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/proposal">
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
              <div class="box-body">    
                <button disabled class="btn btn-block btn-primary btn-flat">Proposal</button>
                <br>
                <table class="table" style="width: 60%">
                  <tr>
                    <td><b>ID Program</b></td><td>:</td><td><?php echo $row->id_program ?></td>
                  </tr>
                  <tr>
                    <td><b>Nama Program</b></td><td>:</td><td><?php echo $row->nama_program ?></td>
                  </tr>
                  <tr>
                    <td><b>Tema Program</b></td><td>:</td><td><?php echo $row->tema_program ?></td>
                  </tr>
                  <tr>
                    <td><b>Tanggal Mulai</b></td><td>:</td><td><?php echo $row->tgl_mulai ?></td>
                  </tr>
                   <tr>
                    <td><b>Tanggal Selesai</b></td><td>:</td><td><?php echo $row->tgl_selesai ?></td>
                  </tr>
                  <tr>
                    <td><b>Sumber Dana</b></td><td>:</td>
                    <td>
                      <?php 
                      if($row->ahm == 'on') echo "AHM ($row->ahm_text) <br>";
                      if($row->md == 'on') echo "MD ($row->md_text) <br>";
                      if($row->dealer == 'on') echo "Dealer ($row->dealer_text) <br>";
                      if($row->lainnya == 'on') echo "Lainnya ($row->lainnya_text) <br>";                      
                      ?>
                        
                    </td>
                  </tr>
                  <tr>
                    <td><b>Target Penjualan</b></td><td>:</td><td><?php echo $row->target_penjualan ?></td>
                  </tr>
                   <tr>
                    <td><b>Lokasi Event</b></td><td>:</td><td><?php echo $row->lokasi_event ?></td>
                  </tr>
                </table>                    <br>                             
                <button disabled class="btn btn-block btn-success btn-flat">Leasing Pendukung</button>
                <br>
                <table class="table" style="width: 60%">
                  <tr>
                    <td>Leasing</td><td>:</td>
                    <td>
                      <?php 
                      if ($row->id_leasing_pendukung!=null) {
                        $id_leasing_pendukung = explode(',', $row->id_leasing_pendukung);
                        foreach ($id_leasing_pendukung as $key => $value) {
                          $id[$key] = "'$value'";
                        }
                        $id_leasing_pendukung = implode(',', $id);
                        $leasing = $this->db->query("SELECT GROUP_CONCAT(finance_company) as leasing  FROM ms_finance_company where id_finance_company in($id_leasing_pendukung)")->row()->leasing;
                        echo $leasing;
                      }
                       ?>
                    </td>
                  </tr>
                </table>
<br>
                <button disabled class="btn btn-block btn-warning btn-flat">Rincian Biaya</button>
                <br>
                
                   <table class="table table-bordered table-hover">
                  <thead>
                    <tr>                                            
                      <th>Item</th>              
                      <th width="10%">Qty</th>              
                      <th>Harga</th>              
                      <th>PPN</th>
                      <th>Keterangan</th>              
                    </tr>
                  </thead>
                  <tbody>
<?php if ($show_rincian->num_rows() > 0): ?>
  <?php foreach ($show_rincian->result() as $res ): ?>
    <tr>
      <td><?php echo $res->item ?></td>
      <td align="right"><?php echo $res->qty ?></td>
      <td align="right"><?php echo format_curr($res->harga) ?></td>
      <td>
        <?php if ($res->ppn==1){ ?>
          Ya
        <?php }else{echo "Tidak"; } ?>
      </td>
      <td><?php echo $res->keterangan ?></td>
     
    </tr>
  <?php endforeach ?>
<?php endif ?>
<tr>
              </tbody>
                </table> 
           
<br>
                <button disabled class="btn btn-block btn-danger btn-flat">Deskripsi Program</button>
                <br>
                <table class="table" style="width:60%">
                 <?php /* ?> <tr>
                    <td><b>Judul</b></td>
                    <td>:</td>
                    <td><?php echo $row->judul ?></td>
                  </tr> <?php */ ?>
                  <tr>
                    <td><b>Latar Belakang</b></td>
                    <td>:</td>
                    <td><?php echo $row->latar_belakang ?></td>
                  </tr>
                  <tr>
                    <td><b>Detail Pelaksanaan</b></td>
                    <td>:</td>
                    <td><?php echo $row->isi ?></td>
                  </tr>
                  <tr>
                    <td><b>Penutup</b></td>
                    <td>:</td>
                    <td><?php echo $row->penutup ?></td>
                  </tr>
                  <tr>
                    <td><b>Jenis Proposal</b></td>
                    <td>:</td>
                    <td><b><?php echo $row->jenis_proposal ?></b></td>
                  </tr>
                </table>
                
              </div><!-- /.box-body -->
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    
    <?php 
    }elseif($set=='edit'){
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
            <form class="form-horizontal" action="dealer/proposal/update" method="post" enctype="multipart/form-data">
              <div class="box-body">                    
                <button disabled class="btn btn-block btn-primary btn-flat">Proposal</button>
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Program</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->id_program ?>" class="form-control" placeholder="ID Program" name="id_program">                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <input type="hidden" name="id_proposal" value="<?php echo $row->id_proposal ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Program</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->nama_program ?>" class="form-control" placeholder="Nama Program" name="nama_program">                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tema Program</label>
                  <div class="col-sm-10">
                    <input type="text"  value="<?php echo $row->tema_program ?>" class="form-control" placeholder="Tema Program" name="tema_program">                                        
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Mulai</label>
                  <div class="col-sm-4">
                    <input type="text"  value="<?php echo $row->tgl_mulai ?>" class="form-control" id="tanggal" placeholder="Tanggal Mulai" name="tgl_mulai">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Selesai</label>
                  <div class="col-sm-4">
                    <input type="text"  value="<?php echo $row->tgl_selesai ?>" class="form-control" placeholder="Tanggal Selesai" name="tgl_selesai">
                  </div>                  
                </div>                                                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sumber Dana</label>
                  <div class="col-sm-4">
                    <input type="checkbox" <?php if($row->ahm=='on') echo 'checked' ?>  name="ahm"> AHM 
                    <input type="checkbox" <?php if($row->md=='on') echo 'checked' ?>  name="md"> MD 
                    <input type="checkbox" <?php if($row->dealer=='on') echo 'checked' ?>  name="dealer"> Dealer
                    <input type="checkbox" <?php if($row->lainnya=='on') echo 'checked' ?>  name="lainnya"> Lainnya                    
                  </div>                                
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Target Penjualan</label>
                  <div class="col-sm-2">
                    <input type="text" value="<?php echo $row->target_penjualan ?>" class="form-control" placeholder="Target Penjualan" name="target_penjualan">                    
                  </div>              
                  <label for="inputEmail3" class="col-sm-2 control-label" style="text-align: left">Unit</label>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Event</label>
                  <div class="col-sm-6">
                    <input type="text" value="<?php echo $row->lokasi_event ?>" class="form-control" placeholder="Lokasi Event" name="lokasi_event">                    
                 </div>                  
                </div>
                <button disabled class="btn btn-block btn-success btn-flat">Leasing Pendukung</button>
                <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Leasing</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" multiple="multiple" name="leasing[]" style="color: white">
                      <?php 
                      $isi = explode(',', $row->id_leasing_pendukung);
                      $hasil='';
                      foreach ($isi as $amb) {      
                        $cek = $this->m_admin->getByID("ms_finance_company","id_finance_company",$amb)->row(); ?>

                        <option selected value="<?php echo $cek->id_finance_company ?>"><?php echo $cek->finance_company ?></option>
                      <?php
                      }                                                                                     
                      ?>                      
                      <?php $dt_fin = $this->db->query("SELECT * from ms_finance_company where active=1") ?>
                      <?php foreach ($dt_fin->result() as $fin): ?>
                          <option value="<?php echo $fin->id_finance_company ?>"><?php echo $fin->finance_company ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>                  
                </div>                
                
                <button disabled class="btn btn-block btn-warning btn-flat">Rincian Biaya</button>
                <br>
                <div id="tampil_rincian"></div>                              
                <input type="hidden" id="mode" value="<?php echo $row->id_proposal ?>">
                <button disabled class="btn btn-block btn-danger btn-flat">Deskripsi Program</button>
                <br>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Latar Belakang</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" name="latar_belakang"><?php echo $row->latar_belakang ?></textarea>
                  </div>                  
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Detail Pelaksanaan</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" name="isi"><?php echo $row->isi ?></textarea>
                  </div>                
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Penutup</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" name="penutup"><?php echo $row->penutup ?></textarea>
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Proposal</label>
                  <div class="col-sm-4">
                    <!--<input type="text" class="form-control" placeholder="Jenis Proposal" name="jenis_proposal">                    -->
                    <select class="form-control" name="jenis_proposal" disabled>                      
                      <option <?php if($row->jenis_proposal == 'Proposal Internal') echo "selected" ?> value="Proposal Internal">Proposal Internal</option>
                      <option <?php if($row->jenis_proposal == 'Proposal Eksternal') echo "selected" ?> value="Proposal Eksternal">Proposal Eksternal</option>
                    </select>
                  </div>                                    
                </div>
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
    }elseif($set=="view"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/proposal/add">
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
              <th>ID Program</th>              
              <th>Nama Program</th>              
              <th>Tema Program</th>              
              <th>Tanggal Mulai</th>              
              <th>Tanggal Selesai</th>
              <th>Jenis Proposal</th> 
              <th>Status</th>             
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_proposal->result() as $row) {         
            $tombol = '';           
            if($row->status == 'input'){
              if($row->jenis_proposal == 'Proposal Internal'){
                $tombol = "<a href=\"dealer/proposal/send?id=$row->id_proposal\" class=\"btn btn-flat btn-xs btn-info\"><i class=\"fa fa-send\"></i></a>";
              }
              $status = "<span class='label label-warning'>Draft</span>";
            }elseif($row->status == 'approved'){
              $status = "<span class='label label-success'>Approve by MD</span>";
            }elseif($row->status == 'waiting'){
              $status = "<span class='label label-warning'>Waiting MD</span>";
            }elseif($row->status == 'rejected'){
              $status = "<span class='label label-danger'>Reject by MD</span>";
            }elseif($row->status == 'revisi'){
              $status = "<span class='label label-info'>Revisi</span>";
              $tombol = "<a href=\"dealer/proposal/send?id=$row->id_proposal\" class=\"btn btn-flat btn-xs btn-info\"><i class=\"fa fa-send\"></i></a>
                        <a href=\"dealer/proposal/edit?id=$row->id_proposal\" class=\"btn btn-flat btn-xs btn-primary\"><i class=\"fa fa-edit\"></i></a>";
            }
				    echo "
            <tr>
              <td>$no</td>
              <td>$row->id_program</td>
              <td>$row->nama_program</td>
              <td>$row->tema_program</td>
              <td>$row->tgl_mulai</td>
              <td>$row->tgl_selesai</td>                            
              <td>$row->jenis_proposal</td>              
              <td>$status</td>              
              <td align='center'> ";
                  echo "<a href=\"dealer/proposal/detail?id=$row->id_proposal\" class=\"btn btn-flat btn-xs btn-warning\"><i class=\"fa fa-eye\"></i></a>";
                  echo $tombol;
              //echo "  <button class='btn btn-flat btn-xs btn-success cetak' id_proposal='$row->id_proposal' type='button'><i class='fa fa-print'></i> Cetak Proposal</button>        ";
              echo "
              </td>
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
  var mode = $("#mode").val();
  $.ajax({
      url : "<?php echo site_url('dealer/proposal/showRincian')?>",
      type:"POST",
      data:"mode="+mode,   
      cache:false,   
      success: function(html){     
        $("#tampil_rincian").html(html);                
      }        
  })
}
function delRincian(a){ 
    var id_rincian  = a;       
    $.ajax({
        url : "<?php echo site_url('dealer/proposal/delete_rincian')?>",
        type:"POST",
        data:"id_rincian="+id_rincian,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              auto();
            }
        }
    })
}
function saveRincian()
{
        var item = $("#item").val();
        var keterangan = $("#keterangan").val();
        var qty = $("#qty").val();
        var harga = $("#harga").val();
        var ppn = $("#ppn").val();
       $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('dealer/proposal/saveRincian');?>",
               type:"POST",
               data:"item="+item
                  +"&qty="+qty
                  +"&keterangan="+keterangan
                  +"&ppn="+ppn
                  +"&harga="+harga,
               cache:false,
               success:function(html){
               	  $('#loading-status').hide();
                  $('#tampil_rincian').html(html);
               },
               statusCode: {
            500: function() {
              $('#loading-status').hide();
              alert('Terjadi Kesalahan Saat Menambahkan Data');
            }
          }
          });
}
</script>
<script type="text/javascript">
  $(document).on("click",".cetak",function(){ 
      var id_proposal=$(this).attr('id_proposal');
       var h=700;
       var w=850;
       var left = (screen.width/2)-(w/2);
      var top = (screen.height/2)-(h/2);
      var targetWin = window.open ('dealer/proposal/print_proposal/'+id_proposal, "Cetak Proposal", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
      //location.reload();
        })
</script>