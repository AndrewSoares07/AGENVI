<?php

    $page = $_REQUEST['page'] ?? '';

    switch($page) {
        case 'advocacia_pesquisar':
            header("Location: advocacia_pesquisar.php");
            break;

        case 'agendamento':
            header("Location: agendamento.php");
            break;

        case 'atualiza_serv':
            header("Location: atualiza_serv.php");
            break;

        case 'atualizar_funcionario':
            header("Location: atualizar_funcionario.php");
            break;

        case 'atualizar_perfil_cliente':
            header("Location: atualizar_perfil_cliente.php");
            break;

        case 'atualizar_perfil':
            header("Location: atualizar_perfil.php");
            break;

        case 'atualizar_perfil':
            header("Location: atualizar_perfil.php");
            break;

        case 'barbearia_pesquisa':
            header("Location: barbearia_pesquisa.php");
            break;

        case 'cadastrar_func':
            header("Location: cadastrar_func.php");
            break;

        case 'cadastrar_servicos':
            header("Location: cadastrar_servicos.php");
            break;

        case 'cadastro-cliente':
            header("Location: cadastro-cliente.php");
            break;

        case 'cadastro-empresa':
            header("Location: cadastro-empresa.php");
            break;

        case 'clinica_pesquisa':
            header("Location: clinica_pesquisa.php");
            break;

        case 'clinica_pesquisa':
            header("Location: clinica_pesquisa.php");
            break;

        case 'conecte-se':
            header("Location: conecte-se.php");
            break;

        case 'edit_func':
            header("Location: edit_func.php");
            break;

        case 'edit_servico':
            header("Location: edit_servico.php");
            break;

        case 'editar_perfil_cliente':
            header("Location: editar_perfil_cliente.php");
            break;

        case 'editar_perfil':
            header("Location: editar_perfil.php");
            break;

        case 'excluir_func':
            header("Location: excluir_func.php");
            break;

        case 'excluir_servico':
            header("Location: excluir_servico.php");
            break;

        case 'favoritar':
            header("Location: favoritar.php");
            break;

        case 'favoritos':
            header("Location: favoritos.php");
            break;

        case 'favoritos':
            header("Location: favoritos.php");
            break;

        case 'horario_empresa':
            header("Location: horario_empresa.php");
            break;

        case 'horario_func':
            header("Location: horario_func.php");
            break;

        case 'lista_func':
            header("Location: lista_func.php");
            break;

        case 'lista_servicos':
            header("Location: lista_servicos.php");
            break;

        case 'login-cliente':
            header("Location: login-cliente.php");
            break;

        case 'login-empresa':
            header("Location: login-empresa.php");
            break;

        case 'logout':
            header("Location: logout.php");
            break;

        case 'negocios':
            header("Location: negocios.php");
            break;

        case 'perfil-cliente':
            header("Location: perfil-cliente.php");
            break;

        case 'perfil-empresa':
            header("Location: perfil-empresa.php");
            break;

        case 'planos':
            header("Location: planos.php");
            break;

        case 'principal-cliente':
            header("Location: principal-cliente.php");
            break;

        case 'protect':
            header("Location: protect.php");
            break;

        case 'salao_pesquisa':
            header("Location: salao_pesquisa.php");
            break;

        case 'salvar_func':
            header("Location: salvar_func.php");
            break;

        case 'salvar_horario':
            header("Location: salvar_horario.php");
            break;

        case 'salvar_horarios_empresa':
            header("Location: salvar_horarios_empresa.php");
            break;

        case 'sobre-nos':
            header("Location: sobre-nos.php");
            break;

        case 'tela-principal':
            header("Location: tela-principal.php");
            break;
            
        case 'teste':
            header("Location: teste.php");
            break;
            
        case 'teste2':
            header("Location: teste2.php");
            break;
            
        default:
            header("Location: home.php");
            break;
    }

?>