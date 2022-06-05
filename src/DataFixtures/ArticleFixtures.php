<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        // Créer 3 catégories fakées

        for ($i = 1; $i < 3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                ->setDescription($faker->paragraph());

            $manager->persist($category);

            // Créer entre 4 et 6 articles
            // mt_rand équivaut à un math.random()
            for ($j = 1; $j <= mt_rand(4, 6); $j++) {
                $article = new Article();

                // On veut ajouter 5 paragraphes (qui sont des tableaux) et les lier avec un début de <p> et une fin de </p>
                $content = '<p>' . join($faker->paragraphs(5), '</p> <p>') . '</p>';

                $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt(new \DateTimeImmutable())
                    // On l'associe à une categorie
                    ->setCategory($category);

                $manager->persist($article);

                // On donne des commentaires à l'article
                for ($k = 1; $k <= mt_rand(4, 10); $k++) {
                    $comment = new Comment();

                    $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';

                    // on créé un nouveau DateTime, on fait la différence avec la date de création de l'article et on en extrait les jours
                    // $days = (new \DateTimeImmutable())->diff($article->getCreatedAt())->days;

                    $comment->setAuthor($faker->name)
                        ->setContent($content)
                        ->setCreatedAt(new \DateTimeImmutable())
                        ->setArticle($article);

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
