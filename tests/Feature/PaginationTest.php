<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Astrologer;
use App\Models\Service;
use App\Models\Product;

class PaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_astrologers_api_returns_pagination_info()
    {
        // Create test data
        $service = Service::create(['name' => 'Chat', 'slug' => 'chat']);

        // Create multiple astrologers
        for ($i = 1; $i <= 15; $i++) {
            $user = User::factory()->create(['name' => "Astrologer {$i}"]);
            $astrologer = Astrologer::create([
                'user_id' => $user->id,
                'status' => 'approved',
                'total_rating' => rand(1, 5)
            ]);

            // Attach service to astrologer
            $astrologer->services()->attach($service->id, ['is_enabled' => true]);
        }

        // Test pagination
        $response = $this->get('/api/astrologers?service=chat&limit=5');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'astrologers',
                        'pagination' => [
                            'current_page',
                            'last_page',
                            'per_page',
                            'total',
                            'from',
                            'to'
                        ]
                    ]
                ]);

        $data = $response->json('data');

        // Verify pagination data
        $this->assertEquals(1, $data['pagination']['current_page']);
        $this->assertEquals(5, $data['pagination']['per_page']);
        $this->assertEquals(15, $data['pagination']['total']);
        $this->assertEquals(3, $data['pagination']['last_page']); // 15 items / 5 per page = 3 pages
        $this->assertEquals(1, $data['pagination']['from']);
        $this->assertEquals(5, $data['pagination']['to']);
    }

    public function test_products_api_returns_pagination_info()
    {
        // Create test products
        for ($i = 1; $i <= 25; $i++) {
            Product::create([
                'name' => "Product {$i}",
                'description' => "Description for product {$i}",
                'price' => rand(100, 1000),
                'stock' => rand(0, 50),
                'status' => 'active'
            ]);
        }

        // Test pagination
        $response = $this->get('/api/products?limit=10');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'products',
                        'pagination' => [
                            'current_page',
                            'last_page',
                            'per_page',
                            'total',
                            'from',
                            'to'
                        ]
                    ]
                ]);

        $data = $response->json('data');

        // Verify pagination data
        $this->assertEquals(1, $data['pagination']['current_page']);
        $this->assertEquals(10, $data['pagination']['per_page']);
        $this->assertEquals(25, $data['pagination']['total']);
        $this->assertEquals(3, $data['pagination']['last_page']); // 25 items / 10 per page = 3 pages
        $this->assertEquals(1, $data['pagination']['from']);
        $this->assertEquals(10, $data['pagination']['to']);
    }

    public function test_pagination_parameters_work_correctly()
    {
        // Create test data
        $service = Service::create(['name' => 'Chat', 'slug' => 'chat']);

        for ($i = 1; $i <= 20; $i++) {
            $user = User::factory()->create(['name' => "Astrologer {$i}"]);
            $astrologer = Astrologer::create([
                'user_id' => $user->id,
                'status' => 'approved',
                'total_rating' => rand(1, 5)
            ]);
            $astrologer->services()->attach($service->id, ['is_enabled' => true]);
        }

        // Test different pagination parameters
        $response = $this->get('/api/astrologers?service=chat&per_page=8&page=2');

        $response->assertStatus(200);

        $data = $response->json('data');

        // Verify second page
        $this->assertEquals(2, $data['pagination']['current_page']);
        $this->assertEquals(8, $data['pagination']['per_page']);
        $this->assertEquals(20, $data['pagination']['total']);
        $this->assertEquals(3, $data['pagination']['last_page']); // 20 items / 8 per page = 3 pages
        $this->assertEquals(9, $data['pagination']['from']); // Items 9-16 on page 2
        $this->assertEquals(16, $data['pagination']['to']);
    }
}
