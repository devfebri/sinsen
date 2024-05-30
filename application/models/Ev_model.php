

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Ev_model extends CI_Model {

    function InsertAcc($data=NULL){
 
        
        $currentTimestamp = date("Y-m-d H:i:s");
        $acc = $data['acc'];

        $row['accType'] = $data['accType'];
        $serial_number  = $data['serial_number']; 

        $this->db->select('accType, mdReceiveDate, mdSLDate, mdSLNo, dealerCode, dealerReceiveDate, bastNo, bastDate, frameNo, engineNo, phoneNo, custName, accStatus_2_processed_at, accStatus_2_processed_by_user, accStatus_3_processed_at, accStatus_4_processed_by_user, accStatus_5_processed_at, accStatus_5_processed_by_user');
        $this->db->from('tr_status_ev_acc');
        $this->db->where('serialNo', $serial_number);
        $this->db->order_by('accessoriesNo', 'desc');
        $this->db->limit(1);

        $last_status = $this->db->get();
   

        $resultArray['api_from']  =1;
            switch ($acc) {
                case 2:
                    $row['accType'] = 'B'; 
                    $row['serialNo']  = $serial_number; 
                    $row['accStatus']     = 2; 
                    $row['mdReceiveDate'] = $currentTimestamp; 
                    $row['accStatus_2_processed_at'] = $currentTimestamp; 
                    $row['accStatus_2_processed_by_user'] = $data['user'];
                    $row['last_updated'] =$currentTimestamp;
                    $row['api_from'] =1;
                    break;
                case 3:
                    $row['accStatus']     = 3; 
                    $row['mdSLDate'] = $data['mdSLDate']; 
                    $row['serialNo']  = $serial_number; 
                    $row['mdSLNo']   = $data['mdSLNo'];
                    $row['dealerCode'] = $data['dealerCode'];
                    $row['accStatus_3_processed_at'] = $currentTimestamp; 
                    $row['accStatus_3_processed_by_user'] = $data['user'];
                    $row['last_updated'] =$currentTimestamp;
                    break;
                case 4:
                    $row['accStatus']  = 4; 
                    $row['dealerReceiveDate'] = $currentTimestamp; 
                    $row['accStatus_4_processed_at'] = $currentTimestamp; 
                    $row['accStatus_4_processed_by_user'] = $data['user'];
                    $row['last_updated'] =$currentTimestamp;
                    break;
                case 5:
                    $row['accStatus'] = 5; 
                    $row['bastNo']   = $data['bastNo'];
                    $row['bastDate'] = $data['bastDate'];
                    $row['frameNo']  = $data['frameNo'];
                    $row['engineNo'] = $data['engineNo']; 
                    $row['phoneNo']  = $data['phoneNo']; 
                    $row['custName'] = $data['custName'];
                    $row['accStatus_5_processed_at'] = $currentTimestamp; 
                    $row['accStatus_5_processed_by_user'] = $data['user'];
                    $row['last_updated'] =$currentTimestamp;
                    break;
                default:
                break;
                }


                 if ($last_status->num_rows() == 0) {

                        $history= array(
                        'accStatus'   => $acc,
                        'serialNo'=> $serial_number,
                        'created_at'  => $currentTimestamp,
                        'status_scan' => 1,
                        );
                        $this->db->insert('tr_status_ev_acc', $row);
                        $this->db->insert('ev_log_send_api_3', $history);
                 
                }else{
                    $last_status = $last_status->row();
        
                    $resultArray['accStatus'] = $history['accStatus'] = $acc;
                    $resultArray['serialNo']  = $history['serialNo'] = $serial_number;
                                                $history['created_at']  =  $currentTimestamp;
                                                $history['status_scan'] = 1;
                                                
                    $resultArray['accType']          =  $last_status->accType;
                    $resultArray['mdReceiveDate']    =  $last_status->mdReceiveDate == null ?  $row['mdReceiveDate'] : $last_status->mdReceiveDate;
                    $resultArray['mdSLDate']         =  $last_status->mdSLDate == null ?  $row['mdSLDate'] : $last_status->mdSLDate;
                    $resultArray['mdSLNo']           =  $last_status->mdSLNo == null ?    $row['mdSLNo'] : $last_status->mdSLNo;
                    $resultArray['dealerCode']       =  $last_status->dealerCode == null ?  $row['dealerCode'] : $last_status->dealerCode;

                    $resultArray['dealerReceiveDate']= isset($row['dealerReceiveDate']) ?  $row['dealerReceiveDate'] : $last_status->dealerReceiveDate; 
                    $resultArray['bastNo']           = isset($row['bastNo'])   ? $row['bastNo'] : $last_status->bastNo; 
                    $resultArray['bastDate']         = isset($row['bastDate']) ? $row['bastDate'] : $last_status->bastDate; 
                    $resultArray['frameNo']          = isset($row['frameNo'])  ? $row['frameNo'] : $last_status->frameNo; 
                    $resultArray['engineNo']         = isset($row['engineNo']) ? $row['engineNo'] : $last_status->engineNo; 
                    $resultArray['phoneNo']          = isset($row['phoneNo'])  ? $row['phoneNo'] : $last_status->phoneNo ; 
                    $resultArray['custName']         = isset($row['custName']) ? $row['custName'] : $last_status->custName ; 

                    $resultArray['accStatus_2_processed_at']         =  isset($row['accStatus_2_processed_at']) ?  $row['accStatus_2_processed_at']        : $last_status->accStatus_2_processed_at; 
                    $resultArray['accStatus_2_processed_by_user']    =  isset($row['accStatus_2_processed_at']) ?  $row['accStatus_2_processed_by_user']   : $last_status->accStatus_2_processed_by_user; 
                    $resultArray['accStatus_3_processed_at']         =  isset($row['accStatus_3_processed_at']) ?  $row['accStatus_3_processed_at']        : $last_status->accStatus_3_processed_at; 
                    $resultArray['accStatus_3_processed_by_user']    =  isset($row['accStatus_3_processed_at']) ?  $row['accStatus_3_processed_by_user']   : $last_status->accStatus_3_processed_by_user; 
                    $resultArray['accStatus_4_processed_at']         =  isset($row['accStatus_4_processed_at']) ?  $row['accStatus_4_processed_at']        : $last_status->accStatus_4_processed_by_user; 
                    $resultArray['accStatus_4_processed_by_user']    =  isset($row['accStatus_4_processed_at']) ?  $row['accStatus_4_processed_by_user']   : $last_status->accStatus_4_processed_at; 
                    $resultArray['accStatus_5_processed_at']         =  isset($row['accStatus_5_processed_at']) ?  $row['accStatus_5_processed_at']        : $last_status->accStatus_5_processed_by_user; 
                    $resultArray['accStatus_5_processed_by_user']    =  isset($row['accStatus_5_processed_at']) ?  $row['accStatus_5_processed_by_user']   : $last_status->accStatus_5_processed_at; 
                    $resultArray['last_updated'] =$currentTimestamp;
                    
                    $last_status = $this->db->query("SELECT * FROM tr_status_ev_acc WHERE serialNo = '$serial_number' and accStatus='$acc' ");
                    if ($last_status->num_rows() == 0) {
                        $this->db->insert('tr_status_ev_acc', $resultArray);
                        $this->db->insert('ev_log_send_api_3', $history);
                    }
                 }

                    $mesaage = array(
                            "message" => "Berhasil Ditambahkan",
                    );

                    $responese['status'] = '1';
                    $responese['data'] =  $mesaage;

                return $responese;

    }





       
}