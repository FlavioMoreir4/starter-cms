<?php

namespace App\Domain\Content\Models;

use App\Models\User;
use Carbon\CarbonImmutable;
use Database\Factories\Domain\Content\Models\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\SchemaOrg\Schema;

/**
 * @property CarbonImmutable|null $published_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable([
    'category_id',
    'author_id',
    'title',
    'slug',
    'excerpt',
    'body',
    'status',
    'published_at',
    'is_featured',
])]
class Post extends Model implements HasMedia
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;

    use HasSEO;
    use InteractsWithMedia;
    use SoftDeletes;

    #[\Override]
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'updated_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Summary of category
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Summary of author
     *
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Summary of tags
     *
     * @return BelongsToMany<Tag, $this>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Summary of scopePublished
     *
     * @param  mixed  $query
     */
    public function scopePublished($query): void
    {
        $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function getDynamicSEOData(): SEOData
    {
        $blogPosting = Schema::blogPosting()
            ->headline($this->title)
            ->description($this->excerpt ?? '')
            ->author(Schema::person()->name($this->author->name ?? 'Anonymous'));

        if ($this->published_at instanceof CarbonImmutable) {
            $blogPosting->datePublished($this->published_at);
        }

        if ($this->updated_at instanceof CarbonImmutable) {
            $blogPosting->dateModified($this->updated_at);
        }

        return new SEOData(
            title: $this->title,
            description: $this->excerpt,
            type: 'article',
            published_time: $this->published_at,
            modified_time: $this->updated_at,
            schema: SchemaCollection::initialize()
                ->push(fn (SEOData $seo): array => $blogPosting->toArray())
                ->addBreadcrumbs(fn ($schema) => $schema->appendBreadcrumbs([
                    'Home' => '/',
                    'Blog' => '/blog',
                    $this->title => '/blog/'.$this->slug,
                ])),
        );
    }
}
