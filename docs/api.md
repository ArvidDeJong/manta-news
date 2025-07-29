# API Documentation

This document describes the API endpoints and programmatic usage of the Manta News Form package.

## Models

### News Model

The main model for news form submissions.

```php
use Darvis\Mantanews\Models\news;

// Create a new news
$news = news::create([
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'john@example.com',
    'subject' => 'General Inquiry',
    'comment' => 'I would like more information...'
]);

// Find newss
$news = news::find(1);
$newss = news::where('active', true)->get();
$recentNewss = news::latest()->take(10)->get();

// Update news
$news->update([
    'comment_internal' => 'Follow up required'
]);

// Soft delete
$news->delete();
```

## Available Methods

### Query Scopes

```php
// Active newss only
news::active()->get();

// By company
news::where('company_id', 1)->get();

// Recent submissions
news::recent()->get();

// Search by email
news::where('email', 'like', '%@example.com')->get();
```

### Relationships

```php
// Get news with files (if using uploads)
$news = news::with('uploads')->find(1);

// Get news creator
$news = news::with('creator')->find(1);
```

## REST API Endpoints

### Frontend API Routes

Create these routes in your application for frontend integration:

```php
// routes/api.php
use App\Http\Controllers\NewsController;

Route::post('/news', [NewsController::class, 'store']);
Route::get('/news-forms', [NewsController::class, 'forms']);
```

### Example Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Darvis\Mantanews\Models\news;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    /**
     * Store a new news submission
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'comment' => 'required|string',
            'newsletters' => 'boolean',
        ]);

        // Add IP address and timestamp
        $validated['ip'] = $request->ip();
        $validated['active'] = true;

        $news = news::create($validated);

        return response()->json([
            'message' => 'News form submitted successfully',
            'id' => $news->id
        ], 201);
    }

    /**
     * Get available news forms
     */
    public function forms(): JsonResponse
    {
        $forms = news::active()
            ->select('id', 'title', 'subtitle', 'content')
            ->get();

        return response()->json($forms);
    }

    /**
     * Get news by ID
     */
    public function show(int $id): JsonResponse
    {
        $news = news::findOrFail($id);

        return response()->json($news);
    }
}
```

## Validation Rules

### Standard Validation

```php
$rules = [
    'firstname' => 'required|string|max:255',
    'lastname' => 'required|string|max:255',
    'email' => 'required|email|max:255',
    'phone' => 'nullable|string|max:255',
    'company' => 'nullable|string|max:255',
    'subject' => 'required|string|max:255',
    'comment' => 'required|string|max:1000',
    'newsletters' => 'boolean',
];
```

### Extended Validation

```php
$rules = [
    // Basic fields
    'firstname' => 'required|string|max:255',
    'lastname' => 'required|string|max:255',
    'email' => 'required|email|max:255|unique:manta_newss,email',

    // Optional fields
    'phone' => 'nullable|string|max:255',
    'company' => 'nullable|string|max:255',
    'address' => 'nullable|string|max:255',
    'zipcode' => 'nullable|string|max:10',
    'city' => 'nullable|string|max:255',
    'country' => 'nullable|string|max:255',

    // Message fields
    'subject' => 'required|string|max:255',
    'comment' => 'required|string|max:2000',

    // Preferences
    'newsletters' => 'boolean',

    // Custom fields
    'option_1' => 'nullable|string|max:1000',
    'option_2' => 'nullable|string|max:1000',
];
```

## Events

### Model Events

```php
use Darvis\Mantanews\Models\news;

// Listen for news creation
news::created(function ($news) {
    // Send notification email
    Mail::to(config('manta-news.email.default_receivers'))
        ->send(new NewsSubmissionMail($news));
});

// Listen for news updates
news::updated(function ($news) {
    // Log the update
    Log::info('News updated', ['id' => $news->id]);
});
```

## Custom Fields

### Using Option Fields

```php
// Store custom data in option fields
$news = news::create([
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'john@example.com',
    'subject' => 'Product Inquiry',
    'comment' => 'I need more information',
    'option_1' => 'Product A',
    'option_2' => 'Urgent',
    'option_3' => json_encode(['source' => 'website', 'campaign' => 'summer2024'])
]);

// Retrieve custom data
$productInterest = $news->option_1;
$priority = $news->option_2;
$metadata = json_decode($news->option_3, true);
```

### Using JSON Data Field

```php
// Store complex data in JSON field
$news = news::create([
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'john@example.com',
    'subject' => 'Support Request',
    'comment' => 'I need help with...',
    'data' => json_encode([
        'source' => 'news_form',
        'utm_campaign' => 'spring_promotion',
        'user_agent' => request()->userAgent(),
        'referrer' => request()->header('referer'),
        'custom_fields' => [
            'department' => 'sales',
            'priority' => 'high'
        ]
    ])
]);

// Query JSON data
$salesNewss = news::whereJsonContains('data->custom_fields->department', 'sales')->get();
```

## Bulk Operations

### Bulk Insert

```php
$newss = [
    [
        'firstname' => 'John',
        'lastname' => 'Doe',
        'email' => 'john@example.com',
        'subject' => 'Inquiry 1',
        'comment' => 'Message 1',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'firstname' => 'Jane',
        'lastname' => 'Smith',
        'email' => 'jane@example.com',
        'subject' => 'Inquiry 2',
        'comment' => 'Message 2',
        'created_at' => now(),
        'updated_at' => now(),
    ]
];

news::insert($newss);
```

### Bulk Update

```php
// Mark all newss from specific company as processed
news::where('company', 'Example Corp')
    ->update(['comment_internal' => 'Processed by sales team']);
```

## Export Functions

### CSV Export

```php
use League\Csv\Writer;

public function exportNewss()
{
    $newss = news::select([
        'firstname', 'lastname', 'email', 'phone',
        'company', 'subject', 'comment', 'created_at'
    ])->get();

    $csv = Writer::createFromString('');
    $csv->insertOne([
        'First Name', 'Last Name', 'Email', 'Phone',
        'Company', 'Subject', 'Message', 'Date'
    ]);

    foreach ($newss as $news) {
        $csv->insertOne([
            $news->firstname,
            $news->lastname,
            $news->email,
            $news->phone,
            $news->company,
            $news->subject,
            $news->comment,
            $news->created_at->format('Y-m-d H:i:s')
        ]);
    }

    return response($csv->toString())
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', 'attachment; filename="newss.csv"');
}
```

## Security Considerations

### Rate Limiting

```php
// In routes/api.php
Route::middleware(['throttle:10,1'])->group(function () {
    Route::post('/news', [NewsController::class, 'store']);
});
```

### Input Sanitization

```php
use Illuminate\Support\Str;

$validated['comment'] = Str::limit(strip_tags($validated['comment']), 2000);
$validated['subject'] = strip_tags($validated['subject']);
```

### CSRF Protection

```php
// For web forms
<form method="POST" action="/news">
    @csrf
    <!-- form fields -->
</form>
```

## Next Steps

- [Learn about usage](usage.md)
- [Understand configuration](configuration.md)
- [View troubleshooting guide](troubleshooting.md)
