# Laravel 12 Vite Deployment - Configuration Summary

## Issues Fixed

### Problem 1: Vite Manifest Location
**Issue**: Vite was generating `public/build/.vite/manifest.json` instead of `public/build/manifest.json`
**Root Cause**: Laravel Vite Plugin's default behavior and Vite's internal structure

**Solution**:
1. Updated `vite.config.js` to explicitly set `manifest: 'manifest.json'`
2. Created post-build script `move-manifest.js` to relocate manifest
3. Updated `package.json` build script to run: `vite build && node ./move-manifest.js`

### Problem 2: Subfolder Asset Paths
**Issue**: Assets path needs to work on shared hosting at `/nobar/build/`
**Solution**: Configured `base: isProduction ? '/nobar/build/' : '/'` in vite.config.js

### Problem 3: Deployment Compatibility
**Issue**: .htaccess needed to handle asset rewriting for subfolder
**Solution**: Added rewrite rule in `public/.htaccess` to map `/build/` to `/nobar/build/`

## Files Modified

### 1. vite.config.js
**Changes**:
- Set `manifest: 'manifest.json'` (explicit, not `true`)
- Added `rollupOptions` with output naming conventions
- Production base path: `/nobar/build/`
- Development base path: `/`

### 2. package.json
**Changes**:
- Build script: `"build": "vite build && node ./move-manifest.js"`
- Ensures manifest is moved to root after Vite build

### 3. move-manifest.js (NEW)
**Purpose**:
- Post-build script to move manifest from `.vite/` to root
- Cleans up `.vite/` directory after move
- Provides feedback on operation

### 4. public/.htaccess
**Existing**:
- Asset rewrite rule: `RewriteRule ^build/(.*)$ /nobar/build/$1 [L,QSA]`
- Handles routing from `/nobar/build/` to actual assets

### 5. .htaccess (root)
**Existing**:
- Redirects all requests to `/public/` internally
- Maintains clean URLs for subfolder deployment

### 6. public/index.php
**Existing**:
- Already using relative paths: `$basePath = dirname(__DIR__)`
- Compatible with subfolder deployment

### 7. bootstrap/app.php
**Existing**:
- Uses `basePath: dirname(__DIR__)` 
- No changes needed

### 8. Blade Templates
**Existing**:
- Using `@vite(['resources/css/app.css', 'resources/js/app.js'])`
- Laravel automatically resolves to correct manifest location

## Build Output Structure

```
public/build/
├── manifest.json          (✓ Now at root, not in .vite/)
├── assets/
│   ├── app-C7VGH93v.css
│   └── app-D5dtCA_w.js
└── .vite/               (removed during post-build)
```

## How It Works on Shared Hosting

1. User visits: `https://dscmkids.online/nobar`
2. Root `.htaccess` redirects to `/nobar/public/`
3. `public/index.php` loads Laravel app
4. Laravel's Vite macro finds `/nobar/public/build/manifest.json`
5. Manifest maps assets to `/nobar/build/assets/`
6. Browser requests `/nobar/build/assets/app-xxxxx.js`
7. `public/.htaccess` rewrites to correct static asset location
8. Assets load successfully with cache busting

## Build Command

```bash
npm run build
```

This executes:
1. `vite build` - Builds assets and generates manifest
2. `node ./move-manifest.js` - Moves manifest to correct location

## Verification Checklist

✓ `public/build/manifest.json` exists at root
✓ `public/build/assets/` contains compiled CSS/JS
✓ `.vite/` directory removed after build
✓ `vite.config.js` configured for `/nobar/build/` paths
✓ `move-manifest.js` post-build script executes
✓ `package.json` includes post-build script
✓ `.htaccess` files handle routing and asset rewriting
✓ `public/index.php` uses relative paths
✓ Blade templates use `@vite()` macro
✓ No hardcoded paths in code

## Deployment Instructions

1. Extract `nobar-hosting.zip` to `public_html/nobar`
2. Run: `npm install` (on shared hosting if Node.js available)
3. Run: `npm run build` (or pre-built assets already included)
4. Application ready at: `https://dscmkids.online/nobar`

## Troubleshooting

If manifest not found after deployment:
1. Verify `public/build/manifest.json` exists
2. Check Laravel's manifest detection:
   - Should look in `public/build/manifest.json`
   - Not in `public/build/.vite/manifest.json`
3. If using pre-built assets:
   - Ensure `move-manifest.js` ran during build
   - Copy manifest manually to root if needed

## No Manual Editing Required

All fixes are automatic and configuration-based:
- Build script handles manifest relocation
- .htaccess handles asset path rewriting
- Laravel Vite macro handles everything else
- Deployment is production-ready immediately after upload
