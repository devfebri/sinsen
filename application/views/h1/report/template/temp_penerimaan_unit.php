<?php 
$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=penerimaan_unit_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">No SIPB</td>
 		<td align="center">Tgl SIPB</td>
 		<td align="center">No SL</td>
 		<td align="center">Tgl SL</td>
 		<td align="center">Ekspedisi</td>
 		<td align="center">No Polisi</td>
 		<td align="center">Kode Tipe</td>
 		<td align="center">Kode Item Kendaraan</td>
 		<td align="center">Nama Item Kendaraan</td>
 		<td align="center">No Mesin</td>
 		<td align="center">No Rangka</td>
 		<td align="center">Tahun Produksi</td>
 		<td align="center">No Penerimaan</td>
 		<td align="center">Tgl Penerimaan</td>
 		<td align="center">Lokasi</td>
 		<td align="center">Slot</td>
 	</tr>
 	<?php 
 	$no=1;
 	
 	foreach ($sql->result() as $row) {
 	 //    $sl = $this->m_admin->getByID("tr_shipping_list","no_shipping_list",$row->no_shipping_list)->row();
 		// $bulan = substr($sl->tgl_sl, 2,2);
   //      $tahun = substr($sl->tgl_sl, 4,4);
   //      $tgl = substr($sl->tgl_sl, 0,2);
   //      $tanggal_sl = $tgl."-".$bulan."-".$tahun;
        
   //      $sipb = $this->m_admin->getByID("tr_sipb","no_sipb",$sl->no_sipb);
   //      if($sipb->num_rows() > 0){        
   //          $bulan_s = substr($sipb->row()->tgl_sipb, 2,2);
   //          $tahun_s = substr($sipb->row()->tgl_sipb, 4,4);
   //          $tgl_s = substr($sipb->row()->tgl_sipb, 0,2);
   //          $tanggal_sipb = $tgl_s."-".$bulan_s."-".$tahun_s;
   //      }else{
   //          $tanggal_sipb = "";
   //      }
        
   //      $vendor_name = $this->m_admin->getByID("ms_vendor","id_vendor",$row->ekspedisi)->row()->vendor_name;
   //      $tipe = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$row->tipe_motor)->row();
   //      $tahun_produksi = $this->m_admin->getByID("tr_fkb","no_mesin_spasi",$row->no_mesin)->row()->tahun_produksi;
   //      $tp = (isset($tahun_produksi)) ? $tahun_produksi : "" ;



 		echo "
 			<tr>
 				<td>$no</td>
 				<td>$row->no_sipb</td>
 				<td>$row->tgl_sipb</td>
 				<td>$row->no_shipping_list</td>
 				<td style='mso-number-format:\@;'>".substr($row->tgl_sl, 0,2)."-".substr($row->tgl_sl, 2,2)."-".substr($row->tgl_sl, 4,4)."</td>
 				<td>$row->vendor_name</td>
 				<td>$row->no_polisi</td>
 				<td>$row->tipe_motor</td>
 				<td>$row->id_item</td>
 				<td>$row->tipe_ahm</td>
 				<td>$row->no_mesin</td>
 				<td>$row->no_rangka</td>
 				<td>$row->tahun_produksi</td>
 				<td>$row->id_penerimaan_unit</td>
 				<td>$row->tgl_penerimaan</td>
 				<td>$row->lokasi</td>
 				<td>$row->slot</td>
 			</tr>
 		";

        // $no_sipb = get_data('tr_shipping_list','no_shipping_list',$row->no_shipping_list,'no_sipb');
        // $tgl_sl = get_data('tr_shipping_list','no_shipping_list',$row->no_shipping_list,'tgl_sl');
        // $tgl_sipb = get_data('tr_sipb','no_sipb',$no_sipb,'tgl_sipb');
        // $tgl_sipb = substr($tgl_sipb, 0,2)."-".substr($tgl_sipb, 2,2)."-".substr($tgl_sipb, 4,4);
        // $tgl_sl = substr($tgl_sl, 0,2)."-".substr($tgl_sl, 2,2)."-".substr($tgl_sl, 4,4);
        // $vendor_name = get_data('ms_vendor','id_vendor',$row->ekspedisi,'vendor_name');



        // echo "
        //     <tr>
        //         <td>$no</td>
        //         <td>$no_sipb</td>
        //         <td>$tgl_sipb</td>
                
        //         <td>$row->no_shipping_list</td>
        //         <td>$tgl_sl</td>
        //         <td>$vendor_name</td>
        //         <td>$row->no_polisi</td>
        //         <td>$row->id_item</td>
        //         <td>$row->no_mesin</td>
        //         <td>$row->no_rangka</td>
        //         <td>$row->id_penerimaan_unit</td>
        //         <td>$row->tgl_penerimaan</td>
        //         <td>$row->lokasi</td>
        //         <td>$row->slot</td>
        //     </tr>
        // ";
 		$no++;
 	}
 	?>
</table>


