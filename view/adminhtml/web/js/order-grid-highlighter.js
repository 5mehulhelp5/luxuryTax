define([
    'jquery',
    'uiRegistry'
], function ($, registry) {
    'use strict';

    function getCfg() {
        return window.andriyLuxuryTaxColors || {};
    }

    function pickColor(amount) {
        var cfg = getCfg();
        console.log(cfg);
        var colLt100   = cfg.lt100   || '#ffffff';
        var colBetween = cfg.btw || '#fffbbb';
        var colGt1000  = cfg.gt1000  || '#bbffbb';

        amount = Number(amount) || 0;
        if (amount < 100)   return colLt100;
        if (amount <= 1000) return colBetween;
        return colGt1000;
    }

    function colorRow($tr, amount) {
        var col = pickColor(amount);
        $tr.find('td').css('background-color', col);
    }

    function buildMapById(items) {
        var map = Object.create(null);
        items.forEach(function (it) {
            var val = it.luxury_tax_condition_amount;
            if (typeof val === 'string') {
                val = val.replace(/\s/g,'')
                    .replace(/[^\d.,-]/g,'')
                    .replace(',', '.');
            }
            map[String(it.entity_id)] = parseFloat(val) || 0;
        });
        return map;
    }

    function applyColors(grid) {
        var cfg = getCfg();
        if (cfg.enabled === false) return;

        var items = (grid && grid.source && grid.source.get('data.items')) || [];
        if (!items.length) return;

        var byId = buildMapById(items);
        var $rows = $('div[data-role="grid-wrapper"] table.data-grid tbody tr.data-row');

        $rows.each(function (i) {
            var $tr = $(this);
            var id = $tr.find('input[data-action="select-row"]').attr('data-id');
            var amt = 0;

            if (id && Object.prototype.hasOwnProperty.call(byId, String(id))) {
                amt = byId[String(id)];
            } else if (items[i]) {
                var raw = String(items[i].luxury_tax_condition_amount || '0');
                amt = parseFloat(raw.replace(',', '.')) || 0;
            }

            colorRow($tr, amt);
        });
    }

    function init() {
        registry.get('sales_order_grid.sales_order_grid', function (grid) {
            var repaint = function () { setTimeout(function(){ applyColors(grid); }, 0); };

            repaint(); // initial run

            if (grid && grid.source && grid.source.on) {
                grid.source.on('reloaded', repaint);
                grid.source.on('updated',  repaint);
            }

            $(document).on('contentUpdated', repaint);
        });
    }

    return init;
});
