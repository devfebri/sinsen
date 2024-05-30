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

<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
  $(document).ready(function() {
    <?php if (isset($row)) { ?>

    <?php } ?>
  })
  Vue.filter('toCurrency', function(value) {
    // console.log("type value ke currency filter" ,  value, typeof value, typeof value !== "number");
    // if (typeof value !== "number") {
    //   return value;
    // }
    return 'Rp. ' + accounting.formatMoney(value, "", 0, ".", ",");
    // return 'Rp. ' + value;
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
      <li class="">KPB</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>
  <section class="content">
    <?php
    if ($set == "insert") {
    ?>

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/claim_kpb">
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
              <form id="form_" class="form-horizontal" action="h2/claim_kpb/save" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode AHASS</label>
                    <div class="col-sm-4">
                      <input type="text" readonly onclick="showModalAHASS()" class="form-control" id="kode_ahass">
                      <input type="hidden" name="id_dealer" id="id_dealer">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama AHASS</label>
                    <div class="col-sm-4">
                      <input type="text" required onclick="showModalAHASS()" class="form-control" id="nama_ahass" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode Claim</label>
                    <div class="col-sm-4">
                      <input id='periode_claim' name='periode_claim' type="text" class="form-control" readonly>
                      <input id='start_date' name='start_date' type="hidden" class="form-control" readonly>
                      <input id='end_date' name='end_date' type="hidden" class="form-control" readonly>
                    </div>
                  </div><br>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                    <div class="col-sm-4">
                      <input type="text" id="no_mesin" onkeypress="getNosin(this)" class="form-control" placeholder="No Mesin">
                    </div>
                    <div class="col-sm-2">
                      <button type="button" id="btnNosin" class="btn btn-flat btn-primary" onclick="showModalNosin()">Choose</button>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="no_rangka" onkeypress="getNosin(this)" placeholder="No Rangka">
                    </div>
                  </div>
                  <button class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
                  <br><br><br>
                  <table class="table table-bordered">
                    <thead>
                      <th>#</th>
                      <th>No. Mesin</th>
                      <th>No. Rangka</th>
                      <th>Tipe Kendaraan</th>
                      <th>No KPB</th>
                      <th>KPB Ke-</th>
                      <th width="12%">Tanggal Beli SMH</th>
                      <th width="10%">KM Service</th>
                      <th width="11%">Tanggal Sevice</th>
                      <th width="5%">Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dtl, index) of details">
                        <td>{{dtl.check}}</td>
                        <td>
                          <input type="text" class="form-control isi" v-model="dtl.no_mesin">
                        </td>
                        <td>
                          <input type="text" class="form-control isi" v-model="dtl.no_rangka">
                        </td>
                        <td>{{dtl.id_tipe_kendaraan}} | {{dtl.tipe_ahm}}</td>
                        <td>
                          <input type="text" class="form-control isi" v-model="dtl.no_kpb">
                        </td>
                        <td>{{dtl.kpb_ke}}</td>
                        <td>
                          <input type="text" class="form-control isi" v-model="dtl.tgl_beli_smh_indo">
                          <!-- <date-picker class='form-control isi' v-model="dtl.tgl_beli_smh_indo" readonly></date-picker> -->
                        </td>
                        <td>
                          <input type="text" class="form-control isi" v-model="dtl.km_service">
                        </td>
                        <td>
                          <input type="text" class="form-control isi" v-model="dtl.tgl_service_indo">
                          <!-- <date-picker class='form-control isi' v-model="dtl.tgl_service_indo" readonly></date-picker> -->
                        </td>
                        <td>
                          <button type="button" class="btn btn-danger btn-flat btn-xs" @click.prevent="form_.delDetails(index)"><i class="fa fa-trash"></i></button>
                          <button v-if="dtl.parts.length>0" type="button" class="btn btn-info btn-flat btn-xs" @click.prevent="form_.showModalPartsOli(index)"><i class="fa fa-eye"></i> Oli</button>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan=9><b>Total : {{details.length}} </b></td>
                      </tr>
                      <tr v-if='form_insert_detail==1'>
                        <td>
                          <input type="text" class="form-control isi" v-model="detail.no_mesin">
                        </td>
                        <td>
                          <input type="text" class="form-control isi" v-model="detail.no_rangka">
                        </td>
                        <td>
                          <input type="text" class="form-control isi" v-model="detail.tipe_ahm">
                        </td>
                        <td>
                          <input type="text" class="form-control isi" v-model="detail.no_kpb">
                        </td>
                        <td>
                          <input type="text" class="form-control isi" v-model="detail.kpb_ke" readonly>
                        </td>
                        <td>
                          <date-picker v-model="detail.tgl_beli_smh_indo" readonly></date-picker>
                        </td>
                        <td>
                          <input type="text" class="form-control isi" v-model="detail.km_service">
                        </td>
                        <td>
                          <date-picker v-model="detail.tgl_service_indo" readonly></date-picker>
                        </td>
                        <td>
                          <button type="button" class="btn btn-danger btn-flat btn-xs" @click.prevent="clearDetail"><i class="fa fa-trash"></i></button>
                          <button type="button" class="btn btn-success btn-flat btn-xs" @click.prevent="addDetails"><i class="fa fa-plus"></i></button>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align="center">
                    <button type="button" id='submitBtn' @click.prevent="submitForm" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <?php
      $data = ['data' => ['modalAHASS', 'modalNoMesinClaimKPB', 'modalPartsOli']];
      $this->load->view('h2/api', $data);
      ?>
      <script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>
      <script type="text/javascript" src="<?= base_url("assets/moment/moment.min.js") ?>"></script>
      <script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
      <link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />

      <script>
        $(function() {
          $('#periode_claim').daterangepicker({
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
        });
        Vue.component('date-picker', {
          template: '<input type="text" v-datepicker class="form-control isi_combo" :value="value" @input="update($event.target.value)">',
          directives: {
            datepicker: {
              inserted(el, binding, vNode) {
                $(el).datepicker({
                  autoclose: true,
                  format: 'dd/mm/yyyy',
                  todayHighlight: false,
                }).on('changeDate', function(e) {
                  vNode.context.$emit('input', e.format(0))
                })
              }
            }
          },
          props: ['value'],
          methods: {
            update(v) {
              this.$emit('input', v)
            }
          }
        })

        function pilihAHASS(ahass) {
          $('#kode_ahass').val(ahass.kode_dealer_md);
          $('#nama_ahass').val(ahass.nama_dealer);
          $('#id_dealer').val(ahass.id_dealer);
        }

        function pilihNosin(el, nosin) {
          for (dtls of form_.details) {
            if (nosin.no_mesin == dtls.no_mesin && nosin.kpb_ke == dtls.kpb_ke) {
              alert('No. mesin =' + nosin.no_mesin + ' dengan KPB Ke-' + nosin.kpb_ke + ' sudah ada !');
              return false
            }
          }
          values = {
            id_work_order: nosin.id_work_order
          }
          $.ajax({
            beforeSend: function() {
              // $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
              $('.btnPilihNosin').attr('disabled', true);
            },
            url: "<?php echo site_url('h2/claim_kpb/getPartOliKPB'); ?>",
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            async: false,
            success: function(response) {
              if (response.status = 'sukses') {
                nosin.oli = response.data;
                setNosinToKPB(nosin);
              }
              $('.btnPilihNosin').attr('disabled', false);
              $('.modalNosin ').modal('hide');
            }
          });
          console.log

        }

        var id_work_order = [];

        function setNosinToKPB(params) {
          for (cek of form_.details) {
            if (cek.no_mesin == params.no_mesin && cek.kpb_ke == params.kpb_ke) {
              alert("No. Mesin : " + cek.no_mesin + " KPB Ke-" + params.kpb_ke + " sudah dipilih !");
              return false;
            }
          }
          form_.form_insert_detail = 1;
          form_.detail.check = params.icon;
          form_.detail.no_mesin = params.no_mesin;
          form_.detail.no_rangka = params.no_rangka;
          form_.detail.tipe_ahm = params.tipe_ahm;
          form_.detail.id_tipe_kendaraan = params.id_tipe_kendaraan;
          form_.detail.tgl_beli_smh = params.tgl_pembelian;
          form_.detail.tgl_service = params.tgl_servis;
          form_.detail.tgl_service_indo = params.tgl_servis_indo;
          form_.detail.tgl_beli_smh_indo = params.tgl_pembelian_indo;
          form_.detail.kpb_ke = params.kpb_ke;
          form_.detail.km_service = params.km_terakhir;
          form_.detail.id_work_order = params.id_work_order;
          form_.detail.parts = params.oli;
          id_work_order.push(params.id_work_order);
          form_.addDetails();
        }

        function tambahTgl(tgl, days) {
          var result = new Date(new Date(tgl).setDate(new Date(tgl).getDate() + days));
          return result.toISOString().substr(0, 10);
        }

        function getNosin(el) {
          set_id = $(el).attr('id');
          var keycode = event.keyCode || event.which;
          if (keycode == '13') {

            var id_dealer = $('#id_dealer').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if (id_dealer == '' || start_date == '' || end_date == '') {
              alert('Silahkan lengkapi data terlebih dahulu ! (Kode AHASS dan Periode Claim)');
              return false;
            }
            var no_mesin = $('#no_mesin').val()
            values = {
              id_dealer: id_dealer,
              start_date: start_date,
              end_date: end_date,
            }
            if (set_id == 'no_mesin') {
              values.no_mesin = $(el).val();
            } else if (set_id == 'no_rangka') {
              values.no_rangka = $(el).val();
            }
            $.ajax({
              url: "<?php echo site_url('h2/claim_kpb/getNosin'); ?>",
              beforeSend: function() {
                $('#btnNosin').attr('disabled', true);
                $('#btnNosin').html('<i class="fa fa-spinner fa-spin">');
              },
              type: "POST",
              data: values,
              cache: false,
              dataType: 'JSON',
              success: function(response) {
                $('#no_mesin').val('');
                $('#no_rangka').val('');
                $('#btnNosin').attr('disabled', false);
                $('#btnNosin').html('Choose');
                if (response.tot > 0) {
                  res = response.nosin;
                  res.oli = response.oli;
                  setNosinToKPB(res);
                } else {
                  alert('Data tidak ditemukan !');
                  return false;
                }
              }
            });
          }
        }

        function getKPB() {
          $.ajax({
            url: "<?php echo site_url('h2/claim_kpb/getKPB'); ?>",
            type: "POST",
            data: form_.detail,
            cache: false,
            success: function(html) {
              // if (html=='kosong') {
              //   alert('Belum ada data master KPB untuk tipe kendaraan ini !')
              //   return false;
              // }
              $('#kpb_ke').html(html);
            }
          });
        }

        var form_ = new Vue({
          el: '#form_',
          data: {
            form_insert_detail: 0,
            mode: '<?= $mode ?>',
            detail: {
              no_mesin: '',
              no_rangka: '',
              id_tipe_kendaraan: '',
              tipe_ahm: '',
              no_kpb: 0,
              kpb_ke: '',
              tgl_beli_smh: '',
              km_service: '',
              tgl_service: '',
              id_work_order: '',
              parts: []
            },
            kpb_detail: [],
            details: [],
          },
          methods: {
            setKPB: function() {
              this.kpb_detail = [];
              this.detail.km_service = '';
              this.detail.tgl_service = '';
              var element = $('#kpb_ke').find('option:selected');
              var data = JSON.parse(element.attr("data"));
              this.kpb_detail.push(data);
              // console.log(this.kpb_detail);
            },
            clearDetail: function() {
              this.detail = {
                no_mesin: '',
                no_rangka: '',
                id_tipe_kendaraan: '',
                tipe_ahm: '',
                no_kpb: 0,
                kpb_ke: '',
                tgl_beli_smh: '',
                km_service: '',
                tgl_service: '',
              }
            },
            addDetails: function() {
              if (this.detail.kpb_ke === '' || this.detail.km_service === '' || this.detail.tgl_service === '') {
                alert('Silahkan isi data dengan lengkap !');
                return false;
              }
              this.detail.kpb_detail = this.kpb_detail;
              // console.log(this.detail);
              // return false;
              this.details.push(this.detail);
              this.clearDetail();
              this.kpb_detail = [];
              this.form_insert_detail = 0;
            },

            delDetails: function(index) {
              this.details.splice(index, 1);
            },

            showModalPartsOli: function(index) {
              $('#modalPartsOli').modal('show');
              app_modalPartsOli.details = form_.details[index];
            },

            submitForm: function() {
              let values = {
                details: form_.details,
              };
              let form = $('#form_').serializeArray();
              for (field of form) {
                values[field.name] = field.value;
              }
              if (confirm("Apakah anda yakin ?") == true) {
                $.ajax({
                  beforeSend: function() {
                    $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                    $('#submitBtn').attr('disabled', true);
                  },
                  url: '<?= base_url('h2/' . $isi . '/save') ?>',
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
                    alert("failure");
                    $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                    $('#submitBtn').attr('disabled', false);

                  },
                  statusCode: {
                    500: function() {
                      alert('fail');
                      $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                      $('#submitBtn').attr('disabled', false);

                    }
                  }
                });
              } else {
                return false;
              }
            }
          },
          watch: {
            detail: function() {
              // alert('dd');
            }
          },
          computed: {
            totPembayaran: function() {
              total = 0;
              for (dtl of this.details) {
                total += dtl.nominal;
              }
              if (isNaN(total)) return 0;
              // return total.toFixed(1);
              return total;
            }
          },
        });

        Vue.component('select2', {
          props: ['options', 'value'],
          template: '<select class="form-control"><option value="">--choose--</option></select>',
          mounted: function() {
            var vm = this
            $(this.$el)
              // init select2
              .select2({
                data: this.options
              })
              .val(this.value)
              .trigger('change')
              // emit event on change.
              .on('change', function() {
                vm.$emit('input', this.value)
              })
          },
          watch: {
            value: function(value) {
              // update value
              $(this.$el)
                .val(value)
                .trigger('change')
            },
            options: function(options) {
              // update options
              $(this.$el).empty().select2({
                data: options
              })
            }
          },
          destroyed: function() {
            $(this.$el).off().select2('destroy')
          }
        })

        function getTipeAHM() {
          var tipe_ahm = $("#id_tipe_kendaraan").select2().find(":selected").data("tipe_ahm");
          $('#tipe_ahm').val(tipe_ahm);
        }
      </script>


    <?php
    } elseif ($set == "view") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/claim_kpb/add">
              <button class="btn bg-blue btn-flat margin"></i> Add New</button>
            </a>
            <a href="h2/claim_kpb/generate_skpb">
              <button class="btn bg-maroon btn-flat margin"></i> Generate SKPB</button>
            </a>
            <a href="h2/claim_kpb/verifikasi">
              <button class="btn bg-green btn-flat margin"></i> Verifikasi</button>
            </a>
            <a href="h2/claim_kpb/create_tagihan_dealer">
              <button class="btn btn-warning btn-flat margin"></i> Create Tagihan Dealer</button>
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
                    <div class="col-sm-3">
                      <label>Nama AHASS</label>
                      <input type="text" class="form-control" id="nama_dealer" name="nama_dealer" readonly onclick="showModalAHASS()" placeholder='Klik Untuk Memilih'>
                    </div>
                    <div class="col-sm-2">
                      <label>No. Mesin</label>
                      <input type="text" class="form-control" id="no_mesin" name="no_mesin">
                    </div>
                    <div class="col-sm-1">
                      <label>KPB Ke-</label>
                      <select class='form-control' id='kpb_ke'>
                        <option value=''>All</option>
                        <option value=1>1</option>
                        <option value=2>2</option>
                        <option value=3>3</option>
                        <option value=4>4</option>
                      </select>
                    </div>
                    <div class="col-sm-2">
                      <label>5 Digit No. Mesin</label>
                      <input type="text" class="form-control" id="no_mesin_5">
                    </div>
                    <div class="col-sm-2">
                      <label>Status KPB</label>
                      <select class='form-control' id='status'>
                        <option value=''>All</option>
                        <option value='input'>Input</option>
                        <option value='approved'>Approved by AHM</option>
                        <option value='rejected'>Rejected by AHM</option>
                      </select>
                    </div>
                    <div class="col-sm-2">
                      <label>Tgl. Service Awal</label>
                      <input type="text" class="form-control datepicker" name="tgl_service_awal" id="tgl_service_awal">
                    </div>
		                <div class="col-sm-2">
                      <label>Tgl. Service Akhir</label>
                      <input type="text" class="form-control datepicker" name="tgl_service_akhir" id="tgl_service_akhir">
                    </div>
                  </div>
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
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_claim_kpb" style="width: 100%">
            <thead>
              <tr>
                <th>Kode AHASS</th>
                <th>Nama AHASS</th>
                <th>No Mesin</th>
                <th>No Rangka</th>
                <th>No KPB</th>
                <th>Tipe Kendaraan</th>
                <th>KPB Ke-</th>
                <th>Tanggal Beli SMH</th>
                <th>KM Service</th>
                <th>Tanggal Service</th>
                <th>Tanggal Entry/Upload</th>
                <th>Status KPB</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_claim_kpb').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": "",
                  "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                },
                order: [],
                ajax: {
                  url: "<?= base_url('h2/claim_kpb/fetch_claim_kpb') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    d.id_dealer = $('#id_dealer').val();
                    d.no_mesin = $('#no_mesin').val();
                    d.kpb_ke = $('#kpb_ke').val();
                    d.end_date = $("#end_date").val();
                    d.start_date = $("#start_date").val();
                    d.no_mesin_5 = $("#no_mesin_5").val();
                    d.tgl_service_awal = $("#tgl_service_awal").val();
		                d.tgl_service_akhir = $("#tgl_service_akhir").val();
                    d.status = $("#status").val();
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [{
                    "targets": [12],
                    "orderable": false
                  },
                  {
                    "targets": [11, 12],
                    "className": 'text-center'
                  },
                  // {
                  //   "targets": [4],
                  //   "className": 'text-right'
                  // },
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
              $('#tbl_claim_kpb').DataTable().ajax.reload();
            }

            function refresh() {
              $('#kode_dealer_md').val('');
              $('#nama_dealer').val('');
              $('#id_dealer').val('');
              $('#no_mesin').val('');
              $('#kpb_ke').val('');
            }
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    } elseif ($set == "generate_skpb") {
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/claim_kpb">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
            <?php /* <a href="h2/claim_kpb/generate_skpb_reject" class="btn btn-flat margin btn-primary">Generate SKPB Reject</a> */ ?>
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
              <form id="form_" class="form-horizontal" action="h2/claim_kpb/save_generate" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                    <div class="col-sm-2">
                      <input type="text" id="start_date" autocomplete="off" required class="form-control datepicker" placeholder="Start Date" name="start_date">
                    </div>
                    <div class="col-sm-2"></div>
                    <label for="inputEmail3" class="col-sm-2 control-label">5 Digit Nomor Mesin</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" name="no_mesin_5" id="no_mesin_5" onchange="form_.getServiceKe()">
                        <?php if ($no_mesin->num_rows() > 0) : ?>
                          <option value="">--choose--</option>
                          <?php foreach ($no_mesin->result() as $ns) : ?>
                            <option value="<?= $ns->no_mesin ?>"><?= $ns->no_mesin ?></option>
                          <?php endforeach ?>
                        <?php endif ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                    <div class="col-sm-2">
                      <input type="text" autocomplete="off" class="form-control datepicker" id="end_date" placeholder="End Date" name="end_date">
                    </div>
                    <div class="col-sm-2"></div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Service Ke-</label>
                    <div class="col-sm-4">
                      <select class="form-control" name="service_ke" id="service_ke"></select>
                    </div>


                  </div>
                  <!-- <div class="form-group">
                    <div class="col-sm-12" align="center">
                      <button type="button" id="generateBtn" @click.prevent="form_.generate()" class="btn btn-primary btn-flat form-control">Generate</button>
                    </div>
                  </div> -->
                  <div class="form-group">
                    <div class="col-sm-12" align="center">
                      <button type="button" id="generateBtn" @click.prevent="form_.generate()" class="btn btn-primary btn-flat">Generate Data</button>
                    </div>
                  </div>
                  <button class="btn btn-success btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
                  <br><br><br>
                  <table id="" class="table table-hover">
                    <thead>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>Tipe Kendaraan</th>
                      <th>No KPB</th>
                      <th>Tanggal Beli SMH</th>
                      <th>KM Service</th>
                      <th>Tanggal Service</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dtl, index) of details">
                        <td>{{dtl.no_mesin}}
                          <input type="hidden" name="id_claim_kpb[]" v-model="dtl.id_claim_kpb">
                        </td>
                        <td>{{dtl.no_rangka}}</td>
                        <td>{{dtl.tipe_ahm}} </td>
                        <td>{{dtl.no_kpb}}</td>
                        <td>{{dtl.tgl_beli_smh}}</td>
                        <td>{{dtl.km_service}}</td>
                        <td>{{dtl.tgl_service}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align="center">
                    <button type="button" @click.prevent="form_.submit()" id="btnDownload" name="save" value="save" class="btn btn-info btn-flat" disabled><i class="fa fa-download"></i> Download File .SKPB</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <script>
        function tambahTgl(tgl, days) {
          var result = new Date(new Date(tgl).setDate(new Date(tgl).getDate() + days));
          return result.toISOString().substr(0, 10);
        }

        var form_ = new Vue({
          el: '#form_',
          data: {
            details: [],
          },
          methods: {
            getServiceKe: function() {
              values = {
                no_mesin_5: $('#no_mesin_5').val(),
              }
              $.ajax({
                url: "<?php echo site_url('h2/claim_kpb/getKPB'); ?>",
                type: "POST",
                data: values,
                cache: false,
                // dataType:'JSON'
                success: function(html) {
                  if (html != 'kosong') {
                    $('#service_ke').html(html);
                  } else {
                    alert('Data servis tidak ditemukan !');
                  }
                }
              });
            },
            generate: function() {
              var no_mesin_5 = $('#no_mesin_5').val();
              var service_ke = $('#service_ke').val();
              var start_date = $('#start_date').val();
              var end_date = $('#end_date').val();
              if (no_mesin_5 == '' || service_ke == '' || start_date == '' || end_date == '') {
                alert('Isi data dengan lengkap !')
                return false;
              }
              values = {
                no_mesin_5: no_mesin_5,
                service_ke: service_ke,
                start_date: start_date,
                end_date: end_date,
              }
              $.ajax({
                url: "<?php echo site_url('h2/claim_kpb/generate'); ?>",
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  form_.details = [];
                  if (response.details.length > 0) {
                    for (dtl of response.details) {
                      form_.details.push(dtl);
                    }
                    $('#start_date').attr('readonly', true);
                    $('#end_date').attr('readonly', true);
                    $('#service_ke').attr('disabled', true);
                    $('#no_mesin_5').attr('disabled', true);
                    $('#generateBtn').attr('disabled', true);
                    $('#btnDownload').attr('disabled', false);
                  } else {
                    alert('Data tidak ditemukan !');
                  }
                }
              });
            },
            // submit: function() {
            //   $.ajax({
            //     url:"<?php echo site_url('h2/claim_kpb/save_generate'); ?>",
            //     type:"POST",
            //     data: $('#form').serialize(),
            //     cache:false,
            //     // dataType:'JSON',
            //     success:function(response){
            //       $('#btnDownload').attr('disabled',false);

            //     }
            //   });
            // }
            submit: function() {
              $('#service_ke').attr('disabled', false);
              $('#no_mesin_5').attr('disabled', false);
              $('#service_ke').attr('readonly', true);
              $('#no_mesin_5').attr('readonly', true);
              $('#btnDownload').attr('disabled', true);
              $('#form_').submit();
              $('#service_ke').attr('disabled', false);
              $('#no_mesin_5').attr('disabled', false);
            }
          }
        })
      </script>
    <?php
    } elseif ($set == "generate_skpb_reject") {
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/claim_kpb">
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
              <form id="form_" class="form-horizontal" action="h2/claim_kpb/save_generate_reject" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Surat Claim</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" name="no_surat_claim" id="no_surat_claim" onchange="namaFileLama()">
                        <?php if ($surat->num_rows() > 0) : ?>
                          <option value="">--choose--</option>
                          <?php foreach ($surat->result() as $ns) : ?>
                            <option value="<?= $ns->no_surat_claim ?>" data-nama_file="<?= $ns->nama_file ?>"><?= $ns->no_surat_claim ?></option>
                          <?php endforeach ?>
                        <?php endif ?>
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama File Lama</label>
                    <div class="col-sm-4">
                      <input type="text" autocomplete="off" class="form-control" id="nama_file_lama" placeholder="Nama File Lama" name="nama_file_lama" readonly>
                    </div>
                  </div>
                  <!-- <div class="form-group">
                    <div class="col-sm-12" align='center'>
                      <button type="button" id="generateBtn"  class="btn btn-primary btn-flat form-control">Generate</button>
                    </div>
                  </div> -->
                  <div class="col-sm-12" align='center' style='padding-bottom:15px'>
                    <button type="button" @click.prevent="form_.generate()" id="generateBtn" class="btn btn-primary btn-flat">Generate</button>
                  </div>
                  <button class="btn btn-success btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
                  <br><br><br>
                  <table class="table table-hover">
                    <thead>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>Tipe Kendaraan</th>
                      <th>No KPB</th>
                      <th>KPB Ke-</th>
                      <th>Tanggal Beli SMH</th>
                      <th>KM Service</th>
                      <th>Tanggal Sevice</th>
                      <th>Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dtl, index) of details">
                        <td>{{dtl.no_mesin}}
                          <input type="hidden" name="id_claim_kpb[]" v-model="dtl.id_claim_kpb">
                        </td>
                        <td>{{dtl.no_rangka}}</td>
                        <td>{{dtl.tipe_ahm}} </td>
                        <td>{{dtl.no_kpb}}
                          <input type="hidden" name="no_kpb[]" v-model="dtl.no_kpb">
                        </td>
                        <td>{{dtl.kpb_ke}}
                          <input type="hidden" name="kpb_ke[]" v-model="dtl.kpb_ke">
                        </td>
                        <td>{{dtl.tgl_beli_smh}}
                          <input type="hidden" name="tgl_beli_smh[]" v-model="dtl.tgl_beli_smh">
                        </td>
                        <td>{{dtl.km_service}}
                          <input type="hidden" name="km_service[]" v-model="dtl.km_service">
                        </td>
                        <td>{{dtl.tgl_service}}
                          <input type="hidden" name="tgl_service[]" v-model="dtl.tgl_service">
                        </td>
                        <td>
                          <button type="button" class="btn btn-warning btn-flat btn-xs" @click.prevent="form_.editDetails(index)"><i class="fa fa-edit"></i></button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align='center'>
                    <button type="button" @click.prevent="form_.submit()" id="btnDownload" name="save" value="save" class="btn btn-info btn-flat" disabled><i class="fa fa-download"></i> Update & Download File .SKPB</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalEditDetail">
          <div class="modal-dialog" style="width:95%">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Edit Detail</h4>
              </div>
              <div class="modal-body">
                <div class="form-horizontal">
                  <input type="hidden" required class="form-control" id="index_edit" autocomplete="off" readonly>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Mesin</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" id="no_mesin_edit" autocomplete="off" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Rangka</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" id="no_rangka_edit" autocomplete="off" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" id="tipe_ahm_edit" autocomplete="off" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. KPB</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" id="no_kpb_edit" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">KPB Ke</label>
                    <div class="col-sm-4">
                      <select class='form-control' id='kpb_ke_edit'>
                        <option value=1>1</option>
                        <option value=2>2</option>
                        <option value=3>3</option>
                        <option value=4>4</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Beli SMH</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control datepicker" id="tgl_beli_smh_edit" autocomplete="off" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">KM Service</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" id="km_service_edit" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Service</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control datepicker" id="tgl_service_edit" autocomplete="off" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class='modal-footer'>
                <div class="col-sm-12" align='center'>
                  <button type="button" onclick="saveEdit()" id="btnSaveEdit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <script>
        function tambahTgl(tgl, days) {
          var result = new Date(new Date(tgl).setDate(new Date(tgl).getDate() + days));
          return result.toISOString().substr(0, 10);
        }

        function namaFileLama() {
          var nama_file = $("#no_surat_claim").select2().find(":selected").data("nama_file");
          $('#nama_file_lama').val(nama_file)
        }

        function saveEdit() {
          let index = $('#index_edit').val();
          form_.details[index].no_kpb = $('#no_kpb_edit').val();
          form_.details[index].kpb_ke = $('#kpb_ke_edit').val();
          form_.details[index].tgl_beli_smh = $('#tgl_beli_smh_edit').val();
          form_.details[index].km_service = $('#km_service_edit').val();
          form_.details[index].tgl_service = $('#tgl_service_edit').val();
          $('#modalEditDetail').modal('hide');
        }
        var form_ = new Vue({
          el: '#form_',
          data: {
            details: [],
          },
          methods: {
            editDetails: function(idx) {
              let data = this.details[idx];
              $('#index_edit').val(idx);
              $('#no_mesin_edit').val(data.no_mesin);
              $('#no_rangka_edit').val(data.no_rangka);
              $('#tipe_ahm_edit').val(data.tipe_ahm);
              $('#no_kpb_edit').val(data.no_kpb);
              $('#kpb_ke_edit').val(data.kpb_ke);
              $('#tgl_beli_smh_edit').val(data.tgl_beli_smh);
              $('#km_service_edit').val(data.km_service);
              $('#tgl_service_edit').val(data.tgl_service);
              $('#modalEditDetail').modal('show');
            },
            generate: function() {
              var no_surat_claim = $('#no_surat_claim').val();
              // alert(no_surat_claim);

              if (no_surat_claim == null || no_surat_claim == '') {
                alert('Isi data dengan lengkap !')
                return false;
              }
              values = {
                no_surat_claim: no_surat_claim
              }
              $.ajax({
                url: "<?php echo site_url('h2/claim_kpb/generateSkpbReject'); ?>",
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  form_.details = [];
                  if (response.details.length > 0) {
                    for (dtl of response.details) {
                      form_.details.push(dtl);
                    }
                    $('#no_surat_claim').attr('readonly', true);
                    $('#generateBtn').attr('disabled', true);
                    $('#btnDownload').attr('disabled', false);
                  }
                }
              });
            },
            submit: function() {
              $('#btnDownload').attr('disabled', true);
              $('#form_').submit();
            }
          }
        })
      </script>
    <?php
    } elseif ($set == "edit_pernosin") {
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/claim_kpb">
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
              <form id="form_" class="form-horizontal" action="h2/claim_kpb/save_edit" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <button class="btn btn-success btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
                  <br><br><br>
                  <table id="example4" class="table table-hover">
                    <thead>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>Tipe Kendaraan</th>
                      <th>No KPB</th>
                      <th>KPB Ke-</th>
                      <th>Tanggal Beli SMH</th>
                      <th>KM Service</th>
                      <th>Tanggal Sevice</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dtl, index) of details">
                        <td>{{dtl.no_mesin}}
                          <input type="hidden" name="id_claim_kpb" v-model="dtl.id_claim_kpb">
                        </td>
                        <td>
                          {{dtl.no_rangka}}
                        </td>
                        <td>{{dtl.id_tipe_kendaraan}} | {{dtl.tipe_ahm}}
                        </td>
                        <td>
                          <input type="text" name="no_kpb" class="form-control isi" v-model="dtl.no_kpb">
                        </td>
                        <td>{{dtl.kpb_ke}}
                        </td>
                        <td>
                          <date-picker class="form-control isi" v-model="dtl.tgl_beli_smh"></date-picker>
                        </td>
                        <td>
                          <input type="text" name="km_service" class="form-control isi" v-model="dtl.km_service" v-on:keyup="form_.cekKm(index)">
                        </td>
                        <td>
                          <date-picker class="form-control isi" v-model="dtl.tgl_service" @change="form_.cekTglService(index)"></date-picker>
                          <!-- <input type="date" @change="form_.cekTglService(index)" name="tgl_service" class="form-control isi" v-model="dtl.tgl_service" /> -->
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-4">
                  </div>
                  <div class="col-sm-4">
                    <button type="submit" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <script>
        function tambahTgl(tgl, days) {
          var result = new Date(new Date(tgl).setDate(new Date(tgl).getDate() + days));
          return result.toISOString().substr(0, 10);
        }

        var form_ = new Vue({
          el: '#form_',
          data: {
            kpb_detail: <?= json_encode($kpb_detail) ?>,
            details: <?= isset($dt_result) ? json_encode($dt_result) : '[]' ?>,
          },
          methods: {
            cekTglService: function(index = null) {
              this.disabledSubmit()
              if (index != null) {
                if (form_.details[0].tgl_service < form_.details[0].tgl_beli_smh) {
                  alert('Tanggal service tidak boleh lebih kecil dari tanggal beli !');
                  form_.details[0].tgl_service = '';
                  this.disabledSubmit();
                  return false;
                } else {
                  this.enabledSubmit()
                }

                var tgl_service = form_.details[0].tgl_service;
                var tgl_beli_smh = form_.details[0].tgl_beli_smh;
                var batas_maks_kpb = parseInt(form_.kpb_detail.batas_maks_kpb);
                var toleransi = parseInt(form_.kpb_detail.toleransi);
                var tot_batas = batas_maks_kpb + toleransi;
                var tgl_maks = tambahTgl(tgl_beli_smh, tot_batas);
                if (tgl_service > tgl_maks) {
                  form_.details[0].tgl_service = '';
                  alert('Tanggal Service tidak boleh melebihi batas KPB !');
                  this.disabledSubmit();
                  return false;
                } else {
                  this.enabledSubmit();
                }
              }
            },
            cekKm: function(index = null) {
              if (index != null) {
                this.disabledSubmit();
                var kpb_km = parseInt(this.kpb_detail.km);
                var km_service = parseInt(this.details[index].km_service);
                if (kpb_km < km_service) {
                  alert('KM Service tidak boleh melebihi batas KPB !');
                  this.details[index].km_service = '';
                  $('#submitBtn').attr('disabled', true);
                  return false;
                } else {
                  $('#submitBtn').attr('disabled', false);
                }
              }
            },
            disabledSubmit: function() {
              $('#submitBtn').attr('disabled', true);
            },
            enabledSubmit: function() {
              $('#submitBtn').attr('disabled', false);
            }
          }
        })
      </script>
    <?php
    } elseif ($set == "verifikasi") {
    ?>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/claim_kpb">
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
            <table id="tbl_verifikasi" class="table table-hover">
              <thead>
                <tr>
                  <th style="width: 30%">Nama File</th>
                  <th style="width: 20%">No. Surat Claim</th>
                  <th style="width: 10%">Tgl. Generate</th>
                  <th style="width: 10%">No Mesin</th>
                  <th style="width: 10%">KPB Ke</th>
                  <th style="width: 10%">Aksi</th>
                </tr>
              </thead>
            </table>
            <script>
              $(document).ready(function() {
                $('#tbl_verifikasi').DataTable({
                  processing: true,
                  serverSide: true,
                  "language": {
                    "infoFiltered": "",
                    "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                  },
                  order: [],
                  ajax: {
                    url: "<?= base_url('h2/claim_kpb/fetch_verifikasi') ?>",
                    dataSrc: "data",
                    data: function(d) {
                      // d.id_dealer = $('#id_dealer').val();
                      // d.no_mesin = $('#no_mesin').val();
                      // d.kpb_ke = $('#kpb_ke').val();
                      // d.end_date = $("#end_date").val();
                      // d.start_date = $("#start_date").val();
                      return d;
                    },
                    type: "POST"
                  },
                  "columnDefs": [{
                      "targets": [3,4,5],
                      "orderable": false
                    },
                    {
                      "targets": [3, 4],
                      "className": 'text-center'
                    },
                    // {
                    //   "targets": [4],
                    //   "className": 'text-right'
                    // },
                    // { "targets":[4], "searchable": false } 
                  ],
                });
              });

              function search() {
                $('#tbl_verifikasi').DataTable().ajax.reload();
              }

              function refresh() {
                $('#kode_dealer_md').val('');
                $('#nama_dealer').val('');
                $('#id_dealer').val('');
                $('#no_mesin').val('');
                $('#kpb_ke').val('');
              }
            </script>
          </div><!-- /.box-body -->
        </div>
      </div><!-- /.box -->
    <?php
    } elseif ($set == "approve_ahm") {
    ?>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/claim_kpb/verifikasi">
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
              <form id="form_" class="form-horizontal" action="h2/claim_kpb/save_approve" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <input type="hidden" name="no_generate" value="<?= $row->no_generate ?>">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama File</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" value="<?= $row->nama_file ?>" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Surat Claim</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" value="<?= $row->no_surat_claim ?>" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl Generate</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" value="<?= $row->tgl_generate ?>" disabled>
                    </div>
                  </div>
                  <button class="btn btn-success btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
                  <br><br><br>
                  <table id="example" class="table table-hover">
                    <thead>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>Tipe Kendaraan</th>
                      <th>No KPB</th>
                      <th>KPB Ke-</th>
                      <th>Tanggal Beli SMH</th>
                      <th>KM Service</th>
                      <th>Tanggal Sevice</th>
                    </thead>
                    <tbody>
                      <?php foreach ($result->result() as $rs) :
                        $tp = $this->db->get_where('ms_tipe_kendaraan', ['id_tipe_kendaraan' => $rs->id_tipe_kendaraan])->row();
                      ?>
                        <tr>
                          <td><?= $rs->no_mesin ?></td>
                          <td><?= $rs->no_rangka ?></td>
                          <td><?= $tp->id_tipe_kendaraan ?> | <?= $tp->tipe_ahm ?></td>
                          <td><?= $rs->no_kpb ?></td>
                          <td><?= $rs->kpb_ke ?></td>
                          <td><?= $rs->tgl_beli_smh ?></td>
                          <td><?= $rs->km_service ?></td>
                          <td><?= $rs->tgl_service ?></td>
                        </tr>
                      <?php endforeach ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align="center">
                    <button type="submit" id="submitBtn" onclick="return confirm('Apakah anda yakin ?')" name="save" value="save" class="btn btn-primary btn-flat"><i class="fa fa-check"></i> Approve</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
    <?php
    } elseif ($set == "reject_ahm") {
    ?>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/claim_kpb/verifikasi">
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
              <form id="form_" class="form-horizontal" action="h2/claim_kpb/save_reject" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <input type="hidden" name="no_generate" value="<?= $row->no_generate ?>">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama File</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" value="<?= $row->nama_file ?>" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Surat Claim</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" value="<?= $row->no_surat_claim ?>" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl Generate</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" value="<?= $row->tgl_generate ?>" disabled>
                    </div>
                  </div>
                  <button class="btn btn-success btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
                  <br><br><br>
                  <table id="example2" class="table table-hover">
                    <thead>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>Tipe Kendaraan</th>
                      <th>No KPB</th>
                      <th>KPB Ke-</th>
                      <th>Tanggal Beli SMH</th>
                      <th>KM Service</th>
                      <th>Tanggal Sevice</th>
                      <th></th>
                    </thead>
                    <tbody>
                      <?php foreach ($result->result() as $key => $rs) :
                        $tp = $this->db->get_where('ms_tipe_kendaraan', ['id_tipe_kendaraan' => $rs->id_tipe_kendaraan])->row();
                      ?>
                        <tr>
                          <td><?= $rs->no_mesin ?></td>
                          <td><?= $rs->no_rangka ?></td>
                          <td><?= $tp->id_tipe_kendaraan ?> | <?= $tp->tipe_ahm ?></td>
                          <td><?= $rs->no_kpb ?></td>
                          <td><?= $rs->kpb_ke ?></td>
                          <td><?= $rs->tgl_beli_smh ?></td>
                          <td><?= $rs->km_service ?></td>
                          <td><?= $rs->tgl_service ?></td>
                          <td>
                            <input type="hidden" id="id_detail_<?= $key ?>" name="id_detail[]" value="<?= $rs->id_detail ?>">
                            <input type="checkbox" id="chk_reject_<?= $key ?>" class="chk_reject" name="chk_reject[]" value="<?= $rs->id_detail ?>">
                          </td>
                        </tr>
                      <?php endforeach ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align='center'>
                    <button type="submit" id="submitBtn" onclick="return confirm('Apakah anda yakin ?')" name="save" value="save" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Reject</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
    <?php
    } elseif ($set == "detail_verifikasi") {
    ?>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/claim_kpb/verifikasi">
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
              <form id="form_" class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <input type="hidden" name="no_generate" value="<?= $row->no_generate ?>">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama File</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" value="<?= $row->nama_file ?>" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Surat Claim</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" value="<?= $row->no_surat_claim ?>" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl Generate</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" value="<?= $row->tgl_generate ?>" disabled>
                    </div>
                  </div>
                  <button class="btn btn-success btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
                  <br><br><br>
                  <table id="example" class="table table-hover">
                    <thead>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>Tipe Kendaraan</th>
                      <th>No KPB</th>
                      <th>KPB Ke-</th>
                      <th>Tanggal Beli SMH</th>
                      <th>KM Service</th>
                      <th>Tanggal Sevice</th>
                      <th>Status</th>
                    </thead>
                    <tbody>
                      <?php foreach ($result->result() as $key => $rs) :
                        $tp = $this->db->get_where('ms_tipe_kendaraan', ['id_tipe_kendaraan' => $rs->id_tipe_kendaraan])->row();
                        $status = '';
                        if ($rs->status == 'approved') {
                          $status = '<label class="label label-success">' . strtoupper($rs->status) . '</label>';
                        }
                        if ($rs->status == 'reject') {
                          $status = '<label class="label label-danger">' . strtoupper($rs->status) . '</label>';
                        }
                      ?>
                        <tr>
                          <td><?= $rs->no_mesin ?></td>
                          <td><?= $rs->no_rangka ?></td>
                          <td><?= $tp->id_tipe_kendaraan ?> | <?= $tp->tipe_ahm ?></td>
                          <td><?= $rs->no_kpb ?></td>
                          <td><?= $rs->kpb_ke ?></td>
                          <td><?= $rs->tgl_beli_smh ?></td>
                          <td><?= $rs->km_service ?></td>
                          <td><?= $rs->tgl_service ?></td>
                          <td><?= $status ?></td>
                        </tr>
                      <?php endforeach ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
    <?php
    } elseif ($set == "tagihan_dealer") {
      $form = '';
      if ($mode == 'insert') {
        $form = 'save_tagihan_dealer';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <script>
        Vue.use(VueNumeric.default);
        Vue.filter('toCurrency', function(value) {
          // console.log("type value ke currency filter" ,  value, typeof value, typeof value !== "number");
          // if (typeof value !== "number") {
          //   return value;
          // }
          return 'Rp. ' + accounting.formatMoney(value, "", 0, ".", ",");
          // return 'Rp. ' + value;
        });
      </script>

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/claim_kpb">
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
              <form id="form_" class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode Service</label>
                    <div class="col-sm-2">
                      <input type="text" required class="form-control datepicker" placeholder="Start Date" name="start_date" id="start_date" autocomplete="off">
                    </div>
                    <div class="col-sm-2">
                      <input type="text" class="form-control datepicker" placeholder="End Date" name="end_date" id="end_date" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode AHASS</label>
                    <div class="col-sm-2">
                      <input type="text" readonly onclick="showModalAHASS()" class="form-control" id="kode_ahass">
                      <input type="hidden" name="id_dealer" id="id_dealer">
                    </div>
                    <div class="col-sm-4">
                      <input type="text" required onclick="showModalAHASS()" class="form-control" id="nama_ahass" readonly placeholder="Nama AHASS">
                    </div>
                  </div>
                  <div class="form-group" style="text-align: center;">
                    <div class="col-md-offset-5 col-sm-2">
                      <button id="btnGenerateTagihan" type="button" @click.prevent="form_.generate_tagihan" class="btn btn-primary btn-flat form-control">Generate Tagihan</button>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Detail</button><br><br>
                  </div>
                  <div class="col-sm-12" style="padding-bottom: 10px">
                    <label class="label label-success" style="font-size: 10pt;display: inline-block;margin-right: 4px" v-for="(dtl, index) of no_mesin_5">{{dtl.no_mesin_5}} {{dtl.harga_material | toCurrency}}</label>
                  </div>
                  <div class="col-sm-12">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <th>No.</th>
                        <th>Kode AHASS</th>
                        <th>Nama Dealer</th>
                        <th style="text-align:right">Total Jasa</th>
                        <th style="text-align:right">Total Oli</th>
                        <th style="text-align:right">Total PPN</th>
                        <th style="text-align:right">Total PPh</th>
                        <th style="text-align:right">Total</th>
                        <th style="text-align:center">Qty</th>
                      </thead>
                      <tbody>
                        <tr v-for="(dtl, index) of details">
                          <td>{{index+1}}</td>
                          <td>{{dtl.kode_dealer_md}}</td>
                          <td>{{dtl.nama_dealer}}</td>
                          <td align='right'>{{dtl.tot_jasa | toCurrency}}</td>
                          <td align='right'>{{dtl.tot_oli | toCurrency}}</td>
                          <td align='right'>{{dtl.tot_ppn | toCurrency}}</td>
                          <td align='right'>{{dtl.tot_pph | toCurrency}}</td>
                          <td align='right'>{{dtl.total | toCurrency}}</td>
                          <td align='center'>{{dtl.tot_qty}}</td>
                        </tr>
                      </tbody>
                      <!-- <tfoot>
                    <tr>
                      <td colspan="5"><b>Total</b></td>
                      <td>
                        <input type="text" :value="total('qty')|toCurrency" style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" readonly>
                      </td>
                      <td colspan="3"></td>
                       <td>
                        <input type="text" :value="total('grandtotal')|toCurrency" style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" readonly>
                      </td>
                    </tr>
                  </tfoot> -->
                    </table>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align='center'>
                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
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
            details: [],
            no_mesin_5: [],
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
            generate_tagihan: function() {
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
                  $('#btnGenerateTagihan').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('#btnGenerateTagihan').attr('disabled', true);
                  form_.details = [];
                  form_.no_mesin_5 = [];
                },
                url: '<?= base_url('h2/claim_kpb/generateTagihan') ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  $('#btnGenerateTagihan').attr('disabled', false);
                  $('#btnGenerateTagihan').html('Generate Tagihan');
                  if (response.tagihan.length == 0) {
                    alert('Data tidak ditemukan !');
                    return false
                  }
                  for (rsp of response.tagihan) {
                    form_.details.push(rsp);
                  }

                  for (rsp of response.no_mesin_5) {
                    form_.no_mesin_5.push(rsp);
                  }
                  // console.log(form_.details);
                },
                error: function() {
                  alert("Something Went Wrong !");
                  $('#btnGenerateTagihan').attr('disabled', false);

                },
                statusCode: {
                  500: function() {
                    alert('Fail Error 500 !');
                    $('#btnGenerateTagihan').attr('disabled', false);

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
                url: '<?= base_url('h2/claim_kpb/' . $form) ?>',
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

        function pilihAHASS(ahass) {
          $('#kode_ahass').val(ahass.kode_dealer_md);
          $('#nama_ahass').val(ahass.nama_dealer);
          $('#id_dealer').val(ahass.id_dealer);
        }
      </script>
    <?php } ?>
  </section>
</div>

<div class="modal fade" id="nosin_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search No Mesin
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <table id="example5" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No Mesin</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>Lokasi</th>
              <th>Status</th>
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $dt_nosin = $this->db->query("SELECT tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_warna.id_warna 
                  FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan ON 
                  tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna ON
                  tr_scan_barcode.warna = ms_warna.id_warna WHERE tr_scan_barcode.status = '1' ORDER BY tr_scan_barcode.no_mesin,tr_scan_barcode.tipe ASC");
            foreach ($dt_nosin->result() as $ve2) {
              echo "
            <tr>
              <td>$no</td>
              <td>$ve2->no_mesin</td>
              <td>$ve2->tipe_motor-$ve2->tipe_ahm</td>
              <td>$ve2->id_warna-$ve2->warna</td>
              <td>$ve2->lokasi-$ve2->slot</td>
              <td>$ve2->tipe</td>";
            ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="choose_nosin('<?php echo $ve2->no_mesin; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>
              </td>
              </tr>
            <?php
              $no++;
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>