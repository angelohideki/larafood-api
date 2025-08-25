{{-- Script para incluir o helper de toast --}}
@push('js')
<script src="{{ asset('js/toast-helper.js') }}"></script>
<script>
    // Passa as mensagens do Laravel para o JavaScript
    window.laravelMessages = {
        @if(session('message'))
            success: @json(session('message')),
        @endif
        @if(session('error'))
            error: @json(session('error')),
        @endif
        @if(session('info'))
            info: @json(session('info')),
        @endif
        @if($errors->any())
            errors: @json($errors->all()),
        @endif
    };
    
    // Limpa as mensagens da sessão após processá-las
    @if(session('message'))
        {{ session()->forget('message') }}
    @endif
    @if(session('error'))
        {{ session()->forget('error') }}
    @endif
    @if(session('info'))
        {{ session()->forget('info') }}
    @endif
</script>
@endpush
