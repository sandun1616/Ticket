# ITTICKET - GitHub Repository Setup Guide

## Overview
This guide helps you push ITTICKET to a GitHub repository for version control and easier deployment.

---

## 1️⃣ Create GitHub Account & Repository

### Step 1: Create GitHub Account
1. Go to https://github.com/signup
2. Choose username, email, password
3. Verify email address
4. Skip optional surveys

### Step 2: Create New Repository
1. Click **+** icon (top right) → **New repository**
2. Repository name: `itticket`
3. Description: `IT Ticket Management System`
4. Set to **Private** (recommended) or **Public**
5. **DO NOT** initialize with README/license (we have our own)
6. Click **Create repository**

### Step 3: Note Your Repository URL
After creating, you'll see:
```
https://github.com/YOUR_USERNAME/itticket.git
```
Keep this URL for the next steps.

---

## 2️⃣ Configure Git Locally

### Step 1: Install Git
Download from https://git-scm.com/download/win

### Step 2: Set Up Git Identity
```powershell
git config --global user.email "your_email@example.com"
git config --global user.name "Your Name"

# Verify
git config --global user.email
git config --global user.name
```

### Step 3: Generate SSH Key (Optional but Recommended)
```powershell
# Generate SSH key
ssh-keygen -t ed25519 -C "your_email@example.com"

# Press Enter 3 times for default location and no passphrase

# Display public key to copy
cat $HOME\.ssh\id_ed25519.pub
```

Then add to GitHub:
1. Go to https://github.com/settings/keys
2. Click **New SSH key**
3. Paste your public key
4. Save

---

## 3️⃣ Initialize Git Repository Locally

### Step 1: Navigate to Project Directory
```powershell
cd "d:\xampp\htdocs\ITTICKET - Copy"
```

### Step 2: Initialize Git
```powershell
# Initialize repository
git init

# Check status
git status
```

### Step 3: Add All Files
```powershell
# Add all files (will respect .gitignore)
git add .

# Check what will be added
git status
```

You should see:
```
On branch master

Initial commit

Changes to be committed:
- app/
- bootstrap/
- config/
... (all important files)

Untracked files not shown:
- node_modules/
- vendor/
- .env
```

### Step 4: Create Initial Commit
```powershell
git commit -m "Initial ITTICKET commit - Laravel ticketing system"

# Verify
git log
```

---

## 4️⃣ Connect to GitHub & Push

### Step 1: Add Remote Repository
```powershell
# Add GitHub as remote
git remote add origin https://github.com/YOUR_USERNAME/itticket.git

# Verify
git remote -v
```

Should show:
```
origin  https://github.com/YOUR_USERNAME/itticket.git (fetch)
origin  https://github.com/YOUR_USERNAME/itticket.git (push)
```

### Step 2: Rename Main Branch (if needed)
```powershell
# Change master to main
git branch -M main

# Verify
git branch
```

### Step 3: Push to GitHub
```powershell
# First push (set upstream)
git push -u origin main

# If prompted for credentials, enter your GitHub username and password (or token)
```

⚠️ **If asked for password:**
- Use **Personal Access Token** instead of password
- GitHub no longer accepts passwords for git operations
- Create token at: https://github.com/settings/tokens

---

## 5️⃣ Verify Repository on GitHub

1. Visit your repository: `https://github.com/YOUR_USERNAME/itticket`
2. Verify all files are there
3. Check that `.env` is NOT in the repository (should be in .gitignore)
4. Verify `.gitignore` is present and correct

---

## 6️⃣ Use GitHub for Deployment

### Option A: Manual Deployment (Simple)
```powershell
# When ready to deploy to GoDaddy:

# 1. Make changes locally
# 2. Test thoroughly
# 3. Commit changes
git add .
git commit -m "Description of changes"

# 4. Push to GitHub
git push origin main

# 5. SSH to GoDaddy server
ssh username@elastogroup.com
cd public_html

# 6. Pull latest code
git pull origin main

# 7. Run migrations/cache clear as needed
php artisan migrate --force
php artisan cache:clear
```

