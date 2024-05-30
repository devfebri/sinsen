<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getPPN')) {
    function getPPN($value = false, $tgl = false)
    {
        $CI = &get_instance();
        if($tgl==false){
            $tgl= date('Y-m-d');
        }

        $result = 0;

        /* opsi kasih end date biar gak berat dilooping atau get setting
        ==================================================================
        ==   Start Date   ====    End Date   == Persent 1 == Persent 2 ==    
        ==================================================================
        ==   2022-06-01   ====   2100-31-31  ==   0.13    ==    1.13   ==
        ==   2022-05-01   ====   2022-05-31  ==   0.12    ==    1.12   ==
        ==   2022-04-01   ====   2022-04-30  ==   0.11    ==    1.11   ==
        ==================================================================
        */

        $data = $CI->db->query("SELECT start_date, end_date, persen_ppn, persen_1, persen_2 from ms_ppn where '$tgl' between start_date and end_date order by start_date desc");

        // tidak perlu di foreach utk menentukan ppn sesuai dengan aktif start date karena sudah ada end date
        if ($data->num_rows() == 1) {
            if($value!=false){
                if ($value == 0.1) {
                    $result = $data->row()->persen_1;
                } elseif ($value == 1.1) {
                    $result = $data->row()->persen_2;
                }
                return $result;
            }else{
                return $data->row()->persen_ppn;
            }
        }else {
            // return default 100% atau something wrong
            return 100;
        }
    }
}

