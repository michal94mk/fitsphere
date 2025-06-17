<?php

return [
    // API Exceptions
    'api_call_failed' => 'Service temporarily unavailable. Please try again later.',
    'spoonacular_api_failed' => 'Recipe service is currently unavailable. Please try again later.',
    'external_service_error' => 'External service error occurred. Please try again.',
    
    // Email Exceptions
    'email_sending_failed' => 'Failed to send email. Please try again or contact support.',
    'email_service_unavailable' => 'Email service is temporarily unavailable.',
    
    // Rate Limit Exceptions
    'rate_limit_exceeded' => 'Too many requests. Please wait :retry_after seconds before trying again.',
    'service_rate_limit' => 'Service rate limit exceeded. Please try again later.',
    
    // Validation Exceptions (user-facing)
    'validation_failed' => 'The provided data is invalid.',
    'invalid_input' => 'Please check your input and try again.',
    
    // General user-facing errors
    'something_went_wrong' => 'Something went wrong. Please try again.',
    'service_unavailable' => 'Service is temporarily unavailable.',
    'unauthorized_access' => 'You are not authorized to perform this action.',
    'resource_not_found' => 'The requested resource was not found.',
    
    // Authentication errors
    'authentication_required' => 'Please log in to continue.',
    'session_expired' => 'Your session has expired. Please log in again.',
    
    // Permission errors
    'insufficient_permissions' => 'You do not have permission to perform this action.',
    'access_denied' => 'Access denied.',
]; 