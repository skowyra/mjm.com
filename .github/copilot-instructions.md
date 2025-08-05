# Copilot Instructions for mjm.com Drupal 11 Project

<!-- Use this file to provide workspace-specific custom instructions to Copilot. For more details, visit https://code.visualstudio.com/docs/copilot/copilot-customization#_use-a-githubcopilotinstructionsmd-file -->

## Project Overview
This is a Drupal 11 website project for mjm.com. The project uses the recommended Composer-based setup with DDEV for local development and modern development practices.

## Development Environment
- Use DDEV for local development (Docker-based)
- DDEV commands should be prefixed with `ddev` (e.g., `ddev drush`, `ddev composer`)
- Access site at https://mjm.com.ddev.site
- Database and other services are managed by DDEV containers

## Development Guidelines
- Follow Drupal coding standards and best practices
- Use Composer for dependency management
- Place custom modules in `web/modules/custom/`
- Place custom themes in `web/themes/custom/`
- Use proper Drupal API functions and hooks
- Follow object-oriented programming principles where applicable
- Implement proper caching strategies
- Ensure accessibility compliance (WCAG 2.1 AA)
- Write secure code following Drupal security best practices

## Project Structure
- `web/` - Document root containing Drupal core and public files
- `vendor/` - Composer dependencies
- `config/` - Configuration management files
- `drush/` - Drush configuration
- `scripts/` - Build and deployment scripts

## Coding Standards
- Use PSR-4 autoloading for custom classes
- Follow Drupal's coding standards for PHP, CSS, and JavaScript
- Use modern PHP features (PHP 8.1+ compatible)
- Implement proper error handling and logging
- Write comprehensive documentation and comments

## Performance Considerations
- Optimize database queries
- Implement proper caching layers
- Minimize external dependencies
- Use lazy loading where appropriate
- Optimize images and assets
