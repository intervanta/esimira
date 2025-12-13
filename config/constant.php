<?php

return [
    'image_path' => env('BACKEND_URL', 'http://127.0.0.1:8001/storage'),
    
    // You can add other constants here
    'frontend' => env('FRONTEND_URL', 'http://127.0.0.1:8000'),
    'app_name' => env('APP_NAME', 'Esimira'),

    'convenience_percentage' => env('CONVENIENCE_PERCENT', 0.03),
    'gst_percentage' => env('GST_PERCENT', 0.18),






];