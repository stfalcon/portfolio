<?php

$I = new TestGuy($scenario);
$I->wantTo('edit post');
$I->amHttpAuthenticated('admin','qwerty');
$I->amOnPage('/admin/blog/posts');
$I->seeLink('Edit Post','edit/post-about-php');
$I->click('Edit Post');
$I->see('Edit post','h4');
$I->seeInCurrentUrl('edit');
$I->fillField('Title','Greatest Post Ever');
$I->click('Save');
$I->see('Congratulations, your post is successfully updated!');
$I->see('Greatest Post Ever');