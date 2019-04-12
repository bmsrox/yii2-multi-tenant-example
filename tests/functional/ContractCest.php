<?php


use app\fixtures\ClientFixture;

class ContractCest
{

    public function _fixtures()
    {
        return [
            'client' => [
                'class' => ClientFixture::class,
                'dataFile' => codecept_data_dir() . 'client_data.php',
            ],
        ];
    }

    // tests
    public function openContractIndexPage(FunctionalTester $I)
    {
        $I->amOnPage(['contract/index']);
        $I->see('Contract', 'h1');
        $I->canSeeElement('.grid-view');
    }

    public function createAContractIncorrect(\FunctionalTester $I)
    {
        $I->amOnPage(['contract/create']);
        $I->submitForm('#contract-form', [
            'Contract[buyer_client]' => '',
            'Contract[seller_client]' => '',
            'Contract[financial_amount]' => '10.00',
            'Contract[description]' => 'test'
        ]);

        $I->see('Buyer Client cannot be blank.', '.help-block');
        $I->see('Seller Client cannot be blank.', '.help-block');
    }

    public function submitEmptyForm(\FunctionalTester $I)
    {
        $I->amOnPage(['contract/create']);
        $I->submitForm('#contract-form', []);
        $I->expectTo('see validations errors');
        $I->see('Create Contract', 'h1');
        $I->see('Buyer Client cannot be blank.', '.help-block');
        $I->see('Seller Client cannot be blank.', '.help-block');
        $I->see('Financial Amount cannot be blank.', '.help-block');
        $I->see('Description cannot be blank.', '.help-block');
    }

    public function createAContractSuccessfully(\FunctionalTester $I)
    {
        $I->amOnPage(['contract/create']);

        $buyer = $I->grabFixture('client', 0);
        $seller = $I->grabFixture('client', 1);

        $I->submitForm('#contract-form', [
            'Contract[buyer_client]' => $buyer->id,
            'Contract[seller_client]' => $seller->id,
            'Contract[financial_amount]' => '10.00',
            'Contract[description]' => 'test'
        ]);
        $I->dontSeeElement('#contract-form');
    }
}
