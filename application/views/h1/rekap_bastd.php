

<style>
.input-checklist {
        border: none;
        background: transparent;
}

table thead,
table tfoot {
  position: sticky;
}
table thead {
  inset-block-start: 0; 
}

table tfoot {
  inset-block-end: 0; 
}


tfoot {
  display: table-row-group; /* Ensures it behaves like a table row */
}

table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; }
th     { background:#eee; }


#loading-spinner {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
}

.gear {
    width: 50px;
    height: 50px;
    position: relative;
    animation: spin 1s infinite linear;
}

.gear div {
    position: absolute;
    width: 20px;
    height: 20px;
    background-color: #333;
    border-radius: 50%;
}

.gear1 {
    top: 0;
    left: 15px;
    animation: spin-reverse 1s infinite linear;
}

.gear2 {
    top: 15px;
    left: 35px;
    animation: spin 1s infinite linear;
}

.gear3 {
    top: 30px;
    left: 15px;
    animation: spin-reverse 1s infinite linear;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes spin-reverse {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(-360deg); }
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
if($set=="view"){?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">     
        <a href="h1/rekap_bastd/add">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-plus"></i> Add</button>
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
        <table id="table-serverside" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No Rekap</th>
              <th>No Surat</th>  
              <th>Tgl Rekap</th>            
              <th>Jenis Rekap</th>            
              <th>Dealer</th>   
              <th>Periode BASTD</th>
              <th>Tgl Jatuh Tempo</th>
              <th>Total Unit</th>              
              <th>Total BBN</th>              
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>  
          </tbody>
        </table>
      </div>
    </div>

   <script>
         $(document).ready(function() {
        $('#table-serverside').DataTable({
            "scrollX": false,
            "processing": true, 
            "serverSide": true, 
            "order": [],

            "ajax": {
                "url": "<?php echo site_url('h1/rekap_bastd/fetch_bastd')?>",
                "type": "POST"
            },
            "columnDefs": [
            {
                "targets": [ 0,9 ], 
                "orderable": false,
            },
            ],
        });
    });
   </script>

  
    <?php
    }  else if($set=="add"){
      $readonly = "";
      if ($mode =='edit'){
        $readonly = '';
      }

      ?>
  
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
          <a href="h1/rekap_bastd">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-angle-left"></i> Back</button>
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
              <form class="form-horizontal" action="h1/penerimaan_unit/save" method="post" enctype="multipart/form-data">
                <div class="box-body">              
       
                  <div class="form-group">                
                    <label class="col-sm-2 ">Nama Dealer</label>
                    <div class="col-sm-4">
                      <select class="form-control select2 set-onchange-id_dealer" value="<?=$row->id_dealer?>"  name="id_dealer"  id="id_dealer" placeholder="Nama Dealer">
                        <option value="">- choose -</option>
                        <?php                       
                        foreach($dt_dealer->result() as $val) {
                          echo "<option value='$val->id_dealer'>$val->nama_dealer ($val->kode_dealer_md)</option>;";
                        }
                        ?>
                      </select>                 
                    </div>
                  </div>

                  <div class="form-group">     

                  <label class="col-sm-2 ">Group Dealer</label>
                    <div class="col-sm-4">
                    <select class="form-control select2 set-onchange-group_dealer" value="<?=$row->group_dealer?>"   name="group_dealer"  id="group_dealer"  placeholder="Group Dealer" >
                        <option value="">- choose -</option>
                        <?php        
                        foreach($dt_group->result() as $val) {
                          echo "<option value='$val->id_group_dealer'>$val->group_dealer</option>;";
                        }
                        ?>
                      </select>                                     
                    </div>
                    </div>

                  <div class="form-group">    
                    <label class="col-sm-2 ">QQ Kuitansi</label>
                    <div class="col-sm-4">
                        <input type="text" autocomplete="off"  name="kwitansi" value="<?=$row->cc_kwitansi?>" id="kwitansi" class="form-control" readonly>
                    </div>
                  </div>

                  <div class="form-group"> 
                    <label class="col-sm-2 ">Periode *</label>
                      <div class="col-sm-4">
                      <input type="text"  class="form-control" id='periode' required value="<?=$row->tgl_awal."-".$row->tgl_akhir?>">
                          <input type="hidden" class="form-control" id='start_periode' name='start_periode'>
                          <input type="hidden" class="form-control" id='end_periode' name='end_periode'>
                    </div>
                   </div>

                  <div class="form-group">                
                    <label class="col-sm-2 ">Tanggal Jatuh Tempo *</label>
                    <div class="col-sm-4">
                    <input type="date" autocomplete="off" required placeholder="Tanggal Jatuh Tempo" name="tgl_jatuh_tempo" value="<?=date('Y-m-d')?>" id="tgl_jatuh_tempo" class="form-control">
                    </div>
                  </div>  

                  <div class="form-group">                
                    <label class="col-sm-2 ">No Surat *</label>
                    <div class="col-sm-4">
                    <input type="text" autocomplete="off"  name="no_surat" id="no_surat" class="form-control" value=''>
                    </div>
                    <button type="button"class="btn btn-info btn-flat btn-generate-set"><i class="fa fa-gear"></i> Generate</button>
                    <a href="/h1/rekap_bastd/add" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i></a>      
                  </div>  
                      
                </div>
         
              </form>
            </div>
          </div>

          <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" action="h1/penerimaan_unit/save" method="post" enctype="multipart/form-data">
                <div class="box-body">   
                <div class="tableFixHead">
                    <table class="table table-bordered table-hover">
                  <thead>
                    <tr height ="30px" >
                      <th width="5%">No</th>
                      <th>No BASTD</th>            
                      <th>Tlg BASTD</th>            
                      <th>Tlg Approve</th>            
                      <th>Nama Dealer <i class="fa fa-info-circle"  title="(di urutkan sesuai dgn nama cabang, baru di urutkan sesuai no bastd)"></i></th>
                      <th>Total Unit</th>            
                      <th>Total BBN</th>            
                      <th><input type="checkbox" name="checkbox-all" id="check-box-all"></th>            
                    </tr>
                  </thead>
                  <tbody class="show-generate">
                  </tbody>

                  <tfoot class="add-manual">
                    <tr class="set-manual">
                          <th width="5%">1</th>
                          <th><input type="text" name="biaya_lainya[]"  class="temp_biaya_lainya_1"  ></th>            
                          <th colspan="4"></th>            
                          <th>
                          <input type="text"  name="harga_lainya[]" class="temp_harga_1"  oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                          <input type="hidden"  class="temp_hargas_1" id="dengan_rupiah">
                        </th>            
                          <th class="button-set-action-1" ><button class="btn btn-sm bg-primary btn-flat appendButton appenBtn-1" onclick="appendButton(1)"  ><i class="fa fa-plus"></i></button></th>            
                    </tr>
                  <tfoot>
                  <tr>
                      <th colspan="4"></th>
           
                      <th>Total</th> 
                      <th>  <input type="text" name="" class="input-checklist checklist-total-unit-bastd" readonly>  </th>             
                      <th>
                      <input type="hidden" name="total-manual"class="manual-total" readonly>  
                      <input type="hidden" name="total-bastd" class="checklist-total-bastd" readonly> 
                      <input type="text" name="full-set" class="input-checklist full-set" readonly> 
                    </th>            
                      <th></th>            
                  </tr> 
                 </tfoot>
                </table>  
                 </div>
                </div><!-- /.box-body -->
              </form>
            </div>
          </div>
          <div class="col-md-12 text-center">
            <button type="button" class="btn btn-info btn-flat" id="saveButton">   <i class="fa fa-save"></i> Save</button>
            <button type="reset"  class="btn btn-default btn-flat"><i class="fa fa-close"> Cancel</i></button>    
            
          </div>   
        </div>
      </div><!-- /.box -->

      <div id="loading-spinner" >
        <div class="gear">
          <div class="gear1"></div>
          <div class="gear2"></div>
          <div class="gear3"></div>
         </div>
      </div>

      <link rel="stylesheet" href="<?php echo base_url() ?>assets/sweetalert2/sweetalert2.min.css">
      <script type="text/javascript" src="<?php echo base_url() ?>assets/sweetalert2/sweetalert2.min.js"></script>

  <script>
    
    // var dengan_rupiah = document.getElementById('dengan-rupiah');
    // dengan_rupiah.addEventListener('keyup', function(e)
    // {
    //     dengan_rupiah.value = formatRupiah(this.value);
    // });

    
    // function formatRupiah(angka, prefix)
    // {
    //     var number_string = angka.replace(/[^,\d]/g, '').toString(),
    //         split     = number_string.split(','),
    //         sisa      = split[0].length % 3,
    //         rupiah    = split[0].substr(0, sisa),
    //         ribuan    = split[0].substr(sisa).match(/\d{3}/gi);
            
    //     if (ribuan) {
    //         separator = sisa ? '.' : '';
    //         rupiah += separator + ribuan.join('.');
    //     }
    //     rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    //     return prefix == undefined ? rupiah : (rupiah ?  + rupiah : '');
    // }


    function updateQQKuitansi() {
        var id_dealer    = $('.set-onchange-id_dealer').val();
        var group_dealer = $('.set-onchange-group_dealer').val();
        $.ajax({
          url: "<?php echo site_url('h1/rekap_bastd/kwitansi_qq'); ?>",
            type: 'POST',
            data: {id_dealer: id_dealer, group_dealer: group_dealer},
            success: function(data) {
                $('#kwitansi').val(data); // Update QQ Kuitansi input field value
    
            }
        });
    }

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
        
      <script>
        
      $( document ).ready(function() {
        $('.set-onchange-id_dealer, .set-onchange-group_dealer').on('change', function(){
        updateQQKuitansi();
        updateTotalBiayaBbnMd();
        updateTotalInputManual();
        get_total();
        });
      });

      $('#check-box-all').change(function() {
            var isChecked = $(this).prop('checked');
            $('.checklist-checkbox').prop('checked', isChecked);
            updateCheckAllCheckbox();
            updateTotalBiayaBbnMd();
        });

        function updateCheckAllCheckbox() {
            var totalCheckboxes = $('.checklist-checkbox').length;
            var totalChecked = $('.checklist-checkbox:checked').length;
            $('#check-box-all').prop('checked', totalChecked === totalCheckboxes);
        }

        $('.checklist-checkbox').change(function() {
            updateCheckAllCheckbox();
        });
   

      function formatIDRCurrency(amount) {
          return 'Rp ' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(amount).replace(/\s/g, '').replace('Rp', '');
        }

        $('#saveButton').click(function() {
          var dataToSend       = []; 
          var dataToSendManual = []; 
          
          var id_dealer     = $("#id_dealer").val();
            var group_dealer  = $("#group_dealer").val();
            var kwitansi      = $("#kwitansi").val();
            var tgl_jatuh_tempo = $("#tgl_jatuh_tempo").val();
            var start_periode   = $("#start_periode").val();
            var end_periode     = $("#end_periode").val();
            var no_surat         = $("#no_surat").val();
        
            $('tr ').each(function() {
                if ($(this).find('input[type="checkbox"]').prop('checked')) {
                    var rowData = {};
                    rowData.no_bastd = $(this).find('input[name="no_bastd[]"]').val();
                    rowData.tgl_bastd = $(this).find('input[name="tgl_bastd[]"]').val();
                    rowData.tgl_approval = $(this).find('input[name="tgl_approval"]').val();
                    rowData.id_dealer = $(this).find('input[name="id_dealer[]"]').val();
                    rowData.total_unit = $(this).find('input[name="total_unit[]"]').val();
                    rowData.biaya_bbn_md = $(this).find('input[name="biaya_bbn_md[]"]').val();
                    dataToSend.push(rowData);
                }
            });

            $('.set-manual').each(function() {
                    var rowDataManual = {};
                    rowDataManual.biaya_lainya = $(this).find('input[name="biaya_lainya[]"]').val();
                    rowDataManual.harga_lainya = $(this).find('input[name="harga_lainya[]"]').val();
                    dataToSendManual.push(rowDataManual);
            });

                $.ajax({
                  url: "<?php echo site_url('h1/rekap_bastd/save'); ?>",
                    type: 'POST',
                    data: { 
                       data: JSON.stringify(dataToSend), 
                        data_manual: JSON.stringify(dataToSendManual), 
                        id_dealer : id_dealer,
                        group_dealer : group_dealer,
                        kwitansi : kwitansi,
                        tgl_jatuh_tempo : tgl_jatuh_tempo,
                        start_periode : start_periode,
                        end_periode : end_periode,
                        no_surat : no_surat,
                    },
                    success: function(response) {
                      Swal.fire('Sukses!', 'Data berhasil disimpan.', 'success').then(function() {
                        // location.reload();
                      });
                    },
                    error: function(xhr, status, error) {
                      Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan data.', 'error');
                    }
                });
            });

        
      function appendButton(set) {
        var biaya = $(".temp_biaya_lainya_" + set).val();
        var harga = $(".temp_harga_" + set).val();
        if (harga !== '' && biaya !== '') {
          var no = set + 1;
          var newRow = '<tr class="set-manual-'+no+'">' +
                        '<th>'+no+'</th>' +
                        '<th><input type="text" name="biaya_lainya[]" class="temp_biaya_lainya_'+no+'"></th>' +
                        '<th colspan="4"></th>' +
                        '<th><input type="text" name="harga_lainya[]" id="temp_harga'+no+'" oninput="this.value = this.value.replace(/[^0-9]/g, \'\')" class="temp_harga_'+no+'"></th>' +
                        '<th class ="button-set-action-'+no+'"><button class="btn btn-sm bg-primary btn-flat appendButton appenBtn-'+no+'"  onclick="appendButton('+no+')" ><i class="fa fa-plus"></i></button></th>' +
                        '</tr>';

            $('.button-set-action-'+set+'').append('<button class="btn btn-sm bg-maroon btn-flat" onclick="deleteButton('+no+')"><i class="fa fa-trash"></i></button>');
            $('.add-manual').append(newRow);
            $(".temp_biaya_lainya_" + set).prop('disabled', true);
            $(".temp_harga_" + set).prop('disabled', true);
            $(".appenBtn-" + set).remove();
            updateTotalInputManual();
            event.preventDefault();
        } else {
          alert('Mohon Periksa Inputan');
          event.preventDefault();
        }
      }

        function deleteButton(set) {
          $(".set-manual-" + set).remove();
          updateTotalInputManual();
          event.preventDefault();
        }
  
        $('.show-generate').on('change', 'input[name="checklist[]"]', function() {
            updateTotalBiayaBbnMd();
        });

        function get_total() {
          var bbn    = parseInt($(".checklist-total-bastd").val()) || 0;
          var manual = parseInt($(".manual-total").val()) || 0;
          var total  = bbn + manual;

          $(".checklist-total-bastd").val(bbn);
          $(".manual-total").val(manual);
          $(".full-set").val(formatIDRCurrency(total));
        }

        function updateTotalBiayaBbnMd() {
            totalBiayaBbnMd = 0;
            totalUnitMD = 0;
            $('input[name="checklist[]"]:checked').each(function() {
                var index = $(this).closest('tr').index(); 
                
                var biayaBbnMd = parseFloat($('input[name="biaya_bbn_md[]"]').eq(index).val().replace(/\./g, '').replace(',', '.')); 
                if (!isNaN(biayaBbnMd)) {
                    totalBiayaBbnMd += biayaBbnMd;
                }

                var totalUnit = parseFloat($('input[name="total_unit[]"]').eq(index).val().replace(/\./g, '').replace(',', '.')); 
                if (!isNaN(totalUnit)) {
                  totalUnitMD += totalUnit;
                }
            });
            $(".checklist-total-unit-bastd").val(totalUnitMD);
            $(".checklist-total-bastd").val(totalBiayaBbnMd);
            get_total();
        }

        function updateTotalInputManual() {
          totalHarga = 0;
          $('input[name="harga_lainya[]"]').each(function() {
              var harga = parseFloat($(this).val().replace(/\./g, '').replace(',', '.')); 
              if (!isNaN(harga)) {
                totalHarga += harga;
              }
          });

          $(".manual-total").val(totalHarga);
          get_total();
        }

        $('.btn-generate-set').click(function() {
            var id_dealer        = $("#id_dealer").val();
            var start_periode    = $("#start_periode").val();
            var end_periode      = $("#end_periode").val();
            var no_surat         = $("#no_surat").val();
            var group_dealer     = $("#group_dealer").val();
            var kuitansi         = $("#kuitansi").val();
            var tgl_jatuh_tempo  = $("#tgl_jatuh_tempo").val();

            // if (id_dealer == '' ) {
            //      Swal.fire('Erorr!', 'Dealer Belum Dipilih', 'error');
            //       event.preventDefault();
            //       return false;
            // }

            if (start_periode == '' ) {
              Swal.fire('Erorr!', 'Periode Harus Dimasukkan.', 'error');
                  event.preventDefault();
                  return false;
            }

            if (tgl_jatuh_tempo == '' ) {
              Swal.fire('Erorr!', 'Tgl Jatuh Tempo Harus Dimasukkan.', 'error');
                  event.preventDefault();
                  return false;
            }

            if (no_surat == '' ) {
              Swal.fire('Erorr!', 'No Surat Tidak Boleh Kosong.', 'error');
                  event.preventDefault();
                  return false;
            }

            $('#loading-spinner').show();

          $.ajax({
                url: "<?php echo site_url('h1/rekap_bastd/generate'); ?>",
                type: 'POST', 
                data:{
                        id_dealer : id_dealer,
                        group_dealer : group_dealer,
                        kuitansi : kuitansi,
                        tgl_jatuh_tempo : tgl_jatuh_tempo,
                        start_periode : start_periode,
                        end_periode : end_periode,
                        no_surat : no_surat,
                     },
                success: function(response) {
                  $('#loading-spinner').hide();
                  $('.show-generate').empty();
                    var urut = 0;
                    $.each(response, function(index, item) {
                      urut++
                        var tableRow = '<tr>' +
                            '<td>' + urut + '</td>' +
                            '<td> <input type="text"      name="no_bastd[]"       value="' + item.no_bastd + '"      id="" class="input-checklist"></td>' +
                            '<td> <input type="text"      name="tgl_bastd[]"      value="' + item.tgl_bastd + '"     id="" class="input-checklist"></td>' +
                            '<td> <input type="text"      name="tgl_approval"     value="' + item.tgl_approval + '"  id="" class="input-checklist"></td>' +
                            '<td> <input type="hidden"    name="id_dealer[]"      value="' + item.id_dealer +'" >' + item.nama_dealer + '</td>' +
                            '<td> <input type="text"      name="total_unit[]"     value="' + item.total_unit + '"    id="" class="input-checklist"></td>' +
                            '<td> <input type="hidden"    name="biaya_bbn_md[]"   value="' + item.total_biaya + '"   id="" class="form-control input-checklist">'+formatIDRCurrency(item.total_biaya)+'</td>' +
                            '<td> <input type="checkbox"  name="checklist[]" class="checklist-checkbox"> </td>' +
                            '</tr>';
                        $('.show-generate').append(tableRow);
                        updateTotalBiayaBbnMd();
                    });
                },
                error: function(xhr, status, error) {
                }
            });
        });
      </script>
      <?php 
      }
    ?>
  </section>
</div>

