<?php 
$rt = $this->db->query("SELECT * FROM tr_sales_order WHERE id_sales_order = 'SO-00675/2019/09/03/0001'")->;
if(is_null($rt->latitude)){
	echo "null";
}else{
	echo "not null";
}
?>