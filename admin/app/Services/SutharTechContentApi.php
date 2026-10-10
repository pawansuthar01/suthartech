<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class SutharTechContentApi
{
    public function request(): PendingRequest
    {
        $token = (string) config('services.suthartech_content_token');
        abort_if($token === '', 503, 'Set SUTHARTECH_CONTENT_TOKEN in both apps before managing public content.');

        return Http::baseUrl(rtrim(config('services.suthartech_content_url'), '/'))
            ->withToken($token)->acceptJson()->timeout(20);
    }

    public function list(string $type, array $filters = []): array
    {
        return $this->decode($this->request()->get($type, array_filter($filters, fn ($value) => $value !== null && $value !== '')));
    }

    public function fixedPages(): array
    {
        return $this->decode($this->request()->get('fixed-pages'));
    }

    public function saveFixedPage(string $slug, array $data): array
    {
        return $this->decode($this->request()->put("fixed-pages/{$slug}", $data));
    }

    public function get(string $type, int $id): array
    {
        return $this->decode($this->request()->get("{$type}/{$id}"));
    }

    public function save(string $type, array $data, ?int $id = null, ?UploadedFile $image = null): array
    {
        $request = $this->request();
        if ($image) {
            $request = $request->attach('image', $image->getContent(), $image->getClientOriginalName(), ['Content-Type' => $image->getMimeType()]);
        }
        $url = $id ? "{$type}/{$id}" : $type;
        $response = $id ? $request->post($url, $data + ['_method' => 'PUT']) : $request->post($url, $data);

        return $this->decode($response);
    }

    public function delete(string $type, int $id): void
    {
        $this->decode($this->request()->delete("{$type}/{$id}"));
    }

    private function decode(Response $response): array
    {
        if ($response->status() === 422) {
            $errors = $response->json('errors') ?: ['content' => [$response->json('message') ?: 'The content could not be saved.']];
            throw ValidationException::withMessages($errors);
        }
        if ($response->status() === 401) {
            abort(503, 'The private content API token does not match between the client and admin apps.');
        }
        if ($response->status() === 404) {
            abort(404);
        }

        return $response->throw()->json() ?? [];
    }
}
