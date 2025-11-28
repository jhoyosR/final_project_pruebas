@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Productos</h2>
    <button class="btn btn-primary" onclick="openProductModal()">Nuevo Producto</button>
</div>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Descripción</th>
                <th style="width: 150px">Acciones</th>
            </tr>
        </thead>
        <tbody id="productsTable">
            </tbody>
    </table>
</div>

<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prodModalTitle">Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="prodId">
                
                <div class="mb-2">
                    <label>Categoría</label>
                    <select id="prodCategory" class="form-select"></select>
                </div>
                <div class="mb-2">
                    <label>Nombre</label>
                    <input type="text" id="prodName" class="form-control">
                </div>
                <div class="row">
                    <div class="col-6 mb-2">
                        <label>Precio</label>
                        <input type="number" step="0.01" id="prodPrice" class="form-control">
                    </div>
                    <div class="col-6 mb-2">
                        <label>Stock</label>
                        <input type="number" id="prodStock" class="form-control">
                    </div>
                </div>
                <div class="mb-2">
                    <label>Descripción</label>
                    <textarea id="prodDesc" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="saveProduct()">Guardar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const productModal = new bootstrap.Modal(document.getElementById('productModal'));
    let categoriesList = [];

    // Cargar Lista de Productos y Categorias para el select
    function init() {
        fetch(`${API_URL}/categories`)
            .then(res => res.json())
            .then(data => {
                categoriesList = data.data || data;
                const select = document.getElementById('prodCategory');
                select.innerHTML = '<option value="">Seleccione...</option>';
                categoriesList.forEach(cat => {
                    select.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
                });
            });

        loadProducts();
    }

    function loadProducts() {
        fetch(`${API_URL}/products`)
            .then(res => res.json())
            .then(response => {
                const products = response.data || response; 
                let html = '';
                
                products.forEach(p => {
                    html += `
                        <tr>
                            <td><strong>${p.name}</strong></td>
                            <td><span class="badge bg-info text-dark">${p.category_name || 'N/A'}</span></td>
                            <td>$${p.price}</td>
                            <td>${p.stock}</td>
                            <td><small>${p.description || ''}</small></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick='editProduct(${JSON.stringify(p)})'>Editar</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteProduct(${p.id})">Eliminar</button>
                            </td>
                        </tr>
                    `;
                });
                document.getElementById('productsTable').innerHTML = html;
            });
    }

    function openProductModal() {
        document.getElementById('prodId').value = '';
        document.getElementById('prodCategory').value = '';
        document.getElementById('prodName').value = '';
        document.getElementById('prodPrice').value = '';
        document.getElementById('prodStock').value = '';
        document.getElementById('prodDesc').value = '';
        document.getElementById('prodModalTitle').innerText = 'Nuevo Producto';
        productModal.show();
    }

    function editProduct(product) {
        document.getElementById('prodId').value = product.id;
        document.getElementById('prodCategory').value = product.category_id;
        document.getElementById('prodName').value = product.name;
        document.getElementById('prodPrice').value = product.price;
        document.getElementById('prodStock').value = product.stock;
        document.getElementById('prodDesc').value = product.description;
        document.getElementById('prodModalTitle').innerText = 'Editar Producto';
        productModal.show();
    }

    function saveProduct() {
        const id = document.getElementById('prodId').value;
        
        const payload = {
            category_id: document.getElementById('prodCategory').value,
            name: document.getElementById('prodName').value,
            price: document.getElementById('prodPrice').value,
            stock: document.getElementById('prodStock').value,
            description: document.getElementById('prodDesc').value,
        };

        const method = id ? 'PUT' : 'POST';
        const url = id ? `${API_URL}/products/${id}` : `${API_URL}/products`;

        fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        }).then(res => res.json().then(data => ({status: res.status, body: data})))
          .then(result => {
            if(result.status === 200 || result.status === 201) {
                productModal.hide();
                loadProducts();
            } else {
                console.error(result);
                alert('Error al guardar. Revisa la consola para más detalles.');
            }
        });
    }

    function deleteProduct(id) {
        if(!confirm('¿Eliminar producto?')) return;
        fetch(`${API_URL}/products/${id}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json' }
        }).then(res => {
            if(res.ok) loadProducts();
        });
    }

    init();
</script>
@endpush