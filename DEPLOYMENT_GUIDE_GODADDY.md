# ITTICKET Deployment Guide to elastogroup.com (GoDaddy)

## Overview
This guide helps you deploy the ITTICKET Laravel application to your GoDaddy hosting with the elastogroup.com domain.

---

## ⚠️ Prerequisites

Before starting, ensure you have:

1. **GoDaddy Account** with:
   - Active hosting package
   - elastogroup.com domain linked
   - FTP/SFTP access enabled
   - SSH access available

2. **Local Tools**:
   - Git (https://git-scm.com/download/win)
   - PHP 8.2+ 
   - Composer (https://getcomposer.org/)
   - Node.js 18+ (https://nodejs.org/)
   - FTP Client: FileZilla (https://filezilla-project.org/) or Cyberduck (https://cyberduck.io/)

3. **Credentials Ready**:
   - GoDaddy hosting username/password
   - FTP host, username, password
   - SSH credentials
   - Database name (you'll create this)

---

## 📋 Step 1: Prepare Your Local Application

### 1.1 Verify Project Files

Open PowerShell in the ITTICKET directory:

```powershell
cd "d:\xampp\htdocs\ITTICKET - Copy"

# Run pre-deployment checker
.\deploy.ps1
```

### 1.2 Build Production Assets

```powershell
# Install dependencies
composer install --no-dev --optimize-autoloader

# Install frontend deps
npm ci

# Build assets for production
npm run build
```

### 1.3 Generate GitHub Repository (Optional but Recommended)

```powershell
# Initialize git
git init
git add .
git commit -m "ITTICKET initial release"

# Change to your GitHub username/email
git config user.email "your@email.com"
git config user.name "Your Name"

# Create repository on GitHub.com first, then:
git remote add origin https://github.com/YOUR_USERNAME/itticket.git
git branch -M main
git push -u origin main
```

---

## 🌐 Step 2: Set Up GoDaddy Hosting

### 2.1 Create MySQL Database

1. Log in to **GoDaddy Hosting Control Panel**
2. Navigate to **Databases** section
3. Click **Create Database**
4. Enter details:
   - Database Name: `itticket_db`
   - Database User: `itticket_user`
   - Password: (create strong password, save it)
5. Click **Create**
6. **Note the following:**
   - Database host (usually `localhost` or something like `mysql.hostgator.com`)
   - Database name: `itticket_db`
   - Username: `itticket_user`
   - Password: (your password)

### 2.2 Check PHP Version

1. Go to **Hosting > Tools > PHP Configuration**
2. Ensure you're running **PHP 8.2+** (required for Laravel 11)
3. Recommended extensions:
   - BCMath
   - Ctype
   - JSON
   - mbstring
   - OpenSSL
   - PDO
   - PDO_MySQL
   - Tokenizer

### 2.3 Enable SSH Access

1. Go to **Hosting > Advanced > SSH Access**
2. Click **Enable SSH** (if not already enabled)
3. Generate SSH key or use password authentication
4. Note your SSH hostname and username

---

## 📁 Step 3: Upload Files to GoDaddy

### Option A: Using FileZilla (Recommended)

1. **Download & Install** FileZilla: https://filezilla-project.org/

2. **Connect to GoDaddy:**
   - Host: `ftp.elastogroup.com` (or your GoDaddy FTP host)
   - Username: Your GoDaddy hosting username
   - Password: Your GoDaddy hosting password
   - Port: 21 (or 22 for SFTP)
   - Click **Quick Connect**

3. **Upload Files:**
   - Navigate to `public_html` folder on the server
   - Drag & drop your project files (all except `node_modules/` and `.git/`)
   - Standard upload: ~5-15 minutes depending on file size

### Option B: Using Command Line (Faster if you know FTP)

```powershell
# Using FTP command (requires ftp.exe)
ftp -s:ftp_commands.txt
```

### Important: Update .env File on Server

**DO NOT upload .env from local!** Instead:

1. Via FileZilla, create a new file in `public_html/`:
   - Right-click → Create new file → name it `.env`
   
2. Edit `.env` with the following (use your GoDaddy credentials):

```env
APP_NAME=ITTicket
APP_ENV=production
APP_KEY=base64:XFc9SZiH5RIvaZUmEL4Umw4+D9vvBODEGGuu754mYoQ=
APP_DEBUG=false
APP_URL=https://elastogroup.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=itticket_db
DB_USERNAME=itticket_user
DB_PASSWORD=your_password_here

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_USERNAME=support@elasto.lk
MAIL_PASSWORD=Tic@2026ST1991#1A
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=support@elasto.lk
MAIL_FROM_NAME="IT Support"

IMAP_HOST=outlook.office365.com
IMAP_PORT=993
IMAP_ENCRYPTION=ssl
IMAP_USERNAME=support@elasto.lk
IMAP_PASSWORD=Tic@2026ST1991#1A
```

---

## 🚀 Step 4: Deploy via SSH (Final Setup)

### 4.1 Connect via SSH

```bash
# Using PuTTY or OpenSSH (Windows 10+)
ssh username@elastogroup.com

# Or on Windows with WSL2
ssh username@your-godaddy-host
```

### 4.2 Run Deployment Script

```bash
cd ~/public_html

# Make deploy script executable
chmod +x deploy.sh

# Run deployment
bash deploy.sh
```

This script will:
- ✅ Install composer dependencies
- ✅ Install npm dependencies  
- ✅ Build frontend assets
- ✅ Run database migrations
- ✅ Clear all caches
- ✅ Optimize application

### 4.3 Create Initial Admin User

```bash
php artisan tinker

# Inside tinker shell:
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@elastogroup.com';
$user->password = Hash\::make('YourSecurePassword123');
$user->role = 'admin';
$user->save();

exit
```

---

## ✅ Step 5: Verify Deployment

1. **Visit your domain:**
   - https://elastogroup.com

2. **Test login:**
   - Email: admin@elastogroup.com
   - Password: YourSecurePassword123

3. **Check logs for errors:**
   ```bash
   ssh username@elastogroup.com
   tail -f ~/public_html/storage/logs/laravel.log
   ```

4. **Test email functionality:**
   - Go to "Create Ticket"
   - Submit a ticket
   - Check if confirmation email is sent to support@elasto.lk

---

## 🔧 Troubleshooting

### Issue: "502 Bad Gateway" Error

**Solution:**
```bash
ssh username@elastogroup.com
cd public_html
chmod -R 755 bootstrap/cache
chmod -R 755 storage
php artisan cache:clear
```

### Issue: "No such file or directory" - .env

**Solution:** Manually create .env file in `public_html/` via FileZilla, copy contents from `.env.production`

### Issue: Database Connection Error

**Solution:**
- Verify DB credentials in `.env` match GoDaddy database
- Check if DB_HOST is correct (usually `localhost` for GoDaddy)
- Test connection:
  ```bash
  php -r "mysqli_connect('localhost', 'db_user', 'password', 'itticket_db')" or die("Connection failed");
  ```

### Issue: "The public/storage directory does not exist"

**Solution:**
```bash
cd public_html
php artisan storage:link
chmod -R 775 storage
```

---

## 📊 Performance Optimization

After deployment, run:

```bash
ssh username@elastogroup.com
cd public_html

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader
```

---

## 🔒 Security Checklist

- [ ] Change admin password to something strong
- [ ] Update `.env` file with production passwords
- [ ] Remove `.env` from Git repository (add to `.gitignore`)
- [ ] Enable HTTPS/SSL (GoDaddy usually includes free SSL)
- [ ] Update email credentials in `.env` if needed
- [ ] Set `APP_DEBUG=false` in production
- [ ] Regularly backup database and files

---

## 📞 Support Resources

- **Laravel Docs:** https://laravel.com/docs
- **GoDaddy Support:** https://support.godaddy.com/
- **Composer Help:** https://getcomposer.org/
- **GitHub Docs:** https://docs.github.com/

---

**Deployment Date:** March 17, 2026
**Domain:** elastogroup.com
**Application:** ITTICKET v1.0
