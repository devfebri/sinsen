<script src="assets/panel/plugins/datatables/jquery.dataTables.min.js"></script>
<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 30px;  
  padding-left: 5px;
  padding-right: 5px;  
  margin-right: 0px; 
}
.isi_combo{   
  height: 30px;
  border:1px solid #ccc;
  padding-left:1.5px;
}
</style>
<base href="<?php echo base_url(); ?>" />
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Biro Jasa</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    <?php 
    if($set=="cetak"){      
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/entry_stnk">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
              <div class="box-body"> 
                <form class="form-horizontal" action="h1/entry_stnk/cetak_stnk" method="post" enctype="multipart/form-data">              
                  <button type="button" onclick="javascript:wincal=window.open('h1/entry_stnk/cetak_stnk','Print','width=600,height=400');" class="btn btn-primary btn-flat pull-right"><i class="fa fa-print"></i> Print STNK</button>          
                  <table id="" class="table table-bordered table-hover">
                    <thead>
                      <tr bgcolor="red">              
                        <th width="5%">No</th>                          
                        <th>Nama Dealer</th>
                        <th>Nama Konsumen</th>
                        <th>No Mesin</th>                 
                        <th>No Polisi</th>
                        <th>No STNK</th>                      
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $no=1;
                      $id_user = $this->session->userdata("id_user");
                      $sql = $this->db->query("SELECT * FROM tr_entry_stnk 
                        inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
        inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
        inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
                        WHERE tr_entry_stnk.updated_by = '$id_user' AND tr_entry_stnk.print_stnk = 'printable'");
                      foreach ($sql->result() as $row) {
                        echo "
                          <tr>
                            <td>$no</td>
                            <td>$row->nama_dealer</td>
                            <td>$row->nama_konsumen</td>
                            <td>$row->no_mesin</td>
                            <td>$row->no_plat</td>
                            <td>$row->no_stnk</td>
                          </tr>
                        ";
                        $no++;
                      }
                      ?>
                    </tbody>
                  </table>
                </form>
                <form class="form-horizontal" action="h1/entry_stnk/save" method="post" enctype="multipart/form-data">              
                  <button type="button" onclick="javascript:wincal=window.open('h1/entry_stnk/cetak_plat','Print','width=600,height=400');" class="btn btn-primary btn-flat pull-right"><i class="fa fa-print"></i> Print PLAT</button>          
                  <table id="" class="table table-bordered table-hover">
                    <thead>
                      <tr bgcolor="yellow">              
                        <th width="5%">No</th>
                        <th>Nama Dealer</th>
                        <th>Nama Konsumen</th>
                        <th>No Mesin</th>
                        <th>No Polisi</th>                      
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $no=1;
                      $id_user = $this->session->userdata("id_user");
                      $sql = $this->db->query("SELECT * FROM tr_entry_stnk 
                        inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
        inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
        inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
                       WHERE tr_entry_stnk.updated_by = '$id_user' AND tr_entry_stnk.print_plat = 'printable'");
                      foreach ($sql->result() as $row) {
                        echo "
                          <tr>
                            <td>$no</td>
                            <td>$row->nama_dealer</td>
                            <td>$row->nama_konsumen</td>
                            <td>$row->no_mesin</td>
                            <td>$row->no_plat</td>
                          </tr>
                        ";
                        $no++;
                      }
                      ?>
                    </tbody>
                  </table>
                </form>
                <form class="form-horizontal" action="h1/entry_stnk/save" method="post" enctype="multipart/form-data">              
                  <button type="button" onclick="javascript:wincal=window.open('h1/entry_stnk/cetak_bpkb','Print','width=600,height=400');" class="btn btn-primary btn-flat pull-right"><i class="fa fa-print"></i> Print BPKB</button>          
                  <table id="" class="table table-bordered table-hover">
                    <thead>
                      <tr bgcolor="green">              
                        <th width="5%">No</th>                          
                        <th>Nama Dealer</th>
                        <th>Nama Konsumen</th>
                        <th>No Mesin</th>                 
                        <th>No Polisi</th>
                        <th>No BPKB</th>                      
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $no=1;
                      $id_user = $this->session->userdata("id_user");
                      $sql = $this->db->query("SELECT * FROM tr_entry_stnk 
                        inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
                        inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
                        inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
                        WHERE tr_entry_stnk.updated_by = '$id_user' AND tr_entry_stnk.print_bpkb = 'printable'");
                      foreach ($sql->result() as $row) {
                        echo "
                          <tr>
                            <td>$no</td>
                            <td>$row->nama_dealer</td>
                            <td>$row->nama_konsumen</td>
                            <td>$row->no_mesin</td>
                            <td>$row->no_plat</td>
                            <td>$row->no_bpkb</td>
                          </tr>
                        ";
                        $no++;
                      }
                      ?>
                    </tbody>
                  </table>
                </form>
              </div><!-- /.box-body -->              
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
          <a href="h1/entry_stnk/history">            
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
          </a>                       
          <a href="h1/entry_stnk/generate">            
            <button class="btn btn-success btn-flat margin"><i class="fa fa-list"></i> Generate</button>
          </a>                       
          <a href="h1/entry_stnk/cetak_ulang">            
            <button class="btn btn-primary btn-flat margin"><i class="fa fa-print"></i> Cetak Ulang</button>
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
      <!-- <form class="form-horizontal" method="GET">
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Mohon Samsat</label>
          <div class="col-sm-4">
            <input type="text" class="form-control datepicker" name="tgl_mohon_samsat" value="<?= isset($tgl_mohon_samsat1)?$tgl_mohon_samsat1:'' ?>" autocomplete="off">
          </div>
          <div class="col-sm-1">
            <button class="btn btn-primary btn-flat">Filter</button>
          </div>
        </div>
      </form> -->
      <form class="form-horizontal" method="GET" action="h1/entry_stnk/index_cari">
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Mohon Samsat Awal</label>
          <div class="col-sm-2">
            <input type="text" class="form-control datepicker" name="tgl_mohon_samsat1" value="<?= isset($tgl_mohon_samsat1)?$tgl_mohon_samsat1:'' ?>" autocomplete="off">
          </div>
          <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Mohon Samsat Akhir</label>
          <div class="col-sm-2">
            <input type="text" class="form-control datepicker" name="tgl_mohon_samsat2" value="<?= isset($tgl_mohon_samsat2)?$tgl_mohon_samsat2:'' ?>" autocomplete="off">
          </div>          
        </div>
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
          <div class="col-sm-2">
            <input type="text" class="form-control" name="no_mesin" value="<?= isset($no_mesin)?$no_mesin:'' ?>" autocomplete="off">
          </div>          
          <div class="col-sm-1">
            <button type="submit" class="btn btn-primary btn-flat">Filter</button>
          </div>
        </div>
      </form>

      <?php
        if($dt_bbn->num_rows() >=1200){
      ?>
          <div class="col-sm-12">
            <button type="button" class="btn btn-danger btn-flat">Perhatian! Data bisa gagal disimpan karena data yang difilter melebihi 1200 data. Silahkan direkap terlebih dahulu dan jika diperlukan dibantu oleh IT untuk mengupdate data.</button>
          </div>
          <br>
          <br>
      <?php
        }
      ?>

      <form class="form-horizontal" action="h1/entry_stnk/save" method="post" enctype="multipart/form-data">                      
        <table id="example8" class="table table-bordered table-hovered">
          <thead>
            <tr>              
              <th width="5%">No</th>    
              <th>Tgl Mohon Samsat</th>             
              <th>Nama Dealer</th>         
              <th>No Mesin</th>
              <th>No Rangka</th>
              <th>Nama Konsumen</th>
              <th>Tipe</th>                 
              <th>Warna</th>
              <th style="width: 8%">No STNK</th>
              <th style="width: 10%">No Polisi</th>
              <th style="width: 10%">Plat</th>
              <th style="width: 8%">No BPKB</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          $cek_b="";$cek_s="";$cek_p="";$jum=0;
          foreach($dt_bbn->result() as $row){   
            $jum = $dt_bbn->num_rows();                                                  
            $cek = $this->m_admin->getByID("tr_entry_stnk","no_mesin",$row->no_mesin);
            if($cek->num_rows() > 0){
              $m = $cek->row();
              $cek_p = ($m->no_plat != "" && $m->print_plat!='input') ? "readonly" : "" ;  
              $cek_s = ($m->no_stnk != "" && $m->print_stnk!='input') ? "readonly" : "" ;  
              $cek_b = ($m->no_bpkb != "" && $m->print_bpkb!='input') ? "readonly" : "" ;   
              $cek_o = ($m->no_pol != "" && $m->print_stnk!='input') ? "readonly" : "" ;
           
              $stnk = "value='$m->no_stnk'";                         
              $bpkb = "value='$m->no_bpkb'";   

              if($m->no_plat != ""){
                $plat_asli = explode(" ", $m->no_plat);
                $no_plat1 = $plat_asli[1];
                $no_plat2 = $plat_asli[2];
                $plat1 = "value='$no_plat1'";
                $plat2 = "value='$no_plat2'";
              }else{
                $plat1 = "value=''";
                $plat2 = "value=''";
              }
       
              if($m->no_pol != ""){
                $pol_asli = explode(" ", $m->no_pol);
                $no_pol1 = $pol_asli[1];
                $no_pol2 = $pol_asli[2];
                $pol1 = "value='$no_pol1'";
                $pol2 = "value='$no_pol2'";
              }else{
                $pol1 = "value=''";
                $pol2 = "value=''";
              }
            }else{
              $cek_s = "";              
              $stnk = "";
              $cek_p = "";
              $cek_o = "";
              $bpkb = "";
              $cek_b = "";
              $plat = "value=''";
              $pol = "value=''";
              $pol1 = "value=''";
              $pol2 = "value=''";
              $plat1 = "value=''";
              $plat2 = "value=''";
            }
            $gettipe =$this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan='$row->id_tipe_kendaraan'")->row()->deskripsi_ahm;
            $tgl_mohon_samsat = date('d-m-Y', strtotime($row->tgl_mohon_samsat));
            echo "          
            <tr>
              <td>$no</td>
              <td>$tgl_mohon_samsat</td>
              <td>$row->nama_dealer</td>
              <td>$row->no_mesin</td>                           
              <td>$row->no_rangka</td>                           
              <td>$row->nama_konsumen</td>                           
              <td>$gettipe</td>                           
              <td>$row->id_warna</td>                           
              <td>  
                <input type='hidden' value='$jum' name='jum' id='jum'>
                <input type='hidden' value='$row->no_mesin' name='no_mesin_$no'>
                <input type='hidden' value='$row->no_rangka' name='no_rangka_$no'>
                <input type='hidden' value='$row->nama_konsumen' name='nama_konsumen_$no'>
                <input type='hidden' value='$row->id_tipe_kendaraan' name='id_tipe_kendaraan_$no'>
                <input type='hidden' value='$row->id_warna' name='id_warna_$no'>
                <input type='hidden' value='$row->notice_pajak' name='notice_pajak_$no'>                
                <input style='min-width:100px' $stnk type=\"text\" autocomplete=\"off\" class=\"form-control isi\" $cek_s name=\"no_stnk_$no\" id=\"no_stnk_$no\">
              </td>                           
              <td>                
                <span id='pol_$no'></span>
                <input style='max-width:50px' $pol1 type=\"text\" autocomplete=\"off\" class=\"form-control isi\" $cek_o name=\"no_pol_$no\" id=\"no_pol_$no\" style='width:60%' i ='$no' maxlength='4'>
                <input style='max-width:40px' $pol2 type=\"text\" autocomplete=\"off\" class=\"form-control isi\" $cek_o name=\"b_pol_$no\" id=\"b_pol_$no\" style='width:35%' maxlength='2' onkeyup=\"this.value = this.value.toUpperCase()\">
              </td> 
              <td>                
                <span id='plat_$no'></span>
                <input style='max-width:50px' $plat1 type=\"text\" autocomplete=\"off\" class=\"form-control isi\" $cek_p name=\"no_plat_$no\" id=\"no_plat_$no\" style='width:60%' i ='$no' maxlength='4'>
                <input style='max-width:40px' $plat2 type=\"text\" autocomplete=\"off\" class=\"form-control isi\" $cek_p name=\"b_plat_$no\" id=\"b_plat_$no\" style='width:35%' maxlength='2' onkeyup=\"this.value = this.value.toUpperCase()\">
              </td>                           
              <td>                
                <input style='min-width:100px' $bpkb type=\"text\" autocomplete=\"off\" class=\"form-control isi\" $cek_b name=\"no_bpkb_$no\" id=\"no_bpkb_$no\">
              </td>";                                      
          $no++;
          }
          ?>
          <?php 
          //$no=1; 
          $cek_b="";$cek_s="";$cek_p="";
          $dt_bbn_bantuan = $this->db->query("SELECT * FROM tr_bantuan_bbn
                    INNER JOIN tr_proses_bbn_detail on tr_bantuan_bbn.no_mesin=tr_proses_bbn_detail.no_mesin 
                    WHERE (tr_proses_bbn_detail.status_stnk = '' OR tr_proses_bbn_detail.status_bpkb = '' OR tr_proses_bbn_detail.status_plat = ''
        OR tr_proses_bbn_detail.status_stnk IS NULL OR tr_proses_bbn_detail.status_bpkb IS NULL OR tr_proses_bbn_detail.status_plat IS NULL)
            ");
          $jum = $dt_bbn_bantuan->num_rows()+$jum;
          foreach($dt_bbn_bantuan->result() as $row){   
                                                              
            $cek = $this->m_admin->getByID("tr_entry_stnk","no_mesin",$row->no_mesin);
            if($cek->num_rows() > 0){
              $m = $cek->row();
              $cek_p = ($m->no_plat != "" && $m->print_plat!='input') ? "readonly" : "" ;  
              $cek_s = ($m->no_stnk != "" && $m->print_stnk!='input') ? "readonly" : "" ;  
              $cek_b = ($m->no_bpkb != "" && $m->print_bpkb!='input') ? "readonly" : "" ;   
              $cek_o = ($m->no_pol != "" && $m->print_stnk!='input') ? "readonly" : "" ;
           
              $stnk = "value='$m->no_stnk'";                         
              $bpkb = "value='$m->no_bpkb'";   

              if($m->no_plat != ""){
                $plat_asli = explode(" ", $m->no_plat);
                $no_plat1 = $plat_asli[1];
                $no_plat2 = $plat_asli[2];
                $plat1 = "value='$no_plat1'";
                $plat2 = "value='$no_plat2'";
              }else{
                $plat1 = "value=''";
                $plat2 = "value=''";
              }
         
              if($m->no_pol != ""){
                $pol_asli = explode(" ", $m->no_pol);
                $no_pol1 = $pol_asli[1];
                $no_pol2 = $pol_asli[2];
                $pol1 = "value='$no_pol1'";
                $pol2 = "value='$no_pol2'";
              }else{
                $pol1 = "value=''";
                $pol2 = "value=''";
              }             
            }else{
              $cek_s = "";
              $stnk = "";
              $cek_p = "";
              $bpkb = "";
              $cek_b = "";
              $plat = "value=''";
              $pol = "value=''";
            }
            $gettipe =$this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan='$row->id_tipe_kendaraan'")->row()->deskripsi_ahm;
            $tgl_mohon_samsat = date('d-m-Y', strtotime($row->tgl_samsat));
            echo "          
            <tr>
              <td>$no</td>
              <td>$tgl_mohon_samsat</td>
              <td>$row->pemohon</td>                           
              <td>$row->no_mesin</td>                           
              <td>$row->no_rangka</td>                           
              <td>$row->nama_konsumen</td>                           
              <td>$gettipe</td>                           
              <td>$row->id_warna</td>                           
              <td>  
                <input type='hidden' value='$jum' name='jum' id='jum'>
                <input type='hidden' value='$row->no_mesin' name='no_mesin_$no'>
                <input type='hidden' value='$row->no_rangka' name='no_rangka_$no'>
                <input type='hidden' value='$row->nama_konsumen' name='nama_konsumen_$no'>
                <input type='hidden' value='$row->id_tipe_kendaraan' name='id_tipe_kendaraan_$no'>
                <input type='hidden' value='$row->id_warna' name='id_warna_$no'>
                <input type='hidden' value='$row->notice_pajak' name='notice_pajak_$no'>                
                <input style=\"min-width:100px\" $stnk type=\"text\" autocomplete=\"off\" class=\"form-control isi\" $cek_s name=\"no_stnk_$no\" id=\"no_stnk_$no\">
              </td>                           
              <td>                
                <span id='pol_$no'></span>
                <input style=\"max-width:50px\" $pol1 type=\"text\" autocomplete=\"off\" class=\"form-control isi\" $cek_o name=\"no_pol_$no\" id=\"no_pol_$no\" style='width:60%' i ='$no' maxlength='4'>
                <input style=\"max-width:40px\" $pol2 type=\"text\" autocomplete=\"off\" class=\"form-control isi\" $cek_o name=\"b_pol_$no\" id=\"b_pol_$no\" style='width:35%' maxlength='2' onkeyup=\"this.value = this.value.toUpperCase()\">
              </td> 
              <td>                
                <span id='plat_$no'></span>
                <input style=\"max-width:50px\" $plat1 type=\"text\" autocomplete=\"off\" class=\"form-control isi\" $cek_p name=\"no_plat_$no\" id=\"no_plat_$no\" style='width:60%' i ='$no' maxlength='4'>
                <input style=\"max-width:40px\" $plat2 type=\"text\" autocomplete=\"off\" class=\"form-control isi\" $cek_p name=\"b_plat_$no\" id=\"b_plat_$no\" style='width:35%' maxlength='2' onkeyup=\"this.value = this.value.toUpperCase()\">
              </td>                           
              <td>                
                <input style=\"min-width:100px\" $bpkb type=\"text\" autocomplete=\"off\" class=\"form-control isi\" $cek_b name=\"no_bpkb_$no\" id=\"no_bpkb_$no\">
              </td>";                                      
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
      <div class="box-footer">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-10">                  
          <button type="submit" onclick="return confirm('Are you sure to send all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-send"></i> Send to MD</button>
          <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
        </div>
      </div><!-- /.box-footer -->
    </div><!-- /.box -->
    </form>

    </div><!-- /.box -->
    <?php
    }elseif($set=="view_new"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">          
          <a href="h1/entry_stnk/history">            
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
          </a>                       
          <a href="h1/entry_stnk/generate">            
            <button class="btn btn-success btn-flat margin"><i class="fa fa-list"></i> Generate</button>
          </a>                       
          <a href="h1/entry_stnk/cetak_ulang">            
            <button class="btn btn-primary btn-flat margin"><i class="fa fa-print"></i> Cetak Ulang</button>
          </a>    
          <a href="h1/entry_stnk/edit_data">
            <button class="btn btn-warning btn-flat margin"><i class="fa fa-pencil"></i> Edit Data</button>
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
      <form class="form-horizontal" method="GET" action="h1/entry_stnk/index_cari">
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Mohon Samsat Awal</label>
          <div class="col-sm-2">
            <input type="text" class="form-control datepicker" name="tgl_mohon_samsat1" value="<?= isset($tgl_mohon_samsat)?$tgl_mohon_samsat:'' ?>" autocomplete="off">
          </div>
          <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Mohon Samsat Akhir</label>
          <div class="col-sm-2">
            <input type="text" class="form-control datepicker" name="tgl_mohon_samsat2" value="<?= isset($tgl_mohon_samsat2)?$tgl_mohon_samsat2:'' ?>" autocomplete="off">
          </div>          
        </div>
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
          <div class="col-sm-2">
            <input type="text" class="form-control" name="no_mesin" value="<?= isset($no_mesin)?$no_mesin:'' ?>" autocomplete="off">
          </div>          
          <div class="col-sm-1">
            <button type="submit" class="btn btn-primary btn-flat">Filter</button>
          </div>
        </div>
      </form>
    
    <?php
    }elseif($set=='edit_data'){
    ?>

      <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">          
              <a href="h1/entry_stnk">            
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
            
            <div id="row2" class="row">
              <form action="h1/entry_stnk/edit_data" method="get">
                <div class="col-sm-1">
                  <label>No. Mesin</label>
                </div>
                <div class="col-sm-4">
                  <!-- <select name="dealer" id="dealer" class="form-control select2"> -->
                  <input type="text" id="no_mesin" name="no_mesin" placeholder="Cari No Mesin" class="form-control">
                    </select>	
                </div>
                <div class="col-sm-2">
                  <button type="submit" name="set" value="filter" class="btn btn-flat btn-info">Cari</button>
                </div>
              </form>
            </div>
          </div>
      </div>

      <?php if ($status_search) {?>
        <?php if ($data_search) { ?>
          <div class="box">
              <div class="box-header with-border">
                <h4 class="box-title">
                    <p class="h4" style="margin:15px; text-align:justify;"><b>No. Mesin : <?= $this->input->get('no_mesin') ?> </b></p>               
                </h4>
              </div>
      
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">            
                      <!-- <form class="form-horizontal" action="h1/entry_stnk/update_data" method="post" enctype="multipart/form-data">               -->
                          <div class="form-group">                  
                            <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                            <div class="col-sm-4">
                              <input type="text" name="konsumen" value="<?php echo $data_search->nama_dealer ?>" readonly placeholder="Nama Konsumen" class="form-control">
                            </div>                  
                            <label for="inputEmail3" class="col-sm-2 control-label">No STNK</label>
                            <?php if($data_search->no_stnk!=''){?>
                              <div class="col-sm-4">
                                <input type="text" name="no_stnk" id='no_stnk' value="<?php echo $data_search->no_stnk ?>" class="form-control">
                              </div> 
                            <?php }else{?>
                              <div class="col-sm-4">
                                <input type="text" name="no_stnk" value="<?php echo $data_search->no_stnk ?>" readonly placeholder="No STNK"class="form-control">
                              </div> 
                            <?php }?>    
                          </div>
                          <br>
                          <br>
                          <div class="form-group">                  
                            <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                            <div class="col-sm-4">
                              <input type="text" name="konsumen" value="<?php echo $data_search->nama_konsumen ?>" readonly placeholder="Nama Konsumen" class="form-control">
                            </div>                  
                            <label for="inputEmail3" class="col-sm-2 control-label">No BPKB</label>
                            <?php if($data_search->no_bpkb!=''){?>
                              <div class="col-sm-4">
                                <input type="text" name="no_bpkb" id='no_bpkb' value="<?php echo $data_search->no_bpkb ?>" placeholder="No BPKB" class="form-control">
                              </div> 
                            <?php }else{?>
                              <div class="col-sm-4">
                                <input type="text" name="no_bpkb" value="<?php echo $data_search->no_bpkb ?>" placeholder="No BPKB" readonly class="form-control">
                              </div>
                            <?php }?>    
                          </div>
                          <br>
                          <br>
                          <div class="form-group">                  
                            <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                            <div class="col-sm-4">
                              <input type="text" name="no_mesin" value="<?php echo $data_search->no_mesin ?>" readonly placeholder="No Mesin" class="form-control">
                            </div>                  
                            <label for="inputEmail3" class="col-sm-2 control-label">No Plat</label>
                            <?php if($data_search->no_plat!=''){?>
                              <div class="col-sm-4">
                                <input type="text" name="no_plat" id='no_plat' value="<?php echo $data_search->no_plat ?>" placeholder="No Plat" class="form-control">
                              </div> 
                            <?php }else{?>
                              <div class="col-sm-4">
                                <input type="text" name="no_plat" value="<?php echo $data_search->no_plat ?>" placeholder="No Plat" readonly class="form-control">
                              </div>
                            <?php }?>         
                          </div>
                          <br>
                          <br>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                            <div class="col-sm-4"> 
                              <input type="text" name="no_rangka" value="<?php echo $data_search->no_rangka ?>" readonly placeholder="No Rangka" class="form-control">
                            </div> 
                            <label for="inputEmail3" class="col-sm-2 control-label">No Polisi</label>
                            <?php if($data_search->no_pol!=''){?>
                              <div class="col-sm-4">
                                <input type="text" name="no_pol" id='no_pol' value="<?php echo $data_search->no_pol ?>" placeholder="No Polisi" class="form-control">
                              </div>     
                            <?php }else{?>
                              <div class="col-sm-4">
                              <input type="text" name="no_pol" value="<?php echo $data_search->no_pol ?>" placeholder="No Polisi" readonly class="form-control">
                              </div>
                            <?php }?>                                           
                          </div>
                          <br>
                          <br>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Notice Pajak</label>
                            <?php if($data_search->notice_pajak!='' and $data_search->notice_pajak!='0'){?>
                              <div class="col-sm-4">
                                <input type="text" name="notice_pajak" id='notice_pajak' value="<?php echo $data_search->notice_pajak?>" class="form-control">
                              </div> 
                            <?php }else{?>
                              <div class="col-sm-4">
                                <input type="text" name="notice_pajak" id='notice_pajak' value="<?php echo $data_search->notice_pajak ?>" readonly class="form-control">
                              </div> 
                            <?php }?>                             
                          </div>

                          <div class="col-md-12 bg-light text-right">
                              <button type="submit" name="set" value="update" class="btn1 btn btn-primary btn-md" data-no="<?= $this->input->get('no_mesin') ?>">Update Data</button>
                          </div>
                      <!-- </form> -->
                    </div>
                  </div>  
              </div>
          </div>
        <?php }else{?>
          <h4>No Mesin Tidak Ditemukan</h4>
        <?php }?>
      <?php }?>    

    <?php
    }elseif($set=="history"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">          
          <a href="h1/entry_stnk">            
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>                          
              <th>No Serah Terima</th>
              <!-- <th>No Serah Terima</th> -->
              <!-- <th>No Mesin</th>                 
              <th>No Polisi</th>
              <th>No STNK/No BPKB</th>                       -->
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php 
          $no=1;
          $id_user = $this->session->userdata("id_user");
          $sql = $this->db->query("SELECT * FROM tr_entry_stnk 
            inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
            inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
            inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
            WHERE (tr_entry_stnk.print_stnk = 'ya' OR tr_entry_stnk.print_bpkb = 'ya' OR tr_entry_stnk.print_plat = 'ya') GROUP BY no_serah_terima");
          foreach ($sql->result() as $row) {
            if($row->no_stnk != ''){
              $no_stnk = $row->no_stnk;
              $cek_stnk = "";
            }else{
              $no_stnk = "";
              $cek_stnk = "style='display:none;'";                  
            } 

            if($row->no_bpkb != ''){
              $no_bpkb = $row->no_bpkb;
              $cek_bpkb = "";
            }else{
              $no_bpkb = "";
              $cek_bpkb = "style='display:none;'";                  
            } 

            if($row->no_plat != ''){              
              $cek_plat = "";
            }else{              
              $cek_plat = "style='display:none;'";                  
            } 

            echo "
              <tr>
                <td>$no</td>
                <td>$row->no_serah_terima</td>
                
                <td>"; 


                ?>
                  <button type='button' <?php echo $cek_bpkb ?> onclick="javascript:wincal=window.open('h1/entry_stnk/cetak_bpkb?id=<?php echo $row->no_mesin ?>','Print','width=600,height=400');" class="btn btn-primary btn-sm btn-flat pull-right"><i class="fa fa-print"></i> BPKB</button>
                  <button type='button' <?php echo $cek_plat ?> onclick="javascript:wincal=window.open('h1/entry_stnk/cetak_plat?id=<?php echo $row->no_mesin ?>','Print','width=600,height=400');" class="btn bg-maroon btn-sm btn-flat pull-right"><i class="fa fa-print"></i> Plat</button>
                  <button type='button' <?php echo $cek_stnk ?> onclick="javascript:wincal=window.open('h1/entry_stnk/cetak_stnk?id=<?php echo $row->no_mesin ?>','Print','width=600,height=400');" class="btn btn-success btn-sm btn-flat pull-right"><i class="fa fa-print"></i> STNK</button>
                </td>
              </tr>
            <?php
            $no++;
          }
          ?>
        </tbody>
          
        </table>
      </div><!-- /.box-body -->      
    </div><!-- /.box -->  

    <?php
    }elseif($set=="cetak_ulang"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">          
          <a href="h1/entry_stnk">            
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
        <form class="form-horizontal" action="h1/entry_stnk/cetak_stnk" method="GET" target="_BLANK" enctype="multipart/form-data">
          <div class="box-body">                                                              
            <div class="form-group">                  
              <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>                  
              <div class="col-sm-3">                
                <input type="text" name="id" class="form-control" required placeholder="No Mesin" autocomplete="off">
              </div>                                                                                 
              <div class="col-sm-2">
                <button type="submit" name="process" value="stnk_cetak" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-print"></i> Cetak STNK</button>                                                      
              </div>                             
            </div>                
          </div><!-- /.box-body -->                        
        </form>
        <form class="form-horizontal" action="h1/entry_stnk/cetak_plat" method="GET" target="_BLANK" enctype="multipart/form-data">
          <div class="box-body">                                                              
            <div class="form-group">                  
              <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>                  
              <div class="col-sm-3">                
                <input type="text" name="id" class="form-control" required placeholder="No Mesin" autocomplete="off">
              </div>                                                                                 
              <div class="col-sm-2">
                <button type="submit" name="process" value="plat_cetak" class="btn btn-warning btn-block btn-flat"><i class="fa fa-print"></i> Cetak PLAT</button>                                                      
              </div>                             
            </div>                
          </div><!-- /.box-body -->                        
        </form>
        <form class="form-horizontal" action="h1/entry_stnk/cetak_bpkb" method="GET" target="_BLANK" enctype="multipart/form-data">
          <div class="box-body">                                                              
            <div class="form-group">                  
              <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>                  
              <div class="col-sm-3">                
                <input type="text" name="id" class="form-control" required placeholder="No Mesin" autocomplete="off">
              </div>                                                                                 
              <div class="col-sm-2">
                <button type="submit" name="process" value="bpkb_cetak" class="btn btn-success btn-block btn-flat"><i class="fa fa-print"></i> Cetak BPKB</button>                                                      
              </div>                             
            </div>                
          </div><!-- /.box-body -->                        
        </form>
      </div><!-- /.box-body -->      
    </div><!-- /.box --> 
    
    <?php
    }elseif($set=="generate"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">          
          <a href="h1/entry_stnk">            
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
        <form class="form-horizontal" action="h1/entry_stnk/generateSave" method="POST" enctype="multipart/form-data">
            <div class="box-header">
                <div class="col-sm-12">                  
                    <p>
                        Tandai No Mesin di bawah ini untuk mencetak Tanda Serah Terima.
                    </p>
                  <button type="submit" onclick="return confirm('Are you sure to generate STNK?')" name="save" value="stnk" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Generate STNK</button>
                  <button type="submit" onclick="return confirm('Are you sure to generate BPKB?')" name="save" value="bpkb" class="btn btn-warning btn-flat"><i class="fa fa-save"></i> Generate BPKB</button>
                  <button type="submit" onclick="return confirm('Are you sure to generate PLAT?')" name="save" value="plat" class="btn btn-danger btn-flat"><i class="fa fa-save"></i> Generate PLAT</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Reset</button>                                  
                </div>
            </div>
            <div class="box-body">      
            <div id='general-content'>
                <table id="" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width='5%'>No</th>
                            <th>Tgl Mohon Samsat</th>  
                            <th>No Mesin</th>
                            <th>No Rangka</th>
                            <th>Nama Konsumen</th>
                            <th>Tipe Kendaraan</th>
                            <th>No STNK</th>
                            <th>No BPKB</th>
                            <th>No Plat</th>
                            <th>No Polisi</th>
                            <th><input type="checkbox" class="select_all"></th>                 
                        </tr>
                        <?php
                        $no=1;
                        foreach($dt_nosin->result() AS $row){
                            $jum = $dt_nosin->num_rows();
                            if($row->print_stnk=='printable' || $row->print_stnk=='ya') $dt_stnk = "<label class='badge badge-success'>cetak</label>";
                                else $dt_stnk = "";
                            if($row->print_bpkb=='printable' || $row->print_bpkb=='ya') $dt_bpkb = "<label class='badge badge-success'>cetak</label>";
                                else $dt_bpkb = "";
                            if($row->print_plat=='printable' || $row->print_plat=='ya') $dt_plat = "<label class='badge badge-success'>cetak</label>";
                                else $dt_plat = "";
                            $cek = $this->m_admin->getByID("tr_pengajuan_bbn_detail","no_mesin",$row->no_mesin);
                            $tgl_mohon = ($cek->num_rows() > 0) ? $cek->row()->tgl_mohon_samsat : "" ;
                            echo "
                            <tr>
                                <td>$no</td>
                                <td>$tgl_mohon</td>
                                <td>$row->no_mesin</td>
                                <td>$row->no_rangka</td>
                                <td>$row->nama_konsumen</td>
                                <td>$row->id_tipe_kendaraan $row->id_warna</td>
                                <td>$row->no_stnk $dt_stnk</td>
                                <td>$row->no_bpkb $dt_bpkb</td>
                                <td>$row->no_plat $dt_plat</td>
                                <td>$row->no_pol</td>
                                <td>
                                    <input type='hidden' name='no_mesin_$no' value='$row->no_mesin'>
                                    <input type='checkbox' name='cek_[$row->no_mesin]' id='cek_nosin[]'>
                                </td>
                            </tr>
                            ";
                            $no++;
                        }
                        echo "<input type='hidden' name='jum' id='jum'>";
                        ?>
                    </thead>
                </table>
                </div>
            </div><!-- /.box-body -->
        </form>
      </div><!-- /.box-body -->      
    </div><!-- /.box --> 
    
    <div id="general">
  <i>  
    <span style="font-size:20px;" id="counter" class="counter badge badge-secondary"></span>
  </i>
</div>
<script type="text/javascript">
  $('#general i .counter').text(' ');

var fnUpdateCount = function() {
  var generallen = $("#general-content input[id='cek_nosin[]']:checked").length;
    //console.log(generallen,$("#general i .counter") )
  $('#jum').val(generallen);
  if (generallen > 0) {
    $("#general i .counter").text('Total Checklist : ' + generallen + ' data');
  } else {
    $("#general i .counter").text(' ');
  }
};

$("#general-content input:checkbox").on("change", function() {
      fnUpdateCount();
    });

$('.select_all').change(function() {
      var checkthis = $(this);
      var checkboxes = $("#general-content input:checkbox");

      if (checkthis.is(':checked')) {
        checkboxes.prop('checked', true);
      } else {
        checkboxes.prop('checked', false);
      }
            fnUpdateCount();
    });
</script>

    <?php
    }
    ?>
  </section>
</div>
<script type="text/javascript">
function mulai(){
  for (var i = 1; i <= 19000; i++) {   
    $("#no_stnk_"+i+"").hide();
    $("#plat_"+i+"").hide();
    $("#no_plat_"+i+"").hide();
    $("#b_plat_"+i+"").hide();
    $("#no_bpkb_"+i+"").hide();
     $("#no_pol_"+i+"").hide();
    $("#b_pol_"+i+"").hide();
  }
  getDatatables();
  //cek_isi_form();
}
function cek_form(){ 
  for (var i = 1; i <= 10000; i++) {
  	$("#no_pol_"+i+"").on('keyup', function(){
  		var str = $(this).val();
  		var i = $(this).attr('i');
  	
		var n = str.length;
		if (n > 3) {
      		$("#b_pol_"+i+"").focus();
		}if (n > 4) {
      		$("#no_pol_"+i+"").focus();
      		$("#no_pol_"+i+"").val('');
		}
  	})
    if (document.getElementById("cek_stnk_"+i+"").checked == true){
      $("#no_stnk_"+i+"").show();
      //$("#no_stnk_"+i+"").val('');
      $("#no_stnk_"+i+"").focus();
    }else{
      $("#no_stnk_"+i+"").hide();
    }
    if (document.getElementById("cek_plat_"+i+"").checked == true){
      $("#no_plat_"+i+"").show();
      $("#plat_"+i+"").show();
      $("#b_plat_"+i+"").show();
      $("#no_plat_"+i+"").val($("#no_pol_"+i+"").val());
      $("#plat_"+i+"").val($("#pol_"+i+"").val());
      $("#b_plat_"+i+"").val($("#b_pol_"+i+"").val());
      //$("#no_plat_"+i+"").val('');
      $("#no_plat_"+i+"").focus();
    }else{
      $("#no_plat_"+i+"").hide();
      $("#plat_"+i+"").hide();
      $("#b_plat_"+i+"").hide();
      $("#no_plat_"+i+"").val('');
      $("#plat_"+i+"").val('');
      $("#b_plat_"+i+"").val('');
    }
    if (document.getElementById("cek_pol_"+i+"").checked == true){
      $("#no_pol_"+i+"").show();
      $("#pol_"+i+"").show();
      $("#b_pol_"+i+"").show();
      //$("#no_pol_"+i+"").val('');
      $("#no_pol_"+i+"").focus();
      //  $("#no_plat_"+i+"").show();
      // $("#plat_"+i+"").show();
      // $("#b_plat_"+i+"").show();
    }else{
      $("#no_pol_"+i+"").hide();
      $("#pol_"+i+"").hide();
      $("#b_pol_"+i+"").hide();
    }
    if (document.getElementById("cek_bpkb_"+i+"").checked == true){
      $("#no_bpkb_"+i+"").show();
      //$("#no_bpkb_"+i+"").val('');
      $("#no_bpkb_"+i+"").focus();
    }else{
      $("#no_bpkb_"+i+"").hide();
    }
  }  
}
function cek_isi_form(){ 
  for (var i = 1; i <= 10000; i++) {
    var stnk = $("#no_stnk_"+i+"").val();
    var plat = $("#no_plat_"+i+"").val();
    var bpkb = $("#no_bpkb_"+i+"").val();
    var pol = $("#no_pol_"+i+"").val();
    if(stnk != ''){
      $("#no_stnk_"+i+"").show();          
    }else{
      $("#no_stnk_"+i+"").hide();
    }
    if(plat != ''){
      $("#no_plat_"+i+"").show();      
      $("#b_plat_"+i+"").show();      
    }else{
      $("#no_plat_"+i+"").hide();
      $("#b_plat_"+i+"").hide();
    }
    if(pol != ''){
      $("#no_pol_"+i+"").show();      
      $("#b_pol_"+i+"").show();      
    }else{
      $("#no_pol_"+i+"").hide();
      $("#b_pol_"+i+"").hide();
    }
    if(bpkb != ''){
      $("#no_bpkb_"+i+"").show();     
    }else{
      $("#no_bpkb_"+i+"").hide();
    }
  }  
}
 function getDatatables()
  {
    $('#example1').DataTable({
          "paging": false
        });
        
  }

</script>

<script type="text/javascript">
    $(document).on('click', '.btn1', function(){
      const no_mesin = $(this).data('no');
      var no_stnk = $('#no_stnk').val();
      var no_bpkb = $('#no_bpkb').val();
      var no_pol = $('#no_pol').val();
      var no_plat = $('#no_plat').val();
      var notice_pajak = $('#notice_pajak').val();
        if(confirm('Yakin data akan di Update?'))
          {
              $.ajax({
                type: 'POST',
                url: "<?php echo base_url('h1/entry_stnk/update_data')?>",
                data: {no_mesin: no_mesin,no_bpkb:no_bpkb,no_stnk:no_stnk,no_plat:no_plat,no_pol:no_pol,notice_pajak:notice_pajak},
                dataType: 'json',
                success: function (data) {
                  alert(data);
                  window.location = "<?php echo base_url('h1/entry_stnk/edit_data')?>";
                },
                error: function (errs) { 
                  console.log(errs) }
              });
          }
			});
	</script>