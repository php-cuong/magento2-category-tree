<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-11-26 11:14:56
 * @Last Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last Modified time: 2017-11-26 11:15:06
 */

namespace PHPCuong\CategoryTree\Model\Config\Source\Backend;

use Magento\Framework\Exception\LocalizedException;

class CategoryTreeValidation extends \Magento\Framework\App\Config\Value
{
    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->categoryRepository = $categoryRepository;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        if (empty($value)) {
            $this->_dataSaveAllowed = true;
        } else {
            if (!$this->isCategory($value)) {
                throw new LocalizedException(__('We can\'t save the Category Tree.'));
            }
        }
        return $this;
    }

    /**
     * @param string $categoryId
     * @return boolean|int
     */
    public function isCategory($categoryId)
    {
        try {
            $this->categoryRepository->get($categoryId);
            return $categoryId;
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {}

        return false;
    }
}
