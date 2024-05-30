      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">  
          <div class="user-panel">
            <div class="pull-left image">
              <?php               
              $id = $this->session->userdata('id_user'); 
              $r  = $this->db->query("SELECT * FROM ms_user WHERE id_user = '$id'")->row();                                 
              echo "<img src='assets/panel/images/user/$r->avatar' width='18px' class='img-circle' alt='User Image'>";
              ?>
            </div>
            <div class="pull-left info">
              <p>
                <?php echo $this->session->userdata('nama');  ?>
              </p>
              <a href="panel/hoe"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>        
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li><a href="panel/home"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
            
            <li class="header">REFERENCES</li>  
            <li class="
            <?php 
            if($isi=='provinsi' or $isi=='kabupaten' or $isi=='kecamatan' or $isi=='kelurahan' or $isi=='kelompok_harga' or
                $isi=='agama' or $isi=='jabatan' or $isi=='divisi' or $isi=='karyawan' or $isi=='pekerjaan' or $isi=='hari_libur' or $isi=='kategori_sms' or
                $isi=='user' or $isi=='user_role' or $isi=='user_group' or $isi=='user_level' or $isi=='user_type' or $isi=='user_access_level' or
                $isi=='vendor_type' or $isi=='vendor_group' or $isi=='vendor' or $isi=='hobi' or $isi=='pendidikan' or $isi=='debt_collector' or
                $isi=='area_penjualan' or $isi=='wilayah_penagihan' or $isi=='wilayah_penagihan_detail' or $isi=='kompetitor'  or $isi=='pos_dealer' or $isi=='dealer' or
                $isi=='sp3d' or $isi=='pengangkatan_dealer' or $isi=='bbn_md' or $isi=='bbn_samsat' or $isi=='pengeluaran_bulan' or
                $isi=='jenis_customer' or $isi=='jenis_sebelumnya' or $isi=='merk_sebelumnya' or $isi=='digunakan' or $isi=='customer' or
                $isi=='kategori' or $isi=='segment' or $isi=='series' or $isi=='classes' or $isi=='modell' or $isi=='jenis' or $isi=='warna' or
                $isi=='tipe' or $isi=='kelompok_part' or $isi=='kelompok_symptom' or $isi=='symptom' or $isi=='kelompok_kerusakan' or 
                $isi=='kerusakan' or $isi=='ongkos_kerja' or $isi=='ahass_tool' or $isi=='gejala' or $isi=='penyebab' or $isi=='pengatasan' or
                $isi=='kelompok_part_symptom' or $isi=='kelompok_symptom' or $isi=='symptom' or $isi=='kelompok_kerusakan' or $isi=='kerusakan' or
                $isi=='ongkos_kerja' or $isi=='ahass_tool' or $isi=='gejala' or $isi=='merk_part' or $isi=='satuan' or $isi=='kelompok_part' or
                $isi=='kelompok_report' or $isi=='department' or $isi=='sub_department' or $isi=='group_dealer' or $isi=='norek_dealer' or
                $isi=='karyawan_dealer' or $isi=='alasan_cancel' or $isi=='alamat_korespondensi' or $isi=='status_hp' or $isi=='master_lead' or 
                $isi=='target_sales' or $isi=='program_promosi' or $isi=='jenis_promosi' or $isi=='item'){
              echo "active";
            } 
            ?>
            ">
              <a href="#">
                <i class="fa fa-database"></i> <span>Master Data</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">

                <li class="                
                  <?php 
                  if($isi=='kecamatan' or $isi=='kelurahan' or $isi=='kabupaten' or $isi=='kelurahan' or $isi=='provinsi' or
                    $isi=='wilayah_penagihan'){
                    echo "active";
                  } 
                  ?>
                  treeview">
                  <a href="#">
                     <span>Demography</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li class="
                    <?php 
                    if($isi=='provinsi'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/provinsi"> Provinsi</a></li>
                    <li class="
                    <?php 
                    if($isi=='kabupaten'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/kabupaten"> Kabupaten/Kota</a></li>
                    <li class="
                    <?php 
                    if($isi=='kecamatan'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/kecamatan"> Kecamatan</a></li>
                    <li class="
                    <?php 
                    if($isi=='kelurahan'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/kelurahan"> Kelurahan</a></li>                    
                    <li class="
                    <?php 
                    if($isi=='wilayah_penagihan'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/wilayah_penagihan"> Wilayah Penagihan (H3)</a></li>                    
                  </ul>                 
                </li>

                <li class="                
                  <?php 
                  if($isi=='jabatan' or $isi=='divisi' or $isi=='karyawan' or $isi=='debt_collector' or 
                    $isi=='department' or $isi=='sub_department'){
                    echo "active";
                  } 
                  ?>
                  treeview">
                  <a href="#">
                     <span>Karyawan</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li class="
                    <?php 
                    if($isi=='divisi'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/divisi"> Divisi</a></li>
                    <li class="
                    <?php 
                    if($isi=='department'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/department"> Department</a></li>
                    <li class="
                    <?php 
                    if($isi=='sub_department'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/sub_department"> Sub Department</a></li>
                    <li class="
                    <?php 
                    if($isi=='jabatan'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/jabatan"> Jabatan</a></li>
                    <li class="
                    <?php 
                    if($isi=='karyawan'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/karyawan"> Karyawan MD</a></li>                    
                    <li class="
                    <?php 
                    if($isi=='debt_collector'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/debt_collector"> Debt Collector</a></li>                    
                  </ul>                 
                </li>

                <li class="                
                  <?php 
                  if($isi=='dealer' or $isi=='group_dealer' or $isi=='karyawan_dealer' or $isi=='pos_dealer' or 
                   $isi=='pengangkatan_dealer' or $isi=='norek_dealer' or $isi=='alasan_cancel' or $isi=='master_lead' or $isi=='target_sales'){
                    echo "active";
                  } 
                  ?>
                  treeview">
                  <a href="#">
                     <span>Dealer</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">                                                          
                    <li class="
                    <?php 
                    if($isi=='dealer'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/dealer"> Dealer</a></li>
                    <li class="
                    <?php 
                    if($isi=='group_dealer'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/group_dealer"> Group Dealer</a></li>
                    <li class="
                    <?php 
                    if($isi=='norek_dealer'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/norek_dealer"> No Rek Dealer</a></li>
                    <li class="
                    <?php 
                    if($isi=='karyawan_dealer'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/karyawan_dealer"> Karyawan Dealer</a></li>
                    <li class="
                    <?php 
                    if($isi=='pos_dealer'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/pos_dealer"> POS Dealer</a></li>
                    <li class="
                    <?php 
                    if($isi=='pengangkatan_dealer'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/pengangkatan_dealer"> Pengangkatan Dealer</a></li>
                    <li class="
                    <?php 
                    if($isi=='target_sales'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/target_sales"> Target Sales Dealer</a></li>
                    <li class="
                    <?php 
                    if($isi=='master_lead'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/master_lead"> Master Lead Time</a></li>
                    <li class="
                    <?php 
                    if($isi=='alasan_cancel'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/alasan_cancel"> Alasan Cancel</a></li>                    
                  </ul>                 
                </li>

                <li class="
                  <?php 
                  if($isi=='kompetitor'){
                    echo "active";
                  } 
                  ?>
                  ">
                  <a href="master/kompetitor"><span>Kompetitor</span></a>                  
                </li>

                <li class="                
                  <?php 
                  if($isi=='vendor_type' or $isi=='vendor'){
                    echo "active";
                  } 
                  ?>
                  treeview">
                  <a href="#">
                     <span>Vendor</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                   
                    <li class="
                    <?php 
                    if($isi=='vendor_type'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/vendor_type"> Vendor Type</a></li>
                    
                    <li class="
                    <?php 
                    if($isi=='vendor'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/vendor"> Vendor</a></li>                                        
                  </ul>                 
                </li>
                
                <li class="                
                  <?php 
                  if($isi=='bbn_md' or $isi=='bbn_samsat'){
                    echo "active";
                  } 
                  ?>
                  treeview">
                  <a href="#">
                     <span>BBN</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                   
                    <li class="
                    <?php 
                    if($isi=='bbn_md'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/bbn_md"> BBN Dealer ke MD</a></li>
                    <li class="
                    <?php 
                    if($isi=='bbn_samsat'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/bbn_samsat"> BBN MD ke Biro Jasa</a></li>                                                                              
                  </ul>                 
                </li>

                <li class="
                  <?php 
                  if($isi=='hari_libur'){
                    echo "active";
                  } 
                  ?>
                  ">
                  <a href="master/hari_libur"><span>Hari Libur</span></a>                  
                </li>

                <li class="                
                  <?php 
                  if($isi=='program_promosi' or $isi=='jenis_promosi'){
                    echo "active";
                  } 
                  ?>
                  treeview">
                  <a href="#">
                     <span>Promosi</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                   
                    <li class="
                    <?php 
                    if($isi=='program_promosi'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/program_promosi"> Program Promosi</a></li>
                    <li class="
                    <?php 
                    if($isi=='jenis_promosi'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/jenis_promosi"> Jenis Promosi</a></li>                                                                              
                  </ul>                 
                </li>                

                <li class="                
                  <?php 
                  if($isi=='agama' or $isi=='jenis_customer' or $isi=='status_hp' or $isi=='hobi' or $isi=='pekerjaan' or $isi=='pendidikan' or 
                    $isi=='pengeluaran_bulan' or $isi=='alamat_korespondensi' or $isi=='jenis_sebelumnya' or $isi=='merk_sebelumnya' or $isi=='digunakan' or $isi=='customer'){
                    echo "active";
                  } 
                  ?>
                  treeview">
                  <a href="#">
                     <span>Customer</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                   
                    <li class="
                    <?php 
                    if($isi=='agama'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/agama"> Agama</a></li>
                    <li class="
                    <?php 
                    if($isi=='pekerjaan'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/pekerjaan"> Pekerjaan</a></li>
                    <li class="
                    <?php 
                    if($isi=='pengeluaran_bulan'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/pengeluaran_bulan"> Pengeluaran 1 Bulan</a></li>
                    <li class="
                    <?php 
                    if($isi=='pendidikan'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/pendidikan"> Pendidikan</a></li>
                    <li class="
                    <?php 
                    if($isi=='jenis_sebelumnya'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/jenis_sebelumnya"> Jenis Kendaraan Sebelumnya</a></li>
                    <li class="
                    <?php 
                    if($isi=='merk_sebelumnya'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/merk_sebelumnya"> Merk Kendaraan Sebelumnya</a></li>
                    <li class="
                    <?php 
                    if($isi=='digunakan'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/digunakan"> Digunakan untuk</a></li>
                    <li class="
                    <?php 
                    if($isi=='hobi'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/hobi"> Hobi</a></li>
                    <li class="
                    <?php 
                    if($isi=='jenis_customer'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/jenis_customer"> Jenis Customer</a></li>
                    <li class="
                    <?php 
                    if($isi=='status_hp'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/status_hp"> Status HP</a></li>
                    <li class="
                    <?php 
                    if($isi=='alamat_korespondensi'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/alamat_korespondensi"> Alamat Korespondnsi</a></li>                                                                                              
                  </ul>                 
                </li>

                <li class="                
                  <?php 
                  if($isi=='kategori' or $isi=='segment' or $isi=='series' or $isi=='classes' or 
                    $isi=='modell' or $isi=='jenis' or $isi=='warna' or $isi=='tipe' or $isi=='item'){
                    echo "active";
                  } 
                  ?>
                  treeview">
                  <a href="#">
                     <span>Unit</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">                   
                    <li class="
                    <?php 
                    if($isi=='kategori'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/kategori"> Kategori</a></li>
                    <li class="
                    <?php 
                    if($isi=='segment'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/segment"> Segment</a></li>
                    <li class="
                    <?php 
                    if($isi=='series'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/series"> Series</a></li>
                    <!--li class="
                    <?php 
                    if($isi=='classes'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/classes"> Class</a></li>
                    <li class="
                    <?php 
                    if($isi=='modell'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/modell"> Model</a></li>
                    <li class="
                    <?php 
                    if($isi=='jenis'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/jenis"> Jenis</a></li-->
                    <li class="
                    <?php 
                    if($isi=='tipe'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/tipe"> Tipe Kendaraan</a></li>
                    <li class="
                    <?php 
                    if($isi=='warna'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/warna"> Warna</a></li>
                    <li class="
                    <?php 
                    if($isi=='item'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/item"> Item</a></li>                                                                                                
                  </ul>                 
                </li>

                <li class="                
                  <?php 
                  if($isi=='kelompok_part_symptom' or $isi=='kelompok_symptom' or $isi=='symptom' or $isi=='kelompok_kerusakan' or 
                    $isi=='kerusakan' or $isi=='ongkos_kerja' or $isi=='ahass_tool' or $isi=='gejala' or $isi=='penyebab' or 
                    $isi=='pengatasan'){
                    echo "active";
                  } 
                  ?>
                  treeview">
                  <a href="#">
                     <span>Service</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">                   
                    <li class="
                    <?php 
                    if($isi=='kelompok_part_symptom'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/kelompok_part_symptom"> Kelompok Part Symptom</a></li>
                    <li class="
                    <?php 
                    if($isi=='kelompok_symptom'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/kelompok_symptom"> Kelompok Symptom</a></li>
                    <li class="
                    <?php 
                    if($isi=='symptom'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/symptom"> Symptom</a></li>
                    <li class="
                    <?php 
                    if($isi=='kelompok_kerusakan'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/kelompok_kerusakan"> Kelompok Kerusakan</a></li>
                    <li class="
                    <?php 
                    if($isi=='kerusakan'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/kerusakan"> Kerusakan</a></li>
                    <li class="
                    <?php 
                    if($isi=='ongkos_kerja'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/ongkos_kerja"> Ongkos Kerja</a></li>
                    <li class="
                    <?php 
                    if($isi=='ahass_tool'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/ahass_tool"> AHASS Tool</a></li>
                    <li class="
                    <?php 
                    if($isi=='gejala'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/gejala"> Gejala</a></li>                                                                              
                    <li class="
                    <?php 
                    if($isi=='penyebab'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/penyebab"> Penyebab</a></li>                                                                              
                    <li class="
                    <?php 
                    if($isi=='pengatasan'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/pengatasan"> Pengatasan</a></li>                                                                              
                  </ul>                 
                </li>

                <li class="                
                  <?php 
                  if($isi=='merk_part' or $isi=='satuan' or $isi=='kelompok_part' or $isi=='kelompok_report'){
                    echo "active";
                  } 
                  ?>
                  treeview">
                  <a href="#">
                     <span>Merk</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                   
                    <li class="
                    <?php 
                    if($isi=='merk_part'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/merk_part"> Merk Part</a></li>
                    <li class="
                    <?php 
                    if($isi=='satuan'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/satuan"> Satuan</a></li>
                    <li class="
                    <?php 
                    if($isi=='kelompok_part'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/kelompok_part"> Kelompok Part</a></li>
                    <li class="
                    <?php 
                    if($isi=='kelompok_report'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/kelompok_report"> Kelompok Report</a></li>                                                                              
                  </ul>                 
                </li>

                <li class="                
                  <?php 
                  if($isi=='user_level' or $isi=='user_role' or $isi=='user_group' or $isi=='user_level' or $isi=='user_type' or
                            $isi=='user_access_level' or $isi=='user'){
                    echo "active";
                  } 
                  ?>
                  treeview">
                  <a href="#">
                     <span>User</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                   
                    <li class="
                    <?php 
                    if($isi=='user_group'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/user_group"> User Group</a></li>
                    <li class="
                    <?php 
                    if($isi=='user_level'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/user_level"> User Level</a></li>
                    <li class="
                    <?php 
                    if($isi=='user_type'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/user_type"> User Type</a></li>
                    <li class="
                    <?php 
                    if($isi=='user_access_level'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/user_access_level"> User Access Level</a></li>
                    <li class="
                    <?php 
                    if($isi=='user'){
                      echo "active";
                    } 
                    ?>
                    "><a href="master/user"> User</a></li>
                  </ul>                 
                </li> 

              </ul>
            </li>
            <li>
              <a href="#"><i class="fa fa-wrench"></i> <span>Setting</span></a>                  
            </li>

            <li class="header">MAIN NAVIGATION</li>
            <li class="treeview">
              <a href="#"><i class="fa fa-download"></i>
                 <span>H1</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                
               <li><a href="#"> Logistik</a></li>
                <li>
                  <a href="#">Sales
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="#">Niguri</a></li>
                    <li><a href="#">Purchase Order</a></li>
                    <li><a href="#">Business Control</a></li>
                    <li><a href="#">Faktur & NIK</a></li>
                    <li><a href="#">STNK & Plat</a></li>
                    <li><a href="#">BPKB</a></li>
                    
                  </ul>
                </li>
                <li>
                  <a href="#">Communication
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="#">Proposal</a></li>
                    <li><a href="#">Report</a></li>
                  </ul>
                </li>
              </ul>
               
            </li>

             <li class="treeview">
              <a href="#"><i class="fa fa-cogs"></i>
                 <span>H2</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
               
                <li>
                  <a href="#">Services
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="#">Kartu Perawatan Berkala</a></li>
                    <li><a href="#">Warranty Claim</a></li>
                    <li><a href="#">Purchase Order</a></li>
                    <li><a href="#">Inventory</a></li>
                    <li><a href="#">Sales Order</a></li>
                  </ul>
                </li>
              
              </ul>
               
            </li>

             <li class="treeview">
              <a href="#"><i class="fa fa-cubes"></i>
                 <span>H3</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                    <li><a href="#"> Part</a></li>
             <li><a href="#"> Oil</a></li>
             <li><a href="#"> Accesoris & Apparel</a></li>
             <li><a href="#"> Other</a></li>
                  </ul>
             
            </li>
             <li class="treeview">
              <a href="#"><i class="fa fa-suitcase"></i>
                 <span>HC3</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                    <li><a href="#"> Verifikasi Customer Database</a></li>
             <li><a href="#"> Customer Relationship Management</a></li>
             <li><a href="#"> Accesoris & Apparel</a></li>
             <li><a href="#"> Complain Handling</a></li>
             <li><a href="#"> Dealer Operation Standard</a></li>
             <li><a href="#"> People Development</a></li>
                  </ul>
             
            </li>

            <li class="treeview">
              <a href="#"><i class="fa fa-group"></i>
                 <span>HRD</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                 <li><a href="#"> Personalia</a></li>
             <li><a href="#"> GA</a></li>
             <li><a href="#"> IT</a></li>
                  </ul>
             
            </li>

            <li class="treeview">
              <a href="#"><i class="fa fa-calculator"></i>
                 <span>F&A</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                    <li><a href="#"> Finance</a></li>
             <li><a href="#"> Accounting & Tax</a></li>
                  </ul>
             
            </li>            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>