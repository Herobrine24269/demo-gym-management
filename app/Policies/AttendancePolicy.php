<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->isAdmin();
    }
}
