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
    Laporan Subsidi Penjualan <br>
    Tanggal <?php echo $tgl1." s/d ".$tgl2 ?>
  </center>
</h4>
<?php 
$nama_dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer)->row()->nama_dealer;
$where = "";
if($id_dealer!=""){
  $where = "AND tr_do_po.id_dealer = '$id_dealer'";
}
?>
Nama Dealer : <?php echo $nama_dealer ?>
<br>
<?php 
$sql = $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
  LEFT JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
  LEFT JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
  WHERE tr_do_po_detail.disc_scp > 0 AND tr_do_po.tgl_do BETWEEN '$tgl1' AND '$tgl2'
  AND tr_do_po.status = 'approved'
  $where GROUP BY ms_item.id_tipe_kendaraan");
  foreach ($sql->result() as $isi) {
    $sql2 = $this->db->query("SELECT tr_invoice_dealer.no_faktur,tr_invoice_dealer.tgl_faktur,tr_do_po.no_do,ms_item.id_item,tr_invoice_dealer_detail.qty_do,
      tr_invoice_dealer_detail.potongan,ms_item.id_tipe_kendaraan FROM tr_invoice_dealer 
      INNER JOIN tr_invoice_dealer_detail ON tr_invoice_dealer.no_do = tr_invoice_dealer_detail.no_do
      INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do
      INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
      INNER JOIN ms_item ON LEFT(tr_invoice_dealer_detail.id_item,6) = ms_item.id_item
      WHERE LEFT(tr_invoice_dealer_detail.id_item,3) = '$isi->id_tipe_kendaraan' AND tr_do_po.tgl_do BETWEEN '$tgl1' AND '$tgl2'
      AND tr_do_po.id_dealer = '$isi->id_dealer' AND tr_invoice_dealer_detail.qty_do > 0 AND tr_do_po.status = 'approved' AND tr_do_po_detail.disc_scp > 0
      GROUP BY tr_do_po.no_do,tr_invoice_dealer_detail.id_item");
    if($sql2->num_rows() > 0){
      echo "Kode Tipe : $isi->id_tipe_kendaraan - $isi->tipe_ahm";
      echo "      
      <table width='100%' border='0'>
        <tr>
          <td width='20%'>No Faktur</td>
          <td width='10%'>Tgl Faktur</td>
          <td width='15%'>No DO</td>
          <td width='10%'>Item</td>
          <td align='right' width='5%'>Discount</td>
          <td align='right' width='5%'>Qty</td>
          <td align='right' width='10%'>Total</td>
        </tr>";   
      $g_sub = 0;
      foreach ($sql2->result() as $row) { 
        echo "
        <tr>
          <td>$row->no_faktur</td>
          <td>$row->tgl_faktur</td>
          <td>$row->no_do</td>
          <td>$row->id_item</td>
          <td align='right'>".mata_uang($isi->disc_scp)."</td>
          <td align='right'>$row->qty_do</td>
          <td align='right'>".mata_uang($sub = $row->qty_do * $isi->disc_scp)."</td>
        </tr>
        ";
        $g_sub += $sub;
      }
    echo "
      <tr>
        <td align='right' colspan='5'>Subtotal</td>
        <td></td>
        <td align='right'>".mata_uang($g_sub)."</td>
      </tr>
    </table>
    <br>";
    $g_total += $g_sub;
    }    
  }
  echo "
  <table width='100%' border='0'>
    <tr>
      <td align='right' width='85%'><b>Grand Total :</b></td>
      <td width='15%' align='right'><b>".mata_uang($g_total)."</b></td>
    </tr>
  </table>";

?>
</body>
</html>