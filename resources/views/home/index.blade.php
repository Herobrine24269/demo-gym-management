@extends('layouts.public')
@section('title', 'Kinetic Apex — Premier Athletic Facility')
@section('meta_description', 'Premier equipment, elite coaches, tailored nutrition. Join Kinetic Apex today.')

@section('content')

{{-- ── HERO ──────────────────────────────────────────────────── --}}
<section style="position:relative;width:100%;overflow:hidden;background:var(--color-surface-lowest);padding:80px 0 64px;">
    <div style="position:absolute;top:-100px;left:50%;transform:translateX(-50%);width:700px;height:350px;background:rgba(57,255,20,.07);filter:blur(100px);border-radius:50%;pointer-events:none;"></div>
    <div style="position:absolute;top:30%;right:-100px;width:320px;height:320px;background:rgba(129,207,255,.06);filter:blur(80px);border-radius:50%;pointer-events:none;"></div>

    <div style="max-width:1280px;margin:0 auto;padding:0 32px;">
        <div style="display:grid;grid-template-columns:7fr 5fr;gap:40px;align-items:center;">
            <div>
                <div style="display:inline-flex;align-items:center;gap:8px;padding:6px 14px;border-radius:999px;background:var(--color-surface-high);color:var(--color-primary-container);margin-bottom:24px;">
                    <span class="pulse-green" style="width:8px;height:8px;"></span>
                    <span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.08em;text-transform:uppercase;">Precision High-Output Facility /// Sector 4</span>
                </div>
                <h1 style="font-family:var(--font-heading);font-size:clamp(36px,6vw,64px);font-weight:700;text-transform:uppercase;color:var(--color-on-surface);line-height:1;margin:0 0 20px;">
                    UNLEASH PEAK <span style="color:var(--color-primary-container);">PERFORMANCE.</span><br>
                    FORGED FOR ATHLETES.
                </h1>
                <p style="font-size:16px;color:var(--color-on-surface-variant);max-width:520px;margin-bottom:32px;line-height:1.6;">
                    Premier equipment, tailored nutrition protocols, and elite strength coaches. Join an uncompromising community engineered purely for tangible physiological results.
                </p>
                <div style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:40px;">
                    @auth
                    <a href="{{ auth()->user()->dashboardRoute() }}" class="btn-primary" style="padding:14px 28px;font-size:14px;">
                        <span class="material-symbols-outlined" style="font-size:18px;">dashboard</span> Go to Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="btn-primary" style="padding:14px 28px;font-size:14px;">
                        Member Login <span class="material-symbols-outlined" style="font-size:18px;">bolt</span>
                    </a>
                    @endauth
                    <a href="#diet-plans" class="btn-ghost" style="padding:14px 28px;font-size:14px;">
                        View Diet Plans <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-primary-container);">restaurant</span>
                    </a>
                </div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;padding:16px;background:rgba(32,31,31,.7);border-radius:12px;">
                    @foreach([['2,400+','Active Members'],['98.4%','Retention Rate'],['24/7','Facility Access']] as [$n,$l])
                    <div>
                        <div style="font-family:var(--font-heading);font-size:clamp(28px,4vw,48px);font-weight:700;color:var(--color-primary-container);line-height:1;">{{ $n }}</div>
                        <div style="font-family:var(--font-heading);font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:var(--color-on-surface-variant);margin-top:4px;">{{ $l }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div>
                <div style="border-radius:12px;overflow:hidden;background:var(--color-surface-high);aspect-ratio:4/5;display:flex;align-items:center;justify-content:center;position:relative;">
                    <span class="material-symbols-outlined" style="font-size:120px;color:var(--color-surface-highest);">fitness_center</span>
                    <div style="position:absolute;inset:0;background:linear-gradient(to top,var(--color-surface-lowest) 0%,transparent 50%);"></div>
                    <div style="position:absolute;bottom:16px;left:16px;right:16px;background:rgba(28,27,27,.9);backdrop-filter:blur(8px);padding:12px 16px;border-radius:8px;display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <div style="font-family:var(--font-heading);font-size:10px;letter-spacing:.08em;color:var(--color-outline);text-transform:uppercase;">Desk Check-In Protocol</div>
                            <div style="font-family:var(--font-heading);font-size:14px;font-weight:600;color:var(--color-on-surface);text-transform:uppercase;">Staff Assisted Manual Entry</div>
                        </div>
                        <div style="width:40px;height:40px;border-radius:6px;background:rgba(57,255,20,.2);display:flex;align-items:center;justify-content:center;color:var(--color-primary-container);">
                            <span class="material-symbols-outlined">badge</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── GYM SHOWCASE CAROUSEL ─────────────────────────────────── --}}
