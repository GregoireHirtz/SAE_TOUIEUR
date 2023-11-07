<?php
declare(strict_types=1);
namespace touiteur\dispatch;

use Exception;
use touiteur\action\ActionLogin;
use touiteur\action\ActionSignin;
use touiteur\auth\Auth;
use touiteur\auth\Session;
use touiteur\exception\InvalideTypePage;

class Dispatcher{

		
	public function run(string $page): void{
		switch ($page){
			case TYPE_PAGE_ACCUEIL:
				$htmlHeader = '';
				$htmlMain = '';
				$htmlFooter = '';
				echo "ACCUEIL";
				break;

			case TYPE_PAGE_PROFILE:
				break;

			case TYPE_PAGE_TOUIT:
				break;

			case TYPE_PAGE_LOGIN:

				// si déjà connecté ==> accueil
				if (!empty($_SESSION)){
					// redirection vers accueil
					header("Location: /");
				}

				$htmlLoginMessage = '';
				$htmlSigninMessage = '';
				// si validation formulaire
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {

					// -- FORMULAIRE LOGIN -- //

					if ($_GET["action"]=="login"){
						$htmlLoginMessage = ActionLogin::execute();
					}

					// -- FORMULAIRE SIGNIN -- //
					elseif ($_GET["action"]=="signin"){
						$htmlSigninMessage = ActionSignin::execute();
					}
				}
				include 'src/vue/login.html';
				break;


			case TYPE_PAGE_UNLOGIN:
				Session::unloadSession();
				header("Location: /");
				break;

			case TYPE_PAGE_NOTFOUND:
				break;

			default:
				throw new InvalideTypePage($page);
		}
	}
}