<style>
  #us-map{
  margin: 0 auto;
  top: 0;
  left: 0;
  width: 30%;
  height: 30%;
}

path:hover, circle:hover {
  stroke: #002868 !important;
  /* stroke-width:2px; */
  /* stroke-linejoin: round; */
  /* #ce2026; */
  fill: #ce2026 !important;
  /* cursor: pointer; */
} 

#path67 {
  fill: none !important;
  stroke: #A9A9A9 !important;
  cursor: default;
}

 #info-box {
  display: none;
  position: absolute;
  top: 0px;
  left: 0px;
  z-index: 1;
  background-color: #ffffff;
  border: 2px solid #BF0A30;
  border-radius: 5px;
  padding: 5px;
  font-family: arial;
}

.map-box{
  width: 80%;
}
</style>
<body>
<div class="content-wrapper">
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Penerimaan</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
  <?php 

  
if($set=="detail_target"){?>
    <div class="box">
      <div class="box-header with-border">
      <a href="h1/ttm/target">
          <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
        </a>  

        <a href="h1/ttm/download_target_set/?id=<?=$this->input->get('id')?>">
          <button class="btn bg-blue btn-flat margin"><i class="fa fa-file"></i> Download</button>
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
              <th width="10%">Kode Dealer</th>
              <th width="20%">Dealer</th>
              <th width="12%">Kecamatan</th>
              <th width="12%">Wilayah</th>
              <th width="12%">Actual (M-1 SSU)</th>
              <th width="5%">Target</th>                 
              <th width="12%">Activity Set</th>                 
            </tr>
          </thead>
          <tbody>            
          <?php 

          $no=1; 
          foreach($sales_force_territory as $row){ ?>        
          <tr>
              <td><?=$no++?></td>
              <td ><?= $row['kode_dealer_md']?></td>
              <td><?= $row['nama_dealer']?></td>
              <td><?= $row['kecamatan']?></td>
              <td><? if ($row['id_ring'] == '9'){echo 'Other';}else if ($row['id_ring'] == '0'){echo 'Wilayah';} else {echo 'Ring '.$row['id_ring'] ;}?></td>
              <td width="20%"><?= $row['ssu']?></td>
              <td width="20%"><?= $row['jumlah']?></td>
              <td width="20%"><?= $row['activity']?></td>
          </tr>
              <?}?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<?}
  
