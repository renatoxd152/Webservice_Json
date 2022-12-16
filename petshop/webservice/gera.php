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
require_once __DIR__ . "/../model/Gera.php";
require_once __DIR__ . "/../model/Procedimento.php";
require_once __DIR__ . "/../model/Atende.php";

if($tipo != "funcionario" && $tipo != "admin"){
    $mensagem = array("Erro" => "Acesso negado");
    responder(403, $mensagem);
    
}

if(isMetodo("POST"))
{
    if(parametrosValidos($_POST, ["idatendimento","idprocedimento"]))
    {
        $idProcedimento = $_POST["idprocedimento"];
        $idAtendimento = $_POST["idatendimento"];

        if(Gera::verifica($idAtendimento,$idProcedimento))
        {
            $msg = ["Essa consulta ja esta cadastrada!"];
            responder(404,$msg);
            
        }  
        else
        {
            if(Atende::existeidConsulta($idAtendimento) and Procedimento::existeidProcedimento($idProcedimento))
            {
                if(Gera::cadastrarProcedimento($idAtendimento,$idProcedimento))
                {
                    $msg = ["O procedimento foi cadastrado com sucesso!"];
                    responder(200,$msg);
                    
                }
                else
                {
                    $msg = ["Erro ao cadastrar a consulta!"];
                    responder(400,$msg);
                    
                }
            }
            else
            {
                $msg = ["Insira o id do atendimento e o id do procedimento!"];
                responder(404,$msg);
                
            }
        }
    }
    else
    {
        $msg = ["Insira o id do atendimento e o id do procedimento para cadastrar!"];
        responder(404,$msg);
        
    }
}


if (isMetodo("GET")) 
{
    if (parametrosValidos($_GET, ["idatendimento"])) 
    {
        $idAtendimento = $_GET["idatendimento"];
        $procedimentos = Gera::listarID($idAtendimento);
        $msg = [];
        if($procedimentos)
        {
            foreach($procedimentos as $p)
            {
                $msg[] = ["Nome"=>$p["nome"]];
            }
            responder(200,$msg);
            
        }
        else{
            $msg =["Houve um erro ao listar os procedimentos desse animal!"];
            responder(404,$msg);
            
        }
    }
    else
    {
        $mensagem = ["Status"=>"Nao foi possivel efetuar a operacao!"];
        responder(400,$mensagem);
        
    }
}

if (isMetodo("DELETE")) {
    if($tipo != "admin"){
        $mensagem = array("Erro" => "Acesso negado");
        responder(403, $mensagem);
        
    }
    else{
        if (parametrosValidos($_DELETE, ["idprocedimento","idatendimento"])) {
            $idProcedimento = $_DELETE["idprocedimento"];
            $idAtendimento = $_DELETE["idatendimento"];
            if (Gera::existeId($idProcedimento,$idAtendimento)) {
                $resultado = Gera::deletarProcedimento($idProcedimento,$idAtendimento);
                if ($resultado) {
                    $msg = ["O procedimento foi deletado com sucesso!"];
                    responder(200,$msg);
                    
                } else {
                    $msg = ["Não foi possivel encontrar esse procedimento!"];
                    responder(400,$msg);
                    
                }
            } else {
                $msg = ["Nao existe id de consulta com esse procedimento!"];
                responder(404,$msg);
                
            }
        }
        else
        {
            $msg = ["Insira o id do atendimento e o id do procedimento!"];
            responder(400,$msg);
            
        }
    }
}

if(isMetodo("PUT"))
{
    if(parametrosValidos($_PUT,  ["idprocedimento","idatendimento","id"]))
    {
        $resultado = Gera::editarProcedimento($_PUT["idprocedimento"],$_PUT["idatendimento"],$_PUT["id"]);
        if ($resultado) {
            $msg = ["Consulta com procedimento editado com sucesso!"];
            responder(200,$msg);
            
        } else {
            $msg = ["Erro ao editar a consulta!"];
            responder(500,$msg);
            
        }
    } else {
        $msg = ["Status"=>"Problemas na requisicao de editar!"];
        responder(400,$msg);
    }
}

