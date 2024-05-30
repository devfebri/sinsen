<div class="box">
    <div class="box-header with-border">
        <div class="col-sm-6">
        <h3 class="box-title">
            <?php if ($sales_campaign['produk_program_gimmick'] == 'Global') : ?>
            <a href="h3/<?= $isi ?>/download_perolehan_gimmick_global?id=<?= $sales_campaign['id'] ?>">
                <button class="btn btn-success btn-flat">Download Laporan</button>
            </a>
            <?php elseif ($sales_campaign['produk_program_gimmick'] == 'Per Item') : ?>
            <a href="h3/<?= $isi ?>/download_perolehan_gimmick_item?id=<?= $sales_campaign['id'] ?>">
                <button class="btn btn-success btn-flat">Download Laporan</button>
            </a>
            <?php endif; ?>
        </h3>
        </div>
        <div class="col-sm-6 text-right">
        <h3 class="box-title">
            <a href="h3/<?= $isi ?>/generate_laporan_gimmick_tidak_langsung?id=<?= $sales_campaign['id'] ?>">
            <button class="btn btn-info btn-flat">Hitung Perolehan Gimmick</button>
            </a>
        </h3>
        </div>
    </div><!-- /.box-header -->
    <div class="box-body">
        <?php $this->load->view('template/normal_session_message.php'); ?>
        <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 text-center">
            <h4>FORM REKAPAN PENCAIRAN HADIAH</h4>
            <h4><?= $sales_campaign['nama'] ?></h4>
            <span>Periode : <?= Mcarbon::parse($sales_campaign['start_date'])->format('d/m/Y') ?> - <?= Mcarbon::parse($sales_campaign['end_date'])->format('d/m/Y') ?></span>
            </div>
        </div>
        </div>
        <?php
        if ($sales_campaign['produk_program_gimmick'] == 'Global') :
        $this->load->view('modal/datatable_perolehan_gimmick_global');
        elseif ($sales_campaign['produk_program_gimmick'] == 'Per Item') :
        $this->load->view('modal/datatable_perolehan_gimmick_item');
        endif;
        ?>
    </div>
</div>