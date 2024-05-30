<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting H23 Data UE per Segment dan Tipe Motor Main Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption>Reporting H23 Data UE per per Segment dan Tipe Motor Main Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</caption>
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" style="width:380px" rowspan="2"><b>AHASS</b></td>
 		<td align="center" colspan="4"><b>Segment</b></td>
         <td align="center" colspan="57"><b>Type Series</b></td>
		<td align="center" rowspan="2"><b>Total UE</b></td>
 	</tr>
	 <tr> 		
 		<td align="center"><b>Matic</b></td>
 		<td align="center"><b>Cub</b></td>
 		<td align="center"><b>Sport</b></td>
    
 		<td align="center"><b>Vario 125</b></td>
 		<td align="center"><b>Vario 110</b></td>
        <td align="center"><b>Vario 150</b></td>
 		<td align="center"><b>Vario 160</b></td>
 		<td align="center"><b>Scoopy</b></td>

         <td align="center"><b>Genio</b></td>
 		<td align="center"><b>ADV150</b></td>
        <td align="center"><b>ADV160</b></td>
 		<td align="center"><b>ADV750</b></td>
 		<td align="center"><b>SUPRAX100</b></td>

         <td align="center"><b>SUPRAX125</b></td>
 		<td align="center"><b>SUPRA150</b></td>
        <td align="center"><b>SUPRAFIT</b></td>
 		<td align="center"><b>BEAT</b></td>
 		<td align="center"><b>BEATPOP</b></td>

         <td align="center"><b>BEATSPORTY</b></td>
 		<td align="center"><b>BEATSTREET</b></td>
        <td align="center"><b>BLADE</b></td>
 		<td align="center"><b>CB150R</b></td>
 		<td align="center"><b>CB150VERZA</b></td>

         <td align="center"><b>CB150X</b></td>
 		<td align="center"><b>CB500F</b></td>
        <td align="center"><b>CB500X</b></td>
 		<td align="center"><b>CB650</b></td>
 		<td align="center"><b>CBR1000R</b></td>

         <td align="center"><b>CBR150</b></td>
 		<td align="center"><b>CBR250</b></td>
        <td align="center"><b>CBR500R</b></td>
 		<td align="center"><b>CBR600R </b></td>
 		<td align="center"><b>CBR650</b></td>

         <td align="center"><b>CMX</b></td>
 		<td align="center"><b>CRF1000</b></td>
        <td align="center"><b>CRF1100</b></td>
 		<td align="center"><b>CRF150</b></td>
 		<td align="center"><b>CRF250</b></td>

        <td align="center"><b>CS1</b></td>
 		<td align="center"><b>CT125</b></td>
        <td align="center"><b>FORZA</b></td>
 		<td align="center"><b>GLMAX</b></td>
 		<td align="center"><b>GOLDWING</b></td>

         <td align="center"><b>KARISMA</b></td>
 		<td align="center"><b>KIRANA</b></td>
        <td align="center"><b>MEGAPRO</b></td>
 		<td align="center"><b>MONKEY</b></td>
 		<td align="center"><b>NM4</b></td>

        <td align="center"><b>OTHERS</b></td>
 		<td align="center"><b>PCX</b></td>
        <td align="center"><b>PHANTOM</b></td>
 		<td align="center"><b>REVO</b></td>
 		<td align="center"><b>SH150</b></td>

         <td align="center"><b>SONIC</b></td>
 		<td align="center"><b>SPACY</b></td>
        <td align="center"><b>SUPERCUBC125</b></td>
 		<td align="center"><b>TIGER</b></td>
 		<td align="center"><b>VARIO110</b></td>

        <td align="center"><b>VARIO125</b></td>
 		<td align="center"><b>VERZA</b></td>
        <td align="center"><b>WIN</b></td>
 		
 	</tr>

