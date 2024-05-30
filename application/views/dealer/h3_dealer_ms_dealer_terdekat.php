<base href="<?php echo base_url(); ?>" />
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
</section>
<section class="content">
<?php
  if ($set=="form") {
      $form     = '';
      $disabled = '';
      $readonly ='';
      if ($mode=='insert') {
        $form = 'save';
      }
      if ($mode=='detail') {
        $disabled = 'disabled';
      }
      if ($mode=='edit') {
          $form = 'update';
      } 
?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php $this->load->view('template/normal_session_message', [
          'pesan_key' => 'pesan_' . $isi,
          'tipe_key' => 'tipe_' . $isi,
        ]); ?>
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" id="form_" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer terdekat</label>
                  <div class="col-sm-4">
                    <div class="input-group">
                      <input v-model='dealer_terdekat.nama_dealer_terdekat' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#modal_dealer_terdekat'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_dealer_dealer_terdekat'); ?>
                <script>
                  function pilih_dealer_terdekat(dealer){
                    form_.dealer_terdekat.id_dealer_terdekat = dealer.id_dealer;
                    form_.dealer_terdekat.nama_dealer_terdekat = dealer.nama_dealer;
                    form_.dealer_terdekat.kode_dealer_md_terdekat = dealer.kode_dealer_md;
                    form_.dealer_terdekat.alamat_terdekat = dealer.alamat;
                    form_.dealer_terdekat.no_telp_terdekat = dealer.no_telp;
                  }
                </script>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer</label>
                  <div class="col-sm-4">
                    <input name="id_dealer_terdekat" type="hidden" v-model="dealer_terdekat.id_dealer_terdekat"> 
                    <input type="text" class="form-control" :disabled="mode=='detail'" disabled v-model="dealer_terdekat.kode_dealer_md_terdekat"> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-4">
                    <textarea disabled v-model="dealer_terdekat.alamat_terdekat" cols="30" rows="10" class="form-control"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor Telepon</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" :disabled="mode=='detail'" disabled v-model="dealer_terdekat.no_telp_terdekat"> 
                  </div>
                </div>
              <div class="box-footer">
                <div class="col-sm-6">
                  <button v-if='mode == "insert"' type="submit" class="btn btn-info btn-sm btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <a v-if='mode == "detail"' onclick='return confirm("Apakah anda yakin ingin menghapus dealer ini dari daftar dealer terdekat?");' :href="'dealer/<?= $isi ?>/delete?k=' + dealer_terdekat.id"><button type="button" class="btn btn-sm btn-danger btn-flat">Hapus</button></a>
                </div>   
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        kosong :'',
        mode : '<?= $mode ?>',
        <?php if($mode == 'detail'): ?>
          dealer_terdekat: <?= json_encode($dealer_terdekat) ?>
        <?php else: ?>
          dealer_terdekat: {
            id_dealer_terdekat: 0,
            nama_dealer_terdekat: '',
            kode_dealer_md_terdekat: '',
            alamat_terdekat: '',
            no_telp_terdekat: '',
          }
        <?php endif; ?>
      }
  });
</script>
    <?php
  } elseif ($set=="index") {
      ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php $this->load->view('template/normal_session_message', [
          'pesan_key' => 'pesan_' . $isi,
          'tipe_key' => 'tipe_' . $isi,
        ]); ?>
        <table id="dealer_terdekat" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Dealer</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function() {
          dealer_terdekat = $('#dealer_terdekat').DataTable({
              processing: true,
              serverSide: true,
              order: [],
              ajax: {
                  url: "<?= base_url('api/dealer/dealer_terdekat') ?>",
                  dataSrc: "data",
                  type: "POST",
              },
              columns: [
                { data: 'index', width: '2%', orderable: false },
                { 
                  data: 'nama_dealer',
                  render: function(data, type, row){
                    return "<a href='dealer/<?= $isi ?>/detail?k="+ row.id +"'>"+ row.nama_dealer + " - " + row.kode_dealer_md  +"</a>";
                  }
                },
              ],
          });
        });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<?php } ?>
  </section>
</div>