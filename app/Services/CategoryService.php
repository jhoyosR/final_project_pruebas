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
}