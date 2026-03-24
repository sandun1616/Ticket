# ITTICKET Deployment - Quick Reference Card

## Timeline: 30-45 minutes

### LOCAL PREPARATION (5 min)
```powershell
cd "d:\xampp\htdocs\ITTICKET - Copy"
composer install --no-dev
npm ci
npm run build
```

### GODADDY SETUP (10 min)
1. Create MySQL database: `itticket_db`
2. Create DB user: `itticket_user` (save password)
3. Note DB host (usually: localhost)

### FILE UPLOAD (10-15 min)
1. Download FileZilla
2. Connect via FTP to ftp.elastogroup.com
3. Upload all files to public_html/
4. Create .env file with credentials

### SSH DEPLOYMENT (5 min)
```bash
ssh your_username@elastogroup.com
cd public_html
chmod +x deploy.sh
bash deploy.sh
```

### VERIFICATION (5 min)
✅ Visit https://elastogroup.com  
✅ Login with admin credentials  
✅ Create test ticket  
✅ Check email sent

---

## 📋 GoDaddy Database Credentials (Save These!)

| Item | Value |
|------|-------|
| Database Host | __________ |
| Database Name | itticket_db |
| DB Username | itticket_user |
| DB Password | __________ |
| FTP Host | ftp.elastogroup.com |
| FTP Username | __________ |
| FTP Password | __________ |
| SSH Host | elastogroup.com |
| SSH Username | __________ |

---

## 🔗 Essential Links

- **GoDaddy cPanel:** https://myh.godaddy.com
- **Domain Check:** https://elastogroup.com
- **GitHub Repo:** https://github.com/YOUR_USERNAME/itticket
- **Laravel Docs:** https://laravel.com/docs/11.x
- **FileZilla Download:** https://filezilla-project.org/

---

## ❌ DO NOT:
- Upload node_modules/ folder
- Upload .git/ folder  
- Commit .env to GitHub
- Set APP_DEBUG=true in production
- Forget to run migrations

## ✅ DO:
- Use HTTPS/SSL
- Keep backups
- Update .env periodically
- Monitor logs regularly
- Test before pushing live changes
