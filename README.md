# SLiMS 9 Bulian - Setup & Deployment Guide

## Deskripsi
SLiMS (Senayan Library Management System) versi 9 Codename Bulian adalah sistem manajemen perpustakaan open-source untuk mengelola koleksi buku, jurnal, dokumen digital, sirkulasi, anggota, dan laporan. Sistem ini menggunakan PHP, MySQL/MariaDB, dan framework Symfony untuk komponen tertentu.

Proyek ini telah dikonfigurasi untuk development lokal (Laragon) dan siap deploy ke server Linux production.

## Requirements Sistem
- **PHP**: >= 8.1 dengan ekstensi: `gd`, `gettext`, `mbstring`, `pdo`, `pdo_mysql`, `json`, `xml`, `curl`, `zip`, `bcmath`, `intl`
- **Database**: MySQL 5.7+ atau MariaDB 10.3+
- **Web Server**: Apache 2.4+ atau Nginx 1.18+
- **OS**: Linux (Ubuntu/Debian/CentOS), Windows (untuk development)
- **Tools**: Composer, Git

## Yang Perlu Diinstall (Detail)

Berikut adalah daftar lengkap package/software yang perlu diinstall, dengan penjelasan fungsi dan alasan masing-masing. Fokus untuk Ubuntu/Debian (sesuaikan untuk CentOS dengan `yum`).

### 1. Sistem Operasi & Tools Dasar
```bash
sudo apt update && sudo apt upgrade  # Update sistem untuk keamanan
sudo apt install curl wget unzip git nano vim ufw
```
- **curl/wget**: Untuk download file dari internet (misal Composer installer).
- **unzip**: Ekstrak file ZIP (untuk download manual jika perlu).
- **git**: Clone kode dari repository GitHub.
- **nano/vim**: Editor teks untuk edit config file.
- **ufw**: Firewall sederhana untuk secure server.

### 2. Web Server
- **Apache** (rekomendasi untuk kemudahan):
  ```bash
  sudo apt install apache2
  sudo systemctl start apache2 && sudo systemctl enable apache2
  ```
  - **Fungsi**: Serve file PHP dan static content. Mudah konfigurasi dengan .htaccess untuk rewrite URL.
  - **Alasan**: SLiMS menggunakan mod_rewrite untuk URL friendly. Apache lebih straightforward untuk setup virtual host.

- **Nginx** (alternatif untuk performance tinggi):
  ```bash
  sudo apt install nginx
  sudo systemctl start nginx && sudo systemctl enable nginx
  ```
  - **Fungsi**: Reverse proxy dan web server cepat, hemat resource.
  - **Alasan**: Lebih efisien untuk traffic tinggi, tapi konfigurasi lebih kompleks untuk PHP.

### 3. Database
- **MySQL/MariaDB**:
  ```bash
  sudo apt install mysql-server  # atau mariadb-server
  sudo systemctl start mysql && sudo systemctl enable mysql
  sudo mysql_secure_installation  # Harden security
  ```
  - **Fungsi**: Store data perpustakaan (buku, anggota, transaksi).
  - **Alasan**: SLiMS membutuhkan MySQL 5.7+ untuk fitur JSON dan performance. MariaDB kompatibel sebagai drop-in replacement.

### 4. PHP & Ekstensi
```bash
sudo apt install php php-cli php-mysql php-gd php-mbstring php-gettext php-xml php-curl php-zip php-bcmath php-intl
```
- **php/php-cli**: Core PHP untuk run aplikasi web dan command line.
- **php-mysql**: Koneksi ke database MySQL.
- **php-gd**: Manipulasi gambar (thumbnail buku, barcode).
- **php-mbstring**: Handle string multibyte (untuk bahasa non-ASCII seperti Indonesia).
- **php-gettext**: Internationalization (i18n) untuk multi-bahasa.
- **php-xml**: Parse/proses XML (untuk import/export data MARC).
- **php-curl**: HTTP requests (untuk API eksternal, seperti cover image dari DuckDuckGo).
- **php-zip**: Kompres/dekompres file (backup, upload ZIP).
- **php-bcmath**: Kalkulasi matematika presisi (untuk denda, statistik).
- **php-intl**: Internationalization tambahan (format tanggal, currency).

**Alasan**: SLiMS membutuhkan PHP >=8.1. Ekstensi ini essential untuk fitur seperti upload gambar, multi-bahasa, dan koneksi DB.

### 5. Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```
- **Fungsi**: Dependency manager untuk PHP, install/update library otomatis.
- **Alasan**: SLiMS menggunakan banyak library third-party. Composer memastikan versi kompatibel dan autoload.

### 6. Dependencies PHP (via Composer)
```bash
composer install --no-dev --optimize-autoloader
```
Package utama yang diinstall (dari `composer.json`):
- **symfony/finder** (v6.4): Cari file/folder (untuk config loader).
- **symfony/console** (v6.4): CLI commands (untuk artisan-like tools).
- **symfony/var-dumper** (v6.4): Debug output (dump variables).
- **symfony/contracts** (v3.6): Interface standar untuk komponen Symfony.
- **elasticsearch/elasticsearch** (~6.0): Integrasi dengan Elasticsearch untuk search advanced.
- **volnix/csrf** (~1.0): Protection Cross-Site Request Forgery.
- **monolog/monolog** (^2.0): Logging (error, activity logs).
- **ramsey/uuid** (^4.0): Generate unique ID (untuk record).
- **ifsnop/mysqldump-php** (^2.9): Backup database otomatis.
- **Lainnya**: PSR interfaces, HTML Purifier, dll.

