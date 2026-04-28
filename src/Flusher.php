final <?php

namespace App;

use Doctrine\ORM\EntityManagerInterface;

class Flusher
{
    private EntityManagerInterface $em;

    public function flush(): void
    {
        $this->em->flush();
    }
}