<?php

namespace App\Domain\Pages\Models;

use Database\Factories\Domain\Pages\Models\PageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\SchemaOrg\Schema;

#[Fillable([
    'title',
    'slug',
    'template',
    'content',
    'status',
    'is_homepage',
])]
class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    use HasSEO;
    use SoftDeletes;

    #[\Override]
    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_homepage' => 'boolean',
        ];
    }

    /**
     * Summary of scopePublished
     *
     * @param  mixed  $query
     */
    public function scopePublished($query): void
    {
        $query->where('status', 'published');
    }

    /**
     * Summary of scopeHomepage
     *
     * @param  mixed  $query
     */
    public function scopeHomepage($query): void
    {
        $query->where('is_homepage', true);
    }

    public function getDynamicSEOData(): SEOData
    {
        $schema = SchemaCollection::initialize();

        $schema->push(function (): array {
            $page = match ($this->template) {
                'about' => Schema::aboutPage()->name($this->title),
                'contact' => Schema::contactPage()->name($this->title),
                default => Schema::webPage()->name($this->title),
            };

            return $page->toArray();
        });

        $schema->addBreadcrumbs(fn ($schema) => $schema->appendBreadcrumbs([
            'Home' => '/',
            $this->title => '/'.$this->slug,
        ]));

        return new SEOData(
            title: $this->title,
            type: 'website',
            schema: $schema,
        );
    }
}
