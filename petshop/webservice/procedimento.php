<?php
require_once __DIR__ . "/../model/checaLogin.php";

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Max-Age: 3600");

require_once __DIR__ . "/../configs/BancoDados.php";
require_once __DIR__ . "/../configs/utils.php";
require_once __DIR__ . "/../configs/methods.php";
require_once __DIR__ . "/../model/Procedimento.php";

if($tipo != "funcionario" && $tipo != "admin"){
    $mensagem = array("Erro" => "Acesso negado");
    responder(403, $mensagem);
    
}

if(isMetodo("POST"))
{
    if(parametrosValidos($_POST,["nome"]))
    {
        $nome = $_POST["nome"];
    
        if (Procedimento::cadastrarProcedimento($nome)) {
            $msg = ["Status"=>"O procedimento $nome foi cadastrado com sucesso!"];
            responder(201,$msg);
            
        } else {
            $msg = ["Status"=>"Erro ao cadastrar o procedimento $nome"];
            responder(500,$msg);
            
        }
    }
    else
    {
        $msg = ["Os parametros nao sao validos!"];
        responder(203,$msg);
        
    }
}

if(isMetodo("GET")){
    if(empty($_GET)){
        $lista = Procedimento::listarProcedimentos();
        if($lista)
        {
            $mensagem = [];
            for($i = 0; $i < count($lista); $i++){
                $procedimento = $lista[$i];
                $mensagem[$i] = array("Nome" => $procedimento["nome"]);
            }
            responder(200, $mensagem);
            
        }
        else
        {
            $mensagem = ["Status"=>"Voce ainda não cadastrou nenhum procedimento!"];
            responder(404,$mensagem);
            
        }
    }
}

if(isMetodo("PUT"))
{
    if(parametrosValidos($_PUT, ["id","nome"]))
    {
        $resultado = Procedimento::editarProcedimento($_PUT["id"],$_PUT["nome"]);
        if ($resultado) {
            $msg = ["Status"=>"Procedimento editado com sucesso!"];
            responder(200,$msg);
            
        } else {
            $msg = ["Status"=>"Erro ao editar o procedimento!"];
            responder(500,$msg);
            
        }
    } else {
        $msg = ["Status"=>"Problemas na requisicao de editar!"];
        responder(400,$msg);
        
    }
}

if (isMetodo("DELETE")) {
    if($tipo != "admin"){
        $mensagem = array("Erro" => "Acesso negado");
        responder(403, $mensagem);
        
    }
    else{
        if (parametrosValidos($_DELETE, ["id"])) {
            $id = $_DELETE["id"];
            if (Procedimento::existeidProcedimento($id)) {
                $resultado = Procedimento::deletarProcedimento($id);
                if ($resultado) {
                    $msg = ["O procedimento foi deletado com sucesso!"];
                    responder(200,$msg);
                    
                } else {
                    $msg = ["Não foi possivel encontrar esse procedimento!"];
                    responder(400,$msg);
                    
                }
            } else {
                $msg = ["Essa procedimento nao existe!"];
                responder(404,$msg);
                
            }
        }
        else
        {
            $msg = ["Insira o id do procedimento!"];
            responder(400,$msg);
            
        }
    }
}
        