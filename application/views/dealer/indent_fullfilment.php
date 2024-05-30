<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Penjualan Unit</li>
    <li class="">Indent Fulfillment List</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>

  <section class="content">
    <?php 
    if($set=="form"){
      $form     = '';
      $disabled = '';
      $readonly ='';
      if ($mode=='insert') {
        $form = 'save';
      }
      if ($mode=='edit') {
        // $readonly ='readonly';
        $form = 'save_edit';
      }
      if ($mode=='detail') {
        $disabled = 'disabled';
      }
    ?>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

<script>
  Vue.use(VueNumeric.default);
  $(document).ready(function(){
    <?php if (isset($row)) { ?>
        $('#id_tipe_kendaraan').val('<?= $row->id_tipe_kendaraan ?>').trigger('change');
        $('#tanya').val('<?= $row->alamat_sama ?>').trigger('change');
        Chooseitem('<?= $row->id_customer ?>');
        chooseitem('<?= $row->id_kelurahan ?>');
        getWarna('<?= $row->id_warna ?>');
    <?php } ?>
  })
</script>
   <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="dealer/spk">

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

            <form id="form_" class="form-horizontal" action="dealer/spk/save" method="post" enctype="multipart/form-data">

              <div class="box-body">              

                <button class="btn btn-block btn-primary btn-flat" disabled> SPK </button> <br>
                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sales People</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" id="sales_people" name="sales_people">
                  </div>
                   <label for="inputEmail3" class="col-sm-2 control-label">FLP ID</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" id="flp_id" name="flp_id">
                  </div>
                </div>
                <div class="form-group">

                  <input type="hidden" readonly class="form-control" id="id_spk" readonly placeholder="No SPK" name="no_spk">                                        

                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">No SPK *</label>

                  <div class="col-sm-4">

                  </div> -->

                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal</label>

                  <div class="col-sm-4">                    

                    <input type="text" readonly class="form-control" value="<?php echo $row->tgl_spk ?>"  id="tanggal" readonly placeholder="Tanggal"  name="tgl_spk" required>                                       

                  </div>



                </div>

                <button class="btn btn-block btn-success btn-flat" disabled> DATA KONSUMEN </button> <br>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">ID Customer *</label>

                  <div class="col-sm-4">                    

                  <input type="text" class="form-control" value="<?php echo $row->id_customer ?>" onpaste="return false" autocomplete="off" onkeypress="return nihil(event)" name="id_customer" id="id_customer" placeholder="ID Customer" required readonly>

                  </div>

                <!--   <div class="col-sm-4">                                        

                    <a class="btn btn-primary btn-flat btn-sm" data-toggle="modal" data-target="#Customermodal" type="button"><i class="fa fa-search"></i> Browse</a>

                  </div>  
 -->
                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Sesuai Identitas *</label>

                  <div class="col-sm-10">

                    <input type="text"  class="form-control"  id="nama_konsumen" value="<?php echo $row->nama_konsumen ?>"  placeholder="Nama Sesuai Identitas" name="nama_konsumen" required readonly>                               

                  </div>

                </div>                

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Tempat/Tgl.Lahir *</label>

                  <div class="col-sm-4">                    

                    <input type="text" id="tempat_lahir" class="form-control" value="<?php echo $row->tempat_lahir ?>" placeholder="Tempat Lahir" name="tempat_lahir" required readonly>                                                         

                  </div>

                  <div class="col-sm-4">                    

                    <input type="text" class="form-control tgl_lahir" onchange="cek_umur()" id="tanggal2" value="<?php echo $row->tgl_lahir ?>" placeholder="Tgl Lahir" name="tgl_lahir" required readonly>                                                            

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kewarganegaraan *</label>

                  <div class="col-sm-4">

                  <select class="form-control" id="jenis_wn"  name="jenis_wn" required readonly>

                      <option><?php echo $row->jenis_wn ?></option>

                      <option>WNA</option>

                      <option selected>WNI</option>

                    </select>

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP/KITAS *</label>

                  <div class="col-sm-4">

                     <input type="text" value="<?php echo $row->no_ktp ?>" id="no_ktp" class="form-control"  onkeypress="return number_only(event)" placeholder="No KTP/KITAS" name="no_ktp"   minlength="16" maxlength="16"  required readonly>      

                  </div>                

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">No KK *</label>

                  <div class="col-sm-4">

                    <input type="text" value="<?php echo $row->no_kk ?>" id="no_kk" maxlength="16" class="form-control"  onkeypress="return number_only(event)" placeholder="No KK" name="no_kk" required readonly>                     

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">No NPWP *</label>

                  <div class="col-sm-4">

                  <input type="text" class="form-control" value="<?php echo $row->npwp ?>" placeholder="No NPWP" id="no_npwp" name="npwp" required readonly>                                         

                  </div>

                </div>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Upload Foto KTP (Maks 100Kb) *</label>

                  <div class="col-sm-4">

                       <input type="file" class="form-control" placeholder="Upload Foto" value="<?php echo $row->file_foto ?>" name="file_foto">                                      

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Upload KK (Maks 500 Kb) *</label>

                  <div class="col-sm-4">

                  <input type="file" class="form-control" placeholder="Upload KK (Maks 500 Kb)" value="<?php echo $row->file_kk ?>" name="file_kk">                 
                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili *</label>

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

                    <input type="text" value="<?php echo $kel ?>" required type="text" onpaste="return false" onkeypress="return nihil(event)" autocomplete="off" name="kelurahan" data-toggle="modal" placeholder="Kelurahan Domisili" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()" readonly>                                                 

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

                    <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten Domisili" id="kabupaten" name="kabupaten" required>                                        

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Domisili</label>

                  <div class="col-sm-4">

                    <input type="hidden" name="id_provinsi" id="id_provinsi">

                    <input type="text" class="form-control" readonly placeholder="Provinsi Domisili" id="provinsi" name="provinsi" required>                                        

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili *</label>

                  <div class="col-sm-10">

                    <input type="text" class="form-control" maxlength="100" placeholder="Alamat Domisili" value="<?php echo $row->alamat ?>" name="alamat" required readonly>

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Kodepos *</label>

                  <div class="col-sm-4">                    

                    <input type="text" class="form-control" placeholder="Kodepos" id="kodepos" name="kodepos" required readonly>                                        

                  </div>

                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Longitude</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" placeholder="Longitude"  name="longitude" id="longitude" value="<?= $row->longitude ?>" readonly>                                        
                    </div>
                   <label for="inputEmail3" class="col-sm-2 control-label">Nama Pada BPKB/STNK</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control"  name="nama_bpkb" value="<?= $row->nama_bpkb ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Latitude</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Latitude"  name="latitude" id="latitude" value="<?= $row->latitude ?>" readonly>
                  </div> 
                    <label for="inputEmail3" class="col-sm-2 control-label">No. KTP/KITAP Pada BPKB</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control"   name="no_ktp_bpkb" value="<?= $row->no_ktp_bpkb ?>" readonly>
                  </div> 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">RT</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="RT"  name="rt" value="<?= $row->rt ?>" readonly>
                  </div> 
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat KTP/KITAP Pada BPKB</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control"   name="alamat_ktp_bpkb" value="<?= $row->alamat_ktp_bpkb ?>" readonly> 
                  </div> 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">RW</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="RW"  name="rw" value="<?= $row->rw ?>" readonly>
                  </div> 
                </div>  
               <!--  <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Denah Lokasi</label>

                  <div class="col-sm-10">

                    <input type="text" class="form-control" placeholder="Latitude,Longitude"  name="denah_lokasi">                                        

                  </div>                  

                </div> -->

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Apakah alamat domisili sama dengan alamat di KTP? *</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="tanya" id="tanya" onchange="cek_tanya()" required readonly>

                      <option value="">- choose -</option>

                      <option>Ya</option>                      

                      <option>Tidak</option>

                    </select>

                  </div>                  

                </div>



                <span id="tampil_alamat">

                  <div class="form-group">

                    <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Sesuai KTP</label>

                    <div class="col-sm-4">

                      <input type="hidden" readonly name="id_kelurahan2"  id="id_kelurahan2">                      

                      <input type="text" type="text" onpaste="return false" onkeypress="return nihil(event)" name="kelurahan2" data-toggle="modal" data-target="#Kelurahanmodal2" class="form-control" id="kelurahan2" onchange="take_kec2()" placeholder="Kelurahan Sesuai KTP">                                            

                    </div>

                    <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Sesuai KTP</label>

                    <div class="col-sm-4">

                      <input type="hidden" name="id_kecamatan2" id="id_kecamatan2">

                      <input type="text" readonly class="form-control"  id="kecamatan2" placeholder="Kecamatan Sesuai KTP"  name="kecamatan2">                                        

                    </div>

                  </div>

                  <div class="form-group">

                    <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Sesuai KTP</label>

                    <div class="col-sm-4">

                      <input type="hidden" name="id_kabupaten" id="id_kabupaten2">

                      <input type="text" readonly class="form-control"  placeholder="Kota/Kabupaten Sesuai KTP" id="kabupaten2" name="kabupaten2">                                        

                    </div>

                    <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Sesuai KTP</label>

                    <div class="col-sm-4">

                      <input type="hidden" name="id_provinsi2" id="id_provinsi2">

                      <input type="text" readonly class="form-control"  placeholder="Provinsi Sesuai KTP" id="provinsi2" name="provinsi2">                                        

                    </div>

                  </div>

                  <div class="form-group">

                    <label for="inputEmail3" class="col-sm-2 control-label">Kodepos Sesuai KTP</label>

                    <div class="col-sm-4">

                      <input type="text" class="form-control" placeholder="Kodepos Sesuai KTP"  name="kodepos2">                                        

                    </div>

                  </div>

                  <div class="form-group">

                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat Sesuai KTP</label>

                    <div class="col-sm-10">

                      <input type="text" class="form-control" maxlength="100" placeholder="Alamat Sesuai KTP"  name="alamat2">                                        

                    </div>

                  </div>

                </span>



                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Status Rumah</label>

                  <div class="col-sm-4">                    

                    <select class="form-control" name="status_rumah" readonly>
                      <option><?php echo $row->status_rumah ?></option>
                      <option value="">- choose -</option>
          
                      <option value="Rumah Sendiri">Rumah Sendiri</option>

                      <option value="Rumah Orang Tua">Rumah Orang Tua</option>

                      <option value="Rumah Sewa">Rumah Sewa</option>                      

                    </select>

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Lama Tinggal</label>

                  <div class="col-sm-4">                    

                    <input type="text" class="form-control" placeholder="Lama Tinggal" name="lama_tinggal" value="<?= $row->lama_tinggal ?>" readonly>

                  </div>  

                </div>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>

                  <div class="col-sm-4">                    

                    <select class="form-control" id="pekerjaan"  name="pekerjaan" readonly>
                    <option value="<?php echo $row->pekerjaan ?>"><?= $this->db->get_where('ms_pekerjaan',['id_pekerjaan'=>$row->pekerjaan])->row()->pekerjaan ?></option>
                      <option value="">- choose -</option>

                      <?php 

                      foreach($dt_pekerjaan->result() as $val) {

                        echo "

                        <option value='$val->id_pekerjaan'>$val->pekerjaan</option>;

                        ";

                      }

                      ?>

                    </select>                                                    

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Lama Kerja</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" placeholder="Lama Kerja" name="lama_kerja" value="<?= $row->lama_kerja ?>" readonly>                    

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Jabatan</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" placeholder="Jabatan" name="jabatan" value="<?= $row->jabatan ?>" readonly>                    

                  </div>                 

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Total Penghasilan</label>

                  <div class="col-sm-4">

                    <input type="number" class="form-control" placeholder="Total Penghasilan" name="penghasilan" value="<?= $row->penghasilan ?>" readonly>

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Pengeluaran Perbulan *</label>

                  <div class="col-sm-4">                    

                    <select class="form-control" name="pengeluaran_bulan" required readonly>

                      <option value="">- choose -</option>

                      <?php 

                      foreach($dt_pengeluaran->result() as $val) {
                        $selected = $val->id_pengeluaran_bulan==$row->pengeluaran_bulan?'selected':'';
                        echo "

                        <option value='$val->id_pengeluaran_bulan' $selected>$val->pengeluaran</option>;

                        ";

                      }

                      ?>  

                    </select>                                                    

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">No HP #1 *</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control"  placeholder="No HP" id="no_hp" maxlength="15" name="no_hp" required value="<?= $row->no_hp ?>" readonly>                                        

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp #1 *</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="status_hp" id="status_nohp" required readonly>

                      <option value="">- choose -</option>

                      <?php 

                      foreach($dt_status_hp->result() as $val) {
                        $selected = $val->id_status_hp==$row->status_hp?'selected':'';

                        echo "

                        <option value='$val->id_status_hp' $selected>$val->status_hp</option>;

                        ";

                      }

                      ?>

                    </select>                                                     

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">No HP #2</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control"  placeholder="No HP" id="no_hp2" maxlength="15" name="no_hp_2" value="<?= $row->no_hp_2 ?>" readonly>                                        

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp #2</label>

                  <div class="col-sm-4">

                    <select class="form-control" name="status_hp_2" id="status_nohp2" value="<?= $row->status_hp_2 ?>" disabled>

                      <option value="">- choose -</option>

                      <?php 

                      foreach($dt_status_hp->result() as $val) {
                        $selected = $val->id_status_hp==$row->status_hp?'selected':'';

                        echo "

                        <option value='$val->id_status_hp' $selected>$val->status_hp</option>;

                        ";

                      }

                      ?>

                    </select>                                                     

                  </div>

                </div>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control"  placeholder="No Telp" maxlength="15" id="no_telp" name="no_telp" value="<?= $row->no_telp ?>" disabled>                                        

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Email *</label>

                  <div class="col-sm-4">

                    <input type="email" maxlength="100" class="form-control"  placeholder="Email" id="email" name="email" required value="<?= $row->email ?>" disabled>                                        

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Refferal ID</label>

                  <div class="col-sm-4">

                    <input type="text" readonly class="form-control" placeholder="Refferal ID"  name="refferal_id" id="refferal_id">                                        

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">RO BD ID</label>

                  <div class="col-sm-3">

                    <input type="text" readonly class="form-control" placeholder="Ro BD ID"  name="robd_id" id="robd_id">                                        

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Refferal ID</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" placeholder="Nama Refferal ID" name="nama_refferal_id" id="nama_refferal_id" readonly>                    

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Nama RO BD ID</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" placeholder="Nama RO BD ID" name="nama_robd_id" readonly id="nama_robd_id">                    

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Gadis Ibu Kandung *</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" placeholder="Nama Gadis Ibu Kandung" name="nama_ibu" required value="<?= $row->nama_ibu ?>" disabled>                    

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir Ibu Kandung *</label>

                  <div class="col-sm-4">

                    <input type="text" id="tanggal3" class="form-control" placeholder="Tgl Lahir Ibu Kandung" required name="tgl_ibu" value="<?= $row->tgl_ibu ?>" disabled>                    

                  </div>

                <div class="form-group">

                </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>

                  <div class="col-sm-4">

                    <input type="text" class="form-control" placeholder="Keterangan" maxlength="200" name="keterangan" value="<?= $row->keterangan ?>" disabled>                    

                  </div>

                </div>



                <button class="btn btn-block btn-danger btn-flat" disabled> DATA KENDARAAN </button> <br>

                <div class="form-group">                                    

                  <label for="inputEmail3" class="col-sm-2 control-label">Type *</label>

                  <div class="col-sm-4">                    

                    <input type="hidden" id="warna_mode">

                    <select class="form-control" name="id_tipe_kendaraan" id="id_tipe_kendaraan" onchange="take_harga()" onclick="getWarna()" required>                     

                      <?php 

                      if(isset($_SESSION['id_tipe'])){

                        $tipe = $_SESSION['id_tipe'];

                        echo "<option value='$tipe'>";

                        $dt_cust    = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$tipe)->row();                                 

                        if(isset($dt_cust)){

                          echo "$dt_cust->id_tipe_kendaraan | $dt_cust->tipe_ahm";

                        }else{

                          echo "- choose -";

                        }

                        ?>

                        </option>

                      <?php

                      }

                      if($dt_tipe->num_rows()>0) {                        

                        foreach($dt_tipe->result() as $val) {

                          echo "

                          <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan | $val->tipe_ahm</option>;

                          ";

                        }

                      }

                      ?>

                    </select>

                  </div>                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Warna *</label>

                  <div class="col-sm-4">

                    <!-- <input type="text" class="form-control" name="id_warna" readonly id="id_warna" placeholder="Warna">                                                     -->

                    <select class="form-control" name="id_warna" id="id_warna" required onchange="take_harga();get_beli();" onclick="getWarna2()">

                      <?php 

                      if(isset($_SESSION['id_warna'])){

                        $warna = $_SESSION['id_warna'];

                        echo "<option value='$warna'>";

                        $dt_cust    = $this->m_admin->getByID("ms_warna","id_warna",$warna)->row();                                 

                        if(isset($dt_cust)){

                          echo "$dt_cust->id_warna | $dt_cust->warna";

                        }else{

                          echo "- choose -";

                        }

                        ?>

                        </option>

                      <?php

                      } ?>                      

                    </select>                    

                  </div>

                  <!-- <div class="col-sm-1">

                    <button onclick="take_harga()" type="button">generate</button>

                  </div> -->

                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Pengiriman</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control datepicker" placeholder="Tanggal Pengiriman" name="tgl_pengiriman" value="<?= $row->tgl_pengiriman ?>">
                  </div>
                </div>
                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Harga</label>

                  <div class="col-sm-4">                    

                    <input type="hidden" name="harga" id="harga">

                    <input type="text" class="form-control" placeholder="Harga" readonly name="harga_r" id="harga_r">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">PPN</label>

                  <div class="col-sm-4">                    

                    <input type="hidden" name="ppn" id="ppn">                  

                    <input type="text" class="form-control" placeholder="PPN" readonly name="ppn_r" id="ppn_r">                  

                  </div>

                </div>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Harga Off The Road</label>

                  <div class="col-sm-4">                    

                    <input type="hidden" name="harga_off" id="harga_off">

                    <input type="text" class="form-control" placeholder="Harga Off The Road" readonly name="harga_off_r" id="harga_off_r">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya BBN</label>

                  <div class="col-sm-4">                    

                    <input type="hidden" name="biaya_bbn" id="biaya_bbn">                  

                    <input type="text" class="form-control" placeholder="Biaya BBN" readonly name="biaya_bbn_r" id="biaya_bbn_r">                  

                  </div>

                </div>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Harga On The Road</label>

                  <div class="col-sm-4">                    

                    <input type="hidden" name="harga_on" id="harga_on">

                    <input type="text" class="form-control" placeholder="Harga On The Road" readonly name="harga_on_r" id="harga_on_r">

                  </div>
                  <label for="inputEmail3" class="  col-sm-2 control-label">Diskon</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" readonly name="diskon" id="diskon" value="<?= $row->diskon ?>">
                  </div>
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">Nama STNK/BPKB *</label>

                    <div class="col-sm-4">

                      <input id="nama_bpkb" type="text" required class="form-control" placeholder="Nama STNK/BPKB" name="nama_bpkb">

                    </div>  -->                                                                     

                </div>
                 <div class="form-group">                  
                  <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">Tanda Jadi</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" name="tanda_jadi" id="tanda_jadi" value="<?= $row->tanda_jadi ?>" readonly> 
                  </div>
                </div>
                <button class="btn btn-block btn-primary btn-flat" disabled> EVENT </button> <br>
                <div class="form-group">
                  <div class="col-md-12">
                    <table class="table table-bordered">
                    <tr>
                      <td width="50%">ID Event</td>
                      <td>Nama Event</td>
                    </tr>
                    <tr>
                      <td>
                        <select name="id_event" id="id_event" onchange="getEvent()" class="form-control select2" <?= $disabled ?> required>
                          <option value="">--choose-</option>
                          <?php foreach ($event->result() as $rs): 
                            $selected = isset($row)?$rs->id_event==$row->id_event?'selected':'':'';
                          ?>
                            <option value="<?= $rs->id_event ?>" <?= $selected ?> 
                                  data-nama_event  = "<?= $rs->kode_event.' | '.$rs->nama_event ?>"
                            ><?= $rs->nama_event ?></option>
                          <?php endforeach ?>
                        </select>
                      </td>
                      <td>
                    <input type="text" class="form-control" readonly name="nama_event" id="nama_event">
                      </td>
                    </tr>
                  </table>
                  </div>
                   <script>
                    function getEvent() {
                      var nama_event     = $("#id_event").select2().find(":selected").data("nama_event");$('#nama_event').val(nama_event);
                      
                    }
                  </script>                 
                </div>

                <button class="btn btn-block btn-warning btn-flat" disabled> SISTEM PEMBELIAN </button> <br>



                <div class="form-group">                                    

                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Pembelian *</label>

                  <div class="col-sm-3">                    

                    <select class="form-control" name="jenis_beli" id="beli" onchange="get_beli()" required>

                      <option value="">- choose -</option>

                      <option>Kredit</option>

                      <option>Cash</option>

                    </select>                                                     

                  </div>

                  <div class="col-sm-1">                    

                    <button class="btn btn-primary btn-flat" type="button" onclick="get_beli()"><i class="fa fa-refresh"></i> Reload Harga</button>

                  </div>

                </div>



                <span id="lbl_cash">

                  <div class="form-group">                  

                    <label for="inputEmail3" class="col-sm-2 control-label">On/Off The Road</label>

                    <div class="col-sm-4">                    

                      <select class="form-control" name="the_road" id="the_road" onchange="get_on()">

                        <option>Off The Road</option>

                        <option selected>On The Road</option>

                      </select>                                                     

                    </div>

                    <label for="inputEmail3" class="col-sm-2 control-label">Harga Tunai</label>

                    <div class="col-sm-4">                    

                      <input type="hidden" name="harga_tunai" id="harga_tunai">

                      <input type="text" class="form-control" placeholder="Harga Tunai" readonly name="harga_tunai_r" id="harga_tunai_r">

                    </div>

                  </div>                  

                  <div class="form-group" id="div_program_umum">                  

                    <label for="inputEmail3" class="col-sm-2 control-label">Program</label>

                    <div class="col-sm-4">                    

                      <select class="form-control" name="program_umum" onchange="cek_program_tambahan()" id="program_umum">

                        <option value="">- choose -</option>

                        <?php 

                        // $tgl = date("Y-m-d");

                        // $cek = $this->db->query("SELECT * FROM tr_sales_program WHERE '$tgl' BETWEEN periode_awal AND periode_akhir AND (jenis_bayar = 'Cash' OR jenis_bayar = 'Cash & Kredit')");

                        // foreach ($cek->result() as $isi) {

                        //   echo "<option value='$isi->id_sales_program'>$isi->id_program_md</option>";

                        //}

                        ?>                      

                      </select>                                                    

                    </div>

                  <label for="inputEmail3" class="col-sm-2 control-label" id="nilai_voucher_lbl">Nilai Voucher</label>

                    <div class="col-sm-4">

                      <input type="hidden" class="form-control" readonly id="voucher_1" placeholder="Nilai Voucher" name="voucher_1">

                      <input type="text" class="form-control" readonly id="nilai_voucher" placeholder="Nilai Voucher" name="nilai_voucher1">

                    </div>

                  </div>

                  <div class="form-group" id="div_program_gabungan">                  

                  <span id="program_gabungan_lbl">

                      <label for="inputEmail3" class="col-sm-2 control-label">Program Gabungan</label>

                    <div class="col-sm-4">

                       <select class="form-control" name="program_gabungan" id="program_gabungan" onchange="getVoucherGabungan()">

                        <?php 

                        // $tgl = date("Y-m-d");

                        // $cek = $this->db->query("SELECT * FROM tr_sales_program WHERE '$tgl' BETWEEN periode_awal AND periode_akhir AND (jenis_bayar = 'Cash' OR jenis_bayar = 'Cash & Kredit')");

                        // foreach ($cek->result() as $isi) {

                        //   echo "<option value='$isi->id_sales_program'>$isi->id_program_md</option>";

                        //}

                        ?>                      

                      </select> 

                    </div>  

                    </span>            

                     <label for="inputEmail3" class="col-sm-2 control-label">Voucher Tambahan</label>

                    <div class="col-sm-4">                    

                      <input type="text" class="form-control" id="voucher_tambahan_1" placeholder="Voucher Tambahan" name="voucher_tambahan_1" onkeyup="get_total_ck()" autocomplete="off">

                    </div>

                  </div>   

                  <div class="form-group">

                    <div id="div_jenis_barang_cash" style="display: none">

                      <label for="inputEmail3" class="col-sm-2 control-label">Jenis Barang</label>

                    <div class="col-sm-4">                    

                      <input type="text" class="form-control" id="jenis_barang_cash" name="jenis_barang_cash" readonly>

                    </div>

                    </div>

                    <label id="lbl_total_bayar_cash" for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">Total Bayar</label>

                    <div class="col-sm-4">                    

                      <input type="text" class="form-control" id="total_bayar_r" placeholder="Total Bayar" name="total_bayar_r" readonly>

                    </div>

                  </div>   

                </span>









                <span id="lbl_kredit">                                                      

                  <div class="form-group">              

                    <label for="inputEmail3" class="col-sm-12">Data Penjamin</label>

                  </div>

                  <div class="form-group">              

                    <label for="inputEmail3" class="col-sm-2 control-label">Nama</label>

                    <div class="col-sm-4">                    

                      <input type="text" class="form-control" placeholder="Nama Penjamin" name="nama_penjamin">

                    </div>                                                      

                    <label for="inputEmail3" class="col-sm-2 control-label">Finance Company *</label>

                    <div class="col-sm-4">                    

                      <select class="form-control select2" name="id_finance_company">

                        <option value="">- choose -</option>                      

                        <?php 

                        foreach ($dt_finance->result() as $isi) {

                          echo "<option value='$isi->id_finance_company'>$isi->finance_company</option>";

                        }

                        ?>

                      </select>

                    </div>

                  </div>

                  <div class="form-group">                  

                    <label for="inputEmail3" class="col-sm-2 control-label">Hub. dg Penjamin</label>

                    <div class="col-sm-4">                    

                      <select class="form-control" name="hub_penjamin">

                        <option value="">- choose -</option>

                        <option>Suami</option>

                        <option>Istri</option>

                        <option>Kakak</option>

                        <option>Adik</option>

                        <option>Anak</option>

                        <option>Kakek</option>

                        <option>Nenek</option>

                        <option>Ayah</option>

                        <option>Ibu</option>

                        <option>Paman</option>

                        <option>Bibi</option>

                        <option>Sepupu</option>

                        <option>Mertua</option>

                        <option>Keponakan</option>

                        <option>Pacar</option>

                      </select>

                    </div>                       

                    <label for="inputEmail3" class="col-sm-2 control-label">Program</label>

                    <div class="col-sm-4">                    

                      <select class="form-control" name="program_umum_k" id="program_umum" onchange="cek_program_tambahan()">

                        <!-- <option value="">- choose -</option>

                        <?php 

                        // $tgl = date("Y-m-d");

                        // $cek = $this->db->query("SELECT * FROM tr_sales_program WHERE '$tgl' BETWEEN periode_awal AND periode_akhir AND (jenis_bayar = 'Kredit' OR jenis_bayar = 'Cash & Kredit')");

                        // foreach ($cek->result() as $isi) {

                        //   echo "<option value='$isi->id_sales_program'>$isi->id_program_md</option>";

                        // }

                        ?> -->                      

                      </select>

                    </div>

                  </div>

                  <div class="form-group">                  

                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>                    

                    <div class="col-lg-4">

                      <div class="input-group">

                        <div class="input-group-btn">

                          <button tooltip='Samakan dengan alamat domisili di atas' type="button" onclick="samakan()" class="btn btn-flat btn-primary"><i class="fa fa-arrow-circle-down"></i></button>                          

                        </div>                        

                        <input type="text" id="alamat_penjamin" class="form-control" placeholder="Alamat Penjamin" name="alamat_penjamin">

                      </div>

                      <!-- /input-group -->                                    

                    </div>                                

                    <!-- <div class="col-sm-2">                                          

                    </div>   -->

                    <span id="program_gabungan_kredit_lbl">

                      <label for="inputEmail3" class="col-sm-2 control-label">Program Gabungan</label>

                    <div class="col-sm-4">

                       <select class="form-control" name="program_gabungan_k" id="program_gabungan_kredit" onchange="getVoucherGabungan()">

                        <?php 

                        // $tgl = date("Y-m-d");

                        // $cek = $this->db->query("SELECT * FROM tr_sales_program WHERE '$tgl' BETWEEN periode_awal AND periode_akhir AND (jenis_bayar = 'Cash' OR jenis_bayar = 'Cash & Kredit')");

                        // foreach ($cek->result() as $isi) {

                        //   echo "<option value='$isi->id_sales_program'>$isi->id_program_md</option>";

                        //}

                        ?>                      

                      </select> 

                    </div> 

                    </span>   

                  </div>                        

                  <div class="form-group">                  

                    <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>

                    <div class="col-sm-4">                    

                      <input type="text" class="form-control" placeholder="No HP" maxlength="15" name="no_hp_penjamin">

                    </div>

                    <label for="inputEmail3" class="col-sm-2 control-label" id="nilai_voucher2_lbl">Nilai Voucher</label>

                    <div class="col-sm-4">

                      <input type="text" class="form-control" readonly placeholder="Nilai Voucher" onchange="get_total_ck()" name="nilai_voucher2" id="nilai_voucher2"> <input type="hidden" class="form-control" name="voucher_2" id="voucher_2">

                    </div>                    

                  </div>                  

                  <div class="form-group">                  

                    <label for="inputEmail3" class="col-sm-2 control-label">Tempat/Tgl Lahir</label>

                    <div class="col-sm-2">                    

                      <input type="text" class="form-control" placeholder="Tempat Lahir" name="tempat_lahir_penjamin">

                    </div>                                      

                    <div class="col-sm-2">                    

                      <input type="text" id="tanggal4" class="form-control" name="tgl_lahir_penjamin" placeholder="Tgl Lahir Penjamin">

                    </div>

                    <label for="inputEmail3" class="col-sm-2 control-label" id="nilai_voucher2_lbl">Voucher Tambahan</label>

                    <div class="col-sm-4">

                      <input type="text" class="form-control" placeholder="Voucher Tambahan" onkeyup="get_total_ck()" name="voucher_tambahan_2" id="voucher_tambahan_2">

                    </div>   

                  </div>

                  <div class="form-group">                  

                    <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>

                    <div class="col-sm-4">                    

                      <select class="form-control" name="pekerjaan_penjamin">

                        <option value="">- choose -</option>

                        <?php 

                        foreach($dt_pekerjaan->result() as $val) {

                          echo "

                          <option value='$val->id_pekerjaan'>$val->pekerjaan</option>;

                          ";

                        }

                        ?>

                      </select>                                           

                    </div>

                    <label for="inputEmail3" class="col-sm-2 control-label">Down Payment Gross</label>

                    <div class="col-sm-4">                    

                      <input type="text" class="form-control" placeholder="Down Payment Gross" id="uang_muka" onkeyup="get_total_ck()" name="uang_muka">

                    </div>                                                                  

                  </div>    

                  <div class="form-group">                  

                    <label for="inputEmail3" class="col-sm-2 control-label">Penghasilan</label>

                    <div class="col-sm-4">                    

                        <input type="text" class="form-control" placeholder="Penghasilan Penjamin" name="penghasilan_penjamin">

                    </div>    



                    <label for="inputEmail3" class="col-sm-2 control-label">Down Payment Setor</label>

                    <div class="col-sm-4">

                      <input readonly id="dp_setor" type="text" class="form-control" placeholder="DP Setor" name="dp_setor">

                      <input readonly id="dp_stor" type="hidden" class="form-control" placeholder="DP Setor" name="dp_stor">

                    </div>    

                  </div>                  

                  <div class="form-group">                  

                    <label for="inputEmail3" class="col-sm-2 control-label">No KTP *</label>

                    <div class="col-sm-4">                    

                      <input type="text" class="form-control" placeholder="No KTP Penjamin" name="no_ktp_penjamin">

                    </div>   



                    <label for="inputEmail3" class="col-sm-2 control-label">Tenor</label>

                    <div class="col-sm-3">                    

                      <input type="text" class="form-control" placeholder="Tenor" name="tenor">

                    </div>                                  

                    <div class="col-sm-1">                    

                      Bulan

                    </div>             

                  </div>                  

                  <div class="form-group">                  

                    <label for="inputEmail3" class="col-sm-2 control-label">Foto KTP (Maks 100Kb)</label>

                    <div class="col-sm-4">                    

                      <input type="file" class="form-control" name="file_ktp_2">

                    </div>                                 

                    <label for="inputEmail3" class="col-sm-2 control-label">Angsuran</label>

                    <div class="col-sm-4">

                      <input type="text" class="form-control" placeholder="Angsuran" name="angsuran">

                    </div>                   

                  </div>

                  <div class="form-group">

                    <div id="div_jenis_barang_kredit" style="display: none">

                      <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">Jenis Barang</label>

                    <div class="col-sm-4">                    

                      <input type="text" class="form-control" id="jenis_barang_kredit" name="jenis_barang_kredit" readonly>

                    </div>

                    </div>

                  </div>                   

                </span>

                <br>
