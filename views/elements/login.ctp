<?php
	if($this->Session->read('FB')){
		$logout = isset($logout) ? $logout : array();
		echo $this->Facebook->logout($logout);
	}
	else{
		$login = isset($login) ? $login : array();
		echo $this->Facebook->login($login);
	}