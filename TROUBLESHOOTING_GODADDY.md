# ITTICKET GoDaddy Deployment - Troubleshooting Guide

## Common Issues & Solutions

---

## 🔴 Issue: "502 Bad Gateway" or "500 Internal Server Error"

### Cause 1: Missing .env file
**Error in logs:** `StreamedResponse is not supported in App::environment()`
```bash
# SSH into server
ssh username@elastogroup.com
cd public_html

# Check if .env exists
ls -la | grep .env

# If missing, create it:
cat > .env << 'EOF'
APP_NAME=ITTicket
APP_ENV=production
APP_KEY=base64:XFc9SZiH5RIvaZUmEL4Umw4+D9vvBODEGGuu754mYoQ=
APP_DEBUG=false
APP_URL=https://elastogroup.com
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=itticket_db
DB_USERNAME=itticket_user
DB_PASSWORD=your_password_here
EOF
```

### Cause 2: Incorrect folder permissions
```bash
ssh username@elastogroup.com
cd public_html

# Fix permissions
chmod -R 755 bootstrap/cache
chmod -R 755 storage
chmod 644 .env

# Restart
php artisan cache:clear
```

### Cause 3: Missing dependencies
```bash
ssh username@elastogroup.com
cd public_html

# Reinstall
composer install --no-dev --optimize-autoloader

# Rebuild assets
npm ci
npm run build

# Clear caches
php artisan config:cache
php artisan route:cache
```

---

## 🔴 Issue: "Database Connection Refused"

### Check Database Host
```bash
ssh username@elastogroup.com
cd public_html

# Test connection
php -r "
\$link = mysqli_connect('localhost', 'itticket_user', 'your_password', 'itticket_db');
if(\$link) {
    echo 'Connection successful!';
    mysqli_close(\$link);
} else {
    echo 'Connection failed: ' . mysqli_connect_error();
}
"
```

### Fix Incorrect Host
If localhost doesn't work, check GoDaddy-provided host:
1. Go to GoDaddy cPanel
2. MySQL Databases section
3. Look for "Remote MySQL Hostname"
4. Update .env:
```env
DB_HOST=your-godaddy-mysql-host.com
```

### Check Credentials
```bash
# SSH to verify credentials are correct
ssh username@elastogroup.com
cat public_html/.env | grep DB_

# Compare with GoDaddy cPanel database settings
```

---

## 🔴 Issue: "No such file or directory" - Storage Symlink

**Error:** `The public/storage directory does not exist`

### Solution
```bash
ssh username@elastogroup.com
cd public_html

# Create symlink
php artisan storage:link

# If that fails, create manually
ln -s storage/app/public public/storage

# Verify
ls -la public/ | grep storage
```

---

## 🔴 Issue: "SQLSTATE[HY000]: General error: 5 Out of resources"

**Cause:** Database quota exceeded or too many connections

### Solution
1. **Optimize database:**
```bash
ssh username@elastogroup.com
cd public_html
php artisan tinker

# In tinker:
DB::select('SHOW PROCESSLIST');
DB::select('OPTIMIZE TABLE tickets');
DB::select('OPTIMIZE TABLE users');
```

2. **Clear old sessions:**
```bash
php artisan session:prune-stale-sessions
```

3. **Check database size** in GoDaddy cPanel

---

## 🔴 Issue: "Class 'DOMPdf' not found"

**Cause:** Missing DomPDF dependency for PDF export

### Solution
```bash
ssh username@elastogroup.com
cd public_html

# Install DomPDF
composer require barryvdh/laravel-dompdf

# Clear cache
php artisan config:cache
```

---

## 🔴 Issue: "Composer: command not found"

**Cause:** Composer not in PATH on GoDaddy

### Solution
```bash
ssh username@elastogroup.com

# Check if composer is installed
which composer

# If not found, install:
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Verify
composer --version
```

---

## 🔴 Issue: "npm: command not found"

**Cause:** Node.js/npm not installed on server

### Solution
```bash
# Option 1: Contact GoDaddy to install Node.js
# Open support ticket requesting npm/Node.js installation

# Option 2: Pre-build assets locally and upload
# On your local machine:
npm run build

# Upload public/build/ directory to server
# This contains pre-compiled assets
```

---

## 🔴 Issue: Emails Not Sending

### Check Mail Configuration
```bash
ssh username@elastogroup.com
cd public_html

# Test mail configuration
php artisan tinker

# In tinker:
Mail::raw('Test email body', function($msg) {
    $msg->to('test@elastogroup.com')
        ->subject('Test Email from ITTICKET');
});
```

