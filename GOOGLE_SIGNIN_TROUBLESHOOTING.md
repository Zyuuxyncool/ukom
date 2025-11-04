# Google Sign-In Troubleshooting Guide

Complete troubleshooting guide for Google OAuth issues in Nusantara Store.

---

## 🔴 Error: "redirect_uri_mismatch"

### Full Error Message
```
Error 400: redirect_uri_mismatch
The redirect URI in the request, https://ukom.test/auth/google/callback,
does not match the ones authorized for the OAuth client.
```

### Causes
1. Redirect URI not added to Google Console
2. Mismatch between .env and Google Console
3. HTTP vs HTTPS mismatch
4. Trailing slash issue
5. Wrong OAuth client selected

### Solutions

#### Check Your .env File
```env
GOOGLE_REDIRECT_URI=https://ukom.test/auth/google/callback
```

#### Check Google Console
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Select your project
3. Navigate to **APIs & Services > Credentials**
4. Click on your OAuth 2.0 Client ID
5. Check **Authorized redirect URIs** section

Should contain:
```
https://ukom.test/auth/google/callback
```

#### Common Mistakes

❌ **Wrong**: `https://ukom.test/auth/google/callback/` (trailing slash)
✅ **Correct**: `https://ukom.test/auth/google/callback`

❌ **Wrong**: `http://ukom.test/auth/google/callback` (when using https)
✅ **Correct**: `https://ukom.test/auth/google/callback`

❌ **Wrong**: `https://ukom.test:443/auth/google/callback` (with port)
✅ **Correct**: `https://ukom.test/auth/google/callback`

### Quick Fix Steps
1. Copy the exact URL from the error message
2. Add it to Google Console Authorized redirect URIs
3. Click **Save**
4. Wait 5 minutes for changes to propagate
5. Clear browser cache
6. Try again

---

## 🔴 Error: "Access blocked: Authorization Error"

### Full Error Message
```
Access blocked: This app's request is invalid
You can't sign in because this app sent an invalid request.
```

Or:

```
Access blocked: Nusantara Store has not completed the Google verification process
```

### Causes
1. User not added as test user
2. OAuth consent screen not configured
3. App not published (for external users)

### Solutions

#### Add Test Users
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Navigate to **APIs & Services > OAuth consent screen**
3. Scroll to **Test users** section
4. Click **+ ADD USERS**
5. Enter email addresses (one per line):
   ```
   your.email@gmail.com
   test.user@gmail.com
   ```
6. Click **Save**

#### Configure OAuth Consent Screen
1. Go to **APIs & Services > OAuth consent screen**
2. Fill in required fields:
   - App name: `Nusantara Store`
   - User support email: Your email
   - Developer contact: Your email
3. Add scopes:
   - `userinfo.email`
   - `userinfo.profile`
   - `openid`
4. Click **Save and Continue**

#### Publish App (Optional)
For production or testing with any Google account:
1. Go to **OAuth consent screen**
2. Click **PUBLISH APP**
3. Confirm the action

**Note**: Publishing requires Google verification for certain scopes.

---

## 🔴 Error: "invalid_client"

### Full Error Message
```
Error 401: invalid_client
The OAuth client was not found.
```

### Causes
1. Wrong Client ID in .env
2. Client ID deleted from Google Console
3. Using credentials from wrong project

### Solutions

#### Verify Client ID
1. Open your `.env` file
2. Check `GOOGLE_CLIENT_ID` value
3. Go to Google Console > Credentials
4. Click on your OAuth 2.0 Client ID
5. Verify the **Client ID** matches exactly

#### Check for Spaces/Typos
```env
# ❌ Wrong (extra space)
GOOGLE_CLIENT_ID= 123456789-abc.apps.googleusercontent.com

# ✅ Correct
GOOGLE_CLIENT_ID=123456789-abc.apps.googleusercontent.com
```

#### Regenerate Credentials
If credentials were deleted:
1. Create new OAuth 2.0 Client ID
2. Update `.env` with new credentials
3. Clear config cache: `php artisan config:clear`

---

## 🔴 Error: "Client Secret is Wrong"

### Symptoms
- Login fails silently
- Redirects back to login page
- No error message shown

### Solutions

