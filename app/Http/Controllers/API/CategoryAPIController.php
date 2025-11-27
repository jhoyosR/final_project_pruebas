<?php

namespace App\Http\Controllers\API;

use App\DTOs\Category\CategoryDTO;
use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryAPIController extends Controller {

    /** Constructor de la clase */
    public function __construct(
        private readonly CategoryService $categoryService
    ) {}

    /**
     * Muestra el listado de categorias
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse {

        // Obtener todas las categorías
        $categories = $this->categoryService->getRecords($request);

        // Devuelve respuesta en formato JSON
        return $this->successResponse(
            message: 'Datos obtenidos exitosamente', 
            result : $categories
        );
    }

    /**
     * Almacena una nueva categoria en la base de datos
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse {

        // Validación de datos
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string']
        ]);

        // Responde con error de datos de formulario
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $category = $this->categoryService->create(
            CategoryDTO::fromArray($request->all())
        );

        return $this->successResponse(
            message: 'Registro guardado exitosamente',
            result  : $category
        );
    }

    /**
     * Muestra un categoria específico
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse {

        $category = $this->categoryService->find($id);

        return $this->successResponse(
            message: 'Datos obtenidos exitosamente',
            result  : $category
        );
    }

    /**
     * Actualiza un categoria en la base de datos
     * 
     * @param  Request $request
     * @param  int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse {

        // Validación de datos
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string']
        ]);

        // Responde con error de datos de formulario
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $category = $this->categoryService->update(
            id: $id,
            data: CategoryDTO::fromArray($request->all())
        );

        return $this->successResponse(
            message: 'Registro actualizado exitosamente',
            result  : $category
        );
    }

    /**
     * Elimina un categoria de la base de datos
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse {

        $this->categoryService->delete($id);

        return $this->successResponse(
            message: 'Registro eliminado exitosamente'
        );
    }

}