<?php

defined('BASEPATH') or exit('No direct script access allowed');

class PromoProgram extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $this->db
        ->select('prmi.id_part')
        ->select('p.nama_part')
        ->select('concat("Rp ", format(p.harga_dealer_user, 0, "ID_id") ) as het')
        ->select('prm.id_promo')
        ->select('prm.tipe_promo')
        ->select('prm.hadiah_per_item')
        ->select('prm.nama')
        ->select('prm.minimal_pembelian')
        ->select('prm.mekanisme_promo')
        ->select('date_format(prm.start_date, "%d/%m/%Y") as start_date')
        ->select('date_format(prm.end_date, "%d/%m/%Y") as end_date')
        ->from('ms_h3_promo_dealer as prm')
        ->join('ms_h3_promo_dealer_items as prmi', 'prm.id_promo = prmi.id_promo')
        ->join('ms_part as p', 'p.id_part = prmi.id_part')
        ->group_start()
        ->where("'{$this->input->get('id_part')}' in (prmi.id_part)")
        ->or_where("'{$this->input->get('kelompok_part')}' = prmi.kelompok_part")
        ->group_end()
        ->where('prm.start_date <= date(now())')
        ->where('prm.end_date >= date(now())')
        ->group_by('prm.id_promo')
        ->order_by('prm.created_at', 'desc')
        ;

        $data = [];
        foreach ($this->db->get()->result_array() as $each) {
            $sub_array = $each;

            $items = $this->db
            ->select('
                case
                    when prmi.tipe_disc = "Percentage" then concat(prmi.disc_value, "%")
                    when prmi.tipe_disc = "Value" then concat("Rp ", prmi.disc_value)
                    else prmi.disc_value
                end as promo_value
            ', false)
            ->from('ms_h3_promo_dealer_items as prmi')
            ->where('prmi.id_promo', $each['id_promo'])
            ->group_start()
            ->where("'{$this->input->get('id_part')}' in (prmi.id_part)")
            ->or_where("'{$this->input->get('kelompok_part')}' = prmi.kelompok_part")
            ->group_end()
            ->get()->result_array();

            $promo_value = '';
            foreach ($items as $item) {
                $promo_value .= "-" . $item['promo_value'];
            }

            $sub_array['promo_value'] = substr($promo_value, 1);

            if($each['hadiah_per_item'] == 0){
                $this->db
                ->select('h.*')
                ->select('p.nama_part')
                ->from('ms_h3_promo_dealer_hadiah as h')
                ->join('ms_part as p', 'p.id_part = h.id_part', 'left')
                ->where('h.id_promo', $each['id_promo'])
                ->where('h.id_items', null);

                $gift = '';
                foreach ($this->db->get()->result_array() as $e) {
                    if($e['part_ahass'] == 0){
                        $gift .= ", {$e['qty_hadiah']} {$e['nama_hadiah']}";
                    }else{
                        $gift .= ", {$e['qty_hadiah']} {$e['nama_part']}";
                    }
                }

                $sub_array['gifts'] = substr($gift, 2);
            }else{
                $this->db
                ->select('prmi.qty')
                ->select('h.*')
                ->from('ms_h3_promo_dealer_items as prmi')
                ->join('ms_h3_promo_dealer_hadiah as h', "(h.id_promo = '{$each['id_promo']}' and h.id_items = prmi.id)")
                ->where('prmi.id_promo', $each['id_promo'])
                ->group_start()
                ->where("'{$this->input->get('id_part')}' in (prmi.id_part)")
                ->or_where("'{$this->input->get('kelompok_part')}' = prmi.kelompok_part")
                ->group_end();

                $gifts = [];
                $text_gift = '';
                foreach ($this->db->get()->result_array() as $e) {
                    if($each['tipe_promo'] == 'Bertingkat'){
                        $text_gift = "Untuk pembelian dengan kuantitas {$e['qty']} mendapatkan ";
                    }else if($each['tipe_promo'] == 'Paket'){
                        $text_gift = "Untuk pembelian minimal {$each['minimal_pembelian']} mendapatkan ";
                    }

                    if($e['part_ahass'] == 0){
                        $text_gift .= "{$e['qty_hadiah']} {$e['nama_hadiah']}";
                    }else{
                        $text_gift .= "{$e['qty_hadiah']} {$e['nama_part']}";
                    }

                    $text_gift = substr($text_gift, 0);
                    $gifts[] = $text_gift;
                }
                $sub_array['gifts'] = '';
                if(count($gifts) > 0){
                    foreach ($gifts as $e) {
                        $sub_array['gifts'] .= ", {$e}";
                    }
                    $sub_array['gifts'] = substr($sub_array['gifts'], 2);
                }
            }

            $data[] = $sub_array;
        }

        send_json($data);
    }
}
