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
                <th>ID Tagihan</th>
                <th>Tanggal</th>
                <th>Nama Vendor</th>
                <th>Total</th>
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
                    "targets": [4, 5],
                    "className": 'text-center'
                  },
                  {
                    "targets": [3],
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
                      <label class="col-sm-2 control-label">ID Tagihan</label>
                      <div class="col-sm-4">
                        <input type="text" readonly name="id_tagihan" readonly v-model="data.id_tagihan" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Tipe Customer</label>
                      <div class="col-sm-4">
                        <select class="form-control" v-model="data.tipe_customer" required name="tipe_customer" :disabled="mode=='detail'||mode=='ubah_status'">
                          <option value="vendor">Vendor</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="form-group" v-if="data.tipe_customer=='vendor'">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">ID Vendor</label>
                      <div class="col-sm-4">
                        <input type="text" id="id_vendor" name="id_vendor" readonly v-model="data.id_vendor" class="form-control" required onclick="showModalVendorPO()" placeholder="Klik untuk memilih">
                      </div>
                    </div>
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Nama Vendor</label>
                      <div class="col-sm-4">
                        <input type="text" name="nama_vendor" readonly v-model="data.nama_vendor" class="form-control" required onclick="showModalVendorPO()" placeholder="Klik untuk memilih">
                      </div>
                    </div>
                  </div>
                  <div class="form-group" v-if="data.tipe_customer=='vendor'">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Tanggal</label>
                      <div class="col-sm-4">
                        <input type="text" id="tgl_tagihan" name="tgl_tagihan" readonly v-model="data.tgl_tagihan" class="form-control datepicker" required placeholder="Klik untuk memilih">
                      </div>
                    </div>
                  </div>
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-info btn-flat btn-sm" disabled>Tipe</button><br><br>
                  <table class="table table-bordered table-hover table-condensed table-stripped">
                    <thead>
                      <th>COA</th>
                      <th>No. PO</th>
                      <th>Tgl. PO</th>
                      <th>No. Kwitansi</th>
                      <th>Tgl. Kwitansi</th>
                      <th>No. BAST</th>
                      <th>Tgl. BAST</th>
                      <th>Due Date</th>
                      <th>PPN(%)</th>
                      <th width='6%'>Tipe PPH</th>
                      <th>PPH</th>
                      <th>Jumlah Yang Dibayar</th>
                      <th v-if="mode=='insert' || mode=='edit'">Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dt, index) of details">
                        <td>{{dt.kode_coa}}</td>
                        <td>{{dt.id_po}}</td>
                        <td>{{dt.tgl_po}}</td>
                        <td>{{dt.no_kwitansi}}</td>
                        <td>{{dt.tgl_kwitansi}}</td>
                        <td>{{dt.no_bast}}</td>
                        <td>{{dt.tgl_bast}}</td>
                        <td>{{dt.due_date}}</td>
                        <td>{{dt.ppn}}</td>
                        <td>{{dt.tipe_pph}}</td>
                        <td align="right">{{pph(dt) | toCurrency}}</td>
                        <td align="right">{{jmlDibayar(index,dt) | toCurrency}}</td>
                        <td align="center" v-if="mode=='insert' || mode=='edit'">
                          <button @click.prevent="delDetails(index)" type="button" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr style="font-size:12pt;text-align:right">
                        <td colspan="11"><b>Total</b></td>
                        <td align="right"><b>{{totBayar | toCurrency}}</b></td>
                        <td v-if="mode=='insert' || mode=='edit'"></td>
                      </tr>
                      <tr v-if="mode=='insert' || mode=='edit'">
                        <td>
                          <input type="text" readonly v-model="dtl.kode_coa" class="form-control input-inline" onclick="showModalCOADealer()">
                        </td>
                        <td>
                          <input class="form-control input-inline" v-model="dtl.id_po" onclick="showModalPOFinance()" readonly />
                        </td>
                        <td>
                          <input class="form-control input-inline" v-model="dtl.tgl_po" onclick="showModalPOFinance()" readonly />
                        </td>
                        <td>
                          <input type="text" class="form-control input-inline" v-model="dtl.no_kwitansi" />
                        </td>
                        <td>
                          <input type="text" class="form-control input-inline datepicker" id="tgl_kwitansi" />
                        </td>
                        <td>
                          <input type="text" class="form-control input-inline" v-model="dtl.no_bast" />
                        </td>
                        <td>
                          <input type="text" class="form-control input-inline datepicker" id="tgl_bast" />
                        </td>
                        <td>
                          <input type="text" class="form-control input-inline datepicker" id="due_date" />
                        </td>
                        <td>
                          <input type="text" class="form-control input-inline" v-model="dtl.ppn" />
                        </td>
                        <td>
                          <select class="form-control input-inline" style='padding-left:0px' v-model="dtl.tipe_pph">
                            <option value="1">1</option>
                            <option value="2">2</option>
                          </select>
                        </td>
                        <td>{{pph(dtl) | toCurrency}}</td>
                        <td>{{jmlDibayar(null,dtl) | toCurrency}}</td>
                        <td align="center">
                          <button @click.prevent="addDetails" type="button" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i></button>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
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
        $data['data'] = ['modalVendorPO', 'modalCOADealer', 'modalPOFinance', 'po_sel_vendor', 'po_sel_approved'];
        $this->load->view('dealer/h2_api_finance', $data); ?>
        <script>
          var coa_to = '';
          var form_ = new Vue({
            el: '#form_',
            data: {
              mode: '<?= $mode ?>',
              data: <?= isset($row) ? json_encode($row) : "{tipe_customer:'vendor',id_vendor:'',nama_vendor:''}" ?>,
              details: <?= isset($row) ? json_encode($details) : '[]' ?>,
              dtl: {
                kode_coa: '',
                coa: '',
                id_po: '',
                tgl_po: '',
                tot_po: 0,
                ppn: 0,
                pph: 0,
                tipe_pph: 1,
              },
            },
            methods: {
              pph: function(dtl) {
                let val_pph = parseInt(dtl.tipe_pph);
                let pph = parseInt((dtl.tot_po / <?php echo getPPN(1.1,false) ?>) * (parseInt(val_pph) / 100));
                return pph;
              },
              jmlDibayar: function(idx, dtl) {
                let tot = 0;
                // val_ppn = dtl.ppn == '' ? 0 : dtl.ppn;
                // let ppn = parseInt(dtl.tot_po * (parseInt(val_ppn) / 100));
                ppn = 0;
                // console.log(this.pph(dtl));
                tot = (parseInt(dtl.tot_po) + ppn) - this.pph(dtl);
                if (idx != null) {
                  this.details[idx].tot_po_tagihan = tot;
                }
                return tot;
              },
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
                  kode_coa: '',
                  coa: '',
                  id_po: '',
                  tgl_po: '',
                  tot_po: 0,
                  // ppn: 0,
                  pph: 0,
                  tipe_pph: 1,
                }
                $('#tgl_kwitansi').val('');
                $('#tgl_bast').val('');
                $('#due_date').val('');
              },
              addDetails: function() {
                if (this.dtl.kode_coa === '') {
                  alert('COA belum dipilih !');
                  return false;
                }
                if (this.dtl.id_po === '') {
                  alert('PO belum dipilih !');
                  return false;
                }
                // if (this.dtl.qty === 0 || this.dtl.qty === '') {
                //   alert('Tentukan qty !');
                //   return false;
                // }
                this.dtl.tgl_kwitansi = $('#tgl_kwitansi').val();
                this.dtl.tgl_bast = $('#tgl_bast').val();
                this.dtl.due_date = $('#due_date').val();
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
              totBayar: function() {
                let tot = 0;
                for (dtl of this.details) {
                  tot += parseInt(this.jmlDibayar(null, dtl));
                }
                return tot;
              }
            }
          });

          function pilihVendor(vendor) {
            form_.data.id_vendor = vendor.id_vendor;
            form_.data.nama_vendor = vendor.nama_vendor;
            form_.dtl.ppn = vendor.ppn;
          }

          function showModalCOADealer() {
            $('#modalCOADealer').modal('show');
          }

          function pilihCOADealer(coa) {
            form_.dtl.kode_coa = coa.kode_coa;
            form_.dtl.coa = coa.coa;
          }

          function pilihPO(po) {
            form_.dtl.id_po = po.id_po;
            form_.dtl.tgl_po = po.tgl_po;
            form_.dtl.tot_po = po.total;
          }
        </script>
      <?php } ?>
  </section>
</div>