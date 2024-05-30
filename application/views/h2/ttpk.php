<style type="text/css">
  .myTable1 {
    margin-bottom: 0px;
  }

  .myt {
    margin-top: 0px;
  }

  .isi {
    height: 25px;
    padding-left: 4px;
    padding-right: 4px;
  }
</style>
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
      <li class="">KPB</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>
  <section class="content">
    <?php
    if ($set == "form") {
      $form = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <script>
        Vue.use(VueNumeric.default);
        Vue.filter('toCurrency', function(value) {
          // console.log("type value ke currency filter" ,  value, typeof value, typeof value !== "number");
          if (typeof value !== "number") {
            return value;
          }
          return accounting.formatMoney(value, "", 0, ".", ",");
          return value;
        });
      </script>

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/ttpk">
              <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
              <form id="form_" class="form-horizontal" action="h2/ttpk/save" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                    <div class="col-sm-2">
                      <input type="text" required class="form-control datepicker" placeholder="Start Date" name="start_date" id="start_date" autocomplete="off">
                    </div>
                    <div class="col-sm-2"></div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode AHASS</label>
                    <div class="col-sm-2">
                      <input type="text" readonly @click.prevent="form_.showModalAHASS" class="form-control" placeholder="Kode AHASS" id="kode_ahass">
                      <input type="hidden" name="id_dealer" id="id_dealer">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                    <div class="col-sm-2">
                      <input type="text" class="form-control datepicker" placeholder="End Date" name="end_date" id="end_date" autocomplete="off">
                    </div>
                    <div class="col-sm-2"></div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama AHASS</label>
                    <div class="col-sm-4">
                      <input type="text" required @click.prevent="form_.showModalAHASS" class="form-control" id="nama_ahass" readonly placeholder="Nama AHASS">
                    </div>
                  </div>
                  <div class="form-group">
                    <p align="center">
                      <div class="col-sm-4"></div>
                      <div class="col-sm-2"><button id="btnGenerate" type="button" @click.prevent="form_.generate" class="btn btn-primary btn-flat form-control">Generate</button></div>
                    </p>
                  </div>
                  <br>
                  <table class="table table-bordered table-hover">
                    <thead>
                      <th>No.</th>
                      <th>Kode Dealer</th>
                      <th>Nama Dealer</th>
                      <th>Kode Part</th>
                      <th>Kode Tipe</th>
                      <th>Qty</th>
                      <th>Harga</th>
                      <th>Diskon</th>
                      <th>Harga Setelah Diskon</th>
                      <th>Total Harga</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dtl, index) of details">
                        <td>{{index+1}}</td>
                        <td>{{dtl.kode_dealer_md}}</td>
                        <td>{{dtl.nama_dealer}}</td>
                        <td>{{dtl.id_part}}</td>
                        <td>{{dtl.id_tipe_kendaraan}}</td>
                        <td>{{dtl.qty}}</td>
                        <td>
                          <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" v-model="dtl.harga_material" disabled v-bind:minus="false" :empty-value="0" separator="." />
                        </td>
                        <td>
                          <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" v-model="dtl.diskon" disabled v-bind:minus="false" :empty-value="0" separator="." />
                        </td>
                        <td>
                          <input type="text" :value="hargaDiskon(dtl)|toCurrency" style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" readonly>
                        </td>
                        <td>
                          <input type="text" :value="totalHarga(dtl)|toCurrency" style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" readonly>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="5"><b>Total</b></td>
                        <td>
                          <input type="text" :value="total('qty')|toCurrency" style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" readonly>
                        </td>
                        <td colspan="3"></td>
                        <td>
                          <input type="text" :value="total('grandtotal')|toCurrency" style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" readonly>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-md-offset-4 col-sm-8">
                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <div class="modal fade modalAHASS" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Daftar AHASS</h4>
            </div>
            <div class="modal-body">
              <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_ahass" style="width: 100%">
                <thead>
                  <tr>
                    <th>Kode AHASS</th>
                    <th>Nama AHASS</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <script>
                function pilihAHASS(ahass) {
                  $('#kode_ahass').val(ahass.kode_dealer_md);
                  $('#nama_ahass').val(ahass.nama_dealer);
                  $('#id_dealer').val(ahass.id_dealer);
                }
                $(document).ready(function() {
                  $('#tbl_ahass').DataTable({
                    processing: true,
                    serverSide: true,
                    "language": {
                      "infoFiltered": ""
                    },
                    order: [],
                    ajax: {
                      url: "<?= base_url('h2/claim_kpb/fetch_ahass') ?>",
                      dataSrc: "data",
                      data: function(d) {
                        // d.kode_item     = $('#kode_item').val();
                        return d;
                      },
                      type: "POST"
                    },
                    "columnDefs": [
                      // { "targets":[4],"orderable":false},
                      {
                        "targets": [2],
                        "className": 'text-center'
                      },
                      // { "targets":[4], "searchable": false } 
                    ]
                  });
                });
              </script>
            </div>
          </div>
        </div>
      </div>
      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            details: [],
          },
          methods: {
            total: function(show) {
              var totQty = 0;
              var grandTotal = 0;
              if (this.details.length > 0) {
                for (dtl of this.details) {
                  totQty += dtl.qty;
                  grandTotal += this.totalHarga(dtl);
                }
              }
              if (show == 'qty') {
                return totQty
              }
              if (show == 'grandtotal') {
                return grandTotal
              }
            },
            totalHarga: function(dtl) {
              return parseInt(this.hargaDiskon(dtl) * dtl.qty);
            },
            hargaDiskon: function(dtl) {
              return parseInt(dtl.harga_material - dtl.diskon);
            },
            showModalAHASS: function() {
              // $('#tbl_part').DataTable().ajax.reload();
              $('.modalAHASS').modal('show');
            },
            generate: function() {
              var start_date = $('#start_date').val();
              var end_date = $('#end_date').val();
              var kode_ahass = $('#kode_ahass').val();
              var id_dealer = $('#id_dealer').val();
              if (start_date == '' || end_date == '' || kode_ahass == '') {
                alert('Isi data dengan lengkap !');
                return false;
              }
              values = {
                start_date: start_date,
                end_date: end_date,
                id_dealer: id_dealer,
                kode_ahass: kode_ahass
              }
              $.ajax({
                beforeSend: function() {
                  $('#btnGenerate').attr('disabled', true);
                  form_.details = [];
                },
                url: '<?= base_url('h2/ttpk/generateData') ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  $('#btnGenerate').attr('disabled', false);
                  if (response.length == 0) {
                    alert('Data tidak ditemukan !');
                    return false
                  }
                  for (rsp of response) {
                    form_.details.push(rsp);
                  }
                  // console.log(form_.details);
                },
                error: function() {
                  alert("Something Went Wrong !");
                  $('#btnGenerate').attr('disabled', false);

                },
                statusCode: {
                  500: function() {
                    alert('Fail Error 500 !');
                    $('#btnGenerate').attr('disabled', false);

                  }
                }
              });
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
              $(input).parents('.form-group').addClass('has-error');
            },
            unhighlight: function(input) {
              $(input).parents('.form-group').removeClass('has-error');
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
                  $('#submitBtn').attr('disabled', true);
                },
                url: '<?= base_url('h2/ttpk/' . $form) ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    window.location = response.link;
                  } else {
                    alert(response.pesan);
                  }
                  $('#submitBtn').attr('disabled', false);
                },
                error: function() {
                  alert("failure");
                  $('#submitBtn').attr('disabled', false);

                },
                statusCode: {
                  500: function() {
                    alert('fail');
                    $('#submitBtn').attr('disabled', false);

                  }
                }
              });
            } else {
              return false;
            }
          } else {
            alert('Silahkan isi field required !')
          }
        })
      </script>

    <?php
    } elseif ($set == "upload") {
    ?>

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/ttpk">
              <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
              <form class="form-horizontal" action="h2/ttpk/upload" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Choose File</label>
                    <div class="col-sm-10">
                      <input type="file" accept=".xlsx, .xls" required class="form-control" autofocus name="file_ttpk">
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-2">
                  </div>
                  <div class="col-sm-10">
                    <button type="submit" onclick="return confirm('Are you sure to upload this data?')" name="submit" value="upload" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Start Upload</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->

    <?php
    } elseif ($set == "view") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <!-- <a href="h2/ttpk/add">
              <button class="btn bg-blue btn-flat margin"></i> Create SO KPB</button>
            </a> -->
            <a href="h2/ttpk/upload">
              <button class="btn btn-info btn-flat margin"><i class="fa fa-upload"></i> Upload</button>
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
                    <div class="col-sm-2">
                      <label>Tanggal TTPK</label>
                      <input type="text" class="form-control datepicker" id="tgl_ttpk" name="tgl_ttpk">
                    </div>
                    <!-- <div class="col-sm-2">
                      <label>Status PO KPB</label>
                      <select class='form-control' id='status'>
                        <option value=''>All</option>
                        <option value='input'>Input</option>
                        <option value='approved'>Approved</option>
                        <option value='rejected'>Rejected</option>
                      </select>
                    </div> -->
                  </div>
                </div>
                <div class="box-footer" align='center'>
                  <button class='btn btn-primary' type="button" onclick="search()"><i class="fa fa-search"></i></button>
                  <button class='btn btn-default' type="button" onclick="refresh()"><i class="fa fa-refresh"></i></button>
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
          <table class="table table-striped table-bordered table-hover table-condensed" id="tr_ttpk" style="width: 100%">
            <thead>
              <tr>
                <th>No. TTPK</th>
                <th>Tgl TTPK</th>
                <th>Kode AHASS</th>
                <th>Nama AHASS</th>
                <th>No. Mesin</th>
                <th>No. Rangka</th>
                <th>No. Surat Claim</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tr_ttpk').DataTable({
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
                    d.tgl_ttpk = $('#tgl_ttpk').val();
                    d.status = $('#status').val();
                    return d;
                  },
                  type: "POST"
                },
                // "columnDefs": [{
                //     "targets": [7],
                //     "orderable": false
                //   },
                //   {
                //     "targets": [7],
                //     "className": 'text-center'
                //   },
                //   {
                //     "targets": [5],
                //     "className": 'text-right'
                //   },
                //   // { "targets":[4], "searchable": false } 
                // ],
              });
            });

            function pilihAHASS(ahass) {
              $('#kode_dealer_md').val(ahass.kode_dealer_md);
              $('#nama_dealer').val(ahass.nama_dealer);
              $('#id_dealer').val(ahass.id_dealer);
            }

            function search() {
              $('#tr_ttpk').DataTable().ajax.reload();
            }

            function refresh() {
              $('#kode_dealer_md').val('');
              $('#nama_dealer').val('');
              $('#id_dealer').val('');
              $('#tgl_ttpk').val('');
              $('#status').val('');
              $('#tr_ttpk').DataTable().ajax.reload();
            }
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>

