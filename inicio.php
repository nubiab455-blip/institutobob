<?php
$galeria = [
 ["fotos/banda-01.jpg","Nossa banda em ação","Apresentações e momentos especiais."],
 ["fotos/banda-02.jpg","Ensaios","Disciplina, ritmo e trabalho em equipe."],
 ["fotos/banda-03.jpg","Nossos integrantes","Jovens unidos pela música."],
 ["fotos/banda-04.jpg","Apresentações","Levando música e orgulho para a comunidade."],
 ["fotos/banda-05.jpg","Bastidores","A preparação por trás de cada apresentação."],
 ["fotos/banda-06.jpg","Momentos especiais","Memórias que construímos juntos."]
];
?>
<!doctype html>
<html lang="pt-BR"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="description" content="Banda Percussiva do Instituto Bob, Extremoz - RN">
<title>Banda Percussiva | Instituto Bob</title>
<link rel="stylesheet" href="style.css"><script src="script.js" defer></script>
</head><body>
<header class="topo" id="inicio">
<a class="marca" href="#inicio"><img src="fotos/logobanda.png" alt="Logo da Banda Percussiva"><span><b>BANDA PERCUSSIVA</b><small>INSTITUTO BOB · EXTREMOZ - RN</small></span></a>
<button class="menu-toggle" aria-label="Abrir menu" aria-expanded="false">☰</button>
<nav class="navegacao"><a href="#sobre">A banda</a><a href="#instrumentos">Instrumentos</a><a href="#galeria">Galeria</a><a href="#contato">Contato</a></nav>
</header>
<main>
<section class="hero"><div class="hero-conteudo reveal"><p class="etiqueta">MÚSICA · DISCIPLINA · UNIÃO</p><h1>UMA BANDA<br><span>UM SOM.</span></h1><p class="hero-texto">Mais do que tocar, aprendemos juntos, criamos amizades e levamos a energia da nossa banda a cada apresentação.</p><div class="hero-botoes"><a class="botao dourado" href="#galeria">Ver nossas fotos ↗</a><a class="botao contorno" href="#sobre">Conheça a banda</a></div></div><div class="hero-selo"><span>BANDA</span><b>PERCUSSIVA</b><small>INTITUTO BOB · EXTREMOZ</small></div><div class="hero-rodape"><span>BATIDA FORTE.</span><span>PROPÓSITO MAIOR.</span></div></section>
<section class="sobre secao" id="sobre"><div class="sobre-logo reveal"><img src="fotos/logobanda.png" alt="Brasão da Banda Percussiva"></div><div class="sobre-texto reveal"><p class="etiqueta">QUEM SOMOS</p><h2>Uma banda.<br><span>Muitas histórias.</span></h2><p>A Banda Percussiva do Instituto Bob, em Extremoz - RN, é um espaço para desenvolver talento, responsabilidade, respeito e espírito de equipe por meio da música.</p><p>Cada ensaio é uma oportunidade de aprender, superar desafios e construir algo maior, juntos.</p><a class="link-dourado" href="#instrumentos">Conheça nossos instrumentos →</a></div></section>
<section class="instrumentos secao" id="instrumentos"><div class="cabecalho-secao reveal"><p class="etiqueta">NOSSO SOM</p><h2>Cada instrumento<br><span>tem sua voz.</span></h2><p>O conjunto ganha força quando cada integrante encontra seu ritmo.</p></div><div class="instrumentos-grid">
<article class="instrumento reveal"><i>♬</i><h3>Caixa</h3><p>Precisão e cadência para conduzir o ritmo.</p></article><article class="instrumento reveal"><i>♫</i><h3>Tarol</h3><p>Brilho, técnica e personalidade na batida.</p></article><article class="instrumento reveal"><i>◉</i><h3>Repique</h3><p>Chamadas e viradas que dão energia ao conjunto.</p></article><article class="instrumento reveal"><i>◍</i><h3>Bumbo fuzileiro</h3><p>A marcação forte que sustenta a banda.</p></article><article class="instrumento reveal"><i>✦</i><h3>Prato</h3><p>Impacto e brilho nos momentos certos.</p></article><article class="instrumento reveal"><i>◎</i><h3>Mini surdo</h3><p>Graves que completam a identidade sonora.</p></article></div></section>
<section class="galeria secao" id="galeria"><div class="galeria-cabecalho reveal"><div><p class="etiqueta">NOSSO ÁLBUM</p><h2>Momentos que<br><span>merecem ser lembrados.</span></h2></div><p>Coloque fotos dos ensaios, integrantes e apresentações nos espaços abaixo.</p></div><div class="galeria-grid">
<?php foreach($galeria as $foto): ?><figure class="foto-card reveal"><?php if(file_exists(__DIR__.'/'.$foto[0])): ?><img src="<?= htmlspecialchars($foto[0],ENT_QUOTES,'UTF-8') ?>" alt="<?= htmlspecialchars($foto[1],ENT_QUOTES,'UTF-8') ?>" loading="lazy"><?php else: ?><div class="foto-placeholder"><span>＋</span><b>ADICIONE SUA FOTO</b><small><?= htmlspecialchars(basename($foto[0]),ENT_QUOTES,'UTF-8') ?></small></div><?php endif; ?><figcaption><b><?= htmlspecialchars($foto[1],ENT_QUOTES,'UTF-8') ?></b><small><?= htmlspecialchars($foto[2],ENT_QUOTES,'UTF-8') ?></small></figcaption></figure><?php endforeach; ?>
</div><p class="dica">Para preencher a galeria, salve suas imagens na pasta <code>fotos</code> como <code>banda-01.jpg</code> até <code>banda-06.jpg</code>.</p></section>
<section class="chamada" id="contato"><div class="reveal"><p class="etiqueta">FAÇA PARTE DESSA HISTÓRIA</p><h2>O próximo ritmo<br>pode ser <span>o seu.</span></h2><p>Procure o Instituto Bob para saber mais sobre a banda e como participar.</p><a class="botao dourado" href="../index.php">Ir para inscrição ↗</a></div></section>
</main><footer class="rodape"><a href="#inicio">BANDA <span>PERCUSSIVA</span></a><p>Instituto Bob · Extremoz - RN</p><a href="#inicio">Voltar ao topo ↑</a><small>© <?= date('Y') ?> Instituto Bob. Todos os direitos reservados.</small></footer>
</body></html>