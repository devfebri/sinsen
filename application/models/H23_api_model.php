<?php

class H23_api_model extends CI_Model{
    
  public function getDataAmount($filter= null) {
         // send_json($filter);
      
        $where = "GROUP BY a.id_part";
        $group_by = '';
        $id= $filter['id_dealer'];
        $nama_part ="b.nama_part";
        $stock ="a.stock";
        $idPart ="a.id_part";
        
    if ($filter != null) {
      if (isset($filter['id_part'])) {
        $where .= " AND a.id_part='{$filter['id_part']}' ";
      }
       if (isset($filter['id_part'])) {
        $where .= " AND b.nama_part='{$filter['nama_part']}' ";
      }
      if (isset($filter['stock'])) {
        $where .= " AND a.stock='{$filter['stock']}' ";
      }
      if (isset($filter['search'])) {
            if ($filter['search'] != '') {
              $search = $filter['search'];
              $where .= " AND ($nama_part LIKE '%$search%'
                                OR $stock LIKE '%$search%'
                                OR $idPart LIKE '%$search%'
                                ) 
                ";
                }
            }
        if (isset($filter['order'])) {
            if ($filter['order'] != '') {
              $order = $filter['order'];
              $order_column = $filter['order_column'];
              $order_clm  = $order_column[$order[0]['column']];
              $order_by   = $order[0]['dir'];
              $where .= " ORDER BY $order_clm $order_by ";
            } else {
              $where .= " ORDER BY a.id_part ASC ";
            }
          } else {
            $where .= " ORDER BY a.id_part ASC ";
          }
          if (isset($filter['limit'])) {
            if ($filter['limit'] != '') {
              $where .= ' ' . $filter['limit'];
            }
          }
        }
        
        return $this->db->query("SELECT a.id_part,b.nama_part,b.harga_dealer_user as harga,a.stock from ms_h3_dealer_stock a join ms_part b on a.id_part=b.id_part where a.stock > 0 and a.id_dealer ='$id'
        or a.id_part in(select d.id_part from tr_h3_dealer_sales_order_parts d join tr_h3_dealer_sales_order e on d.nomor_so=e.nomor_so where e.id_dealer ='$id') $where 
        ");
    }
    
      public function getDataPenerimaan($filter= null) {
         // send_json($filter);
      
        $group_by = '';
        $id= $filter['id_dealer'];
        $where = "where id_dealer ='$id'";
        $igr ="id_good_receipt";
        $nomor_po ="nomor_po";
        $id_reference ="id_reference";
        
    if ($filter != null) {
      if (isset($filter['id_good_receipt'])) {
        $where .= " AND id_good_receipt ='{$filter['id_good_receipt']}' ";
      }
       if (isset($filter['nomor_po'])) {
        $where .= " AND nomor_po ='{$filter['nomor_po']}'";
      }
      if (isset($filter['id_reference'])) {
        $where .= " AND id_reference ='{$filter['id_reference']}' ";
      }
      if (isset($filter['tanggal_receipt'])) {
        $where .= " AND tanggal_receipt ='{$filter['tanggal_receipt']}' ";
      }
      if (isset($filter['search'])) {
            if ($filter['search'] != '') {
              $search = $filter['search'];
              $where .= " AND ($igr LIKE '%$search%'
                                OR  $nomor_po LIKE '%$search%'
                             
                                ) 
                ";
                }
            }
        if (isset($filter['order'])) {
            if ($filter['order'] != '') {
              $order = $filter['order'];
              $order_column = $filter['order_column'];
              $order_clm  = $order_column[$order[0]['column']];
              $order_by   = $order[0]['dir'];
              $where .= " ORDER BY $order_clm $order_by ";
            } else {
              $where .= " ORDER BY tanggal_receipt DESC ";
            }
          } else {
            $where .= " ORDER BY tanggal_receipt DESC ";
          }
          if (isset($filter['limit'])) {
            if ($filter['limit'] != '') {
              $where .= ' ' . $filter['limit'];
            }
          }
        }
        
        return $this->db->query("SELECT * FROM tr_h3_dealer_good_receipt $where");
    } 
}




?>