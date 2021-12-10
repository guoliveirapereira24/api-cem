<?php

class ModelUsuario
{
    private $_method;

    private $_conexao;

    private $_idUsuario;
    private $_nome;
    private $_sobrenome;
    private $_cpf;
    private $_senha;
    private $_email;
    private $_imagem;
    private $_tomPele;

    public function __construct($conexao)
    {

        $json = file_get_contents("php://input");
        $dadosUsuario = json_decode($json);

        $this->_method = $_SERVER['REQUEST_METHOD'];

        switch ($this->_method) {
            case 'POST':
                $this->_idUsuario = $_POST['idUsuario'] ?? null;
                $this->_nome = $_POST['nome'] ?? null;
                $this->_sobrenome = $_POST['sobrenome'] ?? null;
                $this->_cpf = $_POST['cpf'] ?? null;
                $this->_senha = $_POST['senha'] ?? null;
                $this->_email = $_POST['email'] ?? null;
                $this->_imagem = $_FILES['imagem']['name'] ?? null;
                $this->_tomPele = $_POST['tomPele'] ?? null;

                break;

            default:
                $this->_idUsuario = $dadosUsuario->idUsuario ?? null;
                $this->_nome = $dadosUsuario->nome ?? null;
                $this->_sobrenome = $dadosUsuario->sobrenome ?? null;
                $this->_cpf = $dadosUsuario->cpf ?? null;
                $this->_senha = $dadosUsuario->senha ?? null;
                $this->_email = $dadosUsuario->email ?? null;
                $this->_imagem = $dadosUsuario->imagem ?? null;
                $this->_tomPele = $dadosUsuario->tomPele ?? null;

                break;
        }

        $this->_conexao = $conexao;
    }

    public function findById()
    {

        $sql = "SELECT * FROM tblUsuario WHERE idUsuario = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_idUsuario);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create()
    {

        $sql = "INSERT INTO tblUsuario(nome, sobrenome, cpf, senha
        , email, imagem, tomPele)VALUES(?, ?, ?, ?, ?, ?, ?)";

        $extensao = pathinfo($this->_imagem, PATHINFO_EXTENSION);
        $novoNomeArquivo = md5(microtime()) . ".$extensao";

        move_uploaded_file($_FILES["imagem"]["tmp_name"], "../upload/$novoNomeArquivo");


        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_nome);
        $stm->bindValue(2, $this->_sobrenome);
        $stm->bindValue(3, $this->_cpf);
        $stm->bindValue(4, $this->_senha);
        $stm->bindValue(5, $this->_email);
        $stm->bindValue(6, $novoNomeArquivo);
        $stm->bindValue(7, $this->_tomPele);

        if ($stm->execute()) {
            return "Success";
        } else {
            return "Error";
        }
    }

    public function update()
    {

        $sqlImg = "SELECT imagem FROM tblUsuario WHERE idUsuario = ?";

        $stm = $this->_conexao->prepare($sqlImg);
        $stm->bindValue(1, $this->_idUsuario);

        $stm->execute();


        $usuario = $stm->fetchAll(\PDO::FETCH_ASSOC);
        // var_dump($produto[0]["imagem"]);exit;

        unlink("../upload/" . $usuario[0]["imagem"]);

        $nomeArquivo = $_FILES["imagem"]["name"];
        $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);
        $novoNomeArquivo = md5(microtime()) . ".$extensao";
        // echo $novoNomeArquivo;exit;
        move_uploaded_file($_FILES["imagem"]["tmp_name"], "../upload/$novoNomeArquivo");

        $sql = "UPDATE tblUsuario SET
            nome = ?,
            sobrenome = ?,
            cpf = ?,
            senha = ?,
            email = ?,
            imagem = ?,
            tomPele = ?
            WHERE idUsuario = ?";

        $stm = $this->_conexao->prepare($sql);

        $stm->bindValue(1, $this->_nome);
        $stm->bindValue(2, $this->_sobrenome);
        $stm->bindValue(3, $this->_cpf);
        $stm->bindValue(4, $this->_senha);
        $stm->bindValue(5, $this->_email);
        $stm->bindValue(6, $novoNomeArquivo);
        $stm->bindValue(7, $this->_tomPele);
        $stm->bindValue(8, $this->_idUsuario);

        if ($stm->execute()) {
            return "Dados alterados com sucesso!";
        }
    }
}
