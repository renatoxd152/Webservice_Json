<?php
    require_once __DIR__ . "/../configs/BancoDados.php";
    
    class animal
    {

        public static function cadastrarAnimal($nome,$raca,$telefone)
        {
            try
            {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("INSERT INTO animal(nome,raca,teldono) values (?,?,?)");
                $stmt->execute([$nome,$raca,$telefone]);

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

        public static function existeidAnimal($id)
        {
            try
            {
                $conexao = Conexao::getConexao();
                $stmt=$conexao->prepare("SELECT COUNT(*) FROM animal where id=?");
                $stmt->execute([$id]);
        
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
        public static function existeAnimal($id)
        {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT COUNT(*) FROM animal WHERE id = ?");
                $stmt->execute([$id]);

                if ($stmt->fetchColumn() > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }
        public static function listarAnimais()
        {
            try
            {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM animal ORDER BY id");
                $stmt->execute();

                return $stmt->fetchAll();
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
                exit;
            }
        }

        public static function listarAnimal($id)
        {
            try
            {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM animal WHERE id = ?");
                $stmt->execute([$id]);

                return $stmt->fetchAll();
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
                exit;
            }
        }

        public static function deletarAnimal($id)
        {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("DELETE FROM animal WHERE id = ?");
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

        public static function editarAnimal($id,$nomeAnimal,$raca,$telefone)
        {
            try
            {
                $conexao = Conexao::getConexao();
                $stmt=$conexao->prepare("UPDATE animal SET nome=?,raca=?,teldono=? WHERE id = ?");
                $stmt->execute([$nomeAnimal,$raca,$telefone,$id]);

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

        public static function getAnimalById($id)
        {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM animal WHERE id = ?");
                $stmt->execute([$id]);

                return $stmt->fetchAll()[0];
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }


        public static function listarAnimais_telefone($telefone)
        {
            try
            {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM animal WHERE teldono = ?");
                $stmt->execute([$telefone]);

                return $stmt->fetchAll();
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
                exit;
            }
        }

        public static function verificarAnimal_Telefone($nome,$telefone)
        {
            try
            {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM animal WHERE nome = ? and teldono = ?");
                $stmt->execute([$nome,$telefone]);

                return $stmt->fetchAll();
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
                exit;
            }
        }
        public static function listarNumeros()
        {
            try
            {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT DISTINCT teldono FROM animal");
                $stmt->execute();

                return $stmt->fetchAll();
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
                exit;
            }
        }

        public static function listarAnimais_distinct()
        {
            try
            {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT DISTINCT raca FROM animal");
                $stmt->execute();

                return $stmt->fetchAll();
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
                exit;
            }
        }

        public static function listarAnimais_raca($raca)
        {
            try
            {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM animal WHERE raca=?");
                $stmt->execute([$raca]);

                return $stmt->fetchAll();
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
                exit;
            }
        }

        public static function listarAnimaisbyID($idAnimal)
        {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT DISTINCT nome FROM animal WHERE id = ?");
                $stmt->execute([$idAnimal]);

                return $stmt->fetchAll();
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        public static function nomeAnimal($idAnimal)
        {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM animal WHERE id = ?");
                $stmt->execute([$idAnimal]);

                return $stmt->fetchAll();
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }
    }
?>