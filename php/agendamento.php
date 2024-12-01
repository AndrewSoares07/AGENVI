<?php
$idempresa = $_GET['id'];

include('includes/logar-sistema.php');
include('protect_cliente.php');



$idcliente = $_SESSION['idcliente'];



if (!$mysqli) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}



$sql = "SELECT 
e.nome,
e.nome_fantasia,
e.tipo,
e.CNPJ_CPF,
e.telefone,
e.email,
e.numero,
l.cep,
l.logradouro,
l.bairro,
l.UF,
l.cidade,
e.foto_empresa,
e.foto_amb1,
e.foto_amb2,
e.foto_amb3,
e.foto_amb4,
e.foto_amb5,
e.foto_amb6,
e.complemento,
p.port_img1,
p.port_img2,
p.port_img3,
p.port_img4,
p.port_img5,
p.port_img6,
p.port_img7,
p.port_img8,
p.port_img9,
p.port_img10,
p.port_img11,
p.port_img12
FROM empresa e
inner join localidade l on l.cep = e.cep
right join portifolio_empresa p on p.idempresa = e.idempresa
WHERE e.idempresa = $idempresa";

$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $empresa = $result->fetch_assoc();

    $bairro = $empresa['bairro'];
    $uf = $empresa['UF'];
    $cidade = $empresa['cidade'];
    $logradouro = $empresa['logradouro'];
    $numero = $empresa['numero'];
    $cep = $empresa['cep'];
    $nome = $empresa['nome'];
    $email = $empresa['email'];
    $nomef = $empresa['nome_fantasia'];
    $tel = $empresa['telefone'];
    $img1 = $empresa['foto_empresa'];
    $ft1 = $empresa['foto_amb1'];
    $ft2 = $empresa['foto_amb2'];
    $ft3 = $empresa['foto_amb3'];
    $ft4 = $empresa['foto_amb4'];
    $ft5 = $empresa['foto_amb5'];
    $ft6 = $empresa['foto_amb6'];
    $comp = $empresa['complemento'];
    $pot1 = $empresa['port_img1'];
    $pot2 = $empresa['port_img2'];
    $pot3 = $empresa['port_img3'];
    $pot4 = $empresa['port_img4'];
    $pot5 = $empresa['port_img5'];
    $pot6 = $empresa['port_img6'];
    $pot7 = $empresa['port_img7'];
    $pot8 = $empresa['port_img8'];
    $pot9 = $empresa['port_img9'];
    $pot10 = $empresa['port_img10'];
    $pot11 = $empresa['port_img11'];
    $pot12 = $empresa['port_img12'];


    $endereco_completo = "{$logradouro}, {$numero}, {$bairro}, {$cidade}, {$uf}";
    $endereco_url = urlencode($endereco_completo);


} else {
    echo "Nenhum resultado encontrado para o ID da empresa.";
}





?>




<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <title>Agenvi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <link rel="stylesheet" href="../css/teste.css">
    <link rel="stylesheet" href="../css/agendamento.css">




    <style>
        .modal {
            display: none;
            /* Escondido por padrão */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #000;
            font-weight: 400;
            font-size: 20px;
            float: right;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
        }

        .estrelas {
            margin-left: 20px;
            font-size: 30px;
            cursor: pointer;
        }

        textarea {
            width: 95%;
            padding: 10px;
            height: 80px;
            margin-left: 20px;
            border: 1px solid #E9E3FF;
            border-radius: 10px;
        }

        .infosavacli {
            display: flex;
            align-items: center;
        }

        .txtava2 {
            margin-top: -15px;
            font-size: 14px;
        }

        .tituloava {
            display: flex;
            width: 500px;
            margin-bottom: -10px;
            justify-content: space-between;
        }
    </style>



</head>


