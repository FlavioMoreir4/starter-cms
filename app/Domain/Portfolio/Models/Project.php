<?php

namespace App\Domain\Portfolio\Models;

use Database\Factories\Domain\Portfolio\Models\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\SchemaOrg\Schema;

#[Fillable([
    'title',
    'slug',
    'description',
    'body',
    'client',
    'category',
    'repository_url',
    'demo_url',
    'status',
    'is_featured',
    'sort_order',
])]
class Project extends Model implements HasMedia
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    use HasSEO;
    use InteractsWithMedia;
    use SoftDeletes;

    #[\Override]
    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Summary of technologies
     *
     * @return HasMany<Technology, $this>
     */
    public function technologies(): HasMany
    {
        return $this->hasMany(Technology::class);
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

    public function getDynamicSEOData(): SEOData
    {
        $creativeWork = Schema::creativeWork()
            ->name($this->title)
            ->description($this->description ?? '');

        return new SEOData(
            title: $this->title,
            description: $this->description,
            type: 'website',
            schema: SchemaCollection::initialize()
                ->push(fn (SEOData $seo): array => $creativeWork->toArray())
                ->addBreadcrumbs(fn ($schema) => $schema->appendBreadcrumbs([
                    'Home' => '/',
                    'Portfolio' => '/portfolio',
                    $this->title => '/portfolio/'.$this->slug,
                ])),
        );
    }
}
