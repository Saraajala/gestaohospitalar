<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatórios Clínicos e Administrativos</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        label { display: block; margin-top: 10px; }
        select, input[type="date"] { padding: 5px; width: 200px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Relatórios Clínicos e Administrativos</h1>

    <?php
    require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/config.php';
    require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/controller/relatorioscontroller.php';

    $controller = new RelatoriosController($pdo);

    $data_inicio = $_GET['data_inicio'] ?? '';
    $data_fim = $_GET['data_fim'] ?? '';
    $especialidade = $_GET['especialidade'] ?? '';

    $especialidades = $controller->getEspecialidades();
    $relatorios = $_GET ? $controller->gerarRelatorio($data_inicio, $data_fim, $especialidade) : [];
    $indicadores = $_GET ? $controller->getIndicadores($relatorios) : [];
    ?>

    <form method="GET">
        <label>Data Inicial:</label>
        <input type="date" name="data_inicio" value="<?= htmlspecialchars($data_inicio) ?>">

        <label>Data Final:</label>
        <input type="date" name="data_fim" value="<?= htmlspecialchars($data_fim) ?>">

        <label>Especialidade:</label>
        <select name="especialidade">
            <option value="">-- Todas --</option>
            <?php foreach ($especialidades as $esp): ?>
                <option value="<?= htmlspecialchars($esp) ?>" <?= $especialidade === $esp ? 'selected' : '' ?>>
                    <?= htmlspecialchars($esp) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>
        <button type="submit">Gerar Relatório</button>
    </form>

    <?php if (!empty($relatorios)): ?>
        <h2>Relatório de Consultas</h2>
        <table>
            <tr>
                <th>Paciente</th>
                <th>Médico</th>
                <th>Especialidade</th>
                <th>Data</th>
                <th>Status</th>
            </tr>
            <?php foreach ($relatorios as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['paciente']) ?></td>
                    <td><?= htmlspecialchars($r['medico']) ?></td>
                    <td><?= htmlspecialchars($r['area_de_atuacao']) ?></td>
                    <td><?= date("d/m/Y H:i", strtotime($r['data_consulta'])) ?></td>
                    <td><?= ucfirst($r['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h3>Total de consultas: <?= $indicadores['total_consultas'] ?></h3>
        <h3>Total de pacientes atendidos: <?= $indicadores['pacientes_unicos'] ?></h3>

        <!-- Gráfico de consultas por especialidade -->
        <canvas id="graficoConsultas" width="600" height="300"></canvas>
        <script>
            const ctx = document.getElementById('graficoConsultas').getContext('2d');
            const labels = [
                <?php
                $areas = array_count_values(array_column($relatorios, 'area_de_atuacao'));
                foreach ($areas as $area => $count) { echo "'$area',"; }
                ?>
            ];
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Consultas por Especialidade',
                    data: [<?php foreach ($areas as $count) { echo $count . ","; } ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)'
                }]
            };
            const config = {
                type: 'bar',
                data: data,
                options: { responsive: true, plugins: { legend: { display: false } } }
            };
            new Chart(ctx, config);
        </script>

    <?php elseif($_GET): ?>
        <p>Nenhum registro encontrado para os filtros selecionados.</p>
    <?php endif; ?>
</body>
</html>
