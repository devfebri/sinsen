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
              <a href="<?= $folder . '/' . $isi ?>/add"> <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
                <th>ID PO</th>
                <th>Tgl. PO</th>
                <th>Nama Vendor</th>
                <th>Keterangan</th>
                <th>Total</th>
                <th>Total PPN</th>
                <th>Grand Total</th>
                <th>Status</th>
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
                  url: "<?php echo site_url($folder . '/' . $isi . '/fetch'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  {
                    "targets": [7, 8],
                    "className": 'text-center'
                  },
                  {
                    "targets": [4, 5, 6],
                    "className": 'text-right'
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
        $form = 'save';
      }
      if ($mode == 'edit') {
        $form = 'save_edit';
      }
      if ($mode == 'ubah_status') {
        $form = 'save_status';
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
            <a href="<?= $folder . '/' . $this->uri->segment(2); ?>"> <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
                    <div class="form-input">
                      <label class="col-sm-2 control-label">ID PO</label>
                      <div class="col-sm-4">
                        <input type="text" name="id_po" readonly v-model="data.id_po" class="form-control">
                      </div>
                    </div>
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Tgl PO</label>
                      <div class="col-sm-4">
                        <input type="text" name="tgl_po" readonly v-model="data.tgl_po" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">ID Vendor</label>
                      <div class="col-sm-4">
                        <input type="text" name="id_vendor" readonly v-model="data.id_vendor" class="form-control" required onclick="showModalVendorPO()">
                      </div>
                    </div>
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Nama Vendor</label>
                      <div class="col-sm-4">
                        <input type="text" name="nama_vendor" readonly v-model="data.nama_vendor" class="form-control" required onclick="showModalVendorPO()">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Keterangan</label>
                      <div class="col-sm-10">
                        <input type="text" name="keterangan" :readonly="mode=='detail' || mode=='ubah_status'" v-model="data.keterangan" class="form-control">
                      </div>
                    </div>
                  </div>
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-info btn-flat btn-sm" disabled>Detail</button><br><br>
                  <table class="table table-bordered table-hover table-condensed table-stripped">
                    <thead>
                      <th>No.</th>
                      <th>Nama Barang</th>
                      <th>Qty</th>
                      <th>Harga Satuan</th>
                      <th>Total</th>
                      <th v-if="mode=='insert' || mode=='edit'">Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dt, index) of details">
                        <td>{{index+1}}</td>
                        <td>{{dt.nama_barang}}</td>
                        <td>{{dt.qty}}</td>
                        <td align="right">{{dt.harga_satuan | toCurrency}}</td>
                        <td align="right">{{subTotal(dt) | toCurrency}}</td>
                        <td align="center" v-if="mode=='insert' || mode=='edit'">
                          <button @click.prevent="delDetails(index)" type="button" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <!-- <tr style="font-size:12pt;text-align:right">
                        <td colspan="4"><b>Total</b></td>
                        <td align="right"><b>{{totBayar | toCurrency}}</b></td>
                        <td v-if="mode=='insert' || mode=='edit'"></td>
                      </tr> -->
                      <tr v-if="mode=='insert' || mode=='edit'">
                        <td></td>
                        <td>
                          <input class="form-control input-inline" v-model="dtl.nama_barang" />
                        </td>
                        <!-- <td>
                          <input class="form-control input-inline" v-model="dtl.nama_barang" onclick="showModalBarangLuar()" readonly />
                        </td> -->
                        <td>
                          <input type="number" class="form-control input-inline" v-model="dtl.qty" />
                        </td>
                        <td>
                          <input type="number" class="form-control input-inline" v-model="dtl.harga_satuan" />
                        </td>
                        <td><input readonly type="number" class="form-control input-inline" v-model="subTotal(dtl)" />
                        </td>

                        <td align="center">
                          <button @click.prevent="addDetails" type="button" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i></button>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                  <div class="row" style="margin-top:10px">
                    <div class="col-sm-7"></div>
                    <div class="col-sm-5">
                      <table class='table table-bordered' style="font-weight:bold">
                        <tr>
                          <td align='right'>Total</td>
                          <td align='right'>
                            {{totBayar | toCurrency}}
                          </td>
                        </tr>
                        <tr>
                          <td align='right' width="40%">PPN</td>
                          <td>
                            <input type='checkbox' v-model='ada_ppn' true-value='1' false-value='0' :disabled="mode=='detail' || mode=='ubah_status'" />
                            <input style="width:50%" v-if="ada_ppn=='1'" type="number" class="form-control input-inline" v-model="ppn" :disabled="mode=='detail' || mode=='ubah_status'" />
                          </td>
                        </tr>
                        <tr>
                          <td align='right'>Total PPN</td>
                          <td align='right'>
                            {{tot_ppn | toCurrency}}
                          </td>
                        </tr>
                        <tr style='font-size:12pt'>
                          <td align='right'>Grand Total</td>
                          <td align='right'>{{grand | toCurrency}}</td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div> <!-- End OF Header-->
                <div class="box-footer">
                  <div class="form-group">
                    <div class="col-sm-12" align="center">
                      <button type="button" id="submitBtn" v-if="mode=='insert' || mode=='edit'" @click.prevent="save_data('')" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                      <button type="button" id="submitBtn" v-if="mode=='ubah_status'" @click.prevent="save_data('approved')" class="btn btn-success btn-flat">Approve</button>
                      <button type="button" id="submitBtn" v-if="mode=='ubah_status'" @click.prevent="save_data('batal')" class="btn btn-danger btn-flat">Batal</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php
        $data['data'] = ['modalVendorPO'];
        $this->load->view('dealer/h2_api_finance', $data); ?>
        <script>
          var coa_to = '';
          var form_ = new Vue({
            el: '#form_',
            data: {
              mode: '<?= $mode ?>',
              ada_ppn: <?= isset($row) ? json_encode($row->ada_ppn) : 0 ?>,
              ppn: <?= isset($row) ? json_encode($row->ppn) : 0 ?>,
              data: <?= isset($row) ? json_encode($row) : "{}" ?>,
              details: <?= isset($row) ? json_encode($details) : '[]' ?>,
              dtl: {
                // id_barang: '',
                nama_barang: '',
                qty: '',
                harga_satuan: '',
              },
            },
            methods: {
              save_data: function(status) {
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
                    details: this.details,
                    ada_ppn: this.ada_ppn,
                    ppn: this.ppn,
                    tot_ppn: this.tot_ppn,
                    total: this.totBayar,
                    grand: this.grand,
                    status: status
                  };
                  var form = $('#form_').serializeArray();
                  for (field of form) {
                    values[field.name] = field.value;
                  }
                  if (this.details.length === 0) {
                    alert('Belum ada detail barang yang dipilih !');
                    return false;
                  }
                  if (confirm("Apakah anda yakin ?") == true) {
                    $.ajax({
                      beforeSend: function() {
                        $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                        $('#submitBtn').attr('disabled', true);
                      },
                      url: '<?= base_url($folder . '/' . $isi . '/' . $form) ?>',
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
              clearDetail: function() {
                this.dtl = {
                  // id_barang: '',
                  nama_barang: '',
                  qty: 0,
                  harga_satuan: 0,
                }
              },
              addDetails: function() {
                if (this.dtl.nama_barang === '') {
                  alert('Barang belum diisi !');
                  return false;
                }
                if (this.dtl.qty === 0 || this.dtl.qty === '') {
                  alert('Tentukan qty !');
                  return false;
                }
                this.details.push(this.dtl);
                this.clearDetail();
              },
              delDetails: function(index) {
                this.details.splice(index, 1);
              },
              subTotal: function(dtl) {
                return parseInt(dtl.qty) * parseInt(dtl.harga_satuan)
              },
            },
            computed: {
              grand: function() {
                return this.totBayar + this.tot_ppn;
              },
              tot_ppn: function() {
                tot_ppn = 0;
                if (this.ada_ppn == '1') {
                  tot_ppn = this.totBayar * (this.ppn / 100);
                }
                return tot_ppn;
              },
              totBayar: function() {
                let tot = 0;
                let diskon = 0;
                for (dtl of this.details) {
                  tot += parseInt(this.subTotal(dtl));
                }
                return tot;
              }
            }
          });

          function pilihVendor(vendor) {
            form_.data = {
              id_vendor: vendor.id_vendor,
              nama_vendor: vendor.nama_vendor,
            }
            form_.ppn = vendor.ppn
          }

          // function pilihBarangLuar(barang) {
          //   form_.dtl.id_barang = barang.id_barang;
          //   form_.dtl.nama_barang = barang.nama_barang;
          //   form_.dtl.harga_satuan = barang.harga_satuan;
          // }
        </script>
      <?php } ?>
  </section>
</div>