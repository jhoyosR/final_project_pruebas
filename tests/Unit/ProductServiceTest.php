<?php

namespace Tests\Unit\Services;

use App\DTOs\Product\ProductDTO;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class ProductServiceTest extends TestCase {

    public function test_get_records_returns_eloquent_collection() {
        // Mock del Repositorio y Modelo
        $mockRepo = Mockery::mock(ProductRepository::class);
        $mockModel = Mockery::mock(Product::class);

        // Crear el Mock del Builder
        $mockBuilder = Mockery::mock(Builder::class);

        // Crear una colección de Eloquent
        $eloquentCollection = new ResourceCollection([
            new Product(['id' => 1, 'name' => 'Product 1', 'description' => 'Description Product 1', 'price' => 10000, 'stock' => 10]),
            new Product(['id' => 2, 'name' => 'Product 2', 'description' => 'Description Product 2', 'price' => 20000, 'stock' => 20]),
        ]);

        // Definir expectativas
        $mockRepo->shouldReceive('makeModel')
            ->once()
            ->andReturn($mockModel);

        $mockModel->shouldReceive('latest')
            ->with('id')
            ->once()
            ->andReturn($mockBuilder);

        $mockBuilder->shouldReceive('with')
            ->with('category')
            ->once()
            ->andReturnSelf();

        $mockBuilder->shouldReceive('get')
            ->once()
            ->andReturn($eloquentCollection);
        
        // Instanciar el servicio inyectando el mock
        $service = new ProductService($mockRepo);

        // Ejecutar
        $result = $service->getRecords();

        // Verificar
        $this->assertInstanceOf(ResourceCollection::class, $result);
        $this->assertCount(2, $result);
    }


    public function test_create_product_successfully() {
        // DTO de entrada
        $dto = new ProductDTO(category_id: 1, name: 'Nuevo Producto', description: 'Nueva descripcion', price: 10000, stock: 10);

        // Mock del repositorio
        $repositoryMock = Mockery::mock(ProductRepository::class);

        // Simulamos lo que el repository debe devolver
        $fakeProduct = new Product();
        $fakeProduct->id = 1;
        $fakeProduct->name = 'Nuevo Producto';
        $fakeProduct->description = 'Nueva descripcion';
        $fakeProduct->price = 10000;
        $fakeProduct->stock = 10;

        // Esperamos que el repository reciba "create" y devuelva el modelo fake
        $repositoryMock->shouldReceive('create')
            ->once()
            ->with($dto->toArray())
            ->andReturn($fakeProduct);

        // Instanciamos el Service con el mock del repositorio
        $service = new ProductService($repositoryMock);

        $result = $service->create($dto);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Nuevo Producto', $result->name);
        $this->assertEquals(10000, $result->price);
    }

    public function test_find_returns_product() {

        // Mock del modelo
        $modelMock = Mockery::mock(Product::class);

        // Simulamos lo que el repository debe devolver
        $fakeProduct = new Product();
        $fakeProduct->id = 10;
        $fakeProduct->name = 'Axe Spray';
        $fakeProduct->description = 'Desodorante Axe en Spray';
        $fakeProduct->price = 20000;
        $fakeProduct->stock = 104;

        $modelMock->shouldReceive('findOrFail')
            ->with(10)
            ->andReturn($fakeProduct);

        $repositoryMock = Mockery::mock(ProductRepository::class);
        $repositoryMock->shouldReceive('makeModel')->andReturn($modelMock);

        $service = new ProductService($repositoryMock);

        $result = $service->find(10);

        $this->assertEquals(10, $result->id);
        $this->assertEquals('Axe Spray', $result->name);
        $this->assertEquals(104, $result->stock);
    }

    public function test_update_updates_and_returns_product() {
        // Datos de prueba
        $id = 5;
        $dto = ProductDTO::fromArray(['category_id' => 1, 'name' => 'Updated Name', 'description' => 'Updated Description Product 1', 'price' => 10000, 'stock' => 10]);

        // Mockear la transacción de DB
        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        // Mocks
        $mockRepo = Mockery::mock(ProductRepository::class);
        $mockModel = Mockery::mock(Product::class);

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
        $service = new ProductService($mockRepo);
        $result = $service->update($id, $dto);

        // Verificar
        $this->assertEquals($mockModel, $result);
    }

    public function test_delete_removes_product() {
        // Dato de prueba
        $id = 7;

        // Mocks
        $mockRepo = Mockery::mock(ProductRepository::class);
        $mockModel = Mockery::mock(Product::class);

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
        $service = new ProductService($mockRepo);
        $result = $service->delete($id);

        // Verificar
        $this->assertTrue($result);
    }


}
