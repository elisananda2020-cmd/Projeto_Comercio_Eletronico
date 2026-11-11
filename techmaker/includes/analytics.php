<?php

require_once __DIR__ . '/../config/analytics.php';

$measurementId = ga4_measurement_id();
$containerId = gtm_container_id();
$page = $analyticsPage ?? 'privacy';
$analyticsData = ['measurementId' => $measurementId, 'containerId' => $containerId, 'page' => $page];

if ($measurementId !== '' || $containerId !== '') {
    $catalogItem = static fn (array $row): array => [
        'item_id' => (string) $row['id'],
        'item_name' => $row['name'],
        'item_category' => $row['category'],
        'price' => (float) ($row['promotional_price'] ?? $row['price']),
        'quantity' => 1,
    ];

    if ($page === 'home') {
        $analyticsData['list'] = ['id' => 'home', 'name' => 'Mais vendidos', 'items' => array_map($catalogItem, $homeProducts ?? [])];
    } elseif ($page === 'catalog' && !$catalogError) {
        $list = $selectedCategories === []
            ? ['id' => 'catalogo', 'name' => 'Catálogo']
            : ['id' => 'categorias', 'name' => implode(' / ', $selectedCategories)];
        $analyticsData['list'] = $list + ['items' => array_map($catalogItem, $products)];
        $analyticsData['searched'] = $search !== '';
    } elseif ($page === 'product' && $product !== null) {
        $analyticsData['item'] = $catalogItem($product);
    } elseif ($page === 'checkout' && $token !== null && $error === '') {
        $analyticsData['checkout'] = $cart;
    }
}
?>
<link rel="stylesheet" href="analytics.css">
<script type="application/json" id="storeAnalyticsConfig"><?= json_encode($analyticsData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR) ?></script>
<script src="analytics.js" defer></script>
