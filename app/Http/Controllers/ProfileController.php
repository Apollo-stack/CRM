<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Estatísticas do Usuário
        $totalSales = \App\Models\Lead::where('user_id', $user->id)
            ->where('status', \App\LeadStatus::WON)
            ->sum('value');

        $activeLeads = \App\Models\Lead::where('user_id', $user->id)
            ->whereIn('status', [\App\LeadStatus::NEW, \App\LeadStatus::NEGOTIATION])
            ->count();

        $totalLeads = \App\Models\Lead::where('user_id', $user->id)->count();
        $wonLeads = \App\Models\Lead::where('user_id', $user->id)->where('status', \App\LeadStatus::WON)->count();
        
        $conversionRate = $totalLeads > 0 ? ($wonLeads / $totalLeads) * 100 : 0;

        return view('profile.edit', [
            'user' => $user,
            'stats' => [
                'total_sales' => $totalSales,
                'active_leads' => $activeLeads,
                'conversion_rate' => $conversionRate,
            ]
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
