<?php 

function bln($a){

  $bulan=$bl=$month=$a;

  switch($bulan)

  {

    case"1":$bulan="Januari"; break;

    case"2":$bulan="Februari"; break;

    case"3":$bulan="Maret"; break;

    case"4":$bulan="April"; break;

    case"5":$bulan="Mei"; break;

    case"6":$bulan="Juni"; break;

    case"7":$bulan="Juli"; break;

    case"8":$bulan="Agustus"; break;

    case"9":$bulan="September"; break;

    case"10":$bulan="Oktober"; break;

    case"11":$bulan="November"; break;

    case"12":$bulan="Desember"; break;

  }

  $bln = $bulan;

  return $bln;

}

 



function mata_uang3($a){



  // if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);



    // if(is_numeric($a) AND $a != 0 AND $a != ""){



    //   return number_format($a, 0, ',', '.');



    // }else{



    //   return $a;



    // } 

    return number_format($a, 0, ',', '.');       



}

?>

<style type="text/css">

.myTable1{

  margin-bottom: 0px;

}

.myt{

  margin-top: 0px;

}

.isi{

  height: 25px;

  padding-left: 4px;

  padding-right: 4px;  

}

.vertical-text{

  writing-mode: lr-tb;

  text-orientation: mixed;

}

.rotate {

  -webkit-transform: rotate(-90deg);

  -moz-transform: rotate(-90deg);

}

#mySpan{

  writing-mode: vertical-lr; 

  transform: rotate(180deg);

}

</style>

