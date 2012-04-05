<?php
$I = new TestGuy($scenario);
$I->wantTo('create new category');
$I->amHttpAuthenticated('admin','qwerty');
$I->amOnPage('/admin/portfolio/categories');
$I->click('Create new category');
$I->see('Create new category', 'h4');
$I->submitForm('form', array('category' => array('name' => 'Design', 'slug' => 'design', 'description' => 'Our designs are very very cool')));
$I->see('Congratulations, your category is successfully created!');
