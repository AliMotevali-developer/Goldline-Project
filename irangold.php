<?php


$cache_file = 'gold_cache.json';
$cache_time = 300; 


if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_time) {
    $data = json_decode(file_get_contents($cache_file), true);
    if (isset($data['price']) && $data['price'] > 0) {
        echo $data['price'];
        exit;
    }
}

// api به  nobitex
$api_url = "https://api.nobitex.ir/market/stats?srcCurrency=cgg&dstCurrency=irt";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$real_price_per_mg = null;

if ($http_code == 200 && $response) {
    $result = json_decode($response, true);
    
    if (isset($result['stats']['cgg-irt']['latest'])) {
        $price_per_gram = intval($result['stats']['cgg-irt']['latest']);
        
        $real_price_per_mg = round($price_per_gram / 1000);
    }
}

if ($real_price_per_mg !== null && $real_price_per_mg > 0) {
    file_put_contents($cache_file, json_encode(['price' => $real_price_per_mg]));
    echo $real_price_per_mg;
} else {
    if (file_exists($cache_file)) {
        $data = json_decode(file_get_contents($cache_file), true);
        echo isset($data['price']) ? $data['price'] : "19000"; 
    } else {
        echo "19000"; 
    }
}
?>