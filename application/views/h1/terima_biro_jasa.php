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
<body onload="mulai()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Faktur STNK</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    <?php
    if($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!-- <a href="h1/terima_biro_jasa/add">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>           -->
                    
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
        <form action="h1/terima_biro_jasa/save" method="POST">
          <table id="example4" class="table table-bordered table-hover">
            <thead>
              <tr>              
                <th width="5%">No</th>                          
                <th>Action</th>              
                <th>No Mesin</th>              
                <th>No Rangka</th>
                <th>No Serah Terima BJ</th>
                <th>No BASTD</th>
                <th>Nama Konsumen</th>
                <th>No STNK</th>
                <th>Terima STNK</th>
                <th>No Plat</th>
                <th>Terima Plat</th>
                <th>Harga Notice Pajak</th>              
              </tr>
            </thead>
            <tbody>            
            <?php 
            $no=1; 
            foreach($dt_bj->result() as $row) {     
              $jum = $dt_bj->num_rows();      
              $cek = $this->db->query("SELECT * FROM tr_terima_bj WHERE no_mesin = '$row->no_mesin'");
              if($cek->num_rows() > 0){
                $row2 = $cek->row();
                $tom = "<button name=\"edit_$no\" onclick=\"return confirm('Are you sure to edit this data?')\" type=\"submit\" class=\"btn bg-maroon btn-flat btn-xs\"><i class=\"fa fa-edit\"></i> Edit</button>";
                $no_serah_terima = $row2->no_serah_terima;
                $no_mesin = $row2->no_mesin;
                $no_rangka = $row2->no_rangka;
                $nama_konsumen = $row2->nama_konsumen;
                $no_bastd = $row2->no_bastd;
                $no_stnk  = $row2->no_stnk;
                if($row2->terima_stnk == 'ya'){
                  $stnk_c = 'checked';
                }else{
                  $stnk_c = '';
                }              
                $no_plat  = $row2->no_plat;
                if($row2->terima_plat == 'ya'){
                  $plat_c = 'checked';
                }else{
                  $plat_c = '';
                }
                $notice_pajak  = $row2->notice_pajak;              
              }else{
                $tom = "<button name=\"save_$no\" onclick=\"return confirm('Are you sure to save this data?')\" type=\"submit\" class=\"btn btn-success btn-flat btn-xs\"><i class=\"fa fa-save\"></i> Save</button>";
                $no_serah_terima = $row->no_serah_terima;
                $no_mesin = $row->no_mesin;
                $no_rangka = $row->no_rangka;
                $nama_konsumen = $row->nama_konsumen;
                $no_bastd = $row->no_bastd;
                $no_stnk  = $row->no_stnk;              
                $stnk_c = '';                          
                $no_plat  = $row->no_plat;              
                $plat_c = '';              
                $notice_pajak  = $row->notice_pajak;
              }                              
            echo "          
              <tr>
                <td>$no</td>
                <td>";                
                echo $tom;
                echo "
                </td>
                <td>$no_mesin</td>                                         
                <td>$no_rangka</td>                                         
                <td>$no_serah_terima</td>                                         
                <td>$no_bastd</td>                                         
                <td>$nama_konsumen</td>                                         
                <td align='center'>
                  <input type='hidden' value='$jum' name='jum'>
                  <input type='hidden' value='$no_mesin' name='no_mesin_$no'>
                  <input type='hidden' value='$no_rangka' name='no_rangka_$no'>
                  <input type='hidden' value='$no_serah_terima' name='no_serah_terima_$no'>
                  <input type='hidden' value='$nama_konsumen' name='nama_konsumen_$no'>
                  <input type='hidden' value='$no_bastd' name='no_bastd_$no'>                  
                  <input type='hidden' value='$notice_pajak' name='notice_pajak_$no'>                  
                  <input type='checkbox' id='cek_stnk_$no' name='cek_stnk_$no' onchange='cek_stnk()'><br>
                  <span id='no_stnk_$no'><input type='text' name='no_stnk_$no' value='$no_stnk'></span>
                </td>                                         
                <td align='center'>
                  <input type='checkbox' name='terima_stnk_$no' $stnk_c>                
                </td>                                         
                <td align='center'>
                  <input type='checkbox' id='cek_plat_$no' name='cek_plat_$no' onchange='cek_stnk()'><br>
                  <span id='isi_plat_$no'><input type='text' name='isi_plat_$no' value='$no_plat'></span>
                </td>                                         
                <td align='center'>
                  <input type='checkbox' name='terima_plat_$no' $plat_c>
                </td>                                         
                <td>".mata_uang2($notice_pajak)."</td>                                         
                ";                                      
            $no++;
            }
            ?>
            </tbody>
          </table>
        </form>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>

<script type="text/javascript">
function mulai(){
  for (var i = 1; i <= 1000; i++) {   
    $("#no_stnk_"+i+"").hide();
    $("#isi_plat_"+i+"").hide();
  }
}
function cek_stnk(){ 
  for (var i = 1; i <= 1000; i++) {
    if (document.getElementById("cek_stnk_"+i+"").checked == true){
      $("#no_stnk_"+i+"").show();
    }else{
      $("#no_stnk_"+i+"").hide();
    }

    if (document.getElementById("cek_plat_"+i+"").checked == true){
      $("#isi_plat_"+i+"").show();
    }else{
      $("#isi_plat_"+i+"").hide();
    }  
  }  
}
</script>