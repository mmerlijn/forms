# Installation

composer require mmerlijn/forms

De migrations en view files worden automatisch ingeladen. Handig is om de config wel publiseren.

## Publishing config file
php artisan vendor:publish --provider="mmerlijn\FormServiceProvider" -tag=forms-config

## permissions maken en toekennen aan user_id = 1
php artisan forms:permissions