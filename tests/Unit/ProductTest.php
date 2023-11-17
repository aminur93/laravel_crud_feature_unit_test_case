<?php

namespace Tests\Unit;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Arr;


class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_product_instance()
    {
        $productData = [
            'name' => 'Nike',
            'title' => 'Shoes',
            'description' => 'This is a product description',
            'code' => '1234',
            'price' => '49.99',
        ];

        $product = new Product($productData);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Nike', $product->name);
        $this->assertEquals('Shoes', $product->title);
        // Additional assertions for other attributes
    }

    /** @test */
    public function it_can_update_a_product_instance()
    {
        // Create a product using the factory
        $product = Product::factory()->create();
    
        // Define new data for updating the product
        $newData = [
            'name' => 'Updated Name',
            'title' => 'Updated Title',
            'slug' => 'updated-slug',
            'description' => 'Updated Description',
            'code' => '1234',
            'price' => '50.99',
        ];
    
        // Make a request to your update endpoint (modify this based on your application)
        $response = $product->update($newData);
    
        // Reload the product from the database to get the updated attributes
        $product->refresh();
    
        // Assert that the product has been updated
        $this->assertEquals($newData, Arr::except($product->toArray(), ['id', 'created_at', 'updated_at']));
    }
}