<script>
  function ambil_slot() {
    var lokasi_baru = $("#lokasi_baru").val();
    $.ajax({
      url: "<?php echo site_url('h2/ttpk/get_slot') ?>",
      type: "POST",
      data: "lokasi_baru=" + lokasi_baru,
      cache: false,
      success: function(msg) {
        $("#slot").html(msg);
      }
    })
  }

  function ambil_slot_new() {
    var lokasi_s = $("#lokasi_s").val();
    $.ajax({
      url: "<?php echo site_url('h2/ttpk/get_slot_new') ?>",
      type: "POST",
      data: "lokasi_s=" + lokasi_s,
      cache: false,
      success: function(msg) {
        $("#lokasi_baru").html(msg);
      }
    })
  }

  function ambil_slot_new2() {
    var lokasi_s = $("#lokasi_s").val();
    $.ajax({
      url: "<?php echo site_url('h2/ttpk/get_slot_new2') ?>",
      type: "POST",
      data: "lokasi_s=" + lokasi_s,
      cache: false,
      success: function(msg) {
        $("#slot").html(msg);
      }
    })
  }
</script>
<script type="text/javascript">
  function choose_nosin(nosin) {
    document.getElementById("no_mesin").value = nosin;
    cek_nosin();
    $("#nosin_modal").modal("hide");
  }

  function cek_nosin() {
    var no_mesin = document.getElementById("no_mesin").value;
    //alert(id_po);  
    $.ajax({
      url: "<?php echo site_url('h2/ttpk/cek_nosin') ?>",
      type: "POST",
      data: "no_mesin=" + no_mesin,
      cache: false,
      success: function(msg) {
        data = msg.split("|");
        $("#kode_item").val(data[1]);
        $("#tipe").val(data[2]);
        $("#warna").val(data[3]);
        $("#lokasi_l").val(data[4]);
        $("#lokasi_s").val(data[5]);
        ambil_slot_new();
        ambil_slot_new2();
      }
    })
  }
</script>