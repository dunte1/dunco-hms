<div class="card {{ ($result['success'] ?? $result['verified'] ?? false) ? 'bg-success text-white' : 'bg-danger text-white' }}">
    <div class="card-header py-2">
        <strong>{{ $label }}</strong>
        @if(($result['success'] ?? $result['verified'] ?? false))
            <span class="float-right"><i class="fas fa-check-circle"></i></span>
        @else
            <span class="float-right"><i class="fas fa-times-circle"></i></span>
        @endif
    </div>
    <div class="card-body p-0">
        <pre class="m-0 bg-dark text-light p-2 mb-0"><code>{{ json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
    </div>
</div>