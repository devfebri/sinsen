<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">Finance H23</li>
      <li class="">Finance</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php
    if ($set == "index") { ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php if (can_access($isi, 'can_insert')) : ?>
              <a href="dealer/h2_penerimaan_kas/add">
                <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
              </a>
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
                <th>No. Receipt Kas</th>
                <th>Tgl. Entry</th>
                <th>Akun Kas</th>
                <th>Account Name</th>
                <th>Tot. Jml. Dibayar</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
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
                    d.jenis_penerimaan = 'kas'
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  {
                    "targets": [5],
                    "className": 'text-center'
                  },
                  // // { "targets":[0],"checkboxes":{'selectRow':true}}
                  // { "targets":[4],"className":'text-right'}, 
                  // // { "targets":[2,4,5], "searchable": false } 
                ],
              });
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php } elseif ($set == 'form') {
      $form = '';
      if ($mode == 'insert') {
        $form = 'save_kas';
      }
      if ($mode == 'edit') {
        $form = 'save_edit';
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
          return 'Rp. ' + accounting.formatMoney(value, "", 0, ".", ",");
          // return value;
        });

        Vue.filter('cekType', function(value, arg1) {
          if (arg1 == 'persen') {
            return value + ' %';
          } else {
            return 'Rp. ' + accounting.formatMoney(value, "", 0, ".", ",");
          }
        });

        $(document).ready(function() {})
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $this->uri->segment(2); ?>">
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
            <div class="col-sm-12">
              <form class="form-horizontal" id="form_" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group" v-if="mode!='insert'">
                    <label class="col-sm-2 control-label">No. Receipt Kas</label>
                    <div class="col-sm-4">
                      <input type="text" readonly v-model="data.no_receipt_kas" name="no_receipt_kas" id="no_receipt_kas" class="form-control">
                    </div>
                    <label class="col-sm-2 control-label">Tanggal Entry</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.tgl_entry" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Account Kas</label>
                      <div class="col-sm-3">
                        <input type="text" readonly v-model="data.kode_coa" name="kode_coa" id="kode_coa" class="form-control" onclick="showModalCOADealer('header')" required>
                      </div>
                    </div>
                    <div class="col-sm-1">
                      <button v-if="mode=='insert' || mode=='edit'" type="button" onclick="showModalCOADealer('header')" class="btn btn-flat btn-primary" id="btnSearchCOA"><i class="fa fa-search"></i></button>
                    </div>
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Account Name</label>
                      <div class="col-sm-4">
                        <input type="text" readonly v-model="data.coa" class="form-control" required>
                      </div>
                    </div>
                  </div>
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-info btn-flat btn-sm" disabled>Detail Penerimaan</button><br><br>
                  <table class="table table-bordered table-hover table-condensed table-stripped">
                    <thead>
                      <th>Account</th>
                      <th>Jenis Transaksi</th>
                      <th>Referensi</th>
                      <th>Jumlah Dibayar</th>
                      <th>Keterangan</th>
                      <th v-if="mode=='insert' || mode=='edit'">Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dt, index) of details">
                        <td>{{dt.kode_coa}}</td>
                        <td>{{dt.tipe_transaksi}}</td>
                        <td>{{dt.id_referensi}}</td>
                        <td align="right">{{dt.dibayar | toCurrency}}</td>
                        <td>{{dt.keterangan}}</td>
                        <td align="center" v-if="mode=='insert' || mode=='edit'">
                          <button @click.prevent="delDetails(index)" type="button" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr v-if="mode=='insert' || mode=='edit'">
                        <td>
                          <input class="form-control input-inline" v-model="dtl.kode_coa" onclick="showModalCOADealer('detail')" readonly />
                        </td>
                        <td>
                          <input class="form-control input-inline" v-model="dtl.tipe_transaksi" onclick="showModalCOADealer('detail')" readonly />
                        </td>
                        <td>
                          <input class="form-control input-inline" v-model="dtl.id_referensi" style="width:75%;display:inline" />
                          <button style="text-align:right;width:15%" type="button" class="btn btn-primary btn-sm btn-flat" onclick="showModalRefPenerimaan('Cash')"><i class="fa fa-search"></i></button>
                        </td>
                        <td>
                          <input class="form-control input-inline" v-model="dtl.dibayar" />
                        </td>
                        <td>
                          <input class="form-control input-inline" v-model="dtl.keterangan" />
                        </td>
                        <td align="center">
                          <button @click.prevent="addDetails" type="button" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i></button>
                        </td>
                      </tr>
                      <tr style="font-size:12pt;text-align:right">
                        <td colspan="3"><b>Total</b></td>
                        <td align="right"><b>{{totBayar | toCurrency}}</b></td>
                        <td></td>
                        <td v-if="mode=='insert' || mode=='edit'"></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="box-footer">
                  <div class="form-group">
                    <div class="col-sm-12" align="center" v-if="mode=='insert' || mode=='edit'">
                      <button type="button" id="submitBtn" @click.prevent="savePenerimaan" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php
        $data['data'] = ['modalCOADealer', 'modalRefPenerimaan', 'not_exist_penerimaan'];
        $this->load->view('dealer/h2_api_finance', $data); ?>
        <script>
          var coa_to = '';
          var form_ = new Vue({
            el: '#form_',
            data: {
              mode: '<?= $mode ?>',
              data: <?= isset($row) ? json_encode($row) : "{kode_coa:'',coa:''}" ?>,
              details: <?= isset($row) ? json_encode($details) : '[]' ?>,
              dtl: {
                kode_coa: '',
                coa: '',
                tipe_transaksi: '',
                id_referensi: '',
                dibayar: 0,
                keterangan: ''
              },
            },
            methods: {
              savePenerimaan: function() {
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
                    details: form_.details
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
                      url: '<?= base_url('dealer/h2_penerimaan_kas/' . $form) ?>',
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
              showDetailTransaksi: function(dtl) {
                console.log(dtl)
              },
              clearDetail: function() {
                this.dtl = {
                  kode_coa: '',
                  coa: '',
                  tipe_transaksi: '',
                  id_referensi: '',
                  dibayar: 0,
                  keterangan: ''
                }
              },
              addDetails: function() {
                if (this.dtl.kode_coa === '') {
                  alert('COA belum dipilih !');
                  return false
                }
                if (this.dtl.dibayar == 0) {
                  alert('Tentukan jumlah dibayar !');
                  return false
                }
                this.details.push(this.dtl);
                this.clearDetail();
              },
              delDetails: function(index) {
                this.details.splice(index, 1);
              },
            },
            computed: {
              totBiaya: function() {
                let tot = 0;
                let diskon = 0;
                for (dtl of this.details.details) {
                  tot += parseInt(dtl.nilai);
                }
                return tot;
              },
              totBayar: function() {
                let tot = 0;
                for (dtl of this.details) {
                  tot += parseInt(dtl.dibayar);
                }
                return tot;
              },
              sudahBayar: function() {
                return this.riwayat_bayar;
              },
              sisa: function() {
                return this.totBiaya - this.sudahBayar;
              }
            }
          });

          function showModalCOADealer(to) {
            coa_to = to
            $('#modalCOADealer').modal('show');
          }

          function pilihCOADealer(coa) {
            if (coa_to == 'detail') {
              form_.dtl.kode_coa = coa.kode_coa;
              form_.dtl.coa = coa.coa;
              form_.dtl.tipe_transaksi = coa.coa;
            } else if (coa_to == 'header') {
              form_.data.kode_coa = coa.kode_coa;
              form_.data.coa = coa.coa;
            }
            coa_to = '';
          }

          function pilihRef(ref) {
            form_.dtl.id_referensi = ref.id_referensi
            form_.dtl.dibayar = ref.jumlah;
          }
        </script>
      <?php } ?>
  </section>
</div>