<?php
class CRM_leads_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->db_crm_crm = $this->load->database('db_crm', true);
  }

  function getStagingLeads($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
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

      if (isset($filter['deskripsiEventIn'])) {
        if ($filter['deskripsiEventIn'] != '') {
          $filter['deskripsiEventIn'] = arr_sql($filter['deskripsiEventIn']);
          $where .= " AND stl.deskripsiEvent IN({$filter['deskripsiEventIn']})";
        }
      }
      if (isset($filter['kabupatenIn'])) {
        if ($filter['kabupatenIn'] != '') {
          $filter['kabupatenIn'] = arr_sql($filter['kabupatenIn']);
          $where .= " AND stl.kabupaten IN({$filter['kabupatenIn']})";
        }
      }

      if (isset($filter['kodeTypeUnitIn'])) {
        if ($filter['kodeTypeUnitIn'] != '') {
          $filter['kodeTypeUnitIn'] = arr_sql($filter['kodeTypeUnitIn']);
          $where .= " AND stl.kodeTypeUnit IN({$filter['kodeTypeUnitIn']})";
        }
      }

      if (isset($filter['seriesMotorIn'])) {
        if ($filter['seriesMotorIn'] != '') {
          $filter['seriesMotorIn'] = arr_sql($filter['seriesMotorIn']);
          $where .= " AND stl.seriesMotor IN({$filter['seriesMotorIn']})";
        }
      }

      $filter = $this->db_crm->escape_str($filter);
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

      if (isset($filter['noHP_noTelp_email'])) {
        $noHP = $filter['noHP_noTelp_email'][0];
        $noTelp = $filter['noHP_noTelp_email'][1];
        $email = $filter['noHP_noTelp_email'][2];
        if ($noHP != '') {
          $whr[] = "stl.noHP='$noHP'";
        }
        if ($noTelp != '') {
          $whr[] = "stl.noTelp='$noTelp'";
        }
        if ($email != '') {
          $whr[] = "stl.email='$email'";
        }
        if (isset($whr)) {
          $set_whr = implode(" OR ", $whr);
          $where .= " AND ($set_whr)";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db_crm->escape_str($filter['search']);
          $where .= " AND ( stl.kode_md LIKE'%{$filter['search']}%'
                            OR stl.nama LIKE'%{$filter['search']}%'
                            OR stl.no_hp LIKE'%{$filter['search']}%'
                            OR stl.no_telp LIKE'%{$filter['search']}%'
                            OR stl.email LIKE'%{$filter['search']}%'
                            OR kab.kabupaten_kota LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'count') {
          $select = "COUNT(stage_id) count";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "batchID,nama,noHP,email,customerType,eventCodeInvitation,customerActionDate,kabupaten,cmsSource,segmentMotor,seriesMotor,deskripsiEvent,kodeTypeUnit,kodeWarnaUnit,minatRidingTest,jadwalRidingTest,sourceData,platformData,noTelp,assignedDealer,sourceRefID,provinsi,kelurahan,kecamatan,noFramePembelianSebelumnya,keterangan,promoUnit,facebook,instagram,twitter,created_at";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null];
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
    FROM staging_table_leads AS stl
    $where
    $order_data
    $limit
    ");
  }

  function getLeads($filter = null)
  {

  // testing ernesto

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

  function getStagingTableVSMainTable($filter)
  {
    $where = "WHERE 1=1";
    $concat_desc_tipe_warna = "CONCAT(deskripsi_tipe,' - ',IFNULL(deskripsi_warna,'')) ";
    $status_api2 = "CASE WHEN stl.setleads=0 THEN 'New' WHEN stl.setleads=1 THEN 'Inprogress' END ";
    if (isset($filter['noHP'])) {
      if ($filter['noHP'] != '') {
        $where .= " AND stl.noHP='{$this->db_crm->escape_str($filter['noHP'])}'";
      }
    }

    if (isset($filter['mainTableNULL'])) {
      if ($filter['mainTableNULL'] != '') {
        $where .= " AND stl.setleads=0";
      }
    }
    if (isset($filter['mainTableLeadsIDNULL'])) {
      if ($filter['mainTableLeadsIDNULL'] != '') {
        $where .= " AND tl.leads_id IS NULL";
      }
    }

    if (isset($filter['search'])) {
      if ($filter['search'] != '') {
        $filter['search'] = $this->db_crm->escape_str($filter['search']);
        $where .= " AND ( stl.nama LIKE'%{$filter['search']}%'
                          OR stl.noHP LIKE'%{$filter['search']}%'
                          OR stl.noTelp LIKE'%{$filter['search']}%'
                          OR stl.email LIKE'%{$filter['search']}%'
                          OR pld.platform_data LIKE'%{$filter['search']}%'
                          OR sc.source_leads LIKE'%{$filter['search']}%'
                          OR stl.deskripsiEvent LIKE'%{$filter['search']}%'
                          OR ($concat_desc_tipe_warna) LIKE'%{$filter['search']}%'
                          OR ($status_api2) LIKE'%{$filter['search']}%'
        )";
      }
    }

    $group_by = '';
    if (isset($filter['group_by'])) {
      $group_by = 'GROUP BY ' . $filter['group_by'];
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db_crm->query("SELECT stl.batchID,stl.nama,stl.noHP,stl.email,stl.customerType,stl.setleads,stl.eventCodeInvitation,stl.customerActionDate,stl.kabupaten,stl.cmsSource,stl.segmentMotor,stl.seriesMotor,stl.deskripsiEvent,stl.kodeTypeUnit,stl.kodeWarnaUnit,stl.minatRidingTest,stl.jadwalRidingTest,stl.sourceData,stl.platformData,stl.noTelp,stl.assignedDealer,stl.sourceRefID,stl.provinsi,stl.kelurahan,stl.kecamatan,stl.noFramePembelianSebelumnya,stl.keterangan,stl.promoUnit,stl.facebook,stl.instagram,stl.twitter,stl.created_at,tl.leads_id,stl.stage_id,pld.platform_data descPlatformData,sc.source_leads descSourceLeads,tp.deskripsi_tipe,wr.deskripsi_warna,$concat_desc_tipe_warna concat_desc_tipe_warna,
    $status_api2 status_api2,stl.created_at,CASE WHEN stl.sourceData=28 THEN 'Invited' WHEN stl.sourceData=29 THEN 'Non Invited' ELSE '' END customerTypeDesc,
    CASE WHEN cs.kode_cms_source IS NULL THEN stl.cmsSource ELSE cs.deskripsi_cms_source END deskripsiCmsSource,stl.deskripsiEvent,stl.facebook,stl.instagram,stl.twitter,stl.customerActionDate,
    CASE WHEN sc.id_source_leads=28 OR sc.id_source_leads=29 THEN cs.sla
         ELSE sc.src_sla END AS sla,
    CASE WHEN sc.id_source_leads=28 OR sc.id_source_leads=29 THEN cs.sla2
         ELSE sc.src_sla2 END AS sla2,
    stl.periodeAwalEvent,stl.periodeAkhirEvent
    FROM staging_table_leads stl
    JOIN ms_platform_data pld ON pld.id_platform_data=stl.platformData
    JOIN ms_source_leads sc ON sc.id_source_leads=stl.sourceData
    LEFT JOIN ms_maintain_tipe tp ON tp.kode_tipe=stl.kodeTypeUnit
    LEFT JOIN ms_maintain_warna wr ON wr.kode_warna=stl.kodeTypeUnit
    LEFT JOIN leads tl ON tl.noHP=stl.noHP
    LEFT JOIN ms_maintain_cms_source cs ON cs.kode_cms_source=stl.cmsSource
    $where
    $group_by
    $limit
    ");
  }

  function getBatchID()
  {
    $get_data  = $this->db_crm->query("SELECT batchID FROM staging_table_leads 
                  ORDER BY created_at DESC LIMIT 0,1");
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

  function getLeadsID()
  {
    $dmy = gmdate("dmY", time() + 60 * 60 * 7);
    $get_data  = $this->db_crm->query("SELECT * FROM (
      (SELECT
          RIGHT (leads_id, 6) leads_id
        FROM
          leads
        ORDER BY leads_id_int DESC
        LIMIT 1)
        UNION ALL
        (SELECT
          RIGHT (leads_id, 6) leads_id
        FROM
          upload_leads
        ORDER BY id_leads_int DESC LIMIT 1)
      ) AS tabel ORDER BY leads_id DESC LIMIT 1");
    if ($get_data->num_rows() > 0) {
      $row = $get_data->row();
      $new_kode = 'E20/' . $dmy . '/' . sprintf("%'.06d", $row->leads_id + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db_crm->get_where('leads', ['leads_id' => $new_kode])->num_rows();
        if ($cek > 0) {
          $cek = $this->db_crm->get_where('upload_leads', ['leads_id' => $new_kode])->num_rows();
        }
        if ($cek > 0) {
          $new_kode   = 'E20/' . $dmy . '/' . sprintf("%'.06d", substr($new_kode, -6) + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'E20/' . $dmy . '/000001';
    }

    return strtoupper($new_kode);
  }

  function fixLeadsId($leads_id='')
  {
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

  function getLeadsFollowUp($filter = null)
  {
    $where = 'WHERE 1=1';
    $tglFollowUpFormated = sql_convert_date('lfu.tglFollowUp');
    $select = '';
    $is_md = "CASE WHEN dl_assg.kode_dealer IS NULL THEN 1 ELSE 0 END";

    if ($filter != null) {
      // Posisi di atas karena skip filter escape tanda kutip (')
      if (isset($filter['platformDataIn'])) {
        if ($filter['platformDataIn'] != '') {
          $filter['platformDataIn'] = arr_sql($filter['platformDataIn']);
          $where .= " AND ld.platformData IN({$filter['platformDataIn']})";
        }
      }

      if (isset($filter['sourceLeadsIn'])) {
        if ($filter['sourceLeadsIn'] != '') {
          $filter['sourceLeadsIn'] = arr_sql($filter['sourceLeadsIn']);
          $where .= " AND ld.sourceData IN({$filter['sourceLeadsIn']})";
        }
      }

      if (isset($filter['deskripsiEventIn'])) {
        if ($filter['deskripsiEventIn'] != '') {
          $filter['deskripsiEventIn'] = arr_sql($filter['deskripsiEventIn']);
          $where .= " AND ld.deskripsiEvent IN({$filter['deskripsiEventIn']})";
        }
      }

      if (isset($filter['periodeCreatedLeads'])) {
        if ($filter['periodeCreatedLeads'] != '') {
          $created = $filter['periodeCreatedLeads'];
          $where .= " AND LEFT(ld.created_at,10) BETWEEN '{$created[0]}' AND '{$created[1]}' ";
        }
      }

      if (isset($filter['kabupatenIn'])) {
        if ($filter['kabupatenIn'] != '') {
          $filter['kabupatenIn'] = arr_sql($filter['kabupatenIn']);
          $where .= " AND ld.kabupaten IN({$filter['kabupatenIn']})";
        }
      }

      if (isset($filter['assignedDealerIn'])) {
        if ($filter['assignedDealerIn'] != '') {
          $filter['assignedDealerIn'] = arr_sql($filter['assignedDealerIn']);
          $where .= " AND ld.assignedDealer IN({$filter['assignedDealerIn']})";
        }
      }

      if (isset($filter['kodeTypeUnitIn'])) {
        if ($filter['kodeTypeUnitIn'] != '') {
          $filter['kodeTypeUnitIn'] = arr_sql($filter['kodeTypeUnitIn']);
          $where .= " AND ld.kodeTypeUnit IN({$filter['kodeTypeUnitIn']})";
        }
      }

      if (isset($filter['seriesMotorIn'])) {
        if ($filter['seriesMotorIn'] != '') {
          $filter['seriesMotorIn'] = arr_sql($filter['seriesMotorIn']);
          $where .= " AND ld.seriesMotor IN({$filter['seriesMotorIn']})";
        }
      }

      $filter = $this->db_crm->escape_str($filter);
      if (isset($filter['leads_id'])) {
        if ($filter['leads_id'] != '') {
          $where .= " AND lfu.leads_id='{$this->db_crm->escape_str($filter['leads_id'])}'";
        }
      }
      if (isset($filter['is_md'])) {
        if ($filter['is_md'] != '') {
          $where .= " AND ($is_md)='{$this->db_crm->escape_str($filter['is_md'])}'";
        }
      }
      if (isset($filter['assignedDealer'])) {
        $where .= " AND lfu.assignedDealer='{$this->db_crm->escape_str($filter['assignedDealer'])}'";
      }
      if (isset($filter['followUpKe'])) {
        if ($filter['followUpKe'] != '') {
          $where .= " AND lfu.followUpKe='{$this->db_crm->escape_str($filter['followUpKe'])}'";
        }
      }
      if (isset($filter['kodeHasilStatusFollowUp'])) {
        if ($filter['kodeHasilStatusFollowUp'] != '') {
          $where .= " AND lfu.kodeHasilStatusFollowUp='{$this->db_crm->escape_str($filter['kodeHasilStatusFollowUp'])}'";
        }
      }
      if (isset($filter['id_kategori_status_komunikasi'])) {
        if ($filter['id_kategori_status_komunikasi'] != '') {
          $where .= " AND ksk.id_kategori_status_komunikasi='{$this->db_crm->escape_str($filter['id_kategori_status_komunikasi'])}'";
        }
      }
      if (isset($filter['created_by_null'])) {
        if ($filter['created_by_null'] != '') {
          $where .= " AND (lfu.created_by IS NULL OR lfu.created_by='')";
        }
      }
      if (isset($filter['status_null'])) {
        if ($filter['status_null'] != '') {
          $where .= " AND (lfu.status IS NULL OR lfu.status='')";
        }
      }
      if (isset($filter['idSPK_not_null'])) {
        if ($filter['idSPK_not_null'] != '') {
          $where .= " AND IFNULL(ld.idSPK,'')!=''";
        }
      }
      if (isset($filter['frameNo_not_null'])) {
        if ($filter['frameNo_not_null'] != '') {
          $where .= " AND IFNULL(ld.frameNo,'')!=''";
        }
      }
      if (isset($filter['assignedDealerIsNULL'])) {
        if ($filter['assignedDealerIsNULL'] == true) {
          $where .= " AND IFNULL(lfu.assignedDealer,'')=''";
        }
      }
      if (isset($filter['assignedDealerIsNotNULL'])) {
        if ($filter['assignedDealerIsNotNULL'] == true) {
          $where .= " AND IFNULL(lfu.assignedDealer,'')!=''";
        }
      }
      if (isset($filter['id_kategori_status_komunikasi_not'])) {
        if ($filter['id_kategori_status_komunikasi_not'] != '') {
          $where .= " AND ksk.id_kategori_status_komunikasi!='{$this->db_crm->escape_str($filter['id_kategori_status_komunikasi_not'])}'";
        }
      }
      if (isset($filter['followUpID'])) {
        if ($filter['followUpID'] != '') {
          $where .= " AND lfu.followUpID='{$this->db_crm->escape_str($filter['followUpID'])}'";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "leads_id id, leads_id text";
        } elseif ($filter['select'] == 'count') {
          $select = "COUNT(lfu.leads_id) count";
        } elseif ($filter['select'] == 'count_distinct_leads_id') {
          $select = "COUNT(DISTINCT lfu.leads_id) count";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "lfu.id_int,lfu.leads_id,lfu.followUpKe,lfu.pic,
        CASE WHEN lfu.tglFollowUp='0000-00-00 00:00:00' THEN '' ELSE lfu.tglFollowUp END tglFollowUp,
        CASE WHEN lfu.tglFollowUp='0000-00-00 00:00:00' THEN '' ELSE ($tglFollowUpFormated) END tglFollowUpFormated,
        CASE WHEN lfu.tglNextFollowUp='0000-00-00' THEN '' ELSE lfu.tglNextFollowUp END tglNextFollowUp,
        CASE WHEN lfu.created_at='0000-00-00 00:00:00' THEN '' ELSE lfu.created_at END created_at,
        lfu.keteranganFollowUp,lfu.keteranganNextFollowUp,lfu.id_media_kontak_fu,lfu.id_status_fu,lfu.kodeHasilStatusFollowUp,lfu.kodeAlasanNotProspectNotDeal,lfu.noHP,lfu.email,lfu.created_by,lfu.updated_at,lfu.updated_by,media.media_kontak_fu,sts.deskripsi_status_fu status_fu,kategori_status_komunikasi,hks.deskripsiHasilStatusFollowUp,als.alasanNotProspectNotDeal,lfu.status,lfu.assignedDealer,followUpID,
        CASE WHEN IFNULL(keteranganLainnyaNotProspectNotDeal,'')='' THEN keteranganAlasanLainnya ELSE keteranganLainnyaNotProspectNotDeal END keteranganLainnyaNotProspectNotDeal,
        CASE WHEN IFNULL(keteranganLainnyaNotProspectNotDeal,'')='' THEN keteranganAlasanLainnya ELSE keteranganLainnyaNotProspectNotDeal END keteranganAlasanLainnya,keteranganNextFollowUp,$is_md is_md,dl_assg.nama_dealer namaDealerFollowUp,sts.id_kategori_status_komunikasi,lfu.statusProspek,lfu.id_tipe_kendaraan,lfu.id_warna,CONCAT(lfu.id_tipe_kendaraan,' - ',deskripsi_tipe) concatKodeTypeUnit,CONCAT(lfu.id_warna,' - ',deskripsi_warna) concatKodeWarnaUnit";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null];
      $order = $filter['order'];
      if ($order != '') {
        if (is_array($order)) {
          $order_clm  = $order_column[$order['0']['column']];
          $order_by   = $order['0']['dir'];
          $order_data = " ORDER BY $order_clm $order_by ";
        } else {
          $order_data = " ORDER BY $order";
        }
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    $dt_result = $this->db_crm->query("SELECT $select
    FROM leads_follow_up AS lfu
    LEFT JOIN ms_dealer dl_assg ON dl_assg.kode_dealer=lfu.assignedDealer
    JOIN leads ld ON ld.leads_id=lfu.leads_id
    LEFT JOIN ms_media_kontak_fu media ON media.id_media_kontak_fu=lfu.id_media_kontak_fu
    LEFT JOIN ms_status_fu sts ON sts.id_status_fu=lfu.id_status_fu
    LEFT JOIN ms_kategori_status_komunikasi ksk ON ksk.id_kategori_status_komunikasi=sts.id_kategori_status_komunikasi
    LEFT JOIN ms_hasil_status_follow_up hks ON hks.kodeHasilStatusFollowUp=lfu.kodeHasilStatusFollowUp
    LEFT JOIN ms_alasan_not_prospect_not_deal als ON als.kodeAlasanNotProspectNotDeal=lfu.kodeAlasanNotProspectNotDeal
    LEFT JOIN ms_maintain_tipe tipe ON tipe.kode_tipe=lfu.id_tipe_kendaraan
    LEFT JOIN ms_maintain_warna warna ON warna.kode_warna=lfu.id_warna
    $where
    $order_data
    $limit
    ");
    if (isset($filter['response'])) {
      $result = [];
      foreach ($dt_result->result_array() as $rs) {
        $result[$rs['followUpKe']] = $rs;
      }
    } else {
      $result = $dt_result;
    }
    return $result;
  }
  function getLeadsHistoryAssignedDealer($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';
    $tglFollowUp = "SELECT tglFollowUp FROM leads_follow_up lfu WHERE lfu.leads_id=lhad.leads_id AND lfu.assignedDealer=lhad.assignedDealer AND lfu.followUpKe=1";
    if ($filter != null) {
      $filter = $this->db_crm->escape_str($filter);
      if (isset($filter['leads_id'])) {
        if ($filter['leads_id'] != '') {
          $where .= " AND lhad.leads_id='{$this->db_crm->escape_str($filter['leads_id'])}'";
        }
      }
      if (isset($filter['assignedDealer'])) {
        if ($filter['assignedDealer'] != '') {
          $where .= " AND lhad.assignedDealer='{$this->db_crm->escape_str($filter['assignedDealer'])}'";
        }
      }
      if (isset($filter['alasanReAssignDealerNotNULL'])) {
        $where .= " AND IFNULL(lhad.alasanReAssignDealer,'')!=''";
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db_crm->escape_str($filter['search']);
          $where .= " AND ( lhad.leads_id LIKE'%{$filter['search']}%'
                            OR dl.kode_dealer LIKE'%{$filter['search']}%'
                            OR dl.nama_dealer LIKE'%{$filter['search']}%'
                            OR lhad.tanggalAssignDealer LIKE'%{$filter['search']}%'
                            OR lhad.created_at LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "lhad.leads_id id, lhad.leads_id text";
        } else {
          $select = $filter['select'];
        }
      } else {
        $select = "lhad.id_int,lhad.assignedDealer,assignedKe,lhad.tanggalAssignDealer,lhad.assignedDealerBy,lhad.created_at,lhad.created_by,dl.nama_dealer,als.alasan alasanReAssignDealer,alasanReAssignDealerLainnya,($tglFollowUp) tglFollowUp,lhad.ontimeSLA2,CASE WHEN lhad.ontimeSLA2=1 THEN 'On Track' WHEN lhad.ontimeSLA2=0 THEN 'Overdue' ELSE '-' END ontimeSLA2_desc,als.id_alasan";
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null];
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

    $dt_result = $this->db_crm->query("SELECT $select
    FROM leads_history_assigned_dealer AS lhad
    JOIN leads ld ON ld.leads_id=lhad.leads_id
    JOIN ms_dealer dl ON dl.kode_dealer=lhad.assignedDealer
    LEFT JOIN setup_alasan_reassigned_pindah_dealer als ON als.id_alasan=lhad.alasanReAssignDealer
    $where
    $order_data
    $limit
    ");
    return $dt_result;
  }

  function getCustomerID()
  {
    $dmy = gmdate("dmY", time() + 60 * 60 * 7);
    $ymd = tanggal();
    $get_data  = $this->db_crm->query("SELECT RIGHT(customerId,3) customerId 
                  FROM leads WHERE LEFT(created_at,10)='$ymd'
                  ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row = $get_data->row();
      $new_kode = 'CUST/' . $dmy . '/' . sprintf("%'.03d", $row->customerId + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db_crm->get_where('leads', ['customerId' => $new_kode])->num_rows();
        if ($cek > 0) {
          $new_kode   = 'CUST/' . $dmy . '/' . sprintf("%'.03d", substr($new_kode, -3) + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'CUST/' . $dmy . '/001';
    }
    return strtoupper($new_kode);
  }
  function getFollowUpID()
  {
    $dmy = gmdate("dmY", time() + 60 * 60 * 7);
    $ymd = tanggal();
    $get_data  = $this->db_crm->query("SELECT RIGHT(followUpID,3) followUpID 
                  FROM leads_follow_up WHERE LEFT(created_at,10)='$ymd'
                  ORDER BY followUpID,created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row = $get_data->row();
      $new_kode = 'FOLUP/' . $dmy . '/' . sprintf("%'.03d", $row->followUpID + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db_crm->get_where('leads_follow_up', ['followUpID' => $new_kode])->num_rows();
        if ($cek > 0) {
          $new_kode   = 'FOLUP/' . $dmy . '/' . sprintf("%'.03d", substr($new_kode, -3) + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'FOLUP/' . $dmy . '/001';
      $i = 0;
      while ($i < 1) {
        $cek = $this->db_crm->get_where('leads_follow_up', ['followUpID' => $new_kode])->num_rows();
        if ($cek > 0) {
          $new_kode   = 'FOLUP/' . $dmy . '/' . sprintf("%'.03d", substr($new_kode, -3) + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    }
    return strtoupper($new_kode);
  }

  function getDeskripsiEvent()
  {
    $where = "WHERE 1=1 ";
    if (isset($filter['search'])) {
      if ($filter['search'] != '') {
        $filter['search'] = $this->db_crm->escape_str($filter['search']);
        $where .= " AND ( leads.deskripsiEvent LIKE'%{$filter['search']}%'
        )";
      }
    }
    return $this->db_crm->query("SELECT deskripsiEvent id, deskripsiEvent text FROM leads GROUP BY deskripsiEvent");
  }
  function getJumlahFUMaks()
  {
    return $this->db_crm->query("SELECT COUNT(leads_id) maks FROM leads_follow_up GROUP BY leads_id ORDER BY COUNT(leads_id) DESC LIMIT 1")->row()->maks;
  }

  function getLeadsStage($filter)
  {
    $where = "WHERE 1=1 ";
    $filter['search'] = $this->db_crm->escape_str($filter);
    if (isset($filter['sending_to_ahm_at_is_null'])) {
      $where .= " AND sending_to_ahm_at IS NULL";
    }

    if (isset($filter['stageId'])) {
      $where .= " AND stageId='{$filter['stageId']}'";
    }

    if (isset($filter['leads_id'])) {
      $where .= " AND lhs.leads_id='{$filter['leads_id']}'";
    }
    return $this->db_crm->query("SELECT  lhs.leads_id,stageId,IFNULL(lhs.followUpID,'') followUpID 
    FROM leads_history_stage lhs
    JOIN leads ld ON ld.leads_id=lhs.leads_id
    $where ORDER BY lhs.created_at ASC");
  }

  function setOntimeSLA1_detik($customerActionDate, $tglFollowUp)
  {
    $selisih = selisih_detik($customerActionDate, $tglFollowUp);
    return $selisih;
  }

  function setOntimeSLA1($tglFolUp, $batasSLA1)
  {
    $selisih = tanggal_lebih_kecil($tglFolUp, $batasSLA1);
    if ($selisih == true) {
      $return = 1;
    } else {
      $return = 0;
    }
    return $return;
  }

  function setOntimeSLA2_detik($tglAssign, $tglFollowUp)
  {
    $selisih = selisih_detik($tglAssign, $tglFollowUp);
    return $selisih;
  }

  function setOntimeSLA2($tglFolUp, $batasSLA2)
  {
    $selisih = tanggal_lebih_kecil($tglFolUp, $batasSLA2);
    if ($selisih == true) {
      $return = 1;
    } else {
      $return = 0;
    }
    return $return;
  }

  function getLeadsGroupByCustomerType($filter = NULL)
  {
    $where = "WHERE 1=1 ";
    // Posisi di atas karena skip filter escape tanda kutip (')
    if (isset($filter['platformDataIn'])) {
      if ($filter['platformDataIn'] != '') {
        $filter['platformDataIn'] = arr_sql($filter['platformDataIn']);
        $where .= " AND leads.platformData IN({$filter['platformDataIn']})";
      }
    }

    if (isset($filter['sourceLeadsIn'])) {
      if ($filter['sourceLeadsIn'] != '') {
        $filter['sourceLeadsIn'] = arr_sql($filter['sourceLeadsIn']);
        $where .= " AND leads.sourceData IN({$filter['sourceLeadsIn']})";
      }
    }

    if (isset($filter['deskripsiEventIn'])) {
      if ($filter['deskripsiEventIn'] != '') {
        $filter['deskripsiEventIn'] = arr_sql($filter['deskripsiEventIn']);
        $where .= " AND leads.deskripsiEvent IN({$filter['deskripsiEventIn']})";
      }
    }

    if (isset($filter['periodeCreatedLeads'])) {
      if ($filter['periodeCreatedLeads'] != '') {
        $created = $filter['periodeCreatedLeads'];
        $where .= " AND LEFT(leads.created_at,10) BETWEEN '{$created[0]}' AND '{$created[1]}' ";
      }
    }

    if (isset($filter['kabupatenIn'])) {
      if ($filter['kabupatenIn'] != '') {
        $filter['kabupatenIn'] = arr_sql($filter['kabupatenIn']);
        $where .= " AND leads.kabupaten IN({$filter['kabupatenIn']})";
      }
    }

    if (isset($filter['assignedDealerIn'])) {
      if ($filter['assignedDealerIn'] != '') {
        $filter['assignedDealerIn'] = arr_sql($filter['assignedDealerIn']);
        $where .= " AND leads.assignedDealer IN({$filter['assignedDealerIn']})";
      }
    }

    if (isset($filter['kodeTypeUnitIn'])) {
      if ($filter['kodeTypeUnitIn'] != '') {
        $filter['kodeTypeUnitIn'] = arr_sql($filter['kodeTypeUnitIn']);
        $where .= " AND leads.kodeTypeUnit IN({$filter['kodeTypeUnitIn']})";
      }
    }

    if (isset($filter['seriesMotorIn'])) {
      if ($filter['seriesMotorIn'] != '') {
        $filter['seriesMotorIn'] = arr_sql($filter['seriesMotorIn']);
        $where .= " AND leads.seriesMotor IN({$filter['seriesMotorIn']})";
      }
    }


    return $this->db_crm->query("SELECT COUNT(leads_id) count_cust_type, customerType, 
    CASE WHEN sourceData=28 THEN 'Invited' WHEN sourceData=29 THEN 'Non Invited' ELSE '' END customerTypeDesc
    FROM leads 
    $where
    GROUP BY sourceData");
  }

  function getLeadsGroupBySourceData($filter = NULL)
  {
    $where = "WHERE 1=1";
    if (isset($filter['customerType'])) {
      $where .= " AND leads.customerType='{$filter['customerType']}'";
    }
    return $this->db_crm->query("SELECT COUNT(leads_id) c,source_leads 
    FROM leads 
    JOIN ms_source_leads sl ON sl.id_source_leads=leads.sourceData
    $where
    GROUP BY sourceData");
  }

  function insertLeadsStage($data) //$data = array
  {
    $this->db_crm->insert('leads_history_stage', $data);
  }

  function getCountLeadsVsFollowUp($filter)
  {
    $where = "WHERE 1=1 ";
    if (isset($filter['is_md'])) {
      $where .= " AND dl.kode_dealer IS NULL ";
    }

    if (isset($filter['is_dealer'])) {
      $where .= " AND dl.kode_dealer IS NOT NULL ";
    }

    $last_id_kategori = "SELECT id_kategori_status_komunikasi
        FROM leads_follow_up lfu
        LEFT JOIN ms_dealer dl ON dl.kode_dealer=lfu.assignedDealer
        LEFT JOIN ms_status_fu sf ON sf.id_status_fu=lfu.id_status_fu
        $where AND lfu.leads_id=ld.leads_id 
        ORDER BY followUpKe DESC LIMIT 1
    ";

    $selisih_next = "SELECT DATEDIFF(LEFT(lfu.tglNextFollowUp,10),LEFT(lfu.tglFollowUp,10))
        FROM leads_follow_up lfu
        LEFT JOIN ms_dealer dl ON dl.kode_dealer=lfu.assignedDealer
        LEFT JOIN ms_status_fu sf ON sf.id_status_fu=lfu.id_status_fu
        $where AND lfu.leads_id=ld.leads_id 
        ORDER BY followUpKe DESC LIMIT 1
    ";

    $last_kodeHasilStatusFollowUp = "SELECT kodeHasilStatusFollowUp
      FROM leads_follow_up lfu
      LEFT JOIN ms_dealer dl ON dl.kode_dealer=lfu.assignedDealer
      LEFT JOIN ms_status_fu sf ON sf.id_status_fu=lfu.id_status_fu
      $where AND lfu.leads_id=ld.leads_id 
      ORDER BY followUpKe DESC LIMIT 1
      ";
    
    $all_kodeHasilStatusFollowUp = "SELECT kodeHasilStatusFollowUp
      FROM leads_follow_up lfu
      LEFT JOIN ms_dealer dl ON dl.kode_dealer=lfu.assignedDealer
      LEFT JOIN ms_status_fu sf ON sf.id_status_fu=lfu.id_status_fu
      $where AND lfu.leads_id=ld.leads_id 
      ";
    
    $last_statusProspek = "SELECT statusProspek
        FROM leads_follow_up lfu
        LEFT JOIN ms_dealer dl ON dl.kode_dealer=lfu.assignedDealer
        $where AND lfu.leads_id=ld.leads_id 
        ORDER BY followUpKe DESC LIMIT 1
    ";

    if (isset($filter['reset_md_d'])) {
      $where = "WHERE 1=1";
    }
    if (isset($filter['is_workload'])) {
      $folup = "SELECT COUNT(leads_id) FROM leads_follow_up WHERE leads_id=ld.leads_id";
      $where .= " AND ($folup)=0";
    }
    if (isset($filter['is_unreachable'])) {
      $where .= " AND ($last_id_kategori)=1 ";
    }
    if (isset($filter['is_unreachable_dealer'])) {
      $where .= " AND ($last_id_kategori)=1 ";
    }
    if (isset($filter['is_failed'])) {
      $where .= " AND ($last_id_kategori)=2 ";
    }
    if (isset($filter['is_rejected'])) {
      $where .= " AND ($last_id_kategori)=3 ";
    }
    if (isset($filter['is_contacted'])) {
      $where .= " AND ($last_id_kategori)=4 ";
    }
    if (isset($filter['platformDataIn'])) {
      if ($filter['platformDataIn'] != '') {
        $filter['platformDataIn'] = arr_sql($filter['platformDataIn']);
        $where .= " AND ld.platformData IN({$filter['platformDataIn']})";
      }
    }

    if (isset($filter['sourceLeadsIn'])) {
      if ($filter['sourceLeadsIn'] != '') {
        $filter['sourceLeadsIn'] = arr_sql($filter['sourceLeadsIn']);
        $where .= " AND ld.sourceData IN({$filter['sourceLeadsIn']})";
      }
    }

    if (isset($filter['deskripsiEventIn'])) {
      if ($filter['deskripsiEventIn'] != '') {
        $filter['deskripsiEventIn'] = arr_sql($filter['deskripsiEventIn']);
        $where .= " AND ld.deskripsiEvent IN({$filter['deskripsiEventIn']})";
      }
    }

    if (isset($filter['periodeCreatedLeads'])) {
      if ($filter['periodeCreatedLeads'] != '') {
        $created = $filter['periodeCreatedLeads'];
        $where .= " AND LEFT(ld.created_at,10) BETWEEN '{$created[0]}' AND '{$created[1]}' ";
      }
    }

    if (isset($filter['kabupatenIn'])) {
      if ($filter['kabupatenIn'] != '') {
        $filter['kabupatenIn'] = arr_sql($filter['kabupatenIn']);
        $where .= " AND ld.kabupaten IN({$filter['kabupatenIn']})";
      }
    }

    if (isset($filter['assignedDealerIn'])) {
      if ($filter['assignedDealerIn'] != '') {
        $filter['assignedDealerIn'] = arr_sql($filter['assignedDealerIn']);
        $where .= " AND ld.assignedDealer IN({$filter['assignedDealerIn']})";
      }
    }

    if (isset($filter['kodeTypeUnitIn'])) {
      if ($filter['kodeTypeUnitIn'] != '') {
        $filter['kodeTypeUnitIn'] = arr_sql($filter['kodeTypeUnitIn']);
        $where .= " AND ld.kodeTypeUnit IN({$filter['kodeTypeUnitIn']})";
      }
    }

    if (isset($filter['seriesMotorIn'])) {
      if ($filter['seriesMotorIn'] != '') {
        $filter['seriesMotorIn'] = arr_sql($filter['seriesMotorIn']);
        $where .= " AND ld.seriesMotor IN({$filter['seriesMotorIn']})";
      }
    }

    if (isset($filter['selisih_next_lebih_kecil_dari'])) {
      $where .= " AND ($selisih_next) < {$filter['selisih_next_lebih_kecil_dari']}";
    }

    if (isset($filter['selisih_next_between'])) {
      $selisih = $filter['selisih_next_between'];
      $where .= " AND ($selisih_next) BETWEEN {$selisih[0]} AND {$selisih[1]} ";
    }

    if (isset($filter['selisih_next_lebih_besar_dari'])) {
      $where .= " AND ($selisih_next) > {$filter['selisih_next_lebih_besar_dari']}";
    }

    if (isset($filter['kodeHasilStatusFollowUp'])) {
      $where .= " AND ($last_kodeHasilStatusFollowUp) = {$filter['kodeHasilStatusFollowUp']}";
      if ($filter['kodeHasilStatusFollowUp']==4) {
        // echo $where .="sss";
      }
    }
    
    if (isset($filter['kodeHasilStatusFollowUpIn'])) {
      $where .= " AND {$filter['kodeHasilStatusFollowUpIn']} IN ($all_kodeHasilStatusFollowUp)";
      if ($filter['kodeHasilStatusFollowUpIn']==3) {
        # code...
      }
    }

    if (isset($filter['idSPK_not_null'])) {
      if ($filter['idSPK_not_null'] != '') {
        $where .= " AND IFNULL(ld.idSPK,'')!=''";
      }
    }
    
    if (isset($filter['idSpk_null'])) {
      if ($filter['idSpk_null'] != '') {
        $where .= " AND IFNULL(ld.idSPK,'')=''";
      }
    }

    if (isset($filter['kodeHasilStatusFollowUpNotIn'])) {
      if ($filter['kodeHasilStatusFollowUpNotIn'] != '') {
        $where .= " AND ($last_kodeHasilStatusFollowUp) NOT IN({$filter['kodeHasilStatusFollowUpNotIn']})";
      }
    }

    if (isset($filter['not_contacted'])) {
      $where .= " AND ($last_id_kategori) != 4 ";
    }

    if (isset($filter['frameNo_not_null'])) {
      if ($filter['frameNo_not_null'] != '') {
        $where .= " AND IFNULL(ld.frameNo,'') != ''";
      }
    }
    if (isset($filter['frameNo_null'])) {
      if ($filter['frameNo_null'] != '') {
        $where .= " AND IFNULL(ld.frameNo,'') = ''";
      }
    }
    if (isset($filter['kodeIndent_not_null'])) {
      if ($filter['kodeIndent_not_null'] != '') {
        $where .= " AND IFNULL(ld.kodeIndent,'') != ''";
      }
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

    if (isset($filter['id_kategori_status_komunikasi'])) {
      if ($filter['id_kategori_status_komunikasi'] != '') {
        $where .= " AND ($last_id_kategori)='{$filter['id_kategori_status_komunikasi']}'";
      }
    }
   
    if (isset($filter['statusProspek'])) {
      if ($filter['statusProspek'] != '') {
        $where .= " AND ($last_statusProspek)='{$filter['statusProspek']}'";
      }
    }

    if (isset($filter['is_workload_dealer'])) {
      $folup = "SELECT COUNT(leads_id) FROM leads_follow_up WHERE leads_id=ld.leads_id AND IFNULL(assignedDealer,'')!=''";
      $where .= " AND ($folup)=0";
    }

    $group_by = '';
    if (isset($filter['group_by'])) {
      $group_by = "GROUP BY " . $filter['group_by'];
    }
    if (isset($filter['select'])) {
      $select = "ld.leads_id";
    } else {
      $select = "COUNT(DISTINCT ld.leads_id) count,ld.customerType,ld.sourceData";
    }
    return $this->db_crm->query("SELECT $select
    FROM leads ld
    LEFT JOIN ms_dealer dl ON dl.kode_dealer=ld.assignedDealer
    $where $group_by
    ");
  }

  function getInteraksiID()
  {
    $dmy = gmdate("dmY", time() + 60 * 60 * 7);
    $ym = tahun_bulan();
    $get_data  = $this->db_crm->query("SELECT RIGHT(interaksi_id,6)interaksi_id FROM leads_interaksi WHERE LEFT(created_at,7)='$ym'
                  ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row = $get_data->row();
      $new_kode = 'E20/ITR/' . $dmy . '/' . sprintf("%'.06d", $row->interaksi_id + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db_crm->get_where('leads_interaksi', ['interaksi_id' => $new_kode])->num_rows();
        if ($cek > 0) {
          $new_kode   = 'E20/ITR/' . $dmy . '/' . sprintf("%'.06d", substr($new_kode, -6) + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'E20/ITR/' . $dmy . '/000001';
    }
    return strtoupper($new_kode);
  }

  function getLeadsInteraksi($filter = null)
  {
    $where = 'WHERE 1=1';
    $select = '';

    $select = "ldi.leads_id,ldi.interaksi_id,ldi.nama,ldi.noHP,ldi.email,ldi.customerType,ldi.eventCodeInvitation,ldi.customerActionDate,ldi.idKabupaten,ldi.cmsSource,ldi.segmentMotor,ldi.seriesMotor,ldi.deskripsiEvent,ldi.kodeTypeUnit,ldi.kodeWarnaUnit,ldi.minatRidingTest,ldi.jadwalRidingTest,ldi.sourceData,ldi.platformData,ldi.noTelp,ldi.assignedDealer,ldi.sourceRefID,ldi.idProvinsi,ldi.idKelurahan,ldi.idKecamatan,ldi.frameNoPembelianSebelumnya,ldi.keterangan,ldi.promoUnit,ldi.facebook,ldi.instagram,ldi.twitter,ldi.created_at,
    CASE WHEN msl.id_source_leads IS NULL THEN sourceData ELSE msl.source_leads END deskripsiSourceData,
    CASE WHEN mpd.id_platform_data IS NULL THEN platformData ELSE mpd.platform_data END deskripsiPlatformData,
    CASE WHEN cs.kode_cms_source IS NULL THEN cmsSource ELSE cs.deskripsi_cms_source END deskripsiCmsSource,
    CASE WHEN minatRidingTest=1 THEN 'Ya' WHEN minatRidingTest=0 THEN 'Tidak' Else '-' END minatRidingTestDesc,
    prov.provinsi,kab.kabupaten_kota kabupaten,kec.kecamatan,kel.kelurahan
      ";

    if ($filter != null) {

      //Filter Escaped String Like Singe Quote (')
      $filter = $this->db_crm->escape_str($filter);
      if (isset($filter['leads_id'])) {
        if ($filter['leads_id'] != '') {
          $where .= " AND ldi.leads_id='{$this->db_crm->escape_str($filter['leads_id'])}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db_crm->escape_str($filter['search']);
          $where .= " AND ( ldi.leads_id LIKE'%{$filter['search']}%'
        )";
        }
      }
    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, 'nama', 'kodeTypeUnit', 'seriesMotor', 'segmentMotor', 'jadwalRidingTest', 'deskripsi_cms_source', 'msl.source_leads', 'mpd.platform_data', 'customerActionDate', 'sourceRefID'];
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

    $group_by = '';
    if (isset($filter['group_by'])) {
      $group_by = "GROUP BY " . $filter['group_by'];
    }

    return $this->db_crm->query("SELECT $select
  FROM leads_interaksi AS ldi
  LEFT JOIN ms_source_leads msl ON msl.id_source_leads=ldi.sourceData
  LEFT JOIN ms_platform_data mpd ON mpd.id_platform_data=ldi.platformData
  LEFT JOIN ms_maintain_tipe tpu ON tpu.kode_tipe=ldi.kodeTypeUnit
  LEFT JOIN ms_maintain_warna twu ON twu.kode_warna=ldi.kodeWarnaUnit
  LEFT JOIN ms_maintain_cms_source cs ON cs.kode_cms_source=ldi.cmsSource
  LEFT JOIN ms_maintain_provinsi prov ON prov.id_provinsi=ldi.idProvinsi
  LEFT JOIN ms_maintain_kabupaten_kota kab ON kab.id_kabupaten_kota=ldi.idKabupaten
  LEFT JOIN ms_maintain_kecamatan kec ON kec.id_kecamatan=ldi.idKecamatan
  LEFT JOIN ms_maintain_kelurahan kel ON kel.id_kelurahan=ldi.idKelurahan
  $where
  $group_by
  $order_data
  $limit
  ");
  }

  function post_to_api3($leads_id, $interaksi_id = false,$batasSLA2=null)
  {

    $this->load->helper('api');
    $this->load->model('dealer_model', 'dlm');
    $this->load->model('kelurahan_model', 'kelm');
    $this->load->model('sumber_prospek_model', 'sprm');
    $leads = $this->getLeads(['leads_id' => $leads_id])->row();
    $fp = ['id_cdb' => $leads->sourceData];
    $sumber_prospek = $this->sprm->getSumberProspekFromOtherDB($fp)->row()->id;
    $id_event = $this->db->query("SELECT id_event FROM ms_event WHERE nama_event='$leads->deskripsiEvent' OR ms_event.description='$leads->deskripsiEvent'")->row();
    $riding = explode(' ', $leads->jadwalRidingTest);
    $prospek = [
      'leads_id' => $leads->leads_id,
      'kode_dealer_md' => $leads->assignedDealer,
      'id_karyawan_dealer' => $leads->id_karyawan_dealer,
      'id_customer' => $leads->customerId,
      'nama_konsumen' => $leads->nama,
      'no_ktp' => $leads->noKtp,
      'no_npwp' => $leads->npwp,
      'no_kk' => $leads->noKK,
      'jenis_wn' => $leads->jenisKewarganegaraan,
      'jenis_kelamin' => $leads->gender == 1 ? 'Pria' : 'Wanita',
      'tempat_lahir' => $leads->tempatLahir,
      'tgl_lahir' => $leads->tanggalLahir,
      'id_kelurahan' => $leads->kelurahan,
      'alamat' => $leads->alamat,
      'agama' => $leads->idAgama,
      'no_hp' => $leads->noHP,
      'no_telp' => $leads->noTelp,
      'merk_sebelumnya' => $leads->idMerkMotorYangDimilikiSekarang,
      'jenis_sebelumnya' => $leads->idJenisMotorYangDimilikiSekarang,
      'pemakai_motor' => $leads->yangMenggunakanSepedaMotor,
      'email' => $leads->email,
      'status_nohp' => $leads->statusNoHp,
      'status_prospek' => $leads->statusProspek,
      'id_tipe_kendaraan' => $leads->kodeTypeUnit,
      'id_warna' => $leads->kodeWarnaUnit,
      'sumber_prospek' => $sumber_prospek,
      'longitude' => $leads->longitude,
      'latitude' => $leads->latitude,
      'pekerjaan' => (int)$leads->kodePekerjaanKtp,
      'sub_pekerjaan' => (int)$leads->kodePekerjaan,
      'created_at' => waktu(),
      'tgl_prospek' => tanggal(),
      'noFramePembelianSebelumnya' => $leads->noFramePembelianSebelumnya,
      'facebook' => $leads->facebook,
      'instagram' => $leads->instagram,
      'twitter' => $leads->twitter,
      'customerType' => $leads->customerType,
      'batasOnTimeSLA2' => $batasSLA2==null?$leads->batasOntimeSLA2:$batasSLA2,
      'platformData' => $leads->platformData,
      'periodeAwalEvent' => $leads->periodeAwalEvent,
      'periodeAkhirEvent' => $leads->periodeAkhirEvent,
      'deskripsiEvent' => $leads->deskripsiEvent,
      'tanggalNextFU' => $leads->tanggalNextFU,
      'keteranganNextFollowUp' => $leads->keteranganNextFollowUp,
      'test_ride_preference' => $leads->minatRidingTest,
      'tgl_tes_kendaraan' => $riding[0]==''?null:$riding[0],
      'jam_tes_kendaraan' => isset($riding[1]) ? $riding[1] : null,
      'id_event' => $id_event == null ? null : $id_event->id_event,
    ];
    if ($interaksi_id == false) {
      $ld = ['leads_id' => $leads->leads_id];
      $interaksi = $this->db_crm->get_where('leads_interaksi', $ld)->result();
      return ['prospek' => $prospek, 'interaksi' => $interaksi];
    } else {
      $ld = ['leads_id' => $leads->leads_id, 'interaksi_id' => $interaksi_id];
      $interaksi = $this->db_crm->get_where('leads_interaksi', $ld)->result();
      return ['interaksi' => $interaksi];
    }
  }

  function getLeadsBelumAssignDealer($select = false)
  {
    $leads = [];
    // Cek VE
    $belum_assign_dealer = [
      'assignedDealerIsNULL' => true,
      'kodeHasilStatusFollowUpIn' => [1],
      'last_kodeHasilStatusFollowUp' => 1,
      'need_fu_md' => 1
    ];
    if ($select == false) {
      $belum_assign_dealer['select'] = 'count';
    }
    $ve = $this->getLeads($belum_assign_dealer);
    if ($select == false) {
      $ve = $ve->row()->count;
    } else {
      foreach ($ve->result() as $v) {
        $leads[] = $v->leads_id;
      }
    }

    // Cek Non-VE
    $belum_assign_dealer = [
      'assignedDealerIsNULL' => true,
      'need_fu_md' => 0
    ];
    if ($select == false) {
      $belum_assign_dealer['select'] = 'count';
    }
    $non_ve = $this->getLeads($belum_assign_dealer);
    if ($select == false) {
      return $ve + $non_ve->row()->count;
    } else {
      foreach ($non_ve->result() as $v) {
        $leads[] = $v->leads_id;
      }
      return $leads;
    }
  }

  function getLeadsNonVEBelumAssignedDealer($leads_id = null)
  {
    $where = '';
    if ($leads_id != null) {
      $where = " AND ld.leads_id='$leads_id'";
    }
    return $this->db_crm->query("SELECT leads_id,ld.nama,kab.kabupaten_kota,segmentMotor,seriesMotor,kodeDealerPembelianSebelumnya,CONCAT(tp.deskripsi_tipe,'-',twu.deskripsi_warna) tipeWarnaMotor
            FROM leads ld
            LEFT JOIN ms_maintain_kabupaten_kota kab ON kab.id_kabupaten_kota=ld.kabupaten
            LEFT JOIN ms_maintain_tipe tp ON tp.kode_tipe=ld.kodeTypeUnit
            LEFT JOIN ms_maintain_warna twu ON twu.kode_warna=ld.kodeWarnaUnit
            WHERE sourceData NOT IN(28,29)
            AND (SELECT COUNT(leads_id) FROM leads_history_assigned_dealer WHERE leads_id=ld.leads_id)=0
            $where
          ");
  }

  function setInterasksiFromStageToLeads($leads_id, $pst)
  {
    $interaksi_id = $this->ld_m->getInteraksiID();
    $insert_interaksi = [
      'leads_id' => $leads_id,
      'interaksi_id' => $interaksi_id,
      'nama' => clear_removed_html($pst['nama']),
      'noHP' => $pst['no_hp'],
      'email' => $pst['email'],
      'customerType' => clear_removed_html($pst['customerType']),
      'eventCodeInvitation' => $pst['eventCodeInvitation'],
      'customerActionDate' => clear_removed_html($pst['customerActionDate']),
      'idKabupaten' => clear_removed_html($pst['kabupaten']),
      'cmsSource' => clear_removed_html($pst['cmsSource']),
      'segmentMotor' => clear_removed_html($pst['segmentMotor']),
      'seriesMotor' => clear_removed_html($pst['seriesMotor']),
      'deskripsiEvent' => clear_removed_html($pst['deskripsiEvent']),
      'kodeTypeUnit' => clear_removed_html($pst['kodeTypeUnit']),
      'kodeWarnaUnit' => clear_removed_html($pst['kodeWarnaUnit']),
      'minatRidingTest' => clear_removed_html($pst['minatRidingTest']),
      'jadwalRidingTest' => clear_removed_html($pst['jadwalRidingTest']) == '' ? NULL : clear_removed_html($pst['jadwalRidingTest']),
      'sourceData' => $pst['sourceData'],
      'platformData' => clear_removed_html($pst['platformData']),
      'noTelp' => $pst['noTelp'] ?: null,
      'assignedDealer' => clear_removed_html($pst['assignedDealer']),
      'sourceRefID' => clear_removed_html($pst['sourceRefID']),
      'idProvinsi' => clear_removed_html($pst['provinsi']),
      'idKelurahan' => clear_removed_html($pst['kelurahan']),
      'idKecamatan' => clear_removed_html($pst['kecamatan']),
      'frameNoPembelianSebelumnya' => $pst['cdb'] == NULL ? clear_removed_html($pst['noFramePembelianSebelumnya']) : $pst['cdb']->frameNoSebelumnya,
      'keterangan' => clear_removed_html($pst['keterangan']),
      'promoUnit' => clear_removed_html($pst['promoUnit']),
      'facebook' => clear_removed_html($pst['facebook']),
      'instagram' => clear_removed_html($pst['instagram']),
      'twitter' => clear_removed_html($pst['twitter']),
      'created_at' => waktu(),
    ];
    $this->db_crm->insert('leads_interaksi', $insert_interaksi);
    //Set Stage Sudah Dibuat Menjadi Leads(Interaksi)
    $setleads = [
      'setleads' => 1,
    ];
    $this->db_crm->update('staging_table_leads', $setleads, ['stage_id' => $pst['stage_id']]);

    //Cek Apakah Leads Sudah Prospek. Jika Sudah Prospek Kirim Interaksi Ke NMS
    $cek_prospek = $this->db_crm->query("SELECT idProspek FROM leads WHERE leads_id='$leads_id' AND IFNULL(idProspek,'')!=''")->row();
    if ($cek_prospek != null) {
      //Melakukan Pengiriman API 3 Hanya Interaksi Saja
      $data = $this->ld_m->post_to_api3($leads_id, $interaksi_id);
      send_api_post($data, 'mdms', 'nms', 'api_3');
    }
  }
}
