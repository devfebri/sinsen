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

<body>
  <?php $form = 'updateFollowUp'; ?>
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
              <form class="form-horizontal" method="post" enctype="multipart/form-data" id="form_">
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
                      <input type="text" class="form-control" value="<?= isset($row) ? $row->nama_lengkap : '' ?>" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">FLP ID *</label>
                    <div class="col-sm-4">
                      <input type="text" readonly class="form-control" required id="kode_sales" placeholder="FLP ID" name="id_flp_md" value="<?= $row->id_flp_md ?>">
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
                  <button class="btn btn-block btn-info btn-flat" disabled> INTERAKSI </button> <br>
                  <button type="button" class="btn btn-block btn-primary btn-flat btn-sm" onclick="showModalInteraksi(this,'<?= $row->id_prospek ?>')" style="width:10%;margin-bottom:10px">View All</button>
                  <div class="row">
                    <div class="col-sm-12 col-md-12">
                      <div class="table-responsive">
                        <table class='table table-condensed table-bordered'>
                          <thead>
                            <th>Kode Unit Motor</th>
                            <th>Warna Motor</th>
                            <th>Source Data</th>
                            <th>Platform Data</th>
                            <th>Keterangan</th>
                            <th>Customer Action Date</th>
                          </thead>
                          <tbody>
                            <?php foreach ($interaksi as $itr) { ?>
                              <tr>
                                <td><?= $itr->kodeTypeUnit ?></td>
                                <td><?= $itr->kodeWarnaUnit ?></td>
                                <td><?= $itr->descSourceLeads ?></td>
                                <td><?= $itr->descPlatformData ?></td>
                                <td><?= $itr->keterangan ?></td>
                                <td><?= $itr->customerActionDate ?></td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <?php $this->load->view('dealer/prospek_modal_interaksi'); ?>
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
                    <label for="inputEmail3" class="col-sm-2 control-label">Prioritas Prospect Customer *</label>
                    <div class="col-sm-4">
                      <select class='form-control' name='prioritas_prospek' v-model='prioritas_prospek' :disabled="mode=='detail'" required>
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
                      <select class="form-control" name="status_nohp" :disabled="mode=='detail'" required>
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
                      <select class="form-control" name="pekerjaan" onchange="pekerjaan_lain()" required :disabled="mode=='detail'">
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
                      <!-- <input type="text" class="form-control" maxlength="100" placeholder="Sebutkan" name="lain" value="<?= isset($row) ? $row->pekerjaan_lain : '' ?>" :disabled="mode=='detail'"> -->
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
                    <label for="inputEmail3" class="col-sm-2 control-label">No KTP/KITAS</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" onkeypress="return number_only(event)" placeholder="No KTP/KITAS" name="no_ktp" minlength=16 maxlength=16 value="<?= isset($row) ? $row->no_ktp : '' ?>" :disabled="mode=='detail'">
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
                    <label for="inputEmail3" class="col-sm-2 control-label">Tempat Lahir</label>
                    <div class="col-sm-4">
                      <input type="text" id="tempat_lahir" class="form-control" placeholder="Tempat Lahir" name="tempat_lahir" value="<?= isset($row) ? scriptToHtml($row->tempat_lahir) : '' ?>" :disabled="mode=='detail'">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl.Lahir</label>
                    <div class="col-sm-4">
                      <input type="text" id="tanggal4" class="form-control tgl_lahir" placeholder="Tgl.Lahir" name="tgl_lahir" onchange="cek_umur()" value="<?= isset($row) ? $row->tgl_lahir == '0000-00-00' ? '' : $row->tgl_lahir : '' ?>" :disabled="mode=='detail'">
                    </div>
                  </div>

                  <!-- check bug -->
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan </label>
                    <div class="col-sm-4">
                      <input type="hidden" readonly name="id_kelurahan" id="id_kelurahan" value="<?= isset($row) ? $row->id_kelurahan : '' ?>">
                      <input type="text" onpaste="return false" autocomplete="off" onkeypress="return nihil(event)" name="kelurahan" data-toggle="modal" placeholder="Kelurahan" class="form-control" id="kelurahan" onclick="showModalKelurahan()" :disabled="mode=='detail'">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan </label>
                    <div class="col-sm-4">
                      <input type="hidden" class="form-control" id="id_kecamatan" name="id_kecamatan" value="<?= isset($row) ? $row->id_kecamatan : '' ?>">
                      <input type="text" readonly class="form-control" id="kecamatan" placeholder="Kecamatan" name="kecamatan" value="<?= isset($row) ? $row->kec : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten/Kota </label>
                    <div class="col-sm-4">
                      <input type="hidden" class="form-control" id="id_kabupaten" name="id_kabupaten" value="<?= isset($row) ? $row->id_kabupaten : '' ?>">
                      <input type="text" readonly class="form-control" id="kabupaten" placeholder="Kabupaten" name="kabupaten" value="<?= isset($row) ? $row->kab : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Provinsi </label>
                    <div class="col-sm-4">
                      <input type="hidden" class="form-control" id="id_provinsi" name="id_provinsi" value="<?= isset($row) ? $row->id_provinsi : '' ?>">
                      <input type="text" readonly class="form-control" id="provinsi" placeholder="Provinsi" name="provinsi" value="<?= isset($row) ? $row->prov : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" maxlength="100" placeholder="Alamat" name="alamat" value="<?= isset($row) ? $row->alamat : '' ?>" :disabled="mode=='detail'">
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
                      <select class="form-control" name="merk_sebelumnya" :disabled="mode=='detail'">
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
                    <label for="inputEmail3" class="col-sm-2 control-label">Yang menggunakan sepeda motor</label>
                    <div class="col-sm-4">
                      <select class="form-control" name="pemakai_motor" v-model="pemakai_motor" :disabled="mode=='detail'">
                        <option value="">- choose -</option>
                        <option>Saya Sendiri</option>
                        <option>Anak</option>
                        <option>Pasangan Suami/Istri</option>
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Status Prospek </label>
                    <div class="col-sm-4">
                      <input class="form-control" name="status_prospek_header" v-model='status_prospek' readonly>
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
                      <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Kantor </label>
                      <div class="col-md-4">
                        <input type="text" class="form-control" id="kelurahan_kantor" id='kelurahan_kantor' onpaste="return false" autocomplete="off" onkeypress="return nihil(event)" placeholder='Klik Untuk Memilih' onclick="showModalKelurahan('kantor')" value="<?= isset($row) ? $row->kel_kantor : '' ?>">
                        <input type='hidden' id='id_kelurahan_kantor' name='id_kelurahan_kantor' value="<?= isset($row) ? $row->id_kelurahan_kantor : '' ?>">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Kantor </label>
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
                    <label for="inputEmail3" class="col-sm-2 control-label">Jenis Customer</label>
                    <div class="col-md-4">
                      <select name="jenis_customer" class="form-control" v-model='jenis_customer' :disabled="mode=='detail'">
                        <option value="">--choose--</option>
                        <option value="regular">Regular</option>
                        <option value="group_customer">Group Customer</option>
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Sumber Prospek *</label>
                    <div class="col-md-4">
                      <select name="sumber_prospek" class="form-control" v-model='sumber_prospek' :disabled="mode=='detail' || '<?= $row->platformData ?>'!='D'" required>
                        <option value="">--choose--</option>
                        <?php
                        foreach ($set_sumber_prospek as $rw) : ?>
                          <option value="<?php echo $rw->id_dms ?>" <?= $rw->id_dms == $row->sumber_prospek ? 'selected' : '' ?>><?php echo $rw->description ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Test Ride Preference</label>
                    <div class="col-md-4">
                      <select name="test_ride_preference" class="form-control" v-model='test_ride_preference' :disabled="mode=='detail'">
                        <option value="">--choose--</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Rencana Pembayaran</label>
                    <div class="col-md-4">
                      <select id='rencana_pembayaran' name="rencana_pembayaran" class="form-control" v-model='rencana_pembayaran' :disabled="mode=='detail'">
                        <option value="">--choose--</option>
                        <option value="cash">Cash</option>
                        <option value="kredit">Kredit</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group" v-if="test_ride_preference==1">
                    <label for="inputEmail3" class="col-sm-2 control-label">Jadwal Riding Test (Tanggal)</label>
                    <div class="col-md-4">
                      <input type="date" class="form-control" name="tgl_tes_kendaraan" value="<?= isset($row) ? scriptToHtml($row->tgl_tes_kendaraan) : '' ?>" :disabled="mode=='detail'">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Jadwal Riding Test (Jam)</label>
                    <div class="col-md-4">
                      <input type="time" class="form-control" name="jam_tes_kendaraan" value="<?= isset($row) ? scriptToHtml($row->jam_tes_kendaraan) : '' ?>" :disabled="mode=='detail'">
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
                  <div class="form-group">
                    <label class='col-sm-2 control-label'>Facebook</label>
                    <div class="col-sm-4">
                      <input class='form-control' value="<?= $row->facebook ?>" readonly>
                    </div>
                    <label class='col-sm-2 control-label'>Instagram</label>
                    <div class="col-sm-4">
                      <input class='form-control' value="<?= $row->instagram ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class='col-sm-2 control-label'>Twitter</label>
                    <div class="col-sm-4">
                      <input class='form-control' value="<?= $row->twitter ?>" readonly>
                    </div>
                  </div>
                  <button class="btn btn-block btn-info btn-flat" disabled> TEST RIDE </button> <br>
                  <div class="form-group">
                    <label for="TestRideStatus" class="col-sm-2 control-label">Status Riding Test</label>
                    <div class="col-md-4">
                      <select name="TestRideStatus" class="form-control" v-model='TestRideStatus' :disabled="mode=='detail'">
                        <option value="">--choose--</option>
                        <option value="1">Jadi</option>
                        <option value="0">Tidak Jadi</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="TestRideNote" class="col-sm-2 control-label">Catatan Riding Test</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="TestRideNote" value="<?= isset($row) ? scriptToHtml($row->TestRideNote) : '' ?>" :disabled="mode=='detail'">
                    </div>
                  </div>
                  <button class="btn btn-block btn-info btn-flat" disabled> TRADE IN </button> <br>
                  <div class="form-group">
                    <label for="TradeInStatus" class="col-sm-2 control-label">Status Trade In</label>
                    <div class="col-md-4">
                      <select name="TradeInStatus" class="form-control" v-model='TradeInStatus' :disabled="mode=='detail'">
                        <option value="">--choose--</option>
                        <option value="1">Jadi</option>
                        <option value="0">Tidak Jadi</option>
                      </select>
                    </div>
                    <label for="DealPrice" class="col-sm-2 control-label">Deal Price</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" name="DealPrice" value="<?= isset($row) ? scriptToHtml($row->DealPrice) : '' ?>" :disabled="mode=='detail'">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="TradeInNote" class="col-sm-2 control-label">Catatan Trade In</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="TradeInNote" value="<?= isset($row) ? scriptToHtml($row->TradeInNote) : '' ?>" :disabled="mode=='detail'">
                    </div>
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
                  <button class="btn btn-block btn-danger btn-flat" disabled> FOLLOW UP/LIST APPOINTMENT </button> <br>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Batas Waktu SLA 2</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" disabled value="<?= $row->batasOnTimeSLA2 ?>" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Next Follow Up MD</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" disabled value="<?= $row->tanggalNextFU ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Keterangan Follow Up MD</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" disabled value="<?= $row->keteranganNextFollowUp ?>" readonly>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="table-responsive">
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
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align='center'>
                    <?php if ($mode == 'edit') { ?>
                      <button type="button" onclick="updateFollowUpData()" id="btnUpdateData" class="btn btn-info btn-flat btnUpdate">Updated Data</button>
                      <?php
                      if ($is_sales == false) { ?>
                        <button type="button" onclick="createSPK()" id="btnCreateSPK" class="btn btn-success btn-flat btnUpdate">Create SPK</button>
                      <?php }
                      ?>
                    <?php } ?>
                    <!-- <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button> -->
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>
      <?php
      $data['data'] = ['kelurahan_prospek_crm'];
      $this->load->view('dealer/h2_api', $data); ?>
      <script>
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
        $("#tglNextFollowUp").datepicker();
        $(document).ready(function() {
          var temp_id_kel = $('#id_kelurahan').val();
          chooseitem(temp_id_kel);

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

        function chooseitem(id_kelurahan) {
          if(kelurahan_untuk == 'kantor'){
            take_kec_kantor(id_kelurahan);
          }else{
            take_kec(id_kelurahan);
          }                   
          $("#Kelurahanmodal").modal("hide");
        }

        function take_kec(id_kelurahan) {
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
              $("#id_kelurahan").val(id_kelurahan);
            }
          })
        }

        function take_kec_kantor(id_kelurahan) {
          $.ajax({
            url: "<?php echo site_url('dealer/prospek_crm/take_kec') ?>",
            type: "POST",
            data: "id_kelurahan=" + id_kelurahan,
            cache: false,
            success: function(msg) {
              data = msg.split("|");
              $('#id_kelurahan_kantor').val(id_kelurahan);
              $('#kelurahan_kantor').val(data[7]);
              $('#kecamatan_kantor').val(data[1]);
              $('#kabupaten_kantor').val(data[3]);
              $('#provinsi_kantor').val(data[5]);
            }
          })
        }
        
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
            tgl_assign: '<?= isset($row) ? $row->tgl_assign : '' ?>',
            jam_assign: '<?= isset($row) ? $row->jam_assign : '' ?>',
            TestRideStatus: '<?= isset($row) ? $row->TestRideStatus : '' ?>',
            TradeInStatus: '<?= isset($row) ? $row->TradeInStatus : '' ?>',
            detail: {
              tgl_fol_up: '<?= tanggal() ?>',
              waktu_fol_up: '<?= jam() ?>',
              metode_fol_up: '',
              keterangan: '',
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
              status_prospek: '',
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
              if (this.detail.kodeAlasanNotProspectNotDeal == '5' && this.detail.alasanNotProspectNotDealLainnya == '') {
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

        function updateFollowUpData() {
          // Cek apakah sudah ada hasil status fol. up prospek untuk kirim stage 8
          ada_prospek = 0;
          for (dt of form_.details) {
            if (dt.kodeHasilStatusFollowUp == '1') {
              ada_prospek = 1;
            }
          }
          if (ada_prospek === 0) {
            alert("Hasil status follow up = 'prospect' belum ditentukan");
            return false;
          }
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
        }

        function createSPK() {
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
            // Cek apakah sudah ada hasil status fol. up prospek untuk kirim stage 8
            ada_prospek = 0;
            for (dt of form_.details) {
              if (dt.kodeHasilStatusFollowUp == '1') {
                ada_prospek = 1;
              }
            }
            if (ada_prospek === 0) {
              alert("Hasil status follow up = 'prospect' belum ditentukan");
              return false;
            }
            if (confirm("Apakah anda yakin ?") == true) {
              var values = new FormData($('#form_')[0]);
              values.append('details', JSON.stringify(form_.details));
              $.ajax({
                beforeSend: function() {
                  $('#btnCreateSPK').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('.btnUpdate').attr('disabled', true);
                },
                enctype: 'multipart/form-data',
                url: '<?= base_url('dealer/prospek_crm/createSPK') ?>',
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
                  $('#btnCreateSPK').html('Create SPK');
                },
                error: function() {
                  alert("Something Went Wrong !");
                  $('#btnCreateSPK').html('Create SPK');
                  $('.btnUpdate').attr('disabled', false);

                }
              });
            }
          } else {
            alert('Silahkan isi field required !')
          }
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
      </script>
    </section>
  </div>