<section id="gym-showcase" style="background:var(--color-surface-lowest);padding:64px 0;overflow:hidden;">
    <div style="max-width:1280px;margin:0 auto;padding:0 32px;">

        {{-- Section Header --}}
        <div style="text-align:center;margin-bottom:40px;">
            <div style="display:inline-flex;align-items:center;gap:8px;color:var(--color-primary-container);margin-bottom:10px;">
                <span style="font-family:var(--font-heading);font-size:14px;">///</span>
                <span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.08em;text-transform:uppercase;">Facility Tour</span>
            </div>
            <h2 style="font-family:var(--font-heading);font-size:clamp(24px,4vw,36px);font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0 0 12px;">
                Inside Kinetic Apex
            </h2>
            <p style="font-size:14px;color:var(--color-on-surface-variant);max-width:480px;margin:0 auto;">
                State-of-the-art equipment, purpose-built zones — engineered for peak athletic performance.
            </p>
        </div>

        {{-- Carousel Wrapper --}}
        <div id="gymCarousel" style="position:relative;border-radius:16px;overflow:hidden;box-shadow:0 0 60px rgba(57,255,20,.12);aspect-ratio:16/7;">

            {{-- Slides --}}
            @php
                $slides = [
                    [
                        'img'   => asset('images/gym-showcase/slide-weights.jpg'),
                        'tag'   => 'Zone 01 — Iron Lab',
                        'title' => 'Free Weights & Power Racks',
                        'desc'  => 'Olympic barbells, heavy dumbbells up to 150 kg, and dedicated squat platforms engineered for serious lifters.',
                        'icon'  => 'fitness_center',
                    ],
                    [
                        'img'   => asset('images/gym-showcase/slide-cardio.jpg'),
                        'tag'   => 'Zone 02 — Cardio Core',
                        'title' => 'Cardio & Conditioning',
                        'desc'  => 'Smart treadmills, rowing machines, and assault bikes — all connected to heart-rate monitoring dashboards.',
                        'icon'  => 'directions_run',
                    ],
                    [
                        'img'   => asset('images/gym-showcase/slide-functional.jpg'),
                        'tag'   => 'Zone 03 — Functional Arena',
                        'title' => 'Functional Training Zone',
                        'desc'  => 'Battle ropes, TRX rigs, pull-up bars, and open-floor space for HIIT, CrossFit, and athletic drills.',
                        'icon'  => 'sports_gymnastics',
                    ],
                ];
            @endphp

            @foreach($slides as $idx => $slide)
            <div class="gc-slide" data-slide="{{ $idx }}" style="position:absolute;inset:0;opacity:{{ $idx===0 ? 1 : 0 }};transition:opacity .7s ease,transform .7s ease;transform:{{ $idx===0 ? 'scale(1)' : 'scale(1.04)' }};z-index:{{ $idx===0 ? 2 : 1 }};">
                {{-- Background Image --}}
                <img src="{{ $slide['img'] }}" alt="{{ $slide['title'] }}" style="width:100%;height:100%;object-fit:cover;display:block;">

                {{-- Dark gradient overlay --}}
                <div style="position:absolute;inset:0;background:linear-gradient(90deg,rgba(10,10,10,.85) 0%,rgba(10,10,10,.4) 55%,rgba(10,10,10,.1) 100%);"></div>

                {{-- Green accent glow --}}
                <div style="position:absolute;bottom:-60px;left:80px;width:300px;height:200px;background:rgba(57,255,20,.06);filter:blur(60px);border-radius:50%;pointer-events:none;"></div>

                {{-- Content overlay --}}
                <div style="position:absolute;inset:0;display:flex;flex-direction:column;justify-content:center;padding:48px 56px;">
                    {{-- Tag --}}
                    <div style="display:inline-flex;align-items:center;gap:8px;padding:5px 14px;border-radius:999px;background:rgba(57,255,20,.15);border:1px solid rgba(57,255,20,.3);width:fit-content;margin-bottom:18px;">
                        <span class="material-symbols-outlined" style="font-size:13px;color:var(--color-primary-container);">{{ $slide['icon'] }}</span>
                        <span style="font-family:var(--font-heading);font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:var(--color-primary-container);">{{ $slide['tag'] }}</span>
                    </div>
                    {{-- Title --}}
                    <h3 style="font-family:var(--font-heading);font-size:clamp(26px,4vw,48px);font-weight:700;text-transform:uppercase;color:#fff;margin:0 0 14px;line-height:1.05;max-width:520px;">
                        {{ $slide['title'] }}
                    </h3>
                    {{-- Description --}}
                    <p style="font-size:15px;color:rgba(255,255,255,.7);max-width:440px;line-height:1.6;margin:0 0 28px;">
                        {{ $slide['desc'] }}
                    </p>
                    {{-- Slide counter --}}
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span style="font-family:var(--font-heading);font-size:28px;font-weight:700;color:var(--color-primary-container);">0{{ $idx+1 }}</span>
                        <span style="font-size:12px;color:rgba(255,255,255,.4);font-family:var(--font-heading);">/ 0{{ count($slides) }}</span>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Prev / Next Arrows --}}
            <button id="gc-prev" onclick="gymCarouselMove(-1)" style="position:absolute;left:20px;top:50%;transform:translateY(-50%);z-index:10;width:48px;height:48px;border-radius:50%;background:rgba(28,27,27,.8);backdrop-filter:blur(8px);border:1px solid rgba(57,255,20,.3);color:var(--color-primary-container);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s,box-shadow .2s;" onmouseover="this.style.background='rgba(57,255,20,.2)';this.style.boxShadow='0 0 20px rgba(57,255,20,.3)'" onmouseout="this.style.background='rgba(28,27,27,.8)';this.style.boxShadow='none'">
                <span class="material-symbols-outlined" style="font-size:24px;">chevron_left</span>
            </button>
            <button id="gc-next" onclick="gymCarouselMove(1)" style="position:absolute;right:20px;top:50%;transform:translateY(-50%);z-index:10;width:48px;height:48px;border-radius:50%;background:rgba(28,27,27,.8);backdrop-filter:blur(8px);border:1px solid rgba(57,255,20,.3);color:var(--color-primary-container);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s,box-shadow .2s;" onmouseover="this.style.background='rgba(57,255,20,.2)';this.style.boxShadow='0 0 20px rgba(57,255,20,.3)'" onmouseout="this.style.background='rgba(28,27,27,.8)';this.style.boxShadow='none'">
                <span class="material-symbols-outlined" style="font-size:24px;">chevron_right</span>
            </button>

            {{-- Progress Bar --}}
            <div style="position:absolute;bottom:0;left:0;right:0;height:3px;background:rgba(255,255,255,.1);z-index:10;">
                <div id="gc-progress" style="height:100%;background:var(--color-primary-container);width:0%;transition:width 4s linear;"></div>
            </div>
        </div>

        {{-- Dot Navigation --}}
        <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-top:24px;" id="gc-dots">
            @foreach($slides as $idx => $slide)
            <button onclick="gymCarouselGo({{ $idx }})" data-dot="{{ $idx }}" style="width:{{ $idx===0 ? '28px' : '8px' }};height:8px;border-radius:999px;background:{{ $idx===0 ? 'var(--color-primary-container)' : 'rgba(255,255,255,.2)' }};border:none;cursor:pointer;transition:width .3s,background .3s;padding:0;" aria-label="Go to slide {{ $idx+1 }}"></button>
            @endforeach
        </div>

        {{-- Thumbnail Strip --}}
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:20px;">
            @foreach($slides as $idx => $slide)
            <div data-thumb="{{ $idx }}" onclick="gymCarouselGo({{ $idx }})" style="border-radius:8px;overflow:hidden;cursor:pointer;border:2px solid {{ $idx===0 ? 'var(--color-primary-container)' : 'transparent' }};transition:border-color .3s,box-shadow .3s;aspect-ratio:16/6;position:relative;" onmouseover="this.style.boxShadow='0 0 16px rgba(57,255,20,.25)'" onmouseout="this.style.boxShadow='none'">
                <img src="{{ $slide['img'] }}" alt="{{ $slide['title'] }}" style="width:100%;height:100%;object-fit:cover;display:block;filter:brightness(.6);transition:filter .3s;" onmouseover="this.style.filter='brightness(.85)'" onmouseout="this.style.filter='brightness(.6)'">
                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                    <span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;text-transform:uppercase;color:rgba(255,255,255,.85);text-shadow:0 1px 6px rgba(0,0,0,.8);">{{ $slide['tag'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
