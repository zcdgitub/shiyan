<?php
/**
 * Copyright (C) 2012 Erson Puyos<erson.puyos@gmal.com>
 * 
 * Permission is hereby granted, free of charge, 
 * to any person obtaining a copy of this software and associated 
 * documentation files (the "Software"), 
 * to deal in the Software without restriction, 
 * including without limitation the rights 
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, 
 * and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included 
 * in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS 
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, 
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR 
 * OTHER DEALINGS IN THE SOFTWARE.
 */ 
/**
 * Helper file, a collections of Yii function to make the Yii coding shorten.
 * @author Erson Puyos <erson.puyos@gmail.com>
 */ 
/**
 * This file shorten all the long Yii code, example below.
 * 1. Yii::* 
 * 2. CHtml::*
 */    
/**
 * It will return our WebApp.
 * to remove the code at our WebApp Yii::app()     
 * Returns the application singleton, null if the singleton has not been created yet.
 * @return CApplication the application singleton, null if the singleton has not been created yet.	
 */  
function webapp()
{
    return Yii::app();
}
/**
 * Return the base url
 * @return WebApp Base Url
 */ 
function baseUrl()
{
    return webapp()->request->baseUrl;
}    
/**
 * Registers a CSS file
 * @param string $url URL of the CSS file
 * @param string $media media that the CSS file should be applied to. If empty, it means all media types.
 * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
 */
function registerCssFile($url, $media='')
{
    return webapp()->clientScript->registerCssFile($url, $media);
}
/**
 * Registers a piece of CSS code.
 * @param string $id ID that uniquely identifies this piece of CSS code
 * @param string $css the CSS code
 * @param string $media media that the CSS code should be applied to. If empty, it means all media types.
 * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
 */
function registerCss($id, $css, $media='')
{
   return webapp()->clientScript->registerCss($id, $css, $media);
}
/**
 * Registers a javascript file.
 * @param string $url URL of the javascript file
 * @param integer $position the position of the JavaScript code. Valid values include the following:
 * <ul>
 * <li>CClientScript::POS_HEAD : the script is inserted in the head section right before the title element.</li>
 * <li>CClientScript::POS_BEGIN : the script is inserted at the beginning of the body section.</li>
 * <li>CClientScript::POS_END : the script is inserted at the end of the body section.</li>
 * </ul>
 * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
 */
function registerScriptFile($url, $position=CClientScript::POS_HEAD)
{
   return webapp()->clientScript->registerScriptFile($url, $position);
}
/**
 * Registers a piece of javascript code.
 * @param string $id ID that uniquely identifies this piece of JavaScript code
 * @param string $script the javascript code
 * @param integer $position the position of the JavaScript code. Valid values include the following:
 * <ul>
 * <li>CClientScript::POS_HEAD : the script is inserted in the head section right before the title element.</li>
 * <li>CClientScript::POS_BEGIN : the script is inserted at the beginning of the body section.</li>
 * <li>CClientScript::POS_END : the script is inserted at the end of the body section.</li>
 * <li>CClientScript::POS_LOAD : the script is inserted in the window.onload() function.</li>
 * <li>CClientScript::POS_READY : the script is inserted in the jQuery's ready function.</li>
 * </ul>
 * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
 */
function registerScript($id,$script,$position=CClientScript::POS_READY)
{
   return webapp()->clientScript->registerScript($id, $script, $position);
}
/**
 * Function to get the user
 * @return WebUser
 */ 
function user()
{
    return webapp()->getUser();
}
/**
 * This will upon creation of the url shorten, it will elemenate the line below.
 * 1. $this->createUrl() 
 * 2. Yii::app()->controller->createUrl()
 * 3. Yii::app()->createUrl()
 * 
 * Creates a relative URL for the specified action defined in this controller.
 * @param string $route the URL route. This should be in the format of 'ControllerID/ActionID'.
 * If the ControllerID is not present, the current controller ID will be prefixed to the route.
 * If the route is empty, it is assumed to be the current action.
 * Since version 1.0.3, if the controller belongs to a module, the {@link CWebModule::getId module ID}
 * will be prefixed to the route. (If you do not want the module ID prefix, the route should start with a slash '/'.)
 * @param array $params additional GET parameters (name=>value). Both the name and value will be URL-encoded.
 * If the name is '#', the corresponding value will be treated as an anchor
 * and will be appended at the end of the URL. This anchor feature has been available since version 1.0.1.
 * @param string $ampersand the token separating name-value pairs in the URL.
 * @return string the constructed URL               
 */ 
