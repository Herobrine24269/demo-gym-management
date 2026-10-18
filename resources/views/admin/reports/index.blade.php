@extends('layouts.dashboard')
@section('title', 'Reports')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">ANALYTICS</span></div>
        <h1 class="page-title">Reports</h1>
        <p class="page-title-sub">Financial and operational analytics</p>
    </div>
</div>

{{-- Month selector --}}
<form method="GET" style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap;align-items:center;">
    <select name="month" class="form-control-dark form-select-dark" style="width:auto;min-width:140px;">
        @foreach(range(1,12) as $m)
        <option value="{{ $m }}" {{ $month==$m ? 'selected':'' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
        @endforeach
    </select>
    <select name="year" class="form-control-dark form-select-dark" style="width:auto;min-width:100px;">
        @foreach(range(now()->year, now()->year - 3) as $y)
        <option value="{{ $y }}" {{ $year==$y ? 'selected':'' }}>{{ $y }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-primary">View Report</button>
</form>

{{-- Key financials --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin-bottom:32px;">
    @foreach([['Revenue', 'PKR '.number_format($totalRevenue), 'trending_up', 'var(--color-primary-container)'],['Expenses', 'PKR '.number_format($totalExpenses), 'trending_down', 'var(--color-error)'],['Net Profit', 'PKR '.number_format($netProfit), $netProfit >= 0 ? 'arrow_upward' : 'arrow_downward', $netProfit >= 0 ? 'var(--color-primary-container)' : 'var(--color-error)']] as [$lbl,$val,$icon,$clr])
    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;"><div class="stat-label">{{ $lbl }}</div><span class="material-symbols-outlined" style="font-size:20px;color:{{ $clr }};">{{ $icon }}</span></div>
        <div style="font-family:var(--font-heading);font-size:28px;font-weight:700;color:{{ $clr }};margin-top:6px;">{{ $val }}</div>
        <div style="font-size:11px;color:var(--color-outline);margin-top:4px;">{{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}</div>
    </div>
    @endforeach
</div>

{{-- Charts --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
    <div class="chart-card">
        <div style="margin-bottom:16px;"><div class="section-title" style="font-size:15px;">Daily Revenue</div><span class="stat-sub">{{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}</span></div>
        <div class="chart-canvas-wrap" style="height:200px;"><canvas id="revenueChart"></canvas></div>
    </div>
    <div class="chart-card">
        <div style="margin-bottom:16px;"><div class="section-title" style="font-size:15px;">Revenue by Type</div></div>
        <div class="chart-canvas-wrap" style="height:200px;"><canvas id="typeChart"></canvas></div>
    </div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    <div class="chart-card">
        <div style="margin-bottom:16px;"><div class="section-title" style="font-size:15px;">Daily Attendance</div><span class="stat-sub">Last 30 days</span></div>
        <div class="chart-canvas-wrap" style="height:200px;"><canvas id="attendChart"></canvas></div>
    </div>
    <div class="chart-card">
        <div style="margin-bottom:16px;"><div class="section-title" style="font-size:15px;">New Members</div><span class="stat-sub">Last 6 months</span></div>
        <div class="chart-canvas-wrap" style="height:200px;"><canvas id="membersChart"></canvas></div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.color = '#85967c';
Chart.defaults.borderColor = '#353534';
const opts = { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{x:{grid:{color:'rgba(53,53,52,.5)'}},y:{grid:{color:'rgba(53,53,52,.5)'},beginAtZero:true}} };

// Daily revenue
const daysInMonth = {{ \Carbon\Carbon::create($year, $month)->daysInMonth }};
const dayLabels = Array.from({length: daysInMonth}, (_, i) => i + 1);
const revenueData = dayLabels.map(d => {{ json_encode($revenueByDay->toArray()) }}[d] || 0);
new Chart(document.getElementById('revenueChart'), { type:'bar', data:{ labels:dayLabels, datasets:[{label:'Revenue',data:revenueData,backgroundColor:'rgba(57,255,20,0.25)',borderColor:'#39ff14',borderWidth:2,borderRadius:3}]}, options:opts });

// Revenue by type
const typeData = @json($revenueByType);
const typeLabels = {membership:'Memberships',pt_session:'Personal Training',addon:'Store',renewal:'Renewals',other:'Other'};
new Chart(document.getElementById('typeChart'), { type:'doughnut', data:{ labels:Object.keys(typeData).map(k=>typeLabels[k]||k), datasets:[{data:Object.values(typeData),backgroundColor:['#39ff14','#81cfff','#2ae500','#baccb0','#353534'],borderWidth:2,borderColor:'#201f1f'}]}, options:{...opts, plugins:{legend:{position:'bottom',labels:{color:'#baccb0',padding:12}}}, scales:{}} });

// Daily attendance
const adData = @json($attendanceByDay->pluck('count'));
const adLabels = @json($attendanceByDay->pluck('label'));
new Chart(document.getElementById('attendChart'), { type:'line', data:{ labels:adLabels, datasets:[{label:'Check-ins',data:adData,borderColor:'#39ff14',backgroundColor:'rgba(57,255,20,0.1)',fill:true,tension:0.4,pointRadius:2}]}, options:opts });

// New members
const nmData = @json($newMembersByMonth->pluck('count'));
const nmLabels = @json($newMembersByMonth->pluck('label'));
new Chart(document.getElementById('membersChart'), { type:'bar', data:{ labels:nmLabels, datasets:[{data:nmData,backgroundColor:'rgba(129,207,255,0.3)',borderColor:'#81cfff',borderWidth:2,borderRadius:3}]}, options:opts });
</script>
<style>@media(max-width:900px){div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important;}}</style>
@endpush
@endsection
