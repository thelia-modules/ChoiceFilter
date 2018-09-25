<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace ChoiceFilter;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Module\BaseModule;
use Thelia\Install\Database;

/**
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class ChoiceFilter extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'choicefilter';

    public function preActivation(ConnectionInterface $con = null)
    {
        if (!$this->getConfigValue('is_initialized', false)) {
            $database = new Database($con);

            $database->insertSql(null, array(__DIR__ . '/Config/thelia.sql', __DIR__ . '/Config/insert.sql'));

            $this->setConfigValue('is_initialized', true);
        }

        return true;
    }
}
