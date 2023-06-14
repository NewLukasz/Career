<?php

namespace STLK\Career\Controller\Application;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\App\RequestInterface;


class Submit implements HttpPostActionInterface
{
    public function __construct(
        private RequestInterface $request
    )
    {
    }

    /**
     * @return Forward
     */
    public function execute() : Forward
    {

        $post = $this->request->getParams();

        echo "<pre>";
        print_r($post);
        exit;

    }
}