<button class="btn btn-block btn-primary btn-flat" disabled> DETAIL AKSESORIS </button><br>
<div class="form-group">
  <div class="col-md-12">
    <table class="table table-bordered">
      <thead>
        <th>Kode Aksesoris</th>
        <th>Nama Aksesoris</th>
      </thead>
      <tbody>
        <tr v-for="(ks, index) of ksu_">
          <td>{{ks.id_ksu}}</td>
          <td>{{ks.ksu}}</td>
        </tr>
      </tbody>
    </table>
  </div>             
</div>
<button class="btn btn-block btn-primary btn-flat" disabled> DATA KARTU KELUARGA </button><br>
<div class="form-group">
  <label for="inputEmail3" class="col-sm-2 control-label">No. KK</label>
  <div class="col-sm-4">
     <input type="text" id="no_kk" class="form-control" maxlength="15" onkeypress="return number_only(event)" placeholder="No KK" name="no_kk" required>
  </div>          
  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Kartu Keluarga</label>
  <div class="col-sm-4">
    <input type="text" class="form-control" placeholder="Alamat Kartu Keluarga" name="alamat_kk">
  </div>          
</div>
<div class="form-group">
  <label for="inputEmail3" class="col-sm-2 control-label">Anggota Kartu Keluarga</label>
  <div class="col-sm-4">
    <input type="text" class="form-control" placeholder="Anggota Kartu Keluarga" v-model="anggota.anggota">
  </div> 
  <div class="col-sm-1">
    <button type="button" @click.prevent="addAnggota" class="btn btn-primary btn-flat btn-sm"><i class="fa fa-plus"></i></button>
  </div>