function createUrl($route, $params=array(), $ampersand='&')
{
    if(strncmp($route,'http',4)==0)
        return $route;
    return webapp()->controller->createUrl($route,$params,$ampersand);
}
/**
 * To register a MetaTag
 * Registers a meta tag that will be inserted in the head section (right before the title element) of the resulting page.
 * @param string $content content attribute of the meta tag
 * @param string $name name attribute of the meta tag. If null, the attribute will not be generated
 * @param string $httpEquiv http-equiv attribute of the meta tag. If null, the attribute will not be generated
 * @param array $options other options in name-value pairs (e.g. 'scheme', 'lang')
 * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
 * @since 1.0.1          
 */ 
function registerMetaTag($content, $name=NULL, $httpEquiv=NULL, $options=array())
{
    return webapp()->clientScript->registerMetaTag($content, $name, $httpEquiv, $options);
}    
/**
 * A function that will elemenate the long lines of coding to get the parameter.
 * Yii::app()->params['Parameter']
 * 
 * If there will be a nested parameter we defined, we can pull the data by calling this function, and
 * used the character "." (period), example below
 * 
 * 1. params('data')
 * 2. params('data.mysample');
 * 
 * This is to pull upto 10 nested parameters.
 * @param parameter $attribute to pull
 * @return value of params
 */  
function params($attribute)
{  
    $s = explode(".", $attribute);   
    switch(count($s)){
        case 1:
            return webapp()->params[$attribute];
        case 2:
            return webapp()->params[$s[0]][$s[1]];
        case 3:
            return webapp()->params[$s[0]][$s[1]][$s[2]];
        case 4:
            return webapp()->params[$s[0]][$s[1]][$s[2]][$s[3]];
        case 5:
            return webapp()->params[$s[0]][$s[1]][$s[2]][$s[3]][$s[4]];
        case 6:
            return webapp()->params[$s[0]][$s[1]][$s[2]][$s[3]][$s[4]][$s[5]]; 
        case 7:
            return webapp()->params[$s[0]][$s[1]][$s[2]][$s[3]][$s[4]][$s[5]][$s[6]]; 
        case 8:
            return webapp()->params[$s[0]][$s[1]][$s[2]][$s[3]][$s[4]][$s[5]][$s[6]][$s[7]]; 
        case 9:
            return webapp()->params[$s[0]][$s[1]][$s[2]][$s[3]][$s[4]][$s[5]][$s[6]][$s[7]][$s[8]];
        case 10:
            return webapp()->params[$s[0]][$s[1]][$s[2]][$s[3]][$s[4]][$s[5]][$s[6]][$s[7]][$s[8]][$s[9]];                                                                                                                          
    }
}
/**
 * Shorten all the codes we upon generating an HTML tag by calling the class CHtml::*
 */ 
/**
 * Encodes special characters into HTML entities.
 * The {@link CApplication::charset application charset} will be used for encoding.
 * @param string $text data to be encoded
 * @return string the encoded data
 * @see http://www.php.net/manual/en/function.htmlspecialchars.php
 */
function encode($text)
{
    return CHtml::encode($text);        
}
/**
 * Generates a meta tag that can be inserted in the head section of HTML page.
 * @param string $content content attribute of the meta tag
 * @param string $name name attribute of the meta tag. If null, the attribute will not be generated
 * @param string $httpEquiv http-equiv attribute of the meta tag. If null, the attribute will not be generated
 * @param array $options other options in name-value pairs (e.g. 'scheme', 'lang')
 * @return string the generated meta tag
 * @since 1.0.1
 */
function metaTag($content,$name=null,$httpEquiv=null,$options=array())
{
    return CHtml::metaTag($content, $name, $httpEquiv, $options);
}
/**
 * Encloses the given CSS content with a CSS tag.
 * @param string $text the CSS content
 * @param string $media the media that this CSS should apply to.
 * @return string the CSS properly enclosed
 */
function css($text,$media='')
{
    return CHtml::css($text, $media);
}
/**
 * Links to the specified CSS file.
 * @param string $url the CSS URL
 * @param string $media the media that this CSS should apply to.
 * @return string the CSS link.
 */
function cssFile($url,$media='')
{
    return CHtml::cssFile($url, $media);
}
/**
 * Encloses the given JavaScript within a script tag.
 * @param string $text the JavaScript to be enclosed
 * @return string the enclosed JavaScript
 */
function script($text)
{
    return CHtml::script($text);
}
/**
 * Includes a JavaScript file.
 * @param string $url URL for the JavaScript file
 * @return string the JavaScript file tag
 */
