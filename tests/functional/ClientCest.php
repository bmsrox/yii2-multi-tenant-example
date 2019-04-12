<?php


class ClientCest
{
    // tests
    public function openClientIndexPage(FunctionalTester $I)
    {
        $I->amOnPage(['client/index']);
        $I->see('Clients', 'h1');
        $I->canSeeElement('.grid-view');
    }

    public function createAClientIncorrect(\FunctionalTester $I)
    {
        $I->amOnPage(['client/create']);
        $I->submitForm('#client-form', [
            'Client[name]' => 'test',
            'Client[surname]' => 'test',
            'Client[email]' => 'test',
        ]);
        $I->expectTo('see that email address is wrong');
        $I->see('Email is not a valid email address.', '.help-block');
    }

    public function submitEmptyForm(\FunctionalTester $I)
    {
        $I->amOnPage(['client/create']);
        $I->submitForm('#client-form', []);
        $I->expectTo('see validations errors');
        $I->see('Create Client', 'h1');
        $I->see('Name cannot be blank.', '.help-block');
        $I->see('Surname cannot be blank.', '.help-block');
        $I->see('Email cannot be blank.', '.help-block');
    }

    public function createAClientSuccessfully(\FunctionalTester $I)
    {
        $I->amOnPage(['client/create']);
        $I->submitForm('#client-form', [
            'Client[name]' => 'John',
            'Client[surname]' => 'Doe',
            'Client[email]' => 'john@example.com'
        ]);
        $I->dontSeeElement('#client-form');
    }
}
