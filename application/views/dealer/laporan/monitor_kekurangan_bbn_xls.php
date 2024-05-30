<?php 
error_reporting(0);
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=File Monitor Kekurangan BBN.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table class='table table-bordered' style='font-size: 9pt' width='100%'>
  <tr>                
    <td bgcolor='yellow' class='bold text-center' width='25%'>No Mesin</td>
    <td bgcolor='yellow' class='bold text-center' width='25%'>No Rangka</td>            
    <td bgcolor='yellow' class='bold text-center' width='25%'>Item Kendaraan</td>            
    <td bgcolor='yellow' class='bold text-center' width='25%'>Nama Customer</td>  
    <td bgcolor='yellow' class='bold text-center' width='25%'>No BASTD (D - MD)</td>            
    <td bgcolor='yellow' class='bold text-center' width='25%'>Created BASTD (D - MD)</td>            
    <td bgcolor='yellow' class='bold text-center' width='25%'>Tanggal SSU</td>
    <td bgcolor='yellow' class='bold text-center' width='25%'>Jam SSU</td>            
    <td bgcolor='yellow' class='bold text-center' width='25%'>Tanggal Hari ini</td>            
    <td bgcolor='yellow' class='bold text-center' width='25%'>GAP</td>            
  </tr>          
  <?php   /*
  $sql = $this->db->query("SELECT * FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
    INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
    WHERE tr_sales_order.id_dealer = '$id_dealer'
    AND tr_sales_order.no_mesin NOT IN (SELECT no_mesin FROM tr_pengajuan_bbn_detail)");
*/
    $sql = $this->db->query("
    SELECT tr_sales_order.no_mesin, tr_sales_order.tgl_create_ssu, tr_sales_order.id_dealer, tr_sales_order.no_rangka, tr_scan_barcode.id_item, tr_spk.nama_konsumen, tr_faktur_stnk.no_bastd, tr_faktur_stnk.created_at
    FROM tr_sales_order 
    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
    INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
    left join tr_faktur_stnk_detail on tr_sales_order.id_sales_order = tr_faktur_stnk_detail.id_sales_order 
    left join tr_faktur_stnk on tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd 
    where tr_sales_order.id_dealer = '$id_dealer'
    AND (tr_faktur_stnk_detail.no_bastd is null or tr_faktur_stnk.status_faktur not in ('approved','rejected'))
  ");

  foreach ($sql->result() as $isi) {

    /*
    $this->db->select('a.no_bastd, a.created_at');
    $this->db->from('tr_faktur_stnk a');
    $this->db->join('tr_faktur_stnk_detail b', 'a.no_bastd = b.no_bastd', 'inner');
    $this->db->where('b.id_sales_order', $isi->id_sales_order);
    $bastd = $this->db->get()->row();
*/
    $tgl_ssu = substr($isi->tgl_create_ssu,0,10);
    $jam_ssu = substr($isi->tgl_create_ssu,11);
    $tgl_today = date('Y-m-d');
    $tgl1 = new DateTime($tgl_ssu);
    $tgl2 = new DateTime($tgl_today);
    $gap = $tgl2->diff($tgl1)->days;

    echo "
    <tr>
      <td>$isi->no_mesin</td>
      <td>$isi->no_rangka</td>
      <td>$isi->id_item</td>
      <td>$isi->nama_konsumen</td>
      <td>$isi->no_bastd</td>
      <td>$isi->created_at</td>
      <td>$tgl_ssu</td>
      <td>$jam_ssu</td>
      <td>$tgl_today</td>
      <td>$gap</td>
    </tr>
    ";
  }
  
  ?>       
  <?php   
  /*
  $sql2 = $this->db->query("SELECT * FROM tr_sales_order_gc_nosin INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin 
    INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
    INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
    WHERE tr_sales_order_gc.id_dealer = '$id_dealer'
    AND tr_sales_order_gc_nosin.no_mesin NOT IN (SELECT no_mesin FROM tr_pengajuan_bbn_detail)");
  */

  $sql2 = $this->db->query("
  SELECT tr_scan_barcode.no_mesin, tr_sales_order_gc.tgl_create_ssu, tr_sales_order_gc.id_dealer, tr_scan_barcode.no_rangka, tr_scan_barcode.id_item, tr_spk_gc.nama_npwp, tr_faktur_stnk.no_bastd, tr_faktur_stnk.created_at
  FROM tr_sales_order_gc_nosin 
  INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin 
  INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
  INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
  left join tr_faktur_stnk_detail on tr_sales_order_gc.id_sales_order_gc  = tr_faktur_stnk_detail.id_sales_order 
  left join tr_faktur_stnk on tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd 
  where tr_sales_order_gc.id_dealer = '$id_dealer'
  AND tr_sales_order_gc.tgl_create_ssu >= '$tgl_thn' and (tr_faktur_stnk_detail.no_bastd is null or tr_faktur_stnk.status_faktur not in ('approved','rejected'))
  ");

  foreach ($sql2->result() as $isi) {
    /*
    $this->db->select('a.no_bastd, a.created_at');
    $this->db->from('tr_faktur_stnk a');
    $this->db->join('tr_faktur_stnk_detail b', 'a.no_bastd = b.no_bastd', 'inner');
    $this->db->where('b.id_sales_order', $isi->id_sales_order_gc);
    $bastd = $this->db->get()->row();
    */
    $tgl_ssu = substr($isi->tgl_create_ssu,0,10);
    $jam_ssu = substr($isi->tgl_create_ssu,11);
    $tgl_today = date('Y-m-d');
    $tgl1 = new DateTime($tgl_ssu);
    $tgl2 = new DateTime($tgl_today);
    $gap = $tgl2->diff($tgl1)->days;

    echo "
    <tr>
      <td>$isi->no_mesin</td>
      <td>$isi->no_rangka</td>
      <td>$isi->id_item</td>
      <td>$isi->nama_npwp</td>
      <td>$isi->no_bastd</td>
      <td>$isi->created_at</td>
      <td>$tgl_ssu</td>
      <td>$jam_ssu</td>
      <td>$tgl_today</td>
      <td>$gap</td>
    </tr>
    ";
  }
  
  ?>   
</table>