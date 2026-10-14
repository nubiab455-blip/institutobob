<?php
require_once __DIR__ . '/config.php';
$modalidades = $instrumentos = [];
$erroBanco = false;
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    $modalidades = $pdo->query('SELECT id, nome FROM modalidades ORDER BY nome')->fetchAll();
    $instrumentos = $pdo->query('SELECT id, nome, modalidade_id FROM instrumentos ORDER BY nome')->fetchAll();
} catch (Throwable $e) {
    $erroBanco = true;
}
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#08783e">
<title>Inscrições | Instituto Amigo Bob</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="glow glow-a"></div><div class="glow glow-b"></div>
<main class="wrap">
  <header class="brand">
    <div class="logo-wrap"><img src="assets/logo.png" alt="Brasão do Instituto Amigo Bob"></div>
    <div class="brand-copy"><span class="kicker">FAÇA PARTE DA NOSSA HISTÓRIA</span><h1>Inscrição de alunos</h1><p>Instituto Amigo Bob · Educação, cultura e movimento</p></div>
  </header>

  <?php if (isset($_GET['sucesso'])): ?>
    <div class="alert success">✓ Inscrição enviada com sucesso! O cadastro foi registrado.</div>
  <?php elseif (isset($_GET['erro'])): ?>
    <div class="alert error"><?= h($_GET['erro']) ?></div>
  <?php endif; ?>
  <?php if ($erroBanco): ?>
    <div class="alert error">Não foi possível conectar ao banco. Confira os dados em <code>config.php</code> e confirme que as tabelas <code>modalidades</code> e <code>instrumentos</code> existem.</div>
  <?php endif; ?>

  <section class="card">
    <div class="card-top"><div class="spark">✦</div><div><h2>Vamos começar?</h2><p>Preencha os dados abaixo. Campos com * são obrigatórios.</p></div></div>
    <form action="processa.php" method="post" id="formInscricao">
      <div class="section-title"><span>01</span> Dados do aluno</div>
      <div class="grid">
        <label class="field wide"><span>Nome completo <b>*</b></span><input name="nome" required maxlength="150" autocomplete="name" placeholder="Nome completo do aluno"></label>
        <label class="field"><span>Data de nascimento <b>*</b></span><input type="date" name="data_nascimento" id="nascimento" required></label>
        <label class="field"><span>Telefone do aluno</span><input name="telefone" type="tel" maxlength="20" autocomplete="tel" placeholder="(00) 00000-0000"></label>
        <label class="field wide"><span>Endereço</span><input name="endereco" maxlength="255" autocomplete="street-address" placeholder="Rua, número, bairro e cidade"></label>
      </div>

      <div class="section-title"><span>02</span> Modalidade</div>
      <div class="grid">
        <label class="field" id="modalidadeField"><span>Escolha a modalidade <b>*</b></span>
          <select name="modalidade_id" id="modalidade" required>
            <option value="">Selecione uma opção</option>
            <?php foreach ($modalidades as $m): ?><option value="<?= (int)$m['id'] ?>"><?= h($m['nome']) ?></option><?php endforeach; ?>
          </select>
        </label>
        <label class="field" id="instrumentoBox" hidden><span>Instrumento da banda <b>*</b></span>
          <select name="instrumento_id" id="instrumento">
            <option value="">Selecione o instrumento</option>
            <?php foreach ($instrumentos as $i): ?>
            <option value="<?= (int)$i['id'] ?>" data-modalidade="<?= (int)$i['modalidade_id'] ?>"><?= h($i['nome']) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
      </div>

      <section class="guardian" id="responsavelBox" hidden>
        <div class="guardian-head"><span class="heart">♡</span><div><h3>Dados do responsável</h3><p>Como o aluno é menor de 18 anos, informe os dados do responsável legal.</p></div></div>
        <div class="grid">
          <label class="field wide"><span>Nome completo do responsável <b>*</b></span><input name="responsavel_nome" id="respNome" maxlength="150" placeholder="Nome do pai, mãe ou responsável"></label>
          <label class="field"><span>CPF do responsável <b>*</b></span><input name="responsavel_cpf" id="respCpf" maxlength="14" placeholder="000.000.000-00"></label>
          <label class="field"><span>Telefone do responsável <b>*</b></span><input name="responsavel_telefone" id="respTelefone" type="tel" maxlength="20" placeholder="(00) 00000-0000"></label>
          <label class="field wide"><span>Parentesco / vínculo <b>*</b></span><select name="responsavel_parentesco" id="respParentesco"><option value="">Selecione</option><option>Pai</option><option>Mãe</option><option>Avô/Avó</option><option>Tutor(a) legal</option><option>Outro responsável legal</option></select></label>
        </div>
      </section>

      <div class="section-title"><span>03</span> Confirmação</div>
      <label class="consent"><input type="checkbox" name="autorizacao" value="1" required><span>Confirmo que as informações são verdadeiras e autorizo o contato do Instituto sobre esta inscrição. <b>*</b></span></label>
      <button class="submit" type="submit"><span>Enviar inscrição</span><span class="arrow">→</span></button>
      <p class="privacy">As informações serão utilizadas para fins de cadastro e contato do Instituto.</p>
    </form>
  </section>
  <footer>© <?= date('Y') ?> Instituto Amigo Bob · Todos os direitos reservados.</footer>
</main>
<script>
const modalidade = document.getElementById('modalidade');
const instrumentoBox = document.getElementById('instrumentoBox');
const instrumento = document.getElementById('instrumento');
const nascimento = document.getElementById('nascimento');
const responsavelBox = document.getElementById('responsavelBox');
const respFields = ['respNome','respCpf','respTelefone','respParentesco'].map(id => document.getElementById(id));

function atualizarInstrumentos() {
  const option = modalidade.options[modalidade.selectedIndex];
  const isBanda = option && option.text.trim().toLocaleLowerCase('pt-BR').includes('banda');
  instrumentoBox.hidden = !isBanda;
  instrumento.required = isBanda;
  Array.from(instrumento.options).forEach((opt, index) => {
    if (index === 0) { opt.hidden = false; return; }
    opt.hidden = isBanda && opt.dataset.modalidade !== modalidade.value;
  });
  if (!isBanda) instrumento.value = '';
}
function atualizarResponsavel() {
  if (!nascimento.value) {
    responsavelBox.hidden = true;
    respFields.forEach(f => f.required = false);
    return;
  }
  const hoje = new Date();
  const nasc = new Date(nascimento.value + 'T00:00:00');
  let idade = hoje.getFullYear() - nasc.getFullYear();
  const passouAniversario = hoje.getMonth() > nasc.getMonth() || (hoje.getMonth() === nasc.getMonth() && hoje.getDate() >= nasc.getDate());
  if (!passouAniversario) idade--;
  const menor = idade < 18;
  responsavelBox.hidden = !menor;
  respFields.forEach(f => f.required = menor);
}
modalidade.addEventListener('change', atualizarInstrumentos);
nascimento.addEventListener('change', atualizarResponsavel);
nascimento.max = new Date().toISOString().slice(0,10);
atualizarInstrumentos(); atualizarResponsavel();
</script>
</body></html>
