<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">Penjualan Unit</li>
      <li class="">DP Invoice</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
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
        $(document).ready(function() {
          <?php if (isset($row)) { ?>
            $('#tipe_pesan').val('<?= $row->tipe_pesan ?>').trigger('change');
          <?php } ?>
        })
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/pesan_d">
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
              <form class="form-horizontal" id="form_" action="dealer/pesan_d/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">ID Pesan</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" value="<?= isset($row) ? $row->id_pesan : '' ?>" autocomplete="off" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe Pesan</label>
                    <div class="col-sm-4">
                      <select name="tipe_pesan" id="tipe_pesan" class="form-control select2" <?= $disabled ?>>
                        <option value="">--choose--</option>
                        <option value="Reminder Indent">Reminder Indent</option>
                        <option value="Reminder STNK (Kredit)">Reminder STNK (Kredit)</option>
                        <option value="Reminder STNK (Cash)">Reminder STNK (Cash)</option>
                        <option value="Reminder BASTK">Reminder BASTK</option>
                        <option value="Ucapan Selamat Ulang Tahun">Ucapan Selamat Ulang Tahun</option>
                        <option value="Ucapan Selamat Tahun Baru Masehi">Ucapan Selamat Tahun Baru Masehi</option>
                        <option value="reminder_service">Reminder Servis Via SMS</option>
                        <option value="contact_customer_service">Contact Customer Service Via SMS</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Konten</label>
                    <div class="col-sm-10">
                      <input name="konten" id="konten" class="form-control" :disabled="mode=='detail'" <?= $disabled ?>><?= isset($row) ? $row->konten : '' ?></input>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                    <div class="col-sm-4">
                      <input type="text" name="start_date" required class="form-control datepicker" value="<?= isset($row) ? $row->start_date : '' ?>" autocomplete="off" <?= $disabled ?>>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                    <div class="col-sm-4">
                      <input type="text" name="end_date" required class="form-control datepicker" value="<?= isset($row) ? $row->end_date : '' ?>" autocomplete="off" <?= $disabled ?>>
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div>

                  <div class="box-footer" v-if="mode!='detail'">
                    <div class="col-sm-12" v-if="mode=='insert'||mode=='edit'" align="center">
                      <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    </div>
                  </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->

      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            total_harga: '',
          },
          methods: {
            clearDealers: function() {
              this.dealer = {
                id_dealer: '',
                nama_dealer: ''
              }
            },

          },
        });
      </script>
    <?php
    } elseif ($set == "index") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/pesan_d/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
                <th>ID Pesan</th>
                <th>Tipe Pesan</th>
                <th>Konten</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th width="10%">Action</th>
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
              url: "<?php echo site_url('dealer/pesan_d/fetch'); ?>",
              type: "POST",
              dataSrc: "data",
              data: function(d) {
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
              // { "targets":[6,7],"className":'text-right'}, 
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