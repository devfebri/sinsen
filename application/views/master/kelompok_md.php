<style type="text/css"> 
.hide
  {
    display: none;
  }</style>
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Master Data</li>
    <li class="">Kelompok Harga</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
/*
    if($set=="insert"){
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/kelompok_md">
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
            <form class="form-horizontal" action="master/kelompok_md/save" method="post" enctype="multipart/form-data">
              <div class="box-body">                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Kelompok Harga</label>
                  <div class="col-sm-4">
                    <select name="id_kelompok_harga" class="form-control select2 id_kelompok_harga" onchange="CekTargetMarket()">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach($dt_kel->result() as $val) {
                        echo "
                        <option value='$val->id_kelompok_harga' target_market='$val->target_market'>$val->kelompok_harga</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>                
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Item</label>
                  <div class="col-sm-4">
                    <select name="id_item" class="form-control select2">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach($dt_item->result() as $val) {
                        echo "
                        <option value='$val->id_item'>$val->id_item</option>;
                        ";
                      }
                      ?>                                       
                    </select>
                  </div>                
                </div>
                <div class="form-group">
                  <div class="input_harga_bbn">
                    <label for="inputEmail3" class="col-sm-2 control-label">Harga BBN</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="harga_bbn" placeholder="Harga BBN">                    
                    </div>
                  </div>
                 <div class="">
                    <label for="inputEmail3" class="col-sm-2 control-label">Harga Jual</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="harga_jual" placeholder="Harga Jual">                    
                  </div>
                 </div>
                </div>            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal" class="form-control" name="start_date" placeholder="Start Date">
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal2" class="form-control" name="end_date" placeholder="End Date">
                  </div>
                </div>            
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>                  
                </div>                                                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <?php 
*/
    if($set=="insert"){
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/kelompok_md">
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
            <form class="form-horizontal" action="master/kelompok_md/save" method="post" enctype="multipart/form-data" id="form_add">
              <div class="box-body">                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Kelompok Harga</label>
                  <div class="col-sm-4">
                    <select name="id_kelompok_harga" class="form-control select2 id_kelompok_harga" onchange="CekTargetMarket()" id="id_kelompok_harga">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach($dt_kel->result() as $val) {
                        echo "
                        <option value='$val->id_kelompok_harga' target_market='$val->target_market'>$val->kelompok_harga</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>                
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal" class="form-control" name="start_date" placeholder="Start Date" autocomplete="off">
                  </div>                
                  <div class="col-sm-4">
                <button class="btn btn-flat btn-primary " autocomplete="off" onclick="generateDel()" type="button">Generate</button>
                  </div>
                </div>            
                <div class="form-group">
                <div class="col-md-12">
                  <button class="btn btn-primary col-md-12 btn-flat" disabled>Detail</button>                  
                </div>
                </div>
                <div id="showGenerate"></div>                                                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="button" name="save" value="save" class="btn btn-info btn-flat" onclick="submitForm()"><i class="fa fa-save"></i> Save</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<?php }
elseif($set=="approve"){
  $row=$data->row();
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/kelompok_md">
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
            <form class="form-horizontal" action="master/kelompok_md/save_approve_reject" method="post" enctype="multipart/form-data">
              <div class="box-body">                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Kelompok Harga</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kel" value="<?php echo $row->id_kel?>">
                   <input type="text" name="id_kelompok_harga" class="form-control" readonly value="<?php echo $row->id_kelompok_harga?>">
                  </div>                
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="start_date" placeholder="Start Date" autocomplete="off" readonly value="<?php echo $row->start_date ?>">
                  </div>  
                </div>            
                <div class="form-group">
                <div class="col-md-12">
                  <button class="btn btn-primary col-md-12 btn-flat" disabled>Detail</button>                  
                </div>
                </div>
                <table class="table table-bordered">
                   <thead>
                      <th width="22%">Tipe</th>
                      <th width="10%">Kode Item</th>
                      <th width="20%">Warna</th>
                      <th width="11%">Harge Terakhir</th>
                      <th width="11%">Harga baru</th>
                      <th>Keterangan</th>
                    </thead>
                    <tbody>
                        <?php $y=0; foreach ($tipe->result() as $res): ?>
                        
                            <?php $num=$this->db->query("SELECT * FROM ms_kelompok_md_harga_detail WHERE id_kel='$res->id_kel' ")->num_rows(); ?>
                            <?php  $num = $this->db->query("SELECT * FROM ms_kelompok_md_harga_detail WHERE id_kel='$res->id_kel' AND LEFT(id_item,3)='$res->id_tipe_kendaraan' ")->num_rows() ?>
                            <?php $x=1;foreach ($detail->result() as $det): ?>
                             <?php if ($res->id_tipe_kendaraan==$det->id_tipe_kendaraan): ?>
                                 <tr>
                                <?php if ($x==1): ?>
                                  <td rowspan="<?php echo $num?>" style="vertical-align: middle;"><?php echo  $res->id_tipe_kendaraan?> | <?php echo $res->tipe_ahm ?></td>
                                <?php endif ?>
                
                                <?php 
                                $getHeader = $this->db->query("SELECT * FROM ms_kelompok_md_harga WHERE id_kel='$res->id_kel'");
                                $id_kelompok_harga = $getHeader->num_rows()>0?$getHeader->row()->id_kelompok_harga:'';
                                $harga_terakhir=$this->db->query("SELECT harga_jual FROM ms_kelompok_md WHERE id_item='$det->id_item' AND id_kelompok_harga='$id_kelompok_harga' ORDER BY start_date DESC LIMIT 0,1");
                                  $hrg = $harga_terakhir->num_rows()>0?$harga_terakhir->row()->harga_jual:0;
                                ?>
                                <td><?php echo  $det->id_item?></td>
                                <td><?php echo  $det->warna?></td>
                                <td><?php echo  mata_uang2($hrg)?></td>
                                <td><?php echo  mata_uang2($det->harga_jual)?></td>
                                <td>
                                  <input type="hidden" name="id_<?php echo $y?>" value="<?php echo $det->id?>">
                                  <input type="text" name="keterangan_<?php echo $y?>" class="form-control" value="<?php echo $det->keterangan?>" autocomplete="off">
                                </td>
                              </tr>
                             <?php $x++;$y++; endif ?>
                            <?php endforeach ?>
                          
                        <?php endforeach ?>
                    </tbody>
                </table>                                            
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-offset-4 col-sm-8">
                  <button type="submit" name="save" value="approve" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Approve</button>
                  <button type="submit" name="save" value="reject" class="btn btn-danger btn-flat"><i class="fa fa-refresh"></i> Reject</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<?php
    }elseif($set=="edit_"){
      $row = $dt_kelompok_md->row(); 
    ?>
    <body onload="tampil_edit()">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/kelompok_md">
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
            <form class="form-horizontal" action="master/kelompok_md/update" method="post" enctype="multipart/form-data">
              <input type="text" name="id" id="id_kel" value="<?php echo $row->id_kel ?>" />
              <div class="box-body">                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Kelompok Harga</label>
                  <div class="col-sm-4">
                    <select name="id_kelompok_harga" class="form-control select2" disabled>
                      <option value="<?php echo $row->id_kelompok_harga ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_kelompok_harga","id_kelompok_harga",$row->id_kelompok_harga)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->kelompok_harga;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>                      
                    </select>
                  </div>                
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $row->start_date ?>" name="start_date" placeholder="Start Date">
                  </div>                                  
                </div>                                                     
                <div class="form-group">
                  <div class="col-md-12">
                    <button class="btn btn-primary col-md-12 btn-flat" disabled>Detail</button>                                      
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12">
                    <div id="showEdit"></div>                                                
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12">
                    <div id="showGenerate"></div>                                                
                  </div>
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Update</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<?php
    }elseif($set=="edit"){
      $row = $dt_kelompok_md->row(); 
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/kelompok_md">
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
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
  $(document).ready(function(){
    <?php if (isset($row)) { ?>
        
    <?php } ?>
  })
</script>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" id="form_" action="master/kelompok_md/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" id="id_kel" value="<?php echo $row->id_kel ?>" />
              <div class="box-body">                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Kelompok Harga</label>
                  <div class="col-sm-4">
                    <select name="id_kelompok_harga" id="id_kelompok_harga" class="form-control select2" disabled>
                      <option value="<?php echo $row->id_kelompok_harga ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_kelompok_harga","id_kelompok_harga",$row->id_kelompok_harga)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->kelompok_harga;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>                      
                    </select>
                  </div>                
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $row->start_date ?>" name="start_date" id="start_date" placeholder="Start Date">
                  </div>                                  
                </div>                                                     
                <div class="form-group">
                  <div class="col-md-12">
                    <button class="btn btn-primary col-md-12 btn-flat" disabled>Detail</button>                                      
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12">
                    <table class="table table-bordered">
                      <thead>
                        <th width="25%">Tipe</th>
                        <th>Kode Item</th>
                        <th>Warna</th>
                        <th>Harga Terakhir</th>
                        <th>Harga Terbaru</th>
                        <th v-if="status=='rejected'">Keterangan</th>
                        <th>Aksi</th>
                      </thead>
                      <tbody>
                        <tr v-for="(dtls, index) of details">
                          <!-- <td :rowspan="details.length" v-if="index==0" style="vertical-align: middle;"> -->
                          <td style="vertical-align: middle;">
                          {{dtls.id_tipe_kendaraan}} - {{dtls.tipe_ahm}}</td>
                          <td>{{dtls.id_item}}</td>
                          <td>{{dtls.warna}}</td>
                          <td>
                            <vue-numeric style="float: left;width: 100%;text-align: right;"
                            class="form-control text-rata-kanan isi" v-model="dtls.harga_akhir" 
                            v-bind:minus="false" :empty-value="0" separator="." readonly/>
                          </td>
                          <td>
                            <vue-numeric style="float: left;width: 100%;text-align: right;"
                            class="form-control text-rata-kanan isi" v-model="dtls.harga_baru" 
                            v-bind:minus="false" :empty-value="0" separator="."/>
                          </td>
                          <td v-if="status=='rejected'">
                            {{dtls.keterangan}}
                          </td>
                          <td style="vertical-align: middle;text-align: center;">
                            <button type="button" @click.prevent="delDetails(index)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button> 
                          </td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="6">
                          <select name="id_tipe_kendaraan" id="id_tipe_kendaraan" class="form-control select2" style="width: 40%" onchange="getItem()">
                          <?php 
                            $tipe=$this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active=1");
                            if ($tipe->num_rows()>0) { ?>
                              <option value="">- choose -</option>
                              <?php foreach ($tipe->result() as $rs): ?>
                                <option value="<?= $rs->id_tipe_kendaraan ?>"><?= $rs->id_tipe_kendaraan.' | '.$rs->tipe_ahm ?></option>
                              <?php endforeach;
                            }
                           ?>
                          </select>
                        </td>
                        </tr>
                        <tr v-for="(dtl, index) of detail">
                          <td :rowspan="detail.length" v-if="index==0" style="vertical-align: middle;">
                          {{dtl.id_tipe_kendaraan}} - {{dtl.tipe_ahm}}</td>
                          <td>{{dtl.id_item}}</td>
                          <td>{{dtl.warna}}</td>
                          <td>
                            <vue-numeric style="float: left;width: 100%;text-align: right;"
                            class="form-control text-rata-kanan isi" v-model="dtl.harga_akhir" 
                            v-bind:minus="false" :empty-value="0" separator="." readonly/>
                          </td>
                          <td>
                            <vue-numeric style="float: left;width: 100%;text-align: right;"
                            class="form-control text-rata-kanan isi" v-model="dtl.harga_baru" 
                            v-bind:minus="false" :empty-value="0" separator="."/>
                          </td>
                          <td :rowspan="detail.length" v-if="index==0" style="vertical-align: middle;text-align: center;">
                            <button type="button" @click.prevent="addDetails" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i></button> 
                          </td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="button" id="submitBtn" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Update</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<script>
   var form_ = new Vue({
      el: '#form_',
      data: {
        mode : '<?= $set ?>',
        status : '<?= $status ?>',
        detail:[],
        details : <?= isset($details)?json_encode($details):'[]' ?>,
      },
      methods: {
        clearDetail: function () {
        this.detail=[];
        $('#id_tipe_kendaraan').val('').trigger('change'); 
        },
        addDetails : function(){
          // if (this.details[index].parts.length > 0) {
          //   for (prt of this.details[index].parts) {
          //     if (part.id_part === prt.id_part) {
          //         alert("Part Sudah Dipilih !");
          //         return false;
          //     }
          //   }
          // }
          // if (this.details[index].part.id_part=='' || this.details[index].part.qty_part=='') 
          // {
          //   alert('Isi data dengan lengkap !');
          //   return false;
          // }
          // console.log(this.detail);
          // this.details.push(this.detail);
          // console.log(this.details);
          for(ck of this.details){
            for(ck2 of this.detail){
              if (ck.id_item==ck2.id_item) {
                alert('Kode Item = '+ck.id_item+' sudah ada dalam daftar !');
                return false;
              }
            }
          }
          for (dtl of this.detail) {
            if (dtl.harga_baru>0) {
              this.details.push(dtl);
            }
          }
          this.clearDetail();
        },
  
        delDetails: function(index){
            this.details.splice(index, 1);
        },
        showModalPart: function(index) {
          $('.modalPart').modal('show');
          this.index_detail_part = index;
          console.log(this.index_detail_part);
        }
      },
      watch:{
        detail:function () {
          // alert('dd');
        }
      },
      computed: {
        // totDetail:function(detail) {
        //   po_fix     = detail.po_fix==''?0:detail.po_fix;
        //   qty_indent = detail.qty_indent==''?0:detail.qty_indent;
        //   total      = detail.harga * (parseInt(po_fix)+parseInt(qty_indent));
        //   ppn = total *(10/100);
        //   this.detail.total_harga = total+ppn;
        //   return total;
        // },
      },
  });
  function getItem() {
    values = {id_tipe_kendaraan:$('#id_tipe_kendaraan').val(),
              start_date:$('#start_date').val(),
              id_kelompok_harga:$('#id_kelompok_harga').val(),
             }
    $.ajax({
      url:'<?= base_url('master/kelompok_md/getItem') ?>',
      type:"POST",
      data: values,
      cache:false,
      dataType:'JSON',
      success:function(response){
        console.log(response);
        form_.detail=[];
        for (dtl of response) {
            form_.detail.push(dtl);
        }
      }
    }); 
  }
