<?php
require_once __DIR__ . '/../model/NotificacoesModel.php';

class NotificacoesController {
    private $model;

    public function __construct($pdo) {
        $this->model = new NotificacoesModel($pdo);
    }

    public function listarNotificacoes($paciente_id) {
        return $this->model->getNotificacoes($paciente_id);
    }
}
