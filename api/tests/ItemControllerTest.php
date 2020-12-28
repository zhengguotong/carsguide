<?php

use App\Models\Item;
use Laravel\Lumen\Testing\DatabaseMigrations;

class ItemControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test get all items endpoints
     *
     * @return void
     */
    public function testIndex()
    {
        Item::factory()->count(20)->create();

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

    /**
     * Test create new Item
     *
     * @return void
     */
    public function testStore()
    {
        //test validation
        $this->post('items', array('email' => 'test1!kl'))
            ->seeJson([
                'email' => array('The email must be a valid email address.'),
            ])
            ->seeJson([
                'name' => array('The name field is required.'),
            ]);

        //test success case
        $this->post('items', array('email' => 'test1@test.com', 'name' => 'test'))
            ->seeJson([
                'status' => 'ok',
            ])
            ->seeJson([
                'email' => 'test1@test.com',
            ])
            ->seeJson([
                'name' => 'test',
            ])
            ->seeJsonStructure([
                'data' =>  [
                    'id',
                    'guid',
                    'name',
                    'email',
                    'created_dates',
                    'updated_dates',
                ]
            ]);

        $this->seeInDatabase('items', array('email' => 'test1@test.com', 'name' => 'test'));

        //test duplicated email
        $this->post('items', array('email' => 'test1@test.com', 'name' => 'test'))
            ->seeJson([
                'email' => array('The email has already been taken.'),
            ]);
    }

    /**
     * Test create get Item by id
     *
     * @return void
     */
    public function testShow()
    {
        Item::factory()->create(
            ['email' => 'test1@test.com', 'name' => 'test']
        );
        Item::factory()->count(10)->create();

        //Test success get the item
        $this->json('GET', '/items/1')
            ->seeJson([
                'status' => 'ok',
            ])
            ->seeJson([
                'email' => 'test1@test.com',
            ])
            ->seeJson([
                'name' => 'test',
            ])
            ->seeJsonStructure([
                'data' =>  [
                    'id',
                    'guid',
                    'name',
                    'email',
                    'created_dates',
                    'updated_dates',
                ]
            ]);

        //test item not found
        $this->call('GET', '/items/11111')
            ->assertStatus(404);
    }
}
