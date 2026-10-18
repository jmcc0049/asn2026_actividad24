<?php
declare(strict_types=1);
$input = (string)($_GET['q'] ?? '<script>alert("XSS")</script>');
function e(string $v): string { return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>XSS reflejado</title>
<style>body{font:16px system-ui;max-width:820px;margin:2rem auto;padding:0 1rem;color:#182230}input{padding:.6rem;width:min(100%,30rem)}button{padding:.6rem}pre{white-space:pre-wrap;background:#f2f5f9;padding:1rem}.bad{border-left:4px solid #b91c1c;padding:1rem;background:#fef2f2}.good{border-left:4px solid #15803d;padding:1rem;background:#f0fdf4}</style></head><body>
<p><a href="index.php">← Inicio</a></p><h1>Experimento 3: XSS reflejado</h1>
<p>Introduce texto para observar cómo la salida sin codificar puede interpretarse como HTML. No uses esta versión vulnerable fuera del laboratorio.</p>
<form method="get"><label for="q">Texto de entrada</label><br><input id="q" name="q" value="<?= e($input) ?>"><button>Enviar</button></form>
<h2>Versión vulnerable (demostración)</h2><div class="bad"><p>El valor se inserta sin codificación:</p><div><?= $input ?></div></div>
<h2>Versión protegida</h2><div class="good"><p>El valor se codifica con <code>htmlspecialchars</code>:</p><div><?= e($input) ?></div></div>
<h2>Qué comprobar</h2><ul><li>Con el valor predeterminado, la versión vulnerable intenta ejecutar JavaScript.</li><li>La versión protegida muestra la cadena como texto.</li><li>La codificación debe adaptarse al contexto de salida (HTML, atributo, JavaScript, URL, etc.).</li></ul>
</body></html>
