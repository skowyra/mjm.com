<?php

/**
 * @file
 * Local development settings for mjm.com.
 */

// Database settings for DDEV development
// DDEV automatically configures the database in settings.ddev.php
// No need to override database settings when using DDEV
// 
// If you need custom database settings for non-DDEV environments,
// uncomment and modify the following:
/*
$databases['default']['default'] = [
  'database' => 'mjm_drupal',
  'username' => 'mjm_user',
  'password' => 'secure_password',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
];
*/

// Disable CSS and JS aggregation for easier development
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

// Enable local development services
$settings['container_yamls'][] = $app_root . '/sites/development.services.yml';

// Show all error messages, with backtrace information
$config['system.logging']['error_level'] = 'verbose';

// Disable internal dynamic page cache (for development only)
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

// Disable render cache (for development only)
$settings['cache']['bins']['render'] = 'cache.backend.null';

// Disable all caching (for development only - use with caution)
// $settings['cache']['bins']['page'] = 'cache.backend.null';

// Allow test modules and themes to be installed
$settings['extension_discovery_scan_tests'] = TRUE;

// Trusted host configuration for DDEV
$settings['trusted_host_patterns'] = [
  '^localhost$',
  '^127\.0\.0\.1$',
  '^mjm\.com\.ddev\.site$',
  '^.+\.ddev\.site$',
];

// Salt for one-time login links, cancel links, form tokens, etc.
// Generate a new salt: https://www.drupal.org/node/2297711
$settings['hash_salt'] = 'change_this_to_a_random_hash_salt_for_security';

// Private file path (create this directory and make it writable)
// $settings['file_private_path'] = '../private';

// Configuration sync directory
$settings['config_sync_directory'] = '../config/sync';
