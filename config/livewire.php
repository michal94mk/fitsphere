<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Class Namespace
    |--------------------------------------------------------------------------
    |
    | This value sets the root class namespace for Livewire component classes in
    | your application. This value affects component auto-discovery and
    | the file-loading conventions of your Livewire components.
    |
    */

    'class_namespace' => 'App\\Livewire',

    /*
    |--------------------------------------------------------------------------
    | Livewire View Path
    |--------------------------------------------------------------------------
    |
    | This value sets the path for Livewire component views. This affects
    | file manipulation helpers like Livewire::test().
    |
    */

    'view_path' => resource_path('views/livewire'),

    /*
    |--------------------------------------------------------------------------
    | Livewire Layout
    |--------------------------------------------------------------------------
    |
    | The default layout view that will be used when rendering a component via
    | Route::get('/some-endpoint', SomeComponent::class);. In this case the
    | the view returned by SomeComponent::render() will be wrapped in "layouts.app"
    |
    */

    'layout' => 'layouts.app',

    /*
    |--------------------------------------------------------------------------
    | Lazy Loading Placeholder
    |--------------------------------------------------------------------------
    |
    | Livewire allows you to lazy load components that would otherwise slow down
    | the initial page load. Every component can have a custom placeholder or
    | you can define the default placeholder view for all components below.
    |
    */

    'lazy_placeholder' => null,

    /*
    |--------------------------------------------------------------------------
    | Temporary File Uploads
    |--------------------------------------------------------------------------
    |
    | Livewire handles file uploads by storing uploads in a temporary directory
    | before the file is stored permanently. All file uploads are directed to
    | a global endpoint for temporary storage. The configuration is below:
    |
    */

    'temporary_file_upload' => [
        'disk' => null,        // Example: 'local', 's3'              Default: 'default'
        'rules' => null,       // Example: ['file', 'mimes:png,jpg']  Default: ['required', 'file', 'max:12288'] (12MB)
        'directory' => null,   // Example: 'tmp'                      Default 'livewire-tmp'
        'middleware' => null,  // Example: 'throttle:5,1'             Default: 'throttle:60,1'
        'preview_mimes' => [   // Supported file types for temporary pre-signed file URLs.
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 5, // Max duration (in minutes) before an upload gets invalidated.
        'cleanup' => true,      // Should cleanup temporary uploads older than `max_upload_time`?
    ],

    /*
    |--------------------------------------------------------------------------
    | Render On Redirect
    |--------------------------------------------------------------------------
    |
    | This value determines if Livewire will render before it's redirected or not.
    | Setting this to "false" (default) will mean the render method is never
    | fired when the component is redirected. Setting this to "true" will run the
    | render method but will make redirects slower.
    |
    */

    'render_on_redirect' => false,

    /*
    |--------------------------------------------------------------------------
    | Eloquent Model Binding
    |--------------------------------------------------------------------------
    |
    | Previous versions of Livewire supported binding directly to eloquent model
    | properties using wire:model by default. However, this approach had some
    | security vulnerabilities so we no longer support it out of the box.
    |
    */

    'legacy_model_binding' => false,

    /*
    |--------------------------------------------------------------------------
    | Auto-inject Frontend Assets
    |--------------------------------------------------------------------------
    |
    | By default, Livewire automatically injects its JavaScript and CSS into the
    | <head> and before the closing </body> tag of pages containing Livewire
    | components. By disabling this, you need to use @livewireStyles and
    | @livewireScripts helper directives yourself.
    |
    */

    'inject_assets' => true,

    /*
    |--------------------------------------------------------------------------
    | Navigate (SPA mode)
    |--------------------------------------------------------------------------
    |
    | By default, wire:navigate will only run on links with the same host
    | as the application. Below you can configure this behavior to exclude
    | or include hosts as you wish.
    |
    */

    'navigate' => [
        'show_progress_bar' => true,
        'progress_bar_color' => '#2299dd',

        // All the URLs allowed to be navigated to.
        'include_patterns' => ['/*'],

        // All the URLs that are not allowed to be navigated to.
        'exclude_patterns' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | HTML Purification
    |--------------------------------------------------------------------------
    |
    | Below you can configure how Livewire prevents potentially dangerous user
    | input from being persisted to Livewire components. This is done by running
    | the potentially dangerous data through "HTMLPurifier".
    |
    */

    'purify_html' => true,

    'purify_html_attribute' => 'purify',

    /*
    |--------------------------------------------------------------------------
    | Force Asset URL Scheme
    |--------------------------------------------------------------------------
    |
    | Force HTTPS for Livewire assets in production to prevent mixed content
    | errors when the application is served over HTTPS.
    |
    */

    'asset_url' => config('app.env') === 'production' 
        ? config('app.url') 
        : null,

];
