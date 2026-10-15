<?php
// Página inicial do Instituto Amigo Bob.
// O botão de inscrição abre o formulário existente em index.php.
$inscricaoUrl = 'index.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Instituto Amigo Bob: educação, cultura, esporte, cidadania e oportunidades para crianças e jovens.">
    <title>Instituto Amigo Bob | Início</title>
    <link rel="stylesheet" href="style_inicio.css">
</head>
<body>
<header class="topo" id="inicio">
    <div class="nav-wrap">
        <a class="marca" href="#inicio" aria-label="Instituto Amigo Bob - início">
            <img src="assets/logo.png" alt="Logomarca do Instituto Amigo Bob">
            <span>INSTITUTO <strong>AMIGO BOB</strong></span>
        </a>
        <button class="menu-toggle" aria-label="Abrir menu" aria-expanded="false" aria-controls="menu">☰</button>
        <nav class="menu" id="menu">
            <a class="ativo" href="#inicio">Início</a>
            <a href="#sobre">Sobre o Instituto</a>
            <a href="#banda">Banda Marcial</a>
            <a href="#bombeiro">Bombeiro Mirim</a>
            <a class="btn btn-menu" href="../index.php">Faça sua inscrição <span>↗</span></a>
        </nav>
    </div>
</header>

<main>
    <section class="hero">
        <span class="pincel pincel-amarelo"></span><span class="pincel pincel-verde"></span>
        <div class="hero-inner">
            <div class="hero-texto">
                <p class="sobretitulo"><span></span> INSTITUTO AMIGO BOB</p>
                <h1>Educação, disciplina e <em>oportunidades</em> para transformar vidas.</h1>
                <p class="resumo">Um espaço de aprendizado, cidadania, cultura e desenvolvimento para crianças e jovens.</p>
                <div class="hero-acoes">
                    <a class="btn btn-vermelho" href="../index.php">✦ Quero me inscrever</a>
                    <a class="btn btn-contorno" href="#sobre">Conheça o Instituto <span>→</span></a>
                </div>
                <div class="mini-destaques">
                    <span><b>✓</b> Educação</span>
                    <span><b>✓</b> Cidadania</span>
                    <span><b>✓</b> Oportunidades</span>
                </div>
            </div>
            <div class="hero-arte">
                <div class="circulo-arte"></div>
                <div class="logo-card"><img src="assets/logo.png" alt="Brasão do Instituto Amigo Bob"></div>
                <div class="frase-lateral">Juntos por um<br><strong>futuro melhor!</strong><i></i></div>
                <span class="estrela estrela-1">✦</span><span class="estrela estrela-2">✦</span>
            </div>
        </div>
    </section>

    <section class="projetos secao" id="sobre">
        <div class="cabecalho-secao">
            <p class="sobretitulo">CONHEÇA NOSSO TRABALHO</p>
            <h2>Projetos que fazem a diferença</h2>
            <p>Mais do que atividades, criamos caminhos para um futuro com mais possibilidades.</p>
            <div class="traco-colorido"><i></i><i></i><i></i></div>
        </div>

        <div class="cards">
            <article class="card card-sobre">
                <div class="card-topo topo-sobre"><span class="card-icone">♡</span><span class="numero">01</span></div>
                <div class="card-conteudo">
                    <span class="etiqueta">NOSSA MISSÃO</span>
                    <h3>Sobre o Instituto</h3>
                    <p>Promovemos desenvolvimento, convivência e cidadania por meio de iniciativas que valorizam cada criança e jovem.</p>
                    <a href="#valores" class="link-card">Conheça nossa missão <span>→</span></a>
                </div>
            </article>

            <article class="card card-banda" id="banda">
                <div class="card-topo topo-banda"><span class="card-icone">♫</span><span class="numero">02</span></div>
                <div class="card-conteudo">
                    <span class="etiqueta">CULTURA E DISCIPLINA</span>
                    <h3>Banda Marcial</h3>
                    <p>Ritmo, compromisso e trabalho em equipe. A banda incentiva a expressão musical, a responsabilidade e o espírito coletivo.</p>
                    <a href="../index.php" class="link-card">Inscrever-se na banda <span>→</span></a>
                </div>
            </article>

            <article class="card card-bombeiro" id="bombeiro">
                <div class="card-topo topo-bombeiro"><span class="card-icone">✚</span><span class="numero">03</span></div>
                <div class="card-conteudo">
                    <span class="etiqueta">CIDADANIA E PREVENÇÃO</span>
                    <h3>Bombeiro Mirim</h3>
                    <p>Aprendizado sobre responsabilidade, prevenção, respeito, cooperação e atitudes que ajudam a construir uma comunidade melhor.</p>
                    <a href="../index.php" class="link-card">Saiba como participar <span>→</span></a>
                </div>
            </article>
        </div>
    </section>

    <section class="valores" id="valores">
        <div class="valor"><span class="valor-icone amarelo">▤</span><div><h3>Educação</h3><p>Conhecimento que abre caminhos</p></div></div>
        <div class="valor"><span class="valor-icone verde">➤</span><div><h3>Cultura e esporte</h3><p>Talentos, saúde e movimento</p></div></div>
        <div class="valor"><span class="valor-icone vermelho">♥</span><div><h3>Cidadania</h3><p>Respeito e valores para a vida</p></div></div>
        <div class="valor"><span class="valor-icone azul">✧</span><div><h3>Formação</h3><p>Sonhos que viram realidade</p></div></div>
    </section>

    <section class="chamada">
        <div>
            <p class="sobretitulo">SEU PRÓXIMO PASSO COMEÇA AQUI</p>
            <h2>Faça parte da família Amigo Bob!</h2>
            <p>Escolha sua atividade e venha crescer com a gente.</p>
        </div>
        <a class="btn btn-vermelho" href="../index.php">Fazer inscrição <span>→</span></a>
    </section>
</main>

<footer class="rodape">
    <a class="marca marca-rodape" href="#inicio"><img src="assets/logo.png" alt=""><span>INSTITUTO <strong>AMIGO BOB</strong></span></a>
    <p>Educação, cultura, esporte e cidadania.</p>
    <a href="#inicio" class="voltar-topo">Voltar ao topo ↑</a>
    <small>© <?php echo date('Y'); ?> Instituto Amigo Bob. Todos os direitos reservados.</small>
</footer>
<script>
const toggle = document.querySelector('.menu-toggle');
const menu = document.querySelector('.menu');
toggle.addEventListener('click', () => {
    const aberto = menu.classList.toggle('aberto');
    toggle.setAttribute('aria-expanded', aberto ? 'true' : 'false');
});
menu.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
    menu.classList.remove('aberto');
    toggle.setAttribute('aria-expanded', 'false');
}));
</script>
</body>
</html>