$('#submitBtn').click(function(){
  $('#form_').validate({
      rules: {
          'checkbox': {
              required: true
          }
      },
      highlight: function (input) {
          $(input).parents('.form-group').addClass('has-error');
      },
      unhighlight: function (input) {
          $(input).parents('.form-group').removeClass('has-error');
      }
  })
  var values = {details:form_.details};
  var form   = $('#form_').serializeArray();
  for (field of form) {
    values[field.name] = field.value;
  }
  if (form_.details.length==0) {
    alert('Detail belum ditentukan !');
    return false;
  }
  if ($('#form_').valid()) // check if form is valid
  {
    $.ajax({
      beforeSend: function() {
        $('#submitBtn').attr('disabled',true);
      },
      url:'<?= base_url('master/kelompok_md/'.$form) ?>',
      type:"POST",
      data: values,
      cache:false,
      dataType:'JSON',
      success:function(response){
        if (response.status=='sukses') {
          window.location = response.link;
        }else{
          alert(response.pesan);
          $('#submitBtn').attr('disabled',false);
        }
      },
      error:function(){
        alert("failure");
        $('#submitBtn').attr('disabled',false);
      },
      statusCode: {
        500: function() { 
          alert('fail');
          $('#submitBtn').attr('disabled',false);
        }
      }
    });
  }else{
    alert('Silahkan isi field required !')
  }
})
</script>
    <?php
    }elseif($set=="view"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/kelompok_md/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          <a href="master/kelompok_md/all">
            <button class="btn bg-green btn-flat margin"><i class="fa fa-list"></i> Daftar Kelompok Harga Jual MD</button>
          </a>  
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <th width="5%">No</th>
            <th>Kelompok Harga</th>
            <th>Jumlah Item</th>
            <th>Start Date</th>
            <th>Keterangan</th>
            <th>Status</th>
            <th>Aksi</th>
          </thead>
          <tbody>
            <?php $no=1;
              foreach ($data->result() as $rs) { ?>
                   <!-- <button class="btn btn-warning btn-flat btn-xs" onclick="getEditDetail('.$rs->id_kel.')">Edit</button>  -->
                <?php 
                  $tombol='';
                  if ($rs->status=='input') {
                   $tombol='
                   <a href="master/kelompok_md/edit?id='.$rs->id_kel.'" class="btn btn-warning btn-flat btn-xs">Edit</a> 
                   <a onclick="return confirm(\'Are you sure to send this data ?\')" href="master/kelompok_md/send?id='.$rs->id_kel.'" class="btn btn-info btn-flat btn-xs" >Send</a>';
                  }elseif ($rs->status=='Waiting Approval') {
                    $tombol='<a href="master/kelompok_md/approve?id='.$rs->id_kel.'" class="btn btn-primary btn-flat btn-xs" >Approve</a>';
                  }elseif ($rs->status=='rejected') {
                    // $tombol='<a href="master/kelompok_md/approve?id='.$rs->id_kel.'" class="btn btn-primary btn-flat btn-xs" >Approve</a>';
                    $tombol='
                   <a href="master/kelompok_md/edit?id='.$rs->id_kel.'" class="btn btn-warning btn-flat btn-xs">Edit</a> 
                   <a onclick="return confirm(\'Are you sure to send this data ?\')" href="master/kelompok_md/send?id='.$rs->id_kel.'" class="btn btn-info btn-flat btn-xs" >Send</a>';
                  }
                 ?>
                <tr>
                  <td><?php echo $no?></td>
                  <td><?php echo $rs->kelompok_harga?></td>
                  <td><?php echo $rs->jum?></td>
                  <td><?php echo $rs->start_date?></td> 
                  <td><?php echo $rs->keterangan?></td>
                  <td><?php echo $rs->status?></td>
                  <td align="center">
                      <?php echo $tombol?>
                  </td>
                </tr>
             <?php $no++; }
             ?>
          </tbody>
        </table>
<?php /* ?>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>Kelompok Harga</th>   
              <th>Item</th>      
              <th>Harga BBN</th>     
              <th>Harga Jual</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th width="5%">Active</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_kelompok_md->result() as $row) {       
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";
                if (!$row->harga_bbn) {
                  $harga_bbn ="";
                }else{
                  $harga_bbn=mata_uang2($row->harga_bbn);
                }
          echo "          
            <tr>
              <td>$no</td>                          
              <td>$row->kelompok_harga</td>              
              <td>$row->id_item</td>
              <td>".$harga_bbn."</td>
              <td>".mata_uang2($row->harga_jual)."</td>
              <td>$row->start_date</td>              
              <td>$row->end_date</td>
              <td>$active</td>              
              <td>";
              ?>
                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/kelompok_md/delete?id=<?php echo $row->id_kelompok_md ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/kelompok_md/edit?id=<?php echo $row->id_kelompok_md ?>'><i class='fa fa-edit'></i></a>
              </td>
            </tr>
          <?php
          $no++;
          }
          ?>
          </tbody>
        </table> <?php */ ?>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
    } elseif($set=="view_all"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/kelompok_md">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>          
          <a href="master/kelompok_md/history">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
          </a>
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
        <form action='master/kelompok_md/all' method='GET'>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-1 control-label">Start Date</label>
              <div class="col-sm-2">
                <input type="text" id="tanggal2" name="s" class="form-control" value="<?php echo $s ?>" placeholder="Start Date" autocomplete="off">
              </div>
              <label for="inputEmail3" class="col-sm-1 control-label"></label>                  
              <div class="col-sm-4">
                <button type="submit" name="filter" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Filter</button>                                  
              </div>                  
            </div>
        </form>
    </div>
    <div class="box-body">
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>Kelompok Harga</th>   
              <th>Item</th>      
              <th>Deskripsi </th>      
              <!-- <th>Harga BBN</th>     --> 
              <th>Harga Jual</th>
              <th>Start Date</th>
              <th>End Date</th>
            <?php /* ?>  <th width="10%">Action</th> */?>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_kelompok_md->result() as $row) {       
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";
                if (!$row->harga_bbn) {
                  $harga_bbn ="";
                }else{
                  $harga_bbn=mata_uang2($row->harga_bbn);
                }
            $sql = $this->m_admin->getByID("ms_kelompok_harga","id_kelompok_harga",$row->id_kelompok_harga)->row()->kelompok_harga;
            if(isset($sql)) $kelompok_harga = $sql;
              else $kelompok_harga = "";            
          echo "          
            <tr>
              <td>$no</td>                          
              <td>$kelompok_harga</td>              
              <td>$row->id_item</td>
              <td>$row->deskripsi_ahm - $row->tipe_ahm</td>
              
              <td>".mata_uang2($row->harga_jual)."</td>
              <td>$row->start_date</td>              
              <td>$row->end_date</td>";
              ?> 
            </tr>
          <?php
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  <?php }elseif($set=='history'){ ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/kelompok_md/all">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
          </a>          
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>Kelompok Harga</th>   
              <th>Item</th>      
              <!-- <th>Harga BBN</th>     --> 
              <th>Harga Jual</th>
              <th>Start Date</th>
              <th>End Date</th>
            <?php /* ?>  <th width="10%">Action</th> */?>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_kelompok_md->result() as $row) {       
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";
                if (!$row->harga_bbn) {
                  $harga_bbn ="";
                }else{
                  $harga_bbn=mata_uang2($row->harga_bbn);
                }
            $sql = $this->m_admin->getByID("ms_kelompok_harga","id_kelompok_harga",$row->id_kelompok_harga)->row()->kelompok_harga;
            if(isset($sql)) $kelompok_harga = $sql;
              else $kelompok_harga = "";            
          echo "          
            <tr>
              <td>$no</td>                          
              <td>$kelompok_harga</td>              
              <td>$row->id_item</td>
              
              <td>".mata_uang2($row->harga_jual)."</td>
              <td>$row->start_date</td>              
              <td>$row->end_date</td>";
              ?> 
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
    }
    ?>
  </section>
