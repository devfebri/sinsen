<?php

class H3_md_pencatatan_hadiah_sales_campaign_model extends Honda_Model{

    protected $table = 'tr_h3_md_pencatatan_hadiah_sales_campaign';

    public function insert($data){
        $data['created_by'] = $this->session->userdata('id_user');
        $data['created_at'] = date('Y-m-d H:i:s', time());

        parent::insert($data);
    }

    public function insert_batch($batch){
        $batch = array_map(function($data){
            if(!isset($data['created_at'])){
                $data['created_at'] = date('Y-m-d H:i:s', time());
            }

            if(!isset($data['created_by'])){
                $data['created_by'] = $this->session->userdata('id_user');
            }
            return $data;
        }, $batch);
        parent::insert_batch($batch);
    }

    public function generate_pencatatan_hadiah($id_campaign){
        $dealer_yang_mendapatkan_poin = $this->db
        ->select('DISTINCT(ppsc.id_dealer) as id_dealer')
        ->from('tr_h3_md_pencatatan_poin_sales_campaign as ppsc')
        ->get()->result_array();

        foreach ($dealer_yang_mendapatkan_poin as $dealer) {
            $list_hadiah = $this->db
            ->from('ms_h3_md_sales_campaign_detail_hadiah as scdh')
            ->where('scdh.id_campaign', $id_campaign)
            ->where('scdh.voucher_rupiah', 1)
            ->order_by('scdh.jumlah_poin', 'desc')
            ->get()->result_array();

            $poin_campaign = $this->db
            ->from('tr_h3_md_pencatatan_poin_sales_campaign as ppsc')
            ->wherE('ppsc.id_dealer', $dealer['id_dealer'])
            ->where('ppsc.id_campaign', $id_campaign)
            ->get()->result_array();

            $sisa_poin = array_sum(array_map(function($row){
                return floatval($row['poin']);
            }, $poin_campaign));
            $list_pencatatan_hadiah_sales_campaign = [];
            foreach ($list_hadiah as $row) {
                while($sisa_poin >= floatval($row['jumlah_poin'])){
                    $list_pencatatan_hadiah_sales_campaign[] = [
                        'id_campaign' => $row['id_campaign'],
                        'id_hadiah' => $row['id'],
                        'voucher_hadiah' => floatval($row['nama_hadiah']),
                        'id_dealer' => $dealer['id_dealer']
                    ];
                    $sisa_poin -= floatval($row['jumlah_poin']);
                }	
            }
            
            $this->insert_batch($list_pencatatan_hadiah_sales_campaign);
        }
    }
}
