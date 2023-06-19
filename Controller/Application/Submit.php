<?php
declare(strict_types=1);

namespace STLK\Career\Controller\Application;

use STLK\Career\Api\Service\EmailServiceInterface;
use STLK\Career\Service\EmailService;
use STLK\Career\Model\Config\ConfigProvider;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\Result\Redirect;


class Submit implements HttpPostActionInterface
{
    public function __construct(
        private RequestInterface $request,
        private EmailService $emailService,
        private ConfigProvider $configProvider,
        private RedirectFactory $redirectFactory
    )
    {
    }

    /**
     * @return Redirect
     * @throws NoSuchEntityException
     */
    public function execute() : Redirect
    {
        $redirect = $this->redirectFactory->create();

        if(!$this->configProvider->isEnabled()){
            $redirect->setUrl($this->configProvider->getBaseUrl());
        }

        $params = $this->request->getParams();
        $recipients = $this->configProvider->getRecruitmentEmailAddresses();

        if(!$this->sendEmailsWithApplication($params,$recipients)){
            return $redirect->setPath(
                '*/*/form',
                ['result' => 'fail']
            );
        }

        $redirect = $this->redirectFactory->create();
        return $redirect->setPath(
            '*/*/form',
            ['result' => 'success']
        );
    }

    /**
     * @param array $params
     * @param array $recipients
     * @return bool
     */
    private function sendEmailsWithApplication(array $params, array $recipients) : bool
    {
        foreach($recipients as $recipient){
            $sendingStatus = $this->emailService->send(
                EmailServiceInterface::EMAIL_WITH_APPLICATION_TEMPLATE_ID,
                [$recipient],
                $this->configProvider->getSenderEmail(),
                $this->configProvider->getSenderName(),
                $this->prepareEmailData($params)
            );
            if(!$sendingStatus){
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $params
     * @return array
     */
    private function prepareEmailData(array $params) : array
    {
        return [
          'name' => $params['name'],
          'surname' => $params['surname'],
          'email' => $params['email'],
          'position' => $this->removeUnderscoresFromString($params['position']),
          'message' => $params['message']
        ];
    }

    /**
     * @param string $string
     * @return string
     */
    private function removeUnderscoresFromString(string $string) : string
    {
        return str_replace('_', ' ', $string);
    }
}
