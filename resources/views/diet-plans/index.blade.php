@extends('layouts.public')
@section('title', 'Diet Plans & Nutrition Protocols — Kinetic Apex')
@section('meta_description', 'Explore evidence-based nutrition protocols, muscle gain diets, and fat loss meal blueprints curated by Kinetic Apex coaches.')

@section('content')
{{-- ── HERO SECTION ───────────────────────────────────────────── --}}
<section style="position:relative;background:var(--color-surface-lowest);padding:64px 0 48px;border-bottom:1px solid var(--color-surface-highest);overflow:hidden;">
    <div style="position:absolute;top:-120px;left:50%;transform:translateX(-50%);width:700px;height:350px;background:rgba(57,255,20,.07);filter:blur(110px);border-radius:50%;pointer-events:none;"></div>
    <div style="position:absolute;top:20%;right:-80px;width:300px;height:300px;background:rgba(129,207,255,.05);filter:blur(90px);border-radius:50%;pointer-events:none;"></div>

    <div style="max-width:1280px;margin:0 auto;padding:0 32px;position:relative;">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:24px;">
            <div style="max-width:760px;">
                <div style="display:inline-flex;align-items:center;gap:8px;padding:6px 14px;border-radius:999px;background:var(--color-surface-high);color:var(--color-primary-container);margin-bottom:18px;">
                    <span class="pulse-green" style="width:8px;height:8px;"></span>
                    <span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.08em;text-transform:uppercase;">Apex Nutritional Directives /// Precision Fueling</span>
                </div>
                <h1 style="font-family:var(--font-heading);font-size:clamp(32px,5vw,54px);font-weight:700;text-transform:uppercase;color:var(--color-on-surface);line-height:1.05;margin:0 0 16px;">
                    Curated Diet Plans & <span style="color:var(--color-primary-container);">Nutrition Protocols.</span>
                </h1>
                <p style="font-size:15px;color:var(--color-on-surface-variant);line-height:1.6;margin:0;">
                    Tailored macronutrient frameworks engineered for lean muscle accrual, rapid body recomposition, and sustained athletic output.
                </p>
            </div>

            @auth
                @if(auth()->user()->isAdmin())
                <div style="display:flex;align-items:center;gap:12px;">
                    <a href="{{ route('admin.diet-plans.create') }}" class="btn-primary" style="padding:12px 24px;font-size:13px;">
                        <span class="material-symbols-outlined" style="font-size:18px;">add_circle</span>
                        + Upload New Plan
                    </a>
                    <a href="{{ route('admin.diet-plans.index') }}" class="btn-ghost" style="padding:12px 18px;font-size:13px;">
                        <span class="material-symbols-outlined" style="font-size:18px;">settings</span>
                        Manage Plans
                    </a>
                </div>
                @endif
            @endauth
        </div>
    </div>
</section>

