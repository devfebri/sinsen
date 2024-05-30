<?php

class H3_md_ar_part_model extends Honda_Model{

    protected $table = 'tr_h3_md_ar_part';

    public function insert($data){
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
    }

    public function piutang_dealer($id_dealer, $gimmick = false, $kpb = false){
        $dealer = $this->db
        ->select('d.tipe_plafon_h3')
        ->from('ms_dealer as d')
        ->where('d.id_dealer', $id_dealer)
        ->get()->row_array();

        if($dealer == null) throw new Exception(sprintf('Dealer tidak ditemukan [%s]', $id_dealer));

        $this->db
        ->select('ar.referensi')
        ->select('date_format(ar.tanggal_transaksi, "%d/%m/%Y") as tanggal_transaksi')
        ->select('date_format(ar.tanggal_jatuh_tempo, "%d/%m/%Y") as tanggal_jatuh_tempo')
        ->select('(ar.total_amount - ar.sudah_dibayar) as sisa_piutang')
        ->select('"-" as status_pembayaran')
        ->from('tr_h3_md_ar_part as ar')
        ->where('ar.lunas', 0);

        if($dealer['tipe_plafon_h3'] == 'gimmick'){
            $this->db->where('ar.gimmick', 1);
            $this->db->where('ar.kpb', 0);
        }elseif($dealer['tipe_plafon_h3'] == 'kpb' OR $kpb){
            $this->db->where('ar.gimmick', 0);
            $this->db->where('ar.kpb', 1);
        }else{
            $this->db->where('ar.gimmick', 0);
            $this->db->where('ar.kpb', 0);
            $this->db->where('ar.id_dealer', $id_dealer);
        }

        $piutang_dealer = $this->db->get()->result_array();

        $piutang_dealer = array_map(function($row){
            $list_bg = $this->db
            ->select('pb.nama_bank_bg')
            ->select('pb.nomor_bg')
            ->select('date_format(pb.tanggal_jatuh_tempo_bg, "%d/%m/%Y") as tanggal_jatuh_tempo_bg')
            ->select('pb.nominal_bg')
            ->from('tr_h3_md_penerimaan_pembayaran_item as pbi')
            ->join('tr_h3_md_penerimaan_pembayaran as pb', 'pb.id_penerimaan_pembayaran = pbi.id_penerimaan_pembayaran')
            ->where('pbi.referensi', $row['referensi'])
            ->where('pb.jenis_pembayaran', 'BG')
            ->order_by('pb.created_at', 'desc')
            ->get()->result_array();

            $row['list_bg'] = $list_bg;

            return $row;
        }, $piutang_dealer);

		return $piutang_dealer;
	}

    public function dilunaskan($referensi){
        $ar_part = $this->db
        ->select('ar.referensi')
        ->select('ar.tipe_referensi')
        ->select('ar.total_amount')
        ->select('ar.sudah_dibayar')
        ->from('tr_h3_md_ar_part as ar')
        ->where('ar.referensi', $referensi)
        ->limit(1)
        ->get()->row_array();

        if($ar_part != null){
            if(
                floatval($ar_part['total_amount']) == floatval($ar_part['sudah_dibayar'])
            ){
                $this->db
                ->set('ar.lunas', 1)
                ->where('ar.referensi', $referensi)
                ->update('tr_h3_md_ar_part as ar');

                if($ar_part['tipe_referensi'] == 'faktur_penjualan'){
                    $this->db
                    ->set('ps.faktur_lunas', 1)
                    ->where('ps.no_faktur', $referensi)
                    ->update('tr_h3_md_packing_sheet as ps');
                }
            }
        }
    }
}
