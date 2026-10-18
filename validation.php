<?php
declare(strict_types=1);
function e(string $v): string { return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
$name = '';
$age = '';
$errors = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string)($_POST['name'] ?? ''));
    $age = trim((string)($_POST['age'] ?? ''));
    // Validación del lado servidor: no confiar únicamente en atributos HTML.
    if ($name === '' || mb_strlen($name) > 60) $errors[] = 'El nombre es obligatorio y debe tener 60 caracteres como máximo.';
    if (!preg_match('/^(?:[1-9][0-9]?|1[01][0-9]|120)$/', $age)) $errors[] = 'La edad debe ser un entero entre 1 y 120.';
    if (!$errors) $success = true;
}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Validación de datos</title>
<style>body{font:16px system-ui;max-width:760px;margin:2rem auto;padding:0 1rem;color:#182230}label{display:block;margin:.8rem 0 .25rem}input{padding:.6rem;width:min(100%,24rem)}button{margin-top:1rem;padding:.65rem 1rem} .error{color:#a11} .ok{color:#176b35} pre{white-space:pre-wrap;background:#f2f5f9;padding:1rem}</style></head><body>
<p><a href="index.php">← Inicio</a></p><h1>Experimento 1: validación de datos</h1>
<p>La validación se realiza en el servidor y la salida se codifica antes de insertarla en HTML.</p>
<form method="post">
<label for="name">Nombre</label><input id="name" name="name" maxlength="60" required value="<?= e($name) ?>">
<label for="age">Edad (1–120)</label><input id="age" name="age" type="number" min="1" max="120" required value="<?= e($age) ?>">
<button type="submit">Validar</button></form>
<?php foreach ($errors as $error): ?><p class="error"><?= e($error) ?></p><?php endforeach; ?>
<?php if ($success): ?><p class="ok">Entrada válida.</p><pre>Nombre: <?= e($name) . "\n" ?>Edad: <?= e($age) ?></pre><?php endif; ?>
<h2>Qué comprobar</h2><ul><li>Enviar el formulario vacío o una edad fuera de rango: el servidor debe rechazarlo.</li><li>Probar etiquetas HTML en el nombre: deben mostrarse como texto, no ejecutarse.</li><li>Los atributos HTML mejoran la experiencia, pero la validación del servidor es la barrera de seguridad.</li></ul>
</body></html>
