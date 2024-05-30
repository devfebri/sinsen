<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function sql_diskon_spk()
{
  return "CASE 
                WHEN spk.jenis_beli='Kredit' THEN IFNULL(spk.diskon,0)+IFNULL(spk.voucher_2,0)+IFNULL(spk.voucher_tambahan_2,0)
                ELSE IFNULL(spk.diskon,0)+IFNULL(spk.voucher_1,0)+IFNULL(spk.voucher_tambahan_1,0)
              END";
}
function sql_total_bayar_spk()
{

  $diskon = sql_diskon_spk();
  return "(spk.harga_tunai - $diskon)";
}

function sql_diskon_spk_gc_summary()
{
  return "SELECT SUM(IFNULL(nilai_voucher,0)+IFNULL(voucher_tambahan,0)) FROM tr_spk_gc_detail WHERE no_spk_gc=spk_gc.no_spk_gc";
}

function sql_total_bayar_spk_gc_summary()
{
  $diskon = "IFNULL(spk_gc_d.nilai_voucher,0)+IFNULL(spk_gc_d.voucher_tambahan,0)";
  return "SELECT SUM((spk_gc_k.total_unit-($diskon))*spk_gc_k.qty) 
          FROM tr_spk_gc_kendaraan spk_gc_k 
          JOIN tr_spk_gc_detail spk_gc_d ON spk_gc_d.no_spk_gc=spk_gc_k.no_spk_gc AND spk_gc_d.id_tipe_kendaraan=spk_gc_k.id_tipe_kendaraan AND spk_gc_d.id_warna=spk_gc_k.id_warna
          WHERE spk_gc_k.no_spk_gc=spk_gc.no_spk_gc";
}

function sql_summary_terima_pembayaran($jenis)
{
  if ($jenis == 'dp') {
    return "IFNULL((SELECT SUM(dp_r.amount) 
      FROM tr_h1_dealer_invoice_receipt dp_r 
      LEFT JOIN tr_invoice_tjs tjs ON tjs.id_invoice=dp_r.id_invoice
      LEFT JOIN tr_invoice_dp dp_sum ON dp_sum.id_invoice_dp=dp_r.id_invoice
      WHERE (tjs.id_spk=dp.id_spk OR dp_sum.id_spk=dp.id_spk) AND (tjs.status='close' OR 1=1)),0)";
  } else {
    return "IFNULL(
      (
        SELECT SUM(inv_r.amount) 
        FROM tr_h1_dealer_invoice_receipt inv_r 
        LEFT JOIN tr_invoice_tjs tjs ON tjs.id_invoice=inv_r.id_invoice
        LEFT JOIN tr_invoice_pelunasan lunas_sum ON lunas_sum.id_inv_pelunasan=inv_r.id_invoice
        WHERE 
          (tjs.id_spk=lunas.id_spk OR lunas_sum.id_spk=lunas.id_spk) AND (tjs.status='close' OR 1=1))
      ,0)";
  }
}

function sql_summary_terima_pembayaran_v2($jenis, $id_dealer= null)
{
  $where  = '';

  if ($jenis == 'dp') {
    if($id_dealer!=''){
      $where =" and dp_r.id_dealer = '$id_dealer'";
    }

    return "IFNULL((SELECT SUM(dp_r.amount) 
      FROM tr_h1_dealer_invoice_receipt dp_r 
      LEFT JOIN tr_invoice_tjs tjs ON tjs.id_invoice=dp_r.id_invoice
      LEFT JOIN tr_invoice_dp dp_sum ON dp_sum.id_invoice_dp=dp_r.id_invoice
      WHERE (tjs.id_spk=dp.id_spk OR dp_sum.id_spk=dp.id_spk) $where AND (tjs.status='close' OR 1=1)),0) ";
  } else {
      
    if($id_dealer!=''){
      $where =" and inv_r.id_dealer = '$id_dealer'";
    }
    
    return "IFNULL(
      (
        SELECT SUM(inv_r.amount) 
        FROM tr_h1_dealer_invoice_receipt inv_r 
        LEFT JOIN tr_invoice_tjs tjs ON tjs.id_invoice=inv_r.id_invoice
        LEFT JOIN tr_invoice_pelunasan lunas_sum ON lunas_sum.id_inv_pelunasan=inv_r.id_invoice
        WHERE (tjs.id_spk=lunas.id_spk OR lunas_sum.id_spk=lunas.id_spk) $where AND (tjs.status='close' OR 1=1))
      ,0)";
  }
}