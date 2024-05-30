<table class="table table-bordered" style="width: 50%">
	<?php 
	if (isset($_SESSION['type'])) {
				  $this->db->select('*');
	        	  $this->db->from('ms_tipe_kendaraan');
            	$this->db->where_in('ms_tipe_kendaraan.id_tipe_kendaraan',$_SESSION['type']);
	        	  $tipe = $this->db->get()->result();
	    foreach ($tipe as $tipe) { ?>
	    <tr>
	    	<td><?php echo $tipe->id_tipe_kendaraan ?></td>
	    	<td><?php echo $tipe->tipe_ahm ?></td>
	    	<td><button class="btn btn-danger btn-xs delete_type" type="button" tipe="<?php echo $tipe->id_tipe_kendaraan ?>" ><i class="fa fa-trash"></i></button></td>
	    </tr>

	  <?php  
	    }
	} ?>
</table>