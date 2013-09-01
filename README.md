PHPUnit ClassMethodTest
====

Introduction
----

Main features:
* test specific methods
* use of existing class properties/constants
* constructor dependency injection
* apply code coverage for tested methods

ClassMethodTest is a library that helps you to test specific methods of classes which are not made for unit tests.
Consider classes which have too much responsibility and/or dependencies are directly resolved within the class.
The author of this class might never heard about common software principles (IoC, DI, SOLID).
Now you have to extend one of those classes with functionality.
Being a professional developer you want to test your code and make sure it behaves exactly as you intend to do.
But for some reason (time, costs etc.) you cannot refactor.
In this case you can built a ClassMethodTest that will support you in testing the new code.

<b>Whenever you have the possibility and time to refactor a class DO IT and avoid using this library.</b>
I don't want encourage in writing bad code.

Installation
----

create new directory

    mkdir /path/to/phpunit-class-method-test

checkout ClassMethodTest

    git clone git@github.com:flodev/phpunit-class-method-test.git .

get composer and install

    curl -s https://getcomposer.org/installer | php

    php composer.phar install

link phpunit as an executable

    sudo ln -s /path/to/phpunit-class-method-test/vendor/bin/phpunit /usr/local/bin/phpunit

<b>Install to existing PHPUnit installation</b>

You can use the autoloader delivered with this package.
Add a line to your phpunit bootstrap.php

assuming the following structure
-- phpunit-class-method-test/autoloader.php
-- your-project/bootstrap.php

it should be
<pre><code>
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'phpunit-class-method-test' . DIRECTORY_SEPARATOR . 'autoloader.php';
</code></pre>

Use code coverage
----

add TestListener to your phpunit.xml

<pre><code>
<phpunit>
    <listeners>
        <listener class="\ClassMethodTest\TestListener">
        </listener>
    </listeners>
</phpunit>
</code></pre>