<!-- Modal -->
<form class="form-horizontal" method="post" action="dealer/h3_dealer_stock_opname/request_recount" enctype="multipart/form-data">
<div id="request_recount_stock_opname" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog"> 
        <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title text-left" id="myModalLabel">Keterangan Request Recount</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="<?php echo $stock_opname->id_stock_opname?>" name="id_stock_opname">
                    <textarea name="keterangan" class="form-control"></textarea>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-flat btn-sm btn-primary">Proses</button>
                    
              </div>
                </div>
            
        </div>
    </div>
</div>
</form>
