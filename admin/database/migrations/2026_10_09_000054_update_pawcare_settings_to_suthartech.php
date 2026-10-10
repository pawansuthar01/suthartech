<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->replace('site_name', 'PawCare', 'SutharTech');
        $this->replace('site_name', 'PawCare - Pet Shop & Pet Care', 'SutharTech');
        $this->replace('default_meta_title', 'PawCare - Premium Pet Shop & Pet Care Services', 'SutharTech | Software Development & Digital Products');
        $this->replace('default_meta_title', 'PawCare - Premium Pet Shop & Pet Care', 'SutharTech | Software Development & Digital Products');
        $this->replace('default_meta_description', 'PawCare offers professional pet care services including vaccinations, grooming, training and boarding across major Indian cities.', 'SutharTech builds high-performance software and digital products for ambitious businesses.');
        $this->replace('default_meta_keywords', 'pet care, pet shop, pet vaccination, dog grooming, cat care, pet boarding', 'software development, digital products, technology');
    }

    public function down(): void
    {
        $this->replace('site_name', 'SutharTech', 'PawCare');
        $this->replace('default_meta_title', 'SutharTech | Software Development & Digital Products', 'PawCare - Premium Pet Shop & Pet Care Services');
        $this->replace('default_meta_description', 'SutharTech builds high-performance software and digital products for ambitious businesses.', 'PawCare offers professional pet care services including vaccinations, grooming, training and boarding across major Indian cities.');
        $this->replace('default_meta_keywords', 'software development, digital products, technology', 'pet care, pet shop, pet vaccination, dog grooming, cat care, pet boarding');
    }

    private function replace(string $key, string $from, string $to): void
    {
        DB::table('settings')->where('key', $key)->where('value', $from)->update(['value' => $to]);
    }
};
