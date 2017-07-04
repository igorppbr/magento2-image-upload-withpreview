<?php

namespace Igorludgero\Brandlist\Controller\Adminhtml\Brand;

use Magento\Framework\Controller\ResultFactory;

class Upload extends \Magento\Backend\App\Action {

    /**
     * Image uploader
     *
     * @var \Igorludgero\Brandlist\Model\ImageUploader
     */
    protected $imageUploader;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Igorludgero\Brandlist\Model\ImageUploader $imageUploader
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Igorludgero\Brandlist\Model\ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    /**
     * Check admin permissions for this controller
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Igorludgero_Brandlist::edit');
    }

    public function execute()
    {
        try {
            $result = $this->imageUploader->saveFileToTmpDir('image');

            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }

}