#### Verify Client Secret
```env
GOOGLE_CLIENT_SECRET=GOCSPX-your_actual_secret_here
```

#### Check Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

Look for error messages related to OAuth.

#### Test Configuration
```bash
php artisan tinker
```

```php
>>> config('services.google.client_id')
>>> config('services.google.client_secret')
>>> config('services.google.redirect')
```

Verify these match your Google Console settings.

---

## 🔴 Error: "Error 400: invalid_request"

### Causes
1. Missing required parameters
2. Malformed redirect URI
3. Invalid scope

### Solutions

#### Clear Config Cache
```bash
php artisan config:clear
php artisan cache:clear
```

#### Verify Environment Variables
```bash
php artisan tinker
>>> env('GOOGLE_CLIENT_ID')
>>> env('GOOGLE_CLIENT_SECRET')
>>> env('GOOGLE_REDIRECT_URI')
```

All should return non-null values.

#### Check Route Exists
```bash
php artisan route:list | grep google
```

Should show both redirect and callback routes.

---

## 🔴 Button Doesn't Work / Nothing Happens

### Symptoms
- Clicking "Masuk dengan Google" does nothing
- No redirect occurs
- Console shows errors

### Solutions

#### Check Browser Console
Press F12, go to Console tab, look for errors.

#### Verify Route Link
In `login.blade.php`:
```php
{{ route('oauth.google.redirect') }}
```

Should output: `https://ukom.test/auth/google/redirect`

#### Test Route Manually
Visit: `https://ukom.test/auth/google/redirect`

Should redirect to Google immediately.

#### Check for JavaScript Errors
Disable browser extensions that might block redirects:
- Ad blockers
- Privacy extensions
- Cookie blockers

---

## 🔴 Error After Successful Google Login

### Symptoms
- Google login succeeds
- Redirected back to app
- Error occurs during user creation

### Solutions

#### Check Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo()
```

#### Run Migrations
```bash
php artisan migrate
```

Ensure `google_id` column exists:
```bash
php artisan tinker
>>> Schema::hasColumn('users', 'google_id')
```

Should return `true`.

#### Check Laravel Logs
```bash
tail -100 storage/logs/laravel.log
```

Look for SQL errors or validation errors.

---

## 🔴 User Created But Not Logged In

### Symptoms
- User appears in database
- Still not logged in after Google callback
- Redirected to login page

### Solutions

#### Check Session Configuration
`.env`:
```env
SESSION_DRIVER=database
```

Ensure session tables exist:
```bash
php artisan migrate
```

#### Clear Sessions
```bash
php artisan session:clear
```

Or delete browser cookies and try again.

#### Check AuthController
In `googleCallback()` method, ensure:
```php
auth()->login($user, true);
```

The `true` parameter enables "remember me".

---

## 🔴 Environment-Specific Issues

### Laravel Herd Issues

#### Wrong Domain
Check Herd is using correct domain:
```bash
herd list
```

Should show `ukom` linked to project directory.

#### SSL Certificate Issues
If using `https://ukom.test`:
- Ensure Herd SSL is enabled
- Trust the certificate in your system

#### Switch to HTTP if needed
```bash
herd unsecure ukom
```

Update `.env`:
```env
APP_URL=http://ukom.test
GOOGLE_REDIRECT_URI=http://ukom.test/auth/google/callback
```

Update Google Console to use `http://` URLs.

### PHP Artisan Serve Issues

#### Port Already in Use
```bash
php artisan serve --port=8001
```

Update `.env` and Google Console to use `:8001`

---

## 🔴 Production Deployment Issues

### HTTPS Required
Google OAuth requires HTTPS in production.

#### Get SSL Certificate
- Use Let's Encrypt (free)
- Use Cloudflare (free tier available)
- Purchase from SSL provider

#### Force HTTPS in Laravel
`app/Providers/AppServiceProvider.php`:
```php
public function boot()
{
    if (config('app.env') === 'production') {
        URL::forceScheme('https');
    }
}
```

### Different Domain
Update Google Console:
1. Add production domain to Authorized JavaScript origins
2. Add production callback URL to Authorized redirect URIs
3. Update `.env` on production server

---

## 🛠️ Debugging Tools

### 1. Test OAuth Flow Manually

