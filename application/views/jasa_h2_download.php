<!doctype html>
<html>
    <head>
        <title>Download File</title>
        <style>
            .word-table {
                border:1px solid black !important; 
                border-collapse: collapse !important;
                width: 100%;
            }
            .word-table tr th, .word-table tr td{
                border:1px solid black !important; 
                padding: 2px 2px;
                font-family:'sans-serif';
                font-weight:normal;
            }
        </style>
    </head>
    <body>
    <?php
  header("Content-type: application/vnd-ms-excel");
  $tanggal=date('Y-m-d').".xls";
  $name_file ="Master_jasa_h2";
  header("Content-Disposition: attachment; filename=$name_file.xls"); 
   ?> 
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
                <th>ID Jasa Int</th>
                <th>ID Jasa</th>
                <th>ID Jasa2</th>
                <th>Deskripsi</th>
                <th>ID Type</th>
                <th>Kategori</th>
                <th>Tipe Motor</th>
                <th>Harga</th>
                <th>Batas Atas</th>
                <th>Batas Bawah</th>
                <th>Waktu</th>
                <th>Active</th>
                <th>Created At</th>
                <th>Created By</th>
                <th>Updated At</th>
                <th>Updated By</th>
                <th>Deleted At</th>
                <th>Deleted By</th>
                <th>Is Favourite</th>
                <th>Activity Capacity</th>
                <th>Activity Promotion</th>
                <th>Kode Jenis Pekerjaan</th>
                <th>Kode Kategori Pekerjaan</th>
                <th>Kode Jasa AHM</th>
            </tr>
                <?php
                
                $start=1;
                $query = $this->db->query("SELECT * FROM ms_h2_jasa where active ='1'")->result();
                $activity_capacity = "";
                $activity_promotion = "";
                
                foreach($query as $rows){
                    
                    if($rows->activity_capacity == 'BS'){
                        $activity_capacity = "1";
                    }elseif($rows->activity_capacity == 'HH'){
                        $activity_capacity = "2";
                    }else{
                        $activity_capacity = "3";
                    }
                    
                    
                    if($rows->activity_promotion =='SVPS'){
                        $activity_promotion ="1";
                    }elseif($rows->activity_promotion =='SVJD'){
                        $activity_promotion ="2";
                    }elseif($rows->activity_promotion =='SVGC'){
                        $activity_promotion ="3";
                    }elseif($rows->activity_promotion =='SVPA'){
                        $activity_promotion ="4";
                    }elseif($rows->activity_promotion =='SVER'){
                        $activity_promotion ="5";
                    }elseif($rows->activity_promotion =='PE'){
                        $activity_promotion ="6";
                    }elseif($rows->activity_promotion =='RM'){
                        $activity_promotion ="7";
                    }elseif($rows->activity_promotion =='AE01'){
                        $activity_promotion ="8";
                    }elseif($rows->activity_promotion =='AE02'){
                        $activity_promotion ="9";
                    }elseif($rows->activity_promotion =='AE03'){
                        $activity_promotion ="10";
                    }elseif($rows->activity_promotion =='NP'){
                        $activity_promotion ="11";
                    }
                ?>
                <tr>
                <td><?php echo $start?></td>
                <td><?php echo $rows->id_jasa_int?></td>
                <td><?php echo $rows->id_jasa?></td>
                <td><?php echo $rows->id_jasa2?></td>
                <td><?php echo $rows->deskripsi?></td>
                <td><?php echo $rows->id_type?></td>
                <td><?php echo $rows->kategori?></td>
                <td><?php echo $rows->tipe_motor?></td>
                <td><?php echo $rows->harga?></td>
                <td><?php echo $rows->batas_atas?></td>
                <td><?php echo $rows->batas_bawah?></td>
                <td><?php echo $rows->waktu?></td>
                <td><?php echo $rows->active?></td>
                <td><?php echo $rows->created_at?></td>
                <td><?php echo $rows->created_by?></td>
                <td><?php echo $rows->updated_at?></td>
                <td><?php echo $rows->updated_by?></td>
                <td><?php echo $rows->deleted_at?></td>
                <td><?php echo $rows->deleted_by?></td>
                <td><?php echo $rows->is_favorite?></td>
                <td><?php echo $activity_capacity?></td>
                <td><?php echo $activity_promotion?></td>
                <td><?php echo $rows->kode_jenis_pekerjaan?></td>
                <td><?php echo $rows->kode_kategori_pekerjaan?></td>
                <td><?php echo $rows->kode_jasa_ahm?></td>
            </tr>
             <?php
                $start++;
            }
            ?>
        </table>
    </body>
</html>