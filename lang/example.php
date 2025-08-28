<?php

return [
    'welcome' => 'Willkommen in unserer Anwendung, :name!',
];

// Usage in php: echo __('messages.welcome', ['name' => 'John']);
// Usage in blade: {{ __('messages.welcome', ['name' => 'John']) }}

// Output: Willkommen in unserer Anwendung, John!
