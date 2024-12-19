# Stasher - WordPress Filter Value Capture Utility

A lightweight utility for WordPress that captures filter values and makes them available for use later in the same request. Perfect for accessing data from early filters that you need to use in later hooks or filters.

## Features

* 🎯 Simple API: Easy to use with just a few methods
* 🔄 Multiple Storage Modes: Store values always, only first time, or only when empty
* 📦 Named Parameters: Store filter parameters with custom names for easy access
* ✅ Required Parameters: Only store values when specific parameters are not empty
* 🗄️ Value Management: List, retrieve, and clear stored values

## Requirements

* PHP 7.4 or later
* WordPress 6.7.1 or later

## Installation

Install via Composer:

```bash
composer require arraypress/wp-stasher
```

## Basic Usage

```php
use ArrayPress\WP\Stasher;

// Capture all parameters from a filter 
Stasher::stash( 'affwp_get_affiliate_rate' );  

// Use the values later 
$rate = Stasher::get( 'affwp_get_affiliate_rate', 0 ); // Get first parameter 
```

## Examples

### Basic Value Capture

```php
// Store all parameters by position
Stasher::stash( 'my_plugin_filter' );

// Access values later 
$first_param = Stasher::get( 'my_plugin_filter', 0 ); 
$second_param = Stasher::get( 'my_plugin_filter', 1 ); 
$all_params = Stasher::get( 'my_plugin_filter');
```

### Named Parameters

```php
// Store with named parameters for better readability
Stasher::stash( 'affwp_get_affiliate_rate', [ 'rate', 'affiliate_id', 'type', 'reference' ] );

// Access by name 
$rate = Stasher::get( 'affwp_get_affiliate_rate', 'rate' ); 
$type = Stasher::get( 'affwp_get_affiliate_rate', 'type' );
```

### Storage Modes

```php
// Store every time the filter runs (default) 
Stasher::stash( 'my_filter');

// Store only the first time the filter runs 
Stasher::stash( 'my_filter', [], 'first' );

// Store only if no values exist yet 
Stasher::stash( 'my_filter', [], 'empty' );
```

### Required Parameters

```php
// Store only when 'reference' parameter has a value 
Stasher::stash( 'affwp_get_affiliate_rate', [ 'rate', 'affiliate_id', 'type', 'reference' ], 'always', [ 'reference' ] ); 

// Store only when both rate and reference have values
Stasher::stash(
    'affwp_get_affiliate_rate',
    [ 'rate', 'affiliate_id', 'type', 'reference' ],
    'always',
    [ 'rate', 'reference' ]
);

// With positional parameters, using indexes
Stasher::stash(
    'some_filter',
    [],      // No named params
    'always',
    [ 0, 2 ]   // First and third parameters must have values
);
```

### Real World Example

```php
/**
 * Capture the affiliate rate from an early filter
 */
add_action( 'init', function() {
    Stasher::stash(
        'affwp_get_affiliate_rate',
        [ 'rate', 'affiliate_id', 'type', 'reference' ],
        'first',
        [ 'rate', 'reference' ]  // Only store when both rate and reference exist
    );
});

/**
 * Use the rate later in a different filter
 */
add_filter( 'affwp_calc_referral_amount', function( $amount ) {
    // Get the stored rate
    $rate = Stasher::get( 'affwp_get_affiliate_rate', 'rate' );
    
    if ( $rate !== null ) {
        // Use the rate in calculations
        $amount = $amount * $rate;
    }
    
    return $amount;
});
```

### Managing Stored Values

```php
// Check if values exist
if ( Stasher::has( 'my_filter' ) ) {
    // Use the values
}

// Get all stored filter names
$filters = Stasher::list();

// Get all stored values
$all_values = Stasher::get_all();

// Clear specific filter
Stasher::clear( 'my_filter' );

// Clear all stored values
Stasher::clear();
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

## License

Licensed under the GPLv2 or later license.

## Support

- [Issue Tracker](https://github.com/arraypress/stasher/issues)