<!-- resources/views/diet-plans/index.blade.php -->
@extends('layouts.public')
@section('title', 'Diet Plans — Kinetic Apex')
@section('meta_description', 'Explore curated nutrition programs designed for peak performance.')

@section('content')
{{-- ── DIET PLANS LIST ──────────────────────────────────────── --}}
<section id="diet-plans-index" style="background:var(--color-surface-lowest);padding:64px 0;">
    <div style="max-width:1280px;margin:0 auto;padding:0 32px;">
        <div style="text-align:center;margin-bottom:40px;">
            <div style="font-family:var(--font-heading);font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:var(--color-primary-container);margin-bottom:10px;">
                /// Nutrition Protocols
            </div>
            <h2 style="font-family:var(--font-heading);font-size:clamp(24px,4vw,36px);font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0 0 12px;">
                Diet Plans
            </h2>
            <p style="font-size:14px;color:var(--color-on-surface-variant);">
                Tailored nutrition programs to complement your training.
            </p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
            @forelse($diets as $diet)
                <div style="background:var(--color-surface-low);border-radius:12px;padding:20px;box-shadow:0 4px 12px rgba(0,0,0,.05);transition:transform .2s,box-shadow .2s;"
                     onmouseover="this.style.transform='scale(1.02)';this.style.boxShadow='0 8px 20px rgba(57,255,20,.2)'"
                     onmouseout="this.style.transform='scale(1)';this.style.boxShadow='0 4px 12px rgba(0,0,0,.05)'")
                    <h3 style="font-family:var(--font-heading);font-size:16px;font-weight:600;color:var(--color-on-surface);margin:0 0 8px;">
                        {{ $diet->title ?? $diet->name }}
                    </h3>
                    <p style="font-size:13px;color:var(--color-on-surface-variant);margin:0 0 12px;">
                        {{ Str::limit($diet->description ?? $diet->goal, 120) }}
                    </p>
                    <a href="{{ route('diet-plans.show', $diet) }}" class="btn-ghost"
                       style="padding:8px 16px;font-size:12px;">
                        View Details <span class="material-symbols-outlined" style="font-size:14px;">arrow_forward</span>
                    </a>
                </div>
            @empty
                <div style="grid-column:1/-1;text-align:center;color:var(--color-outline);">No diet plans available.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
