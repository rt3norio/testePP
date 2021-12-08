<?php


use App\Models\User;


class UserControllerTest extends TestCase
{


    public function testGetUserReturnsCorrectly()
    {
        $this->refreshTestData();
        $user = User::first();

        $this->get("/user/$user->id");
        $response = json_decode($this->response->getContent());
        $this->assertEquals(
            $user->email,
            $response->email
        );
    }

    public function testCreateUserWorksCorrectly()
    {
        $this->deleteAllData();

        $this->post("/user", [
            'name' => 'user99',
            'email' => 'user99@user99.com',
            'password' => '1234',
            'taxCode' => '00009',
            'store' => false,
        ]);
        $response = json_decode($this->response->getContent());
        $user = User::find($response->id);
        $this->assertEquals($user->email, $response->email);
    }

    public function testCreateUserEnforcesUniqueEmail()
    {
        $this->deleteAllData();

        $this->post("/user", [
            'name' => 'user99',
            'email' => 'user99@user99.com',
            'password' => '1234',
            'taxCode' => '00009',
            'store' => false,
        ]);
        $this->post("/user", [
            'name' => 'user100',
            'email' => 'user99@user99.com',
            'password' => '1234',
            'taxCode' => '000010',
            'store' => false,
        ]);
        $this->response->assertUnprocessable();
    }

    public function testCreateUserEnforcesUniqueTaxCode()
    {
        $this->deleteAllData();

        $this->post("/user", [
            'name' => 'user99',
            'email' => '00000@user99.com',
            'password' => '1234',
            'taxCode' => '00009',
            'store' => false,
        ]);
        $this->post("/user", [
            'name' => 'user100',
            'email' => '999999@user99.com',
            'password' => '1234',
            'taxCode' => '00009',
            'store' => false,
        ]);
        $this->response->assertUnprocessable();
    }
}
