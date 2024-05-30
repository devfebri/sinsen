
<base href="<?php echo base_url(); ?>" /> 
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
</section>
  <section class="content">
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-body">
        <table id="monitor_file_transfer" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Kode Dealer</th>              
              <th>Nama Dealer</th>              
              <th>Alamat</th>              
              <th>Tanggal dan Waktu upload file stok</th>              
              <th>Tanggal dan waktu upload file sales</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
          $(document).ready(function() {
            monitor_file_transfer = $('#monitor_file_transfer').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "<?= base_url('api/md/h3/monitor_file_transfer') ?>",
                    dataSrc: "data",
                    type: "POST",
                },
                columns: [
                    { data: null, orderable: false, width: '3%' }, 
                    { data: 'kode_dealer_md' },
                    { data: 'nama_dealer' },
                    { data: 'alamat' },
                    { data: 'tanggal_upload_stok' },
                    { data: 'tanggal_upload_sales' },
                ],
            });
            
            monitor_file_transfer.on('draw.dt', function() {
              var info = monitor_file_transfer.page.info();
              monitor_file_transfer.column(0, {
                  search: 'applied',
                  order: 'applied',
                  page: 'applied'
              }).nodes().each(function(cell, i) {
                  cell.innerHTML = i + 1 + info.start + ".";
              });
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>