<?php
require '../config/database.php';

$pageTitle = 'Tarefas';
$status = $_GET['status'] ?? '';
$statuses = ['Pendente', 'Em andamento', 'Concluída'];

$sql = 'SELECT * FROM tarefas';
$params = [];

if (in_array($status, $statuses, true)) {
    $sql .= ' WHERE status = ?';
    $params[] = $status;
}

$sql .= ' ORDER BY prazo ASC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$tarefas = $stmt->fetchAll();

require '../includes/header.php';
?>

<div class="head">
    <div>
        <h1>Tarefas</h1>
        <p class="muted">Cadastre e acompanhe as atividades.</p>
    </div>

    <a class="btn" href="form.php">+ Nova tarefa</a>
</div>

<section class="panel">

    <form method="get" style="display:flex;gap:10px;align-items:end">
        <div class="field">
            <label>Filtrar por status</label>

            <select name="status">
                <option value="">Todos</option>

                <?php foreach ($statuses as $s): ?>
                    <option value="<?= htmlspecialchars($s) ?>"
                        <?= $status === $s ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn gray">Filtrar</button>

        <a href="index.php" class="btn gray">Limpar</a>
    </form>

    <div class="scroll">
        <table>
            <tr>
                <th>Tarefa</th>
                <th>Responsável</th>
                <th>Prazo</th>
                <th>Prioridade</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>

            <?php if (count($tarefas) > 0): ?>

                <?php foreach ($tarefas as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['titulo']) ?></td>
                        <td><?= htmlspecialchars($t['responsavel']) ?></td>
                        <td><?= date('d/m/Y', strtotime($t['prazo'])) ?></td>
                        <td><?= htmlspecialchars($t['prioridade']) ?></td>
                        <td><?= htmlspecialchars($t['status']) ?></td>

                        <td>
                            <a class="btn gray"
                               href="form.php?id=<?= (int)$t['id'] ?>">
                                Editar
                            </a>

                        
                            <form method="post"
                                  action="excluir.php"
                                  style="display:inline"
                                  onsubmit="return confirm('Deseja realmente excluir esta tarefa?');">
                                <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                                <button type="submit" class="btn red">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="6">Nenhuma tarefa encontrada.</td>
                </tr>

            <?php endif; ?>
        </table>
    </div>

</section>

<?php require '../includes/footer.php'; ?>