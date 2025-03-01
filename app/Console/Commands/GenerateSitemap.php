<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap for the website';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Создание объекта карты сайта
        $sitemap = Sitemap::create();

        // Добавляем основные страницы
        $sitemap->add(Url::create('/')
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1.0));

        // Страницы для пользователей
        $sitemap->add(Url::create('/register')
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.9));
        $sitemap->add(Url::create('/login')
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.9));

        $sitemap->add(Url::create('/profile')
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.9));

        // Страницы записи
        $sitemap->add(Url::create('/record')
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.9));


        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap has been generated successfully!');
    }
}
