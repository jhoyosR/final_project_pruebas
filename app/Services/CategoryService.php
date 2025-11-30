<?php 

namespace App\Services;

use App\DTOs\Category\CategoryDTO;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

/** Servicio de categories */
class CategoryService {

    /** Constructor de la clase */
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ) {}

    /**
     * Obtiene todos los registros
     *
     * @return Collection
     */
    public function getRecords(): Collection {
        // Obtiene los registros 
        $categories = $this->categoryRepository->makeModel()->latest('id')->get();

        return $categories;
    }

    /**
     * Encuentra un registro
     *
     * @param  integer $id
     * @return Category|null
     */
    public function find(int $id): ?Category {
        return $this->categoryRepository->makeModel()->findOrFail($id);
    }
    
    /**
     * Crea un registro
     *
     * @param  CategoryDTO $data
     * @return Category
     */
    public function create(CategoryDTO $data): Category {
        return DB::transaction(function () use ($data) {
            // Crea categoria
            $category = $this->categoryRepository->create($data->toArray());

            return $category;
        });
    }

    /**
     * Actualiza un registro
     *
     * @param  CategoryDTO $data
     * @return Category
     */
    public function update(int $id, CategoryDTO $data): Category {
        // Inicia una transacción
        return DB::transaction(function () use ($id, $data) {

            // Buscar o lanzar ModelNotFoundException automáticamente
            $category = $this->find($id);

            // Actualizar con los datos del DTO
            $category->update($data->toArray());

            return $category->fresh();
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
            $category = $this->find($id);

            // Eliminar
            return $category->delete();
        });
    }
}