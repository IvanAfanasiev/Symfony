<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $article = new Article();
        $article->setContent(
            'On then sake home is am leaf. Of suspicion do departure at extremely he believing.
             Do know said mind do rent they oh hope of.
             General enquire picture letters garrets on offices of no on. 
             Say one hearing between excited evening all inhabit thought you. 
             Style begin mr heard by in music tried do. To unreserved projection no introduced invitation. '
        );
        $article->setTitle('title');
        $article->setDateAdded(new DateTime('01.01.2024'));
        $manager->persist($article);

        $article1 = new Article();
        $article1->setContent(
            'On then sake home is am leaf. Of suspicion do departure at extremely he believing.
             Do know said mind do rent they oh hope of.
             General enquire picture letters garrets on offices of no on. 
             Say one hearing between excited evening all inhabit thought you. 
             Style begin mr heard by in music tried do. To unreserved projection no introduced invitation. '
        );
        $article1->setTitle('title1');
        $article1->setDateAdded(new DateTime('01.01.2024'));
        $manager->persist($article1);

        $article2 = new Article();
        $article2->setContent(
            'On then sake home is am leaf. Of suspicion do departure at extremely he believing.
             Do know said mind do rent they oh hope of.
             General enquire picture letters garrets on offices of no on. 
             Say one hearing between excited evening all inhabit thought you. 
             Style begin mr heard by in music tried do. To unreserved projection no introduced invitation. '
        );
        $article2->setTitle('title2');
        $article2->setDateAdded(new DateTime('01.01.2024'));
        $manager->persist($article2);

        



        $manager->flush();
    }
}
