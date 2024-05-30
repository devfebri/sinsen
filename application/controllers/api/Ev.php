<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ev extends CI_Controller
{

    public function __construct()
	{		
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->model('m_admin');	
		$this->load->helper('ev_helper');	
	}

	public function cron_job()
	{
			$get_date = gmdate("y-m-d H:i:s", time()+60*60*7);

			$token =  get_token_ev();

			$this->db->select('acc.*, acc_hs.send_to_ahm, acc_hs.accStatus as update_acc');
			$this->db->from('ev_log_send_api_3 acc_hs');
			$this->db->join('tr_status_ev_acc acc', 'acc_hs.serialNo = acc.serialNo', 'left');
			$this->db->where('acc_hs.send_to_ahm', null);
			$get = $this->db->get()->result();


			$temp = array();
			$history = array();

			foreach ($get as $item) 
			{
				$history[]=array(
					'serialNo'            => $item->serialNo,
					'accStatus'           => $item->accStatus,
					'send_to_ahm'         => $get_date
				);
			
			  $temp[]=array(
				'serialNo'            => $item->serialNo,
				'accType'             => $item->accType,
				'accStatus'           => $item->update_acc,
				'mdReceiveDate'       => $item->mdReceiveDate,
				'mdSLDate'            => isset($item->mdSLDate) ? $item->mdSLDate : '',
				'mdSLNo'              => isset($item->mdSLNo) ? $item->mdSLNo : '',
				'dealerCode'          => isset($item->dealerCode) ? $item->dealerCode : '',
				'dealerReceiveDate'   => isset($item->dealerReceiveDate) ? $item->dealerReceiveDate : '',
				'bastNo'              => isset($item->bastNo) ? $item->bastNo : '',
				'bastDate'            => isset($item->bastDate) ? $item->bastDate : '',
				'frameNo'             => isset($item->engineNo) ? $item->frameNo : '',
				'engineNo'            => isset($item->engineNo) ? $item->engineNo : '',
				'phoneNo'             => isset($item->phoneNo) ? $item->phoneNo : '',
				'custName'            => isset($item->custName) ? $item->custName : '',
				'invDirectSalesDate'  => isset($item->invDirectSalesDate) ? $item->invDirectSalesDate : '',
				'invDirectSalesNo'    => isset($item->invDirectSalesNo) ? $item->invDirectSalesNo : ''
			  );
			}

		// $url = 'https://portaldev.ahm.co.id/jx05/ahmsvsdeve000-pst/rest/sd/eve012/acc-update-status';
		$url = 'https://portal2.ahm.co.id/jx05/ahmsvsdeve000-pst/rest/sd/eve012/acc-update-status';

		$data = api_ev($token['jxid'], $token['txid'], $url, $temp);

        $responseData = json_decode($data, true);
		
        $status = $responseData['status'];
        $datas = $responseData['data'];
        $message = $responseData['message'];
	
		$error   =array();
		$success =array();

		// var_dump($data );
		// die();

        // Processing data section

        foreach ($datas as $item) {
			$errorMsg = count($item['errorMsg']);
			if ($errorMsg != 0) {
				$error[] = array (
					'serialNumber'    => $item['serialNo'],
					'response' => json_encode($item['errorMsg']),
					'transactionId'   => $item['transactionId'],
				);
				
			}else{
				$success[] = array (
					'serialNumber'    => $item['serialNo'],
					'response' => 'berhasil',
					'transactionId'   => $item['transactionId'],
				);
			}
        }


		foreach ($error as $item ) {

			$update=array(
				'send_to_ahm' => $get_date,
				'api_response' =>  200,
				'response' =>  $item['response_reject'],
				'transaction_id'=>$message['transactionId']
			);

			$item['serialNo'] =  $item['serialNumber'];
			$this->db->where('serialNo', $item['serialNo']);
			$this->db->update('ev_log_send_api_3', $update);
		}

		
		foreach ($success as $item ) {
			$update=array(
				'send_to_ahm' => $get_date,
				'api_response' =>  200,
				'response' => $responseData,
				'transaction_id'=>$message['transactionId']
			);

			$item['serialNo'] =  $item['serialNumber'];
			$this->db->where('serialNo', $item['serialNo']);
			$this->db->update('ev_log_send_api_3', $update);
		}


		$base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
		$url_cron_job = "/api/ev/cron_job";
		$full_url = $base_url . $url_cron_job;
		$jsonData = json_encode($data);
		$ins_log['post_data'] = $jsonData;
		$ins_log['created_at'] = gmdate("y-m-d H:i:s", time()+60*60*7);
		$ins_log['api_key'] = isset($token['jxid']) ? $token['jxid'] : '';
		// $ins_log['response_time'] = response_time();  
		$ins_log['endpoint']   = $full_url;
		$ins_log['pinpoint']   = "cronjob";
		$ins_log['message']   = $responseData ;
		$ins_log['ip_address']   = $_SERVER['REMOTE_ADDR'];
		// $data['status'] =  isset($data['status']) ? $data['status'] : '';
		$ins_log['kategori']   = "H1";
		$ins_log['type']       = "push";
		// $ins_log['data_count'] = $data['message']['rowCount'];
		$ins_log['data_count'] = 0;

		$this->db->insert('activity_ev_log', $ins_log);

	}


}