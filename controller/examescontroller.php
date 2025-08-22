<?php
require_once 'C:\Turma2\xampp\htdocs\gestaohospitalar\model\examesmodel.php';

class ExameController {
    private $exameModel;

    public function __construct($pdo) {
        $this->exameModel = new Exame($pdo);
    }

    public function index() {
        $exames = $this->exameModel->listar();
        include 'C:\Turma2\xampp\htdocs\gestaohospitalar\view\exame_list.php';
    }

    public function criar() {
        include 'C:\Turma2\xampp\htdocs\gestaohospitalar\view\exame_form.php';
    }

    public function salvar() {
        $this->exameModel->salvar($_POST);
        header("Location: index.php");
        exit;
    }

    public function detalhar($id) {
        $exame = $this->exameModel->detalhar($id);
        include 'C:\Turma2\xampp\htdocs\gestaohospitalar\view\exame_detalhe.php';
    }

    public function atualizarEtapa($id, $etapa) {
        $this->exameModel->atualizarEtapa($id, $etapa);
        header("Location: index.php");
        exit;
    }

    public function salvarResultado($id) {
        $this->exameModel->salvarResultado($id, $_POST['resultado'], isset($_POST['critico']));
        header("Location: index.php");
        exit;
    }
}
