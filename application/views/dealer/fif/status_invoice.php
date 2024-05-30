
<base href="<?php echo base_url(); ?>" />

<div class="content-wrapper">

<!-- Content Header (Page header) -->

<section class="content-header">

  <h1>

    <?php echo $title; ?>    

  </h1>

  <ol class="breadcrumb">

    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>        

    <li class="">Dealer</li>

    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>

  </ol>

  </section>

  <section class="content">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">


     <div class="box box-default">

      <div class="box-header with-border">              

        <div class="row">

          <div class="col-md-12">


              <div class="box-body">

              <a href="dealer/api_fif/index?page=all_order" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
              <br><br>


              <?php 
              $rw = json_decode($hasil)->data[0];
               ?>
              <table class="table">
                <tr>
                  <td>No Invoice Dealer</td>
                  <td><?php echo $rw->chan_inv_no ?></td>
                </tr>
                <tr>
                  <td>Invoice Date Dealer</td>
                  <td><?php echo $rw->chan_inv_date ?></td>
                </tr>
                <tr>
                  <td>FIF No Invoice</td>
                  <td><?php echo $rw->fif_inv_no ?></td>
                </tr>
                <tr>
                  <td>FIF Inv Date</td>
                  <td><?php echo $rw->fif_inv_date ?></td>
                </tr>
                <tr>
                  <td>Status Invoice</td>
                  <td><b><?php echo $rw->inv_status ?></b></td>
                </tr>
                <tr>
                  <td>Invoice UIID</td>
                  <td><?php echo $rw->inv_uuid ?></td>
                </tr>
                <tr>
                  <td>Order UUID</td>
                  <td><?php echo $rw->order_uuid ?></td>
                </tr>
                <tr>
                  <td>Paid Date</td>
                  <td><?php echo $rw->paid_date ?></td>
                </tr>

                <tr>
                  <td>Paid Amount</td>
                  <td><?php echo $rw->paid_amount ?></td>
                </tr>
                <tr>
                  <td>No PO</td>
                  <td><?php echo $rw->po_no ?></td>
                </tr>
                <tr>
                  <td>No PV</td>
                  <td><?php echo $rw->pv_no ?></td>
                </tr>
                <tr>
                  <td>No Bast</td>
                  <td><?php echo $rw->bast_no ?></td>
                </tr>
                <tr>
                  <td>Bast Date</td>
                  <td><?php echo $rw->bast_date ?></td>
                </tr>
                <tr>
                  <td>No Mesin</td>
                  <td>JM51E1883406</td>
                </tr>
                <tr>
                  <td>No Rangka</td>
                  <td>MH1JMXX141MKN</td>
                </tr>
                <tr>
                  <td>Deskripsi</td>
                  <td>VARIO 125 CBS ISS</td>
                </tr>
                  
              </table>

              <br><br>
              <h4>Detail Revisi</h4>
              <hr>
              <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Revisi No Bast</th>
                    <th>Revisi Bast Date</th>
                    <th>Revisi Warna</th>
                    <th>Revisi No Rangka</th>
                    <th>Revisi No Mesin</th>
                    <th>Revisi No Polisi</th>
                    <th>Revisi No Serial</th>
                    <th>Revisi Waranty</th>
                    <th>Revisi Tahun Produksi</th>
                    <th>Revisi Photo</th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>
              </div>

              </div><!-- /.box-body -->              

              

          </div>



        </div>

      </div>

    </div><!-- /.box -->

    

  </section>

</div>





