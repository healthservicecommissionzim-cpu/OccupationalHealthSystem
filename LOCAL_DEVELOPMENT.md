# Local Development Setup

## Quick Start

### 1. Switch to Local Environment

Run this PowerShell command:

```powershell
.\switch-env.ps1 local
```

This will:
- Switch to `.env.local` configuration
- Disable HTTPS requirements
- Enable debug mode
- Clear all Laravel caches

### 2. Start Development Server

```bash
php artisan serve
```

### 3. Access Your App

Open your browser and go to:
```
http://127.0.0.1:8000
```

**Important:** Use `http://` NOT `https://`

---

## Switching Back to Production

When you need to test production settings locally:

```powershell
.\switch-env.ps1 production
```

---

## Key Differences: Local vs Production

| Setting | Local | Production |
|---------|-------|------------|
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | `false` |
| `APP_URL` | `http://127.0.0.1:8000` | `https://injury.hsc.org.zw` |
| `SESSION_SECURE_COOKIE` | `false` | `true` |
| `LOG_LEVEL` | `debug` | `error` |

---

## Troubleshooting

### "Unsupported SSL request" errors

**Cause:** Browser trying to use HTTPS on local HTTP server

**Solution:**
1. Make sure you're using `http://127.0.0.1:8000` (not https)
2. Run `.\switch-env.ps1 local`
3. Clear browser cache and cookies for localhost
4. Restart `php artisan serve`

### Session/Cookie issues

**Cause:** `SESSION_SECURE_COOKIE=true` requires HTTPS

**Solution:**
- Local environment has `SESSION_SECURE_COOKIE=false`
- Use the environment switcher script

### Database connection errors

Make sure your local MySQL is running on port 3307:
```bash
mysql --host=127.0.0.1 --port=3307 -u root -p
```

---

## Safety Notes

✅ `.env.local` is in `.gitignore` - won't be pushed to production
✅ Production `.env` is safe and unchanged
✅ Switching environments is reversible
✅ Always backup before major changes

---

## Commands Reference

```powershell
# Switch to local development
.\switch-env.ps1 local

# Switch to production settings
.\switch-env.ps1 production

# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run queue worker
php artisan queue:work

# Run scheduled jobs
php artisan schedule:work
```
