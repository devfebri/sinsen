<div class="row">
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-6">
                <input :disabled='mode == "detail"' type="radio" v-model='sales_campaign.jenis_item_poin' value='Per Kelompok Part'> Per Kelompok Part
            </div>
            <div class="col-sm-6">
                <input :disabled='mode == "detail"' type="radio" v-model='sales_campaign.jenis_item_poin' value='Per Item Number'> Per Item Number
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Reward</label>
                    <div v-bind:class="{ 'has-error': error_exist('reward_poin') }" class="col-sm-8">
                      <select :disabled='mode == "detail"' class="form-control" v-model='sales_campaign.reward_poin'>
                        <option value="">-Pilih-</option>
                        <option value="Langsung">Langsung</option>
                        <option value="Tidak Langsung">Tidak Langsung</option>
                      </select>
                      <small v-if="error_exist('reward_poin')" class="form-text text-danger">{{ get_error('reward_poin') }}</small>
                    </div>
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Produk Program</label>
                    <div v-bind:class="{ 'has-error': error_exist('produk_program_poin') }" class="col-sm-8">
                      <select :disabled='mode == "detail"' class="form-control" v-model='sales_campaign.produk_program_poin'>
                        <option value="">-Pilih-</option>
                        <option value="Global">Global</option>
                        <option value="Per Item">Per Item</option>
                      </select>
                      <small v-if="error_exist('produk_program_poin')" class="form-text text-danger">{{ get_error('produk_program_poin') }}</small>
                    </div>
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Periode Program</label>
                    <div v-bind:class="{ 'has-error': error_exist('start_date_poin') }" class="col-sm-8">
                      <range-date-picker :disabled='mode == "detail"' :config='get_config_periode_campaign_poin()' class='form-control' @apply-date='applyDatePeriodeCampaignPoin' @cancel-date='cancelDatePeriodeCampaignPoin' readonly></range-date-picker>
                      <small v-if="error_exist('start_date_poin')" class="form-text text-danger">{{ get_error('start_date_poin') }}</small>  
                    </div>
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Satuan Rekapan</label>
                    <div v-bind:class="{ 'has-error': error_exist('satuan_rekapan_poin') }" class="col-sm-8">
                      <select :disabled='mode == "detail"' class="form-control" v-model='sales_campaign.satuan_rekapan_poin'>
                        <option value="">-Pilih-</option>
                        <option value="Pcs">Pcs</option>
                        <option value="Botol">Botol</option>
                        <option value="Dus ">Dus </option>
                      </select>
                      <small v-if="error_exist('satuan_rekapan_poin')" class="form-text text-danger">{{ get_error('satuan_rekapan_poin') }}</small>
                    </div>
                </div>    
            </div>
        </div>
    </div>
</div>
<div class="container-fluid bg-blue-gradient">
    <div class="row">
        <div class="col-sm-12 text-center" style='padding: 8px 0px;'>
            <span class='text-bold'>Detail Poin</span>
        </div>
    </div>
</div>
<table class="table table-compact">
    <tr>
        <td width='3%'>No.</td>
        <td v-if='sales_campaign.jenis_item_poin == "Per Item Number"'>Kode Part</td>
        <td v-if='sales_campaign.jenis_item_poin == "Per Item Number"'>Nama Part</td>
        <td v-if='sales_campaign.jenis_item_poin == "Per Item Number"'>Kelompok Part</td>
        <td v-if='sales_campaign.jenis_item_poin == "Per Item Number"'>HET</td>
        <td v-if='sales_campaign.jenis_item_poin == "Per Item Number"'>Status</td>
        <td v-if='sales_campaign.jenis_item_poin == "Per Kelompok Part"'>Kelompok Part</td>
        <td width='8%'>Poin</td>
        <td>Satuan</td>
        <td v-if='mode != "detail"' width='3%'></td>
    </tr>
    <tr v-if='sales_campaign_detail_poin.length > 0' v-for='(detail_poin, index) of sales_campaign_detail_poin'>
        <td>{{ index + 1 }}.</td>
        <td v-if='sales_campaign.jenis_item_poin == "Per Item Number"'>{{ detail_poin.id_part }}</td>
        <td v-if='sales_campaign.jenis_item_poin == "Per Item Number"'>{{ detail_poin.nama_part }}</td>
        <td v-if='sales_campaign.jenis_item_poin == "Per Item Number"'>{{ detail_poin.kelompok_part }}</td>
        <td v-if='sales_campaign.jenis_item_poin == "Per Item Number"'>{{ detail_poin.het }}</td>
        <td v-if='sales_campaign.jenis_item_poin == "Per Item Number"'>{{ detail_poin.status }}</td>
        <td v-if='sales_campaign.jenis_item_poin == "Per Kelompok Part"'>{{ detail_poin.id_kelompok_part }}</td>
        <td>
            <vue-numeric :read-only='mode == "detail"' class="form-control" v-model='detail_poin.poin' separator='.' :min='1'/>
        </td>
        <td width='10%'>
            <select :disabled='mode == "detail"' v-model='detail_poin.satuan' class="form-control">
                <option value="">-Pilih</option>
                <option v-if='sales_campaign.kategori == "Parts" || sales_campaign.kategori == "Acc"' value="Pcs">Pcs</option>
                <option v-if='sales_campaign.kategori == "Oil"' value="Botol">Botol</option>
                <option value="Dus">Dus</option>
            </select>
        </td>
        <td v-if='mode != "detail"'>
            <button class="btn btn-flat btn-danger" @click.prevent='hapus_detail_poin(index)'><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>
    <tr v-if='sales_campaign_detail_poin.length < 1'>
        <td colspan='9' class='text-center'>Tidak ada data.</td>
    </tr>
</table>
<div class="container-fluid" style='margin-bottom: 10px;'>
    <div class="row">
        <div class="col-sm-12 text-right">
            <button v-if='mode != "detail" && sales_campaign.jenis_item_poin == "Per Item Number"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_parts_sales_campaign_detail_poin'><i class="fa fa-plus"></i></button>
            <button v-if='mode != "detail" && sales_campaign.jenis_item_poin == "Per Kelompok Part"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_kelompok_part_sales_campaign_detail_poin'><i class="fa fa-plus"></i></button>
        </div>
    </div>
</div>
<?php $this->load->view('modal/h3_md_parts_sales_campaign_detail_poin'); ?>
<script>
    function pilih_parts_sales_campaign_detail_poin(data){
        data.satuan = '';
        form_.sales_campaign_detail_poin.push(data);
        h3_md_parts_sales_campaign_detail_poin_datatable.draw();
    }
</script>
<?php $this->load->view('modal/h3_md_kelompok_part_sales_campaign_detail_poin'); ?>
<script>
    function pilih_kelompok_part_sales_campaign_detail_poin(data){
        data.satuan = '';
        form_.sales_campaign_detail_poin.push(data);
        h3_md_kelompok_part_sales_campaign_detail_poin_datatable.draw();
    }
</script>
<?php $this->load->view('modal/h3_md_kelompok_part_filter_part_sales_campaign_detail_poin'); ?>
<script>
    function pilih_kelompok_part_filter_part_sales_campaign_detail_poin(data, type) {
        if (type == "add_filter") {
            $("#id_kelompok_part_filter_parts_sales_campaign_detail_poin").val(data.id_kelompok_part);
        } else if (type == "reset_filter") {
            $("#id_kelompok_part_filter_parts_sales_campaign_detail_poin").val("");
        }
        h3_md_parts_sales_campaign_detail_poin_datatable.draw();
        h3_md_kelompok_part_filter_part_sales_campaign_detail_poin_datatable.draw();
    }
</script>