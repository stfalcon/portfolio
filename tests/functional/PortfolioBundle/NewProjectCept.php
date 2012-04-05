<?php
$I = new TestGuy($scenario);
$I->wantTo('create new project');
$I->amHttpAuthenticated('admin','qwerty');
$I->amOnPage('/admin/portfolio/projects');
$I->click('Create new project');
$I->see('Create new project','h4');
$I->fillField('Name','Codeception');
$I->fillField('Slug','codeception-php');
$I->fillField('Url','http://codeception.com');
$I->selectOption('#project_date_year','2012');
$I->selectOption('#project_date_month','01');
$I->selectOption('#project_date_day','01');
$I->attachFile('Image','banner.jpg');
$I->fillField('Description','This is my sample project');
$I->fillField('Users','Davert');
$I->checkOption('Web Development');
$I->click('Send');
$I->see('Congratulations');