else if($set=="add"){?>

<style>
      
      .hidden-input {
        border: none;
          background: none;
      }
  
      </style>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/ttm/master">
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
            <form class="form-horizontal" action="h1/ttm/insert" method="post" enctype="multipart/form-data">
              <div class="box-body">  

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="no_surat_sppm" name="no_surat_sppm">
                      <option>- choose -</option>
                      <?php 
                      $dt = $this->db->query("select nama_dealer, id_dealer from ms_dealer where h1='1' and active='1' order by nama_dealer asc");
                      foreach($dt->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->nama_dealer</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>       
          
                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Ring</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="ring" name="ring">
                      <option>- choose -</option>
                      <option value="0">Wilayah</option>
                      <option value="1">Ring 1</option>
                      <option value="2">Ring 2</option>
                      <option value="3">Ring 3</option>
                      <option value="9">other</option>
                    
                    </select>
                  </div>
                  <div class="col-sm-1">                  
                    <button onclick="" type="button" class="btn btn-flat btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
                  </div>                  
                </div> 
                

              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

<?}



else if($set=="target"){?>
    <div class="box">
  
      <div class="box-header with-border">
        <h3 class="box-title">     
        <a href="h1/ttm">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
          </a>  
            <button class="btn bg-blue btn-flat margin"  onclick="showModalUploadToTerritory()"><i class="fa fa-plus"></i> Upload Target</button>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div>
    </div><!-- /.box-header -->

    <div class="box">

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
      <div class="box-header with-border">
        <h3 class="box-title">
          Target From Territory Management
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>                          
              <th width="12%">No Register Territory</th>
              <th>Bulan</th>
              <th>Status</th>                 
              <th>Aksi</th>                 
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($sales_force_territory->result() as $row){ ?>        
        <tr>
              <td width="5%"><?=$no++?></td>
              <td width="20%"><a href="/h1/ttm/detail_target?id=<?=$row->id_sales_territory_generate?>" ><?= $row->id_sales_territory_generate?> </a></td>
              <td width="20%"><?= $row->bulan?></td>
              <td width="20%"> <? if($row->status == 0){ echo'Tidak Aktif';}else{echo'Aktif';}?></td>
              <td width="10%">
              <!-- <a data-toggle="tooltip" title="" class="btn btn-warning btn-sm btn-flat" href="h1/ttm/edit?id=<?php //$row->id_sales_territory_generate?>" data-original-title="Edit Data"><i class="fa fa-edit"></i></a> -->
              <a data-toggle="tooltip" title="" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="h1/ttm/delete_target?id=<?=$row->id_sales_territory_generate?>" data-original-title="Delete Data"><i class="fa fa-trash-o"></i></a>
            </td>
          </tr>
              <?}?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          Target From Tipe Kendaraan 
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>                          
              <th width="30%">No Register Tipe Kendaraan </th>
              <th width="20%">Bulan</th>
              <th width="10%">Status</th>                 
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($sales_force->result() as $row){ ?>        
            <tr>
              <td><?=$no++?></td>
              <td>   <a href="/h1/target_sales_from_md/detail_dealer?id=<?=$row->no_register_target_sales?>" ><?= $row->no_register_target_sales?> </a></td>
              <td><?= $row->priode_target?></td>
              <td><?= $row->status?></td>
          </tr>
              <?}?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <div class="modal fade" id="modalUploadToTerritory">
            <div class="modal-dialog" style='width:40%'>
              <div class="modal-content">
                <div class="modal-header bg-red disabled color-palette">
                  <button style='color:white' type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" align='center'>Upload Target Territory</h4>
                </div>
                <div class="modal-body">
                  <form id="form_upload_to_api2" class="form-horizontal">

                  <div class="form-group">
                      <label class="col-sm-4 control-label">Bulan</label>
                      <div class="form-input">
                        <div class="col-sm-7">
                        <div class='input-group date'>
                              <input type='text' class="form-control" id='datepicker' name= "month" placeholder="MM"/ required readonly>
                              <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                           </div>
                        </div>
                      </div>
                    </div>
                    <script type="text/javascript">
                      $(function () {
                          $('#datepicker').datepicker({			    
                              format: 'm',
                              minViewMode: 'months',
                              maxViewMode: 'months',
                              startView: 'months'
                          });
                      });
                    </script>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">Pilih File (.xlsx)</label>
                      <div class="form-input">
                        <div class="col-sm-7">
                          <input type="file" class="form-control" name="file_upload" required accept=".xlsx">
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-sm-12 col-md-12" align='center'>
                    <a href="<?=base_url('./downloads/target_sales_force_md/territory_array.xlsx') ?>" class="btn btn-success btn-flat">Template Upload</a>
                      <button type="button" class="btn btn-info btn-flat" onclick="buttonUploadToApi2(this)"><i class='fa fa-upload'></i> Upload</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
                      
          <link rel="stylesheet" href="<?php echo base_url() ?>assets/sweetalert2/sweetalert2.min.css">
          <script type="text/javascript" src="<?php echo base_url() ?>assets/sweetalert2/sweetalert2.min.js"></script>

          <script>
              function showModalUploadToTerritory() {
                $('#modalUploadToTerritory').modal('show');
              }
              function buttonUploadToApi2(el) {
                $('#form_upload_to_api2').validate({
                  highlight: function(element, errorClass, validClass) {
                    var elem = $(element);
                    if (elem.hasClass("select2-hidden-accessible")) {
                      $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
                    } else {
                      $(element).parents('.form-input').addClass('has-error');
                    }
                  },
                  unhighlight: function(element, errorClass, validClass) {
                    var elem = $(element);
                    if (elem.hasClass("select2-hidden-accessible")) {
                      $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
                    } else {
                      $(element).parents('.form-input').removeClass('has-error');
                    }
                  },
                  errorPlacement: function(error, element) {
                    var elem = $(element);
                    if (elem.hasClass("select2-hidden-accessible")) {
                      element = $("#select2-" + elem.attr("id") + "-container").parent();
                      error.insertAfter(element);
                    } else {
                      error.insertAfter(element);
                    }
                  }
                })

                if ($('#form_upload_to_api2').valid()) // check if form is valid
                {
                  Swal.fire({
                    title: 'Upload Territory',
                    text: 'Apakah Anda yakin melakukan upload file ini ? ',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                  }).then((result) => {
                    if (result.isConfirmed) {
                      var values = new FormData($('#form_upload_to_api2')[0]);
                      $.ajax({
                        beforeSend: function() {
                          set_errors = [];
                          $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
                          $(el).attr('disabled', true);
                        },
                        enctype: 'multipart/form-data',
                        url: "<?php   echo site_url('h1/ttm/upload_excel_territory')?>",
                        type: "POST",
                        data: values,
                        processData: false,
                        contentType: false,
                        cache: false,
                        dataType: 'JSON',
                        success: function(response) {
                          location.reload();

                          if (response.status == 1) {

                          //   const errorTableHTML = generateErrorTable(response.data);
                          //   Swal.fire({
                          //   icon: 'error',
                          //   title: response.pesan,
                          //   html: errorTableHTML,
                          // });

                          function generateErrorTable(errors) {
                            // const tableRows = errors.map((error) => {
                            //   const line = error['array-line'];
                            //   const errorMessages = Object.entries(error)
                            //     .filter(([key]) => key !== 'array-line')
                            //     .map(([, value]) => value);

                            //   let errorMessageHTML = '';
                            //   if (errorMessages.length === 1) {
                            //     errorMessageHTML = errorMessages[0];
                            //   } else {
                            //     errorMessageHTML = '<ul>';
                            //     errorMessageHTML += errorMessages.map(message => `<li>${message}</li>`).join('');
                            //     errorMessageHTML += '</ul>';
                            //   }

                            //   return `<tr>
                            //             <td>${line}</td>
                            //             <td>${errorMessageHTML}</td>
                            //           </tr>`;
                            // }).join('');

                            // return `<table id="detail" border="1" class="table table-bordered table-responsive" border=1 responsive>
                            //           <thead>
                            //             <tr>
                            //               <th>Line</th>
                            //               <th>Error Details</th>
                            //             </tr>
                            //           </thead>
                            //           <tbody>${tableRows}</tbody>
                            //         </table>`;
                          }

                            $('#modalUploadToTerritory').modal('hide');
                            $(el).html('<i class="fa fa-upload"></i> Upload');
                            $(el).attr('disabled', false);

                          }
                          },
                      
                        });
                         
                    } else if (result.isDenied) {
                      Swal.fire('Changes are not saved', '', 'info')
                    }
                  })
                }
              }
              </script>

<?}
else if($set=="view"){?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">     
          
        <a href="h1/ttm/master">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-database" aria-hidden="true"></i> Master Ring</button>
          </a>  
                  
        <a href="h1/ttm/target">
            <button class="btn bg-primary btn-flat margin"><i class="fa fa-plus"></i> Add Target TTM</button>
          </a>  

          <a href="h1/ttm/report">
            <button class="btn bg-yellow btn-flat margin"><i class="fa fa-file" aria-hidden="true"></i> Report</button>
          </a> 
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      </div><!-- /.box-header -->


      <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Mapping Provinsi Jambi</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->

      <div class="box-body">
        
      <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
      <div id="info-box"></div>

      <div class="row">
        <div class="col-md-5">
        <div class="map-box">

        <svg version="1.1"
      id="svg2" sodipodi:docname="Lokasi_Jambi_Kosong.svg" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="http://www.w3.org/2000/svg"
      xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 3548.8 2367.8"
      style="enable-background:new 0 0 3548.8 2367.8;" xml:space="preserve">

    <style type="text/css">
      .st0{display:none;fill-rule:evenodd;clip-rule:evenodd;fill:#C6ECFF;}
        .st1{fill-rule:evenodd;clip-rule:evenodd;fill:#F7D3AA;stroke:#A08070;stroke-width:5.812;stroke-linecap:square;stroke-linejoin:bevel;}
        .st2{fill-rule:evenodd;clip-rule:evenodd;fill:#FFFFE9;stroke:#000000;stroke-width:3.0222;stroke-linecap:square;stroke-linejoin:bevel;}
        .st3{fill-rule:evenodd;clip-rule:evenodd;fill:#B0000F;fill-opacity:0.9412;stroke:#000000;stroke-width:3.0222;stroke-linecap:square;stroke-linejoin:bevel;}
        .st4{fill-rule:evenodd;clip-rule:evenodd;fill:#C6ECFF;stroke:#000000;stroke-width:3.0222;stroke-linecap:square;stroke-linejoin:bevel;}
        .st5{display:none;fill-rule:evenodd;clip-rule:evenodd;fill:#FFFFE9;stroke:#000000;stroke-width:3.0222;stroke-linecap:square;stroke-linejoin:bevel;}
      .st6{display:none;}
    </style>


<sodipodi:namedview  bordercolor="#666666" borderopacity="1" gridtolerance="10" guidetolerance="10" id="namedview120" inkscape:current-layer="svg2" inkscape:cx="2594.0054" inkscape:cy="1407.6931" inkscape:pageopacity="0" inkscape:pageshadow="2" inkscape:window-height="700" inkscape:window-maximized="0" inkscape:window-width="1280" inkscape:window-x="0" inkscape:window-y="0" inkscape:zoom="2.9208883" objecttolerance="10" pagecolor="#ffffff" showgrid="false">
	</sodipodi:namedview>
<desc  id="desc4">Generated with Qt</desc>
<defs>
	
		<inkscape:perspective  id="perspective124" inkscape:persp3d-origin="531.45001 : 236.2004 : 1" inkscape:vp_x="0 : 354.3006 : 1" inkscape:vp_y="0 : 1000 : 0" inkscape:vp_z="1062.9 : 354.3006 : 1" sodipodi:type="inkscape:persp3d">
		</inkscape:perspective>
</defs>
          <path id="path34" class="st0" d="M2.9,2.9h3543v2362H2.9L2.9,2.9"/>
          <g id="g10" transform="translate(2.906004,2.906004)" vector-effect="non-scaling-stroke">
          </g>
          <g id="g12" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" vector-effect="non-scaling-stroke">
          </g>
          <g id="g18" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" vector-effect="non-scaling-stroke">
          </g>
          <g id="g20" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" vector-effect="non-scaling-stroke">
          </g>
          <g id="g26" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" vector-effect="non-scaling-stroke">
          </g>
          <g id="g28" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" vector-effect="non-scaling-stroke">
          </g>
          <g id="g30" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" vector-effect="non-scaling-stroke">
          </g>

          <path id="path40" class="st1" d="M786.6,1900.6l-5.4-5.2l6.6-1L786.6,1900.6"/>
          <path id="path42" class="st1" d="M3166.8,363.8l6.5,14.5l-8.6,10.3l-12.3-19.3L3166.8,363.8"/>
          <path id="path54" class="st1" d="M754.2,1970.2l-17.7,4.3l3.2-13.4l12.1-6.8L754.2,1970.2"/>
          <path id="path56" class="st1" d="M454.2,1512.1l-43-21.9l-5.4-21.9l46.7-17.3l31.5,6.2l3.9,45.1L454.2,1512.1"/>
          <path id="path58" class="st1" d="M3461.5,1012.6l-7.1,1l1-11.3L3461.5,1012.6"/>
          <path id="path60" class="st1" d="M3207.8,363.5l7.9,4.8l-46.2,25.2l3-26.2L3207.8,363.5"/>
          <path id="path62" class="st1" d="M3454.6,1001l-6.9,0.3l-5.9-12.8l-10.7,4.5l5.6-7l-12.8-2.4l1.6-9.4l-6,5.5l-8.3-11.5l-18.1,4.2
            l-1.7-11.7l-66.5,13.4l-1.6,15.5l-15.7,15.9l9,20.8l-1.4,20.5l-9.3,8.2l6.4,20.2l-10.3,16.2l-38.1,14.5l-9.3-20l-44.8-10.1
            l-166.1,47.1l-11.5,15.3l-62.3,4.6l-28.3-0.9l-36.1-41.5l-42.1,0l-38.9,36.3l-113.6,0.7l-41.7,56l-9.3-6.2l-34.2,2.8l-3.2-15
            l-23.4,8.1l-9.7-6.7l-37.8,1.4l-23,33.5l-26.9,7.1l-24.3,26.9l-16,68.5l17.2,7.7l33.6-8.6l18.1,15l-12.5,16.3l-3.8,35.3l-14.4,2.8
            l-6.8,15.2l-11.5,5.1l-7.5,19.4l4.2,14.8l-13.1,6.7l13.8,21.7l21.8-2.9l22.1,9.7l-36.7,28.5l-32.5,11.7l-32-2.5l-9.5,31.7l14.7,47
            l-8.1,17.2l1.9,24.1l-13.7,15.5l2,43.8l-15.6-19.8l-22.8-2.6l2.4-11.1l-18.9-26.9l-37.3-21l-29.7-53.3l-29-17.1l-5.3-31.4l-4.2,5.3
            l-13.5-21l-12.1-2.4l-2-21.7l-51.2,9l-25.6,29.3l-4,75.1l15.6,9.8l12.4,22.7l5.2,40.2l-41.8,15.2l-28.2,26.8l-19.4-3.6l-11.8-12.6
            l-21.3-1.2l-5.2-12l-16.8,1l-13.8-20.3l-21.7,8.7l1.6,18.2l-41.3,1.3l-115.8-35.6l-18.6,26.9l-17.4,5.6l-1.4,19.5l8.7,28l24.4,0.4
            l2.6,24l-49.7,49.7l-30.4,42.9l0.9,31.4l-36.4,16.7l-21.3,28.6l-12.6-24.6l-21.2,34.1l-8-3.9l-30.3,25.5l-26.1,1.4l-38.6,64.4
            l-83.3,1.1l-29,28.8l-21.6-1.7l-29-11.8l-32.6-2.3l-16.9-13.5l-1.1-27.5l-61,6.6l-51.3-41.1l-26.6,17.3l-15.3,59.5l-108.5,38.2
            l-14.7,9.8l-14.5,28.1l-10.9,3.5l-54.3-12l-45.7-33.4l-24-1.6l-14.9,11.2l-39.6,7.6l-48.2-12.2l-14.7-25.8l-30.7-13l-0.4-19.3
            l-45.7-27.2l-99.4-115l-14.7-0.9l-13.4,14l-20.3,4.7l-9,0.6l-8.7-11.2l-28.9,7.7l-15,24.6l-12.8,1.5l-4.9-18.5l6.3-52.2l13.6-37.2
            l-4.6-13.1l-138.3-80l-133.6-93.1l-20.7-175.1l-57.8-92.2l-0.8-28.5l-63.4-55.3l-1-23.5l21.8-8.1l15-33.3l-50.1-97.3l5.8-80.1
            l34.5,26.8l21.6-33l8.8,36.3l12.9,9.9l13.3-4.9l6.2,5.2l40-28.9l37.4,5.9l21.1-4l6.3-10.5l26.6,7.1l18-9.1l16.3,5l14-17.6l18.8,7.6
            l7.3,16.2l43.5-22.3l20.9,5.7l47.5-83l59.7-35.4l62.3-52.9l24.3-44l28.5-26.6l-27-43.4l27.3-35.4l-28.8-67.5l26.1-49l13.4-6.9
            l39.1,2.6l20.6-18l19.2,0.6l70.2-84.4l7.6-35.8l-15.3-5.9l-10,17.7l-14.3-12.9l-8.3,12.9l-12.6-9.8l-9.3-43.4l5.9-49.7l-5.4-13.8
            l10.4-13.5l22.1,4.3l3.5-19.1l37.9-6.9l10.8-13.5l1.5-35.1l19.3-0.1l9.7,9.6l6.9-14.4l11.4-0.3l28.4-25.5l16.1,2.5l14.5-7.6
            l32.5,21.3l20-7.9l57.7,25.7l16.7-18.7l22.7-1.8l27,25.8l0.5,18.6l12.3,2.7l26.6,1.4l23.3-21.3l46.8-1.7l10.8,8l14.4,33.2l18.7,0.1
            l37.8,30l-9.5,26l2.2,17l19-4.5l8.2,11.2l18.5-8l10.8,7.6l8-12.5l23.3,29.4l-2.8,23.5l7.8,4.5l31.9,6.9l16-5.2l47.5,21.1l21.4-10
            l42.8,5.5l21.5,24.6l21.8-15.5l3.9-13.1l23.3-8.6l8.3-18.5l24.8,9.2l4.9-27.5l11.8-16.2l12.7-12.7l20.5,4.5l-3.5-20.8l32.3-44.6
            l73.6-50.9l66.1-62.8l28-33.8l22-45.6l30.1-28.4l85.9-0.1l234.5,45.7l9.6,15.7l52.9-31.1l31.5-2.8l2.2-33.4l14.8-2.4l23.1,29.3
            l-3.9,29.1l8.6,9.4l33.6-1.3l27.5,10.3l10.3,30.5l-10.2,14.8l24.7,18.1l20.4-0.5l34.3,38.2l42.1,23.6l5.1,15.4l11.5-4.9l30.8,5.7
            l30.2,26.3l-0.8,13.3l55.6,1.3l6.2,19.8l22.4,1.6l81.7-33.9l43.3,12.8l68.3,5.5l85.2,33.1l20.8,10.5l-3,6l56.3,2.8l23.9,18.9
            l59-29.8l66.3,1.8l31.9-21.9l25.2,37.9l16.4,66.8l3.4,33.8l-15.4,31.2l-1.6,26l64.4,117.3l-4.9,53.5l5.7,32.6l-16.7,52.9l-2.1,34.9
            l28.5,110.2L3454.6,1001 M382.4,1019.9l-9.9-1l-24.2,20.8l14.6,9.9l17.1-2.5l8.2-9.5L382.4,1019.9 M465.3,1449.7l-59.5,18.5l1,17.3
            l36.5,26l43.6-8.2l-2.3-45.7L465.3,1449.7 M744.2,1960.2l-6.1,16.2l19.9-6.2l-4.4-15.9L744.2,1960.2"/>
          <path id="path72" class="st1"  data-info="<div>State: Arizona</div><div>Capital: Phoenix</div>" fill="#D3D3D3"  d="M362.9,1049.6l-14.7-13.1l24.3-17.5l9.9,1l4.2,20.7L362.9,1049.6"/>
          <path id="path76" class="st2"  data-info="<div>Kab. Batang Hari</div>" fill="#D3D3D3"   data-info="<div>State: Arizona</div><div>Capital: Phoenix</div>" fill="#D3D3D3"  d="M1687.7,1284.8l-15.8,7.9l-59.5-35l-52.7-13.7l-37.5,6.6l-21.8-8.7l-27.9,5.1l-45-18.3l6.2-43.5
            l6.1-21.2l10.3-4.5l6.9,25.8l28.6,6.3l37.9,22.3l21.7,0.9l73.5-61l28.5-46.7l0-28.9l9-22l18-4.8l-7.9-13.3l19.4-0.2l6.2-17.6l6,7.7
            l6.9-5.6l37.4-56.1l22.8-52.8l-2.4-41.1l20.8-40.8l1.7-46.2l-12-21.7l2.5-30.1l21.9-8.2l-2.7-10.1l9.6-7.2l69.6,5.8l44.4,48.3
            l21.6-2.4l27.2,15.8l13.8-4.4l-1.1,10.1l9.1,7.9l7.5-0.8l6.7-42.4l19.3-2.9l-7.2-23.2l47.5,24.6l14.9-4.9l0.6-10.8l10.3-2.1
            l16.4-22.7l1.3,8.7l13.6,2.1l8.5,13.6l1.2-8.8l39.8-22.9l49.8,44.5l8.5-7l-16.5-16.1l7.9-29.8l38-6l23,18.5l19.8,3.5l14.4,70.1
            l30.3,39.4l33.3-2.8l5.9,20.6l37.3,1l-2.8,56.4l-7.7,16.8l6.5,14.8l-6.5,11.4l-28.7,6.3l-6.3,10.3l11.3,6.9l-11.6,18.5l14.9,75.3
            l17.6,11.6l13.5,24.8l-8.2,79.9l6.8,122.5l-7.6,45.1l-36.8,57.2l49.1,117.4l-12.1,33.8l14.7,47l-8.1,17.2l1.9,24.1l-13.7,15.5
            l2,43.8l-15.6-19.8l-22.8-2.6l2.4-11.1l-18.9-26.9l-37.3-21l-29.7-53.3l-29-17.1l-5.3-31.4l-4.2,5.3l-13.5-21l-12.1-2.4l-2-21.7
            l-51.2,9l35.3-18.8l2.4-45.8l-17.4-18.9l-50.3-22.6l-32.1-33.6l14.2-33.1l32.4-22.1l1.8-9.9l-36.9,13.9l-34.2,2.5l-67.7-18.7
            l-54.9,14.5l-22.5-6.3l-33.2,39.1l-32-0.4l-9.6,22.8l-46.5,4.4l-46.9-16.9l9.2-23.5l-4-19.5L1687.7,1284.8"/>
          <path id="path78"  data-info="<div>Kab. Muara Bungo</div>" fill="#D3D3D3"  class="st2" d="M1227.7,751.5l6.7,19.2l14.6,1.7l18.8,27.6l-2.6,12.2l23.8,33.1l-37.7,66.7l-25.4,81.6l-29.9,56.2
            l5.5,11.1l-12.2,29.1l-26.9,33l-14.5,2.1l-10.7-10l-42,9.3l7.2,34.3l-14.3,6.6l-23.8,48l-7.2-8.3l-25.8-1.3l-12.6,4.7l-24.1,31.1
            l-16.1-2.2l-19.1-17.9l-10.2,6.3l-10.6-20.3l-40.5-20.8l-39.3-10.6l-13,5.5l-1.1-8.5l-21.4,0.7l-38.4-17.8l-36.3,10.5l-40.1,34.5
            l-31.9,11.2l-19.5-6.1l-55,7.8l-5.3,19.2l-149.7-137.5l-49.5-59.2l7.3-14.3l34.8-13.3l20.9,5.7l47.5-83l59.7-35.4l62.3-52.9l24.3-44
            l28.5-26.6l-27-43.4l27.3-35.4l-28.8-67.5l26.1-49l13.4-6.9l39.1,2.6l20.6-18l19.2,0.6l60.6-78.5l17.8-5.9l-2.3-13.9l23.6,18
            l10.3-10.1l22.5,2.1l-3.5,15.1l23.3-8.1l-3.5,14.2l9,2.9l-7.5,21.3l10.1,1.4l1.8-18.2l9.8,5.3l4.1,15.1l-12.6,2l-0.8,14.9
            l-19.8,23.7l8.1,35.6l31.5,14.4l57.3-0.6l24.5,24l14-1.9l25.6,19l-1.4,21.8l24.3,22.9l71.2,22.4l31.9,32.4l19-0.4L1227.7,751.5"/>
          <path id="path80" data-info="<div>Kab. Merangin</div>"  class="st2" d="M1085.3,1752.4l-0.4,14.6l26.8,52.2l4.4,30.7l-55.5,47.4l-5.4,17l8,21.2l-40.2,20.3l19.7,33.1
            l4.7,35.1l-10.4,13.8l4.9,22.3l-6.5,32.3l-8.9,8.1l-35.1-8.3l-45.7-33.4l-24-1.6l-14.9,11.2l-39.6,7.6l-48.2-12.2l-14.7-25.8
            l-30.7-13l-0.4-19.3l-45.7-27.2l-99.4-115l-14.7-0.9l-13.4,14l-20.3,4.7l-9,0.6l-8.7-11.2l-28.9,7.7l-15,24.6l-12.8,1.5l-5.1-15.5
            l5.4-50l14.8-44.3l-1.3-42.1l-10.1-13.5l5.6-37.5l-6.1-15.6l44.7,9.1l7.7-27l18.1-1.3l7.1,14l13.1,3.9l10.7-8.6l43.3,4l22.3-12.9
            l8.5-23.6l20.8-19.6l1.8-40.5l20.5-15.4l6.7-29.2l21.8-9.6l14.5-17.7l18.4,10l8-6.4l-10.2-1.8L764,1481l-13.4-3.6l-4.4-34.9
            l-28.6-30.7l0.5-21l-37.4-32.1l-2.8-27.7l-48.9-25.6l-14.3-17.6l-17.8-57l5.3-19.2l55-7.8l19.5,6.1l31.9-11.2l40.1-34.5l39.7-10.4
            l35,17.6l21.4-0.7l1.1,8.5l13-5.5l39.3,10.6l40.5,20.8l10.6,20.3l10.2-6.3l19.1,17.9l16.1,2.2l24.1-31.1l12.6-4.7l25.8,1.3l7.2,8.3
            l23.8-48l14.3-6.6l-7.2-34.3l42-9.3l10.7,10l21.5-5.3l19.9-29.8l89.6-84.5l77.7,22.5l68.6-44.5l50,3.8l7.1,6.4l5.2-14.3l12.2,3.1
            l3.2,7.6l-35.4,71l-52.5,23l-1.4,53.6l-18.2,39.5l4.2,48.3l32.1,108.9l27.2,20.4l35.4-1.3l19.4,12.6l26.3,21.9l42.6,59.4l-13.9,41.3
            l-33.9,22.4l-74.4,22.4l-43.3-4l-38.1,69.3l-23.2,69.3l-18.9,18.8l0,13.9l-9.4,3.4l1.4,6.8l-31.2,11.2l-2.3,10.9l-32.8-4.7
            l-3.9-41.7l-7.2-2.2l-44.3,18.6l-16.3,22.9l-27,7.9l-13.4,14.8L1085.3,1752.4"/>
          <path id="path82"  data-info="<div> Kab.Sarolangun</div>" fill="#D3D3D3"  class="st2" d="M1261.1,1971l-24.2-23.2l-19,7.5l-12.8,21.2l-10.7,48l-108.5,38.2l-34.8,41.1l-18.9-5l9.9-38.8
            l-4.9-22.3l10.4-13.8l-4.7-35.1l-19.7-33.1l40.2-20.3l-8-21.2l5.4-17l55.5-47.4l-4.4-30.7l-26.8-52.2l0.4-14.6l65.5-3.8l13.4-14.8
            l27-7.9l16.3-22.9l44.3-18.6l7.2,2.2l3.9,41.7l32.8,4.7l2.3-10.9l31.2-11.2l-1.4-6.8l9.4-3.4l0-13.9l18.9-18.8l23.2-69.3l38.1-69.3
            l43.3,4l74.4-22.4l33.9-22.4l13.9-41.3l-42.6-59.4l-26.3-21.9l-19.4-12.6l-35.4,1.3l-27.2-20.4l-29.2-93.6l21.7-12.9l52.9,20.9
            l18.3-7l26.5,8.8l37.5-6.6l52.7,13.7l59.5,35l20.9-10.4l17.9,5.1l26.9-7l19.4,7.8l18-6l6,20.1l-9.2,23.5l46.9,16.9l46.5-4.4
            l9.6-22.8l32,0.4l33.2-39.1l22.5,6.3l54.9-14.5l67.7,18.7l34.2-2.5l36.9-13.9l-1.8,9.9l-32.4,22.1l-14.4,30.5l28,32.9l55.9,26.7
            l17.4,22l-3.7,41.9l-46.4,26.9l-16.5,28.6l-1.9,69.6l15.4,7.8l12.4,22.7l5.2,40.2l-41.8,15.2l-28.2,26.8l-19.4-3.6l-11.8-12.6
            l-21.3-1.2l-5.2-12l-16.8,1l-13.8-20.3l-21.7,8.7l1.6,18.2l-41.3,1.3l-115.8-35.6l-18.6,26.9l-17.4,5.6l-1.4,19.5l8.7,28l24.4,0.4
            l2.6,24l-49.7,49.7l-30.4,42.9l0.9,31.4l-36.4,16.7l-21.3,28.6l-12.6-24.6l-21.2,34.1l-8-3.9l-30.3,25.5l-26.1,1.4l-24.1,49.7
            l-21.6,18.4l-76.1-2.5l-29,28.8l-18.5-1.2l-64.9-14.6l-16.9-13.5l-1.1-27.5l-37.7-0.5l-20.9,7.6L1261.1,1971"/>
          <path id="path84"  data-info="<div>Kab. Tebo</div>" class="st2" d="M1683.3,592.7l25.9,8.2l29,47.1l3.5,25.5l14.1,23.3l3.9,39.9l25.3,48.6l-1.7,46.2l-20.8,40.8l3,39
            l-23.4,54.8l-40,58.7l-10.3-4.7l-5.2,17.1l-20.1,0.2l7.6,13.7l-18,4.8l-9,22l0,28.9l-24.7,41.8l-64.8,60.4l-34.1,4.6l-37.9-22.3
            l-28.6-6.3l-4.1-23.6l-8.7-2.2l-16.8,69.2l-24.6,12.1l-7.1-63.6l18.2-39.5l1.4-53.6l52.5-23l35.7-75.4l-15.3-6.7l-5.7,14.6l-7.1-6.4
            l-50-3.8l-68.6,44.5l-77.7-22.5l-89.6,84.5l12.2-29.1l-5.5-11.1l29.9-56.2l25.4-81.6l37.7-66.7l-23.8-33.1l3-11.5l-19.2-28.3
            l-14.6-1.7l-12-26.5l-17.4,1.6l-34.1-33.8l-71.2-22.4l-24.3-22.9l2.4-20.7l-25.9-19.6l-14.8,1.4l-25-24.3l-56.8,0.8L923.9,590
            l-8.1-35.6l19.8-23.7l0.8-14.9l12.6-2l-4.1-15.1l-9.8-5.3l-1.8,18.2l-10.1-1.4l7.5-21.3l-9-2.9l4.8-13.3l-27.1,6.5l7.8-11.6
            l-6.1-4.4l-18.3-0.6l-10.3,10.1L850,454.6l1.3,14.1l-8.2,0.1L852,435l-14.2-8.1l-11.1,17.4l-15.6-12.4l-6.3,12.4l-8.5-1.6L784,419.9
            l-2.7-91.8l10.4-13.5l22.1,4.3l3.5-19.1l37.9-6.9l10.8-13.5l1.5-35.1l19.3-0.1l9.7,9.6l6.9-14.4l11.4-0.3l31.5-26.9l13,3.9l14.5-7.6
            l35,21.6l17.4-8.2l57.7,25.7l16.7-18.7l22.7-1.8l27,25.8l0.5,18.6l12.3,2.7l26.6,1.4l23.3-21.3l46.8-1.7l10.8,8l14.4,33.2l18.7,0.1
            l37.8,30l-9.5,26l2.2,17l19-4.5l8.2,11.2l18.5-8l10.8,7.6l8-12.5l23.3,29.4l-2.8,23.5l7.8,4.5l31.9,6.9l16-5.2l47.5,21.1l21.4-10
            l47.8,8.1l54.3,91.4l1,23.5l10,17.8L1683.3,592.7"/>         
          <path id="path86" data-info="<div> Kota Jambi</div>" fill="#D3D3D3"  class="st3" d="M2577.2,975.8l-49-1.4l-11.1-45.2l27.8-38.7l66.5-10.3l6,5.7l-2,61l20.4,23.8l-18,8.6l-15.6-7.2 L2577.2,975.8"/>
          <path id="path88" class="st4" d="M454.2,1512.1l-43-21.9l-5.4-21.9l46.7-17.3l31.5,6.2l3.9,45.1L454.2,1512.1"/>

          <path id="path90" data-info="<div> Kab. Kerinci </div>"  class="st2" d="M347.8,1682.6l-103.6-72.3l-10.9-95.7l42.6,3.3l32.6-18.3l44.3-11.9l27.4-48.7l10-4.9l-3.6-13.3
            l17.7-8.8l-8.1-21.3l4.7-12.6l-13-18.1l-11.9,6.8l-133.5-9.7l-41.9,42.8l-34.9-56.9l-0.8-28.5l-63.4-55.3l-1-23.5l21.8-8.1l15-33.3
            l-50.1-97.3l4.3-79.6l36,26.3l21.6-33l8.8,36.3l12.9,9.9l13.3-4.9l6.2,5.2l35.5-28.1l41.9,5.1l21.1-4l6.3-10.5l26.6,7.1l18-9.1
            l16.3,5l14-17.6l18.8,7.6l8.7,21.5l49.5,59.2l149.7,137.5l17.8,57l14.3,17.6l48.9,25.6l2.8,27.7l37.4,32.1l-0.5,21l28.6,30.7
            l4.4,34.9l13.4,3.6l22.7,32.3l10.2,1.8l-8,6.4l-18.4-10l-14.5,17.7l-21.8,9.6l-6.7,29.2l-20.5,15.4l-1.8,40.5l-20.8,19.6l-8.5,23.6
            l-22.3,12.9l-43.3-4l-10.7,8.6l-13.1-3.9l-7.1-14l-18.1,1.3l-7.7,27l-44.7-9.1l6.1,15.6l-5.6,37.5l10.4,15.6l-3.1,29.5L347.8,1682.6
            M416.4,1469.1l-12.3,1.3l2.7,15.2l36.5,26l44.6-9.3l-3.4-44.6l-9.6-5.3l-47.7,5.5L416.4,1469.1 M347.5,1038l18.8,11.6l21.9-12
            l-21.9,12L347.5,1038"/>
          <path id="path92" data-info="<div>Kab. Sungai Penuh</div>" class="st2" d="M397.8,1394.4l6.4,17.4l-17.7,8.8l3.6,13.3l-10,4.9l-30.3,51l-41.4,9.6l-32.6,18.3l-42.6-3.3
            l-9.8-79.4l-22.9-35.4l41.5-42.6l134,9.5l11.9-6.8l13,18.1L397.8,1394.4"/>
          <path id="path94" data-info="<div>Kab. Muara Jambi</div>" class="st2" d="M2861.5,1101.3l-36.8,35.7l-113.6,0.7l-41.7,56l-9.3-6.2l-34.2,2.8l-3.2-15l-23.4,8.1l-9.7-6.7
            l-37.8,1.4l-24,34.4l-25.9,6.2l-24.3,26.9l-16,68.5l17.2,7.7l33.6-8.6l18.1,15l-12.5,16.3l-3.8,35.3l-14.4,2.8l-6.8,15.2l-11.5,5.1
            l-7.5,19.4l4.2,14.8l-13.1,6.7l13.8,21.7l28.1-1.8l15.7,9.1l-36.5,28.1l-25.2,9.1l-36.7-2l-49.1-113.4l36.8-61.2l7.6-45.1
            l-6.8-122.5l8.3-77.7l-13.6-27l-18.8-15.3l-13.6-71.5l11.6-18.5l-11.3-6.9l6.3-10.3l28.7-6.3l6.5-11.4l-6.5-14.8l7.7-16.8l2.8-56.4
            l-37.3-1l-5.9-20.6l-33.3,2.8l-30.3-39.4l-14.4-70.1l-19.8-3.5l-14.7-16.6l-46.2,4.1l-7.9,29.8l16.5,16.1l-8.5,7l-48.9-44.3
            l-40.7,22.7l-1.2,8.8l-8.5-13.6l-13.6-2.1l-1.3-8.7l28.8-26.3l18.6,0.2l21.4-18.2l51.3-13.7l10.7-46.4l114.8-0.7l53.5,53.2
            l79.2,29.2l31.6,24.7l33.8,43l31.2,9.4l110-84.1l355-55.3l24.2,29l55.3,33.5l44.1,39.5l37.3,84.5l119.9,168l-2.2,15.8l-15.7,15.9
            l9,20.8l-1.4,20.5l-9.3,8.2l6.4,20.2l-10.3,16.2l-39.2,14.7l-8.2-20.1l-44.8-10.1l-166.1,47.1l-8.6,14l-17.7,3.3l-75.8,1.6
            l-36.1-41.5L2861.5,1101.3 M2555.3,889.7l-25.9,15.5l-12.3,24.1l11.1,45.2l71.9-2.5l17.8,7.4l17.2-7.2l-20.2-27.3l1.6-60.9l-20.8-4
            L2555.3,889.7"/>

          <path id="path96"  data-info="<div>Kab. Tanjung Jabung Barat</div>" fill="#D3D3D3"  class="st2" d="M2533.9,207.8l-12.2,41.5l7.4,9.7l-9.1,1.2l-2.6,25.1l-12.1,18.5l4.1,6.7l-22.9,23.9l-31.2,4.5
            l-16.5,11.6l-17.8,88.3l-29.1,58.5l-30.4,105.8l-79.6-7.1l-46.2,3.9l-10.7,46.4l-51.3,13.7l-21.4,18.2l-24.8,3.2l-39,45.5l-10.3,2.1
            l-0.6,10.8l-18.2,4.6l-17.6-16.1l-26.5-8.1l7.2,23.2L2003,746l-6.7,42.4l-13.9-3.4l-1.5-13.8l-13.8,4.4l-27.2-15.8l-21.6,2.4
            l-44.4-48.3l-28.6,0.9l-20.5-9.5l-20.5,2.8l-9.6,7.2l2.7,10.1l-21,7.2l-4.2,26.1l-16.5-35.2v-26.6l-33.6-78.6l-13-17.3l-25.9-8.2
            l-26-20.7l-10-17.8l-1-23.5l-40.2-58.9l4.8-16.8l19.4-9.3l3.9-13.1l23.3-8.6l8.3-18.5l24.8,9.2l4.9-27.5l11.8-16.2l12.7-12.7
            l20.5,4.5l-3.5-20.8l32.3-44.6l73.6-50.9l66.1-62.8l28-33.8l22-45.6l30.1-28.4l85.9-0.1l234.5,45.7l9.6,15.7l52.9-31.1l31.5-2.8
            l2.2-33.4l14.8-2.4l23.1,29.3l-3.9,29.1l8.6,9.4l33.6-1.3l26.6,9.2l11.1,31.6l-10.2,14.8L2533.9,207.8"/>
          
          <path id="path98"  data-info="<div>Kab. Muaro Jambi</div>"  class="st5" d="M3461.5,1012.6l-7.1,1l1-11.3L3461.5,1012.6"/>
          <path id="path100" class="st5" d="M3207.8,363.5l7.9,4.8l-46.2,25.2l3-26.2L3207.8,363.5"/>
          <path id="path102" class="st5" d="M3166.8,363.8l6.5,14.5l-8.6,10.3l-12.3-19.3L3166.8,363.8"/>
          
          <path id="path104"  data-info="<div>Kab. Tanjung Jabung Timur</div>" f class="st2" d="M2554.8,207.9l33.8,37.6l42.1,23.6l5.1,15.4l11.5-4.9l30.8,5.7l30.2,26.3l-0.8,13.3l55.6,1.3
            l6.2,19.8l22.4,1.6l81.7-33.9l43.3,12.8l68.3,5.5l85.2,33.1l20.8,10.5l-3,6l56.3,2.8l23.9,18.9l59-29.8l66.3,1.8l16.5-6.8l10.1-16.1
            l8.7,3.5l24.3,42.1l13.9,60.2l3.4,33.8l-15.4,31.2l-1.6,26l64.4,117.3l-4.9,53.5l5.7,32.6l-16.7,52.9l-2.1,34.9l28.5,110.2l26.4,50
            l-7.3,0.7l-5.9-12.8l-10.7,4.5l5.6-7l-12.8-2.4l1.6-9.4l-6,5.5l-8.3-11.5l-18.1,4.2l-1.7-11.7l-66,13.1l-5.8-5.2l-114.1-162.8
            l-37.3-84.5l-44.1-39.5l-55.3-33.5l-24.2-29l-355,55.3l-85.9,69.8l-29.5,14.5l-35.7-17.7l-23.9-35l-31.6-24.7l-86.3-35.3l-35.4-43.1
            l2.3-21.5l14.7-27.6l13.4-56.7l29.1-58.5l12.2-73.8l14.1-21l39.3-9.6l22.9-23.9l-4.1-6.7l12.1-18.5l2.6-25.1l9.1-1.2l-7.4-9.7
            l8.5-36.5l5.9-6.6L2554.8,207.9"/>
          <g id="g106" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" class="st6">
          </g>
          <g id="g108" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" class="st6">
          </g>
          <g id="g110" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" class="st6">
          </g>
          <g id="g112" transform="translate(2.906004,2.906004)" class="st6">
          </g>
          <g id="g114" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" vector-effect="non-scaling-stroke" class="st6">
          </g>
          <g id="g116" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" vector-effect="non-scaling-stroke" class="st6">
          </g>
          <g id="g118" transform="translate(2.906004,2.906004)" vector-effect="non-scaling-stroke" class="st6">
          </g>
          </svg>
        </div> 
        <!-- MAP BOX END -->
        </div>
        </div>
      </div>
      </div><!-- /.box-header -->

      <? /*

      
      <div class="box">
         <div class="box-header with-border">
         <h3 class="box-title">Wilayah Actual</h3>     
        </div><!-- /.box-header -->

        <div class="box-body with-border">
        <style> 
        .table_wrapper{
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
        </style>
        <table class="table table-bordered table-hover table_wrapper">
          <thead>
            <tr>              
              <th  width="5%">Wilayah</th>                          
              <?
                foreach($dealer as $row){?>
                <th width="20%" title="<?=$row->nama_dealer ?>"><?=$row->kode_dealer_md ?></th>
                <?}
              ?>
            </tr>
          </thead>
          <tbody>   
            <td>Other</td>
              <td>2</td>
              <td>2</td>
              <td>3</td>
            </tr>
          </tbody>   
          <tfoot>
          <td><b>Total</b></td>
              <td>8</td>
              <td>10</td>
              <td>15</td>
            </tr>
          </tfoot>
          </table>   
        </div><!-- /.box-header -->
      </div>

    

      <div class="box">
         <div class="box-header with-border">
         <h3 class="box-title">Wilayah Actual</h3>     
        </div><!-- /.box-header -->

        <div class="box-body with-border">
        <style> 
        .table_wrapper{
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
        </style>
        <table class="table table-bordered table-hover table_wrapper">
          <thead>
            <tr>              
              <th><button></button></th>
              <th  width="5%">Wilayah</th>                          
              <?
                foreach($dealer as $row){?>
                <th width="20%" title="<?=$row->nama_dealer ?>"><?=$row->kode_dealer_md ?></th>
                <?}
              ?>
            </tr>
          </thead>
          <tbody>   
          <tr>
              <td>1</td>
              <td width="200px">Wilayah</td>
              <td >1</td>
              <td>2</td>
              <td>3</td>
            </tr> 
            <tr>
              <td>2</td>
              <td width="200px">Ring 1</td>
              <td >1</td>
              <td>2</td>
              <td>3</td>
            </tr>   
            <tr>
            <td>3</td>
            <td>Ring 2</td>
              <td>2</td>
              <td>2</td>
              <td>3</td>
            </tr>
            <tr>
            <td>4</td>
            <td>Ring 3</td>
              <td>2</td>
              <td>2</td>
              <td>3</td>
            </tr>
            <tr>
            <td>3</td>
            <td>Other</td>
              <td>2</td>
              <td>2</td>
              <td>3</td>
            </tr>
          </tbody>   
          <tfoot>
          <td><b>Total</b></td>
              <td>8</td>
              <td>10</td>
              <td>15</td>
            </tr>
          </tfoot>
          </table>   
        </div><!-- /.box-header -->
      </div>

        */?>


      <!-- BOX -->
      <div class="box" ">
         <div class="box-header with-border">
         <h3 class="box-title">Wilayah Actual</h3>     <br><br>

         <div class="row">
              <div class="col-md-4">
                <label for="">Nama Dealer</label>
                <select class="form-control select2 combo_change" id="id_dealer"  name="dealer[]" multiple="multiple" >
                      <option value="">- choose -</option>   
                      <?php 
                      foreach($dealer as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->nama_dealer</option>;
                        ";
                      }     ?>  
                    </select>       

                <label for="">Kabupaten</label>
                <select class="form-control select2 combo_change" required id="kabupaten" name="kabupaten"  multiple="multiple"  >
                      <option value="">- choose -</option>    
                      <?php 
                      foreach($kabupaten as $val) {
                        echo "
                        <option value='$val->id_kabupaten'>$val->kabupaten</option>;
                        ";
                      }     ?>            
                    </select>

              </div>
              </div>
         
        </div><!-- /.box-header -->
        <div class="box-body with-border" style="height:400px;width:100%; overflow:auto;">
        <style> 

        .tableFixHead          
        { overflow: auto; height: 300px; }

        .tableFixHead thead th
         { position: sticky; top: 0; z-index: 1; }

        .table_wrapper{
            display: block;
            overflow-x: auto;
            white-space: nowrap;
          }

          .table_horizontal{
            display: block;
            overflow-x: auto;
            overflow-y: auto;
            white-space: nowrap;
          }
        </style>

   
        <table class="table table-bordered table_wrapper table_horizontal tableFixHead"  >
        <!-- <table class="table table-bordered table_wrapper table_horizontal tableFixHead" id="example2" width='200px'> -->
          <thead>
            <tr>              
              <th class="dealer-th"><Button ><i class="fa fa-exchange" aria-hidden="true"></i></Button></th>
              <th width="5%">Wilayah</th>                          
              <th width="5%">Kabupaten</th>                          
              <!-- <th width="5%">id_kecamatan</th>                           -->
                <?
                foreach($dealer_set as $row){?>
                <th class="dealer-modal" width="20%" style="padding-right: 20px;" title="<?=$row->nama_dealer ?>"><?=$row->nama_dealer ?></th>
                <?}
              ?>
            </tr>
          </thead>
          <tbody>   
              <?

              $no = 1;
                foreach($ring as $key => $row){?>
                <tr>
                  <td><?=$no++?></td>
                <td width="20%" ><?=$row['kecamatan'] ?></td>
                <td width="20%" ><?=$row['kabupaten'];?></td>
                <!-- <td width="20%" ><? //$row['id_kecamatan'];?></td> -->
               <?php 
       
               foreach($row['data'] as $jel =>  $set){
                ?>
                <td width="20%" ><?php if($set->tot ==0){ echo '-'; }else{  echo'Ring : ' .$set->tot;} ?></td>
                  <?} ?>
                </tr>
                <?}
              ?>
            
          </tfoot>
          </table>   
          </div>
        </div><!-- /.box-header -->
      </div>

    <div class="modal fade" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Title</h4>
        </div>
        
        <!-- Modal Body -->
        <div class="modal-body">
          <p>Modal body text goes here.</p>
          <!-- Table -->
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Header 1</th>
                <th>Header 2</th>
                <th>Header 3</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Row 1, Column 1</td>
                <td>Row 1, Column 2</td>
                <td>Row 1, Column 3</td>
              </tr>
              <tr>
                <td>Row 2, Column 1</td>
                <td>Row 2, Column 2</td>
                <td>Row 2, Column 3</td>
              </tr>
              <tr>
                <td>Row 3, Column 1</td>
                <td>Row 3, Column 2</td>
                <td>Row 3, Column 3</td>
              </tr>
            </tbody>
          </table>
        </div>
        </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>

        <script>
        $(document).ready(function(){

          $('.js-example-basic-multiple').select2();

            $('.dealer-th').click(function(){
                $(this).toggleClass('active');
            });

            $('.dealer-modal').click(function(){
              $('#myModal').show();
            });

            $(".combo_change").change(function(){

            // var selectedValues = $(this).val() || [];

            var selectedValues = 103;

              $.ajax({
                  url: 'h1/ttm/table_process', 
                  type: 'POST',
                  data: { selectedValue: selectedOption },
                  success: function(response) {
                      $("#result").html("Server response: " + response);
                  },
                  error: function(xhr, status, error) {
                      // Handle error
                      console.error(xhr.responseText);
                  }
              });
           
           
              });

        });
        </script>

      <script>
        
      $("path, circle").hover(function(e) {
        $('#info-box').css('display','block');
        $('#info-box').html($(this).data('info'));
      });

      $("path, circle").mouseleave(function(e) {
        $('#info-box').css('display','none');
      });

      $(document).mousemove(function(e) {
        $('#info-box').css('top',e.pageY-$('#info-box').height()-30);
        $('#info-box').css('left',e.pageX-($('#info-box').width())/2);
      }).mouseover();

      var ios = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
      if(ios) {
        $('a').on('click touchend', function() { 
          var link = $(this).attr('href');   
          window.open(link,'_blank');
          return false;
        });
      }
    </script>
      </script>


      <?php /* 

      <div class="box-header with-border">
        <h3 class="box-title">     
            <div class="row">
              <div class="col-md-12">
                <label for="">Nama Dealer</label>
                <select class="form-control select2" required id="id_dealer" name="id_dealer" >
                      <option value="">- choose -</option>   
                      <?php 
                      foreach($dealer as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->nama_dealer</option>;
                        ";
                      }     ?>  
                    </select>       

                <label for="">Kabupaten</label>
                <select class="form-control select2" required id="kabupaten" name="kabupaten" >
                      <option value="">- choose -</option>    
                      <?php 
                      foreach($kabupaten as $val) {
                        echo "
                        <option value='$val->id_kabupaten'>$val->kabupaten</option>;
                        ";
                      }     ?>            
                    </select>

              </div>
            </div>
        </h3>
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

      <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
      <div id="info-box"></div>

      <div class="row">
        <div class="col-md-5">
        <div class="map-box">

        <svg version="1.1"
      id="svg2" sodipodi:docname="Lokasi_Jambi_Kosong.svg" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="http://www.w3.org/2000/svg"
      xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 3548.8 2367.8"
      style="enable-background:new 0 0 3548.8 2367.8;" xml:space="preserve">

    <style type="text/css">
      .st0{display:none;fill-rule:evenodd;clip-rule:evenodd;fill:#C6ECFF;}
        .st1{fill-rule:evenodd;clip-rule:evenodd;fill:#F7D3AA;stroke:#A08070;stroke-width:5.812;stroke-linecap:square;stroke-linejoin:bevel;}
        .st2{fill-rule:evenodd;clip-rule:evenodd;fill:#FFFFE9;stroke:#000000;stroke-width:3.0222;stroke-linecap:square;stroke-linejoin:bevel;}
        .st3{fill-rule:evenodd;clip-rule:evenodd;fill:#B0000F;fill-opacity:0.9412;stroke:#000000;stroke-width:3.0222;stroke-linecap:square;stroke-linejoin:bevel;}
        .st4{fill-rule:evenodd;clip-rule:evenodd;fill:#C6ECFF;stroke:#000000;stroke-width:3.0222;stroke-linecap:square;stroke-linejoin:bevel;}
        .st5{display:none;fill-rule:evenodd;clip-rule:evenodd;fill:#FFFFE9;stroke:#000000;stroke-width:3.0222;stroke-linecap:square;stroke-linejoin:bevel;}
      .st6{display:none;}
    </style>


<sodipodi:namedview  bordercolor="#666666" borderopacity="1" gridtolerance="10" guidetolerance="10" id="namedview120" inkscape:current-layer="svg2" inkscape:cx="2594.0054" inkscape:cy="1407.6931" inkscape:pageopacity="0" inkscape:pageshadow="2" inkscape:window-height="700" inkscape:window-maximized="0" inkscape:window-width="1280" inkscape:window-x="0" inkscape:window-y="0" inkscape:zoom="2.9208883" objecttolerance="10" pagecolor="#ffffff" showgrid="false">
	</sodipodi:namedview>
<desc  id="desc4">Generated with Qt</desc>
<defs>
	
		<inkscape:perspective  id="perspective124" inkscape:persp3d-origin="531.45001 : 236.2004 : 1" inkscape:vp_x="0 : 354.3006 : 1" inkscape:vp_y="0 : 1000 : 0" inkscape:vp_z="1062.9 : 354.3006 : 1" sodipodi:type="inkscape:persp3d">
		</inkscape:perspective>
</defs>
          <path id="path34" class="st0" d="M2.9,2.9h3543v2362H2.9L2.9,2.9"/>
          <g id="g10" transform="translate(2.906004,2.906004)" vector-effect="non-scaling-stroke">
          </g>
          <g id="g12" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" vector-effect="non-scaling-stroke">
          </g>
          <g id="g18" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" vector-effect="non-scaling-stroke">
          </g>
          <g id="g20" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" vector-effect="non-scaling-stroke">
          </g>
          <g id="g26" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" vector-effect="non-scaling-stroke">
          </g>
          <g id="g28" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" vector-effect="non-scaling-stroke">
          </g>
          <g id="g30" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" vector-effect="non-scaling-stroke">
          </g>

          <path id="path40" class="st1" d="M786.6,1900.6l-5.4-5.2l6.6-1L786.6,1900.6"/>
          <path id="path42" class="st1" d="M3166.8,363.8l6.5,14.5l-8.6,10.3l-12.3-19.3L3166.8,363.8"/>
          <path id="path54" class="st1" d="M754.2,1970.2l-17.7,4.3l3.2-13.4l12.1-6.8L754.2,1970.2"/>
          <path id="path56" class="st1" d="M454.2,1512.1l-43-21.9l-5.4-21.9l46.7-17.3l31.5,6.2l3.9,45.1L454.2,1512.1"/>
          <path id="path58" class="st1" d="M3461.5,1012.6l-7.1,1l1-11.3L3461.5,1012.6"/>
          <path id="path60" class="st1" d="M3207.8,363.5l7.9,4.8l-46.2,25.2l3-26.2L3207.8,363.5"/>
          <path id="path62" class="st1" d="M3454.6,1001l-6.9,0.3l-5.9-12.8l-10.7,4.5l5.6-7l-12.8-2.4l1.6-9.4l-6,5.5l-8.3-11.5l-18.1,4.2
            l-1.7-11.7l-66.5,13.4l-1.6,15.5l-15.7,15.9l9,20.8l-1.4,20.5l-9.3,8.2l6.4,20.2l-10.3,16.2l-38.1,14.5l-9.3-20l-44.8-10.1
            l-166.1,47.1l-11.5,15.3l-62.3,4.6l-28.3-0.9l-36.1-41.5l-42.1,0l-38.9,36.3l-113.6,0.7l-41.7,56l-9.3-6.2l-34.2,2.8l-3.2-15
            l-23.4,8.1l-9.7-6.7l-37.8,1.4l-23,33.5l-26.9,7.1l-24.3,26.9l-16,68.5l17.2,7.7l33.6-8.6l18.1,15l-12.5,16.3l-3.8,35.3l-14.4,2.8
            l-6.8,15.2l-11.5,5.1l-7.5,19.4l4.2,14.8l-13.1,6.7l13.8,21.7l21.8-2.9l22.1,9.7l-36.7,28.5l-32.5,11.7l-32-2.5l-9.5,31.7l14.7,47
            l-8.1,17.2l1.9,24.1l-13.7,15.5l2,43.8l-15.6-19.8l-22.8-2.6l2.4-11.1l-18.9-26.9l-37.3-21l-29.7-53.3l-29-17.1l-5.3-31.4l-4.2,5.3
            l-13.5-21l-12.1-2.4l-2-21.7l-51.2,9l-25.6,29.3l-4,75.1l15.6,9.8l12.4,22.7l5.2,40.2l-41.8,15.2l-28.2,26.8l-19.4-3.6l-11.8-12.6
            l-21.3-1.2l-5.2-12l-16.8,1l-13.8-20.3l-21.7,8.7l1.6,18.2l-41.3,1.3l-115.8-35.6l-18.6,26.9l-17.4,5.6l-1.4,19.5l8.7,28l24.4,0.4
            l2.6,24l-49.7,49.7l-30.4,42.9l0.9,31.4l-36.4,16.7l-21.3,28.6l-12.6-24.6l-21.2,34.1l-8-3.9l-30.3,25.5l-26.1,1.4l-38.6,64.4
            l-83.3,1.1l-29,28.8l-21.6-1.7l-29-11.8l-32.6-2.3l-16.9-13.5l-1.1-27.5l-61,6.6l-51.3-41.1l-26.6,17.3l-15.3,59.5l-108.5,38.2
            l-14.7,9.8l-14.5,28.1l-10.9,3.5l-54.3-12l-45.7-33.4l-24-1.6l-14.9,11.2l-39.6,7.6l-48.2-12.2l-14.7-25.8l-30.7-13l-0.4-19.3
            l-45.7-27.2l-99.4-115l-14.7-0.9l-13.4,14l-20.3,4.7l-9,0.6l-8.7-11.2l-28.9,7.7l-15,24.6l-12.8,1.5l-4.9-18.5l6.3-52.2l13.6-37.2
            l-4.6-13.1l-138.3-80l-133.6-93.1l-20.7-175.1l-57.8-92.2l-0.8-28.5l-63.4-55.3l-1-23.5l21.8-8.1l15-33.3l-50.1-97.3l5.8-80.1
            l34.5,26.8l21.6-33l8.8,36.3l12.9,9.9l13.3-4.9l6.2,5.2l40-28.9l37.4,5.9l21.1-4l6.3-10.5l26.6,7.1l18-9.1l16.3,5l14-17.6l18.8,7.6
            l7.3,16.2l43.5-22.3l20.9,5.7l47.5-83l59.7-35.4l62.3-52.9l24.3-44l28.5-26.6l-27-43.4l27.3-35.4l-28.8-67.5l26.1-49l13.4-6.9
            l39.1,2.6l20.6-18l19.2,0.6l70.2-84.4l7.6-35.8l-15.3-5.9l-10,17.7l-14.3-12.9l-8.3,12.9l-12.6-9.8l-9.3-43.4l5.9-49.7l-5.4-13.8
            l10.4-13.5l22.1,4.3l3.5-19.1l37.9-6.9l10.8-13.5l1.5-35.1l19.3-0.1l9.7,9.6l6.9-14.4l11.4-0.3l28.4-25.5l16.1,2.5l14.5-7.6
            l32.5,21.3l20-7.9l57.7,25.7l16.7-18.7l22.7-1.8l27,25.8l0.5,18.6l12.3,2.7l26.6,1.4l23.3-21.3l46.8-1.7l10.8,8l14.4,33.2l18.7,0.1
            l37.8,30l-9.5,26l2.2,17l19-4.5l8.2,11.2l18.5-8l10.8,7.6l8-12.5l23.3,29.4l-2.8,23.5l7.8,4.5l31.9,6.9l16-5.2l47.5,21.1l21.4-10
            l42.8,5.5l21.5,24.6l21.8-15.5l3.9-13.1l23.3-8.6l8.3-18.5l24.8,9.2l4.9-27.5l11.8-16.2l12.7-12.7l20.5,4.5l-3.5-20.8l32.3-44.6
            l73.6-50.9l66.1-62.8l28-33.8l22-45.6l30.1-28.4l85.9-0.1l234.5,45.7l9.6,15.7l52.9-31.1l31.5-2.8l2.2-33.4l14.8-2.4l23.1,29.3
            l-3.9,29.1l8.6,9.4l33.6-1.3l27.5,10.3l10.3,30.5l-10.2,14.8l24.7,18.1l20.4-0.5l34.3,38.2l42.1,23.6l5.1,15.4l11.5-4.9l30.8,5.7
            l30.2,26.3l-0.8,13.3l55.6,1.3l6.2,19.8l22.4,1.6l81.7-33.9l43.3,12.8l68.3,5.5l85.2,33.1l20.8,10.5l-3,6l56.3,2.8l23.9,18.9
            l59-29.8l66.3,1.8l31.9-21.9l25.2,37.9l16.4,66.8l3.4,33.8l-15.4,31.2l-1.6,26l64.4,117.3l-4.9,53.5l5.7,32.6l-16.7,52.9l-2.1,34.9
            l28.5,110.2L3454.6,1001 M382.4,1019.9l-9.9-1l-24.2,20.8l14.6,9.9l17.1-2.5l8.2-9.5L382.4,1019.9 M465.3,1449.7l-59.5,18.5l1,17.3
            l36.5,26l43.6-8.2l-2.3-45.7L465.3,1449.7 M744.2,1960.2l-6.1,16.2l19.9-6.2l-4.4-15.9L744.2,1960.2"/>
          <path id="path72" class="st1"  data-info="<div>State: Arizona</div><div>Capital: Phoenix</div>" fill="#D3D3D3"  d="M362.9,1049.6l-14.7-13.1l24.3-17.5l9.9,1l4.2,20.7L362.9,1049.6"/>
          <path id="path76" class="st2"  data-info="<div>Lokasi : Batang Hari</div>" fill="#D3D3D3"   data-info="<div>State: Arizona</div><div>Capital: Phoenix</div>" fill="#D3D3D3"  d="M1687.7,1284.8l-15.8,7.9l-59.5-35l-52.7-13.7l-37.5,6.6l-21.8-8.7l-27.9,5.1l-45-18.3l6.2-43.5
            l6.1-21.2l10.3-4.5l6.9,25.8l28.6,6.3l37.9,22.3l21.7,0.9l73.5-61l28.5-46.7l0-28.9l9-22l18-4.8l-7.9-13.3l19.4-0.2l6.2-17.6l6,7.7
            l6.9-5.6l37.4-56.1l22.8-52.8l-2.4-41.1l20.8-40.8l1.7-46.2l-12-21.7l2.5-30.1l21.9-8.2l-2.7-10.1l9.6-7.2l69.6,5.8l44.4,48.3
            l21.6-2.4l27.2,15.8l13.8-4.4l-1.1,10.1l9.1,7.9l7.5-0.8l6.7-42.4l19.3-2.9l-7.2-23.2l47.5,24.6l14.9-4.9l0.6-10.8l10.3-2.1
            l16.4-22.7l1.3,8.7l13.6,2.1l8.5,13.6l1.2-8.8l39.8-22.9l49.8,44.5l8.5-7l-16.5-16.1l7.9-29.8l38-6l23,18.5l19.8,3.5l14.4,70.1
            l30.3,39.4l33.3-2.8l5.9,20.6l37.3,1l-2.8,56.4l-7.7,16.8l6.5,14.8l-6.5,11.4l-28.7,6.3l-6.3,10.3l11.3,6.9l-11.6,18.5l14.9,75.3
            l17.6,11.6l13.5,24.8l-8.2,79.9l6.8,122.5l-7.6,45.1l-36.8,57.2l49.1,117.4l-12.1,33.8l14.7,47l-8.1,17.2l1.9,24.1l-13.7,15.5
            l2,43.8l-15.6-19.8l-22.8-2.6l2.4-11.1l-18.9-26.9l-37.3-21l-29.7-53.3l-29-17.1l-5.3-31.4l-4.2,5.3l-13.5-21l-12.1-2.4l-2-21.7
            l-51.2,9l35.3-18.8l2.4-45.8l-17.4-18.9l-50.3-22.6l-32.1-33.6l14.2-33.1l32.4-22.1l1.8-9.9l-36.9,13.9l-34.2,2.5l-67.7-18.7
            l-54.9,14.5l-22.5-6.3l-33.2,39.1l-32-0.4l-9.6,22.8l-46.5,4.4l-46.9-16.9l9.2-23.5l-4-19.5L1687.7,1284.8"/>
          <path id="path78"  data-info="<div>State: Arizona</div><div>Capital: Phoenix</div>" fill="#D3D3D3"  class="st2" d="M1227.7,751.5l6.7,19.2l14.6,1.7l18.8,27.6l-2.6,12.2l23.8,33.1l-37.7,66.7l-25.4,81.6l-29.9,56.2
            l5.5,11.1l-12.2,29.1l-26.9,33l-14.5,2.1l-10.7-10l-42,9.3l7.2,34.3l-14.3,6.6l-23.8,48l-7.2-8.3l-25.8-1.3l-12.6,4.7l-24.1,31.1
            l-16.1-2.2l-19.1-17.9l-10.2,6.3l-10.6-20.3l-40.5-20.8l-39.3-10.6l-13,5.5l-1.1-8.5l-21.4,0.7l-38.4-17.8l-36.3,10.5l-40.1,34.5
            l-31.9,11.2l-19.5-6.1l-55,7.8l-5.3,19.2l-149.7-137.5l-49.5-59.2l7.3-14.3l34.8-13.3l20.9,5.7l47.5-83l59.7-35.4l62.3-52.9l24.3-44
            l28.5-26.6l-27-43.4l27.3-35.4l-28.8-67.5l26.1-49l13.4-6.9l39.1,2.6l20.6-18l19.2,0.6l60.6-78.5l17.8-5.9l-2.3-13.9l23.6,18
            l10.3-10.1l22.5,2.1l-3.5,15.1l23.3-8.1l-3.5,14.2l9,2.9l-7.5,21.3l10.1,1.4l1.8-18.2l9.8,5.3l4.1,15.1l-12.6,2l-0.8,14.9
            l-19.8,23.7l8.1,35.6l31.5,14.4l57.3-0.6l24.5,24l14-1.9l25.6,19l-1.4,21.8l24.3,22.9l71.2,22.4l31.9,32.4l19-0.4L1227.7,751.5"/>
          <path id="path80" class="st2" d="M1085.3,1752.4l-0.4,14.6l26.8,52.2l4.4,30.7l-55.5,47.4l-5.4,17l8,21.2l-40.2,20.3l19.7,33.1
            l4.7,35.1l-10.4,13.8l4.9,22.3l-6.5,32.3l-8.9,8.1l-35.1-8.3l-45.7-33.4l-24-1.6l-14.9,11.2l-39.6,7.6l-48.2-12.2l-14.7-25.8
            l-30.7-13l-0.4-19.3l-45.7-27.2l-99.4-115l-14.7-0.9l-13.4,14l-20.3,4.7l-9,0.6l-8.7-11.2l-28.9,7.7l-15,24.6l-12.8,1.5l-5.1-15.5
            l5.4-50l14.8-44.3l-1.3-42.1l-10.1-13.5l5.6-37.5l-6.1-15.6l44.7,9.1l7.7-27l18.1-1.3l7.1,14l13.1,3.9l10.7-8.6l43.3,4l22.3-12.9
            l8.5-23.6l20.8-19.6l1.8-40.5l20.5-15.4l6.7-29.2l21.8-9.6l14.5-17.7l18.4,10l8-6.4l-10.2-1.8L764,1481l-13.4-3.6l-4.4-34.9
            l-28.6-30.7l0.5-21l-37.4-32.1l-2.8-27.7l-48.9-25.6l-14.3-17.6l-17.8-57l5.3-19.2l55-7.8l19.5,6.1l31.9-11.2l40.1-34.5l39.7-10.4
            l35,17.6l21.4-0.7l1.1,8.5l13-5.5l39.3,10.6l40.5,20.8l10.6,20.3l10.2-6.3l19.1,17.9l16.1,2.2l24.1-31.1l12.6-4.7l25.8,1.3l7.2,8.3
            l23.8-48l14.3-6.6l-7.2-34.3l42-9.3l10.7,10l21.5-5.3l19.9-29.8l89.6-84.5l77.7,22.5l68.6-44.5l50,3.8l7.1,6.4l5.2-14.3l12.2,3.1
            l3.2,7.6l-35.4,71l-52.5,23l-1.4,53.6l-18.2,39.5l4.2,48.3l32.1,108.9l27.2,20.4l35.4-1.3l19.4,12.6l26.3,21.9l42.6,59.4l-13.9,41.3
            l-33.9,22.4l-74.4,22.4l-43.3-4l-38.1,69.3l-23.2,69.3l-18.9,18.8l0,13.9l-9.4,3.4l1.4,6.8l-31.2,11.2l-2.3,10.9l-32.8-4.7
            l-3.9-41.7l-7.2-2.2l-44.3,18.6l-16.3,22.9l-27,7.9l-13.4,14.8L1085.3,1752.4"/>
          <path id="path82"  data-info="<div>Lokasi : Tebo</div>" fill="#D3D3D3"  class="st2" d="M1261.1,1971l-24.2-23.2l-19,7.5l-12.8,21.2l-10.7,48l-108.5,38.2l-34.8,41.1l-18.9-5l9.9-38.8
            l-4.9-22.3l10.4-13.8l-4.7-35.1l-19.7-33.1l40.2-20.3l-8-21.2l5.4-17l55.5-47.4l-4.4-30.7l-26.8-52.2l0.4-14.6l65.5-3.8l13.4-14.8
            l27-7.9l16.3-22.9l44.3-18.6l7.2,2.2l3.9,41.7l32.8,4.7l2.3-10.9l31.2-11.2l-1.4-6.8l9.4-3.4l0-13.9l18.9-18.8l23.2-69.3l38.1-69.3
            l43.3,4l74.4-22.4l33.9-22.4l13.9-41.3l-42.6-59.4l-26.3-21.9l-19.4-12.6l-35.4,1.3l-27.2-20.4l-29.2-93.6l21.7-12.9l52.9,20.9
            l18.3-7l26.5,8.8l37.5-6.6l52.7,13.7l59.5,35l20.9-10.4l17.9,5.1l26.9-7l19.4,7.8l18-6l6,20.1l-9.2,23.5l46.9,16.9l46.5-4.4
            l9.6-22.8l32,0.4l33.2-39.1l22.5,6.3l54.9-14.5l67.7,18.7l34.2-2.5l36.9-13.9l-1.8,9.9l-32.4,22.1l-14.4,30.5l28,32.9l55.9,26.7
            l17.4,22l-3.7,41.9l-46.4,26.9l-16.5,28.6l-1.9,69.6l15.4,7.8l12.4,22.7l5.2,40.2l-41.8,15.2l-28.2,26.8l-19.4-3.6l-11.8-12.6
            l-21.3-1.2l-5.2-12l-16.8,1l-13.8-20.3l-21.7,8.7l1.6,18.2l-41.3,1.3l-115.8-35.6l-18.6,26.9l-17.4,5.6l-1.4,19.5l8.7,28l24.4,0.4
            l2.6,24l-49.7,49.7l-30.4,42.9l0.9,31.4l-36.4,16.7l-21.3,28.6l-12.6-24.6l-21.2,34.1l-8-3.9l-30.3,25.5l-26.1,1.4l-24.1,49.7
            l-21.6,18.4l-76.1-2.5l-29,28.8l-18.5-1.2l-64.9-14.6l-16.9-13.5l-1.1-27.5l-37.7-0.5l-20.9,7.6L1261.1,1971"/>
          <path id="path84" class="st2" d="M1683.3,592.7l25.9,8.2l29,47.1l3.5,25.5l14.1,23.3l3.9,39.9l25.3,48.6l-1.7,46.2l-20.8,40.8l3,39
            l-23.4,54.8l-40,58.7l-10.3-4.7l-5.2,17.1l-20.1,0.2l7.6,13.7l-18,4.8l-9,22l0,28.9l-24.7,41.8l-64.8,60.4l-34.1,4.6l-37.9-22.3
            l-28.6-6.3l-4.1-23.6l-8.7-2.2l-16.8,69.2l-24.6,12.1l-7.1-63.6l18.2-39.5l1.4-53.6l52.5-23l35.7-75.4l-15.3-6.7l-5.7,14.6l-7.1-6.4
            l-50-3.8l-68.6,44.5l-77.7-22.5l-89.6,84.5l12.2-29.1l-5.5-11.1l29.9-56.2l25.4-81.6l37.7-66.7l-23.8-33.1l3-11.5l-19.2-28.3
            l-14.6-1.7l-12-26.5l-17.4,1.6l-34.1-33.8l-71.2-22.4l-24.3-22.9l2.4-20.7l-25.9-19.6l-14.8,1.4l-25-24.3l-56.8,0.8L923.9,590
            l-8.1-35.6l19.8-23.7l0.8-14.9l12.6-2l-4.1-15.1l-9.8-5.3l-1.8,18.2l-10.1-1.4l7.5-21.3l-9-2.9l4.8-13.3l-27.1,6.5l7.8-11.6
            l-6.1-4.4l-18.3-0.6l-10.3,10.1L850,454.6l1.3,14.1l-8.2,0.1L852,435l-14.2-8.1l-11.1,17.4l-15.6-12.4l-6.3,12.4l-8.5-1.6L784,419.9
            l-2.7-91.8l10.4-13.5l22.1,4.3l3.5-19.1l37.9-6.9l10.8-13.5l1.5-35.1l19.3-0.1l9.7,9.6l6.9-14.4l11.4-0.3l31.5-26.9l13,3.9l14.5-7.6
            l35,21.6l17.4-8.2l57.7,25.7l16.7-18.7l22.7-1.8l27,25.8l0.5,18.6l12.3,2.7l26.6,1.4l23.3-21.3l46.8-1.7l10.8,8l14.4,33.2l18.7,0.1
            l37.8,30l-9.5,26l2.2,17l19-4.5l8.2,11.2l18.5-8l10.8,7.6l8-12.5l23.3,29.4l-2.8,23.5l7.8,4.5l31.9,6.9l16-5.2l47.5,21.1l21.4-10
            l47.8,8.1l54.3,91.4l1,23.5l10,17.8L1683.3,592.7"/>         
          <path id="path86" data-info="<div>Lokasi : Kota Jambi</div>" fill="#D3D3D3"  class="st3" d="M2577.2,975.8l-49-1.4l-11.1-45.2l27.8-38.7l66.5-10.3l6,5.7l-2,61l20.4,23.8l-18,8.6l-15.6-7.2 L2577.2,975.8"/>
          <path id="path88" class="st4" d="M454.2,1512.1l-43-21.9l-5.4-21.9l46.7-17.3l31.5,6.2l3.9,45.1L454.2,1512.1"/>
          <path id="path90" class="st2" d="M347.8,1682.6l-103.6-72.3l-10.9-95.7l42.6,3.3l32.6-18.3l44.3-11.9l27.4-48.7l10-4.9l-3.6-13.3
            l17.7-8.8l-8.1-21.3l4.7-12.6l-13-18.1l-11.9,6.8l-133.5-9.7l-41.9,42.8l-34.9-56.9l-0.8-28.5l-63.4-55.3l-1-23.5l21.8-8.1l15-33.3
            l-50.1-97.3l4.3-79.6l36,26.3l21.6-33l8.8,36.3l12.9,9.9l13.3-4.9l6.2,5.2l35.5-28.1l41.9,5.1l21.1-4l6.3-10.5l26.6,7.1l18-9.1
            l16.3,5l14-17.6l18.8,7.6l8.7,21.5l49.5,59.2l149.7,137.5l17.8,57l14.3,17.6l48.9,25.6l2.8,27.7l37.4,32.1l-0.5,21l28.6,30.7
            l4.4,34.9l13.4,3.6l22.7,32.3l10.2,1.8l-8,6.4l-18.4-10l-14.5,17.7l-21.8,9.6l-6.7,29.2l-20.5,15.4l-1.8,40.5l-20.8,19.6l-8.5,23.6
            l-22.3,12.9l-43.3-4l-10.7,8.6l-13.1-3.9l-7.1-14l-18.1,1.3l-7.7,27l-44.7-9.1l6.1,15.6l-5.6,37.5l10.4,15.6l-3.1,29.5L347.8,1682.6
            M416.4,1469.1l-12.3,1.3l2.7,15.2l36.5,26l44.6-9.3l-3.4-44.6l-9.6-5.3l-47.7,5.5L416.4,1469.1 M347.5,1038l18.8,11.6l21.9-12
            l-21.9,12L347.5,1038"/>
          <path id="path92" class="st2" d="M397.8,1394.4l6.4,17.4l-17.7,8.8l3.6,13.3l-10,4.9l-30.3,51l-41.4,9.6l-32.6,18.3l-42.6-3.3
            l-9.8-79.4l-22.9-35.4l41.5-42.6l134,9.5l11.9-6.8l13,18.1L397.8,1394.4"/>
          <path id="path94" class="st2" d="M2861.5,1101.3l-36.8,35.7l-113.6,0.7l-41.7,56l-9.3-6.2l-34.2,2.8l-3.2-15l-23.4,8.1l-9.7-6.7
            l-37.8,1.4l-24,34.4l-25.9,6.2l-24.3,26.9l-16,68.5l17.2,7.7l33.6-8.6l18.1,15l-12.5,16.3l-3.8,35.3l-14.4,2.8l-6.8,15.2l-11.5,5.1
            l-7.5,19.4l4.2,14.8l-13.1,6.7l13.8,21.7l28.1-1.8l15.7,9.1l-36.5,28.1l-25.2,9.1l-36.7-2l-49.1-113.4l36.8-61.2l7.6-45.1
            l-6.8-122.5l8.3-77.7l-13.6-27l-18.8-15.3l-13.6-71.5l11.6-18.5l-11.3-6.9l6.3-10.3l28.7-6.3l6.5-11.4l-6.5-14.8l7.7-16.8l2.8-56.4
            l-37.3-1l-5.9-20.6l-33.3,2.8l-30.3-39.4l-14.4-70.1l-19.8-3.5l-14.7-16.6l-46.2,4.1l-7.9,29.8l16.5,16.1l-8.5,7l-48.9-44.3
            l-40.7,22.7l-1.2,8.8l-8.5-13.6l-13.6-2.1l-1.3-8.7l28.8-26.3l18.6,0.2l21.4-18.2l51.3-13.7l10.7-46.4l114.8-0.7l53.5,53.2
            l79.2,29.2l31.6,24.7l33.8,43l31.2,9.4l110-84.1l355-55.3l24.2,29l55.3,33.5l44.1,39.5l37.3,84.5l119.9,168l-2.2,15.8l-15.7,15.9
            l9,20.8l-1.4,20.5l-9.3,8.2l6.4,20.2l-10.3,16.2l-39.2,14.7l-8.2-20.1l-44.8-10.1l-166.1,47.1l-8.6,14l-17.7,3.3l-75.8,1.6
            l-36.1-41.5L2861.5,1101.3 M2555.3,889.7l-25.9,15.5l-12.3,24.1l11.1,45.2l71.9-2.5l17.8,7.4l17.2-7.2l-20.2-27.3l1.6-60.9l-20.8-4
            L2555.3,889.7"/>

          <path id="path96"  data-info="<div>Lokasi : Tanjung Jabung Barat</div>" fill="#D3D3D3"  class="st2" d="M2533.9,207.8l-12.2,41.5l7.4,9.7l-9.1,1.2l-2.6,25.1l-12.1,18.5l4.1,6.7l-22.9,23.9l-31.2,4.5
            l-16.5,11.6l-17.8,88.3l-29.1,58.5l-30.4,105.8l-79.6-7.1l-46.2,3.9l-10.7,46.4l-51.3,13.7l-21.4,18.2l-24.8,3.2l-39,45.5l-10.3,2.1
            l-0.6,10.8l-18.2,4.6l-17.6-16.1l-26.5-8.1l7.2,23.2L2003,746l-6.7,42.4l-13.9-3.4l-1.5-13.8l-13.8,4.4l-27.2-15.8l-21.6,2.4
            l-44.4-48.3l-28.6,0.9l-20.5-9.5l-20.5,2.8l-9.6,7.2l2.7,10.1l-21,7.2l-4.2,26.1l-16.5-35.2v-26.6l-33.6-78.6l-13-17.3l-25.9-8.2
            l-26-20.7l-10-17.8l-1-23.5l-40.2-58.9l4.8-16.8l19.4-9.3l3.9-13.1l23.3-8.6l8.3-18.5l24.8,9.2l4.9-27.5l11.8-16.2l12.7-12.7
            l20.5,4.5l-3.5-20.8l32.3-44.6l73.6-50.9l66.1-62.8l28-33.8l22-45.6l30.1-28.4l85.9-0.1l234.5,45.7l9.6,15.7l52.9-31.1l31.5-2.8
            l2.2-33.4l14.8-2.4l23.1,29.3l-3.9,29.1l8.6,9.4l33.6-1.3l26.6,9.2l11.1,31.6l-10.2,14.8L2533.9,207.8"/>
          
            <path id="path98" class="st5" d="M3461.5,1012.6l-7.1,1l1-11.3L3461.5,1012.6"/>
          <path id="path100" class="st5" d="M3207.8,363.5l7.9,4.8l-46.2,25.2l3-26.2L3207.8,363.5"/>
          <path id="path102" class="st5" d="M3166.8,363.8l6.5,14.5l-8.6,10.3l-12.3-19.3L3166.8,363.8"/>
          
          <path id="path104" class="st2" d="M2554.8,207.9l33.8,37.6l42.1,23.6l5.1,15.4l11.5-4.9l30.8,5.7l30.2,26.3l-0.8,13.3l55.6,1.3
            l6.2,19.8l22.4,1.6l81.7-33.9l43.3,12.8l68.3,5.5l85.2,33.1l20.8,10.5l-3,6l56.3,2.8l23.9,18.9l59-29.8l66.3,1.8l16.5-6.8l10.1-16.1
            l8.7,3.5l24.3,42.1l13.9,60.2l3.4,33.8l-15.4,31.2l-1.6,26l64.4,117.3l-4.9,53.5l5.7,32.6l-16.7,52.9l-2.1,34.9l28.5,110.2l26.4,50
            l-7.3,0.7l-5.9-12.8l-10.7,4.5l5.6-7l-12.8-2.4l1.6-9.4l-6,5.5l-8.3-11.5l-18.1,4.2l-1.7-11.7l-66,13.1l-5.8-5.2l-114.1-162.8
            l-37.3-84.5l-44.1-39.5l-55.3-33.5l-24.2-29l-355,55.3l-85.9,69.8l-29.5,14.5l-35.7-17.7l-23.9-35l-31.6-24.7l-86.3-35.3l-35.4-43.1
            l2.3-21.5l14.7-27.6l13.4-56.7l29.1-58.5l12.2-73.8l14.1-21l39.3-9.6l22.9-23.9l-4.1-6.7l12.1-18.5l2.6-25.1l9.1-1.2l-7.4-9.7
            l8.5-36.5l5.9-6.6L2554.8,207.9"/>
          <g id="g106" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" class="st6">
          </g>
          <g id="g108" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" class="st6">
          </g>
          <g id="g110" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" class="st6">
          </g>
          <g id="g112" transform="translate(2.906004,2.906004)" class="st6">
          </g>
          <g id="g114" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" vector-effect="non-scaling-stroke" class="st6">
          </g>
          <g id="g116" transform="matrix(11.81,0,0,11.81,2.906004,2.906004)" vector-effect="non-scaling-stroke" class="st6">
          </g>
          <g id="g118" transform="translate(2.906004,2.906004)" vector-effect="non-scaling-stroke" class="st6">
          </g>
          </svg>
        </div> 
        <!-- MAP BOX END -->
        </div>
        <div class="col-md-7">
        <div id="chart">
</div>

        </div>
      </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


    

<script>
$(document).ready(function() {
    $('#path94').on('click', function() {
        var info = $(this).data('info');
        alert(info);
    });
});
  var options = {
          series: [
          {
            name: 'Actual',
            data: [
              {
                x: '2021',
                y: 12,
                goals: [
                  {
                    name: 'Expected',
                    value: 14,
                    strokeWidth: 2,
                    strokeDashArray: 2,
                    strokeColor: '#00E396'
                  }
                ]
              },
              {
                x: '2022',
                y: 44,
                goals: [
                  {
                    name: 'Expected',
                    value: 54,
                    strokeWidth: 5,
                    strokeHeight: 10,
                    strokeColor: '#00E396'
                  }
                ]
              },
       
     
              {
                x: '2024',
                y: 67,
                goals: [
                  {
                    name: 'Expected',
                    value: 80,
                    strokeWidth: 5,
                    strokeHeight: 10,
                    strokeColor: '#00E396'
                  }
                ]
              },      {
                x: '2024',
                y: 67,
                goals: [
                  {
                    name: 'Expected',
                    value: 70,
                    strokeWidth: 5,
                    strokeHeight: 10,
                    strokeColor: '#00E396'
                  }
                ]
              }
            ]
          }
        ],
          chart: {
          height: 350,
          type: 'bar'
        },
        plotOptions: {
          bar: {
            horizontal: true,
          }
        },
        
        colors: ['#ce2026'],
        dataLabels: {
          formatter: function(val, opt) {
            const goals =
              opt.w.config.series[opt.seriesIndex].data[opt.dataPointIndex]
                .goals
        
            if (goals && goals.length) {
              return `${val} / ${goals[0].value}`
            }
            return val
          }
        },
        legend: {
          show: true,
          showForSingleSeries: true,
          customLegendItems: ['Actual', 'Expected'],
          markers: {
            fillColors: ['#00E396', '#775DD0']
          }
        }
        };
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
</script>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"> Contibution 100%</h3>
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
            <div class="form-group">    
            <label for="inputEmail3" class="col-sm-12 control-label">Priode</label>
                  <div class="col-sm-12">
                    <input type="text"   class="form-control"   value="2022/01/01 - 2022/01/30"  id='periode' readonly>
                          <!-- <input type="text"  class="form-control check_button" id='periode'  required> -->
                    <input type="hidden" class="form-control"   value="2022-01-01" id="priode_awal"  readonly>
                    <input type="hidden" class="form-control"   value="2022-01-31" id="priode_akhir" readonly>
                  </div>

                          <script>
                            $(function() {
                              $('#periode').daterangepicker({
                                autoUpdateInput: false,
                                locale: {
                                  format: 'DD/MM/YYYY'
                                }
                              }, function(start, end, label) {
                                $('#priode_awal').val(start.format('YYYY-MM-DD'));
                                $('#priode_akhir').val(end.format('YYYY-MM-DD'));
                              }).on('apply.daterangepicker', function(ev, picker) {
                                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                              }).on('cancel.daterangepicker', function(ev, picker) {
                                $(this).val('');
                                $('#priode_awal').val('');
                                $('#priode_akhir').val('');
                              });
                            });
                          </script>


                  <label for="inputEmail3" class="col-sm-12 control-label">Dealer</label>
                  <div class="col-sm-12">
                    <select class="form-control select2" required id="id_dealer" name="id_dealer" >
                      <option value="">- choose -</option>   
                      <?php 
                      foreach($dealer as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->nama_dealer</option>;
                        ";
                      }     ?>  
                    </select>
                  </div>

                  <label for="inputEmail3" class="col-sm-12 control-label">Kabupaten</label>
                  <div class="col-sm-12">
                  <select class="form-control select2" required id="kabupaten" name="kabupaten" >
                      <option value="">- choose -</option>    
                      <?php 
                      foreach($kabupaten as $val) {
                        echo "
                        <option value='$val->id_kabupaten'>$val->kabupaten</option>;
                        ";
                      }     ?>            
                    </select>
                  </div>

         
                </div> 

                <label for="inputEmail3" class="col-sm-12 control-label"><br></label>
                  <div class="col-sm-12">
                  <button type="button"class="btn btn-info btn-flat btn-generate-set"><i class="fa fa-gear"></i> Generate</button>
                    <a href="/h1/rekap_bastd/add" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i></a>    
                  </div>
            </div>
            <div class="col-md-6">

            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td><b>Ring</b></td>
                  <td><b>Percent %</b></td>
                  <td><b>Status</b></td>
                </tr>
              </thead>    
              <tbody class="body-contribution">
              </tbody>         
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

<script>
$('.btn-generate-set').click(function() {
  $('.body-contribution').empty();
  var id_dealer       = $("#id_dealer").val();
  var priode_awal     = $("#priode_awal").val();
  var priode_akhir    = $("#priode_akhir").val();

      $.ajax({
        url: "<?php echo site_url('h1/ttm/generate'); ?>",
          type: 'POST',
          data: { 
              id_dealer    : id_dealer,
              priode_awal  : priode_awal,
              priode_akhir : priode_akhir,
          },
          success: function(response) {
            var urut = 0;
            var sum_all_percent = []; 
                    $.each(response, function(index, item) {
                     var number = item.jumlah;
                     var rounded = number.toFixed(2);
                     sum_all_percent.push(rounded);
                      urut++
                        var tableRow = '<tr>' +
                            '<td>Ring ' + urut + '</td>' +
                            '<td>' + rounded + '%</td>' +
                            '<td>' + item.status_jumlah + '</td>' +
                            '</tr>';
                        $('.body-contribution').append(tableRow);
                    });

                    var tableRow = '<tr>' +
                        '<td><b>Total</b></td>' +
                        '<td><b>100%</b></td>' +
                        '<td></td>' +
                        '</tr>';
                    $('.body-contribution').append(tableRow);
          },
          error: function(xhr, status, error) {
          }
      });
    });


      $("path, circle").hover(function(e) {
        $('#info-box').css('display','block');
        $('#info-box').html($(this).data('info'));
      });

      $("path, circle").mouseleave(function(e) {
        $('#info-box').css('display','none');
      });

      $(document).mousemove(function(e) {
        $('#info-box').css('top',e.pageY-$('#info-box').height()-30);
        $('#info-box').css('left',e.pageX-($('#info-box').width())/2);
      }).mouseover();

      var ios = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
      if(ios) {
        $('a').on('click touchend', function() { 
          var link = $(this).attr('href');   
          window.open(link,'_blank');
          return false;
        });
      }
    </script>
    */?>

  
    <?php
    }  else if($set=="master"){
      ?>
  
  <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">   

        <a href="h1/ttm/">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
          </a>  

        <a href="h1/ttm/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add</button>
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
              <th>Kode Dealer</th>
              <th>Nama Dealer</th>
              <th>Lokasi</th>
              <th>Status</th>
              <th>Action</th>              
            </tr>
          </thead>
          <tbody> 
            <?
              $key = 1;
              foreach($dealer as $row) {?>
              <tr>
              <td><?=$key ++?></td>
              <td><?=$row->kode_dealer_md ?></td>
              <td><?=$row->nama_dealer ?></td>
              <td><?=$row->kecamatan ?></td>
              <td><?=$row->status ?></td>
              <td>
                <a href="h1/ttm/show?id=<?=$row->id_dealer?>" class="btn btn-sm bg-success btn-flat" title="Lihat Data"><i class="fa fa-eye"></i></button>
                <a href="h1/ttm/edit?id=<?=$row->id_dealer?>" class="btn btn-sm bg-primary btn-flat" ><i class="fa fa-edit"></i></button>
              </td>
              </tr>
            
             <? }
            ?>
          </tbody>
        </table>
      </div>
    </div>

      <?php 
      }
 else if($set=="show"){
      ?>
  
  <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">     
        <a href="h1/ttm/master">
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
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>Kodya/Kabupaten</th>
              <th>Kecamatan</th>
              <th>Wilayah Kerja</th>
            </tr>
          </thead>
          <tbody> 
            <?
              $key = 1;
              foreach($ring as $row) {?>
              <tr>
                <td><?=$key ++?></td>
              <td><?=$row->kabupaten ?></td>
              <td> <?=$row->kecamatan ?></td>
              <td><?php if($row->id_ring == 0) { echo 'Lokasi'; } else if ($row->id_ring == 9 ) { echo 'Other'; } else  { echo 'Ring ' . $row->id_ring;  } ?></td>
              </tr>
             <? }
            ?>
          </tbody>
        </table>
      </div>
    </div>

      <?php 
      }
      else if($set=="edit"){
        ?>
    
    <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">     
          <a href="h1/ttm/master">
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
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th>Kodya/Kabupaten</th>
                <th>Kecamatan</th>
                <th>Wilayah Kerja</th>
              </tr>
            </thead>
            <tbody> 
              <?
                $key = 1;
                foreach($ring as $row) {?>
                <tr>
                  <td><?=$key ++?></td>
                <td><?=$row->kabupaten ?></td>
                <td> <?=$row->kecamatan ?></td>
                <td><?php if($row->id_ring == 0) { echo 'Lokassi'; } else if ($row->id_ring == 9 ) { echo 'other'; } else  { echo 'Ring ' . $row->id_ring;  } ?></td>
                </tr>
               <? }
              ?>
            </tbody>
          </table>
        </div>
      </div>
  
        <?php 
        }
      else if($set=="report"){
        ?>
  
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/ttm/">
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
            <form class="form-horizontal"  action="/h1/ttm/make_report" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Wilayah</label>
                  <div class="col-sm-4">
                    <select class="form-control select2"  name="wilayah" id="wilayah" >
                      <option value="">- choose -</option>
                      <option value="all">All Wilayah</option>
                      <?php
                            foreach ($dt_wilayah->result() as $isi) {
                              echo "<option value='$isi->id_kabupaten'>$isi->kabupaten</option>";
                            }
                            ?>
                    </select>
                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2"  id="dealer" name="dealer">
                      <option value="">- choose -</option>        
                      <option value="all">All Dealer</option>        
                      <?php
                            foreach ($dealer->result() as $isi) {
                              echo "<option value='$isi->id_dealer'>$isi->nama_dealer</option>";
                            }
                            ?>              
                    </select>
                  </div>
                </div>          

                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Priode</label>
                  <div class="col-sm-4">
                  <input type="text"  class="form-control check_button" id='periode'  required>
                          <input type="hidden" class="form-control" id='start_periode' name='start_periode'>
                          <input type="hidden" class="form-control" id='end_periode' name='end_periode'>
                          <script>
                            $(function() {
                              $('#periode').daterangepicker({
                                autoUpdateInput: false,
                                locale: {
                                  format: 'DD/MM/YYYY'
                                }
                              }, function(start, end, label) {
                                $('#start_periode').val(start.format('YYYY-MM-DD'));
                                $('#end_periode').val(end.format('YYYY-MM-DD'));
                              }).on('apply.daterangepicker', function(ev, picker) {
                                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                              }).on('cancel.daterangepicker', function(ev, picker) {
                                $(this).val('');
                                $('#start_periode').val('');
                                $('#end_periode').val('');
                              });
                            });
                          </script>
                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Ring</label>
                  <div class="col-sm-4">
                    <select class="form-control select2"  id="ring" name="ring" >
                      <option value="">- choose -</option>                      
                      <option value="all">All Ring</option>                      
                      <option value="1">Ring 1</option>                      
                      <option value="2">Ring 2-</option>                      
                      <option value="3">Ring 3</option>                      
                      <option value="9">Other</option>                      
                    </select>
                  </div>
                </div>          
                
                <div class="form-group">                

                  <label for="inputEmail3" class="col-sm-2 control-label">Report</label>
                  <div class="col-sm-4">
                    <select class="form-control select2"  id="report" name="report" >
                      <option value="">- choose -</option>                      
                      <option value="konsumen_ro">Konsumen RO by TTM</option>                      
                    </select>
                  </div>
                </div>     
                
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Show  </button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Refresh All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div>
        <?php 
      }
    ?>
  </section>
</div>

