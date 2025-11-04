# Google Sign-In Flow Architecture

This document explains how Google Sign-In works in the Nusantara Store application.

---

## 📊 Authentication Flow Diagram

```
┌──────────────────────────────────────────────────────────────────┐
│                      User Authentication Flow                     │
└──────────────────────────────────────────────────────────────────┘

   User                  Laravel App               Google OAuth          Database
    │                        │                          │                   │
    │  1. Click "Masuk      │                          │                   │
    │     dengan Google"    │                          │                   │
    ├──────────────────────>│                          │                   │
    │                       │                          │                   │
    │                       │  2. Redirect to         │                   │
    │                       │     Google OAuth         │                   │
    │                       ├─────────────────────────>│                   │
    │                       │                          │                   │
    │  3. Google Login Page │                          │                   │
    │<──────────────────────┼──────────────────────────┤                   │
    │                       │                          │                   │
    │  4. User enters       │                          │                   │
    │     Google credentials│                          │                   │
    ├─────────────────────────────────────────────────>│                   │
    │                       │                          │                   │
    │                       │                          │  5. Validate      │
    │                       │                          │     credentials   │
    │                       │                          │                   │
    │                       │  6. Callback with       │                   │
    │                       │     user data            │                   │
    │                       │<─────────────────────────┤                   │
    │                       │                          │                   │
    │                       │  7. Check if user exists │                   │
    │                       ├─────────────────────────────────────────────>│
    │                       │                          │                   │
    │                       │  8. User data (or null) │                   │
    │                       │<─────────────────────────────────────────────┤
    │                       │                          │                   │
    │                       │  9. Create new user      │                   │
    │                       │     if doesn't exist     │                   │
    │                       ├─────────────────────────────────────────────>│
    │                       │                          │                   │
    │                       │  10. Save google_id      │                   │
    │                       ├─────────────────────────────────────────────>│
    │                       │                          │                   │
    │                       │  11. Create session      │                   │
    │                       │      (login user)        │                   │
    │                       │                          │                   │
    │  12. Redirect to      │                          │                   │
    │      home/dashboard   │                          │                   │
    │<──────────────────────┤                          │                   │
    │                       │                          │                   │
```

---

## 🔐 Code Flow Breakdown

### 1. User Initiates Login

**File**: `resources/views/auth/login.blade.php`

```html
<a href="{{ route('oauth.google.redirect') }}" class="btn btn-google">
    <img src="..." alt="Google Logo">
    Masuk dengan Google
</a>
```

**Route**: `routes/auth.php`
```php
Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect'])
    ->name('oauth.google.redirect');
```

---

### 2. Redirect to Google

**File**: `app/Http/Controllers/AuthController.php`

```php
public function googleRedirect()
{
    return Socialite::driver('google')->redirect();
}
```

**What happens**:
- Laravel Socialite generates a Google OAuth URL
- URL includes: client_id, redirect_uri, scope, state
- User is redirected to Google's consent screen

**Example URL**:
```
https://accounts.google.com/o/oauth2/auth?
  client_id=YOUR_CLIENT_ID&
  redirect_uri=https://ukom.test/auth/google/callback&
  scope=openid+profile+email&
  response_type=code&
  state=RANDOM_STATE_TOKEN
```

---

### 3. User Authorizes on Google

User sees Google's consent screen with:
- Profile picture and name
- Email address
- Permissions requested (profile, email)

User clicks "Continue" or "Allow"

---

### 4. Google Redirects Back

Google redirects to: `https://ukom.test/auth/google/callback?code=AUTH_CODE&state=STATE`

**Route**: `routes/auth.php`
```php
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])
    ->name('oauth.google.callback');
```

---

### 5. Process Google Callback

**File**: `app/Http/Controllers/AuthController.php`

```php
public function googleCallback()
{
    try {
        // 1. Exchange auth code for user data
        $googleUser = Socialite::driver('google')->stateless()->user();
        
        // 2. Extract user information
        $email = $googleUser->getEmail();
        $name = $googleUser->getName();
        $googleId = $googleUser->getId();
        
        // 3. Find or create user
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(Str::random(24)), // Random, not used
            ]);
            
            // Assign Buyer role
            $user->akses()->create(['akses' => 'Buyer']);
            
            // Create profile
            ProfilBuyer::create([
                'user_id' => $user->id,
                'nama' => $user->name
            ]);
        }
        
        // 4. Save Google ID
        if (empty($user->google_id)) {
            $user->google_id = $googleId;
            $user->save();
        }
        
        // 5. Log the user in
        auth()->login($user, true);
        
        // 6. Redirect to appropriate page
        $route = $user->akses->akses === 'Buyer' 
            ? 'buyer.landing' 
            : 'buyer.dashboard';
            
        return redirect()->route($route);
        
    } catch (\Throwable $e) {
        return redirect()->route('login')
            ->withErrors(['email' => 'Gagal login dengan Google.']);
    }
}
```

