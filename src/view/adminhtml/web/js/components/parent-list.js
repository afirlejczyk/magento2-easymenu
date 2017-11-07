define([
    'underscore',
    'Magento_Ui/js/form/element/ui-select'
], function (_,Select) {
    'use strict';

    function isEmptyValue(value) {
        if (value === null || value === '') {
            return true;
        }

        return false;
    }

    return Select.extend({
        /**
         * Set caption
         */
        setCaption: function () {
            var length;

            if (!_.isArray(this.value()) && !isEmptyValue(this.value())) {
                length = 1;
            } else if (this.value()) {
                length = this.value().length;
            } else {
                this.value([]);
                length = 0;
            }

            if (length > 1) {
                this.placeholder(length + ' ' + this.selectedPlaceholders.lotPlaceholders);
            } else if (length && this.getSelected().length) {
                this.placeholder(this.getSelected()[0].label);
            } else {
                this.placeholder(this.selectedPlaceholders.defaultPlaceholder);
            }

            return this.placeholder();
        }
    });
});
