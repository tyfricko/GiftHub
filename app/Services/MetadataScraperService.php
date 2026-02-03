<?php

namespace App\Services;

class MetadataScraperService
{
    /**
     * Scrape metadata from a given URL.
     *
     * @param string \$url
     */
    public function scrape(string $url): array
    {
        $result = [
            'title' => null,
            'description' => null,
            'image_url' => null,
        ];

        // Fetch the HTML content
        $context = stream_context_create([
            'http' => [
                'timeout' => 5,
                'user_agent' => 'Mozilla/5.0 (compatible; MetadataScraper/1.0)',
            ],
        ]);
        $html = @file_get_contents($url, false, $context);

        if (! $html) {
            return $result;
        }

        // Detect and convert encoding to UTF-8 if necessary
        $detectedEncoding = mb_detect_encoding($html, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
        if ($detectedEncoding && $detectedEncoding !== 'UTF-8') {
            $html = mb_convert_encoding($html, 'UTF-8', $detectedEncoding);
        }

        // Suppress warnings for malformed HTML
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();

        // Load HTML with explicit UTF-8 encoding
        if (! $doc->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) {
            return $result;
        }
        libxml_clear_errors();

        $xpath = new \DOMXPath($doc);

        // Try Open Graph tags first
        $ogTitle = $xpath->query('//meta[@property="og:title"]')->item(0);
        $ogDescription = $xpath->query('//meta[@property="og:description"]')->item(0);
        $ogImage = $xpath->query('//meta[@property="og:image"]')->item(0);

        if ($ogTitle && $ogTitle->hasAttribute('content')) {
            $result['title'] = $ogTitle->getAttribute('content');
        }
        if ($ogDescription && $ogDescription->hasAttribute('content')) {
            $result['description'] = $ogDescription->getAttribute('content');
        }
        if ($ogImage && $ogImage->hasAttribute('content')) {
            $result['image_url'] = $ogImage->getAttribute('content');
        }

        // Fallbacks if OG tags are missing
        if (empty($result['title'])) {
            $titleTag = $doc->getElementsByTagName('title')->item(0);
            if ($titleTag) {
                $result['title'] = $titleTag->textContent;
            }
        }
        if (empty($result['description'])) {
            $metaDesc = $xpath->query('//meta[@name="description"]')->item(0);
            if ($metaDesc && $metaDesc->hasAttribute('content')) {
                $result['description'] = $metaDesc->getAttribute('content');
            }
        }

        return $result;
    }

    /**
     * Extract price and currency from JSON-LD.
     */
    private function extractJsonLd(\DOMXPath $xpath): ?array
    {
        $scriptNodes = $xpath->query('//script[@type="application/ld+json"]');
        foreach ($scriptNodes as $script) {
            $json = json_decode($script->textContent, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                continue;
            }

            // Handle single JSON-LD object or an array of objects
            $jsonLdItems = is_array($json) && isset($json[0]) ? $json : [$json];

            foreach ($jsonLdItems as $item) {
                if (isset($item['@type']) && ($item['@type'] === 'Product' || $item['@type'] === 'Offer')) {
                    $offer = $item['offers'] ?? $item; // Product might have offers, Offer is direct
                    if (isset($offer['price']) && isset($offer['priceCurrency'])) {
                        return [
                            'price' => (float) $offer['price'],
                            'currency' => $offer['priceCurrency'],
                        ];
                    }
                }
            }
        }

        return null;
    }

    /**
     * Extract price and currency from Open Graph tags.
     */
    private function extractOpenGraphPrice(\DOMXPath $xpath): ?array
    {
        $price = $xpath->query('//meta[@property="og:price:amount"]')->item(0);
        $currency = $xpath->query('//meta[@property="og:price:currency"]')->item(0);

        if ($price && $price->hasAttribute('content') && $currency && $currency->hasAttribute('content')) {
            return [
                'price' => (float) $price->getAttribute('content'),
                'currency' => $currency->getAttribute('content'),
            ];
        }

        return null;
    }

    /**
     * Extract price and currency from Schema.org Microdata.
     */
    private function extractMicrodataPrice(\DOMXPath $xpath): ?array
    {
        $price = $xpath->query('//*[@itemprop="price"]')->item(0);
        $currency = $xpath->query('//*[@itemprop="priceCurrency"]')->item(0);

        if ($price && $price->hasAttribute('content') && $currency && $currency->hasAttribute('content')) {
            return [
                'price' => (float) $price->getAttribute('content'),
                'currency' => $currency->getAttribute('content'),
            ];
        } elseif ($price && $price->textContent && $currency && $currency->textContent) {
            return [
                'price' => (float) $price->textContent,
                'currency' => $currency->textContent,
            ];
        }

        return null;
    }

    /**
     * Extract price using common XPath patterns as a final fallback.
     */
    private function extractXPathPrice(\DOMXPath $xpath): ?array
    {
        $pricePatterns = [
            '//span[contains(@class, "price")]',
            '//div[contains(@class, "price")]',
            '//b[contains(@class, "price")]',
            '//strong[contains(@class, "price")]',
            '//meta[@itemprop="price"]', // Sometimes price is in meta without currency
        ];

        foreach ($pricePatterns as $pattern) {
            $node = $xpath->query($pattern)->item(0);
            if ($node && $node->textContent) {
                // Attempt to clean and convert to float
                $priceText = trim($node->textContent);
                $price = filter_var($priceText, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                if (is_numeric($price)) {
                    // Simple currency detection (can be improved)
                    $currency = null;
                    if (preg_match('/(\$|€|£|¥|USD|EUR|GBP|JPY)/i', $priceText, $matches)) {
                        $currency = strtoupper($matches[1]);
                    }

                    return [
                        'price' => (float) $price,
                        'currency' => $currency,
                    ];
                }
            }
        }

        return null;
    }
}
