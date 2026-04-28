final <?php

namespace App\Product\Command\Upload;

use App\Product\Entity\UploadDir;
use App\Product\Service\FileHandler;

class Handler
{


    public function handle(Command $command): Response
    {
        $this->uploadDir->setTargetPath($command->targetPath);

        $fileHandler = new FileHandler(
            $this->uploadDir
        );
        $result = $fileHandler->handle($command->uploadFile);

        return Response::fromArray($result);
    }
}