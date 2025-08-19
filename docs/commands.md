# Artisan Commands

Dit document beschrijft alle beschikbare artisan commands voor het Manta News package.

## Overzicht

Het Manta News package biedt de volgende artisan commands:

- [`manta-news:install`](#manta-newsinstall) - Installeer het package
- [`manta-news:seed`](#manta-newsseed) - Seed de database met voorbeeldnieuws

---

## `manta-news:install`

Installeert het Manta News package door configuratie en migraties te publiceren.

### Syntax

```bash
php artisan manta-news:install [options]
```

### Opties

| Optie | Beschrijving |
|-------|-------------|
| `--force` | Overschrijf bestaande bestanden |
| `--migrate` | Voer migraties uit na installatie |

### Voorbeelden

```bash
# Basis installatie
php artisan manta-news:install

# Installatie met migraties
php artisan manta-news:install --migrate

# Forceer overschrijven van bestaande bestanden
php artisan manta-news:install --force --migrate
```

### Wat doet dit commando?

1. **Publiceert configuratie** naar `config/manta-news.php`
2. **Publiceert migraties** naar `database/migrations/`
3. **Voert migraties uit** (indien `--migrate` optie gebruikt)
4. **Controleert configuratie** en toont volgende stappen

### Output voorbeeld

```
🚀 Installing Manta News Package...

📝 Publishing configuration files...
   ✅ Configuration published to config/manta-news.php

📦 Publishing migration files...
   ✅ Migrations published to database/migrations/

🗄️  Running database migrations...
   ✅ Migrations completed successfully

⚙️  Setting up default configuration...
   ✅ Configuration file is ready

🎉 Manta News Package installed successfully!

Next steps:
1. Configure your settings in config/manta-news.php
3. Access the news management at: /news (or your configured route)
```

---

## `manta-news:seed`

Vult de database met voorbeeldnieuwsartikelen voor ontwikkeling en testing.

### Syntax

```bash
php artisan manta-news:seed [options]
```

### Opties

| Optie | Beschrijving |
|-------|-------------|
| `--force` | Forceer seeding ook als er al nieuws bestaat |
| `--fresh` | Verwijder bestaande nieuwsitems eerst |

### Voorbeelden

```bash
# Basis seeding
php artisan manta-news:seed

# Verwijder eerst alle bestaande nieuwsitems
php artisan manta-news:seed --fresh

# Forceer seeding zonder bevestiging
php artisan manta-news:seed --force
```

### Wat doet dit commando?

1. **Controleert bestaande data** en vraagt bevestiging indien nodig
2. **Verwijdert bestaande items** (indien `--fresh` optie gebruikt)
3. **Creëert 4 voorbeeldartikelen** met Nederlandse content:
   - Welkom bij ons nieuwe nieuwssysteem
   - Nieuwe functionaliteiten toegevoegd
   - Tips voor effectief nieuwsbeheer
   - Aankomende updates en verbeteringen (draft)

### Voorbeeldartikelen bevatten:

- **Volledige Nederlandse content** met HTML formatting
- **SEO-geoptimaliseerde velden** (titel, beschrijving)
- **Metadata** (auteur, tags, publicatiedatum)
- **Verschillende statussen** (actief/draft)
- **Willekeurige publicatiedatums** (laatste 30 dagen)

### Output voorbeeld

```
🌱 Seeding Manta News...

📰 Creating sample news items...
News items seeded successfully!

🎉 News seeding completed successfully!
   📊 Total news items in database: 4

💡 Tips:
• Use --fresh to start with a clean slate
• Use --force to skip confirmation prompts
• Check your news management interface to see the seeded items
```

### Veiligheidscontroles

Het commando bevat verschillende veiligheidscontroles:

- **Bestaande data controle**: Waarschuwt als er al nieuwsitems bestaan
- **Bevestigingsprompt**: Vraagt bevestiging voordat er data wordt toegevoegd
- **Fresh optie bevestiging**: Dubbele bevestiging bij `--fresh` optie

---

## Veelvoorkomende workflows

### Eerste installatie

```bash
# 1. Installeer het package
php artisan manta-news:install --migrate

# 2. Seed met voorbeelddata
php artisan manta-news:seed
```

### Development reset

```bash
# Reset alle nieuwsdata en start opnieuw
php artisan manta-news:seed --fresh
```

### Production deployment

```bash
# Alleen installatie, geen seeding
php artisan manta-news:install --force --migrate
```

---

## Troubleshooting

### "Class not found" errors

Als je een "Class not found" error krijgt:

```bash
# Clear en rebuild autoloader
composer dump-autoload

# Clear Laravel caches
php artisan config:clear
php artisan cache:clear
```

### Database connection errors

Zorg ervoor dat je database configuratie correct is:

```bash
# Test database connectie
php artisan migrate:status
```

### Permission errors

Bij permission errors tijdens installatie:

```bash
# Fix permissions (Linux/Mac)
sudo chown -R $USER:$USER storage/ bootstrap/cache/
chmod -R 775 storage/ bootstrap/cache/
```

---

## Zie ook

- [Installation Guide](installation.md) - Volledige installatiehandleiding
- [Configuration](configuration.md) - Configuratie-opties
- [Database Schema](database.md) - Database structuur
- [Troubleshooting](troubleshooting.md) - Probleemoplossing
