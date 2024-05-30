<?php 
function bln(){
  $bulan=$bl=$month=date("m");
  switch($bulan)
  {
    case"1":$bulan="Januari"; break;
    case"2":$bulan="Februari"; break;
    case"3":$bulan="Maret"; break;
    case"4":$bulan="April"; break;
    case"5":$bulan="Mei"; break;
    case"6":$bulan="Juni"; break;
    case"7":$bulan="Juli"; break;
    case"8":$bulan="Agustus"; break;
    case"9":$bulan="September"; break;
    case"10":$bulan="Oktober"; break;
    case"11":$bulan="November"; break;
    case"12":$bulan="Desember"; break;
  }
  $bln = $bulan;
  return $bln;
}
?>
<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 25px;
  padding-left: 4px;
  padding-right: 4px;  
}
</style>
<base href="<?php echo base_url(); ?>" />
<body onload="auto()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Customer</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="ajukan"){
        $row=$dt_so->row();
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/claim_sales_program_d">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
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
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/claim_sales_program_d/ajukan" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id_dealer" value="<?= $row->id_dealer?>">
              <input type="hidden" name="id_program_md" value="<?= $row->program_umum?>">
              <input type="hidden" name="id_sales_order" value="<?= $row->id_sales_order?>">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="No Mesin" name="no_mesin" value="<?=$row->no_mesin?>" readonly>                                        
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Invoice</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" name="nama_konsumen" value="<?=$row->no_invoice?>">                    
                  </div>
                </div>                
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->tipe_ahm?>">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Invoice</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="" value="<?=$row->tgl_cetak_invoice?>">                    
                   </div>                  
                </div>                                  
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Warna" name="nama_konsumen" value="<?=$row->warna?>">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No BASTK</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->no_bastk?>">                    
                  </div>                  
                </div> 
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->nama_konsumen?>">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl BASTK</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->tgl_bastk?>">                    
                  </div>                  
                </div> 
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">No. PO Leasing</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="no_po_leasing" value="<?=$row->no_po_leasing?>">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl PO Leasing</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="tgl_po_leasing" value="<?=$row->tgl_po_leasing?>">                    
                  </div>                  
                </div> 
                <div class="form-group">                
                  <div class="col-md-12">
                    <button type="reset" class="btn btn-primary btn-flat btn-block" disabled="">Syarat dan Ketentuan</button>
                  </div><br><br><br>
                  <div class="col-md-offset-1 col-md-10">
                    <table class="table table-bordered table-condensed table-hover">
                      <thead>
                        <th style="text-align: center;width: 4%">No</th>
                        <th style="text-align: center;">Syarat</th>
                        <th  style="text-align: center;width: 8%">Checklist</th>
                      </thead>
                    <tbody>
                      <?php
                        //$row->program_umum = '181000024-SP-001'; //coba
                       $get_syarat = $this->db->query("SELECT * FROM tr_sales_program_syarat WHERE id_program_md='$row->program_umum' ");
                      if ($get_syarat->num_rows()>0) {
                        $no=1;
                         foreach ($get_syarat->result() as $key => $rs) { ?>
                           <tr>
                            <td><?=$no?></td>
                             <td><?=$rs->syarat_ketentuan?></td>
                             <td align="center">
                              <input type="checkbox" name="cek[]" value="<?=$rs->id?>">
                              <input type="hidden" name="id[]" value="<?=$rs->id?>" >
                             </td>
                           </tr>
                        <?php $no++; }
                       } ?>
                    </tbody>
                  </table>          
                  </div>
                </div>                                 
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-4">
                </div>
                <div class="col-sm-8">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="submit" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Simpan</button>           
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
   <?php 
    }elseif($set=="ulang"){
        $row=$dt_so->row();
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/claim_sales_program_d">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
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
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/claim_sales_program_d/ulang" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id_dealer" value="<?= $row->id_dealer?>">
              <input type="hidden" name="id_program_md" value="<?= $row->program_umum?>">
              <input type="hidden" name="id_sales_order" value="<?= $row->id_sales_order?>">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="No Mesin" name="no_mesin" value="<?=$row->no_mesin?>" readonly>                                        
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Invoice</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" name="nama_konsumen" value="<?=$row->no_invoice?>">                    
                  </div>
                </div>                
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->tipe_ahm?>">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Invoice</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="" value="<?=$row->tgl_cetak_invoice?>">                    
                   </div>                  
                </div>                                  
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Warna" name="nama_konsumen" value="<?=$row->warna?>">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No BASTK</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->no_bastk?>">                    
                  </div>                  
                </div> 
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->nama_konsumen?>">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No BASTK</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="" name="nama_konsumen" value="<?=$row->tgl_bastk?>">                    
                  </div>                  
                </div> 
                <div class="form-group">                
                  <div class="col-md-12">
                    <button type="reset" class="btn btn-primary btn-flat btn-block" disabled="">Syarat dan Ketentuan</button>
                  </div><br><br><br>
                  <div class="col-md-offset-1 col-md-10">
                    <table class="table table-bordered table-condensed table-hover">
                      <thead>
                        <th style="text-align: center;width: 4%">No</th>
                        <th style="text-align: center;">Syarat</th>
                        <th  style="text-align: center;width: 8%">Keterangan Reject</th>
                      </thead>
                    <tbody>
                      <?php
                        //$row->program_umum = '181000024-SP-001'; //coba
                   // //   $get_syarat = $this->db->query("aSELECT * FROM tr_claim_dealer_syarat 
                   //      left join tr_claim_dealer on tr_claim_dealer_syarat.id_claim=tr_claim_dealer_syarat.id_claim AND tr_claim_dealer.id_sales_order='$row->id_sales_order'
                   //      left join tr_sales_program_syarat on tr_claim_dealer_syarat.id_syarat_ketentuan=tr_sales_program_syarat.id
                   //      WHERE tr_claim_dealer.id_program_md='$row->program_umum' ");
                      $get_syarat=$this->db->query("SELECT *  from tr_claim_dealer inner join tr_claim_dealer_syarat on tr_claim_dealer.id_claim = tr_claim_dealer_syarat.id_claim 
                        inner join tr_sales_program_syarat on tr_claim_dealer_syarat.id_syarat_ketentuan=tr_sales_program_syarat.id
                        WHERE id_sales_order='$row->id_sales_order' AND tr_sales_program_syarat.id_program_md='$id_program_md'

                        ");
             
                      if ($get_syarat->num_rows()>0) {
                        $no=1;
                         foreach ($get_syarat->result() as $key => $rs) { ?>
                          <input type="hidden" name="id_claim" value="<?=$rs->id_claim?>">
                           <tr>
                            <td><?=$no?></td>
                             <td><?=$rs->syarat_ketentuan?></td>
                             <td align="center">
                              <!-- <input type="checkbox" name="cek[]" value="<?=$rs->id?>">
                              <input type="hidden" name="id[]" value="<?=$rs->id?>" > -->
                              <?php 
                                $cek_keterangan = $this->db->query("SELECT * FROM ms_alasan_reject WHERE id_alasan_reject='$rs->alasan_reject' ");
                                if ($cek_keterangan->num_rows()>0) {
                                  $keterangan = $cek_keterangan->row()->alasan_reject;
                                }else{
                                  $keterangan='';
                                }
                                echo "$keterangan";
                               ?>
                             </td>
                           </tr>
                        <?php $no++; }
                       } ?>
                    </tbody>
                  </table>          
                  </div>
                </div>                                 
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-4">
                </div>
                <div class="col-sm-8">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="submit" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Simpan</button>           
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box --> 
   
    <?php
    }elseif($set=="view"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="dealer/claim_sales_program_d/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a-->          
          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <!-- <th width="5%">No</th> -->
              <th>No Invoice</th>              
              <th>Tgl Invoice</th>
              <th>No BASTK</th>
              <th>Tanggal BASTK</th>
              <th>No PO Leasing</th>
              <th>Tanggal PO Leasing</th>
              <th>Nama Konsumen</th>
              <th>No Mesin</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>Tahun Produksi</th>
              <th>Program</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          $tgl = date('Y-m-d');
          foreach($dt_so->result() as $row) {     
            //$s = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor = '$row->ekspedisi'")->row();          
            $ms_kendaraan = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan'");
            $ms_kendaraan=$ms_kendaraan->num_rows()>0?$ms_kendaraan->row()->tipe_ahm:'';
            $ms_warna = $this->db->query("SELECT * FROM ms_warna WHERE id_warna = '$row->warna'")->row();
            $tahun = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin_spasi = '$row->no_mesin'");
            $tahun = $tahun->num_rows()>0?$tahun->row()->tahun_produksi:'';
          //  $nilai_piutang = $row->ahm + $row->other + $row->md + $row->dealer;
            $cek_tgl_sp = $this->db->query("SELECT * FROM tr_sales_program WHERE id_program_md='$row->program_umum' ");
            if ($cek_tgl_sp->num_rows()>0) {
              $sp = $cek_tgl_sp->row();
              $id_program_md = $sp->id_program_md;
              $tanggal_maks_bastk = $sp->tanggal_maks_bastk;
              $tanggal_maks_po = $sp->tanggal_maks_po;
            }else{
              $id_program_md = "";
              $tanggal_maks_bastk = "";
              $tanggal_maks_po = "";
            }

            $x = $getClaim = $this->db->query("SELECT * from tr_claim_dealer WHERE id_sales_order ='$row->id_sales_order' AND id_program_md='$id_program_md' AND id_dealer='$id_dealer'");
            if($getClaim->num_rows() >0) {
              $getClaim= $getClaim->row();
              $getClaim2 = $this->db->query("SELECT * from tr_claim_sales_program_detail
               -- WHERE id_claim_sp ='$getClaim->id_claim' 
                WHERE id_claim_dealer='$getClaim->id_claim' AND perlu_revisi = '1'");

              if ($getClaim->status=='rejected' AND $getClaim2->num_rows()>0) {
                $tombol= "<a href=\"dealer/claim_sales_program_d/ulang?id=$row->no_spk&id_program_md=$id_program_md\" class='btn btn-warning btn-xs btn-flat'>Ajukan Ulang</a>";
                // $tombol='';
              }elseif($getClaim->status=='approved'){
                $tombol='<span class="label label-success">Approved</span>';
              }elseif($getClaim2->num_rows() > 0){
                if ($getClaim->status!='ulang') {
                  $tombol= "<a href=\"dealer/claim_sales_program_d/ajukan?id=$row->no_spk&\" class='btn btn-primary btn-xs btn-flat'>Ajukan</a>";
                }
              }else{
                $tombol='';
              }
            }else{
              $tombol= "<a href=\"dealer/claim_sales_program_d/ajukan?id=$row->no_spk&id_program_md=$id_program_md\" class='btn btn-primary btn-xs btn-flat'>Ajukan</a>";
            }

            $tgl_bastk = explode(' ', $row->tgl_bastk);
            $tgl_bastk = strtotime($tgl_bastk[0]);

            if (strtotime($tanggal_maks_bastk) >= $tgl_bastk) {
              if ($row->jenis_beli=='Kredit') {
                if (strtotime($tanggal_maks_po) >= strtotime($row->tgl_po_leasing)) {
                  $x=1;
                }else{
                  $x=0;
                }
              }else{
                $x=1;
              }
              if ($x>0 AND $tombol != '<span class="label label-success">Approved</span>') {
                echo "
                <tr>
                  <td>$row->no_invoice</td>
                  <td>$row->tgl_cetak_invoice</td>
                  <td>$row->no_bastk</td>
                  <td>$row->tgl_bastk</td>
                  <td>$row->no_po_leasing</td>
                  <td>$row->tgl_po_leasing</td>
                  <td>$row->nama_konsumen</td> 
                  <td>$row->no_mesin</td> 
                  <td>$ms_kendaraan</td>                            
                  <td>$ms_warna->warna</td>
                  <td>$tahun</td>
                  <td>$id_program_md</td>
                  <td>$tombol</td>
                </tr>
                ";
              }
            }
            
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
    }
    ?>
  </section>
</div>
<script type="text/javascript">
function aduto(){
  var tgl_js=document.getElementById("tgl").value; 
  $.ajax({
      url : "<?php echo site_url('dealer/claim_sales_program_d/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_claim_sales_program").val(data[0]);                
        $("#id_customer").val(data[1]);                
      }        
  })
}
function take_sales(){
  var id_karyawan_dealer = $("#id_karyawan_dealer").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/claim_sales_program_d/take_sales')?>",
      type:"POST",
      data:"id_karyawan_dealer="+id_karyawan_dealer,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");          
          //$("#no_polisi").html(msg);                                                    
          $("#kode_sales").val(data[0]);                                                    
          $("#nama_sales").val(data[1]);                                                    
      } 
  })
}
</script>