#### Step 1: Get Redirect URL
```bash
php artisan tinker
```

```php
>>> $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl()
>>> dump($url)
```

Copy URL and paste in browser.

#### Step 2: Inspect Callback
Add temporary logging in `googleCallback()`:
```php
public function googleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();
        Log::info('Google User Data:', [
            'id' => $googleUser->getId(),
            'email' => $googleUser->getEmail(),
            'name' => $googleUser->getName(),
        ]);
        // ... rest of code
    } catch (\Throwable $e) {
        Log::error('Google OAuth Error:', ['message' => $e->getMessage()]);
        throw $e;
    }
}
```

Check logs:
```bash
tail -f storage/logs/laravel.log
```

### 2. Check Database

```bash
php artisan tinker
```

```php
>>> User::where('google_id', '!=', null)->get()
>>> User::where('email', 'your.email@gmail.com')->first()
```

### 3. Verify Environment

```bash
php artisan about
```

Check:
- Environment: local/production
- Debug mode: true/false
- URL: should match Google Console
- Database: connected

---

## 📋 Pre-Flight Checklist

Before testing Google Sign-In:

### Google Cloud Console
- [ ] Project created
- [ ] OAuth consent screen configured
- [ ] OAuth 2.0 Client ID created
- [ ] JavaScript origins added (no trailing slash)
- [ ] Redirect URIs added (with full path)
- [ ] Test users added (for external apps)
- [ ] Scopes configured (email, profile, openid)

### Laravel Application
- [ ] `.env` file has `GOOGLE_CLIENT_ID`
- [ ] `.env` file has `GOOGLE_CLIENT_SECRET`
- [ ] `.env` file has `GOOGLE_REDIRECT_URI`
- [ ] `.env` file has correct `APP_URL`
- [ ] `php artisan migrate` has been run
- [ ] `google_id` column exists in `users` table
- [ ] Routes exist (check with `php artisan route:list`)
- [ ] Config cache cleared (`php artisan config:clear`)

### Testing
- [ ] Can access login page
- [ ] Google button is visible
- [ ] Clicking button redirects to Google
- [ ] Can select Google account
- [ ] Redirected back to app after consent
- [ ] User is logged in
- [ ] User data saved in database

---

## 🆘 Still Having Issues?

### Check All Files

1. **AuthController.php** - googleRedirect() and googleCallback() methods exist
2. **routes/auth.php** - Both Google routes defined
3. **config/services.php** - Google configuration present
4. **.env** - All Google variables set
5. **Migration** - google_id column added

### Clear Everything
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
composer dump-autoload
```

### Test Step by Step
1. Test redirect route: Visit `/auth/google/redirect` directly
2. Check if it redirects to Google
3. Complete Google login
4. Check if callback route is hit
5. Check Laravel logs for errors
6. Check database for new user

---

## 📞 Getting Help

### Laravel Logs
```bash
tail -100 storage/logs/laravel.log
```

### Enable Debug Mode
`.env`:
```env
APP_DEBUG=true
```

**Warning**: Never enable debug mode in production!

### Check Socialite Version
```bash
composer show laravel/socialite
```

Should be version 5.x or higher.

---

## ✅ Success Indicators

You know it's working when:
1. ✅ Clicking Google button redirects to Google
2. ✅ Can select Google account and grant permissions
3. ✅ Redirected back to your app
4. ✅ User is logged in (see profile/dashboard)
5. ✅ User exists in database with `google_id`
6. ✅ Can log out and log back in with Google
7. ✅ No errors in browser console
8. ✅ No errors in Laravel logs

---

## 🎓 Common Gotchas

1. **Trailing Slashes**: Google Console URIs should NOT have trailing slashes
2. **HTTP vs HTTPS**: Must match exactly between .env and Google Console
3. **Port Numbers**: Include port in URLs if using non-standard ports
4. **Cache**: Always clear config cache after changing .env
5. **Test Users**: Required for external OAuth apps in development
6. **Multiple Projects**: Make sure you're in the right Google Cloud project
7. **Credentials**: Never commit .env to Git
8. **State Token**: Laravel handles this automatically - don't modify

---

This troubleshooting guide should help you resolve most Google Sign-In issues. If problems persist, check the Laravel and Google OAuth documentation for updates.
