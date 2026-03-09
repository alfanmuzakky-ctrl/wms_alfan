@props(['resource', 'label'])

<div class="toolbar">
    <button class="btn-secondary" onclick="openCreate('{{ $resource }}')">
        <span class="icon">+</span> Add {{ $label }}
    </button>

    <button class="btn-secondary" onclick="loadPage('/{{ $resource }}')">
        <span class="icon">⟳</span> Refresh
    </button>
</div>