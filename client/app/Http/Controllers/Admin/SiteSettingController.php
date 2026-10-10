<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(): View
    {
        $settings = SiteSetting::first() ?? new SiteSetting(['site_name' => 'SutharTech']);

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'contact_email' => ['nullable', 'email', 'max:190'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:190'],
            'default_meta_title' => ['nullable', 'string', 'max:255'],
            'default_meta_description' => ['nullable', 'string', 'max:320'],
            'default_og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'social_links.linkedin' => ['nullable', 'url:http,https', 'max:255'],
            'social_links.github' => ['nullable', 'url:http,https', 'max:255'],
            'social_links.x' => ['nullable', 'url:http,https', 'max:255'],
        ]);
        $data['social_links'] = array_filter($data['social_links'] ?? []);

        $settings = SiteSetting::first() ?? new SiteSetting();
        $oldImage = $settings->default_og_image;
        if ($request->hasFile('default_og_image')) {
            $data['default_og_image'] = $request->file('default_og_image')->store('site', 'public');
        } else {
            unset($data['default_og_image']);
        }
        $settings->fill($data)->save();
        if ($request->hasFile('default_og_image') && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return back()->with('success', 'Site and SEO settings saved.');
    }
}
