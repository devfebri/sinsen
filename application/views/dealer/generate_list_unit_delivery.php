<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">Penjualan Unit</li>
      <li class="">Generate List Unit Dealivery</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php
    if ($set == "form") {
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'assign_supir') {
        $readonly = 'readonly';
        // $disabled = 'disabled';
        $form     = 'save_assign';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

      <script>
        Vue.use(VueNumeric.default);
        $(document).ready(function() {
          <?php if (isset($row)) { ?>

          <?php } ?>
        })
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/generate_list_unit_delivery">
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
              <form class="form-horizontal" id="form_" action="dealer/generate_list_unit_delivery/<?= $form ?>" method="post" enctype="multipart/form-data">
                <?php if (isset($row)) : ?>
                  <input type="hidden" name="id_generate" value="<?= $row->id_generate ?>">
                <?php endif ?>
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl Pengiriman</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control datepicker" id="tgl_pengiriman" name="tgl_pengiriman" autocomplete="off" value="<?= isset($row) ? $row->tgl_pengiriman : '' ?>" <?= $disabled ?> <?= $readonly ?>>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Driver</label>
                    <div class="col-sm-4">
                      <input type="hidden" required class="form-control" id="id_master_plat" name="id_master_plat" value="<?= isset($row) ? $row->id_master_plat : '' ?>" <?= $disabled ?> <?= $readonly ?>>
                      <input type="text" required class="form-control" id="driver" name="driver" autocomplete="off" value="<?= isset($row) ? $row->driver : '' ?>" <?= $disabled ?> <?= $readonly ?> placeholder='Klik untuk memilih driver' :disabled="mode!='insert'" onclick="showModalDriverDeliveryUnit()" readonly>
                    </div>
                  </div>
                  <div class="form-group">

                    <div class="col-sm-12" align='center' v-if="mode=='insert'">
                      <button type="button" id="gnrtBtn" onclick="form_.getUnit()" class="btn btn-primary btn-flat">Generate</button>
                    </div>
                  </div>
                  <button class="btn btn-block btn-primary btn-flat" disabled> DETAIL </button><br>
                  <div class="form-group">
                    <div class="col-md-12">
                      <table class="table table-bordered">
                        <thead>
                          <th>No</th>
                          <th>No Mesin</th>
                          <th>No Rangka</th>
                          <th>Nama Konsumen</th>
                          <th>Tipe Unit</th>
                          <th>Warna</th>
                          <th>Kelengkapan Unit</th>
                          <th>Nomor Kontak Penerima</th>
                          <th>Tgl Pengiriman</th>
                          <th>Waktu Pengiriman</th>
                          <th>Lokasi Pengiriman</th>
                          <th>Sales</th>
                          <!-- <th>Nama Supir</th> -->
                        </thead>
                        <tbody>
                          <tr v-for="(unt, index) of units">
                            <td>{{index+1}}</td>
                            <td>{{unt.no_mesin}}</td>
                            <td>{{unt.no_rangka}}</td>
                            <td>{{unt.nama_konsumen}}</td>
                            <td>{{unt.tipe_ahm}}</td>
                            <td>{{unt.warna}}</td>
                            <td>{{unt.ksu}}</td>
                            <td>{{unt.no_hp_penerima}}</td>
                            <td>{{unt.tgl_pengiriman}}</td>
                            <td>{{unt.waktu_pengiriman}}</td>
                            <td>{{unt.lokasi_pengiriman}}</td>
                            <td>{{unt.sales}}
                              <input type="hidden" name="id_sales_order[]" v-model="unt.id_sales_order">
                            </td>
                            <!-- <td v-if="mode!='assign_supir'">{{unt.nama_supir}}</td> -->
                            <!-- <td v-if="mode=='assign_supir'" width="15%">
                              
                              <select name="nama_supir[]" v-model="unt.id_master_plat" class="form-control select2" required>
                                <option value=""></option>
                                <?php
                                $id_dealer = $this->m_admin->cari_dealer();
                                $driver = $this->db->query("SELECT * FROM ms_plat_dealer WHERE id_dealer ='$id_dealer' ");
                                if ($driver->num_rows() > 0) {
                                  foreach ($driver->result() as $dr) {
                                    echo "<option value='$dr->id_master_plat'>$dr->no_plat | $dr->driver </option>";
                                  }
                                }
                                ?>
                              </select>
                            </td> -->
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div v-if="mode=='assign_supir' || mode=='detail'">
                    <div class="col-md-12">
                      <table style="width: 20%">
                        <tr>
                          <td>Proses PDI</td>
                          <td><input type="checkbox" name="proses_pdi" <?= isset($row) ? $row->proses_pdi == 1 ? 'checked' : '' : '' ?> <?= $disabled ?>></td>
                        </tr>
                        <tr>
                          <td>Manual Book</td>
                          <td><input type="checkbox" name="manual_book" <?= isset($row) ? $row->manual_book == 1 ? 'checked' : '' : '' ?> <?= $disabled ?>></td>
                        </tr>
                        <tr>
                          <td>Standard Tool Kit</td>
                          <td><input type="checkbox" name="standard_toolkit" <?= isset($row) ? $row->standard_toolkit == 1 ? 'checked' : '' : '' ?> <?= $disabled ?>></td>
                        </tr>
                        <tr>
                          <td>Helmet</td>
                          <td><input type="checkbox" name="helmet" <?= isset($row) ? $row->helmet == 1 ? 'checked' : '' : '' ?> <?= $disabled ?>></td>
                        </tr>
                        <tr>
                          <td>Spion</td>
                          <td><input type="checkbox" name="spion" <?= isset($row) ? $row->spion == 1 ? 'checked' : '' : '' ?> <?= $disabled ?>></td>
                        </tr>
                        <tr>
                          <td>BPPSG</td>
                          <td><input type="checkbox" name="bppgs" <?= isset($row) ? $row->bppgs == 1 ? 'checked' : '' : '' ?> <?= $disabled ?>></td>
                        </tr>
                        <tr>
                          <td>Aksesoris</td>
                          <td><input type="checkbox" name="aksesoris" <?= isset($row) ? $row->aksesoris == 1 ? 'checked' : '' : '' ?> <?= $disabled ?>></td>
                        </tr>
                        <tr>
                          <td>Direct Gift</td>
                          <td><input type="checkbox" name="direct_gift" <?= isset($row) ? $row->direct_gift == 1 ? 'checked' : '' : '' ?> <?= $disabled ?>></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div><!-- /.box-body -->

                <div class="box-footer" v-if="mode!='detail'">
                  <div class="col-sm-12" v-if="mode=='insert'||mode=='edit'||mode=='assign_supir'" align="center">
                    <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <?php
      $data['data'] = ['modalDealerDeliveryUnit'];
      $this->load->view('dealer/h1_dealer_api', $data); ?>
      <script>
        function pilihDriver(params) {
          $('#id_master_plat').val(params.id_master_plat);
          $('#driver').val(params.driver);
        }
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            total_harga: '',
            units: <?= isset($units) ? json_encode($units) : '[]' ?>,
          },
          methods: {
            getUnit: function() {
              var tgl_pengiriman = $('#tgl_pengiriman').val();
              var id_master_plat = $('#id_master_plat').val();
              if (tgl_pengiriman == '') {
                alert('Silahkan pilih tanggal pengiriman !');
                return false
              }
              if (id_master_plat == '') {
                alert('Silahkan pilih driver !');
                return false
              }
              values = {
                tgl_pengiriman: tgl_pengiriman,
                id_master_plat: id_master_plat
              }
              $.ajax({
                beforeSend: function() {
                  $('#gnrtBtn').attr('disabled', true);
                },
                url: '<?= base_url('dealer/generate_list_unit_delivery/get_unit') ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  var length = response.length;
                  form_.units = [];
                  if (response.length == 0) {
                    alert('Data tidak ditemukan !');
                  }
                  for (dtl of response) {
                    form_.units.push(dtl);
                  }
                  console.log(form_.units)
                  $('#gnrtBtn').attr('disabled', false);
                },
                error: function() {
                  alert("Error");
                  $('#gnrtBtn').attr('disabled', false);

                },
                statusCode: {
                  500: function() {
                    alert('Error Code 500');
                    $('#gnrtBtn').attr('disabled', false);

                  }
                }
              });
            },
            clearDealers: function() {
              this.dealer = {
                id_dealer: '',
                nama_dealer: ''
              }
            },
            addDealers: function() {
              if (this.dealers.length > 0) {
                for (dl of this.dealers) {
                  if (dl.id_dealer === this.dealer.id_dealer) {
                    alert("Dealer Sudah Dipilih !");
                    this.clearDealers();
                    return;
                  }
                }
              }
              if (this.dealer.id_dealer == '') {
                alert('Pilih Dealer !');
                return false;
              }
              this.dealers.push(this.dealer);
              this.clearDealers();
            },

            delDealers: function(index) {
              this.dealers.splice(index, 1);
            },
            getDealer: function() {
              var el = $('#dealer').find('option:selected');
              var id_dealer = el.attr("id_dealer");
              form_.dealer.id_dealer = id_dealer;
            },
          },
        });
      </script>
    <?php
    } elseif ($set == "index") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/generate_list_unit_delivery/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
            </a>
            <a href="dealer/generate_list_unit_delivery/schedule">
              <button class="btn bg-green btn-flat margin"><i class="fa fa-calendar"></i> Schedule</button>
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
          <table id="datatable_server" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID Pengiriman</th>
                <th>Tanggal Pengiriman</th>
                <th>Driver</th>
                <th>Total Unit</th>
                <th width="10%">Aksi</th>
              </tr>
            </thead>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
      <script>
        $(document).ready(function() {
          var dataTable = $('#datatable_server').DataTable({
            "processing": true,
            "serverSide": true,
            "language": {
              "infoFiltered": "",
              "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
            },
            "order": [],
            "lengthMenu": [
              [10, 25, 50, 75, 100],
              [10, 25, 50, 75, 100]
            ],
            "ajax": {
              url: "<?php echo site_url('dealer/generate_list_unit_delivery/fetch'); ?>",
              type: "POST",
              dataSrc: "data",
              data: function(d) {
                return d;
              },
            },
            "columnDefs": [{
                "targets": [4],
                "orderable": false
              },
              {
                "targets": [4],
                "className": 'text-center'
              },
              // // { "targets":[0],"checkboxes":{'selectRow':true}}
              // { "targets":[6,7],"className":'text-right'}, 
              // // { "targets":[2,4,5], "searchable": false } 
            ],
          });
        });
      </script>
    <?php
    } elseif ($set == "schedule") {
    ?>

      <div class="box">
        <div class="box-header with-border">
	  <h3 class="box-title">
	    <a href="dealer/generate_list_unit_delivery/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add Data</button>
            </a>
            <a href="dealer/generate_list_unit_delivery">
              <button class="btn bg-orange btn-flat margin"><i class="fa fa-eye"></i> List Data</button>
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

	  <div class="table-responsive">
	  <div class="row">
           <table id="datatable_server" class="table table-bordered">
            <thead>
              <tr>
		<?php 
			$awal = date('d M Y');
			$awal = date('d M Y', strtotime($awal. ' -2 day'));
			$date = $awal;
			$jumlah_hari =  3;
			$ukuran = round(100/$jumlah_hari).'%';
			for($i=0; $i<$jumlah_hari;$i++){
				$date = date('d M Y', strtotime($date. ' +1 day'));
		?>
                			<th width="<?php echo $ukuran?>" class="text-center"><?php echo $date; ?></th>
		<?php 
			}
		?>
              </tr>
            </thead>
	    <tbody>
		<?php 
		$start_date = date('Y-m-d');
		$date = date('Y-m-d', strtotime($start_date. ' -2 day'));
		for($i=0; $i<$jumlah_hari;$i++){
			$date = date('Y-m-d', strtotime($date. ' +1 day'));
		?>
		<td width="<?php echo $ukuran?>">
			<div class="accordion" id="accordionExample">
			<?php 
			 	$list_data = $this->m_generate->get_list_driver_schedule($date,$id_dealer);
			 	if($list_data!=false){
				 	foreach($list_data as $row){
			?>
			 		 <div class="card">
						<div class="card-header" id="h<?php echo $row->no_mesin ?>">
						  <h2 class="mb-0">
							<button class="btn btn-flat" type="button" data-toggle="collapse" data-target="#<?php echo $row->no_mesin ?>" aria-expanded="true" aria-controls="collapseOne">
							   <b><?php echo $row->nama_konsumen?></b>
							</button>
						  </h2>
						</div>

						<div id="<?php echo $row->no_mesin ?>" class="collapse" aria-labelledby="h<?php echo $row->no_mesin ?>" data-parent="#accordionExample">
						  <div class="card-body">
							<table>
							   <tr>
							   	<td>No Mesin</td>
							   	<td>:</td>
							   	<td><?php echo $row->no_mesin; ?></td>
							   </tr>
							   <tr>
							   	<td>Tipe Motor</td>
							   	<td>:</td>
							   	<td><?php echo $row->tipe_ahm; ?></td>
							   </tr>
							   <tr>
							   	<td>Warna</td>
							   	<td>:</td>
							   	<td><?php echo $row->warna; ?></td>
							   </tr>

							   <tr>
							   	<td>Driver</td>
							   	<td>:</td>
							   	<td><?php echo $row->driver; ?></td>
							   </tr>
							   <tr>
							   	<td>No Polisi</td>
							   	<td>:</td>
							   	<td><?php echo $row->no_plat; ?></td>
							   </tr>
							   <tr>
							   	<td>Alamat</td>
							   	<td>:</td>
							   	<td><?php echo $row->alamat; ?></td>
							   </tr>

							   <tr>
							   	<td>Kecamatan</td>
							   	<td>:</td>
							   	<td><?php echo $row->kecamatan; ?></td>
							   </tr>
							   <tr>
							   	<td>Status</td>
							   	<td>:</td>
							   	<td><?php echo $row->status; ?></td>
							   </tr>
							</table>
						  </div>
						</div>
					  </div>
			<?php
			}			
		}else{
			echo 'No Data.';
		}
		?>
			</div>
		</td>
		<?php 
			}
		?>
	    </tbody>
          </table>
	  </div>
	  </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
      <script>
        $(document).ready(function() {
	  <?php /* 
          var dataTable = $('#datatable_server').DataTable({
            "processing": true,
            "serverSide": true,
            "language": {
              "infoFiltered": "",
              "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
            },
            "order": [],
            "lengthMenu": [
              [10, 25, 50, 75, 100],
              [10, 25, 50, 75, 100]
            ],
            "ajax": {
              url: "<?php echo site_url('dealer/generate_list_unit_delivery/fetch'); ?>",
              type: "POST",
              dataSrc: "data",
              data: function(d) {
                return d;
              },
            },
            "columnDefs": [{
                "targets": [4],
                "orderable": false
              },
              {
                "targets": [4],
                "className": 'text-center'
              },
              // // { "targets":[0],"checkboxes":{'selectRow':true}}
              // { "targets":[6,7],"className":'text-right'}, 
              // // { "targets":[2,4,5], "searchable": false } 
            ],
          }); */ ?>
        });
      </script>

    <?php
    }
    ?>
  </section>
</div>