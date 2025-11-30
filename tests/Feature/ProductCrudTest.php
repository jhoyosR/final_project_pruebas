<?php
namespace Tests\Feature;

use App\Models\Category;
use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductCrudTest extends TestCase {

    use RefreshDatabase;

    public function test_can_create_product_via_api() {
        // Crea llave foránea
        $category = Category::factory()->create();
        // Prepara cuerpo de petición
        $payload = [
            'category_id' => $category->id,
            'name'        => 'Camiseta UA Azul',
            'description' => 'Camiseta Under Armour Azul',
            'price'       => 70000,
            'stock'       => 32,
        ];

        // Hace la petición
        $response = $this->postJson('/api/products', $payload);
        // Valida respuesta
        $response->assertStatus(200)->assertJsonFragment(['name' => 'Camiseta UA Azul']);
        // Valida existencia en base de datos
        $this->assertDatabaseHas('products', ['name' => 'Camiseta UA Azul']);
    }

    public function test_can_list_products() {
        // Crea registros del modelo con datos aleatorios
        Product::factory()->count(2)->create();
        // Hace la petición
        $response = $this->getJson('/api/products');
        // Valida respuesta
        $response->assertStatus(200)->assertJsonCount(2, 'data');
    }

    public function test_can_show_product() {
        // Crea registro del modelo con datos aleatorios
        $product = Product::factory()->create();
        // Hace la petición
        $response = $this->getJson("/api/products/{$product->id}");
        // Valida respuesta
        $response->assertStatus(200)->assertJsonFragment(['id' => $product->id]);
    }

    public function test_can_update_product() {
        // Crea registro del modelo con datos aleatorios
        $product = Product::factory()->create();
        // Prepara cuerpo de la petición
        $payload = [
            'category_id' => $product->category_id,
            'name'        => 'Nuevo nombre',
            'description' => $product->description,
            'price'       => $product->price,
            'stock'       => $product->stock,
        ];
        // Hace la petición
        $response = $this->putJson("/api/products/{$product->id}", $payload);
        // Valida respuesta
        $response->assertStatus(200)->assertJsonFragment(['name' => 'Nuevo nombre']);
        // Valida existencia en base de datos
        $this->assertDatabaseHas('products', ['id' => $product->id,'name' => 'Nuevo nombre']);
    }

    public function test_can_delete_product() {
        // Crea registro del modelo con datos aleatorios
        $product = Product::factory()->create();
        // Hace la petición
        $response = $this->deleteJson("/api/products/{$product->id}");
        // Valida respuesta
        $response->assertStatus(200);
        // Valida inexistencia en base de datos
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

}