</div>
<!-- Modal Detail -->
<div class="modal fade modal_detail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title">Detail</h4>
      </div>
      <div class="modal-body" id="show_detail">
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function bulk_delete(){
  var list_id = [];
  $(".data-check:checked").each(function() {
    list_id.push(this.value);
  });
  if(list_id.length > 0){
    if(confirm('Are you sure delete this '+list_id.length+' data?'))
      {
        $.ajax({
          type: "POST",
          data: {id:list_id},
          url: "<?php echo site_url('master/kelompok_md/ajax_bulk_delete')?>",
          dataType: "JSON",
          success: function(data)
          {
            if(data.status){
              window.location.reload();
            }else{
              alert('Failed.');
            }                  
          },
          error: function (jqXHR, textStatus, errorThrown){
            alert('Error deleting data');
          }
        });
      }
    }else{
      alert('no data selected');
  }
}
function CekTargetMarket() { 
        var target_market = $(".id_kelompok_harga option:selected").attr("target_market");
        if (target_market=='Dealer Umum' || target_market=='Dealer Khusus') {
          $('.input_harga_bbn').addClass('hide');
        }else{
          $('.input_harga_bbn').removeClass('hide');
        }
}
  function generate()
{
  var value={id_kelompok_harga:$('#id_kelompok_harga').val(),
             tanggal:$('#tanggal').val(),
            }
  $.ajax({
       beforeSend: function() { $('#loading-status').show(); },
       url:"<?php echo site_url('master/kelompok_md/generate')?>",
       type:"POST",
       data:value,
       cache:false,
       success:function(html){
          $('#loading-status').hide();
          $('#showGenerate').html(html);
          getInput();
          $('#id_kelompok_harga').prop('disabled', true);
          $('#tanggal').prop('disabled', true);
       },
       statusCode: {
    500: function() {
      $('#loading-status').hide();
      alert("Something Wen't Wrong");
    }
  }
  });
}
function delData()
{
  $.ajax({
       beforeSend: function() { $('#loading-status').show(); },
       url:"<?php echo site_url('master/kelompok_md/delData')?>",
       type:"POST",
      ////// data:value,
       cache:false,
       success:function(html){
          $('#loading-status').hide();
          $('#id_kelompok_harga').prop('disabled', true);
          $('#tanggal').prop('disabled', true);
       },
       statusCode: {
    500: function() {
      $('#loading-status').hide();
      alert("Something Wen't Wrong");
    }
  }
  });
}
function getInput()
{
  var value={id_tipe_kendaraan:$('#id_tipe_kendaraan').val(),
            id_kelompok_harga:$('#id_kelompok_harga').val(),
            }
            var id_tipe_kendaraan=$('#id_tipe_kendaraan').val()
  $.ajax({
       beforeSend: function() { $('#loading-status').show(); },
       url:"<?php echo site_url('master/kelompok_md/getInput')?>",
       type:"POST",
       data:value,
       cache:false,
       success:function(html){
          $('#loading-status').hide();
          $('#showInput').html(html);
          getSelect2();
          priceformat();
       },
       statusCode: {
    500: function() {
      $('#loading-status').hide();
      alert("Something Wen't Wrong");
    }
  }
  });
}
function getEditDetail(a)
{
  var value={id_kel:a,edit:'y' }
  $.ajax({
       beforeSend: function() { $('#loading-status').show(); },
       url:"<?php echo site_url('master/kelompok_md/getEditDetail')?>",
       type:"POST",
       data:value,
       cache:false,
       success:function(html){
          $("#show_detail").html(html);
          $('.modal_detail').modal('show'); 
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
function getSelect2()
  {
    $(".select2").select2({
            placeholder: "- choose -",
            allowClear: false
        });
  }
  function submitForm() {
       $('#id_kelompok_harga').prop('disabled', false);
       $('#tanggal').prop('disabled', false);
       $("#form_add").submit();
    }
function generateDel()
{
  delData();
  generate();
}
function tampil_edit()
{
  var value={id:$('#id_kel').val()}
  $.ajax({
       beforeSend: function() { $('#loading-status').show(); },
       url:"<?php echo site_url('master/kelompok_md/t_edit')?>",
       type:"GET",
       data:value,
       cache:false,
       success:function(html){
          $('#loading-status').hide();
          $('#showEdit').html(html);
          generate();
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
<script type="text/javascript">
function cek_format(a){
  var tanpa_rupiah = document.getElementById('harga_jual');
  tanpa_rupiah.addEventListener('keyup', function(e)
  {
    tanpa_rupiah.value = formatRupiah(this.value);
  });
  tanpa_rupiah.addEventListener('keydown', function(event)
  {
    limitCharacter(event);
  });
}
function cek_format2(a){
  var tanpa_rupiah = document.getElementById('harga_jual_'+a);
  tanpa_rupiah.addEventListener('keyup', function(e)
  {
    tanpa_rupiah.value = formatRupiah(this.value);
  });
  tanpa_rupiah.addEventListener('keydown', function(event)
  {
    limitCharacter(event);
  });
}
  </script> 