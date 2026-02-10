# Security Fixes & Recommendations

Berikut adalah rincian perbaikan dan rekomendasi konfigurasi server untuk menangani temuan kerentanan keamanan.

## 1. HTTP Parameter Pollution (Medium) - FIXED
**Status:** Teratasi di Aplikasi (Middleware)
**File Terkait:** `app/Http/Middleware/CheckForDuplicateParameters.php`, `app/Http/Kernel.php`

Telah ditambahkan middleware `CheckForDuplicateParameters` yang akan menolak permintaan (request) jika terdeteksi adanya parameter duplikat pada URL (Query String). Jika pengguna mengirimkan `?search=A&search=B`, aplikasi akan mengembalikan error `400 Bad Request`.

## 2. Insecure Cookie Attributes (Low) - FIXED
**Status:** Teratasi di Konfigurasi Aplikasi
**File Terkait:** `config/session.php`

Konfigurasi session telah diperbarui untuk memaksa penggunaan atribut `Secure` pada cookie.
- `SESSION_SECURE_COOKIE` sekarang default ke `true`.
- Pastikan aplikasi diakses melalui HTTPS agar cookie dapat dikirimkan oleh browser.
- `SameSite` sudah dikonfigurasi ke `lax` (standar aman).

## 3. Host Header Injection (Medium)
**Status:** False Positive (Aman), namun direkomendasikan konfigurasi server tambahan.
**Rekomendasi Konfigurasi Web Server (Nginx):**

Tambahkan konfigurasi berikut pada block `server` untuk memastikan Host header valid:

```nginx
server {
    listen 80;
    server_name kka-digisign.telkomuniversity.ac.id;

    # Hapus header X-Forwarded-Host dari client yang tidak terpercaya
    proxy_set_header X-Forwarded-Host $host;
    
    # Validasi Server Name
    if ($host !~* ^(kka-digisign\.telkomuniversity\.ac\.id)$ ) {
        return 444; # Drop connection
    }
    
    ...
}
```

## 4. SSL/TLS Forward Secrecy Cipher Suites Not Supported (Medium)
**Status:** Membutuhkan Konfigurasi Web Server
**Deskripsi:** Server saat ini mendukung cipher suite RSA yang tidak mendukung *Forward Secrecy*.

**Rekomendasi Konfigurasi Nginx (ssl.conf):**
Gunakan konfigurasi *Modern* atau *Intermediate* dari Mozilla SSL Configuration Generator.

Contoh konfigurasi Cipher Suite yang kuat (hanya mendukung TLS 1.2 dan 1.3 dengan ECDHE):

```nginx
ssl_protocols TLSv1.2 TLSv1.3;
ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384;
ssl_prefer_server_ciphers off;
```

**Rekomendasi Konfigurasi Apache:**
```apache
SSLbuProtocol all -SSLv3 -TLSv1 -TLSv1.1
SSLCipherSuite ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384
SSLHonorCipherOrder off
```

Pastikan untuk merestart web server (Nginx/Apache) setelah melakukan perubahan konfigurasi ini.
