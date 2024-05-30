<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
    <?php 
        function mata_uang($a){
            return number_format($a, 0, ',', '.');
        } 
        // function get_kry($id_user)
        // {
        //   $get =  $this->db->query("SELECT * FROM ms_user JOIN ms_karyawan_dealer ON ms_karyawan_dealer.id_karyawan_dealer=ms_user.id_karyawan_dealer WHERE id_user=$id_user")->row()->nama_lengkap;
        //   return $get;
        // }
    ?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cetak</title>
    <style>
        @media print {
            @page {
                sheet-size: 210mm 297mm;
              /*  margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 1cm;
                margin-top: 1cm;*/
            }
            .text-center{text-align: center;}
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
            body{
                font-family: "Arial";
                font-size: 11pt;
            }
        }
    </style>
</head>

<body>

<?php 
if ($set=='list_unit_trf'){ ?>
 <table class="table table-borderedx">
    <tr>
      <td width="100%" align="center" colspan="5"><b>Cetak List Unit Transfer</b><br>&nbsp;</td>
    </tr>
    <tr>
      <td width="20%">ID Type</td><td>: <?= $row->id_mutasi ?></td>
      <td></td>
      <td width="20%">Gudang Asal</td><td>: <?= $row->asal_mutasi ?></td>
    </tr>
    <tr>
      <td width="20%">Tanggal</td><td>: <?= date('d/m/Y') ?></td>
      <td></td>
      <td width="20%">Gudang Tujuan</td><td>: <?= $row->tujuan_mutasi ?></td>
    </tr>
    <tr>
      <td colspan="3"></td>
      <?php $get_kry = $this->db->query("SELECT * FROM ms_user JOIN ms_karyawan_dealer ON ms_karyawan_dealer.id_karyawan_dealer=ms_user.id_karyawan_dealer WHERE id_user=$row->created_by")->row()->nama_lengkap; ?>
      <td width="20%">Creator</td><td>: <?= $get_kry ?></td>
    </tr>
  </table>
  <p style="text-align: center;font-weight: bold;">UNIT</p>
  <table class="table table-bordered">
    <tr>
      <td style="font-weight: bold;">Kode Item</td>
      <td style="font-weight: bold;">Tipe</td>
      <td style="font-weight: bold;">Warna</td>
      <td style="font-weight: bold;">No Mesin</td>
    </tr>
    <?php foreach ($details as $dtl): ?>
      <tr>
        <td><?= $dtl->id_item ?></td>
        <td><?= $dtl->tipe_ahm ?></td>
        <td><?= $dtl->warna ?></td>
        <td><?= $dtl->no_mesin ?></td>
      </tr>
    <?php endforeach ?>
  </table>
  <p style="text-align: center;font-weight: bold;">AKSESORIS</p>
  <table class="table table-bordered">
    <tr>
      <td style="font-weight: bold;">Nama Aksesoris</td>
      <td style="font-weight: bold;">Qty</td>
    </tr>
    <?php $aks = $this->db->query("SELECT *,(SELECT ksu FROM ms_ksu WHERE id_ksu=tr_mutasi_ksu_detail.id_ksu) AS ksu,COUNT(id_ksu) as qty
        FROM tr_mutasi_ksu_detail WHERE id_mutasi='$row->id_mutasi' AND cek='true' GROUP BY id_ksu"); ?>
<?php 
  foreach ($aks->result() as $rs) { ?>
    <tr>
      <td><?= $rs->ksu ?></td>
      <td align="center"><?= $rs->qty ?></td>
    </tr>
 <?php }
 ?>
  </table>
<?php } ?>

<?php if ($set=='print_sj'){ ?>
 <table class="table table-borderedd">
    <tr>
      <td colspan="6" align="center"><b>Surat Jalan</b></td>
    </tr>
    <tr>
      <td colspan="6">Nama Dealer</td>
    </tr>
    <tr>
      <td colspan="6" style="font-weight: bold"><?= $dealer ?></td>
    </tr>
    <tr>
      <td width="20%">No Surat Jalan</td><td width="2%">:</td><td><?= $row->no_sj ?></td>
      <td width="20%">Gudang Asal</td><td width="2%">:</td><td><?= $row->asal_mutasi ?></td>
    </tr>
    <tr>
      <td width="20%">Tanggal Keluar</td></td><td width="2%">:</td><td><?= mediumdate_indo($row->tgl_mutasi,'/') ?></td>
      <td>Event ID</td><td>:</td><td><?= $event->kode_event ?></td>
    </tr>
  </table>
  <p style="text-align: center;font-weight: bold;">UNIT</p>
  <table class="table table-bordered">
    <tr>
      <td style="font-weight: bold;">Kode Item</td>
      <td style="font-weight: bold;">Tipe</td>
      <td style="font-weight: bold;">Warna</td>
      <td style="font-weight: bold;">No Mesin</td>
    </tr>
    <?php foreach ($details as $dtl): ?>
      <tr>
        <td><?= $dtl->id_item ?></td>
        <td><?= $dtl->tipe_ahm ?></td>
        <td><?= $dtl->warna ?></td>
        <td><?= $dtl->no_mesin ?></td>
      </tr>
    <?php endforeach ?>
  </table>
  <p style="text-align: center;font-weight: bold;">AKSESORIS</p>
  <table class="table table-bordered">
    <tr>
      <td style="font-weight: bold;">Nama Aksesoris</td>
      <td style="font-weight: bold;">Qty</td>
    </tr>
    <?php $aks = $this->db->query("SELECT *,(SELECT ksu FROM ms_ksu WHERE id_ksu=tr_mutasi_ksu_detail.id_ksu) AS ksu,COUNT(id_ksu) as qty
        FROM tr_mutasi_ksu_detail WHERE id_mutasi='$row->id_mutasi' AND cek='true' GROUP BY id_ksu"); ?>
<?php 
  foreach ($aks->result() as $rs) { ?>
    <tr>
      <td><?= $rs->ksu ?></td>
      <td align="center"><?= $rs->qty ?></td>
    </tr>
 <?php }
 ?>
  </table>
  <p>Keterangan : <?= $row->keterangan ?></p><br><br>
  <table class="table">
    <tr>
      <td style="text-align: center;">Pengirim,
        <br><br><br><br><br>
        <?php $get_kry = $this->db->query("SELECT * FROM ms_user JOIN ms_karyawan_dealer ON ms_karyawan_dealer.id_karyawan_dealer=ms_user.id_karyawan_dealer WHERE id_user=$row->print_sj_by")->row()->nama_lengkap; ?>
          (<?= $get_kry ?>)
      </td>
      <td style="width: 30%"></td>
      <td style="text-align: center;">Penerima,<br><br><br><br><br>&nbsp;(________________________)</td>
    </tr>
  </table>
<?php } ?>
</body>
</html>
