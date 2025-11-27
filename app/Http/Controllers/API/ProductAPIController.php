<?php

namespace App\Http\Controllers\API;

use App\DTOs\Product\ProductDTO;
use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Transformers\Product\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductAPIController extends Controller {

    /** Constructor de la clase */
    public function __construct(
        private readonly ProductService $productService
    ) {}

    /**
     * Muestra el listado de productos
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse {

        // Obtener todos los productos
        $products = $this->productService->getRecords($request);

        // Devuelve respuesta en formato JSON
        return $this->successResponse(
            message: 'Datos obtenidos exitosamente', 
            result : $products
        );
    }

    /**
     * Almacena un nuevo producto en la base de datos
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse {

        // Validación de datos
        $validator = Validator::make($request->all(), [
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'name'        => ['required', 'string'],
            'description' => ['required', 'string', 'max:400'],
            'price'       => ['required', 'integer'],
            'stock'       => ['required', 'integer'],
        ]);

        // Responde con error de datos de formulario
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $product = $this->productService->create(
            ProductDTO::fromArray($request->all())
        );

        return $this->successResponse(
            message: 'Registro guardado exitosamente',
            result  : $product
        );
    }

    /**
     * Muestra un producto específico
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse {

        $product = $this->productService->find($id);

        return $this->successResponse(
            message: 'Datos obtenidos exitosamente',
            result  : ProductResource::make($product)
        );
    }

    /**
     * Actualiza un producto en la base de datos
     * 
     * @param  Request $request
     * @param  int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse {

        // Validación de datos
        $validator = Validator::make($request->all(), [
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'name'        => ['required', 'string'],
            'description' => ['required', 'string'],
            'price'       => ['required', 'integer'],
            'stock'       => ['required', 'integer'],
        ]);

        // Responde con error de datos de formulario
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $product = $this->productService->update(
            id: $id,
            data: ProductDTO::fromArray($request->all())
        );

        return $this->successResponse(
            message: 'Registro actualizado exitosamente',
            result  : $product
        );
    }

    /**
     * Elimina un producto de la base de datos
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse {

        $this->productService->delete($id);

        return $this->successResponse(
            message: 'Registro eliminado exitosamente'
        );
    }

}