<base href="<?php echo base_url(); ?>" />

   

    <?php 

    if($set=="view"){

    ?>

<div class="content-wrapper">

<!-- Content Header (Page header) -->

<section class="content-header">

  <h1>

    <?php echo $title; ?>    

  </h1>

  <ol class="breadcrumb">

    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    

    <li class="">H1</li>

    <li class="">Laporan</li>

    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>

  </ol>

  </section>

  <section class="content">

    <div class="box box-default">

      <div class="box-header with-border">        

        <div class="row">

          <div class="col-md-12">

            <form class="form-horizontal" id="frm" method="post" enctype="multipart/form-data">

              <div class="box-body">                                                              

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-1 control-label">Start Date</label>

                  <div class="col-sm-2">

                    <input type="text" class="form-control datepicker" name="start_date" value="<?= date('Y-m-d') ?>" id="start_date">

                  </div>  

                  <label for="inputEmail3" class="col-sm-1 control-label">End Date</label>

                  <div class="col-sm-2">

                    <input type="text" class="form-control datepicker" name="end_date" value="<?= date('Y-m-d') ?>" id="end_date">

                  </div>                                     

                </div>             

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-1 control-label">Dealer</label>

                  <div class="col-sm-4">

                    <select class="form-control select2" name="id_dealer" id="id_dealer">

                      <option value="all">All Dealers</option>

                      <?php 

                      $sql_dealer = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1 ORDER BY ms_dealer.id_dealer ASC");

                      foreach ($sql_dealer->result() as $isi) {

                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md - $isi->nama_dealer</option>";

                      }

                       ?>

                    </select>

                  </div>

                  <div class="col-sm-2">

                    <button type="button" onclick="getReport()" name="process" value="edit" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-download"></i> Download .xls</button>                                                      

                  </div>                  

                </div>                

              </div><!-- /.box-body -->              

              <div class="box-footer">                                                              

                <div style="min-height: 600px">                 

                  <iframe style="overflow: auto; border: 0px solid #fff; width: 100%; height: 602px;margin-bottom: -5px;" id="showReport"></iframe>

                </div>

              </div>

            </form>

          </div>

        </div>

      </div>

    </div><!-- /.box -->



    

    <?php }elseif ($set=='cetak') {

header("Content-type: application/octet-stream");

header("Content-Disposition: attachment; filename=Monitoring Penjualan Dealer Daily.xls");

header("Pragma: no-cache");

header("Expires: 0");

 ?>

    <!DOCTYPE html>

    <html>

    <!-- <html lang="ar"> for arabic only -->

    <head>

      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

      <title>Cetak</title>

      <style>

        @media print {

          @page {

            sheet-size: 297mm 210mm;

            margin-left: 0.8cm;

            margin-right: 0.8cm;

            margin-bottom: 1cm;

            margin-top: 1cm;

          }

          .text-center{text-align: center;}

          .bold{font-weight: bold;}

          .table {

            width: 100%;

            max-width: 100%;

            border-collapse: collapse;

           /*border-collapse: separate;*/

          }

          .table-bordered tr td {

            border: 0.01em solid black;

            padding-left: 6px;

            padding-right: 6px;

          }

          body{

            font-family: "Arial";

            font-size: 11pt;

          }

          .str{ mso-number-format:\@; }

          

        }

      </style>

    </head>

    <body>      

      <div style="text-align: center;font-size: 13pt"><b>Monitoring Penjualan Dealer Daily</b></div>        

      <div style="text-align: center; font-weight: bold;">Tanggal : <?php echo date('d/m/Y',strtotime($start_date)) ?> s/d <?= date('d/m/Y',strtotime($end_date)) ?></div>

      <table class="table table-bordered" border="1">

        <tr>

          <td>TGL PENJUALAN</td>

          <td>KODE DEALER</td>

          <td>NAMA DEALER</td>

          <td>NO MESIN</td>

          <td>NO RANGKA</td>

          <td>KODE TIPE</td>

          <td>KODE WARNA</td>

          <td>DESKRIPSI MOTOR CUSTOMER</td>

          <td>DESKRIPSI WARNA</td>

          <td>HARGA OTR</td>

          <td>TENOR</td>

          <td>DP GROSS</td>

          <td>DP SETOR</td>

          <td>JENIS CUSTOMER</td>

          <td>JENIS KELAMIN</td>

          <td>TGL LAHIR</td>

          <td>NAMA CUSTOMER</td>

          <td>NO KTP</td>

          <td>NO KK</td>

          <td>ALAMAT</td>

          <td>NAMA KELURAHAN</td>

          <td>NAMA KECAMATAN</td>

          <td>NAMA KOTA</td>

          <td>KODE POS</td>

          <td>AGAMA</td>

          <td>PENGELUARAN</td>

          <td>PEKERJAAN</td>

          <td>PEKERJAAN SAAT INI</td>

          <td>PENDIDIKAN</td>

          <td>PENANGGUNG JAWAB</td>

          <td>NO HP</td>

          <td>NO TELP</td>

          <td>AKTIFITAS PENJUALAN</td>

          <td>BERSEDIA DIHUBUNGI</td>

          <td>MERK MOTOR SEKARANG</td>

          <td>DIGUNAKAN UNTUK</td>

          <td>YG MENGGUNAKAN MOTOR</td>

          <td>HOBI</td>

          <td>ID FLP</td>

          <td>NAMA FLP DEALER</td>

          <td>JABATAN</td>

          <td>NAMA FINCOY</td>

          <td>KETERANGAN</td>

          <td>EMAIL</td>

          <td>NAMA TEMPAT KANTOR / USAHA</td>
          <td>ALAMAT KANTOR </td>
          <td>KELURAHAN KANTOR</td>
          <td>NO INVOICE</td>
          <td>SALES PROGRAM</td>
          <td>NAMA SALES PROGRAM</td>
          <td>NO PO LEASING (SO)</td>
          <td>TGL PO LEASING (SO)</td>

        </tr>

        <?php

          $filter_dealer = '';

          if ($id_dealer!='all') {

            $filter_dealer = "AND tr_sales_order.id_dealer='$id_dealer'";

          }

          $so = $this->db->query("SELECT tr_sales_order.*,nama_dealer,kode_dealer_md,nama_dealer,tr_spk.*,tipe_ahm,warna 

            FROM tr_sales_order 

            JOIN ms_dealer ON tr_sales_order.id_dealer=ms_dealer.id_dealer

            JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk

            JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan

            JOIN ms_warna ON tr_spk.id_warna=ms_warna.id_warna

            WHERE (tgl_cetak_invoice IS NOT NULL OR tgl_cetak_invoice<>'') AND tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' $filter_dealer ORDER BY tgl_cetak_invoice ASC, jam_cetak_invoice asc ");

          foreach ($so->result() as $so) {

            $prp = $this->db->query("SELECT * FROM tr_prospek WHERE id_customer='$so->id_customer' ORDER BY created_at DESC LIMIT 1")->row();

            $cdb = $this->db->query("SELECT * FROM tr_cdb WHERE no_spk='$so->no_spk' ORDER BY created_at DESC LIMIT 1")->row();

            $jk = $prp->jenis_kelamin=='Pria'?'L':'P';

	    $aktifitas = $this->db->get_where('ms_sumber_prospek',['id_dms'=>$prp->sumber_prospek]);
	
	    $aktifitas = $aktifitas->num_rows()>0?$aktifitas ->row()->description:'';

	    $nama_program ='';
	    $program='';
	    $tgl_po_leasing = '';

	    if($so->jenis_beli =='Kredit'){
		$tgl_po_leasing = $so->tgl_po_leasing;
	    }
		if($so->program_gabungan !='' && $so->program_gabungan !='- choose-' && $so->program_umum !='' && $so->program_umum !='- choose-'){
			$program = $so->program_umum .' & '. $so->program_gabungan;
			
			$get_program = $this->db->get_where('tr_sales_program',['id_program_md'=>$so->program_umum]);
            		$nama_program = $get_program->num_rows()>0 ? $get_program->row()->judul_kegiatan:'';

			if($nama_program ==''){
				$get_program = $this->db->get_where('tr_sales_program',['id_program_md'=>$so->program_gabungan]);
            			$nama_program = $get_program->num_rows()>0 ? $get_program->row()->judul_kegiatan:'';
			}else{
				$get_program = $this->db->get_where('tr_sales_program',['id_program_md'=>$so->program_gabungan]);
				$temp= $get_program->num_rows()>0 ? $get_program->row()->judul_kegiatan:'';
            			$nama_program = $nama_program .' & '. $temp;
			}
		}else if($so->program_umum !='' && $so->program_umum !='- choose-'){
			$program = $so->program_umum;
			$nama_program ='';

			$get_program = $this->db->get_where('tr_sales_program',['id_program_md'=>$so->program_umum]);
            		$nama_program = $get_program->num_rows()>0 ? $get_program->row()->judul_kegiatan:'';			
		}


            $kelurahan_kantor = $this->db->get_where('ms_kelurahan',['id_kelurahan'=>$prp->id_kelurahan_kantor]);

            $kelurahan_kantor = $kelurahan_kantor->num_rows()>0?$kelurahan_kantor->row()->kelurahan:'';

            $kelurahan = $this->db->get_where('ms_kelurahan',['id_kelurahan'=>$so->id_kelurahan]);

            $kelurahan = $kelurahan->num_rows()>0?$kelurahan->row()->kelurahan:'';

              $kecamatan = $this->db->get_where('ms_kecamatan',['id_kecamatan'=>$so->id_kecamatan]);

            $kecamatan = $kecamatan->num_rows()>0?$kecamatan->row()->kecamatan:'';

              $kabupaten = $this->db->get_where('ms_kabupaten',['id_kabupaten'=>$so->id_kabupaten]);

            $kabupaten = $kabupaten->num_rows()>0?$kabupaten->row()->kabupaten:'';

              $provinsi = $this->db->get_where('ms_provinsi',['id_provinsi'=>$so->id_provinsi]);

            $provinsi = $provinsi->num_rows()>0?$provinsi->row()->provinsi:'';

            $agama = $this->db->get_where('ms_agama',['id_agama'=>$cdb->agama]);

            $agama = $agama->num_rows()>0?$agama->row()->agama:'';

            $pekerjaan = $this->db->get_where('ms_pekerjaan',['id_pekerjaan'=>$so->pekerjaan]);

            $pekerjaan = $pekerjaan->num_rows()>0?$pekerjaan->row()->pekerjaan:'';

            $pendidikan = $this->db->get_where('ms_pendidikan',['id_pendidikan'=>$cdb->pendidikan]);

            $pendidikan = $pendidikan->num_rows()>0?$pendidikan->row()->pendidikan:'';

            $merk_sebelumnya = $this->db->get_where('ms_merk_sebelumnya',['id_merk_sebelumnya'=>$cdb->merk_sebelumnya]);

            $merk_sebelumnya = $merk_sebelumnya->num_rows()>0?$merk_sebelumnya->row()->merk_sebelumnya:'';

            $digunakan = $this->db->get_where('ms_digunakan',['id_digunakan'=>$cdb->digunakan]);

            $digunakan = $digunakan->num_rows()>0?$digunakan->row()->digunakan:'';

            $hobi = $this->db->get_where('ms_hobi',['id_hobi'=>$cdb->hobi]);

            $hobi = $hobi->num_rows()>0?$hobi->row()->hobi:'';

            $kry = $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_karyawan_dealer='$prp->id_karyawan_dealer'");

            $id_flp = $kry->num_rows()>0?$kry->row()->id_flp_md:'';

            $nama_sales = $kry->num_rows()>0?$kry->row()->nama_lengkap:'';

            $id_jabatan = $kry->num_rows()>0?$kry->row()->id_jabatan:'';

            $jabatan_flp = $this->db->get_where('ms_jabatan',['id_jabatan'=>$id_jabatan]);

            $jabatan_flp = $jabatan_flp->num_rows()>0?$jabatan_flp->row()->jabatan:'';

             $finco = $this->db->get_where('ms_finance_company',['id_finance_company'=>$so->id_finance_company]);

            $finco = $finco->num_rows()>0?$finco->row()->finance_company:'';

            $pengeluaran_bulan = $this->db->get_where('ms_pengeluaran_bulan',['id_pengeluaran_bulan'=>$so->pengeluaran_bulan]);

            $pengeluaran_bulan = $pengeluaran_bulan->num_rows()>0?$pengeluaran_bulan->row()->pengeluaran:'0';



            $pekerjaan_lain = $prp->sub_pekerjaan;



            if($pekerjaan_lain == 101){

              $pekerjaan_lain = strtoupper($prp->pekerjaan_lain);

            }else if($pekerjaan_lain !=''){

                $pekerjaan_lain = $this->db->get_where('ms_sub_pekerjaan',['id_sub_pekerjaan'=>$prp->sub_pekerjaan]);

                $pekerjaan_lain = $pekerjaan_lain->num_rows()>0?$pekerjaan_lain->row()->sub_pekerjaan:'';

            }





            echo '<tr>';

            echo "

                <td>$so->tgl_cetak_invoice $so->jam_cetak_invoice</td>

                <td>$so->kode_dealer_md &nbsp;</td>

                <td>$so->nama_dealer</td>

                <td>$so->no_mesin</td>

                <td>$so->no_rangka</td>

                <td>$so->id_tipe_kendaraan</td>

                <td>$so->id_warna</td>

                <td>$so->tipe_ahm</td>

                <td>$so->warna</td>

                <td>".mata_uang3($so->harga_tunai)."</td>

                <td>".$so->tenor."</td>

                <td>".$so->uang_muka."</td>

                <td>".$so->dp_stor."</td>

                <td>Individu</td>

                <td>$jk</td>

                <td>$so->tgl_lahir</td>

                <td>$so->nama_konsumen</td>";

                echo "<td>$so->no_ktp &nbsp;</td>";

                echo "<td>$so->no_kk &nbsp;</td>";

                // echo "<td class='str'>$so->no_ktp</td>";

                echo "

                <td>$so->alamat</td>

                <td>$kelurahan</td>

                <td>$kecamatan</td>

                <td>$kabupaten</td>

                <td>$provinsi</td>

                <td>$agama</td>

                <td>".$pengeluaran_bulan."</td>

                <td>$pekerjaan</td>

                <td>$pekerjaan_lain</td>

                <td>$pendidikan</td>

                <td></td>";

                echo "<td>$so->no_hp &nbsp;</td>";

                echo "<td>$so->no_telp &nbsp;</td>";
                
 		echo "<td>$aktifitas</td>";

                // <td>$so->no_hp</td>

                // <td>$so->no_telp</td>

                echo "<td>$cdb->sedia_hub</td>

                <td>$merk_sebelumnya</td>

                <td>$digunakan</td>

                <td>$cdb->menggunakan</td>

                <td>$hobi</td>

                <td>$id_flp</td>

                <td>$nama_sales</td>

                <td>$jabatan_flp</td>

                <td>$finco</td>

                <td></td>

                <td>$so->email</td>

                <td>$prp->nama_tempat_usaha</td>

                <td>$prp->alamat_kantor</td>

                <td>$kelurahan_kantor</td>
                <td>$so->no_invoice</td>
                <td>$program</td>
                <td>$nama_program</td>
                <td>$so->no_po_leasing</td>
                <td>$tgl_po_leasing</td>

            ";

            echo '</tr>';

          }



          $filter_dealer2 = '';

          if ($id_dealer!='all') {

            $filter_dealer2 = "AND tr_sales_order_gc.id_dealer='$id_dealer'";

          }

          $so2 = $this->db->query("SELECT tr_sales_order_gc.*,tr_scan_barcode.tipe_motor AS id_tipe_kendaraan, tr_sales_order_gc_nosin.no_mesin,tr_scan_barcode.no_rangka, ms_dealer.nama_dealer,ms_dealer.kode_dealer_md,tr_spk_gc.*, ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_warna.id_warna FROM tr_sales_order_gc

            INNER JOIN tr_sales_order_gc_nosin ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc 

            INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin

            INNER JOIN ms_dealer ON tr_sales_order_gc.id_dealer=ms_dealer.id_dealer

            INNER JOIN tr_spk_gc ON tr_spk_gc.no_spk_gc=tr_sales_order_gc.no_spk_gc            

            INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan

            INNER JOIN ms_warna ON tr_scan_barcode.warna=ms_warna.id_warna

            WHERE (tgl_cetak_invoice IS NOT NULL OR tgl_cetak_invoice<>'') AND tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' $filter_dealer2 ORDER BY tgl_cetak_invoice ASC, jam_cetak_invoice asc ");

          foreach ($so2->result() as $so) {

            $harga = $this->db->query("SELECT * FROM tr_spk_gc_detail WHERE no_spk_gc='$so->no_spk_gc' AND id_tipe_kendaraan = '$so->id_tipe_kendaraan' AND id_warna = '$so->id_warna'")->row();

            $prp = $this->db->query("SELECT * FROM tr_prospek_gc WHERE no_npwp='$so->no_npwp' ORDER BY created_at DESC LIMIT 1")->row();

            $cdb = $this->db->query("SELECT * FROM tr_cdb_gc WHERE no_spk_gc='$so->no_spk_gc' ORDER BY created_at DESC LIMIT 1")->row();

            $jk = $prp->jenis_kelamin=='Pria'?'L':'P';

 	    $aktifitas = $this->db->get_where('ms_sumber_prospek',['id_dms'=>$prp->sumber_prospek]);
	
	    $aktifitas = $aktifitas->num_rows()>0?$aktifitas ->row()->description:'';

	    $nama_program ='';
	    $program ='';
		if($so->id_program!='' && $so->id_program !='- choose-'){
			$program = $so->id_program;
			
			$get_program = $this->db->get_where('tr_sales_program',['id_program_md'=>$so->id_program]);
            		$nama_program = $get_program->num_rows()>0 ? $get_program->row()->judul_kegiatan:'';
		}

            $kelurahan = $this->db->get_where('ms_kelurahan',['id_kelurahan'=>$so->id_kelurahan]);

            $kelurahan = $kelurahan->num_rows()>0?$kelurahan->row()->kelurahan:'';

              $kecamatan = $this->db->get_where('ms_kecamatan',['id_kecamatan'=>$so->id_kecamatan]);

            $kecamatan = $kecamatan->num_rows()>0?$kecamatan->row()->kecamatan:'';

              $kabupaten = $this->db->get_where('ms_kabupaten',['id_kabupaten'=>$so->id_kabupaten]);

            $kabupaten = $kabupaten->num_rows()>0?$kabupaten->row()->kabupaten:'';

              $provinsi = $this->db->get_where('ms_provinsi',['id_provinsi'=>$so->id_provinsi]);

            $provinsi = $provinsi->num_rows()>0?$provinsi->row()->provinsi:'';

            $agama = $this->db->get_where('ms_agama',['id_agama'=>$cdb->agama]);

            $agama = $agama->num_rows()>0?$agama->row()->agama:'';

            $pekerjaan = $this->db->get_where('ms_pekerjaan',['id_pekerjaan'=>$so->pekerjaan]);

            $pekerjaan = $pekerjaan->num_rows()>0?$pekerjaan->row()->pekerjaan:'';

            $pendidikan = $this->db->get_where('ms_pendidikan',['id_pendidikan'=>$cdb->pendidikan]);

            $pendidikan = $pendidikan->num_rows()>0?$pendidikan->row()->pendidikan:'';

            $merk_sebelumnya = $this->db->get_where('ms_merk_sebelumnya',['id_merk_sebelumnya'=>$cdb->merk_sebelumnya]);

            $merk_sebelumnya = $merk_sebelumnya->num_rows()>0?$merk_sebelumnya->row()->merk_sebelumnya:'';

            $digunakan = $this->db->get_where('ms_digunakan',['id_digunakan'=>$cdb->digunakan]);

            $digunakan = $digunakan->num_rows()>0?$digunakan->row()->digunakan:'';

            $hobi = $this->db->get_where('ms_hobi',['id_hobi'=>$cdb->hobi]);

            $hobi = $hobi->num_rows()>0?$hobi->row()->hobi:'';

            $kry = $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_karyawan_dealer='$prp->id_karyawan_dealer'");

            $id_flp = $kry->num_rows()>0?$kry->row()->id_flp_md:'';

            $nama_sales = $kry->num_rows()>0?$kry->row()->nama_lengkap:'';

            $id_jabatan = $kry->num_rows()>0?$kry->row()->id_jabatan:'';

            $jabatan_flp = $this->db->get_where('ms_jabatan',['id_jabatan'=>$id_jabatan]);

            $jabatan_flp = $jabatan_flp->num_rows()>0?$jabatan_flp->row()->jabatan:'';

             $finco = $this->db->get_where('ms_finance_company',['id_finance_company'=>$so->id_finance_company]);

            $finco = $finco->num_rows()>0?$finco->row()->finance_company:'';

            $pengeluaran_bulan = $this->db->get_where('ms_pengeluaran_bulan',['id_pengeluaran_bulan'=>$so->pengeluaran_bulan]);

            $pengeluaran_bulan = $pengeluaran_bulan->num_rows()>0?$pengeluaran_bulan->row()->pengeluaran:'0';



            $pekerjaan_lain = $prp->sub_pekerjaan;



            if($pekerjaan_lain == 101 ){

              $pekerjaan_lain = $prp->pekerjaan_lain;

            }else if($pekerjaan_lain !=''){

                $pekerjaan_lain = $this->db->get_where('ms_sub_pekerjaan',['id_sub_pekerjaan'=>$prp->sub_pekerjaan]);

                $pekerjaan_lain = $pekerjaan_lain->num_rows()>0?$pekerjaan_lain->row()->sub_pekerjaan:'';

              

            }

	    $dp_gross=$harga->dp_stor+$harga->nilai_voucher;

            echo '<tr>';

            echo "

                <td>$so->tgl_cetak_invoice $so->jam_cetak_invoice</td>

                <td>$so->kode_dealer_md &nbsp;</td>

                <td>$so->nama_dealer</td>

                <td>$so->no_mesin</td>

                <td>$so->no_rangka</td>

                <td>$so->id_tipe_kendaraan</td>

                <td>$so->id_warna</td>

                <td>$so->tipe_ahm</td>

                <td>$so->warna</td>

                <td>=".mata_uang3($harga->harga)."</td>

                <td>".$harga->tenor."</td>

                <td>".$dp_gross."</td>

                <td>".$harga->dp_stor."</td>

                <td>GC</td>

                <td>$jk</td>

                <td>$so->tgl_lahir</td>

                <td>$so->nama_npwp</td>

                <td>$so->no_ktp &nbsp;</td>
	
		 <td></td>

                <td>$so->alamat</td>

                <td>$kelurahan</td>

                <td>$kecamatan</td>

                <td>$kabupaten</td>

                <td>$provinsi</td>

                <td>$agama</td>

                <td>".$pengeluaran_bulan."</td>

                <td>$pekerjaan</td>

                <td>$pekerjaan_lain</td>

                <td>$pendidikan</td>

                <td></td>

                <td>$so->no_hp &nbsp;</td>

                <td>$so->no_telp &nbsp;</td>

 		<td>$aktifitas </td>

                <td>$cdb->sedia_hub</td>

                <td>$merk_sebelumnya</td>

                <td>$digunakan</td>

                <td>$cdb->menggunakan</td>

                <td>$hobi</td>

                <td>$id_flp</td>

                <td>$nama_sales</td>

                <td>$jabatan_flp</td>

                <td>$finco</td>

                <td></td>

                <td>$so->email</td>
                <td></td>
                <td></td>
                <td></td>
                <td>$so->no_invoice</td>
                <td>$program</td>
                <td>$nama_program</td>
                <td>$so->no_po_leasing</td>
                <td>$so->tgl_po_leasing</td>

            ";

            echo '</tr>';

          }



        ?>

      </table>

    </body>

  </html>

  <?php } ?>



  </section>

</div>





<script>

    function getReport()

    {

      var value={start_date:document.getElementById("start_date").value,

                end_date:document.getElementById("end_date").value,

                id_dealer:document.getElementById("id_dealer").value,

                cetak:'cetak',

                }



      if (value.tipe == '') {

        alert('Isi data dengan lengkap ..!');

        return false;

      }else{

        //alert(value.tipe);

        $('.loader').show();

        $('#btnShow').disabled;

        $("#showReport").attr("src",'<?php echo site_url("h1/monitoring_penjualan_dealer_daily?") ?>tipe='+value.tipe+'&cetak='+value.cetak+'&start_date='+value.start_date+'&end_date='+value.end_date+'&id_dealer='+value.id_dealer);

        document.getElementById("showReport").onload = function(e){          

        $('.loader').hide();       

        };

      }

    }

function getRadioVal(form, name) {

  var val;  

  var radios = form.elements[name];

  for (var i=0, len=radios.length; i<len; i++) {

      if ( radios[i].checked ) { // radio checked?

          val = radios[i].value; // if so, hold its value in val

          break; // and break out of for loop

      }

  }

  return val; // return value of checked radio or undefined if none checked

}

</script>