# Manta News Form Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/darvis/manta-news.svg?style=flat-square)](https://packagist.org/packages/darvis/manta-news)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

A Laravel package for managing news forms and submissions. This module integrates seamlessly with the **darvis/manta-laravel-flux-cms** system and provides a complete solution for news form management.

## Features

- 📝 **News Form Management**: Full CRUD functionality for news forms
- 📨 **Submissions**: Comprehensive system for managing news form submissions
- 🌍 **Multilingual**: Support for multiple languages via Manta CMS
- 📁 **File Management**: Integrated upload functionality for attachments

```bash
composer require darvis/manta-news
php artisan manta-news:install
```

### Basic Usage

```php
use Darvis\Mantanews\Models\news;

// Create a news submission
$news = news::create([
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'john@example.com',
    'subject' => 'General Inquiry',
    'comment' => 'I would like more information...'
]);
```

## Documentation

For detailed documentation, please see the `/docs` directory:

- 📚 **[Installation Guide](docs/installation.md)** - Complete installation instructions
- ⚙️ **[Configuration](docs/configuration.md)** - Configuration options and settings
- 🚀 **[Usage Guide](docs/usage.md)** - How to use the package
- 🗄️ **[Database Schema](docs/database.md)** - Complete database documentation
- 🔧 **[Troubleshooting](docs/troubleshooting.md)** - Common issues and solutions
- 🔌 **[API Documentation](docs/api.md)** - Programmatic usage and API endpoints

## Requirements

- PHP ^8.2
- Laravel ^12.0
- darvis/manta-laravel-flux-cms

## Integration with Manta CMS

This module is specifically designed for integration with the Manta Laravel Flux CMS:

- **Livewire v3**: All UI components are Livewire components
- **FluxUI**: Consistent design with the CMS
- **Manta Traits**: Reuse of CMS functionality
- **Multi-tenancy**: Support for multiple companies
- **Audit Trail**: Complete logging of changes
- **Soft Deletes**: Safe data deletion

## Support

For support and questions:

- 📧 Email: info@arvid.nl
- 🌐 Website: [arvid.nl](https://arvid.nl)
- 📖 Documentation: See the `/docs` directory for comprehensive guides
- 🐛 Issues: Create an issue in the repository

## Contributing

Contributions are welcome! See [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

## Security

If you discover a security issue, please send an email to info@arvid.nl.

## License

The MIT License (MIT). See [License File](LICENSE.md) for more information.

## Credits

- [Darvis](https://github.com/darvis)
- [All contributors](../../contributors)
