<?php
$I = new TestGuy($scenario);
$I->wantTo('see blog post');
$I->amOnPage('/blog');
$I->seeLink('Post about php');
$I->click('Post about php');
$I->see('The PHP development team would like to announce the immediate availability of PHP 5.3.6');
$I->seeInCurrentUrl('post-about-php');
$I->see('Post about php','h1');
