<?php



defined('BASEPATH') or exit('No direct script access allowed');

class StockMD extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin');
    }
    public function index()
    {
        $kuantitas = 0;

        if($this->input->get('qty') != null){
            $kuantitas = $this->input->get('qty');
        }

        //check dulu apakah dealer telah ada di master etd
        $check_dealer_di_ms_etd = $this->db->select('id_dealer')
                                           ->from('ms_h3_md_estimated_time_delivery_items as etdi')
                                           ->where('etdi.id_dealer',$this->m_admin->cari_dealer())
                                           ->get()->row_array();
        
        //Jika dealer tersebut ada di ms
        if($check_dealer_di_ms_etd['id_dealer'] != '' || $check_dealer_di_ms_etd['id_dealer'] != NULL){
            $data = $this->db->select('mp.id_part, mp.nama_part, mp.harga_dealer_user as harga_saat_dibeli, mp.import_lokal, mp.current')
            ->select("
                case 
                    when etd.id is not null then 
                        case
                            when ('{$this->input->get('claim')}' = 'true' AND '{$this->input->get('tipe_claim')}' = 'renumbering_claim') then etd.ahm_md + etd.proses_md + etd.md_d + etd.rc
                            when ('{$this->input->get('claim')}' = 'true' AND '{$this->input->get('tipe_claim')}' = 'renumbering_non_claim') then etd.ahm_md + etd.proses_md + etd.md_d + etd.rn
                            when (mp.import_lokal = 'N' AND mp.current = 'C') then etd.ahm_md + etd.proses_md + etd.md_d + etd.lc
                            when (mp.import_lokal = 'N' AND mp.current = 'N') then etd.ahm_md + etd.proses_md + etd.md_d + etd.ln
                            when (mp.import_lokal = 'Y' AND mp.current = 'C') then etd.ahm_md + etd.proses_md + etd.md_d + etd.ic
                            when (mp.import_lokal = 'Y' AND mp.current = 'N') then etd.ahm_md + etd.proses_md + etd.md_d + etd.in
                        end
                    else 
                        case
                            when ('{$this->input->get('claim')}' = 'true' AND '{$this->input->get('tipe_claim')}' = 'renumbering_claim') then (1 + 1 + 1 + 14)
                            when ('{$this->input->get('claim')}' = 'true' AND '{$this->input->get('tipe_claim')}' = 'renumbering_non_claim') then (1 + 1 + 1 + 21)
                            when (mp.import_lokal = 'N' AND mp.current = 'C') then 3 + 2
                            when (mp.import_lokal = 'N' AND mp.current = 'N') then 3 + 4
                            when (mp.import_lokal = 'Y' AND mp.current = 'C') then 3 + 22
                            when (mp.import_lokal = 'Y' AND mp.current = 'N') then 3 + 44
                        end
                end as eta_terlama
            ")
            ->select("
            case 
                when etd.id is not null then 
                    case
                        when ('{$this->input->get('claim')}' = 'true' AND '{$this->input->get('tipe_claim')}' = 'renumbering_claim') then etd.proses_md + etd.md_d
                        when ('{$this->input->get('claim')}' = 'true' AND '{$this->input->get('tipe_claim')}' = 'renumbering_non_claim') then etd.proses_md + etd.md_d
                        when (mp.import_lokal = 'N' AND mp.current = 'C') then etd.proses_md + etd.md_d
                        when (mp.import_lokal = 'N' AND mp.current = 'N') then etd.proses_md + etd.md_d
                        when (mp.import_lokal = 'Y' AND mp.current = 'C') then etd.proses_md + etd.md_d
                        when (mp.import_lokal = 'Y' AND mp.current = 'N') then etd.proses_md + etd.md_d
                    end
                else 
                    2
            end as eta_tercepat
            ")
            ->select("
                case
                        when tsp.qty is null then 'Tidak Ada'
                        when tsp.qty = 0 then 'Tidak Ada'
                        else 'Ada'
                    end as stock
            ")
            ->select("
                    case
                        when tsp.qty >= {$kuantitas} then 'Cukup'
                        else 'Tidak Cukup'
                    end as status
                ")
            ->from('ms_part as mp')
            ->join('tr_stok_part as tsp', 'mp.id_part = tsp.id_part', 'left')
            ->join('ms_h3_md_estimated_time_delivery_items as etdi', "etdi.id_dealer = {$this->m_admin->cari_dealer()}", 'left')
            ->join('ms_h3_md_estimated_time_delivery as etd', 'etd.id = etdi.id_etd', 'left')
            ->where('mp.id_part', $this->input->get('id_part'))
            ->get()->row();

        }else{
            $data = $this->db->select('mp.id_part, mp.nama_part, mp.harga_dealer_user as harga_saat_dibeli, mp.import_lokal, mp.current')
            ->select("
                        case
                            when ('{$this->input->get('claim')}' = 'true' AND '{$this->input->get('tipe_claim')}' = 'renumbering_claim') then (1 + 1 + 1 + 14)
                            when ('{$this->input->get('claim')}' = 'true' AND '{$this->input->get('tipe_claim')}' = 'renumbering_non_claim') then (1 + 1 + 1 + 21)
                            when (mp.import_lokal = 'N' AND mp.current = 'C') then 3 + 2
                            when (mp.import_lokal = 'N' AND mp.current = 'N') then 3 + 4
                            when (mp.import_lokal = 'Y' AND mp.current = 'C') then 3 + 22
                            when (mp.import_lokal = 'Y' AND mp.current = 'N') then 3 + 44
                        end as eta_terlama
            ")
            ->select("
                    2 as eta_tercepat
            ")
            ->select("
                case
                        when tsp.qty is null then 'Tidak Ada'
                        when tsp.qty = 0 then 'Tidak Ada'
                        else 'Ada'
                    end as stock
            ")
            ->select("
                    case
                        when tsp.qty >= {$kuantitas} then 'Cukup'
                        else 'Tidak Cukup'
                    end as status
                ")
            ->from('ms_part as mp')
            ->join('tr_stok_part as tsp', 'mp.id_part = tsp.id_part', 'left')
            ->where('mp.id_part', $this->input->get('id_part'))
            ->get()->row();
        }


        // $data = $this->db->select('mp.id_part, mp.nama_part, mp.harga_dealer_user as harga_saat_dibeli, mp.import_lokal, mp.current')
        // ->select("
        //     case 
        //         when etd.id is not null then 
        //             case
        //                 when ('{$this->input->get('claim')}' = 'true' AND '{$this->input->get('tipe_claim')}' = 'renumbering_claim') then etd.ahm_md + etd.proses_md + etd.md_d + etd.rc
        //                 when ('{$this->input->get('claim')}' = 'true' AND '{$this->input->get('tipe_claim')}' = 'renumbering_non_claim') then etd.ahm_md + etd.proses_md + etd.md_d + etd.rn
        //                 when (mp.import_lokal = 'N' AND mp.current = 'C') then etd.ahm_md + etd.proses_md + etd.md_d + etd.lc
        //                 when (mp.import_lokal = 'N' AND mp.current = 'N') then etd.ahm_md + etd.proses_md + etd.md_d + etd.ln
        //                 when (mp.import_lokal = 'Y' AND mp.current = 'C') then etd.ahm_md + etd.proses_md + etd.md_d + etd.ic
        //                 when (mp.import_lokal = 'Y' AND mp.current = 'N') then etd.ahm_md + etd.proses_md + etd.md_d + etd.in
        //             end
        //         else 
        //             case
        //                 when ('{$this->input->get('claim')}' = 'true' AND '{$this->input->get('tipe_claim')}' = 'renumbering_claim') then (1 + 1 + 1 + 14)
        //                 when ('{$this->input->get('claim')}' = 'true' AND '{$this->input->get('tipe_claim')}' = 'renumbering_non_claim') then (1 + 1 + 1 + 21)
        //                 when (mp.import_lokal = 'N' AND mp.current = 'C') then 3 + 2
        //                 when (mp.import_lokal = 'N' AND mp.current = 'N') then 3 + 4
        //                 when (mp.import_lokal = 'Y' AND mp.current = 'C') then 3 + 22
        //                 when (mp.import_lokal = 'Y' AND mp.current = 'N') then 3 + 44
        //             end
        //     end as eta_terlama
        // ")
        // ->select("
        // case 
        //     when etd.id is not null then 
        //         case
        //             when ('{$this->input->get('claim')}' = 'true' AND '{$this->input->get('tipe_claim')}' = 'renumbering_claim') then etd.proses_md + etd.md_d
        //             when ('{$this->input->get('claim')}' = 'true' AND '{$this->input->get('tipe_claim')}' = 'renumbering_non_claim') then etd.proses_md + etd.md_d
        //             when (mp.import_lokal = 'N' AND mp.current = 'C') then etd.proses_md + etd.md_d
        //             when (mp.import_lokal = 'N' AND mp.current = 'N') then etd.proses_md + etd.md_d
        //             when (mp.import_lokal = 'Y' AND mp.current = 'C') then etd.proses_md + etd.md_d
        //             when (mp.import_lokal = 'Y' AND mp.current = 'N') then etd.proses_md + etd.md_d
        //         end
        //     else 
        //         2
        // end as eta_tercepat
        // ")
        // ->select("
        //     case
        //             when tsp.qty is null then 'Tidak Ada'
        //             when tsp.qty = 0 then 'Tidak Ada'
        //             else 'Ada'
        //         end as stock
        // ")
        // ->select("
        //         case
        //             when tsp.qty >= {$kuantitas} then 'Cukup'
        //             else 'Tidak Cukup'
        //         end as status
        //     ")
        // ->from('ms_part as mp')
        // ->join('tr_stok_part as tsp', 'mp.id_part = tsp.id_part', 'left')
        // ->join('ms_h3_md_estimated_time_delivery_items as etdi', "etdi.id_dealer = {$this->m_admin->cari_dealer()}", 'left')
        // ->join('ms_h3_md_estimated_time_delivery as etd', 'etd.id = etdi.id_etd', 'left')
        // ->where('mp.id_part', $this->input->get('id_part'))
        // ->get()->row();
     
        send_json($data);

    }



}

