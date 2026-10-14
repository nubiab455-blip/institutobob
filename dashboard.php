<?php
session_start();
if (empty($_SESSION['professor_id'])) { header('Location: login.php'); exit; }
require_once __DIR__ . '/config.php';
function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
$q=trim($_GET['q']??'');
$modalidadeProfessor = isset($_SESSION['modalidade_id']) ? (int)$_SESSION['modalidade_id'] : 0;
$modFiltro=filter_input(INPUT_GET,'modalidade',FILTER_VALIDATE_INT) ?: 0;
if ($modalidadeProfessor > 0) { $modFiltro = $modalidadeProfessor; }
if ($modalidadeProfessor > 0) {
 $stMods=$pdo->prepare('SELECT id,nome FROM modalidades WHERE id=? ORDER BY nome'); $stMods->execute([$modalidadeProfessor]); $mods=$stMods->fetchAll();
 $stTot=$pdo->prepare("SELECT COUNT(*) total, SUM(status='Ativo') ativos, SUM(status<>'Ativo') inativos FROM alunos WHERE modalidade_id=?"); $stTot->execute([$modalidadeProfessor]); $totais=$stTot->fetch();
 $stPor=$pdo->prepare('SELECT m.nome, COUNT(a.id) quantidade FROM modalidades m LEFT JOIN alunos a ON a.modalidade_id=m.id WHERE m.id=? GROUP BY m.id,m.nome'); $stPor->execute([$modalidadeProfessor]); $porMod=$stPor->fetchAll();
} else {
 $mods=$pdo->query('SELECT id,nome FROM modalidades ORDER BY nome')->fetchAll();
 $totais=$pdo->query("SELECT COUNT(*) total, SUM(status='Ativo') ativos, SUM(status<>'Ativo') inativos FROM alunos")->fetch();
 $porMod=$pdo->query('SELECT m.nome, COUNT(a.id) quantidade FROM modalidades m LEFT JOIN alunos a ON a.modalidade_id=m.id GROUP BY m.id,m.nome ORDER BY m.nome')->fetchAll();
}
$sql="SELECT a.id,a.nome,a.data_nascimento,a.telefone,a.data_matricula,a.status,m.nome modalidade,i.nome instrumento
      FROM alunos a LEFT JOIN modalidades m ON m.id=a.modalidade_id LEFT JOIN instrumentos i ON i.id=a.instrumento_id WHERE 1=1";
