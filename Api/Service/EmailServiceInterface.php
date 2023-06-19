<?php
declare(strict_types=1);

namespace STLK\Career\Api\Service;

interface EmailServiceInterface
{
    const EMAIL_WITH_APPLICATION_TEMPLATE_ID = 'stlk_career_email_with_application';

    /**
     * Email sending function.
     *
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
    ): bool;
}
