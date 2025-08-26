<?php

declare(strict_types=1);

namespace Darvis\MantaNews\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use OpenAI\Laravel\Facades\OpenAI;

class BlogWriterService
{
    /**
     * Chat model voor tekstgeneratie (override via config/env indien gewenst).
     * Voorbeeld: config('manta-news.openai_chat_model', env('OPENAI_CHAT_MODEL', 'gpt-4o-mini'))
     */
    protected string $chatModel;

    /**
     * Minimale en maximale woorden voor de content.
     */
    protected int $minWords = 450;
    protected int $maxWords = 900;

    /**
     * Ondersteunde image sizes voor gpt-image-1 / dall-e-3:
     * - 1024x1024   (vierkant)
     * - 1024x1536   (staand)
     * - 1536x1024   (liggend)
     * - auto        (laat model kiezen)
     *
     * ❌ Oudere waarden zoals 256x256 of 512x512 worden niet ondersteund.
     */
    protected array $allowedImageSizes = ['1024x1024', '1024x1536', '1536x1024', 'auto'];

    public function __construct(?string $chatModel = null)
    {
        $this->chatModel = $chatModel
            ?? config('manta-news.openai_chat_model', env('OPENAI_CHAT_MODEL', 'gpt-4o-mini'));
    }

    /**
     * Genereer blogvelden met OpenAI (titel, subtitel, excerpt, content, image_prompt).
     *
     * @param  array{
     *   topic:string,              // onderwerp of werktitel
     *   audience?:string|null,     // doelgroep, bv. "mkb in NL"
     *   tone?:string|null,         // bv. "nuchter", "informeel"
     *   lang?:string|null,         // "nl" of "en"
     *   min_words?:int|null,       // minimum woorden voor content
     *   max_words?:int|null        // maximum woorden voor content
     * } $opts
     * @return array{
     *   title:string,
     *   subtitle:string,
     *   excerpt:string,
     *   content:string,
     *   image_prompt:string|null
     * }
     */
    public function generate(array $opts): array
    {
        $opts = array_merge([
            'audience'  => 'Nederlandse ondernemers',
            'tone'      => 'nuchter en informeel',
            'lang'      => 'nl',
            'min_words' => $this->minWords,
            'max_words' => $this->maxWords,
        ], $opts);

        $system = <<<SYS
Je bent een Nederlandse marketing copywriter. Schrijf helder, concreet en zonder poeha.
Lever UITSLUITEND JSON, geen uitleg, geen markdown.
JSON keys: title, subtitle, excerpt, content, image_prompt.
- title: max 70 tekens, pakkend en SEO-vriendelijk
- subtitle: max 120 tekens, vult de titel aan
- excerpt: 30–60 woorden, samenvatting zonder HTML
- content: {$opts['min_words']}-{$opts['max_words']} woorden, scanbaar met <h2>/<h3>, korte alinea's, opsommingen (<ul>, <ol>), eenvoudige HTML (<p>, <strong>, <em>, <a>)
- image_prompt: 1 zin die een realistisch sfeerbeeld beschrijft (geen tekst in beeld), geschikt voor generatieve beeldtools
Schrijf in taalcode "{$opts['lang']}" met toon: {$opts['tone']}.
SYS;

        $user = [
            'topic'    => (string) $opts['topic'],
            'audience' => (string) $opts['audience'],
        ];

        $response = OpenAI::chat()->create([
            'model'    => $this->chatModel,
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user',   'content' => json_encode($user, JSON_UNESCAPED_UNICODE)],
            ],
            'temperature' => 0.7,
        ]);

        $raw = $response->choices[0]->message->content ?? '';
        $data = $this->decodeJsonSafely($raw);

        // Validatie van de velden (fail-safe)
        $validated = Validator::validate($data, [
            'title'        => ['required', 'string', 'max:120'],
            'subtitle'     => ['nullable', 'string', 'max:200'],
            'excerpt'      => ['required', 'string', 'min:80', 'max:600'],
            'content'      => ['required', 'string', 'min:100'],
            'image_prompt' => ['nullable', 'string', 'max:400'],
        ]);

        // Defaults
        $validated['subtitle']     ??= '';
        $validated['image_prompt'] ??= $this->defaultImagePrompt((string) $opts['topic']);

