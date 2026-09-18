<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function run()
    {
        Storage::deleteDirectory('public/posts');
        Storage::makeDirectory('public/posts');

        $this->call(UserSeeder::class);
        $this->call(ScienceSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(SectorSeeder::class);
        $this->call(LevelSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(PriceSeeder::class);
        $this->call(PlatformSeeder::class);
        //$this->call(DevelopmentSeeder::class);
        $this->call(ImageSeeder::class);
        $this->call(EconomicFactSeeder::class);
        $this->call(ModuleSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(EntitySeeder::class);



        //$this->call(SubjectSeeder::class);

    }
}
