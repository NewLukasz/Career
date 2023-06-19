<?php
declare(strict_types=1);

namespace STLK\Career\Service;

use STLK\Career\Api\Service\EmailServiceInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Framework\App\AreaInterface;
use Magento\Framework\App\AreaList;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Magento\Framework\Escaper;
use Exception;

use Magento\Framework\Translate\Inline\StateInterface;

class EmailService implements EmailServiceInterface
{
    public function __construct(
        private StoreRepositoryInterface $storeRepository,
        private Escaper                  $escaper,
        private AreaList                 $areaList,
        private State                    $appState,
        private StateInterface           $inlineTranslation,
        private TransportBuilder         $transportBuilder
    )
    {
    }

    /**
     * @param string $templateId
     * @param array $receiversEmails
     * @param string $senderEmail
     * @param string $senderName
     * @param array $emailVars
     * @return bool
     */
    public function send(
        string $templateId,
        array  $receiversEmails,
        string $senderEmail,
        string $senderName = '',
        array  $emailVars = []
    ): bool
    {
        try{
            $storeId = $this->getStoreId();

            $templateOptions = [
                'area' => Area::AREA_FRONTEND,
                'store' => $storeId
            ];

            $sender = $this->getSender(
                $senderName,
                $senderEmail
            );

            $area = $this->areaList->getArea($this->appState->getAreaCode());
            $area->load(AreaInterface::PART_TRANSLATE);

            $this->inlineTranslation->suspend();
            $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars($emailVars)
                ->setFromByScope($sender,$storeId)
                ->addTo($this->getEmailTo($receiversEmails));

            $this->transportBuilder->getTransport()->sendMessage();
            $this->inlineTranslation->resume();
        } catch(Exception $e) {
            return false;
        }

        return true;

    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    private function getStoreId(): int
    {
        $store = $this->storeRepository->get('default');
        return (int)$store->getId();
    }

    /**
     * @param string $name
     * @param string $email
     * @return array
     * @throws LocalizedException
     */
    private function getSender(string $name, string $email): array
    {
        if (!$name) {
            $senderName = $this->escaper->escapeHtml($email);
        } else {
            $senderName = $this->escaper->escapeHtml($name);
        }

        $senderEmail = $this->escaper->escapeHtml($email);

        if (!$senderName || !$senderEmail) {
            throw new LocalizedException(
                __('Wrong sender information')
            );
        }

        return [
            'name' => $senderName,
            'email' => $senderEmail
        ];

    }

    /**
     * @param array $receiversEmails
     * @return string
     * @throws LocalizedException
     */
    private function getEmailTo(array $receiversEmails) : string
    {
        if(empty($receiversEmails) || !isset($receiversEmails[0])){
            throw new LocalizedException(
                __('Receivers email address missing')
            );
        }
        return (string)$receiversEmails[0];
    }
}
