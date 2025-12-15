# Libraflow Render Deployment Guide

## Overview
This guide will help you deploy your Libraflow Laravel application to Render.com with PostgreSQL database.

## Pre-Deployment Checklist ✅

### Environment Configuration
- ✅ Production environment file created (`.env.production`)
- ✅ Render configuration file updated (`render.yaml`)
- ✅ PostgreSQL connection settings configured
- ✅ Redis cache configuration set up

### Security Enhancements
- ✅ Security headers middleware implemented
- ✅ Production logging configured (LOG_LEVEL=error)
- ✅ HTTPS/SSL configuration verified
- ✅ Admin user seeder updated for secure deployment

### Build & Deployment
- ✅ Dockerfile optimized for Render deployment
- ✅ Build script configured for Vite assets
- ✅ Startup script with conditional seeding

## Deployment Steps

### 1. Push to Git Repository
Ensure your code is pushed to your Git repository:
```bash
git add .
git commit -m "Prepare for Render deployment"
git push origin main
```

### 2. Create Render Account & Connect Repository
1. Go to [Render.com](https://render.com)
2. Sign up or log in
3. Connect your GitHub/GitLab repository
4. Select your Libraflow repository

### 3. Create Database Service
1. In Render dashboard, create a new **PostgreSQL** database
2. Note the connection details provided by Render
3. Copy the database host, port, database name, username, and password

### 4. Create Redis Service
1. Create a new **Redis** service
2. Note the Redis connection details
3. Copy the host and port information

### 5. Deploy Web Service
1. Create a new **Web Service**
2. Choose **Docker** as the runtime
3. Use the existing `Dockerfile` in your repository
4. Configure environment variables (Render will auto-populate database vars):

#### Required Environment Variables:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com
DB_CONNECTION=pgsql
CACHE_DRIVER=redis
REDIS_HOST=your-redis-host
REDIS_PORT=6379
QUEUE_CONNECTION=database
SESSION_DRIVER=database
LOG_LEVEL=error
SEED_ADMIN_USER=false
SEED_REAL_BOOKS=false
SEED_SYSTEM_SETTINGS=false
```

### 6. Database Configuration
Render automatically provides these database environment variables:
- `DB_HOST`
- `DB_PORT` 
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### 7. Deployment Process
The deployment will automatically:
1. Build the Docker container
2. Run database migrations
3. Link storage directories
4. Start Apache server

## Post-Deployment Tasks

### 1. Database Migration
The migration should run automatically during deployment. If not, access your service shell and run:
```bash
php artisan migrate --force
```

### 2. Create Admin User (Optional)
If you need to create an admin user:
```bash
# Set the environment variable SEED_ADMIN_USER=true in Render dashboard
# Or run manually:
php artisan db:seed --class=Database\\Seeders\\AdminUserSeeder
```

**Default admin credentials (if created):**
- Email: `admin@libraflow.com`
- Password: Will be generated and displayed in logs

### 3. Verify Deployment
1. Visit your application URL
2. Test basic functionality
3. Check logs in Render dashboard for any errors
4. Verify database connectivity

## Security Features Implemented

### 1. Security Headers
The application now includes:
- X-Frame-Options: DENY
- X-Content-Type-Options: nosniff
- X-XSS-Protection: 1; mode=block
- Strict-Transport-Security: max-age=31536000; includeSubDomains
- Content-Security-Policy
- Referrer-Policy

### 2. Production Configuration
- Debug mode disabled
- Error logging only
- Secure session configuration
- HTTPS enforcement

### 3. Database Security
- Prepared statements enforced
- Strict SQL mode enabled
- Secure password generation for admin users

## Environment Variables Reference

| Variable | Description | Value |
|----------|-------------|-------|
| `APP_ENV` | Application environment | `production` |
| `APP_DEBUG` | Debug mode | `false` |
| `APP_URL` | Application URL | `https://your-app.onrender.com` |
| `DB_CONNECTION` | Database driver | `pgsql` |
| `CACHE_DRIVER` | Cache driver | `redis` |
| `QUEUE_CONNECTION` | Queue driver | `database` |
| `SESSION_DRIVER` | Session driver | `database` |
| `LOG_LEVEL` | Logging level | `error` |
| `SEED_ADMIN_USER` | Auto-create admin | `false` |
| `SEED_REAL_BOOKS` | Seed sample books | `false` |
| `SEED_SYSTEM_SETTINGS` | Seed system settings | `false` |

## Troubleshooting

### Common Issues:

1. **Database Connection Failed**
   - Verify database service is running
   - Check environment variables
   - Ensure database migrations completed

2. **Build Failures**
   - Check Docker build logs
   - Verify all dependencies are properly configured
   - Ensure build.sh script runs successfully

3. **Permission Issues**
   - Verify storage and bootstrap/cache permissions
   - Check that web server can write to necessary directories

4. **Asset Loading Issues**
   - Ensure Vite build completed successfully
   - Check public/build directory exists
   - Verify manifest.json is present

### Logs Location
- Application logs: Render dashboard → Your Service → Logs
- Apache logs: `/var/log/apache2/` in container
- Laravel logs: `storage/logs/` directory

## Monitoring & Maintenance

### 1. Regular Tasks
- Monitor application performance
- Check database performance
- Review error logs regularly
- Keep dependencies updated

### 2. Backup Strategy
- Enable automated database backups in Render
- Set up log rotation
- Monitor disk usage

### 3. Scaling Considerations
- Monitor CPU and memory usage
- Consider upgrading Render plan if needed
- Implement caching strategies for better performance

## Support
If you encounter issues:
1. Check the logs in Render dashboard
2. Verify environment variables
3. Test database connectivity
4. Review the deployment checklist above

Your Libraflow application is now ready for production deployment on Render! 🚀
