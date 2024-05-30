<base href="<?php echo base_url(); ?>" /> 
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
  </section>
  <section class="content">
    <div class="box">
      <div class="box-body">
        <table id="monitoring_proses_claim_customer" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>No Claim Customer</th>              
              <th>Tanggal Claim Customer</th>              
              <th>Kode Customer</th>              
              <th>Nama Customer</th>              
              <th>No. Jawaban Claim Dealer</th>              
              <th>Kode Part</th>              
              <th>Nama Part</th>              
              <th>Qty Claim AHASS</th>              
              <th>Qty Kirim Ke AHASS</th>              
              <th>Status</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          monitoring_proses_claim_customer = $('#monitoring_proses_claim_customer').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            scrollX: true,
            ajax: {
                url: "<?= base_url('api/md/h3/monitoring_proses_claim_customer') ?>",
                dataSrc: "data",
                type: "POST"
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' },
                { data: 'id_claim_dealer', width: '180px' }, 
                { data: 'tanggal_claim_customer' }, 
                { data: 'kode_dealer_md' }, 
                { data: 'nama_dealer', width: '250px' }, 
                { data: 'id_jawaban_claim_dealer' }, 
                { data: 'id_part' }, 
                { data: 'nama_part', width: '200px' }, 
                { data: 'qty_part_diclaim' }, 
                { data: 'qty_pergantian' }, 
                { data: 'tipe_pergantian', width: '80px', orderable: false }, 
            ],
          });
        });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </section>
</div>
