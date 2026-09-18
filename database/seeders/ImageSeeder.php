<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Image::create([
            'url' => 'app\public\storage\developments\pepesky.png',
            'imageable_id' => '1',
            'imageable_type' =>'App\Models\Development'
        ]);

        Image::create([
            'url' => 'app\public\storage\developments\pepesky.png',
            'imageable_id' => '2',
            'imageable_type' =>'App\Models\Development'
        ]);

        Image::create([
            'url' => 'app\public\storage\developments\bicentenario.png',
            'imageable_id' => '3',
            'imageable_type' =>'App\Models\Development'
        ]);

        Image::create([
            'url' => 'app\public\storage\developments\mantcic-life.png',
            'imageable_id' => '4',
            'imageable_type' =>'App\Models\Development'
        ]);
    }
}
