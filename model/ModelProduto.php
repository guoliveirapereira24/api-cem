<?php

class ModelProduto
{
    private $_method;

    private $_conexao;

    private $_idProduto;
    private $_nome;
    private $_descricao;
    private $_preco;
    private $_imagem;
    private $_desconto;
    private $_qtdParcela;


    public function __construct($conexao)
    {

        $json = file_get_contents("php://input");
        $dadosProduto = json_decode($json);

        $this->_method = $_SERVER['REQUEST_METHOD'];

        switch ($this->_method) {
            case 'POST':
                $this->_idProduto = $_POST['idProduto'] ?? null;
                $this->_nome = $_POST['nome'] ?? null;
                $this->_descricao = $_POST['descricao'] ?? null;
                $this->_preco = $_POST['preco'] ?? null;
                $this->_imagem = $_FILES['imagem']['name'] ?? null;
                $this->_desconto = $_POST['desconto'] ?? null;
                $this->_qtdParcela = $_POST['qtdParcela'] ?? null;
                $this->_categoria = $_POST['categoria'] ?? null;

                break;

            default:
                $this->_idProduto = $dadosProduto->idProduto ?? null;
                $this->_nome = $dadosProduto->nome ?? null;
                $this->_descricao = $dadosProduto->descricao ?? null;
                $this->_preco = $dadosProduto->preco ?? null;
                $this->_imagem = $dadosProduto->imagem ?? null;
                $this->_desconto = $dadosProduto->desconto ?? null;
                $this->_qtdParcela = $dadosProduto->qtdParcela ?? null;
                break;
        }


        $this->_conexao = $conexao;
    }
    public function findAll()
    {

        //instrução sql
        $sql = "SELECT * FROM tblProduto";

        //prepara o processo de execução da instrução sql
        $stm = $this->_conexao->prepare($sql);

        //executa a instrução sql
        $stm->execute();

        //devolve os valores da select para serem utilizados
        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create()
    {


        $sql = "INSERT INTO tblProduto (nome, descricao, preco, imagem, desconto, qtdParcela)
        VALUES (?, ?, ?, ?, ?, ?)";

        $extensao = pathinfo($this->_imagem, PATHINFO_EXTENSION);
        $novoNomeArquivo = md5(microtime()) . ".$extensao";

        move_uploaded_file($_FILES["imagem"]["tmp_name"], "../upload/$novoNomeArquivo");

        $stm = $this->_conexao->prepare($sql);

        $stm->bindValue(1, $this->_nome);
        $stm->bindValue(2, $this->_descricao);
        $stm->bindValue(3, $this->_preco);
        $stm->bindValue(4, $novoNomeArquivo);
        $stm->bindValue(5, $this->_desconto);
        $stm->bindValue(6, $this->_qtdParcela);



        if ($stm->execute()) {
            return "Success";
        } else {
            return "Error";
        }
    }


    public function delete()
    {

        $sqlImagem = "SELECT imagem FROM tblProduto WHERE idProduto = ?";

        $stm = $this->_conexao->prepare($sqlImagem);
        $stm->bindValue(1, $this->_idProduto);
        $stm->execute();

        $produto = $stm->fetchAll(\PDO::FETCH_ASSOC);
        unlink("../upload/" . $produto[0]["imagem"]);

        $sql = "DELETE FROM tblProduto WHERE idProduto = ?";

        $stmt = $this->_conexao->prepare($sql);

        $stmt->bindValue(1, $this->_idProduto);

        if ($stmt->execute()) {
            return "Dados excluídos com sucesso!";
        }
    }

    public function update()
    {
        $sqlImg = "SELECT imagem FROM tblProduto WHERE idProduto = ?";

        $stm = $this->_conexao->prepare($sqlImg);
        $stm->bindValue(1, $this->_idProduto);

        $stm->execute();


        $produto = $stm->fetchAll(\PDO::FETCH_ASSOC);
        // var_dump($produto[0]["imagem"]);exit;

        unlink("../upload/" . $produto[0]["imagem"]);

        $nomeArquivo = $_FILES["imagem"]["name"];
        $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);
        $novoNomeArquivo = md5(microtime()) . ".$extensao";
        // echo $novoNomeArquivo;exit;
        move_uploaded_file($_FILES["imagem"]["tmp_name"], "../upload/$novoNomeArquivo");

        $sql = "UPDATE tblproduto SET 
        nome = ?,
        descricao = ?,
        preco = ?,
        imagem = ?,
        desconto = ?,
        qtdParcela = ?
        WHERE idProduto = ?";

        $stmt = $this->_conexao->prepare($sql);

        // var_dump($stmt);exit;

        $stmt->bindValue(1, $this->_nome);
        $stmt->bindValue(2, $this->_descricao);
        $stmt->bindValue(3, $this->_preco);
        $stmt->bindValue(4, $novoNomeArquivo);
        $stmt->bindValue(5, $this->_desconto);
        $stmt->bindValue(6, $this->_qtdParcela);

        $stmt->bindValue(7, $this->_idProduto);

        if ($stmt->execute()) {
            return "Dados alterados com sucesso!";
        }
    }
}
