<?php
header('Content-Type: application/json');
$configFile = 'config_services.json';

// Garante que o arquivo exista
if (!file_exists($configFile)) {
    file_put_contents($configFile, '{}');
}

// Lê os dados do POST
$input  = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? null;

// Lê os serviços atuais do arquivo
$services = json_decode(file_get_contents($configFile), true);
$response = ['success' => false, 'message' => 'Ação inválida.'];

switch ($action) {
    case 'read':
        $response = ['success' => true, 'data' => $services];
        break;

    case 'save':
        if (empty($input['nome']) || empty($input['ip']) || !isset($input['porta']) || $input['porta'] === '') {
            $response = ['message' => 'Dados incompletos. Nome, IP e Porta são obrigatórios.'];
            break;
        }

        // --- INÍCIO DA VALIDAÇÃO DE PORTA ---
        $isEditMode      = !empty($input['original_nome']);
        $portToCheck     = $input['porta'];
        $portIsDuplicate = false;

        foreach ($services as $existingName => $existingData) {
            // Verifica se a porta já está em uso
            if ($existingData['porta'] == $portToCheck) {
                // Se estiver em modo de edição, a porta é válida se pertencer ao próprio registro.
                if ($isEditMode && $existingName === $input['original_nome']) {
                    continue; // Porta pertence ao mesmo item, ignora e continua a verificação.
                }
                
                // Se chegou aqui, a porta é uma duplicata (ou é um item novo, ou é de outro item na edição).
                $portIsDuplicate = true;
                $response = ['message' => "Erro: A porta {$portToCheck} já está sendo utilizada pelo serviço '{$existingName}'. Escolha outra porta."];
                break; // Encontrou duplicata, pode parar o loop.
            }
        }

        if ($portIsDuplicate) {
            break; // Para a execução do 'case' se a porta for duplicada.
        }
        // --- FIM DA VALIDAÇÃO ---

        // Se a validação passou, continua com o processo de salvar
        $serviceName = $input['nome'];
        
        // Se for uma edição e o nome mudou, remove o antigo registro
        if ($isEditMode && $input['original_nome'] !== $serviceName) {
            unset($services[$input['original_nome']]);
        }

        $services[$serviceName] = [
            'ip'    => $input['ip'],
            'porta' => $input['porta']
        ];
        
        ksort($services);

        if (file_put_contents($configFile, json_encode($services, JSON_PRETTY_PRINT))) {
            $response = ['success' => true, 'message' => 'Serviço salvo com sucesso!'];
        } else {
            $response = ['message' => 'Erro ao salvar o arquivo de configuração.'];
        }
        
        break;

    case 'delete':
        if (!empty($input['nome'])) {
            $serviceName = $input['nome'];
            if (isset($services[$serviceName])) {
                unset($services[$serviceName]);
                if (file_put_contents($configFile, json_encode($services, JSON_PRETTY_PRINT))) {
                    $response = ['success' => true, 'message' => 'Serviço excluído com sucesso!'];
                } else {
                    $response = ['message' => 'Erro ao salvar o arquivo após a exclusão.'];
                }
            } else {
                $response = ['message' => 'Serviço não encontrado para exclusão.'];
            }
        } else {
            $response = ['message' => 'Nome do serviço não fornecido para exclusão.'];
        }
        break;
}

echo json_encode($response);
