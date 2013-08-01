cakephp-bootstrap-helpers
=========================

CakePHP Helpers to generate HTML with @Twitter Boostrap style.

It's a work in progress, if you want to add any kind of bootstrap components, just do it!

If you want a component but you don't really know how to do, do not hesitate to contact me!

How to use?
===========

Just add Helper files into your View/Helpers directory and load the helpers in you controller:
```public $helpers = array('BoostrapHtml', 'BootstrapForm', 'BoostrapPaginator') ;```

I tried to keep CakePHP helpers style. You can find the documentation directly in the Helpers files.

BootstrapHtmlHelper
===================

This is the subclass of HtmlHelper, with 1 redefinition of method and 3 new methods:

<h3>getCrumbLists</h3>
Function now returns a bootstrap style breadcrumbs: http://getbootstrap.com/2.3.2/components.html#breadcrumbs
  
<h3>alert</h3>
Function which returns a bootstrap alert message: http://getbootstrap.com/2.3.2/components.html#alerts
```
/**
    
    Create an Twitter Bootstrap style alert block, containing text.
    
    @param $text The alert text
    @param $options Options that will be passed to Html::div method
    
    Available BootstrapHtml options:
        - block: boolean, specify if alert should have 'alert-block' class
        - type: string, type of alert (default, error, info, success)

**/
BoostrapHtmlHelper::alert ($text, $options = array())
```  

<h3>icon</h3> 
Function which returns a boostrap icon
```
/**
    
    Create a Twitter Bootstrap icon.
    
    @param $icon The type of the icon (search, pencil, etc.)
    @param $color The color of the icon (black or white)

**/
BootstrapHtmlHelper::icon ($icon, $color = 'black')
```

<h3>progress</h3>
Function which returns a boostrap progress bar
```
/**

    Create an Twitter Bootstrap style progress bar.
    
    @param $widths 
        - The width (in %) of the bar
        - An array of bar, with width and type (info, danger, success, warning) specified for each bar
    @param $options Options that will be passed to Html::div method (only for main div)
    
    Available BootstrapHtml options:
        - striped: boolean, specify if progress bar should be striped
        - active: boolean, specify if progress bar should be active
        
    Ex: 
        $this->BoostrapHtml->progress(array(
            array(
                'width' => 10
            ),
            array(
                'width' => 20,
                'type' => 'danger'
            )
        )) ;

**/
BootstrapHtmlHelper::progress ($widths, $options = array()) 
```

Copyright and license
=====================

Copyright 2013 Mikaël Capelle.

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this work except in compliance with the License. You may obtain a copy of the License in the LICENSE file, or at:

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
