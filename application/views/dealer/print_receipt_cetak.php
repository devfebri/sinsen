<?php
// $skip_dealer = [22, 51, 45, 66, 77, 80, 103]; 
$skip_dealer = [];
?>
<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
<?php
function mata_uang($a)
{
  return number_format($a, 0, ',', '.');
}
?>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Cetak</title>
  <style>
    @media print {
      @page {
        sheet-size: 210mm 297mm;
        margin-left: 0.9cm;
        margin-right: 0.9cm;
        margin-top: 1cm;
        /* margin-top: 1cm; */
      }

      .text-center {
        text-align: center;
      }

      .table {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse;
        /*border-collapse: separate;*/
      }

      .table-bordered tr td {
        border: 1px solid black;
        padding-left: 6px;
        padding-right: 6px;
      }

      body {
        font-family: "Arial";
        font-size: 11pt;
      }
    }
  </style>
</head>

<body>

  <?php
  if ($set == 'print_tjs') {
  ?>
    <table class="table table-bordereds" border=0>
      <tr>
        <?php
        $id_dealer     = $this->m_admin->cari_dealer();

        if (!in_array($id_dealer, $skip_dealer)) { ?>
          <td width='65%' style='vertical-align:top'>
            <?php
            $dealer = $this->db->query("SELECT ms_dealer.*,kelurahan,kode_pos, kecamatan,kabupaten FROM ms_dealer 
            LEFT JOIN ms_kelurahan ON ms_kelurahan.id_kelurahan=ms_dealer.id_kelurahan
            LEFT JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan=ms_kelurahan.id_kecamatan
            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten=ms_kecamatan.id_kabupaten
            WHERE id_dealer='$id_dealer'")->row();

 	    if ($dealer ->logo != "") {
        	$logo = $dealer ->logo;
      	    } else {
		$logo ='';
      	    }
            ?>

            <b style='font-size:10pt'><?= $dealer->nama_dealer ?></b><br>
            <span style='font-size:9pt'><?= $dealer->alamat ?><br> TELP. <?= $dealer->no_telp ?> <?php if($dealer->fax_number!=''){ echo "FAX. ". $dealer->fax_number; } ?></span>
          </td>
        <?php } else { ?>
          <td width='65%'></td>
        <?php } ?>
        <td>
          <table class='table' style='font-size:9pt;' border=0>
		<?php if($logo!=''){
			//<img  style ="width: 150px; height:50px;" src="<?php echo base_url('assets/panel/images/'.$logo); ?>" alt="Forest">
		?>
			<tr><td colspan="3" class="right" style="text-align: right; vertical-align: top;"></td></tr>
          	<?php }else{?>
			<tr><td colspan="3"></td></td>
		<?php }?>
	   </table>
        </td>
      </tr>
    </table>
    <table class="table table-bordereds" border=0>
      <tr>
	<td width="65%" style='font-size:12pt;<?= in_array($id_dealer, $skip_dealer) ? 'color:white;' : '' ?>'><b>KUITANSI</b><br>&nbsp;</td>
        <td>
          <table class='table' style='font-size:9pt;' border="0">
	    <tr>
             	<td style="padding-right:50px;">Tgl. Pembelian</td>
        	<td width="2%" style="vertical-align:top">:</td>
              	<td style="padding-left:5px;"><?= date_dmy($row->tgl_spk) ?></td>
            </tr>
            <tr>
              	<td>Tgl. Pembayaran</td>
        	<td width="2%" style="vertical-align:top">:</td>
              	<td style="padding-left:5px;"><?= date_dmy($row->tgl_pembayaran) ?></td>
            </tr>
            <tr>
              	<td>No. SPK</td>
        	<td width="2%" style="vertical-align:top">:</td>
              	<td style="padding-left:5px;"><?= $row->id_spk ?></td>
            </tr>
            <tr>
              	<td>Nama Sales People</td>
        	<td width="2%" style="vertical-align:top">:</td>
              	<td style="padding-left:5px;"><?= $row->nama_lengkap ?></td>
            </tr>
	  </table>
        </td>
      </tr>
    </table>

    <table class="table table-borderedx" style='font-size:11pt' border ="0">
      <tr>
        <td width="20%">No. Kwitansi</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td><?= $row->id_kwitansi ?></td>
      </tr>
      <tr>
        <td width="20%">Terima Dari</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td><?= $row->nama_konsumen ?></td>
      </tr>
      <tr>
        <td>No KTP</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td><?= $row->no_ktp ?></td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td><?= $row->alamat ?></td>
      </tr>
      <tr>
        <td>Sejumlah</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td><?= mata_uang_rp($row->tanda_jadi) ?></td>
      </tr>
      <tr>
        <td>Terbilang</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td colspan="4"> <?= ucwords(to_word($row->tanda_jadi)) ?> rupiah</td>
      </tr>
      <tr>
        <td width="21%" style="vertical-align:top">Tipe Motor / Warna</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td colspan=4>
          <?php foreach ($details as $ns) { ?>
            <?= $ns->tipe_ahm . ' / ' . $ns->warna ?> <br>
          <?php } ?>
        </td>
      </tr>
      <tr>
        <td>Keterangan</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td colspan="4"><?= $row->note ?></td>
      </tr>
    </table>
    <p style='<?= in_array($id_dealer, $skip_dealer) ? 'color:white;' : '' ?>'>Cara Pembayaran :</p>
    <table class="table table-borderedx" style='font-size:11pt'>
      <?php if (!in_array($id_dealer, $skip_dealer)) { ?>
        <tr>
          <td style="border-bottom: 1px solid black;width: 20%">Keterangan</td>
          <td style="border-bottom: 1px solid black;width: 15%">Nominal</td>
          <td style="border-bottom: 1px solid black;width: 10%">Tanggal</td>
          <td style="border-bottom: 1px solid black;width: 25%">No. BG / Cek</td>
          <td style="border-bottom: 1px solid black;width: 25%">Nama Bank</td>
        </tr>
      <?php } else { ?>

        <tr>
          <td style="color:white;width: 20%">Keterangan</td>
          <td style="color:white;width: 15%">Nominal</td>
          <td style="color:white;width: 10%">Tanggal</td>
          <td style="color:white;width: 25%">No. BG / Cek</td>
          <td style="color:white;width: 25%">Nama Bank</td>
        </tr>
      <?php } ?>
      <?php
      foreach ($dt_bayar as $dt) { ?>
        <tr>
          <td><?= $dt->metode_penerimaan_full ?></td>
          <td><?= mata_uang_rp($dt->nominal) ?></td>
          <td><?= date_dmy($dt->tgl_terima) ?></td>
          <td><?= $dt->no_bg_cek ?></td>
          <td><?= $dt->bank ?></td>
        </tr>
      <?php } ?>
    </table>

    <br><br>
    <?php if (!in_array($id_dealer, $skip_dealer)) { ?>
      <table class="table">
        <tr>
          <td width="60%" style='vertical-align:top'>
            <table style='font-size:8pt;text-align:justify;border:0.6px solid black'>
              <tr>
                <td>-</td>
                <td>Pembayaran dengan Bilyet Giro / Cek dianggap sah bila telah diuangkan.</td>
              </tr>
              <tr>
                <td style='vertical-align:top'>-</td>
                <td>Jika dalam batas waktu 1 (Satu) minggu dari tanggal pengeluaran tanda terima ini tidak ada keberatan yang disampaikan mengenai pembayaran untuk data - data tercetak, maka transaksi ini kami anggap telah disetujui</td>
              </tr>
            </table>
          </td>
          <td width='20%'></td>
          <td align="center">Jambi, <?= date_dmy(get_ymd()) ?><br>
            Penerima,
            <br><br><br><br>
            ( Kepala Kasir )
          </td>
        </tr>
      </table>
    <?php } ?>

  <?php } elseif ($set == 'print_dp') {
  ?>
    <table class="table table-bordereds">
      <tr>
        <?php
        $id_dealer     = $this->m_admin->cari_dealer();

        if (!in_array($id_dealer, $skip_dealer)) { ?>
          <td width='65%' style='vertical-align:top'>
            <?php
            $dealer = $this->db->query("SELECT ms_dealer.*,kelurahan,kode_pos, kecamatan,kabupaten FROM ms_dealer 
            LEFT JOIN ms_kelurahan ON ms_kelurahan.id_kelurahan=ms_dealer.id_kelurahan
            LEFT JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan=ms_kelurahan.id_kecamatan
            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten=ms_kecamatan.id_kabupaten
            WHERE id_dealer='$id_dealer'")->row();
            
 	    if ($dealer ->logo != "") {
        	$logo = $dealer ->logo;
      	    } else {
		$logo ='';
      	    }
            ?>

            <b style='font-size:10pt'><?= $dealer->nama_dealer ?></b><br>
            <span style='font-size:9pt'><?= $dealer->alamat ?><br> TELP. <?= $dealer->no_telp ?> <?php if($dealer->fax_number!=''){ echo "FAX. ". $dealer->fax_number; } ?></span>
          </td>
        <?php } else { ?>
          <td width='65%'></td>
        <?php } ?>
        <td>
          <table class='table' style='font-size:9pt;' border=0>
		<?php if($logo!=''){?>
			<tr><td colspan="3" class="right" style="text-align: right; vertical-align: top;"></td></tr>
          	<?php }else{?>
			<tr><td colspan="3"></td></td>
		<?php }?>
	   </table>
        </td>
      </tr>
    </table>
    <table class="table table-bordereds" border=0>
      <tr>
	<td width="65%" style='font-size:12pt;<?= in_array($id_dealer, $skip_dealer) ? 'color:white;' : '' ?>'><b>KUITANSI</b><br>&nbsp;</td>
        <td>
          <table class='table' style='font-size:9pt;' border="0">
	    <tr>
             	<td style="padding-right:50px;">Tgl. Pembelian</td>
        	<td width="2%" style="vertical-align:top">:</td>
              	<td style="padding-left:5px;"><?= date_dmy($row->tgl_spk) ?></td>
            </tr>
            <tr>
              	<td>Tgl. Pembayaran</td>
        	<td width="2%" style="vertical-align:top">:</td>
              	<td style="padding-left:5px;"><?= date_dmy($row->tgl_pembayaran) ?></td>
            </tr>
            <tr>
              	<td>No. SPK</td>
        	<td width="2%" style="vertical-align:top">:</td>
              	<td style="padding-left:5px;"><?= $row->no_spk ?></td>
            </tr>
            <tr>
              	<td>Nama Sales People</td>
        	<td width="2%" style="vertical-align:top">:</td>
              	<td style="padding-left:5px;"><?= $row->nama_lengkap ?></td>
            </tr>
	  </table>
        </td>
      </tr>
    </table>
    <br>
    <table class="table table-bordereds">
      <tr>
        <td width="18%">No. Kwitansi</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td colspan=4><?= $row->id_kwitansi ?></td>
      </tr>
      <tr>
        <td width="20%">Terima Dari</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td><?= $row->nama_konsumen ?></td>
      </tr>
      <tr>
        <td>No KTP</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td><?= $row->no_ktp ?></td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td style="vertical-align: top">:</td>
        <td><?= $row->alamat ?></td>
      </tr>
      <tr>
        <td>Uang Sejumlah</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td colspan="5">Rp. <?= mata_uang_rp($row->amount) ?></td>
      </tr>
      <tr>
        <td>Terbilang</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td colspan="5"><?= ucwords(to_word($row->amount)) ?> rupiah</td>
      </tr>
      <tr>
        <td>Tipe Motor</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td colspan="5">
          <?php foreach ($dt_no_mesin->result() as $nosin) { ?>
            <?= $nosin->tipe_ahm ?> <br>
          <?php } ?>
        </td>
      </tr>
      <tr>
        <td width="21%" style="vertical-align:top">No. Mesin/No. Rangka</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td colspan=4>
          <?php foreach ($dt_no_mesin->result() as $nosin) { ?>
            <?= $nosin->no_mesin . ' / ' . $nosin->no_rangka ?> <br>
          <?php } ?>
        </td>
      </tr>
      <tr>
        <td>Sisa Hutang</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td colspan="5">Rp. <?= mata_uang_rp($dp->sisa_pelunasan) ?></td>
      </tr>
      <tr>
        <td>Keterangan</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td colspan="5"><?= $row->note ?></td>
      </tr>
    </table>
    <p style='<?= in_array($id_dealer, $skip_dealer) ? 'color:white;' : '' ?>'>Cara Pembayaran :</p>
    <table class="table table-borderedx">
      <?php if (!in_array($id_dealer, $skip_dealer)) { ?>
        <tr>
          <td style="border-bottom: 1px solid black;width: 20%">Keterangan</td>
          <td style="border-bottom: 1px solid black;width: 15%">Nominal</td>
          <td style="border-bottom: 1px solid black;width: 10%">Tanggal</td>
          <td style="border-bottom: 1px solid black;width: 25%">No. BG / Cek</td>
          <td style="border-bottom: 1px solid black;width: 25%">Nama Bank</td>
        </tr>
      <?php } else { ?>

        <tr>
          <td style="color:white;width: 20%">Keterangan</td>
          <td style="color:white;width: 15%">Nominal</td>
          <td style="color:white;width: 10%">Tanggal</td>
          <td style="color:white;width: 25%">No. BG / Cek</td>
          <td style="color:white;width: 25%">Nama Bank</td>
        </tr>
      <?php } ?>
      <?php
      foreach ($dt_bayar as $dt) { ?>
        <tr>
          <td><?= $dt->metode_penerimaan_full ?></td>
          <td><?= mata_uang_rp($dt->nominal) ?></td>
          <td><?= date_dmy($dt->tgl_terima) ?></td>
          <td><?= $dt->no_bg_cek ?></td>
          <td><?= $dt->bank ?></td>
        </tr>
      <?php } ?>
    </table>
    <br><br>
    <?php if (!in_array($id_dealer, $skip_dealer)) { ?>

      <table class="table">
        <tr>
          <td width="60%" style='vertical-align:top'>
            <table style='font-size:8pt;text-align:justify;border:0.6px solid black'>
              <tr>
                <td>-</td>
                <td>Pembayaran dengan Bilyet Giro / Cek dianggap sah bila telah diuangkan.</td>
              </tr>
              <tr>
                <td style='vertical-align:top'>-</td>
                <td>Jika dalam batas waktu 1 (Satu) minggu dari tanggal pengeluaran tanda terima ini tidak ada keberatan yang disampaikan mengenai pembayaran untuk data - data tercetak, maka transaksi ini kami anggap telah disetujui</td>
              </tr>
            </table>
          </td>
          <td width='20%'></td>
          <td align="center">Jambi, <?= date_dmy(get_ymd()) ?><br>
            Penerima,
            <br><br><br><br>
            ( Kepala Kasir )
          </td>
        </tr>
      </table>
    <?php } ?>
  <?php } elseif ($set == 'print_pelunasan') {
  ?>
    <table class="table table-bordereds">
      <tr>
        <?php
        $id_dealer     = $this->m_admin->cari_dealer();

        if (!in_array($id_dealer, $skip_dealer)) { ?>
          <td width='65%' style='vertical-align:top'>
            <?php
              $dealer = $this->db->query("SELECT ms_dealer.*,kelurahan,kode_pos, kecamatan,kabupaten FROM ms_dealer 
              LEFT JOIN ms_kelurahan ON ms_kelurahan.id_kelurahan=ms_dealer.id_kelurahan
              LEFT JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan=ms_kelurahan.id_kecamatan
              LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten=ms_kecamatan.id_kabupaten
              WHERE id_dealer='$id_dealer'")->row();
              if ($dealer ->logo != "") {
                $logo = $dealer ->logo;	
              } else {
                $logo ='';
              }
              ?>

              <b style='font-size:10pt'><?= $dealer->nama_dealer ?></b><br>
              <span style='font-size:9pt'><?= $dealer->alamat ?><br> TELP. <?= $dealer->no_telp ?> <?php if($dealer->fax_number!=''){ echo "FAX. ". $dealer->fax_number; } ?></span>
            </td>
          <?php } else { ?>
            <td width='65%'></td>
          <?php } ?>
        <td>
          <table class='table' style='font-size:9pt;' border=0>
            <?php if($logo!=''){?>
              <tr><td colspan="3" class="right" style="text-align: right; vertical-align: top;"></td></tr>
                    <?php }else{?>
              <tr><td colspan="3"></td></td>
            <?php }?>
          </table>
        </td>
      </tr>
    </table>
    <table class="table table-bordereds" border=0>
      <tr>
	      <td width="65%" style='font-size:12pt;<?= in_array($id_dealer, $skip_dealer) ? 'color:white;' : '' ?>'><b>KUITANSI</b><br>&nbsp;</td>
        <td>
          <table class='table' style='font-size:9pt;' border="0">
	          <tr>
             	<td style="padding-right:50px;">Tgl. Pembelian</td>
        	    <td width="2%" style="vertical-align:top">:</td>
              <td style="padding-left:5px;"><?= date_dmy($row->tgl_spk) ?></td>
            </tr>
            <tr>
              	<td>Tgl. Pembayaran</td>
        	      <td width="2%" style="vertical-align:top">:</td>
              	<td style="padding-left:5px;"><?= date_dmy($row->tgl_pembayaran) ?></td>
            </tr>
            <tr>
              	<td>No. SPK</td>
        	      <td width="2%" style="vertical-align:top">:</td>
              	<td style="padding-left:5px;"><?= $row->no_spk ?></td>
            </tr>
            <tr>
              	<td>Nama Sales People</td>
        	      <td width="2%" style="vertical-align:top">:</td>
              	<td style="padding-left:5px;"><?= $row->nama_lengkap ?></td>
            </tr>
	        </table>
        </td>
      </tr>
    </table>
    <br>
    <table class="table table-bordereds" border ="0">
      <tr>
        <td width="18%">No. Kwitansi</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td colspan=4><?= $row->id_kwitansi ?></td>
      </tr>
      <tr>
        <td width="20%">Terima Dari</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td><?= $row->nama_konsumen ?></td>
      </tr>
      <tr>
        <td>No KTP</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td><?= $row->no_ktp ?></td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td style="vertical-align: top">:</td>
        <td><?= $row->alamat ?></td>
      </tr>
      <tr>
        <td>Uang Sejumlah</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td colspan="5">Rp. <?= mata_uang_rp($row->amount) ?></td>
      </tr>
      <tr>
        <td>Terbilang</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td colspan="5"><?= ucwords(to_word($row->amount)) ?> rupiah</td>
      </tr>
          <?php
            $ju = $dt_no_mesin->num_rows();
            $n_per_page = 55; // ganti tag table utk per halaman
            $i=0;

            if($ju >= $n_per_page){
              $ju = $n_per_page;
            }else{
              $ju  = $ju;
            }

            $a = 0;
            foreach ($dt_no_mesin->result() as $nosin) { 
              $i++;
              
              if($a==0){
                $n_per_page = 38;
              }else{
                $n_per_page = 55;
              }

              if($i<=$n_per_page || $i==1){
                if($i==1){
                  echo "
                      <tr>
                        <td width='21%' style='vertical-align:top'>Tipe Motor</td>
                        <td width='2%' style='vertical-align:top'>:</td>
                        <td colspan='4'>
                  ";
                }
            ?>
              <?= $nosin->tipe_ahm  ?> <br>
            <?php 
              }
          
              if($i==$n_per_page){
                $a++;
                $i=0;
                  echo "
                        </td>
                      </tr>
                    </table>
                    <table class='table table-bordereds'>
                  ";
              }
            }

            // $i=0;
            $a=0;
            foreach ($dt_no_mesin->result() as $nosin) { 
              $i++;

              if($i<=$n_per_page || $i==1){
                if($i==1 || $a==0){
                  echo "
                    <tr>
                      <td width='21%' style='vertical-align:top'>No. Mesin/No. Rangka</td>
                      <td width='2%' style='vertical-align:top'>:</td>
                      <td colspan=4>
                  ";
                  $a++;
                }
            ?>
            <?= $nosin->no_mesin . ' / ' . $nosin->no_rangka ?> <br>
            <?php 
              }
          
              if($i==$n_per_page){
                $i=0;
                $a=0;
                  echo "
                        </td>
                      </tr>
                    </table>
                    <table class='table table-bordereds'>
                  ";
              }
            }
          /*
          ?>
      <tr>
        <td width="21%" style="vertical-align:top">No. Mesin/No. Rangka</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td colspan=4>
          <?php 
            $n_per_page = 57; // ganti tag table
            $i=0;
            
            foreach ($dt_no_mesin->result() as $nosin) { 
              $i++;
              if($i<=$n_per_page){
            ?>
            <?= $nosin->no_mesin . ' / ' . $nosin->no_rangka ?> <br>

          <?php }
            } ?>
        </td>
      </tr>
      <?php */ 
      ?>
      <tr>
        <td>Sisa Hutang</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td colspan="5">Rp. <?= mata_uang_rp($lunas->sisa_pelunasan) ?></td>
      </tr>
      <tr>
        <td>Keterangan</td>
        <td width="2%" style="vertical-align:top">:</td>
        <td colspan="5"><?= $row->note ?></td>
      </tr>
    </table>
    <p style='<?= in_array($id_dealer, $skip_dealer) ? 'color:white;' : '' ?>'>Cara Pembayaran :</p>
    <table class="table table-borderedx">
      <?php if (!in_array($id_dealer, $skip_dealer)) { ?>
        <tr>
          <td style="border-bottom: 1px solid black;width: 20%">Keterangan</td>
          <td style="border-bottom: 1px solid black;width: 15%">Nominal</td>
          <td style="border-bottom: 1px solid black;width: 10%">Tanggal</td>
          <td style="border-bottom: 1px solid black;width: 25%">No. BG / Cek</td>
          <td style="border-bottom: 1px solid black;width: 25%">Nama Bank</td>
        </tr>
      <?php } else { ?>

        <tr>
          <td style="color:white;width: 20%">Keterangan</td>
          <td style="color:white;width: 15%">Nominal</td>
          <td style="color:white;width: 10%">Tanggal</td>
          <td style="color:white;width: 25%">No. BG / Cek</td>
          <td style="color:white;width: 25%">Nama Bank</td>
        </tr>
      <?php } ?>
      <?php
      foreach ($dt_bayar as $dt) {
        if ($dt->metode_penerimaan == 'cash') $metode = 'Cash';
        if ($dt->metode_penerimaan == 'bg_cek') $metode = 'BG / Cek';
        if ($dt->metode_penerimaan == 'kredit_transfer') $metode = 'Kredit/Transfer';
      ?>
        <tr>
          <td><?= $metode ?></td>
          <td align="right"><?= mata_uang_rp($dt->nominal) ?>&nbsp;&nbsp;&nbsp;</td>
          <td><?= date_dmy($dt->tgl_terima) ?></td>
          <td><?= $dt->no_bg_cek ?></td>
          <td><?= $dt->bank ?></td>
        </tr>
      <?php } ?>
    </table>
    <br><br>
    <?php if (!in_array($id_dealer, $skip_dealer)) { ?>

      <table class="table">
        <tr>
          <td width="60%" style='vertical-align:top'>
            <table style='font-size:8pt;text-align:justify;border:0.6px solid black'>
              <tr>
                <td>-</td>
                <td>Pembayaran dengan Bilyet Giro / Cek dianggap sah bila telah diuangkan.</td>
              </tr>
              <tr>
                <td style='vertical-align:top'>-</td>
                <td>Jika dalam batas waktu 1 (Satu) minggu dari tanggal pengeluaran tanda terima ini tidak ada keberatan yang disampaikan mengenai pembayaran untuk data - data tercetak, maka transaksi ini kami anggap telah disetujui</td>
              </tr>
            </table>
          </td>
          <td width='20%'></td>
          <td align="center">Jambi, <?= date_dmy($row->tgl_pembayaran) ?><br>
            Penerima,
            <br><br><br><br>
            ( Kepala Kasir )
          </td>
        </tr>
      </table>
    <?php } ?>

  <?php } ?>

</body>

</html>