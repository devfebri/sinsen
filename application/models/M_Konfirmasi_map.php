<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_konfirmasi_map extends CI_Model {

    function getKonfrirmasiMap($filter = null,$export= null)
    {
  
      $where = 'WHERE 1=1';
  
      return $this->db->query("SELECT $select
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
  
}
