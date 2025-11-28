@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Categorías</h2>
    <button class="btn btn-primary" onclick="openModal()">Nueva Categoría</button>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="categoriesTable">
        </tbody>
</table>

<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="catId">
                <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" id="catName" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="saveCategory()">Guardar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));

    // Cargar Categorías
    function loadCategories() {
        fetch(`${API_URL}/categories`)
            .then(res => res.json())
            .then(response => {
                const data = response.data || response; 
                let html = '';
                data.forEach(cat => {
                    html += `
                        <tr>
                            <td>${cat.name}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editCategory(${cat.id}, '${cat.name}')">Editar</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteCategory(${cat.id})">Eliminar</button>
                            </td>
                        </tr>
                    `;
                });
                document.getElementById('categoriesTable').innerHTML = html;
            });
    }

    // Abrir Modal
    function openModal() {
        document.getElementById('catId').value = '';
        document.getElementById('catName').value = '';
        document.getElementById('modalTitle').innerText = 'Nueva Categoría';
        categoryModal.show();
    }

    // Editar (Pre-llenar modal)
    function editCategory(id, name) {
        document.getElementById('catId').value = id;
        document.getElementById('catName').value = name;
        document.getElementById('modalTitle').innerText = 'Editar Categoría';
        categoryModal.show();
    }

    // Guardar (Crear o Actualizar)
    function saveCategory() {
        const id = document.getElementById('catId').value;
        const name = document.getElementById('catName').value;
        const method = id ? 'PUT' : 'POST';
        const url = id ? `${API_URL}/categories/${id}` : `${API_URL}/categories`;

        fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ name: name })
        }).then(res => {
            if(res.ok) {
                categoryModal.hide();
                loadCategories();
            } else {
                alert('Error al guardar');
            }
        });
    }

    // Eliminar
    function deleteCategory(id) {
        if(!confirm('¿Estás seguro?')) return;
        fetch(`${API_URL}/categories/${id}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json' }
        }).then(res => {
            if(res.ok) loadCategories();
        });
    }

    // Iniciar carga
    loadCategories();
</script>
@endpush