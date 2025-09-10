<?php
declare(strict_types=1);

namespace Andriy\LuxuryTax\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Color extends Field
{
    protected function _getElementHtml(AbstractElement $element): string
    {
        $value = (string)($element->getValue() ?: '#ffffff');
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value)) {
            $value = '#ffffff';
        }

        // Основне текстове поле (із name) — Magento збереже його значення
        $textId   = $element->getHtmlId();
        $textName = $element->getName();

        // Додаємо поряд нативний <input type="color"> (без name), який синхронізуємо з текстом
        $pickerId = $textId . '_picker';

        // Контрастний колір тексту для читабельності
        $textColor = $this->getContrastColor($value);

        $html  = '<div class="andriy-color-field" style="display:flex;align-items:center;gap:8px;">';

        $html .= sprintf(
            '<input type="text" id="%s" name="%s" value="%s" ' .
            'style="width:140px; padding:6px 8px; border:1px solid #ccc; border-radius:4px;' .
            'background:%s; color:%s;" />',
            $this->escapeHtmlAttr($textId),
            $this->escapeHtmlAttr($textName),
            $this->escapeHtmlAttr($value),
            $this->escapeHtmlAttr($value),
            $this->escapeHtmlAttr($textColor)
        );

        $html .= sprintf(
            '<input type="color" id="%s" value="%s" style="width:42px;height:32px;padding:0;border:0;background:none;" />',
            $this->escapeHtmlAttr($pickerId),
            $this->escapeHtmlAttr($value)
        );

        $html .= '</div>';

        // JS: синхронізація та підсвітка
        $html .= <<<HTML
<script>
require(['jquery'], function($) {
  var \$text   = $('#{$this->escapeHtmlAttr($textId)}');
  var \$picker = $('#{$this->escapeHtmlAttr($pickerId)}');

  function isHex(v){ return /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(v); }
  function hexToRgb(hex){
    hex = hex.replace('#','');
    if (hex.length === 3) hex = hex.split('').map(function(c){return c+c}).join('');
    var r = parseInt(hex.substr(0,2), 16), g = parseInt(hex.substr(2,2), 16), b = parseInt(hex.substr(4,2), 16);
    return {r:r,g:g,b:b};
  }
  function contrast(hex){
    var c = hexToRgb(hex);
    var yiq = ((c.r*299)+(c.g*587)+(c.b*114))/1000;
    return yiq >= 128 ? '#000000' : '#ffffff';
  }
  function paint(v){
    if (!isHex(v)) return;
    \$text.css({'background-color': v, 'color': contrast(v)});
  }

  // init
  paint(\$text.val());

  // picker -> text
  \$picker.on('input change', function(){
    var v = $(this).val();
    \$text.val(v).trigger('change');
    paint(v);
  });

  // manual text edit -> picker
  \$text.on('input change keyup', function(){
    var v = $(this).val().trim();
    if (isHex(v)) {
      \$picker.val(v);
      paint(v);
    }
  });
});
</script>
HTML;

        return $html;
    }

    private function getContrastColor(string $hex): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        if (!preg_match('/^[A-Fa-f0-9]{6}$/', $hex)) {
            return '#000000';
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        // YIQ контраст
        $yiq = (($r*299)+($g*587)+($b*114))/1000;
        return $yiq >= 128 ? '#000000' : '#ffffff';
    }
}
