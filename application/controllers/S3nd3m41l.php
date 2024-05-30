<?php
defined('BASEPATH') or exit('No direct script access allowed');

class S3nd3m41l extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      //===== Load Database =====
      $this->load->database();
      $this->load->model('m_s3nd3m41l', 'm_s');
   }

   public function salesDealerByFinco()
   {
      $tanggal           = gmdate("Y-m-d", time() + 60 * 60 * 7);
      $waktu             = gmdate("H.i", time() + 60 * 60 * 7);
      // $tanggal           = $this->input->get('tgl');
      $params['tanggal'] = $tanggal;
      $get_data          = $this->m_s->getSalesDealerByFinco($params);
      $data['get_data']   = $get_data;
      $data['tanggal']   = $tanggal;
      $data['waktu']   = $waktu;
      $data['logo']      = base_url('assets/panel/images/logo_sinsen.jpg');
      // $this->load->view('auto_email/sales_daler_by_finco', $data);

      $from = $this->db->get_where('ms_email_md', ['email_for' => 'notification'])->row();
      $to_email   = 'ahadi3305@gmail.com, husna3305@gmail.com';

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

      $this->load->library('email', $config);
      $this->email->set_newline("\r\n");
      $this->email->from($from->email, '[SINARSENTOSA] ');
      $this->email->to($to_email);
      $this->email->subject('Sales Dealer By Finco' . $tanggal . ' ' . $waktu);
      $this->email->message($this->load->view('auto_email/sales_daler_by_finco', $data, true));

      //Send mail 
      if ($this->email->send()) {
         echo 'yes';
      } else {
         echo 'no';
      }
   }

   public function cron_email()
   {
      $from = $this->db->get_where('ms_email_md', ['email_for' => 'notification'])->row();
      $to_email   = 'michael.chandra@sinarsentosa.co.id';

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

      $this->load->library('email', $config);
      $this->email->set_newline("\r\n");
      $this->email->from($from->email, '[SINARSENTOSA] ');
      $this->email->to($to_email);
      $this->email->subject('Tes Kirim email ' . get_waktu());
      $this->email->message("Hallo ini email testing");
      // $this->email->message($this->load->view('auto_email/sales_daler_by_finco', $data, true));

      //Send mail 
	/* dimatikan dulu -> 2021-08-27
      if ($this->email->send()) {
         echo 'yes';
      } else {
         echo 'no';
      }
	*/
   }

   public function tes_em()
   {
      $from = $this->db->get_where('ms_email_md', ['email_for' => 'notification'])->row();
      $to_email   = 'ahadi3305@gmail.com, husna3305@gmail.com';

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
      $this->load->library('email', $config);
      $this->email->set_newline("\r\n");
      $this->email->from($from->email, 'TESS TESSS');
      $this->email->to($to_email);
      $this->email->subject('subject 2');
      $this->email->message('dddd');

      //Send mail 
      if ($this->email->send()) {
         echo 'yes';
      } else {
         echo 'no';
      }
   }
}