function scriptFile($url)
{
    return CHtml::scriptFile($url);
}
/**
 * Generates a hyperlink tag.
 * @param string $text link body. It will NOT be HTML-encoded. Therefore you can pass in HTML code such as an image tag.
 * @param mixed $url a URL or an action route that can be used to create a URL.
 * See {@link normalizeUrl} for more details about how to specify this parameter.
 * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
 * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
 * @return string the generated hyperlink
 * @see normalizeUrl
 * @see clientChange
 */
function hyperlink($text,$url='#',$htmlOptions=array())
{
    return CHtml::link($text, $url, $htmlOptions);	       
}
/**
 * Generates an image tag.
 * @param string $src the image URL
 * @param string $alt the alternative text display
 * @param array $htmlOptions additional HTML attributes (see {@link tag}).
 * @return string the generated image tag
 */
function image($src,$alt='',$htmlOptions=array())
{
    return CHtml::image($src, $alt, $htmlOptions);
}
/**
 * Generates a button.
 * @param string $label the button label
 * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
 * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
 * @return string the generated button tag
 * @see clientChange
 */
function button($label='button',$htmlOptions=array())
{
    return CHtml::button($label, $htmlOptions);
}
/**
 * Generates a submit button.
 * @param string $label the button label
 * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
 * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
 * @return string the generated button tag
 * @see clientChange
 */
function submitButton($label='submit',$htmlOptions=array())
{
    return CHtml::submitButton($label, $htmlOptions);
}
/**
 * Generates a label tag.
 * @param string $label label text. Note, you should HTML-encode the text if needed.
 * @param string $for the ID of the HTML element that this label is associated with.
 * If this is false, the 'for' attribute for the label tag will not be rendered (since version 1.0.11).
 * @param array $htmlOptions additional HTML attributes.
 * Starting from version 1.0.2, the following HTML option is recognized:
 * <ul>
 * <li>required: if this is set and is true, the label will be styled
 * with CSS class 'required' (customizable with CHtml::$requiredCss),
 * and be decorated with {@link CHtml::beforeRequiredLabel} and
 * {@link CHtml::afterRequiredLabel}.</li>
 * </ul>
 * @return string the generated label tag
 */
function label($label,$for,$htmlOptions=array())
{
    return CHtml::label($label, $for, $htmlOptions);
}
/**
 * Generates a text field input.
 * @param string $name the input name
 * @param string $value the input value
 * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
 * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
 * @return string the generated input field
 * @see clientChange
 * @see inputField
 */
function textField($name,$value='',$htmlOptions=array())
{
    return CHtml::textField($name, $value, $htmlOptions);
}
/**
 * Generates a hidden input.
 * @param string $name the input name
 * @param string $value the input value
 * @param array $htmlOptions additional HTML attributes (see {@link tag}).
 * @return string the generated input field
 * @see inputField
 */
function hiddenField($name,$value='',$htmlOptions=array())
{
    return CHtml::hiddenField($name, $value, $htmlOptions);
}
/**
 * Generates a password field input.
 * @param string $name the input name
 * @param string $value the input value
 * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
 * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
 * @return string the generated input field
 * @see clientChange
 * @see inputField
 */
function passwordField($name,$value='',$htmlOptions=array())
{
    return CHtml::passwordField($name, $value, $htmlOptions);
}
/**
 * Generates a text area input.
 * @param string $name the input name
 * @param string $value the input value
 * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
 * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
 * @return string the generated text area
 * @see clientChange
 * @see inputField
 */
function textArea($name,$value='',$htmlOptions=array())
{
    return CHtml::textArea($name, $value, $htmlOptions);
}
/**
 * Generates a radio button.
 * @param string $name the input name
 * @param boolean $checked whether the radio button is checked
 * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
 * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
 * Since version 1.1.2, a special option named 'uncheckValue' is available that can be used to specify
 * the value returned when the radio button is not checked. When set, a hidden field is rendered so that
 * when the radio button is not checked, we can still obtain the posted uncheck value.
 * If 'uncheckValue' is not set or set to NULL, the hidden field will not be rendered.
 * @return string the generated radio button
 * @see clientChange
 * @see inputField
 */
function radioButton($name,$checked=false,$htmlOptions=array())
{
    return CHtml::radioButton($name, $checked, $htmlOptions);
}
/**
 * Generates a check box.
 * @param string $name the input name
 * @param boolean $checked whether the check box is checked
 * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
 * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
 * Since version 1.1.2, a special option named 'uncheckValue' is available that can be used to specify
 * the value returned when the checkbox is not checked. When set, a hidden field is rendered so that
 * when the checkbox is not checked, we can still obtain the posted uncheck value.
 * If 'uncheckValue' is not set or set to NULL, the hidden field will not be rendered.
 * @return string the generated check box
 * @see clientChange
 * @see inputField
 */
