<?php

namespace ClassCentral\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CourseType extends AbstractType {

    public function buildForm(FormBuilder $builder, array $options) {
        $builder
            ->add('name')
            ->add('stream') 
            ->add('initiative', null, array('required'=>false, 'empty_value' => true))  
            ->add('institutions', null, array('required'=>false, 'empty_value'=>true)) 
        ;
       
      
    }

    public function getName() {
        return 'classcentral_sitebundle_coursetype';
    }
        
}
