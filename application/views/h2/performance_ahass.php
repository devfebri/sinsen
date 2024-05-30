<base href="<?php echo base_url(); ?>" />
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script type="text/javascript" src="assets/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="assets/daterangepicker/daterangepicker.css" />
<script>
  Vue.use(VueNumeric.default);
  Vue.filter('toCurrency', function(value) {
    // console.log("type value ke currency filter" ,  value, typeof value, typeof value !== "number");
    // if (typeof value !== "number") {
    //   return value;
    // }
    return accounting.formatMoney(value, "", 0, ".", ",");
    return value;
  });
</script>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">H2</li>
      <li class="">AHASS Network</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>
  <section class="content">
    <?php
    if ($set == "view") {
    ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">

          </h3>
        </div>
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
                    <div class="col-sm-2">
                      <label>Kode AHASS</label>
                      <input type="text" class="form-control" id="kode_dealer_md" name="kode_dealer_md" readonly onclick="showModalAHASS()" placeholder='Klik Untuk Memilih'>
                      <input type="hidden" id="id_dealer">
                    </div>
                    <div class="col-sm-4">
                      <label>Nama AHASS</label>
                      <input type="text" class="form-control" id="nama_dealer" name="nama_dealer" readonly onclick="showModalAHASS()" placeholder='Klik Untuk Memilih'>
                    </div>
                    <div class="col-sm-3">
                      <label>Periode</label>
                      <input type="text" class="form-control" id="periode" name="periode" readonly placeholder='Klik Untuk Memilih'>
                      <input type="hidden" class="form-control" id="start_date" name="start_date" readonly value='<?= date('Y-m') . '-01'; ?>'>
                      <input type="hidden" class="form-control" id="end_date" name="end_date" readonly value='<?= get_ymd() ?>'>
                    </div>
                  </div>
                </div>
                <div class="box-footer" align='center'>
                  <button class='btn btn-flat btn-primary' type="button" onclick="search()"><i class="fa fa-search"></i></button>
                  <button class='btn btn-flat btn-default' type="button" onclick="refresh()"><i class="fa fa-refresh"></i></button>
                  <button class='btn btn-flat btn-success' type="button" id='btn_download' onclick="downloadExcell()"><i class="fa fa-download"></i> .xls</button>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <?php
            $data = ['data' => ['modalAHASS']];
            $this->load->view('h2/api', $data);
            ?>
          </div>
          <table class="table table-striped table-bordered table-hover table-condensed" id="tr_po_kpb" style="width: 100%">
            <thead>
              <tr>
                <th>Kode AHASS</th>
                <th>Nama AHASS</th>
                <th>No. Mesin</th>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>Kunjungan Konsumen</th>
                <th>Pendapatan KPB(Rp)</th>
                <th>Pendapatan PL/PR/OR(Rp)</th>
                <th>Sparepart(Rp)</th>
                <th>Oli(Rp)</th>
                <th>Total ASS</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $('#periode').daterangepicker({
              opens: 'left',
              autoUpdateInput: false,
              locale: {
                format: 'DD/MM/YYYY'
              }
            }, function(start, end, label) {
              $('#start_date').val(start.format('YYYY-MM-DD'));
              $('#end_date').val(end.format('YYYY-MM-DD'));
            }).on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            }).on('cancel.daterangepicker', function(ev, picker) {
              $(this).val('');
              $('#start_date').val('');
              $('#end_date').val('');
            });

            $('#periode_do_filter').daterangepicker({
              opens: 'left',
              autoUpdateInput: false,
              locale: {
                format: 'DD/MM/YYYY'
              }
            }, function(start, end, label) {
              $('#periode_do_filter_start').val(start.format('YYYY-MM-DD'));
              $('#periode_do_filter_end').val(end.format('YYYY-MM-DD'));
              app.get_picking_list();
            }).on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            }).on('cancel.daterangepicker', function(ev, picker) {
              $(this).val('');
              $('#periode_do_filter_start').val('');
              $('#periode_do_filter_end').val('');
              app.get_picking_list();
            });

            $(document).ready(function() {
              $('#tr_po_kpb').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": "",
                  "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                },
                order: [],
                ajax: {
                  url: "<?= base_url($folder . '/' . $isi . '/fetch') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    d.id_dealer = $('#id_dealer').val();
                    d.tgl_po_kpb = $('#tgl_po_kpb').val();
                    d.end_date = $('#end_date').val();
                    d.start_date = $('#start_date').val();
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [{
                    "targets": [5, 6, 7, 8, 9, 10, 11],
                    "orderable": false
                  },
                  {
                    "targets": [5, 10, 11],
                    "className": 'text-center'
                  },
                  {
                    "targets": [6, 7, 8, 9],
                    "className": 'text-right'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
              });
            });

            function pilihAHASS(ahass) {
              $('#kode_dealer_md').val(ahass.kode_dealer_md);
              $('#nama_dealer').val(ahass.nama_dealer);
              $('#id_dealer').val(ahass.id_dealer);
            }

            function search() {
              $('#tr_po_kpb').DataTable().ajax.reload();
            }

            function refresh() {
              $('#kode_dealer_md').val('');
              $('#nama_dealer').val('');
              $('#id_dealer').val('');
              $('#tgl_po_kpb').val('');
              $('#status').val('');
              $('#tr_po_kpb').DataTable().ajax.reload();
            }

            function downloadExcell() {
              var value = {
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                cetak: 'cetak',
              }

              if (value.end_date == '' || value.start_date == '') {
                toastr_warning('Isi data dengan lengkap ..!');
                return false;
              } else {
                let value = {
                  id_dealer: $('#id_dealer').val(),
                  start_date: $('#start_date').val(),
                  end_date: $('#end_date').val(),
                }
                values = JSON.stringify(value);
                window.location = '<?= base_url('h2/' . $isi . '/download_xls?params=') ?>' + values
              }
            }
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    } elseif ($set == "form") {
      $form = '';
      if ($mode == 'insert') {
        $form = 'save';
      } elseif ($mode == 'approved') {
        $form = 'save_approval';
      }
    ?>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="<?= $folder . '/' . $isi ?>" class="btn bg-maroon btn-flat">
              <i class="fa fa-eye"></i> View Data
            </a>
          </h3>
        </div>
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
              <form id="form_" class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <?php if (isset($row)) { ?>
                    <div class="form-group">
                      <div class="form-input">
                        <label for="inputEmail3" class="col-sm-2 control-label">ID PO KPB</label>
                        <div class="col-sm-4">
                          <input type="text" required class="form-control datepicker" name="id_po_kpb" id="id_po_kpb" autocomplete="off" value="<?= isset($row) ? $row->id_po_kpb : '' ?>" readonly>
                        </div>
                      </div>
                      <div class="form-input">
                        <label for="inputEmail3" class="col-sm-2 control-label">Tgl PO KPB</label>
                        <div class="col-sm-4">
                          <input type="text" class='form-control' readonly value="<?= isset($row) ? $row->tgl_po_kpb : '' ?>">
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                      <div class="col-sm-4">
                        <input type="text" required class="form-control datepicker" placeholder="Start Date" name="start_date" id="start_date" autocomplete="off" value="<?= isset($row) ? $row->start_date : '' ?>" :disabled="disabled">
                      </div>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Kode AHASS</label>
                      <div class="col-sm-4">
                        <input type="text" required readonly onclick="showModalAHASS()" class="form-control" placeholder="Klik Untuk Memilih" id="kode_dealer_md" value="<?= isset($row) ? $row->kode_dealer_md : '' ?>">
                        <input type="hidden" name="id_dealer" id="id_dealer" value="<?= isset($row) ? $row->id_dealer : '' ?>">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control datepicker" placeholder="End Date" name="end_date" id="end_date" autocomplete="off" required value="<?= isset($row) ? $row->end_date : '' ?>" :disabled="disabled">
                      </div>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama AHASS</label>
                      <div class="col-sm-4">
                        <input type="text" required onclick="showModalAHASS()" class="form-control" id="nama_ahass" readonly placeholder="Klik Untuk Memilih" required value="<?= isset($row) ? $row->nama_dealer : '' ?>">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12" align="center" v-if="mode=='insert' || mode=='edit'">
                      <button type="button" id="generateBtn" @click.prevent="generateData" class="btn btn-success btn-flat">Generate Data</button>
                    </div>
                  </div>
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-info btn-flat btn-sm" disabled>Detail</button><br><br>
                  <table class="table table-bordered table-hover table-condensed table-stripped">
                    <thead>
                      <th>Kode Part</th>
                      <th>Kode Tipe</th>
                      <th>Qty</th>
                      <th>Harga</th>
                      <th>Diskon</th>
                      <th>Harga Setelah Diskon</th>
                      <th>Total Harga</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dt, index) of details">
                        <td>{{dt.id_part}}</td>
                        <td>{{dt.id_tipe_kendaraan}}</td>
                        <td>{{dt.qty}}</td>
                        <td align="right">{{dt.harga_material | toCurrency}}</td>
                        <td align="right">{{dt.diskon | toCurrency}}</td>
                        <td align="right">{{dt.harga_setelah_diskon | toCurrency}}</td>
                        <td align="right">{{dt.total | toCurrency}}</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan=2><b>Total</b></td>
                        <td><b>{{total.qty | toCUrrency}}</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td align='right'><b>{{total.total | toCurrency}}</b></td>
                      </tr>
                      <tr>
                        <td colspan=6 align='right'><b>PPN</b></td>
                        <td align='right'><b>{{total.ppn | toCurrency}}</b></td>
                      </tr>
                      <tr>
                        <td colspan=6 align='right'><b>Grand Total</b></td>
                        <td align='right'><b>{{total.grand | toCurrency}}</b></td>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align='center'>
                    <button v-if="mode=='insert'" type="button" id="submitBtn" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    <button v-if="mode=='approved'" type="button" onclick="setApproval('approved')" class="btn btn-flat btn-success"><i class="fa fa-check"></i> Approved </button>
                    <button v-if="mode=='approved'" type="button" class="btn btn-flat btn-danger" onclick="setApproval('rejected')"><i class="fa fa-remove"></i> Rejected </button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <?php
      $data = ['data' => ['modalAHASS']];
      $this->load->view('h2/api', $data);
      ?>
      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            details: <?= isset($row) ? json_encode($details) : '[]' ?>,
          },
          methods: {
            generateData: function() {
              $('#form_').validate({
                highlight: function(input) {
                  $(input).parents('.form-input').addClass('has-error');
                },
                unhighlight: function(input) {
                  $(input).parents('.form-input').removeClass('has-error');
                }
              })
              if ($('#form_').valid()) // check if form is valid
              {
                let values = {};
                var form = $('#form_').serializeArray();
                for (field of form) {
                  values[field.name] = field.value;
                }
                $.ajax({
                  beforeSend: function() {
                    $('#generateBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                    $('#generateBtn').attr('disabled', true);
                  },
                  url: '<?= base_url('h2/' . $isi . '/generate') ?>',
                  type: "POST",
                  data: values,
                  cache: false,
                  dataType: 'JSON',
                  success: function(response) {
                    $('#generateBtn').html('Generate Data');
                    $('#generateBtn').attr('disabled', false);
                    if (response.status == 'sukses') {
                      form_.details = response.data;
                    } else {
                      alert(response.pesan);
                      form_.details = [];
                    }
                  },
                  error: function() {
                    alert("Something Went Wrong !");
                    $('#generateBtn').html('Generate Data');
                    $('#generateBtn').attr('disabled', false);
                  },
                });
              } else {
                alert('Silahkan isi field required !')
              }
            }
          },
          computed: {
            total: function() {
              let total = {
                qty: 0,
                grand: 0,
                total: 0,
                ppn: 0,
              }
              for (dt of this.details) {
                total.qty += parseInt(dt.qty);
                total.total += parseInt(dt.total);
              }
              total.ppn = Math.round(total.total * 0.1);
              total.grand = total.total + total.ppn;
              return total;
            },
            disabled: function() {
              let disabled = false;
              if (this.mode === 'approved') {
                disabled = true;
              } else if (this.mode === 'detail') {
                disabled = true;
              }
              return disabled
            }
          }
        })
        $('#submitBtn').click(function() {
          $('#form_').validate({
            rules: {
              'checkbox': {
                required: true
              }
            },
            highlight: function(input) {
              $(input).parents('.form-input').addClass('has-error');
            },
            unhighlight: function(input) {
              $(input).parents('.form-input').removeClass('has-error');
            }
          })
          var values = {
            details: form_.details
          };
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          if ($('#form_').valid()) // check if form is valid
          {
            if (values.details.length == 0) {
              alert('Data masih kosong !')
              return false;
            }
            if (confirm("Apakah anda yakin ?") == true) {
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('#submitBtn').attr('disabled', true);
                },
                url: '<?= base_url('h2/' . $isi . '/' . $form) ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    window.location = response.link;
                  } else {
                    alert(response.pesan);
                    $('#submitBtn').attr('disabled', false);
                  }
                  $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                },
                error: function() {
                  alert("Something Went Wrong !");
                  $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                  $('#submitBtn').attr('disabled', false);

                }
              });
            } else {
              return false;
            }
          } else {
            alert('Silahkan isi field required !')
          }
        })

        function setApproval(params, el) {
          var values = {
            set: params
          };
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          if (confirm("Apakah anda yakin ?") == true) {
            $.ajax({
              beforeSend: function() {
                $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
                $(el).attr('disabled', true);
              },
              url: '<?= base_url('h2/' . $isi . '/' . $form) ?>',
              type: "POST",
              data: values,
              cache: false,
              dataType: 'JSON',
              success: function(response) {
                if (response.status == 'sukses') {
                  window.location = response.link;
                } else {
                  alert(response.pesan);
                  $(el).attr('disabled', false);
                }
                $(el).html('<i class="fa fa-save"></i> Save All');
              },
              error: function() {
                alert("Something Went Wrong !");
                $(el).html('<i class="fa fa-save"></i> Save All');
                $(el).attr('disabled', false);

              }
            });
          }
        }

        function pilihAHASS(ahass) {
          $('#kode_dealer_md').val(ahass.kode_dealer_md);
          $('#nama_ahass').val(ahass.nama_dealer);
          $('#id_dealer').val(ahass.id_dealer);
        }
      </script>
    <?php } ?>
  </section>
</div>