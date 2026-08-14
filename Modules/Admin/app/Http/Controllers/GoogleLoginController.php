<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\GoogleReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Socialite;

class GoogleLoginController extends Controller
{
    public $googleReviewService;
    private const GOOGLE_REVIEW_SCOPE = 'https://www.googleapis.com/auth/business.manage';

    public function __construct(GoogleReviewService $googleReviewService)
    {
        $this->googleReviewService = $googleReviewService;
    }

    public function login()
    {
        return Socialite::driver('google')
            ->scopes([self::GOOGLE_REVIEW_SCOPE])
            ->with([
                'access_type' => 'offline',
                'prompt' => 'consent',
            ])
            ->redirect();
    }

    /**
     * Handle the Google OAuth callback
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $user = Socialite::driver('google')->user();

            $refreshToken = $user->refreshToken;
            if (! $refreshToken) {
                throw new \Exception('No refresh token obtained');
            }

            $accessToken = $this->googleReviewService->getAccessTokenFromRefreshToken($refreshToken);
            Log::info('Access token obtained', ['access_token' => $accessToken]);
            $result = $this->googleReviewService->fetchGoogleReviews($accessToken);
            Log::info('Google reviews fetched', ['count' => $result['count']]);

            return redirect()->route('google-reviews.index');
        } catch (\Exception $e) {
            Log::error('OAuth callback error', ['message' => $e->getMessage()]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
