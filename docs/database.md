# Database Schema

This document describes the database schema for the Manta News Form package.

## Tables

### newss Table

The main table for storing news form submissions.

| Field              | Type      | Description                 |
| ------------------ | --------- | --------------------------- |
| `id`               | bigint    | Primary key                 |
| `created_at`       | timestamp | Creation date               |
| `updated_at`       | timestamp | Last modification           |
| `deleted_at`       | timestamp | Soft delete                 |
| `created_by`       | string    | User who created the record |
| `updated_by`       | string    | User who updated the record |
| `deleted_by`       | string    | User who deleted the record |
| `company_id`       | integer   | Company ID (multi-tenancy)  |
| `host`             | string    | Host information            |
| `pid`              | integer   | Process ID                  |
| `locale`           | string    | Language locale             |
| `active`           | boolean   | Active status               |
| `sort`             | integer   | Sort order                  |
| `company`          | string    | Company name                |
| `title`            | string    | Title/salutation            |
| `sex`              | string    | Gender                      |
| `firstname`        | string    | First name                  |
| `lastname`         | string    | Last name                   |
| `name`             | string    | Full name                   |
| `email`            | string    | Email address               |
| `phone`            | string    | Phone number                |
| `address`          | string    | Street address              |
| `address_nr`       | string    | House number                |
| `zipcode`          | string    | Postal code                 |
| `city`             | string    | City                        |
| `country`          | string    | Country                     |
| `birthdate`        | date      | Date of birth               |
| `newsletters`      | boolean   | Newsletter subscription     |
| `subject`          | string    | Message subject             |
| `comment`          | text      | Message content             |
| `internal_news`    | string    | Internal news reference     |
| `ip`               | string    | IP address                  |
| `comment_client`   | text      | Client comments             |
| `comment_internal` | text      | Internal comments           |
| `option_1`         | text      | Flexible option field 1     |
| `option_2`         | text      | Flexible option field 2     |
| `option_3`         | text      | Flexible option field 3     |
| `option_4`         | text      | Flexible option field 4     |
| `option_5`         | text      | Flexible option field 5     |
| `option_6`         | text      | Flexible option field 6     |
| `option_7`         | text      | Flexible option field 7     |
| `option_8`         | text      | Flexible option field 8     |
| `administration`   | string    | Administration reference    |
| `identifier`       | string    | Unique identifier           |
| `data`             | longtext  | Additional JSON data        |

## Field Groups

### Core Fields

- `id`, `created_at`, `updated_at`, `deleted_at`

### CMS Tracking Fields

- `created_by`, `updated_by`, `deleted_by`
- `company_id`, `host`, `pid`, `locale`

### Status Fields

- `active`, `sort`

### News Information

- `company`, `title`, `sex`
- `firstname`, `lastname`, `name`
- `email`, `phone`

### Address Information

- `address`, `address_nr`
- `zipcode`, `city`, `country`
- `birthdate`

### Communication

- `newsletters`, `subject`, `comment`
- `internal_news`, `ip`

### Comments

- `comment_client`, `comment_internal`

### Flexible Fields

- `option_1` through `option_8` - Can be used for custom form fields

### System Fields

- `administration`, `identifier`, `data`

## Indexes

The migration creates the following indexes:

- Primary key on `id`
- Index on `deleted_at` (for soft deletes)
- Additional indexes may be added based on query patterns

## Model Relationships

### News Model

```php
use Darvis\Mantanews\Models\news;

// Example usage
$news = news::create([
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'john@example.com',
    'subject' => 'General Inquiry',
    'comment' => 'Hello, I would like more information...'
]);

// Query examples
$activeNewss = news::where('active', true)->get();
$recentNewss = news::orderBy('created_at', 'desc')->limit(10)->get();
$newssByCompany = news::where('company_id', 1)->get();
```

## Data Types

### Boolean Fields

- `active` - Default: true
- `newsletters` - Default: null

### Date Fields

- `birthdate` - Date format (Y-m-d)
- `created_at`, `updated_at`, `deleted_at` - Timestamp format

### Text Fields

- `comment`, `comment_client`, `comment_internal` - TEXT type
- `option_1` through `option_8` - TEXT type
- `data` - LONGTEXT type (for JSON data)

### String Fields

- Most other fields are VARCHAR with appropriate lengths

## Migration File

The database schema is defined in:

```
database/migrations/2024_07_26_091749_create_newss_table.php
```

## Next Steps

- [Learn about usage](usage.md)
- [View troubleshooting guide](troubleshooting.md)
- [Understand configuration](configuration.md)
