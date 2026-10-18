<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    public function index()
    {
        $member     = auth()->user()->member;
        $attendance = $member->attendance()->orderByDesc('date')->paginate(30);
        return view('member.attendance', compact('member', 'attendance'));
    }
}
