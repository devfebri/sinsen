<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting H23 Data UE per Segment dan Tipe Motor Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption>Reporting H23 Data UE per per Segment dan Tipe Motor Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</caption>
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" style="width:380px" rowspan="2"><b>AHASS</b></td>
 		<td align="center" colspan="3"><b>Segment</b></td>
        <td align="center" colspan="<?php echo $ue_segment3->num_rows()?>"><b>Type Series</b></td>
		<td align="center" rowspan="2"><b>Total UE</b></td>
 	</tr>
    <tr>
        <td align="center"><b>Matic</b></td>
 		<td align="center"><b>Cub</b></td>
 		<td align="center"><b>Sport</b></td>
        <?php foreach($ue_segment3->result() as $row){?>
            <td><b><?php echo $row->id_series?></b></td>
        <?php }?>
    </tr>

<?php 
    $nom=1;
    $sum_matic = 0;
    $sum_cub   = 0;
    $sum_sport = 0;
    $sum_ue    = 0;
    $sum_total_all = 0;
    if($ue_segment4->num_rows()>0){
    foreach ($ue_segment4->result() as $row2){?>
        <?php 
            $total_matic = $row2->matic;
            $total_cub   = $row2->cub;
            $total_sport = $row2->sport;
            $total_ue    = $row2->matic+$row2->cub+$row2->sport;
        ?>
    <tr>
        <td><?php echo $nom ?></td>
        <td><?php echo $row2->nama_dealer?></td>
        <td><?php echo $row2->matic?></td>
        <td><?php echo $row2->cub?></td>
        <td><?php echo $row2->sport?></td>
        <?php foreach($ue_segment3->result() as $row3){?>
            <?php
                $query = $this->db->query("SELECT SUM(CASE WHEN h.id_series='$row3->id_series' then 1 ELSE 0 end) as sum_series
                FROM ms_dealer a 
                JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
                JOIN tr_h2_sa_form c on c.id_sa_form=b.id_sa_form
                LEFT JOIN ms_customer_h23 d on c.id_customer = d.id_customer
                JOIN ms_tipe_kendaraan h on d.id_tipe_kendaraan=h.id_tipe_kendaraan
                where left(b.closed_at,10) between '$start_date' and '$end_date' and b.id_dealer='$row2->id_dealer'");

            ?>
            <td><?php echo $query->row()->sum_series?></td>
        <?php }?>
        <td><?php echo $total_ue?></td>
        </tr>
        <?php 
            $nom++; 
            $sum_matic += $total_matic;
            $sum_cub   += $total_cub;
            $sum_sport += $total_sport; 
            // $sum_total_all += $query2->row()->total_all;
            $sum_ue += $total_ue;
        ?>
    <?php }?>
    <tr>
        <td colspan="2"><b>Total</b></td>
        <td><b><?php echo $sum_matic ?></b></td>
        <td><b><?php echo $sum_cub ?></b></td>
        <td><b><?php echo $sum_sport ?></b></td>
        <?php foreach($ue_segment3->result() as $row5){
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = " AND b.id_dealer='$id_dealer'";
           }
            $query2 = $this->db->query("SELECT SUM(h.id_series='$row5->id_series') as total_all
            FROM ms_dealer a 
            JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
            JOIN tr_h2_sa_form c on c.id_sa_form=b.id_sa_form
            LEFT JOIN ms_customer_h23 d on c.id_customer = d.id_customer
            JOIN ms_tipe_kendaraan h on d.id_tipe_kendaraan=h.id_tipe_kendaraan
            where left(b.closed_at,10) between '$start_date' and '$end_date' $filter_dealer");    
        ?>
        <td><b><?php echo $query2->row()->total_all  ?></b></td>
        <?php }?>
        <td><?php echo $sum_ue ?></td>
    </tr>
    <?php }else{
        echo "<td colspan='7' style='text-align:center'> Maaf, Tidak Ada Data </td>";
    }?>
</table>


