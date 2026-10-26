<?php

namespace Database\Seeders;

use App\Models\PostCategory;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $trainer = User::where('role', 'trainer')->first() ?? $admin;

        $categories = [
            ['Workout Guides', 'workout-guides'],
            ['Diet Plans',     'diet-plans'],
            ['Fitness Tips',   'fitness-tips'],
            ['Announcements',  'announcements'],
            ['Muscle Gain',    'muscle-gain'],
        ];

        $categoryModels = [];
        foreach ($categories as [$name, $slug]) {
            $categoryModels[$slug] = PostCategory::firstOrCreate(
                ['slug' => $slug],
                ['name' => $name]
            );
        }

        $posts = [
            [
                'workout-guides',
                'Progressive Overload Mastery: The 5x5 Protocol Breakdown',
                'Learn how systematically micro-loading compound lifts accelerates hypertrophy while preventing CNS fatigue.',
                'posts/workout-5x5.jpg',
                $admin?->id ?? 1,
                '<h2>The Scientific Engine Behind Continuous Strength Adaptations</h2><p>Progressive overload is the fundamental mechanical stimulus that commands muscular hypertrophy and neurological strength adaptations. Without a methodical escalation of resistance, mechanical tension diminishes and plateau becomes inevitable.</p><h3>Phase 1: The 5x5 Neurological Priming</h3><p>The 5x5 protocol focuses on high mechanical load with low systemic metabolic burnout. Perform five working sets of five reps across five core compound movements: Back Squat, Conventional Deadlift, Standing Overhead Press, Barbell Bench Press, and Pendlay Rows.</p><blockquote>"If the load does not increase over measured micro-cycles, physiological adaptation ceases entirely." — Apex Strength Staff</blockquote><h3>Executing Micro-Loading</h3><ul><li><strong>Squat & Deadlift:</strong> Add 2.5 kg to 5.0 kg per weekly cycle.</li><li><strong>Upper Body Compound:</strong> Add 1.0 kg to 2.5 kg using fractional plates.</li><li><strong>Deload Timing:</strong> Program a 40% reduction in intensity every 6th week to regenerate connective tissues and restore central nervous system readiness.</li></ul><p>Always maintain strict bar trajectory and complete kinetic chain bracing before accelerating through the concentric phase.</p>',
                7
            ],
            [
                'diet-plans',
                'High Protein Cutting Diet: 2,200 Calorie Plan',
                'Structured macronutrient blueprint designed to preserve lean mass while targeting aggressive fat loss.',
                'posts/cutting-diet.jpg',
                $trainer?->id ?? 1,
                '<h2>Precision Caloric Deficit Without Sacrificing Lean Tissue</h2><p>Cutting body fat while sustaining maximum contractile tissue requires meticulous macronutrient calibration. A steep deficit without adequate amino acid replenishment triggers gluconeogenesis from skeletal muscle tissue, ruining metabolic rate.</p><h3>Macronutrient Target Ratios (2,200 kcal Protocol)</h3><ul><li><strong>Protein:</strong> 200g (800 kcal) — 36% of daily intake (1.0 - 1.2g per lb of body weight).</li><li><strong>Carbohydrates:</strong> 190g (760 kcal) — 35% of daily intake, loaded primarily around intra-workout windows.</li><li><strong>Fats:</strong> 71g (640 kcal) — 29% for vital endocrine hormone production and joint health.</li></ul><h3>Optimal Food Selection Matrix</h3><p>Build your base around lean poultry, wild salmon, whole cage-free eggs, jasmine rice, sweet potatoes, fibrous green cruciferous vegetables, and extra virgin olive oil. Hydrate with 4 to 5 liters of electrolyte-fortified water daily.</p>',
                5
            ],
            [
                'fitness-tips',
                'Top 7 Recovery Protocols Elite Athletes Swear By',
                'Sleep optimization, active recovery, contrast therapy, and strategic deload weeks explained.',
                'posts/recovery-protocols.jpg',
                $trainer?->id ?? 1,
                '<h2>Recovery Is Where Adaptation and Hypertrophy Actually Occur</h2><p>Training only provides the mechanical stimulus and microtrauma. It is the systemic recovery phase that restores cellular homeostasis, repairs myofibrillar tears, and super-compensates muscle glycogen.</p><h3>1. Deep Sleep Architecture</h3><p>Aim for 7.5 to 9 hours of uninterrupted sleep in a 18°C environment. Growth hormone secretion peaks during Slow-Wave Sleep (Stage 3/4 non-REM).</p><h3>2. Active Recovery & Contrast Hydrotherapy</h3><p>Contrast hydrotherapy (3 minutes hot water immersion followed by 1 minute cold immersion at 10-12°C for 4 cycles) significantly accelerates lactic acid clearance and decreases perceived DOMS.</p><h3>3. Micronutrient Replenishment</h3><p>Supplement with 400mg Magnesium Glycinate, 5,000 IU Vitamin D3, and 3g EPA/DHA Omega-3 fatty acids before bed to lower systemic inflammatory cytokines.</p>',
                6
            ],
            [
                'muscle-gain',
                'Creatine Loading Phase: Science vs Myths',
                'Evidence-based breakdown of creatine monohydrate supplementation timing, dosing and cycling.',
                'posts/creatine-guide.jpg',
                $admin?->id ?? 1,
                '<h2>Demystifying the Most Researched Ergogenic Aid in Sports History</h2><p>Creatine monohydrate is unmatched in its efficacy for phosphocreatine resynthesis during high-intensity anaerobic efforts. Yet, widespread myths regarding hydration, renal strain, and mandatory cycling persist in modern gym culture.</p><h3>To Load or Not to Load?</h3><p>The traditional 20g/day loading protocol (split into 4 doses of 5g for 5-7 days) achieves intramuscular saturation in approximately one week. Alternatively, consuming 3-5g daily will achieve identical saturation levels within 21 to 28 days with zero gastrointestinal discomfort.</p><h3>Timing and Synergy</h3><p>Post-workout ingestion accompanied by 30-50g of fast-acting carbohydrates enhances sodium-dependent creatine transporter (CreaT) uptake via insulin-mediated cellular signalling.</p>',
                4
            ],
            [
                'workout-guides',
                'Romanian Deadlift Form Guide for Maximum Hamstring Growth',
                'Step-by-step breakdown of RDL mechanics with common errors and cue corrections.',
                'posts/deadlift-guide.jpg',
                $trainer?->id ?? 1,
                '<h2>Mastering Hip Hinge Biomechanics for Posterior Chain Dominance</h2><p>The Romanian Deadlift (RDL) is the premier compound movement for loaded eccentric lengthening of the biceps femoris, semitendinosus, and gluteus maximus.</p><h3>Core Execution Cues</h3><ul><li><strong>Soft Knees:</strong> Maintain a 15-20 degree knee flexion angle throughout the eccentric excursion. The knee angle must remain constant.</li><li><strong>Push Hips to Wall:</strong> Initiate the movement by translating the pelvis posteriorly rather than bending forwards at the lumbar spine.</li><li><strong>Lat Engagement:</strong> \"Protect your armpits\" by engaging the lats, keeping the barbell dragged tightly against the thighs and shins.</li></ul><p>Reverse the motion once your hamstrings reach full active range of motion (usually mid-shin level). Do not force extra depth through lumbar flexion.</p>',
                5
            ],
        ];

        foreach ($posts as $i => [$catSlug, $title, $excerpt, $image, $authorId, $body, $readTime]) {
            $slug = Str::slug($title);
            Post::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id'     => $categoryModels[$catSlug]->id,
                    'author_id'       => $authorId,
                    'title'           => $title,
                    'excerpt'         => $excerpt,
                    'body'            => $body,
                    'featured_image'  => $image,
                    'status'          => 'published',
                    'published_at'    => now()->subDays($i * 4),
                    'read_minutes'    => $readTime,
                ]
            );
        }
    }
}
