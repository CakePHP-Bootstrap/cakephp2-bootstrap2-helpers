<em>New! Repository for bootstrap v3 available here: https://github.com/Holt59/cakephp-bootstrap3-helpers</em>

CakePHP 2.x Helpers for Bootstrap 2
===================================

CakePHP Helpers to generate HTML with @Twitter Boostrap 2 style.

<i>**Warning:** This plugin version is not maintained anymore.</i>

How to use?
===========

Just add Helper files into your View/Helpers directory and load the helpers in you controller:
```php
public $helpers = array(
    'Html= > 'BoostrapHtml', 
    'Form' => 'BootstrapForm', 
    'Paginator' => 'BoostrapPaginator',
    'Navbar' => 'BootstrapNavbar'
) ;
```

I tried to keep CakePHP helpers style. You can find the documentation directly in the Helpers files.

BootstrapHtmlHelper
===================

This is the subclass of HtmlHelper, with 1 redefinition of method and 3 new methods:

```php
/**
 * 
 * Get crumb lists in a HTML list, with bootstrap like style.
 *
 * @param $options Options for list
 * @param $startText Text to insert before list
 * 
 * Unusable options:
 * 	- Separator
**/
public function getCrumbList($options = array(), $startText = null) ;

/**
 *  
 * Create a Twitter Bootstrap style alert block, containing text.
 *  
 * @param $text The alert text
 * @param $options Options that will be passed to Html::div method
 * 
 * 
 * Available BootstrapHtml options:
 * 	- block: boolean, specify if alert should have 'alert-block' class
 * 	- type: string, type of alert (default, error, info, success)
 *     
**/
public function alert ($text, $options = array()) 

/**
 * 
 * Create a Twitter Bootstrap icon.
 * 
 * @param $icon The type of the icon (search, pencil, etc.)
 * @param $color The color of the icon (black or white)
 * 
**/
public function icon ($icon, $color = 'black') 
    
/**
 *
 * Create a Twitter Bootstrap span label.
 * 
 * @param text The label text
 * @param options Options for span
 *
 * Extra options
 *  - type The type of the label
 *
**/
public function label ($text, $options = array()) 

/**
 *
 * Create a Twitter Bootstrap span badge.
 * 
 * @param text The badge text
 * @param options Options for span
 *
 * Extra options
 *  - type The type of the badge
 *
**/
public function badge ($text, $options = array())

/**
 * 
 * Create a Twitter Bootstrap style progress bar.
 * 
 * @param $widths 
 * 	- The width (in %) of the bar
 * 	- An array of bar, with width and type (info, danger, success, warning) specified for each bar
 * @param $options Options that will be passed to Html::div method (only for main div)
 *  
 * Available BootstrapHtml options:
 * 	- striped: boolean, specify if progress bar should be striped
 * 	- active: boolean, specify if progress bar should be active
 *     
**/
public function progress ($widths, $options = array()) 
```

BootstrapFormHelper
===================

This helper redefines the the most importants method of the FormHelper:

1. BootstrapFormHelper::create now allows you to specify if you want a horizontal, inline or search form (see documentation)
2. BootstrapFormHelper::input now allows you to prepend or append element to your input

button, submit and end methods are redefined to add bootstrap btn style, and allow you to specify which button you want (by specifying "boostrap-size" and "boostrap-type" options).

Two new methods:

1. dropdownButton which allow you to create dropdown button (see documentation)
2. searchForm which allow you to quickly create a search form (see documentation)

A small example:
```php
echo $this->BootstrapForm->create('', array()) ;
echo $this->BootstrapForm->input('text', array(
    'label' => 'Search... ',
    'type' => 'text',
    'prepend' => $this->BootstrapHtml->icon('search'),
    'append' => array(
            $this->BootstrapForm->dropdownButton(__('Action'), array(
                $this->BootstrapHtml->link('Action 1', array()),
                $this->BootstrapHtml->link('Action 2', array()),
                'divider',
                $this->BootstrapHtml->link('Action 3', array())
            ))
    ))) ;
echo $this->BootstrapForm->end() ;
```

Will output:

```html
<form>
    <div style="display:none;">
        <input type="hidden" name="_method" value="POST">
    </div>
    <div class="control-group">
        <label for="ArticleText" class="control-label">Search... </label>
        <div class=" input-prepend input-append">
            <span class="add-on"><i class="icon-search icon-black"></i></span>
            <input name="data[Article][text]" type="text" id="ArticleText">
            <div class="btn-group">
                <button data-toggle="dropdown" class="dropdown-toggle btn">
                    Action
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#">Action 1</a></li>
                    <li><a href="#">Action 2</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Action 3</a></li>
                </ul>
            </div>
        </div>
    </div>
</form>
```

BootstrapNavbarHelper
=====================

A new helper to easily create navigation bar in bootstrap style (http://getbootstrap.com/2.3.2/components.html#navbar).

The helper allow you to create navigation bars with brand block, links (with automatic active class), dropdown menus (and hover menu on dropdown), and other bootstrap stuff with custom options.
All the boostrap navbars (fixed, fixed, inverse, static, responsive) are availables using options.

Copyright and license
=====================

Copyright 2013 Mikaël Capelle.

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this work except in compliance with the License. You may obtain a copy of the License in the LICENSE file, or at:

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
