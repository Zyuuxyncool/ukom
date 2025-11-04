# Google Cloud Console - Visual Guide

This guide shows you what to look for in Google Cloud Console when setting up OAuth.

---

## 📍 Step-by-Step Visual Guide

### 1. Create/Select Project

**Navigation**: Google Cloud Console > Project Dropdown (top bar)

**What to look for**:
```
┌─────────────────────────────────────────┐
│ Google Cloud Console                     │
├─────────────────────────────────────────┤
│                                          │
│  [Project Dropdown ▼]  🔍  👤           │
│                                          │
│  ┌──────────────────────────────────┐  │
│  │ My Projects                       │  │
│  ├──────────────────────────────────┤  │
│  │ ● Nusantara Store                │  │
│  │ ○ Other Project                  │  │
│  │ ○ Another Project                │  │
│  ├──────────────────────────────────┤  │
│  │ [+ NEW PROJECT]                  │  │
│  └──────────────────────────────────┘  │
└─────────────────────────────────────────┘
```

**Action**: Click "+ NEW PROJECT" if you don't have one yet.

---

### 2. Navigate to APIs & Services

**Navigation**: ☰ Menu > APIs & Services > Credentials

**What to look for**:
```
┌─────────────────────────────────────────┐
│ ☰ Navigation Menu                       │
├─────────────────────────────────────────┤
│                                          │
│  🏠 Home                                │
│  📊 Dashboard                           │
│  💰 Billing                             │
│                                          │
│  📦 APIs & Services                     │
│    ├─ Dashboard                         │
│    ├─ Library                           │
│    ├─ Credentials    ← Click here       │
│    └─ OAuth consent screen              │
│                                          │
│  💾 Compute Engine                      │
│  🗄️ Cloud Storage                       │
└─────────────────────────────────────────┘
```

---

### 3. OAuth Consent Screen

**Navigation**: APIs & Services > OAuth consent screen

**What you'll configure**:

```
┌──────────────────────────────────────────────────────┐
│ OAuth consent screen                                  │
├──────────────────────────────────────────────────────┤
│                                                       │
│ Step 1: User Type                                    │
│                                                       │
│  ○ Internal (Google Workspace only)                 │
│  ● External (Available to any Google account)       │
│                                                       │
│  [CREATE]                                            │
│                                                       │
├──────────────────────────────────────────────────────┤
│                                                       │
│ Step 2: OAuth Consent Screen                         │
│                                                       │
│  App name *                                          │
│  ┌──────────────────────────────────────┐          │
│  │ Nusantara Store                       │          │
│  └──────────────────────────────────────┘          │
│                                                       │
│  User support email *                                │
│  ┌──────────────────────────────────────┐          │
│  │ your.email@gmail.com                  │          │
│  └──────────────────────────────────────┘          │
│                                                       │
│  App logo (optional)                                 │
│  [Upload]                                            │
│                                                       │
│  App domain (optional)                               │
│  Application home page:                              │
│  ┌──────────────────────────────────────┐          │
│  │ https://ukom.test                     │          │
│  └──────────────────────────────────────┘          │
│                                                       │
│  Developer contact information *                      │
│  ┌──────────────────────────────────────┐          │
│  │ your.email@gmail.com                  │          │
│  └──────────────────────────────────────┘          │
│                                                       │
│  [SAVE AND CONTINUE]                                 │
│                                                       │
├──────────────────────────────────────────────────────┤
│                                                       │
│ Step 3: Scopes                                        │
│                                                       │
│  [ADD OR REMOVE SCOPES]                              │
│                                                       │
│  ┌─────────────────────────────────────────────┐   │
│  │ Selected Scopes:                             │   │
│  │ ✓ .../auth/userinfo.email                   │   │
│  │ ✓ .../auth/userinfo.profile                 │   │
│  │ ✓ openid                                     │   │
│  └─────────────────────────────────────────────┘   │
│                                                       │
│  [UPDATE]                                            │
│                                                       │
├──────────────────────────────────────────────────────┤
│                                                       │
│ Step 4: Test Users                                    │
│                                                       │
│  [+ ADD USERS]                                       │
│                                                       │
│  Test Users:                                          │
│  • test.user@gmail.com                               │
│  • developer@gmail.com                               │
│                                                       │
└──────────────────────────────────────────────────────┘
```

---

### 4. Create OAuth Client ID

**Navigation**: APIs & Services > Credentials

**What to click**:

```
┌──────────────────────────────────────────────────────┐
│ Credentials                                           │
├──────────────────────────────────────────────────────┤
│                                                       │
│  [+ CREATE CREDENTIALS ▼]                            │
│    ├─ API key                                        │
│    ├─ OAuth client ID        ← Select this          │
│    └─ Service account key                            │
│                                                       │
├──────────────────────────────────────────────────────┤
│                                                       │
│ Create OAuth client ID                                │
│                                                       │
│  Application type *                                   │
│  ┌──────────────────────────────────────┐          │
│  │ Web application            [▼]        │          │
│  └──────────────────────────────────────┘          │
│                                                       │
│  Options:                                             │
│  • Web application    ← Choose this                  │
│  • Android                                           │
│  • iOS                                               │
│  • Chrome Extension                                  │
│  • Desktop app                                       │
│                                                       │
│  Name *                                              │
│  ┌──────────────────────────────────────┐          │
│  │ Nusantara Store Web Client            │          │
│  └──────────────────────────────────────┘          │
│                                                       │
└──────────────────────────────────────────────────────┘
```

