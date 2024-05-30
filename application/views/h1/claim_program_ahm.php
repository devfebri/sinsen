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

      <li class=""><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      <li class="active"><?= $set ?></li>

    </ol>

  </section>


  <style>
    .tabs {
      width: 100%;
      font-family: "lucida grande", sans-serif;
    }

    [role="tablist"] {
      margin: 0 0 -0.1em;
      overflow: visible;
    }

    [role="tab"] {
      position: relative;
      margin: 0;
      padding: 0.3em 0.5em 0.4em;
      border: 1px solid hsl(219deg 1% 72%);
      border-radius: 0.2em 0.2em 0 0;
      box-shadow: 0 0 0.2em hsl(219deg 1% 72%);
      overflow: visible;
      font-family: inherit;
      font-size: inherit;
      background: hsl(220deg 20% 94%);
    }

    [role="tab"]:hover::before,
    [role="tab"]:focus::before,
    [role="tab"][aria-selected="true"]::before {
      /* position: absolute; */
      bottom: 100%;
      right: -1px;
      left: -1px;
      border-radius: 0.2em 0.2em 0 0;
      border-top: 3px solid hsl(20deg 96% 48%);
      content: "";
    }

    [role="tab"][aria-selected="true"] {
      border-radius: 0;
      background: hsl(220deg 43% 99%);
      outline: 0;
    }

    [role="tab"][aria-selected="true"]:not(:focus):not(:hover)::before {
      border-top: 5px solid hsl(218deg 96% 48%);
    }

    [role="tab"][aria-selected="true"]::after {
      position: absolute;
      z-index: 3;
      bottom: -1px;
      right: 0;
      left: 0;
      height: 0.3em;
      background: hsl(220deg 43% 99%);
      box-shadow: none;
      content: "";
    }

    [role="tab"]:hover,
    [role="tab"]:focus,
    [role="tab"]:active {
      outline: 0;
      border-radius: 0;
      color: inherit;
    }

    [role="tab"]:hover::before,
    [role="tab"]:focus::before {
      border-color: hsl(20deg 96% 48%);
    }

    [role="tabpanel"] {
      position: relative;
      z-index: 2;
      padding: 0.5em 0.5em 0.7em;
      border: 1px solid hsl(219deg 1% 72%);
      border-radius: 0 0.2em 0.2em;
      box-shadow: 0 0 0.2em hsl(219deg 1% 72%);
      background: hsl(220deg 43% 99%);
    }

    [role="tabpanel"].is-hidden {
      display: none;
    }

    [role="tabpanel"]:focus {
      border-color: hsl(20deg 96% 48%);
      box-shadow: 0 0 0.2em hsl(20deg 96% 48%);
      outline: 0;
    }

    [role="tabpanel"]:focus::after {
      position: absolute;
      bottom: 0;
      right: -1px;
      left: -1px;
      border-bottom: 3px solid hsl(20deg 96% 48%);
      border-radius: 0 0 0.2em 0.2em;
      content: "";
    }

    [role="tabpanel"] p {
      margin: 0;
    }

    [role="tabpanel"] *+p {
      margin-top: 1em;
    }
  </style>

  <?php
  if ($set == "views") {
  ?>


    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <strong>
              View Claim Proposal :
            </strong>
            <?= $vc['idpm'] ?>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>

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
          
          <div class="table-responsive">
            <table class="table " id="tbl_clamss" border=1>
              <thead class="text-center">
                <tr>

                  <th>Program Year</th>
                  <th>Program Month</th>
                  <th>No.Juklak</th>
                  <th>Juklak Deskription</th>
                  <th>ID Sales Program</th>
                  <th>Sales Program Description</th>
                  <th>No.Frame</th>
                  <th>No.Engine</th>
                  <th>MD Approve Data</th>
                  <th>Dealer Code</th>
                  <th>Dealer Description</th>
                  <th>Unit Type</th>
                  <th>Unit Type Description</th>

                </tr>
              </thead>
              <style>
                .bta {
                  width: 100px;
                }
              </style>
              <tbody>
                <tr>
                  <td><?= $vc['yr'] ?></td>
                  <td><?= $vc['prd'] ?></td>
                  <td><?= $vc['asm'] ?></td>
                  <td><?= "" ?></td>
                  <td><?= $vc['idpm'] ?></td>
                  <td><?= $vc['judul_kegiatan'] ?></td>
                  <td><?= $vc['no_rangka'] ?></td>
                  <td><?= $vc['nos'] ?></td>
                  <td><?= $vc['tgl_approve_reject_md'] ?></td>
                  <td><?= $vc['kode_dealer_md'] ?></td>
                  <td><?= $vc['nama_dealer'] ?></td>
                  <td><?= $vc['tipe_motor'] ?></td>
                  <td><?= $vc['tipe_ahm'] ?></td>
                </tr>
              </tbody>
            </table>
            <br>

            <br>
            <div class="table-responsive">
              <table class="table  table-hover table-striped" border="1">
                <thead>
                  <tr>
                    <th>Unit Type</th>
                    <th>Unit Type Description</th>
                    <th>Color Code</th>
                    <th>Color Description</th>
                    <th>Total Diskon Faktur</th>
                    <th>No. Faktur</th>
                    <th>Faktur Date</th>
                    <th>BAST Date</th>
                    <th>Payment Type</th>
                    <th>Fincoy Code</th>
                    <th>Fincoy Description</th>
                    <th>NIK</th>
                    <th>Dealer Submit Date</th>
                    <th>Owner Sales Program</th>
                    <th>Claim Category</th>
                  </tr>
                </thead>
                <tbody>

                  <?php
                  if ($tb2['jenis_beli'] == "Cash") {
                    $diskon = $tb2['voucher_1'];
                  } else {
                    $diskon = $tb2['voucher_2'];
                  }

                  ?>

                  <?php
                  if ($tb2['jenis'] == "ahm_md") {
                    $js = "AHM & MD";
                  } else {
                    $js = strtoupper($tb2['jenis']);
                  }
                  ?>

                  <?php
                  if ($tb2['ir'] == 0) {
                    $cat = "Regular";
                  } else {
                    $cat = "Irregular";
                  }
                  ?>
                  <tr>
                    <td><?= $tb2['tipe_motor'] ?></td>
                    <td><?= $tb2['tipe_ahm'] ?></td>
                    <td><?= $tb2['id_warna'] ?></td>
                    <td><?= $tb2['msw'] ?></td>
                    <td><?= number_format($diskon) ?></td>
                    <td><?= $tb2['no_invoice'] ?></td>
                    <td><?= $tb2['tgl_cetak_invoice'] ?></td>
                    <td><?= $tb2['tgl_bastk'] ?></td>
                    <td><?= $tb2['jenis_beli'] ?></td>
                    <td> <?= $tb2['id_finance_company'] ?> </td>
                    <td> <?= $tb2['finance_company'] ?> </td>
                    <td><?= $tb2['no_ktp'] ?></td>
                    <td><?= $tb2['tgl_ajukan_claim'] ?></td>
                    <td><?= $js ?></td>
                    <td><?= $cat ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>


      <div class="box box-default">
        <div class="box-header">
          <h3 class="box-title">
            Detail Data Kelengkapan Document
          </h3>

        </div>
        <div class="box-body">
          <table class="table table-hover table-striped text-center" border="1">
            <thead>
              <tr>
                <th>Label Documnet</th>
                <th>Attachment Dokument</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sy = $this->db->get_where('tr_sales_program_syarat', ['id_program_md' => $vc['idpm']])->result_array();
              foreach ($sy as $y) :
              ?>
                <tr>
                  <td><?= $y['syarat_ketentuan'] ?></td>
                  <td><a class="btn btn-danger" disabled>Download</a></td>
                </tr>
              <?php
              endforeach;
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <a href="<?= base_url('h1/claim_program_ahm') ?>" class="btn btn-flat btn-primary">Kembali</a>
    </section>

  <?php
  } else if ($set == "view") {


  ?>
    <section class="content">
      <div class="tabs">
        <div role="tablist" aria-label="Entertainment">
          <button type="button" role="tab" aria-selected="true" aria-controls="nils-tab" id="nils">
            Regular Case
          </button>
          <button type="button" role="tab" aria-selected="false" aria-controls="agnes-tab" id="agnes" tabindex="-1">
            Irregular Case
          </button>

        </div>
        <div tabindex="0" role="tabpanel" id="nils-tab" aria-labelledby="nils">
          <p>
          <div class="box box-default">

            <div class="box-header with-border">
              <h3 class="box-title">

              </h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div>

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

              <form action="<?= base_url('h1/claim_program_ahm') ?>" method="post" class="" id="formregular">
                <div class="row ">
                  <div class="col-lg-4 col-lg-offset-2">
                    <label for="">No. Juklak</label>
                    <input type="text" name="nojuklak1" id="nojuklak1" class="form-control" value="">
                  </div>
                  <div class="col-lg-4 ">
                    <label for="">Program Year</label>
                    <input type="number" class="form-control" name="program_year1" id="program_year1" value="">
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4 col-lg-offset-2">
                    <label for="">Juklak Deskription</label>
                    <input type="text" class="form-control" name="juk_des1" id="jukdes1">
                  </div>
                  <div class="col-lg-4 ">
                    <label for="">Program Month</label>

                    <select name="prog_mon1" id="prog_mon1" class="form-control">
                      <option value="">--ALL--</option>

                      <option value="01">Januari</option>
                      <option value="02">Februari</option>
                      <option value="03">Maret</option>
                      <option value="04">April</option>
                      <option value="05">Mai</option>
                      <option value="06">Juni</option>
                      <option value="07">Juli</option>
                      <option value="08">Agustus</option>
                      <option value="09">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4 col-lg-offset-2">
                    <label for="">ID Sales Program</label>
                    <input type="text" name="id_sales_prg1" id="id_sales_prg1" class="form-control">
                  </div>
                  <div class="col-lg-4 ">
                    <label for="">Dealer Code</label>
                    <input type="number" class="form-control" name="dealer_code1" id="dealer_code1" value="<?= set_value('dealer_code') ?>">
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4 col-lg-offset-2">
                    <label for="">Sales Program Desc</label>
                    <input type="text" class="form-control" name="sales_prog_des1" id="sales_program_desc1" value="<?= set_value('sales_prog_des') ?>">
                  </div>
                  <div class="col-lg-4 ">
                    <label for="">Owner Sales Program</label>
                    <select name="owner_sales_prog1" id="owner_sales_prog1" class="form-control">
                      <option value="">--ALL--</option>
                      <option value="ahm">AHM</option>
                      <option value="md">MD</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4 col-lg-offset-2">
                    <label for="">No Mesin</label>
                    <input type="text" name="nosin1" id="nosin1" class="form-control">
                  </div>
                  <div class="col-lg-4">
                    <label for="">Send AHM</label>
                    <select name="sendahm1" id="sendahm1" class="form-control">
                      <option value="">--ALL--</option>
                      <option value="1">Ya</option>
                      <option value="2">Tidak</option>
                    </select>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-lg-12 col-lg-offset-9">
                    <button class="btn btn-primary btn-flat " type="button" id="btnregular"><i class="fa fa-search"> Cari</i></button>
                  </div>
                </div>
                <br>

              </form>

            </div>
          </div>
          <div class="box box-default">
            <div class="box-header with-border">

            </div>
            <div class="box-body">
              <div class="table-responsive">
                <form action="" method="post">

                  <table class="table table-bordered table-hovered table-condensed" width="100%" id="tbl_clam">
                    <thead class="text-center">
                      <tr>
                        <th>#</th>
                        <th>Program Year</th>
                        <th>Program Month</th>
                        <th>No.Juklak</th>
                        <th>Juklak Deskription</th>
                        <th>ID Sales Program</th>
                        <th>Sales Program Description</th>
                        <th>No.Frame</th>
                        <th>No.Engine</th>
                        <th>MD Approve Date</th>
                        <th>Dealer Code</th>
                        <th>Dealer Description</th>
                        <th>Unit Type</th>
                        <th>Unit Type Description</th>
                        <th>Status verifikasi 1</th>
                        <th>Status verifikasi 2</th>
                        <th>Alasan Reject API</th>
                        <th>Reject Claim API 2</th>
                        <th>Status Send</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <style>
                      .bta {
                        width: 100px;
                      }
                    </style>

                  </table>
                </form>
              </div>
            </div>
          </div>
          </p>
        </div>
        <div tabindex="0" role="tabpanel" id="agnes-tab" aria-labelledby="agnes" class="is-hidden">
          <div class="box box-default">

            <div class="box-header with-border">
              <h3 class="box-title">

              </h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div>

            </div>

            <div class="box-body">
              <form action="<?= base_url('h1/claim_program_ahm') ?>" method="post" class="form_irregular">
                <div class="row ">
                  <div class="col-lg-4 col-lg-offset-2">
                    <label for="">No. Juklak</label>
                    <input type="text" name="no_juk" id="no_juk" class="form-control" value="">
                  </div>
                  <div class="col-lg-4 ">
                    <label for="">Program Year</label>
                    <input type="number" class="form-control" name="program_year" id="program_year" value="">
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4 col-lg-offset-2">
                    <label for="">Juklak Deskription</label>
                    <input type="text" class="form-control" name="juk_des" id="jukdes">
                  </div>
                  <div class="col-lg-4 ">
                    <label for="">Program Month</label>

                    <select name="prog_mon" id="prog_mon" class="form-control">
                      <option value="">--ALL--</option>

                      <option value="01">Januari</option>
                      <option value="02">Februari</option>
                      <option value="03">Maret</option>
                      <option value="04">April</option>
                      <option value="05">Mai</option>
                      <option value="06">Juni</option>
                      <option value="07">Juli</option>
                      <option value="08">Agustus</option>
                      <option value="09">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4 col-lg-offset-2">
                    <label for="">ID Sales Program</label>
                    <input type="number" name="id_sales_prg" id="id_sales_prg" class="form-control">
                  </div>
                  <div class="col-lg-4 ">
                    <label for="">Dealer Code</label>
                    <input type="number" class="form-control" name="dealer_code" id="dealer_code" value="">
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4 col-lg-offset-2">
                    <label for="">Sales Program Desc</label>
                    <input type="text" class="form-control" name="sales_prog_des" id="sales_program_desc" value="">
                  </div>
                  <div class="col-lg-4 ">
                    <label for="">Owner Sales Program</label>
                    <select name="owner_sales_prog" id="owner_sales_prog" class="form-control">
                      <option value="">--ALL--</option>
                      <option value="ahm">AHM</option>
                      <option value="md">MD</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4 col-lg-offset-2">
                    <label for="">No Mesin</label>
                    <input type="text" name="nosin" id="nosin" class="form-control">
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-lg-12 col-lg-offset-9">
                    <button class="btn btn-primary btn-flat " type="button" id="btnirregular"><i class="fa fa-search"> Cari</i></button>
                  </div>
                </div>
              </form>

            </div>
          </div>
          <div class="box box-default">
          </div>
          <div class="box-body">
            <br>
            <br>
            <div class="table-responsive">
              <table class="table table-bordered table-hovered table-condensed" width="100%" id="tbl_irregular">
                <thead class="text-center">
                  <tr>
                    <th>#</th>
                    <th>Program Year</th>
                    <th>Program Month</th>
                    <th>No.Juklak</th>
                    <th>Juklak Deskription</th>
                    <th>ID Sales Program</th>
                    <th>Sales Program Description</th>
                    <th>No.Frame</th>
                    <th>No.Engine</th>
                    <th>Dealer Code</th>
                    <th>Dealer Description</th>
                    <th>Unit Type</th>
                    <!-- <th>status</th> -->
                    <th>Unit Type Description</th>
                    <th>Status verifikasi 2</th>
                    <th>Reject Claim API 2</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <style>
                  .bta {
                    width: 100px;
                  }
                </style>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>

</div>


</section>
<?php
  } else if ($set == "view_irregular") {
?>
  <section class="content">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <strong>
            View Claim Proposal :
          </strong>
          <?= $vc['idpm'] ?>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
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
        <div class="table-responsive">
          <table class="table " id="tbl_clamss" border=1>
            <thead class="text-center">
              <tr>
                <th>Program Year</th>
                <th>Program Month</th>
                <th>No.Juklak</th>
                <th>Juklak Deskription</th>
                <th>ID Sales Program</th>
                <th>Sales Program Description</th>
                <th>No.Frame</th>
                <th>No.Engine</th>
                <th>MD Approve Data</th>
                <th>Dealer Code</th>
                <th>Dealer Description</th>
                <th>Unit Type</th>
                <th>Unit Type Description</th>
              </tr>
            </thead>
            <style>
              .bta {
                width: 100px;
              }
            </style>
            <tbody>
              <tr>
                <td><?= $vc['yr'] ?></td>
                <td><?= $vc['prd'] ?></td>
                <td><?= "" ?></td>
                <td><?= "" ?></td>
                <td><?= $vc['idpm'] ?></td>
                <td><?= $vc['judul_kegiatan'] ?></td>
                <td><?= $vc['no_rangka'] ?></td>
                <td><?= $vc['nos'] ?></td>
                <td><?= $vc['tgl_approve_reject_md'] ?></td>
                <td><?= $vc['kode_dealer_md'] ?></td>
                <td><?= $vc['nama_dealer'] ?></td>
                <td><?= $vc['tipe_motor'] ?></td>
                <td><?= $vc['tipe_ahm'] ?></td>
              </tr>
            </tbody>
          </table>
          <br>
          <br>
          <div class="table-responsive">
            <table class="table  table-hover table-striped" border="1">
              <thead>
                <tr>
                  <th>Unit Type</th>
                  <th>Unit Type Description</th>
                  <th>Color Code</th>
                  <th>Color Description</th>
                  <th>Total Diskon Faktur</th>
                  <th>No. Faktur</th>
                  <th>Faktur Date</th>
                  <th>BAST Date</th>
                  <th>Payment Type</th>
                  <th>Fincoy Code</th>
                  <th>Fincoy Description</th>
                  <th>NIK</th>
                  <th>Dealer Submit Date</th>
                  <th>Owner Sales Program</th>
                  <th>Claim Category</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($tb2['jenis_beli'] == "Cash") {
                  $diskon = $tb2['voucher_1'];
                } else {
                  $diskon = $tb2['voucher_2'];
                }
                ?>
                <?php
                if ($tb2['jenis'] == "ahm_md") {
                  $js = "AHM & MD";
                } else {
                  $js = strtoupper($tb2['jenis']);
                }
                ?>
                <?php
                if ($tb2['ir'] == 0) {
                  $cat = "Regular";
                } else if ($tb2['ir'] == 1) {
                  $cat = "Irregular";
                } else {
                  $cat = "";
                }
                ?>
                <tr>
                  <td><?= $tb2['tipe_motor'] ?></td>
                  <td><?= $tb2['tipe_ahm'] ?></td>
                  <td><?= $tb2['id_warna'] ?></td>
                  <td><?= $tb2['msw'] ?></td>
                  <td><?= number_format($diskon) ?></td>
                  <td><?= $tb2['no_invoice'] ?></td>
                  <td><?= $tb2['tgl_cetak_invoice'] ?></td>
                  <td><?= $tb2['tgl_bastk'] ?></td>
                  <td><?= $tb2['jenis_beli'] ?></td>
                  <td> <?= $tb2['id_finance_company'] ?> </td>
                  <td> <?= $tb2['finance_company'] ?> </td>
                  <td><?= $tb2['no_ktp'] ?></td>
                  <td><?= $tb2['tgl_ajukan_claim'] ?></td>
                  <td><?= $js ?></td>
                  <td><?= $cat ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="box box-default">
      <div class="box-header">
        <h3 class="box-title">
          Detail Data Kelengkapan Document
        </h3>
      </div>
      <div class="box-body">
        <table class="table table-hover table-striped text-center" border="1">
          <thead>
            <tr>
              <th>Label Documnet</th>
              <th>Attachment Dokument</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sy = $this->db->get_where('tr_sales_program_syarat', ['id_program_md' => $vc['idpm']])->result_array();
            foreach ($sy as $y) :
            ?>
              <tr>
                <td><?= $y['syarat_ketentuan'] ?></td>
                <td><a class="btn btn-danger" disabled>Download</a></td>
              </tr>
            <?php
            endforeach;
            ?>
          </tbody>
        </table>
      </div>
    </div>
    <a href="<?= base_url('h1/claim_program_ahm') ?>" class="btn btn-flat btn-primary">Kembali</a>
  </section>
<?php
  } else if ($set == "addfield") {
?>
  <section class="content">
    <div class="box box-default">
      <div class="box-header with-border">
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div>
      <div class="box-body">
        <?php
        if (isset($_SESSION['messege']) && $_SESSION['messege'] <> '') {
        ?>
          <div class="alert alert-success alert-dismissable">
            <strong><?php echo $_SESSION['messege'] ?></strong>
            <button class="close" data-dismiss="alert">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
          </div>
        <?php
        }
        $_SESSION['pesan'] = '';
        ?>
        <form action="<?= base_url('h1/claim_program_ahm/updmemo1/') ?>" method="get" id="form_memo">
          <div class="row">
          </div>
          <div class="row">
            <div class="col-lg-4 ">
              <label for="">Reason Upload</label>
              <select name="reasonmemo" id="reasonmemo" class="form-control" required>
                <option value="">-choose-</option>
                <option value="Force majeure">Force majeure</option>
                <option value="System failure">System failure</option>
                <option value="Import CBU Administration">Import CBU Administration</option>
                <option value="Indent launching">Indent launching</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-4 ">
              <label for="">Memo Referensi</label>
              <input type="text" name="memo2" id="memo2" class="form-control" required>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-lg-12 ">
              <button class="btn btn-primary btn-flat" type="submit" id="btn1" onclick="return confirm('yakin menambahkan memo?')">Simpan</button>
              <a href="<?= base_url('h1/claim_irregular_case') ?>" class="btn btn-danger btn-flat">Kembali</a>
            </div>
          </div>
          <br>
          <div class="table-responsive">
            <table class="table" id="tb_memo">
              <thead class="text-center">
                <tr>
                  <th>#</th>
                  <th>No.</th>
                  <th>Program Year</th>
                  <th>Program Month</th>
                  <th>No.Juklak</th>
                  <th>ID Sales Program</th>
                  <th>Sales Program Description</th>
                  <th>No.Frame</th>
                  <th>No.Engine</th>
                  <th>Dealer Code</th>
                  <th>Dealer Description</th>
                  <th>Unit Type</th>
                  <th>Unit Type Description</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 1;
                foreach ($query as $q) {
                ?>
                  <tr>
                    <td><input type="checkbox" name="claim[]" id="claim" value="<?= $q['idclaim'] ?>"></td>
                    <td><?= $i++ ?></td>
                    <td><?= $q['yr'] ?></td>
                    <td><?= $q['prd'] ?></td>
                    <td><?= $q['asm'] ?></td>
                    <td><?= $q['idpm'] ?></td>
                    <td><?= $q['judul_kegiatan'] ?></td>
                    <td><?= $q['no_rangka'] ?></td>
                    <td><?= $q['nms'] ?></td>
                    <td><?= $q['kode_dealer_md'] ?></td>
                    <td><?= $q['nama_dealer'] ?></td>
                    <td><?= $q['tipe_motor'] ?></td>
                    <td><?= $q['tipe_ahm'] ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
          </div>
          </table>
        </form>
      </div>
    </div>
  </section>
<?php
  }
?>
</div>
<script>
  $(document).ready(function() {
    $('#tb_memo').DataTable({
      "processing": true,
      "searching": false,
      "lengthChange": false
    });
  });
</script>
<script>
  'use strict';

  (function() {
    var tablist = document.querySelectorAll('[role="tablist"]')[0];
    var tabs;
    var panels;

    generateArrays();

    function generateArrays() {
      tabs = document.querySelectorAll('[role="tab"]');
      panels = document.querySelectorAll('[role="tabpanel"]');
    }


    var keys = {
      end: 35,
      home: 36,
      left: 37,
      up: 38,
      right: 39,
      down: 40,
      delete: 46,
      enter: 13,
      space: 32,
    };

    // Add or subtract depending on key pressed
    var direction = {
      37: -1,
      38: -1,
      39: 1,
      40: 1,
    };

    // Bind listeners
    for (var i = 0; i < tabs.length; ++i) {
      addListeners(i);
    }

    function addListeners(index) {
      tabs[index].addEventListener('click', clickEventListener);
      tabs[index].addEventListener('keydown', keydownEventListener);
      tabs[index].addEventListener('keyup', keyupEventListener);

      // Build an array with all tabs (<button>s) in it
      tabs[index].index = index;
    }

    // When a tab is clicked, activateTab is fired to activate it
    function clickEventListener(event) {
      var tab = event.target;
      activateTab(tab, false);
    }

    // Handle keydown on tabs
    function keydownEventListener(event) {
      var key = event.keyCode;

      switch (key) {
        case keys.end:
          event.preventDefault();
          // Activate last tab
          focusLastTab();
          break;
        case keys.home:
          event.preventDefault();
          // Activate first tab
          focusFirstTab();
          break;

          // Up and down are in keydown
          // because we need to prevent page scroll >:)
        case keys.up:
        case keys.down:
          determineOrientation(event);
          break;
      }
    }

    // Handle keyup on tabs
    function keyupEventListener(event) {
      var key = event.keyCode;

      switch (key) {
        case keys.left:
        case keys.right:
          determineOrientation(event);
          break;
        case keys.delete:
          determineDeletable(event);
          break;
        case keys.enter:
        case keys.space:
          activateTab(event.target);
          break;
      }
    }

    // When a tablistâ€™s aria-orientation is set to vertical,
    // only up and down arrow should function.
    // In all other cases only left and right arrow function.
    function determineOrientation(event) {
      var key = event.keyCode;
      var vertical = tablist.getAttribute('aria-orientation') == 'vertical';
      var proceed = false;

      if (vertical) {
        if (key === keys.up || key === keys.down) {
          event.preventDefault();
          proceed = true;
        }
      } else {
        if (key === keys.left || key === keys.right) {
          proceed = true;
        }
      }

      if (proceed) {
        switchTabOnArrowPress(event);
      }
    }

    // Either focus the next, previous, first, or last tab
    // depending on key pressed
    function switchTabOnArrowPress(event) {
      var pressed = event.keyCode;

      if (direction[pressed]) {
        var target = event.target;
        if (target.index !== undefined) {
          if (tabs[target.index + direction[pressed]]) {
            tabs[target.index + direction[pressed]].focus();
          } else if (pressed === keys.left || pressed === keys.up) {
            focusLastTab();
          } else if (pressed === keys.right || pressed == keys.down) {
            focusFirstTab();
          }
        }
      }
    }

    // Activates any given tab panel
    function activateTab(tab, setFocus) {
      setFocus = setFocus || true;
      // Deactivate all other tabs
      deactivateTabs();

      // Remove tabindex attribute
      tab.removeAttribute('tabindex');

      // Set the tab as selected
      tab.setAttribute('aria-selected', 'true');

      // Get the value of aria-controls (which is an ID)
      var controls = tab.getAttribute('aria-controls');

      // Remove is-hidden class from tab panel to make it visible
      document.getElementById(controls).classList.remove('is-hidden');

      // Set focus when required
      if (setFocus) {
        tab.focus();
      }
    }


    function deactivateTabs() {
      for (var t = 0; t < tabs.length; t++) {
        tabs[t].setAttribute('tabindex', '-1');
        tabs[t].setAttribute('aria-selected', 'false');
      }

      for (var p = 0; p < panels.length; p++) {
        panels[p].classList.add('is-hidden');
      }
    }


    function focusFirstTab() {
      tabs[0].focus();
    }


    function focusLastTab() {
      tabs[tabs.length - 1].focus();
    }

    // Detect if a tab is deletable
    function determineDeletable(event) {
      var target = event.target;

      if (target.getAttribute('data-deletable') !== null) {
        // Delete target tab
        deleteTab(event, target);

        // Update arrays related to tabs widget
        generateArrays();

        // Activate the closest tab to the one that was just deleted
        if (target.index - 1 < 0) {
          activateTab(tabs[0]);
        } else {
          activateTab(tabs[target.index - 1]);
        }
      }
    }

    // Deletes a tab and its panel
    function deleteTab(event) {
      var target = event.target;
      var panel = document.getElementById(target.getAttribute('aria-controls'));

      target.parentElement.removeChild(target);
      panel.parentElement.removeChild(panel);
    }
  })();
</script>
<script>
  $('#form_memo').submit(function(e) {
    e.preventDefault();
    var memo2 = $('#memo2').val();
    var centang = $('#claim:checked');
    if (centang.length === 0) {
      alert('Maaf, data tidak ada yang di centang');
    } else {
      document.getElementById('form_memo').submit();
    }
    return false;
  })
</script>
<script>
  $('#btnirregular').click(function() {
    var nojuk1 = $('#no_juk').val();
    var idsales1 = $('#id_sales_prg').val();
    var year1 = $('#program_year').val();
    var mon1 = $('#prog_mon').val();
    var dcode1 = $('#dealer_code').val();
    var owner1 = $('#owner_sales_prog').val();
    var reason1 = $('#reason').val();
    var memo1 = $('#memo').val();
    var nosin1 = $('#nosin').val();
    var send1 = $('#sendahm').val();
    var table;

    table = $('#tbl_irregular').DataTable({
      "processing": true,
      "searching": false,
      "serverSide": true,
      "bDestroy": true,
      "lengthChange": false,
      "language": {
        "processing": "Mohon tunggu...",
        "infoFiltered": ""
      },
      "order": [],
      "ajax": {
        "url": "<?= base_url('h1/claim_program_ahm/show_irregular') ?>",
        "type": "POST",
        "data": {
          nojuk1: nojuk1,
          idsales1: idsales1,
          year1: year1,
          mon1: mon1,
          dcode1: dcode1,
          owner1: owner1,
          nosin1: nosin1,
          send1: send1,
        }
      },
      "fnRowCallback": function(nRow, aData, iDisplayIndex) {

        if (aData[12] == "1") {
          $('td', nRow).css('background-color', 'rgb(0,255,0, 0.4)');
        }
      },
      "columnDefs": [{
        "targets": [12],
        "visible": false,
        "orderable": false,
      }, ],
    });

  });
</script>
<script>

//testing

  $('#btnregular').click(function() {
    var nojuk = $('#nojuklak1').val();
    var idsales = $('#id_sales_prg1').val();
    var year = $('#program_year1').val();
    var mon = $('#prog_mon1').val();
    var dcode = $('#dealer_code1').val();
    var owner = $('#owner_sales_prog1').val();
    var nosin = $('#nosin1').val();
    var send = $('#sendahm1').val();
    var table;

    table = $('#tbl_clam').DataTable({

      "processing": true,
      "searching": false,
      "serverSide": true,
      "bDestroy": true,
      "lengthChange": false,
      "language": {
        "processing": "Mohon tunggu...",
        "infoFiltered": ""
      },
      "order": [],
      "ajax": {
        "url": "<?= base_url('h1/claim_program_ahm/show_tb') ?>",
        "type": "POST",
        "data": {
          nojuk: nojuk,
          idsales: idsales,
          year: year,
          mon: mon,
          dcode: dcode,
          owner: owner,
          nosin: nosin,
          send: send,
        },
      },
      "fnRowCallback": function(nRow, aData, iDisplayIndex) {

        if (aData[18] == "1") {
          $('td', nRow).css('background-color', 'rgb(0,255,0, 0.4)');
        }
      },
      "columnDefs": [{
        "targets": [18],
        "orderable": false,
        "visible": false,
      }, ],
    });
  });
</script>