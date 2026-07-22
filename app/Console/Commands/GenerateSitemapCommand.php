<?php

namespace App\Console\Commands;

use App\Domain\Content\Models\Post;
use App\Domain\Pages\Models\Page;
use App\Domain\Portfolio\Models\Project;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

#[Description('Generate the sitemap XML file')]
#[Signature('sitemap:generate')]
class GenerateSitemapCommand extends Command
{
    public function handle(): int
    {
        $sitemap = Sitemap::create();

        $sitemap->add(
            Url::create('/')
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(1.0)
        );

        Post::query()->published()->each(function (Post $post) use ($sitemap) {
            $sitemap->add(
                Url::create("/blog/{$post->slug}")
                    ->setLastModificationDate($post->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.8)
            );
        });

        Project::query()->published()->each(function (Project $project) use ($sitemap) {
            $sitemap->add(
                Url::create("/portfolio/{$project->slug}")
                    ->setLastModificationDate($project->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.8)
            );
        });

        Page::query()->published()->each(function (Page $page) use ($sitemap) {
            $sitemap->add(
                Url::create("/pagina/{$page->slug}")
                    ->setLastModificationDate($page->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.6)
            );
        });

        $sitemap->writeToDisk('public', 'sitemap.xml');

        $this->info('Sitemap generated successfully!');

        return self::SUCCESS;
    }
}