$args=[];
if($modalidadeProfessor > 0){$sql.=' AND a.modalidade_id=?';$args[]=$modalidadeProfessor;}
if($q!==''){ $sql.=" AND (a.nome LIKE ? OR a.telefone LIKE ?)"; $args[]="%$q%"; $args[]="%$q%"; }
if($modFiltro){$sql.=" AND a.modalidade_id=?";$args[]=$modFiltro;}
$sql.=" ORDER BY a.nome ASC LIMIT 500";
$stmt=$pdo->prepare($sql);$stmt->execute($args);$alunos=$stmt->fetchAll();
$msg=isset($_GET['ok'])?'Operação realizada com sucesso.':(isset($_GET['erro'])?'Não foi possível concluir a operação.':'');
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Painel administrativo | Instituto Amigo Bob</title><link rel="stylesheet" href="admin.css"></head><body>
<header class="topbar"><div class="brand"><img src="assets/logo.png" alt=""><div><strong>Instituto Amigo Bob</strong><small>Painel administrativo</small></div></div><div class="top-actions"><span>Olá, <?=h($_SESSION['professor_nome']??'Professor')?></span><a class="btn ghost" href="alterar_senha.php">Alterar senha</a><a class="btn logout" href="logout.php">Sair</a></div></header>
<main class="wrap">
  <section class="welcome"><div><p class="eyebrow">GESTÃO DO INSTITUTO</p><h1>Dashboard</h1><p class="muted"><?= $modalidadeProfessor > 0 ? 'Painel exclusivo da sua modalidade. Você só pode administrar os alunos sob sua responsabilidade.' : 'Painel de administração geral: acompanhe e gerencie todas as modalidades.' ?></p></div><a class="btn primary" href="index.php" target="_blank" rel="noopener">＋ Nova inscrição</a></section>
  <?php if($msg): ?><div class="alert <?=isset($_GET['ok'])?'success':'error'?>"><?=h($msg)?></div><?php endif; ?>
  <section class="stats">
    <article class="stat"><span class="stat-icon green">♙</span><div><small>Total de alunos</small><strong><?= (int)($totais['total']??0) ?></strong></div></article>
    <article class="stat"><span class="stat-icon blue">✓</span><div><small>Alunos ativos</small><strong><?= (int)($totais['ativos']??0) ?></strong></div></article>
    <article class="stat"><span class="stat-icon orange">▤</span><div><small>Modalidades</small><strong><?=count($mods)?></strong></div></article>
  </section>
  <section class="mod-grid"><?php foreach($porMod as $m): ?><article class="mod-card"><span><?=h($m['nome'])?></span><strong><?= (int)$m['quantidade'] ?></strong><small>aluno(s) inscrito(s)</small></article><?php endforeach; ?></section>
  <section class="panel">
    <div class="panel-head"><div><h2>Gerenciar alunos</h2><p class="muted">Pesquise, filtre ou remova um cadastro.</p></div><span class="count"><?=count($alunos)?> exibidos</span></div>
    <form class="filters" method="get"><label class="search">⌕ <input type="search" name="q" value="<?=h($q)?>" placeholder="Buscar por nome ou telefone"></label><?php if($modalidadeProfessor===0): ?><select name="modalidade"><option value="">Todas as modalidades</option><?php foreach($mods as $m):?><option value="<?=$m['id']?>" <?=$modFiltro===(int)$m['id']?'selected':''?>><?=h($m['nome'])?></option><?php endforeach;?></select><?php else: ?><input type="hidden" name="modalidade" value="<?=$modalidadeProfessor?>"><span class="count"><?=h($mods[0]['nome']??'Sua modalidade')?></span><?php endif; ?><button class="btn primary" type="submit">Filtrar</button><a class="btn ghost" href="dashboard.php">Limpar</a></form>
    <div class="table-wrap"><table><thead><tr><th>Aluno</th><th>Modalidade</th><th>Instrumento</th><th>Telefone</th><th>Matrícula</th><th>Status</th><th>Ações</th></tr></thead><tbody>
    <?php if(!$alunos): ?><tr><td colspan="7" class="empty">Nenhum aluno encontrado.</td></tr><?php endif; ?>
    <?php foreach($alunos as $a): ?><tr><td><strong><?=h($a['nome'])?></strong><small class="mobile-sub">ID #<?=$a['id']?></small></td><td><?=h($a['modalidade']??'—')?></td><td><?=h($a['instrumento']??'—')?></td><td><?=h($a['telefone']?:'—')?></td><td><?=h($a['data_matricula']?:'—')?></td><td><span class="pill <?=($a['status']==='Ativo'?'active':'inactive')?>"><?=h($a['status']?:'Sem status')?></span></td><td><div class="actions"><a class="btn small ghost" href="editar_aluno.php?id=<?=(int)$a['id']?>">Editar</a><form method="post" action="remover_aluno.php" onsubmit="return confirm('Tem certeza que deseja remover o cadastro de <?=h($a['nome'])?>? Esta ação não pode ser desfeita.');"><input type="hidden" name="csrf" value="<?=h($_SESSION['csrf'])?>"><input type="hidden" name="aluno_id" value="<?=(int)$a['id']?>"><button class="btn small danger" type="submit">Remover</button></form></div></td></tr><?php endforeach; ?>
    </tbody></table></div><p class="footnote">Por segurança, a listagem mostra até 500 alunos por vez. Use a busca para localizar cadastros.</p>
  </section>
</main><footer>Instituto Amigo Bob · Área restrita aos professores</footer></body></html>