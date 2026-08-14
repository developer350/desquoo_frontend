<?php

namespace App\Helpers;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Str;
use App\Models\BannerAndMetaTag;

class FrontendHelpers
{
    /**
     * Retrieves the value of a language-specific field from an object.
     *
     * @param string $language The language code ('ar' for Arabic).
     * @param object $object The object containing the fields.
     * @param string $field_name The base name of the field.
     * @return string The language-specific value if available; otherwise, an empty string.
     * @author Sooryajith
     */
    public static function getWord($language, $objectOrArray, $fieldName)
    {
        if (is_array($objectOrArray)) {
            return $language === 'ar'
                ? ($objectOrArray["{$fieldName}_ar"] ?? '')
                : ($objectOrArray[$fieldName] ?? '');
        }

        if (is_object($objectOrArray)) {
            return $objectOrArray->{$language === 'ar' ? "{$fieldName}_ar" : $fieldName} ?? '';
        }

        return ''; // Fallback if the input is neither an object nor an array
    }

    /**
     * Retrieve meta tags and page banner details for the specified page based on the current locale.
     * Falls back to default values (config('app.name')) if not found.
     * Only fetches banner details if 'has_banner' is true in the BannerAndMetaTag model.
     *
     * @param string $page The page for which meta information and banner details are fetched.
     * @return array An array containing meta tags, page title, and banner image.
     * @author Sooryajith
     */
    public static function getPageDetails($page)
    {
        $defaultTitle = config('app.name');

        // Single query to fetch page details
        $pageDetails = BannerAndMetaTag::where('page', $page)->first();

        // Get meta tags
        $metaTags = self::getMetaTags($pageDetails, $defaultTitle);

        // Build banner array if it exists
        $banner = [];
        if ($pageDetails?->has_banner) {
            $banner = [
                'title' => $pageDetails->banner_title ?? $defaultTitle,
                'description' => $pageDetails->banner_description ?? '',
                'image' => $pageDetails->banner_value,
                'mobile_image' => $pageDetails->bannerMobileValue,
                'alt' => $pageDetails->bannerAltTextValue,
            ];
        }

        return array_merge($metaTags, $banner);
    }

    /**
     * Retrieve the meta information for a given page.
     *
     * @param BannerAndMetaTag|null $pageDetails The page details model instance.
     * @param string $default Default value to fall back to if meta tag is not found.
     * @return array An array of meta tags.
     */
    private static function getMetaTags($pageDetails, $default)
    {
        if (!$pageDetails) {
            return [
                'metaTitle' => $default,
                'metaKeywords' => $default,
                'metaDescription' => $default,
                'otherMetaTags' => null,
            ];
        }

        return [
            'metaTitle' => $pageDetails->meta_title ?? $default,
            'metaKeywords' => $pageDetails->meta_keywords ?? $default,
            'metaDescription' => $pageDetails->meta_description ?? $default,
            'otherMetaTags' => $pageDetails->other_meta_tags ?? null,
        ];
    }

    /**
     * Get the banner image URL for a specific page.
     *
     * @param string $page The page identifier.
     * @return string|null The image URL for the page banner, or null if not found.
     */
    public static function getPageBannerImage($page)
    {
        return BannerAndMetaTag::where('page', $page)->value('banner_value');
    }

    /**
     * Get MIME type based on file extension.
     *
     * @param string $filePath
     * @return string
     * @author Sooryajith
     */
    public static function getMimeType($filePath)
    {
        if (!is_string($filePath) || empty($filePath) || !file_exists($filePath)) {
            return 'application/octet-stream'; // Default for invalid or non-existent file paths
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        switch ($extension) {
            case 'pdf':
                return 'application/pdf';
            case 'doc':
                return 'application/msword';
            case 'docx':
                return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
            case 'xls':
                return 'application/vnd.ms-excel';
            case 'xlsx':
                return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            case 'ppt':
                return 'application/vnd.ms-powerpoint';
            case 'pptx':
                return 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
            case 'jpg':
            case 'jpeg':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            case 'gif':
                return 'image/gif';
            case 'txt':
                return 'text/plain';
            case 'csv':
                return 'text/csv';
            case 'zip':
                return 'application/zip';
            case 'rar':
                return 'application/x-rar-compressed';
            case '7z':
                return 'application/x-7z-compressed';
            case 'mp3':
                return 'audio/mpeg';
            case 'wav':
                return 'audio/wav';
            case 'mp4':
                return 'video/mp4';
            case 'mov':
                return 'video/quicktime';
            default:
                return 'application/octet-stream'; // Default MIME type if extension is unknown
        }
    }

    /**
     * Purifies and limits the length of HTML content.
     *
     * @param string $language The language code for which to retrieve the content.
     * @param string $object The object or procedure name to fetch the content from.
     * @param string $field_name The field name within the object to retrieve the content.
     * @param int $limit The maximum number of characters to limit the content to. Defaults to 100.
     * @return string The purified and limited content.
     * @author Sooryajith
     */
    public static function purifyAndLimitInt($language, $object, $field_name, $limit = 100)
    {
        $html = self::getWord($language, $object, $field_name);
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        $limitedDescription = Str::limit(strip_tags($html), $limit);

        return $purifier->purify($limitedDescription);
    }

    public static function purifyAndLimit($text, $limit = 300)
    {
        // Clean the text
        $cleaned = strip_tags($text);
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        $cleaned = trim($cleaned);

        // Truncate without breaking words
        if (strlen($cleaned) > $limit) {
            $truncated = substr($cleaned, 0, $limit);
            $lastSpace = strrpos($truncated, ' ');
            if ($lastSpace !== false) {
                $truncated = substr($truncated, 0, $lastSpace);
            }
        } else {
            $truncated = $cleaned;
        }

        return [
            'full' => $cleaned,
            'truncated' => $truncated,
            'remaining' => substr($cleaned, strlen($truncated)),
            'has_more' => strlen($cleaned) > strlen($truncated)
        ];
    }

    /**
     * Converts a string containing a number with commas to a float.
     *
     * @param string $value The string value containing the number, potentially with commas.
     * @return float The number converted to a float, with commas removed.
     */
    public static function toFloat($value)
    {
        return (float) str_replace(',', '', $value);
    }

    /**
     * Check if a URL is internal (same domain)
     */
    public static function isInternal($url)
    {
        // Handle relative URLs (they're always internal)
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            return true;
        }

        // Get the current domain
        $currentDomain = parse_url(config('app.url'), PHP_URL_HOST);

        // Get the URL domain
        $urlDomain = parse_url($url, PHP_URL_HOST);

        if (!$currentDomain || !$urlDomain) {
            return false;
        }

        // Normalize domains by removing www prefix
        $currentDomain = self::normalizeDomain($currentDomain);
        $urlDomain = self::normalizeDomain($urlDomain);

        return $currentDomain === $urlDomain;
    }

    private static function normalizeDomain($domain)
    {
        $domain = strtolower(trim($domain));

        // Remove www prefix if present
        if (str_starts_with($domain, 'www.')) {
            $domain = substr($domain, 4);
        }

        return $domain;
    }

    /**
     * Get target attribute based on URL type
     */
    public static function getTargetAttribute($url)
    {
        return self::isInternal($url) ? '' : ' target="_blank"';
    }

    /**
     * Get complete link attributes for external links
     */
    public static function getLinkAttributes($url)
    {
        if (self::isInternal($url)) {
            return '';
        }

        return ' target="_blank" rel="noopener noreferrer"';
    }
}
