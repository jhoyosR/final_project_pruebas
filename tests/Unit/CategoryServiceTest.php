<?php

namespace Tests\Unit\Services;

use App\DTOs\Category\CategoryDTO;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class CategoryServiceTest extends TestCase {

    public function test_get_records_returns_eloquent_collection() {
        // Mock del Repositorio y Modelo
        $mockRepo = Mockery::mock(CategoryRepository::class);
        $mockModel = Mockery::mock(Category::class);

        // Crear una colección de Eloquent
        $eloquentCollection = new EloquentCollection([
            new Category(['id' => 1, 'name' => 'Cat 1']),
            new Category(['id' => 2, 'name' => 'Cat 2']),
        ]);

        // Definir expectativas
        $mockRepo->shouldReceive('makeModel')
            ->once()
            ->andReturn($mockModel);

        $mockModel->shouldReceive('latest')
            ->with('id')
            ->once()
            ->andReturnSelf();

        $mockModel->shouldReceive('get')
            ->once()
            ->andReturn($eloquentCollection);
        
        // Instanciar el servicio inyectando el mock
        $service = new CategoryService($mockRepo);

        // Ejecutar
        $result = $service->getRecords();

        // Verificar
        $this->assertInstanceOf(EloquentCollection::class, $result);
        $this->assertCount(2, $result);
    }


    public function test_create_category_successfully() {
        // DTO de entrada
        $dto = new CategoryDTO(name: 'Nueva Categoría');

        // Mock del repositorio
        $repositoryMock = Mockery::mock(CategoryRepository::class);

        // Simulamos lo que el repository debe devolver
        $fakeCategory = new Category();
        $fakeCategory->id = 1;
        $fakeCategory->name = 'Nueva Categoría';

        // Esperamos que el repository reciba "create" y devuelva el modelo fake
        $repositoryMock->shouldReceive('create')
            ->once()
            ->with($dto->toArray())
            ->andReturn($fakeCategory);

        // Instanciamos el Service con el mock del repositorio
        $service = new CategoryService($repositoryMock);

        $result = $service->create($dto);

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Nueva Categoría', $result->name);
    }

    public function test_find_returns_category() {

        // Mock del modelo
        $modelMock = Mockery::mock(Category::class);

        // Simulamos lo que el repository debe devolver
        $fakeCategory = new Category();
        $fakeCategory->id = 10;
        $fakeCategory->name = 'Test';

        $modelMock->shouldReceive('findOrFail')
            ->with(10)
            ->andReturn($fakeCategory);

        $repositoryMock = Mockery::mock(CategoryRepository::class);
        $repositoryMock->shouldReceive('makeModel')->andReturn($modelMock);

        $service = new CategoryService($repositoryMock);

        $result = $service->find(10);

        $this->assertEquals(10, $result->id);
        $this->assertEquals('Test', $result->name);
    }

    public function test_update_updates_and_returns_category() {
        // Datos de prueba
        $id = 10;
        $dto = CategoryDTO::fromArray(['name' => 'Updated Name']);

        // Mockear la transacción de DB
        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        // Mocks
        $mockRepo = Mockery::mock(CategoryRepository::class);
        $mockModel = Mockery::mock(Category::class);

        // Expectativas
        $mockRepo->shouldReceive('makeModel')
            ->once()
            ->andReturn($mockModel);

        $mockModel->shouldReceive('findOrFail')
            ->with($id)
            ->once()
            ->andReturn($mockModel);

        $mockModel->shouldReceive('update')
            ->with($dto->toArray())
            ->once()
            ->andReturn(true);

        $mockModel->shouldReceive('fresh')
            ->once()
            ->andReturnSelf();

        // Ejecutar
        $service = new CategoryService($mockRepo);
        $result = $service->update($id, $dto);

        // Verificar
        $this->assertEquals($mockModel, $result);
    }

    public function test_delete_removes_category() {
        // Dato de prueba
        $id = 5;

        // Mocks
        $mockRepo = Mockery::mock(CategoryRepository::class);
        $mockModel = Mockery::mock(Category::class);

        // Mockear la transacción de DB
        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        // Expectativas
        $mockRepo->shouldReceive('makeModel')
            ->once()
            ->andReturn($mockModel);

        $mockModel->shouldReceive('findOrFail')
            ->with($id)
            ->once()
            ->andReturn($mockModel);

        $mockModel->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        // Ejecutar
        $service = new CategoryService($mockRepo);
        $result = $service->delete($id);

        // Verificar
        $this->assertTrue($result);
    }


}
