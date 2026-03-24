# ITTICKET Pre-Deployment Preparation Script (Windows PowerShell)
# Run this before uploading to GoDaddy

Write-Host "=== ITTICKET Pre-Deployment Checker ===" -ForegroundColor Cyan

# Check if .env.production exists
if (!(Test-Path ".env.production")) {
    Write-Host "❌ .env.production not found!" -ForegroundColor Red
    exit 1
}

Write-Host "✅ .env.production found" -ForegroundColor Green

# Check composer.json
if (!(Test-Path "composer.json")) {
    Write-Host "❌ composer.json not found!" -ForegroundColor Red
    exit 1
}

Write-Host "✅ composer.json found" -ForegroundColor Green

# Check package.json
if (!(Test-Path "package.json")) {
    Write-Host "❌ package.json not found!" -ForegroundColor Red
    exit 1
}

Write-Host "✅ package.json found" -ForegroundColor Green

# List files to upload
Write-Host "`n📋 Files Ready for Upload:" -ForegroundColor Cyan
Write-Host @"
Essential files to upload to GoDaddy:
- app/
- bootstrap/
- config/
- database/migrations/
- public/ (entire directory including index.php)
- resources/
- routes/
- storage/ (with proper permissions)
- vendor/
- .env (UPDATE with GoDaddy credentials first!)
- artisan
- composer.json
- package.json
"@

# Summary
Write-Host "`n✅ Pre-deployment checks passed!" -ForegroundColor Green
Write-Host "`nNext steps:" -ForegroundColor Cyan
Write-Host "1. Update .env.production with GoDaddy database credentials"
Write-Host "2. Run: composer install --no-dev"
Write-Host "3. Run: npm ci"
Write-Host "4. Run: npm run build"
Write-Host "5. Upload all files to GoDaddy public_html via FTP/SFTP"
Write-Host "6. SSH into GoDaddy and run: bash deploy.sh"
