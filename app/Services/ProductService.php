<?php 

namespace App\Services;

use App\DTOs\Product\ProductDTO;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Transformers\Product\ProductResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** Servicio de products */
class ProductService {

    /** Constructor de la clase */
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {}

    /**
     * Obtiene todos los registros
     *
     * @return ResourceCollection
     */
    public function getRecords(): ResourceCollection {
        // Obtiene los registros 
        $products = $this->productRepository->makeModel()->latest('id')->with('category')->get();

        return ProductResource::collection($products);
    }

    /**
     * Encuentra un registro
     *
     * @param  integer $id
     * @return Product|null
     */
    public function find(int $id): ?Product {
        return $this->productRepository->makeModel()->findOrFail($id);
    }
    
    /**
     * Crea un registro
     *
     * @param  ProductDTO $data
     * @return Product
     */
    public function create(ProductDTO $data): Product {
        return DB::transaction(function () use ($data) {
            // Crea producto
            $product = $this->productRepository->create($data->toArray());

            return $product;
        });
    }

    /**
     * Actualiza un registro
     *
     * @param  ProductDTO $data
     * @return Product
     */
    public function update(int $id, ProductDTO $data): Product {
        // Inicia una transacción
        return DB::transaction(function () use ($id, $data) {

            // Buscar o lanzar ModelNotFoundException automáticamente
            $product = $this->find($id);

            // Actualizar con los datos del DTO
            $product->update($data->toArray());

            return $product->fresh();
        });
    }

    /**
     * Elimina un registro
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete(int $id) {
        return DB::transaction(function () use ($id) {

            // Buscar o fallar
            $product = $this->find($id);

            // Eliminar
            return $product->delete();
        });
    }
}