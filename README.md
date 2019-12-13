# bogosoft/configuration

This library contains contracts and implementations for working with application configurations.

## Requirements

- PHP 7.4+
- `bogosoft/core`

## Installation

```bash
composer require bogosoft/configuration
```

## Usage

```php
#
# Start with an array of values.
#
$values = [
    'database:customers:host'     => 'db.example.com',
    'database:customers:password' => '12345',
    'database:customers:port'     => '3306',
    'database:customers:username' => 'root'
];

#
# Convert the array into a configuration.
#
$config = new Bogosoft\Configuration\SimpleConfiguration($values);

#
# Values can be access from the configuration as if it were an array.
#
$port = $config->get('database:customers:port'); // Returns, '3306'.
```
