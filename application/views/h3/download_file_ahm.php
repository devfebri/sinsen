<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=". $nama_file);
header("Pragma: no-cache");
header("Expires: 0");

if($ext_file=='REC'){
    $kolom = [5,30,20,15,15,30,25,10];
    $val = ['kode_md','no_penerimaan_barang','end_penerimaan','packing_sheet_number','supplier','no_po','id_part','qty_diterima'];

    foreach ($data as $row) {
        foreach ($kolom as $key => $value) {
            if($key == 2){
                $value = $value - strlen(date('dmY 000000',strtotime($row->$val[$key])));
                echo date('dmY 000000',strtotime($row->$val[$key]));
            }else{
                $value = $value - strlen($row->$val[$key]);
                echo $row->$val[$key];
            }
            for ($i=1;$i<=$value;$i++){
                echo ' ';
            }
        }
        echo "\r\n";
    }    

}else if($ext_file=='STO'){
    $kolom = [5,8,30,5,10,10];
    $val = ['kode_md','tgl_transaksi','id_part','qty','harga_md_dealer','nilai'];
    
    foreach ($data as $row) {
        foreach ($kolom as $key => $value) {
            if($key <=2){
                if($key == 1){
                    $value = $value - strlen(date('dmY',strtotime($start_date)));
                    echo date('dmY',strtotime($start_date));
                }else{
                    $value = $value - strlen($row->$val[$key]);
                    echo $row->$val[$key];
                }
                for ($i=1;$i<=$value;$i++){
                    echo ' ';
                }    
            }else{
                $value = $value - strlen($row->$val[$key]);
                for ($i=1;$i<=$value;$i++){
                    echo ' ';
                }    
                echo $row->$val[$key];
            }
        }
        echo "\r\n";
    }    
}else if($ext_file=='POD'){
    $kolom = [5,5,30,20,25,10,1];
    $val = ['kode_md','kode_dealer_ahm','po_id','tanggal_order','id_part','kuantitas','type'];

    if($valid_data==1){
        foreach ($data as $row) {
            foreach ($kolom as $key => $value) {
                if($key == 3){
                    $value = $value - strlen(date('dmY His',strtotime($row->$val[$key])));
                    echo date('dmY His',strtotime($row->$val[$key]));
                }else if($key == 6){                
                    // set utk tipe po fix (F) menjadi "R"
                    $value = $value - strlen($row->$val[$key]);
                    if($row->$val[$key] != 'F'){
                        echo $row->$val[$key];
                    }else{
                        echo 'R';
                    }
                }else{
                    $value = $value - strlen($row->$val[$key]);
                    echo $row->$val[$key];
                }
                for ($i=1;$i<=$value;$i++){
                    echo ' ';
                }
            }
            echo "\r\n";
        }
    }else{
        echo "Setting Dealer belum lengkap: \r\n";
        $i = 0;
        foreach ($data as $row) {
            $i++;
            echo $i.'. '.$row->kode_dealer_md. ' => '. $row->nama_dealer. "\r\n";
        }
    }

}else if($ext_file=='SAL'){    
    $kolom = [5,30,20,5,15,30,25,10,15,15,15];
    $val = ['kode_md','no_faktur','tgl_faktur','kode_dealer_ahm','short_id','id_ref','id_part','qty_pemenuhan','harga','harga_setelah_diskon','hpp'];
    
    if($valid_data==1){
        foreach ($data as $row) {
            foreach ($kolom as $key => $value) {
                if($key <=7){
                    if($key == 2){
                        $value = $value - strlen(date('dmY His',strtotime($row->$val[$key])));
                        echo date('dmY His',strtotime($row->$val[$key]));
                    }else{
                        $value = $value - strlen($row->$val[$key]);
                        echo $row->$val[$key];
                    }
                    for ($i=1;$i<=$value;$i++){
                        echo ' ';
                    }
                }else{
                    $value = $value - strlen($row->$val[$key]);
                    for ($i=1;$i<=$value;$i++){
                        echo ' ';
                    }
                    echo $row->$val[$key];
                }
            }
            echo "\r\n";
        }
    }else{
        echo "Setting Dealer belum lengkap: \r\n";
        $i = 0;
        foreach ($data as $row) {
            $i++;
            echo $i.'. '.$row->kode_dealer_md. ' => '. $row->nama_dealer. "\r\n";
        }
    }

}else{   
    echo "Kosong\r\n";
}	