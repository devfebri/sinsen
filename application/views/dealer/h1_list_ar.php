<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">H1</li>
      <li class="">Report</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php
    if ($set == "index") { ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="<?= $folder . '/' . $isi ?>/history" class="btn bg-blue btn-flat">
              <i class="fa fa-list"></i> History
            </a>
          </h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="box box-default box-solid collapsed-box">
                <div class="box-header with-border">
                  <h3 class="box-title">Search</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                  </div>
                  <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-3">
                      <label>No. SO</label>
                      <input type="text" class="form-control" id="id_sales_order" name="id_sales_order">
                      <input type="hidden" id="id_sales_order">
                    </div>
                    <div class="col-sm-3">
                      <label>No. Invoice</label>
                      <input type="text" class="form-control" id="no_invoice" name="no_invoice">
                    </div>
                    <div class="col-sm-3">
                      <label>Nama Customer</label>
                      <input type="text" class="form-control" id="nama_konsumen" name="nama_konsumen">
                    </div>
                    <div class="col-sm-3">
                      <label>Nama Leasing</label>
                      <input type="text" class="form-control" id="finance_company" name="finance_company">
                    </div>
                  </div>
                </div>
                <div class="box-footer" align='center'>
                  <button class='btn btn-flat btn-primary' type="button" onclick="search()"><i class="fa fa-search"></i></button>
                  <button class='btn btn-flat btn-default' type="button" onclick="refresh()"><i class="fa fa-refresh"></i></button>
                  <!-- <button class='btn btn-flat btn-success' type="button" id='btn_download' onclick="downloadExcell()"><i class="fa fa-download"></i> .xls</button> -->
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
          </div>
          <table id="datatable_server" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>No. SO</th>
                <th>Tgl. SO</th>
                <th>No. Invoice</th>
                <th>Nama Customer</th>
                <th>Nama Leasing</th>
                <th>Nilai Invoice</th>
                <th>Sisa Piutang</th>
                <th>Total Pembayaran</th>
                <th>Keterangan Pembayaran</th>
              </tr>
            </thead>
          </table>
          <script>
            function search() {
              $('#datatable_server').DataTable().ajax.reload();
            }

            function refresh() {
              $('#id_sales_order').val('');
              $('#no_invoice').val('');
              $('#nama_konsumen').val('');
              $('#finance_company').val('');
              $('#datatable_server').DataTable().ajax.reload();
            }
            $(document).ready(function() {
              var dataTable = $('#datatable_server').DataTable({
                "processing": true,
                "serverSide": true,
                "scrollX": true,
                "searching": false,
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
                    d.id_sales_order = $('#id_sales_order').val();
                    d.no_invoice = $('#no_invoice').val();
                    d.nama_konsumen = $('#nama_konsumen').val();
                    d.finance_company = $('#finance_company').val();
                  },
                },
                "columnDefs": [{
                    "targets": [6, 7, 8],
                    "orderable": false
                  },
                  {
                    "targets": [5, 6, 7],
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
    <?php } ?>
  </section>
</div>