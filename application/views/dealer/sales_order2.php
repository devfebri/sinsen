<?php
function bln()
{
  $bulan = $bl = $month = date("m");
  switch ($bulan) {
    case "1":
      $bulan = "Januari";
      break;
    case "2":
      $bulan = "Februari";
      break;
    case "3":
      $bulan = "Maret";
      break;
    case "4":
      $bulan = "April";
      break;
    case "5":
      $bulan = "Mei";
      break;
    case "6":
      $bulan = "Juni";
      break;
    case "7":
      $bulan = "Juli";
      break;
    case "8":
      $bulan = "Agustus";
      break;
    case "9":
      $bulan = "September";
      break;
    case "10":
      $bulan = "Oktober";
      break;
    case "11":
      $bulan = "November";
      break;
    case "12":
      $bulan = "Desember";
      break;
  }
  $bln = $bulan;
  return $bln;
}
?>
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
<?php if (isset($_GET['id'])) { ?>

  <body onload="cek_spk()">
  <?php } elseif (isset($_GET['id_c'])) { ?>

    <body onload="cek_spk_gc()">
    <?php } else { ?>

      <body onload="cek()">
      <?php } ?>
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <?php echo $title; ?>
          </h1>
          <ol class="breadcrumb">
            <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
            <li class="">Customer</li>
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
                  <a href="dealer/sales_order">
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
                    <form class="form-horizontal" action="dealer/sales_order/save" method="post" enctype="multipart/form-data">
                      <div class="box-body">
                        <button class="btn btn-block btn-primary btn-flat" disabled> SALES ORDER </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No SPK *</label>
                          <div class="col-sm-4">
                            <select class="form-control select2" name="no_spk" required id="no_spk">
                              <option value="">- choose -</option>
                              <?php
                              foreach ($dt_spk->result() as $val) {
                                $spk = $this->db->get_where('tr_spk', ['no_spk' => $val->no_spk])->row();
                                echo "
                          <option value='$val->no_spk'>$val->no_spk - $spk->nama_konsumen</option>;
                          ";
                              }
                              foreach ($dt_spk2->result() as $val) {
                                $spk = $this->db->get_where('tr_spk', ['no_spk' => $val->no_spk])->row();

                                echo "
                          <option value='$val->no_spk'>$val->no_spk - $spk->nama_konsumen</option>;
                          ";
                              }
                              ?>
                            </select>
                          </div>
                          <div class="col-sm-4">
                            <button type="button" onclick="cek_spk('ya')" class="btn btn-flat btn-primary">Generate</button>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No. Sales Order</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="id_sales_order" name="id_sales_order">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl Pembuatan SO</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="tgl_sales_order" name="tgl_sales_order" value="<?= date('Y-m-d') ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <?php $id_dealer = $this->m_admin->cari_dealer();
                          $dl = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row() ?>
                          <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="id_dealer" name="id_dealer" value="<?= $dl->kode_dealer_md ?>">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly value="<?= $dl->nama_dealer ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Sales People(ID)</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="sales_people" name="sales_people">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">NPWP</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="npwp" name="npwp" value="<?= $dl->npwp ?>">
                          </div>
                        </div>
                        <button class="btn btn-block btn-success btn-flat" disabled> DATA KONSUMEN </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="id_customer" name="id_customer">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Sesuai Identitas</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Nama Sesuai Identitas" readonly id="nama_konsumen" name="nama_konsumen">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No KTP/KITAS</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No KTP/KITAS" name="no_ktp" id="no_ktp" readonly>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No NPWP" readonly id="no_npwp" name="no_npwp">
                          </div>
                          <!-- <label for="inputEmail3" class="col-sm-2 control-label">No KK</label>
                    <div class="col-sm-4">
                      <input type="text" readonly class="form-control" placeholder="No KK" name="no_kk" id="no_kk">                    
                    </div> -->
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kelurahan" id="id_kelurahan">
                            <input type="text" class="form-control" readonly id="kelurahan" placeholder="Kelurahan Domisili" name="kelurahan">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                            <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan Domisili" name="kecamatan">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                            <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten Domisili" id="kabupaten" name="kabupaten">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_provinsi" id="id_provinsi">
                            <input type="text" class="form-control" readonly placeholder="Provinsi Domisili" id="provinsi" name="provinsi">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Alamat Domisili" readonly id="alamat" name="alamat">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kodepos</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="kodepos" readonly name="kodepos" placeholder="Kodepos">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Apakah alamat domisili sama dengan alamat di KTP?</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="tanya" readonly name="tanya">
                          </div>
                        </div>
                        <span id="tampil_alamat">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Sesuai KTP</label>
                            <div class="col-sm-4">
                              <select class="form-control select2" id="id_kelurahan2" name="id_kelurahan2" onchange="take_kec2()">
                                <option value="">- choose -</option>
                              </select>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Sesuai KTP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_kecamatan" id="id_kecamatan2">
                              <input type="text" class="form-control" readonly id="kecamatan2" placeholder="Kecamatan Sesuai KTP" name="kecamatan2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Sesuai KTP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_kabupaten" id="id_kabupaten2">
                              <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten Sesuai KTP" id="kabupaten2" name="kabupaten2">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Sesuai KTP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_provinsi" id="id_provinsi2">
                              <input type="text" class="form-control" readonly placeholder="Provinsi Sesuai KTP" id="provinsi2" name="provinsi2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Alamat Sesuai KTP</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" placeholder="Alamat Sesuai KTP" name="alamat2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kodepos Sesuai KTP</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" id="kodepos2" readonly name="kodepos2" placeholder="Kodepos Sesuai KTP">
                            </div>
                          </div>
                        </span>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No HP - Pertama</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No HP - Pertama" readonly id="no_hp" name="no_hp">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No HP - Kedua</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No HP - Kedua" readonly id="no_hp_2" name="no_hp_2">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No Telp" readonly id="no_telp" name="no_telp">
                          </div>
                        </div>
                        <button class="btn btn-block btn-danger btn-flat" disabled> DATA KENDARAAN </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Type *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="tipe_ahm" placeholder="Type" name="tipe_ahm">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Harga</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="harga" placeholder="Harga" name="harga">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="warna" placeholder="Warna" name="warna">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">PPN</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="ppn" placeholder="PPN" name="ppn">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No Mesin *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="no_mesin" required onchange="return back(event)" onkeypress="return nihil(event)" onpaste="return false" autocomplete="off" placeholder="No Mesin" name="no_mesin">
                            <input type="hidden" class="form-control" id="id_tipe_kendaraan" required name="id_tipe_kendaraan">
                            <input type="hidden" class="form-control" id="id_warna" name="id_warna">
                          </div>
                          <div class="col-sm-1">
                            <button class="btn btn-primary btn-flat btn-sm" data-toggssle="modal" data-targsset="#Nosinmodal" type="button" id="browseNosin" onclick="openModal()"><i class="fa fa-search"></i> Browse</button>
                          </div>
                          <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">Harga Off The Road</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="harga_off" placeholder="Harga Off The Road" name="harga_off">
                          </div>
                        </div>
                        <div class="form-group">
                          <!-- <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                    <div class="col-sm-4">
                      <input type="text" readonly class="form-control" id="no_rangka"  placeholder="No Rangka" name="no_rangka">                    
                    </div> -->
                          <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">Biaya BBN</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="biaya_bbn" placeholder="Biaya BBN" name="biaya_bbn">
                          </div>
                        </div>
                        <div class="form-group">
                          <!--       <label for="inputEmail3" class="col-sm-2 control-label">Tahun Produksi</label>
                    <div class="col-sm-4">                    
                     <input type="text" class="form-control" readonly id="tahun_produksi"  placeholder="Tahun Produksi" name="tahun_produksi">                                                 
                    </div>                   
 -->
                          <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">Harga On The Road</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="harga_on" placeholder="Harga On The Road" name="harga_on">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama STNK/BPKB</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="nama_bpkb" readonly placeholder="Nama STNK/BPKB" name="nama_bpkb">
                          </div>
                        </div>

                        <button class="btn btn-block btn-warning btn-flat" disabled> SISTEM PEMBELIAN TUNAI </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tipe Pembelian</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Tipe Pembelian" readonly id="tipe_pembelian" name="tipe_pembelian">
                          </div>
                        </div>
                        <span id="isi_cash">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">On/Off The Road</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="On/Off The Road" readonly id="the_road" name="the_road">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Harga Tunai</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Harga Tunai" readonly id="harga_tunai2" name="harga_tunai2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Diskon</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" readonly id="diskon1" placeholder="Diskon">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Program</label>
                            <div class="col-sm-3">
                              <input type="text" class="form-control" placeholder="Program" readonly id="program_umum" name="program">
                            </div>
                            <div class="col-sm-1">
                              <input type="checkbox" name="chk_program_umum" id="chk_program_umum">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Nilai Voucher</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Nilai Voucher" name="nilai_voucher" readonly id="nilai_voucher">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Program Gabungan</label>
                            <div class="col-sm-3">
                              <input type="text" class="form-control" placeholder="Program Gabungan" readonly id="program_gabungan" name="program_gabungan">
                            </div>
                            <div class="col-sm-1">
                              <input type="checkbox" name="chk_program_gabungan" id="chk_program_gabungan">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Voucher Tambahan</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Voucher Tambahan" name="voucher_tambahan" id="voucher_tambahan" readonly>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-sm-6"></div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Total Bayar</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Total Bayar" name="total_bayar" id="total_bayar" readonly>
                            </div>
                          </div>
                        </span>
                        <span id="isi_kredit">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Finance Company</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Finance Company" name="finance_company" readonly id="finance_company">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Down Payment Gross</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" readonly placeholder="Down Payment Gross" name="dp" id="dp">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Diskon</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" readonly id="diskon2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Program</label>
                            <div class="col-sm-3">
                              <input type="text" class="form-control" placeholder="Program" name="program2" readonly id="program2">
                            </div>
                            <div class="col-sm-1">
                              <input type="checkbox" name="chk_program_umum2" id="chk_program_umum2">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Nilai Voucher</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Nilai Voucher" name="nilai_voucher2" id="nilai_voucher2" readonly>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Program Gabungan</label>
                            <div class="col-sm-3">
                              <input type="text" class="form-control" placeholder="Program Gabungan" name="program_gabungan2" readonly id="program_gabungan2">
                            </div>
                            <div class="col-sm-1">
                              <input type="checkbox" name="chk_program_gabungan2" id="chk_program_gabungan2">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Voucher Tambahan</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Voucher Tambahan" name="voucher_tambahan2" readonly id="voucher_tambahan2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">No PO Leasing *</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="No PO Leasing" name="no_po_leasing" id="no_po_leasing">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Down Payment Setor</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Down Payment Setor" name="dp_setor" readonly id="dp_setor">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Tgl PO Leasing *</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Tgl PO Leasing" name="tgl_po_leasing" id="tanggal4" autocomplete="off">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Tenor</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Tenor" name="tenor" id="tenor" readonly>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-sm-6"></div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Angsuran</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Angsuran" name="angsuran" id="angsuran" readonly>
                            </div>
                          </div>
                        </span>
                        <button class="btn btn-block btn-info btn-flat" disabled> KSU </button> <br>
                        <div class="form-group">
                          <div id="dt_ksu" style="padding-left: 20px"></div>
                        </div>
                        <hr>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Direct Gift</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Direct Gift" name="direct_gift" id="direct_gift">
                          </div>
                        </div>
                        <hr>
                        <div class="form-group">
                          <div class="col-sm-10" style="padding-left: 7%">
                            <table style="width: 39%; font-weight: bold; min-height: 40px" class="table table-condensed">
                              <?php $estimasi = $this->db->query("SELECT * FROM ms_estimasi_stnk_bpkb_cash")->row();
                              $tgl = date('Y-m-d');
                              $stnk = date("Y-m-d", strtotime("+" . $estimasi->estimasi_stnk . " days", strtotime($tgl)));
                              $bpkb = date("Y-m-d", strtotime("+" . $estimasi->estimasi_bpkb_cash . " days", strtotime($tgl)));
                              $day = 1;
                              $kirim = date("Y-m-d", strtotime("+" . $day . " days", strtotime($tgl)));
                              ?>
                              <tr>
                                <td>Tanggal Estimasi STNK</td>
                                <td>:</td>
                                <td><?= $stnk ?></td>
                              </tr>
                              <tr id="bpkb_show">
                                <td>Tanggal BPKB</td>
                                <td>:</td>
                                <td><?php echo $bpkb ?></td>
                              </tr>
                              <tr>
                                <!-- <td>Tanggal Pengiriman Unit</td><td>:</td><td><?php echo $kirim ?></td> -->
                                <td>Tanggal Pengiriman Unit</td>
                                <td>:</td>
                                <td><?php echo date('Y-m-d') ?></td>
                                <!-- <td>Tanggal Pengiriman Unit</td><td>:</td><td><input type="text" autocomplete="off" name="tgl_pengiriman" class="form-control" id="tanggal1"></td> -->
                              </tr>
                            </table>
                          </div>
                        </div>
                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-10">
                          <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                          <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>
                        </div>
                      </div><!-- /.box-footer -->
                    </form>
                  </div>
                </div>
              </div>
            </div><!-- /.box -->

            <div class="modal fade" id="Fotomodal">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    Lihat Foto
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                    <!--img src="assets/panel/files/<?php echo $row->file_foto ?>" width="100%"-->
                    None
                  </div>
                </div>
              </div>
            </div>
          <?php
          } elseif ($set == 'insert_gc') {
          ?>
            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/sales_order/gc">
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
                    <form class="form-horizontal" action="dealer/sales_order/save_gc" method="post" enctype="multipart/form-data">
                      <div class="box-body">
                        <button class="btn btn-block btn-primary btn-flat" disabled> Sales Order </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No SPK *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="no_spk_gc" onpaste="return false;" onkeypress="return false;" id="no_spk_gc" placeholder="No SPK" required>
                          </div>
                          <div class="col-sm-4">
                            <a class="btn btn-primary btn-flat btn-sm" data-toggle="modal" data-target="#Npwpmodal" type="button"><i class="fa fa-search"></i> Browse</a>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama NPWP</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="nama_npwp" placeholder="Nama NPWP" name="nama_npwp" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" placeholder="No NPWP" name="no_npwp" id="no_npwp">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Jenis GC</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="jenis_gc" placeholder="Jenis GC" name="jenis_gc" required>
                            <input type="hidden" id="jenis_beli" name="jenis_beli">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No Telp Perusahaan</label>
                          <div class="col-sm-4">
                            <input type="text" id="no_telp" readonly class="form-control" placeholder="No Telp Perusahaan" name="no_telp">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl Berdiri Perusahaan</label>
                          <div class="col-sm-4">
                            <input type="text" id="tgl_berdiri" readonly class="form-control" placeholder="Tgl Berdiri Perusahaan" name="tgl_berdiri">
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" readonly name="id_kelurahan" id="id_kelurahan">
                            <input required type="text" readonly onpaste="return false" onkeypress="return nihil(event)" name="kelurahan" data-toggle="modal" placeholder="Kelurahan Domisili" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                            <input type="text" readonly class="form-control" readonly id="kecamatan" placeholder="Kecamatan Domisili" name="kecamatan" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                            <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten Domisili" id="kabupaten" name="kabupaten" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_provinsi" id="id_provinsi">
                            <input type="text" class="form-control" readonly placeholder="Provinsi Domisili" id="provinsi" name="provinsi" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili</label>
                          <div class="col-sm-10">
                            <input type="text" readonly class="form-control" maxlength="100" placeholder="Alamat Domisili" name="alamat" id="alamat" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kodepos *</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" placeholder="Kodepos" id="kodepos" name="kodepos" required>
                          </div>
                        </div>

                        <button class="btn btn-block btn-danger btn-flat" type="button"> Data Kendaraan </button> <br>
                        <div id="showDetail"></div>
                        <br>

                        <button class="btn btn-block btn-danger btn-flat" onclick="tampil_nosin()" type="button"> Data Nomor Mesin </button> <br>
                        <div id="showNosin"></div>
                        <br>
                        <span id="tampil_po">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">No PO Leasing</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="No PO Leasing" id="no_po_leasing" name="no_po_leasing">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Tgl PO Leasing</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" autocomplete="off" placeholder="Tgl PO Leasing" id="tanggal3" name="no_po_leasing">
                            </div>
                          </div>
                        </span>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl Estimasi STNK</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Tgl Estimasi STNK" autocomplete="off" id="tanggal7" name="tgl_estimasi_stnk">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl Estimasi BPKB</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" autocomplete="off" placeholder="Tgl Estimasi BPKB" id="tanggal5" name="tgl_estimasi_bpkb">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl Estimasi Pengiriman Unit</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Tgl Estimasi Pengiriman Unit" autocomplete="off" id="tanggal6" name="tgl_pengiriman">
                          </div>
                        </div>



                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-12" align='center'>
                          <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                        </div>
                      </div><!-- /.box-footer -->
                    </form>
                  </div>
                </div>
              </div>
            </div><!-- /.box -->
            <div class="modal fade" id="Npwpmodal">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    Search and Filter SPK Group Customer
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                    <table id="example3" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th width="10%"></th>
                          <th>No SPK</th>
                          <th>Nama NPWP</th>
                          <th>Jenis GC</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $id_dealer = $this->m_admin->cari_dealer();
                        $dt_spk = $this->db->query("SELECT DISTINCT(tr_spk_gc.no_spk_gc),tr_spk_gc.nama_npwp,tr_spk_gc.jenis_gc FROM tr_spk_gc
                    LEFT JOIN tr_hasil_survey_gc ON tr_spk_gc.no_spk_gc = tr_hasil_survey_gc.no_spk_gc
                    WHERE tr_spk_gc.id_dealer = '$id_dealer' 
                    AND tr_spk_gc.no_spk_gc IN (SELECT no_spk_gc FROM tr_cdb_gc) 
                    AND tr_spk_gc.no_spk_gc NOT IN (SELECT no_spk_gc FROM tr_sales_order_gc WHERE no_spk_gc IS NOT NULL)                     
                    AND tr_spk_gc.no_spk_gc IN (SELECT no_spk_gc FROM tr_hasil_survey_gc WHERE no_spk_gc IS NOT NULL AND status_approval = 'approved')
                    AND tr_spk_gc.jenis_beli = 'Kredit'
                    AND tr_spk_gc.status = 'approved' ORDER BY tr_spk_gc.no_spk_gc ASC");
                        foreach ($dt_spk->result() as $ve2) {
                          echo "
                <tr>"; ?>
                          <td class="center">
                            <button title="Choose" onClick="Choosenpwp('<?php echo $ve2->no_spk_gc; ?>')" type="button" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>
                          </td>
                          <?php echo "
                  <td>$ve2->no_spk_gc</td>
                  <td>$ve2->nama_npwp</td>
                  <td>$ve2->jenis_gc</td>";
                          ?>
                          </tr>
                        <?php
                        }
                        $dt_spk = $this->db->query("SELECT DISTINCT(tr_spk_gc.no_spk_gc),tr_spk_gc.nama_npwp,tr_spk_gc.jenis_gc FROM tr_spk_gc
                    LEFT JOIN tr_hasil_survey_gc ON tr_spk_gc.no_spk_gc = tr_hasil_survey_gc.no_spk_gc
                    WHERE tr_spk_gc.id_dealer = '$id_dealer' 
                    AND tr_spk_gc.no_spk_gc IN (SELECT no_spk_gc FROM tr_cdb_gc)                     
                    AND tr_spk_gc.jenis_beli = 'Cash'
                    AND tr_spk_gc.status = 'approved' ORDER BY tr_spk_gc.no_spk_gc ASC");
                        foreach ($dt_spk->result() as $ve2) {
                          echo "
                <tr>"; ?>
                          <td class="center">
                            <button title="Choose" onClick="Choosenpwp('<?php echo $ve2->no_spk_gc; ?>')" type="button" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>
                          </td>
                          <?php echo "
                  <td>$ve2->no_spk_gc</td>
                  <td>$ve2->nama_npwp</td>
                  <td>$ve2->jenis_gc</td>";
                          ?>
                          </tr>
                        <?php
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          <?php
          } elseif ($set == 'syarat_bbn') {
            $row = $dt_so->row();
          ?>
            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/sales_order/gc">
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
                    <form class="form-horizontal" action="dealer/sales_order/save_syarat_gc" method="post" enctype="multipart/form-data">
                      <div class="box-body">
                        <button class="btn btn-block btn-primary btn-flat" disabled> Sales Order </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No SPK *</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_sales_order_gc" value="<?php echo $row->id_sales_order_gc ?>">
                            <input type="text" readonly value="<?php echo $row->no_spk_gc ?>" class="form-control" name="no_spk_gc" onpaste="return false;" onkeypress="return false;" id="no_spk_gc" placeholder="No SPK" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama NPWP</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="nama_npwp" placeholder="Nama NPWP" name="nama_npwp" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" placeholder="No NPWP" name="no_npwp" id="no_npwp">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Jenis GC</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="jenis_gc" placeholder="Jenis GC" name="jenis_gc" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No Telp Perusahaan</label>
                          <div class="col-sm-4">
                            <input type="text" id="no_telp" readonly class="form-control" placeholder="No Telp Perusahaan" name="no_telp">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl Berdiri Perusahaan</label>
                          <div class="col-sm-4">
                            <input type="text" id="tgl_berdiri" readonly class="form-control" placeholder="Tgl Berdiri Perusahaan" name="tgl_berdiri">
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" readonly name="id_kelurahan" id="id_kelurahan">
                            <input required type="text" readonly onpaste="return false" onkeypress="return nihil(event)" name="kelurahan" data-toggle="modal" placeholder="Kelurahan Domisili" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                            <input type="text" readonly class="form-control" readonly id="kecamatan" placeholder="Kecamatan Domisili" name="kecamatan" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                            <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten Domisili" id="kabupaten" name="kabupaten" required>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_provinsi" id="id_provinsi">
                            <input type="text" class="form-control" readonly placeholder="Provinsi Domisili" id="provinsi" name="provinsi" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili</label>
                          <div class="col-sm-10">
                            <input type="text" readonly class="form-control" maxlength="100" placeholder="Alamat Domisili" name="alamat" id="alamat" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kodepos *</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" placeholder="Kodepos" id="kodepos" name="kodepos" required>
                          </div>
                        </div>

                        <button class="btn btn-block btn-danger btn-flat" disabled type="button"> Syarat BBN </button>
                        <?php
                        if ($row->jenis_gc == 'Swasta/BUMN/Koperasi') {
                        ?>
                          <table id="example2" class="table table-bordered table-hovered myTable1" width="100%">
                            <tr>
                              <td align="center" colspan="6"><b>Swasta/BUMN (Wajib)</b></td>
                            </tr>
                            <tr>
                              <th></th>
                              <th>SITU *</th>
                              <th>SIUP *</th>
                              <th>TDP *</th>
                              <th>NPWP *</th>
                              <th>Surat Kuasa *</th>
                            </tr>
                            <tr>
                              <td><input onclick="cek(this.form.cekbox)" type="checkbox" /> Check All</td>
                              <td><input type="checkbox" id="cekbox" name="swasta_situ" <?php if ($row->situ == 'on') echo "checked"; ?>></td>
                              <td><input type="checkbox" id="cekbox" name="swasta_siup" <?php if ($row->siup == 'on') echo "checked"; ?>></td>
                              <td><input type="checkbox" id="cekbox" name="swasta_tdp" <?php if ($row->tdp == 'on') echo "checked"; ?>></td>
                              <td><input type="checkbox" id="cekbox" name="swasta_npwp" <?php if ($row->npwp == 'on') echo "checked"; ?>></td>
                              <td><input type="checkbox" id="cekbox" name="swasta_kuasa" <?php if ($row->surat_kuasa == 'on') echo "checked"; ?>></td>
                            </tr>
                          </table>
                        <?php
                        } elseif ($row->jenis_gc == 'Instansi') {
                        ?>
                          <table id="example2" class="table table-bordered table-hovered myTable1" width="100%">
                            <tr>
                              <td align="center" colspan="6"><b>Instansi (Wajib)</b></td>
                            </tr>
                            <tr>
                              <th></th>
                              <th>NPWP *</th>
                              <th>Surat Pernyataan *</th>
                              <th>Surat Kuasa *</th>
                            </tr>
                            <tr>
                              <td><input onclick="cek(this.form.cekbox)" type="checkbox"> Check All</td>
                              <td><input type="checkbox" id="cekbox" name="inst_npwp" <?php if ($row->npwp == 'on') echo "checked"; ?>></td>
                              <td><input type="checkbox" id="cekbox" name="inst_pernyataan" <?php if ($row->surat_pernyataan == 'on') echo "checked"; ?>></td>
                              <td><input type="checkbox" id="cekbox" name="inst_kuasa" <?php if ($row->surat_kuasa == 'on') echo "checked"; ?>></td>
                            </tr>
                          </table>
                        <?php
                        } elseif ($row->jenis_gc == 'Joint Promo') {
                        ?>
                          <table id="example2" class="table table-bordered table-hovered myTable1" width="100%">
                            <tr>
                              <td align="center" colspan="6"><b>Joint Promo (Wajib)</b></td>
                            </tr>
                            <tr>
                              <th></th>
                              <th>Surat Pernyataan *</th>
                            </tr>
                            <tr>
                              <td><input onclick="cek(this.form.cekbox)" type="checkbox"> Check All</td>
                              <td><input type="checkbox" id="cekbox" name="joint_pernyataan" <?php if ($row->surat_pernyataan == 'on') echo "checked"; ?>>
                                <input style="display: none;" type="checkbox" id="cekbox" name="joint_pernyataan_cc"></td>
                            </tr>
                          </table>
                        <?php } ?>
                        <br>
                        <button class="btn btn-block btn-primary btn-flat" disabled type="button"> Detail No Mesin </button>
                        <table id="example2" class="table table-bordered table-hovered myTable1" width="100%">
                          <tr>
                            <th>No Mesin</th>
                            <th>Tipe - Warna</th>
                            <th>Nama STNK/BPKB</th>
                            <th>Keterangan</th>
                          </tr>
                          <?php
                          $no = 1;
                          $cek = $this->db->query("SELECT tr_sales_order_gc_nosin.*,tr_scan_barcode.no_mesin,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_sales_order_gc_nosin INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
                    INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                    INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                    WHERE no_spk_gc = '$row->no_spk_gc'");
                          foreach ($cek->result() as $isi) {
                            $jum = $cek->num_rows();
                            echo "
                      <tr>
                        <td>$isi->no_mesin</td>
                        <td>$isi->tipe_ahm $isi->warna</td>
                        <td>
                          <input type='text' name='nama_stnk_$no' value='$isi->nama_stnk'>
                        </td>
                        <td>
                          <input type='hidden' name='no_mesin_$no' value='$isi->no_mesin'>
                          <input type='hidden' name='jum_nosin' value='$jum'>
                          <input type='text' name='keterangan_$no' value='$isi->keterangan'>
                        </td>
                      </tr>";
                            $no++;
                          }
                          ?>
                        </table>




                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-10">
                          <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                        </div>
                      </div><!-- /.box-footer -->
                    </form>
                  </div>
                </div>
              </div>
            </div><!-- /.box -->
            <div class="modal fade" id="Npwpmodal">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    Search and Filter SPK Group Customer
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                    <table id="example3" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th width="10%"></th>
                          <th>No SPK</th>
                          <th>Nama NPWP</th>
                          <th>Jenis GC</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $id_dealer = $this->m_admin->cari_dealer();
                        $dt_spk = $this->db->query("SELECT DISTINCT(tr_spk_gc.no_spk_gc),tr_spk_gc.nama_npwp,tr_spk_gc.jenis_gc FROM tr_spk_gc
                    LEFT JOIN tr_hasil_survey_gc ON tr_spk_gc.no_spk_gc = tr_hasil_survey_gc.no_spk_gc
                    WHERE tr_spk_gc.id_dealer = '$id_dealer' 
                    AND tr_spk_gc.no_spk_gc IN (SELECT no_spk_gc FROM tr_cdb_gc) 
                    AND tr_spk_gc.no_spk_gc NOT IN (SELECT no_spk_gc FROM tr_sales_order_gc WHERE no_spk_gc IS NOT NULL)                     
                    AND tr_spk_gc.no_spk_gc IN (SELECT no_spk_gc FROM tr_hasil_survey_gc WHERE no_spk_gc IS NOT NULL AND status_approval = 'approved')
                    AND tr_spk_gc.jenis_beli = 'Kredit'
                    AND tr_spk_gc.status = 'approved' ORDER BY tr_spk_gc.no_spk_gc ASC");
                        foreach ($dt_spk->result() as $ve2) {
                          echo "
                <tr>"; ?>
                          <td class="center">
                            <button title="Choose" onClick="Choosenpwp('<?php echo $ve2->no_spk_gc; ?>')" type="button" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>
                          </td>
                          <?php echo "
                  <td>$ve2->no_spk_gc</td>
                  <td>$ve2->nama_npwp</td>
                  <td>$ve2->jenis_gc</td>";
                          ?>
                          </tr>
                        <?php
                        }
                        $dt_spk = $this->db->query("SELECT DISTINCT(tr_spk_gc.no_spk_gc),tr_spk_gc.nama_npwp,tr_spk_gc.jenis_gc FROM tr_spk_gc
                    LEFT JOIN tr_hasil_survey_gc ON tr_spk_gc.no_spk_gc = tr_hasil_survey_gc.no_spk_gc
                    WHERE tr_spk_gc.id_dealer = '$id_dealer' 
                    AND tr_spk_gc.no_spk_gc IN (SELECT no_spk_gc FROM tr_cdb_gc)                     
                    AND tr_spk_gc.jenis_beli = 'Cash'
                    AND tr_spk_gc.status = 'approved' ORDER BY tr_spk_gc.no_spk_gc ASC");
                        foreach ($dt_spk->result() as $ve2) {
                          echo "
                <tr>"; ?>
                          <td class="center">
                            <button title="Choose" onClick="Choosenpwp('<?php echo $ve2->no_spk_gc; ?>')" type="button" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>
                          </td>
                          <?php echo "
                  <td>$ve2->no_spk_gc</td>
                  <td>$ve2->nama_npwp</td>
                  <td>$ve2->jenis_gc</td>";
                          ?>
                          </tr>
                        <?php
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          <?php
          } elseif ($set == 'set_bastk_gc') {
          ?>
            <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
            <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
            <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/sales_order/gc">
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
                    <form class="form-horizontal" id="form_">
                      <div class="box-body">
                        <button class="btn btn-block btn-primary btn-flat" disabled> Sales Order </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">ID Sales Order</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="id_sales_order_gc" id="id_sales_order_gc" readonly value='<?= $row->id_sales_order_gc ?>'>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Sales Order</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly value='<?= $row->tgl_so_gc ?>'>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No. SPK GC</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly value='<?= $row->no_spk_gc ?>'>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl. SPK GC</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly value='<?= $row->tgl_spk_gc ?>'>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Sales ID</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly value='<?= $row->id_flp_md ?>'>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Sales Name</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly value='<?= $row->nama_lengkap ?>'>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama NPWP</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" disabled value="<?= $row->nama_npwp ?>">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>
                          <div class="col-sm-4">
                            <input type="text" disabled class="form-control" value="<?= $row->no_npwp ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="form-input">
                            <label for="inputEmail3" class="col-sm-2 control-label">Pengiriman *</label>
                            <div class="col-sm-4">
                              <select class='form-control' name='ambil' v-model='ambil' required>
                                <option value=''>-choose-</option>
                                <option value='Dikirim'>Dikirim</option>
                                <option value='Diambil Sendiri'>Diambil Sendiri</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-input">
                            <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Pengiriman Unit *</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" value="<?= $row->lokasi_pengiriman ?>" name='lokasi_pengiriman' required>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="form-input">
                            <label for="inputEmail3" class="col-sm-2 control-label">Nama Penerima *</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" value="<?= $row->nama_penerima ?>" name='nama_penerima' required>
                            </div>
                          </div>
                          <div class="form-input">
                            <label for="inputEmail3" class="col-sm-2 control-label">No. Kontak Penerima *</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" value="<?= $row->no_hp_penerima ?>" name='no_hp_penerima' onkeypress="return number_only(event)" required>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="form-input">
                            <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Pengiriman Unit *</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control datepicker" value="<?= $row->tgl_pengiriman === '0000-00-00' ? '' : $row->tgl_pengiriman ?>" name='tgl_pengiriman' required>
                            </div>
                          </div>
                          <div class="form-input">
                            <label for="inputEmail3" class="col-sm-2 control-label">Waktu Pengiriman Unit *</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" value="<?= $row->waktu_pengiriman ?>" name='waktu_pengiriman' placeholder="Contoh Pengisian : 12:50" required>
                            </div>
                          </div>
                        </div>
                        <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-block btn-danger btn-flat" onclick="tampil_nosin()" type="button"> Data Nomor Mesin </button> <br>
                        <table class="table table-bordered table-hover table-condensed table-stripped">
                          <thead>
                            <th>No. Mesin</th>
                            <th>No. Rangka</th>
                            <th>Tipe Kendaraan</th>
                            <th>Warna</th>
                            <th>Driver</th>
                            <th width='5%'>Aksi</th>
                          </thead>
                          <tbody>
                            <tr v-for="(dt, index) of detail_nosin">
                              <td>{{dt.no_mesin}}</td>
                              <td>{{dt.no_rangka}}</td>
                              <td>{{dt.tipe_kendaraan}}</td>
                              <td>{{dt.warna_kendaraan}}</td>
                              <td>{{dt.driver}}</td>
                              <td align='center'><button class='btn btn-primary btn-flat btn-xs' @click.prevent="pilihDriver(index)">Pilih Driver</button></td>
                            </tr>
                          </tbody>
                        </table>
                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-12" align='center'>
                          <button type="button" id="submitBtnSetBAST" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                        </div>
                      </div><!-- /.box-footer -->
                    </form>
                  </div>
                </div>
              </div>
            </div><!-- /.box -->
            <?php
            $data['data'] = ['modalDealerDeliveryUnit'];
            $this->load->view('dealer/h1_dealer_api', $data); ?>
            <script>
              var form_ = new Vue({
                el: '#form_',
                data: {
                  detail_nosin: <?= isset($row) ? json_encode($detail_nosin) : '[]' ?>,
                  ambil: '<?= $row->ambil ?>',
                  set_index: ''
                },
                methods: {
                  pilihDriver: function(params) {
                    this.set_index = params;
                    showModalDriverDeliveryUnit()
                  }
                }
              })
              $('#submitBtnSetBAST').click(function() {
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
                  details: form_.detail_nosin
                };
                var form = $('#form_').serializeArray();
                for (field of form) {
                  values[field.name] = field.value;
                }
                if ($('#form_').valid()) // check if form is valid
                {
                  for (drv of form_.detail_nosin) {
                    if (drv.id_master_plat === null) {
                      alert('Masih ada No. Mesin belum ditentukan drivernya');
                      return false;
                    }
                  }
                  if (confirm("Apakah anda yakin ?") == true) {
                    $.ajax({
                      beforeSend: function() {
                        $('#submitBtnSetBAST').html('<i class="fa fa-spinner fa-spin"></i> Process');
                        $('#submitBtnSetBAST').attr('disabled', true);
                      },
                      url: '<?= base_url('dealer/' . $isi . '/save_bastk_gc') ?>',
                      type: "POST",
                      data: values,
                      cache: false,
                      dataType: 'JSON',
                      success: function(response) {
                        if (response.status == 'sukses') {
                          window.location = response.link;
                        } else {
                          alert(response.pesan);
                          $('#submitBtnSetBAST').attr('disabled', false);
                        }
                        $('#submitBtnSetBAST').html('<i class="fa fa-save"></i> Save All');
                      },
                      error: function() {
                        alert("Something Went Wrong !");
                        $('#submitBtnSetBAST').html('<i class="fa fa-save"></i> Save All');
                        $('#submitBtnSetBAST').attr('disabled', false);

                      }
                    });
                  } else {
                    return false;
                  }
                } else {
                  alert('Silahkan isi field required !')
                }
              })

              function pilihDriver(drv) {
                form_.detail_nosin[form_.set_index].id_master_plat = drv.id_master_plat;
                form_.detail_nosin[form_.set_index].driver = drv.driver;
              }
            </script>
          <?php
          } elseif ($set == "view") {
          ?>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/sales_order/add">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
                  </a>
                  <a href="dealer/sales_order/history">
                    <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History</button>
                  </a>
                  <a href="dealer/sales_order/gc">
                    <button class="btn btn-warning btn-flat margin"><i class="fa fa-users"></i> Group Customer</button>
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
                <table id="example4" class="table table-bordered table-hover" width='100%'>
                  <thead>
                    <tr>
                      <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                      <th width="5%">No</th>
                      <th>No So</th>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>No Faktur</th>
                      <th>Tipe</th>
                      <th>Warna</th>
                      <th>Nama Konsumen</th>
                      <th>Alamat</th>
                      <th>Paid</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($dt_sales_order->result() as $row) {
                      $edit     = $this->m_admin->set_tombol($id_menu, $group, 'edit');
                      $delete   = $this->m_admin->set_tombol($id_menu, $group, 'delete');
                      $approval = $this->m_admin->set_tombol($id_menu, $group, 'approval');
                      $print    = $this->m_admin->set_tombol($id_menu, $group, 'print');
                      $no_faktur = $this->db->query("SELECT nomor_faktur from tr_fkb where no_mesin_spasi='$row->no_mesin'");
                      if ($row->status_close != 'closed') {
                        if ($no_faktur->num_rows() > 0) {
                          $no_faktur = $no_faktur->row()->nomor_faktur;
                        } else {
                          $no_faktur = '';
                        }
                        $tombol1 = "";
                        $tombol = "";

                        $cetak_so = "<a href='dealer/sales_order/cetak_so?id=$row->id_sales_order' target='_blank' >
                              <button $print class='btn btn-flat btn-xs bg-blue' ><i class='fa fa-print'></i> Cetak SO</button>
                            </a>
                            ";
                        $cetak_cover = "<a href='dealer/sales_order/cetak_cover?id=$row->id_sales_order' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-green'><i class='fa fa-print'></i> Cetak Cover</button> 
                          </a>";
                        $cetak_invoice = "
                          <a href='dealer/sales_order/cetak_invoice?id=$row->id_sales_order' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-blue'><i class='fa fa-print'></i> Cetak Invoice</button>
                          </a>";
                        $cetak_barcode = "<a href='dealer/sales_order/cetak_barcode?id=$row->id_sales_order' target='_blank' >
                          <button $print class='btn btn-flat btn-xs btn-danger'><i class='fa fa-print'></i> Cetak Barcode AHASS</button>
                        </a>";
                        $cetak_kwitansi = "<a href='dealer/sales_order/cetak_kwitansi?id=$row->id_sales_order' target='_blank' >
                        <button $print class='btn btn-flat btn-xs bg-maroon'><i class='fa fa-print'></i> Cetak Kwitansi</button>
                      </a>";
                        $btn_bastk = "
                            <button $print type=\"button\" class=\"btn btn-success btn-flat btn-xs\"  id_sales_order=\"$row->id_sales_order\" onclick=\"choosedriver('$row->id_sales_order')\">BASTK</button>";
                        $btn_close = "<a href='dealer/sales_order/close?id=$row->id_sales_order'>
                        <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-close'></i> Close</button>
                      </a>";
                        $paid = '';
                        if ($row->is_paid == 1) {
                          $paid = "<i class='fa fa-check'></i>";
                          if ($row->status_cetak == '') {
                            $tombol = $cetak_so;
                          } elseif ($row->status_cetak == 'approve') {
                            $tombol1 = $cetak_so;
                          } elseif ($row->status_cetak == 'cetak_so' and $row->tgl_cetak_so != null) {
                            $tombol1 = $cetak_so . ' ' . $cetak_cover . ' ' . $cetak_invoice;
                          } elseif (($row->status_cetak == 'cetak_invoice' or $row->status_cetak == 'cetak_so') and $row->status_cetak != 'cetak_bastk' and $row->status_so == 'so_invoice') {
                            $tombol1 = $cetak_so . ' ' . $cetak_cover . ' ' . $cetak_invoice . ' ' . $cetak_barcode;
                          } elseif (($row->status_cetak == 'cetak_invoice' or $row->status_cetak == 'cetak_barcode') and $row->tgl_bastk != null and $row->status_cetak != 'cetak_bastk' and $row->status_so == 'so_invoice') {
                            $tombol1 = $cetak_so . ' ' . $cetak_cover . ' ' . $cetak_invoice . ' ' . $cetak_barcode . ' ' . $btn_bastk;
                          } elseif (($row->status_cetak == 'cetak_bastk' or $row->status_cetak == 'cetak_invoice' or $row->status_cetak == 'cetak_barcode'  or $row->status_cetak == 'cetak_kwitansi') and $row->status_so == 'so_invoice') {
                            $tombol1 = $cetak_so . ' ' . $cetak_cover . ' ' . $cetak_invoice . ' ' . $cetak_barcode . ' ' . $btn_bastk . ' ' . $btn_close;
                          } elseif ($row->status_cetak == 'reject') {
                            // $tombol ="<a href='dealer/sales_order/edit?id=$row->id_sales_order' target='_blank' >
                            //             <button $edit class='btn btn-flat btn-xs btn-warning'><i class='fa fa-pencil'></i> Edit</button>
                            //           </a>";
                            $tombol = "";
                            $tombol1 = "";
                          }
                        }
                        echo "
              <tr>
                <td>$no</td>              
                <td><a href='dealer/sales_order/konsumen?id=$row->id_sales_order'>$row->id_sales_order</a></td>
                <td>$row->no_mesin</td>
                <td>$row->no_rangka</td>
                <td>$no_faktur</td>
                <td>$row->tipe_ahm</td>
                <td>$row->warna</td>
                <td>$row->nama_konsumen</td>
                <td>$row->alamat</td>
                <td>$paid</td>
                <td align='center'>$tombol $tombol1</td>
              </tr>
              ";
                        $no++;
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
            <div class="modal fade modal_bastk">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Detail</h4>
                  </div>
                  <div class="modal-body" id="show_detail">
                    <form class="form-horizontal" method="GET" action="dealer/sales_order/bastk" id="form_">
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label>ID Sales Order</label><br>
                          <a href="" class="a_href_modal" target="_blank"></a>
                          <input type="hidden" name="id_sales_order" class="form-control" id="id_sales_order" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label>Sales ID</label><br>
                          <input type="text" class="form-control" id="sales_id" readonly>
                        </div>
                        <div class="col-sm-6">
                          <label>Sales Name</label><br>
                          <input type="text" class="form-control" id="sales_name" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label>Pengiriman</label>
                          <select class="form-control" name="ambil" id="ambil" onchange="cek_ambil()">
                            <option value="Dikirim">Dikirim</option>
                            <option value="Ambil Sendiri">Ambil Sendiri</option>
                          </select>
                        </div>
                        <span id="dikirim">
                          <div class="col-sm-6">
                            <label>Pilih Pengemudi</label>
                            <select class="form-control" name="id_master_plat" id="id_master_plat" required>
                              <?php
                              $id_dealer = $this->m_admin->cari_dealer();
                              $driver = $this->db->query("SELECT * FROM ms_plat_dealer WHERE id_dealer ='$id_dealer' ");
                              if ($driver->num_rows() > 0) {
                                foreach ($driver->result() as $dr) {
                                  echo "<option value='$dr->id_master_plat'>$dr->no_plat | $dr->driver </option>";
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </span>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label>Tgl Pengiriman Unit</label>
                          <input type="text" name="tgl_pengiriman" class="form-control datepicker" id="tgl_pengiriman" autocomplete="off" required>
                        </div>
                        <div class="col-sm-6">
                          <label>Waktu Pengiriman Unit</label>
                          <input type="text" name="waktu_pengiriman" class="form-control" id="waktu_pengiriman" placeholder="Contoh Pengisian : 12:50" autocomplete="off" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label>Lokasi Pengiriman Unit</label>
                          <input type="text" name="lokasi_pengiriman" class="form-control" id="lokasi_pengiriman" autocomplete="off" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label>Nama Penerima</label>
                          <input type="text" name="nama_penerima" class="form-control" id="nama_penerima" autocomplete="off" required>
                        </div>
                        <div class="col-sm-6">
                          <label>No. Kontak Penerima</label>
                          <input type="text" name="no_hp_penerima" class="form-control" id="no_hp_penerima" autocomplete="off" required>
                        </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                    <p align="center">
                      <button type="button" id="submitBtnBASTK" class="btn btn-primary pull-right">Simpan</button>
                    </p>
                  </div>
                  </form>
                </div>
              </div>
            </div>
            <script type="text/javascript">
              $('#submitBtnBASTK').click(function() {
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
                if ($('#form_').valid()) // check if form is valid
                {
                  values = {
                    tgl_pengiriman: $('#tgl_pengiriman').val(),
                    waktu_pengiriman: $('#waktu_pengiriman').val(),
                    id_sales_order: $('#id_sales_order').val(),
                  }
                  $.ajax({
                    beforeSend: function() {
                      $('#submitBtnBASTK').attr('disabled', true);
                    },
                    url: '<?= base_url('dealer/sales_order/cekTglPengiriman') ?>',
                    type: "POST",
                    data: values,
                    cache: false,
                    dataType: 'JSON',
                    success: function(response) {
                      if (response.status == 'selisih') {
                        if (response.bastk == 1) {
                          alert('Tanggal pengiriman tidak boleh lebih kecil dari tanggal BASTK !')
                        } else {
                          alert('Tanggal pengiriman tidak boleh lebih kecil dari tanggal hari ini !')
                        }
                      } else {
                        $('#form_').submit();
                      }
                      $('#submitBtnBASTK').attr('disabled', false);
                    },
                    error: function() {
                      alert("failure");
                      $('#submitBtnBASTK').attr('disabled', false);

                    },
                    statusCode: {
                      500: function() {
                        alert('fail');
                        $('#submitBtnBASTK').attr('disabled', false);

                      }
                    }
                  });
                  // $('#form_').submit();
                } else {
                  alert('Silahkan isi field required !')
                }
              })

              function choosedriver(id_sales_order) {
                $('.modal_bastk .a_href_modal').attr('href', 'dealer/sales_order/konsumen?id=' + id_sales_order);
                $('.modal_bastk .a_href_modal').text(id_sales_order);
                $('.modal_bastk #id_sales_order').val(id_sales_order);
                //var id_gudang = $("#gudang option:selected").val();
                /* var id_rfs_pinjaman = $(".myTable1 .id_rfs_pinjaman").val();
                 var tgl_pinjaman = $("#tgl_pinjaman").val();
                 var keterangan = $("#keterangan").val();
                 var ksu = $("#ksu").val();
                 */
                $.ajax({
                  beforeSend: function() {},
                  url: "<?php echo site_url('dealer/sales_order/getSales'); ?>",
                  type: "POST",
                  data: "id=" + id_sales_order,
                  cache: false,
                  dataType: 'JSON',
                  success: function(resp) {
                    $('.modal_bastk #sales_id').val(resp.sales_id);
                    $('.modal_bastk #sales_name').val(resp.sales_name);
                    $('.modal_bastk').modal('show');
                  },
                  statusCode: {
                    500: function() {
                      alert('Something Went Wrong');
                    }
                  }
                });
              }
            </script>
          <?php
          } elseif ($set == 'view_gc') {
          ?>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/sales_order/add_gc">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
                  </a>
                  <a href="dealer/sales_order/history_gc">
                    <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History GC</button>
                  </a>
                  <a href="dealer/sales_order">
                    <button class="btn btn-warning btn-flat margin"><i class="fa fa-users"></i> Individu</button>
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
                <table id="example4" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                      <th width="5%">No</th>
                      <th>No SO</th>
                      <th>No SPK</th>
                      <th>Nama Konsumen</th>
                      <th>No NPWP</th>
                      <th>Alamat</th>
                      <th>Paid</th>
                      <th width='10%'>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    $id_dealer = $this->m_admin->cari_dealer();

                    foreach ($dt_sales_order->result() as $row) {
                      $edit     = $this->m_admin->set_tombol($id_menu, $group, 'edit');
                      $delete   = $this->m_admin->set_tombol($id_menu, $group, 'delete');
                      $approval = $this->m_admin->set_tombol($id_menu, $group, 'approval');
                      $print    = $this->m_admin->set_tombol($id_menu, $group, 'print');
                      $cetak_so = "<a href='dealer/sales_order/cetak_so_gc?id=$row->id_sales_order_gc' target='_blank' ><button $print class='btn btn-flat btn-xs bg-blue' ><i class='fa fa-print'></i> Cetak SO</button></a>";
                      $cetak_cover = "<a href='dealer/sales_order/cetak_cover_gc?id=$row->id_sales_order_gc' target='_blank' ><button $print class='btn btn-flat btn-xs bg-green'><i class='fa fa-print'></i> Cetak Cover</button> </a>";
                      $syarat_bbn = "<a href='dealer/sales_order/syarat_bbn?id_c=$row->id_sales_order_gc'>
                            <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-list'></i> Syarat BBN</button></a>";
                      $cetak_invoice = "<a href='dealer/sales_order/cetak_invoice_gc?id=$row->id_sales_order_gc' target='_blank' >
                      <button $print class='btn btn-flat btn-xs bg-blue'><i class='fa fa-print'></i> Cetak Invoice</button>
                      </a>";
                      $barcode_ahass = "<a href='dealer/sales_order/cetak_barcode_gc?id=$row->id_sales_order_gc' target='_blank' >
                        <button $print class='btn btn-flat btn-xs btn-danger'><i class='fa fa-print'></i> Cetak Barcode AHASS</button>
                      </a>";
                      // $bastk = "<button $print type=\"button\" class=\"btn btn-success btn-flat btn-xs\" data-toggle=\"modal\" data-target=\".modal_bastk_gc\" id_sales_order_gc=\"$row->id_sales_order_gc\" onclick=\"choosedriver_gc('$row->id_sales_order_gc')\">BASTK</button>";
                      $bastk = "<a $print class=\"btn btn-success btn-flat btn-xs\" href=\"dealer/sales_order/set_bastk_gc?id=$row->id_sales_order_gc\">BASTK</a>";
                      $cetak_kwitansi = "<a href='dealer/sales_order/cetak_kwitansi_gc?id=$row->id_sales_order_gc' target='_blank' >
                        <button $print class='btn btn-flat btn-xs bg-maroon'><i class='fa fa-print'></i> Cetak Kwitansi</button>
                      </a>";
                      $close = "<a href='dealer/sales_order/close_gc?id=$row->id_sales_order_gc'>
                        <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-close'></i> Close</button>
                      </a>";
                      $btn_approval = "<a $approval href='dealer/sales_order/approve_gc?id=$row->id_sales_order_gc' onclick=\"return confirm('Are you sure to Approve this data ?')\" class='btn btn-flat btn-xs bg-green'>Approve</a>";

                      $rejected = "<a $approval href='dealer/sales_order/reject_gc?id=$row->id_sales_order_gc' onclick=\"return confirm('Are you sure to reject this data ?')\" class='btn btn-flat btn-xs bg-red'>Reject</a>";
                      $edit = "<a href='dealer/sales_order/edit_gc?id=$row->id_sales_order_gc' target='_blank' >
                        <button $edit class='btn btn-flat btn-xs btn-warning'><i class='fa fa-pencil'></i> Edit</button>
                      </a>";
                      $tombol1 = "";
                      $tombol  = "";
                      $paid    = '';
                      if ($row->status_close != 'closed') {
                        if ($row->status_cetak == '') {
                          $tombol = $btn_approval . ' ' . $rejected;
                        } else {
                          if ($row->is_paid == 1) {
                            $paid = "<i class='fa fa-check'></i>";
                            if ($row->status_cetak == '') {
                              $tombol = $btn_approval . ' ' . $rejected;
                            } elseif ($row->status_cetak == 'approve') {
                              $tombol1 = $cetak_so . ' ' . $cetak_cover . ' ' . $syarat_bbn;
                            } elseif ($row->status_cetak == 'cetak_so' and $row->tgl_cetak_so != null) {
                              $tombol1 = $cetak_so . ' ' . $cetak_cover . ' ' . $syarat_bbn . ' ' . $cetak_invoice;
                            } elseif (($row->status_cetak == 'cetak_invoice' or $row->status_cetak == 'cetak_so') and $row->status_cetak != 'cetak_bastk' and $row->status_so == 'so_invoice') {
                              $tombol1 = $cetak_so . ' ' . $cetak_cover . ' ' . $syarat_bbn . ' ' . $cetak_invoice . ' ' . $barcode_ahass;
                            } elseif (($row->status_cetak == 'cetak_invoice' or $row->status_cetak == 'cetak_barcode') and $row->tgl_bastk != null and $row->status_cetak != 'cetak_bastk' and $row->status_so == 'so_invoice') {
                              $tombol1 = $cetak_so . ' ' . $cetak_cover . ' ' . $syarat_bbn . ' ' . $cetak_invoice . ' ' . $barcode_ahass . ' ' . $bastk;
                            } elseif (($row->status_cetak == 'cetak_bastk' or $row->status_cetak == 'cetak_invoice' or $row->status_cetak == 'cetak_barcode'  or $row->status_cetak == 'cetak_kwitansi') and $row->status_so == 'so_invoice') {
                              $tombol1 = $cetak_so . ' ' . $cetak_cover . ' ' . $syarat_bbn . ' ' . $cetak_invoice . ' ' . $barcode_ahass . '' . $bastk  . ' ' . $close;
                            } elseif ($row->status_cetak == 'reject') {
                              $tombol = $edit;
                              $tombol1 = "";
                            }
                          }
                        }
                        echo "
            <tr>
              <td>$no</td>              
              <td>$row->id_sales_order_gc</td>
              <td>$row->no_spk_gc</td>
              <td>$row->nama_npwp</td>
              <td>$row->no_npwp</td>              
              <td>$row->alamat</td>
              <td>$paid</td>
              <td align='center'>";
                        $id_group = $this->session->userdata("group");
                        $sql = $this->db->query("SELECT * FROM ms_user_group WHERE id_user_group = '$id_group'")->row();
                        if ($sql->user_group == 'Kasir Dealer') {
                          echo "<a href='dealer/sales_order/cetak_kwitansi_gc?id=$row->id_sales_order_gc' target='_blank' >
                    <button $print class='btn btn-flat btn-xs bg-maroon'><i class='fa fa-print'></i> Cetak Kwitansi</button>
                  </a>";
                        } else {
                          echo "$tombol $tombol1";
                        }
                        echo "
              </td>
            </tr>
            ";
                        $no++;
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->

            <script type="text/javascript">

            </script>
          <?php
          } elseif ($set == "history") {
          ?>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/sales_order">
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
                <table id="datatable" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                      <th width="5%">No</th>
                      <th>No So</th>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>No Faktur</th>
                      <th>Tipe</th>
                      <th>Warna</th>
                      <th>Nama Konsumen</th>
                      <th>Alamat</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->

            <script type="text/javascript">
              $(document).ready(function(e){
                $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
                {
                    return {
                        "iStart": oSettings._iDisplayStart,
                        "iEnd": oSettings.fnDisplayEnd(),
                        "iLength": oSettings._iDisplayLength,
                        "iTotal": oSettings.fnRecordsTotal(),
                        "iFilteredTotal": oSettings.fnRecordsDisplay(),
                        "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };

                var base_url = "<?php echo base_url();?>/"; // You can use full url here but I prefer like this
                $('#datatable').DataTable({
                   "pageLength" : 10,
                   "serverSide": true,
                   "ordering": true, // Set true agar bisa di sorting
                    "processing": true,
                    "language": {
                      processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                      searchPlaceholder: "Pencarian..."
                    },

                   "order": [[1, "desc" ]],
                   "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    },
                   "ajax":{
                            url :  base_url+'dealer/sales_order/getDataHistory',
                            type : 'POST'
                          },
                }); // End of DataTable


              }); 

            </script>
            
            <div class="modal fade modal_bastk">
              <div class="modal-dialog modal-sm">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Detail</h4>
                  </div>
                  <div class="modal-body" id="show_detail">
                    <form class="form-horizontal" method="GET" action="dealer/sales_order/bastk">
                      <div class="form-group">
                        <div class="col-sm-12">
                          <label>ID Sales Order</label>
                          <input type="text" name="id_sales_order" class="form-control" id="id_sales_order" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <select class="form-control" name="ambil" id="ambil" onchange="cek_ambil()">
                          <option value="Dikirim">Dikirim</option>
                          <option value="Ambil Sendiri">Ambil Sendiri</option>
                        </select>
                      </div>
                      <span id="dikirim">
                        <div class="form-group">
                          <div class="col-sm-12">
                            <label>Pilih Pengemudi</label>
                            <select class="form-control" name="id_master_plat" id="id_master_plat" required>
                              <?php
                              $id_dealer = $this->m_admin->cari_dealer();
                              $driver = $this->db->query("SELECT * FROM ms_plat_dealer WHERE id_dealer ='$id_dealer' ");
                              if ($driver->num_rows() > 0) {
                                foreach ($driver->result() as $dr) {
                                  echo "<option value='$dr->id_master_plat'>$dr->no_plat | $dr->driver </option>";
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </span>
                  </div>
                  <div class="modal-footer">
                    <p align="center">
                      <button type="submit" class="btn btn-primary pull-right">Simpan</button>
                    </p>
                  </div>
                  </form>
                </div>
              </div>
            </div>

		<?php
          } elseif ($set == "view_new") {
          ?>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/sales_order/add">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
                  </a>
                  <a href="dealer/sales_order/history">
                    <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History</button>
                  </a>
                  <a href="dealer/sales_order/gc">
                    <button class="btn btn-warning btn-flat margin"><i class="fa fa-users"></i> Group Customer</button>
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
                <table id="datatable" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                      <th width="5%">No</th>
                      <th>No So</th>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>No Faktur</th>
                      <th>Tipe</th>
                      <th>Warna</th>
                      <th>Nama Konsumen</th>
                      <th>Alamat</th>
                      <th>Paid</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->

            <script type="text/javascript">
              $(document).ready(function(e){
                $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
                {
                    return {
                        "iStart": oSettings._iDisplayStart,
                        "iEnd": oSettings.fnDisplayEnd(),
                        "iLength": oSettings._iDisplayLength,
                        "iTotal": oSettings.fnRecordsTotal(),
                        "iFilteredTotal": oSettings.fnRecordsDisplay(),
                        "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };

                var base_url = "<?php echo base_url();?>/"; // You can use full url here but I prefer like this
                $('#datatable').DataTable({
                   "pageLength" : 10,
                   "serverSide": true,
                   "ordering": true, // Set true agar bisa di sorting
                    "processing": true,
                    "language": {
                      processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                      searchPlaceholder: "Pencarian..."
                    },

                   "order": [[1, "desc" ]],
                   "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    },
                   "ajax":{
                            url :  base_url+'dealer/sales_order/getDataAllSO',
                            type : 'POST'
                          },
                }); // End of DataTable


              }); 

            </script>
            
            <div class="modal fade modal_bastk">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Detail</h4>
                  </div>
                  <div class="modal-body" id="show_detail">
                    <form class="form-horizontal" method="GET" action="dealer/sales_order/bastk" id="form_">
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label>ID Sales Order</label><br>
                          <a href="" class="a_href_modal" target="_blank"></a>
                          <input type="hidden" name="id_sales_order" class="form-control" id="id_sales_order" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label>Sales ID</label><br>
                          <input type="text" class="form-control" id="sales_id" readonly>
                        </div>
                        <div class="col-sm-6">
                          <label>Sales Name</label><br>
                          <input type="text" class="form-control" id="sales_name" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label>Pengiriman</label>
                          <select class="form-control" name="ambil" id="ambil" onchange="cek_ambil()">
                            <option value="Dikirim">Dikirim</option>
                            <option value="Ambil Sendiri">Ambil Sendiri</option>
                          </select>
                        </div>
                        <span id="dikirim">
                          <div class="col-sm-6">
                            <label>Pilih Pengemudi</label>
                            <select class="form-control" name="id_master_plat" id="id_master_plat" required>
                              <?php
                              $id_dealer = $this->m_admin->cari_dealer();
                              $driver = $this->db->query("SELECT * FROM ms_plat_dealer WHERE id_dealer ='$id_dealer' ");
                              if ($driver->num_rows() > 0) {
                                foreach ($driver->result() as $dr) {
                                  echo "<option value='$dr->id_master_plat'>$dr->no_plat | $dr->driver </option>";
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </span>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label>Tgl Pengiriman Unit</label>
                          <input type="text" name="tgl_pengiriman" class="form-control datepicker" id="tgl_pengiriman" autocomplete="off" required>
                        </div>
                        <div class="col-sm-6">
                          <label>Waktu Pengiriman Unit</label>
                          <input type="text" name="waktu_pengiriman" class="form-control" id="waktu_pengiriman" placeholder="Contoh Pengisian : 12:50" autocomplete="off" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label>Lokasi Pengiriman Unit</label>
                          <input type="text" name="lokasi_pengiriman" class="form-control" id="lokasi_pengiriman" autocomplete="off" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label>Nama Penerima</label>
                          <input type="text" name="nama_penerima" class="form-control" id="nama_penerima" autocomplete="off" required>
                        </div>
                        <div class="col-sm-6">
                          <label>No. Kontak Penerima</label>
                          <input type="text" name="no_hp_penerima" class="form-control" id="no_hp_penerima" autocomplete="off" required>
                        </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                    <p align="center">
                      <button type="button" id="submitBtnBASTK" class="btn btn-primary pull-right">Simpan</button>
                    </p>
                  </div>
                  </form>
                </div>
              </div>
            </div>
		 <script type="text/javascript">
              $('#submitBtnBASTK').click(function() {
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
                if ($('#form_').valid()) // check if form is valid
                {
                  values = {
                    tgl_pengiriman: $('#tgl_pengiriman').val(),
                    waktu_pengiriman: $('#waktu_pengiriman').val(),
                    id_sales_order: $('#id_sales_order').val(),
                  }
                  $.ajax({
                    beforeSend: function() {
                      $('#submitBtnBASTK').attr('disabled', true);
                    },
                    url: '<?= base_url('dealer/sales_order/cekTglPengiriman') ?>',
                    type: "POST",
                    data: values,
                    cache: false,
                    dataType: 'JSON',
                    success: function(response) {
                      if (response.status == 'selisih') {
                        if (response.bastk == 1) {
                          alert('Tanggal pengiriman tidak boleh lebih kecil dari tanggal BASTK !')
                        } else {
                          alert('Tanggal pengiriman tidak boleh lebih kecil dari tanggal hari ini !')
                        }
                      } else {
                        $('#form_').submit();
                      }
                      $('#submitBtnBASTK').attr('disabled', false);
                    },
                    error: function() {
                      alert("failure");
                      $('#submitBtnBASTK').attr('disabled', false);

                    },
                    statusCode: {
                      500: function() {
                        alert('fail');
                        $('#submitBtnBASTK').attr('disabled', false);

                      }
                    }
                  });
                  // $('#form_').submit();
                } else {
                  alert('Silahkan isi field required !')
                }
              })

              function choosedriver(id_sales_order) {
                $('.modal_bastk .a_href_modal').attr('href', 'dealer/sales_order/konsumen?id=' + id_sales_order);
                $('.modal_bastk .a_href_modal').text(id_sales_order);
                $('.modal_bastk #id_sales_order').val(id_sales_order);
                //var id_gudang = $("#gudang option:selected").val();
                /* var id_rfs_pinjaman = $(".myTable1 .id_rfs_pinjaman").val();
                 var tgl_pinjaman = $("#tgl_pinjaman").val();
                 var keterangan = $("#keterangan").val();
                 var ksu = $("#ksu").val();
                 */
                $.ajax({
                  beforeSend: function() {},
                  url: "<?php echo site_url('dealer/sales_order/getSales'); ?>",
                  type: "POST",
                  data: "id=" + id_sales_order,
                  cache: false,
                  dataType: 'JSON',
                  success: function(resp) {
                    $('.modal_bastk #sales_id').val(resp.sales_id);
                    $('.modal_bastk #sales_name').val(resp.sales_name);
                    $('.modal_bastk').modal('show');
                  },
                  statusCode: {
                    500: function() {
                      alert('Something Went Wrong');
                    }
                  }
                });
              }
            </script>

          <?php
          } elseif ($set == "history_gc") {
          ?>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/sales_order/gc">
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
                <table id="example4" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                      <th width="5%">No</th>
                      <th>No SO</th>
                      <th>No SPK</th>
                      <th>Nama Konsumen</th>
                      <th>No NPWP</th>
                      <th>Alamat</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($dt_sales_order->result() as $row) {
                      $edit = $this->m_admin->set_tombol($id_menu, $group, 'edit');
                      $delete = $this->m_admin->set_tombol($id_menu, $group, 'delete');
                      $approval = $this->m_admin->set_tombol($id_menu, $group, 'approval');
                      $print = $this->m_admin->set_tombol($id_menu, $group, 'print');
                      $tombol1 = "";
                      $tombol = "";
                      if ($row->status_cetak == '') {
                        $tombol = "
                <a $approval href='dealer/sales_order/approve_gc?id=$row->id_sales_order_gc' onclick=\"return confirm('Are you sure to Approve this data ?')\" class='btn btn-flat btn-xs bg-green'>Approve</a>
                <a $approval href='dealer/sales_order/reject_gc?id=$row->id_sales_order_gc' onclick=\"return confirm('Are you sure to reject this data ?')\" class='btn btn-flat btn-xs bg-red'>Reject</a>
                ";
                      } elseif ($row->status_cetak == 'approve') {
                        $tombol1 = "<a href='dealer/sales_order/cetak_so_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-blue' ><i class='fa fa-print'></i> Cetak SO</button>
                          </a>
                          <a href='dealer/sales_order/cetak_cover_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-green'><i class='fa fa-print'></i> Cetak Cover</button> 
                          </a>";
                      } elseif ($row->status_cetak == 'cetak_so' and $row->tgl_cetak_so != null) {
                        $tombol1 = "<a href='dealer/sales_order/cetak_so_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-blue' ><i class='fa fa-print'></i> Cetak SO</button>
                          </a>
                          <a href='dealer/sales_order/cetak_cover_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-green'><i class='fa fa-print'></i> Cetak Cover</button> 
                          </a>
                          <a href='dealer/sales_order/cetak_invoice_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-blue'><i class='fa fa-print'></i> Cetak Invoice</button>
                          </a>";
                      } elseif (($row->status_cetak == 'cetak_invoice' or $row->status_cetak == 'cetak_so') and $row->status_cetak != 'cetak_bastk' and $row->status_so == 'so_invoice') {
                        $tombol1 = "<a href='dealer/sales_order/cetak_so_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-blue' ><i class='fa fa-print'></i> Cetak SO</button>
                          </a>
                          <a href='dealer/sales_order/cetak_cover_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-green'><i class='fa fa-print'></i> Cetak Cover</button> 
                          </a>
                          <a href='dealer/sales_order/cetak_invoice_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-blue'><i class='fa fa-print'></i> Cetak Invoice</button>
                          </a>
                          <a href='dealer/sales_order/cetak_barcode_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs btn-danger'><i class='fa fa-print'></i> Cetak Barcode AHASS</button>
                          </a>";
                      } elseif (($row->status_cetak == 'cetak_invoice' or $row->status_cetak == 'cetak_barcode') and $row->tgl_bastk != null and $row->status_cetak != 'cetak_bastk' and $row->status_so == 'so_invoice') {
                        $tombol1 = "<a href='dealer/sales_order/cetak_so_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-blue' ><i class='fa fa-print'></i> Cetak SO</button>
                          </a>
                          <a href='dealer/sales_order/cetak_cover_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-green'><i class='fa fa-print'></i> Cetak Cover</button> 
                          </a>
                          <a href='dealer/sales_order/cetak_invoice_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-blue'><i class='fa fa-print'></i> Cetak Invoice</button>
                          </a>
                          <a href='dealer/sales_order/cetak_barcode_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs btn-danger'><i class='fa fa-print'></i> Cetak Barcode AHASS</button>
                          </a>
                          <button $print type=\"button\" class=\"btn btn-success btn-flat btn-xs\"  id_sales_order_gc=\"$row->id_sales_order_gc\" onclick=\"choosedriver_gc('$row->id_sales_order_gc')\">BASTK</button>";
                      } elseif (($row->status_cetak == 'cetak_bastk' or $row->status_cetak == 'cetak_invoice' or $row->status_cetak == 'cetak_barcode'  or $row->status_cetak == 'cetak_kwitansi') and $row->status_so == 'so_invoice') {
                        $tombol1 = "<a href='dealer/sales_order/cetak_so_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-blue' ><i class='fa fa-print'></i> Cetak SO</button>
                          </a>
                          <a href='dealer/sales_order/cetak_cover_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-green'><i class='fa fa-print'></i> Cetak Cover</button> 
                          </a>
                          <a href='dealer/sales_order/cetak_invoice_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-blue'><i class='fa fa-print'></i> Cetak Invoice</button>
                          </a>
                          <a href='dealer/sales_order/cetak_barcode_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $print class='btn btn-flat btn-xs btn-danger'><i class='fa fa-print'></i> Cetak Barcode AHASS</button>
                          </a>
                          <button $print type=\"button\" class=\"btn btn-success btn-flat btn-xs\"  id_sales_order_gc=\"$row->id_sales_order_gc\" onclick=\"choosedriver_gc('$row->id_sales_order_gc')\">BASTK</button>
                          
                          <a href='dealer/sales_order/close_gc?id=$row->id_sales_order_gc'>
                            <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-close'></i> Close</button>
                          </a>";
                      } elseif ($row->status_cetak == 'reject') {
                        $tombol = "<a href='dealer/sales_order/edit_gc?id=$row->id_sales_order_gc' target='_blank' >
                            <button $edit class='btn btn-flat btn-xs btn-warning'><i class='fa fa-pencil'></i> Edit</button>
                          </a>";
                        $tombol1 = "";
                      }
                      // <td><a href='dealer/sales_order/konsumen?id=$row->id_sales_order_gc'>$row->id_sales_order_gc</a></td>
                      echo "
            <tr>
              <td>$no</td>              
              <td>$row->id_sales_order_gc</td>
              <td>$row->no_spk_gc</td>
              <td>$row->nama_npwp</td>
              <td>$row->no_npwp</td>              
              <td>$row->alamat</td>
              <td align='center'>$tombol $tombol1</td>
            </tr>
            ";
                      $no++;
                    }
                    ?>
                  </tbody>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
            <div class="modal fade modal_bastk">
              <div class="modal-dialog modal-sm">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Detail</h4>
                  </div>
                  <div class="modal-body" id="show_detail">
                    <form class="form-horizontal" method="GET" action="dealer/sales_order/bastk">
                      <div class="form-group">
                        <div class="col-sm-12">
                          <label>ID Sales Order</label>
                          <input type="text" name="id_sales_order" class="form-control" id="id_sales_order" readonly>
                          <input type="hidden" name="tipe">
                        </div>
                      </div>
                      <div class="form-group">
                        <select class="form-control" name="ambil" id="ambil" onchange="cek_ambil()">
                          <option value="Dikirim">Dikirim</option>
                          <option value="Ambil Sendiri">Ambil Sendiri</option>
                        </select>
                      </div>
                      <span id="dikirim">
                        <div class="form-group">
                          <div class="col-sm-12">
                            <label>Pilih Pengemudi</label>
                            <select class="form-control" name="id_master_plat" id="id_master_plat" required>
                              <?php
                              $id_dealer = $this->m_admin->cari_dealer();
                              $driver = $this->db->query("SELECT * FROM ms_plat_dealer WHERE id_dealer ='$id_dealer' ");
                              if ($driver->num_rows() > 0) {
                                foreach ($driver->result() as $dr) {
                                  echo "<option value='$dr->id_master_plat'>$dr->no_plat | $dr->driver </option>";
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </span>
                  </div>
                  <div class="modal-footer">
                    <p align="center">
                      <button type="submit" class="btn btn-primary pull-right">Simpan</button>
                    </p>
                  </div>
                  </form>
                </div>
              </div>
            </div>
          <?php
          } elseif ($set == "konsumen") {
            $row = $dt_konsumen->row();
          ?>
            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/sales_order">
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
                    <form class="form-horizontal" action="dealer/sales_order/save" method="post" enctype="multipart/form-data">
                      <div class="box-body">
                        <input type="hidden" name="mode" id="mode" value="<?= $mode ?>">
                        <button class="btn btn-block btn-primary btn-flat" disabled> SALES ORDER </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No SPK *</label>
                          <div class="col-sm-4">
                            <input class="form-control" readonly type="text" id="no_spk" value="<?php echo $row->no_spk ?>" name="no_spk">
                          </div>
                        </div>
                        <button class="btn btn-block btn-success btn-flat" disabled> DATA KONSUMEN </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Sesuai Identitas</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Nama Sesuai Identitas" readonly id="nama_konsumen" name="nama_konsumen">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No KTP/KITAS</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No KTP/KITAS" name="no_ktp" id="no_ktp" readonly>
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No NPWP" readonly id="no_npwp" name="no_npwp">
                          </div>
                          <!-- <label for="inputEmail3" class="col-sm-2 control-label">No KK</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="No KK" name="no_kk" id="no_kk">                    
                  </div> -->
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kelurahan" id="id_kelurahan">
                            <input type="text" class="form-control" readonly id="kelurahan" placeholder="Kelurahan Domisili" name="kelurahan">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                            <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan Domisili" name="kecamatan">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                            <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten Domisili" id="kabupaten" name="kabupaten">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Domisili</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_provinsi" id="id_provinsi">
                            <input type="text" class="form-control" readonly placeholder="Provinsi Domisili" id="provinsi" name="provinsi">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Alamat Domisili" readonly id="alamat" name="alamat">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kodepos</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="kodepos" readonly name="kodepos" placeholder="Kodepos">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Apakah alamat domisili sama dengan alamat di KTP?</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="tanya" readonly name="tanya">
                          </div>
                        </div>
                        <span id="tampil_alamat">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Sesuai KTP</label>
                            <div class="col-sm-4">
                              <select class="form-control select2" id="id_kelurahan2" name="id_kelurahan2" onchange="take_kec2()">
                                <option value="">- choose -</option>
                              </select>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Sesuai KTP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_kecamatan" id="id_kecamatan2">
                              <input type="text" class="form-control" readonly id="kecamatan2" placeholder="Kecamatan Sesuai KTP" name="kecamatan2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Sesuai KTP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_kabupaten" id="id_kabupaten2">
                              <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten Sesuai KTP" id="kabupaten2" name="kabupaten2">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Sesuai KTP</label>
                            <div class="col-sm-4">
                              <input type="hidden" name="id_provinsi" id="id_provinsi2">
                              <input type="text" class="form-control" readonly placeholder="Provinsi Sesuai KTP" id="provinsi2" name="provinsi2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Alamat Sesuai KTP</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" placeholder="Alamat Sesuai KTP" name="alamat2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Kodepos Sesuai KTP</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" id="kodepos2" readonly name="kodepos2" placeholder="Kodepos Sesuai KTP">
                            </div>
                          </div>
                        </span>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No HP - Pertama</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No HP - Pertama" readonly id="no_hp" name="no_hp">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No HP - Kedua</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No HP - Kedua" readonly id="no_hp_2" name="no_hp_2">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No Telp" readonly id="no_telp" name="no_telp">
                          </div>
                        </div>
                        <button class="btn btn-block btn-danger btn-flat" disabled> DATA KENDARAAN </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Type *</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="tipe_ahm" placeholder="Type" name="tipe_ahm">
                            <input type="hidden" class="form-control" readonly id="id_tipe_kendaraan" placeholder="Type" name="tipe_ahm">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Harga</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="harga" placeholder="Harga" name="harga">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="warna" placeholder="Warna" name="warna">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">PPN</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="ppn" placeholder="PPN" name="ppn">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                          <div class="col-sm-4">
                            <input value="<?php echo $row->no_mesin ?>" type="text" class="form-control" readonly required placeholder="No Mesin" name="no_mesin">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Harga Off The Road</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="harga_off" placeholder="Harga Off The Road" name="harga_off">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" id="no_rangka" value="<?php echo $row->no_rangka ?>" placeholder="No Rangka" name="no_rangka">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Biaya BBN</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="biaya_bbn" placeholder="Biaya BBN" name="biaya_bbn">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tahun Produksi</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="tahun_produksi" value="<?php echo $row->tahun_produksi ?>" placeholder="Tahun Produksi" name="tahun_produksi">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Harga On The Road</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly id="harga_on" placeholder="Harga On The Road" name="harga_on">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama STNK/BPKB</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="nama_bpkb" readonly placeholder="Nama STNK/BPKB" name="nama_bpkb">
                          </div>
                        </div>

                        <button class="btn btn-block btn-warning btn-flat" disabled> SISTEM PEMBELIAN TUNAI </button> <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tipe Pembelian</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Tipe Pembelian" readonly id="tipe_pembelian" name="tipe_pembelian">
                          </div>
                        </div>
                        <span id="isi_cash">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">On/Off The Road</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="On/Off The Road" readonly id="the_road" name="the_road">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Harga Tunai</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Harga Tunai" readonly id="harga_tunai2" name="harga_tunai2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Program</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Program" readonly id="program_umum" name="program">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Nilai Voucher</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Nilai Voucher" name="nilai_voucher" readonly id="nilai_voucher">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Program Gabungan</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Program Gabungan" readonly id="program_gabungan" name="program_gabungan">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Voucher Tambahan</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Voucher Tambahan" name="voucher_tambahan" id="voucher_tambahan" readonly>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-sm-6"></div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Total Bayar</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Total Bayar" name="total_bayar" id="total_bayar" readonly>
                            </div>
                          </div>
                        </span>
                        <span id="isi_kredit">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Finance Company</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Finance Company" name="finance_company" readonly id="finance_company">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Down Payment Gross</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" readonly placeholder="Down Payment Gross" name="dp" id="dp">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Diskon</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" readonly id="diskon2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Program</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Program" name="program2" readonly id="program2">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Nilai Voucher</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Nilai Voucher" name="nilai_voucher2" id="nilai_voucher2" readonly>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Program Gabungan</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Program Gabungan" name="program_gabungan2" readonly id="program_gabungan2">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Voucher Tambahan</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Voucher Tambahan" name="voucher_tambahan2" readonly id="voucher_tambahan2">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">No PO Leasing</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="No PO Leasing" name="no_po_leasing" id="no_po_leasing" readonly>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Down Payment Setor</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Down Payment Setor" name="dp_setor" readonly id="dp_setor">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Tgl PO Leasing</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Tgl PO Leasing" name="tgl_po_leasing" id="tgl_po_leasing" disabled>
                            </div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Tenor</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Tenor" name="tenor" id="tenor" readonly>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-sm-6"></div>
                            <label for="inputEmail3" class="col-sm-2 control-label">Angsuran</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="Angsuran" name="angsuran" id="angsuran" readonly>
                            </div>
                          </div>
                        </span>
                        <button class="btn btn-block btn-info btn-flat" disabled> KSU </button> <br>
                        <div class="form-group">
                          <div id="dt_ksu" style="padding-left: 20px"></div>
                        </div>
                        <hr>
                        <div class="form-group">
                          <div class="col-sm-10" style="padding-left: 7%">
                            <table style="width: 39%; font-weight: bold; min-height: 40px" class="table table-condensed">
                              <?php $estimasi = $this->db->query("SELECT * FROM ms_estimasi_stnk_bpkb_cash")->row();
                              $tgl = date('Y-m-d');
                              $stnk = date("Y-m-d", strtotime("+" . $estimasi->estimasi_stnk . " days", strtotime($tgl)));
                              $bpkb = date("Y-m-d", strtotime("+" . $estimasi->estimasi_bpkb_cash . " days", strtotime($tgl)));
                              $day = 1;
                              $kirim = date("Y-m-d", strtotime("+" . $day . " days", strtotime($tgl)));
                              ?>
                              <tr>
                                <td>Tanggal Estimasi STNK</td>
                                <td>:</td>
                                <td><?= $stnk ?></td>
                              </tr>
                              <?php
                              $cek = $this->m_admin->getByID("tr_spk", "no_spk", $row->no_spk)->row();
                              if ($cek->jenis_beli == 'Cash') { ?>
                                <tr>
                                  <td>Tanggal BPKB</td>
                                  <td>:</td>
                                  <td><?php echo $bpkb ?></td>
                                </tr>
                              <?php } ?>
                              <tr>
                                <td>Tanggal Pengiriman Unit</td>
                                <td>:</td>
                                <td><?php echo $kirim ?></td>
                              </tr>
                            </table>
                          </div>
                        </div>
                      </div><!-- /.box-body -->
                    </form>
                  </div>
                </div>
              </div>
            </div><!-- /.box -->
            <?php
          } elseif ($set == "kwitansi") {
            $cek_tot = 0;
            $row = $konsumen->row();
            $cek_tot = $cek_jenis_bayar->num_rows();
            if ($cek_jenis_bayar->num_rows() > 0) {
              $cek = $cek_jenis_bayar->row();
              $uang_dibayar = $cek->uang_dibayar; ?>
              <script>
                $(document).ready(function() {
                  $('#jenis_bayar').select2().val('<?= $cek->jenis_bayar ?>').trigger('change');
                  //  getJenisBayar();
                })
              </script>
            <?php }
            $cek_spk = $this->db->query("SELECT * FROM tr_spk WHERE no_spk = '$row->no_spk'")->row();
            if ($cek_spk->jenis_beli == 'Cash') {
              $uang_dibayar = $cek_spk->total_bayar;
            } else {
              $uang_dibayar = $cek_spk->dp_stor;
            }
            ?>
            <!-- <body onload="getDetail()"> -->
            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/sales_order">
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
                    <form class="form-horizontal" action="dealer/sales_order/cetak_kwitansi" method="post" enctype="multipart/form-data">
                      <div class="box-body">
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Telah Terima Dari</label>
                          <div class="col-sm-4">
                            <input type="hidden" id="id_sales_order" name="id_sales_order" value="<?= $row->id_sales_order ?>">
                            <input type="hidden" id="isi_jenis" value="<?php echo $cek_tot ?>">
                            <input type="text" class="form-control" placeholder="Nama Konsumen" name="no_po" autocomplete="off" value="<?= $row->nama_konsumen ?>" disabled><br>
                            <input type="text" readonly class="form-control" placeholder="Alamat" name="no_po" autocomplete="off" value="<?= $row->alamat ?>" <?= $cek_tot > 0 ? 'disabled' : '' ?>>
                          </div>

                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Uang Dibayar Sejumlah</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" placeholder="Uang Dibayar Sejumlah" name="uang_dibayar" autocomplete="off" required value="<?= isset($uang_dibayar) ? $uang_dibayar : '' ?>" <?= $cek_tot > 0 ? 'disabled' : '' ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembayaran</label>
                          <div class="col-sm-4">
                            <select class="form-control select2" onchange="getJenisBayar()" id="jenis_bayar" name="jenis_bayar" <?= $cek_tot > 0 ? 'disabled' : '' ?>>
                              <option>- Choose -</option>
                              <option value="Cash On Hand Collection">Cash On Hand Collection</option>
                              <option value="Transfer">Transfer</option>
                              <option value="Cek/Giro">Cek/Giro</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <?php if ($cek_tot == 0) { ?>
                              <div id="showJenisBayar"></div>
                            <?php } else { ?>
                              <div id="showJenisBayar_detail"></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-10">
                          <?php if ($cek_tot == 0) { ?>
                            <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="submit" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                            <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>
                          <?php } else { ?>
                            <a href="dealer/sales_order/cetak_kwitansi_act?id=<?= $cek->id_sales_order ?>" class="btn btn-primary btn-flat" target="_blank">Cetak</a>
                          <?php } ?>
                        </div>
                      </div><!-- /.box-footer -->
                    </form>
                  </div>
                </div>
              </div>
            </div><!-- /.box -->
            <?php
          } elseif ($set == "kwitansi_gc") {
            $cek_tot = 0;
            $row = $konsumen->row();
            $cek_tot = $cek_jenis_bayar->num_rows();
            if ($cek_jenis_bayar->num_rows() > 0) {
              $cek = $cek_jenis_bayar->row();
              $uang_dibayar = $cek->uang_dibayar; ?>
              <script>
                $(document).ready(function() {
                  $('#jenis_bayar').select2().val('<?= $cek->jenis_bayar ?>').trigger('change');
                  //  getJenisBayar();
                })
              </script>
            <?php }
            $cek_spk = $this->db->query("SELECT SUM(total) as total_bayar,SUM(dp_stor) AS dp_stor FROM tr_spk_gc_detail WHERE no_spk_gc = '$row->no_spk_gc'")->row();
            $cek_s = $this->m_admin->getByID("tr_spk_gc", "no_spk_gc", $row->no_spk_gc)->row();
            if ($cek_s->jenis_beli == 'Cash') {
              $uang_dibayar = $cek_spk->total_bayar;
            } else {
              $uang_dibayar = $cek_spk->dp_stor;
            }
            ?>
            <!-- <body onload="getDetail()"> -->
            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/sales_order/gc">
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
                    <form class="form-horizontal" action="dealer/sales_order/cetak_kwitansi_gc" method="post" enctype="multipart/form-data">
                      <div class="box-body">
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Telah Terima Dari</label>
                          <div class="col-sm-4">
                            <input type="hidden" id="id_sales_order" name="id_sales_order_gc" value="<?= $row->id_sales_order_gc ?>">
                            <input type="hidden" id="isi_jenis" value="<?php echo $cek_tot ?>">
                            <input type="text" class="form-control" placeholder="Nama Konsumen" name="no_po" autocomplete="off" value="<?= $row->nama_npwp ?>" disabled><br>
                            <input type="text" readonly class="form-control" placeholder="Alamat" name="no_po" autocomplete="off" value="<?= $row->alamat ?>" <?= $cek_tot > 0 ? 'disabled' : '' ?>>
                          </div>

                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Uang Dibayar Sejumlah</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" placeholder="Uang Dibayar Sejumlah" name="uang_dibayar" autocomplete="off" required value="<?= isset($uang_dibayar) ? $uang_dibayar : '' ?>" <?= $cek_tot > 0 ? 'disabled' : '' ?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembayaran</label>
                          <div class="col-sm-4">
                            <select class="form-control select2" onchange="getJenisBayar()" id="jenis_bayar" name="jenis_bayar" <?= $cek_tot > 0 ? 'disabled' : '' ?>>
                              <option>- Choose -</option>
                              <option value="Cash On Hand Collection">Cash On Hand Collection</option>
                              <option value="Transfer">Transfer</option>
                              <option value="Cek/Giro">Cek/Giro</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <?php if ($cek_tot == 0) { ?>
                              <div id="showJenisBayar"></div>
                            <?php } else { ?>
                              <div id="showJenisBayar_detail"></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-10">
                          <?php if ($cek_tot == 0) { ?>
                            <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="submit" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                            <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>
                          <?php } else { ?>
                            <a href="dealer/sales_order/cetak_kwitansi_act_gc?id=<?= $cek->id_sales_order_gc ?>" class="btn btn-primary btn-flat" target="_blank">Cetak</a>
                          <?php } ?>
                        </div>
                      </div><!-- /.box-footer -->
                    </form>
                  </div>
                </div>
              </div>
            </div><!-- /.box -->
          <?php
          } elseif ($set == "cetak_invoice") { ?>
            <!-- <body onload="getDetail()"> -->
            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/sales_order">
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
                    <form class="form-horizontal" method="post" action="dealer/sales_order/cetak_invoice" enctype="multipart/form-data">
                      <input type="hidden" name="print" value="ya">
                      <div class="box-body">
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No. Invoice</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" autocomplete="off" value="<?= $no_invoice ?>">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Invoice</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" autocomplete="off" value="<?= $row->tgl_so ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No. Sales Order</label>
                          <div class="col-sm-4">
                            <input type="text" readonly name="id_sales_order" class="form-control" autocomplete="off" value="<?= $row->id_sales_order ?>">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Sales People</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" autocomplete="off" value="<?= $row->sales ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Customer</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" autocomplete="off" value="<?= $row->customer ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <table class="table table-bordered table-condensed table-hover table-stripped">
                              <thead>
                                <th>Tipe</th>
                                <th>Warna</th>
                                <th>No Mesin</th>
                                <th>No Rangka</th>
                                <th>Tahun Rakitan</th>
                              </thead>
                              <tbody>
                                <tr>
                                  <td><?= $row->tipe_motor ?></td>
                                  <td><?= $row->warna ?></td>
                                  <td><?= $row->no_mesin ?></td>
                                  <td><?= $row->no_rangka ?></td>
                                  <td><?= $row->tahun_produksi ?></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <button class="btn btn-block btn-primary btn-flat" disabled> Pembayaran </button><br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Jumlah DP</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" autocomplete="off" value="<?= mata_uang_rp($row->dp_stor) ?>">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tipe Pembayaran</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" autocomplete="off" value="<?= $row->jenis_beli ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tenor</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" autocomplete="off" value="<?= mata_uang_rp($row->tenor) ?>">
                          </div>
                          <?php $finco = $this->db->get_where('ms_finance_company', ['id_finance_company' => $row->id_finance_company]);
                          $finco = $finco->num_rows() > 0 ? $finco->row()->finance_company : ''; ?>
                          <label for="inputEmail3" class="col-sm-2 control-label">Finance Company</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" autocomplete="off" value="<?= $finco ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Cicilan</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" autocomplete="off" value="<?= mata_uang_rp($row->angsuran) ?>">
                          </div>
                          <?php
                          $return = $this->m_admin->detail_individu($row->no_spk);

                          $program = '';
                          $voucher = 0;
                          if ($row->jenis_beli == 'Kredit') {
                            if ($row->chk_program_umum == 1) {
                              $program = $row->program_umum;
                              $voucher = $row->voucher_2 + $row->voucher_tambahan_2;
                            } else {
                              $voucher = $row->diskon;
                            }
                          } else {
                            if ($row->chk_program_umum == 1) {
                              $program = $row->program_umum;
                              $voucher = $row->voucher_1 + $row->voucher_tambahan_1;
                            } else {
                              $voucher = $row->diskon;
                            }
                          }
                          ?>
                          <label for="inputEmail3" class="col-sm-2 control-label">Program (Sales Program)</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" autocomplete="off" value="<?= $program ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Diskon</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" autocomplete="off" value="<?= mata_uang_rp($return['voucher_tambahan'] + $return['voucher'] + $return['voucher2']) ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Harga Jual</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" autocomplete="off" value="<?= mata_uang_rp($return['harga_tunai']) ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Total Harga</label>
                          <div class="col-sm-4">
                            <input type="text" readonly class="form-control" autocomplete="off" value="<?= mata_uang_rp($return['total_bayar']) ?>">
                          </div>
                        </div>
                      </div>
                      <div class="box-footer">
                        <div class="col-sm-12" align="center">
                          <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-print"></i> Print Invoice</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div><!-- /.box -->
          <?php } ?>
        </section>
      </div>
      <div class="modal fade" id="Nosinmodal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              Cari No Mesin
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <div id="showBrowse"></div>
            </div>
          </div>
        </div>
      </div>
      <script type="text/javascript">
        function cek() {
          $("#tampil_alamat").hide();
          $("#isi_cash").hide();
          $("#isi_kredit").hide();
        }

        function cek_spk(cek_nosin = null) {
          var no_spk = $("#no_spk").val();
          if (no_spk == "") {
            alert("Pilih No SPK dahulu...!");
            return false;
          } else {
            $.ajax({
              url: "<?php echo site_url('dealer/sales_order/take_spk') ?>",
              type: "POST",
              data: "no_spk=" + no_spk + "&cek_nosin=" + cek_nosin,
              cache: false,
              success: function(msg) {
                // if (msg=='kosong') {
                //   alert('No. Mesin Tidak Tersedia !');
                //   return false;
                // }
                data = msg.split("|");
                $("#nama_konsumen").val(data[1]);
                $("#no_ktp").val(data[2]);
                $("#no_npwp").val(data[3]);
                $("#id_kelurahan").val(data[4]);
                $("#alamat").val(data[5]);
                $("#kodepos").val(data[6]);
                $("#tanya").val(data[7]);
                $("#id_kelurahan2").val(data[8]);
                $("#no_hp").val(data[9]);
                $("#no_hp_2").val(data[10]);
                $("#no_telp").val(data[11]);
                $("#tipe_ahm").val(data[12]);
                $("#harga").val(data[13]);
                $("#warna").val(data[14]);
                $("#ppn").val(data[15]);
                $("#tahun_produksi").val(data[16]);
                $("#harga_off").val(data[17]);
                $("#biaya_bbn").val(data[18]);
                $("#harga_on").val(data[19]);
                $("#nama_bpkb").val(data[20]);
                $("#tipe_pembelian").val(data[21]);
                $("#the_road").val(data[22]);
                $("#harga_tunai2").val(data[23]);
                $("#program").val(data[24]);
                $("#nilai_voucher").val(data[25]);
                $("#voucher_tambahan").val(data[26]);
                $("#total_bayar").val(data[27]);
                $("#finance_company").val(data[43] + " | " + data[28]);
                $("#dp").val(data[29]);
                $("#program2").val(data[36]);
                $("#nilai_voucher2").val(data[30]);
                $("#voucher_tambahan2").val(data[31]);
                $("#dp_setor").val(data[32]);
                $("#tenor").val(data[33]);
                $("#angsuran").val(data[34]);
                $("#program_gabungan").val(data[37]);
                $("#program_gabungan2").val(data[37]);
                $("#program_umum").val(data[36]);
                $("#browseNosin").attr("data_tipe_kendaraan", data[35]);
                $("#id_tipe_kendaraan").val(data[35]);
                $("#id_warna").val(data[39]);
                var sp = data[40].replace('-', '|');
                $("#sales_people").val(sp);
                $("#id_customer").val(data[41]);
                $("#diskon1").val(data[42]);
                $("#diskon2").val(data[42]);
                $("#no_mesin").val(data[44]);
                $("#no_po_leasing").val(data[45]);
                $("#tgl_po_leasing").val(data[46]);
                take_kec();
                cek_tanya();
                cek_tipe();
                get_ksu();
                if (data[21] == 'Kredit') {
                  $('#bpkb_show').hide();
                  if (data[36] != '') {
                    $('#chk_program_umum2').prop('checked', true);
                  }
                  if (data[37] != '') {
                    $('#chk_program_gabungan2').prop('checked', true);
                  }
                } else {
                  if (data[36] != '') {
                    $('#chk_program_umum').prop('checked', true);
                  }
                  if (data[37] != '') {
                    $('#chk_program_gabungan').prop('checked', true);
                  }
                  $('#bpkb_show').show();
                }
              }
            })
          }
        }

        function take_kec() {
          var id_kelurahan = $("#id_kelurahan").val();
          $.ajax({
            url: "<?php echo site_url('dealer/sales_order/take_kec') ?>",
            type: "POST",
            data: "id_kelurahan=" + id_kelurahan,
            cache: false,
            success: function(msg) {
              data = msg.split("|");
              $("#id_kecamatan").val(data[0]);
              $("#kecamatan").val(data[1]);
              $("#id_kabupaten").val(data[2]);
              $("#kabupaten").val(data[3]);
              $("#id_provinsi").val(data[4]);
              $("#provinsi").val(data[5]);
              $("#kelurahan").val(data[6]);
            }
          })
        }

        function cek_tanya() {
          var tanya = $("#tanya").val();
          if (tanya == 'Tidak') {
            $("#tampil_alamat").show();
            $("#id_kecamatan2").val("");
            $("#id_kabupaten2").val("");
            $("#id_kelurahan2").val("");
            $("#id_provinsi2").val("");
          } else {
            $("#tampil_alamat").hide();
            document.getElementById("id_kecamatan2").value = $("#id_kecamatan").val();
            document.getElementById("id_kabupaten2").value = $("#id_kabupaten").val();
            document.getElementById("id_kelurahan2").value = $("#id_kelurahan").val();
            document.getElementById("id_provinsi2").value = $("#id_provinsi").val();
          }
        }

        function cek_tipe() {
          var tipe_pembelian = $("#tipe_pembelian").val();
          if (tipe_pembelian == 'Cash') {
            $("#isi_cash").show();
            $("#isi_kredit").hide();
	    $("input[name=tgl_po_leasing]").attr("required",false);
	    $("input[name=po_leasing]").attr("required",false);
          } else {
            $("#isi_cash").hide();
            $("#isi_kredit").show();
	    $("input[name=tgl_po_leasing]").attr("required",true);
	    $("input[name=po_leasing]").attr("required",true);

          }
        }

        function take_kec2() {
          var id_kelurahan = $("#id_kelurahan2").val();
          $.ajax({
            url: "<?php echo site_url('dealer/sales_order/take_kec') ?>",
            type: "POST",
            data: "id_kelurahan=" + id_kelurahan,
            cache: false,
            success: function(msg) {
              data = msg.split("|");
              $("#id_kecamatan2").val(data[0]);
              $("#kecamatan2").val(data[1]);
              $("#id_kabupaten2").val(data[2]);
              $("#kabupaten2").val(data[3]);
              $("#id_provinsi2").val(data[4]);
              $("#provinsi2").val(data[5]);
              $("#kelurahan2").val(data[6]);
            }
          })
        }

        function chooseitem(no_mesin, id_tipe_kendaraan) {
          document.getElementById("no_mesin").value = no_mesin;
          document.getElementById("id_tipe_kendaraan").value = id_tipe_kendaraan;
          cek_nosin();
          get_ksu();
          $("#Nosinmodal").modal("hide");
        }

        function openModal() {
          var id_tipe_kendaraan = $('#browseNosin').attr('data_tipe_kendaraan');
          var id_warna = $('#id_warna').val();
          //$(e.currentTarget).find('input[name="bookId"]').val(bookId);
          $.ajax({
            beforeSend: function() {
              // $('#loading-status').show() 
              $("#browseNosin").attr('disabled', true);
              $("#browseNosin").html('<i class="fa fa-spinner fa-spin"></i> Process');
            },
            url: "<?php echo site_url('dealer/sales_order/browseNosin') ?>",
            type: "POST",
            data: "id_tipe_kendaraan=" + id_tipe_kendaraan + "&id_warna=" + id_warna,
            cache: false,
            success: function(msg) {
              $('#showBrowse').html(msg);
              $("#Nosinmodal").modal('show');
              $("#browseNosin").attr('disabled', false);
              $("#browseNosin").html('<i class="fa fa-search"></i> Browse');
              datatables();
            }
          })

        }

        function cek_nosin() {
          var no_mesin = $("#no_mesin").val();
          $.ajax({
            url: "<?php echo site_url('dealer/sales_order/cek_nosin') ?>",
            type: "POST",
            data: "no_mesin=" + no_mesin,
            cache: false,
            success: function(msg) {
              data = msg.split("|");
              if (data[0] == "ok") {
                $("#no_mesin").val(data[1]);
                $("#no_rangka").val(data[2]);
                $("#tahun_produksi").val(data[3]);
              } else {
                alert(data[0]);
              }
            }
          })
        }

        function get_ksu() {
          var id_tipe_kendaraan = $("#id_tipe_kendaraan").val();
          var mode = $("#mode").val();
          $.ajax({
            url: "<?php echo site_url('dealer/sales_order/get_ksu') ?>",
            type: "POST",
            data: "id_tipe_kendaraan=" + id_tipe_kendaraan + "&mode=" + mode,
            cache: false,
            success: function(html) {
              $('#dt_ksu').html(html);
            }
          })
        }

        function datatables() {
          $('.modal #modalexample2').DataTable({
            "destroy": true,
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            // fixedHeader:true,
            "lengthMenu": [
              [10, 25, 50, 75, 100, -1],
              [10, 25, 50, 75, 100, "All"]
            ],
            "autoWidth": true
          });
        }

        function getJenisBayar() {
          var jenis_bayar = $("#jenis_bayar").val();
          var id_sales_order = $("#id_sales_order").val();
          var isi_jenis = $("#isi_jenis").val();
          <?php if ($set == 'kwitansi_gc') { ?>
            var gc = 'ya';
          <?php } else { ?>
            var gc = 'tidak';
          <?php } ?>
          $.ajax({
            url: "<?php echo site_url('dealer/sales_order/getJenisBayar') ?>",
            type: "POST",
            data: "jenis_bayar=" + jenis_bayar + "&id_sales_order=" + id_sales_order + "&isi_jenis=" + isi_jenis + "&gc=" + gc,
            cache: false,
            success: function(html) {
              if (isi_jenis > 0) {
                getDatePicker();
                $('#showJenisBayar_detail').html(html);
              } else {
                $('#showJenisBayar').html(html);
                getDatePicker();
                getSelect2();
              }
            }
          })
        }

        function getDatePicker() {
          $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
          });
        }

        function cek_ambil() {
          var ambil = $("#ambil").val();
          if (ambil == 'Dikirim') {
            $("#dikirim").show();
          } else {
            $("#dikirim").hide();
          }
        }
      </script>
      <script type="text/javascript">
        function getNosin_gc() {
          var no_mesin = $("#no_mesin_gc").val();
          $.ajax({
            url: "<?php echo site_url('dealer/sales_order/getNosin_gc') ?>",
            type: "POST",
            data: "no_mesin=" + no_mesin,
            cache: false,
            success: function(msg) {
              data = msg.split("|");
              if (data[0] == "ok") {
                $("#no_mesin_gc").val(data[1]);
                $("#no_rangka_gc").val(data[2]);
                $("#tipe_warna_gc").val(data[3]);
              } else {
                alert(data[0]);
              }
            }
          })
        }

        function Choosenpwp(no_spk_gc) {
          document.getElementById("no_spk_gc").value = no_spk_gc;
          cek_spk_gc();
          $("#Npwpmodal").modal("hide");
        }

        function cek_spk_gc() {
          var no_spk_gc = $("#no_spk_gc").val();
          $.ajax({
            url: "<?php echo site_url('dealer/sales_order/cek_spk_gc') ?>",
            type: "POST",
            data: "no_spk_gc=" + no_spk_gc,
            cache: false,
            success: function(msg) {
              data = msg.split("|");
              if (data[0] == "ok") {
                $("#no_spk_gc").val(data[9]);
                $("#nama_npwp").val(data[1]);
                $("#no_npwp").val(data[2]);
                $("#alamat").val(data[3]);
                $("#id_kelurahan").val(data[4]);
                $("#jenis_gc").val(data[5]);
                $("#no_telp").val(data[6]);
                $("#tgl_berdiri").val(data[7]);
                $("#kodepos").val(data[8]);
                $("#jenis_beli").val(data[10]);
                take_kec();
                tampil_kendaraan();
                tampil_nosin();
                if (data[10] == 'Kredit') {
                  $("#tampil_po").show();
                } else {
                  $("#tampil_po").hide();
                }
              } else {
                alert(data[0]);
              }
            }
          })
        }

        function tampil_kendaraan() {
          var value = {
            id: $("#no_spk_gc").val()
          }
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/sales_order/getDetail_kendaraan') ?>",
            type: "POST",
            data: value,
            cache: false,
            success: function(html) {
              $('#loading-status').hide();
              $('#showDetail').html(html);
            },
            statusCode: {
              500: function() {
                $('#loading-status').hide();
                alert("Something Wen't Wrong");
              }
            }
          });
        }

        function tampil_nosin(a) {
          var value = {
            id: a,
            no_spk_gc: $("#no_spk_gc").val()
          }
          $.ajax({
            beforeSend: function() {
              $('#loading-status').show();
            },
            url: "<?php echo site_url('dealer/sales_order/getDetail_nosin') ?>",
            type: "POST",
            data: value,
            cache: false,
            success: function(html) {
              $('#loading-status').hide();
              $('#showNosin').html(html);
            },
            statusCode: {
              500: function() {
                $('#loading-status').hide();
                alert("Something Wen't Wrong");
              }
            }
          });
        }

        function addDetail_gc() {
          var no_mesin = $("#no_mesin_gc").val();
          var no_spk_gc = $("#no_spk_gc").val();
          $.ajax({
            url: "<?php echo site_url('dealer/sales_order/addDetail') ?>",
            type: "POST",
            data: "no_mesin=" + no_mesin + "&no_spk_gc=" + no_spk_gc,
            cache: false,
            success: function(data) {
              if (data == 'nihil') {
                tampil_nosin();
              } else {
                alert(data);
              }
            }
          })
        }

        function delDetail(id) {
          var no_spk_gc = $("#no_spk_gc").val();
          $.ajax({
            url: "<?php echo site_url('dealer/sales_order/delDetail') ?>",
            type: "POST",
            data: "id=" + id,
            cache: false,
            success: function(data) {
              if (data == 'nihil') {
                tampil_nosin();
              } else {
                alert(data);
              }
            }
          })
        }
      </script>
      <script type="text/javascript">
        function cek(cekbox) {
          for (i = 0; i < cekbox.length; i++) {
            if (cekbox[i].checked == true) {
              cekbox[i].checked = false;
            } else {
              cekbox[i].checked = true;
            }
          }
        }
      </script>