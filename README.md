# Curhatin

Curhatin adalah platform digital berbasis web dan mobile yang dirancang sebagai ruang aman bagi pengguna untuk berbagi cerita, mengekspresikan perasaan, serta mendapatkan dukungan melalui konsultasi dengan psikolog.

## Teknologi yang Digunakan

### Backend
- Laravel (PHP)
- REST API
- MySQL Database

### Frontend Web
- React.js
- Tailwind CSS

### Mobile Application
- Flutter
- Dart

### Integrasi
- Google OAuth Login
- Midtrans Payment Gateway

---

## Fitur Utama

### Pengguna
- Registrasi dan Login
- Login dengan Google
- Menulis Curhatan
- Posting Anonim
- Daftar Psikolog
- Rekomendasi Psikolog
- Chat Konsultasi
- Riwayat Curhatan

### Psikolog
- Dashboard Psikolog
- Melihat Curhatan Pengguna
- Menerima Konsultasi
- Chat dengan Pengguna
- Riwayat Konsultasi

### Admin
- Manajemen User
- Manajemen Psikolog
- Moderasi Curhatan
- Monitoring Sistem

---

## Struktur Project

```
Curhatin/
│
├── backend/      # Laravel API
├── frontend/     # React Web
└── mobile/       # Flutter Mobile
```

---

## Cara Menjalankan Project

### Backend Laravel

```bash
cd backend

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan serve
```

---

### Frontend React

```bash
cd frontend

npm install

npm run dev
```

---

### Mobile Flutter

```bash
cd mobile

flutter pub get

flutter run
```

---

## Team Developer

Kelompok ABP
- Project manager
- Backend Developer
- Frontend Developer
- Mobile Developer
- UI/UX Designer
- QA Testing

---

## License

Project dibuat untuk kebutuhan akademik dan pengembangan pembelajaran.
