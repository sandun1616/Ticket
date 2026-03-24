# ITTICKET → elastogroup.com DEPLOYMENT CHECKLIST

**Start Date:** March 17, 2026  
**Target Domain:** https://elastogroup.com  
**Status:** Ready for Deployment

---

## ✅ PRE-DEPLOYMENT CHECKLIST

### Local Machine Setup
- [ ] PHP 8.2+ installed locally
- [ ] Composer installed
- [ ] Node.js 18+ installed
- [ ] Git installed
- [ ] FileZilla or Cyberduck downloaded

### Code Preparation
- [ ] All local code changes committed
- [ ] Run `composer install --no-dev`
- [ ] Run `npm ci`
- [ ] Run `npm run build` (assets compiled)
- [ ] `.env.production` file created and configured
- [ ] Recent backup of local project

### GitHub Setup (Optional)
- [ ] GitHub account created
- [ ] Repository created on GitHub
- [ ] Local .git initialized: `git init`
- [ ] Files added: `git add .`
- [ ] Initial commit: `git commit -m "Initial ITTICKET commit"`
- [ ] Remote added: `git remote add origin ...`
- [ ] Pushed to main: `git push -u origin main`

---

## 📋 GODADDY SETUP CHECKLIST

### Hosting Account
- [ ] GoDaddy account active and accessible
- [ ] elastogroup.com domain linked to hosting account
- [ ] Hosting plan includes MySQL database
- [ ] cPanel access available at https://myh.godaddy.com

### Create MySQL Database
- [ ] MySQL Database created: **itticket_db**
- [ ] Database user created: **itticket_user**
- [ ] Password saved securely: **_______________**
- [ ] Database host noted: **_______________**
- [ ] User has full privileges on database

### Server Configuration
- [ ] PHP Version checked: **8.2+** ✓
- [ ] Required PHP Extensions enabled:
  - [ ] BCMath
  - [ ] Ctype
  - [ ] JSON
  - [ ] mbstring
  - [ ] OpenSSL
  - [ ] PDO
  - [ ] PDO_MySQL
  - [ ] Tokenizer

### FTP/SFTP Credentials
- [ ] FTP Host: **ftp.elastogroup.com**
- [ ] FTP Username: **_______________**
- [ ] FTP Password: **_______________**
- [ ] SFTP Port (usually 22 if available): **_______________**

### SSH Access
- [ ] SSH Enabled in cPanel
- [ ] SSH Host: **elastogroup.com**
- [ ] SSH Username: **_______________**
- [ ] SSH Key/Password: **_______________**

---

## 📁 FILE UPLOAD CHECKLIST

### Using FileZilla
- [ ] FileZilla installed and opened
- [ ] Connected to ftp.elastogroup.com
- [ ] Navigated to public_html folder
- [ ] All project files uploaded (except node_modules, .git, vendor)
- [ ] Upload completed without errors

### Server File Structure
After upload, `public_html/` should contain:
- [ ] app/
- [ ] bootstrap/
- [ ] config/
- [ ] database/
- [ ] public/
- [ ] resources/
- [ ] routes/
- [ ] storage/
- [ ] artisan
- [ ] composer.json
- [ ] composer.lock
- [ ] package.json
- [ ] .env (created manually on server)

### Environment Configuration
- [ ] `.env` file created on server via FileZilla
- [ ] Database credentials entered correctly:
  - [ ] DB_HOST: localhost (or GoDaddy host)
  - [ ] DB_DATABASE: itticket_db
  - [ ] DB_USERNAME: itticket_user
  - [ ] DB_PASSWORD: _______________
- [ ] APP_URL set to: https://elastogroup.com
- [ ] APP_DEBUG set to: false
- [ ] Mail credentials configured
- [ ] IMAP credentials configured

---

## 🚀 SSH DEPLOYMENT CHECKLIST

### Initial SSH Connection
- [ ] SSH client opened (PuTTY, WSL, or terminal)
- [ ] Connected to elastogroup.com
- [ ] Username and password entered successfully
- [ ] Prompt shows: `[username@elastogroup.com ~]$`

### Navigate and Prepare
- [ ] Navigated to public_html: `cd ~/public_html`
- [ ] Listed files to verify upload: `ls -la`
- [ ] Confirmed .env file exists: `ls -la | grep .env`
- [ ] Made deploy script executable: `chmod +x deploy.sh`

### Run Deployment Script
- [ ] Executed deployment: `bash deploy.sh`
- [ ] Composer install completed ✓
- [ ] NPM install completed ✓
- [ ] Frontend assets built ✓
- [ ] Database migrations ran ✓
- [ ] Artisan clear-cache completed ✓
- [ ] Permissions set correctly ✓

### Manual Setup (If Needed)
- [ ] Database schema verified: `mysql -u itticket_user -p itticket_db`
- [ ] Migrations table exists: `SHOW TABLES;`
- [ ] Initial admin user created via tinker (or migrations)

---

## ✅ POST-DEPLOYMENT VERIFICATION

### Website Access
- [ ] Website loads at https://elastogroup.com
- [ ] No 502 Bad Gateway errors
- [ ] No white screen of death
- [ ] CSS and images load correctly
- [ ] All pages are responsive

### Authentication
- [ ] Login page accessible at /login
- [ ] Can login with admin credentials
- [ ] Dashboard displays correctly
- [ ] User logout works
- [ ] Session handling works

### Core Features
- [ ] Create new ticket page loads
- [ ] Can submit a new ticket
- [ ] Ticket list displays all tickets
- [ ] Can view individual ticket details
- [ ] Can update ticket status

### Email Testing
- [ ] Submit test ticket
- [ ] Confirmation email sent to support@elasto.lk
- [ ] Email contains ticket number
- [ ] Email formatting looks correct

### Performance
- [ ] Page load time is reasonable (<2 seconds)
- [ ] No JavaScript console errors
- [ ] No PHP warnings in storage/logs/laravel.log
- [ ] Storage symlink working (check public/storage)

### Security
- [ ] APP_DEBUG is false (no debug info shown)
- [ ] .env file is not accessible via web
- [ ] .git directory not web-accessible
- [ ] Sensitive files not exposed
- [ ] HTTPS working and redirecting from HTTP

---

## 🔍 FINAL CHECKS

- [ ] Production database has correct data
- [ ] Backup of production database created
- [ ] Backup of files created
- [ ] Mail server configured and tested
- [ ] IMAP/email fetching configured
- [ ] Cron jobs scheduled (if needed)
- [ ] Error logging configured
- [ ] No hardcoded localhost references
- [ ] All environment variables set correctly
- [ ] Team notified of live deployment

---

## 📞 TROUBLESHOOTING LOG

### Issue 1: _______________
**Error:** ___________________________  
**Solution:** ___________________________  
**Status:** ☐ Resolved / ☐ Pending

### Issue 2: _______________
**Error:** ___________________________  
**Solution:** ___________________________  
**Status:** ☐ Resolved / ☐ Pending

### Issue 3: _______________
**Error:** ___________________________  
**Solution:** ___________________________  
**Status:** ☐ Resolved / ☐ Pending

---

## 🎉 DEPLOYMENT COMPLETE!

**Live URL:** https://elastogroup.com  
**Admin Email:** _______________  
**Deployment Date:** _______________  
**Deployed By:** _______________  

### Post-Launch Tasks
- [ ] Send notification to team/users
- [ ] Monitor logs for first week
- [ ] Schedule regular backups
- [ ] Document any custom changes made
- [ ] Update internal documentation
- [ ] Monitor email delivery
- [ ] Check analytics/usage

---

**Keep this checklist for future reference and updates!**
