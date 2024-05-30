<div class="row">
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-6">
                <input :disabled='mode == "detail"' type="radio" value='Per Kelompok Part' v-model='sales_campaign.jenis_item_gimmick'> Per Kelompok Part
            </div>
            <div class="col-sm-6">
                <input :disabled='mode == "detail"' type="radio" value='Per Item Number' v-model='sales_campaign.jenis_item_gimmick'> Per Item Number
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Reward</label>
                    <div v-bind:class="{ 'has-error': error_exist('reward_gimmick') }" class="col-sm-8">
                      <select :disabled='mode == "detail"' class="form-control" v-model='sales_campaign.reward_gimmick'>
                        <option value="">-Pilih-</option>
                        <option value="Langsung">Langsung</option>
                        <option value="Tidak Langsung">Tidak Langsung</option>
                      </select>
                      <small v-if="error_exist('reward_gimmick')" class="form-text text-danger">{{ get_error('reward_gimmick') }}</small>
                    </div>
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Produk Program</label>
                    <div v-bind:class="{ 'has-error': error_exist('produk_program_gimmick') }" class="col-sm-8">
                      <select :disabled='mode == "detail"' class="form-control" v-model='sales_campaign.produk_program_gimmick'>
                        <option value="">-Pilih-</option>
                        <option value="Global">Global</option>
                        <option value="Per Item">Per Item</option>
                      </select>
                      <small v-if="error_exist('produk_program_gimmick')" class="form-text text-danger">{{ get_error('produk_program_gimmick') }}</small>
                    </div>
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Periode Program</label>
                    <div v-bind:class="{ 'has-error': error_exist('start_date_gimmick') }" class="col-sm-8">
                      <range-date-picker :disabled='mode == "detail"' :config='get_config_periode_campaign_gimmick()' class='form-control' @apply-date='applyDatePeriodeCampaignGimmick' @cancel-date='cancelDatePeriodeCampaignGimmick' readonly></range-date-picker>
                      <small v-if="error_exist('start_date_gimmick')" class="form-text text-danger">{{ get_error('start_date_gimmick') }}</small>  
                    </div>
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Satuan Rekapan</label>
                    <div v-bind:class="{ 'has-error': error_exist('satuan_rekapan_gimmick') }" class="col-sm-8">
                      <select :disabled='mode == "detail"' class="form-control" v-model='sales_campaign.satuan_rekapan_gimmick'>
                        <option value="">-Pilih-</option>
                        <option value="Satuan">Satuan</option>
                        <option value="Dus">Dus</option>
                      </select>
                      <small v-if="error_exist('satuan_rekapan_gimmick')" class="form-text text-danger">{{ get_error('satuan_rekapan_gimmick') }}</small>
                    </div>
                </div>    
            </div>
        </div>
        <div v-if='sales_campaign.produk_program_gimmick == "Global"' class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label no-padding">Kelipatan</label>
                    <div v-bind:class="{ 'has-error': error_exist('kelipatan_gimmick') }" class="col-sm-8">
                      <input type="checkbox" :disabled='mode == "detail"' v-model='sales_campaign.kelipatan_gimmick' true-value='1' false-value='0'>
                      <small v-if="error_exist('kelipatan_gimmick')" class="form-text text-danger">{{ get_error('kelipatan_gimmick') }}</small>
                    </div>
                </div>    
            </div>
        </div>
    </div>
</div>
<div class="container-fluid bg-primary" style='padding: 7px 0px;'>
    <div class="row">
        <div class="col-sm-12 text-center">
            <span class="text-bold">Detail Gimmick</span>
        </div>
    </div>
