<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" />

<body>
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
        if ($mode == 'terima_claim') {
          $form = 'simpan_claim';
        }
        if ($mode == 'detail') {
          $form = 'detail';
          $disabled = 'disabled';
        }
        if ($mode == 'edit') {
          $form = 'update';
        }
        ?>
        <div id="app" class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h3/<?= $isi ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
              </a>
            </h3>
          </div><!-- /.box-header -->
          <div v-if="loading" class="overlay">
            <i class="fa fa-refresh fa-spin text-light-blue"></i>
          </div>
          <div class="box-body">
            <?php $this->load->view('template/session_message.php'); ?>
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">No. Checker</label>
                      <div class="col-sm-4">
                        <input v-model="po_checker.id_checker" readonly type="text" class="form-control">
                      </div>
                      <label class="col-sm-2 control-label">Tgl Checker</label>
                      <div class="col-sm-4">
                        <input :value="moment(po_checker.tgl_checker).format('DD/MM/YYYY')" readonly type="text" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">No. Shipping List</label>
                      <div class="col-sm-4">
                        <input v-model="po_checker.no_shipping_list" readonly type="text" class="form-control">
                      </div>
                      <label class="col-sm-2 control-label">No. PO H3</label>
                      <div class="col-sm-4">
                        <input v-if='po_checker.po_id_dealer_h3 != null' v-model="po_checker.po_id_dealer_h3" readonly type="text" class="form-control">
                        <input v-if='po_checker.po_id_dealer_h3 == null' value='-' readonly type="text" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Kode Tipe Unit</label>
                      <div class="col-sm-4">
                        <input v-model="po_checker.tipe_motor" readonly type="text" class="form-control">
                      </div>
                      <label class="col-sm-2 control-label">No. Rangka</label>
                      <div class="col-sm-4">
                        <input v-model="po_checker.no_rangka" readonly type="text" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Tipe Unit</label>
                      <div class="col-sm-4">
                        <input v-model="po_checker.deskripsi_unit" readonly type="text" class="form-control">
                      </div>
                      <label class="col-sm-2 control-label">No. Mesin</label>
                      <div class="col-sm-4">
                        <input v-model="po_checker.no_mesin" readonly type="text" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Deskripsi Warna</label>
                      <div class="col-sm-4">
                        <input v-model="po_checker.deskripsi_warna" readonly type="text" class="form-control">
                      </div>
                      <label class="col-sm-2 control-label">Status</label>
                      <div class="col-sm-4">
                        <input v-model="po_checker.status_checker" readonly type="text" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-12">
                        <table id="table" class="table table-responsive">
                          <thead>
                            <tr>
                              <th class='align-top' width='3%'>No.</th>
                              <th class='align-top'>Kode Part</th>
                              <th class='align-top'>Nama Part</th>
                              <th class='align-top' width="10%">Qty AVS</th>
                              <th class='align-top' width="10%">Qty Order</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(part, index) in parts">
                              <td class="align-middle">{{ index + 1 }}.</td>
                              <td class="align-middle">{{ part.id_part }}</td>
                              <td class="align-middle">{{ part.nama_part }}</td>
                              <td class="align-middle">
                                <vue-numeric class="form-control" :read-only='true' separator="." :empty-value="1" v-model="part.qty_avs" />
                              </td>
                              <td class="align-middle">
                                <vue-numeric class="form-control" :read-only='true' separator="." :empty-value="1" v-model="part.qty_order" />
                              </td>
                            </tr>
                            <tr v-if="parts.length < 1">
                              <td class="text-center" colspan="4">Belum ada part</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="col-sm-6 text-right">
                      <button v-if='mode == "detail" && (po_checker.status_checker == "Open" || po_checker.status_checker == null)' @click.prevent='approve' type="submit" class="btn btn-sm btn-success btn-flat">Approve</button>
                      <button v-if='mode == "detail" && (po_checker.status_checker == "Open" || po_checker.status_checker == null)' @click.prevent='reject' class="btn btn-sm btn-danger btn-flat">Reject</button>
                      <div id="reject_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">×</span>
                              </button>
                              <h4 class="modal-title text-left" id="myModalLabel">Alasan Reject</h4>
                            </div>
                            <div class="modal-body">
                              <div class="form-group">
                                <div class="col-sm-12">
                                  <textarea class="form-control" id="alasan_reject"></textarea>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-sm-12">
                                  <button @click.prevent='reject' class="btn btn-flat btn-sm btn-primary" data-dismiss="modal">Submit</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div><!-- /.box-footer -->
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
        <script>
          var app = new Vue({
            el: '#app',
            data: {
              errors: {},
              loading: false,
              mode: '<?= $mode ?>',
              index_part: 0,
              po_checker: <?= json_encode($po_checker) ?>,
              parts: <?= json_encode($parts) ?>,
            },
            methods: {
              approve: function() {
                this.loading = true;
                post = _.pick(this.po_checker, ['id_checker']);

                axios.post('h3/<?= $isi ?>/approve', Qs.stringify(post))
                  .then(function(res) {
                    window.location = 'h3/<?= $isi ?>/detail?id_checker=' + res.data.id_checker;
                  })
                  .catch(function(err) {
                    toastr.error(err);
                  })
                  .then(function() {
                    app.loading = false;
                  });
              },
              reject: function() {
                this.loading = true;
                post = _.pick(this.po_checker, ['id_checker']);
                // post.message = $('#alasan_reject').val();

                axios.post('h3/<?= $isi ?>/reject', Qs.stringify(post))
                  .then(function(res) {
                    window.location = 'h3/<?= $isi ?>/detail?id_checker=' + res.data.id_checker;
                  })
                  .catch(function(err) {
                    toastr.error(err);
                  })
                  .then(function() {
                    app.loading = false;
                  });
              },
              error_exist: function(key) {
                return _.get(this.errors, key) != null;
              },
              get_error: function(key) {
                return _.get(this.errors, key)
              }
            },
            watch: {
              'claim_dealer.id_packing_sheet': function() {
                datatable_parts_claim_dealer.draw();
              },
              'claim_dealer.id_dealer': function() {
                datatable_packing_sheet_claim_dealer.draw();
              },
              parts: {
                deep: true,
                handler: function() {
                  datatable_parts_claim_dealer.draw();
                }
              }
            },
          });
        </script>
      <?php endif; ?>
      <?php if ($mode == "index") : ?>
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <?php if ($this->input->get('history') != null) : ?>
                <a href="h3/<?= $isi ?>">
                  <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
                </a>
              <?php else : ?>
                <a href="h3/<?= $isi ?>?history=true">
                  <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
                </a>
              <?php endif; ?>
            </h3>
          </div>
          <div class="box-body">
            <div class="container-fluid">
              <div class="row">
                <div class="col-sm-4">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="" class="control-label">No. Checker</label>
                        <input id='no_checker_filter' type="text" class="form-control">
                      </div>
                    </div>
                  </div>
                  <script>
                    $(document).ready(function() {
                      $('#no_checker_filter').on('keyup', _.debounce(function() {
                        po_checker.draw();
                      }, 500));
                    });
                  </script>
                </div>
                <div class="col-sm-4">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="" class="control-label">No. Shipping List</label>
                        <input id='no_shipping_list_filter' type="text" class="form-control">
                      </div>
                    </div>
                  </div>
                  <script>
                    $(document).ready(function() {
                      $('#no_shipping_list_filter').on('keyup', _.debounce(function() {
                        po_checker.draw();
                      }, 500));
                    });
                  </script>
                </div>
                <div class="col-sm-4">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="" class="control-label">No. Mesin</label>
                        <input id='no_mesin_filter' type="text" class="form-control">
                      </div>
                    </div>
                  </div>
                  <script>
                    $(document).ready(function() {
                      $('#no_mesin_filter').on('keyup', _.debounce(function() {
                        po_checker.draw();
                      }, 500));
                    });
                  </script>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="" class="control-label">No. Rangka</label>
                        <input id='no_rangka_filter' type="text" class="form-control">
                      </div>
                    </div>
                  </div>
                  <script>
                    $(document).ready(function() {
                      $('#no_rangka_filter').on('keyup', _.debounce(function() {
                        po_checker.draw();
                      }, 500));
                    });
                  </script>
                </div>
                <div class="col-sm-4">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="" class="control-label">Kode Tipe Unit</label>
                        <input id='kode_tipe_unit_filter' type="text" class="form-control">
                      </div>
                    </div>
                  </div>
                  <script>
                    $(document).ready(function() {
                      $('#kode_tipe_unit_filter').on('keyup', _.debounce(function() {
                        po_checker.draw();
                      }, 500));
                    });
                  </script>
                </div>
              </div>
            </div>
            <table id="po_checker" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>No. Checker</th>
                  <th>Tanggal Checker</th>
                  <th>No. Shipping List</th>
                  <th>No. Mesin</th>
                  <th>No. Rangka</th>
                  <th>Kode Tipe Unit</th>
                  <th>Keterangan</th>
                  <th>Jumlah Kode Part</th>
                  <th>Jumlah Kuantitas Order</th>
                  <th>Status</th>
                  <th width='3%'>Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <script>
              $(document).ready(function() {
                po_checker = $('#po_checker').DataTable({
                  processing: true,
                  serverSide: true,
                  searching: false,
                  order: [],
                  scrollX: true,
                  ajax: {
                    url: "<?= base_url('api/md/h3/po_checker') ?>",
                    dataSrc: "data",
                    type: "POST",
                    data: function(d) {
                      d.no_checker_filter = $('#no_checker_filter').val();
                      d.no_shipping_list_filter = $('#no_shipping_list_filter').val();
                      d.no_mesin_filter = $('#no_mesin_filter').val();
                      d.no_rangka_filter = $('#no_rangka_filter').val();
                      d.kode_tipe_unit_filter = $('#kode_tipe_unit_filter').val();
                      d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                    },
                    // data: function(d){
                    //   d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                    // }
                  },
                  columns: [{
                      data: 'index',
                      orderable: false,
                      width: '3%'
                    },
                    {
                      data: 'id_checker'
                    },
                    {
                      data: 'tgl_checker',
                      render: function(data) {
                        return moment(data).format('DD/MM/YYYY');
                      }
                    },
                    {
                      data: 'no_shipping_list'
                    },
                    {
                      data: 'no_mesin'
                    },
                    {
                      data: 'no_rangka'
                    },
                    {
                      data: 'tipe_motor'
                    },
                    {
                      data: 'keterangan'
                    },
                    {
                      data: 'jumlah_kode_part'
                    },
                    {
                      data: 'jumlah_order'
                    },
                    {
                      data: 'status_checker'
                    },
                    {
                      data: 'action',
                      orderable: false,
                      className: 'text-center'
                    },
                  ],
                });
              });
            </script>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      <?php endif; ?>
    </section>
  </div>