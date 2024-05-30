<button onclick='return report()' class="btn btn-flat btn-success" style='margin:0px 5px;' type='button'>Report</button>
<script>
    function report(){
        start_date = $('#filter_date_record_demand_start').val();
        end_date = $('#filter_date_record_demand_end').val();

        window.open('dealer/h3_dealer_reason_demand/report?start_date=' + start_date + '&end_date=' + end_date, '_blank');
    }
</script>