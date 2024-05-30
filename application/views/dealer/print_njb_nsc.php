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
            <?php if ($all_print == 0) { ?>
              <a href="dealer/<?= $isi ?>/history" class="btn bg-blue btn-flat margin"><i class="fa fa-list"></i> History</a>
            <?php } else { ?>
              <a href="dealer/<?= $isi ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
              </a>
            <?php } ?>
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
                <th>No NJB</th>
                <th>No NSC</th>
                <th>Tgl Transaksi</th>
                <th>No. Polisi</th>
                <th>Nama Customer</th>
                <th>Tipe Motor</th>
                <th width='5%'>Aksi</th>
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
                  "searchPlaceholder": "Min. 5 digit untuk cari",
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
                    <?php if ($all_print == 1) { ?>
                      d.sisa_0 = true;
                    <?php } else { ?>
                      d.sisa_lebih_besar = true;
                    <?php } ?>
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

              $(".dataTables_filter input")
                .unbind() // Unbind previous default bindings
                .bind("input", function(e) { // Bind our desired behavior
                  // If the length is 3 or more characters, or the user pressed ENTER, search
                  if(this.value.length >= 5 || e.keyCode == 13) {
                      // Call the API search function
                      dataTable.search(this.value).draw();
                  }
                  // Ensure we clear the search if they backspace far enough
                  if(this.value == "") {
                    dataTable.search("").draw();
                  }
                  return;
              });
                
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php endif ?>
  </section>
</div>