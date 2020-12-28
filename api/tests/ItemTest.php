<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ItemTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->call('GET', '/items')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'guid',
                        'name',
                        'email',
                        'created_dates',
                        'updated_dates',
                    ]
                    ]
            ])
            ->assertJsonPath('meta.current_page', 1);

        //test paginate
        $parameters = array(
            'per_page' => 10,
             'page' => 2,
        );

        $this->call('GET', '/items', $parameters)
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'guid',
                    'name',
                    'email',
                    'created_dates',
                    'updated_dates',
                ]
                ]
        ])
        ->assertJsonPath('meta.current_page', $parameters['page'])
        ->assertJsonPath('meta.per_page', $parameters['per_page']);
    }
}
