
<!DOCTYPE html>
<html>


<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Cetak</title>
	<style>
	      @media print {
            @page {
                sheet-size: 210mm 297mm;
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 0cm;
                margin-top: 0.5cm;
            }
    	    
	      }
	</style>
</head>

<body> 
		 
	<?php
	$i = 0;

echo '<table>
            <tr>';
    foreach($rak as $item){
    $data_part = $this->db->query("SELECT * FROM ms_h3_dealer_stock where id_dealer='$dealer' and id_rak='$item->id_rak'")->row();
    $desk = $this->db->query("SELECT nama_part from ms_part where id_part='$data_part->id_part'")->row();
    $i++;
    echo '<td>
    <table style="border: 1px solid black;width: 94.5mm;">
    <tr style="background-color:red;">
								<td align="center" style="color:white;font-size:8pt;font-weight:bold;">
								   <img src="assets/panel/images/logo.png" width="4%">
								   <span style="vertical-align:text-top;">'.$nama_dealer.'</span>
								</td>
								
	</tr>
     <tr>
								<td align="center">
									<barcode code="'.$item->id_rak.';'.$dealer.'" type="C128A" size="1.0" height="1.0" />
								</td>
								
	</tr>
	<tr>
	<td></td>
	</tr>
		<tr>
	   <td style="text-align:center;">
	    <table style="font-size: 18pt;line-height: 7pt;">
										<tr>
											<td style="text-align:center;">'.$data_part->id_part.'</td>
										</tr>
		</table>
	   </td>
	</tr>
	<tr>
	<td></td>
	</tr>
	<tr>
	   <td style="text-align:center;">
	    <table style="font-size: 15pt;line-height: 7pt;">
										<tr>
											<td style="text-align:center;">'.$desk->nama_part.'</td>
										</tr>
		</table>
	   </td>
	</tr>
	<tr>
	<td></td>
	</tr>
	<tr>
	   <td style="text-align:center;">
	    <table style="font-size: 28pt;line-height: 7pt;">
										<tr>
											<td style="text-align:center;">'.$item->id_rak.'</td>
										</tr>
		</table>
	   </td>
	</tr>
	
    '
  
    .'</table>
    </td>';

    if($i == 3) { 
        echo '</tr><tr>';
        $i = 0;
    }
    }
    echo '    </tr>
            </table>';
    	
	?>
		    
</body>



</html>

