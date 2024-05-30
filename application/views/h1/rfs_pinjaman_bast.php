<?php 
function mata_uang($a){      
	if(is_numeric($a) AND $a != 0 AND $a != ""){
		return number_format($a, 0, ',', '.');
	}else{
		return $a;
	}
}
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
          sheet-size: 210mm 297mm;                      
          margin-left: 1cm;
          margin-right: 1cm;
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
          transform: scale(2, 0.5);
          font-family: "Arial";
          font-size: 8pt;
        }
        
      }
    </style>
  </head>
<body>
	<table border="0" width="100%">
		<tr>
			<td colspan="4" align="center" valign="middle"><h3>INVOICE</h3></td>
		</tr>
		<tr>
			<td><font color='red'>TA KE GUEST</font></td>
		</tr>
		
		
		<tr>
			<td width="15%">Recepcionist</td>
			<td width="30%">: </td>
			

	
		
			<td valign="top" width="15%">Check Out</td>
			<td valign="top">: </td>
		</tr>
		<tr>
			<td> <br> <br> <br> </td>
		</tr>
		
		<tr>
			<td colspan="4">
				
			</td>
		</tr>
	</table>	
	<table width="100%" >
		<tr>
			<td width="70%"></i></td>		
			<td>Jambi, <?php echo gmdate(" d F Y", time()+60*60*7); ?></td>
		</tr>
		
		<tr>
			<td></td>
			<td>Receptionist</td>
		</tr>
		<tr>
			<td></td>
			<td> <br> <br> <br></td>
		</tr>
		<tr>
			<td></td>
			<td>____________________________</td>
		</tr>
	</table>
</body>