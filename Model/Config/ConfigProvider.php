<?php
declare(strict_types=1);

namespace STLK\Career\Model\Config;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use STLK\Career\Api\Data\ConfigProviderInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;

class ConfigProvider extends AbstractHelper implements ConfigProviderInterface
{
    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        private StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getBaseUrl() : string
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }

    /**
     * @return bool
     */
    public function isEnabled() : bool
    {
        return $this->scopeConfig->isSetFlag(
            self::MODULE_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getSenderName() : string
    {
        return $this->scopeConfig->getValue(
            self::SENDER_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getSenderEmail() : string
    {
        return $this->scopeConfig->getValue(
            self::SENDER_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return array
     */
    public function getRecruitmentEmailAddresses() : array
    {
        $addresses = $this->scopeConfig->getValue(
            self::RECRUITMENT_EMAIL_ADDRESSES,
            ScopeInterface::SCOPE_STORE
        );

        return $this->getArrayDataFromString($addresses, self::EMAIL_SEPARATOR);
    }

    /**
     * @return array
     */
    public function getOpenPositions() : array
    {
        $positions = $this->scopeConfig->getValue(
            self::OPEN_POSITIONS,
            ScopeInterface::SCOPE_STORE
        );

        return $this->getArrayDataFromString($positions, self::OPEN_POSITIONS_SEPARATOR);
    }

    /**
     * Auxiliary function.
     *
     * @param mixed $data
     * @param string $separator
     * @return array
     */
    private function getArrayDataFromString(string|null $data, string $separator): array
    {
        if (!$data) {
            return [];
        }
        $resultArray = array_filter(explode($separator, $data));
        return $this->removeBlankFromBeginOfStringElements($resultArray);
    }


    /**
     * Auxiliary function.
     *
     * @param array $array
     * @return array
     */
    private function removeBlankFromBeginOfStringElements(array $array): array
    {
        foreach ($array as &$string) {
            $string = $this->trimFirstSignIfBlank($string);
        }
        return $array;
    }

    /**
     * @param string $string
     * @return string
     */
    private function trimFirstSignIfBlank(string $string): string
    {
        return $string[0] === ' ' ? ltrim($string, $string[0]) : $string;
    }
}
