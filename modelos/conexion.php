<?php

class Conexion{

	static public function conectar(){

		$link = new PDO("mysql:host=localhost;dbname=u125044693_prestamos",
			            "u125044693_yeyin25",
			            "26863768Yeya1");

		$link->exec("set names utf8");

		return $link;

	}

}