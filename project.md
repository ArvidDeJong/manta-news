# Project Documentation: Manta News Form Module

## Overview

The **darvis/manta-news** module is a Laravel package designed to integrate with the **darvis/manta-laravel-flux-cms** system. This module provides functionality for managing news forms and associated submissions.

## Technical Specifications

### Framework & Versions

- **Laravel**: ^12.0
- **PHP**: ^8.2
- **Livewire**: v3 (via manta-laravel-flux-cms)
- **FluxUI**: For UI components
- **Tailwind CSS**: v4 for styling

### Package Information

- **Name**: darvis/manta-news
- **Type**: Laravel Library Package
- **License**: MIT
- **Namespace**: `Darvis\Mantanews`

## Project Structure

```
manta-news/
├── config/
│   └── news.php          # Configuration file
├── database/
│   └── migrations/
│       ├── 2024_07_02_161428_create_newss_table.php
│       └── 2024_07_02_162629_create_newssubmissions_table.php
├── resources/
│   └── views/
│       └── index.blade.php            # Basic view template
├── routes/
│   └── web.php                        # Route definitions
├── src/
│   ├── Models/
│   │   ├── news.php      # Main model for news forms
│   │   └── newsSubmission.php # Model for submissions
│   └── newsServiceProvider.php # Service provider
├── composer.json                      # Package configuration
└── README.md                         # Basic documentation
```

## Core Functionality

### 1. news Model

The main model for managing news forms.

**Important features:**

- **Multilingual support** via `HasTranslations` trait
- **File uploads** via `HasUploadsTrait` trait
- **Soft deletes** for data integrity
- **JSON data field** for flexible configuration

**Database fields:**

- Basic CMS fields (created_by, updated_by, company_id, etc.)
- Content fields (title, subtitle, content, excerpt)
- SEO fields (seo_title, seo_description, slug)
- Specific fields (data as JSON)

### 2. newsSubmission Model

Model for managing news form submissions.

**Important features:**

- **File uploads** for attachments
- **Soft deletes** for data preservation
- **Extended news details** storage
- **JSON data field** for extra information

**Database fields:**

- Personal data (firstname, lastname, email, phone, etc.)
- Address data (address, zipcode, city, country)
- Form specific (company, subject, comment)
- Technical fields (ip, internal_news, newsletters)

## Integration with Manta Laravel Flux CMS

### Service Provider Configuration

The `newsServiceProvider` handles:

1. **Configuration merge**: Loads package configuration into Laravel
2. **Asset publishing**:
   - Configuration files to `config/news.php`
   - Database migrations to `database/migrations/`
3. **Auto-loading**: Migrations are automatically loaded

### Manta Traits Integration

The module uses specific Manta traits:

- **`HasUploadsTrait`**: For file management via the CMS
- **`HasTranslations`**: For multilingual content support

### Livewire Components (CMS Integration)

The routes file shows integration with Livewire v3 components:

**For news:**

- `newsList`: Overview of news forms
- `newsCreate`: Create new news form
- `newsUpdate`: Edit news form
- `newsRead`: View news form details
- `newsUpload`: File management
- `newsSettings`: Module settings

**For newsSubmission:**

- `newsSubmissionList`: Overview of submissions
- `newsSubmissionCreate`: New submission
- `newsSubmissionUpdate`: Edit submission
- `newsSubmissionRead`: Submission details
- `newsSubmissionUpload`: Attachment management
- `newsSubmissionSettings`: Submission settings

## Configuration

### Package Configuration (`config/news.php`)

```php
return [
    'enabled' => true,                    // Module on/off
    'route_prefix' => 'news', // URL prefix
    'database' => [
        'table_prefix' => '',        // Database table prefix
    ],
];
```

### Route Configuration

- **Middleware**: `['staff']` - Only for CMS users
- **Prefix**: Configurable via config file
- **Naming**: Dynamic based on module configuration

## Database Schema

### newss Table

Main table for news forms with:

- CMS standard fields (audit trail, multi-tenancy)
- Content management fields
- SEO optimization fields
- Flexible data storage via JSON

### newssubmissions Table

Submissions table with:

- Extended news information
- Form-specific fields
- IP tracking for security
- Flexible data storage via JSON

## Deployment & Installation

### Composer Installation

```bash
composer require darvis/manta-news
```

### Publish Configuration

```bash
php artisan vendor:publish --tag=news-config
php artisan vendor:publish --tag=news-migrations
```

### Database Migration

```bash
php artisan migrate
```

## Development & Testing

### Development Dependencies

- **orchestra/testbench**: ^9.0 - For package testing
- **phpunit/phpunit**: ^10.0 - Unit testing framework

### PSR-4 Autoloading

- **Source**: `Darvis\Mantanews\` → `src/`
- **Tests**: `Darvis\Mantanews\Tests\` → `tests/`

## Collaboration with Manta CMS

### CMS Integration Points

1. **Service Provider**: Automatic registration via Laravel package discovery
2. **Middleware**: Use of CMS staff middleware for security
3. **Traits**: Reuse of CMS functionality (uploads, translations)
4. **Livewire**: Full integration with CMS interface patterns
5. **Database**: Consistent schema with CMS standards

### Module Configuration in CMS

The CMS uses module configuration for:

- Dynamic route naming
- Menu integration
- Access rights management
- UI component rendering

## Future Extensions

### Planned Features (Disabled)

In the ServiceProvider, preparations have been made for:

- **Routes loading**: Currently disabled
- **Views loading**: Prepared for frontend integration

### Extensibility

- **JSON data fields**: Flexible configuration options
- **Trait system**: Easy extension of functionality
- **Livewire components**: Modular UI development

## Conclusion

The manta-news module is a well-structured Laravel package that integrates seamlessly with the Manta Laravel Flux CMS system. It follows Laravel best practices and makes optimal use of the CMS infrastructure for a consistent user experience and developer-friendly architecture.
