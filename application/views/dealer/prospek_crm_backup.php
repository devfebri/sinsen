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

<div class="modal" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false">
  <center>
    <div class="modal-header">

    </div>
    <div class="modal-body">
      <div id="ajax_loader">
        <img src="<?php echo base_url() ?>assets/loader.gif" style="margin-left: auto; margin-right: auto; width: 50px;">
      </div>
      <h3>Please Wait</h3>
    </div>
  </center>
</div>

<?php if (isset($_GET['id'])) { ?>

  <body onload="takes();cek_jenis();">
  <?php  } else { ?>

    <body onload="auto()">
    <?php   } ?>
    <?php if ($set == 'edit') : ?>

      <body onload="getWarna()">
      <?php endif ?>

      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <?php echo $title; ?>
          </h1>
          <ol class="breadcrumb">
            <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
            <li class="">Prospek</li>
            <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
          </ol>
        </section>
        <section class="content">
          <?php
          if ($set == "insert") {
            $form = '';
            if ($mode == 'insert') {
              $form = 'save';
            } elseif ($mode == 'edit') {
              $form = 'update';
            }
          ?>

            <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
            <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
            <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/prospek_crm">
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
                    <form class="form-horizontal" action="dealer/prospek_crm/<?= $form ?>" method="post" enctype="multipart/form-data" id="form_">
                      <div class="box-body">
                        <button class="btn btn-block btn-primary btn-flat" disabled> DATA PROSPEK </button> <br>
                        <?php if (isset($row)) { ?>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">ID Prospek</label>
                            <div class="col-md-4">
                              <input type="text" class="form-control" name="id" value="<?= isset($row) ? $row->id_prospek : '' ?>" readonly>
                            </div>
                          </div>
                        <?php } ?>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Prospek</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" name="tgl_prospek" value="<?= isset($row) ? $row->tgl_prospek : date('Y-m-d') ?>" readonly>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Sales *</label>
                          <div class="col-sm-4">
                            <select class="form-control select2" name="id_karyawan_dealer" required id="id_karyawan_dealer" onchange="take_sales()" :disabled="mode=='detail'">
                              <option value="">- choose -</option>
                              <?php
                              foreach ($dt_karyawan->result() as $val) {
                                $selected = isset($row) ? $row->id_karyawan_dealer == $val->id_karyawan_dealer ? 'selected' : '' : '';
                                echo "
                        <option value='$val->id_karyawan_dealer' $selected>$val->nama_lengkap ($val->nama_dealer)</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">FLP ID *</label>
                          <div class="col-sm-4">
                            <input type="hidden" class="form-control" id="nama_sales" name="nama_sales">
                            <input type="text" readonly class="form-control" required id="kode_sales" placeholder="FLP ID" name="id_flp_md">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Sales Sebelumnya</label>
                          <div class="col-sm-4">
                            <input type="text" disabled class="form-control" value="<?= isset($row) ? $row->nama_sales_sebelumnya : '' ?>">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">FLP ID *</label>
                          <div class="col-sm-4">
                            <input type="text" disabled class="form-control" value="<?= isset($row) ? $row->id_flp_md_sebelumnya : '' ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Leads ID</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" disabled value="<?= isset($row) ? $row->leads_id : '' ?>" readonly>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Customer Type</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" disabled value="<?= isset($row) ? $row->customerTypeDesc : '' ?>" readonly>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No. Rangka Sebelumnya</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" disabled value="<?= isset($row) ? $row->noFramePembelianSebelumnya : '' ?>" readonly>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Finance Company Sebelumnya</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" disabled value="<?= isset($row) ? $row->finance_company : '' ?>" readonly>
                          </div>
                        </div>
                        <button class="btn btn-block btn-primary btn-flat" disabled> DATA CUSTOMER </button> <br>
                        <!-- <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Prospek</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="id_prospek" readonly placeholder="ID Prospek" name="id_prospek">
                    <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="id_customer" readonly placeholder="ID Customer" name="id_customer">                    
                  </div>
                </div> -->
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Prioritas Prospect Customer</label>
                          <div class="col-sm-2">
                            <select class='form-control' name='prioritas_prospek' v-model='prioritas_prospek' :disabled="mode=='detail'">
                              <option value=''>- choose -</option>
                              <option value='1'>Yes</option>
                              <option value='0'>No</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen *</label>
                          <div class="col-sm-10">
                            <input type="text" required class="form-control" placeholder="Nama Konsumen" name="nama_konsumen" value="<?= isset($row) ? scriptToHtml($row->nama_konsumen) : '' ?>" :disabled="mode=='detail'">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No HP *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" maxlength="15" onkeypress="return number_only(event)" required placeholder="No HP" name="no_hp" value="<?= isset($row) ? scriptToHtml($row->no_hp) : '' ?>" :disabled="mode=='detail'">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="status_nohp" required :disabled="mode=='detail'">
                              <option value="">- choose -</option>
                              <?php
                              foreach ($dt_status_hp->result() as $val) {
                                $selected = isset($row) ? $val->id_status_hp == $row->status_nohp ? 'selected' : '' : '';
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
                            <input type="text" class="form-control" onkeypress="return number_only(event)" maxlength="15" placeholder="No Telp" name="no_telp" value="<?= isset($row) ? $row->no_telp : '' ?>" :disabled="mode=='detail'">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                          <div class="col-sm-4">
                            <input type="email" class="form-control" placeholder="Email" name="email" maxlength="100" value="<?= isset($row) ? $row->email : '' ?>" :disabled="mode=='detail'">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan (KTP) *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="pekerjaan" onchange="pekerjaan_lain()" required required :disabled="mode=='detail'">
                              <option value="">- choose -</option>
                              <?php
                              $pekerjaan = '';
                              foreach ($dt_pekerjaan->result() as $val) {
                                $selected = isset($row) ? $val->id_pekerjaan == $row->pekerjaan ? 'selected' : '' : '';
                                if (isset($row)) {
                                  if ($val->id_pekerjaan == $row->pekerjaan) {
                                    $pekerjaan = $val->id_pekerjaan;
                                  }
                                }

                                echo "
                        <option value='$val->id_pekerjaan' $selected>$val->pekerjaan</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kelamin *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="jenis_kelamin" required v-model="jenis_kelamin" :disabled="mode=='detail'">
                              <option value="">- choose -</option>
                              <option>Pria</option>
                              <option>Wanita</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail4" class="col-sm-2 control-label">Pekerjaan Saat Ini *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="sub_pekerjaan" onchange="sub_job()" required :disabled="mode=='detail'">
                              <option value="">- choose -</option>
                              <?php
                              foreach ($dt_subpekerjaan->result() as $val) {
                                $selected = isset($row) ? $val->id_sub_pekerjaan == $row->sub_pekerjaan ? 'selected' : '' : '';
                                echo "<option value='$val->id_sub_pekerjaan' $selected obj='$val->required_instansi'>$val->sub_pekerjaan</option>";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group" id="lain2">
                          <label for="inputEmail3" class="col-sm-2 control-label"></label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" maxlength="100" placeholder="Sebutkan" name="lain" value="<?= isset($row) ? $row->pekerjaan_lain : '' ?>" :disabled="mode=='detail'">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kewarganegaraan</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="jenis_wn" v-model='jenis_wn' :disabled="mode=='detail'">
                              <option value="">- choose -</option>
                              <option>WNA</option>
                              <option>WNI</option>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No KTP/KITAS *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" onkeypress="return number_only(event)" placeholder="No KTP/KITAS" name="no_ktp" minlength=16 maxlength=16 required value="<?= isset($row) ? $row->no_ktp : '' ?>" :disabled="mode=='detail'">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No KK</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" maxlength="16" onkeypress="return number_only(event)" placeholder="No KK" name="no_kk" value="<?= isset($row) ? $row->no_kk : '' ?>" :disabled="mode=='detail'">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" onkeypress="return number_only(event)" placeholder="No NPWP" name="no_npwp" value="<?= isset($row) ? $row->no_npwp : '' ?>" :disabled="mode=='detail'">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tempat Lahir *</label>
                          <div class="col-sm-4">
                            <input type="text" id="tempat_lahir" class="form-control" placeholder="Tempat Lahir" required name="tempat_lahir" value="<?= isset($row) ? scriptToHtml($row->tempat_lahir) : '' ?>" :disabled="mode=='detail'">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl.Lahir *</label>
                          <div class="col-sm-4">
                            <input type="text" id="tanggal4" class="form-control tgl_lahir" placeholder="Tgl.Lahir" required name="tgl_lahir" onchange="cek_umur()" value="<?= isset($row) ? $row->tgl_lahir : '' ?>" :disabled="mode=='detail'">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan *</label>
                          <div class="col-sm-4">
                            <input type="hidden" readonly name="id_kelurahan" id="id_kelurahan" value="<?= isset($row) ? $row->id_kelurahan : '' ?>">
                            <input type="text" onpaste="return false" autocomplete="off" required onkeypress="return nihil(event)" name="kelurahan" data-toggle="modal" placeholder="Kelurahan" class="form-control" id="kelurahan" onclick="showModalKelurahan()" :disabled="mode=='detail'">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan *</label>
                          <div class="col-sm-4">
                            <input type="hidden" class="form-control" id="id_kecamatan" name="id_kecamatan" value="<?= isset($row) ? $row->id_kecamatan : '' ?>">
                            <input type="text" readonly class="form-control" id="kecamatan" placeholder="Kecamatan" name="kecamatan" value="<?= isset($row) ? $row->kec : '' ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten/Kota *</label>
                          <div class="col-sm-4">
                            <input type="hidden" class="form-control" id="id_kabupaten" name="id_kabupaten" value="<?= isset($row) ? $row->id_kabupaten : '' ?>">
                            <input type="text" readonly class="form-control" id="kabupaten" placeholder="Kabupaten" name="kabupaten" value="<?= isset($row) ? $row->kab : '' ?>">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Provinsi *</label>
                          <div class="col-sm-4">
                            <input type="hidden" class="form-control" id="id_provinsi" name="id_provinsi" value="<?= isset($row) ? $row->id_provinsi : '' ?>">
                            <input type="text" readonly class="form-control" id="provinsi" placeholder="Provinsi" name="provinsi" value="<?= isset($row) ? $row->prov : '' ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat *</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="100" placeholder="Alamat" required name="alamat" value="<?= isset($row) ? $row->alamat : '' ?>" :disabled="mode=='detail'">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kodepos</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" onkeypress="return number_only(event)" placeholder="Kodepos" name="kodepos" id="kode_pos" value="<?= isset($row) ? $row->kodepos : '' ?>" :disabled="mode=='detail'">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Agama *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="agama" required :disabled="mode=='detail'">
                              <option value="">- choose -</option>
                              <?php
                              foreach ($dt_agama->result() as $val) {
                                $selected = isset($row) ? $val->id_agama == $row->agama ? 'selected' : '' : '';

                                echo "
                        <option value='$val->id_agama' $selected>$val->agama</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Jenis Motor yg dimiliki sekarang</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="jenis_sebelumnya" :disabled="mode=='detail'">
                              <option value="">- choose -</option>
                              <?php
                              foreach ($dt_jenis_sebelumnya->result() as $val) {
                                $selected = isset($row) ? $val->id_jenis_sebelumnya == $row->jenis_sebelumnya ? 'selected' : '' : '';
                                echo "
                        <option value='$val->id_jenis_sebelumnya' $selected>$val->jenis_sebelumnya</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Merk Motor yg dimiliki sekarang</label>
                          <div class="col-sm-4">
                            <select class="form-control" id="merk_sebelumnya" name="merk_sebelumnya" onchange="cek_merk_sebelumnya()" :disabled="mode=='detail'">
                              <option value="">- choose -</option>
                              <?php
                              foreach ($dt_merk_sebelumnya->result() as $val) {
                                $selected = isset($row) ? $val->id_merk_sebelumnya == $row->merk_sebelumnya ? 'selected' : '' : '';

                                echo "
                        <option value='$val->id_merk_sebelumnya' $selected>$val->merk_sebelumnya</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label"></label>
                          <div class="col-sm-4"></div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor yg dimiliki sekarang <span id="req_tipe_sebelumnya"> *</span></label>
                            <div class="col-sm-4">
                              <input type="text" oninput="this.value = this.value.toUpperCase()" id="tipe_sebelumnya" name="tipe_sebelumnya" value="" class="form-control" required="">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Yang menggunakan sepeda motor</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="pemakai_motor" v-model="pemakai_motor" :disabled="mode=='detail'">
                              <option value="">- choose -</option>
                              <option>Saya Sendiri</option>
                              <option>Anak</option>
                              <option>Pasangan Suami/Istri</option>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Status Prospek</label>
                          <div class="col-sm-4">
                            <input class="form-control" name="status_prospek_header" required v-model='status_prospek' readonly>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Longitude *</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" name="longitude" value="<?= isset($row) ? $row->longitude : '' ?>" :disabled="mode=='detail'" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Latitude *</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" name="latitude" value="<?= isset($row) ? $row->latitude : '' ?>" :disabled="mode=='detail'" required>
                          </div>
                        </div>
                        <div id="dataInstansi">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Nama Instansi/Usaha *</label>
                            <div class="col-md-4">
                              <input type="text" class="form-control" name="nama_usaha" value="<?= isset($row) ? scriptToHtml($row->nama_tempat_usaha) : '' ?>" :disabled="mode=='detail'">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Alamat Kantor *</label>
                            <div class="col-md-4">
                              <input type="text" class="form-control" name="alamat_kantor" value="<?= isset($row) ? $row->alamat_kantor : '' ?>" :disabled="mode=='detail'">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">No Telp Kantor *</label>
                            <div class="col-md-4">
                              <input type="text" class="form-control" name="no_telp_kantor" onkeypress="return number_only(event)" value="<?= isset($row) ? $row->no_telp_kantor : '' ?>" :disabled="mode=='detail'">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Kantor *</label>
                            <div class="col-md-4">
                              <input type="text" class="form-control" id="kelurahan_kantor" id='kelurahan_kantor' onpaste="return false" autocomplete="off" onkeypress="return nihil(event)" placeholder='Klik Untuk Memilih' onclick="showModalKelurahan('kantor')" value="<?= isset($row) ? $row->kel_kantor : '' ?>">
                              <input type='hidden' id='id_kelurahan_kantor' name='id_kelurahan_kantor' value="<?= isset($row) ? $row->id_kelurahan_kantor : '' ?>">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Kantor *</label>
                            <div class="col-md-4">
                              <input type="text" class="form-control" id="kecamatan_kantor" readonly placeholder='Klik Untuk Memilih' onclick="showModalKelurahan('kantor')" value="<?= isset($row) ? $row->kec_kantor : '' ?>">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten Kantor *</label>
                            <div class="col-md-4">
                              <input type="text" class="form-control" id="kabupaten_kantor" readonly placeholder='Klik Untuk Memilih' onclick="showModalKelurahan('kantor')" value="<?= isset($row) ? $row->kab_kantor : '' ?>">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Kantor *</label>
                            <div class="col-md-4">
                              <input type="text" class="form-control" id="provinsi_kantor" readonly placeholder='Klik Untuk Memilih' onclick="showModalKelurahan('kantor')" value="<?= isset($row) ? $row->prov_kantor : '' ?>">
                            </div>
                          </div>
                        </div>
                        <script>
                          var kelurahan_untuk = '';

                          function pilihKelurahan(params) {
                            // showLoader = setTimeout("$('#pleaseWaitDialog').modal();", 5000);
                            // $('#pleaseWaitDialog').modal();
                            if (kelurahan_untuk === 'kantor') {
                              $('#id_kelurahan_kantor').val(params.id_kelurahan)
                              $('#kelurahan_kantor').val(params.kelurahan)
                              $('#kecamatan_kantor').val(params.kecamatan)
                              $('#kabupaten_kantor').val(params.kabupaten)
                              $('#provinsi_kantor').val(params.provinsi)
                            } else if (kelurahan_untuk === 'customer') {
                              $('#id_kelurahan').val(params.id_kelurahan)
                              $('#kelurahan').val(params.kelurahan)
                              $('#id_kecamatan').val(params.id_kecamatan)
                              $('#kecamatan').val(params.kecamatan)
                              $('#id_kabupaten').val(params.id_kabupaten)
                              $('#kabupaten').val(params.kabupaten)
                              $('#id_provinsi').val(params.id_provinsi)
                              $('#provinsi').val(params.provinsi)
                            }
                            // clearTimeout(showLoader);
                            // $('#pleaseWaitDialog').modal('hide');
                          }
                        </script>

                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Jenis Customer *</label>
                          <div class="col-md-4">
                            <select name="jenis_customer" class="form-control" v-model='jenis_customer' :disabled="mode=='detail'" required>
                              <option value="">--choose--</option>
                              <option value="regular">Regular</option>
                              <option value="group_customer">Group Customer</option>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Sumber Prospek *</label>
                          <div class="col-md-4">
                            <select name="sumber_prospek" class="form-control" v-model='sumber_prospek' required <?= isset($row) ? $row->platformData != 'D' ? 'disabled' : '' : '' ?>>
                              <option value="">--choose--</option>
                              <?php
                              foreach ($set_sumber_prospek as $rw) : ?>
                                <option value="<?php echo $rw->id_dms ?>"><?php echo $rw->description ?></option>
                              <?php endforeach ?>
                              <!-- <option value="0001">Pameran (Joint Promo, Grebek Pasar, Alfamart, Indomart, Mall dll)</option>
                              <option value="0002">Showroom Event</option>
                              <option value="0003">Roadshow</option>
                              <option value="0004">Walk in</option>
                              <option value="0005">Customer RO H1</option>
                              <option value="0006">Customer RO H23</option>
                              <option value="0007">Website</option>
                              <option value="0008">Social Media</option>
                              <option value="0009">External Parties (Leasing, Insurance)</option>
                              <option value="0010">Mobile Apps MD/Dealer</option>
                              <option value="9999">Others</option> -->
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Test Ride Preference *</label>
                          <div class="col-md-4">
                            <select name="test_ride_preference" class="form-control" v-model='test_ride_preference' :disabled="mode=='detail'" required>
                              <option value="">--choose--</option>
                              <option value="1">Yes</option>
                              <option value="0">No</option>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Rencana Pembayaran *</label>
                          <div class="col-md-4">
                            <select id='rencana_pembayaran' name="rencana_pembayaran" class="form-control" v-model='rencana_pembayaran' :disabled="mode=='detail'" required>
                              <option value="">--choose--</option>
                              <option value="cash">Cash</option>
                              <option value="kredit">Kredit</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Catatan</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" name="catatan" value="<?= isset($row) ? scriptToHtml($row->catatan) : '' ?>" :disabled="mode=='detail'">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Keterangan Not Deal</label>
                          <div class="col-md-4">
                            <select name="keterangan_not_deal" class="form-control select2" v-model='keterangan_not_deal' :disabled="mode=='detail'">
                              <option value="">--choose--</option>
                              <option>Sudah Beli di Dealer Lain</option>
                              <option>Sudah Beli di Kompetitor</option>
                              <option>Tidak Ada Stok Di Dealer</option>
                              <option>Ditolak Oleh Leasing</option>
                              <option>Lainnya</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">ID Event</label>
                          <div class="col-sm-4">
                            <select name="id_event" id="id_event" onchange="setEvent()" class="form-control select2" :disabled="mode=='detail'">
                              <option value="">--choose-</option>
                              <?php foreach ($this->m_prospek->getEvent()->result() as $rs) :
                                $selected = isset($row) ? $rs->id_event == $row->id_event ? 'selected' : '' : '';
                              ?>
                                <option value="<?= $rs->id_event ?>" <?= $selected ?> data-nama_event="<?= $rs->nama_event ?>"><?= $rs->id_event . ' | ' . $rs->nama_event ?></option>
                              <?php endforeach ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Event</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id='nama_event' readonly value="<?= isset($row) ? $row->nama_event : '' ?>" :disabled="mode=='detail'">
                          </div>
                          <script>
                            function setEvent() {
                              var nama_event = $("#id_event").select2().find(":selected").data("nama_event");
                              $('#nama_event').val(nama_event);

                            }
                          </script>
                        </div>
                        <button class="btn btn-block btn-warning btn-flat" disabled> DATA KENDARAAN </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Type *</label>
                          <div class="col-sm-4">
                            <select class="form-control select2" name="id_tipe_kendaraan" id="id_tipe_kendaraan" required onchange="getWarna()" :disabled="mode=='detail'">
                              <option value="">- choose -</option>
                              <?php
                              foreach ($dt_tipe->result() as $val) {
                                $selected = isset($row) ? strtolower($val->id_tipe_kendaraan) == strtolower($row->id_tipe_kendaraan) ? 'selected' : '' : '';
                                echo "
                        <option value='$val->id_tipe_kendaraan' $selected>$val->id_tipe_kendaraan | $val->tipe_ahm</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>


                          <label for="inputEmail3" class="col-sm-2 control-label">Warna *</label>
                          <div class="col-sm-4">
                            <select onchange="getProgramUmum()" class="form-control select2" name="id_warna" id="id_warna" :disabled="mode=='detail'" required>
                              <?php
                              if (isset($row)) {
                                foreach ($dt_warna->result() as $val) {
                                  $selected = isset($row) ? strtolower($val->id_warna) == strtolower($row->id_warna) ? 'selected' : '' : '';
                                  echo "
                                    <option value='$val->id_warna' $selected>$val->id_warna | $val->warna</option>;
                                  ";
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <!-- <button class="btn btn-block btn-warning btn-flat" disabled> SALES PROGRAM </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Program Utama</label>
                          <div class="col-sm-4">
                            <select class="form-control select2" name="program_utama" id="program_utama" onchange="getProgramGabungan()" :disabled="mode=='detail'">
                              <option value="">- choose -</option>
                              <?php if (isset($row)) {
                                echo " <option value='$row->program_umum'>$row->program_umum</option>";
                              } ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Program Gabungan</label>
                          <div class="col-sm-4">
                            <select class="form-control select2" name="program_gabungan" id="program_gabungan" :disabled="mode=='detail'">
                              <?php if (isset($row)) {
                                echo " <option value='$row->program_gabungan'>$row->program_gabungan</option>";
                              } ?>
                            </select>
                          </div>
                        </div> -->
                        <button class="btn btn-block btn-danger btn-flat" disabled> FOLLOW UP/LIST APPOINTMENT </button> <br>
                        <div class="col-md-12">
                          <table class="table table-bordered">
                          <thead>
                        <th>Tgl Follow UP</th>
                        <th>Waktu Follow UP</th>
                        <th>Keterangan</th>
                        <th>Media Komunikasi Follow UP</th>
                        <th>Status Komunikasi Fol Up</th>
                        <th>Tgl Next Follow UP</th>
                        <th>Keterangan Next Fol. Up</th>
                        <th>Status Prospek</th>
                        <th>Kategori</th>
                        <th>Hasil Status Fol. Up</th>
                        <th>Alasan Not Prospect/Not Deal</th>
                        <th width="5%" style="text-align: center;" v-if="mode=='insert' || mode=='edit'"></th>
                      </thead>
                      <tbody>
                        <tr v-for="(dl, index) of details">
                          <td>{{dl.tgl_fol_up}}
                            <input type="hidden" name="tgl_fol_up[]" v-model="dl.tgl_fol_up">
                          </td>
                          <td>{{dl.waktu_fol_up}}
                            <input type="hidden" name="waktu_fol_up[]" v-model="dl.waktu_fol_up">
                          </td>
                          <td>{{dl.keterangan}}
                            <input type="hidden" name="keterangan[]" v-model="dl.keterangan">
                          </td>
                          <td>{{dl.desc_metode_fol_up}}
                            <input type="hidden" name="metode_fol_up[]" v-model="dl.metode_fol_up">
                          </td>
                          <td>{{dl.status_fu}}
                            <input type="hidden" name="id_status_fu[]" v-model="dl.id_status_fu">
                          </td>
                          <td>{{dl.tgl_next_fol_up }}
                            <input type="hidden" name="tgl_next_fol_up[]" v-model="dl.tgl_next_fol_up">
                          </td>
                          <td>{{dl.keterangan_next_fu}}
                            <input type="hidden" name="keterangan_next_fu[]" v-model="dl.keterangan_next_fu">
                          </td>
                          <td>{{dl.status_prospek}}
                            <input type="hidden" name="status_prospek[]" v-model="dl.status_prospek">
                          </td>
                          <td>{{dl.kategori_status_komunikasi }}
                            <input type="hidden" name="id_kategori_status_komunikasi[]" v-model="dl.id_kategori_status_komunikasi">
                          </td>
                          <td>{{dl.deskripsiHasilStatusFollowUp }}
                            <input type="hidden" name="kodeHasilStatusFollowUp[]" v-model="dl.kodeHasilStatusFollowUp">
                          </td>
                          <td>
                            {{dl.alasanNotProspectNotDeal}}
                            <div v-if="kodeAlasanNotProspectNotDeal='5'"><i>{{dl.alasanNotProspectNotDealLainnya}}</i></div>
                            <input type="hidden" name="kodeAlasanNotProspectNotDeal[]" v-model="dl.kodeAlasanNotProspectNotDeal">
                            <input type="hidden" name="alasanNotProspectNotDealLainnya[]" v-model="dl.alasanNotProspectNotDealLainnya">
                          </td>
                          <td></td>
                        </tr>
                      </tbody>
                      <tfoot v-if="mode=='insert' || mode=='edit'">
                        <tr>
                          <td>
                            <input type="text" class="form-control isi datepicker" id="tgl_fol_up" value="<?= tanggal() ?>">
                          </td>
                          <td>
                            <input type="time" class="form-control isi" id="waktu_fol_up" v-model="detail.waktu_fol_up" value="<?= jam() ?>">
                          </td>
                          <td>
                            <input type="text" class="form-control isi" v-model="detail.keterangan">
                          </td>
                          <td>
                            <select id="metode_fol_up" class="form-control" v-model="detail.metode_fol_up" @change.prevent="setOptionStatusFollowUp">
                              <?php $media = $this->m_prospek->getMediaKomunikasi(); ?>
                              <option value="">--choose--</option>
                              <?php foreach ($media as $sf) { ?>
                                <option value="<?= $sf->id_media_kontak_fu ?>"><?= $sf->media_kontak_fu ?></option>
                              <?php } ?>
                            </select>
                          </td>
                          <td>
                            <select class="form-control" v-model="detail.option_status_fu">
                              <option v-for="option in options_status_fu" v-bind:value="option">
                                {{ option.text }}
                              </option>
                            </select>
                          </td>
                          <td>
                            <date-picker v-model="detail.tgl_next_fol_up"></date-picker>
                          </td>
                          <td>
                            <input type="text" class="form-control isi" v-model="detail.keterangan_next_fu">
                          </td>
                          <td>
                            {{detail.status_prospek}}
                          </td>
                          <td>{{detail.kategori_status_komunikasi}}</td>
                          <td>
                            <select class="form-control" v-model="detail.kodeHasilStatusFollowUp" v-if="detail.id_kategori_status_komunikasi=='4'" id="kodeHasilStatusFollowUp">
                              <?php $hasil = $this->m_prospek->getHasilStatusFollowUp(); ?>
                              <?php foreach ($hasil as $sf) { ?>
                                <option value="<?= $sf->kodeHasilStatusFollowUp ?>"><?= $sf->deskripsiHasilStatusFollowUp ?></option>
                              <?php } ?>
                            </select>
                          </td>
                          <td>
                            <select id="kodeAlasanNotProspectNotDeal" class="form-control" v-model="detail.kodeAlasanNotProspectNotDeal" v-if="detail.kodeHasilStatusFollowUp==2 || detail.kodeHasilStatusFollowUp==4">
                              <?php $hasil = $this->m_prospek->getAlasanNotProspectNotDeal(); ?>
                              <option value="">--choose--</option>
                              <?php foreach ($hasil as $sf) { ?>
                                <option value="<?= $sf->kodeAlasanNotProspectNotDeal ?>"><?= $sf->alasanNotProspectNotDeal ?></option>
                              <?php } ?>
                            </select>
                            <br>
                            <input v-if="detail.kodeAlasanNotProspectNotDeal==5" type="text" class="form-control isi" v-model="detail.alasanNotProspectNotDealLainnya">
                          </td>
                          <td align="center">
                            <button type="button" @click.prevent="addDetails()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i></button>
                          </td>
                        </tr>
                      </tfoot>
                          </table>
                        </div>
                        <!-- <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Atur Tanggal *</label>
                  <div class="col-sm-4">                    
                    <input type="text" id="tanggal2" name="atur_tgl" class="form-control" required>                                                   
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Atur Jam *</label>
                  <div class="col-sm-4">                    
                    <input type="text" id="jam2" name="atur_jam" class="form-control" required>                                                   
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan Follow Up</label>
                  <div class="col-sm-10">                    
                    <input type="text" id="keterangan_fol" name="keterangan_fol" class="form-control">                                                   
                  </div>                                    
                </div> -->

                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-12" align='center'>
                          <?php if ($mode == 'insert') { ?>
                            <button type="button" onclick="saveData()" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                          <?php } elseif ($mode == 'edit') { ?>
                            <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="save" class="btn btn-info btn-flat">Update All</button>
                          <?php } ?>
                          <!-- <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button> -->
                        </div>
                      </div><!-- /.box-footer -->
                    </form>
                  </div>
                </div>
              </div>
            </div><!-- /.box -->
            <?php
            $data['data'] = ['kelurahan'];
            $this->load->view('dealer/h2_api', $data); ?>
            <script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>
            <script>
              function saveData() {
                $('#form_').validate({
                  highlight: function(element, errorClass, validClass) {
                    var elem = $(element);
                    if (elem.hasClass("select2-hidden-accessible")) {
                      $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
                    } else {
                      $(element).parents('.form-input').addClass('has-error');
                    }
                  },
                  unhighlight: function(element, errorClass, validClass) {
                    var elem = $(element);
                    if (elem.hasClass("select2-hidden-accessible")) {
                      $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
                    } else {
                      $(element).parents('.form-input').removeClass('has-error');
                    }
                  },
                  errorPlacement: function(error, element) {
                    var elem = $(element);
                    if (elem.hasClass("select2-hidden-accessible")) {
                      element = $("#select2-" + elem.attr("id") + "-container").parent();
                      error.insertAfter(element);
                    } else {
                      error.insertAfter(element);
                    }
                  }
                })
                if ($('#form_').valid()) // check if form is valid
                {
                  if (confirm("Apakah anda yakin ?") == true) {
                    var values = new FormData($('#form_')[0]);
                    values.append('details', JSON.stringify(form_.details));
                    $.ajax({
                      beforeSend: function() {
                        $('#btnUpdateData').html('<i class="fa fa-spinner fa-spin"></i> Process');
                        $('.btnUpdate').attr('disabled', true);
                      },
                      enctype: 'multipart/form-data',
                      url: '<?= base_url('dealer/prospek_crm/' . $form) ?>',
                      type: "POST",
                      data: values,
                      processData: false,
                      contentType: false,
                      cache: false,
                      dataType: 'JSON',
                      success: function(response) {
                        if (response.status == 'sukses') {
                          window.location = response.link;
                        } else {
                          alert(response.pesan);
                          $('.btnUpdate').attr('disabled', false);
                        }
                        $('#btnUpdateData').html('Updated Data');
                      },
                      error: function() {
                        alert("Something Went Wrong !");
                        $('#btnUpdateData').html('Updated Data');
                        $('.btnUpdate').attr('disabled', false);

                      }
                    });
                  }
                } else {
                  alert('Silahkan isi field required !')
                }
              }
              Vue.component('date-picker', {
                template: '<input type="text" v-datepicker class="form-control isi_combo" :value="value" @input="update($event.target.value)">',
                directives: {
                  datepicker: {
                    inserted(el, binding, vNode) {
                      $(el).datepicker({
                        autoclose: true,
                        format: 'yyyy-mm-dd',
                        todayHighlight: false,
                      }).on('changeDate', function(e) {
                        vNode.context.$emit('input', e.format(0))
                      })
                    }
                  }
                },
                props: ['value'],
                methods: {
                  update(v) {
                    this.$emit('input', v)
                  }
                }
              })
            </script>
            <script>
              function cek_merk_sebelumnya() {
                var value = $("#merk_sebelumnya").val();
                console.log(value);

                if (value == '6' || value == '') {
                  $("#tipe_sebelumnya").prop('disabled', true);
                  $("#req_tipe_sebelumnya").text("");
                  $("#tipe_sebelumnya").prop("required", false);
                } else {
                  $("#tipe_sebelumnya").prop('disabled', false);
                  $("#req_tipe_sebelumnya").text(" *");
                  $("#tipe_sebelumnya").prop("required", true);
                }
              
              }
              $(document).ready(function() {
                $('#lain2').hide();
                $('#dataInstansi').hide();

                var job = $('select[name="pekerjaan"]').val();
                var sub = $('select[name="sub_pekerjaan"]').val();
                if (job == 11 && sub == 101 || sub == 101) {
                  $('#lain2').show();
                  $('[name="lain"]').prop('required', true);
                }

                var sub = $('select[name="sub_pekerjaan"]').children("option:selected").attr('obj');
                if (sub == 1) {
                  $('#dataInstansi').show();
                  $('[name="nama_usaha"]').prop('required', true);
                  $('[name="alamat_kantor"]').prop('required', true);
                  $('[name="no_telp_kantor"]').prop('required', true);
                  $('#kelurahan_kantor').prop('required', true);
                }
              })

              function pekerjaan_lain() {
                var job = $('select[name="pekerjaan"]').val();
                var sub = $('select[name="sub_pekerjaan"]').val();
                is_show_pekerjaan_lain(job, sub);
              }

              function is_show_pekerjaan_lain(id_job, id_sub) {
                if (id_job != '' && id_sub != '') {
                  if (id_job == 11 && id_sub == 101 || id_sub == 101) {
                    $('#lain2').show();
                    $('[name="lain"]').prop('required', true);
                  } else {
                    $('[name="lain"]').val('');
                    $('[name="lain"]').prop('required', false);
                    $('#lain2').hide();
                  }
                }
              }

              function sub_job() {
                var job = $('select[name="pekerjaan"]').val();
                var sub = $('select[name="sub_pekerjaan"]').val();
                var val = $('select[name="sub_pekerjaan"]').children("option:selected").attr('obj');
                is_show_pekerjaan_lain(job, sub);

                if (val == 1) {
                  $('#dataInstansi').show();
                  $('[name="nama_usaha"]').prop('required', true);
                  $('[name="alamat_kantor"]').prop('required', true);
                  $('[name="no_telp_kantor"]').prop('required', true);
                  $('#kelurahan_kantor').prop('required', true);
                } else {
                  $('#dataInstansi').hide();
                  $('[name="nama_usaha"]').prop('required', false);
                  $('[name="alamat_kantor"]').prop('required', false);
                  $('[name="no_telp_kantor"]').prop('required', false);
                  $('#kelurahan_kantor').prop('required', false);
                  $('[name="nama_usaha"]').val('');
                  $('[name="alamat_kantor"]').val('');
                  $('[name="no_telp_kantor"]').val('');
                  $('[name="id_kelurahan_kantor"]').val('');
                  $('#kelurahan_kantor').val('');
                  $('#kabupaten_kantor').val('');
                  $('#kecamatan_kantor').val('');
                  $('#provinsi_kantor').val('');
                }
              }

              var form_ = new Vue({
                el: '#form_',
                data: {
                  mode: '<?= $mode ?>',
                  prioritas_prospek: '<?= isset($row) ? $row->prioritas_prospek : '' ?>',
                  jenis_kelamin: '<?= isset($row) ? $row->jenis_kelamin : '' ?>',
                  jenis_wn: '<?= isset($row) ? $row->jenis_wn : '' ?>',
                  pemakai_motor: '<?= isset($row) ? $row->pemakai_motor : '' ?>',
                  status_prospek: '<?= isset($row) ? $row->status_prospek : '' ?>',
                  jenis_customer: '<?= isset($row) ? $row->jenis_customer : '' ?>',
                  sumber_prospek: '<?= isset($row) ? $row->sumber_prospek : '' ?>',
                  test_ride_preference: '<?= isset($row) ? $row->test_ride_preference : '' ?>',
                  rencana_pembayaran: '<?= isset($row) ? $row->rencana_pembayaran : '' ?>',
                  keterangan_not_deal: '<?= isset($row) ? $row->keterangan_not_deal : '' ?>',
                  detail: {
                    tgl_fol_up: '<?= tanggal() ?>',
                    waktu_fol_up: '<?= jam() ?>',
                    metode_fol_up: '',
                    keterangan: '',
                    tgl_next_fol_up: '',
                    tgl_next_fol_up: '',
                    keterangan_next_fu: '',
                    id_status_fu: '',
                    status_fu: '',
                    id_kategori_status_komunikasi: '',
                    kategori_status_komunikasi: '',
                    kodeHasilStatusFollowUp: '',
                    kodeAlasanNotProspectNotDeal: '',
                    keteranganAlasanNotProspectNotDeal: '',
                    alasanNotProspectNotDealLainnya: '',
                    option_status_fu: {
                      id: '',
                      text: '',
                      id_kategori: '',
                      kategori: '',
                    }
                  },
                  details: <?= isset($details) ? json_encode($details) : '[]' ?>,
                  options_status_fu: [],
                },
                methods: {
                  clearFolUp: function() {
                    $('#tgl_fol_up').val('');
                    this.detail = {
                      tgl_fol_up: '',
                      waktu_fol_up: '',
                      metode_fol_up: '',
                      keterangan: ''
                    }
                  },
                  addDetails: function() {
                    this.detail.tgl_fol_up = $('#tgl_fol_up').val();
                    this.detail.deskripsiHasilStatusFollowUp = $("#kodeHasilStatusFollowUp option:selected").text();
                    this.detail.alasanNotProspectNotDeal = $("#kodeAlasanNotProspectNotDeal option:selected").text();
                    if (this.detail.tgl_fol_up < this.tgl_assign) {
                      alert("Tgl. Follow Up Tidak Boleh Lebih Kecil Dari Tgl. Assigned Dealer");
                      return false;
                    }
                    if (this.detail.id_kategori_status_komunikasi == '4') {
                      if (this.detail.kodeHasilStatusFollowUp == '') {
                        alert("Silahkan Tentukan Hasil Status Fol. Up");
                        return false;
                      }
                    }

                    if (this.detail.metode_fol_up == '') {
                      alert("Silahkan Tentukan Media Komunikasi Follow Up");
                      return false;
                    }
                    if (this.detail.id_status_fu == '') {
                      alert("Silahkan Tentukan Status Komunikasi Follow Up");
                      return false;
                    }
                    if (this.detail.kodeHasilStatusFollowUp == '4') {
                      if (this.detail.kodeAlasanNotProspectNotDeal == '') {
                        alert("Silahkan Tentukan Alasan Not Prospect/Not Deal");
                        return false;
                      }
                    }
                    if (this.detail.kodeAlasanNotProspectNotDeal == '5' && this.detail.alasanNotProspectNotDealLainnya=='') {
                      alert("Silahkan Tentukan Alasan Lainnya");
                      return false;
                    }
                    if (this.detail.kodeHasilStatusFollowUp == '1') {
                      if (this.detail.tgl_next_fol_up == '') {
                        alert("Silahkan Tentukan Tgl. Next Follow Up");
                        return false;
                      }
                    }
                    if (this.detail.tgl_next_fol_up != '') {
                      if (this.detail.tgl_fol_up > this.detail.tgl_next_fol_up) {
                        this.detail.tgl_next_fol_up = '';
                        alert("Tgl. Next Follow Up Tidak Boleh Lebih Kecil Dari Tgl. Follow Up");
                        return false;
                      }
                    }
                    this.details.push(this.detail);
                    this.clearFolUp();
                  },
                  delDetails: function(index) {
                    this.details.splice(index, 1);
                  },
                  setOptionStatusFollowUp: function() {
                    this.detail.id_status_fu = '';
                    this.detail.kodeHasilStatusFollowUp = '';
                    this.detail.kodeAlasanNotProspectNotDeal = '';
                    this.detail.desc_metode_fol_up = $("#metode_fol_up option:selected").text();
                    dt = {
                      id_media_kontak_fu: this.detail.metode_fol_up
                    }
                    $.ajax({
                      url: "<?php echo site_url('dealer/prospek_crm/getOptionsStatusFollowUp') ?>",
                      type: "POST",
                      data: dt,
                      cache: false,
                      success: function(data) {
                        form_.options_status_fu = [];
                        // data = JSON.parse(data);
                        if (data.length > 0) {
                          for (dt of data) {
                            form_.options_status_fu.push(dt);
                          }
                        }
                      }
                    })
                  },
                  statusProspek: function(tgl_folup, tgl_next) {
                    let statusProspek = '';
                    selisih = hitungSelisihHari(tgl_folup, tgl_next);
                    console.log(selisih);
                    if (selisih < 14) {
                      statusProspek = 'Hot';
                    } else if (selisih >= 14 && selisih <= 28) {
                      statusProspek = 'Medium';
                    } else if (selisih > 28) {
                      statusProspek = 'Low';
                    }
                    this.status_prospek = statusProspek;
                    return statusProspek;
                  }
                },
                watch: {
                  detail: {
                    deep: true,
                    handler: function() {
                      this.detail.id_status_fu = this.detail.option_status_fu.id;
                      this.detail.status_fu = this.detail.option_status_fu.text;
                      this.detail.kategori_status_komunikasi = this.detail.option_status_fu.kategori;
                      this.detail.id_kategori_status_komunikasi = this.detail.option_status_fu.id_kategori;
                      this.detail.status_prospek = this.statusProspek(this.detail.tgl_fol_up, this.detail.tgl_next_fol_up);
                      console.log(this.detail);
                      if (this.detail.tgl_next_fol_up != '') {
                        if (this.detail.tgl_fol_up > this.detail.tgl_next_fol_up) {
                          this.detail.tgl_next_fol_up = '';
                          alert("Tgl. Next Follow Up Tidak Boleh Lebih Kecil Dari Tgl. Follow Up");
                          return false;
                        }
                      }
                    }
                  }
                },
              });
              function hitungSelisihHari(tgl1, tgl2) {
                console.log(tgl1);
                console.log(tgl2);
                  // varibel miliday sebagai pembagi untuk menghasilkan hari
                  var miliday = 24 * 60 * 60 * 1000;
                  //buat object Date
                  var tanggal1 = new Date(tgl1);
                  var tanggal2 = new Date(tgl2);
                  // Date.parse akan menghasilkan nilai bernilai integer dalam bentuk milisecond
                  var tglPertama = Date.parse(tanggal1);
                  var tglKedua = Date.parse(tanggal2);
                  var selisih = (tglKedua - tglPertama) / miliday;
                  return selisih;
              }
            </script>
          <?php
          } elseif ($set == 'insert_gc') {
            $form = '';
            if ($mode == 'insert') {
              $form = 'save_gc';
            } elseif ($mode == 'edit') {
              $form = 'update_gc';
            }
          ?>
            <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
            <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
            <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/prospek_crm/gc">
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
                    <form class="form-horizontal" action="dealer/prospek_crm/<?= $form ?>" method="post" enctype="multipart/form-data">
                      <div class="box-body">
                        <button class="btn btn-block btn-primary btn-flat" disabled> DATA PROSPEK </button> <br>
                        <?php if (isset($row)) { ?>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">ID Prospek</label>
                            <div class="col-md-4">
                              <input type="text" class="form-control" name="id" value="<?= isset($row) ? $row->id_prospek : '' ?>" readonly>
                            </div>
                          </div>
                        <?php } ?>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl Prospek</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" name="tgl_prospek" value="<?= isset($row) ? $row->tgl_prospek : date('Y-m-d') ?>" readonly>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Prioritas Prospect Customer</label>
                          <div class="col-sm-2">
                            <select class='form-control' name='prioritas_prospek' v-model='prioritas_prospek' :disabled="mode=='detail'">
                              <option value=''>- choose -</option>
                              <option value='1'>Yes</option>
                              <option value='0'>No</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <input type="hidden" id="id_prospek_gc" value="">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Sales *</label>
                          <div class="col-sm-4">
                            <select class="form-control select2" name="id_karyawan_dealer" required id="id_karyawan_dealer" onchange="take_sales()">
                              <option value="">- choose -</option>
                              <?php
                              foreach ($dt_karyawan->result() as $val) {
                                echo "
                        <option value='$val->id_karyawan_dealer'>$val->nama_lengkap</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">FLP ID *</label>
                          <div class="col-sm-4">
                            <input type="hidden" class="form-control" id="nama_sales" name="nama_sales">
                            <input type="text" readonly class="form-control" required id="kode_sales" placeholder="FLP ID" name="id_flp_md">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Jenis *</label>
                          <div class="col-sm-4">
                            <select name="jenis" onchange="cek_jenis()" id="jenis" required class="form-control">
                              <option value="">- choose -</option>
                              <option>Swasta/BUMN/Koperasi</option>
                              <option>Instansi</option>
                              <option>Joint Promo</option>
                            </select>
                          </div>
                          <span id="span_kelompok_harga">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Harga</label>
                            <div class="col-sm-4">
                              <select name="kelompok_harga" id="kelompok_harga" class="form-control">
                                <option value="">- choose -</option>
                                <?php
                                $sql = $this->db->query("SELECT id_kelompok_harga,kelompok_harga FROM ms_kelompok_harga WHERE target_market = 'Instansi'");
                                foreach ($sql->result() as $isi) {
                                  echo "
                          <option value='$isi->id_kelompok_harga'>$isi->kelompok_harga</option>;
                          ";
                                }
                                ?>
                              </select>
                            </div>
                          </span>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama NPWP *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Nama NPWP" required name="nama_npwp">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No NPWP *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" onkeypress="return number_only(event)" required placeholder="No NPWP" name="no_npwp">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No Telp Perusahaan *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" maxlength="15" required onkeypress="return number_only(event)" placeholder="No Telp Perusahaan" name="no_telp">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl Berdiri Perusahaan *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="tanggal3" placeholder="Tgl Berdiri Perusahaan" required name="tgl_berdiri">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan *</label>
                          <div class="col-sm-4">
                            <?php
                            $id_kel = "";
                            $kel = "";
                            if (isset($_SESSION['id_kelurahan'])) {
                              $id_kel = $_SESSION['id_kelurahan'];
                              if ($id_kel != "") $kel = $this->m_admin->getByID("ms_kelurahan", "id_kelurahan", $id_kel)->row()->kelurahan;
                              $_SESSION['id_kelurahan'] = "";
                            }
                            ?>
                            <input type="hidden" readonly name="id_kelurahan" id="id_kelurahan" value="<?php echo $id_kel ?>">
                            <input type="text" onpaste="return false" value="<?php echo $kel ?>" autocomplete="off" required onkeypress="return nihil(event)" name="kelurahan" data-toggle="modal" placeholder="Kelurahan" onclick="showModalKelurahan()" class="form-control" id="kelurahan">
                            <script>
                              var kelurahan_untuk = '';

                              function pilihKelurahan(params) {
                                // showLoader = setTimeout("$('#pleaseWaitDialog').modal()", 300);
                                if (kelurahan_untuk === 'kantor') {
                                  $('#id_kelurahan_kantor').val(params.id_kelurahan)
                                  $('#kelurahan_kantor').val(params.kelurahan)
                                  $('#kecamatan_kantor').val(params.kecamatan)
                                  $('#kabupaten_kantor').val(params.kabupaten)
                                  $('#provinsi_kantor').val(params.provinsi)
                                } else if (kelurahan_untuk === 'customer') {
                                  $('#id_kelurahan').val(params.id_kelurahan)
                                  $('#kelurahan').val(params.kelurahan)
                                  $('#id_kecamatan').val(params.id_kecamatan)
                                  $('#kecamatan').val(params.kecamatan)
                                  $('#id_kabupaten').val(params.id_kabupaten)
                                  $('#kabupaten').val(params.kabupaten)
                                  $('#id_provinsi').val(params.id_provinsi)
                                  $('#provinsi').val(params.provinsi)
                                }
                                // clearTimeout(showLoader);
                                // $('#pleaseWaitDialog').modal('hide');
                              }
                            </script>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan *</label>
                          <div class="col-sm-4">
                            <input type="hidden" class="form-control" id="id_kecamatan" name="id_kecamatan">
                            <input type="text" readonly class="form-control" id="kecamatan" placeholder="Kecamatan" name="kecamatan">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten/Kota *</label>
                          <div class="col-sm-4">
                            <input type="hidden" class="form-control" id="id_kabupaten" name="id_kabupaten">
                            <input type="text" readonly class="form-control" id="kabupaten" placeholder="Kabupaten" name="kabupaten">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Provinsi *</label>
                          <div class="col-sm-4">
                            <input type="hidden" class="form-control" id="id_provinsi" name="id_provinsi">
                            <input type="text" readonly class="form-control" id="provinsi" placeholder="Provinsi" name="provinsi">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat *</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="100" required placeholder="Alamat" name="alamat">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kodepos *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" required onkeypress="return number_only(event)" placeholder="Kodepos" name="kodepos" id="kode_pos">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Status Prospek *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="status_prospek" required>
                              <option value="">- choose -</option>
                              <option>Low</option>
                              <option>Medium</option>
                              <option>Hot</option>
                              <!-- <option>Cold Prospect</option>
                              <option>Medium Prospect</option>
                              <option>Hot Prospect</option>
                              <option>Deal</option>
                              <option>Closing</option>
                              <option>Loss</option> -->
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Longitude</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" name="longitude" value="<?= isset($row) ? $row->longitude : '' ?>" :disabled="mode=='detail'">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Latitude</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" name="latitude" value="<?= isset($row) ? $row->latitude : '' ?>" :disabled="mode=='detail'">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan *</label>
                          <div class="col-sm-4">
                            <select class="form-control select2" name="id_pekerjaan" required required :disabled="mode=='detail'">
                              <option value="">- choose -</option>
                              <?php
                              foreach ($dt_pekerjaan->result() as $val) {
                                $selected = isset($row) ? $val->id_pekerjaan == $row->id_pekerjaan ? 'selected' : '' : '';
                                echo "
                        <option value='$val->id_pekerjaan' $selected>$val->pekerjaan</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Test Ride Preference</label>
                          <div class="col-md-4">
                            <select name="test_ride_preference" class="form-control" v-model='test_ride_preference' :disabled="mode=='detail'">
                              <option value="">--choose--</option>
                              <option value="1">Yes</option>
                              <option value="0">No</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">ID Event</label>
                          <div class="col-sm-4">
                            <select name="id_event" id="id_event" onchange="setEvent()" class="form-control select2" :disabled="mode=='detail'">
                              <option value="">--choose-</option>
                              <?php foreach ($this->m_prospek->getEvent()->result() as $rs) :
                                $selected = isset($row) ? $rs->id_event == $row->id_event ? 'selected' : '' : '';
                              ?>
                                <option value="<?= $rs->id_event ?>" <?= $selected ?> data-nama_event="<?= $rs->nama_event ?>"><?= $rs->id_event . ' | ' . $rs->nama_event ?></option>
                              <?php endforeach ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Event</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id='nama_event' readonly value="<?= isset($row) ? $row->nama_event : '' ?>" :disabled="mode=='detail'">
                          </div>
                          <script>
                            function setEvent() {
                              var nama_event = $("#id_event").select2().find(":selected").data("nama_event");
                              $('#nama_event').val(nama_event);

                            }
                          </script>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Sales Program</label>
                          <div class="col-sm-4">
                            <select class="form-control select2" name="program_umum" id="program_umum" onchange="cek_program_gc()">
                              <option value="">- choose -</option>
                              <?php
                              $sp = $this->m_prospek->getSalesProgramGC();
                              foreach ($sp->result() as $isi) {
                                echo "<option value='$isi->id_program_md'>$isi->id_program_md</option>";
                              }
                              ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Sumber Prospek *</label>
                          <div class="col-md-4">
                            <select name="sumber_prospek" class="form-control select2" v-model='sumber_prospek' :disabled="mode=='detail'" required>
                              <option value="">--choose--</option>
                              <?php
                              $this->db->where('active', '1');
                              foreach ($this->db->get('ms_sumber_prospek')->result() as $rw) : ?>
                                <option value="<?php echo $rw->id_dms ?>"><?php echo $rw->description ?></option>
                              <?php endforeach ?>
                              <!-- <option value="0001">Pameran (Joint Promo, Grebek Pasar, Alfamart, Indomart, Mall dll)</option>
                              <option value="0002">Showroom Event</option>
                              <option value="0003">Roadshow</option>
                              <option value="0004">Walk in</option>
                              <option value="0005">Customer RO H1</option>
                              <option value="0006">Customer RO H23</option>
                              <option value="0007">Website</option>
                              <option value="0008">Social Media</option>
                              <option value="0009">External Parties (Leasing, Insurance)</option>
                              <option value="0010">Mobile Apps MD/Dealer</option>
                              <option value="9999">Others</option> -->
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat Kantor</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" name="alamat_kantor" value="<?= isset($row) ? $row->alamat_kantor : '' ?>" :disabled="mode=='detail'">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No Telp Kantor</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" name="no_telp_kantor" value="<?= isset($row) ? $row->no_telp_kantor : '' ?>" :disabled="mode=='detail'">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Kantor</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" id="kelurahan_kantor" id='kelurahan_kantor' readonly placeholder='Klik Untuk Memilih' onclick="showModalKelurahan('kantor')" value="<?= isset($row) ? $row->kel_kantor : '' ?>">
                            <input type='hidden' id='id_kelurahan_kantor' name='id_kelurahan_kantor' value="<?= isset($row) ? $row->id_kelurahan_kantor : '' ?>">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Kantor</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" id="kecamatan_kantor" readonly placeholder='Klik Untuk Memilih' onclick="showModalKelurahan('kantor')" value="<?= isset($row) ? $row->kec_kantor : '' ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten Kantor</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" id="kabupaten_kantor" readonly placeholder='Klik Untuk Memilih' onclick="showModalKelurahan('kantor')" value="<?= isset($row) ? $row->kab_kantor : '' ?>">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Kantor</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" id="provinsi_kantor" readonly placeholder='Klik Untuk Memilih' onclick="showModalKelurahan('kantor')" value="<?= isset($row) ? $row->prov_kantor : '' ?>">
                          </div>
                        </div>
                        <button class="btn btn-block btn-primary btn-flat" disabled> DATA PENANGGUNG JAWAB </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Penanggung Jawab *</label>
                          <div class="col-sm-4">
                            <input type="text" required class="form-control" placeholder="Nama Penanggung Jawab" name="nama_penanggung_jawab">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                          <div class="col-sm-4">
                            <input type="email" class="form-control" placeholder="Email" name="email">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No HP *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" maxlength="15" onkeypress="return number_only(event)" required placeholder="No HP" name="no_hp">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="status_nohp" required>
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


                        <button class="btn btn-block btn-danger btn-flat" disabled> DATA KENDARAAN </button> <br>
                        <div id="showDetail"></div>
                        <br>
                        <button class="btn btn-block btn-danger btn-flat" disabled> FOLLOW UP/LIST APPOINTMENT </button> <br>
                        <!-- <div class="col-md-12"> -->
                        <table class="table table-bordered" id='form_'>
                          <thead>
                            <th>Tgl Follow UP</th>
                            <th>Waktu Follow UP</th>
                            <th>Metode Follow UP</th>
                            <th>Keterangan</th>
                            <th width="9%" style="text-align: center;" v-if="mode=='insert' || mode=='edit'">Aksi</th>
                          </thead>
                          <tbody>
                            <tr v-for="(dl, index) of details">
                              <td><input type="text" class="form-control isi" name="tgl_fol_up[]" v-model="dl.tgl_fol_up" readonly></td>
                              <td><input type="text" class="form-control isi" name="waktu_fol_up[]" v-model="dl.waktu_fol_up" readonly></td>
                              <td><input type="text" class="form-control isi" name="metode_fol_up[]" v-model="dl.metode_fol_up" readonly></td>
                              <td><input type="text" class="form-control isi" name="keterangan[]" v-model="dl.keterangan" readonly></td>
                              <td align="center" v-if="mode=='insert' || mode=='edit'">
                                <button type="button" @click.prevent="delDetails(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                              </td>
                            </tr>
                          </tbody>
                          <tfoot v-if="mode=='insert' || mode=='edit'">
                            <tr>
                              <td>
                                <input type="text" class="form-control isi datepicker" id="tgl_fol_up">
                              </td>
                              <td>
                                <input type="text" class="form-control isi" id="waktu_fol_up" v-model="detail.waktu_fol_up">
                              </td>
                              <td>
                                <select id="metode_fol_up" class="form-control" v-model="detail.metode_fol_up">
                                  <option value="">--choose--</option>
                                  <option>SMS</option>
                                  <option>Telepon</option>
                                  <option>Visit</option>
                                  <option>Direct Touch</option>
                                </select>
                              </td>
                              <td>
                                <input type="text" class="form-control isi" id="keterangan" v-model="detail.keterangan">
                              </td>
                              <td align="center">
                                <button type="button" @click.prevent="addDetails()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i></button>
                              </td>
                            </tr>
                          </tfoot>
                        </table>
                        <!-- </div> -->

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
              <?php
              $data['data'] = ['kelurahan'];
              $this->load->view('dealer/h2_api', $data); ?>
              <script>
                var form_ = new Vue({
                  el: '#form_',
                  data: {
                    mode: '<?= $mode ?>',
                    detail: {
                      tgl_fol_up: '',
                      waktu_fol_up: '',
                      metode_fol_up: '',
                      keterangan: '',
                    },
                    details: <?= isset($details) ? json_encode($details) : '[]' ?>,
                  },
                  methods: {
                    clearFolUp: function() {
                      $('#tgl_fol_up').val('');
                      this.detail = {
                        tgl_fol_up: '',
                        waktu_fol_up: '',
                        metode_fol_up: '',
                        keterangan: ''
                      }
                    },
                    addDetails: function() {
                      this.detail.tgl_fol_up = $('#tgl_fol_up').val();
                      // if (this.details.length > 0) {
                      //   for (dl of this.details) {
                      //     if (dl.id_dealer === this.detail.id_dealer) {
                      //         alert("Dealer Sudah Dipilih !");
                      //         this.clearFolUp();
                      //         return;
                      //     }
                      //   }
                      // }
                      this.details.push(this.detail);
                      this.clearFolUp();
                    },
                    delDetails: function(index) {
                      this.details.splice(index, 1);
                    },
                  },
                  watch: {
                    detail: {
                      deep: false,
                      handler: function() {
                        this.detail.id_status_fu = option_status_fu.id_status_fu;
                        this.detail.status_fu = option_status_fu.status_fu;
                        this.detail.kategori_status_komunikasi = option_status_fu.kategori;
                      }
                    }
                  },
                });
              </script>
            </div><!-- /.box -->
          <?php
          } elseif ($set == 'edit_gc') {
            $row = $dt_prospek_gc->row();
          ?>
            <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
            <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
            <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/prospek_crm/gc">
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
                    <form class="form-horizontal" action="dealer/prospek_crm/update_gc" method="post" enctype="multipart/form-data" id='form_'>
                      <div class="box-body">
                        <button class="btn btn-block btn-primary btn-flat" disabled> DATA PROSPEK </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">ID Prospek</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" name="id" value="<?= isset($row) ? $row->id_prospek_gc : '' ?>" readonly>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Prospek</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" name="tgl_prospek" value="<?= isset($row) ? $row->tgl_prospek : '' ?>" readonly>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Sales *</label>
                          <div class="col-sm-4">
                            <input type="hidden" id="id_prospek_gc" name="id_prospek_gc" value="<?php echo $id ?>">
                            <select class="form-control select2" name="id_karyawan_dealer" required id="id_karyawan_dealer" onchange="take_sales()">
                              <option value="<?php echo $row->id_karyawan_dealer   ?>">
                                <?php
                                $dt_cust    = $this->m_admin->getByID("ms_karyawan_dealer", "id_karyawan_dealer", $row->id_karyawan_dealer)->row();
                                if (isset($dt_cust)) {
                                  echo $dt_cust->nama_lengkap;
                                } else {
                                  echo "- choose -";
                                }
                                ?>
                              </option>
                              <?php
                              $id_dealer = $this->m_admin->cari_dealer();
                              $dt_karyawan = $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_dealer = '$id_dealer' AND id_karyawan_dealer != '$row->id_karyawan_dealer' AND id_flp_md <> '' ORDER BY nama_lengkap ASC");
                              foreach ($dt_karyawan->result() as $val) {
                                echo "
                        <option value='$val->id_karyawan_dealer'>$val->nama_lengkap</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">FLP ID *</label>
                          <div class="col-sm-4">
                            <input type="hidden" class="form-control" id="nama_sales" name="nama_sales">
                            <input type="text" readonly class="form-control" required id="kode_sales" placeholder="FLP ID" name="id_flp_md">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Jenis *</label>
                          <div class="col-sm-4">
                            <select name="jenis" id="jenis" onchange="cek_jenis()" required class="form-control">
                              <option <?php if ($row->jenis == '') echo "selected" ?> value="">- choose -</option>
                              <option <?php if ($row->jenis == 'Swasta/BUMN/Koperasi') echo "selected" ?>>Swasta/BUMN/Koperasi</option>
                              <option <?php if ($row->jenis == 'Instansi') echo "selected" ?>>Instansi</option>
                              <option <?php if ($row->jenis == 'Joint Promo') echo "selected" ?>>Joint Promo</option>
                            </select>
                          </div>
                          <span id="span_kelompok_harga">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Harga</label>
                            <div class="col-sm-4">
                              <select name="kelompok_harga" id="kelompok_harga" class="form-control">
                                <option value="<?php echo $row->id_kelompok_harga ?>">
                                  <?php
                                  $dt_cust    = $this->m_admin->getByID("ms_kelompok_harga", "id_kelompok_harga", $row->id_kelompok_harga)->row();
                                  if (isset($dt_cust)) {
                                    echo $dt_cust->kelompok_harga;
                                  } else {
                                    echo "- choose -";
                                  }
                                  ?>
                                </option>
                                <?php
                                $dt_kelompok_harga = $this->m_admin->kondisiCond("ms_kelompok_harga", "id_kelompok_harga != '$row->id_kelompok_harga'");
                                foreach ($dt_kelompok_harga->result() as $val) {
                                  echo "
                                  <option value='$val->id_kelompok_harga'>$val->kelompok_harga</option>;
                                  ";
                                }

                                ?>
                              </select>
                            </div>
                          </span>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama NPWP *</label>
                          <div class="col-sm-4">
                            <input required type="text" class="form-control" value="<?php echo $row->nama_npwp ?>" placeholder="Nama NPWP" name="nama_npwp">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No NPWP *</label>
                          <div class="col-sm-4">
                            <input required type="text" class="form-control" value="<?php echo $row->no_npwp ?>" onkeypress="return number_only(event)" placeholder="No NPWP" name="no_npwp">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No Telp Perusahaan *</label>
                          <div class="col-sm-4">
                            <input required type="text" class="form-control" value="<?php echo $row->no_telp ?>" maxlength="15" onkeypress="return number_only(event)" placeholder="No Telp Perusahaan" name="no_telp">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl Berdiri Perusahaan *</label>
                          <div class="col-sm-4">
                            <input required type="text" class="form-control" value="<?php echo $row->tgl_berdiri ?>" id="tanggal2" placeholder="Tgl Berdiri Perusahaan" name="tgl_berdiri">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan *</label>
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
                            <input type="text" value="<?php echo $kel ?>" required onpaste="return false" autocomplete="off" onkeypress="return nihil(event)" name="kelurahan" data-toggle="modal" placeholder="Kelurahan" onclick="showModalKelurahan()" class="form-control" id="kelurahan">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan *</label>
                          <div class="col-sm-4">
                            <input type="hidden" class="form-control" id="id_kecamatan" name="id_kecamatan">
                            <input type="text" readonly class="form-control" id="kecamatan" placeholder="Kecamatan" name="kecamatan">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten/Kota *</label>
                          <div class="col-sm-4">
                            <input type="hidden" class="form-control" id="id_kabupaten" name="id_kabupaten">
                            <input type="text" readonly class="form-control" id="kabupaten" placeholder="Kabupaten" name="kabupaten">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Provinsi *</label>
                          <div class="col-sm-4">
                            <input type="hidden" class="form-control" id="id_provinsi" name="id_provinsi">
                            <input type="text" readonly class="form-control" id="provinsi" placeholder="Provinsi" name="provinsi">
                          </div>
                        </div>
                        <script>
                          var kelurahan_untuk = '';

                          function pilihKelurahan(params) {
                            // showLoader = setTimeout("$('#pleaseWaitDialog').modal()", 300);
                            if (kelurahan_untuk === 'kantor') {
                              $('#id_kelurahan_kantor').val(params.id_kelurahan)
                              $('#kelurahan_kantor').val(params.kelurahan)
                              $('#kecamatan_kantor').val(params.kecamatan)
                              $('#kabupaten_kantor').val(params.kabupaten)
                              $('#provinsi_kantor').val(params.provinsi)
                            } else if (kelurahan_untuk === 'customer') {
                              $('#id_kelurahan').val(params.id_kelurahan)
                              $('#kelurahan').val(params.kelurahan)
                              $('#id_kecamatan').val(params.id_kecamatan)
                              $('#kecamatan').val(params.kecamatan)
                              $('#id_kabupaten').val(params.id_kabupaten)
                              $('#kabupaten').val(params.kabupaten)
                              $('#id_provinsi').val(params.id_provinsi)
                              $('#provinsi').val(params.provinsi)
                            }
                            // clearTimeout(showLoader);
                            // $('#pleaseWaitDialog').modal('hide');
                          }
                        </script>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat *</label>
                          <div class="col-sm-10">
                            <input required type="text" class="form-control" value="<?php echo $row->alamat ?>" maxlength="100" placeholder="Alamat" name="alamat">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kodepos *</label>
                          <div class="col-sm-4">
                            <input required type="text" class="form-control" value="<?php echo $row->kodepos ?>" onkeypress="return number_only(event)" placeholder="Kodepos" name="kodepos" id="kode_pos">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Status Prospek *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="status_prospek" required v-model='status_prospek'>
                              <option value="">- choose -</option>
                              <option>Low</option>
                              <option>Medium</option>
                              <option>Hot</option>
                              <!-- <option>Cold Prospect</option>
                              <option>Medium Prospect</option>
                              <option>Hot Prospect</option>
                              <option>Deal</option>
                              <option>Closing</option>
                              <option>Loss</option> -->
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Longitude</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" name="longitude" value="<?= isset($row) ? $row->longitude : '' ?>" :disabled="mode=='detail'">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Latitude</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" name="latitude" value="<?= isset($row) ? $row->latitude : '' ?>" :disabled="mode=='detail'">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan *</label>
                          <div class="col-sm-4">
                            <select class="form-control select2" name="id_pekerjaan" required required :disabled="mode=='detail'">
                              <option value="">- choose -</option>
                              <?php
                              foreach ($dt_pekerjaan->result() as $val) {
                                $selected = isset($row) ? $val->id_pekerjaan == $row->id_pekerjaan ? 'selected' : '' : '';
                                echo "
                        <option value='$val->id_pekerjaan' $selected>$val->pekerjaan</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Test Ride Preference</label>
                          <div class="col-md-4">
                            <select name="test_ride_preference" class="form-control" v-model='test_ride_preference' :disabled="mode=='detail'">
                              <option value="">--choose--</option>
                              <option value="1">Yes</option>
                              <option value="0">No</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">ID Event</label>
                          <div class="col-sm-4">
                            <select name="id_event" id="id_event" onchange="setEvent()" class="form-control select2" :disabled="mode=='detail'">
                              <option value="">--choose-</option>
                              <?php foreach ($this->m_prospek->getEvent()->result() as $rs) :
                                $selected = isset($row) ? $rs->id_event == $row->id_event ? 'selected' : '' : '';
                              ?>
                                <option value="<?= $rs->id_event ?>" <?= $selected ?> data-nama_event="<?= $rs->nama_event ?>"><?= $rs->id_event . ' | ' . $rs->nama_event ?></option>
                              <?php endforeach ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Event</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id='nama_event' readonly value="<?= isset($row) ? $row->nama_event : '' ?>" :disabled="mode=='detail'">
                          </div>
                          <script>
                            function setEvent() {
                              var nama_event = $("#id_event").select2().find(":selected").data("nama_event");
                              $('#nama_event').val(nama_event);

                            }
                          </script>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Sales Program</label>
                          <div class="col-sm-4">
                            <select class="form-control select2" name="program_umum" id="program_umum" onchange="cek_program_gc()">
                              <option value="">- choose -</option>
                              <?php
                              $sp = $this->m_prospek->getSalesProgramGC();
                              foreach ($sp->result() as $isi) {
                                echo "<option value='$isi->id_program_md'>$isi->id_program_md</option>";
                              }
                              ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Sumber Prospek</label>
                          <div class="col-md-4">
                            <select name="sumber_prospek" class="form-control select2" v-model='sumber_prospek' :disabled="mode=='detail'">
                              <option value="">--choose--</option>
                              <option value="0001">Pameran (Joint Promo, Grebek Pasar, Alfamart, Indomart, Mall dll)</option>
                              <option value="0002">Showroom Event</option>
                              <option value="0003">Roadshow</option>
                              <option value="0004">Walk in</option>
                              <option value="0005">Customer RO H1</option>
                              <option value="0006">Customer RO H23</option>
                              <option value="0007">Website</option>
                              <option value="0008">Social Media</option>
                              <option value="0009">External Parties (Leasing, Insurance)</option>
                              <option value="0010">Mobile Apps MD/Dealer</option>
                              <option value="9999">Others</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat Kantor</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" name="alamat_kantor" value="<?= isset($row) ? $row->alamat_kantor : '' ?>" :disabled="mode=='detail'">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No Telp Kantor</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" name="no_telp_kantor" value="<?= isset($row) ? $row->no_telp_kantor : '' ?>" :disabled="mode=='detail'">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Kantor</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" id="kelurahan_kantor" id='kelurahan_kantor' readonly placeholder='Klik Untuk Memilih' onclick="showModalKelurahan('kantor')" value="<?= isset($row) ? $row->kel_kantor : '' ?>">
                            <input type='hidden' id='id_kelurahan_kantor' name='id_kelurahan_kantor' value="<?= isset($row) ? $row->id_kelurahan_kantor : '' ?>">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Kantor</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" id="kecamatan_kantor" readonly placeholder='Klik Untuk Memilih' onclick="showModalKelurahan('kantor')" value="<?= isset($row) ? $row->kec_kantor : '' ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten Kantor</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" id="kabupaten_kantor" readonly placeholder='Klik Untuk Memilih' onclick="showModalKelurahan('kantor')" value="<?= isset($row) ? $row->kab_kantor : '' ?>">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Kantor</label>
                          <div class="col-md-4">
                            <input type="text" class="form-control" id="provinsi_kantor" readonly placeholder='Klik Untuk Memilih' onclick="showModalKelurahan('kantor')" value="<?= isset($row) ? $row->prov_kantor : '' ?>">
                          </div>
                        </div>
                        <button class="btn btn-block btn-primary btn-flat" disabled> DATA PENANGGUNG JAWAb </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Penanggung Jawab *</label>
                          <div class="col-sm-4">
                            <input type="text" required value="<?php echo $row->nama_penanggung_jawab ?>" class="form-control" placeholder="Nama Penanggung Jawab" name="nama_penanggung_jawab">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                          <div class="col-sm-4">
                            <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo $row->email ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No HP *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" maxlength="15" onkeypress="return number_only(event)" required value="<?= scriptToHtml($row->no_hp) ?>" placeholder="No HP" name="no_hp">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp *</label>
                          <div class="col-sm-4">
                            <select class="form-control" name="status_nohp" required>
                              <option value="<?php echo $row->status_nohp ?>">
                                <?php
                                $dt_cust    = $this->m_admin->getByID("ms_status_hp", "id_status_hp", $row->status_nohp)->row();
                                if (isset($dt_cust)) {
                                  echo $dt_cust->status_hp;
                                } else {
                                  echo "- choose -";
                                }
                                ?>
                              </option>
                              <?php
                              $dt_status_hp = $this->m_admin->kondisi("ms_status_hp", "id_status_hp != '$row->status_nohp'");
                              foreach ($dt_status_hp->result() as $val) {
                                echo "
                        <option value='$val->id_status_hp'>$val->status_hp</option>;
                        ";
                              }
                              ?>
                            </select>
                          </div>
                        </div>


                        <button class="btn btn-block btn-danger btn-flat" disabled> DATA KENDARAAN </button> <br>
                        <div id="showDetail"></div>
                        <br>
                        <button class="btn btn-block btn-danger btn-flat" disabled> FOLLOW UP/LIST APPOINTMENT </button> <br>
                        <!-- <div class="col-md-12"> -->
                        <table class="table table-bordered">
                          <thead>
                            <th>Tgl Follow UP</th>
                            <th>Waktu Follow UP</th>
                            <th>Metode Follow UP</th>
                            <th>Keterangan</th>
                            <th width="9%" style="text-align: center;" v-if="mode=='insert' || mode=='edit'">Aksi</th>
                          </thead>
                          <tbody>
                            <tr v-for="(dl, index) of details">
                              <td><input type="text" class="form-control isi" name="tgl_fol_up[]" v-model="dl.tgl_fol_up" readonly></td>
                              <td><input type="text" class="form-control isi" name="waktu_fol_up[]" v-model="dl.waktu_fol_up" readonly></td>
                              <td><input type="text" class="form-control isi" name="metode_fol_up[]" v-model="dl.metode_fol_up" readonly></td>
                              <td><input type="text" class="form-control isi" name="keterangan[]" v-model="dl.keterangan" readonly></td>
                              <td align="center" v-if="mode=='insert' || mode=='edit'">
                                <button type="button" @click.prevent="delDetails(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                              </td>
                            </tr>
                          </tbody>
                          <tfoot v-if="mode=='insert' || mode=='edit'">
                            <tr>
                              <td>
                                <input type="text" class="form-control isi datepicker" id="tgl_fol_up">
                              </td>
                              <td>
                                <input type="text" class="form-control isi" id="waktu_fol_up" v-model="detail.waktu_fol_up">
                              </td>
                              <td>
                                <select id="metode_fol_up" class="form-control" v-model="detail.metode_fol_up">
                                  <option value="">--choose--</option>
                                  <option>SMS</option>
                                  <option>Telepon</option>
                                  <option>Visit</option>
                                  <option>Direct Touch</option>
                                </select>
                              </td>
                              <td>
                                <input type="text" class="form-control isi" id="keterangan" v-model="detail.keterangan">
                              </td>
                              <td align="center">
                                <button type="button" @click.prevent="addDetails()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i></button>
                              </td>
                            </tr>
                          </tfoot>
                        </table>
                        <!-- </div> -->

                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-12" align='center'>
                          <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
                        </div>
                      </div><!-- /.box-footer -->
                    </form>
                  </div>
                </div>
              </div>
            </div><!-- /.box -->
            <?php
            $data['data'] = ['kelurahan'];
            $this->load->view('dealer/h2_api', $data); ?>
            <script>
              var form_ = new Vue({
                el: '#form_',
                data: {
                  mode: '<?= $mode ?>',
                  prioritas_prospek: '<?= isset($row) ? $row->prioritas_prospek : '' ?>',
                  status_prospek: '<?= isset($row) ? $row->status_prospek : '' ?>',
                  sumber_prospek: '<?= isset($row) ? $row->sumber_prospek : '' ?>',
                  test_ride_preference: '<?= isset($row) ? $row->test_ride_preference : '' ?>',
                  detail: {
                    tgl_fol_up: '',
                    waktu_fol_up: '',
                    metode_fol_up: '',
                    keterangan: '',
                  },
                  details: <?= isset($details) ? json_encode($details) : '[]' ?>,
                },
                methods: {
                  clearFolUp: function() {
                    $('#tgl_fol_up').val('');
                    this.detail = {
                      tgl_fol_up: '',
                      waktu_fol_up: '',
                      metode_fol_up: '',
                      keterangan: ''
                    }
                  },
                  addDetails: function() {
                    this.detail.tgl_fol_up = $('#tgl_fol_up').val();
                    // if (this.details.length > 0) {
                    //   for (dl of this.details) {
                    //     if (dl.id_dealer === this.detail.id_dealer) {
                    //         alert("Dealer Sudah Dipilih !");
                    //         this.clearFolUp();
                    //         return;
                    //     }
                    //   }
                    // }
                    this.details.push(this.detail);
                    this.clearFolUp();
                  },
                  delDetails: function(index) {
                    this.details.splice(index, 1);
                  },
                },
              });
            </script>
          <?php
          } elseif ($set == "view") {
          ?>
            <style>
              .padding-td {
                padding-right: 5px;
                text-align: center;
                width: 10%;
              }

              .bg-gray-selected {
                background-color: #6a6b6d !important;
              }
            </style>
            <div class="row">
              <div class="col-lg-12 col-md-12 col-xs-12">
                <table style="width:100%">
                  <tbody>
                    <tr>
                      <td class="padding-td">
                        <div class="small-box bg-gray" onclick="setMelewatiSLADealer()" id="melewatiSLADealer">
                          <div class="inner" style="padding-bottom:0px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="">
                            <p style='min-height:65px'>Needs First Follow Up Dealer<br>&nbsp;</p>
                            <h3 class="card_view" id="data_source"><?= $mo['needs_first_follow_up'] ?></h3>
                          </div>
                          <div class="card_view_persen small-box-footer" style="color:black;font-weight:bold" id="data_source_persen"></div>
                        </div>
                      </td>
                      <td class="padding-td">
                        <div class="small-box bg-gray" onclick="setMelewatiSLADealer()" id="melewatiSLADealer">
                          <div class="inner" style="padding-bottom:0px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="">
                            <p style='min-height:65px'>Leads yang melewati SLA Dealer <br>&nbsp;</p>
                            <h3 class="card_view" id="data_source"><?= $mo['lewat_sla_dealer'] ?></h3>
                          </div>
                          <div class="card_view_persen small-box-footer" style="color:black;font-weight:bold" id="data_source_persen"></div>
                        </div>
                      </td>
                      <td class="padding-td">
                        <div class="small-box bg-gray" onclick="setLeadsMultiInteraction()" id="leadsMultiInteraction">
                          <div class="inner" style="padding-bottom:0px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="">
                            <p style='min-height:65px'>Leads Multi-Interaction <br>&nbsp;</p>
                            <h3 class="card_view" id="data_source"><?= $mo['leads_multi_interaction'] ?></h3>
                          </div>
                          <div class="card_view_persen small-box-footer" style="color:black;font-weight:bold" id="data_source_persen"></div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/prospek_crm/add">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
                  </a>
                  <a href="dealer/prospek_crm/gc">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn btn-warning btn-flat margin"><i class="fa fa-users"></i> Grup Customer</button>
                  </a>
                  <a href="dealer/prospek_crm/history">
                    <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History</button>
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
                <?php $this->load->view('dealer/prospek_crm_filter');?>
                <table class='table table-condensed table-bordered table-striped serverside-tables' style="width:100%">
                  <thead>
                    <th width='5%'>#</th>
                    <th width='15%'>ID Prospek</th>
                    <th width='15%'>ID List Appoinment</th>
                    <th width='15%'>Leads ID</th>
                    <th>Nama Konsumen</th>
                    <th>Nama Sales</th>
                    <th>Tgl. Dispatch</th>
                    <th>Platform Data</th>
                    <th>Source Leads</th>
                    <th>Deskripsi Event</th>
                    <th>Periode Event</th>
                    <th>Status FU</th>
                    <th>Pernah Terhubung</th>
                    <th>Hasil FU</th>
                    <th>Jumlah FU</th>
                    <th>Next FU</th>
                    <th>Last Update</th>
                    <th>D Overdue</th>
                    <th>Action</th>
                  </thead>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
            <script src="assets/panel/plugins/datatables/jquery.dataTables.min.js"></script>
            <script src="assets/panel/plugins/datatables/dataTables.bootstrap.min.js"></script>

            <script>
              $(document).ready(function() {
                var dataTable = $('.serverside-tables').DataTable({
                  "processing": true,
                  "serverSide": true,
                  "scrollX": true,
                  "language": {
                    "infoFiltered": "",
                    "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                  },
                  "order": [],
                  "lengthMenu": [
                    [10, 25, 50, 75, 100],
                    [10, 25, 50, 75, 100]
                  ],
                  "ajax": {
                    url: "<?php echo site_url('dealer/prospek_crm/fetchData'); ?>",
                    type: "POST",
                    dataSrc: "data",
                    data: function(d) {
                      d.id_platform_data_multi = $('#id_platform_data').val()
                      d.id_source_leads_multi = $('#id_source_leads').val()
                      d.kode_dealer_sebelumnya_multi = $('#kodeDealerSebelumnya').val()
                      d.assigned_dealer_multi = $('#searchAssignedDealer').val()
                      d.no_hp = $('#no_hp').val()
                      d.id_tipe_kendaraan_multi = $('#id_tipe_kendaraan').val()
                      d.leads_id_multi = $('#search_leads_id').val()
                      d.deskripsi_event_multi = $('#deskripsiEvent').val()
                      d.id_status_fu_multi = $('#id_status_fu').val()
                      d.jumlah_fu = $('#jumlah_fu').val()
                      d.start_next_fu = $('#start_next_fu').val()
                      d.end_next_fu = $('#end_next_fu').val()
                      d.kodeHasilStatusFollowUpMulti = $('#kodeHasilStatusFollowUp').val()
                      d.ontimeSLA2_multi = $('#ontimeSLA2_multi').val()
                      d.show_hasil_fu_not_deal = $('input[name=show_hasil_fu_not_deal]:checked', '').val()
                      d.start_periode_event = $('#start_periode_event').val()
                      d.end_periode_event = $('#end_periode_event').val()
                      d.filterBelumFUMD = filterBelumFUMD;
                      d.leadsNeedFU = leadsNeedFU;
                      d.belumAssignDealer = belumAssignDealer;
                      d.melewatiSLAMD = melewatiSLAMD;
                      d.melewatiSLADealer = melewatiSLADealer;
                      d.leadsMultiInteraction = leadsMultiInteraction;
                      return d;
                    },
                  },
                  "createdRow": function(row, data, index) {
                    if (data[17] == 'Overdue') {
                      $('td', row).eq(17).addClass('bg-red'); // 6 is index of column
                    } else if (data[17] == 'On Track') {
                      $('td', row).eq(17).addClass('bg-green'); // 6 is index of column
                    }
                  },
                  "columnDefs": [{
                      "targets": [0, 18],
                      "orderable": false
                    },
                    {
                      "targets": [17, 18],
                      "className": 'text-center'
                    },
                    // {
                    //   "targets": [3],
                    //   "className": 'text-right'
                    // },
                    // // { "targets":[0],"checkboxes":{'selectRow':true}}
                    // { "targets":[4],"className":'text-right'}, 
                    // // { "targets":[2,4,5], "searchable": false } 
                  ],
                });
              });

              var load_data_assign_dealer = 0;

              var leads_id = '';

              function search() {
                $('.serverside-tables').DataTable().ajax.reload();
              }

              var filterBelumFUMD = false;

              function setFilterBelumFUMD() {
                if (filterBelumFUMD == false) {
                  filterBelumFUMD = true;
                } else {
                  filterBelumFUMD = false;
                }
                resetAllHeader('filterBelumFUMD', filterBelumFUMD);
                search();
              }

              var leadsNeedFU = false;

              function setLeadsNeedFU() {
                if (leadsNeedFU == false) {
                  leadsNeedFU = true;
                } else {
                  leadsNeedFU = false;
                }
                resetAllHeader('leadsNeedFU', leadsNeedFU);
                search();
              }

              var belumAssignDealer = false;

              function setBelumAssignDealer() {
                if (belumAssignDealer == false) {
                  belumAssignDealer = true;
                } else {
                  belumAssignDealer = false;
                }
                resetAllHeader('belumAssignDealer', belumAssignDealer);
                search();
              }

              var melewatiSLAMD = false;

              function setMelewatiSLAMD() {
                if (melewatiSLAMD == false) {
                  melewatiSLAMD = true;
                } else {
                  melewatiSLAMD = false;
                }
                resetAllHeader('melewatiSLAMD', melewatiSLAMD);
                search();
              }

              var melewatiSLADealer = false;

              function setMelewatiSLADealer() {
                if (melewatiSLADealer == false) {
                  melewatiSLADealer = true;
                } else {
                  melewatiSLADealer = false;
                }
                resetAllHeader('melewatiSLADealer', melewatiSLADealer);
                search();
              }

              var leadsMultiInteraction = false;

              function setLeadsMultiInteraction() {
                if (leadsMultiInteraction == false) {
                  leadsMultiInteraction = true;
                } else {
                  leadsMultiInteraction = false;
                }
                resetAllHeader('leadsMultiInteraction', leadsMultiInteraction);
                search();
              }

              function resetAllHeader(except, val) {
                $('.small-box').removeClass('bg-gray-selected');
                $('.small-box').addClass('bg-gray');

                filterBelumFUMD = false;
                leadsNeedFU = false;
                belumAssignDealer = false;
                melewatiSLAMD = false;
                melewatiSLADealer = false;
                leadsMultiInteraction = false;
                if (except == 'filterBelumFUMD') {
                  filterBelumFUMD = val;
                  if (val == true) {
                    $('#filterBelumFUMD').addClass('bg-gray-selected');
                  }
                }

                if (except == 'leadsNeedFU') {
                  leadsNeedFU = val;
                  if (val == true) {
                    $('#leadsNeedFU').addClass('bg-gray-selected');
                  }
                }

                if (except == 'belumAssignDealer') {
                  belumAssignDealer = val;
                  if (val == true) {
                    $('#belumAssignDealer').addClass('bg-gray-selected');
                  }
                }

                if (except == 'melewatiSLAMD') {
                  melewatiSLAMD = val;
                  if (val == true) {
                    $('#melewatiSLAMD').addClass('bg-gray-selected');
                  }
                }

                if (except == 'melewatiSLADealer') {
                  melewatiSLADealer = val;
                  if (val == true) {
                    $('#melewatiSLADealer').addClass('bg-gray-selected');
                  }
                }

                if (except == 'leadsMultiInteraction') {
                  leadsMultiInteraction = val;
                  if (val == true) {
                    $('#leadsMultiInteraction').addClass('bg-gray-selected');
                  }
                }
              }
            </script>

          <?php
          } elseif ($set == "history") {
          ?>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/prospek_crm">
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
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                      <th width="5%">No</th>
                      <th>ID List Appoinment</th>
                      <th>Nama Konsumen</th>
                      <th>Nama Sales</th>
                      <th>Alamat</th>
                      <th>No HP</th>
                      <th>Status Prospek</th>
                      <!-- <th>Action</th>               -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($dt_prospek->result() as $row) {
                      $status = "<span class='label label-success'>$row->status_prospek</span>";
                      echo "
                <tr class='even pointer'>
                  <td class=''>$no</td>      
                  <td class=''>$row->id_list_appointment</td>      
                  <td class=''>
                    <a href='dealer/prospek_crm/detail?id=$row->id_prospek'>"; ?>
                      <?php echo scriptToHtml($row->nama_konsumen); ?>
                      <?php echo "
                   </a>
                  </td>
                  <td class=''>$row->nama_lengkap</td>
                  <td class=''>$row->alamat</td>              
                  <td class=''>"; ?>
                      <?= scriptToHtml($row->no_hp) ?>
                      <?php echo "</td>
                  <td class=''>$status</td>  
                      </tr> "; ?>
                    <?php
                      $no++;
                    }
                    ?>
                  </tbody>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          <?php
          } elseif ($set == 'view_gc') {
          ?>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/prospek_crm/add_gc">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
                  </a>
                  <a href="dealer/prospek_crm">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn btn-warning btn-flat margin"><i class="fa fa-user"></i> Individu</button>
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
                      <th>Nama</th>
                      <th>No NPWP</th>
                      <th>Jenis</th>
                      <th>Alamat</th>
                      <th>Status Prospek</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($dt_prospek->result() as $row) {
                      $status = "<span class='label label-success'>$row->status_prospek</span>";
                      echo "
                <tr class='even pointer'>
                  <td class=''>$no</td>                        
                  <td class=''>$row->nama_penanggung_jawab</td>
                  <td class=''>$row->no_npwp</td>      
                  <td class=''>$row->jenis</td>              
                  <td class=''>$row->alamat</td>
                  <td class=''>$status</td>  
                  <td width='5%'>";
                    ?>

                      <a href='dealer/prospek_crm/edit_gc?id=<?php echo $row->id_prospek_gc ?>'>
                        <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "edit"); ?> class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button>
                      </a>
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
          } elseif ($set == 'cancel_prospek') {
            $form     = '';
            $disabled = '';
            $readonly = '';
            if ($mode == 'cancel') {
              $form = 'save_cancel';
            }
          ?>
            <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
            <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
            <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
            <script>
              Vue.use(VueNumeric.default);
              $(document).ready(function() {})
            </script>
            <div class="box">
              <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">
                    <a href="dealer/prospek_crm">
                      <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
                      <form class="form-horizontal" id="form_" action="dealer/prospek_crm/<?= $form ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Prospek</label>
                          <div class="col-sm-4">
                            <select name="id_prospek" id="id_prospek" onchange="getProspek()" class="form-control select2" <?= $disabled ?> required>
                              <option value="">--choose-</option>
                              <?php foreach ($prospek->result() as $rs) :
                                $selected = isset($row) ? $rs->id_prospek == $row->id_prospek ? 'selected' : '' : '';
                              ?>
                                <option value="<?= $rs->id_prospek ?>" <?= $selected ?> data-id_prospek="<?= $rs->id_prospek ?>" data-nama_konsumen="<?= scriptToHtml($rs->nama_konsumen) ?>" data-no_hp="<?= scriptToHtml($row->no_hp) ?>" data-alamat="<?= $rs->alamat ?>" data-harga_off_road="<?= $rs->harga_off_road ?>" data-harga_on_road="<?= $rs->harga_on_road ?>" data-biaya_bbn="<?= $rs->biaya_bbn ?>" data-id_tipe_kendaraan="<?= $rs->id_tipe_kendaraan ?>" data-id_warna="<?= $rs->id_warna ?>" data-warna="<?= $rs->warna ?>" data-tipe_ahm="<?= $rs->tipe_ahm ?>" data-alamat="<?= $rs->alamat ?>"><?= scriptToHtml($rs->nama_konsumen) ?></option>
                              <?php endforeach ?>
                            </select>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No. HP</label>
                          <div class="col-sm-4">
                            <input type="text" required class="form-control" name="no_hp" id="no_hp" value="<?= isset($row) ? scriptToHtml($row->no_hp) : '' ?>" autocomplete="off" readonly>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama</label>
                          <div class="col-sm-4">
                            <input type="text" required class="form-control" name="nama_konsumen" id="nama_konsumen" value="<?= isset($row) ?  scriptToHtml($row->nama_konsumen)  : '' ?>" autocomplete="off" readonly>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                          <div class="col-sm-6">
                            <input type="text" required class="form-control datepicker" name="alamat" id="alamat" value="<?= isset($row) ? $row->alamat : '' ?>" autocomplete="off" disabled>
                          </div>
                        </div>
                        <input type="hidden" name="tipe_pembayaran" v-model="tipe_pembayaran">
                        <div class="form-group">
                          <div class="col-md-12">
                            <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Detail Kendaraan</button><br><br>
                          </div>
                          <div class="col-md-12">
                            <table class="table table-bordered">
                              <thead>
                                <th>Tipe</th>
                                <th>Warna</th>
                              </thead>
                              <tbody>
                                <tr v-for="(unt, index) of unit">
                                  <td>{{unt.id_tipe_kendaraan}} | {{unt.tipe_ahm}}</td>
                                  <td>{{unt.id_warna}} | {{unt.warna}}</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-warning btn-flat btn-sm" disabled>Cancel</button><br><br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Alasan Not Deal</label>
                          <div class="col-sm-4">
                            <select name="id_reasons" id="id_reasons" onchange="getProspek()" class="form-control select2" <?= $disabled ?> required>
                              <option value="">--choose-</option>
                              <?php foreach ($reasons->result() as $rs) :
                                $selected = isset($row) ? $rs->id_reasons == $row->id_reasons ? 'selected' : '' : '';
                              ?>
                                <option value="<?= $rs->id_reasons ?>" <?= $selected ?>><?= $rs->deskripsi ?></option>
                              <?php endforeach ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Keterangan Not Deal</label>
                          <div class="col-sm-4">
                            <input type="text" required class="form-control" name="keterangan_not_deal" id="keterangan_not_deal" value="<?= isset($row) ? $row->keterangan_not_deal : '' ?>" autocomplete="off" <?= $disabled ?>>
                          </div>
                        </div>
                        <div class="box-footer" v-if="mode!='detail'">
                          <div class="col-sm-12" v-if="mode=='cancel'" align="center">
                            <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                          </div>
                        </div>
                    </div>
                    </form>
                  </div>
                </div>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
            <script>
              var form_ = new Vue({
                el: '#form_',
                data: {
                  mode: '<?= $mode ?>',
                  unit: <?= isset($unit) ? json_encode($unit) : '[]' ?>,
                  tenor: '<?= isset($row) ? $row->tenor : "" ?>',
                  angsuran: '<?= isset($row) ? $row->angsuran : "" ?>',
                  dp: '<?= isset($row) ? $row->dp : "" ?>',
                  nominal_diskon: '<?= isset($row) ? $row->nominal_diskon : "" ?>',
                  tipe_pembayaran: '<?= isset($row) ? $row->tipe_pembayaran : "" ?>',
                },
                methods: {
                  clearDealers: function() {
                    this.dealer = {
                      id_dealer: '',
                      nama_dealer: ''
                    }
                  },
                  addDealers: function() {
                    if (this.dealers.length > 0) {
                      for (dl of this.dealers) {
                        if (dl.id_dealer === this.dealer.id_dealer) {
                          alert("Dealer Sudah Dipilih !");
                          this.clearDealers();
                          return;
                        }
                      }
                    }
                    if (this.dealer.id_dealer == '') {
                      alert('Pilih Dealer !');
                      return false;
                    }
                    this.dealers.push(this.dealer);
                    this.clearDealers();
                  },
                  delDealers: function(index) {
                    this.dealers.splice(index, 1);
                  },
                  getDealer: function() {
                    var el = $('#dealer').find('option:selected');
                    var id_dealer = el.attr("id_dealer");
                    form_.dealer.id_dealer = id_dealer;
                  },
                },
              });

              function getProspek() {
                var nama_konsumen = $("#id_prospek").select2().find(":selected").data("nama_konsumen");
                $('#nama_konsumen').val(nama_konsumen);
                var no_hp = $("#id_prospek").select2().find(":selected").data("no_hp");
                $('#no_hp').val(no_hp);
                var harga_on_road = $("#id_prospek").select2().find(":selected").data("harga_on_road");
                $('#harga_on_road').val(harga_on_road);
                var harga_off_road = $("#id_prospek").select2().find(":selected").data("harga_off_road");
                $('#harga_off_road').val(harga_off_road);
                var alamat = $("#id_prospek").select2().find(":selected").data("alamat");
                $('#alamat').val(alamat);
                var biaya_bbn = $("#id_prospek").select2().find(":selected").data("biaya_bbn");
                $('#biaya_bbn').val(biaya_bbn);
                var id_tipe_kendaraan = $("#id_prospek").select2().find(":selected").data("id_tipe_kendaraan");
                var id_warna = $("#id_prospek").select2().find(":selected").data("id_warna");
                var warna = $("#id_prospek").select2().find(":selected").data("warna");
                var tipe_ahm = $("#id_prospek").select2().find(":selected").data("tipe_ahm");
                var id_prospek = $("#id_prospek").select2().find(":selected").data("id_prospek");
                form_.unit = [{
                  id_tipe_kendaraan: id_tipe_kendaraan,
                  id_warna: id_warna,
                  warna: warna,
                  tipe_ahm: tipe_ahm
                }];
                var values = {
                  id_prospek: id_prospek
                }
                console.log(form_.unit)
              }
            </script>
          <?php
          } elseif ($set == 'notif_outstanding') {   ?>
            <div class="box">
              <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">
                    <a href="dealer/prospek_crm">
                      <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
                      <table id="example2" class="table table-bordered table-hover">
                        <thead>
                          <th>Nama</th>
                          <th>No HP</th>
                          <th>Alamat</th>
                          <th>Catatan</th>
                        </thead>
                        <tbody>
                          <?php foreach ($prospek->result() as $val) :
                            $get_last_fol = $this->db->query("SELECT keterangan FROM tr_prospek_fol_up WHERE id_prospek='$val->id_prospek' ORDER BY id DESC LIMIT 1")->row()->keterangan;
                          ?>
                            <tr>
                              <td><?= scriptToHtml($val->nama_konsumen)  ?></td>
                              <td><?= scriptToHtml($val->no_hp)  ?></td>
                              <td><?= $val->alamat ?></td>
                              <td><?= scriptToHtml($get_last_fol) ?></td>
                            </tr>
                          <?php endforeach ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            <?php } ?>
        </section>
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
        function auto() {
          tampil_detail();
          cek_jenis();
          var tgl_js = document.getElementById("tgl").value;
          $.ajax({
            url: "<?php echo site_url('dealer/prospek_crm/cari_id') ?>",
            type: "POST",
            data: "tgl=" + tgl_js,
            cache: false,
            success: function(msg) {
              data = msg.split("|");
              $("#id_prospek").val(data[0]);
              $("#id_customer").val(data[1]);
              $("#id_list_appointment").val(data[2]);
            }
          })
        }

        function cek_jenis() {
          var jenis = $("#jenis").val();
          if (jenis == 'Instansi') {
            $("#span_kelompok_harga").show();
          } else {
            $("#span_kelompok_harga").hide();
          }
        }

        function takes() {
          take_sales();
          take_kec();
          var id_prospek_gc = $("#id_prospek_gc").val();
          tampil_detail(id_prospek_gc);
        }

        function take_sales() {
          var id_karyawan_dealer = $("#id_karyawan_dealer").val();
          $.ajax({
            url: "<?php echo site_url('dealer/prospek_crm/take_sales') ?>",
            type: "POST",
            data: "id_karyawan_dealer=" + id_karyawan_dealer,
            cache: false,
            success: function(msg) {
              data = msg.split("|");
              //$("#no_polisi").html(msg);                                                    
              $("#kode_sales").val(data[0]);
              $("#nama_sales").val(data[1]);
            }
          })
        }

        function chooseitem(id_kelurahan) {
          document.getElementById("id_kelurahan").value = id_kelurahan;
          take_kec();
          $("#Kelurahanmodal").modal("hide");
        }

        function take_kec() {
          var id_kelurahan = $("#id_kelurahan").val();
          $.ajax({
            url: "<?php echo site_url('dealer/prospek_crm/take_kec') ?>",
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
              $("#kode_pos").val(data[6]);
              $("#kelurahan").val(data[7]);
            }
          })
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
      </script>
      <script type="text/javascript">
        function getWarna() {
          //var nama_type = $(".modal_edit_detailkendaraan #kode_type").select2().find(":selected").data("nama_type");
          var id_tipe_kendaraan = $("#id_tipe_kendaraan").val();
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/prospek_crm/getWarna'); ?>",
            type: "POST",
            data: "id_tipe_kendaraan=" + id_tipe_kendaraan,
            /*   +"&ksu="+ksu
               +"&keterangan="+keterangan
               +"&tgl_pinjaman="+tgl_pinjaman,
            */
            cache: false,
            success: function(html) {
              $('#loading-status').hide();
              $('#id_warna').html(html);
              $("#warna_mode").val("ada");
            },
            statusCode: {
              500: function() {
                $('#loading-status').hide();
                alert("Something Wen't Wrong");
              }
            }
          });
        }

        function getProgramUmum() {
          let values = {
            id_tipe_kendaraan: $("#id_tipe_kendaraan").val(),
            id_warna: $("#id_warna").val(),
            jenis_beli: $("#rencana_pembayaran").val(),
          }
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/prospek_crm/getProgramUmum'); ?>",
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              $('#loading-status').hide();
              $('#program_utama').html('');
              $('#program_gabungan').html('');
              if (response.length > 0) {
                $('#program_utama').append($('<option>').text('--choose--').attr('value', ''));
                for (rsp of response) {
                  $('#program_utama').append($('<option>').text(rsp.id_program_md + ' | ' + rsp.judul_kegiatan).attr({
                    'value': rsp.id_program_md
                  }));
                }
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

        function getProgramGabungan() {
          let values = {
            program_utama: $("#program_utama").val(),
            id_tipe_kendaraan: $("#id_tipe_kendaraan").val(),
            id_warna: $("#id_warna").val(),
            jenis_beli: $("#rencana_pembayaran").val(),
          }
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/prospek_crm/getProgramGabungan'); ?>",
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              $('#loading-status').hide();
              $('#program_gabungan').html('');
              if (response.length > 0) {
                $('#program_gabungan').append($('<option>').text('--choose--').attr('value', ''));
                for (rsp of response) {
                  $('#program_gabungan').append($('<option>').text(rsp.id_program_md + ' | ' + rsp.judul_kegiatan).attr({
                    'value': rsp.id_program_md
                  }));
                }
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

        function getWarnaEdit() {
          //var nama_type = $(".modal_edit_detailkendaraan #kode_type").select2().find(":selected").data("nama_type");
          var id_tipe_kendaraan = $("#id_tipe_kendaraan").val();
          var id_warna_old = $("#id_warna_old").val();
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/prospek_crm/getWarnaEdit'); ?>",
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
                alert("Something Wen't Wrong");
              }
            }
          });
        }

        function getWarna2() {
          var mode = $("#warna_mode").val();
          if (mode == '') {
            getWarna();
          } else {
            return false;
          }
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
            url: "<?php echo site_url('dealer/prospek_crm/getWarna'); ?>",
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
                alert("Something Wen't Wrong");
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
            url: "<?php echo site_url('dealer/prospek_crm/getWarnaEdit'); ?>",
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
                alert("Something Wen't Wrong");
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
      </script>
      <script type="text/javascript">
        function getSelect2() {
          $(".select3").select2({
            allowClear: false
          });
        }

        function tampil_detail(a) {
          var value = {
            id: a
          }
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/prospek_crm/getDetail') ?>",
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

        function addDetail() {
          var id_tipe_kendaraan = $("#id_tipe_kendaraan_gc").val();
          var id_warna = $("#id_warna_gc").val();
          var qty = $("#qty_gc").val();
          var id_prospek_gc = $("#id_prospek_gc").val();
          $.ajax({
            url: "<?php echo site_url('dealer/prospek_crm/addDetail') ?>",
            type: "POST",
            data: "id_tipe_kendaraan=" + id_tipe_kendaraan + "&id_warna=" + id_warna + "&qty=" + qty + "&id_prospek_gc=" + id_prospek_gc,
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
            url: "<?php echo site_url('dealer/prospek_crm/delDetail') ?>",
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
            url: "<?php echo site_url('dealer/prospek_crm/edit_popup'); ?>",
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
          var id_prospek_gc = $("#id_prospek_gc").val();
          $.ajax({
            url: "<?php echo site_url('dealer/prospek_crm/saveEdit') ?>",
            type: "POST",
            data: "id_tipe_kendaraan=" + id_tipe_kendaraan + "&id_warna=" + id_warna + "&qty=" + qty + "&id_gc=" + id,
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
            url: "<?php echo site_url('dealer/prospek_crm/getWarnaEdit'); ?>",
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
                alert("Something Wen't Wrong");
              }
            }
          });
        }
      </script>