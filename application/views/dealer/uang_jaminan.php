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
      <li class="">Billing Process</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php
    if ($set == "index") : ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php if (can_access($isi, 'can_insert')) : ?>
              <a href="dealer/uang_jaminan/create" class="btn bg-blue btn-flat margin">Create Uang Jaminan invoice</a>
            <?php endif; ?>
            <a href="dealer/<?= $isi ?>/history" class="btn bg-blue btn-flat margin"><i class="fa fa-list"></i> History</a>
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
                <th>No Invoice</th>
                <th>Tgl. Invoice</th>
                <th>ID Booking</th>
                <th>Tgl. Request Document</th>
                <th>ID Customer</th>
                <th>Nama Customer</th>
                <th>Uang Muka</th>
                <th>Sisa Pembayaran</th>
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
                  url: "<?php echo site_url('api/dealer/uang_jaminan_new'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                },
                "columnDefs": [{
                    "targets": [8],
                    "orderable": false
                  },
                  {
                    "targets": [8],
                    "className": 'text-center'
                  },
                  // // // { "targets":[0],"checkboxes":{'selectRow':true}}
                  {
                    "targets": [6, 7],
                    "className": 'text-right'
                  },
                  // // // { "targets":[2,4,5], "searchable": false } 
                ],
              });
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php endif ?>
    <?php
    if ($set == "history") : ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $isi ?>" class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</a>
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
                <th>No Invoice</th>
                <th>Tgl. Invoice</th>
                <th>ID Booking</th>
                <th>Tgl. Request Document</th>
                <th>ID Customer</th>
                <th>Nama Customer</th>
                <th>Uang Muka</th>
                <th>Sisa Pembayaran</th>
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
                    d.sisa = 0;
                    return d;
                  },
                },
                "columnDefs": [{
                    "targets": [8],
                    "orderable": false
                  },
                  {
                    "targets": [8],
                    "className": 'text-center'
                  },
                  // // // { "targets":[0],"checkboxes":{'selectRow':true}}
                  {
                    "targets": [6, 7],
                    "className": 'text-right'
                  },
                  // // // { "targets":[2,4,5], "searchable": false } 
                ],
              });
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php endif ?>
    <?php if ($set == 'form') :
      $form = '';
      if ($mode == 'create') {
        $form = 'save_uang_jaminan';
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
          return accounting.formatMoney(value, "", 0, ".", ",");
          return value;
        });

        $(document).ready(function() {
          <?php if (isset($row)) { ?>
            pilihRequestDoc(<?= json_encode($row) ?>);
          <?php } ?>
        })
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
              <form class="form-horizontal" id="form_">
                <div class="form-group" v-if="mode=='detail' || mode=='edit'">
                  <label class="col-sm-2 control-label">Nomor Invoice Uang Jaminan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly v-model="data.no_inv_uang_jaminan" name="no_inv_uang_jaminan" id="no_inv_uang_jaminan" class="form-control">
                  </div>
                </div>
                <div class="form-group" v-if="mode=='detail' || mode=='edit'">
                  <label class="col-sm-2 control-label">Tgl Invoice Uang Jaminan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly v-model="data.tgl_invoice" name="tgl_invoice" id="tgl_invoice" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">ID Booking</label>
                  <div class="col-sm-4">
                    <input type="text" readonly v-model="data.id_booking" name="id_booking" id="id_booking" class="form-control">
                  </div>
                  <div class="col-sm-1" v-if="mode=='create'">
                    <button type="button" id="btnCariRequestDoc" onclick="showModalRequestDocument()" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                  </div>
                </div>
                <div v-if="data!=''">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Tgl Request Document</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.tgl_request" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">ID Customer</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.id_customer" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Nama Customer</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.nama_customer" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">ID Work Order</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.id_work_order" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">No Claim C2</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.no_claim_c2" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Order To</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.order_to" class="form-control">
                    </div>
                  </div>

                  <div class="col-md-12">
                    <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Detail Parts</button><br><br>
                  </div>
                  <div class="col-sm-12">
                    <table class="table table-bordered table-hover table-condensed table-stripped">
                      <thead>
                        <th>No.</th>
                        <th>Kode Part</th>
                        <th>Deskripsi Part</th>
                        <th>Kuantitas</th>
                        <th>Harga</th>
                        <th width='15%'>Persen Uang Muka (%)</th>
                        <th width='10%'>Uang Muka</th>
                        <th width="15%">Subtotal</th>
                      </thead>
                      <tbody>
                        <tr v-for="(prt, index) of data.parts">
                          <!-- <td>{{index+1}}</td> -->
                          <td v-if='prt.part_revisi_dari_md =="1"'>{{index+1}} 
                            <span class="fa fa-exclamation" data-toggle="tooltip" data-original-title="Part Revisi"></span>
                          </td>
                          <td v-else>{{index+1}}</td>
                          <td>{{prt.id_part}}</td>
                          <td>{{prt.nama_part}}</td>
                          <td>{{prt.kuantitas}}</td>
                          <td align="right">{{prt.harga_saat_dibeli | toCurrency}}</td>
                          <td align="right">
                            <input style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan input-sm" v-model="prt.persen_uang_muka">
                          </td>
                          <td align="right">{{hitungUangMukaPerPart(index,prt) | toCurrency}}</td>
                          <td align="right">{{subTotPart(prt) | toCurrency}}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr style="font-size:12pt">
                          <td colspan="7" align="right"><b>Grand Total</b></td>
                          <td align="right"><b>{{totParts.grand_total | toCurrency}}</b></td>
                        </tr>
                        <tr style="font-size:12pt">
                          <td colspan="7" align="right"><b>Uang Muka</b></td>
                          <td align="right">{{total_bayar | toCurrency}}</td>
                        </tr>
                        <tr style="font-size:12pt">
                          <td colspan="7" align="right"><b>Sisa Pembayaran</b></td>
                          <td align="right">{{totParts.sisa | toCurrency}}</td>
                        </tr>
                      </tfoot>
                    </table>
                    </table>
                  </div>
                  <div v-if="data.no_claim_c2 || total_bayar>0">
                    <div class="col-md-12">
                      <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-info btn-flat btn-sm" disabled>Detail Pembayaran</button><br><br>
                    </div>
                    <div class="col-sm-12">
                      <table class="table table-bordered table-hover table-condensed table-stripped">
                        <thead>
                          <th>Metode Pembayaran</th>
                          <th>No. Rekening</th>
                          <th>Nama Bank</th>
                          <th>Tanggal</th>
                          <th>Nominal Pembayaran</th>
                          <th v-if="mode=='create' || mode=='edit'">Aksi</th>
                        </thead>
                        <tbody>
                          <tr v-for="(byr, index) of pembayarans">
                            <td>{{byr.metode_bayar=='uang_muka'?'Uang Jaminan':byr.metode_bayar}}</td>
                            <td>{{byr.no_rekening}}</td>
                            <td>{{byr.bank}}</td>
                            <td>{{byr.tanggal_transaksi}}</td>
                            <td align="right">{{byr.nominal | toCurrency}}</td>
                            <td align="center" v-if="mode=='create' || mode=='edit'">
                              <button @click.prevent="delPembayarans(index)" type="button" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                            </td>
                          </tr>
                        </tbody>
                        <tfoot>
                          <tr v-if="mode=='create' || mode=='edit'">
                            <td>
                              <select v-model="pembayaran.metode_bayar" class="form-control" id="metode_bayar">
                                <option value="">- Pilih -</option>
                                <option value="Cash">Cash</option>
                                <option value="Transfer">Transfer</option>
                              </select>
                            </td>
                            <td>
                              <input v-if="pembayaran.metode_bayar=='Transfer'" class="form-control" v-model="pembayaran.no_rekening" onclick="showModalRekDealer()" readonly placeholder='Klik Untuk Memilih' />
                            </td>
                            <td>
                              <input v-if="pembayaran.metode_bayar=='Transfer'" class="form-control" v-model="pembayaran.bank" onclick="showModalRekDealer()" readonly placeholder='Klik Untuk Memilih' />
                            </td>
                            <td>
                              <date-picker v-if="pembayaran.metode_bayar=='Transfer'" v-model="pembayaran.tanggal_transaksi" readonly required placeholder='Klik Untuk Memilih'></date-picker>
                            </td>
                            <td>
                              <!-- <input class="form-control" v-model="pembayaran.nominal" /> -->
                              <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control" v-model="pembayaran.nominal" v-bind:minus="false" :empty-value="0" separator="." onkeypress="return number_only(event)" />
                            </td>
                            <td align="center">
                              <button @click.prevent="addPembayarans" type="button" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i></button>
                            </td>
                          </tr>
                          <tr align="right">
                            <td colspan="4"><b>Total</b></td>
                            <td align="right"><b>{{totBayar | toCurrency}}</b></td>
                            <td v-if="mode=='create' || mode=='edit'"></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                  <div class="col-sm-12" align="center" v-if="mode=='create' || mode=='edit' ">
                    <button type="button" id="submitBtn" @click.prevent="save_uang_jaminan" class="btn btn-info btn-flat">Save All</button>
                  </div>
                </div> <!-- END IF -->
              </form>
            </div>
          </div>
        </div>
        <?php
        $data['data'] = ['modalRequestDocument', 'not_exists_po_id', 'modalRekDealer'];
        $this->load->view('dealer/h2_api', $data); ?>
        <script>
          function pilihRek(rk) {
            form_.pembayaran.no_rekening = rk.no_rek
            form_.pembayaran.atas_nama = rk.nama_rek
            form_.pembayaran.bank = rk.bank
            form_.pembayaran.id_bank = rk.id_bank
          }
          var form_ = new Vue({
            el: '#form_',
            data: {
              mode: '<?= $mode ?>',
              data: '',
              total_bayar: <?= isset($row) ? $row->total_bayar : 0 ?>,
              pembayaran: {
                metode_bayar: '',
                no_rekening: '',
                atas_nama: '',
                bank: '',
                id_bank: '',
                uang_muka: 0,
                nominal: 0,
                no_inv_jaminan: '',
                tanggal_transaksi: ''
              },
              pembayarans: <?= isset($pembayarans) ? json_encode($pembayarans) : "[]" ?>,
            },
            methods: {
              save_uang_jaminan: function() {
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
                    id_booking: form_.data.id_booking,
                    total_bayar: form_.total_bayar,
                    grand_total: form_.totParts.grand_total,
                    parts: form_.data.parts,
                    pembayarans: form_.pembayarans,
                    no_inv_uang_jaminan: $('#no_inv_uang_jaminan').val()
                  };
                  if(form_.data.no_claim_c2=='' ||form_.data.no_claim_c2==undefined){
                    if (parseFloat(form_.total_bayar) > parseFloat(form_.totParts.grand_total)) {
                      alert('Total bayar tidak boleh lebih besar dari Grand Total !');
                      return false;
                    } else if (parseFloat(form_.total_bayar) == 0) {
                      alert('Total bayar tidak boleh kosong !');
                      return false;
                    }
                    if (form_.pembayarans.length === 0) {
                      alert('Silahkan tentukan detail pembayaran terlebih dahulu !');
                      return false;
                    }
                    if (parseFloat(form_.totBayar) > parseFloat(form_.total_bayar)) {
                      alert('Pembayaran melebihi nilai uang muka !');
                      return false;
                    } else if (parseFloat(form_.totBayar) < parseFloat(form_.total_bayar)) {
                      alert('Pembayaran kurang dari nilai uang muka !');
                      return false;
                    }
                  }else{
                    if (form_.pembayarans.length === 0) {
                      alert('Silahkan tentukan detail pembayaran terlebih dahulu !');
                      return false;
                    }
                  }

                  // if (parseFloat(form_.total_bayar) > parseFloat(form_.totParts.grand_total)) {
                  //   alert('Total bayar tidak boleh lebih besar dari Grand Total !');
                  //   return false;
                  // } else if (parseFloat(form_.total_bayar) == 0) {
                  //   alert('Total bayar tidak boleh kosong !');
                  //   return false;
                  // }
                  // if (form_.pembayarans.length === 0) {
                  //   alert('Silahkan tentukan detail pembayaran terlebih dahulu !');
                  //   return false;
                  // }
                  // if (parseFloat(form_.totBayar) > parseFloat(form_.total_bayar)) {
                  //   alert('Pembayaran melebihi nilai uang muka !');
                  //   return false;
                  // } else if (parseFloat(form_.totBayar) < parseFloat(form_.total_bayar)) {
                  //   alert('Pembayaran kurang dari nilai uang muka !');
                  //   return false;
                  // }
                  if (confirm("Apakah anda yakin ?") == true) {
                    $.ajax({
                      beforeSend: function() {
                        $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                        $('#submitBtn').attr('disabled', true);
                      },
                      url: '<?= base_url('dealer/uang_jaminan/' . $form) ?>',
                      type: "POST",
                      data: values,
                      cache: false,
                      dataType: 'JSON',
                      success: function(response) {
                        $('#submitBtn').html('Save All');
                        if (response.status == 'sukses') {
                          window.location = response.link;
                        } else {
                          alert(response.pesan);
                          $('#submitBtn').attr('disabled', false);
                        }
                      },
                      error: function() {
                        alert("failure");
                        $('#submitBtn').html('Save All');
                        $('#submitBtn').attr('disabled', false);
                      }
                    });
                  } else {
                    return false;
                  }
                } else {
                  alert('Silahkan isi field required !')
                }
              },
              subTotPart: function(parts, skip_uang_muka = false) {
                let uang_muka = parseFloat(parts.uang_muka);
                if (skip_uang_muka == true) {
                  uang_muka = 0;
                }
                return parseFloat(parts.harga_saat_dibeli) * parseFloat(parts.kuantitas) - uang_muka;
              },
              hitungUangMukaPerPart: function(idx, part) {
                let uang_muka = (parseFloat(part.kuantitas) * parseFloat(part.persen_uang_muka) * parseFloat(part.harga_saat_dibeli)) / 100;
                this.data.parts[idx].uang_muka = uang_muka;
                return uang_muka;
              },
              clearPembayaran: function() {
                this.pembayaran = {
                  metode_bayar: '',
                  no_rekening: '',
                  atas_nama: '',
                  bank: '',
                  id_bank: '',
                }
              },
              addPembayarans: function() {
                if (this.pembayaran.metode_bayar === '') {
                  alert('Pilih metode pembayaran !');
                  return false;
                }
                if (this.pembayaran.metode_bayar == 'Cash') {
                  this.pembayaran.no_rekening = '';
                  this.pembayaran.atas_nama = '';
                  this.pembayaran.bank = '';
                  this.pembayaran.id_bank = '';
                  this.pembayaran.tanggal_transaksi = '<?= tanggal() ?>';
                } else {
                  console.log(this.pembayaran);
                  if (this.pembayaran.no_rekening === '') {
                    alert('Rekening belum ditentukan !')
                    return false;
                  }
                  if (this.pembayaran.tanggal_transaksi === '') {
                    alert('Tanggal transfer belum ditentukan !')
                    return false;
                  }
                }
                if(form_.data.no_claim_c2=='' ||form_.data.no_claim_c2==undefined) {
                  if (this.pembayaran.nominal === undefined) {
                    alert('Nominal pembayaran belum ditentukan !')
                    return false;
                  } else if (this.pembayaran.nominal === '' || this.pembayaran.nominal === 0) {
                    alert('Nominal pembayaran belum ditentukan !');
                    return false;
                  }
                }
                // if (this.pembayaran.nominal === undefined) {
                //   alert('Nominal pembayaran belum ditentukan !')
                //   return false;
                // } else if (this.pembayaran.nominal === '' || this.pembayaran.nominal === 0) {
                //   alert('Nominal pembayaran belum ditentukan !');
                //   return false;
                // }
                this.pushPembayaran();
                // console.log(this.nominal_lebih)
              },
              pushPembayaran: function() {
                this.pembayarans.push(this.pembayaran);
                this.clearPembayaran();
              },
              delPembayarans: function(index) {
                this.pembayarans.splice(index, 1);
              },
            },

            computed: {
              totParts: function() {
                tot = {
                  total_no_ppn: 0,
                  grand_total: 0,
                  ppn: 0,
                  sisa: 0,
                }
                for (dtl of this.data.parts) {
                  tot.total_no_ppn += parseFloat(this.subTotPart(dtl, true));
                }
                tot.grand_total = tot.total_no_ppn;
                tot.sisa = tot.grand_total - this.total_bayar;
                return tot;
              },
              totBayar: function() {
                let tot = 0;
                let diskon = 0;
                for (dtl of this.pembayarans) {
                  tot += parseInt(dtl.nominal);
                }
                return tot;
              },
            },
            watch: {
              data: {
                deep: true,
                handler: function() {
                  tot = 0;
                  for (let index = 0; index < this.data.parts.length; index++) {
                    x = this.data.parts[index];
                    if (parseFloat(x.persen_uang_muka) > 100) {
                      let pesan = 'Persen uang muka melebihi 100';
                      toastr.error(pesan);
                      this.data.parts[index].persen_uang_muka = 0;
                      return;
                    }
                    tot += parseFloat(x.uang_muka);
                  }
                  this.total_bayar = tot;
                }
              }
            }
          });

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

          function pilihRequestDoc(book) {
            let values = {
              mode: form_.mode,
              book: book
            }
            $.ajax({
              beforeSend: function() {
                $('#btnCariRequestDoc').html('<i class="fa fa-spinner fa-spin"></i>');
                $('#btnCariRequestDoc').attr('disabled', true);
              },
              url: '<?= base_url('dealer/uang_jaminan/getUangJaminan') ?>',
              type: "POST",
              data: values,
              cache: false,
              dataType: 'JSON',
              success: function(response) {
                if (response.status == 'sukses') {
                  form_.data = response.data;
                  if (form_.mode == 'create') {
                    form_.total_bayar = response.data.uang_muka
                  }
                  // console.log(form_)
                } else {
                  alert(response.pesan);
                }
                $('#btnCariRequestDoc').html('<i class="fa fa-search"></i>');
                $('#btnCariRequestDoc').attr('disabled', false);
              },
              error: function() {
                alert("Something Went Wrong !");
                $('#btnCariRequestDoc').html('<i class="fa fa-search"></i>');
                $('#btnCariRequestDoc').attr('disabled', false);
              }
            });
          }
        </script>
      <?php endif ?>
  </section>
</div>