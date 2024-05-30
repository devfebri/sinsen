<?php 
  function mata_uang($a){
      return number_format(intval($a), 0, ',', '.');
  } ?>

    <input type="hidden" value="<?php echo  $id_kel ?>" id='id_kel' >
    <table class="table table-condensed table-bordered">
    <thead>
      <th width="22%">Tipe</th>
      <th width="10%">Kode Item</th>
      <th width="22%">Warna</th>
      <th width="15%">Harga Terakhir</th>
      <th width="15%">Harga baru</th>
      <th width="20">Aksi</th>
    </thead>
    <tbody>      
      <?php 
      $no=1; 
      foreach($detail->result() as $val){
        $sql = $this->m_admin->getByID("ms_kelompok_md_harga","id_kel",$val->id_kel)->row()->id_kelompok_harga;
        $harga_terakhir = $this->db->query("SELECT harga_jual FROM ms_kelompok_md WHERE id_item='$val->id_item' 
                AND id_kelompok_harga = '$sql' ORDER BY start_date DESC LIMIT 0,1");
        $hrg = $harga_terakhir->num_rows()>0?$harga_terakhir->row()->harga_jual:0;
          ?>
          <tr>
            <td><?php echo $val->id_tipe_kendaraan ?></td>
            <td><?php echo $val->id_item ?></td>
            <td><?php echo $val->warna ?></td>
            <td><?php echo mata_uang($hrg) ?></td>
            <td><input type="text" value="<?php echo mata_uang($val->harga_jual) ?>" id="harga_jual_<?php echo $no ?>" onkeyup="cek_format2(<?php echo $no ?>)"></td>
            <td align="center"><button type="button" class="btn btn-primary btn-xs btn-flat" onclick="saveEditDetailOne('<?php echo  $val->id ?>','<?php echo  $no ?>')">Simpan</button></td>
          </tr>
        <?php 
        $no++; 
        }
        ?>      
    </tbody>
  </table>

<script type="text/javascript">
function saveEditDetailOne(x,a)
{
    var value={id:x,
               harga_jual:$('#harga_jual_'+a).val(),
               id_kel:$('#id_kel').val()
    }

    $.ajax({
             beforeSend: function() { $('#loading-status').show(); },
             url:"<?php echo site_url('master/kelompok_md/saveEditDetailOne');?>",
             type:"POST",
             data:value,
            // dataType:'JSON',
             cache:false,
             success:function(data){
                $('#loading-status').hide();
                $("#show_detail").html(html);

             },
             statusCode: {
          500: function() { 
            $('#loading-status').hide();
            alert("Something Wen't Wrong");
          }
        }
        });
}

</script>
 