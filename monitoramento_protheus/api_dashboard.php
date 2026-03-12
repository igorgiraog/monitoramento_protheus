<?php
header('Content-Type: application/json; charset=utf-8');

$configFile      = 'config_services.json';
$services_data   = [];
$servers_summary = [];

if (!file_exists($configFile)) {
    echo json_encode(['servers' => [], 'services' => []]);
    exit;
}
$services_to_monitor = json_decode(file_get_contents($configFile), true);
if (empty($services_to_monitor)) {
    echo json_encode(['servers' => [], 'services' => []]);
    exit;
}

function extract_metrics($metrics_array) {
    $extracted = [
        'memoria_residente' => 0,
        'memoria_total_kb'  => 0,
        'cpu_total'         => 0,
        'cpu_servico'       => 0,
        'usuarios_all'      => 0,
        'usuarios_unique'   => 0,
        'latencia'          => 0
    ];
    if (!is_array($metrics_array)) return $extracted;
    foreach ($metrics_array as $metric) {
        if (!isset($metric['name'])) continue;
        switch ($metric['name']) {
            case 'memory_resident': 
                $extracted['memoria_residente'] = $metric['value']; 
                break;
            case 'memory_ram_total': 
                $extracted['memoria_total_kb'] = $metric['value']; 
                break;
            case 'cpu_all': 
                $extracted['cpu_total'] = $metric['value']; 
                $extracted['cpu_servico'] = $metric['value'];
                break;
            // case 'cpu_this_process': 
            //     $extracted['cpu_servico'] = $metric['value']; 
            //     break;
            case 'latency_db_query': 
                $extracted['latencia'] = $metric['value']; 
                break;
            case 'users_connected':
                $extracted['usuarios_all'] = $metric['users_connected_all'] ?? 0;
                $extracted['usuarios_unique'] = $metric['users_connected_unique'] ?? 0;
                break;
        }
    }
    return $extracted;
}

foreach ($services_to_monitor as $serviceName => $serviceInfo) {
    $ip   = $serviceInfo['ip'];
    $port = $serviceInfo['porta'];
    $url  = "https://{$ip}:{$port}/api/appserver/metrics/all";

    if (!isset($servers_summary[$ip])) {
        $servers_summary[$ip] = [
            'memoria_usada_kb' => 0,
            'memoria_total_kb' => 0,
            'max_cpu' => 0,
            'total_usuarios_all' => 0,
            'total_usuarios_unique' => 0,
            'online_services' => 0,
            'total_services' => 0,
        ];
    }
    $servers_summary[$ip]['total_services']++;

    // --- Requisição cURL ---
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $response_body = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200 && $response_body) {
        $api_data = json_decode($response_body, true);
        $metrics  = extract_metrics($api_data['metrics'] ?? []);
        
        $services_data[$serviceName] = [
            'status'      => 'Ativo',
            'memoria'     => round($metrics['memoria_residente'] / 1024),
            'latencia'    => $metrics['latencia'],
            'cpu_servico' => $metrics['cpu_servico'],
            'usuarios'    => $metrics['usuarios_all']
        ];

        $servers_summary[$ip]['online_services']++;
        $servers_summary[$ip]['memoria_usada_kb'] += $metrics['memoria_residente'];
        if ($servers_summary[$ip]['memoria_total_kb'] == 0 && $metrics['memoria_total_kb'] > 0) {
            $servers_summary[$ip]['memoria_total_kb'] = $metrics['memoria_total_kb'];
        }
        if ($metrics['cpu_total'] > $servers_summary[$ip]['max_cpu']) {
            $servers_summary[$ip]['max_cpu'] = $metrics['cpu_total'];
        }
        // A lógica para o resumo do servidor continua correta
        $servers_summary[$ip]['total_usuarios_all'] += $metrics['usuarios_all'];
        $servers_summary[$ip]['total_usuarios_unique'] += $metrics['usuarios_unique'];

    } else {
        $services_data[$serviceName] = [
            'status' => 'Inativo', 'memoria' => 0, 'latencia' => 0, 'cpu_servico' => 0, 'usuarios' => 0
        ];
    }
}

foreach ($servers_summary as $ip => &$summary) {
    $summary['memoria_usada_gb'] = round($summary['memoria_usada_kb'] / 1024 / 1024, 2);
    $summary['memoria_total_gb'] = round($summary['memoria_total_kb'] / 1024 / 1024, 2);
    $summary['memoria_percent']  = ($summary['memoria_total_kb'] > 0) ? round(($summary['memoria_usada_kb'] / $summary['memoria_total_kb']) * 100) : 0;
    unset($summary['memoria_usada_kb'], $summary['memoria_total_kb']);
}

$final_response = [
    'servers'  => $servers_summary,
    'services' => $services_data
];

echo json_encode($final_response, JSON_PRETTY_PRINT);
