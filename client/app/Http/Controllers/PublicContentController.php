<?php

namespace App\Http\Controllers;

use App\Domain\SEO\Services\SchemaGenerator;
use App\Domain\SEO\Services\SeoManager;
use App\Models\Blog;
use App\Models\Certification;
use App\Models\Project;
use App\Models\SitePage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicContentController extends Controller
{
    public function page(string $slug): View
    {
        $page = SitePage::published()->where('slug', $slug)->firstOrFail();

        return view('pages.page', [
            'page' => $page,
            'seo' => SeoManager::build([
                'title' => $page->meta_title ?: $page->title,
                'description' => $page->meta_description ?: $page->hero_description,
            ]),
        ]);
    }

    public function blogs(): View
    {
        $topic = request()->query('topic');
        $blogs = Blog::published()
            ->when($topic, fn ($query) => $query->where('topic', $topic))
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('pages.blogs', [
            'blogs' => $blogs,
            'recentBlogs' => Blog::published()->orderByDesc('published_at')->limit(5)->get(),
            'topics' => Blog::published()->select('topic')->distinct()->orderBy('topic')->get(),
            'activeTopic' => $topic,
            'seo' => SeoManager::build([
                'title' => 'SutharTech Journal | Product Engineering Insights',
                'description' => 'Practical articles on software engineering, digital products, product decisions, and dependable technology.',
                'robots' => $blogs->total() ? 'index, follow' : 'noindex, nofollow',
            ]),
        ]);
    }

    public function blog(string $slug): View
    {
        $blog = Blog::published()->where('slug', $slug)->firstOrFail();

        return view('pages.blog', [
            'blog' => $blog,
            'bodyHtml' => Str::markdown($blog->body, ['html_input' => 'strip', 'allow_unsafe_links' => false]),
            'seo' => SeoManager::build([
                'title' => $blog->meta_title ?: $blog->title,
                'description' => $blog->meta_description ?: $blog->excerpt,
                'og_type' => 'article',
                'og_image' => $blog->image_url ?: '',
                'twitter_image' => $blog->image_url ?: '',
                'schema_json' => SchemaGenerator::article($blog),
            ]),
        ]);
    }

    public function project(string $slug): View
    {
        $project = Project::published()->where('slug', $slug)->firstOrFail();
        $projectImage = $project->image_path
            ? (str_starts_with($project->image_path, 'images/') ? asset($project->image_path) : asset('storage/'.$project->image_path))
            : '';

        return view('pages.project', [
            'project' => $project,
            'seo' => SeoManager::build([
                'title' => $project->meta_title ?: $project->title,
                'description' => $project->meta_description ?: $project->summary,
                'og_image' => $projectImage,
                'twitter_image' => $projectImage,
                'schema_json' => SchemaGenerator::creativeWork($project->title, $project->summary),
            ]),
        ]);
    }

    public function certifications(): View
    {
        $certifications = Certification::published()->orderBy('sort_order')->orderByDesc('issued_at')->paginate(12);
        return view('pages.certifications', [
            'certifications' => $certifications,
            'seo' => SeoManager::build([
                'title' => 'Company Certificates | SutharTech',
                'description' => 'Browse SutharTech company certificates, review issuer and validity details, and verify a certificate ID.',
                'robots' => $certifications->total() ? 'index, follow' : 'noindex, nofollow',
            ]),
        ]);
    }

    public function verifyCertification(Request $request): View|RedirectResponse
    {
        $validated = $request->validate(['credential_id' => ['required', 'string', 'max:190']]);
        $credentialId = trim($validated['credential_id']);
        $certification = Certification::published()
            ->whereRaw('LOWER(TRIM(credential_id)) = ?', [Str::lower($credentialId)])
            ->first();

        if ($certification) {
            return to_route('certification.show', $certification->slug);
        }

        $certifications = Certification::published()->orderBy('sort_order')->orderByDesc('issued_at')->paginate(12);

        return view('pages.certifications', [
            'certifications' => $certifications,
            'credentialId' => $credentialId,
            'lookupError' => 'No published certificate matched that certificate ID. Check the ID and try again.',
            'seo' => SeoManager::build([
                'title' => 'Company Certificates | SutharTech',
                'description' => 'Browse SutharTech company certificates and verify a certificate ID.',
                'robots' => 'noindex, nofollow',
            ]),
        ]);
    }

    public function certification(string $slug): View
    {
        $certification = Certification::published()->where('slug', $slug)->firstOrFail();

        return view('pages.certification', [
            'certification' => $certification,
            'seo' => SeoManager::build([
                'title' => $certification->meta_title ?: $certification->title,
                'description' => $certification->meta_description ?: $certification->description,
                'og_image' => $certification->image_path ? asset('storage/'.$certification->image_path) : '',
                'twitter_image' => $certification->image_path ? asset('storage/'.$certification->image_path) : '',
                'schema_json' => SchemaGenerator::creativeWork($certification->title, $certification->description ?: ''),
            ]),
        ]);
    }
}
