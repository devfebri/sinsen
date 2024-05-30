<!-- Modal -->
<div id="h3_md_nomor_karton_penerimaan_barang" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Nomor Karton</h4>
        </div>
        <div class="modal-body">
            <div class="container-fluid" style='margin-bottom: 15px;'>
                <form>
                    <div class="row">
                        <div class="col-sm-4"> 
                            <div class="form-group" style='padding-right: 10px;'>
                                <label class="control-label">Surat Jalan AHM</label>
                                <input id='filter_surat_jalan_ahm' type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4"> 
                            <div class="form-group" style='padding-right: 10px;'>
                                <label class="control-label">Packing Sheet Number</label>
                                <input id='filter_packing_sheet_number' type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4"> 
                            <div class="form-group">
                                <label class="control-label">Nomor Karton</label>
                                <input id='filter_nomor_karton' type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 no-padding">
                            <button id='cari_filter' class="btn btn-flat btn-sm btn-primary">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- <script>
                $(document).ready(function(){
                    $('#cari_filter').on('click', function(e){
                        e.preventDefault();
                        h3_md_nomor_karton_penerimaan_barang_datatable.draw();
                    });
                });
            </script> -->
            <!-- <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_nomor_karton_penerimaan_barang_datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Surat Jalan AHM</th>
                        <th>Packing Sheet Number</th>
                        <th>Packing Sheet Date</th>
                        <th>Nomor Karton</th>
                        <th>Status</th>
                        <th>
                            <input type="checkbox" id='check-all-nomor-karton'>
                        </th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table> -->

            <div class="row">
                <div class="col-md-12">
                <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab" onclick="setSearch('biasa')">Penerimaan Non-EV</a></li>
                            <li><a href="#tab_2" data-toggle="tab" onclick="setSearch('ev')">Penerimaan EV</a></li>
                        </ul>
                        <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_nomor_karton_penerimaan_barang_datatable" style="width: 100%">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Surat Jalan AHM</th>
                                    <th>Packing Sheet Number</th>
                                    <th>Packing Sheet Date</th>
                                    <th>Nomor Karton</th>
                                    <th>Status</th>
                                    <th>
                                        <input type="checkbox" id='check-all-nomor-karton'>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_nomor_karton_penerimaan_barang_ev_datatable" style="width: 100%">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nomor Box</th>
                                    <th>Packing Sheet Number</th>
                                    <th>Nomor Karton</th>
                                    <th>Serial Number</th>
                                    <th>Tipe Acc</th>
                                    <th>
                                        <input type="checkbox" id='check-all-nomor-karton_ev'>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            </div>
                        </div>
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
                </div>
            </div>

            <script>
                 var activeTab = 'biasa'; 

                function setSearch(tab) {
                    activeTab = tab;
                    console.log(activeTab);
                }

                var h3_md_nomor_karton_penerimaan_barang_datatable; 
                var h3_md_nomor_karton_penerimaan_barang_ev_datatable; 
                $(document).ready(function() {
                    $('#cari_filter').click(function(e){
                        e.preventDefault();

                        if(activeTab === 'biasa'){
                            
                            h3_md_nomor_karton_penerimaan_barang_datatable =$('#h3_md_nomor_karton_penerimaan_barang_datatable').DataTable({
                                processing: true,
                                serverSide: true,
                                searching: false,
                                bDestroy: true,
                                order: [],
                                drawCallback: function(settings){
                                    if(settings.aoData.length == 0){
                                        $('#check-all-nomor-karton').hide();
                                    }else{
                                        $('#check-all-nomor-karton').show();
                                    }

                                    list_checklist = $('input.checkbox-nomor-karton[type=checkbox]').not(':disabled');

                                    if(list_checklist.length > 0){
                                        checklist_terchecked = 0;
                                        for(var i = 0; i < list_checklist.length; i++){
                                            var checklist = list_checklist[i];

                                            if($(checklist).is(':checked')) checklist_terchecked++;
                                        }

                                        if(checklist_terchecked == list_checklist.length){
                                            $('#check-all-nomor-karton').prop('checked', true);
                                        }else{
                                            $('#check-all-nomor-karton').prop('checked', false);
                                        }
                                    }else if(list_checklist.length == 0){
                                        $('#check-all-nomor-karton').prop('checked', false);
                                    }
                                },
                                ajax: {
                                    url: '<?= base_url('api/md/h3/nomor_karton_penerimaan_barang') ?>',
                                    dataSrc: 'data',
                                    type: 'POST',
                                    data: function(d){
                                        d.list_nomor_karton_int = _.map(form_.list_nomor_karton, function(data){
                                            return data.nomor_karton_int;
                                        });

                                        d.filter_surat_jalan_ahm = $('#filter_surat_jalan_ahm').val();
                                        d.filter_packing_sheet_number = $('#filter_packing_sheet_number').val();
                                        d.filter_nomor_karton = $('#filter_nomor_karton').val();
                                    }
                                },
                                columns: [
                                    { data: 'index', orderable: false, width: '3%'},
                                    { data: 'surat_jalan_ahm' },
                                    { data: 'packing_sheet_number' },
                                    { data: 'packing_sheet_date' },
                                    { data: 'no_doos' },
                                    { 
                                        data: 'status',
                                        render: function(data){
                                            if(data == 1)return 'Close';
                                            
                                            return 'Open';
                                        }
                                    },
                                    { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                                ],
                            });
                        
                            $('#check-all-nomor-karton').change(function(e){
                                list_checklist = $('input.checkbox-nomor-karton[type=checkbox]').not(':disabled');

                                if($(e.target).is(':checked')){
                                    console.log('sekaigus banyak')
                                    list_nomor_karton_int = [];
                                    for(var i = 0; i < list_checklist.length; i++){
                                        var checklist = list_checklist[i];

                                        list_nomor_karton_int.push($(checklist).data('nomor-karton-int'));
                                        $(checklist).prop('checked', true);
                                    }

                                    for(nomor_karton_int of list_nomor_karton_int){
                                        form_.list_nomor_karton.push({
                                            nomor_karton_int: nomor_karton_int,
                                            jenis_penerimaan_barang: 'non_ev',
                                        });
                                    }
                                }else{
                                    console.log('in');
                                    list_nomor_karton_int = [];
                                    for(var i = 0; i < list_checklist.length; i++){
                                        var checklist = list_checklist[i];

                                        list_nomor_karton_int.push($(checklist).data('nomor-karton-int'));
                                        $(checklist).prop('checked', false);
                                    }

                                    for(nomor_karton_int of list_nomor_karton_int){
                                        index = _.findIndex(form_.list_nomor_karton, function(row){
                                            return row.nomor_karton_int == nomor_karton_int;
                                        });

                                        if(index != -1){
                                            form_.list_nomor_karton.splice(index, 1);
                                        }
                                    }
                                }
                                h3_md_nomor_karton_penerimaan_barang_datatable.draw();
                            });
                        }else{
                            h3_md_nomor_karton_penerimaan_barang_ev_datatable =$('#h3_md_nomor_karton_penerimaan_barang_ev_datatable').DataTable({
                                processing: true,
                                serverSide: true,
                                searching: false,
                                bDestroy: true,
                                order: [],
                                drawCallback: function(settings){
                                    if(settings.aoData.length == 0){
                                        $('#check-all-nomor-karton_ev').hide();
                                    }else{
                                        $('#check-all-nomor-karton_ev').show();
                                    }

                                    list_checklist = $('input.checkbox-nomor-karton[type=checkbox]').not(':disabled');

                                    if(list_checklist.length > 0){
                                        checklist_terchecked = 0;
                                        for(var i = 0; i < list_checklist.length; i++){
                                            var checklist = list_checklist[i];

                                            if($(checklist).is(':checked')) checklist_terchecked++;
                                        }

                                        if(checklist_terchecked == list_checklist.length){
                                            $('#check-all-nomor-karton_ev').prop('checked', true);
                                        }else{
                                            $('#check-all-nomor-karton_ev').prop('checked', false);
                                        }
                                    }else if(list_checklist.length == 0){
                                        $('#check-all-nomor-karton_ev').prop('checked', false);
                                    }
                                },
                                ajax: {
                                    url: '<?= base_url('api/md/h3/nomor_karton_penerimaan_barang_ev') ?>',
                                    dataSrc: 'data',
                                    type: 'POST',
                                    data: function(d){
                                        d.list_nomor_karton_ev_int = _.map(form_.list_nomor_karton_ev, function(data){
                                            return data.nomor_karton;
                                        });

                                        d.filter_surat_jalan_ahm = $('#filter_surat_jalan_ahm').val();
                                        d.filter_packing_sheet_number = $('#filter_packing_sheet_number').val();
                                        d.filter_nomor_karton = $('#filter_nomor_karton').val();
                                    }
                                },
                                columns: [
                                    { data: 'index', orderable: false, width: '3%'},
                                    { data: 'box_id' },
                                    { data: 'packing_id' },
                                    { data: 'carton_id' },
                                    { data: 'serial_number' },
                                    { data: 'acc_tipe' },
                                    { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                                ],
                            });
                        
                            $('#check-all-nomor-karton_ev').change(function(e){
                                list_checklist = $('input.checkbox-nomor-karton[type=checkbox]').not(':disabled');
                                if($(e.target).is(':checked')){
                                    
                                    list_nomor_karton_ev_int = [];
                                    for(var i = 0; i < list_checklist.length; i++){
                                        var checklist = list_checklist[i];

                                        list_nomor_karton_ev_int.push($(checklist).data('nomor-karton'));
                                        $(checklist).prop('checked', true);
                                    }

                                    for(nomor_karton of list_nomor_karton_ev_int){
                                        form_.list_nomor_karton_ev.push({
                                            nomor_karton_int: '',
                                            nomor_karton: nomor_karton,
                                            jenis_penerimaan_barang: 'ev',
                                        });
                                    }
                                    
                                }else{
                                    list_nomor_karton_ev_int = [];
                                    for(var i = 0; i < list_checklist.length; i++){
                                        var checklist = list_checklist[i];

                                        list_nomor_karton_ev_int.push($(checklist).data('nomor-karton'));
                                        $(checklist).prop('checked', false);
                                    }

                                    for(nomor_karton of list_nomor_karton_ev_int){
                                        index = _.findIndex(form_.list_nomor_karton_ev, function(row){
                                            return row.nomor_karton == nomor_karton;
                                        });

                                        if(index != -1){
                                            form_.list_nomor_karton_ev.splice(index, 1);
                                        }
                                    }
                                }
                                h3_md_nomor_karton_penerimaan_barang_ev_datatable.draw();
                            });
                        }
                    });
                });
            


            // $(document).ready(function() {
            //     h3_md_nomor_karton_penerimaan_barang_datatable = $('#h3_md_nomor_karton_penerimaan_barang_datatable').DataTable({
            //         processing: true,
            //         serverSide: true,
            //         searching: false,
            //         order: [],
            //         drawCallback: function(settings){
            //             if(settings.aoData.length == 0){
            //                 $('#check-all-nomor-karton').hide();
            //             }else{
            //                 $('#check-all-nomor-karton').show();
            //             }

            //             list_checklist = $('input.checkbox-nomor-karton[type=checkbox]').not(':disabled');

            //             if(list_checklist.length > 0){
            //                 checklist_terchecked = 0;
            //                 for(var i = 0; i < list_checklist.length; i++){
            //                     var checklist = list_checklist[i];

            //                     if($(checklist).is(':checked')) checklist_terchecked++;
            //                 }

            //                 if(checklist_terchecked == list_checklist.length){
            //                     $('#check-all-nomor-karton').prop('checked', true);
            //                 }else{
            //                     $('#check-all-nomor-karton').prop('checked', false);
            //                 }
            //             }else if(list_checklist.length == 0){
            //                 $('#check-all-nomor-karton').prop('checked', false);
            //             }
            //         },
            //         ajax: {
            //             url: '',
            //             dataSrc: 'data',
            //             type: 'POST',
            //             data: function(d){
            //                 d.list_nomor_karton_int = _.map(form_.list_nomor_karton, function(data){
            //                     return data.nomor_karton_int;
            //                 });

            //                 d.filter_surat_jalan_ahm = $('#filter_surat_jalan_ahm').val();
            //                 d.filter_packing_sheet_number = $('#filter_packing_sheet_number').val();
            //                 d.filter_nomor_karton = $('#filter_nomor_karton').val();
            //             }
            //         },
            //         columns: [
            //             { data: 'index', orderable: false, width: '3%'},
            //             { data: 'surat_jalan_ahm' },
            //             { data: 'packing_sheet_number' },
            //             { data: 'packing_sheet_date' },
            //             { data: 'no_doos' },
            //             { 
            //                 data: 'status',
            //                 render: function(data){
            //                     if(data == 1)return 'Close';
                                
            //                     return 'Open';
            //                 }
            //             },
            //             { data: 'action', orderable: false, width: '3%', className: 'text-center' }
            //         ],
            //     });
                
            //     $('#check-all-nomor-karton').change(function(e){
            //         list_checklist = $('input.checkbox-nomor-karton[type=checkbox]').not(':disabled');

            //         if($(e.target).is(':checked')){
            //             list_nomor_karton_int = [];
            //             for(var i = 0; i < list_checklist.length; i++){
            //                 var checklist = list_checklist[i];

            //                 list_nomor_karton_int.push($(checklist).data('nomor-karton-int'));
            //                 $(checklist).prop('checked', true);
            //             }

            //             for(nomor_karton_int of list_nomor_karton_int){
            //                 form_.list_nomor_karton.push({
            //                     nomor_karton_int: nomor_karton_int
            //                 });
            //             }
            //         }else{
            //             list_nomor_karton_int = [];
            //             for(var i = 0; i < list_checklist.length; i++){
            //                 var checklist = list_checklist[i];

            //                 list_nomor_karton_int.push($(checklist).data('nomor-karton-int'));
            //                 $(checklist).prop('checked', false);
            //             }

            //             for(nomor_karton_int of list_nomor_karton_int){
            //                 index = _.findIndex(form_.list_nomor_karton, function(row){
            //                     return row.nomor_karton_int == nomor_karton_int;
            //                 });

            //                 if(index != -1){
            //                     form_.list_nomor_karton.splice(index, 1);
            //                 }
            //             }
            //         }
            //         h3_md_nomor_karton_penerimaan_barang_datatable.draw();
            //     });
            // });
            // var h3_md_nomor_karton_penerimaan_barang_datatable
            // function drawing_no_karton() {
            //     var h3_md_nomor_karton_penerimaan_barang_datatable =$('#h3_md_nomor_karton_penerimaan_barang_datatable').DataTable({
            //         processing: true,
            //         serverSide: true,
            //         searching: false,
            //         bDestroy: true,
            //         order: [],
            //         drawCallback: function(settings){
            //             if(settings.aoData.length == 0){
            //                 $('#check-all-nomor-karton').hide();
            //             }else{
            //                 $('#check-all-nomor-karton').show();
            //             }

            //             list_checklist = $('input.checkbox-nomor-karton[type=checkbox]').not(':disabled');

            //             if(list_checklist.length > 0){
            //                 checklist_terchecked = 0;
            //                 for(var i = 0; i < list_checklist.length; i++){
            //                     var checklist = list_checklist[i];

            //                     if($(checklist).is(':checked')) checklist_terchecked++;
            //                 }

            //                 if(checklist_terchecked == list_checklist.length){
            //                     $('#check-all-nomor-karton').prop('checked', true);
            //                 }else{
            //                     $('#check-all-nomor-karton').prop('checked', false);
            //                 }
            //             }else if(list_checklist.length == 0){
            //                 $('#check-all-nomor-karton').prop('checked', false);
            //             }
            //         },
            //         ajax: {
            //             url: '<?= base_url('api/md/h3/nomor_karton_penerimaan_barang') ?>',
            //             dataSrc: 'data',
            //             type: 'POST',
            //             data: function(d){
            //                 d.list_nomor_karton_int = _.map(form_.list_nomor_karton, function(data){
            //                     return data.nomor_karton_int;
            //                 });

            //                 d.filter_surat_jalan_ahm = $('#filter_surat_jalan_ahm').val();
            //                 d.filter_packing_sheet_number = $('#filter_packing_sheet_number').val();
            //                 d.filter_nomor_karton = $('#filter_nomor_karton').val();
            //             }
            //         },
            //         columns: [
            //             { data: 'index', orderable: false, width: '3%'},
            //             { data: 'surat_jalan_ahm' },
            //             { data: 'packing_sheet_number' },
            //             { data: 'packing_sheet_date' },
            //             { data: 'no_doos' },
            //             { 
            //                 data: 'status',
            //                 render: function(data){
            //                     if(data == 1)return 'Close';
                                
            //                     return 'Open';
            //                 }
            //             },
            //             { data: 'action', orderable: false, width: '3%', className: 'text-center' }
            //         ],
            //     });
                
            //     $('#check-all-nomor-karton').change(function(e){
            //         list_checklist = $('input.checkbox-nomor-karton[type=checkbox]').not(':disabled');

            //         if($(e.target).is(':checked')){
            //             list_nomor_karton_int = [];
            //             for(var i = 0; i < list_checklist.length; i++){
            //                 var checklist = list_checklist[i];

            //                 list_nomor_karton_int.push($(checklist).data('nomor-karton-int'));
            //                 $(checklist).prop('checked', true);
            //             }

            //             for(nomor_karton_int of list_nomor_karton_int){
            //                 form_.list_nomor_karton.push({
            //                     nomor_karton_int: nomor_karton_int
            //                 });
            //             }
            //         }else{
            //             list_nomor_karton_int = [];
            //             for(var i = 0; i < list_checklist.length; i++){
            //                 var checklist = list_checklist[i];

            //                 list_nomor_karton_int.push($(checklist).data('nomor-karton-int'));
            //                 $(checklist).prop('checked', false);
            //             }

            //             for(nomor_karton_int of list_nomor_karton_int){
            //                 index = _.findIndex(form_.list_nomor_karton, function(row){
            //                     return row.nomor_karton_int == nomor_karton_int;
            //                 });

            //                 if(index != -1){
            //                     form_.list_nomor_karton.splice(index, 1);
            //                 }
            //             }
            //         }
            //         h3_md_nomor_karton_penerimaan_barang_datatable.draw();
            //     });
            // }

            // var h3_md_nomor_karton_penerimaan_barang_datatable; 
            // $(document).ready(function() {
            //     $('#cari_filter').click(function(e){
            //         // $('#h3_md_nomor_karton_penerimaan_barang_datatable').DataTable().clear().destroy();
            //         e.preventDefault();
            //     //    drawing_no_karton();
                
            //     h3_md_nomor_karton_penerimaan_barang_datatable =$('#h3_md_nomor_karton_penerimaan_barang_datatable').DataTable({
            //         processing: true,
            //         serverSide: true,
            //         searching: false,
            //         bDestroy: true,
            //         order: [],
            //         drawCallback: function(settings){
            //             if(settings.aoData.length == 0){
            //                 $('#check-all-nomor-karton').hide();
            //             }else{
            //                 $('#check-all-nomor-karton').show();
            //             }

            //             list_checklist = $('input.checkbox-nomor-karton[type=checkbox]').not(':disabled');

            //             if(list_checklist.length > 0){
            //                 checklist_terchecked = 0;
            //                 for(var i = 0; i < list_checklist.length; i++){
            //                     var checklist = list_checklist[i];

            //                     if($(checklist).is(':checked')) checklist_terchecked++;
            //                 }

            //                 if(checklist_terchecked == list_checklist.length){
            //                     $('#check-all-nomor-karton').prop('checked', true);
            //                 }else{
            //                     $('#check-all-nomor-karton').prop('checked', false);
            //                 }
            //             }else if(list_checklist.length == 0){
            //                 $('#check-all-nomor-karton').prop('checked', false);
            //             }
            //         },
            //         ajax: {
            //             url: '<?= base_url('api/md/h3/nomor_karton_penerimaan_barang') ?>',
            //             dataSrc: 'data',
            //             type: 'POST',
            //             data: function(d){
            //                 d.list_nomor_karton_int = _.map(form_.list_nomor_karton, function(data){
            //                     return data.nomor_karton_int;
            //                 });
            //                 // d.list_nomor_karton = _.map(form_.list_nomor_karton2, function(data){
            //                 //     return data.nomor_karton;
            //                 // });

            //                 d.filter_surat_jalan_ahm = $('#filter_surat_jalan_ahm').val();
            //                 d.filter_packing_sheet_number = $('#filter_packing_sheet_number').val();
            //                 d.filter_nomor_karton = $('#filter_nomor_karton').val();
            //             }
            //         },
            //         columns: [
            //             { data: 'index', orderable: false, width: '3%'},
            //             { data: 'surat_jalan_ahm' },
            //             { data: 'packing_sheet_number' },
            //             { data: 'packing_sheet_date' },
            //             { data: 'no_doos' },
            //             { 
            //                 data: 'status',
            //                 render: function(data){
            //                     if(data == 1)return 'Close';
                                
            //                     return 'Open';
            //                 }
            //             },
            //             { data: 'action', orderable: false, width: '3%', className: 'text-center' }
            //         ],
            //     });
                
            //     $('#check-all-nomor-karton').change(function(e){
            //         list_checklist = $('input.checkbox-nomor-karton[type=checkbox]').not(':disabled');

            //         if($(e.target).is(':checked')){
            //             list_nomor_karton_int = [];
            //             // list_nomor_karton=[];
            //             for(var i = 0; i < list_checklist.length; i++){
            //                 var checklist = list_checklist[i];

            //                 list_nomor_karton_int.push($(checklist).data('nomor-karton-int'));
            //                 // list_nomor_karton.push($(checklist).data('nomor-karton'));
            //                 $(checklist).prop('checked', true);
            //             }

            //             for(nomor_karton_int of list_nomor_karton_int){
            //                 form_.list_nomor_karton.push({
            //                     nomor_karton_int: nomor_karton_int
            //                 });
            //             }
            //             // for(nomor_karton of list_nomor_karton){
            //             //     form_.list_nomor_karton2.push({
            //             //         nomor_karton: nomor_karton
            //             //     });
            //             // }
            //         }else{
            //             list_nomor_karton_int = [];
            //             // list_nomor_karton = [];
            //             for(var i = 0; i < list_checklist.length; i++){
            //                 var checklist = list_checklist[i];

            //                 list_nomor_karton_int.push($(checklist).data('nomor-karton-int'));
                            
            //                 // list_nomor_karton.push($(checklist).data('nomor-karton'));
            //                 $(checklist).prop('checked', false);
            //             }

            //             for(nomor_karton_int of list_nomor_karton_int){
            //                 index = _.findIndex(form_.list_nomor_karton, function(row){
            //                     return row.nomor_karton_int == nomor_karton_int;
            //                 });

            //                 if(index != -1){
            //                     form_.list_nomor_karton.splice(index, 1);
            //                 }
            //             }

            //             // for(nomor_karton of list_nomor_karton){
            //             //     index = _.findIndex(form_.list_nomor_karton2, function(row){
            //             //         return row.nomor_karton == nomor_karton;
            //             //     });

            //             //     if(index != -1){
            //             //         form_.list_nomor_karton2.splice(index, 1);
            //             //     }
            //             // }
            //         }
            //         h3_md_nomor_karton_penerimaan_barang_datatable.draw();
            //     });
            //      });
            // });
            </script>
        </div>
        </div>
    </div>
</div>