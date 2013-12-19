<?php

class CommonAction extends Action{

	public function _initialize() {
		//判断用户是否登录过 通过session
		if (  !isset( $_SESSION['username'] ) || $_SESSION['username'] == '' ||
			$_SESSION['id'] == -1 ) {
			$this->redirect( 'Login/login' );
		}
		// level <-> Group 0 是admin组，1 是User组
		else if (isset( $_SESSION['level'] ) && $_SESSION['level'] == 1 ) {
			//错误，User权限的不能进入
			$this->error( '本用户无权限进入！', A('Home:Index') );
		}
	}
}
?>
