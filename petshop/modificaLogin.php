<?php
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . "/model/checaLogin.php";

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . "/configs/BancoDados.php";
require_once __DIR__ . "/configs/utils.php";
require_once __DIR__ . "/configs/methods.php";
require_once __DIR__ . "/model/Login.php";
require_once __DIR__ . "/model/Funcionario.php";

if($tipo != "admin" && $tipo != "funcionario"){
    $mensagem = ["Erro" => "Acesso negado"];
    responder(403, $mensagem);
    
}

if(isMetodo("PUT")){
    if(parametrosValidos($_PUT, ["email", "senha"])){
        if (!filter_var($_DELETE["email"], FILTER_VALIDATE_EMAIL)) {
            $msg = ["O email nao e valido"];
            responder(400,$msg);
        }
        $funcionario = Funcionario::getFuncionarioByEmail($email);
        //a variavel $email é o email armazenado no token
        if(Login::modificaLogin($_PUT["email"], $_PUT["senha"], $tipo, $email)){
            if(Funcionario::editaEmailFuncionario($email, $_PUT["email"])){
                $mensagem = ["Mensagem" => "Login modificado"];
                responder(200, $mensagem);
            }
            $mensagem = [
                "Mensagem" => "Login modificado",
                "Erro" => "O email do funcionario nao pode ser modificado"
            ];
            responder(500, $mensagem);
        }
        else{
            $mensagem = ["Erro" => "Erro ao fazer a modificacao"];
            responder(500, $mensagem);
        }
    }
    else{
        $mensagem = ["Erro" => "Um ou mais parametros invalidos"];
        responder(400, $mensagem);
    }
}

if(isMetodo("DELETE")){
    if($tipo != "admin"){
        $mensagem = ["Erro" => "Acesso negado"];
        responder(403, $mensagem);
    }
    else{
        if(parametrosValidos($_DELETE, ["email"])){
            if (!filter_var($_DELETE["email"], FILTER_VALIDATE_EMAIL)) {
                $msg = ["O email nao e valido"];
                responder(400,$msg);
            }
            if(Funcionario::existeEmail($_DELETE["email"])){
                $mensagem = ["Erro" => "Existe um funcionario com este email, apenas use esta funcao se o login nao tiver sido deletado corretamente apos deletar o funcionario"];
                responder(418, $mensagem);
            }
            else{
                if(Login::deletaLogin($_DELETE["email"])){
                    $mensagem = ["Mensagem" => "Login deletado"];
                    responder(200, $mensagem);
                }
                else{
                    $mensagem = ["Erro" => "Falha ao deletar o login"];
                    responder(500, $mensagem);
                }
            }
        }
        else{
            $mensagem = ["Erro" => "Parametro invalido."];
            responder(400, $mensagem);
        }
    }
}