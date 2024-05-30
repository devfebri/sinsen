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
      <li class="">Claim</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>
  <section class="content">
    <?php
    if ($set == "form") {
      $form = '';
      if ($mode == 'insert') {
        $form = 'save';
      } elseif ($mode == 'edit') {
        $form = 'save_edit';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <script>
        Vue.filter('toCurrency', function(value) {
          return accounting.formatMoney(value, "", 0, ".", ",");
          return value;
        });
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/lbpc">
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
              <form id="form_" class="form-horizontal" action="h2/lbpc/save" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl. LBPC</label>
                      <div class="col-sm-4">
                        <input type="text" :disabled="mode=='detail'" required class="form-control datepicker2" placeholder="Tgl LBPC" name="tgl_lbpc" id="tgl_lbpc" required autocomplete="off" value="<?= isset($row) ? $row->tgl_lbpc : '' ?>">
                      </div>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">No. SPPB MD TO AHM</label>
                      <div class="col-sm-4">
                        <?php
                        $default_lbpc = '';
                        $no_lbpc = '';
                        if (isset($row)) {
                          $exp_no_lbpc = explode('/', $row->no_lbpc);
                          $no_lbpc = $exp_no_lbpc[0];
                          $default_lbpc = '/' . $exp_no_lbpc[1] . '/' . $exp_no_lbpc[2] . '/' . $exp_no_lbpc[3];
                        } else {
                          $exp_no_lbpc = '';
                          $default_lbpc = '/E20/' . date('m') . '/' . date('Y');
                        }
                        ?>
                        <input v-if="mode=='detail'" type="text" class="form-control" placeholder="No. LBPC" name="no_lbpc" id="no_lbpc" autocomplete="off" value="<?= isset($row) ? $row->no_lbpc : '' ?>" readonly>
                        <input v-if="mode=='edit'" type="hidden" class="form-control" placeholder="No. LBPC" name="no_lbpc_old" autocomplete="off" value="<?= isset($row) ? $row->no_lbpc : '' ?>" readonly>
                        <div class="input-group" v-if="mode=='insert' || mode=='edit'">
                          <input type=" text" class="form-control" name='no_lbpc_awal' required value="<?= $no_lbpc ?>">
                          <span class="input-group-addon"><?= $default_lbpc ?></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                      <div class="form-input">
                        <label for="inputEmail3" class="col-sm-2 col-md-offset-6 control-label">No. SPPB AHASS TO MD</label>
                      <div class="col-sm-4">
                        <input type="text" :disabled="mode=='detail'" required class="form-control" placeholder="No. SPPB AHASS TO MD" name="no_lbpc_ahass_to_md" id="no_lbpc_ahass_to_md" required autocomplete="off" value="<?= isset($row) ? $row->no_lbpc_ahass_to_md : '' ?>">
                      </div>
                      </div>
                  </div>
                  <!-- <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode AHASS</label>
                    <div class="col-sm-4">
                      <input type="text" readonly @click.prevent="form_.showModalAHASS" class="form-control" placeholder="Kode AHASS" id="kode_ahass" value="<?= isset($row) ? $row->kode_dealer_md : '' ?>">
                      <input type="hidden" name="id_dealer" id="id_dealer" value="<?= isset($row) ? $row->id_dealer : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama AHASS</label>
                    <div class="col-sm-4">
                      <input type="text" required @click.prevent="form_.showModalAHASS" class="form-control" id="nama_ahass" readonly placeholder="Nama AHASS" value="<?= isset($row) ? $row->nama_dealer : '' ?>">
                    </div>
                  </div> -->
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control datepicker2" placeholder="Start Date" name="start_date" id="start_date" autocomplete="off" value="<?= isset($row) ? $row->start_date : '' ?>" :disabled="mode=='detail' || mode=='edit'">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control datepicker2" placeholder="End Date" name="end_date" id="end_date" autocomplete="off" required value="<?= isset($row) ? $row->end_date : '' ?>" :disabled="mode=='detail' || mode=='edit'">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Pengajuan</label>
                    <div class="col-sm-4">
                      <select class="form-control" id="kelompok_pengajuan" name="kelompok_pengajuan" required v-model='kelompok_pengajuan' :disabled="mode=='detail' || mode=='edit'">
                        <option value=''>- choose -</option>
                        <option value='E'>Engine</option>
                        <option value='L'>Electric</option>
                        <option value='F'>Frame</option>
                      </select>
                    </div>

                  </div>
                  <div class="form-group">
                    <div class="col-sm-12" align="center" v-if="mode=='insert'">
                      <button class="btn btn-primary btn-flat" id="btnGenerate" type="button" onclick="generate()">Generate</button>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <button class="btn btn-success btn-flat" disabled style="width: 100%">Detail</button>
                    <table class="table table-bordered" style='margin-top:10px'>
                      <thead>
                        <th>No.</th>
                        <th>No. Registrasi</th>
                        <th>Tgl Pengajuan</th>
                        <th>Kode AHASS</th>
                        <th>Nama AHASS</th>
                        <th>No Mesin</th>
                        <th>No Rangka</th>
                        <th>Tgl Pembelian</th>
                        <th>Tgl Kerusakan</th>
                        <th v-if="mode=='insert'">Ceklist</th>
                      </thead>
                      <tbody>
                        <tr v-for="(dtl, index) of details">
                          <td>{{index+1}}</td>
                          <td>{{dtl.no_registrasi}}</td>
                          <td>{{dtl.tgl_pengajuan}}</td>
                          <td>{{dtl.kode_dealer_md}}</td>
                          <td>{{dtl.nama_dealer}}</td>
                          <td>{{dtl.no_mesin}}</td>
                          <td>{{dtl.no_rangka}}</td>
                          <td>{{dtl.tgl_pembelian}}</td>
                          <td>{{dtl.tgl_kerusakan}}</td>
                          <td v-if="mode=='insert'" align='center'>
                            <input v-model='dtl.ceklist' type="checkbox" true-value='1' false-value='0' :disabled="mode=='detail'">
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="col-sm-12" style="padding-top:5px">
                    <button class="btn btn-success btn-flat" disabled style="width: 100%">Detail Parts</button>
                    <table class="table table-bordered" style='margin-top:10px'>
                      <thead>
                        <th>No.</th>
                        <th>No. Registrasi</th>
                        <th>Nomor Part</th>
                        <th>Nama Part</th>
                        <th>Jml</th>
                        <th>HET <br> (Diganti Uang)</th>
                        <th>Ongkos Kerja</th>
                        <th>HET <br> (Diganti Parts)</th>
                      </thead>
                      <tbody>
                        <template v-for="(dt, key, idx) in details" v-if="dt.ceklist==1" :key='dt.id_rekap_claim'>
                          <tr v-for="(prt, index) of show_parts(dt.id_rekap_claim)">
                            <td :rowspan="prt.count_id_rekap" v-if="index==0">{{key+1}}</td>
                            <td :rowspan="prt.count_id_rekap" v-if="index==0">{{prt.no_registrasi}}</td>
                            <td>{{prt.id_part}}</td>
                            <td>{{prt.nama_part}}</td>
                            <td>{{prt.qty}}</td>
                            <td align='right'>{{prt.ganti_uang | toCurrency}}</td>
                            <td align='right'>{{prt.ongkos | toCurrency}}</td>
                            <td align='right'>{{prt.ganti_part | toCurrency}}</td>
                          </tr>
                          <tr>
                            <td colspan=4 align='right'><b>Sub Total</b></td>
                            <td></td>
                            <td align='right'><b>{{subtotal(dt.id_rekap_claim).uang | toCurrency}}</b></td>
                            <td align='right'><b>{{subtotal(dt.id_rekap_claim).ongkos | toCurrency}}</b></td>
                            <td align='right'><b>{{subtotal(dt.id_rekap_claim).part  | toCurrency}}</b></td>
                          </tr>
                          <tr>
                            <td colspan=4 align='right'><b>Yang Ditagih</b></td>
                            <td></td>
                            <td align='right'><b>{{subtotal(dt.id_rekap_claim).uang | toCurrency}}</b></td>
                            <td align='right'><b>{{subtotal(dt.id_rekap_claim).ongkos | toCurrency}}</b></td>
                            <td align='right'><b>{{subtotal(dt.id_rekap_claim).part * 0.77 | toCurrency}}</b></td>
                          </tr>
                          <tr>
                            <td colspan=4 align='right'><b>Total Tagihan</b></td>
                            <td></td>
                            <td align='right'><b>{{subtotal(dt.id_rekap_claim).tot_tagihan | toCurrency}}</b></td>
                            <td></td>
                            <td></td>
                          </tr>
                        </template>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan=8></td>
                        </tr>
                        <tr>
                          <td colspan=4 style='font-size:12pt'>
                            <b align='right'>Total Keseluruhan</b>
                          </td>
                          <td colspan=4 style='font-size:12pt'><b>{{grand_total | toCurrency}}</b></td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                  <br>
                </div><!-- /.box-body -->
                <div class="box-footer" v-if="mode=='insert'||mode=='edit'">
                  <div class="col-sm-12" align="center">
                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div>
      <script>
        var no = 1;
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            detail: {
              id_part: '',
              nama_part: '',
              jumlah: '',
              tipe_penggantian: '',
              harga: '',
              ongkos: '',
              status_part: ''
            },
            kelompok_pengajuan: '<?= isset($row) ? $row->kelompok_pengajuan : '' ?>',
            details: <?= isset($details) ? json_encode($details) : '[]' ?>,
            parts: <?= isset($parts) ? json_encode($parts) : '{parts:[]}' ?>,
          },
          methods: {
            subtotal: function(id_rekap_claim) {
              let tot = {
                uang: 0,
                ongkos: 0,
                part: 0
              }
              for (prt of this.parts.parts) {
                if (prt.id_rekap_claim == id_rekap_claim) {
                  tot.uang += parseInt(prt.ganti_uang);
                  tot.ongkos += parseInt(prt.ongkos);
                  tot.part += parseFloat(prt.ganti_part);
                }
              }
              tot.tot_tagihan = tot.uang + tot.ongkos + (tot.part * 0.77);
              return tot ;
            },
            show_parts: function(id_rekap_claim) {
              return this.parts.parts.filter(prt => prt.id_rekap_claim == id_rekap_claim)
            },
            rekapClaimCeklist: function(string) {
              let claim = this.details.filter(prt => prt.ceklist == 1)
              if (string == true) {
                return JSON.stringify(claim);
              } else {
                return claim;
              }

            }
          },
          computed: {
            grand_total: function(show) {
              grand = 0;
              for (dt of this.rekapClaimCeklist(false)) {
                grand += this.subtotal(dt.id_rekap_claim).tot_tagihan;
              }
              return grand;
            }
          }
        })

        function generate() {
          no = 1;
          var kelompok_pengajuan = $('#kelompok_pengajuan').val();
          var start_date = $('#start_date').val();
          var end_date = $('#end_date').val();
          values = {
            kelompok_pengajuan: kelompok_pengajuan,
            start_date: start_date,
            end_date: end_date
          }
          $.ajax({
            beforeSend: function() {
              $('#btnGenerate').html('<i class="fa fa-spinner fa-spin"></i> Process');
              $('#btnGenerate').attr('disabled', true);
            },
            url: '<?= base_url('h2/lbpc/generate') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              $('#btnGenerate').html('Generate');
              $('#btnGenerate').attr('disabled', false);
              if (response.status == 'sukses') {
                form_.details = response.details;
                form_.parts = response.parts;
              } else {
                form_.details = [];
                form_.parts = {};
                alert(response.pesan);
              }
            },
            error: function() {
              alert("Something Went Wrong !");
              $('#btnGenerate').html('Generate');
              $('#btnGenerate').attr('disabled', false);

            }
          });
        }
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
            // details: form_.details,
            details: form_.rekapClaimCeklist(true),
          };
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          if ($('#form_').valid()) // check if form is valid
          {
            if (form_.rekapClaimCeklist().length == 0) {
              alert('Detail belum dipilih !')
              return false;
            }
            if (confirm("Apakah anda yakin ?") == true) {
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').attr('disabled', true);
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                },
                url: '<?= base_url('h2/lbpc/' . $form) ?>',
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
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                },
                error: function() {
                  alert("failure");
                  $('#submitBtn').attr('disabled', false);
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');


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
    } elseif ($set == "view") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/lbpc/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
            </a>
            <a href="h2/lbpc/download_excel" onclick="return confirm('Apakah anda yakin ?')" class="btn bg-maroon btn-flat margin">
              <i class="fa fa-download"></i> Download Excel
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
          <table id="example4" class="table table-hover">
            <thead>
              <tr>
                <th>No LBPC</th>
                <th>Tanggal LBPC</th>
                <th>No. Registrasi</th>
                <th>Kelompok Pengajuan</th>
                <th>Periode Awal</th>
                <th>Periode Akhir</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              foreach ($dt_result->result() as $rs) {
                $btn_cetak = "<a href='" . base_url('h2/lbpc/cetak?id=') . "$rs->no_lbpc' class=\"btn btn-success btn-xs btn-flat \" ><i class='fa fa-print' data-toggle='tooltip' title='Cetak'></i></a>";
                $btn_edit = "<a href='" . base_url('h2/lbpc/edit?id=') . "$rs->no_lbpc' class=\"btn btn-warning btn-xs btn-flat \" data-toggle='tooltip' title='Edit'><i class='fa fa-edit'></i></a>";
                $button = $btn_edit;
                echo "
            <tr>
              <td><a href='h2/lbpc/detail?id=$rs->no_lbpc'> $rs->no_lbpc</a></td>
              <td>$rs->tgl_lbpc</td>
              <td>$rs->no_registrasi</td>
              <td>$rs->kelompok_pengajuan</td>
              <td>$rs->start_date</td>
              <td>$rs->end_date</td>
              <td align='center'>$button</td>   
            </tr> ";
                $no++;
              }
              ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    } elseif ($set == "generate_skpb") {
    ?>

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/lbpc">
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
              <form class="form-horizontal" action="h2/lbpc/save" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                    <div class="col-sm-2">
                      <input type="text" id="tanggal" required class="form-control" placeholder="Start Date" name="tipe" id="tipe">
                    </div>
                    <div class="col-sm-2"></div>
                    <label for="inputEmail3" class="col-sm-2 control-label">5 Digit Nomor Mesin</label>
                    <div class="col-sm-4">
                      <select class="form-control" name="5_digit">
                        <option value="">--Pilih--</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                    <div class="col-sm-2">
                      <input type="text" class="form-control" id="tanggal1" placeholder="End Date" name="">
                    </div>
                    <div class="col-sm-2"></div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Service Ke-</label>
                    <div class="col-sm-4">
                      <select class="form-control" name="5_digit">
                        <option value="">--Pilih--</option>
                      </select>
                    </div>


                  </div>
                  <div class="form-group">
                    <p align="center">
                      <div class="col-sm-4"></div>
                      <div class="col-sm-2"><button class="btn btn-primary btn-flat form-control">Generate</button></div>
                    </p>
                  </div>
                  <button class="btn btn-success btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
                  <br><br><br>
                  <table id="example4" class="table table-hover">
                    <thead>
                      <th>No Mesin</th>
                      <th>No KPB</th>
                      <th>Tanggal Beli SMH</th>
                      <th>KM Service</th>
                      <th>Tanggal Sevice</th>
                      <th>No Surat Claim</th>
                    </thead>
                    <tbody>
                      <td>12345</td>
                      <td>12345</td>
                      <td>2018-10-10</td>
                      <td>2.000</td>
                      <td>2018-10-10</td>
                      <td>12345</td>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-4">
                  </div>
                  <div class="col-sm-4">
                    <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-download"></i> Download File .SKPB</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>