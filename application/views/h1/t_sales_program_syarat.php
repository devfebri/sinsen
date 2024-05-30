<table class="table tabl-bordered table-condensed">
	<thead>
		<th>Syarat dan Ketentuan</th>
		<th style="text-align: center;width: 5%">Aksi</th>
	</thead>
	<tbody>
		<?php 
      $in_data = array();
      foreach ($sp_syarat->result() as $syrt): 
        array_push($in_data,$syrt->syarat_ketentuan);
      ?>
			<tr>
				<td><?php echo $syrt->syarat_ketentuan ?></td>
				<td><button type="button" class="btn btn-danger btn-sm btn-flat" onclick="delSyarat(<?php echo  $syrt->id ?>)"><i class="fa fa-trash"></i></button></td>
			</tr>
		<?php endforeach ?>
	</tbody>
	<tfoot>

    <?php 
      foreach ($dokument_syarat->result() as $syrt): 
        if (!in_array($syrt->short_name, $in_data)) {
    ?>
			<tr>
				<td><?php echo $syrt->short_name ?></td>
				<td><input type="checkbox" onClick="test('<?php echo $syrt->short_name?>')"></td>
			</tr>
		<?php } endforeach ?>

		<tr>
			<td>
        <textarea class="form-control syarat_ketentuan" id="textarea-full" name="syarat_ketentuan" rows="2"></textarea>
        <input type="hidden" id="id_program_md" value="<?php echo $id_program_md ?>">
      </td>
			<td style="vertical-align: middle;text-align: center;"><button class="btn btn-primary btn-sm btn-flat" type="button" onclick="addSyarat()"><i class="fa fa-plus"></i></button></td>
		</tr>
	</tfoot>
</table>


<script type="text/javascript">
  function test(syarat){
    var id_program_md = $("#id_program_md").val();
    // console.log(syarat);
    $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/sales_program/save_syarat');?>",
               type:"POST",
               data:"syarat_ketentuan="+syarat+"&id_program_md="+id_program_md,
               cache:false,
               success:function(data){
                  $('#loading-status').hide();
                  
                  if(data=="nihil"){
                    getSyarat();    
                  }else{
                    alert(data);          
                  }    
               },
               statusCode: {
            500: function() {
              $('#loading-status').hide();
              alert("Something Wen't Wrong");
            }
          }
      });

  }
	function addSyarat()
  {
      var syarat_ketentuan = $(".syarat_ketentuan").val();
      var id_program_md = $("#id_program_md").val();
      $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/sales_program/save_syarat');?>",
               type:"POST",
               data:"syarat_ketentuan="+syarat_ketentuan+"&id_program_md="+id_program_md,
               cache:false,
               success:function(data){
                  $('#loading-status').hide();
                  
                  if(data=="nihil"){
                    getSyarat();    
                  }else{
                    alert(data);          
                  }    
               },
               statusCode: {
            500: function() {
              $('#loading-status').hide();
              alert("Something Wen't Wrong");
            }
          }
      });
  }

    function delSyarat(id)
  {
      $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/sales_program/delete_syarat');?>",
               type:"POST",
               data:"id="+id,
               cache:false,
               success:function(data){
                  $('#loading-status').hide();
                  
                  if(data=="nihil"){
                    getSyarat();    
                  }else{
                    alert(data);          
                  }    
               },
               statusCode: {
            500: function() {
              $('#loading-status').hide();
              alert("Something Wen't Wrong");
            }
          }
          });
  }
</script>