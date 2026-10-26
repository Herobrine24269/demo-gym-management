<?php

namespace App\Policies;

use App\Models\GymSetting;
use App\Models\PaymentSubmission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentSubmissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isReceptionist()) {
            return (string) GymSetting::get('receptionist_can_view_payment_requests', '0') === '1';
        }

        return false;
    }

    public function view(User $user, PaymentSubmission $submission): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isReceptionist() && (string) GymSetting::get('receptionist_can_view_payment_requests', '0') === '1') {
            return true;
        }

        if ($user->isMember() && $user->member && $submission->member_id === $user->member->id) {
            return true;
        }

        return false;
    }

    public function approve(User $user, PaymentSubmission $submission): bool
    {
        return $user->isAdmin();
    }

    public function reject(User $user, PaymentSubmission $submission): bool
    {
        return $user->isAdmin();
    }

    public function downloadReceipt(User $user, PaymentSubmission $submission): bool
    {
        if ($submission->status !== 'approved') {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isReceptionist() && (string) GymSetting::get('receptionist_can_view_payment_requests', '0') === '1') {
            return true;
        }

        if ($user->isMember() && $user->member && $submission->member_id === $user->member->id) {
            return true;
        }

        return false;
    }
}
