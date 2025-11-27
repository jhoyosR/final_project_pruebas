<?php 

namespace App\DTOs\Category;

/**
 * DTO para creación del modelo
 */
class CategoryDTO {

    /** Constructor */
    public function __construct(
        private readonly string $name,
    ) {}

    /**
     * Obtencion de datos desde array
     *
     * @param array $data Datos del modelo
     */
    public static function fromArray(array $data): CategoryDTO {
        return new self(
            name : data_get(target: $data, key: 'name'),
        );
    }

    /** Datos del modelo */
    public function toArray(): array {
        return [
            'name' => $this->name,
        ];
    }

}