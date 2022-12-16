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
require_once __DIR__ . "/../model/Funcionario.php";
require_once __DIR__ . "/../model/Atende.php";
require_once __DIR__ . "/../model/Animal.php";

if($tipo != "funcionario" && $tipo != "admin"){
    $mensagem = array("Erro" => "Acesso negado");
    responder(403, $mensagem);
    
}

if(isMetodo("GET")){
    if(!empty($_GET) && parametrosValidos($_GET, ["id"])){
        $id = $_GET["id"];
        $resultado = Atende::existeidConsulta($id);
        $mensagem = [];
        if($resultado){
            $consulta = Atende::listarConsulta($id);
            foreach($consulta as $c)
            {
                $mensagem[] = array("Id"=> $c["id"],"Id do funcionario" => $c["idfuncionario"],"Id do animal" => $c["idanimal"],"Data de cadastro" => $c["data"]);
            }
            responder(200,$mensagem);
        }
        else
        {
            $mensagem = ["Erro" => "Essa consulta nao existe."];
            responder(404,$mensagem);
            
        }
    }
    else if(empty($_GET)){
        $lista = Atende::listarConsultas();
        if($lista)
        {
            for($i = 0; $i < count($lista); $i++){
                $consultas = $lista[$i];
                $mensagem[$i] = array(
                    "Status" => "Ok",
                    "Id" => $consultas["id"],
                    "Id do funcionario" => $consultas["idfuncionario"],
                    "Id do animal" => $consultas["idanimal"],
                    "Data" => $consultas["data"]
                );
            }
            responder(200, $mensagem);
            
        }
        else
        {
            $mensagem = ["Erro" => "Ainda nao foi cadastrado nenhuma consulta!"];
            responder(404,$mensagem);
            
        }
    } 
    else
    {
        $mensagem = ["Status"=>"Nao foi possível efetuar a operação!"];
        responder(400,$mensagem);
        
    }
}

if(isMetodo("POST"))
{
    if(parametrosValidos($_POST, ["idfuncionario","idanimal"]))
    {
        $funcionario = $_POST["idfuncionario"];
        $animal = $_POST["idanimal"];

        if(Atende::selecionarfuncionarioanimal($animal,$funcionario))
        {
            $msg = ["Essa consulta ja esta cadastrada!"];
            responder(404,$msg);
            
        }  
        else
        {
            if(Funcionario::existeidFuncionario($funcionario) and Animal::existeAnimal($animal))
            {
                if(Atende::cadastrarConsulta($funcionario,$animal))
                {
                    $msg = ["A consulta foi cadastrada com sucesso!"];
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
                $msg = ["Insira os ids no cadastrados no sistema!"];
                responder(404,$msg);
                
            }

        }
    }
    else
    {
        $msg = ["Insira o id do animal e do funcionario para cadastrar!"];
        responder(400,$msg);
        
    }
}

if(isMetodo("PUT"))
{
    if(parametrosValidos($_PUT,  ["id","idfuncionario","idanimal"]))
    {
        $resultado = Atende::editarConsulta($_PUT["idfuncionario"],$_PUT["idanimal"],$_PUT["id"]);
        if ($resultado) {
            $msg = ["Consulta editada com sucesso!"];
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

if (isMetodo("DELETE")) {
    if($tipo != "admin"){
        $mensagem = array("Erro" => "Acesso negado");
        responder(403, $mensagem);
        
    }
    else{
        if (parametrosValidos($_DELETE, ["id"])) 
        {
            $id = $_DELETE["id"];
            if(Gera::existeIdatendimento($id))
            {
                $deletarProcedimento = Gera::deletarProcedimento_Atendimento($id);
                if($deletarProcedimento)
                {
                    if (Atende::existeidConsulta($id)) 
                    {
                        $resultado = Atende::deletarConsulta($id);
                        if ($resultado) {
                            $msg = ["A consulta foi deletada com sucesso!"];
                            responder(200,$msg);
                            exit;
                        } else {
                            $msg = ["Nao foi possivel encontrar essa consulta!"];
                            responder(400,$msg);
                            exit;
                        }
                    }
                    else
                    {
                        $msg = ["Nao foi possivel encontrar essa consulta!"];
                        responder(400,$msg);
                        exit;
                    }
                }
                    
                 
            }
            else
            {
                if (Atende::existeidConsulta($id))
                {
                    if (Atende::deletarConsulta($id)) {
                        $msg = ["A consulta foi deletada com sucesso!"];
                        responder(200,$msg);
                        exit;
                    } else {
                        $msg = ["Nao foi possivel encontrar essa consulta!"];
                        responder(400,$msg);
                        exit;
                    }
                } 
                else
                {
                    $msg = ["Nao foi possivel encontrar essa consulta!"];
                    responder(400,$msg);
                    exit;
                }
                 
            }
                
        }
        else
        {
            $msg = ["Insira o id da consulta!"];
            responder(400,$msg);
            
        }
    }
}


