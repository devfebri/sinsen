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
    $id_dealer = dealer()->id_dealer;
    $where = "WHERE tr_prospek.active = '1' AND tr_prospek.id_dealer = '$id_dealer' ";

    if (isset($filter['id_prospek'])) {
      if ($filter['id_prospek'] != '') {
        $where .= " AND tr_prospek.id_prospek='{$filter['id_prospek']}'";
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
    $event = "SELECT nama_event FROM ms_event WHERE id_event=tr_prospek.id_event LIMIT 1";
    return $this->db->query("SELECT tr_prospek.*,ms_karyawan_dealer.nama_lengkap,
    kel.kelurahan AS kel,
    kec.kecamatan AS kec,
    kab.kabupaten AS kab,
    prov.provinsi AS prov,
    kel_kantor.kelurahan AS kel_kantor,
    kec_kantor.kecamatan AS kec_kantor,
    kab_kantor.kabupaten AS kab_kantor,
    prov_kantor.provinsi AS prov_kantor,tr_prospek.id_event,($event) AS nama_event
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
      -- LEFT JOIN ms_event ON tr_prospek.id_event=ms_event.id_event
			$where
			ORDER BY tr_prospek.created_at DESC");
  }

  function getEvent()
  {
    return $this->db->query("SELECT * FROM ms_event ORDER BY created_at DESC");
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
          $result[] = ['id_program_md' => $rs->id_program_md, 'judul_kegiatan' => $rs->judul_kegiatan];
        }
      }
    } elseif (strtolower($jenis_beli) == 'kredit') {
      foreach ($cek_program->result() as $rs) {
        if ($rs->tot_kredit > 0) {
          $result[] = ['id_program_md' => $rs->id_program_md, 'judul_kegiatan' => $rs->judul_kegiatan];
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

  function getIDProspek($sumber_prospek)
  {
    $thn_bln     = date('Y-m');
    $thbln     = date('y/m');
    $id_dealer = $this->m_admin->cari_dealer();
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT id_prospek FROM tr_prospek
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_at,7)='$thn_bln'
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {

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
                    (SELECT biaya_instansi FROM ms_bbn_dealer WHERE id_tipe_kendaraan = pgk.id_tipe_kendaraan)    
                  ELSE
                  (SELECT biaya_bbn FROM ms_bbn_dealer WHERE id_tipe_kendaraan = pgk.id_tipe_kendaraan)
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

  public function cek_bbn($params)
  {
    $id_tipe_kendaraan   = $params['id_tipe_kendaraan'];
    $id_warna             = $params['id_warna'];
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
			WHERE ms_kelompok_md.id_item = '$id_item' AND start_date <='$date' AND ms_kelompok_harga.target_market = '$tipe' ORDER BY start_date DESC LIMIT 0,1");
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
    return $harga_tunai;
  }


  function totalProspekSPK($filter)
  {
    $where_id = "WHERE 1=1 ";
    $where_gc = "WHERE 1=1 ";
    if (isset($filter['id_tipe_kendaraan'])) {
      $where_id .= "AND prp.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
      $where_gc .= "AND prp.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
    }
    if (isset($filter['id_warna'])) {
      $where_id .= "AND prp.id_warna='{$filter['id_warna']}'";
      $where_gc .= "AND prp.id_warna='{$filter['id_warna']}'";
    }
    if (isset($filter['id_dealer'])) {
      $where_id .= "AND prp.id_dealer='{$filter['id_dealer']}' ";
      $where_gc .= "AND p_gc.id_dealer='{$filter['id_dealer']}' ";
    }
    $prospek_id = $this->db->query("SELECT COUNT(id_prospek) count FROM tr_prospek prp $where_id")->row()->count;
    $prospek_gc = $this->db->query("SELECT SUM(qty) count 
                          FROM tr_prospek_gc_kendaraan prp 
                          JOIN tr_prospek_gc p_gc ON p_gc.id_prospek_gc=prp.id_prospek_gc
                          $where_gc")->row()->count;
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
}
