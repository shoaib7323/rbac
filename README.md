# Professional RBAC System (Laravel & Blade)

A flexible Role-Based Access Control (RBAC) system with granular control at the Module, Feature, and Action levels.

## 🚀 Installation & Setup

Follow these steps to get the project running on your local machine:

### 1. Prerequisites
- **PHP** (>= 8.1)
- **Composer**
- **XAMPP** (with MySQL/Apache)

### 2. Clone the Project
```bash
git clone https://github.com/shoaib7323/rbac.git
cd rbac
```

### 3. Install Dependencies
```bash
composer install
```

### 4. Configure Environment
1. Copy the `.env.example` file to a new file named `.env`:
   ```bash
   cp .env.example .env
   ```
2. Open `.env` and configure your database settings:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=rbac
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Generate the implementation key:
   ```bash
   php artisan key:generate
   ```

### 5. Database Setup
Create a database named `rbac` in your phpMyAdmin, then run the migrations and seeds:
```bash
php artisan migrate:fresh --seed
```
*Note: This will populate the system with 8 modules and a default Super Admin.*

---

## 🏃 Running the Project

### Option A: Using Artisan Serve (Recommended for Dev)
```bash
php artisan serve
```
Access the project at: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

### Option B: Using XAMPP Apache
1. Move the folder to your `htdocs` directory.
2. Start **Apache** in XAMPP.
3. Access the project at: **[http://localhost/RBAC/public](http://localhost/RBAC/public)**

---

## 🔐 Login Credentials (Default)
- **Email:** `admin@example.com`
- **Password:** `password`

## 🛠 Features
- **Granular Permissions:** Control access at the action level (e.g., `inventory.products.adjust_stock`).
- **Module Matrix:** User-friendly UI to assign permissions across 8 key modules.
- **Predefined Roles:** Special protection for critical roles like 'Super Admin'.
- **Middleware Protection:** Automatic route protection using custom `permission` middleware.
