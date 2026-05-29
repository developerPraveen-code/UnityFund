# 🛡️ Security & Code Quality Setup Guide

## Critical Issues Fixed

### ✅ Security: Database Credentials
- **Before:** Hardcoded credentials in `Database.php`
- **After:** Uses environment variables from `.env` file
- **Action:** Create `.env` file from `.env.example`

### ✅ Security: Unsanitized URL Parameters  
- **Before:** `$_GET['page']` used directly in switch statement
- **After:** Whitelisted pages validation added
- **Action:** Only allowed pages are now processed

### ✅ Security: Missing Password Validation
- **Before:** No password verification against database
- **After:** Email validation added, password verification placeholder added
- **Action:** Implement actual password hashing with `password_verify()`

---

## 🚀 Local Setup

### 1. Create Environment File
```bash
cp .env.example .env
```

### 2. Edit `.env` with your database credentials
```bash
DB_HOST=localhost
DB_NAME=unityfund_db
DB_USER=root
DB_PASS=yourpassword
```

### 3. Install PHP Dependencies
```bash
composer install
```

### 4. Run Code Quality Checks Locally
```bash
# Lint PHP files
composer run lint

# Auto-fix lint issues
composer run lint-fix

# Static analysis
composer run stan

# Run tests
composer run test

# Security check
composer run security

# Run all checks
composer run qa
```

---

## ✅ What's Fixed

| Issue | Status | Location |
|-------|--------|----------|
| Hardcoded DB credentials | ✅ Fixed | `src/shared/database/Database.php` |
| Unsanitized page parameter | ✅ Fixed | `public/index.php` |
| OIDC identity validation | ✅ Fixed | `src/auth/OidcClient.php` |
| PSR-12 compliance | ⚠️ In progress | All PHP files |
| Password verification | ✅ Replaced by OIDC | Auth0/OpenID Connect |

---

## 🔐 Next Steps

### 1. Implement Password Hashing
```php
// Hash password on registration
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Verify password on login
if (password_verify($password, $hashedPasswordFromDB)) {
    // Login successful
}
```

### 2. Update Database Schema
Add password hash column:
```sql
ALTER TABLE users ADD COLUMN password_hash VARCHAR(255) NOT NULL;
```

### 3. Fix Remaining Code Style Issues
```bash
composer run lint-fix  # Auto-fixes most PSR-12 issues
```

---

## 🔄 CI/CD Validation

Your GitHub Actions pipeline will now:
- ✅ Validate all pages are whitelisted
- ✅ Check environment variables are used
- ✅ Verify PSR-12 compliance
- ✅ Run PHPStan static analysis
- ✅ Scan for security vulnerabilities

**The pipeline will fail if:**
- Hardcoded credentials are found
- Unsanitized input is used
- Code doesn't follow PSR-12
- Security vulnerabilities exist
