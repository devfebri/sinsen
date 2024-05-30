<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">H2</li>
      <li class="">Service Execution</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">

    <?php
    if ($set == "index") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">

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
                <th>Good Issue ID</th>
                <th>Nomor SO</th>
                <th>No. Picking Slip</th>
                <th>ID Work Order</th>
                <th>Tgl. Servis</th>
                <th>Jenis Customer</th>
                <th>No. Polisi</th>
                <th>Nama Customer</th>
                <th>No. Mesin</th>
                <th>No. Rangka</th>
                <th>Tipe Motor</th>
                <th>Warna</th>
                <!-- <th width="10%">Action</th> -->
              </tr>
            </thead>
          </table>
        </div><!-- /.box-body -->
        <script>
          $(document).ready(function() {
            var dataTable = $('#datatable_server').DataTable({
              "processing": true,
              "serverSide": true,
              "scrollX": true,
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
                  return d;
                },
              },
              "columnDefs": [],
            });
          });
        </script>
      <?php
    }
      ?>
      <?php if ($set == 'send_wo') { ?>
        <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
        <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
        <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
        <link href='assets/select2/css/select2.min.css' rel='stylesheet' type='text/css'>
        <script src="assets/jquery/jquery.min.js"></script>
        <script src='assets/select2/js/select2.min.js'></script>

        <script>
          Vue.use(VueNumeric.default);
          $(document).ready(function() {
            <?php if (isset($row)) { ?>
              // $('#id_antrian').val('<?= $row->id_antrian ?>').trigger('change');
              getSaForm('detail');
            <?php } ?>
            <?php if (isset($row_wo)) { ?>
              getDataWO('detail');
            <?php } ?>
          })
          Vue.filter('toCurrency', function(value) {
            // // console.log("type value ke currency filter" ,  value, typeof value, typeof value !== "number");
            // if (typeof value !== "number") {
            //     return value;
            // }
            return accounting.formatMoney(value, "", 0, ".", ",");
            return value;
          });
        </script>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="dealer/<?= $this->uri->segment(2); ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
              </a>
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
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal" id="form_" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">ID Work Order</label>
                    <div class="col-sm-4">
                      <input type="text" value="<?= $wo->id_work_order ?>" class="form-control" name="id_work_order" readonly id="id_work_order">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                    <div class="col-sm-4">
                      <input type="text" value="<?= $wo->id_customer ?>" class="form-control" name="id_customer" readonly>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Parts</button><br><br>
                  </div>
                  <table class="table table-bordered">
                    <thead>
                      <th>ID Part</th>
                      <th>Nama Part</th>
                      <th>Harga</th>
                      <th>Quantity</th>
                      <th>Gudang</th>
                      <th>Rak</th>
                      <th>Status Picking</th>
                    </thead>
                    <tbody>
                      <tr v-for="(prt, index) of parts">
                        <input type="hidden" name="id_part[]" :value="prt.id_part">
                        <input type="hidden" name="id_gudang[]" :value="prt.id_gudang">
                        <input type="hidden" name="id_rak[]" :value="prt.id_rak">
                        <input type="hidden" name="kuantitas[]" :value="prt.qty">
                        <input type="hidden" name="harga_saat_dibeli[]" v-model="prt.harga">
                        <td>{{prt.id_part}}</td>
                        <td>{{prt.nama_part}}</td>
                        <td align="right">{{prt.harga | toCurrency}}</td>
                        <td>{{prt.qty}}</td>
                        <td>{{prt.id_gudang}}</td>
                        <td>{{prt.id_rak}}</td>
                        <td>{{prt.picking}}</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="box-footer" v-if="mode!='detail'">
                    <div class="col-sm-12" align="center">
                      <button type="button" id="submitBtn" name="save" value="save" class="btn btn-primary btn-flat"><i class="fa fa-send"></i> Send WO to Part Counter</button>
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
              kosong: '',
              mode: '<?= $mode ?>',
              parts: <?= isset($parts) ? json_encode($parts) : '[]' ?>,
            },
            methods: {

            },
            watch: {}
          });

          $('#submitBtn').click(function() {
            $('#form_').validate({
              rules: {
                'checkbox': {
                  required: true
                }
              },
              highlight: function(input) {
                $(input).parents('.form-group').addClass('has-error');
              },
              unhighlight: function(input) {
                $(input).parents('.form-group').removeClass('has-error');
              }
            })
            if ($('#form_').valid()) // check if form is valid
            {
              // var values = {};
              var form = $('#form_').serializeArray();

              if (confirm("Apakah anda yakin ?") == true) {
                var users = $('input[name="username[]"]').map(function() {
                  return this.value;
                }).get();
                $.ajax({
                  beforeSend: function() {
                    $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                    $('#submitBtn').attr('disabled', true);
                  },
                  url: '<?= base_url('dealer/h3_dealer_sales_order/save') ?>',
                  type: "POST",
                  data: form,
                  cache: false,
                  async: false,
                  dataType: 'JSON',
                  success: function(response) {
                    let nomor_so = response.nomor_so;
                    saveSendWOParts(nomor_so);
                  },
                  error: function() {
                    alert("Something Went Wrong !");
                    $('#submitBtn').html('<i class="fa fa-send"></i> Send WO to Part Counter');
                    $('#submitBtn').attr('disabled', false);
                  },
                });
              } else {
                return false;
              }
            } else {
              alert('Silahkan isi field required !')
            }
          })

          function saveSendWOParts(nomor_so) {
            values = {
              nomor_so: nomor_so,
              id_work_order: $('#id_work_order').val(),
              parts: form_.parts
            }
            $.ajax({
              beforeSend: function() {
                $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                $('#submitBtn').attr('disabled', true);
              },
              url: '<?= base_url('dealer/execute_wo/save_send_wo') ?>',
              type: "POST",
              data: values,
              cache: false,
              async: false,
              dataType: 'JSON',
              success: function(response) {
                if (response.status == 'sukses') {
                  window.location = response.link;
                } else {
                  alert(response.pesan);
                  $('#submitBtn').attr('disabled', false);
                }
                $('#submitBtn').html('<i class="fa fa-send"></i> Send WO to Part Counter');
              },
              error: function() {
                alert("Something Went Wrong !");
                $('#submitBtn').html('<i class="fa fa-send"></i> Send WO to Part Counter');
                $('#submitBtn').attr('disabled', false);
              },
            });
          }
        </script>
      <?php } ?>
  </section>
</div>