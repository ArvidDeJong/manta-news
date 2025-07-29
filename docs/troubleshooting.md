# Troubleshooting Guide

This guide helps you resolve common issues with the Manta News Form package.

## Common Issues

### Package Not Found

If you get a "Package not found" error:

```bash
composer clear-cache
composer install
```

**Possible causes:**

- Composer cache is corrupted
- Package repository is not accessible
- Network connectivity issues

### Migrations Not Running

If migrations fail to run:

```bash
php artisan migrate:status
php artisan migrate --force
```

**Possible causes:**

- Database connection issues
- Permission problems
- Conflicting migration files

**Solutions:**

1. Check database connection in `.env`
2. Verify database user permissions
3. Check for existing tables with same names

### Routes Not Working

If routes are not accessible:

1. Check if you're logged in as staff user
2. Verify middleware configuration
3. Clear route cache: `php artisan route:clear`

**Debug steps:**

```bash
# List all routes to verify they're registered
php artisan route:list | grep news

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Configuration Not Loading

If configuration changes aren't applied:

```bash
php artisan config:clear
php artisan config:cache
```

**Common issues:**

- Configuration cached with old values
- Syntax errors in config file
- Environment variables not loaded

### Email Notifications Not Working

If emails are not being sent:

1. **Check email configuration:**

   ```bash
   php artisan config:show mail
   ```

2. **Verify SMTP settings in `.env`:**

   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=your-smtp-host
   MAIL_PORT=587
   MAIL_USERNAME=your-username
   MAIL_PASSWORD=your-password
   MAIL_ENCRYPTION=tls
   ```

3. **Test email functionality:**
   ```bash
   php artisan tinker
   Mail::raw('Test email', function($msg) {
       $msg->to('test@example.com')->subject('Test');
   });
   ```

### Database Connection Issues

If you're experiencing database problems:

1. **Check database configuration:**

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

2. **Test database connection:**
   ```bash
   php artisan tinker
   DB::connection()->getPdo();
   ```

### File Upload Issues

If file uploads are not working:

1. **Check storage permissions:**

   ```bash
   chmod -R 755 storage/
   chown -R www-data:www-data storage/
   ```

2. **Verify storage link:**

   ```bash
   php artisan storage:link
   ```

3. **Check upload limits in `php.ini`:**
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   max_execution_time = 300
   ```

## Debug Mode

Enable debug mode in your `.env` file for detailed error messages:

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

**Important:** Never enable debug mode in production!

## Logging

Check Laravel logs for detailed error information:

```bash
tail -f storage/logs/laravel.log
```

## Performance Issues

### Slow Queries

If the admin interface is slow:

1. **Add database indexes:**

   ```sql
   CREATE INDEX idx_newss_email ON manta_newss(email);
   CREATE INDEX idx_newss_created_at ON manta_newss(created_at);
   CREATE INDEX idx_newss_company_id ON manta_newss(company_id);
   ```

2. **Enable query logging:**
   ```php
   DB::enableQueryLog();
   // Your code here
   dd(DB::getQueryLog());
   ```

### Memory Issues

If you're running out of memory:

1. **Increase PHP memory limit:**

   ```ini
   memory_limit = 512M
   ```

2. **Use pagination for large datasets:**
   ```php
   $newss = News::paginate(25);
   ```

## Common Error Messages

### "Class 'Darvis\Mantanews\Models\news' not found"

**Solution:**

```bash
composer dump-autoload
php artisan clear-compiled
```

### "SQLSTATE[42S02]: Base table or view not found"

**Solution:**

```bash
php artisan migrate
```

### "Route [news.index] not defined"

**Solution:**

1. Clear route cache: `php artisan route:clear`
2. Verify service provider is registered
3. Check if routes are properly loaded

### "Access denied for user"

**Solution:**

1. Check database credentials in `.env`
2. Verify database user permissions
3. Test connection manually

## Getting Help

If you're still experiencing issues:

1. **Check the logs:**

   - Laravel log: `storage/logs/laravel.log`
   - Web server logs (Apache/Nginx)
   - Database logs

2. **Gather information:**

   - Laravel version: `php artisan --version`
   - PHP version: `php --version`
   - Package version: Check `composer.lock`

3. **News support:**
   - Email: info@arvid.nl
   - Include error messages and logs
   - Describe steps to reproduce the issue

## Preventive Measures

### Regular Maintenance

```bash
# Weekly maintenance script
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload
```

### Backup Strategy

1. **Database backups:**

   ```bash
   mysqldump -u username -p database_name > backup.sql
   ```

2. **File backups:**
   ```bash
   tar -czf storage_backup.tar.gz storage/
   ```

### Monitoring

Set up monitoring for:

- Database performance
- Email delivery rates
- Error rates in logs
- Storage usage

## Next Steps

- [Learn about usage](usage.md)
- [Understand configuration](configuration.md)
- [View database schema](database.md)
