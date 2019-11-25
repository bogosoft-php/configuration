# bogosoft/configuration

This library contains contracts and implementations for working with application configurations.

Types are based on the `IConfiguration` contract and its derivatives from .NET.

Currently, there is no support for mutating configurations once they have been instantiated.

## Requirements

- PHP 7.1+
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
    'database' => [
        'customers' => [
            'host'     => 'db.example.com',
            'password' => '12345',
            'port'     => '3306',
            'user'     => 'root'
        ]
    ]
];

#
# Convert the array into a configuration.
#
$config = Bogosoft\Configuration\ArrayConfiguration::from($values);

#
# Values can be access from the configuration as if it were an array.
#
$port = $config['database:customers:port']; // Returns, '3306'.

#
# Retrieve just a section of the configuration.
#
$db = $config->getSection('database:customers');

#
# Configuration section values can also be accessed as an array.
#
$port = $db['port']; // Again, returns, '3306'.
```

As can be seen above, the `:` is the section delimiter.