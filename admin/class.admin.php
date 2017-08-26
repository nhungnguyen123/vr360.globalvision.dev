<?php
class adminClass
{
	public function __construct()
	{
		// server should keep session data for AT LEAST 1 hour
		ini_set('session.gc_maxlifetime', 3600);
		 
		// each client should remember their session id for EXACTLY 1 hour
		session_set_cookie_params(3600);
		@session_start ();
	}
	public function isLogin()
	{
		
		if ( isset($_SESSION['isAdminLogin']) && $_SESSION['isAdminLogin'] == 'true' ) return true;
		else return false;
	}
	public function login()
	{
		if(isset($_POST['username']) && $_POST['username'] == 'admin' && isset($_POST['pass']))
		{
			//demo only
			
			if($_POST['pass'] == 'vr360@gvadmin')
			{
				$_SESSION['isAdminLogin'] = 'true';
				return true;
			}				
		}
		else return false;
	}
	public function redirect($to)
	{
		header ( 'Location: /'.$to, true, '301' );
		die();
	}
	public function f_401()
	{
		header ( '401 Unauthorized' );
		
		return $this;
	}
	public function echoLoginForm()
	{
		echo <<<'EOF'
<div style="width: 100%; height: 100%">

		<div
			style="left: 50%; margin: -150px 0 0 -300px; padding-right: 0; position: absolute; top: 50%; text-align: center;">
			<a href="http://globalvision.ch" target="_blank"><img id="gvBigLogo"
				src="./../gv_logo.png" style="margin-bottom: 54px;" /></a>
			<form
				action="./admin.php"
				method="post" style="margin: auto;">
				<input class='m8' type="text" name="username" placeholder="Username"
					size="80" /><br /> <input class='m8' type="password" name="pass"
					size="80" placeholder="Password" /><br /> <input class='m81'
					type="submit" value="Sign in" />
			</form>
		</div>

	</div>

</body>
</html>
EOF;
		return $this;
	}
	public function haveAdminLoginData()
	{
		if(isset($_POST['username']) && $_POST['username'] == 'admin' && isset($_POST['pass'])) return true;
		else return false;
	}
}