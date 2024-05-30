<base href="<?php echo base_url(); ?>" />
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/toastr/toastr.min.js") ?>"></script>
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
      <li class="">KPB</li>
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
            <a href="<?= $folder . '/' . $isi ?>/add" class="btn bg-blue btn-flat">
              <i class="fa fa-plus"></i> Add New
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
                      <label>Tanggal PO KPB</label>
                      <input type="text" class="form-control datepicker" id="tgl_po_kpb" name="tgl_po_kpb">
                    </div>
                    <div class="col-sm-2">
                      <label>Status PO KPB</label>
                      <select class='form-control' id='status'>
                        <option value=''>All</option>
                        <option value='input'>Input</option>
                        <option value='approved'>Approved</option>
                        <option value='rejected'>Rejected</option>
                      </select>
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
          <table class="table table-striped table-bordered table-hover table-condensed" id="tr_po_kpb" style="width: 100%">
            <thead>
              <tr>
                <th>ID PO KPB</th>
                <th>Tgl PO KPB</th>
                <th>Kode AHASS</th>
                <th>Nama AHASS</th>
                <th>Total Qty Part</th>
                <th>Total Harga Parts</th>
                <th>Status PO</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
          <script>
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
                    d.status = $('#status').val();
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [{
                    "targets": [7],
                    "orderable": false
                  },
                  {
                    "targets": [6, 7],
                    "className": 'text-center'
                  },
                  {
                    "targets": [5],
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
      } elseif ($mode == 'edit') {
        $form = 'update';
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
                    <div class="col-sm-12" align="center" v-if="mode=='insert'">
                      <button type="button" id="generateBtn" @click.prevent="generateData" class="btn btn-success btn-flat">Generate Data</button>
                    </div>
                  </div>
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-info btn-flat btn-sm" disabled>Detail</button><br><br>
                  <table class="table table-bordered table-hover table-condensed table-stripped">
                    <thead>
                      <th>Kode Part</th>
                      <th>Nama Part</th>
                      <th>Kode Tipe</th>
                      <th>5 Digit</th>
                      <th>Qty</th>
                      <th>Harga</th>
                      <th>Diskon</th>
                      <th>Harga Setelah Diskon</th>
                      <th>Total Harga</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dt, index) of details">
                        <td style="display:none;" v-if='mode=="edit"'>{{dt.id_detail}}</td>
                        <td v-if='mode!="edit"'>{{dt.id_part}}</td>
                        <td v-if='mode=="edit"'><input type="text" class="form-control" v-model='dt.id_part' readonly @click.prevent='pilih_part_h3(index)'></td>
                        <td>{{dt.nama_part}}</td>
                        <td>{{dt.id_tipe_kendaraan}} | {{dt.tipe_ahm}}</td>
                        <td>{{dt.no_mesin_5}}</td>
                        <td v-if='mode!="edit"'>{{dt.qty}}</td>
                        <td v-if='mode=="edit"' width="100px"> <vue-numeric class="form-control" separator='.' v-model='dt.qty'></vue-numeric></td>
                        <td align="right">{{dt.harga_material | toCurrency}}</td>
                        <td align="right" v-if='mode!="edit"'>{{dt.diskon | toCurrency}}</td>
                        <td align="right" v-if='mode=="edit"'> <vue-numeric read-only currency="Rp " thousand-separator="." v-model="diskon(dt)" /></td>
                        <td align="right">{{dt.harga_setelah_diskon | toCurrency}}</td>
                        <td align="right" v-if='mode!="edit"'>{{dt.total | toCurrency}}</td>
                        <td align="right" v-if='mode=="edit"'> <vue-numeric read-only currency="Rp " thousand-separator="." v-model="total_part(dt)" /></td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan=4><b>Total</b></td>
                        <td><b>{{total.qty | toCUrrency}}</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td align='right'><b>{{total.total | toCurrency}}</b></td>
                      </tr>
                      <tr>
                        <td colspan=8 align='right'><b>PPN</b></td>
                        <td align='right'><b>{{total.ppn | toCurrency}}</b></td>
                      </tr>
                      <tr>
                        <td colspan=8 align='right'><b>Grand Total</b></td>
                        <td align='right'><b>{{total.grand | toCurrency}}</b></td>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align='center'>
                    <button v-if="mode=='insert'" type="button" id="submitBtn" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    <button v-if="mode=='edit'" type="button" id="submitBtn" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update</button>
                    <button v-if="mode=='approved'" type="button" onclick="setApproval('approved')" class="btn btn-flat btn-success"><i class="fa fa-check"></i> Approved </button>
                    <button v-if="mode=='approved'" type="button" class="btn btn-flat btn-danger" onclick="setApproval('rejected')"><i class="fa fa-remove"></i> Rejected </button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->

      <?php $this->load->view('modal/part_po_kpb', true); ?>
      <script>
        function pilih_part_po_kpb(data) {
          form_.details[form_.index_part].id_part = data.id_part;
          form_.details[form_.index_part].harga_material = data.het;
          form_.set_id_part_h3(data);
        }
      </script>

      <?php
      $data = ['data' => ['modalAHASS']];
      $this->load->view('h2/api', $data);
      ?>

      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            index_part: 0,
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
            },
            pilih_part_h3: function(index) {
              this.index_part = index;
              $('#h2_md_part_po_kpb').modal('show');
              h2_md_part_po_kpb_datatable.draw();
            },
            set_id_part_h3: function(part_h3) {
              form_.loading = true;
              axios.get('h2/Po_kpb/set_id_part_po_kpb', {
                  params: {
                    // id_detail: this.details[this.index_part].id_detail,
                    id_part: this.details[this.index_part].id_part
                  }
                })
                .then(function(res) {
                  form_.details[form_.index_part].harga_material = part_h3.het;
                  // form_.get_diskon_oli_kpb();
                })
                .catch(function(err) {
                  toastr.error(err);
                })
                .then(function() {
                  form_.loading = false;
                });
            },
            diskon: function(dt) {
              harga_material = dt.harga_material;
              diskon = harga_material - dt.harga_setelah_diskon;

              return diskon;
            },
            total_part: function(dt) {
              total_part = dt.harga_setelah_diskon * dt.qty;

              return total_part;
            },
          },
          computed: {
            total: function() {
              let total = {
                qty: 0,
                grand: 0,
                total: 0,
                ppn: 0,
              }
              // for (dt of this.details) {
              //   total.qty += parseInt(dt.qty);
              //   total.total += parseInt(dt.total);
              // }
              <?php if ($mode == 'edit') { ?>
                for (dt of this.details) {
                  total.qty += parseInt(dt.qty);
                  total.total += parseInt(this.total_part(dt));
                }
              <?php } else { ?>
                for (dt of this.details) {
                  total.qty += parseInt(dt.qty);
                  total.total += parseInt(dt.total);
                }
              <?php } ?>

              total.ppn = Math.round(total.total * <?php echo getPPN(0.1) ?>);
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