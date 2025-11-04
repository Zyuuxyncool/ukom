# Google Sign-In Setup Guide

This guide will help you set up Google Sign-In (OAuth 2.0) for your Laravel application.

## Prerequisites

- A Google account
- Laravel application with Socialite installed (already included in this project)
- Laravel Herd or any local development environment

---

## Step 1: Create a Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Click on the project dropdown at the top and select **"New Project"**
3. Enter a project name (e.g., "Nusantara Store")
4. Click **"Create"**
5. Wait for the project to be created and make sure it's selected

---

## Step 2: Enable Google+ API (if required)

1. In the Google Cloud Console, go to **"APIs & Services" > "Library"**
2. Search for **"Google+ API"** or **"People API"**
3. Click on it and press **"Enable"**

---

## Step 3: Create OAuth 2.0 Credentials

### 3.1 Configure OAuth Consent Screen

1. Go to **"APIs & Services" > "OAuth consent screen"**
2. Choose **"External"** user type (unless you have a Google Workspace)
3. Click **"Create"**
4. Fill in the required information:
   - **App name**: Nusantara Store (or your app name)
   - **User support email**: Your email address
   - **Developer contact information**: Your email address
5. Click **"Save and Continue"**
6. On the **Scopes** page, click **"Add or Remove Scopes"**
   - Add these scopes:
     - `.../auth/userinfo.email`
     - `.../auth/userinfo.profile`
     - `openid`
7. Click **"Update"** then **"Save and Continue"**
8. On **Test users** page (for External apps), add your test email addresses
9. Click **"Save and Continue"**
10. Review and click **"Back to Dashboard"**

### 3.2 Create OAuth Client ID

1. Go to **"APIs & Services" > "Credentials"**
2. Click **"+ Create Credentials"** at the top
3. Select **"OAuth client ID"**
4. Choose **"Web application"** as the application type
5. Give it a name (e.g., "Nusantara Store Web Client")

---

## Step 4: Configure Authorized Redirect URIs and JavaScript Origins

### For Laravel Herd Users

If you're using **Laravel Herd**, your local domain will be something like:
- `http://ukom.test`
- `https://ukom.test` (if Herd has SSL enabled)

### Authorized JavaScript Origins

Add these origins (use the ones that match your setup):

```
http://ukom.test
https://ukom.test
http://localhost
```

**Note**: 
- Do NOT include a trailing slash
- Do NOT include a path (like `/auth/google/redirect`)
- Port numbers like `:8080` should only be included if you're using a specific port

### Authorized Redirect URIs

Add these redirect URIs (use the ones that match your setup):

```
http://ukom.test/auth/google/callback
https://ukom.test/auth/google/callback
http://localhost/auth/google/callback
```

**Important**:
- The path `/auth/google/callback` must match the route defined in your Laravel app
- Include the full URL with protocol and path
- Do NOT add a trailing slash

### Example for Different Environments

#### Laravel Herd (Recommended)
```
Authorized JavaScript origins:
- https://ukom.test
- http://ukom.test

Authorized redirect URIs:
- https://ukom.test/auth/google/callback
- http://ukom.test/auth/google/callback
```

#### PHP Artisan Serve
```
Authorized JavaScript origins:
- http://localhost:8000

Authorized redirect URIs:
- http://localhost:8000/auth/google/callback
```

#### Production Server
```
Authorized JavaScript origins:
- https://yourdomain.com

Authorized redirect URIs:
- https://yourdomain.com/auth/google/callback
```