{{-- ── MAIN PLANS GRID ────────────────────────────────────────── --}}
<div style="max-width:1280px;margin:0 auto;padding:48px 32px 80px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
        <div style="display:flex;align-items:center;gap:8px;">
            <span style="font-family:var(--font-heading);color:var(--color-primary-container);font-size:14px;">///</span>
            <h2 style="font-family:var(--font-heading);font-size:22px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0;">
                All Public Nutrition Programs
            </h2>
        </div>
        <div style="font-size:12px;color:var(--color-outline);">
            Showing {{ $diets->count() }} active plans
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;">
        @forelse($diets as $diet)
        <article style="display:flex;flex-direction:column;background:var(--color-surface-low);border-radius:14px;overflow:hidden;border:1px solid var(--color-surface-highest);transition:transform .2s,box-shadow .2s,border-color .2s;"
                 onmouseover="this.style.borderColor='var(--color-primary-container)';this.style.boxShadow='var(--glow-green)';this.style.transform='translateY(-3px)'"
                 onmouseout="this.style.borderColor='var(--color-surface-highest)';this.style.boxShadow='none';this.style.transform='none'">
            
            {{-- Card Cover Image Header --}}
            <div style="height:200px;background:var(--color-surface-container);position:relative;overflow:hidden;">
                @if($diet->cover_image_url)
                    <img src="{{ $diet->cover_image_url }}" alt="{{ $diet->title }}"
                         style="width:100%;height:100%;object-fit:cover;transition:transform .4s ease;"
                         onmouseover="this.style.transform='scale(1.05)'"
                         onmouseout="this.style.transform='scale(1)'">
                @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:radial-gradient(circle at center, var(--color-surface-high) 0%, var(--color-surface-lowest) 100%);">
                        <span class="material-symbols-outlined" style="font-size:60px;color:var(--color-surface-highest);">restaurant</span>
                    </div>
                @endif
                <div style="position:absolute;inset:0;background:linear-gradient(to top, rgba(28,27,27,.9) 0%, transparent 60%);"></div>

                {{-- Goal Badge --}}
                <div style="position:absolute;top:12px;left:12px;">
                    <span class="badge-green" style="font-size:10px;text-transform:uppercase;">
                        {{ str_replace('_', ' ', $diet->goal ?? 'Nutrition') }}
                    </span>
                </div>

                {{-- Calories Pill --}}
                @if($diet->total_calories)
                <div style="position:absolute;bottom:10px;right:12px;font-size:11px;font-family:var(--font-heading);font-weight:700;color:var(--color-primary-container);background:rgba(19,19,19,.88);border:1px solid rgba(57,255,20,.3);padding:3px 10px;border-radius:6px;display:flex;align-items:center;gap:4px;">
                    <span class="material-symbols-outlined" style="font-size:13px;">local_fire_department</span>
                    {{ number_format($diet->total_calories) }} KCAL
                </div>
                @endif
            </div>

            {{-- Card Body --}}
            <div style="padding:22px;display:flex;flex-direction:column;flex:1;">
                <h3 style="font-family:var(--font-heading);font-size:18px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);margin:0 0 12px;line-height:1.25;">
                    <a href="{{ route('diet-plans.show', $diet) }}" style="color:var(--color-on-surface);text-decoration:none;transition:color .15s;" onmouseover="this.style.color='var(--color-primary-container)'" onmouseout="this.style.color='var(--color-on-surface)'">
                        {{ $diet->title ?? $diet->name }}
                    </a>
                </h3>

                {{-- Macros Row --}}
                <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:8px;margin-bottom:16px;">
                    <div style="background:var(--color-surface-container);border-radius:6px;padding:6px 8px;text-align:center;">
                        <div style="font-family:var(--font-heading);font-size:13px;font-weight:700;color:var(--color-on-surface);">{{ $diet->total_protein_g ?? 0 }}g</div>
                        <div style="font-size:9px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.05em;">Protein</div>
                    </div>
                    <div style="background:var(--color-surface-container);border-radius:6px;padding:6px 8px;text-align:center;">
                        <div style="font-family:var(--font-heading);font-size:13px;font-weight:700;color:var(--color-on-surface);">{{ $diet->total_carbs_g ?? 0 }}g</div>
                        <div style="font-size:9px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.05em;">Carbs</div>
                    </div>
                    <div style="background:var(--color-surface-container);border-radius:6px;padding:6px 8px;text-align:center;">
                        <div style="font-family:var(--font-heading);font-size:13px;font-weight:700;color:var(--color-on-surface);">{{ $diet->total_fat_g ?? 0 }}g</div>
                        <div style="font-size:9px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.05em;">Fats</div>
                    </div>
                </div>

                <p style="font-size:13px;color:var(--color-on-surface-variant);line-height:1.55;margin:0 0 18px;flex:1;">
                    {{ Str::limit($diet->description ?? 'Targeted high-compliance daily nutrition blueprint curated by athletic trainers.', 120) }}
                </p>

                {{-- Card Footer --}}
                <div style="display:flex;align-items:center;justify-content:space-between;padding-top:14px;border-top:1px solid var(--color-surface-highest);">
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--color-outline);">
                        <span class="material-symbols-outlined" style="font-size:16px;color:var(--color-primary-container);">schedule</span>
                        {{ $diet->meals->count() > 0 ? $diet->meals->count() . ' Daily Meals' : 'Full Protocol' }}
                    </div>

                    <a href="{{ route('diet-plans.show', $diet) }}" class="btn-primary" style="padding:8px 16px;font-size:11px;">
                        View Protocol <span class="material-symbols-outlined" style="font-size:14px;">arrow_forward</span>
                    </a>
                </div>
            </div>
        </article>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:64px 20px;background:var(--color-surface-low);border-radius:12px;border:1px dashed var(--color-surface-highest);">
            <span class="material-symbols-outlined" style="font-size:48px;color:var(--color-outline);margin-bottom:12px;">restaurant_menu</span>
            <div style="font-family:var(--font-heading);font-size:18px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);margin-bottom:6px;">No Diet Plans Published</div>
            <p style="font-size:13px;color:var(--color-on-surface-variant);max-width:380px;margin:0 auto 16px;">Our nutritionists and staff have not published public diet protocols yet. Check back soon!</p>
            <a href="{{ route('home') }}" class="btn-ghost" style="padding:8px 18px;font-size:11px;">Back to Home</a>
        </div>
        @endforelse
    </div>
</div>
@endsection