---

## 🗄️ Database Schema

### Users Table

```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    google_id VARCHAR(255) NULLABLE, -- Added for Google OAuth
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### User Akses Table (Roles)

```sql
CREATE TABLE user_akses (
    id BIGINT PRIMARY KEY,
    user_id BIGINT FOREIGN KEY REFERENCES users(id),
    akses VARCHAR(50) DEFAULT 'Buyer', -- Buyer, Seller, Admin, etc.
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Profil Buyer Table

```sql
CREATE TABLE profil_buyers (
    id BIGINT PRIMARY KEY,
    user_id BIGINT FOREIGN KEY REFERENCES users(id),
    nama VARCHAR(255),
    -- other buyer profile fields...
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## ⚙️ Configuration Files

### 1. `.env` Configuration

```env
# Application URL (must match Google Console)
APP_URL=https://ukom.test

# Google OAuth Credentials (from Google Cloud Console)
GOOGLE_CLIENT_ID=123456789-abc123xyz.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-your_secret_here_abc123
GOOGLE_REDIRECT_URI=https://ukom.test/auth/google/callback
```

### 2. `config/services.php`

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI', env('APP_URL') . '/auth/google/callback'),
],
```

---

## 🔒 Security Features

### 1. State Parameter
- Laravel Socialite automatically generates a unique `state` token
- Prevents CSRF attacks by validating state on callback

### 2. Stateless Mode
```php
Socialite::driver('google')->stateless()->user()
```
- Doesn't use sessions for OAuth flow
- Better for API/SPA scenarios

### 3. Email Uniqueness
- Email is unique in database
- Prevents duplicate accounts
- Existing email users are automatically linked

### 4. Google ID Storage
```php
if (empty($user->google_id)) {
    $user->google_id = $googleId;
    $user->save();
}
```
- Associates Google account with user
- Allows linking multiple auth methods

---

## 🎯 User Experience Flow

### New User (First Time)
1. Click "Masuk dengan Google"
2. Redirected to Google
3. Select Google account
4. Grant permissions
5. **Automatically**:
   - User account created
   - Assigned "Buyer" role
   - Profile created
   - Logged in
6. Redirected to buyer landing page

### Existing User
1. Click "Masuk dengan Google"
2. Redirected to Google
3. Select Google account
4. **Automatically**:
   - User found by email
   - Google ID saved (if not already)
   - Logged in
5. Redirected to appropriate dashboard

---

## 🔍 Debugging Tips

### Check Socialite is Working
```bash
php artisan tinker
>>> Socialite::driver('google')->redirect()
```

### View Routes
```bash
php artisan route:list | grep google
```

Should show:
```
GET  /auth/google/redirect  oauth.google.redirect
GET  /auth/google/callback  oauth.google.callback
```

### Check Database Migration
```bash
php artisan migrate:status
```

Should show `2025_11_02_000000_add_google_id_to_users_table` as migrated.

### Test User Creation
After Google login, check database:
```bash
php artisan tinker
>>> User::where('google_id', '!=', null)->get()
```

---

## 📝 Key Points

1. **No Password Required**: Users who sign up with Google don't need a password
2. **Automatic Account Creation**: New users are created automatically on first Google login
3. **Email as Identifier**: Email is the primary key for linking accounts
4. **Default Role**: All Google sign-up users get "Buyer" role by default
5. **Session Management**: `auth()->login($user, true)` creates a persistent session
6. **Error Handling**: Failed OAuth attempts redirect back to login with error message

---

## 🚀 What Makes This Implementation Secure?

1. ✅ **OAuth 2.0 Protocol**: Industry-standard authentication
2. ✅ **State Token**: Prevents CSRF attacks
3. ✅ **HTTPS**: Encrypts data in transit (production)
4. ✅ **No Password Storage**: Google handles password security
5. ✅ **Unique Email**: Prevents account duplication
6. ✅ **Random Password**: For Google users who might need traditional login later
7. ✅ **Error Handling**: Graceful failure with user feedback

---

## 📚 Related Files

- **Controller**: `app/Http/Controllers/AuthController.php`
- **Routes**: `routes/auth.php`
- **Config**: `config/services.php`
- **Views**: 
  - `resources/views/auth/login.blade.php`
  - `resources/views/auth/register.blade.php`
- **Migration**: `database/migrations/2025_11_02_000000_add_google_id_to_users_table.php`

---

## 🎓 Further Learning

- [Laravel Socialite Documentation](https://laravel.com/docs/11.x/socialite)
- [Google OAuth 2.0 Guide](https://developers.google.com/identity/protocols/oauth2)
- [OAuth 2.0 RFC](https://datatracker.ietf.org/doc/html/rfc6749)
