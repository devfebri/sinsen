<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script>
  Vue.use(VueNumeric.default);
</script>
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
    <div id="app" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>  
        </h3>
      </div><!-- /.box-header -->
      <div v-if="loading" class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
        <?php $this->load->view('template/session_message.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No. Surat Pengantar</label>
                  <div class="col-sm-4">                    
                    <input v-model="surat_pengantar.id_surat_pengantar" readonly type="text" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">Tanggal Surat Pengantar</label>
                  <div class="col-sm-4">                    
                    <input v-model="surat_pengantar.tanggal" readonly type="text" class="form-control">
                  </div> 
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No. Jawaban Claim Dealer</label>
                  <div class="col-sm-4">                    
                    <input v-model="surat_pengantar.id_jawaban_claim_dealer" readonly type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-responsive">
                      <thead>
                        <tr>                                      
                          <th class='align-top' width='3%'>No.</th>              
                          <th class='align-top'>Kode Part</th>              
                          <th class='align-top'>Nama Part</th>              
                          <th class='align-top' width="10%">Qty</th>
                          <th class='align-top'>No. Claim Dealer</th>
                          <th class='align-top'>No. Faktur Dealer</th>
                          <th class='align-top'>Keterangan</th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts">
                          <td class="align-middle">{{ index + 1 }}</td>                       
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>            
                          <td class="align-middle">
                            <vue-numeric class="form-control" :read-only='true' separator="." :empty-value="1" v-model="part.qty_ganti_barang"/>
                          </td>      
                          <td class="align-middle">{{ part.id_claim_dealer }}</td>            
                          <td class="align-middle">{{ part.no_faktur }}</td>            
                          <td class="align-middle">{{ part.keterangan }}</td>            
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="7">Belum ada part</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>                                                                                                                                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="row">
                  <div class="col-sm-4"></div>
                  <div class="col-sm-4"></div>
                  <div class="col-sm-4 text-right">
                    <a :href="'h3/h3_md_surat_pengantar_claim_c3/cetak?id_surat_pengantar=' + surat_pengantar.id_surat_pengantar" class="btn btn-flat btn-sm btn-info">Cetak</a>
                  </div>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <script>
      var app = new Vue({
          el: '#app',
          data: {
            errors: {},
            loading: false,
            mode: '<?= $mode ?>',
            index_part: 0,
            surat_pengantar: <?= json_encode($surat_pengantar) ?>,
            parts: <?= json_encode($parts) ?>,
          },
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-body">
        <table id="surat_pengantar_claim_c3" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>No. Surat Pengantar</th>              
              <th>Tgl Surat Pengantar</th>              
              <th>Kode Customer</th>              
              <th>Nama Customer</th>              
              <th>No. Jawaban Claim Dealer</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          surat_pengantar_claim_c3 = $('#surat_pengantar_claim_c3').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            scrollX: true,
            ajax: {
                url: "<?= base_url('api/md/h3/surat_pengantar_claim_c3') ?>",
                dataSrc: "data",
                type: "POST",
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' },
                { data: 'id_surat_pengantar' }, 
                { data: 'tanggal' }, 
                { data: 'kode_dealer_md' }, 
                { data: 'nama_dealer' }, 
                { data: 'id_jawaban_claim_dealer' }, 
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
