<?php
require_once __DIR__ . "/../configs/BancoDados.php";

class Login{
    public static function existeLogin($email){
        try{
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM login WHERE email = ?");
            $stmt->execute([$email]);

            // if($stmt->fetchColumn() > 0){
            //     return true;
            // }
            // else{
            //     return false;
            // }
            return $stmt->fetchAll();
        }catch (Exception $e){
            return false;
        }
    }

    public static function getLoginByEmail($email){
        try{
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM login WHERE email = ?");
            $stmt->execute([$email]);

            return $stmt->fetchAll();
        }catch (Exception $e){
            $e->getMessage;
        }
    }

    public static function cadastraLogin($email, $senha){
        try{
            $conexao = Conexao::getConexao();
            $hash = password_hash($senha, PASSWORD_BCRYPT, ["cost" => 15]);

            $stmt = $conexao->prepare("INSERT INTO login(email, senha) VALUES (?,?)");
            $stmt->execute([$email, $hash]);

            if($stmt->rowCount() > 0){
                return true;
            }
            else{
                return false;
            }
        } catch(Exception $e){
            $e->getMessage();
            exit;
        }
    }

    public static function geraSenhaAleatoria($tamanho = 10){
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tamCaracteres = strlen($caracteres);
        $senhaAleatoria = '';
        for ($i = 0; $i < $tamanho; $i++) {
            $senhaAleatoria .= $caracteres[rand(0, $tamCaracteres - 1)];
        }
        return $senhaAleatoria;
    }

    public static function deletaLogin($email){
        try{
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("DELETE FROM login WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }catch (Exception $e){
            $e->getMessage();
            exit;
        }
    }

    public static function modificaLogin($email, $senha, $tipo, $emailOriginal){
        try{
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("UPDATE login SET email = ?, senha = ?, tipo = ? WHERE email = ?");
            $stmt->execute([$email, $senha, $tipo, $emailOriginal]);

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch(Exception $e){
            return false;
        }
    }

    public static function bancoPopulado(){
        try{
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM login");
            $stmt->execute();

            if($stmt->fetchColumn() > 0){
                return true;
            }
            else{
                return false;
            }
        } catch (Exception $e){
            return false;
        }
    }

    public static function modificaEmailLogin($email, $id){
        try{
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("UPDATE login SET email = ? WHERE id = ?");
            $stmt->execute([$email, $id]);

            if ($stmt->rowCount() > 0){
                return true;
            }
            else{
                return false;
            }
        } catch(Exception $e){
            return false;
        }
    }
}