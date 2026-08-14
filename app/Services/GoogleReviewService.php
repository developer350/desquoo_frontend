<?php

namespace App\Services;

use App\Models\GoogleReview;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleReviewService
{
    private const GOOGLE_MYBUSINESS_API_BASE = 'https://mybusiness.googleapis.com/v4/accounts/';

    private const GOOGLE_OAUTH_TOKEN_URL = 'https://oauth2.googleapis.com/token';

    /**
     * Exchange refresh token for access token
     *
     * @throws \Exception
     */
    public function getAccessTokenFromRefreshToken(string $refreshToken): string
    {
        $response = Http::post(self::GOOGLE_OAUTH_TOKEN_URL, [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
        ]);

        if (! $response->successful()) {
            Log::error('Token refresh failed', ['response' => $response->body()]);
            throw new \Exception('Unable to refresh token');
        }

        $tokenData = $response->json();

        return $tokenData['access_token'];
    }

    /**
     * Fetch Google reviews - initial call
     */
    public function fetchGoogleReviews(string $accessToken): array
    {
        $url = $this->buildReviewsUrl();

        return $this->processReviews($accessToken, $url);
    }

    /**
     * Process reviews and handle pagination
     */
    private function processReviews(string $accessToken, string $url, int $count = 0): array
    {
        $response = Http::withToken($accessToken)->get($url);

        if (! $response->successful()) {
            Log::error('Error fetching reviews', ['response' => $response->body()]);
            throw new \Exception('Unable to fetch reviews');
        }

        $reviewData = $response->json();
        $reviews = $reviewData['reviews'] ?? [];

        if (empty($reviews)) {
            return ['count' => $count];
        }

        $count += $this->saveReviewsInDb($reviews);

        // Handle pagination
        if (isset($reviewData['nextPageToken'])) {
            $nextUrl = $this->buildReviewsUrl($reviewData['nextPageToken']);

            return $this->processReviews($accessToken, $nextUrl, $count);
        }

        return ['count' => $count];
    }

    /**
     * Save reviews to database
     *
     * @return int Number of new reviews saved
     */
    private function saveReviewsInDb(array $reviews): int
    {
        $newReviewCount = 0;

        foreach ($reviews as $review) {
            $reviewId = $review['reviewId'] ?? null;
            if (! $reviewId || GoogleReview::where('review_id', $reviewId)->exists()) {
                continue;
            }

            $googleReview = new GoogleReview;
            $googleReview->review_id = $reviewId;
            $googleReview->name = $review['reviewer']['displayName'] ?? 'Anonymous';
            $googleReview->review = $review['comment'] ?? null;
            $googleReview->published_on = isset($review['createTime'])
                ? Carbon::parse($review['createTime'])->format('Y-m-d')
                : null;
            $googleReview->rating = $this->getStarRating($review['starRating'] ?? '');
            $googleReview->status = 0;
            $googleReview->save();

            if (isset($review['reviewer']['profilePhotoUrl'])) {
                $googleReview->addMediaFromUrl($review['reviewer']['profilePhotoUrl'])
                    ->toMediaCollection('avatar');
            }

            $newReviewCount++;
        }

        return $newReviewCount;
    }

    /**
     * Convert string star rating to integer
     */
    private function getStarRating(string $starRating): int
    {
        $ratings = [
            'ONE' => 1,
            'TWO' => 2,
            'THREE' => 3,
            'FOUR' => 4,
            'FIVE' => 5,
        ];

        return $ratings[$starRating] ?? 0;
    }

    /**
     * Build reviews API URL
     */
    private function buildReviewsUrl(?string $pageToken = null): string
    {
        $accountId = config('services.google_review.account_id');
        $locationId = config('services.google_review.location_id');

        $url = self::GOOGLE_MYBUSINESS_API_BASE."{$accountId}/locations/{$locationId}/reviews";

        if ($pageToken) {
            $url .= "?pageToken={$pageToken}";
        }

        return $url;
    }
}
