define('Andriy_LuxuryTax/js/order-grid-highlighter', [
    'jquery',
    'uiRegistry'
], function ($, registry) {
    'use strict';

    function colorRow($row, amount) {
        amount = Number(amount) || 0;
        $row.css({'background-color':'', 'border-left':'', 'border-right':''});
        if (amount < 100) {
            $row.css('background-color', '#ffffff');     // білий

            $row.closest('tr').find('td').css('background-color', '#ffffff');
        } else if (amount <= 1000) {
            $row.css('background-color', '#fffbbb');     // жовтий
            $row.closest('tr').find('td').css('background-color', '#fffbbb');
        } else {
            $row.css('background-color', '#bbffbb');     // зелений

            $row.closest('tr').find('td').css('background-color', '#bbffbb');
        }
    }

    function buildMapById(items) {
        var map = Object.create(null);
        items.forEach(function (it) {
            var val = it.luxury_tax_condition_amount;
            if (typeof val === 'string') {
                val = val.replace(/\s/g,'').replace(/[^\d.,-]/g,'').replace(',', '.');
            }
            map[String(it.entity_id)] = parseFloat(val) || 0;
        });
        return map;
    }

    function applyColors(grid) {
        var items = (grid && grid.source && grid.source.get('data.items')) || [];
        if (!items.length) return;

        // 1) пробуємо мапити за entity_id з чекбокса (найнадійніше)
        var byId = buildMapById(items);
        var $rows = $('div[data-role="grid-wrapper"] table.data-grid tbody tr.data-row');

        if ($rows.length) {
            $rows.each(function (i) {
                var $row = $(this);
                var id = $row.find('input[data-action="select-row"]').attr('data-id');
                if (id && byId.hasOwnProperty(id)) {
                    colorRow($row, byId[id]);
                } else {
                    // 2) fallback: за індексом (порядок рядків = порядок items)
                    var item = items[i];
                    var amt = item ? (parseFloat(String(item.luxury_tax_condition_amount).replace(',', '.')) || 0) : 0;
                    colorRow($row, amt);
                }
            });
        }
    }

    function init() {
        registry.get('sales_order_grid.sales_order_grid', function (grid) {
            var repaint = function () { setTimeout(function(){ applyColors(grid); }, 0); };

            repaint(); // перший прогін

            if (grid && grid.source && grid.source.on) {
                grid.source.on('reloaded', repaint);
                grid.source.on('updated',  repaint);
            }
            $(document).on('contentUpdated', repaint);
        });
    }

    return init;
});
