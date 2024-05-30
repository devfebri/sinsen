<?php

class h3_analisis_ranking_model extends Honda_Model {

    protected $table = 'ms_h3_analisis_ranking';

    public function get_analisis_ranking($id_dealer, $id_part){
        $data = $this->db
        ->select('ar.w1')
        ->select('ar.w2')
        ->select('ar.w3')
        ->select('ar.w4')
        ->select('ar.w5')
        ->select('ar.w6')
        ->select('ar.avg_six_weeks')
        ->select('ar.akumulasi_qty')
        ->select('ar.akumulasi_persen')
        ->select('ar.stock_days')
        ->select('ar.suggested_order')
        ->select('ar.adjusted_order')
        ->select('ar.rank')
        ->select('ar.status')
        ->from('ms_h3_analisis_ranking as ar')
        ->where('ar.id_dealer', $id_dealer)
        ->where('ar.id_part', $id_part)
        ->limit(1)
        ->get()->row_array();

        return [
            'w1' => $data != null ? $data['w1'] : 0,
            'w2' => $data != null ? $data['w2'] : 0,
            'w3' => $data != null ? $data['w3'] : 0,
            'w4' => $data != null ? $data['w4'] : 0,
            'w5' => $data != null ? $data['w5'] : 0,
            'w6' => $data != null ? $data['w6'] : 0,
            'avg_six_weeks' => $data != null ? $data['avg_six_weeks'] : 0,
            'akumulasi_qty' => $data != null ? $data['akumulasi_qty'] : 0,
            'akumulasi_persen' => $data != null ? $data['akumulasi_persen'] : 0,
            'stock_days' => $data != null ? $data['stock_days'] : 0,
            'suggested_order' => $data != null ? $data['suggested_order'] : 0,
            'adjusted_order' => $data != null ? $data['adjusted_order'] : 0,
            'rank' => $data != null ? $data['rank'] : null,
            'status' => $data != null ? $data['status'] : 0,
        ];
    }
   
}