(function(){
    let current = 0;
    const slides = document.querySelectorAll('.gc-slide');
    const dots   = document.querySelectorAll('#gc-dots button');
    const thumbs = document.querySelectorAll('[data-thumb]');
    const prog   = document.getElementById('gc-progress');
    let autoTimer, progTimer;

    function go(n){
        slides[current].style.opacity = '0';
        slides[current].style.transform = 'scale(1.04)';
        slides[current].style.zIndex = '1';
        dots[current].style.width = '8px';
        dots[current].style.background = 'rgba(255,255,255,.2)';
        thumbs[current].style.borderColor = 'transparent';

        current = (n + slides.length) % slides.length;

        slides[current].style.opacity = '1';
        slides[current].style.transform = 'scale(1)';
        slides[current].style.zIndex = '2';
        dots[current].style.width = '28px';
        dots[current].style.background = 'getComputedStyle(document.documentElement).getPropertyValue("--color-primary-container")';
        dots[current].style.background = '#39ff14';
        thumbs[current].style.borderColor = '#39ff14';

        // Reset progress bar
        prog.style.transition = 'none';
        prog.style.width = '0%';
        setTimeout(()=>{ prog.style.transition = 'width 4s linear'; prog.style.width = '100%'; }, 30);
    }

    window.gymCarouselMove = (dir) => { clearInterval(autoTimer); go(current + dir); startAuto(); };
    window.gymCarouselGo   = (n)   => { clearInterval(autoTimer); go(n); startAuto(); };

    function startAuto(){
        clearInterval(autoTimer);
        prog.style.transition = 'none'; prog.style.width = '0%';
        setTimeout(()=>{ prog.style.transition = 'width 4s linear'; prog.style.width = '100%'; }, 30);
        autoTimer = setInterval(()=>{ go(current + 1); }, 4000);
    }

    // Init
    thumbs[0].style.borderColor = '#39ff14';
    startAuto();
})();
</script>
@endpush

