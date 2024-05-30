<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 30px;  
  padding-left: 5px;
  padding-right: 5px;   
  margin-right: 0px; 
}
.isi_combo{   
  height: 30px;
  border:1px solid #ccc;
  padding-left:1.5px;
}
</style>
<div align="center"><h2>Surat Pengantar Hutang KSU</h2></div>
<table>
  <tr>
    <td>No Surat</td>
    <td>: <?php echo $row->no_surat_pengantar ?></td>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td>Tgl Surat</td>
    <td>: <?php echo $row->tgl_cetak ?></td>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td width="25%">Nama Konsumen</td>
    <td width="40%">: <?php echo $row->nama_konsumen ?></td>
    <td width="20%">Alamat</td>
    <td width="25%">: <?php echo $row->alamat ?></td>
  </tr>
  <tr>
    <td>No SO</td>
    <td>: <?php echo $row->id_sales_order ?></td>
    <td>No Mesin</td>
    <td>: <?php echo $row->no_mesin ?></td>
  </tr>
  <tr>
    <td>Tgl SO</td>
    <td>: <?php echo $row->tgl_cetak_invoice ?></td>
    <td>No Rangka</td>
    <td>: <?php echo $row->no_rangka ?></td>
  </tr>  
</table>
<table width="90%" class="myTable1" class="table table-bordered table-hover">  
  <thead>
    <tr>
      <th colspan="3">Detail KSU</th>
    </tr>
    <tr bgcolor="red">              
      <th width="5%">No</th>                          
      <th>Kode KSU</th>
      <th>Nama KSU</th>      
    </tr>
  </thead>
  <tbody>
   <?php 
   $no=1;
   $sql = $this->db->query("SELECT * FROM tr_pemenuhan_hutang_detail LEFT JOIN ms_ksu ON tr_pemenuhan_hutang_detail.id_ksu = ms_ksu.id_ksu
      WHERE tr_pemenuhan_hutang_detail.no_surat_pengantar = '$row->no_surat_pengantar'");
   foreach ($sql->result() as $isi) {
     echo "
      <tr>
        <td>$no</td>
        <td>$isi->id_ksu</td>
        <td>$isi->ksu</td>
      </tr>
     ";
     $no++;
   }
   ?>
  </tbody>
</table>
<br><br>
<table width="100%">
  <tr align="center">
    <td width="30%">Dibuat Oleh</td>
    <td width="30%">Dikeluarkan Oleh</td>
    <td width="30%">Diterima Oleh</td>
  </tr>
  <tr>
    <td><br><br><br><br></td>
  </tr>
  <tr align="center">
    <td>(____________)</td>
    <td>(____________)</td>
    <td>(____________)</td>
  </tr>
</table>