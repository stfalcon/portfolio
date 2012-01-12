<?php
$I = new TestGuy($scenario);
$I->wantTo('sign in into admin');
$I->amHttpAuthenticated('admin','qwerty');
$I->amOnPage('/admin/blog/posts');
$I->see('Posts');
