<?php
declare(strict_types=1);

namespace STLK\Career\Api\Data;

interface ConfigProviderInterface
{
    /**
     * Module configuration
     */
    const MODULE_ENABLED = 'stlk_career/general/enable';
    const OPEN_POSITIONS = 'stlk_career/general/open_positions';

    /**
     * Emails configuration
     */
    const SENDER_NAME = 'stlk_career/email_config/sender_name';
    const SENDER_EMAIL = 'stlk_career/email_config/sender_email';
    const RECRUITMENT_EMAIL_ADDRESSES = 'stlk_career/email_config/recruitment_email_addresses';

    /**
     * Separators
     */
    const EMAIL_SEPARATOR = ';';
    const OPEN_POSITIONS_SEPARATOR = ';';
}
