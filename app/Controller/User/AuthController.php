<?php
/**
 * Created by PhpStorm.
 * User: afshin
 * Date: 11/12/17
 * Time: 11:52 PM
 */

namespace App\Controller\User;


use App\DataAccess\User\UserDataAccess;
use App\Model\User;
use Core\Facades\Auth;
use Respect\Validation\Validator as v;

use App\Controller\Controller;
use Core\Helpers\Hash;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthController extends Controller
{

    public function get_login_step1_Action(Request $request , Response $response)
    {
        return $this->view->render($response, 'auth/login');
    }


    public function post_login_step1_Action(Request $request , Response $response)
    {
        $validate = $this->validator->validate($request,[
            'login' => v::noWhitespace()->notEmpty()
        ]);
        $params = $request->getParams();
        try {

            if (!$validate->failed()) {
                $userOne = UserDataAccess::getUserLoginField($params['login']);
                $token = UserDataAccess::createNewToken($userOne->id);
                /*send code by sms*/
                $code = $token->code;

                if(!isset($userOne->id)) {

//                    $this->logger->info();
                    $this->flash->addMessage('error','User not exist');
                    return $response->withRedirect('login');

                }else{
                    $this->flash->addMessage('success','login');
                    return $response->withRedirect('login');

                }
            }

        } catch (Exception $e) {

        }



        return $this->view->render($response, 'auth/login');
    }


    public function get_register_Action(Request $request , Response $response )
    {
        return $this->view->render($response, 'auth/register');
    }


    public function post_register_Action(Request $request , Response $response)
    {
        $validate = $this->validator->validate($request,[
            'firstname' => v::noWhitespace()->notEmpty()->alpha(),
            'lastname' => v::noWhitespace()->notEmpty()->alpha(),
            'login' => v::noWhitespace()->notEmpty(),
        ]);

        try{

            if(!$validate->failed()){

                $params = $request->getParams();
                $userOne = UserDataAccess::getUserLoginField($params['login']);

                if(!isset($userOne->id)){
                    $user = new User();
                    $hash = new Hash();
                    $user->first_name = $request->getParam('firstname');
                    $user->last_name = $request->getParam('lastname');
                    $user->mobile = $request->getParam('login');
                    $user->api_token = $hash->hash($request->getParam('login'));
                    $user->save();
                    $this->flash->addMessage('info','You have been signed up');
                    return $response->withRedirect('/');
                }else{
                    return $response->withRedirect('/');
                }

            }else{
                $this->flash->addMessage('error','Invalid Inputs');
                return $response->withRedirect('/');
            }

        } catch (Exception $e) {
            // Generate Exception Error
        }
    }



    /* ResourceFull Actions*/
    public function index(Request $request , Response $response)
    {
        return $this->view->render($response, 'auth/login');
    }

}