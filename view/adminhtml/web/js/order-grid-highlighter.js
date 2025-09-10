define([
    'jquery',
    'uiRegistry'
], function ($, registry) {
    'use strict';

    function getCfg() {
        return window.andriyLuxuryTaxColors || {};
    }
    function toNumber(raw) {
        if (raw == null || raw === '') return null;
        if (typeof raw === 'string') {
            raw = raw.replace(/\s/g,'').replace(/[^\d.,-]/g,'').replace(',', '.');
        }
        var n = parseFloat(raw);
        return Number.isFinite(n) ? n : null;
    }

    function pickColor(amount) {
        var cfg = getCfg();
        var colLt100   = cfg.lt100   || '#ffffff';
        var colBetween = cfg.btw     || '#fffbbb';   // ключ 'btw' як у твоєму cfg
        var colGt1000  = cfg.gt1000  || '#bbffbb';

        if (amount < 100)   return colLt100;
        if (amount <= 1000) return colBetween;
        return colGt1000;
    }

    function colorRow($tr, amount) {
        var col = pickColor(amount);
        $tr.find('td').css('background-color', col);
    }

    // map: entity_id -> amount (тільки валідні)
    function buildMapById(items) {
        var map = Object.create(null);
        items.forEach(function (it) {
            var n = toNumber(it.luxury_tax_condition_amount);
            if (n !== null) {
                map[String(it.entity_id)] = n;
            }
        });
        return map;
    }

    function applyColors(grid) {
        var cfg = getCfg();
        if (cfg.enabled === false) return;

        var items = (grid && grid.source && grid.source.get('data.items')) || [];
        if (!items.length) return;

        var byId = buildMapById(items);
        var $trs = $('div[data-role="grid-wrapper"] table.data-grid tbody tr.data-row');

        // 1) скинь фон усім — щоб не лишалось від попередніх проходів
        $trs.find('td').css('background-color', '');

        // 2) фарбуй тільки там, де є валідне значення
        $trs.each(function (i) {
            var $tr = $(this);
            var id  = $tr.find('input[data-action="select-row"]').attr('data-id');
            var amt = null;

            if (id && Object.prototype.hasOwnProperty.call(byId, String(id))) {
                amt = byId[String(id)];
            } else if (items[i]) {
                amt = toNumber(items[i].luxury_tax_condition_amount);
            }

            if (amt !== null) {
                colorRow($tr, amt);
            }
            // якщо null — нічого не робимо, рядок залишається без змін
        });
    }

    function init() {
        registry.get('sales_order_grid.sales_order_grid', function (grid) {
            var repaint = function () { setTimeout(function(){ applyColors(grid); }, 0); };
            repaint();

            if (grid && grid.source && grid.source.on) {
                grid.source.on('reloaded', repaint);
                grid.source.on('updated',  repaint);
            }
            $(document).on('contentUpdated', repaint);
        });
    }

    return init;
});
