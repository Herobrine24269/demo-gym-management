@extends('layouts.public')
@section('title', ($dietPlan->title ?? $dietPlan->name) . ' — Nutrition Protocol')
@section('meta_description', 'High performance nutrition framework: ' . ($dietPlan->title ?? $dietPlan->name) . ' with complete meal breakdown and macro targets.')

@section('content')
<article style="background:var(--color-surface-lowest);padding:40px 0 80px;">
    <div style="max-width:960px;margin:0 auto;padding:0 24px;">

        {{-- ── BREADCRUMBS ─────────────────────────────────────── --}}
        <nav style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--color-outline);margin-bottom:28px;flex-wrap:wrap;">
            <a href="{{ route('home') }}" style="color:var(--color-outline);text-decoration:none;" onmouseover="this.style.color='var(--color-on-surface)'" onmouseout="this.style.color='var(--color-outline)'">Home</a>
            <span>/</span>
            <a href="{{ route('diet-plans.index') }}" style="color:var(--color-outline);text-decoration:none;" onmouseover="this.style.color='var(--color-on-surface)'" onmouseout="this.style.color='var(--color-outline)'">Diet Plans</a>
            <span>/</span>
            <span style="color:var(--color-on-surface-variant);font-weight:500;">{{ Str::limit($dietPlan->title ?? $dietPlan->name, 35) }}</span>
        </nav>

        {{-- ── PLAN HEADER ─────────────────────────────────────── --}}
        <header style="margin-bottom:32px;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;flex-wrap:wrap;">
                <span class="badge-green" style="font-size:11px;padding:4px 12px;text-transform:uppercase;">
                    {{ str_replace('_', ' ', $dietPlan->goal ?? 'Nutrition Protocol') }}
                </span>
                @if($dietPlan->total_calories)
                <span style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--color-primary-container);font-family:var(--font-heading);font-weight:700;">
                    <span class="material-symbols-outlined" style="font-size:16px;">local_fire_department</span>
                    {{ number_format($dietPlan->total_calories) }} KCAL / DAY
                </span>
                @endif
                <span style="color:var(--color-surface-highest);">•</span>
                <span style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--color-outline);">
                    <span class="material-symbols-outlined" style="font-size:16px;">restaurant</span>
                    {{ $dietPlan->meals->count() }} Structured Meals
                </span>
            </div>

            <h1 style="font-family:var(--font-heading);font-size:clamp(30px,5vw,50px);font-weight:700;text-transform:uppercase;color:var(--color-on-surface);line-height:1.1;margin:0 0 20px;">
                {{ $dietPlan->title ?? $dietPlan->name }}
            </h1>

            {{-- Daily Targets Summary Card --}}
            <div style="background:var(--color-surface-low);border:1px solid var(--color-surface-highest);border-radius:12px;padding:20px;display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:14px;margin-bottom:28px;">
                <div style="text-align:center;padding:12px;background:var(--color-surface-container);border-radius:8px;">
                    <div style="font-family:var(--font-heading);font-size:24px;font-weight:700;color:var(--color-primary-container);">
                        {{ $dietPlan->total_calories ? number_format($dietPlan->total_calories) : '—' }}
                    </div>
                    <div style="font-size:11px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.06em;margin-top:2px;">Daily Calories</div>
                </div>
                <div style="text-align:center;padding:12px;background:var(--color-surface-container);border-radius:8px;">
                    <div style="font-family:var(--font-heading);font-size:24px;font-weight:700;color:var(--color-on-surface);">
                        {{ $dietPlan->total_protein_g ?? 0 }}g
                    </div>
                    <div style="font-size:11px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.06em;margin-top:2px;">Protein</div>
                </div>
                <div style="text-align:center;padding:12px;background:var(--color-surface-container);border-radius:8px;">
                    <div style="font-family:var(--font-heading);font-size:24px;font-weight:700;color:var(--color-on-surface);">
                        {{ $dietPlan->total_carbs_g ?? 0 }}g
                    </div>
                    <div style="font-size:11px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.06em;margin-top:2px;">Carbohydrates</div>
                </div>
                <div style="text-align:center;padding:12px;background:var(--color-surface-container);border-radius:8px;">
                    <div style="font-family:var(--font-heading);font-size:24px;font-weight:700;color:var(--color-on-surface);">
                        {{ $dietPlan->total_fat_g ?? 0 }}g
                    </div>
                    <div style="font-size:11px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.06em;margin-top:2px;">Dietary Fats</div>
                </div>
            </div>
        </header>

        {{-- ── COVER IMAGE BANNER ──────────────────────────────── --}}
        @if($dietPlan->cover_image_url)
        <div style="margin-bottom:36px;border-radius:14px;overflow:hidden;border:1px solid var(--color-surface-highest);box-shadow:0 12px 36px rgba(0,0,0,.5);max-height:460px;">
            <img src="{{ $dietPlan->cover_image_url }}" alt="{{ $dietPlan->title }}" style="width:100%;height:100%;object-fit:cover;display:block;">
        </div>
        @endif

        {{-- ── MEAL PROTOCOL BREAKDOWN ─────────────────────────── --}}
        <div style="margin-bottom:48px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:24px;">
                <span style="font-family:var(--font-heading);color:var(--color-primary-container);font-size:14px;">///</span>
                <h2 style="font-family:var(--font-heading);font-size:22px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0;">
                    Daily Meal Breakdown & Timing
                </h2>
            </div>

            @if($dietPlan->meals->isNotEmpty())
                <div style="display:flex;flex-direction:column;gap:20px;">
                    @foreach($dietPlan->meals as $idx => $meal)
                    <div style="background:var(--color-surface-low);border-radius:12px;border:1px solid var(--color-surface-highest);padding:22px;overflow:hidden;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:32px;height:32px;border-radius:6px;background:rgba(57,255,20,.15);border:1px solid rgba(57,255,20,.3);color:var(--color-primary-container);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:13px;font-weight:700;">
                                    0{{ $idx + 1 }}
                                </div>
                                <div>
                                    <h3 style="font-family:var(--font-heading);font-size:17px;font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0;">
                                        {{ $meal->meal_name }}
                                    </h3>
                                    @if($meal->time_suggestion)
                                    <div style="font-size:11px;color:var(--color-outline);display:flex;align-items:center;gap:4px;margin-top:2px;">
                                        <span class="material-symbols-outlined" style="font-size:13px;color:var(--color-primary-container);">schedule</span>
                                        {{ $meal->time_suggestion }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Food Items Table --}}
                        @if($meal->foodItems->isNotEmpty())
                        <div style="overflow-x:auto;">
                            <table style="width:100%;border-collapse:collapse;font-size:13px;text-align:left;">
                                <thead>
                                    <tr style="border-bottom:1px solid var(--color-surface-highest);color:var(--color-outline);font-size:10px;text-transform:uppercase;letter-spacing:.06em;">
                                        <th style="padding:8px 10px;">Food Item</th>
                                        <th style="padding:8px 10px;">Portion</th>
                                        <th style="padding:8px 10px;text-align:right;">Calories</th>
                                        <th style="padding:8px 10px;text-align:right;">Protein</th>
                                        <th style="padding:8px 10px;text-align:right;">Carbs</th>
                                        <th style="padding:8px 10px;text-align:right;">Fat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($meal->foodItems as $item)
                                    <tr style="border-bottom:1px solid rgba(255,255,255,.04);color:var(--color-on-surface-variant);">
                                        <td style="padding:10px;font-weight:600;color:var(--color-on-surface);">{{ $item->food_name }}</td>
                                        <td style="padding:10px;color:var(--color-outline);">{{ $item->quantity }}</td>
                                        <td style="padding:10px;text-align:right;color:var(--color-primary-container);font-family:var(--font-heading);font-weight:700;">{{ $item->calories }} kcal</td>
                                        <td style="padding:10px;text-align:right;">{{ $item->protein_g }}g</td>
                                        <td style="padding:10px;text-align:right;">{{ $item->carbs_g }}g</td>
                                        <td style="padding:10px;text-align:right;">{{ $item->fat_g }}g</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div style="font-size:12px;color:var(--color-outline);padding:8px 0;">No food items specified for this meal.</div>
                        @endif
                    </div>
                    @endforeach
                </div>
            @else
                <div style="padding:32px;text-align:center;background:var(--color-surface-low);border-radius:12px;border:1px dashed var(--color-surface-highest);color:var(--color-outline);">
                    Consult your assigned trainer or staff for individual meal breakdowns on this protocol.
                </div>
            @endif
        </div>

        {{-- ── FOOTER ACTIONS ──────────────────────────────────── --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 0;border-top:1px solid var(--color-surface-highest);border-bottom:1px solid var(--color-surface-highest);margin-bottom:48px;flex-wrap:wrap;gap:14px;">
            <a href="{{ route('diet-plans.index') }}" class="btn-ghost" style="padding:10px 20px;">
                <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span> Back to All Diet Plans
            </a>

            @auth
            <a href="{{ auth()->user()->dashboardRoute() }}" class="btn-primary" style="padding:10px 24px;">
                <span class="material-symbols-outlined" style="font-size:18px;">dashboard</span> Go to Dashboard
            </a>
            @else
            <a href="{{ route('login') }}" class="btn-primary" style="padding:10px 24px;">
                Join &amp; Assign to Profile <span class="material-symbols-outlined" style="font-size:18px;">bolt</span>
            </a>
            @endauth
        </div>

    </div>
</article>
@endsection
