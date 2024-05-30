<?php if ($set != 'download_excel') : ?>
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
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
        <li class="">H2</li>
        <li class="">Claim</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">
    <?php endif ?>
    <?php
    if ($set == "form") {
      $form = '';
      $readonly = '';
      $disabled = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'approve') {
        $form = 'save_approve';
        $readonly = 'readonly';
        $disabled = 'disabled';
      }
      if ($mode == 'reject') {
        $form = 'save_reject';
        $readonly = 'readonly';
        $disabled = 'disabled';
      }
      if ($mode == 'edit') {
        $form = 'save_edit';
        $readonly = 'readonly';
        $disabled = '';
      }
      if ($mode == 'detail') {
        $readonly = 'readonly';
        $disabled = 'disabled';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <script>
        Vue.use(VueNumeric.default);
        $(document).ready(function() {
          <?php if (isset($row)) { ?>
            pilihAHASS(<?= json_encode($dealer) ?>)
          <?php } ?>
        })
        Vue.filter('toCurrency', function(value) {
          // console.log("type value ke currency filter" ,  value, typeof value, typeof value !== "number");
          if (typeof value !== "number") {
            return value;
          }
          return accounting.formatMoney(value, "", 0, ".", ",");
          return value;
        });
      </script>

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/lkh">
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
          <div class="row">
            <div class="col-md-12">
              <form id="form_" class="form-horizontal" action="h2/lkh/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode AHASS *</label>
                    <div class="col-sm-4">
                      <input type="text" required readonly onclick="showModalAHASS()" class="form-control" placeholder="Klik Untuk Memilih" id="kode_ahass" value="<?= isset($row) ? $row->kode_dealer_md : '' ?>">
                      <input type="hidden" name="id_dealer" id="id_dealer" value="<?= isset($row) ? $row->id_dealer : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama AHASS *</label>
                    <div class="col-sm-4">
                      <input type="text" required onclick="showModalAHASS()" class="form-control" id="nama_ahass" readonly value="<?= isset($row) ? $row->nama_dealer : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kategori Claim</label>
                    <div class="col-sm-4">
                      <select class='form-control' name='kategori_claim' v-model="kategori_claim" :disabled="mode=='detail'">
                        <option value=''>-choose-</option>
                        <option value='C1'>C1</option>
                        <option value='C2'>C2</option>
                      </select>
                    </div>
                    <label for="inputEmail3"  class="col-sm-2 control-label">No. WO</label>
                    <div class="col-sm-3">
                      <input type="text" required class="form-control" id="id_work_order" name="id_work_order" readonly value="<?= isset($row) ? $row->id_work_order : '' ?>" onclick="showModalWO()">
                    </div>
                    <div class="col-sm-1" v-if="(mode=='insert' || mode=='edit')">
                      <button id="searchWO" type="button" onclick="showModalWO()" class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                  <div class="form-group" v-if="kategori_claim=='C2'">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Buku Claim C2</label>
                    <div class="col-sm-4">
                      <input type="text" readonly id="no_buku_claim_c2" class="form-control" value="<?= isset($row) ? $row->no_buku_claim_c2 : '' ?>" disabled>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Claim C2</label>
                    <div class="col-sm-4">
                      <input type="text" readonly id="no_claim_c2" value="<?= isset($row) ? $row->no_claim_c2 : '' ?>" class="form-control" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <?php if (isset($row)) : ?>
                      <input type="hidden" value="<?= $row->id_lkh ?>" name="id_lkh">
                    <?php endif ?>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl LKH</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" placeholder="Tgl LKH" name="tgl_lkh" readonly value="<?= isset($row) ? $row->tgl_lkh : date('d/m/Y') ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tech Serv. AHM</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" placeholder="Tech Serv. AHM" name="tech_serv_ahm" <?= $disabled ?> value="<?= isset($row) ? $row->tech_serv_ahm : '' ?>">
                      </div>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Cc.Tech Serv. MD</label>
                      <div class="col-sm-4">
                        <input type="text" required class="form-control" id="cc_tech_serv_ahm" name="cc_tech_serv_ahm" <?= $disabled ?> value="<?= isset($row) ? $row->cc_tech_serv_ahm : '' ?>">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Model</label>
                    <div class="col-sm-4">
                      <?php $tipe_kendaraan = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active=1 order BY id_tipe_kendaraan") ?>
                      <select name="kode_model" id="kode_model" class="form-control select2" <?= $disabled ?>>
                        <option value="">-choose-</option>
                        <?php foreach ($tipe_kendaraan->result() as $rs) :
                          $select = isset($row) ? $row->kode_model == $rs->id_tipe_kendaraan ? 'selected' : '' : '';
                        ?>
                          <option value="<?= $rs->id_tipe_kendaraan ?>" <?= $select ?>><?= $rs->id_tipe_kendaraan . ' | ' . $rs->tipe_ahm ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tema</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="tema" <?= $disabled ?> value="<?= isset($row) ? $row->tema : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Part Utama</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="part_utama" name="part_utama" readonly value="<?= isset($row) ? $row->part_utama : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Part Utama</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="nama_part" name="nama_part" readonly value="<?= isset($row) ? $row->nama_part : '' ?>">
                      <input type="hidden" class="form-control" id="ongkos_kerja" name="ongkos_kerja" readonly value="<?= isset($row) ? $row->ongkos_kerja : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Symptom Code</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="symptom_code" name="symptom_code" readonly required value="<?= isset($row) ? $row->symptom_code : '' ?>" onclick="showModalSymptom()" placeholder='Klik Untuk Memilih'>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Symptom ID</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="symptom_id" readonly required value="<?= isset($row) ? $row->symptom_id : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Grade</label>
                    <div class="col-sm-4">
                      <select name="grade" class="form-control" <?= $disabled ?> v-model='row.grade' required>
                        <option value="">-choose-</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Telepon</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id='no_telepon' name="no_telepon" disabled value="<?= isset($row) ? $row->no_telepon : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Kota</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="id_kabupaten" id="id_kabupaten" readonly value="<?= isset($row) ? $row->id_kabupaten : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kota</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id='kabupaten' name="kabupaten" disabled value="<?= isset($row) ? $row->kabupaten : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Pelapor</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="pelapor" id='pelapor' value="<?= isset($row) ? $row->pelapor : '' ?>" <?= $disabled ?> required readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kepala Bengkel</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="kepala_bengkel" value="<?= isset($row) ? $row->kepala_bengkel : '' ?>" <?= $disabled ?>>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Gejala</label>
                    <div class="col-sm-10">
                      <textarea <?= $disabled ?> class='form-control' name="gejala" id="gejala" rows="2"><?= isset($row) ? $row->gejala : '' ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Ilustrasi</label>
                    <div class="col-sm-4">
                      <input type="file" class="form-control" name="file_ilustrasi">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Diagnosis</label>
                    <div class="col-sm-10">
                      <textarea name="diagnosis" id="diagnosis" rows="" class='form-control' :disabled="mode=='detail'"><?= isset($row) ? $row->diagnosis : '' ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Penyebab Utama</label>
                    <div class="col-sm-10">
                      <textarea name="penyebab_utama" id="penyebab_utama" rows="2" class='form-control' :disabled="mode=='detail'"><?= isset($row) ? $row->penyebab_utama : '' ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tindakan Sementara</label>
                    <div class="col-sm-10">
                      <textarea name="tindakan_sementara" id="tindakan_sementara" rows="2" class='form-control' :disabled="mode=='detail'"><?= isset($row) ? $row->tindakan_sementara : '' ?></textarea>
                    </div>
                  </div>
                  <br>
                  <button class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Statistik Kejadian Kasus 6 Periode Terakhir</button>
                  <br><br><br>
                  <table class="table table-bordered">
                    <tr v-for="(sts, index) of statistik">
                      <td width="25%">Periode {{sts.periode}}
                        <input type="hidden" v-model="sts.periode" name="periode[]">
                      </td>
                      <td width="30">{{sts.bulan | convertBulan}}
                        <input type="hidden" v-model="sts.bulan" name="bulan[]">
                      </td>
                      <td width="25%">Jumlah Kejadian</td>
                      <td width="20%">
                        {{sts.jml_kejadian}}
                        <input type="hidden" v-model="sts.jml_kejadian" name="jml_kejadian[]">
                      </td>
                    </tr>
                  </table>
                  <br>
                  <button class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Part Terkait</button>
                  <br><br><br>
                  <table class="table table-bordered">
                    <thead>
                      <th>ID Part</th>
                      <th>Deskripsi Part</th>
                    </thead>
                    <tbody>
                      <tr v-for="(prt, index) of part_terkait">
                        <td>{{prt.id_part}}
                          <input type='hidden' name="id_part[]" v-model='prt.id_part' />
                        </td>
                        <td>{{prt.nama_part}}</td>
                      </tr>
                    </tbody>
                  </table>
                  <br>
                  <button class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Kejadian</button>
                  <br><br><br>
                  <table class="table">
                    <thead>
                      <th>Tanggal Pembelian</th>
                      <th>Tanggal Kejadian</th>
                      <th>jam</th>
                      <th>KM</th>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                    </thead>
                    <tbody>
                      <tr>
                        <td><input type="text" readonly id="tgl_pembelian" name="tgl_pembelian" class="form-control isi" <?= $disabled ?> value="<?= isset($row) ? $row->tgl_pembelian : '' ?>"></td>
                        <td><input type="text" readonly id="tgl_kejadian" name="tgl_kejadian" class="form-control isi" <?= $disabled ?> value="<?= isset($row) ? $row->tgl_kejadian : '' ?>"></td>
                        <td><input type="text" readonly id="jam" name="jam" <?= $disabled ?> v-model="row.jam" class="form-control isi"></td>
                        <td><input type="number" readonly id='km' name="km" <?= $disabled ?> v-model="row.km" class="form-control isi"></td>
                        <td><input type="text" readonly id='no_mesin' name="no_mesin" <?= $disabled ?> v-model="row.no_mesin" class="form-control isi"></td>
                        <td><input type="text" readonly id='no_rangka' name="no_rangka" <?= $disabled ?> v-model="row.no_rangka" class="form-control isi"></td>
                      </tr>
                    </tbody>
                  </table>

                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" style="text-align: center;" v-if="mode=='insert'||mode=='edit'">
                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  </div>
                  <div class="col-sm-12" style="text-align: center;" v-if="mode=='approve'">
                    <button type="submit" onclick="return confirm('Apakah anda yakin ?')" name="save" value="save" class="btn btn-success btn-flat">Approve</button>
                  </div>
                  <div class="col-sm-12" style="text-align: center;" v-if="mode=='reject'">
                    <button type="submit" onclick="return confirm('Apakah anda yakin ?')" name="save" value="save" class="btn btn-danger btn-flat">Reject</button>
                  </div>
                  <div class="col-sm-12" style="text-align: center;" v-if="mode=='perbaikan'">
                    <button type="submit" onclick="return confirm('Apakah anda yakin ?')" name="save" value="save" class="btn btn-primary btn-flat">Save</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <?php
      $data['data'] = [
        'WOProses', 'id_claim_not_in_lkh', 'wo_selesai', 'filter_id_dealer', 'wo_c2'
      ];
      $this->load->view('dealer/h2_api', $data); ?>
      <?php
      $data = ['data' => ['modalAHASS', 'all_parts', 'modalSymptom']];
      $this->load->view('h2/api', $data);
      ?>
      <script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>
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

        function pilihSymptom(par) {
          $('#symptom_code').val(par.symptom_code);
          $('#symptom_id').val(par.symptom_id);
        }

        function showModalWO() {
          if ($('#id_dealer').val() === '') {
            alert('Silahkan pilih AHASS terlebih dahulu!');
            return false;
          }
          if ($('#kategori_claim').val() === '') {
            alert('Silahkan pilih kategori claim terlebih dahulu !')
            return false;
          }
          showModalWOProses()
        }

        function pilihWO(wo) {
          values = {
            id_sa_form: wo.id_sa_form,
            id_dealer: $('#id_dealer').val()
          }
          $.ajax({
            beforeSend: function() {
              $('#searchWO').html('<i class="fa fa-spinner fa-spin"></i>');
              $('#searchWO').attr('disabled', true);
            },
            url: '<?= base_url('dealer/work_order_dealer/getDataWO') ?>',
            type: "POST",
            data: values,
            cache: false,
            async: false,
            dataType: 'JSON',
            success: function(response) {
              $('#searchWO').html('<i class="fa fa-search"></i>');
              $('#searchWO').attr('disabled', false);
              if (response.status == 'sukses') {
                $('#id_work_order').val(response.sa.id_work_order);
                $('#no_buku_claim_c2').val(response.sa.no_buku_claim_c2);
                $('#no_claim_c2').val(response.sa.no_claim_c2);
                $('#gejala').val(response.sa.keluhan_konsumen);
                $('#diagnosis').val(response.sa.rekomendasi_sa);
                tgl_pembelian = date_dmy(response.sa.tgl_pembelian);
                $('#tgl_pembelian').val(tgl_pembelian);
                $('#tgl_kejadian').val(response.sa.tgl_servis);
                $('#jam').val(response.sa.jam_servis);
                $('#km').val(response.sa.km_terakhir);
                $('#no_mesin').val(response.sa.no_mesin);
                $('#no_rangka').val(response.sa.no_rangka);
                $('#id_kabupaten').val(response.sa.id_kabupaten);
                $('#kabupaten').val(response.sa.kabupaten);
                $('#no_telepon').val(response.sa.no_hp);
                $('#pelapor').val(response.sa.mekanik);
                $('#kode_model').val(response.sa.id_tipe_kendaraan).trigger('change');
                form_.row.no_mesin = response.sa.no_mesin;
                form_.row.no_rangka = response.sa.no_rangka;
                form_.row.km = response.sa.km_terakhir;
                form_.row.jam = response.sa.jam_servis;
                form_.part_terkait = [];
                for (pkj of response.details) {
                  if (pkj.id_type === 'C2') {
                    for (prt of pkj.parts) {
                      if (prt.part_utama == 1) {
                        $('#part_utama').val(prt.id_part);
                        $('#nama_part').val(prt.nama_part);
                        $('#ongkos_kerja').val(pkj.harga);
                      } else {
                        form_.part_terkait.push(prt);
                      }
                    }
                  }else  if (pkj.id_type === 'C1') {
                    for (prt of pkj.parts) {
                      if (prt.part_utama == 1) {
                        $('#part_utama').val(prt.id_part);
                        $('#nama_part').val(prt.nama_part);
                        $('#ongkos_kerja').val(pkj.harga);
                      } else {
                        form_.part_terkait.push(prt);
                      }
                    }
                  }
                }
                params_statistik = {
                  id_part: $('#part_utama').val()
                }
                reset_statistik(params_statistik)
              } else {
                alert(response.pesan);
                form_.details = [];
              }
            },
            error: function() {
              alert("Something Went Wrong !");
              $('#searchWO').html('<i class="fa fa-search"></i>');
              $('#searchWO').attr('disabled', false);
            },
          });
        }

        function reset_statistik(params) {
          $.ajax({
            beforeSend: function() {
              $('#searchWO').html('<i class="fa fa-spinner fa-spin"></i>');
              $('#searchWO').attr('disabled', true);
            },
            url: '<?= base_url('h2/lkh/refresh_statistik') ?>',
            type: "POST",
            data: params,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              $('#searchWO').html('<i class="fa fa-search"></i>');
              $('#searchWO').attr('disabled', false);
              if (response.status == 'sukses') {
                form_.statistik = response.data
              }
            },
            error: function() {
              alert("Something Went Wrong !");
              $('#searchWO').html('<i class="fa fa-search"></i>');
              $('#searchWO').attr('disabled', false);
            },
          });
        }

        function pilihAHASS(ahass) {
          $('#kode_ahass').val(ahass.kode_dealer_md);
          $('#nama_ahass').val(ahass.nama_dealer);
          $('#id_dealer').val(ahass.id_dealer);
        }

        function setModalPart(el) {
          form_.el_part = el.name;
          showmodalAllParts();
        }

        function pilihPart(part) {
          console.log(form_.el_part)
          $('#' + form_.el_part).val(part.id_part);
        }
        var form_ = new Vue({
          el: '#form_',
          el_part: '',
          data: {
            kategori_claim: '<?= isset($row) ? $row->kategori_claim : '' ?>',
            mode: '<?= $mode ?>',
            detail: {
              id_part: '',
              nama_part: '',
              jumlah: '',
              tipe_penggantian: '',
              harga: '',
              ongkos: '',
              status_part: ''
            },
            part_terkait: <?= isset($part_terkait) ? json_encode($part_terkait) : '[]' ?>,
            row: <?= isset($row) ? json_encode($row) : '[]' ?>,
            statistik: <?= isset($dt_statistik) ? json_encode($dt_statistik) : json_encode($statistik) ?>,
          },
          filters: {
            convertBulan: function(value) {
              var val = parseInt(value);
              switch (val) {
                case 1:
                  return "Januari";
                  break;
                case 2:
                  return "Februari";
                  break;
                case 3:
                  return "Maret";
                  break;
                case 4:
                  return "April";
                  break;
                case 5:
                  return "Mei";
                  break;
                case 6:
                  return "Juni";
                  break;
                case 7:
                  return "Juli";
                  break;
                case 8:
                  return "Agustus";
                  break;
                case 9:
                  return "September";
                  break;
                case 10:
                  return "Oktober";
                  break;
                case 11:
                  return "November";
                  break;
                case 12:
                  return "Desember";
                  break;
              }
            },

          },
          methods: {
            total: function(show) {
              var totQty = 0;
              var grandTotal = 0;
              if (this.details.length > 0) {
                for (dtl of this.details) {
                  totQty += dtl.qty;
                  grandTotal += this.totalHarga(dtl);
                }
              }
              if (show == 'qty') {
                return totQty
              }
              if (show == 'grandtotal') {
                return grandTotal
              }
            },
            totalHarga: function(dtl) {
              return parseInt(this.hargaDiskon(dtl) * dtl.qty);
            },
            hargaDiskon: function(dtl) {
              return parseInt(dtl.harga_material - dtl.diskon);
            },
            showModalAHASS: function() {
              // $('#tbl_part').DataTable().ajax.reload();
              $('.modalAHASS').modal('show');
            },
            showModalPart: function() {
              // $('#tbl_part').DataTable().ajax.reload();
              $('.modalPart').modal('show');
            },
            showModalKelurahan: function() {
              // $('#tbl_part').DataTable().ajax.reload();
              $('.modalKelurahan').modal('show');
            },
            clearDetail: function() {
              this.detail = {
                id_part: '',
                nama_part: '',
                jumlah: '',
                tipe_penggantian: '',
                harga: '',
                ongkos: '',
                status_part: ''
              }
            },
            addDetails: function() {
              this.details.push(this.detail);
              this.clearDetail();
            },
            delDetails: function(index) {
              this.details.splice(index, 1);
            },
          }
        })
        $('#submitBtn').click(function() {
          $('#form_').validate({
            rules: {
              'checkbox': {
                required: true
              }
            },
            highlight: function(input) {
              $(input).parents('.form-input').addClass('has-error');
            },
            unhighlight: function(input) {
              $(input).parents('.form-input').removeClass('has-error');
            }
          })
          var values = {
            details: form_.details
          };
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          if ($('#form_').valid()) // check if form is valid
          {
            $('#form_').submit();
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
            <a href="h2/lkh/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
          <table id="example4" class="table table-hover">
            <thead>
              <tr>
                <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                <!-- <th width="5%">No</th>               -->
                <th>No LKH</th>
                <th>Tgl LKH</th>
                <th>Kode AHASS</th>
                <th>Nama AHASS</th>
                <th>Kode Model</th>
                <th>Tema</th>
                <th>No Part Utama</th>
                <th>Symptom Code</th>
                <th>Grade</th>
                <th>Pelapor</th>
                <th width="8%" align="center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // $no=1; 
              foreach ($dt_result->result() as $rs) {
                $status = '';
                $button = '';
                $btn_edit = "<a class='btn btn-warning btn-xs' href='" . base_url('h2/lkh/edit?id=') . "$rs->id_lkh'>Edit</a>";
                $button = $btn_edit;
                echo "
            <tr>
              <td><a href='" . base_url('h2/lkh/detail?id=') . "$rs->id_lkh'>$rs->id_lkh</a></td>
              <td>$rs->tgl_lkh</td>
              <td>$rs->kode_dealer_md</td>
              <td>$rs->nama_dealer</td>
              <td>$rs->kode_model</td>
              <td>$rs->tema</td>
              <td>$rs->part_utama</td>
              <td>$rs->symptom_code</td>
              <td>$rs->grade</td>
              <td>$rs->pelapor</td>
              <td>$button</td>   
            </tr> ";
              }
              ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    } elseif ($set == 'download_excel') {
      header("Content-type: application/octet-stream");
      header("Content-Disposition: attachment; filename=Rekap Claim Waranty.xls");
      header("Pragma: no-cache");
      header("Expires: 0");
    ?>
      <div align="center" style="font-weight: bold">Rekap Claim Waranty</div>
      <table border="1" width="100%">
        <tr>
          <td>No.</td>
          <td>No Registrasi Claim</td>
          <td>Tanggal Pengajuan</td>
          <td>Kode AHASS</td>
          <td>Nama AHASS</td>
          <td>No Mesin</td>
          <td>No Rangka</td>
          <td>Tanggal Pembelian</td>
          <td>Tanggal Kerusakan</td>
        </tr>
        <?php $no = 1;
        foreach ($rekap->result() as $rs) : ?>
          <tr>
            <td><?= $no ?></td>
            <td><?= $rs->no_registrasi ?></td>
            <td><?= $rs->tgl_pengajuan ?></td>
            <td><?= $rs->kode_dealer_md ?></td>
            <td><?= $rs->nama_dealer ?></td>
            <td><?= $rs->no_mesin ?></td>
            <td><?= $rs->no_rangka ?></td>
            <td><?= $rs->tgl_pembelian ?></td>
            <td><?= $rs->tgl_kerusakan ?></td>
          </tr>
          <tr>
            <td></td>
            <td colspan="8"><b>Detail</b></td>
          </tr>
          <?php $detail = $this->db->query("SELECT tr_lkh_detail.*,nama_part FROM tr_lkh_detail 
      JOIN ms_part ON tr_lkh_detail.id_part=ms_part.id_part
      WHERE id_rekap_claim='$rs->id_rekap_claim'"); ?>
          <tr>
            <td></td>
            <td>Kode Part</td>
            <td>Nama Part</td>
            <td>Jumlah</td>
            <td>Tipe Penggantian</td>
            <td>HET</td>
            <td>Ongkos Kerja</td>
          </tr>
          <?php foreach ($detail->result() as $dtl) : ?>
            <tr>
              <td></td>
              <td><?= $dtl->id_part ?></td>
              <td><?= $dtl->nama_part ?></td>
              <td><?= $dtl->jumlah ?></td>
              <td><?= $dtl->tipe_penggantian ?></td>
              <td><?= $dtl->harga ?></td>
              <td><?= $dtl->ongkos ?></td>
            </tr>
          <?php endforeach ?>
        <?php $no++;
        endforeach ?>
      </table>
    <?php } ?>
    <?php if ($set != 'download_excel') : ?>
    </section>
  </div>
<?php endif ?>