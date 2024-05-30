<style type="text/css">
    .myTable1 {

        margin-bottom: 0px;

    }

    .myt {

        margin-top: 0px;

    }

    .isi {

        height: 30px;

        padding-left: 5px;

        padding-right: 5px;

        margin-right: 0px;

    }

    .isi_combo {

        height: 30px;

        border: 1px solid #ccc;

        padding-left: 1.5px;


    }
</style>

<base href="<?php echo base_url(); ?>" />

<div iv class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

        <h1>

            <?php echo $title; ?>

        </h1>

        <ol class="breadcrumb">

            <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>

            <li class="">H1</li>

            <li class="">Bussiness Control</li>

            <li class=""><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
            <li class="active"><?= $set ?></li>

        </ol>

    </section>

    <?php
    if ($set == "view") {

    ?>

        <section class="content">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">

                    </h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <a href="<?= base_url("h1/claim_program_ahm/addfield/") ?>" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add New</a>
                    <div class="table-responsive">
                        <table class="table table-sm" id="tb_memos">

                            <thead class="text-center">
                                <tr>

                                    <th>No.</th>
                                    <th>No. Memo</th>
                                    <th>Alasan</th>
                                    <th>Jumlah Claim</th>
                                    <th>Action</th>

                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($query as $q) {

                                ?>
                                    <tr>

                                        <td><?= $i++ ?></td>
                                        <td><?= $q['memo'] ?></td>
                                        <td><?= $q['alasan'] ?></td>
                                        <td><?= $q['mm'] ?></td>
                                        <td><a href="<?= base_url('h1/claim_program_ahm/export/') . $q['memo'] . '/' . $q['alasan'] ?>" class="btn btn-success btn-flat">Export</a></td>



                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    </table>

                </div>
            </div>
        </section>
    <?php
    } else if ($set == "addmemo") {
    ?>

    <?php
    }
    ?>
</div>

<script>
    $(document).ready(function() {
        $('#tb_memos').DataTable({
            "processing": true,
            "searching": true,
            "lengthChange": false
        });
    });
</script>