<div class="row">
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-6">
                <input :disabled='mode == "detail"' type="radio" v-model='sales_campaign.jenis_item_diskon' value='Per Kelompok Part'> Per Kelompok Part
            </div>
            <div class="col-sm-6">
                <input :disabled='mode == "detail"' type="radio" v-model='sales_campaign.jenis_item_diskon' value='Per Item Number'> Per Item Number
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Jenis Diskon Campaign</label>
                    <div v-bind:class="{ 'has-error': error_exist('jenis_diskon_campaign') }" class="col-sm-8">
                      <select :disabled='mode == "detail"' class="form-control" v-model='sales_campaign.jenis_diskon_campaign'>
                        <option value="">-Pilih-</option>
                        <option value="Additional">Additional</option>
                        <option value="Non Additional">Non Additional</option>
                      </select>
                      <small v-if="error_exist('jenis_diskon_campaign')" class="form-text text-danger">{{ get_error('jenis_diskon_campaign') }}</small>
                    </div>
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Produk Program</label>
                    <div v-bind:class="{ 'has-error': error_exist('produk_program_diskon') }" class="col-sm-8">
                      <select :disabled='mode == "detail"' class="form-control" v-model='sales_campaign.produk_program_diskon'>
                        <option value="">-Pilih-</option>
                        <option value="Global">Global</option>
                        <option value="Per Item">Per Item</option>
                      </select>
                      <small v-if="error_exist('produk_program_diskon')" class="form-text text-danger">{{ get_error('produk_program_diskon') }}</small>
                    </div>
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label">Periode Program</label>
                    <div v-bind:class="{ 'has-error': error_exist('start_date_diskon') }" class="col-sm-8">
                      <range-date-picker :disabled='mode == "detail"' :config='get_config_periode_campaign_diskon()' class='form-control' @apply-date='applyDatePeriodeCampaignDiskon' @cancel-date='cancelDatePeriodeCampaignDiskon' readonly></range-date-picker>
                      <small v-if="error_exist('start_date_diskon')" class="form-text text-danger">{{ get_error('start_date_diskon') }}</small>  
                    </div>
                </div>    
            </div>
        </div>
    </div>
</div>
<div class="container-fluid bg-blue-gradient" style='padding: 7px 0px;'>
    <div class="row">
        <div class="col-sm-12 text-center">
            <span class="text-bold">Detail Diskon</span>
        </div>
    </div>
</div>
<table class="table table-compact">
    <tr>
        <td width='3%'>No.</td>
        <td v-if='sales_campaign.jenis_item_diskon == "Per Item Number"'>Kode Part</td>
        <td v-if='sales_campaign.jenis_item_diskon == "Per Item Number"'>Nama Part</td>
        <td v-if='sales_campaign.jenis_item_diskon == "Per Item Number"'>Kelompok Part</td>
        <td v-if='sales_campaign.jenis_item_diskon == "Per Item Number"'>HET</td>
        <td v-if='sales_campaign.jenis_item_diskon == "Per Item Number"'>Status</td>
        <td v-if='sales_campaign.jenis_item_diskon == "Per Kelompok Part"'>Kelompok Part</td>
        <td width='10%'>Tipe Diskon</td>
        <td v-if='sales_campaign.produk_program_diskon == "Per Item"' width='3%'></td>
        <td v-if='mode != "detail"' width='3%'></td>
    </tr>
    <tr v-if='sales_campaign_detail_diskon.length > 0' v-for='(detail_diskon, index) of sales_campaign_detail_diskon'>
        <td>{{ index + 1 }}.</td>
        <td v-if='sales_campaign.jenis_item_diskon == "Per Item Number"'>{{ detail_diskon.id_part }}</td>
        <td v-if='sales_campaign.jenis_item_diskon == "Per Item Number"'>{{ detail_diskon.nama_part }}</td>
        <td v-if='sales_campaign.jenis_item_diskon == "Per Item Number"'>{{ detail_diskon.kelompok_part }}</td>
        <td v-if='sales_campaign.jenis_item_diskon == "Per Item Number"'>{{ detail_diskon.het }}</td>
        <td v-if='sales_campaign.jenis_item_diskon == "Per Item Number"'>{{ detail_diskon.status }}</td>
        <td v-if='sales_campaign.jenis_item_diskon == "Per Kelompok Part"'>{{ detail_diskon.id_kelompok_part }}</td>
        <td>
            <select :disabled='mode == "detail"' class="form-control" v-model='detail_diskon.tipe_diskon'>
                <option value="">-Pilih-</option>
                <option value="Rupiah">Rupiah</option>
                <option value="Persen">Persen</option>
            </select>
        </td>
        <td v-if='sales_campaign.produk_program_diskon == "Per Item"'>
            <button class="btn btn-flat btn-info" @click.prevent='open_diskon_bertingkat(index)'><i class="fa fa-eye"></i></button>
        </td>
        <td v-if='mode != "detail"'>
            <button class="btn btn-flat btn-danger" @click.prevent='hapus_detail_diskon(index)'><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>
    <tr v-if='sales_campaign_detail_diskon.length < 1'>
        <td colspan='8' class='text-center'>Tidak ada data.</td>
    </tr>
</table>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 text-right">
            <button v-if='mode != "detail" && sales_campaign.jenis_item_diskon == "Per Item Number"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_parts_sales_campaign_detail_diskon'><i class="fa fa-plus"></i></button>
            <button v-if='mode != "detail" && sales_campaign.jenis_item_diskon == "Per Kelompok Part"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_kelompok_part_sales_campaign_detail_diskon'><i class="fa fa-plus"></i></button>
        </div>
    </div>
</div>
<?php $this->load->view('modal/h3_md_parts_sales_campaign_detail_diskon'); ?>
<script>
    function pilih_parts_sales_campaign_detail_diskon(data){
        form_.sales_campaign_detail_diskon.push(data);
        h3_md_parts_sales_campaign_detail_diskon_datatable.draw();
    }
</script>
<?php $this->load->view('modal/h3_md_kelompok_part_sales_campaign_detail_diskon'); ?>
<script>
    function pilih_kelompok_part_sales_campaign_detail_diskon(data){
        data.diskon_bertingkat = [];
        form_.sales_campaign_detail_diskon.push(data);
        h3_md_kelompok_part_sales_campaign_detail_diskon_datatable.draw();
    }
</script>
<?php $this->load->view('modal/h3_md_kelompok_part_filter_part_sales_campaign_detail_diskon'); ?>
<script>
    function pilih_kelompok_part_filter_part_sales_campaign_detail_diskon(data, type) {
        if (type == "add_filter") {
            $("#id_kelompok_part_filter_parts_sales_campaign_detail_diskon").val(data.id_kelompok_part);
        } else if (type == "reset_filter") {
            $("#id_kelompok_part_filter_parts_sales_campaign_detail_diskon").val("");
        }
        h3_md_parts_sales_campaign_detail_diskon_datatable.draw();
        h3_md_kelompok_part_filter_part_sales_campaign_detail_diskon_datatable.draw();
    }
</script>