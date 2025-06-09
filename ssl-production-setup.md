# SSL Configuration for Production Server

## 📋 Requirements
- Ubuntu/Debian/CentOS server
- PHP 8.x with curl extension
- Root or sudo access

## 🔧 Ubuntu/Debian Setup

### 1. Update CA certificates
```bash
sudo apt update
sudo apt install ca-certificates curl
sudo update-ca-certificates
```

### 2. Configure PHP
```bash
# Find php.ini location
php --ini

# Edit php.ini (usually /etc/php/8.x/apache2/php.ini or /etc/php/8.x/fpm/php.ini)
sudo nano /etc/php/8.x/fpm/php.ini

# Add these lines:
curl.cainfo = /etc/ssl/certs/ca-certificates.crt
openssl.cafile = /etc/ssl/certs/ca-certificates.crt
```

### 3. Restart services
```bash
sudo systemctl restart php8.x-fpm
sudo systemctl restart nginx  # or apache2
```

## 🔧 CentOS/RHEL Setup

### 1. Update CA certificates
```bash
sudo yum update ca-certificates
# or for newer versions:
sudo dnf update ca-certificates
```

### 2. Configure PHP
```bash
# Find php.ini location
php --ini

# Edit php.ini
sudo nano /etc/php.ini

# Add these lines:
curl.cainfo = /etc/pki/tls/certs/ca-bundle.crt
openssl.cafile = /etc/pki/tls/certs/ca-bundle.crt
```

### 3. Restart services
```bash
sudo systemctl restart php-fpm
sudo systemctl restart nginx  # or httpd
```

## ⚙️ Laravel Environment Configuration

### Production .env
```env
# Enable SSL verification for production
GOOGLE_VERIFY_SSL=true

# Google OAuth settings
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URL=https://yourdomain.com/auth/google/callback
```

## 🧪 Testing

### Test SSL Configuration
```bash
# Test SSL with PHP
php -r "var_dump(openssl_get_cert_locations());"

# Test with curl
curl -I https://www.googleapis.com/oauth2/v4/token

# Test Laravel OAuth
php artisan tinker
# Then run:
# \Socialite::driver('google')->redirect()
```

## 🔍 Troubleshooting

### Common Issues:
1. **Certificate bundle not found**: Verify path in php.ini
2. **Permission denied**: Check file permissions
3. **Old certificates**: Update CA bundle

### Debug Commands:
```bash
# Check PHP configuration
php -i | grep -i ssl
php -i | grep -i curl

# Check certificate locations
ls -la /etc/ssl/certs/
ls -la /etc/pki/tls/certs/
```

## 🎯 Benefits of Proper SSL Configuration
- ✅ Secure OAuth communication
- ✅ HTTPS API calls work properly
- ✅ No security warnings
- ✅ Compliance with security standards 