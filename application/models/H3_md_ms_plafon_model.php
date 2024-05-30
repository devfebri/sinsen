<?php

class H3_md_ms_plafon_model extends Honda_Model{

    protected $table = 'ms_h3_md_plafon';

    public function insert($data){
        $data['created_by'] = $this->session->userdata('id_user');
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['status'] = isset($data['status']) ? $data['status'] : 'Open';
        parent::insert($data);
    }

    public function update($data, $condition){
        $data['updated_by'] = $this->session->userdata('id_user');
        $data['updated_at'] = date('Y-m-d H:i:s', time());
        parent::update($data, $condition);
    }

    public function get_plafon($id_dealer, $gimmick = 0, $kategori_po = null, $id_sales_order = null){
        return $this->get_plafon_permanent($id_dealer, $gimmick, $kategori_po) + $this->get_plafon_sementara($id_sales_order); 
    }

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

    public function get_plafon_permanent($id_dealer, $gimmick = 0, $kategori_po = null){
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
        ->join('tr_h3_md_picking_list as pl', 'pl.id = ps.id_picking_list_int')
        ->join('tr_h3_md_do_sales_order as do', 'do.id = pl.id_ref_int')
        ->join('tr_h3_md_sales_order as so', 'so.id = do.id_sales_order_int')
        ;

        if ($sql) {
            if($gimmick == 1){
                $this->db->limit(1);
                $this->db->where('so.gimmick', 1);
                $this->db->where('so.kategori_po !=', 'KPB');         
            }else if($kategori_po == 'KPB'){
                $this->db->limit(1);
                $this->db->where('so.gimmick', 0);
                $this->db->where('so.kategori_po', 'KPB');         
            }else{
                $this->db->where('so.gimmick', 0);
                $this->db->where('so.kategori_po !=', 'KPB');
                $this->db->where("ar.id_dealer = {$id_dealer}");
            }
            return $this->db->get_compiled_select();
        }else{
            if($gimmick == 1){
                $this->db->limit(1);
                $this->db->where('so.gimmick', 1);
                $this->db->where('so.kategori_po !=', 'KPB');   
            }else if($kategori_po == 'KPB'){
                $this->db->limit(1);
                $this->db->where('so.gimmick', 0);
                $this->db->where('so.kategori_po', 'KPB');         
            }else{
                $this->db->where('so.gimmick', 0);
                $this->db->where('so.kategori_po !=', 'KPB');
                $this->db->where('ar.id_dealer', $id_dealer);
            }
            $data = $this->db->get()->row();
        }
        
        return $data != null ? floatval($data->plafon_terpakai) : 0;
    }

    public function get_plafon_booking($id_dealer, $gimmick = 0, $kategori_po = null, $sql = false){
        $this->db
		->select('IFNULL( SUM(do_plafon_booking.total), 0 ) as plafon_booking', false)
        // ->select('so_plafon_booking.id_sales_order')
        // ->select('do_plafon_booking.id_do_sales_order')
        // ->select('do_plafon_booking.total')
        // ->select('do_plafon_booking.status')
		->from('tr_h3_md_sales_order as so_plafon_booking')
        ->join('tr_h3_md_do_sales_order as do_plafon_booking', 'do_plafon_booking.id_sales_order_int = so_plafon_booking.id')
        ->join('ms_dealer as d_plafon_booking', 'd_plafon_booking.id_dealer = so_plafon_booking.id_dealer')
        ->group_start()
        ->where('do_plafon_booking.status', 'Approved')
        ->or_where('do_plafon_booking.status', 'Picking List')
        ->or_where('do_plafon_booking.status', 'Closed Scan')
        ->or_where('do_plafon_booking.status', 'Proses Scan')
        ->group_end()
        ->where('do_plafon_booking.sudah_create_faktur', 0)
        ;

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
            $this->db->where('so_plafon_booking.id_dealer', $id_dealer, !$sql);
        }

        if ($sql) {
            return $this->db->get_compiled_select();
        }else{
            $data = $this->db->get()->row();
            return $data != null ? floatval($data->plafon_booking) : 0;
        }
    }

    public function get_faktur($id_dealer, $dengan_rincian_pembayaran = false, $hanya_bg = false){
        $dealer = $this->db
		->select('d.id_dealer')
		->select('d.tipe_plafon_h3')
		->from('ms_dealer as d')
		->where('d.id_dealer', $id_dealer)
		->get()->row_array();

		if($dealer == null) send_json([]);

		$this->db
		->select('ar.referensi as no_faktur')
		->select('ar.tanggal_jatuh_tempo as tgl_jatuh_tempo')
		->select('(ar.total_amount - ar.sudah_dibayar) as nilai_faktur', false)
		->select('so.produk')
		->from('tr_h3_md_ar_part as ar')
		->join('tr_h3_md_packing_sheet as ps', '(ps.no_faktur = ar.referensi)')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
		->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
		->where('ar.tipe_referensi', 'faktur_penjualan')
		->where('ar.lunas', 0)
		->order_by('ar.tanggal_jatuh_tempo', 'asc')
		;

		if($dealer['tipe_plafon_h3'] == 'gimmick'){
			$this->db->where('ar.gimmick', 1);
		}else if($dealer['tipe_plafon_h3'] == 'kpb'){
			$this->db->where('ar.kpb', 1);
		}else{
			$this->db->where('ar.id_dealer', $dealer['id_dealer']);
			$this->db->where('ar.kpb', 0);
			$this->db->where('ar.gimmick', 0);

		}

		$data = array_map(function($row) use ($dengan_rincian_pembayaran, $hanya_bg){
			$row['nilai_faktur'] = floatval($row['nilai_faktur']);

            if($dengan_rincian_pembayaran){
                $row['rincian_pembayaran'] = $this->get_rincian_pembayaran($row['no_faktur'], $hanya_bg);
            }

			return $row;
		}, $this->db->get()->result_array());

        return $data;
    }

    public function get_rincian_pembayaran($referensi, $hanya_bg = false){
        $data = $this->db
		->select('pp.jenis_pembayaran')
		->select('pp.nomor_bg as nomor_bg')
		->select('pp.tanggal_jatuh_tempo_bg as tanggal_jatuh_tempo_bg')
		->select('
			case
				when pp.jenis_pembayaran = "Cash" then pp.nominal_cash
				when pp.jenis_pembayaran = "BG" then pp.nominal_bg
				when pp.jenis_pembayaran = "Transfer" then pp.nominal_transfer
			end as nominal
		', false)
		->from('tr_h3_md_penerimaan_pembayaran_item as ppi')
		->join('tr_h3_md_penerimaan_pembayaran as pp', 'pp.id_penerimaan_pembayaran = ppi.id_penerimaan_pembayaran')
		->where('ppi.referensi', $referensi)
        ->where('
            case
                when pp.jenis_pembayaran = "BG" then (pp.status_bg IS NULL OR pp.status_bg = "Cair")
                else true
            end 
        ', null, false)
		->order_by('pp.created_at', 'desc');

        if($hanya_bg){
            $this->db->where('pp.jenis_pembayaran', 'BG');
        }

        return $this->db->get()->result_array();
    }

    public function set_detail_plafon($id){
        $plafon = $this->find($id);

        if($plafon == null) return;

        $data = json_encode($this->get_faktur(22, true));
        $this->db
        ->set('faktur_json_plafon', $data)
        ->where('id', $id)
        ->update($this->table);
    }
}
