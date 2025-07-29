# Configuration Guide

This guide explains how to configure the Manta News Form package.

## Configuration File

After publishing the configuration, you'll find the file at `config/manta-news.php`:

```php
return [
    // Route prefix for the news module
    'route_prefix' => 'cms/news',

    // Database settings
    'database' => [
        'table_name' => 'manta_newss',
    ],

    // Email settings
    'email' => [
        'from' => [
            'address' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
            'name' => env('MAIL_FROM_NAME', 'Manta News'),
        ],
        'enabled' => true,
        'default_subject' => 'New news form message',
        'default_receivers' => env('MAIL_TO_ADDRESS', 'admin@example.com'),
    ],

    // UI settings
    'ui' => [
        'items_per_page' => 25,
        'show_breadcrumbs' => true,
    ],
];
```

## Configuration Options

### Route Settings

- **`route_prefix`**: The URL prefix for admin routes (default: `cms/news`)

### Database Settings

- **`table_name`**: The name of the main newss table (default: `manta_newss`)

### Email Settings

- **`email.from.address`**: Default sender email address
- **`email.from.name`**: Default sender name
- **`email.enabled`**: Enable/disable email notifications
- **`email.default_subject`**: Default subject for news form emails
- **`email.default_receivers`**: Default recipients for news form submissions

### UI Settings

- **`ui.items_per_page`**: Number of items per page in admin lists (default: 25)
- **`ui.show_breadcrumbs`**: Show/hide breadcrumb navigation (default: true)

## Environment Variables

Add these variables to your `.env` file:

```env
# Email configuration
MAIL_FROM_ADDRESS=noreply@yoursite.com
MAIL_FROM_NAME="Your Site Name"
MAIL_TO_ADDRESS=admin@yoursite.com

# Optional: Custom route prefix
CONTACT_ROUTE_PREFIX=cms/news
```

## Advanced Configuration

### Custom Table Names

If you need to use custom table names:

```php
'database' => [
    'table_name' => 'custom_newss_table',
],
```

### Multiple Recipients

To send emails to multiple recipients:

```php
'email' => [
    'default_receivers' => 'admin@site.com,manager@site.com,support@site.com',
],
```

### Disable Email Notifications

To disable automatic email notifications:

```php
'email' => [
    'enabled' => false,
],
```

## Next Steps

- [Learn about usage](usage.md)
- [Understand the database schema](database.md)
- [View troubleshooting guide](troubleshooting.md)
