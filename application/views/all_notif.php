<!-- <body onload="metode_a()"> -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">General</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title"><?= $title ?></h3>
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
              <table id="tbl_notifikasi" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Notifikasi</th>
                    <th>Tanggal & Waktu</th>
                    <th width="10%">Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div><!-- /.box-body -->

            <script>
              $(document).ready(function() {
                $('#tbl_notifikasi').DataTable({
                  processing: true,
                  serverSide: true,
                  "language": {
                    "infoFiltered": "",
                    "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                  },
                  order: [],
                  ajax: {
                    url: "<?= base_url('panel/fetch_all_notif') ?>",
                    dataSrc: "data",
                    data: function(d) {},
                    type: "POST"
                  },
                  "columnDefs": [{
                      "targets": [2],
                      "orderable": false
                    },
                    {
                      "targets": [2],
                      "className": 'text-center'
                    }
                  ],
                });
              });
            </script>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
  </section>
</div>