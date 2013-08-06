<?php

/**
* Bootstrap Navbar Helper
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

App::import('Helper', 'Html') ;
App::import('Routing', 'Router') ;

class BootstrapNavbarHelper extends AppHelper {

    /** How to use ? **/
    
    /** 
    
        create () ;
            link () ;
            link () ;
            beginMenu () ;
                link () ;
                link () ;
                beginMenu () ;
                    link () ;
                    divider () ;
                    link () ;
                endMenu () ;
            endMenu () ;
            link () ;
            text () ;
            link () ;
        end () ;
        
        compile () ; // Only this call create and return the navbar
        
    **/

    public $helpers = array('Html') ;
    
    private $options = array() ;
    private $fixed = false ;
    private $static = false ;
    private $responsive = false ;
    private $inverse = false ;
    
    /** Specify if we are currently in a submenu or not. If in submenu,
    this must be an array like: array('name' => '', 'url' => '', 'menu' => array()). **/
    private $currentMenu = null ;
    /** Same but for hover menu. **/
    private $currentSubMenu = null ;
    
    private $brand = null ;
    private $navs = array () ;
    
    /**
    
        Create a new navbar.
        
        @param options Options to customize the bootstrap navbar
        
        Options:
            - fixed: false, 'top', 'bottom'
            - static: false, true (useless if fixed != false)
            - responsive: false, true
            - inverse: false, true
            - options: array(), options for main navbar div
    
    **/
    public function create ($options = array()) {
        $this->fixed = $this->_extractOption('fixed', $options, false) ;
        unset($options['fixed']) ;
        $this->responsive = $this->_extractOption('responsive', $options, false) ;
        unset($options['responsive']) ;
        $this->static = $this->_extractOption('static', $options, false) ;
        unset($options['static']) ;
        $this->inverse = $this->_extractOption('inverse', $options, false) ;
        unset($options['inverse']) ;
        $this->options = $options ;
    }
    
    /**
    
        Create the brand link of the navbar.
        
        @param name The brand link text
        @param url The brand link URL (default '/')
        @param collapse true if you want the brand to be collapsed 
            with responsive design (default false)
        @param options Options passed to link method
    
    **/
    public function brand ($name, $url = '/', $collapse = false, $options = array()) {
        $this->brand = array(
            'text' => $name,
            'url' => $url,
            'collapse' => $collapse,
            'options' => $options
        ) ;
    }
    
    /**
    
        Create a new link
    
    **/
    public function link ($name, $url, $options = array()) {
        $value = array(
            'text' => $name,
            'url' => $url
        ) ;
        $this->_addToCurrent('link', $value, $options) ;
    }
    
    public function divider ($options = array()) {
        $this->_addToCurrent('divider', array(), $options) ;
    }

    public function text ($text, $options = array()) {
        $tag = $this->_extractOption('wrap', $options, 'p') ;
        unset($options['wrap']) ;
        $value = array(
            'wrap' => $tag,
            'text' => $text,
        ) ;
        $this->_addToCurrent('text', $value, $options) ;
    }
    
    /**
    
        Start a new menu, 2 levels: If not in submenu, create a dropdown menu,
        oterwize create hover menu.
        
        @param name The name of the menu
        @param url A URL for the menu (default null)
        @param options Options for the menu
        
        Options:
            - pull: 'left', 'right' (default 'left')
            - options: array, options for the ul div
       
    **/
    public function beginMenu ($name, $url = null, $options = array()) {
        $default = array(
            'type' => 'menu',
            'text' => $name,
            'url' => $url,
            'menu' => array()
        ) ;
        $value = array_merge($this->_extractValue($options), $default) ;
        if ($this->currentMenu === null) {
            $this->currentMenu = $value ;
        }
        else if ($this->currentSubMenu === null) {
            $value['type'] = 'smenu' ;
            $this->currentSubMenu = $value ;
        }
    }
    
    public function endMenu () {
        if ($this->currentSubMenu !== null) {
            $this->currentMenu['menu'][] = $this->currentSubMenu ;
            $this->currentSubMenu = null ;
        }
        else if ($this->currentMenu !== null) {
            $this->navs[] = $this->currentMenu ;
            $this->currentMenu = null ;
        }
    }

    /**
        
        End a navbar.
        
        @param compile If true, compile the navbar and return
    
    **/
    public function end ($compile = false) {
    
        if ($compile) {
            return $this->compile () ;
        }
    }
    
    private function compileNavBlock ($nav) {
        $inner = '' ;
        $class = '' ;
        switch ($nav['type']) {
        case 'text':
            $nav['options'] = $this->_addClass($nav['options'], 'navbar-text') ;
            $inner = $this->Html->tag($nav['wrap'], $nav['text'], $nav['options']) ;
        break ;
        case 'link':
            $active = $nav['active'] === 'auto' ? 
                Router::currentRoute()->match(Router::parse(Router::normalize($nav['url']))) : $nav['active'] ;
            $disabled = $nav['disabled'] ;
            $inner = $this->Html->link($nav['text'], $nav['url'], $nav['options']) ;
        break ;
        case 'menu':
        case 'smenu':
            $res = $this->compileMenu($nav) ;
            $inner = $res['inner'] ;
            $active = $nav['active'] === 'auto' ? $res['active'] : $nav['active'] ;
            $disabled = $nav['disabled'] ;
            $class = $res['class'];
        break ;
        case 'divider':
            $class = 'divider' ;
        break ;
        }
        return array(
            'inner' => $inner,
            'class' => $class,
            'active' => isset($active) && $active,
            'disabled' => isset($disabled) && $disabled
        ) ;
    }
    
    private function compileMenu ($menu) {
        if ($menu['type'] === 'menu') {
            $button = $this->Html->link($menu['text'], $menu['url'] ? $menu['url'] : '#', array(
                'class' => 'dropdown-toggle',
                'data-toggle' => 'dropdown'
            )) ;
        }
        else {
            $button = $this->Html->link($menu['text'], $menu['url'] ? $menu['url'] : '#', array(
                'tabindex' => -1
            )) ;
        }
        $active = false ;
        $link = array() ;
        foreach ($menu['menu'] as $m) {
            $res = $this->compileNavBlock($m) ;
            if ($res['active']) {
                $active = true ;
                $res = $this->_addClass($res, 'active') ;
            }
            $link[] = $this->Html->tag('li', $res['inner'], $res['class'] ? array('class' => $res['class']) : array()) ;
        }
        $list = $this->Html->tag('ul', implode('', $link), array(
            'class' => 'dropdown-menu'
        )) ;
        $class = ($menu['type'] === 'menu') ? 'dropdown' : 'dropdown-submenu' ;
        if ($menu['pull'] !== 'auto') {
            $class .= ' pull-'.$menu['pull'] ;
        }
        return array(
            'active' => $active, 
            'inner' => $button.$list,
            'class' => $class,
            'disabled' => $menu['disabled']
        ) ;
    }
    
    /**
    
        Compile and returns the current navbar.
    
    **/
    public function compile () {
        $htmls = array() ;
        $ul = false ;
        foreach ($this->navs as $nav) {
            if ($ul && $nav['pull'] != 'auto' && $nav['pull'] != $ul) {
                $htmls[] = '</ul>' ;
                $ul = false ;
            }
            if (!$ul && $nav['pull'] === 'auto') {
                $ul = 'left' ;
                $htmls[] = '<ul class="nav">' ;
            }
            if (!$ul && $nav['pull'] !== 'auto') {
                $ul = $nav['pull'] ;
                $htmls[] = '<ul class="nav pull-'.$nav['pull'].'">' ;
            }
            $res = $this->compileNavBlock($nav) ;
            $options = array('class' => $res['class']) ;
            if ($res['active']) {
                $options = $this->_addClass($options, 'active') ;
            }
            if ($res['disabled']) {
                $options = $this->_addClass($options, 'disabled') ;
            }
            $htmls[] = $this->Html->tag('li', $res['inner'], $options) ;
        }
        if ($ul) {
            $ul = false ;
            $htmls[] = '</ul>' ;
        }
        
        /** Generate options for outer div. **/
        $this->options = $this->_addClass($this->options, 'navbar') ;
        if ($this->fixed !== false) {
            $this->options = $this->_addClass($this->options, 'navbar-fixed-'.$this->fixed) ;
        }
        else if ($this->static !== false) {
            $this->options = $this->_addClass($this->options, 'navbar-static-top') ;
        }
        if ($this->inverse !== false) {
            $this->options = $this->_addClass($this->options , 'navbar-inverse') ;
        }
        
        $inner = '' ;
        
        $brand = $this->brand !== null ? 
            $this->Html->link($this->brand['text'], $this->brand['url'], array('class' => 'brand')) : null ;
        $inner = implode('', $htmls) ;
        
        if ($this->responsive) {
            $button = $this->Html->tag('a', 
                implode('', array(
                    $this->Html->tag('span', '', array('class' => 'icon-bar')),
                    $this->Html->tag('span', '', array('class' => 'icon-bar')),
                    $this->Html->tag('span', '', array('class' => 'icon-bar'))
                )),
                array(
                    'class' => 'btn btn-navbar',
                    'data-toggle' => 'collapse',
                    'data-target' => '.nav-collapse'
                )
            ) ;
            if ($this->brand !== null && $this->brand['collapse']) {
                $inner = $brand.$inner ;
            }
            $inner = $this->Html->tag('div', $inner, array('class' => 'nav-collapse collapse')) ;
            if ($this->brand !== null && !$this->brand['collapse']) {
                $inner = $brand.$inner ;
            }
            $inner = $button.$inner ;
        }
        else if ($this->brand !== null) {
            $inner = $brand.$inner ;
        }
        
        /** Add container. **/
        $inner = $this->Html->tag('div', $inner, array('class' => 'container')) ;
        
        /** Add inner. **/
        $inner = $this->Html->tag('div', $inner, array('class' => 'navbar-inner')) ;
                
        /** Add and return outer div. **/
        return $this->Html->tag('div', $inner, $this->options) ;
        
    }
    
    private function _extractOption ($key, $options, $default = null) {
        if (isset($options[$key])) {
            return $options[$key] ;
        }
        return $default ;
    }
    
    private function _addClass ($options, $class) {
        if (!isset($options['class'])) {
            $options['class'] = $class ;
        }
        else {
            $options['class'] .= ' '.$class ;
        }
        return $options ;
    }
    
    private function _extractValue ($options) {
        $value = array () ;
        $value['pull'] = $this->_extractOption('pull', $options, 'auto') ;
        unset ($options['pull']) ;
        $value['disabled'] = $this->_extractOption('disabled', $options, false) ;
        unset ($options['disabled']) ;
        $value['active'] = $this->_extractOption('disabled', $options, 'auto') ;
        unset ($options['active']) ;
        $value['options'] = $options ;
        return $value ;
    }
    
    private function _addToCurrent ($type, $value, $options = array()) {
        $value = array_merge($this->_extractValue($options), $value) ;
        $value['type'] = $type ;
        if ($this->currentSubMenu !== null) {
            $this->currentSubMenu['menu'][] = $value ;
        }
        else if ($this->currentMenu !== null) {
            $this->currentMenu['menu'][] = $value ;
        }
        else {
            $this->navs[] = $value ;
        }
    }
    
        
}

?>