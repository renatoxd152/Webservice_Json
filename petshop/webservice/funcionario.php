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
require_once __DIR__ . "/../model/Login.php";

if($tipo != "funcionario" && $tipo != "admin"){
    $mensagem = ["Erro" => "Acesso negado"];
    responder(403, $mensagem);
    
}

if(isMetodo("GET")){
    if(!empty($_GET) && parametrosValidos($_GET, ["id"])){
        $id = $_GET["id"];
        $resultado = Funcionario::existeidFuncionario($id);
        $mensagem = [];
        if($resultado){
            $funcionario = Funcionario::listarFuncionario($id);
            foreach($funcionario as $f)
            {
                $mensagem[] = array(
                    "Id"=> $f["id"],
                    "Nome" => $f["nome"],
                    "Email" => $f["email"],
                    "Tipo" => $f["tipo"],
                    "Data de cadastro" => $f["datacadastro"]
                );
            }
            responder(200,$mensagem);
        }
        else
        {
            $mensagem = ["Erro" => "Funcionario nao existe."];
            responder(404,$mensagem);
            
        }
    }
    else if(empty($_GET)){
        $lista = Funcionario::listarFuncionarios();
        if($lista)
        {
            for($i = 0; $i < count($lista); $i++){
                $funcionario = $lista[$i];
                $mensagem[$i] = array(
                    "Status" => "Ok",
                    "Id" => $funcionario["id"],
                    "Nome" => $funcionario["nome"],
                    "Email" => $funcionario["email"],
                    "Tipo" => $funcionario["tipo"],
                    "datacadastro" => $funcionario["datacadastro"]
                );
            }
            responder(200, $mensagem);
            
        }
        else
        {
            $mensagem = ["Erro" => "Ainda nao foi cadastrado nenhum funcionario!"];
            responder(404,$mensagem);
            
        }
    }
}

if(isMetodo("POST")){
    if($tipo != "admin"){
        $mensagem = ["Erro" => "Acesso negado"];
        responder(403, $mensagem);
        
    }
    else{
        if(empty($_POST)){
            $mensagem = array("Erro" => "Deve se passar parametros de cadastro.");
            responder(400, $mensagem);
            
        }
        else{
            if (parametrosValidos($_POST, ["nome", "email"])) {
                $nome = $_POST["nome"];
                $email = $_POST["email"];

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $msg = ["O email nao e valido"];
                    responder(400,$msg);
                    
                }
                else{
                    if (!preg_match("/^[a-zA-Z-' ]*$/", $nome)) {
                        $msg = ["O nome possui caracteres invalidos"];
                        responder(400,$msg);
                        
                    }
                    else{
                        if (!Funcionario::existeEmail($email)) {
                            $senha = Login::geraSenhaAleatoria();
                            $cadastraLogin = Login::cadastraLogin($email, $senha);
                            $login = Login::getLoginByEmail($email);
                            $cadastraFuncionario = Funcionario::cadastrarFuncionario($nome, $email, "funcionario", $login[0]["id"]);
                            if ($cadastraLogin && $cadastraFuncionario) {
                                $msg = ["Mensagem" => "O funcionário $nome foi cadastrada com sucesso com a senha $senha"];
                                responder(200,$msg);
                                
                            } else {
                                $msg = ["Erro" => "Erro ao cadastrar o funcionario $nome"];
                                responder(400,$msg);
                                
                            }
                        } else {
                            $msg = ["Erro" => "Já existe um funcionario com o email $email"];
                            responder(400,$msg);
                            
                        }
                    }
                }
            }
            else{
                $mensagem = ["Erro" => "Um ou mais parametros sao invalidos"];
                responder(400, $mensagem);
                
            }
        }
    }
}

// if(isMetodo("PUT")){
//     if($tipo != "admin"){
//         $mensagem = ["Erro" => "Acesso negado"];
//         responder(403, $mensagem);
        
//     }
//     else{
//         if(empty($_PUT)){
//             $mensagem = ["Erro" => "Deve se passar parametros de modificacao."];
//             responder(400, $mensagem);
//         }
//         else{
//             if(parametrosValidos($_PUT, ["emailOriginal"]) && (array_key_exists("nome", $_PUT) && array_key_exists("email", $_PUT))){
//                 if(Funcionario::existeEmail($_PUT["emailOriginal"])){
                    
//                     $parametros = array_keys($_PUT);
//                     $funcionario = Funcionario::getFuncionarioByEmail($_PUT["emailOriginal"]);
//                     foreach($parametros as $key){
//                         if($_PUT[$key] == ""){
//                             $_PUT[$key] = $funcionario[0][$key];
//                         }
//                     }

//                     $idFuncionario = Funcionario::getFuncionarioByEmail($_PUT["emailOriginal"]);

