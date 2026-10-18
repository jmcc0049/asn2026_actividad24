<?php
declare(strict_types=1);
session_start();
if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
if (!isset($_SESSION['demo_value'])) $_SESSION['demo_value'] = 'sin cambios';
$message = '';
$status = 200;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = (string)($_POST['csrf_token'] ?? '');
    if (!hash_equals((string)$_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        $message = 'Solicitud rechazada: token CSRF ausente o inválido.';
    } else {
        $value = (string)($_POST['value'] ?? '');
        if (!in_array($value, ['activar', 'desactivar'], true)) {
            http_response_code(400);
            $message = 'Valor no permitido.';
        } else {
            $_SESSION['demo_value'] = $value;
            $message = 'Cambio aceptado tras verificar el token CSRF.';
        }
    }
}
function e(string $v): string { return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Protección CSRF</title>
<style>body{font:16px system-ui;max-width:760px;margin:2rem auto;padding:0 1rem;color:#182230}button{padding:.65rem 1rem;margin:.3rem 0}.status{background:#f2f5f9;padding:1rem;border-radius:8px}</style></head><body>
<p><a href="index.php">← Inicio</a></p><h1>Experimento 4: protección CSRF</h1>
<p>Este formulario simula una operación que modifica estado. El servidor exige un token aleatorio asociado a la sesión y lo compara con <code>hash_equals</code>.</p>
<div class="status"><strong>Estado actual:</strong> <?= e((string)$_SESSION['demo_value']) ?></div>
<?php if ($message): ?><p><?= e($message) ?></p><?php endif; ?>
<form method="post">
<input type="hidden" name="csrf_token" value="<?= e((string)$_SESSION['csrf_token']) ?>">
<button name="value" value="activar">Activar</button>
<button name="value" value="desactivar">Desactivar</button>
</form>
<h2>Prueba</h2><ol><li>Envía el formulario normalmente: el cambio debe aceptarse.</li><li>Con las herramientas del navegador, elimina o altera el campo <code>csrf_token</code> y reenvía: el servidor debe responder con HTTP 403.</li><li>No basta con ocultar el campo en la interfaz: la comprobación debe hacerse en el servidor.</li></ol>
<p>En producción, combina tokens CSRF con cookies de sesión configuradas con <code>Secure</code>, <code>HttpOnly</code> y <code>SameSite</code> apropiado, HTTPS y comprobaciones de origen cuando proceda.</p>
</body></html>
