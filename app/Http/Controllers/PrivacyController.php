<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PrivacyController extends Controller
{
    /**
     * Show privacy policy
     */
    public function policy()
    {
        return view('privacy.policy');
    }

    /**
     * Show terms of service
     */
    public function terms()
    {
        return view('privacy.terms');
    }

    /**
     * Show data processing information
     */
    public function dataProcessing()
    {
        return view('privacy.data-processing');
    }

    /**
     * Export user data (LGPD/GDPR compliance)
     */
    public function exportData(Request $request)
    {
        $user = Auth::user();
        
        $data = [
            'user_info' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'phone' => $user->phone,
                'created_at' => $user->created_at->toISOString(),
                'updated_at' => $user->updated_at->toISOString(),
            ],
            'financial_data' => [
                'revenues' => $user->revenues()->get()->toArray(),
                'expenses' => $user->expenses()->get()->toArray(),
                'goals' => $user->goals()->get()->toArray(),
                'accounts' => $user->accounts()->get()->toArray(),
            ],
            'export_info' => [
                'exported_at' => now()->toISOString(),
                'format' => 'JSON',
                'rights_exercised' => 'Right to data portability (LGPD Art. 18, GDPR Art. 20)'
            ]
        ];

        $filename = 'user_data_' . $user->id . '_' . now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }

    /**
     * Request account deletion
     */
    public function requestDeletion(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has active subscription
        if ($user->tenant && $user->tenant->subscribed()) {
            return back()->with('error', 'Você deve cancelar sua assinatura antes de excluir sua conta.');
        }

        $request->validate([
            'password' => 'required|string',
            'confirmation' => 'required|in:DELETE',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Senha incorreta.']);
        }

        // Schedule account deletion (30 days grace period)
        $user->update([
            'deletion_requested_at' => now(),
            'deletion_scheduled_at' => now()->addDays(30),
        ]);

        // Log the deletion request
        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties(['scheduled_for' => $user->deletion_scheduled_at])
            ->log('Account deletion requested');

        Auth::logout();

        return redirect()->route('login')->with('message', 
            'Sua conta foi agendada para exclusão em 30 dias. Você pode cancelar fazendo login novamente.');
    }

    /**
     * Cancel deletion request
     */
    public function cancelDeletion(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->deletion_requested_at) {
            return back()->with('error', 'Não há solicitação de exclusão pendente.');
        }

        $user->update([
            'deletion_requested_at' => null,
            'deletion_scheduled_at' => null,
        ]);

        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->log('Account deletion cancelled');

        return back()->with('success', 'Solicitação de exclusão cancelada com sucesso.');
    }

    /**
     * Submit data correction request
     */
    public function submitCorrection(Request $request)
    {
        $request->validate([
            'field' => 'required|string',
            'current_value' => 'required|string',
            'requested_value' => 'required|string',
            'reason' => 'required|string|max:500',
        ]);

        $user = Auth::user();

        // Store correction request
        $correctionRequest = [
            'user_id' => $user->id,
            'field' => $request->field,
            'current_value' => $request->current_value,
            'requested_value' => $request->requested_value,
            'reason' => $request->reason,
            'status' => 'pending',
            'submitted_at' => now()->toISOString(),
        ];

        // Save to file (in production, use database)
        $filename = 'correction_requests.json';
        $existing = Storage::disk('local')->exists($filename) 
            ? json_decode(Storage::disk('local')->get($filename), true) 
            : [];
        
        $existing[] = $correctionRequest;
        Storage::disk('local')->put($filename, json_encode($existing, JSON_PRETTY_PRINT));

        // Log the request
        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($correctionRequest)
            ->log('Data correction requested');

        return back()->with('success', 'Solicitação de correção enviada. Responderemos em até 72 horas.');
    }

    /**
     * Object to data processing
     */
    public function objectToProcessing(Request $request)
    {
        $request->validate([
            'processing_type' => 'required|string',
            'reason' => 'required|string|max:500',
        ]);

        $user = Auth::user();

        $objection = [
            'user_id' => $user->id,
            'processing_type' => $request->processing_type,
            'reason' => $request->reason,
            'status' => 'pending',
            'submitted_at' => now()->toISOString(),
        ];

        // Save objection request
        $filename = 'processing_objections.json';
        $existing = Storage::disk('local')->exists($filename) 
            ? json_decode(Storage::disk('local')->get($filename), true) 
            : [];
        
        $existing[] = $objection;
        Storage::disk('local')->put($filename, json_encode($existing, JSON_PRETTY_PRINT));

        // Log the objection
        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($objection)
            ->log('Processing objection submitted');

        return back()->with('success', 'Objeção registrada. Analisaremos sua solicitação.');
    }

    /**
     * Show consent management
     */
    public function consentManagement()
    {
        $user = Auth::user();
        
        return view('privacy.consent', compact('user'));
    }

    /**
     * Update consent preferences
     */
    public function updateConsent(Request $request)
    {
        $user = Auth::user();
        
        $user->update([
            'marketing_consent' => $request->has('marketing_consent'),
            'analytics_consent' => $request->has('analytics_consent'),
            'third_party_consent' => $request->has('third_party_consent'),
            'consent_updated_at' => now(),
        ]);

        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->only(['marketing_consent', 'analytics_consent', 'third_party_consent']))
            ->log('Consent preferences updated');

        return back()->with('success', 'Preferências de consentimento atualizadas.');
    }
}
