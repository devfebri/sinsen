<base href="<?php echo base_url(); ?>" />
<?php if (!isset($iframe)) { ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
        <li class="">Finance H23</li>
        <li class="">Billing Process</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
  <?php } ?>

  <section class="content">
    <?php
    if ($set == "index") : ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php if (can_access($isi, 'can_insert')) : ?>
              <a href="dealer/njb/create_njb" class="btn bg-blue btn-flat margin">Create NJB</a>
              <!-- <a href="dealer/njb/create_nsc" class="btn bg-blue btn-flat margin">Create NSC</a> -->
            <?php endif; ?>
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
                <th>No NJB</th>
                <th>Tgl. NJB</th>
                <th>No Work Order</th>
                <th>Tgl. Servis</th>
                <th>No. Polisi</th>
                <th>Nama Customer</th>
                <th>Tipe Motor</th>
              </tr>
            </thead>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
      <script>
        $(document).ready(function() {
          var dataTable = $('#datatable_server').DataTable({
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
              url: "<?php echo site_url('dealer/' . $isi . '/fetch'); ?>",
              type: "POST",
              dataSrc: "data",
              data: function(d) {
                // d.status_form = 'open';
                return d;
              },
            },
            "columnDefs": [
              // // { "targets":[2],"orderable":false},
              // {
              //   "targets": [5],
              //   "className": 'text-center'
              // },
              // // // { "targets":[0],"checkboxes":{'selectRow':true}}
              // // { "targets":[4],"className":'text-right'}, 
              // // // { "targets":[2,4,5], "searchable": false } 
            ],
          });
        });
      </script>
    <?php endif ?>
    <?php if ($set == 'form') :
      $form = '';
      if ($mode == 'create_njb') {
        $form = 'saveNJB';
      }
      if ($mode == 'create_nsc') {
        $form = 'saveNSC';
      }
    ?>

      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <link href='assets/select2/css/select2.min.css' rel='stylesheet' type='text/css'>
      <script src="assets/jquery/jquery.min.js"></script>
      <script src='assets/select2/js/select2.min.js'></script>

      <script>
        Vue.use(VueNumeric.default);
        Vue.filter('toCurrency', function(value) {
          return accounting.formatMoney(value, "", 0, ".", ",");
          return value;
        });

        Vue.filter('cekType', function(value, arg1) {
          if (arg1 == 'persen') {
            return value + ' %';
          } else {
            return 'Rp. ' + accounting.formatMoney(value, "", 0, ".", ",");
          }
        });

        $(document).ready(function() {
          <?php if (isset($row)) { ?>
            pilihWO(<?= json_encode($row) ?>);
          <?php } ?>
        })
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <?php if (!isset($iframe)) { ?>

            <h3 class="box-title">
              <a href="dealer/<?= $this->uri->segment(2); ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
              </a>
            </h3>
            <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
          <?php } ?>
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
            <div class="col-sm-12">
              <form class="form-horizontal" id="form_">
                <div class="form-group" v-if="mode=='detail_njb'">
                  <label class="col-sm-2 control-label">No. NJB</label>
                  <div class="col-sm-4">
                    <input type="text" readonly v-model="data.no_njb" name="no_njb" id="no_njb" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">Waktu NJB</label>
                  <div class="col-sm-4">
                    <input type="text" readonly v-model="data.waktu_njb" name="waktu_njb" id="waktu_njb" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">No Work Order</label>
                  <div class="col-sm-3">
                    <input type="text" readonly v-model="data.id_work_order" name="id_work_order" id="id_work_order" class="form-control">
                  </div>
                  <div class="col-sm-1" v-if="mode=='create_njb'">
                    <button type="button" id="btnCariWO" onclick="showModalWOProses()" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                  </div>
                  <div class="col-sm-1" v-if="mode=='detail_njb'"></div>
                  <span v-if="data!=''">
                    <label class="col-sm-2 control-label">Tanggal Servis</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.tgl_servis" class="form-control">
                    </div>
                  </span>
                </div>
                <div v-if="data!=''">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Kode Dealer</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.kode_dealer_md" class="form-control">
                    </div>
                    <label class="col-sm-2 control-label">Nama Dealer</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.nama_dealer" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">ID Mekanik (Honda ID)</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.id_karyawan_dealer" class="form-control">
                    </div>
                    <label class="col-sm-2 control-label">Nama Mekanik</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.nama_lengkap" class="form-control">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 control-label">ID Customer</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.id_customer" class="form-control">
                    </div>
                    <label class="col-sm-2 control-label">Nama Customer</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.nama_customer" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">No. Polisi</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.no_polisi" class="form-control">
                    </div>
                    <label class="col-sm-2 control-label">Tipe Unit</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.tipe_ahm" class="form-control">
                    </div>
                  </div>
                  <div v-if="data.pekerjaan">
                    <div class="col-md-12">
                      <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Detail Pekerjaan</button><br><br>
                    </div>
                    <div class="col-sm-12">
                      <table class="table table-bordered table-hover table-condensed table-stripped">
                        <thead>
                          <th>No.</th>
                          <th>Kategori</th>
                          <th>Job Type</th>
                          <th>Pekerjaan</th>
                          <th>ID Promo</th>
                          <th>Tipe Diskon</th>
                          <th>Nilai Promo</th>
                          <th>Biaya</th>
                          <th>Biaya Nett</th>
                        </thead>
                        <tbody>
                          <tr v-for="(pkj, index) of data.pekerjaan">
                            <td>{{index+1}}</td>
                            <td>{{pkj.kategori}}</td>
                            <td>{{pkj.job_type}}</td>
                            <td>{{pkj.deskripsi}}</td>
                            <td>{{pkj.id_promo}}</td>
                            <td>{{pkj.tipe_diskon}}</td>
                            <td>{{pkj.diskon | cekType(pkj.tipe_diskon)}}</td>
                            <td align="right">{{pkj.harga | toCurrency}}</td>
                            <td align="right">{{biayaNet(pkj) | toCurrency}}</td>
                          </tr>
                        </tbody>
                        <tfoot>
                          <tr v-if="pkp==1">
                            <td colspan="8" align="right"><b>Total Tanpa PPN</b></td>
                            <td align="right"><b>{{tot.tanpa_ppn | toCurrency}}</b></td>
                          </tr>
                          <tr v-if="pkp==1">
                            <td colspan="8" align="right"><b>PPN</b></td>
                            <td align="right"><b>{{tot.ppn | toCurrency}}</b></td>
                          </tr>
                          <tr>
                            <td colspan="8" align="right"><b>Grand Total</b></td>
                            <td align="right"><b>{{tot.grand_total | toCurrency}}</b></td>
                          </tr>
                        </tfoot>
                      </table>
                      </table>
                    </div>
                  </div>
                  <div class="col-sm-12" align="center" v-if="mode=='create_njb'">
                    <button type="button" id="submitBtn" @click.prevent="saveNJB" class="btn btn-info btn-flat">Create NJB</button>
                  </div>
                </div> <!-- END IF -->
              </form>
            </div>
          </div>
        </div>
        <?php
        $data['data'] = ['WOProses', 'wo_njb'];
        $this->load->view('dealer/h2_api', $data); ?>
        <script>
          var form_ = new Vue({
            el: '#form_',
            data: {
              mode: '<?= $mode ?>',
              data: '',
              pkp: <?= $pkp ?>
            },
            methods: {
              saveNJB: function() {
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
                  let values = {
                    id_work_order: $('#id_work_order').val()
                  };
                  $.ajax({
                    beforeSend: function() {
                      $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                      $('#submitBtn').attr('disabled', true);
                    },
                    url: '<?= base_url('dealer/njb/' . $form) ?>',
                    type: "POST",
                    data: values,
                    cache: false,
                    dataType: 'JSON',
                    success: function(response) {
                      $('#submitBtn').html('Create NJB');
                      if (response.status == 'sukses') {
                        window.location = response.link;
                      } else {
                        alert(response.pesan);
                        $('#submitBtn').attr('disabled', false);
                      }
                    },
                    error: function() {
                      alert("failure");
                      $('#submitBtn').html('Create NJB');
                      $('#submitBtn').attr('disabled', false);
                    },
                    statusCode: {
                      500: function() {
                        alert('fail');
                        $('#submitBtn').html('Create NJB');
                        $('#submitBtn').attr('disabled', false);
                      }
                    }
                  });
                } else {
                  alert('Silahkan isi field required !')
                }
              },
              cek_diskon: function(dt) {
                if (dt.id_promo != '') {
                  if (dt.tipe_diskon == 'rupiah') {
                    diskon = parseInt(dt.diskon);
                  } else {
                    let harga = parseInt(dt.harga);
                    if (this.pkp == 1) {
                      harga = parseInt(harga / <?php echo getPPN(1.1,false) ?> );
                    }
                    diskon = harga * (parseInt(dt.diskon) / 100);
                  }
                }
                return parseInt(diskon)
              },
              biayaNet: function(dt) {
                let diskon = 0;
                if (dt.id_promo != '') {
                  diskon = this.cek_diskon(dt);
                }
                let net = dt.harga - diskon;
                // console.log(diskon)
                return parseInt(net);
              }
            },

            computed: {
              tot: function() {
                let tot = {};
                let tanpa_ppn = 0;
                for (dtl of this.data.pekerjaan) {
                  tanpa_ppn += parseInt(this.biayaNet(dtl));
                }
                let ppn = 0;
                if (this.pkp == 1) {
                  ppn = parseInt(tanpa_ppn) * <?php echo getPPN(0.1,false) ?>;
                }
                let grand = parseInt(tanpa_ppn) + ppn;
                tot = {
                  tanpa_ppn: parseInt(tanpa_ppn),
                  ppn: ppn,
                  grand_total: grand
                }
                return tot
              },
            }
          });

          function pilihWO(wo) {
            $.ajax({
              beforeSend: function() {
                $('#btnCariWO').html('<i class="fa fa-spinner fa-spin"></i>');
                $('#btnCariWO').attr('disabled', true);
              },
              url: '<?= base_url('dealer/njb/get_wo_njb') ?>',
              type: "POST",
              data: wo,
              cache: false,
              dataType: 'JSON',
              success: function(response) {
                if (response.status == 'sukses') {
                  form_.data = response.data;
                } else {
                  alert(response.pesan);
                }
                $('#btnCariWO').html('<i class="fa fa-search"></i>');
                $('#btnCariWO').attr('disabled', false);
              },
              error: function() {
                alert("Something Went Wrong !");
                $('#btnCariWO').html('<i class="fa fa-search"></i>');
                $('#btnCariWO').attr('disabled', false);
              }
            });
          }
        </script>
      <?php endif ?>
  </section>
  </div>