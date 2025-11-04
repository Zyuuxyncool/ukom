# Google Sign-In Quick Start Guide

Quick reference for setting up Google Sign-In in your Laravel application.

## 🚀 Quick Setup (5 Minutes)

### Step 1: Google Cloud Console Setup
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create new project or select existing one
3. Navigate to **APIs & Services > Credentials**
4. Click **Create Credentials > OAuth client ID**
5. Select **Web application**

### Step 2: Configure URLs

#### For Laravel Herd Users (e.g., `ukom.test`)

**Authorized JavaScript origins:**
```
https://ukom.test
http://ukom.test
```

**Authorized redirect URIs:**
```
https://ukom.test/auth/google/callback
http://ukom.test/auth/google/callback
```

#### For PHP Artisan Serve (port 8000)

**Authorized JavaScript origins:**
```
http://localhost:8000
```

**Authorized redirect URIs:**
```
http://localhost:8000/auth/google/callback
```

### Step 3: Get Your Credentials
After creating the OAuth client, copy:
- Client ID
- Client Secret

### Step 4: Update .env File

```env
APP_URL=https://ukom.test

GOOGLE_CLIENT_ID=123456789-abcdefghijklmnop.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-your_secret_here
GOOGLE_REDIRECT_URI=https://ukom.test/auth/google/callback
```

### Step 5: Run Migrations

```bash
php artisan migrate
```

### Step 6: Test

1. Visit `/login`
2. Click "Masuk dengan Google"
3. Sign in with your Google account
4. Get redirected back to your app

---

## 📋 Common URLs by Environment

### Laravel Herd
```
APP_URL=https://ukom.test
Redirect: https://ukom.test/auth/google/callback
```

### Artisan Serve
```
APP_URL=http://localhost:8000
Redirect: http://localhost:8000/auth/google/callback
```

### Production
```
APP_URL=https://yourdomain.com
Redirect: https://yourdomain.com/auth/google/callback
```

---

## ⚠️ Important Rules

1. **JavaScript Origins**: NO trailing slash, NO path
   - ✅ `https://ukom.test`
   - ❌ `https://ukom.test/`
   - ❌ `https://ukom.test/auth`

2. **Redirect URIs**: Include full path, NO trailing slash
   - ✅ `https://ukom.test/auth/google/callback`
   - ❌ `https://ukom.test/auth/google/callback/`

3. **Match Exactly**: .env values must match Google Console exactly
   - Protocol (http vs https)
   - Domain/Port
   - Path

---

## 🐛 Quick Troubleshooting

| Error | Solution |
|-------|----------|
| `redirect_uri_mismatch` | Check redirect URI matches exactly in both Google Console and .env |
| `Access blocked` | Add your email as test user in OAuth consent screen |
| `Invalid client` | Double-check GOOGLE_CLIENT_ID in .env |
| Button doesn't work | Run `php artisan route:list \| grep google` to verify routes exist |

---

## 📚 Full Documentation

For detailed instructions, see [GOOGLE_SIGNIN_SETUP.md](./GOOGLE_SIGNIN_SETUP.md)

---

## ✅ Checklist

- [ ] Created Google Cloud Project
- [ ] Enabled required APIs
- [ ] Configured OAuth consent screen
- [ ] Created OAuth Client ID
- [ ] Added JavaScript origins
- [ ] Added redirect URIs
- [ ] Updated .env with Client ID
- [ ] Updated .env with Client Secret
- [ ] Updated .env with APP_URL
- [ ] Updated .env with GOOGLE_REDIRECT_URI
- [ ] Ran migrations
- [ ] Tested sign-in flow

---

## 🎯 What's Already Done

The code is already implemented! You just need:
1. Google OAuth credentials
2. Update .env file
3. Run migrations

That's it! The login and register pages already have Google Sign-In buttons.
