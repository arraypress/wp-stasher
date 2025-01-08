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

You can use either the Stasher class directly or the utility functions:

```php
// Using the Stasher class
use ArrayPress\WP\Stasher\Stasher;

// Capture all parameters from a filter 
Stasher::stash( 'affwp_get_affiliate_rate' );  

// Use the values later 
$rate = Stasher::get( 'affwp_get_affiliate_rate', 0 ); // Get first parameter 

// Or using utility functions
stash_filter( 'affwp_get_affiliate_rate' );
$rate = get_stashed( 'affwp_get_affiliate_rate', 0 );
```

### Utility Functions

The package provides convenient utility functions for all operations:

```php
// Stash filter values
stash_filter( 'my_filter', ['param1', 'param2'], 'first', ['param1'] );

// Get stashed values
$value = get_stashed( 'my_filter', 'param1' );

// Check if filter has values
if ( has_stashed( 'my_filter' ) ) {
    // Use values...
}

// List all stashed filters
$filters = list_stashed();

// Get all stashed values
$all_values = get_all_stashed();

// Clear specific or all filters
clear_stashed( 'my_filter' );
clear_stashed(); // Clears all
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

// Or using utility functions
stash_filter( 'my_plugin_filter' );
$first_param = get_stashed( 'my_plugin_filter', 0 );
```

### Named Parameters

```php
// Store with named parameters for better readability
stash_filter( 'affwp_get_affiliate_rate', [ 'rate', 'affiliate_id', 'type', 'reference' ] );

// Access by name 
$rate = get_stashed( 'affwp_get_affiliate_rate', 'rate' ); 
$type = get_stashed( 'affwp_get_affiliate_rate', 'type' );
```

### Storage Modes

```php
// Store every time the filter runs (default) 
stash_filter( 'my_filter' );

// Store only the first time the filter runs 
stash_filter( 'my_filter', [], 'first' );

// Store only if no values exist yet 
stash_filter( 'my_filter', [], 'empty' );
```

### Required Parameters

```php
// Store only when 'reference' parameter has a value 
stash_filter( 'affwp_get_affiliate_rate', [ 'rate', 'affiliate_id', 'type', 'reference' ], 'always', [ 'reference' ] ); 

// Store only when both rate and reference have values
stash_filter(
    'affwp_get_affiliate_rate',
    [ 'rate', 'affiliate_id', 'type', 'reference' ],
    'always',
    [ 'rate', 'reference' ]
);

// With positional parameters, using indexes
stash_filter(
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
    // Using utility function
    stash_filter(
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
    // Get the stored rate using utility function
    $rate = get_stashed( 'affwp_get_affiliate_rate', 'rate' );
    
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
if ( has_stashed( 'my_filter' ) ) {
    // Use the values
}

// Get all stored filter names
$filters = list_stashed();

// Get all stored values
$all_values = get_all_stashed();

// Clear specific filter
clear_stashed( 'my_filter' );

// Clear all stored values
clear_stashed();
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

## License

Licensed under the GPLv2 or later license.

## Support

- [Issue Tracker](https://github.com/arraypress/stasher/issues)