@extends('layouts.dashboard')
@section('title', 'Expenses')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-eyebrow"><span class="eyebrow-dot"></span><span class="eyebrow-text">FINANCIALS</span></div>
        <h1 class="page-title">Expenses</h1>
    </div>
    <div style="padding:8px 14px;border-radius:6px;background:var(--color-surface-low);font-family:var(--font-heading);font-size:13px;color:var(--color-error);">
        This Month: <strong>PKR {{ number_format($totalMonth) }}</strong>
    </div>
</div>
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">
    <div style="background:var(--color-surface-low);border-radius:12px;overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="table-dark">
                <thead><tr><th>Category</th><th>Amount</th><th>Date</th><th>Recorded by</th></tr></thead>
                <tbody>
                    @forelse($expenses as $exp)
                    <tr>
                        <td><div class="td-name">{{ $exp->category }}</div>@if($exp->description)<div class="td-sub">{{ Str::limit($exp->description, 50) }}</div>@endif</td>
                        <td style="font-weight:600;color:var(--color-error);">PKR {{ number_format($exp->amount) }}</td>
                        <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $exp->incurred_at->format('d M Y') }}</td>
                        <td style="font-size:12px;color:var(--color-on-surface-variant);">{{ $exp->recorder->name ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4"><div class="empty-state"><span class="material-symbols-outlined">account_balance</span><div class="empty-state-title">No Expenses Yet</div></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())<div style="padding:16px;">{{ $expenses->links() }}</div>@endif
    </div>
    <div class="card-dark" style="align-self:start;">
        <div class="section-title" style="font-size:15px;margin-bottom:16px;">Record Expense</div>
        <form method="POST" action="{{ route('admin.expenses.store') }}">
            @csrf
            <div style="margin-bottom:12px;">
                <label class="form-label-dark">Category *</label>
                <input type="text" name="category" class="form-control-dark" value="{{ old('category') }}" list="cats" placeholder="e.g. Equipment, Utilities" required>
                <datalist id="cats">@foreach($categories as $cat)<option value="{{ $cat }}">@endforeach</datalist>
            </div>
            <div style="margin-bottom:12px;">
                <label class="form-label-dark">Amount (PKR) *</label>
                <input type="number" name="amount" class="form-control-dark" value="{{ old('amount') }}" min="1" required>
            </div>
            <div style="margin-bottom:12px;">
                <label class="form-label-dark">Date *</label>
                <input type="date" name="incurred_at" class="form-control-dark" value="{{ old('incurred_at', today()->toDateString()) }}" required>
            </div>
            <div style="margin-bottom:16px;">
                <label class="form-label-dark">Description</label>
                <textarea name="description" class="form-control-dark form-textarea-dark" placeholder="Details...">{{ old('description') }}</textarea>
            </div>
            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
                <span class="material-symbols-outlined" style="font-size:18px;">add</span> Add Expense
            </button>
        </form>
    </div>
</div>
@push('scripts')
<style>@media(max-width:900px){div[style*="grid-template-columns:2fr 1fr"]{grid-template-columns:1fr!important;}}</style>
@endpush
@endsection
