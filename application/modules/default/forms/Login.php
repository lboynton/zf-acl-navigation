<?php

class Default_Form_Login extends Zend_Form
{

    public function init()
    {
		$this->addElement(
            'text',
            'username',
            array(
                'label'     => 'Username',
				'class'		=> 'text',
                'size'      => 15,
                'required'  => true,
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
                'size'      => 15,
                'maxlength' => 15,
                'required'  => true,
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
            'submit',
            'Login',
            array(
                'label'  => 'Login',
				'class'  => 'submit',
                'ignore' => true
                )
            );

		$this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
            array('Description', array('placement' => 'prepend', 'class' => 'error')),
            'Form'
        ));
    }


}

