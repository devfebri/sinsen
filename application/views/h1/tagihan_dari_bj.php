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

    <li class="">Biro Jasa</li>

    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>

  </ol>

  </section>

  <section class="content">

    

    <?php 

    if($set=="insert"){      

    ?>



    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="h1/tagihan_dari_bj">

            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>

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

            <form class="form-horizontal" action="h1/tagihan_dari_bj/save" method="post" enctype="multipart/form-data">              

              <div class="box-body">       

                <br>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Faktur (Awal)</label>                  

                  <div class="col-sm-4">

                    <input autocomplete='off' type="text" id="tanggal" name="tgl_awal" placeholder="Tgl Faktur (Awal)" class="form-control">

                  </div>                                                

                </div>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Faktur (Akhir)</label>                  

                  <div class="col-sm-4">

                    <input autocomplete='off' type="text" id="tanggal1" name="tgl_akhir" placeholder="Tgl Faktur (Akhir)" class="form-control">

                  </div>                              

                  <div class="col-sm-2">

                    <button type="button" onclick="generate()" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Generate</button>

                  </div>  

                </div>

                <div class="form-group">                  

                  <span id="tampil_data"></span>

                </div>                

              </div><!-- /.box-body -->

              <div class="box-footer">

                <div class="col-sm-2">

                </div>

                <div class="col-sm-10">                  

                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>

                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  

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

<?php /* ?>
          <a href="h1/tagihan_dari_bj/add">            

            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>

          </a>          

                    <?php */ ?>

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

              <th width="12%">No Invoice</th>

              <th>Tgl Invoice</th>

              <th>Jumlah Unit</th>

              <th>Amount</th>                 

              <th width="10%">Action</th>              

            </tr>

          </thead>

          <tbody>            

          <?php 

          $no=1; 

          foreach($dt_bbn->result() as $row){                                         

            // $item = $this->db->query("SELECT COUNT(no_mesin) as jum FROM tr_pengajuan_bbn_detail WHERE id_generate = '$row->id_generate'")->row();

            // if(isset($row->no_tanda_terima)){
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');
            $tom = "<a $print href='h1/tagihan_dari_bj/cetak?id=$row->no_invoice_bbn' class='btn btn-primary btn-flat btn-sm'><i class='fa fa-print'></i></a>";

            // }else{

            //   $tom = "";

            // } 

            echo "          

            <tr>

              <td>$no</td>

              <td><a href='h1/tagihan_dari_bj/detail?id=$row->no_invoice_bbn'>$row->no_invoice_bbn</a></td>                           

              <td>$row->tgl_invoice</td>                           

              <td>$row->jumlah_unit</td>                           

              <td>".mata_uang2($row->amount)."</td>                                         

              <td align='center'>";

              echo $tom;

              echo "

              </td>";                                      

          $no++;

          }

          ?>

          </tbody>

        </table>

      </div><!-- /.box-body -->

    </div><!-- /.box -->

 <?php 
}
    if($set=="detail"){      

    ?>



    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="h1/tagihan_dari_bj">

            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>

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


           <?php /* ?>     <br>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Faktur (Awal)</label>                  

                  <div class="col-sm-4">

                    <input autocomplete='off' type="text" id="tanggal" name="tgl_awal" placeholder="Tgl Faktur (Awal)" class="form-control">

                  </div>                                                

                </div>

                <div class="form-group">                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Faktur (Akhir)</label>                  

                  <div class="col-sm-4">

                    <input autocomplete='off' type="text" id="tanggal1" name="tgl_akhir" placeholder="Tgl Faktur (Akhir)" class="form-control">

                  </div>                              

                  <div class="col-sm-2">

                    <button type="button" onclick="generate()" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Generate</button>

                  </div>  

                </div>

                <div class="form-group">    <?php */ ?>              

                    <table id="example2" class="table myTable1 table-bordered table-hover">
                      <thead>
                        <tr>      
                          <th>Tgl Faktur</th>              
                          <th>No Mesin</th>              
                          <th>No Rangka</th>              
                          <th>Nama Konsumen</th>
                          <th>Tipe</th>                        
                          <th>Warna</th>                        
                          <th>Tahun Produksi</th>                        
                          <th>Harga BBN</th>                        
                          <th>Notice Pajak</th>                           
                        </tr>
                      </thead>
                     
                      <tbody>                    
                        <?php   
                        $no = 1;
                        foreach($dt_detail->result() as $isi) {
                            echo "
                            <tr>
                              <td>$isi->tgl_jual</td>
                              <td>$isi->no_mesin</td>
                              <td>$isi->no_rangka</td>
                              <td>$isi->nama_konsumen</td>
                              <td>$isi->tipe_ahm</td>
                              <td>$isi->warna</td>
                              <td>$isi->tahun</td>
                              <td>$isi->biaya_bbn</td>
                              <td>$isi->notice_pajak</td>
                            </tr>
                            ";
                          $no++;
                          }
                        ?>
                      </tbody>
                    </table>     
              </div><!-- /.box-body -->

           </form>

          </div>

        </div>

      </div>

    </div><!-- /.box -->






    <?php

    }

    ?>

  </section>

</div>

<script type="text/javascript">

function mulai(){

  for (var i = 1; i <= 1000; i++) {   

    $("#notice_pajak_"+i+"").hide();    

  }

}

function cek_form(){ 

  for (var i = 1; i <= 1000; i++) {

    if (document.getElementById("cek_form_"+i+"").checked == true){

      $("#notice_pajak__"+i+"").show();

      $("#notice_pajak__"+i+"").val('');

      $("#notice_pajak__"+i+"").focus();

    }else{

      $("#notice_pajak__"+i+"").hide();

    }    

  }  

}

function generate(){    

  $("#tampil_data").show();

  var start_date  = document.getElementById("tanggal").value;   

  var end_date    = document.getElementById("tanggal1").value;   

  var xhr;

  if (window.XMLHttpRequest) { // Mozilla, Safari, ...

    xhr = new XMLHttpRequest();

  }else if (window.ActiveXObject) { // IE 8 and older

    xhr = new ActiveXObject("Microsoft.XMLHTTP");

  } 

   //var data = "birthday1="+birthday1_js;          

    var data = "start_date="+start_date+"&end_date="+end_date;                           

     xhr.open("POST", "h1/tagihan_dari_bj/t_bbn", true); 

     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  

     xhr.send(data);

     xhr.onreadystatechange = display_data;

     function display_data() {

        if (xhr.readyState == 4) {

            if (xhr.status == 200) {       

                document.getElementById("tampil_data").innerHTML = xhr.responseText;

            }else{

                alert('There was a problem with the request.');

            }

        }

    }   

}

</script>