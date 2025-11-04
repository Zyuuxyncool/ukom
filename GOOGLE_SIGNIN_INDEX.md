# Google Sign-In Documentation Index

Complete documentation for setting up and troubleshooting Google Sign-In in Nusantara Store.

---

## 📚 Documentation Overview

This project includes comprehensive documentation for Google OAuth integration. Choose the guide that best fits your needs:

### 🚀 For Quick Setup (5 minutes)
**[GOOGLE_SIGNIN_QUICKSTART.md](./GOOGLE_SIGNIN_QUICKSTART.md)**
- TL;DR setup instructions
- Quick reference for common URLs
- Checklist format
- Perfect for experienced developers

### 📖 For Detailed Setup (15 minutes)
**[GOOGLE_SIGNIN_SETUP.md](./GOOGLE_SIGNIN_SETUP.md)**
- Complete step-by-step instructions
- Detailed explanations for each step
- Environment-specific setup (Herd, Artisan Serve, Production)
- Security best practices
- Perfect for first-time setup

### 🏗️ For Understanding the Architecture
**[GOOGLE_SIGNIN_FLOW.md](./GOOGLE_SIGNIN_FLOW.md)**
- Visual flow diagrams
- Code walkthrough
- Database schema
- How OAuth works in this app
- Perfect for developers who want to understand the implementation

### 🐛 For Troubleshooting Issues
**[GOOGLE_SIGNIN_TROUBLESHOOTING.md](./GOOGLE_SIGNIN_TROUBLESHOOTING.md)**
- Common error messages and solutions
- Debug tools and techniques
- Environment-specific issues
- Pre-flight checklist
- Perfect when things don't work as expected

### 🎨 For Google Console Setup
**[GOOGLE_CONSOLE_SCREENSHOTS.md](./GOOGLE_CONSOLE_SCREENSHOTS.md)**
- Visual guide with ASCII diagrams
- What to look for in Google Console
- Where to find credentials
- Quick reference cards
- Perfect for visual learners

---

## 🎯 Quick Navigation by Goal

### "I want to set up Google Sign-In"
1. Start with [GOOGLE_SIGNIN_QUICKSTART.md](./GOOGLE_SIGNIN_QUICKSTART.md)
2. If you need more details, see [GOOGLE_SIGNIN_SETUP.md](./GOOGLE_SIGNIN_SETUP.md)
3. For Google Console navigation, see [GOOGLE_CONSOLE_SCREENSHOTS.md](./GOOGLE_CONSOLE_SCREENSHOTS.md)

### "I'm getting an error"
1. Go to [GOOGLE_SIGNIN_TROUBLESHOOTING.md](./GOOGLE_SIGNIN_TROUBLESHOOTING.md)
2. Find your error message
3. Follow the solution steps

### "I want to understand how it works"
1. Read [GOOGLE_SIGNIN_FLOW.md](./GOOGLE_SIGNIN_FLOW.md)
2. Check the code files mentioned
3. Experiment with the flow

### "I need to deploy to production"
1. See production section in [GOOGLE_SIGNIN_SETUP.md](./GOOGLE_SIGNIN_SETUP.md)
2. Review security practices
3. Update Google Console for production domain

---

## 🗂️ Documentation Structure

```
Google Sign-In Documentation
│
├─ 🚀 GOOGLE_SIGNIN_QUICKSTART.md
│   ├─ 5-minute setup
│   ├─ Quick reference tables
│   └─ Checklist
│
├─ 📖 GOOGLE_SIGNIN_SETUP.md
│   ├─ Step-by-step guide
│   ├─ Google Cloud Console setup
│   ├─ Laravel configuration
│   ├─ Environment-specific instructions
│   ├─ Security best practices
│   └─ Production deployment
│
├─ 🏗️ GOOGLE_SIGNIN_FLOW.md
│   ├─ Visual flow diagram
│   ├─ Code walkthrough
│   ├─ Database schema
│   ├─ Configuration files
│   └─ Security features
│
├─ 🐛 GOOGLE_SIGNIN_TROUBLESHOOTING.md
│   ├─ Common errors
│   ├─ Solutions
│   ├─ Debug tools
│   ├─ Pre-flight checklist
│   └─ Getting help
│
└─ 🎨 GOOGLE_CONSOLE_SCREENSHOTS.md
    ├─ Visual guides
    ├─ What to look for
    ├─ Where to find things
    └─ Quick reference
```

