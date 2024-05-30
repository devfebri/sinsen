<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<!-- <script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>"/> -->
<link
        href=
"https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet">
 
    <script src=
"https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js">
        </script>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>


<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>

  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H2</li>
    <li class="">3 Axis Analysis</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
    <?php if($set=="view"){ ?>
      <section class="content">
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">
                <a href="dealer/h2_dealer_follow_up_customer_list/history" class="btn bg-blue btn-flat margin"><i class="fa fa-list"></i> History</a>
              </h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div>
            </div><!-- /.box-header -->
            <div class="box-body">   
            <?php if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {?>
          <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
          </div>
      <?php } $_SESSION['pesan'] = ''; ?>
      <?php if($notification->num_rows()>0){ ?>
          <div class="alert alert-success alert-dismissable">
            Terdapat <strong>data Follow Up (3 Axis Analysis)</strong> yang belum di-Follow Up dari MD. Mohon di-Follow Up.
            <button class="close" data-dismiss="alert">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
          </div>
      <?php }?>     
        <div class="row">
          <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover table-condensed" id="fu_assign_table" style="width: 100%">
              <thead>
                <tr>
                  <th>#</th>
                  <th style="width:150px">ID Follow Up</th>
                  <th style="width:150px">ID Customer</th>
                  <th>Nama</th>
                  <th>Tanggal Assigned</th>
                  <th>Follow Up Ke-</th>
                  <th>Media Komunikasi</th>
                  <th>Tanggal Follow Up</th>
                  <th>Status Komunikasi</th>
                  <th>Tanggal Next Follow Up</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
  </section>
  </html>

  <script>
        var table;
        $(document).ready(function() {
            table = $('#fu_assign_table').DataTable({ 
                "processing": true, 
                "serverSide": true, 
                "order": [], 
                "ajax": {
                    "url": "<?php echo site_url('dealer/H2_dealer_follow_up_customer_list/getDataFUReminder')?>",
                    "type": "POST"
                },
                "columnDefs": [
                { 
                    "targets": [ 0 ], 
                    "orderable": false, 
                },
                ], 
            });
        });
        
    </script>   
    
  <?php }elseif($set=="detail"){?>
    <section class="content">
      <div class="box box-default">
      
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/h2_dealer_follow_up_customer_list">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-arrow-left"></i> Kembali </button>
            </a>
          </h3>
          <form class="form-horizontal" id="frm" method="post" action= "dealer/h2_dealer_follow_up_customer_list/save_fu" enctype="multipart/form-data">
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <h2 class="box-title">
                    <h4><b>Profil Customer</b></h4>
                    <input type="hidden" name="id_follow_up" id="id_follow_up" value="<?php echo $getFUData->row()->id_follow_up ?>">
                    <input type="hidden" name="id_customer" id="id_customer" value="<?php echo $getFUData->row()->id_customer ?>">
                  </h2>
                    
                  <div class="row">
                      <div class="col-sm-6">
                        <table class="table">
                          <tr>
                              <td>Nama</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->nama_pembawa ?></td>
                          </tr>
                          <tr>
                              <td>Tanggal Lahir</td>
                              <td>:</td>
                              <td> <input type="text" class="form-control datepicker" name="tgl_lahir" value="<?= $getFUData->row()->tgl_lahir ?>" id="tgl_lahir"></td>
                          </tr>
                          <tr>
                              <td>Asal</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->provinsi ?></td>
                          </tr> 
                          <tr>
                              <td>No.Mesin</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->no_mesin ?></td>
                          </tr> 
                          <tr>
                              <td>No.Rangka</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->no_rangka ?></td>
                          </tr> 
                          <tr>
                              <td>No.Plat</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->no_polisi ?></td>
                          </tr> 
                          <tr>
                              <td>Tahun Motor</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->tahun_produksi ?></td>
                          </tr> 
                          <tr>
                              <td>Tanggal Pembelian Motor</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->tgl_pembelian ?></td>
                          </tr> 
                        </table>
                      </div>
                      <div class="col-sm-6">
                        <table class="table">
                          <tr>
                              <td>No. HP </td>
                              <td>:</td>
                              <td><input type="number" class="form-control" name="no_hp" value="<?=$getFUData->row()->hp_pembawa?>" id="no_hp">
                              <small>Ex.0852XXXXXXXX</small></td>
                          </tr>
                          <tr>
                              <td>Email</td>
                              <td>:</td>
                              <td><input type="email" class="form-control" name="email" value="<?=$getFUData->row()->email?>" id="email"></td>
                          </tr>
                          <tr>
                              <td>Pengguna Motor</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->nama_pembawa ?></td>
                          </tr>
                          <tr>
                              <td>Tujuan Penggunaan Motor</td>
                              <td>:</td>
                              <td><input type="text" class="form-control" name="tujuan_penggunaan_motor" value="<?=$getFUData->row()->tujuan_penggunaan_motor?>" id="tujuan_penggunaan_motor"> </td>
                          </tr> 
                          <tr>
                              <td>Last ToJ</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->deskripsi ?> ( <?php echo $getFUData->row()->tgl_servis ?> ) </td>
                          </tr> 
                          <tr>
                              <td>Pending Item</td>
                              <td>:</td>
                              <td> - </td>
                          </tr>  
                          <tr>
                              <td>Catatan SA/Mekanik</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->saran_mekanik ?></td>
                          </tr>
                        </table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12" align="center">
                      <button type="button" name="process" value="updateData" id="btn_update"  class="btn btn-sm btn-primary btn_generate" onclick="update_data()"> <i class="fa fa-pencil"> </i> Update Data </button>                                                                                                       
                      </div>
                    </div>
                    <br>
                    <?php 
                    $getData = $getFUData->row()->id_follow_up;
                    $deleteChar = str_replace(array("/"), '', $getData); 
                    ?>
                    <button type="button" name="edit" value="edit" class="btn btn-primary btn-sm btn-flat pull-left" data-toggle="modal" data-target="#riwayatFolUp_<?=$deleteChar?>" data-id="<?php echo $getFUData->row()->id_follow_up;?>"> Riwayat Follow Up</button>
                    <button type="button" class="btn btn-warning btn-sm btn-flat" id="btnRiwayatServis" onclick="cekRiwayatServis()" style="margin-left:10px;">Riwayat Servis</button>
                    <br>
                  <h2 class="box-title">
                    <h4><b>Customer Follow Up</b></h4>
                  </h2>
                  <div class="row">
                      <div class="col-sm-12">
                        <!-- <form class="form-horizontal" action="dealer/h2_dealer_follow_up_customer_list/save_fu" enctype="multipart/form-data" method="post"> -->
                            <div class="box-body">
                            <?php
                                if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
                                ?>
                                  <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
                                    <strong><?php echo $_SESSION['pesan'] ?></strong>
                                    <button class="close" data-dismiss="alert">
                                      <span aria-hidden="true">&times;</span>
                                      <span class="sr-only">Close</span>
                                    </button>
                                  </div>
                                <?php
                                }
                                $_SESSION['pesan'] = '';
                              ?>   
                            <div class="form-group">
                            <?php 
                              if($template_pesan->row()->id_dealer == $this->m_admin->cari_dealer()) { ?>
                                <button type="button" id="templateEditWA" name="edit" value="edit" class="btn btn-warning btn-sm btn-flat pull-right" data-toggle="modal" data-target="#templateEdit_<?=$template_pesan->row()->id_template;?>" data-id="<?php echo $template_pesan->row()->id_template ?>"> Edit Template WA</button>
                           <?php }else {?>
                              <button type="button" id="templateAddWA" name="add" value="add" class="btn btn-warning btn-sm btn-flat pull-right" data-toggle="modal" data-target="#modalTemplate"> Add Template WA</button>
                          <?php }?>

                          <?php 
                              if($template_pesan_global->row()->id_dealer == $this->m_admin->cari_dealer()) { ?>
                                <button type="button" id="templateEditGlobal" name="edit" value="edit" class="btn btn-warning btn-sm btn-flat pull-right" data-toggle="modal" data-target="#templateEditGlobal_<?=$template_pesan_global->row()->id_template;?>" data-id="<?php echo $template_pesan_global->row()->id_template ?>"> Edit Template</button>
                           <?php }else {?>
                              <button type="button" id="templateAddGlobal" name="add" value="add" class="btn btn-warning btn-sm btn-flat pull-right" data-toggle="modal" data-target="#modalTemplateGlobal"> Add Template</button>
                          <?php }?>
                           
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">PIC Follow Up*</label>
                                <div class="col-sm-3">
                                <select class="form-control select2" aria-label="Default select example" name="id_karyawan_dealer" id="id_karyawan_dealer">
                                    <option selected disabled>Pilih PIC Follow Up</option>
                                    <?php foreach($pic as $karyawan) : ?>
                                        <option value="<?php echo $karyawan->id_karyawan_dealer?>"><?php echo $karyawan->nama_lengkap?></option>
                                    <?php endforeach; ?>
                                </select>
                                </div>  
                                <label for="inputEmail3" class="col-sm-2 control-label">Media Follow Up*</label>
                                <div class="col-sm-3">
                                <select required class="form-control select2" aria-label="Default select example" name="id_media_kontak_fol_up" id="id_media_kontak_fol_up">
                                    <option selected disabled>Pilih Media Follow Up</option>
                                    <?php foreach($mediaKomunikasi as $media) : ?>
                                        <option value="<?php echo $media->id_media_kontak_fu?>"><?php echo $media->media_kontak_fu?></option>
                                    <?php endforeach; ?>
                                </select>
                                </div> 
                                <?php 
                                // $no = $getFUData->row()->hp_pembawa;
                                $no=$getFUData->row()->hp_pembawa;
                                if(substr($no,0,2)=='08'){
                                  $no = str_replace(substr($no,0,2),"628",$no);
                                }elseif(substr($no,0,3)=='+62'){
                                  $no = str_replace(substr($no,0,3),"62",$no);
                                }elseif(substr($no,0,1)=='8'){
                                  $no = str_replace(substr($no,0,1),"628",$no);
                                }

                                $pesan = $template_pesan->row()->pesan;
                                // $pesan = "<strong>AA</strong>";
                                // $pesan2 = str_replace(['<p>','</p>'],['',''],$pesan);
                                // $pesan2 =  str_replace( array("<b>","<strong>","</b>","</strong>"), array("*","*","*","*"), $pesan2);

                                $pesan = preg_replace('/<p>(.?)<br\s\/?><\/p>/i', '<p>$1</p>', $pesan);

                                $pesan = str_replace(['<p>', '</p>'], ["", "\n"], $pesan);

                                $pesan = str_replace(['<strong>', '</strong>'], ['*', '*'], $pesan);
                                $pesan = str_replace(['<br>'], [""], $pesan);

                                // $message = '&text=' . urlencode($pesan2);
                                $message = '&text=' . urlencode($pesan);
                                $link_no="https://web.whatsapp.com/send?phone=".$no. $message;
                                $wa = '<a href="'.$link_no.'" id="wa" target="_blank" data-toggle="tooltip" data-placement="top" title="Chat WA"> <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                                  </svg> </a>';
                                ?>
                                <div class="col-sm-1" id="chat-wa">
                                    <?php echo $wa?>
                                </div>
                                <!-- <div class="col-sm-1" style="margin-left: -40px;"> -->
                                    <!-- <button id="message_blast" class="btn btn-primary btn-primary btn-sm btn-flat" data-toggle="tooltip" data-placement="top" title="Message Blast">Message Blast</button> -->
                                  <!-- <a id="message_blast" data-toggle="tooltip" data-placement="top" title="Message Blast"> <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">
                                    <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                    <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9.06 9.06 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.437 10.437 0 0 1-.524 2.318l-.003.011a10.722 10.722 0 0 1-.244.637c-.079.186.074.394.273.362a21.673 21.673 0 0 0 .693-.125zm.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6c0 3.193-3.004 6-7 6a8.06 8.06 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a10.97 10.97 0 0 0 .398-2z"/>
                                  </svg> </a>
                                </div>                                    -->
                            </div> 
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Follow Up*</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control datepicker" value="<?= date('Y-m-d') ?>" name="tgl_fol_up" id="tgl_fol_up">
                                </div>  
                                <div class="col-sm-1">
                                    <input type="text" class="form-control" value="<?= date('H:mm:s') ?>" name="jam_fol_up" id="datetime"/>
                                </div> 
                                
                                <label for="inputEmail3" class="col-sm-2 control-label">Hasil Follow Up*</label>
                                <div class="col-sm-3">
                                <select class="form-control select2" aria-label="Default select example" name="id_kategori_status_komunikasi" id="id_kategori_status_komunikasi">
                                    <option selected disabled>Pilih Hasil Follow Up</option>
                                    <?php foreach($listFU as $hasil) : ?>
                                        <option value="<?php echo $hasil->id_kategori_status_komunikasi?>"><?php echo $hasil->kategori_status_komunikasi?></option>
                                    <?php endforeach; ?>
                                </select>                     
                                </div>
                            </div>
                            <?php $dataNama = isset($_POST['keterangan']) ? $_POST['keterangan'] : '';     ?>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Next Follow Up*</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control datepicker" name="tgl_next_fol_up" value="<?= date('Y-m-d') ?>" id="tgl_next_fol_up">
                                </div>  
                                <label for="inputEmail3" class="col-sm-2 control-label">Keterangan Follow Up</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="keterangan" placeholder="Keterangan Follow Up" id="keterangan" value="<?php echo set_value('keterangan'); ?>">
                                </div> 
                            </div>
                            
                            <div class="form-group">
                                <label for="bookingOption" class="col-sm-2 control-label">Apakah Customer Melakukan Booking?*</label>
                                <div class="col-sm-3">
                                    <select class="form-control" id="bookingOption" name="is_booking">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </div>
                                <label for="inputEmail3" class="col-sm-2 control-label" id="label_booking">Tanggal Booking Service</label>
                                <div class="col-sm-3" id="div_booking">
                                    <input type="text" class="form-control datepicker" name="tgl_booking_service" value="<?= date('Y-m-d') ?>" id="tgl_booking_service">                  
                                </div>
                                <div class="col-sm-2" id="div_tombol_booking">
                                    <a href="dealer/manage_booking/add" target="_blank" class="btn btn-sm btn-primary btn-flat">Manage Booking</a>
                                </div>
                            </div>
                        <!-- </form> -->
                      </div>
                    </div>
                  </div>
              </div>
            </div>
            <div class=" box-footer">
              <div class="col-sm-12" align="center">
                <button type="submit" id="submitBtn" name="simpan" value="simpan" class="btn btn-block btn-info btn-flat"><i class="fa fa-save"></i> Simpan</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>

    <!-- MODAL UNTUK HISTORY FOLLOW UP -->
    <!-- <form class="form-horizontal" action="dealer/h2_dealer_follow_up_customer_list/save_template" method="post"> -->
      <div class="modal fade" id="riwayatFolUp_<?=$deleteChar?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="exampleModalLabel">Riwayat Follow Up</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                  <div class="box-body">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">ID Follow Up</th>
                          <th scope="col">Tanggal Follow Up</th>
                          <th scope="col">Tanggal Next Follow Up</th>
                          <th scope="col">Media Komunikasi</th>
                          <th scope="col">Status Komunikasi</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                        <?php 
                        $no = 1;
                        foreach($historyFollowUp->result() as $row){ ?>
                        <tr>
                          <th scope="row"><?php echo $no++?></th>
                          <td><?php echo $row->id_follow_up?></td>
                          <td><?php echo $row->tgl_fol_up?></td>
                          <td><?php echo $row->tgl_next_fol_up?></td>
                          <td><?php echo $row->media_kontak?></td>
                          <td><?php echo $row->status_komunikasi?></td>
                        </tr>
                       <?php }?>
                      </tbody>
                    </table>
                  </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            
          </div>
        </div>
      </div>
    <!-- </form> -->
    <!-- AKHIR DARI MODAL HISTORY FOLLOW UP -->

    <!-- MODAL UNTUK ADD TEMPLATE PESAN WA -->
    <form class="form-horizontal" action="dealer/h2_dealer_follow_up_customer_list/save_template" method="post" id="new-notes-form">
      <div class="modal fade" id="modalTemplate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="exampleModalLabel">ADD TEMPLATE WA</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                  <div class="box-body">
                    <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                          <div> <p><b>Kategori Pesan</b></p></div>
                          <input type="text" class="form-control" name="kategori" id="kategori" value="WA" readonly>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <div> <p><b>Template Pesan *</b></p></div>
                          <input name="pesan" type="hidden">
                          <div id="editor"></div>
                        </div>
                      </div>
                    </div> 
                  </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Template</button>
            </div>
            
          </div>
        </div>
      </div>
    </form>
    <!-- AKHIR DARI MODAL TEMPLATE PESAN -->

    <!-- MODAL UNTUK EDIT TEMPLATE PESAN WA -->
    <form class="form-horizontal" action="dealer/h2_dealer_follow_up_customer_list/edit_template" method="post" id="new-notes-form-edit">
      <div class="modal fade" id="templateEdit_<?=$template_pesan->row()->id_template;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="exampleModalLabel">UPDATE TEMPLATE</h4>
              <input type="hidden" name="id_template" id="id_template" value="<?php echo $template_pesan->row()->id_template ?>">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <!--  -->
            <div class="modal-body">
            <?php
              if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
              ?>
                <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
                  <strong><?php echo $_SESSION['pesan'] ?></strong>
                  <button class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                  </button>
                </div>
              <?php
              }
              $_SESSION['pesan'] = '';

              ?>   
                  <div class="box-body">
                    <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                          <div> <p><b>Kategori Pesan</b></p></div>
                          <input type="text" class="form-control" name="kategori" id="kategori" value="<?php echo $template_pesan->row()->kategori ?>" readonly>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <div> <p><b>Template Pesan *</b></p></div>
                          <?php $pesan = form_error('pesan2') ? set_value('pesan2') : $template_pesan->row()->pesan ?>
                          <input name="pesan2" type="hidden" value="<?= html_escape($pesan) ?>">
                          <div id="editorEditTemplate"><?php echo $pesan ?></div>
                        </div>
                      </div>
                    </div> 
                  </div>
            </div>
            <!--  -->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Update Template</button>
            </div>
            
          </div>
        </div>
      </div>
    </form>
    <!-- AKHIR DARI MODAL TEMPLATE PESAN -->

    <!-- MODAL UNTUK ADD TEMPLATE PESAN Global-->
    <form class="form-horizontal" action="dealer/h2_dealer_follow_up_customer_list/save_template" method="post" id="new-notes-form-add-global">
      <div class="modal fade" id="modalTemplateGlobal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="exampleModalLabel">ADD TEMPLATE GLOBAL</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                  <div class="box-body">
                    <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                          <div> <p><b>Kategori Pesan</b></p></div>
                          <input type="text" class="form-control" name="kategori" id="kategori" value="Umum" readonly>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <div> <p><b>Template Pesan *</b></p></div>
                          <!-- <label for="exampleFormControlTextarea1">Example textarea</label> -->
                          <textarea class="form-control" id="pesan3" name="pesan" rows="3"></textarea>
                        </div>
                      </div>
                    </div> 
                  </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Template</button>
            </div>
            
          </div>
        </div>
      </div>
    </form>
    <!-- AKHIR DARI MODAL TEMPLATE PESAN -->

    <!-- MODAL UNTUK EDIT TEMPLATE PESAN GLOBAL -->
    <form class="form-horizontal" action="dealer/h2_dealer_follow_up_customer_list/edit_template" method="post" id="new-notes-form-edit-global">
      <div class="modal fade" id="templateEditGlobal_<?=$template_pesan_global->row()->id_template;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="exampleModalLabel">EDIT TEMPLATE</h4>
              <input type="hidden" name="id_template" id="id_template" value="<?php echo $template_pesan_global->row()->id_template ?>">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <!--  -->
            <div class="modal-body">
            <?php
              if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
              ?>
                <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
                  <strong><?php echo $_SESSION['pesan'] ?></strong>
                  <button class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                  </button>
                </div>
              <?php
              }
              $_SESSION['pesan'] = '';

              ?>   
                  <div class="box-body">
                    <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                          <div> <p><b>Kategori Pesan</b></p></div>
                          <input type="text" class="form-control" name="kategori" id="kategori" value="<?php echo $template_pesan_global->row()->kategori ?>" readonly>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <div> <p><b>Template Pesan *</b></p></div>
                          <textarea class="form-control" id="pesan4" name="pesan2" rows="3"> <?php echo $template_pesan_global->row()->pesan ?></textarea>
                        </div>
                      </div>
                    </div> 
                  </div>
            </div>
            <!--  -->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Edit Template</button>
            </div>
            
          </div>
        </div>
      </div>
    </form>
    <!-- AKHIR DARI MODAL TEMPLATE PESAN -->

    <?php 
      $data['data'] = ['riwayatServisCustomerH23'];
      $this->load->view('dealer/h2_api', $data); ?>
    <script>
      
      $('#chat-wa').hide();
      $('#templateEditWA').hide();
      $('#templateAddWA').hide();
      $('#templateEditGlobal').show();
      $('#templateAddGlobal').show();
      $('#div_booking').hide();
      // $('#div_booking2').hide();
      $('#label_booking').hide();
      $('#div_tombol_booking').hide();

      var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Silahkan Tuliskan Template Pesan',
      });

      var quill2 = new Quill('#editorEditTemplate', {
        theme: 'snow',
        // placeholder: 'Silahkan Tuliskan Template Pesan',
      });
      quill2.on('text-change', function(delta, oldDelta, source) {
        document.querySelector("input[name='pesan2']").value = quill2.root.innerHTML;
      });

      var form = document.querySelector('#new-notes-form');
          form.onsubmit = function() {
            // Populate hidden form on submit
            var pesan = document.querySelector('input[name=pesan]');
                        
            //comment.value = JSON.stringify(quill.getContents());
            pesan.value = quill.root.innerHTML;          
      };

        $(document).ready(function() {
          

            $('#id_media_kontak_fol_up').on('select2:select', function (e) {
              id_media_kontak_fol_up=$('#id_media_kontak_fol_up').val();
              if(id_media_kontak_fol_up=='3'){
                $('#chat-wa').show();
                $('#templateEditWA').show();
                $('#templateAddWA').show();
                $('#templateEditGlobal').hide();
                $('#templateAddGlobal').hide();
              }else{
                $('#chat-wa').hide();
                $('#templateEditWA').hide();
                $('#templateAddWA').hide();
                $('#templateEditGlobal').show();
                $('#templateAddGlobal').show();
              }
            });

            $('#bookingOption').change(function () {
                var optionSelected = $(this).find("option:selected");
                var valueSelected  = optionSelected.val();
                var textSelected   = optionSelected.text();
                if(valueSelected == '1'){
                  $('#div_booking').show();
                  $('#label_booking').show();
                  $('#div_tombol_booking').show();
                }else{
                  $('#div_booking').hide();
                  $('#label_booking').hide();
                  $('#div_tombol_booking').hide();
                }
            });
        });
      
        


      $('#datetime').datetimepicker({
          format: 'H:mm:ss'
      });
      const update_data = () => {
        var no_hp = $('#no_hp').val();
        var email = $('#email').val();
        if(no_hp.length >13){
          alert("No Hp tidak boleh melebihi 13 angka!");
          return false;
        }


        const emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;
        if (!emailRegex.test(email)) {
            alert('Alamat email tidak valid');
            return false; 
        }

        if(confirm('Yakin data akan diupdate?')){
          $.ajax({
                    type: "POST",
                    url: "<?= base_url('dealer/h2_dealer_follow_up_customer_list/update_data') ?>",
                    beforeSend: function(){
                          $('#btn_update').attr('disabled', true);
                          $('#btn_update').html('<i class="fa fa-spinner fa-spin">');},
                    // complete: function() { $('#loading_generate_data').hide(); }, 
                    dataType: "JSON",
                    data: {
                        no_hp: $('#no_hp').val(),
                        id_customer: $('#id_customer').val(),
                        email: $('#email').val(),
                        tujuan_penggunaan_motor: $('#tujuan_penggunaan_motor').val(),
                        tgl_lahir: $('#tgl_lahir').val(),
                    },
                    // cache: false,
                    success: function(Result) {
                        const {
                            status,
                            message,
                            data
                        } = Result;

                        if (status) {
                            alert('Data berhasil diupdate');
                        } else {
                            alert('Data gagal diupdate');
                        }
                        
                        $('#btn_update').attr('disabled', false);
                        $('#btn_update').html('<i class="fa fa-pencil"></i> Update Data');
                    },
                    error: function(x, y, z) {
                        alert('Data gagal di generate');
                        $('#btn_update').attr('disabled', false);
                        $('#btn_update').html('<i class="fa fa-pencil"></i> Update Data');
                    }
          });
        }     
      }
    </script>   
  <?php }elseif($set=="history"){?>
      <section class="content">
            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/h2_dealer_follow_up_customer_list" class="btn bg-blue btn-flat margin"><i class="fa fa-eye"></i> View Data</a>
                </h3>
                <div class="box-tools pull-right">
                  <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
              </div><!-- /.box-header -->
              <div class="box-body">   
              <?php if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {?>
            <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
              <strong><?php echo $_SESSION['pesan'] ?></strong>
              <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
              </button>
            </div>
        <?php } $_SESSION['pesan'] = ''; ?>     
          <div class="row">
            <div class="col-md-12">
              <table class="table table-striped table-bordered table-hover table-condensed" id="history_fu" style="width: 100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th style="width:150px">ID Follow Up</th>
                    <th style="width:150px">ID Customer</th>
                    <th>Nama</th>
                    <th>Media Komunikasi</th>
                    <th>Tanggal Follow Up</th>
                    <th>Tanggal Booking Service</th>
                    <th>Tanggal Actual Service</th>
                    <th>Tanggal Biaya Service</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
    </section>
    </html>

    <script>
          var table;
          $(document).ready(function() {
              table = $('#history_fu').DataTable({ 
                  "processing": true, 
                  "serverSide": true, 
                  "order": [], 
                  "ajax": {
                      "url": "<?php echo site_url('dealer/H2_dealer_follow_up_customer_list/getDataHistory')?>",
                      "type": "POST"
                  },
                  "columnDefs": [
                  { 
                      "targets": [ 0 ], 
                      "orderable": false, 
                  },
                  ], 
              });
          });
          
      </script>   
  <?php }elseif($set=="detail_history"){?>
    <section class="content">
      <div class="box box-default">
      
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/h2_dealer_follow_up_customer_list">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-arrow-left"></i> Kembali </button>
            </a>
          </h3>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <h2 class="box-title">
                    <h4><b>Profil Customer</b></h4>
                    <input type="hidden" name="id_follow_up" id="id_follow_up" value="<?php echo $getFUData->row()->id_follow_up ?>">
                    <input type="hidden" name="id_customer" id="id_customer" value="<?php echo $getFUData->row()->id_customer ?>">
                  </h2>
                    <div class="row">
                      <div class="col-sm-6">
                        <table class="table">
                          <tr>
                              <td>Nama</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->nama_pembawa ?></td>
                          </tr>
                          <tr>
                              <td>Umur</td>
                              <td>:</td>
                              <td> <?php echo $getFUData->row()->umur ?> Tahun </td>
                          </tr>
                          <tr>
                              <td>Asal</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->provinsi ?></td>
                          </tr> 
                          <tr>
                              <td>No.Mesin</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->no_mesin ?></td>
                          </tr> 
                          <tr>
                              <td>No.Rangka</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->no_rangka ?></td>
                          </tr> 
                          <tr>
                              <td>No.Plat</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->no_polisi ?></td>
                          </tr> 
                          <tr>
                              <td>Tahun Motor</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->tahun_produksi ?></td>
                          </tr> 
                        </table>
                      </div>
                      <div class="col-sm-6">
                        <table class="table">
                          <tr>
                              <td>No. HP </td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->hp_pembawa ?></td>
                          </tr>
                          <tr>
                              <td>Email</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->email ?></td>
                          </tr>
                          <tr>
                              <td>Pengguna Motor</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->nama_pembawa ?></td>
                          </tr>
                          <tr>
                              <td>Tujuan Penggunaan Motor</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->tujuan_penggunaan_motor ?> </td>
                          </tr> 
                          <tr>
                              <td>Last ToJ</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->deskripsi ?> ( <?php echo $getFUData->row()->tgl_servis ?> ) </td>
                          </tr> 
                          <tr>
                              <td>Pending Item</td>
                              <td>:</td>
                              <td> - </td>
                          </tr>  
                          <tr>
                              <td>Catatan SA/Mekanik</td>
                              <td>:</td>
                              <td><?php echo $getFUData->row()->saran_mekanik ?></td>
                          </tr>
                        </table>
                      </div>
                    </div>
                    
                    <?php 
                    $getData = $getFUData->row()->id_follow_up;
                    $deleteChar = str_replace(array("/"), '', $getData); 
                    ?>
                    <button type="button" name="edit" value="edit" class="btn btn-primary btn-sm btn-flat pull-left" data-toggle="modal" data-target="#riwayatFolUp_<?=$deleteChar?>" data-id="<?php echo $getFUData->row()->id_follow_up;?>"> Riwayat Follow Up</button>
                    <br>
                  <h2 class="box-title">
                    <h4><b>Riwayat Servis Pasca Follow Up</b></h4>
                  </h2>
                  <div class="row">
                      <div class="col-sm-6">
                        <table class="table">
                          <tr>
                              <td>Tanggal Booking Servis</td>
                              <td>:</td>
                              <td><?php echo $historyFollowUp->row()->tgl_booking_service ?></td>
                          </tr>
                          <tr>
                              <td>Tanggal Actual Service</td>
                              <td>:</td>
                              <td> <?php echo $historyFollowUp->row()->tgl_actual_service ?></td>
                          </tr>
                          <tr>
                              <td>Biaya Actual Service</td>
                              <td>:</td>
                              <td>Rp.<?php echo number_format($historyFollowUp->row()->biaya_actual_service,0,',','.') ?></td>
                          </tr> 
                          <tr>
                              <td>Status</td>
                              <td>:</td>
                              <td><?php echo $historyFollowUp->row()->status_fol_up ?></td>
                          </tr> 
                        </table>
                      </div>
                  </div>
              </div>
            </div>
        </div>
      </div>
    </section>

    <!-- MODAL UNTUK HISTORY FOLLOW UP -->
    <!-- <form class="form-horizontal" action="dealer/h2_dealer_follow_up_customer_list/save_template" method="post"> -->
      <div class="modal fade" id="riwayatFolUp_<?=$deleteChar?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="exampleModalLabel">Riwayat Follow Up</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                  <div class="box-body">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">ID Follow Up</th>
                          <th scope="col">Tanggal Follow Up</th>
                          <th scope="col">Tanggal Next Follow Up</th>
                          <th scope="col">Media Komunikasi</th>
                          <th scope="col">Status Komunikasi</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                        <?php 
                        $no = 1;
                        foreach($historyFollowUp->result() as $row){ ?>
                        <tr>
                          <th scope="row"><?php echo $no++?></th>
                          <td><?php echo $row->id_follow_up?></td>
                          <td><?php echo $row->tgl_fol_up?></td>
                          <td><?php echo $row->tgl_next_fol_up?></td>
                          <td><?php echo $row->media_kontak?></td>
                          <td><?php echo $row->status_komunikasi?></td>
                        </tr>
                       <?php }?>
                      </tbody>
                    </table>
                  </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            
          </div>
        </div>
      </div>
    <!-- </form> -->
    <!-- AKHIR DARI MODAL HISTORY FOLLOW UP -->
  <?php }?>
  </section>
</div>

  