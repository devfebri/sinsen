<?php 
$conn = mysqli_connect("123.100.226.36","sinarsen_root","success2019**","sinarsen_honda");	
$rt 	= mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM tr_sales_order WHERE id_sales_order = 'SO-00675/2019/09/03/0001'"),MYSQLI_ASSOC);
//if($rt['tahun_produksi'] === NULL){
if(is_null($rt['latitude'])){
	echo "null";
}else{
	echo $rt['tahun_produksi'];
}
?>
