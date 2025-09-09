# Andriy_LuxuryTax

Magento 2 module that introduces a custom **Luxury Tax** feature.

---

## 🚀 Features

- Adds a new total (`luxury_tax`) to **Cart**, **Checkout**, and **Order totals**
- Persists values in `sales_order`, `sales_invoice`, and `sales_creditmemo` tables
- Displays **Luxury Tax** in the Admin panel on **Order**, **Invoice**, and **Credit Memo** view pages
- Highlights rows in the **Sales Orders grid** depending on the `luxury_tax_condition_amount` value:
    - `< 100` → ⚪ white
    - `100–1000` → 🟡 yellow
    - `> 1000` → 🟢 green

---

## 📦 Installation

### Via Composer

```bash
composer require andriy/magento2-luxury-tax:dev-main
bin/magento module:enable Andriy_LuxuryTax
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```
### Manual Installation

1. Copy the module to: app/code/Andriy/LuxuryTax
2. 2. Run the following Magento commands:
```bash
bin/magento module:enable Andriy_LuxuryTax
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```
## ⚙️ Technical Details

- **Custom total calculation**:  
  `Andriy\LuxuryTax\Model\Quote\Address\Total\LuxuryTax`

- **Data persistence** across Quote → Order → Invoice / Credit Memo via fieldsets and observers

- **Admin totals extended with plugins on**:
    - `Magento\Sales\Block\Adminhtml\Order\Totals`
    - `Magento\Sales\Block\Adminhtml\Order\Invoice\Totals`
    - `Magento\Sales\Block\Adminhtml\Order\Creditmemo\Totals`

- **Orders grid** extended with `luxury_tax_condition_amount`;  
  rows highlighted via JS depending on the value

---

## 🛠 Requirements

- Magento Open Source / Commerce **2.4.x**
- PHP **>= 7.4**
