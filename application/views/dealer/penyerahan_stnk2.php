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

    if($set=="insert"){

    ?>



    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="dealer/penyerahan_stnk">

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

        <div id="row">

          <div class="col-md-12">

            <form class="form-horizontal" action="dealer/retur_k_d/save" method="post" enctype="multipart/form-data">

              <div class="box-body">    

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>

                  <div class="col-sm-4">

                    <input type="text" required class="form-control"  placeholder="No Mesin" name="id_penerimaan_unit">

                  </div>                  

                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>

                  <div class="col-sm-4">

                    <input type="text" required class="form-control"  placeholder="No Rangka" name="id_penerimaan_unit">

                  </div>                  

                </div>

                <button class="btn btn-primary btn-block btn-flat" disabled>Data Konsumen</button>

                <br>                

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>

                  <div class="col-sm-10">

                    <input type="text" required class="form-control"  placeholder="Nama Konsumen" name="id_penerimaan_unit">

                  </div>                  

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>

                  <div class="col-sm-10">

                    <input type="text" required class="form-control"  placeholder="Alamat" name="id_penerimaan_unit">

                  </div>                  

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>

                  <div class="col-sm-4">

                    <input type="text" required class="form-control"  placeholder="No KTP" name="id_penerimaan_unit">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>

                  <div class="col-sm-4">

                    <input type="text" required class="form-control"  placeholder="No HP" name="id_penerimaan_unit">

                  </div>

                </div>  

                <button class="btn btn-primary btn-block btn-flat" disabled>Data Kendaraan</button>

                <br>   

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Leasing</label>

                  <div class="col-sm-4">

                    <input type="text" required class="form-control"  placeholder="Leasing" name="id_penerimaan_unit">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor</label>

                  <div class="col-sm-4">

                    <input type="text" required class="form-control"  placeholder="Tipe MOtor" name="id_penerimaan_unit">

                  </div>

                </div>     

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>

                  <div class="col-sm-4">

                    <input type="text" required class="form-control"  placeholder="Warna" name="id_penerimaan_unit">

                  </div>                                  

                  <label for="inputEmail3" class="col-sm-2 control-label">No Polisi</label>

                  <div class="col-sm-4">

                    <input type="text" required class="form-control"  placeholder="No Polisi" name="id_penerimaan_unit">

                  </div>                  

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">No BPKB</label>

                  <div class="col-sm-4">

                    <input type="text" required class="form-control"  placeholder="No BPKB" name="id_penerimaan_unit">

                  </div>                  

                </div>               



              </div>

            </form>

          </div>

        </div>        

      </div><!-- /.box-body -->

      <div class="box-footer">

        <div class="col-sm-2">

        </div>

        <div class="col-sm-10">

          <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>

          <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                

        </div>

      </div><!-- /.box-footer -->

    </div><!-- /.box -->



    <?php 

    }elseif($set=="reject"){

    ?>



    <div class="box box-default">

      <div class="box-header with-border">

        <h3 class="box-title">

          <a href="dealer/penyerahan_stnk">

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

            <form class="form-horizontal" action="dealer/penyerahan_stnk/save" method="post" enctype="multipart/form-data">

              <div class="box-body">              

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">No Spk</label>

                  <div class="col-sm-4">

                    <input type="text" required class="form-control" placeholder="No SPK" name="nama_konsumen">                    

                  </div>                                    

                </div>                

                <div class="form-group">                

                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>

                  <div class="col-sm-10">

                    <input type="text" required class="form-control" placeholder="Nama Konsumen" name="nama_konsumen">                    

                  </div>                  

                </div>

                <div class="form-group">                

                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>

                  <div class="col-sm-10">

                    <input type="text" required class="form-control" placeholder="Nama Konsumen" name="nama_konsumen">                    

                  </div>                  

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>

                  <div class="col-sm-4">

                    <input type="text" required class="form-control" placeholder="Warna" name="nama_konsumen">                    

                  </div>                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor</label>

                  <div class="col-sm-4">                    

                    <input type="text" required class="form-control" placeholder="Tipe Motor" name="nama_konsumen">                    

                  </div>

                  

                </div>

                <div class="form-group">                                  

                  <label for="inputEmail3" class="col-sm-2 control-label">Alasan Reject</label>

                  <div class="col-sm-10">

                    <input type="text" required class="form-control" placeholder="Alasan Reject" name="nama_konsumen">                    

                  </div>                  

                </div>                

              </div>

              <div class="box-footer">

                <div class="col-sm-2">

                </div>

                <div class="col-sm-10">

                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>

                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                

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

          <!-- <a href="dealer/penyerahan_stnk/add">

            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>

          </a>           -->

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

        <table id="example" class="table table-bordered table-hover">

          <thead>

            <tr>

              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              

              <th width="5%">No</th>

              <th>No Mesin</th>              

              <th>No Rangka</th>              

              <th>Nama Konsuen</th>

              <th>Alamat</th>

              <th>Tipe</th>

              <th>Warna</th>

              <th>No Polisi</th>

              <th>No BPKB</th>

              <th>Leasing</th>              

              <th>Aksi</th>                          

            </tr>

          </thead>

          <tbody>            

          <?php 

          $no=1; 

          foreach($dt_stnk->result() as $row) {     

            $s = $this->db->query("SELECT * FROM tr_terima_bj WHERE no_mesin = '$row->no_mesin'");

            if ($s->num_rows() >0) {

              $s = $s->row();

              $nama_konsumen = $s->nama_konsumen;

              $no_rangka = $s->no_rangka;

              $id_tipe_kendaraan = $s->id_tipe_kendaraan;

              $id_warna = $s->id_warna;

              $no_plat = $s->no_plat;

              $no_bpkb = $s->no_bpkb;

            }else{

                $nama_konsumen = '';

              $no_rangka = '';

              $id_tipe_kendaraan = '';

              $id_warna = '';

              $no_plat = '';

              $no_bpkb = '';

            }

            $a = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail WHERE no_mesin = '$row->no_mesin'")->row();          

            $k = $this->db->query("SELECT * FROM tr_spk INNER JOIN tr_faktur_stnk_detail ON tr_spk.no_spk = tr_faktur_stnk_detail.no_spk 

              LEFT JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company

              WHERE tr_faktur_stnk_detail.no_bastd = '$a->no_bastd'");          

            if($k->num_rows() > 0){

              $f = $k->row();

              $fin = $f->finance_company;

            }else{

              $fin = "";

            }



            $print = $this->m_admin->set_tombol($id_menu,$group,'print');

            echo "

            <tr>

              <td>$no</td>

              <td>$row->no_mesin</td>              

              <td>$no_rangka</td>

              <td>$nama_konsumen</td>

              <td>$a->alamat</td>                            

              <td>$id_tipe_kendaraan</td>                                                        

              <td>$id_warna</td>                                                        

              <td>$no_plat</td>                                                        

              <td>$no_bpkb</td>                                                        

              <td>$fin</td>                                                        

              <td>                                

                <a href='dealer/penyerahan_stnk/cash?id=$row->no_mesin'>

                  <button $print class='btn btn-flat btn-xs btn-primary'><i class='fa fa-print'></i> Cetak TTD Cash</button>

                </a>

                <a href='dealer/penyerahan_stnk/print?id=$row->no_mesin'>

                  <button $print class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak TTD Kredit</button>

                </a>

              </td>

            </tr>

            ";

          $no++;

          }

          ?>

          </tbody>

        </table>

      </div><!-- /.box-body -->

    </div><!-- /.box -->



    <?php

    }elseif($set=="history"){

    ?>



    <div class="box">

      <div class="box-header with-border">

        <h3 class="box-title">

         <ul class="nav nav-pills">

            <li role="presentation" ><a href="dealer/penyerahan_stnk/print_preview?p=plat">PLAT</a></li>

            <li role="presentation" ><a href="dealer/penyerahan_stnk/print_preview?p=stnk">STNK</a></li>

            <li role="presentation" ><a href="dealer/penyerahan_stnk/print_preview?p=bpkb">BPKB</a></li>

            <li role="presentation" ><a href="dealer/penyerahan_stnk/print_preview?p=srut">SRUT</a></li>

          </ul>

          <!-- <a href="dealer/penyerahan_stnk/add">

            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>

          </a>           -->

          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  

        </h3>

        <div class="box-tools pull-right">

          <ul class="nav nav-pills">

          <li role="presentation" class="active btn-success"><a href="dealer/penyerahan_stnk/history">HISTORY</a></li>

        </ul>

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

              <th width="5%">No</th>

              <th>No Tanda Terima</th>                     

              <th>Tanggal Tanda Terima</th>

              <th>Jumlah Unit</th>

              <th>Diterima Oleh</th>

              <th>Diperiksa Oleh</th>

              <th>Diserahkan Oleh</th>

              <th>Aksi</th>

            </tr>

          </thead>

          <tbody>            

            <?php $no=1;foreach ($dt_hist->result() as $rs):

                $tgl_exp = explode(' ', $rs->tgl_cetak);

                $tgl = date('d-m-Y', strtotime($tgl_exp[0]));



             ?>

                <tr>

                  <td><?php echo  $no ?></td>

                  <td><?php echo  $rs->kd_stnk_konsumen ?></td>

                  <td><?php echo  $tgl ?></td>

                  <td><?php echo  $rs->jml ?></td>

                  <td><?php echo  $rs->diterima ?></td>

                  <td><?php echo  $rs->diperiksa ?></td>

                  <td><?php echo  $rs->diserahkan ?></td>

                  <td>

                    <a <?php echo $this->m_admin->set_tombol($id_menu,$group,"print"); ?> href="dealer/penyerahan_stnk/cetak_ulang?id=<?php echo $rs->kd_stnk_konsumen?>&p=<?php echo $rs->jenis_cetak?>" class="btn btn-success btn-flat btn-xs" target="_blank"><i class="fa fa-print"></i></a>

                  </td>

                </tr>

            <?php $no++; endforeach ?>

          </tbody>

        </table>

      </div><!-- /.box-body -->

    </div><!-- /.box -->



    <?php

    }elseif($set=="preview"){

    ?>



    <div class="box">

      <div class="box-header with-border">

        <h3 class="box-title">

          <ul class="nav nav-pills">

            <?php 

            $setprint=$this->input->get('p');

            if ($setprint=='plat'){

              $set1='active';$set2='';$set3='';$set4='';  

            }elseif ($setprint=='stnk') {

              $set1='';$set2='active';$set3='';$set4='';

            }elseif ($setprint=='bpkb') {

              $set1='';$set2='';$set3='active';$set4='';

            }elseif ($setprint=='srut') {

              $set1='';$set2='';$set3='';$set4='active';

            }?>

            <li role="presentation" class="<?php echo  $set1 ?>"><a href="dealer/penyerahan_stnk/print_preview?p=plat">PLAT</a></li>

            <li role="presentation" class="<?php echo  $set2 ?>"><a href="dealer/penyerahan_stnk/print_preview?p=stnk">STNK</a></li>

            <li role="presentation" class="<?php echo  $set3 ?>"><a href="dealer/penyerahan_stnk/print_preview?p=bpkb">BPKB</a></li>

            <li role="presentation" class="<?php echo  $set4 ?>"><a href="dealer/penyerahan_stnk/print_preview?p=srut">SRUT</a></li>

          </ul>

          <!-- <a href="dealer/penyerahan_stnk/add">

            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>

          </a>           -->

          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  

        </h3>

        <div class="box-tools pull-right">

          <ul class="nav nav-pills">

          <li role="presentation" class="active btn-success"><a href="dealer/penyerahan_stnk/history">HISTORY</a></li>

        </ul>

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

            <form class="form-horizontal" action="dealer/penyerahan_stnk/print_preview?p=<?php echo  $setprint?>" method="post" enctype="multipart/form-data">

              <div class="box-body">    

                <div class="form-group">

                  <label for="" class="col-sm-1 control-label">Search</label>

                  <div class="col-sm-4">

                    <input type="hidden" name="setprint">

                    <input type="text" required class="form-control"  placeholder="Search" name="search" autocomplete="off" value="<?php echo  $search ?>">

                  </div>

                  <div class="col-sm-1">

                    <button class="btn btn-flat btn-primary" type="submit">Generate</button>

                  </div>                

                </div>

              </form>

              <form class="form-horizontal" action="dealer/penyerahan_stnk/cetak" target="_blank" method="POST">

                <input type="hidden" name="setprint" value="<?php echo  $setprint ?>">

                <table id="example4" class="table table-bordered table-hover">

                  <thead>
                    <th>ID Sales Order</th>
                    <th>No Mesin</th>
                    <th>No Rangka</th>
                    <th>Nama Konsumen</th>
                    <th>No Plat</th>
                    <th>No STNK</th>
                    <th>No BPKB</th>
                    <th>No SRUT</th>
                    <th>Aksi</th>

                  </thead>

                  <tbody>

                    <?php if ($dt_serah !=null) {

                      $x=0;

                      foreach ($dt_serah->result() as $rs) {  ?>

                        <tr>

                          <td><?php echo  $rs->id_sales_order ?></td>
                          <td><?php echo  $rs->no_mesin ?></td>

                          <td><?php echo  $rs->no_rangka ?></td>

                          <td><?php echo  $rs->nama_konsumen ?></td>

                          <td><?php echo  $rs->no_plat ?></td>

                          <td><?php echo  $rs->no_stnk ?></td>

                          <td><?php echo  $rs->no_bpkb ?></td>

                          <td><?php echo  isset($rs->no_srut)?$rs->no_srut:''; ?></td>

                          <td align="center"><input type="checkbox" name="check_<?php echo  $x ?>">

                                             <input type="hidden" name="no_mesin[]" value="<?php echo  $rs->no_mesin ?>">

                          </td>

                        </tr>

                    <?php $x++; }

                    } ?>

                  </tbody>

                </table>
                <?php if ($setprint=='bpkb'): ?>
                  <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Tgl Terima BPKB</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" readonly value="<?= date('Y-m-d') ?>" name="tgl_terima_bpkb" autocomplete="off">
                    </div>              
                  </div>
                <?php endif ?>
                <?php if ($setprint=='stnk'): ?>
                  <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Tgl Terima STNK</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" readonly value="<?= date('Y-m-d') ?>" name="tgl_terima_stnk" autocomplete="off">
                    </div>              
                  </div>
                <?php endif ?>
                <?php if ($setprint=='srut'): ?>
                  <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Tgl Terima SRUT</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" readonly value="<?= date('Y-m-d') ?>" name="tgl_terima_srut" autocomplete="off">
                    </div>              
                  </div>
                <?php endif ?>
                <?php if ($setprint=='plat'): ?>
                  <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Tgl Terima Plat</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" readonly value="<?= date('Y-m-d') ?>" name="tgl_terima_plat" autocomplete="off">
                    </div>              
                  </div>
                <?php endif ?>
                 <div class="form-group">

                    <label for="" class="col-sm-2 control-label">Disetujui Oleh</label>

                    <div class="col-sm-4">

                      <input type="text" required class="form-control" name="disetujui" autocomplete="off">

                    </div>              

                  </div>

                  <?php if ($setprint=='bpkb'): ?>

                    <div class="form-group">

                    <label for="" class="col-sm-2 control-label">Diperiksa Oleh</label>

                    <div class="col-sm-4">

                      <input type="text" required class="form-control" name="diperiksa" autocomplete="off">

                    </div>              

                  </div>

                  <?php endif ?>

                  <div class="form-group">

                    <label for="" class="col-sm-2 control-label">Diserahkan Oleh</label>

                    <div class="col-sm-4">

                      <input type="text" required class="form-control" name="diserahkan" autocomplete="off">

                    </div>              

                  </div>

                  <div class="form-group">

                    <label for="" class="col-sm-2 control-label">Diterima Oleh</label>

                    <div class="col-sm-4">

                      <input type="text" required class="form-control" name="diterima" autocomplete="off">

                    </div>              

                  </div>

                <div class="form-group">
                  <label for="" class="col-sm-2 control-label">Jenis ID</label>
                  <div class="col-sm-4">
                    <select name="jenis_id" class="form-control select2">
                      <option value="">--choose--</option>
                      <option value="KTP">KTP</option>
                      <option value="SIM">SIM</option>
                    </select>
                  </div>              
                </div>
                <div class="form-group">
                  <label for="" class="col-sm-2 control-label">Nomor ID</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="no_id">
                  </div>              
                </div>
              </div>

                <div class="box-footer">

                  <div class="col-sm-5">

                  </div>

                  <div class="col-sm-4">

                    <button type="submit" name="print" value="print" class="btn btn-info btn-flat"><i class="fa fa-print"></i> Cetak</button>

                  </div>

                </div><!-- /.box-footer -->

          </div>

        </div>

      </div><!-- /.box-body -->

    </div><!-- /.box -->

  <?php } ?>

  </section>

</div>