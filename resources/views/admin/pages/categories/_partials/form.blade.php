@include('admin.includes.alerts')

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-form text-primary"></i>
            Dados da Categoria
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="font-weight-bold">
                        Nome da Categoria <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           class="form-control @error('name') is-invalid @enderror" 
                           placeholder="Digite o nome da categoria" 
                           value="{{ $category->name ?? old('name') }}"
                           maxlength="100">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <div class="invalid-feedback"></div>
                    @enderror
                    <small class="form-text text-muted">
                        Nome que será exibido no sistema
                    </small>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold text-muted">
                        URL Gerada
                    </label>
                    <input type="text" 
                           class="form-control-plaintext" 
                           id="urlPreview"
                           readonly 
                           placeholder="A URL será gerada automaticamente">
                    <small class="form-text text-muted">
                        URL amigável gerada automaticamente
                    </small>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description" class="font-weight-bold">
                Descrição <span class="text-danger">*</span>
            </label>
            <textarea name="description" 
                      id="description"
                      class="form-control @error('description') is-invalid @enderror" 
                      rows="4" 
                      placeholder="Descreva a categoria..."
                      maxlength="500">{{ $category->description ?? old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @else
                <div class="invalid-feedback"></div>
            @enderror
            <small class="form-text text-muted">
                <span id="charCount">0</span>/500 caracteres
            </small>
        </div>
    </div>
    
    <div class="card-footer bg-light">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
            
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Salvar
            </button>
        </div>
    </div>
</div>

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameField = document.getElementById('name');
    const descriptionField = document.getElementById('description');
    const urlPreview = document.getElementById('urlPreview');
    const charCount = document.getElementById('charCount');
    
    // Função para gerar URL amigável
    function generateSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[áàâãä]/g, 'a')
            .replace(/[éèêë]/g, 'e')
            .replace(/[íìîï]/g, 'i')
            .replace(/[óòôõö]/g, 'o')
            .replace(/[úùûü]/g, 'u')
            .replace(/[ç]/g, 'c')
            .replace(/[ñ]/g, 'n')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    }
    
    // Atualizar preview da URL
    if (nameField && urlPreview) {
        function updateUrlPreview() {
            const slug = generateSlug(nameField.value);
            urlPreview.value = slug || 'url-sera-gerada-automaticamente';
        }
        
        nameField.addEventListener('input', updateUrlPreview);
        updateUrlPreview(); // Executar na inicialização
    }
    
    // Contador de caracteres
    if (descriptionField && charCount) {
        function updateCharCount() {
            const count = descriptionField.value.length;
            charCount.textContent = count;
            
            if (count > 450) {
                charCount.classList.add('text-warning');
            } else if (count > 480) {
                charCount.classList.remove('text-warning');
                charCount.classList.add('text-danger');
            } else {
                charCount.classList.remove('text-warning', 'text-danger');
            }
        }
        
        descriptionField.addEventListener('input', updateCharCount);
        updateCharCount(); // Executar na inicialização
    }
});
</script>
@endpush
