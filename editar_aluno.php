<?php
session_start();if(empty($_SESSION['professor_id'])){header('Location: login.php');exit;}
require_once __DIR__.'/config.php';
function h($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
$pdo=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
if(!$id){header('Location: dashboard.php');exit;}
$modalidadeProfessor = isset($_SESSION['modalidade_id']) ? (int)$_SESSION['modalidade_id'] : 0;
if ($modalidadeProfessor > 0) {
 $modsStmt=$pdo->prepare('SELECT id,nome FROM modalidades WHERE id=? ORDER BY nome'); $modsStmt->execute([$modalidadeProfessor]); $mods=$modsStmt->fetchAll();
 $al=$pdo->prepare('SELECT * FROM alunos WHERE id=? AND modalidade_id=?'); $al->execute([$id,$modalidadeProfessor]); $aluno=$al->fetch();
} else {
 $mods=$pdo->query('SELECT id,nome FROM modalidades ORDER BY nome')->fetchAll();
 $al=$pdo->prepare('SELECT * FROM alunos WHERE id=?');$al->execute([$id]);$aluno=$al->fetch();
}
if(!$aluno){header('Location: dashboard.php?erro=1');exit;}
$erro='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(!hash_equals($_SESSION['csrf']??'',$_POST['csrf']??'')){http_response_code(403);exit('Solicitação inválida.');}
 $nome=trim($_POST['nome']??'');$nasc=$_POST['data_nascimento']??'';$tel=trim($_POST['telefone']??'');$end=trim($_POST['endereco']??'');$mid=filter_var($_POST['modalidade_id']??'',FILTER_VALIDATE_INT);
 if ($modalidadeProfessor > 0) { $mid = $modalidadeProfessor; }$iid=($_POST['instrumento_id']??'')!==''?filter_var($_POST['instrumento_id'],FILTER_VALIDATE_INT):null;$status=$_POST['status']??'Ativo';
 $dt=DateTime::createFromFormat('!Y-m-d',$nasc);
 if(!$nome||mb_strlen($nome)>150||!$dt||$dt>new DateTime('today')||!$mid||!in_array($status,['Ativo','Inativo'],true)){$erro='Confira os campos obrigatórios.';}
 else{
  $m=$pdo->prepare('SELECT nome FROM modalidades WHERE id=?');$m->execute([$mid]);$mod=$m->fetch();
  if(!$mod){$erro='Modalidade inválida.';}
  else{
   if(stripos($mod['nome'],'banda')!==false){$check=$pdo->prepare('SELECT id FROM instrumentos WHERE id=? AND modalidade_id=?');$check->execute([$iid,$mid]);if(!$iid||!$check->fetch())$erro='Selecione um instrumento válido para a banda.';}
   else $iid=null;
  }
  if(!$erro){$up=$pdo->prepare('UPDATE alunos SET nome=?,data_nascimento=?,telefone=?,endereco=?,modalidade_id=?,instrumento_id=?,status=? WHERE id=?');$up->execute([$nome,$nasc,$tel?:null,$end?:null,$mid,$iid,$status,$id]);header('Location: dashboard.php?ok=1');exit;}
 }
 $aluno=array_merge($aluno,['nome'=>$nome,'data_nascimento'=>$nasc,'telefone'=>$tel,'endereco'=>$end,'modalidade_id'=>$mid,'instrumento_id'=>$iid,'status'=>$status]);
}
$instrumentos=$pdo->query('SELECT id,nome,modalidade_id FROM instrumentos ORDER BY nome')->fetchAll();
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Editar aluno | Instituto Amigo Bob</title><link rel="stylesheet" href="admin.css"></head><body><header class="topbar"><div class="brand"><img src="assets/logo.png" alt=""><div><strong>Instituto Amigo Bob</strong><small>Editar cadastro</small></div></div><a class="btn ghost" href="dashboard.php">← Voltar ao painel</a></header><main class="edit-wrap"><section class="panel edit-panel"><p class="eyebrow">CADASTRO #<?= (int)$id ?></p><h1>Editar aluno</h1><p class="muted">Atualize os dados e salve as alterações.</p><?php if($erro):?><div class="alert error"><?=h($erro)?></div><?php endif;?>
<form method="post" class="edit-grid"><input type="hidden" name="id" value="<?=$id?>"><input type="hidden" name="csrf" value="<?=h($_SESSION['csrf'])?>">
<label class="span-2">Nome completo *<input name="nome" maxlength="150" required value="<?=h($aluno['nome'])?>"></label>
<label>Data de nascimento *<input type="date" name="data_nascimento" required value="<?=h($aluno['data_nascimento'])?>"></label>
<label>Telefone<input name="telefone" value="<?=h($aluno['telefone'])?>"></label>
<label class="span-2">Endereço<input name="endereco" value="<?=h($aluno['endereco'])?>"></label>
<label>Modalidade *<select name="modalidade_id" id="modalidade" required><?php foreach($mods as $m):?><option value="<?=$m['id']?>" <?=$aluno['modalidade_id']==$m['id']?'selected':''?>><?=h($m['nome'])?></option><?php endforeach;?></select></label>
<label id="instrumento-wrap">Instrumento<select name="instrumento_id" id="instrumento"><option value="">Selecione</option><?php foreach($instrumentos as $i):?><option value="<?=$i['id']?>" data-modalidade="<?=$i['modalidade_id']?>" <?=$aluno['instrumento_id']==$i['id']?'selected':''?>><?=h($i['nome'])?></option><?php endforeach;?></select></label>
<label>Status<select name="status"><option <?=$aluno['status']==='Ativo'?'selected':''?>>Ativo</option><option <?=$aluno['status']==='Inativo'?'selected':''?>>Inativo</option></select></label>
<div class="span-2 form-buttons"><button class="btn primary" type="submit">Salvar alterações</button><a class="btn ghost" href="dashboard.php">Cancelar</a></div></form></section></main>
<script>
const modal=document.getElementById('modalidade'), inst=document.getElementById('instrumento'), wrap=document.getElementById('instrumento-wrap');
function sync(){const label=modal.options[modal.selectedIndex]?.text.toLowerCase()||'';const banda=label.includes('banda');wrap.style.display=banda?'grid':'none';for(const o of inst.options){if(o.value)o.hidden=o.dataset.modalidade!==modal.value;}if(!banda)inst.value='';}
modal.addEventListener('change',sync);sync();
</script></body></html>