---

### 5. Configure JavaScript Origins

**Important Section**:

```
┌──────────────────────────────────────────────────────┐
│ Authorized JavaScript origins                         │
├──────────────────────────────────────────────────────┤
│                                                       │
│  For use with requests from a browser                │
│                                                       │
│  [+ ADD URI]                                         │
│                                                       │
│  URIs:                                               │
│  ┌──────────────────────────────────────────────┐  │
│  │ 1  https://ukom.test              [×]         │  │
│  ├──────────────────────────────────────────────┤  │
│  │ 2  http://ukom.test               [×]         │  │
│  └──────────────────────────────────────────────┘  │
│                                                       │
│  ⚠️  Important:                                      │
│     • Do NOT include trailing slash                  │
│     • Do NOT include path                            │
│     • Do NOT include port (unless non-standard)      │
│                                                       │
│  ✅ Correct: https://ukom.test                       │
│  ❌ Wrong:   https://ukom.test/                      │
│  ❌ Wrong:   https://ukom.test/auth                  │
│                                                       │
└──────────────────────────────────────────────────────┘
```

---

### 6. Configure Redirect URIs

**Critical Section**:

```
┌──────────────────────────────────────────────────────┐
│ Authorized redirect URIs                              │
├──────────────────────────────────────────────────────┤
│                                                       │
│  For use with requests from a web server             │
│                                                       │
│  [+ ADD URI]                                         │
│                                                       │
│  URIs:                                               │
│  ┌──────────────────────────────────────────────┐  │
│  │ 1  https://ukom.test/auth/google/callback    │  │
│  │                                        [×]    │  │
│  ├──────────────────────────────────────────────┤  │
│  │ 2  http://ukom.test/auth/google/callback     │  │
│  │                                        [×]    │  │
│  └──────────────────────────────────────────────┘  │
│                                                       │
│  ⚠️  Important:                                      │
│     • Must match Laravel route exactly               │
│     • Include full path                              │
│     • Do NOT include trailing slash                  │
│                                                       │
│  ✅ Correct: https://ukom.test/auth/google/callback │
│  ❌ Wrong:   https://ukom.test/auth/google/callback/│
│  ❌ Wrong:   https://ukom.test/auth/google          │
│  ❌ Wrong:   https://ukom.test                       │
│                                                       │
└──────────────────────────────────────────────────────┘
```

**After clicking CREATE**:

```
┌──────────────────────────────────────────────────────┐
│ OAuth client created                                  │
├──────────────────────────────────────────────────────┤
│                                                       │
│  Your Client ID                                       │
│  ┌──────────────────────────────────────────────┐  │
│  │ 123456789-abc123xyz.apps.googleusercontent   │  │
│  │ .com                                    [📋]  │  │
│  └──────────────────────────────────────────────┘  │
│                                                       │
│  Your Client Secret                                   │
│  ┌──────────────────────────────────────────────┐  │
│  │ GOCSPX-your_secret_here_abc123        [📋]   │  │
│  └──────────────────────────────────────────────┘  │
│                                                       │
│  ⚠️  Copy these values now!                          │
│                                                       │
│  [DOWNLOAD JSON]    [OK]                             │
│                                                       │
└──────────────────────────────────────────────────────┘
```

---

### 7. Final Credentials Page

**After setup is complete**:

```
┌──────────────────────────────────────────────────────────────┐
│ Credentials                                                   │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  OAuth 2.0 Client IDs                                        │
│                                                               │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ Name                    │ Creation   │ Type            │ │
│  ├────────────────────────────────────────────────────────┤ │
│  │ Nusantara Store Web     │ Jan 1      │ Web application │ │
│  │ Client                  │ 2025       │                 │ │
│  │ [🔑] [📝] [🗑️]          │            │                 │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                               │
│  Icons:                                                       │
│  🔑 = Download JSON                                          │
│  📝 = Edit                                                   │
│  🗑️ = Delete                                                 │
│                                                               │
└──────────────────────────────────────────────────────────────┘
```

**Click the pencil icon (📝) to edit**:

```
┌──────────────────────────────────────────────────────┐
│ Edit OAuth client                                     │
├──────────────────────────────────────────────────────┤
│                                                       │
│  Client ID (read-only)                               │
│  123456789-abc123xyz.apps.googleusercontent.com      │
│                                                       │
│  Client Secret                                        │
│  GOCSPX-your_secret_here_abc123      [REGENERATE]    │
│                                                       │
│  Authorized JavaScript origins                        │
│  • https://ukom.test                                 │
│  • http://ukom.test                                  │
│  [+ ADD URI]                                         │
│                                                       │
│  Authorized redirect URIs                             │
│  • https://ukom.test/auth/google/callback           │
│  • http://ukom.test/auth/google/callback            │
│  [+ ADD URI]                                         │
│                                                       │
│  [SAVE]     [CANCEL]                                 │
│                                                       │
└──────────────────────────────────────────────────────┘
```

