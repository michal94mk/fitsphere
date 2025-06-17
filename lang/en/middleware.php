<?php

return [
    // Admin Middleware
    'admin_access_required' => 'Administrator access is required to view this page.',
    'admin_only' => 'This page is only accessible to administrators.',
    
    // Authentication Middleware
    'login_required' => 'Please log in to access this page.',
    'guest_only' => 'This page is only accessible to guests.',
    'already_authenticated' => 'You are already logged in.',
    
    // General middleware messages
    'access_denied' => 'Access denied.',
    'permission_required' => 'You do not have the required permissions.',
    'session_required' => 'A valid session is required.',
    
    // Rate limiting
    'too_many_requests' => 'Too many requests. Please slow down.',
    'rate_limit_message' => 'You have made too many requests. Please wait before trying again.',
    
    // Locale messages
    'locale_set' => 'Language has been changed.',
    'invalid_locale' => 'Invalid language setting.',
    
    // Request tracking
    'request_logged' => 'Request has been logged.',
    'tracking_error' => 'Error occurred while tracking request.',
]; 