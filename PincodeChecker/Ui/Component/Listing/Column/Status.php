<?php

namespace AmeshExtensions\PincodeChecker\Ui\Component\Listing\Column;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class status
 */
class Status implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $status = [
            ['label' => 'Disabled', 'value' => 0],
            ['label' => 'Enabled', 'value' => 1]
        ];

        array_walk(
            $status,
            function (&$item) {
                $item['__disableTmpl'] = true;
            }
        );

        return $status;
    }
}
