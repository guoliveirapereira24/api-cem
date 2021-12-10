<?php

class ControllerUsuario{

    private $_method;
    private $_modelUsuario;
    private $_idUsuario;

    public function __construct($model)
    {
        $this->_modelUsuario = $model;
        $this->_method = $_SERVER['REQUEST_METHOD'];

        $json = file_get_contents("php://input");
        $dadosUsuario = json_decode($json);

        $this->_idUsuario = $dadosUsuario->idUsuario ?? $_POST["idUsuario"];

    }

    function router(){

        switch ($this->_method) {
            case 'GET':
                return $this->_modelUsuario->findById();
                break;
            
            case 'POST':
                if ($this->_idUsuario) {
                    return $this->_modelUsuario->update();
                    break;
                }
                return $this->_modelUsuario->create();
                break;

            default:
                # code...
                break;
        }

    }



}

?>