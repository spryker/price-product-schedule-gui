/**
 * Copyright (c) 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var DependentSelectBox = require('ZedGuiModules/libs/dependent-select-box');

function PriceProductScheduleCreate(options) {
    $.extend(this, options);

    var self = this;

    this.init = function () {
        // From spryker/gui 5.4.0 on, these fields are built with `DateTimePickerType`, which renders
        // a single input marked with `data-spryker-picker` and lets the Gui DateTimePicker initialize
        // and range-link them. Older Gui versions render the compound `DateTimeType` sub-fields the
        // legacy pickers below are bound to.
        if (!this.isGuiDateTimePickerUsed()) {
            this.initActiveFromDatepicker();
            this.initActiveToDatepicker();
        }

        this.hideTimezoneMessage();
        this.initDependentSelectBox();
        this.preventDoubleSubmission();
    };

    /**
     * The legacy `$activeFrom`/`$activeTo` selectors target the compound `DateTimeType` date
     * sub-field, which no longer exists once the single-input picker is in place.
     *
     * @return {boolean}
     */
    this.isGuiDateTimePickerUsed = function () {
        return !this.$activeFrom.length || this.$activeFrom.is('[data-spryker-picker]');
    };

    /**
     * @deprecated Superseded by `DateTimePickerType` and the Gui DateTimePicker. Kept only for
     *   installations running spryker/gui older than 5.4.0.
     */
    this.initActiveFromDatepicker = function () {
        this.$activeFrom.click(function (event) {
            event.preventDefault();
        });
        this.$activeFrom.datepicker({
            altFormat: 'yy-mm-dd',
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            defaultData: 0,
        });
    };

    /**
     * @deprecated Superseded by `DateTimePickerType` and the Gui DateTimePicker. Kept only for
     *   installations running spryker/gui older than 5.4.0.
     */
    this.initActiveToDatepicker = function () {
        this.$activeTo.click(function (event) {
            event.preventDefault();
        });
        this.$activeTo.datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            defaultData: 0,
        });
    };

    this.toggleVisibility = function (display) {
        this.$activeFromTimezoneText.toggle(display);
        this.$activeToTimezoneText.toggle(display);
    };

    this.hideTimezoneMessage = function () {
        if (!this.$store.val()) {
            this.toggleVisibility(false);
        }
    };

    this.fillTimezoneMessage = function (data) {
        this.$timezone.each(function (index, element) {
            $(element).text(data.store.timezone);
        });
    };

    this.successCallback = function (data) {
        if (!data.store) {
            self.toggleVisibility(false);

            return;
        }

        self.fillTimezoneMessage(data);
        self.toggleVisibility(true);
    };

    this.preventDoubleSubmission = function () {
        this.form.submit(function () {
            self.submit.prop('disabled', true);
        });
    };

    this.initDependentSelectBox = function () {
        new DependentSelectBox({
            $trigger: this.$store,
            $target: this.$currency,
            requestUrl: this.requestUrl,
            dataKey: this.dataKey,
            responseData: this.currencies,
            successCallback: this.successCallback,
        });
    };

    this.init();
}

module.exports = PriceProductScheduleCreate;
