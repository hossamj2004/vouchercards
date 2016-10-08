<?php
/**
 * login form ( it also contain the brute force blocker )
 */
namespace app\controllers\superadmin\superforms;
use Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\Password,
	Phalcon\Forms\Element\Submit,
	Phalcon\Forms\Element\Check,
	Phalcon\Forms\Element\Hidden,
	Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\Identical,
    Phalcon\Forms\Element\Select;
class LoginForm extends adminForm
{
    public function initialize()
    {
        $this->add($this->csrfField());
        $this->add($this->defaultTextField('email','Email'));
        $this->add($this->defaultPasswordField('password','Password'));
		$this->add(new Submit('submit', array(
			'value' => _('Login')
		)));
    }

}