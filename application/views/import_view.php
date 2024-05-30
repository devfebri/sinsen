<!-- Content Header (Page header) -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

<script type="text/javascript">
function load_data_temp()
        {  
            var filename =$('#filename').val();
            $.ajax({
                type:"GET",
                url:"<?php echo base_url('import_data/load_temp')?>",
                data:"filename="+filename,
                success:function(hasilajax){
                    $('#list_ku').html(hasilajax);
                }
            });
            
        }
</script>
<!-- Content Header (Page header) -->
<section class="content-header">
      <h1>
       Import Data
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="https://sinarsentosaprimatama.com/stoks/delete_stok"><i class="fa fa-dashboard"></i> Reset Stock</a></li>
      
      </ol>
    </section>


<!-- column -->
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
            </div>
            <form action="<?php echo base_url('Import_data/aksi')?>" method="post" class="form-horizontal" enctype="multipart/form-data">
            <div class="box-body"> 
                <div class="form-group">
                    <label for="varchar" class="col-md-2">Upload File .xls</label>
                    <div class="col-sm-4">
                        <input type="file" name="filename" id="filename"  class="form-control">
                    </div>
                </div>
            </div>    
        <div class="box-footer">
            <button type="submit" class="btn btn-flat bg-maroon" onClick="load_data_temp();"><span class="fa fa-cogs"></span> Generated</button> 
        </div>
        <div class="box box-danger">
            <div class="box-header with-border">
                <h4>Status</h4>
            </div>
           <div class="box-body">
           <div class="col-sm-12">
                     <div id="list_ku" class="table-responsive">
                     a
                </div>
           </div>
        </div>
</form>
</div>
</div>
</div>
