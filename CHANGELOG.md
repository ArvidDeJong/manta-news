# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.1] - 2025-08-27

### Added

- Versie informatie toegevoegd aan composer.json voor betere package management

### Changed

- Package versioning geïmplementeerd volgens semantic versioning standaarden

## [0.1.0] - 2025-07-29

### Added

- Database migrations voor News, Newscat en Newscatjoin models
  - `2024_07_29_080047_create_news_table.php` - Hoofdtabel voor nieuwsartikelen
  - `2024_07_29_080148_create_newscats_table.php` - Categorieën met hiërarchische ondersteuning
  - `2024_07_29_080220_create_newscatjoins_table.php` - Many-to-many pivot table
- Model events voor automatisch vastleggen van gebruikersinformatie in alle models
- Livewire component registratie in NewsServiceProvider
  - News componenten: list, create, read, update, upload
  - Newscat componenten: list, create, read, update, upload
- Auth import toegevoegd aan alle models voor staff guard ondersteuning

### Changed

- Models geoptimaliseerd met correcte Eloquent relaties
  - News model: BelongsToMany relatie naar Newscat via pivot table
  - Newscat model: Hiërarchische parent/children relaties
  - Newscatjoin model: BelongsTo relaties naar News en Newscat
- Volledige fillable velden toegevoegd aan alle models
- Verbeterde casts voor boolean en datetime velden
- Query scopes toegevoegd voor betere performance (active, byLocale, byCompany)
- MODULE_TEMPLATE.md bijgewerkt met model events en Auth import
- Model namespaces gecorrigeerd in Livewire componenten

### Fixed

- Redundante getDataAttribute methoden verwijderd (Laravel cast doet dit automatisch)
- Onjuiste relatie implementaties vervangen door proper Eloquent relaties
- Database indexes toegevoegd voor betere query performance
- Unique constraints toegevoegd aan pivot table om duplicates te voorkomen

### Technical

- Laravel 12 compatibiliteit
- Livewire v3 ondersteuning
- FluxUI componenten integratie
- Manta CMS traits implementatie (HasUploadsTrait, HasTranslations)
- Staff guard authenticatie voor audit trail
