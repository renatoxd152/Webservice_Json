<?php
require __DIR__ . '/vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . "/configs/BancoDados.php";
require_once __DIR__ . "/configs/utils.php";
require_once __DIR__ . "/model/Login.php";
require_once __DIR__ . "/model/Funcionario.php";

if(isMetodo("POST")){
    if(parametrosValidos($_POST, ["email", "senha"])){
        if(Login::existeLogin($_POST["email"])){
            $login = Login::getLoginByEmail($_POST["email"]);
            $senha = $login[0]["senha"];

            $funcionario = Funcionario::getFuncionarioByEmail($_POST["email"]);
            $tipo = $funcionario[0]["tipo"];
            $idUsuario = $funcionario[0]["id"];

            if($_POST["email"] == $login[0]["email"] && password_verify($_POST["senha"], $senha)){
                $secretKey = "chaveSecretaLojaPetshop";
                $now = new DateTimeImmutable();
                $valid = $now->modify("+40 minutes")->getTimestamp();
                $server = "localhost";
                $dados = [
                    "email" => $_POST["email"],
                    "tipo" => $tipo
                ];

                $payload = [
                    "iat" => $now->getTimestamp(),
                    "iss" => $server,
                    "nbf" => $now->getTimestamp(),
                    "exp" => $valid,
                    "sub" => $idUsuario,
                    "data" => $dados
                ];

                $token_pronto = JWT::encode($payload, $secretKey, "HS512");
                http_response_code(200);
                echo json_encode([
                    "status" => "Ok",
                    "token" => $token_pronto
                ]);
                die;
            }
            else{
                $mensagem = array("Erro" => "Email ou senha invalidos");
                responder(400, $mensagem);
                
            }
        }
        elseif(!Login::bancoPopulado()){
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $msg = ["O email nao e valido"];
                responder(400,$msg);
                exit;
            }
            $cadastroLogin = Login::cadastraLogin($_POST["email"], "senha_admin");
            $login = Login::getLoginByEmail($_POST["email"]);
            $cadastroFuncionario = Funcionario::cadastrarFuncionario("Admin", $_POST["email"], "admin", $login[0]["id"]);
            if($cadastroLogin && $cadastroFuncionario){
                $mensagem = array("Mensagem" => "Nao existiam cadastros portanto uma nova conta com o email " . $_POST["email"] . " e senha 'senha_admin' foi criado");
                responder(200, $mensagem);
                exit;
            }
            else{
                $mensagem = array("Erro" => "Problema ao fazer o cadastro");
                responder(500, $mensagem);
                exit;
            }
        }
        else{
            $mensagem = array("Erro" => "Email ou senha invalidos");
            responder(400, $mensagem);
            
        }
    }
    else{
        $mensagem = array("Erro" => "Um ou mais parametros sao invalidos");
        responder(400, $mensagem);
    }
}

$mensagem = ["Erro" => "Metodo nao aceito"];
responder(405, $mensagem);