</div>
<div class="form-group">
  <div class="col-sm-4">
    <table class="table">
      <tr><td></td><td><b>List Anggota</b></td><td></td></tr>
      <tr v-for="(agt, index) of anggota_">
        <td>{{index+1}}. </td><td><input type="text" name="anggota_kk[]" class="form-control" v-model="agt.anggota"></td>
        <td>
          <button type="button" @click.prevent="delAnggota(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
        </td>
      </tr>
    </table>
  </div>
</div>
<button class="btn btn-block btn-primary btn-flat" disabled> DOKUMEN PENDUKUNG </button><br>
<div class="form-group">
  <div class="col-md-12">
    <table class="table">
      <tr>
        <td>File</td>
        <td>Nama File</td>
        <td align="right"><button type="button" @click.prevent="addFile" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i></button></td>
      </tr>
      <tr v-for="(fl, index) of file_pendukung_">
        <td><input type="file" class="form-control" name="file_pendukung[]"> </td>
        <td><input type="text" class="form-control" name="nama_file[]" v-model="fl.nama_file"></td>
        <td align="right"> <button type="button" @click.prevent="delFile(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button></td>
      </tr>
    </table>
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

<script>
  $(document).ready(function() {
    form_.addFile();
  })
  var form_ = new Vue({
      el: '#form_',
      data: {
        ksu_ : <?= isset($ksu_)?json_encode($ksu_):'[]' ?>,
        id_tipe_kendaraan : '',
        anggota:{anggota:''},
        file_pendukung:{file:'',nama_file:''},
        anggota_ : <?= isset($anggota_)?json_encode($anggota_):'[]' ?>,
        file_pendukung_ : <?= isset($file_pendukung_)?json_encode($file_pendukung_):'[]' ?>,
      },
    methods: {
      clearAnggota: function () {
      this.anggota ={anggota:''};
      },
      addAnggota : function(){
        // if (this.anggota_.length > 0) {
        //   for (dl of this.dealers) {
        //     if (dl.id_dealer === this.dealer.id_dealer) {
        //         alert("Dealer Sudah Dipilih !");
        //         this.clearDealers();
        //         return;
        //     }
        //   }
        // }
        // if (this.dealer.id_dealer=='') 
        // {
        //   alert('Pilih Dealer !');
        //   return false;
        // }
        this.anggota_.push(this.anggota);
        this.clearAnggota();
      },
      delAnggota: function(index){
          this.anggota_.splice(index, 1);
      },
      addFile : function(){
        this.file_pendukung_.push(this.file_pendukung);
        console.log(this.file_pendukung_)
        this.clearFile();
      },
      clearFile:function () {
        this.file_pendukung={file:'',nama_file:''}
      },
      delFile: function(index){
          this.file_pendukung_.splice(index, 1);
      },
     
    }
  });
  