//                     if(Funcionario::editarFuncionario($idFuncionario[0]["id"], $_PUT["nome"], $_PUT["email"])){
//                         $mensagem = ["Mensagem" => "Modificado com sucesso."];
//                         responder(200, $mensagem);
//                     }
//                     else{
//                         $mensagem = ["Erro" => "Houve um problema ao fazer a modificacao"];
//                         responder(500, $mensagem);
//                     }
                    
//                 }
//                 else{
//                     $mensagem = ["Erro" => "Funcionario nao existe."];
//                     responder(204, $mensagem);
                    
//                 }
//             }
//             else{
//                 $mensagem = ["Erro" => "Deve se passar todos os parametros de modificacao mesmo que vazios."];
//                 responder(400, $mensagem);
                
//             }
//         }
//     }
// }

if(isMetodo("PUT")){
    if($tipo != "admin"){
        $mensagem = ["Erro" => "Acesso negado"];
        responder(403, $mensagem);
        
    }
    else{
        if(empty($_PUT)){
            $mensagem = ["Erro" => "Deve se passar parametros de modificacao."];
            responder(400, $mensagem);
        }
        else{
            if(parametrosValidos($_PUT, ["emailOriginal"]) && (array_key_exists("nome", $_PUT) && array_key_exists("email", $_PUT))){
                if(Funcionario::existeEmail($_PUT["emailOriginal"])){
                    
                    $parametros = array_keys($_PUT);
                    $funcionario = Funcionario::getFuncionarioByEmail($_PUT["emailOriginal"]);
                    foreach($parametros as $key){
                        if($_PUT[$key] == ""){
                            $_PUT[$key] = $funcionario[0][$key];
                        }
                    }

                    $funcionario = Funcionario::getFuncionarioByEmail($_PUT["emailOriginal"]);

                    if(Funcionario::editarFuncionario($funcionario[0]["id"], $_PUT["nome"], $_PUT["email"])){
                        if(Login::modificaEmailLogin($_PUT["email"], $funcionario[0]["login"])){
                            $mensagem = ["Mensagem" => "Modificado com sucesso."];
                            responder(200, $mensagem);
                        }
                        else{
                            $mensagem = [
                                "Mensagem" => "Funcionario modificado com sucesso",
                                "Erro" => "Login nao foi modificado com novo email"
                            ];
                            responder(500, $mensagem);
                        }
                    }
                    else{
                        $mensagem = ["Erro" => "Houve um problema ao fazer a modificacao"];
                        responder(500, $mensagem);
                    }
                    
                }
                else{
                    $mensagem = ["Erro" => "Funcionario nao existe."];
                    responder(204, $mensagem);
                    
                }
            }
            else{
                $mensagem = ["Erro" => "Deve se passar todos os parametros de modificacao mesmo que vazios."];
                responder(400, $mensagem);
                
            }
        }
    }
}

if (isMetodo("DELETE")) {
    if($tipo != "admin"){
        $mensagem = ["Erro" => "Acesso negado"];
        responder(403, $mensagem);
    }
    else{
        if (parametrosValidos($_DELETE,["email"])) {
            if (Funcionario::existeEmail($_DELETE["email"])) {
                $id = Funcionario::getFuncionarioByEmail($_DELETE["email"]);
                if(Atende::selecionarFuncionario($id[0]["id"]))
                {
                    $msg = ["Status"=>"Não é possível deletar esse funcionário pois está cadastrado em uma consulta!"];
                    responder(400,$msg);
                    
                }
                else
                {
                    if (Funcionario::getIDAdmmin($id[0]["id"]) != "admin"){
                        $deletaFuncionario = Funcionario::deletarFuncionario($id[0]["id"]);
                        $deletaLogin = Login::deletaLogin($_DELETE["email"]); //$funcionario[0]["email"]

                        if ($deletaFuncionario) {
                            if($deletaLogin){
                                $msg = ["Mensagem"=>"Funcionario e login deletados com sucesso!"];
                                responder(200,$msg);
                                exit;
                                
                            }
                            else{
                                $mensagem = [
                                    "Mensagem" => "Funcionario deletado com sucesso",
                                    "Erro" => "Houve um problema ao deletar o login"
                                ];
                                responder(500, $mensagem);
                                exit;
                            }
                        } else {
                            $msg = ["Erro"=>"Nao foi possível deletar esse funcionario!"];
                            responder(500,$msg);
                            exit;
                            
                        
                        }
                    }
                    else{
                        $mensagem = ["Erro" => "Acesso negado"];
                        responder(403, $mensagem);
                        exit;
                    }
                }
            } else {
                $msg = ["Erro"=>"Esse funcionario nao existe!"];
                responder(404,$msg);
                exit;
                
            }
        }
        else
        {
            $msg = ["Erro"=>"Insira algum email para deletar!"];
            responder(400,$msg);
            exit;
            
        }
    }
}