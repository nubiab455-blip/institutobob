<?php
require_once __DIR__ . '/config.php';
function voltar($mensagem) { header('Location: index.php?erro=' . rawurlencode($mensagem)); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }

$nome = trim($_POST['nome'] ?? '');
$nascimento = trim($_POST['data_nascimento'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$modalidadeId = filter_input(INPUT_POST, 'modalidade_id', FILTER_VALIDATE_INT);
$instrumentoId = ($_POST['instrumento_id'] ?? '') !== '' ? filter_var($_POST['instrumento_id'], FILTER_VALIDATE_INT) : null;
$autorizacao = isset($_POST['autorizacao']);

if ($nome === '' || mb_strlen($nome) > 150 || !$nascimento || !$modalidadeId || !$autorizacao) voltar('Preencha os campos obrigatórios.');
$data = DateTime::createFromFormat('!Y-m-d', $nascimento);
$hoje = new DateTime('today');
if (!$data || $data > $hoje) voltar('Informe uma data de nascimento válida.');
$idade = $data->diff($hoje)->y;
$respNome = trim($_POST['responsavel_nome'] ?? '');
$respCpf = trim($_POST['responsavel_cpf'] ?? '');
$respTelefone = trim($_POST['responsavel_telefone'] ?? '');
$respParentesco = trim($_POST['responsavel_parentesco'] ?? '');
if ($idade < 18 && (!$respNome || !$respCpf || !$respTelefone || !$respParentesco)) voltar('Para aluno menor de idade, preencha todos os dados do responsável.');

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    $stmt = $pdo->prepare('SELECT id, nome FROM modalidades WHERE id = ?');
    $stmt->execute([$modalidadeId]); $mod = $stmt->fetch();
    if (!$mod) voltar('Selecione uma modalidade válida.');
    $isBanda = stripos($mod['nome'], 'banda') !== false;
    if ($isBanda) {
        if (!$instrumentoId) voltar('Selecione o instrumento da banda.');
        $check = $pdo->prepare('SELECT id FROM instrumentos WHERE id = ? AND modalidade_id = ?');
        $check->execute([$instrumentoId, $modalidadeId]);
        if (!$check->fetch()) voltar('O instrumento escolhido não pertence à Banda Percussiva.');
    } else {
        $instrumentoId = null;
    }
    // Confere se a coluna opcional de instrumento foi criada na tabela alunos.
    $col = $pdo->query("SHOW COLUMNS FROM alunos LIKE 'instrumento_id'")->fetch();
    if (!$col) voltar('Atualize o banco: adicione a coluna instrumento_id à tabela alunos conforme o SQL incluído no pacote.');

    $pdo->exec("CREATE TABLE IF NOT EXISTS responsaveis (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        aluno_id BIGINT UNSIGNED NOT NULL UNIQUE,
        nome VARCHAR(150) NOT NULL,
        cpf VARCHAR(14) NOT NULL,
        telefone VARCHAR(20) NOT NULL,
        parentesco VARCHAR(60) NOT NULL,
        data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_responsavel_aluno FOREIGN KEY (aluno_id)
          REFERENCES alunos(id) ON UPDATE CASCADE ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $sql = 'INSERT INTO alunos (nome, data_nascimento, telefone, endereco, modalidade_id, instrumento_id, data_matricula, status)
            VALUES (:nome, :nascimento, :telefone, :endereco, :modalidade, :instrumento, CURDATE(), "Ativo")';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome, ':nascimento' => $nascimento,
        ':telefone' => $telefone ?: null, ':endereco' => $endereco ?: null,
        ':modalidade' => $modalidadeId, ':instrumento' => $instrumentoId
    ]);
    $alunoId = (int)$pdo->lastInsertId();

    if ($idade < 18) {
        $stmtResp = $pdo->prepare('INSERT INTO responsaveis (aluno_id, nome, cpf, telefone, parentesco)
                                  VALUES (?, ?, ?, ?, ?)');
        $stmtResp->execute([$alunoId, $respNome, $respCpf, $respTelefone, $respParentesco]);
    }
    header('Location: index.php?sucesso=1'); exit;
} catch (Throwable $e) {
    error_log($e->getMessage());
    voltar('Não foi possível salvar. Confira a conexão e a estrutura do banco de dados.');
}
