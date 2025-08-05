# mjm.com - Drupal 11 Website

A modern Drupal 11 website built with Composer and DDEV for local development.

## Requirements

### For DDEV Development (Recommended)
- [DDEV](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/) 1.21+
- Docker Desktop or equivalent
- Git

### For Traditional Development
- PHP 8.1 or higher
- Composer 2.x
- MySQL 8.0+ or MariaDB 10.6+
- Web server (Apache/Nginx)

## Installation with DDEV (Recommended)

### 1. Start DDEV Environment
```bash
ddev start
```

### 2. Install Drupal (if not already installed)
```bash
ddev drush site:install --site-name="MJM.com" --account-name=admin --account-pass=admin --yes
```

### 3. Access Your Site
- Website: https://mjm.com.ddev.site
- Admin: https://mjm.com.ddev.site/user/login (admin/admin)
- MailHog: https://mjm.com.ddev.site:8026
- phpMyAdmin: https://mjm.com.ddev.site:8037

## DDEV Commands

### Basic Operations
```bash
ddev start          # Start the development environment
ddev stop           # Stop the development environment
ddev restart        # Restart the development environment
ddev describe       # Show project information and URLs
ddev launch         # Open the site in your browser
```

### Development Tasks
```bash
ddev composer install              # Install PHP dependencies
ddev composer update               # Update PHP dependencies
ddev drush cache:rebuild          # Clear Drupal cache
ddev drush config:export          # Export configuration
ddev drush config:import          # Import configuration
ddev ssh                          # SSH into the web container
```

### Database Operations
```bash
ddev export-db --file=backup.sql.gz    # Export database
ddev import-db --file=backup.sql.gz    # Import database
ddev mysql                              # Access MySQL CLI
```

## Traditional Installation (Alternative)

### 1. Install Dependencies
```bash
composer install
```

### 2. Database Setup
Create a MySQL/MariaDB database for the site:
```sql
CREATE DATABASE mjm_drupal;
CREATE USER 'mjm_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON mjm_drupal.* TO 'mjm_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3. Configure Settings
Copy and customize the local settings file:
```bash
cp web/sites/default/settings.local.php web/sites/default/settings.local.php
# Edit the database configuration in settings.local.php
```

### 4. Install Drupal
```bash
./vendor/bin/drush site:install --db-url=mysql://mjm_user:secure_password@localhost/mjm_drupal --site-name="MJM.com" --account-name=admin --account-pass=admin
```

## Project Structure

```
mjm.com/
├── .github/                 # GitHub and Copilot configuration
├── config/                  # Drupal configuration files
├── drush/                   # Drush configuration
├── scripts/                 # Build and deployment scripts
├── vendor/                  # Composer dependencies
├── web/                     # Document root
│   ├── core/               # Drupal core files
│   ├── modules/            # Drupal modules
│   │   └── custom/         # Custom modules
│   ├── profiles/           # Installation profiles
│   ├── sites/              # Site-specific files
│   │   └── default/        # Default site configuration
│   └── themes/             # Drupal themes
│       └── custom/         # Custom themes
├── composer.json           # Composer configuration
└── README.md              # This file
```

## Custom Development

### Creating Custom Modules
Custom modules should be placed in `web/modules/custom/`. Use Drupal Console or Drush to generate module scaffolding:

```bash
drush generate:module
```

### Creating Custom Themes
Custom themes should be placed in `web/themes/custom/`. Generate a theme:

```bash
drush generate:theme
```

## Deployment

### Production Deployment
1. Set up your production environment with proper web server configuration
2. Copy files to production server
3. Install dependencies: `composer install --no-dev --optimize-autoloader`
4. Import configuration: `drush config:import`
5. Clear caches: `drush cache:rebuild`
6. Set proper file permissions

### Environment-Specific Settings
Create environment-specific settings files in `web/sites/default/`:
- `settings.local.php` for local development
- `settings.staging.php` for staging environment
- `settings.production.php` for production environment

## Security

- Keep Drupal core and modules updated
- Use strong passwords and enable two-factor authentication
- Regularly review user permissions
- Monitor security advisories
- Use HTTPS in production
- Implement proper backup strategies

## Support

- [Drupal Documentation](https://www.drupal.org/docs)
- [Drupal Community](https://www.drupal.org/community)
- [Drupal Security Advisories](https://www.drupal.org/security)

## License

This project is proprietary. All rights reserved.
