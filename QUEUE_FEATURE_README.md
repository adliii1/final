# Fitur Antrian Sistem Booking Dokter

## Deskripsi Fitur

Sistem antrian otomatis telah ditambahkan ke dalam aplikasi booking dokter. Ketika user melakukan booking jadwal, mereka akan otomatis masuk ke dalam antrian dengan nomor urut yang diberikan secara berurutan.

## Fitur Yang Ditambahkan

### 1. Model dan Database

-   **Model Queue**: Mengelola data antrian dengan atribut:

    -   `queue_number`: Nomor antrian berurut
    -   `queue_date`: Tanggal antrian
    -   `status`: Status antrian (waiting, in_progress, completed, cancelled)
    -   `estimated_time`: Estimasi waktu dipanggil
    -   `actual_start_time`: Waktu mulai konsultasi
    -   `actual_end_time`: Waktu selesai konsultasi

-   **Migration**: Tabel `queues` dengan relasi ke `bookings`
-   **Relasi**: Queue -> Booking -> Schedule -> Doctor

### 2. Logika Antrian Otomatis

-   Ketika user melakukan booking, sistem otomatis:
    -   Membuat record booking
    -   Generate nomor antrian berdasarkan dokter dan tanggal
    -   Menghitung estimasi waktu berdasarkan durasi 15 menit per pasien
    -   Menambahkan ke antrian dengan status 'waiting'

### 3. Fitur untuk User

-   **Antrian Saya**: Melihat semua antrian user dengan status dan nomor antrian
-   **Antrian Hari Ini**: Melihat antrian real-time untuk hari ini
-   **Riwayat Booking**: Diperluas untuk menampilkan informasi antrian
-   **Form Booking**: Ditambahkan field catatan dan informasi antrian

### 4. Fitur untuk Admin

-   **Dashboard Antrian**: Statistik real-time antrian hari ini
-   **Manajemen Antrian**: Kelola antrian dengan filter berdasarkan:
    -   Tanggal
    -   Dokter
    -   Status
-   **Aksi Admin**:
    -   Mulai antrian (ubah status ke 'in_progress')
    -   Selesaikan antrian (ubah status ke 'completed')
    -   Batalkan antrian (ubah status ke 'cancelled')
    -   Lihat antrian selanjutnya

### 5. Routes Baru

#### User Routes:

-   `/user/queue` - Antrian user
-   `/user/queue/today` - Antrian hari ini

#### Admin Routes:

-   `/admin/queue` - Manajemen antrian
-   `/admin/queue/dashboard` - Dashboard antrian
-   `/admin/queue/{id}/start` - Mulai antrian
-   `/admin/queue/{id}/complete` - Selesaikan antrian
-   `/admin/queue/{id}/cancel` - Batalkan antrian
-   `/admin/queue/next` - Dapatkan antrian selanjutnya

## Cara Penggunaan

### Untuk User:

1. Buat booking melalui form booking
2. Sistem otomatis memberikan nomor antrian
3. Pantau status antrian di menu "Antrian Saya"
4. Lihat antrian real-time di "Antrian Hari Ini"

### Untuk Admin:

1. Akses dashboard antrian untuk melihat statistik
2. Kelola antrian melalui menu "Manajemen Antrian"
3. Filter antrian berdasarkan dokter, tanggal, atau status
4. Gunakan tombol aksi untuk mengubah status antrian
5. Pantau antrian yang sedang berlangsung

## Fitur Tambahan

### Auto-refresh

-   Dashboard dan halaman antrian auto-refresh setiap 30 detik
-   Memastikan informasi antrian selalu up-to-date

### Estimasi Waktu

-   Sistem menghitung estimasi waktu berdasarkan:
    -   Waktu mulai schedule dokter
    -   Nomor antrian × 15 menit per pasien
    -   Dapat disesuaikan sesuai kebutuhan

### Status Tracking

-   **Waiting**: Antrian menunggu dipanggil
-   **In Progress**: Sedang dilayani dokter
-   **Completed**: Konsultasi selesai
-   **Cancelled**: Antrian dibatalkan

### Notifikasi Visual

-   Color coding untuk status antrian
-   Badge untuk nomor antrian
-   Alert untuk informasi penting

## Instalasi dan Setup

1. Jalankan migration:

```bash
php artisan migrate
```

2. Jalankan seeder (opsional):

```bash
php artisan db:seed --class=QueueSeeder
```

3. Fitur siap digunakan!

## Teknologi yang Digunakan

-   **Laravel**: Framework PHP untuk backend
-   **Bootstrap**: CSS framework untuk UI
-   **Font Awesome**: Icon library
-   **JavaScript**: Auto-refresh dan interaktivitas
-   **MySQL**: Database untuk menyimpan data antrian

## Pengembangan Selanjutnya

Beberapa fitur yang dapat ditambahkan:

-   Notifikasi push/email untuk antrian
-   Integrasi dengan WhatsApp API
-   Reporting dan analytics
-   Mobile app untuk monitoring
-   Voice announcement system
-   QR code untuk check-in

## Kesimpulan

Sistem antrian otomatis ini meningkatkan pengalaman user dan memudahkan admin dalam mengelola antrian pasien. Dengan fitur real-time tracking dan manajemen yang lengkap, sistem ini memberikan solusi komprehensif untuk manajemen antrian di klinik atau rumah sakit.
