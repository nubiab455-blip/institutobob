<?php
session_start();
require_once __DIR__ . '/config.php';
if (!empty($_SESSION['professor_id'])) { header('Location: dashboard.php'); exit; }
$erro = isset($_GET['erro']) ? 'Usuário ou senha inválidos.' : '';
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Área dos professores | Instituto Amigo Bob</title><link rel="stylesheet" href="admin.css"></head>
<body class="login-body"><main class="login-card">
  <img class="login-logo" src="assets/logo.png" alt="Instituto Amigo Bob">
  <p class="eyebrow">INSTITUTO AMIGO BOB</p><h1>Área dos professores</h1><p class="muted">Entre para administrar as inscrições dos alunos.</p>
  <?php if ($erro): ?><div class="alert error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
  <?php if (isset($_GET['senha'])): ?><div class="alert success">Senha alterada. Entre novamente com a nova senha.</div><?php endif; ?>
  <form action="autenticar.php" method="post" class="stack">
    <label>Usuário<input name="usuario" required autocomplete="username" placeholder="Seu usuário"></label>
    <label>Senha<input type="password" name="senha" required autocomplete="current-password" placeholder="Sua senha"></label>
    <button class="btn primary full" type="submit">Entrar no painel <span>→</span></button>
  </form>
  <a class="back-link" href="index.php">← Voltar para inscrições</a>
</main></body></html>