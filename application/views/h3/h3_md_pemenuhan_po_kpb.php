<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/sweet_alert.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script src="<?= base_url("assets/toastr/toastr.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/helper.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" />

<body>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
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
        if ($mode == 'pemenuhan') {
          $form = 'simpan_pemenuhan';
        }
        if ($mode == 'detail') {
          $disabled = 'disabled';
        }
        if ($mode == 'edit') {
          $form = 'update';
        }
        ?>
        <div id='app' class="box box-default">
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
                <form class="form-horizontal">
                  <div class="box-body">
                    <div v-if='parts_qty_avs_tidak_memenuhi.length > 0' class="alert alert-warning" role="alert">
                      <strong>Perhatian!</strong> Terdapat kuantitas AVS pada part yang tidak dapat memenuhi permintaan order
                      <ol>
                        <li v-for='row of parts_qty_avs_tidak_memenuhi'>
                          <span>{{ row.id_part }} dengan kuantitas order {{ row.qty_pemenuhan }} tetapi kuantitas AVS {{ row.qty_avs }}</span>
                          <ul>
                            <li v-for='each of row.grouped'>
                              <span>{{ each.id_part }} dengan kuantitas order {{ each.qty_pemenuhan }} untuk tipe kendaraan {{ each.id_tipe_kendaraan }}</span>
                            </li>
                          </ul>
                        </li>
                      </ol>
                    </div>
                    <?php if ($dealer_kpb == null) : ?>
                      <div class="alert alert-warning" role="alert">
                        <strong>Perhatian!</strong> Belum ada dealer yang didaftar sebagai dealer yang menampung plafon KPB
                      </div>
                    <?php endif; ?>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Nomor PO</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.id_po_kpb' type="text" readonly class="form-control">
                      </div>
                      <label class="col-sm-2 control-label">Kode Customer</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.kode_dealer_md' type="text" readonly class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Tanggal PO</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.tgl_po_kpb_formatted' type="text" readonly class="form-control">
                      </div>
                      <label class="col-sm-2 control-label">Nama Customer</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.nama_dealer' type="text" readonly class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">No. SO KPB</label>
                      <div class="col-sm-4">
                        <input v-if='purchase.id_sales_order != null' v-model='purchase.id_sales_order' type="text" readonly class="form-control">
                        <input v-if='purchase.id_sales_order == null' value='-' type="text" readonly class="form-control">
                      </div>
                      <label class="col-sm-2 control-label">Tanggal SO KPB</label>
                      <div class="col-sm-4">
                        <input v-if='purchase.tanggal_so_kpb != null' :value='moment(purchase.tanggal_so_kpb).format("DD/MM/YYYY")' type="text" readonly class="form-control">
                        <input v-if='purchase.tanggal_so_kpb == null' value='-' type="text" readonly class="form-control">
                      </div>
                    </div>
                    <input type="hidden" id='id-detail-for-modal'>
                    <input type="hidden" id='tipe-produksi-for-modal'>
                    <div class="row">
                      <div class="col-sm-12">
                        <table id="detail-parts-po-kpb" class="table table-condensed table-responsive">
                          <thead>
                            <tr>
                              <th width='3%'>No.</th>
                              <th>Kode Part</th>
                              <th>Kode Part H3</th>
                              <th>Deksripsi Part</th>
                              <th>Tipe Produksi</th>
                              <th>Tipe Kendaraan</th>
                              <th class='text-center'>Qty AVS</th>
                              <th class='text-center'>Qty Order</th>
                              <th width="10%" class='text-center'>HET</th>
                              <th width='10%' class='text-center'>Diskon</th>
                              <th width='10%' class='text-center'>Harga Setelah Diskon</th>
                              <th width='10%' class='text-center'>Harga KPB</th>
                              <th width="10%" class='text-center'>Total Harga</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                        </table>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_parts_h3_pemenuhan_po_kpb', true); ?>
                    <script>
                      function pilih_parts_h3_pemenuhan_po_kpb(data) {
                        app.parts[app.index_part].id_part_h3 = data.id_part;
                        app.set_id_part_h3(data);
                      }
                    </script>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="col-sm-12 no-padding">
                      <?php if ($dealer_kpb != null) : ?>
                        <button v-if='purchase.id_sales_order == null' :disabled='parts_qty_avs_tidak_memenuhi.length > 0 || part_h3_belum_dipilih.length > 0' class="btn btn-flat btn-success btn-sm" @click.prevent='proses'>Proses</button>
                        <button v-if='purchase.id_sales_order == null' class="btn btn-flat btn-danger btn-sm" type='button' data-toggle='modal' data-target='#reject_modal'>Reject</button>
                      <?php endif; ?>
                    </div>
                  </div><!-- /.box-footer -->
                  <div id="reject_modal" class="modal fade modalcustomer" tabindex="-1" role="dialog" aria-hidden="true">
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
                              <textarea class="form-control" id="alasan_reject" rows='5'></textarea>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-sm-12">
                              <button @click.prevent='reject' class="btn btn-flat btn-sm btn-primary">Submit</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
        <script>
          app = new Vue({
            el: '#app',
            data: {
              loading: false,
              index_part: 0,
              mode: '<?= $mode ?>',
              purchase: <?= json_encode($purchase) ?>,
              validation_process_items: [],
            },
            mounted: function() {
              this.validation_process();
            },
            methods: {
              proses: function() {
                this.loading = true;
                axios.get('h3/<?= $isi ?>/proses', {
                  params: {
                    id_po_kpb: this.purchase.id_po_kpb
                  }
                })
                  .then(function(res) {
                    data = res.data;
                    if (data.redirect_url != null) window.location = data.redirect_url;
                  })
                  .catch(function(err) {
                    toastr.error(err);
                  })
                  .then(function() {
                    app.loading = false;
                  });
              },
              validation_process: function() {
                axios.get('h3/<?= $isi ?>/validation_process', {
                    params: {
                      id_po_kpb: this.purchase.id_po_kpb
                    }
                  })
                  .then(function(response) {
                    app.validation_process_items = response.data;
                  })
              },
              reject: function() {
                this.loading = true;
                axios.get('h3/h3_md_pemenuhan_po_kpb/reject', {
                    params: {
                      id_po_kpb: this.purchase.id_po_kpb,
                      alasan_reject: $('#alasan_reject').val()
                    }
                  })
                  .then(function(res) {
                    window.location = 'h3/h3_md_pemenuhan_po_kpb';
                  })
                  .catch(function(err) {
                    toastr.error(err);
                  })
                  .then(function() {
                    app.loading = false;
                  });
              },
            },
            computed: {
              part_h3_belum_dipilih: function() {
                return _.chain(this.validation_process_items)
                  .filter(function(part) {
                    return part.id_part_h3 == '' || part.id_part_h3 == null;
                  })
                  .value();
              },
              parts_qty_avs_tidak_memenuhi: function() {
                return _.chain(this.validation_process_items)
                  .groupBy(function(row) {
                    if (row.id_part_h3 != null && row.id_part_h3 != '') {
                      return row.id_part_h3;
                    } else {
                      return row.id_part;
                    }
                  })
                  .map(function(grouped, index) {
                    return {
                      id_part: index,
                      qty_avs: grouped[0].qty_avs,
                      qty_pemenuhan: _.sumBy(grouped, function(group) {
                        return group.qty_pemenuhan;
                      }),
                      grouped: grouped
                    };
                  })
                  .filter(function(part) {
                    return parseInt(part.qty_pemenuhan) > parseInt(part.qty_avs);
                  })
                  .value();
              },
            }
          });

          $(document).ready(function() {
            detailPartsPoKpb = $('#detail-parts-po-kpb').DataTable({
              processing: true,
              serverSide: true,
              searching: false,
              ordering: false,
              order: [],
              ajax: {
                url: "<?= base_url('api/md/h3/detail_parts_pemenuhan_po_kpb') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d) {
                  d.id_po_kpb = '<?= $purchase['id_po_kpb'] ?>';
                }
              },
              createdRow: function(row, data, index) {
                $('td', row).addClass('align-middle');
              },
              columns: [{
                  data: 'index',
                  width: '3%',
                },
                {
                  data: 'id_part',
                },
                {
                  data: 'id_part_h3_input',
                },
                {
                  data: 'nama_part',
                },
                {
                  data: 'tipe_produksi',
                },
                {
                  data: 'id_tipe_kendaraan',
                },
                {
                  data: 'qty_avs',
                  className: 'text-center',
                  render: function(data){
                    return accounting.formatNumber(data, 0, '.', ',');
                  }
                },
                {
                  data: 'qty_pemenuhan',
                  className: 'text-center'
                },
                {
                  data: 'harga',
                  className: 'text-right',
                  render: function(data) {
                    return 'Rp ' + accounting.formatNumber(data, 0, '.', ',');
                  }
                },
                {
                  data: 'diskon_value',
                  className: 'text-right',
                  render: function(data, type, row) {
                    if (row.tipe_diskon == 'Rupiah') {
                      return 'Rp ' + accounting.formatNumber(data, 0, '.', ',');
                    } else if (row.tipe_diskon == 'Persen') {
                      return accounting.formatNumber(data, 0, '.', ',') + '%';
                    }
                    return '--';
                  }
                },
                {
                  data: 'harga',
                  className: 'text-right',
                  render: function(data, type, row) {
                    amount = amount_diskon(row.harga, row.tipe_diskon, row.diskon_value);

                    return 'Rp ' + accounting.formatNumber(row.harga - amount, 0, '.', ',');
                  }
                },
                {
                  data: 'harga_kpb',
                  className: 'text-right',
                  render: function(data, type, row) {
                    return 'Rp ' + accounting.formatNumber(data, 0, '.', ',');
                  }
                },
                {
                  data: 'harga_kpb',
                  className: 'text-right',
                  render: function(data, type, row) {
                    amount = amount_diskon(row.harga, row.tipe_diskon, row.diskon_value);
                    total = row.qty_pemenuhan * (row.harga - amount);
                    return 'Rp ' + accounting.formatNumber(total, 0, '.', ',');
                  }
                },
              ],
            });
          });

          function showPartH3Modal(id_detail, tipe_produksi) {
            $('#id-detail-for-modal').val(id_detail);
            $('#tipe-produksi-for-modal').val(tipe_produksi);
            $('#h3_md_parts_h3_pemenuhan_po_kpb').modal('show');
            h3_md_parts_h3_pemenuhan_po_kpb_datatable.draw();
          }

          function pilihPartH3(id_detail, id_part_h3) {
            axios.get('h3/h3_md_pemenuhan_po_kpb/set_id_part_h3', {
                params: {
                  id_detail: id_detail,
                  id_part_h3: id_part_h3
                }
              })
              .then(function(res) {
                detailPartsPoKpb.draw(false);
                app.validation_process();
              })
              .catch(function(err) {
                console.log(err);
              });
          }
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
          </div><!-- /.box-header -->
          <div class="box-body">
            <table id="pemenuhan_po_kpb" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Nomor PO</th>
                  <th>Tanggal PO</th>
                  <th>Kode Customer</th>
                  <th>Nama Customer</th>
                  <th>Total Qty</th>
                  <th>Total Harga</th>
                  <th>No. SO</th>
                  <th>Tgl. SO</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <script>
              $(document).ready(function() {
                pemenuhan_po_kpb = $('#pemenuhan_po_kpb').DataTable({
                  processing: true,
                  serverSide: true,
                  order: [],
                  ajax: {
                    url: "<?= base_url('api/md/h3/pemenuhan_po_kpb') ?>",
                    dataSrc: "data",
                    type: "POST",
                    data: function(d) {
                      d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                    }
                  },
                  createdRow: function(row, data, index) {
                    $('td', row).addClass('align-middle');
                  },
                  columns: [{
                      data: 'index',
                      orderable: false,
                      width: '3%'
                    },
                    {
                      data: 'id_po_kpb'
                    },
                    {
                      data: 'tgl_po_kpb'
                    },
                    {
                      data: 'kode_dealer_md'
                    },
                    {
                      data: 'nama_dealer'
                    },
                    {
                      data: 'tot_qty'
                    },
                    {
                      data: 'grand_total',
                      render: function(data) {
                        if (data != null) {
                          return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                        }
                        return '-';
                      },
                      className: 'text-right'
                    },
                    {
                      data: 'id_sales_order',
                      render: function(data) {
                        if (data != null) {
                          return data;
                        }
                        return '-';
                      }
                    },
                    {
                      data: 'tanggal_order',
                      render: function(data) {
                        if (data != null) {
                          return moment(data).format("DD/MM/YYYY");
                        }
                        return '-';
                      }
                    },
                    {
                      data: 'action',
                      width: '3%',
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