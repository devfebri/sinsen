<div v-if="purchase.penomoran_ulang == '1'">
    <div class="form-group">
        <label class='control-label col-sm-2 no-padding'>Claim C1/C2</label>
        <div class="col-sm-4">
            <input disabled type="radio" value="claim_c1_c2" v-model="purchase.tipe_penomoran_ulang">
        </div>
        <label class='control-label col-sm-2 no-padding'>Non-Claim</label>
        <div class="col-sm-4">
            <input disabled type="radio" value="non_claim" v-model="purchase.tipe_penomoran_ulang">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <div class="col-sm-6 no-padding">
                <span>Form Warranty Claim C1/C2</span>
                <input disabled type="text" class="input-compact" v-model="purchase.form_warranty_claim_c2_c2">
            </div>
        </div>
        <div class="col-sm-4 col-sm-offset-2">
            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_bpkb_faktur_ahm_non_claim"> Copy BPKB/Faktur AHM
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_faktur_ahm_claim_c1_c2"> Copy Faktur AHM
        </div>
        <div class="col-sm-4 col-sm-offset-2">
            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_stnk_non_claim"> Copy STNK
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.gesekan_nomor_framebody_claim_c1_c2"> Gesekan Nomor Framebody (Rangka)
        </div>
        <div class="col-sm-4 col-sm-offset-2">
            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_ktp_non_claim"> Copy KTP
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.gesekan_nomor_crankcase_claim_c1_c2"> Gesekan Nomor Crankcase (Mesin)
        </div>
        <div class="col-sm-4 col-sm-offset-2">
            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.gesekan_nomor_framebody_non_claim"> Gesekan Nomor Framebody (Rangka)
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <span class="text-bold visible-lg-block">Khusus Untuk Claim C2</span>
            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_ktp_claim_c1_c2"> Copy KTP
        </div>
        <div class="col-sm-4 col-sm-offset-2">
            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.gesekan_nomor_crankcase_non_claim"> Gesekan Nomor Crankcase (Mesin)
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_stnk_claim_c1_c2"> Copy STNK
        </div>
        <div class="col-sm-4 col-sm-offset-2">
            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.potongan_no_rangka_mesin_non_claim"> Potongan No Rangka/Mesin (Jangan Dipotong)*
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-8">
            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.surat_permohonan_penomoran_ulang_dari_kepolisian_non_claim"> Surat Permohonan Penomoran Ulang Dari Kepolisian (Asli)
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-8">
            <span class="text-bold visible-lg-block">Khusus untuk kasus nomor pada rangka/mesin tidak terbaca</span>
            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.surat_laporan_forensik_kepolisian_non_claim"> Surat laporan forensik kepolisian (Asli)
        </div>
    </div>
</div>