#  Marketplace Jasa Desain Logo & Poster

## Status Progres (bertahap sesuai urutan yang diminta)

- [x] 1. Struktur Folder (MVC sederhana)
- [x] 2. Database SQL (`database/`)
- [x] 3. Landing Page (Home lengkap: Hero, Statistik, Masalah, Solusi, Cara Kerja,
      Portfolio, Designer, Pricing, Testimoni, FAQ, CTA, Footer)
- [x] 4. CSS Premium (`public/assets/css/style.css`) — dark mode & responsive termasuk
- [x] 5. JavaScript (`public/assets/js/main.js`) — AOS, Swiper, filter, dark mode toggle
- [ ] 6. Login
- [ ] 7. Register
- [ ] 8. Dashboard User
- [ ] 9. Dashboard Designer
- [ ] 10. Dashboard Admin
- [ ] 11. CRUD Portfolio
- [ ] 12. CRUD Order
- [ ] 13. Chat
- [ ] 14. Invoice
- [ ] 15. Responsive lanjutan (halaman dashboard)
- [ ] 16. Optimasi UI

Bagian yang belum tercentang akan dilanjutkan di pesan berikutnya (chat ini punya batas
panjang output per pesan, jadi project dikerjakan bertahap per fase seperti yang diminta).

## Cara Install (XAMPP / Laragon)

1. Copy folder `` ke `htdocs/` (XAMPP) atau `www/` (Laragon).
2. Buat database lewat phpMyAdmin, lalu import `database/`.
3. Sesuaikan kredensial di `config/database.php` jika perlu (default: user `root`, password kosong).
4. Sesuaikan `BASE_URL` di `config/config.php` sesuai path project kamu.
5. Aktifkan `mod_rewrite` (untuk `.htaccess`) di Apache.
6. Buka `http://localhost/public/` di browser.

**Akun admin default:**
- Email: `admin@desainhub.id`
- Password: `admin123`
(⚠️ segera ganti password setelah login pertama kali)

## Struktur MVC

Lihat detail lengkap di `STRUCTURE.md`.

## Tumpukan Teknologi

- Frontend: HTML5, CSS3, Bootstrap 5, JavaScript ES6, Bootstrap Icons, AOS Animation, SwiperJS
- Backend: PHP Native (MVC sederhana), MySQL, PDO (prepared statements — aman dari SQL Injection)

## Palet Warna

| Nama       | Hex       |
|------------|-----------|
| Primary    | `#6C63FF` |
| Secondary  | `#8B5CF6` |
| Background | `#F8FAFC` |
| White      | `#FFFFFF` |
| Dark       | `#111827` |
