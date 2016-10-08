<?php
/**
 * Created by PhpStorm.
 * User: Hossam
 * Date: 16/4/15
 *
 * This controller handle login and logout pages
 *
 */

namespace app\controllers\superadmin;

class AuthenticationController extends SuperAdminBaseController{
    /**
     * Starts a session in the admin backend
     */
    public function loginAction()
    {

        $LoginForm = new superforms\LoginForm();
        $this->forms->set('LoginForm', $LoginForm);
        if ( $this->request->isPost() ) {
            if ($LoginForm->isValid($this->request->getPost()) == false) {
                foreach ($LoginForm->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                if ($this->superAdminSystem->login($this->request->getPost('email'), $this->request->getPost('password')))
                {
                    $this->response->redirect($this->superAdminSystem->getHomeLink());
                }
                else {
                    $this->flash->error('Login failed , wrong username/password or account has not been activated yet.');
                }
            }
        }
    }
    /**
     * run the logout function that remove user session
     */
    public function logoutAction()
    {
        $this->superAdminSystem->logout();
        $this->response->redirect($this->superAdminSystem->getHomeLink());
    }
}
