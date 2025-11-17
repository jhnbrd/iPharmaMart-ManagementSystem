@props(['label', 'value', 'icon', 'iconBg' => 'bg-[var(--color-brand-green)]'])

<div class="stat-card">
    <div class="stat-card-icon {{ $iconBg }}">
        {!! $icon !!}
    </div>
    <div class="stat-card-content">
        <p class="stat-card-label">{{ $label }}</p>
        <h3 class="stat-card-value">{{ $value }}</h3>
    </div>
</div>
