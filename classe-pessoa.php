<?php

Class Pessoa{

    private $pdo;
    public function __construct($dbname, $host, $user, $senha)
    {
        try 
        {
            $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host,$user,$senha);
        }
        catch (PDOException $e) {
            echo "Erro com o banco de dados: ".$e->getMessage();
            exit();
        }
        catch (Exception $e) {
            echo "Erro generico: ".$e->getMessage();
            exit();
        }    
        
        
    }
    //FUNÇÃO PARA BUSCAR DADOS E COLOCAR NO CANTO DIREITO DA TELA
    public function buscarDados()
    {
        $res = array();
        $cmd = $this->pdo->query("SELECT * FROM pessoa ORDER BY nome");
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    //FUNÇÃO DE CADASTRAR PESSOA NO BANCO DE DADOS
    public function cadastrarPessoa($nome, $telefone, $email, $cidade, $estado)
    {
        //Antes de cadastrar verificar se já tem o email cadastrado
        $cmd = $this->pdo->prepare("SELECT id FROM pessoa WHERE email = :e");
        $cmd->bindValue(":e",$email);
        $cmd->execute();
        if($cmd->rowCount() > 0)//email já existe no banco
        {
            return false;
        }else//não foi encontrado o email
        {
            $cmd = $this->pdo->prepare("INSERT INTO pessoa (nome, telefone, email, cidade, estado) VALUES (:n, :t, :e, :c, :uf)");
            $cmd->bindValue(":n",$nome);
            $cmd->bindValue(":t",$telefone);
            $cmd->bindValue(":e",$email);
            $cmd->bindValue(":c",$cidade);
            $cmd->bindValue(":uf",$estado);
            $cmd->execute();
            return true;
        }
    }
    public function excluirPessoa($id)
    {
        $cmd = $this->pdo->prepare("DELETE FROM pessoa WHERE id = :id");
        $cmd->bindValue(":id",$id);
        $cmd->execute();
    }

    //Buscar dados de uma pessoa
    public function buscarDadosPessoa($id)
    {
        $res = array();
        $cmd = $this->pdo->prepare("SELECT * FROM pessoa WHERE id = :id");
        $cmd->bindValue("id",$id);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    //Atualizar dados no banco de dados
    public function atualizarDados($id, $nome, $telefone, $email, $cidade, $estado)
{
        $cmd = $this->pdo->prepare("UPDATE pessoa SET nome = :n, telefone = :t, email = :e, cidade = :c, estado = :uf WHERE id = :id");
        $cmd->bindValue(":n", $nome);
        $cmd->bindValue(":t", $telefone);
        $cmd->bindValue(":e", $email);
        $cmd->bindValue(":c",$cidade);
        $cmd->bindValue(":uf",$estado);
        $cmd->bindValue(":id", $id);
        $cmd->execute();

           
    }
}

?>