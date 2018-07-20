<?
/*FOREST AUTH*/
class AuthClassUser {
    private $_login;
  	private $_password;

  public function construct($what, $type, $keyaccess = NULL){
      $bds = new readbd;
  		global $getdata;
      if(empty($keyaccess)){
    		$bds->readglobalfunction('login', 'users', $what, $type);
    		$this->_login = $getdata;
    		$bds->readglobalfunction('password', 'users', $what, $type);
    		$this->_password = $getdata;
      }else{
        $this->_login = $bds->readglobal2("login", "forestusers", "TempKey", $keyaccess, true);
        $this->_password = $bds->readglobal2("password", "forestusers", "TempKey", $keyaccess, true);
      }
  	}

      public function isAuth() {
          if (isset($_SESSION["is_authuser"])) {
              return $_SESSION["is_authuser"];
          }
          else return false;
      }

      /**
       * @param string $login
       * @param string $passwors
       */
      public function auth($login, $passwors, $keyaccess = NULL) {

          if(!empty($keyaccess)){
            $bds = new readbd;
            global $getdata;
            $login = $bds->readglobal2("login", "forestusers", "TempKey", $keyaccess, true);
            $passwors = $bds->readglobal2("password", "forestusers", "TempKey", $keyaccess, true);
          }

          if ($login == $this->_login && $passwors == $this->_password) {
            $_SESSION["is_authuser"] = true;
            $_SESSION["loginuser"] = $login;

            if(!empty($keyaccess)){
              $bds = new readbd;
              global $getdata;
              $bds->updatebd("forestusers", "TempKey", "0", "login", $login);
            }
            
            return true;
          }
          else {$_SESSION["is_authuser"] = false;
              return false;
          }
      }

      public function getLogin() {
          if ($this->isAuth()) {
              return $_SESSION["loginuser"];
          }
      }


      public function out() {
          $_SESSION = array();
          session_destroy();
      }

      function checkout(){
        global $infob, $login_get, $action, $login, $auth;
        if(isset($_GET['login'])){
          $login_get = $_GET['login'];
        }

        if(isset($_GET['action'])){
          $action = $_GET['action'];
        }

        if(isset($_SESSION["loginuser"])){
          $login = $_SESSION["loginuser"];
        }

        if (isset($_GET['action']) && $_GET["action"] == 'logout')
        {
          $infob->writestat('Success Logout -> '.$login, 'system/core/journal.mcj');
          $auth->out(); header("Location: ?exit=0");
        }
      }
  }
  unset($bds);
?>
