<base href="<?php echo base_url(); ?>" />
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

  .w-10 {
    width: 10%
  }
</style>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">H2</li>
      <li class="">AHASS Network</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>
  <section class="content">
    <?php
    if ($set == "form") {
      $disabled = '';
      $form = '';
      if ($mode == 'insert') {
        $form = 'save';
      } elseif ($mode == 'edit') {
        $form = 'save_edit';
      }
    ?>
      <script>
        $(document).ready(function() {})
      </script>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/super_visi">
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
              <form id="form_" class="form-horizontal" action="h2/super_visi/save" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <!-- <div class="form-group" v-if="mode!='insert'">
                    <label for="inputEmail3" class="col-sm-2 control-label">ID Supervisi</label>
                    <div class="col-sm-4">
                      
                    </div>
                  </div> -->
                  <input type="hidden" class="form-control datepicker" name="id_supervisi" autocomplete="off" required value="<?= isset($row) ? $row->id_supervisi : '' ?>" readonly>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl Supervisi</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control datepicker" name="tgl_supervisi" autocomplete="off" required value="<?= isset($row) ? $row->tgl_supervisi : date('Y-m-d') ?>" :disabled="mode=='detail' || mode=='hasil'">
                    </div>
                    <label for="inputEmail3" class="col-sm-1 control-label">Quartal</label>
                    <div class="col-sm-1">
                      <select class="form-control" name="quartal" v-model="quartal" onchange="setQuartal(this)" :disabled="mode=='detail'||mode=='hasil'">
                        <option value=1>1</option>
                        <option value=2>2</option>
                        <option value=3>3</option>
                        <option value=4>4</option>
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-1 w-10 control-label">Start Date</label>
                    <div class="col-sm-2">
                      <input type="text" class="form-control datepicker" id="start_date" name="start_date" autocomplete="off" required onchange="setTglQuartal()" :readonly="mode=='detail' || mode=='hasil'">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Agenda</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" placeholder="Agenda" name="agenda" autocomplete="off" value="<?= isset($row) ? $row->agenda : '' ?>" :disabled="mode=='detail' || mode=='hasil'">
                    </div>
                    <label for="inputEmail3" class="col-sm-offset-2 col-sm-1 w-10 control-label">End Date</label>
                    <div class="col-sm-2">
                      <input type="text" class="form-control datepicker" name="end_date" id="end_date" autocomplete="off" required onchange="setTglQuartal()" :readonly="mode=='detail' || mode=='hasil'">
                    </div>
                    <button style="font-size: 11pt;font-weight: 540;width: 100%;margin-top:20px" class="btn btn-info btn-flat btn-sm" disabled>Detail</button><br><br>
                    <table class="table table-bordered table-hover table-condensed table-stripped">
                      <thead>
                        <th>Kode AHASS</th>
                        <th>Nama AHASS</th>
                        <th>Distrik</th>
                        <th>Owner/PIC AHASS</th>
                        <th>SE</th>
                        <th>Kunjungan</th>
                        <th v-if="mode=='hasil' || mode=='detail'">Status</th>
                        <th v-if="mode=='insert' || mode=='edit' || mode=='hasil'">Aksi</th>
                      </thead>
                      <tbody>
                        <tr v-for="(dt, index) of details">
                          <td>{{dt.kode_dealer_md}}</td>
                          <td>{{dt.nama_dealer}}</td>
                          <td>{{dt.kabupaten}}</td>
                          <td>{{dt.nama_pic_dealer}}</td>
                          <td>{{dt.se}}</td>
                          <td>{{dt.kunjungan}}</td>
                          <td v-if="mode=='hasil' || mode=='detail'">{{dt.status_perbaikan}}</td>
                          <td align="center" v-if="mode=='insert' || mode=='edit' || mode=='hasil'">
                            <button v-if="mode=='insert' || mode=='edit'" @click.prevent="delDetails(index)" type="button" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                            <button v-if="mode=='hasil' && dt.hasil==0" @click.prevent="setHasil(index,'insert')" type="button" class="btn btn-info btn-xs btn-flat btnAddHasil"><i class="fa fa-plus"></i></button>
                            <button v-if="mode=='hasil' && dt.hasil>0" @click.prevent="setHasil(index,'detail')" type="button" class="btn btn-primary btn-xs btn-flat btnAddHasil"><i class="fa fa-eye"></i></button>
                            <button v-if="mode=='hasil' && dt.hasil>0" @click.prevent="setHasil(index,'edit')" type="button" class="btn btn-warning btn-xs btn-flat btnAddHasil"><i class="fa fa-edit"></i></button>
                            <a v-if="mode=='hasil' && dt.hasil>0" :href="'<?= base_url('h2/' . $isi . '/download_excel?id=') ?>'+dt.id_dealer+'&id_s=<?= isset($row->id_supervisi) ? $row->id_supervisi : '' ?>'" class="btn btn-success btn-xs btn-flat"><i class="fa fa-download"></i></a>
                            <a v-if="mode=='hasil' && dt.hasil>0" :href="'<?= base_url('h2/' . $isi . '/download_file_dokumen?id=') ?>'+dt.id_dealer+'&id_s=<?= isset($row->id_supervisi) ? $row->id_supervisi : '' ?>'" target="_blank" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-file"></i></a>
                          </td>
                        </tr>
                      <tfoot v-if="mode=='insert' || mode=='edit'">
                        <tr>
                          <td><input type="text" class="form-control isi" v-model="dtl.kode_dealer_md" onclick="showModalAHASS()" readonly placeholder="Klik untuk memilih" /></td>
                          <td><input type="text" class="form-control isi" onclick="showModalAHASS()" readonly placeholder="Klik untuk memilih" v-model="dtl.nama_dealer" /></td>
                          <td><input type="text" class="form-control isi" onclick="showModalKabupaten()" readonly placeholder="Klik untuk memilih" v-model="dtl.kabupaten" /></td>
                          <td><input type="text" class="form-control isi" v-model="dtl.nama_pic_dealer" /></td>
                          <!-- <td><input type="text" class="form-control isi" onclick="showModalKaryawanMD()" readonly placeholder="Klik untuk memilih" v-model="dtl.se" /></td> -->
                          <td><input type="text" class="form-control isi" v-model="dtl.se" /></td>
                          <td><input type="text" class="form-control isi" v-model="dtl.kunjungan" /></td>
                          <td align="center">
                            <button @click.prevent="addDetails" type="button" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i></button>
                          </td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-3">
                  </div>
                  <?php if ($mode == 'insert' || $mode == 'edit') : ?>
                    <div class="col-sm-12" align="center">
                      <button type="button" id="submitBtn" class="btn btn-primary btn-flat" @click.prevent="save_data"><i class="fa fa-save"></i> Save All</button>
                    </div>
                  <?php endif ?>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <?php
      $data = ['data' => ['modalAHASS', 'modalKaryawanMD', 'modalKabupaten', 'filter_provinsi']];
      $this->load->view('h2/api', $data);
      ?>
      <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalHasil" style="overflow-y:auto;">
        <div class="modal-dialog" style="width:95%">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Hasil Supervisi</h4>
            </div>
            <form id="frm_hasil">
              <div class="modal-body">
                <input type="hidden" name="id_supervisi" v-model="row.id_supervisi" />
                <table class="table table-condensed" style="width:80%">
                  <tr>
                    <td>Kode AHASS</td>
                    <td>: {{row.kode_dealer_md}}
                      <input type="hidden" name="id_dealer" v-model="row.id_dealer" />
                    </td>
                    <td>Nama AHASS</td>
                    <td>: {{row.nama_dealer}}</td>
                  </tr>
                  <tr>
                    <td>Owner/PIC AHASS</td>
                    <td colspan=3>: {{row.nama_pic_dealer}}</td>
                  </tr>
                  <tr>
                    <td>SE</td>
                    <td colspan=3>: {{row.se}}</td>
                  </tr>
                </table>
                <button style="font-size: 11pt;font-weight: 540;width: 100%;margin-bottom:15px" class="btn btn-info btn-flat btn-sm" disabled>Hasil Supervisi</button>
                <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <th>No.</th>
                    <th>Temuan Masalah</th>
                    <th>Penyebab</th>
                    <th>Perbaikan</th>
                    <th>PIC</th>
                    <th>Deadline</th>
                    <th>Foto Temuan</th>
                    <th>Foto Perbaikan</th>
                    <th style="width:7%;text-align:center" v-if="mode!='detail'">
                      <button @click.prevent="addDetails" type="button" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i></button>
                    </th>
                  </thead>
                  <tbody>
                    <tr v-for="(dt, index) of details">
                      <td>{{index+1}}</td>
                      <td><input type="text" class="form-control" v-model="dt.temuan_masalah" :disabled="mode=='detail'" /></td>
                      <td><input type="text" class="form-control" v-model="dt.penyebab" :disabled="mode=='detail'" /></td>
                      <td><input type="text" class="form-control" v-model="dt.perbaikan" :disabled="mode=='detail'" /></td>
                      <td><input type="text" class="form-control" v-model="dt.pic" :disabled="mode=='detail'" /></td>
                      <td>
                        <date-picker :disabled="mode=='detail'" v-model="dt.deadline"></date-picker>
                      </td>
                      <td>
                        <input type="file" class="form-control" name="foto_temuan[]" accept="image/x-png,image/gif,image/jpeg,image/jpg,image/bmp" onchange="pilihFoto(this)" :disabled="mode=='detail'" style="display:inline;width:80%" />
                        <button v-if="(mode=='detail'||mode=='edit') && dt.foto_temuan!=''" @click.prevent="showFotoHasil(dt.foto_temuan)" type="button" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-eye"></i></button>
                      </td>
                      <td>
                        <input type="file" class="form-control" name="foto_perbaikan[]" accept="image/x-png,image/gif,image/jpeg,image/jpg,image/bmp" onchange="pilihFoto(this)" :disabled="mode=='detail'" style="display:inline;width:80%" />
                        <button v-if="(mode=='detail'||mode=='edit') && dt.foto_temuan!=''" @click.prevent="showFotoHasil(dt.foto_perbaikan)" type="button" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-eye"></i></button>
                      </td>
                      <td v-if="mode!='detail'" style="text-align:center;vertical-align:middle">
                        <button @click.prevent="delDetails(index)" type="button" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <button style="font-size: 11pt;font-weight: 540;width: 100%;margin-bottom:15px" class="btn btn-info btn-flat btn-sm" disabled>Upload Dokumen</button>
                <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <th>No.</th>
                    <th>File Dokumen</th>
                    <th>Keterangan Dokumen</th>
                    <th style="width:7%;text-align:center" v-if="mode!='detail'">
                      <button @click.prevent="addDokumens" type="button" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i></button>
                    </th>
                  </thead>
                  <tbody>
                    <tr v-for="(dt, index) of dokumens">
                      <td>{{index+1}}</td>
                      <td>
                        <input style="display:inline;width:80%" type="file" class="form-control" name="file_dokumen[]" :disabled="mode=='detail'" accept="image/x-png,image/gif,image/jpeg,image/jpg,image/bmp,.pdf" onclick="pilihFileDokumen(this)" />
                        <button v-if="(mode=='detail'||mode=='edit') && dt.foto_temuan!=''" @click.prevent="previewFileDoc(dt.file_dokumen)" type="button" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-eye"></i></button>
                      </td>
                      <td><input type="text" class="form-control" v-model="dt.keterangan_dokumen" :disabled="mode=='detail'" /></td>
                      <td v-if="mode!='detail'" style="text-align:center;vertical-align:middle">
                        <button @click.prevent="delDokumens(index)" type="button" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="modal-footer" v-if="mode!='detail'">
                <div class="col-sm-12" align='center'>
                  <button class="btn btn-primary btn-flat" @click.prevent="save_hasil" id="btnSubmitHasil">Save All</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalFoto">
        <div class="modal-dialog" style="width:40%">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="labelFoto"></h4>
            </div>
            <div class="modal-body">
              <div class="">
                <img class="img-responsive" id="hasilFotoSupervisi" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalFilePDF">
        <div class="modal-dialog" style="width:80%">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="labelFoto">Preview File Dokumen</h4>
            </div>
            <div class="modal-body">
              <div class="">
                <div id="filePDF">

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>

      <script>
        Vue.component('date-picker', {
          template: '<input type="text" v-datepicker class="form-control isi_combo" :value="value" @input="update($event.target.value)">',
          directives: {
            datepicker: {
              inserted(el, binding, vNode) {
                $(el).datepicker({
                  autoclose: true,
                  format: 'dd/mm/yyyy',
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

        function pilihFoto(el) {
          var maks_upl = 153600;
          var file_size = $(el)[0].files[0].size;
          regex = new RegExp("(.*?)\.(jpg|jpeg|png|gif|bmp)$");
          var val = $(el).val().toLowerCase()
          let pesan = '';
          if ((regex.test(val)) === false) {
            pesan += 'Tipe file tidak didukung';
          }
          if (file_size > maks_upl) {
            if (pesan != '') {
              pesan += ', serta ';
            }
            pesan += 'Ukuran file melebihi batas maksimal. (Maks. file yang di upload adalah 100 KB)';
          }
          if (pesan != '') {
            $(el).val('');
            toastr.error(pesan);
          }
        }

        function pilihFileDokumen(el) {
          var maks_upl = 409600;
          var file_size = $(el)[0].files[0].size;
          regex = new RegExp("(.*?)\.(jpg|jpeg|png|gif|pdf)$");
          var val = $(el).val().toLowerCase()
          let pesan = '';
          if ((regex.test(val)) === false) {
            pesan += 'Tipe file tidak didukung';
          }
          // if (file_size > maks_upl) {
          //   if (pesan != '') {
          //     pesan += ', serta ';
          //   }
          //   pesan += 'Ukuran file melebihi batas maksimal. (Maks. file yang di upload adalah 400 KB)';
          // }
          if (pesan != '') {
            $(el).val('');
            toastr.error(pesan);
          }
        }
        var frm_hasil = new Vue({
          el: '#frm_hasil',
          data: {
            mode: '',
            dtl: {
              temuan_masalah: '',
              penyebab: '',
              perbaikan: '',
              pic: '',
              deadline: '',
              foto_temuan: '',
              foto_perbaikan: '',
            },
            row: {
              kode_dealer_md: '',
              nama_dealer: '',
              nama_pic_dealer: '',
              se: ''
            },
            dok: {
              file_dokumen: '',
              keterangan_dokumen: ''
            },
            dokumens: [],
            details: []
          },
          methods: {
            clearDetail: function() {
              this.dtl = {
                temuan_masalah: '',
                penyebab: '',
                perbaikan: '',
                pic: '',
                deadline: '',
                foto_temuan: '',
                foto_perbaikan: '',
              }
              $('#deadline').val('');
            },
            addDetails: function() {
              // this.dtl.deadline = $('#deadline').val();
              // if (this.dtl.temuan_masalah === '') {
              //   alert('Silahkan lengkapi data !');
              //   return false;
              // }
              this.details.push(this.dtl);
              this.clearDetail();
            },
            addDokumens: function() {
              this.dokumens.push(this.dok);
              this.dok = {
                file_dokumen: '',
                keterangan_dokumen: ''
              }
            },
            delDokumens: function(index) {
              this.dokumens.splice(index, 1);
            },
            delDetails: function(index) {
              this.details.splice(index, 1);
            },
            save_hasil: function() {
              var values = new FormData($('#frm_hasil')[0]);
              values.append('details', JSON.stringify(frm_hasil.details));
              values.append('dokumens', JSON.stringify(frm_hasil.dokumens));
              values.append('mode', frm_hasil.mode);
              if (confirm("Apakah anda yakin ?") == true) {
                $.ajax({
                  beforeSend: function() {
                    $('#btnSubmitHasil').attr('disabled', true);
                    $('#btnSubmitHasil').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  },
                  enctype: 'multipart/form-data',
                  url: '<?= site_url('h2/' . $isi . '/save_hasil') ?>',
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
                      $('#submitBtn').attr('disabled', false);
                    }
                    $('#btnSubmitHasil').html('Save All');
                  },
                  error: function() {
                    alert('Something Went Wrong !')
                    $('#btnSubmitHasil').attr('disabled', false);
                    $('#btnSubmitHasil').html('Save All');
                  }
                });
              } else {
                return false
              }
            },
            showFotoHasil: function(foto) {
              $('#hasilFotoSupervisi').attr('src', '<?= base_url('uploads/supervisi_file/') ?>' + foto)
              $('#labelFoto').text('Foto Hasil Supervisi');
              $('#modalFoto').modal('show');
            },
            previewFileDoc: function(foto) {
              let ext = getFileExtension(foto);
              if (ext == 'pdf') {
                console.log(foto);
                // $('#filePDF').attr('src', '<?= base_url('uploads/supervisi_file/') ?>' + foto)
                $('#filePDF').html('');
                foto = '<?= base_url('uploads/supervisi_file/') ?>' + foto;
                $('#filePDF').append('<embed id="filePDF" src="' + foto + '" frameborder="0" width="100%" height="400px">')
                $('#modalFilePDF').modal('show');
              } else {
                $('#hasilFotoSupervisi').attr('src', '<?= base_url('uploads/supervisi_file/') ?>' + foto)
                $('#labelFoto').text('Foto File Dokumen');
                $('#modalFoto').modal('show');
              }
            }
          }
        })

        function getFileExtension(filename) {
          var ext = /^.+\.([^.]+)$/.exec(filename);
          return ext == null ? "" : ext[1];
        }
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            dtl: {
              kode_dealer_md: '',
              nama_dealer: '',
              id_dealer: '',
              id_kabupaten: '',
              kabupaten: '',
              se: '',
              id_karyawan: '',
              nama_pic_dealer: '',
              id_karyawan: '',
              kunjungan: ''
            },
            // quartal: 1,
            tgl_quartal: <?= isset($row) ? json_encode($tgl_quartal) : '[]' ?>,
            details: <?= isset($row) ? json_encode($details) : '[]' ?>,
            quartal: <?= isset($row) ? $quartal : 1 ?>,
          },
          methods: {
            clearDetail: function() {
              this.dtl = {
                kode_dealer_md: '',
                nama_dealer: '',
                id_dealer: '',
                // nama_lengkap: '',
                id_karyawan: '',
                nama_pic_dealer: '',
                id_karyawan: '',
                se: '',
                kunjungan: ''
              }
            },
            addDetails: function() {
              if (this.dtl.id_dealer === '') {
                alert('AHASS belum dipilih !');
                return false;
              }
              if (this.dtl.se === '') {
                alert('SE belum diisi !');
                return false;
              }
              if (this.dtl.kunjungan === '') {
                alert('Kunjungan belum ditentukan !');
                return false;
              }
              this.details.push(this.dtl);
              this.clearDetail();
            },
            delDetails: function(index) {
              this.details.splice(index, 1);
            },
            save_data: function() {
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
              if ($('#form_').valid()) // check if form is valid
              {
                let values = {
                  details: form_.details,
                  tgl_quartal: form_.tgl_quartal
                };
                var form = $('#form_').serializeArray();
                for (field of form) {
                  values[field.name] = field.value;
                }
                if (confirm("Apakah anda yakin ?") == true) {
                  if (values.details.length == 0) {
                    alert('Detail penerimaan belum ditentukan !');
                    return false;
                  }
                  $.ajax({
                    beforeSend: function() {
                      $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                      $('#submitBtn').attr('disabled', true);
                    },
                    url: '<?= base_url('h2/' . $isi . '/' . $form) ?>',
                    type: "POST",
                    data: values,
                    cache: false,
                    dataType: 'JSON',
                    success: function(response) {
                      $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                      if (response.status == 'sukses') {
                        window.location = response.link;
                      } else {
                        alert(response.pesan);
                        $('#submitBtn').attr('disabled', false);
                      }
                    },
                    error: function() {
                      alert("Something Went Wrong !");
                      $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                      $('#submitBtn').attr('disabled', false);
                    },
                  });
                } else {
                  return false;
                }
              } else {
                alert('Silahkan isi field required !')
              }
            },
            setHasil: function(idx, mode) {

              values = this.details[idx];

              $.ajax({
                beforeSend: function() {
                  // $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('.btnAddHasil').attr('disabled', true);
                },
                url: '<?= base_url('h2/' . $isi . '/setHasil') ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  $('.btnAddHasil').attr('disabled', false);
                  // $(el).html('<i class="fa fa-save"></i> Save All');
                  console.log(response)
                  frm_hasil.row = form_.details[idx];
                  frm_hasil.details = response.details;
                  frm_hasil.dokumens = response.dokumens;
                  frm_hasil.mode = mode;
                  $('#modalHasil').modal('show');
                },
                error: function() {
                  alert("Something Went Wrong !");
                  // $(el).html('<i class="fa fa-save"></i> Save All');
                  $('.btnAddHasil').attr('disabled', false);
                },
              });
            }
          }
        })

        $(document).ready(function() {
          if (form_.mode == 'insert') {
            initQuartal()
          }
          $('#start_date').val(form_.tgl_quartal[0].start_date);
          $('#end_date').val(form_.tgl_quartal[0].end_date);
        })

        function initQuartal() {
          for (let index = 0; index < 1; index++) {
            ins = {
              quartal: index + 1,
              start_date: '',
              end_date: ''
            }
            form_.tgl_quartal.push(ins);
          }
        }

        function setQuartal(el) {
          // let index = parseInt($(el).val()) - 1;
          // $('#start_date').val(form_.tgl_quartal[index].start_date);
          // $('#end_date').val(form_.tgl_quartal[index].end_date);
        }

        function setTglQuartal() {
          let index = parseInt(form_.quartal) - 1;
          form_.tgl_quartal[index] = {
            quartal: form_.quartal,
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
          }
        }

        function pilihAHASS(ahass) {
          form_.dtl.kode_dealer_md = ahass.kode_dealer_md;
          form_.dtl.nama_dealer = ahass.nama_dealer;
          // form_.dtl.nama_pic_dealer = ahass.nama_pic_dealer;
          form_.dtl.id_dealer = ahass.id_dealer;
        }

        function pilihKabupaten(kab) {
          form_.dtl.id_kabupaten = kab.id_kabupaten;
          form_.dtl.kabupaten = kab.kabupaten;
        }

        function pilihKaryawanMD(kry) {
          form_.dtl.id_karyawan = kry.id_karyawan;
          form_.dtl.nama_lengkap = kry.nama_lengkap;
        }
      </script>



    <?php
    } elseif ($set == "view") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/super_visi/add">
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
          <table id="datatable_serverside" class="table table-hover">
            <thead>
              <tr>
                <th>ID Supervisi</th>
                <th>Agenda</th>
                <th>Tanggal Kunjungan</th>
                <th>Total Dealer</th>
                <th>Status</th>
                <th width="10%">Aksi</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              var dataTable = $('#datatable_serverside').DataTable({
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
                  url: "<?php echo site_url('h2/' . $isi . '/fetch'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  {
                    "targets": [5],
                    "className": 'text-center'
                  },
                  // {
                  //   "targets": [4],
                  //   "className": 'text-right'
                  // },
                  // // { "targets":[0],"checkboxes":{'selectRow':true}}
                  // { "targets":[4],"className":'text-right'}, 
                  // // { "targets":[2,4,5], "searchable": false } 
                ],
              });
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    } ?>
  </section>
</div>