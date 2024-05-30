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
              <a href="dealer/<?= $isi ?>/add">
                <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
              </a>
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
                <th>ID Work Order</th>
                <th>ID SA Form</th>
                <th>Tgl. Servis</th>
                <th>Jenis Customer</th>
                <th>No. Polisi</th>
                <th>Nama Customer</th>
                <th>No. Mesin</th>
                <th>No. Rangka</th>
                <th>Tipe Motor</th>
                <th>Warna</th>
                <th>Tahun Motor</th>
                <th>Status WO</th>
                <th width="10%">Action</th>
              </tr>
            </thead>
            <tbody></tbody>
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
                    d.status_wo = "open,pause,pending";
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  {
                    "targets": [12],
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
    } elseif ($set == "history") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $isi ?>">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
            </a>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="datatable_server" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID Work Order</th>
                <th>ID SA Form</th>
                <th>Tgl. Servis</th>
                <th>Jenis Customer</th>
                <th>No. Polisi</th>
                <th>Nama Customer</th>
                <th>No. Mesin</th>
                <th>No. Rangka</th>
                <th>Tipe Motor</th>
                <th>Warna</th>
                <th>Tahun Motor</th>
                <th>Status WO</th>
                <th width="10%">Action</th>
              </tr>
            </thead>
            <tbody></tbody>
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
                  url: "<?php echo site_url('dealer/' . $isi . '/fetchHistory'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    d.status_wo = 'closed,canceled';
                    d.history = true;
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  {
                    "targets": [12],
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
    } elseif ($set == 'surat_jalan') {
      $form = '';
      if ($mode == 'surat_jalan') {
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
        $(document).ready(function() {})
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
                  <div class="col-sm-4">
                    <input type="text" value="<?= $row->id_work_order ?>" class="form-control" name="id_work_order" readonly id="id_work_order">
                    <input type="hidden" value="<?= $row->id_sa_form ?>" class="form-control" name="id_sa_form" readonly id="id_sa_form">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Servis</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?= $row->tgl_servis ?>" class="form-control" name="tgl_servis" readonly id="tgl_servis">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?= $row->id_customer ?>" class="form-control" name="id_customer" readonly>
                  </div> <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?= $row->nama_customer ?>" class="form-control" name="nama_customer" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No. HP</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?= $row->no_hp ?>" class="form-control" name="no_hp" readonly>
                  </div> <label for="inputEmail3" class="col-sm-2 control-label">E-Mail</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?= $row->email ?>" class="form-control" name="nama_customer" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No. Polisi</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?= $row->no_polisi ?>" class="form-control" name="no_polisi" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No. Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?= $row->no_mesin ?>" class="form-control" readonly>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No. Rangka</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?= $row->no_rangka ?>" class="form-control" name="no_rangka" readonly>
                  </div>
                </div>
                <div class="col-md-12">
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Surat Jalan</button><br><br>
                </div>
                <div class="col-sm-12" style="padding-bottom:10px">
                  <button type="button" onclick="modalCreateSJ()" style="font-size: 11pt;font-weight: 540" class="btn btn-primary btn-flat btn-sm">Create</button>
                </div>
                <div class="col-md-12">
                  <table class="table table-bordered ">
                    <thead>
                      <th>ID Surat Jalan</th>
                      <th>ID Vendor</th>
                      <th>Nama Vendor</th>
                      <th>Jumlah Jasa</th>
                      <th>Total Biaya</th>
                      <th>Aksi</th>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalCreateSJ">
        <div class="modal-dialog" style="width:80%">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel"><b>Create Surat Jalan Pekerjaan Luar</b></h4>
            </div>
            <div class="modal-body">
              <form class=" form-horizontal" id="frm_sj">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">ID Work Order</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly value="<?= $row->id_work_order ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Vendor</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly id="id_vendor" name="id_vendor" required>
                    </div>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly id="nama_vendor" required>
                    </div>
                    <div class="col-sm-1">
                      <button type="button" onclick="showModalVendorPekerjaanLuar()" class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Dibawa Oleh</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="dibawa_oleh" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alasan</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="alasan" required>
                    </div>
                  </div>
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-primary btn-flat btn-sm" disabled>Detail Pekerjaan</button><br><br>
                  <div class="col-sm-12">
                    <table class="table table-bordered table-condensed">
                      <thead>
                        <th>ID Jasa/Pekerjaan</th>
                        <th>Deskripsi</th>
                        <th>Harga Dari Vendor</th>
                      </thead>
                    </table>
                  </div>
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-info btn-flat btn-sm" disabled>Detail Parts Related</button><br><br>
                  <div class="col-sm-12">
                    <table class="table table-bordered table-condensed">
                      <thead>
                        <th>ID Part</th>
                        <th>Nama Part</th>
                        <th>Qty</th>
                      </thead>
                    </table>
                  </div>
                </div>
                <div class="modal-footer">
                  <div class="col-sm-12" align="center">
                    <button type="button" onclick="simpanSJ()" class="btn btn-flat btn-info">Simpan</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php
      $data['data'] = ['modalVendorPekerjaanLuar', 'filter_vendor_by_jasa_wo'];
      $this->load->view('dealer/h2_api', $data); ?>
      <script>
        var eta = 0;
        var form_ = new Vue({
          el: '#form_',
          data: {
            kosong: '',
            mode: '<?= $mode ?>',
            details: [],
          },
          methods: {

          },
          watch: {}
        });

        var frm_sj = new Vue({
          el: '#frm_sj',
          data: {
            kosong: '',
            mode: '<?= $mode ?>',
            details: [],
          },
          methods: {

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
      </script>
    <?php } ?>
  </section>
</div>