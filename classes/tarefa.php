<?php

namespace Classes;

class Tarefa
{
    private $id;
    private $descricao;
    private $concluida;

    public function __set($propriedade, $valor)
    {
        $this->$propriedade = $valor;
    }

    public function __get($propriedade)
    {
        return $this->$propriedade;
    }

    public function lista()
    {
        $db = Bd::getConn();
        return $db->query('SELECT * FROM tarefas')->fetchAll();
    }

    public function buscar($id)
    {
        $db = Bd::getConn();
        $stm = $db->prepare('SELECT * FROM tarefas WHERE id=:id');
        $stm->bindValue('id', $id);
        $stm->execute();
        $registro = $stm->fetch();

        if ($registro) {
            $this->id = $registro->id;
            $this->descricao = $registro->descricao;
            $this->concluida = $registro->concluida;
        }
        return $registro;
    }

    public function salvar()
    {
        $db = Bd::getConn();
        if (!$this->id) {
            $stm = $db->prepare('INSERT INTO tarefas (descricao,concluida) VALUES (:descricao,:concluida)');
        } else {
            $stm = $db->prepare('UPDATE tarefas SET descricao=:descricao,concluida=:concluida WHERE id=:id');
            $stm->bindValue('id', $this->id);
        }

        $stm->bindValue('descricao', $this->descricao);
        $stm->bindValue('concluida', $this->concluida);
        $stm->execute();
    }

    public function excluir($id)
    {
        $db = Bd::getConn();

        $stm = $db->prepare('DELETE FROM tarefas WHERE id=:id');
        $stm->bindParam('id', $id);
        $stm->execute();
    }

    public function validar()
    {
        if (strlen($this->descricao) < 3)
            return "Descrição não informada ou inválida.";
    }
}
