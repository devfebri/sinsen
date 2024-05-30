<?php 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");

$cek = $this->m_admin->getByID("tr_niguri","id_niguri",$id)->row();
$bulan = number_format($cek->bulan);
$a1 = $bulan - 2;
$a2 = $bulan - 1;
$a3 = $bulan;
$a4 = $bulan + 1;
$a5 = $bulan + 2;

if($a1 == "-1"){
  $a1 = "11";
}elseif($a1 == "0"){
  $a1 = "12";
}
if($a2 == "0"){
  $a2 = "12";
}
if($a5 == "14"){
  $a5 = "2";
}elseif($a5 == "13"){
  $a5 = "1";
}
if($a4 == "13"){
  $a4 = "1";
}
?>
<table border="1" width="80%" align="left">
	<thead>
		<tr>
			<th align="center"><b>Kode Item</b></th>
			<th align="center"><b>Tipe</b></th>
			<th align="center"><b>Warna</b></th>
			<th align="center"><b>Jenis</b></th>
			<th align="center"><b>M-1 [<?php echo $a1 ?>]</b></th>
			<th align="center"><b>M [<?php echo $a2 ?>]</b></th>
			<th align="center"><b><font color='red'>Fix [<?php echo $a3 ?>]</font></b></th>
			<th align="center"><b>T1 [<?php echo $a4 ?>]</b></th>
			<th align="center"><b>T2 [<?php echo $a5 ?>]</b></th>
		</tr>	
<?php
$sql = $this->db->query("SELECT * FROM tr_niguri_detail INNER JOIN ms_item ON tr_niguri_detail.id_item = ms_item.id_item
						INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan						
						INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
						WHERE tr_niguri_detail.id_niguri = '$id'");
$mo = date("m");
$ye = date("Y");
foreach ($sql->result() as $isi) {
	echo "
		<tr>
			<td rowspan='4'>$isi->id_item</td>
			<td rowspan='4'>$isi->tipe_ahm</td>
			<td rowspan='4'>$isi->warna</td>
			<td>AHM Dist to MD</td>
			<td>$isi->a_m1</td>
			<td>$isi->a_m</td>
			<td>$isi->a_fix</td>
			<td>$isi->a_t1</td>
			<td>$isi->a_t2</td>
		</tr>
		<tr>			
			<td>Retail Sales</td>
			<td>$isi->b_m1</td>
			<td>$isi->b_m</td>
			<td>$isi->b_fix</td>
			<td>$isi->b_t1</td>
			<td>$isi->b_t2</td>
		</tr>
		<tr>			
			<td>Total Stock</td>
			<td>$isi->c_m1</td>
			<td>$isi->c_m</td>
			<td>$isi->c_fix</td>
			<td>$isi->c_t1</td>
			<td>$isi->c_t2</td>
		</tr>
		<tr>			
			<td>Stock Days</td>
			<td>$isi->d_m1</td>
			<td>$isi->d_m</td>
			<td>$isi->d_fix</td>
			<td>$isi->d_t1</td>
			<td>$isi->d_t2</td>
		</tr>
		";
}

?>     
<?php $total = $this->db->query("SELECT SUM(a_m1) AS jum_m1,SUM(a_m) AS jum_m,SUM(a_fix) AS jum_fix,SUM(a_t1) AS jum_t1,SUM(a_t2) AS jum_t2,
						SUM(b_m1) AS um_m1,SUM(b_m) AS um_m,SUM(b_fix) AS um_fix,SUM(b_t1) AS um_t1,SUM(b_t2) AS um_t2,
						SUM(c_m1) AS ju_m1,SUM(c_m) AS ju_m,SUM(c_fix) AS ju_fix,SUM(c_t1) AS ju_t1,SUM(c_t2) AS ju_t2,
						SUM(d_m1) AS j_m1,SUM(d_m) AS j_m,SUM(d_fix) AS j_fix,SUM(d_t1) AS j_t1,SUM(d_t2) AS j_t2 FROM tr_niguri_detail
						WHERE tr_niguri_detail.id_niguri = '$id'");
  $row = $total->row();
    echo "   
    <tr>     
      <td rowspan='4' colspan='3' width='17%' align='right'><b>Total</td>

      <td width='17.8%' align='right'><b>MD Dist to Dealer</td>
      <td width='7%'><b>$row->jum_m1</td>
      <td width='7%'><b>$row->jum_m</td>
      <td width='7%''><b>$row->jum_fix</td>
      <td width='7%'><b>$row->jum_t1</td>
      <td width='7%'><b>$row->jum_t2</td>
    </tr>
    <tr>
      <td width='17.8%' align='right'><b>Retail Sales</td>
      <td width='7%'><b>$row->um_m1</td>
      <td width='7%'><b>$row->um_m</td>
      <td width='7%''><b>$row->um_fix</td>
      <td width='7%'><b>$row->um_t1</td>
      <td width='7%'><b>$row->um_t2</td>
    </tr>
    <tr>
      <td width='17.8%' align='right'><b>Total Stock</td>
      <td width='7%'><b>$row->ju_m1</td>
      <td width='7%'><b>$row->ju_m</td>
      <td width='7%''><b>$row->ju_fix</td>
      <td width='7%'><b>$row->ju_t1</td>
      <td width='7%'><b>$row->ju_t2</td>
    </tr>
    <tr>
      <td width='17.8%' align='right'><b>Total Stock Days</td>
      <td width='7%'><b>$row->j_m1</td>
      <td width='7%'><b>$row->j_m</td>
      <td width='7%''><b>$row->j_fix</td>
      <td width='7%'><b>$row->j_t1</td>
      <td width='7%'><b>$row->j_t2</td>
    </tr>"; ?> 
	</thead>
</table>