---

## 📋 Complete Setup Checklist

Use this master checklist to ensure everything is configured correctly:

### Phase 1: Google Cloud Console
- [ ] Create Google Cloud Project
- [ ] Configure OAuth Consent Screen
  - [ ] Set app name
  - [ ] Set support email
  - [ ] Add scopes (email, profile, openid)
  - [ ] Add test users (for external apps)
- [ ] Create OAuth 2.0 Client ID
  - [ ] Select "Web application"
  - [ ] Add Authorized JavaScript Origins
  - [ ] Add Authorized Redirect URIs
  - [ ] Copy Client ID
  - [ ] Copy Client Secret

### Phase 2: Laravel Application
- [ ] Copy `.env.example` to `.env` (if needed)
- [ ] Add `GOOGLE_CLIENT_ID` to `.env`
- [ ] Add `GOOGLE_CLIENT_SECRET` to `.env`
- [ ] Set `GOOGLE_REDIRECT_URI` in `.env`
- [ ] Set `APP_URL` in `.env`
- [ ] Run `php artisan config:clear`
- [ ] Run `php artisan migrate`
- [ ] Verify routes exist: `php artisan route:list | grep google`

### Phase 3: Testing
- [ ] Visit `/login` page
- [ ] Click "Masuk dengan Google" button
- [ ] Redirected to Google
- [ ] Select Google account
- [ ] Grant permissions
- [ ] Redirected back to app
- [ ] User is logged in
- [ ] Check database for new user with `google_id`
- [ ] Test logout
- [ ] Test login again

### Phase 4: Validation
- [ ] No errors in browser console
- [ ] No errors in `storage/logs/laravel.log`
- [ ] User can access their profile
- [ ] Session persists across page refreshes
- [ ] Can logout successfully
- [ ] Can login again with Google

---

## 🔗 Related Files in Codebase

### Controllers
- `app/Http/Controllers/AuthController.php`
  - `googleRedirect()` method (line ~268)
  - `googleCallback()` method (line ~273)

### Routes
- `routes/auth.php`
  - Line 22: `/auth/google/redirect`
  - Line 23: `/auth/google/callback`

### Views
- `resources/views/auth/login.blade.php` (line 168-171)
- `resources/views/auth/register.blade.php` (line 269-274)

### Configuration
- `config/services.php` (line 38-43)
- `.env.example` (line 68-71)

### Database
- `database/migrations/2025_11_02_000000_add_google_id_to_users_table.php`
  - Adds `google_id` column to `users` table

### Services
- Laravel Socialite package (installed via Composer)

---

## 🔍 Finding What You Need

### By Error Message

| Error | Document | Section |
|-------|----------|---------|
| `redirect_uri_mismatch` | [Troubleshooting](./GOOGLE_SIGNIN_TROUBLESHOOTING.md) | Error: redirect_uri_mismatch |
| `invalid_client` | [Troubleshooting](./GOOGLE_SIGNIN_TROUBLESHOOTING.md) | Error: invalid_client |
| `Access blocked` | [Troubleshooting](./GOOGLE_SIGNIN_TROUBLESHOOTING.md) | Error: Access blocked |
| Button doesn't work | [Troubleshooting](./GOOGLE_SIGNIN_TROUBLESHOOTING.md) | Button Doesn't Work |

### By Task

| Task | Document | Section |
|------|----------|---------|
| Initial setup | [Quickstart](./GOOGLE_SIGNIN_QUICKSTART.md) | Quick Setup |
| Detailed setup | [Setup Guide](./GOOGLE_SIGNIN_SETUP.md) | All sections |
| Configure JavaScript origins | [Console Guide](./GOOGLE_CONSOLE_SCREENSHOTS.md) | Step 5 |
| Configure redirect URIs | [Console Guide](./GOOGLE_CONSOLE_SCREENSHOTS.md) | Step 6 |
| Understand flow | [Flow](./GOOGLE_SIGNIN_FLOW.md) | Authentication Flow |
| Deploy to production | [Setup Guide](./GOOGLE_SIGNIN_SETUP.md) | Production Deployment |

### By Environment

| Environment | Document | Section |
|-------------|----------|---------|
| Laravel Herd | [Quickstart](./GOOGLE_SIGNIN_QUICKSTART.md) | For Laravel Herd Users |
| PHP Artisan Serve | [Quickstart](./GOOGLE_SIGNIN_QUICKSTART.md) | For PHP Artisan Serve |
| Production | [Setup Guide](./GOOGLE_SIGNIN_SETUP.md) | Production Deployment |

