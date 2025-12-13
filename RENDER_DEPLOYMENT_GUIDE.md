# Render Deployment Guide for LibraFlow

This guide will walk you through deploying your Laravel application to Render with PostgreSQL database.

## Prerequisites

- GitHub account
- Render account (free tier available)
- Your application code pushed to GitHub

## Step 1: Prepare Your Repository

1. **Ensure your code is pushed to GitHub:**
   ```bash
   git add .
   git commit -m "Prepare for Render deployment"
   git push origin main
   ```

2. **Verify these files exist in your repository:**
   - `render.yaml` - Already configured
   - `Dockerfile` - Already configured for PostgreSQL
   - `build.sh` - Already configured for build process

## Step 2: Create Render Services

### 2.1 Create PostgreSQL Database

1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click **New +** → **PostgreSQL**
3. Configure your database:
   - **Name**: `libraflow-db`
   - **Plan**: Free tier (or paid for production)
   - **Region**: Choose closest to your users
4. Click **Create Database**
5. Wait for the database to be ready (usually 2-3 minutes)
6. Copy the connection details from the dashboard

### 2.2 Create Redis Instance

1. Click **New +** → **Redis**
2. Configure Redis:
   - **Name**: `libraflow-redis`
   - **Plan**: Starter (free tier available)
   - **Region**: Same as your database
3. Click **Create Instance**
4. Wait for Redis to be ready

### 2.3 Create Web Service

1. Click **New +** → **Web Service**
2. Connect your GitHub repository:
   - Choose your repository containing the LibraFlow code
   - Click **Connect**
3. Configure the web service:
   - **Name**: `libraflow`
   - **Environment**: Docker
   - **Region**: Same as database/Redis
   - **Branch**: `main`
   - **Root Directory**: Leave empty (use root)
4. Click **Create Web Service**

## Step 3: Configure Environment Variables

### 3.1 Database Variables

From your PostgreSQL dashboard, set these environment variables in your web service:

```
DB_CONNECTION=pgsql
DB_HOST=<your-database-host>
DB_PORT=5432
DB_DATABASE=<your-database-name>
DB_USERNAME=<your-database-user>
DB_PASSWORD=<your-database-password>
```

### 3.2 Redis Variables

From your Redis dashboard, set these environment variables:

```
REDIS_HOST=<your-redis-host>
REDIS_PORT=6379
REDIS_PASSWORD=<your-redis-password>
```

### 3.3 Application Variables

Set these application variables:

```
APP_NAME=libraFlow
APP_ENV=production
APP_DEBUG=false
APP_URL=https://<your-app-name>.onrender.com
CACHE_DRIVER=redis
SESSION_DRIVER=database
QUEUE_CONNECTION=database
LOG_LEVEL=error
SESSION_LIFETIME=120
BCRYPT_ROUNDS=12
SEED_ADMIN_USER=false
SEED_REAL_BOOKS=false
SEED_SYSTEM_SETTINGS=false
```

**Important**: Generate a new APP_KEY for production:
```bash
php artisan key:generate --force
```
Copy the output and set it as `APP_KEY` environment variable.

## Step 4: Deploy

1. **Trigger Deployment**: Render will automatically detect your `render.yaml` and start building
2. **Monitor Build Logs**: Watch the build process in the dashboard
3. **Wait for Completion**: Build and deployment usually take 5-10 minutes

## Step 5: Post-Deployment Configuration

### 5.1 Run Database Migrations

After successful deployment, your app will automatically run migrations. If you need to manually run them:

1. Go to your web service dashboard
2. Click **Shell**
3. Run:
   ```bash
   php artisan migrate --force
   ```

### 5.2 Seed Initial Data (Optional)

If you need to create initial admin user and sample data:

1. In the Shell, run:
   ```bash
   # Create admin user
   php artisan db:seed --class=Database\\Seeders\\AdminUserSeeder --force
   
   # Add sample books (optional)
   php artisan db:seed --class=Database\\Seeders\\RealBooksSeeder --force
   
   # Add system settings (recommended)
   php artisan db:seed --class=Database\\Seeders\\SystemSettingsSeeder --force
   ```

### 5.3 Create Admin User Manually

If seeder doesn't work, create admin manually:

1. In the Shell, run:
   ```bash
   php artisan tinker
   ```
2. Then execute:
   ```php
   $user = new App\Models\User();
   $user->name = 'Admin';
   $user->email = 'admin@example.com';
   $user->password = Hash::make('password123');
   $user->role = 'admin';
   $user->email_verified_at = now();
   $user->save();
   exit
   ```

## Step 6: Test Your Deployment

1. **Visit Your App**: Go to `https://<your-app-name>.onrender.com`
2. **Test Registration**: Create a new account
3. **Test Login**: Use admin credentials
4. **Test Core Features**:
   - Book browsing
   - Book borrowing
   - Book returning
   - User management (if admin)
   - Analytics dashboard

## Troubleshooting

### Common Issues

1. **Database Connection Failed**:
   - Verify database credentials in environment variables
   - Check if database is in "Available" state
   - Ensure network connectivity

2. **Build Fails**:
   - Check build logs for specific errors
   - Verify all files are committed to GitHub
   - Check Dockerfile syntax

3. **Migration Errors**:
   - Ensure database is accessible
   - Check for PostgreSQL compatibility issues
   - Review migration files

4. **Permission Errors**:
   - Check file permissions in storage directory
   - Verify Apache can write to storage

### Build Logs Location

View detailed logs in your web service dashboard under **Logs** section.

### Database Logs

Check PostgreSQL logs in your database dashboard under **Logs** section.

## Performance Optimization

### For Production Use

1. **Upgrade Plan**: Consider paid tiers for better performance
2. **Enable Caching**: Redis is already configured
3. **Monitor Usage**: Use Render's monitoring tools
4. **Backup Strategy**: Set up regular database backups

### Cache Configuration

Your app is configured to use Redis for caching. This improves performance significantly.

## Security Considerations

1. **HTTPS**: Render automatically provides SSL certificates
2. **Environment Variables**: Never commit sensitive data to GitHub
3. **Database Security**: Use strong passwords
4. **Regular Updates**: Keep dependencies updated

## Backup and Recovery

### Database Backups

1. Use Render's automated backups (if available)
2. Or use pg_dump manually:
   ```bash
   pg_dump "postgresql://user:pass@host:port/db" > backup.sql
   ```

### Application Backups

1. Code is backed up in GitHub
2. File uploads should be stored in external storage (S3, etc.)

## Support and Maintenance

### Monitoring

1. **Application Logs**: Check web service logs regularly
2. **Database Performance**: Monitor query performance
3. **Error Tracking**: Implement error tracking service

### Updates

1. **Code Updates**: Push to GitHub and Render will auto-deploy
2. **Security Updates**: Keep dependencies updated
3. **Database Updates**: Test migrations before deploying

## Additional Resources

- [Render Documentation](https://render.com/docs)
- [Laravel Deployment Guide](https://laravel.com/docs/deployment)
- [PostgreSQL on Render](https://render.com/docs/databases/postgresql)

---

**Need Help?**

If you encounter issues during deployment:

1. Check the build logs for specific error messages
2. Verify all environment variables are set correctly
3. Ensure your database and Redis instances are running
4. Test locally with production settings first

Good luck with your deployment! 🚀
