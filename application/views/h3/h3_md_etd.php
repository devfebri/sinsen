<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<base href="<?php echo base_url(); ?>" />

<body onload="auto()">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><?= $title; ?></h1>
      <?= $breadcrumb ?>
    </section>
    <section class="content">
      <?php if ($set == 'form') : ?>
        <?php
        $form     = '';
        $disabled = '';
        $readonly = '';
        if ($mode == 'insert') {
          $form = 'save';
        }

        if ($mode == 'upload') {
          $form = 'inject';
        }

        if ($mode == 'detail') {
          $disabled = 'disabled';
          $form = 'detail';
        }

        if ($mode == 'edit') {
          $form = 'update';
        }
        ?>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h3/<?= $isi ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
              </a>
            </h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            <?php $this->load->view('template/session_message.php'); ?>
            <div class="row">
              <div class="col-md-12">
                <form id="vueForm" class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <div class="col-sm-4" v-bind:class="{ 'has-error': errors.ahm_md != null }">
                        <label class="control-label">AHM - MD</label>
                        <input v-model='etd.ahm_md' type="text" class="form-control" :readonly='mode == "detail"'>
                        <small v-if='errors.ahm_md != null' class="form-text text-danger">{{ errors.ahm_md }}</small>
                      </div>
                      <div class="col-sm-4" v-bind:class="{ 'has-error': errors.proses_md != null }">
                        <label class="control-label">Waktu Proses MD</label>
                        <input v-model='etd.proses_md' type="text" class="form-control" :readonly='mode == "detail"'>
                        <small v-if='errors.proses_md != null' class="form-text text-danger">{{ errors.proses_md }}</small>
                      </div>
                      <div class="col-sm-4" v-bind:class="{ 'has-error': errors.md_d != null }">
                        <label class="control-label">MD - D</label>
                        <input v-model='etd.md_d' type="text" class="form-control" :readonly='mode == "detail"'>
                        <small v-if='errors.md_d != null' class="form-text text-danger">{{ errors.md_d }}</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-4" v-bind:class="{ 'has-error': errors.min_md_d != null }">
                        <label class="control-label">Min HO Lead Time MD-D</label>
                        <input v-model='etd.min_md_d' type="text" class="form-control" :readonly='mode == "detail"'>
                        <small v-if='errors.min_md_d != null' class="form-text text-danger">{{ errors.min_md_d }}</small>
                      </div>
                      <div class="col-sm-4" v-bind:class="{ 'has-error': errors.max_md_d != null }">
                        <label class="control-label">Max HO Lead Time MD-D</label>
                        <input v-model='etd.max_md_d' type="text" class="form-control" :readonly='mode == "detail"'>
                        <small v-if='errors.max_md_d != null' class="form-text text-danger">{{ errors.max_md_d }}</small>
                      </div>
                      <div class="col-sm-4" v-bind:class="{ 'has-error': errors.proses_d != null }">
                        <label class="control-label">Waktu Proses D</label>
                        <input v-model='etd.proses_d' type="text" class="form-control" :readonly='mode == "detail"'>
                        <small v-if='errors.proses_d != null' class="form-text text-danger">{{ errors.proses_d }}</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-4" v-bind:class="{ 'has-error': errors.lc != null }">
                        <label class="control-label">Local Parts, Current</label>
                        <input v-model='etd.lc' type="text" class="form-control" :readonly='mode == "detail"'>
                        <small v-if='errors.lc != null' class="form-text text-danger">{{ errors.lc }}</small>
                      </div>
                      <div class="col-sm-4" v-bind:class="{ 'has-error': errors.ln != null }">
                        <label class="control-label">Local Parts, Non-Current</label>
                        <input v-model='etd.ln' type="text" class="form-control" :readonly='mode == "detail"'>
                        <small v-if='errors.ln != null' class="form-text text-danger">{{ errors.ln }}</small>
                      </div>
                      <div class="col-sm-4" v-bind:class="{ 'has-error': errors.ic != null }">
                        <label class="control-label">Import Parts, Current</label>
                        <input v-model='etd.ic' type="text" class="form-control" :readonly='mode == "detail"'>
                        <small v-if='errors.ic != null' class="form-text text-danger">{{ errors.ic }}</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-4" v-bind:class="{ 'has-error': errors.in != null }">
                        <label class="control-label">Import Parts, Non-Current</label>
                        <input v-model='etd.in' type="text" class="form-control" :readonly='mode == "detail"'>
                        <small v-if='errors.in != null' class="form-text text-danger">{{ errors.in }}</small>
                      </div>
                      <div class="col-sm-4" v-bind:class="{ 'has-error': errors.rc != null }">
                        <label class="control-label">Re-numbering Claim</label>
                        <input v-model='etd.rc' type="text" class="form-control" :readonly='mode == "detail"'>
                        <small v-if='errors.rc != null' class="form-text text-danger">{{ errors.rc }}</small>
                      </div>
                      <div class="col-sm-4" v-bind:class="{ 'has-error': errors.rn != null }">
                        <label class="control-label">Re-numbering Non-claim</label>
                        <input v-model='etd.rn' type="text" class="form-control" :readonly='mode == "detail"'>
                        <small v-if='errors.rn != null' class="form-text text-danger">{{ errors.rn }}</small>
                      </div>
                    </div>
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-sm-12 no-padding">
                          <table class="table table-striped">
                            <tr class='bg-blue-gradient'>
                              <td width='3%'>No.</td>
                              <td>Kode Dealer</td>
                              <td>Nama Dealer</td>
                              <td>Alamat Dealer</td>
                              <td>Kabupaten</td>
                              <td v-if='mode != "detail"' width='3%'></td>
                            </tr>
                            <tr v-if='dealers.length > 0' v-for='(dealer, index) of dealers' v-bind:class="{ 'bg-danger': (errors.dealers != null && errors.dealers == dealer.id_dealer) }">
                              <td class='align-middle'>{{ index + 1 }}.</td>
                              <td class='align-middle'>{{ dealer.kode_dealer_md }}</td>
                              <td class='align-middle'>{{ dealer.nama_dealer }}</td>
                              <td class='align-middle'>{{ dealer.alamat }}</td>
                              <td class='align-middle'>{{ dealer.kabupaten }}</td>
                              <td v-if='mode != "detail"' class='text-right align-middle'>
                                <button @click.prevent='hapus_dealer(index)' type='button' class="btn btn-flat btn-sm btn-danger"><i class="fa fa-trash-o"></i></button>
                              </td>
                            </tr>
                            <tr v-if='dealers.length < 1'>
                              <td class='text-center' colspan='4'>Tidak ada data.</td>
                            </tr>
                          </table>
                          <span v-if='errors.dealers != null' class="form-text text-danger">Dealer sudah pernah disetting. Harap tidak membuat duplikat setting.</span>
                        </div>
                      </div>
                      <div v-if='mode != "detail"' class="row">
                        <div class="text-right col-sm-12 no-padding">
                          <button class="btn btn-sm btn-flat btn-primary" data-toggle='modal' data-target='#modal_dealer_etd' type='button'><i class="fa fa-plus"></i></button>
                        </div>
                      </div>
                      <?php $this->load->view('modal/dealer_etd') ?>
                      <script>
                        function pilih_dealer_etd(dealer) {
                          vueForm.dealers.push(dealer);
                        }
                      </script>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="col-sm-12 no-padding">
                      <button v-if='mode == "insert"' @click.prevent='<?= $form ?>' class="btn btn-sm btn-flat btn-primary" type="button">Simpan</button>
                      <button v-if='mode == "edit"' @click.prevent='<?= $form ?>' class="btn btn-sm btn-flat btn-warning" type="button">Perbarui</button>
                      <a v-if='mode == "detail"' :href="'h3/h3_md_etd/edit?id=' + etd.id" class="btn btn-flat btn-sm btn-warning">Edit</a>
                      <a v-if='mode == "detail"' :href="'h3/h3_md_etd/delete?id=' + etd.id" class="btn btn-flat btn-sm btn-danger">Hapus</a>
                    </div>
                  </div><!-- /.box-footer -->
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
        <script>
          Vue.use(VueNumeric.default);

          var vueForm = new Vue({
            el: '#vueForm',
            data: {
              mode: '<?= $mode ?>',
              <?php if ($mode == 'detail' or $mode == 'edit') : ?>
                etd: <?= json_encode($etd) ?>,
                dealers: <?= json_encode($dealers) ?>,
              <?php else : ?>
                etd: {
                  ahm_md: 1,
                  proses_md: 1,
                  md_d: 1,
                  min_md_d:1,
                  max_md_d:1,
                  proses_d:1,
                  lc: 2,
                  ln: 4,
                  ic: 22,
                  in: 44,
                  rc: 14,
                  rn: 21,
                },
                dealers: [],
              <?php endif; ?>
              errors: [],
            },
            methods: {
              hapus_dealer: function(index) {
                this.dealers.splice(index, 1);
              },
              <?= $form ?>: function() {
                post = _.pick(this.etd, [
                  'id', 'ahm_md', 'proses_md', 'md_d', 'min_md_d','max_md_d','proses_d',
                  'lc', 'ln', 'ic', 'in', 'rc', 'rn'
                ]);
                post.dealers = _.map(this.dealers, function(dealer) {
                  return _.pick(dealer, ['id_dealer']);
                });

                axios.post('h3/h3_md_etd/<?= $form ?>', Qs.stringify(post))
                  .then(function(res) {
                    data = res.data;
                    if (data.redirect_url != null) window.location = data.redirect_url;
                  })
                  .catch(function(err) {
                    vueForm.errors = err.response.data;
                    toastr.error(err);
                  });
              }
            }
          });
        </script>
      <?php endif; ?>
      <?php if ($set == "index") : ?>
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h3/<?= $isi ?>/add">
                <button class="btn bg-blue btn-flat margin">Add New</button>
              </a>
              <a href="h3/<?= $isi ?>/upload_etd_revisi">
                <button class="btn btn-success btn-flat">Upload ETD Revisi MD</button>
              </a>
              <a href="h3/<?= $isi ?>/upload_etd_revisi_ahm">
                <button class="btn btn-warning btn-flat">Upload ETD Revisi AHM</button>
              </a>
            </h3>
            <?php if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {?>
              <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
                <strong><?php echo $_SESSION['pesan'] ?></strong>
                <button class="close" data-dismiss="alert">
                  <span aria-hidden="true">&times;</span>
                  <span class="sr-only">Close</span>
                </button>
              </div>
          <?php } $_SESSION['pesan'] = ''; ?>
          </div><!-- /.box-header -->
          <div class="box-body">
            <table id="dt" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th width="3%">No,</th>
                  <th>Setting untuk dealer</th>
                  <th width="3%">Action</th>
                </tr>
              </thead>
              <tbody>
                <!-- <?php if (count($etd) > 0) : ?>
                  <?php $index = 1;
                  foreach ($etd as $e) : ?>
                    <?php
                    $etdi = $this->db
                      ->select('d.nama_dealer')
                      ->from('ms_h3_md_estimated_time_delivery_items as etdi')
                      ->join('ms_dealer as d', 'd.id_dealer = etdi.id_dealer')
                      ->where('etdi.id_etd', $e->id)
                      ->get()->result();

                    $dealers = '';
                    foreach ($etdi as $i) {
                      $dealers .= ', ' .  $i->nama_dealer;
                    }
                    $dealers = substr($dealers, 2);
                    ?>
                    <tr>
                      <td><?= $index ?>.</td>
                      <td><?= $dealers ?></td>
                      <td>
                        <a href="h3/h3_md_etd/detail?id=<?= $e->id ?>" class="btn btn-flat btn-xs btn-primary">View</a>
                      </td>
                    </tr>
                  <?php $index++;
                  endforeach; ?>
                <?php endif; ?> -->
              </tbody>
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
        <script type="text/javascript">
          var table;
          $(document).ready(function() {
      
              //datatables
              table = $('#dt').DataTable({ 
      
                  "processing": true, 
                  "serverSide": true, 
                  "order": [], 
                  
                  "ajax": {
                      "url": "<?php echo site_url('h3/h3_md_etd/getDataTable')?>",
                      "type": "POST"
                  },

                  "columnDefs": [
                  { 
                      "targets": [ 0,2], 
                      "orderable": false, 
                  },
                  ],
      
              });
      
          });
 
        </script>
      <?php endif; ?>
      <?php if ($set == 'upload_etd_revisi') : ?>
        <div class="box box-default" id='app'>
          <div v-if="loading" class="overlay">
            <i class="fa fa-refresh fa-spin text-light-blue"></i>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">File ETD Revisi</label>
                      <div class="col-sm-4">
                        <input type="file" @change='on_file_change()' ref='file' class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="box-footer">
                    <div class="col-sm-6 no-padding">
                      <button class="btn btn-flat btn-primary" @click.prevent='upload'>Upload</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
        <script>
          Vue.use(VueNumeric.default);

          app = new Vue({
            el: '#app',
            data: {
              loading: false,
              errors: {},
              file: null
            },
            methods: {
              upload: function() {
                post = new FormData();
                post.append('file', this.file);

                this.errors = {};
                this.loading = true;
                axios.post('h3/<?= $isi ?>/store_upload_etd_revisi', post, {
                    headers: {
                      'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
                    }
                  })
                  .then(function(res) {
                    data = res.data;
                    if (data.redirect_url != null) window.location = data.redirect_url;
                  })
                  .catch(function(err) {
                    data = err.response.data;
                    if (data.error_type == 'validation_error') {
                      form_.errors = data.errors;
                      toastr.error(data.message);
                    } else if (data.message != null) {
                      toastr.error(data.message);
                    } else {
                      toastr.error(err);
                    }

                    app.loading = false;
                  });
              },
              on_file_change: function() {
                this.file = this.$refs.file.files[0];
              },
              error_exist: function(key) {
                return _.get(this.errors, key) != null;
              },
              get_error: function(key) {
                return _.get(this.errors, key)
              }
            }
          });
        </script>
      <?php endif; ?>
      <?php if ($set == 'upload_etd_revisi_ahm') : ?>
        <div class="box box-default" id='app'>
          <div v-if="loading" class="overlay">
            <i class="fa fa-refresh fa-spin text-light-blue"></i>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal" id="form-csv" method="post" action="<?= base_url() ?>h3/h3_md_etd/store_upload_etd_revisi_ahm" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">File ETD Revisi AHM</label>
                      <div class="col-sm-4">
                        <input type="file" name="file_csv" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="box-footer">
                    <div class="col-sm-6 no-padding">
                      <button class="btn btn-flat btn-primary" type="submit">Upload</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
        <script>
          Vue.use(VueNumeric.default);

          app = new Vue({
            el: '#app',
            data: {
              loading: false,
              errors: {},
              file: null
            },
            methods: {
              upload: function() {
                post = new FormData();
                post.append('file', this.file);

                this.errors = {};
                this.loading = true;
                axios.post('h3/<?= $isi ?>/store_upload_etd_revisi', post, {
                    headers: {
                      'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
                    }
                  })
                  .then(function(res) {
                    data = res.data;
                    if (data.redirect_url != null) window.location = data.redirect_url;
                  })
                  .catch(function(err) {
                    data = err.response.data;
                    if (data.error_type == 'validation_error') {
                      form_.errors = data.errors;
                      toastr.error(data.message);
                    } else if (data.message != null) {
                      toastr.error(data.message);
                    } else {
                      toastr.error(err);
                    }

                    app.loading = false;
                  });
              },
              on_file_change: function() {
                this.file = this.$refs.file.files[0];
              },
              error_exist: function(key) {
                return _.get(this.errors, key) != null;
              },
              get_error: function(key) {
                return _.get(this.errors, key)
              }
            }
          });
        </script>
      <?php endif; ?>
      <?php if ($set == "table_eta") : ?>
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <!-- <b>Set Up Lead Time</b> -->
              <a href="h3/<?= $isi ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
              </a>
            </h3>
          </div><!-- /.box-header -->
          <div class="box-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <td rowspan="2">Kode Dealer</td>
                    <td rowspan="2">LT AHM to MD (hari)</td>
                    <td rowspan="2">Waktu Proses di MD (hari)</td>
                    <td colspan="2">Lead Time MD to D (hari)</td>
                    <td rowspan="2">Waktu Proses di D (hari)</td>
                    <td colspan="2">Total Lead Time (hari)</td>
                  </tr>
                  <tr>
                    <td>Min HO</td>
                    <td>Max HO</td>
                    <td>Min HO</td>
                    <td>Max HO</td>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($tabel_eta->result() as $eta){?>
                    <?php $total_min = $eta->ahm_md+$eta->proses_md+$eta->min_md_d+$eta->md_d;
                    $total_max = $eta->ahm_md+$eta->proses_md+$eta->max_md_d+$eta->md_d;?>
                    <tr>
                      <td><?php echo $eta->kode_dealer_md ?></td>
                      <td><?php echo $eta->ahm_md ?></td>
                      <td><?php echo $eta->proses_md ?></td>
                      <td><?php echo $eta->min_md_d ?></td>
                      <td><?php echo $eta->max_md_d ?></td>
                      <td><?php echo $eta->md_d ?></td>
                      <td><?php echo $total_min ?></td>
                      <td><?php echo $total_max ?></td>
                    </tr>
                  <?php }?>
                </tbody>
              </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      <?php endif; ?> 
    </section>
  </div>