<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryCrudTest extends TestCase {

    use RefreshDatabase;

    public function test_can_create_category_via_api() {
        // Prepara cuerpo de petición
        $payload = [
            'name' => 'Ropa deportiva'
        ];

        // Hace la petición
        $response = $this->postJson('/api/categories', $payload);
        // Valida respuesta
        $response->assertStatus(200)->assertJsonFragment(['name' => 'Ropa deportiva']);
        // Valida existencia en base de datos
        $this->assertDatabaseHas('categories', ['name' => 'Ropa deportiva']);
    }

    public function test_can_list_categories() {
        // Crea registros del modelo con datos aleatorios
        Category::factory()->count(2)->create();
        // Hace la petición
        $response = $this->getJson('/api/categories');
        // Valida respuesta
        $response->assertStatus(200)->assertJsonCount(2, 'data');
    }

    public function test_can_show_category() {
        // Crea registro del modelo con datos aleatorios
        $category = Category::factory()->create();
        // Hace la petición
        $response = $this->getJson("/api/categories/{$category->id}");
        // Valida respuesta
        $response->assertStatus(200)->assertJsonFragment(['id' => $category->id]);
    }

    public function test_can_update_category() {
        // Crea registro del modelo con datos aleatorios
        $category = Category::factory()->create();
        // Prepara cuerpo de la petición
        $payload = [
            'name' => 'Nuevo nombre',
        ];
        // Hace la petición
        $response = $this->putJson("/api/categories/{$category->id}", $payload);
        // Valida respuesta
        $response->assertStatus(200)->assertJsonFragment(['name' => 'Nuevo nombre']);
        // Valida existencia en base de datos
        $this->assertDatabaseHas('categories', ['id' => $category->id,'name' => 'Nuevo nombre']);
    }

    public function test_can_delete_category() {
        // Crea registro del modelo con datos aleatorios
        $category = Category::factory()->create();
        // Hace la petición
        $response = $this->deleteJson("/api/categories/{$category->id}");
        // Valida respuesta
        $response->assertStatus(200);
        // Valida inexistencia en base de datos
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

}