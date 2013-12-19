<?php 
	/**
	* 登陆页面
	*/
	class LoginAction extends Action {
		
		public function login() {

			if ( isset( $_SESSION['username'] ) && $_SESSION['id'] > 0 ) {
				$this->redirect( 'Index/index' );
			}else{
				$this->display();
			}
		}

		public function doLogin() {
			//接受值
			//判断用户在数据库中是否存在
			//存在则登录
			//否则这提示错误
			if (isset($_POST['username']) && isset($_POST['password']) &&
				isset($_POST['code'])) {

				$username = $_POST['username'];
				$password = $_POST['password'];
				$code = $_POST['code'];
			}

			if ( md5( $code ) != $_SESSION['verify'] ) {
				$this->error( '验证码不正确', U('Login/login') );
			}

			$user = D( 'User' );

			$where['username'] = $username;
			$where['pwd'] = $password;
			$where['Group'] = 0;

			$arr = $user->field( 'Pid,Group' )->where( $where )->find();

			if ( $arr ) {
				$_SESSION['username'] = $username;	//用户名
				$_SESSION['id'] = $arr['Pid'];		//用户的ID,判断是否登录什么的
				$_SESSION['level'] = $arr['Group'];	//用户的权限
				$_SESSION['machineModel'] = 0;		//机器型号，便于多个分页模板使用

				$this->success( '用户登录成功', U( 'Index/index' ) );
			} else {
				$this->error( '该用户不存在或者密码不正确', U('Login/login') );
			}
		}

		public function doLogout() {
			$_SESSION['username'] = array();
			$_SESSION['id'] = -1;
			$_SESSION['level'] = 3;
			$_SESSION['machineModel'] = 0;		//机器型号，便于多个分页模板使用
			
			if ( isset( $_COOKIE[session_name()] ) ) {
				setcookie( session_name(), '', time()-1, '/' );
			}
			session_destroy();
			$this->redirect( 'Login/login' );
		}

			//验证码
	    public function code() {
	        $w = isset( $_GET['w'] ) ? $_GET['w'] : 30;
	        $h = isset( $_GET['h'] ) ? $_GET['h'] : 30;

	        import( 'ORG.Util.Image' );
	        Image::buildImageVerify( 6, 1, 'png', $w, $h );
	    }
	}
 ?>