**Alasan**: Package ini menyediakan fungsi yang tidak ada di PHP core, seperti search engine, security, dan utilities. `--no-dev` untuk production (skip dev tools), `--optimize-autoloader` untuk speed.

### 7. Tools Tambahan (Opsional)
- **SSL Certificate**:
  ```bash
  sudo apt install certbot python3-certbot-apache
  ```
  - **Fungsi**: HTTPS gratis via Let's Encrypt.
  - **Alasan**: Secure connection, required untuk modern web.
- **Backup & Monitoring**:
  ```bash
  sudo apt install rsync cron htop fail2ban
  ```
  - **rsync/cron**: Backup scheduled.
  - **htop**: Monitor resource.
  - **fail2ban**: Prevent brute force.

### Estimasi Ukuran & Waktu
- **Total Install**: ~500MB disk space.
- **Waktu**: 30-60 menit (tergantung koneksi internet untuk download).
- **Post-Install**: Verifikasi dengan `php -m` (cek ekstensi) dan `composer show` (cek package).

## Konfigurasi

### 1. Clone Kode
```bash
cd /var/www/html
sudo git clone https://github.com/slims/slims9_bulian.git slims
cd slims
```

### 2. Setup Database
- Buat database:
  ```sql
  CREATE DATABASE slims_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  CREATE USER 'slims_user'@'localhost' IDENTIFIED BY 'strong_password';
  GRANT ALL PRIVILEGES ON slims_db.* TO 'slims_user'@'localhost';
  FLUSH PRIVILEGES;
  ```
- Copy config:
  ```bash
  cp config/database.sample.php config/database.php
  cp config/env.sample.php config/env.php
  ```
- Edit `config/database.php`:
  ```php
  'SLiMS' => [
      'host' => 'localhost',
      'database' => 'slims_db',
      'username' => 'slims_user',
      'password' => 'strong_password',
      'port' => 3306,
      'options' => ['storage_engine' => 'InnoDB']
  ]
  ```

### 3. Permission & Ownership
```bash
sudo chown -R www-data:www-data /var/www/html/slims  # Ubuntu
# atau sudo chown -R apache:apache /var/www/html/slims  # CentOS
sudo chmod -R 755 /var/www/html/slims
sudo chmod -R 777 files/ images/ repository/
```

### 4. Web Server Config
- **Apache** (`/etc/apache2/sites-available/slims.conf`):
  ```
  <VirtualHost *:80>
      ServerName yourdomain.com
      DocumentRoot /var/www/html/slims
      <Directory /var/www/html/slims>
          AllowOverride All
          Require all granted
      </Directory>
  </VirtualHost>
  ```
  ```bash
  sudo a2ensite slims.conf
  sudo a2enmod rewrite
  sudo systemctl restart apache2
  ```
- **Nginx** (`/etc/nginx/sites-available/slims`):
  ```
  server {
      listen 80;
      server_name yourdomain.com;
      root /var/www/html/slims;
      index index.php;
      location / {
          try_files $uri $uri/ /index.php?$query_string;
      }
      location ~ \.php$ {
          include snippets/fastcgi-php.conf;
          fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
      }
  }
  ```
  ```bash
  sudo ln -s /etc/nginx/sites-available/slims /etc/nginx/sites-enabled/
  sudo systemctl restart nginx
  ```

### 5. Firewall
```bash
sudo ufw allow 80/tcp  # HTTP
sudo ufw allow 443/tcp # HTTPS (jika SSL)
sudo ufw enable
```

## Implementasi & Running

### 1. Install SLiMS
- Akses browser: `http://yourdomain.com/install/`
- Ikuti wizard:
  - Masukkan detail database.
  - Set admin username/password.
  - Konfigurasi perpustakaan.

### 2. Akses Aplikasi
- OPAC: `http://yourdomain.com/`
- Admin: `http://yourdomain.com/admin/`

### 3. Cron Jobs (Opsional, untuk maintenance)
- Edit crontab: `sudo crontab -e`
- Tambah:
  ```
  0 2 * * * /usr/bin/php /var/www/html/slims/admin/modules/system/biblio_indexer.inc.php  # Indexer harian
  ```

### 4. Backup
- Database: `mysqldump -u slims_user -p slims_db > backup.sql`
- Files: `rsync -av /var/www/html/slims/files/ /backup/files/`

## Troubleshooting

### Error Umum
- **Extension Missing**: Install ekstensi PHP tambahan jika error "Class not found".
- **Permission Denied**: Set ownership ke `www-data` atau `apache`.
- **SQL Mode Error**: Disable `ONLY_FULL_GROUP_BY` di MySQL: `SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));`
- **Composer Error**: Jalankan `composer update` jika dependency bermasalah.

### Log Files
- Apache: `/var/log/apache2/error.log`
- Nginx: `/var/log/nginx/error.log`
- PHP: `/var/log/php8.1-fpm.log`
- SLiMS: `files/chat/` (untuk debug)

### Performance Tuning
- Enable OPcache: `sudo phpenmod opcache`
- MySQL: Edit `my.cnf` untuk `innodb_buffer_pool_size = 512M`

## Lisensi
GPL v3. Lihat `LICENSE` untuk detail.

## Kontribusi
Fork repo, buat branch, dan submit PR. Issues di GitHub.

## Support
- Dokumentasi: [SLiMS Web](https://slims.web.id)
- Forum: [SLiMS Community](https://forum.slims.web.id)