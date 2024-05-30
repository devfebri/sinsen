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
      if ($mode == 'approve') {
        $form = 'save_approve';
      }
      if ($mode == 'batal') {
        $form = 'save_batal';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <script>
        Vue.use(VueNumeric.default);
        $(document).ready(function() {})
        Vue.filter('toCurrency', function(value) {
          return accounting.formatMoney(value, "", 0, ".", ",");
          return value;
        });
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/rekap_tagihan">
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
              <form id="form_" class="form-horizontal" action="h2/rekap_tagihan/save" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <?php if (isset($row)) : ?>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">No PTCA</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" placeholder="Kode AHASS" name="id_ptca" value="<?= $row->id_ptca ?>">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl. PTCA</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" placeholder="Kode AHASS" name="tgl_ptca" value="<?= $row->tgl_ptca ?>">
                      </div>
                    </div>
                  <?php endif ?>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode AHASS</label>
                    <div class="col-sm-4">
                      <input type="text" readonly @click.prevent="form_.showModalAHASS" class="form-control" placeholder="Kode AHASS" id="kode_ahass" value="<?= isset($row) ? $row->kode_dealer_md : '' ?>">
                      <input type="hidden" name="id_dealer" id="id_dealer">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama AHASS</label>
                    <div class="col-sm-4">
                      <input type="text" required @click.prevent="form_.showModalAHASS" class="form-control" id="nama_ahass" readonly placeholder="Nama AHASS" value="<?= isset($row) ? $row->nama_dealer : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Start Date LBPC</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control datepicker2" name="start_date" id="start_date" autocomplete="off" value="<?= isset($row) ? $row->start_date : '' ?>" :readonly="mode!='insert'">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">End Date LBPC</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control datepicker2" name="end_date" id="end_date" autocomplete="off" value="<?= isset($row) ? $row->end_date : '' ?>" :readonly="mode!='insert'">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12" align="center" v-if="mode=='insert'">
                      <button class="btn btn-primary btn-flat" id="btnGenerate" type="button" onclick="generate()">Generate</button>
                    </div>
                  </div>

                  <button class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
                  <br><br><br>
                  <div class="form-group">
                    <div class="col-md-12">
                      <table class="table table-bordered">
                        <thead>
                          <th>No LBPC</th>
                          <th>No Claim</th>
                          <th>Nilai Part</th>
                          <th>Nilai Jasa</th>
                          <th>Total Part & Jasa</th>
                          <th>Nilai PPn</th>
                          <th>Nilai PPh</th>
                          <th>Total</th>
                          <th v-if="mode=='insert'">
                            <input type='checkbox' v-model='ceklist_all' true-value='1' false-value='0' />
                          </th>
                        </thead>
                        <tbody>
                          <tr v-for="(dtl, index) of details">
                            <td>{{dtl.no_lbpc}}</td>
                            <td>{{dtl.no_registrasi}}</td>
                            <td align='right'>{{dtl.nilai_part | toCurrency}}</td>
                            <td align='right'>{{dtl.nilai_jasa | toCurrency}}</td>
                            <td align='right'>{{tot_part_jasa(dtl) | toCurrency}}</td>
                            <td align='right'>{{dtl.nilai_ppn | toCurrency}}</td>
                            <td align='right'>{{dtl.nilai_pph | toCurrency}}</td>
                            <td align='right'>{{dtl.total | toCurrency}}</td>
                            <td v-if="mode=='insert'">
                              <input v-model='dtl.ceklist' type="checkbox" true-value='1' false-value='0' :disabled="mode=='detail'">
                            </td>
                          </tr>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td align='right' colspan=2><b>Total</b></td>
                            <td align='right'><b>{{total.part | toCurrency}}</b></td>
                            <td align='right'><b>{{total.jasa | toCurrency}}</b></td>
                            <td align='right'><b>{{total.part_jasa | toCurrency}}</b></td>
                            <td align='right'><b>{{total.ppn | toCurrency}}</b></td>
                            <td align='right'><b>{{total.pph | toCurrency}}</b></td>
                            <td align='right'><b>{{total.total | toCurrency}}</b></td>
                            <td align='right' v-if="mode=='insert'"></td>
                          </tr>
                          <tr>
                            <td><b>PKP</b></td>
                            <td colspan=7><b>{{pkp_dealer}}</b></td>
                            <td v-if="mode=='insert'"></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                  <div class="form-group" v-if="mode=='detail' || mode=='batal'">
                    <!-- <label for="inputEmail3" class="col-sm-2 control-label">Alasan Batal</label>
                    <div class="col-sm-9">
                      <input type="text" required class="form-control" name="alasan_cancel" id="alasan_cancel" autocomplete="off" value="<?= isset($row) ? $row->alasan_cancel : '' ?>" :readonly="mode=='detail'">
                    </div> -->
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align="center" v-if="mode=='insert' || mode=='batal'">
                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  </div>
                  <div class="col-sm-12" align="center" v-if="mode=='approve'">
                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-success btn-flat">Approve</button>
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
                  form_.pkp_dealer = ahass.pkp
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
        function generate() {
          var id_dealer = $('#id_dealer').val();
          var start_date = $('#start_date').val();
          var end_date = $('#end_date').val();
          values = {
            id_dealer: id_dealer,
            start_date: start_date,
            end_date: end_date
          }
          $.ajax({
            beforeSend: function() {
              $('#btnGenerate').html('<i class="fa fa-spinner fa-spin"></i> Process');
              $('#btnGenerate').attr('disabled', true);
              form_.details = [];
              form_.ceklist_all = '0';
            },
            url: '<?= base_url('h2/rekap_tagihan/generate') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              $('#btnGenerate').html('Generate');
              $('#btnGenerate').attr('disabled', false);
              if (response.status === 'sukses') {
                form_.details = response.data;
              } else {
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
              status_part: '',
            },
            ceklist_all: '0',
            pkp_dealer: '<?= isset($row) ? $row->pkp_dealer : '' ?>',
            details: <?= isset($details) ? json_encode($details) : '[]' ?>,
          },
          methods: {

            totalHarga: function(dtl) {
              return parseInt(this.hargaDiskon(dtl) * dtl.qty);
            },
            tot_part_jasa: function(dtl) {
              return parseInt(parseInt(dtl.nilai_part) + parseInt(dtl.nilai_jasa));
            },
            showModalAHASS: function() {
              // $('#tbl_part').DataTable().ajax.reload();
              $('.modalAHASS').modal('show');
            },
            lbpcCeklist: function(string = null) {
              let lbpc = this.details.filter(dtl => dtl.ceklist == 1)
              if (string == true) {
                return JSON.stringify(lbpc);
              } else {
                return lbpc;
              }
            }
          },
          computed: {
            total: function() {
              let total = {
                part: 0,
                jasa: 0,
                part_jasa: 0,
                ppn: 0,
                pph: 0,
                total: 0
              }
              for (dt of this.details) {
                total.part += parseInt(dt.nilai_part);
                total.jasa += parseInt(dt.nilai_jasa);
                total.part_jasa += this.tot_part_jasa(dt);
                total.ppn += parseInt(dt.nilai_ppn);
                total.pph += parseInt(dt.nilai_pph);
                // console.log(dt.total);
                total.total += parseInt(dt.total);
              }
              return total;
            },
          },
          watch: {
            ceklist_all: function() {
              index = 0;
              for (dtl of this.details) {
                this.details[index].ceklist = this.ceklist_all;
                index++;
              }
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
            details: form_.lbpcCeklist(true)
          };
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          if ($('#form_').valid()) // check if form is valid
          {
            if (form_.mode == 'insert') {
              if (form_.lbpcCeklist().length == 0) {
                alert('Detail belum dipilih !')
                return false;
              }
            }
            if (confirm("Apakah anda yakin ?") == true) {
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').attr('disabled', true);
                },
                url: '<?= base_url('h2/rekap_tagihan/' . $form) ?>',
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
    } elseif ($set == "view") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/rekap_tagihan/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
                <th>No PTCA</th>
                <th>Tanggal PTCA</th>
                <th>Periode</th>
                <th>Kode AHASS</th>
                <th>Nama AHASS</th>
                <th>No. LBPC</th>
                <th>Status Rekap</th>
                <th>Total</th>
                <th width="12%" align="center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $filter = ['ptca_not_null' => true, 'join_rekap_tagihan' => true, 'get_summary' => true,'order_by'=>'tgl_ptca'];
              $result = $this->m_claim->getRekapClaimWarranty($filter);
              foreach ($result->result() as $row) {
                $status = '';
                $button = '';
                $btn_approve = "<a style=\"margin-bottom:1px\" href='" . base_url('h2/rekap_tagihan/approve?id=') . $row->id_ptca . "' class=\"btn btn-success btn-xs btn-flat \" >Approve</a>";
                $btn_batal = "<a style=\"margin-bottom:1px\" href='" . base_url('h2/rekap_tagihan/batal?id=') . $row->id_ptca . "' class=\"btn btn-danger btn-xs \" >Batal</a>";
                $btn_perbaikan = "<a style=\"margin-bottom:1px\" href='" . base_url('h2/rekap_tagihan/perbaikan?id=') . $row->id_ptca . "' class=\"btn btn-info btn-xs \" >Perbaikan</a>";
                if ($row->status == 'input') {
                  // $status = '<label class="label label-primary">Input</label>';
                  $button = $btn_approve;
                }
                if ($row->status == 'approve') {
                  $status = '<label class="label label-success">Approve</label>';
                }
                if ($row->status == 'batal') {
                  $status = '<label class="label label-danger">Batal</label>';
                }
                echo "
            <tr>
              <td><a href=" . base_url('h2/rekap_tagihan/detail?id=') . "$row->id_ptca>$row->id_ptca</td>
              <td>$row->tgl_ptca</td>
              <td>$row->start_date s/d $row->end_date</td>
              <td>$row->kode_dealer_md</td>
              <td>$row->nama_dealer</td>
              <td>$row->no_lbpc</td>
              <td>$status</td>
              <td>" . mata_uang_rp($row->total) . "</td>
              <td >
                $button
              </td>   
            </tr> ";
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