### Option B: Automated CI/CD with GitHub Actions (Advanced)
Create `.github/workflows/deploy.yml`:
```yaml
name: Deploy to GoDaddy

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Deploy via SSH
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.GoDaddy_HOST }}
          username: ${{ secrets.GoDaddy_USER }}
          password: ${{ secrets.GoDaddy_PASS }}
          script: |
            cd ~/public_html
            git pull origin main
            composer install --no-dev
            npm ci
            npm run build
            php artisan migrate --force
            php artisan cache:clear
```

Then add secrets at: https://github.com/YOUR_USERNAME/itticket/settings/secrets

---

## 📋 Git Commands Cheat Sheet

### Common Commands
```powershell
# Check status
git status

# View commit history
git log
git log --oneline

# See changes before committing
git diff

# Stage specific files
git add app/
git add routes/

# Commit
git commit -m "Clear message about changes"

# Push to GitHub
git push origin main

# Pull latest from GitHub
git pull origin main

# Create new branch
git checkout -b feature/new-feature

# Switch branches
git checkout main

# Merge branch
git merge feature/new-feature

# View branches
git branch -a

# Delete branch
git branch -d feature/new-feature
```

### Undo Changes
```powershell
# Undo unstaged changes
git checkout .

# Unstage files
git reset HEAD file.php

# Undo last commit (keep changes)
git reset --soft HEAD~1

# Undo last commit (lose changes)
git reset --hard HEAD~1
```

---

## 🚨 Important: Protect Sensitive Files

### Always Ensure These Are NOT in Git
```
.env (environment variables)
.env.* (all env files)
composer.lock (optional)
package-lock.json (optional)
vendor/ (dependencies)
node_modules/ (dependencies)
storage/logs/ (logs)
storage/uploads/ (user uploads)
.vscode/ (IDE settings)
.idea/ (IDE settings)
```

Check `.gitignore`:
```powershell
cat .gitignore
```

Ensure these patterns are present:
```
.env
.env.backup
.env.production
/vendor
/node_modules
/storage/logs
```

---

## 🔐 Security Best Practices

1. **Never commit .env** - Use `.env.example` instead
2. **Never push to main without testing** - Use feature branches
3. **Keep secrets in GitHub Actions secrets** - Not in code
4. **Review code before pushing** - Use `git diff`
5. **Write meaningful commit messages** - Helps with debugging

### Create .env.example
```powershell
# Copy .env to .env.example
copy .env .env.example

# Edit .env.example and remove sensitive values
# Keep the structure, just remove actual passwords

# This helps others understand what env variables are needed
```

---

## 📞 Troubleshooting

### Issue: "fatal: not a git repository"
```powershell
# You forgot to run git init
cd d:\xampp\htdocs\ITTICKET\ -\ Copy
git init
```

### Issue: "fatal: 'origin' does not appear to be a remote"
```powershell
# Add remote again
git remote add origin https://github.com/YOUR_USERNAME/itticket.git
```

### Issue: "remote rejection: permission denied"
```powershell
# Wrong credentials or no write access
# 1. Check repository is under your account
# 2. Use personal access token instead of password
# 3. Check SSH key is added if using SSH

git config credential.helper store  # Save credentials locally (careful!)
git push -u origin main  # It will prompt for username/token
```

### Issue: "Permission denied (publickey)"
```powershell
# SSH key not set up correctly
# Use HTTPS instead:
git remote set-url origin https://github.com/YOUR_USERNAME/itticket.git
```

### Issue: "Everything up-to-date"
```powershell
# No changes to push
# Make sure you committed first:
git status  # Shows files to commit
git add .
git commit -m "Your message"
git push origin main
```

---

## 🎯 Next Steps After Setup

1. ✅ Add `.env.example` file showing required variables
2. ✅ Create comprehensive README.md
3. ✅ Add LICENSE file
4. ✅ Create CONTRIBUTING.md for others
5. ✅ Set up GitHub branch protection rules
6. ✅ Configure automatic deployments (if advanced)
7. ✅ Add shields/badges to README

---

## 🔗 Useful Links

- **GitHub Docs:** https://docs.github.com/
- **Git Documentation:** https://git-scm.com/doc
- **GitHub SSH Setup:** https://docs.github.com/en/authentication/connecting-to-github-with-ssh
- **GitHub Personal Tokens:** https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token

---

**Your Repository URL:**
```
https://github.com/YOUR_USERNAME/itticket.git
```

Keep this handy for all git operations!
