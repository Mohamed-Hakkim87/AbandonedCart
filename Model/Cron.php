<?php

namespace Hikmadh\AbandonedCart\Model;

class Cron
{
    /**
     * QuoteFactory
     *
     * @var Sales\QuoteFactory
     */
    public $quoteFactory;

    /**
     * HelperData
     *
     * @var \Risecommerce\AbandonedCart\Helper\Data
     */
    public $helper;

    /**
     * CollectionFactory
     *
     * @var ResourceModel\Cron\CollectionFactory
     */
    public $cronCollection;


    public function __construct(
        \Hikmadh\AbandonedCart\Model\Sales\QuoteFactory $quoteFactory,
        \Hikmadh\AbandonedCart\Helper\Data $helper,
        \Hikmadh\AbandonedCart\Model\ResourceModel\Cron\CollectionFactory $cronCollection
    ) {
        $this->quoteFactory = $quoteFactory;
        $this->helper = $helper;
        $this->cronCollection = $cronCollection;
    }

    /**
     * CRON FOR ABANDONED CARTS.
     *
     * @return null
     */
    public function abandonedCarts()
    {
        if ($this->jobHasAlreadyBeenRun('hikmadh_abandoned_cart')) {
            $this->helper->log('Skipping hikmadh_abandoned_cart job run');
            return;
        }
        $this->quoteFactory->create()->processAbandonedCarts();
    }

    /**
     * Check if already ran for same time
     *
     * @param string $jobCode jobCode
     *
     * @return bool
     */
    public function jobHasAlreadyBeenRun($jobCode)
    {
        $currentRunningJob = $this->cronCollection->create()
            ->addFieldToFilter('job_code', $jobCode)
            ->addFieldToFilter('status', 'running')
            ->setPageSize(1);

        if ($currentRunningJob->getSize()) {
            $jobOfSameTypeAndScheduledAtDateAlreadyExecuted =  $this->cronCollection->create()
                ->addFieldToFilter('job_code', $jobCode)
                ->addFieldToFilter('scheduled_at', $currentRunningJob->getFirstItem()->getScheduledAt())
                ->addFieldToFilter('status', ['in' => ['success', 'failed']]);

            return ($jobOfSameTypeAndScheduledAtDateAlreadyExecuted->getSize()) ? true : false;
        }

        return false;
    }
}
