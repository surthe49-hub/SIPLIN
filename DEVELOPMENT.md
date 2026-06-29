# 🛠️ SIPLIN - Development Guide

**For Developers & Contributors**

---

## 📋 Table of Contents

- [Quick Start](#-quick-start)
- [Development Environment](#-development-environment)
- [Hot Module Reload (HMR)](#-hot-module-reload-hmr)
- [Project Structure](#-project-structure)
- [Code Standards](#-code-standards)
- [Testing](#-testing)
- [Troubleshooting](#-troubleshooting)
- [Common Commands](#-common-commands)

---

## 🚀 Quick Start

### Prerequisites
- **PHP 8.2+** (Tested on 8.3.23)
- **Composer 2.x**
- **Node.js 18+** & NPM
- **MySQL 8.0** / MariaDB 10.6+ / SQLite 3.x

### Initial Setup
```bash
# 1. Clone repository
git clone https://github.com/risunCode/SIPLIN-Laravel.git
cd SIPLIN-Laravel

# 2. Install dependencies
composer install
npm install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Database setup
php artisan migrate
php artisan db:seed

# 5. Create storage link
php artisan storage:link
```

---

## 💻 Development Environment

### Running the Development Server

**⚠️ IMPORTANT: Development requires 2 terminals running simultaneously!**

```bash
# Terminal 1 - Start Vite dev server (Hot Reload)
npm run dev

# Terminal 2 - Start Laravel server
php artisan serve
```

**Access Application:** http://127.0.0.1:8000

### What Each Server Does

| Server | Port | Purpose |
|--------|------|---------|
| **Laravel** | 8000 | PHP backend, routes, API |
| **Vite** | 5173 | Asset compilation, HMR |

### Default Login Credentials
```
Email: admin@inventory.com
Password: password
```

---

## 🔥 Hot Module Reload (HMR)

### How HMR Works
1. Vite watches for file changes in `resources/`
2. When CSS/JS changes, browser updates instantly without full reload
3. When Blade templates change, full page refresh occurs

### HMR Not Working?

**Check these first:**
```bash
# 1. Verify Vite is running
npm run dev  # Should show "VITE ready in X ms"

# 2. Check if public/hot file exists (should auto-create)
ls public/hot  # Windows: dir public\hot

# 3. Clear caches
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

**Manual HMR Fix (if needed):**
```bash
# Delete hot file if stuck
rm public/hot  # Windows: del public\hot

# Restart Vite
npm run dev
```

### Vite Configuration
```javascript
// vite.config.js - Current configuration
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '127.0.0.1',
        port: 5173,
        strictPort: true,
        hmr: {
            host: '127.0.0.1',
        },
    },
});
```

---

## 📁 Project Structure

```
inventaris-barang/
├── app/
│   ├── Enums/           # PHP Enums (AcquisitionType, ConditionType, etc.)
│   ├── Helpers/         # Helper functions (siplin.php)
│   ├── Http/
│   │   ├── Controllers/ # Route controllers
│   │   ├── Middleware/  # Custom middleware
│   │   └── Requests/    # Form request validation
│   ├── Models/          # Eloquent models
│   ├── Notifications/   # Laravel notifications
│   └── Observers/       # Model observers
│
├── config/
│   ├── siplin.php     # System configuration
│   ├── security_questions.php
│   └── inventory.php
│
├── database/
│   ├── migrations/      # Database migrations
│   └── seeders/         # Database seeders
│
├── resources/
│   ├── css/app.css      # TailwindCSS styles
│   ├── js/app.js        # Alpine.js + SweetAlert2
│   └── views/           # Blade templates
│
├── routes/
│   └── web.php          # Web routes
│
└── public/
    ├── build/           # Production assets (git-ignored)
    └── images/          # Static images
```

---

## 📐 Code Standards

### PHP (Laravel Pint)
```bash
# Run code formatter
./vendor/bin/pint

# Check without fixing
./vendor/bin/pint --test
```

### JavaScript/CSS
- Use **Alpine.js** for interactivity
- Use **TailwindCSS** classes
- Follow existing component patterns

### Blade Templates
- Use **components** (`<x-button />`)
- Follow **BEM-like** naming for custom classes
- Keep logic minimal in views

### Naming Conventions
```
Controllers: PascalCase + Controller suffix (CommodityController)
Models:      PascalCase singular (Commodity)
Tables:      snake_case plural (commodities)
Routes:      kebab-case (commodities/create)
Views:       kebab-case (commodities/index.blade.php)
```

---

## 🧪 Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=CommodityTest

# Run with coverage
php artisan test --coverage
```

### Test Structure
```
tests/
├── Feature/           # Feature tests (HTTP, forms)
├── Unit/              # Unit tests (models, services)
└── TestCase.php       # Base test class
```

---

## 🔧 Troubleshooting

### Issue: "npm artisan serve" command not found
```bash
# ❌ WRONG - npm and artisan are separate
npm artisan serve

# ✅ CORRECT - Run in separate terminals
npm run dev        # Terminal 1
php artisan serve  # Terminal 2
```

### Issue: Assets not loading / 404 errors
```bash
# For production (no HMR):
npm run build

# For development (with HMR):
npm run dev  # Must be running!
```

### Issue: Database migration errors
```bash
# Reset database completely
php artisan migrate:fresh --seed

# If foreign key errors:
php artisan migrate:reset
php artisan migrate
php artisan db:seed
```

### Issue: Permission denied errors
```bash
# Fix storage permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
chown -R $USER:www-data storage bootstrap/cache
```

### Issue: Class not found after adding new files
```bash
# Regenerate autoload
composer dump-autoload

# Clear all caches
php artisan optimize:clear
```

### Issue: Styles not updating
```bash
# Clear view cache
php artisan view:clear

# Rebuild assets
npm run build  # or npm run dev
```

---

## 📝 Common Commands

### Development
```bash
npm run dev              # Start Vite with HMR
php artisan serve        # Start Laravel server
php artisan tinker       # Interactive REPL
```

### Database
```bash
php artisan migrate              # Run migrations
php artisan migrate:fresh --seed # Reset & seed
php artisan db:seed              # Run seeders only
```

### Cache
```bash
php artisan optimize:clear  # Clear all caches
php artisan config:clear    # Clear config cache
php artisan view:clear      # Clear view cache
php artisan cache:clear     # Clear app cache
```

### Production
```bash
npm run build           # Build optimized assets
php artisan optimize    # Cache config & routes
php artisan config:cache
php artisan route:cache
```

### Code Quality
```bash
./vendor/bin/pint       # Format PHP code
php artisan test        # Run tests
```

---

## 🔗 Related Documentation

- [README.md](README.md) - Project overview
- [DEPLOYMENT.md](DEPLOYMENT.md) - Production deployment
- [CUSTOMIZATION.md](CUSTOMIZATION.md) - Branding & customization
- [TECHNICAL_SPEC.md](proposal-laravel/TECHNICAL_SPEC.md) - Technical reference
- [CHANGELOG.md](CHANGELOG.md) - Version history

---

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Follow code standards
4. Write/update tests
5. Commit changes (`git commit -m 'Add amazing feature'`)
6. Push to branch (`git push origin feature/amazing-feature`)
7. Open Pull Request

---

**Happy Coding!** 🚀