</script>

    <?php
    }elseif($set=="index"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
                         
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
        <table id="datatable_server" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Kode Indent</th>
              <th>ID SPK</th>
              <th>Nama Customer</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>No Mesin</th>
              <th>No Rangka</th>
              <th>Sales People</th>
              <th>Status Approval</th>
              <th width="10%">Action</th>
            </tr>
          </thead>          
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<script>
   $(document).ready(function(){  
      var dataTable = $('#datatable_server').DataTable({  
         "processing":true, 
         "serverSide":true, 
         "language": {                
              "infoFiltered": "",
              "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
          }, 
         "order":[],
         "lengthMenu": [[10, 25, 50,75,100], [10, 25, 50,75,100]],  
         "ajax":{  
              url:"<?php echo site_url('dealer/indent_fullfilment/fetch'); ?>",  
              type:"POST",
              dataSrc: "data",
              data: function (d) {
                  return d;
              },
         },  
         "columnDefs":[  
              // { "targets":[2],"orderable":false},
              { "targets":[6],"className":'text-center'}, 
              // // { "targets":[0],"checkboxes":{'selectRow':true}}
              // { "targets":[6,7],"className":'text-right'}, 
              // // { "targets":[2,4,5], "searchable": false } 
         ],
      });
    });
</script> 
    <?php
    }
    ?>
  </section>
</div>


<script type="text/javascript">

var jenis_barang_global='';

function samakan(){

  document.getElementById("alamat_penjamin").value = $("#alamat").val();                                                    ;   

}

function cek_program(){

  var program_umum = $("#program_umum").val();                       

  if(program_umum != ""){

    $("#nilai_voucher").show();    

    $("#nilai_voucher_lbl").show();

  }else{

    $("#nilai_voucher").hide();

    $("#nilai_voucher").val("");

    $("#nilai_voucher_lbl").hide();

  }

}





