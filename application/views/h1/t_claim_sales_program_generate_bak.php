<?php function mata_uang($a){

    	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);

        return number_format($a, 0, ',', '.');

    } ?>

<?php if ($set=='generate') { ?>

    <button class="btn btn-block btn-warning btn-flat btn-sm" disabled> DETAIL UNIT </button><br>
<div class="table-responsive">
  
 <table class="table table-bordered table-hovered table-condensed" width="100%">

  <thead>

    <th width='5%'>No Mesin</th>

    <th width='30%'>Tipe Kendaraan</th>                                        

    <th width='10%'>No Faktur</th>                                        

    <th width='7%'>Tgl Faktur</th>
    <th>No PO Leasing</th>
    <th>Tgl PO Leasing</th>
    <th>No BASTK</th>
    <th width="10%">Tgl BASTK</th>
    <th>Nama Konsumen</th>
    <th>Leasing</th>
    <th width='10%'>Nilai Potongan</th>                                        

    <th width='6%'>Cek Syarat</th>                                        

    <th width='5%'>Status</th>                                        

    <th width='8%'>Perlu Revisi</th>                                                        

  </thead> 

  <tbody>
  <?php 
    if ($detail=='kosong') { ?>
      <p align="center"><h3 align="center">Data Kosong</h3></p>
    <?php die(); } 
    $tot_row=count($detail);
    $id_tipe_kendaraan='';
    $no=0;
    $tot_approve=0;$tot_reject=0;$tot_gantung=0;
    $show_tot=0;
    $tot_gantung_all=0;
    foreach ($detail AS $key=>$dt){ ?>
    <?php if ($dt['field']=='row'): 
    ?>

    <?php if ($id_tipe_kendaraan!='') {
      if ($id_tipe_kendaraan!=$dt['data']['id_tipe_kendaraan']) { 
        $tot_gantung_all+=$tot_gantung;
      ?>
        <tr style="font-weight: bold;background-color:#c7c7c7">
          <td colspan="14">
            <span style="padding-right: 5%">Total Unit</span>
            <span style="padding-right: 5%"><?= $tot_gantung+$tot_approve+$tot_reject ?></span>
            <span style="padding-right: 5%">Total Gantung</span>
            <span style="padding-right: 5%"><?= $tot_gantung ?></span>
            <span style="padding-right: 5%">Total Approve</span>
            <span style="padding-right: 5%"><?= $tot_approve ?></span>
            <span style="padding-right: 5%">Total Reject</span>
            <span style="padding-right: 5%"><?= $tot_reject ?></span>
          </td>
        </tr>  
      <?php 
        $tot_approve=0;
        $tot_gantung=0;
        $tot_reject=0;
        $id_tipe_kendaraan=$dt['data']['id_tipe_kendaraan'];
      }
    }
    ?>
      <tr>
        <td><?= $dt['data']['no_mesin'] ?></td>
        <td><?= $dt['data']['id_tipe_kendaraan'].' | '.$dt['data']['tipe_ahm'] ?></td>
        <td><?= $dt['data']['no_invoice'] ?></td>
        <td><?= $dt['data']['tgl_cetak_invoice'] ?></td>
        <td><?= $dt['data']['no_po_leasing'] ?></td>
        <td><?= $dt['data']['tgl_po_leasing'] ?></td>
        <td><?= $dt['data']['no_bastk'] ?></td>
        <td><?= $dt['data']['tgl_bastk'] ?></td>
        <td><?= $dt['data']['nama_konsumen'] ?></td>
        <td><?= $dt['data']['finance_company'] ?></td>

          <?php 
            $id_claim = $dt['data']['id_claim'];
            $nilai_potongan = $this->mbc->get_nilai_potongan($id_claim);
            $nilai_potongan = ($nilai_potongan == '') ? 0 : $nilai_potongan ;
          ?>
        <td align='right'><?=mata_uang($nilai_potongan)?></td>
        <td><button class="btn btn-link" type="button" onclick="showSyarat('<?=$id_claim?>',<?= $key ?>)" >Cek</button></td>
        <?php

                  $disabled=''; 

                  if ($dt['data']['status']=='approved') {

                  $status="<span class='label label-success'>Approved</span>";

                  $disabled='disabled';

              }elseif ($dt['data']['status']=='rejected') {

                  $status="<span class='label label-danger'>Rejected</span>";

              }else{

                $status='';

              } ?>

              <td align="center"><?=$status?></td>

              <td align="center">

                  <input type="hidden" name="id_claim_<?=$key?>" value="<?=$id_claim?>">

                  <input type="checkbox" name="chk_revisi_<?=$key?>" id="chk_revisi_<?=$key?>" <?= $disabled ?> <?= isset($dt['data']['perlu_revisi'])?$dt['data']['perlu_revisi']==1?'checked':'':'' ?> <?= $mode=='detail'?'disabled':'' ?>>

              </td>
      </tr>
      <?php
        if ($id_tipe_kendaraan=='') {
          $id_tipe_kendaraan=$dt['data']['id_tipe_kendaraan'];
        }
        $no++;
        $show_tot=0;
        if ($id_tipe_kendaraan==$dt['data']['id_tipe_kendaraan']) {
          if ($dt['data']['status']=='approved') {
            $tot_approve++;
          }else if ($dt['data']['status']=='rejected') {
            $tot_reject++;
          }else{
            $tot_gantung++;
          }
        }
        if ($no==$tot_row) {
          $show_tot=1;
        }
      ?>
      <?php if ($show_tot==1): 
        $tot_gantung_all+=$tot_gantung;
      ?>
      <tr style="font-weight: bold;background-color:#c7c7c7">
        <td colspan="14">
          <span style="padding-right: 5%">Total Unit</span>
          <span style="padding-right: 5%"><?= $tot_gantung+$tot_approve+$tot_reject ?></span>
          <span style="padding-right: 5%">Total Gantung</span>
          <span style="padding-right: 5%"><?= $tot_gantung ?></span>
          <span style="padding-right: 5%">Total Approve</span>
          <span style="padding-right: 5%"><?= $tot_approve ?></span>
          <span style="padding-right: 5%">Total Reject</span>
          <span style="padding-right: 5%"><?= $tot_reject ?></span>
        </td>
      </tr>
      <?php endif ?>
    <?php endif ?>
  <?php } ?>
  </tbody>       

</table>   
<input type="hidden" id="tot_gantung_all" value="<?= $tot_gantung_all ?>">
</div>
<div class="modal fade" id="modalSyarat">      

  <div class="modal-dialog modal-lg" role="document">

    <div class="modal-content">

      <div class="modal-header">

        Cek Syarat

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        

      </div>

      <div class="modal-body">

        <div id="showModalSyarat"></div>

      </div>      

    </div>

  </div>

</div>

<script type="text/javascript">

    function showSyarat(id_claim,key)

  {

       var value={id_program_md:$("#id_program_md").val(),
                  id_claim:id_claim,
                  key:key,
                  mode:'<?= $mode ?>'
                }

      $.ajax({

           beforeSend: function() { $('#loading-status').show(); },

           url:"<?php echo site_url('h1/claim_sales_program/getSyarat')?>",

           type:"POST",

           data:value,

           cache:false,

           success:function(html){

              $("#modalSyarat").modal('show');

              $('#showModalSyarat').html(html);

              $('#loading-status').hide();

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

<?php }

elseif ($set=='showdatamodal') { 
$set_disabled='';
if ($mode=='detail') {
  $set_disabled='disabled';
}
    // $row=$detail->row();

    ?>

<!-- <div class="form-group">

                

                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>

                  <div class="col-sm-4">

                    <input type="text" readonly class="form-control" placeholder="No Mesin" name="no_mesin" value="<?=$row->no_mesin?>" readonly>                                        

                  </div>                  

                  <label for="inputEmail3" class="col-sm-2 control-label">No Invoice</label>

                  <div class="col-sm-4">

                    <input type="text" readonly class="form-control" name="nama_konsumen" value="<?=$row->no_invoice?>">                    

                  </div>

                </div> -->                
<!-- 
                <div class="form-group">                

                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>

                  <div class="col-sm-4">

                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->tipe_ahm?>">                    

                  </div>                                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Invoice</label>

                  <div class="col-sm-4">

                    <input type="text" readonly class="form-control" placeholder="" name="" value="<?=$row->tgl_cetak_invoice?>">                    

                   </div>                  

                </div> -->                                  

                <!-- <div class="form-group">                

                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>

                  <div class="col-sm-4">

                    <input type="text" readonly class="form-control" placeholder="Warna" name="nama_konsumen" value="<?=$row->warna?>">                    

                  </div>                                  

                  <label for="inputEmail3" class="col-sm-2 control-label">No BASTK</label>

                  <div class="col-sm-4">

                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->no_bastk?>">                    

                  </div>                  

                </div>  -->

            <!--     <div class="form-group">                

                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>

                  <div class="col-sm-4">

                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->nama_konsumen?>">                    

                  </div>                                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl BASTK</label>

                  <div class="col-sm-4">

                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->tgl_bastk?>">                    

                  </div>                  

                </div>  -->

                <?php if($row->jenis_beli == 'Kredit'){ ?>

                <!-- <div class="form-group">                

                  <label for="inputEmail3" class="col-sm-2 control-label">No PO Leasing</label>

                  <div class="col-sm-4">

                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->no_po_leasing?>">                    

                  </div>                                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl PO Leasing</label>

                  <div class="col-sm-4">

                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->tgl_po_leasing?>">                    

                  </div>                  

                </div>  -->

                <?php } ?>

                <div class="form-group">                

                  <div class="col-md-12">

                    <button type="reset" class="btn btn-primary btn-flat btn-block" disabled="">Syarat dan Ketentuan</button>

                  </div><br><br><br>
                  <input type="hidden" id="detail_key" value="<?= $detail_key ?>">
                  <div class="col-md-12">

                    <table class="table table-bordered table-condensed table-hover">

                      <thead>

                        <th style="text-align: center;width: 4%">No</th>

                        <th style="text-align: center;width: 55%">Syarat</th>

                        <th  style="text-align: center;width: 6%">Checklist</th>

                        <th style="text-align: center;width:35%">Alasan Reject</th>

                      </thead>

                    <tbody>

                        <form id="frm_syarat" class="form-horizontal" name="frm_syarat" method="POST">

                      <?php
                        // $get_syarat = $_SESSION['generate_new'][$detail_key]['data']['syarat'];
                        $tot_syarat = count($get_syarat);
                      // if ($tot_syarat>0) {

                        $no=1;
                         foreach ($get_syarat as $key => $rs) { ?>

                           <tr>

                            <td><?=$no?></td>

                             <td><?=$rs['syarat_ketentuan']?></td>

                             <td align="center">

                                <?php if ($rs['checklist_reject_md']=='' OR $rs['checklist_reject_md']==null OR $rs['checklist_reject_md']==0) {

                                    $checked='';

                                }else{

                                    $checked='checked';

                                } ?>

                            <input type="checkbox" id="cek_<?=$key?>" onclick="undisabled(<?=$key?>)" <?=$checked ?> <?= $set_disabled ?>>

                              <input type="hidden" id="id_<?=$key?>" value="<?=$rs['id']?>" >
                              <input type="hidden" id="id_syarat_ketentuan_<?=$key?>" value="<?=$rs['id_syarat_ketentuan']?>" >
                              <input type="hidden" id="syarat_ketentuan_<?=$key?>" value="<?=$rs['syarat_ketentuan']?>" >

                              <input type="hidden" class="id_claim" value="<?=$rs['id_claim']?>" >

                             </td>

                             <td>

                                <?php if ($rs['alasan_reject']=='' OR $rs['alasan_reject']==null)

                                {

                                    $disabled='disabled';

                                }else{

                                    $disabled='';

                                } ?>

                                <select class="form-control select2" <?=$disabled?> style="width: 100%" id="alasan_reject_<?=$key?>" <?= $set_disabled ?>>

                                    <?php 

                                    $getReject=$this->db->query("SELECT * FROM ms_alasan_reject ORDER BY alasan_reject ASC")

                                     ?>

                                     <?php if ($getReject->num_rows()>0): ?>

                                         <option value="">- choose -</option>

                                         <?php foreach ($getReject->result() as $key => $rss):

                                            if ($rss->id_alasan_reject == $rs['alasan_reject']) {

                                                $select='selected';

                                            }else{

                                                $select='';

                                            }

                                          ?>

                                             <option value="<?=$rss->id_alasan_reject?>" <?=$select?> > <?=$rss->alasan_reject?></option>

                                         <?php endforeach ?>

                                     <?php endif ?>

                                </select>

                             </td>

                           </tr>

                        <?php $no++; }

                       // }
                        ?>

                    </tbody>

                  </table>          

                  </div>

<div class="col-sm-5">

                </div>

</form>

                <?php if ($mode!='detail'): ?>
                  <div class="col-sm-7">

                  <button type="button" class="btn btn-info btn-flat" id="btnSaveCekSyarat" onclick="saveCekSyarat()"><i class="fa fa-save"></i> Simpan</button>           

                </div>
                <?php endif ?>

                </div>    

<script type="text/javascript">

    function undisabled(i)

    {

        if ($('#cek_'+i).is(":checked")) {

            $('#alasan_reject_'+i).removeAttr('disabled');

        }else{

            $('#alasan_reject_'+i).val('').trigger('change.select2'); 

            $('#alasan_reject_'+i).attr('disabled','disabled');

        }

    }

    function saveCekSyarat()

  {<?php 

  

    if (isset($mode)) {

      if ($mode=='edit') {

        $mode_=',mode:"edit"';

      }else{

        $mode_='';

      }

    }else{

      $mode_='';

    } ?>

       var value = {

        <?php for($i=0;$i<$tot_syarat;$i++){ ?>

                    id_<?=$i?>:$('#id_<?=$i?>').val(),
                    id_syarat_ketentuan_<?=$i?>:$('#id_syarat_ketentuan_<?=$i?>').val(),
                    syarat_ketentuan_<?=$i?>:$('#syarat_ketentuan_<?=$i?>').val(),

                    alasan_reject_<?=$i?>:$('#alasan_reject_<?=$i?>').val(),

                    cek_<?=$i?>: $('#cek_<?=$i?>').is(":checked")?'1':null,

                <?php } ?>

                row:<?=$tot_syarat?>,
                detail_key:$('#detail_key').val(),
                id_claim:$('.id_claim').val(),
                mode:'<?= $mode ?>'
                <?=$mode_?>

       }

      $.ajax({

           beforeSend: function() { 
            // $('#loading-status').show() 
            $("#btnSaveCekSyarat").attr('disabled',true);
            $("#btnSaveCekSyarat").html('<i class="fa fa-spinner fa-spin"></i> Process');
           },

           url:"<?php echo site_url('h1/claim_sales_program/saveCekSyarat')?>",

           type:"POST",

           data: value,
           cache:false,
           dataType:'JSON',
           success:function(response){
            $("#btnSaveCekSyarat").attr('disabled',false);
            $("#btnSaveCekSyarat").html('<i class="fa fa-save"></i> Simpan');
            if (response.status=='sukses') {
              <?php if ($mode=='insert') { ?>
                  generate('save_detail','<?= $mode ?>','no_reset');
              <?php }else{ ?>
                  generate(null,'<?= $mode ?>','no_reset');
              <?php } ?>
              $("#modalSyarat").modal('hide');
              $('body').removeClass('modal-open');
              $('body').css("padding-right", "0px");  // Bootstrap will calc the width of the browser toolbar and add this padding each postback (cumulatively)
              $('.modal-backdrop').remove();
              // $(".modal-open").remove();
            }else{
              alert('Something went wrong..!');
            }
           },

           statusCode: {

        500: function() {
          $("#btnSaveCekSyarat").attr('disabled',false);
          $("#btnSaveCekSyarat").html('<i class="fa fa-save"></i> Simpan');
          $('#loading-status').hide();

          alert("Something Wen't Wrong");

        }

      }

      });

  }

</script> 

           

<?php }

?>

<script type="text/javascript">

    {

    $(".select2").select2({

            allowClear: false

        });

  }

</script>