<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <?= $breadcrumb ?>
</section>
<section class="content">
    <?php
    if ($set == "form") {
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
        $form = 'detail';
      }
      if ($mode == 'edit') {
        $form = 'update';
      } ?>

<script>
  Vue.use(VueNumeric.default);
</script>
      <div id="form_" class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $isi ?>">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
          </h3>
        </div><!-- /.box-header -->
        <div class="overlay" v-if='loading'>
          <i class="fa fa-refresh fa-spin text-light-blue"></i>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">PO Number</label>
                    <div class="col-sm-4">
                        <input v-model='purchase_order.po_id' type="text" class="form-control" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal PO</label>
                    <div class="col-sm-4">
                        <input v-model='purchase_order.tanggal_order' type="text" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Booking Reference</label>
                    <div class="col-sm-4">
                        <input v-model='purchase_order.id_booking' type="text" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                    <div class="col-sm-4">
                        <input v-model='purchase_order.id_customer' type="text" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                    <div class="col-sm-4">
                        <input v-model='purchase_order.nama_customer' type="text" class="form-control" readonly>
                    </div>
                    <div v-if='purchase_order.nama_pemesan != null'>
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Pemesan</label>
                      <div class="col-sm-4">
                          <input v-model='purchase_order.nama_pemesan' type="text" class="form-control" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kontak Customer</label>
                    <div class="col-sm-4">
                        <input v-model='purchase_order.kontak_customer' type="text" class="form-control" readonly>
                    </div>
                    <div v-if='purchase_order.nama_pemesan != null'>
                      <label for="inputEmail3" class="col-sm-2 control-label">Kontak Pemesan</label>
                      <div class="col-sm-4">
                          <input v-model='purchase_order.kontak_pemesan' type="text" class="form-control" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Qty Order</label>
                    <div class="col-sm-4">
                        <input v-model='purchase_order.qty_order' type="text" class="form-control" readonly>
                    </div>
                    <div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Qty Terpenuhi</label>
                      <div class="col-sm-4">
                          <input v-model='purchase_order.qty_terpenuhi' type="text" class="form-control" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Qty Belum Terpenuhi</label>
                    <div class="col-sm-4">
                        <input v-model='purchase_order.qty_belum_terpenuhi' type="text" class="form-control" readonly>
                    </div>
                    <div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Fulfillment Rate</label>
                      <div class="col-sm-4">
                          <input v-model='purchase_order.fulfillment_rate' type="text" class="form-control" readonly>
                      </div>
                    </div>
                  </div>
                  <?php $list_parts = ''; foreach($purchase_order_parts as $part): $list_parts .= ", " . $part['nama_part']; endforeach; $list_parts = substr($list_parts, 2); ?>
                  <div v-if='notify_customer' class="box-footer box-comments bg-yellow spacing-y-20">
                    <div class="box-comment">
                      <div class="comment-text" style='color: #333;'>
                        <span class="username" style='font-size: 16px; color: #333;'>Pesanan sudah terpenuhi.</span><!-- /.username -->
                        Part <?= $list_parts ?> atas pesanan <?= $purchase_order['nama_pemesan'] != null ? $purchase_order['nama_pemesan'] : $purchase_order['nama_customer'] ?> telah tersedia di dealer <?= $purchase_order['nama_dealer'] ?>. Silahkan melakukan pengambilan parts dengan membawa bukti tanda jadi.
                      </div>
                    </div>
                  </div>
                  <div class="row" style='margin: 10px 0;'>
                    <div class="col-sm-6">
                      <!-- <button v-if='purchase_order.penyerahan_customer != 1' class="btn btn-sm btn-flat btn-info" @click.prevent='penyerahan_customer'>Penyerahan ke Customer</button> -->
                    </div>
                    <!-- <div class="col-sm-6 text-right no-padding">
                      <button @click.prevent='send_email_to_customer' class="btn btn-flat btn-sm btn-success"><i class="fa fa-paper-plane"></i> Kirim Email ke Customer</button>
                    </div> -->
                    <?php 
                      $getData =  $purchase_order['po_id'] ;
                      $deleteChar = str_replace(array("/"), '', $getData); 
                    ?>
                    <div class="col-sm-6 text-right no-padding">
                      <button type="button" class="btn btn-sm btn-primary btn-flat"  data-toggle="modal" data-target="#modal_pesan_ke_customer"><i class="fa fa-message"></i> Kirim Pesan ke Customer</button>
                      <button type="button" class="btn btn-sm btn-success btn-flat"  data-toggle="modal" data-target="#modal_history_pesan_<?=$deleteChar?>" data-id="<?php echo $purchase_order['po_id']?>"><i class="fa fa-history"></i> History Pesan</button>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <tr class='bg-blue-gradient'>
                        <td>No.</td>
                        <td>No. SO</td>
                        <td>Kode Part</td>
                        <td>Nama Part</td>
                        <td width='15%' class='text-right'>Qty</td>
                        <td width='15%' class='text-right'>Qty Tersedia</td>
                        <td width='15%' class='text-right'>Qty Belum Tersedia</td>
                        <td>ETA Tercepat</td>
                        <td>ETA Terlama</td>
                        <td>ETA Revisi</td>
                      </tr>
                      <tr v-if='parts.length > 0' v-for='(e, index) in parts'>
                        <td>{{ index + 1 }}.</td>
                        <td>
                          <span v-if='e.id_sales_order.length > 0'>{{ e.id_sales_order.join(', ') }}</span>
                          <span v-if='e.id_sales_order.length < 1'>-</span>
                        </td>
                        <td>{{ e.id_part }}</td>
                        <td>{{ e.nama_part }}</td>
                        <td class='text-right'>{{ e.kuantitas }}</td>
                        <td class='text-right'>{{ e.qty_terpenuhi }}</td>
                        <td class='text-right'>{{ e.kuantitas - e.qty_terpenuhi }}</td>
                        <td>{{ e.eta_tercepat }}</td>
                        <td>{{ e.eta_terlama }}</td>
                        <td v-if='e.eta_revisi == null'>-</td>
                        <td v-if='e.eta_revisi != null'>{{ e.eta_revisi }}</td>
                      </tr>
                    </table>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <tr class='bg-blue-gradient'>
                        <td>No.</td>
                        <td>Kode Part</td>
                        <td>Nama Part</td>
                        <td width='5%' class='text-right'>Qty</td>
                        <td>Penerimaan Barang</td>
                        <td>Tanggal</td>
                      </tr>
                      <tr v-if='penerimaan_parts.length > 0' v-for='(e, index) in penerimaan_parts'>
                        <td>{{ index + 1 }}.</td>
                        <td>{{ e.id_part }}</td>
                        <td>{{ e.nama_part }}</td>
                        <td class='text-right'>{{ e.qty }}</td>
                        <td>{{ e.id_penerimaan_barang }}</td>
                        <td>{{ e.tanggal_penerimaan_barang }}</td>
                      </tr>
                    </table>
                  </div>
                  </script>
                  <div class="box-footer">
                  </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div>
      <form class="form-horizontal" method="post" action="dealer/h3_dealer_order_fulfillment/kirim_pesan_ke_customer" enctype="multipart/form-data">
        <div id="modal_pesan_ke_customer" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg"> 
                <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title text-left" id="myModalLabel">Kirim Pesan Kepada Customer</h4>
                        </div>
                        <div class="box-body">
                          <div class="modal-body">
                              <input type="hidden" value="<?php echo $purchase_order['po_id']?>" name="po_id">
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group">
                                      <label for="inputEmail3" class="col-sm-3 control-label">Tanggal Kontak ke Customer *</label>
                                      <div class="col-sm-3">
                                          <input type="text" class="form-control datepicker" value="<?= date('Y-m-d') ?>" name="tgl_kontak_customer" id="tgl_kontak_customer">
                                      </div>   
                                      <div class="col-sm-3">
                                          <input type="text" class="form-control" value="<?= date('H:m:s') ?>" name="jam_kontak_customer" id="datetime"/>
                                      </div> 
                                    </div>
                                  </div>
                                  <div class="form-group">
                                      <label for="inputEmail3" class="col-sm-3 control-label">Jenis Informasi Pesan ke Customer *</label>
                                      <div class="col-sm-3">
                                        <select class="form-control select2" aria-label="Default select example" name="informasi_pesan" id="informasi_pesan" style="width: 100%">
                                            <option value="info_eta_ke_customer">Informasi ETA kepada Customer</option>
                                            <option value="info_kedatangan_part">Informasi Kedatangan Part kepada Customer</option>
                                        </select>                     
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label for="inputEmail3" class="col-sm-3 control-label">Media Komunikasi *</label>
                                      <div class="col-sm-3">
                                        <select class="form-control select2" aria-label="Default select example" name="tipe_kontak" id="tipe_kontak" style="width: 100%">
                                          <!-- <option value="no" selected disabled>--Pilih Media Komunikasi--</option> -->
                                          <option value="WA">WhatsApp</option>
                                          <option value="Email">Email</option>
                                        </select>   
                                      </div> 
                                      <?php 
                                        $no=$purchase_order['kontak_customer'];
                                        if(substr($no,0,2)=='08'){
                                          $no = str_replace(substr($no,0,2),"628",$no);
                                        }elseif(substr($no,0,3)=='+62'){
                                          $no = str_replace(substr($no,0,3),"62",$no);
                                        }elseif(substr($no,0,1)=='8'){
                                          $no = str_replace(substr($no,0,1),"628",$no);
                                        }

                                        $pesan2 = "Ini Pesan";
                                        $message = '&text=' . urlencode($pesan2);
                                        $link_no="https://web.whatsapp.com/send?phone=".$no. $message;
                                        $wa = '<a href="'.$link_no.'" id="wa" target="_blank" data-toggle="tooltip" data-placement="top" title="Chat WA"> <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                        <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                                          </svg> </a>';
                                        // $wa = '<a href="'.$link_no.'" id="wa" target="_blank" class="btn btn-flat btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Chat WA"><i class="fa fa-whatsapp"></i> Kirim WA Ke Customer</a>';
                                      ?>
                                      <div class="col-sm-1" id="chat-wa">
                                        <?php echo $wa?>
                                      </div>  
                                      <div class="col-sm-4" id="chat-email">
                                        <button @click.prevent='send_email_to_customer' class="btn btn-flat btn-sm btn-success"><i class="fa fa-envelope"></i></button>
                                      </div> 
                                  </div>
                                </div>
                              </div>
                              <hr>
                              <button type="submit" class="btn btn-flat btn-sm btn-primary btn-block">Proses</button>
                          </div>
                        </div>
                        <!-- <div class="modal-footer">
                            <button type="submit" class="btn btn-flat btn-sm btn-primary">Proses</button>
                        </div> -->
                </div>
            </div>
        </div>
      </form>

      <div id="modal_history_pesan_<?=$deleteChar?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg"> 
                <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title text-left" id="myModalLabel">History Pesan </h4>
                        </div>
                        <div class="box-body">
                          <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tanggal Kontak Customer</th>
                                <th scope="col">Media Komunikasi</th>
                                <th scope="col">Status Pesan</th>
                              </tr>
                            </thead>
                            <tbody>
                              
                              <?php 
                              $no = 1;
                              foreach($history_pesan_customer as $row){ ?>
                              <tr>
                                <th scope="row"><?php echo $no++?></th>
                                <td><?php echo $row['tgl_kontak_customer']?></td>
                                <td><?php echo $row['tipe_kontak']?></td>
                                <td><?php echo $row['informasi_pesan']?></td>
                              </tr>
                            <?php }?>
                            </tbody>
                          </table>
                        </div>
                </div>
            </div>
      </div>
      <script>
        $('#chat-wa').show();
        $('#chat-email').hide();

        $(document).ready(function() {
          $('#tipe_kontak').on('select2:select', function (e) {
              tipe_kontak=$('#tipe_kontak').val();
              if(tipe_kontak=='WA'){
                $('#chat-wa').show();
              }else{
                $('#chat-wa').hide();
              }
              if(tipe_kontak=='Email'){
                $('#chat-email').show();
              }else{
                $('#chat-email').hide();
              }
            });
        });
        form_ = new Vue({
          el: '#form_',
          data: {
            kosong: '',
            mode: '<?= $mode ?>',
            loading: false,
            purchase_order: <?= json_encode($purchase_order) ?>,
            penerimaan_parts: <?= json_encode($penerimaan_parts) ?>,
            parts: <?= json_encode($parts) ?>,
          },
          methods: {
            send_email_to_customer: function(){
              post = {};
              post.id = this.purchase_order.po_id;
              this.loading = true;
              axios.post('dealer/h3_dealer_order_fulfillment/send_email_to_customer', Qs.stringify(post))
              .then(function(res){
                toastr.success(res.data.message);
              })
              .catch(function(err){
                toastr.error(err.response.data.message);
              })
              .then(function(){
                form_.loading = false;
              });
            },
            penyerahan_customer: function(){
              post = {};
              post.po_id = this.purchase_order.po_id;
              this.loading = true;
              axios.post('dealer/h3_dealer_order_fulfillment/penyerahan_customer', Qs.stringify(post))
              .then(function(res){
                toastr.success(res.data.message);
              })
              .catch(function(err){
                toastr.error(err.response.data.message);
              })
              .then(function(){
                form_.loading = false;
              });
            }
          },
          computed: {
            notify_customer: function(){
              return this.purchase_order.qty_order === this.purchase_order.qty_terpenuhi;
            }
          }
        });
      </script>
    <?php
    } elseif ($set == "index") {
    ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $isi ?>/add">
              <!-- <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button> -->
            </a>
          </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <link rel="stylesheet" href="assets/css-progress-wizard-master/css/progress-wizard.min.css">
          <table id="order_fulfillment" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>No.</th>
                <th>Tanggal PO</th>
                <th>PO ID</th>
                <th>Nama Customer</th>
                <th>Kontak Customer</th>
                <th>Qty Order</th>
                <!-- <th>Qty Terpenuhi</th> -->
                <!-- <th>Qty Belum Terpenuhi</th>
                <th>Fulfillment Rate</th>
                <th>ETA Terlama</th> -->
                <!-- <th>Status</th> -->
                <!-- <th></th> -->
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <script>
            $(document).ready(function() {
              order_fulfillment = $('#order_fulfillment').DataTable({
                  initComplete: function() {
                      $('#order_fulfillment_length').parent().removeClass('col-sm-6').addClass('col-sm-2');
                      $('#order_fulfillment_filter').parent().removeClass('col-sm-6').addClass('col-sm-10');
                      axios.get('html/filter_order_fulfillment')
                          .then(function(res) {
                              $('#order_fulfillment_filter').prepend(res.data);

                              $('#filter_status_order_fulfillment').change(function(){
                                order_fulfillment.draw();
                              });
                          });
                  },
                  processing: true,
                  serverSide: true,
                  order: [],
                  ajax: {
                      url: "<?= base_url('api/dealer/order_fulfillment') ?>",
                      dataSrc: "data",
                      type: "POST",
                      data: function(data){
                        start_date = $('#filter_order_fulfillment_date_start').val();
                        end_date = $('#filter_order_fulfillment_date_end').val();
                        if ((start_date != undefined && start_date != '') && (end_date != undefined && end_date != '')) {
                            data.filter_order_fulfillment_date = true;
                            data.start_date = start_date;
                            data.end_date = end_date;
                        }

                        data.filter_status = $('#filter_status_order_fulfillment').val();
                      }
                  },
                  createdRow: function (row, data, index) {
                    $('td', row).addClass('align-middle');
                  },
                  columns: [
                      { data: null, width: '3%', orderable: false },
                      { data: 'tanggal_po' },
                      { data: 'po_id' },
                      { data: 'nama_customer' },
                      { data: 'kontak_customer' },
                      { data: 'qty_order' },
                      // { data: 'qty_terpenuhi' },
                      // { data: 'qty_belum_terpenuhi' },
                      // { data: 'fulfillment_rate' },
                      // { data: 'eta_terlama' },
                      // { data: 'status', orderable: false },
                      // { data: 'indikator', orderable: false, className: 'text-center', width: '2%' },
                      { data: 'action', width: '3%', className: 'text-center', orderable: false },
                  ],
              });

              order_fulfillment.on('draw.dt', function() {
                  var info = order_fulfillment.page.info();
                  order_fulfillment.column(0, {
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
    <?php
    }
    ?>
  </section>
</div>