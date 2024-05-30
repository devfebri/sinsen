<?php
class CRM_leads_model_create extends CI_Model
{
  var $sourceRefID = [];
  public function __construct()
  {
    parent::__construct();

    $this->load->model('CRM_leads_model_create_sub', 'crm_lead');
    $this->load->model('CRM_wilayah', 'crm_wilayah');
    $this->load->model('CRM_leads_model', 'ld_m');
    $this->load->helper('crm_helper');  
  }
  
	function cari_id()
	{
	//$tgl				= $this->input->post('tgl');
		$th 				= date("y");
		$bln 				= date("m");
		$tgl 				= date("d");
		$tahun = date("Y");
		$dealer 		= $this->session->userdata("id_karyawan_dealer");
		$id_dealer 		= $this->m_admin->cari_dealer();
		$isi 				= $this->db->query("SELECT kode_dealer_md FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();
		$kode_dealer 		= $isi->kode_dealer_md;

    // 2023-11-18 : order diganti dari id_prospek menjadi created_at
		$pr_num 			= $this->db->query("SELECT id_prospek FROM tr_prospek WHERE id_dealer = '$id_dealer' and left(created_at,4) = '$tahun' ORDER BY created_at DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_prospek) - 5;
			$id 	= substr($row->id_prospek, $pan, 5) + 1;
			if ($id < 10) {
				$kode1 = $th . $bln . $tgl . "0000" . $id;
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . $bln . $tgl . "000" . $id;
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . $bln . $tgl . "00" . $id;
			} elseif ($id > 999) {
				$kode1 = $th . $bln . $tgl . "0" . $id;
			}
			$kode = $kode_dealer . $kode1;
		} else {
			$kode = $kode_dealer . $th . $bln . $tgl . "00001";
		}
		//$rt = rand(1111,9999);
		$rt = $this->m_admin->get_customer();
		$id_dealer      = $this->m_admin->cari_dealer();
		$get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer'");
		if ($get_dealer->num_rows() > 0) {
			$get_dealer = $get_dealer->row()->kode_dealer_md;
			$panjang = strlen($get_dealer);
		} else {
			$get_dealer = '';
			$panjang = '';
		}
		$tgl						= $this->input->post('tgl');
		$th 						= date("y");
		$waktu 					= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$pr_num 				= $this->db->query("SELECT id_list_appointment FROM tr_prospek WHERE RIGHT(id_list_appointment,$panjang) = '$get_dealer' and left(created_at,4) = '$tahun' ORDER BY created_at DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_list_appointment) - ($panjang + 6);
			$id 	= substr($row->id_list_appointment, $pan, 5) + 1;
			if ($id < 10) {
				$kode1 = $th . "0000" . $id;
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . "000" . $id;
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . "00" . $id;
			} elseif ($id > 999) {
				$kode1 = $th . "0" . $id;
			}
			$kode2 = "PR" . $kode1 . "-" . $get_dealer;
		} else {
			$kode2 = "PR" . $th . "00001-" . $get_dealer;
		}
		return array('kode' => $kode, 'rt' => $rt, 'kode2' => $kode2);
	}



  function leads_id(){

      $id_dealer     = $this->m_admin->cari_dealer();
      $dealer_md   = $this->db->query("select kode_dealer_md from ms_dealer WHERE id_dealer = '$id_dealer'")->row();
      $dmys            = date("Y-m-d");
      $dmys_id_created = date("dmy");
      $get_data  = $this->db_crm->query("SELECT ls.assignedDealer,ls.leads_id from leads ls 
      left join ms_dealer md on md.kode_dealer = ls.assignedDealer 
      WHERE ls.platformData ='D'  AND left(ls.created_at ,10) = '$dmys' AND  ls.assignedDealer='$dealer_md->kode_dealer_md' order BY ls.leads_id_int desc limit 1")->row();
  
      if (count($get_data)>0){
        $string =  $get_data->leads_id;
        $parts = explode('/', $string);
        $lastPart = end($parts);
        $get_lead_id = explode('-', $lastPart)[0];
        $new_kode = 'E20/' . $dmys_id_created . '/' . sprintf("%'.06d", $get_lead_id + 1).'-'.$dealer_md->kode_dealer_md;
      }else{
        $leads_set_awal=NULL;
        $new_kode = 'E20/' . $dmys_id_created . '/' . sprintf("%'.06d", $leads_set_awal + 1).'-'.$dealer_md->kode_dealer_md;
      }
       return $new_kode ;

  }

  function assign_from_sales_people_crm($id){

    $now                 = waktu();

    $query = $this->db->query("SELECT id_prospek ,leads_id  from tr_prospek where id_prospek ='$id' and input_from='ldd' ")->row();

    if ($query) {

      $query_check = $this->db_crm->query("SELECT leads_id from leads_history_stage WHERE leads_id ='$query->leads_id' and stageId='5'")->row();

      if (!$query_check) {
        
          $ins_lead_history_stage_5 = [
          'id_int' =>  NULL,
          'leads_id' => $query->leads_id,
          'stageId' =>  5,
          'created_at' => $now,
          'sending_to_ahm_at ' =>  NULL,
          'path_file ' =>  NULL,
          'no_spk ' =>  NULL,
          'followUpID ' => NULL,
        ];

          $this->db_crm->query("SET FOREIGN_KEY_CHECKS=0"); 
          $this->db_crm->insert('leads_history_stage', $ins_lead_history_stage_5);
          $this->db_crm->query("SET FOREIGN_KEY_CHECKS=1");


            if ($this->db_crm->trans_status() === FALSE) {
              $variable = $this->db_crm->trans_rollback();
            } else 
            {
              $variable = $this->db_crm->trans_commit();
            }

            return  $variable;
      }

    } 

  }
  

  function AssignSalesPeopleStaging($prospek)
  {
    // $id_dealer     = $this->m_admin->cari_dealer();

    // foreach ($prospek as $key => $customer) {
    //   $result[] = array(
    //     'id_claim' 			  => $val->id_claim,
    //     'send_dealer'         =>  1,
    //     'send_dealer_date'    => $waktu ,
    //     'send_dealer_status'  => 'send',
    //     );
    // }

    // $this->db->update_batch($tabel2, $result, 'id_claim');

    //     if($id_dealer == 103){
    //     var_dump($prospek);
    //     die();
    // }

  }


  function inject()
  {
    $crm= $this->db_crm->query("SELECT * from leads WHERE LENGTH(leads_id) >21  and LEFT(created_at,10) BETWEEN '2023-10-01' and '2023-10-31' and assignedDealer ='07781'")->result();


    foreach ($crm as $key => $pst) {

      $insert_prospek['id_prospek'] =  $pst->leads;
   
      // $modifiedStringProspek = 


    // $data['id_prospek'] 		= $modifiedStringProspek;
    // $insert_prospek['created_at']           = $now ;
    // $insert_prospek['created_by']           = NULL;
    // $insert_prospek['customer_image']       = NULL ;
    // $insert_prospek['customerType']         = $customerType ;
    // $insert_prospek['deskripsiEvent']       = $deskripsiEvent;
    // $insert_prospek['digunakan']            = NULL ;
    // $insert_prospek['email']                = $email;
    // $insert_prospek['facebook']             = clear_removed_html($pst['facebook']);
    // $insert_prospek['grand_total']          = NULL  ;
    // $insert_prospek['id_customer']          = $set_list_appoitment['rt'] ;
    // $insert_prospek['id_dealer']            = $dealer ;
    // $insert_prospek['id_event']             =  NULL;
    // $insert_prospek['id_flp_md']            =  $id_flp_md_assign ;
    // $insert_prospek['id_kabupaten']         = $id_kabupaten;
    // $insert_prospek['id_karyawan_dealer']   = $id_karyawan_dealer_assign;
    // $insert_prospek['id_kecamatan']         = $id_kecamatan;
    // $insert_prospek['id_kelurahan']         = $id_kelurahan;
    // $insert_prospek['id_kelurahan']         = $id_kelurahan;
    // $insert_prospek['id_kelurahan_kantor']  = NULL;
    // $insert_prospek['id_list_appointment']  = $new_list_appoitment;
    // $insert_prospek['id_prospek']           = $modifiedStringProspek ;
    // $insert_prospek['id_provinsi']          = $id_provinsi;
    // $insert_prospek['id_reasons']           = NULL;
    // $insert_prospek['id_tipe_kendaraan']    = $kodeTypeUnit  ? NULL : NULL ;
    // $insert_prospek['id_warna']             = $kodeWarnaUnit ? NULL : NULL ;
    // $insert_prospek['input_from']           = 'ldd';
    // $insert_prospek['instagram']            =  clear_removed_html($pst['instagram']);
    // $insert_prospek['jam_tes_kendaraan']    =  clear_removed_html($pst['jadwalRidingTest']) == '' ? NULL : clear_removed_html($pst['jadwalRidingTest']); 
    // $insert_prospek['leads_id']             = $leads_id;
    // $insert_prospek['longitude']            = NULL;
    // $insert_prospek['merk_sebelumnya']      = NULL;
    // $insert_prospek['metode_follow_up_id']  = NULL;
    // $insert_prospek['nama_konsumen']        = $nama;
    // $insert_prospek['no_hp']                = $noHP ;
    // $insert_prospek['no_telp']              = $noTelp ?: null;
    // $insert_prospek['platformData']         = $platformData;
    // $insert_prospek['sumber_prospek']       = $sourceDataprospek;
    // $insert_prospek['twitter']              = clear_removed_html($pst['twitter']);
    // $insert_prospek['tgl_prospek']          = $tanggal_prospek;
    // $insert_prospek['ontimeSLA2']           = 1;
    // $insert_prospek['noFramePembelianSebelumnya'] = strtoupper(clear_removed_html($pst['noFramePembelianSebelumnya']));

       }

       

  }




  function insertStagingTables($post)
  {

    $now                 = waktu();
    $batchID             = $this->crm_lead->getBatchID();
    $validation_initial  = $post;

    $prospek_array = array(); 
    $stagingDataArray = array(); 
    $lead_history_stage_1_array = array(); 
    $lead_history_stage_5_array  =array(); 
    $lead_array  = array(); 
    $leads_history_assigned_dealer_array =array(); 
    $sub_array_validation   = array();
    
    $id_dealer     = $this->m_admin->cari_dealer();
    $dealer_md   = $this->db->query("select kode_dealer_md from ms_dealer WHERE id_dealer = '$id_dealer'")->row();
    
    $set_leads_id  = $this->leads_id();
    $check_no_hp_duplicate = array(); 

    $phoneNumbers = array();
    $noFrame = array();
    $email_ = array();

    foreach ($post as $key => $customer) {

        if (isset($customer['noHP'])) {
            $phoneNumbers[] = $customer['noHP']; 
            $noFrame[] = $customer['noFramePembelianSebelumnya']; 
        }

        if (isset($customer['noHP']) && !in_array(substr($customer['email'], 0, 1), ['N',''])) {
          $email_[] = $customer['email']; 
        }

      //   if (isset($customer['assignSalesPeople'])) {
      //     $phoneNumbers[] = $customer['assignSalesPeople']; 
      //     $flp_sales_people[] = $customer['assignSalesPeople']; 
      // }

    }
    
    $phoneCount = array_count_values($phoneNumbers);
    $noFrameCount = array_count_values($noFrame);
    $emailCount = array_count_values($email_);
    
    $duplicateValuesHp = array_filter($phoneCount, function($countHp) {
        return $countHp > 1;
    });
  
  //   $duplicateNoFrame = array_filter($noFrameCount, function($countFrame) {
  //     return $countFrame > 1;
  // });

    $duplicateEmail= array_filter($emailCount, function($countEmail) {
      return $countEmail > 1;
  });

    
    if (!empty($duplicateValuesHp)) {
        $sub_array = array(); 
        $sub_array['array-line'] = 404;
        $errMsg = "Duplicate Data No HP pada File Excel";
        $sub_array[] = $errMsg;
        $sub_array_validation[] = $sub_array;
        $output = '';
        $output .= "<ol>";

        foreach ($duplicateValuesHp as $value => $countHp) {
            $output .= "<li style='text-align: center'>$value        (Jumlah:<b> $countHp </b>)</li>";
        }

        $output .= "</ol>";
        $sub_array = array(); 
        $sub_array['array-line'] = 404;
        $sub_array[] = $output;
        $sub_array_validation[] = $sub_array;
  
        // if (count($duplicateEmail) !== 0 ){

        //   $sub_array = array(); 
        //   $sub_array['array-line'] = 404;
        //   $errMsg = "Duplicate No Rangka ";
        //   $sub_array[] = $errMsg;
        //   $sub_array_validation[] = $sub_array;
        //   $output = '';
        //   $output .= "<ol>";

        //   foreach ($duplicateEmail as $value => $countEmail) {
        //       $output .= "<li style='text-align: center'>$value        (Jumlah:<b> $countEmail </b>)</li>";
        //   }

        //   $output .= "</ol>";
        //   $sub_array = array(); 
        //   $sub_array['array-line'] = 404;
        //   $sub_array[] = $output;
        //   $sub_array_validation[] = $sub_array;
        // }

        // if (count($duplicateNoFrame) !== 0 ){
        //   $sub_array = array(); 
        //   $sub_array['array-line'] = 404;
        //   $errMsg = "Duplicate No Rangka ";
        //   $sub_array[] = $errMsg;
        //   $sub_array_validation[] = $sub_array;
        //   $output = '';
        //   $output .= "<ol>";
        //   foreach ($duplicateNoFrame as $value => $countFrame) {
        //       $output .= "<li style='text-align: center'>$value        (Jumlah:<b> $countFrame </b>)</li>";
        //   }
        //   $output .= "</ol>";
        //   $sub_array = array(); 
        //   $sub_array['array-line'] = 404;
        //   $sub_array[] = $output;
        //   $sub_array_validation[] = $sub_array;
        // }
      

        if (count($post) > 50 ){
          $sub_array= array(); 
          $sub_array['array-line'] = 404;
          $errMsg = "(Jumlah Data ".count($post)."), Max Upload 50 Data";
          $sub_array[] = $errMsg;
          $sub_array_validation[] =$sub_array;
        }
     
    }


    foreach ($validation_initial as $key => $pst) {
      $sub_array= array(); 

      // if($key == 0){
      //   $leads_id_set = $set_leads_id;
        
      //           if(count($pst['assignSalesPeople']) !== 0){

      //             if ((strlen(clear_removed_html($pst['assignSalesPeople'])) <6) && (strlen(clear_removed_html($pst['assignSalesPeople'])) > 6)) {
      //               $sub_array= array(); 
      //               $sub_array['array-line'] = 404;
      //               $errMsg = "Mohon Periksa Jumlah Karakter Honda ID  ";
      //               $sub_array[] = $errMsg;

      //             }else{

      //               $flp     = clear_removed_html($pst['assignSalesPeople']);
      //               $flp_get = $this->db->query("SELECT id_karyawan_dealer,id_flp_md, id_dealer  from ms_karyawan_dealer WHERE active ='1' AND id_flp_md ='$flp' ")->row();

      //                 if(isset($flp_get->id_flp_md)){
      //                   $id_flp_md_assign          = $flp_get->id_flp_md;
      //                 }else{
      //                   $id_flp_md_assign          = NULL;
      //                 }

      //                 if(isset($flp_get->id_flp_md)){
      //                   $id_karyawan_dealer_assign = $flp_get->id_flp_md;
      //                 }else{
      //                   $id_karyawan_dealer_assign = NULL;
      //                 }
      //             }
      //         }else{
      //               $id_flp_md_assign          = NULL;
      //               $id_karyawan_dealer_assign = NULL;
      //         }
      //       }

                if(count($pst['assignSalesPeople']) !== 0){

                  if ((strlen(clear_removed_html($pst['assignSalesPeople'])) <6) && (strlen(clear_removed_html($pst['assignSalesPeople'])) > 6)) {
                    $errMsg = "Mohon Periksa Jumlah Karakter Honda ID  ";
                    $sub_array[] = $errMsg;

                  }else{
                    $dealer_flp_check      = $this->m_admin->cari_dealer();
                    $flp     = clear_removed_html($pst['assignSalesPeople']);
                    $flp_get = $this->db->query("SELECT id_karyawan_dealer, id_flp_md,id_dealer  
                    from ms_karyawan_dealer WHERE active ='1' AND id_flp_md ='$flp' AND id_dealer ='$dealer_flp_check' ")->row();

                    if ($flp_get !== null) {
                      if(isset($flp_get->id_karyawan_dealer)){
                        $id_flp_md_assign = $flp_get->id_flp_md;

                        if ( $id_flp_md_assign == NULL or $id_flp_md_assign== ''){
                          $errMsg = 'Honda ID tidak Ditemukan '.$id_flp_md_assign;
                          $sub_array[] = $errMsg;
                        }
                        
                        if(isset($flp_get->id_flp_md)){
                          $id_flp_md_assign = $flp_get->id_flp_md;
                          $id_karyawan_dealer_assign = $flp_get->id_karyawan_dealer;
                        }
                      }
                    } else {
                      $id_flp_md_assign          = NULL;
                      $id_karyawan_dealer_assign = NULL;
                    }
                  }
                }

              $input =  $set_leads_id;
              $parts = explode('/', $input);
              $lastPart = end($parts);
              list($beforeHyphen, $afterHyphen) = explode('-', $lastPart);
              $newNumericPart = str_pad((int)$beforeHyphen + $key, strlen($beforeHyphen), '0', STR_PAD_LEFT);
              $newString = implode('/', array_slice($parts, 0, -1)) . '/' . $newNumericPart . '-' . $afterHyphen;
              $leads_id_set = $newString;

              $set_list_appoitment = $this->cari_id();  
              $leads_id = $leads_id_set;

              $initial_string_list = $set_list_appoitment['kode2'];
              $numericList = intval(substr($initial_string_list, 2, 7));
              $incrementedValueList = $numericList + $key;
              $formattedValueList = str_pad($incrementedValueList, 7, "0", STR_PAD_LEFT);
              $new_list_appoitment = "PR{$formattedValueList}-$dealer_md->kode_dealer_md";

              $check_noframe_dealer = clear_removed_html($pst['noFramePembelianSebelumnya']);
              $noHP = clean_no_hp($pst['noHP']);
            
              $wilayah['kelurahan']  =  clear_removed_html($pst['kelurahan']);
              $wilayah['kecamatan']  =  clear_removed_html($pst['kecamatan']);
              $wilayah['kabupaten']  =  clear_removed_html($pst['kabupaten']);
              $wilayah['provinsi']   =  clear_removed_html($pst['provinsi']);

          //  $validation_no_hp   = $this->db_crm->query("select noHp,email,leads_id from leads WHERE noHp = '$noHP' limit 1")->row();
          
          $validasi_wilayah = $this->crm_wilayah->get_provinsi($wilayah);

          $check_no_hp_duplicate [] = $noHP;
          // WILAYAAH
          if (count($validasi_wilayah) == 0 ){
            $errMsg = "Wilayah pada inputan tidak ditemukan";
            $sub_array[] = $errMsg;
          }
            // WILAYAAH

            if (clear_removed_html($pst['assignedDealer']) == NULL or clear_removed_html($pst['assignedDealer']) == ''){
              $errMsg = 'assignedDealer kosong ';
              $sub_array[] = $errMsg;
            }

            // HP
            
            //  if(isset($validation_no_hp->noHp)){
            //    $errMsg = 'No Hp sudah Ada Pada sistem '.$validation_no_hp->noHp;
            //    $sub_array[] = $errMsg;
            //  }

            if ($noHP == '') {
              $errMsg = 'No. HP Wajib Diisi';
              $sub_array[] = $errMsg;
            } 
            
            if (strlen($noHP) > 15) {
              $errMsg = "Jumlah karakter No. HP melebihi batas ";
              $sub_array[] = $errMsg;
            } elseif (strlen($noHP) < 10) {
              $errMsg = 'Jumlah karakter No. HP kurang';
              $sub_array[] = $errMsg;
            } 

            // PLATFORM
            $checkplatformData    =  clear_removed_html($pst['platformData']);
            if ($checkplatformData !== 'D') {
                $errMsg = 'Mohon periksa kembali platform data';
                $sub_array[] = $errMsg;
            }

     
            $checksourceData     =  clear_removed_html($pst['sourceData']);

            if ($checksourceData == 47 || $checksourceData == 48 || $checksourceData == 49 || $checksourceData == 50 || $checksourceData == 12) {
            //  if (($checksourceData >= 47) && ($checksourceData <= 50)) {

                  if($checksourceData == 47){
                    $get_noframe    = clear_removed_html($pst['noFramePembelianSebelumnya']);
                    $get_kodedealer = clear_removed_html($pst['kodeDealerSebelumnya']);

                      if( $get_noframe  == ''  || $get_kodedealer  == '')  {
                        $errMsg = "Mohon pada Data LOL - Inputkan noFramePembelianSebelumnya dan kodeDealerSebelumnya ";
                        $sub_array[] =  $errMsg;
                      }

                      $check_noframe_dealer =substr($pst['noFramePembelianSebelumnya'],0,3);
                      $check_noframe_message = clear_removed_html($pst['noFramePembelianSebelumnya']);
                  
                      if ( $check_noframe_message !== NULL or $check_noframe_message !== ''){

                          if ( $check_noframe_dealer == 'MH1'){
                            $errMsg = "Mohon Check Format No rangka  ($check_noframe_message)";
                            $sub_array[] = $errMsg;
                          }
                  
                          if (strlen($check_noframe_message) > 14) {
                            $errMsg = "No rangka Lebih dari 14 Karakter ($check_noframe_message) ";
                            $sub_array[] = $pst['check_no_rangka_char'] = $errMsg;
                          }
                      
                          if (strpos($check_noframe_message, '-') !== false) {
                            $errMsg = "Pada No Rangk Ada karakter '-'  ".($check_noframe_message);
                            $sub_array[] = $errMsg;
                          }

                                  //   if (preg_match('/[a-z]/', $check_noframe_message)) {
                        //     $errMsg = "Mohon Check Format No rangka, Harus Huruf Kapital :  ($check_noframe_message)";
                        //       $sub_array[] = $errMsg;
                        //  } 


                          if (strpos($check_noframe_message, ' ') !== false) {
                            $errMsg = 'Pada No Rangk Ada karakter spasi " "'.($check_noframe_message);
                            $sub_array[] = $errMsg;
                          }
                        }
                  }
                  

          // if($checksourceData == 48  || $checksourceData  == 49 ||$checksourceData  == 50  ){
            if ($checksourceData == 47 || $checksourceData == 48 || $checksourceData == 49 || $checksourceData == 50 || $checksourceData == 12) {
            $get_noframe    = clear_removed_html($pst['noFramePembelianSebelumnya']);
            $get_kodedealer = clear_removed_html($pst['kodeDealerSebelumnya']);


              $check_noframe_dealer =substr($pst['noFramePembelianSebelumnya'],0,3);
              $check_noframe_message = clear_removed_html($pst['noFramePembelianSebelumnya']);
          
              if ( $check_noframe_message !== NULL or $check_noframe_message !== ''){

                  if ( $check_noframe_dealer == 'MH1'){
                    $errMsg = "Mohon Check Format No rangka  ($check_noframe_message)";
                    $sub_array[] = $errMsg;
                  }

                //   if (preg_match('/[a-z]/', $check_noframe_message)) {
                //     $errMsg = "Mohon Check Format No rangka, Harus Huruf Kapital :  ($check_noframe_message)";
                //       $sub_array[] = $errMsg;
                //  } 
          
                  if (strlen($check_noframe_message) > 14) {
                   $errMsg = "No rangka Lebih dari 14 Karakter ($check_noframe_message) ";
                   $sub_array[] = $pst['check_no_rangka_char'] = $errMsg;
                  }
             
                  if (strpos($check_noframe_message, '-') !== false) {
                    $errMsg = "Pada No Rangk Ada karakter '-'  ".($check_noframe_message);
                    $sub_array[] = $errMsg;
                  }
          
                  if (strpos($check_noframe_message, ' ') !== false) {
                    $errMsg = 'Pada No Rangk Ada karakter spasi " "'.($check_noframe_message);
                    $sub_array[] = $errMsg;
                  }
               }
         }


     }else{
       $errMsg = 'Mohon periksa kembali source data inputan adalah (12,47,48,49,50)';
       $sub_array[] = $pst['source_data'] = $errMsg;
     }
     // SOURCEDATA
    //  END VALIDATION
      $check_email = clear_removed_html($pst['email']);
      if ($check_email != '' or $check_email=NULL) {
        $email = NULL;
        $email_leads= NULL;
        $email_staging = NULL;
      }else{
        $email = NULL; 
        $email_staging = NULL; 
        $email_leads = NULL; 
      }
      
      $noHP = clean_no_hp($pst['noHP']);
      $tanggal_prospek =  date("Y-m-d");
       //Cek customerActionDate

       $customerActionDate = date_iso_8601_to_datetime(clear_removed_html($pst['customerActionDate']));

       if ($pst['customerActionDate'] == '') {
        $errMsg = 'Customer Action Date Wajib Diisi';
        $sub_array[] = $errMsg;
      } else {
        //Cek CustomerActionDate
        $customerActionDate = clear_removed_html($pst['customerActionDate']);
        if (cekISO8601Date($customerActionDate)) {
          $customerActionDate = date_iso_8601_to_datetime(clear_removed_html($pst['customerActionDate']));
        } else {
          $errMsg = 'Format Customer Action Date tidak valid';
          $sub_array[] = $errMsg;
        }

        $customerActionDate = date_iso_8601_to_datetime(clear_removed_html($pst['customerActionDate']));
        $selisih = selisih_detik($customerActionDate, $now);

        if ($selisih < 0) {
          $errMsg = 'Customer Action Date Lebih Besar Dari Tanggal Sekarang';
          $sub_array[] = $errMsg;
        }
        
        //   elseif ($selisih > 172800) {
        //     $errMsg = 'Customer Action Date Harus Dalam 2 Hari Dari Tanggal Sekarang';
        //     $sub_array[] = $errMsg;
        // }

      }

    
      $wilayah['kelurahan']  =  clear_removed_html($pst['kelurahan']);
      $wilayah['kecamatan']  =  clear_removed_html($pst['kecamatan']);
      $wilayah['kabupaten']  =  clear_removed_html($pst['kabupaten']);
      $wilayah['provinsi']   =  clear_removed_html($pst['provinsi']);
      $wilayah_validasi = $this->crm_wilayah->get_provinsi($wilayah);

      if (clear_removed_html($pst['kelurahan']) !== '') {
        $id_kelurahan=$wilayah_validasi->id_kelurahan;
      }else{
        $id_kelurahan='';
      }

      if (clear_removed_html($pst['kecamatan']) !== '') {
        $id_kecamatan=$wilayah_validasi->id_kecamatan;
      }else{
        $id_kecamatan='';
      }

      if (clear_removed_html($pst['kabupaten']) !== '') {
        $id_kabupaten=$wilayah_validasi->id_kabupaten;
      }else{
        $id_kabupaten='';
      }

      if (clear_removed_html($pst['provinsi']) !== '') {
        $id_provinsi=$wilayah_validasi->id_provinsi;
      }else{
        $id_provinsi='';
      }

      $customerType = clear_removed_html($pst['customerType']);
      if ($customerType != 'V') {
        $customerType = 'R';
      }

      $eventCodeInvitation =  clear_removed_html($pst['eventCodeInvitation']);
      $cmsSource           =  5;
      $segmentMotor        =  clear_removed_html($pst['segmentMotor']);
      $seriesMotor         =  clear_removed_html($pst['seriesMotor']);
      $deskripsiEvent      =  clear_removed_html($pst['deskripsiEvent']);
      $kodeTypeUnit        =  clear_removed_html($pst['kodeTypeUnit']);
      $kodeWarnaUnit       =  clear_removed_html($pst['kodeWarnaUnit']);
      $noTelp              =  clear_removed_html($pst['noTelp']);
      
      $dealer      = $this->m_admin->cari_dealer();
      $dealer_md   = $this->db->query("select kode_dealer_md  from ms_dealer WHERE id_dealer = '$dealer'")->row();
      
      if (clear_removed_html($pst['assignedDealer']) !== '') {

        $assigneds                  =  clear_removed_html($pst['assignedDealer']);
        $validate_kode_dealer =  $this->db->query("select kode_dealer_md  from ms_dealer WHERE kode_dealer_md = '$assigneds'")->row();

        if (!is_null($validate_kode_dealer)) {
               $assignedDealer       =  clear_removed_html($pst['assignedDealer']);
          } else {
              $errMsg = 'AssignedDealer Tidak ditemukan';
              $sub_array[] = $errMsg;
          }

      }else{
        $assignedDealer     = $dealer_md->kode_dealer_md;
      }
      
      $checksourceData     =  clear_removed_html($pst['sourceData']);
      // if (($checksourceData >= 47) && ($checksourceData <= 50)) {

        if ($checksourceData == 47 || $checksourceData == 48 || $checksourceData == 49 || $checksourceData == 50 || $checksourceData == 12) {

          if ($checksourceData == '48' ){
            $sourceDataprospek =  '0046';

            $sumberProspek =  'CRM Leads H1 - H1';
          } else if ($checksourceData == '49' ){
            $sourceDataprospek =  '0047';
            $sumberProspek =  'CRM Leads H2 Own - H1 ';

          } else if ($checksourceData == '50' ){
            $sourceDataprospek = '0048';
            $sumberProspek =  'CRM Leads H2 Other - H1 ';


          } else if ($checksourceData == '47' ){
            $sourceDataprospek = '0042';
            $sumberProspek =  'LoL';

          }else if ($checksourceData == '12' ){
            $sourceDataprospek = '0010';
            $sumberProspek =  'Mobile App MD/Dealer';
          }

            $sourceData =  $checksourceData ;
        }
   
        $platformData                   =  clear_removed_html($pst['platformData']);

        // $kodeDealerPembelianSebelumnya  = clear_removed_html($pst['kodeDealerSebelumnya']);

        if (clear_removed_html($pst['kodeDealerSebelumnya']) !== '') {

          $dealerSebelumnya                  =  clear_removed_html($pst['kodeDealerSebelumnya']);
          $validate_kode_dealer_sebelumnya =  $this->db->query("select kode_dealer_md  from ms_dealer WHERE kode_dealer_md = '$dealerSebelumnya'")->row();
  
          if (!is_null($validate_kode_dealer_sebelumnya)) {
                 $kodeDealerPembelianSebelumnya       =  clear_removed_html($pst['assignedDealer']);
            } else {
                $errMsg = 'kodeDealerSebelumnya Tidak ditemukan';
                $sub_array[] = $errMsg;
            }
  
        }else{
          $kodeDealerPembelianSebelumnya     = $dealer_md->kode_dealer_md;
        }

        $sourceRefID                    = clear_removed_html($pst['sourceRefID']);
        $nama_get                       = clear_removed_html($pst['nama']);
        $nama = strtoupper($nama_get);
        $get_id_prospeks                = $this->m_prospek->getIDProspek($sourceDataprospek);

        $parts_prospek = explode('/', $get_id_prospeks);
        $prospekNum = $parts_prospek[6];
        $incrementedProspekNum = sprintf('%05d', intval($prospekNum) + $key);
        $parts_prospek[6] = $incrementedProspekNum;
        $modifiedStringProspek = implode('/', $parts_prospek);

      $data['id_prospek'] 		= $modifiedStringProspek;
      $insert_prospek['created_at']           = $now ;
      $insert_prospek['created_by']           = NULL;
      $insert_prospek['customer_image']       = NULL ;
      $insert_prospek['customerType']         = $customerType ;
      $insert_prospek['deskripsiEvent']       = $deskripsiEvent;
      $insert_prospek['digunakan']            = NULL ;
      $insert_prospek['email']                = $email;
      $insert_prospek['facebook']             = clear_removed_html($pst['facebook']);
      $insert_prospek['grand_total']          = NULL  ;
      $insert_prospek['id_customer']          = $set_list_appoitment['rt'] ;
      $insert_prospek['id_dealer']            = $dealer ;
      $insert_prospek['id_event']             = NULL;
      $insert_prospek['id_flp_md']            = $id_flp_md_assign ;
      $insert_prospek['id_kabupaten']         = $id_kabupaten;
      $insert_prospek['id_karyawan_dealer']   = $id_karyawan_dealer_assign;
      $insert_prospek['id_kecamatan']         = $id_kecamatan;
      $insert_prospek['id_kelurahan']         = $id_kelurahan;
      $insert_prospek['id_kelurahan']         = $id_kelurahan;
      $insert_prospek['id_kelurahan_kantor']  = NULL;
      $insert_prospek['id_list_appointment']  = $new_list_appoitment;
      $insert_prospek['id_prospek']           = $modifiedStringProspek ;
      $insert_prospek['id_provinsi']          = $id_provinsi;
      $insert_prospek['id_reasons']           = NULL;
      $insert_prospek['id_tipe_kendaraan']    = $kodeTypeUnit  ? NULL : NULL ;
      $insert_prospek['id_warna']             = $kodeWarnaUnit ? NULL : NULL ;
      $insert_prospek['input_from']           = 'ldd';
      $insert_prospek['instagram']            =  clear_removed_html($pst['instagram']);
      $insert_prospek['jam_tes_kendaraan']    =  clear_removed_html($pst['jadwalRidingTest']) == '' ? NULL : clear_removed_html($pst['jadwalRidingTest']); 
      $insert_prospek['leads_id']             = $leads_id;
      $insert_prospek['longitude']            = NULL;
      $insert_prospek['merk_sebelumnya']      = NULL;
      $insert_prospek['metode_follow_up_id']  = NULL;
      $insert_prospek['nama_konsumen']        = strtoupper($nama);
      $insert_prospek['no_hp']                = $noHP ;
      $insert_prospek['no_telp']              = $noTelp ?: null;
      $insert_prospek['platformData']         = $platformData;
      $insert_prospek['sumber_prospek']       = $sourceDataprospek;
      $insert_prospek['twitter']              = clear_removed_html($pst['twitter']);
      $insert_prospek['tgl_prospek']          = $tanggal_prospek;
      $insert_prospek['ontimeSLA2']           = 1;
      $insert_prospek['noFramePembelianSebelumnya'] = strtoupper(clear_removed_html($pst['noFramePembelianSebelumnya']));
      

      $ins_staging = [
        'batchID' => $batchID,
        'nama' => strtoupper($nama),
        'noHP' => $noHP,
        'email' => $email_staging,
        'customerType' => $customerType,
        'eventCodeInvitation' => $eventCodeInvitation,
        'customerActionDate' => $customerActionDate,
        'kabupaten' => $id_kabupaten,
        'provinsi' => $id_provinsi,
        'cmsSource' => $cmsSource,
        'segmentMotor' => $segmentMotor,
        'seriesMotor' => $seriesMotor,
        'deskripsiEvent' => $deskripsiEvent,
        'kodeTypeUnit' => $kodeTypeUnit,
        'kodeWarnaUnit' => $kodeWarnaUnit,
        'minatRidingTest' => clear_removed_html($pst['minatRidingTest']),
        'jadwalRidingTest' => clear_removed_html($pst['jadwalRidingTest']) == '' ? NULL : clear_removed_html($pst['jadwalRidingTest']),
        'sourceData' => $sourceData,
        'platformData' => $platformData,
        'noTelp' => $noTelp ?: null,
        'assignedDealer' => $assignedDealer,
        'sourceRefID' => $sourceRefID,
        'provinsi' => $id_provinsi,
        'kelurahan' => $id_kelurahan,
        'kecamatan' => $id_kecamatan,
        'noFramePembelianSebelumnya' => strtoupper(clear_removed_html($pst['noFramePembelianSebelumnya'])),
        'keterangan' => clear_removed_html($pst['keterangan']),
        'promoUnit' => clear_removed_html($pst['promoUnit']),
        'facebook' => clear_removed_html($pst['facebook']),
        'instagram' => clear_removed_html($pst['instagram']),
        'twitter' => clear_removed_html($pst['twitter']),
        'created_at' => $now ,
        'setleads' => 1,
        'sumber_data' => 'ldd'
      ];

      if (isset($periodeAwalEvent)) {
        $ins_staging['periodeAwalEvent'] = $periodeAwalEvent;
      }

      $ins_lead_history_stage_1 = [
        'id_int' =>  NULL,
        'leads_id' =>  $leads_id,
        'stageId' =>  1,
        'created_at' => $now,
        'sending_to_ahm_at ' =>  NULL,
        'path_file ' =>  NULL,
        'no_spk ' =>  NULL,
        'followUpID ' => NULL,
      ];

      $ins_lead_history_stage_5 = [
        'id_int' =>  NULL,
        'leads_id' =>  $leads_id,
        'stageId' =>  5,
        'created_at' => $now,
        'sending_to_ahm_at ' =>  NULL,
        'path_file ' =>  NULL,
        'no_spk ' =>  NULL,
        'followUpID ' => NULL,
      ];

      $ins_lead = [
        'leads_id' =>  $leads_id,
        'batchID' =>  $batchID,
        'nama' =>    strtoupper($nama),
        'noHP' => $noHP,
        'jadwalRidingTest' => clear_removed_html($pst['jadwalRidingTest']) == '' ? NULL : clear_removed_html($pst['jadwalRidingTest']),
        'email' => $email_leads,
        'customerType' => $customerType ,
        'eventCodeInvitation' => NULL,
        'customerActionDate' => $customerActionDate,
        'kabupaten' => $id_kabupaten,
        'cmsSource' => $cmsSource,
        'segmentMotor' => $segmentMotor,
        'seriesMotor' => $seriesMotor,
        'deskripsiEvent' => $deskripsiEvent,
        'kodeTypeUnit' => $kodeTypeUnit,
        'kodeWarnaUnit' => $kodeWarnaUnit,
        'minatRidingTest' => clear_removed_html($pst['minatRidingTest']),
        'jadwalRidingTest' => NULL,
        'sourceData' => $sourceData,
        'platformData' =>$platformData,
        'noTelp' => NULL,
        'assignedDealer' => $assignedDealer,
        'sourceRefID' => $sourceRefID,
        'provinsi' => $id_provinsi,
        'kelurahan' => $id_kelurahan,
        'kecamatan' => $id_kecamatan,
        'noFramePembelianSebelumnya' => clear_removed_html($pst['noFramePembelianSebelumnya']),
        'keterangan' =>clear_removed_html($pst['keterangan']),
        'promoUnit' => clear_removed_html($pst['promoUnit']),
        'facebook' => clear_removed_html($pst['facebook']),
        'instagram' => clear_removed_html($pst['instagram']),
        'twitter' => clear_removed_html($pst['twitter']),
        'created_at' => $now,
        'updated_at' => NULL,
        'updated_by' => NULL,
        'tanggalAssignDealer' => $now, 
        'kodeTypeUnitProspect' => NULL,
        'kodeWarnaUnitProspect' => NULL,
        'picFollowUpMD' => NULL,
        'ontimeSLA1' => NULL,
        'picFollowUpD' => NULL,
        'ontimeSLA2' => 1,
        'idSPK' => NULL,
        'kodeIndent ' => NULL,
        'kodeTypeUnitDeal ' => NULL,
        'kodeWarnaUnitDeal' => NULL,
        'deskripsiPromoDeal' => NULL,
        'metodePembayaranDeal' => NULL,
        'kodeLeasingDeal' => NULL,
        'frameNo' => NULL,
        'tanggalRegistrasi' => NULL, 
        'customerId' => $set_list_appoitment['rt'],
        'id_karyawan_dealer' => NULL,
        'idProspek' => $modifiedStringProspek,
        'kodeDealerPembelianSebelumnya ' => $kodeDealerPembelianSebelumnya ,
        'sumberProspek' => $sumberProspek,
    ];

    $inst_leads_history_assigned_dealer = [
      'id_int'      => NULL,             
      'leads_id'    => $leads_id,              
      'assignedKe'  => 1,         
      'assignedDealer' => $assignedDealer,      
      'alasanReAssignDealer' => 1,
      'tanggalAssignDealer'  => $now,    
      'assignedDealerBy' => 1,       
      'created_at'   => $now ,          
      'created_by'   => NULL,             
      'ontimeSLA2'   => NULL,             
      'alasanReAssignDealerLainnya'=>NULL,  
    ];

      $prospek_array[] = $insert_prospek;
      $stagingDataArray[] = $ins_staging; 
      $lead_history_stage_1_array[] = $ins_lead_history_stage_1; 
      $lead_history_stage_5_array[]  =$ins_lead_history_stage_5; 
      $lead_array[]  = $ins_lead; 
      $leads_history_assigned_dealer_array[] =$inst_leads_history_assigned_dealer; 

        if(isset($sub_array)){
          if(count($sub_array) > 0){
            $sub_array['array-line'] = $key+2 ;
            $sub_array_validation[] = $sub_array;
          }
        }
    }

    // $prospek_to_assing_sales_people = $this->AssignSalesPeopleStaging($prospek_array);

        if(count($sub_array_validation)==0){
            function insertDataWithForeignKeys($db, $table, $data) {
              $db->query("SET FOREIGN_KEY_CHECKS=0");
              $db->insert_batch($table, $data);
              $db->query("SET FOREIGN_KEY_CHECKS=1");
            }
      
            $this->db_crm->trans_start();
            $this->db->trans_start();
        
          try {
              insertDataWithForeignKeys($this->db_crm, 'leads', $lead_array);
              insertDataWithForeignKeys($this->db, 'tr_prospek', $prospek_array);
              insertDataWithForeignKeys($this->db_crm, 'leads_history_assigned_dealer', $leads_history_assigned_dealer_array);
              insertDataWithForeignKeys($this->db_crm, 'staging_table_leads', $stagingDataArray);
              insertDataWithForeignKeys($this->db_crm, 'leads_history_stage', $lead_history_stage_1_array);
              insertDataWithForeignKeys($this->db_crm, 'leads_history_stage', $lead_history_stage_5_array);
          
              $this->db_crm->trans_commit(); // All inserts successful, commit the transaction
              $this->db->trans_commit(); 
              $this->db_crm->query("UPDATE `leads` SET stage_id_foreign=(SELECT stage_id FROM staging_table_leads WHERE staging_table_leads.noHP=leads.noHP ORDER BY stage_id DESC LIMIT 1) WHERE stage_id_foreign IS NULL");
            } catch (Exception $e) {
              $this->db_crm->trans_rollback(); // Something went wrong, rollback the transaction
              $this->db->trans_rollback(); 

              $sub_array= array(); 
              $sub_array['array-line'] = 404;
              $errMsg = 'Gagal Menyimpan data Pada Database';
              $sub_array[] = $errMsg;
              $sub_array_validation[] =$sub_array;
            }
        }
        
        return $sub_array_validation;
    
  }




}