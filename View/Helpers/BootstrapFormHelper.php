<?php

App::import('Helper', 'Form') ;

class BootstrapFormHelper extends FormHelper {

    public $helpers = array('Html') ;
    
    public $horizontal = false ;
    public $inline = false ;
    public $search = false ;
    
    private $buttonTypes = array('primary', 'info', 'success', 'warning', 'danger', 'inverse', 'link') ;
    private $buttonSizes = array('mini', 'small', 'large') ;
    
    /**
    
        Add classes to options according to values of bootstrap-type and bootstrap-size for button.
        
        @param $options The initial options with bootstrap-type and/or bootstrat-size values
        
        @return The new options with class values (btn, and btn-* according to initial options)
    
    **/
    private function addButtonClasses ($options) {
        $options = $this->addClass($options, 'btn') ;
        foreach ($this->buttonTypes as $type) {
            if (isset($options['bootstrap-type']) && $options['bootstrap-type'] == $type) {
                $options = $this->addClass($options, 'btn-'.$type) ;
                break ;
            }
        }
        foreach ($this->buttonSizes as $size) {
            if (isset($options['bootstrap-size']) && $options['bootstrap-size'] == $size) {
                $options = $this->addClass($options, 'btn-'.$size) ;
                break ;
            }
        }
        unset($options['bootstrap-size']) ;
        unset($options['bootstrap-type']) ;
        return $options ;
    }
	
    /**
    
        Create a Twitter Bootstrap like form. 
        
        New options available:
            - horizontal: boolean, specify if the form is horizontal
            - inline: boolean, specify if the form is inline
            - search: boolean, specify if the form is a search form
        
        Unusable options:
            - inputDefaults
        
        @param $model The model corresponding to the form
        @param $options Options to customize the form
        
        @return The HTML tags corresponding to the openning of the form
    
    **/
    public function create($model = null, $options = array()) {
        $this->horizontal = $this->_extractOption('horizontal', $options, false);
		unset($options['horizontal']);
        $this->search = $this->_extractOption('search', $options, false) ;
        unset($options['search']) ;
        $this->inline = $this->_extractOption('inline', $options, false) ;
        unset($options['inline']) ;
		if ($this->horizontal) {
			$options = $this->addClass($options, 'form-horizontal') ;
		}
        else if ($this->inline) {
            $options = $this->addClass($options, 'form-inline') ;
        }
        if ($this->search) {
            $options = $this->addClass($options, 'form-search') ;
        }
        $options['inputDefaults'] = array(
            'div' => $this->inline ? false : array(
                'class' => 'control-group'
            )
        ) ;
		return parent::create($model, $options) ;
	}
    
    /**
    
        Display a error message (Twitter Bootstrap like).
        
        The error is wrapped in a <span> tag, with a class
        according to the form type (help-inline or help-block).
        
        Unusable options:
            - wrap
    
    **/
    public function error($field, $text = null, $options = array()) {
        $this->setEntity($field);
        $optField = $this->_magicOptions(array()) ;
        $options['wrap'] = 'span' ;
        $errorClass = 'help-block' ;
        if ($this->horizontal && $optField['type'] != 'checkbox') {
            $errorClass = 'help-inline' ;
        }
        $options = $this->addClass($options, $errorClass) ;
        return parent::error($field, $text, $options) ;
    }
    
    /**
    
        Display a label message (Twitter Boostrap like).
    
    **/
    public function label($fieldName = null, $text = null, $options = array()) {
        $this->setEntity($fieldName);
        $optField = $this->_magicOptions(array()) ;
        if ($optField['type'] != 'checkbox') {
            $options = $this->addClass($options, 'control-label') ;
        }
        return parent::label($fieldName, $text, $options) ;
    }
	
