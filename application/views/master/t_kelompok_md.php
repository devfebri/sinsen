<?php 

  function mata_uang($a){

      return number_format(intval($a), 0, ',', '.');

  } ?>



<?php if (isset($edit)) { ?>

  <input type="hidden" value="<?php echo  $header->id_kel ?>" id='id_kel' >

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

      <?php if ($detail->num_rows()>0): ?>

        <?php $no=1; foreach ($detail->result() as $val): 

            $harga_terakhir=$this->db->query("SELECT harga_jual FROM ms_kelompok_md WHERE id_item='$val->id_item' AND id_kelompok_harga='$header->id_kelompok_harga' ORDER BY start_date DESC LIMIT 0,1");

                  $hrg = $harga_terakhir->num_rows()>0?$harga_terakhir->row()->harga_jual:0;

          ?>

          <tr>

            <td><?php echo $val->id_tipe_kendaraan?></td>

            <td><?php echo $val->id_item?></td>

            <td><?php echo $val->warna?></td>

            <td><?php echo $hrg?></td>

            <td><input type="text" value="<?php echo $val->harga_jual?>" id="harga_jual_<?php echo $no?>"></td>

            <td align="center"><button class="btn btn-primary btn-xs btn-flat" onclick="saveEditDetailOne('<?php echo  $val->id ?>','<?php echo  $no ?>')">Simpan</button></td>

          </tr>

        <?php $no++;endforeach ?>

      <?php endif ?>

    </tbody>

  </table>

<?php }else{ ?>



<table class="table table-condensed table-bordered">

  <thead>

    <th width="22%">Tipe</th>

    <th width="10%">Kode Item</th>

    <th width="22%">Warna</th>

    <th width="7%">Checklist</th>

    <th width="15%">Harga Terakhir</th>

    <th width="15%">Harga baru</th>

    <th width="20">Aksi</th>

  </thead>

  <tbody>

    <?php if ($detail->num_rows()>0): ?>

        <?php $xx=1; foreach ($tipe->result() as $rs): ?>

            <?php 

            $login_id = $this->session->userdata('id_user');

            $num = $this->db->query("SELECT * FROM ms_kelompok_md_harga_detail WHERE status='new' AND created_by='$login_id'  AND LEFT(id_item,3)='$rs->id_tipe_kendaraan' ")->num_rows() ?>

            <?php $x=1;foreach ($detail->result() as $det): ?>

            

                <?php if ($det->id_tipe_kendaraan==$rs->id_tipe_kendaraan): ?>

                  <?php $harga_terakhir=$this->db->query("SELECT harga_jual FROM ms_kelompok_md WHERE id_item='$det->id_item' AND id_kelompok_harga='$id_kelompok_harga' ORDER BY start_date DESC LIMIT 0,1");

                  $hrg = $harga_terakhir->num_rows()>0?$harga_terakhir->row()->harga_jual:0;

                   ?>

                    <tr>

                      <?php if ($x==1): ?>

                        <td rowspan="<?php echo $num?>" align="left" style="vertical-align: middle;"><?php echo $rs->id_tipe_kendaraan?> | <?php echo  strip_tags($rs->deskripsi_ahm)?></td>

                      <?php endif ?>

                      <td><?php echo $det->id_item?></td>

                      <td><?php echo $det->warna?></td>

                      <td></td>

                      <td><?php echo mata_uang($hrg)?></td>

                      <td><?php echo mata_uang($det->harga_jual)?></td>

                       <?php if ($x==1): ?>

                        <input type="hidden" id="id_tipe_kendaraan_<?php echo $xx?>" value="<?php echo  $rs->id_tipe_kendaraan ?>">

                        <td rowspan="<?php echo $num?>" align="center" style="vertical-align: middle;"><button type="button" class="btn btn-danger btn-xs btn-flat" onclick="delDetail(<?php echo  $xx ?>)"><i class="fa fa-trash"></i></button></td>

                      <?php endif ?>

                    </tr>

                <?php $x++;endif ?>

            <?php endforeach ?>

        <?php $xx++; endforeach ?>



    <?php endif ?>

  </tbody>

</table>

<div id="showInput"></div>

<?php } ?>



<script>

  function delDetail(x)

  {

      var value={id_tipe_kendaraan:$('#id_tipe_kendaraan_'+x).val()

      }



      $.ajax({

               beforeSend: function() { $('#loading-status').show(); },

               url:"<?php echo site_url('master/kelompok_md/delDetail');?>",

               type:"POST",

               data:value,

              // dataType:'JSON',

               cache:false,

               success:function(data){

                  $('#loading-status').hide();

                  

                  if(data=="nihil"){

                    $('#loading-status').hide();

                    generate();

                  }else{

                    alert(data);          

                  }    

               },

               statusCode: {

            500: function() { 

              $('#loading-status').hide();

              alert("Something Wen't Wrong");

            }

          }

          });

  }



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