Vue.component('range-date-picker', {
    template: '<input/>',
    props: [
        'config', 'value'
    ],
    mounted: function () {
        var self = this;

        if(this.config == undefined){
            this.config = {
                opens: 'left',
                autoUpdateInput: false,
                locale: {
                  format: 'DD/MM/YYYY'
                }
            };
        }

        $(this.$el).daterangepicker(this.config)
            .on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                self.$emit('apply-date', picker);
            }).on('cancel.daterangepicker', function (ev, picker) {
                $(this).val(null);
                self.$emit('cancel-date', picker);
            });
    },
    beforeDestroy: function () {
        $(this.$el).daterangepicker('destroy')
    }
});