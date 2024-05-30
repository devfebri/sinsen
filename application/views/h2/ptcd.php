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
      $form     = '';
      $disabled = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
      }
    ?>
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
          value = parseInt(value);
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
            <a href="h2/ptcd">
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
              <form id="form_" class="form-horizontal" action="h2/ptcd/save" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No PTCD</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="no_ptcd" id="no_ptcd" autocomplete="off" readonly value="<?= isset($row->no_ptcd) ? $row->no_ptcd : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl PTCD</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control datepicker" name="tgl_ptcd" id="tgl_ptcd" autocomplete="off" <?= $disabled ?> value="<?= isset($row->tgl_ptcd) ? $row->tgl_ptcd : date('Y-m-d') ?>">
                    </div>
                  </div>

                  <button class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
                  <br><br><br>
                  <table class="table table-bordered">
                    <thead>
                      <th>No LBPC</th>
                      <th>No Claim</th>
                      <th>No Claim Dealer</th>
                      <th>No Frame</th>
                      <th>No Part</th>
                      <th>Nama Part</th>
                      <th>Tgl Purchase</th>
                      <th>Qty</th>
                      <th>Qty Accept</th>
                      <th>Part Cost</th>
                      <th>Labour Cost</th>
                      <th>Amount</th>
                      <th v-if="mode=='insert'">Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dtl, index) of details">
                        <td>{{dtl.no_lbpc}}</td>
                        <td>{{dtl.id_rekap_claim}}</td>
                        <td>{{dtl.no_registrasi}}</td>
                        <td>{{dtl.no_rangka}}</td>
                        <td>{{dtl.id_part}}</td>
                        <td>{{dtl.nama_part}}</td>
                        <td>{{dtl.tgl_pengajuan}}</td>
                        <td>{{dtl.qty }}</td>
                        <td>{{dtl.jml_accept | toCurrency }}</td>
                        <td align="right">{{dtl.harga | toCurrency}}</td>
                        <td align="right">{{dtl.ongkos | toCurrency}}</td>
                        <td align="right">{{amount_dtls(index) | toCurrency}}</td>
                        <td v-if="mode=='insert'"></td>
                      </tr>
                    </tbody>
                    <tfoot v-if="mode=='insert'">
                      <tr v-for="(dtl, index) of detail">
                        <td>
                          <input type="text" @click.prevent="form_.showModalLBPC" class="form-control" readonly v-model="dtl.no_lbpc">
                        </td>
                        <td>{{dtl.id_rekap_claim}}</td>
                        <td>{{dtl.no_registrasi}}</td>
                        <td>{{dtl.no_rangka}}</td>
                        <td>{{dtl.id_part}}</td>
                        <td>{{dtl.nama_part}}</td>
                        <td>{{dtl.tgl_pengajuan}}</td>
                        <td>{{dtl.qty}}</td>
                        <td>
                          <input type="number" class="form-control" v-model="dtl.jml_accept">
                        </td>
                        <td align="right">{{dtl.harga | toCurrency}}</td>
                        <td align="right">{{dtl.ongkos | toCurrency}}</td>
                        <td align="right">{{amount_dtl(index) | toCurrency}}</td>
                        <td style="vertical-align: middle;text-align: center;" :rowspan="detail.length" v-if="index==0">
                          <button type="button" @click.prevent="addDetails" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i></button>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                  <table class="table table-bordered" style="font-weight: bold; text-align: right;">
                    <tr>
                      <td>Total Part</td>
                      <td width="18%" align="right">{{tot_part | toCurrency}}</td>
                    </tr>
                    <tr>
                      <td>Total Jasa</td>
                      <td align="right">{{tot_jasa | toCurrency}}</td>
                    </tr>
                    <tr>
                      <td>Total</td>
                      <td align="right">{{total | toCurrency}}</td>
                    </tr>
                    <tr>
                      <td>PPn</td>
                      <td align="right">{{ppn | toCurrency}}</td>
                    </tr>
                    <tr>
                      <td>PPh</td>
                      <td align="right">{{pph | toCurrency}}</td>
                    </tr>
                    <tr>
                      <td>Grand Total</td>
                      <td align="right">{{grand_total | toCurrency}}</td>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align="center" v-if="mode=='insert'">
                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <div class="modal fade modalAHASS" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" style="width: 55%">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Daftar LBPC</h4>
            </div>
            <div class="modal-body">
              <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_lbpc" style="width: 100%">
                <thead>
                  <tr>
                    <th>No. LBPC</th>
                    <th>Tgl. LBPC</th>
                    <th>Kelompok Pengajuan</th>
                    <th>Perioder Awal</th>
                    <th>Perioder Akhir</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <script>
                $(document).ready(function() {
                  $('#tbl_lbpc').DataTable({
                    processing: true,
                    serverSide: true,
                    "language": {
                      "infoFiltered": ""
                    },
                    order: [],
                    ajax: {
                      url: "<?= base_url('h2/ptcd/fetch_lbpc') ?>",
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
                        "targets": [5],
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
        // function generate() {
        //   var id_dealer = $('#id_dealer').val();
        //   var start_date         = $('#start_date').val();
        //   var end_date           = $('#end_date').val();
        //   values = {id_dealer:id_dealer,start_date:start_date,end_date:end_date}
        //    $.ajax({
        //     beforeSend: function() {
        //       $('#btnGenerate').attr('disabled',true);
        //       form_.details = [];
        //     },
        //     url:'<?= base_url('h2/ptcd/generate') ?>',
        //     type:"POST",
        //     data: values,
        //     cache:false,
        //     dataType:'JSON',
        //     success:function(response){
        //       $('#btnGenerate').attr('disabled',false);
        //       if (response.length==0) {
        //         alert('Data tidak ditemukan !');
        //         return false
        //       }
        //       for(rsp of response)
        //       {
        //         form_.details.push(rsp);
        //       }
        //     },
        //     error:function(){
        //       alert("Something Went Wrong !");
        //       $('#btnGenerate').attr('disabled',false);

        //     },
        //     statusCode: {
        //       500: function() { 
        //         alert('Fail Error 500 !');
        //         $('#btnGenerate').attr('disabled',false);

        //       }
        //     }
        //   });
        // }
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            detail: [{
              no_lbpc: '',
              jumlah: 0,
              harga: 0,
              ongkos: 0,
              jml_accept: 0
            }],
            details: <?= isset($details) ? json_encode($details) : '[]' ?>,
          },
          methods: {
            showModalLBPC: function() {
              // $('#tbl_part').DataTable().ajax.reload();
              $('.modalAHASS').modal('show');
            },
            clearDetail: function() {
              this.detail = [{
                no_lbpc: '',
                jumlah: 0,
                harga: 0,
                ongkos: 0,
                jml_accept: 0
              }]
            },
            amount_dtl: function(idx) {
              let qty = parseInt(this.detail[idx].jml_accept);
              let harga = parseInt(this.detail[idx].harga);
              let ongkos = parseInt(this.detail[idx].ongkos);
              return (qty * harga) + ongkos;
            },
            amount_dtls: function(idx) {
              let qty = parseInt(this.details[idx].jml_accept);
              let harga = parseInt(this.details[idx].harga);
              let ongkos = parseInt(this.details[idx].ongkos);
              return (qty * harga) + ongkos;
            },
            addDetails: function() {
              for (dtl of this.detail) {
                // let cek=0;
                // if (this.details.length>0) {
                //   for (dtls of this.details) {
                //     if (dtls.no_lbpc==dtl.no_lbpc) {
                //       alert('ID LBPC Sudah ada !');
                //       cek++;
                //       break;
                //     }
                //     console.log(dtl.no_lbpc);
                //   }
                // }
                // if (cek==0) {
                this.details.push(dtl);
                // }
              }
              this.clearDetail();
            },
            delDetails: function(index) {
              this.details.splice(index, 1);
            },
            allTotal: function(show) {
              let tot_part = 0;
              let tot_jasa = 0;
              let total = 0;
              index = 0;
              for (dtl of this.details) {
                tot_part += parseInt(dtl.jml_accept);
                tot_jasa += parseInt(dtl.ongkos);
                total += this.amount_dtls(index);
                index++;
              }
              if (show == 'tot_part') {
                return tot_part;
              }
              if (show == 'tot_jasa') {
                return tot_jasa;
              }
              if (show == 'total') {
                return total;
              }
            }
          },
          computed: {
            tot_part: function() {
              return this.allTotal('tot_part');
            },
            tot_jasa: function() {
              return this.allTotal('tot_jasa');
            },
            total: function() {
              let total = this.allTotal('total');
              return total;
            },
            ppn: function() {
              let ppn = parseInt(this.total * 0.1);
              return ppn;
            },
            pph: function() {
              return this.total * 0.02;
            },
            grand_total: function() {
              return this.total + this.ppn - this.pph;
            }
          }
        })

        function pilihLBPC(lbpc) {
          let values = {
            no_lbpc: lbpc.no_lbpc
          }
          $.ajax({
            url: "<?php echo site_url('h2/ptcd/get_lbpc_part') ?>",
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              if (response.length > 0) {
                form_.detail = [];
                for (rsp of response) {
                  form_.detail.push(rsp);
                }
              }
            }
          })
        }
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
            details: form_.details,
            grand_total: form_.grand_total
          };
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          if ($('#form_').valid()) // check if form is valid
          {
            if (values.details.length == 0) {
              alert('Detail masih kosong !')
              return false;
            }
            if (confirm("Apakah anda yakin ?") == true) {
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').attr('disabled', true);
                },
                url: '<?= base_url('h2/ptcd/' . $form) ?>',
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
            <a href="h2/ptcd">
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
              <form class="form-horizontal" action="h2/ptcd/import_db" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Choose File</label>
                    <div class="col-sm-10">
                      <input type="file" accept=".ptcd" required class="form-control" autofocus name="userfile">
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-2">
                  </div>
                  <div class="col-sm-10">
                    <button type="submit" onclick="return confirm('Are you sure to import this data?')" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Start Upload</button>
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
            <a href="h2/ptcd/add">
              <button class="btn btn-primary btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
                <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                <th width="5%">No</th>
                <th>No. PTCD</th>
                <th>Tanggal PTCD</th>
                <th>Grand Total</th>
                <th width='5%'>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $ci = &get_instance();
              $ci->load->model("m_h2");
              foreach ($dt_result->result() as $rs) {
                echo "
            <tr>
            <td>$no</td>
            <td><a href=" . base_url('h2/ptcd/detail?id=') . "$rs->no_ptcd>$rs->no_ptcd</a></td>
            <td>$rs->tgl_ptcd</td>
            <td align='right'>" . mata_uang_rp($rs->grand_total) . "</td>
            <td align='center'><a class='btn btn-primary btn-xs btn-flat' href=" . base_url('h2/ptcd/detail?id=') . "$rs->no_ptcd><i class='fa fa-eye'></i></a></td>
            </tr>
            ";
                $no++;
              }
              ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>