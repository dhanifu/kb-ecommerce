<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use App\Product;
use App\Category;
use GuzzleHttp\Client;

class MarketplaceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $username;
    protected $take;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($username, $take)
    {
        $this->username = $username;
        $this->take = $take;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = 'https://ruangapi.com/api/v1/shopee';
        $client = new Client();
        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => 'HIKo1KbHk6L24ViJCJHG93JVYvTuY0KoWLWF49yx'
            ],
            'form_params' => [
                'username' => $this->username,
                'take' => $this->take
            ]
        ]);

        $body = json_decode($response->getBody(), true);

        foreach ($body['data']['result'] as $row) {
            $filename = Str::slug($row['title']). '-'. time() .'png';

            file_put_contents(storage_path('app/public/products/'. $filename), file_get_contents($row['images'][0]));

            $category = Category::first();

            if (count($row['categories']) > 0) {
                $category = Category::firstOrCreate([
                    'name' => $row['categories'][0],
                    'slug' => Str::slug($row['categories'][0])
                ]);
            }

            Product::firstOrCreate(
                [
                    'name' => $row['title'],
                ],
                [
                    'slug' => Str::slug($row['title']),
                    'cateogry_id' => $category->id,
                    'description' => $row['description'],
                    'image' => $filename,
                    'price' => $row['price'],
                    'weight' => 600,
                    'status' => 1,
                ]
            );

            if (count($body['data']['results']) > 0) {
                MarketplaceJob::dispatch($this->username, $this->take + 10)->delay(now()->addMinutes(5));
            }
        }
    }
}
