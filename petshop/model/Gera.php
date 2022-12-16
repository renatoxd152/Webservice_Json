<?php
require_once __DIR__ . "/../configs/BancoDados.php";
class Gera
{
    public static function cadastrarProcedimento($idatendimento,$idprocedimentonome)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("INSERT INTO gera (idatendimento,idprocedimento) VALUES (?,?)");
            $stmt->execute([$idatendimento,$idprocedimentonome]);

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

    public static function listarprocedimentosbyID($idConsulta)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT p.nome FROM procedimentos p INNER JOIN gera g ON g.idprocedimento = p.id inner join atende a on g.idatendimento = a.id WHERE idprocedimento = ?");
            $stmt->execute([$idConsulta]);

            return $stmt->fetchAll();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            exit;
        }
    }

    public static function listarProcedimentos()
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT DISTINCT g.idatendimento,an.nome FROM gera g INNER JOIN atende a ON g.idatendimento = a.id INNER JOIN animal an ON a.idanimal = an.id");
            $stmt->execute();

            return $stmt->fetchAll();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            exit;
        }
    }

    public static function listarID($idatendimento)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT p.nome FROM procedimentos p INNER JOIN gera g ON g.idprocedimento = p.id WHERE g.idatendimento = ?");
            $stmt->execute([$idatendimento]);

            return $stmt->fetchAll();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            exit;
        }
    }
    public static function verificaProcedimento($idAtendimento)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt=$conexao->prepare("SELECT COUNT(*) FROM gera WHERE idatendimento=?");
            $stmt->execute([$idAtendimento]);

            if ($stmt->fetchColumn() > 0) {
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

    public static function verifica($idatendimento,$idprocedimento)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt=$conexao->prepare("SELECT COUNT(*) FROM gera WHERE idatendimento=? and idprocedimento=?");
            $stmt->execute([$idatendimento,$idprocedimento]);

            if ($stmt->fetchColumn() > 0) {
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

    public static function existeId($idprocedimento,$idatendimento)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt=$conexao->prepare("SELECT COUNT(*) FROM gera WHERE idprocedimento=? and idatendimento=?");
            $stmt->execute([$idprocedimento,$idatendimento]);

            if ($stmt->fetchColumn() > 0) {
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

    public static function existeIdatendimento($idatendimento)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt=$conexao->prepare("SELECT COUNT(*) FROM gera WHERE idatendimento=?");
            $stmt->execute([$idatendimento]);

            if ($stmt->fetchColumn() > 0) {
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

    public static function deletarProcedimento($idprocedimento,$idatendimento)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("DELETE FROM gera WHERE idprocedimento = ? and idatendimento=?");
            $stmt->execute([$idprocedimento,$idatendimento]);

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

    public static function deletarProcedimento_Atendimento($idatendimento)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("DELETE FROM gera WHERE idatendimento=?");
            $stmt->execute([$idatendimento]);

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

    
    public static function editarProcedimento($idprocedimento,$idatendimento,$id)
    {
        try
        {
            $conexao = Conexao::getConexao();
            $stmt=$conexao->prepare("UPDATE gera SET idprocedimento = ?,idatendimento=? WHERE id = ?");
            $stmt->execute([$idprocedimento,$idatendimento,$id]);

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

}

    

    
?>