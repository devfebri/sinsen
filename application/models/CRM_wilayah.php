<?php
class CRM_wilayah extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function get_provinsi($wilayah)
  {

    $kab  = $wilayah['kabupaten'];
    $kec  = $wilayah['kecamatan'];
    $prov = $wilayah['provinsi'];
    $kel  = $wilayah['kelurahan'];
    $where = 'WHERE 1=1 ';
    
    if ($wilayah['kelurahan'] !== '') {
    $where .= "AND kel.kelurahan ='$kel' ";
    } 

    if ($wilayah['kecamatan'] !== '') {
    $where .= "AND kec.kecamatan ='$kec' ";
    }

    if ($wilayah['provinsi'] !== '') {
    $where .= "AND prov.provinsi ='$prov'";
    }

    if ($wilayah['kabupaten'] !== '') {
      $where .= "AND kab.kabupaten like '%$kab%' ";
    }
    
    $provinsi = $this->db->query("
    select 
    kel.id_kelurahan,
    kel.kelurahan,
    kec.id_kecamatan,
    kec.id_kecamatan,
    kab.id_kabupaten,
    kab.kabupaten,
    prov.id_provinsi,
    prov.provinsi 
      from ms_kelurahan kel 
      inner join ms_kecamatan kec on kec.id_kecamatan= kec.id_kecamatan and kec.id_kecamatan = kel.id_kecamatan 
      inner join ms_kabupaten kab on kab.id_kabupaten = kec.id_kabupaten 
      join ms_provinsi prov on prov.id_provinsi = kab.id_provinsi
    $where 
    ")->row();

    return  $provinsi ;
  }


  function get_set_one_($wilayah)
  {

    $kel  = $wilayah['kelurahan'];
    $kab  = $wilayah['kabupaten'];
    $kec  = $wilayah['kecamatan'];
    $prov = $wilayah['provinsi'];

    $where = 'WHERE 1=1 ';

      if ($wilayah['kelurahan'] !== '') {
      $where .= "AND kelurahan ='$kel' ";
      } 
  
      if ($wilayah['kecamatan'] !== '') {
      $where .= "AND kecamatan ='$kec' ";
      }
  
      if ($wilayah['provinsi'] !== '') {
      $where .= "AND provinsi ='$prov'";
      }
  
      if ($wilayah['kabupaten'] !== '') {
        $where .= "AND kabupaten like '%$kab%' ";
      }
      $dt_kel       = $this->db->query("SELECT * FROM ms_kelurahan WHERE kelurahan = '$kel'")->row();
      $dt_kec       = $this->db->query("SELECT * FROM ms_kecamatan WHERE kecamatan = '$kec'")->row();
      $dt_kab       = $this->db->query("SELECT * FROM ms_kabupaten WHERE kabupaten = '$kab'")->row();
      $dt_pro       = $this->db->query("SELECT * FROM ms_provinsi WHERE provinsi   = '$prov'")->row();
  }


  function get_wilayah_with_crm($wilayah)
  {
    $kab  = $wilayah['kabupaten'] = '';
    $kec  = $wilayah['kecamatan'] = 'Maro Sebo Ilir';
    $prov = $wilayah['provinsi']  = '';
    $kel  = $wilayah['kelurahan'] = 'Terusan';
    $where = 'WHERE 1=1 ';


      if ($wilayah['kelurahan'] !== '') {
      $where .= "AND kel.kelurahan ='$kel' ";
      } 
  
      if ($wilayah['kecamatan'] !== '') {
      $where .= "AND kec.kecamatan ='$kec' ";
      }
  
      if ($wilayah['provinsi'] !== '') {
      $where .= "AND prov.provinsi ='$prov'";
      }
  
      if ($wilayah['kabupaten'] !== '') {
        $where .= "AND kab.kabupaten like '%$kab%' ";
      }

      $query = $this->db->query("
      select 
      kel.id_kelurahan,
      kel.kelurahan,
      kec.id_kecamatan,
      kec.id_kecamatan,
      kab.id_kabupaten,
      kab.kabupaten,
      prov.id_provinsi,
      prov.provinsi 
        from ms_kelurahan kel 
        inner join ms_kecamatan kec on kec.id_kecamatan= kec.id_kecamatan and kec.id_kecamatan = kel.id_kecamatan 
        inner join ms_kabupaten kab on kab.id_kabupaten = kec.id_kabupaten 
        join ms_provinsi prov on prov.id_provinsi = kab.id_provinsi
      $where 
      ")->row();



  }




}