function showJenisBarangCash(barang){

  $('#div_jenis_barang_cash').show();

  $('#lbl_total_bayar_cash').removeClass('col-md-offset-6');

  $('#jenis_barang_cash').val(barang);

}

function hideJenisBarangCash(){

  $('#div_jenis_barang_cash').hide();

  $('#lbl_total_bayar_cash').addClass('col-md-offset-6');

  $('#jenis_barang_cash').val('');

}



function showJenisBarangKredit(barang){

  $('#div_jenis_barang_kredit').show();

  $('#lbl_total_bayar_kredit').removeClass('col-md-offset-6');

  $('#jenis_barang_kredit').val(barang);

}

function hideJenisBarangKredit(){

  $('#div_jenis_barang_kredit').hide();

  $('#lbl_total_bayar_kredit').addClass('col-md-offset-6');

  $('#jenis_barang_kredit').val('');

}



function clearProgramUmum(){

  // alert('sudah');

  $('#lbl_cash #program_umum').val('');

  $('#lbl_kredit #program_umum').val('');

  $('#lbl_cash #voucher_1').val('');

  $('#lbl_cash #nilai_voucher').val('');

}





function clearProgramGabungan(){

  // alert('sudah');

  $('#lbl_cash #program_gabungan').val('');

  $('#lbl_kredit #program_gabungan').val('');

  $('#lbl_kredit #program_gabungan_kredit').val('');

  $('#lbl_cash #voucher_tambahan_1').val('');

}





function cek_program_tambahan()

{

  var jenis_beli=$("#beli").val();

  if (jenis_beli=='Cash') {

    var id_program_md=$('#lbl_cash #program_umum').val();

  }else if (jenis_beli=='Kredit') {

    var id_program_md=$('#lbl_kredit #program_umum').val();

  }



  if (id_program_md !='') {

     var value={id_program_md:id_program_md,

             id_warna:$("#id_warna").val(),

             id_tipe_kendaraan:$("#id_tipe_kendaraan").val(),

             jenis_beli:$("#beli").val()

            }

  $.ajax({

       //beforeSend: function() { $('#loading-status').show(); },

       url:"<?php echo site_url('dealer/spk/getProgramTambahan')?>",

       type:"POST",

       data:value,

       cache:false,

       success:function(html){

          data=html.split("##");                    

          $('#loading-status').hide();

          if (data.length > 2) {

            if (jenis_beli=='Cash') {

              if (data[3]!='') {

                showJenisBarangCash(data[3]);

                jenis_barang_global = data[3];

              }else{

                hideJenisBarangCash();

                jenis_barang_global = data[3];

              }

              if (data[2] >0) {

                $("#program_gabungan_lbl").show();

                $('#program_gabungan').html(data[1]);

              }else if (data[2] ==0) {

                $("#program_gabungan_lbl").hide();

              }



            if (data[0] !='' || data[0] >0) {

              $("#nilai_voucher").show();    

              $('#nilai_voucher').val(convertToRupiah(data[0]));

              $('#voucher_1').val(data[0]);

              $("#nilai_voucher_lbl").show();

              }

            else if(data[0] =='' || data[0] ==0){

                $("#nilai_voucher").hide();

                $("#nilai_voucher").val("");

                $("#nilai_voucher_lbl").hide();

              }

          }

          else if (jenis_beli=='Kredit') {

            if (data[3]!='') {

              showJenisBarangKredit(data[3]);

              jenis_barang_global = data[3];

            }else{

              hideJenisBarangKredit();

              jenis_barang_global = data[3];

            }

            if (data[2] >0) {

              $('#program_gabungan_kredit').html(data[1]);

              $('#program_gabungan_kredit').show();

              $('#program_gabungan_kredit_lbl').show();

              }else if (data[2] ==0) {

              $('#program_gabungan_kredit').hide();

                $("#program_gabungan_kredit_lbl").hide();

              }



            if (data[0] !='' || data[0] >0) {

              $('#nilai_voucher2').val(convertToRupiah(data[0]));

              $('#voucher_2').val(data[0]);

              $("#nilai_voucher2").show();

              $("#nilai_voucher2_lbl").show();



              }

            else if(data[0] =='' || data[0] ==0){

                $("#nilai_voucher2").hide();

                $("#nilai_voucher2").val("");

                $("#nilai_voucher2_lbl").hide();

              }

          }

          get_total_ck();

        }else{

          $('#loading-status').hide();

          clearProgramUmum();

          alert(data[0]);

        }

       },

       statusCode: {

    500: function() {

      $('#loading-status').hide();

      alert("Something Wen't Wrong");

    }

  }

  });

}else{

  if (jenis_beli=='Cash') {

    $("#nilai_voucher").hide();

    $("#nilai_voucher").val("");

    $("#nilai_voucher_lbl").hide();

    $("#program_gabungan_lbl").hide();

  }else if(jenis_beli=='Kredit')

  {

    $("#nilai_voucher2").hide();

    $("#nilai_voucher2").val("");

    $("#nilai_voucher2_lbl").hide();

    $("#program_gabungan_kredit_lbl ").hide();

  }

  //alert('Silahkan Pilih Program');

  get_total_ck();



}

}



function getVoucherGabungan()

{

  var jenis_beli=$("#beli").val();

  if (jenis_beli=='Cash') {

    var id_program_md=$('#lbl_cash #program_umum').val();

    var program_gabungan=$('#lbl_cash #program_gabungan').val();

    var jenis_barang=$('#jenis_barang_cash').val();

  }else if (jenis_beli=='Kredit') {

    var id_program_md=$('#lbl_kredit #program_umum').val();

    var program_gabungan=$('#lbl_kredit #program_gabungan_kredit').val();

    var jenis_barang=$('#jenis_barang_kredit').val();

  }



  var value={id_program_md:id_program_md,

             id_program_gabungan:program_gabungan,

             id_warna:$("#id_warna").val(),

             id_tipe_kendaraan:$("#id_tipe_kendaraan").val(),

             jenis_beli:$("#beli").val()

            }

  $.ajax({

       beforeSend: function() { $('#loading-status').show(); },

       url:"<?php echo site_url('dealer/spk/getVoucherGabungan')?>",

       type:"POST",

       data:value,

       cache:false,

       success:function(dt_response){

          response=dt_response.split("##");

          data = response[0];

           if (response.length>1) {

            if (jenis_beli=='Cash') {

             if (data !='' || data >0) {

              $("#nilai_voucher").show();    

              $('#nilai_voucher').val(convertToRupiah(data));

              $('#voucher_1').val(data);



              $("#nilai_voucher_lbl").show();

              }

            else if(data =='' || data ==0){

                $("#nilai_voucher").hide();

                $("#nilai_voucher").val("");

                $("#nilai_voucher_lbl").hide();

              }

            if (id_program_md!='') {

              if (jenis_barang_global=='') {

                if (response[1]!='') {

                showJenisBarangCash(response[1]);

              }else{

                hideJenisBarangCash();

              }

              }

            }

           }

          else if (jenis_beli=='Kredit') {

             if (data !='' || data >0) {

              $("#nilai_voucher2").show();    

              $('#nilai_voucher2').val(convertToRupiah(data));

              $('#voucher_2').val(data);

              $("#nilai_voucher2_lbl").show();

              }

            else if(data =='' || data ==0){

                $("#nilai_voucher2").hide();

                $("#nilai_voucher2").val("");

                $("#nilai_voucher_lbl").hide();

              }

            if (id_program_md!='') {

              if (jenis_barang_global=='') {

                if (response[1]!='') {

                showJenisBarangKredit(response[1]);

              }else{

                hideJenisBarangKredit();

              }

              }

            }

          }

           }else{

            clearProgramGabungan();

            alert(data);

           }

          $('#loading-status').hide();

          get_total_ck();

       },

       statusCode: {

    500: function() {

      $('#loading-status').hide();

      alert("Something Wen't Wrong");

    }

  }

  });

}



function hideProgram(){

  $('#program_umum').val('');

  $('#nilai_voucher').val('');

  $('#div_program_umum').hide();

  $('#div_program_gabungan').hide();

  $('#program_gabungan').val('');

  $('#voucher_tambahan_1').val('');

}



function showProgram(){

  $('#div_program_umum').show();

  $('#div_program_gabungan').show();

}



function cek_voucher(){

  var voucher = $("#voucher").val();                       

  if(voucher != ""){

    $("#nilai_voucher2").show();

    $("#nilai_voucher2_lbl").show();

  }else{

    $("#nilai_voucher2").hide();

    $("#nilai_voucher2").val("");

    $("#nilai_voucher2_lbl").hide();

  }

}

function cari(){

  $("#myTable1").show();

  var no_rangka   = $("#no_rangka_cari").val();                       

  var no_ktp      = $("#no_ktp").val();                       

  if (no_ktp == "") {    

      alert("Isikan No KTP dahulu...!");      

      return false;

  }else{

    $.ajax({

        url: "<?php echo site_url('dealer/spk/take_ref')?>",

        type:"POST",

        data:"no_rangka="+no_rangka+"&no_ktp="+no_ktp,            

        cache:false,

        success:function(msg){                

            data=msg.split("|");                    

            $("#no_rangka_lbl").val(data[0]);                                                    

            $("#refferal_id_lbl").val(data[1]);                                                    

            $("#nama_lbl").val(data[2]);                                                    

            $("#tgl_lahir_lbl").val(data[3]);                                                    

            $("#no_ktp_lbl").val(data[4]);                                                              

        } 

    })

  }

}

function pilih_refferal(){

  document.getElementById("refferal_id").value = $("#refferal_id_lbl").val();

  document.getElementById("nama_refferal_id").value = $("#nama_lbl").val();  

  $("#Reffmodal").modal("hide");

}

function pilih_robd(){

  document.getElementById("robd_id").value = $("#robd_id_lbl").val();        

  document.getElementById("nama_robd_id").value = $("#nama_lbl2").val();     

  $("#Robdmodal").modal("hide");

}

