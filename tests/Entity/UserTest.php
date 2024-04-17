<?php
// tests/Entity/UserTest.php
namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetterAndSetter()
    {
        // Création d'une instance de l'entité User
        $user = new User();

        // Définition de données de test
        $email = 'test@test.com';
        $firstName = 'John';
        $lastName = 'Doe';
        $password = 'test';
        $role = 'user';
        $subscription_id = null;
        $subscription_end_at = new \DateTime();
        $created_at = new \DateTime();
        $updated_at = new \DateTime();


        // Utilisation des setters
        $user->setEmail($email);
        $user->setFirstname($firstName);
        $user->setLastname($lastName);
        $user->setPassword($password);
        $user->setRole($role);
        $user->setSubscriptionId($subscription_id);
        $user->setSubscriptionEndAt($subscription_end_at);
        $user->setCreatedDate($created_at);
        $user->setUpdatedAt($updated_at);

        // Vérification des getters
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($firstName, $user->getFirstname());
        $this->assertEquals($lastName, $user->getLastname());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($role, $user->getRole());
        $this->assertEquals($subscription_id, $user->getSubscriptionId());
        $this->assertEquals($subscription_end_at, $user->getSubscriptionEndAt());
        $this->assertEquals($created_at, $user->getCreatedDate());
        $this->assertEquals($updated_at, $user->getUpdatedAt());
    }
}