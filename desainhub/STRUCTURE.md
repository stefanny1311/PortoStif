# Struktur Folder — 

```

├── config/
│   ├── config.php          # konstanta global, base URL, session
│   └── database.php        # koneksi PDO MySQL
├── core/
│   ├── Router.php          # router sederhana
│   ├── Controller.php      # base controller (MVC)
│   ├── Model.php           # base model (PDO wrapper)
│   └── Auth.php            # helper autentikasi & role guard
├── controllers/
│   ├── HomeController.php
│   ├── AuthController.php
│   ├── DesignerController.php
│   ├── PortfolioController.php
│   ├── OrderController.php
│   ├── UserDashboardController.php
│   ├── DesignerDashboardController.php
│   └── AdminDashboardController.php
├── models/
│   ├── User.php
│   ├── Designer.php
│   ├── Order.php
│   ├── Portfolio.php
│   ├── Review.php
│   ├── Payment.php
│   ├── Category.php
│   ├── Message.php
│   └── Notification.php
├── helpers/
│   └── functions.php       # helper umum (format rupiah, flash message, dll)
├── views/
│   ├── layouts/            # header, footer, navbar (landing & dashboard)
│   ├── landing/            # halaman publik: home, about, services, dst
│   ├── auth/                # login, register, forgot password
│   ├── dashboard/
│   │   ├── user/
│   │   ├── designer/
│   │   └── admin/
│   └── errors/              # 404, 403, dll
├── public/
│   ├── index.php            # single entry point (front controller)
│   ├── .htaccess             # url rewrite
│   └── assets/
│       ├── css/style.css
│       ├── js/main.js
│       ├── img/
│       └── uploads/          # portfolio, brief, avatar
└── database/
    └──          # schema + seed data
```

**Pola arsitektur:** MVC sederhana, satu entry point (`public/index.php`) meneruskan
request ke Router → Controller → Model (PDO) → View. Semua akses DB lewat PDO
prepared statement untuk mencegah SQL Injection.
