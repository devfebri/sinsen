<table class="table" id='modalexample2'>
    <thead>
        <tr>
            <th>FIFO</th>
            <th>Tipe</th>
            <th>Kode Part</th>
            <th>Nama Part</th>
            <th>Serial Number</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody> 
      <?
        $no = 1;
          foreach ($dt_serial_number->result() as $ve2) {
            echo "
            <tr>
              <td>$ve2->fifo</td>
              <td>$ve2->serial_number</td>
              <td>$ve2->part_id</td>            
              <td>$ve2->part_desc</td>
              <td>$ve2->serial_number</td>
              ";
              ?>                         
              <td class="center">
                <button title="Choose" onclick="chooseEV('<?php echo $ve2->serial_number; ?>')" class="btn btn-flat btn-success btn-sm btn_get"><i class="fa fa-check"></i></button>                 
              </td>
            </tr>
            <?php
            $no++;
          } ?>
        </tr>
    </tbody>
</table>
 
