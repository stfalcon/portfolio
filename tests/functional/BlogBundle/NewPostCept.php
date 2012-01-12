<?php

$I = new TestGuy($scenario);
$I->wantTo('create new blog post');
$I->amHttpAuthenticated('admin','qwerty');
$I->amOnPage('/admin/blog/posts');
$I->click('Create new post');
$I->see('Create new post','h4');
$I->fillField('Title','Codeception, a new way of testing!');
$I->fillField('#post_slug','codeception-testing');
$I->fillField('Text','Codeception is new testing frameework for testing');
$I->fillField('Tags','php testing');
$I->click('Send');
$I->see('Congratulations, your post is successfully created!');

