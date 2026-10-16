# Production Deployment Guide

## Critical Security Fixes Applied

### 1. Mixed Content Security Issue (HTTPS/HTTP)
**Problem:** Login form was submitting to HTTP from HTTPS page, causing browser blocks.

**Solution Applied:**
- Updated `APP_URL` in `.env` to `https://injury.hsc.org.zw`
- Enabled secure cookies: `SESSION_SECURE_COOKIE=true`
- **Added URL::forceScheme('https') in AppServiceProvider** - Forces all Laravel-generated URLs to use HTTPS in production
- Laravel will now automatically generate HTTPS URLs for all routes, forms, and assets

### 2. Content Security Policy (CSP) Violations
**Problem:** Font Awesome and form submissions were blocked by CSP headers.

**Solution Applied in `SecurityHeadersMiddleware.php`:**
- Added `https://cdnjs.cloudflare.com` to `style-src` directive
- Added `https://cdnjs.cloudflare.com` to `font-src` directive
- Updated `form-action` to allow HTTPS form submissions

### Updated CSP Policy:
```php
"style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
"font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data:",
"form-action 'self' https:",
```

## Deployment Steps for Production Server

### Step 1: Update Environment Variables
On your production server, update the `.env` file:

```bash
APP_URL=https://injury.hsc.org.zw
SESSION_SECURE_COOKIE=true
```

### Step 2: Clear Configuration Cache
After updating `.env`, run:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Step 3: Rebuild Assets (if needed)
```bash
npm run build
```

### Step 4: Restart Services
```bash
# Restart PHP-FPM
sudo systemctl restart php8.2-fpm

# Restart web server (Nginx or Apache)
sudo systemctl restart nginx
# OR
sudo systemctl restart apache2
```

### Step 5: Verify HTTPS Configuration

Ensure your web server (Nginx/Apache) is properly configured for HTTPS:

#### Nginx Example:
```nginx
server {
    listen 443 ssl http2;
    server_name injury.hsc.org.zw;
    
    ssl_certificate /path/to/ssl/certificate.crt;
    ssl_certificate_key /path/to/ssl/private.key;
    
    # Force HTTPS
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
    
    root /var/www/html/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name injury.hsc.org.zw;
    return 301 https://$server_name$request_uri;
}
```

## Testing After Deployment

1. **Test Login:** Navigate to https://injury.hsc.org.zw/login and verify:
   - No mixed content warnings in browser console
   - Login form submits successfully
   - No CSP violations

2. **Check Browser Console:**
   - Open DevTools (F12)
   - Check Console tab for any errors
   - Verify all resources load from HTTPS

3. **Test Session Security:**
   - Verify cookies are marked as "Secure"
   - Check Set-Cookie headers in Network tab

## Rollback Plan

If issues occur, you can quickly rollback by:

1. Reverting `.env` changes:
   ```bash
   APP_URL=http://localhost
   SESSION_SECURE_COOKIE=false
   ```

2. Clear caches:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

## Additional Security Recommendations

1. **Enable HSTS Preload:** Submit your domain to https://hstspreload.org/
2. **SSL Certificate:** Ensure using valid SSL certificate (Let's Encrypt or commercial)
3. **Security Headers:** Already configured in SecurityHeadersMiddleware
4. **Database Credentials:** Use strong passwords in production
5. **APP_DEBUG:** Ensure set to `false` in production

## Support

If you encounter any issues after deployment:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check web server error logs: `/var/log/nginx/error.log`
3. Verify SSL certificate is valid and not expired
4. Ensure all environment variables are properly set
