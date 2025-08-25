@include('admin.includes.alerts')

<div class="row">
    <div class="col-md-8">
        <!-- Informações Básicas -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informações Básicas</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="title" class="form-label">
                        <i class="fas fa-tag"></i> Título do Produto <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                        id="title"
                        name="title"
                        class="form-control @error('title') is-invalid @enderror"
                        placeholder="Digite o título do produto"
                        value="{{ $product->title ?? old('title') }}"
                        required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price" class="form-label">
                        <i class="fas fa-dollar-sign"></i> Preço <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">R$</span>
                        </div>
                        <input type="text"
                               id="price"
                               name="price"
                               class="form-control @error('price') is-invalid @enderror"
                               placeholder="0,00"
                               value="{{ isset($product) ? number_format($product->price, 2, ',', '.') : old('price') }}"
                               required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="form-text text-muted">Digite o preço em reais (ex: 25,90)</small>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left"></i> Descrição <span class="text-danger">*</span>
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Descreva o produto detalhadamente..."
                              required>{{ $product->description ?? old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        <span id="char-count">0</span>/500 caracteres
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Upload de Imagem -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-image"></i> Imagem do Produto</h5>
            </div>
            <div class="card-body text-center">
                <div class="image-upload-container">
                    <div class="image-preview mb-3" id="imagePreview">
                        @if(isset($product) && $product->image)
                            <img src="{{ url("storage/{$product->image}") }}"
                                 alt="Imagem atual"
                                 class="img-fluid rounded"
                                 style="max-height: 200px;">
                            <p class="text-muted mt-2">Imagem atual</p>
                        @else
                            <div class="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Clique para selecionar uma imagem</p>
                            </div>
                        @endif
                    </div>

                    <input type="file"
                        id="image"
                        name="image"
                        class="form-control-file @error('image') is-invalid @enderror"
                        accept="image/*"
                        style="display: none;">

                    <button type="button"
                            class="btn btn-outline-primary btn-sm"
                            onclick="document.getElementById('image').click()"
                            id="imageButton">
                        <i class="fas fa-upload"></i>
                        {{ isset($product) && $product->image ? 'Alterar Imagem' : 'Selecionar Imagem' }}
                    </button>

                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    <small class="form-text text-muted mt-2">
                        Formatos aceitos: JPG, PNG, GIF<br>
                        Tamanho máximo: 2MB
                    </small>
                </div>
            </div>
        </div>

        <!-- Ações -->
        <div class="card">
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(isset($product))
                        <!-- Botão para edição -->
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save"></i>
                            Atualizar Produto
                        </button>
                    @else
                        <!-- Botão para cadastro -->
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save"></i>
                            Salvar Produto
                        </button>
                    @endif

                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript simplificado -->
<script>
// Preview da imagem
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}"
                     alt="Preview"
                     class="img-fluid rounded"
                     style="max-height: 200px;">
                <p class="text-muted mt-2">Nova imagem selecionada</p>
            `;
        };
        reader.readAsDataURL(file);
    }
});

// Contador de caracteres
const description = document.getElementById('description');
const charCount = document.getElementById('char-count');

function updateCharCount() {
    const count = description.value.length;
    charCount.textContent = count;

    if (count > 500) {
        charCount.parentElement.classList.add('text-danger');
    } else {
        charCount.parentElement.classList.remove('text-danger');
    }
}

description.addEventListener('input', updateCharCount);
updateCharCount(); // Inicializar contador

// Formatação do preço com máscara monetária
const priceInput = document.getElementById('price');

function formatCurrency(value) {
    // Remove tudo que não é dígito
    value = value.replace(/\D/g, '');

    // Converte para centavos
    value = (value / 100).toFixed(2) + '';

    // Substitui o ponto por vírgula
    value = value.replace('.', ',');

    // Adiciona pontos para milhares
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    return value;
}

priceInput.addEventListener('input', function(e) {
    let value = e.target.value;
    e.target.value = formatCurrency(value);
});

// Converter preço antes do envio
document.querySelector('form').addEventListener('submit', function(e) {
    const priceValue = priceInput.value;
    // Converte de "1.234,56" para "1234.56"
    const numericValue = priceValue.replace(/\./g, '').replace(',', '.');
    priceInput.value = numericValue;

    // Validação personalizada para imagem (apenas para novos produtos)
    @if(!isset($product))
    const imageInput = document.getElementById('image');
    if (!imageInput.files || imageInput.files.length === 0) {
        e.preventDefault();
        alert('Por favor, selecione uma imagem para o produto.');
        document.getElementById('imageButton').focus();
        return false;
    }
    @endif
});
</script>

<!-- CSS adicional -->
<style>
.image-upload-container {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 20px;
    transition: border-color 0.3s ease;
}

.image-upload-container:hover {
    border-color: #007bff;
}

.upload-placeholder {
    padding: 40px 20px;
}

.form-label {
    font-weight: 600;
    margin-bottom: 8px;
}

.card-header h5 {
    color: #495057;
}

.btn-lg {
    padding: 12px 24px;
    font-size: 1.1rem;
}

.is-invalid {
    border-color: #dc3545;
}

.is-valid {
    border-color: #28a745;
}

@media (max-width: 768px) {
    .col-md-8, .col-md-4 {
        margin-bottom: 1rem;
    }
}
</style>
