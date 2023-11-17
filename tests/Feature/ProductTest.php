<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

use Illuminate\Database\Eloquent\Factories\factory;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_api_get_all_product()
    {
        $product = Product::latest()->get();

        $response = $this->getJson('api/product');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson($product->toArray());
    }

    /** @test */
    public function test_api_create_a_product()
    {
        $productData = [
            'name' => 'Nike',
            'title' => 'shoes',
            'slug' => 'nike_shoes',
            'description' => 'This is product',
            'code' => '2443',
            'price' => '19.99'
        ];

        $response = $this->postJson('api/product/store', $productData);

        $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson(['data' => $productData,'message' => 'Product store successful']);
    }

    public function test_api_product_validation()
    {
        $productData = [
            'name' => '',
            'title' => '',
            'slug' => 'nike_shoes',
            'description' => 'This is product',
            'code' => '2443',
            'price' => '19.99'
        ];

        $response = $this->postJson('api/product/store', $productData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_api_single_product()
    {
        // Assuming $product is retrieved from the database or created using the factory
        $product = Product::factory()->create(['id' => 1]);

        // Make the request to the API
        $response = $this->getJson('api/product/edit/' . $product->id);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'id' => $product->id,
            'name' => $product->name,
            'title' => $product->title,
            'slug' => $product->slug,
            'description' => $product->description,
            'code' => (string) $product->code,
            'price' => (string) $product->price,
        ]);

    }

    public function test_api_update_product()
    {
        $product = Product::factory()->create(['id' => 1]);

        $productData = [
            'name' => 'puma',
            'title' => 'shoe',
            'description' => 'This is puma shoes',
            'code' => '7890',
            'price' => '25.55'
        ];

        $response = $this->putJson("api/product/update/{$product->id}", $productData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['data' => $productData, 'message' => 'Product update successful']);
    }

    public function test_api_delete_product()
    {
        // Create a product for testing
        $product = Product::factory()->create(['id' => 1]);

        // Make the request to delete the product
        $response = $this->deleteJson("api/product/destroy/{$product->id}");

        // Assert that the response has a successful status code
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'Product destroy successful']);

        // Assert that the product is not present in the database
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
