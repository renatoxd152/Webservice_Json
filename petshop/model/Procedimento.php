<?php

require_once __DIR__ . "/../configs/BancoDados.php";
class Procedimento
{
    public static function cadastrarProcedimento($nome)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("INSERT INTO procedimentos(nome) values (?)");
            $stmt->execute([$nome]);

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

    public static function listarProcedimentos()
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM procedimentos ORDER BY id");
            $stmt->execute();

            return $stmt->fetchAll();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            exit;
        }
    }

    public static function editarProcedimento($id,$nome)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt=$conexao->prepare("UPDATE procedimentos SET nome=? WHERE id = ?");
            $stmt->execute([$nome,$id]);

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

    public static function existeidProcedimento($id)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt=$conexao->prepare("SELECT COUNT(*) FROM procedimentos where id=?");
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

    public static function deletarProcedimento($id)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("DELETE FROM procedimentos WHERE id = ?");
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
    public static function listarProcedimentosbyID($idProcedimento)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM procedimentos WHERE id=?");
            $stmt->execute(["id"]);

            return $stmt->fetchAll();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            exit;
        }
    }
}
?>