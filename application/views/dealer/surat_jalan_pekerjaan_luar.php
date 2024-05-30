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
      <li class="">Manage Work Order</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php
    if ($set == "index") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php if (can_access($isi, 'can_insert')) : ?>
              <a href="dealer/surat_jalan_pekerjaan_luar/create">
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
                <th>ID Surat Jalan</th>
                <th>Tgl Surat Jalan</th>
                <th>ID Work Order</th>
                <th>ID Vendor</th>
                <th>Vendor</th>
                <th>Nama Customer</th>
                <th>Dibawa Oleh</th>
                <th width="10%">Action</th>
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
                    d.status_wo = 'open';
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  {
                    "targets": [7],
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
    <?php
    } elseif ($set == 'form') {
      $form = '';
      if ($mode == 'create') {
        $form = 'save_surat_jalan';
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
        $(document).ready(function() {
          <?php if (isset($row)) { ?>
            getDataWO(<?= json_encode($row) ?>)
          <?php } ?>
        })
        Vue.filter('toCurrency', function(value) {
          // // console.log("type value ke currency filter" ,  value, typeof value, typeof value !== "number");
          // if (typeof value !== "number") {
          //     return value;
          // }
          return accounting.formatMoney(value, "", 0, ".", ",");
          return value;
        });
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
            <div class="col-md-12">
              <form class="form-horizontal" id="form_" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Work Order</label>
                  <div class="col-sm-3">
                    <input type="text" v-model="row.id_work_order" class="form-control" name="id_work_order" readonly id="id_work_order" required>
                  </div>
                  <div class="col-sm-1">
                    <button v-if="mode=='create'" type="button" onclick="showModalWOProses()" class="btn btn-flat btn-primary" id="btnSearchWO"><i class="fa fa-search"></i></button>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Servis</label>
                  <div class="col-sm-4">
                    <input type="text" v-model="row.tgl_servis" class="form-control" name="tgl_servis" readonly id="tgl_servis">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                  <div class="col-sm-4">
                    <input type="text" v-model="row.id_customer" class="form-control" readonly>
                  </div> <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <input type="text" v-model="row.nama_customer" class="form-control" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No. HP</label>
                  <div class="col-sm-4">
                    <input type="text" v-model="row.no_hp" class="form-control" readonly>
                  </div> <label for="inputEmail3" class="col-sm-2 control-label">E-Mail</label>
                  <div class="col-sm-4">
                    <input type="text" v-model="row.email" class="form-control" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No. Polisi</label>
                  <div class="col-sm-4">
                    <input type="text" v-model="row.no_polisi" class="form-control" name="no_polisi" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No. Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" v-model="row.no_mesin" class="form-control" readonly>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No. Rangka</label>
                  <div class="col-sm-4">
                    <input type="text" v-model="row.no_rangka" class="form-control" name="no_rangka" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Vendor</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="id_vendor" readonly id="id_vendor" value="<?= isset($row) ? $row->id_vendor : '' ?>" required>
                  </div>
                  <div class="col-sm-1">
                    <button v-if="mode=='create'" type="button" onclick="showModalVendorPekerjaanLuar()" class="btn btn-flat btn-primary" id="btnSearchVendorPekerjaanLuar"><i class="fa fa-search"></i></button>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Vendor</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="nama_vendor" readonly id="nama_vendor" value="<?= isset($row) ? $row->nama_vendor : '' ?>" :disabled="mode!='create'">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dibawa Oleh</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="dibawa_oleh" id="dibawa_oleh" value="<?= isset($row) ? $row->dibawa_oleh : '' ?>" required :disabled="mode!='create'">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alasan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="alasan" id="alasan" value="<?= isset($row) ? $row->alasan : '' ?>" :disabled="mode!='create'">
                  </div>
                </div>
                <div class="col-md-12">
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-info btn-flat btn-sm" disabled>Detail Pekerjaan</button><br><br>
                </div>
                <div class="col-md-12">
                  <table class="table table-bordered ">
                    <thead>
                      <th>ID Pekerjaan</th>
                      <th>Deskripsi Pekerjaan</th>
                      <th>Harga</th>
                      <th>Harga Dari Vendor</th>
                      <th v-if="mode=='create'">Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(kr, index) of pekerjaans">
                        <td>{{kr.id_jasa}}</td>
                        <td>{{kr.deskripsi}}</td>
                        <td>{{kr.harga | toCurrency}}</td>
                        <td>{{kr.harga_dari_vendor | toCurrency}}</td>
                        <td align="center" v-if="mode=='create'">
                          <button @click.prevent="delPekerjaans(index)" type="button" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot v-if="mode=='create'">
                      <tr>
                        <td>
                          <input class="form-control" v-model="krj.id_jasa" onclick="showModaljasaWO()" readonly />
                        </td>
                        <td>
                          <input class="form-control" v-model="krj.deskripsi" onclick="showModaljasaWO()" readonly />
                        </td>
                        <td>
                          <input class="form-control" :value="krj.harga | toCurrency" onclick="showModaljasaWO()" readonly />
                        </td>
                        <td>
                          <input class="form-control" v-model="krj.harga_dari_vendor" />
                        </td>
                        <td align="center">
                          <button @click.prevent="addPekerjaans" type="button" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i></button>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="col-md-12">
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Detail Parts Related</button><br><br>
                </div>
                <div class="col-md-12">
                  <table class="table table-bordered ">
                    <thead>
                      <th>ID Parts</th>
                      <th>Deskripsi</th>
                      <th>Qty</th>
                      <th v-if="mode=='create'">Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(pr, index) of parts_related">
                        <td>{{pr.id_part}}</td>
                        <td>{{pr.nama_part}}</td>
                        <td>{{pr.qty}}</td>
                        <td align="center" v-if="mode=='create'">
                          <button @click.prevent="delPartsRelated(index)" type="button" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot v-if="mode=='create'">
                      <tr>
                        <td>
                          <input class="form-control" v-model="prt.id_part" onclick="showModalAllParts()" readonly />
                        </td>
                        <td>
                          <input class="form-control" v-model="prt.nama_part" onclick="showModalAllParts()" readonly />
                        </td>
                        <td>
                          <input class="form-control" v-model="prt.qty" />
                        </td>
                        <td align="center">
                          <button @click.prevent="addPartsRelated" type="button" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i></button>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="col-sm-12" align="center" v-if="mode=='create'">
                  <button type="button" id="submitBtn" @click.prevent="saveSJ" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php
      $data['data'] = [
        'modalVendorPekerjaanLuar', 'filter_vendor_by_jasa_wo', 'WOProses', 'wo_pekerjaan_luar', 'modalJasaWO', 'jasa_pekerjaan_luar', 'modalAllParts'
      ];
      $this->load->view('dealer/h2_api', $data); ?>
      <script>
        var eta = 0;
        var form_ = new Vue({
          el: '#form_',
          data: {
            kosong: '',
            mode: '<?= $mode ?>',
            details: [],
            krj: {
              id_jasa: '',
              deskripsi: '',
              harga: 0,
              harga_dari_vendor: 0
            },
            pekerjaans: <?= isset($pekerjaans) ? json_encode($pekerjaans) : '[]' ?>,
            parts_related: <?= isset($parts_related) ? json_encode($parts_related) : '[]' ?>,
            row: <?= isset($row) ? json_encode($row) : '{}' ?>,
            prt: {
              id_part: '',
              nama_part: '',
              qty: ''
            },
          },
          methods: {
            clearKrj: function() {
              this.krj = {
                id_jasa: '',
                deskripsi: '',
                harga: 0,
                harga_dari_vendor: ''
              }
            },
            addPekerjaans: function() {
              if (this.row.id_work_order == undefined) {
                alert('Data Work order belum dipilih !');
                return false;
              }
              if (parseInt(this.krj.harga) < (parseInt(this.krj.harga_dari_vendor))) {
                alert('Harga dari vendor melebihi harga jasa. Silahkan lakukan penyesuaian harga !');
                return false;
              }
              // if (parseInt(this.krj.harga) > (parseInt(this.krj.harga_dari_vendor))) {
              //   alert('Harga dari vendor lebih kecil dari harga jasa. Silahkan lakukan penyesuaian harga !');
              //   return false;
              // }
              if (parseInt(this.krj.harga_dari_vendor) == 0 || this.krj.harga_dari_vendor === undefined || this.krj.harga_dari_vendor === '') {
                alert('Silahkan tentukan harga dari vendor !');
                return false;
              }
              let vendor = $('#id_vendor').val();
              if (vendor == '') {
                alert('Data vendor belum dipilih !');
                return false;
              }
              if (this.krj.id_jasa == '') {
                alert('Data pekerjaan belum dipilih !');
                return false
              }
              this.pekerjaans.push(this.krj);
              this.clearKrj();
            },
            delPekerjaans: function(index) {
              this.pekerjaans.splice(index, 1);
            },
            clearPart: function() {
              this.prt = {
                id_part: '',
                nama_part: '',
                qty: ''
              }
            },
            addPartsRelated: function() {
              if (this.row.id_work_order == undefined) {
                alert('Data Work order belum dipilih !');
                return false;
              }
              let vendor = $('#id_vendor').val();
              if (vendor == '') {
                alert('Data vendor belum dipilih !');
                return false;
              }
              if (this.prt.id_part == '') {
                alert('Data part belum dipilih !');
                return false
              }
              this.parts_related.push(this.prt);
              this.clearPart();
            },
            delPartsRelated: function(index) {
              this.parts_related.splice(index, 1);
            },
            saveSJ: function() {
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
                  pekerjaans: form_.pekerjaans,
                  parts_related: form_.parts_related
                };
                var form = $('#form_').serializeArray();
                for (field of form) {
                  values[field.name] = field.value;
                }
                if (confirm("Apakah anda yakin ?") == true) {
                  $.ajax({
                    beforeSend: function() {
                      $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                      $('#submitBtn').attr('disabled', true);
                    },
                    url: '<?= base_url('dealer/surat_jalan_pekerjaan_luar/' . $form) ?>',
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
          },
          watch: {}
        });

        function modalCreateSJ() {
          $('#modalCreateSJ').modal('show');
        }

        function pilihVendor(vdr) {
          $('#id_vendor').val(vdr.id_vendor);
          $('#nama_vendor').val(vdr.nama_vendor);
        }

        function pilihJasaWO(js) {
          form_.krj = {
            id_jasa: js.id_jasa,
            deskripsi: js.deskripsi,
            harga: js.harga,
            harga_dari_vendor: ''
          }
        }

        function pilihAllPart(prt) {
          form_.prt = {
            id_part: prt.id_part,
            nama_part: prt.nama_part,
            qty: ''
          }
        }

        function pilihWO(wo) {
          getDataWO(wo);
        }

        function getDataWO(wo) {

          let values = {
            id_sa_form: wo.id_sa_form
          }
          $.ajax({
            beforeSend: function() {
              $('#btnSearchWO').attr('disabled', true);
              $('#btnSearchWO').html('<i class="fa fa-spinner fa-spin">');
            },
            url: '<?= base_url('dealer/work_order_dealer/getDataWO') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              if (response.status == 'sukses') {
                form_.row = response.sa;
              }
              $('#btnSearchWO').attr('disabled', false);
              $('#btnSearchWO').html('<i class="fa fa-search">');
            },
            error: function() {
              alert("Something Went Wrong !");
              $('#btnSearchWO').attr('disabled', false);
              $('#btnSearchWO').html('<i class="fa fa-search">');
            }
          });
        }
      </script>
    <?php } ?>
  </section>
</div>