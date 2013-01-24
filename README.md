laravel4-powerpack
==================

Brings back helpers to Laravel 4

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