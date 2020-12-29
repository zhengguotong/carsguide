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


    /**
     * Test create get Item by id
     *
     * @return void
     */
    public function testUpdate()
    {
        Item::factory()->create(
            ['email' => 'test1@test.com', 'name' => 'test']
        );

        Item::factory()->create(
            ['email' => 'test2@test.com', 'name' => 'test']
        );

        Item::factory()->count(10)->create();

        //test item not found
        $this->call('PUT', '/items/11111')
            ->assertStatus(404);

        //test validation
        $this->put('items/1', array('email' => 'test1!kl'))
            ->seeJson([
                'email' => array('The email must be a valid email address.'),
            ]);

        //test exception
        $this->put('items/1', array('email' => 'test2@test.com'))
            ->seeJsonStructure([
                'error', 'code'
            ])
            ->seeJson(['code' => 400]);

        //test success updated item
        $this->put('items/1', array('email' => 'test3@test.com', 'name' => 'test1 updated'))
            ->seeJson([
                'status' => 'ok',
            ])
            ->seeJson([
                'email' => 'test3@test.com',
            ])
            ->seeJson([
                'name' => 'test1 updated',
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

        $this->seeInDatabase('items', array('email' => 'test3@test.com', 'name' => 'test1 updated'));
    }


    /**
     * Test delete an item given by id
     *
     * @return void
     */
    public function testDestroy()
    {
        Item::factory()->create(
            ['email' => 'test1@test.com', 'name' => 'test']
        );
        Item::factory()->count(10)->create();

        //test item not found
        $this->delete('/items/11111')
            ->seeJson([
                'error' => 'Item Not Found',
            ])
            ->seeJson([
                'code' => 404,
            ]);

        //test success deleted item
        $this->delete('/items/1')
            ->seeJson([
                'message' => 'Item 1 deleted',
            ])
            ->seeJson([
                'status' => 'ok',
            ]);

        $this->notSeeInDatabase('items', array('id' => 1));
    }
}
