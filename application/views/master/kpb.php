<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">Master Data</li>
      <li class="">KPB</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
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
      if ($mode == 'edit') {
        $readonly = 'readonly';
        $form = 'save_edit';
        $row = $kpb->row();
        $row_tipe = $dt_tipe->row();
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
        $row = $kpb->row();
        $row_tipe = $dt_tipe->row();
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

      <script>
        Vue.use(VueNumeric.default);
        $(document).ready(function() {
          <?php if (isset($row)) { ?>
            $('#id_tipe_kendaraan').val('<?= $row->id_tipe_kendaraan ?>').trigger('change');
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
            <a href="master/kpb">
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
              <form class="form-horizontal" id="form_" action="master/kpb/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe *</label>
                    <div class="col-sm-4">
                      <select name="id_tipe_kendaraan" id="id_tipe_kendaraan" class="form-control select2" onchange="getTipeAHM()" <?= $disabled ?> readonly>
                        <?php if ($dt_tipe->num_rows() > 0) : ?>
                          <?php foreach ($dt_tipe->result() as $tp) :
                            $selected = isset($row->id_tipe_kendaraan) ? $tp->id_tipe_kendaraan == $row->id_tipe_kendaraan ? 'selected' : '' : ''
                          ?>
                            <option <?= $selected ?> value="<?= $tp->id_tipe_kendaraan ?>" data-tipe_ahm="<?= $tp->tipe_ahm ?>" data-no_mesin="<?= $tp->no_mesin ?>"><?= $tp->id_tipe_kendaraan . ' | ' . $tp->tipe_ahm . ' | ' . $tp->no_mesin ?></option>
                          <?php endforeach ?>
                        <?php endif ?>
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe AHM *</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" id="tipe_ahm" readonly value="<?= isset($row) ? $row->tipe_ahm : $tp->tipe_ahm ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">5 Digit No. Mesin *</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" maxlength="5" id="no_mesin" :disabled="mode=='detail'" <?php if($form=='save'){ echo 'readonly'; }else{ echo 'name="no_mesin"'; } ?> value="<?= isset($row_tipe) ? $row_tipe->no_mesin : $tp->no_mesin ?>">
                    </div>
                    
                    <label for="inputEmail4" class="col-sm-2 control-label">Jumlah Kartu KPB *</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" id="n_kpb" name="n_kpb" :disabled="mode=='detail'" value="<?= isset($row_tipe) ? $row_tipe->n_kpb : $tp->n_kpb ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Harga Oli *</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" id="harga_oli" name='harga_oli' :disabled="mode=='detail'" value="<?= isset($row) ? $row->harga_oli : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Insentif Oli *</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" id="insentif_oli" name='insentif_oli' :disabled="mode=='detail'" value="<?= isset($row) ? $row->insentif_oli : '' ?>">
                    </div>
                  </div>
                  <!-- <div class="form-group">
                    <label for="field-1" class="col-sm-2 control-label"></label>
                    <div class="col-sm-2">
                      <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                        <input type="checkbox" class="flat-red" name="status" <?= isset($row->status) ? $row->status == 1 ? 'checked' : '' : '' ?> <?= $mode == 'insert' ? 'checked' : '' ?>>
                        Active
                      </div>
                    </div>
                  </div> -->
                  <div class="form-group">
                    <div class="col-sm-12">
                      <button class="btn btn-primary btn-flat col-sm-12" disabled=""><b>Detail KPB</b></button>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <ul class="nav nav-tabs">
                      <li v-for="(dtl, index) of details" v-bind:class="{active:(dtl.kpb_ke==1||dtl.kpb_ke==='1')}">
                        <a v-bind:href="'#tab_'+dtl.kpb_ke" data-toggle="tab" aria-expanded="true">KPB {{dtl.kpb_ke}}</a>
                      </li>
                      <li v-if="details.length==0 || details.length<4"><a href="#tambah_kpb" data-toggle="tab" aria-expanded="true"><i class="fa fa-plus"></i></a></li>
                    </ul>
                  </div>
                  <div class="col-sm-12">
                    <div class="tab-content">
                      <div v-for="(dtls, indexs) of details" v-bind:id="'tab_'+dtls.kpb_ke" v-bind:class="{active:(dtls.kpb_ke==1 || dtls.kpb_ke=='1'),'tab-pane':true}">
                        <div class="form-horizontal" style="padding-top:22px">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">KPB Ke-</label>
                            <div class="col-sm-4">
                              <input class="form-control" v-model="dtls.kpb_ke" readonly />
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Batas Max KPB (Hari)</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" v-model="dtls.batas_maks_kpb" :disabled="mode=='detail'">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">KM</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" v-model="dtls.km" :disabled="mode=='detail'">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Toleransi (Hari)</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" v-model="dtls.toleransi" :disabled="mode=='detail'">
                            </div>
                            <!-- <label for="inputEmail3" class="col-sm-2 control-label">Oli</label>
                        <div class="col-sm-4">
                          <input type="text" readonly @click.prevent="form_.showModalPart()" class="form-control" v-model="dtls.oli">
                        </div> -->
                            <label for="inputEmail3" class="col-sm-2 control-label">Harga Jasa</label>
                            <div class="col-sm-4">
                              <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan isi_combo" v-model="dtls.harga_jasa" v-bind:minus="false" :empty-value="0" separator="." :disabled="mode=='detail'" />
                            </div>
                          </div>
                          <!-- <div class="form-group">
                            <div class="col-sm-12">
                              <button class="btn btn-info btn-flat btn-sm" type="button" style="width:100%;font-size:11pt;font-weight:bold;padding-top:2px;padding-bottom:2px;margin-bottom:10px">Detail Oli</button>
                            </div>
                            <div class="col-sm-12">
                              <table class="table table-bordered">
                                <thead>
                                  <th>Kode Part</th>
                                  <th>Nama Part</th>
                                  <th>Tipe Part</th>
                                  <th>HET</th>
                                  <th v-if="mode!='detail'">Aksi</th>
                                </thead>
                                <tbody>
                                  <tr v-for="(oli, index) of dtls.oli">
                                    <td>{{oli.id_part}}</td>
                                    <td>{{oli.nama_part}}</td>
                                    <td>{{oli.harga_dealer_user | toCurrency}}</td>
                                    <td>{{oli.tipe_part}}</td>
                                    <td align="center" v-if="mode!='detail'"><button type="button" @click.prevent="delOli(index,indexs)" class="btn btn-flat btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>
                                  </tr>
                                </tbody>
                                <tfoot v-if="mode!='detail'">
                                  <tr>
                                    <td>
                                      <input placeholder="Klik untuk memilih" class="form-control" v-model="dt_oli.id_part" readonly @click.prevent="showModalPartForKPB(indexs)">
                                    </td>
                                    <td>
                                      <input placeholder="Klik untuk memilih" class="form-control" v-model="dt_oli.nama_part" readonly @click.prevent="showModalPartForKPB(indexs)">
                                    </td>
                                    <td>
                                      {{dt_oli.harga_dealer_user | toCurrency}}
                                    </td>
                                    <td align="center">
                                      <button class="btn btn-primary btn-flat" type="button" @click.prevent="form_.addDetailOli"><i class="fa fa-plus"></i></button>
                                    </td>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div> -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer" v-if="mode!='detail'">
                  <div class="col-sm-12" align="center">
                    <button type="button" id="submitBtn" onclick="submitForm()" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <?php
      $data['data'] = ['all_parts', 'part_oli', 'filter_tipe_motor'];
      $this->load->view('h2/api', $data); ?>
      <script>
        var index = '';
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',

            dt_oli: {
              id_part: '',
              nama_part: ''
            },
            details: <?= isset($details) ? json_encode($details) : '[{kpb_ke:1},{kpb_ke:2},{kpb_ke:3},{kpb_ke:4}]' ?>,
          },
          methods: {
            showModalPartForKPB: function(idx) {
              index = idx;
              console.log(index)
              $('#tbl_parts').DataTable().ajax.reload();
              $('#modalAllParts').modal('show');
            },
            clearDetail: function() {
              this.detail = {
                batas_maks_kpb: '',
                toleransi: '',
                km: '',
                oli: [],
                harga_jasa: '',
              }
            },
            showModalPart: function() {
              // $('#tbl_part').DataTable().ajax.reload();
              $('.modalPart').modal('show');
            },
            addDetail: function(detail) {
              // this.detail.kpb_ke = this.next_kpb;
              // console.log(detail)
              // if (form_.details.length > 0) {
              //   for (detail of form_.details) {
              //       if (detail.referensi === this.detail.referensi) {
              //           alert("Referensi Sudah Dipilih !");
              //           this.clearDetail();
              //           return;
              //       }
              //   }
              // }

              if (this.detail.kpb_ke === '' || this.detail.batas_maks_kpb === '' || this.detail.toleransi === '' || this.detail.km === '' || this.detail.harga_jasa === '') {
                alert('Silahkan isi data dengan lengkap !');
                return false;
              }
              // if (parseInt(this.detail.qty_retur) == 0 || this.detail.qty_retur=='') {
              //   alert('Qty Retur Tidak boleh lebih kecil dari 1');
              //   return;
              // }
              this.details.push(this.detail);
              this.clearDetail();
              // console.log(this.details);
            },

            delDetail: function(index) {
              this.details.splice(index, 1);
            },
            addDetailOli: function() {
              if (this.details[index].oli === undefined) {
                this.details[index].oli = [];
              }
              this.details[index].oli.push(this.dt_oli);
              index = '';
              this.clearDetailOli();
            },
            clearDetailOli: function() {
              this.dt_oli = {
                id_part: '',
                nama_part: ''
              }
            },
            delOli: function(index, indexs) {
              this.details[indexs].oli.splice(index, 1);
            },
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
            },
            next_kpb: function() {
              return this.details.length + 1;

            }
          },
        });

        function pilihPart(params) {
          form_.dt_oli = params;
        }

        function getTipeAHM() {
          var tipe_ahm = $("#id_tipe_kendaraan").select2().find(":selected").data("tipe_ahm");
          $('#tipe_ahm').val(tipe_ahm);
          var no_mesin = $("#id_tipe_kendaraan").select2().find(":selected").data("no_mesin");
          $('#no_mesin').val(no_mesin);
        }

        function submitForm() {
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
          };

          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }

          if ($('#form_').valid()) // check if form is valid
          {
            if (form_.details.length == 0) {
              alert('Batas KPB belum ditentukan !');
              return false
            }
            let kpb = '';
            let tot = 0;
            for (dtl of form_.details) {
              if (dtl.batas_maks_kpb === undefined) {
                kpb += dtl.kpb_ke + ', ';
                tot++;
              }
            }
            if (tot === 4) {
              alert('Batas KPB belum ditentukan !');
              return false;
            }
            if (kpb != '') {
              if (confirm("Batas KPB belum ditentukan hingga ke KPB 4, Apakah tetap ingin menyimpan data ?") === true) {
                submit(values)
              } else {
                return false;
              }
            } else {
              submit(values)
            }
          } else {
            alert('Silahkan isi field required !')
          }
        }

        function submit(values) {
          if (confirm("Apakah anda yakin ?") === true) {
            $.ajax({
              beforeSend: function() {
                $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                $('#submitBtn').attr('disabled', true);
              },
              url: '<?= base_url('master/kpb/' . $form) ?>',
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
            });
          } else {
            return false;
          }
        }
      </script>
    <?php
    } elseif ($set == "view") {
    ?>

      <div class="box">
        <div class="box-header with-border">

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

          <table class="table table-striped table-bordered table-hover table-condensed" id="tr_ms_kpb" style="width: 100%">
            <thead>
              <tr>
                <th>ID Tipe Kendaraan</th>
                <th>Tipe AHM</th>
                <th>5 Digit No. Mesin</th>
                <th>Jumlah KPB</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tr_ms_kpb').DataTable({
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
                    "targets": [3],
                    "orderable": false
                  },
                  {
                    "targets": [3],
                    "className": 'text-center'
                  },
                  // {
                  //   "targets": [5],
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
              $('#tr_ms_kpb').DataTable().ajax.reload();
            }

            function refresh() {
              $('#kode_dealer_md').val('');
              $('#nama_dealer').val('');
              $('#id_dealer').val('');
              $('#tgl_po_kpb').val('');
              $('#status').val('');
              $('#tr_ms_kpb').DataTable().ajax.reload();
            }
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>