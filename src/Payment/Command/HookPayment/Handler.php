<?php

namespace App\Payment\Command\HookPayment;

use App\Flusher;
use App\Payment\Entity\Email;
use App\Payment\Entity\PaymentRepository;
use App\Payment\Entity\Status;
use App\Payment\Service\ProductSender;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\Service\Payment\DTO\PaymentCallbackDTO;
use App\Shared\Domain\Service\Payment\PaymentProviderInterface;
use App\Shared\Domain\Service\Payment\PaymentStatus;
use App\Shared\Domain\Service\Payment\PaymentWebhookDataInterface;
use App\Shared\Domain\Service\Payment\PaymentWebhookParserInterface;
use App\Shared\ValueObject\Id;

class Handler
{
    public function __construct(
        private readonly PaymentWebhookParserInterface  $webhookParser,
        private readonly PaymentProviderInterface       $provider,
        private readonly ProductSender $sender,
        private readonly ProductRepository $productRepository,
        private readonly PaymentRepository $paymentRepository,
        private readonly Flusher $flusher
    )
    {}
    public function handle(Command $command): void
    {
        $data = json_decode($command->requestBody, true);
        if(json_last_error() !== JSON_ERROR_NONE){
            throw new \InvalidArgumentException('Invalid json request body');
        }

        $callbackDTO = new PaymentCallbackDTO(
            $data,
            $_SERVER['HTTP_CONTENT_SIGNATURE'] ?? '',
            $this->provider->getName()
        );

        if(!$this->webhookParser->supports($callbackDTO->provider, $callbackDTO->rawData)){
            throw new \RuntimeException('Unsupported webhook format');
        }

        $paymentId = $this->provider->handleCallback($callbackDTO);

        if (null === $paymentId) {
            return;
        }

        $payment = $this->paymentRepository->getByExternalId($paymentId);

        $paymentWebHookData = $this->webhookParser->parse($callbackDTO->rawData);

        if($this->shouldSendProduct($paymentWebHookData)){
            try{
                $this->sendProduct($paymentWebHookData);

                $payment->setStatus(Status::succeeded());

                $this->paymentRepository->update($payment);

                $this->flusher->flush();

            }catch (\Exception $e){
                throw new \RuntimeException('Failed to send product: ' . $e->getMessage());
            }

        }


    }

    private function shouldSendProduct(PaymentWebhookDataInterface $webhookData): bool
    {
        return $webhookData->isPaid() && $webhookData->getStatus() === PaymentStatus::SUCCEEDED;
    }

    private function sendProduct(PaymentWebhookDataInterface $paymentWebHookData): void
    {
        $productId = $paymentWebHookData->getMetadata('productId');
        $email = $paymentWebHookData->getMetadata('email');

        if(!$productId || !$email){
            throw new \InvalidArgumentException('Missing required metadata in webhook');
        }
        /** @var ProductSender $sender */
        $this->sender->send(
            new Email($email),
            $this->productRepository->get(new Id($paymentWebHookData->getMetadata('productId')))
        );
    }

}