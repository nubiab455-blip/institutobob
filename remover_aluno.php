<?php
session_start(); if(empty($_SESSION['professor_id'])){header('Location: login.php');exit;}
require_once __DIR__.'/config.php';
if($_SERVER['REQUEST_METHOD']!=='POST' || !hash_equals($_SESSION['csrf']??'', $_POST['csrf']??'')){http_response_code(403);exit('Solicitação inválida.');}
$id=filter_input(INPUT_POST,'aluno_id',FILTER_VALIDATE_INT);
if(!$id){header('Location: dashboard.php?erro=1');exit;}
try{
 $pdo=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
 $modalidadeProfessor = isset($_SESSION['modalidade_id']) ? (int)$_SESSION['modalidade_id'] : 0;
if ($modalidadeProfessor > 0) {
 $st=$pdo->prepare('DELETE FROM alunos WHERE id=? AND modalidade_id=?'); $st->execute([$id,$modalidadeProfessor]);
} else {
 $st=$pdo->prepare('DELETE FROM alunos WHERE id=?'); $st->execute([$id]);
}
 header('Location: dashboard.php?'.($st->rowCount()?'ok=1':'erro=1'));exit;
}catch(Throwable $e){error_log($e->getMessage());header('Location: dashboard.php?erro=1');exit;}
