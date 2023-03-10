<?php
require_once __DIR__ . "/../model/checaLogin.php";

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . "/../configs/BancoDados.php";
require_once __DIR__ . "/../configs/utils.php";
require_once __DIR__ . "/../configs/methods.php";
require_once __DIR__ . "/../model/Animal.php";
require_once __DIR__ . "/../model/Atende.php";

if($tipo != "funcionario" && $tipo != "admin"){
    $mensagem = array("Erro" => "Acesso negado");
    responder(403, $mensagem);
    
}

if(isMetodo("GET")){
    if(!empty($_GET) && parametrosValidos($_GET, ["id"])){
        $id = $_GET["id"];
        $resultado = Animal::existeAnimal($id);
        $mensagem = [];
        if($resultado){
            $animal = Animal::listarAnimal($id);
            foreach($animal as $a)
            {
                $mensagem[] = array(
                    "Id"=>$a["id"],
                    "Nome"=>$a["nome"],
                    "Raca"=>$a["raca"],
                    "Teldono"=>$a["teldono"],
                    "Data de cadastro"=>$a["datacadastro"]);
            }
            responder(200,$mensagem);
            
        }
        else
        {
            $mensagem = ["Status"=>"Erro ao listar o animal"];
            responder(404,$mensagem);
            
        }
        
    }
    elseif(empty($_GET)){
        $lista = Animal::listarAnimais();
        $mensagem = [];
        if($lista)
        {
            for($i = 0; $i < count($lista); $i++){
                $animal = $lista[$i];
                $mensagem[$i] = array(
                    "Status" => "Ok",
                    "Id" => $animal["id"],
                    "Nome" => $animal["nome"],
                    "Raca" => $animal["raca"],
                    "Teldono" => $animal["teldono"],
                    "datacadastro" => $animal["datacadastro"]
                );
            }
            responder(200, $mensagem);
            
        }
        else
        {
            $mensagem = ["Status"=>"Voc?? ainda n??o cadastrou nenhum animal!"];
            responder(404,$mensagem);
            
        }
    
    } 
    else
    {
        $mensagem = ["Status"=>"Nao foi poss??vel efetuar a operacao!"];
        responder(400,$mensagem);
        
    }
}  

if(isMetodo("PUT"))
{
    if($tipo != "admin"){
        $mensagem = array("Erro" => "Acesso negado");
        responder(403, $mensagem);
        
    }
    elseif ($tipo == "admin") {
        if(parametrosValidos($_PUT, ["id","nome","raca","teldono"]))
        {
            $resultado = Animal::editarAnimal($_PUT["id"],$_PUT["nome"], $_PUT["raca"],$_PUT["teldono"]);
            if ($resultado) {
                $msg = ["Status"=>"Animal editado com sucesso!"];
                responder(200,$msg);
                
            } else {
                $msg = ["Status"=>"Erro ao editar o animal!"];
                responder(500,$msg);
                
            }
        } else {
            $msg = ["Status"=>"Problemas na requisicao de editar!"];
            responder(400,$msg);
            
        }
    }
}


if(isMetodo("POST"))
{
    if(parametrosValidos($_POST,["nome","raca","teldono"]))
    {
        $nome = $_POST["nome"];
        $raca = $_POST["raca"];
        $teldono= $_POST["teldono"];
        
        if(Animal::verificarAnimal_Telefone($nome,$teldono))
        {
            $msg = ["Status"=>"Ja existe um animal com o nome: $nome e telefone!"];
            responder(409,$msg);
            
        }
        else
        {
            if (Animal::cadastrarAnimal($nome,$raca,$teldono)) {
                $msg = ["Status"=>"O animal $nome de ra??a $raca foi cadastrado com sucesso!"];
                responder(201,$msg);
                
            } else {
                $msg = ["Status"=>"Erro ao cadastrar o animal $nome"];
                responder(500,$msg);
                
            }
        }
    }
    else{
        $mensagem = array("Erro" => "Um ou mais parametros invalidos");
        responder(400, $mensagem);
        
    }
}

if (isMetodo("DELETE")) {
    if($tipo != "admin"){
        $mensagem = array("Erro" => "Acesso negado");
        responder(403, $mensagem);
        
    }
    elseif ($tipo == "admin") {
        if (parametrosValidos($_DELETE,["id"])) {
            $id = $_DELETE["id"];
            if (Animal::existeAnimal($id)) {
                if(Atende::selecionarAnimaisID($id))
                {
                    $msg = ["Status"=>"Nao ?? poss??vel deletar esse animal pois est?? cadastrado em uma consulta!"];
                    responder(400,$msg);
                    
                }
                else
                {
                    if (Animal::deletarAnimal($id)) {
                        $msg = ["Status"=>"Animal deletado com sucesso!"];
                        responder(200,$msg);
                        
                    } else {
                        $msg = ["Status"=>"Nao foi poss??vel encontrar esse animal!"];
                        responder(404,$msg);
                        
                    
                    }
                }
            } else {
                $msg = ["Status"=>"Esse animal n??o existe!"];
                responder(404,$msg);
                
            }
        }
        else
        {
            $msg = ["Status"=>"Insira algum id para deletar!"];
            responder(400,$msg);
            
        }
    }
}
