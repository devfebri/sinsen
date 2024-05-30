<style type="text/css">
  .myTable1 {
    margin-bottom: 0px;
  }

  .myt {
    margin-top: 0px;
  }

  .isi {
    height: 25px;
    padding-left: 4px;
    padding-right: 4px;
  }
</style>
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
      <li class="">Claim</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>
  <section class="content">
    <?php
    if ($set == "view") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php /* ?> <a href="h2/ptcd/upload">
            <button class="btn btn-info btn-flat margin"><i class="fa fa-upload"></i> Upload</button>
          </a>   <?php */ ?>
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
          <table id="tbl_lbpc_processed" class="table table-hover">
            <thead>
              <tr>
                <th>No. LBPC</th>
                <th>Tanggal LBPC</th>
                <th>Kelompok Pengajuan</th>
                <th>Periode Awal</th>
                <th>Periode Akhir</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
      <script>
        $(document).ready(function() {
          $('#tbl_lbpc_processed').DataTable({
            processing: true,
            serverSide: true,
            "language": {
              "infoFiltered": "",
              "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
            },
            order: [],
            ajax: {
              url: "<?= base_url($folder . '/' . $isi . '/fetch') ?>",
              dataSrc: "data",
              data: function(d) {
                d.ptca_not_null = true;
                return d;
              },
              type: "POST"
            },
            "columnDefs": [{
                "targets": [5],
                "orderable": false
              },
              {
                "targets": [5],
                "className": 'text-center'
              },
              // {
              //   "targets": [5],
              //   "className": 'text-right'
              // },
              // { "targets":[4], "searchable": false } 
            ],
          });
        });
      </script>
    <?php
    }
    ?>
  </section>
</div>