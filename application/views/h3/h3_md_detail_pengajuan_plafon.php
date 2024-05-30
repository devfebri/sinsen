<table class="table table-condensed table-bordered">
    <tr class='bg-blue-gradient'>
        <td colspan='7' class='text-center'>Detail Pengajuan Plafon</td>
    </tr>
    <tr>
        <td width='8%' class='text-center'>No.</td>
        <td class='text-center'>Diajukan Oleh</td>
        <td class='text-center'>Tgl Pengajuan</td>
        <td class='text-center'>Nilai Penambahan Plafon</td>
        <td class='text-center'>Nilai Penambahan Sementara</td>
        <td class='text-center'>Nilai Pengurang Plafon</td>
        <td class='text-center'>Keterangan</td>
    </tr>
    <tr>
        <td class='text-center'>1.</td>
        <td>Marketing</td>
        <td>
            <span v-if='plafon.approve_at == null'>-</span>
            <span v-if='plafon.approve_at != null'>{{ moment(plafon.approve_at).format('DD/MM/YYYY HH:mm:ss') }}</span>
        </td>
        <td class='text-right'>
            <vue-numeric read-only v-model='plafon.nilai_penambahan_plafon' separator='.' currency='Rp ' class='form-control'/>
        </td>
        <td class='text-right'>
            <vue-numeric read-only v-model='plafon.nilai_penambahan_sementara' separator='.' currency='Rp ' class='form-control'/>
        </td>
        <td class='text-right'>
            <vue-numeric read-only v-model='plafon.nilai_pengurang_plafon' separator='.' currency='Rp ' class='form-control'/>
        </td>
        <td>
            <span v-if='plafon.keterangan == null'>-</span>
            <span v-if='plafon.keterangan != null'>{{ plafon.keterangan }}</span>
        </td>
    </tr>
    <tr>
        <td class='text-center'>2.</td>
        <td>Finance</td>
        <td>
            <span v-if='plafon.approved_finance_at == null'>-</span>
            <span v-if='plafon.approved_finance_at != null'>{{ moment(plafon.approved_finance_at).format('DD/MM/YYYY HH:mm:ss') }}</span>
        </td>
        <td class='text-right'>
            <vue-numeric read-only v-model='plafon.nilai_penambahan_plafon_finance' separator='.' currency='Rp ' class='form-control'/>
        </td>
        <td class='text-right'>
            <vue-numeric read-only v-model='plafon.nilai_penambahan_sementara_finance' separator='.' currency='Rp ' class='form-control'/>
        </td>
        <td class='text-right'>
            <vue-numeric read-only v-model='plafon.nilai_pengurang_plafon_finance' separator='.' currency='Rp ' class='form-control'/>
        </td>
        <td>
            <span v-if='plafon.keterangan_finance == null'>-</span>
            <span v-if='plafon.keterangan_finance != null'>{{ plafon.keterangan_finance }}</span>
        </td>
        </tr>
    <tr>
        <td class='text-center'>3.</td>
        <td>Pimpinan</td>
        <td>
            <span v-if='plafon.approved_pimpinan_at == null'>-</span>
            <span v-if='plafon.approved_pimpinan_at != null'>{{ moment(plafon.approved_pimpinan_at).format('DD/MM/YYYY HH:mm:ss') }}</span>
        </td>
        <td class='text-right'>
            <vue-numeric read-only v-model='plafon.nilai_penambahan_plafon_pimpinan' separator='.' currency='Rp ' class='form-control'/>
        </td>
        <td class='text-right'>
            <vue-numeric read-only v-model='plafon.nilai_penambahan_sementara_pimpinan' separator='.' currency='Rp ' class='form-control'/>
        </td>
        <td class='text-right'>
            <vue-numeric read-only v-model='plafon.nilai_pengurang_plafon_pimpinan' separator='.' currency='Rp ' class='form-control'/>
        </td>
        <td>
            <span v-if='plafon.keterangan_pimpinan == null'>-</span>
            <span v-if='plafon.keterangan_pimpinan != null'>{{ plafon.keterangan_pimpinan }}</span>
        </td>
    </tr>
</table>