---

## 💡 Best Practices

### 1. Development
- ✅ Use test users in OAuth consent screen
- ✅ Use localhost or .test domain
- ✅ Keep credentials in `.env` (never commit!)
- ✅ Clear config cache after .env changes

### 2. Testing
- ✅ Test with multiple Google accounts
- ✅ Test signup flow (new user)
- ✅ Test login flow (existing user)
- ✅ Test error scenarios
- ✅ Check browser console for errors

### 3. Production
- ✅ Use HTTPS always
- ✅ Publish OAuth consent screen
- ✅ Use environment variables
- ✅ Monitor API usage
- ✅ Set up error logging

### 4. Security
- ✅ Never commit credentials
- ✅ Use strong Client Secret
- ✅ Restrict origins to actual domains
- ✅ Rotate secrets regularly
- ✅ Monitor for suspicious activity

---

## 🆘 Getting Help

### Documentation Not Clear?
1. Check the troubleshooting guide first
2. Review the flow diagram for context
3. Check Laravel logs for detailed errors
4. Look at the code files referenced

### Still Stuck?
1. Enable Laravel debug mode (development only!)
2. Check all configuration values match
3. Clear all caches
4. Test step-by-step manually
5. Review Google Cloud Console settings

### Technical Issues?
- **Laravel Logs**: `storage/logs/laravel.log`
- **Browser Console**: Press F12 → Console tab
- **Route List**: `php artisan route:list`
- **Config Values**: `php artisan tinker` → `config('services.google')`

---

## 📊 Document Status

| Document | Status | Last Updated | Difficulty |
|----------|--------|--------------|------------|
| Quickstart | ✅ Complete | 2025-11-04 | Easy |
| Setup Guide | ✅ Complete | 2025-11-04 | Medium |
| Flow Diagram | ✅ Complete | 2025-11-04 | Medium |
| Troubleshooting | ✅ Complete | 2025-11-04 | Easy |
| Console Guide | ✅ Complete | 2025-11-04 | Easy |

---

## 🎓 Learning Path

### Beginner (No OAuth Experience)
1. Read [Setup Guide](./GOOGLE_SIGNIN_SETUP.md) completely
2. Follow each step carefully
3. Use [Console Guide](./GOOGLE_CONSOLE_SCREENSHOTS.md) for visual reference
4. Refer to [Troubleshooting](./GOOGLE_SIGNIN_TROUBLESHOOTING.md) if errors occur

### Intermediate (Some OAuth Experience)
1. Skim [Quickstart](./GOOGLE_SIGNIN_QUICKSTART.md)
2. Configure Google Console
3. Update .env file
4. Test implementation
5. Review [Flow Diagram](./GOOGLE_SIGNIN_FLOW.md) to understand specifics

### Advanced (OAuth Expert)
1. Use [Quickstart](./GOOGLE_SIGNIN_QUICKSTART.md) checklist
2. Check code in AuthController if needed
3. Reference [Flow Diagram](./GOOGLE_SIGNIN_FLOW.md) for architecture details

---

## 🚀 Quick Start Summary

**For the impatient:**

1. **Google Console**: Create project → OAuth client → Copy credentials
2. **Laravel .env**: Add `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`
3. **Migrate**: `php artisan migrate`
4. **Test**: Click "Masuk dengan Google" on `/login`
5. **Done**: You should be logged in!

**Need help?** → [Troubleshooting Guide](./GOOGLE_SIGNIN_TROUBLESHOOTING.md)

---

## 📝 Contributing

Found an issue or have a suggestion for the documentation?
1. Note which document needs updating
2. Describe the issue or improvement
3. Submit feedback to the project maintainer

---

## ✅ Final Checklist

Before you close this guide:

- [ ] I've read the appropriate documentation for my skill level
- [ ] I understand which URLs go in JavaScript origins vs redirect URIs
- [ ] I know where to find my Google credentials
- [ ] I know how to update my .env file
- [ ] I know where to look when something goes wrong
- [ ] I've bookmarked the troubleshooting guide

---

**Happy coding! 🎉**

The Google Sign-In functionality is fully implemented in the codebase. You just need to configure it with your Google OAuth credentials following the guides above.
