<?php 
function cari_kode(){        
		$conn 					= mysqli_connect("123.100.226.36","sinarsen_root","success2019**","sinarsen_honda");
   	$no   = mysqli_query($conn,"SELECT * FROM ms_bbn_biro ORDER BY id_bbn_biro DESC LIMIT 0,1");                             
    $jum  = mysqli_num_rows($no);
    if($jum > 0){
        $row    = mysqli_fetch_array($no,MYSQLI_ASSOC);
        $id     = $row['id_bbn_biro'] + 1;
        $kode   = $id;
    }else{
        $kode   = 1;
    }
    return $kode;
}

//echo cari_kode();

$conn 					= mysqli_connect("123.100.226.36","sinarsen_root","success2019**","sinarsen_honda");
$json = file_get_contents('http://www.sinarsentosa.co.id/sharing/get_bbn_md_biro_jasa.php');
$obj 	= json_decode($json,true);
// //print_r($obj);
$no = 0;
foreach($obj as $array){
	$id_tipe_kendaraan =  $array['id_tipe_kendaraan'];
	$biaya_bbn =  $array['biaya_bbn'];
	$biaya_instansi =  $array['biaya_instansi'];	
	$tahun =  $array['tahun'];
	$active =  $array['active'];
	if($active == 'f') $active = 0;
		else $active = 1;
	$date 		= gmdate("y-m-d h:i:s", time()+60*60*7);
	$login_id	= 1;

	$cek = mysqli_query($conn,"SELECT * FROM ms_bbn_biro WHERE id_tipe_kendaraan = '$id_tipe_kendaraan' AND tahun_produksi = '$tahun'");
	$jum = mysqli_num_rows($cek);
	if($jum == 0){
		$id = cari_kode();					
 		mysqli_query($conn,"INSERT INTO ms_bbn_biro VALUES ('$id','$id_tipe_kendaraan','$tahun','$biaya_bbn','$biaya_instansi','$date','$login_id','0000-00-00 00:00:00','0','$active')");			 									
	}else{
		mysqli_query($conn,"UPDATE ms_bbn_biro SET biaya_bbn = '$biaya_bbn', biaya_instansi = '$biaya_instansi', active = '$active' WHERE id_tipe_kendaraan = '$id_tipe_kendaraan'");			 									
	}  
	$no++;
}
echo $no." Data berhasil dieksekusi";
?>
