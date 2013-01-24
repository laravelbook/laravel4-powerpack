laravel4-powerpack
==================

Brings back the helper classes from Laravel 3 to Laravel 4... and all that in a single, convenient package!

**`laravel4-powerpack`** contains Laravel 4 ports of the following helper classes:

- [HTML](#html_class)

- [Form](#form_class)

- [Str](#str_class)

<a name="html_class"></a>
# Building HTML

## Content

- [Entities](#entities)
- [Scripts And Style Sheets](#scripts-and-style-sheets)
- [Links](#links)
- [Links To Named Routes](#links-to-named-routes)
- [Links To Controller Actions](#links-to-controller-actions)
- [Mail-To Links](#mail-to-links)
- [Images](#images)
- [Lists](#lists)
- [Custom Macros](#custom-macros)

<a name="entities"></a>
## Entities

When displaying user input in your Views, it is important to convert all characters which have significance in HTML to their "entity" representation.

For example, the < symbol should be converted to its entity representation. Converting HTML characters to their entity representation helps protect your application from cross-site scripting:

#### Converting a string to its entity representation:

	echo HTML::entities('<script>alert(\'hi\');</script>');

#### Using the "e" global helper:

	echo e('<script>alert(\'hi\');</script>');

<a name="scripts-and-style-sheets"></a>
## Scripts And Style Sheets

#### Generating a reference to a JavaScript file:

	echo HTML::script('js/scrollTo.js');

#### Generating a reference to a CSS file:

	echo HTML::style('css/common.css');

#### Generating a reference to a CSS file using a given media type:

	echo HTML::style('css/common.css', array('media' => 'print'));

*Further Reading:*

- *[Managing Assets](/docs/views/assets)*

<a name="links"></a>
## Links

#### Generating a link from a URI:

	echo HTML::link('user/profile', 'User Profile');

#### Generating a link that should use HTTPS:

	echo HTML::secure('user/profile', 'User Profile');

#### Generating a link and specifying extra HTML attributes:

	echo HTML::link('user/profile', 'User Profile', array('id' => 'profile_link'));

<a name="links-to-named-routes"></a>
## Links To Named Routes

#### Generating a link to a named route:

	echo HTML::route('profile');

#### Generating a link to a named route with wildcard values:

	$url = HTML::route('profile', 'User Profile', array($username));

*Further Reading:*

- *[Named Routes](/docs/routing#named-routes)*

<a name="links-to-controller-actions"></a>
## Links To Controller Actions

#### Generating a link to a controller action:

	echo HTML::action('home@index');

### Generating a link to a controller action with wildcard values:

	echo HTML::action('user@profile', 'User Profile', array($username));

<a name="mail-to-links"></a>
## Mail-To Links

The "mailto" method on the HTML class obfuscates the given e-mail address so it is not sniffed by bots.

#### Creating a mail-to link:

	echo HTML::mailto('example@gmail.com', 'E-Mail Me!');

#### Creating a mail-to link using the e-mail address as the link text:

	echo HTML::mailto('example@gmail.com');

<a name="images"></a>
## Images

#### Generating an HTML image tag:

	echo HTML::image('img/smile.jpg', $alt_text);

#### Generating an HTML image tag with extra HTML attributes:

	echo HTML::image('img/smile.jpg', $alt_text, array('id' => 'smile'));

<a name="lists"></a>
## Lists

#### Creating lists from an array of items:

	echo HTML::ol(array('Get Peanut Butter', 'Get Chocolate', 'Feast'));

	echo HTML::ul(array('Ubuntu', 'Snow Leopard', 'Windows'));

	echo HTML::dl(array('Ubuntu' => 'An operating system by Canonical', 'Windows' => 'An operating system by Microsoft'));

<a name="custom-macros"></a>
## Custom Macros

It's easy to define your own custom HTML class helpers called "macros". Here's how it works. First, simply register the macro with a given name and a Closure:

#### Registering a HTML macro:

	HTML::macro('myElement', function()
	{
		return '<article type="awesome">';
	});

Now you can call your macro using its name:

#### Calling a custom HTML macro:

	echo HTML::myElement();

---

<a name="form_class"></a>
# Building Forms

## Contents

- [Opening A Form](#opening-a-form)
- [CSRF Protection](#csrf-protection)
- [Labels](#labels)
- [Text, Text Area, Password & Hidden Fields](#text)
- [File Input](#file)
- [Checkboxes and Radio Buttons](#checkboxes-and-radio-buttons)
- [Drop-Down Lists](#drop-down-lists)
- [Buttons](#buttons)
- [Custom Macros](#custom-macros)

> **Note:** All input data displayed in form elements is filtered through the HTML::entities method.

<a name="opening-a-form"></a>
## Opening A Form

#### Opening a form to POST to the current URL:

	echo Form::open();

#### Opening a form using a given URI and request method:

	echo Form::open('user/profile', 'PUT');

#### Opening a Form that POSTS to a HTTPS URL:

	echo Form::openSecure('user/profile');

#### Specifying extra HTML attributes on a form open tag:

	echo Form::open('user/profile', 'POST', array('class' => 'awesome'));

#### Opening a form that accepts file uploads:

	echo Form::openForFiles('users/profile');

#### Opening a form that accepts file uploads and uses HTTPS:

	echo Form::openSecureForFiles('users/profile');

#### Closing a form:

	echo Form::close();

<a name="csrf-protection"></a>
## CSRF Protection

Laravel provides an easy method of protecting your application from cross-site request forgeries. First, a random token is placed in your user's session. Don't sweat it, this is done automatically. Next, use the token method to generate a hidden form input field containing the random token on your form:

#### Generating a hidden field containing the session's CSRF token:

	echo Form::token();

#### Attaching the CSRF filter to a route:

	Route::post('profile', array('before' => 'csrf', function()
	{
		//
	}));

#### Retrieving the CSRF token string:

	$token = Session::getToken();

> **Note:** You must specify a session driver before using the Laravel CSRF protection facilities.

*Further Reading:*

- [Route Filters](/docs/routing#filters)
- [Cross-Site Request Forgery](http://en.wikipedia.org/wiki/Cross-site_request_forgery)

<a name="labels"></a>
## Labels

#### Generating a label element:

	echo Form::label('email', 'E-Mail Address');

#### Specifying extra HTML attributes for a label:

	echo Form::label('email', 'E-Mail Address', array('class' => 'awesome'));

> **Note:** After creating a label, any form element you create with a name matching the label name will automatically receive an ID matching the label name as well.

<a name="text"></a>
## Text, Text Area, Password & Hidden Fields

#### Generate a text input element:

	echo Form::text('username');

#### Specifying a default value for a text input element:

	echo Form::text('email', 'example@gmail.com');

> **Note:** The *hidden* and *textarea* methods have the same signature as the *text* method. You just learned three methods for the price of one!

#### Generating a password input element:

	echo Form::password('password');

<a name="checkboxes-and-radio-buttons"></a>
## Checkboxes and Radio Buttons

#### Generating a checkbox input element:

	echo Form::checkbox('name', 'value');

#### Generating a checkbox that is checked by default:

	echo Form::checkbox('name', 'value', true);

> **Note:** The *radio* method has the same signature as the *checkbox* method. Two for one!

<a name="file"></a>
## File Input

#### Generate a file input element:

	echo Form::file('image');

<a name="drop-down-lists"></a>
## Drop-Down Lists

#### Generating a drop-down list from an array of items:

	echo Form::select('size', array('L' => 'Large', 'S' => 'Small'));

#### Generating a drop-down list with an item selected by default:

	echo Form::select('size', array('L' => 'Large', 'S' => 'Small'), 'S');

<a name="buttons"></a>
## Buttons

#### Generating a submit button element:

	echo Form::submit('Click Me!');

> **Note:** Need to create a button element? Try the *button* method. It has the same signature as *submit*.

<a name="custom-macros"></a>
## Custom Macros

It's easy to define your own custom Form class helpers called "macros". Here's how it works. First, simply register the macro with a given name and a Closure:

#### Registering a Form macro:

	Form::macro('myField', function()
	{
		return '<input type="awesome">';
	});

Now you can call your macro using its name:

#### Calling a custom Form macro:

	echo Form::myField();


---


<a name="str_class"></a>
# Working With Strings

## Contents

- [Capitalization, Etc.](#capitalization)
- [Word & Character Limiting](#limits)
- [Generating Random Strings](#random)
- [Singular & Plural](#singular-and-plural)
- [Slugs](#slugs)
- [Case Conversion](#case)
- [String Searching](#search)

<a name="capitalization"></a>
## Capitalization, Etc.

The **Str** class provides three convenient methods for manipulating string capitalization: **upper**, **lower**, and **title**. These are more intelligent versions of the PHP [strtoupper](http://php.net/manual/en/function.strtoupper.php), [strtolower](http://php.net/manual/en/function.strtolower.php), and [ucwords](http://php.net/manual/en/function.ucwords.php) methods. More intelligent because they can handle UTF-8 input if the [multi-byte string](http://php.net/manual/en/book.mbstring.php) PHP extension is installed on your web server. To use them, just pass a string to the method:

~~~php
echo Str::lower('I am a string.');
// i am a string.

echo Str::upper('I am a string.');
// I AM A STRING.

echo Str::title('I am a string.');
// I Am A String.
~~~

**Additional methods:**

`length( $string )`: Get the length of a string.

~~~php
// Get the length of a string
$length = Str::length('Taylor Otwell');

// Get the length of a multi-byte string
$length = Str::length('Τάχιστη')
~~~

`upperWords( $string ):` Convert first letter of each word to uppercase.

<a name="limits"></a>
## Word & Character Limiting

#### Limiting the number of characters in a string:

~~~php
echo Str::limit("Lorem ipsum dolor sit amet", 10);
// Lorem ipsu...

echo Str::limitExact("Lorem ipsum dolor sit amet", 10);
// Lorem i...

// Limit the number of characters and append a custom ending
echo Str::limitExact('Taylor Otwell', 9, '---');
~~~

#### Limiting the number of words in a string:

~~~php
echo Str::words("Lorem ipsum dolor sit amet", 3);
// Lorem ipsum dolor...

// Limit the number of words and append a custom ending
echo Str::words('This is a sentence.', 3, '---');
~~~

`wordwrap( $string, $length )`: Adds a space to a string after a given amount of contiguous, non-whitespace characters.

<a name="random"></a>
## Generating Random Strings

#### Generating a random string of alpha-numeric characters:

~~~php
echo Str::random(32);
~~~

#### Generating a random string of alphabetic characters:

~~~php
echo Str::random(32, 'alpha');
~~~

<a name="singular-and-plural"></a>
## Singular & Plural

#### Getting the plural form of a word:

~~~php
echo Str::plural('user');
// users
~~~

#### Getting the singular form of a word:

~~~php
echo Str::singular('users');
// user
~~~

#### Getting the plural form if specified value is greater than one:

~~~php
echo Str::plural('comment', count($comments));
~~~

<a name="slugs"></a>
## Slugs

#### Generating a URL friendly slug:

~~~php
return Str::slug('My First Blog Post!');
// my-first-blog-post
~~~

#### Generating a URL friendly slug using a given separator:

~~~php
return Str::slug('My First Blog Post!', '_');
// my_first_blog_post
~~~

<a name="case"></a>
## Case Conversion

`ascii( $value )`: Convert a string to 7-bit ASCII.

`classify( $value )`: Convert a string to an underscored, camel-cased class name.

~~~php
$class = Str::classify('task_name'); // Returns "Task_Name"

$class = Str::classify('taylor otwell') // Returns "Taylor_Otwell"
~~~

`camelCase( $value )`: Convert a value to camel case.

<a name="search"></a>
## String Searching

`is( $pattern, $value )`: Determine if a given string matches a given pattern.

`endsWith( $haystack, $needle )`: Determine if a given string ends with a given needle.

`startsWith( $haystack, $needle )`: Determine if a string starts with a given needle.

`contains( $haystack, $needle )`: Determine if a given string contains a given sub-string.