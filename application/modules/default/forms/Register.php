<?php

class Default_Form_Register extends Zend_Form
{
	public function init()
	{
		$this->addElement(
            'text',
            'username',
            array(
                'label'     => 'Username',
				'class'		=> 'text',
                'required'  => true,
				'tabindex'  => 1,
                'filters'   => array(
                    'StringTrim',
                    ),
                'validators' => array(
                    ),
				)
            );
		
		$this->addElement(
            'password',
            'password',
            array(
                'label'     => 'Password',
				'class'		=> 'text',
                'required'  => true,
				'tabindex'  => 5,
                'filters'   => array(
                    'StringTrim',
                    ),
                'validators' => array(
                    array('StringLength', true, array(5, 45)),
                    'Alnum',
                    ),
                )
            );
		
		$this->addElement(
            'password',
            'passwordConfirm',
            array(
                'label'     => 'Re-enter Password',
				'class'		=> 'text',
                'required'  => true,
				'tabindex'  => 6,
                'filters'   => array(
                    'StringTrim',
                    ),
                'validators' => array(
					array('identical', false, array('token' => 'password'))
                    ),
                )
            );	

		$this->addElement(
            'submit',
            'register',
            array(
                'label'  => 'Register',
                'ignore' => true,
                )
            );
	}

}

