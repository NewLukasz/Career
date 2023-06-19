<?php
declare(strict_types=1);

namespace STLK\Career\ViewModel\Application;

use STLK\Career\Model\Config\ConfigProvider;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\App\RequestInterface;

class FormViewModel implements ArgumentInterface
{
    /**
     * @param RequestInterface $request
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        private RequestInterface $request,
        private ConfigProvider $configProvider
    )
    {
    }

    /**
     * @return array
     */
    public function getOpenPositions() : array
    {
        return $this->configProvider->getOpenPositions();
    }

    /**
     * @return bool
     */
    public function checkIfFormSubmittedWithSuccess() : bool
    {
        $params = $this->request->getParams();
        if(!isset($params['result'])){
            return false;
        }
        return $params['result']==='success';
    }

    /**
     * @param string $stringWithSpaces
     * @return string
     */
    public function changeSpacesToUnderscoreForDivValue(string $stringWithSpaces) : string
    {
        return str_replace(' ','_',$stringWithSpaces);
    }
}