{{-- ── POSTS ─────────────────────────────────────────────────── --}}
<section id="posts" style="background:var(--color-surface);padding:64px 0;">
    <div style="max-width:1280px;margin:0 auto;padding:0 32px;">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:32px;flex-wrap:wrap;gap:12px;">
            <div>
                <div style="display:flex;align-items:center;gap:8px;color:var(--color-primary-container);margin-bottom:8px;">
                    <span style="font-family:var(--font-heading);font-size:14px;">///</span>
                    <span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.08em;text-transform:uppercase;">Apex Transmission Hub</span>
                </div>
                <h2 style="font-family:var(--font-heading);font-size:clamp(24px,4vw,36px);font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0 0 8px;">Latest Gym Posts & Guides</h2>
                <p style="font-size:14px;color:var(--color-on-surface-variant);margin:0;">Published weekly by our head trainers and staff.</p>
            </div>
            <a href="{{ route('posts.index') }}" class="btn-ghost" style="padding:8px 16px;font-size:12px;">
                View All Posts & Guides <span class="material-symbols-outlined" style="font-size:16px;">arrow_forward</span>
            </a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;">
            @forelse($posts as $post)
            <article style="display:flex;flex-direction:column;background:var(--color-surface-low);border-radius:12px;overflow:hidden;transition:box-shadow .2s,background .2s;" onmouseover="this.style.boxShadow='0 0 24px rgba(57,255,20,.15)';this.style.background='var(--color-surface-container)'" onmouseout="this.style.boxShadow='none';this.style.background='var(--color-surface-low)'">
                <div style="height:180px;background:var(--color-surface-container);display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;">
                    @if($post->image_url)
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover;transition:transform .3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    @else
                        <span class="material-symbols-outlined" style="font-size:60px;color:var(--color-surface-highest);">article</span>
                    @endif
                    <span style="position:absolute;top:12px;left:12px;" class="badge-green">{{ $post->category->name }}</span>
                </div>
                <div style="padding:20px;display:flex;flex-direction:column;flex:1;">
                    <div style="display:flex;align-items:center;gap:8px;color:var(--color-outline);font-size:11px;margin-bottom:10px;">
                        <span class="material-symbols-outlined" style="font-size:14px;">calendar_today</span>
                        {{ $post->published_at?->format('d M Y') }}
                        <span>·</span>
                        <span>{{ $post->read_minutes ?? 5 }} min read</span>
                    </div>
                    <h3 style="font-family:var(--font-heading);font-size:16px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);margin:0 0 10px;line-height:1.3;">
                        <a href="{{ route('posts.show', $post->slug) }}" style="color:inherit;text-decoration:none;" onmouseover="this.style.color='var(--color-primary-container)'" onmouseout="this.style.color='inherit'">{{ $post->title }}</a>
                    </h3>
                    <p style="font-size:13px;color:var(--color-on-surface-variant);flex:1;margin:0 0 16px;">{{ Str::limit($post->excerpt, 100) }}</p>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding-top:12px;border-top:1px solid var(--color-surface-highest);">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:28px;height:28px;border-radius:50%;background:rgba(57,255,20,.2);color:var(--color-primary-container);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:11px;font-weight:700;">{{ $post->author->initials() }}</div>
                            <span style="font-size:12px;color:var(--color-on-surface-variant);">{{ $post->author->name }}</span>
                        </div>
                        <a href="{{ route('posts.show', $post->slug) }}" style="font-family:var(--font-heading);font-size:11px;letter-spacing:.06em;color:var(--color-primary-container);text-decoration:none;text-transform:uppercase;font-weight:600;">Read Guide →</a>
                    </div>
                </div>
            </article>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--color-outline);">No posts published yet.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- ── DIET PLANS PREVIEW ──────────────────────────────────────── --}}
