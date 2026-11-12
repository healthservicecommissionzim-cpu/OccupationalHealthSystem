# Google Maps API Key Setup Guide

## Current Issue: RefererNotAllowedMapError

Your Google Maps API key is currently restricted to specific domains and doesn't include your local development environment (`http://127.0.0.1:8000`).

## Quick Fix for Local Development

### Option 1: Add Local Development to Allowed Referrers (Recommended)

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Navigate to **APIs & Services** → **Credentials**
3. Click on your API key: `AIzaSyCa0Bfxbhl60_Xk7wEU1nfdxMGjHqKneGA`
4. Under **Website restrictions**, click **Add an item**
5. Add these referrers:
   ```
   http://127.0.0.1:8000/*
   http://localhost:8000/*
   ```
6. Click **Save**

### Option 2: Temporarily Remove Restrictions (For Development Only)

⚠️ **Warning:** Only use this temporarily during development!

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Navigate to **APIs & Services** → **Credentials**
3. Click on your API key
4. Under **Application restrictions**, select **None**
5. Click **Save**

**Remember to re-enable restrictions before deploying to production!**

---

## Production Setup (Current Configuration)

Your current production domain is already configured:
```
https://injury.hsc.org.zw/*
```

This is correct and should remain.

---

## Recommended Setup (Both Environments)

Add all necessary domains to the allowed referrers list:

```
# Production
https://injury.hsc.org.zw/*

# Local Development
http://127.0.0.1:8000/*
http://localhost:8000/*
```

---

## Required Google Maps APIs

Make sure these APIs are enabled in your project:

1. **Maps JavaScript API** ✅ (Required)
2. **Places API** ✅ (For location search - if needed later)
3. **Geocoding API** ⚠️ (Optional - for future address → coordinates conversion)

### How to Enable APIs:

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Navigate to **APIs & Services** → **Library**
3. Search for the API you need
4. Click **Enable**

---

## Testing After Configuration

1. **Clear your browser cache** or use Incognito mode
2. Restart your Laravel development server:
   ```bash
   # Stop current server (Ctrl+C)
   php artisan serve
   ```
3. Access the map:
   ```
   http://127.0.0.1:8000/incidents/hotspot-mapping
   ```

4. **Check the browser console** (F12 → Console):
   - ✅ **Success:** No errors, map loads with markers
   - ❌ **Still failing:** Check the exact error message

---

## Troubleshooting

### Error: "RefererNotAllowedMapError"

**Cause:** The API key restrictions don't allow requests from your current domain

**Solution:** Follow Option 1 or 2 above

### Error: "ApiNotActivatedMapError"

**Cause:** Required Google Maps APIs are not enabled

**Solution:**
1. Enable **Maps JavaScript API** in Google Cloud Console
2. Wait 1-2 minutes for changes to propagate

### Error: "InvalidKeyMapError"

**Cause:** The API key is invalid or was deleted

**Solution:**
1. Generate a new API key in Google Cloud Console
2. Update your `.env` file (this won't be necessary - key is hardcoded in the view)

### Map Loads But No Markers

**Cause:** Database migration not run or no incidents data

**Solution:**
```bash
php artisan migrate
```

---

## Security Best Practices

✅ **DO:**
- Use HTTP referrer restrictions for website keys
- Separate API keys for development and production
- Regularly rotate API keys
- Monitor API usage in Google Cloud Console

❌ **DON'T:**
- Commit API keys to public repositories (use environment variables)
- Share API keys publicly
- Leave restrictions disabled in production
- Use the same key for multiple unrelated projects

---

## API Key Usage Limits

Google Maps provides a **$200/month free credit**, which covers:
- ~28,000 map loads per month
- ~40,000 Places API requests per month

**Monitor your usage** at: [Google Cloud Console → Billing](https://console.cloud.google.com/billing)

---

## Current Implementation

Your hotspot mapping uses:
- ✅ Modern AdvancedMarkerElement API
- ✅ Zimbabwe boundary restrictions
- ✅ Color-coded incident markers
- ✅ Individual incident plotting
- ✅ Province aggregation markers

**No additional API calls needed beyond map loading!**

---

## Need Help?

1. **Google Maps Platform Documentation:** https://developers.google.com/maps
2. **API Key Documentation:** https://developers.google.com/maps/documentation/javascript/get-api-key
3. **Billing & Quotas:** https://developers.google.com/maps/billing-and-pricing/billing
