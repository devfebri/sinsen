<base href="<?php echo base_url(); ?>" /> 
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
  </section>
  <section class="content">
    <?php if($set == 'form'): ?>
    <?php 
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'terima_claim') {
        $form = 'simpan_claim';
      }
      if ($mode == 'detail') {
        $form = 'detail';
        $disabled = 'disabled';
      }
      if ($mode == 'edit') {
        $form = 'update';
      }
    ?>

    <?php 
      if($mode == 'insert'):
        $this->load->view('h3/h3_md_jawaban_claim_dealer_create', [
          'form' => $form
        ]);
      elseif($mode == 'detail'):
        $this->load->view('h3/h3_md_jawaban_claim_dealer_detail', [
          'form' => $form
        ]);
      elseif($mode == 'proses'):
          $this->load->view('h3/h3_md_jawaban_claim_dealer_proses', [
            'form' => $form, 
          ]);
      endif;
    ?>

    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
          <?php if($this->input->get('history') != null): ?>
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
          </a>  
          <?php else: ?>
          <a href="h3/<?= $isi ?>?history=true">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
          </a> 
          <?php endif; ?>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="jawaban_claim_dealer" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>No Jawaban Claim</th>              
              <th>No Claim MD Ke AHM</th>              
              <th>Tgl Surat Jawaban AHM</th>              
              <th>No Surat Jawaban AHM</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          jawaban_claim_dealer = $('#jawaban_claim_dealer').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            scrollX: true,
            ajax: {
                url: "<?= base_url('api/md/h3/jawaban_claim_dealer') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                  d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                }
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' },
                { data: 'id_jawaban_claim_dealer' }, 
                { data: 'id_claim_part_ahass' }, 
                { data: 'created_at' }, 
                { data: 'no_surat_jalan_ahm' }, 
                { data: 'action', orderable: false, width: '3%', className: 'text-center' }
            ],
          });
        });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>
