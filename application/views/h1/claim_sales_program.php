<style type="text/css">
  .myTable1 {

    margin-bottom: 0px;

  }

  .myt {

    margin-top: 0px;

  }

  .isi {

    height: 30px;

    padding-left: 5px;

    padding-right: 5px;

    margin-right: 0px;

  }

  .isi_combo {

    height: 30px;

    border: 1px solid #ccc;

    padding-left: 1.5px;

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

      <li class="">H1</li>

      <li class="">Bussiness Control</li>

      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>

    </ol>

  </section>

  <section class="content">

    <?php

    if ($set == "insert" or $set == 'detail' or $set == 'verifikasi') {
      $form = '';
      $disabled = '';
      $readonly = '';
      if ($set == 'insert') {
        $form = 'save';
      }
      if ($set == 'verifikasi') {
        $form = 'save_verifikasi';
      }
      if ($set == 'detail') {
        $disabled = 'disabled';
        // $readonly =
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

        $(document).ready(function() {
          <?php if (isset($row)) { ?>
            let id_program_md = "<?= $row->id_program_md ?>";
            let id_dealer = "<?= $row->id_dealer ?>";
            let id_program_r = id_program_md.replace(/\s/g, '');
            $('#id_program_md').val(id_program_r).trigger('change');
            $("#id_dealer_syarat").val(id_dealer);
            // console.log(id_dealer);
          <?php } ?>
        })
      </script>
      <div class="box box-default">

        <div class="box-header with-border">

          <h3 class="box-title">

            <a href="h1/claim_sales_program">

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

                  <br>

                  <div class="form-group">
                    <?php if (isset($row)) { ?>
                      <input type="hidden" name="id_claim_sp" value="<?= $row->id_claim_sp ?>">
                    <?php } ?>
                    <label for="inputEmail3" class="col-sm-2 control-label">ID Progam MD</label>
                    
                    <input type="hidden" name="id_dealer_syarat" id="id_dealer_syarat">

                    <div class="col-sm-4">

                      <select class="form-control select2" onchange="showID()" id="id_program_md" name="id_program_md" <?= $disabled ?> readonly>

                        <?php

                        // $get_program = $this->db->query("SELECT * FROM tr_sales_program

                        //   WHERE id_program_md NOT IN (SELECT id_program_md_gabungan from tr_sales_program_gabungan WHERE id_program_md_gabungan IS NOT NULl)

                        //   ORDER BY created_at DESC");

                        $get_program = $this->mbc->get_program();

                        if ($get_program->num_rows() > 0) {

                          echo "<option value=''>- choose -</option>";

                          foreach ($get_program->result() as $key => $rs) {

                            echo "<option value='$rs->id_program_md' 
                                  data-ahm='$rs->id_program_ahm'
                                  data-judul_kegiatan='$rs->judul_kegiatan'
                                  data-periode_awal='$rs->periode_awal'
                                  data-periode_akhir='$rs->periode_akhir'
                                  >$rs->id_program_md</option>";
                          }
                        }

                        ?>

                      </select>

                    </div>

                    <label for="inputEmail3" class="col-sm-2 control-label">ID Program AHM</label>

                    <div class="col-sm-4">

                      <input type="text" name="id_program_ahm" id="id_program_ahm" class="form-control" readonly>

                    </div>

                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Judul Kegiatan</label>
                    <div class="col-sm-10">
                      <input id="judul_kegiatan" class="form-control" readonly>
                    </div>
                  </div>

                  <?php /* <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Awal</label>

                  <div class="col-sm-4">

                    <input type="text" name="periode_awal" placeholder="Periode Awal" id="tanggal2" class="form-control">

                  </div>                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Akhir</label>

                  <div class="col-sm-4">

                    <input type="text" name="periode_akhir" placeholder="Periode Akhir" id="tanggal" class="form-control">

                  </div>                  

                </div> */ ?>
                
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode Awal</label>
                    <div class="col-sm-4">
                      <input id="periode_awal" class="form-control" readonly>
                    </div>
                    
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode Akhir</label>
                    <div class="col-sm-4">
                      <input id="periode_akhir" class="form-control" readonly>
                    </div>
                  </div>
                  

                  <div class="form-group">

                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer</label>

                    <div class="col-sm-4">

                      <select class="form-control select2" id="id_dealer" name="id_dealer" <?= $disabled ?> readonly>

                      </select>

                    </div>

                    <label for="inputEmail3" class="col-sm-2 control-label"></label>

                    <div class="col-sm-4" v-if="mode=='insert'">

                      <button class="btn btn-primary btn-flat" id="btnGenerate" type="button" onclick="generate(null,'new')">Generate</button>

                    </div>

                  </div>

                  <div id="showGenerate"></div>

                  <br>





                </div><!-- /.box-body -->

                <div class="box-footer" v-if="mode=='insert'||mode=='verifikasi'">

                  <div class="col-sm-5">

                  </div>

                  <div class="col-sm-7">
                    <!-- <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button> -->
                    <button v-if="mode=='verifikasi' && id_claim_sp!=''" type="button" id="closeBtn" name="save" value="close" class="btn btn-warning btn-flat">Close</button>
                  </div>

                </div><!-- /.box-footer -->

              </form>

            </div>

          </div>

        </div>

      </div><!-- /.box -->

      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $set ?>',
            id_claim_sp: '<?= $row->id_claim_sp ?>',
          },
          methods: {

          }
        })


        $('#closeBtn').click(function() {
          // let tot_gantung = $('#tot_gantung_all').val();
          // if (tot_gantung>0) {
          //   alert('Masih ada unit dengan status gantung !');
          //   return false;
          // }
          if (confirm("Apakah anda yakin ?") == true) {
            var values = {};
            var form = $('#form_').serializeArray();
            for (field of form) {
              values[field.name] = field.value;
            }
            $.ajax({
              beforeSend: function() {
                $('#closeBtn').attr('disabled', true);
                $('#closeBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
              },
              url: '<?= base_url('h1/claim_sales_program/close') ?>',
              type: "POST",
              data: values,
              cache: false,
              dataType: 'JSON',
              success: function(response) {
                $('#closeBtn').html('Close');
                if (response.status == 'sukses') {
                  window.location = response.link;
                } else {
                  $('#closeBtn').attr('disabled', false);
                  alert(response.pesan);
                }
              },
              error: function() {
                alert("failure");
                $('#closeBtn').html('Close');
                $('#closeBtn').attr('disabled', false);

              },
              statusCode: {
                500: function() {
                  alert('fail');
                  $('#closeBtn').html('Close');
                  $('#closeBtn').attr('disabled', false);

                }
              }
            });
          } else {
            return false;
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
          var values = {};
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          if ($('#form_').valid()) // check if form is valid
          {

            if (confirm("Apakah anda yakin ?") == true) {
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').attr('disabled', true);
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                },
                url: '<?= base_url('h1/claim_sales_program/' . $form) ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  alert('view line 402');
                  $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                  if (response.status == 'sukses') {
                    window.location = response.link;
                  } else {
                    $('#submitBtn').attr('disabled', false);
                    alert(response.pesan);
                  }
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
          } else {
            alert('Silahkan isi field required !')
          }
        })
      </script>

    <?php

    } elseif ($set == 'edit_dokumen') {
      $form = '';
      $disabled = 'disabled';
      $readonly = '';
      if ($set == 'edit_dokumen') {
        $form = 'save_edit';
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

        $(document).ready(function() {
          <?php if (isset($rows)) { ?>
            $('#id_program_md').val('<?= $rows->id_program_md ?>').trigger('change');
          <?php } ?>
        })
      </script>

      <div class="box box-default">

        <div class="box-header with-border">

          <h3 class="box-title">

            <a href="h1/claim_sales_program">

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

              <form id="form_" action="h1/claim_sales_program/<?= $form ?>" class="form-horizontal" method="post" enctype="multipart/form-data">

                <div class="box-body">

                  <br>

                  <div class="form-group">
                    <?php if (isset($rows)) { ?>
                      <input type="hidden" name="id_claim_sp" value="<?= $rows->id_claim_sp ?>">
                    <?php } ?>
                    <label for="inputEmail3" class="col-sm-2 control-label">ID Progam MD</label>

                    <div class="col-sm-4">

                      <select class="form-control select2" onchange="showID()" id="id_program_md" name="id_program_md" <?= $disabled ?>>

                        <?php

                        // $get_program = $this->db->query("SELECT * FROM tr_sales_program

                        //   WHERE id_program_md NOT IN (SELECT id_program_md_gabungan from tr_sales_program_gabungan WHERE id_program_md_gabungan IS NOT NULl)

                        //   ORDER BY created_at DESC");

                        $get_program = $this->mbc->get_program();
                        if ($get_program->num_rows() > 0) {
                          echo "<option value=''>- choose -</option>";
                          foreach ($get_program->result() as $key => $rs) {

                            echo "<option value='$rs->id_program_md' 
                                  data-ahm='$rs->id_program_ahm'
                                  data-judul_kegiatan='$rs->judul_kegiatan'
                                  data-periode_awal='$rs->periode_awal'
                                  data-periode_akhir='$rs->periode_akhir'
                                  >$rs->id_program_md</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>

                    <label for="inputEmail3" class="col-sm-2 control-label">ID Program AHM</label>

                    <div class="col-sm-4">

                      <input type="text" name="id_program_ahm" id="id_program_ahm" class="form-control" readonly>

                    </div>

                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Judul Kegiatan</label>
                    <div class="col-sm-10">
                      <input id="judul_kegiatan" class="form-control" readonly>
                    </div>
                  </div>

                  <?php /* <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Awal</label>

                  <div class="col-sm-4">

                    <input type="text" name="periode_awal" placeholder="Periode Awal" id="tanggal2" class="form-control">

                  </div>                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Akhir</label>

                  <div class="col-sm-4">

                    <input type="text" name="periode_akhir" placeholder="Periode Akhir" id="tanggal" class="form-control">

                  </div>                  

                </div> */ ?>


                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode Awal</label>
                    <div class="col-sm-4">
                      <input id="periode_awal" class="form-control" readonly>
                    </div>
                    
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode Akhir</label>
                    <div class="col-sm-4">
                      <input id="periode_akhir" class="form-control" readonly>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" id="id_dealer" name="id_dealer" <?= $disabled ?>>
                      </select>
                    </div>
                  </div>
                  
                  <button class="btn btn-block btn-warning btn-flat btn-sm" disabled> DETAIL UNIT </button><br>
                  <div class="table-responsive">
                    <table class="table table-bordered table-hovered table-condensed" width="100%">

                      <thead>

                        <th>No Mesin</th>
                        <th>Tipe Kendaraan</th>
                        <th>No Faktur</th>
                        <th>Tgl Faktur</th>
                        <th>No PO Leasing</th>
                        <th>Tgl PO Leasing</th>
                        <th>No BASTK</th>
                        <th>Tgl BASTK</th>
                        <th>Nama Konsumen</th>
                        <th>Nama Leasing</th>
                      </thead>
                      <tbody>
                        <?php foreach ($detail->result() as $rs) :
                          $tgl_po_leasing = $rs->tgl_po_leasing;
                          if ($rs->tgl_po_leasing == '0000-00-00') {
                            $tgl_po_leasing = '';
                          }
                        ?>
                          <tr>
                            <td><?= $rs->no_mesin ?></td>
                            <td><?= $rs->id_tipe_kendaraan . ' | ' . $rs->tipe_ahm ?></td>
                            <td><?= $rs->no_invoice ?></td>
                            <td><?= $rs->tgl_cetak_invoice ?></td>
                            <td><input type="text" name="no_po_leasing[]" class="form-control" value="<?= $rs->no_po_leasing ?>"></td>
                            <td>
                              <input type="text" class="form-control datepicker" name="tgl_po_leasing[]" value="<?= $tgl_po_leasing ?>">
                              <input type="hidden" class="form-control" name="id_sales_order[]" value="<?= $rs->id_sales_order ?>">
                            </td>
                            <td><?= $rs->no_bastk ?></td>
                            <td><?= $rs->tgl_bastk ?></td>
                            <td><?= $rs->nama_konsumen ?></td>
                            <td><?= $rs->finance_company ?></td>
                          </tr>
                        <?php endforeach ?>
                      </tbody>
                    </table>
                  </div>
                  <br>

                </div><!-- /.box-body -->

                <div class="box-footer">

                  <div class="col-sm-5">

                  </div>

                  <div class="col-sm-7">

                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  </div>

                </div><!-- /.box-footer -->

              </form>

            </div>

          </div>

        </div>

      </div><!-- /.box -->

      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $set ?>',
          },
          methods: {

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
          var values = {};
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          if ($('#form_').valid()) // check if form is valid
          {

            if (confirm("Apakah anda yakin ?") == true) {
              $('#form_').submit();
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

            <a href="h1/claim_sales_program/history">

              <button class="btn bg-green btn-flat margin"> History</button>

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

          <form action="" method="POST">
            <div class="form-horizontal">
              <div class="form-group">
                <label class="col-sm-2 control-label">ID Program MD</label>
                <div class="col-sm-3">
                  <select class="form-control select2" id="id_program_md">
                    <?php
                    $get_program = $this->mbc->get_program();
                    if ($get_program->num_rows() > 0) {
                      echo "<option value=''>- choose -</option>";

                      foreach ($get_program->result() as $key => $rs) {
                        echo "<option value='$rs->id_program_md' data-ahm='$rs->id_program_ahm'>$rs->id_program_md </option>";
                      }
                    } ?>
                  </select>
                </div>
                <label class="col-sm-offset-1 col-sm-2 control-label">ID Program AHM</label>
                <div class="col-sm-3">
                  <select class="form-control select2" id="id_program_ahm">
                    <?php
                    $get_program = $this->mbc->get_program('y');
                    if ($get_program->num_rows() > 0) {
                      echo "<option value=''>- choose -</option>";

                      foreach ($get_program->result() as $key => $rs) {
                        echo "<option value='$rs->id_program_ahm'>$rs->id_program_ahm</option>";
                      }
                    } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Dealer</label>
                <div class="col-sm-3">
                  <select class="form-control select2" id="id_dealer">
                    <?php $dealer = $this->mbc->get_dealer();
                    if ($dealer->num_rows() > 0) {
                      echo '<option value="">- choose -</option>';
                      foreach ($dealer->result() as $dl) { ?>
                        <option value="<?= $dl->id_dealer ?>"><?= $dl->kode_dealer_md ?> | <?= $dl->nama_dealer ?></option>
                    <?php }
                    }
                    ?>
                  </select>
                </div>
                <div class="col-sm-3">
                  <button type="button" onclick="loads()" class="btn btn-primary btn-flat "><i class="fa fa-search"></i></button>
                </div>
              </div>
            </div>
          </form>
          <hr>
          <table id="tabel_claim" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID Program MD</th>
                <th>ID Program AHM</th>
                <th>Nama Dealer</th>
                <th>Periode Program</th>
                <th>Status</th>
                <th width="15%">Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div><!-- /.box-body -->

      </div><!-- /.box -->
      <script>
        $(document).ready(function() {
          $('#tabel_claim').DataTable({
            processing: true,
            serverSide: true,
            "language": {
              "infoFiltered": ""
            },
            order: [],
            ajax: {
              url: "<?= base_url('h1/claim_sales_program/fetch') ?>",
              dataSrc: "data",
              data: function(d) {
                d.id_dealer = $('#id_dealer').val();
                d.id_program_md = $('#id_program_md').val();
                d.id_program_ahm = $('#id_program_ahm').val();
                d.id_menu = '<?= $id_menu ?>';
                d.group = '<?= $group ?>';
                return d;
              },
              type: "POST"
            },
            "columnDefs": [{
                "targets": [4, 5],
                "orderable": false
              },
              // { "targets":[7],"className":'text-center'}, 
              // { "targets":[4], "searchable": false } 
            ]
          });
        });

        function loads() {
          $('#tabel_claim').DataTable().ajax.reload();
        }
      </script>
    <?php

    } elseif ($set == "history") {

    ?>


      <div class="box">

        <div class="box-header with-border">

          <h3 class="box-title">

            <a href="h1/claim_sales_program">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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

          <form action="" method="POST">
            <div class="form-horizontal">
              <div class="form-group">
                <label class="col-sm-2 control-label">ID Program MD</label>
                <div class="col-sm-3">
                  <select class="form-control select2" id="id_program_md">
                    <?php
                    $get_program = $this->mbc->get_program();
                    if ($get_program->num_rows() > 0) {
                      echo "<option value=''>- choose -</option>";

                      foreach ($get_program->result() as $key => $rs) {
                        echo "<option value='$rs->id_program_md' data-ahm='$rs->id_program_ahm'>$rs->id_program_md</option>";
                      }
                    } ?>
                  </select>
                </div>
                <label class="col-sm-offset-1 col-sm-2 control-label">ID Program AHM</label>
                <div class="col-sm-3">
                  <select class="form-control select2" id="id_program_ahm">
                    <?php
                    $get_program = $this->mbc->get_program('y');
                    if ($get_program->num_rows() > 0) {
                      echo "<option value=''>- choose -</option>";

                      foreach ($get_program->result() as $key => $rs) {
                        echo "<option value='$rs->id_program_ahm'>$rs->id_program_ahm</option>";
                      }
                    } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Dealer</label>
                <div class="col-sm-3">
                  <select class="form-control select2" id="id_dealer">
                    <?php $dealer = $this->mbc->get_dealer();
                    if ($dealer->num_rows() > 0) {
                      echo '<option value="">- choose -</option>';
                      foreach ($dealer->result() as $dl) { ?>
                        <option value="<?= $dl->id_dealer ?>"><?= $dl->kode_dealer_md ?> | <?= $dl->nama_dealer ?></option>
                    <?php }
                    }
                    ?>
                  </select>
                </div>
                <div class="col-sm-3">
                  <button type="button" onclick="loads()" class="btn btn-primary btn-flat "><i class="fa fa-search"></i></button>
                </div>
              </div>
            </div>
          </form>
          <hr>
          <table id="tabel_claim" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID Program MD</th>
                <th>ID Program AHM</th>
                <th>Nama Dealer</th>
                <th>Periode Program</th>
                <th>Status</th>
                <th width="15%">Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div><!-- /.box-body -->

      </div><!-- /.box -->
      <script>
        $(document).ready(function() {
          $('#tabel_claim').DataTable({
            processing: true,
            serverSide: true,
            "language": {
              "infoFiltered": ""
            },
            order: [],
            ajax: {
              url: "<?= base_url('h1/claim_sales_program/fetch_history') ?>",
              dataSrc: "data",
              data: function(d) {
                d.id_dealer = $('#id_dealer').val();
                d.id_program_md = $('#id_program_md').val();
                d.id_program_ahm = $('#id_program_ahm').val();
                return d;
              },
              type: "POST"
            },
            "columnDefs": [{
                "targets": [4, 5],
                "orderable": false
              },
              // { "targets":[7],"className":'text-center'}, 
              // { "targets":[4], "searchable": false } 
            ]
          });
        });

        function loads() {
          $('#tabel_claim').DataTable().ajax.reload();
        }
      </script>
    <?php } ?>
  </section>

</div>





<script type="text/javascript">
  function showID()
  {
    var id_program_ahm = $("#id_program_md").select2().find(":selected").data("ahm");
    var judul_kegiatan = $("#id_program_md").select2().find(":selected").data("judul_kegiatan");
    var periode_awal = $("#id_program_md").select2().find(":selected").data("periode_awal");
    var periode_akhir = $("#id_program_md").select2().find(":selected").data("periode_akhir");

    $('#id_program_ahm').val(id_program_ahm);
    $('#judul_kegiatan').val(judul_kegiatan);
    
    $('#periode_awal').val(periode_awal);
    $('#periode_akhir').val(periode_akhir);

    var value = {
      id_program_md: $('#id_program_md').val()
    }
    $('#showGenerate').html('');
    $.ajax({

      beforeSend: function() {
        $('#loading-status').show();
      },

      url: "<?php echo site_url('h1/claim_sales_program/getDealer') ?>",

      type: "POST",

      data: value,

      cache: false,

      success: function(html) {
        $('#loading-status').hide();
        $('#id_dealer').html(html);
        <?php if (isset($row)) { ?>
          $('#id_dealer').val('<?= $row->id_dealer ?>').trigger('change');
          generate('save_detail', '<?= $set ?>')
        <?php } ?>
        <?php if (isset($rows)) { ?>
          $('#id_dealer').val('<?= $rows->id_dealer ?>').trigger('change');
        <?php } ?>
      },

      statusCode: {

        500: function() {

          $('#loading-status').hide();

          alert("Something Wen't Wrong");

        }

      }

    });

  }

  function generate(save_detail = null, mode = null, no_reset = null)

  {

    var value = {
      id_program_md: $('#id_program_md').val(),
      id_dealer: $('#id_dealer').val(),
      id_claim_sp: '<?= isset($row) ? $row->id_claim_sp : '' ?>',
      mode: mode,
      no_reset: no_reset
    }

    $.ajax({

      beforeSend: function() {
        // $('#loading-status').show(); 
        $('#btnGenerate').html('<i class="fa fa-spinner fa-spin"></i> Process');
        $('#btnGenerate').attr('disabled', true);
        $('#showGenerate').html('');
      },

      url: "<?php echo site_url('h1/claim_sales_program/generate') ?>",

      type: "POST",

      data: value,

      cache: false,

      success: function(html) {

        $('#loading-status').hide();
        $('#btnGenerate').html('Generate');
        $('#btnGenerate').attr('disabled', false);
        if (html == 'kosong') {
          if (save_detail == null) {
            alert('Data Tidak Ditemukan');
          }
          $('#showGenerate').html('');
        } else {
          $('#showGenerate').html(html);

        }

      },

      statusCode: {

        500: function() {

          $('#loading-status').hide();
          $('#btnGenerate').html('Generate');
          $('#btnGenerate').attr('disabled', false);
          alert("Something Wen't Wrong");

        }

      }

    });

  }

  function setRevisi(a)

  {

    if ($('#chk_revisi_' + a).prop('checked')) {

      var perlu_revisi = 1;

    } else {

      var perlu_revisi = 0;

    }



    var value = {
      id: $("#id_" + a).val(),

      perlu_revisi: perlu_revisi

    }

    $.ajax({

      beforeSend: function() {
        $('#loading-status').show();
      },

      url: "<?php echo site_url('h1/claim_sales_program/setRevisi') ?>",

      type: "POST",

      data: value,

      cache: false,

      success: function(html) {

        $('#loading-status').hide();

      },

      statusCode: {

        500: function() {

          $('#loading-status').hide();

          alert("Something Wen't Wrong");

        }

      }

    });

  }
</script>