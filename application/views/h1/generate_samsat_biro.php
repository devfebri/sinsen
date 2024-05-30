<html>
    <head>
        <title>Generate File Samsat</title>
            <style>
              @media print {
                @page {
                  sheet-size: 330mm 210mm;
                  margin-left: 0.8cm;
                  margin-right: 0.8cm;
                  margin-bottom: 1cm;
                  margin-top: 1cm;
                }
        
                .text-center {
                  text-align: center;
                }
        
                .bold {
                  font-weight: bold;
                }
        
                .table {
                  width: 100%;
                  max-width: 100%;
                  border-collapse: collapse;
                  /*border-collapse: separate;*/
                }
        
                .table-bordered tr td {
                  border: 0.01em solid black;
                  padding-left: 5px;
                  padding-right: 5px;
                }
		        
                body {
                  font-family: "Arial";
                  font-size: 10pt;
                }
              }
        </style>
        <style> .str{ mso-number-format:\@; } </style>
    </head>
<body>
  
<?php 
error_reporting(0);
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=File Generate BBN Biro.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>


<table class='table table-bordered' border='0' width='100%'>
<tr class='text-center'> <td colspan = '6'  style="text-align:center;font-weight:bold;font-size:20px;"> SURAT PENGANTAR BIRO JASA</td></tr>
<tr></tr>
<tr><td colspan='2'>Kepada Yth:</td></tr>
<tr><td colspan='2' style='font-weight:bold'>CV. KARYA MANDIRI</td></tr>
<tr><td colspan='3'>KOMP. RUKO GAJAH MADA NO.03 JELUTUNG</td></tr>
<tr><td colspan='2'>TELP. 0741-7555534</td></tr>
<tr><td> </td></tr>

</table>
<table>
  <tr>
  	<td colspan='2'  style='font-weight:bold'>TANGGAL MOHON SAMSAT: </td>
	<td><?php echo $tgl_mohon_samsat;?></td>
  </tr>
</table>
<table class="table table-bordered" border=1>
  <tr style='font-weight:bold'>                
    <td class='bold' style='text-align:center;'>No</td>   
    <td class='bold'>Nama Dealer</td>  
    <td class='bold'>No Mesin</td>
    <td class='bold'>No Rangka</td>              
    <td class='bold'>Nama Customer</td>  
    <td class='bold'>No STCK</td>  
  </tr>          
  <?php   
	if($get_data!=''){
$i=1;
  foreach ($get_data->result() as $isi) {
    echo "
    <tr>
      <td style='text-align:center;'>$i</td>
      <td>$isi->nama_dealer</td>
      <td>$isi->no_mesin</td>
      <td>$isi->no_rangka</td>
      <td>$isi->nama_konsumen</td>
      <td></td>
    </tr>
   
    ";
    $i++;
  }
}
echo " </table>";

echo "<table><tr><td></td></tr><tr><td></td></tr>
	<tr><td colspan='1'></td><td>Yang Menyerahkan</td><td></td><td></td><td>Yang Menerima</td></tr>
	<tr><td><td></tr><tr><td><td></tr><tr><td><td></tr>
	<tr><td colspan='1'></td><td>_________________</td><td></td><td></td><td>_________________<//td></tr>
</tr>
</table>
";
  
  ?>    
       
</body>
</html>
