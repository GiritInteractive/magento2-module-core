<?php
/**
 * Girit-Interactive
 * http://www.girit-tech.com
 * +972-3-9177788
 * info@girit.biz
 *
 * @package     Girit_Core
 * @author      Pniel Cohen <pini@girit.biz>
 * @license     https://opensource.org/licenses/OSL-3.0
 * @copyright   © 2018 Girit-Interactive (https://www.girit-tech.com/). All rights reserved.
 */

namespace Girit\Core\Model;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class EavConfig extends \Magento\Eav\Model\Config
{

    /**
     * Reset object state
     *
     * @return $this
     */
    public function softClear()
    {
        $this->_entityTypeData = null;
        $this->_attributeData = null;
        $this->_objects = null;
        $this->_references = null;
        $this->_attributeCodes = null;
        return $this;
    }
}
