<?php
session_start();if(empty($_SESSION['professor_id'])){header('Location: login.php');exit;}
require_once __DIR__.'/config.php';$erro='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(!hash_equals($_SESSION['csrf']??'',$_POST['csrf']??'')){http_response_code(403);exit('Solicitação inválida.');}
 $atual=$_POST['atual']??'';$nova=$_POST['nova']??'';$conf=$_POST['confirmar']??'';
 try{$pdo=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
 $s=$pdo->prepare('SELECT senha_hash FROM professores WHERE id=?');$s->execute([$_SESSION['professor_id']]);$row=$s->fetch();
 if(!$row||!password_verify($atual,$row['senha_hash']))$erro='A senha atual está incorreta.';
 elseif(strlen($nova)<10)$erro='A nova senha precisa ter pelo menos 10 caracteres.';
 elseif($nova!==$conf)$erro='A confirmação da senha não corresponde.';
 else{$u=$pdo->prepare('UPDATE professores SET senha_hash=? WHERE id=?');$u->execute([password_hash($nova,PASSWORD_DEFAULT),$_SESSION['professor_id']]);session_destroy();header('Location: login.php?senha=1');exit;}
 }catch(Throwable $e){error_log($e->getMessage());$erro='Não foi possível alterar a senha.';}
}
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Alterar senha</title><link rel="stylesheet" href="admin.css"></head><body><header class="topbar"><div class="brand"><img src="assets/logo.png" alt=""><div><strong>Instituto Amigo Bob</strong><small>Segurança da conta</small></div></div><a class="btn ghost" href="dashboard.php">← Voltar ao painel</a></header><main class="edit-wrap"><section class="panel edit-panel"><h1>Alterar senha</h1><p class="muted">Use uma senha forte com pelo menos 10 caracteres.</p><?php if($erro):?><div class="alert error"><?=htmlspecialchars($erro,ENT_QUOTES,'UTF-8')?></div><?php endif;?><form method="post" class="stack"><input type="hidden" name="csrf" value="<?=htmlspecialchars($_SESSION['csrf'],ENT_QUOTES,'UTF-8')?>"><label>Senha atual<input type="password" name="atual" required autocomplete="current-password"></label><label>Nova senha<input type="password" name="nova" minlength="10" required autocomplete="new-password"></label><label>Confirmar nova senha<input type="password" name="confirmar" minlength="10" required autocomplete="new-password"></label><button class="btn primary" type="submit">Atualizar senha</button></form></section></main></body></html>