function cari2(){

  $("#myTable2").show();

  var no_rangka   = $("#no_rangka_cari2").val();                       

  var no_ktp      = $("#no_ktp").val();                       

  if (no_ktp == "") {    

      alert("Isikan No KTP dahulu...!");      

      return false;

  }else{

    $.ajax({

        url: "<?php echo site_url('dealer/spk/take_robd')?>",

        type:"POST",

        data:"no_rangka="+no_rangka+"&no_ktp="+no_ktp,            

        cache:false,

        success:function(msg){                

            data=msg.split("|");                    

            $("#no_rangka_lbl2").val(data[0]);                                                    

            $("#robd_id_lbl").val(data[1]);                                                    

            $("#nama_lbl2").val(data[2]);                                                    

            $("#tgl_lahir_lbl2").val(data[3]);                                                    

            $("#no_ktp_lbl2").val(data[4]);                                                              

        } 

    })

  }

}

function hide(){

  $("#lbl_kredit").hide();

  $("#lbl_cash").hide();

  $("#myTable1").hide();

  $("#myTable2").hide();

  $("#nilai_voucher").hide();

  $("#nilai_voucher_lbl").hide();

  $("#nilai_voucher2").hide();

  $("#nilai_voucher2_lbl").hide();

}

function get_beli(){

  jenis_barang_global='';

  var isi = $("#beli").val();

  if(isi == 'Cash'){

    $("#lbl_cash").show();

    $("#lbl_kredit").hide();

    $("#program_gabungan_lbl").hide();

    $("#nilai_voucher").val('');   

    tampil_cash();

  }else if(isi == 'Kredit'){

    $("#lbl_cash").hide();

    $("#lbl_kredit").show();

    $("#program_gabungan_kredit").val('');

    $("#program_gabungan_kredit").hide();

    $("#program_gabungan_kredit_lbl").hide();

    tampil_kredit();

    // $("#nilai_voucher2").val('');

  }

  var value={id_tipe_kendaraan:$("#id_tipe_kendaraan").val(),

             id_warna:$("#id_warna").val(),

             jenis_beli:$("#beli").val(),

             <?php if (isset($row->program_umum)) {?>

              program_umum :'<?= $row->program_umum ?>'

             <?php } ?>

            }

  $.ajax({

       beforeSend: function() { $('#loading-status').show(); },

       url:"<?php echo site_url('dealer/spk/getProgram')?>",

       type:"POST",

       data:value,

       cache:false,

       success:function(html){

          $('#loading-status').hide();

          if (isi=='Cash') {

            $('#lbl_cash #program_umum').html(html);

            hideJenisBarangCash();

          }

          else if (isi=='Kredit') {

            $('#lbl_kredit #program_umum').html(html);

            hideJenisBarangKredit();

          }

          get_total_ck();

          <?php if ($set=='edit') {?> cek_program_tambahan()<?php } ?>

       },

       statusCode: {

    500: function() {

      $('#loading-status').hide();

      alert("Something Wen't Wrong");

    }

  }

  });

}

function auto(){  

  hide();  

  $("#warna_mode").val("");    

  $("#warna_mode2").val("");    

  var tgl_js = "1";

  $.ajax({

      url : "<?php echo site_url('dealer/spk/cari_id')?>",

      type:"POST",

      data:"tgl="+tgl_js,   

      cache:false,   

      success: function(msg){ 

        data=msg.split("|");

        $("#id_spk").val(data[0]);                

        //$("#id_customer").val(data[1]);           

        $("#tampil_alamat").hide();        

      }        

  })

}

function cek_tanya2(){

var tanya = $("#tanya").val();  

  if(tanya == 'Tidak'){

    $("#tampil_alamat").show();

  }else{

    $("#tampil_alamat").hide();

  }

}

function cek_tanya(){

  var tanya = $("#tanya").val();  

  if(tanya == 'Tidak'){

    $("#tampil_alamat").show();

    $("#id_kecamatan2").val("");    

    $("#id_kabupaten2").val("");

    $("#id_kelurahan2").val("");

    $("#id_provinsi2").val("");

    $("#kodepos2").val("");

    $("#alamat2").val("");

  }else{

    $("#tampil_alamat").hide();

    document.getElementById("id_kecamatan2").value = $("#id_kecamatan").val();    

    document.getElementById("id_kabupaten2").value = $("#id_kabupaten").val();

    document.getElementById("id_kelurahan2").value = $("#id_kelurahan").val();

    document.getElementById("id_provinsi2").value  = $("#id_provinsi").val();    

    // document.getElementById("kodepos2").value  = $("#kodepos").val();    

    // document.getElementById("alamat2").value  = $("#alamat").val();    

  }

}

function Chooseitem(id_customer){
  document.getElementById("id_customer").value = id_customer; 
  cek_customer();
  $("#Customermodal").modal("hide");
}

function getAksesoris() {
  var id_tipe_kendaraan = $('#id_tipe_kendaraan').val();
  values = {id_tipe_kendaraan:id_tipe_kendaraan}
  console.log(values)
  $.ajax({
    beforeSend: function() {},
    url:'<?= base_url('dealer/spk/getAksesoris') ?>',
    type:"POST",
    data: values,
    cache:false,
    dataType:'JSON',
    success:function(response){
      form_.ksu_ =[];
      $.each(response, function(i, ksu) {
        form_.ksu_.push(ksu)
      });
    },
    error:function(){
      // alert("failure");
    },
    statusCode: {
      500: function() { 
        // alert('fail');
      }
    }
  });
}

function cek_customer(){

  var id_customer=$("#id_customer").val();                       

  $.ajax({

      url: "<?php echo site_url('dealer/spk/cek_customer')?>",

      type:"POST",

      data:"id_customer="+id_customer,            

      cache:false,

      success:function(msg){                

          data=msg.split("|");
          console.log(data);
          if(data[0]=="ok"){          

            $("#nama_konsumen").val(data[1]);                

            $("#id_kelurahan").val(data[2]);

            //$("#id_kelurahan").select2().val(data[2]).trigger('change.select2');

            $("#alamat").val(data[3]);                            

            $("#tanggal2").val(data[4]);                            

            $("#jenis_pembelian").val(data[5]);                            

            $("#jenis_wn").val(data[6]);                            

            $("#no_ktp").val(data[7]);                            

            $("#no_kk").val(data[8]);                            

            $("#no_hp").val(data[9]);                            

            $("#email").val(data[10]);                            

            $("#pekerjaan").val(data[11]);                            

            $("#id_tipe_kendaraan").val(data[12]);  

            $("#tempat_lahir").val(data[14]);                            

            $("#no_ktp").val(data[15]);                            

            $("#no_npwp").val(data[16]);                                                    

            $("#pendidikan").val(data[17]);                                                    

            $("#jenis_kelamin").val(data[18]);                                                    

            $("#kodepos").val(data[19]);                                                    

            $("#status_nohp").val(data[20]);                                                    

            $("#sedia_hub").val(data[21]);                                                    

            $("#merk_sebelumnya").val(data[22]);                                                    

            $("#jenis_sebelumnya").val(data[23]);                                                    

            $("#digunakan").val(data[24]);                                                    

            $("#pemakai_motor").val(data[25]);                                                    

            $("#agama").val(data[26]);                                                    

            $("#no_telp").val(data[27]);   
            $("#sales_people").val(data[30]);                                                                
            $("#flp_id").val(data[29]);                                                                
            $("#diskon").val(data[31]);                                                                     
            $("#longitude").val(data[32]);                                                                     
            $("#latitude").val(data[33]);                                                                     
            $("#beli").val(data[34]);     
            get_beli();                                                                

            ambil_slot();

            cek_tanya();

            take_kec();        

            getWarna(data[13]);

            // $("#id_warna").select2().val(data[13]).trigger('change.select2');

            // $("#id_warna").val(data[13]);  

            take_harga();

            // alert(data[13]);

          }else{

            alert(data[0]);

          }

      } 

  })

}

function Choosenpwp(id_prospek_gc){

  document.getElementById("id_prospek_gc").value = id_prospek_gc; 

  cek_prospek();

  $("#Npwpmodal").modal("hide");  

  $("select span option").unwrap(); //unwrap only wrapped



}


function cek_prospek(){

  var id_prospek_gc = $("#id_prospek_gc").val();                       

  $.ajax({

      url: "<?php echo site_url('dealer/spk/cek_prospek')?>",

      type:"POST",

      data:"id_prospek_gc="+id_prospek_gc,            

      cache:false,

      success:function(msg){                

          data=msg.split("|");

          if(data[0]=="ok"){          

            $("#nama_npwp").val(data[1]);                          

            $("#no_npwp").val(data[2]);                            

            $("#alamat").val(data[3]);                            

            $("#id_kelurahan").val(data[4]);

            $("#jenis_gc").val(data[5]);  

            if(data[5] == 'Instansi' || data[5] == 'Join Promo' || data[5] == 'Joint Promo'){

              tambah_option1();

            }else{

              tambah_option2();

            }

            $("#no_telp").val(data[6]);                            

            $("#tanggal4").val(data[7]);                            

            $("#nama_penanggung_jawab").val(data[8]);                            

            $("#email").val(data[9]);                            

            $("#no_hp").val(data[10]);                            

            $("#status_hp").val(data[11]);                                        

            $("#kodepos").val(data[12]);                                                    

            $("#id_prospek_gc").val(data[13]);                                                    

            take_kec();                     

            take_status(); 

            

            tampil_detail(id_prospek_gc);                    

            tampil_cash();

            tampil_kredit();

          }else{

            alert(data[0]);

          }

      } 

  })

}

function tambah_option1(){

  var myOptions = {

    '' : '- choose -',

    Cash : 'Cash'

  };

  var mySelect = $('#beli');

  $('#beli').html("");

  $.each(myOptions, function(val, text) {

      mySelect.append(

          $('<option></option>').val(val).html(text)

      );

  });



}



function tambah_option2(){

  var myOptions = {

    '' : '- choose -',

    Cash : 'Cash',

    Kredit : 'Kredit'

  };

  var mySelect = $('#beli');  

  $('#beli').html("");

  $.each(myOptions, function(val, text) {

      mySelect.append(

          $('<option></option>').val(val).html(text)

      );

  });



}

