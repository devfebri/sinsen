              <table id="example" class="table table-bordered table-hover">

                <thead>

                  <tr>              

                    <th>No Mesin</th>                                  
                    <th>No Rangka</th>                                  
                    <th>Tipe</th>
                    <th>Warna</th>                                  
                    <th style="text-align: center;width: 35% ">Part</th>
                    <th>Aksi</th>      

                  </tr>

                </thead>
              <?php
              $nosin_ins=null;
              if ($cart = $this->cart->contents())

                {

             ?>
                <tbody>            

                <?php 

                $no = 1;

                foreach ($cart as $isi=> $row) {
                  $nosin[$no] = "'$row[no_mesin]'"; 
                  echo "

                  <tr>
                    <td>$row[name]</td>                    
                    <td>$row[no_mesin]</td>                    
                    <td>$row[tipe]</td>                    
                    <td>$row[warna]</td>" ?>                     
                  <td align="center">
                    <table class="table table-bordered">
                      <tr>
                        <td>Need Parts</td>
                        <td colspan="2" style="width: 100%">
                          <select  id="need_parts_<?= $row['no_mesin']?>" name="need_parts_<?= $row['no_mesin']?>" onchange="setNeedParts('<?= $row['no_mesin'] ?>')">
                            <option value="">--choose--</option>
                            <option value="yes"<?= isset($_SESSION[$row['no_mesin']])?$_SESSION[$row['no_mesin']]=='yes'?'selected':'':'' ?> >Yes</option>
                            <option value="no"<?= isset($_SESSION[$row['no_mesin']])?$_SESSION[$row['no_mesin']]=='no'?'selected':'':'' ?> >No</option>
                          </select></td>
                      </tr>
                      <tr>
                        <td><b>Nomor Parts</b></td>
                        <td colspan="2"><b>Kuantitas Part</b></td>
                      </tr>
                      <tr>
                        <td style="width: 70%"><input onclick="showModalPart('<?= $row['no_mesin'] ?>')" readonly id="id_part_<?=$row['no_mesin']?>" style="width: 90%" class="form-control isi" type="text"></td>
                        <td style="width: 25%"><input id="qty_part_<?=$row['no_mesin']?>" style="width: 90%" class="form-control isi" type="text"></td>
                        <td><button type="button" onclick="addPart('<?= $row['no_mesin'] ?>')" class="btn btn-flat btn-primary btn-xs"><i class="fa fa-plus"></i></button></td>
                      </tr>
                      <?php if($part_add = $this->part_add->get_content()) { ?>
                        <?php foreach ($part_add as $prt): 
                          if ($prt['no_mesin']==$row['no_mesin']) { ?>
                            <tr>
                              <td><?= $prt['id_part'] ?></td>
                              <td><?= $prt['qty'] ?></td>
                              <td><button data-toggle="tooltip" title="Delete" class="btn btn-danger btn-xs" type="button" onclick="delPart('<?= $prt['rowid']?>')"><i class="fa fa-trash" ></i></button></td>
                            </tr>  
                         <?php }
                        ?>
                        <?php endforeach ?>
                      <?php } ?>
                    </table>
                  </td>
                    <td width='5%'>

                    <button title="Hapus Data"

                      class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button"       

                      onclick="hapusNosin('<?php echo $row['rowid']; ?>')"></button>

                    </td>

                  </tr>

                  <?php

                  $no++;

                }
               $nosin_ins = implode(',', $nosin)
                ?>

                </tbody>

              </table>

              <?php } ?>