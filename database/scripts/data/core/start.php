<?php

require_once './core/script-control.class.php';

$today = date('d/m/Y');
$last_update = null;
$env = 'local';
$operation_state = null;
$executed = false;

$run = $_GET['run'] ?? null;

$control = new ScriptControl();

// Inicializa operações de leitura de scripts já executados
$control->initializeOperation();

$last_update = $control->getLastExec();

if ($run !== 'true') {

    $executed = false;
    $operation_state = 'O controle de scripts ainda não foi executado.';
}
else {

    $executed = true;
    $operation_state = 'O controle de scripts foi executado com sucesso.';
}