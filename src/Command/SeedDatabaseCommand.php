<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-db',
    description: 'Seeds categories and products into the database',
)]
class SeedDatabaseCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('Purging existing categories and products...');
        $this->entityManager->createQuery('DELETE FROM App\Entity\Product')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Entity\Category')->execute();

        $categoriesData = [
            'Electronics' => 'Headphones, speakers, gadgets and more',
            'Fashion' => 'Clothing, accessories and footwear',
            'Home & Garden' => 'Furniture, decor and gardening tools',
            'Sports & Fitness' => 'Workout gear, yoga mats and equipment',
            'Books' => 'Fiction, non-fiction and educational books',
            'Beauty & Health' => 'Skincare, cosmetics and wellness products',
            'Toys & Games' => 'Fun for kids and family entertainment',
            'Automotive' => 'Car accessories and maintenance tools',
            'Pet Supplies' => 'Food, toys and accessories for pets',
        ];

        $categories = [];
        foreach ($categoriesData as $name => $desc) {
            $cat = new Category();
            $cat->setName($name);
            $cat->setDescription($desc);
            $this->entityManager->persist($cat);
            $categories[$name] = $cat;
        }

        $productsData = [
            [
                'name' => 'Wireless Headphones',
                'description' => 'Experience high-quality sound with these comfortable wireless headphones featuring active noise cancellation.',
                'price' => 79.99,
                'category' => 'Electronics',
                'image' => 'airbod.png',
                'isTop' => true,
            ],
            [
                'name' => 'Bluetooth Speaker',
                'description' => 'Portable waterproof bluetooth speaker with immersive 360-degree sound and 12-hour battery life.',
                'price' => 59.99,
                'category' => 'Electronics',
                'image' => 'mouse.png',
                'isTop' => true,
            ],
            [
                'name' => 'Smartphone Stand',
                'description' => 'Adjustable aluminum phone stand for desk, compatible with all smartphones and tablets.',
                'price' => 19.99,
                'category' => 'Electronics',
                'image' => 'mouse.png',
                'isTop' => false,
            ],
            [
                'name' => 'USB-C Cable 2m',
                'description' => 'Durable nylon braided USB-C to USB-C fast charging cable, 2 meters length.',
                'price' => 12.99,
                'category' => 'Electronics',
                'image' => 'mouse.png',
                'isTop' => false,
            ],
            [
                'name' => 'Wireless Mouse',
                'description' => 'Ergonomic optical wireless mouse with adjustable DPI and silent clicks.',
                'price' => 29.99,
                'category' => 'Electronics',
                'image' => 'mouse.png',
                'isTop' => false,
            ],
            [
                'name' => 'Mechanical Keyboard',
                'description' => 'RGB backlit mechanical keyboard with blue switches, perfect for gaming and typing.',
                'price' => 89.99,
                'category' => 'Electronics',
                'image' => 'mouse.png',
                'isTop' => false,
            ],
            [
                'name' => 'Webcam HD 1080p',
                'description' => 'Full HD 1080p webcam with built-in microphone and privacy cover for video calls.',
                'price' => 49.99,
                'category' => 'Electronics',
                'image' => 'mouse.png',
                'isTop' => false,
            ],
            [
                'name' => 'Power Bank 20000mAh',
                'description' => 'High-capacity external battery pack with dual USB ports and power delivery.',
                'price' => 39.99,
                'category' => 'Electronics',
                'image' => 'mouse.png',
                'isTop' => false,
            ],
            [
                'name' => 'Smart Watch Pro',
                'description' => 'Waterproof smartwatch with heart rate monitor, sleep tracking, and built-in GPS.',
                'price' => 199.99,
                'category' => 'Electronics',
                'image' => 'mouse.png',
                'isTop' => false,
            ],
            [
                'name' => 'Classic Leather Jacket',
                'description' => 'Premium genuine leather jacket with classic styling, zip closure, and multiple pockets.',
                'price' => 149.99,
                'category' => 'Fashion',
                'image' => 'item.png',
                'isTop' => true,
            ],
            [
                'name' => 'Smart Plant Sensor',
                'description' => 'Bluetooth smart plant monitor that tracks soil moisture, fertility, temperature, and sunlight.',
                'price' => 34.99,
                'category' => 'Home & Garden',
                'image' => 'item.png',
                'isTop' => true,
            ],
            [
                'name' => 'Yoga Mat Premium',
                'description' => 'Eco-friendly non-slip yoga mat with carrying strap, 6mm thickness for joint support.',
                'price' => 29.99,
                'category' => 'Sports & Fitness',
                'image' => 'item.png',
                'isTop' => true,
            ],
            [
                'name' => 'Web Development Guide',
                'description' => 'Comprehensive guide to modern web development covering HTML5, CSS3, JavaScript, and PHP/Symfony.',
                'price' => 24.99,
                'category' => 'Books',
                'image' => 'item.png',
                'isTop' => true,
            ],
        ];

        foreach ($productsData as $pData) {
            $p = new Product();
            $p->setName($pData['name']);
            $p->setDescription($pData['description']);
            $p->setPrice($pData['price']);
            $p->setImage($pData['image']);
            $p->setIsTop($pData['isTop']);
            $p->setCategory($categories[$pData['category']]);
            $this->entityManager->persist($p);
        }

        $this->entityManager->flush();

        $io->success('Database seeded successfully with Categories and Products!');

        return Command::SUCCESS;
    }
}
