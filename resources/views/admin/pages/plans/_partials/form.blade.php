@include('admin.includes.alerts')

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name" class="form-label">Nome do Plano <span class="text-danger">*</span></label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   class="form-control @error('name') is-invalid @enderror" 
                   placeholder="Digite o nome do plano..." 
                   value="{{ $plan->name ?? old('name') }}"
                   required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            <label for="price" class="form-label">Preço <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">R$</span>
                </div>
                <input type="number" 
                       id="price" 
                       name="price" 
                       class="form-control @error('price') is-invalid @enderror" 
                       placeholder="0,00" 
                       step="0.01"
                       min="0"
                       value="{{ $plan->price ?? old('price') }}"
                       required>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="description" class="form-label">Descrição</label>
    <textarea id="description" 
              name="description" 
              class="form-control @error('description') is-invalid @enderror" 
              placeholder="Descreva as características e benefícios do plano..." 
              rows="3">{{ $plan->description ?? old('description') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">Máximo de 255 caracteres.</small>
</div>

<div class="form-group mt-4">
    <button type="submit" class="btn btn-primary mr-2">
        <i class="fas fa-save"></i> 
        {{ isset($plan) ? 'Atualizar Plano' : 'Criar Plano' }}
    </button>
    <a href="{{ route('plans.index') }}" class="btn btn-secondary">
        <i class="fas fa-times"></i> Cancelar
    </a>
</div>
