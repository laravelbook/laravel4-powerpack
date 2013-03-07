<?php namespace LaravelBook\Laravel4Powerpack;

/*
 * This file is part of the Laravel 4 Powerpack package.
 *
 * Portions Copyright (c) Max Ehsan <contact@laravelbook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Closure;
use LaravelBook\Laravel4Powerpack\HTML;
use Illuminate\Routing\UrlGenerator as Url;

class Form {

	/**
	 * All of the label names that have been created.
	 *
	 * @var array
	 */
	public $labels = array();

	/**
	 * The url generator instance
	 *
	 * @var Illuminate\Routing\UrlGenerator
	 */
	protected $url;

	/**
	 * The encoding to use.
	 *
	 * @var string
	 */
	protected $encoding = 'utf-8';

	/**
	 * The registered custom macros.
	 *
	 * @var array
	 */
	public $macros = array();

	/**
	 * The request spoofer.
	 *
	 * @var string
	 */
	public $spoofer = '_method';

	/**
	 * The HTML helper
	 *
	 * @var LaravelBook\Laravel4Powerpack\HTML
	 */
	protected $html;


	public function __construct( HTML $html ) {
		$this->html = $html;
		$this->url = $html->getUrlGenerator();
	}

	/**
	 * Registers a custom macro.
	 *
	 * @param string  $name
	 * @param Closure $macro
	 * @return void
	 */
	public function macro( $name, Closure $macro ) {
		$this->macros[$name] = $macro;
	}

	/**
	 * Open a HTML form.
	 *
	 * <code>
	 *  // Open a "POST" form to the current request URI
	 *  echo Form::open();
	 *
	 *  // Open a "POST" form to a given URI
	 *  echo Form::open('user/profile');
	 *
	 *  // Open a "PUT" form to a given URI
	 *  echo Form::open('user/profile', 'put');
	 *
	 *  // Open a form that has HTML attributes
	 *  echo Form::open('user/profile', 'post', array('class' => 'profile'));
	 * </code>
	 *
	 * @param string  $action
	 * @param string  $method
	 * @param array   $attributes
	 * @param bool    $https
	 * @return string
	 */
	public function open( $action = null, $method = 'POST', $attributes = array(), $https = null ) {
		$method = strtoupper( $method );

		$attributes['method'] =  $this->method( $method );

		$attributes['action'] = $this->action( $action, $https );

		// If a character encoding has not been specified in the attributes, we will
		// use the default encoding as specified in the variable $encoding above
		// for the "accept-charset" attribute.
		if ( ! array_key_exists( 'accept-charset', $attributes ) ) {
			$attributes['accept-charset'] = $this->encoding;
		}

		$append = '';

		// Since PUT and DELETE methods are not actually supported by HTML forms,
		// we'll create a hidden input element that contains the request method
		// and set the actual request method variable to POST.
		if ( $method == 'PUT' or $method == 'DELETE' ) {
			$append = $this->hidden( $this->spoofer, $method );
		}

		return '<form'.$this->html->attributes( $attributes ).'>'.$append;
	}

	/**
	 * Determine the appropriate request method to use for a form.
	 *
	 * @param string  $method
	 * @return string
	 */
	protected function method( $method ) {
		return ( $method !== 'GET' ) ? 'POST' : $method;
	}

	/**
	 * Determine the appropriate action parameter to use for a form.
	 *
	 * If no action is specified, the current request URI will be used.
	 *
	 * @param string  $action
	 * @param bool    $https
	 * @return string
	 */
	protected function action( $action, $https ) {
		$uri = ( is_null( $action ) ) ? $this->url->getRequest()->path() : $action;

		return $this->html->entities( $this->url->to( $uri, array(), $https ) );
	}

	/**
	 * Open a HTML form with a HTTPS action URI.
	 *
	 * @param string  $action
	 * @param string  $method
	 * @param array   $attributes
	 * @return string
	 */
	public function openSecure( $action = null, $method = 'POST', $attributes = array() ) {
		return $this->open( $action, $method, $attributes, true );
	}

	/**
	 * Open a HTML form that accepts file uploads.
	 *
	 * @param string  $action
	 * @param string  $method
	 * @param array   $attributes
	 * @param bool    $https
	 * @return string
	 */
	public function openForFiles( $action = null, $method = 'POST', $attributes = array(), $https = null ) {
		$attributes['enctype'] = 'multipart/form-data';

		return $this->open( $action, $method, $attributes, $https );
	}

	/**
	 * Open a HTML form that accepts file uploads with a HTTPS action URI.
	 *
	 * @param string  $action
	 * @param string  $method
	 * @param array   $attributes
	 * @return string
	 */
	public function openSecureForFiles( $action = null, $method = 'POST', $attributes = array() ) {
		return $this->openForFiles( $action, $method, $attributes, true );
	}

	/**
	 * Close a HTML form.
	 *
	 * @return string
	 */
	public function close() {
		return '</form>';
	}

	/**
	 * Generate a hidden field containing the current CSRF token.
	 *
	 * @return string
	 */
	public function token() {
		return $this->input( 'hidden', 'csrf_token', csrf_token() );
	}

	/**
	 * Create a HTML label element.
	 *
	 * @param string  $name
	 * @param string  $value
	 * @param array   $attributes
	 * @return string
	 */
	public function label( $name, $value, $attributes = array() ) {
		$this->labels[] = $name;

		$attributes = $this->html->attributes( $attributes );

		$value = $this->html->entities( $value );

		return '<label for="'.$name.'"'.$attributes.'>'.$value.'</label>';
	}

	/**
	 * Create a HTML input element.
	 *
	 * @param string  $type
	 * @param string  $name
	 * @param mixed   $value
	 * @param array   $attributes
	 * @return string
	 */
	public function input( $type, $name, $value = null, $attributes = array() ) {
		$name = ( isset( $attributes['name'] ) ) ? $attributes['name'] : $name;

		$id = $this->id( $name, $attributes );

		$attributes = array_merge( $attributes, compact( 'type', 'name', 'value', 'id' ) );

		return '<input'.$this->html->attributes( $attributes ).' />';
	}

	/**
	 * Create a HTML text input element.
	 *
	 * @param string  $name
	 * @param string  $value
	 * @param array   $attributes
	 * @return string
	 */
	public function text( $name, $value = null, $attributes = array() ) {
		return $this->input( 'text', $name, $value, $attributes );
	}

	/**
	 * Create a HTML password input element.
	 *
	 * @param string  $name
	 * @param array   $attributes
	 * @return string
	 */
	public function password( $name, $attributes = array() ) {
		return $this->input( 'password', $name, null, $attributes );
	}

	/**
	 * Create a HTML hidden input element.
	 *
	 * @param string  $name
	 * @param string  $value
	 * @param array   $attributes
	 * @return string
	 */
	public function hidden( $name, $value = null, $attributes = array() ) {
		return $this->input( 'hidden', $name, $value, $attributes );
	}

	/**
	 * Create a HTML search input element.
	 *
	 * @param string  $name
	 * @param string  $value
	 * @param array   $attributes
	 * @return string
	 */
	public function search( $name, $value = null, $attributes = array() ) {
		return $this->input( 'search', $name, $value, $attributes );
	}

	/**
	 * Create a HTML email input element.
	 *
	 * @param string  $name
	 * @param string  $value
	 * @param array   $attributes
	 * @return string
	 */
	public function email( $name, $value = null, $attributes = array() ) {
		return $this->input( 'email', $name, $value, $attributes );
	}

	/**
	 * Create a HTML telephone input element.
	 *
	 * @param string  $name
	 * @param string  $value
	 * @param array   $attributes
	 * @return string
	 */
	public function telephone( $name, $value = null, $attributes = array() ) {
		return $this->input( 'tel', $name, $value, $attributes );
	}

	/**
	 * Create a HTML URL input element.
	 *
	 * @param string  $name
	 * @param string  $value
	 * @param array   $attributes
	 * @return string
	 */
	public function url( $name, $value = null, $attributes = array() ) {
		return $this->input( 'url', $name, $value, $attributes );
	}

	/**
	 * Create a HTML number input element.
	 *
	 * @param string  $name
	 * @param string  $value
	 * @param array   $attributes
	 * @return string
	 */
	public function number( $name, $value = null, $attributes = array() ) {
		return $this->input( 'number', $name, $value, $attributes );
	}

	/**
	 * Create a HTML date input element.
	 *
	 * @param string  $name
	 * @param string  $value
	 * @param array   $attributes
	 * @return string
	 */
	public function date( $name, $value = null, $attributes = array() ) {
		return $this->input( 'date', $name, $value, $attributes );
	}

	/**
	 * Create a HTML file input element.
	 *
	 * @param string  $name
	 * @param array   $attributes
	 * @return string
	 */
	public function file( $name, $attributes = array() ) {
		return $this->input( 'file', $name, null, $attributes );
	}

	/**
	 * Create a HTML textarea element.
	 *
	 * @param string  $name
	 * @param string  $value
	 * @param array   $attributes
	 * @return string
	 */
	public function textarea( $name, $value = '', $attributes = array() ) {
		$attributes['name'] = $name;

		$attributes['id'] = $this->id( $name, $attributes );

		if ( ! isset( $attributes['rows'] ) ) $attributes['rows'] = 10;

		if ( ! isset( $attributes['cols'] ) ) $attributes['cols'] = 50;

		return '<textarea'.$this->html->attributes( $attributes ).'>'.$this->html->entities( $value ).'</textarea>';
	}

	/**
	 * Create a HTML select element.
	 *
	 * @param string  $name
	 * @param array   $options
	 * @param string  $selected
	 * @param array   $attributes
	 * @return string
	 */
	public function select( $name, $options = array(), $selected = null, $attributes = array() ) {
		$attributes['id'] = $this->id( $name, $attributes );

		$attributes['name'] = $name;

		$html = array();

		foreach ( $options as $value => $display ) {
			if ( is_array( $display ) ) {
				$html[] = $this->optgroup( $display, $value, $selected );
			}
			else {
				$html[] = $this->option( $value, $display, $selected );
			}
		}

		return '<select'.$this->html->attributes( $attributes ).'>'.implode( '', $html ).'</select>';
	}

	/**
	 * Create a HTML select element optgroup.
	 *
	 * @param array   $options
	 * @param string  $label
	 * @param string  $selected
	 * @return string
	 */
	protected function optgroup( $options, $label, $selected ) {
		$html = array();

		foreach ( $options as $value => $display ) {
			$html[] = $this->option( $value, $display, $selected );
		}

		return '<optgroup label="'.$this->html->entities( $label ).'">'.implode( '', $html ).'</optgroup>';
	}

	/**
	 * Create a HTML select element option.
	 *
	 * @param string  $value
	 * @param string  $display
	 * @param string  $selected
	 * @return string
	 */
	protected function option( $value, $display, $selected ) {
		if ( is_array( $selected ) ) {
			$selected = ( in_array( $value, $selected ) ) ? 'selected' : null;
		}
		else {
			$selected = ( (string) $value == (string) $selected ) ? 'selected' : null;
		}

		$attributes = array( 'value' => $this->html->entities( $value ), 'selected' => $selected );

		return '<option'.$this->html->attributes( $attributes ).'>'.$this->html->entities( $display ).'</option>';
	}

	/**
	 * Create a HTML checkbox input element.
	 *
	 * @param string  $name
	 * @param string  $value
	 * @param bool    $checked
	 * @param array   $attributes
	 * @return string
	 */
	public function checkbox( $name, $value = 1, $checked = false, $attributes = array() ) {
		return $this->checkable( 'checkbox', $name, $value, $checked, $attributes );
	}

	/**
	 * Create a HTML radio button input element.
	 *
	 * @param string  $name
	 * @param string  $value
	 * @param bool    $checked
	 * @param array   $attributes
	 * @return string
	 */
	public function radio( $name, $value = null, $checked = false, $attributes = array() ) {
		if ( is_null( $value ) ) $value = $name;

		return $this->checkable( 'radio', $name, $value, $checked, $attributes );
	}

	/**
	 * Create a checkable input element.
	 *
	 * @param string  $type
	 * @param string  $name
	 * @param string  $value
	 * @param bool    $checked
	 * @param array   $attributes
	 * @return string
	 */
	protected function checkable( $type, $name, $value, $checked, $attributes ) {
		if ( $checked ) $attributes['checked'] = 'checked';

		$attributes['id'] = $this->id( $name, $attributes );

		return $this->input( $type, $name, $value, $attributes );
	}

	/**
	 * Create a HTML submit input element.
	 *
	 * @param string  $value
	 * @param array   $attributes
	 * @return string
	 */
	public function submit( $value = null, $attributes = array() ) {
		return $this->input( 'submit', null, $value, $attributes );
	}

	/**
	 * Create a HTML reset input element.
	 *
	 * @param string  $value
	 * @param array   $attributes
	 * @return string
	 */
	public function reset( $value = null, $attributes = array() ) {
		return $this->input( 'reset', null, $value, $attributes );
	}

	/**
	 * Create a HTML image input element.
	 *
	 * @param string  $url
	 * @param string  $name
	 * @param array   $attributes
	 * @return string
	 */
	public function image( $url, $name = null, $attributes = array() ) {
		$attributes['src'] = $this->url->to( $url );

		return $this->input( 'image', $name, null, $attributes );
	}

	/**
	 * Create a HTML button element.
	 *
	 * @param string  $value
	 * @param array   $attributes
	 * @return string
	 */
	public function button( $value = null, $attributes = array() ) {
		return '<button'.$this->html->attributes( $attributes ).'>'.$this->html->entities( $value ).'</button>';
	}

	/**
	 * Determine the ID attribute for a form element.
	 *
	 * @param string  $name
	 * @param array   $attributes
	 * @return mixed
	 */
	protected function id( $name, $attributes ) {
		// If an ID has been explicitly specified in the attributes, we will
		// use that ID. Otherwise, we will look for an ID in the array of
		// label names so labels and their elements have the same ID.
		if ( array_key_exists( 'id', $attributes ) ) {
			return $attributes['id'];
		}

		if ( in_array( $name, $this->labels ) ) {
			return $name;
		}
	}

	/**
	 * Dynamically handle calls to custom macros.
	 *
	 * @param string  $method
	 * @param array   $parameters
	 * @return mixed
	 */
	public function __call( $method, $parameters ) {
		if ( isset( $this->macros[$method] ) ) {
			return call_user_func_array( $this->macros[$method], $parameters );
		}

		throw new \Exception( "Method [$method] does not exist." );
	}

}
