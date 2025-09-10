require(['jquery'], function($) {
    'use strict';

    function applyColors() {
        $('tr.data-row').each(function() {
            var row = $(this);

            // Знаходимо значення в прихованій колонці
            var conditionCell = row.find('td[data-index="luxury_tax_condition_amount"]');
            var conditionAmount = parseFloat(conditionCell.text()) || 0;

            console.log('Condition amount:', conditionAmount);

            // Застосовуємо кольори
            if (conditionAmount < 100) {
                row.css('background-color', '#ffffff');
            } else if (conditionAmount >= 100 && conditionAmount <= 1000) {
                row.css('background-color', '#bbffbb');
            } else if (conditionAmount > 1000) {
                row.css('background-color', '#bbffbb');
            }
        });
    }

    // Чекаємо завантаження грида
    function waitForGrid() {
        var interval = setInterval(function() {
            if ($('td[data-index="luxury_tax_condition_amount"]').length > 0) {
                clearInterval(interval);
                applyColors();
            }
        }, 500);
    }

    $(document).ready(waitForGrid);
    $(document).on('contentUpdated', waitForGrid);
});
