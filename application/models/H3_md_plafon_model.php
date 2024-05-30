<?php

class H3_md_plafon_model extends CI_Model{

    public function get_plafon_sementara($id_sales_order){
        $data = $this->db
		->select('plafon.nilai_penambahan_sementara as plafon_sementara')
		->from('ms_h3_md_plafon_sales_orders as pso')
		->join('ms_h3_md_plafon as plafon', 'plafon.id = pso.id_plafon')
		->where('pso.id_sales_order', $id_sales_order)
		->where('plafon.status', 'Approved by Pimpinan')
		->order_by('plafon.created_at', 'desc')
		->limit(1)
        ->get()->row()
        ;

        return $data != null ? $data->plafon_sementara : 0;
    }

    public function get_plafon($id_dealer, $gimmick = 0, $kategori_po = null){
        $this->db
        ->select('d.plafon_h3 as plafon')
        ->from('ms_dealer as d');

        if($gimmick == 1){
            $this->db->limit(1);
            $this->db->where('d.tipe_plafon_h3', 'gimmick');
        }else if($kategori_po == 'KPB'){
            $this->db->limit(1);
            $this->db->where('d.tipe_plafon_h3', 'kpb');         
        }else{
            $this->db->where('d.id_dealer', $id_dealer);
        }

        $plafon_dealer = $this->db->get()->row();

        return $plafon_dealer != null ? floatval($plafon_dealer->plafon) : 0;
    }

    public function get_plafon_terpakai($id_dealer, $gimmick = 0, $kategori_po = null, $sql = false){
        $this->db
        ->select('IFNULL( SUM((ar.total_amount - ar.sudah_dibayar)), 0) as plafon_terpakai')
        ->from('tr_h3_md_ar_part as ar')
        ->join('ms_dealer as d', 'd.id_dealer = ar.id_dealer')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = ar.referensi')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
        ;

        if ($sql) {
            if($gimmick == 1){
                $this->db->limit(1);
                $this->db->where('so.gimmick', 1);
                $this->db->where('so.kategori_po !=', 'KPB');  
                $this->db->where('ar.lunas', 0);        
            }else if($kategori_po == 'KPB'){
                $this->db->limit(1);
                $this->db->where('so.gimmick', 0);
                $this->db->where('so.kategori_po', 'KPB');     
                $this->db->where('ar.lunas', 0);       
            }else{
                $this->db->where('so.gimmick', 0);
                $this->db->where('so.kategori_po !=', 'KPB');
                $this->db->where("ar.id_dealer = {$id_dealer}");  
                $this->db->where('ar.lunas', 0); 
            }
            return $this->db->get_compiled_select();
        }else{
            if($gimmick == 1){
                $this->db->limit(1);
                $this->db->where('so.gimmick', 1);
                $this->db->where('so.kategori_po !=', 'KPB');   
                $this->db->where('ar.lunas', 0);   
            }else if($kategori_po == 'KPB'){
                $this->db->limit(1);
                $this->db->where('so.gimmick', 0);
                $this->db->where('so.kategori_po', 'KPB');   
                $this->db->where('ar.lunas', 0);         
            }else{
                $this->db->where('so.gimmick', 0);
                $this->db->where('so.kategori_po !=', 'KPB');
                $this->db->where('ar.id_dealer', $id_dealer);  
                $this->db->where('ar.lunas', 0); 
            }
            $data = $this->db->get()->row();
        }
        
        return $data != null ? floatval($data->plafon_terpakai) : 0;
    }

    public function get_plafon_booking($id_dealer, $gimmick = 0, $kategori_po = null, $sql = false){
        $this->db
		->select('IFNULL( SUM(do_plafon_booking.total), 0 ) as plafon_booking')
		->from('tr_h3_md_sales_order as so_plafon_booking')
        ->join('tr_h3_md_do_sales_order as do_plafon_booking', 'do_plafon_booking.id_sales_order = so_plafon_booking.id_sales_order')
        ->join('ms_dealer as d_plafon_booking', 'd_plafon_booking.id_dealer = so_plafon_booking.id_dealer')
        ->group_start()
        // ->where('do_plafon_booking.status', 'Approved')
        // ->or_where('do_plafon_booking.status', 'Picking List')
        // ->or_where('do_plafon_booking.status', 'Closed Scan')
        // ->or_where('do_plafon_booking.status', 'Proses Scan') 
        ->where_in('do_plafon_booking.status', array('Approved','Picking List','Closed Scan','Proses Scan','On Process'))
        ->group_end()
        ->where('do_plafon_booking.sudah_create_faktur', 0)
        ;

        if ($sql) {
            if($gimmick == 1){
                $this->db->limit(1);
                $this->db->where('so_plafon_booking.gimmick', 1);
                $this->db->where('so_plafon_booking.kategori_po !=', 'KPB');
            }else if($kategori_po == 'KPB'){
                $this->db->limit(1);
                $this->db->where('so_plafon_booking.gimmick', 0);
                $this->db->where('so_plafon_booking.kategori_po', 'KPB');         
            }else{
                $this->db->where('so_plafon_booking.gimmick', 0);
                $this->db->where('so_plafon_booking.kategori_po !=', 'KPB');
                $this->db->where("so_plafon_booking.id_dealer = {$id_dealer}");
            }
            return $this->db->get_compiled_select();
        }else{
            if($gimmick == 1){
                $this->db->limit(1);
                $this->db->where('so_plafon_booking.gimmick', 1);
                $this->db->where('so_plafon_booking.kategori_po !=', 'KPB');
            }else if($kategori_po == 'KPB'){
                $this->db->limit(1);
                $this->db->where('so_plafon_booking.gimmick', 0);
                $this->db->where('so_plafon_booking.kategori_po', 'KPB');         
            }else{
                $this->db->where('so_plafon_booking.gimmick', 0);
                $this->db->where('so_plafon_booking.kategori_po !=', 'KPB');
                $this->db->where('so_plafon_booking.id_dealer', $id_dealer);
            }
            $data = $this->db->get()->row();
        }
        return $data != null ? floatval($data->plafon_booking) : 0;
    }
}