---

## 🎯 Quick Reference Card

### What Goes Where

| Location | Value | Example |
|----------|-------|---------|
| **JavaScript Origins** | | |
| Format | `protocol://domain` | `https://ukom.test` |
| Trailing slash? | ❌ NO | Not `https://ukom.test/` |
| Path? | ❌ NO | Not `https://ukom.test/auth` |
| Port? | Only if non-standard | `http://localhost:8000` |
| | | |
| **Redirect URIs** | | |
| Format | `protocol://domain/path` | `https://ukom.test/auth/google/callback` |
| Trailing slash? | ❌ NO | Not `.../callback/` |
| Must match Laravel? | ✅ YES | Exact match required |
| Case sensitive? | ✅ YES | Use lowercase |

---

## 🔍 Where to Find Your Credentials

### After Creating OAuth Client

**Option 1: Modal Dialog**
- Appears immediately after clicking "CREATE"
- Shows Client ID and Secret
- Click 📋 icon to copy
- **Important**: Copy now, modal won't appear again!

**Option 2: Credentials List**
- Go to: APIs & Services > Credentials
- Find your OAuth 2.0 Client ID
- Click pencil icon (📝)
- Client ID shown at top
- Client Secret shown with "REGENERATE" button

**Option 3: Download JSON**
- Click 🔑 icon in credentials list
- Downloads JSON file with credentials
- **Warning**: Keep this file secure!

---

## 🎨 Visual Checklist

```
Setup Checklist:
┌───────────────────────────────────────────┐
│                                            │
│  Google Cloud Console:                     │
│  ☑ Project created                         │
│  ☑ OAuth consent screen configured         │
│    ├─ App name added                       │
│    ├─ Support email added                  │
│    ├─ Scopes selected                      │
│    └─ Test users added                     │
│  ☑ OAuth Client ID created                 │
│    ├─ Web application selected             │
│    ├─ JavaScript origins added             │
│    └─ Redirect URIs added                  │
│                                            │
│  Laravel .env:                              │
│  ☑ GOOGLE_CLIENT_ID added                  │
│  ☑ GOOGLE_CLIENT_SECRET added              │
│  ☑ GOOGLE_REDIRECT_URI added               │
│  ☑ APP_URL matches origins                 │
│                                            │
│  Testing:                                   │
│  ☑ Migration run (google_id column)        │
│  ☑ Can click Google button                 │
│  ☑ Redirects to Google                     │
│  ☑ Can select account                      │
│  ☑ Redirects back successfully             │
│  ☑ User logged in                          │
│                                            │
└───────────────────────────────────────────┘
```

---

## 📸 Common Error Screens

### redirect_uri_mismatch Error

```
┌─────────────────────────────────────────┐
│  Google                                  │
├─────────────────────────────────────────┤
│                                          │
│  Error 400: redirect_uri_mismatch       │
│                                          │
│  The redirect URI in the request:       │
│  https://ukom.test/auth/google/callback │
│                                          │
│  does not match the ones authorized     │
│  for the OAuth client.                  │
│                                          │
│  [BACK TO SITE]                         │
│                                          │
└─────────────────────────────────────────┘
```

**Fix**: Add exact URI to Google Console

### Access Blocked Error

```
┌─────────────────────────────────────────┐
│  Google                                  │
├─────────────────────────────────────────┤
│                                          │
│  Access blocked: This app's request     │
│  is invalid                              │
│                                          │
│  You can't sign in because this app     │
│  sent an invalid request.                │
│                                          │
│  Learn more about this error             │
│                                          │
└─────────────────────────────────────────┘
```

**Fix**: Add email to test users or publish app

---

## 💡 Pro Tips

1. **Copy-Paste Carefully**: Use the 📋 copy button in Google Console
2. **No Spaces**: Trim whitespace from credentials
3. **Match Protocol**: Use same http/https in both places
4. **Wait 5 Minutes**: Changes take time to propagate
5. **Clear Cache**: Both browser and Laravel config cache
6. **Use Incognito**: Test with fresh session
7. **Check Logs**: Laravel logs show detailed errors
8. **Multiple Environments**: Create separate OAuth clients for dev/prod

---

## 🔗 Navigation Quick Links

| Need to | Go to |
|---------|-------|
| Create project | [console.cloud.google.com](https://console.cloud.google.com/) > New Project |
| Configure consent | APIs & Services > OAuth consent screen |
| Create credentials | APIs & Services > Credentials > Create > OAuth client ID |
| Edit credentials | APIs & Services > Credentials > Click pencil icon |
| Add test users | APIs & Services > OAuth consent screen > Test users |
| View API usage | APIs & Services > Dashboard |

---

This visual guide should help you navigate Google Cloud Console with confidence!
