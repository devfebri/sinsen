
<?php  $row = $dt_po->row();      $id_dealer = $row->id_dealer;
       $sql = $this->db->query("SELECT * FROM ms_dealer WHERE ms_dealer.id_dealer = '$id_dealer'")->row();  ?>
<form class="form-horizontal" action="dealer/po_d/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_po ?>" />
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.PO</label>
                  <div class="col-sm-4">
                    <input type="text" required value="<?php echo $row->id_po ?>" readonly class="form-control"  id="id_po" placeholder="No.PO" name="id_po">
                    <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                    <input type="hidden" id="mode" value="detail">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis PO</label>
                  <div class="col-sm-4">
                    <input id="jenis_po" name="jenis_po" onchange="cek_jenis()" readonly value="<?php echo $row->jenis_po ?>" class="form-control">                    
                  </div>
                </div>                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $sql->nama_dealer ?>" name="delaer">
                    <input type="hidden" name="id_dealer" value="<?php echo $sql->id_dealer ?>">
                  </div>
                </div>                                                                                                                                                         
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bulan" readonly id="bulan">
                      <option value="<?php echo $row->bulan ?>"><?php echo $row->bulan ?></option>                                            
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-4">
                    <select class="form-control" readonly name="tahun" id="tahun">
                      <option value="<?php echo $row->tahun ?>"><?php echo $row->tahun ?></option>                      
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->ket ?>" readonly class="form-control" id="inputEmail3" name="ket">
                  </div>
                </div>
                <hr>                
                <div class="form-group">
                                  
                  
                  <span id="tampil_po"></span>                                                                                  
                  
                  
                </div>                                                 
              </div><!-- /.box-body -->             
            </form>
            <p align="center">
                        <a data-toggle="tooltip" title="Send to MD" onclick="return confirm('Are you sure to send this data?')" class="btn btn-info btn-flat" href="dealer/po_d/send?id=<?php echo $row->id_po ?>"><i class="fa fa-send"></i> <b>Approve & Send to MD</b></a>
            </p>
<script type="text/javascript">
  $(document).ready(function(){
  $("#tampil_po").show();
  var id_po = document.getElementById("id_po").value;   
  var mode = document.getElementById("mode").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_po="+id_po+"&mode="+mode;                           
      <?php if ($row->jenis_po =='PO Additional') { ?>  xhr.open("POST", "dealer/po_d/t_po_add", true);  <?php } ?>
      <?php if ($row->jenis_po =='PO Reguler') { ?>  xhr.open("POST", "dealer/po_d/t_po_reg", true);  <?php } ?>
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_po").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
  })
</script>