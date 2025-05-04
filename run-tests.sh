#!/bin/bash

echo "Running Laravel Tests..."
php artisan test

# Run with coverage if you have XDebug installed
# php artisan test --coverage 