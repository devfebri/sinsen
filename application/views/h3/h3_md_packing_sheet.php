<base href="<?php echo base_url(); ?>" />
<body onload="auto()">
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?= $title; ?></h1>
            <?= $breadcrumb; ?>
        </section>
        <section class="content">
            <?php if($mode=="index"): ?>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">
                    <?php if($this->input->get('history') != null): ?>
                    <a href="h3/<?= $isi ?>">
                        <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
                    </a>  
                    <?php else: ?>
                    <a href="h3/<?= $isi ?>?history=true">
                        <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
                    </a> 
                    <?php endif; ?>
                    </h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="packing_sheet" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tgl Picking List</th>
                                <th>No Picking List</th>
                                <th>Tgl Faktur</th>
                                <th>No Faktur</th>
                                <th>Nama Customer</th>
                                <th>Alamat</th>
                                <th>Tgl Surat Jalan</th>
                                <th>No Surat Jalan</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <script>
                    $(document).ready(function(){
                      packing_sheet = $('#packing_sheet').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        scrollX: true,
                        ajax: {
                            url: "<?= base_url('api/md/h3/packing_sheet') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'tanggal_picking', name: 'pl.tanggal' }, 
                            { data: 'id_picking_list', width: '200px' }, 
                            { data: 'tanggal_faktur', name: 'ps.tgl_faktur', width: '100px' }, 
                            { data: 'no_faktur', width: '200px' }, 
                            { data: 'nama_dealer', width: '250px' }, 
                            { data: 'alamat', width: '250px' }, 
                            { data: 'tgl_packing_sheet', width: '100px' }, 
                            { data: 'id_packing_sheet', width: '200px' }, 
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                      });
                    });
                    </script>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            <?php endif; ?>
        </section>
    </div>
</body>
