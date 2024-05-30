<?php
class CRM_leads_model_create_sub extends CI_Model
{
  var $sourceRefID = [];
  public function __construct()

  {
    parent::__construct();
    $this->db_crm= $this->load->database('db_crm', true);
    
  }

  function getBatchID()
  {
    $get_data  = $this->db_crm->query("SELECT batchID FROM staging_table_leads ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $new_kode = 'MDMS-' . random_hex(10);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db_crm->get_where('staging_table_leads', ['batchID' => $new_kode])->num_rows();
        if ($cek > 0) {
          $new_kode   = 'MDMS-' . random_hex(10);
          $i = 0;        
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'MDMS-' . random_hex(10);
    }
    return strtoupper($new_kode);
  }

  
  function getEvent($filter = null)
  {
    $where = "WHERE 1=1 AND ev.status='approved' AND is_event_ve=1 ";
    $select = '';
    if ($filter != null) {
      $filter = $this->db_crm->escape_str($filter);
      if (isset($filter['nama_deskripsi_kode_event_or_periode'])) {
        $nama_deskripsi_kode = $filter['nama_deskripsi_kode_event_or_periode'][0];
        $tanggal = $filter['nama_deskripsi_kode_event_or_periode'][1];
        $where .= " AND (ev.kode_event = '$nama_deskripsi_kode'
                           OR ev.nama_event = '$nama_deskripsi_kode'
                           OR ev.description = '$nama_deskripsi_kode'
                          )
                          AND '$tanggal' BETWEEN  ev.start_date AND ev.end_date
          ";
      }
      if (isset($filter['nama_deskripsi_event'])) {
        $where .= " AND (ev.nama_event='{$filter['nama_deskripsi_event']}' OR ev.description='{$filter['nama_deskripsi_event']}')";
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "ev.kode_event id, nama_event text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "ev.kode_event,ev.nama_event,ev.sumber,ev.id_jenis_event,ev.start_date,ev.end_date,ev.description
        ";
      }
    }

    return $this->db->query("SELECT $select
    FROM ms_event ev
    $where
    ");
  }

  function getLeads($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    $jumlah_fu = "SELECT COUNT(leads_id) FROM leads_follow_up WHERE leads_id=stl.leads_id";
    $jumlah_fu_d = "SELECT COUNT(leads_id) FROM leads_follow_up fud WHERE leads_id=stl.leads_id AND fud.assignedDealer=stl.assignedDealer";
    $jumlah_interaksi = "SELECT COUNT(leads_id) FROM leads_interaksi WHERE leads_id=stl.leads_id";

    $tgl_follow_up_md = "SELECT tglFollowUp FROM leads_follow_up WHERE leads_id=stl.leads_id AND (assignedDealer='' OR assignedDealer IS NULL) ORDER BY followUpKe LIMIT 1";

    $status_fu = "SELECT deskripsi_status_fu 
                  FROM leads_follow_up lfup
                  JOIN ms_status_fu sfu ON sfu.id_status_fu=lfup.id_status_fu
                  WHERE leads_id=stl.leads_id ORDER BY lfup.followUpKe DESC LIMIT 1";
   
   $last_pic = "SELECT pic 
                  FROM leads_follow_up lfup
                  WHERE leads_id=stl.leads_id ORDER BY lfup.followUpKe DESC LIMIT 1";

    $pernahTerhubung = "SELECT id_kategori_status_komunikasi
                  FROM leads_follow_up lfup
                  JOIN ms_status_fu sfu ON sfu.id_status_fu=lfup.id_status_fu
                  WHERE leads_id=stl.leads_id AND id_kategori_status_komunikasi=4 ORDER BY lfup.followUpKe DESC LIMIT 1";

    $tanggalNextFU = "SELECT tglNextFollowUp 
                  FROM leads_follow_up lfup
                  WHERE leads_id=stl.leads_id ORDER BY lfup.followUpKe DESC LIMIT 1";

    $keteranganNextFollowUp = "SELECT keteranganNextFollowUp 
                  FROM leads_follow_up lfup
                  WHERE leads_id=stl.leads_id ORDER BY lfup.followUpKe DESC LIMIT 1";

    $hasil_fu = "SELECT deskripsiHasilStatusFollowUp 
                  FROM leads_follow_up lfup
                  JOIN ms_hasil_status_follow_up hsfu ON hsfu.kodeHasilStatusFollowUp=lfup.kodeHasilStatusFollowUp
                  WHERE leads_id=stl.leads_id ORDER BY lfup.followUpKe DESC LIMIT 1";

    $last_kodeHasilStatusFollowUp = "SELECT lfup.kodeHasilStatusFollowUp 
                  FROM leads_follow_up lfup
                  WHERE leads_id=stl.leads_id ORDER BY lfup.followUpKe DESC LIMIT 1";

    $last_tanggalNextFU = "SELECT lfup.tglNextFollowUp 
                  FROM leads_follow_up lfup
                  WHERE leads_id=stl.leads_id ORDER BY lfup.followUpKe DESC LIMIT 1";
    $sla = "CASE WHEN IFNULL(leads_sla,'')!='' THEN leads_sla
              ELSE CASE WHEN msl.id_source_leads=28 OR msl.id_source_leads=28 THEN mcs.sla ELSE msl.src_sla END
            END
            ";
    $sla2 = "CASE WHEN IFNULL(leads_sla2,'')!='' THEN leads_sla2
              ELSE CASE WHEN msl.id_source_leads=28 OR msl.id_source_leads=28 THEN mcs.sla2 ELSE msl.src_sla2 END
            END
            ";
    $ontimeSLA1 = "CASE 
                    WHEN IFNULL(ontimeSLA1,'') = '' THEN 
                      CASE WHEN IFNULL(($sla),'') = '' THEN 1
                        ELSE 
                          CASE WHEN IFNULL(batasOntimeSLA1,'')=''
                            THEN CASE WHEN TIMEDIFF(customerActionDate,now()) < 0 THEN 0 ELSE 1 END
                          ELSE
                            CASE WHEN TIMEDIFF(batasOntimeSLA1,now()) < 0 THEN 0 ELSE 1 END
                          END
                      END
                    ELSE ontimeSLA1
                    END
                  ";

    $ontimeSLA1_desc = "CASE WHEN ontimeSLA1=1 THEN 'On Track' 
                            WHEN ontimeSLA1=0 THEN 'Overdue' 
                            ELSE 
                              CASE WHEN IFNULL(($sla),'') = '' THEN '-'
                                ELSE 
                                  CASE WHEN IFNULL(batasOntimeSLA1,'')=''
                                    THEN CASE WHEN TIMEDIFF(customerActionDate,now()) < 0 THEN 'Overdue' ELSE 'On Track' END
                                  ELSE
                                    CASE WHEN TIMEDIFF(batasOntimeSLA1,now()) < 0 THEN 'Overdue' ELSE 'On Track' END
                                  END
                              END
                        END
                        ";

    $ontimeSLA2 = "CASE 
                    WHEN IFNULL(ontimeSLA2,'') = '' THEN 
                      CASE WHEN IFNULL(($sla2),'') = '' THEN 1
                        ELSE 
                          CASE WHEN IFNULL(batasOntimeSLA2,'')='' THEN
                            CASE WHEN TIMEDIFF(stl.assignedDealer,now()) < 0 THEN 0 ELSE 1 END
                          ELSE
                            CASE WHEN TIMEDIFF(batasOntimeSLA2,now()) < 0 THEN 0 ELSE 1 END
                          END
                      END
                    ELSE ontimeSLA2
                    END
                  ";

    $ontimeSLA2_desc = "CASE WHEN assignedDealer!='' THEN
                              CASE WHEN ontimeSLA2=1 THEN 'On Track' 
                              WHEN ontimeSLA2=0 THEN 'Overdue' 
                              ELSE 
                                CASE WHEN IFNULL(($sla2),'') = '' THEN '-'
                                    ELSE 
                                      CASE WHEN IFNULL(batasOntimeSLA2,'')='' THEN
                                        CASE WHEN TIMEDIFF(stl.assignedDealer,now()) < 0 THEN 'Overdue' ELSE 'On Track' END
                                      ELSE
                                        CASE WHEN TIMEDIFF(batasOntimeSLA2,now()) < 0 THEN 'Overdue' ELSE 'On Track' END
                                      END
                                END
                          END
                        ELSE '-' END
                        ";

    $select = "batchID,nama,noHP,email,customerType,eventCodeInvitation,customerActionDate,segmentMotor,seriesMotor,deskripsiEvent,kodeTypeUnit,kodeWarnaUnit,minatRidingTest,jadwalRidingTest, 
        CASE WHEN minatRidingTest=1 THEN 'Ya' WHEN minatRidingTest=0 THEN 'Tidak' Else '-' END minatRidingTestDesc,
        CASE WHEN msl.id_source_leads IS NULL THEN sourceData ELSE msl.source_leads END deskripsiSourceData,sourceData,
        CASE WHEN mpd.id_platform_data IS NULL THEN platformData ELSE mpd.platform_data END deskripsiPlatformData,platformData,
        CASE WHEN mcs.kode_cms_source IS NULL THEN cmsSource ELSE mcs.deskripsi_cms_source END deskripsiCmsSource,cmsSource,
        noTelp,assignedDealer,sourceRefID,noFramePembelianSebelumnya,keterangan,promoUnit,facebook,instagram,twitter,stl.created_at,leads_id,leads_id_int,
        ($jumlah_fu) jumlahFollowUp,
        ($jumlah_fu_d) jumlahFollowUpDealer,
        ($ontimeSLA1) ontimeSLA1, 
        ($ontimeSLA1_desc) ontimeSLA1_desc,
        ($ontimeSLA2) ontimeSLA2,
        ($ontimeSLA2_desc) ontimeSLA2_desc,
        idSPK,kodeIndent,kodeTypeUnitDeal,kodeWarnaUnitDeal,deskripsiPromoDeal,metodePembayaranDeal,kodeLeasingDeal,frameNo,stl.updated_at,tanggalRegistrasi,customerId,kategoriModulLeads,tanggalVisitBooth,segmenProduk,tanggalDownloadBrosur,seriesBrosur,tanggalWishlist,seriesWishlist,tanggalPengajuan,namaPengajuan,tanggalKontakSales,noHpPengajuan,emailPengajuan,
        kab_pengajuan.kabupaten_kota kabupatenPengajuan,idKabupatenPengajuan,
        prov_pengajuan.provinsi provinsiPengajuan,idProvinsiPengajuan,
        CONCAT(kodeTypeUnit,' - ',deskripsi_tipe) concatKodeTypeUnit,CONCAT(kodeWarnaUnit,' - ',deskripsi_warna) concatKodeWarnaUnit, keteranganPreferensiDealerLain, kategoriKonsumen, 
        alasan_pindah.alasan alasanPindahDealer,alasanPindahDealerLainnya,
        kodeDealerSebelumnya,gender,kodeLeasingPembelianSebelumnya,noKtp,tanggalPembelianTerakhir,kodePekerjaan,deskripsiTipeUnitPembelianTerakhir,promoYangDiminatiCustomer,kategoriPreferensiDealer,idPendidikan,namaDealerPreferensiCustomer,idAgama,tanggalRencanaPembelian,kategoriProspect,idKecamatanKantor,namaCommunity,dl_sebelumnya.nama_dealer namaDealerSebelumnya,ls_sebelumnya.leasing namaLeasingPembelianSebelumnya,deskripsiPekerjaan,idPendidikan,pdk.pendidikan deskripsiPendidikan,idAgama,agm.agama deskripsiAgama,
        stl.provinsi,prov_domisili.provinsi deskripsiProvinsiDomisili,
        stl.kabupaten,kab_domisili.kabupaten_kota deskripsiKabupatenKotaDomisili,
        stl.kecamatan,kec_domisili.kecamatan deskripsiKecamatanDomisili,
        stl.kelurahan,kel_domisili.kelurahan deskripsiKelurahanDomisili,
        idKecamatanKantor,kec_kantor.kecamatan deskripsiKecamatanKantor,pkjk.golden_time,pkjk.script_guide,stl.assignedDealerBy,prioritasProspekCustomer,kodePekerjaanKtp,pkjk.pekerjaan deskripsiPekerjaanKtp,jenisKewarganegaraan,noKK,npwp,idJenisMotorYangDimilikiSekarang,jenisMotorYangDimilikiSekarang,idMerkMotorYangDimilikiSekarang,merkMotorYangDimilikiSekarang,yangMenggunakanSepedaMotor,statusProspek,longitude,latitude,jenisCustomer,idSumberProspek,sumberProspek,rencanaPembayaran,statusNoHp,tempatLahir,tanggalLahir,alamat,id_karyawan_dealer,idProspek,tanggalAssignDealer,
        ($status_fu) deskripsiStatusKontakFU,
        ($hasil_fu) deskripsiHasilStatusFollowUp,
        ($last_kodeHasilStatusFollowUp) kodeHasilStatusFollowUp,
        ($tanggalNextFU) tanggalNextFU,
        ($keteranganNextFollowUp) keteranganNextFollowUp,kodeTypeUnitProspect,
        preferensiPromoDiminatiCustomer,kodeWarnaUnitProspect,kodeTypeUnitDeal,kodeWarnaUnitDeal,deskripsiPromoDeal,CASE WHEN ($pernahTerhubung) = 4 THEN 'Ya' ELSE 'Tidak' END pernahTerhubung,
        kodeDealerPembelianSebelumnya,dl_beli_sebelumnya.nama_dealer namaDealerPembelianSebelumnya,
        plm.pengeluaran deskripsiPengeluaran,stl.pengeluaran,need_fu_md,($sla) sla,($sla2)sla2,
        " . sql_convert_date('tanggalRegistrasi') . " tanggalRegistrasiEng,
        " . sql_convert_date('tanggalVisitBooth') . " tanggalVisitBoothEng,
        " . sql_convert_date('tanggalWishlist') . " tanggalWishlistEng,
        " . sql_convert_date('tanggalDownloadBrosur') . " tanggalDownloadBrosurEng,
        " . sql_convert_date('tanggalPengajuan') . " tanggalPengajuanEng,
        " . sql_convert_date('tanggalKontakSales') . " tanggalKontakSalesEng,
        " . sql_convert_date('jadwalRidingTest') . " jadwalRidingTestEng,
        " . sql_convert_date('(' . $tgl_follow_up_md . ')') . " tgl_follow_up_md,
        " . sql_convert_date_dmy('stl.periodeAwalEvent') . " periodeAwalEventId,
        " . sql_convert_date_dmy('stl.periodeAkhirEvent') . " periodeAkhirEventId,
        batasOntimeSLA1,stl.periodeAwalEvent,stl.periodeAkhirEvent,batasOntimeSLA2,platform_for,stl.ontimeSLA2 ontimeSLA2Field,CASE WHEN kodeDealerSebelumnya IS NULL THEN 0 ELSE 1 END pernahAssigned,
        ($last_pic) last_pic
        ";

    if ($filter != null) {
      // Posisi di atas karena skip filter escape tanda kutip (')
      if (isset($filter['platformDataIn'])) {
        if ($filter['platformDataIn'] != '') {
          $filter['platformDataIn'] = arr_sql($filter['platformDataIn']);
          $where .= " AND stl.platformData IN({$filter['platformDataIn']})";
        }
      }
      if (isset($filter['sourceLeadsIn'])) {
        if ($filter['sourceLeadsIn'] != '') {
          $filter['sourceLeadsIn'] = arr_sql($filter['sourceLeadsIn']);
          $where .= " AND stl.sourceData IN({$filter['sourceLeadsIn']})";
        }
      }
      if (isset($filter['kodeDealerSebelumnyaIn'])) {
        if ($filter['kodeDealerSebelumnyaIn'] != '') {
          $filter['kodeDealerSebelumnyaIn'] = arr_sql($filter['kodeDealerSebelumnyaIn']);
          $where .= " AND stl.kodeDealerSebelumnya IN({$filter['kodeDealerSebelumnyaIn']})";
        }
      }
      if (isset($filter['assignedDealerIn'])) {
        if ($filter['assignedDealerIn'] != '') {
          $filter['assignedDealerIn'] = arr_sql($filter['assignedDealerIn']);
          $where .= " AND stl.assignedDealer IN({$filter['assignedDealerIn']})";
        }
      }
      if (isset($filter['kodeWarnaUnitIn'])) {
        if ($filter['kodeWarnaUnitIn'] != '') {
          $filter['kodeWarnaUnitIn'] = arr_sql($filter['kodeWarnaUnitIn']);
          $where .= " AND stl.kodeWarnaUnit IN({$filter['kodeWarnaUnitIn']})";
        }
      }
      if (isset($filter['kodeTypeUnitIn'])) {
        if ($filter['kodeTypeUnitIn'] != '') {
          $filter['kodeTypeUnitIn'] = arr_sql($filter['kodeTypeUnitIn']);
          $where .= " AND stl.kodeTypeUnit IN({$filter['kodeTypeUnitIn']})";
        }
      }
      if (isset($filter['leads_idIn'])) {
        if ($filter['leads_idIn'] != '') {
          $filter['leads_idIn'] = arr_sql($filter['leads_idIn']);
          $where .= " AND stl.leads_id IN({$filter['leads_idIn']})";
        }
      }
      if (isset($filter['deskripsiEventIn'])) {
        if ($filter['deskripsiEventIn'] != '') {
          $filter['deskripsiEventIn'] = arr_sql($filter['deskripsiEventIn']);
          $where .= " AND stl.deskripsiEvent IN({$filter['deskripsiEventIn']})";
        }
      }
      if (isset($filter['id_status_fu_in'])) {
        if ($filter['id_status_fu_in'] != '') {
          $filter['id_status_fu_in'] = arr_sql($filter['id_status_fu_in']);
          $where .= " AND msf.id_status_fu IN({$filter['id_status_fu_in']})";
        }
      }
      if (isset($filter['jumlah_fu_in'])) {
        if ($filter['jumlah_fu_in'] != '') {
          $filter['jumlah_fu_in'] = arr_sql($filter['jumlah_fu_in']);
          $where .= " AND ($jumlah_fu) IN({$filter['jumlah_fu_in']})";
        }
      }
      if (isset($filter['periode_next_fu'])) {
        if ($filter['periode_next_fu'] != '') {
          $next_fu = $filter['periode_next_fu'];
          $where .= " AND ($last_tanggalNextFU) BETWEEN '{$next_fu[0]}' AND '{$next_fu[1]}' ";
        }
      }
      if (isset($filter['periodeCreatedLeads'])) {
        if ($filter['periodeCreatedLeads'] != '') {
          $created = $filter['periodeCreatedLeads'];
          $where .= " AND LEFT(stl.created_at,10) BETWEEN '{$created[0]}' AND '{$created[1]}' ";
        }
      }
      if (isset($filter['periode_event'])) {
        if ($filter['periode_event'] != '') {
          $prd = $filter['periode_event'];
          $where .= " AND ('{$prd[0]}' BETWEEN stl.periodeAwalEvent AND stl.periodeAkhirEvent OR '{$prd[1]}' BETWEEN stl.periodeAwalEvent AND stl.periodeAkhirEvent)";
        }
      }
      if (isset($filter['kabupatenIn'])) {
        if ($filter['kabupatenIn'] != '') {
          $filter['kabupatenIn'] = arr_sql($filter['kabupatenIn']);
          $where .= " AND stl.kabupaten IN({$filter['kabupatenIn']})";
        }
      }

      if (isset($filter['seriesMotorIn'])) {
        if ($filter['seriesMotorIn'] != '') {
          $filter['seriesMotorIn'] = arr_sql($filter['seriesMotorIn']);
          $where .= " AND stl.seriesMotor IN({$filter['seriesMotorIn']})";
        }
      }

      if (isset($filter['leads_id_in'])) {
        if ($filter['leads_id_in'] != '') {
          $filter['leads_id_in'] = arr_sql($filter['leads_id_in']);
          $where .= " AND stl.leads_id IN({$filter['leads_id_in']})";
        }
      }

      //Filter Escaped String Like Singe Quote (')
      $filter = $this->db_crm->escape_str($filter);
      if (isset($filter['leads_id'])) {
        if ($filter['leads_id'] != '') {
          $where .= " AND stl.leads_id='{$this->db_crm->escape_str($filter['leads_id'])}'";
        }
      }
      if (isset($filter['nama'])) {
        if ($filter['nama'] != '') {
          $where .= " AND stl.nama='{$this->db_crm->escape_str($filter['nama'])}'";
        }
      }
      if (isset($filter['sourceRefID'])) {
        if ($filter['sourceRefID'] != '') {
          $where .= " AND stl.sourceRefID='{$this->db_crm->escape_str($filter['sourceRefID'])}'";
        }
      }
      if (isset($filter['noHP'])) {
        if ($filter['noHP'] != '') {
          $where .= " AND stl.noHP='{$this->db_crm->escape_str($filter['noHP'])}'";
        }
      }
      if (isset($filter['status'])) {
        if ($filter['status'] != '') {
          $where .= " AND stl.status='{$this->db_crm->escape_str($filter['status'])}'";
        }
      }
      if (isset($filter['idProspek'])) {
        if ($filter['idProspek'] != '') {
          $where .= " AND stl.idProspek='{$this->db_crm->escape_str($filter['idProspek'])}'";
        }
      }
      if (isset($filter['assignedDealer'])) {
        if ($filter['assignedDealer'] != '') {
          $where .= " AND stl.assignedDealer='{$this->db_crm->escape_str($filter['assignedDealer'])}'";
        }
      }
      if (isset($filter['idSPK'])) {
        if ($filter['idSPK'] != '') {
          $where .= " AND stl.idSPK='{$this->db_crm->escape_str($filter['idSPK'])}'";
        }
      }
      if (isset($filter['eventCodeInvitation_not_null'])) {
        if ($filter['eventCodeInvitation_not_null'] == true) {
          $where .= " AND IFNULL(stl.eventCodeInvitation,'') != '' ";
        }
      }
      if (isset($filter['idProspek_not_null'])) {
        if ($filter['idProspek_not_null'] == true) {
          $where .= " AND IFNULL(stl.idProspek,'') != '' ";
        }
      }
      if (isset($filter['customerType'])) {
        if ($filter['customerType'] != '') {
          $where .= " AND stl.customerType='{$this->db_crm->escape_str($filter['customerType'])}'";
        }
      }
      if (isset($filter['fu_md_contacted'])) {
        if ($filter['fu_md_contacted'] == true) {
          $where .= " AND (SELECT COUNT(leads_id) 
                            FROM leads_follow_up lfup
                            JOIN ms_status_fu sf ON sf.id_status_fu=lfup.id_status_fu
                            WHERE leads_id=stl.leads_id AND IFNULL(lfup.assignedDealer,'')='' AND id_kategori_status_komunikasi=4)>0 ";
        }
      }

      if (isset($filter['assignedDealerIsNULL'])) {
        if ($filter['assignedDealerIsNULL'] == true) {
          $where .= " AND IFNULL(stl.assignedDealer,'')=''";
        }
      }

      if (isset($filter['kodeHasilStatusFollowUpIn'])) {
        if ($filter['kodeHasilStatusFollowUpIn'] != '') {
          $filter['kodeHasilStatusFollowUpIn'] = arr_sql($filter['kodeHasilStatusFollowUpIn']);
          $where .= " AND ($last_kodeHasilStatusFollowUp) IN({$filter['kodeHasilStatusFollowUpIn']})";
        }
      }
      if (isset($filter['ontimeSLA2_multi'])) {
        if ($filter['ontimeSLA2_multi'] != '') {
          $filter['ontimeSLA2_multi'] = arr_sql($filter['ontimeSLA2_multi']);
          $where .= " AND stl.ontimeSLA2 IN({$filter['ontimeSLA2_multi']})";
        }
      }

      if (isset($filter['jumlah_fu_md'])) {
        $where .= " AND IFNULL(($jumlah_fu),0)={$filter['jumlah_fu_md']}";
      }

      if (isset($filter['interaksi_lebih_dari'])) {
        $where .= " AND IFNULL(($jumlah_interaksi),0)>{$filter['interaksi_lebih_dari']}";
      }

      if (isset($filter['ontimeSLA1'])) {
        $where .= " AND ($ontimeSLA1)={$filter['ontimeSLA1']}";
      }

      if (isset($filter['ontimeSLA2'])) {
        $where .= " AND ($ontimeSLA2)={$filter['ontimeSLA2']}";
      }

      if (isset($filter['jumlah_fu_d'])) {
        $where .= " AND IFNULL(($jumlah_fu_d),0)={$filter['jumlah_fu_d']}";
      }

      if (isset($filter['show_hasil_fu_not_prospect'])) {
        $fs = $filter['show_hasil_fu_not_prospect'];
        $where .= " AND 
                        CASE WHEN $fs=0 THEN
                          CASE WHEN IFNULL(($last_kodeHasilStatusFollowUp),0)=2 THEN 0 ELSE 1 END 
                        ELSE 1
                        END = 1  
                        ";
      }

      if (isset($filter['need_fu_md'])) {
        $where .= " AND need_fu_md={$filter['need_fu_md']}";
      }

      if (isset($filter['noHP_noTelp_email'])) {
        $fl = $filter['noHP_noTelp_email'];
        $where .= " AND (stl.noHP='{$fl[0]}' OR stl.noTelp='{$fl[1]}' OR stl.email='{$fl[2]}')";
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db_crm->escape_str($filter['search']);
          $where .= " AND ( stl.leads_id LIKE'%{$filter['search']}%'
                            OR stl.nama LIKE'%{$filter['search']}%'
                            OR stl.assignedDealer LIKE'%{$filter['search']}%'
                            OR stl.tanggalAssignDealer LIKE'%{$filter['search']}%'
                            OR stl.kodeDealerSebelumnya LIKE'%{$filter['search']}%'
                            OR deskripsiEvent LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "leads_id id, leads_id text";
        } elseif ($filter['select'] == 'count') {
          $select = "COUNT(leads_id) count,stl.customerType,stl.sourceData";
        } else {
          $select = $filter['select'];
        }
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'stl.leads_id', 'stl.nama', 'stl.kodeDealerSebelumnya', 'stl.assignedDealer', 'stl.tanggalAssignDealer', 'deskripsiPlatformData', 'deskripsiSourceData', 'deskripsiEvent', 'stl.periodeAwalEvent', 'deskripsiStatusKontakFU', "($pernahTerhubung)", 'deskripsiHasilStatusFollowUp', 'jumlahFollowUp', 'tanggalNextFU', 'stl.updated_at', 'ontimeSLA1_desc', 'ontimeSLA2_desc', null];
      $order = $filter['order'];
      if ($order != '') {
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order_data = " ORDER BY $order_clm $order_by ";
      } else {
        $order_data = "ORDER BY customerActionDate DESC";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    $group_by = '';
    if (isset($filter['group_by'])) {
      $group_by = "GROUP BY " . $filter['group_by'];
    }

    return $this->db_crm->query("SELECT $select
    FROM leads AS stl
    LEFT JOIN ms_source_leads msl ON msl.id_source_leads=stl.sourceData
    LEFT JOIN ms_platform_data mpd ON mpd.id_platform_data=stl.platformData
    LEFT JOIN ms_maintain_tipe tpu ON tpu.kode_tipe=stl.kodeTypeUnit
    LEFT JOIN ms_maintain_warna twu ON twu.kode_warna=stl.kodeWarnaUnit
    LEFT JOIN ms_dealer dl_sebelumnya ON dl_sebelumnya.kode_dealer=stl.kodeDealerSebelumnya
    LEFT JOIN ms_leasing ls_sebelumnya ON ls_sebelumnya.kode_leasing=stl.kodeLeasingPembelianSebelumnya
    LEFT JOIN ms_pekerjaan pkjk ON pkjk.kode_pekerjaan=stl.kodePekerjaanKtp
    LEFT JOIN ms_pendidikan pdk ON pdk.id_pendidikan=stl.idPendidikan
    LEFT JOIN ms_agama agm ON agm.id_agama=stl.idAgama
    LEFT JOIN ms_maintain_provinsi prov_domisili ON prov_domisili.id_provinsi=stl.provinsi
    LEFT JOIN ms_maintain_kabupaten_kota kab_domisili ON kab_domisili.id_kabupaten_kota=stl.kabupaten
    LEFT JOIN ms_maintain_kecamatan kec_domisili ON kec_domisili.id_kecamatan=stl.kecamatan
    LEFT JOIN ms_maintain_kelurahan kel_domisili ON kel_domisili.id_kelurahan=stl.kelurahan
    LEFT JOIN ms_maintain_kecamatan kec_kantor ON kec_kantor.id_kecamatan=stl.idKecamatanKantor
    LEFT JOIN ms_dealer dl_beli_sebelumnya ON dl_beli_sebelumnya.kode_dealer=stl.kodeDealerPembelianSebelumnya
    LEFT JOIN ms_maintain_provinsi prov_pengajuan ON prov_pengajuan.id_provinsi=stl.idProvinsiPengajuan
    LEFT JOIN ms_maintain_kabupaten_kota kab_pengajuan ON kab_pengajuan.id_kabupaten_kota=stl.idKabupatenPengajuan
    LEFT JOIN ms_maintain_cms_source mcs ON mcs.kode_cms_source=stl.cmsSource
    LEFT JOIN ms_pengeluaran plm ON plm.id_pengeluaran=stl.pengeluaran
    LEFT JOIN setup_alasan_reassigned_pindah_dealer alasan_pindah ON alasan_pindah.id_alasan=stl.alasanPindahDealer
    $where
    $group_by
    $order_data
    $limit
    ");
  }

  
  function getPlatformData($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db_crm->escape_str($filter);
      if (isset($filter['id_or_platform_data'])) {
        if ($filter['id_or_platform_data'] != '') {
          $where .= " AND (mu.id_platform_data='{$filter['id_or_platform_data']}' OR mu.platform_data='{$filter['id_or_platform_data']}')";
        }
      }
      if (isset($filter['id_platform_data'])) {
        if ($filter['id_platform_data'] != '') {
          $where .= " AND mu.id_platform_data='{$filter['id_platform_data']}'";
        }
      }
      if (isset($filter['id_platform_data_in'])) {
        if ($filter['id_platform_data_in'] != '') {
          $in = arr_sql($filter['id_platform_data_in']);
          $where .= " AND mu.id_platform_data IN ($in)";
        }
      }

      if (isset($filter['aktif'])) {
        if ($filter['aktif'] != '') {
          $where .= " AND mu.aktif='{$this->db_crm->escape_str($filter['aktif'])}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db_crm->escape_str($filter['search']);
          $where .= " AND ( mu.id_platform_data LIKE'%{$filter['search']}%'
                            OR mu.platform_data LIKE'%{$filter['search']}%'
          )";
        }
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_platform_data id, CONCAT(id_platform_data,' - ',platform_data) text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "mu.id_platform_data,mu.platform_data,mu.aktif,mu.created_at,mu.created_by,mu.updated_at,mu.updated_by";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'id_platform_data', 'platform_data', null];
      $order = $filter['order'];
      if ($order != '') {
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order_data = " ORDER BY $order_clm $order_by ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db_crm->query("SELECT $select
    FROM ms_platform_data AS mu
    $where
    $order_data
    $limit
    ");
  }

  function getTipeWarnaFromOtherDb($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    if ($filter != null) {
      $filter = $this->db_crm->escape_str($filter);

      if (isset($filter['id_or_nama_tipe'])) {
        $where .= " AND (tp.id_tipe_kendaraan = '{$filter['id_or_nama_tipe']}' OR tp.tipe_ahm = '{$filter['id_or_nama_tipe']}')";
      }
      if (isset($filter['id_or_nama_warna'])) {
        $where .= " AND (wr.id_warna = '{$filter['id_or_nama_warna']}' OR wr.warna = '{$filter['id_or_nama_warna']}')";
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "id_tipe_kendaraan id,CONCAT(id_tipe_kendaraan,' - ',tipe_ahm) text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "tp.id_tipe_kendaraan,wr.id_warna,tp.id_series";
      }
    }

    return $this->db->query("SELECT $select
    FROM ms_item itm
    JOIN ms_tipe_kendaraan tp ON tp.id_tipe_kendaraan=itm.id_tipe_kendaraan
    JOIN ms_warna wr ON wr.id_warna=itm.id_warna
    JOIN ms_series srs ON srs.id_series=tp.id_series
    $where
    ");
  }


  function fixLeadsId()
  {

    $leads_id='';

    $dmy            = gmdate("dmY", time() + 60 * 60 * 7);

    if ($leads_id=='') {
      $row = $this->db_crm->query("SELECT RIGHT(leads_id,6) leads_id FROM leads ORDER BY leads_id_int DESC")->row();
      $leads_id = 'E20/' . $dmy . '/' . sprintf("%'.06d", $row->leads_id + 1);
      $this->fixLeadsId($leads_id);
    }else{
      $cekl = $this->db_crm->query("SELECT leads_id FROM leads WHERE leads_id='$leads_id'")->row();
      $ceku = $this->db_crm->query("SELECT leads_id FROM upload_leads WHERE leads_id='$leads_id'")->row();
      if ($cekl==null && $ceku==null) {
        $_SESSION['leads_id']=$leads_id;
      }else{
        $new_leads_id   = 'E20/' . $dmy . '/' . sprintf("%'.06d", substr($leads_id, -6) + 1);
        $this->fixLeadsId($new_leads_id);
      }
    }
  }

  

  

  

}