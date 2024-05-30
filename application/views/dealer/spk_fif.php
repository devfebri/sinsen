<?php
function bln()
{
  $bulan = $bl = $month = date("m");
  switch ($bulan) {
    case "1":
      $bulan = "Januari";
      break;
    case "2":
      $bulan = "Februari";
      break;
    case "3":
      $bulan = "Maret";
      break;
    case "4":
      $bulan = "April";
      break;
    case "5":
      $bulan = "Mei";
      break;
    case "6":
      $bulan = "Juni";
      break;
    case "7":
      $bulan = "Juli";
      break;
    case "8":
      $bulan = "Agustus";
      break;
    case "9":
      $bulan = "September";
      break;
    case "10":
      $bulan = "Oktober";
      break;
    case "11":
      $bulan = "November";
      break;
    case "12":
      $bulan = "Desember";
      break;
  }
  $bln = $bulan;
  return $bln;
}
?>
<style type="text/css">
  .myTable1 {
    margin-bottom: 0px;
  }

  .myt {
    margin-top: 0px;
  }

  .isi {
    height: 25px;
    padding-left: 4px;
    padding-right: 4px;
  }
</style>
<base href="<?php echo base_url(); ?>" />
<?php if (isset($_GET['id'])) { ?>

  <body onload="takes();cek_tanya2();get_total_ck();">
  <?php } elseif (isset($_GET['id_c'])) { ?>

    <body onload="tampil_detail2();cek_tanya2();takes();">
    <?php } else { ?>

      <body onload="auto();get_beli();cek_tanya2();">
      <?php } ?>
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <?php echo $title; ?>
          </h1>
          <ol class="breadcrumb">
            <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
            <li class="">Customer</li>
            <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
          </ol>
        </section>
        <section class="content">

          <?php
          if ($set == "insert") {
            $disabled = '';
          ?>
            <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
            <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
            <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
            <script>
              Vue.use(VueNumeric.default);
              $(document).ready(function() {
                form_.addFile();
              })
            </script>
            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/spk_fif">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
                    <form id="form_" class="form-horizontal" action="dealer/spk_fif/save" method="post" enctype="multipart/form-data">
                      <input type="hidden" name="mode_edit" value="true">
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
                            <input type="text" readonly class="form-control" readonly placeholder="Tanggal" value="<?php echo date("Y-m-d") ?>" name="tgl_spk">
                          </div>
                        </div>
                        <button class="btn btn-block btn-success btn-flat" disabled> DATA KONSUMEN </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">ID Customer *</label>
                          <div class="col-sm-4">
                            <div class="input-group">
                              <input type="text" onchange="cek_tanya2()" class="form-control" name="id_customer" onpaste="return false;" onkeypress="return false;" id="id_customer" placeholder="ID Customer" required readonly>
                              <div class="input-group-btn">
                                <button type="button" data-toggle="modal" data-target="#Customermodal" class="btn btn-primary btn-flat"><i class="fa fa-search"></i> Browse</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Sesuai Identitas *</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_konsumen" placeholder="Nama Sesuai Identitas" name="nama_konsumen" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tempat/Tgl.Lahir *</label>
                          <div class="col-sm-4">
                            <input type="text" id="tempat_lahir" class="form-control" placeholder="Tempat Lahir" name="tempat_lahir" required>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control tgl_lahir" onchange="cek_umur()" id="tanggal2" placeholder="Tgl Lahir" name="tgl_lahir" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kewarganegaraan *</label>
                          <div class="col-sm-4">
                            <select class="form-control" id="jenis_wn" name="jenis_wn" required>
                              <option>WNA</option>
                              <option selected>WNI</option>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No KTP/KITAS *</label>
                          <div class="col-sm-4">
                            <input type="text" id="no_ktp" class="form-control" onkeypress="return number_only(event)" placeholder="No KTP/KITAS" name="no_ktp" required minlength="16" maxlength="16">
                          </div>
                        </div>

                        <div class="form-group">
                          <!--  <label for="inputEmail3" class="col-sm-2 control-label">No KK *</label>
                  <div class="col-sm-4">
                    <input type="text" id="no_kk" class="form-control" maxlength="15" onkeypress="return number_only(event)" placeholder="No KK" name="no_kk" required>                    
                  </div> -->
                          <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">No NPWP *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No NPWP" id="no_npwp" name="npwp" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Upload Foto KTP (Maks 100Kb) *</label>
                          <div class="col-sm-4">
                            <input type="file" class="form-control" id="file_ktp" onchange="cekFileUpload('file_ktp')" placeholder="Upload Foto" name="file_foto" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Upload KK (Maks 500 Kb) *</label>
                          <div class="col-sm-4">
                            <input type="file" class="form-control" id="file_kk" onchange="cekFileUpload('file_kk')" placeholder="Upload KK (Maks 500 Kb)" name="file_kk" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili *</label>
                          <div class="col-sm-4">
                            <input type="hidden" readonly name="id_kelurahan" id="id_kelurahan">
                            <input required type="text" onpaste="return false" onkeypress="return nihil(event)" name="kelurahan" data-toggle="modal" placeholder="Kelurahan Domisili" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()" autocomplete="off">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                            <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan Domisili" name="kecamatan" required>
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
                            <input type="text" class="form-control" maxlength="100" placeholder="Alamat Domisili" name="alamat" id="alamat" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kodepos *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Kodepos" id="kodepos" name="kodepos" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Longitude *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Longitude" name="longitude" id="longitude" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Pada BPKB/STNK *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="nama_bpkb" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Latitude *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Latitude" name="latitude" id="latitude" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No. KTP/KITAP Pada BPKB *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="no_ktp_bpkb" required minlength="16" maxlength="16" onkeypress="return number_only(event)">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">RT *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="RT" name="rt" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat KTP/KITAP Pada BPKB *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="alamat_ktp_bpkb" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">RW *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="RW" name="rw" required>
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
                            <select class="form-control" name="tanya" id="tanya" onchange="cek_tanya()" required>
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
                              <input type="hidden" readonly name="id_kelurahan2" id="id_kelurahan2">
                              <input type="text" type="text" onpaste="return false" onkeypress="return nihil(event)" name="kelurahan2" data-toggle="modal" data-target="#Kelurahanmodal2" class="form-control" id="kelurahan2" onchange="take_kec2()" placeholder="Kelurahan Sesuai KTP">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Sesuai KTP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_kecamatan2" id="id_kecamatan2">
                              <input type="text" readonly class="form-control" id="kecamatan2" placeholder="Kecamatan Sesuai KTP" name="kecamatan2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Sesuai KTP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_kabupaten" id="id_kabupaten2">
                              <input type="text" readonly class="form-control" placeholder="Kota/Kabupaten Sesuai KTP" id="kabupaten2" name="kabupaten2">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Sesuai KTP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_provinsi2" id="id_provinsi2">
                              <input type="text" readonly class="form-control" placeholder="Provinsi Sesuai KTP" id="provinsi2" name="provinsi2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kodepos Sesuai KTP</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Kodepos Sesuai KTP" name="kodepos2" id="kodepos2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Alamat Sesuai KTP</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" maxlength="100" placeholder="Alamat Sesuai KTP" name="alamat2" id="alamat2">
                            </div>
                          </div>
                        </span>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Status Rumah *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="status_rumah" required>
                              <option value="">- choose -</option>
                              <option>Rumah Sendiri</option>
                              <option>Rumah Orang Tua</option>
                              <option>Rumah Sewa</option>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Lama Tinggal</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Lama Tinggal" name="lama_tinggal">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                          <div class="col-sm-4">
                            <select class="form-control" id="pekerjaan" name="pekerjaan" required>
                              <option value="">- choose -</option>
                              <?php
                              foreach ($dt_pekerjaan->result() as $val) {
                                echo "
                        <option value='$val->id_pekerjaan'>$val->pekerjaan</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Lama Kerja</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Lama Kerja" name="lama_kerja">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Jabatan</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Jabatan" name="jabatan">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Tanggungan *</label>
                          <div class="col-sm-4">
                            <input type="number" class="form-control" placeholder="1" name="tanggungan" required>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Status Pernikahan *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="status_pernikahan" required>
                            <?php
                            $this->db->order_by('id_status_pernikahan', 'ASC');
                            $status_pernikahan = $this->db->get('ms_status_pernikahan');
                            if ($status_pernikahan->num_rows() > 0) {
                              echo "<option value=''>-choose-</option>";
                              foreach ($status_pernikahan->result() as $rs) {
                                echo "<option value='$rs->id_status_pernikahan'>$rs->status_pernikahan</option>";
                              }
                            }
                            ?>
                          </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Pendidikan *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="pendidikan" required>
                            <?php
                            $this->db->where('active', '1');
                            $this->db->order_by('id_pendidikan', 'ASC');
                            $pendidikan = $this->db->get('ms_pendidikan');
                            if ($pendidikan->num_rows() > 0) {
                              echo "<option value=''>-choose-</option>";
                              foreach ($pendidikan->result() as $rs) {
                                echo "<option value='$rs->id_pendidikan'>$rs->pendidikan</option>";
                              }
                            }
                            ?>
                          </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Total Penghasilan *</label>
                          <div class="col-sm-4">
                            <input type="number" class="form-control" placeholder="Total Penghasilan" name="penghasilan" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Pengeluaran Perbulan *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="pengeluaran_bulan" required>
                              <option value="">- choose -</option>
                              <?php
                              foreach ($dt_pengeluaran->result() as $val) {
                                echo "
                        <option value='$val->id_pengeluaran_bulan'>$val->pengeluaran</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No HP #1 *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No HP" id="no_hp" maxlength="15" name="no_hp" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp #1 *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="status_hp" id="status_nohp" required>
                              <option value="">- choose -</option>
                              <?php
                              foreach ($dt_status_hp->result() as $val) {
                                echo "
                        <option value='$val->id_status_hp'>$val->status_hp</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No HP #2</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No HP" id="no_hp2" maxlength="15" name="no_hp_2">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp #2</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="status_hp_2" id="status_nohp2">
                              <option value="">- choose -</option>
                              <?php
                              foreach ($dt_status_hp->result() as $val) {
                                echo "
                        <option value='$val->id_status_hp'>$val->status_hp</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No Telp" maxlength="15" id="no_telp" name="no_telp">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Email *</label>
                          <div class="col-sm-4">
                            <input type="email" maxlength="100" class="form-control" placeholder="Email" id="email" name="email" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Refferal ID</label>
                          <div class="col-sm-4">
                            <div class="input-group">
                              <input type="text" readonly class="form-control" placeholder="Refferal ID" name="refferal_id" id="refferal_id">
                              <div class="input-group-btn">
                                <button type="button" data-toggle="modal" data-target="#Reffmodal" class="btn btn-primary btn-flat"><i class="fa fa-search"></i> Browse</button>
                              </div>
                            </div>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">RO BD ID</label>
                          <div class="col-sm-4">
                            <div class="input-group">
                              <input type="text" readonly class="form-control" placeholder="Ro BD ID" name="robd_id" id="robd_id">
                              <div class="input-group-btn">
                                <button type="button" data-toggle="modal" data-target="#Robdmodal" class="btn btn-primary btn-flat"><i class="fa fa-search"></i> Browse</button>
                              </div>
                            </div>
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
                            <input type="text" class="form-control" placeholder="Nama Gadis Ibu Kandung" name="nama_ibu" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir Ibu Kandung *</label>
                          <div class="col-sm-4">
                            <input type="text" id="tanggal3" class="form-control" placeholder="Tgl Lahir Ibu Kandung" required name="tgl_ibu">
                          </div>
                          <div class="form-group">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Keterangan" maxlength="200" name="keterangan">
                          </div>
                        </div>
                        <button class="btn btn-block btn-danger btn-flat" disabled> DATA KENDARAAN </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Type *</label>
                          <div class="col-sm-4">
                            <input type="hidden" id="warna_mode">
                            <select class="form-control" name="id_tipe_kendaraan" id="id_tipe_kendaraan" onchange="take_harga()" onclick="getWarna()" required>
                              <?php
                              if (isset($_SESSION['id_tipe'])) {
                                $tipe = $_SESSION['id_tipe'];
                                echo "<option value='$tipe'>";
                                $dt_cust    = $this->m_admin->getByID("ms_tipe_kendaraan", "id_tipe_kendaraan", $tipe)->row();
                                if (isset($dt_cust)) {
                                  echo "$dt_cust->id_tipe_kendaraan | $dt_cust->tipe_ahm";
                                } else {
                                  echo "- choose -";
                                }
                              ?>
                                </option>
                              <?php
                              }
                              if ($dt_tipe->num_rows() > 0) {
                                foreach ($dt_tipe->result() as $val) {
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
                              if (isset($_SESSION['id_warna'])) {
                                $warna = $_SESSION['id_warna'];
                                echo "<option value='$warna'>";
                                $dt_cust    = $this->m_admin->getByID("ms_warna", "id_warna", $warna)->row();
                                if (isset($dt_cust)) {
                                  echo "$dt_cust->id_warna | $dt_cust->warna";
                                } else {
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
                          <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Pengiriman *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control datepicker" placeholder="Tanggal Pengiriman" name="tgl_pengiriman" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tipe Pembelian *</label>
                          <div class="col-sm-2">
                            <select class="form-control" name="jenis_beli" id="beli" onchange="get_beli()" required v-model="tipe_pembelian">
                              <option value="">- choose -</option>
                              <option value="Kredit">Kredit</option>
                              <option value="Cash">Cash</option>
                            </select>
                          </div>
                          <div class="col-sm-2">
                            <button style='width:100%' class="btn btn-primary btn-flat" type="button" onclick="get_beli()"><i class="fa fa-refresh"></i> Reload Harga</button>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Waktu Pengiriman *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Waktu Pengiriman" name="waktu_pengiriman" required>
                            <i>Contoh pengisian : 12:50:00</i>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Harga Pricelist</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="harga_pricelist" id="harga_pricelist">
                            <input type="text" class="form-control" placeholder="Harga Pricelist" readonly name="harga_pricelist_r" id="harga_pricelist_r">
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
                            <input type="text" class="form-control" readonly name="diskon" id="diskon">
                          </div>
                          <!-- <label for="inputEmail3" class="col-sm-2 control-label">Nama STNK/BPKB *</label>
                    <div class="col-sm-4">
                      <input id="nama_bpkb" type="text" required class="form-control" placeholder="Nama STNK/BPKB" name="nama_bpkb">
                    </div>  -->
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">Tanda Jadi *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="tanda_jadi" id="tanda_jadi" required>
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
                                  <?php $readonly = ''; ?>
                                  <select name="id_event" id="id_event" onchange="getEvent()" class="form-control select2" <?= $readonly ?>>
                                    <option value="">--choose-</option>
                                    <?php foreach ($event->result() as $rs) :
                                      $selected = isset($row) ? $rs->id_event == $row->id_event ? 'selected' : '' : '';
                                    ?>
                                      <option value="<?= $rs->id_event ?>" <?= $selected ?> data-nama_event="<?= $rs->kode_event . ' | ' . $rs->nama_event ?>"><?= $rs->nama_event ?></option>
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
                              var nama_event = $("#id_event").select2().find(":selected").data("nama_event");
                              $('#nama_event').val(nama_event);

                            }
                          </script>
                        </div>
                        <button class="btn btn-block btn-warning btn-flat" disabled> SISTEM PEMBELIAN </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kode PPN</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="kode_ppn">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Faktur Pajak</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="faktur_pajak">
                          </div>
                        </div>
                        <div class="form-group">
                        </div>
                        <div id="lbl_cash" v-if="tipe_pembelian=='Cash'">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">On/Off The Road *</label>
                            <div class="col-sm-4">
                              <select class="form-control" name="the_road" id="the_road" onchange="get_on()" required>
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
                                </select>
                              </div>
                            </span>
                            <label for="inputEmail3" class="col-sm-2 control-label">Voucher Tambahan</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" id="voucher_tambahan_1" placeholder="Voucher Tambahan" name="voucher_tambahan_1" onkeyup="get_total_ck()" autocomplete="off" value="0">
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
                        </div>






                        <span id="lbl_kredit" v-if="tipe_pembelian=='Kredit'">
                          <input type="hidden" name="harga_tunai" id="harga_tunai">
                          <input type="hidden" name="the_road" value="On The Road">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-12">Data Penjamin</label>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Nama *</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Nama Penjamin" name="nama_penjamin" required>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Finance Company *</label>
                            <div class="col-sm-4">

                              <input type="text" class="form-control" placeholder="Finance Company" name="finance_company" required v-model="finco" readonly>
                              <input type="hidden" class="form-control" placeholder="Finance Company" name="id_finance_company" v-model='id_finance_company' required readonly>
                              <!-- <select class="form-control select2" name="id_finance_company" v-model="finco" readonly>
                        <option value="">- choose -</option>                      
                        <?php
                        foreach ($dt_finance->result() as $isi) {
                          echo "<option value='$isi->id_finance_company'>$isi->finance_company</option>";
                        }
                        ?>
                      </select> -->
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Hub. dg Penjamin *</label>
                            <div class="col-sm-4">
                              <select class="form-control" name="hub_penjamin" required>
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
                            <label for="inputEmail3" class="col-sm-2 control-label">No HP *</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="No HP" maxlength="15" name="no_hp_penjamin" required>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label" id="nilai_voucher2_lbl">Nilai Voucher</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" readonly placeholder="Nilai Voucher" onchange="get_total_ck()" name="nilai_voucher2" id="nilai_voucher2"> <input type="hidden" class="form-control" name="voucher_2" id="voucher_2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Tempat/Tgl Lahir *</label>
                            <div class="col-sm-2">
                              <input type="text" class="form-control" placeholder="Tempat Lahir" name="tempat_lahir_penjamin" required>
                            </div>
                            <div class="col-sm-2">
                              <input type="text" id="tanggal4" class="form-control" name="tgl_lahir_penjamin" placeholder="Tgl Lahir Penjamin" required>
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
                                foreach ($dt_pekerjaan->result() as $val) {
                                  echo "
                          <option value='$val->id_pekerjaan'>$val->pekerjaan</option>;
                          ";
                                }
                                ?>
                              </select>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Down Payment Gross *</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Down Payment Gross" id="uang_muka" onchange="get_total_ck()" name="uang_muka" readonly v-model="uang_muka" required>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Penghasilan</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Penghasilan Penjamin" name="penghasilan_penjamin">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Down Payment Setor *</label>
                            <div class="col-sm-4">
                              <input readonly id="dp_setor" type="text" class="form-control" placeholder="DP Setor" name="dp_setor">
                              <input readonly id="dp_stor" type="hidden" class="form-control" placeholder="DP Setor" name="dp_stor">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">No KTP *</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="No KTP Penjamin" name="no_ktp_penjamin" required minlength="16" maxlength="16" onkeypress="return number_only(event)">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Tenor (Bulan) *</label>
                            <div class="col-sm-3">
                              <input type="text" class="form-control" placeholder="Tenor (Bulan)" name="tenor" id="tenor" v-model="tenor" readonly required>
                            </div>
                            <div class="col-sm-1">
                              Bulan
                            </div>
                          </div>
                          <input type="hidden" id="total_bayar" name="total_bayar" value="">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Foto KTP (Maks 100Kb) *</label>
                            <div class="col-sm-4">
                              <input type="file" class="form-control" name="file_ktp_2" id="file_ktp_2" onchange="cekFileUpload('file_ktp_2')" required>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Angsuran *</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Angsuran" name="angsuran" id="angsuran" v-model="angsuran" readonly required>
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
                          <label for="inputEmail3" class="col-sm-2 control-label">No. KK *</label>
                          <div class="col-sm-4">
                            <input type="text" id="no_kk" class="form-control" minlength="16" maxlength="16" onkeypress="return number_only(event)" placeholder="No KK" name="no_kk" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat Kartu Keluarga *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Alamat Kartu Keluarga" name="alamat_kk" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan KK *</label>
                          <div class="col-sm-4">
                            <input type="hidden" readonly name="id_kelurahan_kk" id="id_kelurahan_kk">
                            <input required type="text" onpaste="return false" onkeypress="return nihil(event)" name="kelurahan" placeholder="Kelurahan KK" class="form-control" id="kelurahan_kk" autocomplete="off" onclick="showModalKelurahan('kk')">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan KK</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="kecamatan_kk" placeholder="Kecamatan KK" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten KK</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten KK" id="kabupaten_kk" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Provinsi KK</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly placeholder="Provinsi KK" id="provinsi_kk" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kode POS KK *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" onkeypress="return number_only(event)" placeholder="Kode POS KK" id="kode_pos_kk" name='kode_pos_kk' required>
                          </div>
                        </div>
                        <?php /*
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">

                          </label>
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
                              <tr>
                                <td><b>No.</b></td>
                                <td><b>List Anggota</b></td>
                                <td><b>Aksi</b></td>
                              </tr>
                              <tr v-for="(agt, index) of anggota_">
                                <td>{{index+1}}. </td>
                                <td><input type="text" name="anggota_kk[]" class="form-control" v-model="agt.anggota"></td>
                                <td>
                                  <button type="button" @click.prevent="delAnggota(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </td>
                              </tr>
                            </table>
                          </div>
                        </div>
*/ ?>
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
                        <div class="col-sm-12" align='center'>
                          <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                          <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>
                        </div>
                      </div><!-- /.box-footer -->
                    </form>
                  </div>
                </div>
              </div>
            </div><!-- /.box -->
            <?php
            $data['data'] = ['kelurahan'];
            $this->load->view('dealer/h2_api', $data);
            ?>
            <script>
              var kelurahan_untuk = '';

              function pilihKelurahan(params) {
                if (kelurahan_untuk === 'kk') {
                  $("#id_kelurahan_kk").val(params.id_kelurahan)
                  $("#kelurahan_kk").val(params.kelurahan)
                  $("#kecamatan_kk").val(params.kecamatan)
                  $("#kabupaten_kk").val(params.kabupaten)
                  $("#provinsi_kk").val(params.provinsi)
                  $("#kode_pos_kk").val(params.kode_pos)
                }
                console.log(params);
              }
              var form_ = new Vue({
                el: '#form_',
                data: {
                  ksu_: <?= isset($ksu_) ? json_encode($ksu_) : '[]' ?>,
                  id_tipe_kendaraan: '',
                  tipe_pembelian: '',
                  tenor: '',
                  uang_muka: '',
                  angsuran: '',
                  finco: '',
                  id_finance_company: '',
                  anggota: {
                    anggota: ''
                  },
                  file_pendukung: {
                    file: '',
                    nama_file: ''
                  },
                  anggota_: <?= isset($anggota_) ? json_encode($anggota_) : '[]' ?>,
                  file_pendukung_: <?= isset($file_pendukung_) ? json_encode($file_pendukung_) : '[]' ?>,
                },
                methods: {
                  clearAnggota: function() {
                    this.anggota = {
                      anggota: ''
                    };
                  },
                  addAnggota: function() {
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
                    if (this.anggota.anggota == '') {
                      alert('Tentukan nama anggota terlebih dahulu !');
                      return false;
                    }
                    this.anggota_.push(this.anggota);
                    this.clearAnggota();
                  },
                  delAnggota: function(index) {
                    this.anggota_.splice(index, 1);
                  },
                  addFile: function() {
                    this.file_pendukung_.push(this.file_pendukung);
                    // console.log(this.file_pendukung_)
                    this.clearFile();
                  },
                  clearFile: function() {
                    this.file_pendukung = {
                      file: '',
                      nama_file: ''
                    }
                  },
                  delFile: function(index) {
                    this.file_pendukung_.splice(index, 1);
                  },

                }
              });

              function cekFileUpload(id) {
                var file_size = $('#' + id)[0].files[0].size;
                var val = $('#' + id).val().toLowerCase(),
                  regex = new RegExp("(.*?)\.(jpg|jpeg|png)$");
                var pesan_size = '';
                var pesan_type = '';
                var maks_upl = 153600;
                if (!(regex.test(val))) {
                  $('#' + id).val('');
                  if (id == 'file_ktp') var pesan_size = 'Format file KTP yg diupload tidak sesuai, tipe file yg harus diupload adalah (*.jpg, *.jpeg, *.png) !';
                  if (id == 'file_ktp_2') var pesan_size = 'Format file KTP Penjamin yg diupload tidak sesuai, tipe file yg harus diupload adalah (*.jpg, *.jpeg, *.png) !';
                  if (id == 'file_kk') var pesan_size = 'Format file KK yg diupload tidak sesuai, tipe file yg harus diupload adalah (*.jpg, *.jpeg, *.png) !';
                }
                if (id == 'file_kk') {
                  maks_upl = 512000;
                }
                if (file_size > maks_upl) {
                  $('#' + id).val('');
                  if (id == 'file_ktp') var pesan_type = 'Ukuran file KTP yg diupload terlalu besar !';
                  if (id == 'file_ktp_2') var pesan_type = 'Ukuran file KTP Penjamin yg diupload terlalu besar !';
                  if (id == 'file_kk') var pesan_type = 'Ukuran file KK yg diupload terlalu besar !';
                }
                if (pesan_size != '' && pesan_type != '') {
                  toastr_error(pesan_size + ', Serta ' + pesan_type);
                  return false;
                } else {
                  if (pesan_type != '') {
                    toastr_error(pesan_type);
                    return false;
                  }
                  if (pesan_size != '') {
                    toastr_error(pesan_size);
                    return false;
                  }
                }
              }
              $('#submitBtn').click(function() {
                $('#form_').validate({
                  rules: {
                    'checkbox': {
                      required: true
                    }
                  },
                  highlight: function(input) {
                    $(input).parents('.form-group').addClass('has-error');
                  },
                  unhighlight: function(input) {
                    $(input).parents('.form-group').removeClass('has-error');
                  }
                })

                if ($('#form_').valid()) // check if form is valid
                {
                  // var totNominal = $('#totNominal').text();
                  // if (totNominal==0) {
                  //   alert('Detail belum dipilih !');
                  //   return false;
                  // }
                  let harga = parseInt($('#harga').val());
                  if (harga === 0 || harga === '') {
                    console.log(harga)
                    toastr_error('Pastikan harga untuk unit yang dipilih telah ditentukan !');
                    return false;
                  }

                  jenis_beli = $("#beli").val();
                  if (jenis_beli == 'Cash') {
                    voucher_1 = parseInt($('#voucher_1').val());
                    let id_program_md = $('#lbl_cash #program_umum').val();
                    if (id_program_md === '' && voucher_1 > 0) {
                      toastr_error('Sales program tidak terpilih, tetapi voucher terisi !');
                      return false;
                    }
                  } else if (jenis_beli == 'Kredit') {
                    voucher_2 = parseInt($('#voucher_2').val());
                    let id_program_md = $('#lbl_kredit #program_umum').val();
                    if (id_program_md === '' && voucher_2 > 0) {
                      toastr_error('Sales program tidak terpilih, tetapi voucher terisi !');
                      return false;
                    }
                  }

                  if (confirm('Apakah Anda yakin ?') == true) {
                    $('#submitBtn').attr('disabled', true);
                    $('#form_').submit();
                  }
                } else {
                  alert('Silahkan isi field required !')
                }
              })
            </script>
          <?php
          } elseif ($set == 'insert_gc') {
          ?>
            <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
            <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
            <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/spk_fif/gc">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
                    <form id="form_" class="form-horizontal" action="dealer/spk_fif/save_gc" method="post" enctype="multipart/form-data">
                      <div class="box-body">
                        <button class="btn btn-block btn-primary btn-flat" disabled> SPK </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama NPWP *</label>
                          <div class="col-sm-4">
                            <input type="hidden" id="id_prospek_gc" name="id_prospek_gc">
                            <input type="text" onchange="cek_tanya3()" class="form-control" name="nama_npwp" onpaste="return false;" onkeypress="return false;" id="nama_npwp" placeholder="Nama NPWP" required>
                          </div>
                          <div class="col-sm-1">
                            <a class="btn btn-primary btn-flat btn-sm" data-toggle="modal" data-target="#Npwpmodal" type="button"><i class="fa fa-search"></i> Browse</a>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No TDP *</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" name="no_tdp" placeholder="No TDP" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No NPWP *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="no_npwp" placeholder="No NPWP" name="no_npwp" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Jenis GC</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" placeholder="Jenis GC" name="jenis_gc" id="jenis_gc">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No Telp Perusahaan</label>
                          <div class="col-sm-4">
                            <input type="text" id="no_telp" class="form-control" placeholder="No Telp Perusahaan" name="no_telp">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl Berdiri Perusahaan</label>
                          <div class="col-sm-4">
                            <input type="text" id="tanggal4" class="form-control" placeholder="Tgl Berdiri Perusahaan" name="tgl_berdiri">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No. Fax Perusahaan</label>
                          <div class="col-sm-4">
                            <input type="text" id="no_fax" class="form-control" placeholder="No. Fax Perusahaan" name="no_fax">
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili *</label>
                          <div class="col-sm-4">
                            <input type="hidden" readonly name="id_kelurahan" id="id_kelurahan">
                            <input required type="text" onpaste="return false" onkeypress="return nihil(event)" name="kelurahan" data-toggle="modal" placeholder="Kelurahan Domisili" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                            <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan Domisili" name="kecamatan" required>
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
                            <input type="text" class="form-control" maxlength="100" placeholder="Alamat Domisili" name="alamat" id="alamat" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kodepos *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Kodepos" id="kodepos" name="kodepos" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama BPKB/STNK</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Nama BPKB/STNK" id="nama_bpkb" name="nama_bpkb">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Longitude *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Longitude" id="longitude" name="longitude" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Latitude *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Latitude" id="latitude" name="latitude" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Apakah alamat domisili sama dengan alamat di NPWP? *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="tanya" id="tanya" onchange="cek_tanya()" required>
                              <option value="">- choose -</option>
                              <option>Ya</option>
                              <option>Tidak</option>
                            </select>
                          </div>
                        </div>
                        <span id="tampil_alamat">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Sesuai NPWP</label>
                            <div class="col-sm-4">
                              <input type="hidden" readonly name="id_kelurahan2" id="id_kelurahan2">
                              <input type="text" type="text" onpaste="return false" onkeypress="return nihil(event)" name="kelurahan2" data-toggle="modal" data-target="#Kelurahanmodal2" class="form-control" id="kelurahan2" onchange="take_kec2()" placeholder="Kelurahan Sesuai NPWP">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Sesuai NPWP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_kecamatan2" id="id_kecamatan2">
                              <input type="text" readonly class="form-control" id="kecamatan2" placeholder="Kecamatan Sesuai NPWP" name="kecamatan2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Sesuai NPWP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_kabupaten" id="id_kabupaten2">
                              <input type="text" readonly class="form-control" placeholder="Kota/Kabupaten Sesuai NPWP" id="kabupaten2" name="kabupaten2">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Sesuai NPWP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_provinsi2" id="id_provinsi2">
                              <input type="text" readonly class="form-control" placeholder="Provinsi Sesuai NPWP" id="provinsi2" name="provinsi2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kodepos NPWP</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Kodepos Sesuai NPWP" id="kodepos2" name="kodepos2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Alamat Sesuai NPWP</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" maxlength="100" placeholder="Alamat Sesuai NPWP" id="alamat2" name="alamat2">
                            </div>
                          </div>
                        </span>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">ID Event</label>
                          <div class="col-sm-4">
                            <select name="id_event" id="id_event" onchange="setEvent()" class="form-control select2">
                              <option value="">--choose-</option>
                              <?php
                              $ev = $this->db->get_where('ms_event', ['id_event' => $row->id_event]);
                              if ($ev->num_rows() > 0) {
                                $row->nama_event  = $ev->row()->nama_event;
                              } else {
                                $row->nama_event = '';
                              }
                              foreach ($this->m_prospek->getEvent()->result() as $rs) :
                                $selected = isset($row) ? $rs->id_event == $row->id_event ? 'selected' : '' : '';
                              ?>
                                <option value="<?= $rs->id_event ?>" <?= $selected ?> data-nama_event="<?= $rs->nama_event ?>"><?= $rs->id_event . ' | ' . $rs->nama_event ?></option>
                              <?php endforeach ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Event</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id='nama_event' readonly value="<?= isset($row) ? $row->nama_event : '' ?>">
                          </div>
                          <script>
                            function setEvent() {
                              var nama_event = $("#id_event").select2().find(":selected").data("nama_event");
                              $('#nama_event').val(nama_event);

                            }
                          </script>
                        </div>
                        <button class="btn btn-block btn-warning btn-flat" disabled> Penanggung Jawab </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Penanggung Jawab</label>
                          <div class="col-sm-4">
                            <input type="text" id="nama_penanggung_jawab" class="form-control" placeholder="Nama Penanggung Jawab" name="nama_penanggung_jawab">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                          <div class="col-sm-4">
                            <input type="text" id="email" class="form-control" placeholder="Email" name="email">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                          <div class="col-sm-4">
                            <input type="text" id="no_hp" class="form-control" placeholder="No HP" name="no_hp">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Status HP</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="status_nohp" id="status_nohp">
                            </select>
                          </div>
                        </div>
                        <button class="btn btn-block btn-danger btn-flat" disabled> DATA KENDARAAN </button> <br>
                        <!-- <button class="btn btn-block btn-danger btn-flat" onclick="cek_prospek()" type="button"> DATA KENDARAAN </button> <br> -->
                        <div id="showDetail"></div>
                        <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Pengiriman *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control datepicker" placeholder="Tgl. Pengiriman" name="tgl_pengiriman" required value="">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Waktu Pengiriman *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Waktu Pengiriman" name="waktu_pengiriman" required>
                            <i>Contoh pengisian : 12:50:00</i>
                          </div>
                        </div>
                        <button class="btn btn-block btn-warning btn-flat" disabled> SISTEM PEMBELIAN </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kode PPN</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="kode_ppn">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Faktur Pajak</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="faktur_pajak">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tipe Pembelian *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="jenis_beli" id="beli" onchange="get_beli()" required>
                              <option value="">- choose -</option>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Sales Program</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="id_sales_program_gc" id="id_sales_program_gc" onchange="cek_program_gc()">
                              <option value="">- choose -</option>
                              <?php
                              $mb = $this->db->query("SELECT * FROM tr_sales_program INNER JOIN ms_jenis_sales_program ON tr_sales_program.id_jenis_sales_program = ms_jenis_sales_program.id_jenis_sales_program
                            WHERE ms_jenis_sales_program.jenis_sales_program = 'Group Customer'");
                              foreach ($mb->result() as $isi) {
                                echo "<option value='$isi->id_program_md'>$isi->id_program_md</option>";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tanda Jadi Sementara</label>
                          <div class="col-sm-4">
                            <input class='form-control' name='tanda_jadi' onkeypress="return number_only(event)" value='0' />
                          </div>
                        </div>

                        <!-- <div class="col-sm-3">                    
                    <button class="btn btn-primary btn-flat" type="button" onclick="tampil_cash()"><i class="fa fa-refresh"></i> Cash</button>
                    <button class="btn btn-primary btn-flat" type="button" onclick="tampil_kredit()"><i class="fa fa-refresh"></i> Kredit</button>
                  </div>
                  -->
                        <span id="lbl_cash">
                          <div id="showDetail_cash"></div>
                        </span>
                        <span id="lbl_kredit">
                          <div id="showDetail_kredit"></div>
                        </span>




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
              var form_ = new Vue({
                el: '#form_',
                data: {
                  tipe_pembelian: '',
                },
                methods: {}
              });
            </script>
          <?php
          } elseif ($set == 'edit_gc') {
            $row = $dt_spk->row();
            if ($form == 'edit') {
              $readonly = '';
            }
            if ($form == 'detail') {
              $readonly = 'readonly';
            }
          ?>
            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/spk_fif/gc">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
                    <form class="form-horizontal" action="dealer/spk_fif/update_gc" method="post" enctype="multipart/form-data">
                      <div class="box-body">
                        <button class="btn btn-block btn-primary btn-flat" disabled> SPK </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl SPK *</label>
                          <div class="col-sm-4">
                            <?php if (isset($out)) { ?>
                              <input type='hidden' name='out' value='true' />
                            <?php } ?>
                            <input type="text" value="<?php echo $row->tgl_spk_gc ?>" class="form-control datepicker" name="tgl_spk_gc" onpaste="return false;" onkeypress="return false;" id="tgl_spk_gc" required <?= isset($out) ? 'readonly' : 'disabled' ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama NPWP *</label>
                          <div class="col-sm-4">
                            <input type="hidden" id="id_prospek_gc" name="id_prospek_gc" value="<?php echo $row->id_prospek_gc ?>">
                            <input type="hidden" id="no_spk_gc" name="no_spk_gc" value="<?php echo $row->no_spk_gc ?>">
                            <input readonly type="text" onchange="cek_tanya3()" value="<?php echo $row->nama_npwp ?>" class="form-control" name="nama_npwp" onpaste="return false;" onkeypress="return false;" id="nama_npwp" placeholder="Nama NPWP" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No TDP *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="no_tdp" value="<?php echo $row->no_tdp ?>" placeholder="No TDP" required <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No NPWP *</label>
                          <div class="col-sm-4">
                            <input type="text" value="<?php echo $row->no_npwp ?>" class="form-control" readonly id="no_npwp" placeholder="No NPWP" name="no_npwp" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Jenis GC</label>
                          <div class="col-sm-4">
                            <input type="text" readonly value="<?php echo $row->jenis_gc ?>" class="form-control" placeholder="Jenis GC" name="jenis_gc" id="jenis_gc">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No Telp Perusahaan</label>
                          <div class="col-sm-4">
                            <input type="text" id="no_telp" class="form-control" value="<?php echo $row->no_telp ?>" placeholder="No Telp Perusahaan" name="no_telp" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl Berdiri Perusahaan</label>
                          <div class="col-sm-4">
                            <input type="text" id="tanggal4" class="form-control" value="<?php echo $row->tgl_berdiri ?>" placeholder="Tgl Berdiri Perusahaan" name="tgl_berdiri" <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No. Fax Perusahaan</label>
                          <div class="col-sm-4">
                            <input type="text" id="no_fax" class="form-control" placeholder="No. Fax Perusahaan" name="no_fax" value='<?= $row->no_fax ?>' <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili *</label>
                          <div class="col-sm-4">
                            <?php
                            $id_kelurahan = $row->id_kelurahan;
                            $dt_kel       = $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$id_kelurahan'")->row();
                            $kelurahan    = $dt_kel->kelurahan;
                            $kode_pos     = $dt_kel->kode_pos;
                            $id_kecamatan = $dt_kel->id_kecamatan;
                            $dt_kec       = $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan'")->row();
                            $kecamatan    = $dt_kec->kecamatan;
                            $id_kabupaten = $dt_kec->id_kabupaten;
                            $dt_kab       = $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten'")->row();
                            $kabupaten    = $dt_kab->kabupaten;
                            $id_provinsi  = $dt_kab->id_provinsi;
                            $dt_pro       = $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$id_provinsi'")->row();
                            $provinsi     = $dt_pro->provinsi;

                            ?>
                            <input type="hidden" value="<?php echo $row->id_kelurahan ?>" readonly name="id_kelurahan" id="id_kelurahan">
                            <input required value="<?php echo $kelurahan ?>" type="text" onpaste="return false" onkeypress="return nihil(event)" name="kelurahan" data-toggle="modal" placeholder="Kelurahan Domisili" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kecamatan" value="<?php echo $id_kecamatan ?>" id="id_kecamatan">
                            <input type="text" class="form-control" readonly id="kecamatan" value="<?php echo $kecamatan ?>" placeholder="Kecamatan Domisili" name="kecamatan" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kabupaten" id="id_kabupaten" value="<?php echo $id_kabupaten ?>">
                            <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten Domisili" value="<?php echo $kabupaten ?>" id="kabupaten" name="kabupaten" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_provinsi" id="id_provinsi" value="<?php echo $id_provinsi ?>">
                            <input type="text" class="form-control" readonly placeholder="Provinsi Domisili" value="<?php echo $provinsi ?>" id="provinsi" name="provinsi" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili *</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="100" placeholder="Alamat Domisili" value="<?php echo $row->alamat ?>" name="alamat" id="alamat" required <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kodepos *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Kodepos" id="kodepos" name="kodepos" value="<?php echo $row->kodepos ?>" required <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama BPKB/STNK</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Nama BPKB/STNK" id="nama_bpkb" value="<?php echo $row->nama_bpkb ?>" name="nama_bpkb" <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Longitude *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Longitude" id="longitude" name="longitude" value="<?= $row->longitude ?>" required <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Latitude *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Latitude" id="latitude" name="latitude" value="<?= $row->latitude ?>" required <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Apakah alamat domisili sama dengan alamat di NPWP? *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="tanya" id="tanya" onchange="cek_tanya()" required <?= $readonly ?>>
                              <?php if ($row->alamat_sama == 'Ya') { ?>
                                <option>Ya</option>
                                <option>Tidak</option>
                              <?php } else { ?>
                                <option>Tidak</option>
                                <option>Ya</option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                        <span id="tampil_alamat">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Sesuai NPWP</label>
                            <div class="col-sm-4">
                              <?php
                              $id_kelurahan = "";
                              $kelurahan = "";
                              $id_kecamatan = "";
                              $kecamatan = "";
                              $id_kabupaten = "";
                              $kabupaten = "";
                              $id_provinsi = "";
                              $provinsi = "";
                              if ($row->id_kelurahan2 != "") {
                                $id_kelurahan = $row->id_kelurahan2;
                                $dt_kel       = $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$id_kelurahan'")->row();
                                $kelurahan    = $dt_kel->kelurahan;
                                $kode_pos     = $dt_kel->kode_pos;
                                $id_kecamatan = $dt_kel->id_kecamatan;
                                $dt_kec       = $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan'")->row();
                                $kecamatan    = $dt_kec->kecamatan;
                                $id_kabupaten = $dt_kec->id_kabupaten;
                                $dt_kab       = $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten'")->row();
                                $kabupaten    = $dt_kab->kabupaten;
                                $id_provinsi  = $dt_kab->id_provinsi;
                                $dt_pro       = $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$id_provinsi'")->row();
                                $provinsi     = $dt_pro->provinsi;
                              }

                              ?>
                              <input type="hidden" value="<?php echo $id_kelurahan ?>" readonly name="id_kelurahan2" id="id_kelurahan2">
                              <input type="text" value="<?php echo $kelurahan ?>" type="text" onpaste="return false" onkeypress="return nihil(event)" name="kelurahan2" data-toggle="modal" data-target="#Kelurahanmodal2" class="form-control" id="kelurahan2" onchange="take_kec2()" placeholder="Kelurahan Sesuai NPWP" <?= $readonly ?>>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Sesuai NPWP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_kecamatan2" value="<?php echo $id_kecamatan ?>" id="id_kecamatan2">
                              <input type="text" readonly class="form-control" id="kecamatan2" value="<?php echo $kecamatan ?>" placeholder="Kecamatan Sesuai NPWP" name="kecamatan2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Sesuai NPWP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_kabupaten" id="id_kabupaten2" value="<?php echo $id_kabupaten ?>">
                              <input type="text" readonly class="form-control" placeholder="Kota/Kabupaten Sesuai NPWP" value="<?php echo $kabupaten ?>" id="kabupaten2" name="kabupaten2">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Sesuai NPWP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_provinsi2" id="id_provinsi2" value="<?php echo $id_provinsi ?>">
                              <input type="text" readonly class="form-control" placeholder="Provinsi Sesuai NPWP" value="<?php echo $provinsi ?>" id="provinsi2" name="provinsi2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kodepos NPWP</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Kodepos Sesuai NPWP" id="kodepos2" value="<?php echo $row->kodepos2 ?>" name="kodepos2" <?= $readonly ?>>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Alamat Sesuai NPWP</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" maxlength="100" placeholder="Alamat Sesuai NPWP" id="alamat2" value="<?php echo $row->alamat2 ?>" name="alamat2" <?= $readonly ?>>
                            </div>
                          </div>
                        </span>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">ID Event</label>
                          <div class="col-sm-4">
                            <select name="id_event" id="id_event" onchange="setEvent()" class="form-control select2" <?= $readonly ?>>
                              <option value="">--choose-</option>
                              <?php
                              $ev = $this->db->get_where('ms_event', ['id_event' => $row->id_event]);
                              if ($ev->num_rows() > 0) {
                                $row->nama_event  = $ev->row()->nama_event;
                              } else {
                                $row->nama_event = '';
                              }
                              foreach ($this->m_prospek->getEvent()->result() as $rs) :
                                $selected = isset($row) ? $rs->id_event == $row->id_event ? 'selected' : '' : '';
                              ?>
                                <option value="<?= $rs->id_event ?>" <?= $selected ?> data-nama_event="<?= $rs->nama_event ?>"><?= $rs->id_event . ' | ' . $rs->nama_event ?></option>
                              <?php endforeach ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Event</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id='nama_event' readonly value="<?= isset($row) ? $row->nama_event : '' ?>">
                          </div>
                          <script>
                            function setEvent() {
                              var nama_event = $("#id_event").select2().find(":selected").data("nama_event");
                              $('#nama_event').val(nama_event);

                            }
                          </script>
                        </div>
                        <button class="btn btn-block btn-warning btn-flat" disabled> Penanggung Jawab </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Penanggung Jawab</label>
                          <div class="col-sm-4">
                            <input type="text" value="<?php echo $row->nama_penanggung_jawab ?>" id="nama_penanggung_jawab" class="form-control" placeholder="Nama Penanggung Jawab" name="nama_penanggung_jawab" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                          <div class="col-sm-4">
                            <input type="text" id="email" class="form-control" value="<?php echo $row->email ?>" placeholder="Email" name="email" <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                          <div class="col-sm-4">
                            <input type="text" id="no_hp" class="form-control" placeholder="No HP" value="<?php echo $row->no_hp_penjamin ?>" name="no_hp" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Status HP</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="status_nohp" id="status_nohp" <?= $readonly ?>>
                              <option value="<?php echo $row->status_nohp ?>">
                                <?php
                                $dt_cust    = $this->m_admin->getByID("ms_status_hp", "id_status_hp", $row->status_nohp)->row();
                                if (isset($dt_cust)) {
                                  echo $dt_cust->status_hp;
                                } else {
                                  echo "- choose -";
                                }
                                ?>
                                <?php
                                $dt = $this->m_admin->kondisiCond("ms_status_hp", "id_status_hp != '$row->status_nohp'");
                                foreach ($dt->result() as $val) {
                                  echo "
                          <option value='$val->id_status_hp'>$val->status_hp</option>;
                          ";
                                }
                                ?>
                              </option>
                            </select>
                          </div>
                        </div>
                        <!-- <button class="btn btn-block btn-danger btn-flat" disabled> DATA KENDARAAN </button> <br> -->
                        <button class="btn btn-block btn-danger btn-flat" disabled type="button"> DATA KENDARAAN </button> <br>
                        <div id="showDetail"></div>
                        <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Pengiriman *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control datepicker" placeholder="Tgl. Pengiriman" name="tgl_pengiriman" required value="<?= $row->tgl_pengiriman ?>" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Waktu Pengiriman *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Waktu Pengiriman" name="waktu_pengiriman" required value="<?= $row->waktu_pengiriman ?>" <?= $readonly ?>>
                            <i>Contoh pengisian : 12:50:00</i>
                          </div>
                        </div>
                        <button class="btn btn-block btn-warning btn-flat" disabled> SISTEM PEMBELIAN </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kode PPN</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="kode_ppn" value="<?= $row->kode_ppn ?>" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Faktur Pajak</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="faktur_pajak" value="<?= $row->faktur_pajak ?>" <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tipe Pembelian *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="jenis_beli" id="beli" onchange="get_beli()" required <?= $readonly ?>>
                              <option value="<?php echo $row->jenis_beli ?>"><?php echo $row->jenis_beli ?></option>
                              <!-- <option>Kredit</option>
                      <option class="hide">Cash</option> -->
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Sales Program</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="id_sales_program_gc" id="id_sales_program_gc" onchange="cek_program_gc()" <?= $readonly ?>>

                              <option value="<?php echo $row->id_program ?>">
                                <?php
                                $dt_cust = $this->db->query("SELECT * FROM tr_sales_program INNER JOIN ms_jenis_sales_program ON tr_sales_program.id_jenis_sales_program = ms_jenis_sales_program.id_jenis_sales_program
                            WHERE ms_jenis_sales_program.jenis_sales_program = 'Group Customer' AND id_program_md = '$row->id_program'")->row();
                                if (isset($dt_cust)) {
                                  echo $dt_cust->id_program_md;
                                } else {
                                  echo "- choose -";
                                }
                                ?>
                                <?php
                                $dt = $this->db->query("SELECT * FROM tr_sales_program INNER JOIN ms_jenis_sales_program ON tr_sales_program.id_jenis_sales_program = ms_jenis_sales_program.id_jenis_sales_program
                            WHERE ms_jenis_sales_program.jenis_sales_program = 'Group Customer' AND id_program_md != '$row->id_program'");
                                foreach ($dt->result() as $val) {
                                  echo "
                          <option value='$val->id_program_md'>$val->id_program_md</option>;
                          ";
                                }
                                ?>
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tanda Jadi Sementara</label>
                          <div class="col-sm-4">
                            <input class='form-control' name='tanda_jadi' onkeypress="return number_only(event)" value='<?= $row->tanda_jadi ?>' <?= $readonly ?> />
                          </div>
                        </div>
                        <!-- <div class="col-sm-3">                    
                    <button class="btn btn-primary btn-flat" type="button" onclick="tampil_cash()"><i class="fa fa-refresh"></i> Cash</button>
                    <button class="btn btn-primary btn-flat" type="button" onclick="tampil_kredit()"><i class="fa fa-refresh"></i> Kredit</button>
                  </div>
                  -->
                        <span id="lbl_cash">
                          <!-- <div id="showDetail_cash"></div> -->
                          <button class="btn btn-block btn-danger btn-flat" disabled> Cash </button> <br>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">On/Off The Road</label>
                            <div class="col-sm-4">
                              <select class="form-control" name="on_road_gc" id="on_road_gc" onchange="cek_road_gc()" <?= $readonly ?>>
                                <?php if ($row->on_road_gc == 'On The Road') { ?>
                                  <option>On The Road</option>
                                  <option>Off The Road</option>
                                <?php } else { ?>
                                  <option>Off The Road</option>
                                  <option>On The Road</option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                          <table class="table table-bordered table-hover myTable1">
                            <thead>
                              <tr>
                                <th>Tipe Kendaraan</th>
                                <th>Warna</th>
                                <th>Qty</th>
                                <th>Harga Satuan</th>
                                <th>Biaya BBN</th>
                                <th>Nilai Voucher</th>
                                <th>Voucher Tambahan</th>
                                <th>Total</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $total = 0;
                              $no = 1;
                              $detail = $this->db->query("SELECT * FROM tr_spk_gc_detail LEFT JOIN ms_tipe_kendaraan ON tr_spk_gc_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                        LEFT JOIN ms_warna ON tr_spk_gc_detail.id_warna = ms_warna.id_warna WHERE no_spk_gc = '$row->no_spk_gc'");
                              foreach ($detail->result() as $rs) {
                                $detail2 = $this->db->query("SELECT tr_spk_gc_kendaraan.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_spk_gc_kendaraan
                          LEFT JOIN ms_tipe_kendaraan on tr_spk_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                          LEFT JOIN ms_warna ON tr_spk_gc_kendaraan.id_warna = ms_warna.id_warna
                          WHERE no_spk_gc='$row->no_spk_gc' AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$rs->id_tipe_kendaraan'")->row();
                                $harga_pas = ($detail2->total_unit - $rs->nilai_voucher - $rs->voucher_tambahan) * $rs->qty;
                              ?>
                                <tr>
                                  <td><?= $rs->id_tipe_kendaraan . " - " . $rs->tipe_ahm ?></td>
                                  <td><?= $rs->id_warna . " - " . $rs->warna ?></td>
                                  <td><?= $rs->qty ?></td>
                                  <td align="right"><?= mata_uang2($rs->harga) ?></td>
                                  <td align="right"><?= mata_uang2($rs->biaya_bbn) ?></td>
                                  <td align="right"><?= mata_uang2($rs->nilai_voucher) ?></td>
                                  <td align="right"><?= mata_uang2($rs->voucher_tambahan) ?></td>
                                  <td align="right"><?= mata_uang2($harga_pas) ?></td>
                                </tr>
                              <?php
                                $no++;
                                $total += $harga_pas;
                              }
                              ?>
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="7"></td>
                                <td align="right"><?= mata_uang2($total) ?></td>
                              </tr>
                            </tfoot>
                          </table>
                        </span>
                        <span id="lbl_kredit">
                          <button class="btn btn-block btn-danger btn-flat" disabled> Kredit </button> <br>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Nama Penjamin</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" value="<?php echo $row->nama_penjamin ?>" placeholder="Nama Penjamin" name="nama_penjamin" id="nama_penjamin" <?= $readonly ?>>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Tempat, Tgl Lahir</label>
                            <div class="col-sm-2">
                              <input type="text" class="form-control" value="<?php echo $row->tempat_lahir ?>" placeholder="Tempat Lahir" name="tempat_lahir" id="tempat_lahir" <?= $readonly ?>>
                            </div>
                            <div class="col-sm-2">
                              <input type="text" class="form-control" autocomplete="off" value="<?php echo $row->tgl_lahir ?>" placeholder="Tgl Lahir" name="tgl_lahir_penjamin" id="tanggal2" <?= $readonly ?>>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                            <div class="col-sm-4">
                              <input type="text" value="<?php echo $row->alamat_penjamin ?>" class="form-control" placeholder="Alamat" name="alamat_penjamin" id="alamat_penjamin" <?= $readonly ?>>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                            <div class="col-sm-4">
                              <select class="form-control" id="id_pekerjaan" name="id_pekerjaan" <?= $readonly ?>>
                                <option value="<?php echo $row->id_pekerjaan ?>">
                                  <?php
                                  $dt_cust    = $this->m_admin->getByID("ms_pekerjaan", "id_pekerjaan", $row->id_pekerjaan)->row();
                                  if (isset($dt_cust)) {
                                    echo $dt_cust->pekerjaan;
                                  } else {
                                    echo "- choose -";
                                  }
                                  ?>
                                  <?php
                                  $dt = $this->m_admin->kondisiCond("ms_pekerjaan", "id_pekerjaan != '$row->id_pekerjaan'");
                                  foreach ($dt->result() as $val) {
                                    echo "
                                    <option value='$val->id_pekerjaan'>$val->pekerjaan</option>;";
                                  }
                                  ?>
                                </option>
                              </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="No HP" value="<?php echo $row->no_hp ?>" name="no_hp_penjamin" id="no_hp_penjamin" <?= $readonly ?>>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="No KTP" name="no_ktp" value="<?php echo $row->no_ktp ?>" id="no_ktp" minlength="16" maxlength="16" onkeypress="return number_only(event)" <?= $readonly ?>>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Finance Company</label>
                            <div class="col-sm-4">
                              <select class="form-control" id="id_finance_company" name="id_finance_company" <?= $readonly ?>>
                                <option value="<?php echo $row->id_finance_company ?>">
                                  <?php
                                  $dt_cust    = $this->m_admin->getByID("ms_finance_company", "id_finance_company", $row->id_finance_company)->row();
                                  if (isset($dt_cust)) {
                                    echo $dt_cust->finance_company;
                                  } else {
                                    echo "- choose -";
                                  }
                                  ?>
                                  <?php
                                  $dt = $this->m_admin->kondisiCond("ms_finance_company", "id_finance_company != '$row->id_finance_company'");
                                  foreach ($dt->result() as $val) {
                                    echo "
                          <option value='$val->id_finance_company'>$val->finance_company</option>;
                          ";
                                  }
                                  ?>
                                </option>
                              </select>
                            </div>
                          </div>
                          <table class="table table-bordered table-hover myTable1">
                            <thead>
                              <tr>
                                <th>Tipe-Warna</th>
                                <th>Qty</th>
                                <th>Harga Satuan</th>
                                <th>Biaya BBN</th>
                                <th>Nilai Voucher</th>
                                <th>Voucher Tambahan</th>
                                <th>DP Stor</th>
                                <th>Angsuran</th>
                                <th>Tenor (Bulan)</th>
                                <th>Total</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $total = 0;
                              $no = 1;
                              $detail = $this->db->query("SELECT * FROM tr_spk_gc_detail LEFT JOIN ms_tipe_kendaraan ON tr_spk_gc_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                        LEFT JOIN ms_warna ON tr_spk_gc_detail.id_warna = ms_warna.id_warna WHERE no_spk_gc = '$row->no_spk_gc'");
                              foreach ($detail->result() as $rs) {

                              ?>
                                <tr>
                                  <td><?= $rs->tipe_ahm . " - " . $rs->warna ?></td>
                                  <td><?= $rs->qty ?></td>
                                  <td align="right"><?= mata_uang2($rs->harga) ?></td>
                                  <td align="right"><?= mata_uang2($rs->biaya_bbn) ?></td>
                                  <td align="right"><?= mata_uang2($rs->nilai_voucher) ?></td>
                                  <td align="right"><?= mata_uang2($rs->voucher_tambahan) ?></td>
                                  <td align="right"><?= mata_uang2($rs->dp_stor) ?></td>
                                  <td align="right"><?= $rs->angsuran ?></td>
                                  <td align="right"><?= $rs->tenor ?></td>
                                  <td align="right"><?= mata_uang2($rs->total) ?></td>
                                </tr>
                              <?php
                                $no++;
                                $total += $rs->total;
                              }
                              ?>
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="9"></td>
                                <td align="right"><?= mata_uang2($total) ?></td>
                              </tr>
                            </tfoot>
                          </table>
                        </span>




                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-2">
                        </div>
                        <?php if ($readonly == '') : ?>
                          <div class="col-sm-10">
                            <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
                            <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>
                          </div>
                        <?php endif ?>

                      </div><!-- /.box-footer -->
                    </form>
                  </div>
                </div>
              </div>
            </div><!-- /.box -->
          <?php
          } elseif ($set == "insert_demo") {
          ?>
            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/spk_fif">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
                    <form class="form-horizontal" action="dealer/spk_fif/save_demo" method="post" enctype="multipart/form-data">
                      <div class="box-body">
                        <button class="btn btn-block btn-primary btn-flat" disabled> SPK </button> <br>
                        <div class="form-group">
                          <input type="hidden" readonly class="form-control" id="id_spk" readonly placeholder="No SPK" name="no_spk">
                          <!-- <label for="inputEmail3" class="col-sm-2 control-label">No SPK *</label>
                  <div class="col-sm-4">
                  </div> -->
                          <label for="inputEmail3" class="col-sm-2 control-label">Tanggal</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" id="tanggal" readonly placeholder="Tanggal" value="<?php echo date("Y-m-d") ?>" name="tgl_spk">
                          </div>
                        </div>
                        <button class="btn btn-block btn-success btn-flat" disabled> DATA KONSUMEN </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">ID Customer *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="id_customer" onchange="cek_customer()" onpaste="return false;" onkeypress="return false;" id="id_customer" placeholder="ID Customer" required>
                          </div>
                          <div class="col-sm-4">
                            <a class="btn btn-primary btn-flat btn-sm" data-toggle="modal" data-target="#Customermodal" type="button"><i class="fa fa-search"></i> Browse</a>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Sesuai Identitas *</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_konsumen" placeholder="Nama Sesuai Identitas" name="nama_konsumen" required>
                          </div>
                        </div>

                        <!-- <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Upload Foto KTP (Maks 100Kb) *</label>
                  <div class="col-sm-4">
                    <input type="file" class="form-control" placeholder="Upload Foto" name="file_foto" required>                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Upload KK (Maks 500 Kb) *</label>
                  <div class="col-sm-4">
                    <input type="file" class="form-control" placeholder="Upload KK (Maks 500 Kb)" name="file_kk" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili *</label>
                  <div class="col-sm-4">
                    <input type="hidden" readonly name="id_kelurahan" id="id_kelurahan">                      
                    <input required type="text" onpaste="return false" onkeypress="return nihil(event)"  name="kelurahan" data-toggle="modal" placeholder="Kelurahan Domisili" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">                                          
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                    <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan Domisili"  name="kecamatan" required>                                        
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
                </div> -->

                        <button class="btn btn-block btn-danger btn-flat" disabled> DATA KENDARAAN </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Type *</label>
                          <div class="col-sm-4">
                            <input type="hidden" id="warna_mode">
                            <!-- <input type="text" class="form-control" name="id_tipe_kendaraan" readonly id="id_tipe_kendaraan" placeholder="Type">                                                     -->
                            <select class="form-control" name="id_tipe_kendaraan" id="id_tipe_kendaraan" onchange="take_harga()" onclick="getWarna()" required>
                              <?php
                              if (isset($_SESSION['id_tipe'])) {
                                $tipe = $_SESSION['id_tipe'];
                                echo "<option value='$tipe'>";
                                $dt_cust    = $this->m_admin->getByID("ms_tipe_kendaraan", "id_tipe_kendaraan", $tipe)->row();
                                if (isset($dt_cust)) {
                                  echo "$dt_cust->id_tipe_kendaraan | $dt_cust->tipe_ahm";
                                } else {
                                  echo "- choose -";
                                }
                              ?>
                                </option>
                              <?php
                              }
                              if ($dt_tipe->num_rows() > 0) {
                                foreach ($dt_tipe->result() as $val) {
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
                              if (isset($_SESSION['id_warna'])) {
                                $warna = $_SESSION['id_warna'];
                                echo "<option value='$warna'>";
                                $dt_cust    = $this->m_admin->getByID("ms_warna", "id_warna", $warna)->row();
                                if (isset($dt_cust)) {
                                  echo "$dt_cust->id_warna | $dt_cust->warna";
                                } else {
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
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama STNK/BPKB *</label>
                          <div class="col-sm-4">
                            <input id="nama_bpkb" type="text" required class="form-control" placeholder="Nama STNK/BPKB" name="nama_bpkb">
                          </div>
                        </div>
                        <button class="btn btn-block btn-warning btn-flat" disabled> SISTEM PEMBELIAN </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kode PPN</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="kode_ppn">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Faktur Pajak</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="faktur_pajak">
                          </div>
                        </div>

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
                          <div class="form-group">
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
                          <div class="form-group">
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
                            <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">Total Bayar</label>
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
                                foreach ($dt_pekerjaan->result() as $val) {
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
                              <input type="text" class="form-control" placeholder="No KTP Penjamin" name="no_ktp_penjamin" required minlength="16" maxlength="16" onkeypress="return number_only(event)">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Tenor (Bulan)</label>
                            <div class="col-sm-3">
                              <input type="text" class="form-control" placeholder="Tenor (Bulan)" name="tenor" required>
                            </div>
                            <div class="col-sm-1">
                              Bulan
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Foto KTP (Maks 100Kb)*</label>
                            <div class="col-sm-4">
                              <input type="file" class="form-control" name="file_ktp_2" required>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Angsuran</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Angsuran" name="angsuran">
                            </div>
                          </div>
                        </span>




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
          } elseif ($set == "edit") {
            $row = $dt_spk;
            if ($form == 'edit') {
              $readonly = '';
              $mode_edit = 'true';
            }
            if ($form == 'detail') {
              $readonly = 'readonly';
              $mode_edit = 'false';
            }
          ?>
            <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
            <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
            <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
            <script>
              Vue.use(VueNumeric.default);
              $(document).ready(function() {
                $('#id_event').val('<?= $row->id_event ?>').trigger('change');
                getAksesoris();
              })
            </script>
            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/spk_fif<?= isset($out) ? '/outstanding' : '' ?>">
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
                    <form id="form_" class="form-horizontal" action="dealer/spk_fif/update" method="post" enctype="multipart/form-data">
                      <input type="hidden" id="mode_edit" value="<?php echo $mode_edit ?>">
                      <input type="hidden" id="tipe_customer" value="<?= $row->tipe_customer ?>">
                      <div class="box-body">
                        <button class="btn btn-block btn-primary btn-flat" disabled> SPK </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Sales People</label>
                          <div class="col-sm-4">
                            <?php
                            $sales = $this->db->query("SELECT * FROM tr_prospek 
                        JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
                      WHERE id_customer='$row->id_customer' ORDER BY tr_prospek.created_at DESC")->row(); ?>
                            <input type="text" readonly class="form-control" id="sales_people" name="sales_people" value="<?= $sales->nama_lengkap ?>">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">FLP ID</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" id="flp_id" name="flp_id" value="<?= $sales->id_flp_md ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <input type="hidden" readonly class="form-control" value="<?php echo $row->no_spk ?>" id="id_spk" readonly placeholder="No SPK" name="no_spk" required>
                          <input type="hidden" readonly class="form-control" value="<?php echo $row->no_spk ?>" id="id" readonly placeholder="No SPK" name="id" required>

                          <!-- <label for="inputEmail3" class="col-sm-2 control-label">No SPK *</label>
                  <div class="col-sm-4">
                  </div> -->
                          <label for="inputEmail3" class="col-sm-2 control-label">Tanggal</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" value="<?php echo $row->tgl_spk ?>" id="tanggal" readonly placeholder="Tanggal" value="<?php echo date("Y-m-d") ?>" name="tgl_spk" required>
                          </div>
                        </div>
                        <button class="btn btn-block btn-success btn-flat" disabled> DATA KONSUMEN </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">ID Customer *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" value="<?php echo $row->id_customer ?>" onpaste="return false" autocomplete="off" onkeypress="return nihil(event)" name="id_customer" id="id_customer" placeholder="ID Customer" required <?= $readonly ?>>
                          </div>
                          <div class="col-sm-4" v-if="readonly==''">
                            <a class="btn btn-primary btn-flat btn-sm" data-toggle="modal" data-target="#Customermodal" type="button"><i class="fa fa-search"></i> Browse</a>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Sesuai Identitas *</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_konsumen" value="<?php echo $row->nama_konsumen ?>" placeholder="Nama Sesuai Identitas" name="nama_konsumen" required <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tempat/Tgl.Lahir *</label>
                          <div class="col-sm-4">
                            <input type="text" id="tempat_lahir" class="form-control" value="<?php echo $row->tempat_lahir ?>" placeholder="Tempat Lahir" name="tempat_lahir" required <?= $readonly ?>>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control tgl_lahir" onchange="cek_umur()" id="tanggal2" value="<?php echo $row->tgl_lahir ?>" placeholder="Tgl Lahir" name="tgl_lahir" required <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kewarganegaraan *</label>
                          <div class="col-sm-4">
                            <select class="form-control" id="jenis_wn" name="jenis_wn" required <?= $readonly ?>>
                              <option><?php echo $row->jenis_wn ?></option>
                              <option>WNA</option>
                              <option selected>WNI</option>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No KTP/KITAS *</label>
                          <div class="col-sm-4">
                            <input type="text" value="<?php echo $row->no_ktp ?>" id="no_ktp" class="form-control" onkeypress="return number_only(event)" placeholder="No KTP/KITAS" name="no_ktp" minlength="16" maxlength="16" required <?= $readonly ?>>
                          </div>
                        </div>

                        <div class="form-group">

                          <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">No NPWP *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" value="<?php echo $row->npwp ?>" placeholder="No NPWP" id="no_npwp" name="npwp" required <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Upload Foto KTP (Maks 100Kb) *</label>
                          <div class="col-sm-3" v-if="readonly==''">
                            <input type="file" class="form-control" placeholder="Upload Foto" value="<?php echo $row->file_foto ?>" name="file_foto">
                          </div>
                          <div class="col-sm-1">
                            <a class="btn bg-maroon btn-flat btn-sm" data-toggle="modal" data-target="#Ktpmodal" type="button"><i class="fa fa-image"></i> Lihat</a>
                          </div>
                          <div class="col-sm-3" v-if="readonly=='readonly'"></div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Upload KK (Maks 500 Kb) *</label>
                          <div class="col-sm-3" v-if="readonly==''">
                            <input type="file" class="form-control" placeholder="Upload KK (Maks 500 Kb)" value="<?php echo $row->file_kk ?>" name="file_kk">
                          </div>
                          <div class="col-sm-1">
                            <a class="btn bg-maroon btn-flat btn-sm" data-toggle="modal" data-target="#Kkmodal" type="button"><i class="fa fa-image"></i> Lihat</a>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili *</label>
                          <div class="col-sm-4">
                            <?php
                            $dt_cust    = $this->m_admin->getByID("ms_kelurahan", "id_kelurahan", $row->id_kelurahan)->row();
                            if (isset($dt_cust)) {
                              $kel = $dt_cust->kelurahan;
                            } else {
                              $kel = "";
                            }
                            ?>
                            <input type="hidden" value="<?php echo $row->id_kelurahan ?>" readonly name="id_kelurahan" id="id_kelurahan">
                            <input type="text" value="<?php echo $kel ?>" required type="text" onpaste="return false" onkeypress="return nihil(event)" autocomplete="off" name="kelurahan" data-toggle="modal" placeholder="Kelurahan Domisili" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                            <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan Domisili" name="kecamatan">
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
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili *</label>
                          <div class="col-sm-10">
                            <input value="<?php echo $row->alamat ?>" maxlength="100" type="text" class="form-control" placeholder="Alamat Domisili" name="alamat" id="alamat" required <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kodepos *</label>
                          <div class="col-sm-4">
                            <input type="text" value="<?php echo $row->kodepos ?>" class="form-control" placeholder="Kodepos" id="kodepos" name="kodepos" required <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Longitude *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Longitude" name="longitude" id="longitude" value="<?= $row->longitude ?>" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Pada BPKB/STNK *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="nama_bpkb" value="<?= $row->nama_bpkb ?>" <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Latitude *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Latitude" name="latitude" id="latitude" value="<?= $row->latitude ?>" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No. KTP/KITAP Pada BPKB *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="no_ktp_bpkb" value="<?= $row->no_ktp_bpkb ?>" minlength="16" maxlength="16" onkeypress="return number_only(event)" <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">RT *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="RT" name="rt" value="<?= $row->rt ?>" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat KTP/KITAP Pada BPKB *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="alamat_ktp_bpkb" value="<?= $row->alamat_ktp_bpkb ?>" <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">RW *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="RW" name="rw" value="<?= $row->rw ?>" <?= $readonly ?>>
                          </div>
                        </div>
                        <!--  <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Denah Lokasi</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $row->denah_lokasi ?>" placeholder="Latitude,Longitude"  name="denah_lokasi">                                        
                  </div>                  
                </div> -->
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Apakah alamat domisili sama dengan alamat di KTP? *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="tanya" id="tanya" onchange="cek_tanya()" required <?= $readonly ?>>
                              <option><?php echo $row->alamat_sama ?></option>
                              <option>Ya</option>
                              <option>Tidak</option>
                            </select>
                          </div>
                        </div>
                        <span id="tampil_alamat">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Sesuai KTP</label>
                            <div class="col-sm-4">
                              <?php
                              $dt_cust    = $this->m_admin->getByID("ms_kelurahan", "id_kelurahan", $row->id_kelurahan2)->row();
                              if (isset($dt_cust)) {
                                $kel = $dt_cust->kelurahan;
                              } else {
                                $kel = "";
                              }
                              ?>
                              <input type="hidden" value="<?php echo $row->id_kelurahan2 ?>" readonly name="id_kelurahan2" id="id_kelurahan2">
                              <input type="text" value="<?php echo $kel ?>" type="text" onpaste="return false" onkeypress="return nihil(event)" name="kelurahan2" data-toggle="modal" placeholder="Kelurahan Sesuai KTP" data-target="#Kelurahanmodal2" class="form-control" id="kelurahan2" onchange="take_kec2()" <?= $readonly ?>>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Sesuai KTP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_kecamatan2" id="id_kecamatan2">
                              <input type="text" class="form-control" id="kecamatan2" placeholder="Kecamatan Sesuai KTP" readonly name="kecamatan2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Sesuai KTP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_kabupaten2" id="id_kabupaten2">
                              <input type="text" class="form-control" placeholder="Kota/Kabupaten Sesuai KTP" readonly id="kabupaten2" name="kabupaten2">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Sesuai KTP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_provinsi2" id="id_provinsi2">
                              <input type="text" class="form-control" placeholder="Provinsi Sesuai KTP" readonly id="provinsi2" name="provinsi2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kodepos Sesuai KTP</label>
                            <div class="col-sm-4">
                              <input value="<?php echo $row->kodepos2 ?>" type="text" class="form-control" placeholder="Kodepos Sesuai KTP" name="kodepos2" <?= $readonly ?>>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Alamat Sesuai KTP</label>
                            <div class="col-sm-10">
                              <input type="text" value="<?php echo $row->alamat2 ?>" maxlength="100" class="form-control" placeholder="Alamat Sesuai KTP" name="alamat2" <?= $readonly ?>>
                            </div>
                          </div>
                        </span>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Status Rumah *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="status_rumah" <?= $readonly ?> required>
                              <option><?php echo $row->status_rumah ?></option>
                              <option>Milik Sendiri</option>
                              <option>Sewa</option>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Lama Tinggal</label>
                          <div class="col-sm-4">
                            <input value="<?php echo $row->lama_tinggal ?>" type="text" class="form-control" placeholder="Lama Tinggal" name="lama_tinggal" <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                          <div class="col-sm-4">
                            <select class="form-control" id="pekerjaan" name="pekerjaan" <?= $readonly ?> required>
                              <option value="<?php echo $row->pekerjaan ?>">
                                <?php
                                $dt_cust    = $this->m_admin->getByID("ms_pekerjaan", "id_pekerjaan", $row->pekerjaan)->row();
                                if (isset($dt_cust)) {
                                  echo $dt_cust->pekerjaan;
                                } else {
                                  echo "- choose -";
                                }
                                ?>
                              </option>
                              <?php
                              // $dt = $this->m_admin->kondisiCond("ms_pekerjaan", "id_pekerjaan != '$row->pekerjaan'");
                              $dt = $this->db->query("SELECT id_pekerjaan,pekerjaan FROM ms_pekerjaan WHERE id_pekerjaan NOT IN ('9','10') and id_pekerjaan !='$row->pekerjaan' ORDER BY id_pekerjaan ASC ");
                              foreach ($dt->result() as $val) {
                                echo "
                        <option value='$val->id_pekerjaan'>$val->pekerjaan</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Lama Kerja</label>
                          <div class="col-sm-4">
                            <input value="<?php echo $row->lama_kerja ?>" type="text" class="form-control" placeholder="Lama Kerja" name="lama_kerja" <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Jabatan</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" value="<?php echo $row->jabatan ?>" placeholder="Jabatan" name="jabatan" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Tanggungan</label>
                          <div class="col-sm-4">
                            <input type="number" class="form-control" placeholder="1" name="tanggungan" value="<?php echo $row->tanggungan ?>" <?php echo $readonly ?>>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Status Pernikahan *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="status_pernikahan" required>
                            <?php
                            $stat_pernikahan = get_data('ms_status_pernikahan','id_status_pernikahan',$row->status_pernikahan,'status_pernikahan');
                            
                            $this->db->order_by('id_status_pernikahan', 'ASC');
                            $status_pernikahan = $this->db->get('ms_status_pernikahan');
                            if ($status_pernikahan->num_rows() > 0) {
                              
                              echo "<option value='$row->status_pernikahan'>$stat_pernikahan</option>";
                              foreach ($status_pernikahan->result() as $rs) {
                                echo "<option value='$rs->id_status_pernikahan'>$rs->status_pernikahan</option>";
                              }
                            }
                            ?>
                          </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Pendidikan *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="pendidikan" required>
                            <?php
                            $id_pendidikan = get_data('ms_pendidikan','id_pendidikan',$row->pendidikan,'pendidikan');

                            $this->db->where('active', '1');
                            $this->db->order_by('id_pendidikan', 'ASC');
                            $pendidikan = $this->db->get('ms_pendidikan');
                            if ($pendidikan->num_rows() > 0) {
                              
                              echo "<option value='$row->pendidikan'>$id_pendidikan</option>";
                              foreach ($pendidikan->result() as $rs) {
                                echo "<option value='$rs->id_pendidikan'>$rs->pendidikan</option>";
                              }
                            }
                            ?>
                          </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Total Penghasilan</label>
                          <div class="col-sm-4">
                            <input type="number" class="form-control" placeholder="Total Penghasilan" value="<?php echo $row->penghasilan ?>" name="penghasilan" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Pengeluaran Perbulan *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="pengeluaran_bulan" required <?= $readonly ?>>
                              <option value="<?php echo $row->pengeluaran_bulan ?>">
                                <?php
                                $dt_cust    = $this->m_admin->getByID("ms_pengeluaran_bulan", "id_pengeluaran_bulan", $row->pengeluaran_bulan)->row();
                                if (isset($dt_cust)) {
                                  echo $dt_cust->pengeluaran;
                                } else {
                                  echo "- choose -";
                                }
                                ?>
                              </option>
                              <?php
                              $dt = $this->m_admin->kondisiCond("ms_pengeluaran_bulan", "id_pengeluaran_bulan != '$row->pengeluaran_bulan'");
                              foreach ($dt->result() as $val) {
                                echo "
                        <option value='$val->id_pengeluaran_bulan'>$val->pengeluaran</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No HP #1 *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" value="<?php echo $row->no_hp ?>" maxlength="15" placeholder="No HP" id="no_hp" name="no_hp" required <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp #1 *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="status_hp" id="status_nohp" required <?= $readonly ?>>
                              <option value="<?php echo $row->status_hp ?>">
                                <?php
                                $dt_cust    = $this->m_admin->getByID("ms_status_hp", "id_status_hp", $row->status_hp)->row();
                                if (isset($dt_cust)) {
                                  echo $dt_cust->status_hp;
                                } else {
                                  echo "- choose -";
                                }
                                ?>
                              </option>
                              <?php
                              $dt = $this->m_admin->kondisiCond("ms_status_hp", "id_status_hp != '$row->status_hp'");
                              foreach ($dt->result() as $val) {
                                echo "
                        <option value='$val->id_status_hp'>$val->status_hp</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No HP #2</label>
                          <div class="col-sm-4">
                            <input value="<?php echo $row->no_hp_2 ?>" type="text" class="form-control" maxlength="15" placeholder="No HP" id="no_hp2" name="no_hp_2" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp #2</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="status_hp_2" id="status_nohp2" <?= $readonly ?>>
                              <option value="<?php echo $row->status_hp_2 ?>">
                                <?php
                                $dt_cust    = $this->m_admin->getByID("ms_status_hp", "id_status_hp", $row->status_hp_2)->row();
                                if (isset($dt_cust)) {
                                  echo $dt_cust->status_hp;
                                } else {
                                  echo "- choose -";
                                }
                                ?>
                              </option>
                              <?php
                              $dt = $this->m_admin->kondisiCond("ms_status_hp", "id_status_hp != '$row->status_hp_2'");
                              foreach ($dt->result() as $val) {
                                echo "
                        <option value='$val->id_status_hp'>$val->status_hp</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                          <div class="col-sm-4">
                            <input value="<?php echo $row->no_telp ?>" type="text" class="form-control" maxlength="15" placeholder="No Telp" id="no_telp" name="no_telp" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Email *</label>
                          <div class="col-sm-4">
                            <input type="email" class="form-control" value="<?php echo $row->email ?>" maxlength="100" placeholder="Email" id="email" name="email" required <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Refferal ID</label>
                          <div class="col-sm-3">
                            <input type="text" readonly class="form-control" value="<?php echo $row->refferal_id ?>" placeholder="Refferal ID" name="refferal_id" id="refferal_id" <?= $readonly ?>>
                          </div>
                          <div class="col-sm-1" v-if="readonly==''">
                            <button data-toggle="modal" type="button" data-target="#Reffmodal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-search"></i> Browse</button>
                          </div>
                          <div class="col-sm-1" v-if="readonly=='readonly'"></div>
                          <label for="inputEmail3" class="col-sm-2 control-label">RO BD ID</label>
                          <div class="col-sm-3">
                            <input value="<?php echo $row->robd_id ?>" type="text" readonly class="form-control" placeholder="Ro BD ID" name="robd_id" id="robd_id" <?= $readonly ?>>
                          </div>
                          <div class="col-sm-1" v-if="readonly==''">
                            <button style='width:100%' type="button" data-toggle="modal" data-target="#Robdmodal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-search"></i> Browse</button>
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
                            <input type="text" class="form-control" value="<?php echo $row->nama_ibu ?>" placeholder="Nama Gadis Ibu Kandung" name="nama_ibu" required <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir Ibu Kandung *</label>
                          <div class="col-sm-4">
                            <input id="tanggal3" type="text" class="form-control" value="<?php echo $row->tgl_ibu ?>" placeholder="Tgl Lahir Ibu Kandung" name="tgl_ibu" required <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Keterangan" maxlength="200" value="<?php echo $row->keterangan ?>" name="keterangan" <?= $readonly ?>>
                          </div>
                        </div>
                        <button class="btn btn-block btn-danger btn-flat" disabled> DATA KENDARAAN </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Type *</label>
                          <div class="col-sm-4">
                            <input type="hidden" id="warna_mode">
                            <select class="form-control" name="id_tipe_kendaraan" id="id_tipe_kendaraan" onchange="take_harga();getWarna();" required <?= $readonly ?>>
                              <?php
                              $no = 1;
                              foreach ($dt_tipe->result() as $val) {
                                $selected = $val->id_tipe_kendaraan == $row->id_tipe_kendaraan ? 'selected' : '';
                                if (isset($out)) {
                                  $selected = '';
                                  if ($no == 1) {
                                    echo "<option value='' selected>-choose-</option>;";
                                  }
                                }
                                echo "
                                  <option value='$val->id_tipe_kendaraan' $selected>$val->id_tipe_kendaraan | $val->tipe_ahm</option>;
                                  ";
                                $no++;
                              }
                              ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Warna *</label>
                          <div class="col-sm-4">
                            <!-- <input type="text" class="form-control" name="id_warna" readonly id="id_warna" placeholder="Warna">                                                     -->
                            <select class="form-control" name="id_warna" id="id_warna" required onchange="take_harga();get_beli();" onclick="getWarna2()" <?= $readonly ?>>
                              <?php
                              if (isset($_SESSION['id_warna'])) {
                                $warna = $_SESSION['id_warna'];
                                echo "<option value='$warna'>";
                                $dt_cust    = $this->m_admin->getByID("ms_warna", "id_warna", $warna)->row();
                                if (isset($dt_cust)) {
                                  if (isset($out)) {
                                    echo "- choose -";
                                  } else {
                                    echo "$dt_cust->id_warna | $dt_cust->warna";
                                  }
                                } else {
                                  echo "- choose -";
                                }
                              ?>
                                </option>
                              <?php
                              } ?>

                              <?php
                              $no = 1;
                              foreach ($dt_warna->result() as $val) {
                                if ($val->id_warna == $row->id_warna) {
                                  $selected = 'selected';
                                } else {
                                  $selected = '';
                                }
                                if (isset($out)) {
                                  $selected = '';
                                  if ($no == 1) {
                                    echo "<option value='' selected>-choose-</option>;";
                                  }
                                }
                                echo "
                        <option value='$val->id_warna' $selected>$val->id_warna | $val->warna</option>;
                        ";
                                $no++;
                              }
                              ?>
                            </select>
                          </div>
                          <!-- <div class="col-sm-1">
                    <button onclick="take_harga()" type="button">generate</button>
                  </div> -->
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Pengiriman *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control datepicker" placeholder="Tanggal Pengiriman" name="tgl_pengiriman" required value="<?= $row->tgl_pengiriman ?>" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tipe Pembelian *</label>
                          <div class="col-sm-2">
                            <select class="form-control" name="jenis_beli" id="beli" onchange="get_beli()" required <?= $readonly ?>>
                              <option value="">- choose -</option>
                              <option value="Kredit" <?= $row->jenis_beli == 'Kredit' ? 'selected' : '' ?>>Kredit</option>
                              <option value="Cash" <?= $row->jenis_beli == 'Cash' ? 'selected' : '' ?>>Cash</option>
                            </select>
                          </div>
                          <div class="col-sm-2" v-if="readonly==''">
                            <button style='width:100%' class="btn btn-primary btn-flat" type="button" onclick="get_beli()"><i class="fa fa-refresh"></i> Reload Harga</button>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Waktu Pengiriman *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Waktu Pengiriman" name="waktu_pengiriman" value="<?= $row->waktu_pengiriman ?>" required <?= $readonly ?>>
                            <i>Contoh pengisian : 12:50:00</i>
                          </div>
                        </div>
                        <?php
                        $id     = $this->input->get("id");
                        $return = $this->m_admin->detail_individu($id);
                        if (isset($out)) {
                          $return = [
                            'harga_off_road' => 0,
                            'harga' => 0,
                            'ppn' => 0,
                            'bbn' => 0,
                            'harga_on_road' => 0,
                            'voucher_tambahan' => 0,
                            'voucher' => 0,
                            'voucher2' => 0,
                            'total_bayar' => 0,
                            'harga_tunai' => 0,
                            'diskon' => 0,
                          ];
                        }
                        ?>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Harga Pricelist</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="harga_pricelist" id="harga_pricelist" value="<?= $return['harga_off_road'] + $return['bbn'] ?>">
                            <input type="text" class="form-control" value="Rp. <?php echo mata_uang2($return['harga_off_road'] + $return['bbn']) ?>" placeholder="Harga Pricelist" readonly name="harga_pricelist_r" id="harga_pricelist_r" <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Harga</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="harga" id="harga" value="<?= $return['harga'] ?>">
                            <input type="text" class="form-control" value="Rp. <?php echo mata_uang2($return['harga']) ?>" placeholder="Harga" readonly name="harga_r" id="harga_r" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">PPN</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="ppn" id="ppn" value="<?= $return['ppn'] ?>">
                            <input type="text" class="form-control" value="Rp. <?php echo mata_uang2($return['ppn']) ?>" placeholder="PPN" readonly name="ppn_r" id="ppn_r" <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Harga Off The Road</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="harga_off" id="harga_off" value="<?php echo $return['harga_off_road'] ?>">
                            <input type="text" class="form-control" placeholder="Harga Off The Road" value="Rp. <?php echo mata_uang2($return['harga_off_road']) ?>" readonly name="harga_off_r" id="harga_off_r" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Biaya BBN</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="biaya_bbn" id="biaya_bbn" value="<?php echo $return['bbn'] ?>">
                            <input type="text" class="form-control" placeholder="Biaya BBN" value="Rp. <?php echo mata_uang2($return['bbn']) ?>" readonly name="biaya_bbn_r" id="biaya_bbn_r" <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Harga On The Road</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="harga_on" id="harga_on" value="<?php echo $return['harga_on_road'] ?>">
                            <input type="text" class="form-control" placeholder="Harga On The Road" value="Rp. <?php echo mata_uang2($return['harga_on_road']) ?>" readonly name="harga_on_r" id="harga_on_r" <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="  col-sm-2 control-label">Diskon</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly name="diskon" id="diskon" value="<?php echo $return['diskon']  ?>" <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">Tanda Jadi *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="tanda_jadi" id="tanda_jadi" value="<?= $row->tanda_jadi ?>" <?= $readonly ?>>
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
                                  <select name="id_event" id="id_event" onchange="getEvent()" class="form-control select2" <?= $readonly ?>>
                                    <option value="">--choose-</option>
                                    <?php foreach ($event->result() as $rs) :
                                      $selected = isset($row) ? $rs->id_event == $row->id_event ? 'selected' : '' : '';
                                    ?>
                                      <option value="<?= $rs->id_event ?>" <?= $selected ?> data-nama_event="<?= $rs->kode_event . ' | ' . $rs->nama_event ?>"><?= $rs->nama_event ?></option>
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
                              var nama_event = $("#id_event").select2().find(":selected").data("nama_event");
                              $('#nama_event').val(nama_event);

                            }
                          </script>
                        </div>
                        <button class="btn btn-block btn-warning btn-flat" disabled> SISTEM PEMBELIAN </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kode PPN</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="kode_ppn" value='<?= $row->kode_ppn ?>'>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Faktur Pajak</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="faktur_pajak" value='<?= $row->faktur_pajak ?>'>
                          </div>
                        </div>
                        <span id="lbl_cash" v-if="tipe_pembelian=='Cash'">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">On/Off The Road</label>
                            <div class="col-sm-4">
                              <select class="form-control" name="the_road" id="the_road" onchange="get_on()" <?= $readonly ?>>
                                <?php
                                $sel_off = $row->the_road == 'Off The Road' ? 'selected' : '';
                                $sel_on = $row->the_road == 'On The Road' ? 'selected' : '';
                                ?>
                                <option <?= $sel_off ?>>Off The Road</option>
                                <option <?= $sel_on ?>>On The Road</option>
                              </select>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Harga Tunai</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="harga_tunai" value="<?php echo $row->harga_tunai ?>" id="harga_tunai">
                              <input type="text" class="form-control" value="Rp. <?php echo mata_uang2($row->harga_tunai) ?>" placeholder="Harga Tunai" readonly name="harga_tunai_r" id="harga_tunai_r" <?= $readonly ?>>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Program</label>
                            <div class="col-sm-4">
                              <select class="form-control" name="program_umum" onchange="cek_program_tambahan()" id="program_umum" <?= $readonly ?>>
                              </select>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label" id="nilai_voucher_lbl">Nilai Voucher</label>
                            <div class="col-sm-4">
                              <input type="text" value="<?php echo $row->voucher_1 ?>" class="form-control" readonly id="nilai_voucher" placeholder="Nilai Voucher" name="nilai_voucher">
                              <input type="hidden" class="form-control" readonly id="voucher_1" placeholder="Nilai Voucher" name="voucher_1" value="<?php echo $row->voucher_1 ?>" <?= $readonly ?>>
                            </div>
                          </div>
                          <div class="form-group">
                            <span id="program_gabungan_lbl">
                              <label for="inputEmail3" class="col-sm-2 control-label">Program Gabungan</label>
                              <div class="col-sm-4">
                                <select class="form-control" name="program_gabungan" id="program_gabungan" onchange="getVoucherGabungan()">
                                </select>
                              </div>
                            </span>
                            <label for="inputEmail3" class="col-sm-2 control-label">Voucher Tambahan</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" value="<?php echo $row->voucher_tambahan_1 ?>" id="voucher_tambahan_1" placeholder="Voucher Tambahan" name="voucher_tambahan_1" <?= $readonly ?>>
                            </div>
                          </div>
                          <div class="form-group">
                            <div id="div_jenis_barang_cash" style="display: none">
                              <label for="inputEmail3" class="col-sm-2 control-label">Jenis Barang</label>
                              <div class="col-sm-4">
                                <input type="text" class="form-control" id="jenis_barang_cash" name="jenis_barang_cash" readonly>
                              </div>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Total Bayar</label>
                            <div class="col-sm-4">
                              <input type="hidden" id="total_bayar" name="total_bayar" value="<?php echo $return['total_bayar'] ?>">
                              <input type="text" value="Rp. <?php echo mata_uang2($return['total_bayar']) ?>" class="form-control" id="total_bayar_r" readonly placeholder="Total Bayar" name="total_bayar_r" <?= $readonly ?>>
                            </div>
                          </div>
                        </span>
                        <span id="lbl_kredit" v-if="tipe_pembelian=='Kredit'">
                          <input type="hidden" name="harga_tunai" id="harga_tunai" value="<?= $row->harga_tunai ?>">
                          <input type="hidden" name="the_road" value="On The Road">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-12">Data Penjamin</label>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Nama *</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Nama Penjamin" value="<?php echo $row->nama_penjamin ?>" name="nama_penjamin" required <?= $readonly ?>>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Finance Company *</label>
                            <div class="col-sm-4">
                              <select class="form-control select2" name="id_finance_company" required <?= $readonly ?>>
                                <!-- <option><?php echo $row->id_finance_company ?></option>                       -->
                                <?php
                                echo '<option>- Choose -</option>';
                                foreach ($dt_finance->result() as $isi) {
                                  $select = $isi->id_finance_company == $row->id_finance_company ? 'selected' : '';
                                  echo "<option value='$isi->id_finance_company' $select>$isi->finance_company</option>";
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Hub. dg Penjamin *</label>
                            <div class="col-sm-4">
                              <select class="form-control" name="hub_penjamin" required <?= $readonly ?>>
                                <option><?php echo $row->hub_penjamin ?></option>
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
                              <select class="form-control" name="program_umum" id="program_umum" onchange="cek_program_tambahan()" <?= $readonly ?>>
                                <!-- <option><?php
                                              if (isset($row->voucher)) {
                                                echo $row->voucher;
                                              }
                                              ?></option> -->
                                <?php
                                $tgl = date("Y-m-d");
                                // $cek = $this->db->query("SELECT * FROM tr_sales_program WHERE '$tgl' BETWEEN periode_awal AND periode_akhir AND (jenis_bayar = 'Kredit' OR jenis_bayar = 'Cash & Kredit')");
                                $cek = $this->db->query("SELECT * FROM tr_sales_program WHERE '$tgl' BETWEEN periode_awal AND periode_akhir");
                                // echo '<option>-choose-</option>';
                                foreach ($cek->result() as $isi) {
                                  $select = $isi->id_sales_program == $row->program_umum ? 'selected' : '';
                                  // echo "<option value='$isi->id_sales_program' $select>$isi->id_prdogram_md</option>";
                                }
                                ?>
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
                                <input value="<?php echo $row->alamat_penjamin ?>" type="text" id="alamat_penjamin" class="form-control" placeholder="Alamat Penjamin" name="alamat_penjamin" <?= $readonly ?>>
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
                            <label for="inputEmail3" class="col-sm-2 control-label">No HP *</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" value="<?php echo $row->no_hp_penjamin ?>" placeholder="No HP" name="no_hp_penjamin" required <?= $readonly ?>>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label" id="nilai_voucher2_lbl">Nilai Voucher</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" value="<?php echo $row->voucher_2 ?>" readonly placeholder="Nilai Voucher" onchange="get_total_ck()" name="nilai_voucher2" id="nilai_voucher2" <?= $readonly ?>>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Tempat/Tgl Lahir *</label>
                            <div class="col-sm-2">
                              <input type="text" class="form-control" value="<?php echo $row->tempat_lahir_penjamin ?>" placeholder="Tempat Lahir" name="tempat_lahir_penjamin" <?= $readonly ?>>
                            </div>
                            <div class="col-sm-2">
                              <input type="text" id="tanggal4" class="form-control" name="tgl_lahir_penjamin" value="<?php echo $row->tgl_lahir_penjamin ?>" placeholder="Tgl Lahir Penjamin" <?= $readonly ?>>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Voucher Tambahan</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" value="<?php echo $row->voucher_tambahan_2 ?>" placeholder="Voucher Tambahan" name="voucher_tambahan_2" onkeyup="get_total_ck()" id="voucher_tambahan_2" value="0" <?= $readonly ?>>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                            <div class="col-sm-4">
                              <select class="form-control" name="pekerjaan_penjamin" <?= $readonly ?>>
                                <option value="<?php echo $row->pekerjaan_penjamin ?>">
                                  <?php
                                  $dt_cust    = $this->m_admin->getByID("ms_pekerjaan", "id_pekerjaan", $row->pekerjaan_penjamin)->row();
                                  if (isset($dt_cust)) {
                                    echo $dt_cust->pekerjaan;
                                  } else {
                                    echo "- choose -";
                                  }
                                  ?>
                                </option>
                                <?php
                                $dt = $this->m_admin->kondisiCond("ms_pekerjaan", "id_pekerjaan != '$row->pekerjaan_penjamin'");
                                foreach ($dt->result() as $val) {
                                  echo "
                        <option value='$val->id_pekerjaan'>$val->pekerjaan</option>;
                        ";
                                }
                                ?>
                              </select>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Down Payment Gross *</label>
                            <div class="col-sm-4">
                              <input type="text" value="<?php echo $row->uang_muka ?>" class="form-control" placeholder="Down Payment Gross" id="uang_muka" onchange="get_total_ck()" name="uang_muka" readonly required <?= $readonly ?>>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Penghasilan</label>
                            <div class="col-sm-4">
                              <input type="number" class="form-control" value="<?php echo $row->penghasilan_penjamin ?>" placeholder="Penghasilan Penjamin" name="penghasilan_penjamin" <?= $readonly ?>>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Down Payment Setor *</label>
                            <div class="col-sm-4">
                              <input readonly id="dp_setor" value="<?php echo $row->dp_stor ?>" type="text" class="form-control" placeholder="DP Setor" name="dp_setor_s" required>
                              <input hidden value="<?php echo $row->dp_stor ?>" type="hidden" class="form-control" placeholder="DP Setor" name="dp_stor" id="dp_stor">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">No KTP *</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" value="<?php echo $row->no_ktp_penjamin ?>" placeholder="No KTP Penjamin" name="no_ktp_penjamin" required minlength="16" maxlength="16" onkeypress="return number_only(event)" <?= $readonly ?>>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Tenor (Bulan)*</label>
                            <div class="col-sm-3">
                              <input type="text" class="form-control" placeholder="Tenor (Bulan)" value="<?php echo $row->tenor ?>" name="tenor" readonly required>
                            </div>
                            <div class="col-sm-1">
                              Bulan
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Foto KTP (Maks 100Kb) *</label>
                            <div class="col-sm-3" v-if="readonly==''">
                              <input type="file" class="form-control" name="file_ktp_2" id="file_ktp_2" onchange="cekFileUpload('file_ktp_2')" required>
                            </div>
                            <div class="col-sm-1">
                              <a class="btn bg-maroon btn-flat btn-sm" data-toggle="modal" data-target="#Ktpmodal2" type="button"><i class="fa fa-image"></i> Lihat</a>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Angsuran *</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" value="<?php echo $row->angsuran ?>" placeholder="Angsuran" name="angsuran" readonly required>
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
                          <label for="inputEmail3" class="col-sm-2 control-label">No. KK *</label>
                          <div class="col-sm-4">
                            <input type="text" id="no_kk" class="form-control" minlength="16" maxlength="16" onkeypress="return number_only(event)" placeholder="No KK" name="no_kk" value="<?= $row->no_kk ?>" required <?= $readonly ?>>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat Kartu Keluarga *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Alamat Kartu Keluarga" name="alamat_kk" value="<?= $row->alamat_kk ?>" required <?= $readonly ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan KK *</label>
                          <div class="col-sm-4">
                            <input type="hidden" readonly name="id_kelurahan_kk" id="id_kelurahan_kk" value="<?= $row->id_kelurahan_kk ?>">
                            <input required type="text" onpaste="return false" onkeypress="return nihil(event)" name="kelurahan" placeholder="Kelurahan KK" class="form-control" id="kelurahan_kk" autocomplete="off" onclick="showModalKelurahan('kk')" value="<?= $row->kelurahan_kk ?>">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan KK</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="kecamatan_kk" placeholder="Kecamatan KK" required value="<?= $row->kecamatan_kk ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten KK</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten KK" id="kabupaten_kk" required value="<?= $row->kabupaten_kk ?>">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Provinsi KK</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly placeholder="Provinsi KK" id="provinsi_kk" required value="<?= $row->provinsi_kk ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kode POS KK *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" onkeypress="return number_only(event)" placeholder="Kode POS KK" id="kode_pos_kk" name='kode_pos_kk' required value="<?= $row->kode_pos_kk ?>">
                          </div>
                        </div>
                        <?php /*
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Anggota Kartu Keluarga</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Anggota Kartu Keluarga" v-model="anggota.anggota" <?= $readonly ?>>
                          </div>
                          <div class="col-sm-1" v-if="readonly==''">
                            <button type="button" @click.prevent="addAnggota" class="btn btn-primary btn-flat btn-sm"><i class="fa fa-plus"></i></button>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-4">
                            <table class="table">
                              <tr>
                                <td><b>No.</b></td>
                                <td><b>List Anggota</b></td>
                                <td><b>Aksi</b></td>
                              </tr>
                              <tr v-for="(agt, index) of anggota_">
                                <td>{{index+1}}. </td>
                                <td><input type="text" name="anggota_kk[]" class="form-control" <?= $readonly ?> v-model="agt.anggota"></td>
                                <td>
                                  <button v-if="readonly==''" type="button" @click.prevent="delAnggota(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </td>
                              </tr>
                            </table>
                          </div>
                        </div>
*/ ?>
                        <button class="btn btn-block btn-primary btn-flat" disabled> DOKUMEN PENDUKUNG </button><br>
                        <div class="form-group">
                          <div class="col-md-12">
                            <table class="table">
                              <tr>
                                <td>File</td>
                                <td>Nama File</td>
                                <td v-if="readonly==''" align="right"><button type="button" @click.prevent="addFile" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i></button></td>
                              </tr>
                              <tr v-for="(fl, index) of file_pendukung_">
                                <td><input type="file" class="form-control" name="file_pendukung[]"> </td>
                                <td>
                                  <input type="text" class="form-control" name="nama_file[]" v-model="fl.nama_file">
                                  <input type="hidden" class="form-control" name="file[]" v-model="fl.file">
                                </td>
                                <td align="right" v-if="readonly==''"> <button type="button" @click.prevent="delFile(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button></td>
                              </tr>
                            </table>
                          </div>
                        </div>

                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-12" align='center' v-if="readonly==''">
                          <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
                          <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>
                        </div>
                      </div><!-- /.box-footer -->
                    </form>
                  </div>
                </div>
              </div>
            </div><!-- /.box -->
            <?php
            $data['data'] = ['kelurahan'];
            $this->load->view('dealer/h2_api', $data);
            ?>
            <div class="modal fade" id="Ktpmodal">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    View Image
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                    <img src="assets/panel/files/<?php echo isset($row->file_foto) ? $row->file_foto : ''; ?>" width="80%">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="Kkmodal">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    View Image
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                    <img src="assets/panel/files/<?php echo isset($row->file_kk) ? $row->file_kk : ''; ?>" width="80%">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="Ktpmodal2">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    View Image
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                    <img src="assets/panel/files/<?php echo isset($row->file_ktp_2) ? $row->file_ktp_2 : ''; ?>" width="80%">
                  </div>
                </div>
              </div>
            </div>
            <script>
              var kelurahan_untuk = '';

              function pilihKelurahan(params) {
                if (kelurahan_untuk === 'kk') {
                  $("#id_kelurahan_kk").val(params.id_kelurahan)
                  $("#kelurahan_kk").val(params.kelurahan)
                  $("#kecamatan_kk").val(params.kecamatan)
                  $("#kabupaten_kk").val(params.kabupaten)
                  $("#provinsi_kk").val(params.provinsi)
                  $("#kode_pos_kk").val(params.kode_pos)
                }
                console.log(params);
              }
              $(document).ready(function() {
                form_.addFile();
                var file_ktp_2 = '<?= $row->file_ktp_2 ?>';
                if (file_ktp_2 == '' || file_ktp_2 == null) {
                  $('#file_ktp_2').attr('required', true);
                } else {
                  $('#file_ktp_2').attr('required', false);
                }
              })
              var form_ = new Vue({
                el: '#form_',
                data: {
                  ksu_: <?= isset($ksu_) ? json_encode($ksu_) : '[]' ?>,
                  id_tipe_kendaraan: '',
                  tipe_pembelian: '<?= $row->jenis_beli ?>',
                  anggota: {
                    anggota: ''
                  },
                  file_pendukung: {
                    file: '',
                    nama_file: ''
                  },
                  readonly: '<?= $readonly ?>',
                  anggota_: <?= isset($anggota_) ? json_encode($anggota_) : '[]' ?>,
                  file_pendukung_: <?= isset($file_pendukung_) ? json_encode($file_pendukung_) : '[]' ?>,
                },
                methods: {
                  clearAnggota: function() {
                    this.anggota = {
                      anggota: ''
                    };
                  },
                  addAnggota: function() {
                    // if (this.anggota_.length > 0) {
                    //   for (dl of this.dealers) {
                    //     if (dl.id_dealer === this.dealer.id_dealer) {
                    //         alert("Dealer Sudah Dipilih !");
                    //         this.clearDealers();
                    //         return;
                    //     }
                    //   }
                    // }
                    if (this.anggota.anggota == '') {
                      alert('Tentukan nama anggota terlebih dahulu !');
                      return false;
                    }
                    this.anggota_.push(this.anggota);
                    this.clearAnggota();
                  },
                  delAnggota: function(index) {
                    this.anggota_.splice(index, 1);
                  },
                  addFile: function() {
                    this.file_pendukung_.push(this.file_pendukung);
                    // console.log(this.file_pendukung_)
                    this.clearFile();
                  },
                  clearFile: function() {
                    this.file_pendukung = {
                      file: '',
                      nama_file: ''
                    }
                  },
                  delFile: function(index) {
                    this.file_pendukung_.splice(index, 1);
                  },

                }
              });

              function cekFileUpload(id) {
                var file_size = $('#' + id)[0].files[0].size;
                var val = $('#' + id).val().toLowerCase(),
                  regex = new RegExp("(.*?)\.(jpg|jpeg|png)$");
                var pesan_size = '';
                var pesan_type = '';
                var maks_upl = 153600;
                if (!(regex.test(val))) {
                  $('#' + id).val('');
                  if (id == 'file_ktp') var pesan_size = 'Format file KTP yg diupload tidak sesuai, tipe file yg harus diupload adalah (*.jpg, *.jpeg, *.png) !';
                  if (id == 'file_ktp_2') var pesan_size = 'Format file KTP Penjamin yg diupload tidak sesuai, tipe file yg harus diupload adalah (*.jpg, *.jpeg, *.png) !';
                  if (id == 'file_kk') var pesan_size = 'Format file KK yg diupload tidak sesuai, tipe file yg harus diupload adalah (*.jpg, *.jpeg, *.png) !';
                }
                if (id == 'file_kk') {
                  maks_upl = 512000;
                }
                if (file_size > maks_upl) {
                  $('#' + id).val('');
                  if (id == 'file_ktp') var pesan_type = 'Ukuran file KTP yg diupload terlalu besar !';
                  if (id == 'file_ktp_2') var pesan_type = 'Ukuran file KTP Penjamin yg diupload terlalu besar !';
                  if (id == 'file_kk') var pesan_type = 'Ukuran file KK yg diupload terlalu besar !';
                }
                if (pesan_size != '' && pesan_type != '') {
                  toastr_error(pesan_size + ', Serta ' + pesan_type);
                  return false;
                } else {
                  if (pesan_type != '') {
                    toastr_error(pesan_type);
                    return false;
                  }
                  if (pesan_size != '') {
                    toastr_error(pesan_size);
                    return false;
                  }
                }
              }
              $('#submitBtn').click(function() {
                $('#form_').validate({
                  rules: {
                    'checkbox': {
                      required: true
                    }
                  },
                  highlight: function(input) {
                    $(input).parents('.form-group').addClass('has-error');
                  },
                  unhighlight: function(input) {
                    $(input).parents('.form-group').removeClass('has-error');
                  }
                })

                if ($('#form_').valid()) // check if form is valid
                {
                  // var totNominal = $('#totNominal').text();
                  // if (totNominal==0) {
                  //   alert('Detail belum dipilih !');
                  //   return false;
                  // }
                  let harga = parseInt($('#harga').val());
                  if (harga === 0 || harga === '') {
                    toastr_error('Pastikan harga untuk unit yang dipilih telah ditentukan !');
                    return false;
                  }

                  jenis_beli = $("#beli").val();
                  if (jenis_beli == 'Cash') {
                    voucher_1 = parseInt($('#voucher_2').val());
                    let id_program_md = $('#lbl_cash #program_umum').val();
                    if (id_program_md === '' && voucher_1 > 0) {
                      toastr_error('Sales program tidak terpilih, tetapi voucher terisi !');
                      return false;
                    }
                  } else if (jenis_beli == 'Kredit') {
                    voucher_2 = parseInt($('#voucher_1').val());
                    let id_program_md = $('#lbl_kredit #program_umum').val();
                    if (id_program_md === '' && voucher_2 > 0) {
                      toastr_error('Sales program tidak terpilih, tetapi voucher terisi !');
                      return false;
                    }
                  }

                  $('#submitBtn').attr('disabled', true);
                  // $('#form_').submit();
                  var response = confirm("Apakah anda yakin ingin melakukan perubahan data ini ?. Perubahan ini akan membentuk order survey yang baru !!");
                  if (response == true) {
                    $('#submitBtn').attr('disabled', true);
                    $("#form_").submit();
                  }
                } else {
                  alert('Silahkan isi field required !')
                }
              })
            </script>
          <?php
          } elseif ($set == "view") {
          ?>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/spk_fif/add">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
                  </a>
                  <a href="dealer/spk_fif/history">
                    <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History SPK</button>
                  </a>
                  <a href="dealer/spk_fif/outstanding">
                    <button class="btn btn-info btn-flat margin"><i class="fa fa-refresh"></i> Outstanding SPK</button>
                  </a>
                  <a href="dealer/spk_fif/gc">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn btn-warning btn-flat margin"><i class="fa fa-users"></i> Grup Customer</button>
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
                      <th>No SPK</th>
                      <th>Nama Konsumen</th>
                      <th>Alamat</th>
                      <th>Tipe</th>
                      <th>Warna</th>
                      <th>No KTP</th>
                      <th>Status</th>
                      <th width="15%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($dt_spk->result() as $row) {
                      $edit         = $this->m_admin->set_tombol($id_menu, $group, 'edit');
                      $delete       = $this->m_admin->set_tombol($id_menu, $group, 'delete');
                      $approval     = $this->m_admin->set_tombol($id_menu, $group, 'approval');
                      $print        = $this->m_admin->set_tombol($id_menu, $group, 'print');
                      $status       = '';
                      $tombol_cetak = '';
                      $tombol       = '';

                      $btn_approve = "<a data-toggle='tooltip' $approval onclick=\"return confirm('Are you sure to approve this data?')\" title='Approve' class='btn btn-success btn-xs btn-flat' href='dealer/spk_fif/approve?id=$row->no_spk'><i class='fa fa-check'></i> Approve</a>";
                      $btn_reject = "<a data-toggle='tooltip' $approval onclick=\"return confirm('Are you sure to reject this data?')\" title='Reject' class='btn btn-danger btn-xs btn-flat' href='dealer/spk_fif/reject?id=$row->no_spk'><i class='fa fa-close'></i> Reject</a>";
                      $btn_edit = "<a data-toggle='tooltip' $edit title='Edit' href='dealer/spk_fif/edit?id=$row->no_spk'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>";
                      $btn_cancel = "<a data-toggle='tooltip' title='Cancel' class='btn btn-danger btn-xs btn-flat' href='dealer/spk_fif/cancel?id=$row->no_spk'><i class='fa fa-close'></i> Cancel</a>";
                      $btn_cetak = "<a  data-toggle='tooltip' title='Cetak' target='_blank' href='dealer/spk_fif/cetak?id=$row->no_spk'>
                        <button $print class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak SPK</button>
                      </a> ";
                      if ($row->status_spk == 'canceled') {
                        $status = "<span class='label label-danger'>Canceled</span>";
                      }
                      if ($row->status_spk == 'input') {
                        $status = "<span class='label label-warning'>$row->status_spk</span>";
                        $tombol = $btn_approve . ' ' . $btn_reject . ' ' . $btn_edit . ' ' . $btn_cancel;
                      } elseif ($row->status_spk == 'booking') {
                        $status = "<span class='label label-warning'>$row->status_spk</span>";
                        $tombol = $btn_approve . ' ' . $btn_reject . ' ' . $btn_edit . ' ' . $btn_cancel;
                      } elseif ($row->status_spk == 'rejected') {
                        $status = "<span class='label label-danger'>$row->status_spk</span>";
                        $tombol = "";
                      } elseif ($row->status_spk == 'approved') {
                        $status = "<span class='label label-success'>$row->status_spk</span>";
                        $tombol = $btn_cetak . ' ' . $btn_edit . ' ' . $btn_cancel;
                      } elseif ($row->status_spk == 'paid') {
                        $status = "<span class='label label-success'>$row->status_spk</span>";
                      } elseif ($row->status_spk == 'closed') {
                        $status = "<span class='label label-danger'>Cancel</span>";
                      }
                      $cek_s = $this->db->query("SELECT * FROM tr_hasil_survey WHERE no_spk = '$row->no_spk'");
                      if ($cek_s->num_rows() > 0) {
                        $rr = $cek_s->row();
                        $status_s = $rr->status_approval;
                        if ($status_s == 'rejected' and $row->status_spk!='rejected') {
                          $tombol = "<a data-toggle='tooltip' title='Edit' href='dealer/spk_fif/edit?id=$row->no_spk'><button $edit class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>";
                          $tombol .= ' '. $btn_reject . ' ' . $btn_cancel ;
      }
                      }

                      $prospek = $this->m_admin->getByID("tr_prospek", "id_customer", $row->id_customer);
                      if ($prospek->num_rows() > 0) {
                        $rt = $prospek->row();
                        $nama = $rt->nama_konsumen;
                      } else {
                        $nama = "";
                      }
                      $tipe = $this->m_admin->getByID("ms_tipe_kendaraan", "id_tipe_kendaraan", $row->id_tipe_kendaraan);
                      if ($tipe->num_rows() > 0) {
                        $rs = $tipe->row();
                        $ahm = $rs->tipe_ahm;
                      } else {
                        $ahm = "";
                      }
                      $warna = $this->m_admin->getByID("ms_warna", "id_warna", $row->id_warna);
                      if ($warna->num_rows() > 0) {
                        $rw = $warna->row();
                        $war = $rw->warna;
                      } else {
                        $war = "";
                      }
                      // $cek_so = $this->m_admin->getByID("tr_sales_order","no_spk",$row->no_spk);
                      // if($cek_so->num_rows() == 0){
                      echo "
              <tr>
                <td>$no</td>
                <td><a href='" . base_url('dealer/spk_fif/detail?id=') . "$row->no_spk'>$row->no_spk</a></td>
                <td>$row->nama_konsumen</td>
                <td>$row->alamat</td>              
                <td>$ahm</td>
                <td>$war</td>            
                <td>$row->no_ktp</td>         
                <td>$status</td>         
                <td align='center'>";
                      echo $tombol;
                    ?>
                      </td>
                      </tr>
                    <?php
                      $no++;
                      // }
                    }
                    ?>
                  </tbody>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
            <script>
              function closePrompt(no_spk) {
                var alasan_close = prompt("Alasan melakukan close untuk No SPK : " + no_spk);
                if (alasan_close != null || alasan_close == "") {
                  window.location = '<?= base_url("dealer/spk_fif/close?id=") ?>' + no_spk + '&alasan_close=' + alasan_close;
                  return false;
                }
                return false
              }
            </script>

          <?php
          } elseif ($set == "outstanding") {
          ?>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/spk_fif">
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
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                      <th width="5%">No</th>
                      <th>No SPK</th>
                      <th>Nama Konsumen</th>
                      <th>Alamat</th>
                      <th>Tipe</th>
                      <th>Warna</th>
                      <th>No KTP</th>
                      <th>Status</th>
                      <th width="15%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($dt_spk->result() as $row) {
                      $edit = $this->m_admin->set_tombol($id_menu, $group, 'edit');
                      $delete = $this->m_admin->set_tombol($id_menu, $group, 'delete');
                      $approval = $this->m_admin->set_tombol($id_menu, $group, 'approval');
                      $print = $this->m_admin->set_tombol($id_menu, $group, 'print');
                      $status = '';
                      $tombol_cetak = '';
                      $tombol = '';
                      $tombol2 = '';
                      $tombol3 = '';
                      $tombol4 = '';
                      $tombol5 = '';
                      if ($row->status_spk == 'canceled') {
                        $status = "<span class='label label-danger'>Canceled</span>";
                      }
                      if ($row->status_spk == 'input') {
                        $status = "<span class='label label-warning'>$row->status_spk</span>";
                        $tombol = "<a data-toggle='tooltip' $approval onclick=\"return confirm('Are you sure to approve this data?')\" title='Approve' class='btn btn-success btn-xs btn-flat' href='dealer/spk_fif/approve?id=$row->no_spk'><i class='fa fa-check'></i> Approve</a>";
                        $tombol2 = "<a data-toggle='tooltip' $approval onclick=\"return confirm('Are you sure to reject this data?')\" title='Reject' class='btn btn-danger btn-xs btn-flat' href='dealer/spk_fif/reject?id=$row->no_spk'><i class='fa fa-close'></i> Reject</a>";
                        $tombol3 = "<a data-toggle='tooltip' $edit title='Edit' href='dealer/spk_fif/edit?id=$row->no_spk&set=outstanding'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>";
                        $tombol4 = '';
                        $tombol5 = "<a data-toggle='tooltip' title='Cancel' class='btn btn-danger btn-xs btn-flat' href='dealer/spk_fif/cancel?id=$row->no_spk'><i class='fa fa-close'></i> Cancel</a>";
                        $tombol_cetak = "<a  data-toggle='tooltip' title='Cetak' target='_blank' href='dealer/spk_fif/cetak?id=$row->no_spk'>
                        <button $print class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak SPK</button>
                      </a> ";
                      } elseif ($row->status_spk == 'booking') {
                        $status = "<span class='label label-warning'>$row->status_spk</span>";
                        $tombol = "<a data-toggle='tooltip' $approval onclick=\"return confirm('Are you sure to approve this data?')\" title='Approve' class='btn btn-success btn-xs btn-flat' href='dealer/spk_fif/approve?id=$row->no_spk'><i class='fa fa-check'></i> Approve</a>";
                        $tombol2 = "<a data-toggle='tooltip' $approval onclick=\"return confirm('Are you sure to reject this data?')\" title='Reject' class='btn btn-danger btn-xs btn-flat' href='dealer/spk_fif/reject?id=$row->no_spk'><i class='fa fa-close'></i> Reject</a>";
                        $tombol3 = "<a data-toggle='tooltip' $edit title='Edit' href='dealer/spk_fif/edit?id=$row->no_spk&set=outstanding'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>";
                        $tombol4 = '';
                        $tombol5 = "<a data-toggle='tooltip' title='Cancel' class='btn btn-danger btn-xs btn-flat' href='dealer/spk_fif/cancel?id=$row->no_spk'><i class='fa fa-close'></i> Cancel</a>";
                        $tombol_cetak = "<a  data-toggle='tooltip' title='Cetak' target='_blank' href='dealer/spk_fif/cetak?id=$row->no_spk'>
                        <button $print class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak SPK</button>
                      </a> ";
                      } elseif ($row->status_spk == 'rejected') {
                        $status = "<span class='label label-danger'>$row->status_spk</span>";
                        $tombol = "<a data-toggle='tooltip' $approval onclick=\"return confirm('Are you sure to approve this data?')\" title='Approve' class='btn btn-success btn-xs btn-flat' href='dealer/spk_fif/approve?id=$row->no_spk'><i class='fa fa-check'></i> Approve</a>";
                        $tombol3 = "<a data-toggle='tooltip' $edit title='Edit' href='dealer/spk_fif/edit?id=$row->no_spk&set=outstanding'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>";
                        $tombol4 = '';
                        $tombol5 = "<a data-toggle='tooltip' title='Cancel' class='btn btn-danger btn-xs btn-flat' href='dealer/spk_fif/cancel?id=$row->no_spk'><i class='fa fa-close'></i> Cancel</a>";
                        $tombol_cetak = "<a  data-toggle='tooltip' title='Cetak' target='_blank' href='dealer/spk_fif/cetak?id=$row->no_spk'>
                        <button $print class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak SPK</button>
                      </a> ";
                      } elseif ($row->status_spk == 'approved') {
                        $status = "<span class='label label-success'>$row->status_spk</span>";
                        $tombol2 = "<a data-toggle='tooltip' $approval onclick=\"return confirm('Are you sure to reject this data?')\" title='Reject' class='btn btn-danger btn-xs btn-flat' href='dealer/spk_fif/reject?id=$row->no_spk'><i class='fa fa-close'></i> Reject</a>";
                        $tombol3 = "<a data-toggle='tooltip' $edit title='Edit' href='dealer/spk_fif/edit?id=$row->no_spk&set=outstanding'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>";
                        $tombol4 = '';
                        $tombol5 = "<a data-toggle='tooltip' title='Cancel' class='btn btn-danger btn-xs btn-flat' href='dealer/spk_fif/cancel?id=$row->no_spk'><i class='fa fa-close'></i> Cancel</a>";
                        $tombol_cetak = "<a  data-toggle='tooltip' title='Cetak' target='_blank' href='dealer/spk_fif/cetak?id=$row->no_spk'>
                        <button $print class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak SPK</button>
                      </a> ";
                      } elseif ($row->status_spk == 'paid') {
                        $status = "<span class='label label-success'>$row->status_spk</span>";
                        $tombol3 = "<a data-toggle='tooltip' $edit title='Edit' href='dealer/spk_fif/edit?id=$row->no_spk&set=outstanding'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>";
                        $tombol4 = '';
                        $tombol5 = "<a data-toggle='tooltip' title='Cancel' class='btn btn-danger btn-xs btn-flat' href='dealer/spk_fif/cancel?id=$row->no_spk'><i class='fa fa-close'></i> Cancel</a>";
                        $tombol_cetak = "<a  data-toggle='tooltip' title='Cetak' target='_blank' href='dealer/spk_fif/cetak?id=$row->no_spk'>
                        <button $print class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak SPK</button>
                      </a> ";
                      } elseif ($row->status_spk == null or $row->status_spk == '') {
                        $tombol = "<a data-toggle='tooltip' $approval onclick=\"return confirm('Are you sure to approve this data?')\" title='Approve' class='btn btn-success btn-xs btn-flat' href='dealer/spk_fif/approve?id=$row->no_spk'><i class='fa fa-check'></i> Approve</a>";
                        $tombol2 = "<a data-toggle='tooltip' $approval onclick=\"return confirm('Are you sure to reject this data?')\" title='Reject' class='btn btn-danger btn-xs btn-flat' href='dealer/spk_fif/reject?id=$row->no_spk'><i class='fa fa-close'></i> Reject</a>";
                        $tombol3 = "<a data-toggle='tooltip' $edit title='Edit' href='dealer/spk_fif/edit?id=$row->no_spk&set=outstanding'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>";
                        $tombol4 = '';
                        $tombol5 = "<a data-toggle='tooltip' title='Cancel' class='btn btn-danger btn-xs btn-flat' href='dealer/spk_fif/cancel?id=$row->no_spk'><i class='fa fa-close'></i> Cancel</a>";
                        $tombol_cetak = "<a  data-toggle='tooltip' title='Cetak' target='_blank' href='dealer/spk_fif/cetak?id=$row->no_spk'>
                        <button $print class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak SPK</button>
                      </a> ";
                      } elseif ($row->status_spk == 'closed') {
                        $status = "<span class='label label-danger'>Cancel</span>";
                        $tombol = "<a data-toggle='tooltip' $approval onclick=\"return confirm('Are you sure to approve this data?')\" title='Approve' class='btn btn-success btn-xs btn-flat' href='dealer/spk_fif/approve?id=$row->no_spk'><i class='fa fa-check'></i> Approve</a>";
                        $tombol2 = "<a data-toggle='tooltip' $approval onclick=\"return confirm('Are you sure to reject this data?')\" title='Reject' class='btn btn-danger btn-xs btn-flat' href='dealer/spk_fif/reject?id=$row->no_spk'><i class='fa fa-close'></i> Reject</a>";
                        $tombol3 = "<a data-toggle='tooltip' $edit title='Edit' href='dealer/spk_fif/edit?id=$row->no_spk&set=outstanding'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>";
                        $tombol4 = '';
                        $tombol5 = "<a data-toggle='tooltip' title='Cancel' class='btn btn-danger btn-xs btn-flat' href='dealer/spk_fif/cancel?id=$row->no_spk'><i class='fa fa-close'></i> Cancel</a>";
                        $tombol_cetak = "<a  data-toggle='tooltip' title='Cetak' target='_blank' href='dealer/spk_fif/cetak?id=$row->no_spk'>
                        <button $print class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak SPK</button>
                      </a> ";
                      }
                      $cek_s = $this->db->query("SELECT * FROM tr_hasil_survey WHERE no_spk = '$row->no_spk'");
                      if ($cek_s->num_rows() > 0) {
                        $rr = $cek_s->row();
                        $status_s = $rr->status_approval;
                        if ($status_s == 'rejected' and $tombol3 == "") {
                          $tombol4 = "<a data-toggle='tooltip' title='Edit' href='dealer/spk_fif/edit?id=$row->no_spk'><button $edit class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>";
                        }
                      }

                      $prospek = $this->m_admin->getByID("tr_prospek", "id_customer", $row->id_customer);
                      if ($prospek->num_rows() > 0) {
                        $rt = $prospek->row();
                        $nama = $rt->nama_konsumen;
                      } else {
                        $nama = "";
                      }
                      $tipe = $this->m_admin->getByID("ms_tipe_kendaraan", "id_tipe_kendaraan", $row->id_tipe_kendaraan);
                      if ($tipe->num_rows() > 0) {
                        $rs = $tipe->row();
                        $ahm = $rs->tipe_ahm;
                      } else {
                        $ahm = "";
                      }
                      $warna = $this->m_admin->getByID("ms_warna", "id_warna", $row->id_warna);
                      if ($warna->num_rows() > 0) {
                        $rw = $warna->row();
                        $war = $rw->warna;
                      } else {
                        $war = "";
                      }
                      // $cek_so = $this->m_admin->getByID("tr_sales_order","no_spk",$row->no_spk);
                      // if($cek_so->num_rows() == 0){
                      echo "
                <tr>
                  <td>$no</td>
                  <td><a href='" . base_url('dealer/spk_fif/detail?id=') . "$row->no_spk'>$row->no_spk</a></td>
                  <td>$row->nama_konsumen</td>
                  <td>$row->alamat</td>              
                  <td>$ahm</td>
                  <td>$war</td>            
                  <td>$row->no_ktp</td>         
                  <td>$status</td>         
                  <td>          ";
                      echo $tombol_cetak;
                      echo $tombol3;
                      echo $tombol;
                      echo $tombol2;
                      echo $tombol4;
                      echo $tombol5;
                    ?>
                      </td>
                      </tr>
                    <?php
                      $no++;
                      // }
                    }
                    ?>
                  </tbody>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
            <script>
              function closePrompt(no_spk) {
                var alasan_close = prompt("Alasan melakukan close untuk No SPK : " + no_spk);
                if (alasan_close != null || alasan_close == "") {
                  window.location = '<?= base_url("dealer/spk_fif/close?id=") ?>' + no_spk + '&alasan_close=' + alasan_close;
                  return false;
                }
                return false
              }
            </script>

          <?php
          } elseif ($set == 'view_gc') {
          ?>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/spk_fif/add_gc">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
                  </a>
                  <a href="dealer/spk_fif/history_gc">
                    <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History SPK GC</button>
                  </a>
                  <a href="dealer/spk_fif/outstanding_gc">
                    <button class="btn btn-info btn-flat margin"><i class="fa fa-refresh"></i> Outstanding SPK GC</button>
                  </a>
                  <a href="dealer/spk_fif">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn btn-warning btn-flat margin"><i class="fa fa-user"></i> Individu</button>
                  </a>
                  <!-- <a href="dealer/spk_fif/history_gc">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History SPK</button>
          </a>           -->

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
                      <th>No SPK</th>
                      <th>Nama NPWP</th>
                      <th>No NPWP</th>
                      <th>Alamat</th>
                      <th>Status</th>
                      <th width="15%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($dt_spk->result() as $row) {
                      $edit = $this->m_admin->set_tombol($id_menu, $group, 'edit');
                      $delete = $this->m_admin->set_tombol($id_menu, $group, 'delete');
                      $approval = $this->m_admin->set_tombol($id_menu, $group, 'approval');
                      $print = $this->m_admin->set_tombol($id_menu, $group, 'print');
                      if ($row->status == 'input' || $row->status == null || $row->status == '') {
                        $status = "<span class='label label-warning'>$row->status</span>";
                        $tombol = "<a data-toggle='tooltip' $approval onclick=\"return confirm('Are you sure to approve this data?')\" title='Approve' class='btn btn-success btn-xs btn-flat' href='dealer/spk_fif/approve_gc?id=$row->no_spk_gc'><i class='fa fa-check'></i> Approve</a>";
                        $tombol2 = "<a data-toggle='tooltip' $approval onclick=\"return confirm('Are you sure to reject this data?')\" title='Reject' class='btn btn-danger btn-xs btn-flat' href='dealer/spk_fif/reject_gc?id=$row->no_spk_gc'><i class='fa fa-close'></i> Reject</a>";
                        $tombol3 = "<a href='dealer/spk_fif/edit_gc?id_c=$row->no_spk_gc' data-toggle='tooltip' $edit title='Edit'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>";
                        $tombol4 = '';
                        $tombol_cetak = "";
                      } elseif ($row->status == 'rejected') {
                        $status = "<span class='label label-danger'>$row->status</span>";
                        $tombol = "";
                        $tombol2 = "";
                        $tombol3 = "";
                        $tombol4 = "";
                        $tombol_cetak = '';
                        $tombol5 = '';
                      } elseif ($row->status == 'approved') {
                        $status = "<span class='label label-success'>$row->status</span>";
                        $tombol = "";
                        $tombol2 = "";
                        $tombol3 = "<a href='dealer/spk_fif/edit_gc?id_c=$row->no_spk_gc' data-toggle='tooltip' $edit title='Edit'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>";
                        $tombol4 = "";
                        $tombol_cetak = "<a href='dealer/spk_fif/cetak_gc?id_c=$row->no_spk_gc' $print title='Print' class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak SPK</a>";
                        // $tombol5 = "<a data-toggle='tooltip' onclick=\"return confirm('Are you sure to close this data?')\" title='Close' class='btn btn-danger btn-xs btn-flat' href='dealer/spk_fif/close?id=$row->no_spk'><i class='fa fa-close'></i> Close</a>";
                        $tombol5 = '<button class="btn btn-danger btn-xs" onclick="return closePrompt(\'' . $row->no_spk_gc . '\')"><i class=\'fa fa-close\'></i> Cancel</button>';
                      } elseif ($row->status == null or $row->status == '') {
                        $status = "";
                        $tombol = "";
                        $tombol2 = "";
                        $tombol3 = "";
                        $tombol4 = "";
                        $tombol5 = "";
                        $tombol_cetak = '';
                      } elseif ($row->status == 'closed') {
                        $status = "<span class='label label-danger'>Cancel</span>";
                        $tombol_cetak = '';
                        $tombol3 = '';
                        $tombol = '';
                        $tombol2 = '';
                        $tombol4 = '';
                        $tombol5 = '';
                      }
                      $cek_s = $this->db->query("SELECT * FROM tr_hasil_survey WHERE no_spk = '$row->no_spk_gc'");
                      if ($cek_s->num_rows() > 0) {
                        $rr = $cek_s->row();
                        $status_s = $rr->status_approval;
                        if ($status_s == 'rejected' and $tombol3 == "") {
                          $tombol4 = "<a data-toggle='tooltip' title='Edit' href='dealer/spk_fif/edit_gc?id_c=$row->no_spk_gc'><button $edit class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>";
                        }
                      }

                      $cek_so = $this->m_admin->getByID("tr_sales_order_gc", "no_spk_gc", $row->no_spk_gc);
                      if ($cek_so->num_rows() > 0) {
                        $rr = $cek_so->row();
                        if ($rr->status_cetak == 'approve') {
                          $tombol3 = "";
                        }
                      }
                      echo "
              <tr>
                <td>$no</td>
                <td><a href='" . base_url('dealer/spk_fif/detail_gc?id_c=') . "$row->no_spk_gc'>$row->no_spk_gc</a></td>
                <td>$row->nama_npwp</td>
                <td>$row->no_npwp</td>
                <td>$row->alamat</td>                              
                <td>$status</td>         
                <td>          ";
                      echo $tombol_cetak;
                      echo $tombol3;
                      echo $tombol;
                      echo $tombol2;
                      echo $tombol4;
                    ?>
                      </td>
                      </tr>
                    <?php
                      $no++;
                      //}
                    }
                    ?>
                  </tbody>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
            <script>
              function closePrompt(no_spk) {
                var alasan_close = prompt("Alasan melakukan close untuk No SPK : " + no_spk);
                if (alasan_close != null || alasan_close == "") {
                  window.location = '<?= base_url("dealer/spk_fif/close?id=") ?>' + no_spk + '&alasan_close=' + alasan_close;
                  return false;
                }
                return false
              }
            </script>
          <?php
          } elseif ($set == "history") {
          ?>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/spk_fif">
                    <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
                      <th width="5%">No</th>
                      <th>No SPK</th>
                      <th>Nama Konsumen</th>
                      <th>Alamat</th>
                      <th>Tipe</th>
                      <th>Warna</th>
                      <th>No KTP</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    $print = $this->m_admin->set_tombol($id_menu, $group, 'print');
                    foreach ($dt_spk->result() as $row) {
                      $prospek = $this->m_admin->getByID("tr_prospek", "id_customer", $row->id_customer);
                      if ($prospek->num_rows() > 0) {
                        $rt = $prospek->row();
                        $nama = $rt->nama_konsumen;
                      } else {
                        $nama = "";
                      }
                      $tipe = $this->m_admin->getByID("ms_tipe_kendaraan", "id_tipe_kendaraan", $row->id_tipe_kendaraan);
                      if ($tipe->num_rows() > 0) {
                        $rs = $tipe->row();
                        $ahm = $rs->tipe_ahm;
                      } else {
                        $ahm = "";
                      }
                      $warna = $this->m_admin->getByID("ms_warna", "id_warna", $row->id_warna);
                      if ($warna->num_rows() > 0) {
                        $rw = $warna->row();
                        $war = $rw->warna;
                      } else {
                        $war = "";
                      }
                      echo "
            <tr>
              <td>$no</td>
              <td><a href='" . base_url('dealer/spk_fif/detail?id=') . "$row->no_spk'>$row->no_spk</a></td>
              <td>$nama</td>
              <td>$row->alamat</td>              
              <td>$ahm</td>
              <td>$war</td>            
              <td>$row->no_ktp</td>                       
              <td><a  data-toggle='tooltip' title='Cetak' target='_blank' href='dealer/spk_fif/cetak?id=$row->no_spk'>
                  <button $print class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak SPK</button>
                </a></td>
            </tr>";
                      $no++;
                    }
                    ?>
                  </tbody>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->

          <?php
          } elseif ($set == "history_gc") {
          ?>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/spk_fif/gc">
                    <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
                  </a>
                  <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->
                </h3>
                <div class="box-tools pull-right">
                  <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
              </div><!-- /.box-header -->
              <div class="box-body">
                <table id="tbl_history_gc" class="table table-bordered table-hover">
                  <thead>
                    <th>No SPK</th>
                    <th>Nama NPWP</th>
                    <th>No NPWP</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </thead>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
            <script>
              $(document).ready(function() {
                $('#tbl_history_gc').DataTable({
                  processing: true,
                  serverSide: true,
                  "language": {
                    "infoFiltered": "",
                    "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                  },
                  order: [],
                  ajax: {
                    url: "<?= base_url('dealer/spk_fif/fetch_history_gc') ?>",
                    dataSrc: "data",
                    data: function(d) {
                      return d;
                    },
                    type: "POST"
                  },
                  "columnDefs": [{
                      "targets": [5],
                      "orderable": false
                    },
                    {
                      "targets": [5],
                      "className": 'text-center'
                    },
                    // {
                    //   "targets": [5],
                    //   "className": 'text-right'
                    // },
                    // { "targets":[4], "searchable": false } 
                  ],
                });
              });
            </script>
          <?php
          } elseif ($set == "outstanding_gc") {
          ?>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/spk_fif/gc">
                    <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
                  </a>
                  <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->
                </h3>
                <div class="box-tools pull-right">
                  <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
              </div><!-- /.box-header -->
              <div class="box-body">
                <table id="tbl_outstanding_gc" class="table table-bordered table-hover">
                  <thead>
                    <th>No SPK</th>
                    <th>Nama NPWP</th>
                    <th>No NPWP</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </thead>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
            <script>
              $(document).ready(function() {
                $('#tbl_outstanding_gc').DataTable({
                  processing: true,
                  serverSide: true,
                  "language": {
                    "infoFiltered": "",
                    "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                  },
                  order: [],
                  ajax: {
                    url: "<?= base_url('dealer/spk_fif/fetch_outstanding_gc') ?>",
                    dataSrc: "data",
                    data: function(d) {
                      return d;
                    },
                    type: "POST"
                  },
                  "columnDefs": [{
                      "targets": [5],
                      "orderable": false
                    },
                    {
                      "targets": [5],
                      "className": 'text-center'
                    },
                    // {
                    //   "targets": [5],
                    //   "className": 'text-right'
                    // },
                    // { "targets":[4], "searchable": false } 
                  ],
                });
              });
            </script>
          <?php
          } elseif ($set == "history_fix") {
          ?>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/spk_fif">
                    <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
                <table id="datatable" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td width="5%">No</td>
                      <td>No SPK</td>
                      <td>Nama Konsumen</td>
                      <td>Alamat</td>
                      <td>Tipe</td>
                      <td>Warna</td>
                      <td>No KTP</td>
                      <td>Aksi</td>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>


            <script type="text/javascript">
              $(document).ready(function(e){
                $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
                {
                    return {
                        "iStart": oSettings._iDisplayStart,
                        "iEnd": oSettings.fnDisplayEnd(),
                        "iLength": oSettings._iDisplayLength,
                        "iTotal": oSettings.fnRecordsTotal(),
                        "iFilteredTotal": oSettings.fnRecordsDisplay(),
                        "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };

                var base_url = "<?php echo base_url() ?>"; // You can use full url here but I prefer like this
                $('#datatable').DataTable({
                   "pageLength" : 10,
                   "serverSide": true,
                   "ordering": true, // Set true agar bisa di sorting
                    "processing": true,
                    "language": {
                      processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                      searchPlaceholder: "Pencarian..."
                    },

                   "order": [[1, "desc" ]],
                   "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    },
                   "ajax":{
                            url :  base_url+'dealer/spk_fif/get_history_spk',
                            type : 'POST'
                          },
                }); // End of DataTable


              }); 

            </script>


          <?php
          } elseif ($set == "cancel") {
          ?>
            <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/spk_fif">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
                    <form class="form-horizontal" id="form_" action="dealer/spk_fif/save_cancel" method="post" enctype="multipart/form-data">
                      <div class="box-body">
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No. SPK</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="no_spk" required value="<?= $row->no_spk ?>" readonly>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" required value="<?= $row->nama_konsumen ?>" readonly>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" required value="<?= $row->no_ktp ?>" readonly>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" required value="<?= $row->tipe_ahm ?>" readonly>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" required value="<?= $row->warna ?>" readonly>
                          </div>
                        </div>
                        <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Alasan Cancel SPK</label>
                          <div class="col-sm-4">
                            <select name="id_reasons" class="form-control select2" onchange="cekLain(this)">
                              <?php if ($reasons->num_rows() > 0) : ?>
                                <option value="">--choose--</option>
                                <?php foreach ($reasons->result() as $rs) : ?>
                                  <option value="<?= $rs->id_reasons ?>"><?= $rs->deskripsi ?></option>
                                <?php endforeach ?>
                              <?php endif ?>
                            </select>
                          </div>
                          <div class="col-sm-4" v-if="alasan==1">
                            <input type="text" class="form-control" required value="" name="alasan_close" placeholder="Masukkan Alasan Cancel SPK" autocomplete="off">
                          </div>
                        </div>
                        <?php if ($row->indent > 0) : ?>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Alasan Cancel Indent</label>
                            <div class="col-sm-4">
                              <select name="id_alasan_cancel" class="form-control select2" onchange="cekLainIndent(this)">
                                <?php if ($alasan_cancel->num_rows() > 0) : ?>
                                  <option value="">--choose--</option>
                                  <?php foreach ($alasan_cancel->result() as $rs) : ?>
                                    <option value="<?= $rs->id_alasan_cancel ?>"><?= $rs->alasan_cancel ?></option>
                                  <?php endforeach ?>
                                <?php endif ?>
                              </select>
                            </div>
                            <div class="col-sm-4" v-if="alasan_indent==1">
                              <input type="text" class="form-control" required value="" name="alasan_cancel_indent" placeholder="Masukkan Alasan Cancel Indent" autocomplete="off">
                            </div>
                          </div>
                        <?php endif ?>
                      </div>
                      <div class="box-footer">
                        <div class="col-sm-12" align="center">
                          <button type="submit" name="save" value="save" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Cancel</button>
                        </div>
                      </div><!-- /.box-footer -->
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <script>
              function cekLain(id_) {
                var alasan = $(id_).val();
                if (alasan == 'RE_00003') {
                  form_.alasan = 1;
                } else {
                  form_.alasan = '';
                }
              }

              function cekLainIndent(id_) {
                var alasan = $(id_).val();
                if (alasan == 11) {
                  form_.alasan_indent = 1;
                } else {
                  form_.alasan_indent = '';
                }
              }
              var form_ = new Vue({
                el: '#form_',
                data: {
                  alasan: '',
                  alasan_indent: ''
                }
              })
            </script>
          <?php
          }
          ?>
        </section>
      </div>
      <?php if ($set == 'insert' || $set == 'insert_demo' || $set == 'edit') { ?>
        <div class="modal fade" id="Customermodal">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                Search and Filter Customer
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th width="10%"></th>
                      <th>Tgl Prospek</th>
                      <th>ID Customer</th>
                      <th>Nama Customer</th>
                      <th>No HP</th>
                      <th>Nama Sales</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($dt_customer->result() as $ve2) {
                      echo "
            <tr>"; ?>
                      <td class="center">
                        <button title="Choose" onClick="Chooseitem('<?php echo $ve2->id_customer; ?>')" type="button" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>
                      </td>
                      <?php 
                      $nama_lengkap = isset($ve2->nama_lengkap) ? $ve2->nama_lengkap : '';
                      echo "
              <td>$ve2->tgl_prospek</td>
        <td>$ve2->id_customer</td>
              <td>$ve2->nama_konsumen</td>
              <td>$ve2->no_hp</td>
              <td>$nama_lengkap</td>";
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
      <?php } ?>
      <div class="modal fade" id="Npwpmodal">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              Search and Filter Group Customer
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <table id="example3" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th width="10%"></th>
                    <th>Tgl Prospek</th>
                    <th>No NPWP</th>
                    <th>Nama NPWP</th>
                    <th>Nama Penanggung Jawab</th>
                    <th>Nama Sales</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  foreach ($dt_npwp->result() as $ve2) {
                    echo "
            <tr>"; ?>
                    <td class="center">
                      <button title="Choose" onClick="Choosenpwp('<?php echo $ve2->id_prospek_gc; ?>')" type="button" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>
                    </td>
                    <?php 
                    $nama_lengkap = isset($ve2->nama_lengkap) ? $ve2->nama_lengkap : '';
                    echo "
              <td>$ve2->tgl_prospek</td>
        <td>$ve2->no_npwp</td>
              <td>$ve2->nama_npwp</td>
              <td>$ve2->nama_penanggung_jawab</td>
        <td>$nama_lengkap</td>";
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
      <div class="modal fade" id="Reffmodal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              Search Data Refferal ID
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" autocomplete="off" placeholder="No Rangka" name="no_rangka" id="no_rangka_cari">
                  </div>
                  <div class="col-sm-2">
                    <button type="button" onclick="cari()" class="btn btn-flat btn-primary" type="button">Search</button>
                  </div>
                </div>
                <span id="myTable1">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Refferal ID</label>
                    <div class="col-sm-10">
                      <input type="text" readonly class="form-control" placeholder="Refferal ID" id="refferal_id_lbl">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Lengkap</label>
                    <div class="col-sm-10">
                      <input type="text" readonly class="form-control" placeholder="Nama Lengkap" id="nama_lbl">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir</label>
                    <div class="col-sm-10">
                      <input type="text" readonly class="form-control" placeholder="Tgl Lahir" id="tgl_lahir_lbl">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                    <div class="col-sm-10">
                      <input type="text" readonly class="form-control" placeholder="No KTP" id="no_ktp_lbl">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                      <button type="button" onclick="pilih_refferal()" class="btn btn-success btn-flat"><i class="fa fa-check"></i> Choose</button>
                    </div>
                  </div>
                </span>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="Robdmodal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              Search Data ROBD ID
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" autocomplete="off" placeholder="No Rangka" name="no_rangka" id="no_rangka_cari2">
                  </div>
                  <div class="col-sm-2">
                    <button type="button" onclick="cari2()" class="btn btn-flat btn-primary" type="button">Search</button>
                  </div>
                </div>
                <span id="myTable2">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">ROBD ID</label>
                    <div class="col-sm-10">
                      <input type="text" readonly class="form-control" placeholder="ROBD ID" id="robd_id_lbl">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Lengkap</label>
                    <div class="col-sm-10">
                      <input type="text" readonly class="form-control" placeholder="Nama Lengkap" id="nama_lbl2">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir</label>
                    <div class="col-sm-10">
                      <input type="text" readonly class="form-control" placeholder="Tgl Lahir" id="tgl_lahir_lbl2">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                    <div class="col-sm-10">
                      <input type="text" readonly class="form-control" placeholder="No KTP" id="no_ktp_lbl2">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                      <button type="button" onclick="pilih_robd()" class="btn btn-success btn-flat"><i class="fa fa-check"></i> Choose</button>
                    </div>
                  </div>
                </span>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="Kelurahanmodal">
        <div class="modal-dialog" role="document" style="width: 50%">
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
                    <th>Kabupaten</th>
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
      <div class="modal fade" id="Kelurahanmodal2">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              Search Kelurahan
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <table id="table2" class="table table-bordered table-hover">
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
      <div class="modal fade modal_edit" id="modaall">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edit</h4>
            </div>
            <div class="modal-body" id="show_detail">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
      </div>
      <script type="text/javascript">
        var jenis_barang_global = '';

        function samakan() {
          document.getElementById("alamat_penjamin").value = $("#alamat").val();;
        }

        function cek_program() {
          var program_umum = $("#program_umum").val();
          if (program_umum != "") {
            $("#nilai_voucher").show();
            $("#nilai_voucher_lbl").show();
          } else {
            $("#nilai_voucher").hide();
            $("#nilai_voucher").val("");
            $("#nilai_voucher_lbl").hide();
          }
        }

        function showJenisBarangCash(barang) {
          $('#div_jenis_barang_cash').show();
          $('#lbl_total_bayar_cash').removeClass('col-md-offset-6');
          $('#jenis_barang_cash').val(barang);
        }

        function hideJenisBarangCash() {
          $('#div_jenis_barang_cash').hide();
          $('#lbl_total_bayar_cash').addClass('col-md-offset-6');
          $('#jenis_barang_cash').val('');
        }

        function showJenisBarangKredit(barang) {
          $('#div_jenis_barang_kredit').show();
          $('#lbl_total_bayar_kredit').removeClass('col-md-offset-6');
          $('#jenis_barang_kredit').val(barang);
        }

        function hideJenisBarangKredit() {
          $('#div_jenis_barang_kredit').hide();
          $('#lbl_total_bayar_kredit').addClass('col-md-offset-6');
          $('#jenis_barang_kredit').val('');
        }

        function clearProgramUmum() {
          // alert('sudah');
          $('#lbl_cash #program_umum').val('');
          $('#lbl_kredit #program_umum').val('');
          $('#lbl_cash #voucher_1').val('');
          $('#lbl_cash #nilai_voucher').val('');
        }

        function clearProgramGabungan() {
          // alert('sudah');
          $('#lbl_cash #program_gabungan').val('');
          $('#lbl_kredit #program_gabungan').val('');
          $('#lbl_kredit #program_gabungan_kredit').val('');
          $('#lbl_cash #voucher_tambahan_1').val('');
        }

        function cek_program_tambahan() {
          $('#voucher_2').val('');
          $('#voucher_1').val('');
          var jenis_beli = $("#beli").val();
          if (jenis_beli == 'Cash') {
            var id_program_md = $('#lbl_cash #program_umum').val();
          } else if (jenis_beli == 'Kredit') {
            var id_program_md = $('#lbl_kredit #program_umum').val();
          }
          if (id_program_md != '') {
            var value = {
              id_program_md: id_program_md,
              id_warna: $("#id_warna").val(),
              id_tipe_kendaraan: $("#id_tipe_kendaraan").val(),
              jenis_beli: $("#beli").val()
            }
            $.ajax({
              //beforeSend: function() { $('#loading-status').show(); },
              url: "<?php echo site_url('dealer/spk_fif/getProgramTambahan') ?>",
              type: "POST",
              data: value,
              cache: false,
              success: function(html) {
                data = html.split("##");
                $('#loading-status').hide();
                if (data.length > 2) {
                  if (jenis_beli == 'Cash') {
                    if (data[3] != '') {
                      showJenisBarangCash(data[3]);
                      jenis_barang_global = data[3];
                    } else {
                      hideJenisBarangCash();
                      jenis_barang_global = data[3];
                    }
                    if (data[2] > 0) {
                      $("#program_gabungan_lbl").show();
                      $('#program_gabungan').html(data[1]);
                    } else if (data[2] == 0) {
                      $("#program_gabungan_lbl").hide();
                    }
                    if (data[0] != '' || data[0] > 0) {
                      $("#nilai_voucher").show();
                      $('#nilai_voucher').val(convertToRupiah(data[0]));
                      $('#voucher_1').val(data[0]);
                      $("#nilai_voucher_lbl").show();
                    } else if (data[0] == '' || data[0] == 0) {
                      $("#nilai_voucher").hide();
                      $("#nilai_voucher").val("");
                      $("#nilai_voucher_lbl").hide();
                    }
                  } else if (jenis_beli == 'Kredit') {
                    if (data[3] != '') {
                      showJenisBarangKredit(data[3]);
                      jenis_barang_global = data[3];
                    } else {
                      hideJenisBarangKredit();
                      jenis_barang_global = data[3];
                    }
                    if (data[2] > 0) {
                      $('#program_gabungan_kredit').html(data[1]);
                      $('#program_gabungan_kredit').show();
                      $('#program_gabungan_kredit_lbl').show();
                    } else if (data[2] == 0) {
                      $('#program_gabungan_kredit').hide();
                      $("#program_gabungan_kredit_lbl").hide();
                    }
                    if (data[0] != '' || data[0] > 0) {
                      $('#nilai_voucher2').val(convertToRupiah(data[0]));
                      $('#voucher_2').val(data[0]);
                      $("#nilai_voucher2").show();
                      $("#nilai_voucher2_lbl").show();
                    } else if (data[0] == '' || data[0] == 0) {
                      $("#nilai_voucher2").hide();
                      $("#nilai_voucher2").val("");
                      $('#voucher_2').val('');
                      $("#nilai_voucher2_lbl").hide();
                    }
                  }
                  get_total_ck();
                  <?php if ($set == 'edit' || $set == 'detail') { ?>
                    setProgramGabunganSebelumnya();
                  <?php } ?>
                } else {
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
          } else {
            if (jenis_beli == 'Cash') {
              $("#nilai_voucher").hide();
              $("#nilai_voucher").val("");
              $("#nilai_voucher_lbl").hide();
              $("#program_gabungan_lbl").hide();
            } else if (jenis_beli == 'Kredit') {
              $("#nilai_voucher2").hide();
              $("#nilai_voucher2").val("");
              $("#nilai_voucher2_lbl").hide();
              $("#program_gabungan_kredit_lbl ").hide();
            }
            //alert('Silahkan Pilih Program');
            get_total_ck();
          }
        }

        function getVoucherGabungan(params_program_gabungan = null) {
          var jenis_beli = $("#beli").val();
          if (jenis_beli == 'Cash') {
            var id_program_md = $('#lbl_cash #program_umum').val();
            var program_gabungan = $('#lbl_cash #program_gabungan').val();
            var jenis_barang = $('#jenis_barang_cash').val();
          } else if (jenis_beli == 'Kredit') {
            var id_program_md = $('#lbl_kredit #program_umum').val();
            var program_gabungan = $('#lbl_kredit #program_gabungan_kredit').val();
            var jenis_barang = $('#jenis_barang_kredit').val();
          }
          if (params_program_gabungan != null) {
            program_gabungan = params_program_gabungan;
            $("#program_gabungan_kredit").val(program_gabungan).change();
          }
          var value = {
            id_program_md: id_program_md,
            id_program_gabungan: program_gabungan,
            id_warna: $("#id_warna").val(),
            id_tipe_kendaraan: $("#id_tipe_kendaraan").val(),
            jenis_beli: $("#beli").val()
          }
          console.log(value);
          if (program_gabungan === '- choose-' || program_gabungan === '' || program_gabungan === null) {
            if (jenis_beli == 'Cash') {
              hideJenisBarangCash();
            } else {
              hideJenisBarangKredit();
            }
            return false;
          }
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/spk_fif/getVoucherGabungan') ?>",
            type: "POST",
            data: value,
            cache: false,
            success: function(dt_response) {
              response = dt_response.split("##");
              // console.log(response);
              data = response[0];
              if (response.length > 1) {
                if (jenis_beli == 'Cash') {
                  if (data != '' || data > 0) {
                    $("#nilai_voucher").show();
                    $('#nilai_voucher').val(convertToRupiah(data));
                    $('#voucher_1').val(data);
                    $("#nilai_voucher_lbl").show();
                  } else if (data == '' || data == 0) {
                    $("#nilai_voucher").hide();
                    $("#nilai_voucher").val("");
                    $("#nilai_voucher_lbl").hide();
                  }
                  if (id_program_md != '') {
                    if (jenis_barang_global == '' || jenis_barang_global == 'undefined') {
                      if (response[1] != '') {
                        showJenisBarangCash(response[1]);
                      } else {
                        hideJenisBarangCash();
                      }
                    }
                  }
                } else if (jenis_beli == 'Kredit') {
                  if (data != '' || data > 0) {
                    $("#nilai_voucher2").show();
                    $('#nilai_voucher2').val(convertToRupiah(data));
                    $('#voucher_2').val(data);
                    $("#nilai_voucher2_lbl").show();
                  } else if (data == '' || data == 0) {
                    $("#nilai_voucher2").hide();
                    $("#nilai_voucher2").val("");
                    $("#nilai_voucher_lbl").hide();
                  }
                  if (id_program_md != '') {
                    if (jenis_barang_global == '' || jenis_barang_global == 'undefined') {
                      if (response[1] != '') {
                        showJenisBarangKredit(response[1]);
                      } else {
                        hideJenisBarangKredit();
                      }
                    }
                  }
                }
              } else {
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

        function hideProgram() {
          $('#program_umum').val('');
          $('#nilai_voucher').val('');
          $('#div_program_umum').hide();
          $('#div_program_gabungan').hide();
          $('#program_gabungan').val('');
          $('#voucher_tambahan_1').val('');
        }

        function showProgram() {
          $('#div_program_umum').show();
          $('#div_program_gabungan').show();
        }

        function cek_voucher() {
          var voucher = $("#voucher").val();
          if (voucher != "") {
            $("#nilai_voucher2").show();
            $("#nilai_voucher2_lbl").show();
          } else {
            $("#nilai_voucher2").hide();
            $("#nilai_voucher2").val("");
            $("#nilai_voucher2_lbl").hide();
          }
        }

        function cari() {
          $("#myTable1").show();
          var no_rangka = $("#no_rangka_cari").val();
          var no_ktp = $("#no_ktp").val();
          if (no_ktp == "") {
            alert("Isikan No KTP dahulu...!");
            return false;
          } else {
            $.ajax({
              url: "<?php echo site_url('dealer/spk_fif/take_ref') ?>",
              type: "POST",
              data: "no_rangka=" + no_rangka + "&no_ktp=" + no_ktp,
              cache: false,
              success: function(msg) {
                data = msg.split("|");
                $("#no_rangka_lbl").val(data[0]);
                $("#refferal_id_lbl").val(data[1]);
                $("#nama_lbl").val(data[2]);
                $("#tgl_lahir_lbl").val(data[3]);
                $("#no_ktp_lbl").val(data[4]);
              }
            })
          }
        }

        function pilih_refferal() {
          document.getElementById("refferal_id").value = $("#refferal_id_lbl").val();
          document.getElementById("nama_refferal_id").value = $("#nama_lbl").val();
          $("#Reffmodal").modal("hide");
        }

        function pilih_robd() {
          document.getElementById("robd_id").value = $("#robd_id_lbl").val();
          document.getElementById("nama_robd_id").value = $("#nama_lbl2").val();
          $("#Robdmodal").modal("hide");
        }

        function cari2() {
          $("#myTable2").show();
          var no_rangka = $("#no_rangka_cari2").val();
          var no_ktp = $("#no_ktp").val();
          if (no_ktp == "") {
            alert("Isikan No KTP dahulu...!");
            return false;
          } else {
            $.ajax({
              url: "<?php echo site_url('dealer/spk_fif/take_robd') ?>",
              type: "POST",
              data: "no_rangka=" + no_rangka + "&no_ktp=" + no_ktp,
              cache: false,
              success: function(msg) {
                data = msg.split("|");
                $("#no_rangka_lbl2").val(data[0]);
                $("#robd_id_lbl").val(data[1]);
                $("#nama_lbl2").val(data[2]);
                $("#tgl_lahir_lbl2").val(data[3]);
                $("#no_ktp_lbl2").val(data[4]);
              }
            })
          }
        }

        function hide() {
          $("#lbl_kredit").hide();
          $("#lbl_cash").hide();
          $("#myTable1").hide();
          $("#myTable2").hide();
          $("#nilai_voucher").hide();
          $("#nilai_voucher_lbl").hide();
          $("#nilai_voucher2").hide();
          $("#nilai_voucher2_lbl").hide();
        }

        function get_beli() {
          jenis_barang_global = '';
          if (typeof form_ !== "undefined") {
            form_.tipe_pembelian = '';
          }

          var isi = $("#beli").val();
          if (isi == 'Cash') {
            $("#lbl_cash").show();
            $("#lbl_kredit").hide();
            $("#program_gabungan_lbl").hide();
            $("#nilai_voucher").val('');
            tampil_cash();
            if (typeof form_ !== "undefined") {
              form_.tipe_pembelian = 'Cash';
            }
          } else if (isi == 'Kredit') {
            $("#lbl_cash").hide();
            $("#lbl_kredit").show();
            $("#program_gabungan_kredit").val('');
            $("#program_gabungan_kredit").hide();
            $("#program_gabungan_kredit_lbl").hide();
            tampil_kredit();
            // $("#nilai_voucher2").val('');
            if (typeof form_ !== "undefined") {
              form_.tipe_pembelian = 'Kredit';
            }
          }
          // console.log(form_.tipe_pembelian);
          var value = {
            id_tipe_kendaraan: $("#id_tipe_kendaraan").val(),
            id_warna: $("#id_warna").val(),
            jenis_beli: $("#beli").val(),
            <?php if (isset($row->program_umum)) { ?>
              program_umum: '<?= $row->program_umum ?>'
            <?php } ?>
          }
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/spk_fif/getProgram') ?>",
            type: "POST",
            data: value,
            cache: false,
            success: function(html) {
              $('#loading-status').hide();
              if (isi == 'Cash') {
                $('#lbl_cash #program_umum').html(html);
                hideJenisBarangCash();
                take_harga()
              } else if (isi == 'Kredit') {
                $('#lbl_kredit #program_umum').html(html);
                hideJenisBarangKredit();
                <?php if ($set == 'insert') {  ?>
                  getSkemaKredit();
                <?php } ?>

                // take_harga()
              }
              get_total_ck();

              <?php if ($set == 'edit' || $set == 'detail') { ?>
                setProgramUmumSebelumnya();
              <?php } ?>

              <?php if ($set == 'edit') { ?> cek_program_tambahan() <?php } ?>
            },
            statusCode: {
              500: function() {
                $('#loading-status').hide();
                alert("Something Wen't Wrong");
              }
            }
          });
        }

        function setProgramUmumSebelumnya() {
          <?php if ($set == 'edit' || $set == 'detail') {
            $program_umum = $row->program_umum;
            $program_gabungan = $row->program_gabungan;
          ?>
            let program_umum = '<?= $program_umum ?>';
            $("#program_umum").val(program_umum).change();
          <?php } ?>
        }

        function setProgramGabunganSebelumnya() {
          <?php if ($set == 'edit' || $set == 'detail') {
            $program_gabungan = $row->program_gabungan;
          ?>
            let program_gabungan = '<?= $program_gabungan ?>';
            $("#program_gabungan").val(program_gabungan).change();
            getVoucherGabungan(program_gabungan);
          <?php } ?>
        }

        function auto() {
          hide();
          $("#warna_mode").val("");
          $("#warna_mode2").val("");
          var tgl_js = "1";
          $.ajax({
            url: "<?php echo site_url('dealer/spk_fif/cari_id') ?>",
            type: "POST",
            data: "tgl=" + tgl_js,
            cache: false,
            success: function(msg) {
              data = msg.split("|");
              $("#id_spk").val(data[0]);
              //$("#id_customer").val(data[1]);           
              $("#tampil_alamat").hide();
            }
          })
        }

        function cek_tanya2() {
          var tanya = $("#tanya").val();
          if (tanya == 'Tidak') {
            $("#tampil_alamat").show();
          } else {
            $("#tampil_alamat").hide();
          }
        }

        function cek_tanya() {
          var tanya = $("#tanya").val();
          if (tanya == 'Tidak') {
            $("#tampil_alamat").show();
            $("#id_kecamatan2").val("");
            $("#id_kabupaten2").val("");
            $("#id_kelurahan2").val("");
            $("#id_provinsi2").val("");
            $("#kodepos2").val("");
            $("#alamat2").val("");
          } else {
            $("#tampil_alamat").hide();
            document.getElementById("id_kecamatan2").value = $("#id_kecamatan").val();
            document.getElementById("id_kabupaten2").value = $("#id_kabupaten").val();
            document.getElementById("id_kelurahan2").value = $("#id_kelurahan").val();
            document.getElementById("id_provinsi2").value = $("#id_provinsi").val();
            document.getElementById("kodepos2").value = $("#kodepos").val();
            document.getElementById("alamat2").value = $("#alamat").val();
          }
        }

        function Chooseitem(id_customer) {
          document.getElementById("id_customer").value = id_customer;
          cek_customer();
          $("#Customermodal").modal("hide");
        }

        function getAksesoris() {
          var id_tipe_kendaraan = $('#id_tipe_kendaraan').val();
          values = {
            id_tipe_kendaraan: id_tipe_kendaraan
          }
          // console.log(values)
          $.ajax({
            beforeSend: function() {},
            url: '<?= base_url('dealer/spk_fif/getAksesoris') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              if (typeof form_ !== "undefined") {
                form_.ksu_ = [];
                $.each(response, function(i, ksu) {
                  form_.ksu_.push(ksu)
                });
              }
            },
            error: function() {
              alert("failure");
            },
            statusCode: {
              500: function() {
                alert('fail');
              }
            }
          });
        }

        function getSkemaKredit() {
          var id_customer = $('#id_customer').val();
          values = {
            id_customer: id_customer
          }
          // console.log(values)
          $.ajax({
            beforeSend: function() {},
            url: '<?= base_url('dealer/spk_fif/getSkemaKredit') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              if (response.status == 'sukses') {
                form_.tenor = response.tenor;
                form_.angsuran = response.angsuran;
                form_.uang_muka = response.uang_muka;
                form_.finco = response.finance_company;
                form_.id_finance_company = response.id_finance_company;
              }
              if (response.status == 'kosong') {
                alert('Skema Kredit Belum Ditentukan !')
              }
            },
            error: function() {
              alert("failure");
            },
            statusCode: {
              500: function() {
                alert('fail');
              }
            }
          });
        }

        function cek_customer() {
          var id_customer = $("#id_customer").val();
          $.ajax({
            url: "<?php echo site_url('dealer/spk_fif/cek_customer') ?>",
            type: "POST",
            data: "id_customer=" + id_customer,
            cache: false,
            success: function(msg) {
              data = msg.split("|");
              // console.log(data);
              if (data[0] == "ok") {
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
                // alert(data[13]);
              } else {
                alert(data[0]);
              }
            }
          })
        }

        function Choosenpwp(id_prospek_gc) {
          document.getElementById("id_prospek_gc").value = id_prospek_gc;
          cek_prospek();
          $("#Npwpmodal").modal("hide");
          $("select span option").unwrap(); //unwrap only wrapped
        }

        function cek_prospek() {
          var id_prospek_gc = $("#id_prospek_gc").val();
          $.ajax({
            url: "<?php echo site_url('dealer/spk_fif/cek_prospek') ?>",
            type: "POST",
            data: "id_prospek_gc=" + id_prospek_gc,
            cache: false,
            success: function(msg) {
              data = msg.split("|");
              if (data[0] == "ok") {
                $("#nama_npwp").val(data[1]);
                $("#no_npwp").val(data[2]);
                $("#alamat").val(data[3]);
                $("#id_kelurahan").val(data[4]);
                $("#jenis_gc").val(data[5]);
                if (data[5] == 'Instansi' || data[5] == 'Join Promo' || data[5] == 'Joint Promo') {
                  tambah_option1();
                } else {
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
              } else {
                alert(data[0]);
              }
            }
          })
        }

        function tambah_option1() {
          var myOptions = {
            '': '- choose -',
            Cash: 'Cash'
          };
          var mySelect = $('#beli');
          $('#beli').html("");
          $.each(myOptions, function(val, text) {
            mySelect.append(
              $('<option></option>').val(val).html(text)
            );
          });
        }

        function tambah_option2() {
          var myOptions = {
            '': '- choose -',
            Cash: 'Cash',
            Kredit: 'Kredit'
          };
          var mySelect = $('#beli');
          $('#beli').html("");
          $.each(myOptions, function(val, text) {
            mySelect.append(
              $('<option></option>').val(val).html(text)
            );
          });
        }

        function take_harga() {
          var tipe_customer = $("#tipe_customer").val();
          var id_tipe_kendaraan = $("#id_tipe_kendaraan").val();
          var id_warna = $("#id_warna").val();
          var mode_edit = $("#mode_edit").val();
          //alert(id_warna+id_tipe_kendaraan);
          getAksesoris();
          if (mode_edit != 'false') {
            $.ajax({
              url: "<?php echo site_url('dealer/spk_fif/cek_bbn') ?>",
              type: "POST",
              data: "id_warna=" + id_warna + "&id_tipe_kendaraan=" + id_tipe_kendaraan + "&tipe_customer=" + tipe_customer,
              cache: false,
              success: function(msg) {
                data = msg.split("|");
                var bbn = data[0];
                var h_off = data[2];
                var price = parseInt(bbn) + parseInt(h_off);
                $("#biaya_bbn").val(data[0]);
                $("#harga_on").val(data[1]);
                $("#harga_off").val(data[2]);
                $("#harga_pricelist").val(price);
                $("#ppn").val(data[3]);
                $("#harga").val(data[4]);
                $("#harga_tunai").val(data[5]);
                $("#biaya_bbn_r").val(convertToRupiah(data[0]));
                $("#harga_off_r").val(convertToRupiah(data[2]));
                $("#harga_pricelist_r").val(convertToRupiah(price));
                $("#harga_on_r").val(convertToRupiah(data[1]));
                $("#ppn_r").val(convertToRupiah(data[3]));
                $("#harga_r").val(convertToRupiah(data[4]));
                $("#harga_tunai_r").val(convertToRupiah(data[5]));
                get_total_ck()
                // get_total();
              }
            })
          }
        }

        function get_total() {
          var biaya_bbn = $("#biaya_bbn").val();
          var harga_tunai = $("#harga_tunai").val();
          var program_umum = $("#program_umum").val();
          var voucher_tambahan = $("#voucher_tambahan_2").val();
          var total = parseInt(harga_tunai) - parseInt(voucher_tambahan);
          var ubah_total = convertToRupiah(total);
          $("#total_bayar_r").val(ubah_total);
          $("#total_bayar").val(total);
        }

        function get_total_ck() {
          var biaya_bbn = $("#biaya_bbn").val();
          var harga_tunai = $("#harga_tunai").val() == '' ? 0 : $("#harga_tunai").val();
          var jenis_beli = $("#beli").val();

          if (jenis_beli == 'Cash') {
            var voucher_tambahan = $("#voucher_tambahan_1").val() == '' ? 0 : $("#voucher_tambahan_1").val();
            var n_v = $("#lbl_cash #nilai_voucher").val();
            var nilai_voucher = n_v.replace(/\D/g, "");
            nilai_voucher = nilai_voucher > 0 ? nilai_voucher : 0;
            var total = parseInt(harga_tunai) - (parseInt(nilai_voucher) + parseInt(voucher_tambahan));
            var ubah_total = convertToRupiah(total);
            $("#total_bayar_r").val(ubah_total);
            $("#total_bayar").val(total);
          } else if (jenis_beli == 'Kredit') {
            $("#nilai_voucher2").show();
            $("#nilai_voucher2_lbl").show();
            var n_v = $("#nilai_voucher2").val();

            var nilai_voucher = n_v.replace(/\D/g, "");
            nilai_voucher = nilai_voucher > 0 ? nilai_voucher : 0;
            var voucher_tambahan = $("#voucher_tambahan_2").val() == '' ? 0 : $("#voucher_tambahan_2").val();
            var uang_muka = $("#uang_muka").val() == '' ? 0 : $("#uang_muka").val();
            var dp_setor = parseInt(uang_muka) - parseInt(nilai_voucher) - parseInt(voucher_tambahan);
            dp_setor = dp_setor < 0 ? 0 : dp_setor;
            var ubah_dp_setor = convertToRupiah(dp_setor);
            //alert(nilai_voucher+'/'+voucher_tambahan+'/'+uang_muka+'/'+dp_setor);
            $("#dp_setor").val(ubah_dp_setor);
            $("#dp_stor").val(dp_setor);
          }
        }

        function get_on() {
          var the_road = $("#the_road").val();
          var biaya_bbn = $("#biaya_bbn").val();
          var harga_tunai = $("#harga_tunai").val();
          var harga_off = $("#harga_off").val();
          var harga_on = $("#harga_on").val();
          var program_umum = $("#program_umum").val();
          var program_khusus = $("#program_khusus").val();
          $("#total_bayar").val(total);
          if (the_road == 'On The Road') {
            var total = parseInt(harga_on);
            showProgram();
          } else {
            var total = parseInt(harga_off);
            hideProgram();
            // var total = parseInt(harga_tunai)- parseInt(biaya_bbn);
          }
          $("#harga_tunai_r").val(convertToRupiah(total));
          $("#harga_tunai").val(total);
          get_total_ck();
        }

        function chooseitem2(id_kelurahan) {
          document.getElementById("id_kelurahan2").value = id_kelurahan;
          take_kec2();
          $("#Kelurahanmodal2").modal("hide");
        }

        function chooseitem(id_kelurahan) {
          document.getElementById("id_kelurahan").value = id_kelurahan;
          take_kec();
          $("#Kelurahanmodal").modal("hide");
        }

        function take_kec() {
          var id_kelurahan = $("#id_kelurahan").val();
          $.ajax({
            url: "<?php echo site_url('dealer/spk_fif/take_kec') ?>",
            type: "POST",
            data: "id_kelurahan=" + id_kelurahan,
            cache: false,
            success: function(msg) {
              data = msg.split("|");
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

        function take_kec2() {
          var id_kelurahan = $("#id_kelurahan2").val();
          $.ajax({
            url: "<?php echo site_url('dealer/spk_fif/take_kec') ?>",
            type: "POST",
            data: "id_kelurahan=" + id_kelurahan,
            cache: false,
            success: function(msg) {
              data = msg.split("|");
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

        function takes() {
          hide();
          take_kec();
          take_kec2();
          get_beli();
          get_total_ck();
          $("#tampil_alamat").hide();
        }

        function cek_umur() {
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

        function getWarna(id_warna = null) {
          //var nama_type = $(".modal_edit_detailkendaraan #kode_type").select2().find(":selected").data("nama_type");
          var id_tipe_kendaraan = $("#id_tipe_kendaraan").val();
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/spk_fif/getWarna'); ?>",
            type: "POST",
            data: "id_tipe_kendaraan=" + id_tipe_kendaraan +
              "&id_warna=" + id_warna,
            /* +"&keterangan="+keterangan
                  +"&tgl_pinjaman="+tgl_pinjaman,
               */
            cache: false,
            success: function(html) {
              $('#loading-status').hide();
              $('#id_warna').html(html);
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

        function getWarna2() {
          var mode = $("#warna_mode").val();
          if (mode == '') {
            getWarna();
          } else {
            //getWarna();    
            return false;
          }
        }

        function ambil_slot() {
          var id_customer = $("#id_customer").val();
          var id_tipe_kendaraan = $("#id_tipe_kendaraan").val();
          $.ajax({
            url: "<?php echo site_url('dealer/spk_fif/warna_slot') ?>",
            type: "POST",
            data: "id_customer=" + id_customer + "&id_tipe_kendaraan=" + id_tipe_kendaraan,
            cache: false,
            success: function(msg) {
              $("#id_warna").html(msg);
              take_harga();
            }
          })
        }

        function take_status() {
          var id_prospek_gc = $("#id_prospek_gc").val();
          $.ajax({
            url: "<?php echo site_url('dealer/spk_fif/cek_statushp') ?>",
            type: "POST",
            data: "id_prospek_gc=" + id_prospek_gc,
            cache: false,
            success: function(msg) {
              $("#status_nohp").html(msg);
            }
          })
        }

        function tampil_detail(a) {
          var value = {
            id: a
          }
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/spk_fif/getDetail') ?>",
            type: "POST",
            data: value,
            cache: false,
            success: function(html) {
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

        function tampil_detail2() {
          var value = {
            id: $("#no_spk_gc").val()
          }
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/spk_fif/getDetail2') ?>",
            type: "POST",
            data: value,
            cache: false,
            success: function(html) {
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

        function tampil_cash() {
          var value = {
            id: $("#id_prospek_gc").val()
          }
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/spk_fif/getDetail_cash') ?>",
            type: "POST",
            data: value,
            cache: false,
            success: function(html) {
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

        function tampil_kredit() {
          var value = {
            id: $("#id_prospek_gc").val()
          }
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/spk_fif/getDetail_kredit') ?>",
            type: "POST",
            data: value,
            cache: false,
            success: function(html) {
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
              "url": "<?php echo site_url('dealer/spk_fif/ajax_list') ?>",
              "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [{
              "targets": [0], //first column / numbering column
              "orderable": false, //set not orderable
            }, ],
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
              "url": "<?php echo site_url('dealer/spk_fif/ajax_list_2') ?>",
              "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [{
              "targets": [0], //first column / numbering column
              "orderable": false, //set not orderable
            }, ],
          });
        });
      </script>
      <script type="text/javascript">
        function addDetail() {
          var id_tipe_kendaraan = $("#id_tipe_kendaraan_gc").val();
          var id_warna = $("#id_warna_gc").val();
          var qty = $("#qty_gc").val();
          var tahun = $("#tahun_gc").val();
          var id_prospek_gc = $("#id_prospek_gc").val();
          $.ajax({
            url: "<?php echo site_url('dealer/prospek/addDetail') ?>",
            type: "POST",
            data: "id_tipe_kendaraan=" + id_tipe_kendaraan + "&id_warna=" + id_warna + "&qty=" + qty + "&tahun=" + tahun + "&id_prospek_gc=" + id_prospek_gc,
            cache: false,
            success: function(data) {
              if (data == 'nihil') {
                tampil_detail(id_prospek_gc);
              } else {
                alert(data);
              }
            }
          })
        }

        function delDetail(id) {
          var id_prospek_gc = $("#id_prospek_gc").val();
          $.ajax({
            url: "<?php echo site_url('dealer/prospek/delDetail') ?>",
            type: "POST",
            data: "id=" + id,
            cache: false,
            success: function(data) {
              if (data == 'nihil') {
                tampil_detail(id_prospek_gc);
              } else {
                alert(data);
              }
            }
          })
        }

        function edit_popup(id_gc) {
          $.ajax({
            url: "<?php echo site_url('dealer/prospek/edit_popup'); ?>",
            type: "POST",
            data: "id_gc=" + id_gc,
            cache: false,
            success: function(html) {
              $("#show_detail").html(html);
              getWarna_gc_edit();
            }
          });
        }

        function saveEdit(id) {
          var id_tipe_kendaraan = $("#id_tipe_kendaraan_edit").val();
          var id_warna = $("#id_warna_edit").val();
          var qty = $("#qty_edit").val();
          var tahun = $("#tahun_edit").val();
          var id_prospek_gc = $("#id_prospek_gc").val();
          $.ajax({
            url: "<?php echo site_url('dealer/prospek/saveEdit') ?>",
            type: "POST",
            data: "id_tipe_kendaraan=" + id_tipe_kendaraan + "&id_warna=" + id_warna + "&qty=" + qty + "&tahun=" + tahun + "&id_gc=" + id,
            cache: false,
            success: function(data) {
              if (data == 'nihil') {
                tampil_detail(id_prospek_gc);
                $("#modaall").modal("hide");
              } else {
                alert(data);
              }
            }
          })
        }
      </script>
      <script type="text/javascript">
        function getWarna_gc_edit() {
          var id_tipe_kendaraan = $("#id_tipe_kendaraan_edit").val();
          var id_warna = $("#id_warna_edit2").val();
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/prospek/getWarnaEdit'); ?>",
            type: "POST",
            data: "id_tipe_kendaraan=" + id_tipe_kendaraan + "&id_warna=" + id_warna,
            /*   +"&ksu="+ksu
               +"&keterangan="+keterangan
               +"&tgl_pinjaman="+tgl_pinjaman,
            */
            cache: false,
            success: function(html) {
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
        function getWarna_gc() {
          //var nama_type = $(".modal_edit_detailkendaraan #kode_type").select2().find(":selected").data("nama_type");
          var id_tipe_kendaraan = $("#id_tipe_kendaraan_gc").val();
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/prospek/getWarna'); ?>",
            type: "POST",
            data: "id_tipe_kendaraan=" + id_tipe_kendaraan,
            /*   +"&ksu="+ksu
               +"&keterangan="+keterangan
               +"&tgl_pinjaman="+tgl_pinjaman,
            */
            cache: false,
            success: function(html) {
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

        function getWarnaEdit_gc() {
          //var nama_type = $(".modal_edit_detailkendaraan #kode_type").select2().find(":selected").data("nama_type");
          var id_tipe_kendaraan = $("#id_tipe_kendaraan_gc").val();
          var id_warna_old = $("#id_warna_old").val();
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/prospek/getWarnaEdit'); ?>",
            type: "POST",
            data: "id_tipe_kendaraan=" + id_tipe_kendaraan +
              "&id_warna_old=" + id_warna_old,
            cache: false,
            success: function(html) {
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

        function getWarna2_gc() {
          var mode = $("#warna_mode").val();
          if (mode == '') {
            getWarna();
          } else {
            return false;
          }
        }

        function cek_road_gc() {
          var on_road_gc = $("#on_road_gc").val();
          var jumlah_gc = $("#jumlah_gc").val();
          for (i = 1; i <= jumlah_gc; i++) {
            var biaya_bbn_gc_on = $("#biaya_bbn_gc_on_" + i).val();
            var biaya_bbn_gc_off = $("#biaya_bbn_gc_off_" + i).val();
            if (on_road_gc == 'Off The Road') {
              $("#biaya_bbn_gc_" + i).val(biaya_bbn_gc_off);
            } else {
              $("#biaya_bbn_gc_" + i).val(biaya_bbn_gc_on);
            }
          }
          kali_gc_cash();
        }

        function kali_gc_kredit() {
          var jumlah_kredit = $("#jumlah_kredit").val();
          for (i = 1; i <= jumlah_kredit; i++) {
            var harga = $("#harga_jual_" + i).val();
            var biaya_bbn = $("#biaya_bbn_" + i).val();
            var nilai_voucher = $("#nilai_voucher_" + i).val();
            var voucher_tambahan = $("#voucher_tambahan_" + i).val();
            var qty = $("#qty_" + i).val();
            var dp_stor = $("#dp_stor_" + i).val();
            hasil = (Number(harga) + Number(biaya_bbn) - Number(nilai_voucher) - Number(voucher_tambahan) - Number(dp_stor)) * Number(qty);
            $("#total_" + i).val(hasil);
            // alert(harga);
            // alert(biaya_bbn);
            // alert(nilai_voucher);
            // alert(voucher_tambahan);
            // alert(qty);
            // alert(dp_setor);
          }
          cek_grand_kredit();
        }

        function cek_grand_kredit() {
          var jumlah_kredit = $("#jumlah_kredit").val();
          var ha = 0;
          for (i = 1; i <= jumlah_kredit; i++) {
            var total = $("#total_" + i).val();
            ha = Number(ha) + Number(total);
          }
          $("#g_total").val(ha);
        }

        function kali_gc_cash() {
          var jumlah_gc = $("#jumlah_gc").val();
          for (i = 1; i <= jumlah_gc; i++) {
            var harga = $("#harga_jual_gc_" + i).val();
            var biaya_bbn = $("#biaya_bbn_gc_" + i).val();
            var nilai_voucher = $("#nilai_voucher_gc_" + i).val();
            var voucher_tambahan = $("#voucher_tambahan_gc_" + i).val();
            var qty = $("#qty_gc_" + i).val();
            hasil = (Number(harga) + Number(biaya_bbn) - Number(nilai_voucher) - Number(voucher_tambahan)) * Number(qty);
            $("#total_gc_" + i).val(hasil);
          }
          cek_grand_gc();
        }

        function cek_grand_gc() {
          var jumlah_gc = $("#jumlah_gc").val();
          var ha = 0;
          for (i = 1; i <= jumlah_gc; i++) {
            var total = $("#total_gc_" + i).val();
            ha = Number(ha) + Number(total);
          }
          $("#g_total_gc").val(ha);
        }

        function cek_program_gc() {
          var id_sales_program_gc = $("#id_sales_program_gc").val();
          var beli = $("#beli").val();
          if (beli == 'Cash') {
            var total = "";
            var jumlah_gc = $("#jumlah_gc").val();
            var qty = 4;
            for (i = 1; i <= jumlah_gc; i++) {
              var id_tipe_kendaraan = $("#id_tipe_kendaraan_gc_" + i).val();
              var qty = $("#qty_gc_" + i).val();
              total = total + "|" + id_tipe_kendaraan;
            }
            $.ajax({
              url: "<?php echo site_url('dealer/spk_fif/cek_program_gc') ?>",
              type: "POST",
              data: "id_tipe_kendaraan=" + total + "&id_sales_program=" + id_sales_program_gc + "&beli=" + beli + "&qty=" + qty,
              cache: false,
              success: function(msg) {
                data = msg.split("|");
                for (i = 1; i <= jumlah_gc; i++) {
                  $("#nilai_voucher_gc_" + i).val(data[i + 1]);
                }
                //alert(data[1]);
              }
            })
          } else {
            var total = "";
            var jumlah_kredit = $("#jumlah_kredit").val();
            for (i = 1; i <= jumlah_kredit; i++) {
              var id_tipe_kendaraan = $("#id_tipe_kendaraan_" + i).val();
              var qty = $("#qty_" + i).val();
              total = total + "|" + id_tipe_kendaraan;
            }
            $.ajax({
              url: "<?php echo site_url('dealer/spk_fif/cek_program_gc') ?>",
              type: "POST",
              data: "id_tipe_kendaraan=" + total + "&id_sales_program=" + id_sales_program_gc + "&beli=" + beli + "&qty=" + qty,
              cache: false,
              success: function(msg) {
                data = msg.split("|");
                for (i = 1; i <= jumlah_kredit; i++) {
                  $("#nilai_voucher_" + i).val(data[i + 1]);
                }
                //alert(data[1]);
              }
            })
          }
          //alert(id_sales_program_gc);
        }
      </script>

      <script type="text/javascript">
        $(document).ready(function() {
          // $.ajax({
          //   beforeSend: function() {
          //     $('#showSPK').html('<tr><td colspan=8 style="font-size:12pt;text-align:center">Processing...</td></tr>');
          //   },
          //   url: "<?php echo site_url('dashboard/history_spk') ?>",
          //   type: "POST",
          //   data: "",
          //   cache: false,
          //   success: function(response) {
          //     $('#showSPK').html(response);
          //     datatables();
          //   }
          // })
        });
      </script>
      <script type="text/javascript">
        function datatables() {
          $('#table_ajax').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "scrollX": true,
            fixedHeader: true,
            "lengthMenu": [
              [10, 25, 50, 75, 100],
              [10, 25, 50, 75, 100]
            ],
            "autoWidth": true
          });
        }
      </script>