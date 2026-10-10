<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SutharTechContentApi;
use App\Mail\CertificationAnnouncement;
use App\Models\NewsletterSubscriber;
use App\Models\CertificationAnnouncement as CertificationAnnouncementRecord;
use App\Services\MailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebsiteContentController extends Controller
{
    private const TYPES = ['blogs', 'projects', 'certifications', 'services', 'feedback'];

    public function __construct(private readonly SutharTechContentApi $api) {}

    public function index(Request $request, string $type): View
    {
        $this->allow($type);
        $data = $this->api->list($type, $request->only('search', 'status', 'page'));

        return view('admin.website-content.index', ['type' => $type, 'items' => $data['data'], 'pagination' => $data]);
    }

    public function create(string $type): View
    {
        $this->allow($type);
        return view('admin.website-content.form', ['type' => $type, 'item' => []]);
    }

    public function store(Request $request, string $type): RedirectResponse
    {
        $this->allow($type);
        $data = $this->data($request, $type);
        $item = $this->api->save($type, $data, null, $request->file('image'));
        $notice = $this->announceCertification($type, $item);

        return to_route('admin.website-content.index', $type)->with('success', $notice ?: 'Public website content saved.');
    }

    public function edit(string $type, int $record): View
    {
        $this->allow($type);
        return view('admin.website-content.form', ['type' => $type, 'item' => $this->api->get($type, $record)]);
    }

    public function update(Request $request, string $type, int $record): RedirectResponse
    {
        $this->allow($type);
        $item = $this->api->save($type, $this->data($request, $type), $record, $request->file('image'));
        $notice = $this->announceCertification($type, $item);

        return to_route('admin.website-content.index', $type)->with('success', $notice ?: 'Public website content updated.');
    }

    public function destroy(string $type, int $record): RedirectResponse
    {
        $this->allow($type);
        $this->api->delete($type, $record);

        return to_route('admin.website-content.index', $type)->with('success', 'Public website content deleted.');
    }

    private function data(Request $request, string $type): array
    {
        $data = $request->except(['_token', '_method', 'image']);
        if ($type === 'projects') {
            $data['technologies'] = array_values(array_filter(array_map('trim', preg_split('/[,\r\n]+/', (string) ($data['technologies'] ?? '')) ?: [])));
            $data['is_featured'] = $request->boolean('is_featured');
        }
        if ($type === 'services') {
            $data['deliverables'] = array_values(array_filter(array_map('trim', preg_split('/[,\r\n]+/', (string) ($data['deliverables'] ?? '')) ?: [])));
        }
        if ($type === 'feedback') {
            $data['client_name'] = $data['client_name'] ?? $data['title'] ?? '';
            unset($data['title'], $data['slug'], $data['image'], $data['meta_title'], $data['meta_description']);
        }

        return $data;
    }

    private function allow(string $type): void
    {
        abort_unless(in_array($type, self::TYPES, true), 404);
    }

    private function announceCertification(string $type, array $item): ?string
    {
        if ($type !== 'certifications' || $item['status'] !== 'active') {
            return null;
        }
        $announcement = CertificationAnnouncementRecord::firstOrCreate(['content_id' => $item['id']], ['status' => 'sending']);
        if (! $announcement->wasRecentlyCreated) {
            return null;
        }

        $subscribers = NewsletterSubscriber::whereNotNull('confirmed_at')->whereNull('unsubscribed_at')->get();
        $sent = 0;
        $failed = 0;
        foreach ($subscribers as $subscriber) {
            if (MailService::deliver(new CertificationAnnouncement($item, $subscriber), $subscriber->email, 'newsletter')) {
                $sent++;
            } else {
                $failed++;
            }
        }
        $announcement->update(['recipient_count' => $sent + $failed, 'status' => $failed ? 'partial' : 'sent', 'sent_at' => now()]);

        return $failed ? "Certification activated; {$sent} email(s) sent and {$failed} failed. Check SMTP settings and application logs." : "Certification activated; announcement sent to {$sent} confirmed subscriber(s).";
    }
}
