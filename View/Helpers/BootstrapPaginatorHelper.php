<?php

/**
* Bootstrap Paginator Helper
*
*
* PHP 5
*
*  Licensed under the Apache License, Version 2.0 (the "License");
*  you may not use this file except in compliance with the License.
*  You may obtain a copy of the License at
*
*      http://www.apache.org/licenses/LICENSE-2.0
*
*
* @copyright Copyright (c) Mikaël Capelle (http://mikael-capelle.fr)
* @link http://mikael-capelle.fr
* @package app.View.Helper
* @since Apache v2
* @license http://www.apache.org/licenses/LICENSE-2.0
*/

App::import('Helper', 'Paginator') ;

class BootstrapPaginatorHelper extends PaginatorHelper {

    private function _extractOption ($key, $options, $default = null) {
        if (isset($options[$key])) {
            return $options[$key] ;
        }
        return $default ;
    }
    
    public function first ($title = '<<', $options = array(), $disabledTitle = null, $disabledOptions = array()) {
        $options['tag'] = 'li' ;
        $disabledOptions = array_merge(array('class' => 'disabled', 'disabledTag' => 'a'),
            $disabledOptions) ;
        $disabledOptions['tag'] = 'li' ;
        return parent::first($title, $options, $disabledTitle, $disabledOptions) ;        
    }

    public function prev ($title = '<', $options = array(), $disabledTitle = null, $disabledOptions = array()) {
        $options['tag'] = 'li' ;
        $disabledOptions = array_merge(array('class' => 'disabled', 'disabledTag' => 'a'),
            $disabledOptions) ;
        $disabledOptions['tag'] = 'li' ;
        return parent::prev($title, $options, $disabledTitle, $disabledOptions) ;        
    }
    
    public function next ($title = '>', $options = array(), $disabledTitle = null, $disabledOptions = array()) {
        $options['tag'] = 'li' ;
        $disabledOptions = array_merge(array('class' => 'disabled', 'disabledTag' => 'a'),
            $disabledOptions) ;
        $disabledOptions['tag'] = 'li' ;
        return parent::next($title, $options, $disabledTitle, $disabledOptions) ;        
    }
    
    public function last ($title = '>>', $options = array(), $disabledTitle = null, $disabledOptions = array()) {
        $options['tag'] = 'li' ;
        $disabledOptions = array_merge(array('class' => 'disabled', 'disabledTag' => 'a'),
            $disabledOptions) ;
        $disabledOptions['tag'] = 'li' ;
        return parent::last($title, $options, $disabledTitle, $disabledOptions) ;        
    }
    
    public function numbers ($options = array()) {
    
        $default = array(
            'currentTag' => 'a', 
            'separator' => '', 
            'currentClass' => 'active', 
            'disabledTag' => 'a') ;
        $options = array_merge($default, $options) ;
        $options['tag'] = 'li' ;
          
        $options['before'] = '<div class="pagination"><ul>' ;
        $options['after'] = '</ul></div>' ;
                
        return parent::numbers ($options) ;
    }

}

?>
