<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_dealer_prospek extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function getProspek($filter = NULL)
  {
    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer = dealer()->id_dealer;
    }
    $where = "WHERE tr_prospek.active = '1' AND tr_prospek.id_dealer = '$id_dealer' ";
    $tot_interaksi = "SELECT COUNT(id_prospek) FROm tr_prospek_interaksi WHERE id_prospek=tr_prospek.id_prospek";

    if (isset($filter['id_prospek'])) {
      if ($filter['id_prospek'] != '') {
        $where .= " AND tr_prospek.id_prospek='{$filter['id_prospek']}'";
      }
    }
    if (isset($filter['id_tipe_kendaraan'])) {
      if ($filter['id_tipe_kendaraan'] != '') {
        $where .= " AND tr_prospek.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
      }
    }
    if (isset($filter['id_karyawan_dealer'])) {
      if ($filter['id_karyawan_dealer'] != '') {
        $where .= " AND tr_prospek.id_karyawan_dealer='{$filter['id_karyawan_dealer']}'";
      }
    }
    if (isset($filter['id_warna'])) {
      if ($filter['id_warna'] != '') {
        $where .= " AND tr_prospek.id_warna='{$filter['id_warna']}'";
      }
    }
    if (isset($filter['id_prospek_int'])) {
      if ($filter['id_prospek_int'] != '') {
        $where .= " AND tr_prospek.id_prospek_int='{$filter['id_prospek_int']}'";
      }
    }
    if (isset($filter['id_karyawan_dealer_int'])) {
      if ($filter['id_karyawan_dealer_int'] != '') {
        $where .= " AND ms_karyawan_dealer.id_karyawan_dealer_int='{$filter['id_karyawan_dealer_int']}'";
      }
    }
    if (isset($filter['id_karyawan_dealer_in'])) {
      if ($filter['id_karyawan_dealer_in'] != '') {
        $where .= " AND ms_karyawan_dealer.id_karyawan_dealer IN({$filter['id_karyawan_dealer_in']})";
      }
    }
    if (isset($filter['status_prospek'])) {
      if ($filter['status_prospek'] != '') {
        $where .= " AND tr_prospek.status_prospek='{$filter['status_prospek']}'";
      }
    }
    if (isset($filter['status_prospek_not'])) {
      if ($filter['status_prospek_not'] != '') {
        $where .= " AND tr_prospek.status_prospek!='{$filter['status_prospek_not']}'";
      }
    }
    if (isset($filter['status_prospek_tidak_sama'])) {
      if ($filter['status_prospek_tidak_sama'] != '') {
        $where .= " AND tr_prospek.status_prospek!='{$filter['status_prospek_tidak_sama']}'";
      }
    }
    if (isset($filter['bulan_prospek'])) {
      if ($filter['bulan_prospek'] != '') {
        $where .= " AND LEFT(tr_prospek.created_at,7)='{$filter['bulan_prospek']}'";
      }
    }
    if (isset($filter['periode_prospek'])) {
      $periode = $filter['periode_prospek'];
      $where .= " AND LEFT(tr_prospek.created_at,10) BETWEEN '{$periode[0]}' AND '{$periode[1]}'";
    }
    if (isset($filter['id_customer_in_spk'])) {
      if ($filter['id_customer_in_spk'] == true) {
        $cek = "IN";
      } else {
        $cek = "NOT IN";
      }
      $where .= " AND id_customer $cek (SELECT id_customer FROM tr_spk 
        JOIN tr_sales_order ON tr_sales_order.no_spk=tr_spk.no_spk
        WHERE tr_sales_order.status_delivery IS NOT NULL AND id_customer=tr_prospek.id_customer
         )";
    }
    if (isset($filter['belum_fu'])) {
      if ($filter['belum_fu'] != '') {
        $where .= " AND ((SELECT COUNT(id) FROM tr_prospek_fol_up WHERE id_prospek=tr_prospek.id_prospek AND check_date IS NULL)>0 AND status_prospek!='Deal')";
      }
    }
    if (isset($filter['sudah_fu'])) {
      if ($filter['sudah_fu'] != '') {
        $where .= " AND ((SELECT COUNT(id) FROM tr_prospek_fol_up WHERE id_prospek=tr_prospek.id_prospek AND check_date IS NULL)=0 OR status_prospek='Deal')";
      }
    }
    if (isset($filter['ada_sales'])) {
      $where .= " AND CASE WHEN IFNULL(tr_prospek.id_karyawan_dealer,'')='' THEN 0 ELSE 1 END={$filter['ada_sales']} ";
    }
    if (isset($filter['tot_interaksi_lebih_dari'])) {
      $where .= " AND ($tot_interaksi) > {$filter['tot_interaksi_lebih_dari']} ";
    }
    $tot_folup = "SELECT COUNT(id_prospek) FROM tr_prospek_fol_up WHERE id_prospek=tr_prospek.id_prospek";
    if (isset($filter['tot_folup'])) {
      $where .= " AND ($tot_folup)={$filter['tot_folup']} ";
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (tr_prospek.nama_konsumen LIKE '%$search%'
                            OR tr_prospek.id_prospek LIKE '%$search%'
                            ) 
            ";
      }
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if (isset($order['field'])) {
          $order = "ORDER BY {$order['field']} {$order['order']}";
        } else {

          $order_column = [];
          $order_clm  = $order_column[$order['0']['column']];
          $order_by   = $order['0']['dir'];
          $order .= " ORDER BY $order_clm $order_by ";
        }
      } else {
        $order .= " ORDER BY tr_prospek.created_at DESC ";
      }
    } else {
      $order = "ORDER BY tr_prospek.created_at DESC";
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    if (isset($filter['page'])) {
      $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
      $page = $page < 0 ? 0 : $page;
      $length = 10;
      // $start = $page == 1 ? 0 : $length * ($page - 1);
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }
    $event = "SELECT nama_event FROM ms_event WHERE id_event=tr_prospek.id_event LIMIT 1";

    $ontimeSLA2_desc = "CASE WHEN ontimeSLA2=1 THEN 'On Track' 
              WHEN ontimeSLA2=0 THEN 'Overdue' 
              ELSE 
                CASE WHEN IFNULL(batasOntimeSLA2,'')='' THEN
                  CASE WHEN TIMEDIFF(tr_prospek.created_at,now()) < 0 THEN 'Overdue' ELSE 'On Track' END
                ELSE
                  CASE WHEN TIMEDIFF(batasOntimeSLA2,now()) < 0 THEN 'Overdue' ELSE 'On Track' END
                END
              END
          ";
    $pernahTerhubung = 'SELECT id_kategori_status_komunikasi FROM tr_prospek_fol_up WHERE id_prospek=tr_prospek.id_prospek ORDER BY id DESC LIMIT 1';
    $hasil_fu = "SELECT CASE 
                        WHEN kodeHasilStatusFollowUp=1 THEN 'Prospect'
                        WHEN kodeHasilStatusFollowUp=2 THEN 'Not Prospect'
                        WHEN kodeHasilStatusFollowUp=3 THEN 'Deal'
                        WHEN kodeHasilStatusFollowUp=4 THEN 'Not Deal'
                      END
                 FROM tr_prospek_fol_up WHERE id_prospek=tr_prospek.id_prospek ORDER BY id DESC LIMIT 1";
    $jml_fu = 'SELECT COUNT(id_prospek) FROM tr_prospek_fol_up WHERE id_prospek=tr_prospek.id_prospek';
    $tgl_next_fol_up = 'SELECT tgl_next_fol_up FROM tr_prospek_fol_up WHERE id_prospek=tr_prospek.id_prospek ORDER BY id DESC LIMIT 1';
    $select = "tr_prospek.*,ms_karyawan_dealer.nama_lengkap,
    CASE WHEN tr_prospek.customerType='R' THEN 'Reguler' ELSE 'VIP' END AS customerTypeDesc,
    kel.kelurahan AS kel,
    kec.kecamatan AS kec,
    kab.kabupaten AS kab,
    prov.provinsi AS prov,
    kel_kantor.kelurahan AS kel_kantor,
    kec_kantor.kecamatan AS kec_kantor,
    kab_kantor.kabupaten AS kab_kantor,
    ms_karyawan_dealer.id_karyawan_dealer_int,
    prov_kantor.provinsi AS prov_kantor,tr_prospek.id_event,event.nama_event,event.start_date start_date_event, event.end_date end_date_event, tr_prospek.no_hp,plf.platform_data, 
    CASE WHEN ps.id IS NULL THEN tr_prospek.sumber_prospek ELSE ps.description END AS sumber_prospek_name,
    LEFT(tr_prospek.created_at,10) tgl_assign,RIGHT(tr_prospek.created_at,8) jam_assign,($ontimeSLA2_desc)  ontimeSLA2_desc, CASE WHEN ($pernahTerhubung) = 4 THEN 'Ya' ELSE 'Tidak' END pernahTerhubung,($hasil_fu) hasil_fu,($jml_fu) jml_fu,($tgl_next_fol_up) tgl_next_fol_up
    ";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(tr_prospek.id_prospek) AS count";
      } elseif ($filter['select'] == 'show_prospek_mobile') {
        $select = "id_prospek_int AS id,id_prospek,
        customer_image AS image,
        nama_konsumen AS name,
        tk.tipe_ahm AS produk_name,
        status_prospek AS status,
        jenis_kelamin,
        0 AS assigned,
        0 follow_up,
        plf.platform_data
        ";
      } elseif ($filter['select'] == 'customer_detail_sc') {
        $select = "
        tgl_prospek AS prospek_date,
        customer_image AS image,
        nama_konsumen AS name,
        tr_prospek.no_ktp AS ktp,
        tr_prospek.no_ktp AS nik,
        tr_prospek.no_ktp AS customer_ktp,
        CASE WHEN tr_prospek.jenis_kelamin='Pria' OR tr_prospek.jenis_kelamin='Laki-laki' THEN 'L' ELSE 'P' END AS jenis_kelamin,
        tr_prospek.no_telp AS no_telepon,
        tr_prospek.no_hp AS no_hp,
        tr_prospek.alamat AS address,
        latitude,
        longitude,
        tr_prospek.pekerjaan AS pekerjaan_id,
        pkj.pekerjaan AS pekerjaan_name,
        spkj.sub_pekerjaan AS sub_pekerjaan_name,
        no_telp_kantor AS office_phone,
        alamat_kantor AS office_address,
        CASE WHEN ps.id IS NULL THEN tr_prospek.sumber_prospek ELSE ps.id END AS sumber_prospek_id,
        CASE WHEN ps.id IS NULL THEN tr_prospek.sumber_prospek ELSE ps.description END AS sumber_prospek_name,
        metode_follow_up_id AS metode_follow_up_id,
        IFNULL(fol.name,'') AS metode_fol_up_name,
        tk.id_tipe_kendaraan_int AS unit_id,
        tk.tipe_ahm AS unit_name,
        tk.id_tipe_kendaraan unit_code,
        tgl_tes_kendaraan AS preferensi_uji_perjalanan,
        tr_prospek.tempat_lahir,
        tr_prospek.tgl_lahir,
        tr_prospek.kodepos,
        tr_prospek.email,tr_prospek.rencana_pembelian,
        ms_karyawan_dealer.id_karyawan_dealer_int,
        plf.platform_data
        ";
      } elseif ($filter['select'] == 'all') {
        $select = "tr_prospek.*,ms_dealer.kode_dealer_md,ms_dealer.nama_dealer,ms_karyawan_dealer.id_flp_md,ms_karyawan_dealer.honda_id,ms_karyawan_dealer.nama_lengkap,tk.tipe_ahm,pkj.pekerjaan";
      }
    }
    // send_json($order);
    return $this->db->query("SELECT 
    $select
    FROM tr_prospek 
      LEFT JOIN ms_dealer ON tr_prospek.id_dealer=ms_dealer.id_dealer
			LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
			LEFT JOIN ms_kelurahan kel ON tr_prospek.id_kelurahan=kel.id_kelurahan
			LEFT JOIN ms_kecamatan kec ON tr_prospek.id_kecamatan=kec.id_kecamatan
			LEFT JOIN ms_kabupaten kab ON tr_prospek.id_kabupaten=kab.id_kabupaten
			LEFT JOIN ms_provinsi prov ON tr_prospek.id_provinsi=prov.id_provinsi
			LEFT JOIN ms_tipe_kendaraan ON tr_prospek.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
      LEFT JOIN ms_kelurahan kel_kantor ON tr_prospek.id_kelurahan_kantor=kel_kantor.id_kelurahan
      LEFT JOIN ms_kecamatan kec_kantor ON kel_kantor.id_kecamatan=kec_kantor.id_kecamatan
      LEFT JOIN ms_kabupaten kab_kantor ON kec_kantor.id_kabupaten=kab_kantor.id_kabupaten
      LEFT JOIN ms_provinsi prov_kantor ON kab_kantor.id_provinsi=prov_kantor.id_provinsi
      LEFT JOIN ms_pekerjaan pkj ON tr_prospek.pekerjaan=pkj.id_pekerjaan
      LEFT JOIN ms_sub_pekerjaan spkj ON tr_prospek.sub_pekerjaan=spkj.id_sub_pekerjaan
      LEFT JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=tr_prospek.id_tipe_kendaraan
      LEFT join sc_ms_metode_follow_up fol ON fol.id=tr_prospek.metode_follow_up_id
      LEFT JOIN ms_sumber_prospek ps ON ps.id_dms=tr_prospek.sumber_prospek
      LEFT JOIN ms_platform_data plf ON plf.id_platform_data=tr_prospek.platformData
      left JOIN ms_event event ON event.id_event=tr_prospek.id_event and tr_prospek.id_dealer = event.id_dealer 
			$where
      $order
      $limit
			");
  }

  function getEvent()
  {
	$id_dealer         = $this->m_admin->cari_dealer();
	$date_now = date("Y-m-d");
    return $this->db->query("SELECT id_event, nama_event FROM ms_event where status ='approved' and '$date_now' >= start_date and '$date_now' <= end_date and (sumber = 'E20' or id_dealer ='$id_dealer') ORDER BY created_at DESC");
  }

  public function getProgramUmum($filter)
  {
    $id_dealer         = $this->m_admin->cari_dealer();
    $id_tipe_kendaraan = $filter['id_tipe_kendaraan'];
    $id_warna          = $filter['id_warna'];
    $jenis_beli          = $filter['jenis_beli'];

    $dt = date('Y-m-d');
    $cek_program = $this->db->query("SELECT *,(ahm_cash+md_cash+dealer_cash+other_cash) as tot_cash,(ahm_kredit+md_kredit+dealer_kredit+other_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd, 
				(SELECT count(id_program_md)FROM tr_sales_program_gabungan WHERE tr_sales_program_gabungan.id_program_md_gabungan=pmd) as tot_gabungan 
			FROM tr_sales_program 
			inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md
			INNER JOIN ms_jenis_sales_program ON tr_sales_program.id_jenis_sales_program = ms_jenis_sales_program.id_jenis_sales_program 
			WHERE tr_sales_program_tipe.id_tipe_kendaraan='$id_tipe_kendaraan' 
			AND id_warna LIKE '%$id_warna%' 
			AND ms_jenis_sales_program.jenis_sales_program <> 'Group Customer'
			AND tr_sales_program_tipe.status<>'new' 
			AND '$dt' between tr_sales_program.periode_awal AND tr_sales_program.periode_akhir
      AND
            (CASE 
            	WHEN tr_sales_program.kuota_program>0 
            	THEN 1=1 
            	ELSE 
            		CASE
            			WHEN (SELECT COUNT(id_dealer) FROM tr_sales_program_dealer WHERE id_program_md=tr_sales_program.id_program_md)>0
            			THEN
            				'$id_dealer' IN (SELECT id_dealer FROM tr_sales_program_dealer WHERE id_program_md=tr_sales_program.id_program_md) 
            			ELSE
            			1=1
            		END
            END)
            ");
    if (strtolower($jenis_beli) == 'cash') {
      foreach ($cek_program->result() as $rs) {
        if ($rs->tot_cash > 0) {
          $result[] = ['id_program_md' => $rs->id_program_md, 'judul_kegiatan' => $rs->judul_kegiatan, 'price' => $rs->tot_cash];
        }
      }
    } elseif (strtolower($jenis_beli) == 'kredit') {
      foreach ($cek_program->result() as $rs) {
        if ($rs->tot_kredit > 0) {
          $result[] = ['id_program_md' => $rs->id_program_md, 'judul_kegiatan' => $rs->judul_kegiatan, 'price' => $rs->tot_kredit];
        }
      }
    }
    if (isset($result)) {
      return $result;
    }
  }
  public function getProgramTambahan()
  {
    $id_program_md = $this->input->post('id_program_md');
    $jenis_beli = $this->input->post('jenis_beli');
    $id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
    $id_warna = $this->input->post('id_warna');
    $dt = date('Y-m-d');
    $id_dealer = $this->m_admin->cari_dealer();
    // $cek_ketersediaan=$this->db->query("SELECT * FROM tr_sales_program_dealer
    // 									LEFT JOIN tr_sales_program on tr_sales_program_dealer.id_program_md=tr_sales_program.id_program_md
    // 									WHERE tr_sales_program_dealer.id_program_md='$id_program_md' AND tr_sales_program_dealer.id_dealer='$id_dealer'
    // 									");
    $cek_program = $this->db->get_where('tr_sales_program', ['id_program_md' => $id_program_md])->row();
    //Semua Dealer
    if ($cek_program->kuota_program == 0 || $cek_program->kuota_program == NULL) {
      $cek_dealer = $this->db->get_where('tr_sales_program_dealer', ['id_program_md' => $cek_program->id_program_md]);
      if ($cek_dealer->num_rows() == 0) {
        $tersedia = 1;
      } else {
        $dealer = $this->db->get_where('tr_sales_program_dealer', ['id_program_md' => $id_program_md, 'id_dealer' => $id_dealer]);
        if ($dealer->num_rows() > 0) {
          $dealer = $dealer->row();
          $cek_terjual = $this->db->query("SELECT * FROM tr_spk
		 									   WHERE tr_spk.program_umum='$id_program_md' 
		 									   AND tr_spk.id_dealer='$id_dealer'
		 									   AND status_spk<>'closed'
		 				")->num_rows();
          $tersedia = $cek_terjual <= $dealer->kuota ? $dealer->kuota - $cek_terjual : 0;
        } else {
          $tersedia = 0;
        }
      }
    } else {
      $cek_terjual = $this->db->query("SELECT * FROM tr_spk
		 									   WHERE tr_spk.program_umum='$id_program_md' 
		 									   AND status_spk<>'closed'
		 				")->num_rows();
      $tersedia = $cek_terjual <= $cek_program->kuota_program ? $cek_program->kuota_program - $cek_terjual : 0;
    }

    if ($tersedia > 0) {
      $nilai_voucher_program = $this->db->query("SELECT *,(ahm_cash+md_cash+dealer_cash+other_cash) as tot_cash,(ahm_kredit+md_kredit+dealer_kredit+other_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd, (SELECT count(id_program_md)FROM tr_sales_program_gabungan WHERE tr_sales_program_gabungan.id_program_md_gabungan=pmd) as tot_gabungan FROM tr_sales_program inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md WHERE tr_sales_program_tipe.id_tipe_kendaraan='$id_tipe_kendaraan' AND id_warna LIKE '%$id_warna%' AND tr_sales_program_tipe.status<>'new' AND '$dt' between tr_sales_program.periode_awal AND tr_sales_program.periode_akhir  AND tr_sales_program.id_program_md='$id_program_md' ");
      if ($nilai_voucher_program->num_rows() > 0) {
        $program = $nilai_voucher_program->row();
        if ($jenis_beli == 'Cash') {
          $nilai = $nilai_voucher_program->row();
          $nilai_voucher_program = $nilai->tot_cash;
        } elseif ($jenis_beli == 'Kredit') {
          $nilai = $nilai_voucher_program->row();
          $nilai_voucher_program = $nilai->tot_kredit;
        }
        if ($program->id_jenis_sales_program == 'SP-002') {
          $nilai_voucher_program = 0;
        }
      } else {
        $nilai_voucher_program = '';
      }
      echo $nilai_voucher_program . '##';
      // $cek_program = $this->db->query("SELECT * FROM tr_sales_program_gabungan WHERE id_program_md='$id_program_md' OR id_program_md_gabungan");
      $cek_program = $this->db->query("SELECT DISTINCT(id_program_md)as id_program_md_gabungan FROM(
				SELECT id_program_md FROM tr_sales_program_gabungan WHERE id_program_md='$id_program_md' OR id_program_md_gabungan='$id_program_md'
				UNION
				SELECT id_program_md_gabungan FROM tr_sales_program_gabungan WHERE id_program_md=(SELECT id_program_md FROM tr_sales_program_gabungan WHERE id_program_md_gabungan='$id_program_md') OR id_program_md='$id_program_md' ) as tbl_gabungan
				WHERE id_program_md<>'$id_program_md'
			");
      if ($jenis_beli == 'Cash') {
        echo "<option> - choose- </option>";
        $x = 0;
        foreach ($cek_program->result() as $rs) {
          $cek_program = $this->db->query("SELECT *,(ahm_cash+md_cash+dealer_cash+other_cash) as tot_cash,(ahm_kredit+md_kredit+dealer_kredit+other_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd, (SELECT count(id_program_md)FROM tr_sales_program_gabungan WHERE tr_sales_program_gabungan.id_program_md_gabungan=pmd) as tot_gabungan FROM tr_sales_program inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md WHERE tr_sales_program_tipe.id_tipe_kendaraan='$id_tipe_kendaraan' AND tr_sales_program.id_program_md='$rs->id_program_md_gabungan' AND id_warna LIKE '%$id_warna%' AND tr_sales_program_tipe.status<>'new' AND '$dt' between tr_sales_program.periode_awal AND tr_sales_program.periode_akhir");
          if ($cek_program->num_rows() > 0) {
            $ck = $cek_program->row();
            if ($ck->tot_cash > 0) {
              echo "<option value='$ck->id_program_md'>$ck->id_program_md | $ck->judul_kegiatan</option>";
              $x++;
            }
          }
        }
        echo "##$x";
      } elseif ($jenis_beli == 'Kredit') {
        echo "<option> - choose- </option>";
        $xx = 0;
        foreach ($cek_program->result() as $rs) {
          $cek_program = $this->db->query("SELECT *,(ahm_cash+md_cash+dealer_cash+other_cash) as tot_cash,(ahm_kredit+md_kredit+dealer_kredit+other_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd, (SELECT count(id_program_md)FROM tr_sales_program_gabungan WHERE tr_sales_program_gabungan.id_program_md_gabungan=pmd) as tot_gabungan FROM tr_sales_program inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md WHERE tr_sales_program_tipe.id_tipe_kendaraan='$id_tipe_kendaraan' AND tr_sales_program.id_program_md='$rs->id_program_md_gabungan' AND id_warna LIKE '%$id_warna%' AND tr_sales_program_tipe.status<>'new' AND '$dt' between tr_sales_program.periode_awal AND tr_sales_program.periode_akhir");
          if ($cek_program->num_rows() > 0) {
            $ck = $cek_program->row();
            if ($ck->tot_kredit > 0) {
              echo "<option value='$ck->id_program_md'>$ck->id_program_md | $ck->judul_kegiatan</option>";
            }
          }
          $xx++;
        }
        echo "##$xx";
      }
      $program_tipe = $this->db->get_where('tr_sales_program_tipe', ['id_program_md' => $id_program_md])->row();
      echo "##$program->jenis_barang";
    } elseif ($tersedia == 0) {
      echo "Penjualan dengan program ini sudah mencapai kuota##";
    }
  }

  function getIDProspek($sumber_prospek, $id_dealer = NULL, $is_mobile = false)
  {
    $thn_bln     = date('Y-m');
    $thbln     = date('y/m');
    if ($id_dealer == NULL) {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT id_prospek FROM tr_prospek
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_at,7)='$thn_bln'
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      if ($is_mobile == true) {
        $sumber_prospek = $this->db->query("SELECT id_dms FROM ms_sumber_prospek WHERE id='$sumber_prospek'")->row()->id_dms;
      }
      $row        = $get_data->row();
      $last_number = substr($row->id_prospek, -5);
      $new_kode   = main_dealer()->kode_md . '/' . $dealer->kode_dealer_md . '/' . $thbln . '/PSP/' . $sumber_prospek . '/' . sprintf("%'.05d", $last_number + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_prospek', ['id_prospek' => $new_kode])->num_rows();
        if ($cek > 0) {
          $gen_number = substr($new_kode, -5);
          $new_kode   = main_dealer()->kode_md . '/' . $dealer->kode_dealer_md . '/' . $thbln . '/PSP/' . $sumber_prospek . '/' . sprintf("%'.05d", $gen_number + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = main_dealer()->kode_md . '/' . $dealer->kode_dealer_md . '/' . $thbln . '/PSP/' . $sumber_prospek . '/' . '00001';
    }
    return strtoupper($new_kode);
  }

  function getSalesProgramGC()
  {
    return $this->db->query("SELECT * FROM tr_sales_program INNER JOIN ms_jenis_sales_program ON tr_sales_program.id_jenis_sales_program = ms_jenis_sales_program.id_jenis_sales_program
                            WHERE ms_jenis_sales_program.jenis_sales_program = 'Group Customer'");
  }
  function getProspekGC($filter = NULL)
  {
    $id_dealer = dealer()->id_dealer;
    $where = "WHERE pgc.active = '1' AND pgc.id_dealer = '$id_dealer' ";

    if (isset($filter['id_prospek_gc'])) {
      if ($filter['id_prospek_gc'] != '') {
        $where .= " AND pgc.id_prospek_gc='{$filter['id_prospek_gc']}'";
      }
    }
    if (isset($filter['id_customer_in_spk'])) {
      if ($filter['id_customer_in_spk'] == true) {
        $cek = "IN";
      } else {
        $cek = "NOT IN";
      }
      $where .= " AND id_customer $cek (SELECT id_customer FROM tr_spk 
        JOIN tr_sales_order ON tr_sales_order.no_spk=tr_spk.no_spk
        WHERE tr_sales_order.status_delivery IS NOT NULL AND id_customer=tr_prospek.id_customer
         )";
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (clm.id_dealer LIKE '%$search%'
                            OR clm.no_mesin LIKE '%$search%'
                            OR clm.no_rangka LIKE '%$search%'
                            OR clm.kpb_ke LIKE '%$search%'
                            ) 
            ";
      }
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        // if ($filter['order_column'] == 'view_claim_kpb') {
        //   $order_column = ['kode_dealer_md', 'nama_dealer', 'clm.no_mesin', 'clm.no_rangka', 'clm.no_kpb', 'tk.tipe_ahm', 'clm.kpb_ke', 'clm.tgl_beli_smh', 'clm.created_at', NULL];
        // }
        $order_column = [];
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order .= " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY clm.created_at DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT pgc.*,ms_karyawan_dealer.nama_lengkap,
    kel.kelurahan AS kel,
    kec.kecamatan AS kec,
    kab.kabupaten AS kab,
    prov.provinsi AS prov,
    kel_kantor.kelurahan AS kel_kantor,
    kec_kantor.kecamatan AS kec_kantor,
    kab_kantor.kabupaten AS kab_kantor,
    prov_kantor.provinsi AS prov_kantor,pgc.id_event,ms_event.nama_event
    FROM tr_prospek_gc pgc
      LEFT JOIN ms_dealer ON pgc.id_dealer=ms_dealer.id_dealer
      LEFT JOIN ms_event ON pgc.id_event=ms_event.id_event
			LEFT JOIN ms_karyawan_dealer ON pgc.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
			LEFT JOIN ms_kelurahan kel ON pgc.id_kelurahan=kel.id_kelurahan
			LEFT JOIN ms_kecamatan kec ON pgc.id_kecamatan=kec.id_kecamatan
			LEFT JOIN ms_kabupaten kab ON pgc.id_kabupaten=kab.id_kabupaten
			LEFT JOIN ms_provinsi prov ON pgc.id_provinsi=prov.id_provinsi
      LEFT JOIN ms_kelurahan kel_kantor ON pgc.id_kelurahan_kantor=kel_kantor.id_kelurahan
      LEFT JOIN ms_kecamatan kec_kantor ON kel_kantor.id_kecamatan=kec_kantor.id_kecamatan
      LEFT JOIN ms_kabupaten kab_kantor ON kec_kantor.id_kabupaten=kab_kantor.id_kabupaten
      LEFT JOIN ms_provinsi prov_kantor ON kab_kantor.id_provinsi=prov_kantor.id_provinsi
			$where
			ORDER BY pgc.created_at DESC");
  }

  function getProspekGCDetail($filter)
  {
    $where = "WHERE 1=1 ";
    if (isset($filter['id_prospek'])) {
      $where .= " AND pgk.id_prospek_gc='{$filter['id_prospek']}'";
    }
    $tipe = "CASE WHEN prp.jenis='instansi' THEN 'Instansi' ELSE 'Customer Umum' END";

    $biaya_bbn = "CASE 
                  WHEN prp.jenis='instansi' THEN
                    (SELECT biaya_instansi FROM ms_bbn_dealer WHERE id_tipe_kendaraan = pgk.id_tipe_kendaraan order by created_at DESC LIMIT 1)    
                  ELSE
                  (SELECT biaya_bbn FROM ms_bbn_dealer WHERE id_tipe_kendaraan = pgk.id_tipe_kendaraan order by created_at DESC LIMIT 1)
                  END 
    ";
    $harga_jual = "SELECT harga_jual FROM ms_kelompok_md 
    INNER JOIN ms_kelompok_harga ON ms_kelompok_md.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga 
    WHERE 
    ms_kelompok_md.id_item = itm.id_item 
    AND ms_kelompok_harga.target_market = $tipe
    AND (CASE 
          WHEN prp.jenis='instansi' THEN
            IF(ms_kelompok_harga.id_kelompok_harga=prp.id_kelompok_harga, 1,0)
          ELSE 1
        END
        )=1
    ORDER BY start_date DESC LIMIT 0,1
    ";
    $harga_on = "($harga_jual)+($biaya_bbn)";
    $harga = "FLOOR(($harga_jual)/1.1)";
    $ppn = "FLOOR(0.1 * $harga)";
    return $this->db->query("SELECT pgk.*,tk.tipe_ahm,wr.warna,itm.id_item,IFNULL($biaya_bbn,0) AS biaya_bbn,$tipe AS tipe,IFNULL(($harga_jual),0) AS harga_jual,($harga_on) AS harga_on,($harga_on) AS harga_tunai,($harga_on) AS  harga_asli,($harga) AS harga,$ppn AS ppn
    FROM tr_prospek_gc_kendaraan pgk
    JOIN tr_prospek_gc prp ON prp.id_prospek_gc=pgk.id_prospek_gc
    LEFT JOIN ms_tipe_kendaraan tk on pgk.id_tipe_kendaraan = tk.id_tipe_kendaraan
    LEFT JOIN ms_warna wr ON pgk.id_warna = wr.id_warna
    JOIN ms_item itm ON itm.id_tipe_kendaraan=tk.id_tipe_kendaraan AND itm.id_warna=wr.id_warna
    $where
    ");
  }

  public function cek_bbn($params, $all = NULL)
  {
    $id_tipe_kendaraan   = $params['id_tipe_kendaraan'];
    if (isset($params['id_warna'])) {
      $id_warna             = $params['id_warna'];
    }
    $tipe               = "Customer Umum";
    $cek_bbn = $this->db->query("SELECT biaya_bbn FROM ms_bbn_dealer WHERE id_tipe_kendaraan = '$id_tipe_kendaraan' and active = '1' order by created_at desc");
    if ($cek_bbn->num_rows() > 0) {
      $te = $cek_bbn->row();
      $biaya_bbn = $te->biaya_bbn;
    } else {
      $biaya_bbn = 0;
    }
    $where = '';
    if (isset($id_warna)) {
      $where = " AND it.id_warna = '$id_warna'";
    }

    $date = date('Y-m-d');
    $cek_harga = $this->db->query("SELECT harga_jual FROM ms_kelompok_md 
			INNER JOIN ms_kelompok_harga ON ms_kelompok_md.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga
      JOIN ms_item it ON it.id_item=ms_kelompok_md.id_item
			WHERE 
      it.id_tipe_kendaraan = '$id_tipe_kendaraan'
      $where
      AND start_date <='$date' 
      AND ms_kelompok_harga.target_market = '$tipe' 
      AND (bundling IS NULL OR bundling='') 
      ORDER BY start_date DESC LIMIT 0,1");
    if ($cek_harga->num_rows() > 0) {
      $ts = $cek_harga->row();
      $harga_jual = $ts->harga_jual;
    } else {
      $harga_jual = 0;
    }
    $harga     = floor($harga_jual / 1.1);
    $ppn       = floor(0.1 * $harga);
    $harga_on = $harga_jual + $biaya_bbn;
    $harga_tunai = $harga_on;
    if ($all == NULL) {
      return $harga_tunai;
    } else {
      return [
        'harga_tunai' => $harga_tunai,
        'ppn'         => $ppn,
        'dpp'         => $harga,
        'biaya_bbn'   => $biaya_bbn,
        'harga_jual'  => $harga_jual,
      ];
    }
  }


  function totalProspekSPK($filter)
  {
    $where_id = "WHERE 1=1 ";
    $where_gc = "WHERE 1=1 ";
    if (isset($filter['id_tipe_kendaraan'])) {
      $where_id .= "AND prp.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
      $where_gc .= "AND prp.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
    }
    if (isset($filter['tahun_bulan'])) {
      $where_id .= "AND LEFT(prp.created_at,7)='{$filter['tahun_bulan']}'";
      $where_gc .= "AND LEFT(p_gc.created_at,7)='{$filter['tahun_bulan']}'";
    }
    if (isset($filter['id_warna'])) {
      $where_id .= " AND prp.id_warna='{$filter['id_warna']}'";
      $where_gc .= " AND prp.id_warna='{$filter['id_warna']}'";
    }
    if (isset($filter['id_dealer'])) {
      $where_id .= " AND prp.id_dealer='{$filter['id_dealer']}' ";
      $where_gc .= " AND p_gc.id_dealer='{$filter['id_dealer']}' ";
    }
    if (isset($filter['no_so_not_null'])) {
      $where_id .= " AND so.id_sales_order IS NOT NULL ";
      $where_gc .= " AND so.id_sales_order_gc IS NOT NULL ";
    }
    if (isset($filter['no_so_null'])) {
      $where_id .= " AND so.id_sales_order IS NULL ";
      $where_gc .= " AND so.id_sales_order_gc IS NULL ";
    }
    if (isset($filter['status_no_deal'])) {
      $where_id .= " AND (prp.status_prospek='No Deal' OR prp.status_prospek='Not Deal') ";
    }

    $prospek_id = $this->db->query("SELECT COUNT(id_prospek) count 
                  FROM tr_prospek prp
                  LEFT JOIN tr_spk spk ON spk.id_customer=prp.id_customer
                  LEFT JOIN tr_sales_order so ON so.no_spk=spk.no_spk
                  $where_id")->row()->count;

    // $prospek_gc = $this->db->query("SELECT SUM(qty) count 
    //                       FROM tr_prospek_gc_kendaraan prp 
    //                       JOIN tr_prospek_gc p_gc ON p_gc.id_prospek_gc=prp.id_prospek_gc
    //                       LEFT JOIN tr_spk_gc spk ON spk.id_prospek_gc=prp.id_prospek_gc
    //                       LEFT JOIN tr_sales_order_gc so ON so.no_spk_gc=spk.no_spk_gc
    //                       $where_gc")->row()->count;
    $prospek_gc = 0;

    return $prospek_id + $prospek_gc;
  }

  public function getharga($save = null, $id_tipe_kendaraan = null, $id_warna = null)
  {

    if ($id_tipe_kendaraan == null) {
      $id_tipe_kendaraan   = $this->input->post("id_tipe_kendaraan");
      $id_warna             = $this->input->post("id_warna");
    }

    $tipe               = "Customer Umum";

    $cek_bbn = $this->db->query("SELECT * FROM ms_bbn_dealer WHERE id_tipe_kendaraan = '$id_tipe_kendaraan'");

    if ($cek_bbn->num_rows() > 0) {

      $te = $cek_bbn->row();

      $biaya_bbn = $te->biaya_bbn;
    } else {

      $biaya_bbn = 0;
    }



    $item = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan = '$id_tipe_kendaraan' AND id_warna = '$id_warna'");

    if ($item->num_rows() > 0) {

      $ty = $item->row();

      $id_item = $ty->id_item;
    } else {

      $id_item = "";
    }
    $date = date('Y-m-d');


    $cek_harga = $this->db->query("SELECT * FROM ms_kelompok_md 

			INNER JOIN ms_kelompok_harga ON ms_kelompok_md.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga 

			WHERE ms_kelompok_md.id_item = '$id_item' 
			AND ms_kelompok_harga.target_market = '$tipe' 
			AND start_date <='$date'
			ORDER BY start_date DESC LIMIT 0,1");

    if ($cek_harga->num_rows() > 0) {

      $ts = $cek_harga->row();

      $harga_jual = $ts->harga_jual;
    } else {

      $harga_jual = 0;
    }



    $harga     = floor($harga_jual / 1.1);

    $ppn       = floor(0.1 * $harga);

    $harga_on = $harga_jual + $biaya_bbn;

    $harga_tunai = $harga_on;


    $response = [
      'biaya_bbn' => $biaya_bbn,
      'harga_on' => $harga_on,
      'harga_jual' => $harga_jual,
      'ppn' => $ppn,
      'harga' => $harga,
      'harga_tunai' => $harga_tunai
    ];
    if ($save != null) {
      return $response;
    } else {
      echo json_encode($response);
    }
    // echo $biaya_bbn."|".$harga_on."|".$harga_jual."|".$ppn."|".$harga."|".$harga_tunai;

  }

  function getProspekSkemaKredit($filter)
  {
    $where = "WHERE 1=1";
    if (isset($filter['id_dealer'])) {
      $where .= " AND tr_prospek.id_dealer='{$filter['id_dealer']}'";
    }
    return $this->db->query("SELECT tr_prospek.*,tipe_ahm,warna,tenor,angsuran,dp,harga_off_road,harga_on_road,bbn AS biaya_bbn FROM tr_prospek 
			LEFT JOIN tr_skema_kredit sk ON sk.id_prospek=tr_prospek.id_prospek
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_prospek.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=tr_prospek.id_warna
			$where
			ORDER BY tr_prospek.created_at DESC");
  }

  function getSalesProgram($filter)
  {
    $now = get_ymd();
    $where = "WHERE 1=1 AND '$now' BETWEEN sp.periode_awal AND sp.periode_akhir";
    $id_dealer = $filter['id_dealer'];
    $select = "sp.*";

    // $where .= " AND '$now' BETWEEN periode_awal AND periode_akhir";

    $program_terpakai = "SELECT COUNT(no_spk) FROM tr_spk spk WHERE program_umum=sp.id_program_md AND id_dealer='$id_dealer'";
    $kuota_dealer = "SELECT kuota FROM tr_sales_program_dealer spd WHERE id_dealer='$id_dealer' AND spd.id_program_md=sp.id_program_md";

    // Cek Kuota Program
    $where .= " AND CASE WHEN sp.kuota_program=0 THEN 1
                    WHEN sp.kuota_program>0 THEN 
                      CASE WHEN ($program_terpakai) < ($kuota_dealer) THEN 1 ELSE 0 END
                    ELSE 1
                    END = 1
    ";
    if (isset($filter['type_unit_id'])) {
      if ($filter['type_unit_id'] != '') {
        $where_warna = '';
        if (isset($filter['id_warna'])) {
          $where_warna = "AND spt.id_warna LIKE '%{$filter['id_warna']}%'";
        }
        $where .= "AND (SELECT COUNT(spt.id_tipe_kendaraan) 
                  FROM tr_sales_program_tipe spt
                  JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=spt.id_tipe_kendaraan
                  WHERE id_program_md=sp.id_program_md 
                  AND id_tipe_kendaraan_int='{$filter['type_unit_id']}' $where_warna
                  )>0";
      }
    }

    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(sp.id_program_md) AS count";
      }
    }
    $limit = '';
    if (isset($filter['page'])) {
      $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
      $length = 10;
      // $start = $page == 1 ? 0 : $length * ($page - 1);
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }
    return $this->db->query("SELECT $select 
            FROM tr_sales_program sp $where $limit");
  }

  function getSalesProgramUnitGroup($filter)
  {
    $where = "WHERE 1=1 ";
    if (isset($filter['id_program_md'])) {
      $where .= " AND tp.id_program_md='{$filter['id_program_md']}'";
    }
    return $this->db->query("SELECT GROUP_CONCAT(id_tipe_kendaraan SEPARATOR ', ') tipe
      FROM (select id_tipe_kendaraan 
            from tr_sales_program_tipe tp
            $where
            group by id_tipe_kendaraan)
      AS tabel")->row()->tipe;
  }

  function getProspekDokumen($filter = NULL)
  {
    $id_dealer = $filter['id_dealer'];
    $where = "WHERE p.id_dealer = '$id_dealer' ";

    if (isset($filter['id_prospek'])) {
      if ($filter['id_prospek'] != '') {
        $where .= " AND pk.id_prospek='{$filter['id_prospek']}'";
      }
    }
    if (isset($filter['key'])) {
      if ($filter['key'] != '') {
        $where .= " AND pk.key='{$filter['key']}'";
      }
    }
    if (isset($filter['key_ms_null'])) {
      if ($filter['key_ms_null'] != '') {
        $where .= " AND dc_prp.key IS NULL";
      }
    }
    $select = 'pk.id,pk.path,pk.key,
    CASE WHEN pk.name IS NULL THEN dc_prp.name ELSE pk.name END AS name,
    CASE WHEN dc_prp.key IS NULL THEN 0 ELSE 1 END AS is_required
    ';
    return $this->db->query("SELECT 
    $select
    FROM tr_prospek_dokumen pk
    LEFT JOIN tr_prospek p ON p.id_prospek=pk.id_prospek
    LEFT JOIN sc_ms_document_prospek dc_prp ON dc_prp.key=pk.key
    $where
    ORDER BY pk.id DESC
    ");
  }
  function getProspekFollowUp($filter = NULL)
  {
    $id_dealer = $filter['id_dealer'];
    $where = "WHERE p.id_dealer = '$id_dealer' ";

    if (isset($filter['id'])) {
      if ($filter['id'] != '') {
        $where .= " AND pf.id='{$filter['id']}'";
      }
    }
    if (isset($filter['id_prospek'])) {
      if ($filter['id_prospek'] != '') {
        $where .= " AND pf.id_prospek='{$filter['id_prospek']}'";
      }
    }
    if (isset($filter['id_prospek_int'])) {
      if ($filter['id_prospek_int'] != '') {
        $where .= " AND p.id_prospek_int='{$filter['id_prospek_int']}'";
      }
    }
    if (isset($filter['id_customer'])) {
      if ($filter['id_customer'] != '') {
        $where .= " AND p.id_customer='{$filter['id_customer']}'";
      }
    }
    if (isset($filter['tgl_fol_up'])) {
      if ($filter['tgl_fol_up'] != '') {
        $where .= " AND pf.tgl_fol_up='{$filter['tgl_fol_up']}'";
      }
    }
    if (isset($filter['waktu_fol_up'])) {
      if ($filter['waktu_fol_up'] != '') {
        $where .= " AND pf.waktu_fol_up='{$filter['waktu_fol_up']}'";
      }
    }
    if (isset($filter['check_date_not_null'])) {
      if ($filter['check_date_not_null'] != '') {
        $where .= " AND pf.check_date IS NOT NULL";
      }
    }
    if (isset($filter['key'])) {
      if ($filter['key'] != '') {
        $where .= " AND pf.key='{$filter['key']}'";
      }
    }
    $select = 'pf.id,pf.id_prospek,pf.tgl_fol_up,pf.waktu_fol_up,metode_fol_up,
    CASE WHEN mfu.name IS NULL THEN metode_fol_up ELSE mfu.name END AS metode_fol_up_text,keterangan,check_date
    ';
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(pf.id) AS count";
      }
    }

    $order = "ORDER BY pf.id ASC";
    if (isset($filter['order'])) {
      $order = $filter['order'];
    }
    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    $result = $this->db->query("SELECT 
    $select
    FROM tr_prospek_fol_up pf
    LEFT JOIN sc_ms_metode_follow_up mfu ON mfu.id=pf.metode_fol_up
    LEFT JOIN tr_prospek p ON p.id_prospek=pf.id_prospek
    $where
    $order
    $limit
    ");
    if (isset($filter['return'])) {
      if ($filter['return'] == 'for_service_concept') {
        $no = $result->num_rows();
        foreach ($result->result() as $rs) {
          $new_result[] = [
            'id' => $rs->id,
            'name' => 'Follow Up Prospek ' . $no,
            'activity' => $rs->metode_fol_up_text,
            'check_date' => (string)$rs->check_date,
            'commited_date' => mediumdate_indo($rs->tgl_fol_up, ' ') ?: '',
            'description' => (string)$rs->keterangan,
          ];
          $no--;
        }
        if (isset($new_result)) {
          return $new_result;
        } else {
          return array();
        }
      }
    } else {
      return $result;
    }
  }
  function getProspekUnitDetail($filter = NULL)
  {
    $id_dealer = $filter['id_dealer'];
    $where = "WHERE prp.id_dealer = '$id_dealer' ";

    if (isset($filter['id_prospek_int'])) {
      if ($filter['id_prospek_int'] != '') {
        $where .= " AND prp.id_prospek_int='{$filter['id_prospek_int']}'";
      }
    }
    $select = '*';
    if (isset($filter['select'])) {
      if ($filter['select'] == 'unit') {
        $select = "prp.id_tipe_kendaraan,prp.id_warna,tk.tipe_ahm,wr.warna,price_unit,total_accessories,price_accessories,total_apparel,price_apparel,grand_total";
      }
      if ($filter['select'] == 'detail_unit') {
        $select = "prp.id_tipe_kendaraan,id_tipe_kendaraan_int,prp.id_warna,id_warna_int,tk.tipe_ahm,wr.warna,itm.id_item,itm.id_item_int";
      }
    }
    $result = $this->db->query("SELECT 
    $select
    FROM tr_prospek prp
    LEFT JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=prp.id_tipe_kendaraan
    LEFT JOIN ms_warna wr ON wr.id_warna=prp.id_warna
    LEFT JOIN ms_item itm ON itm.id_tipe_kendaraan=prp.id_tipe_kendaraan AND itm.id_warna=prp.id_warna
    $where
    ");
    return $result;
  }
  function getProspekAccessories($filter = NULL)
  {
    $id_dealer = $filter['id_dealer'];
    $where = "WHERE prp.id_dealer = '$id_dealer' ";

    if (isset($filter['id_prospek_int'])) {
      if ($filter['id_prospek_int'] != '') {
        $where .= " AND prp.id_prospek_int='{$filter['id_prospek_int']}'";
      }
    }
    $select = 'pa.*,prt.*';
    if (isset($filter['select'])) {
      if ($filter['select'] == 'sum_qty') {
        $select = "IFNULL(SUM(pa.accessories_qty),0) AS tot";
      }
    }
    $result = $this->db->query("SELECT $select
    FROM tr_prospek_accessories pa
    JOIN tr_prospek prp ON prp.id_prospek=pa.id_prospek
    JOIN ms_part prt ON prt.id_part=pa.accessories_id
    $where
    ");
    return $result;
  }
  function getProspekApparel($filter = NULL)
  {
    $id_dealer = $filter['id_dealer'];
    $where = "WHERE prp.id_dealer = '$id_dealer' ";

    if (isset($filter['id_prospek_int'])) {
      if ($filter['id_prospek_int'] != '') {
        $where .= " AND prp.id_prospek_int='{$filter['id_prospek_int']}'";
      }
    }
    $select = 'pa.*,prt.*';
    if (isset($filter['select'])) {
    }
    $result = $this->db->query("SELECT $select
    FROM tr_prospek_apparel pa
    JOIN tr_prospek prp ON prp.id_prospek=pa.id_prospek
    JOIN ms_part prt ON prt.id_part=pa.apparel_id
    $where
    ");
    return $result;
  }

  function getProspekActivity($id_karyawan_dealer, $user, $honda_id = NULL)
  {
    $this->load->model('m_dms');
    $f_actual_prospek = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer == NULL ? '' : $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_prospek' => get_ym(),
      'select' => 'count'
    ];
    $filter_target_prospek = [
      'honda_id_in' => $honda_id == NULL ? '' : $honda_id,
      'id_dealer' => $user->id_dealer,
      'tahun' => get_y(),
      'bulan' => get_m(),
      'select' => 'sum_prospek',
      'active' => 1,
    ];
    $filter_sudah_fu_prospek = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer == NULL ? '' : $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_prospek' => get_ym(),
      'sudah_fu' => true,
      'select' => 'count',
      'join' => 'join_prospek',
      // 'status_prospek_not' => 'Deal'
    ];
    $filter_belum_fu_prospek = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer == NULL ? '' : $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_prospek' => get_ym(),
      'status_prospek_tidak_sama' => 'Deal',
      'select' => 'count',
      'belum_fu' => true,
      'join' => 'join_prospek',
      'status_prospek_not' => 'Deal'
    ];

    $f_hot = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer == NULL ? '' : $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_prospek' => get_ym(),
      'status_prospek' => 'hot',
      'select' => 'count',
    ];
    $hot = $this->getProspek($f_hot)->row()->count;

    $f_medium = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer == NULL ? '' : $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_prospek' => get_ym(),
      'status_prospek' => 'medium',
      'select' => 'count',
    ];
    $medium = $this->getProspek($f_medium)->row()->count;

    $f_low = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer == NULL ? '' : $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_prospek' => get_ym(),
      'status_prospek' => 'low',
      'select' => 'count',
    ];
    $low = $this->getProspek($f_low)->row()->count;

    $prospek_target=$this->m_dms->getH1TargetManagement($filter_target_prospek)->row()->sum_prospek;
    return [
      'actual'   => (int)$this->getProspek($f_actual_prospek)->row()->count,
      'target'   => (int)$prospek_target==0?0:$prospek_target,
      'sudah_fu'   => (int)$this->getProspek($filter_sudah_fu_prospek)->row()->count,
      'belum_fu'   => (int)$this->getProspek($filter_belum_fu_prospek)->row()->count,
      'hot' => (int)$hot,
      'medium' => (int)$medium,
      'low' => (int)$low,
    ];
  }
  function getProspekDokumenWajib($filter = NULL)
  {
    $base_url = base_url('assets/panel/files/');
    $path = "SELECT path FROM tr_prospek_dokumen WHERE id_prospek='{$filter['id_prospek']}' AND tr_prospek_dokumen.key IS NOT NULL AND tr_prospek_dokumen.key=ms_dc.key";
    return $this->db->query("SELECT *, ($path) AS path
    FROM sc_ms_document_prospek ms_dc
    WHERE active=1
    ");
  }
  function setSalesProgramForSPK($sp, $id_tipe_kendaraan, $jenis_beli)
  {

    if (isset($sp[0]['sales_program_id'])) {
      $id_sp_umum = $sp[0]['sales_program_id'];
      $sp_umum = $this->db->get_where('tr_sales_program', ['id_sales_program' => $id_sp_umum]);
      if ($sp_umum->num_rows() > 0) {
        $sp_umum = $sp_umum->row();
        $result['id_sp_umum'] = $sp_umum->id_program_md;
        $result['voucher'] = $this->db->query("SELECT 
        CASE WHEN '$jenis_beli'='Cash' THEN (ahm_cash+md_cash+dealer_cash+other_cash) 
             ELSE (ahm_kredit+md_kredit+dealer_kredit+other_kredit) END price
        FROM tr_sales_program_tipe spt
        JOIN tr_sales_program sp ON sp.id_program_md=spt.id_program_md
        WHERE sp.id_sales_program='$id_sp_umum' AND id_tipe_kendaraan='$id_tipe_kendaraan'
        ")->row()->price;
      } else {
        $result['id_sp_umum'] = '';
        $result['voucher'] = 0;
      }
    }
    if (isset($sp[1]['sales_program_id'])) {
      $id_sp_gab = $sp[1]['sales_program_id'];
      $sp_gab = $this->db->get_where('tr_sales_program', ['id_sales_program' => $id_sp_gab]);
      if ($sp_gab->num_rows() > 0) {
        $sp_gab = $sp_gab->row();
        $result['id_sp_gab'] = $sp_gab->id_program_md;
        $result['voucher_gab'] = $this->db->query("SELECT 
        CASE WHEN '$jenis_beli'='Cash' THEN (ahm_cash+md_cash+dealer_cash+other_cash) 
             ELSE (ahm_kredit+md_kredit+dealer_kredit+other_kredit) END price
        FROM tr_sales_program_tipe spt
        JOIN tr_sales_program sp ON sp.id_program_md=spt.id_program_md
        WHERE sp.id_sales_program='$id_sp_gab' AND id_tipe_kendaraan='$id_tipe_kendaraan'
        ")->row()->price;
      } else {
        $result['id_sp_gab'] = '';
        $result['voucher_gab'] = 0;
      }
    }
    if (isset($result)) {
      return $result;
    }
  }

  function getStatusKomunikasiFollowUp($fl = NULL)
  {
    $where = "WHERE sf.aktif=1";
    $select = "sf.id_status_fu,mdks.id_media_kontak_fu,sf.deskripsi_status_fu,sf.id_kategori_status_komunikasi,media_kontak_fu,kategori_status_komunikasi";
    if ($fl != NULL) {
      if (isset($fl['id_media_kontak_fu'])) {
        $where .= " AND mdks.id_media_kontak_fu='{$fl['id_media_kontak_fu']}'";
      }
      if (isset($fl['id_status_fu'])) {
        $where .= " AND sf.id_status_fu='{$fl['id_status_fu']}'";
      }
      if (isset($fl['select'])) {
        if ($fl['select'] == 'dropdown') {
          $select = "sf.id_status_fu id, deskripsi_status_fu text,sf.id_kategori_status_komunikasi id_kategori, kategori_status_komunikasi kategori";
        }
      }
    }
    $data = $this->db_crm->query("SELECT $select
      FROM ms_status_fu sf
      JOIN ms_kategori_status_komunikasi ksk ON ksk.id_kategori_status_komunikasi=sf.id_kategori_status_komunikasi
    JOIN ms_media_kontak_vs_status_fu mdks ON mdks.id_status_fu=sf.id_status_fu
      JOIN ms_media_kontak_fu mf ON mf.id_media_kontak_fu=mdks.id_media_kontak_fu
      $where
    ")->result();
    return $data;
  }

  function getMediaKomunikasi($id_media_kontak_fu = NULL)
  {
    $where = '';
    if ($id_media_kontak_fu != NULL) {
      $where = "AND mkf.id_media_kontak_fu=$id_media_kontak_fu";
    }
    $data = $this->db_crm->query("SELECT id_media_kontak_fu,media_kontak_fu
    FROM ms_media_kontak_fu mkf
      WHERE mkf.aktif=1 $where
    ");
    if ($id_media_kontak_fu == NULL) {
      return $data->result();
    } else {
      return $data->row();
    }
  }

  function getHasilStatusFollowUp($kodeHasilStatusFollowUp = NULL)
  {
    $where = '';
    if ($kodeHasilStatusFollowUp != NULL) {
      $where = "AND mkf.kodeHasilStatusFollowUp=$kodeHasilStatusFollowUp";
    }
    $data = $this->db_crm->query("SELECT kodeHasilStatusFollowUp,deskripsiHasilStatusFollowUp
    FROM ms_hasil_status_follow_up mkf
      WHERE mkf.aktif=1 
      AND mkf.kodeHasilStatusFollowUp IN(1,3,4)
      $where
    ");
    if ($kodeHasilStatusFollowUp == NULL) {
      return $data->result();
    } else {
      return $data->row();
    }
  }

  function getAlasanNotProspectNotDeal($kodeAlasanNotProspectNotDeal = NULL)
  {
    $where = '';
    if ($kodeAlasanNotProspectNotDeal != NULL) {
      $where = "AND mkf.kodeAlasanNotProspectNotDeal=$kodeAlasanNotProspectNotDeal";
    }
    $data = $this->db_crm->query("SELECT kodeAlasanNotProspectNotDeal,alasanNotProspectNotDeal
    FROM ms_alasan_not_prospect_not_deal mkf
      WHERE mkf.aktif=1 $where
    ");
    if ($kodeAlasanNotProspectNotDeal == NULL) {
      return $data->result();
    } else {
      return $data->row();
    }
  }
  function getProspekFollowUpCrm($id_prospek)
  {
    $data = $this->db->query("SELECT pr.* FROm tr_prospek_fol_up pr WHERE id_prospek='$id_prospek'")->result();
    $result = [];
    foreach ($data as $dt) {
      $hasil = $this->getHasilStatusFollowUp($dt->kodeHasilStatusFollowUp);
      $alasan = $this->getAlasanNotProspectNotDeal($dt->kodeAlasanNotProspectNotDeal);
      if ((string)$dt->kodeAlasanNotProspectNotDeal == '') {
        $alasan = NULL;
      }
      $fs = ['id_status_fu' => $dt->id_status_fu];
      $status_fu = $this->getStatusKomunikasiFollowUp($fs);
      $dt->deskripsiHasilStatusFollowUp = $hasil == NULL ? '' : '';
      $dt->alasanNotProspectNotDeal = $alasan == NULL ? '' : $alasan->alasanNotProspectNotDeal;
      $metode = $this->getMediaKomunikasi($dt->metode_fol_up);
      $dt->desc_metode_fol_up = $metode == NULL ? '' : $metode->media_kontak_fu;
      foreach ($status_fu as $fu) {
        $dt->status_fu = $fu->deskripsi_status_fu;
        $dt->kategori_status_komunikasi = $fu->kategori_status_komunikasi;
      }
      $result[] = $dt;
    }
    return $result;
  }


  function getProspekInteraksi($id_prospek, $limit = '')
  {
    $data = $this->db->query("SELECT itr.*,
    CASE WHEN itr.customerType='V' THEN 'Invited' WHEN itr.customerType='R' THEN 'Non Invited' ELSE '' END customerTypeDesc,CONCAT(kodeTypeUnit,'-',tipe_ahm)concat_desc_tipe_warna,sumber.description descSourceLeads,prov.provinsi,kab.kabupaten,kec.kecamatan,kel.kelurahan
    FROm tr_prospek_interaksi itr 
    LEFT JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=itr.kodeTypeUnit
    LEFT JOIN ms_sumber_prospek sumber ON sumber.id_cdb=itr.sourceData
    LEFT JOIN ms_provinsi prov ON prov.id_provinsi=itr.idProvinsi
    LEFT JOIN ms_kabupaten kab ON kab.id_kabupaten=itr.idKabupaten
    LEFT JOIN ms_kecamatan kec ON kec.id_kecamatan=itr.idKecamatan
    LEFT JOIN ms_kelurahan kel ON kel.id_kelurahan=itr.idKelurahan
    WHERE id_prospek='$id_prospek' $limit")->result();
    $datas = [];
    foreach ($data as $dt) {
      $cms_source = $this->db_crm->query("SELECT deskripsi_cms_source FROM ms_maintain_cms_source WHERE kode_cms_source='$dt->cmsSource'")->row();
      $dt->deskripsiCmsSource = $cms_source != null ? $cms_source->deskripsi_cms_source : '';

      $platform = $this->db_crm->query("SELECT platform_data FROM ms_platform_data WHERE id_platform_data='$dt->platformData'")->row();
      $dt->descPlatformData = $platform != null ? $platform->platform_data : '';
      $datas[] = $dt;
    }
    return $datas;
  }

  function getPembelianSebelumnyaByNoRangka($no_rangka)
  {
    return $this->db->query("SELECT kry.id_karyawan_dealer,kry.nama_lengkap nama_sales_sebelumnya,kry.id_flp_md, fin.finance_company 
           FROM tr_sales_order so
           JOIN tr_spk spk ON spk.no_spk=so.no_spk
           LEFT JOIN ms_finance_company fin ON fin.id_finance_company=spk.id_finance_company
           JOIN tr_prospek prp ON prp.id_customer=spk.id_customer
           JOIN ms_karyawan_dealer kry ON kry.id_karyawan_dealer=prp.id_karyawan_dealer
           WHERE so.no_rangka='$no_rangka'
    ");
  }

  function getSumberProspek($filter)
  {
    $where = "WHERE sc.active='1'";
    if (isset($filter['id_platform_data'])) {
      $where .= " AND pd.id_platform_data='{$filter['id_platform_data']}'";
    }
    return $this->db->query("SELECT sc.id_dms,sc.description 
            FROM ms_source_leads_vs_platform_data vs
            JOIN ms_sumber_prospek sc ON sc.id_cdb=vs.id_source_leads
            JOIN ms_platform_data pd ON pd.id_platform_data=vs.id_platform_data
            $where
            ");
  }

  function getJmlProspekDokumenKtpKkBelumDiisi($id_prospek)
  {
    $keys=['ktp','kk'];
    foreach ($keys as $val) {
      $where = "WHERE tr_prospek_dokumen.id_prospek='$id_prospek' AND tr_prospek_dokumen.key='$val'";
      $dt = $this->db->query("SELECT tr_prospek_dokumen.key,tr_prospek_dokumen.path FROM tr_prospek_dokumen $where")->row();
      if ($dt!=null) {
        if ($dt->path=='') {
          $belum[]=strtoupper($val);
        }
      }else{
        $belum[]=strtoupper($val);
      }
    }
    
    if (isset($belum)) {
      $newbelum = implode(', ',$belum);
      return  $newbelum.' belum diupload';
    }
  }
}