<section id="diet-plans" style="background:var(--color-surface-lowest);padding:64px 0;">
    <div style="max-width:1280px;margin:0 auto;padding:0 32px;">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:36px;flex-wrap:wrap;gap:16px;">
            <div>
                <div style="display:inline-flex;align-items:center;gap:8px;color:var(--color-primary-container);margin-bottom:8px;">
                    <span style="font-family:var(--font-heading);font-size:14px;">///</span>
                    <span style="font-family:var(--font-heading);font-size:11px;letter-spacing:.08em;text-transform:uppercase;">Nutrition Directives</span>
                </div>
                <h2 style="font-family:var(--font-heading);font-size:clamp(24px,4vw,36px);font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0 0 8px;">
                    Diet Plans & Protocols
                </h2>
                <p style="font-size:14px;color:var(--color-on-surface-variant);margin:0;">
                    Tailored nutritional regimens engineered to optimize muscle hypertrophy, fat loss, and athletic performance.
                </p>
            </div>
            <a href="{{ route('diet-plans.index') }}" class="btn-ghost" style="padding:10px 20px;font-size:12px;">
                View All Diet Plans <span class="material-symbols-outlined" style="font-size:16px;">arrow_forward</span>
            </a>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:24px;">
            @forelse($diets as $diet)
                <article style="display:flex;flex-direction:column;background:var(--color-surface-low);border-radius:14px;overflow:hidden;border:1px solid var(--color-surface-highest);transition:transform .2s,box-shadow .2s,border-color .2s;"
                         onmouseover="this.style.borderColor='var(--color-primary-container)';this.style.boxShadow='var(--glow-green)';this.style.transform='translateY(-3px)'"
                         onmouseout="this.style.borderColor='var(--color-surface-highest)';this.style.boxShadow='none';this.style.transform='none'">
                    
                    {{-- Card Cover Image --}}
                    <div style="height:190px;background:var(--color-surface-container);position:relative;overflow:hidden;">
                        @if($diet->cover_image_url)
                            <img src="{{ $diet->cover_image_url }}" alt="{{ $diet->title }}"
                                 style="width:100%;height:100%;object-fit:cover;transition:transform .4s ease;"
                                 onmouseover="this.style.transform='scale(1.05)'"
                                 onmouseout="this.style.transform='scale(1)'">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:radial-gradient(circle at center, var(--color-surface-high) 0%, var(--color-surface-lowest) 100%);">
                                <span class="material-symbols-outlined" style="font-size:56px;color:var(--color-surface-highest);">restaurant</span>
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

                    {{-- Card Content --}}
                    <div style="padding:22px;display:flex;flex-direction:column;flex:1;">
                        <h3 style="font-family:var(--font-heading);font-size:18px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);margin:0 0 10px;line-height:1.25;">
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

                        <p style="font-size:13px;color:var(--color-on-surface-variant);line-height:1.5;margin:0 0 18px;flex:1;">
                            {{ Str::limit($diet->description ?? 'Targeted high-compliance daily meal blueprint curated by athletic trainers.', 110) }}
                        </p>

                        {{-- Card Footer --}}
                        <div style="display:flex;align-items:center;justify-content:space-between;padding-top:14px;border-top:1px solid var(--color-surface-highest);">
                            <span style="font-size:11px;color:var(--color-outline);text-transform:uppercase;letter-spacing:.06em;">Verified Plan</span>
                            <a href="{{ route('diet-plans.show', $diet) }}" class="btn-primary" style="padding:8px 16px;font-size:11px;">
                                View Plan <span class="material-symbols-outlined" style="font-size:14px;">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:48px 20px;color:var(--color-outline);background:var(--color-surface-low);border-radius:12px;border:1px dashed var(--color-surface-highest);">
                    No diet plans uploaded yet.
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ── MEMBERSHIP PLANS ──────────────────────────────────────── --}}
<section id="plans" style="background:var(--color-surface-lowest);padding:64px 0;">
    <div style="max-width:1280px;margin:0 auto;padding:0 32px;">
        <div style="text-align:center;margin-bottom:40px;">
            <div style="font-family:var(--font-heading);font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:var(--color-primary-container);margin-bottom:10px;">/// Choose Your Protocol</div>
            <h2 style="font-family:var(--font-heading);font-size:clamp(24px,4vw,36px);font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0 0 12px;">Membership Plans</h2>
            <p style="font-size:14px;color:var(--color-on-surface-variant);">Select the commitment level that matches your training goals.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;">
            @forelse($plans as $i => $plan)
            <div style="background:var(--color-surface-low);border-radius:12px;padding:24px;border:1px solid {{ $i===1 ? 'var(--color-primary-container)' : 'var(--color-surface-highest)' }};{{ $i===1 ? 'box-shadow:var(--glow-green);' : '' }}position:relative;display:flex;flex-direction:column;">
                @if($i===1)<div style="position:absolute;top:-10px;left:50%;transform:translateX(-50%);background:var(--color-primary-container);color:var(--color-on-primary);font-family:var(--font-heading);font-size:10px;letter-spacing:.06em;padding:2px 12px;border-radius:999px;text-transform:uppercase;white-space:nowrap;">Most Popular</div>@endif
                <div style="font-family:var(--font-heading);font-size:18px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);margin-bottom:8px;">{{ $plan->name }}</div>
                <div style="font-family:var(--font-heading);font-size:40px;font-weight:700;color:var(--color-primary-container);margin-bottom:4px;">PKR {{ number_format($plan->price) }}</div>
                <div style="font-size:12px;color:var(--color-outline);margin-bottom:20px;">{{ $plan->duration_days }} days access</div>
                <ul style="list-style:none;padding:0;margin:0 0 24px;flex:1;display:flex;flex-direction:column;gap:8px;">
                    @foreach(($plan->features ?? []) as $feat)
                    <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--color-on-surface-variant);">
                        <span class="material-symbols-outlined" style="font-size:16px;color:var(--color-primary-container);">check_circle</span>
                        {{ $feat }}
                    </li>
                    @endforeach
                </ul>
                @auth
                <a href="{{ auth()->user()->dashboardRoute() }}" class="{{ $i===1 ? 'btn-primary' : 'btn-ghost' }}" style="text-align:center;justify-content:center;">Go to Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="{{ $i===1 ? 'btn-primary' : 'btn-ghost' }}" style="text-align:center;justify-content:center;">Join Now</a>
                @endauth
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;color:var(--color-outline);">No plans available.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- ── TRAINERS ──────────────────────────────────────────────── --}}
<section id="trainers" style="background:var(--color-surface);padding:64px 0;">
    <div style="max-width:1280px;margin:0 auto;padding:0 32px;">
        <div style="text-align:center;margin-bottom:40px;">
            <div style="font-family:var(--font-heading);font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:var(--color-primary-container);margin-bottom:10px;">/// Expert Coaching Staff</div>
            <h2 style="font-family:var(--font-heading);font-size:clamp(24px,4vw,36px);font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0 0 12px;">Our Trainers</h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;">
            @forelse($trainers as $trainer)
            <div style="background:var(--color-surface-low);border-radius:12px;padding:24px;text-align:center;border:1px solid var(--color-surface-highest);transition:border-color .2s,box-shadow .2s;" onmouseover="this.style.borderColor='var(--color-primary-container)';this.style.boxShadow='var(--glow-green)'" onmouseout="this.style.borderColor='var(--color-surface-highest)';this.style.boxShadow='none'">
                <div style="width:72px;height:72px;border-radius:50%;background:var(--color-surface-container);border:2px solid var(--color-outline-variant);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-size:24px;font-weight:700;color:var(--color-primary-container);margin:0 auto 14px;">{{ $trainer->initials() }}</div>
                <div style="font-family:var(--font-heading);font-size:16px;font-weight:600;text-transform:uppercase;color:var(--color-on-surface);margin-bottom:6px;">{{ $trainer->user->name }}</div>
                <div style="font-size:12px;color:var(--color-primary-container);margin-bottom:8px;">{{ $trainer->specialization }}</div>
                @if($trainer->bio)<p style="font-size:11px;color:var(--color-outline);margin:0;">{{ Str::limit($trainer->bio, 80) }}</p>@endif
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;color:var(--color-outline);">No trainers found.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- ── CTA ───────────────────────────────────────────────────── --}}
<section style="background:var(--color-surface-lowest);padding:80px 0;text-align:center;">
    <div style="max-width:600px;margin:0 auto;padding:0 32px;">
        <div style="width:60px;height:60px;border-radius:12px;background:var(--color-primary-container);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
            <span class="material-symbols-outlined" style="font-size:32px;color:var(--color-on-primary);">bolt</span>
        </div>
        <h2 style="font-family:var(--font-heading);font-size:clamp(28px,4vw,44px);font-weight:700;text-transform:uppercase;color:var(--color-on-surface);margin:0 0 16px;">Ready to Start?</h2>
        <p style="font-size:15px;color:var(--color-on-surface-variant);margin-bottom:32px;">Join Kinetic Apex today and transform your performance with elite coaching, structured nutrition, and a community built for results.</p>
        @auth
        <a href="{{ auth()->user()->dashboardRoute() }}" class="btn-primary" style="padding:16px 40px;font-size:15px;">
            <span class="material-symbols-outlined" style="font-size:20px;">dashboard</span>
            Go to My Dashboard
        </a>
        @else
        <a href="{{ route('login') }}" class="btn-primary" style="padding:16px 40px;font-size:15px;">
            <span class="material-symbols-outlined" style="font-size:20px;">login</span>
            Member Login
        </a>
        @endauth
    </div>
</section>

@push('scripts')
<style>
@media(max-width:768px){
    div[style*="grid-template-columns:7fr 5fr"]{grid-template-columns:1fr!important;}
    div[style*="grid-template-columns:7fr 5fr"] > div:last-child{display:none;}
}
</style>
@endpush
@endsection