6. Click **"Create"**
7. A modal will appear showing your **Client ID** and **Client Secret**
8. Copy both values (you'll need them in the next step)

---

## Step 5: Configure Your Laravel Application

### 5.1 Create or Update .env File

If you don't have a `.env` file, copy from the example:
```bash
cp .env.example .env
```

### 5.2 Add Google OAuth Credentials

Open your `.env` file and add/update these values:

```env
APP_URL=https://ukom.test

GOOGLE_CLIENT_ID=your-client-id-here.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret-here
GOOGLE_REDIRECT_URI=https://ukom.test/auth/google/callback
```

**Replace**:
- `your-client-id-here` with your actual Client ID from Google
- `your-client-secret-here` with your actual Client Secret from Google
- `https://ukom.test` with your actual local domain (if different)

### 5.3 Important Notes

- Make sure `APP_URL` matches one of your Authorized JavaScript Origins
- Make sure `GOOGLE_REDIRECT_URI` matches one of your Authorized Redirect URIs exactly
- If using Herd with SSL (https), use `https://` in all URLs
- If using Herd without SSL, use `http://` in all URLs

---

## Step 6: Run Database Migrations

The Google ID column needs to be added to the users table:

```bash
php artisan migrate
```

This will run the migration: `2025_11_02_000000_add_google_id_to_users_table.php`

---

## Step 7: Test Your Implementation

### 7.1 Start Your Application

If using Laravel Herd, your site should already be running at `https://ukom.test`

If using `php artisan serve`:
```bash
php artisan serve
```

### 7.2 Test the Login Flow

1. Navigate to your login page: `https://ukom.test/login`
2. Click the **"Masuk dengan Google"** button
3. You should be redirected to Google's consent screen
4. Select your Google account
5. Grant permissions
6. You should be redirected back to your application and logged in

### 7.3 Verify User Creation

Check your database to confirm a new user was created with:
- Name from Google account
- Email from Google account
- `google_id` populated
- `akses` set to "Buyer"

---

## Troubleshooting

### Error: "redirect_uri_mismatch"

**Problem**: The redirect URI doesn't match what's configured in Google Console

**Solution**:
1. Check your `.env` file - ensure `GOOGLE_REDIRECT_URI` is correct
2. Check Google Console - ensure the exact same URI is in Authorized redirect URIs
3. Check for:
   - HTTP vs HTTPS mismatch
   - Trailing slash (shouldn't have one)
   - Port number mismatch
   - Subdomain or path differences

### Error: "Access blocked: Authorization Error"

**Problem**: Your app is not verified or the user is not added as a test user

**Solution**:
1. Go to OAuth consent screen in Google Console
2. Add the user's email to Test users
3. Or publish your app (if ready for production)

### Error: "Invalid client ID"

**Problem**: The Client ID is incorrect or not found

**Solution**:
1. Double-check your `GOOGLE_CLIENT_ID` in `.env`
2. Ensure there are no extra spaces or characters
3. Make sure you're using the Client ID (not Client Secret)

### Error: "The OAuth client was not found"

**Problem**: The credentials don't exist or project is wrong

**Solution**:
1. Verify you're using the correct Google Cloud project
2. Check that the OAuth client ID still exists in the Credentials page
3. Regenerate credentials if needed

### Google Sign-In Button Not Working

**Problem**: Clicking the button does nothing

**Solution**:
1. Check browser console for JavaScript errors
2. Verify the route exists: `php artisan route:list | grep google`
3. Check that Socialite is properly installed: `composer show laravel/socialite`

---

## For Production Deployment

When deploying to production:

1. **Update Google Cloud Console**:
   - Add your production domain to Authorized JavaScript origins
   - Add your production callback URL to Authorized redirect URIs
   - Example: 
     - Origin: `https://nusantarastore.com`
     - Redirect: `https://nusantarastore.com/auth/google/callback`

2. **Update .env on Production Server**:
   ```env
   APP_URL=https://nusantarastore.com
   GOOGLE_CLIENT_ID=your-production-client-id
   GOOGLE_CLIENT_SECRET=your-production-client-secret
   GOOGLE_REDIRECT_URI=https://nusantarastore.com/auth/google/callback
   ```

3. **Publish Your OAuth App** (if needed):
   - Go to OAuth consent screen
   - Click "Publish App"
   - Complete the verification process (for external apps)

4. **Test Thoroughly**:
   - Test the complete sign-in flow on production
   - Verify user data is saved correctly
   - Check that users can sign in on subsequent visits

---

## Security Best Practices

1. **Never commit credentials to Git**:
   - `.env` is in `.gitignore` by default
   - Never share your Client Secret publicly

2. **Use HTTPS in Production**:
   - Always use `https://` for production URLs
   - Get an SSL certificate (Let's Encrypt is free)

3. **Restrict Origins and Redirect URIs**:
   - Only add the domains you actually use
   - Remove localhost/test domains from production credentials

4. **Rotate Secrets Regularly**:
   - Change your Client Secret periodically
   - Update it in your `.env` file when changed

5. **Monitor Usage**:
   - Check Google Cloud Console for API usage
   - Set up billing alerts if needed

6. **Google Logo CDN**:
   - The implementation uses Google's logo from svgrepo.com CDN
   - This is acceptable per Google's branding guidelines
   - For production, consider hosting the logo locally or using Google's official CDN
   - Alternative: Download and serve from your own `/public` directory

---

## Additional Resources

### Project Documentation
- [Quick Start Guide](./GOOGLE_SIGNIN_QUICKSTART.md) - 5-minute setup
- [Flow Architecture](./GOOGLE_SIGNIN_FLOW.md) - How it works under the hood
- [Troubleshooting Guide](./GOOGLE_SIGNIN_TROUBLESHOOTING.md) - Common issues and solutions

### External Documentation
- [Laravel Socialite Documentation](https://laravel.com/docs/11.x/socialite)
- [Google OAuth 2.0 Documentation](https://developers.google.com/identity/protocols/oauth2)
- [Google Cloud Console](https://console.cloud.google.com/)
- [Laravel Herd Documentation](https://herd.laravel.com/)

---

## Support

If you encounter any issues:

1. Check the troubleshooting section above
2. Review Laravel logs: `storage/logs/laravel.log`
3. Check browser console for JavaScript errors
4. Verify all configuration values are correct

---

## Summary

Your Google Sign-In is already implemented! You just need to:

1. ✅ Create a Google Cloud Project
2. ✅ Set up OAuth consent screen
3. ✅ Create OAuth Client ID
4. ✅ Configure Authorized JavaScript Origins and Redirect URIs
5. ✅ Add credentials to your `.env` file
6. ✅ Run migrations
7. ✅ Test the implementation

The code is already in place - you only need the Google credentials and configuration!
