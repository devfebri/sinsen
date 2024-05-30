<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
  <?php 
    function mata_uang($a){
    if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
    if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
      return number_format($a, 0, ',', '.');
    } ?>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Cetak</title>
  <style>   
    @media print {
      @page {
        sheet-size: 210mm 297mm;
        margin-left: 1cm;
        margin-right: 1cm;
        margin-bottom: 1cm;
        margin-top: 1cm;
      }
      .kertas {page-break-after: always;}
      .kertas2 {page-break-before: always;}
      .text-center{text-align: center;}
      .table {
          width: 100%;
          max-width: 100%;
          border-collapse: collapse;
           /*border-collapse: separate;*/
        }
      .table-bordered tr td {
          border: 0px solid black;
          padding-left: 6px;
          padding-right: 6px;
        }
      body{
        font-family: "Arial";
        font-size: 8pt;
      }
    }
  </style>
</head>

<body>
<h4>
  <center>
    Tanda Bukti Pengambilan Kekurangan KSU <br>
  </center>
</h4>
Tanggal Pelunasan <br>
<?php 
$cek = $this->m_admin->getByID("tr_surat_jalan_ksu_pl","no_surat_jalan",$no_surat_jalan);
$no_sj = ($cek->num_rows() > 0) ? $cek->row()->no_sj_outstanding_ksu : "" ;
?>
No Surat Jalan : <?php echo $no_sj ?> <br>
Berdasarkan Kekurangan KSU dari No Surat Jalan: <?php echo $no_surat_jalan ?> <br>
<table border="1" width="80%">
	<tr>
		<th>No</th>
		<th>Kode KSU</th>
		<th>Part KSU</th>
		<th>Qty</th>
		<th>Nama Dealer</th>
	</tr>
	<?php 
	$no=1;
	$sql = $this->db->query("SELECT * FROM tr_surat_jalan_ksu_pl 
	  LEFT JOIN tr_mon_ksu ON tr_surat_jalan_ksu_pl.no_pl_ksu = tr_mon_ksu.no_pl_ksu
	  LEFT JOIN tr_mon_ksu_detail ON tr_mon_ksu.no_pl_ksu = tr_mon_ksu_detail.no_pl_ksu
	  LEFT JOIN ms_ksu ON tr_mon_ksu_detail.id_ksu = ms_ksu.id_ksu
	  LEFT JOIN tr_surat_jalan ON tr_surat_jalan_ksu_pl.no_surat_jalan = tr_surat_jalan.no_surat_jalan
	  LEFT JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
	  WHERE tr_surat_jalan_ksu_pl.no_surat_jalan = '$no_surat_jalan'");
	foreach ($sql->result() as $isi) {
		echo "
		<tr>
			<td>$no</td>
			<td>$isi->id_ksu</td>
			<td>$isi->ksu</td>
			<td>$isi->qty_konfirmasi</td>
			<td>$isi->nama_dealer</td>
		</tr>
		";
		$no++;
	}	
	?>
</table>
<br><br>
<table border="1" width="30%">
	<tr>
		<th width="50%">Gudang KSU</th>
		<th width="50%">KSU</th>
	</tr>
	<tr>
		<td>
			<br><br><br>
			Nama:
		</td>
		<td>
			<br><br><br>
			Nama:
		</td>
	</tr>
</table>
</body>
</html>