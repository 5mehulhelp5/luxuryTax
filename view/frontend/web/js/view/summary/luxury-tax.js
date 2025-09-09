define([
    'uiComponent',
    'Magento_Checkout/js/model/totals'
], function (Component, totals) {
    'use strict';

    function getSegments() {
        var t = totals.totals();
        return t && t['total_segments'] ? t['total_segments'] : [];
    }
    function getByCode(code) {
        var segs = getSegments();
        for (var i = 0; i < segs.length; i++) {
            if (segs[i].code === code) return Number(segs[i].value || 0);
        }
        return 0;
    }

    // Обчислюємо "невідомий залишок": grand_total - сума ВСІХ інших сегментів
    function computeLuxury() {
        var segs = getSegments();
        if (!segs.length) return 0;

        var grand = getByCode('grand_total');
        if (!grand) return 0;

        var sumOthers = 0;
        for (var i = 0; i < segs.length; i++) {
            var s = segs[i];
            if (s.code === 'grand_total') continue;
            sumOthers += Number(s.value || 0);
        }
        var rest = grand - sumOthers;

        // фільтр дрібних похибок, щоб не блиміло 0.0000001
        return Math.abs(rest) > 0.005 ? rest : 0;
    }

    return Component.extend({
        defaults: {
            template: 'Andriy_LuxuryTax/checkout/summary/luxury_tax'
        },

        // KO сам перевирахує, бо totals.totals() — observable
        isDisplayed: function () {
            return computeLuxury() > 0;
        },
        getTitle: function () { return 'Luxury Tax'; },
        getValue: function () {
            var val = computeLuxury();
            return window.checkout.priceUtils
                ? window.checkout.priceUtils.formatPrice(val)
                : val.toFixed(2);
        }
    });
});
