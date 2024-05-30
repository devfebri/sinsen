<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('generatehtml')) {

    function get_waktu()
    {
        date_default_timezone_set('Asia/Jakarta');
        return date('Y-m-d H:i:s');
    }

    function get_data($tabel,$primary_key,$id,$select)
    {
        $CI =& get_instance();
        $data = $CI->db->query("SELECT $select FROM $tabel where $primary_key='$id' ")->row_array();
        return $data[$select];
    }


    function alert_biasa($pesan,$type)
    {
        return 'swal("'.$pesan.'", "You clicked the button!", "'.$type.'");';
    }

    function log_r($string = null, $var_dump = false)
    {
        if ($var_dump) {
            var_dump($string);
        } else {
            echo "<pre>";
            print_r($string);
        }
        exit;
    }

    function log_data($string = null, $var_dump = false)
    {
        if ($var_dump) {
            var_dump($string);
        } else {
            echo "<pre>";
            print_r($string);
        }
        // exit;
    }


    function mata_uang_rp($a)
    {
        if (preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
        if (is_numeric($a) and $a != 0 and $a != "") {
            return number_format($a, 0, ',', '.');
        } else {
            return $a;
        }
    }

    function int_ke_huruf($int)
    {
        switch ($int) {
            case 1:
                return 'A';
                break;
            case 2:
                return 'B';
                break;
            case 3:
                return 'C';
                break;
            case 4:
                return 'D';
                break;
            case 5:
                return 'E';
                break;
            case 6:
                return 'F';
                break;
            case 7:
                return 'G';
                break;
            case 8:
                return 'H';
                break;
            case 9:
                return 'I';
                break;
            case 10:
                return 'J';
                break;
            case 11:
                return 'K';
                break;
        }
    }
    function getBulanRomawi($bln)
    {
        switch ($bln) {
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }
    function rp($x)

    {

        if (is_int($x) == FALSE) {

            return '';
        } else {

            return number_format((int) $x, 0, ",", ".");
        }
    }



    function waktu()

    {

        date_default_timezone_set('Asia/Jakarta');

        return date("Y-m-d H:i:s");
    }



    function tgl_indo($tgl)

    {

        return substr($tgl, 8, 2) . ' ' . getbln(substr($tgl, 5, 2)) . ' ' . substr($tgl, 0, 4);
    }



    function tgl_indojam($tgl, $pemisah)

    {

        return substr($tgl, 8, 2) . ' ' . getbln(substr($tgl, 5, 2)) . ' ' . substr($tgl, 0, 4) . ' ' . $pemisah . ' ' .  substr($tgl, 11, 8);
    }





    function getbln($bln)

    {

        switch ($bln) {



            case 1:

                return "Januari";

                break;



            case 2:

                return "Februari";

                break;



            case 3:

                return "Maret";

                break;



            case 4:

                return "April";

                break;



            case 5:

                return "Mei";

                break;



            case 6:

                return "Juni";

                break;



            case 7:

                return "Juli";

                break;



            case 8:

                return "Agustus";

                break;



            case 9:

                return "September";

                break;



            case 10:

                return "Oktober";

                break;



            case 11:

                return "November";

                break;



            case 12:

                return "Desember";

                break;
        }
    }



    function selisihTGl($tgl1, $tgl2)

    {

        $pecah1 = explode("-", $tgl1);

        $date1 = $pecah1[2];

        $month1 = $pecah1[1];

        $year1 = $pecah1[0];



        // memecah tanggal untuk mendapatkan bagian tanggal, bulan dan tahun

        // dari tanggal kedua



        $pecah2 = explode("-", $tgl2);

        $date2 = $pecah2[2];

        $month2 = $pecah2[1];

        $year2 =  $pecah2[0];



        // menghitung JDN dari masing-masing tanggal



        $jd1 = GregorianToJD($month1, $date1, $year1);

        $jd2 = GregorianToJD($month2, $date2, $year2);



        // hitung selisih hari kedua tanggal



        $selisih = $jd2 - $jd1;

        return $selisih;
    }



    function seoString($s)

    {

        $c = array(' ');

        $d = array('-', '/', '\\', ',', '.', '#', ':', ';', '\'', '"', '[', ']', '{', '}', ')', '(', '|', '`', '~', '!', '@', '%', '$', '^', '&', '*', '=', '?', '+');



        $s = str_replace($d, '', $s); // Hilangkan karakter yang telah disebutkan di array $d



        $s = strtolower(str_replace($c, '-', $s)); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua

        return $s;
    }





    function breacumb($link)

    {

        $CI = &get_instance();

        $main = $CI->db->get_where('mainmenu', array('link' => $link));

        if ($main->num_rows() > 0) {

            $main = $main->row_array();

            return $main['nama_mainmenu'];
        } else {

            $sub = $CI->db->get_where('submenu', array('link' => $link));

            if ($sub->num_rows() > 0) {

                $sub = $sub->row_array();

                return $sub['nama_submenu'];
            } else {

                return 'tidak diketahui';
            }
        }
    }



    function jmlPaging()

    {

        return 10;
    }



    function getusersLogin($idusers, $field)

    {

        $CI = &get_instance();

        $row = $CI->db->get_where('app_users', array('id_users' => $idusers));

        if ($row->num_rows() > 0) {

            $row = $row->row_array();

            return $row[$field];
        } else {

            return '';
        }
    }


    function getField($tables, $field, $pk, $value)

    {

        $CI = &get_instance();

        $data = $CI->db->query("select $field from $tables where $pk='$value'");

        if ($data->num_rows() > 0) {

            $data = $data->row_array();

            return $data[$field];
        } else {

            return '';
        }
    }

    function faktur()

    {

        $CI = &get_instance();

        $query = "SELECT max(coba) as coba FROM test";

        $hasil = mysql_query($query);

        $data  = @mysql_fetch_array($hasil);

        $data = $CI->db->query($query)->row_array();

        $kodeUSER = $data['coba'];

        $noUrut = (int) substr($kodeUSER, 18, 6);

        $noUrut++;

        $char = ""; //Aktifkan, Jika ingin menggunakan karakter di depan USER_ID

        $newID = $char . sprintf("%04s", $noUrut);

        return $newID;
    }





    function kode_daftar($tingkat, $gender)

    {

        $CI = &get_instance();

        $query = $CI->db->query("SELECT max(kode_daftar) as kode_daftar FROM pmb_student where gender='$gender' and tingkat='$tingkat'");

        if ($query->num_rows() > 0) {

            $query = $query->row_array();

            $kode = $query['kode_daftar'];

            $noUrut = (int) substr($kode, 9, 3);

            $noUrut++;

            //return $noUrut;

            return sprintf("%03s", $noUrut);

            //return (int) $query['kode_daftar'];

        } else {

            return "001";
        }
    }





    function ubahtanggal($tanggal)

    {

        return $newtanggal = substr($tanggal, 8, 2) . '-' . substr($tanggal, 5, 2) . '-' . substr($tanggal, 0, 4);
    }



    function ubahtanggal2($tanggal)

    {

        return $newtanggal = substr($tanggal, 8, 2) . '/' .  substr($tanggal, 5, 2) . '/' .  substr($tanggal, 0, 4);
    }

    function sms_zenziva($telepon, $pesan)
    {

        $CI         =   &get_instance();

        $sms        = $CI->db->query("SELECT * FROM ms_akun_sms WHere active=1")->row();

        $userkey    = $sms->user_key;
        $passkey    = $sms->pass_key;
        // $telepon = '082282535844';
        // $message = "Tes HONDA (SMS)";
        $url        = $sms->link_api;
        $curlHandle = curl_init();

        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey=' . $userkey . '&passkey=' . $passkey . '&nohp=' . $telepon . '&pesan=' . urlencode($pesan));
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        $results = curl_exec($curlHandle);
        curl_close($curlHandle);
        $XMLdata = new SimpleXMLElement($results);
        $status = $XMLdata->message[0]->status;
        if ($status == 0) {
            return ['status' => 0, 'msg' => 'Sukses, SMS telah berhasil disubmit ke server.'];
        } elseif ($status == 1) {
            return ['status' => 1, 'msg' => 'No. tujuan tidak valid.'];
        } elseif ($status == 5) {
            return ['status' => 5, 'msg' => 'Userkey / Passkey salah.'];
        } elseif ($status == 6) {
            return ['status' => 6, 'msg' => 'Konten SMS rejected.'];
        } elseif ($status == 89) {
            return ['status' => 6, 'msg' => 'Pengiriman SMS berulang-ulang ke satu nomor dalam satu waktu.'];
        } elseif ($status == 99) {
            return ['status' => 5, 'msg' => 'Credit tidak mencukupi.'];
        }
    }

    function pesan($template_pesan, $id)
    {
        // $string = "Reminder Indent untuk customer [NamaCustomer], mohon segera difolow up, unit telah sampai di Dealer [NamaDealer]. Unit [TipeUnit] Warna [Warna]";
        // $id = ['NamaCustomer'=>'18/09/23/00020-12322','NamaDealer'=>'2','TipeUnit'=>'GD2','Warna'=>'MH'];

        // foreach ($id as $key=>$val) {
        //     $string = str_replace($key, $val, $string);
        // }
        $CI         =   &get_instance();
        $str = explode(']', $template_pesan);
        // var_dump($str);
        foreach ($str as $val) {
            $kata  = after('[', $val);
            $ganti = $CI->db->query("SELECT * FROM ms_pesan_ganti_id WHERE kata_pesan='$kata'");
            if ($ganti->num_rows() > 0) {
                $id_get    = $id[$kata];
                $ganti = $ganti->row();
                $cek   = $CI->db->query("SELECT * FROM $ganti->tabel WHERE $ganti->id_get='$id_get' ");
                if ($cek->num_rows() > 0) {
                    $cek = $cek->row_array();
                    $template_pesan = str_replace("[$kata]", $cek[$ganti->ganti], $template_pesan);
                }
            }
        }
        // $template_pesan = str_replace(']', '', (str_replace('[', '', $template_pesan)));
        return $template_pesan;
    }

    function after($this, $inthat)
    {
        if (!is_bool(strpos($inthat, $this)))
            return substr($inthat, strpos($inthat, $this) + strlen($this));
    };

    function after_last($this, $inthat)
    {
        if (!is_bool(strrevpos($inthat, $this)))
            return substr($inthat, strrevpos($inthat, $this) + strlen($this));
    };

    function before($this, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $this));
    };

    function before_last($this, $inthat)
    {
        return substr($inthat, 0, strrevpos($inthat, $this));
    };

    function between($this, $that, $inthat)
    {
        return before($that, after($this, $inthat));
    };

    function between_last($this, $that, $inthat)
    {
        return after_last($this, before_last($that, $inthat));
    };

    // use strrevpos function in case your php version does not include it
    function strrevpos($instr, $needle)
    {
        $rev_pos = strpos(strrev($instr), strrev($needle));
        if ($rev_pos === false) return false;
        else return strlen($instr) - $rev_pos - strlen($needle);
    };

    function days_in_month($month, $year)
    {
        return date('t', mktime(0, 0, 0, $month + 1, 0, $year));
    }

    function tambah_dmy($ditambah, $tambah, $tgl)
    {
        if ($ditambah == 'bulan') $wkt = 'months';
        if ($ditambah == 'tanggal') $wkt = 'days';
        if ($ditambah == 'tahun') $wkt = 'year';
        if (function_exists('date_default_timezone_set')) date_default_timezone_set('Asia/Jakarta');
        $date = date_create("$tgl 00:00:00");
        date_add($date, date_interval_create_from_date_string("$tambah $wkt"));
        return $r = ['tanggal' => date_format($date, 'd'), 'bulan' => date_format($date, 'm'), 'tahun' => date_format($date, 'Y')];
    }

    function disc_scp($tgl, $id_tipe_kendaraan, $id_warna)
    {
        $CI = &get_instance();
        $disc_scp = 0;
        $disc = $CI->db->query("SELECT SUM(ahm_kredit) AS ahm_kredit,SUM(md_kredit) AS md_kredit 
            FROM tr_sales_program AS tsp
            JOIN tr_sales_program_tipe AS tspt ON tsp.id_program_md=tspt.id_program_md
            WHERE '$tgl' BETWEEN periode_awal AND periode_akhir 
            AND id_tipe_kendaraan='$id_tipe_kendaraan' 
            AND id_warna LIKE('%$id_warna%') 
            AND id_jenis_sales_program='SP-001'
            AND tspt.metode_pembayaran='Bayar Didepan(Potong DO)'
            ")->row();
        return $disc_scp = ($disc->ahm_kredit + $disc->md_kredit) / 1.1;
    }

    function selisihWaktu($tgl1, $tgl2)
    {
        // $tgl1 = '2005-09-01 09:02:23';
        $tgl1 = new DateTime($tgl1);

        // $tgl2 = '2005-09-02 09:02:23';
        $tgl2 = new DateTime($tgl2);

        // $sekarang = new DateTime();
        $perbedaan = date_diff($tgl1, $tgl2);
        // $perbedaan = $tgl2->diff($tgl2);

        //gabungkan
        // echo $perbedaan->y.' selisih tahun.';
        // echo $perbedaan->m.' selisih bulan.';
        // echo $perbedaan->d.' selisih hari.';
        // echo $perbedaan->h.' selisih jam.';
        // echo $perbedaan->i.' selisih menit.';
        return $perbedaan->days;
    }

    function bandingTgl($tgl1, $tgl2)
    {
        $tgl1 = strtotime($tgl1);
        $tgl2 = strtotime($tgl2);
        if ($tgl1 > $tgl2) {
            return 1;
        } else {
            return 0;
        }
    }

    function cek_dealer($id_dealer)
    {
        $CI = &get_instance();
        $h1_murni = $CI->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$id_dealer' AND h1=1 AND h2=0 AND h3=0");
        $h2_murni = $CI->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$id_dealer' AND h1=0 AND h2=2 AND h3=0");
        $h3_murni = $CI->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$id_dealer' AND h1=0 AND h2=0 AND h3=1");
        $h12 = $CI->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$id_dealer' AND h1=1 AND h2=1 AND h3=0");
        $h23 = $CI->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$id_dealer' AND h1=0 AND h2=1 AND h3=1");
        $h123 = $CI->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$id_dealer' AND h1=1 AND h2=1 AND h3=1");
        $cek_dealer = 'null';
        if ($h1_murni->num_rows() > 0) $cek_dealer = 'h1_murni';
        if ($h2_murni->num_rows() > 0) $cek_dealer = 'h2_murni';
        if ($h2_murni->num_rows() > 0) $cek_dealer = 'h2_murni';
        if ($h12->num_rows() > 0) $cek_dealer      = 'h12';
        if ($h23->num_rows() > 0) $cek_dealer      = 'h23';
        if ($h123->num_rows() > 0) $cek_dealer     = 'h123';
        return $cek_dealer;
    }
    function kop_surat_dealer($id_dealer)
    {
        $CI = &get_instance();
        $dealer = $CI->db->query("SELECT ms_dealer.*,kelurahan,kecamatan,kabupaten,provinsi FROM ms_dealer 
                      LEFT JOIN ms_kelurahan ON ms_kelurahan.id_kelurahan=ms_dealer.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan=ms_kelurahan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten=ms_kecamatan.id_kabupaten
                      LEFT JOIN ms_provinsi ON ms_provinsi.id_provinsi=ms_kabupaten.id_provinsi
                      WHERE id_dealer='$id_dealer'
                      ")->row();
        return strtoupper($dealer->nama_dealer) . " <br>
            $dealer->alamat <br>
            " . ucwords($dealer->kecamatan . " " . $dealer->kabupaten) . "<br>
            $dealer->provinsi <br>
            $dealer->no_telp <br>
        ";
    }

    function random_hex($length)
    {
        $data = 'ABCDEF1234567890';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $pos = rand(0, strlen($data) - 1);
            $string .= $data{
                $pos};
        }
        return $string;
    }

    function send_email($params)
    {
        $CI = &get_instance();
        $from = $CI->db->get_where('ms_email_md', ['email_for' => $params['email_for']])->row();
        $cfg  = $CI->db->get('setup_smtp_email')->row();
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => $cfg->smtp_host,
            'smtp_port' => 465,
            'smtp_user' => $from->email,
            'smtp_pass' => $from->pass,
            'mailtype'  => 'html',
            'charset'   => 'iso-8859-1'
        );
        $CI->load->library('email');
        $CI->email->initialize($config);
        $CI->email->set_newline("\r\n");
        $CI->email->from($from->email, 'SINARSENTOSA');
        $CI->email->to($params['emails_to']);
        $CI->email->subject($params['subject']);
        $CI->email->message($params['message']);
        //Send mail 
        if ($CI->email->send()) {
            return true;
        }
    }
}
