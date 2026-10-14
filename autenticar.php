<?php
session_start();
require_once __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: login.php'); exit; }
$usuario = trim($_POST['usuario'] ?? '');
$senha = $_POST['senha'] ?? '';
try {
 $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
 $stmt=$pdo->prepare('SELECT id,nome,usuario,senha_hash,modalidade_id FROM professores WHERE usuario=? AND ativo=1 LIMIT 1');
 $stmt->execute([$usuario]); $p=$stmt->fetch();
 if ($p && password_verify($senha,$p['senha_hash'])) {
   session_regenerate_id(true); $_SESSION['professor_id']=(int)$p['id']; $_SESSION['professor_nome']=$p['nome']; $_SESSION['modalidade_id']=$p['modalidade_id'] !== null ? (int)$p['modalidade_id'] : null; $_SESSION['csrf']=bin2hex(random_bytes(32));
   header('Location: dashboard.php'); exit;
 }
} catch(Throwable $e) { error_log($e->getMessage()); }
header('Location: login.php?erro=1'); exit;