function checkBox($name,$checked=false,$htmlOptions=array())
{
    return CHtml::checkBox($name, $checked, $htmlOptions);
}
/**
 * Generates a drop down list.
 * @param string $name the input name
 * @param string $select the selected value
 * @param array $data data for generating the list options (value=>display).
 * You may use {@link listData} to generate this data.
 * Please refer to {@link listOptions} on how this data is used to generate the list options.
 * Note, the values and labels will be automatically HTML-encoded by this method.
 * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
 * attributes are recognized. See {@link clientChange} and {@link tag} for more details.
 * In addition, the following options are also supported specifically for dropdown list:
 * <ul>
 * <li>encode: boolean, specifies whether to encode the values. Defaults to true. This option has been available since version 1.0.5.</li>
 * <li>prompt: string, specifies the prompt text shown as the first list option. Its value is empty. Note, the prompt text will NOT be HTML-encoded.</li>
 * <li>empty: string, specifies the text corresponding to empty selection. Its value is empty.
 * Starting from version 1.0.10, the 'empty' option can also be an array of value-label pairs.
 * Each pair will be used to render a list option at the beginning. Note, the text label will NOT be HTML-encoded.</li>
 * <li>options: array, specifies additional attributes for each OPTION tag.
 *     The array keys must be the option values, and the array values are the extra
 *     OPTION tag attributes in the name-value pairs. For example,
 * <pre>
 *     array(
 *         'value1'=>array('disabled'=>true, 'label'=>'value 1'),
 *         'value2'=>array('label'=>'value 2'),
 *     );
 * </pre>
 *     This option has been available since version 1.0.3.
 * </li>
 * </ul>
 * @return string the generated drop down list
 * @see clientChange
 * @see inputField
 * @see listData
 */
function dropDownList($name,$select,$data,$htmlOptions=array())
{
    return CHtml::dropDownList($name, $select, $data, $htmlOptions);
}
/**
 * Stores a flash message.
 * A flash message is available only in the current and the next requests.
 * @param string $key key identifying the flash message
 * @param mixed $value flash message
 * @param mixed $defaultValue if this value is the same as the flash message, the flash message
 * will be removed. (Therefore, you can use setFlash('key',null) to remove a flash message.)
 */
function setFlash($key, $value, $defaultValue=null)
{
    user()->setFlash($key, $value, $defaultValue);
}
/**
 * Returns a flash message.
 * A flash message is available only in the current and the next requests.
 * @param string $key key identifying the flash message
 * @param mixed $defaultValue value to be returned if the flash message is not available.
 * @param boolean $delete whether to delete this flash message after accessing it.
 * Defaults to true. This parameter has been available since version 1.0.2.
 * @return mixed the message message
 */
function getFlash($key, $defaultValue=null, $delete=true)
{
    user()->getFlash($key, $defaultValue, $delete);
}
/**
 * @param string $key key identifying the flash message
 * @return boolean whether the specified flash message exists
 */
function hasFlash($key)
{
	return user()->hasFlash($key);
}
/**
 * Translates a message to the specified language.
 * Starting from version 1.0.2, this method supports choice format (see {@link CChoiceFormat}),
 * i.e., the message returned will be chosen from a few candidates according to the given
 * number value. This feature is mainly used to solve plural format issue in case
 * a message has different plural forms in some languages.
 * @param string $category message category. Please use only word letters. Note, category 'yii' is
 * reserved for Yii framework core code use. See {@link CPhpMessageSource} for
 * more interpretation about message category.
 * @param string $message the original message
 * @param array $params parameters to be applied to the message using <code>strtr</code>.
 * Starting from version 1.0.2, the first parameter can be a number without key.
 * And in this case, the method will call {@link CChoiceFormat::format} to choose
 * an appropriate message translation.
 * Starting from version 1.1.6 you can pass parameter for {@link CChoiceFormat::format}
 * or plural forms format without wrapping it with array.
 * @param string $source which message source application component to use.
 * Defaults to null, meaning using 'coreMessages' for messages belonging to
 * the 'yii' category and using 'messages' for the rest messages.
 * @param string $language the target language. If null (default), the {@link CApplication::getLanguage application language} will be used.
 * This parameter has been available since version 1.0.3.
 * @return string the translated message
 * @see CMessageSource
 */
function t($category, $message, $params=array(), $source=null, $language=null)
{
	if($category=='epmms')
	{
		if(Yii::app()->language=='zh_tw' || $language==='zh_tw')
		{
			return MediaWiki::convert($message,'zh-tw');
		}
	}
    return Yii::t($category, $message, $params, $source, $language);
}