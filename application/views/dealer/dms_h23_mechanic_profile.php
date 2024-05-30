<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">DMS Extension</li>
      <li class="">H23</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $title)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php
    if ($set == "index") { ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
          </h3>
          <div class="box-tools pull-right">
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
                <!-- <th width='16%'>ID Karyawan Dealer</th> -->
                <th width='15%'>Honda ID</th>
                <th>Nama Lengkap</th>
                <th>Jabatan</th>
                <th width='10%'>Active</th>
                <th width='5%'>Aksi</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              var dataTable = $('#datatable_server').DataTable({
                "processing": true,
                "serverSide": true,
                // "scrollX": true,
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
                  url: "<?php echo site_url($folder . '/' . $isi . '/fetch'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  {
                    "targets": [3, 4],
                    "className": 'text-center'
                  },
                  // {
                  //   "targets": [3, 4, 5],
                  //   "className": 'text-right'
                  // },
                  // // { "targets":[0],"checkboxes":{'selectRow':true}}
                  // { "targets":[4],"className":'text-right'}, 
                  // // { "targets":[2,4,5], "searchable": false } 
                ],
              });
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php } elseif ($set == 'detail') {
      $form = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'edit') {
        $form = 'save_edit';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <link href='assets/select2/css/select2.min.css' rel='stylesheet' type='text/css'>
      <script src="assets/jquery/jquery.min.js"></script>
      <script src='assets/select2/js/select2.min.js'></script>
      <script src="assets/panel/dist/js/canvas.min.js"></script>
      <script src="assets/chartjs/Chart.bundle.min.js"></script>
      <script src="assets/chartjs/chartjs-plugin-labels.min.js"></script>
      <script type="text/javascript" src="assets/panel/dist/js/chartjs-plugin-labels.js"></script>
      <script>
        Vue.use(VueNumeric.default);
        Vue.filter('toCurrency', function(value) {
          return 'Rp. ' + accounting.formatMoney(value, "", 0, ".", ",");
          // return value;
        });

        Vue.filter('cekType', function(value, arg1) {
          if (arg1 == 'persen') {
            return value + ' %';
          } else {
            return 'Rp. ' + accounting.formatMoney(value, "", 0, ".", ",");
          }
        });

        $(document).ready(function() {})
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="<?= $folder . '/' . $this->uri->segment(2); ?>"> <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
      </div>
      <div class="row" id='form_'>
        <div class="col-sm-4">
          <div class="box box-danger">
            <div class="box-body">
              <div class="row">
                <div class="col-sm-12">
                  <table style='width:100%;margin-bottom:20px;'>
                    <tr>
                      <td width='40%'>
                        <img src="http://localhost/honda/assets/panel/images/user/admin-lk.jpg" alt="Gambar Mekanik" class="profile-user-img img-responsive img-circle" style="width: 88px;">
                      </td>
                      <td style='text-align:right;width:60%;vertical-align:top'>
                        <b>Pencapaian</b><br>
                        <span style='font-size:25pt'><?= $row->capaian_ue ?>/ <?= $row->target_ue ?></span>
                      </td>
                    </tr>
                  </table>
                  <span style='font-weight:bold'><?= $row->nama_lengkap ?></span><br>
                  <span><?= dealer()->nama_dealer ?></span><br>
                  <span><?= $row->jabatan ?> <?= $row->id_flp_md ?></span><br>
                </div>
              </div>
            </div>
            <div class="box-footer" style='min-height:117px;'>
              <span style='font-size:10pt'>TUGAS SAAT INI</span><br>
              <span style='font-size:10pt'><b>{{wo_now.id_work_order}}</b></span>
              <span style='font-size:10pt;float:right;padding-right:10px'><b>{{wo_now.id_pit}}</b></span><br>
              <span style='font-size:12pt'>{{wo_now.concat_tipe_pekerjaan}}</span><br>
              <span style='font-size:10pt'>Perkiraan Selesai {{wo_now.estimasi_waktu_kerja}} Menit</span>
            </div>
          </div>
        </div>
        <div class="col-sm-8">
          <div class="box box-danger">
            <div class="box-body">
              <h4>Ringkasan Hari Ini</h4>
              <div class="row">
                <div class="col-sm-6">
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <h3><?= $row->ue_selesai_today ?></h3>
                      <p>Tugas Selesai Hari Ini</p>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <h3><?= round($row->produktif_rata2, 2) ?></h3>
                      <p>Produktivitas Unit Rata-rata</p>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <h3>IDR <?= mata_uang_rp($row->pendapatan_mekanik) ?></h3>
                      <p>Rata-rata Pendapatan/Unit (Teknisi)</p>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <h3>IDR <?= mata_uang_rp($row->pendapatan_ahass) ?></h3>
                      <p>Rata-rata Pendapatan/Unit (AHASS)</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-4">
          <div class="box box-danger">
            <div class="box-body" style='min-height:500px'>
              <h4>Diagram Riwayat Tugas</h4>
              <canvas id="diagramRiwayatServis" width="400" height="400"></canvas>
            </div>
          </div>
        </div>
        <div class="col-sm-8">
          <div class="box box-danger">
            <div class="box-body">
              <input type='hidden' id='id_karyawan_dealer' value='<?= $row->id_karyawan_dealer ?>' />
              <table id="tbl_riwayat" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>ID PKB</th>
                    <th>PKB (ID Work Order)</th>
                    <th>Tipe Pekerjaan</th>
                    <th>Waktu Perkiraan</th>
                    <th>Waktu Realisasi</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
      <script>
        var options = {
          tooltips: {
            enabled: false
          },

        };

        function generateChartPie(params) {
          var ctx = document.getElementById(params.id).getContext('2d');
          var chart = new Chart(ctx, {
            type: 'pie',
            data: {
              labels: params.label,
              datasets: [{
                backgroundColor: params.background,
                data: params.data,
              }],
            },
            options: {
              legend: {
                display: true,
                position: 'bottom',
                reverse: true
              },
              plugins: {
                labels: {
                  render: 'value',
                },
              }
            }
          });
        }

        function createDiagramRiwayatTugas() {
          let values = {
            id_karyawan_dealer: $('#id_karyawan_dealer').val(),
            tanggal: '<?= get_ymd() ?>'
          }
          $.ajax({
            beforeSend: function() {},
            url: '<?= base_url($folder . '/' . $isi . '/fetchDiagramRiwayatServis') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              if (response.status == 'sukses') {
                params = {
                  id: 'diagramRiwayatServis',
                  label: response.label,
                  background: response.background,
                  data: response.data,
                }
                generateChartPie(params)
              } else {
                toastr_warning(response.pesan);
              }
            },
            error: function() {
              toastr_warning("Something Went Wrong !");
            },
          });
        }
      </script>
      <script>
        $(document).ready(function() {
          createDiagramRiwayatTugas();
          var dataTable = $('#tbl_riwayat').DataTable({
            "processing": true,
            "serverSide": true,
            "ordering": false,
            // "scrollX": true,
            "language": {
              "infoFiltered": "",
              "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
            },
            'order': [],
            "lengthChange": false,
            "ajax": {
              url: "<?php echo site_url($folder . '/' . $isi . '/fetchRiwayat'); ?>",
              type: "POST",
              dataSrc: "data",
              data: function(d) {
                d.id_karyawan_dealer = $('#id_karyawan_dealer').val()
                return d;
              },
            },
            "columnDefs": [
              // { "targets":[2],"orderable":false},
              // {
              //   "targets": [4],
              //   "className": 'text-center'
              // },
              // {
              //   "targets": [3, 4, 5],
              //   "className": 'text-right'
              // },
              // // { "targets":[0],"checkboxes":{'selectRow':true}}
              // { "targets":[4],"className":'text-right'}, 
              // // { "targets":[2,4,5], "searchable": false } 
            ],
          });
        });


        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            row: <?= isset($row) ? json_encode($row) : "{}" ?>,
            wo_now: <?= isset($wo_now) ? json_encode($wo_now) : "{}" ?>,
          },
          methods: {
            save_data: function() {
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
              if ($('#form_').valid()) // check if form is valid
              {
                let values = {};
                var form = $('#form_').serializeArray();
                for (field of form) {
                  values[field.name] = field.value;
                }
                if (confirm("Apakah anda yakin ?") == true) {
                  $.ajax({
                    beforeSend: function() {
                      $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                      $('#submitBtn').attr('disabled', true);
                    },
                    url: '<?= base_url($folder . '/' . $isi . '/' . $form) ?>',
                    type: "POST",
                    data: values,
                    cache: false,
                    dataType: 'JSON',
                    success: function(response) {
                      $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                      if (response.status == 'sukses') {
                        window.location = response.link;
                      } else {
                        alert(response.pesan);
                        $('#submitBtn').attr('disabled', false);
                      }
                    },
                    error: function() {
                      alert("Something Went Wrong !");
                      $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                      $('#submitBtn').attr('disabled', false);
                    },
                  });
                } else {
                  return false;
                }
              } else {
                alert('Silahkan isi field required !')
              }
            },
            showDetailTransaksi: function(dtl) {
              console.log(dtl)
            },
            clearDetail: function() {
              this.dtl = {}
            },
            addDetails: function() {
              this.details.push(this.dtl);
              this.clearDetail();
            },
            delDetails: function(index) {
              this.details.splice(index, 1);
            },
          }
        });
      </script>
    <?php } ?>
  </section>
</div>