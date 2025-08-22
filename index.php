<?php
require_once 'C:\Turma2\xampp\htdocs\gestaohospitalar\controller\examescontroller.php';

$controller = new ExameController($pdo);

$acao = $_GET['acao'] ?? 'index';

switch ($acao) {
    case 'index': $controller->index(); break;
    case 'criar': $controller->criar(); break;
    case 'salvar': $controller->salvar(); break;
    case 'detalhar': $controller->detalhar($_GET['id']); break;
    case 'etapa': $controller->atualizarEtapa($_GET['id'], $_GET['etapa']); break;
    case 'resultado': $controller->salvarResultado($_GET['id']); break;
    default: $controller->index();
}
