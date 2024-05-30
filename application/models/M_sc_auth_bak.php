<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_sc_auth extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function cekLogin($filter = null)
  {
    $where = "WHERE akses_sc='1' AND akses_sc=1";
    if (isset($filter['username']) && isset($filter['password'])) {
      $password = md5($filter['password']);
      $where .= " AND username_sc='{$filter['username']}' AND password_sc='$password'";
    } else {
      // $where .= " AND 0=1"; //Buat kondisi False karena tidak ada username & password
    }
    $base_url = base_url();
    $default_image_lk = default_img_adm_lk();
    $default_image_pr = default_img_adm_pr();
    $result = $this->db->query("SELECT 
      nama_dealer company,nama_lengkap name,
      CASE 
        WHEN kd.image IS NULL THEN 
          CASE 
            WHEN kd.jk='Laki-laki' THEN '$default_image_lk'
            WHEN kd.jk='Perempuan' THEN '$default_image_pr'
            ELSE '$default_image_lk'
          END
        WHEN kd.image LIKE '%$base_url%' THEN kd.image
        ELSE concat('$base_url',kd.image)
      END AS image,
      usr.username_sc AS username, usr.id_user id,no_hp phone, kd.email,role_sc roles_id,rl.code roles_code,rl.role roles_name,dl.id_dealer,usr.id_user
    FROM ms_user usr
    JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=usr.id_karyawan_dealer
    JOIN ms_dealer dl ON dl.id_dealer=kd.id_dealer
    JOIN sc_ms_role rl ON rl.id=usr.role_sc
    $where
    ");
    if ($result->num_rows() > 0) {
      $res_ = $result->row();
      $res_->id = (int)$res_->id;
      $res_->roles_id = (int)$res_->roles_id;
      if ($res_->email == '' || $res_->email == NULL) {
        $res_->email = 'default@email.com';
      }
      if (isset($filter['token'])) {
        $upd_cond = [
          'id_user' => $res_->id_user,
        ];
        // $upd = [
        //   'id_user' => NULL,
        //   'id_dealer' => NULL
        // ];
        // $this->db->update('sc_api_token', $upd, $upd_cond);
        $upd_token = [
          'id_user' => $res_->id_user,
          'id_user_renew' => $res_->id_user,
          'id_dealer' => $res_->id_dealer
        ];
        $this->db->update('sc_api_token', $upd_token, ['token' => $filter['token']]);
        $upd_user = [
          'last_login_sc' => waktu_full(),
          'regid' => $filter['regid']
        ];
        $this->db->update('ms_user', $upd_user, ['id_user' => $res_->id_user]);

        unset($res_->id_user);
        unset($res_->id_dealer);
      }
      return $res_;
    }
  }
  function cekUser($filter)
  {
    $where = "WHERE 1=1 ";
    if (isset($filter['email'])) {
      $where .= " AND email='{$filter['email']}'";
    }
    $res = $this->db->query("SELECT email,usr.id_user,sc_reset_pass_ke 
    FROM ms_user usr
    LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=usr.id_karyawan_dealer
    $where");
    if ($res->num_rows() > 0) {
      return $res;
    }
  }
  function generateToken($params, $renew = false)
  {
    $token = set_token();
    $cek = 0;
    while ($cek == 0) {
      $cek_token = $this->db->get_where('sc_api_token', ['token' => $token]);
      if ($cek_token->num_rows() == 0) {
        $cek = 1;
      } else {
        $token = set_token();
      }
    }
    $expired = converted_datetime(waktu_full());
    // send_json($expired);
    $expired_int = strtotime($expired);
    if ($renew == true) {
      $params = $params->row();
    }
    $insert = [
      'id_user' => $renew == true ? $params->id_user : NULL,
      'id_user_renew' => $renew == true ? $params->id_user : NULL,
      'id_dealer' => $renew == true ? $params->id_dealer : NULL,
      'token' => $token,
      'ip_address' => get_client_ip(),
      'user_agent' => get_user_agent(),
      'expired_at' => $expired_int,
      'expired_datetime' => $expired
    ];
    // send_json($insert);
    $this->db->insert('sc_api_token', $insert);
    if ($renew == true) {
      $this->cekLastTokenUser($params->id_user, $token);
    }
    $result = [
      'token' => $token,
      'expired_at' => $expired_int,
      'expired_datetime' => $expired
    ];
    return $result;
  }
  function validasiToken($filter = null)
  {
    // $filter['token'] = 'MmgwcmRpeGM4YTRnYzBnY3d3NGN3b2NrMA==';
    $where = '';
    $select = "sc_tk.token,sc_tk.id_user,usr.username_sc username,kry.id_dealer";
    if ($filter != null) {
      $where = "WHERE 1=1 ";
      if (isset($filter['token'])) {
        $ip_addres = get_client_ip();
        $user_agent = get_user_agent();
        $where .= " AND token='{$filter['token']}' AND ip_address='$ip_addres' AND user_agent='$user_agent' AND expired_datetime>NOW() AND logout_at IS NULL
        ";
      } else {
        $where .= " AND 0=1 "; //disabled untuk testing monju
      }
      if (isset($filter['password'])) {
        $password = md5($filter['password']);
        $where .= " AND usr.password='$password'";
      }
      if (isset($filter['password_renew'])) {
        $password = md5($filter['password_renew']);
        $where .= " AND usr_r.password_sc='$password' ";
        $select = "sc_tk.token,sc_tk.id_user_renew id_user,usr_r.username_sc username,kry_r.id_dealer";
      }
      if (isset($filter['id_user_not_null'])) {
        $where .= " AND sc_tk.id_user IS NOT NULL";
      }
    } else {
      $where .= " AND 0=1"; //Kondisi False karena token tidak ada
    }

    $result = $this->db->query("SELECT $select
    FROM sc_api_token sc_tk
    
    LEFT JOIN ms_user usr ON usr.id_user=sc_tk.id_user
    LEFT JOIN ms_karyawan_dealer kry ON kry.id_karyawan_dealer=usr.id_karyawan_dealer
    
    LEFT JOIN ms_user usr_r ON usr_r.id_user=sc_tk.id_user_renew
    LEFT JOIN ms_karyawan_dealer kry_r ON kry_r.id_karyawan_dealer=usr_r.id_karyawan_dealer
    $where
    ");
    if ($result->num_rows() > 0) {
      return $result;
    } else {
      // send_json(print_r($this->db->last_query()));
    }
  }

  function renewToken($filter = null)
  {
    // send_json($filter);
    $validate = $this->validasiToken($filter);
    // send_json($validate->row());
    if ($validate) {
      $result = $this->generateToken($validate, true);
      return $result;
    }
  }
  function logout($filter)
  {
    $usr = $this->db->get_where('sc_api_token', $filter)->row();
    $upd_user = ['regid' => NULL];
    $upd = [
      'logout_at' => waktu_full(),
      'id_user' => null
    ];
    $this->db->update('sc_api_token', $upd, $filter);
    if ($usr != NULL) {
      $this->db->update('ms_user', $upd_user, ['id_user' => $usr->id_user]);
    }
    return $this->db->affected_rows() > 0;
  }

  public function setSendEmailForgotPassword($params)
  {
    $tanggal = get_ymd();
    $waktu   = jam_menit();
    $from = $this->db->get_where('ms_email_md', ['email_for' => 'notification'])->row();
    $to_email   = $params['to_email'];
    // $to_email   = 'husna3305@gmail.com';
    $cfg  = $this->db->get('setup_smtp_email')->row();
    $config = array(
      'protocol' => 'smtp',
      'smtp_host' => $cfg->smtp_host,
      'smtp_port' => 465,
      'smtp_user' => $from->email,
      'smtp_pass' => $from->pass,
      'mailtype'  => 'html',
      'charset'   => 'iso-8859-1'
    );

    $data = [
      'logo' => base_url('assets/panel/images/logo_sinsen.jpg'),
      'url' => base_url('reset_password?id=' . $params['gen_id']),
    ];

    $this->load->library('email', $config);
    $this->email->set_newline("\r\n");
    $this->email->from($from->email, '[SINARSENTOSA] ');
    $this->email->to($to_email);
    $this->email->subject('Reset Lupa Password, ' . $tanggal . ' ' . $waktu);
    $this->email->message($this->load->view('dealer/sales_tools/email_forgot_password', $data, true));

    //Send mail 
    if ($this->email->send()) {
      return true;
    }
  }

  function cekLastTokenUser($id_user, $token)
  {
    $res = $this->db->query("SELECT token FROm sc_api_token WHERE id_user='$id_user' AND token!='$token'");
    if ($res->num_rows() > 0) {
      $this->db->query("UPDATE sc_api_token SET id_user=NULL WHERE id_user='$id_user' AND token!='$token'");
    }
  }
}
