<?php 

namespace App\DTOs\Product;

/**
 * DTO para creación del modelo
 */
class ProductDTO {

    /** Constructor */
    public function __construct(
        private readonly int    $category_id,
        private readonly string $name,
        private readonly string $description,
        private readonly int    $price,
        private readonly int    $stock,
    ) {}

    /**
     * Obtencion de datos desde array
     *
     * @param array $data Datos del modelo
     */
    public static function fromArray(array $data): ProductDTO {
        return new self(
            category_id : data_get(target: $data, key: 'category_id'),
            name        : data_get(target: $data, key: 'name'),
            description : data_get(target: $data, key: 'description'),
            price       : data_get(target: $data, key: 'price'),
            stock       : data_get(target: $data, key: 'stock'),
        );
    }

    /** Datos del modelo */
    public function toArray(): array {
        return [
            'category_id' => $this->category_id,
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
            'stock'       => $this->stock,
        ];
    }

}