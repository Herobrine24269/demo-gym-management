@extends('layouts.public')
@section('title', $dietPlan->title . ' — Diet Plan')
@section('meta_description', Str::limit($dietPlan->goal, 150))

@section('content')
{{-- ── SINGLE DIET PLAN DETAIL ──────────────────────────────────────── --}}
<section id="diet-plan-detail" style="background:var(--color-surface-lowest);padding:80px 0;">
    <div style="max-width:960px;margin:0 auto;padding:0 32px;">
        <div style="text-align:center;margin-bottom:40px;">
            <h1 style="font-family:var(--font-heading);font-size:clamp(28px,5vw,48px);font-weight:700;color:var(--color-on-surface);margin:0;">
                {{ $dietPlan->title }}
            </h1>
            <p style="font-size:15px;color:var(--color-on-surface-variant);margin-top:12px;">
                {{ $dietPlan->goal }}
            </p>
        </div>

        @if($dietPlan->cover_image)
            <div style="margin-bottom:40px;">
                <img src="{{ $dietPlan->cover_image_url }}" alt="{{ $dietPlan->title }}"
                     style="width:100%;border-radius:12px;object-fit:cover;">
            </div>
        @endif

        @if($dietPlan->meals->isNotEmpty())
            <div>
                @foreach($dietPlan->meals as $meal)
                    <div style="margin-bottom:32px;">
                        <h3 style="font-family:var(--font-heading);font-size:20px;font-weight:600;color:var(--color-on-surface);margin:0 0 8px;">
                            {{ $meal->meal_name }} – {{ $meal->time_suggestion }}
                        </h3>
                        @if($meal->foodItems->isNotEmpty())
                            <ul style="list-style:none;padding:0;margin:0;">
                                @foreach($meal->foodItems as $item)
                                    <li style="margin-bottom:6px;font-size:14px;color:var(--color-on-surface-variant);">
                                        {{ $item->food_name }} ({{ $item->quantity }}) –
                                        {{ $item->calories }} kcal, {{ $item->protein_g }}g P, {{ $item->carbs_g }}g C, {{ $item->fat_g }}g F
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
