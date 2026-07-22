<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@mktcode.digital'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            RolesAndPermissionsSeeder::class,
            SiteSettingsSeeder::class,
            CategoriesSeeder::class,
            TagsSeeder::class,
            PostsSeeder::class,
            ProjectsSeeder::class,
            PagesSeeder::class,
            FormsSeeder::class,
        ]);
    }
}
