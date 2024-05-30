<div class="box">
   <div class="box-header with-border">
      <div class="col-sm-6">
         <h3 class="box-title">
            <a href="h3/<?= $isi ?>/download_insentif_poin?id=<?= $sales_campaign['id'] ?>">
            <button class="btn btn-success btn-flat">Download Laporan</button>
            </a>
         </h3>
      </div>
      <div class="col-sm-6 text-right">
        <?php if($sales_campaign['proses_ke_finance'] == 0): ?>
          <h3 class="box-title">
            <a href="h3/<?= $isi ?>/proses_ke_finance_poin/<?= $sales_campaign['id'] ?>">
            <button class="btn btn-success btn-flat">Proses ke finance</button>
            </a>
          </h3>
        <?php endif; ?>
         <?php if ($sales_campaign['sudah_proses_insentif'] == 0 AND $sales_campaign['proses_ke_finance'] == 1) : ?>
         <h3 class="box-title">
            <div class="btn-group">
               <button type="button" class="btn btn-flat btn-info">Proses Insentif</button>
               <button type="button" class="btn btn-flat btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <span class="caret"></span>
               <span class="sr-only">Toggle Dropdown</span>
               </button>
               <ul class="dropdown-menu">
                  <li><a href="h3/<?= $isi ?>/proses_insentif_poin?id=<?= $sales_campaign['id'] ?>">Non-rekap</a></li>
                  <li><a href="h3/<?= $isi ?>/proses_insentif_poin?id=<?= $sales_campaign['id'] ?>&rekap=true">Rekap</a></li>
               </ul>
            </div>
         </h3>
         <?php endif; ?>
         <?php if($sales_campaign['proses_ke_finance'] == 0): ?>
         <h3 class="box-title">
            <a href="h3/<?= $isi ?>/generate_perolehan_poin_tidak_langsung?id=<?= $sales_campaign['id'] ?>">
            <button class="btn btn-info btn-flat">Hitung Perolehan Poin</button>
            </a>
         </h3>
         <?php endif; ?>
      </div>
   </div>
   <!-- /.box-header -->
   <div class="box-body">
      <?php $this->load->view('template/normal_session_message.php'); ?>
      <div class="container-fluid">
         <div class="row">
            <div class="col-sm-12 text-center">
               <h4>FORM REKAPAN PENCAIRAN HADIAH</h4>
               <h4><?= $sales_campaign['nama'] ?></h4>
               <span>Periode : <?= Mcarbon::parse($sales_campaign['start_date'])->format('d/m/Y') ?> - <?= Mcarbon::parse($sales_campaign['end_date'])->format('d/m/Y') ?></span>
            </div>
         </div>
      </div>
      <?php
         $start_date = Mcarbon::parse($sales_campaign['start_date']);
         $start_date_month = $start_date->copy()->startOfMonth();
         $end_date = Mcarbon::parse($sales_campaign['end_date']);
         $end_date_month = $end_date->copy()->startOfMonth();
         
         $perbedaan_bulan = $start_date_month->diffInMonths($end_date_month) + 1;
         ?>
      <table id="perolehan_poin_tidak_langsung" class="table table-bordered table-hover table-condensed">
         <thead>
            <tr>
               <th rowspan='2' class='align-middle'>No.</th>
               <th rowspan='2' class='align-middle'>Nama Dealer</th>
               <td colspan='<?= $perbedaan_bulan ?>' class='text-center'>TOTAL PEMBELIAN PER BULAN</td>
               <td rowspan='2' class='text-center align-middle'>Total</td>
               <?php foreach ($sales_campaign_hadiah as $row_hadiah) : ?>
               <td class='text-center'><?= $row_hadiah['nama_paket'] ?></td>
               <?php endforeach; ?>
               <td rowspan='2' class='align-middle text-center'>Sisa Poin</td>
               <td rowspan='2' class='align-middle text-center'>Total Insentif</td>
               <td rowspan='2' class='align-middle text-center'>PPN</td>
               <td rowspan='2' class='align-middle text-center'>Nilai KW</td>
               <td rowspan='2' class='align-middle text-center'>PPH 23</td>
               <td rowspan='2' class='align-middle text-center'>PPH 21</td>
               <td rowspan='2' class='align-middle text-center'>Total Bayar</td>
               <td rowspan='2' class='align-middle text-center'>Nama Bank</td>
               <td rowspan='2' class='align-middle text-center'>Atas Nama</td>
               <td rowspan='2' class='align-middle text-center'>No Rekening</td>
            </tr>
            <tr>
               <?php for ($add_month = 0; $add_month < $perbedaan_bulan; $add_month++) : ?>
               <?php
                  $month_iteration = $start_date->copy()->addMonths($add_month);
                  ?>
               <td class='text-center'><?= lang('month_' . $month_iteration->format('n')) ?> <?= $month_iteration->format('Y') ?></td>
               <?php endfor; ?>
               <?php foreach ($sales_campaign_hadiah as $row_hadiah) : ?>
               <td class='text-center'><?= $row_hadiah['jumlah_poin'] ?> Poin <br> <?= $row_hadiah['voucher_rupiah'] == 1 ? sprintf('Rp %s', number_format($row_hadiah['nama_hadiah'], 0, ',', '.')) : $row_hadiah['nama_hadiah'] ?></td>
               <?php endforeach; ?>
            </tr>
         </thead>
         <tbody></tbody>
      </table>
      <?php if($sales_campaign['proses_ke_finance'] == 1 AND $sales_campaign['sudah_proses_insentif'] == 0): ?>
      <div id='edit_finance' class="container-fluid no-padding">
         <div class="row">
           <div class="col-md-12 text-right">
             <button v-if='!edit_mode' class="btn btn-flat btn-sm btn-warning" @click.prevent='set_edit_mode'>Edit</button>
             <button v-if='edit_mode' class="btn btn-flat btn-sm btn-warning" @click.prevent='update'>Update</button>
           </div>
         </div>
      </div>
      <script>
        editFinance = new Vue({
          el: '#edit_finance',
          data: {
            edit_mode: false,
            loading: false,
          },
          methods: {
            set_edit_mode: function(){
              this.edit_mode = true;
            },
            update: function(){
              post = {};
              post.parts = _.chain(perolehan_poin_tidak_langsung.rows().data())
                .map(function(data){
                  return _.pick(data, ['id', 'pph_21']);
                })
                .value();

              this.loading = true;
              axios.post('h3/h3_md_ms_sales_campaign/update_pph21_poin', Qs.stringify(post))
              .then(function(res){
                toastr.success(res.data.message);
                editFinance.edit_mode = false;
              })
              .catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  if(data.parts_errors.length){
                    part_errors = data.parts_errors[0];
                    first_error = part_errors.errors[0]
                    toastr.error('Kode Part ' + part_errors.id_part + ' : ' + first_error);
                  }
                }else{
                  toastr.error(data.message);
                }
              }).then(function(){
                editFinance.loading = false;
              });
            }
          },
          watch: {
            edit_mode: function(){
              perolehan_poin_tidak_langsung.draw(false);
            }
          }
        })

      </script>
      <?php endif; ?>
      <script>
         $(document).ready(function() {
           perolehan_poin_tidak_langsung = $('#perolehan_poin_tidak_langsung').DataTable({
             processing: true,
             serverSide: true,
             scrollX: true,
             ordering: false,
             ajax: {
               url: "<?= base_url('api/md/h3/perolehan_poin_tidak_langsung') ?>",
               dataSrc: "data",
               type: "POST",
               data: function(d) {
                 d.id_campaign = <?= $this->input->get('id') ?>;
               }
             },
             drawCallback: function(){
                $(".update_pph21").on("keyup", function(e){
                    $row = $(this).parents("tr");
                    row_data = perolehan_poin_tidak_langsung.row($row).data();
                    input_value = $(this).val();
                    update_key = $(this).data('id');
                    row_data.pph_21 = input_value;
                });
              },
             columns: [{
                 data: 'index',
                 orderable: false,
                 width: '3%'
               },
               {
                 data: 'nama_dealer',
                 render: function(data, type, row) {
                   return row.kode_dealer_md + ' - ' + row.nama_dealer;
                 },
                 width: '350px'
               },
               <?php for ($add_month = 0; $add_month < $perbedaan_bulan; $add_month++) : ?>
                 <?php $month_iteration = $start_date->copy()->addMonths($add_month); ?> {
                   data: 'month_<?= $month_iteration->format('mY') ?>',
                   render: function(data) {
                     return accounting.formatNumber(data, 0, '.');
                   },
                   width: '150px'
                 },
               <?php endfor; ?> {
                 data: 'total_poin_penjualan_per_dealer',
                 render: function(data) {
                   return accounting.formatNumber(data, 0, '.');
                 },
                 width: '150px'
               },
               <?php foreach ($sales_campaign_hadiah as $row_hadiah) : ?> {
                   data: 'hadiah_<?= $row_hadiah['id'] ?>',
                   render: function(data) {
                     return accounting.formatNumber(data, 0, '.');
                   },
                   width: '100px'
                 },
               <?php endforeach; ?> {
                 data: 'sisa_poin',
                 render: function(data) {
                   return accounting.formatNumber(data, 0, '.');
                 },
                 width: '100px'
               },
               {
                 data: 'total_insentif',
                 render: function(data) {
                   return accounting.formatMoney(data, 'Rp ', 0, '.');
                 },
                 width: '150px',
                 className: 'text-right'
               },
               {
                 data: 'ppn',
                 width: '100px',
                 render: function(data) {
                   return accounting.formatMoney(data, 'Rp ', 0, '.');
                 },
                 className: 'text-right'
               },
               {
                 data: 'nilai_kw',
                 width: '100px',
                 render: function(data) {
                   return accounting.formatMoney(data, 'Rp ', 0, '.');
                 },
                 className: 'text-right'
               },
               {
                 data: 'pph_23',
                 width: '100px',
                 render: function(data) {
                   return accounting.formatMoney(data, 'Rp ', 0, '.');
                 },
                 className: 'text-right'
               },
               {
                 data: 'pph_21',
                 width: '100px',
                 render: function(data, type, row) {
                    <?php if($sales_campaign['proses_ke_finance'] == 1 AND $sales_campaign['sudah_proses_insentif'] == 0): ?>
                    if(editFinance.edit_mode){
                      return '<input style="width: 80px;" type="text" data-id=' + row.id +' class="update_pph21" value="'+ data +'">';
                    }
                    <?php endif; ?>
                   return accounting.formatMoney(data, 'Rp ', 0, '.');
                 },
                 className: 'text-right'
               },
               {
                 data: 'total_bayar',
                 width: '100px',
                 render: function(data) {
                   return accounting.formatMoney(data, 'Rp ', 0, '.');
                 },
                 className: 'text-right'
               },
               {
                 data: 'nama_bank',
                 render: function(data) {
                   if (data == null || data == '') {
                     return '-';
                   }
                   return data;
                 },
                 width: '100px',
               },
               {
                 data: 'atas_nama',
                 render: function(data) {
                   if (data == null || data == '') {
                     return '-';
                   }
                   return data;
                 },
                 width: '100px',
               },
               {
                 data: 'no_rekening',
                 render: function(data) {
                   if (data == null || data == '') {
                     return '-';
                   }
                   return data;
                 },
                 width: '100px'
               },
             ],
           });
         });
      </script>
   </div>
   <!-- /.box-body -->
</div>
<!-- /.box -->