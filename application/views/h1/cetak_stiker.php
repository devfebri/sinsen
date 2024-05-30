<style type="text/css">	
body{
	margin: 0.2mm 0.2mm 0.2mm 0.2mm;
}
.set{
	/*font-weight: bold;*/
	font-family: 'consolas';
	font-size:15pt;
	letter-spacing: -1px;
	line-height: 0.8em;
}
@page {
    margin: 0.2cm;
}
</style>
<?php 
$row = $dt_stiker->row();
?>	
<body>
	<span class="set">TYPE : <?= $row->tipe_motor ?></span><br>
	<span class="set">WARNA : <?= $row->warna ?></span><br>
	<span class="set">NO. FIFO : <?= $row->fifo ?></span><br>
	<span class="set">NO.ENGINE&nbsp;&nbsp;:<?php echo $row->no_mesin ?></span><br>
	<span class="set">NO.RANGKA&nbsp;&nbsp;:<?php echo $row->no_rangka ?></span>
	<div class="set" style="font-size: 45pt;letter-spacing: 4px;line-height: 1em;font-weight: bold;"><?php echo $row->lokasi."-".$row->slot ?>
	</div>
</body>