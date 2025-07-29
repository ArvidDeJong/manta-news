# Usage Guide

This guide explains how to use the Manta News Form package.

## Managing News Forms

The module provides full CRUD functionality for news forms via the Manta CMS:

- **List**: Overview of all news forms
- **Create**: Add new news form
- **Edit**: Modify existing news form
- **View**: View news form details
- **Files**: Upload and manage attachments
- **Settings**: Module-specific configuration

## Managing Submissions

The same applies to news form submissions:

- Complete news details from visitors
- Form-specific information
- File management for attachments
- IP tracking for security
- Automatic email notifications

## Programmatic Usage

### Creating News Forms

```php
use Darvis\Mantanews\Models\news;

// Create new news form
$newsForm = news::create([
    'title' => 'General News Form',
    'subtitle' => 'Get in touch with us',
    'content' => 'Please fill out the form below...',
    'data' => ['required_fields' => ['name', 'email', 'message']]
]);
```

### Handling Submissions

```php
use Darvis\Mantanews\Models\newsSubmission;

// Add submission
$submission = newsSubmission::create([
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'john@example.com',
    'subject' => 'General Inquiry',
    'comment' => 'I would like more information about...'
]);
```

## Frontend Integration

For frontend news forms, you can use the submission model directly:

```php
// In your controller
use Darvis\Mantanews\Models\newsSubmission;
use Illuminate\Http\Request;

public function store(Request $request)
{
    $validated = $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'email' => 'required|email',
        'subject' => 'required|string|max:255',
        'comment' => 'required|string',
    ]);

    newsSubmission::create($validated);

    return response()->json(['message' => 'Message sent successfully']);
}
```

### Frontend Form Example

```html
<form action="/api/news" method="POST">
  @csrf
  <div class="grid grid-cols-2 gap-4">
    <div>
      <label for="firstname">First Name</label>
      <input type="text" name="firstname" id="firstname" required />
    </div>
    <div>
      <label for="lastname">Last Name</label>
      <input type="text" name="lastname" id="lastname" required />
    </div>
  </div>

  <div>
    <label for="email">Email</label>
    <input type="email" name="email" id="email" required />
  </div>

  <div>
    <label for="subject">Subject</label>
    <input type="text" name="subject" id="subject" required />
  </div>

  <div>
    <label for="comment">Message</label>
    <textarea name="comment" id="comment" rows="5" required></textarea>
  </div>

  <button type="submit">Send Message</button>
</form>
```

## Admin Interface

### Accessing the Admin

1. Log in to your Manta CMS admin panel
2. Navigate to the News section
3. Use the interface to manage forms and submissions

### Available Routes

All admin routes are protected with staff middleware:

#### News Form Management Routes

- `GET /news` - News forms overview
- `GET /news/create` - Create new news form
- `GET /news/{id}` - View news form details
- `GET /news/{id}/edit` - Edit news form
- `GET /news/{id}/files` - File management
- `GET /news/settings` - Module settings

## Email Notifications

The package automatically sends email notifications when new submissions are received. Configure email settings in the [configuration file](configuration.md).

## File Uploads

The package supports file uploads for news form submissions. Files are managed through the Manta CMS file management system.

## Next Steps

- [Understand the database schema](database.md)
- [View troubleshooting guide](troubleshooting.md)
- [Learn about API endpoints](api.md)
