# 📦 Sales & Purchase Cycle Enterprise ERP System
> **Full-Stack Web ERP System (Laravel, MySQL, DataTables Server-Side, Xendit API & DeepSeek AI)**

[![Laravel](https://img.shields.io/badge/Laravel-8.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-7.4%20%7C%208.x-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Xendit](https://img.shields.io/badge/Xendit-Payment_Gateway-0072FF?style=for-the-badge)](https://xendit.co)
[![DeepSeek AI](https://img.shields.io/badge/DeepSeek_AI-LLM_Assistant-00A67E?style=for-the-badge)](https://deepseek.com)

---

## 📌 About The Project

A comprehensive **Enterprise Resource Planning (ERP)** web application built to digitize and automate the entire **Purchasing & Sales Cycle** for electronics and retail enterprises. 

This project bridges complex business rules into a seamless, automated workflow—featuring **Cascading Document Flow** (PO/SO tracking, Goods Receipt, Shipments, Invoicing), **Vendor-Product Relational Mapping**, **Xendit Payment Gateway Integration**, and an interactive **DeepSeek AI Onboarding Assistant (ARIA)** for new staff onboarding.

---

## 🔥 Key Features & Business Logic

### 1. 🔄 Cascading Document Flow (Automated Transaction Pipeline)
- **Purchase Flow**: `Purchase Request (PR)` ➔ `Purchase Order (PO)` ➔ `Goods Receipt (GR)` ➔ `Purchase Invoice (PINV)` ➔ `Purchase Payment (PPAY)`
  - **Dynamic Auto-Fill**: Selecting a reference document (e.g. selecting PO in Goods Receipt form) auto-populates items, remaining quantities, unit prices, and locks SKUs.
  - **Auto Status Progression**: Orders progress to `Selesai` (Completed) automatically once all items are received/shipped in full.
  - **Smart Filtering**: Completed or canceled documents are automatically excluded from creation dropdowns.
- **Sales Flow**: `Sales Order (SO)` ➔ `Shipment / Surat Jalan (SHP)` ➔ `Sales Invoice (SINV)` ➔ `Sales Payment / Xendit Payment`
  - Customer auto-selection, stock limit checks, price/discount inheritance.

### 2. 🏢 Vendor ↔ Product Relational Mapping
- **Many-to-Many Architecture (`vendor_barang`)**: Vendors supply specific items based on their domain.
- **Real-Time Checkbox Matrix**: Interactive UI on Vendor master form with real-time search filtering and *Select All / Unselect All* controls.
- **PO Filtering**: Purchase Order product dropdowns automatically filter items based on the selected Vendor.

### 3. 💳 Xendit Payment Gateway & Settlement
- **Online Checkout Integration**: Generates online payment URLs supporting **QRIS, Virtual Accounts (BCA/Mandiri/BRI/BNI), E-Wallets, and Credit Cards**.
- **Real-Time Webhook & Polling**: Automatically receives payment callbacks and recalculates invoice balances, instantly updating Sales Invoice status to `Lunas` (*Paid*).

### 4. 🤖 ARIA — AI Internal ERP Onboarding Assistant
- **LLM Integration**: Powered by DeepSeek API (`deepseek-chat`) acting as an internal SOP & onboarding assistant for new employees.
- **Modern Chat Interface**: WhatsApp-style bubble UI, typing indicators, custom ERP persona system prompt, and interactive quick-start chips.

### 5. ⚡ Performance & Security
- **DataTables Server-Side Processing**: Fast rendering of large-scale dataset tables using optimized SQL `JOIN`s.
- **Role-Based Access Control (RBAC)**: Fine-grained backend authorization via custom `BackendPolicy` middleware.

---

## 📐 System Architecture & Workflow

```
[ PURCHASE CHAIN ]
  Purchase Order (PO) ──► Goods Receipt (GR) ──► Purchase Invoice ──► Purchase Payment
  (Vendor Filtered)      (Auto-Fill Qty)       (Auto Unit Price)    (Auto Balance)

[ SALES CHAIN ]
  Sales Order (SO)    ──► Shipment / Surat Jalan ──► Sales Invoice ──► Manual Payment / Xendit (QRIS/VA)
  (Customer Order)       (Auto Remaining Qty)      (Auto Price/Disc)   (Webhook Auto-Settlement)
```

---

## 🛠️ Tech Stack & Dependencies

- **Backend**: Laravel (MVC Architecture), PHP 7.4 / 8.x, Eloquent ORM
- **Database**: MySQL (Relational Schema, Foreign Key Cascades, Pivot Tables)
- **Frontend**: Bootstrap, DataTables Server-Side Processing, AJAX (Fetch API), jQuery, Gentelella Admin Theme
- **Integrations**: Xendit Payment Gateway API, DeepSeek LLM API

---

## 🚀 Quick Start / Local Installation

### Prerequisites
- PHP >= 7.4
- Composer >= 2.x
- MySQL Database
- Node.js & NPM

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/wil332/sales-purchase-cycle-laravel.git
   cd sales-purchase-cycle-laravel
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run dev
   ```

3. **Configure Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Update `.env` database configuration:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_sales_purchase
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Run Migrations & Master Data Seeder**
   ```bash
   php artisan migrate:fresh --seed --class=MasterDataCleanSeeder
   ```

5. **Start Local Development Server**
   ```bash
   php artisan serve --port=8080
   ```
   Open `http://127.0.0.1:8080/login` in your browser.

---

## 🔑 Demo Access Credentials

| Role | Email Login | Password | Access Rights |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@perusahaan.com` | `admin123` | Full Access to All Modules & Config |
| **Personal Admin** | `wilbert@gmail.com` | `admin123` | Full Administrator Access |
| **Purchasing Staff** | `purchasing@perusahaan.com` | `purchasing123` | PO, PR, & Vendor Management |
| **Warehouse Staff** | `gudang@perusahaan.com` | `gudang123` | Goods Receipt & Shipments |
| **Sales Staff** | `sales@perusahaan.com` | `sales123` | Sales Orders, Customers, & Invoices |

---

## 👨‍💻 Developer & Contact

**WILBERT**  
*Full-Stack Web Developer & AI Agent Integration Specialist*  
Medan, Indonesia

- 🌐 **Portfolio**: [wilbert-portfolio.infinityfreeapp.com](https://wilbert-portfolio.infinityfreeapp.com/)
- 💻 **GitHub**: [github.com/wil332](https://github.com/wil332)
- ✉️ **Email**: ndwilbert@gmail.com