<?php 
 	$nom=1;	
    $sum_matic=0;
    $sum_cub=0;
    $sum_sport=0;
    $sum_vario=0;
    $sum_scoopy=0;
    $sum_genio=0;
    $sum_adv=0;
    $sum_big_bike=0;
    $sum_supra=0;
    $sum_total_ue=0;
	if($ue_segment2->num_rows()>0){
		foreach ($ue_segment2->result() as $row) {
        $total_matic = $row->matic;
        $total_cub= $row->cub;
        $total_sport = $row->sport;
        // $total_big_bike = $row->big_bike;
        $total_vario125= $row->vario125;
		$total_vario125= $row->vario150;
		$total_vario125= $row->vario110;
		$total_vario125= $row->vario160;
		$total_vario125= $row->scoopy;

		$total_vario125= $row->genio;
		$total_vario125= $row->adv150;
		$total_vario125= $row->adv160;
		$total_vario125= $row->adv750;
		$total_vario125= $row->suprax100;

		$total_vario125= $row->suprax125;
		$total_vario125= $row->supra150;
		$total_vario125= $row->suprafit;
		$total_vario125= $row->beat;
		$total_vario125= $row->beat_pop;

		$total_vario125= $row->beat_sporty;
		$total_vario125= $row->beat_street;
		$total_vario125= $row->blade;
		$total_vario125= $row->cb150r;
		$total_vario125= $row->cb150verza;

		$total_vario125= $row->cb150x;
		$total_vario125= $row->vario125;
		$total_vario125= $row->vario125;
		$total_vario125= $row->vario125;
		$total_vario125= $row->vario125;

		$total_vario125= $row->vario125;
		$total_vario125= $row->vario125;
		$total_vario125= $row->vario125;
		$total_vario125= $row->vario125;
		$total_vario125= $row->vario125;

		$total_vario125= $row->vario125;
		$total_vario125= $row->vario125;
		$total_vario125= $row->vario125;
		$total_vario125= $row->vario125;
		$total_vario125= $row->vario125;
        $total_scoopy = $row->scoopy;
        $total_genio= $row->genio;
        $total_adv= $row->adv;
        $total_supra= $row->supra;
        $total_ue=$row->matic+$row->cub+$row->sport+$row->big_bike+$row->vario+$row->scoopy+$row->genio+$row->adv+$row->supra;
 		echo "
 			<tr>
 				<td>$nom</td>
 				<td>$row->nama_dealer</td>
				<td>$row->matic</td>
 				<td>$row->cub</td>
 				<td>$row->sport</td>
         
 				<td>$row->vario125</td>
 				<td>$row->vario150</td>
                <td>$row->vario110</td>
 				<td>$row->vario160</td>
 				<td>$row->scoopy</td>

                 <td>$row->genio</td>
 				<td>$row->adv150</td>
                <td>$row->adv160</td>
 				<td>$row->adv750</td>
 				<td>$row->suprax100</td>

                 <td>$row->suprax125</td>
 				<td>$row->supra150</td>
                <td>$row->suprafit</td>
 				<td>$row->beat</td>
 				<td>$row->beat_pop</td>

                 <td>$row->beat_sporty</td>
 				<td>$row->beat_street</td>
                <td>$row->blade</td>
 				<td>$row->cb150r</td>
 				<td>$row->cb150verza</td>

                 <td>$row->cb150x</td>
 				<td>$row->cb500f</td>
                <td>$row->cb500x</td>
 				<td>$row->cb650</td>
 				<td>$row->cbr1000r</td>

                 <td>$row->cbr150</td>
 				<td>$row->cbr250</td>
                <td>$row->cbr500r</td>
 				<td>$row->cb600r</td>
 				<td>$row->cbr650</td>

                 <td>$row->cmx</td>
				 <td>$row->crf1000</td>
 				<td>$row->crf1100</td>
                <td>$row->crf150</td>
 				<td>$row->crf250</td>
 				<td>$row->cs1</td>

                 <td>$row->ct125</td>
 				<td>$row->forza</td>
                <td>$row->glmax</td>
 				<td>$row->goldwing</td>
 				<td>$row->karisma</td>

                 <td>$row->kirana</td>
 				<td>$row->megapro</td>
                <td>$row->monkey</td>
 				<td>$row->nm4</td>
 				<td>$row->others</td>

                 <td>$row->pcx</td>
 				<td>$row->phantom</td>
                <td>$row->revo</td>
 				<td>$row->sh150</td>
 				<td>$row->sonic</td>

                 <td>$row->spacy</td>
 				<td>$row->supercub</td>
                <td>$row->tiger</td>
 				<td>$row->vario110</td>
 				<td>$row->vario125</td>

                <td>$row->verza</td>
 				<td>$row->win</td>
               
				<td><b>-</b></td>

 			</tr>
	 	";
 		$nom++;
        $sum_matic    +=$total_matic;
        $sum_cub      +=$total_cub;
        $sum_sport    +=$total_sport;
        $sum_big_bike +=$total_big_bike;
        $sum_vario    +=$total_vario;
        $sum_scoopy   +=$total_scoopy;
        $sum_genio    +=$total_genio;
        $sum_adv      +=$total_adv;
        $sum_supra    +=$total_supra;
        $sum_total_ue +=$total_ue;
 		}

        // echo "
        //     <tr>
        //         <td style='text-align:center' colspan='2'><b>Total UE</b></td>
        //         <td><b>$sum_matic</b></td>
        //         <td><b>$sum_cub</b></td>
        //         <td><b>$sum_sport</b></td>
        //         <td><b>$sum_big_bike</b></td>
        //         <td><b>$sum_vario</b></td>
        //         <td><b>$sum_scoopy</b></td>
        //         <td><b>$sum_genio</b></td>
        //         <td><b>$sum_adv</b></td>
        //         <td><b>$sum_supra</b></td>
        //         <td><b>-</b></td>
        //     </tr>
        // ";
	}else{
		echo "<td colspan='12' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