<body>
    <?php include("includes/header-nav.php"); ?>

    <div class="divparte1">

        <?php include('includes/avaliacao.php'); ?>

        <div class="dadosemp">
            <div class="num_aval2">
                <h3>
                    <?php echo number_format($media_estrelas, 1); ?>
                </h3>
                <p>
                    <?php echo $total_avaliacoes ?> avaliações
                </p>
            </div>

            <img class="baneremp" src="arquivos/<?php echo $ft1 ?>" alt="">
            <img class="logoemp" src="arquivos/<?php echo $img1 ?>" alt="">
            <div>
                <h4 class="nomep">
                    <?php echo "$nomef"; ?>
                </h4>
                <p class="comp">
                    <?php echo "$comp" ?>
                </p>
            </div>
        </div>


        <div class="mapa">
            <?php echo '<iframe width="500" height="400"  frameborder="0" style=" border-radius: 15px;
             border: 1px solid black;"  src="https://maps.google.com/maps?q=' . $endereco_url . '&z=16&output=embed" allowfullscreen></iframe>'; ?>
            <div class="localizainfo">

                <img class="logolocaliza" src="arquivos/<?php echo $img1 ?>" alt="">

                <div>
                    <h4 class="nomelocaliza">
                        <?php echo "$nomef"; ?>
                    </h4>
                    <p class="locap">
                        <?php echo "  $logradouro, $numero-$bairro, $uf <i class='bx bxs-map' style='color:#6023b0'  ></i>" ?>
                    </p>
                </div>

            </div>

        </div>


    </div>

    <div class="divparte2">

        <div class="serv">
            <h2>Serviços</h2>
            <br><br>
            <?php
            $lista_serv = "SELECT
                            s.nome_serv,
                            s.preco_serv
                            FROM empresa e
                            INNER JOIN lista_servicos_empresa lse ON lse.idempresa = e.idempresa
                            INNER JOIN servicos s ON s.idservico = lse.idservico
                            WHERE e.idempresa = ?";
            $sss = $mysqli->prepare($lista_serv);
            $sss->bind_param("i", $idempresa);
            $sss->execute();
            $resulta = $sss->get_result();

            $serviceCount = 0;
            echo "<div id='service-list'>";
            while ($row = $resulta->fetch_assoc()) {
                $serviceCount++;
                $hiddenClass = $serviceCount > 5 ? 'hidden' : '';
                echo "<div class='preco $hiddenClass'>{$row['nome_serv']}";
                echo "<strong>R$ {$row['preco_serv']}</strong></div><br>";
            }
            echo "</div>";

            if ($serviceCount > 5) {
                echo "<p id='read-more'>Ver mais serviços<i class='bx bx-chevron-down'></i></p>";
            }
            ?>

            <style>
                .hidden {
                    display: none;
                }
            </style>

            <script>
                document.getElementById("read-more").addEventListener("click", function () {
                    const hiddenServices = document.querySelectorAll(".preco.hidden");
                    hiddenServices.forEach(function (service) {
                        service.classList.remove("hidden");
                    });
                    this.style.display = "none";

                    // Move the form below the service list after expanding
                    const serviceList = document.getElementById('service-list');
                    const form = document.getElementById('schedule-form');
                    serviceList.appendChild(form);
                });
            </script>

            <!-- Form HTML (with ID to easily move it) -->
            <br><br>
            <form id="schedule-form" onsubmit="openModal(event)">
                <input class="agendar" type="submit" value="Agendar">
            </form>
            <br>

            <div id="empresa-info" data-idempresa="<?php echo $idempresa; ?>"></div>

            <!-- Modal -->
            <div id="myModal" class="modal">
                <div class="modal1">
                    <div class="background_modal">
                        <span class="close" onclick="closeModal()"><i class='bx bx-x'></i></span>
                        <div class="text-center">
                            <!-- Controles de Mês -->
                            <button id="btnVoltarMes" class="mesatuallbt" onclick="mudarMes(-1)"><i
                                    class="bx bx-chevron-left"></i></button>
                            <span id="mesAtual" class="mesatuall"></span>
                            <button id="btnAvancarMes" class="mesatuallbt" onclick="mudarMes(1)"><i
                                    class="bx bx-chevron-right"></i></button>
                            <br>
                            <div class="sep"></div>

                            <!-- Controles de Semana -->
                            <div class="but_d">
                                <button id="btnAnteriorSemana" class="ProSem" onclick="avancaSemana(-1)"><i
                                        class='bx bx-chevron-left'></i></button>
                                <div class="dias" id="daysContainer">
                                    <!-- Dias da semana (preenchidos dinamicamente) -->
                                </div>
                                <button id="btnProximaSemana" class="ProSem" onclick="avancaSemana(1)"><i
                                        class='bx bx-chevron-right'></i></button>
                            </div>
                        </div>
                        <br><br>
                        <div class="espacao">
                            <button type="button" class="turno" onclick="setPeriodo('manha')">Manhã</button>
                            <button type="button" class="turno" onclick="setPeriodo('tarde')">Tarde</button>
                            <button type="button" class="turno" onclick="setPeriodo('noite')">Noite</button>
                        </div>
                    </div>
                    <br><br>

                    <!-- Service details -->
                    <div id="carouselServicos" class="carousel slide" data-bs-ride="false" data-bs-interval="300">
                        <div class="carousel-inner">
                            <?php
                            $lista_serv = "SELECT
                                        s.idservico,
                                        s.nome_serv,
                                        s.preco_serv,
                                        s.duracao_serv
                                        FROM empresa e
                                        INNER JOIN lista_servicos_empresa lse ON lse.idempresa = e.idempresa
                                        INNER JOIN servicos s ON s.idservico = lse.idservico
                                        WHERE e.idempresa = ?";

                            $sss = $mysqli->prepare($lista_serv);
                            if ($sss === false) {
                                die('Falha ao preparar a consulta: ' . htmlspecialchars($mysqli->error));
                            }
                            $sss->bind_param("i", $idempresa);
                            $sss->execute();
                            $resulta = $sss->get_result();

                            if ($resulta->num_rows > 0) {
                                $count = 0;
                                $activeClass = "active";
                                echo "<div class='carousel-item $activeClass'><div class='d-flex flex-wrap justify-content-center'>";
                                while ($row = $resulta->fetch_assoc()) {
                                    echo "<button class='card-button' data-id='" . htmlspecialchars($row['idservico']) . "' onclick='selecionarServico(" . htmlspecialchars($row['idservico']) . ")'>";
                                    echo "<div class='ladob'><p class='ladobS'>" . htmlspecialchars($row['nome_serv']) . "</p>";
                                    echo "<p class='ladobS'>R$ " . htmlspecialchars($row['preco_serv']) . "</p></div>";
                                    $duracao = $row['duracao_serv'];
                                    list($horas, $minutos, $segundos) = explode(':', $duracao);
                                    $total_minutos = ($horas * 60) + $minutos;
                                    if ($total_minutos >= 60) {
                                        $horas = floor($total_minutos / 60);
                                        $minutos = $total_minutos % 60;
                                        if ($horas > 0)
                                            echo $horas . "h";
                                        if ($minutos > 0)
                                            echo " " . $minutos . "min";
                                    } else {
                                        echo $total_minutos . "min";
                                    }
                                    echo "</button>";

                                    $count++;
                                    if ($count % 3 == 0 && $count < $resulta->num_rows) {
                                        echo "</div></div><div class='carousel-item'><div class='d-flex flex-wrap justify-content-center'>";
                                    }
                                }
                                echo "</div></div>";
                            } else {
                                echo "<div class='carousel-item active'><p>Nenhum serviço encontrado para esta empresa.</p></div>";
                            }
                            ?>
                        </div>

                        <div class="carousel-indicators">
                            <?php
                            for ($i = 0; $i < ceil($resulta->num_rows / 3); $i++) {
                                echo "<button type='button' data-bs-target='#carouselServicos' data-bs-slide-to='$i' class='" . ($i == 0 ? "active" : "") . "' aria-label='Slide " . ($i + 1) . "' style='background-color: #6023B0;'></button>";
                            }
                            ?>
                        </div>
                    </div>

                    <hr>
                    <div>
                    </div>
                    <div id="horarios-container"></div>
                    <div id="container-funcionarios"></div>
                    <div class="modal-f">
                        <button type="button" class="button" onclick="agendar()">Agendar</button>
                    </div>
                </div>



                <hr>
            </div>



        </div>

        

        <div id="customModal" class="modal">
            <div class="modal-content">
            <h3 class="h3_mod">Agendamento realizado com sucesso!</h3>
            <p>Você pode visualizar seu agendamento em “agendamentos”</p>
            <button class="button" onclick="closeCustomModal()">Voltar</button>
            </div>
        </div>


        <div class="div2menor">

            <div class="tabelafunc">
                <h5 class="tit">Membros da equipe</h5><br><br>
                <div class="func-carousel">
                    <?php
                    $func_sql = " SELECT
                                f.nome_func,
                                f.foto_func
                                FROM lista_funcionario_empresa lf
                                inner JOIN funcionario f ON lf.idfuncionario = f.idfuncionario
                                INNER JOIN empresa e ON e.idempresa = lf.idempresa
                                WHERE e.idempresa = $idempresa; ";
                    $result_func = $mysqli->prepare($func_sql);
                    $result_func->execute();
                    $func_list = $result_func->get_result();
                    while ($func__list = $func_list->fetch_assoc()) {
                        ?>
                        <div class="funcionarios">
                            <img class="fotofunc img-fluid" src="arquivos/<?php echo $func__list['foto_func'] ?>" alt="">
                            <p><strong>
                                    <?php echo $func__list['nome_func'] ?>
                                </strong></p>
                        </div>
                    <?php } ?>
                </div>
            </div>


            <div class="infohora">


                <p><strong>Contato e horário de funcionamento</strong></p>

                <div class="flex-hora">
                    <?php
                    $horarios_sql = "SELECT he.dias_semana, he.horario_ini, he.horario_fim
                    FROM horarios_empresa he
                    INNER JOIN empresa e ON e.idempresa = he.idempresa
                    WHERE e.idempresa = ?";
                    $result_hora = $mysqli->prepare($horarios_sql);
                    $result_hora->bind_param('i', $idempresa);
                    $result_hora->execute();
                    $horario_result = $result_hora->get_result();

                    while ($horariofun = $horario_result->fetch_assoc()) {
                        $horario_ini = substr($horariofun['horario_ini'], 0, 5); // Obtém apenas HH:MM
                        $horario_fim = substr($horariofun['horario_fim'], 0, 5); // Obtém apenas HH:MM
                        ?>

                        <div class="diassem">
                            <p>
                                <?php echo htmlspecialchars($horariofun['dias_semana']); ?>
                            </p>
                            <p>
                                <?php echo htmlspecialchars($horario_ini); ?> às
                                <?php echo htmlspecialchars($horario_fim); ?>
                            </p>
                        </div>
                        <?php
                    }
                    ?>

                </div>
                <p><i class='bx bxs-phone'></i> <?php echo "$tel" ?>
                </p>
            </div>


        </div>
    </div>

    <div class="divparte3">
        <div class="div3menor">

            <div class="avaliar">
                <h2>Classificações e resenhas</h2><br>
                <?php include('includes/avaliacao.php'); ?>

                <div class="total_aval">
                    <div class="bodyava">
                        <div class="num_aval">
                            <h3>
                                <?php echo number_format($media_estrelas, 1); ?>
                            </h3>
                            <div class="star-rating">
                                <?php
                                $fullStars = floor($media_estrelas);
                                $halfStar = ($media_estrelas - $fullStars) >= 0.5;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

                                // Exibir estrelas cheias
                                for ($i = 0; $i < $fullStars; $i++) {
                                    echo '<i class="fas fa-star"></i>';
                                }
                                if ($halfStar) {
                                    echo '<i class="fas fa-star-half-alt"></i>';
                                }
                                for ($i = 0; $i < $emptyStars; $i++) {
                                    echo '<i class="far fa-star"></i>';
                                }
                                ?>
                            </div>
                            <p>
                                <?php echo $total_avaliacoes ?> avaliações
                            </p>
                        </div>
                        <div class="bar-container">
                            <?php
                            foreach ($data as $label => $value): ?>
                                <div class="bar-row">
                                    <div class="star-number">
                                        <?php echo $label; ?>
                                    </div>
                                    <div class="bar">
                                        <div class="bar-fill" style="width: <?php echo $value; ?>%;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <br><br>
                    <button class="buttoncoment" onclick="toggleModal()">Avaliar</button>
                </div>

                <!-- Modal para avaliação -->
                <div id="commentModal" class="modal">
                    <div class="modal2">
                        <?php include("includes/informacao_cliente.php") ?>
                        <span onclick="toggleModal()" class="close"><i class='bx bx-window-close'></i></span>
                        <h2>Deixe seu Comentário</h2><br>

                        <div class="infosavacli">
                            <img class="perfiloffcanv" src="arquivos/<?php echo $img_perfil; ?>" alt="">
                            <div><br>
                                <p>
                                    <?php echo " $nome_cliente"; ?>
                                </p>
                                <p class="txtava2">As avaliações são públicas e incluem informações sobre você.</p>
                            </div>
                        </div>

                        <form id="formComentario" method="POST" action="saveava.php">
                            <input type="hidden" name="empresaid" value="<?= $idempresa ?>">
                            <div class="estrelas" name="estrela">
                                <span class="estrela" onclick="selectStar(1)">★</span>
                                <span class="estrela" onclick="selectStar(2)">★</span>
                                <span class="estrela" onclick="selectStar(3)">★</span>
                                <span class="estrela" onclick="selectStar(4)">★</span>
                                <span class="estrela" onclick="selectStar(5)">★</span>
                            </div>
                            <input type="hidden" name="rating" id="rating" value="0"><br>
                            <textarea id="comentario" name="comentario" placeholder="Digite seu comentário..."
                                required></textarea>
                            <div class="divbutava">
                                <button class="buttonava" type="button" onclick="submitForm()">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    function toggleModal() {
                        const modal = document.getElementById('commentModal');
                        modal.style.display = (modal.style.display === 'block') ? 'none' : 'block';
                    }

                    let selectedRating = 0;

                    function selectStar(value) {
                        selectedRating = value;
                        const estrelas = document.querySelectorAll('.estrela');
                        estrelas.forEach((estrela, index) => {
                            estrela.classList.toggle('selected', index < value);
                        });
                    }

                    function submitForm() {
                        const comentario = document.getElementById('comentario').value;
                        if (comentario === '' || selectedRating === 0) {
                            alert('Por favor, preencha o comentário e selecione uma avaliação.');
                            return;
                        }
                        document.getElementById('rating').value = selectedRating; // Armazena a avaliação no campo oculto
                        document.getElementById('formComentario').submit();
                    }

                    window.onclick = function (event) {
                        const modal = document.getElementById('commentModal');
                        if (event.target == modal) {
                            toggleModal();
                        }
                    }

                    function toggleUniqueModal() {
                        const modal = document.getElementById('uniqueCommentModal');
                        modal.style.display = (modal.style.display === 'block') ? 'none' : 'block';
                    }
                </script>

                <!-- Definindo a localidade para exibição de datas em português -->
                <?php setlocale(LC_TIME, 'pt_BR.UTF-8', 'pt_BR', 'Portuguese_Brazil.1252'); ?>
            </div>

            <form method="GET" action="">

                <input type="hidden" name="id" value="<?php echo htmlspecialchars($idempresa); ?>">
                <select class="ordembb" name="ordenar_por" onchange="this.form.submit()">
                    <option value="curtidas" <?php if (isset($_GET['ordenar_por']) && $_GET['ordenar_por'] == 'curtidas')
                        echo 'selected'; ?>>Mais Curtidas</option>
                    <option value="data" <?php if (isset($_GET['ordenar_por']) && $_GET['ordenar_por'] == 'data')
                        echo 'selected'; ?>>Mais Recentes</option>
                    <option value="estrelas" <?php if (isset($_GET['ordenar_por']) && $_GET['ordenar_por'] == 'estrelas')
                        echo 'selected'; ?>>Melhores Avaliações</option>
                </select>
            </form> <br><br>

            <div class="Avaliacao_client">
                <?php
                // Pega a opção selecionada no dropdown
                $ordenar_por = isset($_GET['ordenar_por']) ? $_GET['ordenar_por'] : 'curtidas'; // Default é 'curtidas'
                
                // Definir a cláusula ORDER BY com base na escolha
                switch ($ordenar_por) {
                    case 'data':
                        $orderBy = 'a.data_coment DESC';
                        break;
                    case 'estrelas':
                        $orderBy = 'a.estrelas DESC';
                        break;
                    case 'curtidas':
                    default:
                        $orderBy = 'total_curtidas DESC';
                        break;
                }

                $avaliacao = "SELECT c.nome_cliente, c.foto_perfil, a.chat, a.estrelas, a.data_coment, a.idavaliacao, a.idcliente, a.idempresa,
                                (SELECT COUNT(*) FROM curtidas WHERE idavaliacao = a.idavaliacao) AS total_curtidas,
                                (SELECT COUNT(*) FROM curtidas WHERE idavaliacao = a.idavaliacao AND idcliente = ?) AS curtido
                                FROM cliente c
                            INNER JOIN avaliacao a ON c.idcliente = a.idcliente
                            INNER JOIN empresa e ON e.idempresa = a.idempresa
                            WHERE e.idempresa = ? ORDER BY $orderBy"; // Usa a variável para ordenar
                
                $aaa = $mysqli->prepare($avaliacao);
                $aaa->bind_param("ii", $idcliente, $idempresa);
                $aaa->execute();
                $resulta = $aaa->get_result();
                while ($row = $resulta->fetch_assoc()) {
                    $data_comentario = new DateTime($row['data_coment']);
                    $data_formatada = strftime('%d de %B de %Y', $data_comentario->getTimestamp());
                    ?>
                    <div class="caixa1_aval">
                        <div class="filho_aval">
                            <img class="ftperfilava" src="arquivos/<?php echo htmlspecialchars($row['foto_perfil']) ?>"
                                alt="Foto de perfil">
                            <div>
                                <p class="tituloava">

                                </p><br>
                                <div class="star-rating">
                                    <?php
                                    $fullStars = floor($row['estrelas']); // Número de estrelas cheias
                                    $halfStar = ($row['estrelas'] - $fullStars) >= 0.5; // Verifica se há meia estrela
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0); // Estrelas vazias
                                
                                    // Exibe as estrelas cheias
                                    for ($i = 0; $i < $fullStars; $i++) {
                                        echo '<i class="fas fa-star"></i>';
                                    }
                                    // Exibe a meia estrela, se houver
                                    if ($halfStar) {
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    }
                                    // Exibe as estrelas vazias
                                    for ($i = 0; $i < $emptyStars; $i++) {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="menu-opcoes">
                                <?php 
                                    $date = new DateTime($row['data_coment']); 
                                    echo $date->format('d/m/Y'); 
                                ?>
                                    
                                
                            

                            <button class="menu-btn" onclick="toggleMenu(this)">
                                <i class="fas fa-ellipsis-v"></i> <!-- Ícone dos três pontos -->
                            </button>
                            <!-- Menu dropdown -->
                            <div class="menu-opcoes-dropdown" style="display: none; z-index: unset;">
                                <ul>
                                    <li><a href="#">Reportar</a></li>
                                    <?php if ($idcliente == $row['idcliente']): ?>
                                        <li> <a
                                                href="excluiava.php?idavaliacao=<?php echo $row['idavaliacao']; ?>&idempresa=<?php echo $row['idempresa'] ?>">excluir</a>
                                        </li>
                                        <li> <a href="#">Editar</a></li>
                                    <?php endif; ?>

                                </ul>
                            </div>
                        </div>


                        <p class="chat">
                            <?php echo htmlspecialchars($row['chat']) ?>

                        </p>
                        <div class="curtidas_cont">
                            <span class="num-curtidas" id="curtidas-<?php echo $row['idavaliacao']; ?>">
                                <?= $row['total_curtidas'] ?>
                            </span>
                            <button class="cutido-button" data-avaliacao="<?= $row['idavaliacao'] ?>"
                                onclick="curtir(this)">
                                <img src="../img/<?= $row['curtido'] ? 'heath.png' : 'coracaop.png' ?>"
                                    class="heart-icon" />
                            </button>
                        </div>
                    </div>

                <?php } ?>
            </div>
        </div>

        <script>
            // Função para alternar o menu (abrir/fechar)
            function toggleMenu(button) {
                const menu = button.nextElementSibling; // O próximo elemento é o menu
                menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            }

            // Função para fechar o menu se clicar fora dele
            document.addEventListener('click', function (event) {
                const menu = document.querySelector('.menu-opcoes-dropdown');
                const menuBtn = document.querySelector('.menu-btn');

                // Verifica se o clique foi fora do menu e do botão
                if (!menu.contains(event.target) && !menuBtn.contains(event.target)) {
                    menu.style.display = 'none'; // Fecha o menu
                }
            });
        </script>

        <style>
            /* Estilo da caixa de avaliação */
            .caixa1_aval {
                position: relative;
                /* Necessário para posicionar o menu no canto superior direito */
                padding: 10px;
                border: 1px solid #ccc;
                margin-bottom: 15px;
                border-radius: 8px;
                background-color: #f9f9f9;
            }

            /* Estilo do botão de três pontos */
            .menu-opcoes {
                position: absolute;
                top: 15px;
                width: 120px;
                align-items: center;
                display: flex;
                justify-content: space-between;
                
                right: 50px;
              
            }

            .menu-btn {
                background: none;
                border: none;
                
                font-size: 20px;
                cursor: pointer;
                color: #666;
            }

            .menu-btn:hover {
                color: #333;
            }

            /* Estilo do ícone de três pontos */
            .menu-btn i {
                font-size: 18px;
            }

            /* Estilo do menu dropdown */
            .menu-opcoes-dropdown {
                position: absolute;
                top: 30px;
                
                right: 0;
                background-color: white;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                padding: 10px;
                width: 170px;
                display: none;
                /* Inicialmente oculto */
                display: flex;
                flex-direction: column;
                align-items: center;
                /* Centraliza o conteúdo verticalmente */
                justify-content: center;
                /* Centraliza o conteúdo horizontalmente */
            }

            /* Estilo para os itens do menu */
            .menu-opcoes-dropdown ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .menu-opcoes-dropdown ul li {
                padding: 8px 0;
            }

            .menu-opcoes-dropdown ul li a {
                text-decoration: none;
                color: #333;
                display: block;
            }

            .menu-opcoes-dropdown ul li a:hover {
                background-color: #f0f0f0;
            }
        </style>
        <div class="imagens">
            <h2>Confira nossos resultados</h2>
            <br>
            <br>
            <div class="fotos">
                <div class="fotos_div">
                    <img class="result" src="arquivos/<?php echo $ft1 ?>" alt="">
                    <img class="result" src="arquivos/<?php echo $ft2 ?>" alt="">
                </div>
                <div class="fotos_div">
                    <img class="result" src="arquivos/<?php echo $ft3 ?>" alt="">
                    <img class="result" src="arquivos/<?php echo $ft4 ?>" alt="">
                </div>
                <div class="fotos_div">
                    <img class="result" src="arquivos/<?php echo $ft5 ?>" alt="">

                    <div class="visuimg">
                        <img class="result2" src="arquivos/<?php echo $ft6 ?>" alt="">
                        <a id="result_but" href="vizualizar_imagens_cliente.php?idempresa=<?php echo $idempresa ?>">Ver
                            mais fotos <i class='bx bx-right-arrow-alt'></i></a>
                    </div>

                </div>

            </div>
        </div>

    </div>











    <?php
    include('includes/footer.php');
    ?>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>






<?php
$mysqli->close();
?>
<?php
// Conectar ao banco de dados
$pdo = new PDO('mysql:host=localhost;dbname=agenvi', 'root', '');

$stmt = $pdo->prepare("SELECT dias_semana FROM horarios_empresa WHERE idempresa = ?");
$stmt->execute([$idempresa]);

// Recuperar todos os dias da semana como um array
$diasSemanaArray = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

// Codificar o array em JSON e passar para o JavaScript
echo "<script>
    const diasTrabalhados = " . json_encode($diasSemanaArray, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ";
</script>";
?>

<?php include('java_agend.php') ?>