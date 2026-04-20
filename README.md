# 📚 Online Bookshop

## Project Overview
A fully functional e-commerce web application where customers can browse, search, add to cart/wishlist, and purchase books online. Includes an admin panel to manage books, orders, users, and sales reports.

## Technologies Used

### Backend
- **PHP** 8.5.4
- **Laravel** 13.3.0
- **MySQL** (development) / **PostgreSQL** (production)
- **Stripe API** - payment processing

### Frontend
- **HTML5**, **CSS3**, **Tailwind CSS** 3.4.13
- **JavaScript (ES6)** + **AJAX** for real‑time cart/wishlist updates
- **Font Awesome** icons

### Development & Deployment
- **XAMPP** (local)
- **Composer** / **NPM**
- **Git** + **GitHub**
- **Render** (deployment)

## Key Features

### Customer Side
- User registration & login (Laravel Breeze)
- Browse books with category filter & search
- Sorting (newest, price, title)
- Shopping cart - add/remove, update quantity (AJAX)
- Floating cart sidebar with real‑time updates
- Wishlist - toggle heart (AJAX), move to cart
- Checkout - delivery address, delivery method (standard/express/next‑day)
- Payment - Stripe (credit card), Cash on Delivery, Bank Transfer
- Order history & cancellation (within 7 days)
- Book ratings (1–5 stars)

### Admin Side (separate login)
- Dashboard with statistics (books, orders, revenue, pending cancellations)
- Full CRUD for books (add, edit, delete, low‑stock alerts)
- Order management - view, update status, generate tracking numbers
- Cancel request approval & refund processing
- Sales reports with CSV export
- Low stock email alerts

## Challenges & Solutions

| Challenge | Solution |
|-----------|----------|
| PHP version mismatch (Laravel 13 requires PHP 8.4, XAMPP used 8.2) | Upgraded PHP manually and adjusted `composer.json` to allow PHP 8.3, then switched to PHP 8.4 in production Dockerfile. |
| AJAX cart/wishlist returning raw JSON instead of updating UI | Removed `<form>` tags around wishlist buttons; used `fetch` with proper headers and `preventDefault()`. |
| Duplicate `cancellation_requested` text in admin orders table | Created accessor `getFormattedStatusAttribute()` to display “Cancellation Requested”. |
| Deploying Laravel on Render (Docker build failures) | Switched from Docker to Render’s native PHP runtime; used `--ignore-platform-req` flags for Composer. |
| Missing `.env` file on server | Added `.env` to repository (with dummy values) and set real credentials via Render environment variables. |
| Heart icon not toggling correctly | Fixed controller to return JSON only and added JavaScript to toggle `fas`/`far` classes. |
| Order cancellation refund amount not saving | Added `refund_amount` to `$fillable` in Order model and updated controller logic. |

## Installation (Local)

```bash
git clone https://github.com/yourusername/bookshop.git
cd bookshop
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
# Configure database in .env
php artisan migrate --seed
php artisan serve