### Verify Credentials
```bash
# Check .env has correct SMTP settings
cat .env | grep MAIL_

# Should show:
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.office365.com
# MAIL_PORT=587
# MAIL_USERNAME=support@elasto.lk
# MAIL_ENCRYPTION=tls
```

### Check Logs
```bash
# View recent errors
tail -n 50 storage/logs/laravel.log | grep -i mail
```

---

## 🔴 Issue: White Screen of Death (No Error)

### Enable Debugging (Temporarily)
```bash
ssh username@elastogroup.com
cd public_html

# Edit .env
sed -i 's/APP_DEBUG=false/APP_DEBUG=true/' .env

# Check error
curl https://elastogroup.com

# Disable debug when done
sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
```

### Check Error Logs
```bash
# View last 100 lines of errors
tail -n 100 storage/logs/laravel.log

# Search for specific error
grep "ERROR" storage/logs/laravel.log | tail -20
```

---

## 🔴 Issue: .env File Not Found After SSH Deployment

### Verify .env Exists
```bash
ssh username@elastogroup.com
ls -la public_html/.env

# If not found, create it
cd public_html
touch .env

# Add contents
cat > .env << 'EOF'
APP_NAME=ITTicket
APP_ENV=production
APP_KEY=base64:XFc9SZiH5RIvaZUmEL4Umw4+D9vvBODEGGuu754mYoQ=
APP_DEBUG=false
APP_URL=https://elastogroup.com
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=itticket_db
DB_USERNAME=itticket_user
DB_PASSWORD=your_password_here
MAIL_MAILER=smtp
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_USERNAME=support@elasto.lk
MAIL_PASSWORD=Tic@2026ST1991#1A
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=support@elasto.lk
MAIL_FROM_NAME="IT Support"
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
EOF

# Set permissions
chmod 644 .env
```

---

## 🔴 Issue: Migration Failed - Table Exists

**Error:** `SQLSTATE[42S01]: Table already exists`

### Solution
```bash
ssh username@elastogroup.com
cd public_html

# Check migration status
php artisan migrate:status

# If migrations show as not run but table exists:
php artisan migrate --force

# Or rollback if needed:
php artisan migrate:rollback --force
php artisan migrate --force
```

---

## 🔴 Issue: Headers Already Sent Error

**Cause:** Whitespace or BOM before `<?php` tag

### Solution
```bash
# Check specific files
ssh username@elastogroup.com
cd public_html

# Find files with BOM or extra whitespace
find . -name "*.php" -exec grep -l "^[[:space:]]*$" {} \;

# Fix artisan file
php -d error_reporting=0 artisan config:cache

# Or manually check:
head -c5 artisan | od -c
# Should show: < ? p h p
```

---

## 📊 Performance & Monitoring Commands

### Check Server Resources
```bash
ssh username@elastogroup.com

# Disk usage
du -sh ~/public_html

# Database size
mysql -u itticket_user -p itticket_db -e "SELECT table_name, round(((data_length + index_length) / 1024 / 1024), 2) 'MB' FROM information_schema.TABLES WHERE table_schema = 'itticket_db';"

# Current processes
ps aux | grep php
```

### View Real-time Logs
```bash
ssh username@elastogroup.com
cd public_html

# Follow logs in real-time
tail -f storage/logs/laravel.log

# Filter for errors only
tail -f storage/logs/laravel.log | grep ERROR
```

---

## 🆘 When All Else Fails

### Emergency Reset
```bash
ssh username@elastogroup.com
cd public_html

# Backup current state
cp -r . ~/backup_$(date +%Y%m%d)

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Regenerate app key if needed
php artisan key:generate

# Reinstall everything
composer install --no-dev
npm ci
npm run build

# Run migrations
php artisan migrate --force

# Final cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Contact Support
If nothing works:
1. **GoDaddy Support:** https://support.godaddy.com/
2. **Laravel Support:** https://laracasts.com/ or StackOverflow
3. **Check error logs** for exact error messages before contacting support

---

## 📝 Logging Best Practices

### Configure Detailed Logging
```bash
# In .env:
LOG_CHANNEL=stack
LOG_LEVEL=debug

# In config/logging.php, ensure:
'daily' => [
    'driver' => 'daily',
    'path' => storage_path('logs/laravel.log'),
    'level' => 'debug',
    'days' => 14, // Keep 14 days of logs
]
```

### Monitor Logs Regularly
```bash
# Weekly log check
ssh username@elastogroup.com
cd public_html
grep -c ERROR storage/logs/laravel.log
grep -c WARNING storage/logs/laravel.log
```

---

**Last Updated:** March 17, 2026  
**Version:** 1.0  
**For:** ITTICKET v1.0 on elastogroup.com