    /** 
    
        Display an input block (Twitter Boostrap Like).
       
        New options:
            - prepend: 
                -> string: Add <span class="add-on"> before the input
                -> array: Add elements in array before inputs
            - append: Same as prepend except it add elements after input
       
    **/
	public function input($fieldName, $options = array()) {
    
        $prepend = $this->_extractOption('prepend', $options, null) ;
        unset ($options['prepend']) ;
        $append = $this->_extractOption('append', $options, null) ;
        unset ($options['append']) ;
        $before = $this->_extractOption('before', $options, '') ;
        $after = $this->_extractOption('after', $options, '') ;
        $between = $this->_extractOption('between', $options, '') ;
        $label = $this->_extractOption('label', $options, false) ;
        
        $this->setEntity($fieldName);
        $options = $this->_parseOptions($options) ;
        $options['format'] = array('label', 'before', 'input', 'between', 'error', 'after') ;

        $beforeClass = '' ;
                
        if ($options['type'] == 'checkbox') {
            $before = ($this->horizontal ? '<div class="controls">' : '').'<label class="checkbox">'.$before ;
            $between = $between.'</label>' ;
            $options['format'] = array('before', 'input', 'label', 'between', 'error', 'after') ;
            $after = $after.($this->horizontal ? '</div>' : '') ;
        }
        else if ($options['type'] == 'radio') {
            $options['legend'] = false ;
            $before = $this->label($fieldName)
                .($this->horizontal ? '<div class="controls">' : '').'<label class="radio">'.$before ;
            $between = $between.'</label>' ;
            $options['format'] = array('before', 'input', 'label', 'between', 'error', 'after') ;
            $after = $after.($this->horizontal ? '</div>' : '') ;
        }
        else if ($this->horizontal) {
            $beforeClass .= ' controls' ;
        }
        else if ($this->inline && !$this->search && !$label) {
            $options['label'] = false ;
        }
        if ($prepend) {
            $beforeClass .= ' input-prepend' ;
            if (is_string($prepend)) {
                $before .= '<span class="add-on">'.$prepend.'</span>' ;
            }
            if (is_array($prepend)) {
                foreach ($prepend as $pre) {
                    $before .= $pre ;
                }
            }
        }
        if ($append) {
            $beforeClass .= ' input-append' ;
            if (is_string($append)) {
                $between = '<span class="add-on">'.$append.'</span>'.$between ;
            }
            if (is_array($append)) {
                foreach ($append as $apd) {
                    $between = $apd.$between ;
                }
            }
        }
        
        if ($beforeClass) {
            $before = '<div class="'.$beforeClass.'">'.$before ;
            $after = $after.'</div>' ;
        }
        
        $options['before'] = $before ; 
        $options['after'] = $after ;
        $options['between'] = $between ;
        
		return parent::input($fieldName, $options) ;
	}
    
    /**
    
        Create a Twitter Like button.
        
        New options:
            - bootstrap-type: Twitter bootstrap button type (primary, danger, info, etc.)
            - bootstrap-size: Twitter bootstrap button size (mini, small, large)
    
    **/
    public function button($title, $options = array()) {
        $options = $this->addButtonClasses($options) ;
        return parent::button($title, $options) ;
    }
    
    /**
    
        Create a twitter bootstrap dropdown button.
        
        @param $title The text in the button
        @param $menu HTML tags corresponding to menu options (which will be wrapped
                     into <li> tag). To add separator, pass 'divider'.
        @param $options Options for button
    
    **/
    public function dropdownButton ($title, $menu = array(), $options = array()) {
    
        $options['type'] = false ;
        $options['data-toggle'] = 'dropdown' ;
        $options = $this->addClass($options, "dropdown-toggle") ;
        
        $outPut = '<div class="btn-group">' ;
        $outPut .= $this->button($title.'<span class="caret"></span>', $options) ;
        $outPut .= '<ul class="dropdown-menu">' ;
        foreach ($menu as $action) {
            if ($action === 'divider') {
                $outPut .= '<li class="divider"></li>' ;
            }
            else {
                $outPut .= '<li>'.$action.'</li>' ;
            }
        }
        $outPut .= '</ul></div>' ;
        return $outPut ;
    }
    
    /**
    
        Create a Twitter Like submit input.
        
        New options:
            - bootstrap-type: Twitter bootstrap button type (primary, danger, info, etc.)
            - bootstrap-size: Twitter bootstrap button size (mini, small, large)
            
        Unusable options:
            - div
    
    **/    
    public function submit($caption = null, $options = array()) {
        if (!isset($options['div'])) {
            $options['div'] = false ;
        }
        $options = $this->addButtonClasses($options) ;
        return parent::submit($caption, $options) ;
    }
	
    /**
    
        End a form, Twitter Bootstrap like.
        
        New options:
            - bootstrap-type: Twitter bootstrap button type (primary, danger, info, etc.)
            - bootstrap-size: Twitter bootstrap button size (mini, small, large)
    
    **/
	public function end ($options = null) {
		if ($options == null) {
			return parent::end() ;
		}
		if (is_string($options)) {
			$options = array('label' => $options) ;
		}
        if (!$this->inline) {
            if (!array_key_exists('div', $options)) {
                $options['div'] = array() ;
            }
            $options['div']['class'] = 'form-actions' ;
        }
		return parent::end($options) ;
	}
    
    /** SPECIAL FORM **/
    
    /**
    
        Create a basic bootstrap search form.
        
        @param $model The model of the form
        @param $options The options that will be pass to the BootstrapForm::create method
        
        Extra options:
            - label: The input label (default false)
            - placeholder: The input placeholder (default "Search... ")
            - button: The search button text (default: "Search")
    
    **/
    public function searchForm ($model = null, $options = array()) {
        
        $label = $this->_extractOption('label', $options, false) ;
        unset($options['label']) ;
        $placeholder = $this->_extractOption('placeholder', $options, 'Search... ') ;
        unset($options['placeholder']) ;
        $button = $this->_extractOption('button', $options, 'Search') ;
        unset($options['button']) ;
        
        $output = '' ;
        
        $output .= $this->create($model, array_merge(array('search' => true, 'inline' => (bool)$label), $options)) ;
        $output .= $this->input('search', array(
            'label' => $label,
            'placeholder' => $placeholder,
            'append' => array(
                $this->button($button)
            )
        )) ;
        $output .= $this->end() ;
    
        return $output ;
    }

}

?>