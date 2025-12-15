# 500 Server Error Troubleshooting Guide

## 🚨 **IMMEDIATE DEBUGGING STEPS**

### Step 1: Test Database Connection
**Visit this URL on your live site:**
```
https://libraflow-kb0k.onrender.com/debug-test
```

This will tell us:
- ✅ If database connection is working
- ✅ If tables exist
- ✅ General system status

### Step 2: Create Admin Account (If Database Works)
**If debug test passes, create admin account:**
```
https://libraflow-kb0k.onrender.com/create-admin-simple
```

### Step 3: Run Database Seeder
**After creating admin, seed your database:**
```
https://libraflow-kb0k.onrender.com/admin/database-seeder
```

## 🔧 **Common 500 Error Causes & Solutions**

### 1. **Database Connection Issues**
- **Symptom**: "Database connection failed"
- **Solution**: Check environment variables match Render's provided values
- **Action**: Verify DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD

### 2. **Missing Database Tables**
- **Symptom**: "Table doesn't exist"
- **Solution**: Run migrations
- **Action**: Check if migrations ran automatically on deployment

### 3. **File Permissions**
- **Symptom**: "Permission denied" errors
- **Solution**: Check storage and bootstrap/cache permissions
- **Action**: Verify Docker startup script set proper permissions

### 4. **Missing Dependencies**
- **Symptom**: "Class not found" errors
- **Solution**: Ensure Composer dependencies are installed
- **Action**: Check Docker build process completed

### 5. **Environment Variables**
- **Symptom**: Configuration errors
- **Solution**: Verify all required env variables are set
- **Action**: Cross-check with `.env.render` file

## 📋 **Quick Diagnostic Questions**

**Answer these to help identify the issue:**

1. **What exactly does the 500 error say?**
   - Is it a blank white page?
   - Does it show a specific error message?
   - Does it mention "database" or "connection"?

2. **When did this start?**
   - Right after deployment?
   - After making changes?
   - Randomly?

3. **Can you access the debug route?**
   - Try: `https://libraflow-kb0k.onrender.com/debug-test`
   - What does it show?

## 🛠️ **Immediate Actions**

### Action 1: Check Render Logs
1. Go to your Render dashboard
2. Click on your libraflow service
3. Click "Logs" tab
4. Look for error messages

### Action 2: Verify Environment Variables
Ensure these match exactly in Render:
```
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=pgsql
DB_HOST=dpg-d4uin59r0fns73939jbg-a
DB_DATABASE=libraflow_aa5l
DB_USERNAME=libraflow_aa5l_user
DB_PASSWORD=iluAm4TPp6l1vuPYBTcKUHr1Hgg4ld66
```

### Action 3: Test Database Connection
Visit: `https://libraflow-kb0k.onrender.com/debug-test`

## 🚀 **If Database Connection Works**

1. **Create Admin**: `/create-admin-simple`
2. **Seed Database**: `/admin/database-seeder`
3. **Test Login**: Use created admin credentials

## 🚀 **If Database Connection Fails**

1. **Check Environment Variables** in Render dashboard
2. **Verify Database Status** in Render dashboard
3. **Check Render logs** for specific error messages

## 📞 **Next Steps**

**Please try the debug route first and let me know what it shows!**
