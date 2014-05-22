<?php
/*
 * Ladybug: Simple and Extensible PHP Dumper
 *
 * Mysql/Result dumper
 *
 * (c) Raúl Fraile Beneyto <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ladybug\Plugin\Extra\Inspector\Resource\Php;

use Ladybug\Inspector\AbstractInspector;
use Ladybug\Inspector\InspectorInterface;
use Ladybug\Model\VariableWrapper;
use Ladybug\Plugin\Extra\Type\TableType;

class MysqlResult extends AbstractInspector
{

    /**
     * @inheritdoc
     */
    public function supports(VariableWrapper $data)
    {
        return VariableWrapper::TYPE_RESOURCE == $data->getType() &&
            'mysql result' === $data->getId();
    }

    /**
     * @inheritdoc
     */
    public function get(VariableWrapper $data)
    {
        if (!$this->supports($data)) {
            throw new \Ladybug\Exception\InvalidInspectorClassException();
        }

        $headers = array();
        $rows = array();
        $first = true;
        while ($row = mysql_fetch_assoc($data->getData())) {

            $rowData = array();
            foreach ($row as $k => $v) {
                if ($first) {
                    $headers[] = $k;
                }
                $rowData[] = $v;
            }

            $rows[] = $rowData;
            $first = false;
        }

        /** @var $table TableType */
        $table = $this->extendedTypeFactory->factory('table', $this->level);

        $table->setHeaders($headers);
        $table->setRows($rows);
        $table->setTitle('MySQL resultset');

        return $table;
    }
}