function take_harga(){

  var tipe_customer=$("#tipe_customer").val();                       

  var id_tipe_kendaraan=$("#id_tipe_kendaraan").val();                       

  var id_warna=$("#id_warna").val();             

  //alert(id_warna+id_tipe_kendaraan);
  getAksesoris();
  $.ajax({

      url: "<?php echo site_url('dealer/spk/cek_bbn')?>",

      type:"POST",

      data:"id_warna="+id_warna+"&id_tipe_kendaraan="+id_tipe_kendaraan+"&tipe_customer="+tipe_customer,            

      cache:false,

      success:function(msg){                

          data=msg.split("|");          

          $("#biaya_bbn").val(data[0]);                                        

          $("#harga_on").val(data[1]);                

          $("#harga_off").val(data[2]);                

          $("#ppn").val(data[3]);                

          $("#harga").val(data[4]);                

          $("#harga_tunai").val(data[5]);                

          $("#biaya_bbn_r").val(convertToRupiah(data[0]));                                        

          $("#harga_off_r").val(convertToRupiah(data[2]));                

          $("#harga_on_r").val(convertToRupiah(data[1]));                

          $("#ppn_r").val(convertToRupiah(data[3]));                

          $("#harga_r").val(convertToRupiah(data[4]));                

          $("#harga_tunai_r").val(convertToRupiah(data[5]));                

          get_total();

      }

  })

}

function get_total(){

  var biaya_bbn = $("#biaya_bbn").val();                       

  var harga_tunai = $("#harga_tunai").val();                       

  var program_umum = $("#program_umum").val();                       

  var voucher_tambahan = $("#voucher_tambahan_2").val();                       

  var total = parseInt(harga_tunai) - parseInt(voucher_tambahan);

  var ubah_total = convertToRupiah(total);

  $("#total_bayar_r").val(ubah_total);

  $("#total_bayar").val(total);

}



function get_total_ck(){

  var biaya_bbn = $("#biaya_bbn").val();                       

  var harga_tunai = $("#harga_tunai").val()==''?0:$("#harga_tunai").val();          

  var jenis_beli = $("#beli").val(); 

  

  if (jenis_beli=='Cash') {

    var voucher_tambahan = $("#voucher_tambahan_1").val()==''?0:$("#voucher_tambahan_1").val();  

    var n_v = $("#lbl_cash #nilai_voucher").val();                          

    var nilai_voucher = n_v.replace(/\D/g, "");  

    nilai_voucher = nilai_voucher>0?nilai_voucher:0;     

      var total = parseInt(harga_tunai) - (parseInt(nilai_voucher) + parseInt(voucher_tambahan));

  var ubah_total = convertToRupiah(total);

  $("#total_bayar_r").val(ubah_total);

  $("#total_bayar").val(total);   

  }else if (jenis_beli=='Kredit') {

    $("#nilai_voucher2").show();                          

    $("#nilai_voucher2_lbl").show();                          

    var n_v = $("#nilai_voucher2").val();                          

    

    var nilai_voucher = n_v.replace(/\D/g, "");

    nilai_voucher = nilai_voucher>0?nilai_voucher:0; 



    var voucher_tambahan = $("#voucher_tambahan_2").val()==''?0:$("#voucher_tambahan_2").val();  

    var uang_muka = $("#uang_muka").val()==''?0:$("#uang_muka").val();      

    var dp_setor = parseInt(uang_muka) - parseInt(nilai_voucher) -  parseInt(voucher_tambahan);

    dp_setor = dp_setor<0?0:dp_setor;

    var ubah_dp_setor = convertToRupiah(dp_setor);

    //alert(nilai_voucher+'/'+voucher_tambahan+'/'+uang_muka+'/'+dp_setor);

    $("#dp_setor").val(ubah_dp_setor);

    $("#dp_stor").val(dp_setor);



  }



}



function get_on(){

  var the_road = $("#the_road").val();

  var biaya_bbn = $("#biaya_bbn").val();                       

  var harga_tunai = $("#harga_tunai").val();                       

  var harga_off = $("#harga_off").val();                       

  var harga_on = $("#harga_on").val();                       

  var program_umum = $("#program_umum").val();                       

  var program_khusus = $("#program_khusus").val();                       

  $("#total_bayar").val(total);

  if(the_road == 'On The Road'){

    var total = parseInt(harga_on);

    showProgram();

  }else{    

    var total = parseInt(harga_off);

    hideProgram();

   // var total = parseInt(harga_tunai)- parseInt(biaya_bbn);

  }

  $("#harga_tunai_r").val(convertToRupiah(total));

  $("#harga_tunai").val(total);

  get_total_ck();

}

function chooseitem2(id_kelurahan){

  document.getElementById("id_kelurahan2").value = id_kelurahan; 

  take_kec2();

  $("#Kelurahanmodal2").modal("hide");

}

function chooseitem(id_kelurahan){
  document.getElementById("id_kelurahan").value = id_kelurahan; 
  take_kec();
  $("#Kelurahanmodal").modal("hide");

}

