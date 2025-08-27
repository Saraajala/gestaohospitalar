<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/internacoesmodel.php';

$model = new InternacoesModel($pdo);

// --- Liberar leitos que estão em limpeza há mais de 10 segundos ---
$pdo->prepare("
    UPDATE leitos 
    SET status='livre', data_limpeza=NULL 
    WHERE status='limpeza' AND data_limpeza <= NOW() - INTERVAL 10 SECOND
")->execute();

// Buscar dados
$pacientes     = $model->listarPacientes();
$leitosLivres  = $model->listarLeitosLivres();
$internacoes   = $model->listarInternacoesAtivas();
$leitos        = $model->listarLeitos(); // todos os leitos para exibir limpeza
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Gestão de Internações</title>
<style>
body{font-family:Arial,sans-serif;margin:20px;}
h1,h2{margin:10px 0}
section{margin-bottom:24px;}
table{border-collapse:collapse;width:100%;}
th,td{border:1px solid #ddd;padding:8px}
th{background:#f4f4f4}
.flex{display:flex;gap:12px;flex-wrap:wrap}
.card{border:1px solid #ddd;border-radius:8px;padding:12px;min-width:280px}
input,select,textarea,button{padding:8px;margin:4px 0;width:100%}
textarea{resize:vertical;min-height:60px}
.badge{display:inline-block;padding:2px 8px;border-radius:12px;font-size:12px;background:#eee}
.badge.ocupado{background:#ffdddd}
.badge.livre{background:#ddffdd}
.badge.limpeza{background:#fff3cd}
small.mono{font-family:monospace;color:#666;}
small.warning{color:#856404;}
.row-actions{display:flex;gap:8px;align-items:center}
</style>
</head>
<body>

<h1>Gestão de Internações</h1>

<section class="card">
  <h2>Internar Paciente</h2>
  <form method="POST" action="../controller/internacoescontroller.php">
    <input type="hidden" name="action" value="internar">
    <label>Paciente</label>
    <select name="paciente_id" required>
      <option value="">Selecione o paciente</option>
      <?php foreach($pacientes as $p): ?>
        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
      <?php endforeach; ?>
    </select>

    <label>Leito (apenas livres)</label>
    <select name="leito_id" required>
      <option value="">Selecione um leito</option>
      <?php foreach($leitosLivres as $l): ?>
        <option value="<?= $l['id'] ?>">Leito <?= htmlspecialchars($l['numero_leito']) ?></option>
      <?php endforeach; ?>
    </select>

    <button type="submit">Internar</button>
  </form>
</section>

<section class="card">
  <h2>Status de Leitos</h2>
  <table>
    <thead>
      <tr>
        <th>Leito</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($leitos as $l): ?>
      <tr>
        <td>Leito <?= htmlspecialchars($l['numero_leito']) ?></td>
        <td>
          <span class="badge <?= $l['status'] ?>"><?= $l['status'] ?></span>
          <?php if($l['status'] === 'limpeza'): ?>
            <br><small class="warning">⚠ Leito em limpeza, disponível em 10 segundos</small>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>

<section>
  <h2>Internações Ativas</h2>
  <?php if(empty($internacoes)): ?>
    <p>Nenhuma internação ativa no momento.</p>
  <?php else: ?>
  <table>
    <thead>
      <tr>
        <th>Paciente</th>
        <th>Leito</th>
        <th>Status do Leito</th>
        <th>Desde</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($internacoes as $i): ?>
      <tr>
        <td><?= htmlspecialchars($i['paciente']) ?></td>
        <td>Leito <?= htmlspecialchars($i['leito']) ?></td>
        <td>
          <span class="badge <?= $i['status_leito'] ?>"><?= $i['status_leito'] ?></span>
          <?php if($i['status_leito'] === 'limpeza'): ?>
            <br><small class="warning">⚠ Leito em limpeza, disponível em 10 segundos</small>
          <?php endif; ?>
        </td>
        <td>
          <?= !empty($i['data_entrada']) ? date('d/m/Y H:i', strtotime($i['data_entrada'])) : '-' ?>
        </td>
        <td>
          <!-- Troca de leito -->
          <form method="POST" action="../controller/internacoescontroller.php" style="display:inline-block; min-width:200px;">
            <input type="hidden" name="action" value="trocar_leito">
            <input type="hidden" name="internacao_id" value="<?= $i['id'] ?>">
            <select name="novo_leito_id" required>
              <option value="">Novo leito (livre)</option>
              <?php foreach($leitosLivres as $ll): ?>
                <option value="<?= $ll['id'] ?>">Leito <?= htmlspecialchars($ll['numero_leito']) ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit">Trocar</button>
          </form>

          <!-- Alta -->
          <form method="POST" action="../controller/internacoescontroller.php" style="display:inline-block; min-width:200px;">
            <input type="hidden" name="action" value="alta">
            <input type="hidden" name="internacao_id" value="<?= $i['id'] ?>">
            <textarea name="orientacoes" placeholder="Orientações de alta (opcional)"></textarea>
            <button type="submit" onclick="return confirm('Confirmar alta deste paciente?')">Dar Alta</button>
          </form>
        </td>
      </tr>

      <tr>
        <td colspan="5">
          <div class="flex">
            <!-- Evoluções -->
            <div class="card" style="flex:1">
              <h3>Evoluções</h3>
              <form method="POST" action="../controller/internacoescontroller.php">
                <input type="hidden" name="action" value="evolucao">
                <input type="hidden" name="internacao_id" value="<?= $i['id'] ?>">
                <textarea name="descricao" placeholder="Descreva a evolução..." required></textarea>
                <button type="submit">Adicionar evolução</button>
              </form>
              <ul>
                <?php foreach($model->listarEvolucoes($i['id']) as $ev): ?>
                  <li><small class="mono"><?= date('d/m/Y H:i', strtotime($ev['data_registro'])) ?></small> — <?= nl2br(htmlspecialchars($ev['descricao'])) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>

            <!-- Exames -->
            <div class="card" style="flex:1">
              <h3>Exames</h3>
              <form method="POST" action="../controller/internacoescontroller.php">
                <input type="hidden" name="action" value="exame">
                <input type="hidden" name="internacao_id" value="<?= $i['id'] ?>">
                <input type="text" name="nome_exame" placeholder="Nome do exame" required>
                <textarea name="resultado" placeholder="Resultado (opcional)"></textarea>
                <button type="submit">Adicionar exame</button>
              </form>
              <ul>
                <?php foreach($model->listarExames($i['id']) as $ex): ?>
                  <li>
                    <small class="mono"><?= date('d/m/Y H:i', strtotime($ex['data_registro'])) ?></small> — <b><?= htmlspecialchars($ex['nome_exame']) ?></b>
                    <?= !empty($ex['resultado']) ? ': ' . nl2br(htmlspecialchars($ex['resultado'])) : '' ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>

            <!-- Medicamentos -->
            <div class="card" style="flex:1">
              <h3>Medicamentos</h3>
              <form method="POST" action="../controller/internacoescontroller.php">
                <input type="hidden" name="action" value="medicamento">
                <input type="hidden" name="internacao_id" value="<?= $i['id'] ?>">
                <input type="text" name="nome_medicamento" placeholder="Nome do medicamento" required>
                <input type="text" name="dosagem" placeholder="Dosagem (ex: 500mg 8/8h)">
                <input type="text" name="horario_administracao" placeholder="Horário (ex: 08:00, 16:00)">
                <button type="submit">Adicionar medicamento</button>
              </form>
              <ul>
                <?php foreach($model->listarMedicamentos($i['id']) as $md): ?>
                  <li>
                    <small class="mono"><?= date('d/m/Y H:i', strtotime($md['data_registro'])) ?></small> — <b><?= htmlspecialchars($md['nome']) ?></b>
                    <?= $md['dosagem'] ? ' (' . htmlspecialchars($md['dosagem']) . ')' : '' ?>
                    <?= $md['horario_administracao'] ? ' — ' . htmlspecialchars($md['horario_administracao']) : '' ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>

            <!-- Procedimentos -->
            <div class="card" style="flex:1">
              <h3>Procedimentos</h3>
              <form method="POST" action="../controller/internacoescontroller.php">
                <input type="hidden" name="action" value="procedimento">
                <input type="hidden" name="internacao_id" value="<?= $i['id'] ?>">
                <textarea name="descricao" placeholder="Descreva o procedimento..." required></textarea>
                <button type="submit">Adicionar procedimento</button>
              </form>
              <ul>
                <?php foreach($model->listarProcedimentos($i['id']) as $pr): ?>
                  <li><small class="mono"><?= date('d/m/Y H:i', strtotime($pr['data_registro'])) ?></small> — <?= nl2br(htmlspecialchars($pr['descricao'])) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </td>
      </tr>

      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</section>

</body>
</html>
