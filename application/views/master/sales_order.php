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
<body onload="cek()">
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
          <a href="dealer/sales_order">
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
            <form class="form-horizontal" action="dealer/sales_order/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <button class="btn btn-block btn-primary btn-flat" disabled> SALES ORDER </button> <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No SPK *</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="no_spk" required  id="no_spk">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_spk->result() as $val) {
                        echo "
                        <option value='$val->no_spk'>$val->no_spk</option>;
                        ";
                      }
                      ?>
                    </select> 
                  </div>
                  <div class="col-sm-4">
                    <button type="button" onclick="cek_spk()" class="btn btn-flat btn-primary">Generate</button>
                  </div>  
                </div>
                <button class="btn btn-block btn-success btn-flat" disabled> DATA KONSUMEN </button> <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Type *</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" id="type"  placeholder="Type" name="type">                                                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly id="warna"  placeholder="warna" name="warna">                                                     
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun Rakitan</label>
                  <div class="col-sm-4">                    
                   <input type="text" class="form-control" readonly id="tahun_rakitan"  placeholder="Tahun Rakitan" name="tahun_rakitan">                                                 
                  </div>
                   
                </div>
                <div class="form-group">   
                   <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="no_mesin" required placeholder="No Mesin" name="no_mesin">                    
                  </div>
                  <div class="col-sm-1">
                    <a class="btn btn-primary btn-flat btn-sm"  data-toggle="modal" data-target="#Nosinmodal" type="button"><i class="fa fa-search"></i> Browse</a>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" id="no_rangka"  placeholder="No Rangka" name="no_rangka">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="id_customer" readonly placeholder="ID Customer" name="id_customer">                
                  </div>                  
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kelurahan" id="id_kelurahan">
                    <input type="text" class="form-control" readonly id="kelurahan" placeholder="Kelurahan Domisili"  name="kelurahan">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                    <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan Domisili"  name="kecamatan">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                    <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten Domisili" id="kabupaten" name="kabupaten">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_provinsi" id="id_provinsi">
                    <input type="text" class="form-control" readonly placeholder="Provinsi Domisili" id="provinsi" name="provinsi">                                        
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili</label>
                  <div class="col-sm-10"> 
                    <input type="text"  class="form-control" placeholder="Alamat Domisili" readonly id="alamat" name="alamat">                    
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-10">
                    <input type="text"  class="form-control" placeholder="Nama Konsumen" readonly id="nama_konsumen" name="nama_konsumen">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tempat/Tgl.Lahir/</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Tempat Lahir" readonly id="tempat_lahir" name="tempat_lahir">                                                            
                  </div>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" readonly placeholder="Tgl Lahir" id="tgl_lahir" name="tgl_lahir">                                                            
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembelian</label>
                  <div class="col-sm-4">                                            
                    <input type="text" readonly class="form-control" placeholder="Jenis Pembelian" name="jenis_pembelian" id="jenis_pembelian">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kewarganegaraan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Jenis Kewarganegaraan" name="jenis_wa" id="jenis_wa">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP/KITAS</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No KTP/KITAS" name="no_ktp" id="no_ktp" readonly>                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No KK</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="No KK" name="no_kk" id="no_kk">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Upload Foto KTP</label>
                  <div class="col-sm-10">                    
                    <a class="btn btn-primary btn-flat btn-sm"  data-toggle="modal" data-target="#Fotomodal" type="button"><i class="fa fa-eye"></i> Lihat Foto</a>                  
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Apakah alamat domisili sama dengan alamat di KTP?</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanya" readonly name="tanya">
                  </div>                  
                </div>

                <span id="tampil_alamat">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Sesuai KTP</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" id="id_kelurahan2" name="id_kelurahan2" onchange="take_kec2()">
                        <option value="">- choose -</option>                        
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_kecamatan" id="id_kecamatan2">
                      <input type="text" class="form-control" readonly id="kecamatan2" placeholder="Kecamatan Sesuai KTP"  name="kecamatan2">                                        
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_kabupaten" id="id_kabupaten2">
                      <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten Sesuai KTP" id="kabupaten2" name="kabupaten2">                                        
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_provinsi" id="id_provinsi2">
                      <input type="text" class="form-control" readonly placeholder="Provinsi Sesuai KTP" id="provinsi2" name="provinsi2">                                        
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat Sesuai KTP</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" placeholder="Alamat Sesuai KTP"  name="alamat2">                                        
                    </div>
                  </div>
                </span>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No HP" readonly id="no_hp" name="no_telp">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Email" readonly id="email" name="email">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No NPWP" readonly id="no_npwp" name="no_npwp">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama pada BPKB</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama pada BPKB" readonly id="nama_bpkb" name="nama_bpkb">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Denah Lokasi</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Denah Lokasi" readonly id="denah" name="denah">                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Customer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Tipe Customer" readonly id="tipe_customer" name="tipe_customer">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Pekerjaan" readonly id="pekerjaan" name="pekerjaan">                                                                                                             
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Grup Astra</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Grup Astra" readonly id="grup_astra" name="grup_astra">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jabatan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Jabatan" readonly id="jabatan" name="jabatan">                                        
                  </div> 
                </div>
                <div class="form-group">                        
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Penghasilan</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Penghasilan" readonly id="penghasilan" name="penghasilan">                                                                                                              
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya Surat (BBN)</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Biaya Surat (BBN)" readonly id="biaya_bnn" name="biaya_bnn">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Refferal ID</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly id="refferal_id" placeholder="Refferal ID"  name="refferal_id">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">RO BD ID</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly id="robd_id" placeholder="Ro BD ID"  name="robd_id">                                        
                  </div>
                </div>
               
                <button class="btn btn-block btn-warning btn-flat" disabled> SISTEM PEMBELIAN TUNAI </button> <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Harga Tunai</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Harga Tunai" readonly id="harga_tunai" name="harga_tunai">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">On/Off The Road</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="On/Off The Road" readonly id="the_road" name="the_road">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Program Umum</label>
                  <div class="col-sm-4">                    
                        <input type="text" class="form-control" placeholder="Program Umum" readonly id="program_umum" name="program_umum">                                                  
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Program Khusus</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Program Khusus" name="program_khusus" readonly id="program_khusus">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Bayar</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Total Bayar" name="total_bayar" id="total_bayar" readonly>
                  </div>                  
                </div>

                <button class="btn btn-block btn-warning btn-flat" disabled> SISTEM PEMBELIAN KREDIT </button> <br>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Leasing/Finco</label>
                  <div class="col-sm-4">                    
                     <input type="text" class="form-control" placeholder="Leasing/Finco" name="leasing" readonly id="leasing">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Uang Muka/DP</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Uang Muka/DP" name="dp" id="dp">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Voucher</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Voucher" name="voucher" readonly id="voucher">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Angsuran/Bulan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Angsuran/Bulan" name="angsuran" id="angsuran" readonly>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Rumah</label>
                  <div class="col-sm-4">                    
                     <input type="text" class="form-control" placeholder="Status Rumah" name="status_rumah" readonly id="status_rumah">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Kawin</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Status Kawin" name="status_kawin" readonly id="status_kawin">
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pasangan</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Nama Pasangan" name="nama_pasangan" id="nama_pasangan" readonly>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Tanggungan</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Jumlah Tanggungan" name="jumlah_tanggungan" id="jumlah_tanggungan" readonly>
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Lama Tinggal</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Lama Tinggal" name="lama_tinggal" id="lama_tinggal" readonly>
                  </div>                                    
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No PO Leasing</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="No PO Leasing" name="no_po_leasing">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal PO Leasing</label>
                   <div class="col-sm-4">
                    <input type="text" id="tanggal7" class="form-control" placeholder="Tanggal PO Leasing" name="tgl_po_leasing">                                        
                  </div>                 
                </div>
                        
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="button" onClick="cancel_tr()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    
    <div class="modal fade" id="Fotomodal">      
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            Lihat Foto
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
          </div>
          <div class="modal-body">
            <!--img src="assets/panel/files/<?php echo $row->file_foto ?>" width="100%"-->
            None
          </div>      
        </div>
      </div>
    </div>

    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/sales_order/add">
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No So</th>                            
              <th>No Mesin</th>
              <th>No Rangka</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>Nama Konsumen</th>              
              <th>Alamat</th>              
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_sales_order->result() as $row) {     
            //$s = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor = '$row->ekspedisi'")->row();          
            echo "
            <tr>
              <td>$no</td>              
              <td>$row->id_sales_order</td>
              <td>$row->no_mesin</td>
              <td>$row->no_rangka</td>
              <td>$row->tipe_ahm</td>
              <td>$row->warna</td>
              <td>$row->nama_konsumen</td>
              <td>$row->alamat</td>
              <td>                
                <a href='dealer/sales_order/cetak_kwitansi?id=$row->id_sales_order'>
                  <button class='btn btn-flat btn-xs bg-maroon'><i class='fa fa-print'></i> Cetak Kwitansi</button>
                </a>
                <a href='dealer/sales_order/cetak_so?id=$row->id_sales_order'>
                  <button class='btn btn-flat btn-xs bg-blue'><i class='fa fa-print'></i> Cetak So & Invoice</button>
                </a>
                <a href='dealer/sales_order/cetak_barcode?id=$row->id_sales_order'>
                  <button class='btn btn-flat btn-xs btn-danger'><i class='fa fa-print'></i> Cetak Barcode AHASS</button>
                </a>
                <a href='dealer/sales_order/cetak_bstk?id=$row->id_sales_order'>
                  <button class='btn btn-flat btn-xs btn-success'><i class='fa fa-print'></i> Cetak BSTK</button>
                </a>
                <a href='dealer/sales_order/cetak_sppu?id=$row->id_sales_order'>
                  <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak Surat Perintah Pengiriman Unit</button>
                </a>
                <a href='dealer/sales_order/edit?id=$row->id_sales_order'>
                  <button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button>
                </a>
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
          $id_dealer = $this->m_admin->cari_dealer();
          $dt_nosin = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.no_mesin,tr_scan_barcode.no_rangka,ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,ms_warna.id_warna,ms_warna.warna 
            FROM tr_penerimaan_unit_dealer_detail LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin=tr_scan_barcode.no_mesin 
            LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
            LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
            LEFT JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
            LEFT JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna WHERE tr_penerimaan_unit_dealer_detail.status_dealer = 'input'
            AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'
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

<script type="text/javascript">
function cek(){
  $("#tampil_alamat").hide();
}
function cek_spk(){
  var no_spk = $("#no_spk").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/sales_order/take_spk')?>",
      type:"POST",
      data:"no_spk="+no_spk,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");                    
          $("#type").val(data[0]);                                                    
          $("#warna").val(data[1]);                                                    
          $("#tahun_rakitan").val(data[2]);                                                    
          $("#id_customer").val(data[3]);                                                    
          $("#nama_customer").val(data[4]);
          $("#no_hp").val(data[5]);
          $("#tipe_ahm").val(data[6]);
          $("#id_kelurahan").val(data[7]);          
          $("#alamat").val(data[8]);          
          $("#nama_konsumen").val(data[4]);          
          $("#tempat_lahir").val(data[9]);          
          $("#tgl_lahir").val(data[10]);          
          $("#no_ktp").val(data[11]);          
          $("#tanya").val(data[12]);          
          $("#id_kelurahan2").val(data[13]);          
          $("#no_hp").val(data[14]);          
          $("#email").val(data[15]);          
          $("#no_npwp").val(data[16]);          
          $("#nama_bpkb").val(data[17]);          
          $("#denah").val(data[18]);          
          $("#tipe_customer").val(data[19]);          
          $("#pekerjaan").val(data[20]);          
          $("#grup_astra").val(data[21]);          
          $("#jabatan").val(data[22]);          
          $("#penghasilan").val(data[23]);          
          $("#biaya_bnn").val(data[24]);          
          $("#harga_tunai").val(data[25]);          
          $("#the_road").val(data[26]);          
          $("#program_umum").val(data[27]);          
          $("#program_khusus").val(data[28]);          
          $("#total_bayar").val(data[29]);          
          $("#leasing").val(data[30]);          
          $("#dp").val(data[31]);          
          $("#voucher").val(data[32]);          
          $("#angsuran").val(data[33]);          
          $("#status_rumah").val(data[34]);          
          $("#status_kawin").val(data[35]);          
          $("#nama_pasangan").val(data[36]);          
          $("#jumlah_tanggungan").val(data[37]);          
          $("#lama_tinggal").val(data[38]);          
          $("#jenis_pembelian").val(data[39]);          
          $("#jenis_wa").val(data[40]);          
          $("#no_kk").val(data[41]);          
          $("#refferal_id").val(data[42]);          
          $("#robd_id").val(data[43]);          
          take_kec();
          cek_tanya();
      } 
  })
}
function take_kec(){
  var id_kelurahan = $("#id_kelurahan").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/sales_order/take_kec')?>",
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
function cek_tanya(){
  var tanya = $("#tanya").val();
  if(tanya == 'Tidak'){
    $("#tampil_alamat").show();
    $("#id_kecamatan2").val("");    
    $("#id_kabupaten2").val("");
    $("#id_kelurahan2").val("");
    $("#id_provinsi2").val("");
  }else{
    $("#tampil_alamat").hide();
    document.getElementById("id_kecamatan2").value = $("#id_kecamatan").val();    
    document.getElementById("id_kabupaten2").value = $("#id_kabupaten").val();
    document.getElementById("id_kelurahan2").value = $("#id_kelurahan").val();
    document.getElementById("id_provinsi2").value  = $("#id_provinsi").val();    
  }
}
function take_kec2(){
  var id_kelurahan = $("#id_kelurahan2").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/sales_order/take_kec')?>",
      type:"POST",
      data:"id_kelurahan="+id_kelurahan,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");                    
          $("#id_kecamatan2").val(data[0]);                                                    
          $("#kecamatan2").val(data[1]);                                                    
          $("#id_kabupaten2").val(data[2]);                                                    
          $("#kabupaten2").val(data[3]);                                                    
          $("#id_provinsi2").val(data[4]);                                                    
          $("#provinsi2").val(data[5]);                                                    
          $("#kelurahan2").val(data[6]);                                                    
      } 
  })
}
function chooseitem(no_mesin){
  document.getElementById("no_mesin").value = no_mesin; 
  cek_nosin();
  $("#Nosinmodal").modal("hide");
}
function cek_nosin(){
  var no_mesin = $("#no_mesin").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/sales_order/cek_nosin')?>",
      type:"POST",
      data:"no_mesin="+no_mesin,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            $("#no_mesin").val(data[1]);                
            $("#no_rangka").val(data[2]);                                        
          }else{
            alert(data[0]);
          }
      } 
  })
}


</script>