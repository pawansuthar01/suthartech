<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqDemoSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['page' => 'home', 'question' => 'What does SutharTech build?', 'answer' => 'We build web platforms, mobile products, SaaS systems, AI integrations, workflow automation, and cloud infrastructure.', 'sort_order' => 1],
            ['page' => 'home', 'question' => 'Can I bring an existing product or project brief?', 'answer' => 'Yes. Share your product, current stage, goals, and requirements through the contact form so the team can review the brief.', 'sort_order' => 2],
            ['page' => 'home', 'question' => 'What technologies does the team work with?', 'answer' => 'The site highlights technologies including Laravel, Next.js, React Native, Python, AWS, Docker, PostgreSQL, and related tools. The right stack depends on the project.', 'sort_order' => 3],
            ['page' => 'home', 'question' => 'Can SutharTech build mobile applications?', 'answer' => 'Yes. Mobile product engineering for iOS and Android is one of the services offered.', 'sort_order' => 4],
            ['page' => 'home', 'question' => 'How do I discuss a project?', 'answer' => 'Use the contact page to send a short project brief with your name, work email, project type, and what you are building.', 'sort_order' => 5],
            ['page' => 'contact', 'question' => 'What should I include in my project brief?', 'answer' => 'Share the problem you want to solve, who will use the product, its current stage, key requirements, and your preferred next step. A concise overview is enough to start.', 'sort_order' => 1],
            ['page' => 'contact', 'question' => 'What happens after I send the form?', 'answer' => 'The team reviews your message and project details, then follows up to understand the goals and discuss a suitable next step.', 'sort_order' => 2],
            ['page' => 'contact', 'question' => 'Can I contact you about an existing product?', 'answer' => 'Yes. Include the current product, the areas where you need help, and any important technical or delivery constraints.', 'sort_order' => 3],
            ['page' => 'contact', 'question' => 'Which kinds of projects can I discuss?', 'answer' => 'You can discuss web and mobile products, SaaS platforms, AI or automation work, cloud and DevOps, and product design.', 'sort_order' => 4],
            ['page' => 'contact', 'question' => 'Can I attach a project brief or specification?', 'answer' => 'Yes. Use the optional attachment field for a brief or specification that helps explain the project. Please avoid including passwords, secret keys, or other access credentials.', 'sort_order' => 5],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(['page' => $faq['page'], 'question' => $faq['question']], $faq + ['status' => 'active']);
        }
    }
}
