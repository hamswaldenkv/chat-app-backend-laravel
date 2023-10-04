<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::create([
            'event_id' => Str::uuid(),
            'title' => 'My Title',
            'description' => 'My Event description',
            'photo_url' => null,
            'organisator_name' => 'My Organisator',
            'venue_place' => 'Place Name',
            'venue_address' => '12, Avenue, Suite, Immeuble',
            'venue_location_latitude' => null,
            'venue_location_longitude' => null,
            'is_live' => true,
            'is_free' => true,
            'price_value' => null,
            'price_currency' => null,
            'status' => 1,
            'start_at' => now()->timestamp,
            'finish_at' => now()->addHours(2)->timestamp,
        ]);
    }
}
