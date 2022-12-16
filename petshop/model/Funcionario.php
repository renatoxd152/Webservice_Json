<?php

require_once __DIR__ . "/../configs/BancoDados.php";

class Funcionario
{
    public static function cadastrarFuncionario($nome,$email, $tipo, $idLogin)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("INSERT INTO funcionario(nome,email,tipo,login) values (?,?,?,?)");
            $stmt->execute([$nome,$email, $tipo, $idLogin]);

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }
        catch(Exception $e)
        {
            $e->getMessage();
            exit;
        }
        
    }

    public static function existeEmail($email)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM funcionario WHERE email = ?");
            $stmt->execute([$email]);

            $quantidade = $stmt->fetchColumn();
            if ($quantidade > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public static function editarFuncionario($id,$nome,$email)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt=$conexao->prepare("UPDATE funcionario SET nome = ?,email=? WHERE id = ?");
            $stmt->execute([$nome,$email,$id]);

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }
        catch(Exception $e)
        {
            return false;
        }
    }


    public static function existeidFuncionario($id)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt=$conexao->prepare("SELECT COUNT(*) FROM funcionario where id=?");
            $stmt->execute([$id]);

            if ($stmt->fetchColumn() > 0) {
                return true;
            } else {
                return false;
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            exit;
        }
    }

    public static function getFuncionarioById($id)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM funcionario WHERE id = ?");
            $stmt->execute([$id]);

            return $stmt->fetchAll()[0];
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function listarFuncionario($id)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM funcionario WHERE id = ?");
            $stmt->execute([$id]);

            return $stmt->fetchAll();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            exit;
        }
    }

    public static function deletarFuncionario($id)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("DELETE FROM funcionario WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {

            echo $e->getMessage();
            exit;
        }
    }
    public static function listarFuncionarios()
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM funcionario ORDER BY id");
            $stmt->execute();

            return $stmt->fetchAll();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            exit;
        }
    }
    public static function listarFuncionariosbyId($idFuncionario)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT DISTINCT nome FROM funcionario WHERE id = ?");
            $stmt->execute([$idFuncionario]);

            return $stmt->fetchAll();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            exit;
        }
    }

    public static function nomeFuncionario($idFuncionario)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM funcionario WHERE id = ?");
            $stmt->execute([$idFuncionario]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function getFuncionarioByEmail($email){
        try{
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM funcionario WHERE email = ?");
            $stmt->execute([$email]);

            return $stmt->fetchAll();
        } catch (Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    public static function selecionarTipo($email)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT tipo FROM funcionario WHERE email = ?");
            $stmt->execute([$email]);

            return $stmt->fetchAll();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            exit;
        }
    }

    public static function getIDAdmmin($id)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT tipo FROM funcionario WHERE id = ?");
            $stmt->execute([$id]);

            return $stmt->fetchColumn();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function editaEmailFuncionario($emailAtual, $emailNovo){
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("UPDATE funcionario SET email = ? WHERE email = ?");
            $stmt->execute([$emailNovo, $emailAtual]);

            if($stmt->rowCount() > 0){
                return true;
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
}