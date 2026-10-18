<?php
declare(strict_types=1);
session_start();
$dbPath = __DIR__ . '/data/lab.sqlite';
if (!is_dir(__DIR__ . '/data')) mkdir(__DIR__ . '/data', 0770, true);
$pdo = new PDO('sqlite:' . $dbPath, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
$pdo->exec('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, username TEXT NOT NULL, role TEXT NOT NULL)');
$count = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
if ($count === 0) {
    $seed = $pdo->prepare('INSERT INTO users(username, role) VALUES (?, ?)');
    foreach ([['ana','usuario'],['bruno','administrador'],['carla','usuario']] as $row) $seed->execute($row);
}
$mode = ($_POST['mode'] ?? 'safe') === 'unsafe' ? 'unsafe' : 'safe';
$search = (string)($_POST['username'] ?? 'ana');
$results = [];
$error = '';
$queryShown = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($mode === 'unsafe') {
            // INTENCIONADAMENTE VULNERABLE: concatenar entrada en SQL.
            $queryShown = "SELECT id, username, role FROM users WHERE username = '" . $search . "'";
            $results = $pdo->query($queryShown)->fetchAll();
        } else {
            // SEGURO: consulta parametrizada; el valor se trata como dato, no como SQL.
            $queryShown = 'SELECT id, username, role FROM users WHERE username = :username';
            $stmt = $pdo->prepare($queryShown);
            $stmt->execute(['username' => $search]);
            $results = $stmt->fetchAll();
        }
    } catch (Throwable $e) { $error = 'La consulta ha fallado. Los detalles internos se omiten deliberadamente.'; }
}
function e(string $v): string { return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Inyección SQL</title>
<style>body{font:16px system-ui;max-width:820px;margin:2rem auto;padding:0 1rem;color:#182230}label{display:block;margin:.8rem 0 .25rem}input,select{padding:.6rem;max-width:100%}button{padding:.65rem 1rem;margin-top:1rem}table{border-collapse:collapse}td,th{border:1px solid #ccd5e0;padding:.5rem}pre{white-space:pre-wrap;background:#f2f5f9;padding:1rem}.warn{color:#a11}</style></head><body>
<p><a href="index.php">← Inicio</a></p><h1>Experimento 2: inyección SQL</h1>
<p>Base de datos SQLite con usuarios ficticios. Usa únicamente los payloads de prueba en este entorno.</p>
<form method="post"><label for="username">Nombre de usuario / entrada de prueba</label><input id="username" name="username" value="<?= e($search) ?>" size="45">
<label for="mode">Modo</label><select id="mode" name="mode"><option value="safe" <?= $mode==='safe'?'selected':'' ?>>Protegido (consulta preparada)</option><option value="unsafe" <?= $mode==='unsafe'?'selected':'' ?>>Vulnerable (concatenación SQL)</option></select><button>Ejecutar consulta</button></form>
<p>Prueba controlada: <code>' OR '1'='1' -- </code>. En modo vulnerable puede devolver todas las filas; en modo protegido debe buscar literalmente ese texto.</p>
<?php if ($error): ?><p class="warn"><?= e($error) ?></p><?php endif; ?>
<?php if ($queryShown !== ''): ?><h2>Consulta ejecutada</h2><pre><?= e($queryShown) ?></pre><h2>Resultados (<?= count($results) ?>)</h2>
<?php if ($results): ?><table><thead><tr><th>ID</th><th>Usuario</th><th>Rol</th></tr></thead><tbody><?php foreach ($results as $row): ?><tr><td><?= (int)$row['id'] ?></td><td><?= e((string)$row['username']) ?></td><td><?= e((string)$row['role']) ?></td></tr><?php endforeach; ?></tbody></table><?php else: ?><p>Sin resultados.</p><?php endif; ?><?php endif; ?>
<h2>Conclusión</h2><p>Las consultas preparadas separan la estructura SQL de los valores introducidos. La validación de entrada es complementaria, pero no sustituye a la parametrización.</p>
</body></html>
