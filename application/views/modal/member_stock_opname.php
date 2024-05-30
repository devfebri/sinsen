<!-- Modal -->
<div id="member_stock_opname" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Member</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <tr class='bg-blue-gradient'>
                        <td width='3%'>No.</td>
                        <td>NIK</td>
                        <td>Nama</td>
                        <td>Dari</td>
                        <td>Sampai</td>
                        <td width='3%'></td>
                    </tr>
                    <tr v-if='members.length > 0' v-for='(member, index) of members'>
                        <td class='align-middle'>{{ index + 1 }}.</td>
                        <td class='align-middle'>{{ member.nik }}</td>
                        <td class='align-middle'>{{ member.nama_lengkap }}</td>
                        <td class='align-middle'>{{ member.dari }}</td>
                        <td class='align-middle'>{{ member.sampai }}</td>
                        <td class="align-middle">
                            <button @click.prevent='remove_member(index)' class="btn btn-flat btn-danger"><i class="fa fa-trash-o" type='button'></i></button>
                        </td>
                    </tr>
                    <tr v-if='members.length < 1'>
                        <td class='align-middle text-center' colspan='6'>Tidak ada data.</td>
                    </tr>
                    <tr>
                        <td class='align-middle'></td>
                        <td class='align-middle' v-bind:class='{
                            "has-error": member_errors.id_member != null
                        }'>
                            <input v-model='member.nik' type="text" class="form-control" readonly data-toggle='modal' data-target='#assign_member_stock_opname'>
                            <small v-show='member_errors.id_member != null' class="form-text text-danger">{{ member_errors.id_member }}</small>
                        </td>
                        <td class='align-middle' v-bind:class='{
                            "has-error": member_errors.id_member != null
                        }'>
                            <input v-model='member.nama_lengkap' type="text" class="form-control" readonly data-toggle='modal' data-target='#assign_member_stock_opname'>
                            <small v-show='member_errors.id_member != null' class="form-text text-danger">{{ member_errors.id_member }}</small>
                        </td>
                        <td class='align-middle' v-bind:class='{
                            "has-error": member_errors.dari != null
                        }'>
                            <input v-model='member.dari' type="text" class="form-control">
                            <small v-show='member_errors.dari != null' class="form-text text-danger">{{ member_errors.dari }}</small>
                        </td>
                        <td class='align-middle' v-bind:class='{
                            "has-error": member_errors.sampai != null
                        }'>
                            <input v-model='member.sampai' type="text" class="form-control">
                            <small v-show='member_errors.sampai != null' class="form-text text-danger">{{ member_errors.sampai }}</small>
                        </td>
                        <td class='align-middle'>
                            <button @click.prevent='assign_member' class="btn btn-flat btn-primary"><i class="fa fa-plus"></i></button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('modal/assign_member_stock_opname') ?>
<script>
    function pilih_assign_member_stock_opname(data) {
        form_.member = data;
    }
</script>