</div>
<table class="table table-compact">
    <tr>
        <td width='3%'>No.</td>
        <td v-if='sales_campaign.jenis_item_gimmick == "Per Item Number"'>Nama Part</td>
        <td v-if='sales_campaign.jenis_item_gimmick == "Per Item Number"'>Kode Part</td>
        <td v-if='sales_campaign.jenis_item_gimmick == "Per Item Number"'>Kelompok Part</td>
        <td v-if='sales_campaign.jenis_item_gimmick == "Per Item Number"'>HET</td>
        <td v-if='sales_campaign.jenis_item_gimmick == "Per Item Number"'>Status</td>
        <td v-if='sales_campaign.jenis_item_gimmick == "Per Kelompok Part"'>Kelompok Part</td>
        <td v-if='sales_campaign.produk_program_gimmick == "Per Item"' class='text-center'>Kelipatan</td>
        <td v-if='sales_campaign.produk_program_gimmick == "Per Item"' width='3%'></td>
        <td v-if='mode != "detail"' width='3%'></td>
    </tr>
    <tr v-if='sales_campaign_detail_gimmick.length > 0' v-for='(each, index) of sales_campaign_detail_gimmick'>
        <td>{{ index + 1 }}.</td>
        <td v-if='sales_campaign.jenis_item_gimmick == "Per Item Number"'>{{ each.id_part }}</td>
        <td v-if='sales_campaign.jenis_item_gimmick == "Per Item Number"'>{{ each.nama_part }}</td>
        <td v-if='sales_campaign.jenis_item_gimmick == "Per Item Number"'>{{ each.kelompok_part }}</td>
        <td v-if='sales_campaign.jenis_item_gimmick == "Per Item Number"'>{{ each.het }}</td>
        <td v-if='sales_campaign.jenis_item_gimmick == "Per Item Number"'>{{ each.status }}</td>
        <td v-if='sales_campaign.jenis_item_gimmick == "Per Kelompok Part"'>{{ each.id_kelompok_part }}</td>
        <td v-if='sales_campaign.produk_program_gimmick == "Per Item"' class='text-center'>
            <input type="checkbox" :disabled='mode == "detail"' v-model='each.kelipatan_gimmick' true-value='1' false-value='0'>
        </td>
        <td v-if='sales_campaign.produk_program_gimmick == "Per Item"'>
            <button class="btn btn-flat btn-info" @click.prevent='open_detail_gimmick_item(index)'><i class="fa fa-eye"></i></button>
        </td>
        <td v-if='mode != "detail"'>
            <button class="btn btn-flat btn-danger" @click.prevent='hapus_detail_gimmick(index)'><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>
    <tr v-if='sales_campaign_detail_gimmick.length < 1'>
        <td colspan='6' class='text-center'>Tidak ada data.</td>
    </tr>
</table>
<div class="row" style='margin-bottom: 20px;'>
    <div class="col-sm-12 text-right">
        <button v-if='mode != "detail" && sales_campaign.jenis_item_gimmick == "Per Item Number"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_parts_sales_campaign_detail_gimmick'><i class="fa fa-plus"></i></button>
        <button v-if='mode != "detail" && sales_campaign.jenis_item_gimmick == "Per Kelompok Part"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_kelompok_part_sales_campaign_detail_gimmick'><i class="fa fa-plus"></i></button>
    </div>
</div>
<?php $this->load->view('modal/h3_md_parts_sales_campaign_detail_gimmick'); ?>
<script>
    function pilih_parts_sales_campaign_detail_gimmick(data){
        data.detail_gimmick_item = [];
        form_.sales_campaign_detail_gimmick.push(data);
        h3_md_parts_sales_campaign_detail_gimmick_datatable.draw();
    }
</script>
<?php $this->load->view('modal/h3_md_kelompok_part_sales_campaign_detail_gimmick'); ?>
<script>
    function pilih_kelompok_part_sales_campaign_detail_gimmick(data){
        data.detail_gimmick_item = [];
        form_.sales_campaign_detail_gimmick.push(data);
        h3_md_kelompok_part_sales_campaign_detail_gimmick_datatable.draw();
    }
</script>
<?php $this->load->view('modal/h3_md_kelompok_part_filter_part_sales_campaign_detail_gimmick'); ?>
<script>
    function pilih_kelompok_part_filter_part_sales_campaign_detail_gimmick(data, type) {
        if (type == "add_filter") {
            $("#id_kelompok_part_filter_parts_sales_campaign_detail_gimmick").val(data.id_kelompok_part);
        } else if (type == "reset_filter") {
            $("#id_kelompok_part_filter_parts_sales_campaign_detail_gimmick").val("");
        }
        h3_md_parts_sales_campaign_detail_gimmick_datatable.draw();
        h3_md_kelompok_part_filter_part_sales_campaign_detail_gimmick_datatable.draw();
    }
</script>