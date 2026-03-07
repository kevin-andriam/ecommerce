<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Créer les catégories
        $categories = [];

        $cat1 = new Category();
        $cat1->setNom('Électronique');
        $manager->persist($cat1);
        $categories[] = $cat1;

        $cat2 = new Category();
        $cat2->setNom('Vêtements');
        $manager->persist($cat2);
        $categories[] = $cat2;

        $cat3 = new Category();
        $cat3->setNom('Maison');
        $manager->persist($cat3);
        $categories[] = $cat3;
        

        // Créer les produits
        $products = [
            ['Smartphone XZ', 'Un smartphone puissant avec écran AMOLED.', '299.99', 50, $cat1],
            ['Laptop Pro', 'Ordinateur portable ultra-fin 14 pouces.', '899.99', 20, $cat1],
            ['Casque Bluetooth', 'Son HD sans fil avec réduction de bruit.', '79.99', 100, $cat1],
            ['T-shirt Premium', 'T-shirt 100% coton bio, disponible en plusieurs couleurs.', '19.99', 200, $cat2],
            ['Jean Slim', 'Jean slim coupe moderne et confortable.', '49.99', 150, $cat2],
            ['Veste en cuir', 'Veste en cuir véritable style vintage.', '149.99', 30, $cat2],
            ['Lampe de bureau', 'Lampe LED avec réglage de luminosité.', '39.99', 75, $cat3],
            ['Tapis salon', 'Tapis doux et résistant 160x230cm.', '89.99', 40, $cat3],
            ['Cafetière', 'Cafetière expresso avec mousseur de lait.', '129.99', 60, $cat3],
        ];

        foreach ($products as [$name, $desc, $price, $stock, $category]) {
            $product = new Product();
            $product->setName($name);
            $product->setDescription($desc);
            $product->setPrice($price);
            $product->setStock($stock);
            $product->setCategory($category);
            $product->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($product);
        }

        // Créer un admin
        $admin = new User();
        $admin->setEmail('admin@monshop.com');
        $admin->setFirstName('Admin');
        $admin->setLastName('MonShop');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // Créer un utilisateur test
        $user = new User();
        $user->setEmail('user@monshop.com');
        $user->setFirstName('Jean');
        $user->setLastName('Dupont');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, 'user123'));
        $manager->persist($user);

        $manager->flush();
    }
}