function take_kec(){

  var id_kelurahan = $("#id_kelurahan").val();                       

  $.ajax({

      url: "<?php echo site_url('dealer/spk/take_kec')?>",

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

function take_kec2(){

  var id_kelurahan = $("#id_kelurahan2").val();                       

  $.ajax({

      url: "<?php echo site_url('dealer/spk/take_kec')?>",

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



function takes(){

  hide();

  take_kec();

  take_kec2();

  get_beli();  

  get_total_ck();  

  $("#tampil_alamat").hide();  

}



function cek_umur(){

     var today = new Date();

      var birthDate = new Date($('.tgl_lahir').val());

      var age = today.getFullYear() - birthDate.getFullYear();

      var m = today.getMonth() - birthDate.getMonth();

      if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {

          age--;

      }

     if (age < 17) {

      alert('Usia Kurang Dari 17 Tahun')

      $('.tgl_lahir').val('');

     }

  }

function getWarna(id_warna=null)

  { 

      //var nama_type = $(".modal_edit_detailkendaraan #kode_type").select2().find(":selected").data("nama_type");

      var id_tipe_kendaraan = $("#id_tipe_kendaraan").val();

      $.ajax({

               beforeSend: function() { $('#loading-status').show(); },

               url:"<?php echo site_url('dealer/spk/getWarna');?>",

               type:"POST",

               data:"id_tipe_kendaraan="+id_tipe_kendaraan

                  +"&id_warna="+id_warna,

                  /* +"&keterangan="+keterangan

                  +"&tgl_pinjaman="+tgl_pinjaman,

               */

               cache:false,

               success:function(html){

                  $('#loading-status').hide();

                  $('#id_warna').html(html);
                  $('#id_warna').val(id_warna).trigger('change');

                  $("#warna_mode").val("ada");

                  $("#warna_mode2").val("ada");

                  take_harga();

               },

               statusCode: {

            500: function() {

              $('#loading-status').hide();

              swal("Something Wen't Wrong");

            }

          }

          });

  }

function getWarna2(){

  var mode = $("#warna_mode").val();  

  if(mode == ''){

    getWarna();

  }else{

    //getWarna();    

    return false;

  }

}

function ambil_slot(){

  var id_customer  = $("#id_customer").val();   

  var id_tipe_kendaraan  = $("#id_tipe_kendaraan").val();   

  $.ajax({

    url : "<?php echo site_url('dealer/spk/warna_slot')?>",

    type:"POST",

    data:"id_customer="+id_customer+"&id_tipe_kendaraan="+id_tipe_kendaraan,

    cache:false,   

    success:function(msg){            

      $("#id_warna").html(msg);    

      take_harga();   

    }

  })  

}

function take_status(){

  var id_prospek_gc  = $("#id_prospek_gc").val();     

  $.ajax({

    url : "<?php echo site_url('dealer/spk/cek_statushp')?>",

    type:"POST",

    data:"id_prospek_gc="+id_prospek_gc,

    cache:false,   

    success:function(msg){            

      $("#status_nohp").html(msg);          

    }

  })  

}

function tampil_detail(a)

{

  var value={id:a}

  $.ajax({

       beforeSend: function() { $('#loading-status').show(); },

       url:"<?php echo site_url('dealer/spk/getDetail')?>",

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

function tampil_detail2()

{

  var value={id:$("#no_spk_gc").val()}

  $.ajax({

       beforeSend: function() { $('#loading-status').show(); },

       url:"<?php echo site_url('dealer/spk/getDetail2')?>",

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

function tampil_cash()

{

  var value={id:$("#id_prospek_gc").val()}

  $.ajax({

       beforeSend: function() { $('#loading-status').show(); },

       url:"<?php echo site_url('dealer/spk/getDetail_cash')?>",

       type:"POST",

       data:value,

       cache:false,

       success:function(html){

          $('#loading-status').hide();          

          $('#showDetail_cash').html(html);

       },

       statusCode: {

    500: function() {

      $('#loading-status').hide();

      alert("Something Wen't Wrong");

    }

  }

  });

}

function tampil_kredit()

{

  var value={id:$("#id_prospek_gc").val()}

  $.ajax({

       beforeSend: function() { $('#loading-status').show(); },

       url:"<?php echo site_url('dealer/spk/getDetail_kredit')?>",

       type:"POST",

       data:value,

       cache:false,

       success:function(html){

          $('#loading-status').hide();                    

          $('#showDetail_kredit').html(html);

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

            "url": "<?php echo site_url('dealer/spk/ajax_list')?>",

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



<script type="text/javascript">



var table;



$(document).ready(function() {

    //datatables

    table = $('#table2').DataTable({



        "processing": true, //Feature control the processing indicator.

        "serverSide": true, //Feature control DataTables' server-side processing mode.

        "order": [], //Initial no order.



        // Load data for the table's content from an Ajax source

        "ajax": {

            "url": "<?php echo site_url('dealer/spk/ajax_list_2')?>",

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

<script type="text/javascript">

function addDetail(){

  var id_tipe_kendaraan   = $("#id_tipe_kendaraan_gc").val();                       

  var id_warna      = $("#id_warna_gc").val();                         

  var qty      = $("#qty_gc").val();                         

  var id_prospek_gc      = $("#id_prospek_gc").val();                         

  $.ajax({

      url: "<?php echo site_url('dealer/prospek/addDetail')?>",

      type:"POST",

      data:"id_tipe_kendaraan="+id_tipe_kendaraan+"&id_warna="+id_warna+"&qty="+qty+"&id_prospek_gc="+id_prospek_gc,            

      cache:false,

      success:function(data){                

        if(data=='nihil'){

          tampil_detail(id_prospek_gc);            

        }else{

          alert(data);

        }        

      } 

  })  

}

function delDetail(id){

  var id_prospek_gc      = $("#id_prospek_gc").val();                         

  $.ajax({

      url: "<?php echo site_url('dealer/prospek/delDetail')?>",

      type:"POST",

      data:"id="+id,

      cache:false,

      success:function(data){                

        if(data=='nihil'){

          tampil_detail(id_prospek_gc);            

        }else{

          alert(data);

        }        

      } 

  })  

}

function edit_popup(id_gc)

  {

        $.ajax({

             url:"<?php echo site_url('dealer/prospek/edit_popup');?>",

             type:"POST",

             data:"id_gc="+id_gc,

             cache:false,

             success:function(html){

                $("#show_detail").html(html);

                getWarna_gc_edit();

             }

        });

  }

function saveEdit(id){

  var id_tipe_kendaraan   = $("#id_tipe_kendaraan_edit").val();                       

  var id_warna      = $("#id_warna_edit").val();                         

  var qty      = $("#qty_edit").val(); 

  var id_prospek_gc      = $("#id_prospek_gc").val();                                                   

  $.ajax({

      url: "<?php echo site_url('dealer/prospek/saveEdit')?>",

      type:"POST",

      data:"id_tipe_kendaraan="+id_tipe_kendaraan+"&id_warna="+id_warna+"&qty="+qty+"&id_gc="+id,            

      cache:false,

      success:function(data){                

        if(data=='nihil'){

          tampil_detail(id_prospek_gc);            

          $("#modaall").modal("hide");

        }else{

          alert(data);

        }        

      } 

  })  

}

</script>

<script type="text/javascript">

  function getWarna_gc_edit()

  {     

      var id_tipe_kendaraan = $("#id_tipe_kendaraan_edit").val();

      var id_warna = $("#id_warna_edit2").val();      

      $.ajax({

               beforeSend: function() { $('#loading-status').show(); },

               url:"<?php echo site_url('dealer/prospek/getWarnaEdit');?>",

               type:"POST",

               data:"id_tipe_kendaraan="+id_tipe_kendaraan+"&id_warna="+id_warna,

               /*   +"&ksu="+ksu

                  +"&keterangan="+keterangan

                  +"&tgl_pinjaman="+tgl_pinjaman,

               */

               cache:false,

               success:function(html){

                  $('#loading-status').hide();

                  $('#id_warna_edit').html(html);                  

               },

               statusCode: {

            500: function() {

              $('#loading-status').hide();

              swal("Something Wen't Wrong");

            }

          }

          });

  }

</script>

<script type="text/javascript">

  function getWarna_gc()

  { 

      //var nama_type = $(".modal_edit_detailkendaraan #kode_type").select2().find(":selected").data("nama_type");

      var id_tipe_kendaraan = $("#id_tipe_kendaraan_gc").val();

      $.ajax({

               beforeSend: function() { $('#loading-status').show(); },

               url:"<?php echo site_url('dealer/prospek/getWarna');?>",

               type:"POST",

               data:"id_tipe_kendaraan="+id_tipe_kendaraan,

               /*   +"&ksu="+ksu

                  +"&keterangan="+keterangan

                  +"&tgl_pinjaman="+tgl_pinjaman,

               */

               cache:false,

               success:function(html){

                  $('#loading-status').hide();

                  $('#id_warna_gc').html(html);

                  $("#warna_mode").val("ada");

               },

               statusCode: {

            500: function() {

              $('#loading-status').hide();

              swal("Something Wen't Wrong");

            }

          }

          });

  }



  function getWarnaEdit_gc()

  { 

      //var nama_type = $(".modal_edit_detailkendaraan #kode_type").select2().find(":selected").data("nama_type");

      var id_tipe_kendaraan = $("#id_tipe_kendaraan_gc").val();

      var id_warna_old = $("#id_warna_old").val();

      $.ajax({

               beforeSend: function() { $('#loading-status').show(); },

               url:"<?php echo site_url('dealer/prospek/getWarnaEdit');?>",

               type:"POST",

               data:"id_tipe_kendaraan="+id_tipe_kendaraan

                  +"&id_warna_old="+id_warna_old,

               cache:false,

               success:function(html){

                  $('#loading-status').hide();

                  $('#id_warna').html(html);

                  $("#warna_mode").val("ada");

               },

               statusCode: {

            500: function() {

              $('#loading-status').hide();

              swal("Something Wen't Wrong");

            }

          }

          });

  }

function getWarna2_gc(){

  var mode = $("#warna_mode").val();

  if(mode == ''){

    getWarna();

  }else{

    return false;

  }

}

function cek_road_gc(){

  var on_road_gc = $("#on_road_gc").val();

  var jumlah_gc = $("#jumlah_gc").val();

  for(i=1;i<=jumlah_gc;i++){

    var biaya_bbn_gc_on = $("#biaya_bbn_gc_on_"+i).val();

    var biaya_bbn_gc_off = $("#biaya_bbn_gc_off_"+i).val();

    if(on_road_gc == 'Off The Road'){

      $("#biaya_bbn_gc_"+i).val(biaya_bbn_gc_off);

    }else{

      $("#biaya_bbn_gc_"+i).val(biaya_bbn_gc_on);

    }

  }

  kali_gc_cash();

}

function kali_gc_kredit(){  

  var jumlah_kredit = $("#jumlah_kredit").val();

  for(i=1;i<=jumlah_kredit;i++){

    var harga = $("#harga_jual_"+i).val();

    var biaya_bbn = $("#biaya_bbn_"+i).val();

    var nilai_voucher = $("#nilai_voucher_"+i).val();

    var voucher_tambahan = $("#voucher_tambahan_"+i).val();

    var qty = $("#qty_"+i).val();

    var dp_stor = $("#dp_stor_"+i).val();

    hasil = (Number(harga) + Number(biaya_bbn) - Number(nilai_voucher) - Number(voucher_tambahan) - Number(dp_stor))*Number(qty);    

    $("#total_"+i).val(hasil);                  

    // alert(harga);

    // alert(biaya_bbn);

    // alert(nilai_voucher);

    // alert(voucher_tambahan);

    // alert(qty);

    // alert(dp_setor);

  }

  cek_grand_kredit();

}

function cek_grand_kredit(){

  var jumlah_kredit = $("#jumlah_kredit").val();

  var ha = 0;

  for(i=1;i<=jumlah_kredit;i++){

    var total = $("#total_"+i).val();

    ha = Number(ha) + Number(total);

  }

  $("#g_total").val(ha);                  

}

function kali_gc_cash(){  

  var jumlah_gc = $("#jumlah_gc").val();

  for(i=1;i<=jumlah_gc;i++){

    var harga = $("#harga_jual_gc_"+i).val();

    var biaya_bbn = $("#biaya_bbn_gc_"+i).val();

    var nilai_voucher = $("#nilai_voucher_gc_"+i).val();

    var voucher_tambahan = $("#voucher_tambahan_gc_"+i).val();

    var qty = $("#qty_gc_"+i).val();

    hasil = (Number(harga) + Number(biaya_bbn) - Number(nilai_voucher) - Number(voucher_tambahan))*Number(qty);    

    $("#total_gc_"+i).val(hasil);                  

  }

  cek_grand_gc();

}

function cek_grand_gc(){

  var jumlah_gc = $("#jumlah_gc").val();

  var ha = 0;

  for(i=1;i<=jumlah_gc;i++){

    var total = $("#total_gc_"+i).val();

    ha = Number(ha) + Number(total);

  }

  $("#g_total_gc").val(ha);                  

}

function cek_program_gc(){

  var id_sales_program_gc   = $("#id_sales_program_gc").val();                         

  var beli   = $("#beli").val();                         

  if(beli=='Cash'){

    var total = "";

    var jumlah_gc = $("#jumlah_gc").val();

    var qty   = 4;                         

    for(i=1;i<=jumlah_gc;i++){

      var id_tipe_kendaraan = $("#id_tipe_kendaraan_gc_"+i).val();      

      var qty   = $("#qty_gc_"+i).val();

      total = total+"|"+id_tipe_kendaraan;

    }

    $.ajax({

      url: "<?php echo site_url('dealer/spk/cek_program_gc')?>",

      type:"POST",

      data:"id_tipe_kendaraan="+total+"&id_sales_program="+id_sales_program_gc+"&beli="+beli+"&qty="+qty,

      cache:false,

      success:function(msg){      

        data=msg.split("|");  

        for(i=1;i<=jumlah_gc;i++){                                    

          $("#nilai_voucher_gc_"+i).val(data[i+1]);              

        }

        //alert(data[1]);

      } 

    })    

  }else{

    var total = "";

    var jumlah_kredit = $("#jumlah_kredit").val();

    for(i=1;i<=jumlah_kredit;i++){

      var id_tipe_kendaraan = $("#id_tipe_kendaraan_"+i).val();

      var qty   = $("#qty_"+i).val();

      total = total+"|"+id_tipe_kendaraan;

    }

    $.ajax({

      url: "<?php echo site_url('dealer/spk/cek_program_gc')?>",

      type:"POST",

      data:"id_tipe_kendaraan="+total+"&id_sales_program="+id_sales_program_gc+"&beli="+beli+"&qty="+qty,

      cache:false,

      success:function(msg){      

        data=msg.split("|");  

        for(i=1;i<=jumlah_kredit;i++){                                    

          $("#nilai_voucher_"+i).val(data[i+1]);              

        }

        //alert(data[1]);

      } 

    })    

  }

  //alert(id_sales_program_gc);

}

</script>