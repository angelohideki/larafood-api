@include('admin.includes.alerts')

@csrf

<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="name" class="font-weight-bold">
                <i class="fas fa-user-tag"></i> Nome <span class="text-danger">*</span>
            </label>
            <input type="text" 
                   id="name"
                   name="name" 
                   class="form-control form-control-lg" 
                   placeholder="Digite o nome do perfil" 
                   value="{{ $profile->name ?? old('name') }}"
                   required>
        </div>
        
        <div class="form-group">
            <label for="description" class="font-weight-bold">
                <i class="fas fa-align-left"></i> Descrição
            </label>
            <textarea id="description"
                     name="description" 
                     class="form-control" 
                     rows="3"
                     placeholder="Digite uma descrição para o perfil (opcional)">{{ $profile->description ?? old('description') }}</textarea>
            <small class="form-text text-muted">
                Descreva as responsabilidades e propósito deste perfil.
            </small>
        </div>
        
        <div class="form-group mt-4">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save"></i> 
                {{ isset($profile) ? 'Atualizar Perfil' : 'Criar Perfil' }}
            </button>
            <a href="{{ route('profiles.index') }}" class="btn btn-secondary btn-lg ml-2">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle"></i> Informações
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-2">
                    <strong>Nome:</strong> Campo obrigatório que identifica o perfil.
                </p>
                <p class="small text-muted mb-0">
                    <strong>Descrição:</strong> Campo opcional para detalhar o propósito do perfil.
                </p>
            </div>
        </div>
    </div>
</div>
