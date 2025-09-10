# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.2] - 2025-09-10

### Added

- Automatic module settings import during installation process
- OpenAI functionality moved to reusable NewsTrait for better code organization
- Enhanced upload handling for OpenAI generated images in update process

### Changed

- Faker usage now controlled via USE_FAKER environment variable instead of APP_ENV
- OpenAI image generation logic centralized in NewsTrait for consistency
- Install command now automatically imports module settings

### Removed

- Version number removed from composer.json (following Laravel package best practices)
- Redundant OpenAI methods removed from NewsCreate component

## [1.1.1] - 2025-08-27

### Added

- Version information added to composer.json for better package management
- OpenAI integration for automated content generation
  - AI-powered news article generation with title, excerpt, and content
  - Automated image generation using OpenAI DALL-E
  - Configurable AI prompts and descriptions
  - Integration with MantaOpenai service

### Changed

- Package versioning implemented following semantic versioning standards

## [0.1.0] - 2025-07-29

### Added

- Database migrations for News, Newscat and Newscatjoin models
  - `2024_07_29_080047_create_news_table.php` - Main table for news articles
  - `2024_07_29_080148_create_newscats_table.php` - Categories with hierarchical support
  - `2024_07_29_080220_create_newscatjoins_table.php` - Many-to-many pivot table
- Model events for automatic user information logging in all models
- Livewire component registration in NewsServiceProvider
  - News components: list, create, read, update, upload
  - Newscat components: list, create, read, update, upload
- Auth import added to all models for staff guard support

### Changed

- Models optimized with proper Eloquent relationships
  - News model: BelongsToMany relationship to Newscat via pivot table
  - Newscat model: Hierarchical parent/children relationships
  - Newscatjoin model: BelongsTo relationships to News and Newscat
- Complete fillable fields added to all models
- Improved casts for boolean and datetime fields
- Query scopes added for better performance (active, byLocale, byCompany)
- MODULE_TEMPLATE.md updated with model events and Auth import
- Model namespaces corrected in Livewire components

### Fixed

- Redundant getDataAttribute methods removed (Laravel cast handles this automatically)
- Incorrect relationship implementations replaced with proper Eloquent relationships
- Database indexes added for better query performance
- Unique constraints added to pivot table to prevent duplicates

### Technical

- Laravel 12 compatibility
- Livewire v3 support
- FluxUI components integration
- Manta CMS traits implementation (HasUploadsTrait, HasTranslations)
- Staff guard authentication for audit trail
