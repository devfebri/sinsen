
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
                  <td>Order UIID</td>
                  <td><?php echo $rw->order_uuid ?></td>
                </tr>
                <tr>
                  <td>Appl No</td>
                  <td><?php echo $rw->appl_no ?></td>
                </tr>
                <tr>
                  <td>Order Status</td>
                  <td><?php echo $rw->order_status ?></td>
                </tr>
                <tr>
                  <td>Order Arc Date</td>
                  <td><?php echo $rw->order_arc_date ?></td>
                </tr>
                <tr>
                  <td>Order Arc Reason</td>
                  <td><?php echo $rw->order_arc_reason ?></td>
                </tr>
                <tr>
                  <td>Order Arc Sub Reason</td>
                  <td><?php echo $rw->order_arc_sub_reason ?></td>
                </tr>
                <tr>
                  <td>Tenor</td>
                  <td><?php echo $rw->tenor ?></td>
                </tr>
                <tr>
                  <td>Amount Transfer</td>
                  <td>Rp. <?php echo number_format($rw->trf_amount,2) ?></td>
                </tr>
                <tr>
                  <td>Branch ID</td>
                  <td><?php echo $rw->branch_id ?></td>
                  </tr>
                  <tr>
                  <td>Date Send Invoice</td>
                  <td><?php 
                  if ($rw->tgl_kirim_invoice === null) {
                    $dateTimeString =  $disburst;
                } else {
                  $dateTimeString = $rw->tgl_kirim_invoice;
                }
                $dateTime = new DateTime($dateTimeString);
                echo $dateTime->format("m/d/Y H:i:s");
                  ?></td>
                </tr>
              </table>

              <br><br>
              <h4>Detail</h4>
              <hr>
              <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>No PO</th>
                    <th>No PO Seq</th>
                    <th>Status PO</th>
                    <th>PO Cancel Reason</th>
                    <th>PO Cancel Sub Reason</th>
                    <th>PO Date</th>
                    <th>No Invoice</th>
                    <th>Status Inv Paid</th>
                    <th>Date Inv Paid</th>
                    <th>No Inv Delivery</th>
                    <th>Obj Unit DP</th>
                    <th>Obj Installment</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($rw->object as $br): ?>
                    <tr>
                      <td><?php echo $br->seq_no ?></td>
                      <td>
                        <?php echo $br->po_no ?>
                        <?php if ($br->po_no != ''): ?>
                          <a href="dealer/api_fif/download_po/<?php echo $rw->order_uuid ?>/PO-<?php echo $br->po_no ?>" class="btn btn-success btn-xs">Download PO</a>
                        <?php endif ?> 
                      </td>
                      <td><?php echo $br->po_seq_no ?></td>
                      <td><?php echo $br->po_status ?></td>
                      <td><?php echo $br->po_cancel_reason ?></td>
                      <td><?php echo $br->po_cancel_sub_reason ?></td>
                      <td><?php echo $br->po_date ?></td>
                      <td><?php echo $br->inv_no ?></td>
                      <td><?php echo $br->inv_paid_status ?></td>
                      <td><?php echo $br->inv_paid_date ?></td>
                      <td><?php echo $br->delr_inv_no ?></td>
                      <td>Rp. <?php echo number_format($br->obj_unit_dp,2) ?></td>
                      <td>Rp. <?php echo number_format($br->obj_installment,2) ?></td>
                    </tr>
                  <?php endforeach ?>
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





