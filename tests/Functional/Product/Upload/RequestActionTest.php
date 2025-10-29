<?php

namespace Test\Functional\Product\Upload;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Psr7\UploadedFile;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

class RequestActionTest extends WebTestCase
{
    use ArraySubsetAsserts;

    private array $tempFiles = [];
    public function testSuccess(): void
    {
        $uploadedFile = $this->buildUploadedFile('test', 'data', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',UPLOAD_ERR_OK);

        $response = $this->app()->handle(self::formData(
            'POST',
            '/payment-service/products/upload',
            ['path' => 'fire/992'],
            ['file' => $uploadedFile]
        ));

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($body = $response->getBody());
        
        $data = Json::decode($body);

        self::assertArraySubset([
            'name' => $uploadedFile->getClientFilename(),
            'mime_type' => $uploadedFile->getClientMediaType(),
            'size' => $uploadedFile->getSize(),
            'path' => '/tmp/fire/992/'.$uploadedFile->getClientFilename(),
        ], $data);
        
    }

    public function testEmptyFile(): void
    {
        $response = $this->app()->handle(self::formData(
            'POST', '/payment-service/products/upload', ['path' => 'fire/992']));

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($body = $response->getBody());

        $data = Json::decode($body);

        self::assertArraySubset([
            "message" => "File upload failed",
        ], $data);
    }

    public function testMultiUpload(): void
    {
        $tempFileOne = tempnam(sys_get_temp_dir(), 'test_upload_1');
        file_put_contents($tempFileOne, 'test content1');

        $tempFileTwo = tempnam(sys_get_temp_dir(), 'test_upload_2');
        file_put_contents($tempFileTwo, 'test content2');

        $response = $this->app()->handle(self::formData(
            'POST', '/payment-service/products/upload', ['path' => 'fire/992'], ['file' => [
                new UploadedFile($tempFileOne, '992', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', filesize($tempFileOne), UPLOAD_ERR_OK),
                new UploadedFile($tempFileTwo, '993', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', filesize($tempFileTwo), UPLOAD_ERR_OK)
                ]
            ]
        ));

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($body = $response->getBody());

        $data = Json::decode($body);

        self::assertArraySubset(['message' => 'File upload failed'], $data);
    }

    public function testEmptyPath(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test_upload_');
        file_put_contents($tempFile, 'test content');



        $response = $this->app()->handle(self::formData('POST', '/payment-service/products/upload', [], [
            'file' => new UploadedFile($tempFile, '992', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', filesize($tempFile), UPLOAD_ERR_OK)
        ]));
        self::assertEquals(500, $response->getStatusCode());
        self::assertJson($body = $response->getBody());
        $data = Json::decode($body);

        self::assertArraySubset(['message' => 'Expected a non-empty value. Got: ""'], $data);
    }
    public function testInvalidMimeType(): void
    {
        $uploadedFile = $this->buildUploadedFile('test', 'data', $invalidFileType = 'text/plain', UPLOAD_ERR_OK);
        $response = $this->app()->handle(self::formData(
            'POST', '/payment-service/products/upload', ['path' => 'fire/992'], ['file' => $uploadedFile])
        );

        self::assertEquals(500, $response->getStatusCode());
        self::assertJson($body = $response->getBody());
        $data = Json::decode($body);

        self::assertArraySubset(['message' => 'Invalid file type '. $invalidFileType], $data);
    }

    public function testUploadFailed(): void
    {
        $uploadedFile = $this->buildUploadedFile('test', 'data',  'application/vnd.openxmlformats-officedocument.wordprocessingml.document', UPLOAD_ERR_NO_FILE);
        $response = $this->app()->handle(self::formData('POST', '/payment-service/products/upload', ['path' => 'fire/992'], ['file' => $uploadedFile]));

        self::assertEquals(500, $response->getStatusCode());

        self::assertJson($body = $response->getBody());
        $data = Json::decode($body);

        self::assertArraySubset(['message' => 'Error uploading file '. $uploadedFile->getError()], $data);
    }

    public function testUploadExisting(): void
    {
        $uploadedFile = $this->buildUploadedFile('test', 'data', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',UPLOAD_ERR_OK);
        $response = $this->app()->handle(self::formData('POST', '/payment-service/products/upload', ['path' => 'fire/992'], ['file' => $uploadedFile]));

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($body = $response->getBody());

        $data = Json::decode($body);

        self::assertEquals('data', file_get_contents($data['path']));

        $uploadedFile = $this->buildUploadedFile('test', 'data2', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',UPLOAD_ERR_OK);

        $response = $this->app()->handle(self::formData('POST', '/payment-service/products/upload', ['path' => 'fire/992'], ['file' => $uploadedFile]));

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($body = $response->getBody());
        $data = Json::decode($body);

        self::assertEquals('data2', file_get_contents($data['path']));

    }
    private function buildUploadedFile(string $name, string $content, string $type, int $error): UploadedFileInterface
    {
        $tempFile = tempnam(sys_get_temp_dir(), $name);
        file_put_contents($tempFile, $content);
        $this->tempFiles[] = $tempFile;

        return new UploadedFile(
            $tempFile,
            $name,
            $type,
            filesize($tempFile),
            $error
        );
    }

    public function tearDown(): void
    {
        foreach ($this->tempFiles as $tempFile) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    }
}