# Deployment Notes

This guide covers a safe production deployment flow for MannaRise.

## Server Requirements

- PHP 8.2 or higher
- Composer 2
- Node.js and NPM for building frontend assets
- MySQL or another Laravel-supported database
- Web server configured to serve Laravel from the `public` directory
- Writable `storage` and `bootstrap/cache` directories

## Environment Checklist

Set these production values in `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

Generate and keep a strong application key:

```bash
php artisan key:generate
```

Configure the real production values for:

- Database connection
- Mail driver
- Queue connection
- Cache store
- Session driver
- Filesystem disk

Do not commit `.env` or real credentials.

## Safe Admin Setup

MannaRise does not ship with public hardcoded admin credentials. Create production admins manually or set private seed variables only on trusted environments:

```env
MANNA_SEED_ADMIN_NAME="MannaRise Admin"
MANNA_SEED_ADMIN_EMAIL="admin@example.com"
MANNA_SEED_ADMIN_PASSWORD="use-a-strong-password"
```

Never use public demo passwords in production.

## Deployment Commands

From the project root:

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

If queues are enabled, restart workers after deployment:

```bash
php artisan queue:restart
```

## Shared Hosting Notes

For shared hosting, the best setup is to point the domain document root to:

```txt
/public
```

If the host forces the document root to `htdocs`, place the Laravel project outside public access when possible and expose only the `public` directory. Avoid exposing `.env`, `storage`, `vendor`, `app`, `database`, or `bootstrap` directly to the web.

## File Permissions

Ensure these directories are writable by the web server user:

```bash
chmod -R ug+rw storage bootstrap/cache
```

On Linux servers, ownership is usually set to the web server user/group, for example:

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
```

## Post-Deployment Checks

After deployment, confirm these routes load correctly:

- `/`
- `/daily`
- `/devotionals`
- `/bible`
- `/login`
- `/dashboard`
- `/admin`

Also check:

- Assets load from `public/build`
- No `public/hot` file exists in production
- `APP_DEBUG=false`
- Database migrations completed successfully
- Admin access works only for authorized users

## Rollback Notes

Before risky deployments, take a database backup. If a deployment fails, roll back the code to the previous commit and restore the database backup if migrations changed data irreversibly.
