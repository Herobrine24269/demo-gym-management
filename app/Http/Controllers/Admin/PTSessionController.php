<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PersonalTrainingSession;
use App\Models\Member;
use App\Models\Trainer;
use Illuminate\Http\Request;

class PTSessionController extends Controller
{
    public function index()
    {
        $sessions = PersonalTrainingSession::with('member.user', 'trainer.user')
            ->latest('scheduled_at')
            ->paginate(25);
        return view('admin.pt-sessions.index', compact('sessions'));
    }

    public function create()
    {
        $members  = Member::with('user')->get();
        $trainers = Trainer::with('user')->where('is_active', true)->get();
        return view('admin.pt-sessions.create', compact('members', 'trainers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id'        => 'required|exists:members,id',
            'trainer_id'       => 'required|exists:trainers,id',
            'scheduled_at'     => 'required|date',
            'duration_minutes' => 'nullable|integer|min:30|max:180',
            'amount'           => 'nullable|numeric|min:0',
            'notes'            => 'nullable|string',
        ]);
        $data['status'] = 'scheduled';
        PersonalTrainingSession::create($data);
        return redirect()->route('admin.pt-sessions.index')->with('success', 'PT session booked.');
    }

    public function show(PersonalTrainingSession $ptSession)
    {
        return redirect()->route('admin.pt-sessions.index');
    }

    public function edit(PersonalTrainingSession $ptSession)
    {
        $session = $ptSession;
        return view('admin.pt-sessions.edit', compact('session'));
    }

    public function update(Request $request, PersonalTrainingSession $ptSession)
    {
        $data = $request->validate([
            'scheduled_at'     => 'required|date',
            'status'           => 'required|in:scheduled,completed,cancelled,no_show',
            'duration_minutes' => 'nullable|integer|min:30',
            'amount'           => 'nullable|numeric|min:0',
            'notes'            => 'nullable|string',
        ]);
        $ptSession->update($data);
        return redirect()->route('admin.pt-sessions.index')->with('success', 'PT session updated.');
    }

    public function destroy(PersonalTrainingSession $ptSession)
    {
        $ptSession->delete();
        return redirect()->route('admin.pt-sessions.index')->with('success', 'PT session deleted.');
    }
}