        return Arr::only($validated, ['title', 'subtitle', 'excerpt', 'content', 'image_prompt']);
    }

    /**
     * Genereer een afbeelding en sla op op disk. Retourneert pad + publieke URL.
     * Probeert eerst gpt-image-1 (kan base64 of URL geven). Valt terug op dall-e-3 (URL).
     *
     * @return array{path:string,url:string}
     */
    public function generateImageToStorage(
        string $prompt,
        string $size = '1024x1024',
        string $disk = 'public',
        string $dir = 'blog'
    ): array {
        $size = $this->normalizeImageSize($size);

        // 1) Probeer gpt-image-1 (vereist Verified org + images-scope)
        try {
            $res = OpenAI::images()->create([
                'model'  => 'gpt-image-1',
                'prompt' => $prompt,
                'size'   => $size,
                'n'      => 1,
            ]);
        } catch (\Throwable $e) {
            // 2) Fallback: DALL·E 3 (meestal zonder extra verificatie; geeft URL)
            $res = OpenAI::images()->create([
                'model'  => 'dall-e-3',
                'prompt' => $prompt,
                'size'   => $size,
                'n'      => 1,
            ]);
        }

        $data = $res->data[0] ?? null;

        // Binaire data ophalen (b64_json of via URL)
        if ($data && !empty($data->b64_json)) {
            $binary = base64_decode($data->b64_json);
        } elseif ($data && !empty($data->url)) {
            $binary = Http::timeout(30)->retry(2, 300)->get($data->url)->throw()->body();
        } else {
            throw new \RuntimeException('Geen image data ontvangen van OpenAI.');
        }

        // Bestandsnaam veilig opbouwen
        $filename = $this->makeFileName('ai', 'png');
        $path = trim($dir, '/') . '/' . $filename;
        dd($path);

        Storage::disk($disk)->put($path, $binary);

        return [
            'path' => $path,
            'url'  => Storage::disk($disk)->url($path), // => /storage/... (na `php artisan storage:link`)
        ];
    }

    /**
     * Combinatie-helper: genereer tekst + bewaar afbeelding in één call.
     *
     * @param array $opts Zie generate()
     * @return array{
     *   title:string,
     *   subtitle:string,
     *   excerpt:string,
     *   content:string,
     *   image_prompt:string|null,
     *   image?:array{path:string,url:string}
     * }
     */
    public function generateFull(array $opts, bool $withImage = true, string $imageSize = '1024x1024'): array
    {
        $text = $this->generate($opts);

        if ($withImage && !empty($text['image_prompt'])) {
            try {
                $img = $this->generateImageToStorage($text['image_prompt'], $imageSize, 'public', 'blog');
                $text['image'] = $img;
            } catch (\Throwable $e) {
                // In productie kun je hier logging doen; laat tekst doorgaan zonder image
                // \Log::warning('Image generation failed: '.$e->getMessage());
            }
        }

        return $text;
    }

    /* ============================
     *          Helpers
     * ============================ */

    protected function decodeJsonSafely(string $raw): array
    {
        $raw = trim($raw);

        // Directe decode
        $data = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            return $data;
        }

        // Probeer JSON-blok te extraheren (als er per ongeluk extra tekst mee komt)
        if (preg_match('/\{.*\}/sU', $raw, $m)) {
            $data = json_decode($m[0], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                return $data;
            }
        }

        // Laatste redmiddel: minimale skeleton
        return [
            'title'        => 'Concept titel',
            'subtitle'     => '',
            'excerpt'      => 'Samenvatting kon niet automatisch geparset worden. Pas deze handmatig aan.',
            'content'      => '<p>De inhoud kon niet automatisch geparset worden. Vul dit aan.</p>',
            'image_prompt' => null,
        ];
    }

    protected function normalizeImageSize(string $size): string
    {
        return in_array($size, $this->allowedImageSizes, true) ? $size : '1024x1024';
    }

    protected function makeFileName(string $prefix = 'ai', string $ext = 'png'): string
    {
        return $prefix . '-' . now()->format('Ymd-His') . '-' . Str::lower(Str::random(8)) . '.' . ltrim($ext, '.');
    }

    protected function defaultImagePrompt(string $topic): string
    {
        return "Realistische redactionele foto in natuurlijk licht gerelateerd aan '{$topic}' bij een mkb-omgeving; geen tekst in beeld; hoge resolutie; scherpe focus.";
    }
}
