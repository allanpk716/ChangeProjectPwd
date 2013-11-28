<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {

    public function index(){
	
    	$this->display();
    }

    public function changePwd() {

		if (isset($_POST['username']) && isset($_POST['password_old']) && isset($_POST['password_new1']) && 
			isset($_POST['password_new2']) && isset($_POST['code'])) {
			$username = $_POST['username'];
			$password_old = $_POST['password_old'];
			$password_new1 = $_POST['password_new1'];
			$password_new2 = $_POST['password_new2'];
			$code = $_POST['code'];
		}

		if ($password_new1 != $password_new2) {
			$this->error( '新密码不一致！',U('Index/index') );
		}
    	
		if ( md5( $code ) != $_SESSION['verify'] ) {
			$this->error( '验证码不正确！',U('Index/index') );
		}

		$user = D( 'User' );
		$where['username'] = $username;
		$where['pwd'] = $password_old;

		$arr = $user->where( $where )->find();

		if ($arr) {

			//验证原始密码正确，那么就可以执行修改密码的程序了
			$exepath = "E:\Work_Space\AddUserAndGrouping\AddUserAndGrouping\bin\Debug\AddUserAndGrouping.exe ChangeUserPwd ";

			exec($exepath." ".$username." ".$password_new1,$outArray,$var);

			if ($var > 0) {

				//当windows中的用户密码修改成功的时候，那么才进行本数据库中的密码修改
				$pid = $arr["Pid"];

				$data["Pid"] = $pid;
				$data["username"] = $username;
				$data["pwd"] = $password_new1;
				$user->save($data);

				$this->success( '修改密码成功。');
			}
			else
				$this->error( '修改密码失败，请呼叫管理员!',U('Index/index')  );
			
		}
		else
			$this->error( '该用户不存在或者原密码不正确!请呼叫管理员!',U('Index/index')  );
    }

    //验证码
    public function code() {
        $w = isset( $_GET['w'] ) ? $_GET['w'] : 30;
        $h = isset( $_GET['h'] ) ? $_GET['h'] : 30;

        import( 'ORG.Util.Image' );
        Image::buildImageVerify( 4, 1, 'png', $w, $h );
    }

    private function addUser($username) {

    	$User = M("User"); // 实例化User对象
		$data['username'] = $username;
		$data['pwd'] = '123456';
		$User->add($data);
    }

    //插入初始化用户数据
    private function insertUser() {

    	$out = file("E:\\userlist.txt");
		$icout = count($out);

		//循环添加
		for ($i=0; $i < $icout; $i++) { 
			
			//出去空格和换行符
			$aa = preg_replace("/[\s]{2,}/","",$out[$i]);
			$this->addUser($aa);
		}
    }
}