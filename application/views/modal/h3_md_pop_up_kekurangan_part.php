<!-- Modal -->
<div id="h3_md_pop_up_kekurangan_part" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style='width: 80%'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Barang Kurang</h4>
            </div>
            <div class="modal-body">
                <span class="text-bold">Terdapat sisa <span id='sisa_part'>0</span> part yang belum discan. Apakah tetap disimpan dan diclose atau scan ulang?</span>
                <div style='margin: 20px 0;' class="container-fluid no-padding">
                    <table id='check_kekurangan_part' style='margin-top: 25px;' class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th class='align-middle'>No</th>
                                <th class='align-middle'>No. Karton</th>
                                <th class='align-middle'>Kode Part</th>
                                <th class='align-middle'>Nama Part</th>
                                <th class='align-middle'>Qty PS</th>
                                <th class='align-middle'>Qty Scan</th>
                                <th class='text-center'>Qty Claim AHM</th>
                                <th class='text-center'></th>
                                <th class='text-center'>Qty Claim Ekspedisi</th>
                                <th class='text-center'></th>
                                <th width='3%' class='align-middle'></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div id="check_input"></div>
                <script>
                check_input = new Vue({
                    el: '#check_input',
                    data: {
                        checked: []
                    },
                    watch: {
                        checked: {
                            deep: true,
                            handler: function(){
                                // check_kekurangan_part.draw();
                                drawing_check_kekurangan_part();
                            }
                        }
                    }
                });

                // $(document).ready(function(){
                //     $("#h3_md_pop_up_kekurangan_part").on('change',"input[type='checkbox']",function(e){
                //         target = $(e.target);
                //         id = target.attr('data-id');

                //         if(target.is(':checked')){
                //             check_input.checked.push(id);
                //         }else{
                //             index_dealer = _.indexOf(check_input.checked, id);
                //             check_input.checked.splice(index_dealer, 1);
                //         }
                //     });

                //     check_kekurangan_part = $('#check_kekurangan_part').DataTable({
                //         processing: true,
                //         serverSide: true,
                //         searching: false,
                //         order: [],
                //         ajax: {
                //             url: "",
                //             dataSrc: function(json){
                //                 $('#sisa_part').text(
                //                     accounting.formatMoney(json.recordsTotal, "", 0, ".", ",")
                //                 );

                //                 part_yang_harus_diproses = _.chain(json.data)
                //                 .filter(function(item){
                //                     return item.tersimpan == 1;
                //                 })
                //                 .value();

                //                 if(part_yang_harus_diproses.length > 0){
                //                     $("#button_ahm_belum_kirim").attr("disabled", true);
                //                 }else{
                //                     $("#button_ahm_belum_kirim").attr("disabled", false);
                //                 }

                //                 return json.data;
                //             },
                //             type: "POST",
                //             data: function(d){
                //                 d.no_surat_jalan_ekspedisi = form_.penerimaan_barang.no_surat_jalan_ekspedisi;
                //                 d.checked = check_input.checked;
                //             }
                //         },
                //         columns: [
                //             { data: 'index', orderable: false, width: '3%' }, 
                //             { data: 'nomor_karton' }, 
                //             { data: 'id_part' }, 
                //             { data: 'nama_part' }, 
                //             { 
                //                 data: 'packing_sheet_quantity', 
                //                 render: function(data){
                //                   return accounting.formatMoney(data, "", 0, ".", ",");
                //                 }
                //             }, 
                //             { 
                //                 data: 'qty_diterima', 
                //                 render: function(data){
                //                   return accounting.formatMoney(data, "", 0, ".", ",");
                //                 }
                //             }, 
                //             { 
                //                 data: 'qty_reason_ahm',
                //                 render: function(data){
                //                   return accounting.formatMoney(data, "", 0, ".", ",");
                //                 }
                //             }, 
                //             { data: 'action_qty_reason_ahm', orderable: false, className: 'text-center', width: '3%' }, 
                //             { 
                //                 data: 'qty_reason_ekspedisi', 
                //                 render: function(data){
                //                   return accounting.formatMoney(data, "", 0, ".", ",");
                //                 }
                //             }, 
                //             { data: 'action_qty_ekspedisi', orderable: false, className: 'text-center', width: '3%' }, 
                //             { data: 'action', orderable: false, width: '3%', className: 'text-center' }, 
                //         ],
                //     });
                // });
                $(document).ready(function(){
                    $("#h3_md_pop_up_kekurangan_part").on('change',"input[type='checkbox']",function(e){
                        target = $(e.target);
                        id = target.attr('data-id');

                        if(target.is(':checked')){
                            check_input.checked.push(id);
                        }else{
                            index_dealer = _.indexOf(check_input.checked, id);
                            check_input.checked.splice(index_dealer, 1);
                        }
                    });
                });

                var check_kekurangan_part;
                function drawing_check_kekurangan_part(){
                    check_kekurangan_part = $('#check_kekurangan_part').DataTable({
                        processing: true,
                        serverSide: true,
                        searching: false,
                        bDestroy: true, 
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/check_kekurangan_part') ?>",
                            dataSrc: function(json){
                                $('#sisa_part').text(
                                    accounting.formatMoney(json.recordsTotal, "", 0, ".", ",")
                                );

                                part_yang_harus_diproses = _.chain(json.data)
                                .filter(function(item){
                                    return item.tersimpan == 1;
                                })
                                .value();

                                if(part_yang_harus_diproses.length > 0){
                                    $("#button_ahm_belum_kirim").attr("disabled", true);
                                }else{
                                    $("#button_ahm_belum_kirim").attr("disabled", false);
                                }

                                return json.data;
                            },
                            type: "POST",
                            data: function(d){
                                d.no_surat_jalan_ekspedisi = form_.penerimaan_barang.no_surat_jalan_ekspedisi;
                                d.checked = check_input.checked;
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' }, 
                            { data: 'nomor_karton' }, 
                            { data: 'id_part' }, 
                            { data: 'nama_part' }, 
                            { 
                                data: 'packing_sheet_quantity', 
                                render: function(data){
                                  return accounting.formatMoney(data, "", 0, ".", ",");
                                }
                            }, 
                            { 
                                data: 'qty_diterima', 
                                render: function(data){
                                  return accounting.formatMoney(data, "", 0, ".", ",");
                                }
                            }, 
                            { 
                                data: 'qty_reason_ahm',
                                render: function(data){
                                  return accounting.formatMoney(data, "", 0, ".", ",");
                                }
                            }, 
                            { data: 'action_qty_reason_ahm', orderable: false, className: 'text-center', width: '3%' }, 
                            { 
                                data: 'qty_reason_ekspedisi', 
                                render: function(data){
                                  return accounting.formatMoney(data, "", 0, ".", ",");
                                }
                            }, 
                            { data: 'action_qty_ekspedisi', orderable: false, className: 'text-center', width: '3%' }, 
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }, 
                        ],
                    });
                };
                </script>
                <div class="row" style='margin-top: 10px;'>
                    <div class="col-sm-3 text-center">
                        <button id='button_ahm_belum_kirim' class="btn btn-flat btn-info btn-sm" @click.prevent='ahm_belum_kirim'>AHM Belum kirim</button>
                    </div>
                    <div class="col-sm-3 text-center">
                        <button class="btn btn-flat btn-success btn-sm" @click.prevent='create_berita_acara'>Create Berita Acara</button>
                    </div>
                    <div class="col-sm-3 text-center">
                        <button class="btn btn-flat btn-primary btn-sm" @click.prevent='proses_claim'>Proses Claim</button>
                    </div>
                    <div class="col-sm-3 text-center">
                        <button class="btn btn-flat btn-sm" type='button' data-dismiss='modal'>Lanjut Scan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>