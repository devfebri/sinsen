<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" />
<body onload="auto()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
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
        $form = 'create';
        $disabled = 'disabled';
      }
      if ($mode == 'edit') {
        $form = 'update';
      }
    ?>
    <div id='app' class="box box-default">
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
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No Picking List</label>
                  <div class="col-sm-4">
                    <input v-model="picking.id_picking_list" readonly type="text" class="form-control">
                  </div>       
                  <label class="col-sm-2 control-label">Nama Picker</label>
                  <div class="col-sm-4">
                    <input v-model="picking.nama_picker" readonly type="text" class="form-control">
                  </div> 
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal Picking List</label>
                  <div class="col-sm-4">
                    <input v-model="picking.tanggal_picking" readonly type="text" class="form-control">
                  </div>       
                  <label class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <input v-model="picking.nama_dealer" readonly type="text" class="form-control">
                  </div> 
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Jenis PO</label>
                  <div class="col-sm-4">
                    <input v-model="picking.po_type" readonly type="text" class="form-control">
                  </div>       
                  <label class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">
                    <input v-model="picking.alamat" readonly type="text" class="form-control">
                  </div> 
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">No SO</label>
                  <div class="col-sm-4">
                    <input v-model="picking.id_sales_order" readonly type="text" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">Tanggal SO</label>
                  <div class="col-sm-4">
                    <input v-model="picking.tanggal_so" readonly type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Nomor DO</label>
                  <div class="col-sm-4">
                    <input v-model="picking.id_do_sales_order" readonly type="text" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">Tanggal DO</label>
                  <div class="col-sm-4">
                    <input v-model="picking.tanggal_do" readonly type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-condensed table-responsive">
                      <thead>
                        <tr class='bg-blue-gradient'>                                      
                          <th width='3%'>No.</th>              
                          <th>ID Part</th>              
                          <th>Nama Part</th>              
                          <th>Qty DO</th>              
                          <th>Qty AVS</th>              
                          <th>Qty Picking</th>              
                          <th>Qty Disiapkan</th>              
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts"> 
                          <td class="align-middle">{{ index + 1 }}.</td>
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>            
                          <td class="align-middle">
                            <vue-numeric :read-only='true' class="form-control" separator='.' v-model='part.qty_do'></vue-numeric>
                          </td>            
                          <td class="align-middle">
                            <vue-numeric :read-only='true' class="form-control" separator='.' v-model='part.qty_avs'></vue-numeric>
                          </td>
                          <td class="align-middle">
                            <vue-numeric :read-only='true' class="form-control" separator='.' v-model='part.qty_picking'></vue-numeric>
                          </td>
                          <td class="align-middle">
                            <vue-numeric :read-only='true' class="form-control" separator='.' v-model='part.qty_disiapkan'></vue-numeric>
                          </td>         
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>                                                                                                                                
              </div><!-- /.box-body -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <script>
      var app = new Vue({
          el: '#app',
          data: {
            loading: false,
            mode: '<?= $mode ?>',
            picking: <?= json_encode($picking) ?>,
            parts: <?= json_encode($parts) ?>,
          },
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
    <?php if($this->input->get('history') != null): ?>
              <a href="h3/<?= $isi ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
              </a>  
              <?php else: ?>
              <a href="h3/<?= $isi ?>?history=true">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
              </a> 
      <?php endif; ?>
      <div class="box-body">
        <table id="picking_list" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Tgl Picking List</th>              
              <th>No Picking List</th>              
              <th>Referensi</th>              
              <th>Nama Customer</th>              
              <th>Alamat Customer</th>              
              <!-- <th>Total Item</th>              
              <th>Total Pcs</th>               -->
              <th>Nama Picker</th>     
              <th>Status</th>     
              <th>Action</th>         
            </tr>
          </thead>
          <tbody>    
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <script>
      $(document).ready(function(){
        picking_list = $('#picking_list').DataTable({
          processing: true,
          serverSide: true,
          order: [],
          ajax: {
              url: "<?= base_url('api/md/h3/picking_list') ?>",
              dataSrc: "data",
              type: "POST",
              data: function(d){
                d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
              }
          },
          columns: [
            { data: null, orderable: false, width: '3%' },
            { data: 'tanggal' },
            { data: 'id_picking_list' },
            { data: 'id_ref' },
            { data: 'nama_dealer' },
            { data: 'alamat' },
            // { data: 'total_item' },
            // { data: 'total_pcs' },
            { data: 'nama_picker' },
            { data: 'status' },
            { data: 'action', width: '3%', orderable:false, className: 'text-center' }
          ],
        });

        picking_list.on('draw.dt', function() {
          var info = picking_list.page.info();
          picking_list.column(0, {
              search: 'applied',
              order: 'applied',
              page: 'applied'
          }).nodes().each(function(cell, i) {
              cell.innerHTML = i + 1 + info.start + ".";
          });
        });
      });
    </script>
    <?php endif; ?>
  </section>
</div>