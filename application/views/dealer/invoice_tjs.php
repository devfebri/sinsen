<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">Pembayaran</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $title)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php
    if ($set == "form") {
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'edit') {
        // $readonly ='readonly';
        $form = 'save_edit';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

      <script>
        Vue.use(VueNumeric.default);
        Vue.filter('toCurrency', function(value) {
          // return "Rp. " + accounting.formatMoney(value, "", 0, ".", ",");
          return accounting.formatMoney(value, "", 0, ".", ",");
        });
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/invoice_tjs">
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
              <form class="form-horizontal" id="form_" action="dealer/invoice_tjs/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group" v-if="mode=='detail'">
                    <label for="inputEmail3" class="col-sm-2 control-label">ID Invoice TJS</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" value="<?= isset($row) ? $row->id_invoice : '' ?>" autocomplete="off" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Creation Date</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" value="<?= isset($row) ? $row->created_at : '' ?>" autocomplete="off" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">No. SPK</label>
                      <div class="col-sm-4">
                        <input type="text" required class="form-control" name="no_spk" v-model="row.no_spk" autocomplete="off" readonly placeholder='Klik Untuk Memilih Data' onclick="showModalSPK()">
                      </div>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl. SPK</label>
                      <div class="col-sm-4">
                        <input type="text" required class="form-control" name="tgl_spk" v-model="row.tgl_spk" autocomplete="off" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Sales People ID</label></label>
                    <div class="col-sm-4">
                      <input type="hidden" v-model="row.id_karyawan_dealer" name="id_karyawan_dealer">
                      <input type="text" required class="form-control" name="id_flp_md" v-model="row.id_flp_md" autocomplete="off" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Sales People ID</label></label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" name="nama_lengkap" v-model="row.nama_lengkap" autocomplete="off" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Pelanggan</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" name="nama_konsumen" v-model="row.nama_konsumen" autocomplete="off" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" name="no_ktp" v-model="row.no_ktp" autocomplete="off" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe Pembayaran</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" name="jenis_beli" v-model="row.jenis_beli" autocomplete="off" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanda Jadi</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" :value="row.tanda_jadi | toCurrency" autocomplete="off" readonly>
                    </div>
                  </div>
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-info btn-flat btn-sm" disabled>Detail Kendaraan</button><br><br>
                  <table class="table table-bordered table-hover table-condensed table-stripped">
                    <thead>
                      <th>No.</th>
                      <th>Kode Tipe Unit</th>
                      <th>Deskripsi Unit</th>
                      <th>Kode Warna</th>
                      <th>Deskripsi Warna</th>
                      <th>Harga</th>
                      <th>Tot. Diskon</th>
                      <th>Qty</th>
                      <th>DP</th>
                      <th>Total Harga</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dt, index) of details">
                        <td>{{index+1}}</td>
                        <td>{{dt.id_tipe_kendaraan}}</td>
                        <td>{{dt.tipe_ahm}}</td>
                        <td>{{dt.id_warna}}</td>
                        <td>{{dt.warna}}</td>
                        <td align='right'>{{dt.harga==null?dt.total_bayar-dt.diskon:dt.harga | toCurrency}}</td>
                        <td align='right'>{{dt.diskon | toCurrency}}</td>
                        <td align='right'>{{dt.qty==null?1:dt.qty}}</td>
                        <td align='right'>{{dt.dp_stor | toCurrency}}</td>
                        <td align='right'>{{dt.total_bayar | toCurrency}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                <div>

                  <div class="box-footer" v-if="mode!='detail'">
                    <div class="col-sm-12" v-if="mode=='insert'||mode=='edit'" align="center">
                      <button type="button" id="submitBtn" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    </div>
                  </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <?php
      $data['data'] = ['modalSPK', 'spk_ada_tanda_jadi', 'id_tjs_null'];
      $this->load->view('dealer/h1_dealer_api', $data); ?>
      <script>
        function pilihSPK(params) {
          values = {
            no_spk: params.no_spk,
            jenis_spk: params.jenis_spk
          }
          $.ajax({
            beforeSend: function() {},
            // url: '<?= base_url('api/h1_dealer/getSPKDetail') ?>',
            url: '<?= base_url('api/h1_dealer/getSPKHeaderDetail') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              if (response.status == 'sukses') {
                form_.row = response.row;
                form_.details = response.data;
              } else {
                alert(response.pesan);
                // $('#submitBtn').attr('disabled', false);
              }
              // $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
            },
            error: function() {
              alert("Something Went Wrong !");
              // $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
              // $('#submitBtn').attr('disabled', false);
            }
          });
        }
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            row: <?= isset($row) ? json_encode($row) : '{}' ?>,
            details: <?= isset($details) ? json_encode($details) : '[]' ?>,

          },
          methods: {
            clearDealers: function() {
              this.dealer = {
                id_dealer: '',
                nama_dealer: ''
              }
            },
            addDealers: function() {
              if (this.dealers.length > 0) {
                for (dl of this.dealers) {
                  if (dl.id_dealer === this.dealer.id_dealer) {
                    alert("Dealer Sudah Dipilih !");
                    this.clearDealers();
                    return;
                  }
                }
              }
              if (this.dealer.id_dealer == '') {
                alert('Pilih Dealer !');
                return false;
              }
              this.dealers.push(this.dealer);
              this.clearDealers();
            },

            delDealers: function(index) {
              this.dealers.splice(index, 1);
            },
            getDealer: function() {
              var el = $('#dealer').find('option:selected');
              var id_dealer = el.attr("id_dealer");
              form_.dealer.id_dealer = id_dealer;
            },
          },
        });

        $('#submitBtn').click(function() {
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
          var values = {
            tanda_jadi: form_.row.tanda_jadi
          };
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          if ($('#form_').valid()) // check if form is valid
          {
            if (confirm("Apakah anda yakin ?") == true) {
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('#submitBtn').attr('disabled', true);
                },
                url: '<?= base_url('dealer/' . $isi . '/' . $form) ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    window.location = response.link;
                  } else {
                    alert(response.pesan);
                    $('#submitBtn').attr('disabled', false);
                  }
                  $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                },
                error: function() {
                  alert("Something Went Wrong !");
                  $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                  $('#submitBtn').attr('disabled', false);

                }
              });
            } else {
              return false;
            }
          } else {
            alert('Silahkan isi field required !')
          }
        })
      </script>
    <?php
    } elseif ($set == "index") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/invoice_tjs/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
            </a>
            <a href="dealer/invoice_tjs/history">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-list"></i> History</button>
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
          <table id="datatable_server" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID Invoice TJS</th>
                <th>ID SPK</th>
                <th>Sales People ID</th>
                <th>Nama Pelanggan</th>
                <th>No KTP</th>
                <th>Tipe Pembayaran</th>
                <th>Creation Date</th>
                <th>Amount</th>
                <!-- <th width="10%">Action</th> -->
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
              url: "<?php echo site_url('dealer/invoice_tjs/fetch'); ?>",
              type: "POST",
              dataSrc: "data",
              data: function(d) {
		d.type = 'index';
                d.print_ke_nol = true;

                // d.start_date = $('#start_date').val();
                // d.end_date = $('#end_date').val();
                return d;
              },
            },
            "columnDefs": [
              // { "targets":[2],"orderable":false},
              // {
              //   "targets": [8],
              //   "className": 'text-center'
              // },
              // // { "targets":[0],"checkboxes":{'selectRow':true}}
              {
                "targets": [7],
                "className": 'text-right'
              },
              // // { "targets":[2,4,5], "searchable": false } 
            ],
          });
        });
      </script>
    <?php
    } elseif ($set == "history") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/invoice_tjs">
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
          <table id="datatable_server" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID Invoice TJS</th>
                <th>ID SPK</th>
                <th>Sales People ID</th>
                <th>Nama Pelanggan</th>
                <th>No KTP</th>
                <th>Tipe Pembayaran</th>
                <th>Creation Date</th>
                <th>Amount</th>
                <!-- <th width="10%">Action</th> -->
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
              url: "<?php echo site_url('dealer/invoice_tjs/fetch'); ?>",
              type: "POST",
              dataSrc: "data",
              data: function(d) {
                // d.start_date = $('#start_date').val();
		d.type = 'history';
                d.print_ke_diatas_nol = true;
                return d;
              },
            },
            "columnDefs": [
              // { "targets":[2],"orderable":false},
              // {
              //   "targets": [8],
              //   "className": 'text-center'
              // },
              // // { "targets":[0],"checkboxes":{'selectRow':true}}
              {
                "targets": [7],
                "className": 'text-right'
              },
              // // { "targets":[2,4,5], "searchable": false } 
            ],
          });
        });
      </script>
    <?php
    }
    ?>
  </section>
</div>